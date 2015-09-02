<?php
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('TOKEN_LENGTH', 30);
define('MOD_UNIQUEID','publishcontent');
require(ROOT_PATH.'global.php');
require(CUR_CONF_PATH."lib/functions.php");
class vote_vote_vote extends adminBase
{
	public $field = 'id,content_fromid,pictures,vote_id,title,describes,start_time,end_time,ip_limit_time,userid_limit_time,is_ip,is_userid,is_user_login,is_verify_code,option_num,option_type,min_option,max_option,is_other,total,order_id,create_time,update_time,ip,status,is_open,pictures_info,more_info,source_type,node_id,org_id,user_id,user_name,update_user_id,update_user_name,update_appid,update_appname,update_ip,column_id,column_url,expand_id,vod_id,ini_total,audit_user_id,audit_user_name,audit_time,publishcontent_id';

	public $tablename = 'vote_vote_vote';
	
	public $child_tablename = '';
	
	public $child_table = '';
	
	public $array_field = 'pictures_info,more_info';
	
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
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.$this->tablename." WHERE 1 ".$this->get_condition();
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
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.$this->child_tablename." WHERE 1 ".$condition;
		echo json_encode($this->db->query_first($sql));
		exit;
	}
	
	public function get_processed_content()
	{
		include_once(CUR_CONF_PATH . 'lib/cache.class.php');
		$this->cache = new Cache();
		$id = intval($this->input['id']);
		$url = intval($this->input['url']);
		$dir = intval($this->input['dir']);
		$need_pages = $this->input['need_pages'];
		$need_child_detail = urldecode($this->input['need_child_detail']);
		$need_process = urldecode($this->input['need_process']);
		$need_separate = urldecode($this->input['need_separate']);
		if($id)
		{
			$this->cache->initialize(CUR_CONF_PATH . 'cache/'.$this->tablename.'/');
			$result = $this->cache->get($id);
			if($result == 'no_file_dir' || !$this->obj->is_mk_cache($need_pages,$need_child_detail,$result['need_pages'],$result['need_child'],$need_process,$result['need_process'],$need_separate,$result['need_separate']))
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
					$after_process = $this->obj->content_manage($url,$dir,$con_detail['content'],$need_pages,$need_process,$need_separate);
					$con_detail['content'] = $after_process['content'];
					$con_detail['content_material_list'] = $after_process['content_material_list'];
					$child_data = $after_process['content_pics'];
				}
				$result['content'] = $con_detail;
				if($this->child_tablename && $need_child_detail)
				{
					$child_offset = $this->input['child_offset'] ? intval($this->input['child_offset']) : 0;			
					$child_count = $this->input['child_count'] ? intval($this->input['child_count']) : $this->settings['default_count'];
					$condition = " AND expand_id='".$id."'";
					//查询数据
					$child_table_arr = explode(',',$this->child_tablename);
					foreach($child_table_arr as $k=>$v)
					{
						if($v)
						{
							$child_data = $this->obj->get_content(' * ',$v,$this->array_child_field,$condition,$child_offset,$child_count);
							$result['content'][$v] = empty($child_data)?array():$child_data;
						}
					}
				}
				$result['content']['child_name'] = $this->child_tablename;
				$result['need_pages'] = $need_pages;
				$result['need_child'] = $need_child_detail;
				$result['need_process'] = $need_process;
				$result['need_separate'] = $need_separate;
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
$out = new vote_vote_vote();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
$action = 'unknow';  
}
$out->$action();
?>