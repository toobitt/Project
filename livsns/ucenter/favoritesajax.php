<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: favoritesajax.php 776 2010-12-17 05:47:15Z yuna $
***************************************************************************/

define('ROOT_DIR', '../');
require('./global.php');
require(ROOT_PATH . 'lib/class/favorites.class.php');

class fupdate extends uiBaseFrm
{	
	
	private $info;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function updatefavorites()
	{
		//测试数据
		//$this->input['statusid'] = 424;
		//实例化收藏类
		$favorites = new favorites();
		//传递参数
		$status_id = $this->input['statusid'];
		$ret = json_encode($favorites->update($status_id));
		echo $ret;
	}
	public function deletefavorites()
	{
		//测试数据
		//$this->input['id'] = 467;
		//实例化收藏类
		$favorites = new favorites();
		//传递参数
		$status_id = $this->input['id'];
		$favorites->deletefavorites($status_id);
		//print_r($ret);
	}
	

}
$out = new fupdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'deletefavorites';
}
$out->$action();
?>