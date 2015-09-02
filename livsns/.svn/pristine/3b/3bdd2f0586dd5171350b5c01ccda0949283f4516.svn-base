<?php
define('MOD_UNIQUEID','ship_query');
define('SCRIPT_NAME', 'ship_query_api');
define('BUS_BUFFER_TIME',600);
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_query.class.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
class ship_query_api extends adminBase
{
	private $bus;
    public function __construct()
	{
		parent::__construct();
		$this->bus = new bus();
		$this->buffer = new buffercore();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$this->get_ship();
	}
	
	public function get_ship()
	{
		if(!SHIP_ON_OFF)
		{
			$this->errorOutput('船期管理已关闭,此接口禁止使用,请开启此功能在使用');
		}
		if(!$this->input['drive_date'])
		{
			$this->errorOutput(NO_DRIVE_DATE);
		}
		
		if(!$this->input['rst_name'])
		{
			$this->errorOutput(NO_RST_NAME);
		}
		
		if(!$this->input['dst_name'])
		{
			$this->errorOutput(NO_DST_NAME);
		}
		if(defined('RST_TYPE'))
		{
			$isRstName = RST_TYPE?1:0;
		}
		else
		{
			$isRstName = $this->input['rst_type']?0:1;
		}
		$sst = $isRstName?$this->settings['get_bus_start_stations'][$this->input['rst_name']]:trim($this->input['rst_name']);
		$data = array(
			'date'  => date('Ymd',strtotime($this->input['drive_date'])),
			'sst'	 => $sst,
			'tst'	 => $this->input['dst_name'],
		);
		$ret['data'] = $this->buffer->get_bus($data, 1);
		$output=array();
		$output['total'] = count($ret['data']);
		$output['data']=$ret['data'];
		if(!$output['data'])
		{
			$this->errorOutput(NO_DATA);
		}
		foreach($output['data'] as $key=>$val)
		{
			$passedColor = '';
			if(strtotime($val['departDate'] . ' ' . $val['departTime']) < TIMENOW)
			{
				$passedColor = '808283';
			}
			$output['data'][$key]['passedFontColor'] = $passedColor;
		}
		$this->addItem($output);
		$this->output();
	}
	function get_bus_start_stations()
	{
		if($this->settings['get_bus_start_stations'])
		{
			foreach($this->settings['get_bus_start_stations'] as $key=>$val)
			{
				$item = array(
				'code'=>$key,
				'name'=>$val,
				);
				$this->addItem($item);
			}
		}
		$this->output();
	}
}
include ROOT_PATH . 'excute.php';
?>