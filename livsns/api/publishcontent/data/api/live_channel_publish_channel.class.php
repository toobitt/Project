<?php
class live_channel_publish_channel extends adminBase
{
	public $field = 'id,expand_id,content_fromid,title,brief,outlink,code,logo_info,open_ts,audio_only,stream_name';

	public $tablename = 'live_channel_publish_channel';
	
	public $child_tablename = '';
	
	public $child_table = '';
	
	public $array_field = '';
	
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
		$sql = "SELECT COUNT(*) AS total FROM ".$this->tablename." WHERE 1 ".$this->get_condition($con_data);
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
		$sql = "SELECT COUNT(*) AS total FROM ".$this->child_tablename." WHERE 1 ".$condition;
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
		$child_offset = $data['child_offset'];
		$child_count = $data['child_count'];
		if($id)
		{
			include_once(CUR_CONF_PATH . 'lib/cache.class.php');
			$this->cache = new Cache();
			$this->cache->initialize(CUR_CONF_PATH . 'cache/'.$this->tablename.'/');
			$result = $this->cache->get($id);
			if($result == 'no_file_dir' || !$this->obj->is_mk_cache($need_pages,$need_child_detail,$result['need_pages'],$result['need_child'],$need_process,$result['need_process']))
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
					$after_process = $this->obj->content_manage($url,$dir,$con_detail['content'],$need_pages,$need_process);
					$con_detail['content'] = $after_process['content'];
					$con_detail['content_pics'] = $after_process['content_pics'];
				}
				if($this->child_tablename && $need_child_detail)
				{
					$child_offset = $child_offset ? intval($child_offset) : 0;			
					$child_count = $child_count ? intval($child_count) : $this->settings['default_count'];
					$condition = " AND expand_id='".$id."'";
					//查询数据
					$child_data = $this->obj->get_content(' * ',$this->child_tablename,$this->array_child_field,$condition,$child_offset,$child_count,$data);
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