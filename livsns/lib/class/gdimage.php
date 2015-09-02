<?php
/*
* $Id $
*/
class GDImage 
{ 
	var $sourcePath; //图片存储路径 
	var $galleryPath; //图片缩略图存储路径 
	var $toFile = false; //是否生成文件 
	var $forace_width = false; //等比时强制以宽生成 
	var $fontName; //使用的TTF字体名称 
	var $maxWidth = 500; //图片最大宽度 
	var $maxHeight = 600; //图片最大高度
	
	
	
	//
	var $angle;		//图片角度  旋转角度
	private $pos 				= 0 ;				//水印位置
	private  $fontColor          = array(255,0,255); //水印文字颜色（RGB）
	private $waterImg           = '';       		//水印图片   
    private $srcImg             = '';               //需要添加水印的图片   
    private $img                 = '';               //图片句柄   
    private $water_img           = '';               //水印图片句柄   
    private $srcImg_info        = '';               //图片信息   
    private $waterImg_info      = '';               //水印图片信息   
    private $str_w              = '';               //水印文字宽度   
    private $str_h              = '';               //水印文字高度   
    private $x                  = '';               //水印X坐标   
    private $y                  = '';               //水印y坐标   
    private $margin_x			= '';				//水印X轴偏移
    private $margin_y			= '';				//水印Y轴偏移

	//========================================== 
	// 函数: GDImage($sourcePath ,$galleryPath, $fontPath) 
	// 功能: constructor 
	// 参数: $sourcePath 图片源路径(包括最后一个"/") 
	// 参数: $galleryPath 生成图片的路径 
	// 参数: $fontPath 字体路径 
	//========================================== 
	function GDImage($sourcePath="", $galleryPath="", $fontpath = '', $angle = '')
	{
		$this->init_setting($sourcePath, $galleryPath, $fontpath, $angle);
	}

