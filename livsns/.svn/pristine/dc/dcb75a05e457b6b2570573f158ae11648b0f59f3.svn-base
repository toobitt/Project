<?php
require './global.php';
define ('MOD_UNIQUEID', 'bill_record');
class billRecordUpdateApi extends adminBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/bill_record.class.php');
        $this->obj = new billRecord();
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
    	/*
    	if(!$this->input['bill_id'])
    	{
	    	$this->errorOutput('NO BILL');
    	}
    	*/
    	if(intval($this->input['sort_id'])<0)
    	{
	    	$this->errorOutput(NO_SORT);
    	}
    	$data = array(
    		'user_id' => $this->user['user_id'],
    		'sort_id' => intval($this->input['sort_id']),
    		'bill_id' => intval($this->input['bill_id']),
    		'cost' => intval($this->input['cost']),
    		'cost_capital' => hg_cny(intval($this->input['cost'])),
    		'remark' => trim($this->input['remark']),
    		'is_ticket' => intval($this->input['is_ticket']),
    		'material_id' => 0,
    		'img' => '',
    		'cost_time' => strtotime(trim($this->input['cost_time'])),
    		'state' => intval($this->input['state']),
    		'order_id' => 0,
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	); 
    	if($data['bill_id'])
    	{
	    	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
	    	$this->bill = new bill();
	    	$checkbool = $this->bill->checkLocked($data['bill_id']);
	    	if($checkbool)
	    	{
		    	$this->errorOutput(THIS_IS_LOCKED);
	    	} 
    	}    	 
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
			$data['material_id'] = $material['id'];
    	}
    	if($data['state'])//审核通过新增
    	{
    		$this->reTotalSort($data['sort_id'],0);
    	}
    	$ret = $this->obj->create($data);    	
    	if(intval($this->input['bill_id']))	
    	{
    		$this->retotal(intval($this->input['bill_id']));	
    	}
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
    	/*
    	if(!$this->input['bill_id'])
    	{
	    	$this->errorOutput(NO_BILLID);
    	}
    	*/
    	if(intval($this->input['sort_id'])<0)
    	{
	    	$this->errorOutput(NO_SORT);
    	}
    	$id = intval($this->input['id']);
    	$data = array(
    		'sort_id' => intval($this->input['sort_id']),
    		'cost' => intval($this->input['cost']),
    		'cost_capital' => hg_cny(intval($this->input['cost'])),
    		'remark' => trim($this->input['remark']),
    		'is_ticket' => intval($this->input['is_ticket']),
       		'cost_time' => strtotime(trim($this->input['cost_time'])),
    		'state' => intval($this->input['state']),
    		'update_time' => TIMENOW,
    		
    	);
    	if($this->input['bill_id'])
    	{
	    	include_once(CUR_CONF_PATH . 'lib/bill.class.php');
	    	$this->bill = new bill();
	    	$checkbool = $this->bill->checkLocked($this->input['bill_id']);
	    	if($checkbool)
	    	{
		    	$this->errorOutput(THIS_IS_LOCKED);
	    	} 
    	}
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
			$data['material_id'] = $material['id'];
    	}
    	$old_info = $this->obj->detail($id);
    	if($old_info['state'])//原来是审核通过，不管是否换sort，旧的sort 先-1
    	{
    		$this->reTotalSort(0,$old_info['sort_id']);
    	}
    	$ret = $this->obj->update($data,$id);
    	if($ret['state'])//如果新的审核通过，不管是否换sort,当前的sort +1
    	{
    		$this->reTotalSort($data['sort_id'],0);
    	}
    	if(intval($this->input['bill_id']))	
    	{
    		$this->retotal(intval($this->input['bill_id']));	
    	}
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
    	$bill_id = $this->input['bill_id'] ? intval($this->input['bill_id']) : 0;
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
    
    private function retotal($bill_id)
    {
	    
	    $condition = " AND state=1 AND bill_id=" . $bill_id;//某个单子下的已审核的状态
        $data = array();
        $data = $this->obj->show($condition);
        $ret = array();
        if($data)
        {
        	$tmp = 0;
	        foreach($data as $key => $value)
	        {
	        	$tmp += $value['cost'];
	        }
	        if($tmp)
	        {
		        $ret = array(
		        	'total' => $tmp,
		        );
	        }
	        $this->obj->reaccess($bill_id,$ret['total']);
        }
        return $ret;
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
	    	$this->retotal($info['bill_id']);
	        $this->addItem($info);
	        $this->output(); 
        }
        else
        {
	        $this->errorOutput($info['error']);
        }
    }
    public function unknow(){}
}

$out = new billRecordUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
