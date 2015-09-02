<?php
require_once('global.php');
define('MOD_UNIQUEID', 'config');
define('SCRIPT_NAME', 'config');
require_once(CUR_CONF_PATH . 'lib/curd.class.php');
class config extends adminBase
{
	private $curd = null;
	public function __construct()
	{
		parent::__construct();
		$this->curd = new curd('template_config');
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function show(){
		
		$data = $this->curd->show();
		
		if($data)
		{
			foreach($data as $key=>$val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
		
	}
	public function get_version_config()
	{
		$this->input['type'] = 'version';
		$data = $this->curd->show('*', $this->get_conditions());
		$output = array();
		if($data)
		{
			foreach($data as $val)
			{
				$output[$val['key']] = $val['value'];
			}
		}
		exit(json_encode($output));
	}
	public function get_color_config()
	{
		$this->input['type'] = 'color';
		$data = $this->curd->show('*', $this->get_conditions());
		$output = array();
		if($data)
		{
			foreach($data as $val)
			{
				$output[$val['key']] = $val['value'];
			}
		}
		exit(json_encode($output));
	}
	public function get_style_config()
	{
		$this->input['type'] = 'style';
		$data = $this->curd->show('*', $this->get_conditions());
		$output = array();
		if($data)
		{
			foreach($data as $val)
			{
				$output[$val['key']] = $val['value'];
			}
		}
		exit(json_encode($output));
	}
	public function get_use_config()
	{
		$this->input['type'] = 'use';
		$data = $this->curd->show('*', $this->get_conditions());
		$output = array();
		if($data)
		{
			foreach($data as $val)
			{
				$output[$val['key']] = $val['value'];
			}
		}
		exit(json_encode($output));
	}
	public function detail()
	{
		$id = $this->input['id'];
		$data = array();
		if($id)
		{
			$data = $this->curd->detail($id);
		}
		$this->addItem($data);
		$this->output();
	}
	public function count()
	{
		$total = $this->curd->count();
		exit(json_encode($total));
	}
	public function get_conditions()
	{
		$conditions = ' and status = 1 ';
		if($this->input['type'] && $this->settings['config_type'][$this->input['type']])
		{
			$conditions .= ' AND type = "'.urldecode($this->input['type']).'"';
		}
		return $conditions;
	}
	
}
include ROOT_PATH . 'excute.php';
?>