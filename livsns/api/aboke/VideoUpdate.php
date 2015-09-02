<?php
define('MOD_UNIQUEID', 'aboke');
require_once('global.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
class  VideoUpdateAPI extends outerUpdateBase
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
		if($params['cate_id'] && $categoryVideoNumMax = $this->settings['categoryVideoNumMax'])
		{
			$sql = 'select count(*) as total from '.DB_PREFIX.'video where 1 and cate_id = '.$params['cate_id'].' and user_id = '.$this->user['user_id'];
			$total = $this->db->query_first($sql);
			if($total['total'] > $categoryVideoNumMax)
			{
				$this->errorOutput('此专辑内视频已达上限');
			}
		}
		if($video_id = $this->input['video_id']){
			$filePath = CACHE_DIR .'uploadVideo_' .$video_id.'.php';
			$cache_file = CUR_CONF_PATH . $filePath;
			if (!file_exists($cache_file)) //检测缓存文件是否存在
			{
				$this->errorOutPut(CACHE_ERROR);
			}
			
			$re[0] = @include $cache_file; 
			@unlink($cache_file);
		}
		else {
			$re = $this->upload();
		}		
		if(!is_array($re[0]))
		{
			$this->errorOutput(NO_VIDEO_UPLOAD);
		}
	
		//视频video_id
		$params['video_id'] = $re[0]['id'];
		//视频img
		$params['img'] = $re[0]['img']['host'].
						 $re[0]['img']['dir'].
						 $re[0]['img']['filepath'].
						 $re[0]['img']['filename'];
			
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
        if(isset($this->input['source_type'])){
            $params['source_type'] = intval($this->input['source_type']);
        }
        else {
            $params['source_type'] = 2;
        }
        
        if(isset($this->input['admin_cate_id']))
        {
            $params['admin_cate_id'] = intval($this->input['admin_cate_id']);
        }
		$params['user_id'] = $this->user['user_id'];
 		$params['org_id'] = $this->user['org_id'];
 		$params['user_name'] = $this->user['user_name'];
 		$params['appid'] = $this->user['appid'];
 		$params['appname'] = trim(($this->user['display_name']));
 		$params['ip'] = hg_getip();
 		$params['create_time'] = TIMENOW;
		$params['update_time'] = TIMENOW;
		$params['type'] = 2;
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
		
        $cond = " where 1 and id=$id";
        $data = $this->obj->detail('video',$cond);
        
        //表示无限用户登陆
        if(!isset($this->input['user_id'])){
            $cond = " WHERE `id`=$id";
        }else{
            //如果需要用户登陆
            $cond = " WHERE `id`=$id and `user_id`=".$this->user['user_id'];
        }
		
        //标题
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
		
		if(isset($this->input['num_click']))
		{
			$params['num_click'] = $data['num_click']+1;
		}
		if(isset($this->input['num_share']))
		{
			$params['num_share'] = $data['num_share']+1;
		}
		if(isset($this->input['num_favor']))
        {
            $params['num_favor'] = $data['num_favor']+1;
        }
        
 		$datas = $this->obj->update($this->tbname,$params,$cond);
        
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
		
		$video_id = $this->get_video_id($id);
		if(!$video_id)
		{
			$this->errorOutput(NO_ID);
		}
		
		//删除视频库中的视频
		$this->delete_videoonserver($video_id);
		
		//删除boke中的记录
		$re = $this->obj->delete($this->tbname," where id in ($id)");
		
		$this->addItem($re);
		$this->output();
	}
	
	//获取boke记录的视频video_id
	private function get_video_id($id)
	{
		$videos = $this->obj->show($this->tbname,' where id in ('.$id.') and user_id='.$this->user['user_id']);
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
	
	
	public function upload($type=2)
	{
		if(!isset($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if(!$this->settings['transcode_server_id'])
        {
            $this->settings['transcode_server_id'] = 0;
        }
		$this->create_curl_obj('mediaserver');
		$this->curl->setSubmitType('post');
    	$this->curl->setReturnFormat('json');
    	$this->curl->initPostData();
		
		$this->curl->addFile($_FILES);
        
        
        $this->curl->addRequestData('server_id',intval($this->settings['transcode_server_id']));
		$this->curl->addRequestData('title',htmlspecialchars($this->input['title']));
		$this->curl->addRequestData('comment',htmlspecialchars($this->input['brief']));
		$this->curl->addRequestData('vod_leixing',$type);//视频类型
		$this->curl->addRequestData('a','submit_transcode');
		$re = $this->curl->request("create.php");
        
		unset($this->curl);
		return $re;
	}
	
	public function uploadVideo()
	{
		$params['cate_id'] = $this->input['cate_id'];
		if($params['cate_id'] && $categoryVideoNumMax = $this->settings['categoryVideoNumMax'])
		{
			$sql = 'select count(*) as total from '.DB_PREFIX.'video where 1 and cate_id = '.$params['cate_id'].' and user_id = '.$this->user['user_id'];
			$total = $this->db->query_first($sql);
			if($total['total'] > $categoryVideoNumMax)
			{
				$this->errorOutput('此专辑内视频已达上限');
			}
		}
		$re = $this->upload();
		if(!is_array($re[0]))
		{
			$this->errorOutput(NO_VIDEO_UPLOAD);
		}
		//视频video_id
		$params['id'] = $re[0]['id'];
		$params['title'] = $re[0]['title'];
		//视频img
		$params['img']['host'] = 	$re[0]['img']['host'];
		$params['img']['dir'] = 	$re[0]['img']['dir'];
		$params['img']['filepath'] = $re[0]['img']['filepath'];
		$params['img']['filename'] = $re[0]['img']['filename'];
		$text='<?php return '.var_export($params,true).';?>';
		$filePath = CACHE_DIR .'uploadVideo_' .$params['id'].'.php';
		hg_file_write($filePath,$text);
		$cache_file = CUR_CONF_PATH . $filePath;
		if (!file_exists($cache_file)) //检测缓存文件是否存在,防止require错误
		{
				$this->errorOutPut(CACHE_ERROR);
		}
		$this->addItem($params);
		$this->output();
	}
	public function audit()
	{

	}
	
	public function sort()
	{
		
	}
    
    /**
     * 顶
     */
    public function ding()
    {
        if(!isset($this->input['id']))
        {
            $this->errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        
        $info = $this -> obj -> detail($this -> tbname, $data_limit);
        
        $params['ding'] = $info['ding'] + 1;
        
        $cond = " where 1 and id=$id";
        
        $datas = $this->obj->update($this->tbname,$params,$cond);
        
        $this->addItem($datas);
        $this->output();
    }
 
    /**
     * 赞赏
     */    
    public function zan()
    {
         if(!isset($this->input['id']))
        {
            $this->errorOutput("NO_ID");
        }
        $id = intval($this->input['id']);
        
        $info = $this -> obj -> detail($this -> tbname, $data_limit);
        
        $params['zan'] = $info['zan'] + 1;
        
        $cond = " where 1 and id=$id";
        
        $datas = $this->obj->update($this->tbname,$params,$cond);
        
        $this->addItem($datas);
        $this->output();       
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
    	$key = 'App_'.$app_name;
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
$out = new VideoUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>

