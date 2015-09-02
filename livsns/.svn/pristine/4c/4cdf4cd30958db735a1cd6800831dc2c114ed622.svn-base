<?php
require('../admin/global.php');
class tuji_tuji_m_tuji_tuji_pics extends BaseFrm
{
	public $field = 'id,expand_id,content_fromid,title,brief,tuji_id,material_id,old_name,new_name,thumb_name,path,total_visit,status,is_cover,create_time,update_time,ip,order_id';

	public $tablename = 'liv_tuji_tuji_m_tuji_tuji_pics';
	
	public $child_tablename = '';

	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/get_content.class.php');
		$this->obj = new get_content();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_content()
	{
		$condition = '';
		$expand_id = urldecode($this->input['expand_id']);
		$weight = urldecode($this->input['weight']);
		$field = urldecode($this->input['field']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$field = empty($field)?'*':$field;
		if(!empty($field) && $field!='*')
		{
			//查看要查询的字段是否表中存在
			if(!$this->obj->check_field($field,$this->field))
			{
				$result['msg'] = '不存在相关字段';
				$result['error'] = '1';
				$this->addItem($result);
				$this->output();
			}
		}
		if($expand_id)
		{
			$condition .= " AND expand_id='".$expand_id."'";
		}
		if($weight !== '')
		{
			$condition .= " AND weight='".$weight."'";
		}
		//查询数据
		$data = $this->obj->get_content($field,$this->tablename,$condition,$offset,$count);
		foreach($data as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}

	public function get_content_detail()
	{
		$need_child_detail = urldecode($this->input['need_child_detail']);  //是否需要子级信息，只要有值，表示需要提取子级信息，同时还可以传入子级信息的child_offset，child_count
		$id = intval($this->input['id']);
		$field = urldecode($this->input['field']);
		$field = empty($field)?'*':$field;

		if($id)
		{
			if(!empty($field) && $field!='*')
			{
				//查看要查询的字段是否表中存在
				if(!$this->obj->check_field($field,$this->field))
				{
					$result['msg'] = '不存在相关字段';
					$result['error'] = '1';
					$this->addItem($result);
					$this->output();
				}
			}
			$con_detail = $this->obj->get_content_detail($field,$this->tablename,$id);
			
			if($need_child_detail && $this->child_tablename)
			{
				$child_offset = $this->input['child_offset'] ? intval($this->input['child_offset']) : 0;			
				$child_count = $this->input['child_count'] ? intval($this->input['child_count']) : $this->settings['default_count'];
				$condition = " AND expand_id='".$id."'";
				//查询数据
				$child_data = $this->obj->get_content(' * ',$this->child_tablename,$condition,$child_offset,$child_count);
			}
			$con_detail['child_detail'] = empty($child_data)?array():$child_data;
			$con_detail['child_name'] = $this->child_tablename;
			
			$this->addItem($con_detail);
			$this->output();
		}
		else
		{
			$result['msg'] = '没有详细内容';
			$result['error'] = '2';
			$this->addItem($result);
			$this->output();
		}
	}

	function unknow()
	{
		$result['msg'] = '此方法不存在';
		$result['error'] = '1';
		$this->addItem($result);
		$this->output();
	}
}
$out = new tuji_tuji_m_tuji_tuji_pics();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
$action = 'unknow';  
}
$out->$action();
?>