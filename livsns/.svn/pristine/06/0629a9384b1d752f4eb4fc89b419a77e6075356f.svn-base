<?php

class get_content extends InitFrm
{

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function check_field($check_field, $field)
    {
        $f    = explode(',', $check_field);
        $fall = explode(',', $field);
        foreach ($f as $k => $v)
        {
            if (!in_array($v, $fall))
            {
                return false;
            }
        }
        return true;
    }

    public function get_content($field, $tablename, $array_field, $condition, $offset, $count, $data = array())
    {
        $result          = array();
        $sql             = "SELECT " . $field . " FROM " . DB_PREFIX . $tablename . " WHERE 1 " . $condition . " LIMIT " . $offset . " , " . $count;
        $info            = $this->db->query($sql);
        if ($array_field_arr = explode(',', $array_field))
        {
            while ($row = $this->db->fetch_array($info))
            {
                $tag = true;
                foreach ($array_field_arr as $v)
                {
                    $row[$v] = unserialize($row[$v]) ? unserialize($row[$v]) : $row[$v];
                    if (0 && !empty($data['indexpic']))
                    {
                        if ($v == 'pic' && !empty($row[$v]))
                        {
                            $indexpic = hg_fetchimgurl($data['indexpic']);
                            $nowpic   = hg_fetchimgurl($row[$v]);
                            if ($indexpic == $nowpic)
                            {
                                $tag = false;
                            }
                        }
                    }
                }
                if ($tag)
                {
                    $result[] = $row;
                }
            }
        }
        else
        {
            while ($row = $this->db->fetch_array($info))
            {
                $result[] = $row;
            }
        }
        $result = to_htmlspecialchars_decode($result);
        return $result;
    }

    public function get_content_detail($field, $tablename, $array_field, $id)
    {
        $result = array();
        $sql    = "SELECT " . $field . " FROM " . DB_PREFIX . $tablename . " WHERE id=" . $id;
        $info   = $this->db->query($sql);
        if ($array_field)
        {
            $array_field_arr = explode(',', $array_field);
        }
        if ($array_field_arr)
        {
            while ($row = $this->db->fetch_array($info))
            {
                foreach ($array_field_arr as $v)
                {
                    $row[$v] = unserialize($row[$v]) ? unserialize($row[$v]) : $row[$v];
                }
                $result = $row;
            }
        }
        else
        {
            while ($row = $this->db->fetch_array($info))
            {
                $result = $row;
            }
        }
        $result = to_htmlspecialchars_decode($result);
        return $result;
    }

    public function content_manage($url, $dir, $content, $need_pages, $need_process, $need_separate = false)
    {
        $content     = htmlspecialchars_decode($content);
        $pregreplace = array('<!--', '-->', '>', '<', '"', '!', "'", "\n", '$', "\r", '<script');
        $pregfind    = array('&#60;&#33;--', '--&#62;', '&gt;', '&lt;', '&quot;', '&#33;', '&#39;', "\n", '&#036;', '', '&#60;script');
        $content     = str_replace($pregfind, $pregreplace, $content);
        if ($need_separate)
        {
            //图集，视频，投票处理
            $result['content_material_list'] = self::content_material_list($url, $dir, $content, $need_pages, $need_process);
        }
        preg_match_all('/<img.*?\ssrc=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $match_mat);
        preg_match_all('/<\s*img\s+[^>]*?src\s*=\s*[\'|\"](.*?)[\'|\"][^>]*?\/?\s*>/is',$content,$match_mat);
        //图片处理	
//		print_r($content);exit;
        if ($need_separate)
        {
            self::content_pic($content, $match_mat);
        }
        $tmp = $need_process ? preg_replace('#<p[^>]*>#i', '<p>', $content) : $content;
        if ($need_pages)
        {
            $tmp = str_replace(' style="margin:0 auto;display:block;"', '', $tmp);
            preg_match_all('/<img[^>]*class=\"pagebg\"[^>]*>/i', $tmp, $match);
            if (empty($match[0]))
            {
                $tmp     = $need_process ? ($this->settings['html_tags'] ? strip_tags($tmp, $this->settings['html_tags']) : strip_tags($tmp, '<p><br><a><m2o_mark>')) : $tmp;
                $pages[] = $tmp;
            }
            else
            {
                $page_total = count($match[0]);
                foreach ($match[0] as $k => $p)
                {
                    $pos          = strpos($tmp, $p);
                    $page_content = substr($tmp, 0, $pos);
                    $page_content = $need_process ? strip_tags($page_content, '<p><br><a><m2o_mark>') : $page_content;
                    if ($page_content)
                    {
                        $pages[] = $page_content;
                    }
                    $start = $pos + strlen($p);
                    $tmp   = substr($tmp, $start);
                    if ($k === $page_total - 1)
                    {
                        $page_content = $need_process ? strip_tags($tmp, '<p><br><a><m2o_mark>') : $tmp;
                        if ($page_content)
                        {
                            $pages[] = $page_content;
                        }
                    }
                }
            }
            $result['content'] = $need_process ? str_replace($match_mat[0], '', $pages) : $pages;
        }
        else
        {
            $tmp               = $need_process ? ($this->settings['html_tags'] ? strip_tags($tmp, $this->settings['html_tags']) : strip_tags($tmp, '<p><br><a><m2o_mark>')) : $tmp;
            $result['content'] = $tmp;
        }
        $result['content'] = preg_replace('#<m2o_mark style="display:none">(.*?)</m2o_mark>#i', '<div m2o_mark="\\1" style="display:none"></div>', $result['content']);

        //处理图片
        $pic = array();
        if ($match_mat[1])
        {
            $i = 0;
            foreach ($match_mat[1] as $k => $v)
            {
                $ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $v, $match);
                if ($ismatch)
                {
                    $pics[$i]['pic']['host']       = $match[1];
                    $pics[$i]['pic']['dir']        = $match[2];
                    $pics[$i]['pic']['filepath']   = $match[4];
                    $pics[$i]['pic']['filename']   = $match[5];
                    $pics[$i]['pic']['is_outlink'] = $match[1] == IMG_URL ? 0 : 1;
                    $i++;
                }
                else
                {
                    if (strpos($v, 'http:') === 0)
                    {
                        $pics[$i]['pic']['host']       = '';
                        $pics[$i]['pic']['dir']        = '';
                        $pics[$i]['pic']['filepath']   = '';
                        $pics[$i]['pic']['filename']   = $v;
                        $pics[$i]['pic']['is_outlink'] = 1;
                        $i++;
                    }
                }
            }
        }//print_r($result['content']);exit;
        $result['content_pics'] = $pics ? $pics : array();
        return $result;
    }

