<?php
require './global.php';
define ('MOD_UNIQUEID', 'user');
class userUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/company.class.php');
        $this->obj = new company();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {	
    	if(empty($this->input['user_name']))
    	{
	    	$this->errorOutput(NO_USERNAME);
    	}    	
    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
		$auth = new auth();
		$ret = $auth->CheckUserName( array("user_name" => trim($this->input['user_name'])));
		//print_r($ret);die;
		if(isset($ret['status']) && $ret['status'])
		{
			$this->errorOutput(USERNAME_EXIST);
		}
		unset($ret);
    	if(empty($this->input['password']))
    	{
	    	$this->errorOutput(NO_PASSWORD);
    	}
    	
    	if(!$this->input['org_id'])
    	{	    	
	    	$cid = $this->input['cid'] ? intval($this->input['cid']) : 0;
	    	$ret = $this->obj->detail($cid);
	    	if(isset($ret['org_id']))
	    	{
		    	$org_id = $ret['org_id'];
	    	}	    	
    	}
    	else
    	{
	    	$org_id = intval($this->input['org_id']);
    	}
    	$return = array();
        if($org_id)
        {
	    	$data = array(
	    		'admin_role_id' => DEFAULT_ROLE,
	    		'father_org_id' => $org_id,
	    		'user_name' => trim($this->input['user_name']),
	    		'password' => trim($this->input['password']),
	    	);
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
			
	    	$return = $auth->create_user($data);
        } 	
        $this->addItem($return);
        $this->output();
    }
    
    public function update()
    {
    	if(empty($this->input['user_name']))
    	{
	    	$this->errorOutput(NO_USERNAME);
    	}
    	if(empty($this->input['id']))
    	{
	    	$this->errorOutput(NO_USERID);
    	}
    	$cid = $this->input['cid'] ? intval($this->input['cid']) : 0;
    	$ret = $this->obj->detail($cid);
    	$return = array();
        if($ret['org_id'])
        {
	    	$data = array(
	    		'id' => intval($this->input['id']),
	    		'admin_role_id' => DEFAULT_ROLE,
	    		'father_org_id' => intval($this->input['org_id']) ? intval($this->input['org_id']) : $ret['org_id'],
	    		'user_name' => trim($this->input['user_name']),
	    		'password' => trim($this->input['password']),
	    	);
	    	include_once(ROOT_PATH . 'lib/class/auth.class.php');
			$auth = new auth();
	    	$return = $auth->update_user($data);
        }  	
        $this->addItem($return);
        $this->output();
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'];
        if (!$id) {
            $this->errorOutput(NO_ID);
        } 
    }
    public function unknow(){}
}

$out = new userUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
