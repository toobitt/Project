<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require_once('core/tuji.dat.php');
define('MOD_UNIQUEID','tuji');
class tuji extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->tuji = new tuji_data();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 输出单个或者多个图集内容
	 */
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$this->setXmlNode('tuji','item');
		$tuji_info = $this->tuji->tuji_info($this->get_condition(),'',$offset,$count);
		foreach ($tuji_info as $k=>$r)
		{
			$columns = unserialize($r['column_id']);
			if($columns)
			{
				$columnurls = unserialize($r['column_url']);
				$r['column'] = array();
				foreach ($columns as $column_id => $value) 
				{
					$r['column'][] = array(
						'column_id' => $column_id,
						'name' => $value,
						'url' => $columnurls[$column_id]	
					);
				}
			}

			$r['cover_url'] = unserialize($r['cover_url']);
			//unset($r['column_id'], $r['column_url']);
			$this->addItem($r);
		}
		$this->output();
	}
	/**
	 * 
	 * 获取查询条件方法 ...
	 */
	function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND t.id = '.urldecode(intval($this->input['id']));
		}
		if($this->input['sort_id'])
		{
			$condition .= ' AND t.tuji_sort_id = '.urldecode(intval($this->input['sort_id']));
		}
		if($this->input['title'])
		{
			$condition .= ' AND t.title LIKE "%'.urldecode($this->input['title']).'%"';
		}
		
		if($this->input['user_name'])
		{
			$condition .= " AND t.user_name = '" . $this->input['user_name'] . "' ";
		}
		
		if($this->input['user_id'])
		{
			$condition .= " AND t.user_id = '" . $this->input['user_id'] . "' ";
		}
		/*数据库无此字段暂时注释掉,等待进一步确认
		if($this->input['desc'])
		{
			$condition .= ' AND t.desc LIKE "%'.urldecode($this->input['desc']).'%"';
		}
		*/
		//时间格式1970-01-01
		if($this->input['time'])
		{
			$condition .= ' AND t.create_time > '.urldecode(intval(strtotime($this->input['time'])));
		}
		
		if($this->input['visit'])
		{
			$condition .= ' AND t.total_visit > '.urldecode(intval($this->input['visit']));
		}
		if($this->input['comment'])
		{
			$condition .= ' AND t.total_comment > '.urldecode(intval($this->input['comment']));
		}
		
		$condition .= ' AND t.status = 1';
		return rtrim($condition,',');
	}
	/**
	 * 统计图集数
	 */
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."tuji t WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	/**
	 * 输出单个图集
	 */
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->show();
	}
	/**
	 * 
	 * 图集分类输出,此方法是否有用,待定.
	 */
	function tuji_sort()
	{
		$sql = "SELECT id,sort_name FROM ".DB_PREFIX.'tuji_sort ORDER BY order_id DESC';
		//exit($sql);
		$q = $this->db->query($sql);
		$this->setXmlNode('sorts','item');
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function get_tuji_pics()
	{
		if(!$this->input['tuji_id'])
		{
			$this->errorOutput(NOID);
		}
		if(!(DESCRIPTION_TYPE))
		{
			$tuji_info = $this->tuji->tuji_description($this->input['tuji_id']);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."pics WHERE tuji_id = '".intval($this->input['tuji_id'])."' ORDER BY order_id ASC";
		$q = $this->db->query($sql);
		$ret = array();
		while($r = $this->db->fetch_array($q))
		{
			$r['description'] = $r['description']?$r['description']:(!DESCRIPTION_TYPE?$tuji_info[$r['tuji_id']]['description']:'');
			$r['img_info'] = unserialize($r['img_info']);
			$this->addItem($r);
		}
		$this->output();
	}
}
$out = new tuji();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'show';
}
$out->$action();


?>