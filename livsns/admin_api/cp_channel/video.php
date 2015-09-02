<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
* 
* $Id: video.php 7009 2012-06-05 08:07:35Z hanwenbin $
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
		$ret = array();
		if($this->input['cid'])
		{	
			/*查询出该频道的信息*/
			$sql = "SELECT n.* , u.username FROM " . DB_PREFIX . "network_station AS n LEFT JOIN " . DB_PREFIX . "user AS u ON n.user_id = u.id  WHERE n.id = '".intval($this->input['cid'])."'";
			$arr = $this->db->query_first($sql);
			//如果存在LOGO
			if($arr['logo'])
			{
				$arr['logo_url'] = UPLOAD_URL . LOGO_DIR . ceil($arr['user_id']/NUM_IMG) . "/" . $arr['logo'];
			}
			else//调用默认LOGO
			{
				$arr['logo_url'] = UPLOAD_URL . LOGO_DIR . "0.gif";		
			} 
			$arr['brief'] = hg_cutchars($arr['brief'],21);
			$arr['create_time'] = date('Y-m-d',$arr['create_time']);
			$arr['update_time'] = date('Y-m-d',$arr['update_time']);
			$ret['channel'] = $arr;
		}
		
		
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 24;		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
						
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX  . "user AS u ON v.user_id = u.id WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);
		
		$this->setXmlNode('video_info' , 'video');
		while($row = $this->db->fetch_array($q))
		{
			$row['title'] = hg_cutchars($row['title'],8);
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['toff'] = date('i:s' , $row['toff']);
			$row['state_tags'] = $this->settings['video_state'][$row['state']];
			if($this->settings['rewrite'])
			{
				$row['link'] = SNS_VIDEO . "video-" . $row['id'] .".html";	
			}
			else 
			{
				$row['link'] = SNS_VIDEO . "video_play.php?id=" . $row['id'];	
			}
			switch (intval($row['is_show']))
			{
				case 0:
					$row['audit'] = 0;
					break;
				case 1:
					$row['audit'] = 0;
					break;
				case 2:
					$row['audit'] = 1;
					break;
				default:
					break;
			}
			$row['is_show_tags'] = $this->settings['video_type'][$row['is_show']];
			$row['copyright'] = $this->settings['video_copyright'][$row['copyright']];
			$ret['video'][] = $row;
				
		}
		$this->addItem($ret);
		$this->output();			
	}
	
	/**
	 * 获取视频总数
	 * 默认为全部视频的总数
	 */
	public function count()
	{	
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "video AS v WHERE 1 ";

		//获取查询条件
		$condition = $this->get_condition();				
		$sql = $sql . $condition;
		$r = $this->db->query_first($sql);

		//暂时这样处理
		echo json_encode($r);
	}
		
	/**
	 * 获取单条数据
	 */
	public function detail()
	{
		$this->input['id'] = urldecode($this->input['id']);
		if(!$this->input['id'])
		{
			$condition = ' ORDER BY v.id DESC LIMIT 1';
		}
		else
		{
			$condition = ' WHERE v.id in(' . $this->input['id'] .')';
		}
		$sql = "SELECT v.* , u.username FROM " . DB_PREFIX . "video AS v LEFT JOIN " . DB_PREFIX  . "user AS u ON v.user_id = u.id" . $condition;
		
		$r = $this->db->query_first($sql);
		$this->setXmlNode('video_info' , 'video');
		
		if(is_array($r) && $r)
		{
			if($this->settings['rewrite'])
			{
				$r['link'] = SNS_VIDEO . "video-" . $r['id'] .".html";	
			}
			else 
			{
				$r['link'] = SNS_VIDEO . "video_play.php?id=" . $r['id'];	
			}
			$r['create_time'] = date('Y-m-d H:i:s' , $r['create_time']);
			$r['update_time'] = date('Y-m-d H:i:s' , $r['update_time']);
			$r['status'] = $r['state'] ? 2 : 0;
			$this->addItem($r);
			$this->output();
		}
		else
		{
			$this->errorOutput('视频不存在');	
		} 					
	}

	/**
	 * 获取搜索条件
	 */
	public function get_condition()
	{
		$condition = '';
		
		//查询的关键字
		if($this->input['k'])
		{
			$condition .= " AND v.title LIKE '%" . trim(urldecode($this->input['k'])) . "%' ";
		}
		if($this->input['cid'])
		{
			$condition .= " AND v.sort_id = " . trim(urldecode($this->input['cid']));
		}
		//查询的起始时间
		if($this->input['start_time'])
		{
			$condition .= " AND v.create_time > " . strtotime($this->input['start_time']);
		}
		
		//查询的结束时间
		if($this->input['end_time'])
		{
			$condition .= " AND v.create_time < " . strtotime($this->input['end_time']);	
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
					$condition .= " AND  v.create_time > '".$yesterday."' AND v.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  v.create_time > '".$today."' AND v.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.create_time > '".$last_threeday."' AND v.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.create_time > '".$last_sevenday."' AND v.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//查询视频的类型
		if (isset($this->input['video_state']))
		{
			switch($this->input['video_state'])
			{
				case -1:
						$condition .= " ";
					break;
				case 0:
						$condition .= " AND v.is_show = 0 ";
					break;
				case 1:
						$condition .= " AND v.is_show = 1 ";
					break;
				case 2:
						$condition .= " AND v.is_show = 2 ";
					break;
			}
		}

		if (isset($this->input['coded']))
		{
			switch($this->input['coded'])
			{
				case -1:
						$condition .= "";
					break;
				default:
						$condition .= " AND v.state = " . intval($this->input['coded']);
					break;
			}
		}
		
		$orders = array('collect_count', 'comment_count', 'click_count', 'play_count');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (!in_array($this->input['hgorder'], $orders))
		{
			$this->input['hgorder'] = 'create_time';
		}
		
		$orderby = ' ORDER BY v.' . $this->input['hgorder']  . ' ' . $descasc ;
		return $condition . $orderby;
	}
	/*获取频道以及频道里面的所有视频*/
	function join_channel()
	{
		if(!$this->input['cid'])
		{
			$this->errorOutput('未传频道id');
		}
		
		$ret = array();
		/*查询出该频道的信息*/
		$sql = "SELECT n.* , u.username FROM " . DB_PREFIX . "network_station AS n LEFT JOIN " . DB_PREFIX . "user AS u ON n.user_id = u.id  WHERE n.id = '".intval($this->input['cid'])."'";
		$arr = $this->db->query_first($sql);
		$arr['create_time'] = date('Y-m-d',$arr['create_time']);
		$arr['update_time'] = date('Y-m-d',$arr['update_time']);
		$ret['channel'] = $arr;
		
		/*查询出该频道下视频*/
		//分页参数设置
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;		
		$data_limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT * FROM ". DB_PREFIX . "video as v WHERE 1";
		$condition = $this->get_condition();		
		$sql = $sql . $condition . $data_limit;		
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$r['toff'] = date('i:s',$r['toff']);
			$ret['video'][] = $r;
		}
		$this->addItem($ret);
		$this->output();
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