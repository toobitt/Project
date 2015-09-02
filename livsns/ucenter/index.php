<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2118 2011-02-18 05:15:34Z yuna $
***************************************************************************/

define('ROOT_DIR', '../');
define('SCRIPTNAME', 'index');
require('./global.php');
require(ROOT_PATH . 'lib/class/status.class.php');
require_once(ROOT_PATH . 'lib/user/user.class.php');
class index extends uiBaseFrm
{	
	
	private $info;
	private $status;
	function __construct()
	{
		parent::__construct();
		header('Location:./user.php');
		$this->check_login();
		$this->load_lang['followers'];
		$this->load_lang['index'];
		$this->status = new status();
		$this->info = new user();

		
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{		
		$this->page_title = $this->lang['pageTitle'];

		$gScriptName = SCRIPTNAME;

		
		$app_modules = array(
			'mblog' => array( 
					'link' => '/t',
					'text' => '点滴',
					'desc' => '随时随地分享身边的新鲜事儿',
			),
			'video' => array( 
					'link' => '/v',
					'text' => '视频',
					'desc' => '记录点滴',
			),
			'topic' => array( 
					'link' => '/group',
					'text' => '话题',
					'desc' => '讨论讨论我们身边的一切',
			),
			'albums' => array( 
					'link' => '/albums',
					'text' => '相册',
					'desc' => '照片分享平台',
			),
			'test' => array( 
					'link' => '/test',
					'text' => '测试',
					'desc' => '这不是测试，这是娱乐',
			),
			'poll' => array( 
					'link' => '/vote',
					'text' => '投票',
					'desc' => '欢迎参与，谢谢您的支持',
			),
			'gift' => array( 
					'link' => '/gift',
					'text' => '礼物',
					'desc' => '送出一份祝福',
			),
			'game' => array( 
					'link' => '/game',
					'text' => '游戏',
					'desc' => '休闲游戏中心',
			),
			
		);
		$this->tpl->setTemplateTitle($this->page_title);
		$this->tpl->addVar('app_modules', $app_modules);
		$this->tpl->outTemplate('index');
	}
	
}
$out = new index();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>