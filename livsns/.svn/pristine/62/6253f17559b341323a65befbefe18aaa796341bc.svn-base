<?php
/*******************************************************************
 * filename :orderupdate.php
 * 订单创建、取消
 * Created  :2014年5月27日,Writen by scala
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
class DeliveryTracingUpdateAPI extends  adminUpdateBase {

    //表名
    private $tbname = 'delivery_tracing';
    

    public function __construct() {
        parent::__construct();
        //dao层
        $this -> delivery_tracing_conf = $this -> settings['trace_step'];       //订单状态
                        array(
                            0 => "确认订单", 
                            1 => "打印票据", 
                            2 => "打包", 
                            3 => "出库", 
                            4 => "货运中", 
                            5 => "到达配送站", 
                            6 => "指定配送人员", 
                            7 => "配送", 
                            8 => "签收", 
                            9 => "完成");
        $this -> obj = new Core();
    }

    /**
     * 验证登录
     */
    private function checklogin() {
        if (@!$this -> user['user_id']) {
            $this -> errorOutput("NO_LOGIN");
        }
    }

    public function update() {
    		/**权限**/
    		$this->verify_content_prms(array('_action'=>'manage'));
    		/*******/
        if (!isset($this -> input['id']) || !$this -> input['id']) {
            $this -> errorOutput("NO_ORDER_ID");
        }
        
        $id = (int)$this->input['id'];
        
        if(!isset($this->input['tracestep']))
        {
            $this -> errorOutput("NO_TRACE_STEP");
        }
        $tracestep = (int)$this->input['tracestep'];
        
        if(!array_key_exists($tracestep, $this -> delivery_tracing_conf)){
            $this -> errorOutput("NO_TRACE_STEP_ILLEGAL");
        }
        
        $this->get_orderinfo($id);
        
        if($this->Order['delivery_tracing']>$tracestep && !$this->settings['is_back'])
        {
            $this -> errorOutput("NO_TRACE_STEP_ERROR");
        }
        
       //
        if($tracestep==10 && $this->Order['integral_status']){
	        require_once ROOT_PATH . 'lib/class/members.class.php';
	        //echo json_encode($order[0]);exit();
	        $members = new members();
	        	if($this->Order['pay_credits'])
	        	{
	        		$re = $members->finalFrozenCredit(
	        			  $this->Order['user_id'],
	        			  $this->Order['order_id'],
	        			  'payments',
	        			  'OrderUpdate',
	        			  $this->Order['pay_credits'],
	        			  $this->Order['integral_status']
	        		);
	        	}
        }
        
        $params['ip'] = hg_getip();
        $params['user_id'] = $this->user['user_id'];
        $params['user_name'] = $this->user['user_name'];
        $params['create_time'] = TIMENOW;
        $params['update_time'] = TIMENOW;
        $params['order_id'] = $this->Order['id'];
        $params['order_code'] = $this->Order['order_id'];
        $params['tracestep'] = $tracestep; 
        $params['longitude'] = $this->input['longitude'];
        $params['latitude'] = $this->input['latitude'];    
        $params['id'] = $this->obj->insert('delivery_trace',$params);
        
        $up_info = array(
        		'delivery_tracing'		=> $tracestep,
        );
        if($tracestep == 4)
        {
        	$express_name = $this->input['express_name'];
        	$express_no	= $this->input['express_no'];
        
        	$up_info['express_name'] = $express_name;
        	$up_info['express_no'] = $express_no;
        }
        
        $this->obj->update('order',$up_info," where id=$id ");
        $this->addItem($params);
        $this->output();
    }
    
    /**
     * 获取订单信息
     * @param  int $id 
     * @return  
     */
    private function get_orderinfo($id)
    {
        $result = $this->obj->detail('order'," WHERE id='$id'");
        if(!$result)
        {
            $this -> errorOutput("NO_ORDER_INFO");
        }
        $this->Order = $result;
    }

    public function create() {
        return;
    }

    public function publish() {
        return;
    }

    public function delete() {
        return;
    }

    public function audit() {
        return;
    }

    public function sort() {
        return;
    }

    public function unknow() {
        $this -> errorOutput(NO_ACTION);
    }

    /**
     * 创建curl
     */
    private function create_curl_obj($app_name) {
        $key = 'App_' . $app_name;
        if (!$this -> settings[$key]) {
            return false;
        }
        return new curl($this -> settings[$key]['host'], $this -> settings[$key]['dir']);
    }

    private function init_curl($curlprefix = '') {
        $curl = $curlprefix . "curl";
        $this -> $curl -> setSubmitType('post');
        $this -> $curl -> setReturnFormat('json');
        $this -> $curl -> initPostData();

    }

    private function get_common_datas($params, $curlprefix = '') {
        $curl = $curlprefix . "curl";
        foreach ($params as $key => $val) {
            if ($key == 'r') {
                $re = $this -> $curl -> request($val . ".php");
                return $re;
            } else {
                $this -> $curl -> addRequestData($key, $val);
            }
        }//end foreach
    }

    private function array_to_add($str, $data, $curlprefix = '') {
        $curl = $curlprefix . "curl";
        $str = $str ? $str : 'data';
        if (is_array($data)) {
            foreach ($data AS $kk => $vv) {
                if (is_array($vv)) {
                    $this -> array_to_add($str . "[$kk]", $vv, $curlprefix);
                } else {
                    $this -> $curl -> addRequestData($str . "[$kk]", $vv);
                }
            }
        }//end if
    }
    
   

}

$out = new DeliveryTracingUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>