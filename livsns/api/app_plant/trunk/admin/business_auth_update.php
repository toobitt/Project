<?php
define('MOD_UNIQUEID','business_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/business_auth_mode.php');
require_once(CUR_CONF_PATH . 'lib/company.class.php');

class business_auth_update extends adminUpdateBase
{
	private $mode;
	private $company;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new business_auth_mode();
		$this->company = new CompanyApi();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addLogs('删除授权申请',$ret,'','删除授权申请' . $this->input['id']);
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
	    $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }
        
        //准备好更新的数据
        $data = array('status' => $status);
        if($status == 2)
        {
            $data['open_time'] = TIMENOW;//记录开通时间
            $data['auth_duration'] = BUSINESS_DUTATION;//授权期限
        }
        
        //如果被打回接收打回的原因
        if($status == 3)
        {
            $data['reason'] = $this->input['reason'];
        }

        $ret = $this->mode->update($id,$data);
        if($ret)
        {
            //已开通
            if($status == 2)
            {
                $_ret = $this->company->setUserInfo(array(
                       'a'                => 'setUserBusiness',
                       'user_id'		  => $ret['user_id'],
                       'is_business'      => 1,//设置为商业用户
                ));

                //更新付款记录中的开通时间
                //首先找出最后一次付款的成功的记录
                $cond = " AND user_id = '" .$ret['user_id']. "' AND status = 1 ORDER BY create_time DESC ";
                $payokLog = $this->mode->detailPayLog('',$cond);
                if($payokLog)
                {
                    $this->mode->updateTradeLog($payokLog['id'],array(
                           'open_time' => $data['open_time'],
                    ));
                }
            }
            else if($status == 3)//被打回
            {
                $_ret = $this->company->setUserInfo(array(
                       'a'				  => 'setUserBusiness',
                       'user_id'		  => $ret['user_id'],
                       'is_business'      => 0,//设置为非商业用户
                ));
            }
            
            if($_ret && is_array($_ret) && !isset($_ret['ErrorCode']))
            {
                $this->addItem(array('status' => $status,'status_text' => $this->settings['business_auth_status'][$status]));
                $this->output();
            }
            else 
            {
                $this->errorOutput(FAILED);
            }
        }
	}
	
	//确认付款
	public function confirmPay()
	{
	    $id = $this->input['id'];
	    if(!$id)
	    {
	        $this->errorOutput(NOID);
	    }
	    
	    //修改付款订单状态
	    $ret = $this->mode->updateTradeLog($id,array(
	                'status'   => 1,
	                'pay_time' => TIMENOW,
	    ));
	    
	    //修改主表的付款状态
	    if($ret)
	    {
	        $this->mode->updateApply(" AND user_id = '" .$ret['user_id']. "' ",array(
	                    'pay_status' => 1,
	        ));
	    }
	    $this->addItem(array('status' => 1,'status_text' => '已付款'));
	    $this->output();
	}
	
	//审核票据
	public function auditInvoice()
	{
	    $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }
        
        //准备好更新的数据
        $data = array('status' => $status);
        
	    //如果被打回接收打回的原因
        if($status == 3)
        {
            $data['reason'] = $this->input['reason'];
        }

        $ret = $this->mode->updateInvoiceApply($id,$data);
        if($ret)
        {
            $this->addItem(array('status' => $status,'status_text' => $this->settings['invoice_status'][$status]));
            $this->output();
        }
        else 
        {
            $this->errorOutput(FAILED);
        }
	}
	
	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new business_auth_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();