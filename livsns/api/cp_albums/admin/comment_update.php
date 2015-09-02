<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment_update.php 23021 2013-05-31 09:37:07Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID','comment');//模块标识
require('global.php');
class commentUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function create(){}
	public function update(){}
	public function sort(){}
	public function publish(){}
	
	/**
	 * 评论数据批量审核
	 */
	public function audit()
	{
		//审核的状态(默认为1 通过)
		$state = intval($this->input['audit']);
		if ($state == 2)
		{
			$state = 0;
		}
		else
		{
			$state = $state ? 1 : 2;
		}
				
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['post_id'])));
						
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
				
		$sql = "UPDATE " . DB_PREFIX . "comment SET state = " . $state . " WHERE comment_id IN (" . $verify_id . ")";	

		$r = $this->db->query($sql);

		$this->setXmlNode('comment_info' , 'comment');
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
	 * 批量删除评论数据
	 */
	
	public function delete()
	{		
		/*
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['comment_id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		$delete_id = implode(',' , $id_array);
		*/
		
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$delete_id = $id;
		
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "comment WHERE comment_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		$albums_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			$albums_id .= $space.$row['albums_id'];
			$space = ',';
			$data2[$row['comment_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['content'],
					'cid' => $row['comment_id'],
			);
			$data2[$row['comment_id']]['content']['comment'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		//  普通评论  删除albums 中的 comment_count - 1 
		
		/*$sql = "SELECT albums_id FROM " . DB_PREFIX . "comment WHERE comment_id IN (" . $delete_id . ")";
		
		$q = $this->db->query($sql);
		$albums_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			$albums_id .= $space.$row['albums_id'];
			$space = ',';
		}*/
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "comment WHERE comment_id IN (" . $delete_id . ")";
			$this->db->query($sql);
			
			$sql = "update " . DB_PREFIX . "albums set comment_count = comment_count-1 where albums_id IN(" . $albums_id . ")";
			$r = $this->db->query($sql);
			
			$this->setXmlNode('comment_info' , 'comment');
			if($r)
			{
				$this->addItem('删除成功');
			}
			else
			{
				$this->errorOutput('删除失败!');	
			}
		}
		else
		{
			$this->errorOutput('删除失败');
		}
		$this->output();
	}
	//还原
	public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		$albums_id = $content['comment']['albums_id'];
		//还原相册评论条数
		$sql = "update " . DB_PREFIX . "albums set comment_count = comment_count+1 where albums_id IN(" . $albums_id . ")";
		$this->db->query($sql);
		//还原相册评论记录表
		/*if(!empty($content['comment']))
		{
			$sql = "insert into " . DB_PREFIX . "comment set ";
			$space='';
			foreach($content['comment'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}*/
		return $data;
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');	
	}	
}

/**
 *  程序入口
 */
$out = new commentUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();



?>
