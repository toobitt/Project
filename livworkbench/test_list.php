<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'test');
require('./global.php');
class test extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		$this->tpl->setSoftVar('lib'); //设置软件界面
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		hg_add_head_element('js-c',$str);
		$list = array(
			array(
				'name' => '马三三',
				'categories' => '网友上传',
				'audit' => '待审核',
				'pubdate' => '2011-12-12 12:12:60',
				'title' => '安徽铜陵狮子山区加强党的建设工作综述',
				'time' => '3\'21"',
				'pubdate' => '2011-12-12 12:12:60',
				'stream' => '500',
				'source' => '新闻综合频道',
				'key' => '新闻 资讯',
			),array(
				'name' => '马三三',
				'categories' => '网友上传',
				'audit' => '待审核',
				'pubdate' => '2011-12-12 12:12:60',
				'title' => '安徽铜陵狮子山区加强党的建设工作综述',
				'time' => '3\'21"',
				'pubdate' => '2011-12-12 12:12:60',
				'stream' => '500',
				'source' => '新闻综合频道',
				'key' => '新闻 资讯',
			),
		
		);

		$colunm = array(
			array(
				'column' => '第一时间',
			),
			array(
				'column' => '第二时间',
			),
		);


		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('colunm', $colunm);
		$this->tpl->addVar('list', $list);
		$this->tpl->outTemplate('item');
	}
	
}
include (ROOT_PATH . 'lib/exec.php');
?>