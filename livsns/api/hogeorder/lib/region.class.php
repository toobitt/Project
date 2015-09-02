<?php

class Region extends InitFrm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }


    //获取省信息
    public function get_province($where = '', $order = '', $limit = '',$flag='')
    {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = "SELECT * FROM ". DB_PREFIX . "province " .  $where . $order . $limit;
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            if($flag)
            {
                $ret[$r['id']] = $r['name'];
            }
            else
            {
                $re['id'] = $r['id'];
                $re['name'] = $r['name'];
                $ret[] = $re;
            }
        }
        return $ret;
    }

    //获取市信息
    public function get_city($where = '', $order = '', $limit = '',$flag='')
    {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = "SELECT * FROM ". DB_PREFIX . "city " .  $where . $order . $limit;
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            if($flag)
            {
                $city[$r['id']] = $r['city'];
            }
            else
            {
                $re = $r;
                $re['name'] = $r['city'];
                $city[] = $re;
            }
        }
        return $city;
    }

    //获取区域信息
    public function get_area($where = '', $order = '', $limit = '', $flag='')
    {
        $where = $where == '' ? '' : ' WHERE 1 ' . $where;
        $order = $order == '' ? '' : ' ORDER BY ' . $order;
        $limit = $limit == '' ? '' : ' LIMIT ' . $limit;

        $sql = "SELECT * FROM ". DB_PREFIX . "area " .  $where . $order . $limit;
        $q = $this->db->query($sql);
        while($r = $this->db->fetch_array($q))
        {
            if($flag)
            {
                $area[$r['id']] = $r['area'];
            }
            else
            {
                $re = $r;
                $re['name'] = $r['area'];
                $area[] = $re;
            }
        }
        return $area;
    }

}
?>