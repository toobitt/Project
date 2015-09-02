<?php
/***************************************************************************
 * $Id: member_extension_field.php 43525 2015-01-13 09:15:35Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_extension_field');//模块标识
require('./global.php');
class memberExtensionFieldApi extends adminReadBase
{
	private $mMemberExtensionField;
	public function __construct()
	{
		parent::__construct();
		$this->mMemberExtensionField = new memberExtensionField();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
		$this->verify_setting_prms();
		$condition 	= $this->get_condition();
		$offset 	= $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  	= $this->input['count'] ? intval($this->input['count']) : 20;

		$info = $this->mMemberExtensionField->show($condition, $offset, $count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$extension_field_id = trim($this->input['id']);
		$info = $this->mMemberExtensionField->detail($extension_field_id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMemberExtensionField->count($condition);
		echo json_encode($info);
	}

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND extension_field_name LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		if(isset($this->input['extension_sort_id']) && $this->input['extension_sort_id'] != -1)
		{
			$condition .= " AND field.extension_sort_id = " . trim($this->input['extension_sort_id']) ;
		}
		return $condition;
	}

}

$out = new memberExtensionFieldApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>