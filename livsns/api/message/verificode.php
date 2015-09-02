<?php 
	error_reporting(7);
	session_start();
	
	header("Content-type: text/html; charset=utf-8");
	class verifycode
	{
		public $code = '';
		public $length = 5;
		public $session_code = '';
		public $img_width = 40;
		public $img_height = 40;
		public $strarray = array();
	
		function verifycode()
		{
			$str = 'weyupasdhkxcvbnm2345689';
			$len = strlen($str) - 1;
			for($i = 0 ; $i < $this->length; $i++)
			{
				$this->code .= $str[rand(0, $len)];
			}
		}
		
		function gen_img()
		{
			$this->img_width = 15 * $this->length + 10;
			if(function_exists('imagecreatetruecolor'))
			{
				$font = './arial.ttf'; //字体可更换，中文需要字体支持
				if (!is_file($font))
				{
					exit($font . '字体文件不存在');
				}
				$img = imagecreatetruecolor($this->img_width,$this->img_height);
				$_SESSION['session_code'] = $this->code;
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
				$xs = array(
						5, 12,30,38,58	
				);
				for($i = 0; $i < $this->length; $i++)
				{
					$x = $xs[$i];
					if ($i % 2 == 0)
					{
						$y = 30;
						$ag = 15;
					}
					else
					{
						$y = 30;
						$ag = -30;
					}
					$rand_color = imagecolorallocate($img,mt_rand(0,200),mt_rand(0,180),mt_rand(0,180));
					imagettftext($img,22,$ag, $x, $y,$rand_color,$font,$this->code[$i]);    
	
				}
				imagejpeg($img);
				header("Content-type: image/jpeg");
			}
		}
	}
	
	$code = new verifycode();
	$code->gen_img();

?>