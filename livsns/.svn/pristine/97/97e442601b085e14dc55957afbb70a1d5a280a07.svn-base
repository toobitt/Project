<?php

require('global.php');
define('MOD_UNIQUEID', 'mkpublish'); //模块标识

class mkpublish_updateApi extends adminBase
{

    private $sqlarr             = array();
    private $publish_user       = '';
    private $publish_time       = '';
    private $content_mk_num;
    private $content_param      = array();
    private $content_plan_title = '内容页';

    public function __construct()
    {
        $this->mPrmsMethods = array(
            'show' => '查看内容',
            'manage' => '管理[撤回/排序]',
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj          = new mkpublish();
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys      = new publishsys();
        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->pub_content  = new publishcontent();
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->pub_config   = new publishconfig();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function set_sqlarr($site_id, $page_id, $page_data_id, $content_type, $client_type, $title = '')
    {
        if (!$this->checksqlarr[$site_id][$page_id][$page_data_id][$client_type][$content_type])
        {
            $this->sqlarr[]                                                                    = array(
                'title' => $title ? $title : $this->content_plan_title,
                'site_id' => $site_id,
                'page_id' => $page_id,
                'page_data_id' => $page_data_id,
                'content_type' => $content_type,
                'client_type' => $client_type,
                'publish_time' => $this->publish_time,
                'publish_user' => $this->publish_user,
                'content_param' => serialize($this->content_param),
                'count' => $this->content_mk_num,
                'mk_count' => $this->mk_count,
                'max_page' => $this->max_page,
                'm_type' => $this->m_type,
                'rid' => 0,
            );
            $this->checksqlarr[$site_id][$page_id][$page_data_id][$client_type][$content_type] = 1;
        }
    }

    public function set_publish_user($publish_user)
    {
        $this->publish_user = $publish_user;
    }

    public function set_publish_time($publish_time)
    {
        $this->publish_time = $publish_time;
    }

    public function set_content_mk_num($content_mk_num)
    {
        $this->content_mk_num = $content_mk_num;
    }

    public function set_content_param($content_param)
    {
        $this->content_param = $content_param;
    }

    //进入到生成发布队列
    public function create()
    {
        $m_type           = intval($this->input['m_type']);
        $this->m_type     = $m_type;
        $client_type      = isset($this->input['client_type']) ? $this->input['client_type'] : 2;
        $content_mk_num   = ($this->input['content_mk_num']) ? intval($this->input['content_mk_num']) : 50;//每次取的条数
        $page_number      = ($this->input['page_number']) ? intval($this->input['page_number']) : 0;//最多生成多少条数
        $this->mk_count   = $page_number;
        $content_mk_num   = $page_number < $content_mk_num ? $page_number : $content_mk_num;
        //$min_weight   = ($this->input['min_weight']);
        //$max_weight   = ($this->input['max_weight']);
        $min_publish_time = ($this->input['min_publish_time']);
        $max_publish_time = ($this->input['max_publish_time']);
        $content_typearr  = ($this->input['content_typearr']);
        $is_contain_child = intval($this->input['is_contain_child']); //0不支持 1支持
        $this->max_page   = intval($this->input['max_page']);
        if ($this->input['site_id'])
        {
            $site_id = intval(str_replace('site', '', $this->input['site_id']));
        }
        switch ($m_type)
        {
            case 1:
                $content_typestr = 0;
                $site_id         = intval(str_replace('site', '', $this->input['siteid']));
                if (!$site_id)
                {
                    $this->errorOutput('NO_SITE_ID');
                }
                break; //生成首页
            case 2:
                $site_id = intval(str_replace('site', '', $this->input['siteid']));
                if (!$site_id)
                {
                    $this->errorOutput('NO_SITE_ID');
                }
                include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
                $this->pub_config = new publishconfig();
                $site             = $this->pub_config->get_site_first('*', $site_id);
                if (!$site)
                {
                    $this->errorOutput('NO_SITE_DETAIL');
                }
                $md              = get_site_temdir($site);
                $this->mk_material($site_id, $md);
                $this->mk_mode($site_id, $md);
                $this->mk_frame($site_id, $md, rtrim($site['site_dir'], '/') . '/');
                exit; //生成素材，基础框架
            case 3:
                $content_typestr = 0;
                $this->max_page  = $this->max_page ? $this->max_page : 20;
                //这个地方需要查出这个栏目首页类型是index还是list，来决定content_type的值
                ;
                break; //生成栏目页，
            case 4:
                $content_typestr = -2;
                ;
                break; //生成内容页
            case 5:
                $site_id         = intval(str_replace('site', '', $this->input['siteid']));
                if (!$site_id)
                {
                    $this->errorOutput('NO_SITE_ID');
                }
                deleteDir(ROOT_PATH . 'api/publishsys/cache/mkpublish/' . $site_id);
                echo json_encode('1');
                exit;
            case 6:
                //生成内容类型,获取内容类型
                $is_contain_child = 0;
                $this->max_page   = 0;
                if (!$content_typearr)
                {
                    $all_content_type = $this->pub_content->get_all_content_type();
                    $content_typearr  = array_keys($all_content_type);
                }

                if (!$content_typearr)
                {
                    $this->errorOutput('NO_CONTENT_TYPE');
                }
                $content_typestr = implode(',', $content_typearr);

                if ($min_publish_time)
                {
                    $this->content_param['starttime'] = $min_publish_time;
                }
                if ($max_publish_time)
                {
                    $this->content_param['endtime'] = $max_publish_time;
                }
                break;
        }

        if ($fid = ($this->input['fid']))
        {
            $fidarr = explode(',', $fid);
            foreach ($fidarr as $k => $v)
            {
                if (strstr($v, "page_id") !== false)
                {
                    $page_idstr = str_replace('page_id', '', $v);
                    $get_page   = explode($this->settings['separator'], $page_idstr);
                }
                else if (strstr($v, "page_data_id") !== false)
                {
                    $page_data_id = str_replace('page_data_id', '', $v);
                    $get_page     = explode($this->settings['separator'], $page_data_id);
                }
                $page_id      = $get_page[0];
                $page_data_id = $get_page[1];

                $mk_arr[$k]['site_id']      = $site_id;
                $mk_arr[$k]['page_id']      = $page_id;
                $mk_arr[$k]['page_data_id'] = intval($page_data_id);
                $mk_arr[$k]['content_type'] = $content_typestr;
                $mk_arr[$k]['client_type']  = $client_type;
            }
        }
        else
        {
            $mk_arr[0]['site_id']      = $site_id;
            $mk_arr[0]['page_id']      = 0;
            $mk_arr[0]['page_data_id'] = 0;
            $mk_arr[0]['content_type'] = $content_typestr;
            $mk_arr[0]['client_type']  = $client_type;
        }
        foreach ($mk_arr as $kk => $vv)
        {
            $this->set_content_mk_num($content_mk_num);
            $this->set_publish_user(trim($this->user['user_name']));
            $this->set_publish_time(TIMENOW);

            //内容参数
            $content_param   = array();
            $site_id         = $vv['site_id'];
            $page_id         = $vv['page_id'];
            $page_data_id    = $vv['page_data_id'];
//			$content_type[] = $vv['content_type'];
            $content_type    = explode(',', $vv['content_type']);
            $client_type     = $vv['client_type'];
            $page_data_idarr = explode(',', $page_data_id);
            if ($site_id && !$page_id)
            {
                if ($is_contain_child)
                {
                    /*                     * 发布子级 */
                    //查出站点下的页面类型
                    $page_type = $this->pub_sys->get_page_manage($site_id);
                    if (is_array($page_type) && $page_type)
                    {
                        $this->mk_plan_page_type($content_type, $page_type, $is_contain_child, $client_type);
                    }
                }
                else
                {
                    /*                     * 不发布子级 */
                    foreach ($content_type as $k => $v)
                    {
                        $this->set_sqlarr($site_id, 0, 0, $v, $client_type, '站点首页');
                    }
                }
            }
            if ($page_id && (count($page_data_idarr) == 1 && !$page_data_idarr[0]))
            {
                //判断站点有没有，如有，是不是站点包含子级生成发布过了
//				if($site_id && $is_contain_child)
//				{
//					break;
//				}
                $page_type = $this->pub_sys->get_page_by_id($page_id, 1);
                if (is_array($page_type) && $page_type)
                {
                    $this->mk_plan_page_type($content_type, $page_type, $is_contain_child, $client_type);
                }
            }
            if ($page_data_idarr && (count($page_data_idarr) != 1 || $page_data_idarr[0]))
            {
                //判断站点有没有，如有，是不是站点包含子级生成发布过了
//				if($site_id && $is_contain_child)
//				{
//					break;
//				}
                $page_type = $this->pub_sys->get_page_by_id($page_id, 1, 'id');
                if ($page_type && is_array($page_type))
                {
                    foreach ($page_data_idarr as $k => $v)
                    {
                        //					$page_id;#!!!!!
                        $page_data_id = $v;
                        if ($this->m_type != 6)
                        {
                            $page_data        = $this->pub_sys->get_page_data($page_id, '', '', '', $page_type[$page_id], $page_data_id);
                            $page_data_detail = $page_data['page_data'][0];
                            $content_type     = array();
                            if (is_array($page_data_detail))
                            {
                                if ($page_data_detail['column_file'] == 'list')
                                {
                                    $content_type[] = -1;
                                }
                                else
                                {
                                    $content_type[] = 0;
                                }
                            }
                            else
                            {
                                $content_type[] = 0;
                            }
                        }

                        foreach ($content_type as $kk => $vv)
                        {
                            $this->set_sqlarr($page_type[$page_id]['site_id'], $page_id, $page_data_id, $vv, $client_type, $page_data_detail['name']);
                        }
                        if ($is_contain_child)
                        {
                            $this->mk_plan_page_data($content_type, $page_type[$page_id], $page_data_id, $is_contain_child, $client_type);
                        }
                    }
                }
            }
        }
        //入库
        if ($this->sqlarr)
        {
            $this->obj->insert_plan($this->sqlarr);
        }
    }

    public function mk_plan_page_type($content_type, $page_type, $is_contain_child, $client_type)
    {
        foreach ($page_type as $kk => $vv)
        {
            foreach ($content_type as $k => $v)
            {
                if (!$vv['has_content'])
                {
                    if (!in_array($v, $this->settings['content_type_true']))
                        continue;
                }
                $this->set_sqlarr($vv['site_id'], $vv['id'], 0, $v, $client_type, $vv['title']);
            }
            if ($is_contain_child)
            {
                //查出页面数据
                $this->mk_plan_page_data($content_type, $vv, 0, $is_contain_child, $client_type);
            }
        }
    }

    public function mk_plan_page_data($content_type, $page_info, $fid, $is_contain_child, $client_type)
    {
        $page_data = $this->pub_sys->get_page_data($page_info['id'], 0, 1000, $fid, $page_info);
        if (is_array($page_data['page_data']) && $page_data['page_data'])
        {
            foreach ($page_data['page_data'] as $kkk => $vvv)
            {
                if ($this->m_type != 6)
                {
                    if (is_array($vvv))
                    {
                        $content_type = array();
                        if ($vvv['column_file'] == 'list')
                        {
                            $content_type[] = -1;
                        }
                        else
                        {
                            $content_type[] = 0;
                        }
                    }
                    else
                    {
                        $content_type[] = 0;
                    }
                }

                foreach ($content_type as $k => $v)
                {
                    $this->set_sqlarr($page_info['site_id'], $page_info['id'], $vvv['id'], $v, $client_type, $vvv['name']);
                    if (!$vvv['is_last'] && $is_contain_child)
                    {
                        $this->mk_plan_page_data($content_type, $page_info, $vvv['id'], $is_contain_child, $client_type);
                    }
                }
            }
        }
    }

    //生成素材
    public function mk_material($site_id, $md)
    {
        //将模板素材拷贝
        if (is_dir('../../publishsys/data/template/' . $site_id))
        {
            file_copy('../../publishsys/data/template/' . $site_id, $md . $this->settings['template_name'] . '/' . $site_id,array(),array('zip'));
        }
        if (is_dir('../../publishsys/data/template/' . 0))
        {
            file_copy('../../publishsys/data/template/' . 0, $md . $this->settings['template_name'] . '/' . 0, array(), array('zip'));
        }
        if (is_dir('../../publishsys/data/icon/'))
        {
            file_copy('../../publishsys/data/icon/', $md . $this->settings['template_name'] . '/icon/');
        }
    }

    //生成样式素材
    public function mk_mode($site_id = '', $md)
    {
        if (is_dir('../../publishsys/data/mode/' . $site_id))
        {
            file_copy('../../publishsys/data/mode/' . $site_id, $md . $this->settings['mode_name'] . '/' . $site_id, array());
        }
    }

    //生成框架
    public function mk_frame($site_id, $md, $site_dir)
    {
        $m2o_dir         = $site_dir . $this->settings['frame_filename'];
        hg_mkdir($m2o_dir);
        //将php框架拷贝
        file_copy('../../publishsys/lib/m2o', $m2o_dir, array('var.php'));
        $globalconfigstr = '';
        foreach ($this->settings as $k => $v)
        {
            if (strstr($k, "App") !== false)
            {
                $globalconfigstr .= '$gGlobalConfig[\'' . $k . '\']=' . var_export($this->settings[$k], true) . ';' . "\n";
            }
        }
        $config_str = @file_get_contents('../../publishsys/lib/m2o/conf/config.php');
        $config_str = str_replace('?>', '', $config_str);
        $config_str .= $globalconfigstr;
        @file_put_contents($m2o_dir . '/conf/config.php', $config_str . "\n" . ' ?>');

        //拷贝到各个二级域名栏目下
        $condition    = ' AND site_id=' . $site_id . ' AND childdomain!=\'\'';
        $column_datas = $this->pub_config->get_column(' * ', $condition);
        foreach ($column_datas as $k => $v)
        {
            if (!$v['childdomain'] || !$v['column_dir'])
            {
                continue;
            }
            $m2o_columndir = $site_dir . trim($v['column_dir'], '/') . '/' . $this->settings['frame_filename'];
            file_copy($m2o_dir, $m2o_columndir, array());
        }
    }

    public function mk_content_by_con()
    {
        $site_id                      = $this->input['site_id'] ? $this->input['site_id'] : 1;
        $client_type                  = $this->input['client_type'] ? $this->input['client_type'] : 2;
        $column_id                    = $this->input['column_id'];
        $bundle_id                    = $this->input['bundle_id'];
        $start_time                   = $this->input['start_time'];
        $end_time                     = $this->input['end_time'];
        $count                        = $this->input['count'] ? $this->input['count'] : 5000;
        $content_param['bundle_id']   = $bundle_id;
        $content_param['site_id']     = $site_id;
        $content_param['column_id']   = $column_id;
        $content_param['client_type'] = $client_type;
        $content_param['offset']      = 0;
        $content_param['count']       = $count;
        if ($start_time)
        {
            $content_param['starttime'] = $start_time;
        }
        if ($end_time)
        {
            $content_param['endtime'] = $end_time;
        }

        include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
        $this->pub_content = new publishcontent();
        $content_datas     = $this->pub_content->get_content($content_param);
        if (!$content_datas && !is_array($content_datas))
        {
            echo "没有内容可发布";
            return;
        }
        //内容插入到正在发布库
        $this->pub_content = new publishcontent();
        foreach ($content_datas as $k => $v)
        {
            if ($v['template_sign'])
            {
                continue;
            }
            $content_type_detail               = $this->pub_content->get_content_type_by_app($v['bundle_id'], $v['module_id']);
            $new_plan[$k]                      = array();
            $new_plan[$k]['site_id']           = $site_id;
            $new_plan[$k]['client_type']       = $client_type;
            $new_plan[$k]['page_id']           = 8;
            $new_plan[$k]['page_data_id']      = $v['column_id'];
            $new_plan[$k]['rid']               = $v['id'];
            $new_plan[$k]['publish_time']      = TIMENOW;
            $new_plan[$k]['title']             = $v['title'];
            $new_plan[$k]['template_sign']     = $v['template_sign'];
            $new_plan[$k]['content_type_sign'] = $v['bundle_id'];
            $new_plan[$k]['content_type']      = $content_type_detail['id'];
            $new_plan[$k]['m_type']            = 6;
        }
        if ($new_plan && is_array($new_plan))
        {
            $this->obj->insert_plan_batch('mking', $new_plan);
        }
    }

}

$out    = new mkpublish_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'create';
}
$out->$action();
?>