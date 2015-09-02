<?php
/*
* $Id $
*/
class GDImage 
{ 
	public $sourcePath; //图片存储路径 
	public $galleryPath; //图片缩略图存储路径 
	public $toFile = false; //是否生成文件 
	public $forace_width = false; //等比时强制以宽生成 
	public $fontName; //使用的TTF字体名称 
	public $maxWidth = 500; //图片最大宽度 
	public $maxHeight = 600; //图片最大高度 

	//========================================== 
	// 函数: GDImage($sourcePath ,$galleryPath, $fontPath) 
	// 功能: constructor 
	// 参数: $sourcePath 图片源路径(包括最后一个"/") 
	// 参数: $galleryPath 生成图片的路径 
	// 参数: $fontPath 字体路径 
	//========================================== 
	function GDImage($sourcePath="", $galleryPath="", $fontpath = '')
	{
		$this->init_setting($sourcePath, $galleryPath, $fontpath);
	}

	function init_setting($sourcePath='', $galleryPath='', $fontpath = '') 
	{
		$this->sourcePath = $sourcePath;
		$this->galleryPath = $galleryPath;
		$this->fontName = $fontpath . "04B_08__.TTF";
	}
	//========================================== 
	// 函数: makeThumb($sourFile,$width=128,$height=128) 
	// 功能: 生成缩略图(输出到浏览器) 
	// 参数: $sourFile 图片源文件 
	// 参数: $maketype 索引图生成方式(1=>左上角 2=>右上角 3=>中间)
	// 参数: $dwindle  索引图等比生成方式(true 按等比方式生成)
	// @param: $skip 是否为小图生成缩略图
	// 参数: $width 生成缩略图的宽度 
	// 参数: $height 生成缩略图的高度 
	// 返回: 0 失败 成功时返回生成的图片路径 
	// @param: $must_format 强制小图生成缩略图,不进行缩放 和$skip 配合使用;
	//========================================== 

	function makeThumb($maketype = 1,$dwindle = true, $skip = true, $tojgp = false,$must_format = false,$force = false) 
	{ 
		$imageInfo = $this->getInfo($this->sourcePath); 
		switch ($imageInfo["type"]) 
		{ 
			case 1: //gif 
				$image = @imagecreatefromgif($this->sourcePath); 
			break; 
			case 2: //jpg 
				$image = @imagecreatefromjpeg($this->sourcePath); 
			break; 
			case 3: //png 
				$image = @imagecreatefrompng($this->sourcePath); 
			break; 
			default: 
				return false; 
			break; 
		}

		if (!$image)
		{ 
			return false; 
		}
		$width = $this->maxWidth; 
		$height = $this->maxHeight;
		$srcW = $imageInfo["width"]; 
		$srcH = $imageInfo["height"];
		
		$src_x = $src_y = 0;
		if($skip)
		{
			if($srcW < $width && $srcH < $height)
			{
				@copy($this->sourcePath, $this->galleryPath);
				return false;
			}
		}
		
		 
		if($must_format && $srcW < $width && $srcH < $height)
		{
			if(!$force)
			{
				 $this->maxWidth = $srcW;
				 $this->maxHeight = $srcH;
				 $width = $this->maxWidth; 
				 $height = $this->maxHeight;	
			}
		}
		else 
		{		
			if($srcW >= $srcH)
			{
				$w_bigger = 1;
			}
			else
			{
				$w_bigger = 0; 
			}
			
			if($dwindle)
			{
				if($w_bigger || $this->forace_width)
				{
					$height = $srcH * ($width / $srcW);
					$this->maxHeight = $srcH * ($width / $srcW);
				}
				else
				{
					$width = $srcW * ($height / $srcH);
					$this->maxWidth = $srcW * ($height / $srcH);
				}
			}
			else
			{
				$image_new_ratio = $width/$height;
				$image_ratio = $srcW/$srcH;
				if ($image_new_ratio > $image_ratio) 
				{
					$srcH = $srcH * ($image_ratio/$image_new_ratio);
				}
				else
				{
					$srcW = $srcW*($image_new_ratio/$image_ratio);
				}
				switch ($maketype)
				{
					case 2:
						$src_x = $imageInfo["width"] - $srcW;
					break;
					case 3:
						$src_x = ($imageInfo["width"] - $srcW)/2;
						$src_y = ($imageInfo["height"] - $srcH)/2;
					break;
				}
			}
		}

		if (function_exists('imageantialias')) {
			imageantialias($image, true);
		}
		$thumbnail_img = imagecreatetruecolor($this->maxWidth, $this->maxHeight);
		
		if (function_exists('imagecopyresampled')) 
		{
			@imagecopyresampled($thumbnail_img, $image, 0, 0, $src_x,$src_y, $width, $height, $srcW, $srcH);
		} 
		else 
		{
			@imagecopyresized($thumbnail_img, $image, 0, 0, $src_x,$src_y, $this->maxWidth, $this->maxHeight, $srcW, $srcH);
		}
	
		if($tojgp)
		{
			$imageInfo["type"] = 2;
		}
		if ($imageInfo["type"] == 1) 
		{
			if (!imagegif($thumbnail_img, $this->galleryPath)) 
			{
				return false;
			}
		} 
		else if ($imageInfo["type"] == 2) 
		{
			if (!imagejpeg($thumbnail_img, $this->galleryPath, 90)) 
			{
				return false;
			}
		}
		else if ($imageInfo["type"] == 3) 
		{
			if (!imagepng($thumbnail_img, $this->galleryPath)) 
			{
				return false;
			}
		}
		
		if ($this->toFile) 
		{ 
			if (file_exists($this->galleryPath)) 
			{
				return $this->galleryPath; 
			}
		}
		else
		{
			return true;
		}
	} 


