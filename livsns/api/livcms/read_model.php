<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class model extends LivcmsFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function getSiteinfo()
	{
		
	}
	public function create()
	{
		
	}
	public function show()
	{
		$sql = 'SELECT applyid, applyname FROM '.DB_PREFIX.'mode_apply';
		$q = $this->db->query($sql);
		$model = array('0'=>'请选择模型');
		while($row = $this->db->fetch_array($q))
		{
			$model[$row['applyid']] = $row['applyname'];
		}
		$this->addItem($model);
		$this->output();
	}
	public function getModelField()
	{
		$field = array('未选择'=>'请选择');
		if(!$this->input['applyid'])
		{
			return;
		}
		$sql = 'SELECT fieldtitle,fieldname FROM '.DB_PREFIX.'apply_relate WHERE applyid = '.intval($this->input['applyid']);
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$field[$row['fieldname']] = $row['fieldtitle'];
		}
		//默认加的字段 用于传递数据
		$field['orderid'] = '排序';
		$this->addItem($field);
		$this->output();
	}
}
$out = new model();
$action = $_INPUT['a'];
if(!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action()
?>