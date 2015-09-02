<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: article.php 4808 2011-10-18 00:50:25Z develop_tong $
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('livcms_frm.php');
require_once('cache.class.php');
class articles extends LivcmsFrm
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
		$liv_proc = array(
			'android' => 'httplive://'	
		);
		$filename = urldecode($this->input['column_id'])?urldecode($this->input['column_id']):0;
		$output_fields = $this->show_fields();
		$this->setXmlNode('articles' , 'article');
		$imgtype = urldecode($this->input['imgtype']);
		$modeid = intval($this->input['modeid']);
		$modeid = $modeid ? $modeid : 1;
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;	
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$filename .= '_' . $modeid . '_' . $offset;
		$imgtype = $imgtype ? $imgtype : 'small_thumbfile';
		if(!$offset && !intval($this->input['sinceid']) && !intval($this->input['oldestid']) && !$limit_img && ($cache_file = $this->cache->readCache($filename)))
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

		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$sql = "SELECT cm.id,cm.contentid, a.loadfile as materialid, a.*,cm.pubdate,ma.*,col.colname,col.imageid, cm.columnid
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "article a 
						on cm.contentid = a.articleid 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					left join ". DB_PREFIX . "material ma
						on a.loadfile=ma.materialid
					WHERE cm.modeid={$modeid} and cm.status=3
						and cm.siteid=" . $this->site['siteid'] . $where;
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY cm.istop DESC, cm.pubdate desc';		
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		$artlists =  array();
		$vids = array();
		$which_column_img = 'active_pic';
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$temp = array();
			$imageids = unserialize($row['imageid']);
			unset($row['imageid']);
			if ($imageids[$which_column_img])
			{
				$show_column_img = $this->site['weburl'] . $imageids[$which_column_img][1];
			}
			else
			{
				$show_column_img = '';
			}
			$row['colimg'] = $show_column_img;
			$row['indexpic'] = $this->getimageurl($row, $imgtype);
			$row['pubdate'] = date('Y-m-d h:i:s',$row['pubdate']);
			if ($row['videoid'])
			{
				$vids[] = $row['videoid'];
				$row['is_have_video'] = 1;
			}
			else
			{
				$row['is_have_video'] = 0;
			}
			//是否需要显示 row中包含的字段过多
			foreach($output_fields as $v)
			{
				if(isset($row[$v]))
				{
					$temp[$v] = $row[$v];
				}
			}
			$artlists[] = $temp;
		}
		if($this->input['sinceid'])
		{
			krsort($artlists);
		}
		$vodinfos = array();
		if ($vids && 0)
		{
			include_once(ROOT_PATH.'lib/class/curl.class.php');
			$curl = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir']);
			$curl->initPostData();
			foreach ($vids AS $k => $vid)
			{
				$curl->addRequestData('id[' . $k . ']', $vid);
			}
			$video_info = $curl->request('vod.php');

			foreach($video_info AS $v)
			{
				$vodinfos[$v['id']] = $v;
			}
		}
		$cache_data = array();
		foreach($artlists AS $v)
		{
			//$v['vodid'] = $vodinfos[$v['vid']]['vodid'];
			//$v['vodurl'] = $vodinfos[$v['vid']]['vodurl'];
			$v['bundle_id'] = 'news';
			$v['module_id'] = 'news';
			$v['struct_id'] = 'article';
			$v['content_fromid'] = $v['id'];
			$v['outlink'] = $v['liv_outlink'];
			unset($v['liv_outlink']);
			$cache_data[] = $v;
			$this->addItem($v);
		}

		$this->cache->buildCache($filename, serialize($cache_data));
		$this->output();
	}
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contentmap cm WHERE cm.modeid={$modeid} and cm.status=3
						and cm.siteid=" . $this->site['siteid'];

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
		$this->input['columnid'] = urldecode($this->input['column_id']);
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
		$oldestid = intval($this->input['oldestid']);		
		if($oldestid)
		{
			$condition .= ' AND cm.id < '.$oldestid;
		}
		$limit_img = urldecode($this->input['limitimg']);
		if ($limit_img)
		{
			$condition .= ' AND cm.indexpic > 0';
		}
		return $condition;	
	}
	/**
	 * 获取显示字段
	 */
	public function show_fields()
	{						
		$fields = array('id','columnid','title','subtitle', 'indexpic', 'brief', 'liv_outlink', 'is_have_video');
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
$out = new articles();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
