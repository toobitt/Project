<?php
define('MOD_UNIQUEID','order');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/order_mode.php');
class order_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new order_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
		$product_id = intval($this->input['product_id']);
		
		if(!$product_id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT youhui_price,count_type,max_num,order_num,amount FROM ".DB_PREFIX."product WHERE id = ".$product_id;
		$res = $this->db->query_first($sql);
		if($res['order_num'] >= $res['max_num'])
		{
			$this->errorOutput('已到上限人数');
		}
		if(!$res['amount'])
		{
			$this->errorOutput('商品已被订购完');
		}
		
		$product_num = intval($this->input['product_num']);
		if($product_num > $res['amount'])
		{
			$this->errorOutput('商品数量有误');
			
		}
		if(!$product_num)
		{
			return false;
		}
		
		$order_sum = $res['youhui_price'] * $product_num;
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'user_name'		=> $this->user['user_name'],
			'product_id'	=> $product_id,
			'address'		=> trim($this->input['address']),
			'remark'		=> trim($this->input['remark']),
			'phone'			=> $this->input['phone'],
			'email'			=> $this->input['email'],
			'create_time'	=> TIMENOW,
			'UPDATE'		=> TIMENOW,
			'product_num'	=> $product_num,
			'order_sum'		=> $order_sum,
			
		);
		
		$ret = $this->mode->create($data);
		if($ret)
		{
			if($res['count_type'] == 1)//下单减库存
			{
				$sql = "UPDATE ".DB_PREFIX."product SET order_num = order_num + 1,amount = amount - ".$product_num."WHERE id = ".$product_id;
				$this->db->query($sql);
			}
			else 
			{
				$sql = "UPDATE ".DB_PREFIX."product SET order_num = order_num + 1 WHERE id = ".$product_id;
				$this->db->query($sql);
			}
			$this->addLogs('创建',$ret,'','创建' . $ret['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$update_data = array(
			/*
				code here;
				key => value
			*/
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			//$this->addLogs('更新',$ret,'','更新' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			//$this->addLogs('删除',$ret,'','删除' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new order_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>