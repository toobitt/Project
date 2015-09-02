<?php
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class catalogcore extends InitFrm
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
	 *
	 * 开关 ...
	 */
	public function display($ids, $switch,$table)
	{
		$sql = 'UPDATE '.DB_PREFIX.$table.' SET switch = '.$switch.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'switch'=>$switch,
		);
		return $arr;
	}
	/**
	 *
	 * 查询此应用内容
	 * @param string $where 查询条件
	 * @param array $app_field 已绑定应用编目信息
	 * @param string $field 查询字段
	 */
	public function show_content($where,$field='*')
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'content as c
		INNER JOIN '.DB_PREFIX.'field as f ON c.catalog_field=f.catalog_field 
		LEFT JOIN '.DB_PREFIX.'style as s ON f.form_style=s.id 
		LEFT JOIN '.DB_PREFIX.'materials as m ON m.cid = c.id WHERE 1 '.$where;
		$q=$this->db->query($sql);
		$ret=array();
		while($data = $this->db->fetch_array($q))
		{
			$catalog_field=catalog_prefix($data['catalog_field']);
			if($data['type']=='img')
			{
				$img_info = array();
				if ($data['host'] && $data['dir'] && $data['filepath'] && $data['filename'])
				{
					$img_info = array(
					'id'		=> $data['mid'],
					'host'		=> $data['host'],
					'dir'		=> $data['dir'],
					'filepath'	=> $data['filepath'],
					'filename'	=> $data['filename'],
					'imgheight'	=> $data['imgheight'],
					'imgwidth'	=> $data['imgwidth'],
					);
				}
				$data['value']=$img_info;
			$ret[$data['content_id']][$catalog_field]['zh_name']=$data['zh_name'];
			$ret[$data['content_id']][$catalog_field]['value'][]=$data['value'];
				
			}
			else {
    			$ret[$data['content_id']][$catalog_field]=array(
    					'zh_name'=>$data['zh_name'],
    					'value'=>$this->content_change($data['type'], $data['value'])
    			);
			}
		}
		return $ret;
	}
	
	/**
	 *
	 * 查询此应用全部内容
	 * @param string $where 查询条件
	 * @param array $app_field 已绑定应用编目信息
	 * @param string $field 查询字段
	 */
	public function showAllcontent($where,$field='*',$is_all = false, $is_batch = false)
	{
		$sql='SELECT '.$field.' FROM '.DB_PREFIX.'content as c
		INNER JOIN '.DB_PREFIX.'field as f ON c.catalog_field=f.catalog_field AND c.identifier=f.identifier
		LEFT JOIN '.DB_PREFIX.'style as s ON f.form_style=s.id 
		LEFT JOIN '.DB_PREFIX.'materials as m ON m.cid = c.id WHERE 1 '.$where;
		$q=$this->db->query($sql);
		$ret=array();
		while($data = $this->db->fetch_array($q))
		{
			$catalog_field=catalog_prefix($data['catalog_field']);
			if($data['type']=='img')
			{
				$img_info = array();
				if ($data['host'] && $data['dir'] && $data['filepath'] && $data['filename'])
				{
					$img_info = array(
					'id'		=> $data['mid'],
					'host'		=> $data['host'],
					'dir'		=> $data['dir'],
					'filepath'	=> $data['filepath'],
					'filename'	=> $data['filename'],
					'imgheight'	=> $data['imgheight'],
					'imgwidth'	=> $data['imgwidth'],
					);
				}
				$data['value']=$img_info;
				$ret[$data['catalog_field']]=$data['value'];
			}
			elseif ($is_all && !$is_batch)
			{
				$ret[$data['catalog_field']] = $data;
			}
			elseif ($is_batch)
			{
				$ret[$data['content_id']][$data['catalog_field']] = $data;
			}
			else 
			{
			    $ret[$data['catalog_field']]= $this->content_change($data['type'], $data['value']);
			}
		}
		return $ret;
	}

	/*
	 * 生成缓存文件
	 */
	public function cache()
	{
		$sql='SELECT sort.id as catalog_sort_id,sort.catalog_sort,
		sort.catalog_sort_name,field.id,field.catalog_field,field.remark,
		field.catalog_default,field.selected,field.bak,field.batch,field.required,
		field.zh_name,style.formhtml AS style,style.type FROM '.DB_PREFIX.'field AS field 
		LEFT JOIN '.DB_PREFIX.'style AS style ON field.form_style=style.id 
		LEFT JOIN '.DB_PREFIX.'field_sort AS sort ON sort.id=field.catalog_sort_id WHERE 1  AND field.switch = 1';
		$sql .= " ORDER BY sort.order_id DESC,field.order_id DESC";
		$q = $this->db->query($sql);
		while($data = $this->db->fetch_array($q))
		{
			$data['catalog_field']=catalog_prefix($data['catalog_field']);
			$default=$data['catalog_default']=$data['catalog_default']?explode(',', $data['catalog_default']):NULL;
			$data['selected']=maybe_unserialize($data['selected']);
			if(is_string($data['selected'])&&!empty($data['selected']))
			{
				$data['selected']=$this->content_change($data['type'], $data['selected']);
			}
			$datas[$data['catalog_sort']]['catalog_sort_id']=$data['catalog_sort_id'];
			$datas[$data['catalog_sort']]['catalog_sort_name']=$data['catalog_sort_name'];
			$datas[$data['catalog_sort']]['catalog_sort']=$data['catalog_sort'];

			if($data['type']=='text'||$data['type']=='textarea')
			{
				$data['style'] = str_replace(REPLACE_NAME,$data['catalog_field'],$data['style']);
			}
			elseif ($data['type']=='radio')
			{
				$style= str_replace(REPLACE_NAME,$data['catalog_field'],$data['style']);
				unset($data['style']);
				foreach ($default AS $defaults)
				{
					$data['style'] .= str_replace(REPLACE_DATA,$defaults,$style);
				}
			}
			elseif ($data['type']=='checkbox')
			{
				$style= str_replace(REPLACE_NAME,$data['catalog_field'].'[]',$data['style']);
				unset($data['style']);
				foreach ($default AS $defaults)
				{
					$data['style'] .= str_replace(REPLACE_DATA,$defaults,$style);
				}
			}
			elseif($data['type']=='option')
			{
				$style= $data['style'];
				unset($data['style']);
				foreach ($default AS $defaults)
				{
					$data['style'] .= str_replace(REPLACE_DATA,$defaults,$style);
				}
				$data['style'] ='<select name='.$data['catalog_field'].'><option value>请选择'.$data['zh_name'].'</option>'.$data['style'].'</select>';
			}
			elseif($data['type']=='img')
			{
				if($data['batch'])
				{
					$data['style'] = str_replace(REPLACE_NAME,$data['catalog_field'].'[]',$data['style']);
				}
				else {
					$data['style'] = str_replace(REPLACE_NAME,$data['catalog_field'],$data['style']);
				}
			}
			else continue;

			$html=array(	'catalog_id'=>$data['id'],
							'zh_name' => $data['zh_name'],
							'catalog_field' => $data['catalog_field'],
							'remark'=>$data['remark'],
							'catalog_default' => $data['catalog_default'],
							'selected' => $data['selected'],
							'bak' => $data['bak'],
							'batch' => $data['batch'],
							'required' => $data['required'],
							'type' => $data['type'],
     						'style' => $data['style']
			);
			$datas[$data['catalog_sort']]['html'][$data['catalog_field']]=$html;
			$datas[$data['catalog_sort']]['html'][$data['catalog_field']]['data'] = NULL;
		}
		$text='<?php $cache='.var_export($datas,true).';?>';
		hg_file_write(CACHE_SORT,$text);

	}

	/**
	 *
	 * html样式值替换 ...
	 * @param string $style
	 * @param string $type
	 * @param array $value
	 */
	public function replace($style,$type,$value)
	{
		if($type=='text'||$type=='textarea' || $type=='classify' || $type=='price' || $type=='date' || $type=='label' || $type=='custom')
		{
			if($value)
			{
				$restyle = str_replace(REPLACE_DATA,$value,$style);
			}
			else
			{
				$restyle = str_replace(REPLACE_DATA,'',$style);
			}
		}
		elseif ($type=='radio')
		{
			if($value)
			{
				$restyle = str_replace('value="'.$value.'"','value="'.$value.'"  checked="checked"',$style);
			}
			else
			{
				return $style;
			}

		}
		elseif ($type=='checkbox')
		{
			if($value)
			{
				foreach ($value as $values)
				{
					$style = str_replace('value="'.$values.'"','value="'.$values.'"  checked="checked"',$style);
				}
				$restyle = $style;
			}
			else
			{
				return 	$style;
			}
		}
		elseif($type=='option')
		{
			if($value)
			{
				$restyle=str_replace('value="'.$value.'"','value="'.$value.'"  selected="selected"',$style);
			}
			else return $style;
		}
		elseif($type=='img')
		{
			return $style;
		}
		else return false;

		return $restyle;

	}
	//数据库内容转换为正常状态
	public function content_change($type,$value)
	{
		if ($type=='checkbox'&&$value)
		{
			return explode(',', $value);
		}
		else return $value;
	}

	/**
	 *
	 *	插入数据进素材库 ...
	 * @param array $data 素材数据
	 */
	public function insert_materials($data = array())
	{
		if(!$data)
		{
			return false;
		}
		$sql = " INSERT INTO " . DB_PREFIX . "materials SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	/**
	 * 创建数据
	 * @param String $table 表
	 * @param Array $data 数据
	 * @param String $pk 主键
	 */
	public function create($table, $data, $order=false,$pk = 'id')
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
		if($table&&$order)//更新附加信息表排序
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
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
				elseif (is_array($val))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
			}
		}
		$res=$this->db->query($sql);
		if ($idsArr&&$res)
		{
			return $idsArr;
		}
		return false;

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
		if($data)
		{
			foreach ($data as $key => $val)
			{
				if (is_int($val) || is_float($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($val, ',')!==false))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
				elseif(is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (\'' . $val . '\')';
				}
				elseif (is_array($val))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
			}
		}
		return $this->db->query($sql);
	}
}