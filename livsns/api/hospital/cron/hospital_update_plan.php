<?php
define('MOD_UNIQUEID','hospital');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('SCRIPT_NAME', 'HospitalPlan');
class HospitalPlan extends cronBase
{
	private $post_data = array();
	private $url = '';
	private $material_pic;
					
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '医院信息计划任务更新',	 
			'brief' => '医院信息计划任务更新',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,		//默认是否启用
		);
		$this->addItem($array);
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
		
		//hg_pre($post_data);
		$header[] = "charset=UTF-8";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_TIMEOUT,3);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		
		
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
	
	//插入素材表
	public function insert_material($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function doctor_avatar($id,$hospital_id)
	{
		$token = $this->settings['hospital_token'];
		
		if(!$this->url || !$token || !$id || !$hospital_id)
		{
			return false;
		}
		
		$header[] = "charset=UTF-8";
		$url = $this->url . '?token=' . $token . '&hospitalId=' . $hospital_id . '&picName=' . $id;
		
		//echo $url;
		$ch = curl_init ();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		$response = curl_exec($ch);
		
		if($response)
		{
			$response = base64_encode($response);
			
			$img_info = array();
			$img_info = $this->material_pic->imgdata2pic($response);
			
			//print_r($img_info);
			if(!$img_info[0])
			{
				return false;
			}
			$img_info = $img_info[0];
			$indexpic_id = $this->insert_material($img_info);
			
			$indexpic_id = $indexpic_id ? $indexpic_id : 0;
			return $indexpic_id;
		}
	}
	
	
	public function show()
	{
		if($this->settings['hospital_switch'] && $this->settings['hospital_url'])
		{
			$this->url = $this->settings['hospital_url'];
			//查询医院信息
			$res = $this->_curl($post_data);
		}
		
		if($res)
		{
			$hospital_data = array();
			
			if(!empty($res))
			{
				foreach ($res as $val)
				{
					$hospital_data[$val['hospitalId']] = $val;
				}
			}
			
			$sql = "SELECT id,hospital_id FROM ".DB_PREFIX."hospital WHERE status !=3";
			$q = $this->db->query($sql);
			
			$hospital_local = array();
			while($r = $this->db->fetch_array($q))
			{
				$hospital_local[$r['hospital_id']]['id'] = $r['id'];
			}
			
			$add_arr = array_diff_key($hospital_data, $hospital_local);
			$del_arr = array_diff_key($hospital_local, $hospital_data);
			$upd_arr = array_intersect_key($hospital_data,$hospital_local);
			
			$upd_arr_loc = array_intersect_key($hospital_local,$hospital_data);
			$table = 'hospital';
			
			//hg_pre($del_arr,0);
			if(!empty($del_arr))
			{
				foreach ($del_arr as $val)
				{
					$id_arr[] = $val['id'];
				}
				
				$ids = implode(',', $id_arr);
				$this->_del($ids, $table);
			}
		
			
			if(!empty($upd_arr))
			{
				foreach ($upd_arr as $key => $val)
				{
					$id = '';
					$where = '';
					$upd_tmp = array();
					
					$upd_tmp['name'] = $val['hospitalName'];
					
					$id = $upd_arr_loc[$key]['id'];
					
					if(!$id)
					{
						continue;
					}
					
					$where = ' AND id = ' . $id;
					$this->_upd($upd_tmp, $table, $where);
				}
			}
			
			//hg_pre($add_arr,0);
			if(!empty($add_arr))
			{
				$data = array();
				$user_name 	= $this->user['user_name'];

				foreach ($add_arr as $val)
				{
					$data = array(
						'create_time'	=> TIMENOW,
						'update_time'	=> TIMENOW,
						'user_name'		=> $user_name,
						'hospital_id' 	=> $val['hospitalId'],
						'name'			=> $val['hospitalName'],
					);
					
					$this->_add($data,$table);
				}
			}
		}
		#####################医院处理结束########################
		
		//处理科室
		if($this->settings['departments_switch'] && $this->settings['departments_url'])
		{
			$this->url = $this->settings['departments_url'];
			//查询医院信息
			$sql = "SELECT hospital_id FROM " . DB_PREFIX . "hospital WHERE status = 1";
			$q = $this->db->query($sql);
			
			
			$hospital_id = array();
			while ($r = $this->db->fetch_array($q))
			{
				$hospital_id[] = $r['hospital_id'];
			}
			
			
			if(!empty($hospital_id))
			{
				foreach ($hospital_id as $v)
				{
					$post_data = array(
						'hospitalId' => $v,
					);
					
					$depart_res[$v] = $this->_curl($post_data);
				}
				
				$depart_data = array();
				//hg_pre($depart_res,0);
				if(!empty($depart_res))
				{
					foreach ($depart_res as $key => $val)
					{
						if(empty($val))
						{
							continue;
						}
						foreach ($val as $v)
						{
							$depart_child[$key][$v['departmentId']] = $v['childDeptList'];
							unset($v['childDeptList']);
							$depart_data[$key][$v['departmentId']] = $v;
						}
					}
				}
				
				$table = 'departments';
				if($depart_data)
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "departments WHERE status !=3";
					$q = $this->db->query($sql);
					
					$depart_loca = array();
					$depart_loca_child = array();
					while ($r = $this->db->fetch_array($q))
					{
						if(!$r['fid'])
						{
							$depart_loca[$r['hospital_id']][$r['department_id']] = $r['id'];
						}
						else 
						{
							$depart_loca_child[$r['hospital_id']][$r['fid']][$r['department_id']] = $r;
						}
					}
					//hg_pre($depart_child,1);
					foreach ($depart_data as $key => $val)
					{
						$depart_loca = $depart_loca[$key];
						$depart_loca = is_array($depart_loca) ? $depart_loca : array();
						$depart_add = array_diff_key($val, $depart_loca);
						$depart_del = array_diff_key($depart_loca, $val);
						$depart_upd = array_intersect_key($val,$depart_loca);
						
						
						
						//父级科室更新
						if(!empty($depart_upd))
						{
							foreach ($depart_upd as $k => $v)
							{
								$where = '';
								$data = array(
									'name'				=> $v['name'],
									'short_pinyin' 		=> $v['shortPinyin'],
								);
								
								//更新父科室
								$where = ' AND department_id = ' . $v['departmentId'];
								$this->_upd($data,$table, $where);
							}
							
							//处理子科室
							$depart_child_tmp = array();
							$depart_child_tmp = $depart_child[$key][$k];
							$depart_childs = array();
							
							if($depart_child_tmp)
							{
								foreach ($depart_child_tmp as $vv)
								{
									$depart_childs[$vv['departmentId']] = $vv;
								}
							}
						
							$depart_child_add = array();
							$depart_child_del = array();
							$depart_child_upd = array();
							
							$depart_child_add = array_diff_key($depart_childs, is_array($depart_loca_child[$key][$k])?$depart_loca_child[$key][$k]:array());
							$depart_child_del = array_diff_key(is_array($depart_loca_child[$key][$k])?$depart_loca_child[$key][$k]:array(), $depart_childs);
							$depart_child_upd = array_intersect_key($depart_childs,is_array($depart_loca_child[$key][$k])?$depart_loca_child[$key][$k]:array());
							
							//hg_pre($depart_child_del,0);
							//更新子科室
							if(!empty($depart_child_upd))
							{
								foreach ($depart_child_upd as $depart_child_v)
								{
									$where = '';
									$data = array(
										'name'				=> $depart_child_v['name'],
										'short_pinyin' 		=> $depart_child_v['shortPinyin'],
									);
									
									$where = ' AND department_id = ' . $depart_child_v['departmentId'];
									$this->_upd($data,$table, $where);
								}
							}
							
							
							//添加子科室
							if(!empty($depart_child_add))
							{
								foreach ($depart_child_add as $depart_child_add_v)
								{
									$data = array(
										'department_id'	=> $depart_child_add_v['departmentId'],
										'hospital_id' 	=> $depart_child_add_v['hospitalId'],
										'short_pinyin'	=> $depart_child_add_v['shortPinyin'],
										'name'			=> $depart_child_add_v['name'],
										'fid'			=> $k,
									);
									
									$this->_add($data,$table);
								}
								//hg_pre($depart_child_add);
								//hg_pre($depart_childs);
							}
						
							
							//删除子科室
							if(!empty($depart_child_del))
							{
								foreach ($depart_child_del as $depart_child_del_v)
								{
									$depart_child_del_id[] = $depart_child_del_v['id'];
								}
								
								$depart_child_del_ids = '';
								$depart_child_del_ids = implode(',', $depart_child_del_id);
								
								$this->_del($depart_child_del_ids, $table);
							}
						}
						
						
						if(!empty($depart_add))
						{
							//hg_pre($depart_add,1);
							
							foreach ($depart_add as $depart_add_key => $depart_add_val)
							{
								$data = array(
									'department_id'	=> $depart_add_val['departmentId'],
									'hospital_id' 	=> $depart_add_val['hospitalId'],
									'short_pinyin'	=> $depart_add_val['shortPinyin'],
									'name'			=> $depart_add_val['name'],
								);
								
								$this->_add($data,$table);
								
								
								$depart_child_add = array();
								$depart_child_add = $depart_child[$key][$depart_add_key];
								
								
								if(!empty($depart_child_add))
								{
									foreach ($depart_child_add as $depart_child_add_v)
									{
										$data = array(
											'department_id'	=> $depart_child_add_v['departmentId'],
											'introduction'	=> $depart_child_add_v['introduction'],
											'hospital_id' 	=> $depart_child_add_v['hospitalId'],
											'short_pinyin'	=> $depart_child_add_v['shortPinyin'],
											'name'			=> $depart_child_add_v['name'],
											'fid'			=> $depart_add_key,
										);
										$this->_add($data,$table);
									}
								}
							}
						}
						
						//删除科室
						if(!empty($depart_del))
						{
							
							$depart_del_id = array();
							foreach ($depart_del as $depart_del_val)
							{
								$depart_del_id[] = $depart_del_val;
							}
							//hg_pre($depart_del_id,0);
							if(!empty($depart_del_id))
							{
								$depart_del_ids = implode(',', $depart_del_id);
								
								
								$sql = "SELECT id FROM " . DB_PREFIX . $table . " WHERE hospital_id = {$key} AND fid IN ( {$depart_del_ids} )";
								$q = $this->db->query($sql);
								
								
								while ($r = $this->db->fetch_array($q))
								{
									$depart_del_id[] = $r['id'];
								}
								
								$depart_del_ids = implode(',', $depart_del_id);
								$this->_del($depart_del_ids, $table);
							}
						}
					}
				}
			}
		}
		##################科室处理结束###############
		

		//医生
		if($this->settings['doctor_switch'] && $this->settings['doctor_url'])
		{
			$this->url = $this->settings['doctor_url'];
			$sql = "SELECT hospital_id,department_id FROM " . DB_PREFIX . "departments WHERE status != 3 AND fid != 0";
			$q = $this->db->query($sql);
			
			
			$depart_id = array();
			while($r = $this->db->fetch_array($q))
			{
				if(!$r['hospital_id'] || !$r['department_id'])
				{
					continue;
				}
				$depart_id[$r['hospital_id']][] = $r['department_id'];
			}
			
			//hg_pre($depart_id,1);
			if(!empty($depart_id))
			{
				$doc_data = array();
				foreach ($depart_id as $hos_id => $dep_val)
				{
					if(empty($dep_val))
					{
						continue;
					}
					foreach ($dep_val as $dep_id)
					{
						$post_data = array(
							'hospitalId'		=> $hos_id,
							'departmentId'		=> $dep_id,
						);
						
						$ret = '';
						$ret = $this->_curl($post_data);
						
						if(!$ret)
						{
							continue;
						}
						$doc_data[$hos_id  . '+' . $dep_id] = $ret;
					}
				}
				//hg_pre($doc_data,1);
				if(!empty($doc_data))
				{
					//查询本地医生
					$sql = "SELECT hospital_id,department_id,doctor_id,id FROM " . DB_PREFIX . "doctor WHERE status !=3";
					$q = $this->db->query($sql);
					$doctor_local = array();
					while ($r = $this->db->fetch_array($q))
					{
						if(!$r['hospital_id'] || !$r['department_id'] || !$r['doctor_id'])
						{
							continue;
						}
						$doctor_local[$r['hospital_id'] . '+' . $r['department_id']][$r['doctor_id']] = $r['id'];
					}
					
					//hg_pre($doctor_local,1);
					$table = 'doctor';
					$this->url = $this->settings['doctor_avatar'];
					
					
					include_once(ROOT_PATH.'lib/class/material.class.php');
					$this->material_pic = new material();
					
					foreach ($doc_data as $hos_key => $doc_val)
					{
						if(empty($doc_val))
						{
							continue;
						}
						
						$hospital_id 	= '';
						$depart_id		= '';
						
						$doc_key_tmp = array();
						$doc_key_tmp = explode('+', $hos_key);
						
						$hospital_id 	= $doc_key_tmp[0];
						$depart_id		= $doc_key_tmp[1];
						
						
						if(!$doctor_local[$hos_key])
						{
							foreach ($doc_val as $doc_v)
							{
								$doc_info = array(
									'hospital_id'		=> $doc_v['hospitalId'],
									'department_id'		=> $doc_v['departmentId'],
									'doctor_id'			=> $doc_v['doctorId'],
									'name'				=> $doc_v['name'],
									'title'				=> $doc_v['title'],
									'short_pinyin'		=> $doc_v['shortPinyin'],
									'speciality'		=> $doc_v['speciality'],
									'introduction'		=> $doc_v['introduction'],
									'pic_name'			=> $doc_v['picName'],
								);
								
								if($doc_v['picName'])
								{
									$indexpic_id = '';
									$indexpic_id = $this->doctor_avatar($doc_v['picName'], $doc_v['hospitalId']);
								}
								if($indexpic_id)
								{
									$doc_info['indexpic_id'] = $indexpic_id;
								}
								//hg_pre($doc_info,0);
								$this->_add($doc_info,$table);
							}
						}
						else 
						{
							$doc_add = array();
							$doc_del = array();
							$doc_upd = array();
							
							$doc_api = array();
							foreach ($doc_val as $doc_tmp_v)
							{
								$doc_api[$doc_tmp_v['doctorId']] = $doc_tmp_v;
							}
//hg_pre($doctor_local);
							$doc_id = array();
							foreach ($doctor_local[$hos_key] as $k => $v)
							{
								$doc_id[$k] = $v;
							}
//hg_pre($doc_id);
//hg_pre($doc_api,0);
							//医生对比
							$doc_add = array_diff_key($doc_api, $doc_id);
							$doc_del = array_diff_key($doc_id, $doc_api);
							$doc_upd = array_intersect_key($doc_api, $doc_id);
							
							//hg_pre($doc_add);
							//hg_pre($doc_del);
							//hg_pre($doc_upd,0);
							if(!empty($doc_add))
							{
								//接口返回记录本地不存在，直接入库
								foreach ($doc_add as $doc_add_v)
								{
									$doc_info = array(
										'hospital_id'		=> $doc_add_v['hospitalId'],
										'department_id'		=> $doc_add_v['departmentId'],
										'doctor_id'			=> $doc_add_v['doctorId'],
										'name'				=> $doc_add_v['name'],
										'title'				=> $doc_add_v['title'],
										'short_pinyin'		=> $doc_add_v['shortPinyin'],
										'speciality'		=> $doc_add_v['speciality'],
										'introduction'		=> $doc_add_v['introduction'],
										'pic_name'			=> $doc_add_v['picName'],
									);
									
									if($doc_add_v['picName'])
									{
										$indexpic_id = '';
										$indexpic_id = $this->doctor_avatar($doc_add_v['picName'], $doc_add_v['hospitalId']);
									}
									if($indexpic_id)
									{
										$doc_info['indexpic_id'] = $indexpic_id;
									}
									//hg_pre($doc_info,0);
									$this->_add($doc_info,$table);
								}
							}
							
							if(!empty($doc_upd))
							{
								foreach ($doc_upd as $doc_v)
								{
									$info = array(
										'hospital_id'		=> $doc_v['hospitalId'],
										'department_id'		=> $doc_v['departmentId'],
										'doctor_id'			=> $doc_v['doctorId'],
										'name'				=> $doc_v['name'],
										'title'				=> $doc_v['title'],
										'short_pinyin'		=> $doc_v['shortPinyin'],
										'speciality'		=> $doc_v['speciality'],
										'introduction'		=> $doc_v['introduction'],
										'pic_name'			=> $doc_v['picName'],
									);
									
									if($doc_v['picName'])
									{
										$indexpic_id = '';
										$indexpic_id = $this->doctor_avatar($doc_v['picName'], $doc_v['hospitalId']);
									}
									if($indexpic_id)
									{
										$info['indexpic_id'] = $indexpic_id;
									}
									
									$where = '';
									
									$where = " AND hospital_id = {$hospital_id} AND doctor_id = {$doc_v['doctorId']}";
									
									$this->_upd($info,$table, $where);
								}	
							}
							
							
							if(!empty($doc_del))
							{
								$doc_del_id = array();
								foreach ($doc_del as $doc_del_v)
								{
									$doc_del_id[] = $doc_del_v;
								}
								
								
								if(!empty($doc_del_id))
								{
									$doc_del_ids = implode(',', $doc_del_id);
									
									$this->_del($doc_del_ids, $table);
								}
								
								//hg_pre($sch_del,0);
							}
						}
					}
				}
			}
		}
		#######################医生处理结束##############################
		
		
		//预约号
		if($this->settings['schedules_switch'] && $this->settings['schedules_url'])
		{
			$this->url = $this->settings['schedules_url'];
			
			//查询医生
			$sql = "SELECT hospital_id,department_id,doctor_id FROM " . DB_PREFIX . "doctor WHERE status !=3";
			$q = $this->db->query($sql);
			
			$doctor_res = array();
			while ($r = $this->db->fetch_array($q))
			{
				if(!$r['hospital_id'] || !$r['department_id'] || !$r['doctor_id'])
				{
					continue;
				}
				$doctor_res[] = $r;
			}
			
			//查询已有的预约号记录
			$sql = "SELECT id,hospital_id,department_id,doctor_id,schedule_id FROM " . DB_PREFIX . "schedules WHERE status !=3";
			$q = $this->db->query($sql);
			
			$schedule_local = array();
			while ($r = $this->db->fetch_array($q))
			{
				if(!$r['hospital_id'] || !$r['department_id'] || !$r['doctor_id'])
				{
					continue;
				}
				$sch_key = '';
				$sch_key = $r['hospital_id'] . '+' . $r['department_id'] . '+' . $r['doctor_id'];
				$schedule_local[$sch_key][$r['schedule_id']]['id'] = $r['id'];
			}		
					
					
			//hg_pre($schedule_local,0);
			if(!empty($doctor_res))
			{
				$schedules_api = array();
				foreach ($doctor_res as $schedu_val)
				{
					$post_data = array(
						'hospitalId'		=> $schedu_val['hospital_id'],
						'departmentId'		=> $schedu_val['department_id'],
						'doctorId'			=> $schedu_val['doctor_id'],
					);
					
					$res = array();
					$res = $this->_curl($post_data);
					//hg_pre($res);
					if(empty($res))
					{
						continue;
					}
					
					//$res['hospital_id'] 	= $schedu_val['hospital_id'];
					//$res['departmentId']	= $schedu_val['department_id'];
					//$res['doctorId']		= $schedu_val['doctor_id'];
					
					$schedule_key = $schedu_val['hospital_id'] . '+' . $schedu_val['department_id'] . '+' . $schedu_val['doctor_id'];
					$schedules_api[$schedule_key] = $res;
				}
				
				//hg_pre($schedules_api,0);
				
				if(!empty($schedules_api))
				{
					$table = 'schedules';
					foreach ($schedules_api as $schedu_key => $sch_val)
					{
						if(empty($sch_val))
						{
							continue;
						}
						$hospital_id 	= '';
						$depart_id		= '';
						$doc_id			= '';
						
						$schedu_key_tmp = array();
						$schedu_key_tmp = explode('+', $schedu_key);
						
						$hospital_id 	= $schedu_key_tmp[0];
						$depart_id		= $schedu_key_tmp[1];
						$doc_id			= $schedu_key_tmp[2];
							
						//接口返回记录本地不存在，直接入库
						if(!$schedule_local[$schedu_key])
						{
							foreach ($sch_val as $sch_v)
							{
								$info = array(
									'doctor_id'			=> $doc_id,
									'hospital_id'		=> $hospital_id,
									'department_id'		=> $depart_id,
									'schedule_id'		=> $sch_v['scheduleId'],
									'left_num'			=> $sch_v['leftNum'],
									'call_type'			=> $sch_v['outCallType'],
									'price'				=> $sch_v['price'],
									'reg_date'			=> $sch_v['regDate'],
									'reg_time'			=> $sch_v['regTime'],
									'remark'			=> $sch_v['remark'],
									'week_day'			=> $sch_v['weekDay'],
									'status'			=> 1,
								);
								if($info['reg_time'] == 'MORNING')
								{
									$info['reg_order'] = strtotime($info['reg_date'] . ' 09:00');
								}
								else if($info['reg_time'] == 'AFTERNOON')
								{
									$info['reg_order'] = strtotime($info['reg_date'] . ' 14:00');
								}
								$this->_add($info,$table);
							}
						}
						else //本地存在，比对
						{
							
							$sch_add = array();
							$sch_del = array();
							$sch_upd = array();
							
							$sch_api = array();
							foreach ($sch_val as $sch_tmp_v)
							{
								$sch_api[$sch_tmp_v['scheduleId']] = $sch_tmp_v;
							}

							//预约对比
							$sch_add = array_diff_key($sch_api, $schedule_local[$schedu_key]);
							$sch_del = array_diff_key($schedule_local[$schedu_key], $sch_api);
							$sch_upd = array_intersect_key($sch_api, $schedule_local[$schedu_key]);
							
							//hg_pre($sch_add);
							//hg_pre($sch_del);
							//hg_pre($sch_upd,0);
							if(!empty($sch_add))
							{
								
								//接口返回记录本地不存在，直接入库
								foreach ($sch_add as $sch_add_v)
								{
									$info = array(
										'doctor_id'			=> $doc_id,
										'hospital_id'		=> $hospital_id,
										'department_id'		=> $depart_id,
										'schedule_id'		=> $sch_add_v['scheduleId'],
										'left_num'			=> $sch_add_v['leftNum'],
										'call_type'			=> $sch_add_v['outCallType'],
										'price'				=> $sch_add_v['price'],
										'reg_date'			=> $sch_add_v['regDate'],
										'reg_time'			=> $sch_add_v['regTime'],
										'remark'			=> $sch_add_v['remark'],
										'week_day'			=> $sch_add_v['weekDay'],
										'status'			=> 1,
									);
									
									if($info['reg_time'] == 'MORNING')
									{
										$info['reg_order'] = strtotime($info['reg_date'] . ' 09:00');
									}
									else if($info['reg_time'] == 'AFTERNOON')
									{
										$info['reg_order'] = strtotime($info['reg_date'] . ' 14:00');
									}
								
									$this->_add($info,$table);
								}
							}
							
							if(!empty($sch_upd))
							{
								foreach ($sch_upd as $sch_upd_v)
								{
									$info = array(
										'doctor_id'			=> $doc_id,
										'hospital_id'		=> $hospital_id,
										'department_id'		=> $depart_id,
										'schedule_id'		=> $sch_upd_v['scheduleId'],
										'left_num'			=> $sch_upd_v['leftNum'],
										'call_type'			=> $sch_upd_v['outCallType'],
										'price'				=> $sch_upd_v['price'],
										'reg_date'			=> $sch_upd_v['regDate'],
										'reg_time'			=> $sch_upd_v['regTime'],
										'remark'			=> $sch_upd_v['remark'],
										'week_day'			=> $sch_upd_v['weekDay'],
									);
									
									if($info['reg_time'] == 'MORNING')
									{
										$info['reg_order'] = strtotime($info['reg_date'] . ' 09:00');
									}
									else if($info['reg_time'] == 'AFTERNOON')
									{
										$info['reg_order'] = strtotime($info['reg_date'] . ' 14:00');
									}
									
									$where = '';
									
									$where = " AND hospital_id = {$hospital_id} AND schedule_id = {$sch_upd_v['scheduleId']}";
									
									$this->_upd($info,$table, $where);
								}	
							}
							
							
							if(!empty($sch_del))
							{
								$sch_del_id = array();
								foreach ($sch_del as $sch_del_v)
								{
									$sch_del_id[] = $sch_del_v['id'];
								}
								
								
								if(!empty($sch_del_id))
								{
									$sch_del_ids = implode(',', $sch_del_id);
									
									$this->_del($sch_del_ids, $table);
								}
								
								//hg_pre($sch_del,0);
							}
						}
					}
				}
			}
		}
	}
	
	
	
	//删除
	private function _del($ids,$table)
	{
		if(!$ids || !$table)
		{
			return false;
		}
		//删除,更新站点状态为3,标为已下线
		$sql = "UPDATE " . DB_PREFIX . $table . " SET status = 3 WHERE id IN (" . $ids . ")";
		$this->db->query($sql);
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
		
		
		
		$sql = "";
		$sql = " UPDATE ".DB_PREFIX . $table . " SET order_id = {$id}  WHERE id = {$id}";
		$this->db->query($sql);
		
		return $id;
	}
}
include(ROOT_PATH . 'excute.php');