<?php

class common extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    //根据id获取应用
    public function get_app()
    {
        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $auth = new auth();
        return $auth->get_app();
    }

    //获取模块
    public function get_module($application_id)
    {
        include_once(ROOT_PATH . 'lib/class/auth.class.php');
        $auth = new auth();
        return $auth->get_module($application_id);
    }

    /**
      //根据分类id获取模板和子模板分类
      public function get_template_sort($id,$search='')
      {
      if($search)
      {
      $sql = "SELECT id,name FROM ".DB_PREFIX."template_sort WHERE name like '%".$search."%'";
      $sort_info = $this->db->fetch_all($sql);
      $sql_ = "SELECT id,title FROM ".DB_PREFIX."templates WHERE title like '%".$search."%'";
      $template_info = $this->db->fetch_all($sql_);
      $info = array('sort'=>$sort_info,'template'=>$template_info);
      return $info;
      }
      else
      {
      if($id)
      {
      $sql = "SELECT id,name FROM ".DB_PREFIX."template_sort WHERE fid = ".$id;
      $sort_info = $this->db->fetch_all($sql);
      $sql_ = "SELECT id,title FROM ".DB_PREFIX."templates WHERE sort_id = ".$id;
      $template_info = $this->db->fetch_all($sql_);
      $info = array('sort'=>$sort_info,'template'=>$template_info);
      return $info;
      }
      else
      {
      $sql = "SELECT id,name FROM ".DB_PREFIX."template_sort WHERE fid=0";
      $sort_info = $this->db->fetch_all($sql);
      $info = array('sort'=>$sort_info,'template'=>array());
      return $info;
      }
      }

      }
     */
    //获取模板分类
    public function get_template_sort($tem_style_mark = '', $tem_sort_id, $search = '')
    {
        $con = '';
        $ret = array();
        if ($search)
        {
            $con .= " AND name like '%{$search}%'";
        }
        else
        {
            $con .= " AND fid=" . ($tem_sort_id ? $tem_sort_id : 0);
        }
        $con .= " ORDER by id";
        $sql  = "SELECT * FROM " . DB_PREFIX . "template_sort WHERE 1 " . $con;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    //获取模板
    public function get_template($tem_style_mark, $tem_sort_id = '', $client = '', $search = '')
    {
        $con = '';
        $ret = array();
        $con .= " AND template_style='{$tem_style_mark}' ";
        if ($client)
        {
            $con .= " AND client=" . $client;
        }
        if ($tem_sort_id)
        {
            $con .= " AND sort_id=$tem_sort_id ";
        }
        if ($search)
        {
            $con .= " AND title like '%{$search}%'";
        }
        $con .= " ORDER by id";
        $sql  = "SELECT * FROM " . DB_PREFIX . "templates WHERE 1 " . $con;
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    //根据分类id获取父分类id
    public function get_fsort($id)
    {
        $sql        = "SELECT fid FROM " . DB_PREFIX . "template_sort WHERE id = " . $id;
        $fsort_id   = $this->db->query_first($sql);
        $info['id'] = empty($fsort_id['fid']) ? 0 : $fsort_id['fid'];
        return $info;
    }

    //当栏目内容类型更改时，删除column_template中记录
    public function delete_column_template($column_id, $typeids)
    {
        //不删除本身，子级，内容模板
        $typeids = trim($typeids, ',') . ",'self','child','content'";
        $typeids = trim($typeids, ',');
        $sql     = "DELETE FROM " . DB_PREFIX . "column_template WHERE column_id=" . $column_id . " AND type not in(" . $typeids . ")";
        $this->db->query($sql);
    }

    //取审核通过的套系
    public function get_template_style($siteId = '')
    {
        $condition = '';
        if ($siteId)
        {
            $condition = " AND site_id IN (" . $siteId . ") OR site_id = 0 ";
        }
        $sql = "SELECT id,title,mark FROM " . DB_PREFIX . "template_style WHERE 1 AND state=1 " . $condition;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    //更加套系表示
    public function get_template_style_bymark($mark = '')
    {
        $condition = '';
        if ($mark)
        {
            $condition = " AND mark IN ('" . $mark . "')";
        }
        $sql = "SELECT isusing,isdefault FROM " . DB_PREFIX . "template_style WHERE 1  " . $condition;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $ret[] = $row;
        }
        return $ret;
    }

    //获取模板缓存
    public function get_template_cache($template_sign, $curr_style, $site_id, $material_url = '')
    {
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $this->cache = new cache();
        $this->cache->initialize(CUR_CONF_PATH . 'cache/template/');
        $style       = $curr_style ? $curr_style : $curr_style;
        $template    = $this->cache->get($site_id . '_' . $style . '_' . $template_sign);
        if (!$template)
        {
            $sql      = "SELECT * FROM " . DB_PREFIX . "templates WHERE template_style = '" . $style . "' AND sign = '" . $template_sign . "'";
            $re       = $this->db->query_first($sql);
            $template = self::set_cache($site_id . '_' . $style . '_' . $template_sign, stripslashes($re['content']), $re['site_id'], $re['sort_id']);
        }
        $template = str_replace('{$image_url}', $material_url ? rtrim($material_url, '/') : $this->settings['template_image_url'], $template);
        return $template;
    }
    
    /**根据标识取页面信息**/
    public function getPageBySign($strSign, $strFields = '*') {
        $sql = 'SELECT '.$strFields.' FROM ' . DB_PREFIX . 'page_manage WHERE sign = \'' . $strSign .'\'';
        $info = $this->db->query_first($sql);
        return $info;
    }
    //取页面类型
    public function get_page_manage($site_id, $page_id = '', $key = '')
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE 1 AND fid =0";
        if ($page_id)
        {
            $sql .= " AND id IN(" . $page_id . ")";
        }
        else
        {
            $sql .= " AND site_id=" . $site_id;
            $sql .= " ORDER BY id ";
        }
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row[$row['name_field']] = $row['title'];
            $row[$row['last_field']] = $row['has_child'] ? 0 : 1;
            $row['page_id']          = $row['id'];
            if ($key)
            {
                $ret[$row[$key]] = $row;
            }
            else
            {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    //取页面类型详细信息
    public function get_page_by_id($id, $in = false, $key = '')
    {
        $ret = array();
        if ($in)
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id in(" . $id . ")";
        }
        else
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id =" . $id;
        }
        if ($in)
        {
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                if ($key)
                {
                    $ret[$row[$key]] = $row;
                }
                else
                {
                    $ret[] = $row;
                }
            }
        }
        else
        {
            $ret = $this->db->query_first($sql);
        }
        return $ret;
    }

    //取页面类型中数据
    public function get_page_data($page_id, $offset = '', $count = '', $fid = 0, $pinfo = array(), $page_data_id = '')
    {
        if (!$pinfo)
        {
            $sql   = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE id = " . $page_id;
            $pinfo = $this->db->query_first($sql);
        }

        if (!$pinfo)
        {
            return false;
        }
        $data = $pinfo['argument'] ? unserialize($pinfo['argument']) : array();
        if ($pinfo['app'])
        {
            if ($this->settings['App_' . $pinfo['app']])
            {
                $pinfo['host'] = $this->settings['App_' . $pinfo['app']]['host'];
                $pinfo['dir']  = $this->settings['App_' . $pinfo['app']]['dir'];
            }
        }
        if (!$pinfo['host'])
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "page_manage WHERE 1 ";
            if($page_data_id)
            {
                $con = ' AND id='.$page_data_id;
            }
            else
            {
                $fid = $fid?$fid:$page_id;
                $con = ' AND fid='.$fid;
            }
            $sql .= $con;
            $info = $this->db->query($sql);
            while($row = $this->db->fetch_array($info))
            {
                $page_data[] = $row;
            } 
        }
        else
        {
        	if (!class_exists('curl'))
            {
                include_once(ROOT_PATH . 'lib/class/curl.class.php');
            }
            $this->curl = new curl($pinfo['host'], $pinfo['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
//            if ($page_data_id)
//            {
//                $this->curl->addRequestData('id', $page_data_id);
//            }
//            else
//            {
                $this->curl->addRequestData('id', $page_data_id);
                $this->curl->addRequestData('site_id', $pinfo['site_id']);
                $this->curl->addRequestData($pinfo['offset_field'], $offset);
                $this->curl->addRequestData($pinfo['count_field'], $count ? $count : 1000);
                $this->curl->addRequestData($pinfo['father_field'], $fid);
                if (is_array($data['ident']))
                {
                    foreach ($data['ident'] as $k => $v)
                    {
                        if ($v == $pinfo['father_field'] && $fid > 0)
                        {
                            continue;
                        }
                        if ($v == $pinfo['offset_field'] && $offset > 0)
                        {
                            continue;
                        }
                        if ($v == $pinfo['count_field'] && $count > 0)
                        {
                            continue;
                        }
                        $this->curl->addRequestData($v, $data['value'][$k]);
                    }
                }
//            }
            $this->curl->addRequestData('html', true);
            $ret       = $this->curl->request($pinfo['file_name']);
            $page_data = array();
            if (is_array($ret))
            {
                foreach ($ret as $k => $v)
                {
                    $page_data[$k]            = $v;
                    $page_data[$k]['id']      = $v[$pinfo['field']];
                    $page_data[$k]['fid']     = $v[$pinfo['father_field']];
                    $page_data[$k]['name']    = $v[$pinfo['name_field']];
                    $page_data[$k]['is_last'] = isset($v[$pinfo['last_field']]) ? $v[$pinfo['last_field']] : 1;
                    $page_data[$k]['can_select'] = $v['can_select'];
                }
            }
        }

        $result['page_data'] = $page_data;
        $result['page_info'] = $pinfo;
        return $result;
    }

    //根据站点页面类型页面数据获取部署信息
    public function get_deploy_templates($site_id, $page_id = '', $page_data_id = '')
    {
        if (!$site_id)
        {
            return false;
        }
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $this->cache = new cache();
        $this->cache->initialize(CUR_CONF_PATH . 'cache/deploy');
        $str         = $site_id;
        if ($page_id)
        {
            $str .= '_' . $page_id;
            if ($page_data_id)
            {
                $str .= '_' . $page_data_id;
            }
        }
        $data = $this->cache->get($str);
        return $data;
    }

    //取默认套系标识
    public function get_default_style()
    {
        $sql = "SELECT mark FROM " . DB_PREFIX . "template_style WHERE isdefault = 1";
        $ret = $this->db->query_first($sql);
        return $ret['mark'];
    }

    //取数据源列表
    public function get_data_source()
    {
        $sql = "SELECT id,name,argument FROM " . DB_PREFIX . "data_source  WHERE 1";
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row['argument'] = $row['argument'] ? unserialize($row['argument']) : array();
            $input_param     = array();
            if (is_array($row['argument']['argument_name']) && count($row['argument']['argument_name']) > 0)
            {
                foreach ($row['argument']['argument_name'] as $k => $v)
                {

                    if ($row['argument']['add_status'][$k] == 1)
                    {
                        $input_param[$k]['name']          = $v;
                        $input_param[$k]['sign']          = $row['argument']['ident'][$k];
                        $input_param[$k]['default_value'] = $row['argument']['value'][$k];
                        $input_param[$k]['type']          = $row['argument']['type'][$k];
                        $input_param[$k]['other_value']   = $row['argument']['other_value'][$k];
                        if ($input_param[$k]['type'] == 'select')
                        {
                            $input_param[$k]['other_value'] = hg_string_to_array($input_param[$k]['other_value']);
                        }
                        $input_param[$k]['value'] = $row['argument']['value'][$k];
                    }
                }
                $row['input_param'] = $input_param;
            }
            unset($row['argument']);
            $ret[] = $row;
        }
        return $ret;
    }

    //获取数据源参数配置
    public function get_datasource_info($id, $param = array())
    {
        $info = array();
        if ($id)
        {
            $sql              = "SELECT * FROM " . DB_PREFIX . "data_source WHERE id=" . $id;
            $info             = $this->db->query_first($sql);
            $info['argument'] = $info['argument'] ? unserialize($info['argument']) : array();
        }
        if (is_array($info['argument']['argument_name']) && count($info['argument']['argument_name']))
        {
            foreach ($info['argument']['argument_name'] as $k => $v)
            {
                $input_param[$k]['add_status']    = $info['argument']['add_status'][$k];
                $input_param[$k]['name']          = $v ? $v : $info['argument']['ident'][$k];
                $input_param[$k]['sign']          = $info['argument']['ident'][$k];
                $input_param[$k]['default_value'] = $info['argument']['value'][$k];
                $input_param[$k]['type']          = $info['argument']['type'][$k];
                $input_param[$k]['other_value']   = $info['argument']['other_value'][$k];
                if ($input_param[$k]['type'] == 'select')
                {
                    $input_param[$k]['other_value'] = hg_string_to_array($input_param[$k]['other_value']);
                }
                $input_param[$k]['value'] = isset($param['input_param'][$info['argument']['ident'][$k]]) ? $param['input_param'][$info['argument']['ident'][$k]] : $info['argument']['value'][$k];
            }
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "out_variable WHERE mod_id = 1 AND expand_id = " . $id;
        $q   = $this->db->query($sql);
        while ($row = $this->db->fetch_array($q))
        {
            $out_param[$row['id']] = $row;
        }
        $ret = array(
            'info' => $info,
            'input_param' => $input_param,
            'out_param' => $out_param,
        );
        return $ret;
    }

    //取样式列表
    public function get_mode($site_id = '', $content_type = '', $offset = 0, $count = 1000)
    {
        // if ($site_id && $site_id != '-1')
        // {
            // $condition .= " AND site_id = " . $site_id;
        // }
        $mode_type = ($content_type == 0 || $content_type == -1) ? '0' : '0,1';
        $condtion .= " AND mode_type IN( " . $mode_type . ")";
        $limit     = " limit {$offset},{$count}";
        $sql       = "SELECT id,title,indexpic,description,sort_id FROM " . DB_PREFIX . "cell_mode  WHERE 1 " . $condition . $limit;
        $q         = $this->db->query($sql);
        $ret       = array();
        while ($row = $this->db->fetch_array($q))
        {
            if ($row['indexpic'])
            {
                $row['indexpic'] = unserialize($row['indexpic']);
            }
            $ret[] = $row;
        }
        return $ret;
    }

    public function get_mode_infos($ids, $cssurl = '', $key = '')
    {
        $ret  = array();
        $sql  = 'SELECT * FROM ' . DB_PREFIX . 'cell_mode WHERE id IN(' . $ids . ')';
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $row['default_param'] = unserialize($row['default_param']);
            $css_cache_file       = CSS_CACHE_DIR . $row['id'] . '.php';
            if (!file_exists($css_cache_file))
            {
                $row['css'] = self::set_css_cache($row['css'], $row['site_id'], $row['id'], $cssurl);
            }
            else
            {
                $row['css'] = @file_get_contents($css_cache_file);
            }
            if ($key)
            {
                $ret[$row[$key]] = $row;
            }
            else
            {
                $ret[] = $row;
            }
        }
        return $ret;
    }

    //获取样式参数
    public function get_mode_variable($ids)
    {
        $ret  = array();
        $sql  = 'SELECT * FROM ' . DB_PREFIX . 'cell_mode_variable WHERE cell_mode_id IN(' . $ids . ')';
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[$row['cell_mode_id']][$row['sign']] = $row;
        }
        return $ret;
    }

    public function modeDetail($intCellId, $fields = '*') {
        $sql = "SELECT " . $fields . " FROM " . DB_PREFIX . "cell_mode WHERE id = " . $intCellId;
        $arModeDetail = $this->db->query_first($sql);
        return $arModeDetail;
    }

    //取样式详细信息
    public function get_mode_info($id, $cell_id = '', $css_id = '', $js_id = '', $param = array())
    {
        $ret                        = array();
        $sql                        = "SELECT * FROM " . DB_PREFIX . "cell_mode WHERE id = " . $id;
        $mode_info                  = $this->db->query_first($sql);
        $mode_info['default_param'] = $mode_info['default_param'] ? unserialize($mode_info['default_param']) : array();
        $sql                        = "SELECT * FROM " . DB_PREFIX . "cell_mode_variable  WHERE cell_mode_id=" . $id;
        $info                       = $this->db->query($sql);
        $mode_param                 = $data_param                 = array();
        while ($row = $this->db->fetch_array($info))
        {
            if ($row['type'] == 'select')
            {
                $row['other_value'] = hg_string_to_array($row['other_value']);
            }
            ###合并参数
            $row['name']            = $row['name'] ? $row['name'] : $row['sign'];
            $row['value']           = isset($param['mode_param'][$row['sign']]) ? $param['mode_param'][$row['sign']] : $row['default_value'];
            $mode_param[$row['id']] = $row;
        }
        $sql          = "SELECT * FROM " . DB_PREFIX . "cell_mode_code WHERE mode_id = " . $id . " AND type='js' AND del = 0 ";
        $mode_js_info = $this->db->query_first($sql);
        if ($mode_js_info)
        {
            $mode_js_info['para'] = unserialize($mode_js_info['para']);
            if (is_array($mode_js_info['para']) && count($mode_js_info['para']) > 0)
            {
                $relation = array();
                foreach ($mode_js_info['para'] as $k => $v)
                {
                    $v['js_other_value']      = hg_string_to_array($v['js_other_value']);
                    ###合并参数
                    $v['js_value']            = isset($param['js_param'][$v['js_sign']]) ? $param['js_param'][$v['js_sign']] : $v['js_default_value'];
                    ### 重新复制 使返回值名称统一
                    $variables[$v['js_sign']] = array(
                        'name' => $v['js_name'] ? $v['js_name'] : $v['js_sign'],
                        'sign' => $v['js_sign'],
                        'type' => $v['js_type'],
                        'other_value' => $v['js_other_value'],
                        'default_value' => $v['js_default_value'],
                        'value' => $v['js_value'],
                    );
                    $relation[$v['js_sign']]  = $v['js_value'];
                }
                $ret['js_param'] = $variables;
            }
            $mode_js_info['code'] = html_entity_decode($mode_js_info['code'], ENT_QUOTES);
            if ($mode_js_info['code'])
            {
                if (!class_exists('Parse'))
                {
                    include_once(CUR_CONF_PATH . 'lib/parse.class.php');
                }
                $parse     = new Parse();
                $parse->parse_cssjs($mode_js_info['code'], $relation);
                $ret['js'] = $parse->built_cssjs($cell_id . '_' . $id . '_js' . '.php', true);
            }
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "out_variable WHERE mod_id = 2  AND flag = 0 AND expand_id = " . $id;
        $q   = $this->db->query($sql);
        while ($row = $this->db->fetch_array($q))
        {
            $row['fuction_value']   = unserialize($row['fuction_value']);
            $row['fuction_value']   = $row['fuction_value'] ? $row['fuction_value'] : array();
            $row['fuction_value']   = implode("','", $row['fuction_value']);
            $row['fuction_value']   = "'" . $row['fuction_value'] . "'";
            $data_param[$row['id']] = $row;
        }
        ###css处理   js处理 命名空间替换、参数替换、格式化参数格式确保参数格式统一
        if ($css_id)
        {
            $sql = "SELECT * FROM " . DB_PREFIX . "cell_mode_code WHERE id = " . intval($css_id) . " AND del = 0 ";
            $q   = $this->db->query($sql);
            while ($row = $this->db->fetch_array($q))
            {
                $prefix      = $row['type'];
                $relation    = $variables   = array();
                $row['para'] = unserialize($row['para']);
                if (is_array($row['para']) && count($row['para']) > 0)
                {
                    foreach ($row['para'] as $k => $v)
                    {
                        $v[$prefix . '_other_value']        = hg_string_to_array($v[$prefix . '_other_value']);
                        ###合并参数
                        $v[$prefix . '_value']              = isset($param[$prefix . '_param'][$v[$prefix . '_sign']]) ? $param[$prefix . '_param'][$v[$prefix . '_sign']] : $v[$prefix . '_default_value'];
                        ### 重新复制 使返回值名称统一
                        $variables[$v[$prefix . '_sign']] = array(
                            'name' => $v[$prefix . '_name'] ? $v[$prefix . '_name'] : $v[$prefix . '_sign'],
                            'sign' => $v[$prefix . '_sign'],
                            'type' => $v[$prefix . '_type'],
                            'other_value' => $v[$prefix . '_other_value'],
                            'default_value' => $v[$prefix . '_default_value'],
                            'value' => $v[$prefix . '_value'],
                        );
                        $relation[$v[$prefix . '_sign']]  = $v[$prefix . '_value'];
                    }
                }
                $ret[$prefix . '_code']  = html_entity_decode($row['code']);
                $ret[$prefix . '_param'] = $variables;
                if ($ret[$prefix . '_code'])
                {
                    if (!class_exists('Parse'))
                    {
                        include_once(CUR_CONF_PATH . 'lib/parse.class.php');
                    }
                    $parse        = new Parse();
                    $parse->parse_cssjs($ret[$prefix . '_code'], $relation);
                    $ret[$prefix] = $parse->built_cssjs($cell_id . '_' . $id . '_' . $prefix . '.php', true);
                }
            }
        }
        ###css，js处理处理结束
        ##取样式的css列表
        $sql             = "SELECT * FROM " . DB_PREFIX . "cell_mode_code WHERE mode_id = " . $id . " AND (cell_id = " . $cell_id . " OR cell_id = 0 ) AND type='css' AND del = 0 ";
        $q               = $this->db->query($sql);
        $ret['css_list'] = array();
        while ($row             = $this->db->fetch_array($q))
        {
            $prefix      = $row['type'];
            $row['para'] = unserialize($row['para']);
            if (is_array($row['para']) && count($row['para']) > 0)
            {
                $tmp = array();
                foreach ($row['para'] as $k => $v)
                {
                    $v[$prefix . '_other_value'] = hg_string_to_array($v[$prefix . '_other_value']);
                    $tmp[$v[$prefix . '_sign']]  = array(
                        'name' => $v[$prefix . '_name'] ? $v[$prefix . '_name'] : $v[$prefix . '_sign'],
                        'sign' => $v[$prefix . '_sign'],
                        'type' => $v[$prefix . '_type'],
                        'other_value' => $v[$prefix . '_other_value'],
                        'default_value' => $v[$prefix . '_default_value'],
                        'value' => $v[$prefix . '_default_value'],
                    );
                }
                $row['para'] = $tmp;
            }
//			$ret[$prefix . '_list'][$row['id']] = $row; 
            $ret[$prefix . '_list'][] = $row;
        }
        $ret['mode_info']  = $mode_info;
        $ret['mode_param'] = $mode_param;
        $ret['data_param'] = $data_param;
        return $ret;
    }

    //获取样式分类列表
    public function get_mode_sort($site_id = '')
    {
        $condition = '';
        // if ($site_id)
        // {
            // $condition .= " AND site_id = " . $site_id;
        // }
        $sql       = "SELECT id, site_id, name, brief FROM " . DB_PREFIX . "cell_mode_sort WHERE 1 " . $condition;
        $q         = $this->db->query($sql);
        $mode_sort = array();
        while ($row       = $this->db->fetch_array($q))
        {
            $mode_sort[$row['id']] = $row;
        }
        return $mode_sort;
    }

    public function set_css_cache($css, $site_id, $mode_id, $cssurl)
    {
        $css      = html_entity_decode($css, ENT_QUOTES);
        $css      = preg_replace('/url\([\'|\"]?(.*?)[\'|\"]?\)/ie', "self::get_css_url(\$site_id,\$mode_id,'\\1',\$cssurl)", $css);
        $filepath = CSS_CACHE_DIR;
        if (!is_dir($filepath))
        {
            mkdir($filepath, 0777, true);
        }
        $css_cache_file = $filepath . $mode_id . '.php';
        file_put_contents($css_cache_file, $css);
        return $css;
    }

    public function get_css_url($site_id, $mode_id, $filename, $url = '')
    {
        if (!$site_id || !$mode_id || !$filename)
        {
            return false;
        }
        $url    = $url ? $url : $this->settings['mode_image_url'];
        $imgurl = $url . '/' . $site_id . '/' . $mode_id . '/' . $filename;
        if (!file_exists($imgurl))
        {
            $imgurl = $url . '/' . $site_id . '/default/' . $filename;
        }
        $str = "url(" . $imgurl . ")";
        return $str;
    }

    public function set_cache($sign, $content, $site_id, $sort_id)
    {
        $param         = '{$image_url}/' . $site_id . '/' . $sort_id . '/';
        $str           = $content;
        $pregs         = array(
            '/<link(.*?)href=(\'|\")(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^"]+)\/([^"]+)\\2(.*?)[\/]?>/i',
            '/src=(\'|\")(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^"]+)\/([^"]+)\\1/i',
            '/url\([\'|\"]?(?!(\s*(http[s]?\:\/\/)?\s*www\.|\s*http[s]?\:\/\/))\s*([^\'"]+?)[\'|\"]?\)/i'
        );
        $pregs_replace = array(
            '<link \\1href="' . $param . '\\5/\\6"\\7/>',
            'src="' . $param . '\\4/\5"',
            'url(' . $param . '\\3)'
        );
        $str           = preg_replace($pregs, $pregs_replace, $str);  //css样式匹配   $css[2] 路径  $css[3]文件名
        include_once(CUR_CONF_PATH . 'lib/cache.class.php');
        $this->cache   = new cache();
        $this->cache->initialize(CUR_CONF_PATH . 'cache/template/');
        $this->cache->set($sign, $str);

        return $str;
    }

    //调用数据源接口取数据
    public function get_content_by_datasource($id, $data)
    {
        if (!is_file($this->settings['data_source_dir'] . $id . '.php'))
        {
            self::build_api($id);
        }
        include_once($this->settings['data_source_dir'] . $id . '.php');
        $class      = 'ds_' . $id;
        $this->data = new $class();
        $ret        = $this->data->show($data);
        return $ret;
    }

    //生成单个API文件
    function build_api($id)
    {
        $tpl = CUR_CONF_PATH . '/api/apitpl.php';
        if (!is_readable($tpl))
        {
            $this->errorOutput(NOT_ALLOW_READ);
        }
        $tpl_str = '';
        $tpl_str = @file_get_contents($tpl);
        if (!$tpl_str)
        {
            $this->errorOutput(NOT_ALLOW_READ);
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "data_source WHERE id =" . $id;
        $g   = $this->db->query($sql);
        while ($j   = $this->db->fetch_array($g))
        {
            if ($j['app_id'])
            {
                $app       = 'App_' . $j['app_id'];
                $j['host'] = $this->settings[$app]['host'];
                $j['dir']  = $this->settings[$app]['dir'];
            }
            $return[] = $j;
        }
        $sum     = count($return);
        $setting = $return[0];
        $sql_    = "SELECT * FROM " . DB_PREFIX . "out_variable  WHERE mod_id =1 AND depath =3  AND expand_id =  " . $id;
        $q       = $this->db->query($sql_);
        while ($re      = $this->db->fetch_array($q))
        {
            $out_arment[$re['name']] = $re['value'];
        }
        $curl_settings = $setting;
        unset($curl_settings['args']);
        $class_name    = explode('.', $setting['request_file']);
        $class_name    = 'ds_' . $id;
        $curl_settings = serialize($curl_settings);
        $fieldreleate  = serialize($out_arment);
        $handler       = array(
            '{$file_name}',
            '{$class_name}',
            '{$args}',
            '{$settings}',
            '{$fieldreleate}'
        );
        $replace_value = array(
            $setting['request_file'],
            $class_name,
            $setting['argument'],
            $curl_settings,
            $fieldreleate,
        );
        $tpl_str       = str_replace($handler, $replace_value, $tpl_str);
        hg_mkdir($this->settings['data_source_dir']);
        @file_put_contents($this->settings['data_source_dir'] . $id . '.php', $tpl_str);
    }

    public function get_special_cell_list($special_id, $template_id, $special_column = '',$material_url='')
    {
        if (!$special_id || !$template_id) {
            return false;
        }
        if (!class_exists('Maigc')) {
            include (CUR_CONF_PATH . 'lib/magic.class.php');
        }
        $objMagic = new Magic($site_id, 13, $special_id, $special_column, $template_id); 
        $response = $objMagic->searchCell(false, $material_url);
        if (is_array($response['layouts']) && count($response['layouts']) > 0) {
            foreach ($response['layouts'] as $k => $v) {
                $response['cell'] = $v['cells'] ? array_merge($response['cell'], $v['cells']) : $response['cell'];
            }   
        }
        $ret = array(
            'default_cell'  => $response['cell'],
            'template'      => $response['template'],
        );
        return $ret;
    }
    
    /**
     * 生成时调用此方法获取当前页面部署的模板和模板单元信息
     * 
     * @param int $intSiteId  站点信息   专题时可不传
     * @param int $intPageId  页面信息   专题时可不传
     * @param int $intPageDataId 栏目信息 专题时专题id
     * @param int $intContentType 内容类型 专题时专题栏目
     * @param str $strMaterialUrl 素材地址
     * @param int $intTemplateId 模板id,  有此参数时是专题
     * @return array $ret  当前页面的模板和单元列表
     */
    public function getTemplateAndCell($intSiteId, $intPageId, $intPageDataId, $intContentType, $strMaterialUrl, $intTemplateId='') {
        //$intTemplateId && ($intPageId == 0) && ($intPageId = 13);  //$intTemplateId为真时是专题
        if (!class_exists('Maigc')) {
            include (CUR_CONF_PATH . 'lib/magic.class.php');
        }  
        $objMagic = new Magic($intSiteId, $intPageId, $intPageDataId, $intContentType, $intTemplateId);
        $response = $objMagic->searchCell(false, $strMaterialUrl);   
        if (is_array($response['layouts']) && count($response['layouts']) > 0) {
            foreach ($response['layouts'] as $k => $v) {
                $response['cell'] = $v['cells'] ? array_merge($response['cell'], $v['cells']) : $response['cell'];
            }   
        }
        $ret = array(
            'default_cell'  => $response['cell'],
            'template'      => $response['template'],
        );
        return $ret;            
    }

    public function get_cell_list($site_id, $page_id, $page_data_id, $content_type, $template_sign = '', $site_info = '', $column_info = '')
    {
        if (!class_exists('Maigc')) {
            include (CUR_CONF_PATH . 'lib/magic.class.php');
        }        
        $objMagic = new Magic($site_id, $page_id, $page_data_id, $content_type); 
        $response = $objMagic->searchCell(false, $material_url);        
        ###取站点、栏目信息
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub = new publishconfig();
        if (!$site_info)
        {
            $site_info = $this->pub->get_site_first('*', $site_id);
        }
        if ($page_data_id && !$column_info)
        {
            $page_data   = self::get_page_data($page_id, '', '', '', '', $page_data_id);
            $column_info = $page_data['page_data'][0];
        }
        $deploy = self::merge_cell($site_id, $page_id, $page_data_id, $content_type, $site_info, $template_sign);
        $cell   = $deploy['default_cell'];
        ###单元中样式、数据源参数处理、样式代码、css、js代码解析
        if (is_array($cell) && count($cell) > 0)
        {
            $data_source_ids = array();
            foreach ($cell as $key => $value)
            {
                $mode_info = self::get_mode_info($value['cell_mode'], $value['id'], $value['css_id'], $value['js_id'], $value['param_asso']);
                if ($value['data_source'])
                {
                    $data_source_ids[$value['id']] = $value['data_source'];
                    $data_source                   = self::get_datasource_info($value['data_source'], $value['param_asso']);
                    $map                           = self::get_cell_map($mode_info, $data_source, $value['param_asso']);
                }
                else
                {
                    $map = self::get_mode_map($mode_info);
                }
                $cache_file                      = $value['id'] . '.php';
                include_once(CUR_CONF_PATH . 'lib/parse.class.php');
                $parse                           = new Parse();
                $content                         = $mode_info['mode_info']['content'];
                $parse->parse_template($content, $value['id'], $mode_info['mode_info'], $map['relation_map'], $map['mode_variable_map'], $map['variable_function_relation']);
                $parse->built_mode_cache($cache_file);
                //专题链接处理
                if (strpos($cell[$key]['more_href'], 'COLURL') !== false) {
                    
                }
                $cell[$key]                      = array_merge($cell[$key], $mode_info);
                $cell[$key]['mode_detail']       = $cell[$key]['mode_info'];
                unset($cell[$key]['mode_info']);
                $cell[$key]['data_source_info']  = $data_source['info'];
                $cell[$key]['input_param']       = $data_source['input_param'];
                $cell[$key]['input_param_other'] = $map['data_input_variable'];
                $cell[$key]['site_id']           = $site_id;
                $cell[$key]['page_id']           = $page_id;
                $cell[$key]['page_data_id']      = $page_data_id;
                $cell[$key]['content_type']      = $content_type;
            }
        }
        return array('default_style' => $deploy['default_style'], 'curr_style' => $deploy['curr_style'], 'template_sign' => $deploy['template_sign'], 'default_cell' => $cell, 'data_source_ids' => $data_source_ids);
    }

    public function merge_cell($site_id, $page_id = '', $page_data_id = '', $content_type = '', $site = array(), $template_sign = '')
    {
        $default_style = $this->settings['tem_style_default'];
        //当前部署的模板名
        if (!$template_sign)
        {
            include_once(CUR_CONF_PATH . 'lib/rebuild_deploy.class.php');
            $deploy          = new rebuilddeploy();
            $deploy_template = $deploy->get_deploy_templates($site_id, $page_id, $page_data_id);
            //赛选此客户端类型下该内容类型下部署的模板
            $client_type     = $this->input['client_type'] ? $this->input['client_type'] : 2;
            $template        = $deploy_template[$client_type][$content_type]['template_sign'];
        }
        else
        {
            $template = $template_sign;
        }
        //默认套系中该模板单元
        $condition        = " AND c.template_style='" . $default_style . "' AND c.template_sign IN('" . $template . "') AND c.original_id = 0";
        $default_cell     = self::get_cell($condition);
        //默认套系中页面单元
        $condition        = "  AND c.template_style='" . $default_style . "' AND c.template_sign IN('" . $template . "') AND c.site_id = " . $site_id . " AND c.page_id=" . $page_id . " AND c.page_data_id=" . $page_data_id . " AND c.content_type = " . $content_type . " AND c.original_id != 0";
        $default_set_cell = self::get_cell($condition);
        //第一次合并  默认套系中的模板单元和默认套系中的页面单元
        if (is_array($default_set_cell) && count($default_set_cell))
        {
            foreach ($default_set_cell as $k => $v)
            {
                if (array_key_exists($k, $default_cell))
                {
                    $default_cell[$k] = $default_set_cell[$k];
                }
            }
        }
        //当前使用套系该模板单元
        if (!$site)
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $this->pub = new publishconfig();
            $site      = $this->pub->get_site_first('tem_style', $site_id);
        }
        $condition  = " AND c.template_style='" . $site['tem_style'] . "' AND c.template_sign IN('" . $template . "') AND c.content_type = " . $content_type . " AND c.original_id = 0";
        $using_cell = self::get_cell($condition);
        //第二次合并 第一次合并后的单元和当前使用套系模板单元
        if (is_array($using_cell) && count($using_cell) > 0)
        {
            foreach ($using_cell as $k => $v)
            {
                if (array_key_exists($k, $default_cell))
                {
                    if ($using_cell[$k]['cell_mode'] || $using_cell[$k]['data_source'])
                    {
                        $default_cell[$k] = $using_cell[$k];
                    }
                }
            }
        }
        //当前使用套系页面单元
        $condition      = " AND c.template_style='" . $site['tem_style'] . "' AND c.template_sign IN('" . $template . "') AND c.site_id=" . $site_id . " AND c.page_id=" . $page_id . " AND c.page_data_id=" . $page_data_id . " AND c.content_type = " . $content_type . " AND c.original_id != 0 ";
        $using_set_cell = self::get_cell($condition);
        //第三次合并  第二次合并后的单元和当前使用套系页面单元
        if (is_array($using_set_cell) && count($using_set_cell) > 0)
        {
            foreach ($using_set_cell as $k => $v)
            {
                if (array_key_exists($k, $default_cell))
                {
                    $default_cell[$k] = $using_set_cell[$k];
                }
            }
        }
        $ret = array(
            'default_style' => $default_style,
            'curr_style' => $site['tem_style'],
            'template_sign' => $template,
            'default_cell' => $default_cell,
        );
        return $ret;
    }

    function get_template_cell($site_id, $template_id, $template_sign)
    {
        $default_style = $this->settings['tem_style_default'];
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub     = new publishconfig();
        $site          = $this->pub->get_site_first('tem_style', $site_id);
        $curr_style    = $site['tem_style'];
        $condition     = " AND site_id = " . $site_id . " AND c.page_id = 0 AND c.page_data_id = 0 AND c.content_type = 0 AND c.template_id = " . intval($template_id) . " AND c.original_id = 0";
        $cell          = self::get_cell($condition);
        $ret           = array(
            'default_style' => $default_style,
            'curr_style' => $curr_style,
            'template_sign' => $template_sign,
            'default_cell' => $cell,
        );
        return $ret;
    }

    //根据条件查询单元
    public function get_cell($condition, $limit = '')
    {
        $sql = "SELECT c.*
				FROM " . DB_PREFIX . "cell c 
				WHERE 1 AND c.del=0 " . $condition . $limit;
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q))
        {
            $row['param_asso']                                    = unserialize($row['param_asso']);
            $row['create_time']                                   = date('Y-m-d H:i', $row['create_time']);
            $ret[$row['template_sign'] . '_' . $row['cell_name']] = $row;
        }
        return $ret;
    }

    //获取单元缓存
    //$cell_id 单元id
    public function get_cell_cache($cell_id)
    {
        if (!$cell_id)
        {
            return false;
        }
        $cache_file = MODE_CACHE_DIR .substr(md5($cell_id . '.php'), 0, 2).'/'. $cell_id . '.php';
        $content    = '';
        if (file_exists($cache_file))
        {
            $content = file_get_contents($cache_file);
        }
        return $content;
    }

    //根据数据源标识取数据源
    public function get_datasource_by_sign($type, $page_sign)
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "data_source WHERE type='" . $type . "' AND sign='" . $page_sign . "'";
        return $this->db->query_first($sql);
    }

    /**
     *  获取单元中变量哈希表
     * 	@param array $cell_mode_info   样式信息 
     * 	@param array $data_source_info 数据源参数
     * 	@param array $param            单元里设置的个性化参数和参数关联  
     * 				 $param['input_param']   单元里设置的数据源条件参数  '变量名'  => '设置值' 个性化值和默认值相同时该变量键值对不存在
     * 				 $param['mode_param']    单元里设置的样式参数值 		'变量名'  => '设置值' 个性化值和默认值相同时该变量键值对不存在
     * 				 $param['assoc_param']   关联参数  12(样式输出参数id) => 65,64(数据源返回参数id)
     * @return array $ret
     * 				 $ret['mode_variable_map']  	样式参数键值对     'mode_value' => 1 
     * 				 $ret['data_input_variable']	数据源条件参数哦键值对 'count'  => 10 
     * 				 $ret['variable_function_relation']	参数函数管理信息 'title' => array('function' => 'cutstr','param' => '2,3')
     * 				 $ret['relation_map']  参数关联信息 'title' => 'data>0>title'
     */
    public function get_cell_map($cell_mode_info, $data_source_info = array(), $param = array())
    {
        $mode_variable              = $cell_mode_info['mode_param'];
        $mode_data_variable         = $cell_mode_info['data_param'];
        $data_input_variable        = $data_source_info['input_param'];
        $data_variable              = $data_source_info['out_param'];
        $data_input_variable_map    = $relation_map               = $mode_variable_map          = $variable_function_relation = array();
        if (is_array($mode_data_variable) && count($mode_data_variable) > 0)
        {
            foreach ($mode_data_variable as $k => $v)
            {
                if ($v['mode_fuction'])
                {
                    $variable_function_relation[$v['name']] = array('function' => $v['mode_fuction'], 'param' => $v['fuction_value']);
                }
            }
        }
        if (is_array($data_input_variable) && count($data_input_variable) > 0)
        {
            foreach ($data_input_variable as $k => $v)
            {
                if ($v['type'] == 'auto')
                {
                    if ($v['sign'] == 'need_count')
                    {
                        $v['value'] = $cell_mode_info['mode_info']['need_pages'] ? 1 : 0;
                    }
                    else {

                    }
                }
                $val = explode('.', $v['value']);
                switch ($val[0])
                {
                    case 'site':
                        $v['value'] = $this->arPageSiteInfo[$val[1]];
                        break;
                    case 'column':
                        $v['value'] = $this->arPageColumnInfo[$val[1]];
                        break;
                    case 'client':
                        $v['value'] = $this->arPageClientInfo[$val[1]];
                        break;
                    case 'special':
                        $v['value'] = $this->arPageSpecialInfo[$val[1]];
                        break;
                    case 'special_column':
                        $v['value'] = $this->arPageSpecialColumnInfo[$val[1]];
                        break;
                }                
                $data_input_variable_map[$v['sign']] = $v['value'];
            }
        }
        if (is_array($mode_variable) && count($mode_variable) > 0)
        {
            foreach ($mode_variable as $k => $v)
            {
                $mode_variable_map[$v['sign']] = $v['value'];
            }
        }
        if (is_array($param['assoc_param']) && count($param['assoc_param']) > 0)
        {
            foreach ($param['assoc_param'] as $k => $v)
            {
                $index    = $mode_data_variable[$k]['name'];
                $v        = array_reverse(explode(',', $v));
                $relation = $space    = '';
                foreach ($v as $kk => $vv)
                {
                    $relation .= $space . $data_variable[$vv]['name'];
                    $space = '>';
                }
                $relation_map[$index] = $relation;
            }
        }
        elseif (is_array($mode_data_variable) && count($mode_data_variable) > 0)
        {
            foreach ($mode_data_variable as $k => $v)
            {
                if (is_array($data_variable) && count($data_variable) > 0)
                {
                    foreach ($data_variable as $kk => $vv)
                    {
                        if ($v['name'] == $vv['name'])
                        {
                            $parents  = array_reverse(explode(',', $vv['parents']));
                            $relation = $space    = '';
                            foreach ($parents as $kkk => $vvv)
                            {
                                $relation .= $space . $data_variable[$vvv]['name'];
                                $space = '>';
                            }
                            $relation_map[$v['name']] = $relation;
                            break;
                        }
                    }
                }
            }
        }
        return array('mode_variable_map' => $mode_variable_map, 'relation_map' => $relation_map, 'data_input_variable' => $data_input_variable_map, 'variable_function_relation' => $variable_function_relation);
    }

    //获取样式变量和样式输出变量哈希表
    public function get_mode_map($mode_info)
    {
        $mode_variable              = $mode_info['mode_param'];
        $mode_data_variable         = $mode_info['data_param'];
        $relation_map               = $mode_variable_map          = $variable_function_relation = array();
        if (is_array($mode_data_variable) && count($mode_data_variable) > 0)
        {
            foreach ($mode_data_variable as $k => $v)
            {
                if ($v['mode_fuction'])
                {
                    $variable_function_relation[$v['name']] = array('function' => $v['mode_fuction'], 'param' => $v['fuction_value']);
                }
            }
        }
        if (is_array($mode_variable) && count($mode_variable) > 0)
        {
            foreach ($mode_variable as $k => $v)
            {
                $mode_variable_map[$v['sign']] = $v['value'];
            }
        }
        if (is_array($mode_data_variable) && count($mode_data_variable) > 0)
        {
            foreach ($mode_data_variable as $k => $v)
            {
                $index    = $v['name'];
                $parents  = array_reverse(explode(',', $v['parents']));
                $relation = $space    = '';
                foreach ($parents as $kk => $vv)
                {
                    $relation .= $space . $mode_data_variable[$vv]['name'];
                    $space = '>';
                }
                $relation_map[$index] = $relation;
            }
        }
        return array('mode_variable_map' => $mode_variable_map, 'relation_map' => $relation_map, 'variable_function_relation' => $variable_function_relation);
    }

    function get_template_layoutcss($layout_ids)
    {
        if (!$layout_ids)
        {
            return false;
        }
        $sql    = "SELECT * FROM " . DB_PREFIX . "layout WHERE id IN(" . $layout_ids . ")";
        $q      = $this->db->query($sql);
        $cssstr = '<style type="text/css">';
        while ($row    = $this->db->fetch_array($q))
        {
            $row['css'] = html_entity_decode($row['css']);
            $cssstr .= $row['css'] . ' ';
        }
        $cssstr .= '</style>';
        return $cssstr;
    }

    //模板中单元分析
    public function cell_analyse($id, $content)
    {
        $id = intval($this->input['id']);

        //获取上传文件内容并调用模板比较器
        $sql               = "SELECT * FROM " . DB_PREFIX . "templates WHERE id = " . $id;
        $formerly_template = $this->db->query_first($sql);

        $table            = self::analyse_result($formerly_template['content'], $content);
        $table['content'] = $content;
        include_once (CUR_CONF_PATH . 'lib/template.class.php');
        $this->template   = new template();
        $file_units       = $this->template->parse_templatecell($content);
        $units            = $file_units[1];
        $unitnum          = count($units);

        $tp_exist_cell = $this->template->get_exist_cell($id);

        $celladding = array_diff($units, $tp_exist_cell); //将要增加的单元
        $celldeling = array_diff($tp_exist_cell, $units); //将要删除的单元
        foreach ($celladding as $k => $v)
        {
            $add_info[] = $file_units[0][$k];
        }
        $add_cell[] = $add_info;
        foreach ($celladding as $k => $v)
        {
            $add[] = $v;
        }

        $add_cell[] = $add;

        $table['celladding'] = implode(',', $celladding);
        $table['celldeling'] = implode(',', $celldeling);


        $cell_info = array(
            'template_id' => $id,
            'template_sign' => $formerly_template['sign'],
            'sort_id' => $formerly_template['sort_id'],
            'site_id' => $formerly_template['site_id'],
            'template_style' => $formerly_template['template_style'],
        );
        $re        = array();
        $re        = array(
            'upcell' => array('celladding' => $celladding,
                'celldeling' => $celldeling,
                'add_cell' => $add_cell,
                'cell_info' => $cell_info,),
            'table' => $table,
            'content' => $content,
        );
        return $re;
    }

    function analyse_result($original, $target)
    {
        $return = analyse($original, $target);
        $table1 = draw_table($return[0], 1, 'org_');
        $table2 = draw_table($return[1], 0, 'tar_');

        $table1_fix = ($table2[2] > $table1[2] ? $table2[2] - $table1[2] : 0);
        $table2_fix = ($table1[2] > $table2[2] ? $table1[2] - $table2[2] : 0);
        $str        = '<style>
		.compare{
			width:480px;
			float:left;
			table-layout:fixed;
			overflow:auto;
			height:430px;
			float:left;
		}
	
		.code{
			width:40%;
			word-break:keep-all;
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
		}
	
		.fix
		{
			background-color:#EADAEB;
		}
	
		.notsame
		{
			background-color:#DD5C67;
		}
	
		.span_diff
		{
			background-color:#DD5C67;
		}
	
		.span_same
		{
			background:transparent;
		}
	
		.blank
		{
			background-color:#FFFFFF;
		}
	
		.hover
		{
			background-color:#ffffce;
		}
		</style>
		<script>
			$(document).ready(
			function(){
				jQuery("tr").each(
					function(){
						$(this).mouseover(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).addClass("hover");
								$("#tar_" + id).addClass("hover");
							}
						);
						$(this).mouseout(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).removeClass("hover");
								$("#tar_" + id).removeClass("hover");
							}
						);
					}
				);
				jQuery(".compare").each(
					function(){
						$(this).scroll(
							function(){
								var id = this.id.substr(4);
								$("#org_" + id).scrollTop($(this).scrollTop());
								$("#tar_" + id).scrollTop($(this).scrollTop());
								$("#org_" + id).scrollLeft($(this).scrollLeft());
								$("#tar_" + id).scrollLeft($(this).scrollLeft());
							}
						);
					}
				);
			}
			);
		</script>';
        $str_1      = '<div style="float:left;"><div class="comp-head"><div class="comp-thead">原模板内容</div><div class="comp-thead">新模板内容</div></div>';
        $str1       = $str . $str_1 . '<div class="compare" id="org_container" ><table border="0" cellspacing="0" cellpadding="0" class="">';
        $str1 .= $table1[0];
        $str3       = '';
        for ($i = 1; $i <= $table1_fix; $i++)
        {
            $str1 . '<tr class="fix" id="org_' . $table1[2] . '"><td colspan="2">' . ($i + $table1[1]) . '</td></tr>';
            $table1[2]++;
        }
        $str1 .= '</table></div>';

        $str2 = $str . '<div class="compare" id="tar_container"><table border="0" cellspacing="0" cellpadding="0" class="">';
        $str2 .= $table2[0];
        $str3 .= $table2[0];
        for ($i = 1; $i <= $table2_fix; $i++)
        {
            $str2 .= '<tr class="fix" id="tar_' . $table2[2] . '"><td colspan="2">&nbsp;</td></tr>';
            $table2[2]++;
        }
        $str2 .= '</table></div></div>';
        $table = array($str1, $str2, $target);
        return $table;
    }

    /**
     * 解析单元信息
     *
     * @param string $content
     * @return array
     */
    function parse_cell($content = "")
    {
        $eregtag = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_([\\s\\S]+?(?=<\/span>))<\/span>/is';
        //$eregtag = '/<span[\s]+id="livcms_cell".+?[\s]+name="(.+?)">([\\s\\S]+?(?=<\/span>))<\/span>/is';
        preg_match_all($eregtag, $content, $match);
        return $match;
    }

    function get_special_info($special_id)
    {
        if (!class_exists('special'))
        {
            include_once(ROOT_PATH . 'lib/class/special.class.php');
        }
        $this->special = new special();
        $special_info  = $this->special->detail($special_id);
        return $special_info;
    }
    
    function get_special_column_info($special_column_id)
    {
        if (!class_exists('special')) {
            include(ROOT_PATH . 'lib/class/special.class.php');
        }
        $this->special = new special();
        $special_column_info = $this->special->special_column_info($special_column_id);
        return $special_column_info;  
    }   
    

    function get_mode_default_css($mode_id)
    {
        if (!$mode_id)
        {
            return false;
        }
        $sql = "SELECT id FROM " . DB_PREFIX . "cell_mode_code WHERE mode_id = " . $mode_id . " AND type='css' LIMIT 1 ";
        $ret = $this->db->query_first($sql);
        return $ret['id'];
    }

    //取专题里含有的布局
    function get_special_layout($special_id)
    {
        if (!$special_id)
        {
            return array();
        }
        $sql             = "SELECT * FROM " . DB_PREFIX . "template_layout WHERE special_id = " . $special_id;
        $template_layout = $this->db->query_first($sql);
        return $template_layout;
    }

    function layout_process($layout_ids, $template = '')
    {
        if (!$layout_ids)
        {
            return array();
        }
        $sql          = "SELECT id, content, css, is_header, header_text, is_more, more_href,original_id FROM " . DB_PREFIX . "layout WHERE id IN(" . $layout_ids . ")";
        $q            = $this->db->query($sql);
        $layout_info  = array();
        include_once(CUR_CONF_PATH . 'lib/layout.class.php');
        $this->layout = new layout();
        $layout_info  = array();
        while ($row          = $this->db->fetch_array($q))
        {
            $row                     = $this->layout->layout_namespace_and_header_process($row);       
            $layout_info[$row['id']] = $row;
        }
        $layout_cells = self::get_layout_cell($layout_ids);
        $layout_ids   = explode(',', $layout_ids);
        if (is_array($layout_ids) && count($layout_ids) > 0)
        {
            $layout_ids = array_reverse($layout_ids);
            $cells      = array();
            foreach ($layout_ids as $k => $v)
            {
                if ($template)
                {
                    $template = str_ireplace('</head>', '<style type="text/css">' . $layout_info[$v]['css'] . '</style></head>', $template);
                    $template = preg_replace('/<div[^>]*id="m2o-main-box"[^>]*>/', '\\0' . $layout_info[$v]['content'], $template);
                }
//				$layout_info[$v]['cells'] = $layout_cells[$v];
                $cells = array_merge($cells, $layout_cells['cell'][$v]);
            }
        }
        $data_source = $layout_cells['data_source'];
        return array('layout_cells' => $cells, 'template' => $template);
    }

    function get_layout_cell($layout_ids)
    {
        if (!$layout_ids)
        {
            return array();
        }
        $sql  = "SELECT * FROM " . DB_PREFIX . "layout_cell WHERE layout_id IN( " . $layout_ids . ")";
        $q    = $this->db->query($sql);
        $cell = array();
        while ($row  = $this->db->fetch_array($q))
        {
            //专题栏目链接处理
            if (strpos($row['more_href'], 'COLURL') !== false) {
                $intColumnId = intval(str_replace('COLURL', '', $row['more_href']));
                if (!class_exists('special')) {
                    include(ROOT_PATH . 'lib/class/special.class.php');
                }
                $objSpecial = new special(); 
                $row['more_href'] = $objSpecial->get_special_col_url($intColumnId); 
            }              
            $row['param_asso'] = unserialize($row['param_asso']);
            $mode_info         = self::get_mode_info($row['cell_mode'], $row['id'], $row['css_id'], $row['js_id'], $row['param_asso']);
            if ($row['data_source'])
            {
                $data_source_ids[$row['id']] = $row['data_source'];
                $data_source                 = self::get_datasource_info($row['data_source'], $row['param_asso']);
                $map                         = self::get_cell_map($mode_info, $data_source, $row['param_asso']);
            }
            else
            {
                $map = self::get_mode_map($mode_info);
            }
            $cache_file                                                                         = $row['id'] . '.php';
            include_once(CUR_CONF_PATH . 'lib/parse.class.php');
            $parse                                                                              = new Parse();
            $content                                                                            = $mode_info['mode_info']['content'];
            $parse->parse_template($content, $row['id'], $mode_info['mode_info'], $map['relation_map'], $map['mode_variable_map'], $map['variable_function_relation']);
            $parse->built_mode_cache($cache_file);
            $cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]                      = array_merge($row, $mode_info);
            $cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]['mode_detail']       = $cell[$row['layout_id']][$row['id']]['mode_info'];
            unset($cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]['mode_info']);
            $cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]['data_source_info']  = $data_source['info'];
            $cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]['input_param']       = $data_source['input_param'];
            $cell[$row['layout_id']][$row['id'] . '_' . $row['cell_name']]['input_param_other'] = $map['data_input_variable'];
        }
        return array('cell' => $cell, 'data_source' => $data_source_ids);
    }
    //获取默认数据的分类
    public function get_default_data_cate()
    {
    	$query = "select * from ".DB_PREFIX."data_cate";
    	$q = $this->db->query($query);
    	$info = array();
    	while(($row = $this->db->fetch_array($q))!=false)
    	{
    		$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
    		$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
    		$info[$row['id']] = $row;
    	}
    	return $info;
    }
    //获取默认数据某分类下的数据
    public function get_default_data_cate_datas($cate_id,$count)
    {
    	$cate_id = intval($cate_id)?intval($cate_id):false;
    	if(!$cate_id)
    		return false;
    	$count = intval($count)?intval($count):10;
    	$query = "select * from ".DB_PREFIX."data_cate_datas where cate_id=$cate_id limit 0,$count";
    	$q = $this->db->query($query);
    	$info = array();
    	while(($row = $this->db->fetch_array($q))!=false)
    	{
    		$row['create_time'] = date("Y-m-d H:i",$row['create_time']);
    		$row['update_time'] = date("Y-m-d H:i",$row['update_time']);
    		$info[$row['id']] = $row;
    	}
    	return $info;
    }
    
    
    public function getBlockData($intBlockId) {
        if (!$intBlockId) {
            return array();
        }
        if (!class_exists('block')) {
            include (ROOT_PATH  . 'lib/class/block.class.php');
        }
        $objBlock = new block();
        $response = $objBlock->getBlockData($intBlockId);
        return $response;
    }
    

}

?>
