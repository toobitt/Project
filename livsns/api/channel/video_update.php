<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: video_update.php 3939 2011-05-20 02:04:05Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

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
		$this->mUser = new user();
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
		
		//视频中需要更新的字段
		$update_field = array('title'       => trim($this->input['title']),
							  'tags' 		=> trim($this->input['tag']),
		                      'sort'		=> trim($this->input['sort']),
		                      'copyright' 	=> trim($this->input['copyright']),
		                      'brief' 		=> trim($this->input['brief'])	
		);

		$sql = "UPDATE " . DB_PREFIX . "video SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			$field .= $db_field . " = '" . $value . "' ,";
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE id = " . $video_id;		
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		
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
	 */
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