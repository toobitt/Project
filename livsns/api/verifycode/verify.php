<?php
/***************************************************************************
* $Id: verifycode.php  2013-12-02
***************************************************************************/
define('MOD_UNIQUEID', 'verify_code');
define('ROOT_DIR', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_DIR."global.php");
require(CUR_CONF_PATH."lib/functions.php");
class verify extends outerReadBase
{
	function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/curl.class.php');
      	$this->curl = new curl($this->settings['App_verifycode']['host'],$this->settings['App_verifycode']['dir']);
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$sql  = "SELECT id,name,is_dipartite FROM ".DB_PREFIX."verify WHERE status=1";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function detail()
	{
		if($this->input['is_message']) //短信验证码
		{
			$phone_number = trim($this->input['phone_number']);
			if(!$phone_number)
			{
				$this->errorOutput('请输入手机号码');
			}
			if(strlen($phone_number) != '11')
			{
				$this->errorOutput('手机号码格式不正确');
			}
			if(!preg_match('/^(?:13|14|15|17|18)[0-9]{9}$/',$phone_number))
			{
				$this->errorOutput('手机号码格式不正确');
			}
			//取短信服务器配置
			$sql = "SELECT * FROM " .DB_PREFIX. "verify_message WHERE id=2";
			$config = $this->db->query_first($sql);
			if(!$config['length'] || !$config['send_url'] || !$config['send_content'])
			{
				$this->errorOutput('短信服务器配置缺失');
			}
			//生成验证码字符
			$str = $this->settings['verify_message_str'];
			$code = '';
			for($i=0;$i<$config['length'];$i++)
			{
				$code .= $str[rand(0,strlen($str)-1)];
			}
			//echo $code;exit;
			if(!$code)
			{
				$this->errorOutput('验证码生成失败');
			}
			//生成短信内容
			$content = str_replace('{&#036;c}', $code, $config['send_content']);
			if(!$content)
			{
				$this->errorOutput('短信内容生成失败');
			}
			//发送短信
			$url = $config['send_url'];
			$url = str_replace('{&#036;mobile}', $phone_number, $url);
			$url = str_replace('{&#036;content}', $content, $url);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$ret = curl_exec($ch);
			curl_close($ch);
			if(!$ret)
			{
				$this->errorOutput('短信发送失败');
			}
			//验证码入库
			$data_message = array(
				'session_id' => $phone_number,
				'code' => $code,
				'create_time' => TIMENOW,
			);
			$id = $this->db->insert_data($data_message, 'verify_code');
			if($id)
			{
				//返回html代码
				//$html = '<input name="aaa">';
				//$this->addItem(array('ret' => $html));
				$this->addItem('success');
				$this->output();
			}
		}
		else //图片验证码
		{
			$type = $this->input['type'];
			if(!$type) //没传验证码id就取默认验证码 (在验证码应用中开启)
			{
				$sql = "SELECT id FROM " .DB_PREFIX. "verify WHERE is_default=1";
				$re = $this->db->query_first($sql);
				if($re['id'])
				{
					$type = $re['id'];
				}
				else
				{
					$this->errorOutput('没有验证码id');
				}
			}
			$sql = "SELECT is_dipartite FROM ".DB_PREFIX."verify WHERE id=".$type;
			$q = $this->db->query_first($sql);
			$is_dipartite = $q['is_dipartite'];
			$session_id = md5(time().rand(1000000000,9999999999));
	        $this->curl->setSubmitType('post');
	        $this->curl->setReturnFormat('json');
	        $this->curl->initPostData();
	        $this->curl->addRequestData('a','set_verify_code');
	        $this->curl->addRequestData('type',$type);
	        $this->curl->addRequestData('html', true);
	        $this->curl->addRequestData('session_id',$session_id);
	        $ret = $this->curl->request('verifycode.php');
	        header('Content-type:image/png');
			$file_content = base64_encode($ret);
			$img = 'data:image/jpg;base64,'.$file_content;
			$data['img'] = $img;
			$data['session_id'] = $session_id;
			$data['is_dipartite'] = $is_dipartite;
	        $this->addItem($data);
			$this->output();
		}
	}
	
	public function count(){}

	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}

$out = new verify();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>