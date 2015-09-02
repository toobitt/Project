<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/astro.class.php';
define('SCRIPT_NAME', 'astroinfoApi');
define('MOD_UNIQUEID','astroinfoApi');//模块标识
class astroinfoApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->astro = new astro();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function count()
	{

	}
	public function show()
	{

		$data = $this->astro->astroinfoadminselect();
		foreach($data as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	function index()
	{
	}
/*
 * 星座信息后台form
 */
	function detail()
	{
		$id = intval($this->input['id']);

		if($id < 1 || $id > 12)
		{
			$this->errorOutput('Id is error');
		}
		
			$sql = "SELECT * FROM ".DB_PREFIX."astro_app_info WHERE id = ".($id);
			$info = $this->db->query_first($sql);
			$info['astroimg'] = unserialize($info['astroimg']);
			$info['logo'] = hg_fetchimgurl($info['astroimg']);
			$info['astrostart'] = date("m-d", $info['astrostart']);
			$info['astroend'] = date("m-d", $info['astroend']);
			$this->addItem($info);
			$this->output();
	}

}
include ROOT_PATH . 'excute.php';
?>