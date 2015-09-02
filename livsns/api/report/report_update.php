<?php
include_once('./global.php');
define('MOD_UNIQUEID','cp_report_m');//模块标识

class reportupdateApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		require_once  'lib/reportLib.class.php';
	}
	
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	
	//用户在线判断
	public function checkUserExit()
	{
		//$this->user = array('user_id'=>84);
		if(!$this->user['user_id'])
		{
			$this->errorOutput("用户没有登录");
		}
		return $this->user['user_id'];
	}
	
	/**
	 * 创建举报
	 * 提交来源：source
	 * 提交来源id：source_id
	 * 默认提交人：user_id
	 * 提交原因： 	comtent
	 */
	public function create()
	{
		$data = array();
		$data['user_id'] = $this->checkUserExit();
		$data['source_id'] = trim($this->input['source_id']);
		$data['source'] = trim(htmlspecialchars_decode(urldecode($this->input['source'])));
		if(empty($data['source_id']) || empty($data['source']))
		{
			$this->errorOutput("你的举报对象目标不明确");
		}
		$data['comtent'] = trim(htmlspecialchars_decode(urldecode($this->input['comtent'])));
		if(isset($this->input['source_name']))
		{
			$data['source_name'] = trim(htmlspecialchars_decode(urldecode($this->input['source_name'])));
		}
		if(isset($this->input['source_img']))
		{
			$data['source_img'] = trim(htmlspecialchars_decode(urldecode($this->input['source_img'])));
		}
		if(isset($this->input['source_url']))
		{
			$data['source_url'] = trim(htmlspecialchars_decode(urldecode($this->input['source_url'])));
		}
		$this->setXmlNode('report', 'create');
		$result = array();
		$this->aReportLib = new reportLib();
		$result = $this->aReportLib->get('report', 'rid,state', $data, 0 ,1 ,array(), array(), array());
		$rid = 0;
		if(!$result)
		{
			//增加举报时间
			$data['create_time'] = TIMENOW;
			//来源部分
			$data['app_name'] = $this->user['display_name'];
			$rid = $this->aReportLib->insert('report', $data);
			//重复提交
			$this->addItem_withkey('report_repeat', 0);
		}
		else 
		{
			$rid = $result['rid'];
			if($result['state'] > 2)
			{
				$this->addItem_withkey('report_state', $gGlobalConfig['activity_status'][$result['state']]);
			}
			//重复提交
			$this->addItem_withkey('report_repeat', 1);
		}
		$this->addItem_withkey('report_id', $rid);
		$this->output();
	}
	
	
	function __destruct()
	{
		parent::__destruct();
	}
}
/**
 *  程序入口
 */
$out = new reportupdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'unknow';
}
$out->$action();
?>
