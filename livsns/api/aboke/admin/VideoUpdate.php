<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  VideoUpdate extends adminUpdateBase
{
    private $tbname = 'video';
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
        if(!isset($this->input['cate_id']))
        {
            $this->errorOutput("NO_CATEGORY_ID");
        }
        $params['cate_id'] = $this->input['cate_id'];
        $re = $this->upload();
        if(!is_array($re[0]))
        {
            $this->errorOutput("NO_VIDEO_UPLOAD");
        }
    
        //视频video_id
        $params['video_id'] = $re[0]['id'];
        //视频img
        $params['img'] = $re[0]['img']['host'].
                         $re[0]['img']['dir'].
                         $re[0]['img']['filepath'].
                         $re[0]['img']['filename'];
                         
        if(!isset($this->input['title']))
        {
            $this->errorOutput("NO_TITLE");
        }
        if(isset($this->input['title']))
        {
            $params['title'] = trim($this->input['title']);
        }
        if(isset($this->input['brief']))
        {
            $params['brief'] = trim($this->input['brief']);
        }
        if(isset($this->input['content']))
        {
            $params['content'] = trim($this->input['content']);
        }
        if(isset($this->input['type']))
        {
            $params['type'] = intval($this->input['type']);
        }
        $params['user_id'] = $this->user['user_id'];
        $params['org_id'] = $this->user['org_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['appid'] = $this->user['appid'];
        $params['appname'] = trim(($this->user['display_name']));
        $params['ip'] = hg_getip();
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
        
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
        
        $cond = " WHERE `id`=$id ";
    
        
        if($this->input['title'])
        {
            $params['title'] = trim($this->input['title']);
        }
        if($this->input['brief'])
        {
            $params['brief'] = trim($this->input['brief']);
        }
        if($this->input['content'])
        {
            $params['content'] = trim($this->input['content']);
        }
        
        //更新系统分类
        if(isset($this->input['admin_cate_id']))
        {
            $params['admin_cate_id'] = intval($this->input['admin_cate_id']);
        }

        /*
        if(isset($this->input['state']))
        {
            $params['state'] = intval($this->input['state']);
            //表示视频通过审核
            $video_id = $this->get_video_id($id);
            $this->pass($video_id,$this->input['state']);
            if($params['state']==1)
            {
                $re['state'] = 0;
            }
            else if($params['state']==0)
            {
                $re['state'] = 1;
            }
        }
        */
        //$datas['state'] = $this->obj->update($this->tbname,$params,$cond);
        $datas = $this->obj->update($this->tbname,$params,$cond);
        
        /*
        if(isset($re['state']))
        {
            $this->addItem($re);
        }
        else
        {
            $this->addItem($datas);
        }
        */
        $this->addItem($datas);
        
        $this->output();
    }
    
    
    public function publish()
    {
        
    }
    

    public function delete()
    {
        if (empty ($this->input['id']))
        {
            $this->errorOutput("NO_DATA_ID");
        }
        $id = intval($this->input['id']);
        
        //删除视频库中的视频
        $this->delete_videoonserver($this->get_video_id($id));
        
        //删除boke中的记录
        $re = $this->obj->delete($this->tbname," where id in ($id)");
        
        $this->addItem($re);
        $this->output();
    }
    
    //获取boke记录的视频video_id
    private function get_video_id($id)
    {
        $videos = $this->obj->show($this->tbname,' where id in ('.$id.')');
        $video_ids = '';
        foreach($videos as $video)
        {
            $video_ids .=$video['video_id'].',';
        }
        $video_ids = substr($video_ids, 0,-1);
        return $video_ids;
    }
    /**
     * 删除存储在视频服务器上的视频
     * 支持批量
     */
    private function delete_videoonserver($video_ids)
    {
        if(!$video_ids)
            return false;
        $this->create_curl_obj('livmedia');
        $params['id'] = $video_ids;
        $params['a'] = 'delete';
        $params['r'] = 'vod_update';
        $return = $this->get_common_datas($params);
        $this->curl = NULL;
        return $return;
    }
    
    
    private function get_video_moreinfo($ids)
    {
        $return = array();
        if($ids)
        {
            $this->create_curl_obj('livmedia');
            $params['id'] = $ids;
            $params['a'] = 'detail';
            $params['r'] = 'vod';
            $return = $this->get_common_datas($params);
            return $return;
        }
        return $return;
    }
    
    //审核函数
    private function pass($id,$state)
    {
        $this->create_curl_obj('livmedia');
        $params['id'] = $id;
        $params['audit'] = $state;
        $params['a'] = 'audit';
        $params['r'] = 'admin/vod_update';
        $return = $this->get_common_datas($params);
        return $return;
    }
    
    public function upload($type=2)
    {
        
        $this->create_curl_obj('mediaserver');
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        
        $this->curl->addFile($_FILES);
        $this->curl->addRequestData('title',htmlspecialchars($this->input['title']));
        $this->curl->addRequestData('comment',htmlspecialchars($this->input['brief']));
        $this->curl->addRequestData('vod_leixing',$type);//视频类型
        $this->curl->addRequestData('a','submit_transcode');
        $re = $this->curl->request("create.php");
        unset($this->curl);
        return $re;
    }
    
    /*
     * 需要检查视频已经转码成功了，然后才可以进行审核
     */
    public function audit()
    {
        if(!isset($this->input['id']))
        {
            $this->errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        $cond = " WHERE `id`=$id ";
        $params['state'] = intval($this->input['audit']);
        //表示视频通过审核
        $video_id = $this->get_video_id($id);
        $video_info = $this->get_video_moreinfo($video_id);

        $audit = $this->pass($video_id,$this->input['audit']);

        $re['id'] = $id;
        
        //pass返回结果判定
        if(!$audit)
        {
            $re['status'] = 2;
            $re['audit'] = "转码失败";
            $this->addItem($re);
             $this->output();
        }
        
        if($params['state']==1)
        {
            $re['status'] = 1;
            $re['audit'] = '已审核';
        }
        else if($params['state']==0)
        {
            $re['status'] = 2;
            $re['audit'] = "未审核";
        }
        $datas = $this->obj->update($this->tbname,$params,$cond);
        $this->addItem($re);
        $this->output();
    }
    
    public function sort()
    {
        
    }
    
    
    
    
    public function unknow()
    {
        $this->errorOutput(NO_ACTION);
    }
    /**
     * 创建curl
     */
    public function create_curl_obj($app_name)
    {
        $key        = 'App_'.$app_name;
        global $gGlobalConfig;
        if(!$gGlobalConfig[$key])
        {
            return false;
        }
        $this->curl = new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
    }
    
    /**
     * 解析curl数据
     */
    public function get_common_datas($params)
    {
        $this->curl->setSubmitType('post');
        $this->curl->setReturnFormat('json');
        $this->curl->initPostData();
        foreach($params as $key=>$val)
        {
            if($key!='r')
            {
                $this->curl->addRequestData($key,$val);
            }
            else
            {
                return $this->curl->request($val.".php");
            }
        }
    }   
    

}
$out = new VideoUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
    $action = 'unknow';
}
$out->$action();
?>

