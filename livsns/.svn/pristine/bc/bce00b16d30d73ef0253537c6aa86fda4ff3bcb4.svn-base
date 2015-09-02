<?php
require './global.php';
define ('MOD_UNIQUEID', 'project');
class projectUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/project.class.php');
        $this->obj = new project();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput(NO_NAME);
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    	$check = $this->obj->checkName($data['name']);
    	if($check)
    	{
	    	$this->errorOutput(NAME_EXIST);
    	}
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
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput(NO_NAME);
    	}
    	$id = intval($this->input['id']);
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		'update_time' => TIMENOW,
    	);
    	$check = $this->obj->checkName($data['name'],$id);
    	if($check)
    	{
	    	$this->errorOutput(NAME_EXIST);
    	}
    	$ret = $this->obj->update($data,$id);
        $this->addItem($ret);
        $this->output();
    }
    
    public function update_business()
    {
    	$id = $this->input['id'] ? intval($this->input['id']) : 0;
    	$action = intval($this->input['action']) ? intval($this->input['action']) : 1;
    	if(empty($id))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	$data = $this->obj->update_business($id,$action);
        $this->addItem($data);
        $this->output();
    }    
    
    public function reSetBusiness()
    {
    	$id = $this->input['id'] ? intval($this->input['id']) : 0;
    	$ret = $this->obj->reSetBusiness($id);
    	$this->addItem($ret);
        $this->output();
    }
    
    private function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		  return $this->obj->checkName($name,$id);
	    }
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'] ? trim($this->input['id']) : '';
        if (!$id) {
            $this->errorOutput(NO_ID);
        } 
        $data = $this->obj->delete($id);
        $this->addItem($data);
        $this->output();
    }
    public function unknow(){}
}

$out = new projectUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
