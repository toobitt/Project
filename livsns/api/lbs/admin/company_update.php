<?php
require('global.php');
define('MOD_UNIQUEID','company');//模块标识
define('SCRIPT_NAME', 'CompanyUpdate');
class CompanyUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		//检测是否具有配置权限
        //$this->verify_setting_prms();
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
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写公司名称");
		}
		
		//返回值替换
		$map_val = array();
		if($this->input['map_val_key'])
		{
			foreach ($this->input['map_val_key'] as $k => $v)
			{
				$map_val[$v] = trim($this->input['map_val'][$k]);
			}
			
			$map_val = serialize($map_val);
		}
		$map_val = $map_val ? $map_val : '';
		
		$info = array();
		$info = array(
			'name'				=> $name,
            'brief'				=> $this->input['brief'],
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'latitude'			=> $this->input['latitude'],
			'longitude'			=> $this->input['longitude'],
			'province'			=> intval($this->input['province']),
			'city'				=> intval($this->input['city']),
			'area'				=> intval($this->input['area']),
			'address'			=> $this->input['address'],
			'customer_hotline'	=> $this->input['customer_hotline'],
			'card_hotline'		=> $this->input['card_hotline'],
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'api_url'			=> $this->input['api_url'],
			'map'				=> $map_val,
			'data_node'			=> trim($this->input['data_node']),
			'convert_set'		=> intval($this->input['convert_set']),
			'addlong'			=> trim($this->input['addlong']),
			'addlat'			=> trim($this->input['addlat']),
			'data_pre'			=> $this->input['data_pre'],
			'station_count'		=> intval($this->input['station_count']),
			'park_num_api'		=> $this->input['park_num_api'],
		);
		
		if($_FILES['logo'])
		{
			$res = $this->obj->add_material($_FILES['logo']);
			if($res)
			{
				$info['logo'] = $res;
			}
		}	
		if($_FILES['station_icon'])
		{
			$res = '';
			$res = $this->obj->add_material($_FILES['station_icon']);
			if($res)
			{
				$info['station_icon'] = $res;
			}
		}
		$id = $this->obj->create($info,'company');
		$info['id'] = $id;
		if($id)
		{
			//更新排序id
			$this->obj->update("order_id = {$id}", 'company', "id={$id}");
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
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写公司名称");
		}
		
		//返回值替换
		$map_val = array();
		if($this->input['map_val_key'])
		{
			foreach ($this->input['map_val_key'] as $k => $v)
			{
				$map_val[$v] = trim($this->input['map_val'][$k]);
			}
			
			$map_val = serialize($map_val);
		}
		$map_val = $map_val ? $map_val : '';
		
		$info = array();
		$info = array(
			'name'				=> $name,
            'brief'				=> $this->input['brief'],
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'latitude'			=> $this->input['latitude'],
			'longitude'			=> $this->input['longitude'],
			'province'			=> intval($this->input['province']),
			'city'				=> intval($this->input['city']),
			'area'				=> intval($this->input['area']),
			'address'			=> $this->input['address'],
			'customer_hotline'	=> $this->input['customer_hotline'],
			'card_hotline'		=> $this->input['card_hotline'],
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'api_url'			=> $this->input['api_url'],
			'map'				=> $map_val,
			'data_node'			=> trim($this->input['data_node']),
			'convert_set'		=> intval($this->input['convert_set']),
			'addlong'			=> trim($this->input['addlong']),
			'addlat'			=> trim($this->input['addlat']),
			'data_pre'			=> $this->input['data_pre'],
			'station_count'		=> intval($this->input['station_count']),
			'park_num_api'		=> $this->input['park_num_api'],
		);
		
		if($_FILES['logo'])
		{
			$res = $this->obj->add_material($_FILES['logo']);
			if($res)
			{
				$info['logo'] = $res;
			}
		}	
		if($_FILES['station_icon'])
		{
			$res = '';
			$res = $this->obj->add_material($_FILES['station_icon']);
			if($res)
			{
				$info['station_icon'] = $res;
			}
		}
	
		$ret = $this->obj->update($info,'company',"id={$id}");
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
			$sql = "UPDATE " .DB_PREFIX. "company SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
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
		$sql = "SELECT station_count FROM ".DB_PREFIX."company WHERE id IN ({$ids})";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			if($r['station_count'])
			{
				$this->errorOutput('请先删除运营单位下站点');
			}
		}
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('company',$where);
		$this->addItem('sucess');
		$this->output();
		
	}
	
	public function audit()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$audit = intval($this->input['audit']);
		
		switch ($audit)
		{
			case 0:$status = 2;break;//打回
			case 1:$status = 1;break;
		}
		
		$sql = " UPDATE " .DB_PREFIX. "company SET status = '" .$status. "' WHERE id IN (" .$id. ")";
		$this->db->query($sql);
		$ret = array('status' => $status,'id' => $id);
		
		$this->addItem($ret);
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
