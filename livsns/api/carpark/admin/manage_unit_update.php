<?php
define('MOD_UNIQUEID','manage_unit');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/manage_unit_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class manage_unit_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		/******************************权限*************************/
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		/******************************权限*************************/
		$this->mode = new manage_unit_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'name' 				=> $this->input['name'],
			'enterprise_nature' => $this->input['enterprise_nature'],
			'description' 		=> $this->input['description'],
			'tel' 				=> $this->input['tel'],
			'parking_num' 		=> $this->input['parking_num'],
			'address' 			=> $this->input['address'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip'				=> hg_getip(),
		);
		//处理logo图片
		if($_FILES['logo'])
		{
			$_FILES['Filedata'] = $_FILES['logo'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$logo = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
				);
				$data['logo'] = @serialize($logo);
			}
		}
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$data['id'] = $vid;
			$this->addLogs('创建物业单位','',$data,$data['name']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$data = array(
			'name' 				=> $this->input['name'],
			'enterprise_nature' => $this->input['enterprise_nature'],
			'description' 		=> $this->input['description'],
			'tel' 				=> $this->input['tel'],
			'parking_num' 		=> $this->input['parking_num'],
			'address' 			=> $this->input['address'],
		);
		
		//处理logo图片
		if($_FILES['logo'])
		{
			$_FILES['Filedata'] = $_FILES['logo'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$logo = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
				);
				$data['logo'] = @serialize($logo);
			}
		}
		
		$ret = $this->mode->update($data,$this->input['id']);
		if($ret)
		{
			$update_data = array(
				'user_id'			=> $this->user['user_id'],
				'user_name'			=> $this->user['user_name'],
				'update_time' 		=> TIMENOW,
				'ip'				=> hg_getip(),
			);
			$pre_data = $this->mode->update($update_data,$this->input['id']);
			$up_data = $data + $update_data;
			$this->addLogs('更新物业单位',$pre_data,$up_data,$up_data['name']);
		}
		$this->addItem('success');
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$condition = " AND id IN (" . $this->input['id'] . ")";
		$ret = $this->mode->delete($condition);
		if($ret)
		{
			$this->addLogs('删除物业单位',$ret,'','删除物业单位' . $this->input['id']);
			$this->addItem('success');
			$this->output();	
		}
	}
	
	public function audit()
	{
		
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('manage_unit', 'order_id');	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new manage_unit_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>