<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/27
 * Time: 上午1:08
 */

class ReceiveAddress extends InitFrm
{
    function __construct()
    {
        parent::__construct();
    }
    function __destruct()
    {
        parent::__destruct();
    }

    public function select($where = '', $order = '', $limit = '', $group = '', $key = '') {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $group = $group == '' ? '' : ' GROUP BY ' . $group;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = 'SELECT *  FROM '.DB_PREFIX.'receive_address'
            . $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        $trade_numbers = array();
        while( ($row = $this->db->fetch_array($q)) != false )
        {
            if ($key) {
                $ret[$row[$key]][] = $row;
            } else {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    function detail($id)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."receive_address
        WHERE id = '".$id."'";
        $address = $this->db->query_first($sql);

        if ( !empty($address) )
        {

        }
        return $address;
    }


    function delete($ids)
    {
        if (is_array($ids))
        {
            $ids = implode(',', $ids);
        }

        $sql = "DELETE FROM ".DB_PREFIX."receive_address WHERE id IN (".$ids.")";
        $this->db->query_first($sql);

        return true;
    }

    function create($data)
    {
        if ($this->db->insert_data($data, 'receive_address'))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function update($data, $condition)
    {
        if ( $this->db->update_data($data, 'receive_address', $condition) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}