<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 身份认证接口
 **************************************************************************/
define('MOD_UNIQUEID','business_auth');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/business_auth_mode.php');
require_once(CUR_CONF_PATH . 'lib/UpYunOp.class.php');

class business_auth extends outerUpdateBase
{
    private $mode;
    private $_upYunOp;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new business_auth_mode();
        $this->_upYunOp = new UpYunOp();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //生成一条申请
    public function create()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //首先查看有没有已经申请了
        $business_auth = $this->mode->detail(''," AND user_id = '" .$user_id. "' ");
        if($business_auth)
        {
            $this->errorOutput(IDENTITY_AUTH_HAS_EXISTS);
        }

        $type = intval($this->input['type']);
        if(!$type)
	    {
	        $this->errorOutput(NO_TYPE);
	    }
        
	    $name           = $this->input['name'];//公司名或者个人名称
	    $telephone      = $this->input['telephone'];
	    $address        = $this->input['address'];//详细地址
	    $link_man       = $this->input['link_man'];//联系人
	    $identity_num   = $this->input['identity_num'];//证件号
	    $bank_id        = intval($this->input['bank_id']);//银行id
	    $pay_type       = intval($this->input['pay_type']);//支付类型
	    
	    if(!$pay_type)
	    {
	        $pay_type = 2;//默认支付宝手动
	    }
	     
	    $data = array(
	        'auth_identifier'=> Common::getSerialNumber(),//生成一个授权编号
	        'type'           => $type,
	        'name'           => $name,
	        'address'        => $address,
	        'link_man'       => $link_man,
    	    'telephone'      => $telephone,
    	    'identity_num'   => $identity_num,
	        'auth_duration'  => BUSINESS_DUTATION,
    	    'user_name'      => $this->user['user_name'],
    	    'user_id'        => $this->user['user_id'],
	        'create_time'	 => TIMENOW,
	        'update_time'	 => TIMENOW,
	    );
	    
