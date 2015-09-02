<?php
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class attentionShowApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/action.class.php');
		$this->action = new action();
		
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$data = array();
		$data = $this->getCondition();
		//增加显示
		$data['state'] = 1;
		$result = $this->action->get('option', '*', $data, $offset, $count, array('create_time'=>'desc'));
		
		$this->setXmlNode('option', 'detail');
		if($result)
		{
			foreach($result as $k=>$v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function detail()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 10;
		$data = array();
		$data = $this->getCondition();
		//增加显示
		$data['state'] = 1;
		$result = $this->action->get('option', '*', $data, $offset, $count, array('create_time'=>'desc'));
		
		$this->setXmlNode('option', 'detail');
		if($result)
		{
			foreach($result as $k=>$v)
			{
				$this->addItem_withkey($k, $v);
			}
		}
		$this->output();
	}
	
	public function count()
	{
		$data = array();
		$data = $this->getCondition();
		//增加显示
		$data['state'] = 1;
		$result = $this->action->get('option_sisn', 'count(id) as total,num', $data, 0, 1, array());
		$this->setXmlNode('option', 'count');
		if($result['total'])
		{
			$this->addItem_withkey('total', $result['num']);
		}
		else 
		{
			$this->addItem_withkey('total', $result['total']);
		}
		$this->output();
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public function getCondition()
	{
		$data = array();
		if(isset($this->input['source']))
		{
			$data['source'] = trim(urldecode($this->input['source']));
			if(strpos($data['source'], ','))
			{
				$data['source'] = "'" . str_replace(",", "','", $data['source']) . "'"; 
			}
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim(urldecode($this->input['source_id']));
		}
		if(isset($this->input['action']))
		{
			$data['action'] = trim(urldecode($this->input['action']));
			if(strpos($data['action'], ','))
			{
				$data['action'] = "'" . str_replace(",", "','", $data['action']) . "'"; 
			}
		}
		return $data;
	}
	
	/**
	 * 显示总数和当前用户是否操作
	 * Enter description here ...
	 */
	public function showUserSourceAction()
	{
		$data = array();
		$data = $this->getCondition();
		//增加显示
		$data['state'] = 1;
		$data['user_id'] = ($this->input['user_id']) ? trim($this->input['user_id']) : $this->checkUserExit();
		if(!$data['user_id'])
		{
			$data['user_id'] = -1;//默认为未登陆用户
		}
		$result = $this->action->get('option_sisn', 'count(id) as total,num', $data, 0, 1, array());
		$this->setXmlNode('option', 'showUserSourceAction');
		if($result['total'])
		{
			$this->addItem_withkey('total', $result['num']);
		}
		else 
		{
			$this->addItem_withkey('total', $result['total']);
		}
		$result = $result = $this->action->get('option', 'count(id) as total', $data, 0, 1, array());
		$this->addItem_withkey('state', $result);
		$this->output();
	}
	
	public function index()
	{
		
	}
	
	function __destruct()
	{
		parent::__destruct();
		unset($this->action);
	}
}

$out = new attentionShowApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'create';
}
$out->$action();