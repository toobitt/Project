<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: comment.php 4290 2011-07-31 06:57:49Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class commentApi extends LivcmsFrm
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
	 * 获取评论列表数据
	 */
	public function show()
	{
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY c.pubdate desc';	
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		
		$sql = "SELECT cm.title,  c.* FROM ".DB_PREFIX."comment c LEFT JOIN " . DB_PREFIX . "contentmap cm ON cm.id = c.contentid WHERE 1";
			
		$sql = $sql . $condition . $data_limit;

		$q = $this->db->query($sql);
		$this->setXmlNode('comments' , 'comment');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['pubdate'] = date('Y-m-d H:i:s', $row['pubdate']);
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment c WHERE 1 ";
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
		$siteid = $this->site['siteid'];
		$contentid = $this->input['id']?$this->input['id']:0;
		if(!$contentid)
		{
			$this->errorOutput(OBJECT_NULL);
		}
		/*
		$author = urldecode($this->input['author']) ? urldecode($this->input['author']) : "";
		if($author)
		{
			$condition .= " AND c.author LIKE '%" . $author . "%'";
		}
		$content = urldecode($this->input['content']) ? urldecode($this->input['content']) : "";
		if($content)
		{
			$condition .= " AND c.content LIKE '%" . $content . "%'";
		}*/
		$condition .= " AND c.siteid=" . $siteid . " AND c.contentid = " . $contentid;
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new commentApi();

$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
