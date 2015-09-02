<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: site.class.php 6931 2012-05-31 07:33:56Z repheal $
 * ************************************************************************* */

class content extends InitFrm
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

    public function delete($tablename, $con)
    {
        $sql = "DELETE FROM " . DB_PREFIX . $tablename . " WHERE " . $con;
        $this->db->query($sql);
    }

    public function get_app()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "app WHERE 1 AND father=0";
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_app_child($app)
    {
        $con  = empty($app) ? " AND b.father=0" : " AND b.bundle='" . $app . "'";
        $sql  = "SELECT a.* FROM " . DB_PREFIX . "app a LEFT JOIN " . DB_PREFIX . "app b ON a.father=b.id WHERE 1 " . $con;
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_content_allinfo($condition, $offset, $count, $other_field = '')
    {
        $result      = $col_id_arr  = $app_data    = $site_id_arr = array();
        $col_id_str  = '';
        //查询出app
        $sql         = "SELECT a.name as name,a.bundle as module_id,a1.bundle as bundle_id FROM " . DB_PREFIX . "app a LEFT JOIN " . DB_PREFIX . "app a1 ON a.father=a1.id WHERE a1.father=0";
        $info        = $this->db->query($sql);
        while ($row         = $this->db->fetch_array($info))
        {
            $app_data[$row['bundle_id']][$row['module_id']] = $row['name'];
        }

        //$sql  = "SELECT c.*,c.column_id as main_column_id,r.*,cr.client_type,r.id as rid FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id  LEFT JOIN " . DB_PREFIX . "content c on r.content_id=c.id " .
        //        "WHERE 1 " . $condition . " LIMIT {$offset},{$count} ";
        //$info = $this->db->query($sql);

        $sql           = "SELECT r.*,r.id as rid" . $other_field . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id WHERE 1";
        $sql .= $condition;
        $sql .= " LIMIT {$offset},{$count} ";
        $info          = $this->db->query($sql);
        $ridsarr       = $cidsarr       = $relation_data = array();
        while ($row           = $this->db->fetch_array($info))
        {
            $ridsarr[]                   = $row['id'];
            $cidsarr[$row['content_id']] = $row['content_id'];
            $relation_data[$row['id']]   = $row;
        }
        if ($cidsarr)
        {
            $contentdetail = array();
            $sql1          = "SELECT c.*,c.id as cid,c.column_id as main_column_id FROM " . DB_PREFIX . "content c ";
            $sql1 .= " WHERE c.id in (" . implode(',', $cidsarr) . ")";
            $info          = $this->db->query($sql1);
            while ($row           = $this->db->fetch_array($info))
            {
                $contentdetail[$row['id']] = $row;
            }

            foreach ($ridsarr as $v)
            {
                if (is_array($contentdetail[$relation_data[$v]['content_id']]))
                {
                    $row = $relation_data[$v] + $contentdetail[$relation_data[$v]['content_id']];
                }

                $picurl = '';
                if (!empty($row['indexpic']))
                {
                    $pic    = unserialize($row['indexpic']);
                    $picurl = $pic['host'] . $pic['dir'] . '120x90/' . $pic['filepath'] . $pic['filename'];
                }
                $row['indexpic']               = $picurl;
                $row['create_time']            = date('Y-m-d H:i', $row['create_time']);
                $row['publish_time']           = date('Y-m-d H:i', $row['publish_time']);
                $row['app_name']               = $app_data[$row['bundle_id']][$row['module_id']];
                $col_id_arr[$row['column_id']] = $row['column_id'];
                if ($row['use_maincolumn'])
                {
                    $col_id_arr[$row['column_id']] = $row['main_column_id'];
                }
                $site_id_arr[$row['site_id']] = $row['site_id'];
                unset($row['keywords_unicode'],$row['title_unicode'],$row['title_pinyin']);
                $result[]                     = $row;
            }
        }
        if (!empty($col_id_arr))
        {
            $col_id_str = implode(',', $col_id_arr);
        }
        $result['col_id_str']  = $col_id_str;
        $result['site_id_arr'] = $site_id_arr;
        return $result;
    }

    public function get_content_sql($condition, $offset, $count, $other_field = '', $need_video = '', $app = array(), $need_subtitle = false, $is_useindex = false, $index_field = 'order_id')
    {
        $result   = array();
        $leftjoin = $field    = '';
        if ($app)
        {
            $leftjoin = ' LEFT JOIN ' . DB_PREFIX . $app['bundle_id'] . '_' . $app['module_id'] . '_' . $app['struct_id'] . ' a ON a.id=c.expand_id ';
            $field    = ',a.* ';
        }
        //$sql = "SELECT c.*,c.id as cid,c.column_id as main_column_id,r.site_id,r.column_id,r.column_name,r.file_name,r.file_domain,r.file_dir,r.file_custom_filename,cr.client_type,r.weight,r.id as id,r.order_id,r.weight,r.publish_time,r.publish_user" . $other_field . $field . " FROM " . DB_PREFIX . "content_client_relation cr " . ($is_useindex && $index_field ? '' : 'use index (' . $index_field . ')') . " LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id LEFT JOIN " . DB_PREFIX . "content c on r.content_id=c.id ";
        //$sql .= $leftjoin . $condition;

        $sql = "SELECT r.* " . $other_field . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id";
        $sql .= $condition;

        $sql .= " LIMIT {$offset},{$count} ";
        return $sql;
    }

    public function get_content($condition, $offset, $count, $other_field = '', $need_video = '', $app = array(), $need_subtitle = false, $is_useindex = false, $index_field = 'order_id', $need_catalog = false)
    {
        $time_start   = microtime_float();
        $result       = $catalogs_arr = array();
        $leftjoin     = $field        = '';
        if ($app)
        {
            $leftjoin = ' LEFT JOIN ' . DB_PREFIX . $app['bundle_id'] . '_' . $app['module_id'] . '_' . $app['struct_id'] . ' a ON a.id=c.expand_id ';
            $field    = 'a.*, ';
        }
        $ridsarr       = $cidsarr       = $relation_data = array();
        $sql           = "SELECT r.*" . $other_field . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id";
        $sql .= $condition;
        if ($count)
        {
            $sql .= " LIMIT {$offset},{$count} ";
        }
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ridsarr[]                   = $row['id'];
            $cidsarr[$row['content_id']] = $row['content_id'];
            $relation_data[$row['id']]   = $row;
        }
        $time_end   = microtime_float();
        $alltime    = $time_end - $time_start;
        $time_start = microtime_float();
        if ($cidsarr)
        {
            $contentdetail = array();
            $sql1          = "SELECT " . $field . "c.*,c.id as cid,c.column_id as main_column_id FROM " . DB_PREFIX . "content c ";
            $sql1 .= $leftjoin . " WHERE c.id in (" . implode(',', $cidsarr) . ")";
            $info          = $this->db->query($sql1);
            while ($row           = $this->db->fetch_array($info))
            {
                $contentdetail[$row['id']] = $row;
                if($need_catalog)
                {
                    $catalogs_arr[$row['cid']] = array(
                        'content_id' => $row['content_fromid'],
                        'app_uniqueid' => $row['bundle_id'],
                        'mod_uniqueid' => $row['module_id']);
                }
            }
            foreach ($ridsarr as $v)
            {
                if (is_array($contentdetail[$relation_data[$v]['content_id']]))
                {
                    $row = $relation_data[$v] + $contentdetail[$relation_data[$v]['content_id']];
                }
                if (!$row['column_id'])
                {
                    continue;
                }
                $pic   = $video = array();
                if (!empty($row['indexpic']))
                {
                    $pic = @unserialize($row['indexpic']);
                }
                if ($need_video)
                {
                    if (!empty($row['video']))
                    {
                        $video             = @unserialize($row['video']);
                        $video['filename'] = empty($video['filename']) ? '' : str_replace('.mp4', '.m3u8', $video['filename']);
                    }
                    $row['video'] = $video;
                }
                else
                {
                    unset($row['video']);
                }
                $row['subtitle'] = html_decode($row['subtitle']);
                $row['title']    = html_decode($row['title']);
                if ($need_subtitle)
                {
                    $row['title'] = $row['subtitle'] ? htmlspecialchars_decode($row['subtitle']) : htmlspecialchars_decode($row['title']);
                }
                else
                {
                    $row['title'] = htmlspecialchars_decode($row['title']);
                }
                if (!empty($row['content']))
                {
                    $row['content'] = htmlspecialchars_decode($row['content']);
                }
                if ($row['childs_data'])
                {
                    $row['childs_data'] = @unserialize($row['childs_data']);
                }
                if ($row['catalog'])
                {
                    $row['catalog'] = @unserialize($row['catalog']);
                }
                $row['indexpic']           = $pic;
                $row['publish_time_stamp'] = $row['publish_time'];
                $row['create_time_stamp']  = $row['create_time'];
                $row['create_time']        = date($this->settings['default_time_format'], $row['create_time']);
                $row['publish_time']       = date($this->settings['default_time_format'], $row['publish_time']);
                $row['brief']              = strip_tags(htmlspecialchars_decode($row['brief']));
                $column_ids_arr[]          = $row['column_id'];
                if ($row['use_maincolumn'])
                {
                    $column_ids_arr[] = $row['main_column_id'];
                }
                $site_ids_arr[] = $row['site_id'];
                unset($row['keywords_unicode'],$row['title_unicode'],$row['title_pinyin']);
                $result[]       = $row;
            }
        }

        if (!$column_ids_arr)
        {
            $column_ids_arr = array();
        }
        $result['catalogs_arr']   = $catalogs_arr;
        $result['column_ids_arr'] = $column_ids_arr ? array_flip(array_flip($column_ids_arr)) : array();
        $result['site_ids_arr']   = $site_ids_arr ? array_flip(array_flip($site_ids_arr)) : array();
        $time_end                 = microtime_float();
        $alltime1                 = $time_end - $time_start;
        $at                       = $alltime + $alltime1;
        if ($at >= 0.5)
        {
            //file_put_contents('cache/1.txt', $sql . "\n" . $alltime . "\n" . $sql1 . "\n" . $alltime1 . "\n" . $at . "  " . date('Y-m-d H:i:s', time()) . "\n\n", FILE_APPEND);
        }
        return $result;
    }

    public function get_content_by_condition($condition)
    {
        $result = array();
        $sql    = "SELECT c.*,c.id as cid,c.column_id as main_column_id,r.* FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id LEFT JOIN " . DB_PREFIX . "content c on r.content_id=c.id " .
                $condition;

        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $pic = array();
            if (!empty($row['indexpic']))
            {
                $pic = unserialize($row['indexpic']);
            }
            $row['indexpic']     = $pic;
            $row['create_time']  = date($this->settings['default_time_format'], $row['create_time']);
            $row['publish_time'] = date($this->settings['default_time_format'], $row['publish_time']);
            unset($row['keywords_unicode'],$row['title_unicode'],$row['title_pinyin']);
            $result[]            = $row;
        }
        return $result;
    }

    public function get_content_by_ids($fields, $ids, $key = '')
    {
        $ret  = array();
        $sql  = "SELECT " . $fields . " FROM " . DB_PREFIX . "content WHERE id in (" . $ids . ")";
        $info = $this->db->query($sql);
        if ($key)
        {
            while ($row = $this->db->fetch_array($info))
            {
                $ret[$row[$key]] = $row;
            }
        }
        else
        {
            while ($row = $this->db->fetch_array($info))
            {
                $ret[] = $row;
            }
        }

        return $ret;
    }

    public function get_content_by_rid($field = ' r.id ', $id)
    {
        $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . "content_relation r left join " . DB_PREFIX . "content c on r.content_id=c.id WHERE r.id=" . $id;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_content_by_fromid($fields, $bundle_id, $module_id, $struct_id, $fromid)
    {
        $sql  = "SELECT " . $fields . " FROM " . DB_PREFIX . "content WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND struct_id='" . $struct_id . "' AND content_fromid='" . $fromid . "'";
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_content_by_id($fields, $id)
    {
        $sql  = "SELECT " . $fields . " FROM " . DB_PREFIX . "content WHERE id =" . $id;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_content_relation_by_id($id)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_relation WHERE id=" . $id;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_all_content_by_relationid($id, $need_process = false)
    {
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $column = new column();
        $sql    = "SELECT c.*,r.*,c.id as cid,c.column_id as main_column_id,r.site_id,r.column_id,r.column_name,r.id as id,r.order_id,r.weight,r.file_name,r.file_domain,r.file_dir,r.file_custom_filename,r.publish_time,r.status FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id =" . $id;
        $info   = $this->db->query_first($sql);
        if ($info['indexpic'])
        {
            $info['indexpic'] = unserialize($info['indexpic']);
        }
        if ($info['video'])
        {
            $info['video'] = unserialize($info['video']);
        }
        if ($info['childs_data'])
        {
            $info['childs_data'] = unserialize($info['childs_data']);
        }
        if ($info['catalog'])
        {
            $info['catalog'] = unserialize($info['catalog']);
        }
        if ($need_process)
        {
            if ($info)
            {
                $column_data         = $column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['column_id']);
                $site_data           = $column_data['site_data'];
                unset($column_data['site_data']);
                $info['column_info'] = $column_data;
                if ($info['use_maincolumn'])
                {
                    $column_data              = $column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['main_column_id']);
                    $site_data                = $column_data['site_data'];
                    $info['content_url']      = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, array('column_id' => $info['main_column_id']) + $info);
                    unset($column_data['site_data']);
                    $info['main_column_info'] = $column_data;
                }
                else
                {
                    $info['content_url'] = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, $info);
                }
            }
        }
        unset($info['keywords_unicode'],$info['title_unicode'],$info['title_pinyin']);
        return $info;
    }

    public function get_field($bundle_id, $module_id, $struct_id, $struct_ast_id = '')
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND struct_id='" . $struct_id . "' AND struct_ast_id='" . $struct_ast_id . "'";
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_field_by_id($id)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_field WHERE id=" . $id;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_expand($tablename, $id, $offset = '', $count = '')
    {
        $con    = '';
        $result = array();
        if ($offset !== '' && $count !== '')
        {
            $con = " LIMIT {$offset},{$count}  ";
        }
        $sql  = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE id=" . $id . $con;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $result[] = $row;
        }
        return $result;
    }

    public function get_expand_by_expand_id($tablename, $ids, $offset = '', $count = '')
    {
        $con = '';
        if ($offset !== '' && $count !== '')
        {
            $con = " LIMIT {$offset},{$count}  ";
        }
        $sql  = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE expand_id in (" . $ids . ")" . $con;
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_content_relation($cids, $condition = '')
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_relation WHERE content_id=" . $cids . $condition;
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_cr_by_rid($rids)
    {
        $ridarr      = $cidarr      = $plan_set_id = array();
        $sql         = "SELECT r.id as rid,c.id as cid,c.plan_set_id,r.site_id,r.column_id,c.content_fromid FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id in (" . $rids . ")";
        $info        = $this->db->query($sql);
        while ($row         = $this->db->fetch_array($info))
        {
            $ridarr[$row['rid']] = array('content_id' => $row['cid'], 'content_fromid' => $row['content_fromid'], 'column_id' => $row['column_id'], 'plan_set_id' => $row['plan_set_id']);
            $cidarr[$row['cid']] = array('content_fromid' => $row['content_fromid'], 'column_id' => $row['column_id'], 'plan_set_id' => $row['plan_set_id']);
            $plan_set_id[]       = $row['plan_set_id'];
        }
        $result['ridarr']         = $ridarr;
        $result['cidarr']         = $cidarr;
        $result['plan_set_idarr'] = array_unique($plan_set_id);
        return $result;
    }

    public function get_relationid_by_expand_id($bundle_id, $module_id, $struct_id, $fromid)
    {
        $relation_ids_arr = array();
        $sql              = "SELECT r.id FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE c.bundle_id='" . $bundle_id . "' AND c.module_id='" . $module_id . "' AND c.struct_id='" . $struct_id . "' AND c.content_fromid in (" . $fromid . ")";
        $info             = $this->db->query($sql);
        while ($row              = $this->db->fetch_array($info))
        {
            $relation_ids_arr[] = $row['id'];
        }
        return $relation_ids_arr;
    }

    public function get_update_child_id($tablename, $con, $fromid, $get_id = true)
    {
        $sql  = "SELECT id FROM " . DB_PREFIX . $tablename . " WHERE content_fromid in (" . $fromid . ")";
        $data = $this->db->query_first($sql);
        if (empty($data))
        {
            return false;
        }
        if ($get_id)
        {
            return $data['id'];
        }
        else
        {
            return $data['expand_id'];
        }
    }

    public function get_content_client($field, $data)
    {
        $sql  = "SELECT " . $field . " FROM " . DB_PREFIX . "content_client_relation WHERE relation_id=" . $data['relation_id'] . " AND client_type=" . $data['client_type'];
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function get_content_columns($field, $data)
    {
        $sql = "SELECT " . $field . " FROM " . DB_PREFIX . "content_columns WHERE content_id=" . $data['content_id'];
        return $this->db->query_first($sql);
    }

    public function get_relate_by_cc($content_id, $column_id)
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_relation WHERE content_id=" . $content_id . " AND column_id=" . $column_id;
        $info = $this->db->query_first($sql);
        return $info;
    }

    public function delete_content_relation($cids, $column_ids = '', $need_id = false)
    {
        $rids = array();
        if ($need_id)
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "content_relation WHERE content_id=" . $cids;
            if ($column_ids)
            {
                $sql .= " AND column_id in(" . $column_ids . ")";
            }
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $rids[] = $row['id'];
            }
        }
        $sql = "DELETE  FROM " . DB_PREFIX . "content_relation WHERE content_id=" . $cids;
        if ($column_ids)
        {
            $sql .= " AND column_id in(" . $column_ids . ")";
        }
        $this->db->query($sql);
        return $rids;
    }

    public function delete_content_relation_by_id($rids)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "content_relation WHERE id in (" . $rids . ")";
        $this->db->query($sql);
        return true;
    }

    public function delete_expand($tablename, $ids)
    {
        $sql = "DELETE  FROM " . DB_PREFIX . $tablename . " WHERE id in (" . $ids . ")";
        $this->db->query($sql);
    }

    public function delete_child_expand($tablename, $expand_ids, $in = true)
    {
        if ($in)
        {
            $sql = "DELETE  FROM " . DB_PREFIX . $tablename . " WHERE expand_id in (" . $expand_ids . ")";
        }
        else
        {
            $sql = "DELETE  FROM " . DB_PREFIX . $tablename . " WHERE expand_id=" . $expand_ids;
        }

        $this->db->query($sql);
    }

    public function delete_content_client_by_rid($rids)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "content_client_relation WHERE relation_id in (" . $rids . ")";
        $this->db->query($sql);
    }

    public function delete_content_columns($content_ids)
    {
        $sql = "DELETE FROM " . DB_PREFIX . "content_columns WHERE content_id in (" . $content_ids . ")";
        $this->db->query($sql);
    }

    public function update_content($bundle_id, $module_id, $struct_id, $fromid, $con)
    {
        $sql = "UPDATE " . DB_PREFIX . "content SET " . $con . " WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND struct_id='" . $struct_id . "' AND content_fromid in (" . $fromid . ")";
        $this->db->query($sql);
        //查询这条记录
        $sql = "SELECT * FROM " . DB_PREFIX . "content WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND struct_id='" . $struct_id . "' AND content_fromid='" . $fromid . "'";
        return $this->db->query_first($sql);
    }

    public function update_child_table($tablename, $con, $fromid)
    {
        $sql = "UPDATE " . DB_PREFIX . $tablename . " SET " . $con . " WHERE content_fromid in (" . $fromid . ")";
        $this->db->query($sql);
        return true;
    }

    public function update_content_is_complete($data, $content_id = '')
    {
        /**
          $sql = "SELECT id,content_id FROM " . DB_PREFIX . "content_relation WHERE is_complete!=1 AND id=" . $data['content_rid'];
          $ret = $this->db->query_first($sql);
          if ($ret)
          {
          $sql = "UPDATE " . DB_PREFIX . "content_relation SET is_complete=1 WHERE content_id=" . $ret['content_id'];
          $this->db->query($sql);
          return true;
          }
          return false;
         */
        $result = array();
        if (!$content_id)
        {
            $sql        = "SELECT id,content_id FROM " . DB_PREFIX . "content_relation WHERE id=" . $data['content_rid'];
            $ret        = $this->db->query_first($sql);
            $content_id = $ret['content_id'];
        }

        if ($content_id)
        {
            $sql  = "SELECT id,content_id FROM " . DB_PREFIX . "content_relation WHERE is_complete!=1 AND content_id=" . $content_id;
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $result[] = $row;
            }
            $sql = "UPDATE " . DB_PREFIX . "content_relation SET is_complete=1 WHERE content_id=" . $content_id;
            $this->db->query($sql);
            return $result;
        }
        return false;
    }

    public function update_content_by_id($id, $data)
    {
        $sql = "UPDATE " . DB_PREFIX . "content SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id=" . $id;
        $this->db->query($sql);
    }

    public function update_content_columns($id, $data)
    {
        $sql = "UPDATE " . DB_PREFIX . "content_columns SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE content_id=" . $id;
        $this->db->query($sql);
    }

    public function update_content_relation_by_id($id, $data)
    {
        $sql = "UPDATE " . DB_PREFIX . "content_relation SET";

        $sql_extra = $space     = ' ';
        foreach ($data as $k => $v)
        {
            $sql_extra .=$space . $k . "='" . $v . "'";
            $space = ',';
        }
        $sql .=$sql_extra;
        $sql .= " WHERE id=" . $id;
        $this->db->query($sql);
    }

    public function get_content_in_site($bundle_id, $module_id, $content_fromid, $_site_syn_ids, $bysite = true)
    {
        $sql = "SELECT id,expand_id,content_fromid FROM " . DB_PREFIX . "content WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND content_fromid=" . $content_fromid;
        if ($bysite)
        {
            $sql .= " AND site_id in (" . $_site_syn_ids . ")";
        }
        return $this->db->query_first($sql);
    }

    public function check_content($data, $bysite = false)
    {
        //先查询出这个内容原id是否存在
        $info1 = $this->get_content_in_site($data['bundle_id'], $data['module_id'], $data['content_fromid'], $data['_site_syn_ids'], $bysite);
        if (empty($info1))
        {
            return 'new';
        }
        else
        {
            //判断这个栏目下的客户端
            //$sql   = "SELECT r.id FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.bundle_id='" . $data['bundle_id'] . "' AND r.module_id='" . $data['module_id'] . "' AND r.column_id=" . $data['column_id'] . " AND c.content_fromid=" . $data['content_fromid'];
            $sql   = "SELECT r.id FROM " . DB_PREFIX . "content_relation r WHERE r.content_id='" . $info1['id'] . "' AND r.column_id=" . $data['column_id'];
            $info3 = $this->db->fetch_all($sql);
            if (empty($info3))
            {
                return $info1['id'];
            }
            else
            {
                return false;
            }
        }
    }

    public function update_content_video_record($rids, $data)
    {
        if (!$rids)
        {
            return false;
        }

        $sql  = "SELECT id,relation_id,content_data FROM " . DB_PREFIX . "content_video_record WHERE relation_id in(" . $rids . ")";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            if ($row['content_data'])
            {
                $content_data             = @unserialize($row['content_data']);
                $content_data['title']    = $data['title'];
                $content_data['keywords'] = $data['keywords'];
                $content_data['brief']    = $data['brief'];
                $content_data['indexpic'] = $data['indexpic'];
                $content_data['video']    = $data['video'];
                $sql2                     = "UPDATE " . DB_PREFIX . "content_video_record SET update_time=" . TIMENOW . ", content_data='" . serialize($content_data) . "' WHERE relation_id=" . $row['relation_id'];
                $this->db->query($sql2);
            }
        }
    }

    /**
     * 更新百度视频收录
     * */
    public function update_video_record($expand_data, $op = 'add')
    {
        if ($op != 'del')
        {
            $content_data      = array(
                'title' => $expand_data['title'],
                'keywords' => $expand_data['keyword'],
                'brief' => $expand_data['brief'],
                'indexpic' => $expand_data['indexpic'],
                'video' => $expand_data['video'],
                'file_name' => $expand_data['file_name'],
                'create_time' => $expand_data['create_time'],
            );
            $video_record_data = array(
                'site_id' => $expand_data['site_id'],
                'column_id' => $expand_data['column_id'],
                'bundle_id' => $expand_data['bundle_id'],
                'module_id' => $expand_data['module_id'],
                'struct_id' => $expand_data['struct_id'],
                'relation_id' => $expand_data['rid'],
                'opration' => $op,
                'content_data' => serialize($content_data),
                'update_time' => TIMENOW,
            );
        }
        else
        {
            $video_record_data['relation_id'] = $expand_data['rid'];
            $video_record_data['update_time'] = TIMENOW;
        }

        if (!$video_record_data['relation_id'])
        {
            return false;
        }
        if ($op == 'add')
        {
            $this->insert('content_video_record', $video_record_data);
        }
        else if ($op == 'del')
        {
            $sql = "UPDATE " . DB_PREFIX . "content_video_record SET opration='" . $op . "',update_time='" . $video_record_data['update_time'];
            $sql .= "' WHERE relation_id in (" . $video_record_data['relation_id'] . ")";
            $this->db->query($sql);
        }
    }

    //内容推送记录
    public function content_push($data, $content_id, $content_relation_id, $check = false, $delete = false)
    {
        if ($data['special'] || $delete || $check)
        {
            if (!$this->settings['App_special'])
            {
                return false;
            }
            $data['special'] = empty($data['special']) ? array() : $data['special'];
            self::push_special($data, $content_id, $content_relation_id, $check);
        }
        if ($data['block'] || $delete || $check)
        {
            if (!$this->settings['App_block'])
            {
                return false;
            }
            $data['block'] = empty($data['block']) ? array() : $data['block'];
            self::push_block($data, $content_id, $content_relation_id, $check);
        }
    }

    public function push_special($data, $content_id, $content_relation_id, $check)
    {
        $content_push = array(
            'content_id' => $content_id,
            'content_relation_id' => $content_relation_id,
            'type' => 1,
            'relation_data' => serialize($data['special']),
        );
        include_once(ROOT_PATH . 'lib/class/special.class.php');
        $special_obj  = new special();
        $content_data = $data;
        $sql          = "SELECT * FROM " . DB_PREFIX . "content_push WHERE type=1 AND content_id={$content_id}";
        $info         = $this->db->query_first($sql);
        if ($info && !$content_relation_id)
        {
            $content_push['content_id']          = $info['content_id'];
            $content_push['content_relation_id'] = $info['content_relation_id'];
        }
        $sql                = "SELECT * FROM " . DB_PREFIX . "content_columns WHERE content_id={$content_id}";
        $content_columns    = $this->db->query_first($sql);
        $content_columnsarr = $content_columns['column_datas'] ? unserialize($content_columns['column_datas']) : array();
        foreach ($content_columnsarr as $k => $v)
        {
            $cc[$k] = $v['name'];
        }
        $content_data['publish_content_columns'] = $cc;
        if ($check && !empty($info))
        {
            //先判断content_push有没有记录
            $relation_data         = unserialize($info['relation_data']);
            $data_special_keys     = array_keys($data['special']);
            $relation_special_keys = array_keys($relation_data);
            $insert_relation_idarr = array_diff($data_special_keys, $relation_special_keys);
            $delete_relation_idarr = array_diff($relation_special_keys, $data_special_keys);
            if (!$insert_relation_idarr && !$delete_relation_idarr)
            {
                //只更新专题内容
                $content_data['content_id'] = $content_push['content_relation_id'];
                $content_data['cid']        = $content_push['content_id'];
                $special_obj->update_special_content($content_data);
                return true;
            }
            else if ($insert_relation_idarr)
            {
                //插入专题内容
                $content_data['special_id'] = implode(',', $insert_relation_idarr);
                $content_data['content_id'] = $content_push['content_relation_id'];
                $content_data['cid']        = $content_push['content_id'];
                $special_obj->insert_special_content($content_data);
            }
            else if ($delete_relation_idarr)
            {
                //通知专题删除内容
                $special_obj->delete_special_content(implode(',', $delete_relation_idarr), $content_push['content_id'], $data);
            }
            //更新content_push
            if ($data['special'])
            {
                if (!$content_push['content_relation_id'])
                {
                    unset($content_push['content_relation_id']);
                }
                self::update('content_push', ' id=' . $info['id'], $content_push);
            }
            else
            {
                self::delete_expand('content_push', $info['id']);
            }
        }
        else if ($data['special'])
        {
            //插入content_push
            self::insert('content_push', $content_push);
            //插入专题内容
            $content_data['special_id'] = implode(',', array_keys($data['special']));
            $content_data['content_id'] = $content_push['content_relation_id'];
            $content_data['cid']        = $content_push['content_id'];
            $special_obj->insert_special_content($content_data);
        }
    }

    public function push_block($data, $content_id, $content_relation_id, $check)
    {
        $content_push = array(
            'content_id' => $content_id,
            'content_relation_id' => $content_relation_id,
            'type' => 2,
            'relation_data' => serialize($data['block']),
        );
        include_once(ROOT_PATH . 'lib/class/block.class.php');
        $block_obj    = new block();
        $content_data = array(
            'title' => $data['title'],
            'brief' => $data['brief'],
            'indexpic' => $data['indexpic'],
            'outlink' => $data['outlink'],
            'content_fromid' => $data['content_fromid'],
            'bundle_id' => $data['bundle_id'],
            'module_id' => $data['module_id'],
        );
        $sql          = "SELECT * FROM " . DB_PREFIX . "content_push WHERE type=2 AND content_id={$content_id}";
        $info         = $this->db->query_first($sql);
        if ($info && !$content_relation_id)
        {
            $content_push['content_id']          = $info['content_id'];
            $content_push['content_relation_id'] = $info['content_relation_id'];
        }
        $sql                = "SELECT * FROM " . DB_PREFIX . "content_columns WHERE content_id={$content_id}";
        $content_columns    = $this->db->query_first($sql);
        $content_columnsarr = $content_columns['column_datas'] ? unserialize($content_columns['column_datas']) : array();
        foreach ($content_columnsarr as $k => $v)
        {
            $cc[$k] = $v['name'];
        }
        $content_data['publish_content_columns'] = $cc;
        if ($check && !empty($info))
        {
            //先判断content_push有没有记录
            $relation_data         = unserialize($info['relation_data']);
            $data_block_keys       = array_keys($data['block']);
            $relation_block_keys   = array_keys($relation_data);
            $insert_relation_idarr = array_diff($data_block_keys, $relation_block_keys);
            $delete_relation_idarr = array_diff($relation_block_keys, $data_block_keys);
            if (!$insert_relation_idarr && !$delete_relation_idarr)
            {
                //只更新专题内容
                $content_data['content_id'] = $content_push['content_relation_id'];
                $content_data['cid']        = $content_push['content_id'];
                $block_obj->update_block_content($content_data);
                return true;
            }
            else if ($insert_relation_idarr)
            {
                //插入专题内容
                $content_data['block_id']   = implode(',', $insert_relation_idarr);
                $content_data['content_id'] = $content_push['content_relation_id'];
                $content_data['cid']        = $content_push['content_id'];
                $block_obj->insert_block_content($content_data);
            }
            else if ($delete_relation_idarr)
            {
                //通知专题删除内容
                $block_obj->delete_block_content(implode(',', $delete_relation_idarr), $content_push['content_id'], $data);
            }
            if ($data['block'])
            {
                if (!$content_push['content_relation_id'])
                {
                    unset($content_push['content_relation_id']);
                }
                self::update('content_push', ' id=' . $info['id'], $content_push);
            }
            else
            {
                self::delete_expand('content_push', $info['id']);
            }
        }
        else if ($data['block'])
        {
            //插入content_push
            self::insert('content_push', $content_push);
            //插入专题内容
            $content_data['block_id']   = implode(',', array_keys($data['block']));
            $content_data['content_id'] = $content_push['content_relation_id'];
            $content_data['cid']        = $content_push['content_id'];
            $block_obj->insert_block_content($content_data);
        }
    }

    public function check_app($bundle_id, $module_id, $name)
    {
        $sql    = "SELECT * FROM " . DB_PREFIX . "app WHERE bundle='" . $bundle_id . "' AND father=0";
        $bundle = $this->db->query_first($sql);
        if (!$bundle)
        {
            $fid = self::insert('app', array('bundle' => $bundle_id, 'name' => $name, 'father' => 0));
        }
        else
        {
            $fid = $bundle['id'];
        }

        $sql    = "SELECT * FROM " . DB_PREFIX . "app WHERE bundle='" . $module_id . "' AND father=" . $fid;
        $module = $this->db->query_first($sql);
        if (!$module)
        {
            self::insert('app', array('bundle' => $module_id, 'name' => $name, 'father' => $fid));
        }
        return true;
    }

    public function update_column_content_num($column_ids, $add = true)
    {
        if ($add)
        {
            $sql = "update " . DB_PREFIX . "column set content_num=content_num+1 where id in(" . $column_ids . ")";
            $this->db->query($sql);
        }
        else
        {
            $sql = "update " . DB_PREFIX . "column set content_num=content_num-1 where id in(" . $column_ids . ")";
            $this->db->query($sql);
        }
    }

    //将子级插入五条到主内容表中
    public function insert_childs_to_content($bundle_id, $module_id, $struct_id, $struct_ast_id, $content_fromid, $content_rid)
    {
        if (!$bundle_id || !$module_id || !$struct_id || !$struct_ast_id)
        {
            return 'NO_PARAM';
        }
        $tablename = $bundle_id . '_' . $module_id . '_' . $struct_id . '_' . $struct_ast_id;
        if ($content_rid)
        {
            $content = $this->get_all_content_by_relationid($content_rid);
        }
        else
        {
            if (!$content_fromid)
            {
                return 'NO_ID';
            }
            $sql           = "SELECT id,expand_id FROM " . DB_PREFIX . $tablename . " WHERE content_fromid=" . $content_fromid;
            $child_content = $this->db->query_first($sql);
            if (!$child_content)
            {
                return 'NO_CHILD_INFO';
            }
            $sql           = "DESCRIBE " . DB_PREFIX . $tablename . " order_id ";
            $child_content = $this->db->query_first($sql);
            if ($child_content)
            {
                $order_str = ' ORDER BY order_id DESC ';
            }
            $sql            = "SELECT id,expand_id FROM " . DB_PREFIX . "content WHERE bundle_id='" . $bundle_id . "' AND expand_id=" . $child_content['expand_id'] . $order_str;
            $content        = $this->db->query_first($sql);
            $content['cid'] = $content['id'];
            if (!$content)
            {
                return 'NO_CONTENT';
            }
        }

        if (empty($content))
        {
            return 'NO_DATA';
        }

        $sql  = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE  expand_id=" . $content['expand_id'] . " ORDER BY order_id DESC LIMIT 5";
        $info = $this->db->query($sql);
        $pics = array();
        while ($row  = $this->db->fetch_array($info))
        {
            $ret = array();
            switch ($module_id)
            {
                case 'news':
                    $p               = unserialize($row['pic']);
                    $ret['title']    = $row['name'];
                    $ret['host']     = $p['host'];
                    $ret['dir']      = $p['dir'];
                    $ret['filepath'] = $p['filepath'];
                    $ret['filename'] = $p['filename'];
                    break;
                case 'tuji':
                    if ($row['pic'])
                    {
                        $p               = unserialize($row['pic']);
                        $ret['id']       = $row['content_fromid'];
                        $ret['title']    = $row['title'];
                        $ret['host']     = $p['host'];
                        $ret['dir']      = $p['dir'];
                        $ret['filepath'] = $p['filepath'];
                        $ret['filename'] = $p['filename'];
                    }
                    break;
                case 'contribute':
                    if ($row['pic'])
                    {
                        $p               = unserialize($row['pic']);
                        $ret['id']       = $row['content_fromid'];
                        $ret['title']    = $row['title'];
                        $ret['host']     = $p['host'];
                        $ret['dir']      = $p['dir'];
                        $ret['filepath'] = $p['filepath'];
                        $ret['filename'] = $p['filename'];
                    }
                    break;
            }
            $pics[] = $ret;
        }
        if ($pics)
        {
            //更新到content表中
            $this->update('content', ' id=' . $content['cid'], array('childs_data' => serialize($pics)));
        }
    }

    public function get_siteid_str()
    {
        $siteids = '';
        $sql     = "SELECT id,syn_id FROM " . DB_PREFIX . "site ORDER BY id";
        $info    = $this->db->query($sql);
        while ($row     = $this->db->fetch_array($info))
        {
            if ($row['syn_id'])
            {
                $siteids .= $tag . $row['syn_id'];
                $tag = ',';
            }
        }
        return $siteids;
    }

    public function check_material_by_content($content)
    {
        $content     = htmlspecialchars_decode($content);
        $pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r", '<script');
        $pregfind    = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '', '&#60;script');
        $content     = str_replace($pregfind, $pregreplace, $content);
        preg_match_all('/<img[^>]class=[\'|\"]image-refer[\'|\"][^>]src=[\'|\"]([^>]*?)livmedia\/livmedia\/vod_(\d*)\.png[\'|\"][\/]?>/is', $content, $mat_r1);
        preg_match_all('/<img[^>]src=[\'|\"]([^>]*?)livmedia\/livmedia\/vod_(\d*)\.png[\'|\"].*?class=[\'|\"]image-refer[\'|\"]([^>]*?)[\/]?>/is', $content, $mat_r2);

        preg_match_all('/<img[^>]class=[\'|\"]image-refer[\'|\"][^>]src=[\'|\"]([^>]*?)tuji\/tuji\/tuji_(\d*)\.png[\'|\"][\/]?>/is', $content, $mat_r3);
        preg_match_all('/<img[^>]src=[\'|\"]([^>]*?)tuji\/tuji\/tuji_(\d*)\.png[\'|\"].*?class=[\'|\"]image-refer[\'|\"]([^>]*?)[\/]?>/is', $content, $mat_r4);

        $mat_r1 = count($mat_r1[0]);
        $mat_r2 = count($mat_r2[0]);
        $mat_r3 = count($mat_r3[0]);
        $mat_r4 = count($mat_r4[0]);
        $result['video'] = $mat_r1?$mat_r1:$mat_r2;
        $result['tuji'] = $mat_r3?$mat_r3:$mat_r4;
        return $result;
    }

}

?>