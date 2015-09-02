<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: news.class.php 13450 2012-11-02 02:45:59Z wangleyuan $
***************************************************************************/
class video extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($cond)
	{
		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE state = 1 " . $cond;
		$q = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['object'] = stripslashes($row['object']);
			$info[] = $row;
		}
		return $info;
	}
	
	public function create()
	{
		$info = array(
			'url' => trim($this->input['url']),
			'source'=> $this->input['source'],
			'sid' => intval($this->input['sid']),
			'ip' => hg_getip(),
			'create_time' => TIMENOW,
		);
		if(empty($info['url']))
		{
			return false;
		}
		include_once(ROOT_PATH . 'lib/class/videoUrlParser.class.php');
		$obj = new VideoUrlParser();
		$tmp = $obj->parse($info['url']);
		if(empty($tmp))
		{
			return false;
		}
		$info['title'] = $tmp['title'];
		$info['img'] = $tmp['img'];
		$info['url'] = $tmp['url'];
		$info['swf'] = $tmp['swf'];
		$info['object'] =  addslashes($tmp['object']);
		$sql = "INSERT INTO " . DB_PREFIX . "video SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		//echo $sql;exit;
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		return array(
			'img' => $info['img'],
			'title' => $info['title'],
			'url' => $info['url'],
			'id' => $info['id'],
		);
	}
	
	public function update()
	{
		$info = array(
			'url' => trim($this->input['url']),
			'source'=> $this->input['source'],
			'sid' => intval($this->input['sid']),
			'ip' => hg_getip(),
			'create_time' => TIME_NOW,
		);
		if(empty($info['url']) || empty($this->input['id']))
		{
			return false;
		}
		include_once(ROOT_PATH . 'lib/class/videoUrlParser.class.php');
		$obj = new VideoUrlParser();
		$tmp = $obj->parse($info['url']);
		if(empty($tmp))
		{
			return false;
		}
		$info['title'] = $tmp['title'];
		$info['img'] = $tmp['img'];
		$info['url'] = $tmp['url'];
		$info['swf'] = $tmp['swf'];
		$info['object'] =  addslashes($tmp['object']);
		$sql = "UPDATE " . DB_PREFIX . "video SET ";
		$space = "";
		foreach($info as $k => $v)
		{
			$sql .= $space . $k . "='" . $v . "'";
			$space = ',';
		}
		$sql .= " WHERE id=" . intval($this->input['id']);
		$this->db->query($sql);
		return array(
			'img' => $info['img'],
			'title' => $info['title'],
			'url' => $info['url'],
			'id' => $info['id'],
		);
	}
	
	public function delete($id,$source)
	{
		if(!$id || !$source)
		{
			return false;
		}
		$sql = "DELETE FROM " . DB_PREFIX . "video WHERE sid=" . $sid . " AND source='" . $source . "'";
		$this->db->query($sql);
		return true;
	}
	
	public function count($cond)
	{
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "video WHERE 1 " . $cond; 
		$f = $this->db->query_first($sql);
		return $f;
	}
	
	public function detail()
	{
		
	}
	
	/**
	 * 获取指定来源的视频信息
	 * @param String $source
	 * @param String $topic_id
	 */
	public function get_video_info($source, $topic_id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'video WHERE source = "' . $source . '" 
		AND sid in (' . $topic_id . ') AND state = 1';
		$query = $this->db->query($sql);
		$info = array();
		while($row = $this->db->fetch_array($query))
		{ 	
			$row['object'] = stripslashes($row['object']);
			$info[] = $row;
		}
		return $info;
	}
}

?>