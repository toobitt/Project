<?php
require './global.php';
define('MOD_UNIQUEID', 'res');
class resApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/res.class.php');
        $this->obj = new res();
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
    	$condition = '';
    	if($_GET['id'])
    	{
    		 $condition = 'where id = '.intval($_GET['id']);
    	}
    	elseif($_GET['sort_id'])
    	{
	    	 $condition = 'where sort_id = '.intval($_GET['sort_id']);
    	}
    	elseif($_GET['keyword'])
    	{
	    	$condition = 'where brand like '."'".'%'.trim($_GET['keyword']).'%'."'";//like '%xxx%'
    	}
    	elseif($_GET['model'])
    	{
	    	 $condition = 'where model = "'. $_GET['model'].'"';
    	}
	    return $condition.' order by sort_id,state DESC';
    }
}
$out = new resApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
?>