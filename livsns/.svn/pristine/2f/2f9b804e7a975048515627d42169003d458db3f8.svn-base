<?php
require './global.php';
define ('MOD_UNIQUEID', 'project');
class projectApi extends adminReadBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/project.class.php');
        $this->obj = new project();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function index(){}
    
    public function show() {
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = $this->obj->show($condition . $dataLimit);
        if(!empty($data))
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
        $key = $this->input['name'] ? trim($this->input['name']) : '';
        if($key)
        {
	        $condition .= " AND name like '%" . $key . "%'";
        }
        return $condition;
    }
}

$out = new projectApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
