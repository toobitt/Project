<?php
/**
 * 支付基类
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/18
 * Time: 下午9:22
 */

abstract class HgPay {

    protected $timezone        		= "Asia/Shanghai"; //时区

    protected $charset    		 	= "UTF-8"; // 字符编码

    protected $mer_id     		    = ""; // 商户号
    protected $security_key    		= ""; // 商户密钥
    protected $mer_back_end_url     = ""; // 后台通知地址
    protected $mer_front_end_url     = ""; // 前台通知地址


    protected $sign_method 			= "MD5"; // 签名方法，MD5 SHA RSA
    protected $sign_key 			= "signature"; // 签名key
    protected $sign_method_key 		= "signMethod"; // 签名方法key

    //商户的私钥（后缀是.pen）文件相对路径
    protected $private_key_path = 'key/rsa_private_key.pem';
    //支付宝公钥（后缀是.pen）文件相对路径
    protected $public_key_path = 'key/alipay_public_key.pem';


    protected $http = '';


    /**
     * 重载默认的类的属性的选项
     *
     * @param   array 提供商配置
     * @throws  如果没有提供必要的选项抛出异常
     */
    public function __construct(array $options = array())
    {
//        if (empty($options['mer_id'])) {
//            throw new Exception('缺少关键参数: mer_id');
//        }

        foreach ($options as $k => $v)
        {
            $this->$k = $v;
        }

        include_once (CUR_CONF_PATH . '/lib/pay/hg_http.php');
        $this->http = new Http();
        $this->http->mDebug = false;
    }

    abstract public function getPayParam( array $order);

    abstract public function query( array $order );

    /**
     * 生成要请求参数说组
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组
     */
    protected function buildRequestPara($para_temp, $contain_method = 1) {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);

        //签名结果与签名方式加入请求提交参数组中
        $para_sort[$this->sign_key] = $mysign;

        if ($contain_method)
        {
            $para_sort[$this->sign_method_key] = strtoupper(trim($this->sign_method));
        }

        return $para_sort;
    }

    /**
     * 生成签名结果
     * @param $para_sort 已排序要签名的数组
     * return 签名结果字符串
     */
    protected function buildRequestMysign($para_sort) {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr = $this->createLinkstring($para_sort);
//        var_dump($prestr);exit;
        $mysign = "";
        switch (strtoupper(trim($this->sign_method))) {
            case "MD5" :
                include_once(CUR_CONF_PATH . 'lib/pay/encrypt/hg_md5.class.php');
                $mysign = $this->buildMD5MySign($prestr);
                break;
            case "RSA":
                include_once(CUR_CONF_PATH . 'lib/pay/encrypt/hg_rsa.class.php');
                $mysign = HgRsa::encrypt($prestr, $this->private_key_path);
                break;
            case "SHA1":
                include_once(CUR_CONF_PATH . 'lib/pay/encrypt/hg_sha1.class.php');
                $mysign = HgSha1::encrypt($prestr);
                break;
            default :
                $mysign = "";
        }


        return $mysign;
    }
    /**
     * 生成要请求参数字符串
     * @param $para_temp 请求前的参数数组
     * @return 要请求的参数数组字符串
     */
    protected function buildRequestParaToString($para_temp, $contain_method = 1) {
        //待请求参数数组
        $para = $this->buildRequestPara($para_temp, $contain_method);

        //把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
        $request_data = $this->createLinkstring($para, true);

        return $request_data;
    }


    /**
     * 除去请求要素中的空值和签名参数
     * @param para 请求要素
     * @return 去掉空值与签名参数后的请求要素
     */
    protected function paraFilter($para) {
        $result = array ();
        while ( list ( $key, $value ) = each ( $para ) ) {
            if ($key == $this->sign_key || $key == $this->sign_method_key || $value == "") {
                continue;
            } else {
                $result [$key] = $para [$key];
            }
        }
        return $result;
    }

    /**
     * 把请求要素按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param para 请求要素
     * @param encode 是否需要URL编码
     * @return 拼接成的字符串
     */
    public function createLinkString($para, $encode = '') {
        $linkString  = "";
        while (list ($key, $value) = each ($para)) {
            if ($encode){
                $value = urlencode($value);
            }
            $linkString.=$key.'='.$value.'&';
        }
        //去掉最后一个&字符
        $linkString = substr($linkString,0,count($linkString)-2);

        //如果存在转义字符，那么去掉转义
//        if(get_magic_quotes_gpc()){
//            $linkString = stripslashes($linkString);
//        }

        return $linkString;
    }


    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
     */
    protected function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }


    protected function request($url, $params, $method = 'post')
    {
        if ( !in_array($method, array('post', 'get', 'POST', 'GET')) )
        {
            $method = 'post';
        }
        $method = strtolower($method);
        $resp = $this->http->$method($url, $params);
        return $resp;
    }

    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
     */
    function logResult($word='') {
        $fp = fopen(CUR_CONF_PATH . "cache/log.txt","a");
        flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

} 