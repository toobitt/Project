<?php

require('global.php');
define('MOD_UNIQUEID', 'publishcontent'); //模块标识
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class contentsetApi extends adminBase
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
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $site_id  = '';
        $sitedata = array();
        if ($site_id  = intval($this->input['_id']))
        {
            $sitedata = $this->obj->get_site_by_id($site_id);
        }
        $this->addItem($sitedata);
        $this->output();
    }

    public function create_table()
    {
        $getdata = $this->input['data'];
        $data    = array(
            'bundle_id' => $getdata['bundle_id'], //$data->bundle_id
            'module_id' => $getdata['module_id'], //$data->module_id
            'struct_id' => $getdata['struct_id'], //$data->struct_id
            'struct_ast_id' => $getdata['struct_ast_id'], //$data->struct_ast_id
            'content_type' => $getdata['content_type'], //内容类型：如文章，图片，调查  在部署时会用到，保存到app表中，与应用模块标识对应
            'field' => $getdata['field'], //添加的字段 用逗号隔开
            'field_sql' => $getdata['field_sql'], ////$data->field_sql 添加字段的sql语句
            'table_title' => $getdata['table_title'],
            'child_table' => $getdata['child_table'], //多字表用，号隔开
            'show_field' => serialize($getdata['show_field']),
            'array_field' => $getdata['array_field'],
            'array_child_field' => $getdata['array_child_field'],
        );
        if (!$data['bundle_id'] && !$data['module_id'] && !$data['struct_id'] && !$data['field_sql'])
        {
            $result['msg']   = '相关信息未传入';
            $result['error'] = '1';
            $this->addItem($result);
            $this->output();
        }
        //创建表  如果表已存在  则不创建
        $tablename = get_tablename($data['bundle_id'], $data['module_id'], $data['struct_id'], $data['struct_ast_id']);
        $sql       = "SHOW TABLES LIKE '" . DB_PREFIX . $tablename . "'";
        $table_num = $this->db->fetch_all($sql);
        if (count($table_num) > 0)
        {
            $result['msg']   = '该表已存在';
            $result['error'] = '1';
            $this->addItem($result);
            $this->output();
        }

        $sql = "CREATE TABLE " . DB_PREFIX . $tablename . " (" .
                $data['field_sql'] . " )ENGINE=MYISAM DEFAULT CHARSET=utf8;";
        if ($this->db->query($sql) != 1)
        {
            $result['msg']   = 'create faild';
            $result['error'] = '1';
            $this->addItem($result);
            $this->output();
        }
        //相关字段插入到liv_content_field表中
        unset($data['field_sql']);
        $field_id = $this->obj->insert('content_field', $data);
        if (!$field_id)
        {
            $result['msg']   = '表结构插入失败';
            $result['error'] = '1';
            $this->addItem($result);
            $this->output();
        }

        //插入到app表中
        if (!$data['struct_ast_id'])
        {
            $this->obj->check_app($data['bundle_id'], $data['module_id'], $data['table_title']);
        }

        //创建各自表的对应取内容接口文件
        $this->create_get_file($data['field'], $tablename, $data['child_table'], $data['array_field'], $data['array_child_field']);

        //创建数据源
//		include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
//		$this->pubs = new publishsys();
//		$ret = $this->pubs->addDataSource(array());//数据源参数较多 

        $result['msg'] = 'create success';
        $this->addItem($result);
        $this->output();
    }

    public function create_get_file($field, $tablename, $child_table, $array_field, $array_child_field)
    {
        $dir            = $this->settings['get_content_api_path'];
        $filename       = $tablename . $this->settings['get_content_api_suffix'];
        $filename_class = $tablename . '.class' . $this->settings['get_content_api_suffix'];

        $strings      = file_get_contents(CUR_CONF_PATH . 'admin/get_content_api.php');
        $string_class = file_get_contents(CUR_CONF_PATH . 'admin/get_content_api.class.php');
        $find_arr     = array(
            '{$tablename}',
            '{$child_tablename}',
            '{$child_table}',
            '{$field}',
            '{$array_field}',
            '{$array_child_field}',
        );
        $replace_arr  = array(
            $tablename,
            empty($child_table) ? '' : ($tablename . '_' . $child_table),
            $child_table,
            $field,
            $array_field,
            $array_child_field,
        );
        $strings      = str_replace($find_arr, $replace_arr, $strings);
        $string_class = str_replace($find_arr, $replace_arr, $string_class);

        //生成文件
        if (!file_in($dir, $filename, $strings, true))
        {
            $result['msg']   = '未生成取内容接口文件';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }
        if (!file_in($dir, $filename_class, $string_class, true))
        {
            $result['msg']   = '未生成取内容接口文件';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }
    }

    public function insert_content()
    {
    	
        $data = $this->input['data'];
        
        if (empty($data['column_id']) || empty($data['bundle_id']) || empty($data['module_id']) || empty($data['struct_id']))
        {
            $result['msg']   = '相关信息未传入';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }

        //如果插入的是主内容，则进行内容判断
        if (empty($data['struct_ast_id']))
        {
            $this->insert_main_content($data);
        }
        else
        {
            $this->insert_child_content($data);
        }
    }

    public function insert_main_content($data)
    {
        $data_source         = $data;
        $column_idsarr       = explode(',', $data['column_id']);
        //根据栏目 获取站点id
        include_once(CUR_CONF_PATH . 'lib/column.class.php');
        $column_obj          = new column();
        $column_detail_datas = $column_obj->get_column_by_id(' id,site_id,name,support_client,column_url,folderformat,fileformat,syn_id,parents ', $data['column_id'], 'id');
        $this->obj->update('column', ' id in (' . $data['column_id'] . ')', array('content_update_time' => TIMENOW));

        //对keywords每个字进行转码
        $keywordstr = '';
        if (!empty($data['keywords']))
        {
            $keywordstr = str_utf8_unicode($data['keywords']);
        }

        //对title每个字进行转码
        $titlestr = '';
        if (!empty($data['title']))
        {
            $titlestr = $this->get_titleResult($data['title']);

            $title_pinyin_str = get_spell_title($data['title']);
        }

        foreach ($column_idsarr as $kc => $vc)
        {
            $firstcolumn = array();
            $firstcolumn = $column_detail_datas[$vc];
            if (empty($firstcolumn))
            {
                continue;
            }
            $site_id = $firstcolumn['site_id'];

            if (!$firstcolumn['support_client'])
            {
                continue;
            }

            $client_typearr    = explode(',', $firstcolumn['support_client']);
            $data['site_id']   = $firstcolumn['site_id'];
            $data['column_id'] = $vc;

            //查看内容是否存在，如果在相同模块下内容已存在，则报错，如果内容相同，但栏目不同，则只插入内容关联表中
            $check_result = $this->obj->check_content($data);

            if (!$check_result)
            {
                $content_status[$vc] = 0; //表示这条内容在几个栏目中已发布的
                continue;
            }

            //检测文稿内容正文里有无视频跟图集
            if($data['content'])
            {
                $material_result = $this->obj->check_material_by_content($data['content']);
            }



            if ($check_result != 'new')
            {
                $content_status[$vc]   = 0; //表示这条内容在几个栏目中后面几个个发布的
                //$check_result就是content_id（content表中内容id）
                //插入数据到关联表中
                $content_relation_data = array(
                    'site_id' => $data['site_id'],
                    'bundle_id' => $data['bundle_id'], //$data->bundle_id
                    'module_id' => $data['module_id'], //$data->module_id
                    'struct_id' => $data['struct_id'], //$data->struct_id 'column' => $data['column_id'],
                    'column_id' => $data['column_id'],
                    'column_name' => $firstcolumn['name'],
                    'content_fromid' => $data['content_fromid'],
                    'content_id' => $check_result,
                    'weight' => $data['weight'],
                    'is_have_indexpic' => empty($data['indexpic']) ? 0 : 1,
                    'is_have_video' => empty($data['video']) ? 0 : 1,
                    'keywords_unicode' => addslashes($keywordstr),
                    'title_unicode' => addslashes($titlestr),
                    'title_pinyin' => ($title_pinyin_str),
                    'share_num' => $data['share_num'],
                    'comment_num' => $data['comment_num'],
                    'click_num' => $data['click_num'],
                    'publish_time' => empty($data['publish_time']) ? TIMENOW : $data['publish_time'],
                    'create_time' => empty($data['create_time']) ? TIMENOW : $data['create_time'],
                    'verify_time' => empty($data['verify_time']) ? TIMENOW : $data['verify_time'],
                    'publish_user' => $data['publish_user'],
                    'create_user' => $data['user_name'],
                    'verify_user' => $data['verify_user'],
                    'file_domain' => $data['file_domain'],
                    'file_dir' => $data['file_dir'],
                    'file_custom_filename' => $data['file_custom_filename'],
                    'status' => $this->settings['is_need_audit'] ? 0 : 1,
                    'is_complete' => 0,
                    'praise_count' => $data['praise_count'],
                    'is_have_content_video' => intval($material_result['video']),
                    'is_have_content_tuji' => intval($material_result['tuji']),
                );
                $crd_id                = $this->obj->insert('content_relation', $content_relation_data);
                $this->obj->update_column_content_num($data['column_id']);

                //插入到对应的客户端Liv_content_client表中
                foreach ($client_typearr as $kct => $vct)
                {
                    //对客户端的处理
                    $content_client_data['client_type'] = $vct;
                    $content_client_data['relation_id'] = $crd_id;
                    $client_check_result                = $this->obj->get_content_client('id', $content_client_data);
                    if ($client_check_result)
                    {
                        continue;
                    }
                    else
                    {
                        $this->obj->insert('content_client_relation', $content_client_data);
                    }
                }

                //查询各自内容表的id
                $c_detail  = $this->obj->get_content_by_id(' * ', $check_result);
                $expand_id = $c_detail['expand_id'];

                if ($data['use_maincolumn'])
                {
                    $relate_info = $this->obj->get_relate_by_cc($check_result, $c_detail['column_id']);
                    $file_name   = $relate_info['file_name'];
                }
                else
                {
                    //计算出内容整个目录
                    if ($data['make_content_dir'])
                    {
                        $file_name = make_content_dir($data['publish_id'] ? $data['publish_id'] : $crd_id, $content_relation_data['create_time'], $firstcolumn['folderformat'], $firstcolumn['fileformat'], $data['file_custom_filename']);
                    }
                    else
                    {
                        $file_name = make_content_dir($data['publish_id'] ? $data['publish_id'] : $crd_id, $content_relation_data['create_time'], $firstcolumn['folderformat'], $firstcolumn['fileformat'], $data['custom_filename']) . $this->settings['make_content_file_suffix'];
                    }
                }

                $this->obj->update_content_relation_by_id($crd_id, array(
                    'order_id' => $crd_id,
                    'file_name' => $file_name,
                ));

                if (!$this->settings['is_need_audit'])
                {
                    //整理内容栏目的信息，插入到xunsearch中;先查出现有的content_columns
                    $content_columns                  = $this->obj->get_content_columns(' * ', array('content_id' => $check_result));
                    $column_datas                     = @unserialize($content_columns['column_datas']);
                    $column_datas[$firstcolumn['id']] = array(
                        'id' => $firstcolumn['id'],
                        'column_url' => $firstcolumn['column_url'],
                        'name' => $firstcolumn['name'],
                        'relation_id' => $crd_id,
                        'parents' => $firstcolumn['parents'],
                    );
                    $new_content_columns              = array(
                        'column_ids' => $content_columns['column_ids'] . ',' . $vc,
                        'column_datas' => serialize($column_datas),
                    );
                    $this->obj->update_content_columns($check_result, $new_content_columns);

                    if (!empty($data['video']))
                    {
                        //更新百度视频收录
                        $this->obj->update_video_record($data + array('rid' => $crd_id, 'file_name' => $file_name));
                    }

                    //判断有无推送，有则插入到content_push表中
                    //$this->obj->content_push($data,$check_result,$crd_id,true);
                    //插入到xunsearch中
                    $this->opration_xunsearch($c_detail + $content_relation_data, $new_content_columns, 'update', $column_detail_datas);

                    //判断是否之前有is_complete=1的relation记录，如有，则直接生成，如没有则至is_complete=0并不生成
                    $cr_datas = $this->obj->get_content_relation($check_result, ' AND is_complete=1 AND column_id!=' . $data['column_id']);
                    if ($cr_datas && is_array($cr_datas))
                    {
                        //表示之前有完成的记录
                        $this->obj->update_content_relation_by_id($crd_id, array(
                            'is_complete' => 1,
                        ));
                        //插入到content_publish_time表中
                        if (!$data['use_maincolumn'])
                        {
                            $this->obj->insert('content_publish_time', array('content_id' => $crd_id, 'publish_time' => empty($data['publish_time']) ? TIMENOW : $data['publish_time']));
                        }
                    }
                }
            }
            else
            {
                $content_status[$vc] = 1; //表示这条内容在几个栏目中第一个发布的
                //查询出所需插入的字段
                $tablename           = get_tablename($data['bundle_id'], $data['module_id'], $data['struct_id'], $data['struct_ast_id']);
                $sql                 = "SELECT * FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $data['bundle_id'] . "' AND module_id='" . $data['module_id'] . "' AND struct_id='" . $data['struct_id'] . "' ";
                if (!empty($data['struct_ast_id']))
                {
                    $sql .= " AND struct_ast_id='" . $data['struct_ast_id'] . "'";
                }
                else
                {
                    $sql .= " AND struct_ast_id=''";
                }
                $fields = $this->db->query_first($sql);

                if (empty($fields['field']))
                {
                    $result['msg']   = '数据表中没有相关字段';
                    $result['error'] = '2';
                    $this->addItem($result);
                    $this->output();
                }
                $fieldsarr = explode(',', $fields['field']);
                $sqlstr    = '';
                //插入数据到模块对应表中
                //					unset($data['id']);
                foreach ($data as $k => $v)
                {
                    if (in_array($k, $fieldsarr))
                    {
                        //如果值是数组，串行化存储
                        $v = is_array($v) ? serialize($v) : $v;
                        $sqlstr .= $k . "='" . $v . "',";
                    }
                }
                $sqlstr = trim($sqlstr, ',');
                if (empty($sqlstr))
                {
                    $result['msg']   = '数据插入到模块表失败，缺少相关参数';
                    $result['error'] = '2';
                    $this->addItem($result);
                    $this->output();
                }
                $expand_id = $this->obj->insert($tablename, $sqlstr);
                if (!$expand_id)
                {
                    $result['msg']   = '数据插入到模块表失败';
                    $result['error'] = '2';
                    $this->addItem($result);
                    $this->output();
                }
                
                $content_data = array(
                    'site_id' => $data['site_id'],
                    'column_id' => $data['column_id'],
                    'bundle_id' => $data['bundle_id'], //$data->bundle_id
                    'module_id' => $data['module_id'], //$data->module_id
                    'struct_id' => $data['struct_id'], //$data->struct_id 'column' => $data['column_id'],
                    'expand_id' => $expand_id,
                    'plan_set_id' => $data['plan_set_id'],
                    'content_fromid' => $data['content_fromid'],
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'],
                    'brief' => $data['brief'],
                    'keywords' => $data['keywords'],
                    'indexpic' => is_array($data['indexpic']) ? serialize($data['indexpic']) : $data['indexpic'],
                    'video' => is_array($data['video']) ? serialize($data['video']) : $data['video'],
                    'outlink' => $data['outlink'], //trim($firstsite['weburl'],'/').'/content/'.$data['struct_id'].'_'
                    'child_num' => $data['child_num'],
                    'source' => $data['source'],
                    'appid' => $data['appid'],
                    'appname' => $data['appname'],
                    'ip' => $data['ip'],
                    'publish_user' => $data['publish_user'],
                    'create_user' => $data['user_name'],
                    'verify_user' => $data['verify_user'],
                    'template_sign' => $data['template_sign'],
                    'catalog' => $data['catalog'],
                    'tcolor' => $data['tcolor'],
                    'isbold' => $data['isbold'],
                    'isitalic' => $data['isitalic'],
                    'use_maincolumn' => $data['use_maincolumn'] ? 1 : 0,
                    'author' => $data['author'],
                    'iscomment' => $data['iscomment'] ? 1 : 0,
                	'is_praise' => $data['is_praise'] ? 1 : 0,
                );
                $content_id   = $this->obj->insert('content', $content_data);

                if (!$content_id)
                {
                    $result['msg']   = '数据插入到模块表失败';
                    $result['error'] = '2';
                    $this->addItem($result);
                    $this->output();
                }

                //插入数据到关联表中
                $content_relation_data = array(
                    'site_id' => $data['site_id'],
                    'bundle_id' => $data['bundle_id'], //$data->bundle_id
                    'module_id' => $data['module_id'], //$data->module_id
                    'struct_id' => $data['struct_id'], //$data->struct_id 'column' => $data['column_id'],
                    'column_id' => $data['column_id'],
                    'column_name' => $firstcolumn['name'],
                    'content_fromid' => $data['content_fromid'],
                    'content_id' => $content_id,
                    'weight' => $data['weight'],
                    'is_have_indexpic' => empty($data['indexpic']) ? 0 : 1,
                    'is_have_video' => empty($data['video']) ? 0 : 1,
                    'keywords_unicode' => addslashes($keywordstr),
                    'title_unicode' => addslashes($titlestr),
                    'title_pinyin' => ($title_pinyin_str),
                    'share_num' => $data['share_num'],
                    'comment_num' => $data['comment_num'],
                    'click_num' => $data['click_num'],
                    'publish_time' => empty($data['publish_time']) ? TIMENOW : $data['publish_time'],
                    'create_time' => empty($data['create_time']) ? TIMENOW : $data['create_time'],
                    'verify_time' => empty($data['verify_time']) ? TIMENOW : $data['verify_time'],
                    'publish_user' => $data['publish_user'],
                    'create_user' => $data['user_name'],
                    'verify_user' => $data['verify_user'],
                    'file_domain' => $data['file_domain'],
                    'file_dir' => $data['file_dir'],
                    'file_custom_filename' => $data['file_custom_filename'],
                    'status' => $this->settings['is_need_audit'] ? 0 : 1,
                    'is_complete' => $data['is_complete'] ? 1 : 0,
                    'praise_count' => $data['praise_count'],
                    'is_have_content_video' => intval($material_result['video']),
                    'is_have_content_tuji' => intval($material_result['tuji']),
                );
                /**
                  if($data['publish_id'])
                  {
                  $content_relation_data['id'] = $data['publish_id'];
                  }
                 */
                $crd_id                = $this->obj->insert('content_relation', $content_relation_data);
                if (!$crd_id)
                {
                    $result['msg']   = '数据插入到关联表失败';
                    $result['error'] = '2';
                    $this->addItem($result);
                    $this->output();
                }
                $this->obj->update_column_content_num($data['column_id']);

                //生成内容url
                $this->obj->update_content_relation_by_id($crd_id, array(
                    'order_id' => $crd_id,
                    'file_name' => $data['file_custom_filename'] ? (make_content_dir($data['publish_id'] ? $data['publish_id'] : $crd_id, $content_relation_data['create_time'], $firstcolumn['folderformat'], $firstcolumn['fileformat'], $data['file_custom_filename'])) : make_content_dir($data['publish_id'] ? $data['publish_id'] : $crd_id, $content_relation_data['create_time'], $firstcolumn['folderformat'], $firstcolumn['fileformat'], $data['custom_filename']) . $this->settings['make_content_file_suffix'],
                ));

                //插入到对应的客户端Liv_content_client表中
                $content_client_data['relation_id'] = $crd_id;
                foreach ($client_typearr as $kct => $vct)
                {
                    //对客户端的处理
                    $content_client_data['client_type'] = $vct;
                    $check_result                       = $this->obj->get_content_client('id', $content_client_data);
                    if ($check_result)
                    {
                        continue;
                    }
                    else
                    {
                        $this->obj->insert('content_client_relation', $content_client_data);
                    }
                }

                if (!$this->settings['is_need_audit'])
                {
                    //插入到Liv_content_columns,栏目的相关信息
                    $column_datas       = array(
                        $data['column_id'] => array(
                            'id' => $data['column_id'],
                            'column_url' => $firstcolumn['column_url'],
                            'name' => $firstcolumn['name'],
                            'relation_id' => $crd_id,
                            'parents' => $firstcolumn['parents'],
                    ));
                    $content_columns    = array(
                        'content_id' => $content_id,
                        'column_ids' => $data['column_id'],
                        'column_datas' => serialize($column_datas),
                    );
                    $content_columns_id = $this->obj->insert('content_columns', $content_columns);
                    if (!empty($data['video']))
                    {
                        //更新百度视频收录
                        $this->obj->update_video_record($data + array('rid' => $crd_id));
                    }

                    //判断有无推送，有则插入到content_push表中
                    $this->obj->content_push($data, $content_id, $crd_id, false);

                    if ($data['is_complete'])
                    {
                        //插入到content_publish_time表中
                        $this->obj->insert('content_publish_time', array('content_id' => $crd_id, 'publish_time' => $content_relation_data['publish_time']));
                    }

                    //插入到xunsearch中
                    $content_data['content_id'] = $content_id;
                    $this->opration_xunsearch($content_data + $content_relation_data, $content_columns, 'add', $column_detail_datas);
                }
            }

            //对视频跟电视剧处理
            if ($data['bundle_id'] == 'livmedia' && $data['app_uniqueid'] == 'tv_play')
            {
                $content_detail = $this->obj->get_all_content_by_relationid($crd_id, true);
                if ($content_detail && $content_detail['content_fromid'])
                {
                    include_once(ROOT_PATH . 'lib/class/tv_play.class.php');
                    $tv_playobj = new tv_play();
                    $tv_playobj->update_tv($content_detail['content_fromid'], $content_detail['content_url']);
                }
            }

            $content_rid[$vc] = $crd_id;
        }
        if ($expand_id)
        {
            //清除memcache缓存
            $this->memcache_flush(APP_UNIQUEID);

            $result['msg']            = 'ok';
            $result['expand_id']      = $expand_id;
            $result['content_rid']    = $content_rid;
            $result['content_status'] = $content_status;
        }
        else
        {
            $result['msg']   = '同一内容，同一栏目，同一客户端内容重复';
            $result['error'] = '3';
        }

        $this->addItem($result);
        $this->output();
    }

    public function insert_child_content($data)
    {
        //查询出所需插入的字段
        $tablename = get_tablename($data['bundle_id'], $data['module_id'], $data['struct_id'], $data['struct_ast_id']);
        $sql       = "SELECT * FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $data['bundle_id'] . "' AND module_id='" . $data['module_id'] . "' AND struct_id='" . $data['struct_id'] . "' ";
        if (!empty($data['struct_ast_id']))
        {
            $sql .= " AND struct_ast_id='" . $data['struct_ast_id'] . "'";
        }
        $fields = $this->db->query_first($sql);

        if (empty($fields['field']))
        {
            $result['msg']   = '数据表中没有相关字段';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }
        $fieldsarr = explode(',', $fields['field']);
        $sqlstr    = '';
        //插入数据到模块对应表中
        /** id不用判断，传时注意,当传数据来，根据自己表的主键判断，unset主键 */
        foreach ($data as $k => $v)
        {
            if (in_array($k, $fieldsarr) && $k != 'id')
            {
                //如果值是数组，串行化存储
                $v = is_array($v) ? serialize($v) : $v;
                $sqlstr .= $k . "='" . $v . "',";
            }
        }
        $sqlstr = trim($sqlstr, ',');
        if (empty($sqlstr))
        {
            $result['msg']   = '数据插入到模块表失败，缺少相关参数';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }
        $expand_id = $this->obj->insert($tablename, $sqlstr);

        //删除缓存（以防父级有缓存）
        if ($data['expand_id'])
        {
            include(CUR_CONF_PATH . 'lib/cache.class.php');
            $this->cache = new Cache();
            $this->cache->initialize(CUR_CONF_PATH . 'cache/' . DB_PREFIX . $tablename . '/');
            $this->cache->delete($data['expand_id']);
        }

        if (!$expand_id)
        {
            $result['msg']   = '数据插入到模块表失败';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }

        $result['msg']       = 'ok';
        $result['expand_id'] = $expand_id;
        $this->addItem($result);
        $this->output();
    }

    function xs_clean()
    {
        $this->xs_index('', 'search_config_publish_content', 'clean');
        exit;
    }

    public function opration_xunsearch($data, $content_columns, $opration, $column_detail_datas = array())
    {
        $tag = $column_idstr = '';
        if(is_array($column_datas = unserialize($content_columns['column_datas'])))
        {
            foreach($column_datas as $k=>$v)
            {
                $column_idstr .= $tag.$v['parents'];
                $tag=',';
            }
            $column_idstr = "'".implode("','",array_unique(explode(',',$column_idstr)))."'";
        }
        $xundata = array(
            'id' => empty($data['content_id']) ? $data['id'] : $data['content_id'],
            'title' => $data['title'],
            //'title_pinyin' => $data['title_pinyin'],
            'subtitle' => $data['subtitle'],
            'content' => $data['content'],
            'bundle_id' => $data['bundle_id'],
            'module_id' => $data['module_id'],
            'struct_id' => $data['struct_id'],
            'site_id' => $data['site_id'],
            'column_name' => $content_columns['column_ids'] ? $column_detail_datas[intval($content_columns['column_ids'])]['name'] : '', //用作分类统计搜索
            'column_ids' => $column_idstr,
            'column_datas' => $content_columns['column_datas'],
            'expand_id' => $data['expand_id'],
            'content_fromid' => $data['content_fromid'],
            'is_have_indexpic' => $data['is_have_indexpic'],
            'is_have_video' => $data['is_have_video'],
            'share_num' => $data['share_num'],
            'comment_num' => $data['comment_num'],
            'click_num' => $data['click_num'],
            'publish_time' => $data['publish_time'],
            'create_time' => $data['create_time'],
            'verify_time' => $data['verify_time'],
            'publish_user' => $data['publish_user'],
            'create_user' => $data['create_user'],
            'verify_user' => $data['verify_user'],
            'outlink' => $data['outlink'],
            'ip' => $data['ip'],
            'video' => $data['video'],
            'indexpic' => $data['indexpic'],
            'brief' => $data['brief'],
            'keywords' => $data['keywords'],
        );
        $this->xs_index($xundata, 'search_config_publish_content', $opration);
    }

    /**
     * is_site有值表示column_id记录的是站点id
     * is_site没有值表示column_id记录的是栏目id
     * expand_module表示有支持的模块选择
     * */
    public function get_content_type_by_colid()
    {
        $column_id     = intval($this->input['column_id']);
        $is_site       = intval($this->input['is_site']);
        $expand_module = $this->input['expand_module'];
        if (!$column_id)
        {
            $result['msg']   = '获取内容类型失败，未得到栏目';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }

        $cont_type = array();
        if ($is_site)
        {
            $coldata = $this->pub_config->get_site_first($field   = ' * ', $column_id);
        }
        else
        {
            $coldata = $this->pub_config->get_column_first($field   = ' * ', $column_id);
        }

        if (empty($coldata))
        {
            $result['msg']   = '获取内容类型失败，未取到栏目信息';
            $result['error'] = '2';
            $this->addItem($result);
            $this->output();
        }

        if ($coldata['support_module'])
        {
            $supp_modu = $this->get_app($coldata['support_module']);
            foreach ($supp_modu as $k => $v)
            {
                if ($expand_module)
                {
                    $content_type = $this->get_content_type($v['abundle'], $v['mbundle'], $coldata['support_content_type'], true);
                }
                else
                {
                    $content_type = $this->get_content_type($v['abundle'], $v['mbundle']);
                }
                $cont_type = array_merge($cont_type, $content_type);
            }
        }
        $this->addItem($cont_type);
        $this->output();
    }

    //获取内容的类型 ，如：文章，图片，调查
    public function get_content_type($bundle_id = '', $module_id = '', $support_content = '', $is_sup = false)
    {
        $sql = "SELECT id,struct_id,content_type,bundle_id FROM " . DB_PREFIX . "content_field WHERE 1 AND content_type!='' AND struct_ast_id=''";
        if ($bundle_id)
        {
            $sql .= " AND bundle_id='" . $bundle_id . "' ";
        }
        if ($module_id)
        {
            $sql .= " AND module_id='" . $module_id . "' ";
        }
        if ($is_sup)
        {
            $support_content = $support_content ? $support_content : 0;
            $sql .= " AND id in(" . $support_content . ")";
        }
        $info = $this->db->fetch_all($sql, 'id');
        return $info;
    }

    public function get_all_content_type()
    {
        $content_type = $this->get_content_type();
        $this->addItem($content_type);
        $this->output();
    }

    public function check_mk_publish_content()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "content_publish_time ORDER BY id LIMIT 1";
        $data = $this->db->query_first($sql);
        if (empty($data))
        {
            echo "没有可执行计划";
            exit;
        }
        $sql = "DELETE FROM " . DB_PREFIX . "content_publish_time WHERE id=" . $data['id'];
        $this->db->query($sql);
        if ($this->settings['App_mk_publish_content'])
        {
            $this->curl = new curl($this->settings['App_mk_publish_content']['host'], $this->settings['App_mk_publish_content']['dir']);
            $this->curl->setSubmitType('post');
            $this->curl->setReturnFormat('json');
            $this->curl->initPostData();
            $this->curl->addRequestData('html', true);
            $this->curl->addRequestData('cmid', $data['content_id']);
            $this->curl->request('mk_publish_content.php');
        }

        if ($this->settings['App_mkpublish'])
        {
            $content_data = $this->obj->get_all_content_by_relationid($data['content_id']);
            $sql          = "SELECT id FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $content_data['bundle_id'] . "' AND module_id='" . $content_data['module_id'] . "' AND content_type!='' AND struct_ast_id='' ORDER  BY id";
            $content_type = $this->db->query_first($sql);
            //取栏目的page_id

            include_once(ROOT_PATH . 'lib/class/publishsys.class.php');
            $this->pub_sys = new publishsys();
            $page_data     = $this->pub_sys->get_page_by_sign();
            if (!$page_data['id'])
            {
                exit;
            }
            include_once(ROOT_PATH . 'lib/class/mkpublish.class.php');
            $this->mk = new mkpublish();
            if ($content_data['template_sign'])
            {
                $plan['site_id']       = $content_data['site_id'];
                $plan['page_id']       = $page_data['id'];
                $plan['page_data_id']  = $content_data['column_id'];
                $plan['content_type']  = intval($content_type['id']);
                $plan['template_sign'] = $content_data['template_sign'];
                $plan['rid']           = $content_data['id'];
            }
            $this->mk->mk_publish($plan);
        }
        //请求访问统计，更改对应已被删除内容
        if ($data['cid'])
        {
            @include(ROOT_PATH . 'lib/class/access.class.php');
            if (class_exists('access'))
            {
                $access = new access();
                $access->delete($data['cid']);
            }
        }
    }

    //根据id获取应用模块
    public function get_app($ids)
    {
        $sql  = "SELECT a1.id as id,a1.name as mname,a1.bundle as mbundle,a2.name as aname,a2.bundle as abundle FROM " . DB_PREFIX . "app a1 LEFT JOIN " . DB_PREFIX . "app a2 ON a1.father=a2.id WHERE a1.id in (" . $ids . ")";
        $info = $this->db->fetch_all($sql);
        return $info;
    }

    public function get_all_app()
    {
        $sql  = "SELECT * FROM " . DB_PREFIX . "app WHERE father=0";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret                 = array();
            $ret[$row['bundle']] = $row['name'];
            $this->addItem($ret);
        }
        $this->output();
    }

    public function get_app_module()
    {
        $ret_app    = $ret_module = array();
        $sql        = "SELECT a1.father,a1.bundle,a1.name FROM " . DB_PREFIX . "app a1 LEFT JOIN " . DB_PREFIX . "app a2 ON a1.father=a2.id WHERE a2.father=0";
        $info       = $this->db->query($sql);
        while ($row        = $this->db->fetch_array($info))
        {
            $ret_module[$row['father']] = array('bundle' => $row['bundle'], 'name' => $row['name']);
        }

        $sql  = "SELECT id,bundle,name FROM " . DB_PREFIX . "app WHERE father=0";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret_app[$row['id']] = array('bundle' => $row['bundle'], 'name' => $row['name']);
        }
        $result['app']    = $ret_app;
        $result['module'] = $ret_module;
        $this->addItem($result);
        $this->output();
    }

    public function content_field_by_ids()
    {
        $ids = $this->input['id'];
        $ret = array();
        if (!$ids)
        {
            $this->addItem('error');
            $this->output();
        }
        $sql  = "SELECT id,bundle_id,module_id,struct_id,content_type FROM " . DB_PREFIX . "content_field WHERE id in (" . $ids . ") ORDER BY id";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $ret[$row['id']] = $row;
        }
        $this->addItem($ret);
        $this->output();
    }

    public function video_record()
    {
        $site = $this->input['site'];
        if (!$site || !is_array($site))
        {
            $this->errorOutput('NO_SITE_IDS');
        }

        $video_record = $content_id   = $content_data = array();

        foreach ($site as $k => $v)
        {
            $sql  = "SELECT * FROM " . DB_PREFIX . "content_video_record WHERE site_id=" . $v['site_id'] . " ORDER BY id DESC LIMIT " . $v['video_record_count'];
            $info = $this->db->query($sql);
            while ($row  = $this->db->fetch_array($info))
            {
                $video_record[$row['site_id']][]   = $row;
                $content_data_detail               = $row['content_data'] ? unserialize($row['content_data']) : array();
                $content_data_detail['site_id']    = $row['site_id'];
                $content_data_detail['column_id']  = $row['column_id'];
                $content_data_detail['struct_id']  = $row['struct_id'];
                $content_data_detail['id']         = $row['relation_id'];
                $content_data_detail['op']         = $row['opration'];
                $content_data[$row['relation_id']] = $content_data_detail;
                $column_ids[$row['column_id']]     = $row['column_id'];
                $site_ids[$row['site_id']]         = $row['site_id'];
            }
        }

        if ($content_data)
        {
            include_once(CUR_CONF_PATH . 'lib/column.class.php');
            $column_obj = new column();
            if ($column_ids)
            {
                $column_datas = $column_obj->get_column_by_id(' id,name,childdomain,column_dir,site_id,relate_dir,father_domain ', implode(',', $column_ids), 'id');
            }
            if ($site_ids)
            {
                $site_datas = $column_obj->get_site(' id,site_name,weburl,sub_weburl ', ' AND id in(' . implode(',', $site_ids) . ')', 'id');
            }
            foreach ($content_data as $k => $v)
            {
                $content_data[$k]['content_url'] = mk_content_url($site_datas[$v['site_id']], $column_datas[$v['column_id']], $v);
            }
        }

        $result['video_record'] = $video_record;
        $result['content_data'] = $content_data;

        $this->addItem($result);
        $this->output();
    }

    //将子级插入五条到主内容表中
    public function insert_childs_to_content()
    {
        $bundle_id      = $this->input['bundle_id'];
        $module_id      = $this->input['module_id'];
        $struct_id      = $this->input['struct_id'];
        $struct_ast_id  = $this->input['struct_ast_id'];
        $content_rid    = intval($this->input['content_rid']);
        $content_fromid = intval($this->input['from_id']);
        if (!$bundle_id || !$module_id || !$struct_id || !$struct_ast_id)
        {
            $this->errorOutput('NO_PARAM');
        }
        $tablename = $bundle_id . '_' . $module_id . '_' . $struct_id . '_' . $struct_ast_id;
        if ($content_rid)
        {
            $content = $this->obj->get_all_content_by_relationid($content_rid);
        }
        else
        {
            if (!$content_fromid)
            {
                $this->errorOutput('NO_ID');
            }
            $sql           = "SELECT id,expand_id FROM " . DB_PREFIX . $tablename . " WHERE content_fromid=" . $content_fromid;
            $child_content = $this->db->query_first($sql);
            if (!$child_content)
            {
                $this->errorOutput('NO_CHILD_INFO');
            }
            $sql           = "DESCRIBE " . DB_PREFIX . $tablename . " order_id ";
            $child_content = $this->db->query_first($sql);
            if ($child_content)
            {
                $order_str = ' ORDER BY order_id DESC ';
            }
            $sql            = "SELECT id,expand_id,content_fromid FROM " . DB_PREFIX . "content WHERE bundle_id='" . $bundle_id . "' AND expand_id=" . $child_content['expand_id'] . $order_str;
            $content        = $this->db->query_first($sql);
            $content['cid'] = $content['id'];
            if (!$content)
            {
                $this->errorOutput('NO_CONTENT');
            }
        }

        if (empty($content))
        {
            $this->errorOutput('NO_DATA');
        }

        $sql  = "SELECT * FROM " . DB_PREFIX . $tablename . " WHERE  expand_id=" . $content['expand_id'] . " ORDER BY id LIMIT 5";
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
            $this->obj->update('content', ' id=' . $content['cid'], array('childs_data' => serialize($pics)));
        }
    }

    public function get_content_type_by_app()
    {
        $bundle_id    = $this->input['bundle_id'];
        $module_id    = $this->input['module_id'];
        $sql          = "SELECT * FROM " . DB_PREFIX . "content_field WHERE bundle_id='" . $bundle_id . "' AND module_id='" . $module_id . "' AND content_type!='' AND struct_ast_id='' ORDER  BY id";
        $content_type = $this->db->query_first($sql);

        $this->addItem($content_type);
        $this->output();
    }

    //更新tuji200条自己内容child_datas
    public function update_childs_data()
    {
        $sql  = "select id from " . DB_PREFIX . "content_relation where bundle_id='tuji' order by id desc limit 200";
        $info = $this->db->query($sql);
        while ($row  = $this->db->fetch_array($info))
        {
            $this->input['bundle_id']     = 'tuji';
            $this->input['module_id']     = 'tuji';
            $this->input['struct_id']     = 'tuji';
            $this->input['struct_ast_id'] = 'tuji_pics';
            $this->input['content_rid']   = $row['id'];
            $this->insert_childs_to_content();
        }
    }

    /**
     * 空方法
     * @name unknow
     * @access public
     * @author repheal
     * @category hogesoft
     * @copyright hogesoft
     */
    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new contentsetApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'check_mk_publish_content';
}
$out->$action();
?>
