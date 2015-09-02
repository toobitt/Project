<?php
require './global.php';
define ('MOD_UNIQUEID', 'qingjia_sort');
class qingjia_sortApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/qingjia_sort.class.php');
        $this->obj = new qingjia_sort();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function show() {
        //$condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = array();
        $data = $this->obj->show($condition . $dataLimit);
        if($data)
        {
	        foreach($data as $key => $value)
	        {
	        	$value['logo_info'] = unserialize($value['logo_info']);
	        	$value['logo_url'] = hg_fetchimgurl($value['logo_info'],100,100);
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
        	$ret['logo_info'] = unserialize($ret['logo_info']);
        	$ret['logo_url'] = hg_fetchimgurl($ret['logo_info'],100,100);
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
        return $condition;
    }

    
   }

$out = new qingjia_sortApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
