<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: email_settings.php 41583 2014-11-13 05:46:44Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID', 'email');
require('global.php');
class emailSettingsApi extends adminReadBase
{
	private $mEmailSettings;
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'manage'			=>'管理',
		);
		parent::__construct();
		class_exists('emailSettings') OR require CUR_CONF_PATH . 'lib/email_settings.class.php';
		$this->mEmailSettings = new emailSettings();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;

		$info = $this->mEmailSettings->show($condition, $offset, $count);

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
		$id = trim($this->input['id']);
		$info = $this->mEmailSettings->detail($id);
		$this->addItem($info);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mEmailSettings->count($condition);
		echo json_encode($info);
	}
	
	public function getEmailContentField()
	{
		$condition = '';
		if($sid = $this->input['id'])
		{
			$condition .= " AND id =".$sid;
		}
		$object = new email_content_template();
		$contentField = $object->show('', 0, 100,'','','name,appuniqueid');
		$setInfo = $this->mEmailSettings->show($condition, 0, 0,'','','appuniqueid','appuniqueid');
		if(is_array($contentField))
		{
			foreach ($contentField as $v)
			{
				if(!$sid&&!array_key_exists($v['appuniqueid'], $setInfo)){
					$this->addItem_withkey($v['appuniqueid'], $v['name']);
				}
				elseif($sid&&array_key_exists($v['appuniqueid'], $setInfo))
				{
					$this->addItem_withkey($v['appuniqueid'], $v['name']);
				}
			}
		}
		$this->output();
	}
	
	public function getEmailSettings()
	{
		$appuniqueid = trim($this->input['appuniqueid']);
		if (!$appuniqueid)
		{
			$this->errorOutput('应用标识未传入');
		}
		
		$info = $this->mEmailSettings->getEmailSettings($appuniqueid);
		$this->addItem($info);
		$this->output();
	}

	public function getEmailSettingsById()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$info = $this->mEmailSettings->detail($id);
		$info['appuniqueid'] = $this->settings['email_type'][$info['appuniqueid']];
		$this->addItem($info);
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = $this->mEmailSettings->get_condition();
		return $condition;
	}
	
	public function index()
	{
		
	}
}

$out = new emailSettingsApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>