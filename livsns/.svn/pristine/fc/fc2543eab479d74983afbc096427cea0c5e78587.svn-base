<?php
/**
 * Created by PhpStorm.
 * User: wangleyuan
 * Date: 14/11/27
 * Time: 上午1:08
 */

class AppAccess extends InitFrm
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

        $sql = 'SELECT *  FROM '.DB_PREFIX.'app_access'
            . $where . $order . $group . $limit;
        $q = $this->db->query($sql);

        $ret = array();
        while( ($row = $this->db->fetch_array($q)) != false )
        {
            $row['status_text'] = $this->settings['status_show'][$row['status']];
            $row['create_time'] = date('Y-m-d H:i', $row['create_time']);
            $row['update_time'] = date('Y-m-d H:i', $row['update_time']);
            $row['pay_type'] = $row['pay_type'] ? explode(',', $row['pay_type']) : array();

            $row['pay_type_text'] = array();
            foreach ((array)$row['pay_type'] as $k => $v)
            {
                $row['pay_type_text'][] = $this->settings['pay_type'][$v]['title'];
            }
            $row['pay_type_text'] = implode('，', $row['pay_type_text']);
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
        $sql = "SELECT * FROM ".DB_PREFIX."app_access
        WHERE id = '".$id."'";
        $app = $this->db->query_first($sql);

        if ( !empty($app) )
        {
            $app['status_text'] = $this->settings['status_show'][$app['status']];
            $app['create_time'] = date('Y-m-d H:i', $app['create_time']);
            $app['update_time'] = date('Y-m-d H:i', $app['update_time']);
            $app['pay_type'] = $app['pay_type'] ? explode(',', $app['pay_type']) : array();
        }
        return $app;
    }


    function delete($ids)
    {
        if (is_array($ids))
        {
            $ids = implode(',', $ids);
        }

        $sql = "DELETE FROM ".DB_PREFIX."app_access WHERE id IN (".$ids.")";
        $this->db->query_first($sql);

        return true;
    }

    function create($data)
    {
        if ( ($insert_id = $this->db->insert_data($data, 'app_access')) != false )
        {
            return $insert_id;
        }
        else
        {
            return false;
        }
    }

    function update($data, $condition)
    {
        if ( $this->db->update_data($data, 'app_access', $condition) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}