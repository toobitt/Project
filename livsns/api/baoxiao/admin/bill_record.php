<?php
require './global.php';
define ('MOD_UNIQUEID', 'bill_record');
class billRecordApi extends adminReadBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/bill_record.class.php');
        $this->obj = new billRecord();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index(){}
    
    public function show()
    {
		if(!$this->user['user_id'])
		{
	    	$this->errorOutput(NO_LOGIN);
		}
	    $condition = $this->get_condition();
	    $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
	    $count = $this->input['count'] ? intval($this->input['count']) : 20;
	    $dataLimit = " ORDER BY create_time DESC LIMIT ". $offset . ", " . $count;
	    $data = array();
	    $data = $this->obj->show($condition.$dataLimit);
	    if($data)
	    {
	        foreach($data as $key => $value)
	        {
	        	$value['img'] = unserialize($value['img']);
	        	$value['img_url'] = hg_fetchimgurl($value['img'],200,200);
		        $this->addItem($value);      
	        }
	    }
	    $this->output();       
    }
    
    public function detail() 
    {
    	$ret = array();
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	if(!empty($id))
    	{
    		$ret = $this->obj->detail($id);
       	}
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
        
        if($this->input['user_id'])
        {
	        $condition .= " AND user_id=" . intval($this->input['user_id']);
        }
        
        if(isset($this->input['bill_id']))
        {
	        $condition .= " AND bill_id=" . intval($this->input['bill_id']);
        }
        if(isset($this->input['ids']))
        {
	        $condition .= " AND id in "."(".trim($this->input['ids']).")";
        }
        return $condition;
    }
}

$out = new billRecordApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
