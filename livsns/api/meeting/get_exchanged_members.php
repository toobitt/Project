<?php
//获取个人名片信息
define('MOD_UNIQUEID','get_exchanged');
define('SCRIPT_NAME', 'get_exchanged');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/member_mode.php');
class get_exchanged extends outerReadBase
{
	private $member_mode;
	public function __construct()
	{
		parent::__construct();
		$this->member_mode = new member_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function count(){}
	public function detail(){}
	
	public function show()
	{
		if(!$this->user['user_id'])
		{
			$this->errorOutput(NO_LOGIN);
		}
		
		//获取嘉宾激活信息
		$_memberInfo = $this->member_mode->detail(''," AND member_id = '" .$this->user['user_id']. "' ");
		if(!$_memberInfo)
		{
			$this->errorOutput(YOU_HAVE_NOT_ACTIVATED);
		}
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 15;
		$orderby = '  ORDER BY e.create_time DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		//获取当前用户和哪些人已经交换过了名片
		$_exchanged = $this->member_mode->get_exchanged_members_by_id($_memberInfo['id'],$orderby,$limit);
		if(!$_exchanged)
		{
			$this->errorOutput(YOU_HAVE_NOT_EXCHANGE_WITH_SOMEBODY);
		}
		
		$this->addItem($_exchanged);
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');

?>