<?php

class road extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
        include_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->material = new material();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show($con = "")
    {
        /*
          $sql = "SELECT r.*,g.title AS group_name,g.color,g.log AS glog FROM " . DB_PREFIX . "road r " .
          "LEFT JOIN ". DB_PREFIX ."group g " .
          "ON r.group_id = g.id " .
          " WHERE 1  " . $con;

         */
        $sql = "SELECT r.*,g.title AS group_name,g.color,g.log AS glog, a.id as areaid ,a.name as areaname" .
                " FROM " . DB_PREFIX . "road r " .
                " LEFT JOIN " . DB_PREFIX . "road_area ra " .
                " ON r.id = ra.rid " .
                " LEFT JOIN " . DB_PREFIX . "area a " .
                " ON ra.aid = a.id " .
                " LEFT JOIN " . DB_PREFIX . "group g " .
                " ON r.group_id = g.id " .
                " WHERE 1  " . $con;
        $q    = $this->db->query($sql);
        $data = array();
        while ($row  = $this->db->fetch_array($q))
        {
            $row['pic']          = json_decode($row['pic'], 1);
            $row['picsize']      = json_decode($row['picsize'], 1);
            $row['glog']         = json_decode($row['glog'], 1);
            $row['create_time']  = date('Y-m-d H:i', $row['create_time']);
            //$row['road_area'] = $this->get_road_area($row['id']);
            $ret                 = $data[$row['id']]['road_area'];
            $ret[$row['areaid']] = $row['areaname'];
            $row['road_area']    = $ret;
            //$row['road_area'] = $this->get_road_area($row['id']);
            $data[$row['id']]    = $row;
        }


        return $data;
    }

    public function show_cat()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "group  WHERE 1 ";
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $ret[$row['id']] = $row['title'];
        }
        return $ret;
    }

    //2013.07.12 scala
    public function show_area($con = "")
    {
        $sql  = "SELECT r.*,g.title AS group_name,g.color,g.log AS glog ,a.id as areaid,a.name as areaname FROM " . DB_PREFIX . "road_area ra " .
                " LEFT JOIN " . DB_PREFIX . "road r on ra.rid=r.id" .
                " LEFT JOIN " . DB_PREFIX . "group g on r.group_id=g.id" .
                " LEFT JOIN " . DB_PREFIX . "area a on ra.aid=a.id" .
                " WHERE 1  " . $con;
        $q    = $this->db->query($sql);
        $data = array();
        while ($row  = $this->db->fetch_array($q))
        {
            $row['pic']         = json_decode($row['pic'], 1);
            $row['picsize']     = json_decode($row['picsize'], 1);
            $row['glog']        = json_decode($row['glog'], 1);
            $row['create_time'] = date('Y-m-d H:i', $row['create_time']);
            $data[]             = $row;
        }
        return $data;
    }

    //每一级分类
    public function sort($id = 0, $exclude_id = 0, $flag = 0, $userInfor = '')
    {
        if ($exclude_id)
        {
            $cond = ' AND id NOT IN (' . $exclude_id . ')';
        }
        //$sql = 'SELECT * FROM '.DB_PREFIX.'area WHERE fid=' . intval($id) . $cond .' ORDER BY order_id ASC';
        $sql   = 'SELECT * FROM ' . DB_PREFIX . 'area  ORDER BY order_id ASC';
        $query = $this->db->query($sql);
        $k     = array();
        while (!false == ($row   = $this->db->fetch_array($query)))
        {
            $k[$row['id']] = $row;
        }
        return $k;
    }

    public function create($data)
    {
        if (empty($data))
        {
            return false;
        }
        $sql       = "INSERT INTO " . DB_PREFIX . "road SET ";
        $sql_extra = $space     = '';
        foreach ($data as $k => $v)
        {
            $sql_extra .= $space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .= $sql_extra;
        $this->db->query($sql);
        $data['id'] = $this->db->insert_id();
        if ($_FILES['Filedata'])
        {
            $material = $this->material->addMaterial($_FILES, 0, 0, 0); //插入各类服务器
            if ($material['id'])
            {
                $mat = array(
                    'host' => $material['host'],
                    'dir' => $material['dir'],
                    'filepath' => $material['filepath'],
                    'filename' => $material['filename'],
                );
                $sql = "UPDATE " . DB_PREFIX . "road SET pic_id=" . $material['id'] . ",pic='" . json_encode($mat) . "' ,local_img = 1 WHERE id=" . $data['id'];
                $this->db->query($sql);
            }
        }
        $sql = "UPDATE " . DB_PREFIX . "road SET orderid = " . $data['id'] . " WHERE id = " . $data['id'];
        $this->db->query($sql);
        return $data;
    }

    public function update($data, $con)
    {
        if (empty($data))
        {
            return false;
        }
        $sql   = "UPDATE " . DB_PREFIX . "road SET ";
        $space = '';
        foreach ($data as $k => $v)
        {
            $sql .= $space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .= $con;
        $this->db->query($sql);
        if ($_FILES['Filedata'])
        {
            $material = $this->material->addMaterial($_FILES, 0, 0, 0);
            if ($material['id'])
            {
                $mat = array(
                    'host' => $material['host'],
                    'dir' => $material['dir'],
                    'filepath' => $material['filepath'],
                    'filename' => $material['filename'],
                );
                $sql = "UPDATE " . DB_PREFIX . "road SET pic_id=" . $material['id'] . ",pic='" . json_encode($mat) . "', local_img = 1 " . $con;
                $this->db->query($sql);
            }
        }
        return $data;
    }

    public function delete($id)
    {
        if (empty($id))
        {
            return false;
        }
        $sql    = "SELECT * FROM " . DB_PREFIX . "road WHERE id IN (" . $id . ")";
        $q      = $this->db->query($sql);
        $pic_id = $space  = "";
        $ret    = array();
        while ($row    = $this->db->fetch_array($q))
        {
            if ($row['pic_id'])
            {
                $pic_id .= $space . $row['pic_id'];
                $space = ",";
            }
            $ret[] = $row['id'];
        }
        if ($pic_id)
        {
            $material = $this->material->delMaterialById($pic_id, 2);
        }
        $sql = "DELETE FROM " . DB_PREFIX . "road WHERE id IN (" . $id . ")";
        $this->db->query($sql);
        return $id;
    }

    //scala 2013.07.12
    public function delete_road_area($id)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "road_area WHERE rid IN (" . $id . ")";
        return $this->db->query($sql);
    }

    public function get_road_area($id)
    {
        $sql = "select a.id as areaid ,a.name as areaname  FROM " . DB_PREFIX . "road_area ra left join " . DB_PREFIX . "area a on ra.aid=a.id WHERE rid=$id";
        $q   = $this->db->query($sql);
        $ret = array();
        while (($row = $this->db->fetch_array($q)) != false)
        {
            $ret[$row['areaid']] = $row['areaname'];
            //$ret[] =$row['areaid'];
        }
        return $ret;
    }

    //scala 2013.07.12
    public function detail($con)
    {
        /*
          $sql = "SELECT r.*,a.name as arearname FROM " . DB_PREFIX . "road as r " .
          "left join ".DB_PREFIX."road_area as ra on r.id=ra.rid " .
          "left join ".DB_PREFIX."area as a on ra.aid=a.id WHERE 1 " . $con . " LIMIT 0,1";
         */
        $sql = "SELECT r.*  FROM " . DB_PREFIX . "road as r " .
                "left join " . DB_PREFIX . "road_area as ra on r.id=ra.rid " .
                "WHERE 1 " . $con . " LIMIT 0,1";
        $f   = $this->db->query_first($sql);

        if (!$f)
        {
            return false;
        }
        $id = $f['id'];

        $f['pic']        = json_decode($f['pic'], 1);
        $f['picsize']    = json_decode($f['picsize'], 1);
        $f['road_area']  = $this->get_road_area($id);
        $f['road_areas'] = $this->sort();
        return $f;
    }

    public function count($con)
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "road r WHERE 1 " . $con . " LIMIT 0,1";
        $f   = $this->db->query_first($sql);
        return $f;
    }

    public function audit($ids, $audit)
    {
        if (!$ids)
        {
            return false;
        }
        $arr_id = explode(',', $ids);
        if ($audit == 1) //审核操作
        {
            $sql     = "UPDATE " . DB_PREFIX . "road SET state = 1 WHERE id IN(" . $ids . ")";
            $this->db->query($sql);
            $sql     = "SELECT id,pic,picsize,local_img FROM " . DB_PREFIX . "road WHERE id IN(" . $ids . ") AND local_img = 0";
            $q       = $this->db->query($sql);
            $img     = array();
            $picSize = array();
            while ($row     = $this->db->fetch_array($q))
            {
                if (!$row['local_img'])
                {
                    $img[$row['id']]     = json_decode($row['pic'], 1);
                    $picSize[$row['id']] = json_decode($row['picsize'], 1);
                }
            }
            if ($img && is_array($img))
            {
                foreach ($img as $k => $v)
                {
                    if (!$v['filename'])
                    {
                        continue;
                    }
                    $url      = $v['host'] . $v['dir'] . $picSize[$k]['large'] . $v['filepath'] . $v['filename'];
                    $material = $this->material->localMaterial($url);
                    $material = $material[0];
                    if (!$material['error'])
                    {
                        $material = array(
                            'id' => $material['id'],
                            'host' => $material['host'],
                            'dir' => $material['dir'],
                            'filepath' => $material['filepath'],
                            'filename' => $material['filename'],
                        );
                        $sql      = "UPDATE " . DB_PREFIX . "road SET pic = '" . addslashes(json_encode($material)) . "', local_img = 1 WHERE id = " . $k;
                        $this->db->query($sql);
                    }
                }
            }
            return array('id' => $arr_id, 'status' => 1, 'opration' => '审核');
        }
        else if ($audit == 0) //打回操作
        {
            $sql = "UPDATE " . DB_PREFIX . "road SET state = 2 WHERE id IN(" . $ids . ")";
            $this->db->query($sql);
            return array('id' => $arr_id, 'status' => 2, 'opration' => '打回');
        }
    }

    public function getGroup()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "group WHERE 1 ORDER BY orderid desc";
        $q    = $this->db->query($sql);
        $data = array();
        while ($row  = $this->db->fetch_array($q))
        {
            $data[] = $row;
        }
        return $data;
    }

    //2013.07.11 scala 添加road_area数据
    public function add_road_area($rid, $areas)
    {
        $query = "insert into " . DB_PREFIX . "road_area (rid,aid) value ";
        foreach ($areas as $area)
        {
            $query .= "($rid,$area),";
        }
        $query = substr($query, 0, -1);
        return $this->db->query($query);
    }

    public function update_road_area_type($type)
    {
        $query = "update " . DB_PREFIX . "road_area set type=$type";
        $this->db->query($query);
        return $this->db->insert_id();
    }

    public function get_all_area()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "area WHERE 1 ORDER BY order_id ";
        $q    = $this->db->query($sql);
        $data = array();
        while ($row  = $this->db->fetch_array($q))
        {
            $data[] = $row;
        }
        return $data;
    }

}

?>
