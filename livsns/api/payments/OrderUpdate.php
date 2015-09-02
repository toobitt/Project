<?php
/*******************************************************************
 * filename :orderupdate.php
 * 订单创建、取消
 * Created  :2014年4月22日,Writen by scala
 ******************************************************************/
require ('./global.php');
define('MOD_UNIQUEID', 'pay_order');
require_once CUR_CONF_PATH . 'core/Core.class.php';
include_once(ROOT_PATH . 'lib/class/curl.class.php');
class OrderUpdateAPI extends  outerUpdateBase {
    private $obj = null;

    private $tbname = 'order';                  //表名

    private $GoodsInfos = array();              //商品信息
    
    private $GoodsValues = array();             //商品价格

    private $BundleGoods = array();             //对应应用商品

    private $Consignee = array();               //收货人信息
    
    private $Paymethod = 1;                     //支付方式    1.Money 2.Credits 3.Credits+Money
    
    private $Orderinfo = array();               //订单详情
    
    public function __construct() {
        parent::__construct();
        $this -> obj = new Core();                                      //dao层
        $this -> stateconfig = $this -> settings['order_status'];       //订单状态
//         print_r($this->input);exit;
    }

    /**
     * 验证登录
     */
    private function checklogin()
    {
        if(@!$this->user['user_id'])
        {
            $this -> errorOutput("NO_LOGIN");
        }
    }
    
    public function create() {
//     	print_r($this -> GoodsValues);exit;
        $this -> checklogin();                  //验证登录
        
        $this -> verifydelivery();              //配送方式验证
        
        $this -> verifydelivery_fee();          //运费验证
        
        $this -> verifyconsignee();             //收货人验证
       
        $this -> verifypaymethod();             //支付方式验证

        $this -> verifybill();                  //发票验证
       
        $this -> verifygoods();                 //商品验证
        
        $this -> cumpterValue();                //计算商品的价格,商品数量等信息
     
        $this -> verifycanpay();                //验证积分是否够支付
        
        $this -> main_create();                 //创建订单
       // echo 'create';exit;
    }
    
   /**
     * 效验是否可以支付主要针对积分部分即本系统内容，对应涉及到资金的暂时无法处理 即暂时对支付方式为2、3处理
     */
    private function verifycanpay()
    {
        //获取用户信息
        $re = $this->getuser();//用户字段中credit1表示积分
       // print_r((int)$re[0]['credit']['credit1']);exit;
        if(($this->Paymethod&2)==2||(($this->Paymethod&3)==3)){
            if((int)$re[0]['credit']['credit1']<$this->GoodsValues['credits_value']) 
            {
                $this -> errorOutput("NO_CREDITS_ENOUGH");
            }          
        }
    }
    
    /**
     * 支付方式
     * 1.money
     * 2.credits
     * 3.money+credits
     */
    private function payafter(){  	
        if(($this->Paymethod&2)==2){
            
            $credits = $this->GoodsValues['credits_value'];
            if($credits==0)
            {
                $paymethod = $this->Orderinfo['paymethod'];

                $payprocess = $this->Orderinfo['payprocess'];
                $payprocess |= 2;

                if($payprocess==$paymethod)
                {
                    $params['order_status'] = 25;//支付完成
                    $params['pay_status'] = 2;   //支付完成
                }
                $params['payprocess'] = $payprocess;
                $re = $this->obj -> update('order', $params, " where order_id='" . $_REQUEST['out_trade_no'] . "'");
                if($payprocess==$paymethod)
                {
                    require_once CUR_CONF_PATH . 'lib/sms.class.php';
                    $sms = new sms($this->Orderinfo['order_id']);
                    $sms -> sendsms();
                }
                return 1;
            }
            /**
            if(!$credits)
                 $this -> errorOutput("NO_CREDITS_ENOUGH");
            **/            
            //1.Money 2.Credits 3.Credits+Money
            require ROOT_PATH . 'lib/class/members.class.php';
            $members = new members();
            $url = "http://".$this -> settings['App_payments']['host']."/". $this -> settings['App_payments']['dir'];
            $url .="/callback_url.php?access_token=".$this->user['token'];
            $extend = 'out_trade_no=' . $this->Orderinfo['order_id'];
            
            $callback = array(
                'url'=>$url,
                'extend'=>$extend);
            is_array($this -> GoodsInfos)&&$appUniqueidArr = array_keys($this -> GoodsInfos);  
           $creditLogTitle = $this->input['creditLogTitle'] ? $this->input['creditLogTitle']:($this->settings['creditLogTitle'][$appUniqueidArr[0]]?$this->settings['creditLogTitle'][$appUniqueidArr[0]]:'订单支付');
            $re = $members->consume_credits(
                            $this->user['user_id'],
                            $credits,
                            $this->Orderinfo['order_id'],
                            'payments',
                            'OrderUpdate',
                            'create',
                            $this -> order_title,
                            //"支付订单:".$this->Orderinfo['order_id'],
                            $callback,
                            $creditLogTitle
                            );
            if(!$re['logid']){
                $this -> errorOutput("NO_CREDITS_ENOUGH");
            } 
        }//end if
        
    }
    

