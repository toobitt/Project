<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: video.class.php 4082 2011-06-17 02:05:27Z repheal $
***************************************************************************/
class video{
	
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
	 * 上传视频
	 * @param $video_path //视频的在本地的目录      
	 * @param $file_name //视频的文件
	 * @param $file_size //视频的大小
	 * @param $video_name //视频名称
	 * @param $video_brief //视频简介
	 * @param $video_tags //视频标签
	 * @param $video_sort //视频分类
	 * @param $video_copyright //视频版权
	 * return $info 视频提示信息
	 */
	public function deal_upload()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'deal_upload');
		$this->curl->addRequestData('video_path', $video_path);
		
		$this->curl->addFile($_FILES);
		$this->curl->addRequestData('file_name', $file_name);
		$this->curl->addRequestData('file_size', $file_size);		
		$this->curl->addRequestData('video_name', $video_name);
		$this->curl->addRequestData('video_brief', $video_brief);
		$this->curl->addRequestData('video_tags', $video_tags);
		$this->curl->addRequestData('video_sort', $video_sort);
		$this->curl->addRequestData('video_copyright', $video_copyright);
		$r = $this->curl->request('video/upload_video.php');
		echo $r;	
	}
	
	/**
	 * 获取视频
	 */
	public function get_video()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		return $this->curl->request('video/video.php');
	}
	
	/**
	 * 获取用户
	 */
	public function verify_credentials()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verifyCredentials');
		$ret = $this->curl->request('video/user.php');
		return $ret[0];
	}

	/**
	 * 获取用户
	 */
	public function get_users($page = 0 , $count = 20 , $condition = '')
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('condition', $condition);
		$this->curl->addRequestData('page', $page);
		$ret = $this->curl->request('video/user.php');
		return $ret[0];
	}
	/**
	 * 获取用户
	 * @param $user_id 用户ID
	 * @return $info 网台信息
	 */
	public function getUserById($user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getUserById');
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('video/user.php');
		return $ret[0];
	}
	
	/**
	 * 修改用户的收藏数目
	 * @param $user_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function user_favorite_count($user_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'favorite_count');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/user.php');
		return $ret;
	}
	
	/**
	* 创建以及更新网台数据
	* @param $web_station_name 名称
	* @param $tags 简介
	* @param $brief 简介
	* @param $logo logo名称
	* @param $sta_id 网台ID
	* @return $info 网台信息
	*/
	
	public function create_station($web_station_name,$tags,$brief,$logo,$sta_id = 0,$logo_o="")
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		if($sta_id)
		{
			$this->curl->addRequestData('a', 'update');
			$this->curl->addRequestData('sta_id', $sta_id);
			$this->curl->addRequestData('logo_o', $logo_o);
		}
		else 
		{
			$this->curl->addRequestData('a', 'create');
		}
		$this->curl->addRequestData('web_station_name', $web_station_name);
		$this->curl->addRequestData('tags', $tags);
		$this->curl->addRequestData('brief', $brief);
		$this->curl->addRequestData('logo', $logo);
		$ret = $this->curl->request('video/station.php');
		return $ret;
	}
	
	/**
	 * 修改网台的收藏数目
	 * @param $sta_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function station_favorite_count($sta_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'favorite_count');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/station.php');
		return $ret;
	}
	
	
	/**
	* 上传网台logo
	* @param $file 
	* @return $ret logo信息
	*/
	
	public function logo($file,$logo = "",$sta_id = "")
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'logo');
		$this->curl->addRequestData('logo', $logo);
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addFile($file);
		$ret = $this->curl->request('video/station.php');
		return $ret[0];
	}
	
	/**
	* 根据用户来查询网台信息
	* @return $ret 网台信息
	*/
	public function get_user_station($user_id=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'showOne');
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('video/station.php');
		return $ret[0];
	}
	
	/**
	* 根据条件来查询多个网台信息
	* @param @sta_id  根据网台ID来查询
	* @param @user_id  根据用户ID来查询
	* @return $ret 网台信息
	*/
	public function get_station($sta_id=0,$user_id=0,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/station.php');
		return $ret;
	}
	
	
	/**
	 * 
	 * 获取所有的网台
	 */
	public function get_all_station($page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'all_station');
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/station.php');
		return $ret;	
	}
	
			
	/**
	 * 更新网台的访问次数
	 * @param @sta_id  根据网台ID来查询
	 */
	public function update_click_count($sta_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_click_count');
		$this->curl->addRequestData('sta_id', $sta_id);
		$ret = $this->curl->request('video/station.php');
		return $ret;
	}
	
	/**
	 * 检索网台根据网台名称条件
	 * @param $key
	 * @param $count
	 * @param $page
	 * @return $info 网台信息 
	 */
	public function station_search($key,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'search');
		$this->curl->addRequestData('key', $key);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/station.php');
		return $ret;	
	}
	
	/**
	* 添加网台关注
	* @param $id 内容ID
	* @return $ret 关注信息
	*/
	public function create_station_concern($id,$uid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('uid', $uid);
		$ret = $this->curl->request('video/station_concern.php');
		return $ret[0];
	}
	
	/**
	* 显示网台关注
	* @param $user_id
	* @return $ret 网台关注信息
	*/
	public function get_user_station_concern($user_id = 0,$page = 0,$count = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/station_concern.php');
		return $ret[0];
	}
	
	/**
	* 取网台关注关联
	* @param $id 网台ID
	* @return $ret 网台关注信息
	*/
	function get_station_relevance($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_station_relevance');
		$this->curl->addRequestData('id', $id);
		$ret = $this->curl->request('video/station_concern.php');
		return $ret[0];
	}
	
	/**
	* 取消网台关注
	* @param $id 网台ID
	* @param $uid 对象用户ID
	* @return $ret 网台关注信息
	*/
	public function del_station_concern($id,$uid=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('uid', $uid);
		$ret = $this->curl->request('video/station_concern.php');
		return $ret[0];
	}
	
		
	/**
	* 创建节目单信息
	* @param $sta_id 网台ID
	* @param $video_id 视频ID
	* @param $program_name 节目单名称
	* @param $brief 节目单简介
	* @param $start 起始时间
	* @param $end 结束时间
	* @return $ret 节目单信息
	*/
	public function create_station_program($sta_id,$video_id,$program_name,$brief,$start,$end)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('program_name', $program_name);
		$this->curl->addRequestData('brief', $brief);
		$this->curl->addRequestData('start_time', $start);
		$this->curl->addRequestData('end_time', $end);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
		
	/**
	* 根据网台ID获取节目单信息
	* @param $sta_id
	* @param $user_id
	* @return $ret 节目单信息
	*/
	public function get_station_program($sta_id,$user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}

	/**
	* 根据网台ID获取节目单信息(排序版)
	* @param $sta_id
	* @param $user_id
	* @return $ret 节目单信息
	*/
	public function get_station_programe($sta_id,$user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'shows');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
	
	/**
	* 根据节目单ID查询节目单信息
	* @param $program_id
	* @return $ret 节目单信息
	*/
	public function get_program($program_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('program_id', $program_id);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
		
	/**
	* 根据节目单ID修改节目单信息
	* @param $program_id
	* @param $program_name
	* @param $brief
	* @return $ret 节目单信息
	*/
	public function edit_station_program($program_id,$program_name,$brief)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'edit');
		$this->curl->addRequestData('program_id', $program_id);
		$this->curl->addRequestData('program_name', $program_name);
		$this->curl->addRequestData('brief', $brief);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
	
	/**
	* 删除节目单
	* @param $program_id
	* @param $sta_id
	* @param $gap
	* @return $ret 节目单信息
	*/
	public function del_station_program($program_id,$sta_id,$gap)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('program_id', $program_id);
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('gap', $gap);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
	
	
	/**
	* 根据单个视频ID取其所在的节目单信息
	* @param $video_id 视频ID
	* @return $ret 节目单信息
	*/
	public function video_program($video_id,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'video_program');
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}
	
	/**
	* 移动节目单
	* @param $program_id
	* @param $sta_id
	* @param $action 0为up 1为down
	* @param $gap
	* @return $ret 节目单信息
	*/
	public function move_station_program($program_id,$sta_id,$action,$gap)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'move');
		$this->curl->addRequestData('program_id', $program_id);
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('action', $action);
		$this->curl->addRequestData('gap', $gap);
		$ret = $this->curl->request('video/program.php');
		return $ret[0];
	}	
	
	/**
	 * 修改视频的收藏数目
	 * @param $video_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function video_favorite_count($video_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'favorite_count');
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/video.php');
		return $ret;
	}
	
	
	/**
	 * 修改视频的图片
	 * @param $video_id
	 * @param $file
	 * @return 图片信息
	 */	
	public function update_video_image($file,$video_id,$schematic){
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update_schematic');
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('schematic', $schematic);
		$this->curl->addFile($file);
		$ret = $this->curl->request('video/update_video.php');		
		return $ret[0];
	}
	
	/**
	 * 获取用户的视频信息(已发布)
	 */

	public function get_video_info($user_id , $page = 0 , $count = 0 , $condition = '' , $show_video = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 0);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('show_type', $show_video);
		$this->curl->addRequestData('condition', $condition);
		$ret = $this->curl->request('video/video.php');
		return $ret;
	}
	
	/**
	 * 获取待审核的视频
	 */
	public function get_verify_video($condition = '' , $page = 0 , $count = 10  , $show_video = 1 ,$user_id = 0)
	{		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 6);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('show_type', $show_video);
		$this->curl->addRequestData('condition', $condition);
		$ret = $this->curl->request('video/video.php');
		return $ret;
	}
	
	/**
	 * 获取未通过审核的视频
	 */
	public function get_unpass_video($condition = '' , $page = 0 , $count = 10  , $show_video = 1 ,$user_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 8);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('show_type', $show_video);
		$this->curl->addRequestData('condition', $condition);
		$ret = $this->curl->request('video/video.php');
		return $ret;	
	}
	
	/**
	 * 获取审核的删除的视频
	 */
	public function get_verify_delete_video($condition = '' , $page = 0 , $count = 10  , $show_video = 1 ,$user_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 10);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('show_type', $show_video);
		$this->curl->addRequestData('condition', $condition);
		$ret = $this->curl->request('video/video.php');
		return $ret;	
	}
	
	/**
	 * 获取推荐中的视频
	 */
	public function get_recommend_video($condition = '' , $page = 0 , $count = 10  , $show_video = 1 ,$user_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 9);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('show_type', $show_video);
		$this->curl->addRequestData('condition', $condition);
		$ret = $this->curl->request('video/video.php');
		return $ret;	
	}
		
	
	/**
	 * 获取转码中的视频
	 */
	public function get_transcode_video($user_id = 0,$page=0,$count = 30 , $show_video = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 7);
		$this->curl->addRequestData('count', $count);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('show_type', $show_video);
		$ret = $this->curl->request('video/video.php');
		return $ret;
	}
	
	/**
	 * 获取用户视频信息(所有)
	 */
	public function get_all_video_info($page , $count = 10 )
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 3);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/video.php');
		return $ret;
	}
	
	/**
	 * 获取需要修复的视频
	 */
	public function get_repair_video($page = 0 , $count = 10 )
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_repair_video');
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/video.php');
		return $ret;	
	}
		
	/**
	 * 获取单个视频信息
	 */
	public function get_single_video($video_id = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 4);
		$this->curl->addRequestData('id', $video_id);
		$ret = $this->curl->request('video/video.php');
		return $ret[0];		
	}
	
	
	
	/**
	* 获取相关视频（标签）
	* @param $video_id
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function video_tags_search($video_id,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'tags_search');
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/video.php');
		return $ret[0];	
	}
	
	/**
	* 视频同步到讨论区
	* @param $video_id
	* @return $ret 视频信息
	*/
	public function video_threads($video_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'video_threads');
		$this->curl->addRequestData('video_id', $video_id);
		$ret = $this->curl->request('video/video.php');
		return $ret[0];	
	}
	
	
	/**
	* 视频检索
	* @param $title
	* @param $page
	* @param $count
	* @return $ret 视频信息
	*/
	public function video_search($name,$page=0,$count=10)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('info_type', 5);
		$this->curl->addRequestData('title', $name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/video.php');
		return $ret;	
	}
	
	/**
	* 创建专辑
	* @param $name
	* @param $brief
	* @param $sort_id
	* @param $video_id
	* @return $ret 专辑信息
	*/
	public function  create_album($name,$brief,$sort_id,$video_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('brief', $brief);
		$this->curl->addRequestData('sort_id', $sort_id);
		$this->curl->addRequestData('video_id', $video_id);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];	
	}
	
	/**
	 * 修改专辑的收藏数目
	 * @param $album_id
	 * @param $type(1=+1,0=-1)
	 * @return $video_id 
	 */
	public function album_favorite_count($album_id,$type = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'favorite_count');
		$this->curl->addRequestData('album_id', $album_id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/user.php');
		return $ret;
	}
	
	/**
	* 修改专辑
	* @param $album_info array 修改的专辑信息 
	* @return $ret 专辑信息
	*/
	public function edit_album($album_info)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'edit');
		foreach($album_info as $key =>$value)
		{
			$this->curl->addRequestData($key, $value);
		}
		$ret = $this->curl->request('video/album.php');
		return $ret[0];	
	}
	
	/**
	* 删除专辑（包括关联表中的信息）
	* @param $album_id
	* @return $album_id 专辑ID
	*/
	public function del_album($album_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del_album');
		$this->curl->addRequestData('album_id', $album_id);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];	
	}
	
	/**
	* 修改封面
	* @param $album_id 
	* @param $video_id 
	* @return $ret 专辑信息
	*/
	public function edit_album_cover($video_id,$album_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'edit_cover');
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('album_id', $album_id);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];	
	}
	 
	/**
	* 移除视频
	* @param $id 
	* @param $album_id 用于减去专辑表中是视频数
	* @return $id 关系ID
	*/
	public function del_album_video($id,$album_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del_album_video');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('album_id', $album_id);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];	
	}
	 
	/**
	* 获取用户的专辑
	* @param $user_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_album_info($user_id = 0 ,$page = 0, $count = 20)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];
	}
	
	/**
	* 获得专辑中的视频
	* @param $album_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_album_video($album_id,$page = 0,$count = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_video');
		$this->curl->addRequestData('album_id', $album_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];
	}
	
	
	/**
	* 根据专辑ID获取专辑信息
	* @param $album_id
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_album($album_id,$page = 0,$count = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_album');
		$this->curl->addRequestData('album_id', $album_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];
	}	
	
		
	/**
	* 根据专辑名称获取专辑信息
	* @param $album_name
	* @param $user_id
	* @return $ret 专辑信息
	*/
	public function getAlbumByName($album_name,$user_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getAlbumByName');
		$this->curl->addRequestData('album_name', $album_name);
		$this->curl->addRequestData('user_id', $user_id);
		$ret = $this->curl->request('video/album.php');
		return $ret[0];
	}	
	
	/**
	* 转移专辑中的视频（包括关联表中的信息）
	* @param $album_id（是当前专辑ID）
	* @param $album_id_n （是转移之后的专辑ID）
	* @param $video_id（需要转移的视频ID）
	* @return $album_id 专辑ID
	*/
	public function move_album_video($album_id,$album_id_n,$video_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'move_album_video');
		$this->curl->addRequestData('album_id', $album_id);
		$this->curl->addRequestData('album_id_n', $album_id_n);
		$this->curl->addRequestData('video_id', $video_id);
		$ret = $this->curl->request('video/album.php');
		return $ret;
	}
	
	/**
	 * 更新视频视频信息
	 */
	public function update_video_info($info)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('update_info', $info);
		$this->curl->request('video/update_video.php');	
	}
	
	/**
	 * 更新视频的播放次数
	 */
	public function update_play_count($video_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('a', update_play_count);
		$this->curl->request('video/update_video.php');	
	}
	
	/**
	 * 删除视频
	 */
	public function delete_video($video_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->request('video/delete_video.php');		
	}
	
	/**
	 * 审核视频
	 */
	public function  verify_video($video_id , $state)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('video_id', $video_id);
		$this->curl->addRequestData('video_state', $state);
		$this->curl->request('video/verify_video.php');
		
	}
	
	/**
	* 添加收藏
	* @param $id 内容ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 收藏信息
	*/
	public function create_collect($id,$type,$uid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('uid', $uid);
		$ret = $this->curl->request('video/collect.php');
		return $ret[0];
	}
	
	/**
	 * 添加推荐
	 */
	public function add_recommend($rid , $type , $user , $content)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'add_recommend');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('author', $user);
		$this->curl->addRequestData('content', $content);
		$this->curl->request('recommend/recommend.php');			
	}
	
	/**
	 * 删除推荐
	 */
	public function delete_recommend($rid , $type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'delete_recommend');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->addRequestData('type', $type);
		$this->curl->request('recommend/recommend.php');
	}
	
	/**
	 * 标记推荐
	 */
	public function mark_recommend($rid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'mark_recommend');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->request('video/video.php');			
	}
	
	/**
	 * 取消推荐标记
	 */
	public function cancel_recommend_mark($rid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'cancel_recommend_mark');
		$this->curl->addRequestData('rid', $rid);
		$this->curl->request('video/video.php');
	}
	
	/**
	 * 检测推荐
	 */
	public function check_recommend($rid)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'check_recommend');
		$this->curl->addRequestData('rid', $rid);
		$r = $this->curl->request('video/video.php');
		return $r;				
	}
	
	
	/**
	* 显示收藏
	* @param $user_id
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 收藏信息
	*/
	public function get_user_collect($user_id = 0,$type,$page,$count)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/collect.php');
		return $ret[0];
	}
	
	/**
	* 取收藏关联
	* @param $id 收藏ID
	* @param $type （0视频、1网台、2用户、3专辑）	
	* @return $ret 收藏信息
	*/
	function get_collect_relevance($id,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_collect_relevance');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/collect.php');
		return $ret[0];
	}
	
	/**
	* 取消收藏
	* @param $id 收藏ID
	* @param $cid 内容
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 收藏信息
	*/
	public function del_collect($id,$cid,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/collect.php');
		return $ret[0];
	}
	
	/**
	* 添加评论
	* @param $cid 内容ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	public function create_comment($com)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		if(!is_array($com))
		{
			return '';
		}
		else 
		{
			foreach($com as $key=>$value)
			{
				$this->curl->addRequestData($key, $value);
			}
		}
		$ret = $this->curl->request('video/comment.php');
		return $ret;
	}
	
	/**
	* 显示评论
	* @param $user_id 判断该用户与发表评论用户的关系
	* @param $cid   评论对象
	* @param $type （0视频、1网台、2用户、3专辑）
	* @param $state
	* @param $page
	* @param $count
	* @return $ret 评论信息
	*/
	public function get_comment_list($user_id,$cid = 0,$type,$state=1,$page=0,$count=10)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('state', $state);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/comment.php');
		return $ret[0];
	}
	
	/**
	* 查询用户的评论
	* @param $user_id 
	* @param $type （0视频、1网台、2用户、3专辑）
	* @param $state
	* @param $page
	* @param $count
	* @return $ret 评论信息
	*/
	public function get_user_comments($user_id,$type,$state=1,$page=0,$count=10)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'getUserComments');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('state', $state);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/comment.php');
		return $ret[0];
	}
	
	/**
	* 删除评论
	* @param $id 评论ID
	* @param $cid 评论对象ID
	* @param $type （0视频、1网台、2用户、3专辑）
	* @return $ret 评论信息
	*/
	public function del_comment($id,$cid,$type)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/comment.php');
		return $ret[0];
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
	
	/**
	* 获取网台变动的历史记录
	* @return $ret 信息
	*/
	public function get_station_history($sta_id=0,$page = 0,$count = 10)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_history');
		$this->curl->addRequestData('sta_id', $sta_id);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/station_concern.php');
		return $ret[0];
	}	


	/**
	* 增加访问记录
	* @param $user_id
	* @param $cid
	* @param $type(1-视频，2-网台,3-直播台)
	* @return $ret 信息
	*/
	public function create_visit_history($user_id,$cid,$type=1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$ret = $this->curl->request('video/visit.php');
		return $ret;
	}	
	
	/**
	* 获取某个对象的用户访问记录
	* @param $cid
	* @param $type(1-视频，2-网台,3-直播台)
	* @param $page
	* @param $count
	* @return $ret 专辑信息
	*/
	public function get_visit_history($cid,$type=1,$page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('user_id', $user_id);
		$this->curl->addRequestData('cid', $cid);
		$this->curl->addRequestData('type', $type);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/visit.php');
		return $ret[0];
	}
	
	/**
	* 创建广告
	* @param $mark 广告标识
	* @param $name 广告名称
	* @param $content 广告代码
	* @return $info 广告信息
	*/
	public function create_advert($mark,$name,$content,$html = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'create');
		$this->curl->addRequestData('mark', $mark);
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('content', $content);
		$this->curl->addRequestData('html', $html);
		$ret = $this->curl->request('video/advert.php');
		return $ret[0];
	}
	
	/**
	* 获取广告
	* @return $info 广告信息
	*/
	public function get_advert($page=0,$count=0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'show');
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('page', $page);
		$this->curl->addRequestData('count', $count);
		$ret = $this->curl->request('video/advert.php');
		return $ret[0];
	}	
	
	/**
	* 更新广告
	* @param $adver_id 广告ID
	* @param $mark 广告标识
	* @param $name 广告名称
	* @param $content 广告代码
	* @return $info 广告信息
	*/
	public function update_advert($adver_id,$mark,$name,$content,$html = 1)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'update');
		$this->curl->addRequestData('adver_id', $adver_id);
		$this->curl->addRequestData('mark', $mark);
		$this->curl->addRequestData('name', $name);
		$this->curl->addRequestData('content', $content);
		$this->curl->addRequestData('html', $html);
		$ret = $this->curl->request('video/advert.php');
		return $ret[0];
	}	
	
	/**
	* 删除广告
	* @param $adver_id 广告id
	* @return $info 广告信息
	*/
	public function delete_advert($adver_id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'del');
		$this->curl->addRequestData('adver_id', $adver_id);
		$ret = $this->curl->request('video/advert.php');
		return $ret[0];
	}
	
	public function getStationNum()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_station_num');
		$r = $this->curl->request('video/station.php');
		return $r;	
	}
	
	public function getVideoNum()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_video_num');
		$r = $this->curl->request('video/video.php');
		return $r;	
	}
	
	public function getProgramNum()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'get_program_num');
		$r = $this->curl->request('video/program.php');
		return $r;	
	}
	
	/**
	 * 
	 * 网台审核
	 * @param $station_id int 网台ID
	 * @param $state int 状态
	 */
	public function verify_station($station_id = 0 , $state = 0)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'verify_station');
		$this->curl->addRequestData('station_id', $station_id);	
		$this->curl->addRequestData('state', $state);
		$this->curl->request('video/station.php');		
	}
	
	/**
	 * 
	 * 添加积分日志
	 * @param int $rule_id 积分类型
	 * @param int $oid 被回复或被评论的ID 注册和登录等其他为0
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
	 * 重建视频数据
	 * @param int $id 视频ID
	 */
	public function rebuild_data($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('id', $id);
		$this->curl->addRequestData('a', 'rebuild_data');
		$r = $this->curl->request('video/upload_video.php');
		return $r;		
	}
	
	/**
	 * 创建视频xml文件，用于百度检索
	 */
	public function video_xml()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$r = $this->curl->request('video/video_xml.php');
		return $r[0];		
	}
}
?>
