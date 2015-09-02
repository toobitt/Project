<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: record.php 5128 2011-11-23 02:53:21Z develop_tong $
***************************************************************************/
define('MOD_UNIQUEID','record');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
class recordApi extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	function show()
	{
		$gap = 20; //以秒为单位进行
		$start_time = TIMENOW + 20;
		$sql = "select * from " . DB_PREFIX . "program_record where start_time=" . $start_time;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['rate'] && ($row['rate']>$row['toff']))
			{
				$new_time = $start_time + $row['rate'];
				$sql = "update " . DB_PREFIX . "program_record set start_time=" . $new_time ." where id=" . $row['id'];
				$this->db->query($sql);
				if($row['channel_id'])
				{
					$channel[] = $row['channel_id'];
				}
			}
		}
		$channel = array_unique($channel);
		$channel_id = implode(",", $channel);
		$sql = "select id,code,up_name from " . DB_PREFIX . "channel where id IN (" . $channel_id . ")";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$up_name = unserialize($r['up_name']);
			if ($up_name)
			{
				foreach($up_name as $key => $value)
				{
					$bit = 200;
					if(strstr($value, 'sd'))
					{
						$bit = 400;
					}
					if(strstr($value, 'hd'))
					{
						$bit = 800;
					}
					if(strstr($value, 'cd'))
					{
						$bit = 1000;
					}
					$info[$r['id']] = hg_get_stream_url($this->settings['tvie']['stream_server'], array('channel' => $r['code'], 'stream_name' => $value));
				}
			}
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		
	}
	public function detail()
	{
		
	}
	
	function unkonw()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new recordApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unkonw';
}
$out->$action();
?>