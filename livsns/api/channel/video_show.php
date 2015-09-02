<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: video_show.php 3939 2011-05-20 02:04:05Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 功能：视频数据获取API
 * 
 * 提供的方法：
 * 1)批量获取视频数据
 * 2)获取视频总数
 * 3)获取单条视频数据
 * 
 * @author chengqing 
 *
 */
class videoShowApi extends BaseFrm
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
	 * 视频数据获取默认方法
	 */
	public function show()
	{
		
		//分页参数设置
		$page = $this->input['page'] ? $this->input['page'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$offset = $page * $count;
						
		$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('video_info' , 'video');
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:d' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:d' , $row['update_time']);
			$this->addItem($row);	
		}
		
		$this->output();			
	}
	
	/**
	 * 获取视频总数
	 * 默认为全部视频的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "video WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);	
		$total_nums = $r['total_nums'];		
		$this->setXmlNode('video_info' , 'video_count');
		$this->addItem($total_nums);	
		$this->output();
	}
		
	/**
	 * 获取单条数据
	 */
	public function detail()
	{
		//视频ID
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		if($id > 0)
		{			
			$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE id = " . $id;		
			$r = $this->db->query_first($sql);
			$this->setXmlNode('video_info' , 'video');
			
			if(is_array($r) && $r)
			{
				$r['create_time'] = date('Y-m-d H:i:d' , $r['create_time']);
				$r['update_time'] = date('Y-m-d H:i:d' , $r['update_time']);
				$this->addItem($r);
				$this->output();
			}
			else
			{
				$this->errorOutput('视频不存在');	
			} 				
		}
		else
		{
			$this->errorOutput('未传入查询ID');		
		} 	
	}

	/**
	 * 获取搜索条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['keywords'])
		{
			$condition .= " AND title LIKE '%" . trim($this->input['keywords']) . "%' ";
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND create_time < " . strtotime($this->input['end_time']);	
		}
		
		//查询视频的类型
		if($this->input['type'])
		{
			$condition .= " AND is_show = " . intval($this->input['type']) . " AND state = 1 ";	
		}
		else
		{
			$condition .= " AND state = 1 ";	
		} 
		
		//查询排序类型(字段)
		$order = $this->input['order_field'] ? $this->input['order_field'] : 'create_time'; 
		switch($order)
		{
			case 'create_time' : $condition .= " ORDER BY " . $order;break;
			default:$condition .= " ORDER BY " . $order;	
		}
		
		//查询排序方式(升序或降序)
		$condition .= $this->input['order_type'] ? $this->input['order_type'] : ' DESC ';
		
		return $condition;
	}
}

/**
 *  程序入口
 */
$out = new videoShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>