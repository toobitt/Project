<?php
require './global.php';
define ('MOD_UNIQUEID', 'bill');
class billUpdateApi extends adminUpdateBase
{
    public function __construct() {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/bill.class.php');
        $this->obj = new bill();
    }
    
    public function __destruct() {
        parent::__destruct();
    }
    
    public function create()
    {
    	if(!isset($this->input['user_id']))
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	if(isset($this->input['project_id']) && intval($this->input['project_id'])<0)
    	{
	    	$this->errorOutput(NO_PROJECT_ID);
    	}
    	if(isset($this->input['auditor_id']) && intval($this->input['auditor_id'])<0)
    	{
	    	$this->errorOutput(NO_AUDITOR_ID);
    	}
    	if(isset($this->input['record_id']) && !trim($this->input['record_id']))
    	{
	    	$this->errorOutput(NO_RECORDID);
    	}
    	$data = array(
    		'title' => $this->input['title'] ? trim($this->input['title']) : '' ,
    		'user_id' => intval($this->input['user_id']),
    		'project_id' => intval($this->input['project_id']),
    		'cause' => trim($this->input['cause']),
    		'business_time' => strtotime(trim($this->input['business_time'])),
    		'back_time' => strtotime(trim($this->input['back_time'])),
    		'baoxiao_time' => strtotime(trim($this->input['baoxiao_time'])),
    		'advice' => intval($this->input['advice']),
    		'cost' => intval($this->input['cost']),
    		'auditor_id' => intval($this->input['auditor_id'])>0?intval($this->input['auditor_id']):0,    		
    		'order_id' => 0,
    		'state' => intval($this->input['state']),
    		'create_time' => TIMENOW,
    		'update_time' => TIMENOW,
    		'ip' => hg_getip(),
    	);
    	 
    	//print_r($data);die;
    	if($data['state'] == 1 && $data['auditor_id'])
    	{
	    	if(!$data['business_time'])
	    	{
		    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BUSINESS_TIME);
	    	}
	    	if(!$data['back_time'])
	    	{
		    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BACK_TIME);
	    	}
	    	if(!$data['baoxiao_time'])
	    	{
		    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BAOXIAO_TIME);
	    	}
	    	if(!$data['cost'])
	    	{
		    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_COST);
	    	}
	    	$data['locked'] = 1;
	    }
    	$ret = $this->obj->create($data);    	
    	if($ret['id'] && trim($this->input['record_id']))
    	{
    		include_once(CUR_CONF_PATH . 'lib/bill_record.class.php');
	    	$this->bill_record = new billRecord();
	    	$this->bill_record->update_bill($ret['id'],trim($this->input['record_id']));	    	
    	}
    	if($data['state'] == 1 && $data['auditor_id'])
    	{
	    	$this->add_record($data['auditor_id'],$ret['id']);
	    	if($data['locked'])//报销单，审核通过，并且有审核人之后，提交，锁住当前审核人，不准进行任何操作
	    	{
		    	include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
		    	$this->auditor = new auditor();
		    	$this->auditor->locked_auditor($ret['id']);
	    	}
    	}
    	$this->addItem($ret);
        $this->output();
    }
    
    public function update()
    {
		if(!$this->user['user_id'])
    	{
	    	$this->errorOutput(NO_LOGIN);
    	}
    	if(!intval($this->input['pay_id']))
    	{
	    	if(empty($this->input['id']))
	    	{
		    	$this->errorOutput(NO_ID);
	    	}
	    	if(intval($this->input['project_id'])<0)
	    	{
		    	$this->errorOutput(NO_PROJECT_ID);
	    	}
	    	if(intval($this->input['auditor_id'])<0)
	    	{
		    	$this->errorOutput(NO_AUDITOR_ID);
	    	}
	    	$id = intval($this->input['id']);
	    	$checkbool = $this->obj->checkLocked($id);
	    	if($checkbool)
	    	{
		    	$this->errorOutput(THIS_IS_LOCKED);
	    	}    
	    	$data = array(
	    		'title' => $this->input['title'] ? trim($this->input['title']) : '',
	    		'user_id' => $this->user['user_id'],
	    		'project_id' => intval($this->input['project_id']),
	    		'cause' => trim($this->input['cause']),
	    		'business_time' => strtotime(trim($this->input['business_time'])),
	    		'back_time' => strtotime(trim($this->input['back_time'])),
	    		'baoxiao_time' => strtotime(trim($this->input['baoxiao_time'])),
	    		'advice' => intval($this->input['advice']),
	    		'cost' => intval($this->input['cost']),
	    		'auditor_id' => intval($this->input['auditor_id'])>0?intval($this->input['auditor_id']):0,
	    		//'order_id' => 0,
	    		'state' => intval($this->input['state']),
	    		'update_time' => TIMENOW,
	    	);
			//print_r($data);die;
	    	if($data['state'] == 1 && $data['auditor_id'])
	    	{	
		    	if(!$data['business_time'])
		    	{
			    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BUSINESS_TIME);
		    	}
		    	if(!$data['back_time'])
		    	{
			    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BACK_TIME);
		    	}
		    	if(!$data['baoxiao_time'])
		    	{
			    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_BAOXIAO_TIME);
		    	}
		    	if(!$data['cost'])
		    	{
			    	$this->errorOutput(BILL_WILL_BE_LOCKED . ' , ' . NO_COST);
		    	}
		    	$data['locked'] = 1;
		    	
		    }
	    	$ret = $this->obj->update($data,$id);    	
	    	if($ret['id'] && trim($this->input['record_id']))
	    	{
	    		include_once(CUR_CONF_PATH . 'lib/bill_record.class.php');
		    	$this->bill_record = new bill_record();
		    	$this->bill_record->update_bill($ret['id'],trim($this->input['record_id']));	    	
	    	}
	    	if($data['state'] == 1 && $data['auditor_id'])
	    	{
		    	$this->update_record($data['auditor_id'],$id);
		    	if($data['locked'])//报销单，审核通过，并且有审核人之后，提交，锁住当前审核人，不准进行任何操作
		    	{
			    	include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
			    	$this->auditor = new auditor();
			    	$this->auditor->locked_auditor($id);
		    	}
	    	}
	      }else{
	    	$id = intval($this->input['pay_id']);
		    $data = array(
		    	'pay' => 1,
		    	'update_time' => TIMENOW
		    );
		    $ret = $this->obj->update($data,$id);
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
    	$checkbool = $this->obj->checkLocked($ids);
    	if($checkbool)
    	{
	    	$this->errorOutput(THIS_IS_LOCKED);
    	}
    	$check_content = $this->obj->checkContent($ids);
    	//var_dump($check_content);exit;
    	if($check_content)
    	{
	    	$this->errorOutput($check_content);
    	}
	    $state = intval($this->input['audit']) ? 1 : 2;
        $info = $this->obj->audit($ids,$state);
        $this->addItem($info);
        $this->output(); 
    }
    
    private function add_record($aid,$bill_id)
    {
    	if(!$aid)
    	{
	    	return false;
    	}
        include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
        $this->auditor = new auditor();
    	$auditor = $this->auditor->detail($aid);
    	$data = array();
    	$auditor_record = $auditor['info'];
    	
    	if($this->input['cost'] >= $this->input['pay_limit']) //如果花费大于限额，则需要沈总审批。
		{
			$auditor_record[3] = array('user_id' => intval($this->input['boss_id']),'user_name' =>$this->input['boss_name'],'audit_level' => 4);
		}

    	foreach($auditor_record as $k => $v)
    	{
	    	$auditor_record[$k]['auditor_id'] = intval($this->input['user_id']);
	    	$auditor_record[$k]['bill_id'] = $bill_id;
	    	$auditor_record[$k]['create_time'] = TIMENOW;
	    	$auditor_record[$k]['update_time'] = TIMENOW;
	    	$auditor_record[$k]['ip'] = hg_getip();
	    	
	    	$this->auditor->add_record($auditor_record[$k]);
    	}
    }
    
    private function update_record($aid,$bill_id)
    {
    	if(!$aid)
    	{
	    	return false;
    	}
        include_once(CUR_CONF_PATH . 'lib/auditor.class.php');
        $this->auditor = new auditor();
    	$auditor = $this->auditor->detail($aid);
    	$data = array();
    	$auditor_record = $auditor['info'];
    	
    	$this->auditor->delete_record($bill_id);//先删除，再增加
    	foreach($auditor_record as $k => $v)
    	{
	    	$auditor_record[$k]['auditor_id'] = $this->user['user_id'];
	    	$auditor_record[$k]['bill_id'] = $bill_id;
	    	$this->auditor->add_record($auditor_record[$k]);
    	}
    }
    
    public function delete()
    {
        $id = trim($this->input['id']);
        if (!$id) {
            $this->errorOutput(NO_ID);
        }
    	$checkbool = $this->obj->checkLocked($id);
    	if($checkbool)
    	{
	    	$this->errorOutput(THIS_IS_LOCKED);
    	}
        $data = $this->obj->delete($id);
        $this->addItem($data);
        $this->output();
    }
    
    public function sort(){}
    public function publish(){}
    public function unknow(){
	    $this->errorOutput('unknow');
    }
}

$out = new billUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
