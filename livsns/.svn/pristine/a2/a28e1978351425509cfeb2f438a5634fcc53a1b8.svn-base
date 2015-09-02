<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class columns extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$fid = intval($this->input['fid']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'columns WHERE father_id = '.$fid;
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			$this->addItem($row);
		}
		$this->output();
	}
	function getcolfathers()
	{
		$id = intval($this->input['colid']);
		if($this->input['cmscol'])
		{
			$id = $this->transcms2w(intval($this->input['colid']));
		}
		$sql = 'SELECT parents FROM '.DB_PREFIX.'columns WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$this->addItem(array($id=>explode(',',$ret['parents'])));
		$this->output();
	}
	function transcms2w($cmscol = 0)
	{
		$sql = 'SELECT id FROM '.DB_PREFIX.'columns WHERE cms_columnid = '.intval($cmscol);
		$ret = $this->db->query_first($sql);
		return intval($ret['id']);
	}
}
$out = new columns();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 