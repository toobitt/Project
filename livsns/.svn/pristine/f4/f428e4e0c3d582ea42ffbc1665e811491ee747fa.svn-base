<?php
require './global.php';
define ('MOD_UNIQUEID', 'sort');
class sortUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/sort.class.php');
        $this->obj = new sort();
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
    	$material = array();
    	$material = parent::upload_indexpic();
    	if($material)
    	{	
	    	$logo_info = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$data['logo_info'] = serialize($logo_info);
			$data['logo_id'] = $material['id'];
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
    	$material = array();
    	$material = parent::upload_indexpic();
    	if($material)
    	{	
	    	$logo_info = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$data['logo_info'] = serialize($logo_info);
			$data['logo_id'] = $material['id'];
    	} 
    	$ret = $this->obj->update($data,$id);
        $this->addItem($ret);
        $this->output();
    }
    
    public function update_cost()
    {
    	$id = $this->input['id'] ? intval($this->input['id']) : 0;
    	$action = intval($this->input['action']) ? intval($this->input['action']) : 1;
    	if(empty($id))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	$data = $this->obj->update_cost($id,$action);
        $this->addItem($data);
        $this->output();
    }
    
    private function checkName($name,$id=0)
    {
	    if(!empty($name))
	    {
		  return $this->obj->checkName($name,$id);
	    }
    }
    
    public function reSetCost()
    {
    	$id = $this->input['id'] ? intval($this->input['id']) : 0;
    	$ret = $this->obj->reSetCost($id);    	
    	$this->addItem($ret);
        $this->output();
    }
    
    public function audit()
    {
       
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
    public function unknow(){}
}

$out = new sortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
