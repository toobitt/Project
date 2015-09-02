<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: $
***************************************************************************/
define('ROOT_DIR', '../'); 
require('./global.php'); 
require(ROOT_PATH . 'lib/class/status.class.php');

class autoget extends uiBaseFrm
{	
	var $gSoapConfig; 
	function __construct()
	{
		global $gSoapConfig;
		parent::__construct();
		$this->status = new status();
		$this->gSoapConfig = $gSoapConfig;
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		global  $gGlobalConfig;
		include_once ROOT_PATH.'lib/class/shorturl.class.php';
		$shorturl = new shorturl();
		$last_id = @file_get_contents(CACHE_DIR . 'autoget.txt');
		echo $last_id = intval($last_id);
		$statusline = $this->status->public_timeline($page, 30, $last_id, 1);
		if ($statusline)
		{
			krsort($statusline);
			$xml_soap = '<?xml version="1.0" encoding="utf-8"?> <contentlist>';
			$pattern = "((((f|ht){1}tp|ftp|gopher|news|telnet|rtsp|mms)://|www\.)[-a-zA-Z0-9@:%_\+.~#?&//=]+)";
			foreach ($statusline AS $k => $blog)
			{
				if (in_array($blog['user']['id'], array(1,6687)))
				{
					continue;
				}
				$blog['text'] = strip_tags($blog['text']);

				$blog['text']  = preg_replace("/<script/i", "&#60;script", $blog['text'] );

				$pregfind = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
				$pregreplace = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', '', '&#036;', '');
				$val = str_replace($pregfind, $pregreplace, $blog['text'] );

				$blog['text']  = preg_replace('/\\\(&amp;#|\?#)/', '&#092;', $blog['text'] );
				$blog['text'] = preg_replace("/(\:.*[0-9]*\:)/Ui",'',$blog['text']);
				$blog['text'] = preg_replace($pattern,'',$blog['text']);
				$blog['text'] = trim($blog['text']);
				$text = preg_replace("/(#[\x{4e00}-\x{9fa5}0-9A-Za-z_-\s‘’“”'\"!\?$%&:;！？￥×\*\<\>》《]+[\s#])/iu",'',$blog['text']);
				if (!$blog['text'] || !trim($text))
				{
					continue;
				}
				if ($blog['medias'][0]['ori'])
				{
					if ($blog['text'] == '分享图片')
					{
						continue;
					}
				}
				else
				{
					$images = '';
				}
				$last_id = $blog['id'];
				if ($gGlobalConfig['rewrite'])
				{
					$link = $this->settings['mblog_url'] . 'status-' . $blog['id'] .'.html';
				}
				else 
				{
					$link = $this->settings['mblog_url'] . 'show.php?id=' . $blog['id'];
				}
				$link = $shorturl->shorturl($link);
				$xml_soap .= '<content> 
					<columnid>24</columnid>
					<tcontentid>'.$blog['id'].'</tcontentid>
					<title><![CDATA['.$blog['text'].']]></title>
					<tname><![CDATA['.$blog['user']['username'].']]></tname>
					<user_id>'.$blog['user']['id'].'</user_id>
					<images><![CDATA[' . $images . ']]></images>
					<content><![CDATA['.$blog['text'].']]></content>
					<liv_outlink><![CDATA['.$link.']]></liv_outlink>
					</content>';
			}
			$xml_soap .= '</contentlist>';		
			ini_set("soap.wsdl_cache_enabled", "0"); 
			$xml_soap = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/','',$xml_soap);
			$objSoapClient = new SoapClient($this->gSoapConfig['wsdl_url']);
			$xml_soap = $objSoapClient->import($xml_soap,$this->gSoapConfig['u'],$this->gSoapConfig['p']);
			if ($xml_soap)
			{
				file_put_contents(CACHE_DIR . 'autoget.txt', $last_id);
			}
		}
	}
	
}

$out = new autoget();
$action = $_INPUT['a'];

if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();