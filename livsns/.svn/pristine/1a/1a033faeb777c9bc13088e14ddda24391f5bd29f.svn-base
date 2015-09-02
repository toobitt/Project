<?php
require ('./global.php');
require_once(CUR_CONF_PATH . 'lib/product_mode.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
define('MOD_UNIQUEID','product');
define('SCRIPT_NAME', 'product');
class product extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->obj = new product_mode(); 
		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$orderby = ' ORDER BY t1.order_id DESC ';
				
		$res = $this->obj->show($this->get_condition(),$orderby,$limit);
		if($res)
		{
			foreach ($res as $v)
			{
				if($v['start_time'] > TIMENOW)
				{
					$v['cheap_status'] = '预告中';
					$v['cheap_state'] = 0;
				}
				else if($v['start_time'] < TIMENOW && $v['end_time'] > TIMENOW)
				{
					$v['cheap_status'] = '进行中';
					$v['cheap_state'] = 1;
					if($v['channel_id'] && $v['live_start_time'] <= TIMENOW && $v['live_end_time'] >= TIMENOW)
					{
						$v['cheap_status'] = '直播中';
					}
				}
				else if($v['end_time'] < TIMENOW)
				{
					$v['cheap_status'] = '已结束';
					$v['cheap_state'] = 2;
				}
				if($v['amount'] <= 0)
				{
					$v['cheap_status'] = '已售完';
					$v['cheap_state'] = 2;
				}
				
				if($v['sale_base'])
				{
					$v['sale_num'] += $v['sale_base'];
				}
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = '';
		//站点名称
		if($this->input['name'])
		{
			$condition .= ' AND t1.title LIKE "%'.trim($this->input['name']).'%"';
		}
		
		//根据区域查询
		if($this->input['company_id'])
		{
			$condition .= " AND t1.company_id =" . intval($this->input['company_id']);
		}
		
		if($this->input['cheap_type'] == 1)//当前优惠
		{
			$condition .= " AND t1.start_time < " . TIMENOW . " AND end_time > ".TIMENOW;
		}
		elseif($this->input['cheap_type'] == 2)//优惠预告
		{
			$condition .= " AND t1.start_time > ".TIMENOW;
		}
		$condition .= " AND t1.status = 1";
		return $condition ;
	}
	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count($condition);
		echo json_encode($info);
	}
	public function detail()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			return false;
		}
	
		$ret = $this->obj->detail($id);
		if($ret)
		{
			if($ret['contract_way']['contract_value'])
			{
				foreach($ret['contract_way']['contract_value'] as $k => $v)
				{
					$contract_val[] = $v;
				}
				if($contract_val)
				{
					$ret['contract_way'] = $contract_val;
				}
				else
				{
					$ret['contract_way'] = array();

				}
			}
			$ret['show_type'] = 0;
			//索引图
			if(!$ret['pic_info'])
			{
				$ret['pic_info'] = array();
				if($ret['img_info'])
				{
					$ret['show_type'] = 3;
				
					$ret['pic_info'][] = $ret['img_info'];
				}
			}
			else 
			{
				$ret['show_type'] = 3;
				if($ret['img_info'])
				{
					array_unshift($ret['pic_info'],$ret['img_info']);
				}
			}
			
			//视频
			if($ret['video_info'])
			{
				foreach($ret['video_info'] as $vid => $info)
				{
					$video_arr[] = $info;
				}
				$ret['video_info'] = $video_arr[0];
				$ret['show_type'] = 2;
				
			}
			else
			{
				$ret['video_info'] = array();
			}
			
			//直播
			if($ret['live_info'])
			{
				$ret['show_type'] = 1;
				
				//检查直播端口
				$new_url = check_live_port($ret['live_info']['m3u8']);
				if($new_url)
				{
					$ret['live_info']['m3u8'] 		= $new_url;
					$ret['live_info']['live_url'] 	= $new_url;
					$ret['live_info']['live_m3u8'] 	= $new_url;
				}
			}
			else 
			{
				$ret['live_info'] = array();
			}
			
			//判断优惠有没有开始
			if($ret['start_time2'] > TIMENOW)
			{
				$ret['cheap_status'] = '预告中';
				$ret['cheap_state'] = 0;
			}
			else if($ret['start_time2'] < TIMENOW && $ret['end_time2'] > TIMENOW)
			{
				$ret['cheap_status'] = '进行中';
				$ret['cheap_state'] = 1;
				if($ret['show_type'] == 1)
				{
					$ret['cheap_status'] = '直播中';
				}
			}
			else if($ret['end_time2'] < TIMENOW)
			{
				$ret['cheap_status'] = '已结束';
				$ret['cheap_state'] = 2;
			}
			
			if($ret['sale_base'])
			{
				$ret['sale_num'] += $ret['sale_base'];
			}
				
			//hg_pre($ret,0);
			$this->addItem($ret);
			$this->output();
		}
	}

}
include(ROOT_PATH . 'excute.php');
?>