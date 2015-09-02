<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  FavorUpdateAPI extends outerUpdateBase
{
    private $tbname = 'favor';
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
        //boke中视频表的id
        if(!isset($this->input['video_id']))
        {
            $this->errorOutput("NO_VIDEO_ID");
        }
        
        $params['video_id'] = intval($this->input['video_id']);
        
        $cond = " where 1 and id=".$params['video_id'];
        $vinfo = $this->obj->detail('video',$cond);     //视频信息
        
        $param['create_user_id'] = $vinfo['user_id'];
        $param['create_user_name'] = $vinfo['user_name'];
        
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
    private function exist_favor($tag_id,$video_id)
    {
        $cond = " WHERE 1 AND `tag_id`=$tag_id AND `video_id`=$video_id";
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
        if (empty ($this->input['video_id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = intval($this->input['video_id']);
        
        $cond = $this->get_condition()." AND `video_id` in ($id) AND `user_id`=".$this->user['user_id'];
        //删除tag中的数据
        $re = $this->obj->delete($this->tbname,$cond);
        
       // $cond = $this->get_condition()." AND `tag_id` in ($id) AND `user_id`=".$this->user['user_id'];
        //删除favor中的数据
        //$re = $this->obj->delete('favor',$cond);
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
$out = new FavorUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
