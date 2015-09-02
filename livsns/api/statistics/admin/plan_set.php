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
class plan_setApi extends BaseFrm
{
	/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include plan_set.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/plan_set.class.php');
		$this->obj = new plan_set();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function insert_plan_set()
	{
		$data = array(
			1=>array('name'=>'文稿','host'=>'localhost','path'=>'livsns/api/','filename'=>'test.php','action'=>'show','fid'=>0),
			2=>array('name'=>'图片','host'=>'localhost','path'=>'livsns/api/','filename'=>'test.php','action'=>'show','fid'=>1),
			3=>array('name'=>'','host'=>'localhost','path'=>'livsns/api/','filename'=>'test.php','action'=>'show','fid'=>2),
			4=>array('name'=>'','host'=>'localhost','path'=>'livsns/api/','filename'=>'test.php','action'=>'show','fid'=>3),
		);
		$fid = 0;
		foreach($data as $k=>$v)
		{
			$indata = array(
				'name' => $v['name'],
				'host' => $v['host'],
				'path' => $v['path'],
				'filename' => $v['filename'],
				'action' => $v['action'],
			);
			$indata['fid'] = empty($v['fid'])?0:(empty($data[$v['fid']]['set_id'])?0:$data[$v['fid']]['set_id']);
			$fid = $this->obj->insert_set($indata);
			$data[$k]['set_id'] = $fid;
		}
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

$out = new plan_setApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			