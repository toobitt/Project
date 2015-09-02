<?php
/*******************************************************************
 * filename :Core.class.php
 * Created  :2013年12月20日,Writen by scala
 * 
 ******************************************************************/
class Core extends InitFrm {
    public function __construct() {
        parent::__construct();
    }
    
    public function insert($tbname, $data) {
        
        $query = "insert into " . DB_PREFIX . "$tbname set ";

        foreach ($data as $field => $val) {
            $query .= "`$field` = '" . $val . "',";
        }
        $query = substr($query, 0, -1);
        
        $this -> db -> query($query);
        
        $data['id'] = $this -> db -> insert_id();
        
        return $data;
    }
    
    public function count($tbname, $cond = '') {
        $query = "select count(id) as total from " . DB_PREFIX . "$tbname $cond";
        $result = $this -> db -> query($query);
        return $this -> db -> fetch_array($result);
    }

    public function update($tbname, $data, $cond = '') {
        
        $query = "update " . DB_PREFIX . "$tbname set ";

        foreach ($data as $field => $val)
            $query .= "`$field` = '" . $val . "',";
        $query = substr($query, 0, -1);
        $this -> db -> query($query . $cond);
        $data['affected_row'] = $this -> db -> affected_rows();
        return $data;
    }

    public function detail($tbname, $cond = '') {
        
        $query = "select * from " . DB_PREFIX . "$tbname $cond";
        $result = $this -> db -> query($query);
        
        if (!($row=$this -> db -> fetch_array($result))) {
            return array();
        }
        $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
        $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
        return $row;
    }

    public function delete($tbname, $cond = '') {
        $query = "delete from " . DB_PREFIX . "$tbname $cond";
        $this -> db -> query($query);
        return $this -> db -> affected_rows();
    }

    public function show($tbname, $cond = '', $fields = '*') {
        $query = "select $fields from " . DB_PREFIX . "$tbname $cond";

        $q = $this -> db -> query($query);
        $info = array();
        while (($row = $this -> db -> fetch_array($q)) != false) {
            $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
            $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
            $info[$row['id']] = $row;
        }
        return $info;
    }

    public function query($query) {
        $q = $this -> db -> query($query);
        $info = array();
        while (($row = $this -> db -> fetch_array($q)) != false) {
            $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
            $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
            $info[$row['id']] = $row;
        }
        return $info;
    }

    public function __destruct() {
        parent::__destruct();
    }
}
?>
