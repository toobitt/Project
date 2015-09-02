<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'livmedia');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH.'lib/class/statistic.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
class  vod_add_video_mark extends adminBase
{
	private $local_curl;
    public function __construct()
	{
		parent::__construct();
		$this->local_curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$this->publish_column = new publishconfig();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	protected function index_search($data, $type = 'add')
	{
		$conf = realpath(CUR_CONF_PATH . 'conf/search_vod.ini');
		if ($conf)
		{
			include_once (ROOT_PATH . 'lib/xunsearch/XS.php');
			$xs = new XS($conf); // 建立 XS 对象，项目名称为：demo
			$index = $xs->index; // 获取 索引对象
			if ($type == 'del')
			{
				$index->$type($data);
				return;
			}
			if ($data)
			{
				if (!is_array($data))
				{
					$sql = "SELECT *  FROM " . DB_PREFIX . 'vodinfo WHERE id=' . $data;
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
				$doc = new XSDocument;
				$doc->setFields($data);
				 
				// 添加到索引数据库中
				$index->$type($doc);
			}
		}
	}

    public function  add_video_mark()
    {
    	if(!isset($this->input['add_edit']))
    	{
    		$this->errorOutput('非法操作');
    	}
    	
    	if(intval($this->input['add_edit']) == 1)//编辑标注
    	{
    		$this->edit_mark();
    	}
    	else if(intval($this->input['add_edit']) == -1)//快速编辑
    	{
    		$this->fast_edit();
    	}
    	else 
    	{
    		$this->add_mark();//新增标注
    	}
    }
    
//新增标注
    public function  add_live_mark()
    {
	    	$sql = "SELECT *  FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
	    	$return = $this->db->query_first($sql);
	    	$mark_count = $return['mark_count'];//原视频里被标注数量
	    	//如果不存在分类就默认其分类与类型相同
		if(!$this->input['vod_sort_id'] || intval($this->input['vod_sort_id']) == -1)
		{
			$this->input['vod_sort_id'] = 4;
		}
		
		$live_id = (int)$this->input['live_id'];
		//固定的参数
		$new_video = array(
	    	'isfile' 		=> 0,
	    	'source' 		=> intval($this->input['source']),
	    	'title' 		=> $this->input['title'],
	    	'subtitle' 		=> $this->input['subtitle'],
	    	'comment'		=> $this->input['comment'],
	    	'author' 		=> $this->input['author'],
	    	'keywords' 		=> $this->input['keywords'], 
	    	'vod_sort_id' 	=> $this->input['vod_sort_id'], 
			'channel_id'    => $live_id,
	    	'vod_leixing' 	=> 4,//标注归档
	    	'create_time' 	=> TIMENOW,
	    	'update_time' 	=> TIMENOW,
	    	'ip' 			=> hg_getip(),
			'marktype'      => 1,
 	    	'addperson' 	=> $this->user['user_name'],
	    	'user_id' 		=> intval($this->user['user_id']),
		  	'org_id' 		=> $this->user['org_id'],
	    	'from_appid' 	=> intval($this->user['appid']),
	    	'from_appname' 	=> $this->user['display_name'],
			'column_id' 	=> $this->input['column_id'],
		);
		if($new_video['column_id'])
		{
			$new_video['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$new_video['column_id']);
			$new_video['column_id'] = addslashes(serialize($new_video['column_id']));
		}
		
   	    if($this->input['img_info'])
    	{
    		$image_info = str_replace('&quot;','"',$this->input['img_info']);
			$new_video['img_info'] = $image_info;
    	}
    	
    	$sql  = "INSERT INTO ".DB_PREFIX."vodinfo SET ";
    	foreach($new_video as $k => $v)
    	{
    		$sql .=  $k ." = '". $v . "',";
    	}
    	$sql = substr($sql,0,strlen($sql)-1);
    	$this->db->query($sql);
    	$id = $this->db->insert_id();
    	if(!$this->input['img_info'])
    	{
    		$this->change_mark_pic($id);//请求图片接口
    	}
    	/********************************************************视频片段数据的处理******************************************************************************/
    	//将视频片段的数据插入到表中
    	$start_time_arr    = $this->input['start_time'];
    	$duration_arr      = $this->input['duration'];
    	$original_id_arr   = $this->input['original_id'];
    	$order_id_arr      = $this->input['order_id'];
    	$name_arr          = $this->input['name'];
    	$vcr_num = count($start_time_arr);//记录视频片段的数目
    	
    	foreach($start_time_arr as $k => $v)
    	{
    		$sql  = "";
    		$sql  =  " INSERT INTO ".DB_PREFIX."vod_mark_video SET ";
    		$sql .=  " name = '".($name_arr[$k])."',".
    				 " start_time = '".($start_time_arr[$k])."',".
    				 " duration = '".($duration_arr[$k])."',".
    				 " marktype = '1',".
    				 " original_id = '".$live_id."',".
    				 " order_id = '".($order_id_arr[$k])."',".
    				 " vodinfo_id = '".$id."'";
    		$this->db->query($sql);
    	}
   
    	/************************************************此时对这个标注的一些额外操作**********************************/
    	//单片段
    	$mark_info = array();//用于返回数据
    	if($vcr_num == 1)
    	{
    		$start = intval($start_time_arr[0]);//标注的开始时间
	    	$duration = intval($duration_arr[0]);//标注的时长
	    	$mark_endTime = $start + $duration;//标注的结束时间
	    	$original_id = ($original_id_arr[0]);//记录从哪个视频标过来的
	    	
	    	/**********标注一段视频之后要将标注的视频记录到最新标注的表中*********/
	    	$sql_n = " SELECT * FROM ".DB_PREFIX."vod_newest_mark WHERE marktype = 1 AND original_id = '".$live_id."'";
	    	$arr_n = $this->db->query_first($sql_n);
	    	if($arr_n)
	    	{
	    		$sql_n  = " UPDATE ".DB_PREFIX."vod_newest_mark SET ";
	    		$sql_n .= " name     = '".($this->input['title'])."',".
	    		 		  " duration = '".$duration."' WHERE id = '".$arr_n['id']."'";
	    	}
	    	else
	    	{
	    		$sql_n  = " INSERT INTO ".DB_PREFIX."vod_newest_mark SET ";
	    		$sql_n .= " name        = '".($this->input['title'])."',".
	    		 		  " duration    = '".$duration."',".
	    				  " marktype = '1',".
	    		 		  " collect_id  = '".$return['mark_collect_id']."',".
	    		 		  " original_id = '".$live_id."'";
	    	}
	    	
	    	$this->db->query($sql_n);
	    	/********************************************************************/
	    	$sql = " UPDATE ".DB_PREFIX."vodinfo SET  video_order_id = '".$id."', start = 0 ,status = 0,original_id = '".$live_id."',mark_etime = '".$mark_endTime."',video_count = '".$vcr_num."'   WHERE id = '".$id."'";
	    	$this->db->query($sql);

			$sql = "UPDATE ".DB_PREFIX."vodinfo SET mark_count = mark_count + 1  WHERE id = '".$original_id."'";
	    	$this->db->query($sql);
	    	$endTime = $mark_endTime + $duration;//重置页面的标注把柄的结束位置
	    	
	    	$sql = "SELECT duration FROM ".DB_PREFIX."vodinfo WHERE id = '".$original_id."'";
	    	$arr = $this->db->query_first($sql);
	    	
	    	if($endTime > intval($arr['duration']))
	    	{
	    		$endTime = intval($arr['duration']);
	    	}
	    
    		if($mark_endTime == intval($arr['duration']))//标注完了
	    	{
	    		$sql_v  = " UPDATE ".DB_PREFIX."vodinfo SET ";
	    		$sql_v .= " is_finish = 1 WHERE id = '".$original_id."'";
	    		$this->db->query($sql_v);
	    	}
	    	
	    	//提交视频id生成物理文件
    		$this->request_create_physics($id, $original_id_arr, $start_time_arr, $duration_arr);
	    	$markCount = $mark_count + 1;
	    	$video_mark = $return['hostwork'].'/'. $return['video_path'] . MAINFEST_F4M;
	    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$id."'";
    		$mark_info = $this->db->query_first($sql);
    		$mark_info['img_info'] = hg_fetchimgurl(unserialize($mark_info['img_info']), 80,60);
    		$mark_info['duration_format'] = time_format($duration);;
    		$mark_info['mark_start'] = $mark_endTime;
    		$mark_info['mark_end']   = $endTime;
    		$mark_info['markCount']  = $markCount;
    		$mark_info['mark_vodid'] = $original_id;
    		$mark_info['video_mark'] = $video_mark;
    		$mark_info['mark_duration'] = $duration;
    		$mark_info['mark_aspect'] = $return['aspect'];
    		if($this->input['img_info'])
    		{
    			$mark_info['mark_img'] = $img_path;
    		}
    	}
    	else 
    	{
	    	$duration = 0;
	    	$status = ',status = 0 ';
	    	for($i = 0;$i<count($duration_arr);$i++)
	    	{
	    		$duration += intval($duration_arr[$i]);
	    	}
    		$sql = " UPDATE ".DB_PREFIX."vodinfo SET  video_order_id = '".$id."', start = 0,duration = '".$duration."',original_id = 0,mark_etime = '".$duration."',video_count = '".$vcr_num."' {$status}  WHERE id = '".$id."'";
	    	$this->db->query($sql);
	    	//生成物理文件
	    	$this->request_create_physics($id,$original_id_arr, $start_time_arr, $duration_arr);
	    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$id."'";
    		$mark_info = $this->db->query_first($sql);
	    	$mark_info['more_vcr'] = 1;
    	}

		$this->index_search($mark_info);
    	//插入工作量统计
		$statistic = new statistic();
		$statistics_data = array(
			'content_id' => $id,
			'contentfather_id' => '',
			'type' => 'insert',
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'before_data' => '',
			'last_data' => $return['title'],
			'num' => 1,
		);
		$statistic->insert_record($statistics_data);
    	$this->addItem($mark_info);
    	$this->output();
    }
    
    //新增标注
    public function  add_mark()
    {
	    	$sql = "SELECT *  FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
	    	$return = $this->db->query_first($sql);
	    	$mark_count = $return['mark_count'];//原视频里被标注数量
	    	//如果不存在分类就默认其分类与类型相同
		if(!$this->input['vod_sort_id'] || intval($this->input['vod_sort_id']) == -1)
		{
			$this->input['vod_sort_id'] = 4;
		}
		//固定的参数
		$new_video = array(
	    	'isfile' 		=> 0,
	    	'source' 		=> intval($this->input['source']),
	    	'title' 		=> $this->input['title'],
	    	'subtitle' 		=> $this->input['subtitle'],
	    	'comment'		=> $this->input['comment'],
	    	'author' 		=> $this->input['author'],
	    	'keywords' 		=> $this->input['keywords'], 
	    	'vod_sort_id' 	=> $this->input['vod_sort_id'], 
	    	'vod_leixing' 	=> 4,//标注归档
	    	'create_time' 	=> TIMENOW,
	    	'update_time' 	=> TIMENOW,
	    	'ip' 			=> hg_getip(),
	    	'addperson' 	=> $this->user['user_name'],
	    	'user_id' 		=> intval($this->user['user_id']),
		  	'org_id' 		=> $this->user['org_id'],
	    	'from_appid' 	=> intval($this->user['appid']),
	    	'from_appname' 	=> $this->user['display_name'],
			'column_id' 	=> $this->input['column_id'],
		);
		if($new_video['column_id'])
		{
			$new_video['column_id'] = $this->publish_column->get_columnname_by_ids('id,name',$new_video['column_id']);
			$new_video['column_id'] = addslashes(serialize($new_video['column_id']));
		}
		
   	    if($this->input['img_info'])
    	{
    		$image_info = str_replace('&quot;','"',$this->input['img_info']);
			$new_video['img_info'] = $image_info;
    	}
    	
    	$sql  = "INSERT INTO ".DB_PREFIX."vodinfo SET ";
    	foreach($new_video as $k => $v)
    	{
    		$sql .=  $k ." = '". $v . "',";
    	}
    	$sql = substr($sql,0,strlen($sql)-1);
    	$this->db->query($sql);
    	$id = $this->db->insert_id();
    	if(!$this->input['img_info'])
    	{
    		$this->change_mark_pic($id);//请求图片接口
    	}
    	/********************************************************视频片段数据的处理******************************************************************************/
    	//将视频片段的数据插入到表中
    	$start_time_arr    = $this->input['start_time'];
    	$duration_arr      = $this->input['duration'];
    	$original_id_arr   = $this->input['original_id'];
    	$order_id_arr      = $this->input['order_id'];
    	$name_arr          = $this->input['name'];
    	$vcr_num = count($start_time_arr);//记录视频片段的数目
    	
    	foreach($start_time_arr as $k => $v)
    	{
    		$sql  = "";
    		$sql  =  " INSERT INTO ".DB_PREFIX."vod_mark_video SET ";
    		$sql .=  " name = '".($name_arr[$k])."',".
    				 " start_time = '".($start_time_arr[$k])."',".
    				 " duration = '".($duration_arr[$k])."',".
    				 " original_id = '".($original_id_arr[$k])."',".
    				 " order_id = '".($order_id_arr[$k])."',".
    				 " vodinfo_id = '".$id."'";
    		$this->db->query($sql);
    	}
   
    	/************************************************此时对这个标注的一些额外操作**********************************/
    	//单片段
    	$mark_info = array();//用于返回数据
    	if($vcr_num == 1)
    	{
    		$start = intval($start_time_arr[0]);//标注的开始时间
	    	$duration = intval($duration_arr[0]);//标注的时长
	    	$mark_endTime = $start + $duration;//标注的结束时间
	    	$original_id = ($original_id_arr[0]);//记录从哪个视频标过来的
	    	
	    	/**********标注一段视频之后要将标注的视频记录到最新标注的表中*********/
	    	$sql_n = " SELECT * FROM ".DB_PREFIX."vod_newest_mark WHERE original_id = '".$original_id."'";
	    	$arr_n = $this->db->query_first($sql_n);
	    	if($arr_n)
	    	{
	    		$sql_n  = " UPDATE ".DB_PREFIX."vod_newest_mark SET ";
	    		$sql_n .= " name     = '".($this->input['title'])."',".
	    		 		  " duration = '".$duration."' WHERE id = '".$arr_n['id']."'";
	    	}
	    	else
	    	{
	    		$sql_n  = " INSERT INTO ".DB_PREFIX."vod_newest_mark SET ";
	    		$sql_n .= " name        = '".($this->input['title'])."',".
	    		 		  " duration    = '".$duration."',".
	    		 		  " collect_id  = '".$return['mark_collect_id']."',".
	    		 		  " original_id = '".$original_id."'";
	    	}
	    	
	    	$this->db->query($sql_n);
	    	/********************************************************************/
	    	$sql = " UPDATE ".DB_PREFIX."vodinfo SET  video_order_id = '".$id."', start = 0 ,status = 0,original_id = '".$original_id."',mark_etime = '".$mark_endTime."',video_count = '".$vcr_num."'   WHERE id = '".$id."'";
	    	$this->db->query($sql);

			$sql = "UPDATE ".DB_PREFIX."vodinfo SET mark_count = mark_count + 1  WHERE id = '".$original_id."'";
	    	$this->db->query($sql);
	    	$endTime = $mark_endTime + $duration;//重置页面的标注把柄的结束位置
	    	
	    	$sql = "SELECT duration FROM ".DB_PREFIX."vodinfo WHERE id = '".$original_id."'";
	    	$arr = $this->db->query_first($sql);
	    	
	    	if($endTime > intval($arr['duration']))
	    	{
	    		$endTime = intval($arr['duration']);
	    	}
	    
    		if($mark_endTime == intval($arr['duration']))//标注完了
	    	{
	    		$sql_v  = " UPDATE ".DB_PREFIX."vodinfo SET ";
	    		$sql_v .= " is_finish = 1 WHERE id = '".$original_id."'";
	    		$this->db->query($sql_v);
	    	}
	    	
	    	//提交视频id生成物理文件
    		$this->request_create_physics($id, $original_id_arr, $start_time_arr, $duration_arr);
	    	$markCount = $mark_count + 1;
	    	$video_mark = $return['hostwork'].'/'. $return['video_path'] . MAINFEST_F4M;
	    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$id."'";
    		$mark_info = $this->db->query_first($sql);
    		$mark_info['img_info'] = hg_fetchimgurl(unserialize($mark_info['img_info']), 80,60);
    		$mark_info['duration_format'] = time_format($duration);;
    		$mark_info['mark_start'] = $mark_endTime;
    		$mark_info['mark_end']   = $endTime;
    		$mark_info['markCount']  = $markCount;
    		$mark_info['mark_vodid'] = $original_id;
    		$mark_info['video_mark'] = $video_mark;
    		$mark_info['mark_duration'] = $duration;
    		$mark_info['mark_aspect'] = $return['aspect'];
    		if($this->input['img_info'])
    		{
    			$mark_info['mark_img'] = $img_path;
    		}
    	}
    	else 
    	{
	    	$duration = 0;
	    	$status = ',status = 0 ';
	    	for($i = 0;$i<count($duration_arr);$i++)
	    	{
	    		$duration += intval($duration_arr[$i]);
	    	}
    		$sql = " UPDATE ".DB_PREFIX."vodinfo SET  video_order_id = '".$id."', start = 0,duration = '".$duration."',original_id = 0,mark_etime = '".$duration."',video_count = '".$vcr_num."' {$status}  WHERE id = '".$id."'";
	    	$this->db->query($sql);
	    	//生成物理文件
	    	$this->request_create_physics($id,$original_id_arr, $start_time_arr, $duration_arr);
	    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".$id."'";
    		$mark_info = $this->db->query_first($sql);
	    	$mark_info['more_vcr'] = 1;
    	}

		$this->index_search($mark_info);
    	//插入工作量统计
		$statistic = new statistic();
		$statistics_data = array(
			'content_id' => $id,
			'contentfather_id' => '',
			'type' => 'insert',
			'user_id' => $this->user['user_id'],
			'user_name' => $this->user['user_name'],
			'before_data' => '',
			'last_data' => $return['title'],
			'num' => 1,
		);
		$statistic->insert_record($statistics_data);
    	$this->addItem($mark_info);
    	$this->output();
    }
	
	private function request_create_physics($id,$original_id,$start, $duration,$type='transcode_mark',$status = 0)
	{
		$source_dir = array();
		foreach($original_id AS $k => $v)
		{
			$sql = "SELECT video_base_path,video_path,video_filename,is_water_marked,is_forcecode FROM ". DB_PREFIX ."vodinfo WHERE id = {$v}";
			$arr = $this->db->query_first($sql);
			$source_dir[] = $arr['video_path'] . $arr['video_filename'];
			$is_water_marked[] = $arr['is_water_marked'];
			$is_forcecode[] = $arr['is_forcecode'];
			$video_base_path[] = $arr['video_base_path'];
		}
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('type',$type);
		$curl->addRequestData('audit_auto',$status);
		foreach($original_id AS $k => $v)
		{
			$curl->addRequestData('start[' . $k . ']',intval($start[$k])/1000);
			$curl->addRequestData('duration[' . $k . ']',intval($duration[$k])/1000);
			$curl->addRequestData('source_dir[' . $k . ']',$source_dir[$k]);
			$curl->addRequestData('is_water_marked[' . $k . ']',$is_water_marked[$k]);
			$curl->addRequestData('is_forcecode[' . $k . ']',$is_forcecode[$k]);
			$curl->addRequestData('video_base_path[' . $k . ']',$video_base_path[$k]);
		}
		$curl->request('videomark.php');
	}
    
	//编辑标注
    public function edit_mark()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	$start_time_arr    = $this->input['start_time'];
    	$duration_arr      = $this->input['duration'];
    	$original_id_arr   = $this->input['original_id'];
    	$order_id_arr      = $this->input['order_id'];
    	$name_arr          = $this->input['name'];
    	$vcr_num = count($start_time_arr);//记录视频片段的数目
    	
    	//清除掉所有的片段
    	$sql = "DELETE FROM ".DB_PREFIX."vod_mark_video  WHERE vodinfo_id = '".intval($this->input['id'])."'";
    	$this->db->query($sql);
    	
    	//再添加视频片段
    	foreach($start_time_arr as $k => $v)
    	{
    		$sql  = "";
    		$sql  =  " INSERT INTO ".DB_PREFIX."vod_mark_video SET ";
    		$sql .=  " name = '".($name_arr[$k])."',".
    				 " start_time = '".($start_time_arr[$k])."',".
    				 " duration = '".($duration_arr[$k])."',".
    				 " original_id = '".($original_id_arr[$k])."',".
    				 " order_id = '".($order_id_arr[$k])."',".
    				 " vodinfo_id = '".intval($this->input['id'])."'";
    		$this->db->query($sql);
    	}
    	
    	//如果此时已经是单视频
    	if($vcr_num == 1)
    	{
    		$start = ($start_time_arr[0]);
    		$duration = ($duration_arr[0]);
    		$mark_etime = intval($start) + intval($duration);
    		$original_id = ($original_id_arr[0]);
    		$sql = "UPDATE ".DB_PREFIX."vodinfo SET mark_count = mark_count + 1  WHERE id = '".$original_id."'";
	    	$this->db->query($sql);
    	}
    	else 
    	{
    		$start = 0;
    		$duration = 0;
	    	for($i = 0;$i<count($duration_arr);$i++)
	    	{
	    		$duration += intval($duration_arr[$i]);
	    	}
    		$mark_etime = $duration;
    		$original_id = 0;
    	}
    	
    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
    	$arr = $this->db->query_first($sql);
    	
    	 /*如果原来的original_id存在，说明该标注原来是单段标注,就要更新*/
    	 if($arr['original_id'])
    	 {
    	 	$sql = "UPDATE ".DB_PREFIX."vodinfo SET mark_count = mark_count - 1  WHERE id = '".$arr['original_id']."'";
	    	$this->db->query($sql);
    	 }	
    	 
 		//重标注之前视频的状态
 		$ori_status = $arr['status'];
 		
 		//查询视频重标注之前已经发布到的栏目
		$arr['column_id'] = unserialize($arr['column_id']);
		$ori_column_id = array();
		if(is_array($arr['column_id']))
		{
			$ori_column_id = array_keys($arr['column_id']);
		}
		
        $isfile = 0;
        $status = 0;
		if(!$this->input['img_info'] && ($this->input['img_src'] || $this->input['img_src_cpu'] || $this->input['source_img_pic']))
		{
			$this->change_mark_pic(intval($this->input['id']));
		}
		else if($this->input['img_info'])
		{
    		$image_info = str_replace('&quot;','"',$this->input['img_info']);
		}

       	$column_id = $this->publish_column->get_columnname_by_ids('id,name',($this->input['column_id']));
		$column_id = serialize($column_id);
		
    	//如果不存在分类就默认其分类与类型相同
		if(!$this->input['vod_sort_id'] || intval($this->input['vod_sort_id']) == -1)
		{
			$this->input['vod_sort_id'] = 4;
		}
		
    	$sql  =  " UPDATE ".DB_PREFIX."vodinfo SET ";
    	$sql .=  " title    = '".($this->input['title'])."',".
    	 		 " subtitle = '".($this->input['subtitle'])."',".
    	 		 " keywords = '".($this->input['keywords'])."',".
    	 		 " author = '".($this->input['author'])."',".
    	 		 " comment = '".($this->input['comment'])."',".
    	 		 " vod_sort_id = '".($this->input['vod_sort_id'])."',".
    	 		 " source = '".intval($this->input['source'])."',".
    	 		 " start = '".$start."',".
    	 		 " duration = '".$duration."',".
    	 		 " original_id  = '".$original_id ."',".
    	 		 " video_count  = '".$vcr_num ."',".
    	 		 " isfile = '".$isfile."',".
		     	// " status = '".$status."',".
    			 " column_id = '" . addslashes($column_id)."',".
    	 		 " mark_etime = '".$mark_etime."' ";
    	if($image_info)
    	{
    		$sql .= ",img_info = '" .$image_info . "' ";
    	}
    	$sql .=  " WHERE id = '".intval($this->input['id'])."'";
    	$this->db->query($sql);
    	$this->request_create_physics($arr['id'], $original_id_arr , $start_time_arr, $duration_arr,'transcode_fast_edit',$arr['status']);
    	//插入工作量统计
    	$statistic = new statistic();
    	$statistics_data = array(
	    	'content_id' => intval($this->input['id']),
			'contentfather_id' => '',
			'type' => 'update',
			'user_id' => $arr['user_id'],/**$arr['user_id']*/
			'user_name' => $arr['addperson'],/**$arr['addperson']*/
			'before_data' => '',
			'last_data' => ($this->input['title']),
			'num' => 1,
    	);
    	
    	$statistic->insert_record($statistics_data);
    	
    	//把数据返回
    	$sql_r = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
    	$ret = $this->db->query_first($sql_r);
    	$ret['img_info'] = hg_fetchimgurl(unserialize($ret['img_info']), 80,60);
 		//重标注视频后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}   	
		if($ori_status == 2)
		{
			if(!empty($ret['expand_id']))
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($ret, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($ret, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($ret, 'update',$same_column);
				}
			}
			else 
			{
				if($new_column_id)
				{
					$op = "insert";
					publish_insert_query($ret,$op);
				}
			}
		}
		else 
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				publish_insert_query($ret,$op);
			}
		}
		$this->index_search($ret, 'update');
    	$this->addItem($ret);
    	$this->output();
    }
    
