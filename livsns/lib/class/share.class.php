<?php
class share
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_share']['host'], $gGlobalConfig['App_share']['dir']);
	}

	function __destruct()
	{
	}
	
	/**
	 * $access_plat_token  多个逗号隔开
	 * 
	 * */
	public function get_plat($access_plat_token='', $appid = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('appid', $appid);
		return $ret = $this->curl->request('get_access_plat.php');
	}
	
	/**
	 * 
	 * 
	 * */
	public function get_plat_type($appid='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid', $appid);
		return $ret = $this->curl->request('get_plat_type.php');
	}
	
	/**
	 * 获取平台详细信息
	 */
	public function get_plat_info($platid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('platid', $platid);
		$this->curl->addRequestData('a', 'get_plat_info');
		$ret = $this->curl->request('get_plat_type.php');
		return $ret[0];
	}	
	
	/**
	 * 
	 * 
	 * */
	public function get_type($appid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid', $appid);
		$this->curl->addRequestData('a','get_type');
		return $ret = $this->curl->request('get_plat_type.php');
	}
	
	public function oauthlogin($id,$access_plat_token='',$appid = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		return $ret = $this->curl->request('oauthlogin.php');
	}
	
	
	public function accesstoken($access_plat_token='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		return $ret = $this->curl->request('accesstoken.php');
	}
	
	public function get_user_timeline($appid,$platid,$uid='',$name='',$access_plat_token='',$since_id='',$page='',$count='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('uid',$uid);
		$this->curl->addRequestData('name',$name);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('since_id',$since_id);
		$this->curl->addRequestData('page',$page);
		$this->curl->addRequestData('count',$count);
		$ret = $this->curl->request('get_user_timeline.php');
		return $ret[0];
	}
	
	//获取用户关注人的微博
	public function get_home_timeline($appid,$platid,$access_plat_token='',$since_id='',$page='',$count='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('uid',$uid);
		$this->curl->addRequestData('name',$name);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('since_id',$since_id);
		$this->curl->addRequestData('page',$page);
		$this->curl->addRequestData('count',$count);
		$ret = $this->curl->request('get_home_timeline.php');
		return $ret[0];
	}
	
	//关注一个用户
	public function friendships_create($appid,$platid,$uid='',$name='',$access_plat_token='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('uid',$uid);
		$this->curl->addRequestData('name',$name);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$ret = $this->curl->request('friendships_create.php');
		return $ret[0];		
	}	
	
	
	
	public function get_user($uid='',$name='',$access_plat_token='')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('uid',$uid);
		$this->curl->addRequestData('name',$name);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$ret = $this->curl->request('get_user.php');
		return $ret;
	}
	
	public function search_user($appid,$id,$access_plat_token='',$keyword)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('keyword',$keyword);
		$ret = $this->curl->request('search_user.php');
		return $ret[0];
	}
	
	public function get_comment($platid,$access_plat_token,$weibo_id,$since_id,$page,$count,$since_time)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('weibo_id',$weibo_id);
		$this->curl->addRequestData('since_id',$since_id);
		$this->curl->addRequestData('since_time',$since_time);
		$this->curl->addRequestData('page',$page);
		$this->curl->addRequestData('count',$count);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$ret = $this->curl->request('get_comments.php');
		return $ret[0];		
	}
	
	public function get_detail($platid,$access_plat_token,$weibo_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('weibo_id',$weibo_id);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$ret = $this->curl->request('get_detail.php');
		return $ret[0];		
	}

	public function get_mention($appid,$platid,$access_plat_token,$since_id,$page,$count)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('since_id',$since_id);
		$this->curl->addRequestData('page',$page);
		$this->curl->addRequestData('count',$count);
		$ret = $this->curl->request('get_mention.php');
		return $ret[0];		
	}
	
	public function toshare($appid,$platid,$plat_type,$access_plat_token,$text,$pic = '',$title = '', $section_id = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('appid',$appid);
		$this->curl->addRequestData('id',$platid);
		$this->curl->addRequestData('plat_type',$plat_type);
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('text',$text);
		$this->curl->addRequestData('picpath',$pic);
        $this->curl->addRequestData('title',$title);
        $this->curl->addRequestData('section_id',$section_id);
		$this->curl->addRequestData('a','toshare');
		$ret = $this->curl->request('update.php');
		return $ret[0];
	}
	
	public function get_auth_user($access_plat_token) 
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('access_plat_token',$access_plat_token);
		$this->curl->addRequestData('a','get_auth_user');
		$ret = $this->curl->request('get_user.php');
		return $ret[0];
	}
	
	public function get_user_list($auth = true) 
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('offset', 0);
		$this->curl->addRequestData('count', 200);
		$this->curl->addRequestData('auth', $auth);
		$this->curl->addRequestData('a','show');
		$ret = $this->curl->request('/admin/user.php');
		return $ret;
	}
}
?>
