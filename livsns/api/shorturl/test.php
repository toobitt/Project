<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: test.php 519 2010-12-14 06:12:26Z develop_tong $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class testApi extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function test()
	{
		$this->ConnectDB();
		$this->ConnectQueue();
		$this->ConnectMemcache();
		$this->setXmlNode('statuses', 'status');
		$data = array(
				'k1' => array('id' => 1, 'text' => 'text1',  'user' => array('id' => 1, 'name' => 'user1')),	
				'k2' => array('id' => 2, 'text' => 'text2',  'user' => array('id' => 1, 'name' => 'user1')),	
				'k3' => array('id' => 3, 'text' => 'text3',  'user' => array('id' => 2, 'name' => 'user2')),	
		);
		$i = 1;
		$this->memcache->set('data' . $i, $data);
		$t = $this->memcache->get('data' . $i);
		$this->addItem($t);
		foreach ($data AS $item)
		{
			$this->addItem($item);
		}
		$this->addItem($_COOKIE);
		$this->addItem($this->input);
		//$this->debug($this->mData);
		$this->output();
		//$this->errorOutput();
		
	}
}
$out = new testApi();
$out->test();
?>