<?php
/***************************************************************************
* $Id: live_control_auto.php 32507 2013-12-10 05:55:18Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','live_control_auto');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class liveControlAutoApi extends cronBase
{
	private $mLivemms;
	private $mLiveControl;
	private $mLive;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/livemms.class.php';
		$this->mLivemms = new livemms();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '删除播控过期信号',	 
			'brief' => '删除多少时间之前没被切播使用中的临时信号 默认1800秒',
			'space' => '1800',	//运行时间间隔，单位秒
			'is_use' => 0,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	/**
	 * 删除多少时间之前没被切播使用中的临时信号 默认1800秒
	 * Enter description here ...
	 */
	public function live_control_auto()
	{
		$server_info = $this->settings['server_info'];
		
		if (!$server_info['host'])
		{
			$this->errorOutput('播控服务器未配置');
		}
		$host				= $server_info['host'];
		$input_dir  		= $server_info['input_dir'];
		$sql = 'SELECT token, chg_id, type FROM ' . DB_PREFIX . "stream_info WHERE cnt < 1";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$input_data = array(
				'action'	=> 'delete',
				'id'		=> $r['chg_id'],
			);
			if ($r['type'] == 'stream')
			{
				$ret_delete = $this->mLivemms->inputStreamOperate($host, $input_dir, $input_data);
			}
			if ($r['type'] == 'file')
			{
				$ret_delete = $this->mLivemms->inputFileOperate($host, $input_dir, $input_data);
			}
			if ($ret_delete['result'])
			{
				$this->addItem($r);
				$sql = 'DELETE FROM ' . DB_PREFIX . "stream_info WHERE token='{$r['token']}'";
				$this->db->query($sql);
			}
		}
		$this->output();
	}
}

$out = new liveControlAutoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'live_control_auto';
}
$out->$action();
?>