    //快速编辑
    public function fast_edit()
    { 
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    			
		$sql = "SELECT * FROM " .DB_PREFIX. "vodinfo WHERE id = '" .intval($this->input['id']). "'";
		$arr = $this->db->query_first($sql);
 		//快速编辑之前视频的状态
 		$ori_status = $arr['status'];
 		
 		//查询视频快速编辑之前已经发布到的栏目
		$arr['column_id'] = unserialize($arr['column_id']);
		$ori_column_id = array();
		if(is_array($arr['column_id']))
		{
			$ori_column_id = array_keys($arr['column_id']);
		}		
		
       	$column_id = $this->publish_column->get_columnname_by_ids('id,name',($this->input['column_id']));
		$column_id = serialize($column_id);
    	$sql  = " UPDATE ".DB_PREFIX."vodinfo SET ";
    	
    	$data = array(
    		'title' 		=> $this->input['title']?$this->input['title']:$arr['title'],
    		'subtitle' 		=> $this->input['subtitle']?$this->input['subtitle']:$arr['subtitle'],
    		'comment' 		=> $this->input['comment']?$this->input['comment']:$arr['comment'],
    		'keywords' 		=> $this->input['keywords']?$this->input['keywords']:$arr['keywords'],
    		'author' 		=> $this->input['author']?$this->input['author']:$arr['author'],
    		'source' 		=> $this->input['mark_source_id']?$this->input['mark_source_id']:$arr['source'],
    		'vod_sort_id' 	=> intval($this->input['mark_sort_id'])?intval($this->input['mark_sort_id']):$arr['vod_sort_id'],
    		'isfile' 		=> 0,
    		//'status' 		=> 0,
    		'column_id' 	=> addslashes($column_id),
    	);
   
    	foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql  = trim($sql,',');
		$sql .= " WHERE id = '".intval($this->input['id'])."'";
    	$this->db->query($sql);
    	
    	$this->change_mark_pic(intval($this->input['id']));
		$original_id_arr   = $this->input['original_id'];
    	$start_time_arr    = $this->input['start_time'];
    	$duration_arr      = $this->input['duration'];
    	$this->request_create_physics(intval($this->input['id']), $original_id_arr , $start_time_arr, $duration_arr,'transcode_fast_edit',$arr['status']);
    	
    	//返回数据
		$sql_r = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql_r); 
		
