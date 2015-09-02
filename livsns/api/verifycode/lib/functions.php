<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php
***************************************************************************/

/**
* 16进制颜色转换为RGB色值
* @method hex2rgb
*/
function hex2rgb($hexColor) {

$color = str_replace('#', '', $hexColor);
if (strlen($color) > 3)
{
	$rgb = array(
		'r' => hexdec(substr($color, 0, 2)),
		'g' => hexdec(substr($color, 2, 2)),
		'b' => hexdec(substr($color, 4, 2))
	);
}
else
{
	$color = str_replace('#', '', $hexColor);
	$r = substr($color, 0, 1) . substr($color, 0, 1);
	$g = substr($color, 1, 1) . substr($color, 1, 1);
	$b = substr($color, 2, 1) . substr($color, 2, 1);
	$rgb = array(
		'r' => hexdec($r),
		'g' => hexdec($g),
		'b' => hexdec($b)
	);
}
return $rgb;
}

?>