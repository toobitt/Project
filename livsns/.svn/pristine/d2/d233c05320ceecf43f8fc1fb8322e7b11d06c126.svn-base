<?php
define('MOD_UNIQUEID','carpark_type');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/carpark_type_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class carpark_type_update extends adminUpdateBase
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
		$this->mode = new carpark_type_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'name' 				=> $this->input['name'],
			'description' 		=> $this->input['description'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip'				=> hg_getip(),
			'need_update'		=> intval($this->input['need_update']),
		);
		//处理map_marker图片
		if($_FILES['map_marker'])
		{
			$_FILES['Filedata'] = $_FILES['map_marker'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$map_marker = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
				);
				$data['map_marker'] = @serialize($map_marker);
			}
		}
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$data['id'] = $vid;
			$this->addLogs('创建停车场类型','',$data,$data['name']);
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
			'description' 		=> $this->input['description'],
			'need_update'		=> intval($this->input['need_update']),
		);
		
		//处理logo图片
		if($_FILES['map_marker'])
		{
			$_FILES['Filedata'] = $_FILES['map_marker'];
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$map_marker = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
				);
				$data['map_marker'] = @serialize($map_marker);
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
			$this->addLogs('更新停车场类型',$pre_data,$up_data,$up_data['name']);
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
			$this->addLogs('删除停车场类型',$ret,'','删除停车场类型' . $this->input['id']);
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
		
		$ret = $this->drag_order('carpark_type', 'order_id');	
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

$out = new carpark_type_update();
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