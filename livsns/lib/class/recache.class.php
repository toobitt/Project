<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: recache.class.php 1524 2011-01-04 09:46:16Z yuna $
***************************************************************************/

/**
 * 
 * 从memcache获取数据,不存在将重建缓存
 * 
 */
class recache
{
	var $memcache;
	var $db;
	
	function __construct()
	{
		global $gMemcache, $gDB;
		$this->memcache = $gMemcache;
		$this->db = $gDB;
	}
	
	function __destruct()
	{
	}
	
	/**
	 * 
	 * 重建用户关注缓存
	 */
	public function friends_recache($args = array())
	{
		$id = intval($args[0]);
		if (!$id)
		{
			return false;
		}
		$data = array($id);
		
		$sql = "SELECT fmember_id FROM " . DB_PREFIX . "member_relation WHERE member_id = " . $id ;		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data[] = $row['fmember_id']; 		
		}
	    $ids_str = implode(',' , $data);  
	    $this->memcache->set(FRIENDS_MEM_PRE . $id , $ids_str); //更新memcache
	}
	
	/**
	 * 
	 * 重建用户粉丝缓存
	 */
	public function followers_recache($args = array())
	{
		$id = intval($args[0]);
		if (!$id)
		{
			return false;
		}
		$data = array($id);
		
		$sql = "SELECT member_id FROM " . DB_PREFIX . "member_relation WHERE fmember_id = " . $id ;		
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$data[] = $row['member_id']; 		
		}
	    $ids_str = implode(',' , $data);	    	    
	    $this->memcache->set(FOLLOWERS_MEM_PRE . $id , $ids_str); //更新memcache
	}
	
	/**
	 * 重建用户黑名单缓存
	 */
	public function blockers_recache($args = array())
	{
		$id = intval($args[0]);
		$sql = "SELECT bmemberid FROM " . DB_PREFIX . "member_block WHERE member_id = " . $id;
		
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$data[] = $row['bmemberid']; 		
		}
		$ids_str = implode(',' , $data);
		$this->memcache->set('blockers' . $id , $ids_str);      //更新memcache
	}
	
	/**
	 * 重建“个性模板”分类缓存
	 * 
	 */
//	public function style_sort_recache($args = array())
//	{
//
//	}
}
?>