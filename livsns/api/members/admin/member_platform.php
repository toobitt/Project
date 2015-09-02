<?php
/***************************************************************************
* $Id: member_platform.php 41744 2014-11-19 01:56:36Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID','member_platform');//模块标识
require('./global.php');
class memberPlatformApi extends adminReadBase
{
	private $mMemberPlatform;
	public function __construct()
	{
		parent::__construct();
		
		require_once CUR_CONF_PATH . 'lib/member_platform.class.php';
		$this->mMemberPlatform = new memberPlatform();
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
		
		$info 	= $this->mMemberPlatform->show($condition, $offset, $count);

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
		$info = $this->mMemberPlatform->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMemberPlatform->count($condition);
		echo json_encode($info);
	}
	
	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		
		if(isset($this->input['date_search']) && !empty($this->input['date_search']))
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		return $condition;
	}
		
	public function get_appinfo()
	{
		include_once ROOT_PATH . 'lib/class/curl.class.php';
		$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'].'admin/');
		$curl->initPostData();
		$curl->addRequestData('count', 100);
		$ret = $curl->request('auth.php');
		if(is_array($ret) && $ret)
		{
			foreach($ret as $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
}

$out = new memberPlatformApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>