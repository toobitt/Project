<?php
if (!defined('ROOT_DIR'))
{
    define('ROOT_DIR', './');
}
if (!defined('CUR_CONF_PATH'))
{
    define('CUR_CONF_PATH', './');
}
if (!defined('M2O_ROOT_PATH'))
{
    //define('M2O_ROOT_PATH', ($dir = @realpath(ROOT_DIR)) ? $dir . '/' : ROOT_DIR);
}
//define('CUR_M2O_PATH', M2O_ROOT_PATH);
if (function_exists('date_default_timezone_set'))
{
    date_default_timezone_set('PRC');
}
define('TIMENOW', time());

//use pagecache
//include(M2O_ROOT_PATH . 'lib/pagecache.php');

require_once(M2O_ROOT_PATH . 'lib/func/functions.php');
require_once(M2O_ROOT_PATH . 'lib/web_functions.php');
require_once(M2O_ROOT_PATH . 'frm/base_frm.php');
require_once(M2O_ROOT_PATH . 'lib/class/curl.class.php');
@include_once(M2O_ROOT_PATH . 'conf/config.php');
@include_once(M2O_ROOT_PATH . 'conf/var.php');

foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES', '_SERVER') as $v)
{
    $$v = m2o_web_addslashes($$v);
}
if (PHP_VERSION < '6.0.0')
{
    @set_magic_quotes_runtime(0);

    define('MAGIC_QUOTES_GPC', @get_magic_quotes_gpc() ? true : false);
    if (MAGIC_QUOTES_GPC)
    {

        function stripslashes_vars(&$vars)
        {
            if (is_array($vars))
            {
                foreach ($vars as $k => $v)
                {
                    stripslashes_vars($vars[$k]);
                }
            }
            else if (is_string($vars))
            {
                $vars = stripslashes($vars);
            }
        }

        if (is_array($_FILES))
        {
            foreach ($_FILES as $key => $val)
            {
                $_FILES[$key]['tmp_name'] = str_replace('\\', '\\\\', $val['tmp_name']);
            }
        }

        foreach (array('_REQUEST', '_GET', '_POST', '_COOKIE', '_FILES') as $v)
        {
            stripslashes_vars($$v);
        }
    }

    define('SAFE_MODE', (@ini_get('safe_mode') || @strtolower(ini_get('safe_mode')) == 'on') ? true : false);
}
else
{
    define('MAGIC_QUOTES_GPC', false);
    define('SAFE_MODE', false);
}

$_REQUEST = $_INPUT = hg_init_input();

$_configs   = $gGlobalConfig;
$agent      = strtolower($_SERVER['HTTP_USER_AGENT']);
$is_iphone  = (strpos($agent, 'iphone')) ? true : false;
$is_ipad    = (strpos($agent, 'ipad')) ? true : false;
$is_ipod    = (strpos($agent, 'ipod')) ? true : false;
$is_android = (strpos($agent, 'android')) ? true : false;
define('ISIOS', ($is_iphone || $is_ipad || $is_ipod));
define('ISANDROID', $is_android);

function m2o_web_addslashes($string)
{
    if (is_array($string))
    {
        foreach ($string as $key => $val)
        {
            $string[$key] = m2o_web_addslashes($val);
        }
    }
    else
    {
        $string = addslashes($string);
    }
    return $string;
}

?>