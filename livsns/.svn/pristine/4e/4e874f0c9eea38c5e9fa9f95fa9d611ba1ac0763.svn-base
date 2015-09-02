<?php
require('global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','bicycle_station');//模块标识
define('SCRIPT_NAME', 'BicycleStationUpdate');
class BicycleStationUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/station.class.php');
		$this->obj = new station();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
	}
	
	function create()
	{	
		$name = $this->input['name'];
		if(!$name)
		{
			$this->errorOutput("请填写站点名称");
		}
		$station_id = intval($this->input['station_id']);
		if(!$station_id)
		{
			$this->errorOutput('站点编号为数字');
		}
		$region_id = intval($this->input['region_id']);
		if(!$region_id)
		{
			//$this->errorOutput("请选择区域");
		}
		$company_id = intval($this->input['company_id']);
		if(!$company_id)
		{
			//$this->errorOutput('请选择运营单位');
			
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['region_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'region WHERE id IN('.$this->input['region_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点	
			
		$totalnum = intval($this->input['totalnum']);
		$currentnum = intval($this->input['currentnum']);
		
		$info = array();
		$info = array(
			'name'				=> $name,
			'station_id'		=> $station_id,
            'region_id'			=> $region_id,
            'brief'				=> $this->input['brief'],
			'company_id'		=> $company_id,
			'address'			=> $this->input['address'],
			'totalnum'			=> $totalnum,
			'currentnum'		=> $currentnum,
			'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'province'			=> intval($this->input['province']),
			'city'				=> intval($this->input['city']),
			'area'				=> intval($this->input['area']),
			'type'				=> 1,
			//获取状态设置值
			'state'    			=> $this->get_status_setting('create'),
		);
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($info['baidu_latitude'] && $info['baidu_longitude'])
		{
			$gps = FromBaiduToGpsXY($info['baidu_longitude'],$info['baidu_latitude']);
			$info['stationx'] = $gps['GPS_x'];
			$info['stationy'] = $gps['GPS_y'];
		}
		else
		{
			$info['stationx'] = 0;
			$info['stationx'] = 0;
		}
		$id = $this->obj->create($info,'station');
		
		//更新区域站点数目
		if($id)
		{
			//更新区域站点数目
			$this->obj->update('station_num = station_num+1', 'region', "id={$region_id}");
			
			//更新运营单位站点数目
			$this->obj->update('station_count = station_count+1', 'company', "id={$company_id}");
			
			//更新排序id
			$this->obj->update("order_id = {$id}", 'station', "id={$id}");
			
			//更新素材表中素材内容id
			$img_id = $this->input['img_id'];
			
			if($img_id)
			{
				if(!$this->input['indexpic_id'])
				{
					$indexpic_id = $img_id[0];
				}
				else 
				{
					$indexpic_id = intval($this->input['indexpic_id']);
				}
				//更新索引图id
				$this->obj->update("material_id = {$indexpic_id}", 'station', "id={$id}");
					
				$img_id = implode(',', $img_id);
				$this->obj->update("cid = {$id}", 'material', "id IN ({$img_id})");
				
			}
		}
		
		
		//上传图片
		/*if($_FILES['Filedata'] && $id)
		{	
			$photos = array();
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			
			//获取图片服务器上传配置
			$PhotoConfig = $this->obj->getPhotoConfig();
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
			}
			
			$count = count($_FILES['Filedata']['name']);
			for($i = 0; $i <= $count; $i++)
			{
				if ($_FILES['Filedata']['name'][$i])
				{
					if ($_FILES['Filedata']['error'][$i]>0)
					{
						$this->errorOutput('图片上传异常');
					}
					if (!in_array($_FILES['Filedata']['type'][$i], $PhotoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$PhotoConfig['hit'].'格式的图片');
					}
					if ($_FILES['Filedata']['size'][$i]>100000000)
					{
						$this->errorOutput('只允许上传100M以下的图片!');
					}
					foreach($_FILES['Filedata'] AS $k =>$v)
					{
						$photo['Filedata'][$k] = $_FILES['Filedata'][$k][$i];
					}
					$photos[] = $photo;
				}			
			}
			if (!empty($photos))
			{
				//循环插入图片服务器
				foreach ($photos as $val)
				{
					$PhotoInfor = $this->obj->uploadToPicServer($val, $id);
					if (empty($PhotoInfor))
					{
						$this->errorOutput('图片服务器错误!');
					}
					$temp = array(
						'cid'			=> $id,
						'type'			=> $PhotoInfor['type'],						
						'material_id'	=> $PhotoInfor['id'],
						'host'			=> $PhotoInfor['host'],
						'dir'			=> $PhotoInfor['dir'],
						'filepath' 		=> $PhotoInfor['filepath'],
						'filename'		=> $PhotoInfor['filename'],
					);
					//插入素材表
					$PhotoId = $this->obj->insert_material($temp);
					
					//默认第一张图片为索引图
					if(!$indexpic)
					{
						$indexpic = $this->obj->update_indexpic($PhotoId, $id);
					}
				}
			}
		}*/	
		$info['id'] = $id;
		//添加日志
		$this->addLogs('创建自行车站点','',$info,$info['name']);
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
			$this->errorOutput("请填写站点名称");
		}
		$station_id = intval($this->input['station_id']);
		if(!$station_id)
		{
			$this->errorOutput('站点编号为数字');
		}
		$region_id = intval($this->input['region_id']);
		if(!$region_id)
		{
			//$this->errorOutput("请选择区域");
		}
		$company_id = intval($this->input['company_id']);
		if(!$company_id)
		{
			//$this->errorOutput('请选择运营单位');
		}
		$totalnum = intval($this->input['totalnum']);
		$currentnum = intval($this->input['currentnum']);
		
		//查询修改站点之前信息
		$sql = 'SELECT * FROM '.DB_PREFIX.'station WHERE id = '.$id;
		$q = $this->db->query_first($sql);
		$old_region_id = $q['region_id'];
		$old_company_id = $q['company_id'];
		$stat_station_detail = $q;
		$order_id = $q['order_id'];
		#####
		
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_sort_ids = '';
			if($q['region_id'])
			{
				$_sort_ids = $q['region_id'];
			}
			if($this->input['region_id'])
			{
				$_sort_ids  = $_sort_ids ? $_sort_ids . ',' . $this->input['region_id'] : $this->input['region_id'];
			}
			if($_sort_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'region WHERE id IN('.$_sort_ids.')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$data['nodes'][$row['id']] = $row['parents'];
				}
				//$this->errorOutput(var_export($data['nodes']['news_node'],1));
			}
		}
		#####节点权限
		
		$data['id'] = $id;
		$data['user_id'] = $q['user_id'];
		$data['org_id'] = $q['org_id'];
		
		$data['_action'] = 'manage';
		$this->verify_content_prms($data);
		
		if(intval($q['state']) == 1 && $this->user['group_type']>MAX_ADMIN_TYPE)
		{
			$status = $this->get_status_setting('update_audit', $q['state']);
		}
		else 
		{
			$status = $q['state'];
		}
		//$this->errorOutput('false'.$status);
		
		######获取默认数据状态
		
		#####结束
		$info = array();
		$info = array(
			'name'				=> $name,
			'station_id'		=> $station_id,
            'region_id'			=> $region_id,
            'brief'				=> $this->input['brief'],
			'company_id'		=> $company_id,
			'address'			=> $this->input['address'],
			'totalnum'			=> $totalnum,
			'currentnum'		=> $currentnum,
			/*'org_id'			=> $this->user['org_id'],
			'user_id'			=> $this->user['user_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> $this->user['ip'],
			'update_time'		=> TIMENOW,*/
			'appid'				=> $this->user['appid'],
			'appname'			=> $this->user['display_name'],
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'province'			=> intval($this->input['province']),
			'city'				=> intval($this->input['city']),
			'area'				=> intval($this->input['area']),
			'state'				=> $status
		);
		//如果排序id不存在，默认与id相同
		if(!$order_id)
		{
			$info['order_id'] = $id;
		}
		$ret = $this->obj->update($info,'station',"id={$id}");
		if($ret)
		{
			//区域站点计数更新
			if($old_region_id != $region_id)
			{
				//原区域站点数目-1
				$this->obj->update('station_num = station_num-1', 'region', "id={$old_region_id}");
				//新区域站点数目+1
				$this->obj->update('station_num = station_num+1', 'region', "id={$region_id}");
			}
			
			//运营单位计数更新
			if($old_company_id != $company_id)
			{
				//原运营单位站点数目-1
				$this->obj->update('station_count = station_count-1', 'company', "id={$old_company_id}");
				//新运营单位站点数目+1
				$this->obj->update('station_count = station_count+1', 'company', "id={$company_id}");
			}
			$this->addLogs('修改站点',$stat_station_detail,$info,$info['name']);	
		}
		
		$this->addItem($ret);
		$this->output();
	}
	
	function delete()
	{			
		$ids = urldecode($this->input['id']);
		if(empty($ids))
		{
			$this->errorOutput(NOID);
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'station WHERE id IN ('.$ids.')';
		$q = $this->db->query($sql);
		
		$conInfor  = array();
		$sorts 	   = array();
		while ($r = $this->db->fetch_array($q))
		{
			$sorts[] 		   	  = $r['sort_id'];
			$conInfor[$row['id']] = $r;
			
			if($region_id_arr[$r['region_id']])
			{
				$region_id_arr[$r['region_id']] += 1; 
			}
			else 
			{
				$region_id_arr[$r['region_id']] = 1;
			}
			
			if($company_id_arr[$r['company_id']])
			{
				$company_id_arr[$r['company_id']] += 1; 
			}
			else 
			{
				$company_id_arr[$r['company_id']] = 1;
			}
		}
		
		/**************权限控制开始**************/
		//节点验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'region WHERE id IN ('.implode(',',$sorts).')';
				$query = $this->db->query($sql);
				$nodes =array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
					
				}
				if (!empty($nodes))
				{
					$nodes['_action'] = 'manage';
					$this->verify_content_prms($nodes);
				}
			}
		}
		//能否修改他人数据
		if (!empty($conInfor) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
		}
		/**************权限控制结束**************/
			
		$where = ' id IN ('.$ids.')';
		$ret = $this->obj->delete('station',$where);
		
		if($ret)
		{
			if($region_id_arr)
			{
				//更新区域站点数目
				foreach ($region_id_arr as $k =>$v)
				{
					$this->obj->update("station_num = station_num-{$v}", 'region', "id = {$k}");
				}
			}
			if($company_id_arr)
			{
				//更新运营单位站点数目
				foreach ($company_id_arr as $k =>$v)
				{
					$this->obj->update("station_count = station_count-{$v}", 'company', "id = {$k}");
				}
			}
			$this->obj->delete('material', " cid IN ({$ids})");
			
			//添加日志
			if (!empty($conInfor))
			{
				$this->addLogs('删除自行车站点', $conInfor,'','删除自行车站点'.$ids);
			}
		}
		$this->addItem('sucess');
		$this->output();
		
	}
	public function audit()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		/**************权限控制开始**************/	
		$sql = "SELECT * FROM " . DB_PREFIX . "station WHERE id=" . $id;
		$info = $this->db->query_first($sql);
		$sorts = array();
		
		$sorts[] = $info['region_id'];
		$status = $info['state'];
		$nodes = array();
		//节点权限
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'region WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
			
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		/**************权限控制结束**************/	
		
		$tip = '';
		
		if ($status == 1)
		{
			$sql = "UPDATE " . DB_PREFIX . "station SET state=0 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 0;
		}
		else 
		{
			//如果已下线，再审核，将变成后台站点，不再从计划任务走
			if($status == 3)
			{
				$sql = "UPDATE " . DB_PREFIX . "station SET state=1,type=1 WHERE id=" . $id;
			}
			else 
			{
				$sql = "UPDATE " . DB_PREFIX . "station SET state=1 WHERE id=" . $id;
			}
			
			$this->db->query($sql);
			$tip = 1;
		}
		$this->addItem($tip);
		$this->output();
	}
	
	//删除站点实景照片
	public function del_pic()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			return false;
		}
		$where = ' id = '.$id;
		$ret = $this->obj->delete('material',$where);
		$this->addItem('sucess');
		$this->output();
	}
	
	public function set_indexpic()
	{
		$id = $this->input['id'];
		$mater_id = $this->input['img_id'];
		if(!$id || !$mater_id)
		{
			return false;
		}
		$this->obj->update("material_id = {$mater_id}", 'station', "id={$id}");
		
		$this->addItem('sucess');
		$this->output();
	}
	
	
	//ajax上传图片
	public function upload()
	{
		if($_FILES['Filedata'])
		{
			$cid = $this->input['cid'];
			
			$PhotoInfor = $this->obj->uploadToPicServer($_FILES);
			if (empty($PhotoInfor))
			{
				return false;
			}
			$temp = array(
				'cid'			=> $cid,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
			);
			//插入素材表
			$PhotoId['id'] = $this->obj->insert_material($temp);
			$this->addItem($PhotoId);
			$this->output();
		}
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
			$sql = "UPDATE " .DB_PREFIX. "station  SET order_id = ".$order_ids[$k]."  WHERE id = ".$v;
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	
	public function publish()
	{
	}
	
	function unknow()
	{
		$this->errorOutput("此方法不存在！");
	}
}

include(ROOT_PATH . 'excute.php');

?>
