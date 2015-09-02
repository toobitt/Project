<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: favorites.php 2866 2011-03-16 13:32:10Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class favoritesInfoApi extends BaseFrm
{
	private $mVideo;
	
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_DIR.'lib/user/user.class.php');
		$this->user = new user();
	}
	
	function __destruct()
	{
		parent::__destruct();
	}

	/**
	* 显示收藏信息(根据类型)
	* @param $type_id
	* @return $info 收藏信息
	*/
	public function show()
	{
		$userinfo = $this->user->verify_credentials(); 	
		if(!$userinfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$type_id = $this->input['type_id']? $this->input['type_id']:1;
		if(!$type_id)
		{
			$sql = "SELECT * FROM ".DB_PREFIX."favorites WHERE member_id=".$userinfo['id'];
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$favorites[] = $row;
			}
		}
		else 
		{
			$sql = "SELECT * FROM ".DB_PREFIX."type";
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$type_infos[$row['id']] = $row;
			}
			$type_info = $type_infos[$type_id];
			
			
			$sql = "SELECT * FROM ".DB_PREFIX."favorites WHERE member_id=".$userinfo['id']." AND type_id=".$type_id;
			
			//file_put_contents('f:show.txt', $sql);
			$query = $this->db->query($sql);
			$cid = "";
			$space = " ";
			while($row = $this->db->fetch_array($query))
			{
				$favorites[] = $row;
				$cid .=$space.$row['cid'];
				$space = ",";
			}
			include_once (ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($type_info['host'],$type_info['apidir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', $type_info['function']);
			$this->curl->addRequestData($type_info['param'], $cid);
			$fa = $this->curl->request($type_info['show_api']);
			if($fa)
			{
				foreach($fa as $key=>$value)
				{
					$fas[$value['id']] = $fa[$key];
					unset($fa[$key]);
				}
			}
			
			
			if($favorites)
			{
				foreach($favorites as $key=>$value)
				{
					$favorites[$key]['type'] = $fas[$value['cid']];
					unset($value['cid'],$value['type_id']);
				}
			}
		}
		$this->setXmlNode('favorites' , 'info');
		$this->addItem($favorites);
		$this->output();
	}
	
	/**
	 * 获取相册收藏
	 */
	
	public function get_albums_fav()
	{
		$userinfo = $this->user->verify_credentials(); 	
		if(!$userinfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$type_id = $this->input['type_id'] ? $this->input['type_id'] : '';

		if(!$type_id)
		{
			$this->errorOutput('未指定收藏类型');	
		}
		
		$sql = "SELECT * FROM ".DB_PREFIX."favorites WHERE member_id=".$userinfo['id']." AND type_id=".$type_id;
		$q = $this->db->query($sql);
		
		$this->setXmlNode('albums_favorites' , 'info');

		while($row = $this->db->fetch_array($q))
		{
			$this->addItem($row);
		}
		
		$this->output();		
	}
	
	/**
	* 添加收藏
	* @param $title
	* @param $cid
	* @param $type_id
	* @param $link
	* @param $schematic
	* @return $info 收藏信息
	*/
	public function create()
	{
		$mInfo = $this->user->verify_credentials();
		if(!$mInfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$collect = array(
			"member_id" => $mInfo['id'],
			"title" => urldecode($this->input['title']?$this->input['title']:""),
			"cid" => $this->input['cid']?$this->input['cid']:0,
			"type_id" => $this->input['type_id']?$this->input['type_id']:0,
			"link" => urldecode($this->input['link']?$this->input['link']:""),
			"schematic" => urldecode($this->input['schematic']?$this->input['schematic']:""),
			"fa_id" => urldecode($this->input['schematic']?$this->input['fa_id']:""),
			"create_time" => time()
		);
		
		$sql = "INSERT INTO ".DB_PREFIX."favorites SET ";
		$con = "";
		$space = " ";
		foreach($collect as $key => $value)
		{
			$con .= $space.$key."='".$value."'";
			$space = ", ";
		}
		$sql = $sql.$con;
		$this->db->query($sql);
		$collect['id'] = $this->db->insert_id();
		
		$this->setXmlNode('favorites' , 'info');
		$this->addItem($collect);
		$this->output();
	}
	
	/**
	* 删除收藏
	* @param $id
	* @return $id 收藏ID
	*/
	public function del()
	{
		$mInfo = $this->user->verify_credentials();
		$id = $this->input['id']? $this->input['id']:0;
		$type = $this->input['type']? $this->input['type']:0;//默认fa_id删除
		
		if(!$mInfo&&!$id)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		if($type)
		{
			$con = "WHERE id=".$id;
		}
		else
		{
			$con = "WHERE fa_id=".$id;
		}
		$sql = "DELETE FROM ".DB_PREFIX."favorites ".$con;
		$this->db->query($sql);
		
		$this->setXmlNode('favorites' , 'info');
		$this->addItem($id);
		$this->output();
	}
	
	
	/**
	 * 取消相册收藏
	 */
	public function cancel_fav()
	{
		$mInfo = $this->user->verify_credentials();
		
		$type_id = $this->input['type'];
		
		$cid = $this->input['cid'];
		
		if(!$mInfo)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		
		$sql = "DELETE FROM " . DB_PREFIX ."favorites WHERE member_id = " . $mInfo['id'] . " AND type_id = " . $type_id . " AND cid = " . $cid;

		file_put_contents('f:/show.txt', $sql);
		$this->db->query($sql);
	}
	
}

$out = new favoritesInfoApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>