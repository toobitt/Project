<?php
/*$Id:$*/

define('ROOT_DIR', '../');
require('./global.php');
define('SCRIPTNAME', 'mytemplate');

class mytemplate extends uiBaseFrm
{ 
	private $mDB,$mSetting;
	function __construct()
	{
		global $gDB;
		parent::__construct();
		$this->check_login();
		$this->load_lang('skin');
		$this->mDB = &$gDB;
		$this->mSetting = $this->settings['user_skin'];
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$user_info = array();
		$user_info = $this->user;
		$gScriptName = SCRIPTNAME; 
		$user_info = $this->user;
		$this->page_title = $this->lang['pageTitle'];
		
//		$user_set = $this->mDB->query_first("SELECT * FROM " . DB_PREFIX . 'user_style WHERE member_id = ' . $this->user['id']);
//		if($user_set)
//		{
//			//此处要根据用户之前选择来加载不同的css样式
//			$defined_con = $user_set['defined_content'];
//			$style_id = $user_set['style_id'];
//		}
//		
		hg_add_head_element('js',RESOURCE_DIR . 'scripts/skin.js');
		if(file_exists(RESOURCE_DIR . 'user_defined/' . $this->user['id'] . '.css'))
		{
			hg_add_head_element("css",RESOURCE_DIR . 'user_defined/' . $this->user['id'] . '.css');
		}
		//include hg_load_template('mytemplate');
		$this->tpl->addVar('_mSetting',$this->mSetting);

		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->outTemplate('mytemplate');
	}
	public function uploadImg()
	{ 
		
		$files = $_FILES;
		if(!$files)
		{
			$this->ReportError(UPLOAD_ERR_NO_FILE);
		}
		
	  	$type = substr($files['pic1']['name'],strrpos($files['pic1']['name'],'.')+1);
		$admit_type = array('jpg','gif','png','jpeg');
		
		if(!in_array($type,$admit_type))
		{ 
			$this->ReportError($this->lang['error_type']);  
		}
		
		if($files['pic1']['size'] > BGIMG_MAX_SIZE)
		{ 
			$this->ReportError($this->lang['over_size']);
		}
		 
		$uploadedfile = $files['pic1']['tmp_name'];//源文件  
		
		$file_name = $this->user['id'].".jpg";//文件名 
		
		$file_dir = USER_BGIMG_DIR .ceil($userinfo['id']/NUM_IMG).'/'. $this->user['id']."/";//目录
	
		//文件路径
		$file_path = $file_dir . $file_name;
		if(!hg_mkdir($file_dir))
		{
			$this->ReportError($this->lang['mkdir_fail']);  
		}
		if(!copy($uploadedfile, $file_path))
		{					
			$this->ReportError(UPLOAD_ERR_NO_FILE);
		}
		else
		{
			$result = 1;
		}
		$filedir = USER_BGIMG_URL . $this->user['id'] .'/' . $file_name ; 
		if(!$result)
		{
			echo $this->lang['upload_fail']; 
		}
		else
		{
			$flie = array('imgsrc' => $filedir,'success' => $this->lang['success'] );
			$flie = json_encode($flie);
			echo '<script>parent.endUpload("' . addslashes($flie) . '")</script>';
		}
		
	}
	
	public function savaUserChoice()
	{
		$bg = $this->input['bg_pos'];
		$bgimg = $this->input['bg_url'];
		$is_default = !$bgimg ? 1 : 0;
		$color = array();
		$color = json_decode($this->input['color_set']);
		
		foreach($this->mSetting as $key => $value)
		{
			$str .= $value['className'] . '{' . $color->$key . ';}' . "\r\n";
		} 
		
		$str .= 'body{' . $bg . '}';
		$str = addslashes($str);
		$sql1 = 'SELECT * FROM ' . DB_PREFIX . 'user_style WHERE member_id = ' . $this->user['id'];
		$query = $this->mDB->query_first('SELECT * FROM ' . DB_PREFIX . 'user_style WHERE member_id = ' . $this->user['id']);
		
		 
		if(!$query)
		{
			if(!$this->input['styleid'])
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'user_style(member_id,is_default,definded_content,create_at) VALUES(' . $this->user['id'] . ',' . $is_default . ',"' . $str . '","' . time() . '")';
			}
			else
			{
				$sql = 'INSERT INTO ' . DB_PREFIX . 'user_style(member_id,is_default,style_id,create_at) VALUES(' . $this->user['id'] . ',' . $is_default . ',"' . $this->input['styleid'] . '","' . time() . '")';
			}
			
		}
		else
		{
			if(!$this->input['styleid'])
			{
				$sql = 'UPDATE ' . DB_PREFIX . 'user_style SET definded_content = "' . $str . '" WHERE member_id = ' . $this->user['id'];
			}
			else
			{
				$sql = 'UPDATE ' . DB_PREFIX . 'user_style SET style_id = "' . $this->input['styleid'] . '" WHERE member_id = ' . $this->user['id'];
			}
		}
		
		$this->mDB->query($sql);
		 
		//file_put_contents(RESOURCE_DIR . 'user_defined/' . $this->user['id'] . '.css',$str);
		echo $this->lang['save_success'];
		
	}
}

$out = new mytemplate();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();