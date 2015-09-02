<?php
/***************************************************************************

* $Id: member.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class memberMyFieldBind extends classCore
{
	private $membersql;
	private $mfid = 0;
	private $msid = 0;
	private $params = array();
	private $paramType = array();
	private $data = array();
	private $offset = 0;
	private $count = 0;
	private $field = '*';
	private $key = '';
	private $orderby = '';
	private $Stype = 1;
	private $SotherKey = '';

	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();

	}
	public function __destruct()
	{
		parent::__destruct();
	}	
	
	public function count($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		$this->membersql->limit($this->offset, $this->count);
		$this->membersql->orderby($this->orderby);
		return $this->membersql->count(array(), 'member_myFieldBind');
	}
	
	public function bindData($mfid,array $oldbind, array $newbind)
	{
		$create_bind = array();
		$delete_bind = array();
		if($oldbind&&$newbind)
		{
			$create_bind = array_diff($newbind,$oldbind);
			$delete_bind = array_diff($oldbind,$newbind);
		}
		elseif (!$oldbind && $newbind)
		{
			$create_bind = $newbind;
		}
		elseif($oldbind&&!$newbind)
		{
			$delete_bind = $oldbind;
		}
		$this->setMfId($mfid);
		if($create_bind && is_array($create_bind))
		{
			$this->setDatas('mfid');
			$unsetArr = $this->notBindMySet();
			foreach ($create_bind as $val)
			{
				if(in_array($val, $unsetArr))
				{
					throw new Exception(MYSET_EQUAL_FIELD_ERROR, 200);
				}
				$this->setMsId($val);
				$this->setDatas('msid');
				$this->createAll();
			}
		}
		if($delete_bind&&is_array($delete_bind))
		{
			$this->setParams('mfid');
			foreach ($delete_bind as $v)
			{
			  $this->setParams('msid',$v);
			  $this->deleteAll();
			}
		}
		return true;
	}
	
	public function notBindMySet()
	{
		$memberMyField = new memberMyField();
		$FieldBind = array();
		$mfidArr = array();
		$Fieldmark = '';
		$Fieldmark = $memberMyField->getMyFieldIdToMark($this->mfid);
		$Fieldmark && $mfidArr = $memberMyField->getMyFieldMarkToId($Fieldmark);
		$mfidArr && is_array($mfidArr) && $FieldBind = $this->getBindInfoToMfIdBatch($mfidArr);
		$unsetArr = array();
		foreach ($FieldBind as $k => $v)
		{
			if($this->mfid  != $k)
			{
				$unsetArr = array_merge($unsetArr, $v);
			}
			
		}
		return $unsetArr;
	}
	
	public function setParams($key,$val = null,$asname='',$diykey = null)
	{	
		!$diykey && $diykey = $key;		
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if(isset($val[$k]))
				{
					$this->setParams($v, $val[$k],$asname,$diykey[$k]);
				}
				else {
					$this->setParams($v,null,$asname,$diykey[$k]);
				}
			}
		}
		elseif($key&&isset($val)) {			
			$this->params[($asname?$asname.'.':'').$key] = $val;
		}
		elseif($key)
		{
			$this->$key&&$this->params[($asname?$asname.'.':'').$key] = $this->$diykey;
		}
		return $this->params[($asname?$asname.'.':'').$key];
	}
	
	public function unsetParams($key = '',$asname='')
	{
		if($key&&isset($this->params[($asname?$asname.'.':'').$key]))
		{
			unset($this->params[($asname?$asname.'.':'').$key]);
		}
		elseif(!$key)
		{
			$this->params = array();
		}
	}
	
	public function setParamType($key,$type,$val = 1,$asname='')
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if($type)
				{
					$this->setParamType($v,$type,$val,$asname);
				}
			}
		}
		elseif($key&&$type) {
			$this->paramType[($asname?$asname.'.':'').$key][$type] = $val;
		}
		return $this->paramType[($asname?$asname.'.':'').$key];
	}
	public function unsetParamType($key = '')
	{
		if($key&&isset($this->paramType[$key]))
		{
			unset($this->paramType[$key]);
		}
		elseif(!$key)
		{
			$this->paramType = array();
		}
	}
	public function setDatas($key,$val = null)
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if($val && isset($val[$k]))
				{
					$this->setDatas($v, $val[$k]);
				}
				else {
					$this->setDatas($v);
				}
			}
		}
		elseif($key&&isset($val)) {			
			$this->data[$key] = $val;
		}
		elseif($key)
		{
			isset($this->$key)&&$this->data[$key] = $this->$key;
		}
		return is_array($key)?$this->data:$this->data[$key];
	}
	
	public function unsetDatas($key = '')
	{
		if($key&&isset($this->data[$key]))
		{
			unset($this->data[$key]);
		}
		elseif(!$key)
		{
			$this->data = array();
		}
	}

	public function verify($param)
	{
		return $this->membersql->verify('member_myFieldBind',$param);
	}
	
	public function show($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		return $this->membersql->show(array(), 'member_myFieldBind', $this->offset, $this->count,$this->orderby,$this->field,$this->key,array(),$this->Stype,$this->SotherKey);
	}
	
	public function setMfId($id)
	{
		$this->mfid = $id;
		if(!$this->mfid)
		{
			throw new Exception(NO_DATA_ID, 200);
		}
	}
	
	public function setMsId($id)
	{
		$this->msid = $id;
		if(!$this->msid)
		{
			throw new Exception(NO_DATA_ID, 200);
		}
	}
	public function setAs($asname='')
	{
		$this->membersql->setAsTable($asname);
	}
	
	public function setFieldS($_field = '*')
	{
		$this->field = $_field;
		return $this;
	}
	public function setKeyS($_key = '')
	{
		$this->key = $_key;
		return $this;
	}
	public function setOrderbyS($_orderby = 'ORDER BY mdid DESC')
	{
		$this->orderby = $_orderby;
		return $this;
	}
	
	public function setStype($stype = 1)
	{
		$this->Stype = $stype;
	}
	
	public function setSotherKey($SotherKey = '')
	{
		$this->SotherKey = $SotherKey;
	}
	
	public function detail()
	{
		$this->field && $this->membersql->setSelectField($this->field);
		return $this->membersql->detail($this->params, 'member_myFieldBind',array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i'),'update_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
	}
	public function create()
	{
		$this->dataFormatError();
		return $this->membersql->create('member_myFieldBind', $this->data);
	}
	
	public function createAll()
	{
		$addret = $this->create();
		$addret && is_array($addret) &&$this->setMFcountAll(array($addret),'add');
		return $addret;
	}
	
	public function delete()
	{
		$delret = $this->show();		
		if($this->membersql->delete('member_myFieldBind', $this->params))
		{
			return $delret;
		}
		else if ( $delret )
		{
		   throw new Exception(BIND_MYSET_DATA_DEL_ERROR, 200);	
		}
	}
	public function deleteAll()
	{
		$delret = $this->delete();
		$delret && is_array($delret) &&$this->setMFcountAll($delret,'del');
		return $delret;
	}
	public function setMFcountAll(array $delret,$type)
	{	
		$memberMySet = new memberMySet();
		foreach ($delret as $v)
		{
		  $memberMySet->setMFcount($v['msid'],1,$type);
		}
		return true;
	}
	
	public function getBindMsid()
	{
		$this->setFieldS('msid');
		$this->setStype(6);
		$this->setKeyS('msid');
		$this->setSotherKey('id');
		$this->groupby('msid');
		return $this->show();
	}
	
	public function groupby($groupby = '')
	{
		return $this->membersql->groupby($groupby);
	}

	public function getBindInfoToMfIdBatch($mfid)
	{
		if(!is_array($mfid))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$this->setFieldS('mfid,msid');
		$this->setStype(3);
		$this->setKeyS('mfid');
		$this->setSotherKey('msid');
		return $this->show(array('mfid'=>$mfid));
	}
	
	public function getBindInfoToMfId($mfid)
	{
		if(!is_numeric($mfid))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$bindInfo = $this->getBindInfoToMfIdBatch(array($mfid));
		return $bindInfo[$mfid]?$bindInfo[$mfid]:array();
	}
	
	public function getBindInfoToMsIdBatch($msid)
	{
		if(!is_array($msid))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$this->setFieldS('mfid,msid');
		$this->setStype(3);
		$this->setKeyS('msid');
		$this->setSotherKey('mfid');
		return $this->show(array('msid'=>$msid));
	}
	
	public function getBindInfoToMsId($msid)
	{
		if(!is_numeric($msid))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$bindInfo = $this->getBindInfoToMsIdBatch(array($msid));
		return $bindInfo[$msid]?$bindInfo[$msid]:array();
	}
	
	private function dataFormatError()
	{
		if(!is_array($this->data)) 
		{
			throw new Exception(DATA_FORMAT_ERROR, 200);
		}
	}
}

?>