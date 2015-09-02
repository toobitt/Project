<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: stream_synchro.php 4943 2011-10-28 09:56:15Z lijiaying $
***************************************************************************/
require('global.php');
class streamSynchroApi extends BaseFrm
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
	 * 
	 * Enter description here ...
	 */
	function up_stream_create()
	{
		//开启
		$gGlobalConfig['tvie'] = array('open' => '1',
			'up_stream_server' => array('client' => 'hoolo',
			'outhost' => 'live1.hoolo.tv',
			'api_server_name' => '192.168.33.44',
			'read_token' => '8k30f1p6u9yf9vou1lqc',
			'write_token' => 'ld2b5thbvanukhkq80md',
			'liveport' => '11105',
			),
			'stream_server' => array('client' => 'hoolo',
			'outhost' => 'live2.hoolo.tv',
			'api_server_name' => '192.168.33.14',
			'read_token' => '123456789',
			'write_token' => '987654321',
			'liveport' => '11105',
			'append_host' => 'live3.hoolo.tv,live4.hoolo.tv',
			),
			);
		if($this->settings['tvie']['open'])
		{
			include(CUR_CONF_PATH . 'lib/tvie_api.php');
			$up_tvie = new TVie_api($gGlobalConfig['tvie']['up_stream_server']);
			$up_channels = $up_tvie->get_all_channels();
			print_r($gGlobalConfig['tvie']['up_stream_server']);
			print_r($up_channels);
			//上游频道信息
			$up_channels = $up_channels['channels'];
			$up_ch_ids = array();
			if($up_channels)
			{
				foreach($up_channels as $key=>$value)
				{
					if($value['type'] == 'live')
					{
						$up_ch_ids[] = $value['id'];
					}
					
				}
			}
			
			//本地频道id
			$sql = "select ch_id from " . DB_PREFIX . "stream ";
			$q = $this->db->query($sql);
			$ch_ids = array();
			while($row = $this->db->fetch_array($q))
			{
				$ch_ids[] = $row['ch_id'];
			}
			$offset_ch_id = array_diff($up_ch_ids, $ch_ids);		//	上游频道id与本地频道id差集
			
			if(!$offset_ch_id)
			{
				$this->addItem('error');
			}
		
			if(is_array($offset_ch_id) && $offset_ch_id)
			{
				foreach($offset_ch_id as $ch_id)
				{
					$up_channel_info = $up_tvie->get_channel_by_id($ch_id);
					$up_channel_info = $up_channel_info['channel'];
					$streams = $up_channel_info['streams'];
					$server_id = $up_channel_info['server_id'];
					$type = $up_channel_info['type'];
					if($streams && is_array($streams))
					{
						$uri_arr = array();
						$other_info = array();
						foreach($streams as $key => $value)
						{
							$uri_arr[] = $value['uri'];
							$other_info[] = array(
										'id' =>  $value['id'],
										'name' =>  $value['name'],
										'uri' => $value['uri'],
										'recover_cache' =>  $value['recover_cache'],
										'source_name' =>  $value['source_name'],
										'drm' =>  $value['drm'],
										'backstore' =>  'flv',
										'wait_relay' =>  $value['wait_relay'],
										'audio_only' =>  $value['audio_only'],
										'bitrate' =>  $value['bitrate']
							);
							
						}
					}
				
					$info = array(
									'ch_id' => $ch_id,
									's_name' => $up_channel_info['display_name'],
									'ch_name' => $up_channel_info['channel_name'],
									'uri' => serialize($uri_arr),
									'type' => $type,
									'other_info' => serialize($other_info),
									'server_id' => $this->input['server_id'] ? $this->input['server_id'] : $server_id,
									'create_time' => TIMENOW,
									'update_time' => TIMENOW,
									'ip' => hg_getip(),
								);
					if($info)
					{
						$sql = "INSERT INTO " . DB_PREFIX . "stream SET ";
						$space = "";
						foreach($info as $key => $value)
						{
							$sql .= $space . $key . "=" . "'" . $value . "'";
							$space= ",";
						}
						$this->db->query($sql);
					}
					$info['id'] = $this->db->insert_id();
					
					$this->setXmlNode('stream','info');
					$this->addItem($info);
				}
			}
			$this->output();	
		}
	}
}
$out = new streamSynchroApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'up_stream_create';
}
$out->$action();
?>