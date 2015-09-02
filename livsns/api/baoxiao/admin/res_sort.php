<?php
require './global.php';
define('MOD_UNIQUEID', 'dengji_sort');
class res_sortApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/res_sort.class.php');
        $this->obj = new res_sort();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    public function show() {
       	$condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = array();
        $data = $this->obj->show($condition . $dataLimit);
        if($data)
        {
	        foreach($data as $key => $value)
	        {
	    		        $this->addItem($value);
	        }
        }
        $this->output();          
    }
    public function get_condition()
    {
    	if($_GET['id'])
    	{
	    	$condition = 'where id = '.intval($_GET['id']);
	    }elseif($_GET['sort_id']){
	    	$condition = 'where sort_id = '.intval($_GET['sort_id']);
	    }
	    return $condition;
    }
}
$out = new res_sortApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
?>