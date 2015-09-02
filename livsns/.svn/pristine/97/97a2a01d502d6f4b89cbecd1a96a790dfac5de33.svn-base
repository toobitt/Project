<?php
require_once('global.php');
define('MOD_UNIQUEID','mediaserver');//模块标识
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/transcode.class.php');
class transcode_status_manger_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	//暂停
	public function pause_transcode_task()
	{
		$this->transcode_oprate('pause_transcode_task',4);
	}
	
	//恢复
	public function resume_transcode_task()
	{
		$this->transcode_oprate('resume_transcode_task');
	}
	
	//停止
	public function stop_transcode_task()
	{
		$this->transcode_oprate('stop_transcode_task',5);//状态置为已取消
	}
	
	private function transcode_oprate($op,$status = 0)
	{
		if(!$this->input['video_ids'] || !$this->input['server_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = " SELECT * FROM " .DB_PREFIX. "transcode_center WHERE id = '" .$this->input['server_id']. "'";
		$server = $this->db->query_first($sql);
		if(!$server)
		{
			$this->errorOutput(NOSERVER);
		}
		
		$t_server = array(
			'host' => $server['trans_host'],
			'port' => $server['trans_port'],
		);
		
		$video_ids = explode(',',$this->input['video_ids']);
		$transcode = new transcode($t_server);
		foreach ($video_ids AS $id)
		{
			$ret = $transcode->$op($id);
			$ret = json_decode($ret,1);
			if($ret['return'] && $ret['return'] == 'success')
			{
				if(!strstr($id,'_more'))
				{
					$sql = " UPDATE " .DB_PREFIX. "vodinfo SET status = '" .$status. "' WHERE id = '" .$id. "'";
					$this->db->query($sql);
				}
			}
		}
		
		$this->addItem('success');
		$this->output();
	}
	
	//设置等待任务的优先级
	public function set_waiting_task_weight()
	{
		if(!$this->input['id'] || !$this->input['server_id'])
		{
			$this->errorOutput(NOID);
		}
		
		$weight = intval($this->input['weight']);
		
		$sql = " SELECT * FROM " .DB_PREFIX. "transcode_center WHERE id = '" .$this->input['server_id']. "'";
		$server = $this->db->query_first($sql);
		if(!$server)
		{
			$this->errorOutput(NOSERVER);
		}
		
		$t_server = array(
			'host' => $server['trans_host'],
			'port' => $server['trans_port'],
		);
		
		$transcode = new transcode($t_server);
		$ret = $transcode->set_waiting_task_weight($this->input['id'],$weight);
		$ret = json_decode($ret,1);
		if($ret['return'] && $ret['return'] == 'success')
		{
			$this->addItem('success');
			$this->output();
		}
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new transcode_status_manger_update();
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