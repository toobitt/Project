<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','program_record');//模块标识
class programRecordUpdateApi extends adminUpdateBase
{
	private $obj;
	function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/program_record.class.php');
		$this->obj = new programRecord();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : 0);
		if(empty($channel_id))
		{
			$this->errorOutput('请传入频道ID');
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1, 1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
	//	$all_node = $newLive->getFatherNodeByid($channel['node_id']);
		$nodes['nodes'][$channel['id']] = $channel['id'];
		//hg_pre($nodes);exit;
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		

		$start_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time'])));
		$end_time = strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time'])));

		$toff = $end_time - $start_time;
		if($toff < 0)
		{
			$toff = $toff + 24*3600;
		}		
		$is_record = 0;//判断录制状态
		$is_out = 0;//判断是否超时
		if(is_array($this->input['week_day']) && $this->input['week_day'])
		{
			$is_record = 0;
			if($start_time > (TIMENOW+5))//大于当前时间
			{
				$week_now = date('N',TIMENOW);
				$week_num = $this->input['week_day'];
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
				$next_week = ($next_day - $week_now) >= 0 ? ($next_day - $week_now) : ($next_day - $week_now + 7);
				$dates = date('Y-m-d', (TIMENOW + $next_week*86400));
				$start_time =  strtotime($dates . ' ' . trim(urldecode($this->input['start_time'])));
				$end_time =  strtotime($dates . ' ' . trim(urldecode($this->input['end_time'])));
			}
			$week_day = serialize($this->input['week_day']);
			
		}
		else
		{//单天
			if($start_time > TIMENOW)//大于当前时间
			{
				$is_record = 0;
				$is_out = 0;
			}
			else
			{
				$is_record = 2;
				$is_out = 1;
			}			
		}

		$sql = "SELECT channel_id, start_time, toff FROM " . DB_PREFIX . "program_record WHERE channel_id =" . $channel_id . " AND " . $start_time . "=start_time and " . $end_time . "=(start_time+toff) AND week_day='" . $week_day . "'";
		$f = $this->db->query_first($sql);
		if($f['channel_id'])
		{
			$this->errorOutput('此段时间已经有节目被录制！');
		}
		$columnid = $this->input['column_id'] ? $this->input['column_id'] : '';
		$column_name = $this->input['column_name'] ? $this->input['column_name'] : '';
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			switch($this->user['prms']['default_setting']['create_content_status'])
			{
				case 1:
					$this->input['audit_auto'] = 0;
					break;
				case 2:
					$this->input['audit_auto'] = 1;
					break;
				default:
				break;
			}			
		}
		
		$info = array(
				'channel_id' => $channel_id,
				'start_time' => $start_time,
				'title' => $this->input['title'] ? $this->input['title'] : '',
				'toff' => $toff,
				'week_day' => ($week_day ? $week_day : ''),
				'item' => intval($this->input['item']),
				'columnid' => $columnid,
				'column_name' => $column_name,
				'create_time' => TIMENOW,
				'update_time' => TIMENOW,
				'is_mark' => $this->input['is_mark'],
				'ip' => hg_getip(),
				'force_codec' => $this->input['force_codec'] ? 1 : 0,
				'is_record' => $is_record,
				'audit_auto' => $this->input['audit_auto'],
				'is_out' => $is_out,
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'org_id' => $this->user['org_id'],
				'appid' => $this->user['appid'],
				'appname' => $this->user['display_name'],
		);
		if(!$info['channel_id'] || !$info['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		if(empty($toff))
		{
			$this->errorOutput('录制时长不能为零！');
		}
		$server_id = intval($this->input['server_id']) ? intval($this->input['server_id']) : 0;
		$server = $this->_get_server(0,$server_id);
		if(empty($server))
		{
			$this->errorOutput('录制服务不存在or有误～');
		}
		$info['server_id'] = $server['id'];
		$ret = $this->obj->create($info);
		$info['id'] = $ret['id'];
		$this->addLogs('新增录制','',$info,'','',$info['title']);
		
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		if(!$this->input['channel_id'] || !$this->input['start_time'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$channel_id = intval($this->input['channel_id'] ? $this->input['channel_id'] : 0);
		if(empty($channel_id))
		{
			$this->errorOutput('请传入频道ID');
		}
		include_once(ROOT_PATH . 'lib/class/live.class.php');
		$newLive = new live();
		$channel = $newLive->getChannelById($channel_id, -1, 1);
		$channel = $channel[0];
		if(!$channel['id'])
		{
			$this->errorOutput('该频道已经不存在！');
		}

		$sql = "select * from " . DB_PREFIX . "program_record where id=" . $id;
		$f = $this->db->query_first($sql);
		if(empty($f))
		{
			$this->errorOutput('此录制已不存在');
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$nodes = array();
		//$all_node = $newLive->getFatherNodeByid($channel['node_id']);
		$nodes['nodes'][$channel_id] = $channel_id;
		//hg_pre($nodes);exit;
		$this->verify_content_prms($nodes);
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
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if($f['conid'] && $f['start_time'] > (TIMENOW+5)) //更新的时候，假如当前的conid计划，未开始录制，删除，正在录制不进行任何调整 5秒的冗余
		{
			$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 AND id=" . $f['server_id'];
				$fServer = $this->db->query_first($sql);
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
				if($fServer)
				{
					$this->curl = new curl($fServer['host'] . ':' . $fServer['port'] , $fServer['dir']);
				}
				else
				{
					$this->errorOutput('录制服务地址不存在！');
				}
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
		}
		
		if($f['conid'] && TIMENOW > $f['start_time'] && TIMENOW < ($f['start_time']+$f['toff']))
		{				
			$sql = "SELECT id,log_id,conid FROM " . DB_PREFIX . "program_queue WHERE id=" . $f['conid'];
			$sen = $this->db->query_first($sql);
			if($sen)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1 AND id=" . $f['server_id'];
				$fServer = $this->db->query_first($sql);
				include_once (ROOT_PATH . 'lib/class/curl.class.php');
				if($fServer)
				{
					$this->curl = new curl($fServer['host'] . ':' . $fServer['port'] , $fServer['dir']);
				}
				else
				{
					$this->errorOutput('录制服务地址不存在！');
				}
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
		}
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			switch($this->user['prms']['default_setting']['create_content_status'])
			{
				case 1:
					$this->input['audit_auto'] = 0;
					break;
				case 2:
					$this->input['audit_auto'] = 1;
					break;
				default:
				break;
			}			
		}
		
		$data = array(
			'column_id' => urldecode($this->input['column_id']) ? urldecode($this->input['column_id']) : '',
			'column_name' => urldecode($this->input['column_name']) ? urldecode($this->input['column_name']) : '',
			'is_mark' => $this->input['is_mark'],
			'force_codec' => $this->input['force_codec'] ? 1 : 0,
			'audit_auto' => $this->input['audit_auto'],
			'start_time' => strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['start_time']))),
			'end_time' => strtotime(trim(urldecode($this->input['dates'])) . ' ' . trim(urldecode($this->input['end_time']))),
			'week_day' => $this->input['week_day'],
			'channel_id' => intval($this->input['channel_id']),
			'title' => $this->input['title'] ? trim($this->input['title']) : '',
			'item' => intval($this->input['item']),
			'server_id' => $f['server_id'],
		);
		$server_id = intval($this->input['server_id']) ? intval($this->input['server_id']) : 0;
		$server = $this->_get_server(0,$server_id);
		if(empty($server))
		{
			$this->errorOutput('录制服务不存在or有误～');
		}
		$data['server_id'] = $server['id'];
		$ret = $this->obj->update($id,$data);
		if($ret['id'])
		{
			$this->addLogs('更新录制',$f,$ret,'','',$ret['title']);
		}
		$this->setXmlNode('program_record', 'info');
		$this->addItem($ret);
		$this->output();
	}
	
	private function _get_server($offset = 0,$id = 0)
	{
		$cond = '';
		if($id)
		{
			$cond = " AND id=" . $id;
		}
		$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 " . $cond . " AND state=1 LIMIT " . $offset . ",1";
		$f = $this->db->query_first($sql);
		if(!$f)
		{
			return false;
		}
		$f['isSuccess'] = $this->_checkServer($f['host'] . ':' . $f['port'] . $f['dir']);
		if(!$f['isSuccess'])
		{
			return $this->_get_server($offset+1);
		}
		else
		{
			return $f;
		}	
	}
	
	private function _checkServer($url)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_exec($ch);
		$head_info = curl_getinfo($ch);
        curl_close($ch);
		if ($head_info['http_code'] != 200)
		{
			return false;
		}
		return true;
	}
	

	//
	public function delete()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "server_config WHERE 1 AND state=1";
			$q = $this->db->query($sql);
			$server_config = array();
			while($row = $this->db->fetch_array($q))
			{
				$server_config[$row['id']] = $row;
			}
		
			$sql = "SELECT * FROM " . DB_PREFIX . "program_record WHERE id IN (" . $id . ")";
			$q = $this->db->query($sql);
			
			include_once(ROOT_PATH . 'lib/class/live.class.php');
			$newLive = new live();
			$info = array();
			$channel_id = array();
			while($row = $this->db->fetch_array($q))
			{
				$info[] = $row;
				$channel_id[$row['channel_id']] = $row['channel_id'];
			}
			$channel_node = $channel_parent_node = array();
			if($channel_id)
			{
				$channel = $newLive->getChannelById(implode(',',$channel_id), -1);
				if(!empty($channel))
				{
					foreach($channel as $k => $v)
					{
						$tmp_data = $newLive->getFatherNodeByid($v['node_id']);
						$channel_node[$v['id']] = $tmp_data[0];
						$channel_parent_node[$v['id']] = $v['node_id'];
					}
				}
			}
			
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$id_success = array();
			$delete_id = $space = "";
			$ret = array();
			foreach($info as $k => $row)
			{
				$this->curl = new curl($server_config[$row['server_id']]['host'] . ':' . $server_config[$row['server_id']]['port'], $server_config[$row['server_id']]['dir']);
				#####整合数据进行权限
				$nodes = array();
				$nodes['nodes'][$row['channel_id']] = $row['channel_id'];
				//hg_pre($nodes);exit;
				$this->verify_content_prms($nodes);
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
				#####整合数据进行权限结束
				
				if($row['conid'] && $row['start_time'] > (TIMENOW+5)) //未进行录制，可以删除 5秒的冗余
				{
					$sql = "SELECT * FROM " . DB_PREFIX . "program_queue WHERE id=" . $row['conid'];
					$tmp_first = $this->db->query_first($sql);
					if(!empty($tmp_first))
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
						$this->curl->addRequestData('id', $tmp_first['conid']);
						$record_xml = $this->curl->request('');
						$record_array = xml2Array($record_xml);
						if($record_array['result'])
						{
							$id_success[] = $row['id'];
						}
					}
				}
				$delete_id .= $space . $row['id'];
				$space = ',';
				$ret[] = $row;
			}
			
			if($delete_id)
			{		
				$sql = "DELETE FROM " . DB_PREFIX . "program_record WHERE id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_record_relation WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_record_log WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
				$sql = "DELETE FROM " . DB_PREFIX . "program_queue WHERE record_id IN (" . $delete_id . ")";
				$this->db->query($sql);
			}
			$this->addLogs('删除录制' , $ret , '', '', '', '删除录制' . $delete_id);
		}
		
		$re = array();
		$id_array = explode(',',$id);
		$delid_array = explode(',',$delete_id);
		if(count($id_array) == count($delid_array))
		{
			$re['info'] = '';
		}
		else
		{
			$re['info'] = '来自于节目单或者节目单计划的录制内容无法删除！';
		}
		
		$re['id'] = $delete_id;
		
		$this->setXmlNode('program_record','info');
		$this->addItem($re);
		$this->output();
	}
	
	/*
	 * 获取转码配置
	 */
	public function get_transcode_config()
	{
		require_once(ROOT_PATH . 'lib/class/mediaserver.class.php');
		$m = new mediaserver();
		$ret = $m->getSettings();
		$this->addItem($ret);
		$this->output();
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
	
	public function update_record_state()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('未传入录制ID');
		}
		$this->obj->update_record_state($id);
		
		$this->addItem(array('id' => $id));
		$this->output();
	}
}

$out = new programRecordUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();
?>