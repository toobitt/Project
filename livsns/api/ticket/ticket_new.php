<?php
require_once './global.php';
require_once CUR_CONF_PATH . 'lib/ticket.class.php';
define('MOD_UNIQUEID', 'ticket'); //模块标识
class ticketApi extends outerReadBase
{
    public $perform_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->ticket = new ticket();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    function count()
    {

    }

    public function show()
    {
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count = $this->input['count'] ? intval($this->input['count']) : 10;
        $orderby = ' ORDER BY s.start_time ASC,s.order_id DESC ';

        $res = array();
        $condition = $this->get_condition();
        $res = $this->ticket->show($condition, $orderby, $offset, $count);

        //hg_pre($this->perform_data,0);
        if (!empty($res)) {
            if ($this->input['need_count']) {
                $tmp_val = array();
                foreach ($res as $key => $val) {
                    if ($this->input['need_star'] && $val['star_ids']) {
                        $val['star_info'] = $this->get_star_info_web($val['star_ids']);
                    }
                    switch ($val['sale_state']) {
                        case 1:
                            $val['sale_state_name'] = '预售';
                            break;
                        case 2:
                            $val['sale_state_name'] = '售票';
                            break;
                        case 3:
                            $val['sale_state_name'] = '结束';
                            break;
                    }

                    $val['title'] = strip_tags(htmlspecialchars_decode($val['title'], ENT_QUOTES));
                    $val['title'] = str_replace('&nbsp;', ' ', $val['title']);

                    //根据场次时间显示演出列表
                    if ($this->perform_data) {
                        foreach ($this->perform_data as $k => $v) {
                            if ($v['show_id'] == $key) {
                                $val['perform_show_time'] = $v['show_time'];
                                $val['perform_show_time_date'] = $v['perform_time_date'];
                                $val['perform_id'] = $v['id'];
                                $tmp_val[] = $val;
                            }
                        }
                    } else {
                        $tmp_val[] = $val;
                    }
                }
                $totalcount = $this->return_count();
                $this->addItem_withkey('total', $totalcount['total']);
                $this->addItem_withkey('data', $tmp_val);
            } else {
                foreach ($res as $key => $val) {
                    //查询明星信息
                    if ($this->input['need_star'] && $val['star_ids']) {
                        $val['star_info'] = $this->get_star_info_web($val['star_ids']);
                    }
                    switch ($val['sale_state']) {
                        case 1:
                            $val['sale_state_name'] = '预售';
                            break;
                        case 2:
                            $val['sale_state_name'] = '售票';
                            break;
                        case 3:
                            $val['sale_state_name'] = '结束';
                            break;
                    }

                    $val['title'] = strip_tags(htmlspecialchars_decode($val['title'], ENT_QUOTES));
                    $val['title'] = str_replace('&nbsp;', ' ', $val['title']);

                    //根据场次时间获取演出列表
                    if ($this->perform_data) {
                        foreach ($this->perform_data as $k => $v) {
                            if ($v['show_id'] == $key) {
                                $val['perform_show_time'] = $v['show_time'];
                                $val['perform_show_time_date'] = $v['perform_time_date'];
                                $val['perform_id'] = $v['id'];
                                $this->addItem($val);
                            }
                        }
                    } else {
                        $this->addItem($val);
                    }
                }
            }
        } else {
            $this->addItem(array());
        }
        $this->output();
    }

    public function show_web()
    {

    }

