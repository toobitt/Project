<?php
define('MOD_UNIQUEID', 'SiteConfigUpdate');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  SiteConfigUpdate extends adminUpdateBase
{
    private $tbname = 'site_config';
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
        if(!isset($this->input['key_field']))
        {
            $this->errorOutput(NO_TYPE);
        }
        
        if(!isset($this->input['key_value']))
        {
            $this->errorOutput(NO_VALUE);
        } 
        $params['key_field'] = $this->input['key_field'];
        $params['key_value'] = $this->input['key_value'];
        $params['id'] = $this->obj->insert($this->tbname,$params);
        $this->addItem($params);
        $this->output();    
            
    }
    public function update()
    {
        if(!isset($this->input['id']))
        {
            $this->errorOutput(NO_VIDEO_ID);
        }
        $id = intval($this->input['id']);
        $params = $this->get_condition();
        $re = $this->obj->update($this->tbname,$params," where id=".$id);
        $this->addItem($re);
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
        
        $id = $this->input['id'];
        
        $cond = "WHERE 1 AND `id` in ($id) ";
       
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
        $params = array();
        
        
        if(isset($this->input['key_field']))
        {
            $params['key_field'] = $this->input['key_field'];
        }
        
        if(isset($this->input['key_value']))
        {
            $params['key_value'] = $this->input['key_value'];
        }
        
        return $params;       
    }
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }


}
$out = new SiteConfigUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
