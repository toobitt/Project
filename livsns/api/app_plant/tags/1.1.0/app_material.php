<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appMaterial.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_material extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appMaterial();
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
		$material_info = $this->api->show($data);
		$this->setXmlNode('material_info', 'material');
		if ($material_info)
		{
			foreach ($material_info as $material)
			{
				$this->addItem($material);
			}
		}
		$this->output();
	}
	
	/**
	 * 单个数据
	 */
	public function detail()
	{
		$id = intval($this->input['id']);
		$data = array('id' => $id);
		$material_info = $this->api->detail('app_material', $data);
		$this->addItem($material_info);
		$this->output();
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
	    $id = trim($this->input['id']);
	    $data = array();
	    if (!empty($id)) $data['id'] = $id;
		return $data;
	}
	
	/**
	 * 上传图片
	 */
	public function upload()
	{
	    if (!$_FILES['Filedata'])
	    {
	        $this->errorOutput(PARAM_WRONG);
	    }
	    $material = new material();
	    $result = $material->addMaterial($_FILES, '', '', '', '', 'png');
	    if (!$result) $this->errorOutput(FAILED);
	    $flag = !!$this->input['flag'];
	    if ($flag)
	    {
    	    $picData = array(
    			'material_id' => $result['id'],
    			'name' => $result['name'],
    			'mark' => $result['mark'],
    			'type' => $result['type'],
    			'filesize' => $result['filesize'],
    			'imgwidth' => $result['imgwidth'],
    			'imgheight' => $result['imgheight'],
    			'host' => $result['host'],
    			'dir' => $result['dir'],
    			'filepath' => $result['filepath'],
    			'filename' => $result['filename'],
    			'user_id' => $this->user['user_id'],
    			'user_name' => $this->user['user_name'],
    			'org_id' => $this->user['org_id'],
    			'create_time' => $result['create_time'],
    			'ip' => $result['ip']
    		);
    		$result = $this->api->create('app_material', $picData);
	    }
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除图片
	 */
	public function delete()
	{
	    $id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		$result = $this->api->delete('app_material', array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
}

$out = new app_material();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>