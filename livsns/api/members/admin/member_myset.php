<?php
define('MOD_UNIQUEID','member_myset');//模块标识
require('./global.php');
class member_myset extends adminReadBase
{
	public $memberMySet;
	public function __construct()
	{
		parent::__construct();
		$this->memberMySet = new memberMySet();
		if(empty($this->settings['mySetUseSource']))
		{
			$this->settings['mySetUseSource'] = array(
					'0'=>'不限制',
					'1'=>'网页端专用',
					'2'=>'手机端专用',
				);
		}
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
		$info 	= $this->memberMySet->show($condition,$offset,$count);

		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				$v['usesourcename'] = $this->settings[mySetUseSource][$v['usesource']];
				$this->addItem($v);
			}
		}

		$this->output();
	}

	public function detail()
	{
		$id = intval($this->input['id']);
		if(empty($id))
		{
			return false;
		}
		$info = $this->memberMySet->detail($id);
		$this->addItem($info);
		$this->output();
	}
	
	public function count()
	{
		$condition = $this->get_condition();
		$ret[total] = $this->memberMySet->count($condition);
		echo json_encode($ret);
	}	

	private function get_condition()
	{
		$condition = '';
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title LIKE \'%' . trim($this->input['k']) . '%\'';
		}
		return $condition;
	}

}

$out = new member_myset();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>