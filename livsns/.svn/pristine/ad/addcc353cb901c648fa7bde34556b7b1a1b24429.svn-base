<?php
/*******************************************************************
 * filename :member_myData_update.php
 * 我的数据储存数据字段更新接口
 * Created  :2014年5月29日,Writen by ayou
 ******************************************************************/
define('MOD_UNIQUEID','member_myField');//模块标识
require('./global.php');
class member_myFieldUpdateAdmin extends adminUpdateBase
{
	private $memberMyField = null;
	private $memberMySet = null;
	private $memberMyFieldBind = null;
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->memberMyField = new memberMyField();
		$this->memberMySet = new memberMySet();
		$this->memberMyFieldBind = new memberMyFieldBind();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function create()
	{
		$bindms = array();
		$data = array();
		try {
		   $this->memberMyField->setFieldMark($this->input['fieldmark']);
		   $this->memberMyField->setUserId($this->user['user_id']);
		   $this->memberMyField->setUserName($this->user['user_name']);
		   $this->memberMyField->setCreateTime();
		   $this->filter_data();
		   $bindms = $this->setBindMs();
		   $insertField = array(
		   'fieldname',
		   'fieldmark',
		   'brief',
		   'isunique',
		   'addstatus',
		   'isrequired',
		   'issearch',
		   'defaultsvalue',
		   'user_id',
		   'user_name',
		   'create_time',
		   'update_time',
		   );
		   $this->memberMyField->setDatas($insertField);
		   $data =  $this->memberMyField->create();
		   $data['mfid'] && $this->memberMyFieldBind->bindData($data['mfid'], array(), $bindms);
		}
		catch (Exception $e)
		{
			$data['mfid'] && $this->memberMyField->setParams('mfid',$data['mfid']) && $this->memberMyField->delete();
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->setAddItemValueType();
		$this->addItem($data);
		$this->output();
	}
	
	private function filter_data()
	{
		$this->memberMyField->setFieldName($this->input['fieldname']);
		$this->memberMyField->setBrief($this->input['brief']);
		$this->memberMyField->setIsRequired($this->input['isrequired']);
		$this->memberMyField->setUpdateTime();
		$this->memberMyField->setIsUnique($this->input['isunique']);
		$this->memberMyField->setAddStatus($this->input['addstatus']);
		$this->memberMyField->setDefaultsValue($this->input['defaultsvalue']);
		$this->memberMyField->setIsSearch($this->input['issearch']);
	}
	
	public function update()
	{
		$data = array();
		$bindms = array();
		try {
		   $this->memberMyField->setMfid($this->input['id']);
		   $this->memberMyField->setParams('mfid');
		   $this->memberMyField->dataExists();
		   $NmemberMyFieldBind = clone $this->memberMyFieldBind;//防止后续此对象类变量被串改
		   $mfBindInfo = $NmemberMyFieldBind->getBindInfoToMfId($this->memberMyField->getMfid());
		   $this->filter_data();
		   $bindms = $this->setBindMs();
		   $updateField = array(
		   'fieldname',
		   'brief',
		   'isunique',
		   'addstatus',
		   'isrequired',
		   'issearch',
		   'defaultsvalue',
		   'update_time',
		   );
		   $this->memberMyField->setDatas($updateField);
		   $data =  $this->memberMyField->update();
		   $data['mfid'] && $this->memberMyFieldBind->bindData($this->memberMyField->getMfid(),$mfBindInfo,$bindms);
		}
		catch (Exception $e)
		{
			$this->errorOutput($e->getMessage(),$e->getCode());
		}
		$this->setAddItemValueType();
		$this->addItem($data);
		$this->output();
	}
	
	private function setBindMs()
	{
		$bindms = array_filter((array)$this->input['bindms'],"clean_array_null");
		$bindms && $markArr = $this->memberMySet->getMySetIdToMarkBatch($bindms);
		if($bindms && (count($markArr) != count($bindms)))
		{
		    throw new Exception(BIND_MYSET_DATA_ERROR, 200);
		}
		return $bindms;
	}
	
	
	public function audit()
	{
		
	}
	
	//字段配置开关
	public function display()
	{
		$field = trim($this->input['field']);
		$opened = intval($this->input['is_on']) ? 1 : 0;
		$data = array();
		try{
			$this->memberMyField->setMfid(intval($this->input['id']));
			$this->memberMyField->setParams('mfid');
			$data = $this->memberMyField->display($opened,$field);
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
		   $this->memberMyField->setMfid(trim($this->input['id']));
		   $this->memberMyField->setParams('mfid');
		   if(!$this->memberMyField->delete())
		   {
		   	  throw new Exception(DELETE_FAILED, 200);
		   }
		   $this->memberMyFieldBind->setParams('mfid',$this->memberMyField->getMfid());
		   $this->memberMyFieldBind->deleteAll();
		   
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

$out = new member_myFieldUpdateAdmin();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>