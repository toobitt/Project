<?php
/*$Id: comment.class.php 3795 2011-04-26 02:43:35Z repheal $*/

class comment
{
	function __construct()
	{
		global $gMysqlStatusesConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gMysqlStatusesConfig['host'], $gMysqlStatusesConfig['apidir']);
	}
	
	function __destruct()
	{
		
	}
	
	/**
	 * 根据某条点滴id返回该点滴的评论列表
	 * @param status_id:点滴id
	 * @param total:每页返回的条数，默认每页取10条
	 * @param page:返回结果页，默认返回第一页
	 */
	public function get_comment_list($status_id,$count=50,$page=1)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $status_id);//点滴ID
		$this->curl->addRequestData('count', $count);//每页显示的条数
		$this->curl->addRequestData('page', $page);//返回第几页
		return $this->curl->request('comment_list.php');	
	}
	
	/**
	 * 评论某一条点滴
	 * @param id:要评论的点滴的id
	 * @param content：评论的内容
	 * @param cid：某条评论id，回复某条评论时需传递此参数
	 */
	public function comment($id,$content,$cid = '')
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);//点滴ID
		$this->curl->addRequestData('content', $content); 
		$this->curl->addRequestData('cid', $cid); 
		$result = $this->curl->request('comment.php');
		return $result[0];
	}
	
	/**
	 * 回复某一条评论
	 * @param cid:要回复的某条评论的id
	 * @param status_id ： 点滴id
	 * @param content ： 回复内容
	 */
	public function reply_comment($cid,$status_id,$content)
	{
		
		
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('status_id', $status_id);//点滴ID
		$this->curl->addRequestData('text', $content); 
		$this->curl->addRequestData('cid', $cid); 
		$result = $this->curl->request('comment_reply.php');
		return $result[0];
	}
	
	/**
	 * 删除用户自己发布的一条评论
	 * @param cid : 要删除的评论id
	 */
	public function del_comment($cid)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('cid', $cid); 
		$result = $this->curl->request('comment_destory.php');
		return $result[0];
	}
	
	/**
	 * 获取用户自己的评论列表
	 */
	public function get_my_comments($since_id = 0,$max_id = 0,$page = 1, $count = 20,$keywords = NULL)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData(); 
		$this->curl->addRequestData('since_id', $since_id);
		$this->curl->addRequestData('max_id', $max_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('keywords', $keywords); 
		$result = $this->curl->request('comments_by_me.php'); 
		return $result;
	}
	
	/**
	 * 获取用户收到的评论列表
	 */
	public function get_resived_comments($since_id = 0,$max_id = 0,$page = 1, $count = 20,$keywords = NULL)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData(); 
		$this->curl->addRequestData('since_id', $since_id);
		$this->curl->addRequestData('max_id', $max_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('keywords', $keywords); 
		$result = $this->curl->request('comments_to_me.php'); 
		return $result;
	} 
	
	/**
	 * 批量删除用户的评论
	 */
	public function delete_more_comments($comment_ids,$type)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData(); 
		$this->curl->addRequestData('commentIds', $comment_ids);
		$this->curl->addRequestData('type', $type); 
		$result = $this->curl->request('destroy_batch.php'); 
		return $result;
	}
	
	
}