    /**
     * 收货人验证
     * 分两种情况1.上门自取  2.快递  
     * @param
     * @return  mixed
     */
    private function verifyconsignee() {
        
        //1.上门自取
        if($this->Delivery_category['mark']=='GetBySelf')
        {
            $this -> Consignee = array();
            return 1;
        }
        
        $consignee_id = intval($this -> input['consignee_id']);
        
        if (!intval($consignee_id)) {
            $this -> errorOutput("NO_CONSIGNEE_ID");
        }
        
        $query = "SELECT c.*,p.name as province_name,city.city as city_name,a.area as area_name 
                  FROM " . DB_PREFIX . "consignee c 
                  LEFT JOIN  " . DB_PREFIX . "province p 
                  ON c.province=p.id
                  LEFT JOIN " . DB_PREFIX . "city city
                  ON c.city = city.id 
                  LEFT JOIN " . DB_PREFIX . "area a
                  ON c.area = a.id                  
                  WHERE c.id=$consignee_id 
                 ";
        $re = $this -> obj -> query($query);
        if ($re) {
            $this -> Consignee = $re[$consignee_id];
        } else {
            $this -> errorOutput("NO_HAS_NOCONSIGNEES");
        }
    }

    /**
     * 1.Money 2.Credits 3.Credits+Money
     */
    private function verifypaymethod() {
        if(!isset($this->input['paymethod'])||!$this->input['paymethod']){
              $this->Paymethod = 1;     //1.money
        }else{
            $paymethod = intval($this->input['paymethod']);
            $this->Paymethod = $paymethod;
        }
        $sys_paymethods = array(1,2,3);
        if(!in_array($this->Paymethod, $sys_paymethods))
        {
            $this -> errorOutput("NO_ILLEGAL_PAYMETHOD");	//非法支付方式
        }        
    }

    /**
     * 配送方式验证
     * @param   delivery_id int
     * @return  mixed
     */
    private function verifydelivery() {
        if(!isset($this->input['delivery_id'])){
            $this -> errorOutput("NO_DELIVERY_ID");
        }
        
        $delivery_id = intval($this -> input['delivery_id']);
        
        if (!intval($delivery_id)) {
            $this -> errorOutput("NO_DELIVERY_ID");
        }
        
        $query = "SELECT * 
                  FROM " . DB_PREFIX . "deliverycategory 
                  WHERE id=$delivery_id";

        $re = $this -> obj -> query($query);
        
        if ($re[$delivery_id]) {
            $this -> Delivery_category = $re[$delivery_id];
        } else {
            $this -> errorOutput("NO_HAS_DELIVERY");
        }
    }

    
    /**
     * 配送方式验证
     * @param   int delivery_id
     * @return  mixed
     */
    private function verifydelivery_fee() {
        //上门自取
        if($this->Delivery_category['mark']=='GetBySelf')
        {
            $this -> Delivery_fee = 0;
            return 1;
        }
        
        if(isset($this->input['delivery_fee']))
        {
            $this->Delivery_fee = (float)$this->input['delivery_fee'];
            return;
        }
    }

