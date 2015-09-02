<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: user.class.php 6229 2012-03-29 00:58:32Z repheal $
***************************************************************************/
class user
{
	private $curl;
	function __construct()
	{
		global $gApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gApiConfig['host'], $gApiConfig['apidir']);
	}

	function __destruct()
	{
	}
		
	public function createUser(&$data)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->initPostData();
		foreach($data as $key => $value)
		{
			$this->curl->addRequestData( $key, $value);
		}
		$ret = $this->curl->request('users/create.php');
		return $ret[0];
	}
	
	public function getUserById($id,$type="base")
	{	
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('type', $type);	
		return $this->curl->request('users/show.php');
	}

	public function getUserById_Group($id)
	{	
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('a', 'getUserById_Group');
		return $this->curl->request('users/show.php');
	}

	public function getStatus($ids)
	{	
		$this->curl->setSubmitType('get');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('users/user_status.php');
	}
	

	public function getUserByName($name,$type="base")
	{
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('screen_name',$name);
		$this->curl->addRequestData('type', $type);
		return $this->curl->request('users/show.php');		
	}
	public function verifyUsername($username)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('username', $username);
		return $this->curl->request('users/verify.php');	 	
	}
	
	public function verifyEmail($email)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('email', $email);
		return $this->curl->request('users/verify.php');	 	
	}

	public function verify_credentials($user = '', $pass = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user', $user);
		$this->curl->addRequestData('pass', $pass);
		$ret = $this->curl->request('users/verify_credentials.php');
		
		return $ret[0];
	}
	
	public function verify_user_exist($user,$pass)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user', $user);
		$this->curl->addRequestData('pass', $pass);
		$ret = $this->curl->request('users/verify_user_exist.php');
		return $ret[0];	 
	}
	
	public function update_profile($userinfo)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $userinfo['id']);
		$this->curl->addRequestData('email',$userinfo['email'] );
		$this->curl->addRequestData('cur_email',$userinfo['cur_email'] );
		$this->curl->addRequestData('username', $userinfo['username']);
		$this->curl->addRequestData('cur_username', $userinfo['cur_username']);
		$this->curl->addRequestData('location', $userinfo['location']);
		$this->curl->addRequestData('birthday',$userinfo['birthday']);
		$this->curl->addRequestData('location_code', $userinfo['location_code']);
		$this->curl->addRequestData('sex', $userinfo['sex']);
		$this->curl->addRequestData('truename', $userinfo['truename']);
		$this->curl->addRequestData('privacy', $userinfo['privacy']);
		$this->curl->addRequestData('qq', $userinfo['qq']);
		$this->curl->addRequestData('mobile', $userinfo['mobile']);
		$this->curl->addRequestData('msn', $userinfo['msn']);
		$this->curl->addRequestData('tv', $userinfo['tv']);
		$ret = $this->curl->request('users/update_profile.php');
		return $ret[0];				
	}
	
	public function update_type($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'updateType');
		$this->curl->addRequestData('id',$id);
		return $this->curl->request('users/update_profile.php');	
	}

	public function update_last_status($user_id,$last_status_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_last_status');
		$this->curl->addRequestData('user_id',$user_id);
		$this->curl->addRequestData('last_status_id',$last_status_id);
		return $this->curl->request('users/update_profile.php');
	}
	
	public function update_profile_image($file, $cut_info = '' , $uid = 0)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $uid);
		$this->curl->addRequestData('cut_info', $cut_info);
		$this->curl->addFile($file);
		return $this->curl->request('users/upload_profile_image.php');	
	}
		
	public function update_password($password)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();		
		$this->curl->addRequestData('a', 'updatePassword');
		$this->curl->addRequestData('password', $password);
		$ret = $this->curl->request('users/update_password.php');			
		return $ret[0];			
	}
	
	public function verify_password($password)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verifyPassword');
		$this->curl->addRequestData('password', $password);
		$ret = $this->curl->request('users/update_password.php');			
		return $ret[0];
	}
	
	public function add_location($group_id,$gname,$glat,$glng)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('group_id', $group_id);
		$this->curl->addRequestData('group_name', $gname);
		$this->curl->addRequestData('glat', $glat);
		$this->curl->addRequestData('glng', $glng);
		return $this->curl->request('users/location.php');
	}

	public function get_location()
	{
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getLocation'); 
		return $this->curl->request('users/location.php');
	}
	
	public function del_location($user_id,$location)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delLocation');
		$this->curl->addRequestData('user_id', intval($user_id));
		$this->curl->addRequestData('location',$location);
		return $this->curl->request('users/location.php');
	}
	public function destroy_attention_count()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('action', 'reduce');
		return $this->curl->request('users/destroy_attention_count.php');	
	}
	
	/**
	 * 获取用户权限
	 */
	public function get_user_authority($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		return $this->curl->request('users/get_authority.php');	
	}
	
	/**
	 * 获取绑定信息
	 * 
	 */
	public function get_bind_info()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'get_bind_info');
		return $this->curl->request('oauth/oauth.php');	
	}

	/**
	 * 检测这条点滴是否是同步发送的点滴
	 */
	public function check_syn_status($status_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('func', 'check_syn_status');
		$this->curl->addRequestData('status_id', $status_id);
		$this->curl->addRequestData('type', 1);
		return $this->curl->request('oauth/oauth.php');
	}
	
	/**
	 * 
	 * 添加积分日志
	 * @param int $rule_id 积分类型
	 * @param int $oid 被回复或被评论的ID 注册和登录为0
	 */
	public function add_credit_log($rule_id , $oid = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('rule_type', $rule_id);
		$this->curl->addRequestData('oid', $oid);
		$this->curl->request('users/credit_log.php');	
	}

	/**
	 * 
	 * 获取会员
	 */
	public function getVip($page = 0 , $count = 6 , $total = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('total', $total);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('a', 'getVip');
		return $this->curl->request('users/show.php');		
	}

	
	/**
	 * 添加关注
	 */
	public function create($user_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id',$user_id);		
		$this->curl->request('friendships/create.php');	
	}
	
	/**
	 * 更新用户视频数(增加)
	 */
	public function update_video_count($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id' , $user_id);
		$this->curl->addRequestData('a', 'update_video');
		return $this->curl->request('users/show.php');	
	}
	
	/**
	 * 更新用户视频数(减少)
	 */
	public function delete_video_count($video_count)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('video_count' , $video_count);
		$this->curl->addRequestData('a', 'delete_video_nums');
		return $this->curl->request('users/show.php');		
	}
	
	/**
	 * 验证用户是否为管理员
	 */
	public function check_isAdmin($userId)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('userId' , $userId);
		$this->curl->addRequestData('a', 'check_isAdmin');
		return $this->curl->request('users/verify_user_isAdmin.php');	
	}
	
	/**
	* 验证邀请码
	*@param $invite_code;
	*/
	public function verify_invite_code($invite_code)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verify_invite_codes');
		$this->curl->addRequestData('invite_code' , $invite_code);
		$ret = $this->curl->request('users/create.php');	
		return $ret;
	}
	
	/**
	* 关闭注册，未有邀请码或邀请码不正确，记录email
	*@param $invite_code;
	*/
	public function record_email($email)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'record_email');
		$this->curl->addRequestData('email' , $email);
		$ret = $this->curl->request('users/create.php');	
		return $ret;
	}
	
	/**
	 * 用QQ账户登录（同步数据）
	 * @param $nickname QQ昵称
	 * @param $openid 返回的唯一ID
	 * retrun $info
	 */
	public function qq_login($nickname,$openid,$avatar)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verfiy');
		$this->curl->addRequestData('nickname' , $nickname);
		$this->curl->addRequestData('openid' , $openid);
		$this->curl->addRequestData('avatar' , $avatar);
		$ret = $this->curl->request('users/qq.php');	
		return $ret[0];
	}
	

	/**
	 * 
	 * 手机号码绑定
	 * @param $tel
	 * return $info 用户部分信息
	 */
	public function cellphone($tel)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('tel', $tel);
		$ret = $this->curl->request('users/phone.php');
		return $ret[0];
	}
	
	/**
	 * 
	 * 解除绑定
	 * return $info 用户id
	 */
	public function unbindPhone()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$ret = $this->curl->request('users/phone.php');
		return $ret[0];
	}
	
	
	/**
	 * 根据绑定手机号码返回用户登录信息
	 * @param $tel 
	 * return $info 用户部分信息
	 */
	public function getUserByCellphone($tel)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('tel', $tel);
		$ret = $this->curl->request('users/phone.php');
		return $ret[0];
	}
}
?>