 		//快编视频后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}   	
		if($ori_status == 2)
		{
			if(!empty($ret['expand_id']))
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($ret, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($ret, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($ret, 'update',$same_column);
				}
			}
			else 
			{
				if($new_column_id)
				{
					$op = "insert";
					publish_insert_query($ret,$op);
				}
			}
		}
		else 
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				publish_insert_query($ret,$op);
			}
		}
		
		$this->index_search($ret, 'update');
		//插入工作量统计
		$statistic = new statistic();
		$statistics_data = array(
			'content_id' => intval($this->input['id']),
			'contentfather_id' => '',
			'type' => 'update',
			'user_id' => $ret['user_id'],/**$arr['user_id']*/
			'user_name' => $ret['addperson'],/**$arr['addperson']*/
			'before_data' => '',
			'last_data' => ($this->input['title']),
			'num' => 1,
		);
		$statistic->insert_record($statistics_data); 		
    	$this->addItem($ret);
    	$this->output();
    }

	//用于请求更新截图的接口
 	public function  change_mark_pic($id)
    {
    	 $this->local_curl->setSubmitType('get');
		 $this->local_curl->initPostData();
		 $this->local_curl->addRequestData('id',$id);
		 $this->local_curl->addRequestData('img_src',($this->input['img_src']));
		 $this->local_curl->addRequestData('img_src_cpu',($this->input['img_src_cpu']));
		 $this->local_curl->addRequestData('source_img_pic',($this->input['source_img_pic']));
		 $this->local_curl->addRequestData('module_id',($this->input['module_id']));
		 $this->local_curl->addRequestData('a','update_img');
		 $this->local_curl->request('vod_update_img.php');
    }
    
    //创建一个集合 
    public function create_collect($arr)
    {
    	$sql  = "INSERT INTO ".DB_PREFIX."vod_collect SET  ";
    	$sql .= "collect_name = '".$arr['title']."',".
    	        "vod_sort_id  = '".$arr['vod_sort_id']."',".
    	        "source       = '".$arr['source']."',".
    	        "admin_name   = '".($this->user['user_name'])."',".
    	        "admin_id     = '".($this->user['user_id'])."',".
    	        "count        = 0,".
    	        "is_auto      = 1,".
    	        "create_time  = '".TIMENOW."',".
    	        "update_time  = '".TIMENOW."'";
    	$this->db->query($sql);
    	$vid = $this->db->insert_id(); 
    	$sql = " UPDATE ".DB_PREFIX."vod_collect SET collect_order_id = '".$vid."' WHERE id = '".$vid."'";
    	$this->db->query($sql);
    	return $vid; 
    }
    
    //手动设置标注完成
    public function setfinish()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	$ids = explode(',',($this->input['id']));
    	$sql = "UPDATE ".DB_PREFIX."vodinfo SET is_finish = '".intval($this->input['is_finish'])."' WHERE id in (".($this->input['id']).")";
    	$this->db->query($sql);
    	$this->addItem($ids);
    	$this->output();
    }
    
    
    //添加到集合 
    public function  add2collect($id,$collect_id)
    {
    	 $this->local_curl->setSubmitType('get');
		 $this->local_curl->initPostData();
		 $this->local_curl->addRequestData('id',$id);
		 $this->local_curl->addRequestData('collect_id',$collect_id);
		 $this->local_curl->addRequestData('a','video2collect');
		 $this->local_curl->request('vod_video2collect.php');
    }
    
    //暂停转码任务
    public function control_transcode_task()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	if(!$this->input['type'])
    	{
    		$this->errorOutput('请指定操作的类型');
    	}
    	$op = ($this->input['type']);
    	$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',intval($this->input['id']));
		$curl->addRequestData('a',$op . '_transcode_task');
		$ret = $curl->request('video_transcode.php');
		$ret['op'] = $op;
		if($ret['return'] == 'success')
		{
			if($op == 'pause')
			{
				$sql = "UPDATE " . DB_PREFIX ."vodinfo SET status = 4 WHERE id = '".intval($this->input['id'])."'";
			}
			
			if($op == 'resume')
			{
				$sql = "UPDATE " . DB_PREFIX ."vodinfo SET status = 0 WHERE id = '".intval($this->input['id'])."'";
			}
			$this->db->query($sql);
		}
		$this->addItem($ret);
		$this->output();
    }
    //重新转码
    public function retranscode()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',intval($this->input['id']));
		$curl->addRequestData('a','retranscode');
		$ret = $curl->request('retranscode.php');
		$this->addItem($ret);
		$this->output();
    }
    
	//多码流
    public function multi_bitrate()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	$return = array();
    	$sql = "SELECT * FROM " . DB_PREFIX . "vod_config ";
    	$q = $this->db->query($sql);
    	while ($r = $this->db->fetch_array($q))
    	{
    		$return['config'][] = $r;
    	}
    	$sql = "SELECT * FROM " . DB_PREFIX . "video WHERE video_id = '" .intval($this->input['id']). "'";
    	$q = $this->db->query($sql);
    	while ($r = $this->db->fetch_array($q))
    	{
    		$return['ids'][] = $r['vodconfig_id'];
    	}
    	$return['id'] = intval($this->input['id']);
    	$this->addItem($return);
    	$this->output();
    }
    
    public function domulti_bitrate()
    {
    	if(!$this->input['id'])
    	{
    		$this->errorOutput(NOID);
    	}
    	
    	if(!$this->input['cid'])
    	{
    		$this->errorOutput(NOID);
    	}

    	$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',intval($this->input['id']));
		$curl->addRequestData('cid',intval($this->input['cid']));
		$curl->addRequestData('type','transcode_multi_bitrate');
		$ret = $curl->request('videomark.php');
		$this->addItem($ret);
		$this->output();
    }
    
    //批量重新转码
 	public function retrans_videos()
    {
	    $offset = $this->input['offset']?intval($this->input['offset']):0;
		$count = $this->input['count']?intval($this->input['count']):15;
		$limit = " limit {$offset}, {$count}";
    	if($this->input['id'])
    	{
    		$condition = " AND id IN (" .$this->input['id']. ")";
    	}
    	if($this->input['start'])
    	{
    		$condition .= " AND id>= (" .$this->input['start']. ")";
    	}
    	if($this->input['end'])
    	{
    		$condition .= " AND id<= (" .$this->input['end']. ")";
    	}
    	if($this->input['type'] == 'retrans_mark')
		{
    		$condition .= " AND vod_leixing=4";
		}
		else
		{
    		$condition .= " AND vod_leixing!=4";
		}
    	$sql = "SELECT * FROM ".DB_PREFIX."vodinfo WHERE status!=0 " . $condition . ' ORDER BY id DESC ' . $limit;
    	$q = $this->db->query($sql);
		$curl = new curl($this->settings['App_mediaserver']['host'], $this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$marks = array();
		while($v = $this->db->fetch_array($q)) 
		{
			if($v['vod_leixing'] != 4)
			{
				$curl->initPostData();
				$curl->addRequestData('id',$v['id']);
				$curl->addRequestData('audit_auto',$v['status']);
				$curl->addRequestData('a','retranscode');
				$curl->request('retranscode.php');
    			$this->addItem($v);
			}
			else 
			{
				$marks[$v['id']] = $v;
			}
		}
		
    	if($this->input['type'] != 'retrans_mark')
		{
			$this->output();
		}
		if (!$marks)
		{
			$this->errorOutput('没有数据了');
		}

		$sql = "SELECT * FROM " .DB_PREFIX. "vod_mark_video WHERE vodinfo_id IN(" . implode(',', array_keys($marks)). ") ORDER BY vodinfo_id ASC, order_id ASC ";
		$q = $this->db->query($sql);
		$vcr = array();
		while($v = $this->db->fetch_array($q)) 
		{
			$vcr[$v['vodinfo_id']][] = $v;
		}
		foreach($marks AS $k => $v)
		{
			foreach($vcr[$k] AS $kk => $vv)
			{
				$original_id[] = $vv['original_id'];
				$start[] = $vv['start_time'];
				$duration[] = $vv['duration'];
			}
			$this->request_create_physics($v['id'],$original_id,$start, $duration,'transcode_fast_edit',$v['status']);
			
			$sql = " UPDATE " .DB_PREFIX. "vodinfo SET status = 0 WHERE id = ".$v['id'];
			$this->db->query($sql);
    		$this->addItem($v);
		}
    	
    	$this->output();
    }
	
	//视频合成，生成新的视频
	public function video_merge()
	{
		if(!$this->input['vodinfo_id'])
		{
			$this->errorOutput(NOID);
		}
		$original_id = $this->input['vodinfo_id'];
		if($original_id[0])
		{
			$sql = "SELECT img_info FROM " .DB_PREFIX. "vodinfo WHERE id = '" .$original_id[0]. "'";
			$originalVideo = $this->db->query_first($sql);
			$img_info = $originalVideo['img_info'];
		}
		else 
		{
			$img_info = '';
		}
		
		if($this->input['column_id'])
		{
			$column_id = $this->publish_column->get_columnname_by_ids('id,name',$this->input['column_id']);
			$column_id = serialize($column_id);
		}

		$start 		 = $this->input['start_time'];
		$duration 	 = $this->input['duration'];
		//准备好数据，插入一条纪录
		$data = array(
			'title'    			=> $this->input['title'],
			'comment'    		=> $this->input['comment'],
			'column_id'    		=> addslashes($column_id),
			'vod_leixing'		=> $this->input['vod_leixing'],
			'vod_sort_id' 		=> $this->input['vod_sort_id'],
	   		'from_appid'		=> $this->user['appid'],
			'from_appname'		=> $this->user['display_name'],
			'user_id'			=> $this->user['user_id'],
		  	'org_id' 			=> $this->user['org_id'],
			'addperson'			=> $this->user['user_name'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
		);
		$sql = ' INSERT INTO ' .DB_PREFIX. 'vodinfo SET ';
		foreach($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."vodinfo SET video_order_id = '" .$id. "',img_info = '" .$img_info. "'  WHERE id = '" .$id. "'";
		$this->db->query($sql);
		//将数据转接到转码服务器
		$this->request_create_physics($id,$original_id,$start,$duration);
		$this->addItem('success');
		$this->output();
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}	
}

$out = new vod_add_video_mark();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'add_video_mark';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>