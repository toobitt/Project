<?php
require './global.php';
define ('MOD_UNIQUEID', 'audit_record');
class auditRecordUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/audit_record.class.php');
        $this->obj = new auditRecord();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {

	}
    
    public function update()
    {
    
    }
    
    public function audit()
    {
       $id = $this->input['id'] ? trim($this->input['id']) : 0;
       if(!$id)
       {
	      $this->errorOutput(NO_ID); 
       }
       $state = $this->input['audit'] ? intval($this->input['audit']) : 0;
       $reason = $this->input['reason'] ? trim($this->input['reason']) : '';
       $info = $this->obj->audit($id,$state,$reason);//只有审核通过，无打回操作
       if(isset($info['error']))
       {
	       $this->errorOutput($info['error']); 
       }
       $this->addItem($info);
       $this->output(); 
    }
    
    public function delete()
    {
        $id = $this->input['id'];
        if (!$id) {
            $this->errorOutput(NO_ID);
        } 
    }
    
    public function show_audit()
    {
        $bill_id = $this->input['bill_id'] ? trim($this->input['bill_id']) : 0;
        if(!$bill_id)
        {
	       $this->errorOutput(NO_ID); 
        }
        $info = $this->obj->show_audit($bill_id);
        if(isset($info['error']))
        {
	       $this->errorOutput($info['error']); 
        }
        $this->addItem($info);
        $this->output(); 
    }

}

$out = new auditRecordUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
