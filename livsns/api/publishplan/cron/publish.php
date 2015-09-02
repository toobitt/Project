<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'publishplan'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');

class publishApi extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include news.class.php
     */
    public function __construct()
    {
        parent::__construct();
        $this->pubplan     = new publishplan();
        $this->pub_content = new publishcontent();
        include(CUR_CONF_PATH . 'lib/publish.class.php');
        $this->obj         = new publish();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '发布内容',
            'brief' => '发布内容',
            'space' => '1', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function check_plan_by_con()
    {
        $max  = 1;
        $sql  = "SELECT p.*,s.*,s.id as sid,p.id as pid FROM " . DB_PREFIX . "plan p LEFT JOIN " . DB_PREFIX . "plan_set s ON p.set_id=s.id WHERE publish_time<=" . TIMENOW . " ORDER BY p.publish_time,p.id LIMIT " . $max;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $plans[] = $row;
            $ids[]   = $row['pid'];
        }
        if (!$ids)
        {
            echo "NO_PLAN";
            exit;
        }
        $sql = "delete from " . DB_PREFIX . "plan where id in (" . implode(',', $ids) . ")";
        $this->db->query($sql);
        foreach ($plans as $k => $v)
        {
            $this->check_plan($v);
        }
    }

    public function check_plan($plan = array())
    {
        if (!$plan)
        {
            //取出最前面一条发布队列
            $plan = $this->obj->get_plan_first();
            if (empty($plan))
            {
                echo "NO_PLAN";
                exit;
            }

            //删除这条队列
            if (!$this->obj->delete_plan($plan['pid']))
            {
                exit;
            }
        }


        //判断当前操作属于插入，删除，更新
        switch ($plan['action_type'])
        {
            case 'insert' : $result = $this->insert_content($plan);
                break;
            case 'delete' : $result = $this->delete_content($plan);
                break;
            case 'update' : $result = $this->update_content($plan);
                break;
        }

        //插入这条队列到日志
        $plan['status'] = $result ? 1 : 2;
        if (!$plan['fid'])
        {
            $this->obj->insert_log($plan);
        }
    }

    public function insert_content($plan)
    {
        //查看是否有子级内容
        $child_set_other = $this->obj->get_child_set($plan['sid']);

        //到对应接口取发布的内容,内容格式：二维数组 多条内容 每条内容所有的参数就是发布系统里需要的参数
        $this->pubplan->setAttribute($plan['host'], $plan['path'], $plan['filename'], 'get_content');
        $contentdata = $this->pubplan->get_content($plan['from_id'], $plan['sort_id'], $plan['offset'], $plan['num']);

        if (empty($contentdata) || !is_array($contentdata))
        {
            //将子级插入五条到主内容表中
            $this->insert_childs_to_content($plan, $child_set_other);

            //子级全部发布后更新发布内容主内容is_complate(因为如果没有子级，则不进入发布操作，只能在最后再次请求)
            $this->update_is_complete($plan, $child_set_other);
            return false;
        }

        //将内容进行发布
        foreach ($contentdata as $key => $value)
        {
            $plan['content_fromid'] = $value['content_fromid'];
            if (!is_array($value))
            {
                continue;
            }

            $make_plan = false;      //只有主内容第一次发布，才会有生成自己发布任务
            //如果是主内容 并且不需要发子级 表示发布过去即成功结束
            if (!$plan['struct_ast_id'])
            {
                if ($child_set_other)
                {
                    if (!$plan['is_publish_child'])
                    {
                        $value['is_complete'] = 1;
                    }
                }
                else
                {
                    $value['is_complete'] = 1;
                }
            }

            //内容发布到发布系统里  遍历value 加上配置里的属性 column_id,应用标识
            $value['plan_set_id']   = $plan['id'];
            $value['column_id']     = $plan['column_id'];
            $value['bundle_id']     = $plan['bundle_id'];
            $value['module_id']     = $plan['module_id'];
            $value['struct_id']     = $plan['struct_id'];
            $value['struct_ast_id'] = $plan['struct_ast_id'];
            $value['publish_time']  = $plan['publish_time'];
            $value['publish_user']  = $plan['publish_user'];
            $expand                 = $this->pub_content->insert_content($value);

            //发布后的内容id传给各自模块的接口记录
            $expand_data = array(
                'column_id' => $plan['column_id'],
                'from_id' => $value['content_fromid'],
                'expand_id' => $expand['expand_id'],
                'content_url' => $expand['content_rid'], //此content_url是传的内容关联表id
            );

            $this->pubplan->setAttribute($plan['host'], $plan['path'], $plan['filename'], 'update_content');
            $this->pubplan->insert_pub_content_id($expand_data);

            if (is_array($expand['content_rid']))
            {
                foreach ($expand['content_status'] as $crk => $crv)
                {
                    if ($crv)
                    {
                        //如果是主内容 子级任务全部记录主内容的发布系统id
                        $content_rid = $plan['struct_ast_id'] ? '' : $expand['content_rid'][$crk];
                        $make_plan   = true;
                    }
                }
            }

            //当内容之前已经发布过，再次发布到另外一个栏目时，不生成子任务
            if (!$make_plan && !$plan['struct_ast_id'])
            {
                continue;
            }

            //内容发布后，查找各自内容是否有子级，如果有，则添加每条内容的队列
            //查询出plan_set子级
            if (!empty($plan['is_publish_child']))
            {
                if (!empty($child_set_other))
                {
                    foreach ($child_set_other as $k => $v)
                    {
                        $newchildplan = array(
                            'fid' => $plan['pid'],
                            'set_id' => $v['id'],
                            'from_id' => $value['content_fromid'],
                            'sort_id' => $plan['sort_id'],
                            'column_id' => $plan['column_id'],
                            'title' => $plan['title'],
                            'action_type' => 'insert',
                            'offset' => 0,
                            'publish_time' => $plan['publish_time'],
                            'publish_user' => $plan['publish_user'],
                            'ip' => $plan['ip'],
                            'status' => $plan['status'],
                            'content_rid' => $content_rid ? $content_rid : $plan['content_rid'],
                        );
                        $this->obj->insert_plan($newchildplan);
                    }
                }
            }
        }

        //插入剩余数的新计划  $offset 才开始为0  如果offset为no，表示没有内容了
//		$offset = count($contentdata)<$plan['num']?'no':($plan['offset']+count($contentdata));
        $is_make_child_plan = count($contentdata) < $plan['num'] ? false : true;
        if ($is_make_child_plan)
        {
            $newplan = array(
                'set_id' => $plan['sid'],
                'from_id' => $plan['from_id'],
                'sort_id' => $plan['sort_id'],
                'column_id' => $plan['column_id'],
                'title' => $plan['title'],
                'action_type' => 'insert',
                'offset' => 0,
                'publish_time' => $plan['publish_time'],
                'publish_user' => $plan['publish_user'],
                'ip' => $plan['ip'],
                'status' => $plan['status'],
                'content_rid' => $content_rid ? $content_rid : $plan['content_rid'],
            );
            $this->obj->insert_plan($newplan);
        }
        else
        {
            //将子级插入五条到主内容表中
            $this->insert_childs_to_content($plan, $child_set_other);
            //子级全部发布后更新发布内容主内容is_complate(因为如果没有子级，则不进入发布操作，只能在最后再次请求)
            $this->update_is_complete($plan, $child_set_other);
        }

        return true;
    }

    public function delete_content($plan)
    {
        $data = array(
            'bundle_id' => $plan['bundle_id'],
            'module_id' => $plan['module_id'],
            'struct_id' => $plan['struct_id'],
            'struct_ast_id' => $plan['struct_ast_id'],
            'column_id' => $plan['column_id'],
            'content_fromid' => $plan['from_id'],
            'delete_all' => $plan['delete_all'],
        );

        if ($data['struct_ast_id'])
        {
            $result = $this->pub_content->delete_child_content($data);
        }
        else
        {
            $result = $this->pub_content->delete_content($data);
        }
        //0表示不去更新各自模块的expand_id（只是减少栏目，内容还在），1表示更新（全部内容都删除了）
        if ($result['msg'] == 1)
        {
            //发布后的内容id传给各自模块的接口记录
            $expand_data = array(
                'column_id' => $plan['column_id'],
                'from_id' => $plan['from_id'],
                'expand_id' => 0,
            );
            $this->pubplan->setAttribute($plan['host'], $plan['path'], $plan['filename'], 'update_content');
            $this->pubplan->insert_pub_content_id($expand_data);
        }

        return true;
    }

    public function update_content($plan)
    {
        //到对应接口取发布的内容,内容格式：二维数组 多条内容 每条内容所有的参数就是发布系统里需要的参数
        $this->pubplan->setAttribute($plan['host'], $plan['path'], $plan['filename'], 'get_content');
        $contentdata = $this->pubplan->get_content($plan['from_id'], $plan['sort_id'], $plan['offset'], $plan['num'], true);
        $value       = $contentdata[0];

        $value['column_id']     = $plan['column_id'];
        $value['bundle_id']     = $plan['bundle_id'];
        $value['module_id']     = $plan['module_id'];
        $value['struct_id']     = $plan['struct_id'];
        $value['struct_ast_id'] = $plan['struct_ast_id'];
        $value['publish_time']  = $plan['publish_time'];
        $value['publish_user']  = $plan['publish_user'];
        //把内容发布到发布系统里  遍历value 加上配置里的属性 column_id,应用标识
        $this->pub_content->update_content($value);
        if ($plan['struct_ast_id'])
        {
            //$this->insert_childs_to_content($plan, 1);
        }
        return true;
    }

    public function update_is_complete($plan, $child_set_other)
    {
        if ($plan['struct_ast_id'])
        {
            if (empty($child_set_other))
            {
                $update_main_data = array(
                    'content_rid' => $plan['content_rid']
                );
                $this->pub_content->update_is_complete($update_main_data);
            }
        }
    }

    public function insert_childs_to_content($plan, $child_set_other)
    {
        if ($plan['struct_ast_id'])
        {
            if (empty($child_set_other))
            {
                $this->pub_content->insert_childs_to_content($plan['bundle_id'], $plan['module_id'], $plan['struct_id'], $plan['struct_ast_id'], $plan['content_rid'],$plan['content_fromid']);
            }
        }
    }

    public function test()
    {
        include_once (ROOT_PATH . 'lib/class/curl.class.php');
        $this->curl = new curl('10.0.1.40', 'livsns/api/publishplan/' . 'admin/');
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        $this->curl->addRequestData('a', 'check_plan');
        while (true)
        {
            $this->curl->request('publish.php');
            sleep(1);
        }
    }

}

$out    = new publishApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_plan_by_con';
}
$out->$action();
?>