    private function verifybill() {
        
        if(isset($this->input['bill_header_content']))
        {
            $this->bill_header_content = $this->input['bill_header_content'];
        }
        else
        {
            $this->bill_header_content = '';
        }      
    }

    /**
     * 商品相关校验，主要的校验有
     * 1.库存校验
     * 2.价格计算
     * @access private
     * @return mixed
     */
    private function verifygoods() {
     	
        $this -> init_goods();
       
        $Re_getStores = $this -> opBundle('getStore');
   
        $getStore_Statue = 1;
        foreach ($Re_getStores as $bundleid => $Re_getStore) {
            if ($Re_getStore['status']!=1) {
                $getStore_Statue = 2;
            }
            unset($Re_getStores[$bundleid]['total_goods_value']);
        }
        
        if ($getStore_Statue == 2) {
            $Re_getStores['ErrorCode'] = "NO_STORE";
            $Re_getStores['ErrorText'] = "库存不足";
            exit(json_encode($Re_getStores));
        }
        $this -> GoodsValues = $Re_getStores;
       
    }

    private function main_create() {

        $this -> obj -> transaction_begin();
        $update_store_status = 1;
        
        //
        //减库存
        $Re_updateStores = $this -> opBundle('updateStore', array('operation' => 'minus'));
      //  var_dump($Re_updateStores);exit;
        foreach ($Re_updateStores as $reupdateStore) {
            if (!isset($reupdateStore['status'])||$reupdateStore['status']!==1) {
                $update_store_status = 2;
                break;
            }
        }
        
        //echo json_encode($reupdateStore);exit();
        if ($update_store_status == 2) {
            $this -> obj -> transaction_end();
            exit(json_encode($reupdateStore));
        }

        $orderparams = $this -> insertIntoOrder();

        //插入数据库失败
        if (!$orderparams['id']) {
            //补库存
            $Re_Minus_updateStores = $this -> opBundle('updateStore', array('operation' => 'plus'));
            //需要处理
            $this -> obj -> transaction_end();
            $this -> errorOutput("OS_ERROR");
            exit();
        }
//      print_r($orderparams);exit;
        $update_store_status = $this -> insertIntoGoodslist($orderparams['id']);

        if ($update_store_status !== 1) {
            $Re_updateStores = $this -> opBundle('updateStore', array('operation' => 'plus'));
            $this -> obj -> transaction_rollback();
            $this -> errorOutput("OS_ERROR");
            exit();
        }
        $this -> insertIntoConsignee($orderparams['id']);
        
        $this -> insertIntoContact($orderparams['id']);

        $this -> insertIntoDelivery($orderparams['id']);

        //$this -> insertIntoOrderbill($orderparams['id']);

        $this -> obj -> transaction_end();

        $orderparams['is_cancel'] = 22;
        $orderparams['is_cancel_title'] = $this -> settings['order_status']['is_cancel'][22];
        $orderparams['pay_status'] = 1;
        $orderparams['pay_status_title'] = $this -> settings['order_status']['pay_status'][1];
        $orderparams['is_completed'] = 24;
        $orderparams['is_completed_title'] = $this -> settings['order_status']['is_completed'][24];
        $orderparams['is_comment'] = 112;
        $orderparams['is_comment_title'] = $this -> settings['order_status']['is_comment'][112];
        $orderparams['order_status'] = 21;
        $orderparams['order_status_title'] = $this -> settings['order_status']['order_status'][21];
       
        foreach ($orderparams as $key => $val) {
            $this -> addItem_withkey($key, $val);
        }
        
       // echo 'create';exit;
        $this -> payafter();                    //支付流程

        $this -> Output();
    }

    /**
     * 获取用户信息
     */ 
   private function getuser()
   {
        $key = 'App_members';
        $url = "http://".$this -> settings[$key]['host']."/". $this -> settings[$key]['dir'];
        $url .="/member.php?a=detail&access_token=".$this->user['token'];
        $re = file_get_contents($url);
        return json_decode($re,1);
    } 

    private function getphone()
    {
        if(isset($this->input['telphone'])&&$this->input['telphone'])
            return $this->input['telphone'];
        return $this -> Consignee['telephone'];
    }
    
