<?php
/**
 * 专辑管理
 * 专辑分系统（前端公共使用）、用户自定义两类
 */
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  SpecialUpdate extends adminUpdateBase
{
    private $tbname = 'special';
    public function __construct()
    {
        parent::__construct();
        $this->obj = new Core();
    }
    public function __destruct()
    {
        parent::__destruct();
    }
    public function create()
    {
        if(!isset($this->input['name']))
        {
            $this->errorOutput(NO_NAME);
        }
        $name = trim($this->input['name']);
        if(!$name)
        {
            $this->errorOutput(NO_NAME);
        }  
          
        $result = $this->check_exist($name);
        if($result[0]['total'])
        {
            $this->errorOutput("NO_DATA_EXIST");
        }
        
        //专辑类别
        if(isset($this->input['type']))
        {
            $params['type'] = intval($this->input['type']);
        }
        else
        {
            $params['type'] = 2;//用户自定义
        }
            
        $params['name'] = $name;
        $params['user_id'] = $this->user['user_id'];
        $params['org_id'] = $this->user['org_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['appid'] = $this->user['appid'];
        $params['appname'] = trim(($this->user['display_name']));
        $params['create_time'] = TIMENOW;
        $params['ip'] = hg_getip();
        $params['id'] = $this->obj->insert($this->tbname,$params);
        $this->addItem($params);
        $this->output();    
            
    }
    private function check_exist($name)
    {
        $cond = " WHERE 1 AND `name`='$name' AND `user_id`=".$this->user['user_id'];
        return $this->obj->count($this->tbname,$cond);
    }
    
    public function update()
    {

    }
    public function publish()
    {
        return;
    }
    
    public function delete()
    {
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = intval($this->input['id']);
        
        $cond = $this->get_condition()." AND `id` in ($id) ";
        //删除tag中的数据
        $re = $this->obj->delete($this->tbname,$cond);
        
        $this->addItem($re);
        $this->output();
        
    }
    public function audit()
    {
        return ;
    }
    public function sort()
    {
        return ;
    }
    private function get_condition()
    {
        $cond = " WHERE 1 ";
        
        return $cond;
    }
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }


}
$out = new SpecialUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
