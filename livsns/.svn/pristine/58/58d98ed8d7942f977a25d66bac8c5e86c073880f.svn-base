<?php
/***************************************************************************
* $Id: member.php 12974 2012-10-25 03:43:11Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','member');//模块标识
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('UC_CLIENT_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class talkApi extends outerReadBase
{
	private $talk;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/talk.class.php';
		$this->talk = new talk();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出会员列表
	 * Enter description here ...
	 */
	public function show()
	{	
		$condition = $this->get_condition();
		$condition .= " AND state=1 "; 
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$ret = $this->talk->show($condition,$this->user['user_id'], $offset, $count);
		$this->addItem($ret);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->talk->count($condition);
		$this->addItem($info);
		$this->output();
	}
	
	public function detail()
	{
		if($this->user['user_id'])
		{
			$ret = $this->talk->detail($this->user['user_id']);
			$this->addItem($ret);
			$this->output();
		}
		else
		{
			$this->errorOutput('用户未登录！');
		}
	}
	
	private function get_condition()
	{
		return $condition;
	}
	
	public function index()
	{
		
	}
}

$out = new talkApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>