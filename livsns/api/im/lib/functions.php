<?php
function get_states($start_time=0,$end_time=0)
{
	if($end_time == 0)
	{
		$state = '永久有效';
	}
	if($start_time > TIMENOW)
	{
		$state = '尚未开始';
	}
	if($end_time < TIMENOW && $end_time!=0)
	{
		$state = '已过期';
	}
	if($end_time < $start_time && $end_time!=0)
	{
		$state = '错误时间';
	}
	if($start_time < TIMENOW && $end_time >TIMENOW)
	{
		$state = '进行中';
	}
	return $state;
}

//配合array_filter使用,清空所有数组空value值
function clean_array_null($v)
{
	$v=trimall($v);
	if(!empty($v))return true;
	return false;
}
//配合array_filter使用,清空所有数组非数字值
function clean_array_num($v)
{
	if(is_numeric($v))return true;
	return false;
}
//配合array_filter使用,清空所有数组非数字值
function clean_array_num_max0($v)
{
	if(is_numeric($v)&&$v>0)return true;
	return false;
}
//配合array_filter使用,清空所有数组纯数字值
function clean_array_string($v)
{
	if(is_numeric($v))return false;
	return true;
}
function trimall($str)//删除空格
{
	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
	return str_replace($qian,$hou,$str);
}
