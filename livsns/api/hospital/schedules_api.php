<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','schedules');
define('SCRIPT_NAME', 'Schedules');
class Schedules extends outerReadBase
{
	private $url = '';
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function count(){}	
	
	private function _curl($post_data)
	{
		$token = $this->settings['hospital_token'];
		$this->url = $this->settings['schedules_url'];
		
		if(!$this->url || !$token)
		{
			return false;
		}
		
		$post_data['token'] = $token;
		
		//hg_pre($post_data);
		$header[] = "charset=UTF-8";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($post_data));
		
		
		$ret = '';
		$ret = curl_exec($ch);
		//hg_pre($ret);
		curl_close($ch);//关闭
		
		if($ret)
		{
			$res = json_decode($ret,1);
			if($res['success'])
			{
				$data = $res['data'];
			}
			else 
			{
				return false;
			}
		}
		return $data;
	}
	
	public function show()
	{
		/*$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		
		$sql = "SELECT t1.* FROM " . DB_PREFIX . "schedules t1 
				WHERE 1 " . $cond . $limit; 
		$q = $this->db->query($sql);
		
		
		
		$data = array();
		while ($row = $this->db->fetch_array($q)) 
		{	
			if(!$row['left_num'])
			{
				$row['order_status'] = 0;
				$row['order_text'] = '已满';
			}
			else if($row['left_num'])
			{
				$row['order_status'] = 1;
				$row['order_text'] = '预约';
			}
			
			$row['reg_time'] = $this->settings['regTime'][$row['reg_time']];
			$row['call_type'] = $this->settings['CallType'][$row['call_type']];
			$this->addItem($row);
		}*/
		
		$hospital_id = intval($this->input['hospital_id']);
		$depart_id = intval($this->input['department_id']);
		$doctor_id = intval($this->input['doctor_id']);
		if(!$hospital_id || !$depart_id || !$doctor_id)
		{
			$this->errorOutput(NOID);
		}
		
		$post_data = array(
			'hospitalId'		=> $hospital_id,
			'departmentId'		=> $depart_id,
			'doctorId'			=> $doctor_id,
		);
		
		$res = array();
		$res = $this->_curl($post_data);
		//hg_pre($res);exit();
		
		if(is_array($res) && count($res))
		{
			foreach ($res as $k => $row)
			{
				if(!$row['leftNum'])
				{
					$row['order_status'] = 0;
					$row['order_text'] = '已满';
				}
				else if($row['leftNum'])
				{
					$row['order_status'] = 1;
					$row['order_text'] = '预约';
				}
				
				$row['reg_time'] = $this->settings['regTime'][$row['regTime']] ? $this->settings['regTime'][$row['regTime']] : '';
				$row['call_type'] = $this->settings['CallType'][$row['outCallType']] ? $this->settings['CallType'][$row['outCallType']] : '';
			
			
				$tmp = array(
					'hospital_id' 		=> $hospital_id,
					'department_id'		=> $depart_id,
					'doctor_id'			=> $doctor_id,
					'schedule_id'		=> $row['scheduleId'],
					'left_num'			=> $row['leftNum'],
					'call_type'			=> $row['call_type'],
					'price'				=> $row['price'],
					'reg_date'			=> $row['regDate'],
					'remark'			=> $row['remark'],
					'reg_time'			=> $row['reg_time'],
					'week_day'			=> $row['weekDay'] ? $row['weekDay'] : '',
					'order_status'		=> $row['order_status'],
					'order_text'		=> $row['order_text'],
					'status'			=> 1,
				);
				
				$this->addItem($tmp);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$hospital_id = intval($this->input['hospital_id']);
		$depart_id = intval($this->input['department_id']);
		$doctor_id = intval($this->input['doctor_id']);
		if(!$hospital_id || !$depart_id || !$doctor_id)
		{
			$this->errorOutput(NOID);
		}
		
		$condition = '';
		$condition .= ' AND t1.hospital_id = ' . $hospital_id . " AND t1.department_id = {$depart_id} AND t1.doctor_id = {$doctor_id}";
		
		$condition .= " AND t1.status = 1";
		
		$condition .= ' AND t1.reg_order > ' . TIMENOW;
		
		$condition .= ' ORDER BY t1.order_id  ASC ';
		
		
		return $condition ;
	}
	
	public function detail()
	{
		/*$id = intval($this->input['id']);
		if(!$id)
		{
			//$this->errorOutput(NOID);
		}
		$sql = "SELECT t1.*,t2.name as hospital_name,t3.name as doctor_name,t3.title as doctor_title,t4.name as department_name FROM " . DB_PREFIX . "schedules t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2
					ON t1.hospital_id = t2.hospital_id
				LEFT JOIN " . DB_PREFIX ."departments t4 
					ON t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id 
				LEFT JOIN " . DB_PREFIX . "doctor t3 
					ON t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id AND t1.doctor_id = t3.doctor_id
				WHERE 1 AND t1.id = {$id}";
		$row = $this->db->query_first($sql);
		
		$row['reg_time'] = $this->settings['regTime'][$row['reg_time']];
		$row['call_type'] = $this->settings['CallType'][$row['call_type']];
		
		//$this->addItem($row);
		//$this->output();*/
		
		$hospital_id 	= intval($this->input['hospital_id']);
		$depart_id 		= intval($this->input['department_id']);
		$doctor_id 		= $this->input['doctor_id'];
		$schedule_id 	= intval($this->input['schedule_id']);
		if(!$hospital_id || !$depart_id || !$doctor_id || !$schedule_id)
		{
			$this->errorOutput(NOID);
		}
		
		$post_data = array(
			'hospitalId'		=> $hospital_id,
			'departmentId'		=> $depart_id,
			'doctorId'			=> $doctor_id,
		);
		
		$res = array();
		$res = $this->_curl($post_data);
		//hg_pre($res);exit();
		if(!$res)
		{
			$this->errorOutput('实时获取号源失败');
		}
		
		foreach ($res as $k => $row)
		{
			if($row['scheduleId'] != $schedule_id)
			{
				continue;
			}
			if(!$row['leftNum'])
			{
				$row['order_status'] = 0;
				$row['order_text'] = '已满';
			}
			else if($row['leftNum'])
			{
				$row['order_status'] = 1;
				$row['order_text'] = '预约';
			}
			
			$row['reg_time'] = $this->settings['regTime'][$row['regTime']] ? $this->settings['regTime'][$row['regTime']] : '';
			$row['call_type'] = $this->settings['CallType'][$row['outCallType']] ? $this->settings['CallType'][$row['outCallType']] : '';
		
		
			$sch = array(
				'hospital_id' 		=> $hospital_id,
				'department_id'		=> $depart_id,
				'doctor_id'			=> $doctor_id,
				'schedule_id'		=> $row['scheduleId'],
				'left_num'			=> $row['leftNum'],
				'call_type'			=> $row['call_type'],
				'price'				=> $row['price'],
				'reg_date'			=> $row['regDate'],
				'remark'			=> $row['remark'],
				'reg_time'			=> $row['reg_time'],
				'week_day'			=> $row['weekDay'] ? $row['weekDay'] : '',
				'order_status'		=> $row['order_status'],
				'order_text'		=> $row['order_text'],
				'status'			=> 1,
			);
		}
		
		if(!$sch['left_num'])
		{
			$this->errorOutput('该号已预约完');
		}
		
		if(!empty($sch))
		{
			$sql = "SELECT name FROM " . DB_PREFIX . "hospital WHERE hospital_id = {$hospital_id}";
			$hos_res = $this->db->query_first($sql);
			$sch['hospital_name'] = $hos_res['name'];
			
			$sql = "SELECT name FROM " . DB_PREFIX . "departments WHERE hospital_id = {$hospital_id} AND department_id = {$depart_id}";
			$dep_res = $this->db->query_first($sql);
			$sch['department_name'] = $dep_res['name'];
			
			$sql = "SELECT name,title FROM " . DB_PREFIX . "doctor WHERE hospital_id = {$hospital_id} AND department_id = {$depart_id} AND doctor_id = {$doctor_id}";
			$dep_res = $this->db->query_first($sql);
			$sch['doctor_name'] = $dep_res['name'];
			$sch['doctor_title'] = $dep_res['title'];
		}
		$this->addItem($sch);
		$this->output();
	}
}
include(ROOT_PATH . 'excute.php');
?>