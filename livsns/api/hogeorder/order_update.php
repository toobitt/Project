<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/24
 * Time: 下午1:50
 */
//file_put_contents('./cache/4455.txt', var_export($_REQUEST,1));
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'OrderUpdateApi');
class OrderUpdateApi extends  outerUpdateBase
{
    private $goods_list = array();  //订单中商品列表
    private $receive_address = array(); //订单收货人信息
    private $total_price = '';      //订单价格
    private $total_integral_price = '';   //订单积分价格
    private $app = '';   //应用信息

    public function __construct()
    {
        parent::__construct();
        include_once (CUR_CONF_PATH . 'lib/order.class.php');
        $this->obj = new Order();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(){}

    public function update(){}

    public function delete(){}

    /**
     * 创建订单接口
     *
     * 参数
     * @param id  商品id
     * @param num 商品数量
     * @param extend[full_price_num] 全票张数     汽车票订单需要用此参数
     * @param extend[half_price_num] 儿童票张数    汽车票订单需要用此参数
     * @param extend[free_price_num] 免票儿童张数   汽车票订单需要用此参数
     * @param extend[person] 乘车人信息 多个用,号隔开  汽车票订单需要用此参数
     * @param string app_uniqueid 应用标识    必选
     * @param string third_party  是否第三方商品  必选
     * @param req_reserved string  请求方保留域 此字段内容会原样返回   可选
     * @param receive_address_id int  收货地址id    receive_address_id和receive_address 二选一
     *
     * @param receive_address   array 收货人信息    receive_address_id和receive_address 二选一
     *        array(
     *          'contact_name'  => '收货人姓名', //必填
     *          'mobile'        => '收货人手机号',  //必填
     *          'postcode'      => '邮编', //可选
     *          'email'         => '邮箱', //可选
     *          'prov'          => '省份',  //可选
     *          'city'          => '城市',  //可选
     *          'area'          => '区县',  //可选
     *          'address'       => '收货人详细地址',  //可选
     *      )
     *
     * @param coupon_id 1,2
     * @param coupon_num 1,1
     * @param pay_type  string 付款方式(alipay, weixin, unionpay)  可选
     * @param title   string 订单标题    可选
     *
     * @return   array  订单信息
     */
    public function submit_order()
    {
//        $this->input['id'] = '2014-12-10*ZD0751*050CB0B532B488BB61825E5DE57F3C211B7DB279756503599866CB351BB92CCEFD523C5C15E2653F1701353A903271ABBA2236ACB75E40401813B7B54888662E';
//        $this->input['num'] =1;
//        $this->input['app_uniqueid'] = 'yc_bus';
//        $this->input['third_party'] = '1';
//        $this->input['receive_address'] = array('contact_name'=>'wan', 'mobile'=>'18777821');
//        //$this->input['extend'] = array('person'=> '48,49');
//        $this->input['extend']['full_price_num'] = 2;
//        $this->input['extend']['half_price_num'] = 1;
//        $this->input['extend']['free_price_num'] = 0;
//file_put_contents('./cache/11122233.txt', var_export($this->input,1));
        if (!$this->user['user_id'])
        {
            $this->errorOutput('NO LOGIN');
        }

        $this->input['app_uniqueid'] = trim($this->input['app_uniqueid']);
        if (!$this->input['app_uniqueid'])
        {
            $this->errorOutput('NO APP_UNIQUEID');
        }

        if (!isset($this->input['third_party']))
        {
            $this->errorOutput('NO THIRD_PARTY');
        }

        if (!$this->input['id'])
        {
            $this->errorOutput('NO ID');
        }

        if (!$this->input['num'])
        {
            $this->errorOutput('NO NUM');
        }

        if ($this->input['extend']['person'])
        {
            //获取用户信息
            $curl = new curl ($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
            $curl->addRequestData('a', 'show');
            $curl->addRequestData('manymdid', $this->input['extend']['person']);
            $curl->addRequestData('access_token', $this->user['access_token']);
            $curl->addRequestData('mark', 'passengers');
            $person = $curl->request('member_myData.php');
            if (empty($person) || empty($person[0]['data']))
            {
                $this->errorOutput('NO PERSON');
            }
            $this->input['extend']['person'] = $person = $person[0]['data'];
        }

        /********************** 验证收货地址 ***********************/
        $receive_address_id = $this->input['receive_address_id'];
        $receive_address = $this->input['receive_address'];
        if (!$receive_address_id && empty($receive_address))
        {
            $this->errorOutput('NO RECEIVE_ADDRESS');
        }
        if (!$receive_address_id)
        {
            if (!$receive_address['contact_name'] || !$receive_address['mobile'])
            {
                $this->errorOutput('NO RECEIVE_ADDRESS');
            }
        }
        if ($receive_address_id)
        {
            $sql = "SELECT * FROM ".DB_PREFIX."receive_address WHERE id = ".$receive_address_id." AND user_id = " . $this->user['user_id'];
            $receive_address = $this->db->query_first($sql);
            if (empty($receive_address))
            {
                $this->errorOutput('NO EXISTS RECEIVE_ADDRESS');
            }
        }
        $this->receive_address = $receive_address;
        /********************** 验证收货地址 ***********************/


        /********************** 查询应用信息 ***********************/
        $sql = "SELECT * FROM ".DB_PREFIX."app_access WHERE 1 AND app_uniqueid = '".$this->input['app_uniqueid']."'";
        $this->app = $this->db->query_first($sql);
        if (empty($this->app))
        {
            $this->errorOutput('ERROR APP_UNIQUEID');
        }
        /********************** 查询应用信息 ***********************/


    /********************************* 创建订单 ******************************/

        $this->db->commit_begin(); //开启事务
        $commit_tag = 0;   //事务提交标记


        /************************* 扣库存 ***********************/
        $sub_store_ret = $this->sub_store();
        /************************* 扣库存 ***********************/

        if ($sub_store_ret['success'] != 1)
        {
            $commit_tag = 1;
            $errormsg = $sub_store_ret['errmsg'];
        }
        else
        {
            //应用扣库存方法需返回total_product_fee(订单总价格)、total_integral_fee(积分总价格)、good_detail(商品详情) 字段
            if (!isset($sub_store_ret['total_product_fee']) || !isset($sub_store_ret['total_integral_fee']) || !isset($sub_store_ret['good_detail']))
            {
                $commit_tag = 1;
                $errormsg = 'API RETURN ERROR';
            }
        }

        if (!$commit_tag)
        {
            if ($sub_store_ret['other'])    //第三方订单将订单详细放入other节点
            {
                $out_trade_number = $sub_store_ret['other']['trade_number'];
            }
            $this->total_price = $sub_store_ret['total_product_fee'];
            $this->total_integral_price = $sub_store_ret['total_integral_price'];

            /********************* 优惠券处理 *******************/
            $this->process_coupon();
            /********************* 优惠券处理 *******************/

            /********************* 验证用户积分 *******************/
            $this->verify_integral();
            /********************* 验证用户积分 *******************/

            //创建订单
            $order = array(
                'user_id'           => $this->user['user_id'],
                'user_name'         => $this->user['user_name'],
                'trade_number'      => generate_trade_num(),
                'out_trade_number'  => $out_trade_number,
                'out_trade_info'    => $sub_store_ret['other'] ? serialize($sub_store_ret['other']) : '',
                'order_type'        => $this->input['third_party'] ? 'THIRD_PARTY' : 'default',
                'trade_create_time' => TIMENOW,
                'trade_expire_time' => TIMENOW + $this->app['trade_expire_time'],
                'trade_status'      => 'NOT_PAY',
                'delivery_fee'      => abs($this->input['delivery_fee']),
                'product_fee'       => $this->total_price,
                'integral_fee'      => $this->total_integral_price,
                'total_fee'         => $this->total_price + abs($this->input['delivery_fee']),
                'ip'                => hg_getip(),
                'app_uniqueid'      => $this->input['app_uniqueid'],
                'req_reserved'      => $this->input['req_reserved'],
            );

            if (!$this->db->insert_data($order, 'orders'))
            {
                $commit_tag = 1;
                $errormsg = 'CREATE ORDER FAILURE';
            }
        }

        /*************** 订单商品入库  *****************/
        if (!$commit_tag)
        {
            foreach ((array) $sub_store_ret['good_detail'] as $k => $v)
            {
                $tmp = array(
                    'trade_number'      => $order['trade_number'],
                    'app_uniqueid'      => $this->input['app_uniqueid'],
                    'product_id'        => $v['product_id'],
                    'title'             => $v['title'],
                    'brief'             => $v['brief'],
                    'indexpic'          => $v['indexpic'],
                    'link'              => $v['link'],
                    'product_nums'      => $v['product_nums'],
                    'product_fee'       => $v['product_fee'],
                    'integral_fee'      => $v['integral_fee'],
                    'extend'            => $v['extend'] ? serialize($v['extend']) : '',
                );
                if (!$this->db->insert_data($tmp, 'order_items'))
                {
                    $commit_tag = 1;
                    break;
                }
            }
        }
        /*************** 订单商品入库  *****************/


        /********************收货地址信息处理****************/
        if (!$commit_tag)
        {
            $order_address = array(
                'trade_number'      => $order['trade_number'],
                'contact_name'      => $this->receive_address['contact_name'],
                'mobile'            => $this->receive_address['mobile'],
                'country'           => $this->receive_address['country'],
                'prov'              => $this->receive_address['prov'],
                'city'              => $this->receive_address['city'],
                'area'              => $this->receive_address['area'],
                'address_detail'    => $this->receive_address['address_detail'],
                'postcode'          => $this->receive_address['postcode'],
                'email'             => $this->receive_address['email'],
            );
            if ( !$this->db->insert_data($order_address,'order_address') )
            {
                $commit_tag = 1;
            }
            else
            {
                if ( !$receive_address_id )
                {
                    $order_address['user_id'] = $this->user['user_id'];
                    $order_address['user_name'] = $this->user['user_name'];
                    unset($order_address['trade_number']);
                    $this->db->insert_data($order_address,'receive_address');
                }
            }
        }
        /********************收货地址信息处理****************/


        /********************回滚*************************/
        if ($commit_tag)
        {
            $this->db->rollback();  //回滚

            $this->add_store();     //还原库存

            $errormsg = $errormsg ? $errormsg : '下单失败,请稍后再试!';
            $this->errorOutput($errormsg);
        }
        /********************回滚*************************/


        /********************提交事务*************************/
        $this->db->commit_end();
        /********************提交事务*************************/

        $info = $this->obj->detail($order['trade_number']);
        list($order, $item, $address, $pay_type) = $info;
        $this->addItem_withkey('order', $order);
        $this->addItem_withkey('item', $item);
        $this->addItem_withkey('address', $address);
        $this->addItem_withkey('pay_type', $pay_type);
        $this->output();
    }

    /**
     * 验证用户积分是否够支付
     */
    private function verify_integral()
    {
        if ($this->total_integral_price)
        {
            //获取用户信息
            $curl = new curl ($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
            $curl->addRequestData('a', 'get_member_credits');
            $curl->addRequestData('member_id', $this->user['user_id']);
            $user_info = $curl->request('member.php');
            if ($user_info['ErrorCode'])
            {
                $this->errorOutput($user_info['ErrorCode']);
            }
            if (empty($user_info))
            {
                $this->errorOutput('No user_info');
            }

            if ($user_info[0]['credit1'] < $this->total_integral_price)
            {
                $this->errorOutput('No enough integral');
            }
        }
        return ;
    }

    /**
     * 优惠券处理
     * @param $name
     * @param $arguments
     */
    private function process_coupon()
    {
        return ;
    }

    /**
     * 扣库存方法
     */
    private function sub_store()
    {
        $curl_name = $this->input['app_uniqueid'] . '_curl';
        if (!$this->$curl_name)   //应用curl不存在时格式化curl
        {
            $this->$curl_name = new curl($this->app['host'], $this->app['dir']);
        }
        $this->$curl_name->setSubmitType('post');
        $this->$curl_name->setReturnFormat('json');
        $this->$curl_name->initPostData();
        $this->$curl_name->addRequestData('a', $this->app['sub_store_func']);
        $this->$curl_name->addRequestData('id', $this->input['id']);
        $this->array_to_add($curl_name, 'receive_address' , $this->receive_address);
        //$this->array_to_add($curl_name, 'person' , $this->input['extend']['person']);
        $this->array_to_add($curl_name, 'extend' , $this->input['extend']);
        $ret = $this->$curl_name->request($this->app['request_file']);
        return $ret;
    }

    private function add_store()
    {
        $curl_name = $this->input['app_uniqueid'] . '_curl';
        if (!$this->$curl_name)   //应用curl不存在时格式化curl
        {
            $this->$curl_name = new curl($this->app['host'], $this->app['dir']);
        }
        $this->$curl_name->setSubmitType('post');
        $this->$curl_name->setReturnFormat('json');
        $this->$curl_name->initPostData();
        $this->$curl_name->addRequestData('a', $this->app['add_store_func']);
        $this->array_to_add($curl_name, 'goods' , $this->goods_list);
        $this->array_to_add($curl_name, 'receive_address' , $this->receive_address);
        $this->$curl_name->addRequestData('app_uniqueid', $this->input['app_uniqueid']);
        $ret = $this->$curl_name->request($this->app['request_file']);
        return $ret;
    }

    private  function array_to_add($curl_name, $str , $data)
    {
        $str = $str ? $str : 'data';
        if(is_array($data))
        {
            foreach ($data AS $kk => $vv)
            {
                if(is_array($vv))
                {
                    $this->array_to_add($curl_name, $str . "[$kk]" , $vv);
                }
                else
                {
                    $this->$curl_name->addRequestData($str . "[$kk]", $vv);
                }
            }
        }
    }

    public function __call($name, $arguments)
    {
        $this->errorOutput('No method');
    }

}
require_once (ROOT_PATH . 'excute.php');