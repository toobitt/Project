<?php
define ( 'MOD_UNIQUEID', 'pay_order' );
require ('./global.php');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class OrderAPI extends adminReadBase {
	private $obj = null;
	private $tbname = 'order';
	private $stateconfig = array ();
	public function __construct() {
		parent::__construct ();
		$this->obj = new Core ();
		$this->stateconfig = $this->settings ['order_status'];
		$this->trace_stepconfig = $this->settings ['trace_step'];
		$this->mPrmsMethods = array(
			'show'	=>'查看',
			'manage'=>'管理',
		);
	}
	

	/**
	 * 返回订单详情
	 * 
	 * @param int id 订单id
	 * @param order_type string 订单类型
	 * @return json
	 */
	public function detail() {
		if (! isset ( $this->input ['id'] ) || ! $this->input ['id']) {
			$this->errorOutput ( NO_ID );
		}
		/***************权限*****************/
    		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND o.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND o.org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***********************************/
		$id = intval ( $this->input ['id'] );
		$cond = " WHERE 1 AND o.id=" .$id.$condition;
		$query = "SELECT 
                  o.*,
                  contact.email as contact_email,
                  contact.phone as contact_phone,
                  contact.address as contact_address,
                  contact.telphone as contact_telphone,
                  contact.contact_name as nameofcontact,
                  consignee.email as consignee_email,
                  consignee.phone as consignee_phone,
                  consignee.address as consignee_address,
                  consignee.telephone as consignee_telphone,
                  consignee.consignee_name as nameofconsignee
                  FROM " . DB_PREFIX . "order o
                  LEFT JOIN " . DB_PREFIX . "order_consignee consignee 
                  ON o.id=consignee.order_id 
                  LEFT JOIN " . DB_PREFIX . "order_contact contact 
                  ON o.id=contact.order_id ";
		$re = $this->obj->query ( $query . $cond );
		$orderinfo = $re [$id];
		if (! $orderinfo) {
			exit ( json_encode ( array () ) );
		}
		$orderinfo ['is_cancel_title'] = $this->stateconfig ['is_cancel'] [$orderinfo ['is_cancel']];
		$orderinfo ['is_completed_title'] = $this->stateconfig ['is_completed'] [$orderinfo ['is_completed']];
		$orderinfo ['is_comment_title'] = $this->stateconfig ['is_comment'] [$orderinfo ['is_comment']];
		$orderinfo ['pay_status_title'] = $this->stateconfig ['pay_status'] [$orderinfo ['pay_status']];
		$orderinfo ['order_status_title'] = $this->stateconfig ['order_status'] [$orderinfo ['order_status']];
		$orderinfo ['submit_time'] = date ( "Y-m-d H:i", $orderinfo ['submit_time'] );
		$orderinfo ['pay_start_time'] = date ( "Y-m-d H:i", $orderinfo ['pay_start_time'] );
		$orderinfo ['pay_end_time'] = date ( "Y-m-d H:i", $orderinfo ['pay_end_time'] );
		$orderinfo ['goods_out_time'] = date ( "Y-m-d H:i", $orderinfo ['goods_out_time'] );
		$orderinfo ['goods_wait_time'] = date ( "Y-m-d H:i", $orderinfo ['goods_wait_time'] );
		$orderinfo ['completed_time'] = date ( "Y-m-d H:i", $orderinfo ['completed_time'] );
		$orderinfo ['goodslist'] = $this->get_goodslist ( $id );
		$this->addItem ( $this->formatdata($orderinfo) );
		$this->output ();
	}
	function formatdata($data = array()) {
		$return = array ();
		if ($data) {
			foreach ( $data as $k => $v ) {
				if (is_array ( $v )) {
					$return [$k] = $this->formatdata ( $v );
				} else {
					$return [$k] = $this->formatvalue ( $v );
				}
			}
		}
		return $return;
	}
	function formatvalue($v){
		if(!$v){
			return "";
		}
		return $v;
	}
	
	public function show() {
		$condition = $this->get_condition ();
		$offset = $this->input ['offset'] ? $this->input ['offset'] : 0;
		$count = $this->input ['count'] ? intval ( $this->input ['count'] ) : 20;
		$data_limit = $condition . ' order by o.id desc LIMIT ' . $offset . ' , ' . $count;
		$query = "SELECT 
                  o.*,
                  contact.email as contact_email,
                  contact.phone as contact_phone,
                  contact.address as contact_address,
                  contact.telphone as contact_telphone,
                  contact.contact_name as nameofcontact,
                  consignee.email as consignee_email,
                  consignee.phone as consignee_phone,
                  consignee.address as consignee_address,
                  consignee.telephone as consignee_telphone,
                  consignee.consignee_name as nameofconsignee
                  FROM " . DB_PREFIX . "order o
                  LEFT JOIN " . DB_PREFIX . "order_consignee consignee 
                  ON o.id=consignee.order_id 
                  LEFT JOIN " . DB_PREFIX . "order_contact contact 
                  ON o.id=contact.order_id ";
		$datas = $this->obj->query ( $query . $data_limit );
		if (! $datas) {
			exit ( json_encode ( array () ) );
		}
		foreach ( $datas as $k => $v ) {
			$v ['is_cancel_title'] = $this->stateconfig ['is_cancel'] [$v ['is_cancel']];
			$v ['is_completed_title'] = $this->stateconfig ['is_completed'] [$v ['is_completed']];
			$v ['is_comment_title'] = $this->stateconfig ['is_comment'] [$v ['is_comment']];
			$v ['pay_status_title'] = $this->stateconfig ['pay_status'] [$v ['pay_status']];
			$v ['order_status_title'] = $this->stateconfig ['order_status'] [$v ['order_status']];
			$v ['submit_time'] = date ( "Y-m-d H:i", $row ['submit_time'] );
			$v ['pay_start_time'] = date ( "Y-m-d H:i", $row ['pay_start_time'] );
			$v ['pay_end_time'] = date ( "Y-m-d H:i", $row ['pay_end_time'] );
			$v ['goods_out_time'] = date ( "Y-m-d H:i", $row ['goods_out_time'] );
			$v ['goods_wait_time'] = date ( "Y-m-d H:i", $row ['goods_wait_time'] );
			$v ['completed_time'] = date ( "Y-m-d H:i", $row ['completed_time'] );
			
			$v ['delivery_tracing_title'] = $this->trace_stepconfig [$v ['delivery_tracing']];
			$this->addItem ( $v );
		}
		$this->output ();
	}
	public function count() {
		$condition = $this->get_condition ();
		$info = $this->obj->count ( $this->tbname . " o", $condition );
		echo json_encode ( $info );
	}
	public function index() {
		
	}
	private function get_goodslist($order_id = 0) {
		if (! $order_id) {
			return array ();
		}
		$cond = " WHERE 1 AND `order_id`=$order_id";
		$query = "SELECT *
                  FROM `" . DB_PREFIX . "goodslist` 
                  $cond";
		$result = $this->obj->query ( $query );
		
		if (! is_array ( $result ) || ! $result) {
			return array ();
		}
		
		$goods_ids = array_keys ( $result );
		
		$extends = $this->get_goodsextendtion ( $goods_ids );
		
		foreach ( $extends as $extend ) {
			$result [$extend ['goods_id']] [$extend ['field']] [] = $extend ['value'];
		}
		
		return $result;
	}
	private function get_goodsextendtion($goods_ids) {
		if (! $goods_ids)
			return array ();
		if (is_array ( $goods_ids ) && $goods_ids) {
			$goods_ids = implode ( ',', $goods_ids );
		}
		$query = "SELECT * 
                  FROM `" . DB_PREFIX . "goodsextensionvalue` 
                  WHERE goods_id in ($goods_ids)";
		
		$result = $this->obj->query ( $query );
		if (! is_array ( $result ) || ! $result) {
			return array ();
		}
		return $result;
	}
	
	private function get_condition() {
		$condition = " where 1 ";
		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND o.user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND o.org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***** 权限 *****/
		if (isset ( $this->input ['title'] ) && $this->input ['title']) {
			$condition .= " and o.title like '%" . $this->input ['title'] . "%' or o.order_id='{$this->input['title']}'";
		}
		
		/**
		 * 支付状态
		 */
		if (isset ( $this->input ['pay_status'] ) && ($this->input ['pay_status'] > 0)) {
			$condition .= " and o.pay_status=" . $this->input ['pay_status'];
		}
		
		/**
		 * 支付状态
		 */
		if (isset ( $this->input ['session'] ) && $this->input ['session']) {
			$condition .= " and gev.session=" . $this->input ['session']; // goodsextensionvalue
		}
		
		/**
		 * 配送跟踪
		 */
		if (isset ( $this->input ['trace_step'] ) && ($this->input ['trace_step'] > 0)) {
			$condition .= " and o.delivery_tracing=" . $this->input ['trace_step'];
		}
		
		// 查询发布的时间
		if ($this->input ['date_search']) {
			$today = strtotime ( date ( 'Y-m-d' ) );
			$tomorrow = strtotime ( date ( 'Y-m-d', TIMENOW + 24 * 3600 ) );
			switch (intval ( $this->input ['date_search'] )) {
				case 1 : // 所有时间段
					break;
				case 2 : // 昨天的数据
					$yesterday = strtotime ( date ( 'y-m-d', TIMENOW - 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $yesterday . "' AND o.create_time < '" . $today . "'";
					break;
				case 3 : // 今天的数据
					$condition .= " AND  o.create_time > '" . $today . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				case 4 : // 最近3天
					$last_threeday = strtotime ( date ( 'y-m-d', TIMENOW - 2 * 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $last_threeday . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				case 5 : // 最近7天
					$last_sevenday = strtotime ( date ( 'y-m-d', TIMENOW - 6 * 24 * 3600 ) );
					$condition .= " AND  o.create_time > '" . $last_sevenday . "' AND o.create_time < '" . $tomorrow . "'";
					break;
				default : // 所有时间段
				        if ($this->input['start_time'] == $this->input['end_time']) 
				        {
							$his = date('His', strtotime($this->input['start_time']));
							if (! intval($his)) 
							{
								$this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])). ' 00:00';
								$this->input['end_time'] = date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59';
							}
       				 }
					//查询创建的起始时间
					if($this->input['start_time'])
					{
						$start_time = strtotime($this->input['start_time']);
						$condition .= " AND o.create_time >= " . $start_time;
					}
			
					//查询创建的结束时间
					if($this->input['end_time'])
					{
						$end_time = strtotime(date('Y-m-d', strtotime($this->input['end_time'])). ' 23:59:59');
						$condition .= " AND o.create_time <= " . $end_time;
						$start_time > $end_time && $this->errorOutput('搜索开始时间不能大于结束时间');
					}
					break;
			}
		}
		return $condition;
	}

    public function update_webviewurl()
    {
    		/**权限**/
    		$this->verify_content_prms(array('_action'=>'manage'));
    		/*******/
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('No id');
        }
        $url = $this->input['webview_url'];

        $sql = "UPDATE " . DB_PREFIX . "order SET webview_url = '".$url."' WHERE id = " . $id;
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

	
    //更新快递信息
    public function express_update()
    {
    	$id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('No id');
        }
        $express_name = $this->input['express_name'];
        $express_no	= $this->input['express_no'];

        $sql = "UPDATE " . DB_PREFIX . "order SET express_name = '".$express_name."',express_no = '" . $express_no . "' WHERE id = " . $id;
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

	public function unknow() {
		$this->errorOutput ( NO_ACTION );
	}
}

$out = new OrderAPI ();
$action = $_INPUT ['a'];
if (! method_exists ( $out, $action )) {
	$action = 'unknow';
}
$out->$action ();
?>