    /**
     * 对传入的goods数据进行初步的格式化整理
     */
    private function init_goods() {
        $params = array();
        if (!isset($this -> input['goods']) || !is_array($this -> input['goods'])) {
            $this -> errorOutput("NO_CHOOSE_GOODS");
        }
        foreach ($this->input as $key => $onegoods) {
            if (!is_array($onegoods)) {
                continue;
            }
            foreach ($onegoods as $k => $goods) {
                $bundle_id = "App_" . trim($goods['bundle_id']);
                if (!array_key_exists($bundle_id, $this -> settings)) {
                  
                    $this -> errorOutput("NO_BUNDLE_ID");
                }
                
                $bundle_goods[$goods['bundle_id']][$key][$k] = $goods;
            }
        }
// print_r($bundle_goods);exit;
        foreach ($bundle_goods as $bundleid => $onebundle_datas) {
            $curl = $bundleid . "curl";
            $this -> $curl = $this -> create_curl_obj($bundleid);
            $this -> init_curl($bundleid);
            if (!is_array($onebundle_datas))
                continue;
            foreach ($onebundle_datas as $key => $value) {
                if (is_array($value)) {
                    $this -> array_to_add($key, $value, $bundleid);
                } else {
                    $params[$key] = $value;
                }
            }

            $params['a'] = "getGoodsInfo";
            $params['r'] = $bundleid;
            $Re_getGoodsInfos[$bundleid] = $this -> get_common_datas($params, $bundleid);     
        }
        
        //echo json_encode($Re_getGoodsInfos);exit();
        foreach ($Re_getGoodsInfos as $bundleid => $goodsinfos) {
            if (!$goodsinfos) {
                $this -> errorOutput("NO_GOOD_INFO");
            }
            if(isset($goodsinfos['status'])){
                $this -> errorOutput("NO_GOOD_INFO");
            }
        }

        foreach ($Re_getGoodsInfos as $goodsinfos) {
            if ($goodsinfos && is_array($goodsinfos)) {
                foreach ($goodsinfos as $goodsinfo) {
                    $this -> order_title = $goodsinfo['goods_title'];
                    $this -> order_brief = $goodsinfo['goods_brief'];
                }
            }
        }
       
        $this -> GoodsInfos = $Re_getGoodsInfos;
        
        $this -> BundleGoods = $bundle_goods;
    }

    /**
     * 该方法主要针对其他应用的操作
     * @param  String $op  操作方法 如：获取库存的方法getStore /获取商品信息的getGoodsInfo等
     * @access private
     * @return mixed
     */
    private function opBundle($op, $params = array()) {
        foreach ($this->BundleGoods as $bundleid => $onebundle_datas) {
            if (!is_array($onebundle_datas))
                continue;
            foreach ($onebundle_datas as $key => $value) {
                if (is_array($value)) {
                    $this -> array_to_add($key, $value, $bundleid);
                } else {
                    $params[$key] = $value;
                }
            }
            $params['a'] = $op;
            $params['r'] = $bundleid;
            $Re[$bundleid] = $this -> get_common_datas($params, $bundleid);
           
        }
        $state = 1;
       
        foreach ($Re as $bundle_id => $values) {
            if (@$values['status'] != 1) {
                $state = 2;
                break;
            }
        }
        
        if ($state == 2) {
            exit(json_encode($Re));
        }
        return $Re;
    }

    private function cumpterValue() {
        $total = '';
        $discount = "";
        $order_quantity = "";
        foreach ($this->GoodsValues as $bundle => $goodsValues) {
            foreach ($goodsValues as $goodsValue) {
                $total += $goodsValue['goods_number'] * $goodsValue['goods_value'];
                $discount += $goodsValue['goods_number'] * $goodsValue['goods_discount'];
                $order_quantity += $goodsValue['goods_number'];
                $credits += $goodsValue['goods_number'] * $goodsValue['credits_value'];
            }
        }
        
        
        
        $this -> GoodsValues['order_quantity'] = $order_quantity;
        $this -> GoodsValues['total_discount'] = $discount;
        $this -> GoodsValues['total_value'] = $total - $discount;
        
        $this -> GoodsValues['goods_value'] = $total - $discount;           //商品总价
        $this -> GoodsValues['delivery_fee'] = $this -> Delivery_fee;       //运费
        $this -> GoodsValues['total_value'] += $this -> Delivery_fee;       //小计
        $this -> GoodsValues['credits_value'] = $credits;          //积分小计
    }