    private function get_condition()
    {
        //状态为已审核
        $condition = ' AND s.status=1 ';

        //网页端过滤结束的演出，只显示预售和在售的
        /*if($this->input['exclude_end'])
        {
            $condition .= ' AND s.sale_state IN (1,2) ';
        }
        else //手机端显示，售卖状态为预售，售卖中，和已结束
        {
            $condition .= ' AND s.sale_state IN (1,2,3) ';
        }*/

        //我收藏的演出ids
        if ($this->input['show_ids']) {
            $condition .= ' AND s.id IN (' . $this->input['show_ids'] . ')';
        }

        //根据明星id查询，明星参加的演出
        if ($this->input['star_id']) {
            $show_id = $this->get_star_trip($this->input['star_id'], 1);
            if ($show_id) {
                $condition .= ' AND s.id IN (' . $show_id . ')';
            } else {
                $this->addItem(array());
                $this->output();
            }
        }


        //分类id
        if ($this->input['sort_id']) {
            $sort_id = '';
            $sort_id = $this->ticket->child_sort($this->input['sort_id']);
            $condition .= ' AND s.sort_id IN (' . $sort_id . ')';
        }

        //栏目id
        if (($this->input['column_id'])) {
            //栏目下面排除轮转图里的演出
            if ($this->input['exclude_weight']) {
                $condition .= ' AND s.weight < ' . intval($this->input['exclude_weight']);
            }

            $sql = "SELECT show_id FROM " . DB_PREFIX . "publish_record WHERE column_id IN (" . $this->input['column_id'] . ")";
            $q = $this->db->query($sql);
            $show_ids = array();
            while ($r = $this->db->fetch_array($q)) {
                $show_ids[] = $r['show_id'];
            }
            if (!empty($show_ids)) {
                $show_ids = implode(',', $show_ids);

                $condition .= ' AND s.id IN (' . $show_ids . ')';
            } else {
                $this->addItem(array());
                $this->output();
            }
        }

        //按场次时间显示
        if ($this->input['show_perform']) {
            //$sql = "SELECT id,show_id,show_time FROM " . DB_PREFIX . "performances WHERE show_time >= " . TIMENOW . " ORDER BY show_time ASC,order_id DESC";
            $sql = "SELECT id,show_id,show_time FROM " . DB_PREFIX . "performances WHERE 1 ";
            if ($this->input['show_time']) {
                $start_time = strtotime(trim($this->input['show_time']));
                $end_time = $start_time + 86400;

                $sql .= " AND show_time >= " . $start_time . " AND show_time < " . $end_time;
            } else {
                $sql .= " AND show_time >= " . TIMENOW;
            }

            $sql .= " ORDER BY show_time ASC,order_id DESC";

            $q = $this->db->query($sql);
            $show_ids = array();
            while ($r = $this->db->fetch_array($q)) {
                if ($r['show_time']) {
                    $show_ids[$r['show_id']] = 1;

                    $week = hg_mk_weekday($r['show_time']);
                    $r['show_time1'] = date('Y年m月d号', $r['show_time']);
                    $r['show_time2'] = date('H:i', $r['show_time']);
                    $r['perform_time_date'] = $r['show_time1'] . ' ' . $week . ' ' . $r['show_time2'];

                    $this->perform_data[] = $r;
                }
            }

            if ($show_ids) {
                $show_ids = implode(',', array_keys($show_ids));
                $condition .= " AND s.id IN (" . $show_ids . ")";
            } else if ($this->input['show_time']) //日历搜索不到，返回空
            {
                $this->addItem(array());
                $this->output();
            }
        } else if ($this->input['show_time']) //日历搜索
        {
            $start_time = strtotime(trim($this->input['show_time'])); //2014-04-21
            $end_time = $start_time + 86400;

            $sql = "SELECT show_id FROM " . DB_PREFIX . "performances WHERE
			show_time >= " . $start_time . " AND show_time < " . $end_time;
            $q = $this->db->query($sql);

            $show_ids = array();
            while ($r = $this->db->fetch_array($q)) {
                $show_ids[] = $r['show_id'];
            }

            if (!empty($show_ids)) {
                $show_ids = implode(',', $show_ids);

                $condition .= ' AND s.id IN (' . $show_ids . ')';
            } else {
                $this->addItem(array());
                $this->output();
            }
        }

        //权重
        if ($this->input['weight']) {
            $condition .= ' AND s.weight >= ' . intval($this->input['weight']);
        }

        //排除权重
        /*if($this->input['exclude_weight'] && !$this->input['star_id'] && !$this->input['show_ids'] && !$this->input['sort_id'] && !$this->input['sort_time'])
        {
            $condition .= ' AND s.weight < ' . intval($this->input['exclude_weight']);
        }*/

        //搜标题
        if ($this->input['k']) {
            $condition .= ' AND s.title LIKE "%' . trim(urldecode($this->input['k'])) . '%"';
        }

        //分类搜索
        if ($this->input['sort_time']) {
            $today = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d', TIMENOW + 24 * 3600));
            $threeday = strtotime(date('y-m-d', TIMENOW + 2 * 24 * 3600));
            switch (trim($this->input['sort_time'])) {
                case time_0: //所有时间段
                    break;
                case time_1: //今天的数据
                    $condition .= " AND  s.start_time >= " . $today . " AND s.start_time < " . $tomorrow;
                    break;
                case time_2: //明天
                    $condition .= " AND  s.start_time >= " . $tomorrow . " AND s.start_time < " . $threeday;
                    break;
                case time_3: //后天
                    $fourday = strtotime(date('y-m-d', TIMENOW + 4 * 24 * 3600));
                    $condition .= " AND s.start_time >= " . $threeday . " AND s.start_time < " . $fourday;
                    break;
                case time_4: //七天内
                    $sevenday = strtotime(date('y-m-d', TIMENOW + 7 * 24 * 3600));
                    $condition .= " AND s.start_time >= " . $today . " AND s.start_time < " . $sevenday;
                    break;
                default: //所有时间段
                    break;
            }
        }

        //搜索的时候显示所有（日历搜索，收藏搜索，标题搜索）
        if ($this->input['show_time'] || $this->input['show_ids'] || $this->input['k']) {
            $condition .= ' AND s.sale_state IN (1,2,3) ';
        } else //其余情况显示未结束，售卖状态为预售和在售的
        {
            $condition .= " AND s.end_time >=" . TIMENOW;
            $condition .= ' AND s.sale_state IN (1,2) ';
        }
        return $condition;
    }

