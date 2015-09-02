<?php
//水费查询类
class water
{	
	public function query($user_number = '',$month = '')
	{
		if(!$user_number || !$month)
		{
			return false;
		}
		
		$url = WATER_API . '&code=' . $user_number . '&month=' . $month;
		$data = curlRequest($url);
		if(!$data)
		{
			return false;
		}
		return $data;
	}
}