    private function insertIntoOrder() {

        $orderparams['bill_header_content'] = $this->bill_header_content;
        $orderparams['order_id'] = generateOrderCode();
        $orderparams['fid'] = 0;
        $orderparams['froder_id'] = '';
        $orderparams['user_id'] = $this -> user['user_id'];
        $orderparams['pay_credits'] = $this -> GoodsValues['credits_value'];
        $orderparams['user_name'] = $this -> user['user_name'];
        $orderparams['title'] = stripslashes($this -> input['title']);
        $orderparams['brief'] = stripslashes($this -> input['brief']);
        $orderparams['goods_value'] = $this -> GoodsValues['goods_value'];
        $orderparams['order_value'] = $this -> GoodsValues['total_value'];
        $orderparams['order_quantity'] = $this -> GoodsValues['order_quantity'];
        $orderparams['delivery_fee'] = $this -> GoodsValues['delivery_fee'];
        $orderparams['create_time'] = TIMENOW;
        $orderparams['title'] = $this -> order_title;
        $orderparams['brief'] = $this -> order_brief;
        $orderparams['order_client_type'] = '';
        $orderparams['order_client_ip'] = hg_getip();
        $orderparams['submit_time'] = TIMENOW;
        $orderparams['update_time'] = TIMENOW;
        $orderparams['goods_out_time'] = TIMENOW;
        $orderparams['pay_start_time'] = TIMENOW;
        $orderparams['pay_end_time'] = TIMENOW;
        $orderparams['goods_wait_time'] = TIMENOW;
        $orderparams['completed_time'] = TIMENOW;
        $orderparams['paymethod'] = $this->Paymethod;
        
        $orderparams['appid'] = $this->user['appid'];
        $orderparams['appname'] = trim(($this->user['display_name']));
        $orderparams['ip'] = hg_getip();
        
        foreach($this->GoodsInfos as $bundle_id=>$datas)
        {
            foreach($datas as $k=>$v)
            {
                if($v['index_img'])
                {
                    $orderparams['indexpic'] = urlencode(json_encode($v['index_img']));
                    //$orderparams['indexpic'] = serialize($v['index_img']);
                    break;
                }
            }
        }
//        //商品为自取商品时，生成兑换码
//        if(!$this->input['pick_up_way'])
//        {
//        	$orderparams['exchange_code'] = generateExchangeCode();
//        	
//        	include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
//			$qrcode_server = new qrcode();
//			
//			$data = array('content'=>$orderparams['exchange_code']);
//			$qrcode = $qrcode_server->create($data,-1);
//			
//			$orderparams['exchange_qrcode'] = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
//        }
        
        
        $orderparams['telphone'] = $this->getphone();
        $orderparams['id'] = $this -> obj -> insert($this -> tbname, $orderparams);
        $orderparams['indexpic'] = json_decode(urldecode($orderparams['indexpic']),1);
        //$orderparams['indexpic'] = serialize($v['index_img']);
        if ($orderparams['id']) {
        	
	        /****** 商品为自取商品时，生成兑换码 ******/
	        if(!$this->input['pick_up_way'])
	        {
	        	$exchange_code = $this->input['verify_url'] ? $this->input['verify_url'].'?id='.$orderparams['id'] : generateExchangeCode();
	        	include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
				$qrcode_server = new qrcode();
				$data = array('content'=>$exchange_code);
				$qrcode = $qrcode_server->create($data,1);
				$exchange_qrcode = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
				$rows = $this->obj->update($this->tbname,array('exchange_code'=>$exchange_code,'exchange_qrcode'=>$exchange_qrcode),' WHERE id='.$orderparams['id']);
				if($rows)
				{
					$orderparams['exchange_code'] = $this->input['verify_url'] ? '' : $exchange_code;
					$orderparams['exchange_qrcode'] = $exchange_qrcode;
				}
	        }
        	/****** 商品为自取商品时，生成兑换码 ******/
        	
            $this->Orderinfo = $orderparams;
            return $orderparams;
        }
        return false;
    }

    
    /*
     * #手动更新数据库
     * #对"已支付"但是"未处理"的订单进行"兑换二维码"的重新生成
     */
    /*
    public function go()
    {
    	$star = $this->input['star']; //开始id
    	$end = $this->input['end']; //结束id
    	if(!$star || !$end)
    	{
    		$this->errorOutput('缺少参数');
    	}
		$sql = 'SELECT id FROM liv_order WHERE  id >= '.$star.' and id <= '.$end. ' and pay_status=2 and delivery_tracing != 10';
    	$ret = $this->obj->query($sql);
    	if(!$ret)
    	{
    		echo '没有数据了';exit;
    	}
//    	hg_pre($ret);exit;
		include_once(ROOT_PATH . 'lib/class/qrcode.class.php');
		$qrcode_server = new qrcode();
		$i = 0;
    	foreach((array)$ret as $k => $v)
    	{
	    	$exchange_code = 'http://pmobile.ijntv.cn/h5/jf_mall/verify.html?id='.$v['id'];
			$data = array('content'=>$exchange_code);
			$qrcode = $qrcode_server->create($data,-1);
			$exchange_qrcode = is_array($qrcode) ? hg_fetchimgurl($qrcode) : '';
			$rows = $this->obj->update($this->tbname,array('exchange_code'=>$exchange_code,'exchange_qrcode'=>$exchange_qrcode),' WHERE id='.$v['id']);
    		$i++;
    	}
    	echo 'xxxxx更新了'.$i.'条xxxxx';exit;
    }
    */
    
    
    private function insertIntoOrderbill($order_id) {
        $params['order_id'] = $order_id;
        if(isset($this->input['bill_header_content']))
        {
            $params['bill_header_content'] = trim($this -> input['bill_header_content']);
            $re = $this -> obj -> insert('order_bill', $params);
            if (!$re) {
                $update_store_status = 2;
                return $update_store_status;
            }
        }
        return true;
    }

