<?php
define('MOD_UNIQUEID','bus_query');
define('SCRIPT_NAME', 'bus_query_api');
define('BUS_BUFFER_TIME',600);
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_query.class.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
require_once(CUR_CONF_PATH . 'lib/functions.php');
class bus_query_api extends adminBase
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
	//常州
	public function show()
	{
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
		
		$data = array(
			'drive_date' => date('Ymd',strtotime($this->input['drive_date'])),
			'rst_name'	 => $this->input['rst_name'],
			'dst_name'	 => $this->input['dst_name'],
		);

		$buffer_key = md5(serialize($data));
		$output = $this->buffer->select($buffer_key);
		$output = $output ? json_decode($output,1) : array();
		if(!$output)
		{
		    $result = $this->bus->query($data);
		    if($result['success'])
		    {
		    	$this->errorOutput($result['msg']);
		    }
			$output = array();
			$output['total'] = count($result['data']);
			$output['data'] = $result['data'] ? $result['data'] : array();
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		
		//$ret = $this->bus->query($data);
		if(!$output)
		{
			$this->errorOutput(NO_DATA);
		}
		$this->addItem($output);
		$this->output();
	}
	
	//徐州
	public function get_buses()
	{
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
		
		$xzdata = array(
			'sdate'  => date('Ymd',strtotime($this->input['drive_date'])),
			'sst'	 => $this->input['rst_name'],
			'tst'	 => $this->input['dst_name'],
		    'output' => isset($this->input['output']) ? $this->input['output'] : 'json',
		);
		$buffer_key = md5(serialize($xzdata));
		$output = $this->buffer->select($buffer_key);
		$output = $output ? json_decode($output,1) : array();
		if(!$output)
		{
			//exit('1');
			$result = $this->bus->query_xzapi($xzdata);
		    $output = array();
			$output['total'] = count($result['data']);
			$output['data'] = $result['data'] ? $result['data'] : array();
			$this->buffer->replace($buffer_key, json_encode($output));
		}
		if(!$output)
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
	//盐城
	public function get_bus_yc()
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,此接口禁止使用,请开启此功能在使用');
		}
		if ($this->input['offset'])
		{
			$this->output();
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
		$stations = array_flip($this->settings['get_bus_start_stations']);
		$sst = $isRstName?$this->input['rst_name']:$stations[$this->input['rst_name']];
		$data = array(
			'date'  => date('Ymd',strtotime($this->input['drive_date'])),
			'sst'	 => $sst,
			'tst'	 => $this->input['dst_name'],
		);
		$ret = $this->buffer->query($data);
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
	
	public function get_bus()
	{
		if(!BUS_ON_OFF)
		{
			$this->errorOutput('客运管理已关闭,此接口禁止使用,请开启此功能在使用');
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
		$ret['data'] = $this->buffer->get_bus($data);
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