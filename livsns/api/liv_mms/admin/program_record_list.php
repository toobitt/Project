<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_list.php 7586 2012-07-05 09:40:56Z repheal $
***************************************************************************/
require('global.php');
class programRecordListApi extends BaseFrm
{
	private $weeks;
	private $years;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	private function getDayOfWeeks($year,$week,$type = 0)
	{
		if($type)
		{
			return date('Y.m.d',strtotime('+' . ($week-1) . ' week 2 days',strtotime($year . '-01-01')));
		}
		else
		{
			return date('m.d',strtotime('+' . $week . ' week 1 days',strtotime($year . '-01-01')));
		}
	} 
	

	/**
	 * 显示录播节目单
	 */
	function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = " LIMIT " . $offset . " , " . $count;
		$sql = "select p.*,v.sort_name from " . DB_PREFIX . "program_record_log p left join " . DB_PREFIX . "vod_sort v on p.item = v.id ";
		$sql .= " where 1 " . $condition . " ORDER BY id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$state = array(0=>'未收录', 1=>'收录成功', 2=>'收录失败');
		while($row = $this->db->fetch_array($q))
		{
		//	$row['dates'] = $row['years'].'-'.$row['months'].'-'.$row['days'];
			$dates = date('m-d H:i:s', $row['create_time']);
			$channel_id = $row['channel_id'];
			$start_time = $row['start_time'];
	
			$row['end_time'] = $row['toff'] + $start_time;
			$mins = floor($row['toff']/60);
			$sen = $row['toff'] - $mins*60;
			$row['toff_decode'] = ($mins?$mins."'":'').($sen?$sen."''":'');

			$row['program_name'] = $row['program_name'] ? $row['program_name'] : '精彩节目';
			//$row['subtopic'] = $f['subtopic'];
			$row['channel_id'] = $channel_id;
			$row['dates'] = $dates;
			$row['w'] = date('w',strtotime($start_time));
			$row['sortname'] = $row['sortname']?$row['sortname']:'';
		//	$info[] = $row;
			$this->addItem($row);
		}

		
		$this->output();
	}
	
	/**
	 * 获取单条信息
	 */
	public function detail()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$condition = " ORDER BY id DESC LIMIT 1";
		}
		else 
		{
			$condition = " WHERE id IN(" . $id . ")";
		}
		$sql ="SELECT * FROM " . DB_PREFIX . "program_record_log " . $condition;
		$row = $this->db->query_first($sql);
		$this->setXmlNode('program_record', 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['end_time'] = date('Y-m-d H:i:s' , ($row['start_time'] + $row['toff']));
			$row['start_time'] = date('Y-m-d H:i:s' , $row['start_time']);
			$row['week_day'] = $row['week_day'] ? unserialize($row['week_day']) : array();
		
			$this->addItem($row);
			$this->output();
		}
		else 
		{
			$this->errorOutput('录播节目不存在');
		}
	}

		
	/**
	 * Enter description here ...
	 */
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "program_record_log WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
	
	/**
	 * 获取条件
	 */
	private function get_condition()
	{
		$condition = '';

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  start_time > '".$yesterday."' AND start_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  start_time > '".$today."' AND start_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  start_time > '".$last_threeday."' AND start_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  start_time > '".$last_sevenday."' AND start_time < '".$tomorrow."'";
					break;
				case 'other'://所有时间段
					$start = urldecode($this->input['start_time']) ? strtotime(urldecode($this->input['start_time'])) : 0;
					if($start)
					{
						$condition .= " AND start_time > '" . $start . "'";
					}
					$end = urldecode($this->input['end_time']) ? strtotime(urldecode($this->input['end_time'])) : 0;
					if($end)
					{
						$condition .= " AND start_time < '" . $end . "'";
					}
					break;
				default:
					break;
			}
		}
		if($this->input['channel_id']>0)
		{
			$condition .= ' AND channel_id=' . $this->input['channel_id'];
		}
		if($this->input['record_id']>0)
		{
			$condition .= ' AND record_id=' . $this->input['record_id'];
		}
		return $condition;
	}
}

$out = new programRecordListApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>