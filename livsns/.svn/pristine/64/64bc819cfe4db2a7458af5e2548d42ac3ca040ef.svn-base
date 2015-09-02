<?php
class searchtag extends InitFrm {
    
    
    function tag_list($condition, $fields = '*', $key = '') {
        $sql = 'SELECT ' . $fields . ' FROM ' . DB_PREFIX . 'searchtag WHERE 1 ' . $condition;
        $q = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
        	if (isset($row['tag_val'])) {
        		$row['tag_val'] = $row['tag_val'] ? unserialize($row['tag_val']) : array();
        	}
            if($key) {
                $ret[$row[$key]] = $row;
            }
            else {
                $ret[] = $row;
            }
        }   
        return $ret;
    }
    
    function count($condition) {
        $sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'searchtag WHERE 1 ' . $condition;
        $total = $this->db->query_first($sql);
        return $total;
    }
    
    
    function create($data) {
        if (empty($data)) {
            return false;
        }
        $tag_id = $this->db->insert_data($data, 'searchtag');    
        return $tag_id;    
    }
}
