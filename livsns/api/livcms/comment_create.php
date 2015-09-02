<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: comment_create.php 4287 2011-07-31 05:55:42Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class commentCreateApi extends LivcmsFrm
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
	 * 增加评论
	 * @param contentid 文章对象ID	not null
	 * @param siteid 站点ID 			not null
	 * @param moduleid 模型ID		null
	 * @param author 作者			not null
	 * @param content 内容			not null
	 * @param auditing 	是否审核		null
	 * return $ret 新评论的ID 
	 */
	function create(){
		if (!$this->input['user'])
		{
		//	$this->errorOutput(USENAME_NOLOGIN);
		}
		$info = array(
			'contentid' => $this->input['id'] ? $this->input['id'] : 0,
			'siteid' => $this->site['siteid'],
			'moduleid' => $this->input['moduleid'] ? $this->input['moduleid'] : 0,
			'author' => $this->input['user'] ? urldecode($this->input['user']) : "",
			'content' => $this->input['content'] ? urldecode($this->input['content']) : "",
			'pubdate' => time(),
			'auditing' => $this->input['auditing'] ? $this->input['auditing'] : 0,
			'source' => $this->input['source'],
			'tocolumn' => 0,
			'ip' => hg_getip(),
		);
		if(!$info['contentid'] || !$info['content'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO " . DB_PREFIX . "comment SET ";
		$space = "";
		foreach($info as $key => $value)
		{
			$sql .= $space . $key . "=" . "'" . $value . "'";
			$space = ",";
		}
		$this->db->query($sql);
		$ret = array();
		$ret['id'] = $this->db->insert_id();
		$ret['message'] = '';		
		$this->setXmlNode('channel','info');
		$this->addItem($ret);
		$this->output();
	}
}
$out = new commentCreateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();
?>