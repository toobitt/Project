<?php

class user_mode extends InitFrm{

    function __construct() {
    	parent::__construct();
    }
    
    
    function get_user() {
    	
    	$sql = "SELECT id, group_id, circle_id, name, since_id  FROM ".DB_PREFIX."user WHERE 1 LIMIT 0, 1000";
    	$q = $this->db->query($sql);
    	$ret = array();
    	while ($row = $this->db->fetch_array($q)) {
    		$row['circle_id'] = $row['circle_id'] ? unserialize($row['circle_id']) : array();
    		$ret[$row['name']] = $row;
    	}
    	
    	return $ret;
    }
}
?>