	    $ret = $this->mode->create($data);
	    if($ret)
	    {
	        //申请生成之后
	        if($pay_type)
	        {
	            $payLog = array(
	                'type'          => $pay_type,
	                'pay_reason'    => 1,//支付事由（首次申请）
	                'bank_id'	    => $bank_id,
	                'order_num'     => Common::getOrderNumberByUserID($user_id),
	                'money'		    => PAY_MONEY,
	                'user_id'	    => $this->user['user_id'],
	                'create_time'   => TIMENOW,//日志
	                'auth_duration' => BUSINESS_DUTATION,
	            );
	            $isPayLog = $this->mode->createTradeLog($payLog);
	            if(!$isPayLog)
	            {
	                $this->errorOutput(PAY_LOG_ERROR);
	            }
	        }
	        
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //更新一条申请
    public function update()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //首先查看有没有已经申请了
        $business_auth = $this->mode->detail(''," AND user_id = '" .$user_id. "' ");
        if(!$business_auth)
        {
            $this->errorOutput(YOU_HAVE_NOT_APPLY);
        }
        
        //只有被打回的申请才能重新提交
        if(intval($business_auth['status']) != 3)
        {
            $this->errorOutput(YOU_CAN_NOT_RE_SUBMIT_APPLY);
        }

        $type = intval($this->input['type']);
        if(!$type)
	    {
	        $this->errorOutput(NO_TYPE);
	    }
        
	    $name           = $this->input['name'];//公司名或者个人名称
	    $telephone      = $this->input['telephone'];
	    $address        = $this->input['address'];//详细地址
	    $link_man       = $this->input['link_man'];//联系人
	    $identity_num   = $this->input['identity_num'];//证件号
	    $pay_type       = intval($this->input['pay_type']);//支付类型
	    $bank_id        = intval($this->input['bank_id']);//银行id
	    
        if(!$pay_type)
	    {
	        $pay_type = 2;//默认支付宝手动
	    }
    	    
	    $data = array(
	        'type'           => $type,
	        'name'           => $name,
	        'address'        => $address,
	        'link_man'       => $link_man,
    	    'telephone'      => $telephone,
    	    'identity_num'   => $identity_num,
	        'auth_duration'  => BUSINESS_DUTATION,
	        'update_time'	 => TIMENOW,
	        'is_confirm_pay' => 0,
	        'status'	     => 1,//重新提交的申请重置为待审核（受理中）
	    );
	    
	    $ret = $this->mode->update($business_auth['id'],$data);
	    if($ret)
	    {
	        //申请生成之后，检测该用户针对首次申请的付款有没有已经付款
	        if($pay_type && !$ret['pay_status'])
	        {
	            $payLog = array(
	                'type'          => $pay_type,    
	                'pay_reason'    => 1,//支付事由（首次申请）
	                'bank_id'	    => $bank_id,
	                'order_num'     => Common::getOrderNumberByUserID($user_id),
	                'money'		    => PAY_MONEY,
	                'user_id'	    => $this->user['user_id'],
	                'create_time'   => TIMENOW,//日志
	                'auth_duration' => BUSINESS_DUTATION,
	            );
	            $isPayLog = $this->mode->createTradeLog($payLog);
	            if(!$isPayLog)
	            {
	                $this->errorOutput(PAY_LOG_ERROR);
	            }
	        }
	        
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    public function detail()
    {
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $business_auth = $this->mode->detail(''," AND user_id = '" .$this->user['user_id']. "' ");
        if($business_auth)
        {
            $this->addItem($business_auth);
        }
        else 
        {
            $this->addItem(array('nodata' => 1));
        }
        $this->output();
    }
    
    //用户自己确认付款
    public function confirmPay()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //首先查看有没有已经申请了
        $business_auth = $this->mode->detail(''," AND user_id = '" .$user_id. "' ");
        if(!$business_auth)
        {
            $this->errorOutput(YOU_HAVE_NOT_APPLY);
        }
        
        $ret = $this->mode->update($business_auth['id'],array('is_confirm_pay' => 1));
	    if($ret)
	    {   
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //某人的发票申请的详情
    public function detailInvoiceApply()
    {
        if(!$this->user['user_id'])
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $invoice_apply = $this->mode->detailInvoiceApply(''," AND user_id = '" .$this->user['user_id']. "' ");
        if($invoice_apply)
        {
            $this->addItem($invoice_apply);
        }
        else 
        {
            $this->addItem(array('nodata' => 1));
        }
        $this->output();
    }
    
    //申请获取发票
    public function askforInvoice()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        //先查看票据的状态，查看是不是已经存在
        $invoiceInfo = $this->mode->detailInvoiceApply(''," AND user_id = '" .$user_id. "' ");
        if($invoiceInfo)
        {
            $this->errorOutput(IDENTITY_AUTH_HAS_EXISTS);
        }
        
        $invoice_type      = intval($this->input['invoice_type']);//票据类型
	    $money             = intval($this->input['money']);//金额
	    $invoice_title     = $this->input['invoice_title'];//抬头
	    $taxpayer_id       = $this->input['taxpayer_id'];//纳税人识别号
	    $deposit_bank      = $this->input['deposit_bank'];//开户银行
	    $bank_account      = $this->input['bank_account'];//开户账号
	    $register_address  = $this->input['register_address'];//注册地址
	    $register_phone    = $this->input['register_phone'];//公司注册电话
	    $recipient         = $this->input['recipient'];//收件人
	    $recipient_address = $this->input['recipient_address'];//收件人地址
	    $recipient_phone   = $this->input['recipient_phone'];//收件人电话
	    
	    $data = array(
	        'user_id'			=> $this->user['user_id'],
	        'invoice_type'      => $invoice_type,
	        'money'             => $money,
	        'invoice_title'     => $invoice_title,
	        'taxpayer_id'       => $taxpayer_id,
	        'deposit_bank'      => $deposit_bank,
	        'bank_account'      => $bank_account,
	        'register_address'  => $register_address,
	        'register_phone'    => $register_phone,
	        'recipient'         => $recipient,
	        'recipient_address' => $recipient_address,
	        'recipient_phone'   => $recipient_phone,
	        'create_time'		=> TIMENOW,
	        'update_time'		=> TIMENOW,
	    );

	    //一般纳税人证明
	    if(isset($_FILES['taxpayer_cert']) && !$_FILES['taxpayer_cert']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['taxpayer_cert'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['taxpayer_cert'] = addslashes(serialize($img_info));
            }
	    }
	    
	    //税务登记证  tax_register_cert
        if(isset($_FILES['tax_register_cert']) && !$_FILES['tax_register_cert']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['tax_register_cert'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['tax_register_cert'] = addslashes(serialize($img_info));
            }
	    }
	    
	    $ret = $this->mode->createInvoiceApply($data);
        if($ret)
	    {
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //更新一条发票申请
    public function updateInvoice()
    {
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput(NOID);
        }
        
        //先查看票据的状态，只有被打回的情况下才可以更新
        $invoiceInfo = $this->mode->detailInvoiceApply($id);
        if(!$invoiceInfo)
        {
            $this->errorOutput(YOU_HAVE_NOT_APPLY);
        }
        
        if(intval($invoiceInfo['status']) != 3)
        {
            $this->errorOutput(YOU_CAN_NOT_RE_SUBMIT_APPLY);
        }
        
        $invoice_type      = intval($this->input['invoice_type']);//票据类型
	    $money             = intval($this->input['money']);//金额
	    $invoice_title     = $this->input['invoice_title'];//抬头
	    $taxpayer_id       = $this->input['taxpayer_id'];//纳税人识别号
	    $deposit_bank      = $this->input['deposit_bank'];//开户银行
	    $bank_account      = $this->input['bank_account'];//开户账号
	    $register_address  = $this->input['register_address'];//注册地址
	    $register_phone    = $this->input['register_phone'];//公司注册电话
	    $recipient         = $this->input['recipient'];//收件人
	    $recipient_address = $this->input['recipient_address'];//收件人地址
	    $recipient_phone   = $this->input['recipient_phone'];//收件人电话
	    
	    $data = array(
	        'invoice_type'      => $invoice_type,
	        'money'             => $money,
	        'invoice_title'     => $invoice_title,
	        'taxpayer_id'       => $taxpayer_id,
	        'deposit_bank'      => $deposit_bank,
	        'bank_account'      => $bank_account,
	        'register_address'  => $register_address,
	        'register_phone'    => $register_phone,
	        'recipient'         => $recipient,
	        'recipient_address' => $recipient_address,
	        'recipient_phone'   => $recipient_phone,
	        'update_time'		=> TIMENOW,
	        'status'			=> 1,//重新提交的发票申请,都变成受理中
	    );

	    //一般纳税人证明
	    if(isset($_FILES['taxpayer_cert']) && !$_FILES['taxpayer_cert']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['taxpayer_cert'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['taxpayer_cert'] = addslashes(serialize($img_info));
            }
	    }
	    
	    //税务登记证  tax_register_cert
        if(isset($_FILES['tax_register_cert']) && !$_FILES['tax_register_cert']['error'])
	    {
	        $img = $this->_upYunOp->uploadToBucket($_FILES['tax_register_cert'],'',$this->user['user_id']);
            if($img)
            {
                $img_info = array(
					'host' 		=> $img['host'],
					'dir' 		=> $img['dir'],
					'filepath' 	=> $img['filepath'],
					'filename' 	=> $img['filename'],	
					'imgwidth'	=> $img['imgwidth'],
					'imgheight'	=> $img['imgheight'],
                );
                $data['tax_register_cert'] = addslashes(serialize($img_info));
            }
	    }
	    
	    $ret = $this->mode->updateInvoiceApply($id,$data);
        if($ret)
	    {
	        $this->addItem(array('return' => 1));
	        $this->output();
	    }
	    else 
	    {
	        $this->errorOutput(FAILED);
	    }
    }
    
    //获取某人的付款记录
    public function getPayLogs()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $ret = $this->mode->getPayLogByUserId($user_id," AND status = 1 ");
        if($ret)
        {
            $this->addItem($ret);
        }
        else 
        {
            $this->addItem(array('nodata' => 1));
        }
        $this->output();
    }
    
    //获取某人最新的付款记录
    public function getNewPayLog()
    {
        $user_id = $this->user['user_id'];
        if(!$user_id)
        {
            $this->errorOutput(NO_LOGIN);
        }
        
        $ret = $this->mode->detailPayLog(''," AND user_id = '" .$user_id. "' ORDER BY create_time DESC ");
        if($ret)
        {
            $this->addItem($ret);
        }
        else 
        {
            $this->errorOutput(NO_DATA);
        }
        $this->output();
    }
    
    public function delete(){}
    
    public function unkow()
    {
        $this->errorOutput(UNKNOW);
    }
}

$out = new business_auth();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'unkow';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();