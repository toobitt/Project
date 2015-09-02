<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
define('MOD_UNIQUEID', 'video_fast_edit');
require_once('global.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
class  vod_fast_edit_video_update extends adminUpdateBase
{
    public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	
	//自动保存片段
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
			'main_id'		=> $this->input['main_id'],
			'vodinfo_id'	=> $vcr['vodinfo_id'],//视频id（该片段来自于哪个视频）
			'img'			=> $vcr['img'],//保存图片链接
			'start_time' 	=> $vcr['start_time'],//入点时间
			'end_time' 		=> $vcr['end_time'],//出点时间
			'hash_id'		=> $vcr['hash_id'],
			'ext_info'		=> addslashes(html_entity_decode($vcr['ext_info'])),
		);
		
		$sql = ' INSERT INTO ' .DB_PREFIX. 'fast_vcr_tmp SET ';
		foreach($data AS $k => $v)
		{
			$sql .= $k  . ' = "' . $v . '",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
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
			'start_time' 	=> $vcr['start_time'],//入点时间
			'end_time' 		=> $vcr['end_time'],//出点时间
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
		$hash_id  = $this->input['hashs'];
		foreach($hash_id AS $k => $v)
		{
			$sql = " UPDATE " .DB_PREFIX. "fast_vcr_tmp SET order_id = '" .intval($k + 1). "' WHERE hash_id = '" .$hash_id[$k]. "'";
			$this->db->query($sql);
		}
		$this->addItem('success');
		$this->output();
	}
	
	//清空临时报数据
	public function clear_tmp()
	{
		//首先清除图片物理文件
		$tmp = array();
		$sql = " SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp WHERE user_id = '" .$this->user['user_id']. "'";
		$q = $this->db->query_first($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tmp[] = $r;
		}
		foreach($tmp AS $v)
		{
			@unlink(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] .'_start.img');
			@unlink(FAST_EDIT_IMGDATA_PATH . $v['hash_id'] .'_end.img');
		}
		//再删除表里面的数据
		$sql = " DELETE FROM " .DB_PREFIX. "fast_vcr_tmp WHERE user_id = '" .$this->user['user_id']. "'";
		$this->db->query($sql);
		$this->addItem('success');
		$this->output();
	}

	//保存快编（覆盖原来的）
	public function save_fast_edit()
	{
		//要覆盖的视频id
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		/*********************权限管理*****************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$prms['_action'] = 'update';
			$prms['id'] = $this->input['id'];
			$current_video = $this->get_videos($prms['id']);
			$prms['node'] = $current_video['vod_sort_id'];
			$this->verify_self_prms($prms);
		}
		/*********************权限管理*****************/
		$sql = "SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp WHERE user_id = '".$this->user['user_id']."' ORDER BY order_id ASC ";
		$q   = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tmp[] = $r;
		}
		
		if(!$tmp)
		{
			$this->addLogs('保存快编有误', '', '','没有可用于快编的视频片段,视频id：' .$this->input['id']);
			$this->addItem(array('error' => '没有可用于保存快编的视频片段'));
			$this->output();
		}
		file_put_contents('../cache/1.txt',var_export($tmp,1));
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('id',$this->input['id']);
		$curl->addRequestData('a','fast_edit');
		foreach($tmp AS $k => $v)
		{
			$curl->addRequestData('start_time['.$k.']',$v['start_time']);
			$curl->addRequestData('duration['.$k.']',intval($v['end_time']) - intval($v['start_time']));
			$curl->addRequestData('original_id['.$k.']',$v['vodinfo_id']);
		}
		$ret = $curl->request('vod_add_video_mark.php');
		file_put_contents('../cache/2.txt',var_export($ret,1));
		//提交之后清空临时表
		$this->clear_tmp();
		$this->addItem('success');
		$this->output();
	}
	
	//另存快编
	public function save_as_fast_edit()
	{

		$sql = "SELECT * FROM " .DB_PREFIX. "fast_vcr_tmp WHERE user_id = '".$this->user['user_id']."' ORDER BY order_id ASC ";
		$q   = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$tmp[] = $r;
		}
		
		if(!$tmp)
		{
			$this->addLogs('另存快编有误', '', '','没有可用于另存快编的视频片段,用户名：' .$this->user['user_name']);
			$this->addItem(array('error' => '没有可用于另存快编的视频片段'));
			$this->output();
		}
		
		//分类id
		$vod_sort_id = $this->input['vod_sort_id']?$this->input['vod_sort_id']:4;
		
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','video_merge');
		$curl->addRequestData('title',$this->input['title']);//标题
		$curl->addRequestData('comment',$this->input['comment']);//描述
		$curl->addRequestData('vod_leixing',4);//类型为标注归档
		$curl->addRequestData('vod_sort_id',$vod_sort_id);
		if($this->input['column_id'])//发布的栏目
		{
			$curl->addRequestData('column_id',$this->input['column_id']);
		}
		foreach($tmp AS $k => $v)
		{
			$curl->addRequestData('start_time['.$k.']',$v['start_time']);
			$curl->addRequestData('duration['.$k.']',intval($v['end_time']) - intval($v['start_time']));
			$curl->addRequestData('vodinfo_id['.$k.']',$v['vodinfo_id']);
		}
		$curl->request('vod_add_video_mark.php');
		//提交之后清空临时表
		$this->clear_tmp();
		$this->addItem('success');
		$this->output();
	}
	
	public function get_videos($id)
    {
    	$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$id);
		$curl->addRequestData('a','get_videos');
		$video = $curl->request('vod.php');
		return $video[0];
    }
    
	private function verify_self_prms($data = array())
	{
		$action  = $data['_action'] ? $data['_action'] : $this->input['a'];
		if ($this->user['user_id'] < 1)
		{
			$this->errorOutput(USER_NOT_LOGIN);
		}
		
		if($this->user['group_type'] <= MAX_ADMIN_TYPE)
		{
			return;
		}
		
		if(!in_array($action,(array)$this->user['prms']['app_prms']['livmedia']['action']))
		{
			$this->errorOutput(NO_PRIVILEGE);
		}
		
		if($data['id'])
		{
			$manage_other_data = $this->user['prms']['default_setting']['manage_other_data'];
			if(!$manage_other_data)
			{
				if($this->user['user_id'] != $data['user_id'])
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
			//1 代表组织机构以内
			if($manage_other_data == 1 && $this->user['slave_org'])
			{
				if(!in_array($data['org_id'], explode(',', $this->user['slave_org'])))
				{
					$this->errorOutput(NO_PRIVILEGE);
				}
			}
		}
		
		if($data['node'])
		{
			$auth_prms_nodes = $this->get_childs_nodes();
			if(!in_array($data['node'],$auth_prms_nodes))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
	}
	
	private function get_childs_nodes()
	{
		$prms_nodes = implode(',',$this->user['prms']['app_prms']['livmedia']['nodes']);
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
    	$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('id',$prms_nodes);
		$curl->addRequestData('a','get_childs_nodes');
		$nodes = array();
		$nodes = $curl->request('vod.php');
		if($nodes && $nodes[0])
		{
			$nodes = $nodes[0];
		}
		return $nodes;
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}

$out = new vod_fast_edit_video_update();
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