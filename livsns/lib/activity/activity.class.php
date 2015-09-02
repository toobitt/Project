<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: activity.class.php 4041 2011-06-07 02:29:13Z repheal $
***************************************************************************/
class activity{
	
	private $curl;
	
	function __construct()
	{
		global $gApiConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gApiConfig['host'], $gApiConfig['apidir']);
	}
	
	function __destruct()
	{}
	
	/**
	* 创建活动（线上，线下）
	* @param $action_name 活动名称
	* @param $action_sort（0：线上，1：线下）
	* @param $type_id 活动类型
	* @param $action_img 活动封面
	* @param $start_time 开始时间
	* @param $end_time 结束时间
	* @param $place 活动地点
	* @param $need_pay 判断是否需要花销0：不要 有内容：要
	* @param $need_num 活动人数的上限
	* @param $introduce 对活动的描述
	* @param $concern '对于是否关注该活动发起者 0：不要，1：要
	* @param $contact 对联系方式的要求 0：不要，1：要
	* @param $rights 对权限审核的要求 0：不要，1：要
	* @param $media_id 关联微博的媒体ID，用于发布微博时封面为分享图片
	* @return $info 活动信息
	*/
	public function create_activity($info)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		foreach($info as $key => $value)
		{
			$this->curl->addRequestData($key, $value);
		}
		$ret = $this->curl->request('activity/create.php');
		return $ret[0];
	}
	
	/**
	* 上传活动封面
	* @param $file 活动封面
	* @param $file_name 用户名
	* @return $info 活动信息
	*/
	public function cover_activity($file, $file_name)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'cover');
		$this->curl->addFile($file);
		$this->curl->addRequestData('file_name', $file_name);
		$ret = $this->curl->request('activity/create.php');
		return $ret[0];
	}

	/**
	* 参与活动（线上，线下）
	* @param $action_id 活动id
	* @param $obj_id 用于参与活动是否需要关注该对象的判断（帖子中是group_id）
	* @param $tel 手机号
	* @param $leave_words 申请时的留言
	* @return $info 申请信息
	*/
	function create_activity_apply($action_id,$obj_id,$tel,$leave_words)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'apply');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('obj_id', $obj_id);
		$this->curl->addRequestData('tel', $tel);
		$this->curl->addRequestData('leave_words', $leave_words);
		$ret = $this->curl->request('activity/create.php');
		return $ret[0];
	}

	/**
	* 取消参与
	* @param $id 参与
	* @param $action_id 活动id
	* @return $info 
	*/
	function delete_activity_apply($action_id,$id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete_apply');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/create.php');
		return $ret[0];
	}

	/**
	* 更新活动（线上，线下）
	* @param $id 活动Id
	* @param $action_name 活动名称
	* @param $type_id 活动类型
	* @param $action_img 活动封面
	* @param $start_time 开始时间
	* @param $end_time 结束时间
	* @param $place 活动地点
	* @param $need_pay 判断是否需要花销0：不要 有内容：要
	* @param $need_num 活动人数的上限
	* @param $introduce 对活动的描述
	* @param $concern '对于是否关注该活动发起者 0：不要，1：要
	* @param $contact 对联系方式的要求 0：不要，1：要
	* @param $rights 对权限审核的要求 0：不要，1：要
	* @return $info 活动信息
	*/
	public function update_activity($info)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update');
		foreach($info as $key => $value)
		{
			$this->curl->addRequestData($key, $value);
		}
		$ret = $this->curl->request('activity/update.php');
		return $ret[0];
	}
	
	/**
	 * 更新某个活动的用户权限(包括批量)
	 * @param $action_id 要更新的对象的用户ID
	 * @param $user_id 要更新的对象的用户ID
	 * @param $type (1:待审核；2:审核通过；3,审核未通过)
	 * return $info 
	 */
	public function update_rights($action_id,$user_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_rights');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('activity/update.php');
		return $ret;
	}
	
		
	/**
	 * 更新活动链接（单个）
	 * @param $action_id 要更新的对象的用户ID
	 * @param $link 要更新的内容链接
	 * return $info 
	 */
	public function update_link($action_id,$link)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_link');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('link', $link);
		$ret = $this->curl->request('activity/update.php');
		return $ret[0];
	}

	/**
	* 查询活动信息(支持多个)
	* @param $id 活动Id
	* @return $info 活动信息
	*/
	public function show_activity($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/show.php');
		return $ret;
	}

	/**
	 * 查询某个（多个活动下的申请人）
	 * @param $action_id
	 * return $info 活动信息以及
	 */
	public function show_activity_apply($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show_apply');
		$this->curl->addRequestData('action_id', $action_id);
		$ret = $this->curl->request('activity/show.php');
		return $ret;
	}
	
	/**
	* 查询某个(多个)用户的活动信息
	* @param $user_id 用户ID
	* @return $info 活动信息
	*/
	function show_user_activity()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show_user_activity');
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('activity/show.php');
		return $ret;
	}
	
	/**
	* 查询活动编辑权限
	* @param $id 活动Id
	* @return $num 剩余编辑次数
	*/
	public function edit_count($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'edit_count');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}

	/**
	* 查询活动人数上线
	* @param $id 活动Id
	* @return 
	*/
	public function need_num($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'need_num');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
	
	/**
	* 查询是否需要联系方式
	* @param $id 活动Id
	* @return 
	*/
	public function is_contact($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'is_contact');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
	
	/**
	* 查询是否需要审核
	* @param $id 活动Id
	* @return 
	*/
	public function check_rights($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'check_rights');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
	
	/**
	* 查询是否需要关注某个对象（变更）
	* @param $obj_id 对象ID
	* @return 
	*/
	public function check_concern($obj_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'check_concern');
		$this->curl->addRequestData('obj_id', $obj_id);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}

	/**
	* 查询某个用户的活动信息（包括创建，收藏，申请）标识
	* @param $user_id 用户ID
	* @return $info 活动信息
	*/
	public function show_my_activity($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show_my_activity');
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('activity/show.php');
		return $ret;
	}

	/**
	* 显示收藏活动
	* @param $action_id 活动标识（ID）
	* @return 收藏信息
	*/
	public function show_collect($action_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('action_id', $action_id);
		$ret = $this->curl->request('activity/collect.php');
		return $ret[0];
	}
	
	/**
	* 收藏活动
	* @param $action_id 活动标识（ID）
	* @return $info
	*/
	public function create_collect($action_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('action_id', $action_id);
		$ret = $this->curl->request('activity/collect.php');
		return $ret[0];
	}

	/**
	* 取消收藏活动
	* @param $action_id 活动标识（ID）
	* @param $id 收藏（ID）
	* @return $action_id
	*/
	public function delete_collect($action_id,$id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('activity/collect.php');
		return $ret[0];
	}
		
	/**
	* 查询用户活动类型
	* @return $info
	*/
	public function show_activity_type()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show_activity_type');
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
	
	/**
	* 查询某个活动的感兴趣用户
	* @param $action_id 活动ID
	* @param $page 页码
	* @param $count 总数
	* @return $info 微博信息（包含用户信息）
	*/
	function get_collect_user($action_id,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_collect_user');
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
	
	/**
	* 查询某个活动的参与用户
	* @param $action_id 活动ID
	* @param $page 页码
	* @param $count 总数
	* @return $info 微博信息（包含用户信息）
	*/
	function get_join_user($action_id,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_join_user');
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('action_id', $action_id);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('activity/show.php');
		return $ret[0];
	}
}
?>
