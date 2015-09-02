<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'global.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class mk_contentApi extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include site.class.php
     */
    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include(CUR_CONF_PATH . 'lib/content.class.php');
        $this->obj        = new content();
        include_once(ROOT_PATH . 'lib/class/mkpublish.class.php');
        $this->mk         = new mkpublish();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '内容页面生成',
            'brief' => '内容页面生成',
            'space' => '1', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function check_mk_publish_content_by_con()
    {
        $max  = 1;
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_publish_time ORDER BY id LIMIT " . $max;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $plans[] = $row;
            $ids[]   = $row['id'];
        }
        if (!$ids)
        {
            echo "NO_PLAN";
            exit;
        }
        $sql = "delete from " . DB_PREFIX . "content_publish_time where id in (" . implode(',', $ids) . ")";
        $this->db->query($sql);

        foreach ($plans as $k => $v)
        {
            $this->check_mk_publish_content($v);
        }
    }

    public function check_mk_publish_content($data = array())
    {
        if (!$data)
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "content_publish_time ORDER BY id LIMIT 1";
            $data = $this->db->query_first($sql);
            if (empty($data))
            {
                echo "没有可执行计划";
                exit;
            }
            $sql = "DELETE FROM " . DB_PREFIX . "content_publish_time WHERE id=" . $data['id'];
            $this->db->query($sql);
        }

        if ($this->settings['App_mk_publish_content'])
        {
            $this->curl = new curl($this->settings['App_mk_publish_content']['host'], $this->settings['App_mk_publish_content']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('html', true);
            $this->curl->addRequestData('cmid', $data['content_id']);
            $this->curl->request('mk_publish_content.php');
        }

        if ($this->settings['is_syn_clouds'])
        {
            $content_data = $this->obj->get_all_content_by_relationid($data['content_id']);
            include_once(CUR_CONF_PATH . 'lib/content_syn.class.php');
            $content_syn  = new content_syn();
            if (!$content_data)
            {
                $content_syn->delete_syn_content($data['content_id']);
            }
            else
            {
                $content_syn->content($data['content_id']);
            }
        }

        if ($this->settings['App_mkpublish'])
        {
            $content_data = $content_data?$content_data:$this->obj->get_all_content_by_relationid($data['content_id'],true);
            if ($this->settings['is_need_audit'])
            {
                if ($content_data && is_array($content_data))
                {
                    if ($content_data['status'] != 1)
                    {
                        $this->mk->del_publish(array('rid' => $data['content_id']));
                        //请求访问统计，更改对应已被删除内容
                        if ($data['cid'])
                        {
                            @include(ROOT_PATH . 'lib/class/access.class.php');
                            if (class_exists('access'))
                            {
                                $access = new access();
                                $access->delete($data['cid']);
                            }
                        }
                        @include DATA_DIR . 'hooks/publish.php';
                        exit;
                    }
                }
            }
            if (!$content_data)
            {
                $this->mk->del_publish(array('rid' => $data['content_id']));
                //请求访问统计，更改对应已被删除内容
                if ($data['cid'])
                {
                    @include(ROOT_PATH . 'lib/class/access.class.php');
                    if (class_exists('access'))
                    {
                        $access = new access();
                        $access->delete($data['cid']);
                    }
                }
                @include DATA_DIR . 'hooks/publish.php';
                exit;
            }

            $sql          = "SELECT id FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $content_data['bundle_id'] . "' AND module_id='" . $content_data['module_id'] . "' AND content_type!='' AND struct_ast_id='' ORDER  BY id";
            $content_type = $this->db->query_first($sql);

            $plan['title']             = $content_data['title'];
            $plan['site_id']           = $content_data['site_id'];
            $plan['page_data_id']      = $content_data['column_id'];
            $plan['content_type']      = intval($content_type['id']);
            $plan['template_sign']     = $content_data['template_sign'];
            $plan['rid']               = $content_data['id'];
            $plan['content_type_sign'] = $content_data['bundle_id'];
            $plan['client_type']       = 2;
            $plan['content_detail']         = array(
                'content_url' => $content_data['content_url'],
                'id' => $content_data['id'],
                'rid' => $content_data['id'],
                'cid' => $content_data['cid'],
                'content_fromid' => $content_data['content_fromid'],
                'file_name' => $content_data['file_name'],
                'template_sign' => $content_data['template_sign'],
                'content_type_sign' => $content_data['bundle_id'],
                'column_id' => $content_data['column_id'],
                'site_id' => $content_data['site_id'],
            );
            $error_data                = $plan;

            //取栏目的page_id
            include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
            $this->pub_sys = new publishsys();
            $page_data     = $this->pub_sys->get_page_by_sign('column', $content_data['site_id']);
            if (!$page_data['id'])
            {
                //插入错误日志；
                $error_data['failure'] = '模板->页面管理：可能没有栏目页面类型，或者栏目配置错误';
                $this->insert_error_log($error_data);
                return;
            }
            $plan['page_id'] = $page_data['id'];

            if ($content_data['template_sign'] && in_array($content_data['bundle_id'], array('special')))
            {
                //查询页面类型id
                $page_data_c = $this->pub_sys->get_page_by_sign($content_data['bundle_id']);
                if ($page_data_c['id'])
                {
                    $plan['page_data_id_c'] = $content_data['content_fromid'];
                    $plan['page_id_c']      = $page_data_c['id'];
                }
                else
                {
                    $plan['template_sign'] = '';
                    //插入错误日志；
                    $error_data['failure'] = '模板->页面管理：可能没有专题页面类型，或者专题配置，专题添加参数错误';
                    $this->insert_error_log($error_data);
                    return;
                }
            }
            else
            {
                $plan['template_sign'] = '';
            }
            $this->mk->mk_publish($plan);
            @include DATA_DIR . 'hooks/publish.php';
        }
    }

    public function mk_content_by_rid($rid = '')
    {
        if ($rid)
        {
            $data['content_id'] = $rid;
            $allmk              = true;
        }
        else
        {
            $data['content_id'] = intval($this->input['rid']);
        }
        if (!$data['content_id'])
        {
            if ($this->input['rewrite'])
            {
                exit;
            }
            echo "没有查询的id";
            exit;
        }
        if ($this->settings['App_mkpublish'])
        {
            include_once(CUR_CONF_PATH . 'lib/column.class.php');
            $this->column = new column();
            $content_data = $this->obj->get_all_content_by_relationid($data['content_id']);

            if (!$content_data && $this->input['rewrite'])
            {
                exit;
            }
            $column_data                 = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $content_data['column_id']);
            $site_data                   = $column_data['site_data'];
            unset($column_data['site_data']);
            $content_data['content_url'] = $content_data['outlink'] ? $content_data['outlink'] : mk_content_url($site_data, $column_data, $content_data);
            $content_data['column_info'] = $column_data;
            if (!$content_data)
            {
				if ($this->input['rewrite'])
				{
					exit;
				}
                if ($allmk)
                {
                    if ($this->input['rewrite'])
                    {
                        exit;
                    }
                    return "这条内容已打回" . "<br>";
                }
                else
                {
                    if ($this->input['rewrite'])
                    {
                        exit;
                    }
                    echo "这条内容已打回";
                    exit;
                }
            }

            if (!$content_data)
            {
                $this->mk->del_publish(array('rid' => $data['content_id']));
                exit;
            }

            $sql          = "SELECT id FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $content_data['bundle_id'] . "' AND module_id='" . $content_data['module_id'] . "' AND content_type!='' AND struct_ast_id='' ORDER  BY id";
            $content_type = $this->db->query_first($sql);

            $plan['title']             = $content_data['title'];
            $plan['site_id']           = $content_data['site_id'];
            $plan['page_data_id']      = $content_data['column_id'];
            $plan['content_type']      = intval($content_type['id']);
            $plan['template_sign']     = $content_data['template_sign'];
            $plan['rid']               = $content_data['id'];
            $plan['content_type_sign'] = $content_data['bundle_id'];
            $plan['client_type']       = 2;
            $plan['content_detail']         = array(
                'content_url' => $content_data['content_url'],
                'id' => $content_data['id'],
                'rid' => $content_data['rid'],
                'cid' => $content_data['cid'],
                'content_fromid' => $content_data['content_fromid'],
                'file_name' => $content_data['file_name'],
                'template_sign' => $content_data['template_sign'],
                'content_type_sign' => $content_data['content_type_sign'],
                'column_id' => $content_data['column_id'],
                'site_id' => $content_data['site_id'],
            );
            $error_data                = $plan;

            //取栏目的page_id
            include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
            $this->pub_sys = new publishsys();
            $page_data     = $this->pub_sys->get_page_by_sign('column', $content_data['site_id']);
            if (!$page_data['id'])
            {
                //插入错误日志；
                $error_data['failure'] = '模板->页面管理：可能没有栏目页面类型，或者栏目配置错误';
                $this->insert_error_log($error_data);
                return;
            }
            $plan['page_id'] = $page_data['id'];

            if ($content_data['template_sign'])
            {
                //查询页面类型id
                $page_data_c = $this->pub_sys->get_page_by_sign($content_data['bundle_id']);
                if (!$page_data_c['id'])
                {
                    return;
                }
                if ($page_data_c['id'])
                {
                    $plan['page_data_id_c'] = $content_data['content_fromid'];
                    $plan['page_id_c']      = $page_data_c['id'];
                }
                else
                {
                    $plan['template_sign'] = '';
                    //插入错误日志；
                    $error_data['failure'] = '模板->页面管理：可能没有专题页面类型，或者专题配置，专题添加参数错误';
                    $this->insert_error_log($error_data);
                    return;
                }
            }
            if ($allmk)
            {
                $plan['publish_time'] = TIMENOW;
                $plan['title']        = $content_data['title'];
                $this->mk->insert_plan($plan);
            }
            else
            {
                $this->mk->mk_publish($plan);
            }
            if ($this->input['rewrite'])
            {
            	header('Location: ' . $content_data['content_url']);
                //echo @file_get_contents($content_data['content_url']);
                exit;
            }
            if ($allmk)
            {
                return $content_data['content_url'] . '<br>';
            }
            else
            {
                echo json_encode($content_data);
                return;
            }
        }
        echo "未安装生成发布";
        exit;
    }

    //没有生成的页面重新生成
    public function remk_content()
    {
        set_time_limit(0);
        $sql  = 'select id from ' . DB_PREFIX . 'content_relation order by id asc limit 200';
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $mkresult = $this->mk->check_is_mk($row['id']);
            if (!$mkresult)
            {
                $result = $this->mk_content_by_rid($row['id']);
                echo $result . str_repeat(' ', 4096);
                ob_flush();
            }
            else
            {
                echo $row['id'] . "已生成<br>" . str_repeat(' ', 4096);
                ob_flush();
            }
        }
    }

    private function insert_error_log($data)
    {

        $this->mk->insert_error_log($data);
    }

}

$out    = new mk_contentApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_mk_publish_content_by_con';
}
$out->$action();
?>
