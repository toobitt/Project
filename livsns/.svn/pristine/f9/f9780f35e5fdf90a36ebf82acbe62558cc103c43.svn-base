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
			$params['fopenid'] = $uid;
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
	
	
	function txupload( $openid, $url, $text, $pic, $format = true, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		$params = array();
		$params['content'] = $text;
		$params['pic_url'] = $pic;
		$params['access_token'] = $this->oauth->access_token;
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
		$params['oauth_version'] = '2.a';
		$params['clientip'] = self::getClientIp();
		$params['scope'] = 'all';
		$params['appfrom'] = 'php-sdk2.0beta';
		$params['seqid'] = time();
		$params['serverip'] = $_SERVER['SERVER_ADDR'];
		if ($lat) {
			$params['jing'] = floatval($lat);
		}
		if ($long) {
			$params['wei'] = floatval($long);
		}
		return $this->oauth->post( $url, $params , $format);
	}
	
	function txupdate( $openid, $url, $text, $pic, $format = true, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		$params = array();
		$params['content'] = $text;
		$params['access_token'] = $this->oauth->access_token;
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['openid'] = $openid;
		$params['oauth_version'] = '2.a';
		$params['clientip'] = self::getClientIp();
		$params['scope'] = 'all';
		$params['appfrom'] = 'php-sdk2.0beta';
		$params['seqid'] = time();
		$params['serverip'] = $_SERVER['SERVER_ADDR'];
		$params['syncflag'] = '0';
		if ($lat) {
			$params['jing'] = floatval($lat);
		}
		if ($long) {
			$params['wei'] = floatval($long);
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
	function follow_by_id( $url, $uid, $name )
	{
		$params = array();
		$this->id_format($uid);
		if ($uid) {
			$params['fopenids'] = $uid;
		}
		if ($name) {
			$params['name'] = $name;
		}
		$params['scope'] = 'all';
		$params['format'] = 'json';
		print_r($params);
		return $this->oauth->post( $url, $params, true );
	}
	
	function txfollow_by_id( $url, $uid ,$name,$openid='')
	{
		$params = array();
		if($name)
		{
			$params['name'] = $name;
		}
		else
		{
			$this->id_format($uid);
			$params['fopenids'] = $uid;
		}
		$params['scope'] = 'all';
		$params['openid'] = $openid;
		$params['access_token'] = $this->oauth->access_token;
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['oauth_version'] = '2.a';
		$params['format'] = 'json';
		return $this->oauth->post( $url, $params, 'tx' );
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
		$params['scope'] = 'all';
		$params['access_token'] = $this->oauth->access_token;
//		print_r($params);exit;
		return $this->oauth->get($url, $params , $type);
	}

    function get_home_timeline($url = '',$since_id,$page,$count,$openid='',$type=true)
	{
		//type作用：验证是哪个平台，如果是腾讯，则type=tx作为标识，后面访问链接不加.json如果是别的平台则type=1，后面访问链接加.json
		$params=array();
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		if($type==='tx')
		{
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
			$params['page'] = $page;
			$params['count'] = $count;
			if($since_id)
			{
				$params['since_id'] = $since_id;
			}
			
		}
		$params['scope'] = 'all';
		$params['access_token'] = $this->oauth->access_token;
//		print_r($params);exit;
		return $this->oauth->get($url, $params , $type);
	}
		
	function get_topic($url = '',$keyword,$page,$count,$openid)
	{
		$params=array();
		$params['oauth_version'] = '2.a';
		$params['oauth_consumer_key'] = $this->oauth->client_id;
		$params['format'] = 'json';
		$params['pageinfo'] = $page;
		$params['reqnum'] = $count;
		$params['httext'] = $keyword;
		$params['openid'] = $openid;
		$params['access_token'] = $this->oauth->access_token;
//		print_r($params);exit;
		return $this->oauth->get($url, $params , 'tx');
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
	
	function search_userbytag($url,$keyword,$openid,$type) {
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
		$params['pagesize'] = 15;
		$params['page'] = 1;
		$params['scope'] = 'all';
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
