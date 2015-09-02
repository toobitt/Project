<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: lineChannel_show.php 3940 2011-05-20 05:25:23Z chengqing $
***************************************************************************/

define('ROOT_DIR', '../../');
require(ROOT_DIR . 'global.php');

/**
 * 
 * 网台数据获取API 
 * 
 * 提供的方法：
 * 1)批量获取频道数据
 * 2)获取单个频道数据
 * 3)获取频道总数
 * @author chengqing
 *
 */
class lineChannelShowApi extends BaseFrm
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
	 * 频道数据获取默认执行的方法
	 */
	public function show()
	{
		//分页参数设置
		$page = $this->input['page'] ? $this->input['page'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$offset = $page * $count;
						
		$sql = "SELECT * FROM " . DB_PREFIX . "network_station WHERE 1 ";
		
		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('channel_info' , 'channel');
		while($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:d' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:d' , $row['update_time']);
			
			//如果存在LOGO
			if($row['logo'])
			{
				$row['logo_url'] = UPLOAD_URL . LOGO_DIR . ceil($row['user_id']/NUM_IMG) . "/" . $row['logo'];	
			}
			else//调用默认LOGO
			{
				$row['logo_url'] = UPLOAD_URL . LOGO_DIR . "/0.gif";	
			} 
			$this->addItem($row);	
		}		
		$this->output();		
	}
	
	/**
	 * 获取频道总数
	 * 默认为全部频道的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total_nums FROM " . DB_PREFIX . "network_station WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);	
		$total_nums = $r['total_nums'];		
		$this->setXmlNode('channel_info' , 'channel_count');
		$this->addItem($total_nums);	
		$this->output();
	}
	
	/**
	 * 获取单条频道数据
	 */
	public function detail()
	{
		//视频ID
		$id = $this->input['id'] ? intval($this->input['id']) : -1;
		if($id > 0)
		{			
			$sql = "SELECT * FROM " . DB_PREFIX . "network_station WHERE id = " . $id;		
			$r = $this->db->query_first($sql);
			$this->setXmlNode('channel_info' , 'channel');
			
			if(is_array($r) && $r)
			{
				$r['create_time'] = date('Y-m-d H:i:d' , $r['create_time']);
				$r['update_time'] = date('Y-m-d H:i:d' , $r['update_time']);
				
				//如果存在LOGO
				if($r['logo'])
				{
					$r['logo_url'] = UPLOAD_URL . LOGO_DIR . ceil($r['user_id']/NUM_IMG) . "/" . $r['logo'];	
				}
				else//调用默认LOGO
				{
					$r['logo_url'] = UPLOAD_URL . LOGO_DIR . "/0.gif";	
				}
				 
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
	 * 获取查询条件
	 */
	private function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['keywords'])
		{
			$condition .= " AND web_station_name LIKE '%" . trim($this->input['keywords']) . "%' ";
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
		if($this->input['state'])
		{
			$condition .= " AND state = " . intval($this->input['state']);	
		}
		
		//查询排序类型(字段，默认为创建时间)
		$order = $this->input['order_field'] ? $this->input['order_field'] : 'create_time'; 
		switch($order)
		{
			case 'create_time' : $condition .= " ORDER BY " . $order;break;
			default:$condition .= " ORDER BY " . $order;	
		}
		
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['order_type'] ? $this->input['order_type'] : ' DESC ';
		
		return $condition;	
	}		
}

/**
 *  程序入口
 */
$out = new lineChannelShowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

