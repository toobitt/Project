<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  CommentUpdate extends adminUpdateBase
{
    private $tbname = 'comment';
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
        if(!isset($this->input['video_id']))
        {
            $this->errorOutput("NO_VIDEO_ID");
        }
        $params['video_id'] = intval($this->input['video_id']);
        
        if(isset($this->input['title']))
        {
            $params['title'] = trim($this->input['title']);
        }
        
        if(isset($this->input['content']))
        {
            $params['content'] = trim($this->input['content']);
        }
        
        //如果参数设置state=0表示审核未通过,则数据库中该字段默认为1表示审核通过
        if(isset($this->input['state']))
        {
            $params['state'] = intval($this->input['state']);
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
        
        if(isset($this->input['title']))
        {
            $params['title'] = trim($this->input['title']);
        }
        
        if(isset($this->input['content']))
        {
            $params['content'] = trim($this->input['content']);
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
$out = new CommentUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
