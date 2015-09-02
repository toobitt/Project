<?php

/*
 * 获取身份证信息类
 * 生日/星座/年龄
 */
class IdCard
{
	private $idCardNumber;
	public function __construct($idCardNumber = '')
	{
		$this->idCardNumber = $idCardNumber;
	}
	
	//获取出生日期
	public function getBirthday()
	{
		if($this->idCardNumber)
		{
			$len = strlen($this->idCardNumber);
			if($len == 18)
			{
				return substr($this->idCardNumber,6,8);
			}
			else if($len == 15)
			{
				return '19' . substr($this->idCardNumber,6,6);
			}
		}
	}
	
	//获取年龄
	public function getAge($birthday = '')
	{
		if(!$birthday)
		{
			$birthday = $this->getBirthday();
		}
		
		$birthday_time = strtotime($birthday);
		return intval((time() - $birthday_time)/(3600 * 24 * 365));
	}
	
	/*
	 * 	  获取星座
	 *    1 	=> "摩羯",
		  2 	=> "水瓶",
		  3 	=> "双鱼",
		  4		=> "白羊",
		  5 	=> "金牛",
		  6 	=> "双子",
		  7 	=> "巨蟹",
		  8 	=> "狮子",
		  9 	=> "处女",
		  10 	=> "天秤",
		  11 	=> "天蝎",
		  12 	=> "射手",
	 * 
	 */
	public function getConstellation($birthday = '')
	{
		if(!$birthday)
		{
			$birthday = $this->getBirthday();
		}

		$month = intval(date('m',strtotime($birthday)));
		$day   = intval(date('d',strtotime($birthday)));
		$strValue = 0;
		if (($month == 1 && $day >= 20) || ($month == 2 && $day <= 18)) 
		{
			$strValue = 2;
		} 
		else if (($month == 2 && $day >= 19) || ($month == 3 && $day <= 20)) 
		{
			$strValue = 3;
		} 
		else if (($month == 3 && $day > 20) || ($month == 4 && $day <= 19)) 
		{
			$strValue = 4;
		} 
		else if (($month == 4 && $day >= 20) || ($month == 5 && $day <= 20)) 
		{
			$strValue = 5;
		} 
		else if (($month == 5 && $day >= 21) || ($month == 6 && $day <= 21)) 
		{
			$strValue = 6;
		} 
		else if (($month == 6 && $day > 21) || ($month == 7 && $day <= 22)) 
		{
			$strValue = 7;
		} 
		else if (($month == 7 && $day > 22) || ($month == 8 && $day <= 22)) 
		{
			$strValue = 8;
		} 
		else if (($month == 8 && $day >= 23) || ($month == 9 && $day <= 22)) 
		{
			$strValue = 9;
		} 
		else if (($month == 9 && $day >= 23) || ($month == 10 && $day <= 23)) 
		{
			$strValue = 10;
		} 
		else if (($month == 10 && $day > 23) || ($month == 11 && $day <= 22)) 
		{
			$strValue = 11;
		} 
		else if (($month == 11 && $day > 22) || ($month == 12 && $day <= 21)) 
		{
			$strValue = 12;
		} 
		else if (($month == 12 && $day > 21) || ($month == 1 && $day <= 19)) 
		{
			$strValue = 1;
		}
		return $strValue;
	}
}