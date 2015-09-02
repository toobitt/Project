<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: groups.class.php 12696 2012-10-20 00:57:57Z daixin $
***************************************************************************/
class groups
{
	private $curl;
	
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_group']['host'], $gGlobalConfig['App_group']['dir']);
	}
	
	function __destruct()
	{
		//unset($this->curl);
	}
	
	//获取全部圈子信息
	public function groups($page, $count)
	{
		$this->curl->setSubmitType('get');	
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'show');
		return $this->curl->request('group.php');
	}
	
	//获取圈子总数
	public function get_count()
	{
		$this->curl->setSubmitType('get');	
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'count');
		$ret = $this->curl->request('group.php');
		return $ret;
	}
	
	//根据ID检查对应圈子是否存在
	public function check_group_exists($group_ids)
	{
		$this->curl->setSubmitType('get');	
		$this->curl->initPostData();
		$this->curl->addRequestData('group_ids', $group_ids);
		$this->curl->addRequestData('a', 'check_group_exists');
		$ret = $this->curl->request('group.php');
		return $ret[0];
	}
	
}
?>