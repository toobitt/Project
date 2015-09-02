<?php
define('MOD_UNIQUEID','grade');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/grade_style_mode.php');
class grade_style_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new grade_style_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$names = $this->input['jiancheng']; //简称
		$describes = $this->input['describe'];	//描述
		$data = array(
			'name' => trim($this->input['name']),				//样式名称
			'points_system' => $this->input['points_system'],	//分制
			'is_login' => intval($this->input['is_login']),		//是否需要登录
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'ip' => hg_getip(),
			'update_user_id' => $this->user['user_id'],
			'update_user_name' => $this->user['user_name'],
			'update_ip' => hg_getip(),
			'org_id' => $this->user['org_id'],
			'update_org_id' => $this->user['org_id'],
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
		);
		
		//数据验证
		//$this->data_check($data);
		
		//上传索引图
		$cover['Filedata'] = $_FILES['Filedata'];
		$cover2['Filedata'] = $_FILES['Filedata2'];
		include_once ROOT_PATH  . 'lib/class/material.class.php';
		$material = new material();
		if($cover['Filedata'])
		{
			$re = $material->addMaterial($cover);
			$cover  = array();
			$cover = array(
				'host' => $re['host'],
				'dir' => $re['dir'],
				'filepath' => $re['filepath'],
				'filename' => $re['filename'],
			);
			$data['index_pic'] = addslashes(serialize($cover));
		}
		//上传默认图
		if($cover2['Filedata'])
		{
			$re2 = $material->addMaterial($cover2);
			$cover2  = array();
			$cover2 = array(
				'host' => $re2['host'],
				'dir' => $re2['dir'],
				'filepath' => $re2['filepath'],
				'filename' => $re2['filename'],
			);
			$data['default_pic'] = addslashes(serialize($cover2));
		}
		$vid = $this->mode->create($data);
		
		
		if($vid)
		{
			//添加描述
			//if($this->input['is_describe'])
			//{
				if($names || $describes)
				{
					foreach($names as $k => $v)
					{
						$describe_data = array(
								'style_id'	=> $vid,
								'star'	=> $k+1,
								'name'	=> trim($v),
							    'describes'	=> trim($describes[$k]),
						);
						$this->mode->create_describe($describe_data);
					}
				}
			//}

			$data['id'] = $vid;
			//$this->addLogs('创建',$data,'','创建' . $vid);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			'name' => trim($this->input['name']),				//样式名称
			'points_system' => $this->input['points_system'],	//分制
			'is_login' => intval($this->input['is_login']),		//是否需要登录
		);
		
		//数据验证
		$this->data_check($data,$this->input['old_name']);
		
		//上传索引图
		$cover['Filedata'] = $_FILES['Filedata'];
		$cover2['Filedata'] = $_FILES['Filedata2'];
		include_once ROOT_PATH  . 'lib/class/material.class.php';
		$material = new material();
		if($cover['Filedata'])
		{
			$re = $material->addMaterial($cover);
			$cover  = array();
			$cover = array(
				'host' => $re['host'],
				'dir' => $re['dir'],
				'filepath' => $re['filepath'],
				'filename' => $re['filename'],
			);
			$update_data['index_pic'] = addslashes(serialize($cover));
		}
		//上传默认图
		if($cover2['Filedata'])
		{
			$re2 = $material->addMaterial($cover2);
			$cover2  = array();
			$cover2 = array(
				'host' => $re2['host'],
				'dir' => $re2['dir'],
				'filepath' => $re2['filepath'],
				'filename' => $re2['filename'],
			);
			$update_data['default_pic'] = addslashes(serialize($cover2));
		}
		
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//如果内容有更新才更新以下内容
			$update_data['update_user_name'] = $this->user['user_name'];
			$update_data['update_user_id'] = $this->user['user_id'];
			$update_data['update_org_id'] = $this->user['org_id'];
			$update_data['update_time'] = TIMENOW;
			$update_data['update_ip'] = $this->user['ip'];
			$sql = "UPDATE " . DB_PREFIX . "grade_style SET 
					update_user_name ='" . $update_data['update_user_name'] . "',
					update_user_id = '".$update_data['update_user_id']."',
					update_org_id = '".$update_data['update_org_id']."',
					update_ip = '" . $update_data['update_ip'] . "', 
					update_time = '". TIMENOW . "' WHERE id=" . $this->input['id'];
			$this->db->query($sql);
			
			//更新描述
			if($this->input['is_describe'])
			{
				$names = $this->input['name']; //简述词
				$describes = $this->input['describe'];	//描述
				if($names || $describes)
				{
					foreach($names as $k => $v)
					{
						$describe_data = array(
								'style_id'	=> $vid,
								'star'	=> $k,
								'name'	=> trim($v),
							    'describes'	=> trim($describes[$k]),
						);
						$this->mode->update_describe($this->input['id'],$describe_data);
					}
				}
			}
		}
		//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
		$this->addItem('success');
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
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
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 数据检测
	 */
	private function data_check($data = array(),$old_name = "")
	{
		if(!$data['name'])
		{
			$this->errorOutput("请填写样式名称");
		}
		if(!$data['index_pic'])
		{
			$this->errorOutput("请上传索引图");
		}
		if(!$data['default_pic'])
		{
			$this->errorOutput("请上传默认图");
		}
		
		//检查名字是否重复
		$sql = "SELECT id FROM " . DB_PREFIX . "grade_style WHERE name='" . $data['name'] . "'";
		$arr = $this->db->query_first($sql);
		$c_id = $arr['id'];
		if(!$old_name)//创建
		{
			if($c_id)
			{
				$this->errorOutput("该名称已存在");
			}
		}
		else //更新
		{
			if($c_id && $data['name'] != $old_name)
			{
				$this->errorOutput("该名称已存在");
			}
		}
		
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new grade_style_update();
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