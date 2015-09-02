<?php
class videoop extends initFrm
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function __destruct()
    {
        parent::__construct();
    }
    
    public function getContentList($condition, $field = '*')
    {
        $sql = "SELECT " .$field. " FROM " .DB_PREFIX. "videoop WHERE 1 " . $condition;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            if ($row['create_time'])
                $row['create_time_show'] = date('Y-m-d H:i', $row['create_time']);
            if ($row['update_time'])
                $row['update_time_show'] = date('Y-m-d H:i', $row['update_time']);
            $row['status'] = $this->settings['status_config'][$row['state']];
            $row['site_info'] = $row['site_info'] ? unserialize($row['site_info']) : array();
            $ret[] = $row;
        }
        return $ret;
    }
    
    public function getContentById($id, $field = '*')
    {
        $sql = "SELECT ".$field." FROM " . DB_PREFIX . "videoop WHERE id = " . $id;
        $ret = $this->db->query_first($sql);
        if ($ret)
        {
            
        }
        return $ret;
    }
    
    public function getTotalNum($condition)
    {
        $sql = "SELECT COUNT(*) AS total FROM ".DB_PREFIX."videoop WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        return $total;
    }
    
    public function delConByIds($ids)
    {
        if (!$ids) {
            return false;
        }
        $sql = "DELETE FROM ".DB_PREFIX."videoop WHERE id IN(".$ids.")";
        $this->db->query($sql);
    }
}

?>