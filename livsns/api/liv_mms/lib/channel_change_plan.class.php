<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
***************************************************************************/
class ChannelChangePlan extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 计划任务添加
	 * Enter description here ...
	 * @param  $ch_id
	 * @param  $ch2_id
	 * @param  $stream_name
	 * @param  $stream_uri
	 * @param  $stream_status
	 * @param  $live_delay
	 * @param  $backlive_time
	 */
	public function change_plan_create($ch_id, $ch2_id, $stream_name, $stream_uri, $stream_status, $change_time, $toff, $backlive_time)
	{	
		if($ch_id || $ch2_id)
		{
			$info = array(
							'ch_id' => intval($ch_id),
							'ch2_id' => intval($ch2_id),
							'stream_name' =>serialize($stream_name),
							'stream_uri' => serialize($stream_uri),
							'stream_status' => $stream_status,
							'change_time' => $change_time,
							'toff' => $toff,
							'backlive_time' => $backlive_time,
							'create_time' => TIMENOW,
							'update_time' => TIMENOW,
							'ip' => hg_getip(),
						);
					
			$createsql = "INSERT INTO " . DB_PREFIX . "qbtask SET ";
			$space = "";
			foreach($info as $key => $value)
			{
				$createsql .= $space . $key . "=" . "'" . $value . "'";
				$space = ",";
			}
			$ret = array();
			$this->db->query($createsql);
			$ret['id'] = $this->db->insert_id();
			return $ret;
		}
	}
	
	public function change_plan_update()
	{
		
	}
}
?>