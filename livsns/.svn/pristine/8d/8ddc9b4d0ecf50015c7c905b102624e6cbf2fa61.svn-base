<?php
require('global.php');
define('MOD_UNIQUEID','randata');
define(SCRIPT_NAME,'randata');
class randata extends adminBase
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
		$id = intval($this->input['sort_id']);
		if($id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."webapp_rank WHERE id = " . $id;
			$sort_data = $this->db->query_first($sql);
			if(empty($sort_data))
			{
				$this->errorOutput(INVALID_SORT_ID);
			}
			if($sort_data['start_time'])
			{
				$create_time = $sort_data['start_time'];
			}
			if($sort_data['duration'])
			{
				$duration = $sort_data['duration'] * 60;
			}
			$map  = array('ding'=>'up', 'cai'=>'down', 'pingfen'=>'score');
			if(!in_array($sort_data['ranktype'], array_flip($map)))
			{
				$this->errorOutput(INVALID_SORT_TYPE);
			}
			$sort_data['app_uniqueid'] = $sort_data['type'] ? explode(',', $sort_data['type']) : '';
			$sort_data['limit_num'] = $sort_data['limit_num'] ? $sort_data['limit_num'] : 20;
			$data_limit = " LIMIT 0, " . intval($sort_data['limit_num']);
			if($create_time || $duration)
			{
				$sql = 'SELECT content_type,listid, count(*) as total FROM '.DB_PREFIX.'webapp_list WHERE 1 ';
				$where = ' AND mark_name = "' . $sort_data['ranktype'] . '"';
				if($sort_data['app_uniqueid'])
				{
					$where .= ' AND content_type IN("'.implode('","', $sort_data['app_uniqueid']).'")';
				}
				if($create_time && !$duration)
				{
					$where .= ' AND create_time >= '.$create_time;
				}
				if($duration && !$create_time)
				{
					$where .= ' AND create_time >= '.(TIMENOW - $duration);
				}
				if($create_time && $duration)
				{
					$where .= ' AND create_time >= '.$create_time . ' AND create_time <= '.($create_time + $duration);
				}
				$gourpby = ' GROUP BY listid ';
				$orderby = ' ORDER BY total ' . $sort_data['sort_type'];
				$sql .= $where . $gourpby  . $orderby . $data_limit;
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$listid[] = $row['listid'];
				}
				if($listid)
				{
					$sql = 'SELECT content_id,'.$map[$sort_data['ranktype']].' FROM '.DB_PREFIX.'webapp WHERE id iN('.implode(',', $listid).')';
					$query = $this->db->query($sql);
					while($row = $this->db->fetch_array($query))
					{
						$cidArr[] = $row['content_id'];
						$conArr[$row['content_id']] = $row[$map[$sort_data['ranktype']]];
					}
				}
			}
			else
			{
				
				$sort_data['sort_type'] = $sort_data['sort_type'] && in_array($sort_data['sort_type'], array('asc', 'desc'))? $sort_data['sort_type'] : 'desc'; 
				$orderby = ' ORDER BY ' . $map[$sort_data['ranktype']] . ' ' . $sort_data['sort_type'];
				if(!$sort_data['app_uniqueid'])
				{
					$where  = '';
				}
				else
				{
					$where = '  AND app_uniqueid IN("'.implode('","', $sort_data['app_uniqueid']).'")';
				}
				$sql = "SELECT content_id, ".$map[$sort_data['ranktype']]." FROM ".DB_PREFIX."webapp WHERE 1 " . $where . $orderby . $data_limit;
				$q = $this->db->query($sql);
				$cidArr = array();
				while($row= $this->db->fetch_array($q))
				{
					$cidArr[] = $row['content_id'];
					$conArr[$row['content_id']] = $row[$map[$sort_data['ranktype']]];
				}
			}
			if($cidArr)
			{
				$cidStr = implode(',',$cidArr);
				if($cidStr)
				{
					include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
					$this->publishtcontent = new publishcontent();
					$ret = $this->publishtcontent->get_content_by_rids($cidStr);
					if(is_array($ret) && count($ret) > 0)
					{
						foreach($ret as $k => $v)
						{
							$ret[$k]['_value'] = $conArr[$v['rid']];
						}
					}
					if(is_array($ret) && count($ret))
					{
						$ret = hg_array_sort($ret,'_value',strtoupper($sort_data['sort_type']));					
						foreach($ret as $k => $v)
						{
							$this->addItem($v);
						}
					}													
				}
			}
		}
		$this->output();		
	}
}
require(ROOT_PATH . 'excute.php');
?>
