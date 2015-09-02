<?php
/***************************************************************************
 * $Id: member_extension_field_update.php 43556 2015-01-14 08:44:27Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_extension_field');//模块标识
require('./global.php');
class memberExtensionFieldUpdateApi extends adminUpdateBase
{
	private $mMemberExtensionField;
	public function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		$this->mMemberExtensionField = new memberExtensionField();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$extension_field 	  = trim($this->input['extension_field']);
		$extension_field_name = trim($this->input['extension_field_name']);
		$extension_sort_id = trim($this->input['extension_sort_id']);
		$is_unique			  = intval($this->input['is_unique']);
		$type			  = intval($this->input['type']);
		$create_time 	=	 TIMENOW;
		if (!$extension_field_name)
		{
			$this->errorOutput(EXTENSION_FIELD_NAME_NOT_NULL);
		}
		
		if (!$extension_field)
		{
			$this->errorOutput(EXTENSION_FIELD_NOT_NULL);
		}
		
		if (!$extension_sort_id)
		{
			$this->errorOutput(EXTENSION_SORT_NOT_SELECT);
		}
		
		$member_base_info = array();
		$this->settings['member_base_info']&&$member_base_info = $this->settings['member_base_info'];
		foreach ($member_base_info as $v)
		{
		if($v['field'] == $extension_field)	
		{
			$this->errorOutput('扩展标识禁止含有基本资料字段:'.$v['field_name']);
		}
		}
		$this->checkFieldFormat($extension_field);
		//验证 $extension_field_name 是否存在
		$condition = " AND extension_field_name = '" . $extension_field_name . "'";
		$member_extension_field = $this->mMemberExtensionField->get_member_extension_field($condition);
		if (!empty($member_extension_field))
		{
			$this->errorOutput(EXTENSION_FIELD_NAME_EXIST);
		}
		
		//验证 $extension_field 是否存在
		$condition = " AND extension_field = '" . $extension_field . "'";
		$member_extension_field = $this->mMemberExtensionField->get_member_extension_field($condition);
		if (!empty($member_extension_field))
		{
			$this->errorOutput(EXTENSION_FIELD_EXIST);
		}

		$data = array(
			'extension_field'		=> $extension_field,
			'extension_field_name'	=> $extension_field_name,
			'extension_sort_id'	=> $extension_sort_id,
			'create_time'		=> $create_time,
			'is_unique'				=> $is_unique,
			'type' 				=>$type,
		);

		$ret = $this->mMemberExtensionField->create($data);

		if (!$ret)
		{
			$this->errorOutput(ADD_FAILED);
		}

		$this->addItem($data);
		$this->output();
	}
	
	private function checkFieldFormat($field)
	{
		if(preg_match("/[\'.,:;*?~`!@#$%^&+\-=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$field))
		{
			$this->errorOutput('标识禁止使用特殊字符,请使用字母、字母和数字组合(可用下划线连接)!');
		}
		elseif(is_numeric($field))
		{
			$this->errorOutput('标识禁止全数字');
		}
		elseif (preg_match("/([\x81-\xfe][\x40-\xfe])/", $field))
		{
			$this->errorOutput('标识禁止使用或者含有汉字');
		}
		return $field;
	}
	

	public function update()
	{
		$extension_field 	  = trim($this->input['extension_field']);
		$extension_field_name = trim($this->input['extension_field_name']);
		$extension_sort_id = trim($this->input['extension_sort_id']);
		$is_unique			  = intval($this->input['is_unique']);
		$update_time	=	TIMENOW;

		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		if (!$extension_field_name)
		{
			$this->errorOutput(EXTENSION_FIELD_NAME_NOT_NULL);
		}
		
		if (!$extension_field)
		{
			$this->errorOutput(EXTENSION_FIELD_NOT_NULL);
		}

		if (!$extension_sort_id)
		{
			$this->errorOutput(EXTENSION_SORT_NOT_NULL);
		
		}
		$member_base_info = array();
		$this->settings['member_base_info']&&$member_base_info = $this->settings['member_base_info'];
		foreach ($member_base_info as $v)
		{
			if($v['field'] == $extension_field)	
			{
				$this->errorOutput('扩展标识禁止含有基本资料字段:'.$v['field_name']);
			}
		}
		$this->checkFieldFormat($extension_field);
		//验证 $extension_field_name 是否存在
		$condition = " AND extension_field_name = '" . $extension_field_name . "' AND extension_field_id NOT IN ('" . $this->input['id'] . "')";
		$member_extension_field = $this->mMemberExtensionField->get_member_extension_field($condition);
		if (!empty($member_extension_field))
		{
			$this->errorOutput(EXTENSION_FIELD_NAME_EXIST);
		}
		
		$condition = " AND extension_field = '" . $extension_field . "' AND extension_field_id = " . $this->input['id'];
		$member_extension_field = $this->mMemberExtensionField->get_member_extension_field($condition);
		if (empty($member_extension_field))
		{
			$this->errorOutput(EXTENSION_FIELD_NOT_UPDATE);
		}

		$data = array(
		
			'extension_field'		=> $extension_field,
			'extension_field_name'	=> $extension_field_name,
			'extension_sort_id'	=> $extension_sort_id,
			'is_unique'				=> $is_unique,
			'update_time'			=> $update_time,
		);

		$ret = $this->mMemberExtensionField->update($this->input['id'],$data);

		if (!$ret)
		{
			$this->errorOutput(UPDATE_FAILED);
		}

		$this->addItem($data);
		$this->output();
	}

	public function delete()
	{
		$extension_field_id = trim($this->input['id']);
		if (!$extension_field_id)
		{
			$this->errorOutput(NO_EXTENSION_FIELD_ID);
		}
		$ret = array();
		if($this->mMemberExtensionField->delete_member_info($extension_field_id))
		{
			$ret = $this->mMemberExtensionField->delete($extension_field_id);
		}
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		$this->addItem($extension_field_id);
		$this->output();
	}

	public function audit(){}
	public function sort(){
		$this->addLogs('更改会员扩展字段排序', '', '', '更改会员扩展字段排序');
		$ret = $this->drag_order('member_extension_field', 'order_id','extension_field_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish(){}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new memberExtensionFieldUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>