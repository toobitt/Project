<?php
/***************************************************************************
 * $Id: grade.class.php 17481 2013-04-18 09:36:46Z yaojian $
 ***************************************************************************/
class manage extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->catalogcore = new catalogcore();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 获取单个编目管理的数据
	 * @param Int $id
	 */
	public function detail($id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'field WHERE id = ' . intval($id);
		return $this->db->query_first($sql);
	}


	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'field WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$condition .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$condition .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		$sql .= $condition;
		$result = $this->db->query_first($sql);
		return $result['total'];
	}
	
	/**
	 * 已被某个应用使用编目
	 */
	public function usefield($id)
	{
		$sql='SELECT distinct f.zh_name FROM '.DB_PREFIX.'app_map as b LEFT JOIN '.DB_PREFIX.'field as f ON b.field_id=f.id WHERE 1 AND b.field_id in ('.$id.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$delfield[] = $row['zh_name'];
		}
		return $delfield;
	}
	
	/**
	 * 
	 * 获取已被应用填充内容的编目
	 * @param int $id
	 */
	public function usefield_content($id)
	{
		$sql ='SELECT count(*) as total FROM '.DB_PREFIX.'content c 
		left join '.DB_PREFIX.'field f ON f.catalog_field = c.catalog_field where f.id ='.intval($id);
		$count = $this->db->query_first($sql);
		if($count['total'] > 0)
		return false;
		else return true;
	}

	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $pk = 'id')
	{
		if (!$table || !is_array($data)) return false;
		$fields = '';
		foreach ($data as $k => $v)
		{
			if (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
			elseif (is_int($v) || is_float($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if($table=='field')//更新编目表排序
		{
		$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		}
		$data[$pk] = $id;
		return $data;
	}

	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	public function update($table, $data, $idsArr, $flag = false)
	{
			
		if (!$table || !is_array($data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($data as $k => $v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields .= $k . '=' . $k . $v . ',';
			}
			else
			{
				if (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
				elseif (is_int($v) || is_float($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
					//		            return $this->detail($val);
				}
			}
		}

		$this->db->query($sql);

		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					return $this->detail($val);
				}
				elseif (is_string($val))
				{
					return $this->detail($val);
				}
			}
		}

	}

	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_int($v) || is_float($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' IN (' . $v . ')';
			}
		}
		return $this->db->query($sql);
	}

	/**
	 * 获取所有编目类型信息
	 */
	public function get_styles($id='',$field='*')
	{
		$sql = 'SELECT '.$field.' FROM ' . DB_PREFIX . 'style';
		if(!empty($id))
		{
			$sql .=' WHERE 1 AND id='.$id;
		}
		$sql .=' ORDER BY id';
		$query = $this->db->query($sql);
		$info = array();
		while ($rows = $this->db->fetch_array($query)) {
			$info[] = $rows;
		}
		return $info;
	}
	/**
	 * 通过编目id获取编目所属应用信息
	 * 当id为-1的时候，获取所有的编目所属的应用信息
	 */
	public function get_sort($field_id)
	{
		if(!$field_id)
		{
			return null;
		}
		else
		{
			if($field_id == -1)
			{
				$sql = 'SELECT distinct app_uniqueid FROM ' . DB_PREFIX . 'app_map WHERE 1';//获取已有的所有应用
			}
			else
			{
				$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_map WHERE field_id = ' . $field_id;
			}
			$query = $this->db->query($sql);
			while ($rows = $this->db->fetch_array($query))
			{
				$appuniqueid = explode(',',$rows['app_uniqueid']);
				foreach ($appuniqueid as $appuniqueids)
				$sorts[] = $appuniqueids;
			}			
			if(!empty($sorts) && is_array($sorts))
			{
				$sorts=array_unique($sorts);
			}
			return $sorts;
		}
	}

	/**
	 * 通过app条件,获取所属的编目
	 * @param Array $data
	 */
	function get_app_name($app,$field_id='field_id')
	{

		if($app)
		{
			$condition = ' AND app_uniqueid like "%'.$app.'%"';

			$sql = "SELECT {$field_id} FROM " .DB_PREFIX. "app_map WHERE 1" . $condition;

			$q = $this->db->query($sql);
			$field = array();
			while ($data = $this->db->fetch_array($q))
			{
				$field[] = $data['field_id'];
			}
			return $field;
		}
		else return null;
	}

	function get_catalog_filter($app_name,$no='NOT',$return=FALSE)
	{
		$arr_catalog_field = array();
		//获取应用的编目标识开始.
		$app_name = isset($this->input['app_uniqueid']) ? trim(urldecode($this->input['app_uniqueid'])): '';
		$app_field = $this->get_app_name($app_name);//编目所属应用
		IF($return&&empty($app_field))
		{
			return FALSE;
		}
		else $sql='SELECT catalog_field FROM '.DB_PREFIX.'field WHERE 1';
		if(!empty($app_field)&&is_array($app_field))
		{
			if (count($app_field) == 1)
			{
				$app_field = intval(current($app_field));
			}
			else
			{
				$app_field = implode(',', $app_field);
			}
			$sql .= " AND id {$no} IN (".$app_field.") ";
		}
		$q = $this->db->query($sql);//查出不属于当前应用的编目标识,以便unset掉不需要的缓存输出.
		while($data = $this->db->fetch_array($q))
		{
			$data['catalog_field']=catalog_prefix($data['catalog_field']);
			$arr_catalog_field[$data['catalog_field']] =  $data['catalog_field'];
		}
		return 	$arr_catalog_field;
		//获取应用的编目标识结束.
	}
	function get_catalog_field($where)
	{
		$sql = "SELECT catalog_field FROM " . DB_PREFIX . "content WHERE 1 " . $where;
		$q = $this->db->query($sql);//当前内容id是否已存在与内容表,并取出编目标识
		while($data = $this->db->fetch_array($q))
		{
			$data['catalog_field']=catalog_prefix($data['catalog_field']);
			$arr_catalog_field[$data['catalog_field']] =  $data['catalog_field']; //编目标识
		}
		return $arr_catalog_field;
	}

}
?>