<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: shorturl.php 28455 2013-09-03 02:15:18Z tong $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR . 'global.php');
define('MOD_UNIQUEID', 'shorturl'); //模块标识

class shorturlApi extends appCommonFrm
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function shorturl()
	{
		if(IS_SHORTURL)
		{
		
			//匹配出字符串中所有的 超链接
			$this->input['str'] = str_replace('&amp;', '&', $this->input['str']);
			preg_match_all("((((f|ht){1}tp|ftp|gopher|news|telnet|rtsp|mms)://|www\.)[-a-zA-Z0-9@:%_\+.~#?&//=]+)" ,$this->input['str'],$matches);
			$patterns = array();
			
			foreach($matches[0] as $key => $value)
			{	
				$patterns[] ='#'.str_replace(array(".","?","#","&","'"),array('\.','\?','\#', '\&'),$value).'#';
				$replacements[] =$this->generateurl($value,'');
			}
			$a = preg_replace($patterns, $replacements, $this->input['str']);
			echo $a;	
		}
		else
		{
			echo $this->input['str'] = str_replace('&amp;', '&', $this->input['str']);
		}
	}
	
	private function generateurl($url,$alias)
	{
		//保留原有的$url;
		$lasturl = $url;
		//对用户输入的url进行处理
		$url = trim($url);
		if(!$url)
		{
			//没有网址，返回空
			return;
		}
		else
		{
			if (strlen($url) < (strlen(SITE_URL) + 6))
			{
				return $url;
			}
			if (!preg_match("/^(".URL_PROTOCOLS.")\:\/\//i", $url))
		 	{
		        $url = "http://".$url;
		    }
		 	$last = $url[strlen($url) - 1];
		    if ($last == "/")
		    {
		        $url = substr($url, 0, -1);
		    }
		}
	    //验证url合法性
	 	
	    //解析url为数组
    	$data = @parse_url($url);
		if (!$data['scheme'] || !$data['host']) 
		{
	      	//网址没有效，返回空;
	      	return;
	    }
	    else
	    {
	        $stieurl = parse_url(SITE_URL);
	        if (preg_match("/(".$stieurl['host'].")/i", $data['host']))
	        {
	            //网址为短网址的网址，原网址返回
	            return $lasturl;
	            
	        }
	    }
		
	    //自定义码验证
		if (strlen($alias) > 0) 
		{
			$check = $this->db->query_first("SELECT id FROM " . DB_PREFIX . "urls WHERE alias='$alias' or code='$alias'");
			if (!preg_match("/^[a-zA-Z0-9_-]+$/", $alias))
	        {
	            //自定义别名只能包含字母，数字，下划线和破折号,原网址返回;
	            return $lasturl;
	        }
	        else if ($check) 
	        {
	            //您输入的自定义别名已经存在，原网址返回
	            return $lasturl;
	        }
	    }
		
	    //url在数据库中是否存在
	    $create = false;
	 	$check = $this->db->query_first("SELECT code,url FROM " . DB_PREFIX . "urls WHERE url='$url'");
	    $url_data =$check['url'];
	    if (!$check['url'])
	    {
	        $create = true;
	    }
	    else if ($check['url'] && $alias)
	    {
	       	$create = true;
	       	$code = $alias;	    
	    }
	    else if ($check['url'] && !$alias)
	    {
	    	$code = $check['code'];
	    }
		
		 //创建短网址
		 if ($create) 
		 {
	         do
	         {
	             $get_last_number = $this->db->query_first("SELECT last_number FROM ".DB_PREFIX."urlset");
	         	 $code =$this->generate_code($get_last_number['last_number']);
	         	 if(trim($code))
	         	 { 
	         	 	$code_exists = $this->db->query_first("SELECT id FROM ".DB_PREFIX."urls WHERE  code = '$code' OR alias = '$code'");
	         	 } 
	         	 $increase_last_numbe=$this->db->query("UPDATE ".DB_PREFIX."urlset SET last_number = (last_number + 1)");
	             if (!$increase_last_numbe)
	             {
	                 //系统出错,原网址返回;
	                 return $lasturl;
	             }
	
	             if ($code_exists['id']) 
	             {
	                 continue;
	             }
	             break;
	         } 
	         while (1);
	         	$this->db->query("INSERT INTO ".DB_PREFIX."urls (url, code, alias, date_added) VALUES ('$url', '$code', '$alias', NOW())");
	       }
		
		if($alias)
		{
			$html = SITE_URL."/".$alias;
		}
		else 
		{
			$html = SITE_URL."/".$code;
		}
		return  $html;
	}
	private function generate_code($number)
	{
	    $out   = "";
	    $codes = "abcdefghjkmnpqrstuvwxyz23456789ABCDEFGHJKMNPQRSTUVWXYZ";
	    while ($number > 53) 
	    {
	        $key    = $number % 54;
	        $number = floor($number / 54) - 1;
	        $out    = $codes{$key}.$out;
	    }
	    return $codes{$number}.$out;
	}
}
$out = new shorturlApi();
$out->shorturl();
?>