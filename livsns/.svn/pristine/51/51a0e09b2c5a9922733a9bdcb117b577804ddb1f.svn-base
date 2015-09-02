<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-6
 * Time: 上午10:32
 */

class MaterialMode extends InitFrm {

    public function __construct() {
        parent::__construct();
    }

    public function __destruct() {
        parent::__destruct();
    }


    public function select($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT * FROM '.DB_PREFIX.'materials' . $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]] = $row;
            } else {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    public function insert($data, $replace = false) {
        if (!is_array($data) || count($data) < 0) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, 'materials', $replace);

        if (!$insert_id) {
            return false;
        }

        return $insert_id;
    }

    public function update($data, $where = '') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,'materials', $where);
    }

    public function delete ($where) {
        if ($where == '') {
            return false;
        }

        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE FROM '.DB_PREFIX.'materials' . $where;
        $this->db->query($sql);
        return true;
    }

} 