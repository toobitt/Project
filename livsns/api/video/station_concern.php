<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: station_concern.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . '/lib/class/curl.class.php');	
class stationConcernApi extends adminBase
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
		$this->curl = new curl();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 显示关注
	* @param $user_id
	* @return $ret 关注信息
	*/
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$user_id = $this->input['user_id']?$this->input['user_id']:$mInfo['id'];
		if(!$user_id)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:0);
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT $offset , $count";
		}
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
		);
		$sql = "SELECT c.*,n.* FROM ".DB_PREFIX."network_station n 
			LEFT JOIN ".DB_PREFIX."station_concern c 
			ON n.id=c.sid 
			WHERE n.state = 1 AND c.user_id=".$user_id. $end;
		$query = $this->db->query($sql);
		$ids = '';
		$space = ' ';
		while($array = $this->db->fetch_array($query))
		{
			foreach($size as $key=>$value)
			{
				if($array['logo'])
				{
					$new_name = $value['t'].$array['logo'];
					$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($array['user_id']/NUM_IMG)."/".$new_name;
				}
				else 
				{
					if(file_exists(hg_avatar($user_id,"larger",$user_id.".jpg")))
					{
						$array[$key] = hg_avatar($user_id,"larger",$user_id.".jpg");
					}
					else 
					{
						$array[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
			$ids .= $space.$array['id']; 
			$space = ',';
			$program[] = $array;
		}
		$re = $this->mVideo->get_concern_relevance($mInfo['id'],$ids);
		foreach($program as $key =>$value)
		{
			$program[$key]['relation'] = $re[$value['id']]['relation'];
			$program[$key]['collect_id'] = $re[$value['id']]['id'];
		}
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."network_station n 
			LEFT JOIN ".DB_PREFIX."station_concern c 
			ON n.id=c.sid 
			WHERE n.state = 1 AND c.user_id=".$user_id;
		$total = $this->db->query_first($sql);
		$program['total'] = $total['total'];
		$this->setXmlNode('concern','info');
		$this->addItem($program);
		$this->output();	
	}

	
	/**
	* 添加关注
	* @param $id 内容ID
	* @return $ret 关注信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sid = $this->input['id']?$this->input['id']:0;
		if(!$sid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $mInfo['id'],
			'uid' => $this->input['uid']?$this->input['uid']:0,
			'sid' => $sid,
			'type' => $type,
		);
			/**
	   		 *  配置是否同时取消关注该用户的频道
		   	 * @param $id 网台ID
			 * @return $ret 网台关注信息
	   		 */
			global $gGlobalConfig;
			if($gGlobalConfig['follow_set'] && !$this->input['have'])
			{
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->addRequestData('user_id',$info['uid']);
				$this->curl->addRequestData('self_id',0);
				$this->curl->addRequestData('have',1);
				$ret =  $this->curl->request('friendships/create.php');		
			}
		
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE user_id=".$info['user_id']." AND sid=".$info['sid'];
		$first = $this->db->query_first($sql);
		if(!$first)
		{
			if($info['uid']!=$mInfo['id'])
			{
				$sql = "INSERT INTO ".DB_PREFIX."station_concern(user_id,sid,create_time) VALUES(".$info['user_id'].",".$info['sid']."," . time() . ")";
				$this->db->query($sql);
				$info['id'] = $this->db->insert_id();
				$info['self'] =0;
			}
			else 
			{
				$info['self'] =1;
			}
			$info['is']=1;
		}
		else 
		{
			$info['id'] = $first['id'];
			$info['is']=0;
		}	
		$this->setXmlNode('concern','info');
		$this->addItem($info);
		$this->output();
	}
	

	/**
	* 添加关注 (多个用户)
	* @param $id 内容ID
	* @param $uid 关注的用户ID
	* @return $ret 关注信息
	*/
	function create_more(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$uids = $this->input['uids']?urldecode($this->input['uids']):0;
		if(!$uids)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $mInfo['id'],
			'uid' => $uids,
			'sid' => 0,
		);
		$info['uid'] = rtrim($info['uid'],",");
		$arr_uid = explode(",", $info['uid']);
		$sql = "SELECT * FROM ".DB_PREFIX."network_station WHERE user_id IN(".$info['uid'].")";
		$q = $this->db->query($sql);
		$arr_sid = array();
		$sid = "";
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
			$sid .= $space.$row['id'];
			$space = ',';
		}
		foreach($arr_uid as $k => $v)
		{
			$arr_sid[$v]['user_id'] = $v;
			$arr_sid[$v]['id'] = 0;
			$arr_sid[$v]['cid'] = 0;
			$arr_sid[$v]['self'] = 0;
			$arr_sid[$v]['is'] = 0;
			foreach($arr as $key => $value)
			{
				if($arr_sid[$v]['user_id'] == $value['user_id'])
				{
					$arr_sid[$v]['id'] = $value['id'];
				}
				if($arr_sid[$v]['user_id'] == $mInfo['id'])
				{
					$arr_sid[$v]['self'] = 1;
				}
			}
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE user_id=".$info['user_id']." AND sid IN (".$sid.")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$arr[] = $row;
		}
		$sid = "";
		$space = "";
				
				
		foreach($arr_sid as $k => $v)
		{
			foreach($arr as $key => $value)
			{
				if($arr_sid[$k]['id'] == $value['sid'])
				{
					$arr_sid[$k]['is'] = 1;
					$arr_sid[$k]['cid'] = $value['id'];
				}
			}
			if(!$arr_sid[$k]['is'] && !$arr_sid[$k]['self'])
			{
				$sql = "INSERT IGNORE INTO ".DB_PREFIX."station_concern(user_id,sid,create_time) VALUES";
				$sql.= "(".$info['user_id'].",
					".$arr_sid[$k]['id']."," . time() . ")";
				$this->db->query($sql);
				$arr_sid[$k]['cid'] = $this->db->insert_id();
				$arr_sid[$k]['is'] = 1;
			}
		}
		$this->setXmlNode('concern','info');
		$this->addItem($arr_sid);
		$this->output();
	}
	
	/**
	* 取关注关联
	* @param $id 关注ID
	* @return $ret 关注信息
	*/
	function get_station_relevance()
	{
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sid = $this->input['id']?$this->input['id']:0;
		if(!$sid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $mInfo['id'],
			'sid' => $sid,
			'relation' => 0,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE user_id=".$info['user_id']." AND sid=".$info['sid'];
		$first = $this->db->query_first($sql);
		if($first)
		{
			$info['relation'] = 1;
		}
		else 
		{
			$info['id'] = $first['id'];
		}
		$this->setXmlNode('concern','info');
		$this->addItem($info['relation']);
		$this->output();
	}
	
	
	/**
	* 取消收藏
	* @param $id 收藏ID
	* @return $ret 收藏信息
	*/
	function del(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$id = $this->input['id']?$this->input['id']:0;
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		/**
   		 *  配置是否同时取消关注该用户的频道
	   	 * @param $id 网台ID
		 * @return $ret 网台关注信息
   		 */
		global $gGlobalConfig;
		if($gGlobalConfig['follow_set'] && !$this->input['have'])
		{
			$uid = $this->input['uid'];
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->addRequestData('user_id',$uid);
			$this->curl->addRequestData('have',1);
			$ret = $this->curl->request('friendships/destroy.php');		
		}
		
		
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE id = ".$id;
		$info = $this->db->query_first($sql);
		$sql = "DELETE FROM ".DB_PREFIX."station_concern WHERE id = ".$id;
		$this->db->query($sql);
		$this->setXmlNode('concern','info');
		$this->addItem($info);
		$this->output();
	}
	

	
	/**
	* 取消收藏
	* @param $user_id 对象的用户ID
	* @return $ret 收藏信息
	*/
	function del_more(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$user_id = $this->input['id']?$this->input['id']:0;
		if(!$user_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."network_station WHERE user_id = ".$user_id;
		$f = $this->db->query_first($sql);
		if($f && $f['id'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE sid = ".$f['id']." AND user_id = ".$mInfo['id'];
			$fk = $this->db->query_first($sql);
			if($fk && $fk['id'])
			{
				$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE id = ".$fk['id'];
				$info = $this->db->query_first($sql);
				$sql = "DELETE FROM ".DB_PREFIX."station_concern WHERE id = ".$fk['id'];
				$this->db->query($sql);
				$this->setXmlNode('concern','info');
				$this->addItem($info);
				$this->output();
			}
			else 
			{
				$this->errorOutput(OBJECT_NULL);
			}
		}
		else 
		{
			$this->errorOutput(OBJECT_NULL);
		}
	}
	
	/**
	* 查询关注网台的更新记录
	* @return $ret 更新信息
	*/
	function get_history()
	{
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$sta_id = $this->input['sta_id']?$this->input['sta_id'].",":"";
		
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:0);
		$offset = $page * $count;
		$end = "";
		if($count)
		{
			$end = " LIMIT $offset , $count";
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE user_id=".$mInfo['id'];
		$q = $this->db->query($sql);
		$space = "";
		while($row = $this->db->fetch_array($q))
		{
			$sta_id .= $space.$row['sid'];
			$space = ",";
		}
		
		$size = array(
			"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
			"ori" => array('t'=>"",'size'=>LOGO_SIZE_SMALL),
		);
		if(!$sta_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "SELECT * FROM ". DB_PREFIX ."network_station WHERE state=1 AND programe <> '' AND id IN(".$sta_id.") ORDER BY update_time DESC ".$end;
		
		$query = $this->db->query($sql);
		
		while($array = $this->db->fetch_array($query))
		{		
			foreach($size as $key=>$value)
			{
				if($array['logo'])
				{
					$new_name = $value['t'].$array['logo'];
					$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($array['user_id']/NUM_IMG)."/".$new_name;
				}
				else 
				{
					
					if(file_exists(hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg")))
					{
						$array[$key] = hg_avatar($array['user_id'],"larger",$array['user_id'].".jpg");
					}
					else 
					{
						$array[$key] = AVATAR_URL."larger_0.jpg";
					}
				}
			}
			$info[$array['id']] = $array;
		}
	
		foreach($info as $key => $value)
		{
			$info[$key]['programe'] = unserialize($value['programe']);
		}
		if($count)
		{
			$sql = "SELECT count(*) as total FROM ". DB_PREFIX ."network_station WHERE state=1 AND programe <> '' AND id IN(".$sta_id.") ORDER BY update_time DESC ";
			$first = $this->db->query_first($sql);
			$info['total'] = $first['total'];
		}
		$this->setXmlNode('get_history','info');
		$this->addItem($info);
		$this->output();
	}
	
	
	
	
}

$out = new stationConcernApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'get_history';
}
$out->$action();
?>