<?php
/***************************************************************************
* $Id: member_extension_field.php 26794 2013-08-01 04:34:02Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member_purview');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/member_purview.class.php';
class memberpurview extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->purview = new purview();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	public function show()
	{	
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;
		$info 	= $this->purview->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}
	
		$this->output();
	}
	
	public function detail()
	{
		$id = trim($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput('请传权限id');
		}
		$info = $this->purview->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "purview WHERE 1 " . $condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND pname LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if (isset($this->input['allow']) && $this->input['allow'] != -1)
		{
			$condition .= " AND allow = " . intval($this->input['allow']);
		}
		
		return $condition;
	}

}

$out = new memberpurview();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>