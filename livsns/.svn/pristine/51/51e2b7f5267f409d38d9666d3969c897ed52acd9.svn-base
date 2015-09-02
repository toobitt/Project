<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_get_many_videos extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/*功能:获取符合条件视频信息
	 *返回值：符合条件视频信息
	 * */
	public function get_many_videos()
	{
		$offset = intval($this->input['start'])?intval($this->input['start']):0;
		$count = intval($this->input['num'])?intval($this->input['num']):40;
		$limit = "  limit {$offset}, {$count}";
		$condition = $this->get_condition();
		
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE 1 ".$condition."  ORDER BY video_order_id  DESC  ".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['duration'] = time_format($r['duration']);
			$r['totalsize'] = hg_fetch_number_format($r['totalsize'],true);
			$img_arr = $r['img_info'] = unserialize($r['img_info']);
			$r['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			$return['video_info'][] = $r;
		}
		
		$current_page = intval($this->input['start']);
	    $page_num = 40;/*每页显示的数目*/
		$total_num = $this->count();
		$last_page = intval($total_num/$page_num) * $page_num;
		/*第一页*/
		$return['first_page'] = 0;
		/*下一页*/
		if(($current_page == $last_page) || ($current_page + $page_num == $total_num))
		{
			$return['next_page']  = $current_page;
		}
		else
		{
			$return['next_page']  = $current_page + $page_num;
		}
		/*前一页*/
		if($current_page == 0)
		{
			$return['prev_page']  = 0;
		}
		else 
		{
			$return['prev_page']  = $current_page - $page_num;
		}
		/*最后一页*/
		if(intval($total_num%$page_num) == 0)
		{
			$return['last_page'] = $last_page - $page_num;
		}
		else 
		{
			$return['last_page'] = $last_page;
		}
		/*当前页*/
		$return['current_page']  = intval($current_page/$page_num) + 1;
		
		/*总页数*/
		if(intval($total_num%$page_num) == 0)
		{
			$return['total_page']    = intval($total_num/$page_num);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$page_num) + 1;
		}
		/*总条数*/
		$return['total_num'] = $total_num;
		$return['page_num'] = $page_num;
		
		$return['switch_mode'] = intval($this->input['g_switch_mode']);

		$this->addItem($return);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND id = '".urldecode($this->input['id'])."'";
		}
		
		if($this->input['title'])
		{
			$condition .= ' AND title LIKE "%'.urldecode($this->input['title']).'%"';
		}
		
		if($this->input['_id'])
		{
			$condition .= " AND vod_sort_id = '".intval($this->input['_id'])."'";
		}
		
		if($this->input['vcr_type'])
		{
			$condition .= " AND vcr_type = '".intval($this->input['vcr_type'])."'";
		}
		
		if($this->input['_type'])
		{
			$condition .= " AND  vod_leixing = '".intval($this->input['_type'])."'";
		}
	   
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  title  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
				
		if($this->input['trans_status'] && urldecode($this->input['trans_status'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['trans_status'])."'";
		}
		else if(urldecode($this->input['trans_status']) == '0')//此处为了区分状态0的情况与传过来的值为空的情况，为空的时候查出所有
		{
			$condition .= " AND status = 0 ";
		}
		/*
		if($this->input['video_id'])
		{
			$sql = "SELECT frame_rate FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['video_id'])."'";
			$arr = $this->db->query_first($sql);
			$condition .= " AND frame_rate = '".$arr['frame_rate']."' AND vod_leixing != 4 ";
		}
		*/

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
		return $condition;
	}

	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'vodinfo WHERE 1  '.$this->get_condition();
		$total = $this->db->query_first($sql);
		return 	$total['total'];
	}
	
	public function select_videos()
	{
		$offset = intval($this->input['start'])?intval($this->input['start']):0;
		$count = intval($this->input['num'])?intval($this->input['num']):40;
		$limit = "  limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$extend_cond = ' AND status = 2 ';
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE 1 ".$condition.$extend_cond ."  ORDER BY video_order_id  DESC  ".$limit;
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['duration_format'] = time_format($r['duration']);
			$r['totalsize'] = hg_fetch_number_format($r['totalsize'],true);
			$img_arr = $r['img_info'] = unserialize($r['img_info']);
			$r['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			$return['video_info'][] = $r;
		}
		
		$current_page = intval($this->input['start']);
	    $page_num = 40;/*每页显示的数目*/
		$total_num = $this->count();
		$last_page = intval($total_num/$page_num) * $page_num;
		/*第一页*/
		$return['first_page'] = 0;
		/*下一页*/
		if(($current_page == $last_page) || ($current_page + $page_num == $total_num))
		{
			$return['next_page']  = $current_page;
		}
		else
		{
			$return['next_page']  = $current_page + $page_num;
		}
		/*前一页*/
		if($current_page == 0)
		{
			$return['prev_page']  = 0;
		}
		else 
		{
			$return['prev_page']  = $current_page - $page_num;
		}
		/*最后一页*/
		if(intval($total_num%$page_num) == 0)
		{
			$return['last_page'] = $last_page - $page_num;
		}
		else 
		{
			$return['last_page'] = $last_page;
		}
		/*当前页*/
		$return['current_page']  = intval($current_page/$page_num) + 1;
		
		/*总页数*/
		if(intval($total_num%$page_num) == 0)
		{
			$return['total_page']    = intval($total_num/$page_num);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$page_num) + 1;
		}
		/*总条数*/
		$return['total_num'] = $total_num;
		$return['page_num'] = $page_num;
		
		$return['switch_mode'] = intval($this->input['g_switch_mode']);

		$this->addItem($return);
		$this->output();
	}

}

$out = new vod_get_many_videos();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'get_many_videos';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>