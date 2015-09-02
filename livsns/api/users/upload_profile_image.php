<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: upload_profile_image.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class uploadImageApi  extends appCommonFrm
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
	* 头像生成接口
	* @return array  头像地址
	*/	
	public function uploadImage()
	{
		if (!$this->input['user_id'])
		{
			$userinfo = $this->mUser->verify_credentials();
		}
		else
		{
			$userinfo['id'] = intval($this->input['user_id']);
		}
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$files = $_FILES['files'];
		include_once(ROOT_DIR . 'lib/class/gdimage.php');
		//源文件
		$uploadedfile = $files['tmp_name'];

		//源文件类型
		$tmp = explode('.' , $uploadedfile);
		$file_type = $tmp[1];
		
		//文件名
		$file_name = $userinfo['id'].".jpg";
	
		//目录
		$file_dir = AVATAR_DIR . ceil($userinfo['id']/NUM_IMG)."/";	
	
		//文件路径
		$file_path = $file_dir . $file_name;
		
		$size = array(
				"larger" => array(LARGER_IMG_WIDTH,LARGER_IMG_HEIGHT),
				"middle" => array(MIDDLE_IMG_WIDTH,MIDDLE_IMG_HEIGHT),
				"small" => array(SMALL_IMG_WIDTH,SMALL_IMG_HEIGHT),
			);
	
		if(!hg_mkdir($file_dir))
		{
			$this->errorOutput(UPLOAD_ERR_NO_FILE);
		}
		if(!move_uploaded_file($uploadedfile, $file_path))
		{					
			$this->errorOutput(UPLOAD_ERR_NO_FILE);						
		}
		
		//如果传递了裁剪信息
		if($this->input['cut_info'])
		{
			$cut_info = urldecode($this->input['cut_info']);
			
			$info = explode(',' , $cut_info);
			
			//裁剪的起点坐标
			$src_x = $info[0];
			$src_y = $info[1];

			//裁剪图片的大小
			$src_w = $info[2];
			$src_h = $info[3];

			$src_img = imagecreatefromjpeg($file_path);
			
			$dst_img = imageCreateTrueColor($src_w , $src_h);
			imageCopy($dst_img , $src_img , 0 , 0 , $src_x , $src_y , $src_w , $src_h);
			
			imageJPEG($dst_img , $file_path ,100);
			
		
		}
		
		
		$img = new GDImage($file_path , $file_path , '');
		$info =array();
		foreach($size as $key => $value)
		{
			$save_file_path = $file_dir . $key . '_' . $file_name ;
			$img->init_setting($file_path , $save_file_path , '');
			$img->maxWidth = $value[0];
			$img->maxHeight = $value[1];
			$img->makeThumb(3,false);
			$info[$key] = AVATAR_URL.ceil($userinfo['id']/NUM_IMG)."/".$key . '_' . $file_name."?".hg_rand_num(7); 
		}
		$info['ori'] = AVATAR_URL.ceil($userinfo['id']/NUM_IMG)."/".$file_name."?".hg_rand_num(7); 
		$sql = "UPDATE ".DB_PREFIX."member 
		SET avatar = '".$userinfo['id'].".jpg' 
		WHERE id=".$userinfo['id'];
		$this->db->query($sql);	
		$info['id'] = $userinfo['id'];
		$this->setXmlNode('img','imagefile');
		$this->addItem($info);
		return $this->output();	
	}	
}
$out = new uploadImageApi();
$out->uploadImage();
?>