	function create_watermark($uploadfile, $extension,$watermark_pos,$watermarkimg)
	{
		global $siteinfo;

		$uploadsize = @GetImageSize( $uploadfile );
		if(!$uploadsize[0] OR !$uploadsize[1]) return;
		if($extension=='.jpeg' OR $extension=='.jpg') {
			if (function_exists('imagecreatefromjpeg')) {
				$tmp = @imagecreatefromjpeg($uploadfile);
			} else {
				return;
			}
		} else	if($extension=='.png') {
			if (function_exists('imagecreatefrompng')) {
				$tmp = @imagecreatefrompng($uploadfile);
			} else {
				return;
			}
		} 
		else
			return;
		
		if($imgmark = @imagecreatefrompng(ROOT_PATH."images/watermark/".$watermarkimg))
		{	
			$marksize = @GetImageSize(ROOT_PATH."images/watermark/".$watermarkimg);
			if ($uploadsize[0] < ($marksize[0]) OR $uploadsize[1] < ($marksize[1])) 
			{
				return '';
			}
			switch (intval($watermark_pos)) {
				case 0:
					return;
					break;
				case 1:
					$pos_x = 0;
					$pos_y = 0;
					break;
				case 2:
					$pos_x = 0;
					$pos_y = $uploadsize[1] -  $marksize[1];
					break;
				case 3:
					$pos_x = $uploadsize[0] -  $marksize[0];
					$pos_y = 0;
					break;
				case 4:
					$pos_x = $uploadsize[0] -  $marksize[0];
					$pos_y = $uploadsize[1] -  $marksize[1];
					break;
				case 5:
					$pos_x = ($uploadsize[0] / 2) -  ($marksize[0] / 2);
					$pos_y = ($uploadsize[1] / 2) -  ($marksize[1] / 2);
					break;
				default:
					$pos_x = $uploadsize[0] -  $marksize[0];
					$pos_y = $uploadsize[1] -  $marksize[1];
					break;
			}
			//echo $pos_x."<hr />";
			//echo $pos_y;
			imagecopy($tmp, $imgmark, $pos_x, $pos_y, 0, 0, $marksize[0], $marksize[1]);
		} 
		else 
		{
			$white  = ImageColorAllocate($tmp, 0, 0, 0);
			$black  = ImageColorAllocate($tmp, 255, 255, 255);
			imagestring($tmp, 5, 2, 3, $siteinfo['weburl'], $white);
			imagestring($tmp, 5, 1, 2, $siteinfo['weburl'], $black);
		}
		if ( function_exists( 'imagejpeg' ) AND ($extension=='.jpeg' OR $extension=='.jpg') ) 
		{
			@imagejpeg( $tmp, $uploadfile );
			@imagedestroy( $tmp );
		} 
		else if ( function_exists( 'imagepng' ) AND $extension=='.png' )
		{
			@imagepng( $tmp, $uploadfile );
			@imagedestroy( $tmp );
		}
		else
		{
			return '';
		}
	}

