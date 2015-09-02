<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/26
 * Time: 上午11:10
 */
require_once ('./global.php');
define('MOD_UNIQUEID', 'hogeorder');
define('SCRIPT_NAME', 'YCBus');
class YCBus extends  outerUpdateBase
{
    private $yc_bus_api = "http://www.yc5s.com:8083/ticketYCGDService/services/ticketBuy?wsdl";
    private $yc_api_key = "ycgd1234567890nj";

    public function __construct()
    {
        parent::__construct();
        $this->bus_client = new SoapClient($this->yc_bus_api);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(){}

    public function update(){}

    public function delete(){}

    public function sub_store()
    {
//$this->input = array (
//    'format' => 'json',
//    'a' => 'sub_store',
//    'id' => '2014-12-09*ZD0751*050CB0B532B488BB61825E5DE57F3C211B7DB279756503599866CB351BB92CCEFD523C5C15E2653F1701353A903271ABBA2236ACB75E40401813B7B54888662E',
//    'receive_address' =>
//        array (
//            'contact_name' => 'wan',
//            'mobile' => '18777821',
//        ),
//    'extend' => array(
//        'full_price_num' => 1,
//        'half_price_num'  => 1,
//        'person' =>
//            array (
//                0 =>
//                    array (
//                        'mdid' => '49',
//                        'idnum' => '1234567899876454',
//                        'provetype' => '身份证',
//                        'realname' => '米饭',
//                        'tickettype' => '成人票',
//                    ),
//                1 =>
//                    array (
//                        'mdid' => '48',
//                        'idnum' => '32108119900304243X',
//                        'provetype' => '身份证',
//                        'realname' => '王亚',
//                        'tickettype' => '成人票',
//                    ),
//            ),
//    ),
//    'lpip' => '',
//    'm2o_ckey' => 'OjEN52E9LieIe9yx8mfDZEpDlUnxuya9',
//    'appid' => '55',
//    'appkey' => 'GLtPX7N7ijwb83wupXuIrEl1YvIeBbm7',
//    'access_token' => '1574200410d6f13177a83d9b6f086418',
//);
        //file_put_contents('../cache/111.txt', var_export($this->input,1));
        $id = $this->input['id'];
        $receive_address = $this->input['receive_address'];
        $passengers = $this->input['extend']['person'];
        $full_price_num = abs(intval($this->input['extend']['full_price_num']));
        $half_price_num = abs(intval($this->input['extend']['half_price_num']));
        $free_price_num = abs(intval($this->input['extend']['free_price_num']));
        if (!$id)
        {
            $this->format_error(3, 'NO ID');
        }
        if (empty($receive_address))
        {
            $this->format_error(4, 'NO RECEIVE_ADDRESS');
        }
//        if (empty($person))
//        {
//            $this->format_error(4, 'NO PERSON');
//        }

        if (!$full_price_num && !$half_price_num)
        {
            $this->format_error(5, 'NO TICKET NUM');
        }

        if ( ($full_price_num + $half_price_num) > 5 )
        {
            $this->format_error(6, '一次最多只能买5张');
        }

        if ( $half_price_num > 1 )
        {
            $this->format_error(7, '儿童票最多只能买一张');
        }

        if ($free_price_num > $full_price_num)
        {
            $this->format_error(8, '免票儿童数不能大于成人票数');
        }

        list($depart_date, $train_number,$dest_code) = explode('*', $id);

        $depart_date = implode('', explode('-', $depart_date));

        //$full_price_num = $half_price_num = 0;
        $passenger_string = '';
        foreach ((array)$passengers as $passenger)
        {
            $passenger['tickettype'] = ($passenger['tickettype'] == '成人票') ? 0 : 1;
            if ($passenger['tickettype'] == 1)
            {
                //$half_price_num ++;
            }
            else if ($passenger['tickettype'] == 0)
            {
                //$full_price_num++;
            }
            if ($passenger['provetype'] != '01')
            {
                $passenger['provetype'] = '01';
            }
            $passenger_string .= $passenger['tickettype'] . '|' . $passenger['realname'] . '|' . $passenger['provetype'] . '|' .$passenger['idnum'] . '|' . $passenger['mobile'] . '*';
        }
        $passenger_string = substr($passenger_string, 0, strlen($passenger_string) - 2);
        $params = array(
            'fields1' 	=> $depart_date,   //发车日期
            'fields2' 	=> $train_number,           //车次
            'fields3' 	=> $dest_code,          //到达站代码  目的地代码
            'fields4' 	=> $full_price_num,         //全票张数
            'fields5'   => $half_price_num,          //儿童票张数
            'fields6'   => '',          //用户名
            'fields7'   => $receive_address['contact_name'],         //订票人姓名
            'fields8'   => $receive_address['mobile'],   //订票人联系电话
            'fields9'   => $receive_address['cert_number'] ? $receive_address['cert_number'] : '' ,   //订票人证件号
            'fields10'  => '0',          //是否保险 0 不买保险 1 买保险
            //'fields11'  => '0|王乐园|01|342221199008284057|17712862787*0|王乐乐|01|342221200808284057|17712862787',          //乘车人信息多个乘客用*隔开格式：票种|姓名|证件类型|证件号|手机号*票种|姓名|证件类型|证件号|手机号
            //'fields11'  => '0|王乐园|01|342221199008284057|17712862787',
            'fields11'  => $passenger_string,
            //票种 0 全票 1 童票
            //证件类型 01身份证
            'fields12' => $free_price_num,          //免票儿童数
        );

        $params['fields13'] = $this->generate_key($params);
        $obj = $this->bus_client->orderTickets($params);
        if (!$obj || !$obj->return)
        {
            $this->format_error(1, 'BUS API ERROR');
        }
        $return = $obj->return;
        $return = json_decode($return,1);
        if ( empty($return) || $return['success'] > 0 )
        {
            $errmsg = $return['other'] ? 'BUS API RETURN ' . $return['other'] : 'BUS API ERROR';
            $this->format_error(2, $errmsg);
        }
        $info = array(
            'trade_number'       => $return['msg'][0]['FIELDS1'],  //订单号
            'ticket_number'      => $return['msg'][0]['FIELDS12'],//取票号
            'ticket_pwd'      => $return['msg'][0]['FIELDS2'],  //取票密码
            'on_station'      => $return['msg'][0]['FIELDS3'],  //上车站名称
            'start_station'   => $return['msg'][0]['FIELDS4'], //始发站
            'train_number'    => $return['msg'][0]['FIELDS5'], //车次
            'depart_date'     => $return['msg'][0]['FIELDS6'], //发车日期
            'dest'            => $return['msg'][0]['FIELDS7'], //目的地
            'depart_time'     => $return['msg'][0]['FIELDS8'], //发车时间
            'seat_number'     => $return['msg'][0]['FIELDS9'], //座位号  多个以-隔开
            'price'           => $return['msg'][0]['FIELDS10'],//票价 多个以-隔开
            'gate_number'        => $return['msg'][0]['FIELDS11'],//检票口
            'take_ticket_fee' => $return['msg'][0]['FIELDS13'],//取票手续费
            'free_children_num'=> $return['msg'][0]['FIELDS14'], //免票儿童数
            'total_price'   => array_sum(explode('-', $return['msg'][0]['FIELDS10'])),
        );
        $FIELDS10 = explode('-', $return['msg'][0]['FIELDS10']);
        asort($FIELDS10);
        $full_price = $info['full_price'] = end($FIELDS10);
        arsort($FIELDS10);
        $half_price = $info['half_price'] = end($FIELDS10);
        $total_product_fee = $info['total_price'];
        $total_integral_fee = '';
        $good_detail = array();

        $good_detail[] = array(
            'product_id'    => $id,
            'title'          => $info['on_station'] . ' --- ' . $info['dest'],
            'brief'          => '',
            'indexpic'       => '',
            'link'           => '',
            'product_nums'   => $full_price_num + $half_price_num,
            'product_fee'    => $full_price,
            'integral_fee'   => '',
            'extend'         => $passengers,
        );

        $this->addItem_withkey('success', 1);
        $this->addItem_withkey('total_product_fee', $total_product_fee);
        $this->addItem_withkey('total_integral_fee', $total_integral_fee);
        $this->addItem_withkey('good_detail', $good_detail);
        $this->addItem_withkey('other', $info);
        $this->output();
    }


    /**
     * 支付接口
     */
    public function pay()
    {
//        $this->input['trade_number'] = '1205017601022517';
//        $this->input['total_fee'] = '616.00';
        $this->input['trade_number'] = trim($this->input['trade_number']);
        $this->input['total_fee'] = trim($this->input['total_fee']);
        if (!$this->input['trade_number'])
        {
            $this->format_error(10, 'NO TRADE_NUMBER');
        }

        if (!$this->input['total_fee'])
        {
            $this->format_error(11, 'NO TOTAL_FEE');
        }

        $params = array(
            'fields1' => $this->input['trade_number'],
            'fields2' => 3,
            'fields3' => intval($this->input['total_fee']),
        );
        $params['fields4'] = $this->generate_key($params);
        $obj = $this->bus_client->orderPay($params);
        if (!$obj || !$obj->return)
        {
            $this->format_error(1, 'BUS API ERROR');
        }
        $return = $obj->return;
        $return = json_decode($return,1);
//        var_dump($return);exit;
        if ( empty($return) || $return['success'] > 0 )
        {
            $errmsg = $return['other'] ? 'BUS API RETURN ' . $return['other'] : 'BUS API ERROR';
            $this->format_error(2, $errmsg);
        }

        $info = array(
            'ticket_number'      => $return['msg'][0]['FIELDS1'],//取票号
            'ticket_pwd'      => $return['msg'][0]['FIELDS2'],  //取票密码
        );
        $this->addItem_withkey('success', 1);
        $this->addItem_withkey('data', $info);
        $this->output();
    }

    /**
     * 订单查询接口
     */
    public function order_detail()
    {
       // $this->input['trade_number'] = '1201017601000306';
        $this->input['trade_number'] = trim($this->input['trade_number']);
        if (!$this->input['trade_number'])
        {
            $this->format_error(15, 'NO TRADE_NUMBER');
        }
        $params = array(
            'fields1' => $this->input['trade_number'],
            //'fields1' => '2222222',
        );
        $params['fields2'] = $this->generate_key($params);
        //var_dump($params);exit;
//        $this->bus_client->soap_defencoding = 'utf-8';
//        $this->bus_client->xml_encoding = 'utf-8';
        $obj = $this->bus_client->queryOrderStatus($params);
        if (!$obj || !$obj->return)
        {
            $this->format_error(1, 'BUS API ERROR');
        }
        $return = $obj->return;
        $return = json_decode($return,1);
        if ( empty($return) || $return['success'] > 0 )
        {
            $errmsg = $return['other'] ? 'BUS API RETURN ' . $return['other'] : 'BUS API ERROR';
            $this->format_error(2, $errmsg);
        }

        $info = array(
            'status' => $return['msg'][0]['FIELDS1'],
            'status_text' => $return['msg'][0]['FIELDS2'],
        );
        $this->addItem_withkey('success', 1);
        $this->addItem_withkey('data', $info);
        $this->output();
    }


    private function  generate_key($params)
    {
        $key = '';
        foreach ((array)$params as $k => $v)
        {
            $key .= $v;
        }
        $key .= $this->yc_api_key;
        $key = md5($key);
        return $key ;
    }

    private function format_error($errno, $errmsg = '')
    {
        $errMap = array(
        );
        $errmsg = $errmsg ? $errmsg : $errMap[$errno];
        $this->addItem_withkey('errno', $errno);
        $this->addItem_withkey('errmsg', $errmsg);
        $this->output();
    }

    public function __call($name, $arguments)
    {
        $this->errorOutput('UNKONW METHOD');
    }

}
require_once (ROOT_PATH . 'excute.php');
?>