<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php
***************************************************************************/
/**
 * 
 * 内容替换函数 ...
 * @param String $replacePrefix 标签占位符前缀
 * @param Array $replaceData 对应占位符下标数组
 * @param String $content 需要替换的内容
 * @param int $i 替换下标，一般从0开始
 */
function replaceContent($replacePrefix,$replaceData,$content,$i = 0)
{
	if($replacePrefix&&$content&&is_array($replaceData)&&$replaceData)
	{
		$val = array_shift($replaceData);
		$content = replaceContent($replacePrefix,$replaceData,str_replace('{$'.$replacePrefix.$i.'}',$val,$content),++$i);
	}
	return $content; 
}
?>