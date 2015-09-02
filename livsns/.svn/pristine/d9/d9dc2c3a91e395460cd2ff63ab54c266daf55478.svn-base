<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  FavorUpdate extends adminUpdateBase
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
        if(!isset($this->input['tag_id']))
        {
            $this->errorOutput("NO_TAG_ID");
        }
        //boke中视频表的id
        if(!isset($this->input['video_id']))
        {
            $this->errorOutput("NO_VIDEO_ID");
        }
        
        $result = $this->exist_favor($tag_id,$video_id);
        if($result[0]['total'])
        {
            $this->errorOutput("FAVOR_EXIST");
        }

        $parmas['tag_id'] = intval($this->input['tag_id']);
        $parmas['video_id'] = intval($this->input['video_id']);
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
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = intval($this->input['id']);
        
        $cond = $this->get_condition()." AND `id` in ($id) ";
        //删除tag中的数据
        $re = $this->obj->delete($this->tbname,$cond);
        
        $cond = $this->get_condition()." AND `tag_id` in ($id) ";
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
$out = new FavorUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
