<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: visit.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class visitApi extends adminBase
{
	private $mVideo;
	private $mUser;
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 获取某个对象的用户访问记录
	* @param $cid
	* @param $type(1-视频，2-网台)
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$cid = $this->input['cid']? $this->input['cid']:6;
		$type = $this->input['type']? $this->input['type']:1;
		
		$page = $this->input['page'] ? $this->input['page'] : 0;
		$count = intval($this->input['count'])?intval($this->input['count']):10;		
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT ".$offset.",".$count;
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."visit_history WHERE cid=".$cid." AND type=".$type." ORDER BY visit_time DESC ".$end;
		$q = $this->db->query($sql);
		$user_id = "";
		while($row = $this->db->fetch_array($q))
		{
			$user_id .= $row['user_id'].",";
			$info[] = $row;
		}
		
		if(is_array($info))
		{
			include_once(ROOT_PATH . '/api/lib/video.class.php');
			$this->mVideo = new video();
			$user = $this->mVideo->getUserById($user_id);
			foreach($info as $key => $value)
			{
				$info[$key]['user'] = $user[$value['user_id']];
			}
			
			if($count)
			{
				$sql = "SELECT COUNT(*) as total FROM ".DB_PREFIX."visit_history WHERE cid=".$cid." AND type=".$type;
				$f = $this->db->query_first($sql);
				$info['total'] = $f['total'];
			}
		}
		
		$this->setXmlNode('visit_info' , 'visit');
		$this->addItem($info);
		$this->output();
	}

	
	/**
	* 增加访问记录
	* @param $user_id //访问的用户
	* @param $cid
	* @param $type(1-视频，2-网台)
	* @return $ret 信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();

		$user_id = $this->input['user_id']? $this->input['user_id']:0;
		$cid = $this->input['cid']? $this->input['cid']:0;
		$type = $this->input['type']? $this->input['type']:1;
		if(!$user_id&&!$cid)
		{
		  $this->errorOutput(OBJECT_NULL);
		}

		$info = array(
			"user_id" => $mInfo['id'],	
			"cid" => $cid,	
			"type" => $type,	
			"visit_time" => time(),	
			"ip" => hg_getip(),	
		);
		$sqls = "SELECT * FROM ".DB_PREFIX."visit_history WHERE user_id = ".$info["user_id"]." AND type=".$info['type']." AND cid=".$info['cid'];
		$first = $this->db->query_first($sqls);
		if(is_array($first)&&$first)
		{
				$sql = "UPDATE ".DB_PREFIX."visit_history SET visit_time=".$info['visit_time']." 
		WHERE user_id = ".$info["user_id"]." AND type=".$info['type']." AND cid=".$info['cid'];
				$this->db->query($sql);
		}
		else 
		{
			if($mInfo['id'])
			{
				$sql = "INSERT IGNORE INTO ".DB_PREFIX."visit_history SET ";
				$con = "";
				$space = "";
				foreach($info as $key=>$value)
				{
					$con .= $space.$key."='".$value."'";
					$space = ", ";
				}
				$sql = $sql.$con;
				
				switch ($info['type'])
				{
					case 1:
						if($user_id != $mInfo['id'])
						{
							$this->db->query($sql);
						}
						break;
					case 2:
						if($user_id != $mInfo['id'])
						{
							$this->db->query($sql);
						}
						break;
					case 3:
							$this->db->query($sql);
						break;
					default:
						break;
				}
			}
		}
		
		$this->setXmlNode('albums','info');
		$this->addItem($info);
		$this->output();
	}
	
}

$out = new visitApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>