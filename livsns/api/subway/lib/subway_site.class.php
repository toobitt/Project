<?php

//地铁站点的数据库操作
class subwaySite extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增地铁站点
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "subway_site SET ";
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

    //更新地铁站点相关信息
    public function update($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "subway_site SET ";
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

    //删除地铁站点
    public function delete($ids)
    {
    	$sqll = "select * from " . DB_PREFIX . "subway_site where id in(" . $ids .")";
		$ret = $this->db->query($sqll);
		while($row = $this->db->fetch_array($ret))
		{
			$pre[] = $row;
		}
		
        $sql = "DELETE FROM " . DB_PREFIX . "subway_site WHERE id IN (" . $ids . ")";
        $this->db->query($sql);

        
        if(count($pre) >1)
        {
        	$this->addLogs('删除地铁站点',$pre,'', '删除地铁站点  ' . $ids);
        }
        else
        {
        	$name = $pre[0]['name'];
        	$this->addLogs('删除地铁站点',$pre,'', '地铁站点  ' . $name);
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询地铁站点
    public function show($condition, $limit)
    {
    	$sql = "SELECT *
				FROM  " . DB_PREFIX ."subway_site 
				WHERE 1".$condition.' ORDER BY order_id DESC '.$limit;
		$q = $this->db->query($sql);
		
		$sorts = array();
		$sql_ = "select name,id from " . DB_PREFIX . "subway_site_sort where 1";
		$sorts = $this->db->fetch_all($sql_);
		
		while($row = $this->db->fetch_array($q))
		{				
			foreach ($sorts as $k=>$v){			
				if( $v['id']== $row['sort_id']){
					$row['sort_name'] = $v['name'];
				}
			}	
			$row['cre_time'] = date("Y-m-d H:i",$row['create_time']);
			$row['direction'] = $row['start'].'-'.$row['end'];
			$row['sort_name'] = '测试';
			$ret[] = $row;
		}
		
    	return $ret;
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