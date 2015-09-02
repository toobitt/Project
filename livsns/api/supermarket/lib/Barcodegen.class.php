<?php
require_once(CUR_CONF_PATH . 'lib/barcodegen/class/BCGFontFile.php');
require_once(CUR_CONF_PATH . 'lib/barcodegen/class/BCGColor.php');
require_once(CUR_CONF_PATH . 'lib/barcodegen/class/BCGDrawing.php');
require_once(CUR_CONF_PATH . 'lib/barcodegen/class/BCGcode128.barcode.php');

class Barcodegen
{
	private $font = 24;
	public function setFont($font)
	{
		$this->font = $font;
	}
	
	public function create($text = '',$filename = '')
	{
		if(!$text)
		{
			return false;
		}
		
		$font = new BCGFontFile(CUR_CONF_PATH . 'lib/barcodegen/font/Arial.ttf', $this->font);
		$text = $text ? $text : 'NO INPUTS';
		$color_black = new BCGColor(0, 0, 0);
		$color_white = new BCGColor(255, 255, 255);
		
		$drawException = null;
		try 
		{
			$code = new BCGcode128();
			$code->setScale(2); // Resolution
			$code->setThickness(60); // Thickness
			$code->setForegroundColor($color_black); // Color of bars
			$code->setBackgroundColor($color_white); // Color of spaces
			$code->setFont($font); // Font (or 0)
			$code->parse($text); // Text
		}
		catch(Exception $exception) 
		{
			$drawException = $exception;
		}
		
		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing = new BCGDrawing($filename, $color_white);
		if($drawException) 
		{
			$drawing->drawException($drawException);
		} 
		else 
		{
			$drawing->setBarcode($code);
			$drawing->draw();
		}
		
		// Header that says it is an image (remove it if you save the barcode to a file)
		/*
		header('Content-Type: image/png');
		header('Content-Disposition: inline; filename="barcode.png"');
		*/
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
		return true;
	}
}