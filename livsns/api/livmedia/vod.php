<?php

define('MOD_UNIQUEID', 'livmedia');
define('ROOT_PATH', '../../');
define('CUR_CONF_PATH', './');
require(ROOT_PATH . "global.php");
require(CUR_CONF_PATH . "lib/functions.php");
require_once(ROOT_PATH . 'lib/class/curl.class.php');

class vod extends outerReadBase
{

    private $members;

    public function __construct()
    {
        parent::__construct();
        require ROOT_PATH . 'lib/class/members.class.php';
        $this->members = new members();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
        $offset    = $this->input['offset'] ? intval(urldecode($this->input['offset'])) : 0;
        $count     = $this->input['count'] ? intval(urldecode($this->input['count'])) : 15;
        $limit     = " limit {$offset}, {$count}";
        $condition = ' AND v.status=2';

        $condition .= $this->get_condition();
        //根据发布栏目
        if ($this->input['pub_column_id'])
        {
            $condition .= " GROUP BY v.id";
            $sql = "SELECT v.*, vs.name AS sort_name,vs.color AS vod_sort_color  
                    FROM " . DB_PREFIX . "vodinfo v 
                    LEFT JOIN " . DB_PREFIX . "vod_media_node vs 
                        ON v.vod_sort_id = vs.id
                    LEFT JOIN " . DB_PREFIX . "pub_column pc
                        ON v.id = pc.aid     
                    WHERE 1 " . $condition . $orderby . $limit;
        }
        else
        {
            $sql = "SELECT v.*, vs.name AS sort_name FROM " . DB_PREFIX . "vodinfo v LEFT JOIN " . DB_PREFIX . "vod_media_node vs ON v.vod_sort_id = vs.id WHERE 1 " . $condition . "  ORDER BY v.video_order_id DESC, v.id DESC " . $limit;
        }

        $q        = $this->db->query($sql);
        $this->setXmlNode('vod', 'item');
        $vod_info = array();
        while ($r        = $this->db->fetch_array($q))
        {
            if ($r['isfile'])
            {
                $r['start'] = 0;
            }
            $r['vod_sort_color'] = $this->settings['video_upload_type_attr'][intval($r['vod_leixing'])]['color'];
            $r['vod_leixing']    = $this->settings['video_upload_type'][$r['vod_leixing']];
            if ($r['sort_name'])
            {
                $r['vod_sort_id'] = $r['sort_name'];
            }
            else
            {
                $r['vod_sort_id'] = $r['vod_leixing'];
            }

            $collects = unserialize($r['collects']);
            if ($collects)
            {
                $r['collects'] = $collects;
            }
            else
            {
                $r['collects'] = '';
            }

            $r['img_info']       = unserialize($r['img_info']);
            $r['column_url']     = unserialize($r['column_url']);
            $r['column_id']      = unserialize($r['column_id']);
            $r['video_filename'] = str_replace('.mp4', '', $r['video_filename']);
            $r['videoaddr']      = array(
                'default' => array(
                    'f4m' => $r['hostwork'] . '/' . $r['video_path'] . MAINFEST_F4M,
                    'm3u8' => $r['hostwork'] . '/' . $r['video_path'] . $r['video_filename'] . '.m3u8',
                ),
            );

            $r['vodurl'] = $r['hostwork'] . '/' . $r['video_path'];
            unset($r['transcode_server'], $r['source_path'], $r['source_filename'], $r['etime'], $r['img']);

            $rgb = $r['bitrate'] / 100;

            if ($rgb < 10)
            {
                $r['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
            }
            else
            {
                $r['bitrate_color'] = $this->settings['bitrate_color'][9];
            }


            if ($r['starttime'])
            {
                $r['starttime'] = date('Y-m-d', $r['starttime']);
            }
            else
            {
                $r['starttime'] = '';
            }

            $r['duration']    = time_format($r['duration']);
            $r['status']      = $this->settings['video_upload_status'][$r['status']];
            $r['create_time'] = date('Y-m-d H:i', $r['create_time']);
            $r['update_time'] = date('Y-m-d H:i', $r['update_time']);
            $r['right_info']  = addslashes($r['right_info']);
            if ($r['catalog'])
            {
                $r['catalog'] = unserialize($r['catalog']);
            }
            $this->addItem($r);
        }
        $this->output();
    }

    public function updateclick()
    {
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput(NOID);
        }

        $sql = 'UPDATE ' . DB_PREFIX . 'vodinfo SET click_count = (click_count + 1) WHERE id = ' . $id;
        $q   = $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

    public function detail()
    {
        $id       = $this->input['id'];
        $ad_group = $this->input['ad_group'];
        if ($id && $id != 'latest')
        {
            if (is_array($id))
            {
                $id = implode(',', $id);
                $id = str_replace(',,', ',', $id);
            }
            $sql = 'SELECT * FROM ' . DB_PREFIX . 'vodinfo WHERE status = 2 AND id IN( ' . $id . ')';
        }
        else
        {
            $sql = 'SELECT * FROM ' . DB_PREFIX . 'vodinfo WHERE status = 2  ORDER BY video_order_id DESC limit 0,1';
        }

        $q           = $this->db->query($sql);
        $first_frame = '';
        while ($r           = $this->db->fetch_array($q))
        {
            $r['vodurl'] = $r['hostwork'] . '/' . $r['video_path'];
            if (!$first_frame)
            {
                $first_frame = $this->get_first_frame($r['id']);
            }
            $r['img_info']    = unserialize($r['img_info']);
        		foreach((array)$first_frame[0] as $k => $v)
            {
            		if(strpos($v, 'fail'))
            		{
            			$first_frame[0][$k] = $r['img_info']['host'].$r['img_info']['dir'].$r['img_info']['filepath'].$r['img_info']['filename'];
            		}
            }
            $r['first_frame'] = $first_frame;
            if ($r['isfile'])
            {
                $r['start'] = 0;
            }
            $r['videoaddr'] = array(
                'default' => array(
                    'f4m' => $r['hostwork'] . '/' . $r['video_path'] . MAINFEST_F4M,
                    'm3u8' => $r['hostwork'] . '/' . $r['video_path'] . str_replace('.mp4', '.m3u8', $r['video_filename']),
                ),
            );

            $more_vodurl = array();
            if ($r['clarity'] && $r['is_morebitrate'])
            {
                $clarity_unique_ids = unserialize($r['clarity']);
                foreach ($clarity_unique_ids AS $k => $v)
                {
                    $target_dir_info = pathinfo(rtrim($r['video_path'], '/'));
                    if (substr($r['video_filename'], -5) == '.m3u8')
                    {
                        $new_target_dir = $target_dir_info['dirname'] . $v;
                        $more_vodurl[]  = $r['hostwork'] . $new_target_dir;
                    }
                    else
                    {
                        $new_target_dir = $target_dir_info['dirname'] . '/' . $v . '_' . $target_dir_info['basename'];
                        $more_vodurl[]  = $r['hostwork'] . '/' . $new_target_dir . '/';
                    }
                }
            }
            //多码流视频地址
            $r['more_vodurl'] = $more_vodurl;
            $r['column_id']   = @unserialize($r['column_id']);
            $r['column_url']  = @unserialize($r['column_url']);
            if ($r['catalog'])
            {
                $r['catalog'] = unserialize($r['catalog']);
            }
            if ($this->user['user_id'] && $r['id'] && $this->input['iscreditsrule'])
            {
                $credit_rules            = $this->callMemberCreditsRules($this->user['user_id'], APP_UNIQUEID, MOD_UNIQUEID, 0, $r['id']);
                /*                 * 积分文案处理* */
                $r['copywriting_credit'] = $this->members->copywriting_credit(array($credit_rules));
            }
            if ($this->input['ad_group'])
            {
                $r['colid'] = $this->input['column_id'];;
                $r['ad'] = $this->getAds($this->input['ad_group'], $r);
            }
            $this->addItem($r);
        }
        $this->output();
    }

    //获取视频的第一帧图片
    public function get_first_frame($vodid)
    {
        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('id', $vodid);
        $curl->addRequestData('count', 1);
        $curl->addRequestData('stime', 0);
        $curl->addRequestData('width', 640);
        $ret  = $curl->request('snap.php');
        return $ret;
    }

    public function count()
    {
        $condition = $this->get_condition();
        $sql       = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "vodinfo v WHERE 1 AND v.status=2 " . $condition;
        $return    = $this->db->query_first($sql);
        $this->addItem($return);
        $this->output();
    }

    //获取分页的一些参数
    public function get_page_data()
    {
        $condition = $this->get_condition();
        $sql       = "SELECT COUNT(id) AS total FROM " . DB_PREFIX . "vodinfo v WHERE 1 " . $condition;
        $ret       = $this->db->query_first($sql);
        $total_num = $ret['total']; //总的记录数
        $page_num  = $this->input['count'] ? intval($this->input['count']) : 20;
        //总页数
        if (intval($total_num % $page_num) == 0)
        {
            $return['total_page'] = intval($total_num / $page_num);
        }
        else
        {
            $return['total_page'] = intval($total_num / $page_num) + 1;
        }
        $return['total_num']    = $total_num; //总的记录数
        $return['page_num']     = $page_num; //每页显示的个数
        $return['current_page'] = $this->input['pp']; //当前页码
        $this->addItem($return);
        $this->output();
    }

    public function get_vod_info()
    {
        $condition = $this->get_condition();
        $field     = $this->input['field'] ? trim($this->input['field']) : ' * ';
        $offset    = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count     = $this->input['count'] ? intval($this->input['count']) : 20;
        $limit     = " LIMIT {$offset}, {$count}";
        $sql       = "SELECT {$field} FROM " . DB_PREFIX . "vodinfo v WHERE 1 " . $condition . "  ORDER BY v.video_order_id DESC, v.id DESC " . $limit;
        $q         = $this->db->query($sql);
        $vod_info  = array();
        while ($r         = $this->db->fetch_array($q))
        {
            $r['vod_sort_color'] = $this->settings['video_upload_type_attr'][intval($r['vod_leixing'])]['color'];
            $r['vod_leixing']    = $this->settings['video_upload_type'][$r['vod_leixing']];

            if ($r['sort_name'])
            {
                $r['vod_sort_id'] = $r['sort_name'];
            }
            else
            {
                $r['vod_sort_id'] = $r['vod_leixing'];
            }

            $collects = unserialize($r['collects']);
            if ($collects)
            {
                $r['collects'] = $collects;
            }
            else
            {
                $r['collects'] = '';
            }

            $r['img_info'] = unserialize($r['img_info']);
            $r['img']      = $r['img_info']['host'] . $r['img_info']['dir'] . $r['img_info']['filepath'] . $r['img_info']['filename'];

            $rgb = $r['bitrate'] / 100;

            if ($rgb < 10)
            {
                $r['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
            }
            else
            {
                $r['bitrate_color'] = $this->settings['bitrate_color'][9];
            }

            $r['vodurl'] = $r['hostwork'] . '/' . $r['video_path'];

            if ($r['starttime'])
            {
                $r['starttime'] = '(' . date('Y-m-d', $r['starttime']) . ')';
            }
            else
            {
                $r['starttime'] = '';
            }

            $r['start']          = $r['start'] + 1;
            $r['etime']          = intval($r['duration']) + intval($r['start']);
            $r['toff']           = $r['duration'];
            $r['duration_num']   = $r['duration'];
            $r['duration']       = time_format($r['duration']);
            $r['status_display'] = intval($r['status']);
            $r['status']         = $this->settings['video_upload_status'][$r['status']];
            $r['create_time']    = date('Y-m-d H:i', $r['create_time']);
            $r['update_time']    = date('Y-m-d H:i', $r['update_time']);
            $r['video_url']      = $r['hostwork'] . '/' . $r['video_path'] . MAINFEST_F4M;
            $r['video_m3u8']     = $r['hostwork'] . '/' . $r['video_path'] . str_replace('.mp4', '.m3u8', $r['video_filename']);
            $this->addItem($r);
        }
        $this->output();
    }

    //获取多个视频信息
    public function get_videos()
    {
        if (!$this->input['id'])
        {
            return false;
        }
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" . $this->input['id'] . ")";
        $q   = $this->db->query($sql);
        while ($r   = $this->db->fetch_array($q))
        {
            $img                 = unserialize($r['img_info']);
            $r['source_img']     = $img['host'] . $img['dir'] . $img['filepath'] . $img['filename'];
            $r['video_url']      = $r['hostwork'] . '/' . $r['video_path'] . MAINFEST_F4M;
            $filename            = str_replace('.mp4', '', $r['video_filename']);
            $r['video_url_m3u8'] = $r['hostwork'] . '/' . $r['video_path'] . $filename . '.m3u8';
            $r['status_format']  = $this->settings['video_upload_status'][$r['status']];
            $video[$r['id']]     = $r;
        }
        $this->addItem($video);
        $this->output();
    }

    public function get_video_extend()
    {
        $id = intval($this->input['id']);
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $sql       = 'SELECT * FROM ' . DB_PREFIX . 'vod_extend WHERE vodinfo_id=' . $id;
        $videoinfo = $this->db->query_first($sql);
        $this->addItem($videoinfo);
        $this->output();
    }

    public function get_split_videos()
    {
        if (!$this->input['id'])
        {
            return false;
        }
        $video = array();
        $sql   = "SELECT v.*,vm.start_time AS split_start,vm.duration AS split_duration FROM " . DB_PREFIX . "vodinfo v LEFT JOIN " . DB_PREFIX . "vod_mark_video vm ON vm.vodinfo_id = v.id WHERE v.marktype = 0 AND v.original_id = '" . $this->input['id'] . "' ORDER BY v.video_order_id DESC, v.id DESC ";

        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $r['column_id']        = $r['column_id'] ? unserialize($r['column_id']) : '';
            $r['split_end']        = intval($r['split_start']) + intval($r['split_duration']);
            $r['duration_format']  = time_format($r['duration']);
            $r['transcode_server'] = unserialize($r['transcode_server']);
            $r['img_info']         = hg_fetchimgurl(unserialize($r['img_info']), 80, 60);
            $video[]               = $r;
        }
        $this->addItem($video);
        $this->output();
    }
    
