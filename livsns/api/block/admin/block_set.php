<?php
require('global.php');
define('MOD_UNIQUEID','publishcontent_block_set');//模块标识
require_once(ROOT_PATH.'lib/class/publishconfig.class.php');
require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
class block_setApi extends adminBase
{
		/**
	 * 构造函数
	 * @author repheal
	 * @category hogesoft
	 * @copyright hogesoft
	 * @include site.class.php
	 */
	public function __construct()
	{
		parent::__construct();
		$this->pub_config= new publishconfig();
		$this->pub_content= new publishcontent();
		include(CUR_CONF_PATH . 'lib/block_set.class.php');
		$this->obj = new block_set();
		include(CUR_CONF_PATH . 'lib/block.class.php');
		$this->block = new block();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有要设置的区块');
		}
		
		$block = $this->block->get_group_block($id);
		if(!empty($block))
		{
			$block_idarr = array_keys($block['block']);
			$block_ids = implode($block_idarr,',');
			$block_line = $this->obj->get_group_block_line($block_ids);
			$content = $this->obj->get_group_block_content($block['block']);
			
			if($block['block_record'])
			{
				$column_array = array_unique($block['block_record']);
				foreach($column_array as $v)
				{
					foreach($v as $vv)
					{
						$column_ids .= ','.$vv;
					}
				}
				$columns = $this->pub_config->get_columnname_by_ids('id,name',trim($column_ids,','));
			}
		}
		//查出所有app标识
		$apps = $this->pub_content->get_app();
		
		$data['block'] = $block;
		$data['block_line'] = $block_line;
		$data['content'] = $content;
		$data['column'] = $columns;
		$data['app'] = $apps;
		$this->addItem($data);
		$this->output();
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."block WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	
	private function get_condition()
	{
		$condition = '';
		return $condition;
	}
	
	public function block_form()
	{
		$data = array();
		$id = intval($this->input['id']);
		if($id)
		{
			$data = $this->obj->get_block_first($id);
		}
		$this->addItem($data);
		$this->output();
	}
	
	public function create()
	{
		$data = array(
			'name' => urldecode($this->input['name']),
			'update_time' => intval($this->input['update_time']),
			'update_type' => intval($this->input['update_type']),
			'datasource_id' => intval($this->input['datasource_id']),
			'width' => intval($this->input['width']),
			'height' => intval($this->input['height']),
			'line_num' => intval($this->input['line_num']),
			'loop_body' => urldecode($this->input['loop_body']),
		);
		if(!$data['name'] || !$data['update_type'])
		{
			$this->errorOutput('信息未填全');
		}
		$this->obj->insert($data);
		
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('更新失败');
		}
		$data = array(
			'name' => urldecode($this->input['name']),
			'update_time' => intval($this->input['update_time']),
			'update_type' => intval($this->input['update_type']),
			'datasource_id' => intval($this->input['datasource_id']),
			'width' => intval($this->input['width']),
			'height' => intval($this->input['height']),
			'line_num' => intval($this->input['line_num']),
			'loop_body' => urldecode($this->input['loop_body']),
		);
		if(!$data['name'] || !$data['update_type'])
		{
			$this->errorOutput('信息未填全');
		}
		$this->obj->update($data,$id);
		$data = $this->obj->get_block_first($id);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		$ids = urldecode($this->input['id']);
		if(!$ids)
		{
			$this->errorOutput('删除失败');
		}
		$this->obj->delete($ids);
		$this->addItem('success');
		$this->output();
	}
	
	public function block_set_sort()
	{
		$data = json_decode(html_entity_decode($this->input['data']),true);
//		$data['line'] = array(45=>3,46=>2);
//		$data['content'] = array(
//			60 => array('line'=>3),
//			61 => array('line'=>2),
//		);
		if(!empty($data['line']))
		{
			foreach($data['line'] as $k=>$v)
			{
				$this->obj->update_line_by_id($k,$v);
			}
		}
		if(!empty($data['content']))
		{
			foreach($data['content'] as $k=>$v)
			{
				$this->obj->update_content_by_id($k,$v);
			}
		}
		$this->addItem('success');
		$this->output();
	}
	
	/*
	public function show()
	{
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('没有要设置的区块');
		}
		
		$block = $this->block->get_block_first($id);
		
		$block_line = $this->obj->get_block_line($id);
		
		$content = $this->obj->get_block_content($id , $block['line_num']);
		
		if(!$block['line_num'])
		{
			$this->errorOutput('没有数据行');
		}
		if(!$block['height'])
		{
			$this->errorOutput('未设置区块高度');
		}
		
		$child_height = $block['height']/$block['line_num'];
		
		for($i=1;$i<=$block['line_num'];$i++)
		{
			$style = " id='line".$i."' name='line".$i."' style='height:".$child_height."px;";
			$style .= "'";
			if(!$insert_i = stripos($block_line[$i]['loop_body'],'>'))
			{
				$this->errorOutput('未找到标识');
			}
			$loop_body = str_insert($block_line[$i]['loop_body'], $insert_i , $style);
			
			if(strpos($block_line[$i]['loop_body'],'$title'))
			{
				$loop_body = str_replace('$title',"<a href='".$content[$i]['outlink']."'>".$content[$i]['title']."</a>",$loop_body);
				$loop_body = str_replace('{','',$loop_body);
				$loop_body = str_replace('}','',$loop_body);
			}
			if(strpos($block_line[$i]['loop_body'],'$brief'))
			{
				$loop_body = str_replace('$brief',$content[$i]['brief'],$loop_body);
				$loop_body = str_replace('{','',$loop_body);
				$loop_body = str_replace('}','',$loop_body);
			}
			
			$child_data[$i] = $loop_body;
			
		}
		
		
		
		$data['block'] = $block;
		$data['block_line'] = $block_line;
		$data['child_height'] = $child_height;
		$data['child_data'] = $child_data;
//		print_r($data);exit;
		$this->addItem($data);
		$this->output();
	}
	*/
	

	
	
	/**
	 * 空方法
	 * @name unknow
	 * @access public
	 * @author repheal
	 * @category hogesoft
	 * @copyright 	ho	gesoft
	 */
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

$out = new block_setApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>
