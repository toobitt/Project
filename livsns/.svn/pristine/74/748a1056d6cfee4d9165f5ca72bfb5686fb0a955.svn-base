<?php

/* * *************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id: vod_update.php 8044 2012-07-18 02:30:26Z zhoujiafei $
 * ************************************************************************* */
//以下常量用于定于是否需要权限检测和模块唯一标识符
define('MOD_UNIQUEID', 'mediaserver');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/statistic.class.php');
require_once(ROOT_PATH . 'lib/class/recycle.class.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');

class vod_update extends adminUpdateBase
{
    private $local_curl;
    private $curl;

    public function __construct()
    {
        parent::__construct();
        $this->recycle        = new recycle();
        $this->publish_column = new publishconfig();
        $this->local_curl     = new curl($this->settings['App_livmedia']['host'], $this->settings['App_livmedia']['dir'] . 'admin/');
        $this->curl           = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function sort()
    {
        $this->drag_order('vodinfo', 'video_order_id');
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN(" . $this->input['content_id'] . ")";
        $ret = $this->db->query($sql);
        while ($row = $this->db->fetch_array($ret))
        {
            if (!empty($row['column_id']) && !empty($row['expand_id']))
            {
                publish_insert_query($row, 'update');
            }
        }
        $ids = explode(',', $this->input['content_id']);
        $this->addItem(array('id' => $ids));
        $this->output();
    }

    private function index_search($data, $type = 'add')
    {
        $conf = realpath(CUR_CONF_PATH . 'conf/search_vod.ini');
        if ($conf)
        {
            try
            {
                include_once (ROOT_PATH . 'lib/xunsearch/XS.php');
                $xs    = new XS($conf); // 建立 XS 对象，项目名称为：demo
                $index = $xs->index; // 获取 索引对象
            }
            catch (XSException $e)
            {
                return false;
            }
            if ($type == 'del')
            {
                $index->$type($data);
                return;
            }
            if ($data)
            {
                if (!is_array($data))
                {
                    $sql  = "SELECT *  FROM " . DB_PREFIX . 'vodinfo WHERE id=' . $data;
                    $data = $this->db->query_first($sql);
                }
                $data = array(
                    'id' => $data['id'],
                    'title' => $data['title'],
                    'subtitle' => $data['subtitle'],
                    'comment' => $data['comment'],
                    'channel_id' => $data['channel_id'],
                    'vod_sort_id' => $data['vod_sort_id'],
                    'status' => $data['status'],
                    'vod_leixing' => $data['vod_leixing'],
                    'is_allow' => $data['is_allow'],
                    'from_appid' => $data['from_appid'],
                    'from_appname' => $data['from_appname'],
                    'duration' => $data['duration'],
                    'trans_use_time' => $data['trans_use_time'],
                    'mark_collect_id' => $data['mark_collect_id'],
                    'create_time' => $data['create_time'],
                    'playcount' => $data['playcount'],
                    'click_count' => $data['click_count'],
                    'downcount' => $data['downcount'],
                    'keywords' => $data['keywords'],
                    'bitrate' => $data['bitrate'],
                    'author' => $data['author'],
                    'video_order_id' => $data['video_order_id'],
                );
                // 创建文档对象
                $doc  = new XSDocument;
                $doc->setFields($data);

                // 添加到索引数据库中
                $index->$type($doc);
            }
        }
    }

    /* 参数:上传上去的视频的基本信息
     * 功能:上传完视频之后，往数据库中插入一条记录
     * 返回值:需要插入表中的数据($this->input)
     * */

    public function create()
    {
        //通过链接上传视频
        if($this->input['is_link'])
        {
            require_once(ROOT_PATH . 'lib/class/videourlparser.class.php');
            $parse = new VideoUrlParser();
            
            $url = trim($this->input['url']);
            if(!$url)
            {
                $this->errorOutput('请填写视频链接');
            }
            $ret = $parse->parse($url);
            if(!$ret)
            {
                echo json_encode(array('msg' => '提取失败,视频链接有误或该视频存在版权问题', 'error' => 1));exit();
            }
            $this->input['title'] = $ret['title'];
            $this->input['index_pic'] = $ret['img'];
            $this->input['ori_url'] = $this->input['url'];
            $url = parse_url($ret['m3u8']);
            $this->input['hostwork'] = 'http://'.$url['host'];
            $this->input['video_path'] = substr($ret['m3u8'],strlen($this->input['hostwork'].'/'));
            $this->input['swf'] = $ret['swf'];
            $this->input['duration'] = $ret['duration'];
        }
        if(!$this->input['title'])
        {
            $this->errorOutput(NO_TITLE);
        }

        //分类不传，默认分类是编辑上传
        $vod_sort_id = $this->input['vod_sort_id']?intval($this->input['vod_sort_id']):1;
        //通过分类id反查类型id
        $sql = "SELECT * FROM " . DB_PREFIX . "vod_media_node WHERE id IN (1,2,3,4)";
        $q = $this->db->query($sql);
        while ($r = $this->db->fetch_array($q))
        {
            $_childs = explode(',',$r['childs']);
            if(in_array($vod_sort_id,$_childs))
            {
                $vod_leixing = $r['id'];
                break;
            }
        }
        
        if(!$vod_leixing)
        {
            $vod_leixing = 1;
        }

        //处理发布的栏目
        $column_id = '';
        if($this->input['column_id'])
        {
            $column_id = $this->input['column_id'];
            $column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
            $column_id = addslashes(serialize($column_id));
        }
        $status = isset($this->input['status'])?$this->input['status']:1;//不传默认待审核
        $data = array(
            'title'         =>  rawurldecode($this->input['title']),
            'vod_leixing'   =>  $vod_leixing,
            'vod_sort_id'   =>  $vod_sort_id,
            'status'        =>  $status,
            'column_id'     =>  $column_id,
            'isbold'        =>  $this->input['isbold'],
            'tcolor'        =>  $this->input['tcolor'],
            'isitalic'      =>  $this->input['isitalic'],
            'weight'        =>  $this->input['weight'],
            'comment'       =>  $this->input['comment'],
            'subtitle'      =>  $this->input['subtitle'],
            'source'        =>  $this->input['source'],
            'author'        =>  $this->input['author'],
            'keywords'      =>  $this->input['keywords'],
            'hostwork'      =>  $this->input['hostwork'],
            'video_path'    =>  $this->input['video_path'],
            'video_filename'=>  $this->input['video_filename'],
            'duration'      =>  $this->input['duration'],
            'totalsize'     =>  $this->input['totalsize'],
            'video'         =>  $this->input['video'],
            'frame_rate'    =>  $this->input['frame_rate'],
            'aspect'        =>  $this->input['aspect'],
            'width'         =>  $this->input['width'],
            'height'        =>  $this->input['height'],
            'audio'         =>  $this->input['audio'],
            'sampling_rate' =>  $this->input['sampling_rate'],
            'audio_channels'=>  $this->input['audio_channels'],
            'bitrate'       =>  $this->input['bitrate'],
            'starttime'     =>  $this->input['starttime'],
            'is_forcecode'  =>  1,
            'addperson'     =>  $this->user['user_name'],
            'user_id'       =>  $this->user['user_id'],
            'org_id'        =>  $this->user['org_id'],
            'from_appid'    =>  $this->user['appid'],
            'from_appname'  =>  $this->user['display_name'],
            'create_time'   =>  TIMENOW,
            'update_time'   =>  TIMENOW,
            'ip'            =>  hg_getip(),
            'template_sign' => $this->input['template_sign'],
            'ori_url'  =>  $this->input['ori_url'],
            'swf'       => $this->input['swf'] ? $this->input['swf'] : '',
            'is_link' => $this->input['is_link'] ? 1 : 0,
            'iscomment' => $this->input['iscomment'] ? 1 : 0,
        	'source_path'=>$this->input['source_path'],
        	'source_filename'=>$this->input['source_filename'],
            //'morebitrate_config_id' => $this->input['morebitrate_config_id'],
        );
        
        //为叮当视频外链处理
        if($this->input['chain_m3u8'])
        {
            $url = parse_url($this->input['chain_m3u8']);
            $data['hostwork'] = 'http://'.$url['host'];
            $data['video_path'] = substr($this->input['chain_m3u8'],strlen($data['hostwork'].'/'));
            $data['swf'] = $this->input['chain_swf'] ? $this->input['chain_swf'] : '';
            $data['is_link'] = 1;
            $data['duration'] = $this->input['chain_duration'];
        }
        $sql = "INSERT INTO ".DB_PREFIX."vodinfo SET ";
        foreach ($data AS $k => $v)
        {
            $sql .= " {$k} = '{$v}',";
        }
        $sql = rtrim($sql,',');
        $this->db->query($sql);
        $vid = $this->db->insert_id();
        
        //本地化索引图片
        if($this->input['index_pic'])
        {
            $img_info = $this->create_thumb($this->input['index_pic'], $vid);
            if($img_info)
            {
                $imgArr = array(
                    'host'      => $img_info['host'],
                    'dir'       => $img_info['dir'],
                    'filepath'  => $img_info['filepath'],
                    'filename'  => $img_info['filename'],
                    'imgwidth'  => $img_info['imgwidth'],
                    'imgheight' => $img_info['imgheight'],
                );
                $img_info = addslashes(serialize($imgArr));
            }
        }
        else 
        {
            $img_info = '';
        }
        //为叮当视频外链传来的索引图作处理
        if($this->input['chain_img'])
        {
            unset($this->input['chain_img']['id']);
        $img_info = serialize($this->input['chain_img']);
        }
        //更新排序
        $sql = "UPDATE " .DB_PREFIX. "vodinfo SET img_info='".$img_info."',video_order_id = '" .$vid. "' WHERE id = '" .$vid. "'";
        $this->db->query($sql);
        $data['id'] = $vid;
        
        //如果创建状态是'已审核',就插入发布队列
        if($data['status'] == 2 && $this->input['column_id'])
        {
            $data['pub_time'] = TIMENOW;
            $data['column_id'] = stripslashes($data['column_id']);
            publish_insert_query($data,'insert');
        }
        
            //将视频url提交到转码服务器下载并转码
        if($data && !$this->input['chain_m3u8'] && !$this->input['is_link'])
        {
            $data['img_info'] = serialize($imgArr);
            $data['is_forcecode'] = $this->input['is_forcecode'];
            $re = $this->set_url($data);
            if($re == 'transcode')
            {
                $sql = "UPDATE " .DB_PREFIX. "vodinfo SET status=-1 WHERE id = '" .$vid. "'";
                $this->db->query($sql);
            }
            elseif($re == 'download')
            {
                $sql = "UPDATE " .DB_PREFIX. "vodinfo SET status=6 WHERE id = '" .$vid. "'";
                $this->db->query($sql);
            }
        }
        
        //返回值
        $this->addItem($data);
        $this->output();
    }
    
    /*
     * 更新云视频
     * index_pic   索引图
     * chain_m3u8  m3u8链接
     */
    public function cloud_vod_update()
    {
        if(!$this->input['id'])
        {
            //$this->errorOutput(NOID);
        }
        
        $data = array();
        $data = array(
			'duration' => intval($this->input['duration']),//时长(单位毫秒)
			'totalsize' => intval($this->input['totalsize']),//视频大小(单位字节)
			'frame_rate' => $this->input['frame_rate'],//帧速/频率(如:15.000000fps)
			'height' => intval($this->input['height']),
			'width' => intval($this->input['width']),
			'bitrate' => intval($this->input['bitrate']),
			'sampling_rate' => $this->input['sampling_rate'],//采样率(如:44.1 KHz)
			'audio' => $this->input['audio'],//音频格式(如:aac)
			'audio_channels' => $this->input['audio_channels'],//声道(如左右声道:Front: L R)
			'video' => $this->input['video'],//视频格式(如:H264)
			'aspect' => $this->input['aspect'],//比率(如:16:9)
        );
        
        /******* 权限 *******/
        /*******************/
        
        //外链处理
        if($this->input['chain_m3u8'])
        {
            $url = parse_url($this->input['chain_m3u8']);
            $data['hostwork'] = 'http://'.$url['host'];
            $data['video_path'] = substr($this->input['chain_m3u8'],strlen($data['hostwork'].'/'));
        }
        $this->input['status'] ? $data['status'] = $this->input['status'] : '';
     	//本地化索引图片
        if($this->input['index_pic'])
        {
            $img_info = $this->create_thumb($this->input['index_pic'], $vid);
            if($img_info)
            {
                $imgArr = array(
                    'host'      => $img_info['host'],
                    'dir'       => $img_info['dir'],
                    'filepath'  => $img_info['filepath'],
                    'filename'  => $img_info['filename'],
                    'imgwidth'  => $img_info['imgwidth'],
                    'imgheight' => $img_info['imgheight'],
                );
                $img_info = addslashes(serialize($imgArr));
                $data['img_info'] = $img_info;
            }
        }
        foreach((array)$data as $k => $v)
        {
        		if(!trim($v))
        		{
        			unset($data[$k]);
        		}
        }
        if(!empty($data))
        {
	        $sql = " UPDATE " . DB_PREFIX . "vodinfo SET ";
			foreach ($data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql  = trim($sql,',');
			$sql .= " WHERE id = '"  .$this->input['id']. "'";
			$this->db->query($sql);
	        //返回值
	        $this->addItem($data);
	        $this->output();
        }
        $this->addItem('success');
        $this->output();
    }
    
    
    /*
     * 将视频地址提交到转码服务器进行下载
     * 参数: 视频地址
     * 返回: 成功(true)失败(false)
     */
    
    public function set_url($data)
    {
        $this->curl->setSubmitType('post');
        $this->curl->initPostData();
        foreach ($data as $key => $val)
        {
            $this->curl->addRequestData($key, $val);
        }
        $this->curl->addRequestData('html', 'true');
        $this->curl->addRequestData('a', 'download_and_transcode');
        $return = $this->curl->request('yun.php');
        if($return)
        {
            return $return;
        }
        else
        {
            return false;
        }
    }
    
    /* 参数:视频的id
     * 功能:删除一个视频
     * 返回值:成功(success)
     * */

    public function delete()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $id           = $this->input["id"];
        $sql          = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id in (" . $id . ")";
        $q            = $this->db->query($sql);
        $data         = array(); //记录回收站的数据
        $log          = array(); //记录日志
        $user_ids     = array(); //工作量统计
        $addpersons   = array(); //工作量统计
        $original_ids = array(); //为了修改源视频拥有的拆条个数
        $tv_play_info = array(); //触发电视剧剧集更新
        while ($r = $this->db->fetch_array($q))
        {
            $log[]        = $r;
            $user_ids[]   = $r['user_id'];
            $addpersons[] = $r['addperson'];
            $column_id    = @unserialize($r['column_id']);
            if ($column_id && is_array($column_id))
            {
                $ori_column_ids = implode(',', array_keys($column_id));
            }
            else
            {
                $ori_column_ids = '';
            }

            if (intval($r['status']) == 2 && ($r['expand_id'] || $column_id))
            {
                $op = "delete";
                publish_insert_query($r, $op);
            }
            $data[$r['id']]                       = array(
                'title' => $r['title'],
                'delete_people' => trim(($this->user['user_name'])),
                'cid' => $r['id'],
            );
            $data[$r['id']]['content']['vodinfo'] = $r;
            if ($r['original_id'])
            {
                $original_ids[] = $r['original_id'];
            }

            $vod_sorts_arr[]                           = $r['vod_sort_id'];
            $prms_arr[$r['id']]['id']                  = $r['id'];
            $prms_arr[$r['id']]['user_id']             = $r['user_id'];
            $prms_arr[$r['id']]['published_column_id'] = $ori_column_ids;
            $prms_arr[$r['id']]['org_id']              = $r['org_id'];
            $prms_arr[$r['id']]['vod_sort_id']         = $r['vod_sort_id'];
            
            //已发布的视频，并且来源为电视剧，删除触发电视剧剧集更新操作
            if($r['tv_play_id'] && $r['expand_id'])
            {
                $tv_play_info[] = $r['id'];
            }
        }

        /*         * *********************************权限控制**************************************************** */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if ($vod_sorts_arr)
            {
                $sql            = 'SELECT id,parents FROM ' . DB_PREFIX . 'vod_media_node WHERE id IN(' . implode(',', $vod_sorts_arr) . ')';
                $query          = $this->db->query($sql);
                $sort_ids_array = array();
                while ($row            = $this->db->fetch_array($query))
                {
                    $sort_ids_array[$row['id']] = $row['parents'];
                }
            }
            if (!empty($prms_arr))
            {
                foreach ($prms_arr as $key => $value)
                {
                    $value['nodes'][$value['vod_sort_id']] = $sort_ids_array[$value['vod_sort_id']];
                    $this->verify_content_prms($value);
                }
            }
        }
        /*         * *********************************权限控制**************************************************** */

        //为了修改源视频拥有的拆条个数
        if (!empty($original_ids))
        {
            $sql = " UPDATE " . DB_PREFIX . "vodinfo SET mark_count = mark_count - 1 WHERE id IN (" . implode(',', $original_ids) . ")";
            $this->db->query($sql);
        }

        //正式删除记录与物理文件视频物理文件（ism与ismv）
        $this->del_phyfile($id);
        $sql = "DELETE FROM " . DB_PREFIX . "vodinfo  WHERE  1  AND  id  in (" . $id . ")";
        $this->db->query($sql);
        //删除编目
        $this->catalog('delete',$id);
        //加入回收站
        if (!empty($data))
        {
            foreach ($data as $key => $value)
            {
                $this->recycle->add_recycle($value['title'], $value['delete_people'], $value['cid'], $value['content']);
            }
        }
        
        //触发电视剧剧集更新
        if(!empty($tv_play_info))
        {
            $tv_video_ids = implode(',', $tv_play_info);
            include_once(ROOT_PATH.'lib/class/curl.class.php');
                
            $host = $this->settings['App_tv_play']['host'];
            $dir  = $this->settings['App_tv_play']['dir'].'admin/';
            $filename = 'tv_play';
                
            $curl = new curl($host,$dir);
                    
            $curl->setSubmitType('post');
            $curl->initPostData();
            $curl->addRequestData('a','update_episode_expand_id');
    
            $curl->addRequestData('vids',$tv_video_ids);
            $curl->request($filename.'_update.php');
        }
            
        //记录日志
        $this->addLogs('删除视频', $log, '', '删除视频' . $id);

        $this->index_search(explode(',', $id), 'del');
        //插入工作量统计
        $statistic       = new statistic();
        $statistics_data = array(
            'content_id' => $this->input["id"],
            'contentfather_id' => '',
            'type' => 'delete',
            'user_id' => implode(',', $user_ids),
            'user_name' => implode(',', $addpersons),
            'before_data' => '',
            'last_data' => $this->input['title'],
            'num' => 1,
        );
        $statistic->insert_record($statistics_data);
        /* 返回删除的id */
        $del_ids         = explode(',', ($this->input['id']));
        $this->addItem(array('id' => $del_ids));
        $this->output();
    }

