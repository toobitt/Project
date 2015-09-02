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
class column extends LivcmsFrm
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
		$count = $this->input['count'] ? intval($this->input['count']) : 2000;
	
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
						
		$fields = 'columnid AS id,colname AS name,fatherid AS fid,content AS brief,coldepth AS depth, colparents AS parents, colchilds AS childs, coltag';
		$sql = "SELECT $fields, imageid 
				FROM ".DB_PREFIX."column WHERE siteid=" . $this->site['siteid'] . '';
		
		//获取查询条件
		$condition = $this->get_condition() . ' ORDER BY orderid DESC';		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);

		$this->setXmlNode('columns' , 'column');
		$which_column_img = $this->input['imgtype'] ? $this->input['imgtype'] : 'notactive_pic';
		/*
		
			$result_icon[$row['column_id']]['icon_'.$row['client']]['default'] = empty($i_d)?'':$i_d;
			$result_icon[$row['column_id']]['icon_'.$row['client']]['activation'] = empty($a)?'':$a;
			$result_icon[$row['column_id']]['icon_'.$row['client']]['no_activation'] = empty($n_a)?'':$n_a;
		*/
		while(false !== ($row = $this->db->fetch_array($q)))
		{
			$imageids = unserialize($row['imageid']);
			unset($row['imageid']);
			if ($imageids[$which_column_img][1])
			{
				$show_column_img = $imageids[$which_column_img][1];
			}
			elseif ($imageids['notactive_pic'][1])
			{
				$show_column_img = $imageids['notactive_pic'][1];
			}
			else
			{
				$show_column_img = '';
			}
			$mt = preg_match('/^\/liv_loadfile\/(.*?)\/([0-9_]+\.([a-zA-Z]+))/is',$show_column_img,$out);
			if ($mt)
			{
				$img = array(
					'host' => CMS_IMG_DOMAIN,	
					'dir' => '',	
					'filepath' => $out[1],	
					'filename' => $out[2],	
				);
			}
			$row['icon_1']['default'] = $img;
			$length = count(explode(',', $row['childs']));
			unset($row['childs']);
			if ($length == 1)
			{
				$row['is_last'] = 1;
			}
			else
			{
				$row['is_last'] = 0;
			}
			$this->addItem($row);
		}
		
		$this->output();
	}
	
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "column WHERE 1 ";

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
			$sql = "SELECT * FROM ".DB_PREFIX."column WHERE columnid = " . $id;		
			$r = $this->db->query_first($sql);
			$this->setXmlNode('columns' , 'column');
			
			if(is_array($r) && $r)
			{
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('栏目不存在');	
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
			$condition .= ' AND columnid IN(' . implode(',', $ida) . ')';
		}
		else
		{
			$father_id = $this->input['fid'] ? intval($this->input['fid']) : -1;	
			if ($father_id)
			{
				$condition .= ' AND fatherid=' . $father_id;
			}
		}
		return $condition;	
	}
}

/**
 *  程序入口
 */
$out = new column();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
