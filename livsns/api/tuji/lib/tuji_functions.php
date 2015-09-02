<?php
	//获取图集的上传目录
	/*function hg_num2dir($num)
	{
		$dir = number_format($num);
		$dir = explode(',', $dir);
		$dir[0] = str_pad($dir[0], 3, '0', STR_PAD_LEFT);
		$dir = implode('/', $dir) . '/';
		return $dir;
	}*/
	function build_file_name($filename = 'null.file')
	{
		$filename = explode('.',$filename);
		$suffix = $filename[count($filename)-1];
		$filename = date('Ymdhis').uniqid().'.'.$suffix;
		return $filename;
	}
	
	function build_file_jpg($filename)
	{
		$filename = explode('.',$filename);
		$suffix = $filename[count($filename)-1];
		$filename = date('Ymdhis').uniqid().'.jpg';
		return $filename;
	}
	
	function no_extension_file_name($filename)
	{
		$file_info = array();
		$filetype= strrchr($filename, ".");
		$_filename=str_replace($filetype,"",$filename);
		$file_info['filename'] = $_filename;
		$file_info['extension'] = trim($filetype,'.');
		return $file_info;
	}
	//配合array_filter使用,清空所有数组空value值
    function clean_array_null($v)
   {
	 $v=trimall($v);
	 if(!empty($v))return true;
	 return false;
   }
   
   function trimall($str)//删除空格
	{
		$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
		return str_replace($qian,$hou,$str);
	}
	function trims($str)//删除空格
	{
		if(is_array($str))
		{
			return $str;
		}
	return trim($str);
	}
	
?>