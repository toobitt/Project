<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/4
 *
 * 微信类
 */

final class HgWeixin extends HgPay
{

    //财付通商户号
    protected $mer_id     		    = "1220665201";
    //财付通密钥
    protected $security_key    		= "fncjriwdr4nmxvn012on6lvh1tr9yt01";
    //appid
    protected $appid                = "wx8f1e44251c0a1d05";
    //appsecret
    protected $appsecret            = "1d105487356f3402ddfd9b6227aaa730";
    //paysignkey(非appkey)
    protected $paysignkey           = "ZQpS6dbR3pEznxVH8flwi3AXCUgmdn5xQvYdpCNp6e4MUX5DBy2JZkZBptUG7EYeML84mYa9LPL4REqMZQUh08zdeF8eRz5dDyFGkwjEwVRrcw6lRY0Isw5ydP9JcTdX";

    // 后台通知地址
    protected $mer_back_end_url     = "http://218.2.102.114:233/livsns/api/hogeorder/notify/notify_url_weixin.php";

    //Token获取网关地址
    protected $token_url            = "https://api.weixin.qq.com/cgi-bin/token";
    //预支付网关url地址
    protected $gate_url             = "https://api.weixin.qq.com/pay/genprepay";
    //订单查询url地址
    protected $query_url            = "https://api.weixin.qq.com/pay/orderquery";
    //发货通知接口url地址
    protected $deliver_url          = "https://api.weixin.qq.com/pay/delivernotify";

    protected $sign_method 			= "SHA1"; // 签名方法，MD5 SHA1 RSA
    protected $sign_key 			= "app_signature"; // 签名key
    protected $sign_method_key 		= "sign_method"; // 签名方法key


    public function __construct(array $options = array())
    {
        parent::__construct($options);

        //获取token
        $this->token = $this->getToken();
    }

    public function getPayParam( array $order)
    {
        //订单详情package参数设定
        $packageParams = array();
        $packageParams['bank_type']     		= 'WX'; // 银行通道类型
        $packageParams['body']                  = $order['title']; // 订单描述
        $packageParams['fee_type']		        = '1';   //支付币种  1人民币
        $packageParams['input_charset']	        = $this->charset;  //传入参数字符编码
        $packageParams['notify_url']	        = $this->mer_back_end_url;   //后台通知URL
        $packageParams['out_trade_no']          = $order['trade_number'];
        $packageParams['partner']               = $this->mer_id;  //财付通商户号
        $packageParams['total_fee']             = $order['total_fee'];
        $packageParams['time_start']            = $order['trade_create_time'];
        $packageParams['time_expire']           = $order['trade_expire_time'];
        $packageParams['spbill_create_ip']      = '10.0.2.86';    //必选 用户浏览器端ip 格式IPV4
        $this->sign_method = 'MD5';
        $packageParams = $this->buildRequestPara($packageParams, 0);
        $packageParams['sign'] = $packageParams['app_signature'];
        unset($packageParams['app_signature']);
        $package = $this->createLinkstring($packageParams, true);

        $time_stamp = TIMENOW;
        $nonce_str = md5(rand());

        //请求参数
        $signParams =array();
        $signParams['appid']	= $this->appid;
        $signParams['appkey']	= $this->paysignkey;
        $signParams['noncestr']	=$nonce_str;
        $signParams['package']	=$package;
        $signParams['timestamp']=$time_stamp;
        $signParams['traceid']	= 'mytraceid_001';
        $this->sign_method = 'SHA1';
        $params = $this->buildRequestPara($signParams);

        $params['sign_method'] = strtolower($params['sign_method']);
        unset($params['appkey']);
//        var_dump($params);

        $url= $this->gate_url .'?access_token='.$this->token;
        $params = json_encode($params);
        $ret = $this->request($url, $params);

        if (!$ret['prepayid']  || $ret['errcode'])
        {
            $errno = $ret['errcode'] ? $ret['errcode'] : '0000';
            $errmsg = $ret['errmsg'] ? $ret['errmsg'] : 'API CALL FAILURE';
            return array('errno' => $errno, 'errmsg' => $errmsg);
        }
        else
        {
            $pack	= 'Sign=WXPay';
            $prePayParams =array();
            $prePayParams['appid']		= $this->appid;
            $prePayParams['appkey']		= $this->paysignkey;
            $prePayParams['noncestr']	= $nonce_str;
            $prePayParams['package']	= $pack;
            $prePayParams['partnerid']	= $this->mer_id;
            $prePayParams['prepayid']	=$ret['prepayid'];
            $prePayParams['timestamp']	=$time_stamp;
            $this->sign_method = 'SHA1';
            $sdk_param = $this->buildRequestPara($prePayParams, 0);
            unset($sdk_param['appkey']);
            $ret = array(
                'sdk_param' => $sdk_param,
            );
            return $ret;
        }
    }

    /**
     * 交易信息查询接口
     */
    public function query( array $order)
    {

    }

    //获取TOKEN，一天最多获取200次
    function getToken()
    {
        session_start();
        if (!$_SESSION['hogepay_weixin_token'])
        {
            $url= $this->token_url . '?grant_type=client_credential&appid='.$this->appid .'&secret='.$this->appsecret;
            $ret = $this->request($url, '', 'get');
            if ($ret['access_token'] )
            {
                $_SESSION['hogepay_weixin_token'] = $ret['access_token'];

            }
        }
        return $_SESSION['hogepay_weixin_token'];
    }


    /**
     * md5签名 在 MD5 签名时，需要商户私钥参与签名
     */
    public function buildMD5MySign($prestr)
    {
        $prestr = strtoupper(HgMd5::encrypt($prestr . '&key=' . $this->security_key));
        return $prestr;
    }

    /**
     * 异步通知消息验证
     * @param para 异步通知消息
     * @return 验证结果
     */
    public  function verifySignature($para) {
        $respSignature = strtoupper($para['sign']);
        //设置签名key值
        $this->sign_key = 'sign';
        // 除去数组中的空值和签名参数
        $filteredReq = $this->paraFilter($para);
//        var_dump($filteredReq);
        $filteredReq = $this->argSort($filteredReq);
//        var_dump($filteredReq);exit;
        $this->sign_key = 'sign';
        $signature = $this->buildRequestMysign($filteredReq);
//        echo $signature . "<br/>";
        if ("" != $respSignature && $respSignature==$signature) {
            return true;
        }else {
            return false;
        }
    }

}
