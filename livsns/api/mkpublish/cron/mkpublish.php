<?php

define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
define('CUR_M2O_PATH', '../lib/m2o/');
require_once(ROOT_PATH . 'global.php');
define('MOD_UNIQUEID', 'mkpublish'); //模块标识
require(CUR_CONF_PATH . 'lib/functions.php');

class mkpublishApi extends cronBase
{

    /**
     * 构造函数
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     * @include news.class.php
     */
    public function __construct()
    {
        parent::__construct();
        include_once(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj     = new mkpublish();
        include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
        $this->pub_sys = new publishsys();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function initcron()
    {
        $array = array(
            'mod_uniqueid' => MOD_UNIQUEID,
            'name' => '生成发布页面',
            'brief' => '生成发布页面',
            'space' => '1', //运行时间间隔，单位秒
            'is_use' => 1, //默认是否启用
        );
        $this->addItem($array);
        $this->output();
    }

    public function check_plan()
    {
        $time_start = microtime_float();
        $max        = 1;
        $con        = ' AND publish_time<=' . TIMENOW . ' ORDER BY publish_time LIMIT ' . $max;
        $plan       = $this->obj->get_plan_by_con($con);
        if (!$plan)
        {
            echo "NO_PLAN";
            exit;
        }
        foreach ($plan as $v)
        {
            $this->show($v);
        }
        $time_end = microtime_float();
        $alltime  = $time_end - $time_start;
        if ($alltime >= 10)
        {
            //file_put_contents(CUR_CONF_PATH . 'cache/10.txt', var_export($plan, true) . $alltime . "\n\n", FILE_APPEND);
        }
    }

    public function show($plan = array())
    {
        if ($rid = intval($this->input['rid']))
        {
            if ($this->input['rid'])
            {
                //file_put_contents('../cache/1.txt', var_export($this->input, 1));
            }
            /*             * 从发布库来生成内容 */
            $plan['rid']               = $rid;
            $plan['title']             = $this->input['title'];
            $plan['site_id']           = intval($this->input['site_id']);
            $plan['page_id']           = intval($this->input['page_id']);
            $plan['page_data_id']      = intval($this->input['page_data_id']);
            $plan['content_type']      = intval($this->input['content_type']);
            $plan['client_type']       = intval($this->input['client_type']);
            $plan['content_type_sign'] = ($this->input['content_type_sign']);
            $plan['template_sign']     = ($this->input['template_sign']);
            $plan['page_id_c']         = intval($this->input['page_id_c']);
            $plan['page_data_id_c']    = intval($this->input['page_data_id_c']);
            $plan['publish_time']      = TIMENOW;
            $plan['content_detail']          = $this->input['content_detail'];
        }
        else
        {
            if (!$plan)
            {

                $plan = $this->obj->get_plan_first();
                if (!$plan)
                {
                    echo "没有需要生成的队列";
                    return;
                }
                $this->obj->delete('mking', ' id=' . $plan['id']);
            }
            unset($plan['id']);

            //如果是正文,并且rid没有值，取列表插库
            if (!in_array($plan['content_type'], $this->settings['content_type_true']) && !$plan['rid'] && $plan['m_type'] == 6)
            {
                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->pub_content = new publishcontent();
                $all_content_type  = $this->pub_content->get_all_content_type();
                if (!$all_content_type || !$all_content_type[$plan['content_type']])
                {
                    echo "没有到发布库获取到内容类型";
                    return;
                }
                $content_param['bundle_id']   = $all_content_type[$plan['content_type']]['bundle_id'];
                //$content_param['module_id']   = $all_content_type[$plan['content_type']]['module_id'];
                $content_param['site_id']     = $plan['site_id'];
                $content_param['column_id']   = $plan['page_data_id'];
                $content_param['client_type'] = $plan['client_type'];
                $content_param['offset']      = $plan['offset'];
                $content_param['count']       = $plan['count'];
                $content_param                = $content_param + $plan['content_param']+array('from_mkpublish'=>1);
                $content_datas                = $this->pub_content->get_content($content_param);
                if (!$content_datas || !is_array($content_datas))
                {
                    echo "没有内容可发布";
                    return;
                }
                if(!$plan['page_id'])
                {
                    $page_data     = $this->pub_sys->get_page_by_sign('column', $plan['site_id']);
                    if(!$page_data['id'])
                    {
                        $plan['failure'] = '请求模板页面类型数据失败：'.  var_export($page_data,true);
                        $this->insert_mklog($plan);
                        $this->errorOutput(var_export($page_data, true));
                    }
                    $plan['page_id'] = $page_data['id'];
                }
                
                //内容插入到正在发布库
                foreach ($content_datas as $k => $v)
                {
                    $new_plan[$k]                      = array();
                    $new_plan[$k]                      = $plan;
                    $new_plan[$k]['page_data_id']      = $v['column_id'];
                    $new_plan[$k]['rid']               = $v['id'];
                    $new_plan[$k]['publish_time']      = TIMENOW;
                    $new_plan[$k]['title']             = $v['title'];
                    $new_plan[$k]['template_sign']     = $v['template_sign'];
                    $new_plan[$k]['content_param']     = serialize($plan['content_param']);
                    $new_plan[$k]['offset']            = 0;
                    $new_plan[$k]['page_num']          = 1;
                    $new_plan[$k]['content_type_sign'] = $all_content_type[$plan['content_type']]['bundle_id'];
                    $new_plan[$k]['content_detail']    = serialize(array(
                        'content_url' => $v['content_url'],
                        'id' => $v['id'],
                        'rid' => $v['rid'],
                        'cid' => $v['cid'],
                        'content_fromid' => $v['content_fromid'],
                        'file_name' => $v['file_name'],
                        'template_sign' => $v['template_sign'],
                        'content_type_sign' => $v['content_type_sign'],
                        'column_id' => $v['column_id'],
                        'site_id' => $v['site_id'],
                    ));
                }
                if ($new_plan && is_array($new_plan))
                {
                    $this->obj->insert_plan_batch('mking', $new_plan);
                }

                //看有没有余下的内容
                $next_plan          = $plan;
                $plan_content_count = count($content_datas);
                $next_plan['offset'] += $plan_content_count;
                if($plan['mk_count'])
                {
                    //有设置生成条数，暂时有bug
                    if ($plan_content_count < $plan['count'] || $next_plan['offset'] >= $plan['mk_count'])
                    {
                        return;
                    }
                    $n_plannum = $plan['mk_count'] - $offset;
                    if ($n_plannum <= 0)
                    {
                        return;
                    }
                    $next_plan['count'] = $n_plannum >= $plan['count'] ? $plan['count'] : $n_plannum;
                }
                else
                {    
                    if ($plan_content_count < $plan['count'])
                    {
                        return;
                    }
                    $next_plan['count'] = $plan['count'];
                }
                
                $next_plan['publish_time'] = TIMENOW;
                $next_plan['content_param'] = serialize($plan['content_param']);
                $this->obj->insert('mking', $next_plan);
                return;
            }
            //运行剩下的内容页
        }
        if ($plan['site_id'] && $plan['page_id'] && !$plan['page_data_id'] && !$plan['rid'])
        {
            return;
        }
        
        $rets = $this->pub_sys->mk($plan);

        $log_arr = $plan;
        file_put_contents('../cache/2.txt', var_export($rets, 1));
        if ($rets['status'] == 'error')
        {
            $log_arr['failure'] = $rets['message'];
            $this->insert_mklog($log_arr);
            $this->errorOutput(var_export($rets, true));
        }
        if (!is_array($rets[0]))
        {
            $log_arr['failure'] = '访问模板生成文件接口出错，可能：访问模板域名,生成超时';
            $this->insert_mklog($log_arr);
            $this->errorOutput(var_export($rets, true));
        }
        $ret = $rets[0];
        if ($ret['status'] != 'ok')
        {
            $log_arr['failure'] = '模板生成接口返回状态status不ok';
            $this->insert_mklog($log_arr);
            $this->errorOutput(var_export($rets, true));
        }
        //将站点，客户端信息写入道前端m2o/conf/var.php
        $var_data_tag = false;
        $varstr       = '<?php ';
        if (!empty($ret['site']) && is_array($ret['site']))
        {
            $var_data_tag = true;
            $varstr .= '$gGlobalConfig[\'v_site\'] = ' . var_export($ret['site'], true) . ';' . "\n";
        }
        if (!empty($ret['client']))
        {
            $var_data_tag = true;
            $varstr .= '$gGlobalConfig[\'v_client\'] = ' . var_export($ret['client'], true) . ';' . "\n";
        }
        $varstr .= ' ?>';
        if ($var_data_tag)
        {
            $sitedir = $ret['site']['site_dir'];
            if ($sitedir)
            {
                @file_put_contents(rtrim($sitedir, '/') . '/m2o/conf/var.php', $varstr);
            }
        }

        //将列表，内容页面的css文件写入道css文件中生成引入
        if ($ret['mar_css']['mar_css_dir'])
        {
            $ret['mar_css']['css'] = str_replace(array('<style type="text/css">', '</style>'), '', $ret['mar_css']['css']);
            file_in($ret['mar_css']['mar_css_dir'], $ret['mar_css']['mar_css_filename'], $ret['mar_css']['css'], true, true);
        }

        //js单元
        if ($ret['js_cell'] && is_array($ret['js_cell']))
        {
            foreach ($ret['js_cell'] as $k => $v)
            {
                $js_html = '';
                $js_html .= '<?php ' . $v['head'] . "\n" . $v['ds'] . "\n" . ' ?>' . $v['cell'];
                file_in($v['include_dir'], $v['include_filename'], $js_html, true, true);
                if ($v['dir'] && $v['filename'])
                {
                    $callback_html = '<?php  
                        if(!file_exists(\'' . $v['include_dir'] . $v['include_filename'] . '\'))
                        {
                            $callback_html = \'\';
                        }
                        else
                        {
                            ob_clean();
                            ob_start();
                            include \'' . $v['include_dir'] . $v['include_filename'] . '\';
                            $callback_html = ob_get_contents();
                            ob_clean();
                        }
                        ?>';
                    $callback_html .= 'document.write(\'<?php echo str_replace(array("\n","\'"), array("","\\\'"), $callback_html) ?>\')';
                    file_in($v['dir'], $v['filename'], $callback_html, true, true);
                }
            }
        }

        //生成的余下队列
        if ($ret['new_plan'] && is_array($ret['new_plan']))
        {
            $after_plan = array(
                'title' => $plan['title'],
                'rid' => $plan['rid'],
                'site_id' => $plan['site_id'],
                'page_id' => $plan['page_id'],
                'page_data_id' => $plan['page_data_id'],
                'content_type' => $plan['content_type'],
                'client_type' => $plan['client_type'],
                'content_type_sign' => $plan['content_type_sign'],
                'template_sign' => $plan['template_sign'],
                'offset' => intval($ret['new_plan']['offset']),
                'page_num' => intval($ret['new_plan']['page_num']),
                'max_page' => intval($plan['max_page']),
                'publish_time' => intval($plan['publish_time']),
                'content_detail' => serialize($plan['content_detail']),
            );
            $this->obj->insert('mking', $after_plan);
            if ($after_plan['page_num'] == 2 && $ret['need_show_all_pages'] && $ret['page_content_type'])
            {
                $all_pages_plan = array(
                    'title' => $plan['title'],
                    'rid' => $plan['rid'],
                    'site_id' => $plan['site_id'],
                    'page_id' => $plan['page_id'],
                    'page_data_id' => $plan['page_data_id'],
                    'content_type' => $plan['content_type'],
                    'client_type' => $plan['client_type'],
                    'content_type_sign' => $plan['content_type_sign'],
                    'template_sign' => $plan['template_sign'],
                    'offset' => intval($ret['new_plan']['offset']),
                    'page_num' => -1,
                    'max_page' => intval($plan['max_page']),
                    'publish_time' => intval($plan['publish_time']),
                    'content_detail' => serialize($plan['content_detail']),
                );
                $this->obj->insert('mking', $all_pages_plan);
            }
        }
        /**
         * $ret:new_plan,html,page_info,status
         * $page_info:file_mktype,weburl,dir,suffix
         * */
        //生成文件
        $dir = rtrim($ret['page_info']['dir'], '/');
        $ret['page_info']['filename'] = $ret['page_info']['filename']?$ret['page_info']['filename']:'index';
        
        if (!$ret['filename'] && !empty($plan['content_detail']['filename']))
        {
            $ret['filename'] = $plan['content_detail']['filename'];
        }
        if ($ret['filename'] && $ret['page_info']['file_mktype'] != 2)
        {
            //内容页静态生成
            if ($ret['page_info']['custom_content_dir'])
            {
                $dir = str_replace($ret['page_info']['relate_dir'], '', $dir);
                $dir = rtrim($dir, '/') . '/' . trim($ret['page_info']['custom_content_dir'], '/');
            }
            if ($ret['page_info']['file_mktype'] != 1 && $ret['filename'] == '.php')
            {
                $filename = $plan['content_type_sign'] . '.php';
            }
            else
            {
                $fnarr = explode('/', $ret['filename']);
                $fnarr_count = count($fnarr);
                for ($i = 0; $i < $fnarr_count; $i++)
                {
                    if ($i == ($fnarr_count - 1))
                    {
                        $filename = $fnarr[$i];
                    }
                    else
                    {
                        $dir .= '/' . $fnarr[$i];
                    }
                }
                if (!$filename)
                {
                    $log_arr['failure'] = '内容页filename:' . $ret['filename'] . ' 不正确';
                    $this->insert_mklog($log_arr);
                    $this->errorOutput("NO_FILENAME");
                }
            }
        }
        else if ($ret['page_info']['page_type'] == 'special')
        {
            if ($ret['page_info']['custom_content_dir'])
            {
                $dir = str_replace($ret['page_info']['relate_dir'], '', $dir);
                $dir = rtrim($dir, '/') . '/' . trim($ret['page_info']['custom_content_dir'], '/') . '/' . $ret['page_info']['special_dir'];
            }
            $filename = $ret['page_info']['filename'] . $ret['page_info']['suffix'];
        }
        else if ($plan['content_type_sign'] && $ret['page_info']['file_mktype'] == 2)
        {
            //有内容类型标识是内容正文生成，页面生成方式为动态
            $filename = $plan['content_type_sign'] . '.php';
        }
        //else if (!$ret['get_analysis_result']['is_contentdetail'] && !$plan['rid'])
        else if (!$plan['rid'])
        {
            //表示首页或者列表页生成
            $filename = $ret['page_info']['filename'] . $ret['page_info']['suffix'];
        }
        if (!$filename || $filename == '<')
        {
            if ($ret['get_analysis_result']['is_contentdetail'])
            {
                if (!$plan['rid'])
                {
                    $log_arr['failure'] = '首页或者列表页可能包含了正文样式';
                }
                else
                {
                    $log_arr['failure'] = '未获取到正文页内容详情的filename';
                }
            }
            else
            {
                if($plan['rid'])
                {
                    $log_arr['failure'] = '内容页未选择正文样式或者数据源配置出错';
                }
                else
                {
                    $log_arr['failure'] = '站点或者栏目未设置生成文件名名称:index;再或者未获取到发布库站点栏目信息';
                }
            }
            $this->insert_mklog($log_arr);
            $this->errorOutput("NO_FILENAME");
        }
        
        $ret['html'] = ltrim($ret['html']);
        $fr = file_in($dir, $filename, $ret['html'], true, true);
        
        if(!$fr)
        {
            $log_arr['failure'] = '目录或者文件不可写,请查看目录权限:'.$dir.'/'.$filename;
            $this->insert_mklog($log_arr);
            $this->errorOutput("NO_PATH_AUTH");
        }

        //进入完成库
        $complete_plan = $plan;
        $complete_plan['content_param'] = serialize($complete_plan['content_param']);
        if ($ret['page_info']['file_mktype'] != 1)
        {
            $complete_plan['rid'] = 0;
        }
        //if (!$complete_plan['publish_time'])
        {
            $complete_plan['publish_time'] = TIMENOW;
        }
        if (!$complete_plan['publish_user'])
        {
            $complete_plan['publish_user'] = $this->user['user_name'];
        }
        if (!$complete_plan['file_path'])
        {
            $complete_plan['file_path'] = $dir;
        }
        if (!$complete_plan['file_name'])
        {
            $complete_plan['file_name'] = $filename;
        }
        if ($ret['org_filename'])
        {
            $complete_plan['file_name'] = $ret['org_filename'];
        }
        unset($complete_plan['page_id_c']);
        unset($complete_plan['page_data_id_c']);
        unset($complete_plan['content_detail']);
        $this->obj->complete_plan($complete_plan);

        //删除同名文件下动静态
        preg_match('/(.*?)\.(\w+)$/i', $filename, $fmat);
        if ($fmat[0])
        {
            switch ($fmat[2])
            {
                case 'php':
                    @unlink($dir . '/' . $fmat[1] . '.html');
                    @unlink($dir . '/' . $fmat[1] . '.htm');
                    for($j=1;$j<=50;$j++)
                    {
                        @unlink($dir . '/' . $fmat[1] .'_'.$j. '.html');
                        @unlink($dir . '/' . $fmat[1] .'_'.$j. '.htm');
                    }
                    break;
                case 'html':
                    @unlink($dir . '/' . $fmat[1] . '.php');
                    @unlink($dir . '/' . $fmat[1] . '.htm');
                    break;
                case 'htm':
                    @unlink($dir . '/' . $fmat[1] . '.php');
                    @unlink($dir . '/' . $fmat[1] . '.html');
                    break;
            }
        }
        echo rtrim($dir, '/') . '/' . $filename;
    }

    public function del_publish()
    {
        $rid          = intval($this->input['rid']);
        $site_id      = intval($this->input['site_id']);
        $page_id      = intval($this->input['page_id']);
        $page_data_id = intval($this->input['page_data_id']);

        if (!$rid && !($site_id || $page_id || $page_data_id))
        {
            $this->errorOutput('NO_RID');
        }
        $infos = array();
        if ($rid)
        {
            $sql  = 'SELECT * FROM ' . DB_PREFIX . 'mking_complete WHERE rid=' . $rid;
            $row = $this->db->query_first($sql);
            if($row)
            {
                $infos[] = $row;
            }
        }
        else
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "mking_complete WHERE site_id=$site_id AND page_id=$page_id AND page_data_id=$page_data_id ";
            if(isset($this->input['content_type']))
            {
                $sql .= " AND content_type=".intval($this->input['content_type']);
            }
            if(isset($this->input['client_type']))
            {
                $sql .= " AND client_type=".intval($this->input['client_type']);
            }
            $infos = $this->db->fetch_all($sql);
        }
        if (empty($infos))
        {
            $this->errorOutput('NO_DELETE');
        }
        foreach($infos as $k=>$info)
        {
            $this->obj->unlink_file($info['file_path'], $info['file_name'], $info['page_num']);
            $idarr[] = $info['id'];
        }
        $this->obj->delete('mking_complete', ' id in (' . implode(',',$idarr).')');
    }

    public function insert_mklog($log_arr)
    {
        if ($log_arr['failure'])
        {
            $arr['title']             = $log_arr['title'];
            $arr['site_id']           = $log_arr['site_id'];
            $arr['page_id']           = $log_arr['page_id'];
            $arr['page_data_id']      = $log_arr['page_data_id'];
            $arr['content_type']      = $log_arr['content_type'];
            $arr['content_type_sign'] = $log_arr['content_type_sign'];
            $arr['rid']               = $log_arr['rid'];
            $arr['client_type']       = $log_arr['client_type'];
            $arr['file_path']         = $log_arr['file_path'];
            $arr['file_name']         = $log_arr['file_name'];
            $arr['template_sign']     = $log_arr['template_sign'];
            $arr['offset']            = intval($log_arr['offset']);
            $arr['count']             = intval($log_arr['count']);
            $arr['max_page']          = intval($log_arr['max_page']);
            $arr['page_num']          = intval($log_arr['page_num']);
            $arr['content_param']     = $log_arr['content_param'];
            $arr['m_type']            = $log_arr['m_type'];
            $arr['publish_user']      = $log_arr['publish_user'];
            $arr['publish_time']      = $log_arr['publish_time'];
            $arr['failure']           = $log_arr['failure'];
            $arr['status']            = 0;
            $arr['create_time']       = time();
            $this->obj->insert('mklog', $arr);
        }
    }
    
    //发布库插入内容生成错误日志
    public function insert_error_log()
    {
        $plan = $this->input['plan'];
        if($plan['title'])
        {
            $this->insert_mklog($plan);
        }
        
    }

}

$out    = new mkpublishApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_plan';
}
$out->$action();
?>