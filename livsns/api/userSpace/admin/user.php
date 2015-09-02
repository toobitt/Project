<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','clouduser');//模块标识
define('SCRIPT_NAME', 'clouduser');
require(CUR_CONF_PATH . 'lib/auth_app.php');
class clouduser extends adminReadBase
{
	protected $auth;
	function __construct()
	{
		parent::__construct();
		$this->auth = new authapp();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	function show()
	{
		//$this->errorOutput(var_export($this->input,1));
		$conditions = $this->get_condition();
		//$this->errorOutput(var_export($conditions,1));
		$user_list = $this->auth->user_list($conditions);
		if(!empty($user_list) && $user_list)
		{
			foreach ($user_list as $val)
			{
				$user_id[] = $val['id'];
			}
			if($user_id)
			{
				$sql = 'SELECT * FROM  ' . DB_PREFIX . 'user_queue WHERE user_id IN('.implode(',', $user_id).')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$info[$row['user_id']] = $row;
				}
				foreach ($user_list as $val)
				{
					$val['balance'] = $info[$val['id']]['balance'];
					$val['status'] = $info[$val['id']]['status'];
					$this->addItem($val);
				}
			}
		}
		$this->output();
	}
	function count()
	{
		$conditions = $this->get_condition();
		$total = ($this->auth->user_count($conditions));
		exit(json_encode($total));
	}
	function get_condition()
	{
		$condition = array();
		if($this->input['_id'])
		{
			$condition['_id'] = intval($this->input['_id']);
		}
		else
		{
			$condition['_id'] = DEFAULT_ORG . ',' . DEFAULT_ORG_2;
		}
		if($this->input['offset'])
		{
			$condition['offset'] = intval($this->input['offset']);
		}
		if($this->input['count'])
		{
			$condition['count'] = intval($this->input['count']);
		}
		return $condition;
	}
	function detail()
	{
		print_r($this->auth->user_detail(array('id'=>1)));
	}
	//查看用情况
	function get_bucket_status()
	{
		
	}
	//查看用户视频
	function get_user_video()
	{
	}
	//
	function get_org()
	{
		$org_id = DEFAULT_ORG . ',' . DEFAULT_ORG_2;
		$orginfo = $this->auth->get_org(array('id'=>$org_id));
		if(!empty($orginfo) && $orginfo)
		{
			foreach ($orginfo as $val)
			{
			$this->addItem($val);
			}
			
		}
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';