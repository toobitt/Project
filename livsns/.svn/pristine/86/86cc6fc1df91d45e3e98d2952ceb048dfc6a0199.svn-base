<?php
require ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','order');
define('SCRIPT_NAME', 'Order');
class Order extends outerReadBase
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
	
	
	public function show()
	{
		$cond = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 200;
		$limit = ' LIMIT ' . $offset . ',' . $count;
		$order_by = ' ORDER BY t1.id DESC ';
		$cond .= " GROUP BY t1.id ";
		$sql = "SELECT t1.*,t3.name as doctor_name,t3.title as doctor_title,t2.name as hospital_name,t4.name as department_name,t5.reg_date,t5.reg_order,t5.reg_time,t5.week_day,t5.price,t6.name as patient_name FROM " . DB_PREFIX . "yuyue t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2 
					ON t1.hospital_id = t2.hospital_id 
				LEFT JOIN " . DB_PREFIX ."departments t4 
					ON t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id
				LEFT JOIN " . DB_PREFIX . "doctor t3 
					ON t1.doctor_id = t3.doctor_id AND t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id 
				LEFT JOIN " . DB_PREFIX . "schedules t5
					ON t1.schedule_id = t5.schedule_id AND t1.doctor_id = t3.doctor_id AND t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id 
				LEFT JOIN " . DB_PREFIX . "patient t6 
					ON t1.patient_id = t6.id
				WHERE 1 " . $cond . $order_by . $limit; 
		
		//echo $sql;
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q)) 
		{	
			$row['reg_time'] = $this->settings['regTime'][$row['reg_time']] ? $this->settings['regTime'][$row['reg_time']] : $row['reg_time'];
			
			
			//时间判断，就诊时间小于当前时间变为已就诊
			if(($row['reg_order'] < TIMENOW) && !$row['status'])
			{
				$row['status'] = 1;
			}
			
			if($row['status'] == 1)
			{
				$row['status_text'] = '已就诊';
			}
			else if($row['status'] == 2)
			{
				$row['status_text'] = '已取消';
			}
			else 
			{
				$row['status_text'] = '待就诊';
			}
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	
	public function get_condition()
	{
		$member_id = intval($this->user['user_id']);
		if(!$member_id)
		{
			$this->errorOutput(NOID);
		}
		$condition = '';
		//站点名称
		$condition .= " AND t1.member_id = {$member_id}";
		
		//$condition .= " AND t1.status = 1";
		
		return $condition ;
	}
	
	public function yuyue()
	{
		$yuyue_url = $this->settings['yuyue_url'];
		if(!$yuyue_url)
		{
			$this->errorOutput('暂时不提供此功能');
		}
		
		$member_id		= intval($this->user['user_id']);
		
		if(!$member_id)
		{
			$this->errorOutput('请先登陆');
		}
		
		$hospital_id 	= intval($this->input['hospital_id']);
		$depart_id		= intval($this->input['department_id']);
		$doctor_id		= intval($this->input['doctor_id']);
		$schedule_id	= intval($this->input['schedule_id']);
		$patient_id		= intval($this->input['patient_id']);
		
		if(!$hospital_id)
		{
			$this->errorOutput('医院id不存在');
		}
		
		if(!$depart_id)
		{
			$this->errorOutput('科室id不存在');
		}
		
		if(!$doctor_id)
		{
			$this->errorOutput('医生id不存在');
		}
		
		if(!$schedule_id)
		{
			$this->errorOutput('预约号id不存在');
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "patient WHERE id = {$patient_id}";
		$res = $this->db->query_first($sql);
		
		$patient_name 	= $res['name'];
		$idCardNo		= $res['id_card'];
		$mobile			= $res['cellphone'];
		
		
		$sql = "SELECT card_num FROM " . DB_PREFIX . "medical_card WHERE patient_id = {$patient_id} AND hospital_id = {$hospital_id}";
		$res = array();
		$res = $this->db->query_first($sql);
		
		$medicalCardNo = $res['card_num'];
		
		
		$post_data = array(
			'hospitalId'	=> $hospital_id,
			'departmentId'	=> $depart_id,
			'doctorId'		=> $doctor_id,
			'scheduleId'	=> $schedule_id,
			'patientName'	=> $patient_name,
			'medicalCardNo'	=> $medicalCardNo,
			'idCardNo'		=> $idCardNo,
			'mobile'		=> $mobile,
		);
		$this->url = $this->settings['yuyue_url'];
	
		$res = $this->_curl($post_data);
		
		//hg_pre($res);
		if(!$res['success'])
		{
			$res['errMsg'] = $res['errMsg'] ? $res['errMsg'] : '预约失败';
			$this->errorOutput($res['errMsg']);
		}
		$data = array(
			'member_id'		=> $member_id,
			'hospital_id'	=> $hospital_id,
			'department_id'	=> $depart_id,
			'doctor_id'		=> $doctor_id,
			'schedule_id'	=> $schedule_id,
			'patient_id'	=> $patient_id,
			'yuyue_id'		=> $res['data'],
			'create_time'	=> TIMENOW,
		);
		
		$id = $this->_add($data,'yuyue');
		
		//记录预约号信息
		$this->sch_insert();
		
		$data['id'] = $id;
		$this->addItem($data);
		
		$this->output();
	}
	
	//取消预约
	public function cancel_yuyue()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->settings['cancel_yuyue'])
		{
			$this->errorOutput('暂时不提供此功能');
		}
		
		$member_id		= intval($this->user['user_id']);
		if(!$member_id)
		{
			$this->errorOutput('请先登陆');
		}
		
		
		$this->url = $this->settings['cancel_yuyue'];
		
		
		$sql = "SELECT yuyue_id,hospital_id,schedule_id FROM " . DB_PREFIX . "yuyue WHERE member_id= " . $member_id . " AND id = {$id}";
		$res = $this->db->query_first($sql);
		
		if(!$res)
		{
			$this->errorOutput('预约信息异常');
		}
		$post_data = array(
			'zhpTradeId'	=> $res['yuyue_id'],
			'hospitalId'	=> $res['hospital_id'],
			'order_id'		=> $res['schedule_id'],
		);
		
		$ret = $this->_curl($post_data);
		//hg_pre($ret);
		if($ret)
		{
			$sql = "UPDATE " . DB_PREFIX . "yuyue SET status = 2 WHERE id = {$id}";
			$this->db->query($sql);
			
			
			$data['id'] = $id;
			$this->addItem($data);
		}
		
		$this->output();
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT t1.*,t3.name as doctor_name,t3.title as doctor_title,t2.name as hospital_name,t4.name as department_name,t5.reg_date,t5.reg_order,t5.reg_time,t5.week_day,t5.price,t6.name as patient_name,t6.id_card FROM " . DB_PREFIX . "yuyue t1 
				LEFT JOIN " . DB_PREFIX . "hospital t2 
					ON t1.hospital_id = t2.hospital_id 
				LEFT JOIN " . DB_PREFIX ."departments t4 
					ON t1.department_id = t4.department_id AND t1.hospital_id = t4.hospital_id
				LEFT JOIN " . DB_PREFIX . "doctor t3 
					ON t1.doctor_id = t3.doctor_id AND t1.hospital_id = t4.hospital_id 
				LEFT JOIN " . DB_PREFIX . "schedules t5
					ON t1.schedule_id = t5.schedule_id AND t1.hospital_id = t4.hospital_id 
				LEFT JOIN " . DB_PREFIX . "patient t6 
					ON t1.patient_id = t6.id
				WHERE 1 AND t1.id = {$id}";
		$row = $this->db->query_first($sql);
		
		$row['reg_time'] = $this->settings['regTime'][$row['reg_time']] ? $this->settings['regTime'][$row['reg_time']] : $row['reg_time'];
			
		
		//时间判断，就诊时间小于当前时间变为已就诊
		if(($row['reg_order'] < TIMENOW) && !$row['status'])
		{
			$row['status'] = 1;
		}
			
		if($row['status'] == 1)
		{
			$row['status_text'] = '已就诊';
		}
		else if($row['status'] == 2)
		{
			$row['status_text'] = '已取消';
		}
		else 
		{
			$row['status_text'] = '待就诊';
		}
		$this->addItem($row);
		$this->output();
	}
	
	private function _curl($post_data)
	{
		$token = $this->settings['hospital_token'];
		
		
		if(!$this->url || !$token)
		{
			return false;
		}
		
		$post_data['token'] = $token;
		
		$header[] = "charset=UTF-8";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		
		
		$ret = '';
		$ret = curl_exec($ch);
		curl_close($ch);//关闭
		
		if($ret)
		{
			$res = json_decode($ret,1);
		}
		return $res;
	}
	
	private function _add($data=array(),$table)
	{
		if(!$data || !$table)
		{
			return false;
		}
		
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		$id =  $this->db->insert_id();	
		
		return $id;
	}
	
	//更新
	private function _upd($data=array(),$table,$where)
	{
		if(empty($data) || !$table)
		{
			return false;
		}
		
		if($table == '' or $where == '') 
		{
			return false;
		}
		
		$where = ' WHERE 1 '.$where;
		$field = '';
		
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET ' . $field . $where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function sch_insert()
	{
		$hospital_id 	= intval($this->input['hospital_id']);
		$depart_id 		= intval($this->input['department_id']);
		$doctor_id 		= intval($this->input['doctor_id']);
		$schedule_id 	= intval($this->input['schedule_id']);
		if(!$hospital_id || !$depart_id || !$doctor_id || !$schedule_id)
		{
			return false;
		}
		
		$post_data = array(
			'hospitalId'		=> $hospital_id,
			'departmentId'		=> $depart_id,
			'doctorId'			=> $doctor_id,
		);
		
		$this->url = $this->settings['schedules_url'];
		$res = array();
		$res = $this->_curl($post_data);
		
		//hg_pre($res);exit();
		if(!$res['success'])
		{
			return false;
		}
		
		if(is_array($res['data']) && count($res['data']))
		{
			foreach ($res['data'] as $k => $row)
			{
				if($row['scheduleId'] != $schedule_id)
				{
					continue;
				}
			
				$sch = array(
					'hospital_id' 		=> $hospital_id,
					'department_id'		=> $depart_id,
					'doctor_id'			=> $doctor_id,
					'schedule_id'		=> $row['scheduleId'],
					'left_num'			=> $row['leftNum'],
					'call_type'			=> $row['outCallType'],
					'price'				=> $row['price'],
					'reg_date'			=> $row['regDate'],
					'remark'			=> $row['remark'],
					'reg_time'			=> $row['regTime'],
					'week_day'			=> $row['weekDay'],
					'status'			=> 1,
				);
			}
			
			if(!empty($sch))
			{
				$table = 'schedules';
				if($sch['reg_time'] == 'MORNING')
				{
					$sch['reg_order'] = strtotime($sch['reg_date'] . ' 12:00');
				}
				else if($sch['reg_time'] == 'AFTERNOON')
				{
					$sch['reg_order'] = strtotime($sch['reg_date'] . ' 18:00');
				}
				else
				{
					$sch['reg_order'] = strtotime($sch['reg_date'] . ' 18:00');
				} 
				
				$sql = "SELECT id FROM " . DB_PREFIX . "schedules WHERE hospital_id = {$hospital_id} AND department_id = {$depart_id} AND doctor_id = {$doctor_id} AND schedule_id = {$schedule_id}";
				$res = $this->db->query_first($sql);
				
				if(!$res)
				{
					$this->_add($sch,$table);
				}
				else 
				{
					$where = " AND hospital_id = {$hospital_id} AND schedule_id = {$schedule_id}";
					$this->_upd($sch,$table, $where);
				}
			}
		}
	}
}
include(ROOT_PATH . 'excute.php');
?>