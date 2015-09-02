<?php
define('MOD_UNIQUEID','livmedia');
define('NOD_UNIQUEID', 'vod_node');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/vod_copyright.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/outpush.class.php');
class  vod extends adminReadBase
{
    //先调用的是__getConfig方法重写父类
    protected  $curl;
    private $default_type;
    private $outpush;
    public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'=>array(
			'name'=>'视频分类',
			'filename'=>'vod_media_node.php',
			'node_uniqueid'=>'vod_media_node',
			),
		);
		parent::__construct();
		$this->default_type = '.wmv,.avi,.dat,.asf,.rm,.rmvb,.ram,.mpg,.mpeg,.3gp,.mov,.mp4,.m4v,.dvix,.dv,.dat,.mkv,.flv,.vob,.ram,.qt,.divx,.cpk,.fli,.flc,.mod,.m4a,.f4v,.3ga,.caf,.mp3,.vob';
		$this->material = new material();
        $this->outpush = new outpush();
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	public function index()
	{
		
	}
	public function stats()
	{
		$app = array('appid' => $this->input['appid'],'appkey' => $this->input['appkey']);
		$this->input = $this->settings['App_mediaserver'];
		$this->input['appid'] = $app['appid'];
		$this->input['appkey'] = $app['appkey'];
		$this->input['dir'] .= 'admin/';
		$this->input['file'] = 'index.php';
		$upload_status = $this->check_api_state();
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$trans_status = $curl->request('index.php');
		if ($trans_status)
		{
			$diskspace = $trans_status[0]['diskspace'];
			$trans_status = $trans_status[0]['trans_status'];
		}
		$array = array(
			'upload_status' => $upload_status,
			'trans_status' => $trans_status,
			'diskspace' => array('size' => $diskspace, 'text' => hg_fetch_number_format($diskspace, true)),
		);
		$this->addItem($array);
		$this->output();
	}

	public function show()
	{
        #####
		$this->verify_content_prms();
		#####
	    $offset = $this->input['offset']?intval(($this->input['offset'])):0;
		$count = $this->input['count']?intval(($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
	
		//查询出顶级类别供下面没有分类的时候用
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE fid = 0";
		$q = $this->db->query($sql);
		$top_sorts = array();
		while($r = $this->db->fetch_array($q))
		{
			$top_sorts[$r['id']] = $r;
		}
		$orderby =$this->input['orderby_id']?' ORDER BY v.id ASC,v.video_order_id DESC':' ORDER BY v.video_order_id DESC, v.id DESC';
        //根据发布库栏目搜索
        if($this->input['pub_column_id']) {
            $condition .= " GROUP BY v.id";
            $sql = "SELECT v.*, vs.name AS sort_name,vs.color AS vod_sort_color  
                    FROM ".DB_PREFIX."vodinfo v 
                    LEFT JOIN ".DB_PREFIX."vod_media_node vs 
                        ON v.vod_sort_id = vs.id
                    LEFT JOIN ".DB_PREFIX."pub_column pc
                        ON v.id = pc.aid     
                    WHERE 1 ". $condition .$orderby . $limit;
        }
        else {
            $sql = "SELECT v.*, vs.name AS sort_name,vs.color AS vod_sort_color  FROM ".DB_PREFIX."vodinfo v LEFT JOIN ".DB_PREFIX."vod_media_node vs ON v.vod_sort_id = vs.id WHERE 1 ". $condition .$orderby.$limit;
        }  
		$q  = $this->db->query($sql);
		$this->setXmlNode('vod','item');
		$vod_info = array();

        //判断outpush状态
        $outpushInfo = $this->outpush->getOutpushInfoByAppid(APPLICATION_ID,$_REQUEST['access_token']);
        $outpush =  $outpushInfo[0] ? $outpushInfo[0]['status'] : 0;

		while($r = $this->db->fetch_array($q))
		{
			if($r['sort_name'])
			{
				$r['vod_sort_id'] = $r['sort_name'];
			}
			else
			{
				$r['vod_sort_id']    = $top_sorts[$r['vod_leixing']]['name'];
				$r['vod_sort_color'] = $top_sorts[$r['vod_leixing']]['color'];
			}
			
			$collects = unserialize($r['collects']);
			if($collects)
			{
				$r['collects'] = $collects;
			}
			else 
			{
				$r['collects'] = '';
			}
			
			$img_arr = $r['img_info'] = unserialize($r['img_info']);
			$r['img'] = '';
			if ($img_arr['filename'] == '<')
			{
				$img_arr = '';
				$r['img_info'] = '';
			}
			if($img_arr)
			{
			$r['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			}
			$rgb = $r['bitrate']/100;
			
			if($rgb < 10)
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
			}
			else 
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][9];
			}
			if($r['starttime'])
			{
				$r['starttime'] = '('.date('Y-m-d',$r['starttime']).')';
			}
			else
			{
				$r['starttime'] = '';
			}
			
			$r['start'] = $r['start'] + 1;
			$r['etime'] = intval($r['duration']) + intval($r['start']);
			if($r['duration'])
			{
				$r['video_duration'] = hg_timeFormatChinese($r['duration']);//时长
			}
			else 
			{
				$r['video_duration'] = '无';
			}
			if($r['isfile'])
			{
				$r['start'] = 0;
				$r['isfile_name'] = '是';
			}
			else 
			{
				$r['isfile_name'] = '否';
			}
			
			$r['duration'] = time_format($r['duration']);
			$r['status_display'] = intval($r['status']);
			$r['status'] = $this->settings['video_upload_status'][$r['status']];
			$r['create_time'] = hg_get_date($r['create_time']);
			$r['update_time'] = date('Y-m-d H:i',$r['update_time']);
			$r['pub'] = unserialize($r['column_id']);
			$r['pub_url'] = unserialize($r['column_url']);
			$pub_column = array();
			if ($r['pub'])
			{
				foreach($r['pub'] as $k => $v)
				{
					$pub_column[] = array(
						'column_id' => $k,
						'column_name' => $v,
						'pub_id' => intval($r['pub_url'][$k])
					);
				}
			}
			$r['pub_column'] = $pub_column;
			if($r['is_link'])
			{
				$r['video_url'] = $r['hostwork'].'/'.$r['video_path'];
			}
			else
			{
				$r['video_url'] = $r['hostwork'].'/'.$r['video_path'].MAINFEST_F4M;
			}
            $r['video_m3u8'] = $r['hostwork'].'/'.$r['video_path'].str_replace('.mp4', '.m3u8', $r['video_filename']);
			$r['frame_rate'] =  number_format($r['frame_rate'],3).'fps';
			if($this->settings['App_mediaserver'])
			{
				if(!$this->settings['App_mediaserver']['protocol'])
				{
					$this->settings['App_mediaserver']['protocol'] = 'http://';
				}
				$r['download'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . '/admin/download.php';
				$r['retranscode_url'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'] . '/' . $this->settings['App_mediaserver']['dir'] . '/admin/retranscode.php';
			}
			else 
			{
				$r['download'] = '';
				$r['retranscode_url'] = '';
			}
			
			if($r['totalsize'])
			{
				$r['video_totalsize'] = hg_fetch_number_format($r['totalsize'],1);
			}
			else 
			{
				$r['video_totalsize'] = '无';
			}
			
			$r['format_duration'] = hg_timeFormatChinese($r['duration']);//时长
			$r['resolution'] = $r['width'].'*'.$r['height'];//分辨率
			$audio_status = check_str('L','R',$r['audio_channels']);
			
			switch ($audio_status)//声道
			{
				case 0  :$r['video_audio_channels'] = '无';break;
				case 1  :$r['video_audio_channels'] = '右';break;
				case 2  :$r['video_audio_channels'] = '左';break;
				case 3  :$r['video_audio_channels'] = '左右';break;
				default :$r['video_audio_channels'] = '无';break;
			}
			$r['video_resolution'] = $r['width'].'*'.$r['height'];//分辨率
			
			//多码流的状态
			if($r['clarity'])
			{
				$r['is_do_morebit'] = '是';
			}
			else 
			{
				$r['is_do_morebit'] = '否';
			}
			
			if($r['is_morebitrate'])
			{
				$r['is_morebitrate_ok'] = '是';
			}
			else 
			{
				$r['is_morebitrate_ok'] = '否';
			}
			
			if($r['is_forcecode'])
			{
				$r['is_forcecode_ok'] = '是';
			}
			else 
			{
				$r['is_forcecode_ok'] = '否';
			}
			
			//应用
			if(!$r['app_uniqueid'])
			{
				$r['app_uniqueid'] = APP_UNIQUEID;
			}
			if(!empty($r['right_info']))
            {
                    $tmp_data = json_decode($r['right_info'],1);
                    $r['object_id'] = $tmp_data['ObjectID'] ? $r['id'] : 0;
            }
			$vod_info[$r['id']] = $r;
			if($r['catalog'])
			{
				$r['catalog'] =unserialize($r['catalog']);
			}
            $r['outpush'] = $outpush;
            $this->addItem($r);
		}
		$this->output();
	}
	
	//查看审核通过的
	public function news_refer_material()
	{
		$condition = '';
		if(!empty($this->input['user']))
		{
			$user = ($this->input['user']);
			$condition .=" and v.user_id='" . $this->user['user_id'] ."' AND v.vod_leixing != 2";
		}
		if(!empty($this->input['key']))
		{
			$key = ($this->input['key']);
			$condition .=" and v.title like '%" . $key ."%'";
		}
		if(!empty($this->input['sort_id']))
		{
			$sort_id = intval($this->input['sort_id']);
			if($sort_id < 5)
			{
				$condition .= " AND v.vod_leixing = " . $sort_id;	
			}
			else
			{
				$condition .=" and v.vod_sort_id = " . $sort_id;
			}
		}
		
		$condition .= ' and status = 2 and img_info != ""';
		
	    $offset = $this->input['offset']?intval(($this->input['offset'])):0;
		$count = $this->input['count']?intval(($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		if(!empty($key))
		{
			$limit = '';
		}
		$sql = "SELECT v.*, vs.name AS sort_name FROM ".DB_PREFIX."vodinfo v LEFT JOIN ".DB_PREFIX."vod_media_node vs ON v.vod_sort_id = vs.id WHERE 1" . $condition." ORDER BY v.video_order_id DESC, v.id DESC ".$limit;
		$q  = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{			
			$r['create_time'] = date('Y-m-d H:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d H:i',$r['update_time']);
			$r['time'] = hg_tran_time($r['update_time']);
			$r['app_bundle'] = APP_UNIQUEID;
			$r['module_bundle'] = MOD_UNIQUEID;
			$r['img'] = unserialize($r['img_info']);
			$this->addItem($r);
		}
		$this->output();
	}	
	
	public function news_refer_sort()
	{
		$info = array();
		$info[] = array('name' => '全部分类','brief' => '全部分类','fid' => 0,'is_last' => 1,'sort_id' => 0);
		
		if(!empty($this->input['fid']))
		{
			$fid = intval($this->input['fid']);
			$sql = "select * from " . DB_PREFIX ."vod_media_node where fid = " . $fid;
			$q = $this->db->query($sql);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			if(!empty($ret))
			{
				foreach($ret as $k => $v)
				{
					$v['fid'] = $v['sort_id'] = $v['id'];
					$info[] =  $v;
				}
			}
		}
		else 
		{
			$sql = "select * from " . DB_PREFIX ."vod_media_node where fid = 0";
			$q = $this->db->query($sql);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			if(!empty($ret))
			{
				foreach($ret as $k => $v)
				{
					$v['fid'] = $v['sort_id'] = $v['id'];
					$info[] = $v;	
				}
			}
		}
		
		if(!empty($info))
		{
			foreach ($info as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
		
	}
	
	public function count()
	{
        $condition = $this->get_condition();
        //根据发布库栏目搜索
        if($this->input['pub_column_id']) {
            $condition .= " GROUP BY v.id";
            $sql = "SELECT COUNT(*) AS total FROM (
                        SELECT v.id FROM ".DB_PREFIX."vodinfo v
                        LEFT JOIN ".DB_PREFIX."pub_column pc 
                            ON v.id=pc.aid 
                        WHERE 1 " . $condition ."
                    ) aa";            
        }
        else {	    
		  $sql = 'SELECT count(*) as total from '.DB_PREFIX.'vodinfo v WHERE 1 '.$condition;
        }
		$vodinfo_total = $this->db->query_first($sql);
		echo json_encode($vodinfo_total);		
	}
	
	//已审核的总数
	public function news_refer_count()
	{
		$sql = "select count(*) as total from " .DB_PREFIX ."vodinfo where 1 and status = 2";
		$total = $this->db->query_first($sql);
		$this->addItem($total);
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = "";
        //搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if ( in_array( $k, array('_id') ) )
                {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = $v;
            }
        }
         //搜索标签
        		
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND v.user_id = '.$this->user['user_id'];
			}
			else 
			{
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND v.org_id IN('.$this->user['slave_org'].')';
				}
			}
			
			$authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($authnode && $authnode[0] != '-1')
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str)
				{
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'vod_media_node WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					//算出所有允许的节点
					$auth_nodes = array();
					foreach($authnode_array AS $k => $v)
					{
						$auth_nodes = array_merge($auth_nodes,$v);
					}
					
					//如果没有_id就查询出所有权限所允许的节点下的视频包括其后代元素
					if(!$this->input['_id'])
					{
						$condition .= " AND v.vod_sort_id IN (".implode(',', $auth_nodes).")";
					}
					else if(in_array($this->input['_id'],$auth_nodes))
					{
						if(isset($authnode_array[$this->input['_id']]) && $authnode_array[$this->input['_id']])
						{
							$condition .= " AND v.vod_sort_id IN (".implode(',', $authnode_array[$this->input['_id']]).")";
						}
						else 
						{
							$sql = "SELECT id,childs FROM ".DB_PREFIX."vod_media_node WHERE id = '" .$this->input['_id']. "'";
							$childs_nodes = $this->db->query_first($sql);
							$condition .= " AND v.vod_sort_id IN (".$childs_nodes['childs'].")";
						}
					}
					else 
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
			else 
			{
				if($this->input['_id'])
				{
					$sql = " SELECT childs, fid FROM ".DB_PREFIX."vod_media_node WHERE  id = '".$this->input['_id']."'";
					$arr = $this->db->query_first($sql);
					if($arr)
					{
						$condition .= " AND v.vod_sort_id IN (".$arr['childs'].")";
					}
				}
			}
		}
		else 
		{
			if($this->input['_id'])
			{
				$sql = " SELECT childs, fid FROM ".DB_PREFIX."vod_media_node WHERE  id = '".$this->input['_id']."'";
				$arr = $this->db->query_first($sql);
				if($arr)
				{
					$condition .= " AND v.vod_sort_id IN (".$arr['childs'].")";
				}
			}
		}

		####增加权限控制 用于显示####
		if($this->input['id'])
		{
			$condition .= " AND v.id in (".($this->input['id']).")";
		}
		if($this->input['max_id'])//自动化任务用到.
		{
			$condition .= " AND v.id >".intval($this->input['max_id']);
		}
		
		if($this->input['comment'])
		{
			$condition .= ' AND v.comment LIKE "%'.($this->input['comment']).'%"';
		}
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  v.title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		if($this->input['user_name'])
		{
			$condition .= " AND v.addperson = '" . $this->input['user_name'] . "' ";
		}
		
		if($this->input['user_id'])
		{
			$condition .= " AND v.user_id = '" . $this->input['user_id'] . "' ";
		}
		
		if($this->input['author'])
		{
			$condition .= ' AND v.author LIKE "%'.($this->input['author']).'%"';
		}
		
		if($this->input['title'])
		{
			$condition .= ' AND v.title LIKE "%'.($this->input['title']).'%"';
		}
		
		if($this->input['key'] || trim(($this->input['key']))== '0')
		{
			$condition .= ' AND  v.title  LIKE "%'.trim(($this->input['key'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND v.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND v.create_time <= '".$end_time."'";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND v.weight >=" . $this->input['start_weight'];
		}
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND v.weight <= " . $this->input['end_weight'];
		}
				
		if($this->input['trans_status'] && ($this->input['trans_status'])!= -2)
		{
			$condition .= " AND v.status = '".($this->input['trans_status'])."'";
		}
		else if(($this->input['trans_status']) == '0')//此处为了区分状态0的情况与传过来的值为空的情况，为空的时候查出所有
		{
			$condition .= " AND v.status = 0 ";
		}
		
		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  v.create_time > '".$yesterday."' AND v.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  v.create_time > '".$today."' AND v.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.create_time > '".$last_threeday."' AND v.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.create_time > '".$last_sevenday."' AND v.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		//加限制显示来自于哪些应用的视频
		if(defined('LIMIT_NOT_SHOW') && LIMIT_NOT_SHOW)
		{
			$_limit_app = explode(',',LIMIT_NOT_SHOW);
			
			foreach ($_limit_app AS $_k => $_v)
			{
				$condition .= " AND v.app_uniqueid != '" .$_v. "' ";
			}
		}
        
        if ($this->input['pub_column_id']) {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishconfig = new publishconfig();
            $pub_column_id = $publishconfig->get_column_by_ids('id, childs', $this->input['pub_column_id']);
            foreach ((array)$pub_column_id as $k => $v) {
                $column_id[]= $v['childs'];
            }
            $column_id = implode("','", $column_id);
            if ($column_id) {
                $condition .= " AND pc.column_id IN('" . $column_id . "')";
            }            
        }
		return $condition;
	}
	
	/*参数:视频的id
	 *功能:进入编辑页面时显示该视频的一些基本信息
	 *返回值:$vod_result(数组)
	 **/
	public function detail()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$is_fast_edit = array();
		$sql = "SELECT * FROM ".DB_PREFIX."vod_mark_video  WHERE  original_id IN (".$this->input['id'].")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$is_fast_edit[$row['original_id']][$row['id']] = $row;
		}

		$sql = "SELECT v.*,vm.name AS vod_sort_name FROM ".DB_PREFIX."vodinfo v LEFT JOIN " .DB_PREFIX. "vod_media_node vm ON v.vod_sort_id = vm.id  WHERE v.id IN( " . $this->input['id'] . ")"; 
		$qs = $this->db->query($sql);
		while($vod_result = $this->db->fetch_array($qs))
		{
			if(!$vod_result['vod_sort_name'])
			{
				$vod_result['vod_sort_name'] = $this->settings['video_upload_type'][$vod_result['vod_leixing']];
			}

			if($vod_result['isfile'])
			{
				$vod_result['start'] = 0;
			}
			
			$vod_result['title'] 	= trim($vod_result['title']);
			$vod_result['comment'] 	= trim($vod_result['comment']);
			$vod_result['subtitle'] = trim($vod_result['subtitle']);
			$vod_result['keywords'] = trim($vod_result['keywords']);
			$vod_result['author'] 	= trim($vod_result['author']);
			$vod_result['column_id'] = unserialize($vod_result['column_id']);
			$vod_result['column_url'] = unserialize($vod_result['column_url']);
			$pub_column = array();
			if ( is_array($vod_result['column_id']) && $vod_result['column_id'] )
			{
				$column_id = array();
				foreach($vod_result['column_id'] as $k => $v)
				{
					$column_id[] = $k;
					$pub_column[] = array(
                                    "column_id" => $k,
                                    "column_name" => $v,
									'pub_id' => intval($vod_result['column_url'][$k])
                            );
				}
				$column_id = implode(',', $column_id);
				$vod_result['column_id'] = $column_id;
			}
			$vod_result['pub_column'] = $pub_column;
			$vod_result['pub_time'] = $vod_result['pub_time'] ? date("Y-m-d H:i", $vod_result['pub_time']) : '';
			
			$img_arr = unserialize($vod_result['img_info']);
			if ($img_arr['filename'] == '<')
			{
				$img_arr = '';
			}
			$vod_result['img_info'] = $img_arr;
			$vod_result['img'] = $img_arr['host'].$img_arr['dir'].$img_arr['filepath'].$img_arr['filename'];
			$vod_result['source_img'] = $vod_result['img'];
			if($vod_result['is_link'])
			{
				$vod_result['video_url'] = $vod_result['hostwork'].'/'.$vod_result['video_path'];
			}
			else
			{
				$vod_result['video_url'] = $vod_result['hostwork'].'/'.$vod_result['video_path'].MAINFEST_F4M;
			}
			$vod_result['video_m3u8'] = $vod_result['hostwork'].'/'.$vod_result['video_path'].str_replace('.mp4', '.m3u8', $vod_result['video_filename']);
			$vod_result['snapUrl'] = $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'].'/'.$this->settings['App_mediaserver']['dir'].'admin/snap.php';
			
			if($vod_result['totalsize'])
			{
				$vod_result['video_totalsize'] = hg_fetch_number_format($vod_result['totalsize'],1);
			}
			else 
			{
				$vod_result['video_totalsize'] = '无';
			}
			
			if($vod_result['duration'])
			{
				$vod_result['video_duration'] = hg_timeFormatChinese($vod_result['duration']);//时长
			}
			else 
			{
				$vod_result['video_duration'] = '无';
			}
			$vod_result['format_duration'] = hg_timeFormatChinese($vod_result['duration']);//时长
			$vod_result['resolution'] = $vod_result['width'].'*'.$vod_result['height'];//分辨率
			$vod_result['frame_rate'] =  number_format($vod_result['frame_rate'],3).'fps';
			$audio_status = check_str('L','R',$vod_result['audio_channels']);
			
			switch ($audio_status)//声道
			{
				case 0  :$vod_result['video_audio_channels'] = '无';break;
				case 1  :$vod_result['video_audio_channels'] = '右';break;
				case 2  :$vod_result['video_audio_channels'] = '左';break;
				case 3  :$vod_result['video_audio_channels'] = '左右';break;
				default :$vod_result['video_audio_channels'] = '无';break;
			}
			
			$vod_result['video_resolution'] = $vod_result['width'].'*'.$vod_result['height'];//分辨率
			$vod_result['snap_img'] = '';//$this->get_video_imgs($vod_result['id'],$vod_result['duration']);
			if(isset($is_fast_edit[$vod_result['id']]) && !empty($is_fast_edit[$vod_result['id']]))
			{
				$vod_result['is_fast_edit'] = 0;
			}
			else 
			{
				$vod_result['is_fast_edit'] = 1;
			}

			if($vod_result['status'] == 2)
			{
				$vod_result['pubstatus'] = 1;
			}
			else 
			{
				$vod_result['pubstatus'] = 0;
			}
			if($vod_result['catalog'])
			{
				$vod_result['catalog'] =unserialize($vod_result['catalog']);
			}
			$this->addItem($vod_result);
		}
		$this->output();
	}
	
	public function get_video_imgs($id,$duration)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('count',5);
		$curl->addRequestData('stime',0);
		$curl->addRequestData('etime',$duration);
		$img_arr = $curl->request('snap.php');
		return $img_arr[0];
	}
	
	/*
	 * 获取引用素材的详细信息，文搞库调用
	 * 
	 */
	public function refer_detail()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = array();
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo  WHERE id = " . intval($this->input['id']);
		$info = $this->db->query_first($sql);	
		$ret['type'] = "vod";
		$ret['title'] = $info['title'];
		$ret['time'] = date('Y-m-d H:i',$info['create_time']);
		
		$ret['img'] = unserialize($info['img_info']);
		
		$ret['keywords'] = $info['keywords'];
		
		$info['totalsize'] = hg_bytes_to_size($info['totalsize']);
		$ret['size'] = $info['totalsize'];
		$ret['duration'] = hg_toff_time(0,$info['duration']);			
		$ret['flashvars'] = array(
			'startTime' => $info['start'],
			'duration'  => $info['duration'],
			//'videoUrl'  => $info['hostwork'].'/'.$info['video_path'].MAINFEST_F4M,
            'videoUrl'=> $info['hostwork'].'/'.$info['video_path'].str_replace('.mp4', '.m3u8', $info['video_filename']),
			'snapUrl'   => $this->settings['App_mediaserver']['protocol'].$this->settings['App_mediaserver']['host'].'/'.$this->settings['App_mediaserver']['dir'].'admin/snap.php',
			'videoId'   => intval($this->input['id']),
			'aspect'    => $info['aspect'],
 		);
 		//如果是链接上传，返回原始链接和swf信息https://redmine.hoge.cn/issues/3531 
 		if ($info['is_link'])
 		{
 		    $ret['is_link'] = $info['is_link'];
 		    $ret['ori_url'] = $info['ori_url'];
		    $ret['swf']     = $info['swf']; 		    
 		}
		//查询分类
		$sql = "select * from " . DB_PREFIX ."vod_media_node where id = " . $info['vod_sort_id'];
		$sort_info = $this->db->query_first($sql);
		$ret['sort_name'] = $sort_info['name'];
		$this->addItem($ret);
		$this->output();
	}

	
	public function get_sketch_map()
	{
		if(!$this->input['id'])
		{
			return false;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo  WHERE id = " . intval($this->input['id']); 
		$ret = $this->db->query_first($sql);
		$ret['img_info'] = unserialize($ret['img_info']);
		$srcPath = $ret['img_info']['dir'] . $ret['img_info']['filepath'] . $ret['img_info']['filename'];
		//获取当前脚本名称
		$url = $_SERVER['PHP_SELF'];
		$scriptname = end(explode('/',$url));
		$scriptname = explode('.', $scriptname);
		$scriptname = $scriptname[0];
		$newName = $scriptname .'_'. $ret['id'].".png";
		$title = hg_cutchars($ret['title'],15);
		$url = $this->material->create_sketch_map($srcPath,$newName,$title,'vod');
		$this->addItem($url);
		$this->output();				
	}
	
	
	//视频播放器请求视频数据   不支持批量
	public function id2videoid()
	{
		$id = intval($this->input['id']);
		$video = array();
		if(!$id)
		{
			return $video;
		}
		$sql = 'SELECT *  FROM '.DB_PREFIX.'vodinfo WHERE id = '.$id;
		$video = $this->db->query_first($sql);
		$img_arr = $video['img_info'] = unserialize($video['img_info']);
		$video['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
		$video['duration'] = time_format($video['duration']);
		$video['totalsize'] = hg_fetch_number_format($video['totalsize'],true);
		$this->addItem($video);
		$this->output();
	}
	
	/*参数:视频的记录id
	 *功能:获取一些基本的视频信息
	 *返回值:$video(数组)
	 * */
	public function get_video()
	{
		$id = intval($this->input['id']);
		$video = array();
		if(!$id)
		{
			return $video;
		}
		$sql = 'SELECT *  FROM '.DB_PREFIX.'vodinfo WHERE id = '.$id;
		$video = $this->db->query_first($sql);
		$img_arr = $video['img_info'] = unserialize($video['img_info']);
		$video['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
		$video['duration'] = time_format($video['duration']);
		$video['totalsize'] = hg_fetch_number_format($video['totalsize'],true);
		unset($video['comment']);
		unset($video['collects']);
		$this->addItem($video);
		$this->output();
	}
	
	/*参数:视频的记录ids
	 *功能:获取一些基本的视频信息
	 *返回值:$video(数组)
	 * */
	public function get_videos()
	{
		$id = $this->input['id'];
		$video = array();
		if(!$id)
		{
			return $video;
		}
		$sql = 'SELECT id,img_info,duration,totalsize,is_audio  FROM '.DB_PREFIX.'vodinfo WHERE id IN (' . $id . ')';
		$q = $this->db->query($sql);
		
		while ($video = $this->db->fetch_array($q))
		{
			$img_arr =  array();
			$img_arr = $video['img_info'] = unserialize($video['img_info']);
			$video['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
			$video['duration'] = time_format($video['duration']);
			$video['totalsize'] = hg_fetch_number_format($video['totalsize'],true);
			$this->addItem_withkey($video['id'], $video);
		}
		$this->output();
	}
	
	//重写父类获取config的方法
	public function __getConfig()
	{
		//获取mediaserver的里面视频类型的配置
		if($this->settings['App_mediaserver'])
		{
			$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir']);
			$curl->setReturnFormat('json');
			$curl->setCurlTimeOut(10);//设置curl超时时间10秒
			$curl->initPostData();
			$curl->addRequestData('a','settings');
			$m_config = $curl->request('configuare.php');
		}
		if($m_config && is_array($m_config) && $m_config['base']['video_type']['allow_type'])
		{
			$video_type = $m_config['base']['video_type']['allow_type'];
		}
		else 
		{
			$video_type = $this->default_type;
		}
		$video_type_arr = explode(',',$video_type);
		$flash_video_type = '';
		foreach($video_type_arr AS $k => $v)
		{
			$flash_video_type .= '*' . $v . ';'; 
		}
		$video_types = str_replace('.','',$video_type);
		$this->settings['flash_video_type'] = $flash_video_type;
		$this->settings['video_type'] = $video_types;
		
		//增加第三方对接云配置输出
		$this->settings['video_cloud'] = array(
		'open' => $m_config['base']['video_cloud'],
		'title'=>$m_config['base']['video_cloud_title'],
		);
		parent::__getConfig();
	}
	
	//为了调批量编辑的模板
	public function batch_edit()
	{
		
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	
	public function get_scolumn()
	{
		$id = $this->input['id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "vodinfo WHERE id=" . $id;
		$f = $this->db->query_first($sql);
		$colinfo = unserialize($f['special']);
		
		$col_arr = array();
		if($colinfo && is_array($colinfo))
		{
			foreach($colinfo as $k=>$v)
			{
				if($k)
				{
					$v['show_name'] = str_replace("&gt;", '>', $v['show_name']);
					$col_arr[] = array(
							'column_id' 	=>		$k,
							'column_name' 	=>		$v['name'],
							'showName' 		=>		$v['show_name'],
							'special_id' 	=>		$v['special_id'],
					);
				}
			}
		}
		$this->addItem($col_arr);
		$this->output();
	}
	public function get_video_status()
	{
        $id = intval($this->input['id']);
        $sql = "SELECT transcode_server,status FROM " . DB_PREFIX . "vodinfo WHERE id =" . $id;
        $transcode_server  = $this->db->query_first($sql);
        if($transcode_server['status'] >= 1)
        {
        	$ret = array(
        		'return' => "success",
				'reason' => "",
				'id' => $id,
        	);
	        $this->addItem($ret);
	        $this->output();
        }
        $transcode_server = ($tmp = @unserialize($transcode_server['transcode_server'])) ? $tmp : array();
        if(!$transcode_server)
        {
        	$this->errorOutput("视频转码信息不完整");
        }
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id', $id);
		$curl->addRequestData('a', 'get_transcode_status');
		$curl->addRequestData('host', $transcode_server['host']);
		$curl->addRequestData('port', $transcode_server['port']);
		$ret = $curl->request('video_transcode.php');
		$this->addItem($ret);
        $this->output();
	}
	
	public function statistics()
	{
		$return['static'] = 1;
  		$static_date = $this->input['static_date'];
    	if($static_date)
    	{
    		$date = strtotime($static_date);
    	}
    	else 
    	{
    		$date = strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));
    	}
    	$sql = 'SELECT childs FROM '.DB_PREFIX.'vod_media_node WHERE id =2';
    	$node_ids = $this->db->query_first($sql);
    	if($node_ids['childs'])
    	{
    		$con = ' AND vod_sort_id NOT IN('.$node_ids['childs'].') ';
    	}
    	$sql = 'SELECT id,status,user_id,addperson,org_id,column_id,expand_id FROM '.DB_PREFIX.'vodinfo WHERE  1 AND create_time >= '.$date .' and create_time < '. ($date+86400) .$con;
    	$query = $this->db->query($sql);
    	while($r = $this->db->fetch_array($query))
    	{
    		$ret[$r['user_id']]['org_id'] = $r['org_id'];
    		$ret[$r['user_id']]['user_name'] = $r['addperson'];
    		$ret[$r['user_id']]['count']++;
    		$r['status'] == 2 ? $ret[$r['user_id']]['statued']++ : false;
    		$r['status'] == 3 ? $ret[$r['user_id']]['unstatued']++ : false;
    		$r['column_id'] ? $ret[$r['user_id']]['publish']++ : false;
    		$r['expand_id'] ? $ret[$r['user_id']]['published']++ : false;
    		if($r['column_id'])
    		{
    			$columns = unserialize($r['column_id']);
    			if($columns)
    			foreach ($columns as $column_id => $column_name)
    			{
	    			$ret[$r['user_id']]['column'][$column_id]['column_name'] = $column_name;
	    			$ret[$r['user_id']]['column'][$column_id]['total']++;
    				if($r['expand_id'])
	    			{
	    				$ret[$r['user_id']]['column'][$column_id]['success']++;
	    			}
    			}
    		}
     	}
     	$return['data'] = $ret;
    	$this->addItem($return);
    	$this->output();		
	}
	//云视频表单初始化
	public function get_form_api()
	{
		if(!$this->settings['cloud_video']['open'])
		{
			$this->errorOutput('已关闭云视频功能');
		}
		$access_token = @file_get_contents(CACHE_DIR . 'cloud_token.txt');
		if(!$access_token)
		{
			//调用oauth获取access_token
	    	$userinfo = $this->oauth_login();
	    	$access_token = $userinfo['token'];
	    	@file_put_contents(CACHE_DIR . 'cloud_token.txt', $access_token);
		}
		
		$url = $this->settings['cloud_video']['userspaceapi'] . '/' . 'user.php?a=get_form_api';
		$postdatas = array(
		'status'=>1,
		'title'=>rawurldecode($this->input['title']),
		'client_id'=>$this->settings['cloud_video']['client_id'],
		'access_token'=> $access_token,
		);
		$responce = $this->curl_post($url, $postdatas);
	    if(is_array($responce) && $responce['ErrorCode'] || $responce['ErrorText'])
	    {
	    	//调用oauth获取access_token
	    	$userinfo = $this->oauth_login();
	    	@file_put_contents(CACHE_DIR . 'cloud_token.txt', $userinfo['token']);
	    	$postdatas['access_token'] = $userinfo['token'];
	    	$responce = $this->curl_post($url, $postdatas);
	    }
	    $responce =$responce[0];
	    $this->addItem($responce);
	    $this->output();
	    
	}
	//用户登录获取视频token 用于访问userspaceapi
	protected function oauth_login($times = 3)
	{
		$url = $this->settings['cloud_video']['cloud_video_oauth'];
		$postdatas = array(
		'username'=>$this->settings['cloud_video']['user'],
		'password'=>$this->settings['cloud_video']['pass'],
		);
		
		for ($i=1;$i<=$times;$i++)
		{
			$responce = $this->curl_post($url, $postdatas);
			if($responce['status'] == 'error')
			{
				continue;
			}
			break;
		}
		return $responce['data'];
	}
	protected function curl_post($url, $postdatas = array())
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdatas);
	    curl_setopt($ch, CURLOPT_HEADER, false);
	    $responce = json_decode(curl_exec($ch),true);
	    curl_close($ch);
	    return $responce;
	}
}

$out = new vod();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>