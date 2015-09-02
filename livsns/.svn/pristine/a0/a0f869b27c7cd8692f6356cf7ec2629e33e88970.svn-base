<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id$
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/appTemplate.class.php';
include_once ROOT_PATH . 'lib/class/material.class.php';
define('MOD_UNIQUEID', 'app_plant');

class app_template extends appCommonFrm
{
	private $api;
	
	public function __construct()
	{
		parent::__construct();
		$this->api = new appTemplate();
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
		$appTemplate_info = $this->api->show($data);
		$this->setXmlNode('appTemplate_info', 'template');
		if ($appTemplate_info)
		{
			foreach ($appTemplate_info as $template)
			{
				$this->addItem($template);
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
		$appTemplate_info = $this->api->detail('app_template', $data);
		if ($appTemplate_info)
		{
			if (unserialize($appTemplate_info['pic']))
			{
				$appTemplate_info['pic'] = unserialize($appTemplate_info['pic']);
			}
			if (unserialize($appTemplate_info['module_pic_zip']))
			{
				$appTemplate_info['module_pic_zip'] = unserialize($appTemplate_info['module_pic_zip']);
			}
			//获取对应的示例图片
			$example_pic = $this->api->get_pic($id);
			if ($example_pic) $appTemplate_info['example_pic'] = $example_pic[$id];
			//获取对应的属性
			$attr_info = $this->api->get_attribute($id);
			if ($attr_info) $appTemplate_info['attr'] = $attr_info;
		}
		
		$this->addItem($appTemplate_info);
		$this->output();
	}
	
	/**
	 * 创建数据
	 */
	public function create()
	{
		$data = $this->filter_data();
		//是否重名
		$check = $this->api->verify(array('name' => $data['name']));
		if ($check > 0) $this->errorOutput(NAME_EXISTS);
		if ($_FILES['template_pic'])
		{
			$_FILES['Filedata'] = $_FILES['template_pic'];
			unset($_FILES['template_pic']);
			$data['pic'] = $this->upload();
		}
		if ($_FILES['module_pic'])
		{
		    if ($_FILES['module_pic']['type'] != 'application/zip')
		    {
		        $this->errorOutput(FILE_TYPE_ERROR);
		    }
		    $_FILES['Filedata'] = $_FILES['module_pic'];
		    unset($_FILES['module_pic']);
		    $validate['module_pic_zip'] = $this->upload();
		}
		if ($data['attr_ids']) $attr_ids = $data['attr_ids'];
		unset($data['attr_ids']);
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$data['org_id'] = $this->user['org_id'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		$result = $this->api->create('app_template', $data);
		if ($_FILES['example_pic'])
		{
			$insertData = array('temp_id' => $result['id']);
			foreach ($_FILES['example_pic']['name'] as $k => $file)
			{
				$_FILES['Filedata'] = array(
					'name' => $file,
					'type' => $_FILES['example_pic']['type'][$k],
					'tmp_name' => $_FILES['example_pic']['tmp_name'][$k],
					'error' => $_FILES['example_pic']['error'][$k],
					'size' => $_FILES['example_pic']['size'][$k]
				);
				$insertData['info'] = $this->upload();
				$this->api->create('picture_template', $insertData);
			}
		}
		//绑定属性
		if ($attr_ids) $this->set_attr($result['id'], $attr_ids);
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 更新数据
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$appTemplate_info = $this->api->detail('app_template', array('id' => $id));
		if (!$appTemplate_info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($appTemplate_info['name'] != $data['name'])
		{
			//是否重名
			$check = $this->api->verify(array('name' => $data['name']));
			if ($check > 0) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($appTemplate_info['mark'] != $data['mark'])
		{
			$validate['mark'] = $data['mark'];
		}
		if ($appTemplate_info['brief'] != $data['brief'])
		{
			$validate['brief'] = $data['brief'];
		}
		if ($_FILES['template_pic'])
		{
			$_FILES['Filedata'] = $_FILES['template_pic'];
			unset($_FILES['template_pic']);
			$validate['pic'] = $this->upload();
		}
		if ($_FILES['module_pic'])
		{
		    if ($_FILES['module_pic']['type'] != 'application/zip')
		    {
		        $this->errorOutput(FILE_TYPE_ERROR);
		    }
		    $_FILES['Filedata'] = $_FILES['module_pic'];
		    unset($_FILES['module_pic']);
		    $validate['module_pic_zip'] = $this->upload();
		}
		//删除示例图片
		$drop_ids = trim(urldecode($this->input['drop_ids']));
		$id_arr = explode(',', $drop_ids);
		$id_arr = array_filter($id_arr, 'filter_arr');
		if ($id_arr)
		{
			$idStr = implode(',', $id_arr);
			$drop_ids = count($id_arr) > 1 ? $idStr : intval($idStr);
			$this->api->delete('picture_template', array('id' => $drop_ids));
		}
		//添加示例图片
		if ($_FILES['example_pic'])
		{
			$insertData = array('temp_id' => $id);
			foreach ($_FILES['example_pic']['name'] as $k => $file)
			{
				$_FILES['Filedata'] = array(
					'name' => $file,
					'type' => $_FILES['example_pic']['type'][$k],
					'tmp_name' => $_FILES['example_pic']['tmp_name'][$k],
					'error' => $_FILES['example_pic']['error'][$k],
					'size' => $_FILES['example_pic']['size'][$k]
				);
				$insertData['info'] = $this->upload();
				$this->api->create('picture_template', $insertData);
			}
		}
		if ($validate || $data['attr_ids'])
		{
			if ($validate)
			{
				$result = $this->api->update('app_template', $validate, array('id' => $id));
			}
			if ($data['attr_ids'])
			{
				$result = $this->set_attr($id, $data['attr_ids']);
			}
		}
		else
		{
			$result = true;
		}
		$this->addItem($result);
		$this->output();
	}
	
	//绑定属性
	private function set_attr($temp_id, $attr_ids)
	{
		$temp_info = $this->api->detail('app_template', array('id' => $temp_id));
		if (!$temp_info) $this->errorOutput(PARAM_WRONG);
		include_once CUR_CONF_PATH . 'lib/appAttr.class.php';
		$attr = new appAttr();
		$info = $attr->show(array('count' => -1, 'condition' => array('id' => $attr_ids)));
		if (!$info)
		{
			$attr_ids = array();
		}
		else
		{
			$ids = array();
			foreach ($info as $v)
			{
				$ids[] = $v['id'];
			}
			$attr_ids = $ids;
		}
		$attr_info = $this->api->get_attribute($temp_id);
		if ($attr_info)
		{
			$original = array();
			foreach ($attr_info as $attr)
			{
				$original[] = $attr['attr_id'];
			}
			$delete_ids = array_diff($original, $attr_ids);
			$insert_ids = array_diff($attr_ids, $original);
		}
		else
		{
			$insert_ids = $attr_ids;
		}
		if ($delete_ids)
		{
			$data = array(
				'temp_id' => $temp_id,
				'attr_id' => implode(',', $delete_ids)
			);
			$result = $this->api->delete('temp_attr', $data);
		}
		if ($insert_ids)
		{
			foreach ($insert_ids as $id)
			{
				$data = array(
					'temp_id' => $temp_id,
					'attr_id' => $id
				);
				$result = $this->api->create('temp_attr', $data);
			}
		}
		return $result;
	}
	
	/**
	 * (图片|附件)上传
	 */
	public function upload()
	{
		$material = new material();
		$result = $material->addMaterial($_FILES);
		if (!$result) $this->errorOutput(PARAM_WRONG);
		return serialize($result);
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
		$temp_info = $this->api->show(array('count' => -1, 'condition' => array('id' => $ids)));
		if (!$temp_info) $this->errorOutput(PARAM_WRONG);
		$validate = array();
		foreach ($temp_info as $temp)
		{
			$validate[$temp['id']] = $temp['id'];
		}
		$v_ids = implode(',', $validate);
		$app_info = $this->api->detail('app_info', array('temp_id' => $v_ids));
		if ($app_info) $this->errorOutput(PARAM_WRONG);
		//删除模板关联的示例图片
		$this->api->delete('picture_template', array('temp_id' => $v_ids));
		//删除模板对应的属性
		$this->api->delete('temp_attr', array('temp_id' => $v_ids));
		//删除模板
		$result = $this->api->delete('app_template', array('id' => $v_ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$temp_name = trim(urldecode($this->input['temp_name']));
		$temp_mark = trim(urldecode($this->input['temp_mark']));
		$temp_brief = trim(urldecode($this->input['temp_brief']));
		$attr_ids = $this->input['attribute_ids'];
		if (empty($temp_name) || empty($temp_mark))
		{
			$this->errorOutput(PARAM_WRONG);
		}
		if ($attr_ids)
		{
			$id_arr = array_filter($attr_ids, 'filter_arr');
			if (!$id_arr) $this->errorOutput(PARAM_WRONG);
			$attr_ids = implode(',', $id_arr);
		}
		$data = array(
			'name' => $temp_name,
			'mark' => $temp_mark,
			'brief' => $temp_brief,
			'attr_ids' => $attr_ids
		);
		return $data;
	}
	
	/**
	 * 查询条件
	 */
	private function condition()
	{
		$keyword = trim(urldecode($this->input['k']));
		return array(
			'keyword' => $keyword
		);
	}
	
	public function editModulePic()
	{
	    $t_id = intval($this->input['tid']);
	    $template_info = $this->api->detail('app_template', array('id' => $t_id));
	    if (!$template_info) $this->errorOutput(PARAM_WRONG);
	    $dir = CUR_CONF_PATH . 'cache';
	    if (!hg_mkdir($dir)) $this->errorOutput('检查目录可写权限');
	    $path = $dir . '/' . $template_info['mark'];
	    if (!file_exists($path))
	    {
	        if (!hg_mkdir($path)) $this->errorOutput('检查目录可写权限');
	        if (!unserialize($template_info['module_pic_zip']))
	        {
	            $this->errorOutput('数据有误');
	        }
	        //生成模块图标文件
	        $module_pic = unserialize($template_info['module_pic_zip']);
	        if (!$this->generate($module_pic['url'], $path))
	        {
	            $this->errorOutput('生成文件有误');
	        }
	    }
	    //输出文件进行编辑
	}
	
	private function generate($url, $dir)
	{
	    $content = file_get_contents($url);
	    $filename = $dir . '/' . time() . '.zip';
	    if (!hg_file_write($filename, $content))
	    {
	        return false;
	    }
	    //解压缩文件并删除压缩包文件
	    
	}
}

function filter_arr(&$value)
{
	$value = intval($value);
	return $value <= 0 ? false : true;
}

$out = new app_template();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out->$action();
?>