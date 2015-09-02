<?php
require './global.php';
define ('MOD_UNIQUEID', 'tables');
class tablesApi extends adminBase
{
    public function __construct() {		
		$this->mPrmsMethods = array(
			'manger' => '管理',
		);
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/tables.class.php');
        $this->obj = new tables();
        $prms_data = array(
        	'_action' => 'manger',
        );
    	$this->verify_content_prms($prms_data);
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function show() {
    	if(!$this->user['user_id'])
	    {
		    $this->errorOutput('NO LOGIN');
	    }
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        
        $data = $this->obj->show($condition . $dataLimit);
        foreach($data as $key => $value)
        { 
        	$this->addItem($value);
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
        return $condition;
    }
}

$out = new tablesApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
