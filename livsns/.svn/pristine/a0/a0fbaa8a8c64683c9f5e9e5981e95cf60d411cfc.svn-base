<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: channel.php 4370 2011-08-09 08:13:28Z lijiaying $
***************************************************************************/
require('global.php');
class tvie extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 显示
	 */
	function show()
	{
		$this->get_tvie_channels();
	}

	function uri_info()
	{
		$sql = "select id,s_name from " . DB_PREFIX . "stream ";
		$s = $this->db->query($sql);
		while($row = $this->db->fetch_array($s))
		{
			$this->addItem($row);
		}
		
		$this->output();
	}
	private function get_tvie_channels()
	{
		if (!$this->settings['tvie']['open'])
		{
			return;
		}
		include(CUR_CONF_PATH . 'lib/tvie_api.php');
		$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
		$ret = $tvie_api->get_all_channels();
		$stream = array();
		
		$this->setXmlNode('stream' , 'info');
		if(is_array($ret['channels']))
		{
			foreach($ret['channels'] as $key => $value)
			{
				if($value['type'] == 'live')
				{
					$this->addItem($value);
					//hg_pre($value);
				}
				
			}
		}
		$this->output();
		
	}

	private function get_tvie_channel_byid($channel_id)
	{
		if (!$this->settings['tvie']['open'])
		{
			return;
		}
		include(CUR_CONF_PATH . 'lib/tvie_api.php');
		$tvie_api = new TVie_api($this->settings['tvie']['up_stream_server']);
		$ret = $tvie_api->get_channel_by_id($channel_id);
		$stream = array();
		
		$this->setXmlNode('stream' , 'info');
		if(is_array($ret['channel']))
		{
			foreach($ret['channel'] as $key => $value)
			{
				$this->addItem($value);
			}
		}
		$this->output();
		
	}
	
}

$out = new tvie();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>