<?php
/*******************************************************************
 * filename :functions.php
 * Created  :2013年8月8日,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 *
 ******************************************************************/
/*
 * @function debug export var
 * @param:$fname
 * @param:$var
 * @param:$line which line calls the function
 * @param:$file which file calls the function
 *
 */

function export_var($fname, $var, $line, $file, $flag = false) {
	if (DEBUG_OPEN) {
		$path = realpath($fname);
		$path_parts = pathinfo($path);
		$content = $line . "\n" . $file . "\n" . var_export($var, 1) . "\n";

		if (@!file_put_contents($fname, $content) || !$flag) {
			echo "<div class='debug_export'>";
			echo $content;
			echo "</div>";
		}//end if

	}//end if

}

/*
 * 获取文件名称
 */
function get_filename() {
	$phpself = explode("/", $_SERVER['PHP_SELF']);
	return substr($phpself[count($phpself) - 1], 0, -4);
}

/**
 * 验证是否有合法的URL
 *
 * @param string $string  被搜索的 字符串
 * @param array $matches  会被搜索的结果,默认为array()
 * @param boolean $ifAll  是否进行全局正则表达式匹配，默认为false即仅进行一次匹配
 * @return boolean 如果匹配成功返回true，否则返回false
 */
function hasUrl($string, &$matches = array(), $ifAll = false) {
	return 0 < validateByRegExp('/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/', $string, $matches, $ifAll);
}

/**
* 验证是否是合法的url
*
* @param string $string 待验证的字串
* @return boolean 如果是合法的url则返回true，否则返回false
*/
function isUrl($string) {
	return 0 < preg_match('/^(?:http(?:s)?:\/\/(?:[\w-]+\.)+[\w-]+(?:\:\d+)*+(?:\/[\w- .\/?%&=]*)?)$/', $string);
}


/**
 * 在 $string 字符串中搜索与 $regExp 给出的正则表达式相匹配的内容。
 * 
 * @param string $regExp  搜索的规则(正则) 
 * @param string $string  被搜索的 字符串
 * @param array $matches 会被搜索的结果，默认为array()
 * @param boolean $ifAll   是否进行全局正则表达式匹配，默认为false不进行完全匹配
 * @return int 返回匹配的次数
 */
function validateByRegExp($regExp, $string, &$matches = array(), $ifAll = false) {
	return $ifAll ? preg_match_all($regExp, $string, $matches) : preg_match($regExp, $string, $matches);
}