<?php
require './global.php';
define ('MOD_UNIQUEID', 'qingjia_auditor');
class qingjia_auditorApi extends adminReadBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/qingjia_auditor.class.php');
        $this->obj = new qingjia_auditor();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index(){}
    
    public function show() {
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
        if($this->user['user_id'])
        {
	        $condition .= " AND type='qingjia' AND user_id=" . intval($this->user['user_id']); 
        }
        return $condition;
    }
}

$out = new qingjia_auditorApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
