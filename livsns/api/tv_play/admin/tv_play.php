<?php
define('MOD_UNIQUEID','tv_play');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/tv_play_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/livmedia.class.php');
class tv_play extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		$this->mPrmsMethods = array(
			'show'			=>'查看',
			'create'		=>'增加',
			'update'		=>'修改',
			'delete'		=>'删除',
			'audit'			=>'审核',
			'_node'			=>array(
				'name'			=>'电视剧分类',
				'filename'		=>'play_sort.php',
				'node_uniqueid'	=>'play_sort',
			),
		);
		parent::__construct();
		$this->mode = new TVPlayMode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$this->verify_content_prms();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition();
		$orderby = '  ORDER BY t.order_id DESC,t.id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		
		/*************************************权限控制**************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND t.user_id = '.$this->user['user_id'];
			}
			else 
			{
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND t.org_id IN('.$this->user['slave_org'].')';
				}
			}
			
			$authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($authnode)
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str)
				{
					$sql = 'SELECT id,childs FROM '.DB_PREFIX.'play_sort WHERE id IN('.$authnode_str.')';
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
						$condition .= " AND t.play_sort_id IN (".implode(',', $auth_nodes).",0)";
					}
					else if(in_array($this->input['_id'],$auth_nodes))
					{
						if(isset($authnode_array[$this->input['_id']]) && $authnode_array[$this->input['_id']])
						{
							$condition .= " AND t.play_sort_id IN (".implode(',', $authnode_array[$this->input['_id']]).")";
						}
						else 
						{
							$sql = "SELECT id,childs FROM ".DB_PREFIX."play_sort WHERE id = '" .$this->input['_id']. "'";
							$childs_nodes = $this->db->query_first($sql);
							$condition .= " AND t.play_sort_id IN (".$childs_nodes['childs'].")";
						}
					}
					else 
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
		}
		else 
		{
			if($this->input['_id'])
			{
				$sql = " SELECT childs, fid FROM ".DB_PREFIX."play_sort WHERE  id = '".$this->input['_id']."'";
				$arr = $this->db->query_first($sql);
				if($arr)
				{
					$condition .= " AND t.play_sort_id IN (".$arr['childs'].")";
				}
			}
		}
		/*************************************权限控制**************************************/
		
		if($this->input['id'])
		{
			$condition .= " AND t.id IN (".($this->input['id']).")";
		}
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  t.title  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND t.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND t.create_time <= '".$end_time."'";
		}
		
		//按电视剧等级
		if($this->input['play_grade'])
		{
			$condition .= " AND t.play_grade = '".$this->input['play_grade']."' ";
		}
		
		//按电视剧类型
		if($this->input['type'])
		{
			$condition .= " AND t.type IN (".$this->input['type'].") ";
		}
		
		//按年代
		if($this->input['year'])
		{
			$condition .= " AND t.year = '".$this->input['year']."' ";
		}
		
		//按语言
		if($this->input['lang'])
		{
			$condition .= " AND t.lang = '".$this->input['lang']."' ";
		}
		
		//按地区
		if($this->input['district'])
		{
			$condition .= " AND t.district = '".$this->input['district']."' ";
		}
		
		//按首字母查询
		if($this->input['initial'] && intval($this->input['initial']) != -1)
		{
			$condition .= " AND t.initial = '".$this->input['initial']."' ";
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
					$condition .= " AND  t.create_time > '".$yesterday."' AND t.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  t.create_time > '".$today."' AND t.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  t.create_time > '".$last_threeday."' AND t.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  t.create_time > '".$last_sevenday."' AND t.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/****************************************一些扩展的操作***********************************************/
	//获取电视剧类型编目
	public function get_tv_play_type()
	{
		$ret = $this->mode->get_tv_play_type();
		$this->addItem($ret);
		$this->output();
	}
	
	//获取电视剧语言编目
	public function get_tv_play_lang()
	{
		$ret = $this->mode->get_tv_play_lang();
		$this->addItem($ret);
		$this->output();
	}
	
	//获取电视剧年份编目
	public function get_tv_play_year()
	{
		$ret = $this->mode->get_tv_play_year();
		$this->addItem($ret);
		$this->output();
	}
	
	//获取电视剧地区编目
	public function get_tv_play_district()
	{
		$ret = $this->mode->get_tv_play_district();
		$this->addItem($ret);
		$this->output();
	}
	
	//获取电视剧版权商编目
	public function get_tv_play_publisher()
	{
		$ret = $this->mode->get_tv_play_publisher();
		$this->addItem($ret);
		$this->output();
	}
	
	//获取首字母
	public function get_tv_play_initial()
	{
		$ret = $this->mode->get_initial_info();
		$this->addItem($ret);
		$this->output();
	}
	
	//剧集上传之后，获取视频转码进度
	public function get_video_status()
	{
		//视频的id
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}

		//判断是否安装视频库
		if(!$this->settings['App_livmedia'])
		{
			$this->errorOutput(NO_INSTALL_LIVMEDIA);
		}
		
		$media = new livmedia();
		$ret = $media->get_video_status($this->input['video_id']);
		$this->addItem($ret);
		$this->output();
	}
	
	//剧集播放功能(单个)
	public function play_episode()
	{
		//剧集的id
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		//根据剧集的id获得该剧集的视频id
		$episode = $this->mode->get_episode_info($this->input['id']);
		if(!$episode)
		{
			$this->errorOutput(NO_DATA);
		}

		$video_id = $episode[0]['video_id'];
		
		$media = new livmedia();
		$video = $media->get_videos($video_id);
		$this->addItem($video[0]);
		$this->output();
	}
	
	//获取视频上传的类型
	public function getVideoTypes()
	{
		//获取mediaserver的里面视频类型的配置
		if($this->settings['App_mediaserver'])
		{
			$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
			$curl->setReturnFormat('json');
			$curl->initPostData();
			$curl->addRequestData('a','__getConfig');
			$m_config = $curl->request('index.php');
		}
		
		if($m_config && is_array($m_config))
		{
			$video_type = $m_config[0]['video_type']['allow_type'];
			$video_type_arr = explode(',',$video_type);
			$flash_video_type = '';
			foreach($video_type_arr AS $k => $v)
			{
				$flash_video_type .= '*' . $v . ';'; 
			}
			$this->addItem(array('videoTypes' => $flash_video_type));
			$this->output();
		}
	}
        
    //根据类型id获取电视剧类型  add:donghuichun
    public function get_tv_play_type_by_id()
    {
        $id = intval($this->input['id']);
        $ret = $this->mode->get_tv_play_type($id);
        if($ret[0] && is_array($ret[0]))
        {
            $ret = $ret[0];
        }
        else
        {
            $ret = array();
        }
        $this->addItem($ret);
        $this->output();
    }
    
    //获取转码服务器
    public function getTranscodeServers()
    {
    	$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
        $curl->setSubmitType('get');
        $curl->initPostData();
        $curl->addRequestData('a', 'getCanUseServers');
        $ret  = $curl->request('transcode_center.php');
        if($ret[0])
        {
        	$ret = $ret[0];
        }
        $this->addItem($ret);
        $this->output();
    }
}

$out = new tv_play();
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