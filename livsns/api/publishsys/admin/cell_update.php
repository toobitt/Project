<?php
require('global.php');
define('MOD_UNIQUEID','cell');
class cellUpdateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include_once(CUR_CONF_PATH . 'lib/common.php');
		include(CUR_CONF_PATH . 'lib/cell.class.php');
		$this->obj = new cell();
		include_once(ROOT_PATH . 'lib/class/block.class.php');
		$this->block = new block();		
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	function create()
	{	
	}
	function update()
	{	
		$cell_id = intval($this->input['id']);
		$using_block = intval($this->input['using_block']);
		$data = array(
				'site_id'      => intval($this->input['site_id']),
				'page_id'      => intval($this->input['page_id']),
				'page_data_id' => intval($this->input['page_data_id']),
				'content_type' => $this->input['content_type'],
				'cell_type'    => intval($this->input['cell_type']),
				'cell_code'    => htmlspecialchars_decode($this->input['cell_code']),
				'cell_mode'    => $this->input['cell_mode'],
				'using_block'  => $using_block,
		);
		if($using_block)
		{
			$data['block_id'] = intval($this->input['block_id']);
		}
		else
		{
			$data['data_source'] = $this->input['data_source'];			
		}
		if($this->input['input_param'])
		{
			$param['input_param'] = $this->input['input_param'];
		}
		if($this->input['mode_param'])
		{
			$param['mode_param'] = $this->input['mode_param'];
		}
		if($this->input['assoc_param'])
		{
			$param['assoc_param'] = $this->input['assoc_param'];
		}
		$data['param_asso'] = '';
		if($param)
		{
			$data['param_asso'] = serialize($param);
		}		
		$condition = " AND site_id = " . $data['site_id'] ." AND page_id = " . $data['page_id'] ." AND page_data_id = " . $data['page_data_id'] . " AND content_type = " . $data['content_type'] . " AND cell_name='".$this->input['title']."' AND original_id != 0 AND del=0";
		$info = $this->obj->detail($condition);
		if($info)
		{
			$data['update_time'] = TIMENOW;
			$this->obj->update($data,$info['id']);
		}
		else
		{
			$data['original_id'] = $cell_id;   //原单元id
			$data['cell_name'] = $this->input['title'];
			$data['template_style'] = $this->input['template_style'];
			$data['sort_id'] = intval($this->input['sort_id']);
			$data['template_id'] = intval($this->input['template_id']);
			$data['template_sign'] = $this->input['template_sign'];
			$data['user_id'] = $this->user['user_id'];
			$data['user_name'] = $this->user['user_name'];
			$data['appid'] = $this->user['appid'];
			$data['appname'] = $this->user['display_name'];
			$data['create_time'] = TIMENOW;
			$data['update_time'] = TIMENOW;
			$cell_id = $this->obj->create($data);
		}
		$cell_mode = common::get_mode_info($data['cell_mode']);
		$data_source = common::get_datasource_info($data['data_source']);
		$map = $this->obj->get_cell_map($cell_mode,$data_source,$param);
		$datafile = $this->settings['data_source_dir'].$data['data_source'].'.php';
		include_once $datafile;
		$classname = "ds_" . $data['data_source'];
		$obj = new $classname();
		$ret = $obj->show('');
		$cache_file = $cell_id .'_'.$data['data_source'] .'_'.$data['cell_mode'] . '.php';
		include_once(CUR_CONF_PATH . 'lib/parse.class.php');
		$parse = new Parse();
		$content = html_entity_decode($cell_mode['mode_info']['content']);
		$parse->parse_template($content,$map['relation_map'],$map['mode_variable_map']);
		$html = $parse->built_cell_html($ret,$cache_file);	
		$this->addItem($html);
		$this->output();
	}
		
	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput("请选择需要删除的单元");
		}
		$condition .= " AND c.id IN(".$ids.")";
		$cell = $this->obj->show($condition);
		if(is_array($cell) && count($cell))
		{
			foreach($cell as $k => $v)
			{
				if(!$v['original_id'])
				{
					$this->errorOutput('此单元不能删除');
				}
			}
		}
		$ret = $this->obj->delete($ids);
		$this->addItem($ret);
		$this->output();
	}
    
	function create_block()
	{
		if(!$this->input['name'])
		{
			$this->errorOutput('noname');
		}
		$data = array(
				'site_id' => intval($this->input['site_id']),
				'column_id' => intval($this->input['column_id']),
				'name' => urldecode($this->input['name']),
				'update_time' => intval($this->input['update_time']),
				'update_type' => intval($this->input['update_type']),
				'datasource_id' => intval($this->input['datasource_id']),
				'width' => intval($this->input['width']),
				'height' => intval($this->input['height']),
				'line_num' => intval($this->input['line_num']),
				'father_tag' => urldecode($this->input['father_tag']),
				'loop_body' => urldecode($this->input['loop_body']),
				'next_update_time' => TIMENOW+intval($this->input['update_time']),
				'is_support_push' => intval($this->input['is_support_push']),
		);	
		$ret = $this->block->insert_block($data);
		$this->addItem($ret);
		$this->output();	
	}	
	
	function audit(){}
	function sort(){}
	function publish(){}
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new cellUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();

?>