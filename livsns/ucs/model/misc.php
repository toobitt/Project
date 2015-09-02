<?php

/*
	[UCenter] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms

	$Id: misc.php 2749 2011-03-14 10:48:45Z wang $
*/

!defined('IN_UC') && exit('Access Denied');

define('UC_ARRAY_SEP_1', 'UC_ARRAY_SEP_1');
define('UC_ARRAY_SEP_2', 'UC_ARRAY_SEP_2');

class miscmodel {

	var $db;
	var $base;

	function __construct(&$base) {
		$this->miscmodel($base);
	}

	function miscmodel(&$base) {
		$this->base = $base;
		$this->db = $base->db;
	}

	function get_host_by_url($url) {
		$m = parse_url($url);
		if(!$m['host']) {
			return -1;
		}
		if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $m['host'])) {
			$ip = @gethostbyname($m['host']);
			if(!$ip || $ip == $m['host']) {
				return -2;
			}
			return $ip;
		} else {
			return $m['host'];
		}
	}

	function check_url($url) {
		return preg_match("/(https?){1}:\/\/|www\.([^\[\"']+?)?/i", $url);
	}

	function check_ip($url) {
		return preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $url);
	}

	function dfopen2($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCODE') {
		$__times__ = isset($_GET['__times__']) ? intval($_GET['__times__']) + 1 : 1;
		if($__times__ > 2) {
			return '';
		}
		$url .= (strpos($url, '?') === FALSE ? '?' : '&')."__times__=$__times__";
		//file_put_contents('F:/vui.txt',$url.'\n',FILE_APPEND);
		return $this->dfopen($url, $limit, $post, $cookie, $bysocket, $ip, $timeout, $block, $encodetype);
	}

	function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE	, $ip = '', $timeout = 15, $block = TRUE, $encodetype  = 'URLENCODE') {
		//error_log("[uc_server]\r\nurl: $url\r\npost: $post\r\n\r\n", 3, 'c:/log/php_fopen.txt');
		$return = '';
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;

		if($post) {
			$out = "POST $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$boundary = $encodetype == 'URLENCODE' ? '' : ';'.substr($post, 0, trim(strpos($post, "\n")));
			$out .= $encodetype == 'URLENCODE' ? "Content-Type: application/x-www-form-urlencoded\r\n" : "Content-Type: multipart/form-data$boundary\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= 'Content-Length: '.strlen($post)."\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cache-Control: no-cache\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
			$out .= $post;
		} else {
			$out = "GET $path HTTP/1.0\r\n";
			$out .= "Accept: */*\r\n";
			//$out .= "Referer: $boardurl\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host:$port\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}

		if(function_exists('fsockopen')) {
			$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} elseif (function_exists('pfsockopen')) {
			$fp = @pfsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		} else {
			$fp = false;
		}

		if(!$fp) {
			return '';
		} else {
			stream_set_blocking($fp, $block);
			stream_set_timeout($fp, $timeout);
			@fwrite($fp, $out);
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
				while (!feof($fp)) {
					if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
						break;
					}
				}

				$stop = false;
				while(!feof($fp) && !$stop) {
					$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
					$return .= $data;
					if($limit) {
						$limit -= strlen($data);
						$stop = $limit <= 0;
					}
				}
			}
			@fclose($fp);
			return $return;
		}
	}

	function array2string($arr) {
		$s = $sep = '';
		if($arr && is_array($arr)) {
			foreach($arr as $k => $v) {
				$s .= $sep.$k.UC_ARRAY_SEP_1.$v;
				$sep = UC_ARRAY_SEP_2;
			}
		}
		return $s;
	}

	function string2array($s) {
		$arr = explode(UC_ARRAY_SEP_2, $s);
		$arr2 = array();
		foreach($arr as $k => $v) {
			list($key, $val) = explode(UC_ARRAY_SEP_1, $v);
			$arr2[$key] = $val;
		}
		return $arr2;
	}
}

?>