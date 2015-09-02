<?php
/***************************************************************************
* $Id: live.class.php 17481 2013-02-21 09:36:46Z gaoyuan $
***************************************************************************/
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordLog extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$data_limit)
	{
		$sql = "SELECT a.*,q.conid FROM  " . DB_PREFIX . "program_record_log a LEFT JOIN " . DB_PREFIX . "program_queue q ON a.id=q.log_id WHERE 1 " . $condition . " ORDER BY id DESC " . $data_limit;
		$q = $this->db->query($sql);
		$info = array();
		$ids = $space = '';
		while($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
			$ids .= $space . $row['record_id'];
			$space = ',';
		}
		if(!empty($info))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
			$q = $this->db->query($sql);
			$server_config = array();
			while($row = $this->db->fetch_array($q))
			{
				$server_config[$row['id']] = $row;
			}
			if($ids)
			{
				$sql = "SELECT id,server_id FROM " . DB_PREFIX . "program_record WHERE id IN(" . $ids . ")";
				$q = $this->db->query($sql);
				$record_server = array();
				while($row = $this->db->fetch_array($q))
				{
					$record_server[$row['id']] = $row['server_id'];
				}
			}
				
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$data = array();
			foreach($info as $k => $v)
			{
				if(empty($server_config[$record_server[$v['record_id']]]))
				{
					$obj_curl = new curl($this->settings['mms']['record_server']['host'], $this->settings['mms']['record_server']['dir']);
				}
				else
				{
					$obj_curl = new curl($server_config[$record_server[$v['record_id']]]['host'] . ':' . $server_config[$record_server[$v['record_id']]]['port'], $server_config[$record_server[$v['record_id']]]['dir']);
				}		
				$ret = array();
				$ret[$k]['id'] = $v['id'];
				$ret[$k]['channel_id'] = $v['channel_id'];
				$ret[$k]['record_id'] = $v['record_id'];
				$ret[$k]['dates'] = date('Y-m-d',$v['start_time']);
				$pre_title = date('Y-m-d H:i:s',$v['start_time']) . '~~~' . date('Y-m-d H:i:s',($v['start_time']+$v['toff'])) . '--《' . $v['title'] . '》';
				/*
				switch(intval($v['state']))
				{
					case 0://录制等待录制
						$ret[$k]['state'] = 0;
						$ret[$k]['operation'] = $v['text'];
						
						$obj_curl->setSubmitType('get');
						$obj_curl->initPostData();
						$obj_curl->addRequestData('action', 'SELECT');
						$obj_curl->addRequestData('id', $v['conid']);
						$record_xml = $obj_curl->request('');
						$record_array = xml2Array($record_xml);
		        		if(!empty($record_array) && $record_array['result'])//表示任务存在
		        		{
		        			if($record_array['record']['status'] == 'running')
		        			{
		        				if(($v['start_time']+$v['toff'])<=($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
		        				{
			        				$ret[$k]['operation'] = '录制超时';
					        		$ret[$k]['state'] = 2;
					        		$ret[$k]['has_completed'] = -1;
		        				}
		        				else 
		        				{
			        				$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
		        					$ret[$k]['operation'] = '录制中';//.
		        				}
		        			}
		        			if($record_array['record']['status'] == 'waiting')
		        			{
		        				if(($v['start_time']+$v['toff'])<=($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW))
		        				{
			        				$ret[$k]['operation'] = '录制超时';
					        		$ret[$k]['state'] = 2;
					        		$ret[$k]['has_completed'] = -1;
		        				}
		        				else
		        				{
			        				$ret[$k]['operation'] = '等待录制';//.$record_array['record']['startTime']
			        				$ret[$k]['has_completed'] = -1;
		        				}
		        			}
		        		}
		        		else
		        		{
			        		$ret[$k]['operation'] = '录制超时';
							$ret[$k]['state'] = 2;
							$ret[$k]['has_completed'] = -1;
			        	}
			        	
					break;
					case 1://录制成功
						$ret[$k]['state'] = 1;
						$ret[$k]['operation'] = $v['text'] ? $v['text'] : '录制超时';
			        	$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
					break;
					case 2://录制失败
						$ret[$k]['state'] = 2;
						$ret[$k]['operation'] = $v['text'] ? $v['text'] : '录制超时';
			        	$ret[$k]['has_completed'] = ($record_array['record']['serverTime'] ? $record_array['record']['serverTime'] : TIMENOW) - $v['start_time'];
					break;
				}
				*/					
				/*
if(!$channel[$v['channel_id']])
				{
					$ret[$k]['operation'] = '频道关闭';
					$ret[$k]['state'] = 2;
				}
*/
				
				//$ret[$k]['state'] = intval($v['state']);
				$ret[$k]['auto'] = 1;
				$ret[$k]['toff'] = $v['toff'];
				$ret[$k]['isError'] = $ret[$k]['state']==2 ? 1 : 0;//$v['up_data'];
				$ret[$k]['operation'] = $pre_title;
				//hg_pre($ret[$k]);
				$data[] = $ret[$k];
//				$this->addItem($ret[$k]);
			}
			return $data ;
		}
	}
	
	public function detail($condition = '')
	{
		$sql ="SELECT * FROM " . DB_PREFIX . "program_record_log " . $condition;
		$row = $this->db->query_first($sql);
		return $row;
	}
	
	public function count($condition = '')
	{
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "program_record_log WHERE 1" . $condition;
		$f = $this->db->query_first($sql);
		return $f;		
	}
	
	public function create($info = array())
	{
		if(empty($info))
		{
			return false;
		}	
		$sql = "INSERT INTO " . DB_PREFIX . "program_record_log SET ";
		$space = '';
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$this->db->query($sql);
		return $id = $this->db->insert_id();
	}
	
	public function update($info = array())
	{
		if(empty($info))
		{
			return false;
		}
		$sql = "SELECT conid FROM " . DB_PREFIX . "program_record WHERE id=" . $info['content_id'];
		$f = $this->db->query_first($sql);
		if($f && $f['conid'])
		{
			$sql = "SELECT log_id FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen && $sen['log_id'])
			{
				$sql = "UPDATE " . DB_PREFIX . "program_record_log SET text='" . $info['text'] . "',state=" . $info['state'] . " WHERE id=" . $sen['log_id'];
				$this->db->query($sql);
				return $sen['log_id'];
			}
			else
			{
				return array('error' => '日志不存在');
			}
		}
		else
		{
			return array('error' => '录制无此对象');
		}
	}
	
	public function delete_log($record_id,$id)
	{
		if(empty($record_id) || empty($id))
		{
			return false;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id=" . $record_id;
		$row = $this->db->query_first($sql);
			
		if($row['start_time'] <= TIMENOW && TIMENOW <= ($row['start_time']+$row['toff']))
		{
			if(defined('IS_WOZA') && IS_WOZA)
			{
				$this->errorOutput('录制已经开始，无法删除');//woza录制无法删除正在录制中的。。。。
			}
		}
		
		//删除录制日志
		$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE id=" . $id;
		$this->db->query($sql);
		
		$week_day = unserialize($row['week_day']);
		if (is_array($week_day) && $week_day)
		{
			$week_now = date('N',$row['start_time']);
			$new_arr = array_flip($week_day);
			if(count($week_day) > ($new_arr[$week_now]+1))
			{
				$ks = $new_arr[$week_now] + 1;
			}
			else
			{
				$ks = 0;
			}
			$week_day = array_flip($new_arr);
			$next_week = ($week_day[$ks] - $week_now)>0?($week_day[$ks] - $week_now):($week_day[$ks] - $week_now + 7);

			$start_time = $row['start_time']+($next_week*86400);
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=0,start_time=" . $start_time . " WHERE id=" . $row['id'];
			$this->db->query($sql_update);
		}
		else
		{
			$sql_update = "UPDATE " . DB_PREFIX . "program_record SET is_record=2 WHERE id=" . $row['id'];
			$this->db->query($sql_update);
		}
		
		if($row['conid'] && $row['start_time'] > (TIMENOW+5))//只删除未开始录制的
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
			$tmp_first = $this->db->query_first($sql);
			if(!empty($tmp_first))
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 AND id=" . $row['server_id'];
				$fServer = $this->db->query_first($sql);
				if($fServer)
				{
					include_once (ROOT_PATH . 'lib/class/curl.class.php');
					$this->curl = new curl($fServer['host'] . ':' . $fServer['port'], $fServer['dir']);
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
					$this->curl->addRequestData('id', $tmp_first['conid']);
					$record_xml = $this->curl->request('');
					$record_array = xml2Array($record_xml);
				}				
				$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE record_id IN (" . $row['id'] . ")";
				$this->db->query($sql);
			}
		}
		return true;
	}
	
}
?>