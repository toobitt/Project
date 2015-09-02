<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: collect.php 17962 2013-02-26 06:01:25Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class collectApi extends adminBase
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
	* 显示收藏
	* @param $user_id
	* @param $type （0视频、1网台、2用户）
	* @return $ret 收藏信息
	*/
	function show(){
		$mInfo = $this->mUser->verify_credentials();
		$mInfo['id'] = $mInfo['id']?$mInfo['id']:0;
		$user_id = $this->input['user_id']?$this->input['user_id']:$mInfo['id'];
		if(!$user_id)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$type = $this->input['type']?$this->input['type']:0;//默认为视频
		$page = intval($this->input['page']?$this->input['page']:0);
		$count = intval($this->input['count']?$this->input['count']:00);
		$offset = $page * $count;
		$end = " LIMIT $offset , $count";
		if(!$user_id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		switch($type)
		{
			case 0:
				$sql = "SELECT c.*,v.* FROM ".DB_PREFIX."video v 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON v.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 0 ". $end;
				$query = $this->db->query($sql);
				$ids = '';
				$space = ' ';
				while($array = $this->db->fetch_array($query))
				{
					$array = hg_video_image($array['id'], $array);
					$ids .= $space.$array['id']; 
					$space = ',';
					$program[] = $array;
				}
				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$ids,0);
				foreach($program as $key =>$value)
				{
					$program[$key]['relation'] = $re[$value['id']]['relation'];
					$program[$key]['collect_id'] = $re[$value['id']]['id'];
				}
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."video v 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON v.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 0 ";
				$total = $this->db->query_first($sql);
				$program['total'] = $total['total'];
				break;
			case 1:
				$size = array(
					"small" => array('t'=>"s_",'size'=>LOGO_SIZE_SMALL),
				);
				$sql = "SELECT c.*,n.* FROM ".DB_PREFIX."network_station n 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON n.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 1". $end;
				$query = $this->db->query($sql);
				$ids = '';
				$space = ' ';
				while($array = $this->db->fetch_array($query))
				{
					if($array['logo'])
					{
						foreach($size as $key=>$value)
						{
							$new_name = $value['t'].$array['logo'];
							$array[$key] = UPLOAD_URL.LOGO_DIR.ceil($mInfo['id']/NUM_IMG)."/".$new_name; 
						}
					}
					$ids .= $space.$array['id']; 
					$space = ',';
					
					$program[] = $array;
				}
				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$ids,1);
				foreach($program as $key =>$value)
				{
					$program[$key]['relation'] = $re[$value['id']]['relation'];
					$program[$key]['collect_id'] = $re[$value['id']]['id'];
				}
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."network_station n 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON n.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 1";
				$total = $this->db->query_first($sql);
				$program['total'] = $total['total'];
				break;
			case 2:
				$sql = "SELECT c.*,u.* FROM ".DB_PREFIX."user u 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON u.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 2". $end;
				$query = $this->db->query($sql);
				$ids = '';
				$space = ' ';
				while($array = $this->db->fetch_array($query))
				{
					if(strlen($array['avatar']) > 32)//qq同步的用户头像
					{
						$array['large_avatar']= hg_avatar($array['id'],"100",$array['avatar'],0);
						$array['middle_avatar']= hg_avatar($array['id'],"50",$array['avatar'],0);
						$array['small_avatar'] = hg_avatar($array['id'],"10",$array['avatar'],0);
					}
					else 
					{
						$array['large_avatar']= hg_avatar($array['id'],"larger",$array['avatar']);
						$array['middle_avatar']= hg_avatar($array['id'],"middle",$array['avatar']);
						$array['small_avatar'] = hg_avatar($array['id'],"small",$array['avatar']);
					}
					
					$ids .= $space.$array['id']; 
					$space = ',';
					$program[] = $array;
				}
				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$ids,2);
				foreach($program as $key =>$value)
				{
					$program[$key]['relation'] = $re[$value['id']]['relation'];
					$program[$key]['collect_id'] = $re[$value['id']]['id'];
				}
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."user u 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON u.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 2";
				$total = $this->db->query_first($sql);
				$program['total'] = $total['total'];
				break;
			case 3:
				$sql = "SELECT c.*,a.* FROM ".DB_PREFIX."album a 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON a.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 2". $end;
				$query = $this->db->query($sql);
				$ids = '';
				$space = ' ';
				while($array = $this->db->fetch_array($query))
				{
					$ids .= $space.$array['id']; 
					$space = ',';
					$program[] = $array;
				}
				$sql = "SELECT id,schematic FROM ".DB_PREFIX."video WHERE id IN(".$ids.")";
				$query = $this->db->query($sql);
				while($arr = $this->db->fetch_array($query))
				{
					$arr = hg_video_image($arr['id'], $arr['schematic'],0);
					$cover[$arr['id']] = $arr;
				}

				$re = $this->mVideo->get_collect_relevance($mInfo['id'],$ids,2);
				foreach($program as $key =>$value)
				{
					$program[$key]['relation'] = $re[$value['id']]['relation'];
					$program[$key]['collect_id'] = $re[$value['id']]['id'];
					$program[$key]['cover'] = $cover[$value['cover_id']]['schematic'];
				}
				$sql = "SELECT count(*) as total FROM ".DB_PREFIX."album a 
					LEFT JOIN ".DB_PREFIX."collects c 
					ON a.id=c.cid 
					WHERE c.user_id=".$user_id." AND c.type = 3";
				$total = $this->db->query_first($sql);
				$program['total'] = $total['total'];
				break;
			default:
				break;
		}
		$this->setXmlNode('collects','info');
		$this->addItem($program);
		$this->output();	
	}

	
	/**
	* 添加收藏
	* @param $id 内容ID
	* @param $type （0视频、1网台、2用户）
	* @return $ret 收藏信息
	*/
	function create(){
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$cid = $this->input['id']?$this->input['id']:0;
		$type = $this->input['type']?$this->input['type']:0;//默认为视频
		if(!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $mInfo['id'],
			'uid' => $this->input['uid']?$this->input['uid']:0,
			'cid' => $cid,
			'type' => $type,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."collects WHERE user_id=".$info['user_id']." AND cid=".$info['cid']." AND type=".$info['type'];
		$first = $this->db->query_first($sql);
		if(!$first)
		{
			switch($type)
			{
				case 0:
					$sql = "INSERT INTO ".DB_PREFIX."collects(user_id,cid,type) VALUES(".$info['user_id'].",".$info['cid'].",".$info['type'].")";
					$this->db->query($sql);
					$info['id'] = $this->db->insert_id();
//					$sql = "UPDATE ".DB_PREFIX."video SET collect_count= collect_count+1 
//						WHERE id=".$info['cid'];
//					$this->db->query($sql);
					$info['self'] =0;
					break;
				case 1:
					if($info['uid']!=$mInfo['id'])
					{
						$sql = "INSERT INTO ".DB_PREFIX."collects(user_id,cid,type) VALUES(".$info['user_id'].",".$info['cid'].",".$info['type'].")";
						$this->db->query($sql);
						$info['id'] = $this->db->insert_id();
//						$sql = "UPDATE ".DB_PREFIX."network_station SET collect_count= collect_count+1 
//							WHERE id=".$info['cid'];
//						$this->db->query($sql);
						$info['self'] =0;
					}
					else 
					{
						$info['self'] =1;
					}
					break;
				case 2:
					if($info['cid']!=$mInfo['id'])
					{
						$sql = "INSERT INTO ".DB_PREFIX."collects(user_id,cid,type) VALUES(".$info['user_id'].",".$info['cid'].",".$info['type'].")";
						$this->db->query($sql);
						$info['id'] = $this->db->insert_id();
//						$sql = "UPDATE ".DB_PREFIX."user SET collect_count= collect_count+1  
//							WHERE id=".$info['cid'];
//						$this->db->query($sql);
						$info['self'] =0;	
					}
					else 
					{
						$info['self'] =1;
					}
					break;
				case 3:
					if($info['cid']!=$mInfo['id'])
					{
						$sql = "INSERT INTO ".DB_PREFIX."collects(user_id,cid,type) VALUES(".$info['user_id'].",".$info['cid'].",".$info['type'].")";
						$this->db->query($sql);
						$info['id'] = $this->db->insert_id();
//						$sql = "UPDATE ".DB_PREFIX."album SET collect_count= collect_count+1  
//							WHERE id=".$info['cid'];
//						$this->db->query($sql);
						$info['self'] =0;	
					}
					else 
					{
						$info['self'] =1;
					}
					break;
				default:
					break;
			}
			
			$info['is']=1;
		}
		else 
		{
			$info['id'] = $first['id'];
			$info['is']=0;
		}	
		$this->setXmlNode('collects','info');
		$this->addItem($info);
		$this->output();
	}
	
	/**
	* 取收藏关联
	* @param $id 收藏ID
	* @param $type （0视频、1网台、2用户）	
	* @return $ret 收藏信息
	*/
	function get_collect_relevance()
	{
		$mInfo = $this->mUser->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$cid = $this->input['id']?$this->input['id']:0;
		$type = $this->input['type']?$this->input['type']:0;//默认为视频
		if(!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $mInfo['id'],
			'cid' => $cid,
			'type' => $type,
			'relation' => 0,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."collects WHERE user_id=".$info['user_id']." AND cid=".$info['cid']." AND type=".$info['type'];
		$first = $this->db->query_first($sql);
		if($first)
		{
			$info['relation'] = 1;
		}
		else 
		{
			$info['id'] = $first['id'];
		}
		$this->setXmlNode('collects','info');
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
//		$cid = $this->input['cid']?$this->input['cid']:0;
//		$type = $this->input['type']?$this->input['type']:0;
		if(!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "DELETE FROM ".DB_PREFIX."collects WHERE id = ".$id;
		$this->db->query($sql);
//		switch($type)
//			{
//				case 0:
//					$sql = "UPDATE ".DB_PREFIX."video SET collect_count= collect_count-1 
//						WHERE id=".$cid;
//					break;
//				case 1:
//						$sql = "UPDATE ".DB_PREFIX."network_station SET collect_count= collect_count-1 
//							WHERE id=".$cid;
//					break;
//				case 2:
//						$sql = "UPDATE ".DB_PREFIX."user SET collect_count= collect_count-1  
//							WHERE id=".$cid;
//					break;
//				case 3:
//						$sql = "UPDATE ".DB_PREFIX."album SET collect_count= collect_count-1  
//							WHERE id=".$cid;
//					break;
//				default:
//					break;
//			}
//		$this->db->query($sql);
		$this->setXmlNode('collects','info');
		$this->addItem($id);
		$this->output();
	}
}

$out = new collectApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>