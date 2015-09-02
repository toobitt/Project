<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/iconManage.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_icon extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new iconManage();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
	}
	
	/**
	 * 显示数据
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $this->condition()
		);
		$icon_info = $this->api->show($data);
		if ($icon_info)
		{
		    foreach ($icon_info as $icon)
		    {
		        $this->addItem($icon);
		    }
		}
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$type = trim(urldecode($this->input['type']));
		if (empty($type)) return array();
		$info = $this->api->getCategoryIdByTag($type);
		if ($info)
		{
    		return array(
    		    'category_id' => implode(',', $info)
    		);
		}
	}
}

$out = new app_icon();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>