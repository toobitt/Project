<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'livmedia');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_getvideo_info extends adminBase
{
	private $curl;
    public function __construct()
	{
		parent::__construct();
		$this->curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function getinfo()
	{
		$info = array();//用于输出视频信息时的存储
		$transids = urldecode($this->input['transids']);//正在转码的id
		//如果$this->input['since_id']存在(说明在首页), 才取数据
		if($this->input['since_id'])
		{
		    if($this->input['pub_column_id']) {
		        $sql = "SELECT * FROM ".DB_PREFIX."vodinfo v
		                LEFT JOIN ".DB_PREFIX."pub_column pc
		                   ON v.id = pc.aid
		                WHERE v.id > ".intval($this->input['since_id']);
                $sql .= $this->get_condition();
                $sql .= " GROUP BY v.id";        
            }
            else {
			     $sql = "SELECT * FROM ".DB_PREFIX."vodinfo v WHERE v.id > ".intval($this->input['since_id']);
                 $sql .= $this->get_condition(); 
            }
            $sql .= "  ORDER BY v.id ASC ";  
            
			$q = $this->db->query($sql);
			while ($r = $this->db->fetch_array($q))
			{
				$info['add_data'][] = $r;//取出需要在首页添加一行的数据
			}
		}
		
		//查询出正在转码的视频
		$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE status !=4 AND id in (".$transids.")";
		$q  = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			if($row['transcode_server'])
			{
				$row['transcode_server'] = unserialize($row['transcode_server']);
			}
			$data[] = $row;
		}

		$return_info = array();//用于存放视频转码进度信息信息的数组
		//根据视频id去请求转码进度
		if($data && !empty($data))
		{
			  $curl = $this->curl;//new curl('10.0.1.58','mediaserver4/api/mediaserver/admin/');
			  foreach ($data as $v)
			  { 
				 $curl->setSubmitType('get');
				 $curl->initPostData();
				 $curl->addRequestData('id',$v['id']);
				 $curl->addRequestData('a' ,'get_transcode_status');
				 $curl->addRequestData('host',$v['transcode_server']['host']);
				 $curl->addRequestData('port',$v['transcode_server']['port']);
				 $ret = $curl->request('video_transcode.php');
				 if($ret['return'] && $ret['return'] == 'fail')
				 {
				 	 $info['status_data'][] = array('transcode_percent' => 100,'id' => $ret['id'],'status' => $v['status']);
				 }
				 else 
				 {
				 	 $ret['status'] = $v['status'];
				 	 $info['status_data'][] = $ret;
				 }
			  }
			  $this->addItem($info);
		}
		else   
		{
			//如果都已经转码成功，就输出空数组
			$this->addItem($info);
		}
		$this->output();
	}
	
	public function get_condition()
	{
		$condition = "";
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
			if($authnode)
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
					if(!$this->input['vod_sort_id'])
					{
						$condition .= " AND v.vod_sort_id IN (".implode(',', $auth_nodes).")";
					}
					else if(in_array($this->input['vod_sort_id'],$auth_nodes))
					{
						if(isset($authnode_array[$this->input['vod_sort_id']]) && $authnode_array[$this->input['vod_sort_id']])
						{
							$condition .= " AND v.vod_sort_id IN (".implode(',', $authnode_array[$this->input['vod_sort_id']]).")";
						}
						else 
						{
							$sql = "SELECT id,childs FROM ".DB_PREFIX."vod_media_node WHERE id = '" .$this->input['vod_sort_id']. "'";
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
		}
		else 
		{
			if($this->input['vod_sort_id'])
			{
				$sql = " SELECT childs, fid FROM ".DB_PREFIX."vod_media_node WHERE  id = '".$this->input['vod_sort_id']."'";
				$arr = $this->db->query_first($sql);
				if($arr)
				{
					$condition .= " AND v.vod_sort_id IN (".$arr['childs'].")";
				}
			}
		}
		####增加权限控制 用于显示####
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  v.title  LIKE "%'.trim(($this->input['k'])).'%"';
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
		
		if($this->input['vod_leixing'])
		{
			$sql .= "  AND v.vod_leixing = ".intval($this->input['vod_leixing']); 
		}
		
		if($this->input['user_name'])
		{
			$condition .= " AND v.addperson = '" . $this->input['user_name'] . "' ";
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
        
        //根据发布库栏目搜索
        if($this->input['pub_column_id']) {
            include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
            $publishconfig = new publishconfig();
            $pub_column_id = $publishconfig->get_column_by_ids('id, childs', $this->input['pub_column_id']);
            foreach ((array)$pub_column_id as $k => $v) {
                $column_id[]= $v['childs'];
            }
            $column_id = implode(",", $column_id);
            if ($column_id) {
                $condition .= " AND pc.column_id IN(" . $column_id . ")";
            }
        }        
		
		return $condition;
	}
	

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
		
}

$out = new vod_getvideo_info();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>