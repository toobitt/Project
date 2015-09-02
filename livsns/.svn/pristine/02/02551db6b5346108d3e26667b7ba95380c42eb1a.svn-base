<?php

include_once('./global.php');
define('MOD_UNIQUEID','cp_report_m');//模块标识

class reportshowApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
		require_once  CUR_CONF_PATH.'lib/reportLib.class.php';
	}
	
	public function unknow()
	{
		$this->errorOutput("你搜索得方法不存在");
	}
	
	public function getCondition()
	{
		$data = array();
		$data['source'] = 'team';
		$data['state'] = 2;
		if(isset($this->input['state']))
		{
			$data['state'] = trim($this->input['state']);
		}
		if(isset($this->input['user_id']))
		{
			$data['user_id'] = trim($this->input['user_id']);
		}
		if(isset($this->input['rid']))
		{
			$data['rid'] = trim($this->input['rid']);
		}
		if(isset($this->input['source']))
		{
			$data['source'] = trim(htmlspecialchars_decode(urldecode($this->input['source'])));
		}
		if(isset($this->input['source_name']))
		{
			$data['source_name'] = trim(htmlspecialchars_decode(urldecode($this->input['source_name'])));
		}
		if(isset($this->input['source_url']))
		{
			$data['source_url'] = trim(htmlspecialchars_decode(urldecode($this->input['source_url'])));
		}
		if(isset($this->input['source_id']))
		{
			$data['source_id'] = trim($this->input['source_id']);
		}
		if(isset($this->input['comtent']))
		{
			$data['comtent'] = trim(htmlspecialchars_decode(urldecode($this->input['comtent'])));
		}
		return $data;
	}
	
	/**
	 * 返回列表
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 6;
		
		$data = array();
		$data = $this->getCondition();
		
		$result = array();
		$this->aReportLib = new reportLib();
		$result = $this->aReportLib->get('report','rid,user_id,source_id,source,source_name,source_img,source_url,comtent,create_time,state', $data, $offset, $count, array('source'=>''), array(), array());
		if($result)
		{
			foreach($result as $k)
			{
				if($k['source_img'])
				{
					$k['source_img'] = unserialize($k['source_img']);
				}
				if(NEED_TIME_TO_UNIX)
				{
					if($k['create_time'] && is_numeric($k['create_time']))
					{
						$k['create_time'] = date("Y-m-d H:i:s", $k['create_time']);
					}
				}
				$this->addItem_withkey($k['rid'], $k);
			}
		}
		$this->output();
	}
	
	/**
	 * 返回对应总数
	 */
	public function count()
	{
		$data = array();
		$data = $this->getCondition();
		
		$result = 0;
		$this->aReportLib = new reportLib();
		$result = $this->aReportLib->get('report','count(rid) as total', $data, 0, 1, array('source'=>''), array(), array());
		$this->addItem_withkey('total', $result);
		$this->output();
	}
	
	/**
	 * 显示具体某条举报
	 */
	public function detail()
	{
		$rid = intval($this->input['rid']);
		$result = array();
		$this->aReportLib = new reportLib();
		$result = $this->aReportLib->get('report','*', $data, 0, 1, array(), array(), array());
		if($result)
		{
			foreach($result as $k => $v)
			{
				if($k == 'source_img' && $v)
				{
					$v = unserialize($v);
				}
				if(NEED_TIME_TO_UNIX)
				{
					if(($k == 'create_time' || $k == 's_create_time') && is_numeric($v))
					{
						$v = date("Y-m-d H:i:s",$v);
					}
				}
				$this->addItem_withkey($k , $v);
			}
		}
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
$out = new reportshowApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>
