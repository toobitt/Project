<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_update.php 5937 2012-02-16 03:08:02Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','old_live');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class programUpdateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
		if($this->mNeedCheckIn && !$this->prms['program'])
		{
			$this->errorOutput(NO_OPRATION_PRIVILEGE);
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{
		
		
	}
	/**
	 * 更新节目单数据
	 * @param $channel_id 频道ID  		not null
	 * @param $start_time 开始时间  		not null
	 * @param $toff 时长					not null
	 * @param $theme 主题				not null 
	 * @param $subtopic 副主题			null
	 * @param $type_id	节目类型			not null
	 * @param $weeks 所属周				not null
	 * @param $dates 日期					not null
	 * @param $describes 描述				null
	 * return $ret 节目单的信息 
	 */
	function update()
	{
		
		
	}

	function update_day()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("未传入频道ID");
		}
		
		if(!$this->input['dates'])
		{
			$this->errorOutput("未传入更新日期");
		}
		/*
$sql = "select r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "program_record_relation r on p.id = r.record_id where r.channel_id=" . $this->input['channel_id'] . " and r.week_num=".date('N',strtotime($this->input['dates']));
		$q = $this->db->query($sql);
				$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$record[] = $r['start_time'] . '-' . $r['end_time'] . '-' . $r['item'];
		}
*/
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$obj_live = new live();
		
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);

		$arr = array(
			'color' => $this->input['color'],
			'checke' => $this->input['checke'],
			'start_time' => $this->input['start_time'],
			'theme' => $this->input['theme'],
			'end_time' => $this->input['end_time'],
			'item' => $this->input['item'],
			'new' => $this->input['new'],
			'plan' => $this->input['plan'],
			'plan_source' => $this->input['plan_source'],
			'program_source' => $this->input['program_source'],
		);
		
		foreach($arr as $key => $value)
		{
			if(empty($value))
			{
				unset($arr[$key]);
			}
		}
		$dates = urldecode($this->input['dates']);
		if(empty($arr))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " and dates='" . $dates . "'";
			$q = $this->db->query($sql);
			$tmp_record_id = $tmp_space = '';
			while($row = $this->db->fetch_array($q))
			{
				if($row['record_id'])
				{
					$tmp_record_id .= $tmp_space . $row['record_id'];
					$tmp_space = ',';
				}				
			}
			
			if($tmp_record_id)//删除的节目单中假如有录制，那对应的必须删除录制
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $tmp_record_id .")";
				$q = $this->db->query($sql);
				$tmp_record_id_plan = $tmp_record_id_program = 0;
				$space_plan = $space_program = '';
				while($row = $this->db->fetch_array($q))
				{
					if($row['plan_id'])
					{
						$tmp_record_id_plan .= $space_plan . $row['id'];  
						$space_plan = ',';
					}
					else
					{
						$tmp_record_id_program .= $space_program . $row['id'];  
						$space_program = ',';
					}
				}
				if($tmp_record_id_program)
				{
					$obj_live->delete_record($tmp_record_id_program);				
				}
				if($tmp_record_id_plan)
				{
					$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=0 WHERE id IN(" . $tmp_record_id_plan . ")";
					$this->db->query($sql);
				}
			}
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " and dates='" . $dates . "'";
			$this->db->query($sql);
			
			$program_plan = $this->getPlan($this->input['channel_id'],$dates);
			$program = array();
			$start = strtotime($dates." 00:00:00");
			$end = strtotime($dates." 23:59:59");
			if(empty($program_plan))
			{
				$program[] = $this->getInfo($start,strtotime($dates." 08:00:00"),$dates,$this->input['channel_id'],1,0);
				$program[] = $this->getInfo(strtotime($dates." 08:00:00"),$end,$dates,$this->input['channel_id']);
			}
			else
			{
				$com_time = 0;
				foreach($program_plan as $k => $v)
				{
					if(!$com_time && $v['start_time'] > $start)//头
					{
						$program[] = $this->getInfo($start,$v['start_time'],$dates,$this->input['channel_id']);
					}

					if($com_time && $com_time != $v['start_time'])//中
					{
						$program[] = $this->getInfo($com_time,$v['start_time'],$dates,$this->input['channel_id']); 
					}
					$v['start'] = date("H:i",$v['start_time']);
					$v['end'] = date("H:i",$v['start_time']+$v['toff']);
					if($v['start_time'] <= TIMENOW)
					{
						$v['outdate'] = 1;
					}
					else
					{
						$v['outdate'] = 0;
					}
					$com_time = $v['start_time']+$v['toff'];
					$program[] = $v;
				}
				if($com_time && $com_time < $end)//中
				{
					$program[] = $this->getInfo($com_time,$end,$dates,$this->input['channel_id']);
				}
			}
		//	file_put_contents('../cache/2.php',var_export($program,1));
			$this->addItem($program);
			$this->output();
		}
		$prev_end = 0;
		foreach($arr['start_time'] as $k => $v)
		{
			$start_this = strtotime(urldecode($dates." ".$v));
			$end_this = strtotime(urldecode($dates . " " . $arr['end_time'][$k]));
			if($start_this >= $end_this)
			{
				$this->errorOutput($v . '~' . urldecode($arr['end_time'][$k])."的开始时间大于等于结束时间");
			}
			if($prev_end && $prev_end > $start_this)
			{
				$this->errorOutput($start_this."的上一个节目的时间有误");
			}
			$prev_end = $end_this;
		}
		
		$ids = $spa = '';
		foreach($arr['color'] as $key => $value)
		{
			$pid = $key;
			if($arr['checke'][$pid])
			{
				$info = array(
						'id' => $pid,
						'color' => urldecode($value),
						'start_time' => strtotime(urldecode($dates." ".$arr['start_time'][$key])),
						'theme' => rawurldecode($arr['theme'][$key]),
						'toff' => strtotime(urldecode($dates." ".$arr['end_time'][$key])) - strtotime(urldecode($dates." ".$arr['start_time'][$key])),
						'item' => intval($arr['item'][$key]),
						'new' => urldecode($arr['new'][$key]),
						'plan' => intval($arr['plan'][$key]),
						'plan_source' => intval($arr['plan_source'][$key]),
						'program_source' => intval($arr['program_source'][$key]),
					);
					if($info['new'])//create
					{
						$creates = array(
							'channel_id' => $this->input['channel_id'],
							'start_time' => $info['start_time'],
							'toff' => $info['toff'],
							'theme' => rawurldecode($arr['theme'][$key]),
							'type_id' => 1,
							'weeks' => date("W",$info['start_time']),
							'dates' => date("Y-m-d",$info['start_time']),
							'create_time' => TIMENOW,
							'update_time' => TIMENOW,
							'ip' => hg_getip(),
							'is_show' => 1
						);
						$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
						$space = "";
						foreach($creates as $k => $v)
						{
							$sql .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
						$this->db->query($sql);
						$info['id'] = $this->db->insert_id();
						$pid = $info['id'];
						if($info['item'] > 0)//说明需要插入录制
						{
							$record_create = array(
								'title' => $info['theme'],
								'channel_id' => $this->input['channel_id'],
								'program_id' => $info['id'],
								'start_time' => $info['start_time'],
								'toff' => $info['toff'],
								'item' => $info['item'],
								'create_time' => TIMENOW,
								'update_time' => TIMENOW,
								'ip' => hg_getip(),
							);
							
							if($record_create['start_time'] < TIMENOW)
							{
								$record_create['is_out'] = 1;
							}
							if($info['plan'])//新插入录制原本是计划
							{
								$sql = "SELECT id FROM " . DB_PREFIX . "program_record WHERE plan_id=" . $info['plan'];
								$tmp_plan_record = $this->db->query_first($sql);
								if($info['program_source'] == 2)
								{
									$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
									$space = "";
									foreach($record_create as $k => $v)
									{
										$createsql .= $space . $k . "=" . "'" . $v . "'";
										$space = ",";
									}
									$this->db->query($createsql);
									$record_id = $this->db->insert_id();
									$this->insert_relation($record_create['channel_id'],$record_id,$record_create['start_time'],$record_create['toff'],0);	
									$sql = "UPDATE " . DB_PREFIX . "program SET record_id='" . $record_id . "' WHERE id=" . $info['id'];
									$this->db->query($sql);
								}
								else//此计划的录制存在，更新关联
								{
									if(!empty($tmp_plan_record))//存在，那根据plan_source判断是新建，还是什么都不做（更新）
									{
										$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=" . $info['id'] . " WHERE plan_id=" . $info['plan'];
										$this->db->query($sql);
										$sql = "UPDATE " . DB_PREFIX . "program SET record_id='" . $tmp_plan_record['id'] . "' WHERE id=" . $info['id'];
										$this->db->query($sql);
									}
									else//说明此计划的录制不存在，更新或者什么都不做
									{
										
									}
								}
							}
							else
							{//不是计划，而且有录制，说明肯定创建单天录制
								$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
								$space = "";
								foreach($record_create as $k => $v)
								{
									$createsql .= $space . $k . "=" . "'" . $v . "'";
									$space = ",";
								}
								$this->db->query($createsql);
								$record_id = $this->db->insert_id();
								$this->insert_relation($record_create['channel_id'],$record_id,$record_create['start_time'],$record_create['toff'],0);
								$sql = "UPDATE " . DB_PREFIX . "program SET record_id='" . $record_id . "' WHERE id=" . $info['id'];
								$this->db->query($sql);
							}								
						}
					}//update
					else
					{
						$sql = "UPDATE " . DB_PREFIX . "program SET color='" . $info['color'] . "',start_time=" . $info['start_time'] . ",theme='" . $info['theme'] . "',toff=" . $info['toff'] . " where id=" . $info['id'];
						$this->db->query($sql);
						if($info['item']>0)//更新而且有录制
						{
							$record_create = array(
								'title' => $info['theme'],
								'channel_id' => $this->input['channel_id'],
								'program_id' => $info['id'],
								'start_time' => $info['start_time'],
								'toff' => $info['toff'],
								'item' => $info['item'],
								'create_time' => TIMENOW,
								'update_time' => TIMENOW,
								'ip' => hg_getip(),
							);
							
							$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE program_id=" . $info['id'];
							$tmp_senc = $this->db->query_first($sql);
							if(empty($tmp_senc))//原来的不是录制，现在是了
							{
								$createsql = "INSERT INTO " . DB_PREFIX . "program_record SET ";
								$space = "";
								foreach($record_create as $k => $v)
								{
									$createsql .= $space . $k . "=" . "'" . $v . "'";
									$space = ",";
								}
								$this->db->query($createsql);
								$record_id = $this->db->insert_id();
								$this->insert_relation($record_create['channel_id'],$record_id,$record_create['start_time'],$record_create['toff'],0);
								$sql = "UPDATE " . DB_PREFIX . "program SET record_id='" . $record_id . "' WHERE id=" . $info['id'];
								$this->db->query($sql);
							}
							else//原来的是录制，现在依旧是，直接更新
							{
								$record_update = array(
										'title' => $info['theme'],
										'start_time' => $info['start_time'],
										'toff' => $info['toff'],
										'item' => $info['item'],
										'update_time' => TIMENOW,
								);
								$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $tmp_senc['conid'];
								$sen = $this->db->query_first($sql);
								if($sen)
								{
									$this->curl->mPostContentType('string');
									$this->curl->setSubmitType('get');
									$this->curl->setReturnFormat('json');
									$this->curl->initPostData();
									if(!defined('IS_WOZA') || !IS_WOZA)
									{
										$this->curl->addRequestData('action', 'DELETE');
									}
									else
									{
										$this->curl->addRequestData('action', 'delete');				
									}
									$this->curl->addRequestData('id', $sen['conid']);
									$record_xml = $this->curl->request('');
									$record_array = xml2Array($record_xml);
									if($record_array['result'])
									{
										$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id IN (" . $sen['log_id'] . ")";
										$this->db->query($sql);
										$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE id=" . $sen['id'];
										$this->db->query($sql);
									}
								}
								
								if(empty($tmp_senc['week_day']))//原来的单天录制
								{
									if($record_update['start_time'] <= TIMENOW)//开始时间小于当前时间就说明超时
									{
									//相当于重置录制
										$record_update['is_out'] = 1;
									}
									$updatesql = "UPDATE " . DB_PREFIX . "program_record SET ";
									$space = "";
									foreach($record_update as $k => $v)
									{
										$updatesql .= $space . $k . "=" . "'" . $v . "'";
										$space = ",";
									}
									$updatesql .= " WHERE program_id=" . $info['id'];
									$this->db->query($updatesql);
								}
								else//多天录制，更新，要判断当前的
								{
									if($record_update['start_time'] <= TIMENOW)//新的时间小于当前时间，就必须要到第二天
									{
										$week_now = date('N',TIMENOW);
										$week_num = unserialize($tmp_senc['week_day']);
										if(in_array($week_now,$week_num))
										{
											$dates = date('Y-m-d',TIMENOW);
										}
										$i = 0;
										$next_day = $next_week_day = 0;
										foreach($week_num as $k => $v)
										{
											if(!$i && $v > $week_now)
											{
												$next_day = $v;
												$i = 1;
											}
											if(!$k)
											{
												$next_week_day = $v;
											}
										}
										if(!$next_day)
										{
											$next_day = $next_week_day;
										}
										$next_week = ($next_day - $week_now)>0?($next_day - $week_now):($next_day - $week_now + 7);
										$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
										$record_update['start_time'] = strtotime($dates . ' ' . date('H:i:s',$tmp_senc['start_time']));
									}
									
									$updatesql = "UPDATE " . DB_PREFIX . "program_record SET ";
									$space = "";
									foreach($record_update as $k => $v)
									{
										$updatesql .= $space . $k . "=" . "'" . $v . "'";
										$space = ",";
									}
									$updatesql .= " WHERE program_id=" . $info['id'];
									$this->db->query($updatesql);
								}
							}
						}
						else
						{
							$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE program_id=" . $info['id'];
							$tmp_senc = $this->db->query_first($sql);
							if(empty($tmp_senc))//原来的不是录制，现在也不是
							{
								//对录制不做任何操作
							}
							else//原来的是录制，现在的不是，删除
							{
								if($tmp_senc['plan_id'])//是有计划生成的录制,清空录制中的节目单ID
								{
									$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=0 WHERE id=" . $tmp_senc['id'];
									$this->db->query($sql);
								}
								else//彻底删除这条录制
								{
									$obj_live->delete_record($tmp_senc['id']);
								}
								//原本的节目中的录制ID必须更新为0
								$sql = "UPDATE " . DB_PREFIX . "program SET record_id=0 WHERE id=" . $info['id'];
								$this->db->query($sql);
							}
						}
					}
			}
			$ids .= $spa . $pid;
			$spa = ',';
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " AND id NOT IN(" . $ids . ") and dates='" . $dates . "'";
		$q = $this->db->query($sql);
		$tmp_record_id = $tmp_space = '';
		while($row = $this->db->fetch_array($q))
		{
			if($row['record_id'])
			{
				$tmp_record_id .= $tmp_space . $row['record_id'];
				$tmp_space = ',';
			}	
		}

		if($tmp_record_id)//删除的节目单中假如有录制，那对应的必须删除录制
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $tmp_record_id .")";
			$q = $this->db->query($sql);
			$tmp_record_id_plan = $tmp_record_id_program = 0;
			$space_plan = $space_program = '';
			while($row = $this->db->fetch_array($q))
			{
				if($row['plan_id'])
				{
					$tmp_record_id_plan .= $space_plan . $row['id'];  
					$space_plan = ',';
				}
				else
				{
					$tmp_record_id_program .= $space_program . $row['id'];  
					$space_program = ',';
				}
			}
			if($tmp_record_id_program)
			{
				$obj_live->delete_record($tmp_record_id_program);				
			}
			if($tmp_record_id_plan)
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record SET program_id=0 WHERE id IN(" . $tmp_record_id_plan . ")";
				$this->db->query($sql);
			}
		}
		
		$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $this->input['channel_id'] . " AND id NOT IN(" . $ids . ") and dates='" . $dates . "'";
		$this->db->query($sql);
		$program = $this->get_program($this->input['channel_id'],$dates);
	//	file_put_contents('../cache/1.php',var_export($program,1));
		$this->addItem($program);
		$this->output();
	}
	
	private function get_program($channel_id,$dates)
	{
		$condition = " AND channel_id=" . $channel_id;
		$condition .= " AND dates='" . $dates . "'";
		//该频道的录播记录
		$sql = "select p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item from " . DB_PREFIX . "program_record p left join " . DB_PREFIX . "program_record_relation r on p.id = r.record_id where r.channel_id=" . $channel_id . " and r.week_num=".date('N',strtotime($dates));
		$q = $this->db->query($sql);
		$record = array();
		while($r = $this->db->fetch_array($q))
		{
			$record[$r['id']] = $r['item'];
		}
	
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 ' . $condition . ' ORDER BY start_time ASC';
	//	file_put_contents('../cache/3.php',$sql);
		$q = $this->db->query($sql);
		$program = array();
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		$program_plan = $this->getPlan($channel_id,$dates);
	//	file_put_contents('../cache/4.php',var_export($program_plan,1));
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$plan = $this->verify_plan($program_plan,$start,$row['start_time'],$channel_id);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($start,$row['start_time'],$dates,$channel_id);
				}
			}
			if($com_time && $com_time != $row['start_time'])//中
			{
				$plan = $this->verify_plan($program_plan,$com_time,$row['start_time'],$channel_id);
				if($plan)
				{
					foreach($plan as $k => $v)
					{
						$program[] = $v;
					}
				}
				else
				{
					$program[] = $this->getInfo($com_time,$row['start_time'],$dates,$channel_id); 
				}
			}
			$row['start'] = date("H:i",$row['start_time']);
			$row['end'] = date("H:i",$row['start_time']+$row['toff']);
			//$row['item'] = $record[$row['id']]?$record[$row['id']]:0;
			$com_time = $row['start_time']+$row['toff'];
			$record_verify = $row['record_id'];
			$row['item'] = $record[$record_verify]?$record[$record_verify]:0;
			if(($row['start_time']) <= TIMENOW)
			{
				$row['outdate'] = 1;
			}
			else
			{
				$row['outdate'] = 0;
			}
			$program[] = $row;
		}
		if($com_time && $com_time < $end)//中
		{
			$plan = $this->verify_plan($program_plan,$com_time,$end,$channel_id);
			if($plan)
			{
				foreach($plan as $k => $v)
				{
					$program[] = $v;
				}
			}
			else
			{
				$program[] = $this->getInfo($com_time,$end,$dates,$channel_id);
			}
		}
		return $program;
	}

	private function insert_relation($channel_id,$record_id,$start_time,$toff,$week_day)
	{
		$start = date("H:i:s",$start_time);
		$end = date("H:i:s",$start_time+$toff);

		$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id=" . $record_id;
		$this->db->query($sql);
		if(!$week_day)
		{
			$week_num = date('N',$start_time);

			$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $week_num . ",num=0";
			$this->db->query($sql);
		}
		else
		{
			$week_day = unserialize($week_day);
			foreach($week_day as $k => $v)
			{
				$sql = "INSERT INTO " . DB_PREFIX . "program_record_relation SET record_id=" . $record_id . " ,channel_id=" . $channel_id . ", start_time='" . $start . "', end_time='" . $end . "', week_num=" . $v . ",num=1";
				$this->db->query($sql);
			}
		}
	}

	public function check_program()
	{
		$id = $this->input['id'] ?  $this->input['id'] : 0;
		//验证节目计划
		if(empty($id))
		{
			$this->errorOutput("未传入节目单ID");
		}
		$data = array();
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE program_id=" . $id;
		
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$data['record_result'] = 0;
		}
		else
		{
			$data['record_result'] = 1;
		}
		$data['id'] = $id;
		$data['type'] = $this->input['type'];
		$this->addItem($data);
		$this->output();
	}

	public function add_dom_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}

		$times = $this->input['times'];//验证时间
		if(!$times)
		{
			$this->errorOutput("参数不完整");
		}

		$dates = date("Y-m-d",$times);
		$type = $this->input['type'] ? 1:0;//0---表示开始，往上添加 1---表示结束，往下添加
		
		$sql = "select * from " . DB_PREFIX . "program where channel_id=" . $channel_id . " and dates='" . $dates . "'  ORDER BY start_time ASC";
		$q = $this->db->query($sql);
		$start = strtotime($dates." 00:00:00");
		$end = strtotime($dates." 23:59:59");
		$com_time = 0;
		$queue = array();
		while($row = $this->db->fetch_array($q))
		{
			if(!$com_time && $row['start_time'] > $start)//头
			{
				$queue[] = array(
						'start' => $start,
						'end' => $row['start_time'],
						'starts' => date("H:i:s",$start),
						'ends' => date("H:i:s",$row['start_time']),
					);
			}
			if($com_time && $com_time != $row['start_time'])//中
			{
				$queue[] = array(
						'start' => $com_time,
						'end' => $row['start_time'],
						'starts' => date("H:i:s",$com_time),
						'ends' => date("H:i:s",$row['start_time']),
					);
			}
			$com_time = $row['start_time']+$row['toff'];
			$queue[] = array(
						'start' => $row['start_time'],
						'end'=> $row['start_time']+$row['toff'],
						'starts' => date("H:i:s",$row['start_time']),
						'ends' => date("H:i:s",$row['start_time']+$row['toff']),
					);
		}
		if($com_time < $end)//中
		{
			$queue[] = array(
						'start' => $com_time,
						'end' => $end,
						'starts'=>date("H:i:s",$com_time),
						'ends'=>date("H:i:s",$end),
					);
		}
