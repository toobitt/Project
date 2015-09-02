<?php

class  vodShow extends InitFrm
{
	private  $_offset;
	private  $_count;
	private  $condition;
    public function __construct($_offset,$_count)
	{
		parent::__construct();
		$this->_offset = $_offset;
		$this->_count  = $_count;
		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$return = array();
	    $offset = $this->_offset?$this->_offset:0;
		$count  = $this->_count?$this->_count:15;
		$limit = " limit {$offset}, {$count}";
	
		$sql = "SELECT v.*, vs.sort_name FROM ".DB_PREFIX."vodinfo v LEFT JOIN ".DB_PREFIX."vod_sort vs ON v.vod_sort_id = vs.id WHERE 1 ". $this->_getCondition($condition) ."  ORDER BY v.video_order_id DESC, v.id DESC ".$limit;
		$q  = $this->db->query($sql);
		$this->setXmlNode('vod','item');
		while($r = $this->db->fetch_array($q))
		{
			$r['vod_sort_color'] = $this->settings['video_upload_type_attr'][intval($r['vod_leixing'])]['color'];
			$r['vod_leixing'] = $this->settings['video_upload_type'][$r['vod_leixing']];
			
			if($r['sort_name'])
			{
				$r['vod_sort_id'] = $r['sort_name'];
			}
			else
			{
				$r['vod_sort_id'] = $r['vod_leixing'];
			}
			
			$collects = unserialize($r['collects']);
			if(!$collects)
			{
				$r['collects'] = "";
			}
			
			$r['img'] = SOURCE_THUMB_PATH.$r['img']."?".TIMENOW;
			
			$rgb = $r['bitrate']/100;
			
			if($rgb < 10)
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
			}
			else 
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][9];
			}
		
			if($r['starttime'])
			{
				$r['starttime'] = '('.date('Y-m-d',$r['starttime']).')';
			}
			else
			{
				$r['starttime'] = '';
			}
			
			$r['start'] = $r['start'] + 1;
			$r['etime'] = intval($r['duration']) + intval($r['start']);
			$r['duration'] = time_format($r['duration']);
			$r['status'] = $this->settings['video_upload_status'][$r['status']];
			$r['create_time'] = date('Y-m-d h:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i',$r['update_time']);
			$return[] = $r;
		}
		return $return;
	}
	
	public function _setCondition($condition)
	{
		$this->condition = $condition;
	}
	
	public function _getCondition($condition)
	{
		return $this->condition;
	}

}

?>