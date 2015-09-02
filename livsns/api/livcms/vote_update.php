<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: vote_update.php 4297 2011-07-31 09:37:47Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class voteUpdateApi extends LivcmsFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 投票
	 * @param $voteid 投票ID
	 * return $post
	 */
	public function update()
	{	
		$voteid = $this->input['voteid']? $this->input['voteid']:0;
		$father = $this->input['father']? $this->input['father']:0;
		$voteid = array_filter(explode(',',$voteid));
		if(!$voteid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		$sql = "UPDATE " . DB_PREFIX . "vote SET post = post + 1 WHERE voteid IN( " . implode(',', $voteid) . ')';
		$this->db->query($sql);
		if ($father)
		{
			$sql = "SELECT post,father FROM " . DB_PREFIX . "vote WHERE voteid IN( " . implode(',', $voteid) . ')';
			$r = $this->db->query_first($sql);
			$sql = "UPDATE " . DB_PREFIX . "vote SET post = post + 1 WHERE voteid = " . $r['father'];
			$this->db->query($sql);
		}
		$this->setXmlNode('votes' , 'vote');
		$this->addItem($r);
		$this->output();
	}
}

/**
 *  程序入口
 */
$out = new voteUpdateApi();

$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'update';
}
$out->$action();

?>
