<?php
define('MOD_UNIQUEID','send_authcode');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/auth_code_mode.php');
class send_authcode extends outerReadBase
{
	private $mode;
	private $code;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new auth_code_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}

	public function show()
	{
		//获取手机号
		$telephone = trim($this->input['telephone']);
	 	if (!$telephone) 
        {
            $this->errorOutput(NOTEL);
        }
        
	 	//验证手机号格式
        if(!preg_match('/^1[3-8]\d{9}$/', $telephone)) 
        {
            $this->errorOutput(ERROR_FORMAT_TEL);
        }
        
        //接收验证码的类型（1：注册用 2：忘记密码）
        $type = intval($this->input['type']);
        if(!$type || !in_array($type,array(1,2,3)))
        {
        	$type = 1;//默认是注册
        }

        //检查同一手机号的频率有没有在规定的时间内超过限制
        if($this->settings['sms_code_limit']['telephone']['time_limit'])
        {
        	$stime = TIMENOW - $this->settings['sms_code_limit']['telephone']['time_limit'];
            $condition = " AND create_time > " . $stime . " AND create_time < " . TIMENOW . " AND telephone = '".$this->input['telephone']."' AND type = '" .$type. "' ";
            $total = $this->mode->count($condition);
            if ($total['total'] >= $this->settings['sms_code_limit']['telephone']['num_limit']) 
            {
                $this->errorOutput(TELEPHONE_RATE_FAST);
            }
        }
        
        //同一ip限制
        /******************IP限制******************/
        $ip = hg_getip();
        if ($this->settings['sms_code_limit']['ip']['time_limit']) 
        {
            $stime = TIMENOW - $this->settings['sms_code_limit']['ip']['time_limit'];
            $condition = " AND create_time > " . $stime . " AND create_time < " . TIMENOW . " AND ip = '".$ip."' AND type = '" .$type. "' ";
            $total = $this->mode->count($condition);
            if ($total['total'] >= $this->settings['sms_code_limit']['ip']['num_limit']) 
            {
                $this->errorOutput(BEYOND_THE_IP_LIMIT);
            }
        }
        /******************IP限制******************/
        
        /******************查询此手机号是否有未使用的验证码**********************/
        $condition = " AND telephone='".$this->input['telephone']."' AND status = 1 AND type = '" .$type. "' ";
        $authCode = $this->mode->detail('', $condition);
        $_expire_time = defined('AUTHCODE_EXPIRED') ? TIMENOW + AUTHCODE_EXPIRED : TIMENOW + 300;
        if (!$authCode)
        {
            $this->mkAuthCode();
        }
        else
        {
            $this->mode->audit($authCode['id']);   //标记为已使用 一个手机号最多只能有一个未使用的验证码
            $this->code = $authCode['code'];
            //如果该验证码已经过期就重新生成验证码
            if($authCode['expire_time'] < TIMENOW)
            {
				 $this->mkAuthCode();
            }
            else 
            {
            	//如果没有过期，过期时间还是原来的
            	$_expire_time = $authCode['expire_time'];
            }
        }
        /******************查询此手机号是否有未使用的验证码**********************/
        
        //发送验证码
        if($this->sendSms($telephone,$type))
        {
        	//保存发送的验证码
	        $data = array(
	            'telephone'     => $this->input['telephone'],
	            'code'          => $this->code,
	            'create_time'   => TIMENOW,
	            'expire_time'  	=> $_expire_time,
	            'status'        => 1,
	            'type'        	=> $type,
	            'ip'            => $ip,
	        );    
	        $this->mode->create($data);
	        //返回结果
	        $this->addItem(array('return' => 1));
        	$this->output();
        }
        else 
        {
        	$this->errorOutput(SEND_CODE_FALSE);
        }
	}
	
	//产生验证码
	private function mkAuthCode()
	{
		$this->code = hg_rand_num(6);	
	}
	
	//发短信
	/*
 	private function sendSms()
    {
    	$config = $this->settings['sms_code'];
        $preg = array('{code}', '{time}');
        $replace = array($this->code, defined('AUTHCODE_EXPIRED') ? (AUTHCODE_EXPIRED/60) : 5);
        $content = str_replace($preg, $replace, $config['content']);   
              
        $data = array(
            'Account' 	=> $config['account'],
            'Password'	=> md5($config['password']),
            'Content'  	=> iconv("UTF-8","GB2312",$content),
        	'SGID' 		=> $config['sgid'], 
            'Phone' 	=> $this->input['telephone'],
        );
   
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_URL, $config['request_send_url'] . '?' . http_build_query($data));
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        $reponse = curl_exec($ci);
        curl_close($ci);
    	if($reponse)
		{
			$ret = xml2Array($reponse);
			if(!$ret || !$ret['response'] || intval($ret['response']) != 1)
			{
				return false;
			}
			return true;
		}
		else 
		{
			return false;
		}
    }
    */
    
    public function sendSms($telephone = '',$type = 1)
    {
    	if(!$telephone)
    	{
    		return false;
    	}
    	
    	$config = $this->settings['sms_code'];
        $preg = array('{code}', '{time}');
        $replace = array($this->code, defined('AUTHCODE_EXPIRED') ? (AUTHCODE_EXPIRED/60) : 5);
        $content = str_replace($preg, $replace, $config['content'][$type]);   
        
        $message = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"
					.	"<message>"
					.	"<account>" . $config['account'] . "</account>"
					.	"<password>". md5($config['password']). "</password>"
					. 	"<msgid></msgid>"
					. 	"<phones>". $telephone. "</phones>"
					. 	"<content>". $content. "</content>"
					. 	"<sign>" . $config['sign'] . "</sign>"
					.	"<subcode>" . $config['subcode'] . "</subcode>"
					.	"<sendtime></sendtime>"
					.	"</message>";
        
		$params = array('message' => $message);
		$data = http_build_query($params);
		$context = array(
			'http' 	=> array(
					'method' 	=> 'POST',
					'header'  	=> 'Content-Type: application/x-www-form-urlencoded',
					'content' 	=> $data
				)
		);
		
		$reponse = file_get_contents($config['request_send_url'], false, stream_context_create($context));
    	if($reponse)
		{
			$ret = xml2Array($reponse);
			if($ret && is_array($ret) && !$ret['result'])
			{
				return true;
			}
			return false;
		}
		else 
		{
			return false;
		}
    }
}

$out = new send_authcode();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>