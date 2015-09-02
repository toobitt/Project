<?php
require_once 'global.php';
require_once CUR_CONF_PATH.'lib/city.php';
require_once CUR_CONF_PATH.'lib/weather_forcast.class.php';
define('MOD_UNIQUEID','weatehr');//模块标识
class weatherApi extends outerReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->city = new city();
		$this->forcast = new forcastWeather();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function show()
	{
		$cityName = urldecode($this->input['name']);
		//对城市名称进行处理，有省，市的进行过滤
		if ($cityName)
		{
			$cityName = $this->city->filter($cityName);
			$condition = ' AND name="'.$cityName.'"';
		}	
		$days = $this->input['count'] && $this->input['count'] < 7 ? $this->input['count'] : 6;
		$sql = "SELECT img_id,img_title FROM ".DB_PREFIX.'weather_material_buffer WHERE 1';
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$img_id[$r['img_title']] = $r['img_id'];
		}
		//对城市名称进行查询，有返回天气信息
		$wether = array();
		$ret = $this->forcast->getWeather($cityName);
		if($ret && is_array($ret))
		{
			$wether['city'] = $ret[0]['name'];
			$wether['date'] = '';
			$wether['date_y'] = date('Y年m月d日',strtotime($ret[0]['w_date']));
			$wether['cityid'] = $ret[0]['cityid'];
			$wether['city_en'] = $ret[0]['city_en'];
			foreach ($ret as $k=>$v)
			{
				if($v)
				{
					$img_title = explode('转',$v['report']);
					$wether['fl'.($k+1)] = $v['fl'];
					$wether['fx'.($k+1)] = $v['fx'];
					$wether['img'.(2*$k+1)] = intval($img_id[$img_title[0]]);
					$wether['img'.(2*$k+2)] = $img_title[1] ? $img_id[$img_title[1]] : 99;
					$wether['img_title'.(2*$k+1)] = $img_title[0];
					$wether['img_title'.(2*$k+2)] = $img_title[1] ? $img_title[1] : $img_title[0];
					$wether['temp'.($k+1)] = $v['temp'];
					$wether['weather'.($k+1)] = $v['report'];
					$wether['wind'.($k+1)] = $v['fx'];
				}
			}
			$wether['index_uv'] = $ret[0]['uv']['hint'];
			$rets['weatherinfo'] = $wether;
			@file_put_contents(CUR_CONF_PATH.'/data/'.$wether['cityid'].'.html', json_encode($rets));
		}
		$this->addItem($rets);
		$this->output();
		
	}
	
	public function count()
	{
	
	}
	
	public function detail()
	{
		
	}
}
$ouput= new weatherApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}else {
	$action = $_INPUT['a'];
}
$ouput->$action();