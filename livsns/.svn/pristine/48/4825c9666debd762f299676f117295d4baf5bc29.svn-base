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
		$filename = 'vod_' . urldecode($this->input['columnid'])?urldecode($this->input['columnid']):0;
		$output_fields = $this->show_fields();
		$this->setXmlNode('vods' , 'vod');
		$limit_img = urldecode($this->input['limitimg']);
		$imgtype = urldecode($this->input['imgtype']);
		$imgtype = $imgtype ? $imgtype : 'small_thumbfile';
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;	
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
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;		
		$sql = "SELECT cm.id,cm.contentid, a.loadfile as materialid, a.*,cm.pubdate,ma.*,col.colname, cm.columnid
				FROM ".DB_PREFIX."contentmap cm 
					left join " . DB_PREFIX . "vod a 
						on cm.contentid = a.vodid 
					left join " . DB_PREFIX . "column col
						on cm.columnid=col.columnid
					left join ". DB_PREFIX . "material ma
						on a.loadfile=ma.materialid
					WHERE cm.modeid=5 and cm.status=3
						and cm.siteid=" . $this->site['siteid'] . $where;
		//获取查询条件;
		$condition = $this->get_condition() . ' ORDER BY cm.istop DESC, cm.pubdate desc';		
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		$artlists = array();
		$vids = array();
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$row['imgurl'] = $this->getimageurl($row, $imgtype);
			$row['pubdate'] = date('Y-m-d h:i:s',$row['pubdate']);
			if ($row['starttime'])
			{
				$row['starttime'] = date('Y-m-d',$row['starttime']);
			}
			else
			{
				$row['starttime'] = '';
			}
			if ($row['vid'])
			{
				$vids[] = $row['vid'];
				$row['is_video'] = 1;
			}
			else
			{
				$row['is_video'] = 0;
			}
			$row['brief'] = hg_cutchars($row['comment'], 38, '');
			$artlists[] = $row;
		}
		if($this->input['sinceid'])
		{
			arsort($artlists);
		}
		$vodinfos = array();
		if ($vids)
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
			$v['vodid'] = $vodinfos[$v['vid']]['vodid'];
			$v['vodurl'] = $vodinfos[$v['vid']]['vodurl'];
			//是否需要显示 row中包含的字段过多
			$temp = array();
			foreach($output_fields as $vv)
			{
				if(isset($v[$vv]))
				{
					$temp[$vv] = $v[$vv];
				}
			}
			$cache_data[] = $temp;
			$this->addItem($temp);
		}
		$this->cache->buildCache($filename, serialize($cache_data));
		$this->output();
	}
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contentmap cm WHERE cm.modeid=5 and cm.status=3 and cm.siteid=" . $this->site['siteid'];

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
$out = new articles();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
