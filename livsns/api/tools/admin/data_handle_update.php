<?php
require './global.php';
define ('MOD_UNIQUEID', 'data_handle');
class dataHandleUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/data_handle.class.php');
        $this->obj = new data_handle();
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
    	if(empty($this->input['filename']))
    	{
	    	$this->errorOutput('NO FILENAME');
    	}    	
    	$tmp_bool = $this->obj->checkName($this->input['filename']);
    	if($tmp_bool)
    	{
	    	$this->errorOutput('FILENAME IS EXISTS');
    	}
    	$data = array(
    		'filename' => trim($this->input['filename']) ? trim($this->input['filename']) : '',
    		'brief' => trim($this->input['brief']) ? trim($this->input['brief']) : '',
    		'parameter' => trim($this->input['parameter']) ? addslashes(str_replace("'","\'",$this->input['parameter'])) : '',
    		'sql_content' => trim($this->input['sql_content']) ? addslashes($this->input['sql_content']) : '',
    		'dataformat' => trim($this->input['dataformat']) ? addslashes(str_replace("'","\'",$this->input['dataformat'])) : '',
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
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
    	if(empty($this->input['filename']))
    	{
	    	$this->errorOutput('NO FILENAME');
    	}
    	$tmp_bool = $this->obj->checkName($this->input['filename'],$id);
    	if($tmp_bool)
    	{
	    	$this->errorOutput('FILENAME IS EXISTS');
    	}
    	$data = array(
    		'filename' => trim($this->input['filename']) ? trim($this->input['filename']) : '',
    		'brief' => trim($this->input['brief']) ? trim($this->input['brief']) : '',
    		'parameter' => trim($this->input['parameter']) ? addslashes(str_replace("'","\'",$this->input['parameter'])) : '',
    		'sql_content' => trim($this->input['sql_content']) ? addslashes($this->input['sql_content']) : '',
    		'dataformat' => trim($this->input['dataformat']) ? addslashes(str_replace("'","\'",$this->input['dataformat'])) : '',
    		'update_time' => TIMENOW,
    	);
    	$ret = $this->obj->update($data,$id);
    	if($ret['error'])
    	{
    		$this->errorOutput($ret['error']);
	    	
    	}
        $this->addItem($ret);
        $this->output();
    }
    
    public function create_file()
    {
    	$id = $this->input['id'] ? trim($this->input['id']) : '';
	    $ret = $this->obj->create_file($id);
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
        $ret = $this->obj->delete($id);
        $this->addItem($ret);
        $this->output();
    }
    public function unknow(){}
}

$out = new dataHandleUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
