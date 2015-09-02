<?php
define('MOD_UNIQUEID','medal_manage');//模块标识
require('./global.php');
require_once CUR_CONF_PATH . 'lib/medal_manage.class.php';
class medal_manage extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->medalmanage = new medalmanage();
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
		$info 	= $this->medalmanage->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if($v['expiration']!=0)
				{
					$v['expiration']=$v['expiration'].'天';
				}
				else
				{
					$v['expiration']='永久有效';
				}
				$v['type_name']=$this->settings['medal_type'][$v['type']];
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			$this->errorOutput(NO_DATA_ID);
		}
		$info = $this->medalmanage->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition=$this->get_condition();
		$sql = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "medal WHERE 1 ".$condition;
		$info = $this->db->query_first($sql);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		
		return $condition;
	}
}

$out = new medal_manage();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>