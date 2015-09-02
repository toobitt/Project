<?php

	/**
	 * 检查直播路径中的端口,80保持不变
	 * Enter description here ...
	 * @param string $live_url 直播url
	 */
	function check_live_port($live_url)
	{
		$live_port = CHEAPBUY_LIVE_PORT;
		if(!$live_port)
		{
			return false;
		}
						
		if(!$live_url)
		{
			return false;
		}
		$url1 = explode('//', $live_url);
		
		if($url1[1])
		{
			$url = explode('/', $url1[1]);
		}
		if($url[0])
		{
			$url2 = explode(':', $url[0]);
			
			if($url2[0])
			{
				if($live_port == 80)
				{
					$new_live_port = '';
				}
				else 
				{
					$new_live_port = ':' . $live_port;
				}
				
				$new_url_host = $url1[0].'//' . $url2[0] . $new_live_port;
				
				
				unset($url[0]);
				foreach ($url as $k => $v)
				{
					$new_url_dir .= '/'.$v;
				}
				
				$new_url = $new_url_host . $new_url_dir;
				
				return $new_url;
			}
		}
	}
?>