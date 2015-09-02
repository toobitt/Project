<?php

define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
define('MOD_UNIQUEID', 'special');
require_once(ROOT_PATH . "global.php");
require_once(CUR_CONF_PATH . "lib/functions.php");

class specialApi extends adminBase
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
        require_once CUR_CONF_PATH . 'lib/special.class.php';
        $this->special         = new special();
        require_once CUR_CONF_PATH . 'lib/special_content.class.php';
        $this->special_content = new specialContent();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $condition     = $this->get_condition();
        $offset        = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count         = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit         = " limit {$offset}, {$count}";
        $need_summary  = $this->input['need_summary'];
        $need_material = $this->input['need_material'];
        $appid         = $this->input['appid'];

        $ret = $this->special->show($condition, $limit, $appid, $need_summary, $need_material);
        if (is_array($ret))
        {
            foreach ($ret AS $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    private function get_condition()
    {
        $condition = '';

        if ($special_ids = $this->input['id'])
        {
            $condition .=" AND a.id  IN (" . $special_ids . ")";
        }

        if ($fid = $this->input['fid'])
        {
            $sql    = 'SELECT childs from ' . DB_PREFIX . 'special_sort WHERE id  = ' . $fid;
            $chs    = $this->db->query_first($sql);
            $childs = $chs['childs'];
            $condition .=" AND a.sort_id  IN (" . $childs . ")";
        }

        //查询分组
        if ($this->input['sort_id'] && $this->input['sort_id'] != -1)
        {
            //$condition .= " AND  a.sort_id = '" . intval($this->input['sort_id']) . "'";
            $condition .= " AND  a.sort_id IN(" . $this->input['sort_id'] . ")";
        }

        if($exclude_sort_id = $this->input['exclude_sort_id'])
        {
            $childs = '';
            $sql    = 'SELECT childs from ' . DB_PREFIX . 'special_sort WHERE id  in (' . $exclude_sort_id.')';
            $chs    = $this->db->query($sql);
            while($row = $this->db->fetch_array($chs))
            {
                $childs = $childs.','.$row['childs'];
            }
            if($childs)
            {
                $condition .=" AND a.sort_id  NOT IN (" . $childs . ")";
            }
        }

        //查询创建的起始时间
        if ($this->input['start_time'])
        {
            $condition .= " AND a.create_time > " . strtotime($this->input['start_time']);
        }

        //查询创建的结束时间
        if ($this->input['end_time'])
        {
            $condition .= " AND a.create_time < " . strtotime($this->input['end_time']);
        }

        if (isset($this->input['weight']) && intval($this->input['weight']) >= 0)
        {
            $condition .= " AND a.weight= " . intval($this->input['weight']);
        }

        //查询权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1)
        {
            $condition .=" AND a.weight >= " . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -1)
        {
            $condition .=" AND a.weight <= " . $this->input['end_weight'];
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
                    $condition .= " AND  a.create_time > '" . $yesterday . "' AND a.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  a.create_time > '" . $today . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND a.create_time > '" . $last_threeday . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  a.create_time > '" . $last_sevenday . "' AND a.create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }
        if ($this->input['state'])
        {
            $condition .= " AND a.state = " . $this->input['state'];
        }
        else
        {
            $condition .= " AND a.state = 1";
        }
        if ($this->input['sort_type'] == 'ASC')
        {
            $condition .=" ORDER BY a.order_id  " . $this->input['sort_type'];
        }
        else
        {
            $condition .= " ORDER BY a.order_id DESC ";
        }
        return $condition;
    }

    /**
     * 获取专题信息
     * */
    public function get_special_node()
    {
        $condition = '';
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 20;
        $limit     = " limit {$offset}, {$count}";
        $fid       = $this->input['fid'];

        if (strpos($fid, "sort") !== false)
        {
            $sort_id = intval($fid);
        }
        elseif (strpos($fid, "spe") !== false)
        {
            $special_id = intval($fid);
        }
        $ret = $this->special->show_special($sort_id, $special_id, $limit);
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 获取专题信息
     * */
    public function get_special_sort()
    {
        $fid = $this->input['fid'];

        $ret = $this->special->get_special_sort($fid);
        if ($ret)
        {
            foreach ($ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /**
     * 获取专题条数
     * */
    public function get_special_count()
    {
        $sql           = 'SELECT count(*) as total from ' . DB_PREFIX . 'special a WHERE 1 ' . $this->get_condition();
        $special_total = $this->db->query_first($sql);
        echo json_encode($special_total);
        exit;
    }

    /* 根据special_id查询专题信息
     * */

    public function get_special_by_id()
    {
        $need_process = $this->input['need_process'];
        $special_id        = intval($this->input['id']);
        $special_column_id = intval($this->input['special_column_id']);
        if ($special_column_id)
        {
            $sql            = "select special_id from " . DB_PREFIX . "special_columns where id=" . $special_column_id;
            $special_column = $this->db->query_first($sql);
            $special_id     = $special_column['special_id'];
        }
        if (!$special_id)
        {
            $this->errorOutput("请输入专题id！");
        }
		$appid         = $this->input['appid'];
        $ret = $this->special->get_special_by_id($special_id,$appid);

        if($need_process)
        {
            $ret['brief'] = strip_tags($ret['brief']);
        }
        //取专题链接
        $ret['column_url'] = $ret['column_url'];
        if ($ret['column_url'])
        {
            if ($rid = current($ret['column_url']))
            {
                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->pub_content = new publishcontent();
                $content           = $this->pub_content->get_content(array('content_id' => $rid));

                if ($content[0] && is_array($content[0]))
                {
                    $ret['content_url'] = $content[0]['content_url'];
                }
            }
        }

        $this->addItem($ret);
        $this->output();
    }

    /* 根据special_id查询专题概要
     * */

    public function get_special_summary()
    {
        $id = intval($this->input['id']);
        if (!$id) {
            $this->errorOutput(NO_SPECIALID);
        }
        $sq      = 'SELECT * FROM ' . DB_PREFIX . 'special_summary  WHERE special_id =' . $id . ' AND del=0';
        $q_      = $this->db->query($sq);
        $summary = array();
        while ($ro      = $this->db->fetch_array($q_))
        {
            $summary[] = $ro;
        }
        $this->addItem($summary);
        $this->output();
    }

    /* 根据special_id查询专题素材
     * */

    public function get_special_material()
    {
        $id = intval($this->input['id']);
        if (!$id) {
            $this->errorOutput(NO_SPECIALID);
        }
        $sql_     = 'SELECT * FROM ' . DB_PREFIX . 'special_material  WHERE special_id =' . $id . ' AND del =0';
        $q        = $this->db->query($sql_);
        $summary  = $material = array();
        while ($row      = $this->db->fetch_array($q))
        {
            if ($row['mark'] != 'video')
            {
                $row['filesize'] = hg_bytes_to_size($row['filesize']);
                $row['material'] = unserialize($row['material']);
                $material[]      = $row;
            }
            else
            {
                $video[$row['id']] = unserialize($row['material']);
            }
        }
        $re['mer']   = $material;
        $re['video'] = $video;

        $this->addItem($re);
        $this->output();
    }

    public function get_mkspecial()
    {
        $special_id        = intval($this->input['id']);
        $special_column_id = intval($this->input['special_column_id']);
        if ($special_column_id)
        {
            $sql            = "select * from " . DB_PREFIX . "special_columns where id=" . $special_column_id;
            $special_column = $this->db->query_first($sql);
            $special_id     = $special_column['special_id'];
        }
        if (!$special_id)
        {
            $this->errorOutput("请输入专题id！");
        }
        $ret               = $this->special->get_special_by_id($special_id);
        $ret['column_url'] = $ret['column_url'];
        if ($ret['column_url'])
        {
            if ($rid = current($ret['column_url']))
            {
                include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
                $this->pub_content = new publishcontent();
                $content           = $this->pub_content->get_content(array('content_id' => $rid));

                if ($content[0] && is_array($content[0]))
                {
                    if ($ret['column_dir'])
                    {
                        $ret['special_dir'] = $ret['column_dir'];
                    }
                    else
                    {
                        if ($content[0]['file_name'])
                        {
                            $file_namearr = explode('/', $content[0]['file_name']);
                            $file_namearr = array_reverse($file_namearr, false);
                            unset($file_namearr[0]);
                            $file_namearr = array_reverse($file_namearr, false);
                            if ($file_namearr)
                            {
                                foreach ($file_namearr as $k => $v)
                                {
                                    if ($v)
                                    {
                                        $ret['special_dir'] .= $v . '/';
                                    }
                                }
                                if ($ret['special_dir'])
                                {
                                    $ret['special_dir'] = '/' . trim($ret['special_dir'], '/');
                                }
                            }
                        }
                    }

                    $ret['column_dir']     = $content[0]['main_column_info']['column_dir'] . ($ret['special_dir'] ? ('/' . $ret['special_dir']) : '');
                    $ret['relate_dir']     = $content[0]['main_column_info']['relate_dir'] . ($ret['special_dir'] ? ('/' . $ret['special_dir']) : '');
                    $ret['maketype']       = $special_column['maketype'];
                    $ret['column_file']    = $special_column['column_file'];
                    $ret['colindex']       = $special_column['colindex'] ? $special_column['colindex'] : ($special_column['id'] . '_list');
                    $ret['title']          = $special_column['column_name'] . '_' . $ret['name'];
                    $ret['keywords']       = $ret['keywords'];
                    $ret['brief']          = strip_tags(htmlspecialchars_decode($ret['brief']));
                    $ret['special_column'] = $special_column;
                    //取专题发布至的链接
                    $ret['content_url']    = $content[0]['content_url'];
                }
            }
            else
            {
                $ret = array();
            }
        }
        else
        {
            $ret = array();
        }

        $this->addItem($ret);
        $this->output();
    }

    /**
     * 根据special_id查询专题内容
     * */
    public function get_content_by_special_ids()
    {
        $special_ids = $this->input['special_id'];
        if (!$special_ids)
        {
            $special_ids = $this->input['column_id'];
            if (!$special_ids)
            {
                $this->errorOutput("请输入专题id！");
            }
        }
        $offset    = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count     = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit     = " limit {$offset}, {$count}";
        $sort_type = $this->input['sort_type'];
        $sort      = $this->input['sort'];
        $ret       = $this->special_content->show_content_by_special_ids($special_ids, $limit, $sort_type, $sort);
        if (is_array($ret))
        {
            foreach ($ret as $k => $v)
            {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    /* 根据专题栏目查询内容
     * */

    public function get_content_by_special_column_ids()
    {
        $special_column_ids = $this->input['special_column_id'];
        if (!$special_column_ids)
        {
            $this->errorOutput("请输入专题栏目id！");
        }
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count  = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit  = " limit {$offset}, {$count}";
        $ret    = $this->special_content->get_content_by_special_column_ids($special_column_ids, $limit);
        foreach ($ret as $k => $v)
        {
            $this->addItem($v);
        }
        $this->output();
    }

    /* 根据专题id获取栏目和 栏目下内容信息
     * */

    public function get_child_content_by_special_id()
    {
        $special_id = $this->input['special_id'];
        if (!$special_id)
        {
            $this->errorOutput("请输入专题id！");
        }
        $ret = $this->special_content->get_child_content_by_special_id($special_id);

        $this->addItem($ret);
        $this->output();
    }

    public function get_column_special()
    {
        $id          = $this->input['id'];
        $host        = $this->settings['App_publishcontent']['host'];
        $dir         = $this->settings['App_publishcontent']['dir'];
        $curl        = new curl($host, $dir);
        $curl->setSubmitType('post');
        $curl->initPostData();
        $curl->addRequestData('a', 'show');
        $curl->addRequestData('fid', $id);
        $curl->addRequestData('count', 200);
        $curl->addRequestData('sort_type', 'DESC');
        $column_info = $curl->request('column.php');
        if (!$column_info)
        {
            exit;
        }
        foreach ($column_info AS $col)
        {
            if ($col && is_array($col))
            {
                $spe_info = array(
                    'id' => $col['id'],
                    'name' => $col['name'],
                    'order_id' => $col['order_id'],
                    'brief' => $col['brief'],
                    'keywords' => $col['keywords'],
                    'user_id' => $col['user_id'],
                    'user_name' => $col['user_name'],
                    'ip' => $col['ip'],
                    'update_time' => $col['update_time'],
                    'create_time' => $col['create_time'],
                    'maketype' => $col['maketype'],
                    'column_domain' => $col['column_domain'],
                    'column_dir' => $col['column_dir'],
                    'pic' => serialize($col['indexpic']),
                    'top_pic' => serialize($col['indexpic']),
                );
            }
            $ret = $this->special->insert_data($spe_info, 'special');
            $this->special->update_special(array('order_id' => $ret), 'special', " id IN({$ret})");

            $column_info = array(
                'column_name' => '默认栏目',
                'special_id' => $ret,
            );
            $colid       = $this->special->insert_data($column_info, 'special_columns');

            $count  = $this->input['count'];
            $offset = $this->input['offset'];

            $curl->addRequestData('a', 'get_content');
            $curl->addRequestData('column_id', $col['id']);
            $curl->addRequestData('count', $count);
            $curl->addRequestData('id', $offset);
            $curl->addRequestData('client_type', '2');
            $curl->addRequestData('sort_field', 'order_id');
            $curl->addRequestData('sort_type', 'ASC');
            $con_info = $curl->request('content.php');

            if ($con_info && is_array($con_info))
            {
                $error   = $success = '';
                foreach ($con_info as $k => $content_info)
                {
                    $info = array(
                        'pub_id' => $content_info['id'],
                        'title' => addslashes($content_info['title']),
                        'special_id' => $ret,
                        'column_id' => $colid,
                        'columns' => $content_info['column_id'],
                        'state' => 1, //1已审核
                        'weight' => $content_info['weight'],
                        'indexpic' => addslashes(serialize($content_info['indexpic'])),
                        'module_id' => $content_info['module_id'],
                        'bundle_id' => $content_info['bundle_id'],
                        'user_name' => $content_info['publish_user'],
                        //'ip'				=> $content_info['ip'],
                        'create_time' => TIMENOW,
                        'update_time' => TIMENOW,
                    );
                    $re   = $this->special_content->storedIntoDB($info, 'special_content', 1);
                    $this->special_content->update_special_content(array('order_id' => $re), 'special_content', " id IN({$re})");
                }
            }

            $special_id = $ret;
            $q          = $this->special_content->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id . " AND column_id = " . $colid);
            $this->special_content->update_special_content(array('count' => $q['num']), 'special_columns', " id =" . $colid);

            $count = $this->special_content->get_content_count('count(*) as num', 'special_content', " WHERE special_id = " . $special_id);
            $this->special_content->update_special_content(array('content_count' => $count['num']), 'special', " id IN({$special_id})");

            $sqll = "DELETE FROM " . DB_PREFIX . "special_content_child WHERE special_id = " . $special_id . " AND column_id = " . $colid;
            $this->db->query($sqll);

            $sql = "SELECT *
					FROM  " . DB_PREFIX . "special_content
					WHERE special_id = " . $special_id . " AND column_id = " . $colid . " ORDER BY id DESC LIMIT 5";
            $q_  = $this->db->query($sql);

            while ($row = $this->db->fetch_array($q_))
            {
                $this->special_content->create_child($row);
            }
        }
        $this->addItem($special_id);
        $this->output();
    }


    function unknow()
    {
        $this->errorOutput("此方法不存在！");
    }

}

$out    = new specialApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>
