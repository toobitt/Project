<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: upload.php 4149 2011-06-30 08:57:41Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class uploadeApi extends BaseFrm
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
	
	/**
	* 上传图片
	*/
	public function uploadeImage(){
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$files = $_FILES['files'];
		$uploadedfile = $files['tmp_name'];	
		$info = $this->handle($uploadedfile);
		$this->setXmlNode('media','info');
		$this->addItem($info);
		$this->output();	
	}
	
	
	
	/**
	* 上传图片
	*/
	public function uploadeImageMore(){
		
		$files = urldecode($this->input['filenames']);
		if(!$files)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$files_arr = explode(",", $files);
		if(is_array($files_arr))
		{
			$this->setXmlNode('media','info');
			foreach($files_arr as $k => $v)
			{
				$info = $this->handle($v);
				if(is_file($v))
				{
					unlink($v);
				}
				$this->addItem($info);
			}
			$this->output();
		}
		else 
		{
			$this->errorOutput(OBJECT_NULL);
		}
	}
	
	private function handle($uploadedfile)
	{
		include_once(ROOT_DIR . 'lib/class/gdimage.php');
		//源文件
		if((filesize($uploadedfile)/1024/1024) >= IMG_SIZE)
		{
			$this->errorOutput(IMG_SIZE_ERROR);
		}
		$image = getimagesize($uploadedfile);
		$width = $image[0];
		$height = $image[1];
		if(strpos(strtolower($image['mime']),'jpeg'))
		{
			$type = '.jpg';
		}
		else if(strpos(strtolower($image['mime']),'png'))
		{
			$type = '.png';
		}
		else if(strpos(strtolower($image['mime']),'gif'))
		{
			$type = '.gif';
		}
		//文件名
		$file_name = hg_generate_user_salt(16).".jpg";
		$size = array(
			"larger" => array('t'=>"l_",'size'=>IMG_SIZE_LARGER),
			"middle" => array('t'=>"m_",'size'=>IMG_SIZE_MIDDLE),
			"small" => array('t'=>"s_",'size'=>IMG_SIZE_SMALL),
		);
		
		//目录
		$file_dir = UPLOAD_DIR.IMG_DIR . ceil($userinfo['id']/NUM_IMG)."/";	
	
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
			
			if($key == "larger")
			{
				$img->maxWidth = $width > $value['size']?$value['size'] : $width;
				$img->maxHeight = $height * ($img->maxWidth/$width);
				$img->makeThumb(1,false,true);
			}
			else 
			{
				if($width > $height)
				{
					$img->maxWidth = $width > $value['size']?$value['size'] : $width;
					$img->maxHeight = $height * ($img->maxWidth/$width);
				}
				else 
				{
					$img->maxHeight = $height > $value['size']?$value['size'] : $height;
					$img->maxWidth = $width * ($img->maxHeight/$height);
				}
				$img->makeThumb(3);
			}
			$info[$key] = UPLOAD_URL.IMG_DIR.ceil($userinfo['id']/NUM_IMG)."/".$new_name; 
			if( defined('WATER_MARK_DONE') && WATER_MARK_DONE == true )
			{
				$img->create_watermark($save_file_path,$type,4,WATER_MARK_IMG);
			}
		}
		$ip = hg_getip();
		$create_at = time();
		$sql = "INSERT ".DB_PREFIX."media(status_id,dir,url,ip,create_at) VALUES(0,'".IMG_DIR.ceil($userinfo['id']/NUM_IMG)."/"."','".$file_name."','".$ip."',".$create_at.")";

		$this->db->query($sql);	
		$id = $this->db->insert_id();
		$info['id'] = $id;
		$info['url'] = $file_name;
		$info['ip'] = $ip;
		$info['create_at'] = $create_at;
		$info['type'] = 0;	
		return $info;
	}
	
	/**
	* 视频
	*/
	public function uploadVideo()
	{
		//$this->input['url'] = "http://www.tudou.com/playlist/playindex.do?lid=11088377&iid=68415328";
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		if(!$this->input['url'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$link = urldecode($this->input['url']);
//		$link = "http://127.0.0.1/livsns/vui/video_play.php?id=1";
		$infos = hg_get_video($link);
//		file_put_contents("D:texzt.php", $link);
		
		if(!$infos['img']&&!$infos['link']&&!$infos['title'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		include_once(ROOT_DIR . 'lib/class/shorturl.class.php');
		$short = new shorturl();
		$url = $short->shorturl($link);
		$ip = hg_getip();
		$create_at = time();
		$sql = "INSERT ".DB_PREFIX."media(status_id,type,title,link,img,url,ip,create_at) VALUES(0,1,'".str_replace("'", "’", $infos['title'])."','".$infos['link']."','".$infos['img']."','".$url."','".$ip."',".$create_at.")";
		$this->db->query($sql);	
		$id = $this->db->insert_id();
		$info['id'] = $id;
		$info['url'] = $url;
		$info['ip'] = $ip;
		$info['link'] = $infos['link'];
		$info['img'] = $infos['img'];
		$info['title'] = $infos['title'];
		$info['create_at'] = $create_at;
		$info['type'] = 1;	
		$this->setXmlNode('media','info');
		$this->addItem($info);
		return $this->output();	
	}
	
	public function delete(){
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$member_id = $userinfo['id'];
		if($this->input['id'] && $this->input['url'])
		{
			$id = $this->input['id'];
			$name = $this->input['url'];
			$size = array(
			"ori" => "",
			"larger" => "l_",
			"middle" => "m_",
			"small" => "s_",
			);

			$url = UPLOAD_DIR.IMG_DIR.ceil($userinfo['id']/NUM_IMG)."/";
			foreach($size as $key=>$value)
			{
				$urls = $url.$value.$name;
				unlink($urls);
			}
			$sql = "DELETE FROM ".DB_PREFIX."media WHERE id = ".$id;
			$this->db->query($sql);
			$info = array(
				'id' => $id,
				'url' => $name,
			);
			$this->setXmlNode('media','info');
			$this->addItem($info);
			return $this->output();	
		}
	}
	
	public function update()
	{
/*		$this->input['status_id'] =73187;
		$this->input['media_id'] = '172,171';
		$this->input['type'] = '0,';*/
		if($this->input['status_id'] && $this->input['media_id'])
		{
			
			$status_id = $this->input['status_id'];
			$media_id = urldecode($this->input['media_id']);
			
			str_replace(",","",$media_id, $count);
			if(!$count)
			{				
				$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ".$media_id;
				$this->db->query($sql);
			}
			else 
			{
				$media_ids = explode(",",$media_id);
				$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ";
				foreach ($media_ids as $key => $value)
				{
					if($value)
					{
						$sqls = $sql.$value;
					}
					$this->db->query($sqls);
				}
			}
			
			$info = array(
				'status_id' => $status_id,
				'media_id' => $media_id,
			);
			$this->setXmlNode('media','info');
			$this->addItem($info);
			return $this->output();	
		}	
	}
	
	/**
	 * 针对于活动的发布微博的图片问题
	 */
	public function insert_update()
	{
		if($this->input['status_id'] && $this->input['media_id'])
		{
			$status_id = $this->input['status_id'];
			$media_id = $this->input['media_id'];
			$sql = "INSERT INTO ".DB_PREFIX."media(`type`,`source`,`dir`,`url`,`ip`,`create_at`) 
		SELECT `type`,`source`,`dir`,`url`,`ip`,`create_at` FROM ".DB_PREFIX."media WHERE id=".$media_id;
			$this->db->query($sql);
			$new_id = $this->db->insert_id();
			
			$sql = "UPDATE ".DB_PREFIX."media SET status_id = ".$status_id." WHERE id = ".$new_id;
			$this->db->query($sql);
			
			$info = array(
				'status_id' => $status_id,
				'media_id' => $new_id,
			);
			$this->setXmlNode('media','info');
			$this->addItem($info);
			return $this->output();	
		}
	}
	
	public function show()
	{
		$info = array();
		//$this->input['status_id'] = "73103";
		if($this->input['status_id'])
		{			
			$status_ids = urldecode($this->input['status_id']);
			$sql = "SELECT * FROM ".DB_PREFIX."media WHERE status_id IN (".$status_ids.")" ;
			$query = $this->db->query($sql);
			$this->setXmlNode('media','info');
			while ($array = $this->db->fetch_array($query))
			{
				$info = $array;
				$info['ori']=UPLOAD_URL.$array['dir'].$array['url'];
				$info['larger']=UPLOAD_URL.$array['dir']."l_".$array['url'];
				$info['middle']=UPLOAD_URL.$array['dir']."m_".$array['url'];
				$info['small']=UPLOAD_URL.$array['dir']."s_".$array['url'];
				$this->addItem($info);
			}			
			$this->output();
		}
	}
	
	
	/**
	 * 获得图片
	 */
	public function get_pic()
	{
		    $pic_id = $this->input['pic_id'];

			$sql = "SELECT * FROM ".DB_PREFIX."media WHERE id = " . $pic_id;

			$pic_info = $this->db->query_first($sql);
			$pic_info['larger']=UPLOAD_URL.$pic_info['dir']."l_".$pic_info['url'];

			$this->setXmlNode('media','info');
			$this->addItem($pic_info['larger']);
			$this->output();
			
		
	}
}
$out = new uploadeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>