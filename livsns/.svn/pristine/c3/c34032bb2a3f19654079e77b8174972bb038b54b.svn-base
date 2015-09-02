<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/4
 * Time: 下午11:21
 *
 * 银联支付类
 */

final class HgUnionpay extends HgPay
{

    protected $version     			= "1.0.0"; // 版本号

    protected $mer_id     		    = ""; // 商户号
    protected $security_key    		= ""; // 商户密钥
    protected $mer_back_end_url     = ""; // 后台通知地址
    protected $mer_front_end_url     = ""; // 前台通知地址

    protected  $upmp_trade_url   	 	= "http://202.101.25.178:8080/gateway/merchant/trade";
    protected  $upmp_query_url    	 	= "http://202.101.25.178:8080/gateway/merchant/query";


    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    /**
     * 生成订单并获取 支付参数
     */
    public function getPayParam( array $order)
    {
        //请求参数设定
        $req = array();
        $req['version']     		= $this->version; // 版本号
        $req['charset']     		= $this->charset; // 字符编码
        $req['transType']   		= "01"; // 交易类型  01消费
        $req['merId']       		= $this->mer_id; // 商户代码
        $req['backEndUrl']      	= $this->mer_back_end_url; // 通知URL
        $req['frontEndUrl']     	= $this->mer_front_end_url; // 前台通知URL(可选)
        $req['orderDescription']	= $order['title'];// 订单描述(可选)
        $req['orderTime']   		= $order['trade_create_time']; // 交易开始日期时间yyyyMMddHHmmss
        $req['orderTimeout']   		= $order['trade_expire_time']; // 订单超时时间yyyyMMddHHmmss(可选)
        $req['orderNumber'] 		= $order['trade_number']; //订单号(商户根据自己需要生成订单号)
        $req['orderAmount'] 		= $order['total_fee']; // 订单金额
        $req['orderCurrency'] 		= "156"; // 交易币种(可选)

//        var_dump($req);exit;
        $params = $this->buildRequestParaToString($req);
//        echo $params;exit;
        //$this->logResult('推送订单请求参数----' . $params);
        $respString = $this->request($this->upmp_trade_url, $params);
//        var_dump($respString);exit;
//        echo $respString;exit;
        //$this->logResult('推送订单响应参数---' . $respString);
        $resp = array();
        $validResp = $this->verifyResponse($respString, $resp);

//        var_dump($resp);exit;

        if ($validResp)
        {
            // 服务器应答签名验证通过
            if ($resp['respCode'] != '00')
            {
                return array('errno' => $resp['respCode'], 'errmsg' => $resp['respMsg']);
            }
            $ret = array(
                'tn' => $resp['tn'],    //订单号
                'sdk_param' => $resp['tn'],   //客户端打开sdk使用此参数
            );
        }
        else
        {
            // 服务器应答签名验证失败
            return false;
        }
        return $ret;
    }

    /**
     * 交易信息查询接口
     */
    public function query( array $order)
    {
        ////请求参数设定
        $req = array();
        $req['version']     	= $this->version; // 版本号
        $req['charset']     	= $this->charset; // 字符编码
        $req['transType']   	= "01"; // 交易类型
        $req['merId']       	= $this->mer_id; // 商户代码
        $req['orderTime']   	= $order['trade_create_time']; // 交易开始日期时间yyyyMMddHHmmss或yyyyMMdd
        $req['orderNumber'] 	= $order['trade_number']; // 订单号

        $params = $this->buildRequestParaToString($req);
        //$this->logResult('订单查询请求参数----' . $params);
        $respString = $this->request($this->upmp_query_url, $params);
//        echo $respString;exit;
        //$this->logResult('订单查询响应参数---' . $respString);
        $resp = array();
        $validResp = $this->verifyResponse($respString, $resp);

//        var_dump($resp);exit;

        if ($validResp)
        {  //验证通过
            if($resp['transStatus'] != '00')
            {
                return false;
            }


        }
        else
        {
            // 服务器应答签名验证失败

        }


        return $resp;
    }

