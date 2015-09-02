<?php
/***************************************************************************

* (C)2004-2015 HOGE Software.
*
* $Id: dvr_checked_auto.php 19895 2013-04-08 02:42:01Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','live_monitor');
define('ROOT_DIR', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_DIR.'global.php');
require(CUR_CONF_PATH . 'lib/functions.php');
class live_monitor extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '监控直播流',	 
			'brief' => '',
			'space' => '6',	//运行时间间隔，单位秒
			'is_use' => 1,	//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	public function show()
	{
		$sql  = "SELECT cs.stream_name, cs.bitrate, c.id,c.code,c.name,c.time_shift,c.status,sc.ts_host FROM " . DB_PREFIX . "channel_stream cs LEFT JOIN " . DB_PREFIX . "channel c ON cs.channel_id = c.id LEFT JOIN " . DB_PREFIX . "server_config sc ON sc.id=c.server_id WHERE sc.type='nginx' ORDER BY cs.order_id ASC";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$livem3u8 = DATA_DIR . $r['code'] . '/' . $r['stream_name'] . '/live.m3u8';
			$logcache = CACHE_DIR . 'log_' . $r['code'] . '_' . $r['stream_name'] . '.php';
			if (!$r['status'])
			{
				$type = 0;
				$title = '频道' . $r['name'] . '&nbsp;&nbsp;已停止[' . date('Y-m-d H:i:s', time()) . '].';
			}
			elseif (!is_file($livem3u8))
			{
				$type = 0;
				$title = '频道' . $r['name'] . '&nbsp;&nbsp;信号' .$r['stream_name'] . '&nbsp;&nbsp;m3u8流文件已不存在[' . date('Y-m-d H:i:s', time()) . '].';
			}
			else
			{
				$filetime = @filemtime($livem3u8);
				if ((time() - $filetime) > 20)
				{
					$type = 0;
					$mark = md5(APP_UNIQUEID . '_live_' . $r['code'] . '_' . $r['stream_name'] . '_' . $filetime);
					$title = '频道' . $r['name'] . '&nbsp;&nbsp;信号' .$r['stream_name'] . '&nbsp;&nbsp;m3u8流文件已于[' . date('Y-m-d H:i:s', $filetime) . ']停止生成.';
				}
				else
				{
					$mark = md5(APP_UNIQUEID . '_live_' . $r['code'] . '_' . $r['stream_name'] . '_' . time());
					$title = '频道' . $r['name'] . '&nbsp;&nbsp;信号' .$r['stream_name'] . '&nbsp;&nbsp;m3u8流文件已于[' . date('Y-m-d H:i:s', $filetime) . ']恢复.';
					$type = 1;
				}
			}
			
			$lastlogtype = intval(@file_get_contents($logcache));
			if (!is_file($logcache) || $lastlogtype != $type)
			{
				file_put_contents($logcache, $type);
				$this->addmonitorlog($mark, $title, '', $type);
			}
		}
	}
	
	private function addmonitorlog($mark, $title, $content = '', $type = 0)
	{
		if (!$this->settings['App_servermonitor'])
		{
			return;
		}
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$curl = new curl($this->settings['App_servermonitor']['host'], $this->settings['App_servermonitor']['dir'] . 'admin/');
		
		$curl->setReturnFormat('json');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a', 'create');
		$curl->addRequestData('mark', $mark);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('type', $type);
		$curl->addRequestData('content', $content);
		$curl->request('logs_update.php');
	}
}

$out = new live_monitor();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>