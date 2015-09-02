<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
 * ************************************************************************* */

class mkpublish extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function insert($table, $data)
    {
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

    public function update($tablename, $con, $data)
    {
        $sql = "UPDATE " . DB_PREFIX . $tablename . " SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE " . $con;
        $this->db->query($sql);
    }

    public function insert_plan($sqlarr)
    {
        $deleteid  = $insertsql = $allplan   = array();

        $sql  = "SELECT * FROM " . DB_PREFIX . "mking ";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $allplan[$row['site_id']][$row['page_id']][$row['page_data_id']][$row['client_type']][$row['content_type']] = $row;
        }
        foreach ($sqlarr as $k => $v)
        {
            if (!empty($allplan[$v['site_id']][$v['page_id']][$v['page_data_id']][$v['client_type']][$v['content_type']]))
            {
                $deleteid[] = $allplan[$v['site_id']][$v['page_id']][$v['page_data_id']][$v['client_type']][$v['content_type']]['id'];
            }
        }
        if ($deleteid)
        {
            $sql = "DELETE FROM " . DB_PREFIX . "mking WHERE id IN(" . implode(',', $deleteid) . ")";
            $this->db->query($sql);
        }

        $i   = 0;
        $tag = '';
        foreach ($sqlarr as $k => $v)
        {
            $insertsql[$i] = empty($insertsql[$i]) ? '' : $insertsql[$i];
            $insertsql[$i] = $insertsql[$i] . $tag . "('" . $v['title'] . "','" . $v['site_id'] . "','" . $v['page_id'] . "','" . $v['page_data_id'] . "','" . $v['content_type'] . "','" . $v['client_type'] . "','" . $v['publish_time'] . "','" . $v['publish_user'] . "','" . $v['content_param'] . "','" . $v['count'] . "','" . $v['mk_count'] . "','" . $v['max_page'] . "','" . $v['m_type'] . "')";
            $tag           = ',';
            if (($k + 1) % 50 == 0)
            {
                $i++;
                $tag = '';
            }
        }
        foreach ($insertsql as $k => $v)
        {
            $sql = "INSERT INTO " . DB_PREFIX . "mking(title,site_id,page_id,page_data_id,content_type,client_type,publish_time,publish_user,content_param,count,mk_count,max_page,m_type) VALUES " . $v;
            $this->db->query($sql);
        }
    }

    public function insert_plan_batch($table, $plan)
    {
        $i          = 0;
        $tag        = '';
        $insertsql  = array();
        $field_flag = true;
        foreach ($plan as $k => $v)
        {
            $insertsql[$i] = empty($insertsql[$i]) ? '' : $insertsql[$i];
            $fieldtag      = '';
            $values        = '';
            foreach ($v as $kk => $vv)
            {
                if ($field_flag)
                {
                    $field .= $fieldtag . $kk;
                }
                $values .= $fieldtag . '\'' . $vv . '\'';
                $fieldtag = ',';
            }
            $field_flag = false;
            $insertsql[$i] .= $tag . '(' . $values . ')';
            $tag        = ',';
            if (($k + 1) % 50 == 0)
            {
                $i++;
                $tag = '';
            }
        }
        if (!$field)
        {
            return false;
        }
        foreach ($insertsql as $k => $v)
        {
            $sql = "INSERT INTO " . DB_PREFIX . $table . "(" . $field . ") VALUES " . $v;
            $this->db->query($sql);
        }
    }

    public function get_plan($condition = '', $limit = '')
    {
        $ret  = array();
        $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE 1 " . $condition . $limit;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    public function get_plan_first()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE publish_time<=" . TIMENOW . " ORDER BY rid,publish_time LIMIT 1";
        $info = $this->db->query_first($sql);
        if ($info)
        {
            $info['content_param'] = $info['content_param'] ? unserialize($info['content_param']) : array();
            $info['content_detail'] = $info['content_detail'] ? unserialize($info['content_detail']) : array();
        }
        return $info;
    }

    public function get_plan_by_con($con = '')
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE 1";
        $sql .= $con;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $row['content_param']  = $row['content_param'] ? unserialize($row['content_param']) : array();
            $row['content_detail'] = $row['content_detail'] ? unserialize($row['content_detail']) : array();
            $plans[]               = $row;
            $ids[]                 = $row['id'];
        }
        if ($ids)
        {
            self::delete('mking', ' id in(' . implode(',', $ids) . ')');
        }
        return $plans;
    }

    public function delete($tablename, $con)
    {
        $sql = "DELETE FROM " . DB_PREFIX . $tablename . " WHERE " . $con;
        $this->db->query($sql);
    }

    public function get_mkpublish_plan($condition, $offset, $count)
    {
        $ret  = array();
        $sql  = "select * from " . DB_PREFIX . "mking where 1 " . $condition . " limit {$offset},{$count}";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    public function get_mklog($offset, $count, $condition)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "mklog WHERE 1 " . $condition . " ORDER BY id DESC LIMIT {$offset},{$count}";
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_mk_plan($offset, $count, $condition)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE 1 " . $condition . " ORDER BY rid,publish_time DESC LIMIT {$offset},{$count}";
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_mkcomplete_plan($offset, $count, $condition)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "mking_complete WHERE 1 " . $condition . " ORDER BY publish_time DESC LIMIT {$offset},{$count}";
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_waits($ids, $key = '')
    {
        $result = array();
        $sql    = "SELECT * FROM " . DB_PREFIX . "mkwait WHERE 1 AND id in(" . $ids . ")";
        $info   = $this->db->query($sql);
        while ($row    = $this->db->fetch_array($info))
        {
            if ($key)
            {
                $result[$row[$key]] = $row;
            }
            else
            {
                $result[$row['id']] = $row;
            }
        }
        return $result;
    }

    public function check_mking($v)
    {
        $result = array();
        if ($v['content_id'])
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE content_id=" . $v['content_id'];
            $info = $this->db->query_first($sql);
        }
        else
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "mking WHERE site_id=" . $v['site_id'] . " AND page_id=" . $v['page_id'] . " AND page_data_id=" . $v['page_data_id'] . " AND content_type=" . $v['content_type'] . " AND client_type=" . $v['client_type'];
            $info = $this->db->query_first($sql);
        }
        if ($info)
        {
            $this->delete_mk($info['id']);
        }
        unset($v['id']);
        $this->insert('mking', $v);
    }

    public function delete_wait($ids)
    {
        $sql  = "DELETE FROM " . DB_PREFIX . "wait WHERE id in (" . $ids . ")";
        $info = $this->db->query($sql);
    }

    public function delete_mk($ids)
    {
        $sql  = "DELETE FROM " . DB_PREFIX . "mking WHERE id in (" . $ids . ")";
        $info = $this->db->query($sql);
    }

    public function insert_queue($data)
    {
        $sql = "INSERT INTO " . DB_PREFIX . "plan SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $this->db->query($sql);
    }

    public function get_plan_set($ids)
    {
        $result = array();
        $sql    = "SELECT * FROM " . DB_PREFIX . "plan_set WHERE id in (" . $ids . ")";
        $info   = $this->db->query($sql);
        while ($row    = $this->db->fetch_array($info))
        {
            $result[$row['id']] = $row;
        }
        return $result;
    }

    public function check_action_type($set_id, $from_id, $action_type)
    {
        if (!$set_id || !$from_id || !$action_type)
        {
            return false;
        }
        $sql  = "SELECT id FROM " . DB_PREFIX . "plan WHERE set_id=" . $set_id . " AND from_id=" . $from_id . " AND action_type='" . $action_type . "'";
        $info = $this->db->fetch_all($sql);
        if (empty($info))
        {
            return true;
        }
        $ids = '';
        foreach ($info as $k => $v)
        {
            $ids .= $v['id'] . ',';
        }
        $ids = trim($ids . ',');
        if ($ids)
        {
            $sql = "DELETE FROM " . DB_PREFIX . "plan WHERE id in(" . $ids . ")";
            $this->db->query($sql);
        }
    }

    public function complete_plan($complete_plan)
    {
        $str = '';
        $str .= ' AND site_id=' . $complete_plan['site_id'];
        $str .= ' AND page_id=' . $complete_plan['page_id'];
        $str .= ' AND page_data_id=' . $complete_plan['page_data_id'];
        $str .= ' AND content_type=' . $complete_plan['content_type'];
        $str .= ' AND client_type=' . $complete_plan['client_type'];
        $str .= ' AND rid=' . intval($complete_plan['rid']);

        $sql  = 'SELECT * FROM ' . DB_PREFIX . 'mking_complete WHERE 1 ' . $str;
        $info = $this->db->query_first($sql);

        if (!$info)
        {
            self::insert('mking_complete', $complete_plan);
            return;
        }

        if ($complete_plan['file_path'] != $info['file_path'])
        {
            self::update('mking_complete', ' id=' . $info['id'], array('file_path' => $complete_plan['file_path'], 'publish_time' => $complete_plan['publish_time'], 'title' => $complete_plan['title']));
            //self::unlink_file($info['file_path'],$info['file_name'],$info['page_num']);
            return;
        }

        if ($complete_plan['file_name'] != $info['file_name'])
        {
            self::update('mking_complete', ' id=' . $info['id'], array('file_name' => $complete_plan['file_name'], 'publish_time' => $complete_plan['publish_time'], 'title' => $complete_plan['title']));
            //self::unlink_file($info['file_path'],$info['file_name'],$info['page_num']);
            return;
        }
        if($complete_plan['page_num']>=0)
        {
            self::update('mking_complete', ' id=' . $info['id'], array('page_num' => $complete_plan['page_num'], 'publish_time' => $complete_plan['publish_time'], 'title' => $complete_plan['title']));
        }
        return;
    }

    public function unlink_file($file_path, $file_name, $page_num)
    {
        $page_num = intval($page_num)?$page_num:20;
        $urlarr = explode('.', $file_name);
        $file_path = rtrim($file_path, '/');
        if (!$urlarr)
        {
            continue;
        }
        for ($i = 0; $i <= $page_num; $i++)
        {
            if ($i)
            {
                $f = $urlarr[0] . '_' . $i . '.' . $urlarr[1];
            }
            else
            {
                $f = $file_name;
            }
            @unlink($file_path . '/' . $f);
        }
        $f = $urlarr[0] . '_all' . '.' .$urlarr[1];
        @unlink($file_path . '/' . $f);
    }

    public function get_mkplan($condition, $offset, $count)
    {
        $res  = array();
        $sql  = "SELECT * FROM " . DB_PREFIX . "mkpublish_plan where 1 " . $condition . " order by id desc limit $offset,$count";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $res[] = $row;
        }
        return $res;
    }

    public function get_mkplan_first($id)
    {
        $sql = "select * from " . DB_PREFIX . "mkpublish_plan where id=" . $id;
        return $this->db->query_first($sql);
    }

}

?>