    //回收站删除数据回调（此处需要删除视频）
    public function delete_comp()
    {
        if (!$this->input['content']['vodinfo'] || empty($this->input['content']['vodinfo']))
        {
            $this->errorOutput(NO_INFO);
        }
        $video      = $this->input['content']['vodinfo'];
        $video_path = array(
            'video_path' => $video['video_path'],
            'video_filename' => $video['video_filename'],
            'source_path' => $video['source_path'],
            'source_filename' => $video['source_filename'],
        );

        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        foreach ($video_path AS $k => $v)
        {
            $curl->addRequestData("$k", $v);
        }
        $curl->addRequestData('a', 'delete_video_file');
        $curl->request('delete.php');
        $this->addItem('success');
        $this->output();
    }

    /* 参数:视频的vodid
     * 功能:删除服务器上面的物理文件
     * 返回值:无
     */

    public function del_phyfile($id)
    {
        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('id', $id);
        $curl->request('delete.php');
    }

    //还原视频
    public function recover()
    {
        $content = $this->input['content'];
        $ids     = array(); //记录插入的id
        if ($content && $content[0])
        {
            $content = $content[0];
            foreach ($content as $key => $value)
            {
                if (!empty($value))
                {
                    $sql   = "insert into " . DB_PREFIX . $key . " set ";
                    $space = '';
                    foreach ($value as $k => $v)
                    {
                        if (in_array($k, array('expand_id', 'column_id', 'column_url')))
                        {
                            continue;
                        }

                        if (is_array($v))
                        {
                            $sql2  = "insert into " . DB_PREFIX . $key . " set ";
                            $space = '';
                            foreach ($v as $kk => $vv)
                            {
                                $sql2 .= $space . $kk . "='" . $vv . "'";
                                $space = ',';
                            }
                            $this->db->query($sql2);
                        }
                        else
                        {
                            $sql .= $space . $k . "='" . $v . "'";
                            $space = ',';
                        }
                    }
                    $this->db->query($sql);
                    $ids[] = $this->db->insert_id();
                }
            }
        }
        $video_ids    = implode(',', $ids);
        //查询出还原的视频里面是不是拆条过来，如果是要将源视频里面的mark_count+1
        $sql          = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" . $video_ids . ")";
        $q            = $this->db->query($sql);
        $original_ids = array();
        while ($r            = $this->db->fetch_array($q))
        {
            if ($r['original_id'])
            {
                $original_ids[] = $r['original_id'];
            }
        }
        if (!empty($original_ids))
        {
            $sql = " UPDATE " . DB_PREFIX . "vodinfo SET mark_count = mark_count + 1 WHERE id IN (" . implode(',', $original_ids) . ")";
            $this->db->query($sql);
        }

        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('id', $video_ids);
        $curl->addRequestData('a', 'recover');
        $curl->request('delete.php');
        $this->addItem('success');
        $this->output();
    }

    /* 参数:视频的记录id
     * 功能:对视频进行审核/打回
     * 返回值:被审核或者被打回的视频的id以及他们操作之后的状态
     * */

    public function audit()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        //读取这些视频的状态是否为大于0，只有大于0的才能被审核，过滤掉小于0的
        $pre_log       = array(); //记录日志
        $vod_sorts_arr = array(); //存储节点
        $prms_arr      = array(); //存储权限信息
        $sql           = "SELECT * FROM " . DB_PREFIX . "vodinfo  WHERE id IN (" . ($this->input["id"]) . ") AND status > 0";
        $q             = $this->db->query($sql);
        while ($r             = $this->db->fetch_array($q))
        {
            $pre_log[]    = $r;
            $ids[]        = $r['id'];
            $user_ids[]   = $r['user_id'];
            $addpersons[] = $r['addperson'];
            $column_id    = @unserialize($r['column_id']);
            if ($column_id && is_array($column_id))
            {
                $ori_column_ids = implode(',', array_keys($column_id));
            }
            else
            {
                $ori_column_ids = '';
            }
            //存储权限信息
            $vod_sorts_arr[]                   = $r['vod_sort_id'];
            //$prms_arr[$r['id']]['id'] = $r['id'];
            //$prms_arr[$r['id']]['user_id'] = $r['user_id'];
            //$prms_arr[$r['id']]['published_column_id'] = $ori_column_ids;
            //$prms_arr[$r['id']]['org_id'] = $r['org_id'];
            $prms_arr[$r['id']]['vod_sort_id'] = $r['vod_sort_id'];
        }

        /*         * *********************************权限控制**************************************************** */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if ($vod_sorts_arr)
            {
                $sql            = 'SELECT id,parents FROM ' . DB_PREFIX . 'vod_media_node WHERE id IN(' . implode(',', $vod_sorts_arr) . ')';
                $query          = $this->db->query($sql);
                $sort_ids_array = array();
                while ($row            = $this->db->fetch_array($query))
                {
                    $sort_ids_array[$row['id']] = $row['parents'];
                }
            }
            if (!empty($prms_arr))
            {
                foreach ($prms_arr as $key => $value)
                {
                    $value['nodes'][$value['vod_sort_id']] = $sort_ids_array[$value['vod_sort_id']];
                    $this->verify_content_prms($value);
                }
            }
        }
        /*         * *********************************权限控制**************************************************** */
        if (!$ids)
        {
            $this->errorOutput('转码中不能审核');
        }
        $video_ids    = implode(",", $ids);
        $return['id'] = $ids;

        //如果audit为1,进行审核操作,审核之后为status为2
        if (intval($this->input['audit']) == 1)
        {
            $sql  = "UPDATE " . DB_PREFIX . "vodinfo  SET  status=2,audit_time=".TIMENOW."  WHERE id in (" . $video_ids . ")";
            $q    = $this->db->query($sql);
            //发布
            $sql  = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN(" . ($this->input['id']) . ")";
            $ret  = $this->db->query($sql);
            while ($info = $this->db->fetch_array($ret))
            {
                $op = '';
                if (!empty($info['expand_id']))
                {
                    $op = "update";
                }
                else
                {
                    if (unserialize($info['column_id']))
                    {
                        $op = "insert";
                    }
                }
                publish_insert_query($info, $op);
            }
            $return['status'] = 2;
        }
        else if (intval($this->input['audit']) == 0)
        {
            $sql = "UPDATE " . DB_PREFIX . "vodinfo SET status = 3 WHERE id in ( " . $video_ids . ")";
            $this->db->query($sql);

            $sql  = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN(" . ($this->input['id']) . ")";
            $ret  = $this->db->query($sql);
            
            $tv_play_info = array();
            while ($info = $this->db->fetch_array($ret))
            {
                $info['column_id'] = @unserialize($info['column_id']);
                if (!empty($info['expand_id']) || $info['column_id'])
                {
                    $op = "delete";    //expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
                }
                else
                {
                    $op = "";
                }
                publish_insert_query($info, $op);
                
                if($info['tv_play_id'] && $info['expand_id'])
                {
                    $tv_play_info[] = $info['id'];
                }
            }
            
            //如果视频为电视剧里剧集，请求电视剧接口
            if(!empty($tv_play_info))
            {
                $tv_video_ids = implode(',', $tv_play_info);
                include_once(ROOT_PATH.'lib/class/curl.class.php');
                
                $host = $this->settings['App_tv_play']['host'];
                $dir  = $this->settings['App_tv_play']['dir'].'admin/';
                $filename = 'tv_play';
                
                $curl = new curl($host,$dir);
                    
                $curl->setSubmitType('post');
                $curl->initPostData();
                $curl->addRequestData('a','update_episode_expand_id');
    
                $curl->addRequestData('vids',$tv_video_ids);
                $curl->request($filename.'_update.php');
            }
            $return['status'] = 3;
        }

        //查询出需要入工作量统计的数据
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo  WHERE  id in (" . $video_ids . ")";
        $q   = $this->db->query($sql);
        while ($r   = $this->db->fetch_array($q))
        {
            $up_log[]      = $r;
            $fuser_ids[]   = $r['user_id'];
            $faddpersons[] = $r['addperson'];
        }
        //加日志
        $this->addLogs('审核视频', $pre_log, $up_log, '审核视频' . $video_ids);
        //插入工作量统计
        $statistic       = new statistic();
        $statistics_data = array(
            'content_id' => $video_ids,
            'contentfather_id' => '',
            'type' => $return['status'] == 2 ? 'verify_suc' : 'verify_fail',
            'user_id' => implode(",", $fuser_ids),
            'user_name' => implode(",", $faddpersons),
            'before_data' => 'status=1',
            'last_data' => 'status=2',
            'num' => count($fuser_ids),
        );
        $statistic->insert_record($statistics_data);

        if ($return['status'] == 2)
        {
            $return['pubstatus'] = 1;
        }
        else
        {
            $return['pubstatus'] = 0;
        }
        $this->addItem($return);
        $this->output();
    }
    public function update_video_info()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $video_id = intval($this->input['id']);

        $sql               = "SELECT id,user_id,addperson FROM " . DB_PREFIX . "vodinfo WHERE id='{$video_id}'";
        $vodinfo           = $this->db->query_first($sql);
        $this->input['id'] = $vodinfo['id'];
        if (!$vodinfo['img_info'])
        {
            $this->change_pic(); //图片的处理
        }

        if (!$this->input['status'])
        {
            $this->input['status'] = '-1';
        }
        $info = array(
            'totalsize',
            'height',
            'start',
            'duration',
            'bitrate',
            'width',
            'type',
            'status',
            'vod_sort_id',
            'audio',
            'audio_channels',
            'sampling_rate',
            'frame_rate',
            'video',
            'aspect',
            'starttime',
            'delay_time',
            'trans_use_time',
            'vtype',
        );

        $update_info = array(); //用于存放更新之后的信息,用于版本控制

        $fields = ' SET  ';
        foreach ($info AS $k => $v)
        {
            if (!isset($this->input[$v]))
            {
                continue;
            }

            $fields .= $v . ' = \'' . ($this->input[$v]) . '\', ';
        }
        $fields .= ' isfile=1, update_time = \'' . TIMENOW . '\'';

        $updatesql = "UPDATE " . DB_PREFIX . 'vodinfo ' . $fields . '  WHERE id=' . $this->input['id'];
        $info      = $this->db->query($updatesql);
        $this->index_search($video_id, 'update');
        echo 1;
    }

    /* 参数:视频的记录id
     * 功能:更新编辑一个视频之后的信息
     * 返回值:更新之后的视频信息(数组)
     * */

    public function update()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $id             = intval($this->input['id']);
        $new_column_ids = $this->input['column_id'];
        //查询修改视频之前已经发布到的栏目
        $sql            = "select * from " . DB_PREFIX . "vodinfo where id = " . $id;
        $q              = $this->db->query_first($sql);
        $pre_data       = $q;
        $q['column_id'] = unserialize($q['column_id']);
        $ori_column_id  = array();
        if (is_array($q['column_id']))
        {
            $ori_column_id = array_keys($q['column_id']);
        }

        $column_id                = $this->publish_column->get_columnname_by_ids('id,name', ($this->input['column_id']));
        $column_id                = serialize($column_id);
        $this->input['column_id'] = $column_id;

        /*         * *******************************权限控制*************************************** */
        if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            if ($this->input['vod_sort_id'])
            {
                $sql   = 'SELECT id, parents FROM ' . DB_PREFIX . 'vod_media_node WHERE id IN(' . $this->input['vod_sort_id'] . ')';
                $query = $this->db->query($sql);
                while ($row   = $this->db->fetch_array($query))
                {
                    $data['nodes'][$row['id']] = $row['parents'];
                }
            }
            $data['id']                  = $id;
            $data['user_id']             = $q['user_id'];
            $data['org_id']              = $q['org_id'];
            $data['column_id']           = $new_column_ids;
            $data['published_column_id'] = implode(',', $ori_column_id);
            $this->verify_content_prms($data);
            $this->check_weight_prms(intval($this->input['weight']), $pre_data['weight']);
        }
        /*         * *******************************权限控制*************************************** */



        $this->change_pic_server(); //图片处理

        $info        = array(
            'copyright',
            'author',
            'comment',
            'title',
            'subtitle',
            'keywords',
            'vod_sort_id',
            'source',
            'column_id',
            'weight',
            'tcolor',
            'isbold',
            'isitalic',
            'template_sign',
            'iscomment',
        );
        $update_info = array(); //用于存放更新之后的信息,用于版本控制

        $fields = ' SET  ';
        foreach ($info AS $k => $v)
        {
            if (!isset($this->input[$v]))
            {
                continue;
            }

            if (in_array($v, array('title', 'comment', 'subtitle', 'keywords', 'author', 'template_sign', 'iscomment')))
            {
                $this->input[$v] = trim($this->input[$v]);
            }

            $fields .= $v . ' = \'' . ($this->input[$v]) . '\',';
        }
        $fields    = trim($fields, ',');
        $updatesql = "UPDATE " . DB_PREFIX . 'vodinfo ' . $fields . '  WHERE  id = ' . $id;
        //更新编目
        $this->catalog('update',$id,'vodinfo',$q['catalog']);
        
        $this->db->query($updatesql);
        if ($this->db->affected_rows())
        {
            $append_update_data = array(
                'update_time' => TIMENOW,
            );
            $sql                = " UPDATE " . DB_PREFIX . "vodinfo SET ";
            foreach ($append_update_data AS $k => $v)
            {
                $sql .= " {$k} = '{$v}',";
            }
            $sql = trim($sql, ',');
            $sql .= " WHERE id = '" . $id . "'";
            $this->db->query($sql);
        }
        else
        {
            $this->addItem('success');
            $this->output();
        }

        $sql  = "SELECT * FROM " . DB_PREFIX . "vodinfo  WHERE id =" . $id;
        $info = $this->db->query_first($sql);
        /*************************此处是为了解决vod_sort_id 编程栏目的id这个bug暂时不清楚为什么会有这种情况的***/
        if($info['vod_sort_id'])
        {
            $_sql =  "SELECT * FROM " .DB_PREFIX. "vod_media_node WHERE id = '" .$info['vod_sort_id']. "'";
            if(!$this->db->query_first($_sql))
            {
                $_sql = "UPDATE " .DB_PREFIX. "vodinfo SET vod_sort_id = '" .$info['vod_leixing']."' WHERE id = '" .$id. "'";
                $this->db->query($_sql);
                $info['vod_sort_id'] = $info['vod_leixing'];
            }
        }
        /******************************************************************************************/
        $this->addLogs('更新视频', $pre_data, $info, $info['title']);

        $this->index_search($info, 'update');
        //插入工作量统计
        $statistic       = new statistic();
        $statistics_data = array(
            'content_id' => $id,
            'contentfather_id' => '',
            'type' => 'update',
            'user_id' => $info['user_id'],
            'user_name' => $info['addperson'],
            'before_data' => '',
            'last_data' => $info['title'],
            'num' => 1,
        );
        $statistic->insert_record($statistics_data);

        $sql         = "SELECT * FROM " . DB_PREFIX . "vodinfo  WHERE id =" . $id;
        $info        = $this->db->query_first($sql);
        $update_data = serialize($info); //将更改的信息进行串行化

        $info['format_duration'] = hg_timeFormatChinese($info['duration']); //时长
        $info['resolution']      = $info['width'] . '*' . $info['height']; //分辨率
        $info['vod_leixing']     = $this->settings['video_upload_type'][$info['vod_leixing']];
        $info['totalsize']       = hg_fetch_number_format($info['totalsize'], 1);
        $audio_status            = check_str('L', 'R', $info['audio_channels']);

        switch ($audio_status)//声道
        {
            case 0 :$info['audio_channels'] = '无';
                break;
            case 1 :$info['audio_channels'] = '右';
                break;
            case 2 :$info['audio_channels'] = '左';
                break;
            case 3 :$info['audio_channels'] = '左右';
                break;
            default :$info['audio_channels'] = '无';
                break;
        }

        //发布系统
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id = " . intval($this->input['id']);
        $ret = $this->db->query_first($sql);

        //更改视频后发布的栏目
        $ret['column_id'] = unserialize($ret['column_id']);
        $new_column_id    = array();
        if (is_array($ret['column_id']))
        {
            $new_column_id = array_keys($ret['column_id']);
        }
        
        //记录发布库栏目分发表
        $this->update_pub_column(intval($this->input['id']), implode(',', $new_column_id));
        //记录发布库栏目分发表
        
        
        if (intval($ret['status']) == 2)
        {
            if (!empty($ret['expand_id']))
            {
                $del_column = array_diff($ori_column_id, $new_column_id);
                if (!empty($del_column))
                {
                    publish_insert_query($ret, 'delete', $del_column);
                }
                $add_column = array_diff($new_column_id, $ori_column_id);
                if (!empty($add_column))
                {
                    publish_insert_query($ret, 'insert', $add_column);
                }
                $same_column = array_intersect($ori_column_id, $new_column_id);
                if (!empty($same_column))
                {
                    publish_insert_query($ret, 'update', $same_column);
                }
            }
            else
            {
                if ($new_column_id)
                {
                    $op = "insert";
                    publish_insert_query($ret, $op);
                }
            }
        }
        else
        {
            if (!empty($ret['expand_id']))
            {
                $op = "delete";
                publish_insert_query($ret, $op);
            }
        }

        /* 返回数据 */
        $sql      = " SELECT * FROM " . DB_PREFIX . "vod_media_node WHERE id = '" . $info['vod_sort_id'] . "'";
        $sort_arr = $this->db->query_first($sql);
        if ($sort_arr['id'])
        {
            $info['vod_sort_id']    = $sort_arr['name'];
            $info['vod_sort_color'] = $sort_arr['color'];
        }
        else
        {
            $info['vod_sort_id']    = $this->settings['video_upload_type'][$info['vod_leixing']];
            $info['vod_sort_color'] = $this->settings['video_upload_type_attr'][$info['vod_leixing']];
        }

        $collects = unserialize($info['collects']);
        if ($collects)
        {
            $info['collects'] = $collects;
        }
        else
        {
            $info['collects'] = '';
        }

        $img_arr          = $info['img_info'] = unserialize($info['img_info']);
        $info['img']      = $img_arr['host'] . $img_arr['dir'] . '80x60/' . $img_arr['filepath'] . $img_arr['filename'];

        $rgb = $info['bitrate'] / 100;

        if ($rgb < 10)
        {
            $info['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
        }
        else
        {
            $info['bitrate_color'] = $this->settings['bitrate_color'][9];
        }
        if ($info['starttime'])
        {
            $info['starttime'] = '(' . date('Y-m-d', $r['starttime']) . ')';
        }
        else
        {
            $info['starttime'] = '';
        }

        $info['duration']       = time_format($info['duration']);
        $info['status_display'] = intval($info['status']);
        $info['status']         = $this->settings['video_upload_status'][$info['status']];
        $info['create_time']    = date('Y-m-d H:i', $info['create_time']);
        $info['update_time']    = date('Y-m-d H:i', $info['update_time']);
        $info['pub']            = unserialize($info['column_id']);
        $info['pub_url']        = unserialize($info['column_url']);
        $info['row_id']         = $info['id'];
        $this->addItem($info);
        $this->output();
    }

    /* 功能:请求更新图片接口对图片进行更新
     * 返回值:无
     * */

    public function change_pic()
    {
        $this->local_curl->setSubmitType('get');
        $this->local_curl->initPostData();
        $this->local_curl->addRequestData('id', $this->input['id']);
        $this->local_curl->addRequestData('img_src', ($this->input['img_src']));
        $this->local_curl->addRequestData('img_src_cpu', ($this->input['img_src_cpu']));
        $this->local_curl->addRequestData('source_img_pic', ($this->input['source_img_pic']));
        $this->local_curl->addRequestData('a', 'update_img');
        $this->local_curl->request('vod_update_img.php');
    }

    //更新图片，并且将图片保存到图片服务器上
    public function change_pic_server()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $img_info = array();
        //先查出有没有从本地上传图片，如果有的话采用本地上传的图片
        if ($this->input['img_src_cpu'])
        {
            $img_path = ($this->input['img_src_cpu']);
            if (strrpos($img_path, '?'))
            {
                $img_path = substr($img_path, 0, strrpos($img_path, '?'));
            }
            $img_info = $this->create_thumb($img_path, $this->input['id']);
        }
        else
        {
            if ($this->input['img_src'])
            {
                $img_path = ($this->input['img_src']);
                $img_info = $this->create_thumb($img_path, $this->input['id']);
            }
            else if ($this->input['source_img_pic'])
            {
                if ($this->check_img_url($this->input['source_img_pic']))
                {
                    $img_path = ($this->input['source_img_pic']);
                    if (strrpos($img_path, '?'))
                    {
                        $img_path = substr($img_path, 0, strrpos($img_path, '?'));
                    }
                    $img_info = $this->create_thumb($img_path, $this->input['id']);
                }
                else
                {
                    $img_info = $this->imgdata2pic($this->input['source_img_pic']);
                }
            }
            else
            {
                return; //没有传图片直接return
            }
        }

        if ($img_info)
        {
            $img_info_arr = array(
                'host' => $img_info['host'],
                'dir' => $img_info['dir'],
                'filepath' => $img_info['filepath'],
                'filename' => $img_info['filename'],
                'imgwidth' => $img_info['imgwidth'],
                'imgheight' => $img_info['imgheight'],
            );
            $sql          = "UPDATE " . DB_PREFIX . "vodinfo  SET  ";
            $sql .= " img_info ='" . serialize($img_info_arr) . "' ";
            $sql .= "  WHERE 1 AND id=" . $this->input['id'];
            $this->db->query($sql);
        }
    }

    public function create_thumb($url, $cid = 0)
    {
        $material = new material();
        $img_info = $material->localMaterial($url, $cid);
        return $img_info[0];
    }

    public function imgdata2pic($imgdata)
    {
        //生成图片
        $data       = explode(',', $imgdata);
        $data1      = explode(';', $data[0]);
        $type       = explode('/', $data1[0]);
        $material   = new material();
        $img_info   = $material->imgdata2pic($data[1], $type[1]);
        $img_info   = $img_info[0];
        $image_info = array(
            'host' => $img_info['host'],
            'dir' => $img_info['dir'],
            'filepath' => $img_info['filepath'],
            'filename' => $img_info['filename'],
        );
        return $image_info;
    }

    //判断是不是图片链接
    private function check_img_url($url = '')
    {
        if (substr($url, 0, 7) == 'http://')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /* 参数:$img(从数据库中查询出的图片路径),$vodid(视频的vodid),$copyright_id(版本的id)
     * 功能:保存版本图片
     * 返回值:无
     * */

    public function save_copyright_pic($img, $vodid, $copyright_id)
    {
        $dir            = hg_num2dir($vodid);
        $img_path       = SOURCE_IMAGE_PATH . $img;
        $source_img_dir = SOURCE_IMG_DIR . 'copyright/' . $dir;
        $img_name       = 'copyright_' . $copyright_id;
        $this->get_source_img($img_path, $source_img_dir, $img_name);

        $sql             = " SELECT contents FROM " . DB_PREFIX . "vod_copyright WHERE id = '" . $copyright_id . "'";
        $arr             = $this->db->query_first($sql);
        $contents        = unserialize($arr['contents']);
        $contents['img'] = $dir . $img_name . '.jpg';
        $info            = serialize($contents);
        $sql             = " UPDATE " . DB_PREFIX . "vod_copyright SET contents = '" . $info . "'  WHERE id = '" . $copyright_id . "'";
        $this->db->query($sql);
    }

    //获得原图，并且将其保存在指定目录
    public function get_source_img($img_path, $source_img_dir, $img_name)
    {
        $img = "";
        $img = @file_get_contents($img_path);
        hg_mkdir($source_img_dir);
        if (is_dir($source_img_dir) && $img)
        {
            file_put_contents($source_img_dir . $img_name . ".jpg", $img);
        }
    }

    /* 参数:视频的记录id
     * 功能:更新发布状态
     * 返回值:成功success
     * */

    public function update_pub_status()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $sql = "UPDATE " . DB_PREFIX . "vodinfo SET pub_status = '" . intval($this->input['pub_status']) . "'  WHERE id = " . intval($this->input['id']);
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

    /* 设置视频可否标注 */

    public function setmark()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        $ids = explode(',', ($this->input['id']));
        $sql = "UPDATE " . DB_PREFIX . "vodinfo SET is_allow = '" . intval($this->input['is_allow']) . "'  WHERE id in (" . ($this->input['id']) . ")";
        $this->db->query($sql);
        $this->addItem($ids);
        $this->output();
    }

    /**
     * 修改权重
     * 
     */
    private function check_weight_prms($input_weight = 0, $org_weight = 0)
    {
        if ($this->user['group_type'] < MAX_ADMIN_TYPE)
        {
            return;
        }
        $set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
        if (!$set_weight_limit)
        {
            return;
        }
        if ($org_weight > $set_weight_limit)
        {
            $this->errorOutput(MAX_WEIGHT_LIMITED);
        }
        if ($input_weight > $set_weight_limit)
        {
            $this->errorOutput(MAX_WEIGHT_LIMITED);
        }
    }

    public function update_weight()
    {
        if (empty($this->input['data']))
        {
            $this->errorOutput(NODAA);
        }
        $data = htmlspecialchars_decode($this->input['data']);
        $data = json_decode($data, 1);
        $id   = @array_keys($data);
        if (!$id)
        {
            $this->errorOutput(INVALID_LIVMEDIA);
        }
        $sql   = 'SELECT id,weight FROM ' . DB_PREFIX . 'vodinfo WHERE id IN(' . implode(',', $id) . ')';
        $query = $this->db->query($sql);
        while ($row   = $this->db->fetch_array($query))
        {
            $org_weight[$row['id']] = $row['weight'];
        }
        $sql   = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
        $this->db->query($sql);
        $sql   = "INSERT INTO tmp VALUES ";
        $space = '';
        foreach ($data as $k => $v)
        {
            $sql .= $space . "(" . $k . ", " . $v . ")";
            $this->check_weight_prms($v, $org_weight[$k]);
            $space = ',';
        }
        $this->db->query($sql);
        $sql = "UPDATE " . DB_PREFIX . "vodinfo v,tmp SET v.weight = tmp.weight WHERE v.id = tmp.id";
        $this->db->query($sql);
        $this->addItem('success');
        $this->output();
    }

    //发布专题
    public function push_special()
    {
        $id_arr = explode(',',$this->input['id']);
        $spe_idarr = explode(',',$this->input['special_id']);
        $col_namearr = explode(',',$this->input['column_name']);
        $col_idarr = explode(',',$this->input['col_id']);
        $sname_idarr = explode(',',$this->input['show_name']);
        if(!$spe_idarr)
        {
            $this->errorOutput('NO_ID');
        }
        $spe_arr = array();
        if($col_idarr)
        {
            foreach($col_idarr as $k=>$v)
            {
                if($v)
                {
                    $spe_arr[$v]['id'] = $v;
                    $spe_arr[$v]['name'] = $col_namearr[$k];
                    $spe_arr[$v]['special_id'] = $spe_idarr[$k];
                    $spe_arr[$v]['show_name'] = $sname_idarr[$k];
                }
            }
        }
        if ($id_arr)
        {
            foreach ($id_arr as $k => $v)
            {
                $sql = "UPDATE " . DB_PREFIX . "vodinfo SET special = '" . serialize($spe_arr) . "' WHERE id = " . $v;
                $this->db->query($sql);

                $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id = " . $v;
                $q   = $this->db->query_first($sql);
                if ($q['expand_id'])
                {
                    //插发布队列
                    $q['column_id'] = unserialize($q['column_id']);
                    $ori_column_id  = array();
                    if (is_array($q['column_id']))
                    {
                        $ori_column_id = array_keys($q['column_id']);
                    }
                    publish_insert_query($q, 'update', $ori_column_id);
                }
            }
        }
        $this->addItem('true');
        $this->output();
    }

    public function publish()
    {
        $id = urldecode($this->input['id']);
        if (!$id)
        {
            $this->errorOutput('No Id');
        }

        if ($this->input['app_uniqueid'] == 'tv_play')
        {
            $pub_time = $this->input['pub_time'];
        }
        else
        {
            $pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
        }

        $column_id = urldecode($this->input['column_id']);
        $isbatch   = strpos($id, ',');
        if ($isbatch !== false && !$column_id)
        {
            $this->addItem(true);
            $this->output();
        }
        include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
        $this->publish_column = new publishconfig();
        $column_id            = $this->publish_column->get_columnname_by_ids('id,name,parents', $column_id);

        /*         * *************************************增加如果电视剧发布，触发剧集对应的视频库里面的视频发布**************************************** */
        if ($this->input['app_uniqueid'] == 'tv_play')
        {
            $sql = "UPDATE " . DB_PREFIX . "vodinfo SET status = '" . $this->input['status'] . "' WHERE id IN (" . $id . ")";
            $this->db->query($sql);
        }
        /*         * *************************************增加如果电视剧发布，触发剧集对应的视频库里面的视频发布**************************************** */

        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN( " . $id . ")";
        $q   = $this->db->query($sql);
        while ($row = $this->db->fetch_array($q))
        {
            $row['column_id'] = unserialize($row['column_id']);
            $ori_column_id    = array();
            if (is_array($row['column_id']))
            {
                $ori_column_id = array_keys($row['column_id']);
            }
            $ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
            if ($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
            {
                $row['column_id'] = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
            }
            else
            {
                $row['column_id'] = $column_id;
            }
            $new_column_id = array_keys($row['column_id']);
            /*             * *************************权限控制************************************** */
            //批量签发时只能新增 所以对比已经发布的栏目，会导致没有权限发布
            $published_column_id = ($isbatch !== false) ? $this->input['column_id'] : $ori_column_id_str;
            $this->verify_content_prms(array('column_id' => $this->input['column_id'], 'published_column_id' => $published_column_id));
            /*             * *************************权限控制************************************** */
            $sql           = "UPDATE " . DB_PREFIX . "vodinfo SET column_id = '" . addslashes(serialize($row['column_id'])) . "',pub_time = '" . $pub_time . "' WHERE id in (" . $row['id'].")";
            $this->db->query($sql);
            
            //记录发布库栏目分发表
            $this->update_pub_column($row['id'], implode(',', $new_column_id));
            //记录发布库栏目分发表
                        
            $row['pub_time'] = $pub_time;
            if (intval($row['status']) == 2)
            {
                if (!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
                {
                    $del_column = array_diff($ori_column_id, $new_column_id);
                    if (!empty($del_column))
                    {
                        publish_insert_query($row, 'delete', $del_column);
                    }
                    $add_column = array_diff($new_column_id, $ori_column_id);
                    if (!empty($add_column))
                    {
                        publish_insert_query($row, 'insert', $add_column);
                    }
                    $same_column = array_intersect($ori_column_id, $new_column_id);
                    if (!empty($same_column))
                    {
                        publish_insert_query($row, 'update', $same_column);
                    }
                }
                else        //未发布，直接插入
                {
                    if ($new_column_id)
                    {
                        $op = "insert";
                        publish_insert_query($row, $op);
                    }
                }
            }
            else    //打回
            {
                if (!empty($row['expand_id']))
                {
                    $op = "delete";
                    publish_insert_query($row, $op);
                }
            }
        }
        $this->addItem('true');
        $this->output();
    }

    //视频评论计数更新
    function update_comment_count()
    {
        $id = intval($this->input['id']);
        if (!$id)
        {
            $this->errorOutput(ON_ID);
        }
        //评论数目
        if ($this->input['comment_count'])
        {
            $comment_count = $this->input['comment_count'];
        }
        else
        {
            $comment_count = 1;
        }

        //审核增加评论数、打回减少评论数
        if ($this->input['type'] == 'audit')
        {
            $type = '+';
        }
        else if ($this->input['type'] == 'back')
        {
            $type = '-';
        }

        $info = array();
        if ($type)
        {
            $sql  = "UPDATE " . DB_PREFIX . "vodinfo  SET  comment_count=comment_count" . $type . $comment_count . "  WHERE id =" . $id;
            $q    = $this->db->query($sql);
            //发布
            $sql  = "SELECT id, status, expand_id, title, column_id, pub_time,addperson FROM " . DB_PREFIX . "vodinfo WHERE id =" . $id;
            $info = $this->db->query_first($sql);
        }

        if(empty($info))
        {
            return FALSE;
        }
        
        if ($info['status'] == 2)
        {
            $op = '';
            if (!empty($info['expand_id']))
            {
                $op = "update";
            }
            else
            {
                if (unserialize($info['column_id']))
                {
                    $op = "insert";
                }
            }
        }
        else if ($info['status'] == 3)
        {
            if (!empty($info['expand_id']))
            {
                $op = "delete";    //expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
            }
            else
            {
                $op = "";
            }
        }
        publish_insert_query($info, $op);
        $this->addItem($id);
        $this->output();
    }

    /**
     * 同步访问统计
     */
    function access_sync()
    {
        if (!$this->input['id'])
        {
            $this->errorOutput('NOID');
        }
        $id                  = intval($this->input['id']);
        $data                = array();
        if ($this->input['click_num'])
            $data['click_count'] = intval($this->input['click_num']);
        if (!empty($data) && is_array($data))
        {
            $sql   = "UPDATE " . DB_PREFIX . "vodinfo SET ";
            $space = '';
            foreach ($data as $k => $v)
            {
                $sql.= $space . $k . "='" . $v . "'";
                $space = ',';
            }
            $sql .= " WHERE id = " . $id;
            $this->db->query($sql);
        }
        $this->addItem($data);
        $this->output();
    }

    //插入发布队列到视频库
    public function insertQueueToLivmedia()
    {
        $op = $this->input['op'];
        if (!$op || !in_array($op, array('insert', 'delete', 'update')))
        {
            $this->errorOutput('no opration');
        }

        if (!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        
        //如果现在的栏目存在就已审核否则待审核
        if ($this->input['now_colum'])
        {
            $status = ", status = 2 ";
        }
        else
        {
            $status = ", status = 3 ";
        }

        $sql = "UPDATE " . DB_PREFIX . "vodinfo SET column_id = '" . html_entity_decode($this->input['now_colum']) . "' {$status} WHERE id IN (" . $this->input['id'] . ")";
        $q   = $this->db->query($sql);
        $sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id IN (" . $this->input['id'] . ")";
        $q   = $this->db->query($sql);
        while ($r   = $this->db->fetch_array($q))
        {
            $column_id_arr = array();
            if ($this->input['column_id'])
            {
                $column_id_arr = explode(',', $this->input['column_id']);
            }
            //插入到队列
            $r['pub_time'] = $this->input['pub_time'] ? $this->input['pub_time'] : TIMENOW;
            publish_insert_query($r, $op, $column_id_arr);
        }
    }
    
    //提取视频到指定目录
    public function pickup_video()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }
        
        $this->curl->setSubmitType('get');
        $this->curl->initPostData();
        $this->curl->addRequestData('id', $this->input['id']);
        $ret = $this->curl->request('pickUpVideos.php');
        if($ret && $ret[0])
        {
            $ret = $ret[0];
        }
        $this->addItem($ret);
        $this->output();
    }

    public function redo_callback()
    {
        $id = intval($this->input['id']);
        $sql = "SELECT transcode_server FROM " . DB_PREFIX . "vodinfo WHERE id =" . $id;
        $transcode_server  = $this->db->query_first($sql);
        $transcode_server = ($tmp = @unserialize($transcode_server['transcode_server'])) ? $tmp : array();
        if(!$transcode_server)
        {
            $this->errorOutput("视频转码信息不完整");
        }
        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('id', $id);
        $curl->addRequestData('a', 'redo_callback');
        $curl->addRequestData('host', $transcode_server['host']);
        $curl->addRequestData('port', $transcode_server['port']);
        $ret = $curl->request('video_transcode.php');
        $this->addItem($ret);
        $this->output();
    }
    
    public function unknow()
    {
        $this->errorOutput(NOMETHOD);
    }
    
    //修改发布栏目分发表
    public function update_pub_column($ids, $column_ids) {
        if (!$ids) {
            return false;
        }
        $sql = "DELETE FROM " . DB_PREFIX . "pub_column WHERE aid IN(" . $ids . ")";
        $this->db->query($sql);
        
        if ($column_ids) {
            $arr_ids = explode(',', $ids);
            $ar_column_ids = explode(',', $column_ids);
            
            $sql = "INSERT INTO " . DB_PREFIX . "pub_column (aid, column_id) VALUES";
            $space = '';
            foreach ($arr_ids as $k => $v) {
                foreach ($ar_column_ids as $kk => $vv) {
                    $sql .= $space . " ('" . $v . "', '" . $vv . "')";
                    $space = ',';
                }
            }
            $this->db->query($sql);  
        }          
        return true;
    }    
    public function sync_letv()
    {
        $id = intval($this->input['id']);
        $sql = 'SELECT v.id, ve.content_id FROM ' . DB_PREFIX . 'vodinfo v 
                    LEFT JOIN ' . DB_PREFIX . 'vod_extend ve ON v.id=ve.vodinfo_id WHERE                                                        
                    v.id='.$id;
        $vodinfo = $this->db->query_first($sql);
        //$this->errorOutput(var_export($vodinfo,1));
        if($vodinfo['content_id'])
        {
            $this->errorOutput("此视频已经同步");
        }
        $curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $id = intval($this->input['id']);
        $this->curl->initPostData();
        $this->curl->setCurlTimeOut(0);//不超时
        $this->curl->addRequestData('id', $id);
        $this->curl->addRequestData('a', 'sync_letv');
        $ret = $this->curl->request('yun.php');
        if($ret[0] != 'success')
        {
            $this->errorOutput("同步错误");
        }
        $this->addItem(array('id' => $id));
        $this->output();
    }
    public function show(){}
	public function detail(){}
	public function count(){}
}

$out = new vod_update();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
    $action = 'unknow';
}
$out->$action();
?>