<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/
/**
	 * 时间验证
	 * @name verify_timeline
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $starttime array 开始时间
	 * @param $endtime array 结束时间
	 * @return true or false 
	 */
function verify_timeline($starttime, $endtime , $date)
{
	$return = array_group($starttime, $endtime , $date);
	for ($i = 1, $c = count($return); $i < $c; $i++)
	{
		if (($return[$i] - $return[$i - 1]) < 0)
		{
			return false;
		}
	}
	return true;
}

/**
 * 开始时间和结束时间组成新数组
 * @name array_group
 * @access private
 * @author lijiaying
 * @category hogesoft
 * @copyright hogesoft
 * @param $arr1 array 开始时间
 * @param $arr2 array 结束时间
 * @param $temp array 日期
 * @return $array array 时间线
 */
function array_group($arr1, $arr2, $temp)
{
	$num = count($arr1);
	$array = array();
	$i = 0;
	$j = 0;
	while($j < $num)
	{
	   $array[$i] = strtotime(urldecode($temp. ' ' .$arr1[$j]));
	   $array[$i+1] = strtotime(urldecode($temp. ' ' .$arr2[$j]));
	   $i= $i + 2;
	   $j++;
	}
	return $array;
}
?>