    /**
     * 交易撤销接口
     */
    public function cancle(array $order)
    {
        //需要填入的部分
        $req['version']     	= $this->version; // 版本号
        $req['charset']     	= $this->charset; // 字符编码
        $req['transType']   	= "31"; // 交易类型
        $req['merId']       	= $this->mer_id; // 商户代码
        $req['backEndUrl']      = $this->mer_back_end_url; // 通知URL
        $req['orderTime']   	= $order['trade_create_time']; // 交易开始日期时间yyyyMMddHHmmss（撤销交易新交易日期，非原交易日期）
        $req['orderNumber'] 	= $order['trade_number']; // 订单号（撤销交易新订单号，非原订单号）
        $req['orderAmount'] 	= $order['total_fee']; // 订单金额
        $req['orderCurrency'] 	= "156"; // 交易币种(可选)
        $req['qn'] 				= $order['qn']; // 查询流水号（原订单支付成功后获取的流水号）


        $params = $this->buildRequestParaToString($req);
        //$this->logResult('消费撤销请求参数----' . $params);
        $respString = $this->http->post($this->upmp_trade_url, $params);
        //$this->logResult('消费撤销响应参数----' . $respString);
        $resp = array();
        $validResp = $this->verifyResponse($respString, $resp);

        if ($validResp)
        {  //验证通过


        }
        else
        {
            // 服务器应答签名验证失败

        }


        return $resp;
    }

    /**
     * 退款接口
     */
    public function refund(HgOrder $order)
    {
        //需要填入的部分
        $req['version']     	= $this->version; // 版本号
        $req['charset']     	= $this->charset; // 字符编码
        $req['transType']   	= "04"; // 交易类型
        $req['merId']       	= $this->mer_id; // 商户代码
        $req['backEndUrl']      = $this->mer_back_end_url; // 通知URL
        $req['orderTime']   	= $order['trade_create_time']; // 交易开始日期时间yyyyMMddHHmmss（退货交易新交易日期，非原交易日期）
        $req['orderNumber'] 	= $order['trade_num']; // 订单号（退货交易新订单号，非原交易订单号）
        $req['orderAmount'] 	= $order['total_fee']; // 订单金额
        $req['orderCurrency'] 	= "156"; // 交易币种(可选)
        $req['qn'] 				= $order['qn']; // 查询流水号（原订单支付成功后获取的流水号）

        $params = $this->buildRequestParaToString($req);
        $resp = array ();
        $respString = $this->http->post($this->upmp_trade_url, $params);
        $validResp = $this->verifyResponse($respString, $resp);

        if ($validResp)
        {  //验证通过


        }
        else
        {
            // 服务器应答签名验证失败

        }


        return $resp;
    }


    /**
     * 应答解析
     * @param respString 应答报文
     * @param resp 应答要素
     * @return 应答是否成功
     */
    protected function verifyResponse($respString, &$resp) {
        if  ($respString != ""){
            parse_str($respString, $para);
            $signIsValid = $this->verifySignature($para);

            $resp = $para;
            if ($signIsValid) {
                return true;
            }else {
                return false;
            }
        }
    }

    /**
     * 异步通知消息验证
     * @param para 异步通知消息
     * @return 验证结果
     */
    public  function verifySignature($para) {
        $respSignature = $para[$this->sign_key];
//        echo $respSignature . "<br/>";
        // 除去数组中的空值和签名参数
        $filteredReq = $this->paraFilter($para);
//        var_dump($filteredReq);
        $filteredReq = $this->argSort($filteredReq);
//        var_dump($filteredReq);exit;
        $signature = $this->buildRequestMysign($filteredReq);
//        echo $signature . "<br/>";
        if ("" != $respSignature && $respSignature==$signature) {
            return true;
        }else {
            return false;
        }
    }


    /**
     * md5签名 在 MD5 签名时，需要商户私钥参与签名
     */
    public function buildMD5MySign($prestr)
    {
        $prestr = HgMd5::encrypt($prestr.'&'.HgMd5::encrypt($this->security_key));
        return $prestr;
    }


}