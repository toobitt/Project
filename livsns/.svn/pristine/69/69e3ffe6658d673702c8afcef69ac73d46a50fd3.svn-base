<?php
/*******************************************************************
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','identifierUserSystem');//模块标识
require('./global.php');
class identifierUserSystemUpdateAdmin extends adminUpdateBase
{
	private $identifierUserSystem;
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->identifierUserSystem = new identifierUserSystem();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$data = array();
		try {
		   $this->identifierUserSystem->setIdentifier()->setDatas('identifier');
		   $this->identifierUserSystem->setUserId($this->user['user_id'])->setDatas('user_id');
		   $this->identifierUserSystem->setUserName($this->user['user_name'])->setDatas('user_name');
		   $this->identifierUserSystem->setCreateTime()->setDatas('create_time');
		   $this->filter_data();
		   $data =  $this->identifierUserSystem->create();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->setAddItemValueType();
		$this->addItem($data);
		$this->output();
	}
	
	private function filter_data()
	{
		$this->identifierUserSystem->setIusname($this->identifierUserSystem->checkIusname($this->input['iusname']))->setDatas('iusname');
		$this->identifierUserSystem->setBrief($this->input['brief'])->setDatas('brief');
		$this->identifierUserSystem->setOpened($this->input['opened'])->setDatas('opened');
		$this->identifierUserSystem->setUpdateTime()->setDatas('update_time');
	}
	
	public function update()
	{
		$data = array();
		try {
		   $this->identifierUserSystem->setIusid($this->identifierUserSystem->checkIusId($this->input['id']))->verifyIusId()->modifyForbidIdentifierForZero()->setParams('iusid');
		   $this->filter_data();
		   $data =  $this->identifierUserSystem->update();
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->setAddItemValueType();
		$this->addItem($data);
		$this->output();
	}
	
	
	public function audit()
	{
		
	}
	
	//字段配置开关
	public function display()
	{
		$opened = intval($this->input['is_on']) ? 1 : 0;
		$data = array();
		try{
			$this->identifierUserSystem->setIusid($this->identifierUserSystem->checkIusId($this->input['id']))->verifyIusId()->modifyForbidIdentifierForZero()->setParams('iusid');
			$data = $this->identifierUserSystem->display($opened);
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function sort()
	{
		
	}
	public function publish()
	{
		
	}
	public function delete()
	{
		try {
		   $this->identifierUserSystem->setIusidS($this->identifierUserSystem->checkIusIdS($this->input['id']))->verifyIusIdS()->modifyForbidIdentifierForZero()->setParams('iusid',null,array('iusid'=>'iusidS'));
		   if(!$this->identifierUserSystem->delete())
		   {
		   	  throw new Exception(DELETE_FAILED, 200);
		   }
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$redata = array(
			'status'=> 1,
			'copywriting' => '删除成功',
			) ;
		$this->addItem($redata);
		$this->output();
	}

	/**
	 * 空方法,如果用户调取的方法不存在.则执行
	 */
	public function unknow()
	{
		$this->errorOutput("此方法不存在");
	}


}

$out = new identifierUserSystemUpdateAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>