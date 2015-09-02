<?php
/**
 * 支付宝即时到账接口类
 * User: kangxiaoqiang
 * Date: 15/05/15
 * Time: 下午17:34
 */

class HgAliPay extends HgPay
{

    /**
     *支付宝网关地址（新）
     */
    protected  $alipay_gateway_new = 'https://mapi.alipay.com/gateway.do?';

    /* 签名方式 */
    protected $sign_method = 'RSA';
    protected $sign_key 			= "sign"; // 签名key
    protected $sign_method_key 		= "sign_type"; // 签名方法key

    /* 证书文件 */
    protected  $certFile;
    /* 证书密码 */
    protected  $certPasswd;
    /* 证书类型PEM */
    protected 	$certType;

    /* CA文件 */
    protected $caFile;

    /* 商户的私钥（后缀是.pen）文件相对路径 */
    protected $private_key_path = 'key/rsa_private_key.pem';
    /* 支付宝公钥（后缀是.pen）文件相对路径 */
    protected $public_key_path = 'key/alipay_public_key.pem';

    /* 访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http */
    protected $transport = 'http';
	
    
    //HTTPS形式消息验证地址
	protected $https_verify_url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&';
    
	//HTTP形式消息验证地址
	protected $http_verify_url = 'http://notify.alipay.com/trade/notify_query.do?';



    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    
	/**
	 * 异步通知消息验证
     * 针对notify_url验证消息是否是支付宝发出的合法消息
     * @return 验证结果
     */
	function verifyNotify($data = array())
	{
		if(empty($data))
		{
			return false;
		}
		else
		{
			//生成签名结果
			$isSign = $this->getSignVeryfy($data, $data["sign"]);
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			if (!empty($data["notify_id"]))
			{
				$responseTxt = $this->getResponse($data["notify_id"]);
			}
			
			//写日志记录
			//if ($isSign) {
			//	$isSignStr = 'true';
			//}
			//else {
			//	$isSignStr = 'false';
			//}
			//$log_text = "responseTxt=".$responseTxt."\n notify_url_log:isSign=".$isSignStr.",";
			//$log_text = $log_text.createLinkString($data);
			//logResult($log_text);
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$responseTxt) && $isSign)
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
    
	
	/**
     * 获取返回时的签名验证结果
     * @param $para_temp 通知返回来的参数数组
     * @param $sign 返回的签名结果
     * @return 签名验证结果
     */
	function getSignVeryfy($para_temp, $sign)
	{
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		
		$isSgin = false;
		switch (strtoupper($this->sign_method))
		{
			case "RSA" :
				$isSgin = $this->rsaVerify($prestr, trim($this->public_key_path), $sign);
				break;
			default :
				$isSgin = false;
		}
		
		return $isSgin;
	}
    
	
	/**
     * 获取远程服务器ATN结果,验证返回URL
     * @param $notify_id 通知校验ID
     * @return 服务器ATN结果
     * 验证结果集：
     * invalid命令参数不对 出现这个错误，请检测返回处理中partner和key是否为空 
     * true 返回正确信息
     * false 请检查防火墙或者是服务器阻止端口问题以及验证时间是否超过一分钟
     */
	function getResponse($notify_id)
	{
		//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
		$transport = strtolower(trim($this->transport));
		//合作身份者id，以2088开头的16位纯数字
		$partner = trim($this->transport);
		$veryfy_url = '';
		if($transport == 'https')
		{
			$veryfy_url = $this->https_verify_url;
		}
		else
		{
			$veryfy_url = $this->http_verify_url;
		}
		$veryfy_url = $veryfy_url."partner=" . $partner . "&notify_id=" . $notify_id;
		$responseTxt = $this->getHttpResponseGET($veryfy_url, $this->cacert_url);
		
		return $responseTxt;
	}
	
	
	/**
	 * RSA验签
	 * @param $data 待签名数据
	 * @param $ali_public_key_path 支付宝的公钥文件路径
	 * @param $sign 要校对的的签名结果
	 * return 验证结果
	 */
	private function rsaVerify($data, $ali_public_key_path, $sign)
	{
		//$pubKey = file_get_contents($ali_public_key_path);
		$pubKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnxj/9qwVfgoUh/y2W89L6BkRA
FljhNhgPdyPuBV64bfQNN1PjbCzkIM6qRdKBoLPXmKKMiFYnkd6rAoprih3/PrQE
B/VsW8OoM8fxn67UDYuyBTqA23MML9q1+ilIZwBC2AQ2UBVOrFXfFl75p6/B5Ksi
NG9zpgmLCUYuLkxpLQIDAQAB
-----END PUBLIC KEY-----';
	    $res = openssl_get_publickey($pubKey);
	    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
	    openssl_free_key($res);    
    	return $result;
	}
	
	
	/**
	 * 远程获取数据，GET模式
	 * 注意：
	 * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
	 * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
	 * @param $url 指定URL完整路径地址
	 * @param $cacert_url 指定当前工作目录绝对路径
	 * return 远程输出的数据
	 */
	public function getHttpResponseGET($url,$cacert_url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
		curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
		$responseText = curl_exec($curl);
		//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
		curl_close($curl);
		
		return $responseText;
	}
	
	
    public function getPayParam(array$order){}

    public function query(array$order){}


}