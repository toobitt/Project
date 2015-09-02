<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: company_update.php 4728 2013-04-19 10:38:02Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/company.class.php';
include_once ROOT_PATH . 'lib/class/publishconfig.class.php';
include_once ROOT_PATH . 'lib/class/auth.class.php';
define('MOD_UNIQUEID', 'company');  //模块标识

class companyUpdateApi extends adminUpdateBase
{
	private $company;
	private $site;
	private $auth;
	
	public function __construct()
	{
		parent::__construct();
		$this->company = new company();
		$this->site = new publishconfig();
		$this->auth = new Auth();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->company);
		unset($this->site);
		unset($this->auth);
	}
	
	/**
	 * 创建企业信息
	 */
	public function create()
	{
		$data = $this->filter_data();
		//验证名称是否重复
		$checkResult = $this->company->verify(array('name' => $data['name']));
		if ($checkResult) $this->errorOutput(NAME_EXISTS);
		//验证logo
		$logo_info = $this->company->detail('material', array('id' => $data['logo'], 'state' => 1));
		if (!$logo_info) $this->errorOutput(PARAM_WRONG);
		//验证行业
		$trade_info = $this->company->detail('trade', array('id' => $data['trade_id']));
		if (!$trade_info) $this->errorOutput(PARAM_WRONG);
		//验证等级
		$grade_info = $this->company->detail('grade', array('id' => $data['grade_id']));
		if (!$grade_info) $this->errorOutput(PARAM_WRONG);
		$data['user_id'] = $this->user['user_id'];
		$data['appid'] = $this->user['appid'];
		$data['appname'] = $this->user['display_name'];
		$data['create_time'] = TIMENOW;
		$data['ip'] = hg_getip();
		//创建站点
		$siteData = array(
			'site_name' => $data['name'],
			'site_keywords' => $data['keywords'],
			'content' => $data['intro']
		);
		$site_info = $this->site->edit_site($siteData);
		if (!$site_info) $this->errorOutput(FAILED);
		$data['site_id'] = $site_info['site_id'];
		//创建组织机构
		$orgData = array(
			'name' => $data['name'],
			'brief' => $data['intro']
		);
		$org_info = $this->auth->create_org($orgData);
		if (!$org_info) $this->errorOutput(FAILED);
		$data['org_id'] = $org_info['id'];
		$company_info = $this->company->create('company', $data);
		//更新附件信息
		$this->company->update(
			'material',
			array('company_id' => $company_info['id']),
			array('id' => $data['logo'])
		);
		$this->addItem($company_info);
		$this->output();
	}
	
	/**
	 * 修改企业信息
	 */
	public function update()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$info = $this->company->detail('company', array('id' => $id));
		if (!$info) $this->errorOutput(PARAM_WRONG);
		$data = $this->filter_data();
		$validate = array();
		if ($data['name'] != $info['name'])
		{
			//验证名称是否重复
			$checkResult = $this->company->verify(array('name' => $data['name']));
			if ($checkResult) $this->errorOutput(NAME_EXISTS);
			$validate['name'] = $data['name'];
		}
		if ($data['logo'] != $info['logo'])
		{
			//验证logo
			$logo_info = $this->company->detail('material', array('id' => $data['logo'], 'state' => 1));
			if (!$logo_info) $this->errorOutput(PARAM_WRONG);
			$validate['logo'] = $data['logo'];
		}
		if ($data['trade_id'] != $info['trade_id'])
		{
			//验证行业
			$trade_info = $this->company->detail('trade', array('id' => $data['trade_id']));
			if (!$trade_info) $this->errorOutput(PARAM_WRONG);
			$validate['trade_id'] = $data['trade_id'];
		}
		if ($data['grade_id'] != $info['grade_id'])
		{
			//验证等级
			$grade_info = $this->company->detail('grade', array('id' => $data['grade_id']));
			if (!$grade_info) $this->errorOutput(PARAM_WRONG);
			$validate['grade_id'] = $data['grade_id'];
		}
		if ($data['keywords'] != $info['keywords'])
		{
			$validate['keywords'] = $data['keywords'];
		}
		if ($data['intro'] != $info['intro'])
		{
			$validate['intro'] = $data['intro'];
		}
		if ($data['province'] != $info['province'])
		{
			$validate['province'] = $data['province'];
		}
		if ($data['city'] != $info['city'])
		{
			$validate['city'] = $data['city'];
		}
		if ($data['area'] != $info['area'])
		{
			$validate['area'] = $data['area'];
		}
		if ($data['address'] != $info['address'])
		{
			$validate['address'] = $data['address'];
		}
		$result = $this->company->update('company', $validate, array('id' => $id));
		if ($validate['logo'] && $result)
		{
			//更新素材
			$this->company->update(
				'material',
				array('company_id' => $id),
				array('id' => $validate['logo'])
			);
			$this->company->update(
				'material',
				array('company_id' => 0),
				array('id' => $info['logo'])
			);
		}
		//更新站点
		$siteData = array();
		if ($validate['name']) $siteData['name'] = $validate['name'];
		if ($validate['keywords']) $siteData['keywords'] = $validate['keywords'];
		if ($validate['intro']) $siteData['intro'] = $validate['intro'];
		if ($siteData)
		{
			$siteData['site_id'] = $info['site_id'];
			$this->site->edit_site($siteData);
		}
		//更新组织机构
		$orgData = array();
		if ($validate['name']) $orgData['name'] = $validate['name'];
		if ($validate['intro']) $orgData['brief'] = $validate['intro'];
		if ($orgData)
		{
			$orgData['id'] = $info['org_id'];
			$this->auth->update_org($orgData);
		}
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 图片上传
	 */
	public function upload()
	{
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$material = new material();
		$material_info = $material->addMaterial($_FILES);
		if (!$material_info) $this->errorOutput(PARAM_WRONG);
		$data = array(
			'm_id' => $material_info['id'],
			'host' => $material_info['host'],
			'dir' => $material_info['dir'],
			'filepath' => $material_info['filepath'],
			'filename' => $material_info['filename'],
			'type' => $material_info['type'],
			'filesize' => $material_info['filesize'],
			'user_id' => $this->user['user_id'],
			'org_id' => $this->user['org_id'],
			'appid' => $this->user['appid'],
			'appname' => $this->user['display_name'],
			'create_time' => TIMENOW,
			'ip' => hg_getip()
		);
		$result = $this->company->create('material', $data);
		$return = array(
			'id' => $result['id'],
			'host' => $result['host'],
			'dir' => $result['dir'],
			'filepath' => $result['filepath'],
			'filename' => $result['filename']
		);
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 删除图片
	 */
	public function dropImg()
	{
		$id = intval($this->input['id']);
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$info = $this->company->detail('material', array('id' => $id));
		if (!$info) $this->errorOutput(PARAM_WRONG);
		include_once ROOT_PATH . 'lib/class/material.class.php';
		$material = new material();
		$material->delMaterialById($info['m_id']);
		$result = $this->company->delete('material', array('id' => $id));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 删除企业信息(逻辑删除)
	 */
	public function delete()
	{
		$ids = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : '';
		if (empty($ids)) $this->errorOutput(PARAM_WRONG);
		$ids_arr = explode(',', $ids);
		$ids_arr = array_filter($ids_arr);
		if (!$ids_arr) $this->errorOutput(PARAM_WRONG);
		$ids = count($ids_arr) == 1 ? intval(current($ids_arr)) : implode(',', $ids_arr);
		$v_ids = $ids_data = array();
		$companyInfo = $this->company->show(array('count' => -1, 'condition' => array('id' => $ids)));
		if (!$companyInfo) $this->errorOutput(PARAM_WRONG);
		foreach ($companyInfo as $company)
		{
			$v_ids[$company['id']] = $company['id'];
			$ids_data[$company['id']] = $company;
		}
		$v_ids = count($v_ids) == 1 ? intval(current($v_ids)) : implode(',', $v_ids);
		foreach ($ids_data as $v)
		{
			//删除站点
			$this->site->delete_site($v['site_id']);
			//删除组织机构
			$this->auth->delete_org($v['org_id']);
		}
		//删除附件信息
		$this->company->update('material', array('state' => 0), array('company_id' => $v_ids));
		//删除企业
		$result = $this->company->update('company', array('is_drop' => 1), array('id' => $v_ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 审核操作
	 */
	public function audit()
	{
		$ids = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : '';
		if (empty($ids)) $this->errorOutput(PARAM_WRONG);
		$ids_arr = explode(',', $ids);
		$ids_arr = array_filter($ids_arr);
		if (!$ids_arr) $this->errorOutput(PARAM_WRONG);
		$ids = count($ids_arr) == 1 ? intval(current($ids_arr)) : implode(',', $ids_arr);
		$result = $this->company->update('company', array('state' => 1), array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 打回操作
	 */
	public function back()
	{
		$ids = isset($this->input['id']) ? trim(urldecode($this->input['id'])) : '';
		if (empty($ids)) $this->errorOutput(PARAM_WRONG);
		$ids_arr = explode(',', $ids);
		$ids_arr = array_filter($ids_arr);
		if (!$ids_arr) $this->errorOutput(PARAM_WRONG);
		$ids = count($ids_arr) == 1 ? intval(current($ids_arr)) : implode(',', $ids_arr);
		$result = $this->company->update('company', array('state' => 2), array('id' => $ids));
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 方法不存在的时候调用的方法
	 */
	public function none()
	{
		$this->errorOutput('调用的方法不存在');
	}
	
	/**
	 * 处理提交的数据
	 */
	private function filter_data()
	{
		$name = trim(urldecode($this->input['c_name']));
		$logo = intval($this->input['c_logo']);
		$keywords = trim(urldecode($this->input['c_keywords']));
		$intro = trim(urldecode($this->input['c_intro']));
		$trade_id = intval($this->input['tradeId']);
		$grade_id = intval($this->input['gradeId']);
		$province = trim(urldecode($this->input['c_province']));
		$city = trim(urldecode($this->input['c_city']));
		$area = trim(urldecode($this->input['c_area']));
		$address = trim(urldecode($this->input['c_address']));
		if (empty($name) || $logo <= 0 || $trade_id <= 0 || $grade_id <= 0)
		{
			$this->errorOutput(PARAM_WRONG);
		}
		$data = array(
			'name' => $name,
			'logo' => $logo,
			'trade_id' => $trade_id,
			'grade_id' => $grade_id,
			'keywords' => $keywords ? $keywords : '',
			'intro' => $intro ? $intro : '',
			'province' => $province ? $province : '',
			'city' => $city ? $city : '',
			'area' => $area ? $area : '',
			'address' => $address ? $address : ''
		);
		return $data;
	}
	
	public function sort() {}
	public function publish() {}
}

$out = new companyUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'none';
}
$out->$action();
?>