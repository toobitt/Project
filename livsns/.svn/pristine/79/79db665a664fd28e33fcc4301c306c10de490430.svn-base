<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count|unknow
* @private function get_condition
*
* $Id: news.php 6930 2012-05-31 07:16:07Z repheal $
***************************************************************************/
require('global.php');
require_once(ROOT_PATH.'lib/class/publishplan.class.php');
class publishApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include news.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/publish.class.php');
		$this->obj = new publish();
		include(CUR_CONF_PATH . 'lib/publishsys.class.php');
		$this->pubsys = new publishsys();
		
		$this->pubplan = new publishplan();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function check_plan()
	{
		$planset = array();
		
		//获取未发布并且在规定时间内的发布计划
		$con = " AND status=1 AND publish_time<=".TIMENOW;
		$pubdata = $this->obj->get_plan_by_status($con);
		
		//更改被取出来的计划状态
		foreach($pubdata as $k=>$v)
		{
			$pubdataids .= $v['id'].',';
		}
		if($pubdataids = trim($pubdataids,','))
		{
			$this->obj->update_plan_status($pubdataids,2);
		}
		
		//取出计划配置
		$setdata = $this->obj->get_plan_set();
		foreach($setdata as $k=>$v)
		{
			$planset[$v['id']] = $v;
		}
		
		foreach($pubdata as $k=>$v)
		{
			if(empty($v['class_id']))
			{
				//如果没有填写分类id，说明只是逐步发布
				
				if(!empty($planset[$v['set_id']]))
				{
					$nowset = $planset[$v['set_id']];
					
					//到对应接口取发布的内容
					$this->pubplan->setAttribute($nowset['host'],$nowset['path'],$nowset['filename'],$nowset['action']);
					$contentdata = $this->pubplan->get_content($v['fromid']);
					
					if(!empty($contentdata))
					{
						//把内容发布到发布系统里
						$expand_id = $this->pubsys->insert_content($contentdata);
						//发布后的内容id传给各自模块的接口记录
						$expand_data = array('from_id'=>$from_id,
											'expand_id'=>$expand_id
											);
						$this->pubsys->insert_expand_id($expand_data);
					}
					
				}
			}
			else
			{
				//如果有填写分类id，把这个分类下的所有内容都进行发布
				
			}
		}
	}
	
	public function get_content($apiurl,$fromid)
	{
		
	}

	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new publishApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			