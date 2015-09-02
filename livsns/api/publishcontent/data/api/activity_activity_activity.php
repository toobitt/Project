<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('TOKEN_LENGTH', 30);
define('MOD_UNIQUEID','publishcontent');
require(ROOT_PATH.'global.php');
class liv_activity_activity_activity extends adminBase
{
	public $field = 'id,content_fromid,cuser_id,cuser_name,action_sort,type_id,start_time,end_time,place,need_pay,need_num,introduce,slogan,yet_join,apply_num,collect_num,concern,contact,rights,link,swfurl,media_id,edit_count,create_time,is_open,bus,connection_user,connection_group,state,lat,lng';

	public $tablename = 'liv_activity_activity_activity';
	
	public $child_tablename = '';
	
	public $child_table = '';
	
	public $array_field = '';
	
	public $array_child_field = '';

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
		$data = $this->obj->get_content($field,$this->tablename,$this->array_field,$condition,$offset,$count);
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
			$con_detail = $this->obj->get_content_detail($field,$this->tablename,$this->array_field,$id);
			
			if($need_child_detail && $this->child_tablename)
			{
				$child_offset = $this->input['child_offset'] ? intval($this->input['child_offset']) : 0;			
				$child_count = $this->input['child_count'] ? intval($this->input['child_count']) : $this->settings['default_count'];
				$condition = " AND expand_id='".$id."'";
				//查询数据
				$child_data = $this->obj->get_content(' * ',$this->child_tablename,$this->array_child_field,$condition,$child_offset,$child_count);
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
	
	public function get_processed_content()
	{
		include(CUR_CONF_PATH . 'lib/cache.class.php');
		$this->cache = new Cache();
		$id = intval($this->input['id']);
		$url = intval($this->input['url']);
		$dir = intval($this->input['dir']);
		$need_pages = $this->input['need_pages'];
		$need_child_detail = urldecode($this->input['need_child_detail']);
		$need_process = urldecode($this->input['need_process']);
		if($id)
		{
			$this->cache->initialize(CUR_CONF_PATH . 'cache/'.$this->tablename.'/');
			$result = $this->cache->get($id);
			if($result == 'no_file_dir' || !$this->obj->is_mk_cache($need_pages,$need_child_detail,$result['need_pages'],$result['need_child'],$need_process,$result['need_process']))
			{
				$result = $child_data = array();
				//建缓存
				$con_detail = $this->obj->get_content_detail(' * ',$this->tablename,$this->array_field,$id);
				if(empty($con_detail))
				{
					$this->addItem('无效id');
					$this->output();
				}
				
				if(!empty($con_detail['content']))
				{
					$after_process = $this->obj->content_manage($url,$dir,$con_detail['content'],$need_pages,$need_process);
					$con_detail['content'] = $after_process['content'];
					$con_detail['material'] = $after_process['material'];
					
					$result['content']['child_detail'] = empty($child_data)?array():$child_data;
					$result['content']['child_name'] = $this->child_tablename;
				}
				if($this->child_tablename && $need_child_detail)
				{
					$child_offset = $this->input['child_offset'] ? intval($this->input['child_offset']) : 0;			
					$child_count = $this->input['child_count'] ? intval($this->input['child_count']) : $this->settings['default_count'];
					$condition = " AND expand_id='".$id."'";
					//查询数据
					$child_data = $this->obj->get_content(' * ',$this->child_tablename,$this->array_child_field,$condition,$child_offset,$child_count);
				}
				$result['content'] = $con_detail;
				$result['content'][$this->child_table] = $child_data;
				$result['need_pages'] = $need_pages;
				$result['need_child'] = $need_child_detail;
				$result['need_process'] = $need_process;
				$this->cache->initialize(CUR_CONF_PATH . 'cache/'.$this->tablename.'/');
				$this->cache->set($id,$result);
			}
			$this->addItem($result['content']);
			$this->output();
		}
		else
		{
			$this->addItem('无效id');
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
$out = new liv_activity_activity_activity();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
$action = 'unknow';  
}
$out->$action();
?>