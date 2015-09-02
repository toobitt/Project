<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php
***************************************************************************/
/**
 * 创建图片
 * @param $uploadedfile 需生成的图片路径
 * @param $name 生成后的返回的图片名称
 * @param $path 需要存放的图片上级目录
 * @param $size 缩略图的尺寸（array）
 * @param $max_pixel 尺寸的最大值（M）
 * return 生成后的图片名
 */
function hg_mk_images($uploadedfile,$name,$path,$size,$max_pixel = 2)
{
	if(!$uploadedfile || !$name || !$path || !is_array($size))
	{
		return OBJECT_NULL;
	}
	include_once(ROOT_PATH . 'lib/class/gdimage.php');
	//源文件
	if((filesize($uploadedfile)/1024/1024) >= $max_pixel)
	{
		return PIXEL_ERROR;
	}
	$image = getimagesize($uploadedfile);
	$width = $image[0];
	$height = $image[1];

	$file_name = $name;
	
	//目录
	$file_dir = $path;	

	//文件路径
	$file_path = $file_dir . $file_name;
	if(!hg_mkdir($file_dir))
	{
		return UPLOAD_ERR_NO_FILE;
	}
	if(!copy($uploadedfile, $file_path))
	{			
		return UPLOAD_ERR_NO_FILE;	
	}
	
	$img = new GDImage($file_path , $file_path , '');
	foreach($size as $key=>$value)
	{
		$new_name = $value['label'].$file_name;
		$save_file_path = $file_dir . $new_name;
		$img->init_setting($file_path , $save_file_path , '');
		
		$img->maxHeight = $value['height'];
		$img->maxWidth = $value['width'];
	/*
		if($width > $height)
		{
			$img->maxWidth = $width > $value['width']?$value['width'] : $width;
			$img->maxHeight = $height * ($img->maxWidth/$width);
		}
		else 
		{
			$img->maxHeight = $height > $value['height']?$value['height'] : $height;
			$img->maxWidth = $width * ($img->maxHeight/$height);
		}
	*/	
		$img->makeThumb(3);		
	}
	
	return $file_name;
}

/**
 * 根据路径读取原图以及生成的缩略的访问路径
 * @param $filename 图片名称（一般是数据库存的数据）
 * @param $path 图片读取的前路径
 * @param $size 图片的大小规则（一般是config里定义的规则，按照一定规则）
 */
function hg_get_images($filename,$path,$size)
{
	if(!$filename || !$path || !is_array($size))
	{
		return array();
	}
	$info = array();
	$info['img'] = $path.$filename;
	foreach($size as $key => $value)
	{
		$info[$key] = $path.$value['label'].$filename;
	}
	return $info;
}

function hg_order_num($order_id)
{
	switch($order_id)
	{
		case $order_id < 1:
			return '0000';
		break;
		case $order_id < 10:
			return '000' . $order_id;
		break;
		case $order_id < 100:
			return '00' . $order_id;
		break;
		case $order_id < 1000:
			return '0' . $order_id;
		break;
		case $order_id < 10000:
			return $order_id;
		break;
		default:
			return $order_id;
		break;
	}
}

?>