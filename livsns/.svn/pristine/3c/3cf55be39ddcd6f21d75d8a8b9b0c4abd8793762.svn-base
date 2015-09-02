<?php

//地铁服务分类的数据库操作
class subwayServiceSort extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增地铁服务分类
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "subway_service_sort SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    //更新地铁服务分类相关信息
    public function update($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "subway_service_sort SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id =" . $info['id'];
        $this->db->query($sql);
    }

    //删除地铁服务分类
    public function delete($ids)
    {
    	$sqll = "select * from " . DB_PREFIX . "subway_service_sort where id in(" . $ids .")";
		$ret = $this->db->query($sqll);
		while($row = $this->db->fetch_array($ret))
		{
			$pre[] = $row;
		}
		
        $sql = "DELETE FROM " . DB_PREFIX . "subway_service_sort WHERE id IN (" . $ids . ")";
        $this->db->query($sql);

        
        if(count($pre) >1)
        {
        	$this->addLogs('删除地铁服务分类',$pre,'', '删除地铁服务分类  ' . $ids);
        }
        else
        {
        	$name = $pre[0]['name'];
        	$this->addLogs('删除地铁服务分类',$pre,'', '删除地铁服务分类  ' . $name);
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询地铁服务
    public function show($condition, $limit)
    {
    	$sql = "SELECT *
				FROM  " . DB_PREFIX ."subway_service_sort 
				WHERE 1".$condition.' ORDER BY order_id DESC '.$limit;
		$q = $this->db->query($sql);
		
		while($row = $this->db->fetch_array($q))
		{
			$indexpic_ = array();
			$sqlm_ = 'SELECT * FROM ' .DB_PREFIX. 'subway_materials WHERE cid = '.$row['id'] .' AND cid_type = 5';
			$qm_ = $this->db->query($sqlm_);
			while ($rm_ = $this->db->fetch_array($qm_))
			{
				if ($rm_['mark'] == 'img')
				{
					$indexpic_[] = array(
						'id'=>$rm_['id'],
						'host'=>$rm_['host'],
						'dir'=>$rm_['dir'],
						'filepath'=>$rm_['filepath'],
						'filename'=>$rm_['filename'],
						'imgwidth'=>$rm_['imgwidth'],
						'imgheight'=>$rm_['imgheight'],
					);
				}
			}
			$row['indexpic'] = $indexpic_;
			
			$row['id'] 		=  $row['column_id'];
			$row['cre_time'] = date("Y-m-d H:i",$row['create_time']);
			$ret[] = $row;
		}
    	return $ret;
    }

    
	public function insert_img($data = array())
	{
		if(!$data)
		{
			return false;
		}
		$sql = " INSERT INTO " . DB_PREFIX . "subway_materials SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}

	//删除图片
	public function deleteMaterials($ids)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'subway_materials WHERE id IN (' . $ids . ')';
		$this->db->query($sql);
		return $ids;
	}
	
    public function update_data($data, $table, $where = '')
    {
        if ($table == '' or $where == '')
        {
            return false;
        }
        $where = ' WHERE ' . $where;
        $field = '';
        if (is_string($data) && $data != '')
        {
            $field = $data;
        }
        elseif (is_array($data) && count($data) > 0)
        {
            $fields = array();
            foreach ($data as $k => $v)
            {
                $fields[] = $k . "='" . $v . "'";
            }
            $field = implode(',', $fields);
        }
        else
        {
            return false;
        }
        $sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $field . $where;
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function insert_data($data, $table)
    {
        if (!$table)
        {
            return false;
        }
        $sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
        if (is_array($data))
        {
            $sql_extra = $space     = ' ';
            foreach ($data as $k => $v)
            {
                $sql_extra .=$space . $k . "='" . $v . "'";
                $space = ',';
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