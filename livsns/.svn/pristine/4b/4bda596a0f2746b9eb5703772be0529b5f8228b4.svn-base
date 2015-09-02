<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video.php 2146 2011-02-19 07:52:14Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

class recommendApi extends BaseFrm
{
	private $mUser;
	
	function __construct()
	{
		parent::__construct();
		include_once(ROOT_PATH . 'api/lib/user.class.php');
		$this->mUser = new user();		
	}
	
	function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 添加推荐
	 */
	public function add_recommend()
	{				
		$rid = intval($this->input['rid']);          //视频ID
		$type = intval($this->input['type']);        //推荐类型
		$author = urldecode($this->input['author']); //推荐者 
		$time = time();                              //推荐时间
		$content = urldecode($this->input['content']);
		$sql = "INSERT INTO " . DB_PREFIX . "recommend 
					   (rid , type , author , time ) 
					    VALUE
					   ($rid , $type , '$author' , $time)";
		
		$this->db->query($sql);		
		$insert_id = $this->db->insert_id();
		
		$sql = "INSERT INTO " . DB_PREFIX . "content SET id = " . $insert_id . " , content = '" . $content . "'";

		$this->db->query($sql);		
	}

	/**
	 * 取消推荐
	 */
	public function delete_recommend()
	{
		$rid = intval($this->input['rid']);     //视频ID
		$type = intval($this->input['type']);   //推荐类型
		$sql = "SELECT id FROM " . liv_recommend . " WHERE rid = " . $rid . " AND type = " . $type;
		
		$r = $this->db->query_first($sql);
		$delete_id = $r['id'];  	
		
		$sql = "DELETE FROM " . liv_recommend . " WHERE rid = " . $rid . " AND type = " . $type;
		$this->db->query($sql);
		
		$sql = "DELETE FROM " . liv_content . " WHERE id = " . $delete_id;
		$this->db->query($sql);  	
	}
}

$out = new recommendApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'add_recommend';
}
$out->$action();

?>