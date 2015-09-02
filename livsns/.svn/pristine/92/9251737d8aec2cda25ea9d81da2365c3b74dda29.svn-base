<?php
/**
 * Created by livsns.
 * User: wangleyuan
 * Date: 14-5-6
 * Time: 上午9:42
 */

class GoodMode extends InitFrm{

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

        $sql = 'SELECT g.*, n.name, m.pic AS indexpic_url FROM '.DB_PREFIX.'goods g
                LEFT JOIN '.DB_PREFIX.'node  n
                    ON g.node_id = n.id
                LEFT JOIN '.DB_PREFIX.'materials m
                    ON g.indexpic = m.id' .  $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false ) {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
              $ret[] = $row;
            }
        }
        return $ret;
    }


    public function getOne($where = '', $order = '', $group = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = ' LIMIT 1 ';

        $sql = 'SELECT g.*, n.name, m.pic AS indexpic_url , gd.brief, gd.exchange_state, gd.exchange_rule
                FROM '.DB_PREFIX.'goods g
                LEFT JOIN '.DB_PREFIX.'node  n
                    ON g.node_id = n.id
                LEFT JOIN '.DB_PREFIX.'materials m
                    ON g.indexpic = m.id
                LEFT JOIN '.DB_PREFIX.'good_detail gd
                    ON g.id = gd.good_id' .  $where . $order . $group . $limit;
        $ret = $this->db->query_first($sql);

        return $ret;
    }

    public function count($where = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;

        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'goods g' . $where;

        $total = $this->db->query_first($sql);

        return $total;
    }


    public function insert($data, $replace = false) {
        if (!is_array($data) || count($data) < 0 ) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, 'goods', $replace);

        if (!$insert_id) {
            return false;
        }

        //更改排序id
        $this->db->update_data(array('order_id' => $insert_id), 'goods', ' id = ' . $insert_id);

        return $insert_id;
    }

    public function insert_detail($data, $replace = false) {
        if (!is_array($data) || count($data) < 0) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, 'good_detail', $replace);

        if (!$insert_id) {
            return false;
        }
        return $insert_id;
    }


    /**
     * 用户商品购买记录表
     * @param $data
     * @param bool $replace
     * @return bool
     */
    public function insert_amount($data, $replace = false) {
        if (!is_array($data) || count($data) < 0) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, 'amount_selled', $replace);

        if (!$insert_id) {
            return false;
        }
        return $insert_id;
    }

    /**
     * 活动周期购买记录表
     * @param $data
     * @param bool $replace
     * @return bool
     */
    public function insert_period($data, $replace = false) {
        if (!is_array($data) || count($data) < 0) {
            return false;
        }
        $insert_id = $this->db->insert_data($data, 'period_selled', $replace);

        if (!$insert_id) {
            return false;
        }
        return $insert_id;
    }

    /**
     * @param $data
     * @param string $where ' id= 1 AND good_id = 2'
     * @return bool
     */
    public function update($data, $where = '') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,'goods', $where);
    }

    public function update_detail($data, $where = '') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,'good_detail', $where);
    }

    /**
     * @param $data
     * @param string $where ' id= 1 AND good_id = 2'
     * @return bool
     */
    public function update_amount($data, $where = '') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,'amount_selled', $where);
    }

    /**
     * @param $data
     * @param string $where ' id= 1 AND good_id = 2'
     * @return bool
     */
    public function update_period($data, $where = '') {
        if ($data == '' || count($data) < 0 || $where == '') {
            return false;
        }
        return $this->db->update_data($data,'period_selled', $where);
    }


    public function delete ($where) {
        if ($where == '') {
            return false;
        }

        $where = ' WHERE 1 ' . $where;

        $sql = 'DELETE g,gd,m FROM '.DB_PREFIX.'goods g
                LEFT JOIN '.DB_PREFIX.'good_detail gd
                    ON g.id = gd.good_id
                LEFT JOIN ' .DB_PREFIX. 'materials m
                    ON g.id = m.good_id' . $where;
        $this->db->query($sql);
        return true;
    }


    public function getGoodPeriod($good_id, $period, $week) {
        if (!$good_id || !$period) {
            return false;
        }
        $where = ' AND good_id = ' . intval($good_id) . ' AND period = ' . $period .' AND week = ' . intval($week);
        $where = ' WHERE 1 ' . $where;

        $sql = 'SELECT id, good_id, period, week, num FROM '.DB_PREFIX.'period_selled ' . $where;

        return $this->db->query_first($sql);
    }

    public function getGoodUser($user_id, $good_id) {
        if (!$user_id || !$good_id ) {
            return false;
        }

        $where = ' AND user_id = ' . intval($user_id) . ' AND good_id = ' . $good_id;
        $where = 'WHERE 1 ' . $where;
        
        $sql = 'SELECT id, user_id, good_id, num FROM ' .DB_PREFIX. 'amount_selled ' . $where;
        return $this->db->query_first($sql);
    }

} 