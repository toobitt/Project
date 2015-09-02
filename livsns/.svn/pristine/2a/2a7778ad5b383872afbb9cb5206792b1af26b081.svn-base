<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: program_record_update.php 4728 2011-10-12 10:38:02Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','payment');//模块标识
class paymentApi extends adminReadBase
{
	private $pay_type;
	private $obj;
	function __construct()
	{
		parent::__construct();
		$this->pay_type = $this->input['pay_type'] ? trim($this->input['pay_type']) : 'alipay';
		include_once(CUR_CONF_PATH . 'lib/' . $this->pay_type . '.class.php');
		include_once(CUR_CONF_PATH . 'lib/' . $this->pay_type . '.func.php');
		$this->obj = new $this->pay_type();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	
	/*
	* 支付
	*/
	public function form()
	{
		if(isset($this->settings['payment_key']) && !empty($this->settings['payment_key'][$this->pay_type]))
		{
			$key = $this->settings['payment_key'][$this->pay_type];
			$data = array();
			$key_tmp = explode(',',$key['key']);
			if(!empty($key_tmp))
			{
				foreach($key_tmp as $k => $v)
				{
					$data[$v] = $key[$v] ? $key[$v] : ($this->input[$v] ? $this->input[$v] : '');
				}
				$ret = $this->obj->create($data);
				$this->addItem(htmlspecialchars($ret));
				$this->output();
			}
		}
		else
		{
			$this->errorOutput('暂不支持' . $this->pay_type . '支付方式！');
		}		
	}

	public function verifyReturn()
	{
		if(isset($this->settings['payment_config']) && !empty($this->settings['payment_config'][$this->pay_type]))
		{
			$data = array(
				'get_data' => trim($this->input['get_data']),
			);
			$ret = $this->obj->verifyReturn(json_decode($data['get_data'],true));

			$this->addItem($this->settings);
			$this->output();
			//hg_pre();
			//			
		}
		else
		{
			$this->errorOutput('暂不支持' . $this->pay_type . '支付方式！');
		}
	}
	
	public function notify_url()
	{
		
	}
	
	public function show()
	{
	
	}
		
	public function detail()
	{
		
	}
	
	public function count()
	{
		
	}
	
	public function index()
	{
		
	}
}

$out = new paymentApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>