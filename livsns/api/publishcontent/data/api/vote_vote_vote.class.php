<?php
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
		include_once(CUR_CONF_PATH . 'lib/get_content.class.php');
		$this->obj = new get_content();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function get_content($expand_id,$weight,$field,$offset,$count,$con_data=array())
	{
		$field = empty($field)?'*':$field;
		if(!empty($field) && $field!='*')
		{
			//查看要查询的字段是否表中存在
			if(!$this->obj->check_field($field,$this->field))
			{
				$result['msg'] = '不存在相关字段';
				$result['error'] = '1';
				return $result;
			}
		}
		$condition = $this->get_condition($con_data);
		//查询数据
		$data = $this->obj->get_content($field,$this->tablename,$this->array_field,$condition,$offset,$count);
		return $data;
	}
	
	private function get_condition($con_data)
	{
		$condition = '';
		if($con_data['expand_id'])
		{
			$condition .= " AND expand_id='".$con_data['expand_id']."'";
		}
		if(isset($con_data['weight']))
		{
			$condition .= " AND weight='".$con_data['weight']."'";
		}
		return $condition;
	}
	
	public function count($con_data)
	{
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.$this->tablename." WHERE 1 ".$this->get_condition($con_data);
		return json_encode($this->db->query_first($sql));
	}

	public function get_content_detail($id,$field,$need_child_detail,$child_offset,$child_count)
	{
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
					return $result;
				}
			}
			$con_detail = $this->obj->get_content_detail($field,$this->tablename,$this->array_field,$id);
			
			if($need_child_detail && $this->child_tablename)
			{
				$child_offset = $child_offset ? intval($child_offset) : 0;			
				$child_count = $child_count ? intval($child_count) : $this->settings['default_count'];
				$condition = " AND expand_id='".$id."'";
				//查询数据
				$child_data = $this->obj->get_content(' * ',$this->child_tablename,$this->array_child_field,$condition,$child_offset,$child_count);
			}
			$con_detail['child_detail'] = empty($child_data)?array():$child_data;
			$con_detail['child_name'] = $this->child_tablename;
			
			return $con_detail;
		}
		else
		{
			$result['msg'] = '没有详细内容';
			$result['error'] = '2';
			return $result;
		}
	}
	
	public function child_data_count($id)
	{
		$condition = " AND expand_id='".$id."'";
		$sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX.$this->child_tablename." WHERE 1 ".$condition;
		return json_encode($this->db->query_first($sql));
	}
	
	public function get_processed_content($data)
	{
		$id = $data['id'];
		$url = $data['url'];
		$dir = $data['dir'];
		$need_pages = $data['need_pages'];
		$need_child_detail = $data['need_child_detail'];
		$need_process = $data['need_process'];
		$need_separate = $data['need_separate'];
		$child_offset = $data['child_offset'];
		$child_count = $data['child_count'];
		if($id)
		{
			include_once(CUR_CONF_PATH . 'lib/cache.class.php');
			$this->cache = new Cache();
			$this->cache->initialize(CUR_CONF_PATH . 'cache/'.$this->tablename.'/');
			$result = $this->cache->get($id,true);
			if($result == 'no_file_dir' || !$this->obj->is_mk_cache($need_pages,$need_child_detail,$result['need_pages'],$result['need_child'],$need_process,$result['need_process'],$need_separate,$result['need_separate']))
			{
				$result = $child_data = array();
				//建缓存
				$con_detail = $this->obj->get_content_detail(' * ',$this->tablename,$this->array_field,$id);
				if(empty($con_detail))
				{
					$result['msg'] = 'NO_DATA_BY_ID';
					$result['error'] = '1';
					return $result;
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
					$child_offset = $child_offset ? intval($child_offset) : 0;			
					$child_count = $child_count ? intval($child_count) : $this->settings['default_count'];
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
				$this->cache->set($id,$result,$this->settings['content_cache_time']);
			}
			return $result['content'];
		}
		else
		{
			$result['msg'] = 'NO_ID';
			$result['error'] = '1';
			return $result;
		}
	}
}
?>