<?php
//主题分类的数据库操作
define('MOD_UNIQUEID','special_content');//模块标识

class specialContent extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		require_once(ROOT_PATH . 'lib/class/auth.class.php');
		$this->auth = new Auth();
		include_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->puc = new publishcontent();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create($info)
	{		
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."special_content SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."special_content SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	
	//更新发布的专题内容
	public function update_content($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."special_content SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		//$sql .= " WHERE content_fromid =".$info['content_fromid'] ."  AND bundle_id = '".$info['bundle_id']."'"."  AND module_id = '".$info['module_id']."'";
		if(empty($info['cid']))
                {
                    return false;
                }
                $sql .= " WHERE cid = '".$info['cid']."'";
		$this->db->query($sql);		
	}
	
	public function delete($ids)
	{			
		$info = $this->get_content_count('*','special_content',"  where id IN(" . $ids . ")");
		$special_id = $info['special_id'];
		
		$sql = "DELETE FROM " . DB_PREFIX . "special_content WHERE id IN(" . $ids . ")";
		$this->db->query($sql);
		
		$sql_ = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE id IN(" . $ids . ")";
		$this->db->query($sql_);
		
		$q =  $this->get_content_count('count(*) as num','special_content'," WHERE special_id = ".$special_id ." AND column_id = ".$info['column_id']);
		
		$this->update_special_content(array('count' => $q['num']), 'special_columns', " id =".$info['column_id']);
		
		$count = $this->get_content_count('count(*) as num','special_content'," WHERE special_id = ".$special_id);
		$this->update_special_content(array('content_count' => $count['num']), 'special', " id IN({$special_id})");
		
		return ($this->db->affected_rows() > 0) ? true : false;
	}	
	
	//根据条件查询模专题内容
	public function show($condition,$limit,$special_id,$column_id='')	
	{	
		$str = " ORDER BY column_id,order_id DESC";
		$sql = "SELECT *
				FROM  " . DB_PREFIX ."special_content
				WHERE 1".$condition.$str.$limit;
		$q = $this->db->query($sql);
		$modules = $this->get_module();
		if($column_id)
		{
			$sqq = "SELECT id,count  FROM  " . DB_PREFIX . "special_columns where id = ".$column_id." AND special_id = ".$special_id;
			$sq_ = $this->db->query_first($sqq);
		}
		$re = $this->get_content_info('id,column_name','special_columns'," WHERE special_id = ".$special_id);
		if($re)
		{
			foreach($re as $k=>$v)
			{
				$columns[$v['id']] = $v['column_name'];
			}	
		}
		$sq_n = "SELECT id,name  FROM  " . DB_PREFIX . "special  where 1"." AND id = ".$special_id;
		$sname = $this->db->query_first($sq_n);
		
		$arr = $count = array();
		while($row = $this->db->fetch_array($q))
		{		
			if($row['pub_id'])
			{
				$pub_id[] = $row['pub_id'];
			}
			
			if($row['module_id'])
			{
				$row['module'] = $modules[$row['module_id']];
			}
			else
			{
				$row['module'] = '外链';
			}
            
			$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['pic'] = json_encode(unserialize($row['indexpic']));
			$row['state_color'] = $this->settings['state_color'][$row['state']];
			switch ($row['state'])
			{
				case 0 :
					$row['audit'] = $row['status'] = '待审核';
					break;
				case 1 :
					$row['audit'] = $row['status'] = '已审核';
					break;
				default:
					$row['audit'] = $row['status'] = '已打回';
					break;
			}
			$row['column_name'] = $columns[$row['column_id']];
			$info['info'][]=$row;
		}
		$pubids = array();
		if($pub_id)
		{
			$data = array(
				'id' => implode(',',$pub_id),
				'count' => 100,
			);
			$re = $this->puc->get_content($data);
			if($re && is_array($re))
			{
				foreach($re as $k=>$v)
				{
					$pub_info[$v['id']] = $v;
					$pubids[] = $v['id'];
				}
			}
		}
		if(is_array($info['info']))
		{
			foreach($info['info'] as $ke=>$va)
			{
				$a[] = $va;
				if($va['pub_id'])
				{
					$fa = $va;
					if($va['pub_id']&&!in_array($va['pub_id'],$pubids))
					{
						//unset($info[$ke]);
						$return['info'][$ke] = $va;
					}
					else
					{
						if($pub_info[$va['pub_id']])
						{
							$content_info = $pub_info[$fa['pub_id']];
							if ($content_info['column_name'] && $content_info['main_column_id'])
				            {
				            	$column_content = array();
				            	$column_content[$content_info['main_column_id']]  = $content_info['column_name'];
				                $va['pub'] = $column_content;
				                //$va['column_content'] = addslashes(serialize($column_content));
				            }
				            if ($content_info['id'] && $content_info['main_column_id'])
				            {
				            	$column_url = array();
				            	$column_url[$content_info['main_column_id']]  = $content_info['id'];
				                $va['pub_url'] = $column_url;
				                //$va['column_url'] = addslashes(serialize($column_url));
				            }
						}
						$return['info'][$ke] = $va;
					}
				}
				else
				{
					$return['info'][$ke] = $va;
				}
				
			}
		}
		$return['special_name'] = $sname['name'];
		if($column_id)
		{
			$return['count'] = $sq_['count'];
		}
		return $return;
	}	
	
	//根据条件查询模专题分类
	public function query($title)	
	{	
		$sql = "select id from " . DB_PREFIX . "special_content where title = '".$title."'";
		$q = $this->db->query_first($sql);
		return $q;
	}	
	
	
	public function create_child($info)
	{		
		$sq = "select count(*) as num from " . DB_PREFIX . "special_content_child where special_id = ".$info['special_id'] ." AND column_id = ".$info['column_id'];
		$q = $this->db->query_first($sq);
		if($q['num']>(CONTENT_COUNT-1))
		{
			$sql_ = "select id from " . DB_PREFIX . "special_content_child where special_id = ".$info['special_id'] ." AND column_id = ".$info['column_id'] ." ORDER BY create_time ASC LIMIT 1";
			$q_ = $this->db->query_first($sql_);
			$sqll = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE id =".$q_['id'];
			$this->db->query($sqll);
		}
		
		/*$s = "UPDATE " . DB_PREFIX ."special_content_child SET count =".$info['count']." WHERE special_id = ".$info['special_id'] ." AND column_id = ".$info['column_id'];
		$this->db->query($s);*/
		//插入数据操作
		$sql = "INSERT INTO " . DB_PREFIX ."special_content_child SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$this->db->query($sql);
		return $this->db->insert_id();
	}
	
	public function update_child($info)
	{	
		//更新数据操作
		$sql = "UPDATE " . DB_PREFIX ."special_content_child SET ";
		$sql_extra = $space ='';
		foreach($info as $k=>$v)
		{
			$sql_extra .=$space . $k . "='" . $v . "'";
			$space=',';
		}
		$sql .=$sql_extra;
		$sql .= " WHERE id =".$info['id'];
		$this->db->query($sql);		
	}
	
	//根据专题id专题内容
	public function show_content_by_special_ids($special_ids,$limit,$sort_type='',$sort='')	
	{	
            $pregreplace           = array('&#032;', '<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r");
                                        $pregfind              = array(' ', '&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '');
		$info = $pubids = array();
                $str = '';
                if (isset($this->input['content_weight']) && $this->input['content_weight'] !== '' && $this->input['content_weight'] != -1)
                {
                    $str .= " AND weight=".intval($this->input['content_weight']);
                }
                if (isset($this->input['except_content_weight']) && $this->input['except_content_weight'] !== '' && $this->input['except_content_weight'] != -1)
                {
                    $str .= " AND weight!=".intval($this->input['except_content_weight']);
                }
                $str .= ' ORDER BY b.order_id DESC,';
		if($sort_type == 'ASC'&& !$sort)
		{
			$str .= "a.order_id ".$sort_type;
		}
		elseif($sort_type&&$sort)
		{
			$str .= "a.".$sort." ".$sort_type." , "." a.order_id ".$sort_type;
		}
		elseif(!$sort_type&&$sort)
		{
			$str .= "a.".$sort." DESC ";
		}
		else
		{
			$str .= "a.order_id DESC";
		}
		
		$sql = "SELECT a.*, b.column_name AS special_content_column FROM  " . DB_PREFIX ."special_content a LEFT JOIN " . DB_PREFIX ."special_columns b ON a.column_id=b.id  WHERE  a.special_id  IN (" . $special_ids . ")"."  AND a.state =1 ".$str.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['special_content_column'] = trim($row['special_content_column']);
                        $row['special_content_column']          = str_replace($pregfind, $pregreplace, $row['special_content_column']);
			if($row['special_content_column'] == DEFAULT_COLUMN)
			{
				$row['special_content_column'] = '';
			}
			if($row['pub_id'])
			{
				$pub_id[] = $row['pub_id'];
			}
			else
			{
				$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : '';
			}
			$column[] = $row['column_id'];
			$info[] = $row;
		}
		if($pub_id)
		{
			$data = array(
				'id' => implode(',',$pub_id),
				'need_video' => 1,
				'count' => 100,
				'client_type' => $this->input['client_type'] ? intval($this->input['client_type']) : 2,
			);
			$re = $this->puc->get_content($data);
			//$re = $this->puc->get_content_by_rids(implode(',',$pub_id));
		}
		if(is_array($re))
		{
			foreach($re as $k=>$v)
			{
				$pub_info[$v['id']] = $v;
				$pubids[] = $v['id'];
			}
		}
		if(is_array($info))
		{
			foreach($info as $ke=>$va)
			{
				$fa = $va;
				if($va['pub_id']&&!in_array($va['pub_id'],$pubids))
				{
					unset($info[$ke]);
				}
				else
				{
					if($pub_info[$va['pub_id']])
					{
						$va = $pub_info[$fa['pub_id']] + $va;
						$va['columns'] = $va['column_id'];
						$va['column_id'] = $column[$ke];
					}
					$va['create_time'] = date("Y-m-d H:i",$va['create_time']);
					$va['update_time'] = date("Y-m-d H:i",$va['update_time']);
					//$va['indexpic'] = $va['indexpic'] ? unserialize($va['indexpic']) : '';
					
                                        $va['brief']          = str_replace($pregfind, $pregreplace, $va['brief']);
                                        $info[$ke] = $va;
				}
			}
		}
		return $info;
	}	
	
	//根据专题id获取专题内容条数
	public function show_content_count_by_special_ids($special_ids)	
	{
		$sql = "SELECT count(*) as count,special_id FROM " . DB_PREFIX ."special_content WHERE special_id in (" . $special_ids . ") group by special_id";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$re[$row['special_id']] = $row['count'];
		}
		return $re;
	}
	
		//根据专题栏目id专题内容
	public function get_content_by_special_column_ids($special_column_ids,$limit)	
	{	
		$info = array();
		$str = " ORDER BY create_time DESC";
		$sql = "SELECT * FROM  " . DB_PREFIX ."special_content WHERE  column_id  IN (" . $special_column_ids . ")".$str.$limit;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{		
			if($row['pub_id'])
			{
				$pub_id[] = $row['pub_id'];
			}
			else
			{
				$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : '';
			}
			$column[] = $row['column_id'];
			$info[] = $row;
		}
		if($pub_id)
		{
			$re = $this->puc->get_content_by_rids(implode(',',$pub_id));
		}
		if(is_array($re))
		{
			foreach($re as $k=>$v)
			{
				$pub_info[$v['rid']] = $v;
				$pubids[] = $v['rid'];
			}
		}
		if(is_array($info))
		{
			foreach($info as $ke=>$va)
			{
				$fa = $va;
				if($va['pub_id']&&!in_array($va['pub_id'],$pubids))
				{
					unset($info[$ke]);
				}
				else
				{
					if($pub_info[$va['pub_id']])
					{
						$va = $pub_info[$fa['pub_id']];
						$va['columns'] = $va['column_id'];
						$va['column_id'] = $column[$ke];
					}
					$va['create_time'] = date("Y-m-d H:i",$va['create_time']);
					$va['update_time'] = date("Y-m-d H:i",$va['update_time']);
					//$va['indexpic'] = $va['indexpic'] ? unserialize($va['indexpic']) : '';
					$info[$ke] = $va;
				}
			}
		}
		return $info;
	}
	
	
	/* 根据专题id获取栏目下内容
	 * */
	public function get_child_content_by_special_id($special_id)	
	{	
		$info = array();
		$sql = "SELECT * FROM  " . DB_PREFIX ."special_content_child WHERE  special_id  =" . $special_id ;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{		
			if($row['pub_id'])
			{
				$pub_id[] = $row['pub_id'];
			}
			else
			{
				$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : '';
			}
			$column[] = $row['column_id'];
			$info[] = $row;
		}
		if($pub_id)
		{
			$ar = array(
				'id' 				=> 		implode(',',$pub_id),
				'client_type'		=> 		'2',
				'need_video'		=> 		'1',
			);
			$re = $this->puc->get_content($ar);
		}
		$pubids = array();
		if(is_array($re))
		{
			foreach($re as $k=>$v)
			{
				$pub_info[$v['id']] = $v;
				$pubids[] = $v['id'];
			}
		}
		if(is_array($info))
		{
			foreach($info as $ke=>$va)
			{
				$fa = $va;
				if($va['pub_id']&&!in_array($va['pub_id'],$pubids))
				{
					unset($info[$ke]);
				}
				else
				{
					if($pub_info[$va['pub_id']])
					{
						$va = $pub_info[$fa['pub_id']];
						$va['columns'] = $va['column_id'];
						$va['column_id'] = $column[$ke];
					}
					$va['create_time'] = date("Y-m-d H:i",$va['create_time']);
					$va['update_time'] = date("Y-m-d H:i",$va['update_time']);
					//$va['indexpic'] = $va['indexpic'] ? unserialize($va['indexpic']) : '';
					$info[$ke] = $va;
				}
			}
		}
		if(is_array($info))
		{
			foreach($info as $k=>$v)
			{
				$child_info[$v['column_id']][] = $v;
			}
		}
		
		$sql_ = "SELECT * FROM  " . DB_PREFIX ."special_columns  WHERE  special_id  =" . $special_id .' ORDER BY order_id DESC';
		$q_ = $this->db->query($sql_);
		while($rq = $this->db->fetch_array($q_))
		{
			if($rq['column_name'] == DEFAULT_COLUMN)
			{
				unset($rq['column_name']);
				//$rq['column_name'] = '';
			}
			$rq['content'] = $child_info[$rq['id']];
			$return[] = $rq;
		}
		return $return;
	}	
		
	/**
	 * 公共入库方法 ...
	 * @param array $data 数据
	 * @param string $dbName  数据库名
	 */
	public function storedIntoDB($data,$dbName,$flag=0)
	{		
		if (!$data || !is_array($data) || !$dbName)
		{
			return false;
		}
		$sql = 'REPLACE INTO '.DB_PREFIX.$dbName.' SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($flag)
		{
			return $this->db->insert_id();
		}
		return true;
	}
	
	
	public function get_app()
	{		
		$apps = $this->auth->get_app('bundle,name');
		if(is_array($apps))
		{
			foreach($apps as $k=>$v)
			{
				$apps_arr[$v['bundle']] = $v['name'];
			}
		}
		return $apps_arr;
	}
	
	public function get_module()
	{		
		/*$modules = $this->auth->get_module('mod_uniqueid,name');
		if(is_array($modules))
		{
			foreach($modules as $k=>$v)
			{
				$modules_arr[$v['mod_uniqueid']] = $v['name'];
			}
		}*/
		require_once(ROOT_PATH.'lib/class/publishcontent.class.php');
		$this->publishcontent = new publishcontent();
		$return = $this->publishcontent->get_pub_content_type();
		if($return)
		{
			foreach($return as$k=>$v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		return $bundles;
	}
	
	
	public function get_content_count($field, $table, $where = '')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . $table.$where;
		$info = $this->db->query_first($sql);
		return $info;
	}
	
	public function get_content_info($field, $table, $where = '')
	{
		$sql = "SELECT ". $field ." FROM " . DB_PREFIX . $table.$where;
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{	
			$info[] = $row;
		}
		return $info;
	}
	
	public function update_special_content($data, $table, $where = '') 
	{
		if($table == '' or $where == '') 
		{
			return false;
		}
		$where = ' WHERE '.$where;
		$field = '';
		if(is_string($data) && $data != '') 
		{
			$field = $data;
		} 
		elseif (is_array($data) && count($data) > 0) 
		{
			$fields = array();
			foreach($data as $k=>$v) 
			{
				$fields[] = $k."='".$v . "'";
			}
			$field = implode(',', $fields);
		} 
		else 
		{
			return false;
		}
		$sql = 'UPDATE '. DB_PREFIX . $table . ' SET '.$field.$where;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	
	public function insert_data($data,$table)
	{
		if(!$table)
		{
			return false;
		}
		$sql="INSERT INTO " . DB_PREFIX .$table. " SET ";		
		if(is_array($data))
		{
			$sql_extra=$space=' ';
			foreach($data as $k => $v)
			{
				$sql_extra .=$space . $k . "='" . $v . "'";
				$space=',';
			}
			$sql .=$sql_extra;
		}
		else
		{
			$sql .= $data;
		}
		$this->db->query($sql);
		return $this->db->insert_id();		
	}
}
?>