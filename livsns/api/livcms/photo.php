<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: article.php 4272 2011-07-31 01:57:11Z zhuld $
***************************************************************************/
define('ROOT_DIR', '../../');
define('CACHE_ENABLE', false);
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
require_once('cache.class.php');
class photoes extends LivcmsFrm
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
		$this->setXmlNode('photoes' , 'photo');
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
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$sql = "SELECT cm.contentid AS id,cm.contentid, p.*,cm.pubdate,ma.*,col.colname, cm.columnid
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "photo p 
						on cm.contentid = p.photoid 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					left join ". DB_PREFIX . "material ma
						on cm.indexpic=ma.materialid
					WHERE cm.modeid=5 and cm.status=3
						and cm.siteid=" . $this->site['siteid'];
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY cm.pubdate desc';		
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		$cache_data = array();
		$temp = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$temp = array();
			$row['cover'] = $this->getimageurl($row, $imgtype);
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
	public function getPictures()
	{
		if(!$this->input['photo_id'])
		{
			return;
		}
		$imgtype = urldecode($this->input['imgtype']);
		$imgtype = $imgtype ? $imgtype : 'small_thumbfile';
		$sql = 'SELECT pic.*,ma.* FROM '.DB_PREFIX.'picture pic LEFT JOIN '.DB_PREFIX.'material ma ON ma.materialid = pic.loadfile WHERE pic.applyid = 6 AND pic.photoid = '.intval(urldecode($this->input['photo_id']));
		//exit($sql);
		$id = intval($this->input['id']);
		if($id)
		{
			if($this->input['pre'])
			{
				$sql .= ' AND pic.pictureid < ' . $id;
			}
			else
			{
				$sql .= ' AND pic.pictureid > ' . $id;
			}
		}
		$sql .= ' ORDER BY pic.pictureid ASC';
		$this->setXMLNode('pictures','pic');
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$r['pic_url'] = $this->getimageurl($r, $imgtype);
			$pics = array('pictureid'=>$r['pictureid'],'title'=>$r['title'],'brief'=>$r['brief'],'pic_url'=>$r['pic_url']);
			$this->addItem($pics);
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
		$fields = array('id','title','brief','cover');
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
$out = new photoes();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
