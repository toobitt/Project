<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appModule.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
include_once ROOT_PATH . 'lib/class/publishconfig.class.php';
define('MOD_UNIQUEID', 'dingdone_app');

class app_module extends appCommonFrm
{
	private $api;
	private $material;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appModule();
		$this->material = new material();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
		unset($this->material);
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
		$appModule_info = $this->api->show($data);
		$this->setXmlNode('appModule_info', 'module');
		if ($appModule_info)
		{
			foreach ($appModule_info as $module)
			{
				$this->addItem($module);
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
		$appModule_info = $this->api->detail('app_module', $data);
		if ($appModule_info)
		{
			if (unserialize($appModule_info['pic']))
			{
				$appModule_info['pic'] = unserialize($appModule_info['pic']);
			}
			if (unserialize($appModule_info['column_ids']))
			{
				$appModule_info['column_ids'] = unserialize($appModule_info['column_ids']);
			}
			$appModule_info['type'] = explode(',', $appModule_info['type']);
			if ($appModule_info['ui_id'])
			{
    			//获取对应界面的属性
    			include_once CUR_CONF_PATH . 'lib/appInterface.class.php';
    			$ui_api = new appInterface();
    			$attr_info = $ui_api->get_attribute($appModule_info['ui_id'], $id, true);
    			if ($attr_info) $appModule_info['attr'] = $attr_info[$appModule_info['ui_id']];
			}
		}
		$this->addItem($appModule_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//验证APP是否存在
		$queryData = array(
		    'id' => $data['app_id'],
		    'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		//是否重名
		$check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		$check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
		if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
		$pic_id = intval($this->input['pic_id']);
		$pic_info = $this->material->get_material_by_ids($pic_id);
		if (!$pic_info[0]) $this->errorOutput(PARAM_WRONG);
		$data['pic'] = serialize($pic_info[0]);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('app_module', $data);
		if ($result['id'])
		{
		    $this->api->update('app_module', array('sort_order' => $result['id']), array('id' => $result['id']));
		    $result['sort_order'] = $result['id'];
		    $this->addItem($result);
		}
		$this->output();
	}
	
	/**
	 * 更新基本数据
	 */
	public function updateBasic()
	{
	    $id = intval($this->input['id']);
	    if ($id <= 0) $this->errorOutput(PARAM_WRONG);
	    $data = $this->filter_data();
	    //验证APP是否存在
		$queryData = array(
		    'id' => $data['app_id'],
		    'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
	    $appModule_info = $this->api->detail('app_module', array('id' => $id, 'app_id' => $data['app_id']));
		if (!$appModule_info) $this->errorOutput(PARAM_WRONG);
		$validate = array();
		if ($appModule_info['name'] != $data['name'])
		{
			//是否重名
			$check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($appModule_info['english_name'] != $data['english_name'])
		{
		    //是否重名
			$check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
			if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
			$validate['english_name'] = $data['english_name'];
		}
		if (isset($this->input['pic_id']))
		{
		    $pic_id = intval($this->input['pic_id']);
		    $pic_info = $this->material->get_material_by_ids($pic_id);
    		if (!$pic_info[0]) $this->errorOutput(PARAM_WRONG);
    		$pic_info = serialize($pic_info[0]);
    		if ($appModule_info['pic'] != $pic_info)
    		{
    		    $validate['pic'] = $pic_info;
    		}
		}
		if ($validate)
		{
			$result = $this->api->update('app_module', $validate, array('id' => $id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新扩展数据
	 */
	public function updateExtra()
	{
	    $id = intval($this->input['id']);
		$app_id = intval($this->input['app_id']);
		if ($id <= 0 || $app_id <= 0) $this->errorOutput(PARAM_WRONG);
		//验证APP是否存在
		$queryData = array(
		    'id' => $app_id,
		    'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		$appModule_info = $this->api->detail('app_module', array('id' => $id, 'app_id' => $app_id));
		if (!$appModule_info) $this->errorOutput(PARAM_WRONG);
		$data = array(
		    'ui_id' => intval($this->input['uiId']),
		    'column_ids' => isset($this->input['columnIds']) ? $this->input['columnIds'] : '',
		    'type' => isset($this->input['type']) ? implode(',', (array)$this->input['type']) : '',
		    'site_id' => intval($this->input['siteId']),
		    'is_sub' => intval($this->input['is_sub']),
		    'icon_bg' => trim(urldecode($this->input['iconBackground'])),
		    'webview_id' => intval($this->input['webviewId']),
		    'webview_url' => trim(urldecode($this->input['webviewUrl'])),
		    'solidify_id' => intval($this->input['solidifyId'])
		);
		$validate = array();
		if ($data['webview_id'] > 0 || $data['webview_url'])
		{
    		if ($data['webview_id'] > 0 && $appModule_info['web_view'] != $data['webview_id'])
    		{
    		    $webview_info = $this->api->detail('app_webview', array('id' => $data['webview_id']));
    		    if (!$webview_info) $this->errorOutput(PARAM_WRONG);
    		    $validate['web_view'] = $data['webview_id'];
    		    $validate['web_url'] = $webview_info['url'];
    		}
    		elseif ($data['webview_url'] && $appModule_info['web_url'] != $data['webview_url'])
    		{
    		    //判断url
    		    if (!filter_var($data['webview_url'], FILTER_VALIDATE_URL))
    		    {
    		        $this->errorOutput(URL_NOT_VALID);
    		    }
    		    $validate['web_url'] = $data['webview_url'];
    		    $validate['web_view'] = -1;
    		}
    		$validate['ui_id'] = 0;
    		$validate['column_ids'] = '';
    		$validate['type'] = '';
    		$validate['solidify_id'] = 0;
		}
		else
		{
    		if ($data['ui_id'] > 0 && $appModule_info['ui_id'] != $data['ui_id'])
    		{
    			$validate['ui_id'] = $data['ui_id'];
    		}
    		if ($data['solidify_id'] > 0 && $appModule_info['solidify_id'] != $data['solidify_id'])
    		{
    		    $validate['solidify_id'] = $data['solidify_id'];
        		$validate['column_ids'] = '';
        		$validate['type'] = '';
    		}
    		else
    		{
        		if ($data['column_ids'] && $data['site_id'])
        		{
            		$publish = new publishconfig();
            		$where = ' AND site_id = ' . $data['site_id'] . ' AND id IN (' . $data['column_ids'] . ')';
            		$column_info = $publish->get_column('*', $where);
            		if (!$column_info) $this->errorOutput(PARAM_WRONG);
            		$column_arr = array();
            		foreach ($column_info as $column)
            		{
            			$column_arr[$column['id']] = $column['name'];
            		}
            		if ($column_arr) $data['column_ids'] = serialize($column_arr);
        		}
        		unset($data['site_id']);
        		if ($appModule_info['column_ids'] != $data['column_ids'])
        		{
        			$validate['column_ids'] = $data['column_ids'];
        		}
        		if ($appModule_info['type'] != $data['type'])
        		{
        			$validate['type'] = $data['type'];
        		}
        		$validate['solidify_id'] = 0;
    		}
    		$validate['web_view'] = 0;
    		$validate['web_url'] = '';
		}
		if ($appModule_info['is_sub'] != $data['is_sub'])
		{
			$validate['is_sub'] = $data['is_sub'];
		}
		if ($appModule_info['icon_bg'] != $data['icon_bg'])
		{
		    if ($data['icon_bg'] && checkColor($data['icon_bg']) === false)
		    {
		        $this->errorOutput(COLOR_ERROR);
		    }
			$validate['icon_bg'] = $data['icon_bg'];
		}
		if ($validate)
		{
			$result = $this->api->update('app_module', $validate, array('id' => $id));
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除数据
	 */
	public function delete()
	{	
		$id = trim(urldecode($this->input['id']));
		$id_arr = explode(',', $id);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if (!$id_arr) $this->errorOutput(PARAM_WRONG);
		$ids = implode(',', $id_arr);
		//检验是否为自己的APP的模块
		$queryData = array(
		    'count' => -1,
		    'condition' => array(
		        'id' => $ids,
		        'uid' => $this->user['user_id']
		    )
		);
		$module_info = $this->api->show($queryData);
		if (!$module_info) $this->errorOutput(PARAM_WRONG);
		$validate_ids = array();
		foreach ($module_info as $module)
		{
		    $validate_ids[] = $module['id'];
		}
		$validate_ids = implode(',', $validate_ids);
		//删除绑定的界面对应的属性
		$this->api->delete('ui_value', array('module_id' => $validate_ids));
		//删除模块数据
		$result = $this->api->delete('app_module', array('id' => $validate_ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 排序操作
	 */
	public function sort()
	{
	    $sort = $this->input['sort'];
	    if (!$sort || !is_array($sort)) $this->errorOutput(PARAM_WRONG);
	    $condition = array('user_id' => $this->user['user_id']);
	    foreach ($sort as $k => $v)
	    {
	        $condition['id'] = intval($k);
	        $result = $this->api->update('app_module', array('sort_order' => intval($v)), $condition);
	    }
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$module_name = trim(urldecode($this->input['moduleName']));
		$english_name = trim(urldecode($this->input['englishModuleName']));
		$app_id = intval($this->input['appId']);
		if ($app_id <= 0 || !$module_name || !$english_name)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		//判断名称字符限制
		if (MODULE_NAME_LIMIT)
		{
		    $str = @iconv('', 'UTF-8', $module_name);
		    $len = mb_strlen($str, 'UTF-8');
		    if ($len > MODULE_NAME_LIMIT)
		    {
		        $this->errorOutput(CHAR_OVER);
		    }
		}
		if (MODULE_ENGLISH_LIMIT)
		{
		    $str = @iconv('', 'UTF-8', $english_name);
		    $len = mb_strlen($str, 'UTF-8');
		    if ($len > MODULE_ENGLISH_LIMIT)
		    {
		        $this->errorOutput(CHAR_OVER);
		    }
		}
		$data = array(
			'app_id' => $app_id,
			'name' => $module_name,
			'english_name' => $english_name
		);
		return $data;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$app_id = intval($this->input['appId']);
		return array(
			'app_id' => $app_id
		);
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

/**
 * 检测颜色值的有效性
 */
function checkColor($val)
{
    if (empty($val)) return false;
    if (strpos($val, '#') === false) $val = '#' . $val;
    if (!preg_match('/^#[0-9a-f]{6}|[0-9a-f]{3}$/i', $val)) return false;
    if (strlen($val) == 4)
    {
        $newStr = substr($val, 1);
        $len = strlen($newStr);
        $out = '#';
        for ($i = 0; $i < $len; $i++)
        {
            $color = substr($newStr, $i, 1);
            $out .= str_repeat($color, 2);
        }
        $val = $out;
    }
    return $val;
}

$out = new app_module();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>