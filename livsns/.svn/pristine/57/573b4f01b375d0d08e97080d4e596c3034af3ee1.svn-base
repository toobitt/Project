<?php
require './global.php';
define ('MOD_UNIQUEID', 'qingjia_record');
class qingjia_recordApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/qingjia_record.class.php');
        $this->obj = new qingjia_record();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function show() {
    
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
// $this->addItem($data);
        $this->output();       
    }
    
    public function show_xiaojia() {
        $data = array();
        $data = $this->obj->show_xiaojia();
        $this->addItem( $data);
        $this->output();          
    }
    
    public function show_qingjia_message() {
        $data = array();
        $data = $this->obj->show_qingjia_message();
        $this->addItem( $data);
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
    
    public function qingjia_count()
    {   
        $data = array();
        if($this->input['start_time'])
        {
	        $start_time = intval($this->input['start_time']);
        }
        if($this->input['end_time'])
        {
	        $end_time = intval($this->input['end_time']);
        }
        $condition = $this->get_time();
        $data = $this->obj->qingjia_count($condition,$start_time,$end_time);
         $this->addItem($data);
        $this->output();
       
    }
    
    public function get_time()
    {
        $condition = '';
        if($this->input['start_time']  && $this->input['end_time'])
        {
	        $condition .= " AND start_time >= " . intval($this->input['start_time']) ." AND end_time <= " . intval($this->input['end_time']);
            $condition .= " OR start_time <=" . intval($this->input['start_time'])." AND end_time >= " .intval($this->input['end_time']);
	        $condition .= " OR start_time <=" . intval($this->input['end_time'])." AND start_time >= " . intval($this->input['start_time']);
	        $condition .= " OR end_time <=" . intval($this->input['end_time']) ." AND end_time >= " .intval($this->input['start_time']);
        }
        return $condition;
    }

    public function get_condition()
    {
        $condition = '';
        
        if($this->input['user_id'])
        {
	        $condition .= " AND user_id=" . intval($this->input['user_id']);
        }
        return $condition;
    }
}

$out = new qingjia_recordApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
