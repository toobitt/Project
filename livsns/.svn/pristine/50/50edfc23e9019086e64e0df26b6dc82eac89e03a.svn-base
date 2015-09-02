<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/ticket.class.php';
define('MOD_UNIQUEID','ticket');//模块标识
class ticketApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->ticket = new ticket();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function count()
	{
		
	}
	public function show()
	{
		$offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):10;
		$orderby = ' ORDER BY s.order_id DESC,s.end_time  DESC';
		$res = $this->ticket->show($this->get_condition(),$orderby,$offset,$count);
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	public function get_condition()
	{
		$condition = ' AND s.status=1';
		if ($this->input['id'])
		{
			$condition .= ' AND s.id = '.intval($this->input['id']);
		}
		if ($this->input['sort_id'])
		{
			$sort = $this->ticket->child_sort(intval($this->input['sort_id']));
			$condition .= ' AND s.sort_id IN ('.$sort.')';
		}
		return $condition;
	}
	public function detail()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = intval($this->input['id']);
		$flag = intval($this->input['flag']);
		$data = $this->ticket->detail($id,$flag);
		$this->addItem($data);
		$this->output();
	}
	
	
	/**
	 * 订单获取商品信息（支持多个ids）
	 * Enter description here ...
	 */
	public function getGoodsInfo()
	{
		$goods = array();
		$goods = $this->input['goods'];
		/*$goods = array(
			1 => array(
				'id' => 1,
				'goods_number' => 1,
			),
			2 => array(
				'id' => 2,
				'goods_number' => 20,
			),
		);*/
		if(!is_array($goods) && !count($goods))
		{
			$this->errorOutput('数据异常');
		}
		$price_ids = implode(',',array_keys($goods));
		
		if(!$price_ids)
		{
			$this->errorOutput('票价id不存在');
		}
		
		$sql = "SELECT id,show_id,perform_id FROM " . DB_PREFIX . "price WHERE id IN (" . $price_ids . ")";
		$q = $this->db->query($sql);
		$price_info = array();
		$perform_id = array();
		while ($r = $this->db->fetch_array($q))
		{
			$price_info[$r['id']] 			= $r;
			$show_id[$r['show_id']] 		= 1;
			$perform_id[$r['perform_id']] 	= 1;
		}
		if(empty($price_info))
		{
			$this->errorOutput('票信息不存在');
		}
		
		$show_ids 		= implode(',',array_keys($show_id));
		if(!$show_ids)
		{
			$this->errorOutput('演出id不存在');
		}
		
		if($perform_id)
		{
			$perform_ids	= implode(',',array_keys($perform_id));
			$sql = "SELECT id,show_time FROM " . DB_PREFIX . "performances WHERE id IN (" . $perform_ids . ")";
			$q = $this->db->query($sql);
			
			$perform_info = array();
			while ($r = $this->db->fetch_array($q))
			{
				$perform_info[$r['id']] = $r['show_time'];
			}
		}
		
		$offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):100;
		
		$condition = ' AND s.id IN (' . $show_ids . ')';
		$res = $this->ticket->show($condition,'',$offset,$count);
		
		$data_tmp = array();
		foreach ($price_info as $k => $v)
		{
			if(!$res[$v['show_id']])
			{
				continue;
			}
			$data_tmp[$k]['goods_title'] 			= $res[$v['show_id']]['title'];
			$data_tmp[$k]['goods_brief']			= $res[$v['show_id']]['brief'];
			$data_tmp[$k]['goods_id']				= $v['show_id'];
			$data_tmp[$k]['index_img']				= $res[$v['show_id']]['index_url'];
			
			$data_tmp[$k]['goods_discount']			= 0;
			if(!$perform_info[$v['perform_id']])
			{
				$error_flag = 2;
				$error_data['data'][$k]['msg'] = '场次不存在';
			}
			else 
			{
				$week = hg_mk_weekday($perform_info[$v['perform_id']]);
				$r['show_time1'] = date('m月d号',$perform_info[$v['perform_id']]);
				$r['show_time2'] = date('H:i',$perform_info[$v['perform_id']]);
				$data_tmp[$k]['extension']['session'] = $r['show_time1'] . ' ' .$week . ' ' . $r['show_time2'];
				$res['session'] = $r['show_time1'] . ' ' .$week . ' ' . $r['show_time2'];
				unset($r['show_time1'],$r['show_time2']);
			}
			$data_tmp[$k]['goods_all_info']			= $res;
		}
		
		if($error_flag==2)
		{
            $error_data['status'] = 2;
            foreach ($error_data as $key=>$val)
            {
                $this->addItem_withkey($key,$val);
            }
            $this->output();
		}
		if (!empty($data_tmp))
		{
			foreach ($data_tmp as $key=>$val)
			{
				$this->addItem_withkey($key,$val);
			}
		}
		else 
		{
			$this->addItem(array());
		}
		$this->output();
	}
	
	/**
	 * 获取票的库存(支持多个ids)
	 * Enter description here ...
	 */
	public function getStore()
	{
		$goods = array();
		$goods = $this->input['goods'];
		
		// $goods = array(
			// 1 => array(
				// 'id' => 1,
				// 'goods_number' => 1111111111,
			// ),
			// 2 => array(
				// 'id' => 2,
				// 'goods_number' => 20,
			// ),
		// );
		if(!is_array($goods) && !count($goods))
		{
			$this->errorOutput('数据异常');
		}
		$price_ids = implode(',',array_keys($goods));
		
		if(!$price_ids)
		{
			$this->errorOutput('id不存在');
		}
		
		$sql = "SELECT id,goods_total_left as store_number,price as goods_value FROM " . DB_PREFIX . "price WHERE id IN (" . $price_ids . ")";
		$q = $this->db->query($sql);
		$data = array();
		while ($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		
		if(empty($data))
		{
			$this->errorOutput('数据异常');
		}
		
		$error_data = array();
		$total_goods_value = '';
        $error_flag = 1;
		foreach ($data as $k => $v)
		{
			if($goods[$k] && $v['store_number'] < $goods[$k]['goods_number'])
			{
			    $error_flag = 2;
				$error_data['data'][$k]['msg'] = '库存不足';
			}
			else 
			{
				$data_tmp[$k]['goods_number'] 	= $goods[$k]['goods_number'];
				$data_tmp[$k]['goods_value']	= $v['goods_value'];
				$total_goods_value += $v['goods_value'] * $goods[$k]['goods_number'];
			}
		}
        
		if($error_flag==2)
		{
		    //$error_data['ErrorCode'] = 'NO_GOODS_NUMBER';
            //$error_data['ErrorText'] = '库存不足';
            $error_data['status'] = 2;
            foreach ($error_data as $key=>$val)
            {
                $this->addItem_withkey($key,$val);
            }
		    //$this->addItem($this->addItem($error_data));
            $this->output();
		}
		
		if($data_tmp)
		{
			$data_tmp['total_goods_value'] = $total_goods_value;
		}
		//hg_pre($data_tmp,0);
		foreach ($data_tmp as $key=>$val)
		{
			$this->addItem_withkey($key,$val);
		}
        $this->addItem_withkey('status',1);
		$this->output();
	}
	
	/**
	 * 更新票库存
	 * Enter description here ...
	 */
	public function updateStore()
	{
		$arr = $this->input['goods'];
		
		if(!count($arr) || !is_array($arr))
		{
			$this->errorOutput('数据异常');
		}
		$type = '';
		if($this->input['operation'] == 'plus')
		{
			$type = '+';
		}
		else if($this->input['operation'] == 'minus')
		{
			$type = '-';
			
			//$show_id = $this->input['show_id'];
			//验证演出状态是否为出售中
			if($show_id)
			{
				$show_ids = implode(',',array_keys($show_id));
			
				$sql = "SELECT sale_state,id FROM " . DB_PREFIX . "show WHERE id IN (" . $show_ids . ")";
				$q = $this->db->query($sql);
				while ($r = $this->db->fetch_array($q))
				{
					if($r['sale_state'] != 2)
					{
						$error_flag = 2;
						$error_data['data'][$r['id']]['msg'] = '演出已结束';
						//$this->errorOutput('演出已结束');
					}
				}
			}
		}
		
		if($error_flag==2)
		{
            $error_data['status'] = 2;
            foreach ($error_data as $key=>$val)
            {
                $this->addItem_withkey($key,$val);
            }
            $this->output();
		}
		
		if(!$type)
		{
			$this->errorOutput('更新异常');
		}
		
		//开启事务
		$this->db->commit_begin();
        $commit_tag = 1;
        
        foreach ($arr as $k => $v)
        {
        	//商品数量不存在
        	if(!$v['goods_number'])
        	{
        		$commit_tag = 0;
        		break;
        	}
        	
        	$sql = '';
        	$sql = "UPDATE " . DB_PREFIX . "price SET goods_total_left=goods_total_left" . $type . $v['goods_number'] . " WHERE id = " . $v['id'];
        	if($type == '-')
        	{
        		$sql .= " AND goods_total_left >=" . $v['goods_number'];
        	}
        	$this->db->query($sql);
        	$ret = $this->db->affected_rows();
        	if(!$ret)
        	{
        	    $error_data['data'][$r['id']]['msg'] = '演出不存在';
        		$commit_tag = 0;
        		break;
        	}
        }
        
        //中途出错，回滚
        if (!$commit_tag) 
		{   
            $this->db->rollback();
            //$this->errorOutput('更新异常');
            
            $error_data['status'] = 2;
            foreach ($error_data as $key=>$val)
            {
                $this->addItem_withkey($key,$val);
            }
            $this->output();
        }
        
        //提交事务
    	$this->db->commit_end();
    	

        
    	/*foreach ($arr as $key=>$val)
		{
			$this->addItem_withkey($key,$val);
		}*/
		$this->addItem_withkey('status',1);
		$this->output();
	}
}
$out = new ticketApi();
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