    public function content_material_list($url, $dir, &$content, $need_pages, $need_process)
    {
        $content_material_list = array();
        preg_match_all('/<img[^>]class=[\'|\"]image-refer[\'|\"][^>]src=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $mat_r1);
        preg_match_all('/<img[^>]src=[\'|\"](.*?)[\'|\"].*?class=[\'|\"]image-refer[\'|\"].*?[\/]?>/is', $content, $mat_r2);
        /*         * **匹配外部视频开始*** */
        preg_match_all('/<img[^>]class=[\'|\"]extranet-prev-pic[\'|\"].*?imageid=[\'|\"](.*?)[\'|\"].*?src=[\'|\"](.*?)[\'|\"].*?title=[\'|\"](.*?)[\'|\"].*?_m3u8=[\'|\"](.*?)[\'|\"].*?_swf=[\'|\"](.*?)[\'|\"].*?_videourl=[\'|\"](.*?)[\'|\"].*?[\/]?>/is', $content, $mat_r3);
        $mat_r3                = array_filter($mat_r3);
        if (!empty($mat_r3))
        {
            $infor = array();
            foreach ($mat_r3 as $key => $val)
            {
                foreach ($val as $k => $v)
                {
                    $infor[$k][$key] = $v;
                }
            }
            foreach ($infor as $key => $val)
            {
                $videoInfor = array();
                $ismatch    = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $val[2], $match);
                if ($ismatch)
                {
                    $videoInfor['indexpic']['host']     = $match[1];
                    $videoInfor['indexpic']['dir']      = $match[2];
                    $videoInfor['indexpic']['filepath'] = $match[4];
                    $videoInfor['indexpic']['filename'] = $match[5];
                }
                $videoInfor['title']                           = $val[3];
                $videoInfor['video_url']                       = $val[4];
                $videoInfor['swf']                             = $val[5];
                $videoInfor['videourl']                        = $val[6];
                //模拟规则$fileid由imageid代替
                $content_material_list['videolink_' . $val[1]] = $videoInfor;
                $find_arr[]                                    = $val[0];
                $replace_arr[]                                 = '<m2o_mark style="display:none">videolink_' . $val[1] . '</m2o_mark>';
            }
        }
        /*         * **匹配外部视频结束*** */
        $mat_r = arrpreg($mat_r1, $mat_r2);
        if ((!$mat_r[0] || !is_array($mat_r[0])) && empty($mat_r3))
        {
            return array();
        }
        if ($mat_r[0] && is_array($mat_r[0]))
        {
            foreach ($mat_r[0] as $k => $v)
            {
                if ($mat_r[1][$k])
                {
                    $ex_arr    = explode('/', $mat_r[1][$k]);
                    $re_ex_arr = array_reverse($ex_arr);
                    $filename  = $re_ex_arr[0];
                    $module    = $re_ex_arr[1];
                    $app       = $re_ex_arr[2];

                    $filename_arr    = explode('_', $filename);
                    $re_filename_arr = array_reverse($filename_arr);
                    $fileid          = intval($re_filename_arr[0]);
                    unset($re_filename_arr[0]);
                    if (empty($this->settings['App_' . $app]) || !$re_filename_arr)
                    {
                        continue;
                    }
                    $curl   = new curl($this->settings['App_' . $app]['host'], $this->settings['App_' . $app]['dir']);
                    $curl->setSubmitType('post');
                    $curl->setReturnFormat('json');
                    $curl->initPostData();
                    $curl->addRequestData('id', $fileid);
                    $curl->addRequestData('a', 'detail');
                    $result = $curl->request(implode('_', array_reverse($re_filename_arr)) . '.php');

                    if (is_array($result) && $result)
                    {
                        $ret = $this->select_child($app, $result);
                    }
                    $content_material_list[$app . '_' . $fileid] = $ret;
                    $find_arr[]                                  = $v;
                    $replace_arr[]                               = '<m2o_mark style="display:none">' . $app . '_' . $fileid . '</m2o_mark>';
                }
            }
        }


        if ($find_arr && $replace_arr && $content)
        {
            $content = str_replace($find_arr, $replace_arr, $content);
        }
        return $content_material_list;
    }

