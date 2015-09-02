<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: uc.php 1872 2011-01-26 07:16:40Z develop_tong $
***************************************************************************/
define('UC_CLIENT_VERSION', '1.5.0');
define('UC_CLIENT_RELEASE', '20081212');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 1);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_GETCREDIT', 1);
define('API_UPDATECREDITSETTINGS', 1);

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('ROOT_DIR', '../../');

define('ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);
define('TIMENOW', time());

define('UI_ROOT', substr(dirname(__FILE__), 0, -3));

require(ROOT_PATH . 'conf/global.conf.php');
require(ROOT_PATH . 'lib/func/functions.php');
require(UI_ROOT . 'conf/config.php');

if(!defined('IN_UC')) {

	error_reporting(0);
	set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}

	$timestamp = time();
	if(empty($get)) {
		exit('Invalid Request');
	} elseif($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	$action = $get['action'];
	require_once UI_ROOT . 'uclient/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
		
	include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
	$gDB = new db();
	$gDB->connect($gDBconfig['host'], $gDBconfig['user'], $gDBconfig['pass'], $gDBconfig['database'], $gDBconfig['charset'], $gDBconfig['pconnect'], $gDBconfig['dbprefix']);
	$GLOBALS['db'] = $gDB;
	$GLOBALS['tablepre'] = DB_PREFIX;
	$uc_note = new uc_note(); 
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}

}

class uc_note {

	var $db = '';
	var $tablepre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once UI_ROOT.'uclient/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = UI_ROOT;
		$this->db = $GLOBALS['db'];
		$this->tablepre = $GLOBALS['tablepre'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		$uids = $get['ids'];
		!$uids && $uids = $post['ids'];
		!API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
		$this->db->query('delete from ' . DB_PREFIX . "user WHERE id in ({$uids})");
		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}
		$this->db->query('UPDATE ' . DB_PREFIX . "user set username='{$usernamenew}'  WHERE username = '{$usernameold}'");

		return API_RETURN_SUCCEED;
	}

	function synlogin($get, $post) {
		global $gGlobalConfig;
		$uid = $get['uid'];
		$username = strtolower($get['username']);
		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$cookiepre = $gGlobalConfig['cookie_prefix'];
		$cookiepath = $gGlobalConfig['cookie_path'];
		$cookiedomain = $gGlobalConfig['cookie_domain'];
		$timestamp = time();
		include_once(ROOT_PATH . 'lib/user/user.class.php');
		$user = new user();
		$member = $user->verify_user_exist($get['username'], $get['password']);
		if ($member)
		{
			$sql = "SELECT * FROM " . DB_PREFIX . "user WHERE username='".$get['username']."'";
			$first = $this->db->query_first($sql);
			if(!$first)
			{
				$sql = "INSERT INTO ".DB_PREFIX."user(id,username,password,salt,email,avatar,register_time,ip, member_id) 
				values(".$member['id'].",'".$get['username']."','".$member['password']."','".$member['salt']."','".$member['email']."','".$member['avatar']."',".$member['join_time'].",'".$ip."', " . $member['id'] . ")";
				$this->db->query($sql);
				$sql = "INSERT INTO ".DB_PREFIX."user_extra(user_id) 
				values(".$member['id'].")";
				$this->db->query($sql);		
				$user_name = $second['username'];
				$password = $second['password'];
				$user_id = $id;
			}
			setcookie($cookiepre . 'user', $member['username'], $timestamp+ 31536000, $cookiepath, $cookiedomain);
			setcookie($cookiepre . 'pass',$member['password'], $timestamp+ 31536000, $cookiepath, $cookiedomain);
			setcookie($cookiepre . 'member_id',$member['id'], $timestamp+ 31536000, $cookiepath, $cookiedomain);
		}
		
	}

	function synlogout($get, $post) {
		global $cookiedomain, $cookiepath, $cookiepre, $timestamp;
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}

		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$cookiepre = $gGlobalConfig['cookie_prefix'];
		$cookiepath = $gGlobalConfig['cookie_path'];
		$cookiedomain = $gGlobalConfig['cookie_domain'];
		$timestamp = time();
		setcookie($cookiepre . 'user', '', $timestamp+ 31536000, $cookiepath, $cookiedomain);
		setcookie($cookiepre . 'pass','', $timestamp+ 31536000, $cookiepath, $cookiedomain);
		setcookie($cookiepre . 'member_id','', $timestamp+ 31536000, $cookiepath, $cookiedomain);
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}
		$username = $get['username'];
		$password = $get['password'];
		$salt = hg_generate_salt();
		$pass = md5(md5($password).$salt);
		$this->db->query('UPDATE ' . DB_PREFIX . "user set salt='{$salt}',password='{$pass}'  WHERE username = '{$username}'");
		return API_RETURN_SUCCEED;
	}
}

function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
	setcookie(($prefix ? $cookiepre : '').$var, $value,
		$life ? $timestamp + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
				return '';
			}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
