<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status.php 4079 2011-06-16 08:29:10Z develop_tong $
***************************************************************************/
class status_data
{
	var $db;
	var $media;
	function __construct() 
	{
		global $gDB,$gGlobalConfig;
		$this->db = $gDB;
		$this->settings = $gGlobalConfig;
		$this->media = new media_data();
		$this->mUser = new user();
	}
	function __destruct() 
	{
	}
	
	public function detail($id = 0)
	{
		if ($id)
		{
			$condition = ' WHERE s.id IN(' . $id . ')';
		}
		else
		{
			$condition = ' ORDER BY s.id DESC LIMIT 1';
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status s 
						LEFT JOIN ' . DB_PREFIX . 'status_extra se
							ON s.id=se.status_id
							' . $condition;
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['create_at'] = date('Y-m-d H:i:s', $r['create_at']);
			$r['pic'] = $r['pic'] ? '图' : '';
			$r['video'] = $r['video'] ? '视频' : '';
		
			if($this->settings['rewrite'])
			{
				$r['status_link'] = SNS_MBLOG . "status-".$r['id'].".html";	
			}
			else 
			{
				$r['status_link'] = SNS_MBLOG . 'show.php?id=' . $r['id'];
			}
			$r['column_id'] = unserialize($r['column_id']);
			if(is_array($r['column_id']))
			{
				$column_id = array();
				foreach($r['column_id'] as $k => $v)
				{
					$column_id[] = $k;
				}
				$column_id = implode(',',$column_id);
				$r['column_id'] = $column_id;
			}
			//$r['text'] = hg_verify($r['text']);
			$status_info[$r['id']] = $r;
		}
		return $status_info;
	}
	/**
		获取微博
	*/
	public function status($condition, $orderby = 's.create_at DESC', $offset = 0, $count = 20) 
	{
		$offset = intval($offset);
		$count = intval($count);
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 20;
		if (!$orderby)
		{
			$orderby = 's.create_at DESC';
		}
		$sql = 'SELECT *, (transmit_count + reply_count) AS transmit_count FROM ' . DB_PREFIX . 'status s 
						LEFT JOIN ' . DB_PREFIX . 'status_extra se
							ON s.id=se.status_id
					WHERE 1 ' . $condition . ' 
					ORDER BY  ' . $orderby . '
					LIMIT ' . $offset . ',' . $count;
		$q = $this->db->query($sql);
		$status_info = array();
		$trans_status = array();
		$member_ids = array();
		$status_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_at'] = date('Y-m-d H:i:s', $r['create_at']);
			if ($r['status'] == 0)
			{
				$r['audit'] = 1;
				$r['status_show'] = '通过';
			}
			else
			{
				$r['audit'] = 0;
				$r['status_show'] = '不通过';
			}
			if($this->settings['rewrite'])
			{
				$r['status_link'] = SNS_MBLOG . "status-".$r['id'].".html";	
			}
			else 
			{
				$r['status_link'] = SNS_MBLOG . 'show.php?id=' . $r['id'];
			}
			$r['text'] = hg_verify($r['text']);
			$status_info[$r['id']] = $r;
			$member_ids[] = $r['member_id'];
			$status_ids[] = $r['id'];
			if ($r['reply_status_id'])
			{
				$status_ids[] = $r['reply_status_id'];
				$trans_status[$r['id']] = $r['reply_status_id'];
				$member_ids[] = $r['reply_user_id'];
			}
		}
		//获取转发信息
		if ($trans_status)
		{
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'status s 
							LEFT JOIN ' . DB_PREFIX . 'status_extra se
								ON s.id=se.status_id
						WHERE id IN ( ' . implode(',', $trans_status) . ' )';
			$q = $this->db->query($sql);
			$trans_status = array();
			while($r = $this->db->fetch_array($q))
			{
				$r['create_at'] = date('Y-m-d H:i:s', $r['create_at']);
				if($this->settings['rewrite'])
				{
					$r['status_link'] = SNS_MBLOG . "status-".$r['id'].".html";	
				}
				else 
				{
					$r['status_link'] = SNS_MBLOG . 'show.php?id=' . $r['id'];
				}
				$r['text'] = hg_verify($r['text']);
				if ($r['status'] == 0)
				{
					$r['audit'] = 1;
					$r['status_show'] = '通过';
				}
				else
				{
					$r['audit'] = 0;
					$r['status_show'] = '不通过';
				}
				$trans_status[$r['id']] = $r;
			}
		}
		//获取媒体信息
		if ($status_ids)
		{
			$condition = ' AND 	status_id IN (' . implode(',', $status_ids) . ')';
			$medias = $this->media->media($condition);
		}
		//获取用户信息
		if ($member_ids)
		{
			$userinfo = $this->mUser->getUserById(implode(',', array_unique($member_ids)));
			$members = array();
			if ($userinfo)
			{
				foreach ($userinfo AS $user)
				{
					$members[$user['id']] = $user;
				}
			}
		}
		//合并微博信息
		if ($status_info)
		{
			foreach ($status_info AS $k => $v)
			{
				$status_info[$k]['user'] = $members[$v['member_id']];//合并用户
				if ($v['reply_status_id'])
				{
					if ($trans_status[$v['reply_status_id']])
					{
						$trans = $trans_status[$v['reply_status_id']];
					}
					else
					{
						$trans = array(
							'id' => 	0,
							'text' => '该信息已删除',
						);
					}
					$trans['user'] = $members[$v['reply_user_id']]; //合并转发用户
					$trans['medias'] = $medias[$v['reply_status_id']];//合并转发媒体信息
					$status_info[$k]['trans'] = $trans; //合并转发
				}
				$status_info[$k]['medias'] = $medias[$k];//合并媒体信息
			}
		}
		return $status_info;
	}
		
	/**
		取出总的微博记录数
	*/
	function count($condition)
	{
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'status s 
						LEFT JOIN ' . DB_PREFIX . 'status_extra se
							ON s.id=se.status_id
					WHERE 1 ' . $condition;
		$r = $this->db->query_first($sql);
		return intval($r['total']);
	}
}
?>