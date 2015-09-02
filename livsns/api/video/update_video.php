<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: update_video.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class updateVideosInfoApi extends adminBase
{
	private $mUser;
	function __construct()
	{
		parent::__construct();	
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	public function update()
	{
		$info = urldecode($this->input['update_info']);
		
		$video_info = unserialize($info);
		
		$sql = "SELECT tags FROM " . DB_PREFIX . "video WHERE id=" . $video_info['video_id'];
		$f = $this->db->query_first($sql);
		
		//更新视频信息		
		$sql = "UPDATE " . DB_PREFIX . "video 
			    SET title = '" . $video_info['video_title'] . "' , 
			    	tags = '" .  $video_info['video_tag'] . "' , 
			    	sort_id =  " . $video_info['video_sort'] . " , 
			    	copyright = " . $video_info['video_copyright'] . ", 
			    	brief = '" . $video_info['video_brief'] . "' 
			    WHERE id = " . $video_info['video_id'];
		
		$this->db->query($sql);	
		
		//更新的视频ID			
		$video_id = $video_info['video_id'];
		
		if($video_info['tags'] != $f['tags'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$q = $this->db->query($sql);
			
			$t_id = $space = "";
			while($row = $this->db->fetch_array($q))
			{
				if($row['tag_id'])
				{
					$t_id .= $space . $row['tag_id'];
				}
				$space = ",";
			}
			
			$sql = "delete from " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$this->db->query($sql);
			if($t_id)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE id IN (" . $t_id . ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$tag_count = 0;
					if(($row['tag_count']-1)>0)
					{
						$tag_count = $row['tag_count'] - 1;
					}
					
					$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = $tag_count  WHERE id = '" . $row['id'] . "'";
					$this->db->query($sql);
				}
			}
		}
		
		//标签		
		$tags = explode(',' , $video_info['video_tag']);
		
		//此处标签数量有限制(最多5个)
		foreach($tags as $k => $v)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname = '" . trim($v) . "'";
		
			$r = $this->db->query_first($sql);
		
			if($r)
			{
				$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = tag_count + 1 WHERE tagname = '" . trim($v) . "'";
				$this->db->query($sql);
				
				$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $video_id . ", tag_id = " . $r['id'] . " , type = 0";
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
	}

	/**
	 * 更新视频的播放次数
	 */
	public function update_play_count()
	{
		$video_id = intval(trim($this->input['video_id']));
		$sql = "UPDATE " . DB_PREFIX . "video SET play_count = play_count + 1,click_count = click_count + 1  WHERE id = " . $video_id ;
		$this->db->query($sql);	
	}
	
	/**
	 *修改视频图片 
	 */
	public function update_schematic()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$video_id = intval(trim($this->input['video_id']));
		$file_name = urldecode($this->input['schematic']);
		
		if(!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$files = $_FILES['files'];
		include_once(ROOT_DIR . 'lib/class/gdimage.php');
		//源文件
		$uploadedfile = $files['tmp_name'];	
		
		$image = getimagesize($uploadedfile);
		$width = $image[0];
		$height = $image[1];

		
		$size = array(
			"big" => array('t'=>"b_",'width'=>VIDEO_IMG_WIDTH*VIDEO_IMG_MULTIPLE,'height'=>VIDEO_IMG_HEIGHT*VIDEO_IMG_MULTIPLE),
			"normal" => array('t'=>"n_",'width'=>VIDEO_IMG_WIDTH,'height'=>VIDEO_IMG_HEIGHT),
		);
		//文件名
		

		if(!$file_name)
		{
			$file_name = hg_generate_user_salt(16).".jpg";
		}
		else 
		{
			str_replace(DOMAIN,"",$file_name,$cnt);
			if($cnt)
			{
				$file_name = hg_generate_user_salt(16).".jpg";
			}
			else 
			{
				$arr = explode("/",$file_name);
				$file_name = substr($arr[count($arr)-1],2);
				if(!trim($file_name))
				{
					$file_name = hg_generate_user_salt(16).".jpg";
				}
			}
		}
		
		
		//目录
		$file_dir = UPLOAD_DIR.VIDEO_DIR. ceil($video_id/NUM_IMG)."/";	
	
		//文件路径
		$file_path = $file_dir . $file_name;
		
		

		if(!hg_mkdir($file_dir))
		{
			$this->errorOutput(UPLOAD_ERR_NO_FILE);
		}
		if(!copy($uploadedfile, $file_path))
		{					
			$this->errorOutput(UPLOAD_ERR_NO_FILE);						
		}
		$img = new GDImage($file_path , $file_path , '');
		$info =array();
		foreach($size as $key=>$value)
		{
			$new_name = $value['t'].$file_name;
			$save_file_path = $file_dir . $new_name;
			$img->init_setting($file_path , $save_file_path , '');
			$img->maxWidth = $value['width'];
			$img->maxHeight = $value['height'];
			$img->makeThumb(3,false);
			$info[$key] = UPLOAD_URL.VIDEO_DIR.ceil($video_id/NUM_IMG)."/".$new_name."?".hg_generate_user_salt(5); 
		}
		$sql = "UPDATE " . DB_PREFIX . "video SET bschematic = '" . $file_name . "' , schematic = '" . $file_name . "' WHERE id = " . $video_id;
		$info['ori'] = $file_name;
		$info['id'] = $video_id;
		$this->db->query($sql);
		$this->setXmlNode('media','info');
		$this->addItem($info);
		return $this->output();
	}
	
	
}

$out = new updateVideosInfoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();

?>