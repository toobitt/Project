<?php
require './global.php';
define('MOD_UNIQUEID', 'res');
class resApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/res.class.php');
        $this->obj = new res();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
     public function create()
    {
    	if(empty($this->input['sort_id']))
    	{
	    	$this->errorOutput(NO_SORT);
    	}
    	if(empty($this->input['brand']))
    	{
	    	$this->errorOutput(NO_BRAND);
    	}
    	if(empty($this->input['model']))
    	{
	    	$this->errorOutput(NO_MODEL);
    	}
    	if(empty($this->input['price']))
    	{
	    	$this->errorOutput(NO_PRICE);
    	}
    	$data = array(
    		'sort_id' => intval($this->input['sort_id']) ? intval($this->input['sort_id']) : '',
    		'brand' => trim($this->input['brand']) ? trim($this->input['brand']) : '',
    		'model' => trim($this->input['model']) ? trim($this->input['model']) : '',
    		'price' => trim($this->input['price']) ? trim($this->input['price']) : '',
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    	);   
      	$ret = $this->obj->create($data);
    	$this->addItem($ret);
        $this->output();
    }
     public function update()
    {
    	if(empty($this->input['id']))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	$id = intval($this->input['id']);
    	if(!isset($this->input['user_name']) && !isset($this->input['back']))
    	{
	    	if(empty($this->input['model']))
			{
	    		$this->errorOutput(NO_MODEL);
			}
			if(empty($this->input['brand']))
			{
				$this->errorOutput(NO_BRAND);
			}
			if(empty($this->input['price']))
			{
				$this->errorOutput(NO_PRICE);
			}
			$data = array(
	    		'model' => trim($this->input['model']) ? trim($this->input['model']) : '',
	    		'brand' => trim($this->input['brand']) ? trim($this->input['brand']) : '',
	    		'price'	=> trim($this->input['price']) ? trim($this->input['price']) : '',
	    		'update_time' => TIMENOW,
    	     );    	
    	}else if(isset($this->input['user_name']) && !isset($this->input['back'])){
	    	if(empty($this->input['user_name']))
	    	{
		    	$this->errorOutput(NO_USERNAME);
	    	}
	    	$data = array(
	    		'update_time' => TIMENOW,
	    		'user_name' => trim($this->input['user_name']),
	    		'state' => 1
	    	);
    	}else if(isset($this->input['back'])){
    		$data = array(
		    	'user_name' => '',
		    	'back_time' => TIMENOW,
		    	'state' => 0
	    	);
    	}
       	$ret = $this->obj->update($data,$id);
    	$this->addItem($ret);
        $this->output();
    }
    public function delete()
    {
        $id = trim($this->input['id']);
        if (!$id) {
            $this->errorOutput(NO_ID);
        }
        $data = $this->obj->delete($id);
        $this->addItem($data);
        $this->output();
    }
}
$out = new resApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();