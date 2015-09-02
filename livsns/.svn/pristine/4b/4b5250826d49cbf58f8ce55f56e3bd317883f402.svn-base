<?php

require_once('global.php');
require_once(ROOT_PATH . 'frm/node_frm.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
define('MOD_UNIQUEID', 'deploy');
define('SCRIPT_NAME', 'deploy_node');

class deploy_node extends nodeFrm
{

    public function __construct()
    {
        parent::__construct();
        $this->pub_config = new publishconfig();
        include_once(CUR_CONF_PATH . 'lib/common.php');
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {

        $fid = $this->input['fid'];
        /*模板 权限验证预处理 start*/
        $need_auth = 0;
        //$auth_page_self存储授权页面本身、$auth_page_parents存储授权栏目父级页面
        $auth_site = $auth_site_self = $auth_page = $auth_column = $auth_page_self = $auth_page_parents = array();
        if ( $this->user['group_type'] > MAX_ADMIN_TYPE )
        {
            $need_auth = 1;
            $auth_node = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            if ( (is_array($auth_node) ? implode(',',$auth_node) : $auth_node) == 1)
            {
                $need_auth = 0;  //1表示全选  不需要验证权限
            }
            $auth_node = is_array($auth_node) ? $auth_node : explode(',', $auth_node);
//            print_r($auth_node);exit;
            if ($need_auth)
            {
                foreach ((array) $auth_node as $k => $v)
                {
                    switch ($v)
                    {
                        case strstr($v, "site") !== false :
                            $v = str_replace("site", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $auth_site_self[] = $v[0];
                            break;
                        case strstr($v, "page_id") !== false :
                            $v = str_replace("page_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_self[] = $v[1];
                            break;
                        case strstr($v, "page_data_id") !== false:
                            $v = str_replace("page_data_id", "", $v);
                            $v = explode($this->settings['separator'], $v);
                            $auth_site[] = $v[0];
                            $auth_page[] = $auth_page_parents[] = $v[1];
                            $auth_column[$v[1]][] = $v[2];
                            break;
                        default:
                            break;
                    }
                }
            }

        }
        /*模板 权限验证预处理 end*/

        if (empty($fid))     //获取站点
        {
            if ($need_auth)
            {
                $auth_site_str = implode(',', array_unique($auth_site));
                if ($auth_site_str)
                {
                    $sites = $this->pub_config->get_site('id, site_name', '', '', $auth_site_str);
                }
                else
                {
                    $sites = array();
                }
            }
            else
            {
                $sites = $this->pub_config->get_site('id, site_name');
            }
            foreach ($sites as $k => $v)
            {
                $m         = array('id' => 'site' . $v['id'] . $this->settings['separator'] . $v['site_name'], "name" => $v['site_name'], "fid" => 0, "depth" => 1);
                if ($this->input['need_normal_id'])
                {
                    $m['id'] = $v['id'];
                }
                //获取页面类型
                $page_type = common::get_page_manage($v['id']);
                if (empty($page_type))
                {
                    $m['is_last'] = 1;
                }
                else
                {
                    $m['is_last'] = 0;
                }
                $this->addItem($m);
            }
        }
        else
        {
            //获取页面
            if (strstr($fid, "site") !== false)
            {
                //点击的站点
                $site_id   = str_replace('site', '', $fid);
                $get_page  = explode($this->settings['separator'], $site_id);
                $page_type = common::get_page_manage($get_page[0]);

                foreach ($page_type as $k => $v)
                {
                    $v['is_auth'] = 0;
                    if ($need_auth)
                    {
                        $v['is_auth'] = $need_auth ? 0 : 1;
                        //授权页面本身 显示
                        if (in_array($v['id'], $auth_page_self))
                        {
                            $v['is_auth'] = 1;
                        }
                        if (!$v['is_auth'])
                        {
                            //授权栏目父级页面 显示
                            if (in_array($v['id'], array_diff($auth_page_parents, $auth_page_self)))
                            {
                                $v['is_auth'] = 2;
                            }

                            //授权站点子集页面 显示
                            if (in_array($v['site_id'], $auth_site_self))
                            {
                                $v['is_auth'] = 3;
                            }
                        }
                    }

                    if ($v['is_auth'] || !$need_auth)
                    {
                        if ($v['sign'] == 'special')
                        {
                            continue;
                        }
                        $m         = array('id' => 'page_id' . $v['id'] . $this->settings['separator'] . $v['title'], "name" => $v['title'], "fid" => 'page_id' . $v['id'], "depth" => 1);
                        $page_data = common::get_page_data($v['id'], 0, 1);
                        if (empty($page_data['page_data']))
                        {
                            $m['is_last'] = 1;
                        }
                        else
                        {
                            $m['is_last'] = 0;
                        }
                        $m['is_root'] = 1;
                        $m['is_auth'] = $v['is_auth'];
                        $this->addItem($m);
                    }
                }
            }
            //获取页面下栏目
            else if ( ( ( $has_page_id = strstr($fid, "page_id") ) !== false) || ( ($has_page_data_id = strstr($fid, "page_data_id") ) !== false) )
            {
                //点击的页面类型
                $id = str_replace(array('page_id', 'page_data_id'), array('', ''), $fid);
                $get_page = explode($this->settings['separator'], $id);
                $page_data = common::get_page_data($get_page[0], 0, 1000, intval($get_page[1]), 0);
                if ($auth_column)
                {
                    //过滤部署与本页面的栏目ID
                    $auth_column = isset($auth_column[$get_page[0]]) ? $auth_column[$get_page[0]] : array() ;
                }
                foreach ((array)$page_data['page_data'] as $k => $v)
                {
//                    if ( $v[$page_data['page_info']['father_field']] != intval($get_page[1]) )
//                    {
//                        //重新过滤一遍栏目 发布库 page_data_id存在时不接受fid字段
//                        unset($page_data['page_data'][$k]);
//                        continue;
//                    }
                    if (in_array($v['id'], (array)$auth_column))
                    {
                        $auth_column_parents[$v['id']] = explode(',', $v['parents']);
                    }
                }
                foreach ((array)$page_data['page_data'] as $k => $v)
                {
                    $v['is_auth'] = 0;
                    if ($need_auth)
                    {
                        $v['is_auth'] = $need_auth ? 0 : 1;
                        //授权节点自身
                        if (in_array($v['id'], (array)$auth_column))
                        {
                            $v['is_auth'] = 1;
                        }
                        if (!$v['is_auth'])
                        {
                            //父级节点  显示
                            foreach ((array)$auth_column_parents as $column_id => $column_parent)
                            {
                                if (in_array($v['id'], $column_parent))
                                {
                                    $v['is_auth'] = 2;
                                    break;
                                }
                            }
                            //栏目孩子节点显示
                            if(array_intersect(explode(',', $v['parents']), (array)$auth_column))
                            {
                                $v['is_auth'] = 3;
                            }

                            //页面孩子节点显示
                            if (!$v['is_auth'] && in_array($get_page[0], $auth_page_self))
                            {
                                $v['is_auth'] = 3;
                            }

                            //站点孩子节点 显示
                            if (!$v['is_auth'] && in_array($v['site_id'], $auth_site_self))
                            {
                                $v['is_auth'] = 3;
                            }
                        }
                    }
                    if ($v['is_auth'] || !$need_auth)
                    {
                        $id_field = $page_data['page_info']['field'] ? $page_data['page_info']['field'] : 'id';
                        $name_field = $page_data['page_info']['name_field'] ? $page_data['page_info']['name_field'] : 'name';
                        $m_id         = 'page_data_id' . $page_data['page_info']['id'] . $this->settings['separator'] . $v[$id_field] . $this->settings['separator'] . $v[$name_field];
                        $m            = array('id' => $m_id, "name" => $v[$name_field], "fid" => 'page_data_id' . $page_data['page_info']['id'], "depth" => 1);
                        $m['is_last'] = $v['is_last'];
                        $m['is_auth'] = $v['is_auth'];
                        $this->addItem($m);
                    }
                }
            }
        }
        $this->output();
    }

}

include(ROOT_PATH . 'excute.php');
?>
