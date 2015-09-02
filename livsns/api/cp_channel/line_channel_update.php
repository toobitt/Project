<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: line_channel_update.php 8427 2012-07-27 03:12:02Z hanwenbin $
***************************************************************************/

require_once './global.php';
define('MOD_UNIQUEID','channel_m');//模块标识
require_once(ROOT_PATH . 'lib/class/logs.class.php');

/**
 * 
 * 功能 ：频道数据更新API
 * 
 * 提供的方法：
 * 1) 频道数据单条审核
 * 2) 频道数据批量审核
 * 
 * @author chengqing
 *
 */
class lineChannelUpdate extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		$this->logs = new logs();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$this->errorOutput("111");
	}
	
	/**
	 * 
	 * 更新频道的数据
	 */
	public function update()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($id <= 0)
		{
			$this->errorOutput('未传入更新频道的ID');	
		}
		//file_put_contents('1.txt',$this->input['state']);
		//视频中需要更新的字段
		$update_field = array('web_station_name'       => urldecode(trim($this->input['web_station_name'])),
							  'tags' 		=> urldecode(trim($this->input['tags'])),
		                      'brief' 		=> urldecode(trim($this->input['brief'])),
							  'create_time' => strtotime(urldecode($this->input['create_time'])),
							  'collect_count' => intval($this->input['collect_count']),
							  'state' => intval($this->input['state']),
							  'update_time' => TIMENOW
		);
		$sql = "UPDATE " . DB_PREFIX . "network_station SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE id = " . $id;	
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		//标签		
		$str_tag = urldecode(trim($this->input['tags']));
		$str = str_replace(array(" ","，"), ",", $str_tag);
		
		$tags = explode(',' ,$str);
		
		//更数据库中标签表
		foreach($tags as $k => $v)
		{
			//查看该标签是否已经存在
			$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE tagname = '" . $v . "'";		
			$r = $this->db->query_first($sql);
		
			//如果该标签已存在
			if($r && is_array($r))
			{
				//更新该标签的数量
				$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = tag_count + 1 WHERE tagname = '" . trim($v) . "'";
				$this->db->query($sql);
				
				//录入标签和视频的对应关系
				$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $id . ", tag_id = " . $r['id'] . " , type = 1";
				$this->db->query($sql);
			}
			else//如果该标签不存在
			{
				//录入新标签
				$sql = "INSERT INTO " . DB_PREFIX . "tags SET tagname = '" . trim($v) . "' , tag_count = tag_count + 1";
				$this->db->query($sql);			
				$tag_id = $this->db->insert_id();
				
				//录入标签和视频的对应关系
				$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $id . ", tag_id = " . $tag_id . " , type = 1";
				$this->db->query($sql);
			} 	
		}

		$this->setXmlNode('station_info' , 'station');
		if($r)
		{
			$this->addItem('频道更新成功');
		}
		else
		{
			$this->addItem('频道更新失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 批量审核频道数据
	 */
	public function audit()
	{		
		//审核的状态
		$state = intval($this->input['audit']) ? 1 : 2;
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['id'])));		
		
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		foreach($id_array as $k => $v)
		{
			if(!$v)
			{
				unset($id_array[$k]);
			}
		}
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
		$sql = "UPDATE " . DB_PREFIX . "network_station SET state = " . $state . " WHERE id IN (" . $verify_id . ")";		
		$r = $this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$this->setXmlNode('channel_info' , 'channel');
		if($r)
		{
			$this->addItem('批量审核成功');
		}
		else
		{
			$this->addItem('批量审核失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 
	 * 批量删除频道数据
	 */
	public function delete()
	{		
		
		//删除的网台IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		 
		$delete_id = implode(',' , $id_array);
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "network_station WHERE id IN (" . $delete_id . ")";
		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['web_station_name'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['network_station'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "network_station WHERE id IN (" . $delete_id . ")";
	
			$r = $this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$this->setXmlNode('channel_info' , 'channel');
			if($r)
			{
				$this->addItem('批量删除成功');
			}
			else
			{
				$this->errorOutput('批量删除失败');	
			}
		}
		else 
		{
			$this->errorOutput('批量删除失败');
		}
		
		$this->output(); 
	}
	//还原
	/*public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		//还原视频记录表
		if(!empty($content['channel']))
		{
			$sql = "insert into " . DB_PREFIX . "network_station set ";
			$space='';
			foreach($content['channel'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'recover', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
		}
		return $data;
	}*/	
	
	/**
	 * 
	 * 方法名不存在时调用的方法
	 */
	public function none()
	{
		$this->errorOutput('方法不存在');		
	}	
}

/**
 *  程序入口
 */
$out = new lineChannelUpdate();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>
