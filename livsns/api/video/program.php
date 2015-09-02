<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class programApi extends adminBase
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
	 * 根据条件来查询多个节目单信息
	 * @return $info 节目单信息
	 */
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		$sta_id = $this->input['sta_id']?urldecode($this->input['sta_id']):0;
		$program_id = $this->input['program_id']?urldecode($this->input['program_id']):0;
		$user_id = $this->input['user_id']?urldecode($this->input['user_id']):0;
		$cond = " 1 ";
		
		if($user_id)
		{
			$cond .= " AND user_id IN(".$user_id.")";
		}
		
		if($sta_id)
		{
			$cond .= " AND sta_id IN(".$sta_id.")";
		}
		
		if($program_id)
		{
			$cond .= " AND id IN (".$program_id.")";
		}
		
		if(!$user_id&&!$sta_id&&!$program_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE ".$cond." ORDER BY update_time DESC";
		$query = $this->db->query($sql);
		$video_id = "";
		$space = " ";
		while($array = $this->db->fetch_array($query))
		{
			$program[] = $array;
			$video_id .= $space.$array['video_id'];
			$space = ",";
		}
		
		$program = hg_check_time($program);
		if($video_id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."video WHERE id IN(".$video_id.")";
			$query = $this->db->query($sql);
			while($array = $this->db->fetch_array($query))
			{
				$array = hg_video_image($array['id'], $array);
				$video[$array['id']] = $array;
			}
		}
		$re = $this->mVideo->get_collect_relevance($mInfo['id'],$video_id,0);
		foreach($program as $key=>$value)
		{
			if($video_id)
			{
				$program[$key]['video'] = $video[$value['video_id']];
			}
			$program[$key]['video']['relation'] = $re[$value['video_id']]['relation'];
			$program[$key]['video']['collect_id'] = $re[$value['video_id']]['id'];
		}
		
		$this->setXmlNode('user','info');
		$this->addItem($program);
		$this->output();	
	}
	
	

	/**
	 * 根据条件来查询多个节目单信息
	 * @return $info 节目单信息
	 */
	function shows(){
		$mInfo = $this->mUser->verify_credentials();
		$sta_id = $this->input['sta_id']?urldecode($this->input['sta_id']):0;
		$program_id = $this->input['program_id']?urldecode($this->input['program_id']):0;
		$user_id = $this->input['user_id']?urldecode($this->input['user_id']):0;
		$cond = " 1 ";
		
		if($user_id)
		{
			$cond .= " AND user_id IN(".$user_id.")";
		}
		
		if($sta_id)
		{
			$cond .= " AND sta_id IN(".$sta_id.")";
		}
		
		if($program_id)
		{
			$cond .= " AND id IN (".$program_id.")";
		}
		
		if(!$user_id && !$sta_id && !$program_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE ".$cond." ORDER BY order_id DESC";
		$query = $this->db->query($sql);
		$video_id = "";
		$space = " ";
		while($array = $this->db->fetch_array($query))
		{
			$program[] = $array;
			$video_id .= $space.$array['video_id'];
			$space = ",";
		}
		
		$program = hg_check_time($program);
		if($video_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id IN(" . $video_id . ")";
			$query = $this->db->query($sql);
			while($array = $this->db->fetch_array($query))
			{
				if($array['images'])
				{
					$array = hg_video_image($array['id'], $array);
				}
				$video[$array['id']] = $array;
			}
		}

		$re = $this->mVideo->get_collect_relevance($mInfo['id'],$video_id,0);
		foreach($program as $key=>$value)
		{
			if($video_id)
			{
				$program[$key]['video'] = $video[$value['video_id']];
			}
			$program[$key]['video']['relation'] = $re[$value['video_id']]['relation'];
			$program[$key]['video']['collect_id'] = $re[$value['video_id']]['id'];
		}
		
		$this->setXmlNode('user','info');
		$this->addItem($program);
		$this->output();	
	}
	
	/**
	* 增加节目单
	* @return $info 节目单信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}

		$info = array(
			'user_id' => $mInfo['id'],
			'sta_id' => $this->input['sta_id'] ? $this->input['sta_id'] : 0,
			'video_id' => $this->input['video_id'] ? $this->input['video_id'] : 0,
			'programe_name' => hg_filter_chars(urldecode($this->input['program_name'])),
			'brief' => hg_filter_chars(urldecode($this->input['brief'])),
			'start_time' => $this->input['start_time'] ? $this->input['start_time'] : 0,
			'end_time' => $this->input['end_time'] ? $this->input['end_time'] : 0,
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
			'order_id' => 1,
		);
		if(!$info['end_time'] && !$info['sta_id'] && !$info['video_id'])
		{
			$this->errorOutput(OBJECT_NULL);
		}	

		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = ".$info['sta_id']." ORDER BY order_id DESC";
		$f = $this->db->query_first($sql);
		if(!empty($f))
		{
			$info['order_id'] = $f['order_id']+1;
		}
		$extra = $space = '';
		foreach($info as $k => $v)
		{
			if($v)
			{
				$extra .= $space . $k . "='" . $v . "'" ;
				$space = ',';
			}
		}
		$sql = "INSERT INTO ".DB_PREFIX."network_programme SET ";
		if(!$extra)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql .= $extra;
		$this->db->query($sql);
				
		$this->insert_history($info['sta_id']);
		$this->update_station_program($info['sta_id'],1);
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = " . $info['sta_id'] . " ORDER BY order_id DESC";
		$query = $this->db->query($sql);
		$program = array();
		while ($array = $this->db->fetch_array($query))
		{
			$array['toff'] = $array['end_time']-$array['start_time'];
			$program[] = $array;
		}

		$this->setXmlNode('user','info');
		$this->addItem($program);
		$this->output();
	}
	
	/**
	* 修改节目单
	* @param $program_id
	* @param $program_name
	* @param $brief
	* @return $info 节目单信息
	*/
	function edit(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$p_id = $this->input['program_id']?$this->input['program_id']:0;
		if(!$p_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => $p_id,
			'user_id' => $mInfo['id'],
			'program_name' => hg_filter_chars(urldecode($this->input['program_name'])),
			'brief' => hg_filter_chars(urldecode($this->input['brief'])),
			'update_time' => time(),
		);
		
		$sql = "UPDATE ".DB_PREFIX."network_programme 
			SET 
				programe_name = '".$info['program_name']."',
				brief = '".$info['brief']."',
				update_time = ".$info['update_time']." 
			WHERE 
				id = ".$info['id'];
		
		$this->db->query($sql);

		$this->setXmlNode('user','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 删除节目单
	* @param $program_id
	* @param $sta_id
	* @param $gap
	* @return $info 节目单信息
	*/
	function del(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$info = array(
			'id' => $this->input['program_id'] ? $this->input['program_id'] : 0,
			'sta_id' => $this->input['sta_id'] ? $this->input['sta_id'] : 0 ,
			'user_id' => $mInfo['id'],
		);

		if(!$info['id'] && !$info['sta_id'])
		{
			$this->errorOutput(OBJECT_NULL);
		}

		$sql = "DELETE FROM " . DB_PREFIX . "network_programme WHERE id = " . $info['id'];
		$this->db->query($sql);
		$this->reset_order($info['sta_id']);
		$this->update_station_program($info['sta_id'],2);
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = " . $info['sta_id'] . " ORDER BY order_id DESC";
		$query = $this->db->query($sql);
		$program = array();
		while ($array = $this->db->fetch_array($query))
		{
			$array['toff'] = $array['end_time']-$array['start_time'];
			$program[] = $array;
		}

		$this->setXmlNode('user','info');
		$this->addItem($program);
		$this->output();
	}
	
	/**
	* 移动节目单
	* @param $program_id
	* @param $sta_id
	* @param $action 0为up 1为down
	* @param $gap
	* @return $ret 节目单信息
	*/
	function move(){
	/**/$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}

		$action = $this->input['action']?$this->input['action']:0;
		$info = array(
			'id' => $this->input['program_id'] ? $this->input['program_id'] : 0,
			'sta_id' => $this->input['sta_id'] ? $this->input['sta_id'] : 0,
			'user_id' => $mInfo['id'],
		);

		if(!$info['id'] && !$info['sta_id'])
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "network_programme WHERE id = ".$info['id'];
		$f = $this->db->query_first($sql);
		if(!$action) //下move
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "network_programme WHERE sta_id = " . $info['sta_id'] . " and order_id=" . ($f['order_id']+1);
			$f_u = $this->db->query_first($sql);
			
		}
		else //上move
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "network_programme WHERE sta_id = " . $info['sta_id'] . " and order_id=" . ($f['order_id']-1);
			$f_u = $this->db->query_first($sql);
		}

		if(!empty($f_u))
		{
			$sql = "UPDATE	" . DB_PREFIX . "network_programme set order_id=" . $f_u['order_id'] . " where id=" . $f['id'];
			$this->db->query($sql);
			$sql = "UPDATE	" . DB_PREFIX . "network_programme set order_id=" . $f['order_id'] . " where id=" . $f_u['id'];
			$this->db->query($sql);
			$sql = "UPDATE " . DB_PREFIX . "network_station SET update_time =".time()." WHERE id=".$info['sta_id'];
			$this->db->query($sql);		
			$this->update_station_program($info['sta_id']);
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = " . $info['sta_id'] . " ORDER BY order_id DESC";
		$query = $this->db->query($sql);
		$program = array();
		while ($array = $this->db->fetch_array($query))
		{
			$array['toff'] = $array['end_time']-$array['start_time'];
			$program[] = $array;
		}
		$this->setXmlNode('user','info');
		$this->addItem($program);
		$this->output();
	}

	function reset_order($sta_id = 0)
	{
		$con = '';
		if($sta_id)
		{
			$con = " where 1 and sta_id=" . $sta_id;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."network_programme " . $con . " ORDER BY order_id ASC";
		$q = $this->db->query($sql);
		$info = array();
		$i = 1;
		while($row = $this->db->fetch_array($q))
		{
			$sql = "update ".DB_PREFIX."network_programme SET order_id=" . $i . " where id=" . $row['id'];
			$this->db->query($sql);
			$i++;
		}
	
	}
	
	/**
	* 根据单个视频ID取其所在的节目单信息
	* @param $video_id 视频ID
	* @return $ret 节目单信息
	*/
	function video_program()
	{
		$mInfo = $this->mUser->verify_credentials();
		$video_id = $this->input['video_id']?$this->input['video_id']:0;
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:0);
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT $offset , $count";
		}
		if(!$video_id)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$sql = "SELECT * FROM " . DB_PREFIX . "network_programme WHERE video_id=".$video_id.$end;
		$q = $this->db->query($sql);
		$space = "";
		$sta_id ="";
		while($row = $this->db->fetch_array($q))
		{
			$sta_id .= $space.$row['sta_id'];
			$space = ",";
		}
		
		if($sta_id)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "network_station WHERE id IN(".$sta_id.")";
			$query =  $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$station[] = $row;
			}
			
			if($count)
			{
				$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "network_programme WHERE video_id=".$video_id;
				$first = $this->db->query_first($sql);
				$station['total'] = $first['total'];
			}
		}
		
		$this->setXmlNode('program','info');
		$this->addItem($station);
		$this->output();
		
	}
	
	
	/**
	*某网台的 节目单做（创建）取最新五条记录作为网台记录
	* @param $sta_id
	* @return $ret 节目单信息
	*/
	private function update_station_program($sta_id = 0,$is_count = false)
	{
		$mInfo = $this->mUser->verify_credentials();
		
		$user_id = $mInfo['id'];//当前的ID
		
		$info = array(
			"sta_id" => $sta_id,
			"user_id" => $user_id,
			"programe" => "",
			"update_time" => TIMENOW,
			"ip" => hg_getip(),		
		);
		
		if($info['sta_id'])
		{
			$sql = $sql = "SELECT count(*) as total FROM ". DB_PREFIX ."network_programme WHERE sta_id =".$info['sta_id'];
			$f = $this->db->query_first($sql);
			$total = $f['total'];
			$sql = "SELECT id,user_id,sta_id,video_id,programe_name,start_time,end_time,create_time,update_time,order_id FROM ". DB_PREFIX ."network_programme WHERE sta_id =".$info['sta_id']." ORDER BY update_time DESC LIMIT 0,4";
			$q = $this->db->query($sql);
			$video_id = "";
			$user_id = "";
			$space = "";
			while($row = $this->db->fetch_array($q))
			{
				$program [$row['id']]= $row;
				$video_id .= $space.$row['video_id'];
				$user_id .= $row['user_id'].",";
				$space = ",";
			}
			
			$user = $this->mVideo->getUserById($user_id);
			
			if($video_id)
			{
				$sql = "SELECT id,user_id,sort_id,title,tags,schematic,bschematic,filename,streaming_media,toff,copyright,collect_count,comment_count,play_count,click_count,is_top,state,bans,ip,create_time,update_time,is_show,serve_id,is_recommend,is_thread FROM ". DB_PREFIX ."video WHERE id IN(" . $video_id . ")";
				$q = $this->db->query($sql);
				while($row = $this->db->fetch_array($q))
				{
					if($row['id'])
					{
						$row = hg_video_image($row['id'], $row);
						$video[$row['id']] = $row;
					}
				}
			}
			
			if(is_array($program))
			{
				foreach($program as $key => $value)
				{
					$program[$key]['user'] = $user[$value['user_id']];
					if($video_id)
					{
						$program[$key]['video'] = $video[$value['video_id']];
					}
				}
//				$program['total'] = $total;
			}

			$con = '';
			switch($is_count)
			{
				case 1:
					$con = ', program_count=program_count+1 ';
					break;
				case 2:
					$con = ', program_count=program_count-1 ';
					break;
				default:
					break;
			}
			
			$info['programe'] = hg_filter_chars(serialize($program));
			$sql = "UPDATE " . DB_PREFIX . "network_station SET programe='" . $info['programe'] . "',update_time =" . $info['update_time'] . $con . " WHERE id=" . $info['sta_id'];
			$this->db->query($sql);
			return true;
		}
		return false;
	}
	
	/**
	*某网台的 节目单做任何更新（创建，删除，编辑）取最新的记录作为历史记录保存
	* @param $sta_id
	* @return $ret 节目单信息
	*/
	private function insert_history($sta_id)
	{
		$mInfo = $this->mUser->verify_credentials();
		
		$user_id = $mInfo['id'];//当前的ID
		
		$info = array(
			"sta_id" => $sta_id,
			"programe" => "",
			"user_id" => $user_id,
			"update_time" => time(),
			"ip" => hg_getip(),		
		);
		
		if($info['sta_id'])
		{
			$sql = "SELECT id,user_id,sta_id,video_id,programe_name,brief,start_time,end_time,create_time,update_time FROM ". DB_PREFIX ."network_programme WHERE sta_id =".$info['sta_id']." ORDER BY update_time DESC";
			$first = $this->db->query_first($sql);
			
			$user = $this->mVideo->getUserById($first['user_id']);
			
			if($first['video_id'])
			{
				$sql = "SELECT id,user_id,sort_id,title,tags,schematic,bschematic,filename,streaming_media,toff,copyright,collect_count,comment_count,play_count,click_count,is_top,state,bans,ip,create_time,update_time,is_show,serve_id,is_recommend,is_thread FROM ". DB_PREFIX ."video WHERE id =".$first['video_id'];
				$video = $this->db->query_first($sql);
			}
			
			$first['video'] = $video;
			$first['user'] = $user;
			$info['programe'] = serialize($first);
			$sql = "INSERT INTO " . DB_PREFIX . "program_history(
				sta_id,
				user_id,
				programe,
				update_time,
				ip
				) 
				VALUES(
				".$info['sta_id'].",
				".$info['user_id'].",
				'".$info['programe']."',
				".$info['update_time'].",
				'".$info['ip']."'
				)";
			$this->db->query($sql);
			return true;
		}
		return false;
	}
	
	/**
	*复制指定用户的节目单到某个用户
	* @param $user_id 某用户
	* @return $ret 节目单信息
	*/
	public function copys()
	{
		$user_id = $this->input['user_id']? $this->input['user_id']:0;
		if(!$user_id)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'network_station WHERE user_id = '.$user_id;
		$q = $this->db->query_first($sql);
		if($q)
		{
			$sta_id = $q['id'];
		}
		else 
		{
			$user = $this->mVideo->getUserById($user_id);
			$info = array(
				'user_id' => $user_id,
				'create_time' => time(),
				'update_time' => time(),
			);
			
			$sql = "INSERT INTO ".DB_PREFIX."network_station(
				web_station_name,
				user_id,
				create_time,
				update_time
			)
			VALUES(
				".$user['username'].",
				".$info['user_id'].",
				".$info['create_time'].",
				".$info['update_time']."
			)";
			$this->db->query($sql);
			$sta_id = $this->db->insert_id();
		}
		
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$this->mUser = new user();
		$this->mUser->update_type($user_id);
		
		if(defined('PROGRAM_USER'))
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'network_station WHERE user_id = '.PROGRAM_USER;
			$f = $this->db->query_first($sql);
			if(is_array($f))
			{
				$sql ="INSERT INTO  " . DB_PREFIX . "network_programme (user_id,sta_id, video_id, programe_name, brief, start_time, end_time, create_time, update_time) 
			SELECT " . $user_id . "," . $sta_id . ", video_id, programe_name, brief, start_time, end_time, UNIX_TIMESTAMP(), UNIX_TIMESTAMP() FROM " . DB_PREFIX . "network_programme where user_id = " . PROGRAM_USER . " and sta_id=" . $f['id'];
				$this->db->query($sql);
			}
			$sql = "select count(*) as num from " . DB_PREFIX . "network_programme where user_id = " . PROGRAM_USER;
			$sen = $this->db->query_first($sql);
			$sql = "UPDATE ".DB_PREFIX."network_station SET program_count=" . $sen['num'] . " WHERE id = " . $sta_id;
			$this->db->query($sql);
			
			$this->insert_history($sta_id);
			$this->update_station_program($sta_id);
		}
		$this->setXmlNode('user','info');
		$this->addItem(true);
		$this->output();
	}
}

$out = new programApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>