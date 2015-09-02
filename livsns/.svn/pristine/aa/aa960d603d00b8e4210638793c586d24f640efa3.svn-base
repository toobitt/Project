<?php 
require_once(ROOT_PATH. 'lib/class/curl.class.php');
class interview_admin extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 
	 * 请求会员接口数据
	 * @param array $conditon key为条件，value为值   如array('k'=>1);
	 */
	public function get_user($conditon)
	{
		$curl = new curl($this->settings['login_url']['host'],$this->settings['login_url']['dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		if (!empty($conditon)){
			foreach ($conditon as $key=>$value)
			{
				$curl->addRequestData($key, $value);
			}
		}
		$ret = $curl->request('member.php'); 
		return $ret;	
	}
	/**
	 * 
	 * 请求会员接口数据 获取会员的总数
	 * 
	 */
	public function get_user_num($conditon)
	{
		$curl = new curl($this->settings['login_url']['host'],$this->settings['login_url']['dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a', 'count');
		if (!empty($conditon)){
			foreach ($conditon as $key=>$value)
			{
				$curl->addRequestData($key, $value);
			}
		}
		$ret = $curl->request('member.php');
		return $ret[0];	
	}
	public function get_sort()
	{
		$curl = new curl('localhost','livsns/api/member/');
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		$ret = $curl->request('member_update.php');
		return $ret;	
	}
	/**
	 * 
	 * 通过用户ID获取用户信息
	 * @param int $id   用户ID
	 */
	public function get_userInfo($id)
	{
		$curl = new curl($this->settings['login_url']['host'],$this->settings['login_url']['dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('member_id', $id);
		$curl->addRequestData('a', 'get_member_by_id');
		$ret = $curl->request('member.php');
		return $ret[0];	
	}
	/**
	 * 添加后增加用户组表的该组下的用户数字段
	 */
	public function add_user_num($user_group_id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'user_group SET user_number = user_number+1  WHERE id='.$user_group_id;
		$this->db->query($sql);
	}
	
	/**
	 * 删除后减少用户组表的该组下的用户数字段
	 */
	public function reduce_user_num($user_group_id)
	{
		$sql = 'UPDATE '.DB_PREFIX.'user_group SET user_number = user_number-1  WHERE id='.$user_group_id;
		$this->db->query($sql);
	}
	/**
	 * 
	 *根据用户组ID判断改变的用户是否是主持人或者嘉宾，如果是更新访谈信息
	 * @param int $user_id				用户ID
	 * @param int $interview_id			访谈ID
	 * @param int $flag					1为访谈添加主持人或者嘉宾，0为删除访谈中的主持人和嘉宾
	 */
	public function change($interview_id,$user_id,$flag=1)
	{
		
		//根据访谈ID和用户取得用户所在的组
		$sql = 'SELECT * FROM '.DB_PREFIX.'interview_user WHERE interview_id='.$interview_id.' AND user_id='.$user_id;
		$res = $this->db->query_first($sql);
		//根据用户ID取得用户名
		$user_info = $this->get_userInfo($user_id);
		$user_name = $user_info['nick_name'];
		
		//获取组的类型
		$sql = 'SELECT role FROM '.DB_PREFIX.'user_group WHERE id='.$res['group'];
		$data = $this->db->query_first($sql);
		
		if($flag){
			if ($data['role'] ==2)
			{
				$sql = 'SELECT moderator FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$m = $this->db->query_first($sql);
				$moder  = unserialize($m['moderator']);
				$moder[$user_id] = $user_name;
				
				$new_moderator = addslashes(serialize($moder));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET moderator ="'.$new_moderator.'" WHERE id='.$interview_id;
				$this->db->query($sql);		 
			}elseif ($data['role'] ==3){
				$sql = 'SELECT honor_guests FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$h = $this->db->query_first($sql);
				$honor  = unserialize($h['honor_guests']);
				$honor[$user_id] = $user_name;
				$new_honor = addslashes(serialize($honor));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET honor_guests ="'.$new_honor.'" WHERE id='.$interview_id;
				$this->db->query($sql);								
			}
			
		}else {
		
			if ($data['role'] ==2)
			{
				$sql = 'SELECT moderator FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$m = $this->db->query_first($sql);
				$moder = unserialize($m['moderator']);
				$u_arr = array($user_name);
				$moder =  array_diff($moder, $u_arr);
				$new_moderator = addslashes(serialize($moder));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET moderator ="'.$new_moderator.'" WHERE id='.$interview_id;
				$this->db->query($sql);		 
			}elseif ($data['role'] ==3){
				$sql = 'SELECT honor_guests FROM '.DB_PREFIX.'interview WHERE id='.$interview_id;
				$h = $this->db->query_first($sql);
				$honor = unserialize($h['honor_guests']);
				$u_arr = array($user_name);
				$honor = array_diff($honor,$u_arr);
				$new_honor = addslashes(serialize($honor));
				$sql = 'UPDATE '.DB_PREFIX.'interview SET honor_guests ="'.$new_honor.'" WHERE id='.$interview_id;
				$this->db->query($sql);
							 	
				
			}
		}
	}
	public function get_group()
	{
		$curl = new curl($this->settings['login_url']['host'],$this->settings['login_url']['dir']);
		$curl->setSubmitType('post');
		$curl->initPostData();
		$curl->addRequestData('a', 'show');
		$ret = $curl->request('group.php');
		return $ret;
	}
	public function addInterviewUser($uid,$interview_id)
	{
		//数据库插入
		$sql = 'INSERT INTO '.DB_PREFIX.'interview_user SET interview_id ='.$interview_id
			.',user_id='.$uid
			.',`group`=0';
		$this->db->query($sql);
		$order = $this->db->insert_id();
		//更新排序ID
		$sql = 'UPDATE '.DB_PREFIX.'interview_user SET order_id ='.$order.' WHERE id='.$order;
		$this->db->query($sql);
		return $order;
	}
	
	public function group_name()
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'user_group';
		$res = $this->db->query($sql);
		$arr = array();
		while (!false == ($r=$this->db->fetch_array($res)))
		{
			$arr[$r['id']] = $r['group_name'];
		}
		return $arr;
	}
	
	public function del_pic($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'files WHERE id IN ('.$id.')';
		$this->db->query($sql);
		return true;
	}
	public function del_online($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'online WHERE interview_id IN ('.$id.')';
		$this->db->query($sql);
		return true;
	}
	public function del_record($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'records WHERE interview_id IN ('.$id.')';
		$this->db->query($sql);
		return true;
	}
	public function del_user($id)
	{
		$sql = 'DELETE FROM '.DB_PREFIX.'interview_user WHERE interview_id IN ('.$id.')';
		$this->db->query($sql);
		return true;
	}
}




