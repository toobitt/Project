<?php
require('global.php');
define('MOD_UNIQUEID','realtimepay');//模块标识
require_once '../lib/UpYunApi.class.php';
class realtimepay extends cronBase
{
	public function __construct()
	{
		parent::__construct();
		$this->upyun = new UpYunApi();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '云平台实时计费',	 
			'brief' => '云平台实时计费',
			'space' => '10',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'user_queue ';
		$orderby = ' ORDER BY update_time ASC';
		$limit = ' limit 0,'.CHARGE_LIMIT;
		$query = $this->db->query($sql . $orderby . $limit);
		$queue = array();
		while($row = $this->db->fetch_array($query))
		{
			$queue[] = $row;
		}
	
		if($queue)
		{
			foreach($queue as $v)
			{
				if(!$v['bucket_name'] || !$v['domain'])
				{
					continue;
				}
				$param = array(
				'bucket_name'=>$v['bucket_name'],
				'domain'=>$v['domain'],
				'start_day'=>'',
				'period'=>1,
				);
				//查询流量
				$status = $this->upyun->BucketStatus($param);
				print_R($status);exit;
				if($status['discharge'])
				{
					$total = 0;
					foreach($status['discharge'] as $val)
					{
						$total = $total + $val;
					}
					$charge = $total/1024/1024/1024*DISCHARGE;
				}
				$sql = 'REPLACE INTO ' . DB_PREFIX . 'realtime_charge VALUE('.$v['user_id'].','.$charge.','.TIMENOW.')';
				$this->db->query($sql);
				$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET update_time='.TIMENOW.' WHERE user_id='.$v['user_id'];
				$this->db->query($sql);
			}
		}
		
	}
}


$out = new realtimepay();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>