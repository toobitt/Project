<?php
define('MOD_UNIQUEID','department');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/department_mode.php');
class department_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new department_mode();
		include_once(ROOT_PATH . 'lib/class/material.class.php');
		$this->mater = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name = trim($this->input['name']);
		
		if(!$name)
		{
			$this->errorOutput('请填写科室名称');
		}
		
		if(!$this->input['hospital_id'])
		{
			$this->errorOutput('医院id不存在');
		}
		
		if(!$this->input['department_id'])
		{
			$this->errorOutput('请填写科室id');
		}
		
		
		$data = array(
			'fid'			=> intval($this->input['fid']),
			'name'			=> $name,
			'position'		=> $this->input['position'],
			'introduction'	=> $this->input['introduction'],
			'hospital_id'	=> intval($this->input['hospital_id']),
			'department_id'	=> intval($this->input['department_id']),
		);
		
		$res = $this->sole_depart_id($data['hospital_id'], $data['department_id']);
		
		if($res)
		{
			$this->errorOutput('科室id已存在');
		}
		$vid = $this->mode->create($data);
		if($vid)
		{
			//处理图片
			$this->material_manage($vid);
			
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
			$this->errorOutput('请填写科室名称');
		}
		
		if(!$this->input['hospital_id'])
		{
			$this->errorOutput('医院id不存在');
		}
		
		if(!$this->input['department_id'])
		{
			$this->errorOutput('请填写科室id');
		}
		
		
		$data = array(
			'fid'			=> intval($this->input['fid']),
			'name'			=> $name,
			'position'		=> $this->input['position'],
			'introduction'	=> $this->input['introduction'],
			'hospital_id'	=> intval($this->input['hospital_id']),
			'department_id'	=> intval($this->input['department_id']),
		);
		$res = $this->sole_depart_id($data['hospital_id'], $data['department_id'],$id);
		
		if($res)
		{
			$this->errorOutput('科室id已存在');
		}
		
		$ret = $this->mode->update($id,$data);
		if($ret)
		{
			/*$this->material_manage($id);
			
			if($this->input['material_id'])
			{
				foreach ($this->input['material_id'] as $k => $v)
				{
					$mater_arr = array(
						'brief' => $this->input['des'][$k] ? $this->input['des'][$k] : '',
					);
					
					$this->mode->update_material($v, $mater_arr);
				}
			}*/
		
				
			$img_id = $this->input['material_id'];
			//更新素材表中素材内容id
			if(is_array($img_id) && count($img_id))
			{
				$img_id = implode(',', $img_id);
				$img_id = trim($img_id,',');
				
				$sql = "UPDATE ".DB_PREFIX."materials SET cid = {$id} WHERE id IN ({$img_id})";
				$this->db->query($sql);
				if ($this->db->affected_rows($query))
				{
					$update_tag = true;
				}
			}
			
			$del_img = $this->input['del_img'];
			if($del_img)
			{
				$sql = "DELETE FROM " . DB_PREFIX . "materials WHERE id IN ({$del_img})";
				$this->db->query($sql);
			}
			
			$this->addLogs('更新',$ret,'','更新' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//查询出原来
		$sql = " SELECT * FROM " .DB_PREFIX. "departments WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$depart_id[] = $r['department_id'];
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			$arr['error_msg'] = '数据已经不存在';
			echo json_encode($arr);exit();
			//$this->errorOutput('数据已经不存在');
		}
		
		if(!empty($depart_id))
		{
			$depart_ids = implode(',', $depart_id);
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "doctor WHERE department_id IN ({$depart_ids})";
			$res = $this->db->query_first($sql);
			
			if($res['total'])
			{
				$arr['error_msg'] = '请先删除科室下医生';
				echo json_encode($arr);exit();
				//$this->errorOutput('请先删除科室下医生');
			}
			
			$res = '';
			$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "departments WHERE fid IN ({$depart_ids})";
			$res = $this->db->query_first($sql);
			
			if($res['total'])
			{
				$arr['error_msg'] = '请先删除科室下子科室';
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
	
	//附件处理
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
				'ctype'			=> 2,//科室
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
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sole_depart_id($hospital_id,$depart_id,$id='')
	{
		if(!$hospital_id || !$depart_id)
		{
			return false;
		}
		$sql = "SELECT id FROM ".DB_PREFIX."departments WHERE hospital_id = {$hospital_id} AND department_id = {$depart_id} ";
		
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
	
	//ajax上传图片
	public function upload_pic()
	{
		if($_FILES['Filedata'])
		{
			//$cid = intval($this->input['id']);
			
			include_once(ROOT_PATH . 'lib/class/material.class.php');
			$this->material = new material();
			
			$PhotoInfor = $this->material->addMaterial($_FILES); //插入图片服务器
			if (empty($PhotoInfor))
			{
				return false;
			}
			$temp = array(
				//'cid'			=> $cid,
				'type'			=> $PhotoInfor['type'],
				'ctype'			=> 2,						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'imgwidth'		=> $PhotoInfor['imgwidth'],
				'imgheight'		=> $PhotoInfor['imgheight'],
			);
			
			$material_id = intval($this->input['material_id']);
			
			if($material_id)
			{
				$PhotoId = $material_id;
				$this->mode->update_material($material_id, $temp);
			}
			else 
			{
				//插入素材表
				$PhotoId = $this->mode->insert_material($temp);
			}
			
			
			$pic_info = array(
				'host'		=> $PhotoInfor['host'],
				'dir'		=> $PhotoInfor['dir'],
				'filepath' 	=> $PhotoInfor['filepath'],
				'filename'	=> $PhotoInfor['filename'],
				'id'		=> $PhotoId,		
			);
			$this->addItem($pic_info);
			$this->output();
		}
	}	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new department_update();
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