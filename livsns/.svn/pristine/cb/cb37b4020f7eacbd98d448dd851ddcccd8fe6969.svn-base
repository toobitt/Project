<?php
require './global.php';
define ('MOD_UNIQUEID', 'qingjia_audit_record');
class qingjia_audit_recordApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/qingjia_audit_record.class.php');
        $this->obj = new qingjia_audit_record();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function show(){
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
        $condition = $this->get_condition();
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
        $count = $this->input['count'] ? intval($this->input['count']) : 20;
        $dataLimit = " LIMIT ". $offset . ", " . $count;
        $data = array();
        $data = $this->obj->show($condition.$dataLimit); 

        if($data)
        {
	        foreach($data as $key => $value)
	        {
	            $value['bill']['img'] = unserialize($value['bill']['img']);
	        	$value['bill']['img_url'] = hg_fetchimgurl($value['bill']['img'],200,200);
		        $this->addItem($value);      
	        }
        }
        $this->output();       
    }
    
    public function showinfo() {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
        $condition = " AND auditor_id=" . intval($this->user['user_id']);
        $bill_id = $this->input['bill_id'] ? intval($this->input['bill_id']) : 0 ;
        $data = array();
        $data = $this->obj->showinfo($bill_id, $condition);
        $this->addItem($data); 
        $this->output();       
    }
   
   
   public function show_message() {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
        $id = $this->input['id'] ? intval($this->input['id']) : 0 ;
        $data = array();
        $data = $this->obj->show_message($id);
        $this->addItem($data); 
        $this->output();       
    }
    
    public function audit()
	{
	     $id = $this->input['id'] ? trim($this->input['id']) : 0;
         $str = $this->input['str'];
         if(!$id)
	     {
		    $this->errorOutput(NO_ID); 
	     }
	     if($str == "shenp"){
	        $state = 1 ;
	     }else{
	        $state =  2 ; 
	     }
	     $reason = $this->input['reason'] ? trim($this->input['reason']) : '';
	     $info = $this->obj->audit($id,$state,$reason);//只有审核通过，无打回操作
	     if(isset($info['error']))
	     {
		    $this->errorOutput($info['error']); 
	     }
	     $this->addItem($info);
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
	        $condition .= " AND user_id=" . intval($this->user['user_id']);
        }
        return $condition;
    }
    
}

$out = new qingjia_audit_recordApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'show';
}
$out->$action();
