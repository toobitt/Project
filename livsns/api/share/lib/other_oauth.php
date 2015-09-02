<?php
require_once(CUR_CONF_PATH . 'lib/oauth.php');
class ClientV2 
{
	/**
	 * 构造函数
	 * 
	 * @access public
	 * @param mixed $akey 微博开放平台应用APP KEY
	 * @param mixed $skey 微博开放平台应用APP SECRET
	 * @param mixed $access_token OAuth认证返回的token
	 * @param mixed $refresh_token OAuth认证返回的token secret
	 * @return void
	 */
	function __construct( $client_id, $client_secret, $response_type = 'code',
						  $access_token = NULL, $refresh_token = NULL)
	{
		$this->oauth = new Oauth( $client_id, $client_secret, $response_type ,
						  $access_token, $refresh_token );
	}
	
	/**
	 * 根据用户UID或昵称获取用户资料
	 *
	 * 按用户UID或昵称返回用户资料，同时也将返回用户的最新发布的微博。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/users/show users/show}
	 * 
	 * @access public
	 * @param int  $uid 用户UID。
	 * @return array
	 * 
	 */
	function show_user_by_id($url , $uid = '' , $name = '' , $openid , $type = '')
	{
		//type作用：验证是哪个平台，如果是腾讯，则type=tx作为标识，后面访问链接不加.json如果是别的平台则type=1，后面访问链接加.json
		$params=array();
		if ( $uid !== '' ) {
			$params['uid'] = $uid;
		}
		else if( $name !== '' )
		{
			$params['name'] = $name;
		}
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
		$params['format'] = 'json';
		
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	function get_other_user($url,$uid,$name,$openid='',$type)
	{
		$params=array();
		if ( $uid !== '' ) {
			$params['uid'] = $uid;
		}
		else if( $name !== '' )
		{
			if($type==='tx')
			{
				$params['name'] = $name;
			}
			else
			{
				$params['screen_name'] = $name;
			}
		}
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
		$params['format'] = 'json';
		
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	/**
	 * 发表图片微博
	 *
	 * 发表图片微博消息。目前上传图片大小限制为<5M。 
	 * <br />注意：lat和long参数需配合使用，用于标记发表微博消息时所在的地理位置，只有用户设置中geo_enabled=true时候地理位置信息才有效。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/statuses/upload statuses/upload}
	 * 
	 * @access public
	 * @param string $status 要更新的微博信息。信息内容不超过140个汉字, 为空返回400错误。
	 * @param string $pic_path 要发布的图片路径, 支持url。[只支持png/jpg/gif三种格式, 增加格式请修改get_image_mime方法]
	 * @param float $lat 纬度，发表当前微博所在的地理位置，有效范围 -90.0到+90.0, +表示北纬。可选。
	 * @param float $long 可选参数，经度。有效范围-180.0到+180.0, +表示东经。可选。
	 * @return array
	 */
	function upload( $url, $status, $pic_path, $format = true, $lat = NULL, $long = NULL )
	{
		$params = array();
		$params['status'] = $status;
		$params['pic'] = "@".$pic_path;
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}
//		print_r($params);exit;
		return $this->oauth->post( $url, $params, $format, $pic_path?true:false);
	}
	
	/**
	 * 发表微博
	 *
	 * 发布一条微博信息。
	 * <br />注意：lat和long参数需配合使用，用于标记发表微博消息时所在的地理位置，只有用户设置中geo_enabled=true时候地理位置信息才有效。
	 * <br />注意：为防止重复提交，当用户发布的微博消息与上次成功发布的微博消息内容一样时，将返回400错误，给出错误提示：“40025:Error: repeated weibo text!“。 
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/statuses/update statuses/update}
	 * 
	 * @access public
	 * @param string $status 要更新的微博信息。信息内容不超过140个汉字, 为空返回400错误。
	 * @param float $lat 纬度，发表当前微博所在的地理位置，有效范围 -90.0到+90.0, +表示北纬。可选。
	 * @param float $long 经度。有效范围-180.0到+180.0, +表示东经。可选。
	 * @param mixed $annotations 可选参数。元数据，主要是为了方便第三方应用记录一些适合于自己使用的信息。每条微博可以包含一个或者多个元数据。请以json字串的形式提交，字串长度不超过512个字符，或者数组方式，要求json_encode后字串长度不超过512个字符。具体内容可以自定。例如：'[{"type2":123}, {"a":"b", "c":"d"}]'或array(array("type2"=>123), array("a"=>"b", "c"=>"d"))。
	 * @return array
	 */
	function update( $url, $status, $format = true, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		 date_default_timezone_set('PRC');
		$params = array();
		$params['status'] = $status;
		if ($lat) {
			$params['lat'] = floatval($lat);
		}
		if ($long) {
			$params['long'] = floatval($long);
		}
		if (is_string($annotations)) {
			$params['annotations'] = $annotations;
		} elseif (is_array($annotations)) {
			$params['annotations'] = json_encode($annotations);
		}
		return $this->oauth->post( $url, $params ,$format);
	}
	
	
	 /** 关注一个用户。
	 *
	 * 成功则返回关注人的资料，目前最多关注2000人，失败则返回一条字符串的说明。如果已经关注了此人，则返回http 403的状态。关注不存在的ID将返回400。
	 * <br />对应API：{@link http://open.weibo.com/wiki/2/friendships/create friendships/create}
	 * 
	 * @access public
	 * @param int $uid 要关注的用户UID
	 * @return array
	 */
	function follow_by_id( $url, $uid )
	{
		$params = array();
		$this->id_format($uid);
		$params['uid'] = $uid;
		return $this->oauth->post( $url, $params, true );
	}
	
	/**
	 * @ignore
	 */
	function id_format(&$id) {
		if ( is_float($id) ) {
			$id = number_format($id, 0, '', '');
		} elseif ( is_string($id) ) {
			$id = trim($id);
		}
	}
	
	//获取客户端IP
    function getClientIp()
    {
        if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
            $ip = getenv ( "HTTP_CLIENT_IP" );
        else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
            $ip = getenv ( "HTTP_X_FORWARDED_FOR" );
        else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
            $ip = getenv ( "REMOTE_ADDR" );
        else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }
    
    function get_user_timeline($url = '',$uid,$name,$since_id,$page,$count,$openid='',$type=true)
	{
		//type作用：验证是哪个平台，如果是腾讯，则type=tx作为标识，后面访问链接不加.json如果是别的平台则type=1，后面访问链接加.json
		$params=array();
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		if($type==='tx')
		{
			if($name)
			{
				$params['name'] = $name;
			}
			if($uid)
			{
				$params['fopenid'] = $uid;
			}
			$params['openid'] = $openid; 
			$params['pageflag'] = 1;
			$params['pagetime'] = $page;
			$params['reqnum'] = $count;
			$params['type'] = 0;
			$params['contenttype'] = 0;
			if($since_id)
			{
				$params['lastid'] = $since_id;
			}
		}
		else
		{
			if($uid)
			{
				$params['uid'] = $uid;
			}
			else
			{
				$params['screen_name'] = $name;
			}
			$params['page'] = $page;
			$params['count'] = $count;
			if($since_id)
			{
				$params['since_id'] = $since_id;
			}
			
		}
		$params['access_token'] = $this->oauth->access_token;
//		print_r($params);exit;
		return $this->oauth->get($url, $params , $type);
	}
	
	function search_user($url,$keyword,$openid,$type)
	{
		$params=array();
		if($type==='tx')
		{
			$params['keyword'] = $keyword;
		}
		else
		{
			$params['q'] = $keyword;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
		$params['format'] = 'json';
		$params['count'] = 50;
		
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	function repost($url,$weibo_id,$text,$is_comment,$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['reid'] = $weibo_id;
			$params['content'] = $text;
			$params['clientip'] = $this->getClientIp();
			$params['syncflag'] = $is_comment;
		}
		else
		{
			$params['id'] = $weibo_id;
			$params['status'] = $text;
			$params['is_comment'] = $is_comment;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
			
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->post($url, $params , $type);
	}
	
	function comments($url,$weibo_id,$comment,$comment_ori,$comment_id='',$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['reid'] = $weibo_id;
			$params['content'] = $comment;
			$params['clientip'] = $this->getClientIp();
			$params['syncflag'] = $comment_ori;
		}
		else
		{
			$params['id'] = $weibo_id;
			if($comment_id)
			{
				$params['cid'] = $comment_id;
			}
			$params['comment'] = $comment;
			$params['comment_ori'] = $comment_ori;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
			
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->post($url, $params , $type);
	}
	
	function detail_show($url,$weibo_id,$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['id'] = $weibo_id;
			$params['openid'] = $openid;
			$params['clientip'] = $this->getClientIp();
		}
		else
		{
			$params['id'] = $weibo_id;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	function get_comments($url,$weibo_id,$since_id='',$since_time='',$page='',$count='',$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['rootid'] = $weibo_id;
			$params['flag'] = 0;//类型标识。0－转播列表，1－点评列表，2－点评与转播列表
			$params['pageflag'] = empty($page)?0:$page;//0：第一页，1：向下翻页，2：向上翻页
			$params['openid'] = $openid;
			$params['clientip'] = $this->getClientIp();
			$params['reqnum'] = empty($count)?50:$count;//每次请求记录的条数（1-100条）
			$params['twitterid'] = $since_id;//微博id，与pageflag、pagetime共同使用，实现翻页功能（第1页填0，继续向下翻页，填上一次请求返回的最后一条记录id）
			$params['pagetime'] = $since_time;//微博id，与pageflag、pagetime共同使用，实现翻页功能（第1页填0，继续向下翻页，填上一次请求返回的最后一条记录id）
		}
		else
		{
			$params['id'] = $weibo_id;
			$params['since_id'] = empty($since_id)?0:$since_id;
			$params['page'] = empty($page)?1:$page;
			$params['count'] = empty($count)?50:$count;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	function get_user_mention($url,$since_id='',$page='',$count='',$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['pageflag'] = $page;//0：第一页，1：向下翻页，2：向上翻页
			$params['openid'] = $openid;
			$params['reqnum'] = $count;//每次请求记录的条数（1-100条）
			if($since_id)
			{
				$params['lastid'] = $since_id;//微博id，与pageflag、pagetime共同使用，实现翻页功能（第1页填0，继续向下翻页，填上一次请求返回的最后一条记录id）
			}
			
		}
		else
		{
			$params['since_id'] = empty($since_id)?0:$since_id;
			$params['page'] = $page;
			$params['count'] = $count;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->get($url, $params , $type);
	}
	
	function favorite($url,$weibo_id,$openid='',$type=true)
	{
		$params=array();
		if($type==='tx')
		{
			$params['id'] = $weibo_id;
		}
		else
		{
			$params['id'] = $weibo_id;
		}
		
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
			
		$params['access_token'] = $this->oauth->access_token;
		return $this->oauth->post($url, $params , $type);
	}
	
	function revoke_by_access_token($url,$openid='',$type=true)
	{
		$params=array();
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		if($openid)
		{
			$params['openid'] = $openid; 
		}
		$params['access_token'] = $this->oauth->access_token;
//		print_r($params);exit;
		return $this->oauth->get($url, $params , $type);
	}
	
}
?>
