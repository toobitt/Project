<?php
require('global.php');
define('MOD_UNIQUEID','ticket_venue');//模块标识
define('SCRIPT_NAME', 'VenueUpdate');
class VenueUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        $this->verify_setting_prms();
		include(CUR_CONF_PATH . 'lib/venue.class.php');
		$this->obj = new Ticket();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	function create()
	{	
		$name = addslashes(trim($this->input['name']));
		if(!$name)
		{
			$this->errorOutput("请填写场馆名称");
		}
		
		$info = array();
		$info = array(
			'venue_name'		=> $name,
            'content'			=> addslashes(trim($this->input['content'])),
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'venue_address'		=> addslashes(trim($this->input['address'])),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
		);
		
		$id = $this->obj->create($info,'venue');
		$info['id'] = $id;
		if($id)
		{
			//更新排序id
			$this->obj->update("order_id = {$id}", 'venue', "id={$id}");
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
		$name = addslashes(trim($this->input['name']));
		if(!$name)
		{
			$this->errorOutput("请填写场馆名称");
		}
		$info = array();
		$info = array(
			'venue_name'		=> $name,
            'content'			=> addslashes(trim($this->input['content'])),
			//'org_id'			=> $this->user['org_id'],
			//'user_id'			=> $this->user['user_id'],
			//'user_name'			=> $this->user['user_name'],
			//'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,
			'venue_address'		=> addslashes(trim($this->input['address'])),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
		);
	
		$ret = $this->obj->update($info,'venue',"id={$id}");
		$this->addItem($ret);
		$this->output();
	}
	
	
	public function sort()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		$ids       = explode(',',urldecode($this->input['video_id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " .DB_PREFIX. "venue SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
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
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."show WHERE venue_id IN ({$ids})";
		$res = $this->db->query_first($sql);
	
		if($res['total'])
		{
			$this->errorOutput('请先删除场馆下演出');
		}
		
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('venue',$where);
		$this->addItem('success');
		$this->output();
	}
	
	public function audit(){}
	public function publish(){}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

include(ROOT_PATH . 'excute.php');

?>