//echo date("Y-m-d H:i:s",$times);
//hg_pre($queue,1);
		$start = $end = $range_start = $range_end = 0;
		foreach($queue as $key => $value)
		{
			if($times > $value['start'] && $times < $value['end'])
			{
				$range_start = $value['start'];
				$range_end = $value['end'];
				if($type)
				{
					$start = $times;
					$end = $value['end'];
				}
				else
				{
					$start = $value['start'];
					$end = $times;
				}
			}
		}
		if(!$start || !$end)
		{
			echo "填写时间在" . $dates . "的" . date("H:i",$range_start) . "~" . date("H:i",$range_end) . "之间";
			$this->errorOutput("填写时间在" . $dates . "的" . date("H:i",$range_start) . "~" . date("H:i",$range_end) . "之间");
		}
		$program[] = $this->getInfo($start,$end,$dates,$channel_id);
		$this->addItem($program);
		$this->output();
	}

	public function check_copy()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$sql = "select * from " . DB_PREFIX . "program where channel_id=" . $channel_id . " and dates='" . $dates . "'";
		$f = $this->db->query_first($sql);
		
		$tip = array('ret'=>1,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		if(!$f['id'])
		{
			$tip = array('ret'=>0,'id'=>$this->input['id'],'show_id'=>$this->input['show_id']);
		}
		$this->addItem($tip);
		$this->output();
	}

	public function copy_day()
	{
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		
		$dates = urldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$copy_dates = urldecode($this->input['copy_dates']);
		if(!$copy_dates)
		{
			$this->errorOutput("未传入更新日期");
		}
		
		$diff = strtotime($copy_dates) - strtotime($dates); //相差时间
		
		$sql = "DELETE FROM  " . DB_PREFIX . "program WHERE channel_id=" . $channel_id ." AND dates='" . $copy_dates . "'";
		$this->db->query($sql);
		
		$sql ="INSERT  INTO  " . DB_PREFIX . "program (channel_id,start_time, toff, theme, subtopic, type_id, dates, weeks, describes, create_time, update_time, ip, is_show) SELECT channel_id,start_time+" . $diff . ", toff, theme, subtopic, type_id, '" . $copy_dates . "', " . date('N',strtotime($copy_dates)) . ", describes, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), ip, is_show FROM " . DB_PREFIX . "program ";
		
		$sql .= "WHERE dates='" . $dates ."' AND channel_id=" . $channel_id;
		$this->db->query($sql);
		$tip = array('ret'=>1);
		$this->addItem($tip);
		$this->output();

	}
	
	private function getPlan($channel_id,$dates)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "program_plan p LEFT JOIN " . DB_PREFIX . "program_plan_relation r ON r.plan_id=p.id WHERE 1 and p.channel_id=" . $channel_id . " AND r.week_num=" . date("N",strtotime($dates)) . " ORDER BY p.start_time ASC";
		$q = $this->db->query($sql);
		$program_plan = array();
		while($r = $this->db->fetch_array($q))
		{
			$program_plan[] = array(
					'id' => hg_rand_num(10),	
					'channel_id' => $r['channel_id'],
					'start_time' => strtotime($dates . " " . date("H:i:s",$r['start_time'])),	
					'toff' =>  $r['toff'],	
					'theme' => $r['program_name'],	
					'subtopic' => '',	
					'type_id' => 1,	
					'dates' => $dates,	
					'weeks' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'describes' => '',	
					'create_time' => TIMENOW,	
					'update_time' => TIMENOW,	
					'ip' => hg_getip(),	
					'is_show' => 1,	
					'color' => '#537ABF,#E5EEFF',	
					'start' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'end' => date("H:i",strtotime($dates . " " . date("H:i:s",$r['start_time'])) + $r['toff']),	
					'week_set' => date('W',strtotime($dates . " " . date("H:i:s",$r['start_time']))),	
					'item' => $r['item'],
					'new' => 0,
					'outdate' => (strtotime($dates . " " . date("H:i:s",$r['start_time']))) <= TIMENOW ? 1:0,
					'is_plan' => $r['id'],
				);
		}
		return $program_plan;
	}

	private function verify_plan($plan,$start_time,$end_time,$channel_id)
	{
		$program_plan = array();
		if(!empty($plan))
		{
			foreach($plan as $k => $v)
			{
				if($v['start_time'] >= $start_time && ($v['start_time']+$v['toff'])<= $end_time)
				{
					$program_plan[] = $v;
				}
			}
			if(empty($program_plan))
			{
				return false;
			}
			$program = array();
			$start = $start_time;
			$end = $end_time;
			$dates = date("Y-m-d",$start_time);
			$com_time = 0;
			foreach($program_plan as $k => $v)
			{
				if(!$com_time && $v['start_time'] > $start)//头
				{
					$program[] = $this->getInfo($start,$v['start_time'],$dates,$channel_id);
				}

				if($com_time && $com_time != $v['start_time'])//中
				{
					$program[] = $this->getInfo($com_time,$v['start_time'],$dates,$channel_id); 
				}
				$v['start'] = date("H:i",$v['start_time']);
				$v['end'] = date("H:i",$v['start_time']+$v['toff']);
				if($v['start_time'] <= TIMENOW)
				{
					$v['outdate'] = 1;
				}
				else
				{
					$v['outdate'] = 0;
				}
				$com_time = $v['start_time']+$v['toff'];
				$program[] = $v;
			}
			if($com_time && $com_time < $end)//中
			{
				$program[] = $this->getInfo($com_time,$end,$dates,$channel_id);
			}
			if(empty($program_plan))
			{
				return false;
			}
			return $program;
		}
		else
		{
			return false;
		}
	}

	private function getInfo($start,$end,$dates,$channel_id,$type=0,$new=1)
	{
		$info = array(
				'id' => hg_rand_num(10),	
				'channel_id' => $channel_id,	
				'start_time' => $start,	
				'toff' =>  $end-$start,	
				'theme' => '精彩节目',	
				'subtopic' => '',	
				'type_id' => 1,	
				'dates' => $dates,	
				'weeks' => date('W',$start),	
				'describes' => '',	
				'create_time' => TIMENOW,	
				'update_time' => TIMENOW,	
				'ip' => hg_getip(),	
				'is_show' => 1,	
				'color' => '#DF6564,#FEF2F2',	
				'start' => date("H:i",$start),	
				'end' => date("H:i",$end),	
				'week_set' => date('W',$start),	
				'item' => 0,
				'new' => $new,
			);
		
		if($start <= TIMENOW)
		{
			$info['outdate'] = 1;
		}
		else
		{
			$info['outdate'] = 0;
		}
		if($type)
		{
			$info['space'] = 1;
		}
		return $info;
	}

	function check_day()
	{
		$id = $this->input['id'];
		$channel_id = $this->input['channel_id'];
		if(!$channel_id)
		{
			$this->errorOutput("未传入频道ID");
		}
		$start_time = $this->input['start_time'];
		if(!$start_time)
		{
			$this->errorOutput("未传入开始时间");
		}
		$end_time = $this->input['end_time'];
		if(!$end_time)
		{
			$this->errorOutput("未传入结束时间");
		}

		//该频道的录播记录
		$sql = "SELECT p.id,r.start_time,r.end_time,r.channel_id,r.week_num,p.item FROM " . DB_PREFIX . "program_record p LEFT JOIN " . DB_PREFIX . "program_record_relation r ON p.id = r.record_id WHERE r.channel_id=" . $channel_id . " AND r.week_num=" . date('N',strtotime($dates)) . " AND r.start_time='" . date('H:i:s',$start_time) . "' AND r.end_time='" . date('H:i:s',$end_time) . "'";	
		$f = $this->db->query_first($sql);

		if($f['id'])
		{
			$this->addItem(array('tips'=>1));
		}
		else
		{
			include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
			$livmedia = new livmedia();
			$sort_name = $livmedia->getAutoItem();
			$this->addItem($sort_name);
		}
		$this->output();
	}
	
	public function delete()
	{
		
	}
	public function audit()
	{
		
	}
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	
}
$out = new programUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>