<?php
/***************************************************************************
* LivSNS 0.1
* (C)2009-2010 HOGE Software.
*
* $Id: status.class.php 12433 2012-10-11 09:59:16Z repheal $
***************************************************************************/
class status
{
	function __construct()
	{
		global $gGlobalConfig;
		include_once (ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($gGlobalConfig['App_status']['host'], $gGlobalConfig['App_status']['dir']);	
	}

	function __destruct()
	{
	}
	
	public function update($str,$source,$id = 0, $user_id = 0,$type = "",$pic_id = 0 , $ip = '' , $time = '',$html = 0)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);//点滴ID
		$this->curl->addRequestData('text', $str);//点滴内容
		
		$this->curl->addRequestData('ip', $ip);//点滴内容
		$this->curl->addRequestData('time', $time);//点滴内容
		
		$this->curl->addRequestData('source', $source);//发布来源
		$this->curl->addRequestData('user_id', $user_id);//发布来源
		$this->curl->addRequestData('type', $type);//媒体信息类型
		$this->curl->addRequestData('pic_id', $pic_id);//图片ID
		$this->curl->addRequestData('html', $html);
		$ret = $this->curl->request('update.php');
		return $ret[0];
	}
	
	
	/**
	 * 记录同步关系
	 * 
	 */
	public function syn_relation($id , $syn_id , $type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('a', 'add_syn_relation');
		$this->curl->addRequestData('status_id', $id);
		$this->curl->addRequestData('syn_id', $syn_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->request('update.php');	
	}
	
	public function destroy($status_id)
	{
		$this->curl->setSubmitType('post');		
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $status_id);//点滴ID
		return $this->curl->request('destroy.php');			
	}
	public function friends_timeline($id,$getTotal,$page = 0,$count = 20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('user_id', $id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('gettoal', $getTotal);
		return $this->curl->request('friends_timeline.php');	
	}

	public function newlist_timeline($since_id,$user_id,$getTotal,$page = 0,$count = 20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('since_id', $since_id);
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('gettoal', $getTotal);
		return $this->curl->request('friends_timeline.php');	
	}
	
	public function user_timeline($userid,$gettoal,$page = 0,$count = 20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('user_id', $userid);		
		$this->curl->addRequestData('gettoal', $gettoal);		
		return $this->curl->request('user_timeline.php');	
	}
	
	public function show($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		return $this->curl->request('show.php');
	}

	public function public_timeline($page,$count = 50, $since_id = 0, $ori = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('ori', $ori);
		$this->curl->addRequestData('since_id', $since_id);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('public_timeline.php');	
	}
		
	
	public function mentions($gettoal,$page,$count = 20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('gettoal', $gettoal);	
		return $this->curl->request('mentions.php');	
	}
	
	public function search($keywords,$page = 0,$count = 20 , $order_type = 0 , $newest_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('q', $keywords);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('order_type', $order_type);
		$this->curl->addRequestData('newest_id', $newest_id);
		$this->curl->addRequestData('count', $count);
		return $this->curl->request('search.php');	
	}
	
	public function verifystatus()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verifystatus');
		$ret = $this->curl->request('search.php');	
		return $ret[0];
	}
	
	public function getTopic()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$ret = $this->curl->request('topic.php');	
		return $ret[0];
	}
	
	public function listTopic($perpage,$curpage)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('perpage',$perpage);
		$this->curl->addRequestData('curpage',$curpage);
		return $this->curl->request('topic.php');	
	}
	
	public function editTopic($title,$topicId)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','editTopic');
		$this->curl->addRequestData('title',$title);
		$this->curl->addRequestData('topicId',$topicId);
		return $this->curl->request('topic.php');	
	}
	
	public function delTopic($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delTopic');
		$this->curl->addRequestData('id',$id);
		return $this->curl->request('topic.php');	
	}
	
	public function verifyTopic($id,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verifyTopic');
		$this->curl->addRequestData('id',$id);
		$this->curl->addRequestData('type',$type);
		return $this->curl->request('topic.php');	
	}
	
	public function searchTopic($search_condition,$perpage,$curpage)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'searchTopic');
		$this->curl->addRequestData('search_condition',$search_condition);
		$this->curl->addRequestData('perpage',$perpage);
		$this->curl->addRequestData('curpage',$curpage);
		return $this->curl->request('topic.php');	
	}

	public function addTopicFollow($topic)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('topic', $topic);
		$ret = $this->curl->request('topic_follow.php');
		return $ret[0];
	}
	
	public function getTopicFollow($topic = "")
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('topic', $topic);
		$ret = $this->curl->request('topic_follow.php');
		return $ret[0];
	}
	
	public function delTopicFollow($topic)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('topic', $topic);
		$ret = $this->curl->request('topic_follow.php');
		return $ret[0];
	}
	
	public function addKeywords($keyword,$result_count)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('keywords', $keyword);
		$this->curl->addRequestData('result_count', $result_count);
		return $this->curl->request('keywords.php');
	}
	
	public function getKeywords($keyword,$result_count)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('keywords', $keyword);
		$this->curl->addRequestData('result_count', $result_count);
		return $this->curl->request('keywords.php');
	}
	
	public function uploadeImage($file){
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','uploadeImage');
		$this->curl->addFile($file);
		$ret = $this->curl->request('upload.php');		
		return $ret[0];
	}

	public function uploadeImageMore($files){
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'uploadeImageMore');
		$this->curl->addRequestData('filenames', $files);
		$ret = $this->curl->request('upload.php');
		return $ret;
	}
	
	
	public function uploadVideo($url)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'uploadVideo');
		$this->curl->addRequestData('url', $url);
		$ret = $this->curl->request('upload.php');		
		return $ret[0];
	}
	
	public function deleteMedia($id,$url)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('url', $url);
		$ret = $this->curl->request('upload.php');		
		return $ret[0];
	}
	
	public function updateMedia($status_id,$media_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update');
		$this->curl->addRequestData('status_id', $status_id);
		$this->curl->addRequestData('media_id', $media_id);
		$ret = $this->curl->request('upload.php');		
		return $ret[0];
	}

	public function getMediaByStatusId($status_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('status_id', $status_id);
		$ret = $this->curl->request('upload.php');		
		return $ret[0];
	}


	/**
	 * 修改点滴的收藏数目
	 * @param $status_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function status_favorite_count($status_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'favorite_count');
		$this->curl->addRequestData('status_id', $status_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('status_favorite.php');
		return $ret;
	}
	
	public function getPicById($pic_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_pic');
		$this->curl->addRequestData('pic_id', $pic_id);
		$ret = $this->curl->request('upload.php');		
		return $ret;	
	}
	
	/**
	 * 获取微博数目
	 */
	public function getStatusNum()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_num');
		$ret = $this->curl->request('update.php');		
		return $ret;	
	}
	
	/**
	* 创建举报
	* @param $cid 对象ID
	* @param $uid 被举报人
	* @param $type 类型：1：帖子，2：视频，3：微博评论，4：相册，5：视频评论，6：相册评论，7：帖子回复
	* @param $content 举报内容
	* @return $info 举报信息
	*/
	public function create_report($cid,$uid,$type,$url,$content)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('uid', $uid);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('url', $url);
		$this->curl->addRequestData('content', $content);
		$ret = $this->curl->request('report.php');
		return $ret[0];
	}
	
	/**
	* 检索举报
	* @param $type 类型
	* @param $state 状态  0--所有的  1 存在
	* @param $page 页码
	* @param $count 数量每页
	* @return $info 举报信息
	*/
	public function get_report($type = 0,$state = 0,$page = 0,$count = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('state', $state);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('report.php');
		return $ret;
	}
	
	/**
	* 删除举报
	* @param $id 
	* @return $info 举报信息
	*/
	public function del_report($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('report.php');
		return $ret;
	}

	/**
	* 恢复举报
	* @param $id 
	* @return $info 举报信息
	*/
	public function recover_report($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'recover');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('report.php');
		return $ret;
	}
	
	public function getUserIdByStatusId($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->addRequestData('status_id', $id);
		$user_id = $this->curl->request('getUserIdByStatusId.php');
		return $user_id;
	}
	
	
}

?>