    function return_count()
    {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'show s WHERE 1 ' . $this->get_condition();
        $res = $this->db->query_first($sql);
        return $res;
    }

    public function detail()
    {
        $id = intval($this->input['id']);

        if (!$id) {
            $this->errorOutput(NOID);
        }

        $sql = 'SELECT s.*,so.name,m.index_url FROM ' . DB_PREFIX . 'show s
				LEFT JOIN ' . DB_PREFIX . 'sort so ON s.sort_id = so.id
				LEFT JOIN ' . DB_PREFIX . 'material m ON s.index_id = m.id
				WHERE s.id = ' . $id;
        $data = $this->db->query_first($sql);

        //没查询到数据返回空array
        if (!$data) {
            $this->errorOutput('数据已被删除');
            /*
            $data = array();
            $this->addItem($data);
            $this->output();
            */
        }

        //查询场次时间
        $perform_id = intval($this->input['perform_id']);
        if ($perform_id) {
            $sql = "SELECT show_time FROM " . DB_PREFIX . "performances WHERE id = " . $perform_id;
            $res = $this->db->query_first($sql);
            if ($res['show_time']) {
                $week = hg_mk_weekday($res['show_time']);
                $show_time1 = date('Y年m月d号', $res['show_time']);
                $show_time2 = date('H:i', $res['show_time']);
                $data['perform_time_date'] = $show_time1 . ' ' . $week . ' ' . $show_time2;
            }
        }

        //座位图
        if ($data['seat_map']) {
            $data['seat_map'] = unserialize($data['seat_map']);
        }
        $data['index_url'] = unserialize($data['index_url']) ? unserialize($data['index_url']) : '';
        $data['brief'] = strip_tags(htmlspecialchars_decode($data['brief'], ENT_QUOTES));
        $data['brief'] = str_replace('&nbsp;', ' ', $data['brief']);

        $data['title'] = strip_tags(htmlspecialchars_decode($data['title'], ENT_QUOTES));
        $data['title'] = str_replace('&nbsp;', ' ', $data['title']);

        switch ($data['sale_state']) {
            case 1:
                $data['sale_state_name'] = '预售';
                $data['sale'] = 0;
                $data['sale_tip'] = $this->settings['sale_tip']['sale_1'];
                break;
            case 2:
                $data['sale_state_name'] = '售票';
                $data['sale'] = 1;
                break;
            case 3:
                $data['sale_state_name'] = '结束';
                $data['sale'] = 0;
                $data['sale_tip'] = $this->settings['sale_tip']['sale_3'];
                break;
        }

        $data['start_time'] = date('Y-m-d H:i:s', $data['start_time']);
        $data['end_time'] = date('Y-m-d H:i:s', $data['end_time']);

        if ($data['tel']) {
            $data['tel'] = unserialize($data['tel']);
        }
        $data['sell_tel'] = '';
        if (count($data['tel']) && is_array($data['tel'])) {
            foreach ($data['tel'] as $key => $val) {
                if (strtotime($val['start_time']) < TIMENOW && strtotime($val['end_time']) > TIMENOW) {
                    $data['sell_tel'][] = $data['tel'][$key];
                }
            }
        }

        //查询场馆信息
        if ($data['venue_id']) {
            $venue_info = array();
            $sql = "SELECT venue_name,venue_address FROM " . DB_PREFIX . "venue WHERE id = " . $data['venue_id'];
            $venue_info = $this->db->query_first($sql);
            if (!empty($venue_info)) {
                $data['venue'] = $venue_info['venue_name'];
                $data['address'] = $venue_info['venue_address'];
            }
        }

        //查询演出明星信息
        if ($data['star_ids']) {
            $data['star_info'] = $this->get_star_info($data['star_ids']);
        }

        if ($this->input['need_content']) {
            $flag = intval($this->input['flag']);
            $data['content'] = $this->ticket->get_content($id, $flag);
        }
        //hg_pre($data,0);
        $this->addItem($data);
        $this->output();
    }


