<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/webView.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_webview extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new webView();
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
		$webview_info = $this->api->show($data);
		$this->setXmlNode('webview_info', 'webview');
		if ($webview_info)
		{
			foreach ($webview_info as $webview)
			{
				$this->addItem($webview);
			}
		}
		$this->output();
	}
	
	/**
	 * 数据总数
	 */
	public function count()
	{
		$condition = $this->condition();
		$info = $this->api->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 单个数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$data = array('id' => $id);
		$webview_info = $this->api->detail('app_webview', $data);
		$this->addItem($webview_info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		return array();
	}
}

$out = new app_webview();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>