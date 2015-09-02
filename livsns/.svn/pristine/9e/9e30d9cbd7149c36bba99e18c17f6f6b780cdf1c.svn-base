<?php
/**
 *供外部调用发送验证码 
 */
define('MOD_UNIQUEID','authcode');
require_once('global.php');
class authcode extends outerReadBase
{
	private $code;
    public function __construct()
	{
		parent::__construct();
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
        
        $content = $this->input['content'];
        if(!$content)
        {
        	$this->errorOutput(NO_CONTENT);
        }
        
        //发送验证码
        if($this->sendSms($telephone,$content,intval($this->input['is_meeting'])))
        {
	        $this->addItem_withkey('return',1);
        	$this->output();
        }
        else 
        {
        	$this->errorOutput(SEND_CODE_FALSE);
        }
	}
    
    public function sendSms($telephone = '',$content = '',$is_meeting = 0)
    {
    	if(!$telephone || !$content)
    	{
    		return false;
    	}
    	
    	$config = $this->settings['sms_code'];
    	
        if($is_meeting)
    	{
            $config['sign'] = '【厚建软件】';
            $config['subcode'] = '';
    	}
    	
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

$out = new authcode();
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