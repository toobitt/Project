<?php
require_once ('global.php');
require_once (ROOT_PATH . 'lib/class/curl.class.php');
require_once (CUR_CONF_PATH . 'lib/videopoint.class.php');
define ( 'MOD_UNIQUEID', 'videopoint' );
class videopointApi extends adminReadBase {
	var $curl, $obj;
	public function __construct() {
		$this->mPrmsMethods = array (
				'show'   => '查看',
				'manage' => '管理' 
		);
		parent::__construct ();
		// 此处是为了判断视频库有没有安装
		if (! $this->settings ['App_livmedia'] ['host']) {
			$this->errorOutput ( 'please install livmedia first' );
		}
		$this->curl = new curl ( $this->settings ['App_livmedia'] ['host'], $this->settings ['App_livmedia'] ['dir'] );
		$this->obj = new videopoint ();
	}
	public function __destruct() {
		parent::__destruct ();
	}
	public function detail() {
	}
	public function count() {
	}
	public function index() {
	}
	public function show() {
		$this->verify_content_prms(array('_action'=>'show'));
		$data = array (
				'date_time' => date ( 'Y-m-d', TIMENOW ) 
		);
		if ($this->input ['video_id']) {
			$this->curl->setSubmitType ( 'post' );
			$this->curl->setReturnFormat ( 'json' );
			$this->curl->initPostData ();
			$this->curl->addRequestData ( 'a', 'get_vod_info' );
			$this->curl->addRequestData ( 'id', intval ( $this->input ['video_id'] ) );
			$cur_video = $this->curl->request ( 'vod.php' );
			$data ['duration_time'] = 60 * $cur_video [0] ['duration'] + substr ( $cur_video [0] ['duration'], stripos ( $cur_video [0] ['duration'], "'" ) + 1 );
			$cur_video [0] ['duration_time'] = $data ['duration_time'];
			$data ['cur_video'] = $cur_video [0];
		}
		$this->addItem ( $data );
		$this->output ();
	}
	// 判断视频是否加点||显示某视频所有的点 方法名 is_pointed 参数voideoid
	public function is_pointed() {
		if (! isset ( $this->input ['videoid'] ))
			return false;
		$ret = $this->obj->detail ( 'point', intval ( $this->input ['videoid'] ) );
		if (empty ( $ret )) {
			$ret = - 1; // 给前端做判断
		}
		$this->addItem ( $ret );
		$this->output ();
	}
	public function show_points() {
		if (! isset ( $this->input ['videoid'] ))
			return false;
		$ret = $this->obj->detail ( 'point', intval ( $this->input ['videoid'] ) );
		if (empty ( $ret ))
			$this->errorOutput ( 'video id 不正确' );
		$this->addItem ( $ret );
		$this->output ();
		return $ret;
	}
	// 显示某视频某一个点 方法名 show_onepoint 参数id
	public function show_onepoint() {
		if (! isset ( $this->input ['id'] ))
			return false;
		$ret = $this->obj->detail ( 'point', intval ( $this->input ['id'] ), 'id' );
		if (empty ( $ret ))
			$this->errorOutput ( 'id 不正确' );
		$this->addItem ( $ret );
		$this->output ();
	}
	public function vod_detail() {
		if (! $this->input ['id']) {
			$this->errorOutput ( NOID );
		}
		$this->curl->setSubmitType ( 'get' );
		$this->curl->setReturnFormat ( 'json' );
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'a', 'detail' );
		$this->curl->addRequestData ( 'id', intval ( $this->input ['id'] ) );
		$ret = $this->curl->request ( 'vod.php' );
		$this->addItem ( $ret [0] );
		$this->output ();
	}
	public function get_vod_info() {
		$pp = $this->input ['page'] ? intval ( $this->input ['page'] ) : 1; // 如果没有传第几页，默认是第一页
		$count = $this->input ['counts'] ? intval ( $this->input ['counts'] ) : 20;
		$offset = intval ( ($pp - 1) * $count );
		$vod_sort_id = intval ( $this->input ['vod_sort_id'] );
		$vod_data = array (
				'offset' => $offset,
				'count' => $count,
				'vod_sort_id' => $vod_sort_id,
				'pp' => $pp,
				'k' => $this->input ['title'],
				'date_search' => $this->input ['date_search'] 
		);
		$this->curl->setSubmitType ( 'post' );
		$this->curl->setReturnFormat ( 'json' );
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'a', 'get_vod_info' );
		$this->curl->addRequestData ( 'self_group_type', $this->user ['group_type'] );
		
		foreach ( $vod_data as $k => $v ) {
			$this->curl->addRequestData ( $k, $v );
		}
		$ret = $this->curl->request ( 'vod.php' );
		$data ['video'] = $ret;
		// 获取分页的参数
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'a', 'get_page_data' );
		$this->curl->addRequestData ( 'self_group_type', $this->user ['group_type'] );
		foreach ( $vod_data as $k => $v ) {
			$this->curl->addRequestData ( $k, $v );
		}
		$page_data = $this->curl->request ( 'vod.php' );
		$data ['page'] = $page_data;
		$data ['date_search'] = $this->settings ['date_search'];
		$this->addItem ( $data );
		$this->output ();
	}
	// 获取视频节点
	public function get_vod_node() {
		$this->curl->setSubmitType ( 'get' );
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'fid', $this->input ['fid'] );
		$this->curl->addRequestData ( 'self_group_type', $this->user ['group_type'] );
		$ret = $this->curl->request ( 'vod_media_node.php' );
		$this->addItem ( $ret );
		$this->output ();
	}
	// 获取视频的进度
	public function get_video_status() {
		if (! $this->input ['id']) {
			$this->errorOutput ( NOID );
		}
		$this->curl->setSubmitType ( 'get' );
		$this->curl->setReturnFormat ( 'json' );
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'a', 'get_video_status' );
		$this->curl->addRequestData ( 'id', intval ( $this->input ['id'] ) );
		$ret = $this->curl->request ( 'vod.php' );
		$this->addItem ( $ret [0] );
		$this->output ();
	}
	// 获取某个节点下的所有视频
	public function get_node_videos() {
		$this->curl->setSubmitType ( 'get' );
		$this->curl->initPostData ();
		$this->curl->addRequestData ( 'vod_sort_id', intval ( $this->input ['id'] ) );
		$this->curl->addRequestData ( 'a', 'show' );
		$video = $this->curl->request ( 'vod.php' );
		// file_put_contents('videos.txt',var_export($video,1));
		$this->addItem ( $video [0] );
		$this->output ();
	}
	
	// 显示某个视频下所有点数
	public function get_video_points() {
		if (! isset ( $this->input ['videoid'] ))
			return false;
		$ret = $this->obj->count_points ( intval ( $this->input ['videoid'] ) );
		$this->addItem ( $ret );
		$this->output ();
	}
}
$out = new videopointApi ();
if (! method_exists ( $out, $_INPUT ['a'] )) {
	$action = 'show';
} else {
	$action = $_INPUT ['a'];
}
$out->$action ();
?>