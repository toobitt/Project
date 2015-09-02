<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class optionUpdateApi extends outerUpdateBase
{
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/action.class.php');
		$this->action = new action();
		
	}
	
	//获取对象参数
	public function getObject()
	{
		$data = array();
		$data['source'] = trim(urldecode($this->input['source']));
		if(!$data['source'])
		{
			$this->errorOutput("缺少对象性质");
		}
		$data['source_id'] = trim(urldecode($this->input['source_id']));
		if(!$data['source_id'])
		{
			$this->errorOutput("缺少对象id");
		}
		$data['action'] = trim(urldecode($this->input['action']));
		if(!$data['action'])
		{
			$this->errorOutput("缺少对象属性");
		}
		return $data;
	}
	/**
	 * 创建关系
	 * Enter description here ...
	 */
	public function create()
	{
		$this->checkUserExit();
		$post = $data = array();
		$post = $data = $this->getObject();
		
		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->checkUserExit();
		if(!$data['user_id'])
		{
			$this->errorOutput("缺少添加人");
		}
		$this->setXmlNode('option', 'create');
		$total = $this->action->get('option','count(id) as total', $data, 0, 1, array());
		if($total)
		{
			$this->addItem_withkey('state', true);
		}
		else 
		{
			$data['create_time'] = TIMENOW;
			$result =  $this->action->insert('option', $data);
			if($result)
			{
				//更新统计表
				$this->updateSignData($post, 1);
				$this->addItem_withkey('state', true);
			}
		}
		$this->output();
	}
	/**
	 * 删除关系
	 * Enter description here ...
	 */
	public function delete()
	{
		$this->checkUserExit();
		$post = $data = array();
		$post = $data = $this->getObject();
		if(isset($this->input['id']))
		$data['user_id'] = isset($this->input['user_id']) ? trim($this->input['user_id']) : $this->checkUserExit();
		if(!$data['user_id'])
		{
			$this->errorOutput("缺少添加人");
		}
		$this->setXmlNode('option', 'delete');
		$total = $this->action->get('option','count(id) as total', $data, 0, 1, array());
		if($total)
		{
			$result =  $this->action->delete('option', $data);
			if($result)
			{
				//更新统计表
				$this->updateSignData($post, -1);
				$this->addItem_withkey('state', true);
			}
		}
		else 
		{
			$this->addItem_withkey('state', true);
		}
		$this->output();
	}
	/**
	 * 
	 * Enter description here ...
	 */
	function updateSignData($data, $num)
	{
		$total = 0;
		$total = $this->action->get('option_sign', 'count(id) as total', $data, 0, 1, array());
		if($total)
		{
			$this->action->update('option_sign', array('num'=>$num), $data, array('num'=>$num));
		}
		else 
		{
			$data['num'] = $num;
			$this->action->insert('option_sign', $data);
		}
	}
	
	public function update()
	{
		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
	function __destruct()
	{
		parent::__destruct();
		unset($this->action);
	}
}

$out = new optionUpdateApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();