	//========================================== 
	// 函数: waterMark($sourFile, $text) 
	// 功能: 给图片加水印 
	// 参数: $sourFile 图片文件名 
	// 参数: $text 文本数组(包含二个字符串) 
	// 返回: 1 成功 成功时返回生成的图片路径 
	//========================================== 
	function waterMark($sourFile, $text) 
	{ 
		global $siteinfo;

		$imageInfo = $this->getInfo($sourFile); 
		$sourFile = $this->sourcePath . $sourFile; 
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . "_mark.jpg"; 
			switch ($imageInfo["type"]) 
			{ 
				case 1: //gif 
				$img = imagecreatefromgif($sourFile); 
				break; 
				case 2: //jpg 
				$img = imagecreatefromjpeg($sourFile); 
				break; 
				case 3: //png 
				$img = imagecreatefrompng($sourFile); 
				break; 
				default: 
				return 0; 
				break; 
			} 
		if (!$img) 
		return 0; 

		$width = ($this->maxWidth > $imageInfo["width"]) ? $imageInfo["width"] : $this->maxWidth; 
		$height = ($this->maxHeight > $imageInfo["height"]) ? $imageInfo["height"] : $this->maxHeight; 
		$srcW = $imageInfo["width"]; 
		$srcH = $imageInfo["height"]; 
		if ($srcW * $width > $srcH * $height) 
		$height = round($srcH * $width / $srcW); 
		else 
		$width = round($srcW * $height / $srcH); 
		//* 
		if (function_exists("imagecreatetruecolor")) //GD2.0.1 
		{ 
			$new = imagecreatetruecolor($width, $height); 
			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]); 
		} 
		else 
		{ 
			$new = imagecreate($width, $height); 
			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]); 
		} 
		$white = imageColorAllocate($new, 255, 255, 255); 
		$black = imageColorAllocate($new, 0, 0, 0); 
		$alpha = imageColorAllocateAlpha($new, 230, 230, 230, 40); 
		//$rectW = max(strlen($text[0]),strlen($text[1]))*7; 
		ImageFilledRectangle($new, 0, $height-26, $width, $height, $alpha); 
		ImageFilledRectangle($new, 13, $height-20, 15, $height-7, $black); 
		ImageTTFText($new, 4.9, 0, 20, $height-14, $black, $this->fontName, $text[0]); 
		ImageTTFText($new, 4.9, 0, 20, $height-6, $black, $this->fontName, $text[1]); 
		//*/ 
		if ($this->toFile) 
		{ 
			if (file_exists($this->galleryPath . $newName)) 
				unlink($this->galleryPath . $newName); 
			ImageJPEG($new, $this->galleryPath . $newName); 
			return $this->galleryPath . $newName; 
		} 
		else 
		{ 
			ImageJPEG($new); 
		} 
		ImageDestroy($new); 
		ImageDestroy($img); 

	} 
	//========================================== 
	// 函数: displayThumb($file) 
	// 功能: 显示指定图片的缩略图 
	// 参数: $file 文件名 
	// 返回: 0 图片不存在 
	//========================================== 
	function displayThumb($file) 
	{ 
		$thumbName = substr($file, 0, strrpos($file, ".")) . "_thumb.jpg"; 
		$file = $this->galleryPath . $thumbName; 
		if (!file_exists($file)) 
		return 0; 
		$html = "<img src='$file' style='border:1px solid #000'/>"; 
		echo $html; 
	} 
	//========================================== 
	// 函数: displayMark($file) 
	// 功能: 显示指定图片的水印图 
	// 参数: $file 文件名 
	// 返回: 0 图片不存在 
	//========================================== 
	function displayMark($file) 
	{ 
		$markName = substr($file, 0, strrpos($file, ".")) . "_mark.jpg"; 
		$file = $this->galleryPath . $markName; 
		if (!file_exists($file)) 
		return 0; 
		$html = "<img src='$file' style='border:1px solid #000'/>"; 
		echo $html; 
	} 
	//========================================== 
	// 函数: getInfo($file) 
	// 功能: 返回图像信息 
	// 参数: $file 文件路径 
	// 返回: 图片信息数组 
	//========================================== 
	function getInfo($file) 
	{ 
		//$file = $this->sourcePath . $file; 
		$data = @getimagesize($file); 
		$imageInfo["width"] = $data[0]; 
		$imageInfo["height"]= $data[1]; 
		$imageInfo["type"] = $data[2]; 
		$imageInfo["name"] = basename($file); 
		return $imageInfo; 
	} 

} 

?>