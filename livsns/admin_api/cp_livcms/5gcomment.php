<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: 5gcomment.php 4293 2011-07-31 08:05:23Z repheal $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
require_once('cache.class.php');
class gcomment extends LivcmsFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->cache = new cache();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 * 获取文章列表数据
	 */
	public function show()
	{
		//如果存在缓存直接读取缓存数据
		$filename = urldecode($this->input['columnid'])?urldecode($this->input['columnid']):0;
		$output_fields = $this->show_fields();
		$this->setXmlNode('articles' , 'article');
		$limit_img = urldecode($this->input['limitimg']);
		$imgtype = urldecode($this->input['imgtype']);
		$imgtype = $imgtype ? $imgtype : 'small_thumbfile';
		if(!urldecode($this->input['sinceid']) && !$limit_img && ($cache_file = $this->cache->readCache($filename)))
		{
			foreach($cache_file as $key => $value)
			{
				$temp = array();
				foreach($output_fields as $v)
				{
					if(isset($value[$v]))
					{
						$temp[$v] = $value[$v];
					}	
				}
				$this->addItem($temp);
			}
			$this->output();	
		}
		//分页参数设置
		if ($limit_img)
		{
			$where = ' AND cm.indexpic > 0';
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$sql = "SELECT cm.id,cm.contentid, a.loadfile as materialid, a.*,cm.pubdate,ma.*,col.colname, cm.columnid
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "5gcomment a 
						on cm.contentid = a.5gcommentid 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					left join ". DB_PREFIX . "material ma
						on cm.indexpic=ma.materialid
					WHERE cm.modeid=1 and cm.status=3
						and cm.siteid=" . $this->site['siteid'] . $where;
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY cm.pubdate desc';		
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		$cache_data = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$temp = array();
			$row['imgurl'] = $this->getimageurl($row, $imgtype);
			$row['pubdate'] = date('Y-m-d h:i:s',$row['pubdate']);
			$cache_data[] = $row;
			//是否需要显示 row中包含的字段过多
			foreach($output_fields as $v)
			{
				if(isset($row[$v]))
				{
					$temp[$v] = $row[$v];
				}
			}
			$this->addItem($temp);
		}
		$this->cache->buildCache($filename, serialize($cache_data));
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
		//取得大于指定文章ID的列表条件
		$this->input['sinceid'] = intval(urldecode($this->input['sinceid']));
		if($this->input['sinceid'])
		{
			$condition .= ' AND cm.id > '.$this->input['sinceid'];
		}
		return $condition;	
	}
	/**
	 * 获取显示字段
	 */
	public function show_fields()
	{						
		$fields = array('id','columnid','title');
		if($f = urldecode($this->input['show']))
		{
			foreach(explode(',',$f) as $v)
			{
				if(!in_array($v, $fields))
				{
					array_push($fields, $v);
				}
			}
		}
		return $fields;
	}
}

/**
 *  程序入口
 */
$out = new gcomment();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
