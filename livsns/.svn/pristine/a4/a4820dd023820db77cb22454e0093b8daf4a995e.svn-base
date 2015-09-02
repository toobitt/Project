<?php
require './global.php';
define('MOD_UNIQUEID', 'dengji_sort');
class res_sort_updateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/res_sort.class.php');
        $this->obj = new res_sort();
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
			$data['logo_url'] = $material['host'].$material['dir'].$material['filepath'].$material['filename'];
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
    	$id = intval($this->input['id']);
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput(NO_NAME);
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    		//'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    	);    	
    	/*$check = $this->obj->checkName($data['name']);
    	if($check)
    	{
	    	$this->errorOutput(NAME_EXIST);
    	}*/
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
			$data['logo_url'] = $material['host'].$material['dir'].$material['filepath'].$material['filename'];
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
$out = new res_sort_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();