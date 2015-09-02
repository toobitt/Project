<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
require_once ROOT_PATH . 'lib/class/material.class.php';
require_once CUR_CONF_PATH.'core/pub.class.php';
class forcast extends InitFrm
{
	private $curl;
	public function __construct()
	{	
		parent::__construct();
		$this->material = new material();
		$this->pubWeather = new common_Weather();			
	}
	public function __destruct()
	{
		parent::__destruct();
	}
	//中国天气网天气更新
	function cn_com_weather_update($city= array(), $weather_source_id = 0, $userinfo=array(),$update=true)
	{
		$weather_info = array();
		if(!$weather_source_id || empty($city))
		{
			return $weather_info;
		}
		$sql = 'SELECT weather_api_url host,weather_api_dir dir FROM '.DB_PREFIX.'weather_source WHERE id = '.intval($weather_source_id);
		$curl_parameters = $this->db->query_first($sql);
		$this->curl = new curl($curl_parameters['host'], $curl_parameters['dir']);
		$this->curl->initPostData();
		$this->curl->setSubmitType('get');
		$sql = 'SELECT id, code FROM '.DB_PREFIX.'weather_city_source WHERE source_id = '.intval($weather_source_id).' AND id in ('.implode(',', $city).')';
		$query = $this->db->query($sql);
		$codes = array();//城市代码
		while($city = $this->db->fetch_array($query))
		{
			$codes[$city['id']] = $city['code'];
			$file = $city['code'] . '.html';
			//效率存在问题 循环请求接口
			$weather_info[$city['id']] = $this->curl->request($file);
		}
		$weather_zs = array();
		//获取天气网指数http://m.weather.com.cn/zs/101010100.html
		if (!empty($codes))
		{
			if ($this->settings['weather_zs'])
			{
				$this->curl = new curl($this->settings['weather_zs']['host'], $this->settings['weather_zs']['dir']);
				$this->curl->initPostData();
				$this->curl->setSubmitType('get');
				foreach ($codes as $key=>$code)
				{
					$filename = $code.'.html';
					$weather_zs[$key] = $this->curl->request($filename);
				}
			}
		}
		$return = array();
		if($weather_info)
		{
			foreach ($weather_info as $city_id=>$weathers)
			{
				
				$weather = $weathers['weatherinfo'];
				$extra = array(
					'index_zwx'=>$weather['index_uv'], //紫外线指数
					'index_cy'			=> array(
												'name' => $weather_zs[$city_id]['zs']['ct_name'],
												'hint' => $weather_zs[$city_id]['zs']['ct_hint'],
												'des'  => $weather_zs[$city_id]['zs']['ct_des'],
											),
					'index_ac'			=> array(
												'name' => $weather_zs[$city_id]['zs']['ac_name'],
												'hint' => $weather_zs[$city_id]['zs']['ac_hint'],
												'des'  => $weather_zs[$city_id]['zs']['ac_des'],
											),
					'index_ag'			=> array(
												'name' => $weather_zs[$city_id]['zs']['ag_name'],
												'hint' => $weather_zs[$city_id]['zs']['ag_hint'],
												'des'  => $weather_zs[$city_id]['zs']['ag_des'],
											),
					'index_be'			=> array(
												'name' => $weather_zs[$city_id]['zs']['be_name'],
												'hint' => $weather_zs[$city_id]['zs']['be_hint'],
												'des'  => $weather_zs[$city_id]['zs']['be_des'],
												'img'  => '',
											),
					'index_cl'			=> array(
												'name' => $weather_zs[$city_id]['zs']['cl_name'],
												'hint' => $weather_zs[$city_id]['zs']['cl_hint'],
												'des'  => $weather_zs[$city_id]['zs']['cl_des'],
											),
					'index_co'			=> array(
												'name' => $weather_zs[$city_id]['zs']['co_name'],
												'hint' => $weather_zs[$city_id]['zs']['co_hint'],
												'des'  => $weather_zs[$city_id]['zs']['co_des'],
											),
					'index_dy'			=> array(
												'name' => $weather_zs[$city_id]['zs']['dy_name'],
												'hint' => $weather_zs[$city_id]['zs']['dy_hint'],
												'des'  => $weather_zs[$city_id]['zs']['dy_des'],
											),
					'index_fs'			=> array(
												'name' => $weather_zs[$city_id]['zs']['fs_name'],
												'hint' => $weather_zs[$city_id]['zs']['fs_hint'],
												'des'  => $weather_zs[$city_id]['zs']['fs_des'],
											),
					'index_gj'			=> array(
												'name' => $weather_zs[$city_id]['zs']['gj_name'],
												'hint' => $weather_zs[$city_id]['zs']['gj_hint'],
												'des'  => $weather_zs[$city_id]['zs']['gj_des'],
											),
					'index_gm'			=> array(
												'name' => $weather_zs[$city_id]['zs']['gm_name'],
												'hint' => $weather_zs[$city_id]['zs']['gm_hint'],
												'des'  => $weather_zs[$city_id]['zs']['gm_des'],
											),
					'index_gz'			=> array(
												'name' => $weather_zs[$city_id]['zs']['gz_name'],
												'hint' => $weather_zs[$city_id]['zs']['gz_hint'],
												'des'  => $weather_zs[$city_id]['zs']['gz_des'],
											),
					'index_hc'			=> array(
												'name' => $weather_zs[$city_id]['zs']['hc_name'],
												'hint' => $weather_zs[$city_id]['zs']['hc_hint'],
												'des'  => $weather_zs[$city_id]['zs']['hc_des'],
											),
					'index_jt'			=> array(
												'name' => $weather_zs[$city_id]['zs']['jt_name'],
												'hint' => $weather_zs[$city_id]['zs']['jt_hint'],
												'des'  => $weather_zs[$city_id]['zs']['jt_des'],
											),
					'index_lk'			=> array(
												'name' => $weather_zs[$city_id]['zs']['lk_name'],
												'hint' => $weather_zs[$city_id]['zs']['lk_hint'],
												'des'  => $weather_zs[$city_id]['zs']['lk_des'],
											),
					'index_ls'			=> array(
												'name' => $weather_zs[$city_id]['zs']['ls_name'],
												'hint' => $weather_zs[$city_id]['zs']['ls_hint'],
												'des'  => $weather_zs[$city_id]['zs']['ls_des'],
											),
					'index_mf'			=> array(
												'name' => $weather_zs[$city_id]['zs']['mf_name'],
												'hint' => $weather_zs[$city_id]['zs']['mf_hint'],
												'des'  => $weather_zs[$city_id]['zs']['mf_des'],
											),
					'index_nl'			=> array(
												'name' => $weather_zs[$city_id]['zs']['nl_name'],
												'hint' => $weather_zs[$city_id]['zs']['nl_hint'],
												'des'  => $weather_zs[$city_id]['zs']['nl_des'],
											),
					'index_pj'			=> array(
												'name' => $weather_zs[$city_id]['zs']['pj_name'],
												'hint' => $weather_zs[$city_id]['zs']['pj_hint'],
												'des'  => $weather_zs[$city_id]['zs']['pj_des'],
											),
					'index_pk'			=> array(
												'name' => $weather_zs[$city_id]['zs']['pk_name'],
												'hint' => $weather_zs[$city_id]['zs']['pk_hint'],
												'des'  => $weather_zs[$city_id]['zs']['pk_des'],
											),
					'index_pl'			=> array(
												'name' => $weather_zs[$city_id]['zs']['pl_name'],
												'hint' => $weather_zs[$city_id]['zs']['pl_hint'],
												'des'  => $weather_zs[$city_id]['zs']['pl_des'],
											),
					'index_pp'			=> array(
												'name' => $weather_zs[$city_id]['zs']['pp_name'],
												'hint' => $weather_zs[$city_id]['zs']['pp_hint'],
												'des'  => $weather_zs[$city_id]['zs']['pp_des'],
											),
					'index_sg'			=> array(
												'name' => $weather_zs[$city_id]['zs']['sg_name'],
												'hint' => $weather_zs[$city_id]['zs']['sg_hint'],
												'des'  => $weather_zs[$city_id]['zs']['sg_des'],
											),
					'index_tr'			=> array(
												'name' => $weather_zs[$city_id]['zs']['tr_name'],
												'hint' => $weather_zs[$city_id]['zs']['tr_hint'],
												'des'  => $weather_zs[$city_id]['zs']['tr_des'],
											),
					'index_uv'			=> array(
												'name' => $weather_zs[$city_id]['zs']['uv_name'],
												'hint' => $weather_zs[$city_id]['zs']['uv_hint'],
												'des'  => $weather_zs[$city_id]['zs']['uv_des'],
											),
					'index_xc'			=> array(
												'name' => $weather_zs[$city_id]['zs']['xc_name'],
												'hint' => $weather_zs[$city_id]['zs']['xc_hint'],
												'des'  => $weather_zs[$city_id]['zs']['xc_des'],
											),
					'index_xq'			=> array(
												'name' => $weather_zs[$city_id]['zs']['xq_name'],
												'hint' => $weather_zs[$city_id]['zs']['xq_hint'],
												'des'  => $weather_zs[$city_id]['zs']['xq_des'],
											),
					'index_yd'			=> array(
												'name' => $weather_zs[$city_id]['zs']['yd_name'],
												'hint' => $weather_zs[$city_id]['zs']['yd_hint'],
												'des'  => $weather_zs[$city_id]['zs']['yd_des'],
											),
					'index_yh'			=> array(
												'name' => $weather_zs[$city_id]['zs']['yh_name'],
												'hint' => $weather_zs[$city_id]['zs']['yh_hint'],
												'des'  => $weather_zs[$city_id]['zs']['yh_des'],
											),
					'index_ys'			=> array(
												'name' => $weather_zs[$city_id]['zs']['ys_name'],
												'hint' => $weather_zs[$city_id]['zs']['ys_hint'],
												'des'  => $weather_zs[$city_id]['zs']['ys_des'],
											),
					'index_zs'			=> array(
												'name' => $weather_zs[$city_id]['zs']['zs_name'],
												'hint' => $weather_zs[$city_id]['zs']['zs_hint'],
												'des'  => $weather_zs[$city_id]['zs']['zs_des'],
											),
				);
				/*
				$extra = array(
					'index48'=>$weather['index48'], //未来48小时穿衣指数
					'index48_d'=>$weather['index_d'], //未来48小时穿衣指数的建议
					'index'=>$weather_zs[$city_id]['zs']['ct_hint'],//穿衣指数
					'index_d'=>$weather_zs[$city_id]['zs']['ct_des'],//穿衣指数建议
					'index_ac'=>$weather_zs[$city_id]['zs']['ac_hint'],//空调开启指数
					'index_ac_d'=>$weather_zs[$city_id]['zs']['ac_des'],//空调开启指数建议
					'index_ag'=>$weather_zs[$city_id]['zs']['ag_hint'],//息斯敏过敏指数
					'index_ag_d'=>$weather_zs[$city_id]['zs']['ag_des'],//息斯敏过敏指数建议
					'index_be'=>$weather_zs[$city_id]['zs']['be_hint'],//海滨浴场
					'index_be_d'=>$weather_zs[$city_id]['zs']['be_des'],//海滨浴场建议
					'index_cl'=>$weather_zs[$city_id]['zs']['cl_hint'],//晨练指数
					'index_cl_d'=>$weather_zs[$city_id]['zs']['cl_des'],//晨练指数建议
					'index_co'=>$weather_zs[$city_id]['zs']['co_hint'],//舒适度指数
					'index_co_d'=>$weather_zs[$city_id]['zs']['co_des'],//舒适度指数建议
					'index_dy'=>$weather_zs[$city_id]['zs']['dy_hint'],//钓鱼指数
					'index_dy_d'=>$weather_zs[$city_id]['zs']['dy_des'],//钓鱼指数建议
					'index_fs'=>$weather_zs[$city_id]['zs']['fs_hint'],//防晒指数
					'index_fs_d'=>$weather_zs[$city_id]['zs']['fs_des'],//防晒指数建议
					'index_gj'=>$weather_zs[$city_id]['zs']['gj_hint'],//逛街指数
					'index_gj_d'=>$weather_zs[$city_id]['zs']['gj_des'],//逛街指数建议
					'index_gm'=>$weather_zs[$city_id]['zs']['gm_hint'],//感冒指数
					'index_gm_d'=>$weather_zs[$city_id]['zs']['gm_des'],//感冒指数建议
					'index_gz'=>$weather_zs[$city_id]['zs']['gz_hint'],//干燥指数
					'index_gz_d'=>$weather_zs[$city_id]['zs']['gz_des'],//干燥指数建议
					'index_hc'=>$weather_zs[$city_id]['zs']['hc_hint'],//划船指数
					'index_hc_d'=>$weather_zs[$city_id]['zs']['hc_des'],//划船指数建议
					'index_jt'=>$weather_zs[$city_id]['zs']['jt_hint'],//交通指数
					'index_jt_d'=>$weather_zs[$city_id]['zs']['jt_des'],//交通指数建议
					'index_lk'=>$weather_zs[$city_id]['zs']['lk_hint'],//路况指数
					'index_lk_d'=>$weather_zs[$city_id]['zs']['lk_des'],//路况指数建议
					'index_ls'=>$weather_zs[$city_id]['zs']['ls_hint'],//晾晒指数
					'index_ls_d'=>$weather_zs[$city_id]['zs']['ls_des'],//晾晒指数建议
					'index_mf'=>$weather_zs[$city_id]['zs']['mf_hint'],//美发指数
					'index_mf_d'=>$weather_zs[$city_id]['zs']['mf_des'],//美发指数建议
					'index_nl'=>$weather_zs[$city_id]['zs']['nl_hint'],//夜生活指数
					'index_nl_d'=>$weather_zs[$city_id]['zs']['nl_des'],//夜生活指数建议
					'index_pj'=>$weather_zs[$city_id]['zs']['pj_hint'],//啤酒指数
					'index_pj_d'=>$weather_zs[$city_id]['zs']['pj_des'],//啤酒指数建议
					'index_pk'=>$weather_zs[$city_id]['zs']['pk_hint'],//放风筝指数
					'index_pk_d'=>$weather_zs[$city_id]['zs']['pk_des'],//放风筝指数建议
					'index_pl'=>$weather_zs[$city_id]['zs']['pl_hint'],//空气污染扩散条件指数
					'index_pl_d'=>$weather_zs[$city_id]['zs']['pl_des'],//空气污染扩散条件指数建议
					'index_pp'=>$weather_zs[$city_id]['zs']['pp_hint'],//化妆指数
					'index_pp_d'=>$weather_zs[$city_id]['zs']['pp_des'],//化妆指数建议
					'index_sg'=>$weather_zs[$city_id]['zs']['sg_hint'],//一句话提示指数
					'index_sg_d'=>$weather_zs[$city_id]['zs']['sg_des'],//一句话提示指数建议
					'index_tr'=>$weather_zs[$city_id]['zs']['tr_hint'],//旅游指数
					'index_tr_d'=>$weather_zs[$city_id]['zs']['tr_des'],//旅游指数建议
					'index_uv'=>$weather_zs[$city_id]['zs']['uv_hint'],//紫外线强度指数
					'index_uv_d'=>$weather_zs[$city_id]['zs']['uv_des'],//紫外线强度指数建议
					'index_xc'=>$weather_zs[$city_id]['zs']['xc_hint'],//洗车指数
					'index_xc_d'=>$weather_zs[$city_id]['zs']['xc_des'],//洗车指数建议
					'index_xq'=>$weather_zs[$city_id]['zs']['xq_hint'],//心情指数
					'index_xq_d'=>$weather_zs[$city_id]['zs']['xq_des'],//心情指数建议
					'index_yd'=>$weather_zs[$city_id]['zs']['yd_hint'],//运动指数
					'index_yd_d'=>$weather_zs[$city_id]['zs']['yd_des'],//运动指数建议
					'index_yh'=>$weather_zs[$city_id]['zs']['yh_hint'],//约会指数
					'index_yh_d'=>$weather_zs[$city_id]['zs']['yh_des'],//约会指数建议
					'index_ys'=>$weather_zs[$city_id]['zs']['ys_hint'],//雨伞指数
					'index_ys_d'=>$weather_zs[$city_id]['zs']['ys_des'],//雨伞指数建议
					'index_zs'=>$weather_zs[$city_id]['zs']['zs_hint'],//中暑指数
					'index_zs_d'=>$weather_zs[$city_id]['zs']['zs_des'],//中暑指数建议
					'index48_uv'=>$weather['index48_uv'],//未来48小时紫外线指数
				);
				
					'index_xc'=>$weather['index_xc'],//洗车指数
					'index_uv'=>$weather['index_uv'], //紫外线指数
					'index_tr'=>$weather['index_tr'],//旅游指数
					'index_co'=>$weather['index_co'],//舒适度指数
					'index_ls'=>$weather['index_ls'],//晾晒指数
					'index_ag'=>$weather['index_ag'],//息斯敏过敏气象指数
					'index_cl'=>$weather['index_cl'],//晨练指数
					'index'=>$weather['index'], //穿衣指数
					'index_d'=>$weather['index_d'],  //穿衣指数建议
				*/
				//图片上传图片服务器
				$img_id = '';
				$img_id_1 = '';
				$img_id_2 = '';
				$img_id_1 = $this->pubWeather->get_system_material_id($weather['img1'],$weather['img_title1'],intval($weather_source_id),$userinfo);
				//天气网接口如果第二个图片的id为99,则表明一天天气只有一种
				if ($weather['img2']!='99')
				{
					$img_id_2 = $this->pubWeather->get_system_material_id($weather['img2'],$weather['img_title2'],intval($weather_source_id),$userinfo);
				}
				if ($img_id_2){
					$img_id = $img_id_1.','.$img_id_2;
				}else {
					$img_id = $img_id_1;
				}
				//返回数据整理
				//数据处理
				$data = array(
				'id'=>$city_id,
				'source_id'=>intval($weather_source_id),
				'w_date'=>date('Y-m-d',TIMENOW),
				'img_id'=>$img_id,
				'weather_report'=>$weather['weather1'],
				'temperature'=>$weather['temp1'],
				'wind_direction'=>$weather['fx1'],
				'wind_level'=>$weather['wind1'],
				'extra'=>addslashes(serialize($extra)),
				'user_id'=>$userinfo['user_id'],
				'user_name'=>$userinfo['user_name'],
				'ip'=>hg_getip(),
				);		
				$ret = $this->pubWeather->storedIntoDB($data, 'weather_information');
				$ret = $this->user_buffer($data);
			}
		}
		return true;
	}
	//更新用户缓冲表,今日天气
	private  function user_buffer($data)
	{
		if (!is_array($data) || empty($data))
		{
			return false;	
		}	
		//将天气网采集信息转换为一维数组
		if (!empty($data['extra']))
		{
			$data['extra'] = unserialize(stripslashes($data['extra']));
			$data = array_merge($data,$data['extra']);
			foreach ($data as $key=>$val)
			{
				if ($key!='extra')
				{
					$k[$key] = $val;
				}
			}
		}
		$data = $k;
		unset($k);
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE source_id != 0';
		$query = $this->db->query($sql);
		$field = array();
		while($row = $this->db->fetch_array($query))
		{
			$field[$row['user_field']] = $row['source_field'];
		}
		//纪录被序列化的字段
		$serialize = array();
		if (!empty($field))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'weather_user_buffer SET ';
			$sql .= 'id = "'.$data['id'].
					'",source_id = "'.$data['source_id'].
					'",w_date = "'.$data['w_date'].'",';			
			foreach ($field as $key=>$val)
			{
				$data[$val] = $data[$val] ? $data[$val] : '';
				if (is_array($data[$val]))
				{
					$data[$val] = serialize($data[$val]);
					$serialize[] =  $key;
				}
				$sql .= $key.'="'.addslashes($data[$val]).'",';
			}
			$sql = rtrim($sql,',');
			$this->db->query($sql);
		}
		//检查是否有额外的自定义参数
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_define WHERE source_id = 0 AND city_id = '.$data['id'];
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['start_time']<TIMENOW && $row['end_time']>TIMENOW)
			{
				$k[$row['user_field']] = $row['user_data'];
			}
		
		}
		if (!empty($k))
		{
			$sql = 'UPDATE '.DB_PREFIX.'weather_user_buffer SET ';
			foreach ($k as $key=>$val)
			{
				$sql .= $key.'="'.$val.'",';
			}
			$sql = rtrim($sql,',');
			$sql .= ' WHERE id = '.$data['id'];
			$this->db->query($sql);
		}
		//更新天气表
		$sql = 'SELECT * FROM '.DB_PREFIX.'weather_user_buffer WHERE id ='.$data['id'];
		$res = $this->db->query_first($sql);
		if ($res)
		{
			foreach ($res as $key=>$val)
			{
				if (in_array($key, $serialize))
				{
					$res[$key] = unserialize($val);
				}
			}
			$sql  = 'SELECT name FROM '.DB_PREFIX.'weather_city WHERE id = '.$res['id'];
			$q = $this->db->query_first($sql);
			$res['name']  = $q['name'];
			$res['update_time'] = TIMENOW;
			$sql = 'SELECT id FROM '.DB_PREFIX.'weather WHERE id = '.$data['id'];
			$ret = $this->db->query_first($sql);
			if ($ret['id'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'weather SET one = "'.addslashes(serialize($res)).'",update_time='.TIMENOW.' WHERE id = '.$ret['id'];
			}else {
				$sql = 'REPLACE INTO '.DB_PREFIX.'weather SET one = "'.addslashes(serialize($res)).'",id ='.$data['id'].',update_time='.TIMENOW.',order_id = '.$data['id'];
			}		
			$this->db->query($sql);		
		}
		return true;		
	}	
}