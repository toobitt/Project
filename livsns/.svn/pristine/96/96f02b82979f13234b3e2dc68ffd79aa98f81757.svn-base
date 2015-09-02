<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 6082 2012-03-13 03:16:40Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . "global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('MOD_UNIQUEID','program_record_server');
class serverApi extends outerReadBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/server.class.php');
		$this->obj = new server();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 显示录播节目单
	 */
	function show()
	{
		
	}
	
	function getServerSource()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$ret = $this->obj->show($condition,$data_limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$tmp = array('id' => $v['id'],'name' => $v['name']);
				$this->addItem($tmp);
			}
			$this->output();
		}
	}
	
	public function get_condition()
	{
		return ' AND state=1 ';
	}

	public function count()
	{
		
	}

	public function detail()
	{
		$ret = $this->obj->detail();
		if(!empty($ret))
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
}

$out = new serverApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>