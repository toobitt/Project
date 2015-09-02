<?php
require './global.php';
define ('MOD_UNIQUEID', 'tables_sort');
class tablesSortUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/tables_sort.class.php');
        $this->obj = new tables_sort();
        
        $prms_data = array(
        	'_action' => 'manger',
        );
    	$this->verify_content_prms($prms_data);
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput('NO NAME');
    	}
    	$tmp_bool = $this->obj->checkName($this->input['name']);
    	if($tmp_bool)
    	{
	    	$this->errorOutput('NAME IS EXISTS');
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    	);
    	
    	$ret = $this->obj->create($data);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
    	$this->addItem($ret);
        $this->output();
    }
    
    public function update()
    {
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(empty($id))
    	{
	    	$this->errorOutput('NO ID');
    	}
    	if(empty($this->input['name']))
    	{
	    	$this->errorOutput('NO NAME');
    	}
    	$tmp_bool = $this->obj->checkName($this->input['name'],$id);
    	if($tmp_bool)
    	{
	    	$this->errorOutput('NAME IS EXISTS');
    	}
    	$data = array(
    		'name' => trim($this->input['name']) ? trim($this->input['name']) : '',
    	);
    	
    	$ret = $this->obj->update($data,$id);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
        $this->addItem($ret);
        $this->output();
    }
    
    public function audit()
    {
       
    }
    
    public function delete()
    {
        $id = $this->input['id'];
        if (!$id) {
            $this->errorOutput('NO ID');
        }
    }
    public function unknow(){}
}

$out = new tablesSortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
