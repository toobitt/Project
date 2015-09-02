<?php
require('global.php');
define('MOD_UNIQUEID','scenic_survey');//模块标识
class scenicSpotsUpdateApi extends adminBase
{

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/scenic_spots.class.php');
		$this->obj = new scenicSpots();	
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	function create()
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写景点名称");
		}
		
		$info = array();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		if($_FILES)
		{
			$file_name= $_FILES['Filedata']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("图片类型错误，请重新上传");
			}
			$fileinfo = $this->material->addMaterial($_FILES); //插入图片服务器
		}	
			
		$info = array(
			'title'			=> $title,
			'appid'			=> intval($this->input['appid']),
            'brief'			=> $this->input['brief'],
			'address'		=> $this->input['address'],
			'grade'			=> $this->input['grade'],
			'keywords'		=> $this->input['keywords'],
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'create_time'	=> TIMENOW,
		);
		if($fileinfo)
		{
			$arr = array(
				'host'			=>	$fileinfo['host'],
				'dir'			=>	$fileinfo['dir'],
				'filepath'		=>	$fileinfo['filepath'],
				'filename'		=>	$fileinfo['filename'],
			);
			$info['indexpic'] =	serialize($arr);
		}
		$ret = $this->obj->create($info);
		
		$introid = $this->obj->insert_content($ret,$this->input['introduce']);
		$this->addItem($ret);
		$this->output();
	}
	
	function update()
	{	
		$title = $this->input['title'];
		if(!$title)
		{
			$this->errorOutput("请填写景点名称");
		}
		
		$info = array();
		require_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->material = new material();
		if($_FILES)
		{
			$file_name= $_FILES['Filedata']['name'];
			$file_type = strtolower(strrchr($file_name,"."));
			$ftypes = $this->settings['pic_types'];
			if(!in_array($file_type,$ftypes))
			{
				$this->errorOutput("图片类型错误，请重新上传");
			}
			$fileinfo = $this->material->addMaterial($_FILES); //插入图片服务器
		}	
			
		$info = array(
			'id'			=> intval($this->input['id']),
			'title'			=> $title,
            'sort_id'		=> intval($this->input['sort_id']),
			'appid'			=> intval($this->input['appid']),
            'brief'			=> $this->input['brief'],
			'country'		=> '1',
			'address'		=> $this->input['address'],
			'grade'			=> $this->input['grade'],
			'keywords'		=> $this->input['keywords'],
			'longitude'		=> $this->input['longitude'],
			'latitude'		=> $this->input['latitude'],
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'ip'			=> $this->user['ip'],
			'create_time'	=> TIMENOW,
		);
		if($this->input['province'])
		{
			$info['province'] = $this->input['province'];
		}
		if($this->input['city'])
		{
			$info['city'] = $this->input['city'];
		}
		if($this->input['area'])
		{
			$info['area'] = $this->input['area'];
		}
		if($fileinfo)
		{
			$arr = array(
				'host'			=>	$fileinfo['host'],
				'dir'			=>	$fileinfo['dir'],
				'filepath'		=>	$fileinfo['filepath'],
				'filename'		=>	$fileinfo['filename'],
			);
			$info['indexpic'] =	serialize($arr);
		}
		$ret = $this->obj->update($info);
		$argument = array(
				'argument_name'		=>	 $this->input['argument_name'],
				'value'				=>	 $this->input['value'],
		);
		
		$ret = $this->obj->update_content($ret,$argument);
		$this->addItem('sucess');
		$this->output();
	}
	
	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的景点");
		}
		$ret = $this->obj->delete($ids);
		$this->addItem('sucess');
		$this->output();
		
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new scenicSpotsUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>