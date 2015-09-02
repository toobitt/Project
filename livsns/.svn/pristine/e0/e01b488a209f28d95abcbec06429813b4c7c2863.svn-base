<?php
define('MOD_UNIQUEID','bicycle_station');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require_once(ROOT_PATH.'global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('SCRIPT_NAME', 'station_plan');
class station_plan extends cronBase
{
	protected $company = array();
	
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '自行车站点数据更新',	 
			'brief' => '更新站点可借车数',
			'space' => '60',	//运行时间间隔，单位秒
			'is_use' => 1,		//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		$sort_id = $this->settings['bicycle_sort_id'];
		if(!$sort_id)
		{
			return false;
		}
		
		$offset = $queue['offset']?$queue['offset']:0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		//查询运营单位配置
		$sql = 'SELECT id,api_url,data_pre,data_node,map,convert_set,addlong,addlat,park_num_api FROM '.DB_PREFIX.'company WHERE 1 AND status = 1 ' . $limit;
		$query = $this->db->query($sql);
		$company = array();
		while ($row = $this->db->fetch_array($query))
		{
			if($row['api_url'])
			{
				if($row['map'])
				{
					$row['map'] = unserialize($row['map']);
				}
				
				$company_id[] = $row['id'];
				$company[$row['id']] = $row;
			}
		}
		if(!$company)
		{
			return false;
		}
		$this->company = $company;
		//hg_pre($company,1);
		
		
		//循环请求各运营单位接口
		$ch = curl_init();
		foreach ($company as $id => $val)
		{
			curl_setopt($ch, CURLOPT_URL, $val['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
			curl_setopt($ch, CURLOPT_TIMEOUT,10);
			$response = array();
			$response = curl_exec($ch);
			
			
			if(!$response)
			{
				continue;
			}
			
			if($val['data_pre'])
			{
				//$response = str_replace('var ibike = ','',$response);
				$response = str_replace($val['data_pre'],'',$response);
			}
			
			$response = json_decode($response,1);
			$data_tmp = array();
			if($val['data_node'])
			{
				if(!$response[$val['data_node']])
				{
					continue;
				}
				$data = array();
				$data = $response[$val['data_node']];
				if($val['map'])
				{
					foreach ($data as $k => $v)
					{
						//对返回值进行字段替换
						foreach ($val['map'] as $kk => $vv)
						{
							if(isset($v[$kk]))
							{
								$v[$vv] = $v[$kk];
								if($vv != $kk)
								{
									unset($v[$kk]);
								}
							}
						}
						
						$data_tmp[] = $v;
					}
					//$res[$id] = $response[$val['data_node']];
					$res[$id] = $data_tmp;
				}
				else 
				{
					$res[$id] = $data;
				}
			}
			else 
			{
				if(!$response['date'])
				{
					continue;
				}
				$data = array();
				$data = $response['date'];
				if($val['map'])
				{
					foreach ($data as $k => $v)
					{
						//替换接口映射字段
						foreach ($val['map'] as $kk => $vv)
						{
							if(isset($v[$kk]))
							{
								$v[$vv] = $v[$kk];
								unset($v[$kk]);
							}
						}
						$data_tmp[] = $v;
					}
					$res[$id] = $data_tmp;
				}
				else 
				{
					$res[$id] = $data;
				}
			}
		}
		curl_close($ch);//关闭
		
		if(!$res)
		{
			return false;
		}
		
		//如果请求有返回,查询运营单位下非后台添加的站点(type=0)
		$comp_ids = implode(',', $company_id); //运营单位ids
		$sql = "SELECT id,title,local_id,baidu_longitude,baidu_latitude,address,company_id 
				FROM ".DB_PREFIX."lbs 
				WHERE sort_id = " . $sort_id . " AND company_id IN (".$comp_ids.")";
		$q = $this->db->query($sql);
		
		$station = array();
		while($r = $this->db->fetch_array($q))
		{
			$station[$r['company_id']][$r['local_id']] = $r;
		}
		
		
		
		//hg_pre($station,0);
		
		$api_data = $res;
		
		
		//剔除已入库的站点
		if($station)
		{
			foreach ($station as $com_id => $v)
			{
				if($api_data[$com_id])
				{
					unset($api_data[$com_id]);
				}
			}
		}
		
		//如果剔除已入库的站点，接口返回还有数据，就进行数据初始化
		if($api_data)
		{
			$this->init_data($api_data,$sort_id);
			return false;
		}
		
		
		//hg_pre($res,0);
		//hg_pre($company_id,0);
		//循环数据库中站点信息，和接口返回的信息进行对比
		foreach ($station as $cid => $val)
		{
			//hg_pre($val,1);
			//接口返回信息
			if(!$res[$cid] || empty($res[$cid]) || !$company_id)
			{
				continue;
			}
			$api_arr = array();
			foreach ($res[$cid] as $k => $v)
			{
				$api_arr[$v['stationid']] = $v;
				//$api_arr[ltrim($v['stationid'],'0')] = $v;
				$add_arr[$cid] = array_diff_key($api_arr, $val);
				$del_arr[$cid] = array_diff_key($val, $api_arr);
				$upd_arr[$cid] = array_intersect_key($api_arr,$val);
			}
		}
		
		
		//hg_pre($add_arr,0);
		//删除,更新站点状态为3,标为已下线
		if(!empty($del_arr) && is_array($del_arr))
		{
			foreach ($del_arr as $cid => $val)
			{
				if(empty($val))
				{
					continue;
				}
				foreach ($val as $sid => $v)
				{
					$sql = "UPDATE ".DB_PREFIX."lbs SET
							status 		= 3
							WHERE local_id = " . $sid . " AND company_id = " . $cid;
					$this->db->query($sql);
				}
				
			}
		}
		
		//更新
		if(!empty($upd_arr) && is_array($upd_arr))
		{
			$convert_set 	= '';//对坐标处理
			$addlong		= '';//经度偏移量
			$addlat			= '';//纬度偏移量
			$park_num_api	= '';//可停可借接口地址
			foreach ($upd_arr as $cid=>$val)
			{
				if(empty($val) || !$cid)
				{
					continue;
				}
				
				//取运营单位里的配置
				if($company[$cid])
				{
					$convert_set 	= $company[$cid]['convert_set'];
					$addlong		= $company[$cid]['addlong'];
					$addlat			= $company[$cid]['addlat'];
					$park_num_api	= $company[$cid]['park_num_api'];
				}
				
				foreach ($val as $sid => $v)
				{
					if(!$sid)
					{
						continue;
					}
					if($park_num_api)
					{
						$ch = curl_init();
						for ($i=1; $i<3; $i++)
						{
							$url = $park_num_api . 'id='  . $sid . '&flag=' .$i;
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_TIMEOUT, 3);
							$response  = curl_exec($ch);
							if(!$response)
							{
								continue;
							}
							
							$num = findNum($response);
							if($i == 1)
							{
								$v['currentnum'] = $num;
							}
							else if($i == 2)
							{
								$v['totalnum'] = $v['currentnum'] + $num;
							}
						}
						curl_close($ch);//关闭
					}
					
					//查询站点在lbs表中id
					$sql = "SELECT id FROM " . DB_PREFIX . "lbs WHERE status = 1 AND local_id = {$sid} AND company_id = {$cid}";
					
					$res = $this->db->query_first($sql);
					if(!$res['id'])
					{
						continue;
					}
					
					//删除扩展表中记录
					$sql = "DELETE FROM " . DB_PREFIX . "fieldcontent WHERE content_id = {$res['id']}";
					$this->db->query($sql);
					
					//记录可停可借数目
					$this->insert_fieldontent($res['id'],$sort_id,$v['totalnum'],$v['currentnum']);
					
					
					//更新站点信息
					$sql = "UPDATE ".DB_PREFIX."lbs SET
							update_time = '" . $v['dateline'] . "'";
					
					//需要处理坐标，并且坐标存在
					if($convert_set && $v['stationx'] && $v['stationy'])
					{
						$zb = '';
						if($convert_set == 2)//gps转百度
						{
							$zb = FromGpsToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
						}
						else if($convert_set == 3) //谷歌转百度
						{
							$zb = FromGoogleToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
						}
						else if($convert_set == 4 && $addlong && $addlat)//添加偏移量
						{
							$zb['x'] = $v['stationx'] + $addlong;
							$zb['y'] = $v['stationy'] + $addlat;
						}
						else if ($convert_set == 1)//接口返回百度坐标，不处理
						{
							$zb['x'] = $v['stationx'];
							$zb['y'] = $v['stationy'];
						}
						if($zb)
						{
							$sql .= ",baidu_longitude='" . $zb['x'] . "',baidu_latitude='" . $zb['y'] . "'";
						}
					}
					$sql .=" WHERE id = " . $res['id'];
					$this->db->query($sql);
				}
			}
		}
		
		
		//新增
		if(!empty($add_arr) && is_array($add_arr))
		{
			
			$user_name		= $this->user['user_name'];
			
			//$add_sql = "INSERT INTO ".DB_PREFIX."station (name, state, station_id, company_id, totalnum, currentnum, baidu_longitude, baidu_latitude, dateline, address, org_id, user_id, user_name, ip, appid, appname, create_time) VALUES";
			
			$convert_set 	= '';//对坐标处理
			$addlong		= '';//经度偏移量
			$addlat			= '';//纬度偏移量
			foreach ($add_arr as $cid => $val)
			{
				if(empty($val))
				{
					continue;
				}
				
				//取运营单位里的配置
				if($company[$cid])
				{
					$convert_set 	= $company[$cid]['convert_set'];
					$addlong		= $company[$cid]['addlong'];
					$addlat			= $company[$cid]['addlat'];
				}
				
				foreach ($val as $sid => $v)
				{
					
					//需要处理坐标，并且坐标存在
					if($convert_set && $v['stationx'] && $v['stationy'])
					{
						$zb = '';
						if($convert_set == 2)//gps转百度
						{
							$zb = FromGpsToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
						}
						else if($convert_set == 3) //谷歌转百度
						{
							$zb = FromGpsToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
						}
						else if($convert_set == 4 && $addlong && $addlat)//添加偏移量
						{
							$zb['x'] = $v['stationx'] + $addlong;
							$zb['y'] = $v['stationy'] + $addlat;
						}
						else if ($convert_set == 1)//接口返回百度坐标，不处理
						{
							$zb['x'] = $v['stationx'];
							$zb['y'] = $v['stationy'];
						}
						if($zb)
						{
							$v['stationx'] = $zb['x'];
							$v['stationy'] = $zb['y'];
						}
					}
					
					$info = array(
						'title' 			=> $v['station'],
						'local_id' 			=> $v['stationid'],
						'address' 			=> $v['address'],
						'baidu_latitude' 	=> $v['stationy'],
						'baidu_longitude' 	=> $v['stationx'],
						'company_id'		=> $cid,
						'create_time'		=> TIMENOW,
						'user_name'			=> $user_name,
						'status'			=> 0,
						'sort_id'			=> $sort_id,
					);
				
					$sql = " INSERT INTO " . DB_PREFIX . "lbs SET ";
					foreach ($info AS $kk => $vv)
					{
						$sql .= " {$kk} = '{$vv}',";
					}
					$sql = trim($sql,',');
					$this->db->query($sql);
					
					$sid = '';
					$sid = $this->db->insert_id();
					if(!$sid)
					{
						continue;
					}
					$sql = " UPDATE ".DB_PREFIX."lbs SET order_id = {$sid}  WHERE id = {$sid}";
					$this->db->query($sql);
				
					$this->insert_fieldontent($sid,$sort_id,$v['totalnum'],$v['currentnum']);
					//$vals.= "('".$v['station']."',1, ".$sid.", ".$cid.", '".$v['totalnum']."','".$v['currentnum']."','".$v['stationx']."','".$v['stationy']."','".$v['dateline']."','".$v['address']."','".$org_id."','".$user_id."','".$user_name."','".$ip."','".$appid."','".$appname."',".TIMENOW."),";
				}
				
				//更新运营单位站点计数
				$station_num = '';
				$station_num = count($val);
				if($station_num)
				{
					$up_sql = "UPDATE ".DB_PREFIX."company SET station_count = station_count + " . $station_num . " WHERE id = " . $cid;
					$this->db->query($up_sql);
				}
			}
			/*if($vals)
			{
				$vals = rtrim($vals,',');
				$add_sql .= $vals;
				$this->db->query($add_sql);
			}*/
		}
	}
	private function insert_fieldontent($content_id,$sort_id,$totalnum='',$currentnum='')
	{
		if(!$content_id || !$sort_id)
		{
			return false;
		}
		
		if($totalnum > $currentnum)
		{
			$kt_num = $totalnum - $currentnum;
		}
		else 
		{
			$kt_num = 0;
		}
		
		$kt_con = array(
			'sort_id'		=> $sort_id,
			'content_id'	=> $content_id,
			'field'			=> 'kt_num',
			'value'			=> $kt_num,
		);
		
		if(!empty($kt_con))
		{
			$sql = '';
			$sql = " INSERT INTO " . DB_PREFIX . "fieldcontent SET ";
			foreach ($kt_con AS $kt_k => $kt_v)
			{
				$sql .= " {$kt_k} = '{$kt_v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
		
		
		$kj_con = array(
			'sort_id'		=> $sort_id,
			'content_id'	=> $content_id,
			'field'			=> 'kj_num',
			'value'			=> $currentnum,
		);
		if(!empty($kj_con))
		{
			$sql = '';
			$sql = " INSERT INTO " . DB_PREFIX . "fieldcontent SET ";
			foreach ($kj_con AS $kj_k => $kj_v)
			{
				$sql .= " {$kj_k} = '{$kj_v}',";
			}
			$sql = trim($sql,',');
			$this->db->query($sql);
		}
				
	}
	private function init_data($data,$sort_id)
	{
		if(!$data || !$sort_id)
		{
			return FALSE;
		}
		
		$user_name 	= $this->user['user_name'];
		$appid		= $this->user['appid'];
		$appname	= $this->user['display_name'];
		//$sql = "INSERT INTO ".DB_PREFIX."station (user_name, state, appid, appname, company_id, name, station_id, baidu_longitude, baidu_latitude, address, totalnum, currentnum, dateline, create_time)VALUES";
		$val = '';
		
		$convert_set 	= '';//对坐标处理
		$addlong		= '';//经度偏移量
		$addlat			= '';//纬度偏移量
		
		foreach ($data as $key => $value)
		{
			if(empty($value))
			{
				continue;
			}
			//取运营单位里的配置
			if($this->company[$key])
			{
				$convert_set 	= $this->company[$key]['convert_set'];
				$addlong		= $this->company[$key]['addlong'];
				$addlat			= $this->company[$key]['addlat'];
			}
			
			$val = '';
			foreach ($value as $k => $v)
			{
				//需要处理坐标，并且坐标存在
				if($convert_set && $v['stationx'] && $v['stationy'])
				{
					$zb = '';
					if($convert_set == 2)//gps转百度
					{
						$zb = FromGpsToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
					}
					else if($convert_set == 3) //谷歌转百度
					{
						$zb = FromGoogleToBaidu($v['stationx'].','.$v['stationy'], BAIDU_AK);
					}
					else if($convert_set == 4 && $addlong && $addlat)//添加偏移量
					{
						$zb['x'] = $v['stationx'] + $addlong;
						$zb['y'] = $v['stationy'] + $addlat;
					}
					else if ($convert_set == 1)//接口返回百度坐标，不处理
					{
						$zb['x'] = $v['stationx'];
						$zb['y'] = $v['stationy'];
					}
					
					if($zb)
					{
						$v['stationx'] = $zb['x'];
						$v['stationy'] = $zb['y'];
					}
				}
				
				$info = array(
					'title' 			=> $v['station'],
					'local_id' 			=> $v['stationid'],
					'address' 			=> $v['address'],
					'baidu_latitude' 	=> $v['stationy'],
					'baidu_longitude' 	=> $v['stationx'],
					'company_id'		=> $key,
					'create_time'		=> TIMENOW,
					'sort_id'			=> $sort_id,
					'user_name'			=> $user_name,
					'status'			=> 0,
				);
				
				$sql = " INSERT INTO " . DB_PREFIX . "lbs SET ";
				foreach ($info AS $kk => $vv)
				{
					$sql .= " {$kk} = '{$vv}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				
				$sid = '';
				$sid = $this->db->insert_id();
				
				if(!$sid)
				{
					continue;
				}
				
				
				$sql = "";
				$sql = " UPDATE ".DB_PREFIX."lbs SET order_id = {$sid}  WHERE id = {$sid}";
				$this->db->query($sql);
				
				//扩展字段记录可停，可借数目
				$this->insert_fieldontent($sid,$sort_id,$v['totalnum'],$v['currentnum']);
				//$val .=  "('" . $user_name . "',1," . $appid . ",'" . $appname . "','" . $key . "','" . $v['station'] . "','".$v['stationid']."','".$v['stationx']."','".$v['stationy']."','".$v['address']."','".$v['totalnum']."','".$v['currentnum']."','".$v['dateline']."',".TIMENOW."),";
			}
			
			//更新运营单位站点计数
			$station_num = '';
			$station_num = count($value);
			if($station_num)
			{
				$up_sql = "UPDATE ".DB_PREFIX."company SET station_count = station_count + " . $station_num . " WHERE id = " . $key;
				$this->db->query($up_sql);
			}
		}
		/*if($val)
		{
			$sql .= $val; 
			$sql = rtrim($sql,',');
			$this->db->query($sql);
		}*/
	}
}
include(ROOT_PATH . 'excute.php');