<?php 

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

/**
 * 转换版本信息
 * @param Integer $version  版本号
 * @param Boolean $flag  是否更新
 */
function getVersionName($version)
{
    $version = intval($version);
    $arr = array();
    for ($i = strlen($version); $i--;) 
    {
    	$arr[$i] = substr($version, $i, 1);
    }
    ksort($arr);
    return implode('.', $arr);
}

/**
 * 检测颜色值的有效性
 */
function checkColor($val)
{
    if (empty($val)) return false;
    if (strpos($val, '#') === false) $val = '#' . $val;
    if (!preg_match('/^#[0-9a-f]{6}|[0-9a-f]{3}$/i', $val)) return false;
    if (strlen($val) == 4)
    {
        $newStr = substr($val, 1);
        $len = strlen($newStr);
        $out = '#';
        for ($i = 0; $i < $len; $i++)
        {
            $color = substr($newStr, $i, 1);
            $out .= str_repeat($color, 2);
        }
        $val = $out;
    }
    return $val;
}

//对象转换成数组
function object_array($array)
{
  	if(is_object($array))
  	{
    	$array = (array)$array;
  	}
  	
  	if(is_array($array))
  	{
    	foreach($array as $key=>$value)
    	{
      		$array[$key] = object_array($value);
    	}
  	}
  	return $array;
} 

?>