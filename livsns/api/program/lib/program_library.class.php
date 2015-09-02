<?php
class programLibrary extends InitFrm
{
    
    public function getList($condition, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX .'program_library WHERE 1 ' . $condition;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
            $row['week_day'] = $row['week_day'] ? unserialize($row['week_day']) : array();
            $ret[] = $row;
        }
        return $ret;
    }
    
    public function getOneById($id, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'program_library WHERE 1 AND id = ' . $id;
        $ret = $this->db->query_first($sql);
        if ($ret) {
            $ret['indexpic'] = $ret['indexpic'] ? unserialize($ret['indexpic']) : array();
            $ret['week_day'] = $ret['week_day'] ? unserialize($ret['week_day']) : array();
        }
        return $ret;
    }    
}

?>