<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: thread.php 3989 2011-05-26 01:14:29Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
class items extends LivcmsFrm
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
	 * 获取栏目数据
	 */
	public function show()
	{
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
	
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
						
		$sql = "SELECT cm.id,cm.pubdate,cm.columnid,a.title, a.loadfile as materialid, col.columnid,col.colname
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "article a 
						on cm.contentid = a.articleid 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					WHERE cm.modeid=1 
						and cm.siteid=" . $this->site['siteid'];
		
		//获取查询条件
		$condition = $this->get_condition() . ' ORDER BY cm.pubdate desc';		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);

		$this->setXmlNode('items' , 'item');
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['pubdate'] = date('Y-m-d H:i:s', $row['pubdate']);
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contentmap cm WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		echo json_encode($r);
	}
	
	public function detail()
	{
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		if($id > 0)
		{			
			$sql = "SELECT cm.id,cm.pubdate,cm.columnid,a.*, a.loadfile as materialid, col.columnid,col.colname,ac.content
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "article a 
						on cm.contentid = a.articleid 
					left join " . DB_PREFIX . "article_contentbody ac
						on a.articleid = ac.articleid
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					WHERE cm.id={$id} 
						and cm.modeid=1 
						and cm.status=3
						and cm.siteid=" . $this->site['siteid'];		
			$r = $this->db->query_first($sql);
			
			$r['pubdate'] = date('Y-m-d H:i:s', $r['pubdate']);
			$r['content'] = strip_tags($r['content'], '<br><p>');
			$this->setXmlNode('contents' , 'content');
			
			if(is_array($r) && $r)
			{
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('内容不存在');	
			} 					
		}
		else
		{
			$this->errorOutput('未传入查询ID');		
		} 		
	}
	
	/**
	 * 获取查询条件
	 */
	public function get_condition()
	{
		$condition = '';
		$this->input['columnid'] = urldecode($this->input['columnid']);
		if ($this->input['columnid'])
		{
			$ids = explode(',', $this->input['columnid']);
			$ida = array();
			foreach($ids AS $id)
			{
				if(intval($id))
				{
					$ida[] = $id;
				}
			}
			$sql = 'SELECT colchilds from ' . DB_PREFIX . 'column where columnid IN(' . implode(',', $ida) . ')';
			$q = $this->db->query($sql);
			$childs = array();
			while ($row = $this->db->fetch_array($q))
			{
				$childs[] = $row['colchilds'];
			}
			if ($childs)
			{
				$condition .= ' AND cm.columnid IN (' . implode(',', $childs) . ')';
			}
		}
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new items();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
