<?php
define('ROOT_DIR', '../../');
define('WITHOUT_DB', true);
require(ROOT_DIR . 'global.php');
require(ROOT_DIR . 'frm/configuare_frm.php');
require(ROOT_DIR  . 'lib/class/curl.class.php');
class configuare extends configuareFrm
{	
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	/**
	* 配置更新前处理
	*
	*/
	protected function settings_process()
	{
		if($this->settings['schedule_control_wowza']['is_wowza'] && ($this->settings['App_schedule'] || $this->settings['App_live_control']))
		{
			if(!$this->settings['schedule_control_wowza']['is_wowza'])
			{
				$this->input['base']['schedule_control_wowza'] = array();
				//$this->errorOutput('请修改配置选项schedule_control_wowza＝>is_wowza以启用播控');
			}
			else
			{
				$this->input['base']['schedule_control_wowza']['is_wowza'] = 1;
			
				if(!$this->input['base']['schedule_control_wowza']['host'])
				{
					$this->errorOutput('请设置wowza主机');
				}
				if(!$this->input['base']['schedule_control_wowza']['inputdir'])
				{
					$this->errorOutput('请设置wowza目录');
				}
			}
			$curl = new curl();
			$curl->initPostData();
			$curl->setSubmitType('post');
			$curl->addRequestData('base[server_info][host]', $this->input['base']['schedule_control_wowza']['host']);
			$curl->addRequestData('base[server_info][input_dir]', $this->input['base']['schedule_control_wowza']['inputdir']);
			$curl->addRequestData('a', 'doset');
			//
			if($this->settings['App_schedule'])
			{
				//$this->errorOutput(var_export($this->settings['App_schedule'],1));
				$curl->setUrlHost($this->settings['App_schedule']['host'], $this->settings['App_schedule']['dir']);
				$ret = $curl->request('configuare.php');
				if(!$ret['success'])
				{
					$this->errorOutput("同步串联单配置失败");
				}	
			}
			if($this->settings['App_live_control'])
			{
				$curl->setUrlHost($this->settings['App_live_control']['host'],$this->settings['App_live_control']['dir']);
				$ret = $curl->request('configuare.php');
				if(!$ret['success'])
				{
					$this->errorOutput("同步播控配置失败");
				}
			}
		}
		$max_time_shift = intval($this->input['base']['max_time_shift']);
		$this->input['base']['max_time_shift'] = $max_time_shift > 0 ? $max_time_shift : 168;
	}
}
$module = 'configuare';
$$module = new $module();  

$func = $_INPUT['a'];
if (!method_exists($$module, $func))
{
	$func = 'show';	
}
$$module->$func();
?>