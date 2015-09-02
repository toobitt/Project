<?php
require('global.php');
define('MOD_UNIQUEID','cell');
class cellApi extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'=>'管理',
		'_node'=>array(
			'name'=>'栏目',
			'node_uniqueid'=>'cloumn_node',
			),
		);		
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/common.php');
		include_once(CUR_CONF_PATH . 'lib/cell.class.php');
		$this->obj = new cell();
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->pub = new publishconfig();
		include_once(ROOT_PATH . 'lib/class/block.class.php');
		$this->block = new block();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index(){}
	/**
	 * 查看内容类型
	 * @name show
	 * @access public
	 */
	public function show()
	{
		$site_id = intval($this->input['site_id']) ? intval($this->input['site_id']) : 1;
		$page_id = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_id']);
		$fid = intval($this->input['fid']);
		if($site_id && !$page_id)
		{
			$page = common::get_page_manage($site_id);
			if(is_array($page) && count($page) > 0)
			{
				foreach ($page as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}
		}
		else if($site_id && $page_id)
		{
			$page_data = common::get_page_data($page_id,0,1000,$fid);
			if(is_array($page_data['page_data']) && count($page_data['page_data']) > 0)
			{
				foreach($page_data['page_data'] as $k => $v)
				{
					$v['page_id'] = $page_id;
					$v['page_data_id'] = $v['id'];
					$this->addItem($v);
				}
				$this->output();
			}
		}
		else
		{
			$this->output();
		}
	
	}
	
	function get_site()
	{
		$sites = $this->pub->get_site('id,site_name');
		$this->addItem($sites);
		$this->output();
	}
	//取内容类型
	function get_content_type()
	{
		$site_id	  = intval($this->input['site_id']);
		$page_id 	  = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_data_id']);
		$set_type = $this->settings['site_col_template'];
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->publishcontent = new publishcontent();		
		if($site_id && !$page_id && !$page_data_id)
		{
			//有内容，查出内容类型
			$content_type = $this->publishcontent->get_all_content_type();
			foreach($content_type as $k=>$v)
			{
				$set_type[$v['id']] = $v['content_type'];
			}
		}
		else if($page_id)
		{
			$page_info = common::get_page_by_id($page_id);
			$site_id   = $page_info['site_id'];
			if($page_info['has_content'])
			{
				//有内容，查出内容类型
				$content_type = $this->publishcontent->get_all_content_type();
				foreach($content_type as $k=>$v)
				{
					$set_type[$v['id']] = $v['content_type'];
				}
			}
		}
		else
		{
			$this->errorOutput('NO_PAGE_ID');
		}
		$result['set_type']	   = $set_type;
		$result['site_id'] 	  	  	= $site_id;
		$result['page_id']  	  	= $page_id;
		$result['page_data_id']   	= $page_data_id;
		$this->addItem($result);
		$this->output();
	}
	function detail()
	{	
		$site_id	  = intval($this->input['site_id']);
		$page_id 	  = intval($this->input['page_id']);
		$page_data_id = intval($this->input['page_data_id']);
		$content_type = $this->input['content_type'];		
		$condition = " AND id = " . intval($this->input['id']);
		$cell = $this->obj->detail($condition);
		if($cell)
		{
			$cell['param_asso'] = unserialize($cell['param_asso']);
		}
		if($cell['cell_mode'] && $cell['data_source'])
		{
			$cell_mode_param = common::get_mode_info($cell['cell_mode']);
			if($cell_mode_param['mode_param'])
			{
				foreach ($cell_mode_param['mode_param'] as $k => $v)
				{
					$v['value'] = $cell['param_asso']['mode_param'][$k] ? $cell['param_asso']['mode_param'][$k] : $v['value'];
					$mode_param[$k] = $v;
				}
				$cell_mode_param['mode_param'] = $mode_param;
			}	
			if($cell_mode_param['data_param'])
			{
				foreach ($cell_mode_param['data_param'] as $k => $v)
				{
					$v['assoc_data_variable'] = $cell['param_asso']['assoc_param'][$k];
					$mode_data_param[$k] = $v;
				}
				$cell_mode_param['data_param'] = $mode_data_param;
			}		
			$data_source_param = common::get_datasource_info($cell['data_source']);
			if($data_source_param['input_param'])
			{
				foreach ($data_source_param['input_param'] as $k => $v)
				{
					$v['value'] = $cell['param_asso']['input_param'][$v['sign']] ? $cell['param_asso']['input_param'][$v['sign']] : $v['value'];
					$input_param[$k] = $v;
				}
				$data_source_param['input_param'] = $input_param;
			}
		}
		$block = $this->block->get_block_list();
		$cell_mode = common::get_mode($site_id);
		$data_source = common::get_data_source();
		$ret = array('cell' => $cell,'cell_mode_param' => $cell_mode_param,'data_source_param' => $data_source_param,'block' => $block,'cell_mode' => $cell_mode,'data_source' => $data_source);
		$this->addItem($ret);
		$this->output();
	}
	public function count()
	{	
		if($this->input['content_type'])
		{
			echo 0;
		}
		else
		{
			$condition = $this->get_condition();
			$totalNum = $this->obj->count($condition);
			echo json_encode($totalNum);			
		}
	}
	private function get_condition()
	{		
		$condition = '';
		return $condition;
	}
	//获取样式
	public function get_cell_mode()
	{
		$cell_mode = common::get_mode();
		$this->addItem($cell_mode);
		$this->output();
	}
	//获取数据源
	public function get_data_source()
	{
		$data_source = common::get_data_source();
		$this->addItem($data_source);
		$this->output();
	}
	public function get_datasource_info()
	{
		$data_source_id = intval($this->input['id']);
		$data_source = common::get_datasource_info($data_source_id);
		$this->addItem($data_source);
		$this->output();
	}
	//获取样式和数据源的参数
	public function get_cell_data_param()
	{
		$cell_mode_id = intval($this->input['cell_mode_id']);
		$data_source_id = intval($this->input['data_source_id']);
		if(!$cell_mode_id || !$data_source_id)
		{
			$this->errorOutput('noid');
		}
		$cell_mode_param = common::get_mode_info($cell_mode_id);
		$data_source_param = common::get_datasource_info($data_source_id);
		$ret = array('cell_mode_param' => $cell_mode_param,'data_source_param' => $data_source_param);
		$this->addItem($ret);
		$this->output();
	}
	function create_block_form()
	{
		$data_source = common::get_data_source();
		include_once(ROOT_PATH.'lib/class/publishconfig.class.php');
		$this->pub_config= new publishconfig();
		$column = $this->pub_config->get_column(' id,name ');
		$ret = array('data_source' => $data_source, 'column' => $column);
		$this->addItem($ret);
		$this->output();
	}
	function get_block_info()
	{
		$block_id = intval($this->input['block_id']);
		if(!$block_id)
		{
			$this->errorOutput('block_id 不能为空');
		}
		$ret = $this->block->get_block_info($block_id);
		$this->addItem($ret);
		$this->output();
	}
	
	function test()
	{
		$special_id = intval($this->input['special_id']);
		$template_id = intval($this->input['template_id']);
		$special_column = intval($this->input['special_column']);
		$ret = common::get_special_cell_list($special_id,$template_id,$special_column);
		$this->addItem($ret);
		$this->output();
	}	
}
$out = new cellApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
