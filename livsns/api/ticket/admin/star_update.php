<?php
require('global.php');
define('MOD_UNIQUEID','ticket_star');//模块标识
define('SCRIPT_NAME', 'StarUpdate');
class StarUpdate extends adminUpdateBase
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
			$this->errorOutput("请填明星名称");
		}
		
		$info = array();
		$info = array(
			'name'				=> $name,
			'brief'				=> addslashes(trim($this->input['brief'])),
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
		);
		
		$id = $this->obj->create($info,'star');
		$info['id'] = $id;
		if($id)
		{
			if($_FILES['Filedata'])
			{
				$res = $this->obj->uploadToPicServer($_FILES);
				if ($res)
				{
					$url = array(
						'host'=>$res['host'],
						'dir'=>$res['dir'],
						'filepath'=>$res['filepath'],
						'filename'=>$res['filename'],
					);
					$update_info['logo'] = serialize($url);
				}
			}
			$update_info['order_id'] = $id;
			//更新排序id和明星头像
			$this->obj->update($update_info, 'star', "id={$id}");
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
			$this->errorOutput("请填写明星名称");
		}
		$info = array();
		$info = array(
			'name'				=> $name,
			'brief'				=> addslashes(trim($this->input['brief'])),
			//'org_id'			=> $this->user['org_id'],
			//'user_id'			=> $this->user['user_id'],
			//'user_name'		=> $this->user['user_name'],
			//'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,
		);
	
		if($_FILES['Filedata'])
		{
			$res = $this->obj->uploadToPicServer($_FILES);
			if ($res)
			{
				$url = array(
					'host'=>$res['host'],
					'dir'=>$res['dir'],
					'filepath'=>$res['filepath'],
					'filename'=>$res['filename'],
				);
				$info['logo'] = serialize($url);
			}
		}
		$ret = $this->obj->update($info,'star',"id={$id}");
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
			$sql = "UPDATE " .DB_PREFIX. "star SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
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
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."star_trip WHERE show_end_time > " . TIMENOW . " AND star_id IN ({$ids})";
		$res = $this->db->query_first($sql);
	
		if($res['total'])
		{
			$this->errorOutput('明星还有未结束的演出');
		}
		
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('star',$where);
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
