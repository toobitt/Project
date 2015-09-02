<?php
/***************************************************************************
 * $Id: member_extension_field_update.php 26794 2013-08-01 04:34:02Z lijiaying $
 ***************************************************************************/
define('MOD_UNIQUEID','member_extension_sort');//模块标识
require('./global.php');
class memberextensionsortUpdateApi extends adminUpdateBase
{
	private $mmemberextensionsort;
	public function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		require_once CUR_CONF_PATH . 'lib/member_extension_sort.class.php';
		$this->mmemberextensionsort = new mmemberextensionsort();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		$extension_sort 	  = trim($this->input['extension_sort']);
		$extension_sort_name 	  = trim($this->input['extension_sort_name']);
		$create_time	  = TIMENOW;
			
		if (!$extension_sort_name)
		{
			$this->errorOutput(EXTENSION_SORT_NAME_NOT_NULL);
		}
		if (!$extension_sort)
		{
			$this->errorOutput(EXTENSION_SORT_FIELD_NOT_NULL);
		}


		$this->checkFieldFormat($extension_sort);
		//验证 $extension_sort 是否存在
		$condition = " AND extension_sort = '" . $extension_sort . "'";
		$member_extension_sort = $this->mmemberextensionsort->get_member_extension_sort($condition);
		if (!empty($member_extension_sort))
		{
			$this->errorOutput(EXTENSION_SORT_FIELD_EXIST);
		}

		//验证 $extension_sort_name 是否存在
		$condition = " AND extension_sort_name = '" . $extension_sort_name . "'";
		$member_extension_sort_name = $this->mmemberextensionsort->get_member_extension_sort($condition);
		if (!empty($member_extension_sort_name))
		{
			$this->errorOutput(EXTENSION_SORT_NAME_EXIST);
		}


		$data = array(
			'extension_sort'		=> $extension_sort,
			'extension_sort_name'		=> $extension_sort_name,
			'create_time'		=> $create_time,
		);

		$ret = $this->mmemberextensionsort->create($data);

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
		$extension_sort	  = trim($this->input['extension_sort']);
		$extension_sort_name	  = trim($this->input['extension_sort_name']);
		$update_time	=	TIMENOW;
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		if (!$extension_sort_name)
		{
			$this->errorOutput(EXTENSION_SORT_NAME_NOT_NULL);
		}
		if (!$extension_sort)
		{
			$this->errorOutput(EXTENSION_SORT_FIELD_NOT_NULL);
		}
		$this->checkFieldFormat($extension_sort);

		$condition = " AND extension_sort = '" . $extension_sort . "' AND extension_sort_id  = " . $this->input['id'];
		$member_extension_sort = $this->mmemberextensionsort->get_member_extension_sort($condition);
		if (empty($member_extension_sort))
		{
			$this->errorOutput(EXTENSION_SORT_NOT_UPDATE);
		}

		//验证 $extension_sort_name 是否存在
		$condition = " AND extension_sort_name = '" . $extension_sort_name . "' AND extension_sort_id NOT IN ('" . $this->input['id'] . "')";
		$member_extension_sort = $this->mmemberextensionsort->get_member_extension_sort($condition);
		if (!empty($member_extension_sort))
		{
			$this->errorOutput(EXTENSION_SORT_NAME_EXIST);
		}

		$data = array(
		'extension_sort'		=> $extension_sort,
		'extension_sort_name'	=>	$extension_sort_name,
		'update_time'		=>		$update_time,
		);

		$ret = $this->mmemberextensionsort->update($this->input['id'],$data);

		if (!$ret)
		{
			$this->errorOutput(UPDATE_FAILED);
		}

		$this->addItem($data);
		$this->output();
	}

	public function delete()
	{
		$extension_sort_id = trim($this->input['id']);
		if (!$extension_sort_id)
		{
			$this->errorOutput(NO_EXTENSION_FIELD_ID);
		}

		//验证 分类是否已被使用，使用则禁止删除。
		$condition = " AND sort.extension_sort_id IN (".$extension_sort_id.')';
		$sql = "SELECT * FROM " . DB_PREFIX . "member_extension_field AS field LEFT JOIN ". DB_PREFIX . "member_extension_sort AS sort ON field.extension_sort_id =sort.extension_sort_id";
		$sql.= " WHERE 1 " . $condition;
		$member_extension_sort  = $this->db->query_first($sql);
		if (!empty($member_extension_sort))
		{
			$this->errorOutput(EXTENSION_SORT_USES_NOT_DELETE);
		}

		$ret = $this->mmemberextensionsort->delete($extension_sort_id);

		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}

		$this->addItem($extension_sort_id);
		$this->output();
	}

	public function audit(){}
	public function sort(){
		$this->addLogs('更改会员扩展分类排序', '', '', '更改会员扩展分类排序');
		$ret = $this->drag_order('member_extension_sort', 'order_id','extension_sort_id');
		$this->addItem($ret);
		$this->output();
	}
	public function publish(){}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new memberextensionsortUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>