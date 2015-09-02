<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: stream_server_create.php 4311 2011-08-01 05:31:32Z repheal $
***************************************************************************/
require('global.php');
class streamServerCreateApi extends BaseFrm
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
	 * 创建服务器信息
	 * @param $name 服务器名称						not null
	 * @param $brief 服务器简介						null 
	 * @param $server_name 服务器名称（域名或者IP）	not null
	 * @param $server_path 服务器地址				not null
	 * @param $server_ip 服务器IP					not null
	 * return $ret 新服务器信息
	 */
	function create(){
		$info = array(
			'name' => $this->input['name'] ? urldecode($this->input['name']) : "",
			'brief' => $this->input['brief'] ? urldecode($this->input['brief']) : "",
			'server_name' => $this->input['server_name'] ? urldecode($this->input['server_name']) : "",
			'server_path' => $this->input['server_path'] ? urldecode($this->input['server_path']) : "",
			'server_ip' => $this->input['server_ip'] ? urldecode($this->input['server_ip']) : "",
			'create_time' => time(),
			'update_time' => time(),
			'ip' => hg_getip(),
		);

		if(!$info['name'] || !$info['server_name'] || !$info['server_path'] || !$info['server_ip'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO " . DB_PREFIX . "stream_server SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();
		
		$this->setXmlNode('stream','info');
		$this->addItem($ret);
		$this->output();
	}
}
$out = new streamServerCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>