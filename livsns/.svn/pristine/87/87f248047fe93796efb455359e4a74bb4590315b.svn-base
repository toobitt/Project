<?php
/***************************************************************************

* $Id: member.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class memberMyField extends classCore
{
	private $membersql;
	private $mfid = 0;
	private $fieldmark = '';
	private $fieldname = '';
	private $brief = '';
	private $isunique = 0;
	private $addstatus = 0;
	private $issearch = 0;
	private $isrequired = 0;
	private $defaultsvalue = '';
	private $user_id = 0;
	private $user_name = '';
	private $create_time = 0;
	private $update_time = 0;
	private $params = array();
	private $offset = 0;
	private $count = 0;
	private $field = '*';
	private $key = '';
	private $orderby = '';
	private $Stype = 1;
	private $SotherKey = '';
	private $paramType = array();
	private $markInfo = array();
	private $data = array();
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();

	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * 设置数据标识 ...
	 * @param unknown_type $_mark
	 */
	public function setMark($_mark,$isEnforce = 1)
	{
		if($_mark)
		{

			if (preg_match("/([\x81-\xfe][\x40-\xfe])/",$_mark))
			{
				throw new Exception(PROHIBIT_CN, 200);
			}
			elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$_mark))
			{
				throw new Exception(MARK_CHARACTER_ILLEGAL, 200);
			}
			else if(!$this->_getMark($_mark))
			{
				throw new Exception(MARK_ERROR, 200);
			}
		}
		else if ($isEnforce)
		{
			throw new Exception(NO_MARK_ERROR, 200);
		}
		return $_mark;
	}
	
	/**
	 * 
	 * 设置数据标识 ...
	 * @param unknown_type $_fieldmark
	 */
	public function setFieldMark($_fieldmark,$isEnforce = 1)
	{
		if($_fieldmark)
		{

			if (preg_match("/([\x81-\xfe][\x40-\xfe])/",$_fieldmark))
			{
				throw new Exception(PROHIBIT_CN, 200);
			}
			elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$_fieldmark))
			{
				throw new Exception(MARK_CHARACTER_ILLEGAL, 200);
			}
		}
		else if ($isEnforce)
		{
			throw new Exception(NO_MARK_ERROR, 200);
		}
		$this->fieldmark = (string)$_fieldmark;
		return $this->fieldmark;
	}
	
	public function setFieldName($_fieldname,$isEnforce = 1)
	{
		$_fieldname = trim($_fieldname);
		if (!$_fieldname && $isEnforce)
		{
			throw new Exception(NO_NAME, 200);
		}
		$this->fieldname = (string)$_fieldname;
		return $this->fieldname;
	}
	
	public function setIsUnique($_isunique)
	{
		$this->isunique = (int)$_isunique;
	}
	
	public function setAddStatus($_addStatus)
	{
		$this->addstatus = (int)$_addStatus;
	}
	
	public function setIsRequired($_isrequired)
	{
		$this->isrequired = (int)$_isrequired;
	}
	
	public function setIsSearch($_issearch)
	{
		($this->isrequired || in_array($this->addstatus, array('1','2','3')) || $this->defaultsvalue) && $this->issearch = (int)$_issearch;
	}
	public function setDefaultsValue($_defaultsValue)
	{
		$this->defaultsvalue = $_defaultsValue;
	}
	
	public function setBrief($_brief)
	{
		$this->brief = trim($_brief);
	}
	
	public function setUserId($_user_id)
	{
		$this->user_id = intval($_user_id);
	}
	
	public function setUserName($_user_name)
	{
		$this->user_name = trim($_user_name);
	}
	
	public function setCreateTime()
	{
		$this->create_time = TIMENOW;
	}
	
	public function setUpdateTime()
	{
		$this->update_time = TIMENOW;
	}
	
	private function _getMark($_mark)
	{
		if(!$this->markInfo && $_mark)
		{
			$memberMySet = new memberMySet();
			$this->markInfo = $memberMySet->show(array('mark'=>$_mark),0,0,'title,mark','mark');
		}
		else if (!$_mark) 
		{
			throw new Exception(NO_MARK_ERROR, 200);
		}
		return $this->markInfo[$_mark];
	}
		
	
	public function count($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		$this->membersql->limit($this->offset, $this->count);
		return $this->membersql->count(array(), 'member_myField');
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
		return $this->membersql->verify('member_myField',$param);
	}
	
	public function dataExists($param = array())
	{
		!$param && $param = $this->params;
		if(!$param)
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		if ($ret = $this->verify($param))
		{
			return $ret;
		}
		throw new Exception(NO_DATA, 200);
	}
	
	public function show($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		$this->membersql->orderby('ORDER BY update_time DESC');
		return $this->membersql->show(array(), 'member_myField', $this->offset, $this->count,$this->orderby,$this->field,$this->key,array(),$this->Stype,$this->SotherKey);
	}
	
	public function setDataFormat($dataformat)
	{
		$this->membersql->setDataFormat($dataformat);
	}
	
	public function setJoin($sql = '')
	{
		$this->membersql->join($sql);
	}
	
	public function setSql()
	{
		return $this->membersql;
	}
	public function setMfid($id)
	{
		$this->mfid = $id;
		if(!$this->mfid)
		{
			throw new Exception(NO_DATA_ID, 200);
		}
	}
	public function getMfid()
	{
		return $this->mfid;
	}
	
	public function setAs($asname='')
	{
		$this->membersql->setAsTable($asname);
	}
	
	public function setOffsetS($_offset = 0)
	{
		$this->offset = (int)$_offset;
		return $this;
	}
	public function setCountS($_count = 0)
	{
		$this->count = (int)$_count;
		return $this;
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
		return $this->membersql->detail($this->params, 'member_myField',array('create_time'=>array('type'=>'date','format'=>'Y-m-d H:i'),'update_time'=>array('type'=>'date','format'=>'Y-m-d H:i')));
	}
	
	public function getMyFieldMarkToIdBatch($mark)
	{
		if(!is_array($mark))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$this->setFieldS('mfid,fieldmark');
		$this->setKeyS('fieldmark');
		$this->setSotherKey('mfid');
		$this->setStype(3);
		return $this->show(array('fieldmark'=>$mark));
	}
	
	public function getMyFieldMarkToId($mark)
	{
		$mySetInfo = $this->getMyFieldMarkToIdBatch(array($mark));
		return $mySetInfo[$mark];
	}
	
	
	public function getMyFieldIdToMarkBatch($id)
	{
		if(!is_array($id))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$this->setFieldS('mfid,fieldmark');
		$this->setKeyS('mfid');
		$this->setSotherKey('fieldmark');
		$this->setStype(4);
		return $this->show(array('mfid'=>$id));
	}
	
	public function getMyFieldIdToMark($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception(PARAM_WRONG, 200);
		}
		$mySetInfo = $this->getMyFieldIdToMarkBatch(array($id));
		return $mySetInfo[$id];
	}
	
	public function create()
	{
		$this->dataFormatError();
		$this->membersql->setPk('mfid');
		return $this->membersql->create('member_myField', $this->data);
	}
	public function update()
	{
		$this->dataFormatError();
		return $this->membersql->update('member_myField',$this->data,$this->params);
	}
	public function delete()
	{
		return $this->membersql->delete('member_myField', $this->params);
	}
	
	public function myFieldRulesRequired($fieldInfo,$input,& $data)
	{
		if($fieldInfo['isrequired'] && !$input)
		{
			throw new Exception(MYSET_FIELD_REQUIRED_ERROR, 200);
		}
       $data[$fieldInfo['fieldmark']] = $input;
       return $input;
	}
	
	public function myFieldRulesSearch($fieldInfo,& $search)
	{
		if($fieldInfo['issearch'])
		{	
		  $search[] = $fieldInfo['fieldmark'];
		}
	}
	
	public function myFieldRulesIsunique($fieldInfo , & $data)
	{
		$isunique = $fieldInfo['isunique'];
		if($isunique)
		{
			 $data[] = $fieldInfo['fieldmark'];
		}
		return $isunique;
	}
	
	public function myFieldRulesAddStatus($fieldInfo , & $data)
	{
		$addstatus = $fieldInfo['addstatus'];
		if($addstatus == 1)
		{
			 $data[$fieldInfo['fieldmark']] = $fieldInfo['defaultsvalue'];
		}
		else if ($addstatus == 2)
		{
			 $data[$fieldInfo['fieldmark']] = date('Y-m-d H:i:s',TIMENOW);
		}
		else if ($addstatus == 3)
		{
			 $data[$fieldInfo['fieldmark']] = hg_getip();
		}
		return $addstatus;
	}
	
	public function myFieldRulesDefaults($fieldInfo , & $data)
	{
		if(! $data[$fieldInfo['fieldmark']] && $fieldInfo['defaultsvalue'])
		{
				$data[$fieldInfo['fieldmark']] = $fieldInfo['defaultsvalue'];
		}
        else if(! isset($data[$fieldInfo['fieldmark']]))
        {
        	    $data[$fieldInfo['fieldmark']] = '';
        }
               
	}
	
	public function display($opened,$field)
	{
		$ret = array();
		if($field)
		{			
			$myFieldInfo = $this->setFieldS($field)->detail();
			if($myFieldInfo[$field]  != $opened && in_array($field, array('isrequired','issearch')))
			{
				$this->setDatas($field,$opened);
				if($field == 'isrequired' && !$opened)
				{
					$this->setDatas('issearch',0);
				}
				$ret = $this->update();
			}
		}
		$arr = array(
			'id' => $this->mfid,
			'display' => $opened ? 1 : 0,
		);
		return $arr;
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