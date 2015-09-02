<?php
define('MOD_UNIQUEID','jssdk_api');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/jssdk_api_mode.php');
class jssdk_api_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new jssdk_api_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $name = trim($this->input['name']);
	    if(!$name)
	    {
	        $this->errorOutput(NO_API_NAME);     
	    }
	    
	    $ename = trim($this->input['ename']);
	    if(!$ename)
	    {
	        $this->errorOutput(NO_API_NAME);
	    }
	    
	    $is_open = intval($this->input['is_open']);
	    $level = intval($this->input['level']);
	    $brief = trim($this->input['brief']);
	    
		$data = array(
			'name'        => $name,
			'ename'       => $ename,
			'is_open'     => $is_open,
			'level'       => $level,
			'brief'       => $brief,
		    'create_time' => TIMENOW,
		    'update_time' => TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$name = trim($this->input['name']);
	    if(!$name)
	    {
	        $this->errorOutput(NO_API_NAME);     
	    }
	    
	    $ename = trim($this->input['ename']);
	    if(!$ename)
	    {
	        $this->errorOutput(NO_API_NAME);
	    }
	    
	    $is_open = intval($this->input['is_open']);
	    $level = intval($this->input['level']);
	    $brief = trim($this->input['brief']);
		
		$update_data = array(
			'name'        => $name,
			'ename'       => $ename,
			'is_open'     => $is_open,
			'level'       => $level,
			'brief'       => $brief,
		    'update_time' => TIMENOW,
		);
		$ret = $this->mode->update($this->input['id'],$update_data);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			//$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new jssdk_api_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();