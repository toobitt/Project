<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment_update.php 8433 2012-07-27 03:42:19Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_comment_m');//模块标识
require(ROOT_DIR . 'global.php');

class commentUpdateApi extends outerUpdateBase
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
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	/**
	 * 评论数据批量审核
	 */
	public function audit()
	{
		/**
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
		*/
				
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
				
		$sql = "UPDATE " . DB_PREFIX . "post SET state = 1 WHERE post_id IN (" . $verify_id . ")";	

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
	 * 评论数据批量打回
	 */
	public function back()
	{
		/**
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
		*/
				
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
				
		$sql = "UPDATE " . DB_PREFIX . "post SET state = 0 WHERE post_id IN (" . $verify_id . ")";	

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
		$sql = "SELECT * FROM " . DB_PREFIX . "post WHERE post_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		$thread_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			$thread_id .= $space.$row['thread_id'];
			$space = ',';
			$data2[$row['post_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['pagetext'],
					'cid' => $row['post_id'],
			);
			$data2[$row['post_id']]['content']['post'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		
		//放入回收站结束
		//  投票的话   删除 poll 中的totalpoll - 1 
		
		//  普通评论  删除thread 中的（包括投票） post_count - 1 
		
		/*$sql = "SELECT post_id,thread_id,poll_id FROM " . DB_PREFIX . "post WHERE post_id IN (" . $delete_id . ")";
		
		$q = $this->db->query($sql);
		$poll_id = $thread_id = $space = "";
		while($row = $this->db->fetch_array($q))
		{
			if($row['poll_id'])
			{
				$poll_id .= $space.$row['poll_id'];
			}
			$thread_id .= $space.$row['thread_id'];
			$space = ',';
		}*/
		if($res['sucess'])
		{
			$sql = "DELETE FROM " . DB_PREFIX . "post WHERE post_id IN (" . $delete_id . ")";
			$this->db->query($sql);
			
	//		$sql = "update " . DB_PREFIX . "poll set totalpoll = totalpoll-1 where poll_id IN(" . $poll_id . ")";
	//		$this->db->query($sql);
	
			$sql = "update " . DB_PREFIX . "thread set post_count = post_count-1 where thread_id IN (" . $thread_id . ")";
			$resule_aa = $this->db->query($sql);
			
			$this->setXmlNode('comment_info' , 'comment');
			$this->addItem($resule_aa);
			$this->output();
		}
	}

	//还原
	public function recover()
	{
		if(empty($this->input['content']))
		{
			return false;
		}
		$content=json_decode(urldecode($this->input['content']),true);
		$thread_id = $content['post']['thread_id'];
		
		//还原评论
		/*if(!empty($content['post']))
		{
			$sql = "insert into " . DB_PREFIX . "post set ";
			$space='';
			foreach($content['post'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}*/
		//更新帖子评论数
		$sql = "update " . DB_PREFIX . "thread set post_count = post_count+1 where thread_id IN(" . $thread_id . ")";
		$this->db->query($sql);
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
