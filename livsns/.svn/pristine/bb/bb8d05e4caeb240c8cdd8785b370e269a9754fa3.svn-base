<?php
require './global.php';
define ('MOD_UNIQUEID', 'qingjia_record');
class qingjia_record_updateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/qingjia_record.class.php');
        $this->obj = new qingjia_record();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	if(intval($this->input['sort_id'])<0)
    	{
	    	$this->errorOutput(NO_SORT);
    	}
    	if(isset($this->input['auditor_id']) && intval($this->input['auditor_id'])<0)
    	{
	    	$this->errorOutput(NO_AUDITOR_ID);
    	}
    	
    	$data = array(
    		'user_id' => $this->user['user_id'],
    		'sort_id' => intval($this->input['sort_id']),
    		'remark' => trim($this->input['remark']),
    		'auditor_id' => intval($this->input['auditor_id'])>0?intval($this->input['auditor_id']):0, 
    		'img' => '',
    		'start_time' => strtotime(trim($this->input['start_time'])),
    		'end_time' => strtotime(trim($this->input['end_time'])),
    		'state' => intval($this->input['state']),
    		'is_approve' => intval($this->input['is_approve']),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);  
    	$material = array();
    	$material = parent::upload_indexpic();
    	if($material)
    	{	
	    	$logo_info = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$data['img'] = serialize($logo_info);
			//$data['material_id'] = $material['id'];
    	}
    
    	//print_r($logo_info);
    	//if($data['state'])//审核通过新增
    	//{
    	//	$this->reTotalSort($data['sort_id'],0);
    	//}
    	$ret = $this->obj->create($data); 
    	
    	    	//$rets =$this->obj->create($audit);  	
    	/***if(intval($this->input['bill_id']))	
    	{
    		$this->retotal(intval($this->input['bill_id']));	
    	} **/
    
    	$this->addItem($ret);
        $this->output();
    }
    
     public function reset_data()
     {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}	
    	$data = array(
    		'user_id' => $this->user['user_id'],
        	);  
    	$ret = $this->obj->reset_data($data);   	    	    
    	$this->addItem($ret);
        $this->output();
    }
    
    
    
    
    public function update()
    {
    	if(empty($this->input['id']))
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	
    	if(intval($this->input['sort_id'])<0)
    	{
	    	$this->errorOutput(NO_SORT);
    	}
    	$id = intval($this->input['id']);
    	$data = array(
    		'sort_id' => intval($this->input['sort_id']),
    		'remark' => trim($this->input['remark']),
    		//'is_ticket' => intval($this->input['is_ticket']),
       		'start_time' => strtotime(trim($this->input['start_time'])),
       	    'end_time' => strtotime(trim($this->input['end_time'])),
    		'state' => intval($this->input['state']),
    		'update_time' => TIMENOW,
    		
    	);
    	$material = array();
    	$material = parent::upload_indexpic();
    	if($material)
    	{		    	$logo_info = array(
				'host' => $material['host'],
				'dir' => $material['dir'],
				'filepath' => $material['filepath'],
				'filename' => $material['filename'],
			);
			$data['img'] = serialize($logo_info);
			//$data['material_id'] = $material['id'];
    	 }
    	//$old_info = $this->obj->detail($id);    
    	/***

    		if($old_info['state'])//原来是审核通过，不管是否换sort，旧的sort 先-1
    	{
    		$this->reTotalSort(0,$old_info['sort_id']);
    	}    **/
    	$ret = $this->obj->update($data,$id);    /***
    	if($ret['state'])//如果新的审核通过，不管是否换sort,当前的sort +1
    	{
    		$this->reTotalSort($data['sort_id'],0);
    	}
    	//if(intval($this->input['bill_id']))	
    	//{
    		//$this->retotal(intval($this->input['bill_id']));	
    	//}
    	**/
    	//print_r($data);
        $this->addItem($ret);
        $this->output();
    }
    
    public function audit()
    {
       	$ids = trim($this->input['id']);
	    if (!$ids) {
	        $this->errorOutput(NO_ID);
	    }
	    $state = intval($this->input['audit']) ? 1 : 2;
        $info = $this->obj->audit($ids,$state);
        if(!$info['error'])
        {
	    	$this->retotal($info['bill_id']);
	        $this->addItem($info);
	        $this->output(); 
        }
        else
        {
	        $this->errorOutput($info['error']);
        }  
    }
    
    
    public function xiaojia()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	$data = array(
    	    'record_id' => intval($this->input['record_id']),
    	    'sort_id' => intval($this->input['sort_id']),
    		'start_time' => strtotime(trim($this->input['start_time'])),
    		'end_time' => strtotime(trim($this->input['end_time'])),
    		'state' => intval($this->input['state']),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);  
 
        $ret = $this->obj->xiaojia($data); 
  
    	$this->addItem($ret);
        $this->output();
    }
      
    
    private function reTotalSort($sort_id = 0,$old_sort_id = 0)//xin
    {
    	if($sort_id)
    	{
	    	$action = 1;
	    	$this->obj->updateSortCount($sort_id,$action);
	    	return true;	    	
    	}
    	if($old_sort_id)
    	{
    		$action = -1;
    		$this->obj->updateSortCount($old_sort_id,$action);
    		return true;
    	}
    	return false;
    }    
    
    public function reaccess()
    {
    	$bill_id = $this->input['id'] ? intval($this->input['id']) : 0;
    	if(!$bill_id)
	    {
		    $this->errorOutput(NO_BILLID);
	    }
    	if($bill_id)
    	{
	    	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
	    	$this->bill = new bill();
	    	$checkbool = $this->bill->checkLocked($bill_id);
	    	if($checkbool)
	    	{
	    		$this->errorOutput(THIS_IS_LOCKED);
	    	} 
    	}
	    $ret = $this->retotal($bill_id);
	    $this->addItem($ret);
        $this->output();
    }
    
    private function retotal($id)
    {
	    
	    $condition = " AND state=1 AND id=" . $id;//某个单子下的已审核的状态
        $data = array();
        $data = $this->obj->show($condition);
        
        $ret = $data;
        return $ret;
    }
    
    public function fill_message()
    {
    	if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	$data = array(
    	    'user_id' => $this->user['user_id'],
    	    'record_id' => intval($this->input['record_id']),
    		'remark' => trim($this->input['remark']),
    		'reason' => trim($this->input['reason']),
    		'state' => intval($this->input['state']),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);  
        $ret = $this->obj->fill_message($data); 
    	$this->addItem($ret);
        $this->output();
    }

    
    
    public function checkState()
    {
    	$record_id = trim($this->input['id']) ? trim($this->input['id']) :'';
    	if(!$record_id)
    	{
	    	$this->errorOutput(NO_ID);
    	}
    	$ret = $this->obj->checkState($record_id);
    	if(isset($ret['error']) && $ret['error'])
    	{
	    	$this->errorOutput($ret['error']);
    	}
    	$this->addItem($ret);
	    $this->output();
    }
    
    public function delete()
    {
        $id = trim($this->input['id']);
        if (!$id) {
            $this->errorOutput(NO_ID);
        } 
        $info = $this->obj->delete($id);
        if(!$info['error'])
        {
	    	//$this->retotal($info['id']);
	        $this->addItem($info);
	        $this->output(); 
        }
        else
        {
	        $this->errorOutput($info['error']);
        }
    }
     public function cancel_records()
    {
        $id = trim($this->input['id']);  
        if (!$id) {
            $this->errorOutput(NO_ID);
        } 
        $info = $this->obj->cancel_records($id);
	    $this->addItem($info);
	    $this->output(); 
       
    }

    
    public function unknow(){}
}

$out = new qingjia_record_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
