<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_item_create.php 4380 2011-08-10 09:53:46Z lijiaying $
***************************************************************************/
require('global.php');
class programitemCreateApi extends BaseFrm
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
	 * 创建节目
	 * @param $name 节目名称  			not null
	 * @param $start_time 开始时间  		not null
	 * @param $toff 时长					not null
	 * @param $is_taped 是否录播 0录播  1不录播			 null 
	 * return $ret 新节目的信息 
	 */
	function create(){
		$info = array(
			'name' => $this->input['name'] ? urldecode($this->input['name']) : "",
			'start_time' => $this->input['start_time'],
			'toff' => $this->input['toff'],
			'is_taped' => 0,
			'create_time' => time(),
			'update_time' => time(),
			'ip' => hg_getip()
		);
		if(!$info['name'] || !$info['start_time'] || !$info['toff'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO " . DB_PREFIX . "programitem SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}

		$this->db->query($sql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();
		
		$this->setXmlNode('program','info');
		$this->addItem($ret);
		$this->output();
	}
}
$out = new programitemCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>