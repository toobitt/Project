<?php
define('MOD_UNIQUEID','hospital');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/hospital_mode.php');
class hospital_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new hospital_mode();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		//权限判断
		$this->verify_content_prms(array('_action'=>'manage_hospital'));
		
		$name = trim($this->input['name']);
		if(!$name)
		{
			/*$error_msg = array(
				'error' => 1,
				'msg'	=> '请填写医院名称',
			);
			
			echo json_encode($error_msg);exit();*/
			$this->errorOutput('请填写医院名称');
		}
		
	
		//$sql = "SELECT id FROM " . DB_PREFIX . "hospital WHERE hospital_id = {$hospital_id} AND patient_id = {$patient_id} AND id != {$id}";
		//$res = $this->db->query_first($sql);
		//if($res['id'])
		{
			//$this->errorOutput('就诊人已经添加医院就诊卡');
		}
		
		$province_id = intval($this->input['province_id']);
		$city_id 	 = intval($this->input['city_id']);
		$area_id 	 = intval($this->input['area_id']);
		if(empty($province_id))
		{
			$city_id=0;
			$area_id=0;
		}
		elseif(empty($city_id))
		{
			$area_id=0;
		}
		
		$data = array(
			'name'				=> $name,
			'level'				=> $this->input['level'],
			'yibao_point'		=> intval($this->input['yibao_point']),
			'website'			=> trim($this->input['website']),
			'traffic'			=> $this->input['traffic'],
			'important_depart'	=> trim($this->input['important_depart']),
			'special_depart'	=> trim($this->input['special_depart']),
			'address'			=> trim($this->input['address']),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'province_id'		=> $province_id,
			'city_id' 			=> $city_id,
			'area_id'			=> $area_id,
			'status'			=> intval($status),
			'hospital_id'		=> intval($this->input['hospital_id']),
			'yuyue_rule'		=> trim($this->input['yuyue_rule']),
			'update_time'		=> TIMENOW,
			'create_time'		=> TIMENOW,
		);
		
		$res = $this->sole_hospital_id($data['hospital_id']);
		if($res)
		{
			/*$error_msg = array(
				'error' => 1,
				'msg'	=> '医院id已存在',
			);
			
			echo json_encode($error_msg);exit();*/
			$this->errorOutput('医院id已存在');
		}
		//电话处理
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$data['telephone']=serialize($tel_arr);
		}
		
		//索引图
		if($_FILES['logo'])
		{
			$file['Filedata'] = $_FILES['logo'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$indexpic = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$data['logo'] = serialize($indexpic);
			}
		}
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			
			$content = trim($this->input['content']);
			if($content)
			{
				$this->content_manage($vid, $content);
			}
			
			$this->material_manage($vid);
			
			if($_FILES['indexpic'])
			{
				$file['Filedata'] = $_FILES['indexpic'];
				$res = $this->mater->addMaterial($file);
				if($res)
				{
					$indexpic = array(
						'cid'			=> $vid,
						'ctype'			=> 1,
						'host'			=> $res['host'],
						'dir'			=> $res['dir'],
						'filepath'		=> $res['filepath'],
						'filename'		=> $res['filename'],
						'material_id'	=> $res['id'],
						'imgwidth'		=> $res['imgwidth'],
						'imgheight'		=> $res['imgheight'],
					);
					
					$indexpic_id = $this->mode->insert_material($indexpic);
					
					$indexpic_data['indexpic_id'] = $indexpic_id ? $indexpic_id : 0;
					
					if($indexpic_id)
					{
						$this->mode->update($vid,$indexpic_data);
					}
				}
			}
		
			$data['id'] = $vid;
			$this->addLogs('创建',$data,'','创建' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$name = trim($this->input['name']);
		if(!$name)
		{
			/*$error_msg = array(
				'error' => 1,
				'msg'	=> '请填写医院名称',
			);
			
			echo json_encode($error_msg);exit();*/
			$this->errorOutput('请填写医院名称');
		}
		
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
				
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node)
		{
			if(!in_array($id, $node))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		
		$sql = "select * from " . DB_PREFIX ."hospital where id = " . $id;
		$q = $this->db->query_first($sql);
		
		/**************修改他人数据权限判断***************/
		$info['id'] = $id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_hospital';
		
		//$this->verify_content_prms($info);
		/**************结束***************/
		
		//只有管理员可以修改杂志名称
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['title'] != $q['name'])
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		
		$province_id = intval($this->input['province_id']);
		$city_id 	 = intval($this->input['city_id']);
		$area_id 	 = intval($this->input['area_id']);
		if(empty($province_id))
		{
			$city_id=0;
			$area_id=0;
		}
		elseif(empty($city_id))
		{
			$area_id=0;
		}
		
		$update_data = array(
			'name'				=> $name,
			'level'				=> $this->input['level'],
			'yibao_point'		=> intval($this->input['yibao_point']),
			'website'			=> trim($this->input['website']),
			'traffic'			=> $this->input['traffic'],
			'important_depart'	=> trim($this->input['important_depart']),
			'special_depart'	=> trim($this->input['special_depart']),
			'address'			=> trim($this->input['address']),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'province_id'		=> $province_id,
			'city_id' 			=> $city_id,
			'area_id'			=> $area_id,
			'hospital_id'		=> intval($this->input['hospital_id']),
			'yuyue_rule'		=> trim($this->input['yuyue_rule']), 
			'update_time'		=> TIMENOW,
		);
		
		$res = $this->sole_hospital_id($update_data['hospital_id'],$id);
		if($res)
		{
			/*$error_msg = array(
				'error' => 1,
				'msg'	=> '医院id已存在',
			);
			
			echo json_encode($error_msg);exit();*/
			$this->errorOutput('医院id已存在');
		}
		
		//索引图
		if($_FILES['logo'])
		{
			$file['Filedata'] = $_FILES['logo'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$logo = array(
					'host'			=>$res['host'],
					'dir'			=>$res['dir'],
					'filepath'		=>$res['filepath'],
					'filename'		=>$res['filename'],
				);
				
				$update_data['logo'] = serialize($logo);
			}
		}
		
		if($_FILES['indexpic'])
		{
			$file['Filedata'] = $_FILES['indexpic'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$indexpic = array(
					'cid'			=> $id,
					'ctype'			=> 1,
					'host'			=> $res['host'],
					'dir'			=> $res['dir'],
					'filepath'		=> $res['filepath'],
					'filename'		=> $res['filename'],
					'material_id'	=> $res['id'],
					'imgwidth'		=> $res['imgwidth'],
					'imgheight'		=> $res['imgheight'],
				);
				
				$indexpic_id = $this->input['indexpic_id'];
				if($indexpic_id)
				{
					$this->mode->update_material($indexpic_id, $indexpic);
				}
				else 
				{
					$indexpic_id = $this->mode->insert_material($indexpic);
				}
				
				$update_data['indexpic_id'] = $indexpic_id ? $indexpic_id : 0;
			}
		}
		//电话处理
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$update_data['telephone']=serialize($tel_arr);
		}
		$ret = $this->mode->update($id,$update_data);
		
		if($ret)
		{
			$this->material_manage($id);
			
			if($this->input['material_id'])
			{
				foreach ($this->input['material_id'] as $k => $v)
				{
					$mater_arr = array(
						'brief' => $this->input['des'][$k] ? $this->input['des'][$k] : '',
					);
					
					$this->mode->update_material($v, $mater_arr);
				}
			}
			
			$del_img = $this->input['del_img'];
			if($del_img)
			{
				$sql = "DELETE FROM " . DB_PREFIX . "materials WHERE id IN ({$del_img})";
				$this->db->query($sql);
			}
		
			$content = trim($this->input['content']);
			$this->content_manage($id, $content);
			$this->addLogs('更新',$ret,'','更新' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function content_manage($id,$content='')
	{
		if(!$id)
		{
			return false;
		}
		
		$data = array(
			'cid'		=> $id,
			'content'	=> $content,
		);
		$sql  = 'REPLACE INTO '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		
		$this->db->query($sql);
		$id = $this->db->insert_id();
		return $id;
	}
	
	public function material_manage($id)
	{
		//图片上传
		if ($_FILES['photos'])
		{
			$photos = array();
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput(NO_MATERIAL_APP);
			}
			
			foreach ($_FILES['photos']['name'] as $i => $file_val)
			{
				if ($_FILES['photos']['name'][$i])
				{
					if ($_FILES['photos']['error'][$i]>0)
					{
						continue;
					}
						
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$photo['Filedata'][$k] = $_FILES['photos'][$k][$i];
					}
					$photos[$i]['img'] = $photo;
					$photos[$i]['des'] = $this->input['des'][$i];
					$photos[$i]['cid'] = $this->input['material_id'][$i] ? $this->input['material_id'][$i] : 0;
				}
			}
		}
		
		if (empty($photos))
		{
			return false;
		}
			
		foreach ($photos as $val)
		{
			$PhotoInfor = $this->mater->addMaterial($val['img']);
			if (empty($PhotoInfor))
			{
				continue;
			}
			$temp = array(
				'cid'			=> $id,
				'ctype'			=> 1,
				'brief'			=> $val['des'],
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'imgwidth'		=> $PhotoInfor['imgwidth'],
				'imgheight'		=> $PhotoInfor['imgheight'],
			);
			
			if(!$val['cid'])
			{
				//插入数据库
				$PhotoId = $this->mode->insert_material($temp);
			}
			else if($val['cid'])
			{
				$this->mode->update_material($val['cid'], $temp);
			}
			//默认第一张图片为索引图
			if (!$indexpic)
			{
				//$indexpic = $this->contribute->update_indexpic($PhotoId, $contributeId);
			}
		}
	}
	public function delete()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		$node = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
				
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $node)
		{
			//被删除杂志ids
			$arr_ids = explode(',', $id);
			
			foreach ($arr_ids as $k => $v)
			{
				if(!in_array($v, $node))
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "hospital WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		$hospital_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			if($r['hospital_id'])
			{
				$hospital_id[] = $r['hospital_id'];
			}
			$pre_data[] 	= $r;
		}
		//print_r($pre_data);
		if(!$pre_data)
		{
			$arr['error_msg'] = '数据已经不存在';
			echo json_encode($arr);exit();
		}
		
		if(!empty($hospital_id))
		{
			$hospital_ids = implode(',', $hospital_id);
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "departments WHERE hospital_id IN ({$hospital_ids})";
			$res = $this->db->query_first($sql);
			
			if($res['total'])
			{
				$arr['error_msg'] = '请先删除医院下科室';
				echo json_encode($arr);exit();
			}
		}
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
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
		
		$id = $this->input['id'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'audit')); //权限判断
			/**************节点权限*************/
			$prms_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			$hospital_id = explode(',',$id);
			foreach ($hospital_id as $key => $val)
			{
				if($prms_ids && !in_array($val,$prms_ids))
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
			/*********************************/
			
			/**************审核他人数据权限判断***************/
			$sql = 'SELECT * FROM '.DB_PREFIX.'hospital WHERE id IN ('. $id .')';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$conInfor[] = $row;
			}
			if (!empty($conInfor))
			{
				foreach ($conInfor as $val)
				{
					$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				}
			}
			/*********************************************/
		}
		
		$audit = intval($this->input['audit']);
		
		$ret = $this->mode->audit($this->input['id'],$audit);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sole_hospital_id($hospital_id,$id='')
	{
		if(!$hospital_id)
		{
			return false;
		}
		$sql = "SELECT id FROM ".DB_PREFIX."hospital WHERE hospital_id = {$hospital_id} ";
		
		if($id)
		{
			$sql .= " AND id != {$id}";
		}
		$res = $this->db->query_first($sql);
		if($res['id'])
		{
			return true;
		}
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new hospital_update();
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