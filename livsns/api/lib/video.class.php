<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video.class.php 5477 2011-12-26 08:57:02Z repheal $
***************************************************************************/

class video extends BaseFrm
{
	function __construct()
	{
		parent:: __construct();
	}
	
	function __destruct()
	{
		
	}
	
	/**
     *
     * 根据用户ID获取用户信息
     */
	public function getUserById($user_id)
	{		
		if (!$user_id)
		{
			return false;
		}
		str_replace(",", "", $user_id, $count);
		
		include_once(ROOT_PATH . '/lib/user/user.class.php');
		$this->mUser = new user();
		
		$user = $this->mUser->getUserById($user_id);
		if(!$count)
		{
			$info = $user[0];
		}
		else
		{
			$user_id = rtrim($user_id,",");
			if(!empty($user))
			{
				foreach($user as $key => $value)
				{
					$info[$value['id']] = $value;
				}
			}
		}
		if (!$info)
		{
			/**
			 * 用户不存在
			 */
		}
		return $info;
	}
	
	/**
	* 取收藏关联
	* @param $cid 收藏ID
	* @param $type （0视频、1网台、2用户）	
	* @return $ret 收藏信息
	*/
	function get_collect_relevance($user_id,$cid,$type)
	{
		if(!$user_id)
		{
			$info['id'] = 0;
			$info['relation'] = 0;
			return $info;
		}
		if(!$cid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $user_id,
			'cid' => $cid,
			'type' => $type,
			'relation' => 0,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."collects WHERE user_id=".$info['user_id']." AND type=".$info['type']." AND cid IN(".$info['cid'].")";
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			$array['relation'] = 1;
			$infos[$array['cid']] = $array;
		}
		return $infos;
	}
	
	
	/**
	* 取网台关注关联
	* @param $sid 关注ID
	* @return $ret 关注信息
	*/
	function get_concern_relevance($user_id,$sid)
	{
		if(!$user_id)
		{
			$info['id'] = 0;
			$info['relation'] = 0;
			return $info;
		}
		if(!$sid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$info = array(
			'id' => 0,
			'user_id' => $user_id,
			'sid' => $sid,
			'relation' => 0,
		);
		$sql = "SELECT * FROM ".DB_PREFIX."station_concern WHERE user_id=".$info['user_id']." AND sid IN(".$info['sid'].")";
		$query = $this->db->query($sql);
		while($array = $this->db->fetch_array($query))
		{
			$array['relation'] = 1;
			$infos[$array['sid']] = $array;
		}
		return $infos;
	}	
}
?>