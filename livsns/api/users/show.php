<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: show.php 17947 2013-02-26 02:57:46Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class showApi extends appCommonFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
		require_once(ROOT_DIR.'lib/user/user.class.php');
		require_once(ROOT_DIR.'lib/class/curl.class.php');
		$this->user = new user();
		$this->curl = new curl();
	}

	function __destruct()
	{
		parent::__destruct();
	}
		
	/**
	 * 根据用户名获取用户信息
	 * @param $name 指定需要清理的数据
	 * @return xml/json 用户信息
	 */
	public function getUserByName($name,$type="base")
	{
		$this->getUserInfo($name,"username",$type);
	}
	
	/**
	 * 根据用户ID获取用户信息
	 * @param $id 指定需要清理的数据
	 * @return xml/json 用户信息
	 */
	public function getUserById($id,$type="base")
	{				
		$this->getUserInfo($id,"id",$type);
	}
	
	public function getUserInfo($user,$con,$type)
	{
		$userinfo = $this->user->verify_credentials();
		$delEle = array('password','salt');	
		$privacyArr = array('truename','birthday','email','qq', 'msn','mobile');
		if($type == "base")
		{
			$delEle = array_merge($delEle,$privacyArr);
		}
		else if(strcmp($type,'all') != 0)
		{
			$this -> errorOutput(OBJECT_NULL);
		}
		
		
		$mark = ',';
		if(empty($user))
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else 
		{
			$user = urldecode($user);
			$names = array_unique(explode($mark,$user));
			$num = count($names);
			$sql = "SELECT m.*,e.*,ml.group_id,ml.group_name,ml.lat,ml.lng
					FROM ".DB_PREFIX."member m
					LEFT JOIN ".DB_PREFIX."member_extra e
					ON m.id = e.member_id 
					left join " . DB_PREFIX . "member_location ml
					on m.id = ml.member_id
					WHERE m.".$con;
			if($num > BATCH_FETCH_LIMIT)
			{		
				$names = array_slice($names,0,BATCH_FETCH_LIMIT);
			}
				foreach ($names as $key => $value)
				{
					$names[$key] = "'".$value."'";					
				}			
				$names = implode($mark,$names);
				$sql .= " IN(".$names.") ORDER BY m.id ASC";
				$result = $this->db->query($sql);	
				$this->setXmlNode('userinfo','user');

				$info = array();
				$array_id = array();
				$privacy_arr = array();

				$i = 0;					
				while($row = $this->db->fetch_array($result))
				{
					foreach($delEle as $key => $value)
					{
						unset($row[$value]);
					}
					//if ($row['id'] != $this->user['id'])
					{
						unset($row['cellphone']);
					}
					$info[$i] = $row;
					if(strlen($row['avatar']) > 32)//qq同步的用户头像
					{
						$info[$i]['larger_avatar'] = hg_avatar($row['id'],"100",$row['avatar'],0);
						$info[$i]['middle_avatar'] = hg_avatar($row['id'],"50",$row['avatar'],0);
						$info[$i]['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
					}
					else 
					{
						$info[$i]['larger_avatar'] = hg_avatar($row['id'],"larger",$row['avatar']);
						$info[$i]['middle_avatar'] = hg_avatar($row['id'],"middle",$row['avatar']);
						$info[$i]['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']);
					}
	
					$array_id[] = $row['id'];
					$privacy_arr[] = $row['privacy'];
					
					$i++;	
			
				}
				
				$ids = implode(',' , $array_id);
				
				$rt = $this->get_relation($userinfo['id'] ,$ids);
				
				$len = count($array_id);
				
				foreach($info as $key => $val)
				{
					if($val['id'] != $userinfo['id']&&$userinfo['id'])
					{
						$privacy = substr($val['privacy'],0,6);
						$flag = intval($privacy{1});
						if(strcmp($info[$key]['birthday'],'0000-00-00')==0)
						{
							unset($info[$key]['birthday']);
						}
						else 
						{
							switch($flag)
							{
								case 1: 
										unset($info[$key]['birthday']);break;
								case 2:
										$temp = explode('-',$val['birthday']);
										$info[$key]['birthday'] = $temp[1]."月".$temp[2]."日";
										break;
								case 3:
									 	$info[$key]['birthday'] = $this->hg_calc_constellation($val['birthday']);
									 	break;
							}
						}
						
						for($j=0;$j<6;$j++)
						{
							if($j != 1)
							{
								if($rt[0]==0 || intval($privacy{$j})==0)
								{
									unset($info[$key][$privacyArr[$j]]);
								}
							}
						}
						
					}
				} 
				foreach($info as $k => $v)
				{
					$v['home'] = SNS_UCENTER . 'user.php';
					$this->addItem($v);	
				}
				return $this->output();			
		}
	}
	

	/**
	 * 入口
	 */

	public function show()
	{	
		if($this->input['user_id'])
		{
			if($this->input['type'])
				$this->getUserById(urldecode(trim($this->input['user_id'])),$this->input['type']);
			else
				$this->getUserById(urldecode(trim($this->input['user_id'])));
		}
		if($this->input['screen_name'])
		{
			if($this->input['type'])
				$this->getUserByName(urldecode(trim($this->input['screen_name'])),$this->input['type']);
			else
				$this->getUserByName(urldecode(trim($this->input['screen_name'])));	
		}	
	}
	
	/**
	 * 获取当前用户和取出用户的关系
	 */
	public function get_relation($id , $ids)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('friendships/user_relation_friends.php');		
	}
	
	/**
	 * 更新用户视频数(增加)
	 */
	public function update_video()
	{
		$userinfo = $this->user->verify_credentials();		
		if(!$userinfo)
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码	
		}
		
		$user_id = intval($this->input['user_id']);		
		$sql = "UPDATE " . DB_PREFIX . "member_extra SET video_count = video_count + 1 WHERE member_id = " . $user_id;
		$this->db->query($sql);
	}
	
	/**
	 * 更新用户视频数(减少)
	 */
	public function delete_video_nums()
	{
		$userinfo = $this->user->verify_credentials();		
		if(!$userinfo)
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码	
		}
		
		$user_id = $userinfo['id'];
		$video_count = $this->input['video_count'];
				
		$sql = "UPDATE " . DB_PREFIX . "member_extra SET video_count = " . $video_count . " WHERE member_id = " . $user_id;
		$this->db->query($sql);
	}
	
	/**
	* 返回星座
	*/
	function hg_calc_constellation($birthday = 0)
	{
		$return = 0;
		if(!$birthday)
		{
			return '';
		}
		$score_arr = array();
		$birthday = explode('-', $birthday);
		$month = $birthday[1];
		$day = $birthday[2];
		
		if ($month == 1 && $day >=20 || $month == 2 && $day <=18) 
		{
			$return = 1;
			$score_arr = array('01-20','02-18');
		}
		if ($month == 1 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 2 && $day >=19 || $month == 3 && $day <=20) 
		{
			$return = 2;
			$score_arr = array('02-19','03-20');
		}
		if ($month == 2 && $day > 29) 
		{
			$return = 0;
		}
		if ($month == 3 && $day >=21 || $month == 4 && $day <=19) 
		{
			$return = 3;
			$score_arr = array('03-21','04-19');
		}
		if ($month == 3 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 4 && $day >=20 || $month == 5 && $day <=20) 
		{
			$return = 4;
			$score_arr = array('04-20','05-20');
		}
		if ($month == 4 && $day > 30) 
		{
			$return = 0;
		}
		if ($month == 5 && $day >=21 || $month == 6 && $day <=21) 
		{
			$return = 5;
			$score_arr = array('05-21','06-21');
		}
		if ($month == 5 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 6 && $day >=22 || $month == 7 && $day <=22) 
		{
			$return = 6;
			$score_arr = array('06-22','07-22');
		}
		if ($month == 6 && $day > 30) 
		{
			$return = 0;
		}
		if ($month == 7 && $day >=23 || $month == 8 && $day <=22) 
		{
			$return = 7;
			$score_arr = array('07-23','08-22');
		}
		if ($month == 7 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 8 && $day >=23 || $month == 9 && $day <=22) 
		{
			$return = 8;
			$score_arr = array('08-23','09-22');
		}
		if ($month == 8 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 9 && $day >=23 || $month == 10 && $day <=22) 
		{
			$return = 9;
			$score_arr = array('09-23','10-22');
		}
		if ($month == 9 && $day > 30) 
		{
			$return = 0;
		}
		if ($month == 10 && $day >=23 || $month == 11 && $day <=21) 
		{
			$return = 10;
			$score_arr = array('10-23','11-21');
		}
		if ($month == 10 && $day > 31) 
		{
			$return = 0;
		}
		if ($month == 11 && $day >=22 || $month == 12 && $day <=21) 
		{
			$return = 11;
			$score_arr = array('11-22','12-21');
		}
		if ($month == 11 && $day > 30) 
		{
			$return = 0;
		}
		if ($month == 12 && $day >=22 || $month == 1 && $day <=19) 
		{
			$return = 12;
			$score_arr = array('12-22','12-31','01-01','01-19'); 
		}
		if ($month == 12 && $day > 31) 
		{
			$return = 0;
		}
		return $return;
	}
	
	
	/**
	 * 获取会员
	 */
	public function getVip()
	{
		$page = $this->input['page'] ? intval($this->input['page']) : 0;
				
		if(!$this->input['count'])
		{
			$this->input['count'] =  6;
		}
		
		$count = intval($this->input['count']);
		
		$totle = $this->input['total'] ? intval($this->input['total']) : 0;
				
		if($page >= $totle)
		{
			$page = 0;
		}
		
		$offset = $page * $count;
				
		$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE user_group_id != 2";
		$r = $this->db->query_first($sql);
		
		$total = $r['nums'];
			
		$conditon  = " LIMIT " . $offset . ' , ' . $count;
		
		$sql = "SELECT m.id ,m.username , m.avatar , e.* FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE user_group_id != 2 ORDER BY join_time DESC " . $conditon;
		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('vip_info' , 'vip');
		
		while($row = $this->db->fetch_array($q))
		{
			if(strlen($row['avatar']) > 32)//qq同步的用户头像
			{
				$row['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
			}
			else 
			{
				$row['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
			}
			
			$this->addItem($row);	
		}
		
		$this->addItem($total);
		
		$this->output();		
	}

	public function getUserById_Group()
	{
		$type = "base";
		$userid = urldecode($this->input['user_id']);
		$delEle = array('password','salt');	
		$privacyArr = array('truename','birthday','email','qq', 'msn','mobile');
		if($type == "base")
		{
			$delEle = array_merge($delEle,$privacyArr);
		}
		else if(strcmp($type,'all') != 0)
		{
			$this -> errorOutput(OBJECT_NULL);
		}
		
		if(empty($userid))
		{
			$this -> errorOutput(OBJECT_NULL);//返回0x0000代码
		}
		else 
		{
			$sql = "SELECT m.*,e.*,ml.group_id,ml.group_name,ml.lat,ml.lng
					FROM ".DB_PREFIX."member m
					LEFT JOIN ".DB_PREFIX."member_extra e
					ON m.id = e.member_id 
					left join " . DB_PREFIX . "member_location ml
					on m.id = ml.member_id
					WHERE m.id IN(" . $userid . ") ORDER BY m.id ASC";

			$result = $this->db->query($sql);	
			$this->setXmlNode('userinfo','user');

			$info = array();
			$i = 0;					
			while($row = $this->db->fetch_array($result))
			{
				foreach($delEle as $key => $value)
				{
					unset($row[$value]);
				}
				$info[$i] = $row;
				if(strlen($row['avatar']) > 32)//qq同步的用户头像
				{
					$info[$i]['larger_avatar'] = hg_avatar($row['id'],"100",$row['avatar'],0);
					$info[$i]['middle_avatar'] = hg_avatar($row['id'],"50",$row['avatar'],0);
					$info[$i]['small_avatar'] = hg_avatar($row['id'],"10",$row['avatar'],0);
				}
				else 
				{
					$info[$i]['larger_avatar'] = hg_avatar($row['id'],"larger",$row['avatar']);
					$info[$i]['middle_avatar'] = hg_avatar($row['id'],"middle",$row['avatar']);
					$info[$i]['small_avatar'] = hg_avatar($row['id'],"small",$row['avatar']);
				}
				$i++;			
			}
			
			foreach($info as $k => $v)
			{
				$v['home'] = SNS_UCENTER . 'user.php';
				$this->addItem($v);	
			}
			$this->output();		
		}
	}


	/**
	 * 根据条件获取用户
	 */
	public function getUserByOrder()
	{
		
		$page = $this->input['page'] ? intval($this->input['page']) : 0;
				
		if(!$this->input['count'])
		{
			$this->input['count'] =  20;
		}
		
		$count = intval($this->input['count']);
		
		$totle = $this->input['total'] ? intval($this->input['total']) : 0;
				
		if($page >= $totle)
		{
			$page = 0;
		}
		
		$offset = $page * $count;
				
	/*	$sql = "SELECT COUNT(*) AS nums FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE 1";
		
		$r = $this->db->query_first($sql);
		
		$total = $r['nums'];*/
		$order = '';
		switch(urldecode($this->input['order']))
		{
			case 'join_time':
				$order = 'ORDER BY m.join_time DESC';
				break;
			case 'last_login':
				$order = 'ORDER BY m.last_login DESC';
				break;
			case 'last_activity':
				$order = 'ORDER BY e.last_activity DESC';
				break;
			case 'id':
				$order = 'ORDER BY m.id ASC';
				break;
			default:
				$order = 'ORDER BY join_time DESC';
				break;
		}
			
		$conditon  = " LIMIT " . $offset . ' , ' . $count;
		
		$sql = "SELECT m.id ,m.username , m.avatar , e.* FROM " . DB_PREFIX . "member AS m LEFT JOIN " . DB_PREFIX . "member_extra AS e ON m.id = e.member_id WHERE 1 " . $order . $conditon;
		
		$q = $this->db->query($sql);
				
		while($row = $this->db->fetch_array($q))
		{
			if(strlen($row['avatar']) > 32)//qq同步的用户头像
			{
				$row['larger_avatar']= hg_avatar($row['id'],"100",$row['avatar'],0);
				$row['middle_avatar']= hg_avatar($row['id'],"50",$row['avatar'],0);
				$row['small_avatar']= hg_avatar($row['id'],"10",$row['avatar'],0);
			}
			else 
			{
				$row['larger_avatar']= hg_avatar($row['id'],"larger",$row['avatar']);
				$row['middle_avatar']= hg_avatar($row['id'],"middle",$row['avatar']);
				$row['small_avatar']= hg_avatar($row['id'],"small",$row['avatar']);
			}
			$this->addItem($row);	
		}
		$this->output();		
	}
}

$out = new showApi();

$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>