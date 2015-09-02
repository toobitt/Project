<?php

require('global.php');
define('MOD_UNIQUEID','ticket_venue');//模块标识
define('SCRIPT_NAME', 'Venue');
class Venue extends adminReadBase
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
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;	
		$count = $this->input['count'] ? intval($this->input['count']) : 20;				
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		
		$condition = $this->get_condition();
		
		$sql = "SELECT * FROM " . DB_PREFIX . "venue WHERE 1 " . $condition . $limit; 
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{ 	
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			
			$this->addItem($row);
		}
		$this->output();	
	}

	function detail()
	{	
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM ".DB_PREFIX."venue WHERE id = ".$id;
		$info = $this->db->query_first($sql);
		
		$this->addItem($info);
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
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'venue WHERE 1'.$this->get_condition();
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
			$condition .= ' AND venue_name LIKE "%'.trim($this->input['k']).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' WHERE id = '.intval($this->input['id']);
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim($this->input['start_time']));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim($this->input['end_time']));
			$condition .= " AND create_time <= ".$end_time;
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
					$condition .= " AND create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		$condition .=" ORDER BY order_id DESC,create_time DESC ";
		
		return $condition;
	}
	
	//获取城市
	public function get_city_name()
	{	
		$ret = DEFAULT_CITY_NAME;
		$this->addItem($ret);
		$this->output();
	}
	
	//根据地图坐标获取地址信息
	public function get_address_by_xy()
	{
		if(!$this->input['location'])
		{
			$this->errorOutput(NO_DATA);
		}
		$url = BAIDU_GEOCODER_DOMAIN . 'ak='  . BAIDU_AK. '&location=' .$this->input['location']. '&output=json';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$address = json_decode($response,1);
		$address_arr = $address['result']['addressComponent'];
		if($address_arr)
		{
			$street = $address_arr['city'] . $address_arr['district'].$address_arr['street'].$address_arr['street_number'];
		}
		$this->addItem(array('address' => $street));
		$this->output();
	}
}

include(ROOT_PATH . 'excute.php');

?>
