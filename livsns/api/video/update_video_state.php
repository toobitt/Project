<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update_video_state.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

/**
 *  
 *  更新未转码的视频
 *
 */

class updateUserVideoState extends adminBase
{
	
	private $config;
	
	function __construct(){		
		parent::__construct();
		global $config;
		$this->config = $config;		
	}
	
	function __destruct(){		
		parent::__destruct();
	}
	
	public function updata_video_state()
	{
		$sql = "SELECT serve_id FROM " . DB_PREFIX . "video WHERE state = 0 ORDER BY id DESC";		
		$r = $this->db->query($sql);		
		$nums = $this->db->num_rows($r);
				
		if($nums > 0)//存在状态为转码中的视频
		{			
			echo  $nums . ' videos need update.';
			$serve_id = array();
			while($row = $this->db->fetch_array($r))
			{
				$serve_id[] = $row['serve_id'];	
			}
			
			require(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
			$tvie_video_api=new TVie_video_api($this->config);
			
			//$video_info = $tvie_video_api->find_video_by_id(697);

			foreach($serve_id as $k => $v)//考虑到此处的视频数量不是很多
			{
				$video_info = $tvie_video_api->find_video_by_id($v); //获取服务器上该视频信息
				
				if($video_info->job_status != 'done')
				{
					continue;
				}
				
				$id = $video_info->id;                        		//服务器ID
				$media_addr = $video_info->files[0]->url;           //流媒体地址
				$job_status = $video_info->job_status;        		//转码状态
				$thumbnail_url = $video_info->thumbnail_url;  		//视频缩略图 
				$bthumbnail_url = $video_info->video_still_url;   	//视频大图 
				$duration = ceil($video_info->files[0]->duration);  //视频时长(返回秒数)
				$time = time();                                     //更新时间
				
				//更新数据库
				$sql = "UPDATE " . DB_PREFIX . "video 
						SET schematic = '" . $thumbnail_url . "' , 
							bschematic = '" . $bthumbnail_url . "' ,
							streaming_media =  '" . $media_addr . "' , 
							toff = " . $duration . " ,
							update_time = " . $time . " , 
							state = 1 							
						WHERE serve_id = " . $id;
				$this->db->query($sql);	
				$this->handle($id,$bthumbnail_url);
			}
			return true;
		}
		else
		{
			echo 'No video need update.';
		}
	}

	public function handle($video_id,$uploadedfile,$title="")
	{
		//源文件

		$image = getimagesize($uploadedfile);
		$width = $image[0];
		$height = $image[1];

		//文件名
		$file_name = md5($video_id).".jpg";
		$size = $this->settings['video_img_size'];
		
		//目录
		$file_dir = UPLOAD_DIR.VIDEO_DIR . ceil($video_id/NUM_IMG)."/";	
	
		//文件路径
		$file_path = $file_dir . $file_name;

		if(!hg_mkdir($file_dir))
		{
			$this->errorOutput(UPLOAD_ERR_NO_FILE);
		}
		
		if(!copy($uploadedfile, $file_path))
		{	
			return "ID为".$video_id."视频《".$title."》缩略图<span style='color:red;'>生成失败！</span><br />";
		}
		$img = new GDImage($file_path , $file_path , '');
		$info =array();
		foreach($size as $key=>$value)
		{
			$new_name = $value['label'].$file_name;
			$save_file_path = $file_dir . $new_name;
			$img->init_setting($file_path , $save_file_path , '');
			$img->maxWidth = $value['width'];
			$img->maxHeight = $value['height'];
			$img->makeThumb(3,false);
			$info[$key] = UPLOAD_URL.VIDEO_DIR.ceil($video_id/NUM_IMG)."/".$new_name; 

		}	
		$sql = "update " . DB_PREFIX . "video set images='".$file_name."' where id=".$video_id;
		$this->db->query($sql);
		return "ID为".$video_id."视频《".$title."》缩略图生成成功！<br />";
	}
}
?>