<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('TOKEN_LENGTH', 30);
define('MOD_UNIQUEID','publishcontent');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class contribute_contribute_content extends adminBase
{
	public $field = 'id,expand_id,content_fromid, title,brief,sort_id,keywords,material_id,appid,client,longitude,
						latitude,create_time,update_time,user_id,user_name,audit,is_pub,order_id,expand_id,column_id,
						column_url,publish_time,content,indexpic,opinion';

	public $tablename = 'contribute_contribute_content';
	
	public $child_tablename = 'contribute_contribute_content_materials';
	
	public $child_table = 'materials';
	
	public $array_field = 'indexpic';
	
	public $array_child_field = 'pic,video';

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
		if($this->input['is_count'])
		{
			$this->count();
		}
		$field = urldecode($this->input['field']);
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 100;
		$field = empty($field)?'*':$field;
		if(!empty($field) && $field!='*')
		{
			//查看要查询的字段是否表中存在
			if(!$this->obj->check_field($field,$this->field))
			{
				$this->errorOutput('NOT_EXITS_FIELD');
			}
		}
		$condition = $this->get_condition();
		//查询数据
		$data = $this->obj->get_content($field,$this->tablename,$this->array_field,$condition,$offset,$count);
		foreach($data as $k=>$v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	private function get_condition()
	{
		$condition = '';
		$expand_id = urldecode($this->input['expand_id']);
		$weight = urldecode($this->input['weight']);
		if($expand_id)
		{
			$condition .= " AND expand_id='".$expand_id."'";
		}
		if($weight !== '')
		{
			$condition .= " AND weight='".$weight."'";
		}
		return $condition;
	}
	
	public function count()
	{
		$sql = "SELECT COUNT(*) AS total FROM ".$this->tablename." WHERE 1 ".$this->get_condition();
		echo json_encode($this->db->query_first($sql));
		exit;
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
					$this->errorOutput('NOT_EXITS_FIELD');
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
			$this->errorOutput('NO_DETAIL_DATA');
		}
	}
	
	public function child_data_count()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$condition = " AND expand_id='".$id."'";
		$sql = "SELECT COUNT(*) AS total FROM ".$this->child_tablename." WHERE 1 ".$condition;
		echo json_encode($this->db->query_first($sql));
		exit;
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
					$this->errorOutput('NO_DATA_BY_ID');
				}
				
				if(!empty($con_detail['content']))
				{
					$after_process = $this->obj->content_manage($url,$dir,$con_detail['content'],$need_pages,$need_process);
					$con_detail['content'] = $after_process['content'];
					$con_detail['content_pics'] = $after_process['content_pics'];
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
				$result['content']['child_name'] = $this->child_tablename;
				if($this->child_table)
				{
					$result['content'][$this->child_table] = empty($child_data)?array():$child_data;;
				}
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
			$this->errorOutput('NO_ID');
		}
	}

	function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
}
$out = new contribute_contribute_content();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
$action = 'unknow';  
}
$out->$action();
?>