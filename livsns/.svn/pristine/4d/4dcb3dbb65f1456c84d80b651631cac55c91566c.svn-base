<?php
/*******************************************************************
 * filename :Core.class.php
 * Created  :2014年01月22日, by Scala
 *
 ******************************************************************/
class Dao extends InitFrm {
    public function __construct() {
        parent::__construct();
    }

    public function insert($tbname, $data) {
        if (!trim($tbname)) {
            return false;
        }
        $query = "insert into " . DB_PREFIX . "$tbname set ";

        if (!is_array($data)) {
            $query = $query . $data;
        } else {
            foreach ($data as $field => $val) {
                $query .= "`$field` = '" . $val . "',";
            }
            $query = substr($query, 0, -1);
        }
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
            $query = $query . $data . $cond;
        } else {
            foreach ($data as $field => $val)
                $query .= "`$field` = '" . $val . "',";
            $query = substr($query, 0, -1) . $cond;
        }
        $this -> db->query($query);
        return $this -> db -> affected_rows();
    }

    public function delete($tbname, $cond = '') {
        if (!trim($tbname) || !$cond) {
            return false;
        }
        $query = "delete from " . DB_PREFIX . "$tbname $cond";
        $this -> db -> query($query);
        return $this -> db -> affected_rows();
    }

    public function show($tbname, $cond = '', $fields = '*', $need_id = false) {
        $query = "select $fields from " . DB_PREFIX . "$tbname $cond";
        $q = $this -> db -> query($query);
        $info = array();
        if ($need_id) {
            while (($row = $this -> db -> fetch_array($q)) != false) {
                $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
                $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
                $info[$row['id']] = $row;
            }
        } else {
            while (($row = $this -> db -> fetch_array($q)) != false) {
                $row['create_time'] = date("Y-m-d H:i", $row['create_time']);
                $row['update_time'] = date("Y-m-d H:i", $row['update_time']);
                $info[] = $row;
            }
        }
        return $info;
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

    /**
     * 启动事务
     * @access public
     * @return void
     */
    public function transaction_begin() {
        if ($this -> transTimes == 0) {
            $this -> db -> query('SET AUTOCOMMIT=0');
            $this -> db -> query('START TRANSACTION');
        }
        $this -> transTimes++;
        return;
    }

    /**
     * 用于非自动提交状态下面的查询提交
     * @access public
     * @return boolen
     */
    public function transaction_end() {
        if ($this -> transTimes > 0) {
            $result = $this -> db -> query('COMMIT');
            $this -> transTimes = 0;
            if (!$result) {
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
        if ($this -> transTimes > 0) {
            $result = $this -> db -> query('ROLLBACK');
            $this -> transTimes = 0;
            if (!$result) {
                return false;
            }
        }
        return true;
    }

}
?>