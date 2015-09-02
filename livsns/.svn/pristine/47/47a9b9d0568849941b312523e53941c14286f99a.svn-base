<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: article.php 4808 2011-10-18 00:50:25Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class config extends LivcmsFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取文章列表数据
	 */
	public function show()
	{
		$config = array(
			'subject' => '分享应用：杭州电视台',
			'message' => '我觉得这个应用非常不错，分享给你，下载地址：http://itunes.apple.com/cn/app/hang-zhou-dian-shi-tai/id503797798?mt=8'	
			);
		$this->addItem($config);
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new config();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
