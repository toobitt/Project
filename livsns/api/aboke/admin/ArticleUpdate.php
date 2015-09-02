<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  ArticleUpdate extends adminUpdateBase
{
    private $tbname = 'article';
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
        if(!isset($this->input['special_id']))
        {
            $this->errorOuptut(NO_SPECIAL_ID);
        } 
        if(!isset($this->input['id']))
        {
            $this->errorOutput(NO_VIDEO_ID);
        }
        $special_id = intval($this->input['special_id']);
        $params['special_id'] = $special_id;
        
        $video_id = intval($this->input['id']);
        
       
        $result = $this->check_exist($special_id,$video_id);
        
        if($result[0]['total'])
        {
            $this->errorOutput("NO_DATA_EXIST");
        }
        
        $video = $this->obj->detail('video'," where id=".$video_id);
        
        $params['special_id'] = $special_id;
        $params['video_id'] = $video_id;
        $params['cate_id'] = $video['cate_id'];
        $params['media_id'] = $video['video_id'];
        $params['source_type'] = $video['source_type'];
        $params['title'] = $video['title'];
        $params['brief'] = $video['brief'];
        $params['content'] = $video['content'];
        $params['img'] = $video['img'];
        
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
    private function check_exist($special_id,$video_id)
    {
        $cond = " WHERE 1 AND `special`='$special_id' AND `video_id`=".$video_id;
        return $this->obj->count($this->tbname,$cond);
    }
    
    public function update()
    {
        if(!isset($this->input['special_id']))
        {
            $this->errorOuptut(NO_SPECIAL_ID);
        } 
        if(!isset($this->input['id']))
        {
            $this->errorOutput(NO_VIDEO_ID);
        }
        $special_id = intval($this->input['special_id']);
        $params['special_id'] = $special_id;
        
        $id = intval($this->input['id']);
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
        $id = intval($this->input['id']);
        
        $cond = $this->get_condition()." AND `id` in ($id) ";
       
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
$out = new ArticleUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>
