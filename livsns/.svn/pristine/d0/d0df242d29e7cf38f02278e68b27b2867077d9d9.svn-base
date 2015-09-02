<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status.php 4079 2011-06-16 08:29:10Z develop_tong $
***************************************************************************/
class media_data
{
	var $db;
	var $settings;
	function __construct() 
	{
		global $gDB;
		$this->db = $gDB;
	}
	function __destruct() 
	{
	}
	/**
		获取微博
	*/
	public function media($condition, $offset = 0, $count = 500) 
	{
		$offset = intval($offset);
		$count = intval($count);
		$offset = $offset ? $offset : 0;
		$count = $count ? $count : 20;
			
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'media
					WHERE 1 ' . $condition . ' 
					ORDER BY create_at DESC 
					LIMIT ' . $offset . ',' . $count;
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['create_at'] = date('Y-m-d H:i:s', $r['create_at']);
			$data[$r['status_id']][] = $r;
		}
		return $data;
	}
		
	/**
		取出总的微博记录数
	*/
	function count($condition)
	{
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'media 
					WHERE 1 ' . $condition;
		$r = $this->db->query_first($sql);
		return intval($r['total']);
	}
}
?>