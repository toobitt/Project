<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/solidify.class.php';
define('MOD_UNIQUEID', 'dingdone_app');

class app_solidify extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new solidify();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 显示数据
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
		);
		$solidify_info = $this->api->show($data);
		$this->setXmlNode('solidify_info', 'solidify');
		if ($solidify_info)
		{
			foreach ($solidify_info as $solidify)
			{
				$this->addItem($solidify);
			}
		}
		$this->output();
	}
	
	/**
	 * 数据总数
	 */
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 单个数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$data = array('id' => $id);
		$solidify_info = $this->api->detail('solidify_module', $data);
		if ($solidify_info['pic'] && unserialize($solidify_info['pic']))
		{
		    $solidify_info['pic'] = unserialize($solidify_info['pic']);
		}
		$this->addItem($solidify_info);
		$this->output();
	}
	
	public function get_solidify_config()
	{
		$solidify_id = $this->input['solidify_id'];
		$user_id = $this->input['user_id'];
		if(!$solidify_id)
		{
			$this->errorOutput(NO_SOLIDIFY_ID);
		}
		
		if(!$user_id)
		{
			$this->errorOutput(NO_USER_ID);
		}
		
		//查询出对应的参数
		$cond = " AND user_id = '" .$user_id. "' AND solidify_id = '" .$solidify_id. "' ";
		$param = $this->api->get_config_param($cond);
		$this->addItem($param);
		$this->output();
	}
	
	public function create_solidify_config()
	{
		$solidify_id = $this->input['solidify_id'];
		$user_id = $this->input['user_id'];
		$param = $this->input['param'];
		if(!$solidify_id)
		{
			$this->errorOutput(NO_SOLIDIFY_ID);
		}
		
		if(!$user_id)
		{
			$this->errorOutput(NO_USER_ID);
		}
		
		if(!$param)
		{
			$this->errorOutput(NO_SOLIDIFY_PARAM);
		}
		
		$arr = array(
			'user_id' => $user_id,
			'solidify_id' => $solidify_id,
			'param'	=> addslashes(serialize($param)),
		);
		
		$ret = $this->api->create_solidify_config($arr);
		$this->addItem($ret);
		$this->output();
	}
	
	public function update_solidify_config()
	{
		$id = $this->input['id'];
		$param = $this->input['param'];
		
		if(!$id)
		{
			$this->errorOutput(NO_CONFIG_ID);
		}
		
		if(!$param)
		{
			$this->errorOutput(NO_SOLIDIFY_PARAM);
		}
		
		$arr = array(
			'param'	=> addslashes(serialize($param)),
		);
		
		$ret = $this->api->update_solidify_config($id,$arr);
		$this->addItem($ret);
		$this->output();
	}
	
	public function get_solidify_by_user()
	{
		if(!$this->input['user_id'])
		{
			$this->errorOutput(NO_USER_ID);
		}	
		
		$ret = $this->api->get_solidify_by_user($this->input['user_id']);
		$this->addItem($ret);
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_solidify();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>