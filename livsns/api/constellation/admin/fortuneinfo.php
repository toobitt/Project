<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/fortuneinfo.class.php';
define('SCRIPT_NAME', 'fortuneinfoApi');
define('MOD_UNIQUEID','fortuneinfoApi');//模块标识
class fortuneinfoApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->fortuneinfo = new fortuneinfo();
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

		$data = $this->fortuneinfo->show();
		foreach($data as $k => $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	function index()
	{
	}

	function detail()
	{
		$id = intval($this->input['id']);

		$sql = "SELECT * FROM ".DB_PREFIX."astro_app_fortuneinfo WHERE id = ".($id);
		$info = $this->db->query_first($sql);
		$info['astrofunimg'] = unserialize($info['astrofunimg']);
		$info['logo'] = hg_fetchimgurl($info['astrofunimg']);
		$info['fortuneinfostart']=date("Y年m月d日",$info['fortuneinfostart']);
		$info['fortuneinfoend']=date("Y年m月d日",$info['fortuneinfoend']);
		$this->addItem($info);
		$this->output();
	}

}
include ROOT_PATH . 'excute.php';
?>