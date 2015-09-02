<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: avatar.php 1693 2011-01-10 09:56:44Z repheal $
***************************************************************************/
define('ROOT_DIR', '../');
define('SCRIPTNAME', 'avatar');
require('./global.php');
class avatar extends uiBaseFrm
{	
	private $info;
	public $user_info;
	function __construct()
	{		
		parent::__construct();
		$this->load_lang('avatar');
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->info = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{		
		$this->check_login();
		$this->page_title = $this->lang['pageTitle'];
		$this->user_info = $this->info->getUserById($this->user['id']);
		$gScriptName = SCRIPTNAME;
		$this->page_title = '上传头像';
		hg_add_head_element('css', RESOURCE_DIR . 'imageCut/css/imgareaselect-default.css');
		hg_add_head_element('js', RESOURCE_DIR . 'scripts/' .  'jquery.form.js');
		hg_add_head_element('js', RESOURCE_DIR  . 'imageCut/jquery.imgareaselect.js');
		$this->tpl->addVar("user_info",$this->user_info);
		$this->tpl->addVar("gScriptName",$gScriptName);
		
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('avatar');
		
	}
	
	/**
	 * 
	 * AJAX提交表单
	 */
	public function uploadImage()
	{
		$tmp_name = $_FILES['files']['tmp_name'];
		$image = getimagesize($tmp_name);
		$width = $image[0];  
		$height = $image[1];

		$tmp = explode('.' , $_FILES['files']['name']);
		$file_type = strtolower($tmp[1]);
		
		$file = array('jpg' , 'png' , 'gif');
		
		/**
		 * 判断上传文件大小
		 */
		if($width <= 1000 && $height <= 1000 && $width >= 50 && $height >50)
		{
			/**
			 * 判断文件格式
			 */
			if(!in_array($file_type , $file))
			{
				echo 2;
			}
			else
			{
				$cut_info = $this->input['cut_info'] ? $this->input['cut_info'] : '';
				$ret = $this->info->update_profile_image($_FILES , $cut_info );
				echo json_encode($ret[0]);	
			} 			
		}
		else
		{
			echo 1;
		} 		
	}
	
	/**
	 * 获取裁剪的原图
	 */
	public function get_ori_img()
	{
		//原头像图
		$file_name = $this->user['id'] . ".jpg";
		$path = AVATAR_URL.ceil($this->user['id']/NUM_IMG)."/".$file_name."?".hg_rand_num(7);
		echo $path; 
	}
	
	public function get_avatar()
	{
		$uid = $this->input['user_id']?$this->input['user_id']:$this->user['id'];
		$info = $this->info->getUserById($uid);
		header("Location: ".$info[0]['middle_avatar']);
	}
	
}
$out = new avatar();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();



?>