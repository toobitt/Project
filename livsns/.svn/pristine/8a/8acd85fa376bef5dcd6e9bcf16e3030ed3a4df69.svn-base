<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: geoinfo.php 2242 2011-02-25 03:25:14Z yuna $
***************************************************************************/
/**
* verifycode.php :: 验证码 :: dealfunc
* 
* $Id: db_mysql.php 67 2007-06-01 03:48:20Z $
*/
@error_reporting(7);
@session_start();
class verifycode
{
	var $code = '';
	var $length = 4;
	var $session_code = '';
	var $img_width = 54;
	var $img_height = 21;
	var $strarray = array();

	function verifycode()
	{
		for($i = 0 ; $i < $this->length; $i++)
		{
			$this->code .= dechex(rand(0,15));
		}
	}
	
	function gen_img()
	{
		$this->img_width = 15 * $this->length;
		if(function_exists('imagecreatetruecolor'))
		{
			$img = imagecreatetruecolor($this->img_width,$this->img_height);
			$_SESSION['hg_verifycode'] = $this->code;
			$white = ImageColorAllocate($img, 255,255,255);           
			$black = ImageColorAllocate($img, 0,0,0);
			$gray = imagecolorallocate($img,200,200,200);
			ImageRectangle($img,1,1,$this->img_width-1,$this->img_height-1,$black);
			imagefill($img,$this->img_width,$img->height,$white);
			
			for($i = 0; $i<200; $i++)
			{
				$rand_color = imagecolorallocate($img,mt_rand(180,255),mt_rand(180,255),mt_rand(180,255));
				imagesetpixel($img,mt_rand()%($this->img_width),mt_rand()%($this->img_height),$rand_color);
			}
			for($i = 0; $i < $this->length; $i++)
			{
				$rand_color = imagecolorallocate($img,mt_rand(0,180),mt_rand(0,180),mt_rand(0,180));
				imagestring($img,5,1+$i*15,1,$this->code[$i],$rand_color);
			}
			imagejpeg($img);
			header("Content-type: image/jpeg");
		}
	}
}

$code = new verifycode();
$code->gen_img();
?>