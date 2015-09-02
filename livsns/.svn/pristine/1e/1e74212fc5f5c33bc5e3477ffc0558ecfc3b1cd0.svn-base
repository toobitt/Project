<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - dxtan
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014年12月11日
 * @encoding    UTF-8
 * @description rongcloud_mode.php
 **************************************************************************/
class rongcloud_mode extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->curl = new CurlApi();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 申请应用密钥
	 *
	 *2014年12月11日
	 *return_type
	 */
	public function apply_signature($data = array())
	{
	    //数据请求
	    $this->curl->setRequestUrl(RC_APPLY_URL);
	    $result = $this->curl->post($data);
	
	    //数据整理
	    if ($result)
	    {
	        if (isset($result))
	        {
	            $result = $result;
	        }
	        if (empty($result))
	        {
	            $result = array();
	        }
	    }
	    return $result;
	}
	
	
	public function show(){}
	public function create(){}
	public function update(){}
	public function detail(){}
	public function count(){}
	public function delete(){}
	public function audit(){}
}
?>