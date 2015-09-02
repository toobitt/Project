<?php
define('MOD_UNIQUEID','member_qdxq');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_qdxq.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
class member_qdxqUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->qdxq = new qdxq();
		$this->Members=new members();
		$this->membersql = new membersql();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据

		//验证名称是否重复
		$checkResult = $this->membersql->verify('sign_emot',array('name' => $data['name']));
		if ($checkResult)
		{
			$this->errorOutput('心情名称重复');
		}
		//验证标识是否重复
		$checkResult = $this->membersql->verify('sign_emot',array('qdxq' => $data['qdxq']));
		if ($checkResult)
		{
			$this->errorOutput('心情标识重复');
		}
		if(empty($data['img']))
		{
			$this->errorOutput('您未选择心情图标,请上传心情图标');
		}
		if($data)
		{
			$result = $this->membersql->create('sign_emot',$data,true);
		}

		$this->addItem($result);
		$this->output();
	}

	/**
	 *
	 * 更新
	 */
	public function update()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);

		$info=$this->qdxq->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['name']!=$info['name']&&empty($info['is_sys']))
		{
			//验证名称是否重复
			$checkResult = $this->membersql->verify('sign_emot',array('name' => $data['name']));
			if ($checkResult) $this->errorOutput('心情名称重复');
		}
		elseif($data['name']!=$info['name']) {
			$this->errorOutput('系统心情禁止修改名称');
		}
		if($data['qdxq']!=$info['qdxq'])
		{
			$this->errorOutput('禁止修改标识');
		}
		if(empty($info['img'])&&empty($data['img']))
		{
			$this->errorOutput('您未选择心情图标,请上传心情图标');
		}
		if ($data)
		{
			$result = $this->membersql->update('sign_emot', $data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除
	 */
	public function delete()
	{
		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		if(empty($id_array)) return false;
		//验证 是系统禁止删除。
		$condition = " AND id in (".implode(',' , $id_array).")";
		$sql = "SELECT id,name,is_sys FROM " . DB_PREFIX . "sign_emot ";
		$sql.= " WHERE 1 " . $condition;
		$qdxq  = $this->db->fetch_all($sql);
		if (empty($qdxq))
		{
			$this->errorOutput('签到心情不存在');
		}
		else {
			$delete_id='';
			$qdxqname=array();
			foreach ($qdxq as $val)
			{
				if($val['is_sys'])
				{
					$qdxqname[]=$val['name'];
				}
				else {
					$delete_id[]=$val['id'];
				}
			}
			if($qdxqname)
			{
				$this->errorOutput(implode(',', $qdxqname).'为系统表情禁止删除');
			}
			if($delete_id)
			{
				$ret = $this->membersql->delete('sign_emot',array('id'=>$delete_id));
			}
		}
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{
		$this->addLogs('更改心情排序', '', '', '更改心情排序');
		$ret = $this->drag_order('sign_emot', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish()
	{
		//
	}

	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['name']) ? trim(urldecode($this->input['name'])) : '';
		$description = isset($this->input['description']) ? trim(urldecode($this->input['description'])) : '';
		$qdxq = isset($this->input['qdxq']) ? trim(urldecode($this->input['qdxq'])) : '';
		if (empty($name)) $this->errorOutput('心情名不能为空');
		if (empty($qdxq)) $this->errorOutput('心情标识不能为空');
		$this->checkFieldFormat($qdxq);
		$data = array(
			'name'    => $name,
			'qdxq'    => $qdxq,
			'description' => $description,
		);
		if($_FILES['img'])//如果有图片,则添加图片数据
		{
			$img['Filedata']=$_FILES['img'];
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($img);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			$data['img']=maybe_serialize($img_data);
		}
		return $data;
	}
	
	private function checkFieldFormat($field)
	{
		if(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$field))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		elseif(is_numeric($field))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $field))
		{
			$this->errorOutput('标识禁止使用或者含有汉字');
		}
		return $field;
	}
	


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new member_qdxqUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>