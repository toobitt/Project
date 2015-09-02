<?php
require('global.php');
define('MOD_UNIQUEID','ticket_perform');//模块标识
define('SCRIPT_NAME', 'PerformUpdate');
class PerformUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
       // $this->verify_setting_prms();
		include(CUR_CONF_PATH . 'lib/venue.class.php');
		$this->obj = new Ticket();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function create()
	{	
		$this->verify_content_prms(array('_action'=>'perform_manage')); //操作权限
		$show_id = intval($this->input['show_id']);
		if(!$show_id)
		{
			$this->errorOutput('演出id不存在');
		}
		
		$show_time = strtotime((trim($this->input['show_time'])));
		if(!$show_time)
		{
			$this->errorOutput("请填场次时间");
		}
		
		$info = array();
		$info = array(
			'show_time'			=> $show_time,
			'show_id'			=> $show_id,
            'brief'				=> addslashes(trim($this->input['brief'])),
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
		);
		
		$id = $this->obj->create($info,'performances');
		$info['id'] = $id;
		if($id)
		{
			//更新排序id
			$this->obj->update("order_id = {$id}", 'performances', "id={$id}");
			
			$price_id = $this->input['price_id'];
			
			if($price_id)
			{
				$add_arr = array();
				foreach ($price_id as $k => $v)
				{
					$add_arr[$k]['price'] 				= $this->input['price'][$k];
					$add_arr[$k]['price_notes'] 		= $this->input['price_notes'][$k];
					$add_arr[$k]['goods_total'] 		= $this->input['goods_total'][$k];
					$add_arr[$k]['goods_total_left'] 	= $this->input['goods_total_left'][$k];
				}
			}
			
			//新增票
			if($add_arr)
			{
				$this->add_price($add_arr, $show_id, $id);
			}
		}
		
		$this->addItem($info);
		$this->output();
	}
	
	function update()
	{	
		$this->verify_content_prms(array('_action'=>'perform_manage')); //操作权限
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//演出id
		$show_id = intval($this->input['show_id']);
		if(!$show_id)
		{
			$this->errorOutput('演出id不存在');
		}
		
		//场次时间
		$show_time = strtotime((trim($this->input['show_time'])));
		if(!$show_time)
		{
			$this->errorOutput("请填场次时间");
		}
		
		$info = array();
		$info = array(
			'show_time'			=> $show_time,
			'show_id'			=> $show_id,
            'brief'				=> addslashes(trim($this->input['brief'])),
			//'org_id'			=> $this->user['org_id'],
			//'user_id'			=> $this->user['user_id'],
			//'user_name'			=> $this->user['user_name'],
			//'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,
		);
	
		$ret = $this->obj->update($info,'performances',"id={$id}");
		
		if(!$ret)
		{
			$this->errorOutput('更新失败');
		}
		
		$price_id = $this->input['price_id'];
		if($price_id)
		{
			$price_info = array();
			foreach ($price_id as $k => $v)
			{
				if($v == 'add')
				{
					$add_arr[$k]['price'] 				= $this->input['price'][$k];
					$add_arr[$k]['price_notes'] 		= $this->input['price_notes'][$k];
					$add_arr[$k]['goods_total'] 		= $this->input['goods_total'][$k];
					$add_arr[$k]['goods_total_left'] 	= $this->input['goods_total_left'][$k];
				}
				$price_info[$v]['price'] 			= $this->input['price'][$k];
				$price_info[$v]['price_notes'] 		= $this->input['price_notes'][$k];
				$price_info[$v]['goods_total'] 		= $this->input['goods_total'][$k];
				$price_info[$v]['goods_total_left'] = $this->input['goods_total_left'][$k];
			}
		}
		//查询场次下的票
		$sql = "SELECT id FROM " . DB_PREFIX . "price WHERE perform_id = " . $id;
		$q = $this->db->query($sql);
		
		$price_id_old = array();
		while ($r = $this->db->fetch_array($q))
		{
			$price_id_old[] = $r['id'];
		}
		
		$del_arr = array_diff($price_id_old, $price_id);
		$upd_arr = array_intersect($price_id,$price_id_old);
		
		
		//更新票信息
		if($upd_arr)
		{
			foreach ($upd_arr as $pid)
			{
				if(!$price_info[$pid])
				{
					continue;
				}
				$v = '';
				$v = $price_info[$pid];
				$sql = "UPDATE ".DB_PREFIX."price SET
						price 				= '" . $v['price'] . "' ,
						price_notes 		= '" . $v['price_notes'] . "' ,
						goods_total 		= '" . $v['goods_total'] . "' ,
						goods_total_left 	= '" . $v['goods_total_left'] . "'
						WHERE id = " . $pid;
				$this->db->query($sql);
			}
		}
		//删除票信息
		if($del_arr)
		{
			$del_ids = implode(',',$del_arr);
			$sql = 'DELETE FROM '.DB_PREFIX.'price WHERE id IN ('.$del_ids.')';
			$this->db->query($sql);
		}
		
		//新增票
		if($add_arr)
		{
			$this->add_price($add_arr, $show_id, $id);
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function sort()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "performances SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	
	
	function delete()
	{			
		$this->verify_content_prms(array('_action'=>'perform_manage')); //操作权限
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput(NOID);
		}
		/*$sql = "SELECT count(*) as total FROM ".DB_PREFIX."price WHERE perform_id IN ({$ids})";
		$res = $this->db->query_first($sql);
	
		if($res['total'])
		{
			$this->errorOutput('请先删除场次下票记录');
		}*/
		
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('performances',$where);
		
		if($ret)
		{
			$sql = "DELETE FROM " . DB_PREFIX . "price WHERE perform_id IN (" . $ids . ")";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}
	
	/**
	 * 添加票
	 * Enter description here ...
	 * @param array $add_arr 票信息
	 * @param int $show_id	演出id
	 * @param int $id		场次id
	 */
	public function add_price($add_arr,$show_id,$id)
	{
		if(!$add_arr)
		{
			return FALSE;
		}
		$ip				= $this->user['ip'];
		$org_id 		= $this->user['org_id'];
		$user_id 		= $this->user['user_id'];
		$user_name		= $this->user['user_name'];
		
		$add_sql = "INSERT INTO ".DB_PREFIX."price (price, price_notes, show_id, perform_id, goods_total, goods_total_left, org_id, user_id, user_name, ip, create_time, update_time) VALUES";
		foreach ($add_arr as $v)
		{
			if(empty($v))
			{
				continue;
			}
			$vals.= "('".$v['price']."','" . $v['price_notes'] . "', ".$show_id.", ".$id.", '".$v['goods_total']."','".$v['goods_total_left']."','".$org_id."','".$user_id."','".$user_name."','".$ip."','".TIMENOW."','".TIMENOW."'),";
		}
		if($vals)
		{
			$vals = rtrim($vals,',');
			$add_sql .= $vals;
			$this->db->query($add_sql);
		}
	}
	
	
	public function audit()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			return false;
		}
		
		$audit = intval($this->input['audit']);
		switch ($audit)
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;//审核
		}
		
		$sql = " UPDATE " .DB_PREFIX. "performances SET status = '" .$status. "' WHERE id IN ('" .$id. "')";
		$this->db->query($sql);
		$data = array('status' => $status,'id' => $id);
		
		$this->addItem($data);
		$this->output();
	}
	public function publish(){}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

include(ROOT_PATH . 'excute.php');

?>
