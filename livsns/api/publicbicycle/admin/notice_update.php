<?php
require('global.php');
define('MOD_UNIQUEID','notice');//模块标识
define('SCRIPT_NAME', 'NoticeUpdate');
class NoticeUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        $this->verify_setting_prms();
		include(CUR_CONF_PATH . 'lib/station.class.php');
		$this->obj = new station();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function create()
	{	
		$content = $this->input['content'];
		if(!$content)
		{
			$this->errorOutput("请填写通知内容");
		}
		$station_id = intval($this->input['station_id']);
		/*if(!$station_id || $station_id == -1)
		{
			$this->errorOutput('请选择站点');
		}*/
		
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $station_id == -1)
		{
			//能否修改他人数据判断
			$sql = 'SELECT id,user_id,org_id FROM '.DB_PREFIX.'station WHERE id = '.$station_id;
			$q = $this->db->query_first($sql);
			$data['id'] = $station_id;
			$data['user_id'] = $q['user_id'];
			$data['org_id'] = $q['org_id'];
			
			$data['_action'] = 'manage';
			$this->verify_content_prms($data);
		}
		$info = array();
		$info = array(
			'station_id'		=> $station_id,
            'content'			=> $content,
			'title'				=> $this->input['title'],
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
		);
		
		$id = $this->obj->create($info,'notice');
		$info['id'] = $id;
		if($id)
		{
			//更新排序id
			$this->obj->update("order_id = {$id}", 'notice', "id={$id}");
			
			if($station_id != -1)
			{
				$sql = 'UPDATE '.DB_PREFIX.'station SET notice_num = notice_num+1 WHERE id = '.$station_id;
				$this->db->query($sql);
			}
		}
		$this->addItem($info);
		$this->output();
	}
	
	function update()
	{	
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$content = $this->input['content'];
		if(!$content)
		{
			$this->errorOutput("请填写公司名称");
		}
		$station_id = intval($this->input['station_id']);
		/*if(!$station_id)
		{
			$this->errorOutput('请选择站点');
		}*/
		$sql = 'SELECT station_id FROM '.DB_PREFIX.'notice WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		$old_station_id = $res['station_id'];
		$info = array();
		$info = array(
            'content'			=> $content,
			'title'				=> $this->input['title'],
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'station_id'		=> $station_id,
		);
		
		$ret = $this->obj->update($info,'notice',"id={$id}");
		if($ret && $old_station_id != $station_id)
		{
			if($old_station_id != -1)
			{
				$this->obj->update('notice_num = notice_num-1', 'station', "id={$old_station_id}");
			}
			if($station_id !=-1)
			{
				$this->obj->update('notice_num = notice_num+1', 'station', "id={$station_id}");
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function sort()
	{
		if(!$this->input['content_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',',urldecode($this->input['content_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "notice SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput(NOID);
		}
		
		//查询被删除的通知属于哪个站点
		$sql = 'SELECT station_id FROM '.DB_PREFIX.'notice WHERE id IN ('.$ids.')';
		$q = $this->db->query($sql);
		
		while ($r = $this->db->fetch_array($q))
		{
			if($station_id[$r['station_id']])
			{
				$station_id[$r['station_id']] += 1; 
			}
			else 
			{
				$station_id[$r['station_id']] = 1;
			}
		}
		
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('notice',$where);
		if($station_id)
		{
			foreach ($station_id as $k =>$v)
			{
				$this->obj->update("notice_num = notice_num-{$v}", 'station', "id = {$k}");
			}
		}
		$this->addItem('sucess');
		$this->output();
		
	}
	
	public function audit()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput('没有id');
		}
		
		$ids = urldecode($this->input['id']);
		$arr_id = explode(',', $ids);
		
		$sql = 'UPDATE '.DB_PREFIX.'notice SET state = 1 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		
		$arr = array('id' => $arr_id,'status' => 'back');
		$this->addItem($arr);
		$this->output();
	}
	
	public function back()
	{
		if (!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ids = urldecode($this->input['id']);
		$arr_id = explode(',', $ids);
		
		$sql = 'UPDATE '.DB_PREFIX.'notice SET state = 2 WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		
		$arr = array('id' => $arr_id,'status' => 'audit');
		
		$this->addItem($arr);
		$this->output();
	}
	
	public function publish(){}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

include(ROOT_PATH . 'excute.php');

?>
