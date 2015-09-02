<?php
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once CUR_CONF_PATH.'lib/constellation.class.php';
require_once CUR_CONF_PATH.'lib/astrojson.php';
class fortuneinfo extends appCommonFrm
{
	//
	protected $api_uri;
	protected $dataType;
	function __construct()
	{
		$this->api_uri = APIURI;
		$this->dataType = 'json';
		parent::__construct();
		$this->constellation = new constellation();
		$this->astrojson = new astrojson();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{

		$sql = 'SELECT id,astrofun,astrofun,astrofuncn,astrofunimg,fortuneinfostart,fortuneinfoend FROM '.DB_PREFIX.'astro_app_fortuneinfo WHERE 1 ';
		$query = $this->db->query($sql);
		$data = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['astrofunimg'] = unserialize($row['astrofunimg']);
			$row['logo'] = hg_fetchimgurl($row['astrofunimg']);
			$row['fortuneinfostart']=date("Y年m月d日",$row['fortuneinfostart']);
			$row['fortuneinfoend']=date("Y年m月d日",$row['fortuneinfoend']);
				
			$data[$row['id']] = $row;
		}

		return $data;
	}


}