    public function content_pic(&$content, $match_mat)
    {
        if (!is_array($match_mat[0]) || !$match_mat[0])
        {
            return false;
        }
        $i=0;
        foreach ($match_mat[0] as $k => $v)
        {
            if (strrchr($match_mat[1][$k], "bg.png") == "bg.png")
            {
                continue;
            }
            $find_arr[]    = $v;
            $replace_arr[] = '<m2o_mark style="display:none">pic_' . $i . '</m2o_mark>';
            $i++;
        }
        if ($find_arr && $replace_arr && $content)
        {
            $content = str_replace($find_arr, $replace_arr, $content);
        }
    }

    public function select_child($app, $result)
    {
        $ret = array();
        switch ($app)
        {
            case 'tuji':
                foreach ($result as $k => $v)
                {
                    $row['title']    = $v['title'];
                    $row['brief']    = $v['brief'];
                    $row['keywords'] = $v['keywords'];
                    $row['app']      = 'tuji';
                    if ($v['img_src'])
                    {
                        foreach ($v['img_src'] as $kk => $vv)
                        {
                            $ismatch = preg_match('/^(.*?)(material\/.*?img\/)([0-9]*[x|-][0-9]*)\/(\d{0,4}\/\d{0,2}\/)(.*?)$/is', $vv, $match);
                            if ($ismatch)
                            {
                                $row['img_src'][$kk]['host']     = $match[1];
                                $row['img_src'][$kk]['dir']      = $match[2];
                                $row['img_src'][$kk]['filepath'] = $match[4];
                                $row['img_src'][$kk]['filename'] = $match[5];
                            }
                        }
                    }
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr)
                        {
                            foreach ($column_urlarr as $kkk => $vvv)
                            {
                                $row['relation_id'][] = array('column_id' => $kkk, 'id' => $vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'livmedia':
                foreach ($result as $k => $v)
                {
                    $row['title']                = $v['title'];
                    $row['brief']                = $v['brief'];
                    $row['keywords']             = $v['keywords'];
                    $row['column_url']           = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    //$v['video_filename'] = str_replace('.mp4','.m3u8',$v['video_filename']);
                    //$row['video_url'] = rtrim($v['hostwork'],'/').'/'.$v['video_path'].$v['video_filename'];
                    $row['video_url']            = $v['videoaddr']['default']['m3u8'];
                    $row['video_url_f4m']        = $v['videoaddr']['default']['f4m'];
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
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr)
                        {
                            foreach ($column_urlarr as $kkk => $vvv)
                            {
                                $row['relation_id'][] = array('column_id' => $kkk, 'id' => $vvv);
                            }
                            $row['id'] = $row['relation_id'][0]['id'];
                        }
                    }
                    $ret = $row;
                }
                break;
            case 'vote':
                foreach ($result as $k => $v)
                {
                    $row               = $v;
                    $row['column_url'] = is_array($v['column_url']) ? $v['column_url'] : unserialize($v['column_url']);
                    $row['column_id']  = is_array($v['column_id']) ? $v['column_id'] : unserialize($v['column_id']);
                    if ($v['column_url'])
                    {
                        $column_urlarr = @unserialize($v['column_url']);
                        if ($column_urlarr)
                        {
                            foreach ($column_urlarr as $kkk => $vvv)
                            {
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

    function hg_load_inner_content($url, $dir, $uniqueid, $id)
    {
        return '<script type="text/javascript" src="' . $url . $dir . $uniqueid . '.php?id=' . $id . '"></script>';
    }

    public function is_mk_cache($need_pages, $need_child_detail, $result_need_pages, $result_need_child, $need_process, $result_need_process, $need_separate = false, $result_need_separate = false)
    {
        if (($need_pages && $result_need_pages) || (!$need_pages && !$result_need_pages))
        {
            $tag1 = true;
        }
        if (($need_child_detail && $result_need_child) || (!$need_child_detail && !$result_need_child))
        {
            $tag2 = true;
        }
        if (($need_process && $result_need_process) || (!$need_process && !$result_need_process))
        {
            $tag3 = true;
        }
        if (($need_separate && $result_need_separate) || (!$need_separate && !$result_need_separate))
        {
            $tag4 = true;
        }
        return ($tag1 && $tag2 && $tag3 && $tag4) ? true : false;
    }

}

?>