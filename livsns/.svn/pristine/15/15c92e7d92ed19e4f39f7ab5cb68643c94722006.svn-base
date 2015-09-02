<?php

require('global.php');
define('MOD_UNIQUEID', 'hotwords'); //模块标识
require_once(ROOT_PATH . 'lib/class/curl.class.php');

class HotwordsUpdateApi extends adminUpdateBase
{

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/hotwords.class.php');
        $this->obj = new Hotwords();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create()
    {
        $name = $this->input['name'];
        if (!$name)
        {
            $return = array(
                'error' => '请填写热词名称',
            );
            $this->addItem($return);
            $this->output();
            exit;
        }
        $sql = "select id from " . DB_PREFIX . "hotwords where name = '" . $name . "'";
        $q   = $this->db->query_first($sql);
        if ($q['id'])
        {
            $return = array(
                'error' => '热词名已存在',
            );
            $this->addItem($return);
            $this->output();
            exit;
        }

        $info = array(
            'name' => $name,
            'user_id' => $this->user['user_id'],
            'user_name' => $this->user['user_name'],
            'ip' => $this->user['ip'],
            'update_time' => TIMENOW,
            'create_time' => TIMENOW,
        );
        include(ROOT_PATH . 'lib/class/pinyin.class.php');
        $title_pinyin_result = hanzi_to_pinyin($name, false, 0);
        
        if($title_pinyin_result['word'])
        {
            $info['pinyin'] = implode('',$title_pinyin_result['word']);
        }
        
        $ret = $this->obj->create($info);
        
        $this->obj->update_hotwords(array('order_id' => $ret), 'hotwords', " id =" . $ret);

        $return = array(
            'success' => true,
            'id' => $ret,
        );
        $this->addItem($return);
        $this->output();
    }

    public function update()
    {
        $name = $this->input['name'];
        if (!$name)
        {
            $return = array(
                'error' => '请填热词名称',
            );
            $this->addItem($return);
            $this->output();
            exit;
        }

        $info = array(
            'id' => intval($this->input['id']),
            'name' => $name,
            'update_time' => TIMENOW,
        );
        
        include(ROOT_PATH . 'lib/class/pinyin.class.php');
        $title_pinyin_result = hanzi_to_pinyin($name, false, 0);
        
        if($title_pinyin_result['word'])
        {
            $info['pinyin'] = implode('',$title_pinyin_result['word']);
        }
        
        $ret  = $this->obj->update($info);

        $return = array(
            'success' => true,
            'id' => $this->input['id'],
        );
        $this->addItem($return);
        $this->output();
    }

    public function delete()
    {
        $ids = $this->input['id'];
        if (empty($ids))
        {
            $this->errorOutput('请选择需要删除的热词');
        }

        $ret = $this->obj->delete($ids);
        $this->addItem($ids);
        $this->output();
    }

    public function drag_order()
    {
        $ids       = explode(',', urldecode($this->input['content_id']));
        $order_ids = explode(',', urldecode($this->input['order_id']));
        foreach ($ids as $k => $v)
        {
            $sql = "UPDATE " . DB_PREFIX . "hotwords  SET order_id = '" . $order_ids[$k] . "'  WHERE id = '" . $v . "'";
            $this->db->query($sql);
        }

        $ids = explode(',', $this->input['content_id']);
        $this->addItem(array('id' => $ids));
        $this->output();
    }

    public function sort()
    {
        
    }

    public function audit()
    {
        $id = urldecode($this->input['id']);
        if (!$id)
        {
            $this->errorOutput("未传入热词ID");
        }
        $idArr = explode(',', $id);

        if (intval($this->input['audit']) == 1)
        {
            $this->obj->update_hotwords(array('state' => 1), 'hotwords', " id IN({$id})");
            $ret = $this->obj->get_hotwords_list(" id IN({$id})");
            if (is_array($ret) && count($ret) > 0)
            {
                foreach ($ret as $info)
                {
                    $stat_id[]        = $info['id'];
                    $stat_user_id[]   = $info['user_id'];
                    $stat_user_name[] = $info['user_name'];
                }
            }
            $return        = array('status' => 1, 'id' => $idArr);
            //审核通过
            $stat_opration = 'verify_suc';
            $opration      = '审核专题';
        }
        else if (intval($this->input['audit']) == 0)
        {
            $this->obj->update_hotwords(array('state' => 2), 'hotwords', " id IN({$id})");
            $ret = $this->obj->get_hotwords_list(" id IN({$id})");
            if (is_array($ret) && count($ret) > 0)
            {
                foreach ($ret as $info)
                {
                    $stat_id[]        = $info['id'];
                    $stat_user_id[]   = $info['user_id'];
                    $stat_user_name[] = $info['user_name'];
                }
            }
            $return = array('status' => 2, 'id' => $idArr);

            $stat_opration = 'verify_fail';
            $opration      = '打回专题';
        }

        if (!empty($stat_id))
        {
            $stat_data = array(
                'content_id' => implode(',', $stat_id),
                'contentfather_id' => '',
                'type' => $stat_opration,
                'user_id' => implode(',', $stat_user_id),
                'user_name' => implode(',', $stat_user_name),
                'before_data' => '',
                'last_data' => '',
                'num' => 1,
            );
            $this->addStatistics($stat_data);
        }

        //$this->addLogs($opration,'','',$opration . '+' . $id);	
        $this->addItem($return);
        $this->output();
    }

    public function publish()
    {
        
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new HotwordsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>