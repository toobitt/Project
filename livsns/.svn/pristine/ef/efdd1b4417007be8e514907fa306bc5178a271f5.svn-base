<?php
define('MOD_UNIQUEID','water_query');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/bus_query.class.php');
require_once(CUR_CONF_PATH . 'lib/buffer.class.php');
class bus_query_api extends adminReadBase
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
	
	public function index(){}
	public function count(){}
	public function detail(){}

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
}

$out = new bus_query_api();
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