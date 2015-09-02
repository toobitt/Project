<?php
define('MOD_UNIQUEID', 'news');
require('global.php');

class newsApi extends adminReadBase {

    public function __construct()
    {
        $this->mPrmsMethods = array(
            'show'   => '查看',
            'create' => '增加',
            'update' => '修改',
            'delete' => '删除',
            'audit'  => '审核',
            '_node'  => array(
                'name'          => '新闻分类',
                'filename'      => 'news_node.php',
                'node_uniqueid' => 'news_node',
            ),
        );
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/news.class.php');
        $this->obj = new news();
        include_once(ROOT_PATH . 'lib/class/material.class.php');
        include_once(ROOT_PATH . 'lib/class/curl.class.php');
        $this->mater = new material();

    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index()
    {
    }

    public function show()
    {
        $this->verify_content_prms();
        $condition  = $this->get_condition();
        $offset     = $this->input['offset'] ? $this->input['offset'] : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $count;
        if ($this->input['pub_column_id']) {
            $join_pub_column = 1;
        }
        $condition .= " ORDER BY ";
        $condition .= $this->input['orderby_id'] ? 'a.id ASC,' : '';
        //根据时间，order_id 和 istop字段排序，istop字段优先级高 create_time<order_id<istop
        $condition .= "a.istop DESC,a.order_id DESC,a.create_time ";
        //查询排序方式(升序或降序,默认为降序)
        $condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';
        $news = $this->obj->get_news_list($condition . $data_limit, $join_pub_column, $_REQUEST['access_token']);
        if ($news && is_array($news)) {
            foreach ($news as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    public function fetch_one_li()
    {
        $id = intval($this->input['id']);
        if (empty($id)) {
            $this->errorOutput(NOID);
        }
        $data_limit = " AND a.id = " . $id;
        $ret        = $this->obj->get_news_list($data_limit);
        if ($ret && is_array($ret)) {
            foreach ($ret as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 根据条件返回总数
     *
     * @name count
     * @access    public
     * @category  hogesoft
     * @copyright hogesoft
     * @return    $info string 总数，json串
     */
    public function count()
    {
        $condition = $this->get_condition();
        if ($this->input['pub_column_id']) {
            $join_pub_column = 1;
        }
        $info = $this->obj->count($condition, $join_pub_column);
        echo json_encode($info);
    }

    /**
     * 显示单篇文章 文章ID不存在默认为最新第一条
     *
     * @name      detail
     * @access    public
     * @category  hogesoft
     * @copyright hogesoft
     * @param int $id 文章ID
     * @return    $info array 新闻内容
     */
    function detail()
    {

        $id = intval($this->input['id']);
        if ($id) {
            #####
            $this->verify_content_prms(array('_action' => 'show'));
            #####		    
            $data_limit = ' AND a.id=' . $id;
        } else {

            if (! $this->settings['autoSaveDraft']) {
                $this->output();
            }
            //添加文稿时取出该用户最新的一片草稿,draft_id存在时打开指定的草稿
            if ($this->input['draft_id']) {
                if ($this->input['draft_id'] == -1) {
                    $this->output();
                }
                $draft = $this->obj->draft_detail(intval($this->input['draft_id']));
                $this->addItem($draft['content']);
                $this->output();
            } else {
                $draft = $this->obj->get_last_draft($this->user['user_id']);
                $this->addItem($draft['content']);
                $this->output();
            }
            //		    //ID不存在时需要新增空白占位文稿  验证create权限
            //            #####
            //            $this->verify_content_prms(array('_action'=>'create'));
            //            #####
            //            //清理自动草稿
            //            $time= TIMENOW - 1 * 86400;
            //            $sql = "DELETE a, ac FROM ".DB_PREFIX."article a
            //                    LEFT JOIN ".DB_PREFIX."article_contentbody ac
            //                        ON a.id=ac.articleid
            //                    WHERE a.create_time <= " . $time . " AND a.state = -1 ";
            //            $this->db->query($sql);
            //            //清理自动草稿
            //
            //		    //ID不存在时(新增时) 创建一条空白文稿占位(自动草稿)  用于自动保存功能
            //		    $data = array(
            //		      'create_time'   => TIMENOW,
            //		      'state'         => '-1',  //自动草稿
            //		      'user_id'       => $this->user['user_id'],
            //		      'user_name'     => $this->user['user_name'],
            //		    );
            //            $data['id'] = $this->obj->insert_data($data, "article");
            //            $this->obj->update(array('order_id' => $data['id']),"article","id={$data['id']}");
            //            $this->obj->insert_data(array("articleid" => $data['id']), "article_contentbody");
            //            $data['is_first_hand_save'] = 1;  //此字段用于判断是验证create权限还是update权限
            //            $this->addItem($data);
            //			$this->output();
        }
        $info = $this->obj->get_content($data_limit);
        if ($info) {
            $info['create_time_show'] = date("Y-m-d H:i", $info['create_time']);
            $info['update_time_show'] = date("Y-m-d H:i", $info['update_time']);
            $info['pub_time']         = $info['pub_time'] ? date("Y-m-d H:i", $info['pub_time']) : '';
            if ($info['column_id']) {
                $info['column_id'] = unserialize($info['column_id']);
            } else {
                $info['column_id'] = '';
            }
            if ($info['column_url']) {
                $info['column_url'] = unserialize($info['column_url']);
            } else {
                $info['column_url'] = '';
            }
            $info['other_settings'] = $info['other_settings'] ? unserialize($info['other_settings']) : '';
            $pub_column             = array();
            if (is_array($info['column_id'])) {
                $column_id = array();
                foreach ($info['column_id'] as $k => $v) {
                    $column_id[]  = $k;
                    $pub_column[] = array(
                        'column_id'   => $k,
                        'column_name' => $v,
                        'pub_id'      => intval($info['column_url'][$k])
                    );
                }
                $column_id         = implode(',', $column_id);
                $info['column_id'] = $column_id;
            }
            $info['pub_column'] = $pub_column;
            if ($info['outlink'] == '请填写超链接！') {
                $info['outlink'] = '';
            }

            $info['newcontent'] = $info['content'];
            $info['allpages']   = $info['content'];
            if ($info['indexpic']) {
                $info['indexpic_url'] = unserialize($info['pic']);
            }
            $ret          = $this->obj->getMaterialById($info['id']);
            $support_type = $this->mater->get_allow_type();
            if (! empty($ret)) {
                foreach ($ret as $k => $v) {
                    $v['filesize'] = hg_bytes_to_size($v['filesize']);
                    switch ($v['mark']) {
                        case 'img':
                            //将缩略图信息加入info数组
                            $info['material'][$v['id']]             = $v;
                            $info['material'][$v['id']]['path']     = $v['host'] . $v['dir'];
                            $info['material'][$v['id']]['dir']      = $v['filepath'];
                            $info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                            break;
                        case 'doc':
                            $info['material'][$v['id']]             = $v;
                            $info['material'][$v['id']]['path']     = $v['host'] . $v['dir'];
                            $info['material'][$v['id']]['dir']      = $v['filepath'];
                            $info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                            break;
                        case 'real':
                            $info['material'][$v['id']] = $v;
                            break;
                        default:
                            break;
                    }
                    $url                                = $v['host'] . $v['dir'] . $v['filepath'] . $v['filename'];
                    $info['material'][$v['id']]['code'] = str_replace(array('{filename}', '{name}'), array($url, $v['name']), $support_type[$v['mark']][$v['type']]['code']);
                }
            }
            $info['newcontent']     = $info['newcontent'];
            $info['pubstatus']      = $info['state'];
            $info['status']         = $info['state'] ? 2 : 0;
            $info['status_display'] = $info['status'];
            if ($this->input['need_process']) {
                $info['content'] = htmlspecialchars_decode($info['content']);
                $info['content'] = strip_tags($info['content'], '<p><br><a><img><div>');
                $info['content'] = preg_replace('#<p[^>]*>#i', '<p>', $info['content']);
            }
            $info['attach_support'] = implode(',', array_keys((array)$support_type['doc']));
            $info['img_support']    = implode(',', array_keys((array)$support_type['img']));
            $this->addItem($info);
            $this->output();
        } else {
            $this->errorOutput('文章不存在');
        }
    }

    //多id获取news
    public function details()
    {
        $ids = trim($_REQUEST['ids']);
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            if ($id) {
                $data_limit = ' AND a.id=' . $id;
            } else {
                $this->output(NOID);
            }
            $info = $this->obj->get_content($data_limit);
            if ($info) {
                $info['create_time_show'] = date("Y-m-d H:i", $info['create_time']);
                $info['update_time_show'] = date("Y-m-d H:i", $info['update_time']);
                $info['pub_time']         = $info['pub_time'] ? date("Y-m-d H:i", $info['pub_time']) : '';
                $info['column_id']        = unserialize($info['column_id']);
                $info['other_settings']   = $info['other_settings'] ? unserialize($info['other_settings']) : '';
                if (is_array($info['column_id'])) {
                    $column_id = array();
                    foreach ($info['column_id'] as $k => $v) {
                        $column_id[] = $k;
                    }
                    $column_id         = implode(',', $column_id);
                    $info['column_id'] = $column_id;
                }
                if ($info['outlink'] == '请填写超链接！') {
                    $info['outlink'] = '';
                }

                $_content    = $info['content'];
                $_content    = htmlspecialchars_decode($_content);
                $pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r", '<script');
                $pregfind    = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '', '&#60;script');
                $_content    = str_replace($pregfind, $pregreplace, $_content);

                $info['file_info']        = $this->content_material_list('', '', $_content, '', '');
                $info['newcontent'] = $info['content'];
                $info['allpages']   = $info['content'];
                if ($info['indexpic']) {
                    $info['indexpic_url'] = unserialize($info['pic']);
                }
                $ret = $this->obj->getMaterialById($info['id']);
                if (! empty($ret)) {
                    foreach ($ret as $k => $v) {
                        $v['filesize'] = hg_bytes_to_size($v['filesize']);
                        switch ($v['mark']) {
                            case 'img':
                                //将缩略图信息加入info数组
                                $info['material'][$v['id']]             = $v;
                                $info['material'][$v['id']]['path']     = $v['host'] . $v['dir'];
                                $info['material'][$v['id']]['dir']      = $v['filepath'];
                                $info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                                break;
                            case 'doc':
                                $info['material'][$v['id']]             = $v;
                                $info['material'][$v['id']]['path']     = $v['host'] . $v['dir'];
                                $info['material'][$v['id']]['dir']      = $v['filepath'];
                                $info['material'][$v['id']]['filename'] = $v['filename'] . '?' . hg_generate_user_salt(4);
                                break;
                            case 'real':
                                $info['material'][$v['id']] = $v;
                                break;
                            default:
                                break;
                        }
                    }
                }
                $info['newcontent'] = $info['newcontent'];
                $info['pubstatus']  = $info['state'];
                $info['status']     = $info['state'] ? 2 : 0;
                if ($this->input['need_process']) {
                    $info['content'] = htmlspecialchars_decode($info['content']);
                    $info['content'] = strip_tags($info['content'], '<p><br><a><img><div>');
                    $info['content'] = preg_replace('#<p[^>]*>#i', '<p>', $info['content']);
                }
                $support_type           = $this->mater->get_allow_type();
                $info['attach_support'] = implode(',', array_keys($support_type['doc']));
                $info['img_support']    = implode(',', array_keys($support_type['img']));
            }
            $newsinfo[] = $info;
        }
        $this->addItem($newsinfo);
        $this->output();
    }

    public function content_material_list($url, $dir, $content, $need_pages, $need_process)
    {
        $content_material_list = array();
        preg_match_all('/<img[^>]class=[\'|\"]image-refer[\'|\"][^>]src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $mat_r1);
        preg_match_all('/<img[^>]src=[\'|\"](.*?)[\'|\"].*?class=[\'|\"]image-refer[\'|\"].*?[\/]?>/is', $content, $mat_r2);
        $mat_r = $this->arrpreg($mat_r1, $mat_r2);
        if ($mat_r[0] && is_array($mat_r[0])) {
            foreach ($mat_r[0] as $k => $v) {
                if ($mat_r[1][$k]) {
                    $ex_arr    = explode('/', $mat_r[1][$k]);
                    $re_ex_arr = array_reverse($ex_arr);
                    $filename  = $re_ex_arr[0];
                    $module    = $re_ex_arr[1];
                    $app       = $re_ex_arr[2];

                    $filename_arr    = explode('_', $filename);
                    $re_filename_arr = array_reverse($filename_arr);
                    $fileid          = intval($re_filename_arr[0]);
                    unset($re_filename_arr[0]);
                    if (empty($this->settings['App_' . $app]) || ! $re_filename_arr) {
                        continue;
                    }
                    $curl = new curl($this->settings['App_' . $app]['host'], $this->settings['App_' . $app]['dir']);
                    $curl->setSubmitType('post');
                    $curl->setReturnFormat('json');
                    $curl->initPostData();
                    $curl->addRequestData('id', $fileid);
                    $curl->addRequestData('a', 'detail');
                    $result = $curl->request(implode('_', array_reverse($re_filename_arr)) . '.php');
                    if (is_array($result) && $result) {
                        $ret = $this->select_child($app, $result);
                    }
                    $content_material_list[$app . '_' . $fileid] = $ret;
                    $find_arr[]                                  = $v;
                    $replace_arr[]                               = '<m2o_mark style="display:none">' . $app . '_' . $fileid . '</m2o_mark>';
                }
            }
        }
        return $content_material_list;
    }

    public function select_child($app, $result)
    {
        $ret = array();
        switch ($app) {
            case 'tuji':
                foreach ($result as $k => $v) {
                    $row['title']    = $v['title'];
                    $row['brief']    = $v['brief'];
                    $row['keywords'] = $v['keywords'];
                    $row['app']      = 'tuji';
                    if ($v['img_src']) {
                        foreach ($v['img_src'] as $kk => $vv) {
                            $ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $vv, $match);
                            if ($ismatch) {
                                $row['img_src'][$kk]['host']     = $match[1];
                                $row['img_src'][$kk]['dir']      = $match[2];
                                $row['img_src'][$kk]['filepath'] = $match[4];
                                $row['img_src'][$kk]['filename'] = $match[5];
                            }
                        }
                    }
                    if ($v['column_url']) {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr) {
                            foreach ($column_urlarr as $kkk => $vvv) {
                                $row['relation_id'][] = array('column_id' => $kkk, 'id' => $vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'livmedia':
                foreach ($result as $k => $v) {
                    $row['title']      = $v['title'];
                    $row['brief']      = $v['brief'];
                    $row['keywords']   = $v['keywords'];
                    $row['column_url'] = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    //$v['video_filename'] = str_replace('.mp4','.m3u8',$v['video_filename']);
                    $row['video_url'] = rtrim($v['hostwork'],'/').'/'.$v['video_path'].$v['video_filename'];
                    //$row['video_url']            = $v['videoaddr']['default']['m3u8'];
                    //$row['video_url_f4m']        = $v['videoaddr']['default']['f4m'];
                    $row['app']                  = 'livmedia';
                    $row['indexpic']['host']     = $v['img_info']['host'];
                    $row['indexpic']['dir']      = $v['img_info']['dir'];
                    $row['indexpic']['filepath'] = $v['img_info']['filepath'];
                    $row['indexpic']['filename'] = $v['img_info']['filename'];
                    $row['aspect']               = $v['aspect'];
                    $row['is_audio']             = $v['is_audio'];
                    $row['duration']             = $v['duration'];
                    $row['start']                = $v['start'];
                    $row['bitrate']              = $v['bitrate'];
                    if ($v['column_url']) {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr) {
                            foreach ($column_urlarr as $kkk => $vvv) {
                                $row['relation_id'][] = array('column_id' => $kkk, 'id' => $vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'vote':
                foreach ($result as $k => $v) {
                    $row               = $v;
                    $row['column_url'] = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    $row['column_id']  = is_array($v['column_id']) ? $v['column_id'] : unserialize($v['column_id']);
                    if ($v['column_url']) {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr) {
                            foreach ($column_urlarr as $kkk => $vvv) {
                                $row['relation_id'][] = array('column_id' => $kkk, 'id' => $vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
        }

        return $ret;
    }

    function get_allow_type()
    {
        $support_type           = $this->mater->get_allow_type();
        $info                   = array();
        $info['attach_support'] = implode(',', $support_type['doc']);
        $info['img_support']    = implode(',', $support_type['img']);
        $this->addItem($info);
        $this->output();
    }

    function get_keywords()
    {
        if ($content = htmlspecialchars_decode($this->input['content'])) {
            $num    = intval($this->input['num']);
            $result = $this->xs_get_keyword($content, empty($num) ? '' : $num);
            $this->addItem($result);
            $this->output();
        } else {
            $this->addItem(array('errmsg' => '内容为空'));
            $this->output();
        }
    }

    function show_history()
    {
        if ($this->input['id']) {
            $id   = intval($this->input['id']);
            $sql  = "SELECT * FROM " . DB_PREFIX . "article_history WHERE aid=" . $id . " ORDER BY create_time DESC";
            $q    = $this->db->query($sql);
            $info = array();
            while (FALSE !== ($row = $this->db->fetch_array($q))) {
                $row['create_time'] = date('Y-m-d H:i:s', $row['create_time']);
                $info[]             = $row;
            }

            if (! empty($info)) {
                foreach ($info as $k => $v) {
                    $this->addItem($v);
                }
                $this->output();
            }
        }
    }

    function arrpreg($a, $b)
    {
        $arr = array();
        if (is_array($a)) {
            foreach ($a as $k => $r) {
                if (is_array($r)) {
                    foreach ($r as $k1 => $r1) {
                        $arr[$k][$k1] = $r1;
                    }
                }
            }
        }
        if (is_array($b)) {
            foreach ($b as $k => $r) {
                if (is_array($r)) {
                    foreach ($r as $k1 => $r1) {
                        $arr[$k][] = $r1;
                    }
                }
            }
        }

        return $arr;
    }

    function detail_history()
    {
        if (empty($this->input['id'])) {
            $this->errorOutput('未传入版本ID');
        }
        $id  = intval($this->input['id']);
        $sql = "SELECT * FROM " . DB_PREFIX . "article_history WHERE id=" . $id;
        $f   = $this->db->query_first($sql);
        if (empty($f)) {
            return FALSE;
        }
        $info                = unserialize($f['content']);
        $info['history_id']  = $f['id'];
        $info['create_time'] = date('Y-m-d H:i:s', $info['create_time']);

        $info['newcontent'] = $info['content'];

        //分页
        $info['allpages'] = $info['content'];
        if (! empty($info['indexpic'])) {
            //查找索引图
            $info['indexpic_url'] = $this->getThumbById($info['indexpic'], $this->settings['default_index']);
        } else {
            $info['indexpic_url'] = '';
        }
        $ret = $this->getMaterialByMaterialId($info['material_id']);

        if (! empty($ret)) {
            foreach ($ret as $k => $v) {
                //				if(in_array($v['material_id'],$out['id'])) //去除文章中包含的素材图片
                //				{
                //					unset($ret[$k]);
                //					continue;
                //				}
                switch ($v['mark']) {
                    case 'img':
                        //将缩略图信息加入info数组
                        $info['material'][$v['id']]             = $v;
                        $info['material'][$v['id']]['filename'] = $v['filename'];
                        $info['material'][$v['id']]['path']     = $v['host'] . $v['dir'];
                        $info['material'][$v['id']]['dir']      = $v['filepath'];
                        $info['material'][$v['id']]['type']     = $v['type'];

                        $info['material'][$v['id']]['url']       = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], $this->settings['default_index']['label'] . '/');
                        $info['material'][$v['id']]['small_url'] = hg_material_link($v['host'], $v['dir'], $v['filepath'], $v['filename'], $this->settings['default_size']['label'] . '/');
                        $other_img .= '[img id="' . $v['id'] . '" width="' . $v['imgwidth'] . '" height="' . $v['imgheight'] . '"]' . $info['material'][$v['id']]['ori_url'] . '[/img]';
                        break;
                    case 'doc':
                        $info['material'][$v['id']]        = $v;
                        $info['material'][$v['id']]['url'] = MATERIAL_TYPE_THUMB . "doc.png?" . hg_generate_user_salt(5); //返回小图
                        $other_doc .= '[doc id="' . $v['id'] . '" width="' . $v['imgwidth'] . '" height="' . $v['imgheight'] . '"]' . $info['material'][$v['id']]['ori_url'] . '[/doc]';
                        break;
                    case 'real':
                        $info['material'][$v['id']]        = $v;
                        $info['material'][$v['id']]['url'] = MATERIAL_TYPE_THUMB . "real.png?" . hg_generate_user_salt(5); //返回小图
                        $other_real .= '[real id="' . $v['id'] . '" width="' . $v['imgwidth'] . '" height="' . $v['imgheight'] . '"]' . $info['material'][$v['id']]['ori_url'] . '[/real]';
                        break;
                    default:
                        break;
                }
            }
        }
        $info['pubstatus'] = $info['state'];
        $info['status']    = $info['state'] ? 2 : 0;
        $encode            = array('user_name', 'content', 'newcontent', 'allpages');
        foreach ($encode as $v) {
            $info[$v] = rawurlencode($info[$v]);
        }
        $this->addItem($info);
        $this->output();
    }

    public function special()
    {

    }

    public function get_scolumn()
    {
        $id      = $this->input['id'];
        $sql     = "SELECT * FROM " . DB_PREFIX . "article WHERE id=" . $id;
        $f       = $this->db->query_first($sql);
        $colinfo = unserialize($f['special']);

        $col_arr = array();
        if ($colinfo && is_array($colinfo)) {
            foreach ($colinfo as $k => $v) {
                if ($k) {
                    $v['show_name'] = str_replace("&gt;", '>', $v['show_name']);
                    $col_arr[]      = array(
                        'column_id'   => $k,
                        'column_name' => $v['name'],
                        'showName'    => $v['show_name'],
                        'special_id'  => $v['special_id'],
                    );
                }
            }
        }
        $this->addItem($col_arr);
        $this->output();
    }

    /**
     * 检索条件 关键字，时间，状态,标题，发布时间，图片，附件，视频
     *
     * @name get_condition
     * @access    private
     * @category  hogesoft
     * @copyright hogesoft
     */
    private function get_condition()
    {
        $condition = '';
        //搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if (in_array($k, array('_id'))) {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = hg_clean_value($v);
            }
        }
        //搜索标签        
        ####增加权限控制 用于显示####
        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            if (! $this->user['prms']['default_setting']['show_other_data']) {
                $condition .= ' AND user_id = ' . $this->user['user_id'];
            } else {
                //组织以内
                if ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group']) {
                    $condition .= ' AND org_id IN(' . $this->user['slave_org'] . ')';
                }
            }
            if ($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes']) {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if ($authnode_str === '0') {
                    $condition .= ' AND a.sort_id IN(' . $authnode_str . ')';
                }
                if ($authnode_str && $authnode_str != -1) {
                    $authnode_str   = intval($this->input['_id']) ? $authnode_str . ',' . $this->input['_id'] : $authnode_str;
                    $sql            = 'SELECT id,childs FROM ' . DB_PREFIX . 'sort WHERE id IN(' . $authnode_str . ')';
                    $query          = $this->db->query($sql);
                    $authnode_array = array();
                    while ($row = $this->db->fetch_array($query)) {
                        $authnode_array[$row['id']] = explode(',', $row['childs']);
                    }
                    $authnode_str = '';
                    foreach ($authnode_array as $node_id => $n) {
                        if ($node_id == intval($this->input['_id'])) {
                            $node_father_array = $n;
                            if (! in_array(intval($this->input['_id']), $authnode)) {
                                continue;
                            }
                        }
                        $authnode_str .= implode(',', $n) . ',';
                    }
                    $authnode_str = TRUE ? $authnode_str . '0' : trim($authnode_str, ',');
                    if (! $this->input['_id']) {
                        $condition .= ' AND a.sort_id IN(' . $authnode_str . ')';
                    } else {
                        $authnode_array = explode(',', $authnode_str);
                        if (! in_array($this->input['_id'], $authnode_array)) {
                            //
                            if (! $auth_child_node_array = array_intersect($node_father_array, $authnode_array)) {
                                $this->errorOutput(NO_PRIVILEGE);
                            }
                            //$this->errorOutput(var_export($auth_child_node_array,1));
                            $condition .= ' AND a.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
                        }
                    }
                }
            }
        }
        if ($this->input['_id']) {
            $sql = "SELECT childs FROM " . DB_PREFIX . "sort WHERE id = " . intval($this->input['_id']);
            $ret = $this->db->query_first($sql);
            $condition .= " AND  a.sort_id in (" . $ret['childs'] . ")";
        }
        ####增加权限控制 用于显示####
        if ($this->input['max_id'])//自动化任务用到.
        {
            $condition .= " AND a.id >" . intval($this->input['max_id']);
        }
        //查询
        if ($this->input['key']) {
            if (stripos($this->input['key'], '_') !== FALSE) {
                $this->input['key'] = addcslashes($this->input['key'], '_');
            }
            if (stripos($this->input['key'], '%') !== FALSE) {
                $this->input['key'] = addcslashes($this->input['key'], '%');
            }
            if ($this->input['key'] == '#')//纯#号搜索的特殊处理
            {
                $condition .= " AND a.title REGEXP '[^&]#' OR a.title LIKE '#%' ";
            } else {
                $condition .= " AND a.title LIKE '%" . addslashes(trim($this->input['key'])) . "%' ";
            }
        }
        if ($this->input['user_name']) {
            $condition .= " AND a.user_name = '" . trim($this->input['user_name']) . "' ";
        }

        if ($this->input['author']) {
            $condition .= " AND a.author = '" . trim($this->input['author']) . "'";
        }

        //查询分组
        if ($this->input['sort_id'] && $this->input['sort_id'] != -1) {
            $condition .= " AND  a.sort_id = '" . intval($this->input['sort_id']) . "'";
        }

        if ($this->input['para']) {
            $condition .= " AND  a.para = '" . intval($this->input['para']) . "'";
        }


        if ($this->input['start_time'] == $this->input['end_time']) {
            $his = date('His', strtotime($this->input['start_time']));
            if (! intval($his)) {
                $this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])) . ' 00:00';
                $this->input['end_time']   = date('Y-m-d', strtotime($this->input['end_time'])) . ' 23:59';
            }
        }
        //查询创建的起始时间
        if ($this->input['start_time']) {
            $start_time = strtotime($this->input['start_time']);
            $condition .= " AND a.create_time > " . $start_time;
        }

        //查询创建的结束时间
        if ($this->input['end_time']) {
            $end_time = strtotime($this->input['end_time']);
            $condition .= " AND a.create_time < " . $end_time;
            $start_time > $end_time && $this->errorOutput('搜索开始时间不能大于结束时间');
        }

        //查询权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1) {
            $condition .= " AND a.weight >= " . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1) {
            $condition .= " AND a.weight <= " . $this->input['end_weight'];
        }


        if ($this->input['outlink'] == 1) {
            $condition .= " AND a.outlink != '' ";
        }

        if ($this->input['outlink_status']) {
            switch ($this->input['outlink_status']) {
                case 1:
                    $condition .= " AND a.outlink != '' ";
                    break;
                case 2:
                    $condition .= " AND a.outlink = '' ";
                    break;
            }
        }

        //查询发布的时间
        if ($this->input['date_search']) {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('Y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search'])) {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  a.create_time > '" . $yesterday . "' AND a.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  a.create_time > '" . $today . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND  a.create_time > '" . $last_threeday . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  a.create_time > '" . $last_sevenday . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }


        //查询文章的状态
        if (isset($this->input['status'])) {
            switch (intval($this->input['status'])) {
                case 0:
                    $condition .= " ";
                    break;
                case 1: //待审核
                    $condition .= " AND a.state= 0";
                    break;
                case 2://已审核
                    $condition .= " AND a.state = 1";
                    break;
                case 3: //已打回
                    $condition .= " AND a.state = 2";
                default:
                    break;
            }
        }

        //根据是否有图片查询
        if (isset($this->input['is_img'])) {
            switch (intval($this->input['is_img'])) {
                case 1: //不限制
                    $condition .= " ";
                    break;
                case 2: //没有图片
                    $condition .= " AND a.is_img = 0";
                    break;
                case 3: //有图片
                    $condition .= " AND a.is_img = 1";
                    break;
                default:
                    break;
            }
        }

        //根据是否有附件查询

        if (isset($this->input['is_affix'])) {
            switch (intval($this->input['is_affix'])) {
                case 1: //不限制
                    $condition .= " ";
                    break;
                case 2:  //没有附件
                    $condition .= " AND a.is_affix = 0";
                    break;
                case 3: //有附件
                    $condition .= " AND a.is_affix = 1";
                    break;
                default:
                    break;
            }
        }

        //根据是否有视频查询

        if (isset($this->input['is_video'])) {
            switch (intval($this->input['is_video'])) {
                case 1: //不限制
                    $condition .= " ";
                    break;
                case 2:  //没有视频
                    $condition .= " AND a.is_video = 0";
                    break;
                case 3: //有视频
                    $condition .= " AND a.is_video = 1";
                    break;
                default:
                    break;
            }
        }


        //过滤掉自动草稿
        $condition .= " AND a.state != -1";


        //根据发布库栏目搜索
        if ($this->input['pub_column_id']) {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishconfig = new publishconfig();
            $pub_column_id = $publishconfig->get_column_by_ids('id, childs', $this->input['pub_column_id']);
            foreach ((array)$pub_column_id as $k => $v) {
                $column_id[] = $v['childs'];
            }
            $column_id = implode("','", $column_id);
            if ($column_id) {
                $condition .= " AND pc.column_id IN('" . $column_id . "')";
            }
            $condition .= " GROUP BY a.id";
        }

        return $condition;
    }

    /**
     * 获取用户草稿列表
     */
    public function draft_list()
    {
        $sql
             = "SELECT id, title, isauto, create_time
                FROM " . DB_PREFIX . "draft
                WHERE 1 AND user_id = " . $this->user['user_id'] . " ORDER BY create_time DESC LIMIT 0, 100";
        $q   = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetch_array($q)) {
            $row['create_time'] = date('Y-m-d H:i', $row['create_time']);
            $ret[]              = $row;
        }
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 获取草稿详情
     */
    public function draft_detail()
    {
        $id = $this->input['draft_id'];
        if (! $id) {
            $this->output();
        } else {
            $draft = $this->obj->draft_detail($id);
            $this->addItem($draft['content']);
            $this->output();
        }
    }

    //工作量统计接口
    public function statistics()
    {
        $return['static'] = 1;
        $static_date      = $this->input['static_date'];
        if ($static_date) {
            $date = strtotime($static_date);
        } else {
            $date = strtotime(date("Y-m-d 00:00:00", strtotime("-1 day")));
        }
        $sql   = 'select state,user_id,user_name,org_id,expand_id,column_id from ' . DB_PREFIX . 'article where create_time >= ' . $date . ' and create_time < ' . ($date + 86400);
        $query = $this->db->query($sql);
        while ($r = $this->db->fetch_array($query)) {
            $ret[$r['user_id']]['org_id']    = $r['org_id'];
            $ret[$r['user_id']]['user_name'] = $r['user_name'];
            $ret[$r['user_id']]['count']++;
            $r['state'] == 1 ? $ret[$r['user_id']]['statued']++ : FALSE;
            $r['state'] == 2 ? $ret[$r['user_id']]['unstatued']++ : FALSE;
            $r['column_id'] ? $ret[$r['user_id']]['publish']++ : FALSE;
            $r['expand_id'] ? $ret[$r['user_id']]['published']++ : FALSE;
            if ($r['column_id']) {
                $columns = unserialize($r['column_id']);
                if ($columns && is_array($columns)) {
                    foreach ($columns as $column_id => $column_name) {
                        $ret[$r['user_id']]['column'][$column_id]['column_name'] = $column_name;
                        $ret[$r['user_id']]['column'][$column_id]['total']++;
                        if ($r['expand_id']) {
                            $ret[$r['user_id']]['column'][$column_id]['success']++;
                        }
                    }
                }
            }
        }
        $return['data'] = $ret;
        $this->addItem($return);
        $this->output();
    }

    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }
}

$out    = new newsApi();
$action = $_INPUT['a'];
if (! method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();

?>
