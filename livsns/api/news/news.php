<?php
require('global.php');
define('MOD_UNIQUEID', 'news');

class newsApi extends adminBase {

    function __construct()
    {
        parent::__construct();
        include(CUR_CONF_PATH . 'lib/news.class.php');
        $this->obj = new news();
        include_once(ROOT_PATH . 'lib/class/material.class.php');
        $this->mater = new material();
    }

    function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $limit   = 'limit 0, 20';
        $orderby = ' ORDER BY create_time DESC ';
        $sql     = 'SELECT id,title,indexpic,create_time FROM ' . DB_PREFIX . 'article ' . $orderby . $limit;
        $query   = $this->db->query($sql);
        while ($row = $this->db->fetch_array($query)) {
            //$row['indexpic'] = $row['indexpic'] ? unserialize($row['indexpic']) : array();
            $this->addItem($row);
        }
        $this->output();
    }

    public function get_news_list()
    {

        $condition  = $this->get_condition();
        $offset     = $this->input['offset'] ? $this->input['offset'] : 0;
        $count      = $this->input['count'] ? intval($this->input['count']) : 20;
        $data_limit = ' LIMIT ' . $offset . ' , ' . $count;
        $news       = $this->obj->get_news_list($condition . $data_limit, $access_token = $_REQUEST['access_token']);
        $this->addItem($news);
        $this->output();
    }

    public function count()
    {
        $condition = $this->get_condition();
        $sql       = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'article a  WHERE 1' . $condition;

        exit(json_encode($this->db->query_first($sql)));
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
            $data_limit = ' AND a.id=' . $id;
        } else {
            $this->output();
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
            $this->addItem($info);
            $this->output();
        } else {
            $this->errorOutput('文章不存在');
        }
    }

    public function details()
    {
        $ids = trim($this->input['ids']);
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
            file_put_contents('2.txt',var_export($newsinfo,1));
        }
        $this->addItem($newsinfo);
        $this->output();
    }

    public function get_condition()
    {
        $condition = '';

        //查询app
        if ($this->input['key']) {
            $condition .= " AND a.title LIKE '%" . trim(urldecode($this->input['key'])) . "%' ";
        }

        //应用查询
        if ($this->input['app_uniqueid']) {
            $condition .= " AND  a.app = '" . urldecode($this->input['app_uniqueid']) . "'";
        }

        if ($this->input['user_name']) {
            $condition .= " AND a.user_name LIKE '%" . trim($this->input['user_name']) . "%' ";
        }

        //查询分组
        if ($this->input['sort_id'] && $this->input['sort_id'] != -1) {
            $condition .= " AND  a.sort_id in '(" . $this->input['sort_id'] . ")'";
        }

        //查询创建的起始时间
        if ($this->input['start_time']) {
            $condition .= " AND a.create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if ($this->input['end_time']) {
            $condition .= " AND a.create_time < " . strtotime($this->input['end_time']);
        }

        //查询权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1) {
            $condition .= " AND a.weight >= " . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1) {
            $condition .= " AND a.weight <= " . $this->input['end_weight'];
        }

        if ($this->input['para']) {
            $condition .= " AND a.para = " . $this->input['para'];
        }

        if ($this->input['outlink'] == 1) {
            $condition .= " AND a.outlink != '' ";
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
        if (isset($this->input['article_status'])) {
            switch (intval($this->input['article_status'])) {
                case 1:
                    $condition .= " ";
                    break;
                case 2: //待审核
                    $condition .= " AND a.state= 0";
                    break;
                case 3://已审核
                    $condition .= " AND a.state = 1";
                    break;
                case 4: //已打回
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
        if (! $this->input['flag']) {
            //根据时间，order_id 和 istop字段排序，istop字段优先级高 create_time<order_id<istop
            $condition .= " ORDER BY a.istop DESC,a.order_id DESC ,a.create_time  ";
            //查询排序方式(升序或降序,默认为降序)
            $condition .= $this->input['descasc'] ? (' ' . $this->input['descasc']) : ' DESC ';
        }

        return $condition;
    }

    function unknow()
    {
        $this->errorOutput('此方法不存在');
    }
}

$out    = new newsApi();
$action = $_INPUT['a'];
if (! method_exists($out, $action)) {
    $action = 'unknow';
}
$out->$action();
?>