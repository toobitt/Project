<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: thread_update.php 8265 2012-07-23 08:54:52Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_thread_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 帖子数据更新API
 * 
 * 提供的方法：
 * 1) 删除单条帖子数据
 * 2) 批量删除帖子数据
 * 
 * @author chengqing
 *
 */
class threadUpdateApi extends BaseFrm
{
	public function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->recycle = new recycle();
		$this->publish_column = new publishconfig();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 更新帖子的数据
	 */
	public function update()
	{
		$thread_id = $this->input['thread_id'] ? intval($this->input['thread_id']) : -1;
		
		if($thread_id <= 0)
		{
			$this->errorOutput('未传入更新帖子的ID');
		}
		
		//视频中需要更新的字段
		$update_field = array('title' => urldecode(trim($this->input['title']))
		);

		$sql = "UPDATE " . DB_PREFIX . "thread SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value )
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE thread_id = " . $thread_id;		
		$sql = $sql . $field . $condition;
				
		$this->db->query($sql);
		$num = $this->db->affected_rows();
		if(urldecode(trim($this->input['pagetext'])))
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "thread WHERE thread_id=".$thread_id;
			$f = $this->db->query_first($sql);
			if($f['first_post_id'])
			{
				$sql = "UPDATE " . DB_PREFIX . "post SET pagetext='".urldecode(trim($this->input['pagetext']))."' WHERE post_id=".$f['first_post_id'];
				$this->db->query($sql);
			}
		}

