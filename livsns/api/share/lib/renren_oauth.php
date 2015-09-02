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
	
	function renren_show_user($url,$uid,$name,$skey)
	{
		$params = array();
		$params['access_token'] = $this->oauth->access_token;
//		$params['fields'] = 'uid,name,sex';
		$params['format'] = 'json';
		$params['method'] = 'users.getInfo';
		if($uid)
		{
			$params['uids'] = $uid;
		}
		$str = '';
		$params['v'] = '1.0';
		foreach($params as $k=>$v)
		{
			$str .= $k.'='.$v;
		}
		$str .= $skey;
//		print_r($str);exit;
		$params['sig'] = md5($str);
		return $this->oauth->post($url, $params , 'tx');
	}
	
	function rrupload( $title, $url, $text, $skey, $pic, $format = true, $lat = NULL, $long = NULL, $annotations = NULL )
	{
		$params = array();
		$params['access_token'] = $this->oauth->access_token;
		$params['content'] = $text;
		$params['method'] = 'blog.addBlog';
		$params['title'] = $title;
		$params['v'] = '1.0';
		
		$str = '';
		foreach($params as $k=>$v)
		{
			$str .= $k.'='.$v;
		}
		$str .= $skey;
//		print_r($str);exit;
		$params['sig'] = md5($str);
		return $this->oauth->post( $url, $params , $format);
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
	
}
?>
