<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_update.php 5937 2012-02-16 03:08:02Z repheal $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class programUpdateApi extends adminUpdateBase
{
	function __construct()
	{
		parent::__construct();
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
	 */
	function update()
	{
		$this->update_day();
	}

	function update_day()
	{
		if(!$this->input['channel_id'])
		{
			$this->errorOutput("未传入频道ID");
		}
		$channel_id = intval($this->input['channel_id']);
		
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}

		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'update';
		$nodes['nodes'][$channel_id] = $channel_id;
		
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
	//	file_put_contents('../cache/dsf',123);
		if(!$this->input['dates'])
		{
			$this->errorOutput("未传入更新日期");
		}
		$dates = trim($this->input['dates']);
		
		$data = json_decode(trim($this->input['data']),true);
		if(!empty($data))
		{
			$pre_start = 0;
			$info = array();
			$length = count($data)-1;
			if($length > 0)
			{
				foreach($data as $k => $v)
				{
					$info_tmp = array();
					if(($k+1)<= $length)
					{
						$next_end = strtotime($dates .  ' ' . $data[$k+1]['start']);
						$start =  strtotime($dates . ' ' . $v['start']);
						$info_tmp = array(
							'channel_id' => $channel_id,
							'start_time' => $start,
							'toff' => $next_end - $start,
							'theme' => rawurldecode($v['theme']),
							'index_pic' => $v['index_pic'],
							'type_id' => 1,
							'weeks' => date("W",$start),
							'dates' => $dates,
							'create_time' => TIMENOW,
							'update_time' => TIMENOW,
							'ip' => hg_getip(),
							'is_show' => 1,
							'user_id' => $this->user['user_id'],
							'user_name' => $this->user['user_name'],
							'org_id' => $this->user['org_id'],
							'appid' => $this->user['appid'],
							'appname' => $this->user['display_name'],
						);						
					}
					else
					{
						$day_end = strtotime($dates . ' 23:59:59');
						$start =  strtotime($dates . ' ' . $v['start']);
						$info_tmp = array(
							'channel_id' => $channel_id,
							'start_time' => $start,
							'toff' => $day_end - $start,
							'theme' => rawurldecode($v['theme']),
							'index_pic' => $v['index_pic'],
							'type_id' => 1,
							'weeks' => date("W",$start),
							'dates' => $dates,
							'create_time' => TIMENOW,
							'update_time' => TIMENOW,
							'ip' => hg_getip(),
							'is_show' => 1,
							'user_id' => $this->user['user_id'],
							'user_name' => $this->user['user_name'],
							'org_id' => $this->user['org_id'],
							'appid' => $this->user['appid'],
							'appname' => $this->user['display_name'],
						);
					}
					if($v['id'])
					{
						unset($info_tmp['create_time'],$info_tmp['ip']);
						$info['update'][$v['id']] = $info_tmp;
					}
					else
					{
						$info['create'][] = $info_tmp;
					}
				}
			}
			else
			{
				$day_end = strtotime($dates . ' 23:59:59');
				$start =  strtotime($dates . ' ' . $data[0]['start']);
				$info_tmp = array(
					'channel_id' => $channel_id,
					'start_time' => $start,
					'toff' => $day_end - $start,
					'theme' => rawurldecode($data[0]['theme']),
					'index_pic' => $data[0]['index_pic'],
					'type_id' => 1,
					'weeks' => date("W",$start),
					'dates' => $dates,
					'create_time' => TIMENOW,
					'update_time' => TIMENOW,
					'ip' => hg_getip(),
					'is_show' => 1,
					'user_id' => $this->user['user_id'],
					'user_name' => $this->user['user_name'],
					'org_id' => $this->user['org_id'],
					'appid' => $this->user['appid'],
					'appname' => $this->user['display_name'],
				);
				if($data[0]['id'])
				{
					unset($info_tmp['create_time'],$info_tmp['ip'],$info_tmp['user_id'],$info_tmp['user_name'],$info_tmp['org_id'],$info_tmp['appid'],$info_tmp['appname']);
					$info['update'][$data[0]['id']] = $info_tmp;
				}
				else
				{
					$info['create'][] = $info_tmp;
				}
			}
			$idArr = array();
			if(!empty($info['create']))
			{			
				$sql = "INSERT INTO " . DB_PREFIX . "program SET ";
				foreach($info['create'] as $key => $value)
				{
					$sql_extra = $space = "";
					foreach($value as $k => $v)
					{
						$sql_extra .= $space . $k . "=" . "'" . $v . "'";
						$space = ",";
					}
					//echo  .'<br/>';
					$this->db->query($sql.$sql_extra);
					$value['id'] = $idArr[] = $this->db->insert_id();
					$this->addLogs('新增节目','',$value,'','',$value['theme'].$value['id']);
				}
			}
			
			if(!empty($info['update']))
			{
				$sql = "UPDATE " . DB_PREFIX . "program SET ";
				foreach($info['update'] as $key => $value)
				{
					$sql_pre = "SELECT * FROM " . DB_PREFIX . "program WHERE id=" . $key;
					$f = $this->db->query_first($sql_pre);
					if($this->user['group_type'] > MAX_ADMIN_TYPE)
					{
						switch($this->user['prms']['default_setting']['manage_other_data'])
						{
							case 1://组织内，修改者和作者是否在同一组织
							if($this->user['org_id'] != $f['org_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							break;
							case 5://全部
							break;
							case 0://只能自己修改
							if($this->user['user_id'] != $f['user_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							break;
							default:
							break;
						}			
					}
					if($f)
					{
						$sql_extra = $space = "";
						foreach($value as $k => $v)
						{
							$sql_extra .= $space . $k . "=" . "'" . $v . "'";
							$space = ",";
						}
						//echo  $sql . $sql_extra . " WHERE id=" . $key .'<br/>';
						$this->db->query($sql.$sql_extra."WHERE id=" . $key);
						$value['id'] = $idArr[] = $key;
						$this->addLogs('更新节目',$f,$value,'','',$value['theme'].$value['id']);
					}
				}
			}
			if(!empty($idArr))
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " AND id NOT IN(" . implode(',' , $idArr) . ") and dates='" . $dates . "'";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					if($this->user['group_type'] > MAX_ADMIN_TYPE)
					{
						switch($this->user['prms']['default_setting']['manage_other_data'])
						{
							case 1://组织内，修改者和作者是否在同一组织
							if($this->user['org_id'] != $row['org_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							break;
							case 5://全部
							break;
							case 0://只能自己修改
							if($this->user['user_id'] != $row['user_id'])
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							break;
							default:
							break;
						}			
					}
					$this->addLogs('删除节目','',$row,'','',$row['theme'].$row['id']);
				}
				$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " AND id NOT IN(" . implode(',' , $idArr) . ") and dates='" . $dates . "'";
				$this->db->query($sql);
			}
			$program = $this->get_program($channel_id,$dates);
			$this->addItem($program);
			$this->output();
		}
		else//删除
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " AND dates='" . $dates . "'";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					switch($this->user['prms']['default_setting']['manage_other_data'])
					{
						case 1://组织内，修改者和作者是否在同一组织
						if($this->user['org_id'] != $row['org_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						case 5://全部
						break;
						case 0://只能自己修改
						if($this->user['user_id'] != $row['user_id'])
						{
							$this->errorOutput(NO_PRIVILEGE);
						}
						break;
						default:
						break;
					}			
				}
				$this->addLogs('删除节目','',$row,'','',$row['theme'].$row['id']);
			}
			$sql = "DELETE FROM " . DB_PREFIX . "program WHERE channel_id = " . $channel_id . " and dates='" . $dates . "'";
			$this->db->query($sql);
			$program = array();
			$this->addItem($program);
			$this->output();
		}	
	}
	
	private function get_program($channel_id,$dates)
	{
		$condition = " AND channel_id=" . $channel_id;
		$condition .= " AND dates='" . $dates . "'";
		//该频道的录播记录
		$sql = "select *,FROM_UNIXTIME(start_time, '%Y-%m-%d') as start,FROM_UNIXTIME(start_time, '%U') as week_set from " . DB_PREFIX . "program ";
		$sql .= ' where 1 ' . $condition . ' ORDER BY start_time ASC';
		$q = $this->db->query($sql);
		$program = array();
		$key = '';
		$noon = strtotime($dates." 12:00");
		while($row = $this->db->fetch_array($q))
		{
			$row['start'] = date("H:i",$row['start_time']);
			
			$row['end'] = date("H:i",$row['start_time']+$row['toff']);
			if($row['start_time'] <= TIMENOW)
			{
				$row['outdate'] = 1;
			}
			else
			{
				$row['outdate'] = 0;
			}
			if($row['start_time'] <= $noon)
			{
				$row['pos'] = hg_get_pos($row['start_time'] - strtotime($dates));
				$row['slider'] = hg_get_slider($row['start_time'] - strtotime($dates));
				$key = 'am';
			}
			else
			{
				$row['pos'] = hg_get_pos($row['start_time'] - strtotime($dates." 12:00"));
				$row['slider'] = hg_get_slider($row['start_time'] - strtotime($dates." 12:00"));
				$key = 'pm';				
			}
			$row['key'] = hg_rand_num(4);
			$program[$key][] = $row;
		}
		return $program;
	}
	
	public function check_program()
	{
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
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}

		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		$nodes['_action'] = 'manage';
		$nodes['nodes'][$channel_id] = $channel_id;
		
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$dates = rawurldecode($this->input['dates']);//源日期
		if(!$dates)
		{
			$this->errorOutput("未传入更新日期");
		}

		$copy_dates = rawurldecode($this->input['copy_dates']);
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
	
	function upload_indexpic()
	{
/*
		$id = $this->input['id'] ? intval($this->input['id']) : 0;
		if(!$id)
		{
			$this->errorOutput('请传入节目id');
		}
*/
		if(!$_FILES['img'])
		{
			$this->errorOutput('没有文件');
		}
		require_once(ROOT_PATH.'lib/class/material.class.php');
		$_FILES['Filedata'] = $_FILES['img'];
		unset($_FILES['img']);
		$material_pic = new material();
		$img_info = $material_pic->addMaterial($_FILES);
		if($img_info)
		{
			$img = array(
				'id' => $img_info['id'],
				'filename' => $img_info['filename'],
				'filepath' => $img_info['filepath'],
				'dir' => $img_info['dir'],
				'filesize' => $img_info['filesize'],
				'host' => $img_info['host'],
				'imgheight' => $img_info['imgheight'],
				'imgwidth' => $img_info['imgwidth'],
				'url' => $img_info['url'],
			);
			$this->addItem($img);
			$this->output();
		}
	}

	function check_day()
	{
		/*
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
		include_once(ROOT_PATH . 'lib/class/program_record.class.php');
		$programRecord = new programRecord();
		$f = $programRecord->check_day($channel_id,$start_time,$end_time);
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
*/
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