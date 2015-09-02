<?php

//地铁的数据库操作
class subway extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //新增地铁线路
    public function create($info)
    {
        //插入数据操作
        $sql       = "INSERT INTO " . DB_PREFIX . "subway SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    //更新地铁线路相关信息
    public function update($info)
    {
        //更新数据操作
        $sql       = "UPDATE " . DB_PREFIX . "subway SET ";
        $sql_extra = $space     = '';
        foreach ($info as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id =" . $info['id'];
        $this->db->query($sql);
    }

    //删除地铁线路
    public function delete($ids)
    {
        $sqll = "select * from " . DB_PREFIX . "subway where id in(" . $ids . ")";
        $ret  = $this->db->query($sqll);
        while ($row  = $this->db->fetch_array($ret))
        {
            $pre[] = $row;
        }

        $sql = "DELETE FROM " . DB_PREFIX . "subway WHERE id IN (" . $ids . ")";
        $this->db->query($sql);


        if (count($pre) > 1)
        {
            $this->addLogs('删除地铁线路', $pre, '', '删除地铁线路  ' . $ids);
        }
        else
        {
            $name = $pre[0]['title'];
            $this->addLogs('删除地铁线路', $pre, '', '删除地铁线路  ' . $name);
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //删除地铁线路站点
    public function delete_site($id, $sub_id)
    {
        $this->delete_data(' AND sub_id =' . $sub_id . ' AND site_id = ' . $id, 'subway_relation');

        $count = $this->get_count('count(*) as num', 'subway_relation', " WHERE sub_id  = " . $sub_id . " AND direction = 'end'");
        $this->update_data(array('site_count' => $count['num']), 'subway', " id IN({$sub_id})");

        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway_relation 
				WHERE site_id = " . $id;
        $q   = $this->db->query_first($sql);
        if (!$q)
        {
            $s        = "SELECT * FROM " . DB_PREFIX . "subway_site WHERE id = " . $id;
            $pre_data = $this->db->query_first($s);

            $this->addLogs('删除地铁站点', $pre_data, '', $pre_data['title']);

            $this->delete_data(' AND id = ' . $id, 'subway_site');
        }

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    //根据条件查询地铁
    public function show($condition, $limit)
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway 
				WHERE 1" . $condition . ' ORDER BY order_id DESC ' . $limit;
        $q   = $this->db->query($sql);

        $sites = $sorts = array();
        $sites = $this->get_subway_site();

        $sorts = $this->get_subway_sort();

        while ($row = $this->db->fetch_array($q))
        {
            if ($sorts && is_array($sorts))
            {
                $row['sort_name'] = $sorts[$row['sort_id']];
            }

            if ($sites && is_array($sites))
            {
                $row['start'] = $sites[$row['start']];
                $row['end']   = $sites[$row['end']];
            }

            $row['cre_time']   = date("Y-m-d H:i", $row['create_time']);
            $row['direction']  = $row['start'] . '-' . $row['end'];
            $row['is_operate'] = $row['is_operate'] ? '运行' : '在建';
            switch ($row['state'])
            {
                case 0 :
                    $row['audit']  = $row['status'] = '待审核';
                    break;
                case 1 :
                    $row['audit']  = $row['status'] = '已审核';
                    break;
                default:
                    $row['audit']  = $row['status'] = '已打回';
                    break;
            }
            $ret[] = $row;
        }

        return $ret;
    }

    public function insert_img($data = array())
    {
        if (!$data)
        {
            return false;
        }
        $sql = " INSERT INTO " . DB_PREFIX . "subway_materials SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql = trim($sql, ',');
        $this->db->query($sql);
        $vid = $this->db->insert_id();
        return $vid;
    }

    //删除图片
    public function deleteMaterials($ids)
    {
        $sql = 'DELETE FROM ' . DB_PREFIX . 'subway_materials WHERE id IN (' . $ids . ')';
        $this->db->query($sql);
        return $ids;
    }

    public function update_data($data, $table, $where = '')
    {
        if ($table == '' && $where == '')
        {
            return false;
        }
        $where = ' WHERE ' . $where;
        $field = '';
        if (is_string($data) && $data != '')
        {
            $field = $data;
        }
        elseif (is_array($data) && count($data) > 0)
        {
            $fields = array();
            foreach ($data as $k => $v)
            {
                $fields[] = $k . "='" . $v . "'";
            }
            $field = implode(',', $fields);
        }
        else
        {
            return false;
        }
        $sql = 'UPDATE ' . DB_PREFIX . $table . ' SET ' . $field . $where;
        $this->db->query($sql);
        return $this->db->affected_rows();
    }

    public function insert_data($data, $table)
    {
        if (!$table)
        {
            return false;
        }
        $sql = "INSERT INTO " . DB_PREFIX . $table . " SET ";
        if (is_array($data))
        {
            $sql_extra = $space     = ' ';
            foreach ($data as $k => $v)
            {
                $sql_extra .=$space . $k . "='" . $v . "'";
                $space = ',';
            }
            $sql .=$sql_extra;
        }
        else
        {
            $sql .= $data;
        }
        $this->db->query($sql);
        return $this->db->insert_id();
    }

    public function delete_data($condition, $table)
    {
        $sql = "DELETE FROM " . DB_PREFIX . $table . " WHERE 1 " . $condition;
        $this->db->query($sql);
    }

    public function insert_subway_site_exinfo($site_id, $gate_id, $info)
    {
        $type_id      = $info['type_id'];
        $station_name = $info['station_name'];
        $station_id   = $info['station_id'];
        $brief        = $info['brief'];
        $sql          = "INSERT INTO " . DB_PREFIX . "subway_site_exinfo(
					site_id,
					gate_id,
					type,
					type_id,
					station_name,
					station_id,
					brief)VALUES";
        for ($i = 0; $i < count($type_id); $i++)
        {
            if ($type_id[$i] && $type_id[$i] != '-1')
            {
                $sql .="   (
					'$site_id',
					'$gate_id',
					'3',
					'{$type_id[$i]}',
					'{$station_name[$i]}',
					'{$station_id[$i]}',
					'{$brief[$i]}'),";
            }
        }
        $sql_ = substr("$sql", 0, -1);
        $this->db->query($sql_);
        return $this->db->insert_id();
    }

    public function get_subway_service_sort($limit = '')
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway_service_sort 
				WHERE 1" . ' ORDER BY order_id DESC ' . $limit;
        $q   = $this->db->query($sql);

        $re     = $return = array();
        while ($row    = $this->db->fetch_array($q))
        {

            $re['id']    = $row['column_id'];
            $re['title'] = $row['title'];
            $re['type']  = $row['type'];
            $re['sign']  = $row['sign'];
            $return[]    = $re;
        }
        return $return;
    }

    public function get_subway_service_sort_by_sign($sign)
    {
        $sql  = "SELECT id
				FROM  " . DB_PREFIX . "subway_service_sort 
				WHERE sign = '" . $sign . "'";
        $sort = $this->db->query_first($sql);

        return $sort['id'];
    }

    public function get_subway_sort()
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway_sort 
				WHERE 1";
        $q   = $this->db->query($sql);

        while ($row = $this->db->fetch_array($q))
        {
            $re[$row['id']] = $row['name'];
        }
        return $re;
    }

    public function get_subway()
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway 
				WHERE 1";
        $q   = $this->db->query($sql);

        $sites = $this->get_subway_site();
        while ($row   = $this->db->fetch_array($q))
        {
            $re[$row['id']] = array(
                'title' => $row['title'],
                'color' => $row['color'],
                'start' => $sites[$row['start']],
                'end' => $sites[$row['end']],
            );
        }
        return $re;
    }

    public function get_subway_site()
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway_site
				WHERE 1";
        $q   = $this->db->query($sql);

        while ($row = $this->db->fetch_array($q))
        {
            $re[$row['id']] = $row['title'];
        }
        return $re;
    }

    public function get_count($field, $table, $where = '')
    {
        $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . $table . $where;
        $info = $this->db->query_first($sql);
        return $info;
    }

    //根据条件查询地铁
    public function get_subway_list($condition, $limit, $need_site = '')
    {
        $sql = "SELECT *
				FROM  " . DB_PREFIX . "subway 
				WHERE 1" . $condition . ' ORDER BY order_id DESC ' . $limit;
        $q   = $this->db->query($sql);

        $sites = $sorts = $re    = array();
        $sites = $this->get_subway_site();
        while ($row   = $this->db->fetch_array($q))
        {
            $re = array(
                'id' => $row['id'],
                'title' => $row['title'],
                'color' => $row['color'],
                'sign' => $row['sign'],
                'start_egname' => $row['start_egname'],
                'end_egname' => $row['end_egname'],
            );

            if ($sites && is_array($sites))
            {
                $re['start'] = $sites[$row['start']];
                $re['end']   = $sites[$row['end']];
            }
            if ($need_site)
            {
                $site_color = $this->get_site_color(1);
                $direction  = 'end';
                $sql_       = "SELECT b.*,a.start as start_time,a.end as end_time FROM  " . DB_PREFIX . "subway_relation a " .
                        "LEFT JOIN " . DB_PREFIX . "subway_site b ON a.site_id = b.id" . " WHERE a.sub_id = " . $row['id'] . " AND a.direction = '" . $direction . "' ORDER BY a.order_id ASC";
                $q_         = $this->db->query($sql_);

                while ($row_ = $this->db->fetch_array($q_))
                {
                    $row_['subway'] = $site_color[$row_['id']]['subname'];
                    $row_['sub_color'] = $site_color[$row_['id']]['color'];
                    if (count($row_['sub_color']) > 1)
                    {
                        $row_['is_hub'] = 1;
                    }
                    else
                    {
                        $row_['is_hub'] = 0;
                    }
                    $site[] = $row_;
                }
                $re['site_info'] = $site;
            }

            $ret[] = $re;
        }
        return $ret;
    }

    //根据条件查询地铁
    public function get_subway_info($id, $need_site = '')
    {
        $re  = array();
        $sql = 'SELECT *
				FROM ' . DB_PREFIX . 'subway WHERE id = ' . $id;
        $ret = $this->db->query_first($sql);

        $sites = $sorts = $re    = array();
        $sites = $this->get_subway_site();

        $sq               = "select name from " . DB_PREFIX . "subway_sort where id = " . $ret['sort_id'];
        $sort_name        = $this->db->query_first($sq);
        $ret['sort_name'] = $sort_name['name'];
        $ret['start']     = $sites[$ret['start']];
        $ret['end']       = $sites[$ret['end']];

        //取所有的素材
        $sqlm = 'SELECT * FROM ' . DB_PREFIX . 'subway_materials WHERE cid = ' . $id . ' AND cid_type = 1';
        $qm   = $this->db->query($sqlm);
        while ($rm   = $this->db->fetch_array($qm))
        {
            if ($rm['id'] && $rm['mark'] == 'img')
            {
                $ret['indexpic'][] = array(
                    'id' => $rm['id'],
                    'host' => $rm['host'],
                    'dir' => $rm['dir'],
                    'filepath' => $rm['filepath'],
                    'filename' => $rm['filename'],
                    'imgwidth' => $rm['imgwidth'],
                    'imgheight' => $rm['imgheight'],
                );
            }
        }
        if ($need_site)
        {
            $site_color = $this->get_site_color(1);

            $direction = 'end';
            $sql       = "SELECT b.*,a.start as start_time,a.end as end_time FROM  " . DB_PREFIX . "subway_relation a " .
                    "LEFT JOIN " . DB_PREFIX . "subway_site b ON a.site_id = b.id" . " WHERE a.sub_id = " . $id . " AND a.direction = '" . $direction . "' ORDER BY a.order_id ASC";
            $q         = $this->db->query($sql);

            while ($row = $this->db->fetch_array($q))
            {
                $row['sub_color'] = $site_color[$row['id']]['color'];
                if (count($row['sub_color'] > 1))
                {
                    $row['is_hub'] = 1;
                }
                $site[] = $row;
            }
            $ret['site_info']['end'] = $site;

            $direction_ = 'start';
            $sql_       = "SELECT b.*,a.start as start_time,a.end as end_time FROM  " . DB_PREFIX . "subway_relation a " .
                    "LEFT JOIN " . DB_PREFIX . "subway_site b ON a.site_id = b.id" . " WHERE a.sub_id = " . $id . " AND a.direction = '" . $direction_ . "' ORDER BY a.order_id DESC";
            $q_         = $this->db->query($sql_);

            while ($row_ = $this->db->fetch_array($q_))
            {
                $row_['sub_color'] = $site_color[$row_['id']]['color'];
                if (count($row_['sub_color'] > 1))
                {
                    $row_['is_hub'] = 1;
                }
                $site_[] = $row_;
            }
            $ret['site_info']['start'] = $site_;
        }
        return $ret;
    }

    //根据条件查询地铁站点
    public function get_subway_sites($condition, $limit, $info = array())
    {

        $sql = "SELECT DISTINCT a.*
				FROM  " . DB_PREFIX . "subway_site a
				LEFT JOIN " . DB_PREFIX . "subway_relation b on a.id = b.site_id
				LEFT JOIN " . DB_PREFIX . "subway c on c.id = b.sub_id
				WHERE 1" . $condition . ' AND c.state=1 ORDER BY id DESC ' . $limit;

        $q = $this->db->query($sql);

        $site_color = $ret        = $retu       = $distance   = array();
        $site_color = $this->get_site_color(1);

        if ($info['latitude'] && $info['longitude'])
        {
            while ($row = $this->db->fetch_array($q))
            {
                $suname           = $color            = array();
                $suname           = $site_color[$row['id']]['subname'];
                $color            = $site_color[$row['id']]['color'];
                $row['subway']    = $suname;
                $row['sub_color'] = $color;
                if ($row['latitude'] && $row['longitude'])
                {
                    $row['distance'] = GetDistance($info['latitude'], $info['longitude'], $row['latitude'], $row['longitude']);
                    if ($row['distance'])
                    {
                        $retu[$row['distance']] = $row;
                        $distance[]             = $row['distance'];
                    }
                    else
                    {
                        $retu[0]    = $row;
                        $distance[] = 0;
                    }
                }
            }
            asort($distance, SORT_NUMERIC);
            $key   = array_shift($distance);
            //$retu[$key]['distance']	= $this->get_distance($retu[$key]['distance']);
            $ret[] = $retu[$key];
        }
        else
        {
            while ($row = $this->db->fetch_array($q))
            {
                $suname           = $color            = array();
                $suname           = $site_color[$row['id']]['subname'];
                $color            = $site_color[$row['id']]['color'];
                $row['subway']    = $suname;
                $row['sub_color'] = $color;
                $ret[]            = $row;
            }
        }
        foreach($ret as $k=>$v)
        {
            $v['distance'] = intval($v['distance']);
            if($v['distance']>=1000)
            {
                $v['distance'] = $v['distance']/1000;
                $ret[$k]['distance'] = sprintf("%.2f", $v['distance']).' km';
            }    
            else
            {
                $ret[$k]['distance'] .= ' m';
            }
        }
        return $ret;
    }

    //根据条件查询地铁站点详情
    public function get_subway_site_info($id)
    {
        $re  = array();
        $sql = 'SELECT *
				FROM ' . DB_PREFIX . 'subway_site WHERE id = ' . $id;
        $ret = $this->db->query_first($sql);

        $subways    = $suname     = $color      = array();
        $site_color = $this->get_site_color(1);

        $suname           = $site_color[$ret['id']]['subname'];
        $color            = $site_color[$ret['id']]['color'];
        $ret['subway']    = $suname;
        $ret['sub_color'] = $color;

        $subways = $this->get_subway();
        //取所有的素材
        $sqlm    = 'SELECT * FROM ' . DB_PREFIX . 'subway_materials WHERE cid = ' . $id . ' AND cid_type = 2 order by id';
        $qm      = $this->db->query($sqlm);
        while ($rm      = $this->db->fetch_array($qm))
        {
            if ($rm['id'] && $rm['mark'] == 'img')
            {
                $ret['indexpic'][] = array(
                    'id' => $rm['id'],
                    'host' => $rm['host'],
                    'dir' => $rm['dir'],
                    'filepath' => $rm['filepath'],
                    'filename' => $rm['filename'],
                    'imgwidth' => $rm['imgwidth'],
                    'imgheight' => $rm['imgheight'],
                );
            }
        }

        $sql_ = "SELECT *
				 FROM  " . DB_PREFIX . "subway_relation 
				 WHERE site_id = " . $id;
        $q_   = $this->db->query($sql_);

        while ($row_ = $this->db->fetch_array($q_))
        {
            $color                            = $subways[$row_['sub_id']]['color'];
            $row_['direction_name']           = $subways[$row_['sub_id']][$row_['direction']];
            $site[$color][$row_['direction']] = $row_;
        }
        if ($site && is_array($site))
        {
            foreach ($site as $key => $val)
            {
                $arr['color'] = $key;
                $arr['start'] = array(
                    'station' => $val['start']['direction_name'],
                    'start_time' => $val['start']['start'],
                    'end_time' => $val['start']['end'],
                );
                $arr['end']   = array(
                    'station' => $val['end']['direction_name'],
                    'start_time' => $val['end']['start'],
                    'end_time' => $val['end']['end'],
                );
                $train[]      = $arr;
            }
        }
        $ret['train'] = $train;
        return $ret;
    }

    //根据条件查询地铁站点服务设施
    public function get_subway_site_service($id)
    {
        $re     = $return = array();
        $sql    = 'SELECT *
				FROM ' . DB_PREFIX . 'subway_site_exinfo  WHERE site_id = ' . $id . ' AND type = 2';
        $re     = $this->db->query($sql);

        $types = $this->get_site_type(1);
        while ($row   = $this->db->fetch_array($re))
        {
            $serid = $row['id'];
            $ret   = array(
                'id' => $row['id'],
                'sign' => $row['sign'],
                'color' => $row['color'],
                'brief' => $row['brief'],
            );
            if ($serid)
            {
                //取所有的素材
                $sqlm = 'SELECT * FROM ' . DB_PREFIX . 'subway_materials WHERE cid = ' . $serid . ' AND cid_type = 3';
                $qm   = $this->db->query($sqlm);
                while ($rm   = $this->db->fetch_array($qm))
                {
                    if ($rm['id'] && $rm['mark'] == 'img')
                    {
                        $ret['indexpic'][] = array(
                            'id' => $rm['id'],
                            'host' => $rm['host'],
                            'dir' => $rm['dir'],
                            'filepath' => $rm['filepath'],
                            'filename' => $rm['filename'],
                            'imgwidth' => $rm['imgwidth'],
                            'imgheight' => $rm['imgheight'],
                        );
                    }
                }
            }

            if ($types[$row['type_id']])
            {
                $ret['title'] = $types[$row['type_id']];
            }

            $return[] = $ret;
        }
        return $return;
    }

    public function get_subway_site_gate($id)
    {
        $sqlg = "SELECT *
				 FROM  " . DB_PREFIX . "subway_site_exinfo 
				 WHERE site_id  = " . $id . " ORDER BY order_id";
        $qg   = $this->db->query($sqlg);
        while ($rowg = $this->db->fetch_array($qg))
        {
            if ($rowg['gate_id'])
            {
                //取所有的素材
                $sqlm_ = 'SELECT * FROM ' . DB_PREFIX . 'subway_materials WHERE cid = ' . $rowg['id'] . ' AND cid_type = 3';
                $qm_   = $this->db->query($sqlm_);
                while ($rm_   = $this->db->fetch_array($qm_))
                {
                    if ($rm_['mark'] == 'img')
                    {
                        $indexpic_[] = array(
                            'id' => $rm_['id'],
                            'host' => $rm_['host'],
                            'dir' => $rm_['dir'],
                            'filepath' => $rm_['filepath'],
                            'filename' => $rm_['filename'],
                            'imgwidth' => $rm_['imgwidth'],
                            'imgheight' => $rm_['imgheight'],
                        );
                    }
                }

                $ex[$rowg['gate_id']][] = array(
                    'type_id' => $rowg['type_id'],
                    'brief' => $rowg['brief'],
                    'indexpic' => $indexpic_,
                    'station_id' => $rowg['station_id'],
                    'station_name' => $rowg['station_name'],
                    'sign' => $rowg['sign'],
                    'title' => $rowg['title'],
                );
            }
            if ($rowg['type'] == 1)
            {
                //取所有的素材
                $sqlm = 'SELECT * FROM ' . DB_PREFIX . 'subway_materials WHERE cid = ' . $rowg['id'] . ' AND cid_type = 3';
                $qm   = $this->db->query($sqlm);
                while ($rm   = $this->db->fetch_array($qm))
                {
                    if ($rm['mark'] == 'img')
                    {
                        $indexpica[] = array(
                            'id' => $rm['id'],
                            'host' => $rm['host'],
                            'dir' => $rm['dir'],
                            'filepath' => $rm['filepath'],
                            'filename' => $rm['filename'],
                            'imgwidth' => $rm['imgwidth'],
                            'imgheight' => $rm['imgheight'],
                        );
                    }
                }
                $gate[] = array(
                    'id' => $rowg['id'],
                    'sign' => $rowg['sign'],
                    'color' => $rowg['color'],
                    'title' => $rowg['title'],
                    'latitude' => $rowg['latitude'],
                    'longitude' => $rowg['longitude'],
                    'brief' => $rowg['brief'],
                    'indexpic' => $indexpica,
                );
                unset($indexpica);
            }
        }
        if ($gate && is_array($gate))
        {
            foreach ($gate as $k => $v)
            {
                $gate[$k]['expand'] = $ex[$v['id']] ? $ex[$v['id']] : array();
            }
        }
        return $gate;
    }

    public function get_site_type($flag = '')
    {

        if ($flag)
        {
            $sql = "SELECT *
					FROM  " . DB_PREFIX . "subway_site_type 
					WHERE 1 ";
            $q   = $this->db->query($sql);
            $re  = array();
            while ($row = $this->db->fetch_array($q))
            {
                $re[$row['id']] = $row['title'];
            }
            return $re;
        }
        else
        {
            $type = $this->input['type'];
            $sql  = "SELECT *
					FROM  " . DB_PREFIX . "subway_site_type 
					WHERE type = " . $type;
            $q    = $this->db->query($sql);
            $re   = array();
            while ($row  = $this->db->fetch_array($q))
            {
                $re[] = $row;
            }
            $this->addItem($re);
            $this->output();
        }
    }

    public function get_site_color($flag = '')
    {
        $sql_ = "SELECT id,color,title FROM  " . DB_PREFIX . "subway  WHERE 1 ";
        $q_   = $this->db->query($sql_);
        while ($row_ = $this->db->fetch_array($q_))
        {
            $subway[$row_['id']] = array(
                'color' => $row_['color'],
                'title' => $row_['title'],
            );
        }
        $con = '';
        if ($flag)
        {
            $con = " AND c.state=1";
        }

        $sql = "SELECT distinct a.sub_id,a.site_id FROM  " . DB_PREFIX . "subway_relation a
				LEFT JOIN " . DB_PREFIX . "subway_site b ON a.site_id = b.id 
				LEFT JOIN " . DB_PREFIX . "subway c ON a.sub_id = c.id 
				WHERE 1 " . $con . " ORDER BY a.order_id ASC";

        $q   = $this->db->query($sql);
        $re  = array();
        while ($row = $this->db->fetch_array($q))
        {
            $re[$row['site_id']]['color'][]   = $subway[$row['sub_id']]['color'];
            $re[$row['site_id']]['subname'][] = $subway[$row['sub_id']]['title'];
        }
        return $re;
    }

    public function get_type_sign()
    {
        $sign = array();
        $sql_ = "SELECT id,sign FROM  " . DB_PREFIX . "subway_site_type  WHERE 1 ";
        $q_   = $this->db->query($sql_);
        while ($row_ = $this->db->fetch_array($q_))
        {
            $sign[$row_['id']] = $row_['sign'];
        }

        return $sign;
    }

    public function get_distance($distance)
    {

        $units = array('米', '千米');
        for ($i = 0; $distance >= 1000 && $i < 2; $i++)
        {
            $distance /= 1000;
        }
        return round($distance, 2) . $units[$i];
    }

    /**
     * gps坐标转换百度 
     * params ： x 经度；y 纬度；; 
     */
    function GpsToBaidu($x, $y)
    {
        $url = BAIDU_CONVERT_DOMAIN . '&x=' . $x . '&y=' . $y;

        $ch       = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch); //关闭

        $info = json_decode($response, 1);
        if ($info && !$info['error'])
        {
            unset($info['error']);
            $info['x'] = base64_decode($info['x']);
            $info['y'] = base64_decode($info['y']);
            return $info;
        }
    }

}

?>