		$this->setXmlNode('thread_info' , 'thread');
		if($num)
		{
			$this->addItem('帖子更新成功');
		}
		else
		{
			$this->addItem('帖子更新失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 删除单条帖子数据
	 * 注：此方法已不用
	 */
	/*
	public function delete()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		
		if($id <= 0)
		{
			$this->errorOutput('未传入删除帖子的ID');	
		}

		$sql = "SELECT * FROM " . DB_PREFIX . "thread WHERE thread_id =" . $id ;
		$thread_info = $this->db->query_first($sql);
		
		if($thread_info && is_array($thread_info))
		{
			//删除该帖子的评论
			$sql = "DELETE FROM " . DB_PREFIX . "post WHERE thread_id = " . $id;
			$this->db->query($sql);
			
			//更新分类表
			$sql = "UPDATE " . DB_PREFIX . "thread_category SET thread_count = CASE WHEN thread_count -1 > 0 THEN thread_count - 1 ELSE 0 END WHERE id = " . $thread_info['category_id'];
			$this->db->query($sql);
			
			//更新地盘下的帖子数目
			$sql = "SELECT g.parents FROM " . DB_PREFIX . "group g LEFT JOIN " . DB_PREFIX . "thread t ON g.group_id = t.group_id WHERE t.thread_id = " . $id;
			$r = $this->db->query_first($sql);
						
			$ids_array = explode(',' , $r['parents']);

			$ids = '';
			foreach($ids_array as $v)
			{
				if(empty($v))
				{
					continue;
				}
				$ids .= $v . ',';
			}		
			$ids = substr($ids , 0 , strlen($ids) - 1);
			
			$sql = "UPDATE " . DB_PREFIX . "group SET thread_count  = CASE WHEN thread_count - 1 > 0 THEN thread_count - 1 ELSE 0 END , post_count = CASE WHEN post_count - 1 > 0 THEN post_count - 1 ELSE 0 END WHERE group_id IN( $ids )";
			$this->db->query($sql);
			
			//删除帖子数据
			$sql = "DELETE FROM ".DB_PREFIX."thread WHERE thread_id =" . $id ;
			$this->db->query($sql);

			//删除帖子附件(暂未调用，涉及路径的配置)
			//$this->clean_attach($id , 0 );
		}
		else
		{
			$this->errorOutput('删除的帖子不存在');
		} 
	}*/
	
	/**
	 * 批量删除帖子数据
	 * 注：此方法已不用
	 */
	/*
	public function batch_delete()
	{
		//删除的帖子IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim($this->input['id']));				
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		foreach($id_array as $k => $v)
		{
			if(!$v)
			{
				unset($id_array[$k]);
			}
		}
		
		$delete_ids = implode(',' , $id_array);
		
		//更新分类信息表
		$sql = "SELECT * FROM " . DB_PREFIX . "thread WHERE thread_id IN ( $delete_ids )";
		$q = $this->db->query($sql);
		
		$category_id = '';
		while($row = $this->db->fetch_array($q))
		{
			$category_id .= $row['category_id']	. ',';		
		}
		$category_id = substr($category_id , 0 , strlen($category_id) - 1);
		
		//更新分类表
		$sql = "UPDATE " . DB_PREFIX . "thread_category SET thread_count = CASE WHEN thread_count -1 > 0 THEN thread_count - 1 ELSE 0 END WHERE id IN ( $category_id )";
		$this->db->query($sql);
				
		$parents = array();
		//更新地盘下的帖子数目
		$sql = "SELECT g.parents FROM " . DB_PREFIX . "group g LEFT JOIN " . DB_PREFIX . "thread t ON g.group_id = t.group_id WHERE t.thread_id IN ( $delete_ids )";
		$q = $this->db->query($sql);
			
		while($row = $this->db->fetch_array($q))
		{
			$ids_array = explode(',' , $row['parents']);
			$p_ids = '';
			foreach($ids_array as $v)
			{
				if(empty($v))
				{
					continue;
				}
				$p_ids .= $v . ',';
			}		
			$p_ids = substr($ids , 0 , strlen($ids) - 1);
			
			$sql = "UPDATE " . DB_PREFIX . "group SET thread_count  = CASE WHEN thread_count - 1 > 0 THEN thread_count - 1 ELSE 0 END , post_count = CASE WHEN post_count - 1 > 0 THEN post_count - 1 ELSE 0 END WHERE group_id IN ( $p_ids )";
			$this->db->query($sql);		
		}
		
		//删除帖子数据
		$sql = "DELETE FROM " . DB_PREFIX . "thread WHERE thread_id IN ( $delete_ids )";
		$this->db->query($sql);

		//删除帖子附件(暂未调用，涉及路径的配置)
		//$this->clean_attach($id , 0 );			
	}*/
	
	/**
	 * 批量审核帖子数据
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
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['thread_id'])));
						
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
				
		$sql = "UPDATE " . DB_PREFIX . "thread SET state = 1 WHERE thread_id IN (" . $verify_id . ")";	
		
		$r = $this->db->query($sql);

		
		$sql = "SELECT * FROM " . DB_PREFIX ."thread WHERE thread_id IN(" . $verify_id . ")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op = "update";			
			}
			else
			{
				$op = "insert";
			}
			$this->publish_insert_query($info['thread_id'], $op);
		}
		$this->setXmlNode('thread_info' , 'thread');
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
	 * 批量打回帖子数据
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
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['thread_id'])));
						
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
				
		$sql = "UPDATE " . DB_PREFIX . "thread SET state = 0 WHERE thread_id IN (" . $verify_id . ")";	
		
		$r = $this->db->query($sql);

		$sql = "SELECT * FROM " . DB_PREFIX ."thread WHERE thread_id IN(" . $verify_id .")";
		$ret = $this->db->query($sql);
		while($info = $this->db->fetch_array($ret))
		{
			if(!empty($info['expand_id']))
			{
				$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
			}		
			else 
			{
				$op = "";
			}
			$this->publish_insert_query($info['thread_id'], $op);
		}
		
		$this->setXmlNode('thread_info' , 'thread');
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
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');	
	}
	
	/**
	 * 
	 * 删除帖子附件
	 * @param $ids 帖子IDS
	 * @param $type 附件类型
	 */
	private function clean_attach($ids, $type = 0)
	{
		if ($ids) 
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "material WHERE id IN ( ". $ids . ") AND type=" . $type;
			
			$this->db->query($sql);
			while($matinfo = $this->db->fetch_array())
			{
				if (in_array(strtolower($matinfo['file_type']), array('gif', 'jpg', 'jpeg', 'png'))) 
				{
					foreach ($this->settings['thread_attach_thumb'] as $path => $aa) 
					{
						@unlink($this->settings['livime_upload_dir'] . 'space/oth/' . $path. $row['file_path'] . $row['file_name']);
					}					
				}
				@unlink($this->settings['livime_upload_dir'] . 'space/oth/img/'. $row['file_path'] . $row['file_name']);
			}
			
			$sql = "DELETE FROM " . DB_PREFIX . "material WHERE material_id IN ( $ids )";
			$this->db->query($sql);		
		}
	}
	
	/**
	 * 批量删除帖子数据
	 */
	public function delete()
	{
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['thread_id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		
		$delete_id = implode(',' , $id_array);
		//放入回收箱开始
		$sql = "SELECT * FROM " . DB_PREFIX . "thread WHERE thread_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if(intval($row['state']) == 1 && $row['expand_id'])
			{
				$op = "delete";
				$this->publish_insert_query($row['thread_id'],$op);
			}
			
			$data2[$row['thread_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['title'],
					'cid' => $row['thread_id'],
			);
			$data2[$row['thread_id']]['content']['thread'] = $row;
		}
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		
		//放入回收站结束
		$sql = "DELETE FROM " . DB_PREFIX . "thread WHERE thread_id IN (" . $delete_id . ")";

		$r = $this->db->query($sql);
		
		$this->setXmlNode('thread_info' , ' thread');
		if($r)
		{
			$this->addItem('批量删除成功');
		}
		else
		{
			$this->addItem('批量删除失败');	
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
		//还原帖子
		if(!empty($content['thread']))
		{
			$sql = "insert into " . DB_PREFIX . "thread set ";
			$space='';
			foreach($content['thread'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}
		return $data;
	}
	*/
	
	
	//using
	/**
	*  发布系统，将内容传入发布队列
	*  
	*/	
	public function publish_insert_query($id,$op,$column_id = array(),$child_queue = 0)
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		
		$sql = "select * from " . DB_PREFIX ."thread where thread_id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id))
		{		
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}			
		}
		else 
		{
			$column_id = implode(',',$column_id);
		}

 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=>	THREAD_PLAN_SET_ID,
			'from_id'   =>  $info['thread_id'],
			'class_id'	=> 	0,
			'column_id' => $column_id,
			'title'     =>  $info['title'],
			'action_type' => $op,
			'publish_time'  => $info['pub_time'],
			'publish_people' => urldecode($this->user['user_name']),
			'ip'   =>  hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = THREAD_PLAN_SET_ID;
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	
	/**
	 * 即时发布
	 * @param id  int   文章id
	 * @param column_id string  发布的栏目id
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		
		$id = intval($this->input['id']);
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		$column_id = serialize($column_id);
	
		//查询修改文章之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."thread where thread_id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		
		$sql = "UPDATE " . DB_PREFIX ."thread SET column_id = '". $column_id ."' WHERE thread_id = " . $id;
		$this->db->query($sql);
		
		if(intval($q['state']) == 1)
		{
			if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
		}
		else    //打回
		{
			if(!empty($q['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($id,$op);
			}
		}
		
		$this->addItem($ret);
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new threadUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>