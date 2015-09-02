<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  AdminCategoryUpdate extends adminUpdateBase
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
    
    /*
     * @describe:       新增系统分类,如果参数设置state=0,则数据库中该字段默认为1表示审核通过
     * @function:       create
     * @return:         array
     */
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
        
        //如果参数设置state=0表示审核未通过,则数据库中该字段默认为1表示审核通过
        if(isset($this->input['state']))
        {
            $params['state'] = intval($this->input['state']);
        }
        
        //如果参数设置type=2表示用户自定义,则数据库中该字段默认为1表示系统用户设置
        if(isset($this->input['type']))
        {
            $params['type'] = intval($this->input['type']);
        }
        
        if(isset($this->input['cate_mark_id']))
        {
            $params['cate_mark_id'] = intval($this->input['cate_mark_id']);
        }

        if(isset($this->input['sort_id']))
        {
            $params['sort_id'] = intval($this->input['sort_id']);
        }
        
        $params['user_id'] = $this->user['user_id'];
        $params['org_id'] = $this->user['org_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['appid'] = $this->user['appid'];
        $params['appname'] = trim(($this->user['display_name']));
        $params['create_time'] = TIMENOW;
        $params['id'] = $this->obj->insert($this->tbname,$params);
        $params['ip'] = hg_getip();
        
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
        
        $cond = " WHERE `id`=$id ";
        
        if($this->input['name'])
        {
            $params['name'] = trim($this->input['name']);
        }
        if($this->input['desc'])
        {
            $params['desc'] = trim($this->input['desc']);
        }

        //分类类别
        if(isset($this->input['cate_mark_id']))
        {
            $params['cate_mark_id'] = intval($this->input['cate_mark_id']);
        }

        if(isset($this->input['sort_id']))
        {
            $params['sort_id'] = intval($this->input['sort_id']);
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
        
        $cond = " where id in ($id) ";
        
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
$out = new AdminCategoryUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