	function init_setting($sourcePath='', $galleryPath='', $fontpath = '',$angle = '') 
	{
		$this->sourcePath = $sourcePath;
		$this->galleryPath = $galleryPath;
		$this->fontName = $fontpath . "04B_08__.TTF";
		$this->angle = $angle;
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
            
                //从 JPEG 或 TIFF 文件中读取 EXIF 头信息 相机照片缩略图旋转问题
                @$exif = exif_read_data($this->sourcePath);
                if(!empty($exif['Orientation'])) {
                    switch($exif['Orientation']) {
                        case 8:
                            $angle = 90;  //左旋转
                            $tmp = $imageInfo['width'];
                            $imageInfo['width'] = $imageInfo['height'];
                            $imageInfo['height'] = $tmp;
                            $tmp = $this->maxWidth;
                            $this->maxWidth = $this->maxHeight;
                            $this->maxHeight = $tmp;
                            break;
                        case 3:
                            $angle = 180;  //180旋转
                            break;
                        case 6:
                            $angle = -90;  //左旋转
                            $tmp = $imageInfo['width'];
                            $imageInfo['width'] = $imageInfo['height'];
                            $imageInfo['height'] = $tmp;   
                            $tmp = $this->maxWidth;
                            $this->maxWidth = $this->maxHeight;
                            $this->maxHeight = $tmp;                                                        
                            break;
                    }
                    if ($angle) {
                        $image = imagerotate($image, $angle, 0);
                    }
                }
                
                //从 JPEG 或 TIFF 文件中读取 EXIF 头信息 相机照片缩略图旋转问题
			break; 
			case 3: //png 
				$image = @imagecreatefrompng($this->sourcePath); 
				imagesavealpha($image,true);
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
			if($srcW <= $width && $srcH <= $height)
			{
				@copy($this->sourcePath, $this->galleryPath);
				return true;
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
		if($imageInfo["type"] == 3)
		{
			imagealphablending($thumbnail_img,false);	//不合并颜色,直接用$thumbnail_img图像颜色替换,包括透明色
			imagesavealpha($thumbnail_img,true);        //保留$thumbnail_img图像透明色
		}
		
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
		} elseif($extension=='.png') {
			if (function_exists('imagecreatefrompng')) {
				$tmp = @imagecreatefrompng($uploadfile);
			} else {
				return;
			}
		}
		else
			return;
		
		if($imgmark = @imagecreatefrompng($watermarkimg))
		{	
			$marksize = @GetImageSize($watermarkimg);
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

	/**
	 * 
	 * 根据图片角度$this->angle旋转图片 ...
	 * 
	 * @name		turnImg
	 * @access		public
	 * @author		wangleyuan
	 * @category	hogesoft
	 * @copyright	hogesoft
	 * 
	 */
	public function turnImg()
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
		$white = @imagecolorallocate($image, 255, 255, 255);
		$image = @imagerotate($image,$this->angle,$white);
		imagealphablending($image,false);
		imagesavealpha($image,true);

		if($imageInfo["type"] == 1) 
		{
			if (!imagegif($image, $this->galleryPath)) 
			{
				return false;
			}
		} 
		else if ($imageInfo["type"] == 2) 
		{
			if (!imagejpeg($image, $this->galleryPath, 90)) 
			{
				return false;
			}
		}
		else if ($imageInfo["type"] == 3) 
		{
			if (!imagepng($image, $this->galleryPath)) 
			{
				return false;
			}
		}
		
		return true;
	}

	
	/**
	 * 添加图片水印 支持背景图片透明
	 * 
	 * @name		waterimg
	 * @author		wangleyuan
	 * @category	hogesoft
	 * @copyright	hogesoft 
	 * @param	string 	$srcImg		目标图片
	 * @param	string	$waterImg	目标水印
	 * @param	int		$position	水印位置
	 * @param	float	$pct		透明度    0 - 100 
	 */
    public function waterimg($srcImg,$water = array()) 
    {   
    	if(empty($water['water_file_path']))
    	{
    		return false;
    	}
    	if(!file_exists($water['water_file_path']))
    	{
    		return false;
    	}
    	$this->srcImg = $srcImg;
    	$this->waterImg = $water['water_file_path'];
    	$this->pos = $water['position'];
    	$this->margin_x = $water['margin_x'];
    	$this->margin_y = $water['margin_y'];
    	
    	$this->imginfo();			//获取目标图片信息，并载入图片
    	$this->waterimginfo();			//获取水印图片信息,并载入水印图片
    	$this->waterpos();			//根据水印位置计算水印坐标
    	
    	if ($this->img && $this->water_img) {
    	   //判断水印图片是否超过目标图标
            if ($this->srcImg_info['width'] <= $this->waterImg_info['width'] || $this->srcImg_info['height'] <= $this->waterImg_info['height']){   
                return false;
            } 
    		
            if($this->srcImg_info['width'] < $water['condition_x'] || $this->srcImg_info['height'] < $water['condition_y'])
            {
            	return false;
            }
            
            $cut = imagecreatetruecolor($this->waterImg_info['width'],$this->waterImg_info['height']);   
            imagecopy($cut,$this->img,0,0,$this->x,$this->y,$this->waterImg_info['width'],$this->waterImg_info['height']);    
            imagecopy($cut,$this->water_img,0,0,0,0,$this->waterImg_info['width'],$this->waterImg_info['height']);   
            imagecopymerge($this->img,$cut,$this->x,$this->y,0,0,$this->waterImg_info['width'],$this->waterImg_info['height'],$water['opacity']);
            //输出
            $this->output();
        }    
    }
    
    /**
     * 
     * 添加文字水印函数，支持文字透明
     *
     * @name				waterstr
     * @author				wangleyuan
     * @category			hogesoft
     * @copyright			hogesoft
     * @access public
     * @param $srcImg		源图片地址	
     * @param $waterStr		水印文字
     * @param $position		水印位置
     * @param $pct			水印透明度
     * @param $waterColor	水印文字颜色
     * @param $fontSize		水印文字字体大小	
     * @param $waterFont	水印文字字体路径
     */
     public function waterstr($srcImg,$water = array()) 
     {
     	if(empty($water['water_text']) || empty($water['water_color']) || empty($water['font_size']) || empty($water['water_font']))  
     	{
     		return false;
     	}
        if(!file_exists($water['water_font']))
        {
            return false;
        }
        
     	$this->srcImg = $srcImg;
     	$this->pos = $water['position'];
     	$this->margin_x = $water['margin_x'];
     	$this->margin_y = $water['margin_y'];
     	
     	$this->html2rgb($water['water_color']); 						//16进制颜色转换为rgb并保存
     	
     	//$waterStr= iconv('ISO-8859-1','UTF-8',$water['water_text']);	//防止中文乱码
     	$waterStr = $water['water_text'];

        $rect = imagettfbbox($water['font_size'],0,$water['water_font'],$waterStr);   
        $min_x = min( array($rect[0], $rect[2], $rect[4], $rect[6]) ); 
        $max_x = max( array($rect[0], $rect[2], $rect[4], $rect[6]) ); 
        $min_y = min( array($rect[1], $rect[3], $rect[5], $rect[7]) ); 
        $max_y = max( array($rect[1], $rect[3], $rect[5], $rect[7]) ); 
        $w  = ( $max_x - $min_x ); 
        $h = ( $max_y - $min_y + 10);         
        $fontHeight = $water['font_size'];
        $this->water_img = imagecreatetruecolor($w, $h);   
        imagealphablending($this->water_img,false);   
        imagesavealpha($this->water_img,true);   
        $white_alpha = imagecolorallocatealpha($this->water_img,255,255,255,127);   
        
        imagefill($this->water_img,0,0,$white_alpha);   		//创建透明图片 
        
        $color = imagecolorallocate($this->water_img,$this->fontColor[0],$this->fontColor[1],$this->fontColor[2]);   
        imagettftext($this->water_img,$water['font_size'],0,0,$water['font_size'],$color,$water['water_font'],$waterStr);   
        $this->waterImg_info = array('width'=>$w,'height'=>$h);   
        
        
        $this->imginfo();			//获取目标图片信息，并载入图片
        $this->waterpos();			//根据水印位置计算水印坐标
        
        if ($this->img && $this->water_img) {
         	//判断水印图片是否超过目标图标
            if ($this->srcImg_info['width'] <= $this->waterImg_info['width'] || $this->srcImg_info['height'] <= $this->waterImg_info['height'])
            {   
                return false;
            }       
            if($this->srcImg_info['width'] < $water['condition_x'] || $this->srcImg_info['height'] < $water['condition_y'])
            {
            	return false;
            }
            
            $cut = imagecreatetruecolor($this->waterImg_info['width'],$this->waterImg_info['height']);
//            imagealphablending($cut, false);
//            imagesavealpha($cut, true);
            imagecopy($cut,$this->img,0,0,$this->x,$this->y,$this->waterImg_info['width'],$this->waterImg_info['height']);    
            imagecopy($cut,$this->water_img,0,0,0,0,$this->waterImg_info['width'],$this->waterImg_info['height']);
            imagecopymerge($this->img,$cut,$this->x,$this->y,0,0,$this->waterImg_info['width'],$this->waterImg_info['height'],$water['opacity']);
            
            //输出
            $this->output();
        }
    }    
	
    
     /**
      * 
      * 获取需要添加水印的图片信息，判断图片是否可用，根据图片格式载入图片 
      * 
      * @name		imginfo
      * @access		private
      * @author		wangleyuan
      * @category	hogesoft
      * @copyright	hogesoft
      * 
      * 
      */
     private function imginfo() 
     {   
     	if(!file_exists($this->srcImg))
     	{                
     		return false;
     	}
     	
        $src = '';
        if(function_exists("file_get_contents"))
        {
            $src = file_get_contents($this->srcImg);
        }
        else
        {
            $handle = fopen ($this->srcImg, "r");
            while (!feof ($handle))
            {
                $src .= fgets($handle, 4096);
            }
            fclose ($handle);
        }
        if(empty($src))
        {
            return false;
        }
        
        $this->srcImg_info = $this->getInfo($this->srcImg);   
        switch ($this->srcImg_info['type']) 
        {   
            case 3:   
                $this->img = imagecreatefrompng($this->srcImg);
                imagealphablending($this->img, false);
                imagesavealpha($this->img, true);
                break 1;   
            case 2:   
                $this->img = imagecreatefromjpeg($this->srcImg);   
                break 1;   
            case 1:   
                $this->img = imagecreatefromgif($this->srcImg);
                break 1;   
            default:   
                return false;   
        }   
    } 
    
     /**
      * 
      * 获取水印图片的信息，判断水印图片是否可用，根据水印图片格式载入水印图片 ...
      * 
      * @name		waterimginfo
      * @access		private
      * @author		wangleyuan
      * @category	hogesoft
      * @copyright	hogeost
      */
     private function waterimginfo() 
     {  
        if(!file_exists($this->waterImg))
     	{                
     		return false;
     	}
     	
        $src = '';
        if(function_exists("file_get_contents"))
        {
            $src = file_get_contents($this->waterImg);
        }
        else
        {
            $handle = fopen ($this->waterImg, "r");
            while (!feof ($handle))
            {
                $src .= fgets($handle, 4096);
            }
            fclose ($handle);
        }
        if(empty($src))
        {
            return false;
        }    
                
        $this->waterImg_info = $this->getInfo($this->waterImg);   
        switch ($this->waterImg_info['type']) 
        {   
            case 3:   
                $this->water_img = imagecreatefrompng($this->waterImg);   
                break;   
            case 2:   
                $this->water_img = imagecreatefromjpeg($this->waterImg);   
                break;   
            case 1:   
                $this->water_img = imagecreatefromgif($this->waterImg);   
                break;   
            default:   
                return false;   
        }   
    } 

     /**
      * 
      * 计算水印位置 ...
      * 
      * @name 		waterpos
      * @access		private
      * @author		wangleyuan
      * @category	hogesoft
      * @copyright	hogesoft
      * 
      */
     private function waterpos() 
     {                 
        switch ($this->pos) 
        {   
            case 0:     //随机位置   
                $this->x = rand(0,$this->srcImg_info['width']-$this->waterImg_info['width']);   
                $this->y = rand(0,$this->srcImg_info['height']-$this->waterImg_info['height']);   
                break 1;   
            case 1:     //上左   
                $this->x = $this->margin_x;   
                $this->y = $this->margin_y;   
                break 1;   
            case 2:     //上中   
                $this->x = ($this->srcImg_info['width']-$this->waterImg_info['width'])/2;   
                $this->y = $this->margin_y;   
                break 1;   
            case 3:     //上右   
                $this->x = $this->srcImg_info['width']-$this->waterImg_info['width'] - $this->margin_x;   
                $this->y = $this->margin_y;   
                break 1;   
            case 4:     //中左   
                $this->x = $this->margin_x;   
                $this->y = ($this->srcImg_info['height']-$this->waterImg_info['height'])/2 + $this->margin_y;   
                break 1;   
            case 5:     //中中   
                $this->x = ($this->srcImg_info['width']-$this->waterImg_info['width'])/2 + $this->margin_x;   
                $this->y = ($this->srcImg_info['height']-$this->waterImg_info['height'])/2 +$this->margin_y;   
                break 1;   
            case 6:     //中右   
                $this->x = $this->srcImg_info['width']-$this->waterImg_info['width'] - $this->margin_x;   
                $this->y = ($this->srcImg_info['height']-$this->waterImg_info['height'])/2 + $this->margin_y;   
                break 1;   
            case 7:     //下左   
                $this->x = $this->margin_x;   
                $this->y = $this->srcImg_info['height']-$this->waterImg_info['height'] + $this->margin_y;   
                break 1;   
            case 8:     //下中   
                $this->x = ($this->srcImg_info['width']-$this->waterImg_info['width'])/2 + $this->margin_x;   
                $this->y = $this->srcImg_info['height']-$this->waterImg_info['height'] - $this->margin_y;   
                break 1;   
            default:    //下右   
                $this->x = $this->srcImg_info['width']-$this->waterImg_info['width'] - $this->margin_x;   
                $this->y = $this->srcImg_info['height']-$this->waterImg_info['height'] - $this->margin_y;   
                break 1;   
        }   
    }

    
    
    /**
     * 
     * 根据图片格式输出图片,并销毁图片和水印的资源句柄 ...
     * 
     * @name		output
     * @access		private
     * @author		wangleyuan
     * @category	hogesoft
     * @copyright	hogesoft
     * 
     * 
     */
    private function output() 
    {   
        switch($this->srcImg_info['type'])
        {   
            case 3:   
                imagepng($this->img,$this->srcImg);   
                break 1;   
            case 2:   
                imagejpeg($this->img,$this->srcImg);   
                break 1;   
            case 1:   
                imagegif($this->img,$this->srcImg);   
                break 1;   
            default:   
                break;   
        }   
        imagedestroy($this->img);   
        imagedestroy($this->water_img);   
    }

    
    /**
     * 
     * 将16进制色彩转换成RGB色
     * 
     * @name		html2rgb
     * @access		private
     * @author		wangleyuan
     * @category	hogesoft
     * @copyright	hogesoft
     * @param $color  string 16进制颜色
     * @param $returnstring boole  是否以字符串的形式返回
     * 
     */
	private function  html2rgb($color,$returnstring=false)
	{
	    if ($color[0] == '#')
	    { 
	       $color = substr($color, 1);
	    }
	    if (strlen($color) == 6)
	    {
	       list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
	    }
	    elseif (strlen($color) == 3)
	    {
	        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	    }
	    else
	    {
	        return false;
	    }
	    $key = 1;
	    $r = hexdec($r)*$key;
	    $g = hexdec($g)*$key;
	    $b = hexdec($b)*$key;
	    if($returnstring)
	    {
	        $this->fontColor =  "{rgb $r $g $b}";
	    }
	    else
	    {
	        $this->fontColor =  array($r, $g, $b);
	    }
	}
    
} 

?>
