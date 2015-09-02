<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'publishcontent');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class contentApi extends adminBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include site.class.php
     */
    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include(CUR_CONF_PATH . 'lib/content.class.php');
        $this->obj        = new content();
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $this->column     = new column();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function get_content_condition($to_count = false)
    {
        $condition   = $other_field = '';
        $condition .= " WHERE 1 ";
        if ($client_type = intval($this->input['client_type']))
        {
            $condition .= " AND cr.client_type='" . $client_type . "'";
        }
        $condition .= " AND r.is_complete=1 ";
        if ($this->settings['is_need_audit'])
        {
            $condition .= " AND r.status=1";
        }
        //支持多个栏目
        if ($min_id = intval($this->input['min_id']))
        {
            $condition .= " AND r.id>" . $min_id;
        }
        if ($this->input['id'])
        {
            $ids = hg_filter_ids($this->input['id']);
            $condition .= " AND r.id IN (" . $ids . ')';
        }
        if ($site_id = intval($this->input['site_id']))
        {
            $condition .= " AND r.site_id=" . $site_id;
        }
        $column_id   = urldecode($this->input['column_id']);
        if (!$column_id && ($column_name = trim($this->input['column_name'])))
        {
            $fid         = intval($this->input['fid']);
            $column_name = explode(',', $column_name);
            if (!$this->input['child_column'])
            {
                $sql  = 'SELECT parents FROM ' . DB_PREFIX . "column  WHERE id=" . $fid;
                $info = $this->db->query_first($sql);
                if ($info['parents'])
                {
                    $parents = explode(',', $info['parents']);
                    $fid     = $parents[count($parents) - 1];
                }
                if($fid)
                {
                    $sql = 'SELECT id FROM ' . DB_PREFIX . "column  WHERE concat(',', parents, ',') LIKE '%," . $fid . ",%' AND  name IN ('" . implode("','", $column_name) . "')";
                }
                else
                {
                    $sql = 'SELECT id FROM ' . DB_PREFIX . "column  WHERE  name IN ('" . implode("','", $column_name) . "')";
                }
            }
            else
            {
                $sql = 'SELECT id FROM ' . DB_PREFIX . "column  WHERE fid=$fid AND  name IN ('" . implode("','", $column_name) . "')";
            }
            $tmp_column = array();
            $info       = $this->db->query($sql);
            while ($row        = $this->db->fetch_array($info))
            {
                $tmp_column[] = $row['id'];
            }
            $column_id = implode(',', $tmp_column);
            if(!$column_id && $fid)
            {
                $column_id = $fid;
            }
        }
        if ($column_id)
        {
            $column_ids = '';
            $column_id  = explode(',', $column_id);
            $column_id  = "'" . implode("','", $column_id) . "'";
            if (!$this->input['not_need_child_column'])
            {
                $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs,content_num ', $column_id, 'id');
                $tag          = '';
                if (is_array($columns_data) && $columns_data)
                {
                    foreach ($columns_data as $k => $v)
                    {
                        $column_ids .= $tag . $v['childs'];
                        $tag = ',';
                    }
                }
            }
            else
            {
            	$column_ids = $column_id;
            }
            if ($column_ids)
            {
                $column_idarr = @array_unique(explode(',', $column_ids));
                $column_ids   = implode(',', $column_idarr);
                $condition .= " AND r.column_id in (" . $column_ids . ")";
            }
            else 
            {
            	$this->errorOutput(NO_COLUMN_ID);
            }
        }
        if ($bundle_id = urldecode($this->input['bundle_id']))
        {
            $bundle_idarr = explode(',', $bundle_id);
            $condition .= " AND r.bundle_id in ('" . implode("','", $bundle_idarr) . "')";
        }
        if ($this->input['exclude_special'])   //过滤专题内容
        {
            $condition .= " AND r.bundle_id != '" . $this->input['exclude_special'] . "'";
        }

        if ($this->input['exclude_bundle'])   //需要过滤的内容标识
        {
            $exclude_bundle = explode(',', $this->input['exclude_bundle']);
            $condition .= " AND r.bundle_id NOT IN('" . implode("','", $exclude_bundle) . "')";
        }

        if ($module_id = urldecode($this->input['module_id']))
        {
            $condition .= " AND r.module_id='" . $module_id . "'";
        }
        if (isset($this->input['weight']) && $this->input['weight'] !== '' && $this->input['weight'] != -1)
        {
            $condition .= " AND r.weight='" . intval($this->input['weight']) . "'";
        }
        if ($exclude_id = $this->input['exclude_id'])
        {
            $condition .= " AND r.content_id not in (" . $exclude_id . ")";
        }
        if ($cid = $this->input['cid'])
        {
            $cid = hg_filter_ids($this->input['cid']);
            $condition .= " AND r.content_id in (" . $cid . ")";
        }
        //查询权重
        if (isset($this->input['start_weight']) && intval($this->input['start_weight']) >= 0)
        {
            $condition .=" AND r.weight >= " . $this->input['start_weight'];
        }
        if (isset($this->input['end_weight']) && intval($this->input['end_weight']) >= 0)
        {
            $condition .=" AND r.weight <= " . $this->input['end_weight'];
        }
        if (isset($this->input['except_weight']) && $this->input['except_weight'] !== '')
        {
            $condition .=" AND r.weight != " . intval($this->input['except_weight']);
        }


        if (isset($this->input['is_have_indexpic']) && $this->input['is_have_indexpic'] !== '')
        {
            $condition .= " AND r.is_have_indexpic=" . intval($this->input['is_have_indexpic']);
        }
        if (isset($this->input['is_have_video']) && $this->input['is_have_video'] !== '')
        {
            $condition .= " AND r.is_have_video=" . intval($this->input['is_have_video']);
        }
        if ($start_time = trim(urldecode($this->input['starttime'])))
        {
            if ($start_time = strtotime($start_time))
            {
                $condition .= " AND r.publish_time >= '" . $start_time . "'";
            }
        }
        if ($end_time = trim(urldecode($this->input['endtime'])))
        {
            if ($end_time = strtotime($end_time))
            {
                $condition .= " AND r.publish_time <= '" . $end_time . "'";
            }
        }
        if ($create_user = trim($this->input['create_user'], ' '))
        {
            $condition .= " AND r.create_user='" . $this->input['create_user'] . "' ";
        }
        if ($publish_user = trim($this->input['publish_user'], ' '))
        {
            $condition .= " AND r.publish_user='" . $this->input['publish_user'] . "' ";
        }
        //查询发布的时间
        if ($this->input['date_search'])
        {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  r.publish_time > '" . $yesterday . "' AND r.publish_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  r.publish_time > '" . $today . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND r.publish_time > '" . $last_threeday . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  r.publish_time > '" . $last_sevenday . "' AND r.publish_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }

        //标题
        if ($this->input['k'] || $this->input['title'])
        {
            $title = $this->input['title'] ? $this->input['title'] : $this->input['k'];
            $title = $this->get_titleResult($title . ' ');
            if ($title)
            {
                if ($this->settings['App_textsearch'] && !$this->input['normal_search'])
                {
                    $condition .= " AND MATCH (title_unicode) AGAINST ('" . $title . "' IN BOOLEAN MODE )";
                    $other_field = ",MATCH (title_unicode) AGAINST ('" . $title . "' IN BOOLEAN MODE ) AS title_score";
                }
                else
                {
                    $condition .= " AND r.title_unicode like '%" . $title . "%'";
                    $not_use_title_score = true;
                }
            }
        }

        if ($keywords = urldecode($this->input['keywords']))
        {
            $keywordsstr = str_utf8_unicode($keywords);
            if ($keywordsstr)
            {
                if ($this->settings['App_textsearch'] && !$this->input['normal_search'])
                {
                    $condition .= " AND MATCH (keywords_unicode) AGAINST ('" . $keywordsstr . "' IN BOOLEAN MODE )";
                    $other_field = ",MATCH (keywords_unicode) AGAINST ('" . $keywordsstr . "' IN BOOLEAN MODE ) AS score";
                }
                else
                {
                    $condition .= " AND r.keywords_unicode like '%" . $keywordsstr . "%'";
                    $not_use_score = true;
                }
            }
        }

        if ($title_pinyin_str = urldecode($this->input['spell_title']))
        {
            $title_pinyin_str = get_spell_title($title_pinyin_str);
            if ($title_pinyin_str)
            {
                $condition .= " AND MATCH (title_pinyin) AGAINST ('" . $title_pinyin_str . "' IN BOOLEAN MODE )";
                $other_field = ",MATCH (title_pinyin) AGAINST ('" . $title_pinyin_str . "' IN BOOLEAN MODE ) AS title_pinyin";
            }
        }

        if ($this->input['need_group_cid'])
        {
            $condition .= " GROUP BY r.content_id ";
        }
        if (!$to_count)
        {
            $order_tag = true;
            $condition .= " ORDER BY ";
            if ($keywordsstr && !$not_use_score)
            {
                $condition .= " score DESC ";
                $order_tag = false;
            }
            else if ($title && !$not_use_title_score)
            {
                $condition .= " title_score DESC ";
                $order_tag = false;
            }
            else if ($spell_title_str)
            {
                $condition .= " title_pinyin DESC ";
                $order_tag = false;
            }
            //排序
            if ($sort_field = urldecode($this->input['sort_field']))
            {
                if (in_array($sort_field, array('weight', 'id', 'order_id', 'publish_time')))
                {
                    $condition .= ($order_tag ? " " : ",") . 'r.' . $sort_field . ' ';
                    $order_tag = false;
                    if ($sort_type = urldecode($this->input['sort_type']))
                    {
                        $condition .= in_array($sort_type, $this->settings['sort_keyword']) ? $sort_type : 'DESC';
                    }
                    if ($sort_field == 'weight')
                    {
                        $condition .= ' ,r.order_id DESC';
                    }
                }
                else if (in_array($sort_field, $this->settings['content_field']))
                {
                    $condition .= ($order_tag ? " " : ",") . 'r.' . $sort_field . ' ';
                    $order_tag = false;
                    if ($sort_type = urldecode($this->input['sort_type']))
                    {
                        $condition .= in_array($sort_type, $this->settings['sort_keyword']) ? $sort_type : 'DESC';
                    }
                }
                else
                {
                    $condition .= ($order_tag ? ' ' : ',') . "r.publish_time DESC ";
                    $order_tag = false;
                }
            }
            else
            {
                $condition .= ($order_tag ? ' ' : ',') . "r.order_id DESC ";
            }
            //$condition .= ($order_tag ? ' ' : ',') . "cr.id DESC ";
        }
        $result['condition']   = $condition;
        $result['other_field'] = $other_field;
        return $result;
    }

    /**
     * @Description    :    获取发布库内容列表
     * @Author         :    dong(dong@hoge.cn)
     * @Category       :    publishcontent
     * @Date           :    2014-2-14
     * @LastUpdateDate :    2014-2-14
     * @Copyright      :    hogesoft
     * @Param          :    site_id(站点id);column_id(栏目id，多个逗号隔开);client_type(终端id)
     * @Return         :    json
     */
    public function get_content()
    {
        if ($this->input['is_count'])
        {
            $this->get_content_count();
        }
        //content_id是内容关联表id
        $need_video    = urldecode($this->input['need_video']);
        $need_subtitle = urldecode($this->input['need_subtitle']);
        $weight_cssid  = intval($this->input['weight_cssid']);
        $need_catalog  = intval($this->input['need_catalog']);
        if ($content_id    = intval($this->input['content_id']))
        {
            $content_data = $this->obj->get_all_content_by_relationid($content_id);
            if ($content_data)
            {
                $column_data                 = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $content_data['column_id']);
                $site_data                   = $column_data['site_data'];
                unset($column_data['site_data']);
                $content_data['column_info'] = $column_data;
                if ($content_data['use_maincolumn'])
                {
                    $column_data                      = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $content_data['main_column_id']);
                    $site_data                        = $column_data['site_data'];
                    $content_data['content_url']      = $content_data['outlink'] ? $content_data['outlink'] : mk_content_url($site_data, $column_data, array('column_id' => $content_data['main_column_id']) + $content_data);
                    unset($column_data['site_data']);
                    $content_data['main_column_info'] = $column_data;
                }
                else
                {
                    $content_data['content_url'] = $content_data['outlink'] ? $content_data['outlink'] : mk_content_url($site_data, $column_data, $content_data);
                }
                if ($this->settings['App_catalog'])
                {
                    //取编目信息
                    $catalog_data                = array();
                    include(ROOT_PATH . 'lib/class/catalog.class.php');
                    $catalogobj                  = new catalog();
                    $catalog                     = $catalogobj->get_catalog($content_data['bundle_id'], $content_data['module_id'], $content_data['content_fromid']);
                    $content_data['catalog_new'] = $catalog;
                }
            }
            $this->addItem($content_data);
            $this->output();
        }
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        if (!$this->input['from_mkpublish'])
        {
            //$offset = $offset > 1000 ? 0 : $offset;
        }
        $count = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        if ($this->input['id'] || $this->input['cid'])
        {
            $count = 0;
        }
        $result      = $this->get_content_condition();
        $content_sql = $this->obj->get_content_sql($result['condition'], $offset, $count, $result['other_field'], $need_video, '', $need_subtitle, $this->is_useindex, $this->index_field, $need_catalog);
        if ($this->input['need_count'])
        {
            $totalcount = $this->return_content_count();
        }
        //memcache处理
        $memcache_result = $this->op_memcache($content_sql, $need_video, $need_subtitle, $offset, $count, 'get');
        if (!$memcache_result)
        {
            $content_data = $this->obj->get_content($result['condition'], $offset, $count, $result['other_field'], $need_video, '', $need_subtitle, $this->is_useindex, $this->index_field, $need_catalog);
            $this->op_memcache($content_sql, $need_video, $need_subtitle, $offset, $count, 'set', $content_data);
        }
        else
        {
            $content_data = $memcache_result;
        }

        if ($content_data['column_ids_arr'])
        {
            $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,cssid,is_outlink,linkurl ', implode(',', $content_data['column_ids_arr']));
        }
        if ($content_data['catalogs_arr'] && $this->settings['App_catalog'])
        {
            //取编目信息
            $catalog_data   = array();
            include(ROOT_PATH . 'lib/class/catalog.class.php');
            $catalogobj     = new catalog();
            $catalog_result = $catalogobj->getAllcontent($content_data['catalogs_arr']);
            if ($catalog_result && is_array($catalog_result))
            {
                foreach ($catalog_result as $k => $v)
                {
                    foreach ($content_data as $ko => $vo)
                    {
                        if ($k == $vo['content_id'])
                        {
                            $content_data[$ko]['catalog_new'] = $v;
                        }
                    }
                }
            }
        }
        unset($content_data['column_ids_arr']);
        unset($content_data['site_ids_arr']);
        unset($content_data['catalogs_arr']);
        foreach ($content_data as $k => $v)
        {
            //在这个里面配置内容url
            $content_data[$k]['column_info'] = empty($column_datas[$v['column_id']]) ? array() : $column_datas[$v['column_id']];
            $v['column_id']                  = $v['use_maincolumn'] ? $v['main_column_id'] : $v['column_id'];
            $content_data[$k]['content_url'] = $v['outlink'] ? $v['outlink'] : (empty($column_datas[$v['column_id']]) ? '' : (mk_content_url($column_datas[$v['column_id']], $column_datas[$v['column_id']], $v)));
            if (!empty($content_data[$k]['column_info']['cssid']))
            {
                $content_data[$k]['cssid'] = $content_data[$k]['column_info']['cssid'];
            }
            if ($weight_cssid !== 0)
            {
                if ($weight_cssid > 0)
                {
                    $content_data[$k]['cssid'] = $weight_cssid;
                }
                else if ($v['weight'])
                {
                    $content_data[$k]['cssid'] = $v['weight'];
                }
            }
            if (!$this->input['need_count'])
            {
                $this->addItem($content_data[$k]);
            }
            if ($v['bundle_id'] == 'special' && $this->input['id_to_fromid'])
            {
                $content_data[$k]['id'] = $v['content_fromid'];
            }
            $content_data[$k] = $content_data[$k] + (is_array($catalog_result[$v['cid']]) ? $catalog_result[$v['cid']] : array());
        }

        if ($this->input['need_count'])
        {
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $content_data);
        }
        else
        {
            //$this->addItem_withkey('',$content_data );
        }
        $this->output();
    }

    public function op_memcache($content_sql, $need_video, $need_subtitle, $offset, $count, $op = 'set', $content_data = array(), $is_count = false)
    {
        if (!$this->settings['is_open_contentcache'])
        {
            return false;
        }
        if (!$is_count)
        {
            $content_sql .= ($need_video ? 1 : 0) . ($need_subtitle ? 1 : 0);
            if ($offset > $this->settings['memcache_max_offset'] || $count > $this->settings['memcache_max_count'])
            {
                return false;
            }
        }
        $key             = md5($content_sql);
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $cache           = new Cache();
        $cell_data_cache = CUR_CONF_PATH . 'cache/contentlist';
        if ($op == 'set')
        {
            $cache->initialize($cell_data_cache);
            $cache->set($key, $content_data, 150);
            return true;
            //return $this->memcache_set($key, $content_data, APP_UNIQUEID);
        }
        else
        {
            $cache->initialize($cell_data_cache);
            $ret = $cache->get($key, true);
            if ($ret == 'no_file_dir')
            {
                return false;
            }
            return $ret;
        }
    }

    public function get_app_content()
    {
        //content_id是内容关联表id
        $need_video    = urldecode($this->input['need_video']);
        $need_subtitle = urldecode($this->input['need_subtitle']);
        $app           = urldecode($this->input['app']);
        if ($app)
        {
            $t   = explode(',', $app);
            $app = array(
                'bundle_id' => $t[0],
                'module_id' => $t[1],
                'struct_id' => $t[2],
            );
        }
        $offset       = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count        = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $result       = $this->get_content_condition();
        $content_data = $this->obj->get_content($result['condition'], $offset, $count, $result['other_field'], $need_video, $app, $need_subtitle, $this->is_useindex, $this->index_field);
        if ($content_data['column_ids_arr'])
        {
            $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,is_outlink,linkurl ', implode(',', $content_data['column_ids_arr']));
        }
        unset($content_data['column_ids_arr']);
        unset($content_data['site_ids_arr']);
        unset($content_data['catalogs_arr']);
        foreach ($content_data as $k => $v)
        {
            //在这个里面配置内容url
            $content_data[$k]['column_info'] = empty($column_datas[$v['column_id']]) ? array() : $column_datas[$v['column_id']];
            $v['column_id']                  = $v['use_maincolumn'] ? $v['main_column_id'] : $v['column_id'];
            $content_data[$k]['content_url'] = $v['outlink'] ? $v['outlink'] : (empty($column_datas[$v['column_id']]) ? '' : (mk_content_url($column_datas[$v['column_id']], $column_datas[$v['column_id']], $v)));
            if (!$this->input['need_count'])
            {
                $this->addItem($content_data[$k]);
            }
        }
        if ($this->input['need_count'])
        {
            $totalcount = $this->return_content_count();
            $this->addItem_withkey('total', $totalcount['total']);
            $this->addItem_withkey('data', $content_data);
        }
        else
        {
            //$this->addItem_withkey('',$content_data );
        }
        $this->output();
    }

    public function get_content_count()
    {
        $result          = $this->get_content_condition(true);
        $sql             = "SELECT COUNT(*) AS total " . $result['other_field'] . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id " . $result['condition'];
        $memcache_result = $this->op_memcache($sql, '', '', '', '', 'get', array(), true);
        if (!$memcache_result)
        {
            $total = $this->db->query_first($sql);
            $this->op_memcache($sql, '', '', '', '', 'set', $total, true);
        }
        else
        {
            $total = $memcache_result;
        }
        echo json_encode($total);
        exit;
    }

    public function return_content_count()
    {
        $result = $this->get_content_condition(true);
        if ($this->input['need_group_cid'])
        {
            $sql = "SELECT COUNT(1) AS total FROM (SELECT COUNT(*) AS total " . $result['other_field'] . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id " . $result['condition'] . ")aa";
        }
        else
        {
            $sql = "SELECT COUNT(*) AS total " . $result['other_field'] . " FROM " . DB_PREFIX . "content_client_relation cr LEFT JOIN " . DB_PREFIX . "content_relation r ON cr.relation_id=r.id " . $result['condition'];
        }
        $memcache_result = $this->op_memcache($sql, '', '', '', '', 'get', array(), true);
        if (!$memcache_result)
        {
            $total = $this->db->query_first($sql);
            $this->op_memcache($sql, '', '', '', '', 'set', $total, true);
        }
        else
        {
            $total = $memcache_result;
        }
        return $total;
    }

    public function get_content_around_condition($value, $field, $last)
    {
        $condition = '';
        $condition .= " WHERE r.is_complete=1 ";
        if ($this->settings['is_need_audit'])
        {
            $condition .= " AND r.status=1";
        }
        if ($site_id = intval($this->input['site_id']))
        {
            $condition .= " AND r.site_id=" . $site_id;
        }
        //支持多个栏目
        if ($column_id = urldecode($this->input['column_id']))
        {
            $column_ids   = '';
            $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs ', $column_id, 'id');
            $tag          = '';
            foreach ($columns_data as $k => $v)
            {
                $column_ids .= $tag . $v['childs'];
                $tag = ',';
            }
            if ($column_ids)
            {
                $condition .= " AND r.column_id in (" . $column_ids . ")";
            }
        }
        if ($client_type = intval($this->input['client_type']))
        {
            $condition .= " AND cr.client_type='" . $client_type . "'";
        }
        if (isset($this->input['weight']) && $this->input['weight'] !== '' && $this->input['weight'] != -1)
        {
            //			$levelLabel = array_flip($this->settings['levelLabel']);
            //			$condition .= " AND cr.weight='".$levelLabel[intval($this->input['weight'])]."'";
            $condition .= " AND r.weight='" . intval($this->input['weight']) . "'";
        }
        if ($bundle_id = urldecode($this->input['bundle_id']))
        {
            $condition .= " AND r.bundle_id='" . $bundle_id . "'";
        }
        if ($module_id = urldecode($this->input['module_id']))
        {
            $condition .= " AND r.module_id='" . $module_id . "'";
        }
        if (isset($this->input['is_have_indexpic']) && $this->input['is_have_indexpic'] !== '')
        {
            $condition .= " AND r.is_have_indexpic=" . intval($this->input['is_have_indexpic']);
        }
        if (isset($this->input['is_have_video']) && $this->input['is_have_video'] !== '')
        {
            $condition .= " AND r.is_have_video=" . intval($this->input['is_have_video']);
        }
        if ($field)
        {
            $mark      = $last ? '<' : '>';
            $sort_type = $last ? 'DESC' : 'ASC';
            /**
              if (in_array($field, array('weight', 'id', 'order_id', 'publish_time')))
              {
              $tag = 'r.';
              }
              else if (in_array($field, $this->settings['content_field']))
              {
              $tag = 'r.';
              }
              else
             */
            {
                $tag = 'r.';
            }
            $field = 'order_id';
            $condition .= " AND " . $tag . $field . $mark . "'" . $value . "'";
            $condition .= " ORDER BY " . $tag . $field . " " . $sort_type;
        }
        else
        {
            $condition .= " ORDER BY r.order_id DESC";
        }
        //$condition .= ",r.order_id DESC";
        $condition .= " LIMIT 1";
        return $condition;
    }

    /**
     * @Description    :    获取发布库内容上下篇
     * @Author         :    dong(dong@hoge.cn)
     * @Category       :    publishcontent
     * @Date           :    2014-2-14
     * @LastUpdateDate :    2014-2-14
     * @Copyright      :    hogesoft
     * @Param          :    site_id(站点id);column_id(栏目id，多个逗号隔开);client_type(终端id);content_id(内容rid)
     * @Return         :    json
     */
    public function get_content_around()
    {
        if ($content_id = intval($this->input['content_id']))
        {
            $content_data = $this->obj->get_all_content_by_relationid($content_id);
            //取这个id的上id
            $condition    = $this->get_content_around_condition($content_data['order_id'], 'order_id', true);
            $data         = $this->obj->get_content_by_condition($condition);
            if (!empty($data[0]))
            {
                $result['next_content'] = $data[0];
                $column_ids[]           = $data[0]['column_id'];
            }
            $condition = $this->get_content_around_condition($content_data['order_id'], 'order_id', false);
            $data      = $this->obj->get_content_by_condition($condition);
            if (!empty($data[0]))
            {
                $result['last_content'] = $data[0];
                $column_ids[]           = $data[0]['column_id'];
            }
            if ($column_ids)
            {
                $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,is_outlink,linkurl ', implode(',', $column_ids));
                if ($result['next_content'])
                {
                    $result['next_content']['content_url'] = $result['next_content']['outlink'] ? $result['next_content']['outlink'] : mk_content_url($column_datas[$result['next_content']['column_id']], $column_datas[$result['next_content']['column_id']], $result['next_content']);
                }
                if ($result['last_content'])
                {
                    $result['last_content']['content_url'] = $result['last_content']['outlink'] ? $result['last_content']['outlink'] : mk_content_url($column_datas[$result['last_content']['column_id']], $column_datas[$result['last_content']['column_id']], $result['last_content']);
                }
            }
        }
        else
        {
            $condition = $this->get_content_around_condition('', '', '');
            $data      = $this->obj->get_content_by_condition($condition);
            if (!empty($data[0]))
            {
                $result['next_content'] = $data[0];
                $column_ids[]           = $data[0]['column_id'];
            }
            $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,is_outlink,linkurl ', implode(',', $column_ids));
            if ($column_ids)
            {
                $result['next_content']['content_url'] = $result['next_content']['outlink'] ? $result['next_content']['outlink'] : mk_content_url($column_datas[$result['next_content']['column_id']], $column_datas[$result['next_content']['column_id']], $result['next_content']);
            }
        }
        $this->addItem($result);
        $this->output();
    }

    /**
     * @Description    :    获取迅搜上发布库内容
     * @Author         :    dong(dong@hoge.cn)
     * @Category       :    publishcontent
     * @Date           :    2014-2-14
     * @LastUpdateDate :    2014-2-14
     * @Copyright      :    hogesoft
     * @Param          :    site_id(站点id);column_id(栏目id，多个逗号隔开);client_type(终端id);
     * @Return         :    json
     */
    public function xs_get_content_condition()
    {
        $con_arr = array();
        $tag     = '';
        if ($site_id = intval($this->input['site_id']))
        {
            $con_arr['query'] .= 'site_id:(' . $site_id . ') ';
            $tag = 'AND';
        }
        //支持多个栏目
        if ($column_id = urldecode($this->input['column_id']))
        {
            /**
              $column_ids   = '';
              $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs ', $column_id, 'id');
              $_tag         = '';
              foreach ($columns_data as $k => $v)
              {
              $column_ids .= $_tag . $v['childs'];
              $_tag = ',';
              }
             * 
             */
            $column_idarr = explode(',', $column_id);
            $column_idarr = count($column_idarr) < 10 ? $column_idarr : array_slice($column_idarr, 0, 10, true);
            $column_ids   = implode("' OR '", $column_idarr);
            if ($column_ids)
            {
                $con_arr['query'] .= $tag . ' column_ids:(\'' . $column_ids . '\') ';
                $tag = 'AND';
            }
        }
        /**
          if ($exclude_column_id = urldecode($this->input['exclude_column_id']))
          {
          $column_ids   = '';
          $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs ', $exclude_column_id, 'id');
          $_tag         = '';
          foreach ($columns_data as $k => $v)
          {
          $column_ids .= $_tag . $v['childs'];
          $_tag = ',';
          }
          $column_idarr = explode(',', $column_ids);
          //$column_idarr = count($column_idarr) < 10 ? $column_idarr : array_slice($column_idarr, 0, 10, true);
          //$column_ids   = implode(' NOT ', $column_idarr);
          if ($column_idarr)
          {
          foreach($column_idarr as $k=>$v)
          {
          $con_arr['query'] .=  ' NOT column_ids:(' . $v . ') ';
          }
          $tag = 'AND';
          }
          }
         * 
         */
        if ($exclude_column_id = urldecode($this->input['exclude_column_id']))
        {
            $column_idarr = explode(',', $exclude_column_id);
            //$column_idarr = count($column_idarr) < 10 ? $column_idarr : array_slice($column_idarr, 0, 10, true);
            //$column_ids   = implode(' NOT ', $column_idarr);
            if ($column_idarr)
            {
                foreach ($column_idarr as $k => $v)
                {
                    //$con_arr['query'] .= ' NOT column_ids:(\'' . $v . '\') ';
                }
                //$tag = 'AND';
            }
            
            $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs ', $exclude_column_id, 'id');
            $_tag         = '';
            foreach ($columns_data as $k => $v)
            {
                $this->select_exclude_column_ids .= $_tag . $v['childs'];
                $_tag = ',';
            }
        }
        if ($bundle_id = urldecode($this->input['bundle_id']))
        {
            $con_arr['query'] .= $tag . ' bundle_id:(' . $bundle_id . ') ';
            $tag = 'AND';
        }
        if ($module_id = urldecode($this->input['module_id']))
        {
            $con_arr['query'] .= $tag . ' module_id:(' . $module_id . ') ';
            $tag = 'AND';
        }
        if (isset($this->input['is_have_indexpic']) && $this->input['is_have_indexpic'] !== '')
        {
            $con_arr['query'] .= $this->input['is_have_indexpic'] === '0' ? ($tag . ' client_type:(0) ') : ($tag . ' client_type:(1) ');
            $tag = 'AND';
        }
        if (isset($this->input['is_have_video']) && $this->input['is_have_video'] !== '')
        {
            $con_arr['query'] .= $this->input['is_have_video'] === '0' ? ($tag . ' is_have_video:(0) ') : ($tag . ' is_have_video:(1) ');
            $tag = 'AND';
        }
        if ($keywords = urldecode($this->input['keywords']))
        {
            $con_arr['query'] .= $tag . ' keywords:(' . $keywords . ') ';
            $tag = 'AND';
        }
        if ($search_text = $this->input['search_text'])
        {
            if ($this->input['search_field'])
            {
                $search_field = $this->input['search_field'] . ':';
            }
            else
            {
                $search_field = '';
            }
            $search_text = '(' . implode(' OR ', explode(',', $search_text)) . ')';
            $con_arr['query'] .= $tag . ' ' . $search_field . $search_text;
        }

        $from_publish_time = strtotime(trim(urldecode($this->input['starttime'])));
        $to_publish_time   = strtotime(trim(urldecode($this->input['endtime'])));
        if ($from_publish_time || $to_publish_time)
        {
            $con_arr['range']['publish_time'] = (empty($from_publish_time) ? null : $from_publish_time) . ',' . (empty($to_publish_time) ? null : $to_publish_time);
        }

        //排序
        if ($sort_type = urldecode($this->input['sort_type']))
        {
            $sort_type = strcasecmp($sort_type, 'ASC') === 0 ? true : false;
        }
        else
        {
            $sort_type = false;
        }
        if ($sort_field = urldecode($this->input['sort_field']))
        {
            if (in_array($sort_field, $this->settings['content_field']))
            {
                $con_arr['sort'][$sort_field] = $sort_type;
            }
            else
            {
                $con_arr['sort']['create_time'] = $sort_type;
            }
        }
        else
        {
            $con_arr['sort']['create_time'] = $sort_type;
        }

        //$fact_field   支持的字段搜索，多个逗号隔开
        if ($fact_field = urldecode($this->input['fact_field']))
        {
            $con_arr['setfacets_fields'] = array('field' => explode(',', $fact_field), 'count_type' => 1);
        }

        //热词
        if ($hotquery = intval($this->input['hotquery_limit']))
        {
            $con_arr['hotquery']['limit'] = $hotquery;
            if ($this->input['hotquery_type'])
            {
                $con_arr['hotquery']['type'] = $this->input['hotquery_type'];
            }
        }

        //针对某个字段添加权重索引词
        if ($addweight = $this->input['addweight'])
        {
            if ($search_text)
            {
                $con_arr['weight'] = array('title' => $search_text, 'brief' => $search_text);
            }
        }

        $con_arr['sort']['id'] = false;

        if (isset($this->input['offset']) || isset($this->input['count']))
        {
            $con_arr['limit'] = intval($this->input['count']) . ',' . intval($this->input['offset']);
        }

        return $con_arr;
    }

    public function xs_get_content()
    {
        $result = array();
        if ($this->input['search_text'])
        {
            $con_arr = $this->xs_get_content_condition();
            //print_r($con_arr);exit;
            $hign    = $this->input['search_text'] ? array('title', 'brief', 'content', 'keywords') : array();
            $result  = $this->xs_search($con_arr, 'search_config_publish_content', array('indexpic', 'video', 'column_datas'), $hign);
        }
        /**
          if(is_array($result['data']))
          {
          foreach ($result['data'] as $k => $v)
          {
          if($end = strpos($v['title'],"`__xx__`"))
          {
          $result['data'][$k]['title'] = substr($v['title'],0,$end);
          }
          }
          }
         * 
         */
        
        if ($this->select_exclude_column_ids)
        {
            $select_exclude_column_idarr = explode(',', $this->select_exclude_column_ids);
            foreach ($result['data'] as $k => $v)
            {
                $column_idsarr = explode(',', str_replace('\'', '', $v['column_ids']));
                foreach ($column_idsarr as $kk => $vv)
                {
                    if ($result['data'][$k]['column_datas'][$vv] && !in_array($vv, $select_exclude_column_idarr))
                    {
                        $result['data'][$k]['column_datas'] = array($vv => $result['data'][$k]['column_datas'][$vv]) + $result['data'][$k]['column_datas'];
                        break;
                    }
                }
            }
        }
        
        if ($this->input['need_count'])
        {
            $this->addItem_withkey('total', $result['count']);
            $this->addItem_withkey('data', is_array($result['data']) ? $result['data'] : array() );
        }
        else
        {
            $this->addItem_withkey('', $result['data']);
        }
        $this->output();
    }

    public function xs_get_hotquery()
    {
        $count = intval($this->input['count']);
        @include_once(ROOT_PATH . 'lib/class/textsearch.class.php');
        $ts    = new textsearch();
        $hot   = $ts->xs_get_hotquery($count);
        if (is_array($hot) && $hot)
        {
            foreach ($hot as $k => $v)
            {
                $r = array('title' => $k, 'num' => $v);
                $this->addItem($r);
            }
            $this->output();
        }
    }

    public function get_content_by_rid()
    {
        $id = intval($this->input['id']);
        if ($id)
        {
            $need_pages        = intval($this->input['need_pages']);
            $need_process      = intval($this->input['need_process']);
            $need_separate     = intval($this->input['need_separate']);
            $need_child_detail = intval($this->input['need_child_detail']);
            $not_need_content  = intval($this->input['not_need_content']);
            $need_special      = intval($this->input['need_special']);
            $sql               = "SELECT c.*,c.column_id as main_column_id,r.*,r.id as rid,c.id as id FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id =" . $id;
            $info              = $this->db->query_first($sql);
            if (empty($info))
            {
                $this->errorOutput('此内容已删除');
            }
            if ($info['indexpic'])
            {
                $info['indexpic'] = unserialize($info['indexpic']);
            }
            if ($info['video'])
            {
                $info['video']             = unserialize($info['video']);
                $info['video']['filename'] = str_replace('.mp4', '.m3u8', $info['video']['filename']);
            }
            if ($info['childs_data'])
            {
                $info['childs_data'] = unserialize($info['childs_data']);
            }
            if ($info['catalog'])
            {
                $info['catalog'] = unserialize($info['catalog']);
            }
            $info['title']               = $info['title'] ? htmlspecialchars_decode($info['title']) : '';
            $info['brief']               = $info['brief'] ? strip_tags(htmlspecialchars_decode($info['brief'])) : '';
            $info['create_time_format']  = date($this->settings['default_time_format'], $info['create_time']);
            $info['publish_time_stamp']  = $info['publish_time'];
            $info['create_time_stamp']   = $info['create_time'];
            $info['publish_time_format'] = date($this->settings['default_time_format'], $info['publish_time']);
            if ($info['column_id'])
            {
                $column_data = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['column_id']);
                $site_data   = $column_data['site_data'];
            }
            $result = array();
            if (!$not_need_content)
            {
                $apiname   = get_tablename($info['bundle_id'], $info['module_id'], $info['struct_id']);
                $filedir   = $this->settings['get_content_api_path'] . $apiname . '.class' . $this->settings['get_content_api_suffix'];
                $classname = $apiname;
                if (file_exists($filedir))
                {
                    $data       = array(
                        'id' => $info['expand_id'],
                        'url' => urldecode($this->input['url']),
                        'dir' => urldecode($this->input['dir']),
                        'need_pages' => urldecode($this->input['need_pages']),
                        'need_child_detail' => urldecode($this->input['need_child_detail']),
                        'need_process' => urldecode($this->input['need_process']),
                        'need_separate' => urldecode($this->input['need_separate']),
                        'child_offset' => intval($this->input['child_offset']),
                        'child_count' => intval($this->input['child_count']),
                        'indexpic' => empty($info['indexpic']) ? array() : $info['indexpic'],
                    ); //echo $filedir;echo $classname;exit;
                    include($filedir);
                    $module_get = new $classname();
                    $result     = $module_get->get_processed_content($data);
                    $result     = is_array($result) ? $result : array();
                }
            }

            $info1       = $info;
            $info1['id'] = $info['id'];
            if ($info['use_maincolumn'])
            {
                $column_data         = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['main_column_id']);
                $site_data           = $column_data['site_data'];
                $info['content_url'] = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, array('column_id' => $info['main_column_id']) + $info1);
            }
            else
            {
                $info['content_url'] = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, $info1);
            }
            $info['column_info'] = $column_data;

            if ($this->input['video2index'] && $result['content_material_list'])
            {
                foreach ($result['content_material_list'] AS $k => $v)
                {
                    if ($v['app'] == 'livmedia')
                    {
                        $videourl              = parse_url($v['video_url']);
                        $info['video']         = array(
                            'host' => $videourl['scheme'] . '://' . $videourl['host'],
                            'dir' => '',
                            'filepath' => $videourl['path'],
                            'filename' => '',
                        );
                        $info['is_have_video'] = '1';
                        break;
                    }
                }
            }
            if ($this->input['shorturl'] && $this->settings['App_shorturl'])
            {
                $curl = new curl($this->settings['App_shorturl']['host'], $this->settings['App_shorturl']['dir']);

                $curl->setReturnFormat('str');
                $curl->setSubmitType('post');
                $curl->initPostData();
                $curl->addRequestData('str', $info['content_url']);
                $ret = $curl->request('shorturl.php');
                if ($ret)
                {
                    $info['content_url'] = $ret;
                }
            }
            if ($this->settings['App_catalog'])
            {
                //取编目信息
                $catalog_data          = array();
                include(ROOT_PATH . 'lib/class/catalog.class.php');
                $catalogobj            = new catalog();
                $catalog               = $catalogobj->get_catalog($info['bundle_id'], $info['module_id'], $info['content_fromid']);
                $result['catalog_new'] = $catalog;
            }
            $result['source'] = isset($result['source']) ? $result['source'] : '';
            $info['source']   = isset($info['source']) ? $info['source'] : $result['source'];
            if ($result)
            {
                $result = $info + $result;
            }
            else
            {
                $result = $info;
            }

            //统计
            if ($this->input['need_access'])
            {
                include_once(ROOT_PATH . 'lib/class/access.class.php');
                $access_obj = new access();
                $fromType = ($this->input['fromType']) ? trim($this->input['fromType']) : "";
                $ret        = $access_obj->add_access($info['content_id'], $info['column_id'], $info['bundle_id'], $info['module_id'], $info['title'],$info['content_fromid'],$fromType);
            }

            //加广告
            if ($this->input['ad_group'])
            {
                $r                 = array();
                $r['id']           = $result['rid'];
                $r['colid']        = $result['column_id'];
                $r['title']        = $result['title'];
                $r['brief']        = $result['brief'];
                $r['keywords']     = $result['keywords'];
                $r['appid']        = $result['appid'];
                $r['appname']      = $result['appname'];
                $r['create_user']  = $result['create_user'];
                $r['publish_user'] = $result['publish_user'];
                $r['group']        = $this->input['ad_group'];
                $result['ad']      = $this->getAds($this->input['ad_group'], $r, $r['colid']);
            }

            //取这条内容发布到的专题
            if ($need_special && $info['content_id'])
            {
                $sql          = "SELECT relation_data FROM " . DB_PREFIX . "content_push WHERE type=1 AND content_id=" . $info['content_id'];
                $content_push = $this->db->query_first($sql);
                if ($content_push)
                {
                    $special_data = @unserialize($content_push['relation_data']);
                    if ($special_data)
                    {
                        $special_ids = array();
                        foreach ($special_data as $k => $v)
                        {
                            if ($v['special_id'] = intval($v['special_id']))
                            {
                                $special_ids[] = $v['special_id'];
                            }
                        }
                        if ($special_ids)
                        {
                            include(ROOT_PATH . 'lib/class/special.class.php');
                            $special_obj   = new special();
                            $special_datas = $special_obj->get_special_by_ids(implode(',', $special_ids));
                            if (is_array($special_datas))
                            {
                                $result['special_datas'] = $special_datas;
                            }
                        }
                    }
                }
            }

            $this->addItem($result);
            $this->output();
        }
        else
        {
            $this->errorOutput('NO_ID');
        }
    }

    /**
     * 根据多个rid来查询
     * */
    public function get_content_by_rids()
    {
        $id = urldecode($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('NO_ID');
        }
        $ret               = array();
        $need_pages        = intval($this->input['need_pages']);
        $need_process      = intval($this->input['need_process']);
        $need_child_detail = intval($this->input['need_child_detail']);
        $sql               = "SELECT c.*,c.column_id as main_column_id,r.*,r.id as rid,c.id as id FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id in(" . $id . ")";
        $q                 = $this->db->query($sql);
        while ($info              = $this->db->fetch_array($q))
        {
            $result = array();
            if ($info['indexpic'])
            {
                $info['indexpic'] = unserialize($info['indexpic']);
            }
            if ($info['video'])
            {
                $info['video']             = unserialize($info['video']);
                $info['video']['filename'] = str_replace('.mp4', '.m3u8', $info['video']['filename']);
            }
            if ($info['childs_data'])
            {
                $info['childs_data'] = unserialize($info['childs_data']);
            }
            $info['title']       = $info['title'] ? html_entity_decode($info['title']) : '';
            $info['brief']       = $info['brief'] ? html_entity_decode($info['brief']) : '';
            $column_data         = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['column_id']);
            $site_data           = $column_data['site_data'];
            $info['content_url'] = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, $info);

            $apiname   = get_tablename($info['bundle_id'], $info['module_id'], $info['struct_id']);
            $filedir   = $this->settings['get_content_api_path'] . $apiname . '.class' . $this->settings['get_content_api_suffix'];
            $classname = $apiname;
            if (file_exists($filedir))
            {
                $data           = array(
                    'id' => $info['expand_id'],
                    'url' => urldecode($this->input['url']),
                    'dir' => urldecode($this->input['dir']),
                    'need_pages' => urldecode($this->input['need_pages']),
                    'need_child_detail' => urldecode($this->input['need_child_detail']),
                    'need_process' => urldecode($this->input['need_process']),
                    'child_offset' => intval($this->input['child_offset']),
                    'child_count' => intval($this->input['child_count']),
                    'indexpic' => empty($info['indexpic']) ? array() : $info['indexpic'],
                );
                include_once($filedir);
                $module_get     = new $classname();
                $result         = $module_get->get_processed_content($data);
                $info['source'] = $info['source'] ? $info['source'] : $result['source'];
                if ($result)
                {
                    $result = $info + $result;
                }
                else
                {
                    $result = $info;
                }
                $this->addItem($result);
            }
        }
        $this->output();
    }

    public function get_content_detail()
    {
        $id = intval($this->input['id']);
        if ($id)
        {
            $need_child_detail = intval($this->input['need_child_detail']);
            $child_offset      = isset($this->input['child_offset']) ? intval($this->input['child_offset']) : 0;
            $child_count       = isset($this->input['child_count']) ? intval($this->input['child_count']) : 1000;
            $sql               = "SELECT r.id,r.column_id,r.column_name,r.file_name,r.file_domain,r.file_dir,r.file_custom_filename,c.expand_id,c.bundle_id,c.module_id,c.struct_id,c.indexpic,c.video,r.is_have_video, r.is_have_indexpic,c.title,c.brief,c.outlink,r.publish_time FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id =" . $id;
            $info              = $this->db->query_first($sql);
            if (empty($info))
            {
                $this->errorOutput('NO_MAIN_DATA');
            }
            if ($info['indexpic'])
            {
                $info['indexpic'] = unserialize($info['indexpic']);
            }
            if ($info['video'])
            {
                $info['video']             = unserialize($info['video']);
                $info['video']['filename'] = str_replace('.mp4', '.m3u8', $info['video']['filename']);
            }
            $info['title']        = $info['title'] ? html_entity_decode($info['title']) : '';
            $info['brief']        = $info['brief'] ? html_entity_decode($info['brief']) : '';
            $info['publish_time'] = date($this->settings['default_time_format'], $info['publish_time']);

            $column_data         = $this->column->get_site_column_first(' id,name,site_id,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype ', $info['column_id']);
            $site_data           = $column_data['site_data'];
            $info['content_url'] = $info['outlink'] ? $info['outlink'] : mk_content_url($site_data, $column_data, $info);

            $apiname   = get_tablename($info['bundle_id'], $info['module_id'], $info['struct_id']);
            $filedir   = $this->settings['get_content_api_path'] . $apiname . '.class' . $this->settings['get_content_api_suffix'];
            $classname = $apiname;
            if (file_exists($filedir))
            {
                include($filedir);
                $module_get = new $classname();
                $result     = $module_get->get_content_detail($info['expand_id'], '*', $need_child_detail, $child_offset, $child_count);
                if ($result['error'])
                {
                    $this->errorOutput($result['msg']);
                }
                if ($result)
                {
                    $result = $info + $result;
                }
                else
                {
                    $result = $info;
                }
                $this->addItem($result);
                $this->output();
            }
            else
            {
                $this->errorOutput('没有访问内容接口文件');
            }
        }
        else
        {
            $this->errorOutput('NO_ID');
        }
    }

    //废弃
    public function get_child_tablename()
    {
        $bundle_id = urldecode($this->input['bundle_id']);
        $module_id = urldecode($this->input['module_id']);
        $struct_id = urldecode($this->input['struct_id']);
        $sql       = "SELECT child_table FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND struct_id='" . $struct_id . "' AND struct_ast_id is not NULL";
        $data      = $this->db->query_first($sql);
        $this->addItem($data);
        $this->output();
    }

    public function get_relation_condition()
    {
        $condition = '';
        $condition .= " WHERE 1";
        if ($site_id   = intval($this->input['site_id']))
        {
            $condition .= " AND r.site_id=" . $site_id;
        }
        //支持多个栏目
        if ($column_id = urldecode($this->input['column_id']))
        {
            $column_ids   = '';
            $columns_data = $this->column->get_column_by_id(' id,name,fid,parents,childs ', $column_id, 'id');
            $tag          = '';
            if (is_array($columns_data))
            {
                foreach ($columns_data as $k => $v)
                {
                    $column_ids .= $tag . $v['childs'];
                    $tag = ',';
                }
            }
            if ($column_ids)
            {
                $condition .= " AND r.column_id in (" . $column_ids . ")";
            }
        }
        if ($client_type = intval($this->input['client_type']))
        {
            $condition .= " AND cr.client_type='" . $client_type . "'";
        }
        if (isset($this->input['weight']) && intval($this->input['weight']) >= 0)
        {
            //			$levelLabel = array_flip($this->settings['levelLabel']);
            //			$condition .= " AND cr.weight='".$levelLabel[intval($this->input['weight'])]."'";
            $condition .= " AND r.weight='" . intval($this->input['weight']) . "'";
        }
        if ($bundle_id = urldecode($this->input['bundle_id']))
        {
            $condition .= " AND r.bundle_id='" . $bundle_id . "'";
        }
        if ($module_id = urldecode($this->input['module_id']))
        {
            $condition .= " AND r.module_id='" . $module_id . "'";
        }
        $order_tag  = true;
        $condition .= " ORDER BY ";
        //排序
        if ($sort_field = urldecode($this->input['sort_field']))
        {
            if (in_array($sort_field, array('weight', 'id', 'order_id', 'publish_time')))
            {
                $condition .= ($order_tag ? " " : ",") . 'r.' . $sort_field . ' ';
                $order_tag = false;
                if ($sort_type = urldecode($this->input['sort_type']))
                {
                    $condition .= in_array($sort_type, $this->settings['sort_keyword']) ? $sort_type : 'DESC';
                }
            }
        }
        else
        {
            $condition .= ($order_tag ? ' ' : ',') . "r.order_id DESC ";
            $order_tag = false;
        }
        //$condition .= ($order_tag ? ' ' : ',') . "cr.id DESC ";
        $result['condition'] = $condition;
        return $result;
    }

    //暂停使用
    public function get_relation()
    {
        $content_data = array();
        $offset       = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count        = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $result       = $this->get_relation_condition();

        $sql  = "SELECT * FROM " . DB_PREFIX . "content_relation r " . $result['condition'];
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $content_data[]                   = $row;
            $column_id_arr[$row['column_id']] = $row['column_id'];
            $site_id_arr[$row['site_id']]     = $row['site_id'];
        }
        //查出栏目信息
        if ($column_id_arr)
        {
            $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,is_outlink,linkurl ', implode(',', $column_id_arr));
        }

        foreach ($content_data as $k => $v)
        {
            //在这个里面配置内容url
            $v['content_url'] = $v['outlink'] ? $v['outlink'] : (empty($column_datas[$v['column_id']]) ? '' : mk_content_url($column_datas[$v['column_id']], $column_datas[$v['column_id']], $v));
            $v['column_info'] = empty($column_datas[$v['column_id']]) ? array() : $column_datas[$v['column_id']];
            $this->addItem($v);
        }
        $this->output();
    }

    /**
     * 根据content_id获取内容
     * */
    public function get_content_by_cid()
    {
        $content_ids = $this->input['content_id'];
        $column      = $this->input['column'];
        if (!$content_ids)
        {
            $this->errorOutput('NO_CONTENT_IDS');
        }

        $ret           = $column_id_arr = array();
        $sql           = "select c.*,c.id as cid,c.column_id as main_column_id,r.* from " . DB_PREFIX . "content_relation r left join " . DB_PREFIX . "content c on r.content_id=c.id where r.content_id in(" . $content_ids . ")";
        $info          = $this->db->query($sql);
        while ($row           = $this->db->fetch_array($info))
        {
            $ret[$row['cid']][$row['column_id']] = $row;
            $column_id_arr[$row['column_id']]    = $row['column_id'];
        }

        $column_datas = $this->column->get_column_site_by_ids(' id,parents,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,is_outlink,linkurl ', implode(',', $column_id_arr));

        foreach ($ret as $k => $v)
        {
            $row        = array();
            $use_column = empty($column[$k]) ? '' : explode(',', $column[$k]);
            foreach ($v as $kk => $vv)
            {
                if (is_array($use_column))
                {
            		$ccolumns = explode(',', $column_datas[$kk]['parents']);
                    if (array_intersect($ccolumns, $use_column))
                    {
                        $row = $vv;
                    }
                }
                else
                {
                    $row = $vv;
                }
                if ($row)
                {
                    if ($row['indexpic'])
                    {
                        $row['indexpic'] = unserialize($row['indexpic']);
                    }
                    if ($row['video'])
                    {
                        $row['video']             = unserialize($row['video']);
                        $row['video']['filename'] = empty($row['video']['filename']) ? '' : str_replace('.mp4', '.m3u8', $row['video']['filename']);
                    }
                    $row['title']              = htmlspecialchars_decode($row['title']);
                    $row['childs_data']        = $row['childs_data'] ? unserialize($row['childs_data']) : array();
                    $row['publish_time_stamp'] = $row['publish_time'];
                    $row['create_time_stamp']  = $row['create_time'];
                    $row['create_time']        = date($this->settings['default_time_format'], $row['create_time']);
                    $row['publish_time']       = date($this->settings['default_time_format'], $row['publish_time']);
                    $row['brief']              = htmlspecialchars_decode($row['brief']);
                    $row['content_url']        = $row['outlink'] ? $row['outlink'] : mk_content_url($column_datas[$row['column_id']], $column_datas[$row['column_id']], $row);
                    $result[$k]                = $row;
                    break;
                }
            }
        }
        //	 	print_r($result);
        $this->addItem($result);
        $this->output();
    }

    /**
     * 根据内容原id 应用，模块标识获取内容
     * */
    public function get_content_by_other()
    {
        $content_fromid = $this->input['content_fromid'];
        $bundle_id      = $this->input['bundle_id'];
        $module_id      = $this->input['module_id'];
        $struct_id      = $this->input['struct_id'];
        if (!$content_fromid && !$bundle_id && !$module_id)
        {
            $this->errorOutput("缺少参数");
        }
        $sql = "SELECT r.* FROM " . DB_PREFIX . "content_relation r
	 			LEFT JOIN " . DB_PREFIX . "content c 
	 			ON r.content_id  = c.id
	 			WHERE r.bundle_id='" . $bundle_id . "' AND r.module_id='" . $module_id . "' AND r.content_fromid='" . $content_fromid . "'";
        if ($struct_id)
        {
            $sql .= " AND struct_id='" . $struct_id . "'";
        }
        $info = $this->db->query_first($sql);
        $this->addItem($info);
        $this->output();
    }

    /**
     * 获取内容id最大的一条
     * */
    public function get_max_id_content()
    {
        $sql  = "SELECT id FROM " . DB_PREFIX . "content_relation ORDER BY id DESC limit 1";
        $info = $this->db->query_first($sql);
        $this->addItem($info);
        $this->output();
    }

    public function test()
    {
        $this->memcache_set('a', 'aaaaa', 'a');
    }

    //批量发布生成到cms
    public function mk_content()
    {
        $offset       = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count        = $this->input['count'] ? intval(urldecode($this->input['count'])) : 1000000;
        $result       = $this->get_content_condition();
        $content_data = $this->obj->get_content($result['condition'], $offset, $count, $result['other_field'], $need_video, '', $need_subtitle);
        foreach ($content_data as $k => $v)
        {
            //插入到cotent_publish_time生成页面
            $this->obj->insert('content_publish_time', array('content_id' => $v['id'], 'publish_time' => TIMENOW));
        }
    }

    /**
     * 根据视频id获取电视剧剧集
     */
    public function get_tv_play_by_video_id()
    {
        $result = array();
        $id     = intval($this->input['id']);
        if (!$id)
        {
            $this->addItem($result);
            $this->output();
        }
        $sql  = "SELECT r.id,c.content_fromid,r.bundle_id,r.module_id,r.struct_id FROM " . DB_PREFIX . "content_relation r LEFT JOIN " . DB_PREFIX . "content c ON r.content_id=c.id WHERE r.id =" . $id;
        $info = $this->db->query_first($sql);
        if (!$info || !$info['content_fromid'])
        {
            $this->addItem($result);
            $this->output();
        }
        $tablename  = 'tv_play_tv_play_tv_play_tv_episode';
        $sql        = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE video_id=" . $info['content_fromid'];
        $tv_episode = $this->db->query_first($sql);
        if (!$tv_episode['expand_id'])
        {
            $this->addItem($result);
            $this->output();
        }
        $sql  = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE expand_id=" . $tv_episode['expand_id'] . " ORDER BY index_num";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $this->addItem($row);
        }
        $this->output();
    }

    /**
     * 
     */
    public function get_title_pic()
    {
        //参数
        //$title = "公安部派员督办东莞扫黄 重点打击保护伞";
        $title     = trim($this->input['title']);
        $fontface  = './data/宋体-粗体.ttf';
        //$width = '800';
        $width     = $this->input['width'];
        //$height = '60';
        $height    = $this->input['height'];
        $fontcolor = array(17, 92, 147);
        $bgcolor   = array(195, 232, 254);
        $fontsize  = ($height - $height / 2) / 4 * 3;
        $angle     = '0';
        $x         = $height - $height / 2;
        $y         = ($height - $fontsize) / 2 + $fontsize;



        //生成图片
        $image    = imagecreatetruecolor($width, $height);
        $bg_color = imagecolorallocate($image, $bgcolor[0], $bgcolor[1], $bgcolor[2]);
        imagefill($image, 0, 0, $bg_color);
        $color    = imagecolorallocate($image, $fontcolor[0], $fontcolor[1], $fontcolor[2]);
        imagettftext($image, $fontsize, $angle, $x, $y, $color, $fontface, $title);

        header("Content-Type:image/jpeg");
        $rand = rand(10000, 99999);
        $dir  = './data/' . $rand . '.jpg';
        imagejpeg($image, $dir);
        imagedestroy($image);

        //上传图片到图片服务器
        $file     = file_get_contents($dir);
        include_once ROOT_PATH . 'lib/class/material.class.php';
        $material = new material();
        $re       = $material->imgdata2pic(base64_encode($file), 'jpg');

        //删除图片
        if ($re)
        {
            unlink($dir);
        }

        //返回图片地址
        $this->addItem($re);
        $this->output();
    }

    /**
     * 获取文稿的浏览量
     */
    public function get_click_num()
    {
        $content_id = intval($this->input['content_id']);
        $sql        = 'SELECT * FROM dev_publishcontent_new.' . DB_PREFIX . 'content_relation WHERE content_fromid=' . $content_id . '';
        $info       = $this->db->query_first($sql);
        $this->addItem($info);
        $this->output();
    }

    //获取某用户的点击数 评论数
    public function get_clicknums()
    {
        $user_name   = $this->input['user_name'];
        $create_time = $this->input['create_startime'] ? $this->input['create_startime'] : strtotime('-1 year');
        $end_time    = $this->input['create_endtime'];
        $sql         = 'SELECT create_user, click_num , comment_num,create_time FROM ' . DB_PREFIX . 'content_relation WHERE click_num > 0 AND create_time > ' . $create_time . ' AND create_user LIKE "' . $user_name . '" GROUP BY create_time';
        $q           = $this->db->query($sql);
        while ($r           = $this->db->fetch_array($q))
        {
            $date = date('Ymd', $r['create_time']);
            $data[$date]['click_num'] += intval($r['click_num']);
            $data[$date]['comment_num'] += intval($r['comment_num']);
        }
        $ret['data']  = $data;
        $ret['total'] = count($data);
        $this->addItem($ret);
        $this->output();
    }
    
    public function get_content_list()
    {
    	$offset = $this->input['offset'] ? $this->input['offset'] : 0;
    	$count = $this->input['count'] ? $this->input['count'] : 15;
    	$result	= $this->get_content_condition();
    	$condition = $result['condition'] ? $result['condition'] : '';
    	$sql = 'SELECT distinct content_id FROM '.DB_PREFIX.'content_relation r '.$condition;
    	$sql .= 'LIMIT '.$offset.','.$count;
    	$q = $this->db->query($sql);
    	while($r = $this->db->fetch_array($q))
    	{
    		if($r['content_id'])
    		{
    			$cid[] = $r['content_id'];
    		}
    	}
    	if($cid)
    	{
    		$cids = implode(',',$cid);
    		$sql = 'SELECT c.*,r.column_id,r.click_num,r.comment_num,r.publish_time,r.file_dir,r.file_custom_filename,r.file_name,r.file_domain FROM '.DB_PREFIX.'content_relation r LEFT JOIN '.DB_PREFIX.'content c ON c.id = r.content_id WHERE c.id in('.$cids.')';
    		$query = $this->db->query($sql);
    		while($r = $this->db->fetch_array($query))
    		{
    			$r['publish_time'] = $r['publish_time'] ? date('Y-m-d H:i:s',$r['publish_time']) : '';
    			if($r['column_id'])
    			{
    				$column_ids[] = $r['column_id'];
    				$column[$r['id']][] = $r['column_id'];
    			}
    			$content[$r['id']] = $r;
    		}
    	}
     	if ($column_ids)
        {
            $column_datas = $this->column->get_column_site_by_ids(' id,name,fid,childdomain,father_domain,column_dir,relate_dir,col_con_maketype,cssid,is_outlink,linkurl ', implode(',', $column_ids));
        }
        if(!$content)
        {
        	$this->errorOutput(NOCONTENT);
        }
        foreach ($content as $k=>$v)
        {
        	if($column[$k])
        	{
        		foreach ($column[$k] as $vv)
        		{
        			$column_datas[$vv]['content_url'] = $v['outlink'] ? $v['outlink'] : (empty($column_datas[$vv]) ? '' : (mk_content_url($column_datas[$vv], $column_datas[$vv], $v)));
        			$v['column_info'][$vv] = $column_datas[$vv];
        		}
        	}
        	$data[] = $v;
        }
    	if($this->input['need_count'])
    	{
    		$sql = 'SELECT count( distinct r.content_id) as total FROM '.DB_PREFIX.'content c LEFT JOIN '.DB_PREFIX.'content_relation r ON c.id = r.content_id '.$condition;
    		$total = $this->db->query_first($sql);	
    		$ret['total'] = $total['total'];
    		$ret['data'] = $data;
    	}else
    	{
    		$ret = $data;
    	}
    	$this->addItem($ret);
    	$this->output();
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright 	ho	gesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new contentApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>
