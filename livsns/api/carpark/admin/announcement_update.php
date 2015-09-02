<?php
define('MOD_UNIQUEID','announcement');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/announcement_mode.php');
class announcement_update extends adminUpdateBase
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
		$this->mode = new announcement_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'title' 			=> $this->input['title'],
			'content' 			=> $this->input['content'],
			'carpark_id' 		=> $this->input['carpark_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip'				=> hg_getip(),
		);
		
		//查询站点对应的类型
		if($data['carpark_id'])
		{
			$sql = "SELECT type_id FROM " . DB_PREFIX . "carpark WHERE id = " . $data['carpark_id'];
			$res = $this->db->query_first($sql);
			
			if($res['type_id'])
			{
				$data['type_id'] = $res['type_id'];
			}
		}
		
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			$data['id'] = $vid;
			$this->addLogs('创建停车场公告','',$data,$data['title']);
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
			'title' 			=> $this->input['title'],
			'content' 			=> $this->input['content'],
			'carpark_id' 		=> $this->input['carpark_id'],
		);
		
		//查询站点对应的类型
		if($data['carpark_id'])
		{
			$sql = "SELECT type_id FROM " . DB_PREFIX . "carpark WHERE id = " . $data['carpark_id'];
			$res = $this->db->query_first($sql);
			
			if($res['type_id'])
			{
				$data['type_id'] = $res['type_id'];
			}
		}
		
		$ret = $this->mode->update($data,$this->input['id'],1);
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
			$this->addLogs('更新停车场公告',$pre_data,$up_data,$up_data['title']);
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
			$this->addLogs('删除停车场公告',$ret,'','删除停车场公告' . $this->input['id']);
			$this->addItem('success');
			$this->output();	
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('announcement', 'order_id');	
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

$out = new announcement_update();
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