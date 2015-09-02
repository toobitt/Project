<?php
/*******************************************************************
 * filename :Core.class.php
 * Created  :2014年3月11日,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 *
 ******************************************************************/
class Core extends InitFrm {
    private $transTimes = 0;
    
    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function insert($tbname, $data) {
        if (!trim($tbname)) {
            return false;
        }
        $query = "insert into " . DB_PREFIX . "$tbname set ";

        if (!is_array($data)) {
            $this -> db -> query($query . $data);
            return $this -> db -> insert_id();
        }

        foreach ($data as $field => $val) {
            $query .= "`$field` = '" . $val . "',";
        }
        $query = substr($query, 0, -1);
        $this -> db -> query($query);
        return $this -> db -> insert_id();
    }

    public function count($tbname, $cond = '') {
        if (!trim($tbname)) {
            return false;
        }
        $query = "select count(id) as total from " . DB_PREFIX . "$tbname $cond";
        $result = $this -> db -> query($query);
        return $this -> db -> fetch_array($result);
    }

    public function update($tbname, $data, $cond = '') {
        if (!trim($tbname) || !$cond) {
            return false;
        }

        $query = "update " . DB_PREFIX . "$tbname set ";

        if (is_string($data)) {
            $this -> db -> query($query . $data . $cond);
            return $this -> db -> affected_rows();
        }

        foreach ($data as $field => $val)
            $query .= "`$field` = '" . $val . "',";
        $query = substr($query, 0, -1);
        $this -> db -> query($query . $cond);
        return $this -> db -> affected_rows();

    }

    public function detail($tbname, $cond = '') {
        if (!trim($tbname) || !$cond) {
            return false;
        }
        $query = "select * from " . DB_PREFIX . "$tbname $cond";
        $result = $this -> db -> query($query);

        $row = $this -> db -> fetch_array($result);
        if (!$row) {
            return false;
        }
        $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
        $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
        return $row;
    }

    public function delete($tbname, $cond = '') {
        if (!trim($tbname) || !$cond) {
            return false;
        }
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

    public function query($query,$id='id') {
        $q = $this -> db -> query($query);
        $info = array();
        while (($row = $this -> db -> fetch_array($q)) != false) {
            $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
            $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
            if($id){
                $info[$row[$id]] = $row;
            }else{
                $info[] = $row;
            }
            
        }
        return $info;
    }
    
    public function query_update($query)
    {
        $q = $this -> db -> query($query);
        return $this -> db -> affected_rows();
    }
    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function transaction_begin() {
        if ($this->transTimes == 0) {
            $this->db->query('SET AUTOCOMMIT=0');
            $this->db->query('START TRANSACTION');
        }
        $this->transTimes++;
        return ;
    }
    
    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return boolen
     */
    public function transaction_end() {
        if ($this->transTimes > 0) {
            $result = $this->db->query('COMMIT');
            $this->transTimes = 0;
            if(!$result){
                return false;
            }
        }
        return true;
    }
    /**
     * 事务回滚
     * @access public
     * @return boolen
     */
    public function transaction_rollback() {
        if ($this->transTimes > 0) {
            $result = $this -> db ->query('ROLLBACK');
            $this->transTimes = 0;
            if(!$result){
                return false;
            }
        }
        return true;
    }
    
    public function exchange_code_verify($exchange_code)
    {
    	if(!$exchange_code)
    	{
    		return false;
    	}
    	
    	$sql = "SELECT id,delivery_tracing FROM " . DB_PREFIX . "order WHERE exchange_code = '{$exchange_code}'";
    	$res = $this->db->query_first($sql);
    	
    	if($res['id'])
    	{
    		return $res;
    	}
    	else 
    	{
    		return false;
    	}
    }
}
?>
