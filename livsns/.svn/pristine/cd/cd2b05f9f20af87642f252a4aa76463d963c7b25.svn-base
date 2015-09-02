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
define('MOD_UNIQUEID','share');//模块标识
class share_nodeApi extends adminBase
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
		include(CUR_CONF_PATH . 'lib/share.class.php');
		$this->obj = new share();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$fid = $this->input['fid']?'0':$this->input['fid'];
		foreach($this->settings['share_plat'] as $k=>$v)
		{
			$m = array('id'=>$k,"name"=>$v['name_ch'],"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
	}
	
	public function app_show()
	{
		$fid = $this->input['fid']?'0':$this->input['fid'];
		foreach($this->settings['status'] as $k=>$v)
		{
			$m = array('id'=>$k,"name"=>$v,"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
	}
	
	public function plat_show()
	{
		$fid = $this->input['fid']?'0':$this->input['fid'];
		$plats = $this->obj->get_all_plat();
		foreach($plats as $k=>$v)
		{
			$m = array('id'=>$v['id'],"name"=>$v['name'],"fid"=>$fid,"depth"=>1 ,'is_last'=>1);
			$this->addItem($m);
		}
		$this->output();
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

$out = new share_nodeApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>


			