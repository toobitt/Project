<?php
define('MOD_UNIQUEID','carpark');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/carpark_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class carpark_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{		
		parent::__construct();
		$this->mode = new carpark_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['district_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'carpark_district WHERE id IN('.$this->input['district_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		switch ($status)
		{
			case 0:$status=1;break;
			case 1:$status=2;break;
			case 2:$status=3;break;
			default:break;
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!trim($this->input['name']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if(!intval($this->input['district_id']))
		{
			$this->errorOutput('请选择区域划分！');
		}
		$business_time = array();
		if($this->input['date'])
		{
			$dateArr = $this->input['date'];
			$b_stime = $this->input['b_stime'];
			$b_etime = $this->input['b_etime'];
			foreach ($dateArr AS $k => $v)
			{
				$business_time[] = array(
					'date' 	=> $v,
					'stime' => $b_stime[$v],
					'etime' => $b_etime[$v],
				);
			}
		}
		$parking_num = trim($this->input['parking_num']);
		$ret_num = $this->check_num_exist($parking_num);
		if($ret_num)
		{
			$this->errorOutput('编号已存在！');
		}
		
		$main_data = array(
			'name' 				=> $this->input['name'],
			'parking_num' 		=> $parking_num,
			'parking_space' 	=> $this->input['parking_space'],
			'empty_space' 		=> $this->input['empty_space'],
		    'status'            => $status ? $status : 0,
			'tel' 				=> $this->input['tel'],
			'district_id' 		=> $this->input['district_id'],
			'type_id' 			=> $this->input['type_id'],
			'unit_id' 			=> $this->input['unit_id'],
			'icp_number' 		=> $this->input['icp_number'],
			'main_device' 		=> $this->input['main_device'],
			'description' 		=> $this->input['description'],
			'baidu_longitude' 	=> $this->input['baidu_longitude'],
			'baidu_latitude' 	=> $this->input['baidu_latitude'],
			'entrance_num' 		=> $this->input['entrance_num'],
			'exitus_num' 		=> $this->input['exitus_num'],
			'is_inout_same' 	=> $this->input['is_inout_same']?1:0,
			'limited_height' 	=> $this->input['limited_height'],
			'building_storey' 	=> $this->input['building_storey'],
			'struct_type' 		=> ($this->input['struct_type'] && is_array($this->input['struct_type']))?implode(',',$this->input['struct_type']):'',
			'other_struct_type' => $this->input['other_struct_type'],
			'address'			=> $this->input['address'],
			'province_id' 		=> $this->input['province_id'],
			'city_id' 			=> $this->input['city_id'],
			'area_id' 			=> $this->input['area_id'],
			'price_text'		=> $this->input['price_text'],
			'price_brief'		=> $this->input['price_brief'],
			'business_time'		=> $business_time?serialize($business_time):'',
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip'				=> hg_getip(),
		    'org_id'            => $this->user['org_id'],
		);
		
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($main_data['baidu_latitude'] && $main_data['baidu_longitude'])
		{
			$gps = FromBaiduToGpsXY($main_data['baidu_longitude'],$main_data['baidu_latitude']);
			$main_data['GPS_x'] = $gps['GPS_x'];
			$main_data['GPS_y'] = $gps['GPS_y'];
		}
		else
		{
			$main_data['GPS_x'] = 0;
			$main_data['GPS_y'] = 0;
		}

		//此处处理服务时间
		$server_time = $this->input['server_time'];
		$server_time_data = array();
		if($server_time && !empty($server_time))
		{
			foreach($server_time AS $k => $v)
			{
				$server_time_data[] = array(
					'server_type' 	=> $v,
					'start_time'  	=> $this->input['start_time_' . $k],
					'end_time'  	=> $this->input['end_time_' . $k],
					'start_date'	=> $this->input['start_date_' . $k]?$this->input['start_date_' . $k]:'',
					'end_date'		=> $this->input['end_date_' . $k]?$this->input['end_date_' . $k]:'',
				);
			}
		}

		//此处处理收费数据
		$fees = $this->input['fees'];
		$collect_fees = array();
		if($fees && !empty($fees))
		{
			foreach($fees AS $k => $v)
			{
				$collect_fees[] = array(
					'fees_type' 	=> $v,
					'start_time' 	=> $this->input['s_time'][$k]?$this->input['s_time'][$k]:'',
					'end_time' 		=> $this->input['e_time'][$k]?$this->input['e_time'][$k]:'',
					'car_type' 		=> $this->input['car_type_' . $k],
					'price' 		=> $this->input['price'][$k]?$this->input['price'][$k]:'',
					'charge_unit' 	=> $this->input['charge_unit_' . $k]?$this->input['charge_unit_' . $k]:'',
					'instructions'	=> $this->input['instruction'][$k]? $this->input['instruction'][$k]:'',
				);
			}
		}
		//实景图片id
		$photo = $this->input['photo'];
		$ret = $this->mode->create($main_data,$server_time_data,$collect_fees,$photo);
		if($ret)
		{
			$data['id'] = $vid;
			$this->addLogs('创建停车场','',$main_data,$main_data['name']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!trim($this->input['name']))
		{
			$this->errorOutput(NO_TITLE);
		}
		if(!intval($this->input['district_id']))
		{
			$this->errorOutput('请选择区域划分！');
		}
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."carpark WHERE id = " .intval($this->input['id']);
		$cp = $this->db->query_first($sql);	
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($cp['district_id'])
			{
				$_node_ids = $cp['district_id'];
			}
			if($this->input['district_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['district_id'] : $this->input['district_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'carpark_district WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= intval($this->input['id']);
		$nodes['user_id'] 	= $cp['user_id'];
		$nodes['org_id'] 	= $cp['org_id'];
		###获取默认数据状态
		if(intval($cp['status']) == 2)
		{
			//停车场的审核状态为 123 默认的审核状态 012，所以需要转换一下
			$_status = $cp['status'] - 1;
			$status = $this->get_status_setting('update_audit', $_status);
			switch ($status)
			{
				case 0:$status=1;break;
				case 1:$status=2;break;
				case 2:$status=3;break;
				default:break;
			}
		}
		######获取默认数据状态
		$this->verify_content_prms($nodes);
		########权限#########
		
		
		$business_time = array();
		if($this->input['date'])
		{
			$dateArr = $this->input['date'];
			$b_stime = $this->input['b_stime'];
			$b_etime = $this->input['b_etime'];
			foreach ($dateArr AS $k => $v)
			{
				$business_time[] = array(
					'date' 	=> $v,
					'stime' => $b_stime[$v],
					'etime' => $b_etime[$v],
				);
			}
		}
		$parking_num = trim($this->input['parking_num']);
		$ret_num = $this->check_num_exist($parking_num,$this->input['id']);
		if($ret_num)
		{
			$this->errorOutput('编号已存在！');
		}
		
		$main_data = array(
			'name' 				=> $this->input['name'],
			'parking_num' 		=> $parking_num,
			'parking_space' 	=> $this->input['parking_space'],
			'empty_space' 		=> $this->input['empty_space'],
			'tel' 				=> $this->input['tel'],
		    'status'            => $status ? $status : $cp['status'],
			'district_id' 		=> $this->input['district_id'],
			'type_id' 			=> $this->input['type_id'],
			'unit_id' 			=> $this->input['unit_id'],
			'icp_number' 		=> $this->input['icp_number'],
			'main_device' 		=> $this->input['main_device'],
			'description' 		=> $this->input['description'],
			'baidu_longitude' 	=> $this->input['baidu_longitude'],
			'baidu_latitude' 	=> $this->input['baidu_latitude'],
			'entrance_num' 		=> $this->input['entrance_num'],
			'exitus_num' 		=> $this->input['exitus_num'],
			'is_inout_same' 	=> $this->input['is_inout_same']?1:0,
			'limited_height' 	=> $this->input['limited_height'],
			'building_storey' 	=> $this->input['building_storey'],
			'struct_type' 		=> ($this->input['struct_type'] && is_array($this->input['struct_type']))?implode(',',$this->input['struct_type']):'',
			'other_struct_type' => $this->input['other_struct_type'],
			'address'			=> $this->input['address'],
			'province_id' 		=> $this->input['province_id'],
			'city_id' 			=> $this->input['city_id'],
			'area_id' 			=> $this->input['area_id'],
			'price_text'		=> $this->input['price_text'],
			'price_brief'		=> $this->input['price_brief'],
			'business_time'		=> $business_time?serialize($business_time):'',
			'update_time' 		=> TIMENOW,
		    'update_user_id'    => $this->user['user_id'],
		    'update_user_name'  => $this->user['user_name'],
			'ip'				=> hg_getip(),
		);
		
		//同步公告type_id
		if($main_data['type_id'] != $cp['type'])
		{
			$sql = "UPDATE " . DB_PREFIX . "announcement SET type_id = " . $main_data['type_id'] . " WHERE carpark_id = " . $this->input['id'];
			$this->db->query($sql);
		}
		
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($main_data['baidu_latitude'] && $main_data['baidu_longitude'])
		{
			$gps = FromBaiduToGpsXY($main_data['baidu_longitude'],$main_data['baidu_latitude']);
			$main_data['GPS_x'] = $gps['GPS_x'];
			$main_data['GPS_y'] = $gps['GPS_y'];
		}
		else
		{
			$main_data['GPS_x'] = 0;
			$main_data['GPS_y'] = 0;
		}

		//此处处理服务时间
		$server_time = $this->input['server_time'];
		$server_time_data = array();
		if($server_time && !empty($server_time))
		{
			foreach($server_time AS $k => $v)
			{
				$server_time_data[] = array(
					'server_type' 	=> $v,
					'start_time'  	=> $this->input['start_time_' . $k],
					'end_time'  	=> $this->input['end_time_' . $k],
					'start_date'	=> $this->input['start_date_' . $k]?$this->input['start_date_' . $k]:'',
					'end_date'		=> $this->input['end_date_' . $k]?$this->input['end_date_' . $k]:'',
				);
			}
		}

		//此处处理收费数据
		$fees = $this->input['fees'];
		$collect_fees = array();
		if($fees && !empty($fees))
		{
			foreach($fees AS $k => $v)
			{
				$collect_fees[] = array(
					'fees_type' 	=> $v,
					'start_time' 	=> $this->input['s_time'][$k]?$this->input['s_time'][$k]:'',
					'end_time' 		=> $this->input['e_time'][$k]?$this->input['e_time'][$k]:'',
					'car_type' 		=> $this->input['car_type_' . $k],
					'price' 		=> $this->input['price'][$k]?$this->input['price'][$k]:'',
					'charge_unit' 	=> $this->input['charge_unit_' . $k]?$this->input['charge_unit_' . $k]:'',
					'instructions'	=> $this->input['instruction'][$k]? $this->input['instruction'][$k]:'',
				);
			}
		}
		//实景图片id
		$photo = $this->input['photo'];
		$ret = $this->mode->update($this->input['id'],$main_data,$server_time_data,$collect_fees,$photo);
		if($ret)
		{
			$this->addLogs('更新停车场',$ret,$main_data,$main_data['name']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		$nodes = $node_id = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'carpark WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($r=$this->db->fetch_array($q))
		{
			$node_id[] = $r['district_id'];
			$nodes[] = array(
				'title' 		=> $r['name'],
				'delete_people' => $this->user['user_name'],
				'cid' 			=> $r['id'],
				'catid' 		=> $r['district_id'],
				'user_id'		=> $r['user_id'],
				'org_id'		=> $r['org_id'],
				'id'			=> $r['id'],
			);
		}
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'carpark_district WHERE id IN('.implode(',',$node_id).')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		if(!empty($nodes))
		{
			foreach ($nodes AS $node)
			{
				if($node['catid'])
				{
					$node['nodes'][$node['catid']] = $node_ids[$node['catid']];
				}
				$this->verify_content_prms($node);
			}
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除停车场',$ret,'','删除停车场' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['district_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'carpark_district WHERE id IN('.$this->input['district_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核停车场','',$ret,'审核停车场' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//execl导入
	public function excel_update()
	{
	 	if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            $this->errorOutput('只有管理员可以操作');
        }
        
		include (CUR_CONF_PATH . 'lib/excel.class.php');
		$excel = new excel();
		
		//获取文件扩展名
		 $extend = pathinfo($_FILES["excel"]["name"]);
		 $extend = strtolower($extend["extension"]);
		 //获取文件扩展名结束
		 
		 
		 $time=date("Y-m-d-H-i-s");//取当前上传的时间
		 $name=$time.'.'.$extend; //重新组装上传后的文件名
		 $uploadfile=CACHE_DIR.$name;//上传后的文件名地址
		if ((($extend == "xls") && ($_FILES["file"]["size"] < 200000)))
		{
			$tmp_name=$_FILES["excel"]["tmp_name"];
			$strtotimes=strtotime(date('Ymd'));
			$key=md5_file($tmp_name);
			$sql=" SELECT filekey FROM " .DB_PREFIX. "con_fileinfo WHERE filekey = '" .$key. "' AND create_time =".$strtotimes;
			$re=$this->db->query_first($sql);
			if ($_FILES["excel"]["error"] > 0)
			{
				$this->errorOutput("Return Code: " . $_FILES["excel"]["error"] . "<br />");
			}
			elseif($re['filekey']==$key)
			{
				$this->errorOutput('已经导入成功,无需重复导入');
			}
			else
			{
				$isupload=$excel->show($uploadfile,$tmp_name,$this->user);
				if($isupload)
				{
					$sql = 'INSERT INTO ' . DB_PREFIX . 'con_fileinfo SET filekey = \''.$key.'\',create_time ='.$strtotimes;
					$this->db->query($sql);
					// 删除除今天以外的文件MD5值.
					$sql = " DELETE FROM " .DB_PREFIX. "con_fileinfo WHERE 1 AND create_time NOT IN (".$strtotimes.")";
					$this->db->query($sql);
					$this->addItem($isupload);
					$this->output();
				}
				else $this->errorOutput('导入失败');

			}
		}
		else
		{
			$this->errorOutput('文件错误,仅支持xls,文件不能大于2M');
		}

	}
	
	//提交图片到图片服务器
	public function upload_real_img()
	{
		if($_FILES['Filedata'])
		{
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			$img_data = array(
				'host' 		=> $img_info['host'],
				'dir' 		=> $img_info['dir'],
				'filepath' 	=> $img_info['filepath'],
				'filename' 	=> $img_info['filename'],
			);
			
			$data = array(
				'img' => serialize($img_data),
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'ip'	=> hg_getip(),
			);
			
			$vid = $this->mode->insert_img($data);
			if($vid)
			{
				$this->addItem(array('id' => $vid,'img' => hg_fetchimgurl($img_data,200)));
				$this->output();
			}
		}
	}
	
	//删除实景图片
	public function delete_real_img()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete_real_img($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
		
	public function sort()
	{
	//	$this->verify_content_prms();
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('carpark', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	private function check_num_exist($parking_num,$id = 0)
	{
		if(!$parking_num)
		{
			return false;
		}
		$sql = 'SELECT id FROM '.DB_PREFIX.'carpark WHERE parking_num = "'.$parking_num.'"';
		if($id) $sql .= ' AND id !='.$id;
		$ret_num = $this->db->query_first($sql);
		if($ret_num['id']) 
		{
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new carpark_update();
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