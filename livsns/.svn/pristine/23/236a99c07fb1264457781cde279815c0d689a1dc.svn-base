<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: company.php 7586 2013-04-19 09:40:56Z yaojian $
***************************************************************************/
require_once './global.php';
include_once CUR_CONF_PATH . 'lib/company.class.php';
define('MOD_UNIQUEID', 'company');  //模块标识

class companyApi extends adminReadBase
{
	private $company;
	
	public function __construct()
	{
		parent::__construct();
		$this->company = new company();
	}

	public function __destruct()
	{
		parent::__destruct();
		unset($this->company);
	}
	
	public function index() {}
	
	/**
	 * 获取企业信息
	 */
	public function show()
	{
		$offset = isset($this->input['offset']) ? intval($this->input['offset']) : 0;
		$count = isset($this->input['count']) ? intval($this->input['count']) : 20;
		$condition = $this->filter_data();
		$data = array(
			'offset' => $offset,
			'count' => $count,
			'condition' => $condition
		);
		$company_info = $this->company->show($data);
		$this->setXmlNode('company_info', 'company');
		if ($company_info) {
			foreach ($company_info as $company)
			{
				$this->addItem($company);
			}
		}
		$this->output();
	}
	
	/**
	 * 获取企业总数
	 */
	public function count()
	{
		$condition = $this->filter_data();
		$info = $this->company->count($condition);
		echo json_encode($info);
	}
	
	/**
	 * 获取单个企业信息
	 */
	public function detail()
	{
		$id = isset($this->input['id']) ? intval($this->input['id']) : 0;
		if ($id <= 0) $this->errorOutput(PARAM_WRONG);
		$company_info = $this->company->get_one_company($id);
		$this->addItem($company_info);
		$this->output();
	}
	
	/**
	 * 获取未使用的素材
	 */
	public function get_material()
	{
		$pic_info = $this->company->get_pic(array(
			'company_id' => 0,
			'user_id' => intval($this->user['user_id']),
			'state' => 1
		));
		$this->setXmlNode('pic_info', 'pic');
		if ($pic_info)
		{
			foreach ($pic_info as $pic)
			{
				$this->addItem($pic);
			}
		}
		$this->output();
	}
	
	/**
	 * 过滤数据
	 */
	private function filter_data()
	{
		$name = isset($this->input['k']) ? trim(urldecode($this->input['k'])) : '';
		$trade_id = isset($this->input['t_id']) ? intval($this->input['t_id']) : '';
		$grade_id = isset($this->input['g_id']) ? intval($this->input['g_id']) : '';
		$time = isset($this->input['date_search']) ? intval($this->input['date_search']) : '';
		$start_time = trim($this->input['start_time']);
		$end_time = trim($this->input['end_time']);
		$state = isset($this->input['status']) ? intval($this->input['status']) : '';
		$data = array();
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$data['uid'] = intval($this->user['user_id']);
		}
		if (!empty($name)) $data['keyword'] = $name;
		if ($trade_id) $data['trade_id'] = $trade_id;
		if ($grade_id) $data['grade_id'] = $grade_id;
		if ($start_time) $data['start_time'] = $start_time;
		if ($end_time) $data['end_time'] = $end_time;
		if ($time) $data['date_search'] = $time;
		if ($state) $data['state'] = $state;
		return $data;
	}
}

$out = new companyApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>