    private function insertIntoGoodslist($order_id) {
        foreach ($this->GoodsInfos as $bundle_id => $goodsinfo) {
            $query = "SELECT * FROM " . DB_PREFIX . "goodsextension where goodstype_id = 
             (SELECT id FROM " . DB_PREFIX . "goodstype where mark='" . $bundle_id . "')";
            $extensions[$bundle_id] = $this -> obj -> query($query);

            foreach ($goodsinfo as $key => $v) {
                
                $params = array();
                $params['order_id'] = $order_id;
                $params['bundle_id'] = $bundle_id;
                $params['goods_id'] = $key;
                //$params['goods_id'] = $v['goods_id'];
                
                $params['user_id'] = $this->user['user_id'];
                $params['user_name'] = $this->user['user_name'];
                
                $params['goods_title'] = $v['goods_title'];
                $params['goods_brief'] = $v['goods_brief'];
                $params['goods_value'] = $this -> GoodsValues[$bundle_id][$key]['goods_value'];
                $params['goods_discount'] = $this -> GoodsValues[$bundle_id][$key]['goods_discount'];
                $params['goods_number'] = $this -> GoodsValues[$bundle_id][$key]['goods_number'];
                $params['indexpic'] = urlencode(json_encode($v['index_img']));
                $params['extensions'] = urlencode(json_encode($v['goods_all_info'][$v['goods_id']]));
                
                //$params['indexpic'] = serialize($v['index_img']);
                //$params['extensions'] = serialize($v['goods_all_info'][$v['goods_id']]);
                
                $re = $this -> obj -> insert('goodslist', $params);
                if (!$re) {
                    $update_store_status = 2;
                    return $update_store_status;
                }
                $params_extensions = array();
                foreach($extensions as $ek=>$ev)
                {
                        
                    if(is_array($ev)) 
                    {
                        foreach($ev as $kk=>$vv)
                        {
                            if(!$v['extension'][$vv['mark']])
                                continue;
                            $params_extensions['goodsextension_id'] = $vv['id'];
                            $params_extensions['goods_id'] = $re;
                            $params_extensions['field'] = $vv['mark'];
                            $params_extensions['value'] = $v['extension'][$vv['mark']];
                            $re = $this -> obj -> insert('goodsextensionvalue', $params_extensions);
                            if (!$re) {
                                $update_store_status = 2;
                                return $update_store_status;
                            }
                        }
                    }//end if
                      
                }//end foreach extensions
                
            }//end foreach goodsinfo
            
        }//end foreach
        
        return 1;

    }

