<?php
/***************************************************************************
 * $Id: grade.class.php 17481 2013-04-18 09:36:46Z yaojian $
 ***************************************************************************/
class field extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
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
	 *
	 * 附加信息开关 ...
	 */
	public function display($ids, $switch)
	{
		$sql = 'UPDATE '.DB_PREFIX.'field SET switch = '.$switch.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'switch'=>$switch,
		);
		return $arr;
	}
	public function usefield($id)
	{
		$sql='SELECT distinct f.zh_name FROM '.DB_PREFIX.'fieldbind as b LEFT JOIN '.DB_PREFIX.'field as f ON b.field_id=f.id WHERE 1 AND b.field_id in ('.$id.')';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$delfield[] = $row['zh_name'];
		}
		return $delfield;
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
		if($table=='field')//更新附加信息表排序
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
	 * 通过编目id获取编目所属应用信息
	 * 当id为-1的时候，获取所有的编目所属的应用信息
	 */
	public function get_fieldbind($field_id)
	{
		if(!$field_id)
		{
			return null;
		}
		else
		{
			if($field_id == -1)
			{
				$sql = 'SELECT distinct sort_id FROM ' . DB_PREFIX . 'fieldbind WHERE 1';//获取已有的所有应用
			}
			else
			{
				$sql = 'SELECT * FROM ' . DB_PREFIX . 'fieldbind WHERE field_id = ' . $field_id;
			}
			$query = $this->db->query($sql);
			$info = array();
			while ($rows = $this->db->fetch_array($query))
			{
				$sorts[] = $rows['sort_id'];
			}
			if(!empty($sorts) && is_array($sorts))
			{
				$sorts=array_unique($sorts);
			}
			return $sorts;
		}
	}
	/**
	 * 获取所有样式类型信息
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
	//获取分类信息
	public function get_sort($condition,$field='id,name',$is_array=true)
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'sort WHERE 1'.$condition;
		$q=$this->db->query($sql);
		while ($re = $this->db->fetch_array($q))
		{
			if($is_array)
			{
				$return[]=$re;
			}
			else {
				$return=$re;
			}
		}
		return $return;
	}

	/*
	 *
	 */
	/*
	 * 附加信息组装

	 public function handle($sort_id='')
	 {
		$sql='SELECT field.id,field.field,field.field_default,field.selected,field.zh_name,style.formhtml AS style FROM '.DB_PREFIX.'field AS field LEFT JOIN '.DB_PREFIX.'style AS style ON field.form_style=style.id WHERE 1 AND field.switch = 1';
		if($sort_id)
		{
		$sql .=' AND field.id  IN (SELECT field_id from '.DB_PREFIX.'fieldbind where sort_id ='.$sort_id.')';
		}
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
		$datas[$data['field']]=$data;
		}
		if (!empty($datas) && is_array($datas))
		{
		foreach ($datas as $key=>$value)
		{
		$data_key[$key]=array('id'=>$value['id'],
		'field' => $value['field'],
		'field_default' => $value['field_default'],
		'selected' => $value['selected'],
		'zh_name' => $value['zh_name'],
		'style' => $value['style'],);
		$field_default=!empty($value['field_default'])?explode(',',$value['field_default']):FALSE;
		$cachefield[$key]=$data_key[$key];
		if(stripos($cachefield[$key]['style'], 'radio')!== false)//单选处理
		{
			
		if (!empty($datas) && is_array($datas))
		{
		$cachefield[$key]['style']= str_replace('{$name}',$key,$cachefield[$key]['style']);
		if(!empty($field_default)&&is_array($field_default))
		foreach ($field_default AS $values)
		{
		$tmp .= str_replace('{$data}',$values,$cachefield[$key]['style']);
		}
		$cachefield[$key]['style']=$tmp;
		unset($tmp);//清空$tmp缓存数据
		}
		}
		elseif(stripos($cachefield[$key]['style'], 'option')!== false)//下拉处理
		{
		if (!empty($datas) && is_array($datas))
		{
		if(!empty($field_default)&&is_array($field_default))
		foreach ($field_default AS $values)
		{
		$tmp .= str_replace('{$data}',$values,$cachefield[$key]['style']);
		}
		$tmp='<select name="{$name}"><option value>请选择'.$value['zh_name'].'</option>'.$tmp.'</select>';
		$cachefield[$key]['style']=$tmp;
		$cachefield[$key]['style']= str_replace('{$name}',$key,$cachefield[$key]['style']);
		unset($tmp);//清空$tmp缓存数据
		}
		}
			
		elseif(stripos($cachefield[$key]['style'], 'checkbox')!== false)//多选处理
		{
		if (!empty($datas) && is_array($datas))
		{
		$cachefield[$key]['style']= str_replace('{$name}',$key.'[]',$cachefield[$key]['style']);
		if(!empty($field_default)&&is_array($field_default))
		foreach ($field_default AS $values)
		{
		$tmp .= str_replace('{$data}',$values,$cachefield[$key]['style']);
		}
		$cachefield[$key]['style']=$tmp;
		unset($tmp);//清空$tmp缓存数据
		}
		}
		else $cachefield[$key]['style']= str_replace('{$name}',$key,$cachefield[$key]['style']);
			
		$re_field[$key]=$cachefield[$key];
		unset($cachefield);

		}
		}

		return $re_field;
		}
	 */
}
?>