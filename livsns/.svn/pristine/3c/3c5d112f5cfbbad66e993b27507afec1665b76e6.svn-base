<?php
require_once(ROOT_PATH . 'lib/class/material.class.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/member.class.php');
class ClassLBS extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->material = new material();
		$this->member = new member();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	public function show($condition, $orderby, $offset, $count, $myLng = '', $myLat = '', $distance = 0,$need_brief='')
	{
		$limit = " limit {$offset}, {$count}";
		if ($myLng && $myLat)
		{
			/*
			$range = 180 / pi() * $distance / 6372.797; //里面的 $distance 就代表搜索 $distance 之内，单位km  
			$lngR = $range / cos($myLat * pi() / 180);  
			//echo $range;exit()
			$maxLat = $myLat + $range;//最大纬度  
			$minLat = $myLat - $range;//最小纬度  
			$maxLng = $myLng + $lngR;//最大经度  
			$minLng = $myLng - $lngR;//最小经度
			
			$sql = 'SELECT lbs.*,ABS(ABS(lbs.GPS_longitude)-ABS('.$myLng.')) AS Lng, ABS(ABS(lbs.GPS_latitude)-ABS('.$myLat.')) AS Lat, s.name AS sort_name,p.name AS province,city.city AS city, a.area AS area,
					m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth
					FROM '.DB_PREFIX.'lbs  lbs 
					LEFT JOIN '.DB_PREFIX.'province p ON lbs.province_id = p.id
					LEFT JOIN '.DB_PREFIX.'city city ON lbs.city_id = city.id
					LEFT JOIN '.DB_PREFIX.'area a ON lbs.area_id = a.id
					LEFT JOIN '.DB_PREFIX.'sort s ON lbs.sort_id = s.id
					LEFT JOIN '.DB_PREFIX.'materials m ON lbs.indexpic = m.id
					WHERE lbs.GPS_longitude >='.$minLng.' AND lbs.GPS_longitude <='.$maxLng
					.' AND lbs.GPS_latitude >='.$minLat.' AND lbs.GPS_latitude <= '.$maxLat
					.' AND lbs.GPS_longitude != 0.00000000000000 AND lbs.GPS_latitude != 0.00000000000000 '
					. $condition.' ORDER BY Lng, Lat ASC,order_id DESC '.$limit; 
			*/
			
			$sql = 'SELECT lbs.*,ABS(ABS(lbs.GPS_longitude)-ABS('.$myLng.')) AS Lng, ABS(ABS(lbs.GPS_latitude)-ABS('.$myLat.')) AS Lat, s.name AS sort_name,p.name AS province,city.city AS city, a.area AS area,
					m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth
					FROM '.DB_PREFIX.'lbs  lbs 
					LEFT JOIN '.DB_PREFIX.'province p ON lbs.province_id = p.id
					LEFT JOIN '.DB_PREFIX.'city city ON lbs.city_id = city.id
					LEFT JOIN '.DB_PREFIX.'area a ON lbs.area_id = a.id
					LEFT JOIN '.DB_PREFIX.'sort s ON lbs.sort_id = s.id
					LEFT JOIN '.DB_PREFIX.'materials m ON lbs.indexpic = m.id 
					WHERE 1 '. $condition.' ORDER BY Lng, Lat ASC,order_id DESC';
			//echo $sql;exit();
		}
		else
		{
			$fields = ' lbs.*,s.name AS sort_name,p.name AS province,city.city AS city, a.area AS area,m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth';
			
			if($need_brief)
			{
				$fields .= ',c.content as brief';
			}
			$sql = 'SELECT ' . $fields . ' FROM '.DB_PREFIX.'lbs  lbs 
					LEFT JOIN '.DB_PREFIX.'province p ON lbs.province_id = p.id
					LEFT JOIN '.DB_PREFIX.'city city ON lbs.city_id = city.id
					LEFT JOIN '.DB_PREFIX.'area a ON lbs.area_id = a.id
					LEFT JOIN '.DB_PREFIX.'sort s ON lbs.sort_id = s.id
					LEFT JOIN '.DB_PREFIX.'materials m ON lbs.indexpic = m.id ';
			
			if($need_brief)
			{
				$sql .= ' LEFT JOIN ' . DB_PREFIX . 'lbs_content c ON lbs.id = c.id';
			}
		
			$sql .= ' WHERE 1 '. $condition.$orderby;
		}
		
		
		//缓存处理
		$cache_tag = false;
		$cache_time = $this->settings['cache_time'];
		if($myLng && $myLat && $cache_time)
		{
			$md5_sql = md5($sql);
			$cache_sql = '';
			$cache_sql = "SELECT create_time FROM " . DB_PREFIX . "lbs_cache WHERE md5_sql = '" . $md5_sql . "' ORDER BY create_time DESC LIMIT 0,1";
			$res = $this->db->query_first($cache_sql);
			
			if($res['create_time'] && ((TIMENOW - $res['create_time']) <= ($cache_time*60)))
			{
				$data_sql = '';
				
				$data_sql = "SELECT content FROM " . DB_PREFIX . "lbs_cache WHERE md5_sql = '" . $md5_sql . "' ORDER BY distance ASC " . $limit;
				$q = $this->db->query($data_sql);
				
				$data = array();
				while ($r = $this->db->fetch_array($q))
				{
					$data[] = unserialize($r['content']);
				}
				
				return $data;
				
			}
			else
			{
				$del_sql = '';
				$del_time = TIMENOW - ($cache_time*60);
				$del_sql = "DELETE FROM " . DB_PREFIX . "lbs_cache WHERE create_time < " . $del_time;
				$this->db->query($del_sql);
			}
			
			$cache_tag = true;
		}
		
		
		//不需要缓存，正常查询
		if(!$cache_tag)		
		{
			$sql .= $limit;
		}
		else 
		{
			$sql .=" limit 0, {$this->settings['data_count']}";
		}
		
		//echo $sql;exit();		
		$query = $this->db->query($sql);
		$res = array();
		while ($row = $this->db->fetch_array($query))
		{
			$region = '';
			if ($row['province'])
			{
				$region .= $row['province'];
				if ($row['city'])
				{
					$region .= '-'.$row['city'];
					if ($row['area'])
					{
						$region .= '-'.$row['area'];
					}
				}
			}
			$row['stime'] = $row['stime'] ? @date('H:i', $row['stime']) : '';
			$row['etime'] = $row['etime'] ? @date('H:i', $row['etime']) : '';
			$row['status_name'] = $this->settings['lbs_status'][$row['status']];
			$row['status_name']  = $row['status_name'] ? $row['status_name'] : '未审核';
			$row['format_create_time'] = @date('Y-m-d H:i:s', $row['create_time']);
			$row['region'] = $region;
			$row['img_info'] = '';		
			if ($row['host'] && $row['dir'] && $row['filepath'] && $row['filename'])
			{
				$row['img_info'] = array(
					'id'		=> $row['mid'],
					'host'		=> $row['host'],
					'dir'		=> $row['dir'],
					'filepath'	=> $row['filepath'],
					'filename'	=> $row['filename'],
					'imgheight'	=> $row['imgheight'],
					'imgwidth'	=> $row['imgwidth'],
				);
			}
			if ($row['tel'])
			{
				$row['tel'] = unserialize($row['tel']) ? unserialize($row['tel']) : '';
			}
			if ($myLng && $myLat && $row['GPS_latitude'] != 0.00000000000000 && $row['GPS_longitude'] != 0.00000000000000)
			{
				$row['distance'] = $this->GetDistance($row['GPS_latitude'], $row['GPS_longitude'], $myLat, $myLng, 1,1);
				
				$row['distance_m'] = $row['distance'];
				if($row['distance'] > 1000)
				{
					$row['distance'] /= 1000;
					
					$row['distance'] = round($row['distance'],1);
					$row['distance'] .= 'km'; 
				}
				else 
				{
					$row['distance'] .= 'm';
				}
			}
			else 
			{
				if($cache_tag)
				{
					continue;
				}
				$row['distance'] = '距离不祥';
			}
			$res[$row['id']] = $row;
		}
		
		if($cache_tag && $md5_sql && $res)
		{
			foreach ($res as $k => $v)
			{
				$sql = '';
				$val = array();
			
				$val = array(
					'md5_sql'			=> $md5_sql,
					'content'			=> serialize($v),
					'distance'			=> $v['distance_m'] ? $v['distance_m'] : 0,
					'create_time'		=> TIMENOW,
				);
				
				
				$sql="INSERT INTO " . DB_PREFIX . "lbs_cache SET ";		
				if(is_array($val))
				{
					$sql_extra = $space = ' ';
					foreach($val as $kk => $vv)
					{
						$sql_extra .=$space . $kk . "='" . $vv . "'";
						$space=',';
					}
					$sql .=$sql_extra;
				}
		
				$this->db->query($sql);
			}
			
			$data_sql = '';
			$data_sql = "SELECT content FROM " . DB_PREFIX . "lbs_cache WHERE md5_sql = '" . $md5_sql . "' ORDER BY distance ASC " . $limit;
			$q = $this->db->query($data_sql);
			
			$data = array();
			while ($r = $this->db->fetch_array($q))
			{
				$data[] = unserialize($r['content']);
			}
			return $data;
			
		}
		return $res;
		
	}
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'lbs lbs WHERE 1'.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	public function add_lbs($data)
	{
		if (empty($data) || !is_array($data))
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'lbs SET ';
		foreach ($data as $key=>$val)
		{
			$sql .=  $key.'=\''.addslashes($val).'\',';
		}
		$sql = rtrim($sql,',');
		
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$sql = 'UPDATE '.DB_PREFIX.'lbs set order_id = '.$id.' WHERE id = '.$id;
		$this->db->query($sql);
		$data['id'] = $id;
		return $data;
	}
	
	public function add_content($content, $id)
	{
		if (!$id)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'lbs_content (id, content) VALUES ('.$id.',"'.addslashes(html_entity_decode($content,ENT_QUOTES)).'")';
		$this->db->query($sql);
		return $content;
	}
	
	/**
	 * 
	 * @Description: 上传图片服务器 
	 * @author Kin
	 * @date 2013-4-13 下午04:04:52
	 */
	public function uploadToPicServer($file,$content_id)
	{
		$material = $this->material->addMaterial($file,$content_id); //插入图片服务器
		return $material;
	}
	
	public function insert_img($data = array())
	{
		if(!$data)
		{
			return false;
		}
		$sql = " INSERT INTO " . DB_PREFIX . "materials SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$vid = $this->db->insert_id();
		return $vid;
	}
	
	/**
     * 
     * @Description: 单图片上传入库
     * @author Kin
     * @date 2013-4-13 下午04:09:39
     */
	public function upload_pic($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if ($id)
		{
			$ret = array(
				'id'		=> $id,
				'host'		=> $data['host'],
				'dir' 		=> $data['dir'],
				'filepath'	=> $data['filepath'],
				'filename'	=> $data['filename'],
				'imgheight'	=> $data['imgheight'],
				'imgwidth'	=> $data['imgwidth'],
			);
			return $ret;
		}
		else
		{
			return false;
		} 
		
	}
	
	/**
     * 
     * @Description: 单视频上传入库
     * @author Kin
     * @date 2013-4-13 下午04:09:39
     */
	public function upload_vod($data)
	{
		if (!is_array($data) || !$data)
		{
			return false;
		}
		$sql = 'INSERT INTO '.DB_PREFIX.'materials SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$id = $this->db->insert_id();
		if ($id)
		{
			$ret = array(
				'host'		=> $data['host'],
				'dir'		=> $data['dir'],
				'filepath'	=> $data['filepath'],
				'filename'	=> $data['filename'],
				'vodid'		=> $data['original_id'],
			);
			return $ret;
		}
		else 
		{
			return false;
		}
	}
	/**
	 * 
	 * @Description 视频上传
	 * @author Kin
	 * @date 2013-4-13 下午04:34:29
	 */
	public function uploadToVideoServer($file,$title='',$brief = '',$vod_lexing = 1)
	{
		$curl = new curl($this->settings['App_mediaserver']['host'],$this->settings['App_mediaserver']['dir'] . 'admin/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addFile($file);
		$curl->addRequestData('title', $title);
		$curl->addRequestData('comment',$brief);
		$curl->addRequestData('vod_leixing',$vod_lexing);//网页传的视频类型是1，手机传的视频是2
		$curl->addRequestData('app_uniqueid',APP_UNIQUEID);
		$curl->addRequestData('mod_uniqueid',MOD_UNIQUEID);
		$ret = $curl->request('create.php');
		return $ret[0];
	}
	
	public function update_status($status, $id)
	{
		if (!is_array($status) || !$id)
		{
			return false;
		}
		$sql = 'UPDATE '.DB_PREFIX.'lbs SET ';
		foreach ($status as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$this->db->query($sql);
		return $status;
	}
	
	public function detail($id, $myLng = '', $myLat = '')
	{
		$sql = 'SELECT lbs.*,s.name AS sort_name, c.content, 
				p.name AS province,city.city AS city, a.area AS area,
				m.id AS mid, m.host, m.dir, m.filepath, m.filename, m.imgheight, m.imgwidth 
				FROM '.DB_PREFIX.'lbs  lbs 
				LEFT JOIN '.DB_PREFIX.'lbs_content c ON lbs.id = c.id
				LEFT JOIN '.DB_PREFIX.'province p ON lbs.province_id = p.id
				LEFT JOIN '.DB_PREFIX.'city city ON lbs.city_id = city.id
				LEFT JOIN '.DB_PREFIX.'area a ON lbs.area_id = a.id
				LEFT JOIN '.DB_PREFIX.'sort s ON lbs.sort_id = s.id
				LEFT JOIN '.DB_PREFIX.'materials m ON lbs.indexpic = m.id
				WHERE lbs.id = '. $id;
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			return '';
		}
		
		
		$ret['tel'] = ($ret['tel'] ? @unserialize($ret['tel']) : '') ? @unserialize($ret['tel']) : '';
		$ret['stime'] = $ret['stime'] ? @date('H:i', $ret['stime']) : '';
		$ret['etime'] = $ret['etime'] ? @date('H:i', $ret['etime']) : '';
		$ret['status_name'] = $this->settings['lbs_status'][$ret['status']];
		$ret['status_name']  = $ret['status_name'] ? $ret['status_name'] : '未审核';
		
		//数据类型判断
		if($this->settings['bicycle_sort_id'] && $ret['sort_id'] && $this->settings['bicycle_sort_id'] == $ret['sort_id'])
		{
			$ret['station_type'] = 1;
			if($ret['company_id'])
			{
				$sql = "SELECT name FROM " . DB_PREFIX . "company WHERE id  = {$ret['company_id']}";
				$res = $this->db->query_first($sql);
				
				if($res['name'])
				{
					$ret['company_name'] = $res['name'];
				}
				else 
				{
					$ret['company_name'] = '';
				}
			}
		}
		else 
		{
			$ret['station_type'] = 0;
		}
		
		
		
		//营业时间判断
		if($ret['stime'] == '00:00' && $ret['etime'] == '23:59')
		{
			$ret['business_text'] = '24小时营业';
			$ret['business_status'] = 3;
		}
		else if($ret['stime'] && $ret['etime'])
		{
			$stime = strtotime($ret['stime']);
			$etime = strtotime($ret['etime']);
			
			
			if($stime < TIMENOW && $etime >TIMENOW)
			{
				$ret['business_text'] = '营业中';
				$ret['business_status'] = 1;
			}
			elseif ($etime < TIMENOW)
			{
				$ret['business_text'] = '营业结束';
				$ret['business_status'] = 2;
			}
		}
		else 
		{
			$ret['business_text'] = '营业时间未知';
			$ret['business_status'] = 0;
		}
		
		if ($ret['host'] && $ret['dir'] && $ret['filepath'] && $ret['filename'])
		{
			$ret['img_info'] = array(
				'id'		=> $ret['mid'],
				'host'		=> $ret['host'],
				'dir'		=> $ret['dir'],
				'filepath'	=> $ret['filepath'],
				'filename'	=> $ret['filename'],
				'imgwidth'	=> $ret['imgwidth'],
				'imgheight'	=> $ret['imgheight'],
			);
		}
		if ($myLng && $myLat)
		{
			$ret['distance'] = $this->GetDistance($ret['GPS_latitude'], $ret['GPS_longitude'], $myLat, $myLng, 1);
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
				$ret['distance'] = '距离不祥';
			}
		}
		unset($ret['mid']);
		unset($ret['host']);
		unset($ret['dir']);
		unset($ret['filepath']);
		unset($ret['filename']);
		unset($ret['imgwidth']);
		unset($ret['imgheight']);
		//取所有的素材
		$sql = 'SELECT * FROM ' .DB_PREFIX. 'materials WHERE module=0 AND cid = '.$id;
		$query = $this->db->query($sql);
		$ret['images'] = '';
		$ret['video'] = '';
		$videoId = '';
		while ($row = $this->db->fetch_array($query))
		{
			if ($row['mark'] == 'video')
			{
				$videoId = $row['original_id'];
			}
			if ($row['mark'] == 'img')
			{
				$ret['images'][] = array(
					'id'=>$row['id'],
					'host'=>$row['host'],
					'dir'=>$row['dir'],
					'filepath'=>$row['filepath'],
					'filename'=>$row['filename'],
					'imgwidth'=>$row['imgwidth'],
					'imgheight'=>$row['imgheight'],
				);
			}
		}
		if ($videoId)
		{
			$videos = $this->get_video($videoId);
			$ret['video'] = $videos[$videoId];
		}
		return $ret;
	}	
	
	/**
	 * 
	 * @Description  获取视频信息
	 * @author Kin
	 * @date 2013-6-18 上午09:12:55
	 */
	public function get_video($ids)
	{		
		if (!$this->settings['App_livmedia'] || !$ids)
		{
			return false;
		}
		$curl = new curl($this->settings['App_livmedia']['host'],$this->settings['App_livmedia']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_videos');
		$curl->addRequestData('id',$ids);
		$ret = $curl->request('vod.php');
		$ret = $ret[0];
		$vodInfor = array();
		if (is_array($ret) && !empty($ret))
		{
			$arr_id = explode(',', $ids);
			foreach ($arr_id as $val)
			{
				$vodInfor[$val]['url'] = $ret[$val]['video_url'];
				$arr = explode('.', $ret[$val]['video_filename']);
				$type = $arr[1];
				$m3u8 = $ret[$val]['hostwork'].'/'.$ret[$val]['video_path'].str_replace($type, 'm3u8', $ret[$val]['video_filename']);
				$img = $ret[$val]['img_info'] ? unserialize($ret[$val]['img_info']) : '';
				$vodInfor[$val]['img'] = $img;
				$vodInfor[$val]['m3u8'] = $m3u8;
				$vodInfor[$val]['vodid'] = $val;
				$vodInfor[$val]['duration'] = $ret[$val]['duration'];
				$vodInfor[$val]['totalsize'] = $ret[$val]['totalsize'];
				$vodInfor[$val]['is_audio'] = $ret[$val]['is_audio'];
			}
		}		
		return $vodInfor;
	}
	
	public function sort($id, $exclude_id)
	{
		if ($exclude_id)
		{
			$cond = ' AND id NOT IN (' . $exclude_id . ')';
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'sort WHERE fid=' . intval($id) . $cond .' ORDER BY order_id ASC';
		$query = $this->db->query($sql);
		$k = array();
		while(!false == ($row = $this->db->fetch_array($query)))
		{
			$k[] = array(
				'id'		=> $row['id'],
				'name'		=> $row['name'],
				'fid'		=> $row['fid'],
				'parents'	=> $row['parents'],
				'childs'	=> $row['childs'],
				'depath'	=> $row['depath'],
				'is_last'	=> $row['is_last'],
			);
		}
		return $k;
	}
	
	/**
	 * 
	 * @Description 审核操作
	 * @author Kin
	 * @date 2013-6-19 下午05:25:47
	 */
	public function audit($ids, $status)
	{
		$sql = 'UPDATE '.DB_PREFIX.'lbs SET status = '.$status.' WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		$ids = explode(',', $ids);
		$arr = array(
			'id'=>$ids,
			'status'=>$status,
		);
		return $arr;
	}
	
	public function delete($ids)
	{
		//求助信息表
		$sql = 'DELETE FROM '.DB_PREFIX.'lbs WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		//内容表
		$sql = 'DELETE FROM '.DB_PREFIX.'lbs_content WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
	public function update($id)
	{
		
	}
	
	
	
	//百度坐标转换为GPS坐标
	public function FromBaiduToGpsXY($x,$y)
	{
	    $Baidu_Server = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
	    $result = @file_get_contents($Baidu_Server);
	    $json = json_decode($result);  
	    if($json->error == 0)
	    {
	        $bx = base64_decode($json['x']);     
	        $by = base64_decode($json['y']);  
	        $GPS_x = 2 * $x - $bx;  
	        $GPS_y = 2 * $y - $by;
	        return array('GPS_x' => $GPS_x,'GPS_y' => $GPS_y);//经度,纬度
	    }
	    else
	    {
	    	return false;//转换失败
	    }
	}
	
	//GPS坐标转换为百度坐标
	public function FromGpsToBaiduXY($x,$y)
	{
		$url = BAIDU_CONVERT_DOMAIN . '&x='  . $x . '&y=' .$y;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response  = curl_exec($ch);
		curl_close($ch);//关闭
		$info = json_decode($response,1);
		if($info && !$info['error'])
		{
			unset($info['error']);
			$info['x'] = base64_decode($info['x']);
			$info['y'] = base64_decode($info['y']);
			return $info;
		}
	}
	//删除图片
	public function deleteMaterials($ids)
	{
		$sql = 'DELETE FROM ' . DB_PREFIX . 'materials WHERE id IN (' . $ids . ')';
		$this->db->query($sql);
		return $ids;
	}
	
	/**
	 * 
	 * @Description 更新索引图
	 * @author Kin
	 * @date 2013-4-16 上午09:32:46
	 */
	public function update_indexpic($mid,$cid)
	{
		$sql = 'UPDATE '.DB_PREFIX.'lbs SET indexpic = '.$mid.' WHERE id = '.$cid;		
		$this->db->query($sql);
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE id = '.$mid;
		$pic = $this->db->query_first($sql);
		if ($pic['host'] && $pic['dir'] && $pic['filepath'] && $pic['filename'])
		{
			$url = array(
					'id'			=> $mid,
					'host'			=> $pic['host'],
					'dir'			=> $pic['dir'],
					'filepath' 		=> $pic['filepath'],
					'filename' 		=> $pic['filename'],
					'imgwidth'		=> $pic['imgwidth'],
					'imgheight'		=> $pic['imgheight'],
					'cid'	=> $cid,
				);	
		}
		return $url;
	}
	//计算两点之间的距离(GPS坐标)
	function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2) 
	{
		$radLat1 = $lat1 * PI / 180.0; 
		$radLat2 = $lat2 * PI / 180.0;
		$a = $radLat1 - $radLat2; 
		$b = ($lng1 * PI / 180.0) - ($lng2 * PI / 180.0); 
		$s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2))); 
		$s = $s * EARTH_RADIUS; 
		$s = round($s * 1000); 
		if ($len_type > 1) 
		{ 
			$s /= 1000; 
		} 
		return round($s, $decimal); 
	}
	//验证是否是自己创建的LBS信息，只供外部接口使用
	public function checkSelfData($id, $user_id)
	{
		if (!$id || !$user_id)
		{
			return false;
		}
		$sql = 'SELECT user_id FROM '.DB_PREFIX.'lbs WHERE id = '.$id;
		$query = $this->db->query($sql);
		$arr = array();
		while ($row = $this->db->fetch_array($query))
		{
			$arr[] = $row;
		}
		if (!in_array($user_id, $arr))
		{
			return false;
		}
		return true;
	}
	
}