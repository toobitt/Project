<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  TagUpdateAPI extends adminUpdateBase
{
    private $tbname = 'tag';
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
            $this->errorOutput("NO_NAME");
        }
        $params['name'] = trim($this->input['name']);
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
    public function update()
    {
        if(!isset($this->input['id']))
        {
            $this->errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        
        $cond = $this->get_condition()." AND `id`=$id AND `user_id`=".$this->user['user_id'];
        
        if($this->input['name'])
        {
            $params['name'] = trim($this->input['name']);
        }

        $datas = $this->obj->update($this->tbname,$params,$cond);
        
        $this->addItem($datas);
        $this->output();
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
        
        $cond = $this->get_condition()." AND `id` in ($id) AND `user_id`=".$this->user['user_id'];
        //删除tag中的数据
        $re = $this->obj->delete($this->tbname,$cond);
        
        $cond = $this->get_condition()." AND `tag_id` in ($id) AND `user_id`=".$this->user['user_id'];
        //删除favor中的数据
        $re = $this->obj->delete('favor',$cond);
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
$out = new TagUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
