<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: payments.class.php 16201 2012-12-28 06:01:09Z jeffrey $
***************************************************************************/

class paymentsClass
{
	public function __construct()
	{
		global $gGlobalConfig;
		$this->curl = new curl($gGlobalConfig['App_payments']['host'], $gGlobalConfig['App_payments']['dir']);
	}

	public function __destruct()
	{
		unset($this->curl);
	}
	
	/**
	 * * 提交支付
	 * * @param payid  支付配置id
	 * * * @param paycode  签约类型code
	 * * @param trade_no  商户订单号
	 * * @param total_fee  订单金额
	 * * @param subject  订单名称
	 * * @param uid      用户id
	 */
	public function payments($payid,$paycode,$trade_no,$total_fee,$subject,$uid)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'payments');
		$this->curl->addRequestData('payid', $payid);
		$this->curl->addRequestData('paycode', $paycode);
		$this->curl->addRequestData('trade_no', $trade_no);
		$this->curl->addRequestData('total_fee', $total_fee);
		$this->curl->addRequestData('subject', $subject);
		$this->curl->addRequestData('uid', $uid);
		$result = $this->curl->request('payments.php');
		return $result;
	}
	
	/**
	 * * 查询订单状态
	 * * @param trade_no  商户订单号
	 */
	public function paycontent($trade_no)
	{
		if(!$this->curl)
		{
			return array();
		}
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail_data');
		$this->curl->addRequestData('trade_no', $trade_no);
		$result = $this->curl->request('payments.php');
		return $result;
	}
	
}
?>