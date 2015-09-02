<?php
/***************************************************************************
 * $Id: member_extension_field.php 43525 2015-01-13 09:15:35Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_extension_field');//模块标识
require('./global.php');
require_once(CUR_CONF_PATH . 'lib/app_extension_mode.php');
class memberExtensionFieldApi extends outerReadBase
{
	private $mMemberExtensionField;
	private $appExtension;
	public function __construct()
	{
		parent::__construct();
		$this->mMemberExtensionField = new memberExtensionField();
		$this->appExtension = new app_extension_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function index(){}

	public function show()
	{
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
	
	/**
	 * 获取app设置的扩展属性
	 */
	public function getExtFieldByApp()
	{
	    $app_id = intval($this->input['app_id']);
	    if(!$app_id)
	    {
	        $this->errorOutput(NO_APP_ID);
	    }
	    $con = " AND app_id=".$app_id."";
        $info = $this->appExtension->getAllExtension($con);
	    //处理用户是否选择

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