<?php
require './global.php';
define ('MOD_UNIQUEID', 'bill');
class billApi extends adminReadBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/bill.class.php');
        $this->obj = new bill();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index(){}
    
    public function show() {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        if($this->input['start_time'] && $this->input['end_time'])
        {
	        $dataLimit = " ORDER BY create_time DESC ";
        }
        else
        {
	        $dataLimit = " ORDER BY create_time DESC LIMIT ". $offset . ", " . $count;
        }
        $data = array();
        $data = $this->obj->show($condition.$dataLimit);
        if($data)
        {
	        foreach($data as $key => $value)
	        {
		        $this->addItem($value);      
	        }
        }
        $this->output();       
    }
    
    public function myselft()
    {
	    $condition = $this->get_condition();
	    $condition .= " AND user_id=" . intval($this->user['user_id']);
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = array();
        $data = $this->obj->show($condition.$dataLimit);
        if($data)
        {
	        foreach($data as $key => $value)
	        {
		        $this->addItem($value);      
	        }
        }
        $this->output(); 
    }
    
    public function detail() 
    {
    	$ret = array();
    	$id = intval($this->input['id']) ? intval($this->input['id']) : 0;
    	$ret = $this->obj->detail($id);
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
        }if($this->input['bill_id'])
        {
	        $condition .= " AND id IN (" . trim($this->input['bill_id']) . ")";
        }
        if($this->input['start_time'] && $this->input['end_time'])
        {
	        $condition .= " AND update_time >= " . intval($this->input['start_time']) ." AND update_time <= " . intval($this->input['end_time']);
	        $condition .= " AND pay = 1 ";
        }
        return $condition;
    }
}

$out = new billApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
