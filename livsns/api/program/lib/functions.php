<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 8250 2012-07-23 07:34:59Z lijiaying $
***************************************************************************/
function hg_get_pos($data)
{
	if(empty($data))
	{
		return 0;
	}
	else
	{
		//取分
		return intval($data/60);
	}
}

function hg_get_slider($data)
{
	if(empty($data))
	{
		return 720;
	}
	else
	{
		//取分
		return intval(720 - intval($data)/60);
	}
}

function hg_array_sameItems($array1,$array2) {
	$i = 0;
	$j = count($array1);
	$result = array();
	while($i<$j)
	{
		if(in_array($array1[$i],$array2))
		{
			array_push($result,$array1[$i]);
		}
		$i++;
	}
	return $result ? $result:false;
}

?>