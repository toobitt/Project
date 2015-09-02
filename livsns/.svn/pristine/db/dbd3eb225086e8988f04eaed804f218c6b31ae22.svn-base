<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'videopoint');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(CUR_CONF_PATH . 'lib/videopoint.class.php');
class  videopoint_update extends outerReadBase
{
    public function __construct()
    {
        parent::__construct();
        $this->obj = new videopoint();
    }
    
    public function __destruct()
    {
        parent::__destruct();
    }
    
    public function create(){}
    public function update(){}
    public function delete(){}
    public function audit(){}
    public function sort(){}
    public function publish(){}
    

    //增加point
    //添加点 需要参数 videoid(视频id) point（点的时间戳） brief（某点的简介） 方法名addpoint
    
    public function addpoint()
    {
        if(!isset($this->input['videoid']))
            return false;
        if(!isset($this->input['point']))
            return false;
        $datas['videoid'] = intval($this->input['videoid']);
        $datas['point'] = intval($this->input['point']);
        //$datas['precent'] = $this->input['precent'];
        if(isset($this->input['brief']))
            $datas['brief'] = $this->input['brief'];
        $datas['user_id'] = $this->user['id'];
        $datas['create_time'] = TIMENOW;
        $datas['ip'] = hg_getip();
        $datas['appid'] = intval($this->user['appid']);
        $datas['appname'] = trim(($this->user['display_name']));
        $return = $this->obj->insert('point',$datas);
        $this->addItem($return);
        $this->output();
        if(!$return)
            return false;
        return true;
    }
    //修改某点 需要传递参数 id(某点id) brief(简介) 方法名updatepoint
    public function updatepoint() 
    {
        if(!isset($this->input['id']))
            return false;
        if(isset($this->input['brief']))
            $datas['brief'] = $this->input['brief'];
        $datas['update_time'] = TIMENOW;
        $cond = " where id=".intval($this->input['id']);
        $return = $this->obj->update('point',$datas,$cond);
        $this->addItem($return);
        $this->output();
        if(!$return)
            return false;
        return true;        
    }
    //删除点
    //如果删除某个视频全部的点 直接传videoid(视频id) 方法名deletepoint
    //删除某点传点的id 
    public function deletepoint()
    {
        if(isset($this->input['id']))
        {
            $re = $this->obj->delete('point','where id='.$this->input['id']);
            $this->addItem($re);
            $this->output();
            return $re;
        }
        if(isset($this->input['videoid']))
        {
            $re = $this->obj->delete('point','where videoid='.$this->input['videoid']);
            $this->addItem($re);
            $this->output();
            return $re;
        }
        return false;
    }
    

}
$out = new videopoint_update();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'addpoint';
}
else 
{
    $action = $_INPUT['a'];
}
$out->$action(); 
?>