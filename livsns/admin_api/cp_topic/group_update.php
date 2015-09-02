<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: group_update.php 8264 2012-07-23 08:54:13Z hanwenbin $
***************************************************************************/

define('ROOT_DIR', '../../');
define('MOD_UNIQUEID','cp_group_m');//模块标识
require(ROOT_DIR . 'global.php');

/**
 * 
 * 地盘数据更新API
 * 
 * 提供的方法：
 * 1) 删除单条帖子数据
 * 2) 批量删除帖子数据
 * 
 *
 */

class groupUpdateApi extends BaseFrm
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

	/**
	 * 更新圈子数据
	 */
	public function update()
	{
		$group_id = $this->input['group_id'] ? intval($this->input['group_id']) : -1;

		if($group_id <= 0)
		{
			$this->errorOutput('未传入更新地盘的ID');	
		}
		
		$update_field = array('name'        => urldecode(trim($this->input['gname'])),
							  'description' => urldecode(trim($this->input['description'])),
							  'fatherid' => urldecode(trim($this->input['father_id'])),
		                      'group_type'	=> intval($this->input['group_type']),
		                      'lat' 		=> trim($this->input['hid_lat']),
							  'lng' 		=> trim($this->input['hid_lng']),
		                      'group_addr' 	=> urldecode(trim($this->input['hid_addr']))	
		);
		
		$sql = "UPDATE " . DB_PREFIX . "group SET ";
		
		$field = '';
		foreach($update_field as $db_field => $value)
		{
			if(trim($value))
			{
				$field .= $db_field . " = '" . $value . "' ,";
			}
		}
		
		$field = substr($field , 0 , (strlen($field)-1));		
		$condition = " WHERE group_id = " . $group_id;		
		$sql = $sql . $field . $condition;				
		$this->db->query($sql);
		$num = $this->db->affected_rows();
		$this->setXmlNode('thread_info' , 'thread');
		if($num)
		{
			$this->addItem('地盘更新成功');
		}
		else
		{
			$this->addItem('地盘更新失败');	
		}
		$this->output(); 
	}
	
	/**
	 * 删除单条地盘记录
	 * 注：此方法已不用
	 */
	/*
	public function delete()
	{
		//删除地盘的ID
		$group_id = $this->input['id'] ? intval($this->input['id']) : -1;  
		
		if($video_id <= 0)
		{
			$this->errorOutput('未传入地盘ID');  
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "group WHERE group_id = " . $group_id ;
		$r = $this->db->query_first($sql);
				
		if($r && is_array($r))
		{			
			if(($r['fatherid'] != 0 ) || (!$r['fatherid'] && $r['is_last']))
			{
				//删除地盘
				$sql = "DELETE FROM " . DB_PREFIX . "group WHERE group_id = " . $group_id ;
				$this->db->query($sql);
				
				//更新地盘字段is_last
				$sql = "SELECT COUNT(*) AS child_nums FROM " . DB_PREFIX . "group WHERE fatherid = " . $r['fatherid'];
				$tmp = $this->db->query_first($sql);
				
				//将上级地盘标记为末级地盘
				if($tmp['child_nums'] == 0)
				{
					$sql = "UPDATE " . DB_PREFIX . "group SET is_last = 1 WHERE group_id = " . $r['fatherid'];
				}
				
				//删除地盘-帖子关联表中的数据
				$sql = "DELETE FROM " . DB_PREFIX . "group_thread WHERE group_id =" . $group_id ;  
				$this->db->query($del_gt_sql);
				
				//删除地盘成员表中的相关记录
				$sql = "DELETE FROM " . DB_PREFIX . "group_members WHERE group_id=" . $group_id;
				$r = $this->db->query($sql);
				
				$this->setXmlNode('group_info' , 'group');
				if($r)
				{
					$this->addItem('地盘删除成功');
				}
				else
				{
					$this->addItem('地盘删除失败');	
				}
				$this->output(); 	
			}
			else
			{
				$this->errorOutput('该地盘下含有子地盘，无法删除'); 			
			} 								
		}
		else
		{
			$this->errorOutput('地盘不存在'); 		
		}
	}*/
	
	/**
	 * 批量删除地盘数据
	 * 注：此方法已不用
	 */
	/*
	public function batch_delete()
	{
		//删除的地盘IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim($this->input['ids']));		
		$id_array = array();		
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		foreach($id_array as $k => $v)
		{
			if(!$v)
			{
				unset($id_array[$k]);
			}
		}
		
		$delete_id = implode(',' , $id_array);

		$sql = "SELECT * FROM " . DB_PREFIX . "group WHERE group_id IN ( $delete_id )";
 		$q = $this->db->query($sql);
 		
 		$tmp = array();
 		while(false != ($row = $this->db->fetch_array($q)))
 		{
 			//筛选叶子地盘
 			if(($row['fatherid'] != 0 ) || (!$row['fatherid'] && $row['is_last']))
 			{
 				$tmp[] = $row['group_id'];	
 			} 				
 		}
 		
 		//如果删除的含有叶子地盘
 		if($tmp && is_array($tmp))
 		{
 			$delete_ids = implode(',' , $tmp);
 			
 			//删除讨论区
			$sql = "DELETE FROM " . DB_PREFIX . "group WHERE group_id IN ( $delete_ids )";
			$this->db->query($sql);
			
			//删除圈子-帖子关联表中的数据
			$sql = "DELETE FROM " . DB_PREFIX . "group_thread WHERE group_id IN ( $delete_ids )";  
			$this->db->query($del_gt_sql);
			
			//删除讨论区成员表中的相关记录
			$sql = "DELETE FROM " . DB_PREFIX . "group_members WHERE group_id IN ( $delete_ids )";
			$this->db->query($sql);
 		}
	}
	*/
	
	/**
	 * 地盘数据批量审核
	 */
	public function audit()
	{
				
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['group_id'])));
						
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
				
		$sql = "UPDATE " . DB_PREFIX . "group SET state = 1 WHERE group_id IN (" . $verify_id . ")";	
		
		$r = $this->db->query($sql);

		$this->setXmlNode('group_info' , 'group');
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
	 * 地盘数据批量打回
	 */
	public function back()
	{
				
		//删除的视频IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['group_id'])));
						
		$id_array = explode(',' , $ids);
		
		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入审核ID');		
		}
		
		$verify_id = implode(',' , $id_array);
				
		$sql = "UPDATE " . DB_PREFIX . "group SET state = 0 WHERE group_id IN (" . $verify_id . ")";	
		
		$r = $this->db->query($sql);

		$this->setXmlNode('group_info' , 'group');
		if($r)
		{
			$this->addItem('批量打回成功');
		}
		else
		{
			$this->addItem('批量打回失败');	
		}
		$this->output(); 
	}
