<?php
require './global.php';
define ('MOD_UNIQUEID', 'auditor');
class auditorUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
        $this->obj = new auditor();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	if(!$this->input['userinfo'])
    	{
	    	$this->errorOutput(NO_UERINFO);
    	}
    	$info = array();
      	$info = $this->input['userinfo'];
    	$userinfo = array();
    	//审核人信息
    	foreach($info as $k => $v)
    	{
    		$tmp = explode('--',$v);
	    	$userinfo[] = array(
	    		'user_id' => $tmp[0],
	    		'user_name' => $tmp[1],
	    		'audit_level' => 0 ,
	    	);
    	}    
    	//file_put_contents('../cache/sss1',var_export($userinfo,1));	
    	$data = array(
    		'user_id' => $this->user['user_id'],
    		'type' => 'qingjia',
    		'info' => serialize($userinfo),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    //file_put_contents('../cache/sss',var_export($data,1));
    	$ret = $this->obj->create($data);
    	$this->addItem($ret);
	    $this->output();
	}
	
	    
    public function update()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	if(empty($this->input['id']))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	if(!$this->input['userinfo'])
    	{
	    	$this->errorOutput(NO_UERINFO);
    	}
    	$id = intval($this->input['id']);
    	$info = array();
      	$info = $this->input['userinfo'];
    	$userinfo = array();
    	//审核人信息
    	foreach($info as $k => $v)
    	{
    		$tmp = explode('--',$v);
	    	$userinfo[] = array(
	    		'user_id' => $tmp[0],
	    		'user_name' => $tmp[1],
	    		'audit_level' => 0 ,
	    	);
    	}  
    	$info = array();
    	$data = array(
    		'user_id' => $this->user['user_id'],
    		'type' => 'qingjia',
    		'info' => serialize($userinfo),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    	$ret = $this->obj->update($data,$id);
        $this->addItem($ret);
        $this->output();
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = trim($this->input['id']) ? trim($this->input['id']) : 0;
        if (!$id) {
            $this->errorOutput(NO_ID);
        }
        $ret = $this->obj->delete($id);
        if($ret['error'])
        {
	        $this->errorOutput($ret['error']);
        }
        else
        {
	       $this->addItem($ret);
	       $this->output(); 
        }
    }
}

$out = new auditorUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
