<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', './../../');
define('MOD_UNIQUEID','adv');//模块标识
require(ROOT_DIR . 'global.php');
class valid_click extends InitFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	//广告统计的详细信息
	function doclick()
	{
		$pubid = intval($this->input['pubid']);
		$url = urldecode($this->input['url']);
		if(!$url)
		{
			$sql = 'SELECT c.link FROM '.DB_PREFIX.'advpub a LEFT JOIN '.DB_PREFIX.'advcontent c ON a.ad_id = c.id WHERE a.id = '.$pubid;
			$ad = $this->db->query_first($sql);
			$url = $ad['link'];
		}
		$data = array(
		'create_time'=>TIMENOW,
		'ip'=>hg_getip(),
		'reffer'=>$this->input['reffer'] ? $this->input['reffer'] : $_SERVER['HTTP_REFERER'], 
		//'reffer'=>$this->input['reffer'],
		'pubid'=>$pubid,
		);
		if(!$data['pubid'] || !$url)
		{
			exit("Invalid AD...");
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'advcount SET'.
		' create_time = "'.$data['create_time'].'"'.
		', ip = "'.$data['ip'].'"'.
		', reffer = "'.addslashes($data['reffer']).'"'.
		', pubid = "'.$data['pubid'].'"';
		$this->db->query($sql);

		$sql = 'UPDATE '.DB_PREFIX.'statistics SET click=click+1 WHERE pubid='.intval($data['pubid']);
		$this->db->query($sql);
		ob_clean();
		header('location:'.$url);
		exit;
	}
	public function verifyToken()
	{
		
	}
}
$obj = new valid_click();
$obj->doclick();
?>