    /**
     * 获取演出介绍详情
     * Enter description here ...
     */
    public function get_show_content()
    {
        $show_id = intval($this->input['id']);
        if (!$show_id) {
            $this->errorOutput('演出id不存在');
        }
        $flag = intval($this->input['flag']);
        $data = $this->ticket->get_content($show_id, $flag);
        //hg_pre($data,0);

        if (!$data) {
            $data = array();
        }
        $this->addItem($data);
        $this->output();
    }

    //获取场次信息
    public function get_perform()
    {
        $show_id = intval($this->input['show_id']);
        if (!$show_id) {
            $this->errorOutput('演出id不存在');
        }

        $sql = "SELECT id,show_time FROM " . DB_PREFIX . "performances WHERE show_id = " . $show_id . " ORDER BY show_time ASC";
        $q = $this->db->query($sql);
        $first_perform_id = '';
        while ($r = $this->db->fetch_array($q)) {
            if (time() > $r['show_time']) {
                continue;
            }
            if (!$first_perform_id) {
                $first_perform_id = $r['id'];
            }
            if ($r['show_time']) 
            {
            	$r['showtime'] = $r['show_time'];
                $week = hg_mk_weekday($r['show_time']);
                $r['show_time1'] = date('m月d号', $r['show_time']);
                $r['show_time2'] = date('H:i', $r['show_time']);
                $r['show_time'] = $r['show_time1'] . ' ' . $week . ' ' . $r['show_time2'];
            }
            unset($r['show_time1'], $r['show_time2']);
            $data['perform'][] = $r;
        }

        //查询票票数
        if ($first_perform_id) {
            $data['ticket_info'] = $this->get_ticket_info($first_perform_id);
        }

        if (!$data) {
            $data = array();
        }
        foreach ($data as $key => $val) {
            $this->addItem_withkey($key, $val);
        }
        //hg_pre($data,0);

        $this->output();
    }

    //获取场次下票务信息
    public function get_ticket()
    {
        $perform_id = intval($this->input['perform_id']);
        if (!$perform_id) {
            $this->errorOutput('场次id不存在');
        }
        $data = $this->get_ticket_info($perform_id);
        //hg_pre($data,0);
        if (!$data) {
            $data = array();
        }
        $this->addItem($data);
        $this->output();
    }

