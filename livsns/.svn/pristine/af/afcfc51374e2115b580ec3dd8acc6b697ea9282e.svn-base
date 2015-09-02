<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  FrontCategoryUpdateAPI extends outerUpdateBase
{
    private $tbname = 'cate';
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
        if(isset($this->input['desc']))
        {
            $params['desc'] = trim($this->input['desc']);
        }
        
        //数据库中已经默认值为1，为0表示未通过审核
        if(isset($this->input['state']))
        {
            $params['state'] = intval($this->input['state']);
        }
        
        //用户添加类型
        $params['type'] = 2;
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
        
        $cond = " WHERE `id`=$id AND `user_id`=".$this->user['user_id'];
        
        if($this->input['name'])
        {
            $params['name'] = trim($this->input['name']);
        }
        if($this->input['desc'])
        {
            $params['desc'] = trim($this->input['desc']);
        }

        if($this->input['state'])
        {
            $params['state'] = intval($this->input['state']);
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
        
        $cond = " where id in ($id) AND `user_id`=".$this->input['user_id'];
        
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
    
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }

}

$out = new FrontCategoryUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
