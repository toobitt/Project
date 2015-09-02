<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('global.php');
define('MOD_UNIQUEID', 'vod');
class  vod_mark_live extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
	    $offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):15;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
	
		$sql = "SELECT id,name FROM ".DB_PREFIX."channel ";
		$q = $this->db->query($sql);
		while ($w = $this->db->fetch_array($q))
		{
			$channel[intval($w['id'])] = $w['name'];
		}
		
		//查询出顶级类别供下面没有分类的时候用
		$sql = "SELECT * FROM ".DB_PREFIX."vod_media_node WHERE fid = 0";
		$q = $this->db->query($sql);
		$top_sorts = array();
		while($r = $this->db->fetch_array($q))
		{
			$top_sorts[$r['id']] = $r;
		}
	
		$sql = "SELECT v.*, vs.name AS sort_name,vs.color AS vod_sort_color ,ch.name as channel_name,vn.name as new_mark_name,vn.collect_id as auto_collect_id FROM ".DB_PREFIX."vodinfo v LEFT JOIN ".DB_PREFIX."vod_media_node vs ON v.vod_sort_id = vs.id  LEFT JOIN ".DB_PREFIX."channel as ch ON v.source = ch.id  LEFT JOIN ".DB_PREFIX."vod_newest_mark as vn ON vn.original_id = v.id  WHERE 1 ". $condition . "  ORDER BY v.video_order_id DESC, v.id DESC ".$limit;
		$q  = $this->db->query($sql);
		$this->setXmlNode('vod','item');
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
			
			$r['vod_leixing'] = $top_sorts[$r['vod_leixing']]['name'];
			
			$collects = unserialize($r['collects']);
			if($collects)
			{
				$r['collects'] = $collects;
			}
			else 
			{
				$r['collects'] = '';
			}
			
			$img_arr = unserialize($r['img_info']);
			$r['img'] = $img_arr['host'].$img_arr['dir'].'80x60/'.$img_arr['filepath'].$img_arr['filename'];
						
			$rgb = $r['bitrate']/100;
			
			if($rgb < 10)
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][$rgb];
			}
			else 
			{
				$r['bitrate_color'] = $this->settings['bitrate_color'][9];
			}
	
			$r['st_end'] = date('h:i:s',$r['starttime'] + $r['duration']);
			
			if($r['starttime'])
			{
				$r['st_start']  = date('y/m h:i:s',$r['starttime']);
				$r['starttime'] = date('Y-m-d',$r['starttime']);
			}
			else
			{
				$r['st_start']  = '';
				$r['starttime'] = '';
			}
			
			if(!$r['new_mark_name'])
			{
				$r['new_mark_name'] = '暂无标注';
			}

			$r['auto_collect_id'] = $r['auto_collect_id']?$r['auto_collect_id']:0;//自动创建的集合
			$r['start'] = $r['start'] + 1;
			$r['etime'] = intval($r['duration']) + intval($r['start']);
			$r['source'] = $channel[intval($r['source'])];
			$r['duration'] = time_format($r['duration']);
			$r['status_display'] = intval($r['status']);
			$r['status'] = $this->settings['video_upload_status'][$r['status']];
			$r['is_allow'] = $r['is_allow']?'不允许':'允许';
			$r['is_finish'] = $r['is_finish']?'完成':'未完成';
			$r['mark_count'] = $r['mark_count'].'条';
			$r['create_time'] = date('Y-m-d h:i',$r['create_time']);
			$r['update_time'] = date('Y-m-d h:i',$r['update_time']);
			$r['collect_mid'] = 33;
			$this->addItem($r);
		}
		$this->output();
	}
	
	public function count()
	{
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'vodinfo v WHERE 1 '.$this->get_condition().'  AND v.vod_leixing in (3,4) ';
		$vodinfo_total = $this->db->query_first($sql);
		echo json_encode($vodinfo_total);		
	}
	
	public function get_condition()
	{
		$condition = "";
		if($this->input['id'])
		{
			$condition .= " AND v.id = '".urldecode($this->input['id'])."'";
		}
		
		if($this->input['comment'])
		{
			$condition .= ' AND v.comment LIKE "%'.urldecode($this->input['comment']).'%"';
		}
		
		if($this->input['author'])
		{
			$condition .= ' AND v.author LIKE "%'.urldecode($this->input['author']).'%"';
		}
		
		if($this->input['title'])
		{
			$condition .= ' AND v.title LIKE "%'.urldecode($this->input['title']).'%"';
		}
		
		if(intval($this->input['is_finish']) == 1)
		{
			$condition .= " AND v.is_finish = 1";
		}
		else if(intval($this->input['is_finish']) != -1)
		{
			$condition .= " AND v.is_finish = 0";
		}
		
		if($this->input['_id'])
		{
			$condition .= " AND v.vod_sort_id = '".intval($this->input['_id'])."'";
		}
		
		if($this->input['_type'])
		{
			$condition .= " AND v.vod_leixing = " . $this->input['_type'];
		}
		else 
		{
			$condition .= " AND v.vod_leixing = 3";
		}
	   
		if($this->input['k'] || trim(urldecode($this->input['k']))== '0')
		{
			$condition .= ' AND  v.title  LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND v.create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND v.create_time <= '".$end_time."'";
		}
				
		if($this->input['trans_status'] && urldecode($this->input['trans_status'])!= -1)
		{
			$condition .= " AND v.status = '".urldecode($this->input['trans_status'])."'";
		}
		else if(urldecode($this->input['trans_status']) == '0')//此处为了区分状态0的情况与传过来的值为空的情况，为空的时候查出所有
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
					$condition .= " AND  v.starttime > '".$yesterday."' AND v.starttime < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  v.starttime > '".$today."' AND v.starttime < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  v.starttime > '".$last_threeday."' AND v.starttime < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  v.starttime > '".$last_sevenday."' AND v.starttime < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		else 
		{
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
			$condition .= " AND  v.starttime > '".$last_threeday."' AND v.starttime < '".$tomorrow."'";
		}
		$condition .= " AND v.is_allow = 0 ";//列出允许标注的列表
		return $condition;
	}
	
	public function rebuild_data()
	{
		$sql = " SELECT vf.id as video_id,vc.id as collect_id FROM ".DB_PREFIX."vod_collect as vc LEFT JOIN ".DB_PREFIX."vodinfo as vf ON vf.id = vc.vodinfo_id WHERE vc.vodinfo_id != 0";
		$q = $this->db->query($sql);
		$return = array();
		while ($r = $this->db->fetch_array($q))
		{
			$return[] = $r;
		}
		
		foreach($return as $v)
		{
			$sql = " UPDATE ".DB_PREFIX."vod_newest_mark SET collect_id = '".$v['collect_id']."' WHERE original_id = '".$v['video_id']."'";
			$this->db->query($sql);
			
			$sql = " UPDATE ".DB_PREFIX."vod_collect SET is_auto = 1 WHERE id = '".$v['collect_id']."'";
			$this->db->query($sql);
		}
		
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
		
}

$out = new vod_mark_live();
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