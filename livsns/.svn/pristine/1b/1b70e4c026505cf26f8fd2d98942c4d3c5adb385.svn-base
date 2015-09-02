<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: line_channel.php 6766 2012-05-17 09:39:51Z hanwenbin $
***************************************************************************/

require_once './global.php';
define('MOD_UNIQUEID', 'channel_m'); //模块标识

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
class lineChannelShowApi extends outerReadBase
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
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
						
		
		$orders = array('collect_count', 'comment_count', 'click_count');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY n.' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		$sql = "SELECT n.* , u.username FROM " . DB_PREFIX . "network_station AS n LEFT JOIN " . DB_PREFIX . "user AS u ON n.user_id = u.id WHERE 1 " . $orderby;
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		//获取查询条件
		$condition = $this->get_condition();
		
		$sql = $sql . $condition . $data_limit;
		$q = $this->db->query($sql);
		
		$this->setXmlNode('channel_info' , 'channel');
		while($row = $this->db->fetch_array($q))
		{
			
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			
			if($this->settings['rewrite'])
			{
				$row['link'] = SNS_VIDEO . "station-" . $row['id'] .".html";	
			}
			else 
			{
				$row['link'] = SNS_VIDEO."station.play.php?sta_id=".$row['id'];
			}
			switch ($row['state'])
			{
				case 0:
					$row['audit'] = 0;
					break;
				case 1:
					$row['audit'] = 1;
					break;
				case 2:
					$row['audit'] = 0;
					break;
				default:
					break;
			}			
			$row['state_tags'] = $this->settings['state'][$row['state']];
						
			//如果存在LOGO
			if($row['logo'])
			{
				$row['logo_url'] = UPLOAD_URL . LOGO_DIR . ceil($row['user_id']/NUM_IMG) . "/" . $row['logo'];
				//$row['logo_url'] = UPLOAD_URL . "20100716015551382.jpg";
			}
			else//调用默认LOGO
			{
				$row['logo_url'] = UPLOAD_URL . LOGO_DIR . "0.gif";	
				//$row['logo_url'] = UPLOAD_URL . "20100716015551382.jpg";	
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
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "network_station AS n WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);
		echo json_encode($r);
	}
	
	/**
	 * 获取单条频道数据
	 */
	public function detail()
	{
		/*$sql = "SELECT * FROM ".DB_PREFIX."network_programme WHERE sta_id = " . $this->input['id'] . " ORDER BY order_id DESC";
		$query = $this->db->query($sql);
		$channel = array();
		while ($array = $this->db->fetch_array($query))
		{
			$toff = $array['end_time']-$array['start_time'];
			$array['toff'] = date('H:i:s',$toff);
			$channel['programme'][] = $array;
		}*/
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			$condition = ' ORDER BY n.id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE n.id in(' . $this->input['id'] .')';
		}
		
		$sql = "SELECT n.* , u.username FROM " . DB_PREFIX . "network_station AS n LEFT JOIN " . DB_PREFIX . "user AS u ON n.user_id = u.id " . $condition;
		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('channel_info' , 'channel');
		
		if(is_array($r) && $r)
		{
			$r['create_time'] = date('Y-m-d H:i:s' , $r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
			
			if($this->settings['rewrite'])
			{
				$r['link'] = SNS_VIDEO . "station-" . $r['id'] .".html";	
			}
			else 
			{
				$r['link'] = SNS_VIDEO."station.play.php?sta_id=".$r['id'];
			}
			
			//如果存在LOGO
			if($r['logo'])
			{
				$r['logo_url'] = UPLOAD_URL . LOGO_DIR . ceil($r['user_id']/NUM_IMG) . "/" . $r['logo'];	
			}
			else//调用默认LOGO
			{
				$r['logo_url'] = UPLOAD_URL . LOGO_DIR . "0.gif";	
			}
			$r['status'] = $r['state'] ? 2 : 0;
			//$channel['info'] = $r;
	//		hg_pre($r);exit;
			$this->addItem($r);
			$this->output();
			
		}
		else
		{
			$this->errorOutput('视频不存在');	
		} 							
	}
	
	/**
	 * 获取查询条件
	 */
	private function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND n.web_station_name LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND n.create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND n.create_time < " . strtotime($this->input['end_time']);	
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		//查询网台的类型
		if(isset($this->input['channel_state']) && intval($this->input['channel_state'])> -1)
		{
			$condition .= " AND n.state = ".intval($this->input['channel_state']);
		}
		
		//查询排序类型(字段，默认为创建时间)
		$order = $this->input['order_field'] ? $this->input['order_field'] : 'n.create_time'; 
		switch($order)
		{
			case 'n.create_time' : $condition .= " ORDER BY " . $order;break;
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

