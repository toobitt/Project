<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 * $Id:$
 ***************************************************************************/
define('MOD_UNIQUEID', 'tuji');
require_once('./global.php');
require_once('../core/tuji.dat.php');
require_once(ROOT_PATH . 'lib/class/gdimage.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/outpush.class.php');
class tuji extends adminReadBase {

    private $gd;
    private $outpush;

    public function __construct()
    {
        $this->mPrmsMethods = array(
            'show'        => '查看',
            'create_tuji' => '增加',
            'update_tuji' => '修改',
            'delete'      => '删除',
            'audit'       => '审核',
            '_node'       => array(
                'name'          => '图集分类',
                'filename'      => 'tuji_node.php',
                'node_uniqueid' => 'tuji_node',
            ),
        );
        parent::__construct();
        $this->tuji     = new tuji_data();
        $this->gd       = new GDImage();
        $this->material = new material();
        $this->outpush  = new outpush();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index()
    {
    }

    private function get_cover($id)
    {
        if (! $id) {
            return;
        }
        $sql = 'SELECT cover_url FROM ' . DB_PREFIX . 'tuji WHERE id=' . intval($id);
        $r   = $this->db->query_first($sql);
        if ($r['cover_url']) {
            return TRUE;
        }

        return FALSE;
    }

    public function show()
    {
        $this->verify_content_prms();
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count  = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $this->setXmlNode('tuji', 'item');

        $condition = $this->get_condition();

        $orderby = ' ORDER BY ';
        $orderby .= $this->input['orderby_id'] ? 't.id ASC,' : '';
        $orderby .= 't.order_id DESC ';

        //根据发布库栏目搜索
        if ($this->input['pub_column_id']) {
            $condition .= " GROUP BY t.id";
            $tuji_info = $this->tuji->tuji_info($condition, $orderby, $offset, $count, 1);
        } else {
            $tuji_info = $this->tuji->tuji_info($condition, $orderby, $offset, $count);
        }
        foreach ($tuji_info as $k => $v) {
            $v['pub']     = unserialize($v['column_id']);
            $v['pub_url'] = unserialize($v['column_url']);
            $pub_column   = array();
            if ($v['pub']) {
                foreach ($v['pub'] as $kk => $vv) {
                    $pub_column[] = array(
                        'column_id'   => $kk,
                        'column_name' => $vv,
                        'pub_id'      => intval($v['pub_url'][$kk])
                    );
                }
            }
            $v['pub_column'] = $pub_column;
            if ($v['catalog']) {
                $v['catalog'] = unserialize($v['catalog']);
            }

            //判断outpush状态
            $outpushInfo         = $this->outpush->getOutpushInfoByAppid(APPLICATION_ID, $_REQUEST['access_token']);
            $outpush             = $outpushInfo[0] ? $outpushInfo[0]['status'] : 0;
            $v['outpush_status'] = $outpush;

            $this->addItem($v);
        }
        $this->output();
    }

    public function news_refer_material()
    {
        $condition = '';
        if (! empty($this->input['user'])) {
            $user = urldecode($this->input['user']);
            $condition .= " and t.user_name='" . $user . "'";
        }
        if (! empty($this->input['key'])) {
            $key = urldecode($this->input['key']);
            $condition .= " and t.title like '%" . $key . "%'";
        }

        if (! empty($this->input['sort_id'])) {
            $sort_id = intval($this->input['sort_id']);
            $condition .= " and t.tuji_sort_id = " . $sort_id;
        }
        $offset = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count  = $this->input['count'] ? intval(urldecode($this->input['count'])) : 10;
        $limit  = " limit {$offset}, {$count}";

        if (! empty($key)) {
            $limit = '';
        }
        $this->setXmlNode('tuji', 'item');
        $orderby = ' ORDER BY t.order_id DESC ';
        $condition .= ' and t.status = 1';
        $tuji_info = $this->tuji->news_refer_material($condition, $orderby, $limit);
        foreach ($tuji_info as $k => $v) {
            $this->addItem($v);
        }
        $this->output();
    }

    public function news_refer_sort()
    {
        $info   = array();
        $info[] = array('name' => '全部分类', 'brief' => '全部分类', 'fid' => 0, 'is_last' => 1, 'sort_id' => 0);

        if (! empty($this->input['fid'])) {
            $fid = intval($this->input['fid']);
            $sql = "select * from " . DB_PREFIX . "tuji_node where fid = " . $fid;
            $q   = $this->db->query($sql);
            $ret = array();
            while ($row = $this->db->fetch_array($q)) {
                $ret[] = $row;
            }
            if (! empty($ret)) {
                foreach ($ret as $k => $v) {
                    $v['fid'] = $v['sort_id'] = $v['id'];
                    $info[]   = $v;
                }
            }
        } else {
            $sql = "select * from " . DB_PREFIX . "tuji_node where fid = 0";
            $q   = $this->db->query($sql);
            $ret = array();
            while ($row = $this->db->fetch_array($q)) {
                $ret[] = $row;
            }
            if (! empty($ret)) {
                foreach ($ret as $k => $v) {
                    $v['fid'] = $v['sort_id'] = $v['id'];
                    $info[]   = $v;
                }
            }
        }

        if (! empty($info)) {
            foreach ($info as $k => $v) {
                $this->addItem($v);
            }
            $this->output();
        }

    }

    public function get_condition()
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
                $this->input[$k] = $v;
            }
        }
        //搜索标签		
        ####增加权限控制 用于显示####
        if ($this->user['group_type'] > MAX_ADMIN_TYPE) {
            if (! $this->user['prms']['default_setting']['show_other_data']) {
                $condition .= ' AND t.user_id = ' . $this->user['user_id'];
            } else {
                if ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group']) {
                    $condition .= ' AND t.org_id IN(' . $this->user['slave_org'] . ')';
                }
            }

            $authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
            if ($authnode) {
                $authnode_str = $authnode ? implode(',', $authnode) : '';
                if ($authnode_str && $authnode_str != -1) {
                    $sql            = 'SELECT id,childs FROM ' . DB_PREFIX . 'tuji_node WHERE id IN(' . $authnode_str . ')';
                    $query          = $this->db->query($sql);
                    $authnode_array = array();
                    while ($row = $this->db->fetch_array($query)) {
                        $authnode_array[$row['id']] = explode(',', $row['childs']);
                    }
                    //算出所有允许的节点
                    $auth_nodes = array();
                    foreach ($authnode_array AS $k => $v) {
                        $auth_nodes = array_merge($auth_nodes, $v);
                    }

                    //如果没有_id就查询出所有权限所允许的节点下的视频包括其后代元素
                    if (! $this->input['_id']) {
                        $condition .= " AND t.tuji_sort_id IN (" . implode(',', $auth_nodes) . ",0)";
                    } else if (in_array($this->input['_id'], $auth_nodes)) {
                        if (isset($authnode_array[$this->input['_id']]) && $authnode_array[$this->input['_id']]) {
                            $condition .= " AND t.tuji_sort_id IN (" . implode(',', $authnode_array[$this->input['_id']]) . ")";
                        } else {
                            $sql          = "SELECT id,childs FROM " . DB_PREFIX . "tuji_node WHERE id = '" . $this->input['_id'] . "'";
                            $childs_nodes = $this->db->query_first($sql);
                            $condition .= " AND t.tuji_sort_id IN (" . $childs_nodes['childs'] . ")";
                        }
                    } else {
                        $this->errorOutput(NO_PRIVILEGE);
                    }
                } else if ($authnode_str == -1) {
                    if ($this->input['_id']) {
                        $sql = " SELECT childs, fid FROM " . DB_PREFIX . "tuji_node WHERE  id = '" . $this->input['_id'] . "'";
                        $arr = $this->db->query_first($sql);
                        if ($arr) {
                            $condition .= " AND t.tuji_sort_id IN (" . $arr['childs'] . ")";
                        }
                    }
                }
            }
        } else {
            if ($this->input['_id']) {
                $sql = " SELECT childs, fid FROM " . DB_PREFIX . "tuji_node WHERE  id = '" . $this->input['_id'] . "'";
                $arr = $this->db->query_first($sql);
                if ($arr) {
                    $condition .= " AND t.tuji_sort_id IN (" . $arr['childs'] . ")";
                }
            }
        }

        if ($this->input['id']) {
            $condition .= ' AND t.id = ' . intval($this->input['id']);
        }
        if ($this->input['max_id'])//自动化任务用到.
        {
            $condition .= " AND t.id >" . intval($this->input['max_id']);
        }

        if (trim($this->input['key']) || trim(urldecode($this->input['key'])) == '0') {
            $condition .= ' AND t.title LIKE "%' . trim($this->input['key']) . '%"';
        }

        if ($this->input['user_name']) {
            $condition .= " AND t.user_name = '" . $this->input['user_name'] . "' ";
        }

        if ($this->input['user_id']) {
            $condition .= " AND t.user_id = '" . $this->input['user_id'] . "' ";
        }

        if ($this->input['comment']) {
            $condition .= ' AND t.comment LIKE "%' . urldecode($this->input['comment']) . '%"';
        }

        if (intval($this->input['status'])) {
            $condition .= " AND t.status = '" . intval($this->input['status']) . "'";
        }

        if ($this->input['start_time'] == $this->input['end_time']) {//处理时间相等
            $his = date('His', strtotime($this->input['start_time']));
            if (! intval($his)) {
                $this->input['start_time'] = date('Y-m-d', strtotime($this->input['start_time'])) . ' 00:00';
                $this->input['end_time']   = date('Y-m-d', strtotime($this->input['end_time'])) . ' 23:59:59';
            }
        }

        if ($this->input['start_time']) {
            $start_time = strtotime(trim(urldecode($this->input['start_time'])));
            $condition .= " AND t.create_time >= '" . $start_time . "'";
        }

        if ($this->input['end_time']) {
            $end_time = strtotime(trim(urldecode($this->input['end_time'])));
            $condition .= " AND t.create_time <= '" . $end_time . "'";
        }

        //权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1) {
            $condition .= " AND t.weight >= " . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1) {
            $condition .= " AND t.weight <= " . $this->input['end_weight'];
        }

        if ($this->input['date_search']) {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search'])) {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  t.create_time > '" . $yesterday . "' AND t.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  t.create_time > '" . $today . "' AND t.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND  t.create_time > '" . $last_threeday . "' AND t.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  t.create_time > '" . $last_sevenday . "' AND t.create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }

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
        }

        return $condition;
    }

    public function count()
    {
        $condition = $this->get_condition();
        //根据发布库栏目搜索
        if ($this->input['pub_column_id']) {
            $condition .= " GROUP BY t.id";
            $sql
                = "SELECT COUNT(*) AS total FROM (
                        SELECT t.id FROM " . DB_PREFIX . "tuji t
                        LEFT JOIN " . DB_PREFIX . "pub_column pc
                            ON t.id=pc.aid 
                        WHERE 1 " . $condition . "
                    ) aa";
        } else {
            $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "tuji t WHERE 1 " . $condition;
        }

        echo json_encode($this->db->query_first($sql));
    }

    public function news_refer_count()
    {
        $sql   = "select count(*) as total from " . DB_PREFIX . "tuji where 1 and status = 1";
        $total = $this->db->query_first($sql);
        $this->addItem($total);
        $this->output();
    }

    public function detail()
    {
        $ret = array();
        if ($this->input['id']) {
            $sql = "SELECT * FROM " . DB_PREFIX . "tuji WHERE id = '" . intval($this->input['id']) . "'";
            $ret = $this->db->query_first($sql);
            if (! $ret) {
                $this->errorOutput(ITEM_NOT_EXISTS);
            }
            if ($ret['status'] == 1) {
                $ret['pubstatus'] = 1;
                $ret['status']    = 2;
            } else {
                $ret['pubstatus'] = 0;
                $ret['status']    = 0;
            }
            if ($ret['catalog']) {
                $ret['catalog'] = unserialize($ret['catalog']);
            }
            $ret['status_display'] = $ret['pubstatus'];
            $column_id             = unserialize($ret['column_id']);
            $column_url            = unserialize($ret['column_url']);
            $ret['column_id']      = '';
            $pub_column            = array();
            if (is_array($column_id)) {
                $column_ids = array();
                foreach ($column_id as $k => $v) {
                    $column_ids[] = $k;
                    $pub_column[] = array(
                        "column_id"   => $k,
                        "column_name" => $v,
                        "pub_id"      => intval($column_url[$k])
                    );
                }
                $column_ids       = implode(',', $column_ids);
                $ret['column_id'] = $column_ids;

            }
            $ret['pub_column'] = $pub_column;
            if ($ret['id']) {
                $sql   = 'SELECT id,img_info,description FROM ' . DB_PREFIX . 'pics WHERE tuji_id = ' . $ret['id'] . " ORDER BY order_id DESC";
                $query = $this->db->query($sql);
                $i     = 0;
                while ($row = $this->db->fetch_array($query)) {
                    if ($this->input['is_news_helper']) {
                        $ret['pics'][$i]                = unserialize($row['img_info']);
                        $ret['pics'][$i]['description'] = $row['description'];
                        $ret['pics'][$i]['id']          = $row['id'];
                        $i++;
                    } else {
                        $ret['pics'][$row['id']]                = unserialize($row['img_info']);
                        $ret['pics'][$row['id']]['description'] = $row['description'];
                    }
                }
            }
            $ret['cover_url'] = $ret['cover_url'] ? unserialize($ret['cover_url']) : array();

            $ret['pub_time'] = $ret['pub_time'] ? date("Y-m-d H:i", $ret['pub_time']) : '';
        }
        $this->addItem($ret);
        $this->output();
    }

    //多id查询
    public function details()
    {
        if (! $_REQUEST['ids']) {
            $this->errorOutput(NOID);
        }
        $ids = explode(',', $_REQUEST['ids']);
        foreach ($ids as $id) {
            if ($id) {
                $sql = "SELECT * FROM " . DB_PREFIX . "tuji WHERE id = '" . intval($id) . "'";
                $ret = $this->db->query_first($sql);
                if (! $ret) {
                    $this->errorOutput(ITEM_NOT_EXISTS);
                }
                if ($ret['status'] == 1) {
                    $ret['pubstatus'] = 1;
                    $ret['status']    = 2;
                } else {
                    $ret['pubstatus'] = 0;
                    $ret['status']    = 0;
                }
                if ($ret['catalog']) {
                    $ret['catalog'] = unserialize($ret['catalog']);
                }
                $ret['status_display'] = $ret['pubstatus'];
                $column_id             = unserialize($ret['column_id']);
                $column_url            = unserialize($ret['column_url']);
                $ret['column_id']      = '';
                $pub_column            = array();
                if (is_array($column_id)) {
                    $column_ids = array();
                    foreach ($column_id as $k => $v) {
                        $column_ids[] = $k;
                        $pub_column[] = array(
                            "column_id"   => $k,
                            "column_name" => $v,
                            "pub_id"      => intval($column_url[$k])
                        );
                    }
                    $column_ids       = implode(',', $column_ids);
                    $ret['column_id'] = $column_ids;

                }
                $ret['pub_column'] = $pub_column;
                if ($ret['id']) {
                    $sql   = 'SELECT id,img_info,description FROM ' . DB_PREFIX . 'pics WHERE tuji_id = ' . $ret['id'] . " ORDER BY order_id DESC";
                    $query = $this->db->query($sql);
                    $i     = 0;
                    while ($row = $this->db->fetch_array($query)) {
                        if ($this->input['is_news_helper']) {
                            $ret['pics'][$i]                = unserialize($row['img_info']);
                            $ret['pics'][$i]['description'] = $row['description'];
                            $ret['pics'][$i]['id']          = $row['id'];
                            $i++;
                        } else {
                            $ret['pics'][$row['id']]                = unserialize($row['img_info']);
                            $ret['pics'][$row['id']]['description'] = $row['description'];
                        }
                    }
                }
                $ret['cover_url'] = $ret['cover_url'] ? unserialize($ret['cover_url']) : array();

                $ret['pub_time'] = $ret['pub_time'] ? date("Y-m-d H:i", $ret['pub_time']) : '';
            }
            $return[] = $ret;
        }
        $this->addItem($return);
        $this->output();
    }

    public function refer_detail()
    {
        $ret = array();
        if ($this->input['id']) {
            $sql             = "select * from " . DB_PREFIX . "tuji where id=" . intval($this->input['id']);
            $info            = $this->db->query_first($sql);
            $ret['type']     = "tuji";
            $ret['title']    = $info['title'];
            $ret['brief']    = $info['comment'];
            $ret['time']     = date('Y-m-d H:i', $info['create_time']);
            $ret['keywords'] = $info['keywords'];

            $ret['img'] = unserialize($info['cover_url']);
            unset($info['cover_url']);

            $sql              = "select * from " . DB_PREFIX . "tuji_node where id=" . $info['tuji_sort_id'];
            $sort_info        = $this->db->query_first($sql);
            $ret['sort_name'] = $sort_info['name'] ? $sort_info : '';
        }
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 获取图集示意图...
     */
    public function get_sketch_map()
    {
        if (! $this->input['id']) {
            return FALSE;
        }
        $sql   = "SELECT * FROM " . DB_PREFIX . "tuji  WHERE id = " . intval($this->input['id']);
        $ret   = $this->db->query_first($sql);
        $order = " ORDER BY p.order_id ASC ";
        $sql   = "SELECT p.*,t.default_comment,t.is_namecomment FROM " . DB_PREFIX . "pics p LEFT JOIN " . DB_PREFIX . "tuji t ON t.id = p.tuji_id WHERE p.tuji_id = '" . intval($this->input['id']) . "' {$order} ";
        $q     = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q)) {
            $srcPath[] = $r['path'] . $r['new_name'];
        }
        $srcPath = implode(",", $srcPath);
        //获取当前脚本名称
        $url        = $_SERVER['PHP_SELF'];
        $scriptname = end(explode('/', $url));
        $scriptname = explode('.', $scriptname);
        $scriptname = $scriptname[0];

        $newName = $scriptname . '_' . $ret['id'] . ".png";
        $title   = hg_cutchars($ret['title'], 26);
        $url     = $this->material->create_sketch_map($srcPath, $newName, $title, 'tuji');
        $this->addItem($url);
        $this->output();
    }

    /**
     * 获取所有图集下的图片信息 ...
     */
    public function show_all_images()
    {
        if (! $this->input['id']) {
            $this->errorOutput(NOID);
        }

        $order = " ORDER BY p.order_id ASC ";
        $sql   = "SELECT p.*,t.default_comment,t.is_namecomment FROM " . DB_PREFIX . "pics p LEFT JOIN " . DB_PREFIX . "tuji t ON t.id = p.tuji_id WHERE p.tuji_id = '" . intval($this->input['id']) . "' {$order} ";
        $q     = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q)) {
            $r['img_info'] = unserialize($r['img_info']);
            $r['pic_url']  = hg_fetchimgurl($r['img_info']);
            $ret           = array(
                'id'            => $r['id'],
                'title'         => $r['old_name'],
                'brief'         => $r['description'] ? $r['description'] : $r['default_comment'],
                'img'           => $r['pic_url'],
                'appid'         => $r['appid'],
                'appname'       => $r['appname'],
                'user_id'       => $r['user_id'],
                'user_name'     => $r['user_name'],
                'img_info'      => $r['img_info'],
                'url'           => $r['url'],
                'app_bundle'    => APP_UNIQUEID,
                'module_bundle' => MOD_UNIQUEID,
            );
            $this->addItem($ret);
        }
        $this->output();
    }

    //新增图集
    public function add_new_tuji()
    {
        if (! $this->input['title'])//如果没传图集的名称过来就报错
        {
            $this->errorOutput('error,No title!');
        }

        $title           = urldecode($this->input['title']);
        $default_comment = urldecode($this->input['default_comment']);
        $sql             = "SELECT * FROM " . DB_PREFIX . "tuji WHERE title = '{$title}'";
        $arr             = $this->db->query_first($sql);
        $tuji_id         = 0;
        if (! $arr['title'])//如果能查询到,说明图集名称已存在,不存在创建图集
        {
            //创建图集
            $sql = " INSERT INTO " . DB_PREFIX . "tuji SET ";
            $sql .= " title = '" . $title . "'," .
                    " tuji_sort_id = '" . intval($this->input['tuji_sort_id']) . "'," .
                    " comment = '" . urldecode($this->input['comment']) . "'," .
                    " default_comment = '" . $default_comment . "'," .
                    " keywords = '" . urldecode($this->input['keywords']) . "'," .
                    " auto_cover = '" . intval($this->input['auto_cover']) . "'," .
                    " is_namecomment = '" . intval($this->input['is_namecomment']) . "'," .
                    " is_orderby_name = '" . intval($this->input['is_orderby_name']) . "'," .
                    " is_add_water = '" . intval($this->input['is_add_water']) . "'," .
                    " user_name = '" . urldecode($this->user['user_name']) . "'," .
                    " create_time = '" . TIMENOW . "'," .
                    " update_time = '" . TIMENOW . "'," .
                    " ip = '" . hg_getip() . "'," .
                    " status = -1";
            $this->db->query($sql);
            $vid = $this->db->insert_id();
            $sql = " UPDATE " . DB_PREFIX . "tuji SET order_id = '{$vid}'  WHERE id = '{$vid}'";
            $this->db->query($sql);
            $tuji_id = $vid;
        } else {
            $tuji_id = $arr['id'];
        }

        /*将图片提交到图片服务器*/
        $files['Filedata'] = $_FILES['videofile'];
        if ($files['Filedata']) {
            $material_pic   = new material();
            $img_info       = $material_pic->addMaterial($files, $tuji_id);
            $img_thumb_info = hg_fetchimgurl($img_info['img_info'], 100, 100);
            //是否以图片名作为图片的描述
            if (($this->input['is_namecomment'])) {
                $description = $files['Filedata']['name'];
            } else {
                $description = $default_comment;
            }

            $data = array(
                'tuji_id'     => $tuji_id,
                'old_name'    => $files['Filedata']['name'],
                'new_name'    => $img_info['filename'],
                'material_id' => $img_info['id'],
                'description' => $description,
                'create_time' => TIMENOW,
                'path'        => $img_info['filepath'],
                'ip'          => hg_getip(),
            );

            //判断是否存在封面 如果不存在则以第一幅图片做为封面
            $cover = $this->get_cover($data['tuji_id']);
            if (! $cover) {
                $sql = 'UPDATE ' . DB_PREFIX . 'tuji SET cover_url = "' . $data['path'] . $data['new_name'] . '" WHERE id = ' . $data['tuji_id'];
                $this->db->query($sql);
            }
            //更新图集的最新图片
            $sql    = 'SELECT latest FROM ' . DB_PREFIX . 'tuji WHERE id = ' . $data['tuji_id'];
            $latest = $this->db->query_first($sql);

            if ($latest['latest']) {
                $latest = unserialize($latest['latest']);

            } else {
                $latest = array();

            }
            if (count($latest) >= 4) {
                @array_shift($latest);
                array_push($latest, $data['path'] . $data['new_name']);
            } else {
                @array_push($latest, $data['path'] . $data['new_name']);
            }
            $sql = 'UPDATE ' . DB_PREFIX . 'tuji SET latest = \'' . serialize($latest) . '\' WHERE id = ' . $data['tuji_id'];
            $this->db->query($sql);
            //图片数据入库
            $sql = "INSERT INTO " . DB_PREFIX . 'pics SET ';
            foreach ($data as $key => $v) {
                $sql .= " `{$key}` = '{$v}',";
            }
            $this->db->query(rtrim($sql, ','));
            $vid = $this->db->insert_id();
            $sql = " UPDATE " . DB_PREFIX . "pics SET order_id = '" . $vid . "' WHERE id = '" . $vid . "'";
            $this->db->query($sql);
            $ret_data = array('pic_src' => $img_thumb_info, 'description' => $data['description'], 'img_flag' => 1, 'pic_id' => $vid, 'tuji_id' => $data['tuji_id']);
        }

        if ($ret_data) {
            $this->addItem($ret_data);
        } else {
            $this->addItem(array('img_flag' => 1));
        }
        $this->output();
    }

    /*新增图集将图片提交到图片服务器*/
    public function add_new_tuji_server()
    {
        if (! $this->input['title'])//如果没传图集的名称过来就报错
        {
            $this->errorOutput(TITLE);
        }

        $title   = urldecode($this->input['title']);
        $sql     = "SELECT * FROM " . DB_PREFIX . "tuji WHERE title = '{$title}'";
        $arr     = $this->db->query_first($sql);
        $tuji_id = 0;
        if (! $arr['title'])//如果能查询到,说明图集名称已存在,不存在创建图集
        {
            //创建图集
            $sql = " INSERT INTO " . DB_PREFIX . "tuji SET ";
            $sql .= " title = '" . $title . "'," .
                    " tuji_sort_id = '" . intval($this->input['tuji_sort_id']) . "'," .
                    " comment = '" . urldecode($this->input['comment']) . "'," .
                    " default_comment = '" . urldecode($this->input['default_comment']) . "'," .
                    " keywords = '" . urldecode($this->input['keywords']) . "'," .
                    " auto_cover = '" . intval($this->input['auto_cover']) . "'," .
                    " is_namecomment = '" . intval($this->input['is_namecomment']) . "'," .
                    " is_orderby_name = '" . intval($this->input['is_orderby_name']) . "'," .
                    " is_add_water = '" . intval($this->input['is_add_water']) . "'," .
                    " user_name = '" . urldecode($this->user['user_name']) . "'," .
                    " create_time = '" . TIMENOW . "'," .
                    " update_time = '" . TIMENOW . "'," .
                    " ip = '" . hg_getip() . "'," .
                    " status = -1";
            $this->db->query($sql);
            $vid = $this->db->insert_id();
            $sql = " UPDATE " . DB_PREFIX . "tuji SET order_id = '{$vid}'  WHERE id = '{$vid}'";
            $this->db->query($sql);
            $tuji_id = $vid;
        } else {
            $tuji_id = $arr['id'];
        }

        //处理里面的图片
        $files['filedata'] = $_FILES['videofile'];
        if ($files) {
            $data = array();
            $data = array(
                'tuji_id'     => $tuji_id,
                'old_name'    => $files['name'],
                'new_name'    => $file_name,
                'description' => $files['name'],
                'create_time' => TIMENOW,
                'path'        => $file_path,
                'ip'          => hg_getip(),
            );
            //判断是否存在封面 如果不存在则以第一幅图片做为封面
            $cover = $this->get_cover($data['tuji_id']);
            if (! $cover) {
                $sql = 'UPDATE ' . DB_PREFIX . 'tuji SET cover_url = "' . $data['path'] . $data['new_name'] . '" WHERE id = ' . $data['tuji_id'];
                $this->db->query($sql);
            }
            //更新图集的最新图片
            $sql    = 'SELECT latest FROM ' . DB_PREFIX . 'tuji WHERE id = ' . $data['tuji_id'];
            $latest = $this->db->query_first($sql);

            if ($latest['latest']) {
                $latest = unserialize($latest['latest']);

            } else {
                $latest = array();

            }
            if (count($latest) >= 4) {
                @array_shift($latest);
                array_push($latest, $data['path'] . $data['new_name']);
            } else {
                @array_push($latest, $data['path'] . $data['new_name']);
            }
            $sql = 'UPDATE ' . DB_PREFIX . 'tuji SET latest = \'' . serialize($latest) . '\' WHERE id = ' . $data['tuji_id'];
            $this->db->query($sql);
            //图片数据入库
            $sql = "INSERT INTO " . DB_PREFIX . 'pics SET ';
            foreach ($data as $key => $v) {
                $sql .= " `{$key}` = '{$v}',";
            }
            $this->db->query(rtrim($sql, ','));
            $vid = $this->db->insert_id();
            $sql = " UPDATE " . DB_PREFIX . "pics SET order_id = '" . $vid . "' WHERE id = '" . $vid . "'";
            $this->db->query($sql);
            $ret_data = array('pic_src' => UPLOAD_THUMB_URL . $data['path'] . $data['new_name'], 'description' => $data['description'], 'img_flag' => 1, 'pic_id' => $vid, 'tuji_id' => $data['tuji_id']);
        }

        if ($ret_data) {
            $this->addItem($ret_data);
        } else {
            $this->addItem(array('img_flag' => 1));
        }
        $this->output();
    }

    //编辑评论
    public function change_comment()
    {
        if (! $this->input['id']) {
            $this->errorOutput('未传id');
        }

        if ($this->input['is_tuji']) {
            $sql = " UPDATE " . DB_PREFIX . "tuji SET comment = '" . urldecode($this->input['comment']) . "' WHERE id = '" . intval($this->input['id']) . "'";
        } else {
            $sql = " UPDATE " . DB_PREFIX . "pics SET description = '" . urldecode($this->input['description']) . "' WHERE id = '" . intval($this->input['id']) . "'";
        }
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

    //添加完图集之后动态添加一行
    public function add_tuji_new()
    {
        if (! $this->input['id']) {
            $this->errorOutput('未传id');
        }
        $sql                = "SELECT t.*,ts.name as sort_name FROM " . DB_PREFIX . "tuji t LEFT JOIN " . DB_PREFIX . "tuji_node ts ON ts.id = t.tuji_sort_id WHERE t.id = '" . intval($this->input['id']) . "'";
        $ret                = $this->db->query_first($sql);
        $ret['status']      = $this->settings['image_upload_status'][$ret['status']];
        $ret['img_info']    = unserialize($ret['img_info']);
        $ret['cover_url']   = hg_fetchimgurl($ret['img_info']);
        $ret['create_time'] = date('Y-m-d H:i:s', $ret['create_time']);
        $this->addItem($ret);
        $this->output();
    }

    public function open_tuji()
    {
        if (! $this->input['tuji_id']) {
            $this->errorOutput(NOID);
        }

        $order = " ORDER BY p.order_id ASC ";
        $sql   = " SELECT p.*,t.default_comment,t.is_namecomment FROM " . DB_PREFIX . "pics p LEFT JOIN " . DB_PREFIX . "tuji t ON t.id = p.tuji_id WHERE p.tuji_id = '" . intval($this->input['tuji_id']) . "' {$order} ";
        $q     = $this->db->query($sql);
        $ret   = array();
        while ($r = $this->db->fetch_array($q)) {
            $r['img_info'] = unserialize($r['img_info']);
            $r['img_src']  = hg_fetchimgurl($r['img_info'], 160);
            $ret[]         = $r;
        }
        $this->addItem($ret);
        $this->output();
    }

    public function revolveImg()
    {
        if (! $this->input['pic_id']) {
            $this->errorOutput(NOID);
        }

        $sql         = "SELECT * FROM " . DB_PREFIX . "pics WHERE id = '" . intval($this->input['pic_id']) . "'";
        $arr         = $this->db->query_first($sql);
        $material_id = $arr['material_id'];
        $ret         = $this->material->revolveImg($material_id, intval($this->input['direction']));
        if ($ret) {
            $this->addItem($ret);
            $this->output();
        } else {
            $this->errorOutput('旋转失败');
        }
    }

    //获取图集信息以及该图集下面图片的信息
    public function get_tuji_info()
    {
        #####
        $this->verify_content_prms(array('_action' => 'show'));
        #####
        $ret = array();
        if ($this->input['id']) {
            //先查出图集的信息
            $sql         = "SELECT t.*,tn.name as tuji_sort_name,tn.parents FROM " . DB_PREFIX . "tuji t LEFT JOIN " . DB_PREFIX . "tuji_node tn ON t.tuji_sort_id = tn.id WHERE t.id = '" . intval($this->input['id']) . "'";
            $ret['tuji'] = $this->db->query_first($sql);
            /**************************权限控制********************************/
            /*
            if($this->user['group_type'] > MAX_ADMIN_TYPE)
            {
                $prms_arr['_action'] = 'update_tuji';
                if(!$ret['tuji']['parents'])
                {
                    $ret['tuji']['parents'] = 0;
                }
                $prms_arr['nodes']['tuji_node'][$ret['tuji']['tuji_sort_id']] = $ret['tuji']['parents'];
                $this->verify_content_prms($prms_arr);
            }
            */
            /**************************权限控制********************************/
            if ($ret['tuji']['catalog']) {
                $ret['tuji']['catalog'] = unserialize($ret['tuji']['catalog']);
            }
            $ret['tuji']['column_name'] = unserialize($ret['tuji']['column_id']);
            $ret['tuji']['column_id']   = unserialize($ret['tuji']['column_id']);
            if ($ret['tuji']['cover_url']) {
                $ret['tuji']['cover_url'] = unserialize($ret['tuji']['cover_url']);
            }
            $ret['tuji']['create_time'] = date('Y-m-d H:i:s', $ret['tuji']['create_time']);
            if (is_array($ret['tuji']['column_id']) && $ret['tuji']['column_id']) {
                $column_id = array();
                foreach ($ret['tuji']['column_id'] as $k => $v) {
                    $column_id[] = $k;
                }
                $column_id                = implode(',', $column_id);
                $ret['tuji']['column_id'] = $column_id;
            }
            //在查出该图集下的图片
            $sql = "SELECT * FROM " . DB_PREFIX . "pics WHERE tuji_id = '" . intval($this->input['id']) . "' ORDER BY order_id ASC";
            $q   = $this->db->query($sql);
            while ($r = $this->db->fetch_array($q)) {
                $r['img_info'] = unserialize($r['img_info']);
                $r['img_src']  = hg_fetchimgurl($r['img_info'], 160);
                $r['pic_id']   = $r['id'];
                $ret['pics'][] = $r;
            }
            $ret['edit'] = 1;
        }
        $this->addItem($ret);
        $this->output();
    }

    /**
     * 获取所有已审核的图集列表信息 ...
     */
    public function show_quick_select()
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "tuji WHERE status = 1 ORDER BY order_id DESC ";
        $q   = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q)) {
            $cover = unserialize($r['cover_url']);
            if (! $cover || ! is_array($cover)) {
                $cover = array('host' => '', 'dir' => '', 'filepath' => '', 'filename' => '');
            }
            $ret = array(
                'id'            => $r['id'],
                'title'         => $r['title'],
                'brief'         => $r['comment'],
                'cover_url'     => $cover,
                'appid'         => $r['appid'],
                'appname'       => $r['appname'],
                'user_id'       => $r['user_id'],
                'user_name'     => $r['user_name'],
                'app_uniquid'   => APP_UNIQUEID,
                'module_bundle' => MOD_UNIQUEID,
            );
            $this->addItem($ret);
        }
        $this->output();
    }

    /**
     * 获取图集推送到专题的记录信息 ...
     */
    public function get_scolumn()
    {
        $id = $this->input['id'];
        if (empty($id)) {
            $this->errorOutput(NOID);
        }
        $sql     = "SELECT * FROM " . DB_PREFIX . "tuji WHERE id=" . $id;
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

    public function statistics()
    {
        $return['static'] = 1;
        $static_date      = $this->input['static_date'];
        if ($static_date) {
            $date = strtotime($static_date);
        } else {
            $date = strtotime(date("Y-m-d 00:00:00", strtotime("-1 day")));
        }
        $sql   = 'select status,user_id,user_name,org_id,expand_id,column_id from ' . DB_PREFIX . 'tuji where create_time >= ' . $date . ' and create_time < ' . ($date + 86400);
        $query = $this->db->query($sql);
        while ($r = $this->db->fetch_array($query)) {
            $ret[$r['user_id']]['org_id']    = $r['org_id'];
            $ret[$r['user_id']]['user_name'] = $r['user_name'];
            $ret[$r['user_id']]['count']++;
            $r['status'] == 1 ? $ret[$r['user_id']]['statued']++ : FALSE;
            $r['status'] == 2 ? $ret[$r['user_id']]['unstatued']++ : FALSE;
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

}
$out    = new tuji();
$action = $_INPUT['a'];
if (! $_INPUT['a']) {
    $action = 'show';
}
$out->$action();
?>