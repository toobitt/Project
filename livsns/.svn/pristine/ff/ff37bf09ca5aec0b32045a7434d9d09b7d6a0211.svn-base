<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID','gongjiao');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");

class lineApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/line.class.php');
		$this->line = new line();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function get_line_info()
	{
		$condition = '';
		if($this->input['name'])
		{
			$condition .= ' AND name LIKE "%'.trim($this->input['name']).'%"';
		}
		$condition .= ' ORDER BY routeid ASC';
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):20;
		$limit = " limit {$offset}, {$count}";
		$sql = "SELECT id,name
				FROM  " . DB_PREFIX ."line 
				WHERE 1".$condition.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{				
			$this->addItem($row);
		}
		$this->output();
	}
	
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new lineApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'get_line_info';
}
$out->$action();
?>
