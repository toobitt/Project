<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: album.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class albumApi extends adminBase
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/video.class.php');
		$this->mVideo = new video();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 获取用户的专辑
	* @param $user_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$user_id = intval(trim($this->input['user_id']))? intval(trim($this->input['user_id'])):$mInfo['id'];	
				
		$page = $this->input['page'] ? $this->input['page'] : 0;
		$count = intval($this->input['count'])?intval($this->input['count']):20;		
		$offset = $page * $count;
		
		$end = " LIMIT ".$offset.",".$count;
		$sql = "SELECT * FROM " . DB_PREFIX . "album WHERE user_id = " . $user_id.$end;
		
		$q = $this->db->query($sql);

		$album_array = array();
		
		$cover_id = "";
		$space = " ";
		while($row  = $this->db->fetch_array($q))
		{
			$row['create_time'] = date("Y-m-d",$row['create_time']);
			$row['update_time'] = date("Y-m-d",$row['update_time']);
			$album_video[] = $row;
			$cover_id .= $space.$row['cover_id'];
			$space = ",";
		}
		
		if(!$album_video)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$arr_cover = array_unique(explode(',', trim($cover_id)));
		
		$cover_id = implode(",",$arr_cover);
		$sql = "SELECT id,schematic FROM ".DB_PREFIX."video WHERE id IN(".$cover_id.")";
		$query = $this->db->query($sql);
		while($arr = $this->db->fetch_array($query))
		{
			$cover[$arr['id']] = $arr;
		}
		
		foreach($album_video as $key => $value)
		{
			$album_video[$key]['cover'] = hg_video_image($cover[$value['cover_id']]['id'],$cover[$value['cover_id']]['schematic'],0);
		}

		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "album WHERE user_id = " . $user_id;
		$t = $this->db->query_first($sql);
		$album_video['total'] = $t['total'];
		
		$this->setXmlNode('album_info' , 'album');
		$this->addItem($album_video);
		$this->output();
	}

	
	/**
	* 创建专辑
	* @param $name
	* @param $brief
	* @param $sort_id
	* @return $ret 专辑信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$video_id = rtrim(urldecode($this->input['video_id']?$this->input['video_id']:""),',');
		
		$arr_video = array_unique(explode(',', $video_id));
		sort($arr_video);
		
		$info = array(
			'user_id' => $mInfo['id'],
			'cover_id' => $arr_video[0],
			'name' => urldecode($this->input['name']?$this->input['name']:""),
			'brief' => urldecode($this->input['brief']?$this->input['brief']:""),
			'sort_id' => ($this->input['sort_id']?$this->input['sort_id']:0),
			'create_time' => time(),
			'update_time' => time(),
			'video_count' => count($arr_video),
		);
		if(!$info['user_id']&&!$info['name']&&!$info['sort_id']&&!$arr_video)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "INSERT INTO ".DB_PREFIX."album SET ";
		
		$con = "";
		$space = "";
		foreach($info as $key=>$value)
		{
			$con .= $space.$key."='".$value."'";
			$space = ", ";
		}
		$sql = $sql.$con;
		$this->db->query($sql);
		$info['id'] = $this->db->insert_id();
		
		$sql = "INSERT INTO ".DB_PREFIX."album_video(album_id,video_id) VALUES";
		
		$con = "";
		$space = "";
		foreach($arr_video as $k1 =>$v1)
		{
			$con .= $space."('".$info['id']."','".$v1."')";
			$space = ",";
		}
		$sql = $sql.$con;
		$this->db->query($sql);		
		$info['create_time'] = date("Y:m:d H:i:s",$info['create_time']);
		$info['update_time'] = date("Y:m:d H:i:s",$info['update_time']);
		$this->setXmlNode('albums','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 修改专辑
	* @param $album_info array 修改的专辑信息 
	* @return $ret 专辑信息
	*/
	public function edit(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$sql = "SELECT * FROM ".DB_PREFIX."album_video WHERE album_id =".$album_id;
		$query = $this->db->query($sql);
		$video_id = "";
		$space = " ";
		while($row = $this->db->fetch_array($query))
		{
			$video_id .= $space.$row['video_id'];
			$space = ",";
		}
		$video_id_n = rtrim(urldecode($this->input['video_id_n']?$this->input['video_id_n']:""),",");
		
		
		$arr_video = array_unique(explode(',', trim($video_id)));
		$arr_video_n = array_unique(explode(',', $video_id_n));
		sort($arr_video);
		sort($arr_video_n);
		$same = array_intersect($arr_video, $arr_video_n);
		if($same)
		{
			$arr_video_n = array_diff($arr_video_n,$same);
		}
		
		
		$info = array(
			'user_id' => $mInfo['id'],
			'name' => urldecode($this->input['name']?$this->input['name']:""),
			'brief' => urldecode($this->input['brief']?$this->input['brief']:""),
			'sort_id' => ($this->input['sort_id']?$this->input['sort_id']:0),
			'update_time' => time(),
		);
		
		$sql = "UPDATE  ".DB_PREFIX."album SET ";
		$con = "";
		$space = " ";
		foreach($info as $key=>$value)
		{
			$con .= $space.$key."='".$value."'";
			$space = ",";
		}
		$sql = $sql.$con.",video_count =video_count+".count($arr_video_n)."  WHERE id=".$album_id;
		$this->db->query($sql);
		
		$info['id'] = $album_id;
		$sqls = "INSERT INTO ".DB_PREFIX."album_video(album_id,video_id) VALUES";
		
		if(count($arr_video_n))
		{
			$cons = "";
			$spaces = " ";
			foreach($arr_video_n as $k1 =>$v1)
			{
				$cons .= $spaces."('".$info['id']."','".$v1."')";
				$spaces = ",";
			}
			$sqls .= $cons;
			$this->db->query($sqls);
		}
		$this->setXmlNode('albums','info');
		$this->addItem($info);
		$this->output();
	}
	
	
	/**
	* 修改封面
	* @param $album_id 
	* @param $video_id 
	* @return $album_id 专辑ID
	*/
	public function edit_cover(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$video_id = $this->input['video_id']? $this->input['video_id']:0;
		if(!$album_id&&!$video_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "UPDATE  ".DB_PREFIX."album SET cover_id = ".$video_id." WHERE id =".$album_id;
		$this->db->query($sql);
		$this->setXmlNode('albums','info');
		$this->addItem($album_id);
		$this->output();
	}
	
	/**
	* 根据专辑ID获取专辑信息
	* @param $album_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_album()
	{
		$album_id = urldecode($this->input['album_id']? $this->input['album_id']:0);
		$page = $this->input['page'] ? $this->input['page'] : 0;
		$count = intval($this->input['count'])?intval($this->input['count']):0;		
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."album WHERE id IN(".$album_id.")";
		$q = $this->db->query($sql);
		$space = " ";
		$cover_id = "";
		while($row = $this->db->fetch_array($q))
		{
			$album[] = $row;
			$cover_id .= $space.$row['cover_id'];
			$space = ",";
		}
		
		$sql = "SELECT id,schematic FROM ".DB_PREFIX."video WHERE id IN(".$cover_id.")";
		$query = $this->db->query($sql);
		while($arr = $this->db->fetch_array($query))
		{
			$cover[$arr['id']] = $arr;
		}
		foreach($album as $key => $value)
		{
			$album[$key]['cover'] = hg_video_image($cover[$value['cover_id']]['id'],$cover[$value['cover_id']]['schematic'],0);
		}
		
		$q = $this->db->query($sql);
		$this->setXmlNode('albums','info');
		foreach ($album as $k => $v)
		{
			$this->addItem($v);	
		}
		$this->output();
	}

	/**
	* 根据专辑名称获取专辑信息
	* @param $album_name
	* @param $user_id
	* @return $ret 专辑信息
	*/
	public function getAlbumByName()
	{
		$album_name = urldecode(trim($this->input['album_name']? $this->input['album_name']:''));
		$user_id = $this->input['user_id']? $this->input['user_id']:0;
		$album_name = explode(",", $album_name);
		foreach ($album_name as $key => $value)
		{
			$album_names[$key] = "'".$value."'";					
		}
		$album_names = implode(",",$album_names);
		$sql = "SELECT * FROM ".DB_PREFIX."album WHERE name IN(".$album_names.") AND user_id=".$user_id." ORDER BY create_time DESC";
		
		
		$q = $this->db->query($sql);
		$space = " ";
		$cover_id = "";
		while($row = $this->db->fetch_array($q))
		{
			$album[] = $row;
			$cover_id .= $space.$row['cover_id'];
			$space = ",";
		}
		if(!$cover_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "SELECT id,schematic FROM ".DB_PREFIX."video WHERE id IN(".$cover_id.")";
		$query = $this->db->query($sql);
		while($arr = $this->db->fetch_array($query))
		{
			$cover[$arr['id']] = $arr;
		}
		foreach($album as $key => $value)
		{
			$album[$key]['cover'] = hg_video_image($cover[$value['cover_id']]['id'],$cover[$value['cover_id']]['schematic'],0);
		}
		$q = $this->db->query($sql);
		$this->setXmlNode('albums','info');
		foreach ($album as $k => $v)
		{
			$this->addItem($v);	
		}
		$this->output();
		
	}
	/**
	* 移除视频
	* @param $id 
	* @param $album_id 用于减去专辑表中是视频数
	* @return $id 关系ID
	*/
	public function del_album_video(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$id = urldecode($this->input['id']? $this->input['id']:0);
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		if(!$id&&!$album_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."album_video WHERE album_id= ".$album_id." AND video_id IN(".$id.")";
		$this->db->query($sql);
		$count = count(explode(",", $id));
		$sql = "UPDATE ".DB_PREFIX."album SET video_count = video_count-".$count." WHERE id =".$album_id;
		$this->db->query($sql);
		$this->setXmlNode('albums','info');
		$this->addItem($id);
		$this->output();
	}
	
	/**
	* 获得专辑中的视频
	* @param $album_id
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function get_video(){
		$mInfo = $this->mUser->verify_credentials();
		$album_id = $this->input['album_id']? $this->input['album_id']:1;
		if(!$album_id&&!$mInfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$page = $this->input['page'] ? $this->input['page'] : 0;
		$count = intval($this->input['count'])?intval($this->input['count']):0;		
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		$sql = "SELECT v.id as relation_id,v.album_id,v.video_id,a.* FROM ".DB_PREFIX."album a 
		LEFT JOIN ".DB_PREFIX."album_video v
		ON a.id = v.album_id 
		WHERE v.album_id =".$album_id;
		$sql = "SELECT * FROM ".DB_PREFIX."album WHERE id=".$album_id;
		$album = $this->db->query_first($sql);
		
		$sql = "SELECT id as relation_id,album_id,video_id FROM ".DB_PREFIX."album_video WHERE album_id=".$album_id;

		$query = $this->db->query($sql);
		$video_id = "";
		$user_id = "";
		$space = " ";
		$relation = array();
		while($arr = $this->db->fetch_array($query))
		{
			$relation[$arr['video_id']] = $arr['relation_id'];
			$video_id .= $space.$arr['video_id'];
			$space = ",";
		}
		$album_video = $album;
		if($video_id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."video WHERE id IN (".trim($video_id).") ORDER BY create_time DESC ".$end;
			$query = $this->db->query($sql);
			$space = " ";
			while($arr = $this->db->fetch_array($query))
			{
				$arr = hg_video_image($arr['id'], $arr);
				$user_id .= $space.$arr['user_id'];
				$arr['relation_id'] = $relation[$arr['id']];
				$album_video['video'][$arr['id']] =  $arr;
				$space = ",";
			}
			
			$user_info = $this->mVideo->getUserById($user_id);
			foreach($album_video['video'] as $key => $value)
			{
				$album_video['video'][$key]['user'] = $user_info[$value['user_id']];
				$album_video['video'][$key]['create_time'] = date("Y-m-d",$value['create_time']);
				$album_video['video'][$key]['update_time'] = date("Y-m-d",$value['update_time']);
				unset($album_video['video'][$key]['user_id']);
			}
			
			if($count)
			{
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."video WHERE id IN (".trim($video_id).")";
				$first = $this->db->query_first($sql);
				$album_video['video']['total'] = $first['total'];
			}
		}
		$this->setXmlNode('albums','info');
		$this->addItem($album_video);
		$this->output();
	}
	

	/**
	* 删除专辑（包括关联表中的信息）
	* @param $album_id
	* @return $album_id 专辑ID
	*/
	public function del_album(){
		$mInfo = $this->mUser->verify_credentials();
		$album_id = $this->input['album_id']? $this->input['album_id']:2;
		if(!$album_id&&!$mInfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}

		$sql = "DELETE FROM ".DB_PREFIX."album WHERE id=".$album_id;
		$this->db->query($sql);
		
		$sql = "DELETE FROM ".DB_PREFIX."album_video WHERE album_id=".$album_id;
		$this->db->query($sql);
		
		$this->setXmlNode('albums','info');
		$this->addItem($album_id);
		$this->output();
	}
	

	/**
	* 转移专辑中的视频（包括关联表中的信息）
	* @param $album_id（是当前专辑ID）
	* @param $album_id_n （是转移之后的专辑ID）
	* @param $video_id（需要转移的视频ID）
	* @return $album_id 专辑ID
	*/
	public function move_album_video(){
		$mInfo = $this->mUser->verify_credentials();
		$video_id = urldecode($this->input['video_id']? $this->input['video_id']:0);
		$album_id = $this->input['album_id']? $this->input['album_id']:0;
		$album_id_n = $this->input['album_id_n']? $this->input['album_id_n']:0;
		if(!$album_id&&!$mInfo&&!$video_id&&!$album_id_n)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$video = explode(",", $video_id);
		$count = count($video);
		$sql = "DELETE FROM ".DB_PREFIX."album_video WHERE album_id = ".$album_id." AND video_id IN(".trim($video_id).")";
		$this->db->query($sql);
		$sql = "UPDATE ".DB_PREFIX."album SET video_count = video_count-".$count." WHERE id =".$album_id;
		$this->db->query($sql);
		
		
		$sql = "SELECT * FROM ".DB_PREFIX."album_video WHERE album_id=".$album_id_n;
		$q = $this->db->query($sql);
		
		$video_ids = "";
		$space = " ";
		$video_count = 1;
		while($row = $this->db->fetch_array($q))
		{
			$video_ids .= $space.$row['video_id'];
			$space = ",";
			$video_count ++;
		}
		
		$arr_video = array_unique(explode(',', trim($video_ids)));
		$arr_video_n = array_unique($video);
		sort($arr_video);
		sort($arr_video_n);
		$same = array_intersect($arr_video, $arr_video_n);
		if($same)
		{
			$arr_video_n = array_diff($arr_video_n,$same);
		}

		
		$sqls = "INSERT INTO ".DB_PREFIX."album_video(album_id,video_id) VALUES";
	
		if(count($arr_video_n))
		{
			$cons = "";
			$spaces = " ";
			foreach($arr_video_n as $k1 =>$v1)
			{
				$cons .= $spaces."('".$album_id_n."','".$v1."')";
				$spaces = ",";
			}
			$sqls .= $cons;
			
			$this->db->query($sqls);
			$sql = "UPDATE ".DB_PREFIX."album SET video_count = ".$video_count." WHERE id =".$album_id_n;
			$this->db->query($sql);
		}
				
		$this->setXmlNode('albums','info');
		$this->addItem($album_id);
		$this->output();
	}
	

	/**
	 * 修改专辑的收藏数目
	 * @param $video_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function favorite_count()
	{
		$mInfo = $this->mUser->verify_credentials();
		$album_id = $this->input['album_id']? $this->input['album_id']:3;
		$type = $this->input['type']? $this->input['type']:1;//默认增加
		
		if(!$mInfo&&!$album_id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "album SET collect_count=";
		if($type)
		{
			$sql .="collect_count+1";
		}
		else 
		{
			$sql .="collect_count-1";
		}
		
		$sql .= " WHERE id = ".$album_id;
		$this->db->query($sql);
		$this->setXmlNode('album' , 'info');
		$this->addItem($album_id);
		$this->output();
	}
}

$out = new albumApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'favorite_count';
}
$out->$action();
?>