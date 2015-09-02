<?php
require('global.php');
define('MOD_UNIQUEID','mkpublish');//模块标识
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
class mkpublish_planApi extends adminBase
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
		include(CUR_CONF_PATH."lib/common.php");
		include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
		$this->obj = new mkpublish();
		$this->pub_config= new publishconfig();
		$this->pub_content= new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$plan = $this->obj->get_mkpublish_plan($this->get_condition,$offset,$count);
		$this->addItem($plan);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."mkpublish_plan WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = ' order by publish_time ';
		return $condition;
	}
	
	
}

$out = new mkpublish_planApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>
