<?php
/*******************************************************************
 * filename :Order.php
 * 订单显示列表 详情
 * Created  :2013年8月9日,Writen by scala
 *
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class OrderAPI extends  outerReadBase {
    private $obj = null;
    private $tbname = 'order';
    private $stateconfig = array();
    public function __construct() {
        
        parent::__construct();
        $this -> obj = new Core();
        $this -> stateconfig = $this->settings['order_status'];
        $this -> trace_stepconfig = $this->settings['trace_step'];
    }

    /**
     * 返回订单详情
     * @param  int              id          订单id
     * @param  order_type       string      订单类型
     * @return array
     */
    public function detail() {
        if (!isset($this -> input['id']) || !$this -> input['id']) {
            $this -> errorOutput(NO_ID);
        }

        $id = intval($this -> input['id']);
        if(!$this->input['exchange_tag'])
        {
        	 $cond = " where 1 and id=$id and user_id=" . $this -> user['user_id'];
        }
       	else 
       	{
       		$cond = " where 1 and id=$id";
       	}

        $orderinfo = $this -> obj -> detail($this -> tbname, $cond);
        if (!$orderinfo) {
            exit(json_encode(array()));
        }

        $orderinfo['is_cancel_title'] = $this -> stateconfig['is_cancel'][$orderinfo['is_cancel']];
        $orderinfo['is_completed_title'] = $this -> stateconfig['is_completed'][$orderinfo['is_completed']];
        $orderinfo['is_comment_title'] = $this -> stateconfig['is_comment'][$orderinfo['is_comment']];
        $orderinfo['pay_status_title'] = $this -> stateconfig['pay_status'][$orderinfo['pay_status']];
        $orderinfo['order_status_title'] = $this -> stateconfig['order_status'][$orderinfo['order_status']];

        $orderinfo['submit_time'] = date("Y-m-d H:i", $orderinfo['submit_time']);
        $orderinfo['pay_start_time'] = date("Y-m-d H:i", $orderinfo['pay_start_time']);
        $orderinfo['pay_end_time'] = date("Y-m-d H:i", $orderinfo['pay_end_time']);
        $orderinfo['goods_out_time'] = date("Y-m-d H:i", $orderinfo['goods_out_time']);
        $orderinfo['goods_wait_time'] = date("Y-m-d H:i", $orderinfo['goods_wait_time']);
        $orderinfo['completed_time'] = date("Y-m-d H:i", $orderinfo['completed_time']);
        
        $orderinfo['contact'] = $this -> get_contact($id);
        $orderinfo['consignee'] = $this -> get_consignee($id);
        
        $orderinfo['bill'] = $this -> get_bill($id);
        $orderinfo['goodslist'] = $this -> get_goodslist($id);
        
        $orderinfo['delivery'] = $this -> get_delivery($id);
        
        $orderinfo['indexpic'] = json_decode(urldecode($orderinfo['indexpic']),1);
        
        $orderinfo['delivery_tracing_title'] = $this->trace_stepconfig[$orderinfo['delivery_tracing']];
        foreach($orderinfo as $key=>$val)
        {
            $this->addItem_withkey($key,$val);
        }
        
        $this -> output();
    }

    public function show() {
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit =  'where 1 and o.user_id='.$this->user['user_id'].' order by o.id desc LIMIT ' . $offset . ' , ' . $count;
        
        $query = "SELECT o.*,g.extensions as extensions 
                  FROM ".DB_PREFIX."order o
                  LEFT JOIN ".DB_PREFIX."goodslist g
                  ON o.id=g.order_id 
                  ";
        //$datas = $this -> obj -> show($this -> tbname, $data_limit, $fields = '*');
        $datas = $this -> obj ->query($query.$data_limit);
        if (!$datas) {
            exit(json_encode(array()));
        }
        
        foreach ($datas as $k => $v) {
            $v['is_cancel_title'] = $this -> stateconfig['is_cancel'][$v['is_cancel']];
            $v['is_completed_title'] = $this -> stateconfig['is_completed'][$v['is_completed']];
            $v['is_comment_title'] = $this -> stateconfig['is_comment'][$v['is_comment']];
            $v['pay_status_title'] = $this -> stateconfig['pay_status'][$v['pay_status']];
            $v['order_status_title'] = $this -> stateconfig['order_status'][$v['order_status']];
            
            $v['extensions'] = json_decode(urldecode($v['extensions']),1);
            $v['indexpic'] = json_decode(urldecode($v['indexpic']),1);
            
            $v['submit_time'] = date("Y-m-d H:i", $row['submit_time']);
            $v['pay_start_time'] = date("Y-m-d H:i", $row['pay_start_time']);
            $v['pay_end_time'] = date("Y-m-d H:i", $row['pay_end_time']);
            $v['goods_out_time'] = date("Y-m-d H:i", $row['goods_out_time']);
            $v['goods_wait_time'] = date("Y-m-d H:i", $row['goods_wait_time']);
            $v['completed_time'] = date("Y-m-d H:i", $row['completed_time']);
            $v['delivery_tracing_title'] = $this->trace_stepconfig[$v['delivery_tracing']];
            $this -> addItem($v);
        }
//echo json_encode($this->trace_stepconfig);exit();
        $this -> output();
    }

    public function count() {
        $condition = $this -> get_condition();
        $info = $this -> obj -> count($this -> tbname, $condition);
        echo json_encode($info);
    }

    public function index() {

    }


    private function get_goodslist($order_id = 1) {
        $cond = " where 1 and order_id=$order_id and user_id=" . $this -> user['user_id'];
        
        //$cond = " WHERE 1 AND `order_id`=$order_id";
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "goodslist` 
                  $cond";
        $result = $this -> obj -> query($query);
        if (!is_array($result) || !$result) {
            return array();
        }

        $goods_ids = array_keys($result);

        $extends = $this -> get_goodsextension($goods_ids);

        foreach ($extends as $extend) {
            
            $result[$extend['goods_id']]['extension'][$extend['field']][] = $extend['value'];
        
        }
        $re = array();
        foreach($result as $k=>$v)
        {
            $v['extensions'] = json_decode(urldecode($v['extensions']),1);
            $v['indexpic'] = json_decode(urldecode($v['indexpic']),1);
            
            $re[] = $v;
        }
        return $re;
    }
    
    private function get_consignee($order_id = 1) {
        $cond = " where 1 and order_id=$order_id and user_id=" . $this -> user['user_id'];
        
        //$cond = " WHERE 1 AND `order_id`=$order_id";
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "order_consignee`
                  $cond";
                  
        $result = $this -> obj -> query($query);

        if (!is_array($result) || !$result) {
            return array();
        }
        foreach($result as $k=>$v)
        {
            return $v;
        }
        
    }
    
    private function get_delivery($order_id)
    {
        $cond = " WHERE 1 AND `order_id`=$order_id";
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "order_delivery`
                  $cond";
                  
        $result = $this -> obj -> query($query);

        if (!is_array($result) || !$result) {
            return array();
        }
        foreach($result as $k=>$v)
        {
            return $v;
        }
    }
    
    private function get_bill($order_id = 1) {
        $cond = " where 1 and order_id=$order_id and user_id=" . $this -> user['user_id'];
        
        //$cond = " WHERE 1 AND `order_id`=$order_id";
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "order_bill`
                  $cond";
                  
        $result = $this -> obj -> query($query);

        if (!is_array($result) || !$result) {
            return array();
        }
        foreach($result as $k=>$v)
        {
            return $v;
        }
        
    }

    private function get_contact($order_id = 1) {
        $cond = " where 1 and order_id=$order_id and user_id=" . $this -> user['user_id'];
        
       // $cond = " WHERE 1 AND `order_id`=$order_id";
        $query = "SELECT *
                  FROM `" . DB_PREFIX . "order_contact` 
                  $cond";
                  
        $result = $this -> obj -> query($query);

        if (!is_array($result) || !$result) {
            return array();
        }
        foreach($result as $k=>$v)
        {
            return $v;
        }
        
    }

    private function get_goodsextension($goods_ids) {
        if (!$goods_ids)
            return array();
        if (is_array($goods_ids) && $goods_ids) {
            $goods_ids = implode(',', $goods_ids);
        }
        $query = "SELECT * 
                  FROM `" . DB_PREFIX . "goodsextensionvalue` 
                  WHERE goods_id in ($goods_ids)";
        $result = $this -> obj -> query($query);
        if (!is_array($result) || !$result) {
            return array();
        }
        return $result;
    }

    private function get_condition() {
        $cond = " where 1 and user_id=" . $this -> user['user_id'];
        //$cond = " where 1 ";
        return $cond;
    }

    public function exchange_code()
    {
    	$exchange_code = trim($this->input['exchange_code']);
    	
    	if(!$exchange_code)
    	{
    		$this->errorOutput('请输入兑换码');
    	}
    	
    	$ret = $this -> obj -> exchange_code_verify($exchange_code);
    	 
    	 if(!$ret['id'])
    	 {
    	 	$this->errorOutput('未找到对应订单');
    	 }
    	 elseif ($ret['delivery_tracing'] == 10)
    	 {
    	 	$this->errorOutput('订单已完成');
    	 }
    	 
    	 if($this->input['verify_exchange_code'])
    	 {
    	 	$sql = "UPDATE " . DB_PREFIX . "order SET delivery_tracing = 10 WHERE id = '{$ret['id']}'";
		 	$this->db->query($sql);
		 	
		 	$this->addItem('success');
		 	
		 	$this->output();
    	 }
			
    	 $this->input['id'] = $ret['id'];
    	 $this->input['exchange_tag'] = 1;
    	 $this->detail();
    	
    }
    
    //查询自取商品
	public function pick_up_show() 
	{
        $offset = $this -> input['offset'] ? $this -> input['offset'] : 0;
        $count = $this -> input['count'] ? intval($this -> input['count']) : 20;
        $data_limit =  'where 1 and o.exchange_code !="" order by o.id desc LIMIT ' . $offset . ' , ' . $count;
        
        $query = "SELECT o.*,g.extensions as extensions 
                  FROM ".DB_PREFIX."order o
                  LEFT JOIN ".DB_PREFIX."goodslist g
                  ON o.id=g.order_id 
                  ";
        $datas = $this -> obj ->query($query.$data_limit);
        if (!$datas) {
            exit(json_encode(array()));
        }
        
        foreach ($datas as $k => $v) {
            $v['is_cancel_title'] = $this -> stateconfig['is_cancel'][$v['is_cancel']];
            $v['is_completed_title'] = $this -> stateconfig['is_completed'][$v['is_completed']];
            $v['is_comment_title'] = $this -> stateconfig['is_comment'][$v['is_comment']];
            $v['pay_status_title'] = $this -> stateconfig['pay_status'][$v['pay_status']];
            $v['order_status_title'] = $this -> stateconfig['order_status'][$v['order_status']];
            
            $v['extensions'] = json_decode(urldecode($v['extensions']),1);
            $v['indexpic'] = json_decode(urldecode($v['indexpic']),1);
            
            $v['submit_time'] = date("Y-m-d H:i", $row['submit_time']);
            $v['pay_start_time'] = date("Y-m-d H:i", $row['pay_start_time']);
            $v['pay_end_time'] = date("Y-m-d H:i", $row['pay_end_time']);
            $v['goods_out_time'] = date("Y-m-d H:i", $row['goods_out_time']);
            $v['goods_wait_time'] = date("Y-m-d H:i", $row['goods_wait_time']);
            $v['completed_time'] = date("Y-m-d H:i", $row['completed_time']);
            $v['delivery_tracing_title'] = $this->trace_stepconfig[$v['delivery_tracing']];
            $this -> addItem($v);
        }
        $this -> output();
    }
    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

}

$out = new OrderAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>
