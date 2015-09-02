<?php
require('./global.php');
define('MOD_UNIQUEID','member_staricon');//模块标识
require_once CUR_CONF_PATH . 'lib/member_staricon.class.php';
class memberstariconUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->staricon = new staricon();

	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = $this->filter_data();      //获取提交的数据

		//验证名称是否重复
		$checkResult = $this->staricon->verify(array('starname' => $data['starname']));
		if ($checkResult) $this->errorOutput('星级样式名称重复');
		if (empty($data['star'])) $this->errorOutput('请传星星图标');
		if (empty($data['moon'])) $this->errorOutput('请传月亮图标');
		if (empty($data['sun'])) $this->errorOutput('请传太阳图标');
		if($data)
		{
			$result = $this->staricon->create('staricon', $data);
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

		$info=$this->staricon->detail($id);
		if (!$info) $this->errorOutput(PARAM_WRONG);  //数据库中没有该条数据
		$data = $this->filter_data(); //获取提交的数据
		if($data['starname']!=$info['starname'])
		{
			//验证名称是否重复
			$checkResult = $this->staricon->verify(array('starname' => $data['starname']));
			if ($checkResult) $this->errorOutput('星级样式名称重复');
		}
		if ($data)
		{
			$result = $this->staricon->update('staricon', $data, array('id' => intval($id)));
		}
		$this->addItem($result);
		$this->output();

	}


	/**
	 * 删除样式
	 */
	public function delete()
	{

		$id = trim($this->input['id']);
		$ids = str_replace('，' , ',' , $id);
		$id_array = explode(',' , $ids);
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		if(empty($id_array)) return false;
		$delete_id = implode(',' , $id_array);
		$condition = " AND id in (".$delete_id.")";
		$sql = "SELECT id,starname,issystem,opened FROM " . DB_PREFIX . "staricon ";
		$sql.= " WHERE 1 " . $condition;
		$staricon  = $this->db->fetch_all($sql);
		if (empty($staricon))
		{
			$this->errorOutput('待删除星级样式不存在');
		}
		else {
			$delete_id='';
			$starname=array();
			$opened=false;
			foreach ($staricon as $val)
			{
				if($val['issystem'])
				{
					$starname[]=$val['starname'];
				}
				else {
					$delete_id[]=$val['id'];
				}
				if($val['opened'])
				{
					$opened=true;
				}
			}
			if($starname)
			{
				$this->errorOutput(implode(',', $starname).'为系统星级样式禁止删除');
			}
			if($delete_id)
			{
				$ret = $this->staricon->delete('staricon',array('id'=>implode(',',$delete_id)));
			}
		}
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		else {
			if($opened)//已删除记录含有已启用图标,则重新启用系统默认图标
			{
			
			$sql='SELECT id  FROM '.DB_PREFIX.'staricon WHERE issystem = 1';
			$staricon=$this->db->query_first($sql);
			
			if(empty($staricon)) {
				$this->errorOutput('您真强大,竟然有办法把系统默认星级样式也删除,联系软件提供商吧!');
			}
		$data = array(
			'opened'    => 1,
			);
			$this->staricon->update('staricon', $data, array('id' => intval($staricon['id'])));
		}
		}
		$this->addItem($id);
		$this->output();
	}
	
	//等级图标开关
	public function display()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->staricon->display($ids);
		$this->addItem($data);
		$this->output();
	}

	public function audit()
	{
		//
	}
	public function sort()
	{ 
		
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
		$starname = isset($this->input['starname']) ? trim(urldecode($this->input['starname'])) : '';
		if (empty($starname)) $this->errorOutput('星级样式名称不能为空');
		$data = array(
			'starname'    => $starname,
		);
		if($_FILES['star'])//如果有图片,则添加图片数据
		{
			$data['star']=$this->img_upload($_FILES['star']);
		}
			if($_FILES['moon'])//如果有图片,则添加图片数据
		{
			$data['moon']=$this->img_upload($_FILES['moon']);
		}
			if($_FILES['sun'])//如果有图片,则添加图片数据
		{
			$data['sun']=$this->img_upload($_FILES['sun']);
		}
		return $data;
	}
	
	public function img_upload($img_file)
	{
			require_once(ROOT_PATH.'lib/class/material.class.php');
			$img['Filedata']=$img_file;
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
			return maybe_serialize($img_data);
	}


	public function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}

}

$out = new memberstariconUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>