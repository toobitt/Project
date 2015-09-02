<?php
require_once('global.php');
define('MOD_UNIQUEID','block_node');//模块标识
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
define('SCRIPT_NAME', 'block_node');
class block_node extends nodeFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->pub_config= new publishconfig();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		$fid = ($this->input['fid']);
		include_once(CUR_CONF_PATH.'lib/common.php');
		if(empty($fid))
		{
			$sites = $this->pub_config->get_site(' id,site_name ');
			foreach($sites as $k=>$v)	
			{
				$m = array('id'=>'site'.$v['id'].$this->settings['separator'].$v['site_name'],"name"=>$v['site_name'],"fid"=>0,"depth"=>1);
				//获取页面类型
				$page_type = common::get_page_manage($v['id']);
				if(empty($page_type))
				{
					$m['is_last'] = 1;
				}
				$this->addItem($m);
			}
		}
		else
		{
			if(strstr($fid,"site")!==false)
			{
				//点击的站点
				$site_id = str_replace('site','',$fid);
				$get_page = explode($this->settings['separator'],$site_id);
				$page_type = common::get_page_manage($get_page[0]);
				foreach($page_type as $k=>$v)
				{
					$m = array('id'=>'page_id'.$v['id'].$this->settings['separator'].$v['title'],"name"=>$v['title'],"fid"=>'page_id'.$v['id'].$this->settings['separator'].$v['title'],"depth"=>1);
					$page_data = common::get_page_data($v['id'],0,1);
					if(empty($page_data['page_data']))
					{
						$m['is_last'] = 1;
					}
					$this->addItem($m);
				}
			}
			else if(strstr($fid,"page_id")!==false)
			{
				//点击的页面类型
				$page_id = str_replace('page_id','',$fid);
				$get_page = explode($this->settings['separator'],$page_id);
				$page_data = common::get_page_data($get_page[0],0,100);
				foreach($page_data['page_data'] as $k=>$v)
				{
					$m_id = 'page_data_id'.$page_data['page_info']['id'].$this->settings['separator'].$v[$page_data['page_info']['field']].$this->settings['separator'].$v[$page_data['page_info']['name_field']];
					$m = array('id'=>$m_id,"name"=>$v[$page_data['page_info']['name_field']],"fid"=>$m_id,"depth"=>1);
					$m['is_last'] = $v[$page_data['page_info']['last_field']];
					$this->addItem($m);
				}
			}
			else if(strstr($fid,"page_data_id")!==false)
			{
				//点击的页面数据
				$page_data_id = str_replace('page_data_id','',$fid);
				$get_page = explode($this->settings['separator'],$page_data_id);
				$page_data = common::get_page_data($get_page[0],'','',$get_page[1]);
				foreach($page_data['page_data'] as $k=>$v)
				{
					$m_id = 'page_data_id'.$page_data['page_info']['id'].$this->settings['separator'].$v[$page_data['page_info']['field']].$this->settings['separator'].$v[$page_data['page_info']['name_field']];
					$m = array('id'=>$m_id,"name"=>$v[$page_data['page_info']['name_field']],"fid"=>$m_id,"depth"=>1);
					$m['is_last'] = $v[$page_data['page_info']['last_field']];
					$this->addItem($m);
				}
			}
			
		}
		$this->output();
	}
	
}
include(ROOT_PATH . 'excute.php');
?>
