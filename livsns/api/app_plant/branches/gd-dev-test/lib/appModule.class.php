<?php
/***************************************************************************
* $Id: appModule.class.php 17481 2013-04-19 09:36:46Z yaojian $
***************************************************************************/
class appModule extends InitFrm
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
	 * 获取APP数据
	 * @param Array $data
	 */
	public function show($data)
	{
		if ($data['count'] != -1)
		{
			$data_limit = ' LIMIT ' . $data['offset'] . ' , ' . $data['count'];
		}
		$fields = $data['fields'] ? $data['fields'] : '*';
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'app_module WHERE 1';
		//获取查询条件
		$condition = $this->get_condition($data['condition']);
		$sql .= $condition;
		if ($data_limit) $sql .= $data_limit;
		$query = $this->db->query($sql);
		$info = $solidify_id = $tpl_id = $bind_id = array();
		while ($rows = $this->db->fetch_array($query))
		{
			if (unserialize($rows['pic']))
			{
				$rows['pic'] = unserialize($rows['pic']);
			}
			if (unserialize($rows['press_pic']))
			{
				$rows['press_pic'] = unserialize($rows['press_pic']);
			}
			if ($rows['bind_id'] && unserialize($rows['bind_params']))
			{
			    $rows['bind_params'] = unserialize($rows['bind_params']);
			}
			if ($rows['solidify_id'] > 0)
			{
			    $solidify_id[$rows['solidify_id']] = $rows['solidify_id'];
			}
			if ($rows['body_tpl_id'] > 0)
			{
			    $tpl_id[$rows['body_tpl_id']] = $rows['body_tpl_id'];
			}
			if ($rows['bind_id'] > 0)
			{
			    $bind_id[$rows['bind_id']] = $rows['bind_id'];
			}
			else if($rows['bind_id'] < 1 && $rows['bind_collect'] >0)
			{
				$bind_id[$rows['bind_id']] = $rows['bind_collect'];
			}
			$info[] = $rows;
		}
		//获取固化模块
		if ($solidify_id)
		{
		    $solidify_id = implode(',', $solidify_id);
		    $solidify_info = $this->getSolidifyInfo($solidify_id);
		}
		//获取正文模板信息
		if ($tpl_id)
		{
		    $tpl_id = implode(',', $tpl_id);
		    $tpl_info = $this->getMainTextInfo($tpl_id);
		}
		//获取绑定数据信息
		if ($bind_id)
		{
		    $bind_id = implode(',', $bind_id);
		    $bind_info = $this->getBindInfo($bind_id);
		}
		if ($solidify_info || $tpl_info || $bind_info)
		{
            foreach ($info as $k => $v)
            {
                if ($solidify_info && $solidify_info[$v['solidify_id']])
                {
                    $info[$k]['solidify'] = $solidify_info[$v['solidify_id']];
                }
                if ($tpl_info && $tpl_info[$v['body_tpl_id']])
                {
                    $info[$k]['body_tpl'] = $tpl_info[$v['body_tpl_id']]['body_html'];
                    $info[$k]['body_tpl_uniqueid'] = $tpl_info[$v['body_tpl_id']]['uniqueid'];
                    $info[$k]['body_tpl_frame_uniqueid'] = $tpl_info[$v['body_tpl_id']]['frame_uniqueid'];                
                }
                if ($bind_info && $bind_info[$v['bind_id']])
                {
                    $info[$k]['bind_name'] = $bind_info[$v['bind_id']];
                }
                if ($bind_info && $bind_info[$v['bind_collect']])
                {
                	$info[$k]['bind_name'] = $bind_info[$v['bind_collect']];
                }
            }
		}
		return $info;
	}
	
	public function getBindInfo($id)
	{
	    $sql = 'SELECT id,name FROM ' . DB_PREFIX . 'data_bind WHERE status = 1 AND id IN (' . $id . ')';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $info[$rows['id']] = $rows['name'];
	    }
	    return $info;
	}
	
	/**
	 * 获取正文模板数据
	 * @param string|int $id
	 */
	private function getMainTextInfo($id)
	{
	    $sql = 'SELECT id,body_html,uniqueid,frame_uniqueid FROM ' . DB_PREFIX . 'content_tpl WHERE status = 2 AND id IN (' . $id . ')';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $rows['body_html'] = html_entity_decode($rows['body_html']);
	        $info[$rows['id']] = array(
	            	'uniqueid'       => $rows['uniqueid'],
	                'body_html'      => $rows['body_html'],
	                'frame_uniqueid' => $rows['frame_uniqueid'],
	        );
	    }
	    return $info;
	}
	
	/**
	 * 获取固化模块数据
	 * @param string|int $solidify_id
	 * @param int $user_id
	 */
	private function getSolidifyInfo($solidify_id)
	{
	    /*
	    if ($user_id)
	    {
	        $sql = 'SELECT m.id, m.mark, m.name, m.pic, u.param FROM ' . DB_PREFIX . 'solidify_module m 
	        INNER JOIN ' . DB_PREFIX . 'solidify_user u ON m.id = u.solidify_id 
	        WHERE m.id IN (' . $solidify_id . ') AND u.user_id = ' . $user_id;
	    }
	    else
	    {
	        $sql = 'SELECT id, mark, name, pic FROM ' . DB_PREFIX . 'solidify_module WHERE id IN (' . $solidify_id . ')';
	    }
	    */
	    $sql = 'SELECT id, mark, name, pic FROM ' . DB_PREFIX . 'solidify_module WHERE id IN (' . $solidify_id . ')';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        if ($rows['pic'] && unserialize($rows['pic']))
	        {
	            $rows['pic'] = unserialize($rows['pic']);
	        }
	        if ($rows['param'] && unserialize($rows['param']))
	        {
	            $rows['param'] = unserialize($rows['param']);
	        }
	        $info[$rows['id']] = $rows;
	    }
	    return $info;
	}
	
	/**
	 * 获取数据总数
	 * @param Array $data
	 */
	public function count($data)
	{
		$condition = $this->get_condition($data);
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_module WHERE 1';
		if ($condition) $sql .= $condition;
		return $this->db->query_first($sql);
	}
	
	/**
	 * 获取单个数据
	 * @param String $table
	 * @param Array $data
	 * @param String $fields
	 */
	public function detail($table, $data, $fields = '*')
	{
		$sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . $table .' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
			{
				$sql .= ' AND ' . $k . ' = ' . $v;
			}
			elseif (is_string($v))
			{
				$sql .= ' AND ' . $k . ' = "' . $v . '"';
			}
		}
		return $this->db->query_first($sql);
	}
	
	/**
	 * 验证数据
	 * @param Array $data
	 */
	public function verify($data)
	{
		if (!is_array($data)) return false;
		$sql = 'SELECT COUNT(id) AS total FROM ' . DB_PREFIX . 'app_module WHERE 1';
		$condition = '';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
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
	 * 根据模块获取栏目数据
	 * @param integer $app_id
	 */
	public function getColumnsByModule($app_id)
	{
	    $sql = 'SELECT column_id FROM ' . DB_PREFIX . 'app_module 
	    WHERE app_id = ' . $app_id . ' ORDER BY sort_order ASC';
	    $query = $this->db->query($sql);
	    $info = array();
	    while ($rows = $this->db->fetch_array($query))
	    {
	        $info[] = $rows['column_id'];
	    }
	    return $info;
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
			if (is_numeric($v))
			{
				$fields .= $k . '=' . $v . ',';
			}
			elseif (is_string($v))
			{
				$fields .= $k . "='" . $v . "',";
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'INSERT INTO ' . DB_PREFIX . $table . ' SET ' . $fields;
		$this->db->query($sql);
		$data[$pk] = $this->db->insert_id();
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
				if (is_numeric($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
				elseif (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val))
				{
					$sql .= ' AND ' . $key . ' in (' . $val . ')';
				}
			}
		}
		return $this->db->query($sql);
	}
	
	/**
	 * 删除信息
	 * @paramString $table
	 * @param Array $data
	 */
	public function delete($table, $data)
	{
		if (empty($table) || !is_array($data)) return false;
		$sql = 'DELETE FROM ' . DB_PREFIX . $table . ' WHERE 1';
		foreach ($data as $k => $v)
		{
			if (is_numeric($v))
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
	 * 获取查询条件
	 * @param Array $data
	 */
	private function get_condition($data)
	{
		$condition = '';
		
		//查询的关键字
		if ($data['keyword'])
		{
			$condition .= " AND name LIKE '%" . $data['keyword'] . "%'";
		}
		
		//根据ID获取数据
		if (isset($data['id']))
		{
			if (is_numeric($data['id']))
			{
				$condition .= " AND id = " . $data['id'];
			}
			elseif (is_string($data['id']))
			{
				$condition .= " AND id IN (" . $data['id'] . ")";
			}
		}
		
		//根据APP的id获取数据
		if (isset($data['app_id']))
		{
			if (is_numeric($data['app_id']))
			{
				$condition .= " AND app_id = " . $data['app_id'];
			}
			elseif (is_string($data['app_id']))
			{
				$condition .= " AND app_id IN (" . $data['app_id'] . ")";
			}
		}
		
		//根据用户获取数据
		if (isset($data['uid']))
		{
			if (is_numeric($data['uid']))
			{
				$condition .= " AND user_id = " . $data['uid'];
			}
			elseif (is_string($data['uid']))
			{
				$condition .= " AND user_id IN (" . $data['uid'] . ")";
			}
		}
		
		//排序
		$sort = ' ORDER BY ';
		if ($data['order'] && is_array($data['order']))
		{
			foreach ($data['order'] as $k => $v)
			{
				$sort .= $k . ' ' . $v . ', ';
			}
			$sort = rtrim($sort, ', ');
		}
		else
		{
			$sort .= 'sort_order ASC';
		}
		$condition = $condition . $sort;
		return $condition;
	}
	/**
	 * 根据app_id 和 module_id得到app_module
	 * @param unknown $app_id
	 * @param unknown $module_id
	 * @return unknown
	 * @author jitao
	 */
	public function getAppMopduleByAppidAndModuleId($app_id,$module_id)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'app_module WHERE app_id = '.$app_id .' and id = '.$module_id;
		$result = $this->db->query_first($sql);
		return $result;
	}
	
	//根据条件获取模块信息
	public function getModulesData($cond = '',$field = '*')
	{
        if(!$cond || !$field)
        {
            return FALSE;
        }
        
        $sql = " SELECT " .$field. " FROM " .DB_PREFIX. "app_module WHERE 1 " . $cond;
        $q = $this->db->query($sql);
        $ret = array();
        while ($r = $this->db->fetch_array($q))
        {
            $ret[] = $r;
        }
	    return $ret;
	}
}