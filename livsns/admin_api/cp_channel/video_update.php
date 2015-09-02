<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: video_update.php 8427 2012-07-27 03:12:02Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','video_m');//模块标识
require(ROOT_DIR . 'global.php');
require_once(ROOT_PATH . 'lib/class/logs.class.php');

/**
 * 
 * 功能 ：视频数据维护API
 * 
 * 提供的方法：
 * 1) 视频数据的录入
 * 2) 视频的数据更新
 * 3) 视频的单个删除
 * 
 * @author chengqing
 *
 */
class updateVideoApi extends BaseFrm
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();
		
		//导入视频接口调用封装类
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
		$this->mUser = new user();
		$this->logs = new logs();
	}
		
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 视频数据的录入
	 */
	public function insert(){}

	/**
	 * 
	 * 更新视频的数据
	 */
	public function update()
	{
		$video_id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($video_id <= 0)
		{
			$this->errorOutput('未传入更新视频的ID');	
		}
		$sql = "SELECT tags FROM " . DB_PREFIX . "video WHERE id=" . $video_id;
		$f = $this->db->query_first($sql);
		
		//视频中需要更新的字段
		$update_field = array('title'       => urldecode(trim($this->input['title'])),
							  'tags' 		=> urldecode(trim($this->input['tags'])),
		                      'sort_id'		=> trim($this->input['sort_id']),
		                      'copyright' 	=> trim($this->input['copyright']),
		                      'brief' 		=> urldecode(trim($this->input['brief'])),
							  'is_show'     => trim($this->input['is_show'])
		);

		$sql = "UPDATE " . DB_PREFIX . "video SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE id = " . $video_id;		
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'update', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束	
		if($video_info['tags'] != $f['tags'])
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$q = $this->db->query($sql);
			
			$t_id = $space = "";
			while($row = $this->db->fetch_array($q))
			{
				if($row['tag_id'])
				{
					$t_id .= $space . $row['tag_id'];
				}
				$space = ",";
			}
			
			$sql = "delete from " . DB_PREFIX . "video_tags where type=0 and video_id = " . $video_id;
			$this->db->query($sql);
			if($t_id)
			{
				$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE id IN (" . $t_id . ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					$tag_count = 0;
					if(($row['tag_count']-1)>0)
					{
						$tag_count = $row['tag_count'] - 1;
					}
					
					$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = $tag_count  WHERE id = '" . $row['id'] . "'";
					$this->db->query($sql);
				}
			}
		}
		
		
		//标签		
		$tags = explode(',' , trim($this->input['video_tag']));
		
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
				$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $video_id . ", tag_id = " . $r['id'] . " , type = 0";
				$this->db->query($sql);
			}
			else//如果该标签不存在
			{
				//录入新标签
				$sql = "INSERT INTO " . DB_PREFIX . "tags SET tagname = '" . trim($v) . "' , tag_count = tag_count + 1";
				$this->db->query($sql);			
				$tag_id = $this->db->insert_id();
				
				//录入标签和视频的对应关系
				$sql = "INSERT INTO " . DB_PREFIX . "video_tags SET video_id = " . $video_id . ", tag_id = " . $tag_id . " , type = 0";
				$this->db->query($sql);
			} 	
		}

		$this->setXmlNode('video_info' , 'video');
		if($r)
		{
			$this->addItem('视频更新成功');
		}
		else
		{
			$this->addItem('视频更新失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 
	 * 删除视频数据(单条删除)
	 * 注：现在数据删除已建立触发器，此方法已不用
	 */
	/*
	public function delete()
	{
		//删除视频的ID
		$video_id = $this->input['id'] ? intval($this->input['id']) : -1;  
		
		if($video_id <= 0)
		{
			$this->errorOutput('未传入视频ID');  
		}
		
		//查询该用户的信息
		$sql = "SELECT user_id FROM " . DB_PREFIX . "video WHERE id = " . $video_id;
		$r = $this->db->query_first($sql);		
		$user_id = $r['user_id'];
		
		//删除视频表中视频信息
		$sql = "DELETE FROM " . DB_PREFIX . "video WHERE id = " . $video_id;
		$this->db->query($sql);

		//查询该用户的视频数目
		$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "video WHERE user_id = " . $user_id;
		$r = $this->db->query_first($sql);
		$video_count = $r['nums']; 
		
		//更新用户扩展表中的用户视频数目
		$this->mUser->delete_video_count($video_count);
		
		//删除标签-视频关联表中的信息
		$sql = "DELETE FROM " . DB_PREFIX . "video_tags WHERE video_id = " . $video_id . " AND type = 0";
		$this->db->query($sql);
					
		//如果该视频被收藏一并删除
		$sql = "SELECT * FROM " . DB_PREFIX . "collects WHERE cid = " . $video_id . " AND type = 0 AND user_id = " . $user_id;				
		$r = $this->db->query_first($sql);		
		if($r && is_array($r))
		{
			$sql = "DELETE FROM " . DB_PREFIX . "collects WHERE cid = " . $r['cid'] . " AND user_id = " . $user_id . " AND type = 0";
			$this->db->query($sql);
		}
		
		//如果这部视频有评论也将评论删除
		$sql = "SELECT * FROM " . DB_PREFIX . "comments WHERE cid = " . $video_id . " AND type = 0";
		$q = $this->db->query($sql);					
		$commment_id = array();			
		if($this->db->num_rows($q) > 0)
		{			
			while($row = $this->db->fetch_array($q))
			{
				$comment_id[] = $row['id'];
			}
			
			$ids = implode(',', $comment_id);
			$sql = "DELETE FROM " . DB_PREFIX . "comments WHERE id IN (" . $ids . ") AND type = 0";			
			$this->db->query($sql);			
		}

		$this->setXmlNode('video_info' , 'video');
		if($r)
		{
			$this->addItem('视频删除成功');
		}
		else
		{
			$this->addItem('视频删除失败');	
		}
		$this->output(); 
	}
	*/
	
	/**
	 * 批量删除视频数据
	 */
	public function delete()
	{
		
		//删除的视频IDS(格式：'1,2,3,4,5')
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
		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['id'],
			);
			$data2[$row['id']]['content']['video'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		if($res['sucess'])
		{
			//删除视频记录表中记录
			$sql = "DELETE FROM " . DB_PREFIX . "video WHERE id IN (" . $delete_id . ")";
			
			$this->db->query($sql);
			//记录日志
			$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
			//记录日志结束
			$this->setXmlNode('video_info' , ' video');
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
	//彻底删除
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$cid = urldecode($this->input['cid']);

		$sql = "SELECT serve_id FROM " . DB_PREFIX . "video where id IN (" . $cid . ")";
		$q = $this->db->query($sql);

		global $config;
		include_once(ROOT_DIR . 'api/video/video_api.php');          //导入流媒体API
		$tvie_video_api = new TVie_video_api($config);
		//删除物理文件
		while($row = $this->db->fetch_array($q))
		{
			$tvie_video_api->delete_video($row['serve_id']);
		}
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'delete_comp', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		//标签操作开始
		//从索引表中根据视频id查出tags的id
		$sql = "SELECT * FROM " . DB_PREFIX . "video_tags where type=0 and video_id IN( " . $cid .")";
		$q = $this->db->query($sql);
		
		$t_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			if($row['tag_id'])
			{
				$t_id .= $space . $row['tag_id'];
			}
			$space = ",";
		}
		//从索引表中删除与视频关联的标签
		$sql = "delete from " . DB_PREFIX . "video_tags where type=0 and video_id IN( " . $cid .")";
		$this->db->query($sql);
		if($t_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "tags WHERE id IN (" . $t_id . ")";
			$q = $this->db->query($sql);
			//更新标签使用计数
			while($row = $this->db->fetch_array($q))
			{
				$tag_count = 0;
				if(($row['tag_count']-1)>0)
				{
					$tag_count = $row['tag_count'] - 1;
				}
				
				$sql = "UPDATE " . DB_PREFIX . "tags SET tag_count = $tag_count  WHERE id = '" . $row['id'] . "'";
				$this->db->query($sql);
			}
		}
		//标签操作结束
		return $cid;
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
		if(!empty($content['video']))
		{
			$sql = "insert into " . DB_PREFIX . "video set ";
			$space='';
			foreach($content['video'] as $k => $v)
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
	 * 批量审核视频数据
	 */
	public function audit()
	{
		//审核的状态(默认为1 通过审核)
		$state = intval($this->input['audit']) ? 2 : 1;
				
		//审核的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['id'])));		
	
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值		
		$id_array = array_filter($id_array);
		
		$verify_id = implode(',' , $id_array);
		$sql = "UPDATE " . DB_PREFIX . "video SET is_show = " . $state . " WHERE id IN (" . $verify_id . ")";	
		
		$r = $this->db->query($sql);
		//记录日志
		$this->logs->addLogs(APP_UNIQUEID,MOD_UNIQUEID, 'audit', $this->user['display_name'], $this->user['lon'], $this->user['lat'],$this->user['user_id'],$this->user['user_name']);
		//记录日志结束
		$num = $this->db->affected_rows();
		$this->setXmlNode('video_info' , 'video');
		if($num)
		{

			$this->addItem(1);
		}
		else
		{
			$this->addItem(0);	
		}
		$this->output(); 
	}
	
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
$out = new updateVideoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>