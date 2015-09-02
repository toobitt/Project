<?php
define('MOD_UNIQUEID','bicycle_station');
require_once ('./global.php');
require_once CUR_CONF_PATH.'lib/functions.php';
define('MOD_UNIQUEID','bicycle_station');
define('SCRIPT_NAME', 'stationDetail');
class stationDetail extends outerReadBase
{
		
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function detail(){}
	public function count(){}
	public function show()
	{
		$id = intval($this->input['station_id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$cond = " AND t1.id = ".$id;
		$sql = "SELECT t1.id, t1.name, t1.company_id, t1.station_id, t1.totalnum, t1.currentnum, t1.address, t1.brief, t1.dateline, t1.baidu_latitude, t1.baidu_longitude, t2.host, t2.dir, t2.filepath, t2.filename, t3.name company_name, t3.customer_hotline, t3.api_url, t3.brief as company_brief, t3.station_icon, t4.name as region_name FROM " . DB_PREFIX . "station t1 
			LEFT JOIN " .DB_PREFIX . "material t2 
				ON t1.material_id=t2.id 
			LEFT JOIN " . DB_PREFIX . "company t3 
				ON t1.company_id=t3.id 
			LEFT JOIN " . DB_PREFIX . "region t4
				ON t1.region_id = t4.id
			WHERE 1 " . $cond; 
		$ret = $this->db->query_first($sql);
		//hg_pre($ret,0);
		if(!$ret)
		{
			$this->errorOutput('NOCONTENT');
		}
		//查询站点下的实景照片数目
		$sql = "SELECT count(*) as total FROM ".DB_PREFIX."material WHERE cid = ".$id;
		$pho_total = $this->db->query_first($sql);
		
		//实景照片数目
		$ret['img_num'] = $pho_total['total'];
		
		//如果运营单位有更新数据接口，请求接口替换总数，可借自行车数量
		if($ret['api_url'])
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $ret['api_url']);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			if($response)
			{
				$response = json_decode($response,1);
				$info = $response['date'];
			}
			curl_close($ch);//关闭
			
			if($info)
			{
				foreach ($info as $k => $v)
				{
					if($ret['station_id'] == ltrim($v['stationid'],'0'))
					{
						$ret['dateline'] 	= $v['dateline'];
						$ret['totalnum'] 	= $v['totalnum'];
						$ret['currentnum'] 	= $v['currentnum'];
						break;
					}
				}
			}
		}
		unset($ret['api_url']);
		//可停车位
		if($ret['totalnum'] && $ret['totalnum']>=$ret['currentnum'])
		{
			$ret['park_num'] = $ret['totalnum'] - $ret['currentnum'];
			unset($ret['totalnum']);
		}
		else 
		{
			$ret['park_num'] = 0;
		}
		
		if($ret['host'] && $ret['dir'] && $ret['filepath'] && $ret['filename'])
		{
			//索引图
			$ret['indexpic'] = array(
					'host'		=>	$ret['host'],
					'dir'		=>	$ret['dir'],
					'filepath'	=>	$ret['filepath'],
					'filename'	=>	$ret['filename'],
			);		
		}
		else if($ret['station_icon'])
		{
			$icon = '';
			$icon = unserialize($ret['station_icon']);
			
			if(is_array($icon) && !empty($icon))
			{
				$ret['indexpic'] = array(
					'host'		=>	$icon['host'],
					'dir'		=>	$icon['dir'],
					'filepath'	=>	$icon['filepath'],
					'filename'	=>	$icon['filename'],
				);
			}
			else 
			{
				$ret['indexpic'] = array();
			}
		}
		else 
		{
			$ret['indexpic'] = array();
		}
		unset($ret['host'],$ret['dir'],$ret['filepath'],$ret['filename'],$ret['station_icon']);
		
		//数据更新时间
		if($ret['dateline'])
		{
			$ret['dateline'] = date('m-d H:i', $ret['dateline']);
		}
		else 
		{
			$ret['dateline'] = date('m-d H:i', TIMENOW);
		}
		
		
		if($ret['baidu_longitude'] && $ret['baidu_latitude'])
		{
			//ios传入gps坐标，转百度坐标
			if($this->input['jd'] && $this->input['wd'])
			{
				//gps坐标转百度坐标
				$baidu_zuobiao = GpsToBaidu($this->input['jd'],$this->input['wd']);
				$this->input['baidu_longitude'] = $baidu_zuobiao['x'];
				$this->input['baidu_latitude'] = $baidu_zuobiao['y'];
			}
			if($this->input['baidu_longitude'] || $this->input['baidu_latitude'])
			{
				//计算距离
				$ret['distance'] = GetDistance($ret['baidu_latitude'], $ret['baidu_longitude'], $this->input['baidu_latitude'], $this->input['baidu_longitude'], 1);
				if($ret['distance'])
				{
					if($ret['distance'] > 1000)
					{
						$ret['distance'] /= 1000;
						$ret['distance'] .= 'km'; 
					}
					else 
					{
						$ret['distance'] .= 'm';
					}
				}
				else 
				{
					$ret['distance'] = '距离不详';
				}
			}
		}
		if ($ret['baidu_latitude'] != '0.00000000000000')
		{
			$ret['latitude'] = $ret['baidu_latitude'];
		}
		if ($ret['baidu_longitude'] != '0.00000000000000')
		{
			$ret['longitude'] = $ret['baidu_longitude'];
		}
		unset($ret['baidu_longitude'],$ret['baidu_latitude']);
		
		$this->addItem($ret);
		$this->output();
	}	
}
include(ROOT_PATH . 'excute.php');
?>