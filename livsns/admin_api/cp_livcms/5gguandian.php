<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: 5gguandian.php 4294 2011-07-31 08:28:38Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class gguandian extends LivcmsFrm
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
	 * 
	 * 获取观点数据
	 */
	public function show()
	{
		//获取查询条件;
		$condition = $this->get_condition() . " ORDER BY g.liv_orderid DESC";	
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		
		$sql = "SELECT g.*,ma.* FROM ".DB_PREFIX."guandian g
		LEFT JOIN ". DB_PREFIX . "material ma ON g.loadfile = ma.materialid WHERE 1";
			
		$sql = $sql . $condition . $data_limit;

		$q = $this->db->query($sql);
		$imgtype = urldecode($this->input['imgtype']) ? urldecode($this->input['imgtype']) : 'small_thumbfile';
		$this->setXmlNode('guandians' , 'guandian');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['images'] = $this->getimageurl($row, $imgtype);
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "guandian WHERE 1 ";
		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		echo json_encode($r);
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = "";
		$id = $this->input['id']?$this->input['id']:0;
		if(!$id)
		{
			$condition .= " AND guandianid=" . $id;
		}
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new gguandian();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
