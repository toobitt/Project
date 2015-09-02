<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'vod');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
class  vod_fast_edit_video_opration extends adminBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function auto_save()
	{
		//根据传过来hash_id判断执行哪种操作
		$sql = " SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp WHERE hash_id = '" .$this->input['hash_id']. "'";
		$vcr = $this->db->query_first($sql);
		$type = $vcr['id']?'update':'create';
		$func = 'auto_save_' . $type;
		$this->$func($this->input);
	}
	
	//自动保存创建一个片段
	private function auto_save_create($vcr)
	{
		//先插入fast_vcr_tmp表
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'main_video_id'	=> $vcr['main_video_id'],//主页面视频id（从哪个视频点进来的）
			'vcr_type'		=> $vcr['vcr_type'],
			'vodinfo_id'	=> $vcr['vodinfo_id'],//视频id（该片段来自于哪个视频）
			'input_point' 	=> $vcr['input_point'],//入点时间
			'output_point' 	=> $vcr['output_point'],//出点时间
			'hash_id'		=> $vcr['hash_id'],
		);
		
		$sql = ' INSERT INTO ' .DB_PREFIX. 'fast_vcr_tmp SET ';
		foreach($data AS $k => $v)
		{
			$sql .= $k  . ' = "' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		//保存图片data数据
		$start_filename = $vcr['hash_id'] .'_start.img'; 
		$end_filename   = $vcr['hash_id'] .'_end.img'; 
		if (!hg_mkdir(FAST_EDIT_IMGDATA_PATH) || !is_writeable(FAST_EDIT_IMGDATA_PATH))
		{
			$this->errorOutput(NOWRITE);
		}
		
		if($this->input['start_imgdata'])
		{
			@file_put_contents(FAST_EDIT_IMGDATA_PATH . $start_filename, $this->input['start_imgdata']);
		}
		
		if($this->input['end_imgdata'])
		{
			@file_put_contents(FAST_EDIT_IMGDATA_PATH . $start_filename, $this->input['end_imgdata']);
		}
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存更新片段
	private function auto_save_update($vcr)
	{
		//先判断更新哪个表
		if($this->input['start_imgdata'])
		{
			@file_put_contents(FAST_EDIT_IMGDATA_PATH . $vcr['hash_id'] .'_start.img', $this->input['start_imgdata']);
		}
		
		if($this->input['end_imgdata'])
		{
			@file_put_contents(FAST_EDIT_IMGDATA_PATH . $vcr['hash_id'] .'_end.img', $this->input['end_imgdata']);
		}
		
		$sql = "UPDATE " .DB_PREFIX. "fast_vcr_tmp SET ";
		$data = array(
			'input_point' 	=> $vcr['input_point'],//入点时间
			'output_point' 	=> $vcr['output_point'],//出点时间
		);
		foreach($data AS $k => $v)
		{
			$sql .= $k  . ' = "' . $v . '",';
		}
		$sql  = rtrim($sql,',');
		$sql .= " WHERE hash_id = '" .$vcr['hash_id']. "'";
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存删除片段
	public function auto_save_delete()
	{
		if(!$this->input['hash_id'])
		{
			$this->errorOutput(NOID);
		}
		//先删除fast_vcr_tmp表
		$sql = " DELETE FROM " .DB_PREFIX. "fast_vcr_tmp WHERE hash_id IN (" .$this->input['hash_id']. ")";
		$this->db->query($sql);
		//删除图片文件
		$hash_arr = explode(',',$this->input['hash_id']);
		foreach($hash_arr AS $v)
		{
			@unlink(FAST_EDIT_IMGDATA_PATH . $v .'_start.img');
			@unlink(FAST_EDIT_IMGDATA_PATH . $v .'_end.img');
		}
		$this->addItem('success');
		$this->output();
	}
	
	//自动保存排序片段
	public function auto_save_order()
	{
		$order_id = $this->input['order_id'];
		$hash_id  = $this->input['hash_id'];
		foreach($order_id AS $k => $v)
		{
			$sql = " UPDATE " .DB_PREFIX. "fast_vcr_tmp SET order_id = '" .$v. "' WHERE hash_id = '" .$hash_id[$k]. "'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}
	
	//保存底部添加的视频
	public function save_added_videos()
	{
		$data = array(
			'user_id'		=> $this->user['user_id'],
			'main_video_id'	=> $this->input['main_video_id'],//主页面视频id（从哪个视频点进来的）
			'vodinfo_id'	=> $this->input['vodinfo_id'],//视频id（该片段来自于哪个视频）
		);
		
		$sql = ' INSERT INTO ' .DB_PREFIX. 'fast_add_videos_tmp SET ';
		foreach($data AS $k => $v)
		{
			$sql .= $k  . ' = "' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	//删除临时添加的视频
	public function delete_added_videos()
	{
		if(!$this->input['vodinfo_id'] || !$this->input['main_video_id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = " DELETE FROM " .DB_PREFIX. "fast_add_videos_tmp WHERE vodinfo_id = '" .$this->input['vodinfo_id']. "' AND main_video_id = '" .$this->input['main_video_id']. "' AND user_id = '" .$this->user['user_id']. "'";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}
	
	//提交到快编接口
	public function submit_fast_edit()
	{
		if(!$this->input['main_video_id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp  WHERE main_video_id = '" .$this->input['main_video_id']. "' AND user_id = '" .$this->user['user_id']. "' ORDER BY order_id ASC ";
		$q = $this->db->query($sql);
		$vcr_tmp = array();
		$hash_ids = array();
		$vcr_ids = array();
		while($r = $this->db->fetch_array($q))
		{
			$hash_ids[] = $r['hash_id'];
			$vcr_tmp[] = $r;
			if($r['vcr_type'] != 4)
			{
				if(!in_array($r['vodinfo_id'],$vcr_ids))
				{
					$vcr_ids[] = $r['vodinfo_id'];
				}
			}
		}
		
		if(!$vcr_tmp && empty($vcr_tmp))
		{
			$this->errorOutput('找不到片段数据');
		}
		
		$vcr_videoinfo = array();//存储片头，片花，片尾类型视频的信息
		if(!empty($vcr_ids))
		{
			$sql = " SELECT * FROM " .DB_PREFIX. "vodinfo WHERE id IN (" .implode(',',$vcr_ids). ")";
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$vcr_videoinfo[$row['id']] = $row;
			}
		}
		
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] .'admin/');
		$curl->setSubmitType('post');
		$curl->initPostData();
		foreach($vcr_tmp AS $k => $v)
		{
			if($v['vcr_type'] != 4)
			{
				$v['input_point'] 	= $vcr_videoinfo[$v['vodinfo_id']]['start'];
				$duration 			= $vcr_videoinfo[$v['vodinfo_id']]['duration'];
			}
			else 
			{
				$duration = intval($v['output_point']) - intval($v['input_point']);
			}
			$curl->addRequestData('start_time['.$k.']',$v['input_point']);
			$curl->addRequestData('duration['.$k.']',$duration);
			$curl->addRequestData('original_id['.$k.']',$v['vodinfo_id']);
			$curl->addRequestData('order_id['.$k.']',$v['order_id']);
		}
		$curl->addRequestData('a','fast_edit');
		$curl->addRequestData('id',$this->input['main_video_id']);
		$curl->request('vod_add_video_mark.php');
		
		//删除临时片段数据
		$sql = "DELETE FROM " . DB_PREFIX . "fast_vcr_tmp WHERE hash_id IN (" .implode(',',$hash_ids). ")";
		$this->db->query($sql);
		$sql = "DELETE FROM " .DB_PREFIX . "fast_add_videos_tmp WHERE main_video_id = '" .$this->input['main_video_id']. "' AND user_id = '" .$this->user['user_id']. "'";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_fast_edit_video_opration();
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