    public function get_split_live_videos()
    {
        if (!$this->input['live_id'])
        {
            return false;
        }
        $video = array();
        $sql   = "SELECT v.*,vm.start_time AS split_start,vm.duration AS split_duration FROM " . DB_PREFIX . "vodinfo v LEFT JOIN " . DB_PREFIX . "vod_mark_video vm ON vm.vodinfo_id = v.id WHERE v.marktype = 1 AND v.original_id = '" . $this->input['live_id'] . "' ORDER BY v.video_order_id DESC, v.id DESC ";

        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $r['column_id']        = $r['column_id'] ? unserialize($r['column_id']) : '';
            $r['split_end']        = intval($r['split_start']) + intval($r['split_duration']);
            $r['duration_format']  = time_format($r['duration']);
            $r['transcode_server'] = unserialize($r['transcode_server']);
            $r['img_info']         = hg_fetchimgurl(unserialize($r['img_info']), 80, 60);
            $video[]               = $r;
        }
        $this->addItem($video);
        $this->output();
    }

    public function get_video_status()
    {
        if (!$this->input['id'])
        {
            return false;
        }

        //查询出正在转码的视频
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id in (" . $this->input['id'] . ")";
        $q   = $this->db->query($sql);
        while ($row = $this->db->fetch_array($q))
        {
            $row['transcode_server'] = $row['transcode_server'] ? @unserialize($row['transcode_server']) : '';
            if ($row['transcode_server'] && is_array($row['transcode_server']))
            {
                $data[] = $row;
            }
        }

        $return_info = array(); //用于存放视频转码进度信息信息的数组
        //根据视频id去请求转码进度
        if ($data && !empty($data))
        {
            $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
            foreach ($data as $v)
            {
                $curl->setSubmitType('get');
                $curl->initPostData();
                $curl->addRequestData('id', $v['id']);
                $curl->addRequestData('a', 'get_transcode_status');
                $curl->addRequestData('host', $v['transcode_server']['host']);
                $curl->addRequestData('port', $v['transcode_server']['port']);
                $ret = $curl->request('video_transcode.php');
                if ($ret['return'] && $ret['return'] == 'fail')
                {
                    $info['status_data'][] = array('transcode_percent' => 100, 'id' => $v['id'], 'status' => $v['status']);
                }
                else
                {
                    $ret['status']         = $v['status'];
                    $info['status_data'][] = $ret;
                }
            }
            $this->addItem($info);
        }
        else
        {
            $this->addItem($info);
        }
        $this->output();
    }

    private function get_condition()
    {
        $condition = "";
        if ($this->input['vod_sort_id'])
        {
            $sql = " SELECT childs, fid FROM " . DB_PREFIX . "vod_media_node WHERE  id = '" . $this->input['vod_sort_id'] . "'";
            $arr = $this->db->query_first($sql);
            if ($arr)
            {
                $condition .= " AND v.vod_sort_id IN (" . $arr['childs'] . ")";
            }
        }

        ####增加权限控制 用于显示####
        if ($this->input['id'])
        {
            $condition .= " AND v.id in (" . ($this->input['id']) . ")";
        }

        if ($this->input['user_name'])
        {
            $condition .= " AND v.addperson = '" . $this->input['user_name'] . "' ";
        }

        if ($this->input['user_id'])
        {
            $condition .= " AND v.user_id = '" . $this->input['user_id'] . "' ";
        }

        if ($this->input['title'] || trim(($this->input['title'])) == '0')
        {
            $condition .= ' AND v.title LIKE "%' . ($this->input['title']) . '%"';
        }

        if ($this->input['k'] || trim(($this->input['k'])) == '0')
        {
            $condition .= ' AND v.title LIKE "%' . ($this->input['k']) . '%"';
        }

        if ($this->input['status'])
        {
            $condition .= " AND v.status IN (" . ($this->input['status']) . ")";
        }

        if ($this->input['is_forcecode'])
        {
            $condition .= " AND v.is_forcecode = 1 ";
        }

        if ($this->input['start_time'])
        {
            $start_time = strtotime(trim(($this->input['start_time'])));
            $condition .= " AND v.create_time >= '" . $start_time . "'";
        }

        if ($this->input['end_time'])
        {
            $end_time = strtotime(trim(($this->input['end_time'])));
            $condition .= " AND v.create_time <= '" . $end_time . "'";
        }

        //权重
        if ($this->input['start_weight'] && $this->input['start_weight'] != -1)
        {
            $condition .= " AND v.weight >=" . $this->input['start_weight'];
        }
        if ($this->input['end_weight'] && $this->input['end_weight'] != -2)
        {
            $condition .= " AND v.weight <= " . $this->input['end_weight'];
        }

        if ($this->input['date_search'])
        {
            $today    = strtotime(date('Y-m-d'));
            $tomorrow = strtotime(date('y-m-d', TIMENOW + 24 * 3600));
            switch (intval($this->input['date_search']))
            {
                case 1://所有时间段
                    break;
                case 2://昨天的数据
                    $yesterday     = strtotime(date('y-m-d', TIMENOW - 24 * 3600));
                    $condition .= " AND  v.create_time > '" . $yesterday . "' AND v.create_time < '" . $today . "'";
                    break;
                case 3://今天的数据
                    $condition .= " AND  v.create_time > '" . $today . "' AND v.create_time < '" . $tomorrow . "'";
                    break;
                case 4://最近3天
                    $last_threeday = strtotime(date('y-m-d', TIMENOW - 2 * 24 * 3600));
                    $condition .= " AND  v.create_time > '" . $last_threeday . "' AND v.create_time < '" . $tomorrow . "'";
                    break;
                case 5://最近7天
                    $last_sevenday = strtotime(date('y-m-d', TIMENOW - 6 * 24 * 3600));
                    $condition .= " AND  v.create_time > '" . $last_sevenday . "' AND v.create_time < '" . $tomorrow . "'";
                    break;
                default://所有时间段
                    break;
            }
        }
        if ($this->input['pub_column_id'])
        {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishconfig = new publishconfig();
            $pub_column_id = $publishconfig->get_column_by_ids('id, childs', $this->input['pub_column_id']);
            foreach ((array) $pub_column_id as $k => $v)
            {
                $column_id[] = $v['childs'];
            }
            $column_id = implode("','", $column_id);
            if ($column_id)
            {
                $condition .= " AND pc.column_id IN('" . $column_id . "')";
            }
        }
        return $condition;
    }

    //接受外部数据
    public function insert_data()
    {
        $input = $this->input;
        $data  = array(
            'title' => $input['title'],
            'user_id' => $input['user_id'],
            'addperson' => $input['addperson'],
            'subtitle' => $input['subtitle'],
            'author' => $input['author'],
            'keywords' => $input['keywords'],
            'comment' => $input['comment'],
            'vod_sort_id' => $input['vod_sort_id'],
            'vod_leixing' => $input['vod_leixing'],
            'delay_time' => $input['delay_time'],
            'starttime' => $input['starttime'],
            'channel_id' => $input['channel_id'],
            'duration' => $input['duration'],
            'column_id' => $input['column_id'],
            'create_time' => $input['create_time'],
            'update_time' => $input['update_time'],
        );
        $sql   = 'INSERT INTO ' . DB_PREFIX . 'vodinfo SET ';
        $space = '';
        foreach ($data as $key => $value)
        {
            $sql .= $space . $key . '="' . $value . '"';
            $space = ',';
        }
        $this->db->query($sql);
        $insert_id = $this->db->insert_id();
        $sql       = "UPDATE " . DB_PREFIX . "vodinfo SET video_order_id = " . $insert_id . " WHERE id = " . $insert_id;
        $this->db->query($sql);
        $this->addItem(array('id' => $insert_id));
        $this->output();
    }

    //获取制定节点的子节点
    public function get_childs_nodes()
    {
        $id = $this->input['id'];
        if (!$id)
        {
            if ($this->user['group_type'] > MAX_ADMIN_TYPE && $this->user['prms']['app_prms']['livmedia']['nodes'])
            {
                $id = implode(',', $this->user['prms']['app_prms']['livmedia']['nodes']);
            }
        }

        $cond = '';
        if ($id)
        {
            $cond = " AND id IN (" . $id . ")";
        }

        $sql            = 'SELECT id,childs FROM ' . DB_PREFIX . 'vod_media_node WHERE 1 ' . $cond;
        $query          = $this->db->query($sql);
        $authnode_array = array();
        while ($row            = $this->db->fetch_array($query))
        {
            $authnode_array[$row['id']] = explode(',', $row['childs']);
        }
        //算出所有允许的节点
        $auth_nodes = array();
        foreach ($authnode_array AS $k => $v)
        {
            $auth_nodes = array_merge($auth_nodes, $v);
        }
        $this->addItem($auth_nodes);
        $this->output();
    }

    //接受外部更改视频截图
    public function update_img()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $sql = "UPDATE " . DB_PREFIX . "vodinfo SET img_info = '" . $this->input['img_info'] . "' WHERE id = '" . $this->input['id'] . "'";
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

    //统计视频
    public function numOfVideos()
    {
        $condition = '';
        if ($this->input['date'])
        {
            $start_time = strtotime($this->input['date']);
            $end_time   = $start_time + 24 * 3600 * intval(date('t', $start_time));
            $condition  = " AND create_time >= '" . $start_time . "' AND create_time <= '" . $end_time . "'";
        }

        $sql    = "SELECT addperson AS user_name,user_id,count(*) AS total FROM " . DB_PREFIX . "vodinfo WHERE 1 " . $condition . " GROUP BY user_id ";
        $q      = $this->db->query($sql);
        $videos = array();
        while ($r      = $this->db->fetch_array($q))
        {
            $this->addItem($r);
        }
        $this->output();
    }

    /**
     *
     * 用户观看直播增加积分 ...
     */
    private function callMemberCreditsRules($member_id, $appUniqueid, $modUniqueid, $sortId, $contentId)
    {
        $this->members->Initoperation();
        $this->members->Setoperation(APP_UNIQUEID);
        return $this->members->get_credit_rules($member_id, $appUniqueid, $modUniqueid, $sortId, $contentId);
    }

}

$out    = new vod();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'show';
}
$out->$action();
?>