<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: base_frm.php 3959 2011-05-25 02:47:50Z develop_tong $
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
}