    private function insertIntoConsignee($order_id) {
        //上门自取
        if($this->Delivery_category['mark']=='GetBySelf')
        {
            $this -> Delivery_fee = 0;
            return 1;
        }
        $Consignee_params['order_id'] = $order_id;
        $Consignee_params['user_id'] = $this->user['user_id'];
        $Consignee_params['user_name'] = $this->user['user_name'];
        $Consignee_params['user_type'] = $this->Consignee['user_type'];
        $Consignee_params['telephone'] = $this->Consignee['telephone'];
        $Consignee_params['phone'] = $this->Consigneer['phone'];
        $Consignee_params['address'] = $this->Consignee['address'];
        $Consignee_params['postcode'] = $this->Consignee['postcode'];
        $Consignee_params['email'] = $this->Consigneer['email'];
        
        $Consignee_params['province_name'] = $this->Consignee['province_name'];
        $Consignee_params['city_name'] = $this->Consignee['city_name'];
        $Consignee_params['area_name'] = $this->Consignee['area_name'];
        
        $Consignee_params['consignee_name'] = $this -> Consignee['consignee_name'];
        $this -> obj -> insert('order_consignee', $Consignee_params);
    }
    
    
    private function insertIntoContact($order_id) {
        //上门自取
        if($this->Delivery_category['mark']=='GetBySelf')
        {
            $this -> Contact = array();
            
            if (!$this -> input['contact_name']) {
                $this -> errorOutput("NO_CONTACT_NAME");
            }
        
            if($this->input['telphone'])
            {
                $Contact_params['telphone'] = trim($this->input['telphone']);
            }

            if($this->input['phone'])
            {
                $Contact_params['phone'] = trim($this->input['phone']);
            }
            
            if(isset($this->input['address']))
            {
                $Contact_params['address'] = trim($this->input['address']);
            }
            
            if(isset($this->input['email']))
            {
                $Contact_params['email'] = trim($this->input['email']);
            }
            
            if(isset($this->input['postcode']))
            {
                $Contact_params['postcode'] = trim($this->input['postcode']);
            }
            $Contact_params['order_id'] = $order_id;
            $Contact_params['user_id'] = $this->user['user_id'];
            $Contact_params['user_name'] = $this->user['user_name'];
            
            $Contact_params['user_type'] = '';
            $Contact_params['contact_name'] = $this -> input['contact_name'];
            
            $this -> obj -> insert('order_contact', $Contact_params);
        }
    }

    private function insertIntoDelivery($order_id) {
        $Delivery_params['order_id'] = $order_id;
        $Delivery_params['user_name'] = $this -> Delivery_category['user_name'];
        $Delivery_params['user_id'] = $this -> Delivery_category['user_id'];
        $Delivery_params['user_type'] = $this->Consignee['user_type'];
        $Delivery_params['title'] = $this -> Delivery_category['title'];
        $Delivery_params['brief'] = $this -> Delivery_category['brief'];
        $Delivery_params['mark'] = $this -> Delivery_category['mark'];
        $Delivery_params['delivery_fee'] = $this -> Delivery_fee;
        $this -> obj -> insert('order_delivery', $Delivery_params);
    }


    public function update() {
        if (intval($this -> input['pay_status'])) {
            $params['pay_status'] = intval($this -> input['pay_status']);
        }
        if (intval($this -> input['is_cancel']) == 1) {
            $params['pay_status'] = 6;
        }

        return;
    }