    //获取明星信息
    public function get_star()
    {
        $star_ids = $this->input['star_ids'];
        if (!$star_ids) {
            $this->errorOutput(NOID);
            //return array();
        }
        $data = $this->get_star_info($star_ids);
        //hg_pre($data,0);
        if (empty($data)) {
            $data = array();
            $this->addItem($data);
        } else {
            foreach ($data as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    //获取栏目信息
    public function get_column()
    {
        $sql = "SELECT column_id,title FROM " . DB_PREFIX . "column WHERE column_id !='' ORDER BY order_id DESC";
        $q = $this->db->query($sql);
        $data = array();
        while ($r = $this->db->fetch_array($q)) {
            $data[] = $r;
        }

        if (empty($data)) {
            $data = array();
        }

        $this->addItem($data);
        $this->output();
    }

    //网页端获取栏目
    public function get_column_web()
    {
        $sql = "SELECT column_id,title FROM " . DB_PREFIX . "column WHERE column_id !='' ORDER BY order_id DESC";
        $q = $this->db->query($sql);
        $data = array();
        while ($r = $this->db->fetch_array($q)) {
            $data[$r['column_id']] = $r;
        }

        if (empty($data)) {
            $data = array();
        }

        foreach ($data as $key => $val) {
            $this->addItem_withkey($key, $val);
        }
        $this->output();
    }

    //获取场馆信息
    public function get_venue_info()
    {
        $id = $this->input['venue_id'];

        if (!$id) {
            $this->addItem(array());
            $this->output();
        }

        $sql = "SELECT * FROM " . DB_PREFIX . "venue WHERE id = " . $id;
        $data = $this->db->query_first($sql);

        $this->addItem($data);
        $this->output();
    }

    //手机端显示
    public function get_show_date_mobile()
    {
        $start_time = strtotime($this->input['start_time']);
        $end_time = strtotime($this->input['end_time']);

        $sql = "SELECT show_id,show_time FROM " . DB_PREFIX . "performances WHERE 1 ";

        if ($start_time) {
            $sql .= " AND show_time >= " . $start_time;
        }

        if ($end_time) {
            $sql .= " AND show_time <= " . $end_time;
        }
        $q = $this->db->query($sql);

        $show_ids = array();
        while ($r = $this->db->fetch_array($q)) {
            if ($r['show_time']) {
                $k = date('Y-m-d', $r['show_time']);
                $data[$k] = 1;
            }
        }

        if (!empty($data)) {
            $data_tmp = array_keys($data);
            foreach ($data_tmp as $k => $v) {
                $this->addItem($v);
            }
        }
        $this->output();
    }

    //日历显示是否有演出
    public function get_show_date()
    {
        $start_time = strtotime($this->input['start_time']);
        $end_time = strtotime($this->input['end_time']);

        $sql = "SELECT show_id,show_time FROM " . DB_PREFIX . "performances WHERE 1 ";

        if ($start_time) {
            $sql .= " AND show_time >= " . $start_time;
        }

        if ($end_time) {
            $sql .= " AND show_time <= " . $end_time;
        }
        $q = $this->db->query($sql);

        $show_ids = array();
        while ($r = $this->db->fetch_array($q)) {
            if ($r['show_time']) {
                $show_ids[$r['show_id']] = 1;
                $k = date('Y-m-d', $r['show_time']);
                $data[$k][$r['show_id']] = 1;
            }
        }

        if ($show_ids) {
            $show_ids = implode(',', array_keys($show_ids));

            $sql = "SELECT id,title FROM " . DB_PREFIX . "show WHERE status =1 AND id IN (" . $show_ids . ")";
            $q = $this->db->query($sql);
            while ($r = $this->db->fetch_array($q)) {
                $show_info[$r['id']] = $r['title'];
            }

            if ($show_info) {
                foreach ($data as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($show_info[$k]) {
                            $tmp[$key][$k] = $show_info[$k];
                        }
                    }
                }
            }
        }
        //hg_pre($tmp);
        $data = array();
        if (!$tmp) {
            $data = array();
        } else {
            $data = $tmp;
        }

        //hg_pre($data,0);
        $this->addItem($data);
        $this->output();
    }

    /**
     * 根据场次id查询场次下所有票务信息
     * Enter description here ...
     * @param int $perform_id
     */
    public function get_ticket_info($perform_id)
    {
        if (!$perform_id) {
            return false;
        }
        $sql = "SELECT id,price,price_notes,goods_total_left FROM " . DB_PREFIX . "price WHERE perform_id = " . $perform_id . " ORDER BY price_type ASC";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q)) {
            $ticket_info[] = $r;
        }
        return $ticket_info;
    }

