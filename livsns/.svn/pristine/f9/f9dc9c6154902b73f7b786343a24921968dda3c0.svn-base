<?php

//热词的数据库操作

class Hotwords extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增热词
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "hotwords SET ";
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

    //更新热词相关信息
    public function update($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "hotwords SET ";
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

    //删除热词
    public function delete($ids)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "hotwords WHERE id IN (" . $ids . ")";
        $this->db->query($sql);

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询热词
    public function show($condition,$limit,$other_field='')
    {
        $sql   = "SELECT  * $other_field FROM  " . DB_PREFIX . "hotwords  WHERE 1" . $condition.$limit;
        $q     = $this->db->query($sql);

        while ($row = $this->db->fetch_array($q))
        {
            $row['create_time_show'] = date("Y-m-d H:i", $row['create_time']);
            switch ($row['state'])
            {
                case 0 :
                    $row['audit']  = $row['status'] = '待审核';
                    break;
                case 1 :
                    $row['audit']  = $row['status'] = '已审核';
                    break;
                default:
                    $row['audit']  = $row['status'] = '已打回';
                    break;
            }
            $row['title'] = $row['name'];
            $ret[] = $row;
        }
      	//echo $sql;exit;
        return $ret;
    }
    
     public function update_hotwords($data, $table, $where = '')
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
    
    public function get_hotwords_list($cond, $field = "*")
    {
        $sql = "SELECT " . $field . " FROM " . DB_PREFIX . "hotwords WHERE 1 AND " . $cond;
        $q   = $this->db->fetch_all($sql);
        return $q;
    }
    
}

?>