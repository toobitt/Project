<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: livcms_frm.php 4623 2011-09-30 01:06:07Z develop_tong $
***************************************************************************/

/**
 * 程序基类
 * @author develop_tong
 *
 */
abstract class LivcmsFrm extends BaseFrm
{
	var $site = array();
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	protected function verifyToken()
	{
		$token = trim($this->input['token']);
		
		if (!$token)
		{
			$this->errorOutput(TOKEN_ERROR);		
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'siteconf WHERE token=\'' . $token . "'";
		$this->site = $this->db->query_first($sql);
		if (!$this->site)
		{
			$this->errorOutput(TOKEN_ERROR);
		}

	}
	
	/**
	 * 获取图片路径
	 */ 
	function getimageurl($imginfo, $imgtype = 'thumbfile')
	{
		if (!$imginfo[$imgtype])
		{
			return '';
		}
		if (!$imginfo['imgdomain'])
		{
			$imgdomain = $this->site['sitepath'];
			$imginfo['filepath'] = substr($imginfo['filepath'], 1) ;
		}
		else
		{
			$imgdomain = $imginfo['imgdomain'];
			if ($imginfo['iscache'])
			{
				$imgdomain .= $imginfo['host'] . '/' . $imginfo['width'] . '/' . $imginfo['height'] . '/';
				$imgtype = 'filename';
			}
			$imginfo['filepath'] = substr($imginfo['filepath'], 14); 
		}
		return $imgdomain . str_replace('//', '/', $imginfo['filepath']. '/' . $imginfo[$imgtype]);
	}	
}

function html_standardization($html) 
{
	if(!function_exists('tidy_repair_string'))
	{
		return $html; 
	}

	$str = tidy_repair_string($html, array('output-xhtml'=>true), 'utf8'); 
	if (!$str)
	{
		return $html; 
	}
	$str = tidy_parse_string($str, array('output-xhtml'=>true), 'utf8'); 
	$standard_html = '';

	$nodes = @tidy_get_body($str)->child;

	if(!is_array($nodes))
	{
		$returnVal = 0; 
		return $html; 
	}

	foreach($nodes AS $n)
	{ 
		$standard_html .= $n->value; 
	} 
	return $standard_html; 
}