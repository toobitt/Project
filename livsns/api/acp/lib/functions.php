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

/******************************* CURL相关函数 *******************************/
/**
 * 创建curl
 */
function create_curl_obj($app_name) {
    $key = 'App_' . $app_name;
    global $gGlobalConfig;
    $key = 'App_' . $app_name;
    if (!$gGlobalConfig[$key]) {
        return false;
    }
    require_once (ROOT_PATH . 'lib/class/curl.class.php');
    return new curl($gGlobalConfig[$key]['host'], $gGlobalConfig[$key]['dir']);
}

function init_curl($curl = NULL) {
    if (is_object($curl)) {
        return false;
    }
    $curl -> setSubmitType('post');
    $curl -> setReturnFormat('json');
    $curl -> initPostData();
}

function get_common_datas($curl, $params) {
    foreach ($params as $key => $val) {
        if ($key == 'r') {
            $re = $curl -> request($val . ".php");
            return $re;
        } else {
            $curl -> addRequestData($key, $val);
        }
    }
}

function array_to_add($curl, $str, $data) {
    $str = $str ? $str : 'data';
    if (is_array($data)) {
        foreach ($data AS $kk => $vv) {
            if (is_array($vv)) {
                array_to_add($curl, $str . "[$kk]", $vv);
            } else {
                $curl -> addRequestData($str . "[$kk]", $vv);
            }
        }
    }
}

/******************************* CURL相关函数 end *******************************/

/**
 * 验证是否有合法的ipv4地址
 *
 * @param string $string   被搜索的 字符串
 * @param array $matches   会被搜索的结果,默认为array()
 * @param boolean $ifAll   是否进行全局正则表达式匹配，默认为false即仅进行一次匹配
 * @return boolean 如果匹配成功返回true，否则返回false
 */
function hasIpv4($string, &$matches = array(), $ifAll = false) {
    return 0 < self::validateByRegExp('/((25[0-5]|2[0-4]\d|1\d{2}|0?[1-9]\d|0?0?\d)\.){3}(25[0-5]|2[0-4]\d|1\d{2}|0?[1-9]\d|0?0?\d)/', $string, $matches, $ifAll);
}

/**
 * 验证是否是合法的IP
 *
 * @param string $string 待验证的字串
 * @return boolean 如果是合法的IP则返回true，否则返回false
 */
function isIpv4($string) {
    return 0 < preg_match('/(?:(?:25[0-5]|2[0-4]\d|1\d{2}|0?[1-9]\d|0?0?\d)\.){3}(?:25[0-5]|2[0-4]\d|1\d{2}|0?[1-9]\d|0?0?\d)/', $string);
}

/**
 * 验证是否有合法的ipV6
 *
 * @param string $string 被搜索的 字符串
 * @param array $matches 会被搜索的结果,默认为array()
 * @param boolean $ifAll 是否进行全局正则表达式匹配，默认为false即仅进行一次匹配
 * @return boolean 如果匹配成功返回true，否则返回false
 */
function hasIpv6($string, &$matches = array(), $ifAll = false) {
    return 0 < self::validateByRegExp('/\A((([a-f0-9]{1,4}:){6}|
                                        ::([a-f0-9]{1,4}:){5}|
                                        ([a-f0-9]{1,4})?::([a-f0-9]{1,4}:){4}|
                                        (([a-f0-9]{1,4}:){0,1}[a-f0-9]{1,4})?::([a-f0-9]{1,4}:){3}|
                                        (([a-f0-9]{1,4}:){0,2}[a-f0-9]{1,4})?::([a-f0-9]{1,4}:){2}|
                                        (([a-f0-9]{1,4}:){0,3}[a-f0-9]{1,4})?::[a-f0-9]{1,4}:|
                                        (([a-f0-9]{1,4}:){0,4}[a-f0-9]{1,4})?::
                                    )([a-f0-9]{1,4}:[a-f0-9]{1,4}|
                                        (([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}
                                        ([0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])
                                    )|((([a-f0-9]{1,4}:){0,5}[a-f0-9]{1,4})?::[a-f0-9]{1,4}|
                                        (([a-f0-9]{1,4}:){0,6}[a-f0-9]{1,4})?::
                                    )
                                )\Z/ix', $string, $matches, $ifAll);
}

/**
 * 验证是否是合法的ipV6
 *
 * @param string $string 待验证的字串
 * @return boolean 如果是合法的ipV6则返回true，否则返回false
 */
function isIpv6($string) {
    return 0 < preg_match('/\A(?:(?:(?:[a-f0-9]{1,4}:){6}|
                                        ::(?:[a-f0-9]{1,4}:){5}|
                                        (?:[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){4}|
                                        (?:(?:[a-f0-9]{1,4}:){0,1}[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){3}|
                                        (?:(?:[a-f0-9]{1,4}:){0,2}[a-f0-9]{1,4})?::(?:[a-f0-9]{1,4}:){2}|
                                        (?:(?:[a-f0-9]{1,4}:){0,3}[a-f0-9]{1,4})?::[a-f0-9]{1,4}:|
                                        (?:(?:[a-f0-9]{1,4}:){0,4}[a-f0-9]{1,4})?::
                                    )(?:[a-f0-9]{1,4}:[a-f0-9]{1,4}|
                                        (?:(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])\.){3}
                                        (?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])
                                    )|(?:(?:(?:[a-f0-9]{1,4}:){0,5}[a-f0-9]{1,4})?::[a-f0-9]{1,4}|
                                        (?:(?:[a-f0-9]{1,4}:){0,6}[a-f0-9]{1,4})?::
                                    )
                                )\Z/ix', $string);
}

function curl_post($url, $postdatas) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

?>