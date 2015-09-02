<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
�˽ӿ���ϳ�
*
* $Id:$  
***************************************************************************/
define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');
require('lib/tuji_functions.php');
require_once(ROOT_DIR.'lib/class/gdimage.php');
define('MOD_UNIQUEID','tuji');
class pics extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		$this->gd = new GDImage();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	/**
	 * 获取图片列表显示
	 */
	function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = 'SELECT p.*,t.title as tuji_title,t.comment,t.path as tuji_path,s.sort_name FROM '.DB_PREFIX.'pics p LEFT JOIN '
		.DB_PREFIX.'tuji t ON p.tuji_id = t.id LEFT JOIN '
		.DB_PREFIX.'tuji_sort s ON s.id = t.tuji_sort_id WHERE 1 '.$condition.' ORDER BY create_time DESC '.$limit;
		$this->setXmlNode('pics', 'pic');
		//print_r($this->db->fetch_all($sql));
		$q  = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i:s',$r['update_time']);
			$r['pic_url'] = UPLOAD_ABSOLUTE_URL.hg_num2dir($r['tuji_id']).$r['new_name'];
			$r['description'] = $r['description']?$r['description']:(!DESCRIPTION_TYPE?$r['comment']:'');
			unset($r['comment']);
			if($this->input['width'] || $this->input['height'])
			{
				$subdir  = hg_num2dir($r['tuji_id']).intval($this->input['width']).intval($this->input['height']).'/';
				$thumb_dir = UPLOAD_THUMB_DIR.$subdir;
				if(!is_dir($thumb_dir))
				{
					@hg_mkdir($thumb_dir);
				}
				$thumb_file = $this->settings['thumb']['prefix'].$r['new_name'];
				$this->gd->init_setting($r['pic_url'],$thumb_dir.$thumb_file);
				if($location = $this->gd->makeThumb(1))
				{
					$r['thumb_url'] = UPLOAD_THUMB_URL.$subdir.$thumb_file;
				}
				//$r['thumb_url'] = $thumb_dir;
			}
			
			$this->addItem($r);
		}
		$this->output();		
	}
	function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.'pics p WHERE 1 '.$this->get_condition();
		echo json_decode($this->db->query_first($sql));
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= 'AND p.id = '.intval(urldecode($this->input['id'])).',';
		}
		if($this->input['tid'])
		{
			$condition .= 'AND p.tuji_id = '.intval(urldecode($this->input['tid'])).',';
		}
		if($this->input['desc'])
		{
			$condition .= 'AND p.desc = "%'.urldecode($this->input['desc']).'%",';
		}
		
		if($this->input['visit'])
		{
			$condition .= 'AND p.total_visit > '.urldecode($this->input['visit']).',';
		}
		if($this->input['width'])
		{
			$this->gd->maxWidth = intval($this->input['width']);
		}
		if($this->input['height'])
		{
			$this->gd->maxHeight = intval($this->input['height']);
		}
		$condition = rtrim($condition, ',').' AND p.status = 0';
		return $condition;
	}
	/**
	 * 获取图片详细信息
	 */
	function detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$this->show();
	}	
}
$out = new pics();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>