<?php
require ('./global.php');
require_once(CUR_CONF_PATH . 'lib/order_mode.php');
require_once(ROOT_PATH . 'lib/func/functions.php');
require_once(CUR_CONF_PATH . 'lib/product_mode.php');

define('MOD_UNIQUEID','order');
define('SCRIPT_NAME', 'order');
class order extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->mode = new order_mode(); 
		$this->prod_mode = new product_mode();
		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		if(!$this->user['user_id'])
		{
			return false;
		}
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY o.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$sql = "SELECT o.*,p.title,p.youhui_price,p.start_time,p.end_time,p.amount,m.host,m.dir,m.filepath,m.filename,p.channel_id,p.live_start_time,p.live_end_time FROM ".DB_PREFIX."order o 
					LEFT JOIN ".DB_PREFIX."product p 
				ON o.product_id = p.id 
					LEFT JOIN ".DB_PREFIX."materials m 
				ON p.indexpic_id = m.id WHERE 1 " . $condition . $orderby . $limit;
				
		$q = $this->db->query($sql);
		
		while($r = $this->db->fetch_array($q))
		{
			if($r['end_time'] >= TIMENOW)
			{
				$r['cheap_status'] = '进行中';
				if($r['channel_id'] && $r['live_start_time'] <= TIMENOW && $r['live_end_time'] >= TIMENOW)
				{
					$r['cheap_status'] = '直播中';
				}
			}
			else 
			{
				$r['cheap_status'] = '已过期';
			}
			if($r['amount'] <= 0)
			{
				$r['cheap_status'] = '已售完';
				$r['cheap_state'] = 2;
			}
			$r['create_time'] = hg_tran_time($r['create_time']);
			if($r['host'] && $r['dir'] && $r['filepath'] && $r['filename'])
			{
				$r['indexpic'] = array(
					'host' 		=> $r['host'],
					'dir'		=> $r['dir'],
					'filepath'	=> $r['filepath'],
					'filename'	=> $r['filename'],	 
				);
			}
			else
			{
				$r['indexpic'] = array();
			}
			
			$this->addItem($r);
		}
		$this->output();
		
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order o WHERE 1 " . $condition;
		$total = $this->db->query_first($sql);
		echo json_encode($total);
	}
	
	
