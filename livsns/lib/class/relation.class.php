<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: relation.class.php 3052 2011-03-24 10:13:32Z repheal $
***************************************************************************/
class Relation{
	
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
		
	/**
	 * 
	 * 获取该用户粉丝
	 * @param $user_id
	 * @param $page 
	 * @param $count 
	 * @return $ret 结果
	 */
	public function get_fans($user_id,$page=0,$count=20)
	{	
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('friendships/followers.php');	
	}
	
	/**
	 * 获取当前用户和取出用户的关系：
	 * 当前用户是否关注了这些用户
	 * @param $user_id 用户ID
	 * @param $ids 对象ID
	 * @return $ret 结果
	 */
	public function get_relation($user_id , $ids)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('friendships/user_relation.php');		
	}
	
	/**
	 * 将用户加入黑名单
	 * @param $user_id 
	 * @return $ret 结果 
	 */
	public function addBlock($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		return $this->curl->request('Blocks/create.php');	
	}
	
	/**
	 * 返回搜索结果
	 * @param $screen_name 关键词
	 * @param $page 
	 * @param $count 
	 * @return $ret 结果
	 */
	public function get_search_follow($screen_name,$page=0,$count=20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $screen_name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('friendships/follow_search.php');
	}
	
	
	/**
	 * 获取该用户关注用户
	 * @param $user_id 
	 * @param $page 
	 * @param $count 
	 * @return $ret 结果
	 */
	public function get_friends($user_id,$page=0,$count=20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);		
		return $this->curl->request('friendships/friends.php');	
	}
	
	/**
	 * 获取当前用户和取出用户的关系：
	 * 取出用户是否关注了当前用户
	 * @param $user_id 用户ID
	 * @param $ids 对象ID
	 * @return $ret 结果
	 */
	public function get_relation_friend($user_id , $ids)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('ids', $ids);
		return $this->curl->request('friendships/user_relation_friends.php');		
	}
	
	
	/**
	* 返回搜索结果（关注）
	* @param $screen_name 关键词
	* @param $page 
	* @param $count 
	* @return $ret 结果
	*/
	public function get_search_friend($screen_name,$page=0,$count=20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('screen_name', $screen_name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('friendships/friend_search.php');
	}
	
	/**
	* 恢复评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	public function recover_comment($id,$cid,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'recover');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/comment.php');
		return $ret[0];
	}
	
}
?>