/**
	public function audit()
	{
		$id=urldecode($this->input['group_id']);
		$sql="update " . DB_PREFIX . "group set state=1 where group_id IN(" . $id . ")";
		$this->db->query($sql);
		return array('status' => 1,'id' => $id,'pubstatus' =>1);
	}
	public function  back()
	{
		$id=urldecode($this->input['group_id']);
		$sql="update " . DB_PREFIX . "group set state=0 where group_id IN(" . $id . ")";
		$this->db->query($sql);
		return array('status' => 0, 'id' => $id ,'pubstatus' =>0);
	}
	*/
	
	/**
	 * 地盘数据批量删除
	 */
	public function delete()
	{		
		//删除的地盘IDS(格式：'1,2,3,4,5')
		$ids = str_replace('，' , ',' , trim(urldecode($this->input['group_id'])));		
		
		$id_array = explode(',' , $ids);

		//过滤数组中的空值
		$id_array = array_filter($id_array);
		
		if(empty($id_array))
		{
			$this->errorOutput('未传入删除ID');		
		}
		$delete_id = implode(',' , $id_array);

		//放入回收箱开始
		//查找地盘信息
		$sql = "SELECT * FROM " . DB_PREFIX . "group WHERE group_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['group_id']] = array(
					'delete_people' => trim(urldecode($this->user['user_name'])),
					'title' => $row['name'],
					'cid' => $row['group_id'],
			);
			$data2[$row['group_id']]['content']['group'] = $row;
		}
		//查找地盘成员信息
		$sql = "SELECT * FROM " . DB_PREFIX . "group_members WHERE group_id IN (" . $delete_id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data2[$row['group_id']]['content']['group_members'][] = $row;
		}
		
		//放入回收站
		foreach($data2 as $key => $value)
		{
			$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
		}
		//放入回收站结束
		$sql = "DELETE FROM " . DB_PREFIX . "group WHERE group_id IN (" . $delete_id . ")";

		$r = $this->db->query($sql);

		$sql = "DELETE FROM " . DB_PREFIX . "group_members WHERE group_id IN (" . $delete_id . ")";
		$r = $this->db->query($sql);
		
		$this->setXmlNode('group_info' , 'group');
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
		//还原地盘
		if(!empty($content['group']))
		{
			$sql = "insert into " . DB_PREFIX . "group set ";
			$space='';
			foreach($content['group'] as $k => $v)
			{
                $sql .= $space . $k . "='" . $v . "'";
				$space=',';
			}
			$this->db->query($sql);
		}

		//还原地盘成员
		if(!empty($content['group_members']))
		{
			
			foreach($content['group_members'] as $k => $v)
			{
				$sql = "insert into " . DB_PREFIX . "group_members set ";
				$space='';
				if(is_array($v) && count($v)>0)
				{
					foreach($v as $kk => $vv)
					{
						$sql .= $space . $kk . "='" . $vv . "'";
						$space=',';
					}
					$this->db->query($sql);
				}  
			}
		}
		return $data;
	}*/
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
$out = new groupUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();

?>