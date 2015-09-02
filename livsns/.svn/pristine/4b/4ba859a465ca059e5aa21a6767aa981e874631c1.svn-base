<?php

require('global.php');
define('MOD_UNIQUEID', 'mkpublish'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
require_once(ROOT_PATH . 'lib/class/publishsys.class.php');

class mkpublishApi extends adminBase
{

    //站点id
    private $site_id;
    //页面类型id
    private $page_id;
    //页面数据id
    private $page_data_id;
    //内容类型
    private $content_type;
    //客户端类型
    private $client_type;
    //生成文件内容
    private $html              = '';
    //文件里的所有数据源
    private $ds                = array();
    //文件里所有单元使用的完整数据源
    private $cell_ds           = array();
    //文件要写入的css
    private $css               = '';
    //文件要写入的js
    private $js                = '';
    //头部结果集
    private $head_set;
    //单元集合（样式）
    private $cell_set;
    //$cell_info_set['mk']单元生成方式 0静态 1动态
    private $cell_info_set;
    //模板
    private $template_set;
    //自身，列表模板类型id
    private $content_type_true = array(0, -1);
    
    private $page_info         = array(); //file_mktype filename weburl
    
    private $plan;
    
    private $is_page_content = true;   //页面是正文还是列表

    public function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . "lib/common.php");
        include(CUR_CONF_PATH . 'lib/mkpublish.class.php');
        $this->obj         = new mkpublish();
        $this->pub_config  = new publishconfig();
        $this->pub_content = new publishcontent();
        $this->pub_sys     = new publishsys();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    private function setHtml($html)
    {
        $this->html = $this->html . ' ' . $html;
    }

    private function get_cachedir()
    {
        if ($this->page_id_c && $this->page_data_id_c)
        {
            $dir = $this->site_id . '/' . $this->page_id_c . '/' . $this->page_data_id_c . '/' . $this->content_type . '/' . $this->client_type;
        }
        else if ($this->page_data_id_s && $this->content_type_s)
        {
            $dir = $this->site_id . '/' . $this->page_id . '/' . $this->page_data_id_s . '/' . $this->content_type_s . '/' . $this->client_type;
        }
        else
        {
            $dir = $this->site_id . '/' . $this->page_id . '/' . $this->page_data_id . '/' . $this->content_type . '/' . $this->client_type;
        }
        return $dir;
    }

    private function get_cachefilename($atta = '')
    {
        if ($this->page_id_c && $this->page_data_id_c)
        {
            $filename = $this->site_id . '_' . $this->page_id_c . '_' . $this->page_data_id_c . '_' . $this->content_type . '_' . $this->client_type . $atta . '.php';
        }
        else if ($this->page_data_id_s && $this->content_type_s)
        {
            $filename = $this->site_id . '_' . $this->page_id . '_' . $this->page_data_id_s . '_' . $this->content_type_s . '_' . $this->client_type . $atta . '.php';
        }
        else
        {
            $filename = $this->site_id . '_' . $this->page_id . '_' . $this->page_data_id . '_' . $this->content_type . '_' . $this->client_type . $atta . '.php';
        }
        return $filename;
    }

    private function set_error($error = 'error')
    {
        $this->addItem_withkey('status', 'error');
        $this->addItem_withkey('message', $error);
        $this->output();
    }

    private function set_cache()
    {
        $cache_arr              = array();
        //$cache_arr['head_set']        = $this->head_set;
        //$cache_arr['cell_set']        = $this->cell_set;
        //$cache_arr['cell_mktype_set'] = $this->cell_mktype_set;
        //$cache_arr['template_set']    = $this->template_set;
        $cache_arr['page_info'] = $this->page_info;
        //$cache_arr['js']              = $this->js;
        //$cache_arr['css']             = $this->css;
        //$cache_arr['cell_ds']         = $this->cell_ds; //数据源参数合并后的
        //$cache_arr['ds']              = $this->ds; //数据源参数合并后的
        //$cache_arr['mar_css']         = $this->mar_css; //数据源参数合并后的
        file_in(MKPUBLISH_DIR . $this->get_cachedir(), $this->get_cachefilename(), serialize($cache_arr), true, true);
    }

    public function show()
    {
        $this->site_id        = intval($this->input['plan']['site_id']);
        $this->page_id        = intval($this->input['plan']['page_id']);
        $this->page_data_id   = intval($this->input['plan']['page_data_id']);
        $this->content_type   = intval($this->input['plan']['content_type']);
        $this->template_sign  = ($this->input['plan']['template_sign']);
        $this->client_type    = intval($this->input['plan']['client_type']);
        $this->page_id_c      = intval($this->input['plan']['page_id_c']);
        $this->page_data_id_c = intval($this->input['plan']['page_data_id_c']);
        $content_rid          = intval($this->input['plan']['rid']);
        //只生成缓存文件
        $only_mk_cache        = $this->input['only_mk_cache'];
        $this->plan           = $this->input['plan'];

        //查询是否是专题的生成
        $page_type        = common::get_page_manage($this->site_id, $this->page_id, 'id');
        $page_type_detail = $page_type[$this->page_id];
        if ($page_type_detail['sign'] == 'special')
        {
            //查询专题的生成目录
            include_once(ROOT_PATH . 'lib/class/special.class.php');
            $special_obj          = new special();
            $special_detail       = $special_obj->get_mkspecial('', $this->page_data_id);
            $this->page_data_id_s = $special_detail['id'];
            $this->content_type_s = $this->page_data_id . 's';
        }

        //取缓存文件
        $file = MKPUBLISH_DIR . $this->get_cachedir() . '/' . $this->get_cachefilename();
        if ($only_mk_cache || !file_exists($file))
        {
            //去生成缓存文件
            //$this->build_mk_cache();
            
        }
        else
        {
            $filedata        = unserialize(file_get_contents($file));
            //$this->head_set        = $filedata['head_set'];
            //$this->cell_set        = $filedata['cell_set'];
            //$this->cell_mktype_set = $filedata['cell_mktype_set'];
            //$this->template_set    = $filedata['template_set'];
            $this->page_info = $filedata['page_info'];
            //$this->js              = $filedata['js'];
            //$this->css             = $filedata['css'];
            //$this->cell_ds         = $filedata['cell_ds'];
            //$this->ds              = $filedata['ds'];
            //$this->mar_css         = $filedata['mar_css'];
        }

        //取缓存文件
        $file_result = MKPUBLISH_DIR . $this->get_cachedir() . '/' . $this->get_cachefilename('result');
        if ($only_mk_cache || !file_exists($file_result))
        {
            $this->build_mk_cache();

            /*             * 单元模板合并，生成文件 */
            //加载分页信息
            $this->head_set .= "\n" . '$need_page_info[\'file_mktype\'] = \'' . $this->page_info['file_mktype'] . '\';';
            $this->head_set .= "\n" . '$need_page_info[\'page_url\'] = \'' . '' . '\';';
            //$this->head_set .= "\n" . '$need_page_info[\'page_url\'] = \'' . rtrim($this->page_info['weburl'], '/') . '\';';
            $this->head_set .= "\n" . '$need_page_info[\'page_filename\'] = \'' . rtrim($this->page_info['filename'], '/') . '\';';
            $this->head_set .= "\n" . '$need_page_info[\'suffix\'] = \'' . $this->page_info['suffix'] . '\';';
            $this->head_set .= "\n" . '$GLOBALS[\'need_page_info\'] = $need_page_info;';

            //单元处理
            $this->cell_code();
            if (is_array($this->cell_set))
            {
                foreach ($this->cell_set as $k => $v)
                {
                    $tem_replace_cell[$k] = $v['cell'];
                    //模板里需要替换的单元
                    $tem_find_cell[$k]    = '/<span[\s]+(?:id|class)="livcms_cell".+?>liv_' . $this->cell_mktype_set[$k]['cell_name'] . '<\/span>/';
                }
            }

            //替换模板里的单元
            if ($tem_find_cell)
            {
                $this->template_set = preg_replace($tem_find_cell, $tem_replace_cell, $this->template_set);
            }

            //生成缓存文件
            $file_result_html = '<?php ' . $this->head_set . '?>' . $this->template_set;
            $file_result_html = $this->mateurl_replace($file_result_html);
            file_in(MKPUBLISH_DIR . $this->get_cachedir(), $this->get_cachefilename('result'), $file_result_html, true, true);
        }
        if ($only_mk_cache)
        {
            echo 'ok';
            exit;
        }
        $request['rid']             = $content_rid;
        if($this->plan['page_num'] == -1)
        {
            //表示生成内容正文全文阅读
            $request['pp'] = -1;
        }
        else
        {
            $request['pp']              = $this->plan['page_num'] <= 1 ? 0 : intval($this->plan['offset']);
        }
        $request['__page_max_page'] = intval($this->plan['max_page']);
        $request['__content_detail'] = $this->plan['content_detail'];

        //运行缓存文件
        //可以先取内容正文，如关键字，描述，标题等
        //get_content
        $r = $this->pub_sys->mk_include($file_result, $request);
        
        //结果处理
        $_get_analysis_result = isset($r['_get_analysis_result']) ? $r['_get_analysis_result'] : array();
        $this->page_info['filename'] .= $this->plan['page_num'] <= 1 ? '' : ('_' . $this->plan['page_num']);
        $_get_analysis_result['file_name'] = $this->plan['content_detail']['file_name'];
        //if ($_get_analysis_result['file_name'] && $_get_analysis_result['file_name'] != '.php')
        if ($this->page_info['file_mktype']!=2)//静态文件生成处理url
        {
            if ($this->plan['page_num'] > 1 || $this->plan['page_num'] == -1)
            {
                $get_pages_url                     = get_pages_url($_get_analysis_result['file_name'], $this->plan['page_num']==-1?'all':$this->plan['page_num']);
                $_get_analysis_result['file_name'] = $get_pages_url['filedir'];
                $ret['org_filename']               = $get_pages_url['org_filename'];
            }
        }

        $ret['html']                = $r['html'];
        $ret['new_plan']            = $_get_analysis_result['new_plan'];
        $ret['page_info']           = $this->page_info;
        $ret['status']              = 'ok';
        $ret['filename']            = $_get_analysis_result['file_name'];
        $ret['get_analysis_result'] = $_get_analysis_result;
        $ret['need_show_all_pages'] = $this->settings['need_show_all_pages'];
        $ret['page_content_type']   = $this->page_info['page_content_type']?1:0;

        if ($this->mar_css)
        {
            $this->mar_css['css'] = $this->css;
            $ret['mar_css']       = $this->mar_css;
        }

        if ($this->site_id && !$this->page_id)
        {
            $ret['site']   = $this->site;
            $ret['client'] = $this->client;
        }
        if ($this->js_cell)
        {
            foreach ($this->js_cell as $k => $v)
            {
                $this->js_cell[$k]['head'] = str_replace('<start_replace___info></start_replace___info>', '$__info=' . var_export($r['__info'], true) . ';', $v['head']);
            }
            $ret['js_cell'] = $this->js_cell;
        }
        $this->addItem($ret);
        $this->output();
    }

    public function build_mk_cache()
    {
        //获取站点信息
        $this->site        = $this->pub_config->get_site_first('*', $this->site_id);
        $css_js_filein_tem = true;

        //获取客户端详细信息
        $this->client = $this->pub_config->get_client_first($this->client_type);

        //设置文件生成方式
        $this->page_info['file_mktype']        = $this->site['produce_format'];
        $this->page_info['filename']           = $this->site['indexname']?$this->site['indexname']:'index';
        $this->page_info['weburl']             = $this->site['site_info']['url'];
        $this->page_info['dir']                = $this->site['site_dir'];
        $this->page_info['custom_content_dir'] = $this->site['custom_content_dir'];
        $this->page_info['custom_content_dir'] = $this->site['custom_content_dir'];
        $this->page_info['tem_material_dir']   = get_site_temdir($this->site);
        $this->page_info['tem_material_url']   = get_site_temurl($this->site);

        //如果有页面id
        if ($this->page_id)
        {
            $page_type        = common::get_page_manage($this->site_id, $this->page_id, 'id');
            $page_type_detail = $page_type[$this->page_id];
            if ($page_type_detail['sign'] == 'special')
            {
                //查询专题的生成目录
            }
            else if ($page_type_detail['sign'])
            {
                //设置文件生成方式
                $this->page_info['file_mktype'] = isset($page_type_detail['maketype']) ? $page_type_detail['maketype'] : 1;
                $this->page_info['filename']    = isset($page_type_detail['colindex']) ? $page_type_detail['colindex'] : 'index';
                $this->page_info['weburl']      = $page_type_detail['column_domain'];
                $this->page_info['dir'] .= $page_type_detail['column_dir'];
            }

            //如果有页面数据id,
            if ($this->page_data_id && $page_type_detail['sign'] != 'special')
            {
                $page_data        = common::get_page_data($this->page_id, '', '', '', $page_type_detail, $this->page_data_id);
                $page_data_detail = $page_data['page_data'][0];
                if (!$page_data_detail)
                {
                    $this->set_error('未取得page_data_id:' . $this->page_id .'_'. $this->page_data_id.var_export($page_data,true) . '详细信息');
                }
                $this->column = $page_data_detail;

                //设置文件生成方式
                if (in_array($this->content_type, $this->content_type_true))
                {
                    $this->page_info['file_mktype'] = $page_data_detail['maketype'];
                }
                else
                {
                    $this->page_info['file_mktype'] = $page_data_detail['col_con_maketype'];
                    //表示内容正文（目前生成分页正文全文链接有作用）
                    $this->page_info['page_content_type'] = 1;
                }
                //设置文件生成方式
                $this->page_info['filename'] = $page_data_detail['colindex'];
                $this->page_info['weburl']   = $page_data_detail['column_domain'];
                $this->page_info['dir'] .= $page_data_detail['column_dir'];
                $this->page_info['column_dir'] .= $page_data_detail['column_dir'];
                $this->page_info['relate_dir'] .= $page_data_detail['relate_dir'];
            }
        }

        $this->page_info['suffix'] = $this->page_info['file_mktype'] == 1 ? '.html' : '.php';

        //内容页计算出内容的url
        if (in_array($this->content_type, $this->content_type_true))
        {
            $this->is_page_content = false;
        }
        
        //取单元，设有缓存
        if ($this->page_id_c && $this->page_data_id_c)
        {
            //专题模块查询出该专题详细信息
            include_once(ROOT_PATH . 'lib/class/special.class.php');
            $special_obj          = new special();
            $this->special_detail = $special_obj->get_special_by_id($this->page_data_id_c);
            //$cell_ret             = common::get_special_cell_list($this->page_data_id_c, $this->template_sign, 0, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name']);
            $cell_ret             = common::getTemplateAndCell(0, 0, $this->page_data_id_c, 0, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'], $this->template_sign);
            if (is_array($this->special_detail) && $this->special_detail)
            {
                //$this->page_info['column_dir'] .= $this->special_detail['column_dir'];
                $this->page_info['file_mktype'] = $this->special_detail['maketype'];
                $this->page_info['suffix']      = $this->page_info['file_mktype'] == 1 ? '.html' : '.php';
            }
            //表示内容正文（目前生成分页正文全文链接有作用）
            $this->page_info['page_content_type'] = 0;
        }
        else if ($page_type_detail['sign'] == 'special')
        {
            //专题模块查询出该专题详细信息
            include_once(ROOT_PATH . 'lib/class/special.class.php');
            $special_obj          = new special();
            //根据专题子栏目查询出专题
            $this->special_detail = $special_obj->get_mkspecial('', $this->page_data_id);
            //查询出这个专题的发布目录
            if ($this->special_detail && is_array($this->special_detail))
            {
                $this->special_column           = $this->special_detail['special_column'];
                $this->page_info['column_dir'] .= $this->special_detail['column_dir'];
                $this->page_info['relate_dir']  = $this->special_detail['relate_dir'];
                $this->page_info['special_dir'] = $this->special_detail['special_dir'];
                $this->page_info['file_mktype'] = $this->special_detail['maketype'];
                $this->page_info['filename']    = $this->special_detail['colindex'];
                $this->page_info['dir'] .= $this->special_detail['column_dir'];
                $this->page_info['suffix']      = $this->page_info['file_mktype'] == 1 ? '.html' : '.php';
                $this->page_info['page_type']   = 'special';
                $this->template_sign            = $this->special_detail['template_sign'];
                $this->column['name']           = $this->special_detail['title'];
                $this->column['keywords']       = $this->special_detail['keywords'];
                $this->column['content']        = $this->special_detail['brief'];
                $this->page_data_id_s           = $this->special_detail['id'];
                $this->content_type_s           = $this->page_data_id . 's';
            }
            else
            {
                $this->set_error('未取到专题信息，专题的栏目id:'.$this->page_data_id.';专题返回数据(get_mkspecial):'.var_export($this->special_detail,true));
            }
            //$cell_ret = common::get_special_cell_list($this->special_detail['id'], $this->template_sign, $this->page_data_id, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name']);
            $cell_ret = common::getTemplateAndCell(0, 0, $this->special_detail['id'], $this->page_data_id, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'], $this->template_sign);
        }
        else
        {
                    //$cell_ret = common::get_cell_list($this->site_id, $this->page_id, $this->page_data_id, $this->content_type, $this->template_sign);
            $cell_ret = common::getTemplateAndCell($this->site_id, $this->page_id, $this->page_data_id, $this->content_type, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name']);
        }
        /**
        foreach($cell_ret['default_cell'] as $k=>$v)
        {
            if($v['layout_id'])
            {
                $cell_ret['default_cell'][$k]['id']=$v['id'].'_'.$v['layout_id'];
            }
        }
         */
        //当前使用中的套系标识
        $template_style = $cell_ret['curr_style'] ? $cell_ret['curr_style'] : 'default';

        //当前模板标识
        $template_sign = $cell_ret['template_sign'];

        //获取模板，设有缓存
        if (($this->page_id_c && $this->page_data_id_c) || $page_type_detail['sign'] == 'special')
        {
            foreach ($cell_ret['default_cell'] as $k => $v)
            {
                if ($v['layout_id'])
                {
                    $cell_ret['default_cell'][$k]['id'] = $v['id'] . '_' . $v['layout_id'];
                }
            }
            $this->template_set = $cell_ret['template'];
            //$this->template_set = common::get_template_cache($template_sign, $template_style, $this->settings['special_template']['site_id'], $this->page_info['tem_material_url'] . '/' . $this->settings['template_name']);
        }
        else
        {
            $this->template_set = $cell_ret['template'];
            //$this->template_set = common::get_template_cache($template_sign, $template_style, $this->site_id, $this->page_info['tem_material_url'] . '/' . $this->settings['template_name']);
        }

        //页面标题关键字描述的插入到模板中
        if (!$this->is_page_content)
        {
            $this->template_set = template_process($this->template_set, $this->site, $this->column);
        }
        else
        {
            $this->template_set = template_process($this->template_set, $this->site, $this->column, true);
        }

        //取出单元用到的数据源，样式
        $this->cell_process($cell_ret);

        //js加入栏目信息到源码中
        $column_detail_codearr = array(
            'id' => $page_data_detail['id'],
            'title' => $page_data_detail['name'],
            'keywords' => $page_data_detail['keywords'],
        );
        $column_detail_codestr      = "\n" . "<script type=\"text/javascript\">var m2o_column=" . json_encode($column_detail_codearr).";";
        $m2o_content_detail_codearr = '<?php 
                        $m2o_content_detail_codearr = array(
                        \'id\' => $__info[\'content\'][\'content_id\'],
                        \'title\' => $__info[\'content\'][\'title\'],
                        \'keywords\' => $__info[\'content\'][\'keywords\'],
                        \'bundle_id\' => $__info[\'content\'][\'bundle_id\'],
                        \'module_id\' => $__info[\'content\'][\'module_id\'],
                        \'content_id\' => $__info[\'content\'][\'content_id\'],
                        \'content_fromid\' => $__info[\'content\'][\'content_fromid\'],
                        \'rid\' => $__info[\'content\'][\'rid\'],
                    );
                    echo json_encode($m2o_content_detail_codearr);
                    ?>';
        $column_detail_codestr .= "var m2o_content = '".$m2o_content_detail_codearr."';";
        $column_detail_codestr .= "</script>" . "\n";
        $this->template_set    = str_ireplace('</head>', $column_detail_codestr . '</head>', $this->template_set);

        //css加载到模板中
        if (!$this->is_page_content)
        {
            $this->template_set = str_ireplace('</head>', $this->css . "\n" . '</head>', $this->template_set);
            $this->css          = '';
        }
        else
        {
            //计算js，css路径，外链到模板head中去
            //页面素材域名
            if ($this->page_id_c && $this->page_data_id_c)
            {
                $mar_dir      = $this->page_id_c . '/' . $this->page_data_id_c . '/' . $this->content_type . '/';
                $css_filename = $this->page_id_c . '_' . $this->page_data_id_c . '_' . $this->content_type . '_' . $this->client_type . '.css';
            }
            else
            {
                $mar_dir      = $this->page_id . '/' . $this->page_data_id . '/' . $this->content_type . '/';
                $css_filename = $this->page_id . '_' . $this->page_data_id . '_' . $this->content_type . '_' . $this->client_type . '.css';
            }
            //计算css路径
            $this->mar_css['mar_css_dir']      = $this->page_info['tem_material_dir'] . '/' . $this->settings['template_name'] . '/' . $mar_dir;
            $this->mar_css['mar_css_filename'] = $css_filename;
            $css_link                          = '<link  rel="stylesheet" type="text/css" href="' . $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'] . '/' . $mar_dir . $css_filename . '" />';
            $this->template_set                = str_ireplace('</head>', $css_link . "\n" . '</head>', $this->template_set);
        }
        //js添加到</body>之前
        if ($this->js)
        {
            $this->template_set = str_ireplace('</body>', $this->js . "\n" . '</body>', $this->template_set);
            $this->js           = '';
        }

        //生成头部文件
        $this->include_head($cell_ret);

        //生成缓存文件
        $this->set_cache();

        //复制数据源缓存到lib/m2o/include
        if (is_dir(CUR_CONF_PATH . 'cache/datasource'))
        {
            if(!file_copy(CUR_CONF_PATH . 'cache/datasource', CUR_CONF_PATH . 'lib/m2o/include', array()))
            {
                $this->set_error('模板服务器:'.realpath(CUR_CONF_PATH . 'lib/m2o/include').'目录不可写');
            }
        }
    }

    //加载头部
    public function include_head($cell_ret)
    {
        //ROOT_PATH定义,引入global文件
//		$html = ' $M2O_ROOT_PATH = substr(preg_replace(\'#[^/]+#\', \'..\', $_SERVER[\'PHP_SELF\']), 1, -2).\'m2o/\';'.
        $html = ' $M2O_ROOT_PATH = \'' . '/' . trim($this->site['site_dir'], '/') . '/m2o/\';' .
                ' define(\'M2O_ROOT_PATH\', $M2O_ROOT_PATH);' .
                ' define(\'CUR_CONF_PATH\', $M2O_ROOT_PATH); ';
        $html .= 'require_once(M2O_ROOT_PATH.\'' . 'global.php\');';

        //定义基础数据
        $html .= 'global $gGlobalConfig;' .
                ' $__configs = $gGlobalConfig;';
        $js_base_html = $html;
        $html .= '$__info[\'site\'] = ' . var_export($this->site, true) . ';' .
                ' $__info[\'column\'] = ' . ($this->column ? var_export($this->column, true) : 'array()') . ';' .
                ' $__info[\'client\'] = ' . ($this->client ? var_export($this->client, true) : 'array()') . ';';
        if ($this->special_detail)
        {
            $html .= ' $__info[\'special\'] = ' . var_export($this->special_detail, true) . ';';
        }
        if ($this->special_column)
        {
            $html .= ' $__info[\'special_column\'] = ' . var_export($this->special_column, true) . ';';
        }
        
        if($this->is_page_content)
        {
            //查询正文内容
            $html .= ' if($_REQUEST[\'rid\'] = intval($_REQUEST[\'rid\']))
            {
                $__info[\'content\'] = get_content_detail($_REQUEST[\'rid\']);
            } ';
        }
        
        $base_html = $html;
        if ($this->ds['ds_sort'] && is_array($this->ds['ds_sort']))
        {
            foreach ($this->ds['ds_sort'] as $k => $v)
            {
                foreach ($cell_ret['default_cell'] as $kkk => $vvv)
                {
                    if ($this->cell_ds_is_loaded[$vvv['id']])
                    {
                        continue;
                    }
                    //$cell_dynamic   $cell_static
                    //0为静态单元 1为动态单元 2为js调用   动态单元直接echo  静态单元赋值结果
                    if (!$vvv['data_source'])
                    {
                        $this->cell_ds_is_loaded[$vvv['id']] = true;
                    }
                    $cell_one = $this->get_cell_ds($vvv, $v);
                    if ($this->cell_mktype_set[$vvv['id']]['cell_type'] == 2)
                    {
                        //js调用单元
                        $this->js_cell[$vvv['id']]['head'] = $js_base_html . '<start_replace___info></start_replace___info>';
                        $this->js_cell[$vvv['id']]['ds']   = $cell_one;
                    }
                    else
                    {
                        if ($this->page_info['file_mktype'] != 1)
                        {
                            //动态
                            if ($this->cell_mktype_set[$vvv['id']]['cell_type'])
                            {
                                $html .= $cell_one;
                                $head_set_next .= 'echo \'<?php ' . pub_addslashes($cell_one) . ' ?>\';';
                            }
                            else
                            {
                                $head_set_next .= 'echo \'<?php $__cell_data[\\\'' . $vvv['id'] . '\\\'] = \'.var_export($__cell_data[\'' . $vvv['id'] . '\'],true).\' ?>\';';
                            }
                        }
                        $html .= $cell_one;
                    }
                }
            }
        }

        $html .= $this->html;
        $html .= ' $GLOBALS[\'__info\'] = $__info;';
        if ($this->page_info['file_mktype'] == 1)
        {
            //静态
            $this->head_set = $html;
        }
        else
        {
            //动态,
            $this->head_set .= $html;
            $this->head_set .= "\n" . 'echo \'<?php ' . pub_addslashes($base_html) . ' ?>\';';
            $this->head_set .= "\n" . $head_set_next;
            $this->head_set .= "\n" . 'echo \'<?php ' . pub_addslashes('$GLOBALS[\'__info\'] = $__info;') . ' ?>\';';
        }
    }

    public function get_cell_ds($vvv, $v)
    {
        $html = '';
        if ($vvv['using_block'] && $vvv['block_id'])
        {
            $html .= '$__cell_data[\'' . $vvv['id'] . '\'] = web_get_block(\'' . $vvv['block_id'] . '\',\'' . $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'] . '/icon/' . '\');';
            $this->cell_ds_is_loaded[$vvv['id']] = true;
        }
        else if ($vvv['data_source'] == $v)
        {
            //引入数据源
            $html .= 'include_once(M2O_ROOT_PATH.\'include/' . $v . '.php\');';
            $html .= '$ds_' . $v . ' = new ' . 'ds_' . $v . '();';
            //执行数据源:1.取单元设置的参数 2.取该数据源依赖其他数据源的参数
            $html .= '$__cell_data[\'' . $vvv['id'] . '\'] = web_compare_cell_content(\'' . $vvv['id'] . '\',$ds_' . $v . '->show(' . array_export($this->cell_ds[$vvv['id']]) . '));';
            //样式默认数据
            $html .= 'if(!$__cell_data[\'' . $vvv['id'] . '\'] || !is_array($__cell_data[\'' . $vvv['id'] . '\']))
					{
						if(' . var_export($this->cell_set[$vvv['id']]['mode_detail']['default_param'], true) . ' && is_array(' . var_export($this->cell_set[$vvv['id']]['mode_detail']['default_param'], true) . '))
						{
							$__cell_data[\'' . $vvv['id'] . '\'] = ' . var_export($this->cell_set[$vvv['id']]['mode_detail']['default_param'], true) . ';
						}
						else
						{
							$__cell_data[\'' . $vvv['id'] . '\'] = array();
						}
					}';
            $html .= $this->out_param($vvv);
            if ($vvv['mode_detail']['mode_type'] == 1)
            {
                //正文数据源,取内容标题关键字描述
                $html .= '$__info[\'__pagetitle\'] = $__info[\'__pagetitle\']?$__info[\'__pagetitle\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'title\'];
                      $__info[\'__pagekeyword\'] = $__info[\'__pagekeyword\']?$__info[\'__pagekeyword\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'keyword\'];
                      $__info[\'__pagedescription\'] = $__info[\'__pagedescription\']?$__info[\'__pagedescription\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'brief\'];';
            }
            $this->cell_ds_is_loaded[$vvv['id']] = true;
        }
        //如果没有选择数据源 则用样式里默认数据
        if (!$vvv['data_source'])
        {
            $html .= 'if(is_array(' . var_export($this->cell_set[$vvv['id']]['mode_detail']['default_param'], true) . '))
						{
							$__cell_data[\'' . $vvv['id'] . '\'] = ' . var_export($this->cell_set[$vvv['id']]['mode_detail']['default_param'], true) . ';
						}
						else
						{
							$__cell_data[\'' . $vvv['id'] . '\'] = array();
						}';
            if ($vvv['mode_detail']['mode_type'] == 1)
            {
                //正文数据源,取内容标题关键字描述
                $html .= '$__info[\'__pagetitle\'] = $__info[\'__pagetitle\']?$__info[\'__pagetitle\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'title\'];
                      $__info[\'__pagekeyword\'] = $__info[\'__pagekeyword\']?$__info[\'__pagekeyword\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'keyword\'];
                      $__info[\'__pagedescription\'] = $__info[\'__pagedescription\']?$__info[\'__pagedescription\']:$__cell_data[\'' . $vvv['id'] . '\'][0][\'brief\'];';
            }
            $this->cell_ds_is_loaded[$vvv['id']] = true;
        }
        return $html;
    }

    public function out_param($v)
    {
        if ($this->ds['ds_datas'][$v['data_source']]['out_param'])
        {
            $out_paramarr = explode(',', $this->ds['ds_datas'][$v['data_source']]['out_param']);
            //此数据源数据添加到$__info中去
            $html         = '';
            if ($out_paramarr)
            {
                $out_paramarr = str_process($out_paramarr);
            }
            if($this->cell_ds[$v['id']]['need_count'])
            {
                $array_location = "['data'][0]";
            }
            else
            {
                $array_location = "[0]";
            }
                
            foreach ($out_paramarr as $kk => $vv)
            {
                $r = explode('=>', $vv);
                if ($r[0] && $r[1])
                {
//					$html .= ' $__info[\'ds_out'.($ds_out_flag?$v['id']:'').'\'][\''.$r[0].'\']=';
                    $html .= ' $__info[\'ds_out\'][\'' . $r[0] . '\']=';
                    $html .= ('$__cell_data[\'' . $v['id'] . '\']'.$array_location.'[\'' . $r[1] . '\']') . ';';
                    $html .= ' ';
                    $ds_out_flag = true;
                }
            }
//			$this->ds_outcell['ds_out'.($ds_out_flag?$v['id']:'')] = $v['data_source'];
//			$this->ds_outcell['ds_out'.($ds_out_flag?$v['id']:'')] = $v['data_source'];
//			$this->setHtml($html);
        }
        return $html;
    }

    public function cell_process($cell_ret)
    {
        /*         * 单元生成方式是静态的还是动态的  0是静态  1是动态 */
        $mode_idarr   = $datasourceid = $cssarr       = $tem_js       = $tem_css      = array();
        $first_ds     = $last_ds      = array();
        if (!is_array($cell_ret['default_cell']))
        {
            return false;
        }

        //取出单元用到的数据源，样式
        foreach ($cell_ret['default_cell'] as $k => $v)
        {
            if ($v['cell_mode'])
            {
                $mode_idarr[$v['cell_mode']] = $v['cell_mode'];

                //取各个样式里js,css并合并
                $this->css_js_process($v);
            }
            if ($v['data_source'])
            {
                $datasourceid[]         = $v['data_source'];
                //单元对应数据源
                $ds_cell_dsid[$v['id']] = $v['data_source'];
            }
            $this->cell_mktype_set[$v['id']] = array('cell_type' => $v['cell_type'],
                'cell_name' => $v['cell_name']);
        }

        //js,css整合
        $this->css_js_process($v, true);

        //数据源信息
        $datasourceid = (is_array($datasourceid) && $datasourceid) ? array_unique($datasourceid) : array();
        $this->datasource_param($datasourceid);

        foreach ($cell_ret['default_cell'] as $k => $v)
        {
            //数据源参数跟单元设的参数合并
            $data_input_variable = array();
            if ($v['data_source'])
            {
                $data_input_variable                     = is_array($v['param_asso']['input_param']) ? $v['param_asso']['input_param'] : array();
                $this->ds['ds_param'][$v['data_source']] = is_array($this->ds['ds_param'][$v['data_source']]) ? $this->ds['ds_param'][$v['data_source']] : array();
                $this->cell_ds[$v['id']]                 = $data_input_variable + $this->ds['ds_param'][$v['data_source']];

                //判断样式里有没有分页函数，有则在数据源参数里加入
                if (strstr($this->cell_set[$v['id']]['cell'], 'web_build_page_link') !== false)
                {
                    //表示需要分页
                    /**
                    if ($this->cell_set[$v['id']]['mode_detail']['mode_type'] == 1)
                    {
                        $this->cell_ds[$v['id']]['need_count'] = 0;
                        $this->cell_ds[$v['id']]['need_pages'] = 1;
                    }
                    else
                    {
                        $this->cell_ds[$v['id']]['need_count'] = 1;
                    }
                    */
                    $this->cell_ds[$v['id']]['need_pages'] = 1;
                    $this->cell_ds[$v['id']]['need_count'] = 1;
                    $this->cell_ds[$v['id']]['offset']      = '$_REQUEST[\'pp\']';
                    $this->cell_set[$v['id']]['need_pages'] = 1;
                }
                else
                {
                    /**
                    if ($this->cell_set[$v['id']]['mode_detail']['mode_type'] == 1)
                    {
                        $this->cell_ds[$v['id']]['need_pages'] = 0;
                    }
                    */
                    $this->cell_ds[$v['id']]['need_pages'] = 0;
                    $this->cell_ds[$v['id']]['need_count'] = 0;
                }
            }
            //单元所用数据源标识存入到cell_set中
            $this->cell_set[$v['id']]['ds_sign'] = $this->ds['ds_datas'][$v['data_source']]['sign'];
        }

        //处理数据源先后顺序,数据源自动获取参数值的设定
        foreach ($cell_ret['default_cell'] as $k => $v)
        {
            if (!$v['data_source'])
            {
                continue;
            }
            if ($this->ds['ds_datas'][$v['data_source']]['argument']['type'])
            {
                foreach ($this->ds['ds_datas'][$v['data_source']]['argument']['type'] as $kk => $vv)
                {
                    //表示此参数是自动获取
                    if ($vv == 'auto' || $vv == 'special_column' || $vv == 'column')
                    {
                        //参数
                        $param       = $this->ds['ds_datas'][$v['data_source']]['argument']['ident'][$kk];
                        //查看这个参数在单元参数里设置的值,并替换成真实变量htmlspecialchars_decode
                        $param_value = $this->cell_ds[$v['id']][$param];
                        $param_value = str_process($param_value);
                        preg_match('/\$_POST\[|\$_GET\[|\$_REQUEST\[|$_SESSTION|$_COOKIE/i', $param_value, $mat);
                        if ($mat[0])
                        {
                            $this->cell_ds[$v['id']][$param] = $param_value;
                        }
                        else
                        {
                            //是js调用
                            //ds_out.id
                            $ds_out = explode('.', $param_value);
                            if ($ds_out[0] && $ds_out[1])
                            {
                                /** if (preg_match('/ds_out(\d*)/i', $param_value, $mat))
                                  {
                                  $this->ds['ds_sort'][] = $this->ds_outcell['ds_out' . $mat[1]];
                                  $this->ds['ds_sort'][] = $v['data_source'];
                                  } */
                                if ($v['cell_type'] == 2 && $ds_out[0] == 'ds_out')
                                {
                                    $this->cell_ds[$v['id']][$param]          = '$_REQUEST[\'' . $ds_out[1] . '\']';
                                    $this->jscell_param[$v['id']][$ds_out[1]] = '$__info[\'' . $ds_out[0] . '\'][\'' . $ds_out[1] . '\']';
                                }
                                else
                                {
                                    $this->cell_ds[$v['id']][$param] = '$__info[\'' . $ds_out[0] . '\'][\'' . $ds_out[1] . '\']';
                                }
                            }
                        }
                    }
                }
            }
            if ($this->ds['ds_datas'][$v['data_source']]['out_param'])
            {
                $first_ds[] = $v['data_source'];
            }
            else
            {
                $last_ds[] = $v['data_source'];
            }
        }
        $this->ds['ds_sort'] = @array_unique(@array_merge($first_ds, $last_ds));
    }

    //获取数据源详细信息
    public function datasource_param($datasourceid)
    {
        if (!$datasourceid)
        {
            return;
        }
        $ret      = $result   = $ds_datas = array();
        $sql      = "SELECT * FROM " . DB_PREFIX . "data_source  WHERE id in(" . implode(',', $datasourceid) . ")";
        $info     = $this->db->query($sql);
        while ($row      = $this->db->fetch_array($info))
        {
            $row['argument'] = $row['argument'] ? unserialize($row['argument']) : array();

            //此参数需要加入到$__info[]里
            $row['out_param']     = $row['out_param'];
            $ret[$row['id']]      = $row['argument'];
            $ds_datas[$row['id']] = $row;
        }
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                if (!$v['ident'])
                {
                    continue;
                }
                foreach ($v['ident'] as $kk => $vv)
                {
                    $result[$k][$vv] = $v['value'][$kk];
                }
                $ds_id_sign[$v['id']] = $v['sign'];
            }
        }
        $r['ds_param'] = $result;
        $r['ds_datas'] = $ds_datas;
        $r['ds_ids']   = $datasourceid;
        $this->ds      = $r;
    }

    public function get_modecache_dir($cellid)
    {
        return MODE_CACHE_DIR . $cellid . '.php';
    }

    //单元的css,js处理
    public function css_js_process($v, $mk = false)
    {
        if ($mk)
        {
            $this->css = '<style type="text/css">';
            if (is_array($this->tem_css))
            {
                foreach ($this->tem_css as $k => $v)
                {
                    $cell_idarr = array_keys($v);
                    if ($this->cell_set[$cell_idarr[0]]['layout_id'])
                    {
                        $cell_suff = '.layoutcell_';
                    }
                    else
                    {
                        $cell_suff = '.cell_';
                    }
                    $v = preg_replace('/\<NS(.*?)\>/ise', "css_js_replace('\\1','" . implode(',', $cell_idarr) . "','" . $cell_suff . "')", $v);
                    $v = preg_replace('/\<NNS([0-9a-zA-Z]*)\>/ise', "css_js_replace('\\1','" . implode(',', $cell_idarr) . "','" . $cell_suff . "')", $v);
                    if ($v && is_array($v))
                    {
                        foreach ($v as $kk => $vv)
                        {
                            if ($vv)
                            {
                                $this->css .= $vv . "\n";
                            }
                        }
                    }
                }
            }
            $this->css .= '</style>';

            $this->js = '<script type="text/javascript">';
            if (is_array($this->tem_js))
            {
                foreach ($this->tem_js as $k => $v)
                {
                    $cell_idarr = array_keys($v);
                    if ($this->cell_set[$cell_idarr[0]]['layout_id'])
                    {
                        $cell_suff = '.layoutcell_';
                        $cell_suff_NNS = 'layoutcell_';
                    }
                    else
                    {
                        $cell_suff = '.cell_';
                        $cell_suff_NNS = 'cell_';
                    }
                    $v = preg_replace('/\<NS([0-9a-zA-Z]*)\>/ise', "css_js_replace('\\1','" . implode(',', $cell_idarr) . "','" . $cell_suff . "')", $v);
                    $v = preg_replace('/\<NNS([0-9a-zA-Z]*)\>/ise', "css_js_replace('\\1','" . implode(',', $cell_idarr) . "','" . $cell_suff_NNS . "')", $v);
                    //$this->js .= "\n" . implode("\n", $v) . "\n";
                    if ($v && is_array($v))
                    {
                        foreach ($v as $kk => $vv)
                        {
                            if ($vv)
                            {
                                $this->js .= $vv . "\n";
                            }
                        }
                    }
                }
            }
            $this->js .= '</script>';

            //对css js进行附件地址处理，标志：<MATEURL>
            $this->css = $this->mateurl_replace($this->css);
            $this->js  = $this->mateurl_replace($this->js);
            return;
        }
//		$md5_js = md5($v['js']);
//		$md5_css = md5($v['css']);  
        $this->cell_set[$v['id']]['cell'] = common::get_cell_cache($v['id']);
        if ($v['is_header'])
        {
            $find                             = array('{$header_text}', '{$more_href}', '{$more_text}');
            $replace                          = array($v['header_text'], $v['is_more'] ? $v['more_href'] : '#', $v['is_more'] ? '更多>>' : '');
            $header                           = str_replace($find, $replace, $this->settings['header_dom']['cell']);
            $this->cell_set[$v['id']]['cell'] = $header . $this->cell_set[$v['id']]['cell'];
        }
        if ($this->cell_set[$v['id']]['cell'])
        {
            if ($v['layout_id'])
            {
                $this->cell_set[$v['id']]['cell'] = preg_replace('/\<NS([0-9a-zA-Z]*)\>/ise', 'layoutcell_' . $v['id'] . '_\\1', $this->cell_set[$v['id']]['cell']);
                $this->cell_set[$v['id']]['cell'] = preg_replace('/\<NNS([0-9a-zA-Z]*)\>/ise', 'layoutcell_' . $v['id'] . '_\\1', $this->cell_set[$v['id']]['cell']);
            }
            else
            {
                $this->cell_set[$v['id']]['cell'] = preg_replace('/\<NS([0-9a-zA-Z]*)\>/ise', 'cell_' . $v['id'] . '_\\1', $this->cell_set[$v['id']]['cell']);
                $this->cell_set[$v['id']]['cell'] = preg_replace('/\<NNS([0-9a-zA-Z]*)\>/ise', 'cell_' . $v['id'] . '_\\1', $this->cell_set[$v['id']]['cell']);
            }
        }

        $this->cell_set[$v['id']]['js']          = $v['js'];
        $this->cell_set[$v['id']]['css']         = $v['css'];
        $this->cell_set[$v['id']]['mode_detail'] = $v['mode_detail'];
        $this->cell_set[$v['id']]['mode_param']  = $v['mode_param'];
        $this->cell_set[$v['id']]['need_pages']  = 0;
        $this->cell_set[$v['id']]['layout_id']   = $v['layout_id'];
        $this->tem_js[$v['id']][$v['id']]        = $v['js'];
        $this->tem_css[$v['id']][$v['id']]       = $v['css'];
        return;
    }

    //处理单元分页代码加入到单元
    public function cell_code()
    {
        if (!is_array($this->cell_set))
        {
            return false;
        }
        foreach ($this->cell_set as $k => $v)
        {
            $html = '';
            if ($this->cell_mktype_set[$k]['cell_type'] == 4 && $this->settings['is_support_shtml'])
            {
                //shtml单元
                $md5dir                           = $this->get_md5_filedir($k);
                $this->shtml_cell[$k]['dir']      = $this->page_info['tem_material_dir'] . '/' . $this->settings['template_name'] . '/shtml/' . $md5dir . '/';
                $this->shtml_cell[$k]['url']      = $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'] . '/shtml/' . $md5dir . '/';
                $this->shtml_cell[$k]['filename'] = $k . '.php';
                $this->cell_set[$k]['cell']       = '<!--#include file="' . $this->shtml_cell[$k]['url'] . $this->shtml_cell[$k]['filename'] . '"-->';
            }
            if ($this->cell_mktype_set[$k]['cell_type'] == 2)
            {
                //js调用单元
                $md5dir                                = $this->get_md5_filedir($k);
                $this->js_cell[$k]['include_dir']      = $this->page_info['tem_material_dir'] . '/' . $this->settings['template_name'] . '/jscell_include/' . $md5dir . '/';
                //$this->js_cell[$k]['include_filename'] = $k . '.php';
                $this->js_cell[$k]['include_filename'] = $this->get_cachefilename($k);
                $this->js_cell[$k]['dir']              = $this->page_info['tem_material_dir'] . '/' . $this->settings['template_name'] . '/jscell/' . $md5dir . '/';
                $this->js_cell[$k]['url']              = $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'] . '/jscell/' . $md5dir . '/';
                //$this->js_cell[$k]['filename']         = $k . '.php';
                $this->js_cell[$k]['filename']         = $this->get_cachefilename($k);
                $this->cell_set[$k]['cell']            = '<script type="text/javascript" src="' . $this->js_cell[$k]['url'] . $this->js_cell[$k]['filename'];
                if ($this->jscell_param[$k] && is_array($this->jscell_param[$k]))
                {
                    $this->cell_set[$k]['cell'] .= '?';
                    $jsurltag = '';
                    foreach ($this->jscell_param[$k] as $jkey => $jvalue)
                    {
                        $this->cell_set[$k]['cell'] .= $jsurltag . $jkey . '=<?php echo ' . $jvalue . ' ?>';
                        $jsurltag = '&';
                    }
                }
                $this->cell_set[$k]['cell'] .= '"></script>';
                if ($v['need_pages'])
                {
                    if ($v['mode_detail']['mode_type'] == 1)
                    {
                        $this->js_cell[$k]['cell'] = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][0]; ?>' . $v['cell'];
                    }
                    else
                    {
                        $this->js_cell[$k]['cell'] = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][\'data\']; ?>' . $v['cell'];
                    }
                }
                else
                {
                    $this->js_cell[$k]['cell'] = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\']; ?>' . $v['cell'];
                }
            }
            else if ($this->cell_mktype_set[$k]['cell_type'] && $this->page_info['file_mktype'] != 1)
            {
                //动态单元
                if ($v['need_pages'])
                {
                    /**
                    if ($v['mode_detail']['mode_type'] == 1)
                    {
                        $html                            = '$m2o[\'data\'] = $__cell_data[\'' . $k . '\'][0];
								 $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';
								 $contentfilearr = hg_split_url($m2o[\'data\'][0][\'content_url\']);
								 $GLOBALS[\'need_page_info\'] = $need_page_info;
								 $GLOBALS[\'need_page_info\'][\'page_filename\'] = $contentfilearr[\'file\'];
								 $GLOBALS[\'need_page_info\'][\'suffix\'] = $contentfilearr[\'suffix\'];
								 $GLOBALS[\'need_page_info\'][\'need_show_all_pages\'] = \''.$this->settings['need_show_all_pages'].'\';
								 $GLOBALS[\'need_page_info\'][\'page_url\'] = $contentfilearr[\'dir\'];';
                        $this->cell_set[$k]['cell']      = '<?php echo \'' . pub_addslashes($v['cell']) . '\'; ?>';
                        $this->cell_ds[$k]['need_count'] = 0;
                    }
                    else
                    {
                        $html                       = ' $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][\'data\'];
								  $_REQUEST[\'__page_total\'] = $__cell_data[\'' . $k . '\'][\'total\'];
								  $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';
								  ';
                        $this->cell_set[$k]['cell'] = '<?php echo \'' . pub_addslashes($v['cell']) . '\'; ?>';
                        $dy_page_tag                = true;
                    }
                     */
                    //这里开始
                    $html                       = ' if($__info[\'content\'])
                                                    {
                                                        $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][\'data\'];
                                                        $_REQUEST[\'pp\'] = $_REQUEST[\'pp\']?$_REQUEST[\'pp\']:0;
                                                        $contentfilearr = hg_split_url($__info[\'content\'][\'content_url\']);
                                                        $GLOBALS[\'need_page_info\'] = $need_page_info;
                                                        $GLOBALS[\'need_page_info\'][\'page_filename\'] = $contentfilearr[\'file\'];
                                                        $GLOBALS[\'need_page_info\'][\'suffix\'] = $contentfilearr[\'suffix\'];
                                                        $GLOBALS[\'need_page_info\'][\'need_show_all_pages\'] = \'' . $this->settings['need_show_all_pages'] . '\';
                                                        $GLOBALS[\'need_page_info\'][\'page_url\'] = $contentfilearr[\'dir\'];
                                                    }
                                                    else
                                                    {
                                                        $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][\'data\'];
                                                        $_REQUEST[\'__page_total\'] = $__cell_data[\'' . $k . '\'][\'total\'];
                                                    }
                                                    $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';
                                                    
                                                    ';
                    $this->cell_set[$k]['cell'] = '<?php echo \'' . pub_addslashes($v['cell']) . '\'; ?>';
                }
                else
                {
                    $html                       = ' $m2o[\'data\'] = $__cell_data[\'' . $k . '\'];';
                    $this->cell_set[$k]['cell'] = '<?php echo \'' . str_replace("'", "\'", $v['cell']) . '\'; ?>';
                }
                /**
                if($dy_page_tag)
                {
                    $html = '<?php echo \'<?php ' . pub_addslashes($html . '$_REQUEST[\'__page_max_page\']=') . '\'.intval($_REQUEST[\'__page_max_page\']).\'; ?>\';  ?>';
                }
                else
                {
                    $html = '<?php echo \'<?php ' . pub_addslashes($html) . ' ?>\';  ?>';
                }
                */
                $html = '<?php echo \'<?php ' . pub_addslashes($html . 'if(!$__cell_data[\'' . $k . '\'][0])$_REQUEST[\'__page_max_page\']=') . '\'.intval($_REQUEST[\'__page_max_page\']).\'; ?>\';  ?>';
            }
            else
            {
                //静态单元
                if ($v['need_pages'])
                {
                    /*
                    if ($v['mode_detail']['mode_type'] == 1)
                    {
                        //正文样式分页，特殊处理
                        $html                            = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\'];?>';
                        $html .= '<?php $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';?>';
                        $html .= '<?php $_REQUEST[\'__page_max_page\'] = intval($_REQUEST[\'__page_max_page\'])' . ';?>';
                        $html .= '<?php $contentfilearr = hg_split_url($m2o[\'data\'][0][\'content_url\']);' .
                                ' $GLOBALS[\'need_page_info\'] = $need_page_info;' .
                                ' $GLOBALS[\'need_page_info\'][\'page_filename\'] = $contentfilearr[\'file\'];' .
                                ' $GLOBALS[\'need_page_info\'][\'suffix\'] = $contentfilearr[\'suffix\'];' .
                                ' $GLOBALS[\'need_page_info\'][\'need_show_all_pages\'] = \''.$this->settings['need_show_all_pages'].'\';' .
                                ' $GLOBALS[\'need_page_info\'][\'page_url\'] = $contentfilearr[\'filedir\']; ?>';
                        $this->cell_ds[$k]['need_count'] = 0;
                    }
                    else
                    {
                        $html = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\'][\'data\'];?>';
                        $html .= '<?php $_REQUEST[\'__page_total\'] = $__cell_data[\'' . $k . '\'][\'total\']; ?>';
                        $html .= '<?php $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';?>';
                        $html .= '<?php $_REQUEST[\'__page_max_page\'] = intval($_REQUEST[\'__page_max_page\'])' . ';?>';
                    }
                    */
                    
                    //正文样式分页，特殊处理
                    $html = '<?php $m2o[\'data\'] = (array_key_exists(\'data\',$__cell_data[\'' . $k . '\']) && !$__cell_data[\'' . $k . '\'][0])?$__cell_data[\'' . $k . '\'][\'data\']:$__cell_data[\'' . $k . '\'];?>';
                    $html .= '<?php $_REQUEST[\'__page_count\'] = \'' . intval($this->cell_ds[$k]['count']) . '\';?>';
                    $html .= '<?php $_REQUEST[\'__page_max_page\'] = intval($_REQUEST[\'__page_max_page\'])' . ';?>';
                    $html .= '<?php if($__info[\'content\'])
                              { $contentfilearr = hg_split_url($m2o[\'data\'][0][\'content_url\']);' .
                            ' $GLOBALS[\'need_page_info\'] = $need_page_info;' .
                            ' $GLOBALS[\'need_page_info\'][\'page_filename\'] = $contentfilearr[\'file\'];' .
                            ' $GLOBALS[\'need_page_info\'][\'suffix\'] = $contentfilearr[\'suffix\'];' .
                            ' $GLOBALS[\'need_page_info\'][\'need_show_all_pages\'] = \'' . $this->settings['need_show_all_pages'] . '\';' .
                            ' $GLOBALS[\'need_page_info\'][\'page_url\'] = $contentfilearr[\'filedir\']; }
                                else {
                                $_REQUEST[\'__page_total\'] = $__cell_data[\'' . $k . '\'][\'total\'];
                                }
                            ?>';
                }
                else
                {
                    $html = '<?php $m2o[\'data\'] = $__cell_data[\'' . $k . '\'];?>';
                }
            }
            $this->cell_set[$k]['cell'] = $html . $this->cell_set[$k]['cell'];

            //如果是列表，需要判断是否需要生成下一页，需要则插入下一页生成的计划
            if ($v['need_pages'] && $this->page_info['file_mktype'] == 1)
            {
                /**
                if ($v['mode_detail']['mode_type'] == 1)
                {
                    //正文样式分页，取content，特殊处理
                    $this->cell_set[$k]['cell'] .= '<?php if($_REQUEST[\'__next_plan\'])
					{
					$_get_analysis_result[\'new_plan\'] = ' . var_export($this->plan, true) . ';
					$_get_analysis_result[\'new_plan\'][\'rid\'] = intval($_REQUEST[\'rid\']);
                                        $_get_analysis_result[\'new_plan\'][\'offset\'] = intval($_REQUEST[\'pp\'])+' . intval($this->cell_ds[$k]['count']) . ';
					$_get_analysis_result[\'new_plan\'][\'page_num\'] = intval($_REQUEST[\'pp\'])/' . intval($this->cell_ds[$k]['count']) . '+2;
					}?>';
                }
                else
                {
                    $this->cell_set[$k]['cell'] .= '<?php if($_REQUEST[\'__next_plan\'])
					{
					$_get_analysis_result[\'new_plan\'] = ' . var_export($this->plan, true) . ';
                                        $_get_analysis_result[\'new_plan\'][\'rid\'] = intval($_REQUEST[\'rid\']);   
					$_get_analysis_result[\'new_plan\'][\'offset\'] = intval($_REQUEST[\'pp\'])+' . intval($this->cell_ds[$k]['count']) . ';
					$_get_analysis_result[\'new_plan\'][\'page_num\'] = intval($_REQUEST[\'pp\'])/' . intval($this->cell_ds[$k]['count']) . '+2;
					}?>';
                }
                */
                
                $this->cell_set[$k]['cell'] .= '<?php if($_REQUEST[\'__next_plan\'])
					{
					$_get_analysis_result[\'new_plan\'] = ' . var_export($this->plan, true) . ';
                                        $_get_analysis_result[\'new_plan\'][\'rid\'] = intval($_REQUEST[\'rid\']);   
					$_get_analysis_result[\'new_plan\'][\'offset\'] = intval($_REQUEST[\'pp\'])+' . intval($this->cell_ds[$k]['count']) . ';
					$_get_analysis_result[\'new_plan\'][\'page_num\'] = intval($_REQUEST[\'pp\'])/' . intval($this->cell_ds[$k]['count']) . '+2;
					}?>';
            }

            //表示正文，需要加载得到正文信息计算url,并且生成方式为静态
            if ($v['mode_detail']['mode_type'] == 1)
            {
                //静态的
                if ($this->page_info['file_mktype'] == 1 || $this->page_data_id_c)
                {
                    /**
                    $this->cell_set[$k]['cell'] .= '<?php 
                        $m2o_content_detail_codearr = array(
                        \'id\' => $_REQUEST[\'__content_detail\'][\'cid\'],
                        \'title\' => $_REQUEST[\'__content_detail\'][\'title\'],
                        \'keywords\' => $_REQUEST[\'__content_detail\'][\'keywords\'],
                    );
                    echo \'<script type="text/javascript">var m2o_content = \'.json_encode($m2o_content_detail_codearr).\';</script>\';
                    ?>';
                    
                    $this->cell_set[$k]['cell'] .= ' <?php 
					if($m2o[\'data\'][0][\'file_name\'])
					{
						$_get_analysis_result[\'is_contentdetail\'] = 1;
						$_get_analysis_result[\'content_url\'] = $m2o[\'data\'][0][\'content_url\'];
						$_get_analysis_result[\'file_name\'] = $m2o[\'data\'][0][\'file_name\']; 
					}' .
                            '?>';
                     * 
                     */
                }
                //动态的 .php没有实际作用，只是说明是动态正文内容页
                else
                {
                    /**
                    $this->cell_set[$k]['cell'] .= ' <?php 
					if($m2o[\'data\'][0][\'file_name\'])
					{
						$_get_analysis_result[\'is_contentdetail\'] = 1;
						$_get_analysis_result[\'content_url\'] = $m2o[\'data\'][0][\'content_url\'];
						$_get_analysis_result[\'file_name\'] = \'.php\'; 
					}' .
                            '?>';
                     * 
                     * @param type $str
                     * @return type
                     */
                }
            }
        }
    }

    public function mateurl_replace($str)
    {
        $str = str_replace(array('<MATEURL>', '&lt;MATEURL&gt;'), $this->page_info['tem_material_url'] . '/' . $this->settings['template_name'] . '/icon/', $str);
        return $str;
    }

    public function get_md5_filedir($filename)
    {
        if (!$filename)
        {
            $f = 'zz';
        }
        else
        {
            $f = substr(md5($filename), 0, 2);
        }
        return $f;
    }

    public function delete_mkpublish_cache()
    {
        $page_data_id = intval($this->input['page_data_id']);
        if (!$page_data_id)
        {
            $this->errorOutput('NO_PAGE_DATA_ID');
        }
        $sql  = "select * from " . DB_PREFIX . "page_manage where sign='special'";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            deleteDir(CUR_CONF_PATH . 'cache/mkpublish/' . $row['site_id'] . '/' . $row['id'] . '/' . $page_data_id);
        }
        $this->addItem('true');
        $this->output();
    }

}

$out    = new mkpublishApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
