<?php
define('MOD_UNIQUEID','doctor');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/doctor_mode.php');
class doctor_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new doctor_mode();
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
			$this->errorOutput('请填写医生名称');
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
			'name'			=> $name,
			'title'			=> $this->input['title'],
			'level'			=> $this->input['level'],
			'brief'			=> $this->input['brief'],
			'expert'		=> $this->input['expert'],
			'speciality'	=> $this->input['speciality'],
			'introduction'	=> $this->input['introduction'],
			'short_pinyin'	=> $this->input['short_pinyin'],
			'doctor_id'		=> intval($this->input['doctor_id']),
			'hospital_id'	=> intval($this->input['hospital_id']),
			'department_id'	=> intval($this->input['department_id']),
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			
			if($_FILES['indexpic'])
			{
				$file['Filedata'] = $_FILES['indexpic'];
				$res = $this->mater->addMaterial($file);
				if($res)
				{
					$indexpic = array(
						'cid'			=> $vid,
						'ctype'			=> 3,
						'host'			=> $res['host'],
						'dir'			=> $res['dir'],
						'filepath'		=> $res['filepath'],
						'filename'		=> $res['filename'],
						'material_id'	=> $res['id'],
						'imgwidth'		=> $res['imgwidth'],
						'imgheight'		=> $res['imgheight'],
					);
					
					$indexpic_id = $this->mode->insert_material($indexpic);
					
					$data['indexpic_id'] = $indexpic_id ? $indexpic_id : 0;
					$update_data['indexpic_id'] = $data['indexpic_id'];
					
					$ret = $this->mode->update($vid,$update_data);
				}
			}
			
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
			$this->errorOutput('请填写医生名称');
		}
		
		if(!$this->input['hospital_id'])
		{
			$this->errorOutput('医院id不存在');
		}
		
		if(!$this->input['department_id'])
		{
			$this->errorOutput('请填写科室id');
		}
		
		$update_data = array(
			'name'			=> $name,
			'title'			=> $this->input['title'],
			'level'			=> $this->input['level'],
			'brief'			=> $this->input['brief'],
			'expert'		=> $this->input['expert'],
			'speciality'	=> $this->input['speciality'],
			'introduction'	=> $this->input['introduction'],
			'short_pinyin'	=> $this->input['short_pinyin'],
			'doctor_id'		=> intval($this->input['doctor_id']),
			'hospital_id'	=> intval($this->input['hospital_id']),
			'department_id'	=> intval($this->input['department_id']),
		);
		
		if($_FILES['indexpic'])
		{
			$file['Filedata'] = $_FILES['indexpic'];
			$res = $this->mater->addMaterial($file);
			if($res)
			{
				$indexpic = array(
					'cid'			=> $id,
					'ctype'			=> 3,
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
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
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
		$sql = " SELECT * FROM " .DB_PREFIX. "doctor WHERE id IN (" . $id . ")";
		$q = $this->db->query($sql);
		$pre_data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$pre_data[] 	= $r;
		}
		if(!$pre_data)
		{
			$this->errorOutput('数据不存在');
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
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
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

$out = new doctor_update();
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