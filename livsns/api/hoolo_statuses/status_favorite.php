<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: status_favorite.php 2774 2011-03-15 06:58:54Z wang $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class statusApi extends BaseFrm
{
	private $mUser;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	* 发布一条点滴信息
	*/
	public function update()
	{	
//		$this->input['id'] = 72778;
//		$this->input['text'] = "";
//		$this->input['source'] = "点滴";
		
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
	}
	
	/**
	 * 修改点滴的收藏数目
	 * @param $video_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function favorite_count()
	{
		$userinfo = $this->mUser->verify_credentials();
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		$status_id = $this->input['status_id']? $this->input['status_id']:73442;
		$type = $this->input['type']? $this->input['type']:0;//默认增加
		
		if(!$status_id)
		{
			$this->errorOutput(OBJECT_NULL);	
		}
		
		$sql = "UPDATE " . DB_PREFIX . "status_extra SET collect_count=";
		if($type)
		{
			$sql .="collect_count+1";
		}
		else 
		{
			$sql .="collect_count-1";
		}
		
		$sql .= " WHERE status_id = ".$status_id;
		$this->db->query($sql);
		$this->setXmlNode('status' , 'info');
		$this->addItem($status_id);
		$this->output();
	}	
}

$out = new statusApi();
$action = $_REQUEST['a'];
if (!method_exists($out,$action))
{
	$action = 'favorite_count';
}
$out->$action();
?>