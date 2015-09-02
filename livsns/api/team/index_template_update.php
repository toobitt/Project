<?php
require_once './global.php';
class indexTemplateUpdateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建话题
	 */
	public function create()
	{
		$info = array(
			'title' => urldecode($this->input['title']),
			'name' => urldecode($this->input['name']),
			'host' => urldecode($this->input['host']),
			'dir' => urldecode($this->input['dir']),
			'filepath' => urldecode($this->input['filepath']),
			'filename' => urldecode($this->input['filename']),
			'is_update' => 1,
			'update_time' => TIMENOW,
		);
		$sql = "INSERT INTO " . DB_PREFIX . "index SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput('请传入ID');
		}
		$info = array(
			'title' => urldecode($this->input['title']),
			'name' => urldecode($this->input['name']),
			'host' => urldecode($this->input['host']),
			'dir' => urldecode($this->input['dir']),
			'filepath' => urldecode($this->input['filepath']),
			'filename' => urldecode($this->input['filename']),
			'is_update' => 1,
			'update_time' => TIMENOW,
		);
		$sql = "UPDATE " . DB_PREFIX . "index SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$sql .= " WHERE id=" . $id;
		$this->db->query($sql);
		$info['id'] = $id;
		$this->addItem($info);
		$this->output();
	}
	
	public function upload()
	{
		if($_FILES['Filedata'])
		{
			if($_FILES['Filedata']['error'])
			{
				$this->addItem(array('error' => '图片有误'));
				$this->output();
			}
			else
			{
				/*
$tmp = getimagesize($_FILES['Filedata']['tmp_name']);
				if($tmp[0]<2100)
				{
					$this->addItem(array('error' => '图片宽度必须大于2100'));
					$this->output();
				}
				if($tmp[1]<1400)
				{
					$this->addItem(array('error' => '图片高度必须大于1400'));
					$this->output();
				}
*/
				include_once ROOT_PATH . '/lib/class/material.class.php';
				$obj_material = new material();
				$ret = $obj_material->addMaterial($_FILES);
				if(!empty($ret))
				{
					$this->addItem($ret);
					$this->output();
				}
			}
		}
	}
	
	public function delete()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput('请传入ID');
		}
		$sql = "DELETE FROM " . DB_PREFIX . "index WHERE id=" . $id;
		$this->db->query($sql);
		$this->addItem($id);
		$this->output();
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
}

$out = new indexTemplateUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();