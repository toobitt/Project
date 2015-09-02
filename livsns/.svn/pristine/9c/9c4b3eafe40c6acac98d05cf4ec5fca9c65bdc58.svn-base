<?php
define('MOD_UNIQUEID','attribute_type');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/attribute_type_mode.php');
class attribute_type_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new attribute_type_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
	    $name     = $this->input['name'];
	    $uniqueid = $this->input['uniqueid'];
	    
	    if(!$name)
	    {
	        $this->errorOutput(NO_TYPE_NAME);
	    }
	    
	    if(!$uniqueid)
	    {
	        $this->errorOutput(NO_UNIQUEID);
	    }
	    else 
	    {
	        //检测是否已经存在该标识
	        if($this->mode->detail(''," AND uniqueid = '" .$uniqueid. "' "))
	        {
	            $this->errorOutput(UNIQUEID_HAS_EXISTS);
	        }
	    }
	
		$data = array(
			'name'         => $name,
		    'uniqueid'     => $uniqueid,
		    'user_id'      => $this->user['user_id'],
		    'user_name'    => $this->user['user_name'],
		    'create_time'  => TIMENOW,
		    'update_time'  => TIMENOW,
		);
		
		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建属性类型',$data,'','创建属性类型' . $vid);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function update()
	{
	    $id = $this->input['id'];
	    $name     = $this->input['name'];
	    $uniqueid = $this->input['uniqueid'];
	    
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
	    if(!$name)
	    {
	        $this->errorOutput(NO_TYPE_NAME);
	    }
	    
	    if(!$uniqueid)
	    {
	        $this->errorOutput(NO_UNIQUEID);
	    }
	    else 
	    {
	        //检测是否已经存在该标识
	        if($this->mode->detail(''," AND uniqueid = '" .$uniqueid. "' AND id != '" .$id. "' "))
	        {
	            $this->errorOutput(UNIQUEID_HAS_EXISTS);
	        }
	    }
		
		$update_data = array(
			'name'         => $name,
		    'uniqueid'     => $uniqueid,
		    'update_time'  => TIMENOW,
		);
		$ret = $this->mode->update($id,$update_data);
		if($ret)
		{
			$this->addLogs('更新属性类型',$ret,'','更新属性类型' . $id);
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
			$this->addLogs('删除属性类型',$ret,'','删除属性类型' . $this->input['id']);
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

$out = new attribute_type_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 