    /**
     * 获取明星信息(网页端)
     * Enter description here ...
     * @param unknown_type $star_ids
     */
    public function get_star_info_web($star_ids)
    {
        if (!$star_ids) {
            return FALSE;
        }

        //查询演出中的明星信息
        $sql = "SELECT id,name FROM " . DB_PREFIX . "star WHERE id IN (" . $star_ids . ")";
        $q = $this->db->query($sql);
        $star_info = array();
        while ($r = $this->db->fetch_array($q)) {
            $star_info[$r['id']] = $r;
        }

        return $star_info;
    }

    /**
     * 获取明星信息和行程
     * Enter description here ...
     * @param unknown_type $star_ids
     */
    public function get_star_info($star_ids)
    {
        if (!$star_ids) {
            return FALSE;
        }

        //查询演出中的明星信息
        $sql = "SELECT id,name,logo FROM " . DB_PREFIX . "star WHERE id IN (" . $star_ids . ")";
        $q = $this->db->query($sql);
        $star_info = array();
        while ($r = $this->db->fetch_array($q)) {
            if ($r['logo']) {
                $r['logo'] = unserialize($r['logo']);
            }
            $star_info[$r['id']] = $r;
        }

        //查询明星行程
        /*$sql = "SELECT star_id FROM " . DB_PREFIX . "star_trip WHERE show_end_time > " . TIMENOW . " AND star_id IN (" . $star_ids . ")";
        $q = $this->db->query($sql);
        $star_trip = array();
        while ($r = $this->db->fetch_array($q))
        {
            if(!$r['star_id'])
            {
                continue;
            }
            if($star_trip[$r['star_id']])
            {
                $star_trip[$r['star_id']] += 1;
            }
            else
            {
                $star_trip[$r['star_id']] = 1;
            }
        }*/

        if (!empty($star_info)) {
            //查询明星行程
            $star_trip = $this->get_star_trip($star_ids);

            foreach ($star_info as $k => $v) {
                if ($star_trip[$k]) {
                    $v['trip_num'] = $star_trip[$k];
                } else {
                    $v['trip_num'] = 0;
                }
                $star_info_tmp[] = $v;
            }
        }
        return $star_info_tmp;
    }


    /**
     * 根据明星id查询行程和参加的演出id
     * Enter description here ...
     * @param string $star_ids
     * @param int $type 存在为根据明星 id 查询演出id,不存在为查询行程
     */
    public function get_star_trip($star_ids, $type = '')
    {
        //查询明星行程
        $sql = "SELECT t.show_id,t.star_id,s.status FROM " . DB_PREFIX . "star_trip t LEFT JOIN
				" . DB_PREFIX . "show s
					ON t.show_id = s.id 
				WHERE t.show_end_time > " . TIMENOW . " AND t.star_id IN (" . $star_ids . ")";

        $q = $this->db->query($sql);
        if (!$type) {
            $star_trip = array();
            while ($r = $this->db->fetch_array($q)) {
                if (!$r['star_id'] || $r['status'] != 1) {
                    continue;
                }
                if ($star_trip[$r['star_id']]) {
                    $star_trip[$r['star_id']] += 1;
                } else {
                    $star_trip[$r['star_id']] = 1;
                }
            }
            return $star_trip;
        } else {
            $show_id = '';
            while ($r = $this->db->fetch_array($q)) {
                $show_id .= $r['show_id'] . ',';
            }
            $show_ids = rtrim($show_id, ',');
            return $show_ids;
        }
    }
}

$out = new ticketApi();
if (!method_exists($out, $_INPUT['a'])) {
    $action = 'show';
} else {
    $action = $_INPUT['a'];
}
$out->$action();
?>