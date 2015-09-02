<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: stream_server_create.php 4215 2011-07-27 09:38:36Z repheal $
***************************************************************************/
require('global.php');
class streamServerUpdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 更新服务器信息
	 * @param $id 服务器Id						not null
	 * @param $name 服务器名称						not null
	 * @param $brief 服务器简介						null 
	 * @param $server_name 服务器名称（域名或者IP）	not null
	 * @param $server_path 服务器地址				not null
	 * @param $server_ip 服务器IP					not null
	 * return $ret 新服务器信息
	 */
	function update(){
		$id = $this->input['id'] ? $this->input['id'] : 0;
		$info = array(
			'name' => $this->input['name'] ? urldecode($this->input['name']) : "",
			'brief' => $this->input['brief'] ? urldecode($this->input['brief']) : "",
			'server_name' => $this->input['server_name'] ? urldecode($this->input['server_name']) : "",
			'server_path' => $this->input['server_path'] ? urldecode($this->input['server_path']) : "",
			'server_ip' => $this->input['server_ip'] ? urldecode($this->input['server_ip']) : "",
			'update_time' => time()
		);
		
		$sql = "UPDATE " . DB_PREFIX . "stream_server SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$sql .= " where id=" . $id; 
		$this->db->query($sql);
		
		$this->setXmlNode('stream_server','info');
		$this->addItem($info);
		$this->output();
	}
	

	/**
	 * 批量删除服务器数据
	 */
	public function delete()
	{
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		
		$delete_id = implode(',' , $id_array);
		$sql = "DELETE FROM " . DB_PREFIX . "stream_server WHERE id IN (" . $delete_id . ")";

		$r = $this->db->query($sql);
		
		$this->setXmlNode('stream_server' , ' server');
		if($r)
		{
			$this->addItem('批量删除成功');
		}
		else
		{
			$this->addItem('批量删除失败');	
		}
		$this->output();
	}
	
	/**
	 * 批量审核视频数据
	 */
	public function audit()
	{
		
	}
}
$out = new streamServerUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>