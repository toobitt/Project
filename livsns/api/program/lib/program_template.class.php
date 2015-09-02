<?php
class programTemplate extends InitFrm
{
    
    public function getList($condition, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX .'program_template WHERE 1 ' . $condition;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['data'] = $row['data'] ? unserialize($row['data']) : array();
            $row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
            $ret[] = $row;
        }
        return $ret;
    }
    
    public function getOneById($id, $fields = '*') {
        $sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'program_template WHERE 1 AND id = ' . $id;
        $ret = $this->db->query_first($sql);
        if ($ret) {
            $ret['data'] = $ret['data'] ? unserialize($ret['data']) : array();
            $ret['indexpic'] = $ret['indexpic'] ? unserialize($ret['indexpic']) : array();
        }
        return $ret;
    }
    
    public function getProTemRelation($condition, $fields = '*') {
        $sql = 'SELECT ' .$fields . ' FROM ' . DB_PREFIX .'program_template_relation WHERE 1 ' . $condition;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row=$this->db->fetch_array($q)) {
            $ret[] = $row;
        }
        return $ret;
    }
    
}

?>