public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND o.id IN (".($this->input['id']).")";
		}
				
		if($this->user['user_id'])
		{
			$condition .= ' AND o.user_id = '.intval($this->user['user_id']);
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND o.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND o.create_time <= '".$end_time."'";
		}

		return $condition;
	}

	public function detail()
	{
		$id=$this->input['id'];
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."order WHERE id = ".$id;
		$order_info = $this->db->query_first($sql);
		if($order_info)
		{
			$product_id = $order_info['product_id'];
			if($product_id)
			{
				$product_info = $this->prod_mode->detail($product_id);
			}
			
			if($product_info)
			{
				//联系方式
				if($product_info['contract_way']['contract_value'])
				{
					foreach($product_info['contract_way']['contract_value'] as $k => $v)
					{
						$contract_val[] = $v;
					}
					
					if($contract_val)
					{
						$product_info['contract_way'] = $contract_val;
					}
					else
					{
						$product_info['contract_way'] = array();
					}
				}	

				
				//显示类型1直播，2视频，3图片
				$product_info['show_type'] = 0;
				//图片信息
				if(!$product_info['pic_info'])
				{
					$product_info['pic_info'] = array();
					
					if($product_info['img_info'])
					{
						$product_info['show_type'] = 3;
						$product_info['pic_info'][] = $product_info['img_info'];
					}
				}
				else 
				{
					$product_info['show_type'] = 3;
					if($product_info['img_info'])
					{
						array_unshift($product_info['pic_info'],$product_info['img_info']);
					}
				}
				
				//视频
				if($product_info['video_info'])
				{
					foreach($product_info['video_info'] as $vid => $info)
					{
						$video_arr[] = $info;
					}
					$product_info['video_info'] = $video_arr[0];
					$product_info['show_type'] = 2;
				}
				else
				{
					$product_info['video_info'] = array();
				}
				
				//直播
				if($product_info['live_info'])
				{
					$product_info['show_type'] = 1;
					
					//检查直播端口
					$new_url = check_live_port($product_info['live_info']['m3u8']);
					if($new_url)
					{
						$product_info['live_info']['m3u8'] 		= $new_url;
						$product_info['live_info']['live_url'] 	= $new_url;
						$product_info['live_info']['live_m3u8'] = $new_url;
					}
				}
				else 
				{
					$product_info['live_info'] = array();
				}
				
				//判断优惠有没有开始
				if($product_info['start_time2'] > TIMENOW)
				{
					$product_info['cheap_status'] = '预告中';
					$product_info['cheap_state'] = 0;
				}
				else if($product_info['start_time2'] < TIMENOW && $product_info['end_time2'] > TIMENOW)
				{
					$product_info['cheap_status'] = '进行中';
					$product_info['cheap_state'] = 1;
					if($product_info['show_type'] == 1)
					{
						$product_info['cheap_status'] = '直播中';
					}
				}
				else if($product_info['end_time2'] < TIMENOW)
				{
					$product_info['cheap_status'] = '已结束';
					$product_info['cheap_state'] = 2;
				}
				$product_info['order_info'] = $order_info;
				$this->addItem($product_info);
			
			}
		}
		$this->output();
		
	}
	
	public function create()
	{
		
		$product_id = intval($this->input['product_id']);
		
		if(!$product_id)
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->user['user_id'])
		{
			$this->errorOutput('请先登录');
		}
		
		$product_num = intval($this->input['product_num']);
		if(!$product_num)
		{
			$this->errorOutput('请输入商品数量');
		}
		
		$custom_name = trim($this->input['custom_name']);
		if(!$custom_name)
		{
			$this->errorOutput('请填写客户姓名');
		}
		
		$sql = "SELECT youhui_price,count_type,max_num,order_num,amount,id_limit,fare,prod_url FROM ".DB_PREFIX."product WHERE id = ".$product_id;
		$res = $this->db->query_first($sql);
		
		if($res['amount'] <= 0)
		{
			$this->errorOutput('商品已被订购完');
		}
		if($res['max_num'])
		{
			if($res['order_num'] >= $res['max_num'])
			{
				$this->errorOutput('已到上限人数');
			}
		}
		
		if($res['id_limit'])
		{
			if($product_num > $res['id_limit'])
			{
				$this->errorOutput('每个ID只能购买'.$res['id_limit'].'件');
			}
		}
		
		if($product_num > $res['amount'])
		{
			$this->errorOutput('商品数量有误');
		}
		
		$address = trim($this->input['address']);
		$order_sum = $res['youhui_price'] * $product_num;
		
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'product_id'	=> $product_id,
			'custom_name'	=> $custom_name,
			'address'		=> trim($this->input['address']),
			'remark'		=> trim($this->input['remark']),
			'phone'			=> $this->input['phone'],
			'email'			=> $this->input['email'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'product_num'	=> $product_num,
			'order_sum'		=> $order_sum,
			
		);
		
		$ret = $this->mode->create($data);
		$ret['order_sum'] += $res['fare'];

		if($ret)
		{
			if(!$res['count_type'])//下单减库存
			{
				$sql = "UPDATE ".DB_PREFIX."product SET order_num = order_num + 1,amount = amount - " . $product_num . ",sale_num = sale_num + " . $product_num . " WHERE id = ".$product_id;
				$this->db->query($sql);
			}
			else //付款或者确认后减库存
			{
				$sql = "UPDATE ".DB_PREFIX."product SET order_num = order_num + 1,sale_num = sale_num + " . $product_num . " WHERE id = ".$product_id;
				$this->db->query($sql);
			}
			if($res['prod_url'])
			{
				$ret['show_text'] = '您已成功提交订单，可通过淘宝支付订金！';
			}
			else 
			{
				$ret['show_text'] = '您已成功提交订单，客服稍后将与你联系！';
			}
			$this->addItem($ret);
			
		}
		$this->output();
	}


}
include(ROOT_PATH . 'excute.php');
?>