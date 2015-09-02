<?php
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."conf/config.php");
require_once(CUR_CONF_PATH . 'core/message_module.dat.php');
class MessSet extends BaseFrm
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function show()
	{
		$var_name = urldecode($this->input['var_name']);
		$app_bundle = urldecode($this->input['bundle_id']);
		$module_bundle = urldecode($this->input['module_id']);
		//查找系统配置
		$sql = "select * from " . DB_PREFIX . "settings where 1 AND var_name='message_form_set'";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['type'] !=2)
			{
				$row['value'] = unserialize($row['value']);
			}
			$row['bundle_id'] = $app_bundle;
			$row['module_id'] = $module_bundle;
			$ret[$row['var_name']] = $row;
		}
		//查找应用自己的配置
		$sql = "SELECT * FROM " .	 DB_PREFIX . "app_settings WHERE bundle_id = '" . $app_bundle . "' AND module_id ='" . $module_bundle ."' AND var_name='message_form_set'";
		$q = $this->db->query($sql);
		//如果没有直接调用系统配置
		if(!$this->db->num_rows($q))
		{
			$this->addItem($ret);
			$this->output();
		}
		else//应用有自己的配置,使用系统自己的配置
		{
			while($row = $this->db->fetch_array($q))
			{

				if($row['type'] != 2)
				{
					$row['value'] = unserialize($row['value']);
				}
				$m_set = $row['value'];
			}
			/**
			*$m_set
			*array (
			  'state' => '1',
			  'allow_reply' => '1',
			  'vote' => '1',
			  'allow_quoted' => '0',
			  'display' => '0',
			  'max_word' => '100',
			)
			**/
			if($ret)//替换系统配置中应用已经设置过的项
			{
				foreach($ret['message_form_set']['value'] as $key=>$val)
				{
					foreach($val as $k=>$v)
					{
						if(isset($m_set[$k]))
						{
							$ret['message_form_set']['value'][$key][$k]['def_val'] = $m_set[$k];
						}
					}
				}
			}
			$this->addItem($ret);
			$this->output();
		}
	
	}

	function get_condition()
	{
		$condition = '';

		if($this->input['_id'])
		{
			$condition .= ' AND md.id in (6,'.intval($this->input['_id']).')';//查询具体模块
		}

		if($this->input['_type'])
		{
			if(intval($this->input['_type'])==2 && !$this->input['_id'] && !$this->input['_mid'])//点击模块
			{
				$condition .= ' AND md.id in (5,6)';
			}
			else if(intval($this->input['_type'])==1 && !$this->input['_id'])//点击全局
			{
				$condition .= ' AND md.id = 6';
			}
			else if(intval($this->input['_type']) == 2 && $this->input['_mid'])//cms调取模块配置
			{
				$condition .= ' AND md.mid in (0,' .$this->input['_mid'].')';
			}
		}
		else
		{
			$condition .= ' AND md.fid = 1 AND  md.id = 6';//默认全局
		}
		return $condition;
	}

	function test()
	{
		$this->addItem($this->input['_id']);
		$this->output();
	}
}
$output = new MessSet();
if(!method_exists($output,$_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>