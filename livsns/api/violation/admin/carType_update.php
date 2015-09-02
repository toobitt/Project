<?php
require ('./global.php');
define('MOD_UNIQUEID','violation');
class CarTypeUpdate extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	public function create(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	function update()
	{
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$data = array(
			'log' => htmlspecialchars_decode(urldecode($this->input['log'])),
		);
		$data['log'] = json_decode($data['log'],1);
 		$data['log'] = array(
 			'id' => $data['log'][0]['id'],
 			'host' => $data['log'][0]['host'],
 			'dir'  => $data['log'][0]['dir'],
 			'filepath'   => $data['log'][0]['filepath'],
 			'filename'   => $data['log'][0]['filename'],
 		);	
 		$data['log'] = json_encode($data['log']);
 		$sql = "UPDATE ".DB_PREFIX."cat_type SET ";
 		$space = '';
 		foreach($data as $k => $v)
 		{
 			$sql .= $space . $k ."='".$v."'";
 			$space = ',';
 		}
 		$sql .= " WHERE id= " . $id	;
 		$this->db->query($sql);
 		$data['id'] = $id;
 		$this->addItem($data);
 		$this->output();
	}
	
	
	public function upload()
	{
		if($_FILES['Filedata'])
		{			
			$typetmp = explode('.',$_FILES['Filedata']['name']);
			$filetype = strtolower($typetmp[count($typetmp)-1]);
			$gMaterialType = $this->mater->check_cache();
			$type = '';
			if(!empty($gMaterialType))
			{
				foreach($gMaterialType as $k => $v)
				{
					if(in_array($filetype,$v))
					{
						$type = $k;
					}
				}
			}
				
			if($type!='img')
			{
				$return = array(
					'success' => false,
					'error' => '上传文件格式不正确',
				);
				return $return;
			}
				
			$material = $this->mater->addMaterial($_FILES); //插入各类服务器
				
			if(!empty($material))
			{
				$material['success'] = true;
			    $return = $material;
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
			}			
		}
		else 
		{
			$return = array(
				'success' => false,
				'error' => '文件上传失败',
			);
		}
		$this->addItem($return);	
		$this->output();	
	}	
	
	function unknow()
	{
		$this->errorOutput('未知方法');
	}
}
$out = new CarTypeUpdate();
$action = $_INPUT['a'];
if(!$action)
{
	$action = 'unknow';
}
$out->$action();
?>
