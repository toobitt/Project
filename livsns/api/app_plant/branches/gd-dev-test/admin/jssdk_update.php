<?php
define('MOD_UNIQUEID','jssdk');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/jssdk_mode.php');
class jssdk_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new jssdk_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $domain = trim($this->input['domain']);
	    if(!$domain)
	    {
	         $this->errorOutput(NO_DOMAIN);     
	    }
	    
	    $is_open = intval($this->input['is_open']);
	    $level = intval($this->input['level']);
	    
		$data = array(
			'domain'      => $domain,
			'is_open'     => $is_open,
			'level'       => $level,
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
		
	    $domain = trim($this->input['domain']);
	    if(!$domain)
	    {
	         $this->errorOutput(NO_DOMAIN);     
	    }
	    
	    $is_open = intval($this->input['is_open']);
	    $level = intval($this->input['level']);
	    
		$update_data = array(
			'domain'      => $domain,
		    'is_open'	  => $is_open,
		    'level'       => $level,
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

$out = new jssdk_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 