<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: vote.php 4296 2011-07-31 09:21:26Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class voteApi extends LivcmsFrm
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
	 * 获取投票数据
	 */
	public function show()
	{
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY v.voteid DESC';
		
		if($this->input['orderid'])
		{
			$condition .= ',v.orderid DESC';
		}
		
		if($this->input['post'])
		{
			$condition .= ',v.post DESC';
		}
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		
		$sql = "SELECT v.*,vs.sortname,ma.* FROM ".DB_PREFIX."vote v 
		LEFT JOIN ".DB_PREFIX."vote_sort vs ON v.sortid = vs.sortid 
		LEFT JOIN ". DB_PREFIX . "material ma ON v.imageid = ma.materialid
		WHERE 1";
			
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
			
		$this->setXmlNode('votes' , 'vote');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			if($row['imageid'])
			{
				$row['images'] = $this->getimageurl($row);
			}
			if($this->input['sortid'] && $this->input['sortid'] == $row['sortid'] && $row['father'])
			{
			//	$num += $row['post'];
			}
			else
			{
				/*$row['total'] = $num;
				$num = 0;*/
				$row['total'] = $row['post'];
			}
			$this->addItem($row);
		}
		$this->output();
	}
	
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vote v WHERE v.father=0 ";
		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		echo json_encode($r);
	}
	
	/**
	 * 获取查询条件
	 * @param $voteid 投票ID
	 * @param $sortid 分类ID
	 * @param $subject 答案或者名称
	 * @param $father 父级ID
	 * @param $votetype 投票类型
	 * @param $orderid 排序顺序
	 * @param $is_display 是否显示	
	 */
	public function get_condition()
	{
		$condition = "";

	
		$voteid = $this->input['voteid'] ? $this->input['voteid'] : 0;
		if($voteid)
		{
			$condition .= " AND v.father=" . $voteid ." OR v.voteid = ".$voteid;
		}
		
		$sortid = $this->input['sortid'] ? $this->input['sortid'] : 0;
		if($sortid)
		{
			$condition .= " AND v.sortid=" . $sortid ." AND v.father = 0";
		}
		
		$subject = urldecode($this->input['subject']) ? urldecode($this->input['urldecode']):"";
		if($subject)
		{
			$condition .= " AND v.subject LIKE '%" . $sortid . "%'";
		}
		
		$votetype = urldecode($this->input['votetype']) ? urldecode($this->input['votetype']):"";
		if($votetype)
		{
			$condition .= " AND v.votetype = '" . $votetype . "'";
		}
	
		$siteid = $this->site['siteid'] ? $this->site['siteid'] : 0;
		if($siteid)
		{
			$condition .= " AND v.siteid=" . $siteid;
		}
	
		$is_display = $this->input['is_display'] ? $this->input['is_display'] : 0;
		if($is_display)
		{
			$condition .= " AND v.is_display=" . $is_display;
		}
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new voteApi();

$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>