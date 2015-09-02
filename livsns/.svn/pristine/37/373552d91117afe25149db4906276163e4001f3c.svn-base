<?php

require('global.php');
define('MOD_UNIQUEID','ticket_perform');//模块标识
define('SCRIPT_NAME', 'Perform');
class Perform extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function  show()
	{	
		$this->verify_content_prms(array('_action'=>'perform_manage')); //操作权限
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;	
		$count = $this->input['count'] ? intval($this->input['count']) : 20;				
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$condition = $this->get_condition();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "performances WHERE 1 " . $condition . $limit;
		$q = $this->db->query($sql);
		
		$info = array();
		while($r= $this->db->fetch_array($q))
		{ 	
			$perform_id[] = $r['id'];
			$week = hg_mk_weekday($r['show_time']);
			$r['show_time1'] = date('Y年m月d号',$r['show_time']);
			$r['show_time2'] = date('H:i',$r['show_time']);
			$r['show_time'] = $r['show_time1'] . ' ' .$week . ' ' . $r['show_time2'];
			$r['create_time'] = date("Y-m-d H:i",$r['create_time']);
			
			$info[$r['id']] = $r; 
			//$this->addItem($r);
		}
		if(!empty($info))
		{
			$perform_ids = implode(',', $perform_id);
			$sql = "SELECT perform_id,goods_total,goods_total_left FROM " . DB_PREFIX . "price WHERE perform_id IN (" . $perform_ids . ")";
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$goods_info[$r['perform_id']]['goods_total'] += $r['goods_total'];
				$goods_info[$r['perform_id']]['goods_total_left'] += $r['goods_total_left'];
			}
			if($goods_info)
			{
				foreach ($goods_info as $k => $v)
				{
					if($info[$k])
					{
						$info[$k]['goods_total'] = $v['goods_total'];
						$info[$k]['goods_total_left'] = $v['goods_total_left'];
					}	
				}
			}
			foreach ($info as $k => $v)
			{
				$this->addItem($v);
			}
		}
		$this->output();	
	}

	function detail()
	{	
		$this->verify_content_prms(array('_action'=>'perform_manage')); //操作权限
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."performances WHERE id = ".$id;
		$r = $this->db->query_first($sql);
		if(!$r)
		{
			return FALSE;
		}
		$r['show_time'] = date('Y-m-d H:i',$r['show_time']);
		
		//查询场次下票价
		$sql = "SELECT id,price,price_notes,goods_total,goods_total_left FROM " . DB_PREFIX . "price WHERE perform_id = " . $id . " ORDER BY id ASC ";
		$q = $this->db->query($sql);
		$price_info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$price_info[$row['id']] = $row;
		}
		
		if(!empty($price_info))
		{
			$r['price_info'] = $price_info;
		}
		$this->addItem($r);
		$this->output();
	}

	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @return $info string 总数，json串
	 */
	public function count()
	{	
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'performances WHERE 1'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 */
	private function get_condition()
	{		
		$condition = '';
		//查询应用分组
		if($this->input['k'])
		{
			$condition .= ' AND brief LIKE "%'.trim($this->input['k']).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}
		if($this->input['show_id'])
		{
			$condition .= ' AND show_id = '.intval($this->input['show_id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND show_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND show_time <= ".$end_time;
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
					$condition .= " AND show_time >= ".$yesterday." AND show_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND show_time >= ".$today." AND show_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND show_time >= ".$last_threeday." AND show_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND show_time >= ".$last_sevenday." AND show_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .=" ORDER BY show_time ASC,order_id DESC ";
		
		return $condition;
	}
}

include(ROOT_PATH . 'excute.php');

?>
