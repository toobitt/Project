<?php
/***************************************************************************

* $Id: memberMyData.class.php 36754 2014-04-30 10:02:14Z youzhenghuan $

***************************************************************************/
class memberMyData extends classCore
{
	private $membersql;
	private $memberId;
	private $mark = '';
	private $myData = array();
	private $serach = array();
	private $data = array();
	private $outputData = array();
	private $myDataInfo = array();
	private $mdid;
	private $dataunique = array();
	private $params = array();
	private $offset = 0;
	private $count = 0;
	private $field = '*';
	private $key = '';
	private $orderby = '';
	private $paramType = array();
	public function __construct()
	{
		parent::__construct();
		$this->membersql = new membersql();

	}
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function setMemberId($_member_id)
	{
		$Members = new members();
		if($_member_id)
		{
			if(!$Members->checkuser($_member_id))
			{
				return -1;//$this->errorOutput(NO_MEMBER);
			}
		}
		elseif(!$_member_id)
		{
			return -2;//$this->errorOutput(NO_MEMBER_ID);
		}
		$this->memberId = (int)$_member_id;
		return $this->memberId;
	}
	
	/**
	 * 
	 * 设置数据标识 ...
	 * @param unknown_type $_mark
	 */
	public function setMark($_mark)
	{
		if($_mark)
		{

			if (preg_match("/([\x81-\xfe][\x40-\xfe])/",$_mark))
			{
				return -3;//$this->errorOutput(PROHIBIT_CN);
			}
			elseif(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$_mark))
			{
				return -4;//$this->errorOutput(MARK_CHARACTER_ILLEGAL);
			}
			else if(!$this->_getMark($_mark))
			{
				return -5;//$this->errorOutput(MARK_ERROR);
			}
		}
		else
		{
			return -6;//$this->errorOutput(NO_MARK_ERROR);
		}
		$this->mark = (string)$_mark;
		return $this->mark;
	}
	/**
	 * 
	 * 设置数据ID ...
	 * @param unknown_type $_id
	 */
	public function setMdid($_id)
	{
		if(is_numeric($_id)&&$_id>0)
		{		
			$this->setParams('mdid', $_id);
			$this->detail();
			$this->unsetParams('mdid');
			if(!$this->myDataInfo)
			{
				return -9;
			}
		}
		else
		{
			return -10;//$this->errorOutput(NO_MARK_ERROR);
		}
		$this->mdid = (int)$_id;
		return $this->mdid;
	}
	
	/**
	 * 批量设置数据ID
	 */
	public function setManyMdid($_id,$isRequired = true)
	{
		$arrValue = array();
		if($_id && !is_array($_id)){
			$arrValue = explode(',',$_id);//转为数组方便字符串转换
		}
		else if( $_id )
		{
			$arrValue = $id;
		}
		if($arrValue&&is_array($arrValue))
		{
		  $arrValue=array_filter($arrValue,"clean_array_null");
		  $arrValue=array_filter($arrValue,"clean_array_num_max0");
		}		
		if(!$arrValue && $isRequired)
		{
			throw new Exception(NO_INPUT_MYDATA_ID, 200);
		}
		foreach ($arrValue as $v)
		{
			$ret = $this->setMdid($v);
			if($ret == -9)
			{				
				throw new Exception(NO_DATA, 200);
			}
			else if ($ret == -10)
			{				
				throw new Exception(NO_INPUT_MYDATA_ID, 200);
			}
			$this->myDataInfo = array();
		}
		$this->mdid = $arrValue;
		return $this->mdid;
	}
	
	/**
	 * 
	 * 检测数据隐私级别 ...
	 */
	public function checkMeData($isPrivacy = 0)
	{		
		if(!$this->myDataInfo)
		{
			$this->setParams('member_id', $this->memberId);
			$isPrivacy && $this->setParams('privacy', 1);
			$count = $this->count();
			$this->unsetParams();
		}
		else
		{	
			$privacy = 1;	
			if($this->myDataInfo['member_id']!= $this->memberId)
			{
				if(!$isPrivacy||($isPrivacy&&$this->myDataInfo['privacy']==1))
				{
					$privacy = 0;
				}
			}
			return $privacy;
		}
		return $count?1:0;
	}
	
	public function count($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		$this->membersql->limit($this->offset, $this->count);
		$this->membersql->orderby($this->orderby);
		return $this->membersql->count(array(), 'member_mydata');
	}
	
	public function setParams($key,$val = null,$asname='')
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				if($val[$k])
				{
					$this->setParams($v, $val[$k]);
				}
				else {
					$this->setParams($v);
				}
			}
		}
		elseif($key&&$val) {
			$this->params[($asname?$asname.'.':'').$key] = $val;
		}
		elseif($key)
		{
			$this->$key&&$this->params[($asname?$asname.'.':'').$key] = $this->$key;
		}
		return $this->params[($asname?$asname.'.':'').$key];
	}
	public function unsetParams($key = '')
	{
		if($key&&isset($this->params[$key]))
		{
			unset($this->params[$key]);
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
	public function setDatas($key,$val)
	{
		if(is_array($key))
		{
			foreach ($key as $k => $v)//$key数组表单名称必须和$val值下标对应
			{
				$this->setDatas($v, $val[$k]);
			}
		}
		else $this->data[$key] = $val;
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
	
	public function getMark($_mark = '',$field = '')
	{
		$markInfo = array();
		$_mark?$_mark:$this->mark;
		!$_mark     && ($_mark = $this->mark = $this->myDataInfo['mark']);
		$this->markInfo && $markInfo = $this->markInfo[$_mark];
		if(!$markInfo)
		{
			$markInfo = $this->_getMark($_mark);
		}
		if($field && array_key_exists($field, (array)$markInfo))
		{
			return $markInfo[$field];
		}
		return $markInfo?$markInfo:array();
	}
	private function _getMark($_mark)
	{
		$memberMySet = new memberMySet();
		$this->markInfo = $memberMySet->show(array('mark'=>$_mark),0,0,'id,title,mark,uniquetype','mark');
		return $this->markInfo[$_mark];
	}

	public function setMyData($_myData)
	{
		if($_myData&&is_array($_myData))
		{	
			$_myData = $this->myDataFieldProcess($_myData);
			return $this->myData = $_myData;
		}
		elseif($_myData){
			return -7;
		}
		return 0;
	}
	
	public function myDataFieldProcess($_myData)
	{
		$data = array();
		$uniquedata = array();
		$search = array();
		if($msid = $this->getMark($this->mark,'id'))
		{
			$memberMySet = new memberMySet();
			if($memberMySet->getMFcount($msid))
			{
				$memberMyFieldBind = new memberMyFieldBind();
				$mfidArr = $memberMyFieldBind->getBindInfoToMsId($msid);
				$memberMyField = new memberMyField();
				$memberMyField->setFieldS('fieldmark,isunique,addstatus,issearch,isrequired,defaultsvalue');
				$memberMyField->setParams('mfid',$mfidArr);
				$myFieldInfo = $memberMyField->show();
				$uniquetype = $this->getMark($this->mark,'uniquetype');
				if($myFieldInfo && is_array($myFieldInfo))
				{
					foreach ($myFieldInfo as $v)
					{
						if(! $memberMyField->myFieldRulesAddStatus($v , $data))
						{
						      $memberMyField->myFieldRulesRequired($v , $_myData[$v['fieldmark']] , $data);
						      $memberMyField->myFieldRulesDefaults($v , $data);
						}
						if($uniquetype)
						{
							$memberMyField->myFieldRulesIsunique($v, $uniquedata);
						}
						$memberMyField->myFieldRulesSearch($v , $search);
					}
				}
				$this->input['serach'] = $search;
			}
		  !$data && $data = $_myData;
		  foreach ($uniquedata as $v)
		  {
		  	$uniquevalue[] = $v.'_'.$data[$v];
		  }
		 $uniquevalue && $this->dataunique[$this->mark] = $dataunique = md5(implode('_', $uniquevalue));
		 if($dataunique && $this->checkdataunique($dataunique))
		 {
			throw new Exception(MYSET_FIELD_UNIQUE_ERROR, 200); 
		 }
		}
		return $data;
	}
	
	public function checkdataunique($dataunique)
	{
		return $this->verify(array('mark'=>$this->mark,'member_id'=>$this->memberId,'dataunique'=>$dataunique));
	}
		
	public function getMyData()
	{
		return $this->myData;
	}
	
	public function setSerach($_serach)
	{
		if($_serach&&!is_array($_serach))
		{
			$_serach = explode(',', $_serach);
		}
		if($_serach&&is_array($this->myData))
		{
			foreach ($_serach as $v)
			{
				if(array_key_exists($v, $this->myData))
				{
					$this->serach[$v] = $this->myData[$v];
				}
			}
			return $this->serach;
		}
		if($_serach)return -8;//储存项格式有误
	}
	
	public function verify($param)
	{
		return $this->membersql->verify('member_mydata',$param);
	}
	/**
	 * 
	 * 输出数据格式统一处理...
	 */
	public function outputProcess($_data = array(),$_mark = '')
	{
		$_data = $_data?$_data:$this->outputData;
		$_mark = $_mark?$_mark:$this->mark;
		$fieldInfo = $this->getMark($_mark);
		$output = array(
		'title' => $fieldInfo['title'],
		'mark'  => $fieldInfo['mark'],
		'data' => array(),
		);
		$_data &&  $output['data'] = $_data;
		return $output;
	}
	
	public function selectDataFormatProcess()
	{
		$ret = array();
		$outputData = $this->outputData;
		$this->outputData = array();
		if(is_array($outputData))
		{
			foreach ($outputData as $k => $v)
			{
				$mydata['mdid'] = $v['mdid'];				
				$mydata = array_merge($mydata,$v['mydata']);
				$this->outputData[] = $mydata;
			}
		}
		return $this;
	}
	
	public function show($condition = array())
	{
		$condition = $condition?$condition:$this->params;
		$this->membersql->where($condition,$this->paramType);
		$this->outputData = $this->membersql->show(array(), 'member_mydata', $this->offset, $this->count,$this->orderby,$this->field,$this->key,array('mydata'=>array('type'=>'array','format'=>'unserialize')));
		return $this;
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
	
	public function setAs($asname='')
	{
		$this->membersql->setAsTable($asname);
	}
	
	public function getoutputData()
	{
		return $this->outputData;
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
	public function detail($_param = array())
	{
		$_param = $_param?$_param:$this->params;
		!$this->myDataInfo && $this->myDataInfo = $this->outputData[0] = $this->membersql->detail($_param, 'member_mydata',array('mydata'=>array('type'=>'array','format'=>'unserialize'),'search'=>array('type'=>'explode','delimiter'=>array("\n",","))));
		 $this->myDataInfo &&  $this->outputData[0] = $this->myDataInfo;
		 return $this;
	}
	public function create($_data = array())
	{
		$_data = $_data?$_data:$this->data;
		return $this->membersql->create('member_mydata', $_data);
	}
	public function update($_data = array(),$_param = array())
	{
		$_data = $_data?$_data:$this->data;
		$_param = $_param?$_param:$this->params;
		return $this->membersql->update('member_mydata',$_data,$_param);
	}
	public function delete($_param = array())
	{
		$_param = $_param?$_param:$this->params;
		return $this->membersql->delete('member_mydata', $_param);
	}
	private function setSerachFormat()
	{
		$tmp_serach = '';
		foreach ($this->serach as $k => $v)
		{
			if($tmp_serach)
			{
				$tmp_serach .="\n";
			}
			$tmp_serach .= $k.','.$v;
		}
		return $tmp_serach;
	}
	private function setmyDataFormat()
	{
		return maybe_serialize($this->myData);
	}
	private function createdata()
	{
		$key = array(
			'mark'   ,
			'member_id'	,
		    'search',
			'dataunique',
			'mydata' ,
			'create_time' ,
			'update_time'  ,
		);
		$val = array(
			$this->mark,
			$this->memberId,
		    $this->setSerachFormat(),
		    $this->dataunique[$this->mark],
			$this->setmyDataFormat(),
			TIMENOW,
			TIMENOW,
		);
		$this->setDatas($key, $val);
	}
	private function updatedata()
	{
			$this->data = array(
		    'search' => $this->setSerachFormat(),
			'mydata' => $this->setmyDataFormat(),
			'update_time'  => TIMENOW,
		);
	}
	public function deleteparam()
	{
		$this->setParams('mdid', $this->mdid);
	}
	
	public function updateparam()
	{
		$this->setParams('mdid', $this->mdid);
	}
	
	public function showparam()
	{
		$key = array(
			'mark'   ,
			'member_id'	,
		);
		$val = array(
			$this->mark,
			$this->memberId,
		);
		$this->setParams($key, $val);
	}
	
	public function detailparam()
	{
		$key = array(
			'mdid'   ,
		);
		$val = array(
			$this->mdid,
		);
		$this->setParams($key, $val);
	}
	
	public function countparam()
	{
		$key = array(
			'mark'   ,
			'member_id'	,
		);
		$val = array(
			$this->mark,
			$this->memberId,
		);
		$this->setParams($key, $val);
	}
	
	public function dataProcess($action)
	{
		$this->{$action.'data'}();
		return $this;
	}
	public function paramProcess($action)
	{
		$this->{$action.'param'}();
		return $this;
	}
}

?>