    //确认收货
	public function confirm_receipt() 
	{
		$order_id = intval($this->input['id']);
		if(!$order_id)
		{
			$this->errorOutput('订单信息异常');
		}
        //查询订单交易流程
        $sql = "SELECT delivery_tracing FROM " . DB_PREFIX . "order WHERE id = '{$order_id}'";
		$res = $this->db->query_first($sql);
		
		if($res['delivery_tracing'] >= 4)
		{
			$sql = "UPDATE " . DB_PREFIX . "order SET delivery_tracing = 9 WHERE id = '{$order_id}'";
			$this->db->query($sql);
			
			$this->addItem('success');
		}
		else 
		{
			$this->errorOutput('商品还未发货');
		}
        $this->output();
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



    public function cancel_order() {
        $this->checklogin();
        $id = intval($this -> input['id']); 
        if (!$id) {
            $this -> errorOutput(NO_ID);
        }
        $query = "SELECT g.* 
                  FROM ".DB_PREFIX."goodslist g
                  LEFT JOIN ".DB_PREFIX."order o 
                  ON g.order_id=o.id
                  WHERE
                  o.is_cancel=22 
                  and g.order_id=$id 
                  and g.user_id=". $this -> user['user_id']
                  ;
                  
        $goodses = $this->obj->query($query);
        
        $query = "SELECT *
                  FROM ".DB_PREFIX."order o 
                  WHERE
                  o.id=$id 
                  and o.user_id=". $this -> user['user_id']
                  ;
         
        $order = $this->obj->query($query,'');
        
        if(!$goodses)
        {
            $this -> addItem(0);
            $this -> Output();
        }
        
        foreach($goodses as $key=>$goods)
        {
            $bundle_goods[$goods['bundle_id']]['goods'][$goods['goods_id']] ['id'] = $goods['goods_id']; 
            $bundle_goods[$goods['bundle_id']]['goods'][$goods['goods_id']] ['goods_number'] += $goods['goods_number']; 
            $bundle_goods[$goods['bundle_id']]['goods'][$goods['goods_id']] ['bundle_id'] = $goods['bundle_id']; 
        }
        
        $bundle_goods[$goods['bundle_id']]['order_info']['id'] = $order[0]['id'];
        $bundle_goods[$goods['bundle_id']]['order_info']['order_id'] = $order[0]['order_id'];
        $bundle_goods[$goods['bundle_id']]['order_info']['create_time'] = $order[0]['create_time'];
            
        $this->BundleGoods = $bundle_goods;
        
        foreach ($bundle_goods as $bundleid => $onebundle_datas) {
            $curl = $bundleid . "curl";
            $this -> $curl = $this -> create_curl_obj($bundleid);
            $this -> init_curl($bundleid);
            $Re_Minus_updateStores = $this -> opBundle('updateStore', array('operation' => 'plus'));
            if($Re_Minus_updateStores['status'])
            {
                $status=2;
                $this -> addItem(0);
                $this -> Output();
            }
        }
        
        require_once ROOT_PATH . 'lib/class/members.class.php';
        
        //echo json_encode($order[0]);exit();
        //echo json_encode($order);exit();
        $members = new members();
        if($order[0]['pay_credits'])
        {
           $creditLogTitle = $this->input['creditLogTitle'] ? $this->input['creditLogTitle']:'取消订单';
            if($order[0]['pay_credits'])
            {
                $re = $members->return_credit(
                            $this->user['user_id'],
                            $order[0]['pay_credits'],
                            $order[0]['order_id'],
                            'payments',
                            'OrderUpdate',
                            'cancle',
                            $order[0]['title'],
                		    $order[0]['integral_status'],
                		    $creditLogTitle
                            //"取消订单:".$order[0]['order_id']
                            );
                            
                //echo json_encode($re);exit();            
                if(@!$re['logid']){
                    $this -> errorOutput("NO_NET");
                }
            }
            
        }
         
        $cond = " where 1 and id=$id and user_id=" . $this -> user['user_id'];
        $params['is_cancel'] = 21;
        $params['order_status'] = 22;
        $params['is_completed'] = 23;
        $re = $this -> obj -> update('order', $params, $cond);
        $this -> addItem(1);
        $this -> Output();
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
//                  var_dump($re);exit;
                return $re;
            } else {
            	//print_r($this -> $curl -> addRequestData($key, $val));exit;
                $this -> $curl -> addRequestData($key, $val);
            }
        }
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
        }
    }
    

}

$out = new OrderUpdateAPI();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out -> $action();
?>