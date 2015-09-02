<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.php 1813 2011-01-20 02:59:40Z repheal $
***************************************************************************/
set_time_limit(0);
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class dealVideosUpload extends adminBase
{
	private $config;
	private $mUser;
	
	
	function __construct()
	{
		parent::__construct();
		global $config;
		$this->config = $config;

		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function deal_upload()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo)
		{
			//$this->errorOutput(LOGIN_FAILED);  //用户不存在
			echo '用户未登录！';
			exit;
		}
		$video_path = urldecode($this->input['video_path']); 	//视频的在本地的目录      
		$file_name = urldecode($this->input['file_name']);		//视频的文件
		require(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
		$tvie_video_api=new TVie_video_api($this->config);
		
		if(!$tvie_video_api)
		{
			echo '视频上传接口初始化出错！';
			exit;
		}
				
		if (!$_FILES['videofile'])
		{
			//$this -> errorOutput(UPLOAD_ERR_NO_FILE);  //视频文件丢失
			echo '视频文件丢失！';
			exit;
		}
		
		$file_size = $this->input['file_size']/1024/1024;
		
		if(intval(substr(ini_get("upload_max_filesize") , 0 , strlen($s)-1)) < $file_size) //判断上传的文件是否大于ini中的上传大小
		{
			//$this -> errorOutput(OVER_UPLOAD_SIZE);
			echo '上传文件过大！ ';
			exit;  
		}
		//将视频上传到流媒体服务器
		$result = $tvie_video_api->upload_video($_FILES['videofile']['tmp_name'] , '' , '' , $file_name);
		$result = json_decode($result);
		
		//判断文件上传到流媒体服务器是否出错
		if($result->error_type == 1)
		{			
			echo $result->errors;
			exit; 
		}
		
		$return_id = $result->video_id;

		if($return_id) 	
		{			
			$sever_id = intval($return_id);			    				//服务器上的视频ID
			$user_id = $userinfo['id'];           						//用户ID
			$video_name = trim(urldecode($this->input['video_name'])); 	//视频名称
			$video_brief = trim(urldecode($this->input['video_brief']));//视频简介
			$video_tags = trim(urldecode($this->input['video_tags']));  //视频标签
			$video_sort = intval($this->input['video_sort']);     		//视频分类
			$video_copyright = intval($this->input['video_copyright']); //视频版权
			$schematic = '';
			$time = time();
			$ip = hg_getip();
			
			$text = $video_brief.$video_name.$video_tags;
			$video_tags = str_replace("，",",",$video_tags);
			include_once(ROOT_DIR . 'lib/class/banword.class.php');
			$banword = new banword();
			$status = 0;
			$banwords = $banword->banword(urlencode($text));

			//file_put_contents('/data/web/api.hcrt.cn/uploads/d.txt', serialize($banwords));
			if($banwords && $banwords != 'null') //暂时先定义为没关键词
			{
			 	$status = 2;	
				$banwords = implode(',', $banwords);
			}
			else
			{
				$banwords = '';
			}
			
			$sql = "INSERT INTO " . DB_PREFIX . "video 
				    (sort_id , 
				     user_id ,
				     serve_id , 
				     title , 
				     brief , 
				     tags , 
				     filename , 
				     copyright ,
				     schematic , 
				     bans,
				     state,
				     ip , 
				     create_time ,
				     update_time ) 
				     VALUE
				     ($video_sort , 
					  $user_id ,
					  $sever_id ,
				      '" . $video_name . "' ,
				      '" . $video_brief . "' , 
				      '" . $video_tags . "' ,
				      '" . $file_name . "' , 
				      $video_copyright ,
				      '" . $schematic . "' ,
				      '" . $banwords . "' ,
				      '" . $status . "' ,
				      '" . $ip . "' ,
				      $time ,
				      $time) ";
			$this->db->query($sql);
			
			//获取返回的ID			
			$video_id = $this->db->insert_id();
			
			if(!$video_id)
			{
				echo '视频数据入库出错！';
				exit;
			}
			
			
			//标签
			$tags = explode(',' , $video_tags );
			
			//此处标签数量有限制(最多10个)
			foreach($tags as $k => $v)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname = '" . trim($v) . "'";
			
				$r = $this->db->query_first($sql);
			
				if($r)
				{
					$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = tag_count + 1 WHERE tagname = '" . trim($v) . "'";
					$this->db->query($sql);
					
					$sql = "REPLACE INTO " . DB_PREFIX . "video_tags SET video_id = " . $video_id . ", tag_id = " . $r['id'] . " , type = 0";
					$this->db->query($sql);
				}
				else
				{
					$sql = "INSERT INTO " . DB_PREFIX . "tags SET tagname = '" . trim($v) . "' , tag_count = tag_count + 1";
					$this->db->query($sql);			
					$tag_id = $this->db->insert_id();
					
					$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $video_id . ", tag_id = " . $tag_id . " , type = 0";
					$this->db->query($sql);
				} 	
			}
			
			/**
			 * 添加上传积分
			 */
			$this->mUser->add_credit_log(UPLOAD_VIDEO);
			
			/**
			 * 更新ucenter用户扩展表中的数据
			 */
			$this->mUser->update_video_count($user_id);					
			echo  1;			
		}
		else
		{
			echo '视频未能成功上传到流媒体服务器！';
		} 	
	}

	
	public function rebuild_data()
	{
		$id = intval($this->input['id']);
		
		//获取视频在流媒体服务器上的ID
		include(ROOT_DIR . 'lib/video/video.class.php');
		$video = new video();
		$video_info = $video->get_single_video($id);		
		$serve_id = $video_info['serve_id'];
				
		if(!$serve_id)
		{
			echo 0;
			exit;
		}
				
		include(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
		$tvie_video_api=new TVie_video_api($this->config);
		$serve_video_info = $tvie_video_api->find_video_by_id($serve_id);
		
		$duration = ceil($serve_video_info->files[0]->duration);    //视频时长(返回秒数)
		$thumbnail_url = $serve_video_info->thumbnail_url;  		//视频缩略图 
		$bthumbnail_url = $serve_video_info->video_still_url;   	//视频大图 
		$media_addr = $serve_video_info->files[0]->url;           	//流媒体地址
		
		//更新本地缩略图
		$sql = "UPDATE " . DB_PREFIX . "video 
				SET toff = " . $duration . " , 
					schematic = '" . $thumbnail_url . "' , 
					bschematic = '" . $bthumbnail_url . "' , 
					streaming_media = '" . $media_addr . "' 
				WHERE id = " . $id;

		$r = $this->db->query($sql);
		if($r)
		{
			echo 1;
		}
		else
		{
			echo 0;
		} 		
	}
}

$out = new dealVideosUpload();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'deal_upload';
}
$out->$action();
?>