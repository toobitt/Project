<?php
require('./global.php');
define('MOD_UNIQUEID','paycloudvideo');//模块标识
require_once '../lib/UpYunApi.class.php';
class paycloudvideo extends cronBase
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
			'name' => '云平台费用核算',	 
			'brief' => '云平台费用核算',
			'space' => '10',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'user_queue ';
		$orderby = ' ORDER BY charge_time ASC';
		$limit = ' limit 0,'.CHARGE_LIMIT;
		$where = ' WHERE charge_time < ' . strtotime(date('Y-m-d'));
		$query = $this->db->query($sql . $where . $orderby . $limit);
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
				'start_day'=>date("Y-m-d",strtotime("-1 day")),
				'period'=>1,
				);
				//查询流量
				$status = $this->upyun->BucketStatus($param);
				if($status['discharge'])
				{
					$total = 0;
					foreach($status['discharge'] as $val)
					{
						$total = $total + $val;
					}
					$charge = $total/1024/1024/1024*DISCHARGE;
				
				//扣除昨日费用
				$sql = 'UPDATE ' . DB_PREFIX . 'user_queue SET charge_time='.strtotime(date('Y-m-d')).',balance=balance-'.$charge . ' WHERE user_id='.$v['user_id'];
				$this->db->query($sql);
				
				//插入费用纪录表
				$sql = 'INSERT INTO ' . DB_PREFIX . 'charge_record VALUE(NULL,'.$v['user_id'].','.$charge.', 2,'.TIMENOW.')';
				$this->db->query($sql);
				}
			}
		}
		
	}
}


$out = new paycloudvideo();
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