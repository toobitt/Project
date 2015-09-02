<?php
class membersql extends InitFrm
{
	private $table = '';
	private $join = '';
	private $as = '';
	private $where = '';
	private $limit = '';
	private $orderby = '';
	private $groupby = '';
	private $format = array();
	private $newDb = null;
	private $mField = '*';
	private $pk = 'id';
	private $key = '';
	private $type = 1;
	private $otherKey = '';
	private $data = array();
	private $orderId = false;
	private $orderField = 'id';
	private $notUnsetWhere = 0;
	public function __construct($isclone = 0)
	{
		parent::__construct();
		$this->newDb = $isclone?clone $this->db:$this->db;
	}
	public function __destruct()
	{
		parent::__destruct();
		unset($this->newDb);
	}

	public function where($idsArr,$paramType = array())
	{		
		if (is_array($idsArr))
		{			
			foreach ($idsArr as $key => $val)
			{				
				if (is_array($val))
				{
					$Idcount = count($val);
					if($Idcount>1)
					{
					 $this->where .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
					}
					else if ($Idcount == 1)
					{
						 $this->where .= ' AND ' . $key . ' = \'' . $val[0] . '\'';
					}
				}
				elseif($paramType[$key]['fuzzy'])
				{
					$this->where .= ' AND '.$key.' LIKE \'%' . $val . '%\'';
				}
				elseif($paramType[$key]['math'])
				{
					$this->where .= ' AND ' . $key . $paramType[$key]['math'].' \'' . $val . '\'';
				}
				elseif (is_int($val) || is_float($val)||is_numeric($val))
				{
					$this->where .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')!==false))
				{
					$this->where .= ' AND ' . $key . ' in (' . $val . ')';
				}
				elseif(is_string($val))
				{
					$this->where .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}
		elseif (is_string($idsArr))
		{
			$this->where .= $idsArr;
		}		
		return $this->where;
	}
	/**
	 * 
	 * 清空where条件 ...
	 */
	public function unsetWhere()
	{
		!$this->notUnsetWhere && $this->where = '';
		 $this->notUnsetWhere && $this->notUnsetWhere!=-1 && $this->notUnsetWhere -= 1;
	}
	
	/**
	 * 
	 * 不清空where条件,大于0为n次，-1为不限制 ...
	 */
	public function notUnsetWhere($flat = 1)
	{
		$this->notUnsetWhere = $flat;
	}
	/**
	 * 
	 * 设置查询字段 ...
	 */
	public function setSelectField($field = '*')
	{
		$this->mField = $field;
	}
	public function detail($id = array(),$table = '',$_format = array(),&$output = array())
	{
		
		if(is_array($_format)&&$_format&&$this->format)
		{
			$_format = array_merge($_format,$this->format);
		}
		else {
			$_format = $_format?$_format:$this->format;
		}
		$table&&$this->setTable($table);//兼容
		if(!$this->table)
		{
			return -1;
		}
		if($id&&is_array($id))
		{
			$this->where($id);
		}
		elseif($id)
		{
			$this->where('AND id = \''.$id.'\'');
		}
		$sql = "SELECT {$this->mField} FROM " . DB_PREFIX . $this->table .' WHERE 1 '.$this->where;
		$this->unsetWhere();
		$row = $this->newDb->query_first($sql);
		if(is_array($row) && $row)
		{
			$output['detail'] = $this->dataFormat($_format, $row);
			return $output['detail'];
		}
		return false;
	}
	public function limit($offset,$count)
	{
		return $count?$this->limit 	 = " LIMIT " . (int)$offset . " , " . (int)$count:'';
	}
	public function orderby($_orderby)
	{
		$this->orderby = $_orderby?' '.$_orderby:'';		
		return $this->orderby;
	}
	
	public function groupby($_groupby)
	{
		$this->groupby = $_groupby?' group by '.$_groupby:'';
		return $this->groupby;
	}
	public function setKey($_key)
	{
		$this->key = $_key;
	}
	public function setType($_type)
	{
		$this->type = $_type;
	}
	public function setOtherKey($_otherKey)
	{
		$this->otherKey = $_otherKey;
	}
	public function show($idArr = array(),$table = '',$offset = 0,$count = 0,$orderby = '',$field = '*',$key = '',$_format = array(),$type = 1,$otherKey='')
	{
		$ret = array();
		$row = array();
		if(is_array($_format)&&$_format&&$this->format)
		{
			$_format = array_merge($_format,$this->format);
		}
		else {
			$_format = $_format?$_format:$this->format;
		}
		$idArr&&$this->where($idArr);//兼容
		$table&&$this->setTable($table);//兼容
		if(!$this->table)
		{
			return -1;
		}
		(!$field || $field == '*') && $field = $this->mField;//兼容
		empty($key) && $key = $this->key;//兼容
		$type == 1 && $type = $this->type;//兼容
		empty($otherKey) && $otherKey = $this->otherKey;//兼容
		$count && $this->limit($offset, $count);
		$orderby && $this->orderby($orderby);		
		$sql = 'SELECT '.$field.' FROM '.DB_PREFIX.$this->table.$this->as.' '.$this->join.' WHERE 1 '.$this->where.$this->groupby.$this->orderby.$this->limit;
		$query = $this->newDb->query($sql);
		while ($row = $this->newDb->fetch_array($query))
		{
			$row = $this->dataFormat($_format, $row);
			if($key&&$type==1){
				$ret[$row[$key]] = $row;
			}
			elseif ($key&&$type==2)
			{
				$ret[$row[$key]][] = $row;
			}
			elseif ($key&&$type==3)
			{
				$ret[$row[$key]][] = $row[$otherKey];
			}
			elseif ($key&&$type==4)
			{
				$ret[$row[$key]] = $row[$otherKey];
			}
			elseif ($key&&$type==5)
			{
				!$otherKey && $otherKey = $key;
				$ret[$row[$otherKey]][] = $row[$key];
			}
			elseif ($key&&$type==6)
			{
				!$otherKey && $otherKey = $key;
				$ret[$otherKey][] = $row[$key];
			}
			elseif($key&&!$type)
			{
				$ret[] = $row[$key];
			}
			else {
				$ret[] = $row;
			}
		}
		$this->unsetWhere();
		return $ret;
	}
	
	public function setDataFormat(array $_format)
	{
		$this->format = $_format;
	}
	
	public function setTable($_table)
	{
		$_table&&$this->table = $_table;
	}
	
	public function setAsTable($asname)
	{
		$asname&&$this->as = ' AS '.$asname;
	}
	
	public function join($sql)
	{
		if($this->join)
		{
			$this->join .= ' '.$sql;
		}
		else {
			$this->join .= $sql;
		}
	}

	public function dataFormat($format,$row)
	{		
		if(is_array($format)&&$format)
		foreach ($format as $k => $v)
		{
			if(isset($row[$k]))
			{
				if($v['type'] == 'date')
				{		
					$row[$k] = $row[$k]?date($v['format'],$row[$k]):'';
				}
				elseif ($v['type'] == 'array')
				{
					$row[$k] = $row[$k]?call_user_func('maybe_'.$v['format'],$row[$k]):array();
				}
				elseif ($v['type'] == 'explode')
				{
					$row[$k] = explodes($row[$k], $v['delimiter']);
				}
			}
		}
		return $row;
	}

	public function count($idsArr = array(),$table = '')
	{
		$idsArr&&$this->where($idsArr);//兼容
		$table&&$this->setTable($table);//兼容
		if(!$this->table)
		{
			return -1;
		}
		$sql = 'SELECT count(*) as total FROM '.DB_PREFIX.$this->table.$this->as.' '.$this->join.' WHERE 1 '.$this->where.$this->orderby.$this->limit;
		$this->unsetWhere();
		$row = $this->newDb->query_first($sql);
		if(is_array($row) && $row)
		{
			return (int)$row['total'];
		}
		return 0;
	}
	
	public function setPk($pk)
	{
		$pk && $this->pk = $pk;
	}
	
	public function setData($_data)
	{
		$_data&&$this->data = $_data;
	}
	
	public function setOrderId($_order = false)
	{
		$this->orderId = (bool)$_order;
	}
	
	public function setOrderField($_orderF = 'id')
	{
		$this->orderField = (string)$_orderF;
	}
	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table = '', $data = array(), $order = false,$pk = 'id',$replace=false,$orderField = 'id')
	{
		$ret = array();
		$table&&$this->setTable($table);//兼容
		$data &&$this->setData($data);//兼容
		$order && $this->setOrderId($order);//兼容
		$orderField != 'id' && $this->orderField($orderField);
		if (!$this->table || !is_array($this->data)) return false;
		$fields = '';
		$pk == 'id' && $pk = $this->pk;
		foreach ((array)$this->data as $k => $v)
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
		$cmd = $replace ? 'REPLACE INTO ' : 'INSERT INTO ';
		$sql = $cmd.DB_PREFIX . $this->table . ' SET ' . $fields;
		$this->newDb->query($sql);
		$id = $this->newDb->insert_id();
		$ret = $this->data;
		$pk && $id && $ret[$pk] = $id;
		if($this->table&&$this->orderId)
		{
			$this->update($this->table, array('order_id'=>$id),array($this->orderField => $id));
		}
		return $ret;
	}

	/**
	 * 更新数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	public function update($table = '', $data = array(), $idsArr=array(), $flag = false,$math_key = array())
	{
		$ret = array();
		$table&&$this->setTable($table);//兼容
		$data &&$this->setData($data);//兼容		
		if (!$this->table || !is_array($this->data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($this->data as $k => $v)
		{
			if ($flag&&(empty($math_key)||array_key_exists($k, $math_key)))
			{
				$math = empty($math_key)?'+':$math_key[$k];
				$v = $v >= 0 ? $math . $v : $v;
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
		$sql = 'UPDATE ' . DB_PREFIX . $this->table . ' SET ' . $fields . ' WHERE 1'.$this->where($idsArr);
		$this->unsetWhere();
		$res = $this->newDb->query($sql);
		if ($this->data&&$res)
		{
			if($idsArr&&is_array($idsArr))
			{
				$ret = array_merge($idsArr,$this->data);
			}
			elseif ($this->data)
			{
				$ret = $this->data;
			}
			return $ret;
		}
		return false;

	}

	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function replace($table, $data, $order=false,$pk = 'id')
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
		$sql = 'REPLACE INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->newDb->query($sql);
		$id = $this->newDb->insert_id();
		if($table&&$order)
		{
			$sql = 'UPDATE '.DB_PREFIX. $table . ' set order_id = '.$id.' WHERE id = '.$id;
			$this->newDb->query($sql);
		}
		$id && $data[$pk] = $id;
		return $data;
	}

	/**
	 * 删除
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table = '', $data = array())
	{
		$table&&$this->setTable($table);//兼容
		$data &&$this->where($data);//兼容		
		if (empty($this->table) || !$this->where) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $this->table . ' WHERE 1'.$this->where;
		$this->unsetWhere();
		if($this->newDb->query($sql))
		{
			return $this->affected_rows();
		}
		return 0;
	}

	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($table = '',$data = array())
	{
		$table&&$this->setTable($table);//兼容
		$data &&$this->where($data);//兼容		
		if (!$this->table  || !$this->where) return false;
		$sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . $this->table .' WHERE 1'.$this->where;
		$this->unsetWhere();
		$result = $this->newDb->query_first($sql);
		return $result['total']?true:false;
	}
	
	public function affected_rows()
	{
	  return $this->newDb->affected_rows();
	}

}
