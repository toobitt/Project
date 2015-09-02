<?php
	//生成上传目录所在的文件夹名称
	function get_upload_dir($num = 0)
	{
		$dir = number_format($num);
		$dir = explode(',', $dir);
		$dir[0] = str_pad($dir[0], 3, '0', STR_PAD_LEFT);
		$dir = implode('/', $dir) . '/';
		return $dir;
	}
	//确定评论表名
	function check_table_name($year)
	{
		if($year != '2013')
		{
			$tableName = 'message_'.$year;
		}
		else 
		{
			$tableName = "message";
		}
		return $tableName;
	}
?>