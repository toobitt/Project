 <?php
/*$Id: comment_destory.php 2774 2011-03-15 06:58:54Z wang $*/

 //删除某条点滴
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
class destoryAPI extends BaseFrm
{
	private $mUser,$mStatus;
	function __construct()
	{
		parent::__construct();
			
		include_once(ROOT_DIR . 'lib/user/user.class.php');
		$this->mUser = new user();
		include_once(ROOT_DIR . 'lib/class/status.class.php');
		$this->mStatus = new status();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function destory()
	{
		$userinfo = $this->mUser->verify_credentials(); 
		if(!$userinfo['id'])
		{
			$this->errorOutput(USENAME_NOLOGIN);
		}
		
		$cid = intval($this->input['cid']);
		!$cid && $this->errorOutput(OBJECT_NULL);
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'status_comments WHERE id = ' . $cid;
		$query_array = $this->db->query_first($sql);
		
		!$query_array && $this->errorOutput(OBJECT_NULL);
		$this->db->query('UPDATE ' . DB_PREFIX . 'status_comments SET flag = 1 WHERE id = ' . $cid);
	//	$this->db->query('DELETE FROM ' . DB_PREFIX . 'status_comments WHERE id = ' . $cid);
		$this->db->query('UPDATE ' . DB_PREFIX . 'status_extra SET comment_count = CASE WHEN comment_count > 1 THEN comment_count - 1 ELSE 0 END WHERE status_id = ' . $query_array['status_id']);
		$member_id = $query_array['member_id'];
		$members = $this->mUser->getUserById($member_id); 
		$status_info = $this->mStatus->show($query_array['status_id']);
		$return_array = array(
			'id' => $cid,
			'create_at' => $query_array['comment_time'],
			'text' => $query_array['content'],
			'user' => $members[0],
			'status'=>$status_info[0]
		); 
		unset($query_array);
		$this->addItem($return_array);
		$this->output();
	}
}

$out = new destoryAPI();
$out->destory();
