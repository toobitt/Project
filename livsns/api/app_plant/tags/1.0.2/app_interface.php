<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appInterface.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_interface extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appInterface();
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
		$appInterface_info = $this->api->show($data);
		$this->setXmlNode('appInterface_info', 'interface');
		if ($appInterface_info)
		{
			foreach ($appInterface_info as $interface)
			{
				$this->addItem($interface);
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
		$appInterface_info = $this->api->detail('app_interface', $data);
		if ($appInterface_info)
		{
			if (unserialize($appInterface_info['pic']))
			{
				$appInterface_info['pic'] = unserialize($appInterface_info['pic']);
			}
			$module_id = isset($this->input['mod_id']) ? intval($this->input['mod_id']) : 0;
			//获取对应的属性
			$attr_info = $this->api->get_attribute($id, $module_id, true);
			if ($attr_info)
			{
			    $attr_info = $attr_info[$id];
			    $arr = $group_ids = array();
			    foreach ($attr_info as $v)
			    {
			        if ($v['owning_group'])
			        {
    			        $arr[$v['owning_group']][] = $v;
    			        $group_ids[$v['owning_group']] = $v['owning_group'];
			        }
			    }
			    if ($group_ids)
			    {
			        $ids = implode(',', $group_ids);
			        
			        $group_info = $this->api->getGroup($ids);
			        if ($group_info)
			        {
			            foreach ($group_info as $k => $v)
			            {
			                $group_info[$k]['group'] = $arr[$k];
			            }
			            $arr = $group_info;
			        }
			    }
			    if (empty($arr)) $arr = $attr_info;
			    $appInterface_info['attr'] = $arr;
			}
		}
		$this->addItem($appInterface_info);
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

$out = new app_interface();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>