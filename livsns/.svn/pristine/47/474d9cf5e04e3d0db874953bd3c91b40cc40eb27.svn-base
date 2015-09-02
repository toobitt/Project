<?php 


class uset 
{
	private $curl;
	
	function __construct()
	{
		global $gUserApiConfig;
		
		require_once(ROOT_PATH . "lib/class/curl.class.php");
		$this->curl = new curl($gUserApiConfig['host'],$gUserApiConfig['apidir']);
	}
	
	function __destruct()
	{	
	}
	
	/**
	 * 
	 * 添加用户设置
	 * @param string $uset_name
	 * @param string $uset_identi
	 * @param int    $uset_status
	 */
	public function add_user_set($usetArr)
	{
		$this->curl->setRequestType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('usetArr',$usetArr);
		$this->curl->addRequestData('a', 'add_user_set');
		return $this->curl->request('user_set.php');
	}
	
	/**
	 * 获取所有用户设置
	 */
	public function get_user_set()
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_user_set');
		return $this->curl->request('user_set.php');
	}
	
	/**
	 * 删除用户设置
	 */
	public function del_user_set($id)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','del_user_set');
		$this->curl->addRequestData('id',$id);
		return  $this->curl->request('user_set.php');
	}
	
	/**
	 * 批量更新用户设置
	 */
	public function update_user_set($updateArr)
	{
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','update_user_set');//请求应用程序中的方法名
		$this->curl->addRequestData('updateArr',$updateArr);
		return  $this->curl->request('user_set.php');
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param char $parameter 序列化后的字符串,单个字符直接传|多个要请写成数组的形式
	 */
	public function get_desig_uset($parameter)
	{
		$serialize = serialize($parameter);
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_desig_uset');
		$this->curl->addRequestData('serialize',$serialize);
		$rt = $this->curl->request('user_set.php');
		return $rt[0];
	}
	
	public function get_uset_array($parameter)
	{
		$serialize = serialize($parameter);
		
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','get_uset_array');
		$this->curl->addRequestData('serialize',$serialize);
		$rt = $this->curl->request('user_set.php');
		return $rt[0];
	}
}
?>