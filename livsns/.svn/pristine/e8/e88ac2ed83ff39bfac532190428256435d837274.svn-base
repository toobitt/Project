<?php

//模板的数据库操作
class cell extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增模板
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "cell SET ";
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

    //更新模板相关信息
    public function update($data, $id)
    {
        //插入数据操作
        $sql       = "UPDATE " . DB_PREFIX . "cell SET ";
        $sql_extra = $space     = '';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .= $sql_extra;
        $sql .= " WHERE id =" . $id;
        $this->db->query($sql);
    }

    //删除日志
    public function delete()
    {
        //删除日志
        $ids = urldecode($this->input['id']);
        $sql = "DELETE FROM " . DB_PREFIX . "cell WHERE id IN(" . $ids . ")";
        $this->db->query($sql);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询单元
    public function show($condition, $limit = '')
    {
        $sql = "SELECT c.*
				FROM " . DB_PREFIX . "cell c 
				WHERE 1 AND c.del=0 " . $condition . $limit;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row['create_time']                                   = date('Y-m-d H:i', $row['create_time']);
            $ret[$row['template_sign'] . '_' . $row['cell_name']] = $row;
        }
        return $ret;
    }

    public function show_cell_style($condition)
    {
        $sql = "SELECT t.template_style FROM " . DB_PREFIX . "cell c 
				LEFT JOIN " . DB_PREFIX . "templates t
						ON c.template_id = t.id WHERE 1 " . $condition;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            if ($row['template_style'])
            {
                $ret[] = $row['template_style'];
            }
        }
        return $ret;
    }

    public function detail($condition, $tableName = 'cell')
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . $tableName . " WHERE 1 " . $condition;
        $info = $this->db->query_first($sql);
        if (!empty($info))
        {
            $info['param_asso'] = $info['param_asso'] ? unserialize($info['param_asso']) : array();
        }
        return $info;
    }

    public function count($condition)
    {
        $sql      = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'cell c  WHERE 1 ' . $condition;
        $totalNum = $this->db->query_first($sql);
        return $totalNum;
    }

    public function get_cell($condition)
    {
        $result = array();
        $sql    = "SELECT * FROM " . DB_PREFIX . "cell WHERE 1 " . $condition;
        $info   = $this->db->query($sql);
        while ($row    = $this->db->fetch_array($info))
        {
            $result[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['content_type']] = $row;
        }
        return $result;
    }

    public function getCellData($intCellId, $key = 'content_id')
    {
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache  = new Cache();
        $cache->initialize(CELL_DATA_CACHE);
        $ret = $cache->get($intCellId,'no_file_dir');
        if ($ret == 'no_file_dir')
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "cell_data WHERE cell_id = " . $intCellId;
            $q   = $this->db->query($sql);
            $ret = array();
            while ($row = $this->db->fetch_array($q))
            {
                $row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
                $ret[$row[$key]] = $row;
            }
            $cache->initialize(CELL_DATA_CACHE);
            $cache->set($intCellId, $ret);
        }
        return $ret;
    }
    
    public function delete_cell_data_cache($intCellId)
    {
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache  = new Cache();
        $cache->initialize(CELL_DATA_CACHE);
        $ret = $cache->delete($intCellId);
    }

}

?>