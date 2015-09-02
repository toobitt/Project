<?php
require './global.php';
define ('MOD_UNIQUEID', 'data_manager');
class dataManagerApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/data_manager.class.php');
        $this->obj = new data_manager();
        $prms_data = array(
        	'_action' => 'manger',
        );
    	$this->verify_content_prms($prms_data);
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    public function show() {
	    $condition = $this->get_condition();
	    $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = $this->obj->show($condition . $dataLimit,intval($this->input['need_field']),$dataLimit);
        $this->addItem($data);
        $this->output(); 
	    
    }
    public function show_list() {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        
        $data = $this->obj->show($condition . $dataLimit,intval($this->input['id']));
        foreach($data as $key => $value)
        { 
        	$this->addItem($value);
        }
        $this->output();          
    }
    
    public function get_field()
    {
    	$table_name = $this->input['table_name'] ? trim($this->input['table_name']) : 0;
    	$ret = array();
	    if($table_name)
	    {
		    $ret = $this->obj->get_field($table_name);
	    }
	    $this->addItem($ret);
        $this->output();
    }
    
    public function detail() 
    {
    	$ret = array();
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	$table_name = trim($this->input['table_name']) ? trim($this->input['table_name']) : '';
    	if(empty($table_name))
    	{
    		$this->errorOutput(NO_TABLE_NAME);
    	}
    	$ret = $this->obj->detail($table_name,$id);
        $this->addItem($ret);
        $this->output();
    }
    
    public function count()
    {
        $condition = $this->get_condition();
        $total = $this->obj->count($condition);
        echo json_encode($total);
    }
    
    public function get_condition()
    {
        $condition = '';
        $id = $this->input['id'] ? intval($this->input['id']) : 0;
        $_colid = $this->input['_colid'] ? intval($this->input['_colid']) : 0;
        $table_name = $this->input['table_name'] ? trim($this->input['table_name']): '';
        $this->input['need_field'] = 0;
        if(!$id && !$_colid && !$table_name)
	    {
	    	$this->errorOutput(NO_ID);		    
	    }
	    else
	    {
		    $this->input['need_field'] = 1;
	    }
	    if($table_name)
	    {
		    $condition .= " AND table_name='" . $table_name . "'";
	    }
	    if($_colid)
	    {
		    $condition .= " AND id =" . $_colid;
	    }
	    if($id>0)
	    {
	    	$condition .= " AND id =" . $id;
	    }
        return $condition;
    }
}

$out = new dataManagerApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
?>