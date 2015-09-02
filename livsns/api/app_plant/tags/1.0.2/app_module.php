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
include_once ROOT_PATH . 'lib/class/company.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_module extends appCommonFrm
{
	private $api;
	private $material;
	private $company;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appModule();
		$this->material = new material();
		$this->company = new company();
	}
	
	public function __destruct()
	{
		parent::__destruct();
		unset($this->api);
		unset($this->material);
		unset($this->company);
	}
	
	private function getSite()
	{
	    if ($this->user['user_id'] <= 0) return false;
	    $userInfo = $this->company->getSiteByUser($this->user['user_id']);
	    if (!$userInfo) return false;
	    return $userInfo;
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
			if (unserialize($appModule_info['press_pic']))
			{
				$appModule_info['press_pic'] = unserialize($appModule_info['press_pic']);
			}
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
	 * 验证app模块创建的个数是否达到上限
	 * @param integer $app_id
	 */
	private function limitNum($app_id, $limitNum = MODULE_LIMIT_NUM)
	{
	    $queryData = array(
	        'app_id' => $app_id
	    );
	    $nums = $this->api->count($queryData);
	    return $nums['total'] >= $limitNum ? true : false;
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
	    $data = $this->filter_data();
	    //验证是否为VIP用户
	    if ($this->settings['vip_user'])
	    {
	        $is_vip = in_array($this->user['user_name'], $this->settings['vip_user']) ? true : false;
	    }
	    if ($is_vip && $this->limitNum($data['app_id'], 15))
	    {
	        $this->errorOutput(OVER_LIMIT);
	    }
	    //验证创建的个数是否达到上限
	    if (!$is_vip && $this->limitNum($data['app_id']))
	    {
	        $this->errorOutput(OVER_LIMIT);
	    }
		//验证APP是否存在
		$queryData = array(
		    'id' => $data['app_id'],
		    'user_id' => $this->user['user_id'],
		    'del' => 0
		);
		$app_info = $this->api->detail('app_info', $queryData);
		if (!$app_info) $this->errorOutput(NO_APPID);
		/*
		//是否重名
		$check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		if ($data['english_name'])
		{
    		$check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
    		if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
		}
		*/
		//以模块名称创建站点栏目
		$userInfo = $this->getSite();
		if (!$userInfo) $this->errorOutput(NO_USER_ID);
		$publish = new publishconfig();
		$column_data = array(
		    'fast_add_column' => 1,
		    'column_name' => $data['name'],
			'site_id' => $userInfo['s_id']
		);
		$column_id = $publish->edit_column($column_data);
		if (!$column_id) $this->errorOutput(FAILED);
		if ($this->input['pic_id'])
		{
    		$pic_id = intval($this->input['pic_id']);
    		$pic_info = $this->material->get_material_by_ids($pic_id);
    		if (!$pic_info[0]) $this->errorOutput(PARAM_WRONG);
    		$data['pic'] = serialize($pic_info[0]);
    		if ($this->input['press_id'])
    		{
    		    $press_id = intval($this->input['press_id']);
    		    $press_pic = $this->material->get_material_by_ids($press_id);
    		    if (!$press_pic[0]) $this->errorOutput(PARAM_WRONG);
    		    $data['press_pic'] = serialize($press_pic[0]);
    		}
		}
		elseif ($this->input['pic_url'])
		{
		    $data['pic'] = trim(urldecode($this->input['pic_url']));
		    $data['press_pic'] = str_replace('/normal/', '/press/', $data['pic']);
		}
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$data['column_id'] = $column_id;
		$result = $this->api->create('app_module', $data);
		if ($result['id'])
		{
		    $this->api->update('app_module', array('sort_order' => $result['id']), array('id' => $result['id']));
		    $result['sort_order'] = $result['id'];
		    if ($pic_info && $pic_info[0])
		    {
		        $result['pic'] = $pic_info[0];
		    }
		    $this->addItem($result);
		}
		$this->output();
	}
	
	/**
	 * 更新数据
	 */
	public function update()
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
		    /*
			//是否重名
			$check = $this->api->verify(array('name' => $data['name'], 'app_id' => $data['app_id']));
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			*/
			$validate['name'] = $data['name'];
			//编辑栏目
    		$userInfo = $this->getSite();
    		if (!$userInfo) $this->errorOutput(NO_USER_ID);
    		$publish = new publishconfig();
    		$column_data = array(
    		    'fast_add_column' => 1,
    		    'column_name' => $data['name'],
    			'site_id' => $userInfo['s_id'],
    		    'column_id' => $appModule_info['column_id']
    		);
    		$column_id = $publish->edit_column($column_data);
    		if (!$column_id) $this->errorOutput(FAILED);
		}
		if ($data['english_name'] && $appModule_info['english_name'] != $data['english_name'])
		{
		    /*
		    //是否重名
			$check = $this->api->verify(array('english_name' => $data['english_name'], 'app_id' => $data['app_id']));
			if ($check > 0) $this->errorOutput(ENGLISH_EXISTS);
			*/
			$validate['english_name'] = $data['english_name'];
		}
		if ($this->input['pic_url'])
		{
		    $pic_url = trim(urldecode($this->input['pic_url']));
		    if ($appModule_info['pic'] != $pic_url)
		    {
		        $validate['pic'] = $pic_url;
		        $validate['press_pic'] = str_replace('/normal/', '/press/', $pic_url);
		    }
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
		if (isset($this->input['press_id']))
		{
		    $press_id = intval($this->input['press_id']);
		    $press_pic = $this->material->get_material_by_ids($press_id);
		    if (!$press_pic[0]) $this->errorOutput(PARAM_WRONG);
		    $press_pic = serialize($press_pic[0]);
		    if ($appModule_info['press_pic'] != $press_pic)
		    {
		        $validate['press_pic'] = $press_pic;
		    }
		}
		if ($appModule_info['is_sub'] != $data['is_sub'])
		{
			$validate['is_sub'] = $data['is_sub'];
		}
		if ($appModule_info['normal_color'] != $data['normal_color'])
		{
			$validate['normal_color'] = $data['normal_color'];
		}
		if ($appModule_info['normal_alpha'] != $data['normal_alpha'])
		{
			$validate['normal_alpha'] = $data['normal_alpha'];
		}
		if ($appModule_info['press_color'] != $data['press_color'])
		{
			$validate['press_color'] = $data['press_color'];
		}
		if ($appModule_info['press_alpha'] != $data['press_alpha'])
		{
			$validate['press_alpha'] = $data['press_alpha'];
		}
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
		}
		else
		{
    		if ($data['ui_id'] > 0 && $appModule_info['ui_id'] != $data['ui_id'])
    		{
    			$validate['ui_id'] = $data['ui_id'];
    		}
    		$validate['web_view'] = 0;
    		$validate['web_url'] = '';
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
		$validate_ids = $column_ids = array();
		foreach ($module_info as $module)
		{
		    $validate_ids[] = $module['id'];
		    $column_ids[] = $module['column_id'];
		}
		$validate_ids = implode(',', $validate_ids);
		//删除绑定的界面对应的属性
		$this->api->delete('ui_value', array('module_id' => $validate_ids));
		//删除栏目
		if ($column_ids)
		{
		    $parent_ids = $column_ids; //顶级栏目id
		    $column_ids = implode(',', $column_ids);
    		$publish = new publishconfig();
    		$column_info = $publish->get_column_by_ids('*', $column_ids);
    		if ($column_info)
    		{
    		    $childs_id = '';
    		    foreach ($column_info as $v)
    		    {
    		        $childs_id .= ','.$v['childs'];
    		    }
    		    //去除顶级栏目id
    		    $childs_id = trim($childs_id, ',');
    		    $childs_id = explode(',', $childs_id);
    		    $all_childs = array_diff($childs_id, $parent_ids);
    		    //删除子集栏目
    		    if ($all_childs)
    		    {
    		        $all_childs = implode(',', $all_childs);
    		        $result = $publish->delete_column($all_childs);
    		        if ($result != 'success') $this->errorOutput(FAILED);
    		    }
    		    //删除顶级栏目
		        $result = $publish->delete_column(implode(',', $parent_ids));
		        if ($result != 'success') $this->errorOutput(FAILED);
    		}
		}
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
	    //排序栏目
	    $module_info = $this->api->show(array('count' => -1));
	    if ($module_info)
	    {
	        $column_sort = array();
	        foreach ($module_info as $v)
	        {
	            if ($sort[$v['id']])
	            {
	                $column_sort[$v['column_id']] = $sort[$v['id']];
	            }
	        }
	        $publish = new publishconfig();
	        $ret = $publish->column_sort(json_encode($column_sort));
	        if ($ret != 'success') $this->errorOutput(COLUMN_SORT_WRONG);
	    }
	    $condition = array('user_id' => $this->user['user_id']);
	    foreach ($sort as $k => $v)
	    {
	        $condition['id'] = intval($k);
	        $updateData = array('sort_order' => intval($v));
	        $result = $this->api->update('app_module', $updateData, $condition);
	    }
	    $this->addItem($result);
	    $this->output();
	}
	
	/**
	 * 根据模块获取顶级栏目数据
	 */
	public function getColumnsByModule()
	{
	    $app_id = intval($this->input['app_id']);
	    $column_info = $this->api->getColumnsByModule($app_id);
	    if ($column_info)
	    {
	        foreach ($column_info as $column)
	        {
	            $this->addItem($column);
	        }
	    }
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
		$ui_id = intval($this->input['uiId']);
		$subscribe = isset($this->input['is_sub']) ? intval($this->input['is_sub']) : 1;
		$webview_id = intval($this->input['webviewId']);
		$webview_url = trim(urldecode($this->input['webviewUrl']));
		if ($app_id <= 0 || !$module_name)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'app_id' => $app_id,
			'name' => $module_name,
			'english_name' => $english_name,
		    'is_sub' => $subscribe
		);
		if (isset($this->input['normal_color']))
		{
		    $normal_color = trim(urldecode($this->input['normal_color']));
		    if ($normal_color && !checkColor($normal_color))
		    {
		        $this->errorOutput(COLOR_ERROR);
		    }
		    $data['normal_color'] = $normal_color;
		}
		if (isset($this->input['normal_alpha']))
		{
		    $normal_alpha = trim(urldecode($this->input['normal_alpha']));
		    if ($normal_alpha != '')
		    {
		        $normal_alpha = floatval($normal_alpha);
		        if ($normal_alpha < 0 || $normal_alpha > 1)
		        {
		            $this->errorOutput(PROPERTY_AUTH_FAIL);
		        }
		    }
		    $data['normal_alpha'] = $normal_alpha;
		}
		if (isset($this->input['press_color']))
		{
		    $press_color = trim(urldecode($this->input['press_color']));
		    if ($press_color && !checkColor($press_color))
		    {
		        $this->errorOutput(COLOR_ERROR);
		    }
		    $data['press_color'] = $press_color;
		}
		if (isset($this->input['press_alpha']))
		{
		    $press_alpha = trim(urldecode($this->input['press_alpha']));
		    if ($press_alpha != '')
		    {
		        $press_alpha = floatval($press_alpha);
    		    if ($press_alpha < 0 || $press_alpha > 1)
    		    {
    		        $this->errorOutput(PROPERTY_AUTH_FAIL);
    		    }
		    }
		    $data['press_alpha'] = $press_alpha;
		}
		if ($webview_id <= 0 && empty($webview_url) && $ui_id <= 0)
		{
		    $ui_id = DEFAULT_UI;
		}
		/*
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
		*/
		if ($ui_id > 0)
		{
		    $data['ui_id'] = $ui_id;
		}
		elseif ($webview_id > 0 || !empty($webview_url))
		{
		    $data['webview_id'] = $webview_id;
		    $data['webview_url'] = $webview_url;
		}
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