<?php
define('MOD_UNIQUEID','lbs');//模块标识
require_once './global.php';
require_once(CUR_CONF_PATH.'lib/lbs.class.php');
require_once(CUR_CONF_PATH.'core/lbs.core.php');
class LBSUpdateApi extends outerUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->lbs_field = new lbs_field();
		$this->lbs = new ClassLBS();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$data = array(
			'title' 			=> trim($this->input['title']),
			'sort_id' 			=> intval($this->input['sort_id']),
			'province_id'		=> intval($this->input['province_id']),
			'city_id' 			=> intval($this->input['city_id']),
			'area_id'			=> intval($this->input['area_id']),
			'stime'				=> strtotime($this->input['stime']),
			'etime'				=> strtotime($this->input['etime']),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'address'			=> trim($this->input['address']),
			'GPS_longitude'		=> $this->input['GPS_longitude'],
			'GPS_latitude'		=> $this->input['GPS_latitude'],
			'create_time'		=> TIMENOW,
			'org_id'			=> $this->user['org_id'],	
			'user_id'			=> $this->user['user_id'],	
			'user_name'			=> $this->user['user_name'],	
			'ip'				=> $this->user['ip'],	
		);
		if (!$data['title'])
		{
			$this->errorOutput('标题不能为空');
		}

		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'] && !$data['GPS_longitude'] && !$data['GPS_latitude'])
		{
			$gps = $this->lbs->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//如果GPS坐标存在的话，就转换为百度坐标也存起来
		if(!$data['baidu_longitude'] && !$data['baidu_latitude'] && $data['GPS_longitude'] && $data['GPS_latitude'])
		{
			$baidu = $this->lbs->FromGpsToBaiduXY($data['GPS_longitude'],$data['GPS_latitude']);
			$data['baidu_longitude'] = $baidu['x'];
			$data['baidu_latitude'] = $baidu['y'];
		}
		//电话处理
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel))
			{
				$data['tel'] = serialize($tel);
			}
		}
		//添加lbs信息
		$lbs = $this->lbs->add_lbs($data);
		if (!$lbs['id'])
		{			
			$this->errorOutput('数据库插入失败');
		}
		$id = $lbs['id'];
		//附加信息处理
		$data['field']=$this->lbs_field->field_contentupdate($id,$this->input['sort_id'],$this->input['catalog']);
		//添加描述	
		$content = trim($this->input['content']);
		if ($content)
		{
			$contentInfor = $this->lbs->add_content($content, $id);
			if (!$contentInfor)
			{
				$this->errorOutput('数据库插入失败');
			}
			$data['content'] = $contentInfor;
		}
		//图片上传
		if ($_FILES['photos'])
		{
			$photos = array();
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			$count = count($_FILES['photos']['name']);
			for($i = 0; $i < $count; $i++)
			{
				if ($_FILES['photos']['name'][$i])
				{
					if ($_FILES['photos']['error'][$i]>0)
					{
						$this->errorOutput('图片上传异常');
					}
					if ($_FILES['photos']['size'][$i]>100000000)
					{
						$this->errorOutput('只允许上传100M以下的图片!');
					}
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$photo['Filedata'][$k] = $_FILES['photos'][$k][$i];
					}
					$photos[] = $photo;
				}			
			}
			if (!empty($photos))
			{
				//循环插入图片服务器
				foreach ($photos as $val)
				{
					$PhotoInfor = $this->lbs->uploadToPicServer($val, $id);
					if (empty($PhotoInfor))
					{
						$this->errorOutput('图片服务器错误!');
					}
					$temp = array(
									'cid'			=> $id,
									'type'			=> $PhotoInfor['type'],						
									'original_id'	=> $PhotoInfor['id'],
									'host'			=> $PhotoInfor['host'],
									'dir'			=> $PhotoInfor['dir'],
									'filepath' 		=> $PhotoInfor['filepath'],
									'filename'		=> $PhotoInfor['filename'],
									'imgwidth'		=> $PhotoInfor['imgwidth'],
									'imgheight'		=> $PhotoInfor['imgheight'],
									'mark'			=> 'img',
					);
					//插入数据库
					$ret_pic = $this->lbs->upload_pic($temp);
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->lbs->update_indexpic($ret_pic['id'], $id);
						$data['indexpic'] = $indexpic['id'];
						$data['img_info'] = $indexpic;
					}
					if ($ret_pic)
					{
						$data['images'][] = $ret_pic;
					}
					else 
					{
						$this->errorOutput('图片入库失败');	
					}
					
				}
			}
		}		
		//视频上传
		if ($_FILES['video'])
		{
			$videos = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput('视频服务器未安装!');
			}

			$count = count($_FILES['video']['name']);
			for($i = 0; $i < $count; $i++)
			{
				if ($_FILES['video']['name'][$i])
				{
					if ($_FILES['video']['error'][$i]>0)
					{
						$this->errorOutput('视频上传异常');
					}
					foreach($_FILES['video'] AS $k =>$v)
					{
						$video['videofile'][$k] = $_FILES['video'][$k][$i];
					}
					$videos[] = $video;
				}
			}
			
			if (!empty($videos))
			{
				foreach ($videos as $videoInfor)
				{
					//上传视频服务器
					$videodata = $this->lbs->uploadToVideoServer($videoInfor, $data['title'], '',2);
					if (!$videodata)
					{
						$this->errorOutput('视频服务器错误!');
					}
					//视频入库
					$arr = array(
								'cid' 		 => $id,
								'type'		 => $videodata['type'],
								'host'		 => $videodata['protocol'].$videodata['host'],
								'dir'		 => $videodata['dir'],
								'original_id'=> $videodata['id'],
								'filename'	 => $videodata['file_name'],
								'mark'		 => 'video',
							);
							
					$ret_vod = $this->lbs->upload_vod($arr);
					if ($ret_vod)
					{
						$data['video'][] = $ret_vod;
					}
					else
					{
						$this->errorOutput('视频入库失败');
					} 
				}
			}
		}
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		//数据验证，不可修改他人数据
		$res = $this->lbs->checkSelfData($id, $this->user['id']);
		if (!$res)
		{
			//$this->errorOutput('不可修改他人数据');
		}
		$data = array(
			'title' 			=> trim($this->input['title']),
			'sort_id' 			=> intval($this->input['sort_id']),
			'province_id'		=> intval($this->input['province_id']),
			'city_id' 			=> intval($this->input['city_id']),
			'area_id'			=> intval($this->input['area_id']),
			'stime'				=> strtotime($this->input['stime']),
			'etime'				=> strtotime($this->input['etime']),
			'indexpic'			=> intval($this->input['indexpic']),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'address'			=> trim($this->input['address']),
			'update_time'		=> TIMENOW,
			'update_org_id'		=> $this->user['org_id'],	
			'update_user_id'	=> $this->user['user_id'],	
			'update_user_name'	=> $this->user['user_name'],	
			'update_ip'			=> $this->user['ip'],	
		);
		if (!$data['title'])
		{
			$this->errorOutput('标题不能为空');
		}
		if (!$data['sort_id'])
		{
			$this->errorOutput('请选择分类');
		}
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->lbs->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//电话处理
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel))
			{
				$data['tel'] = serialize($tel);
			}
		}
		$sql = 'UPDATE '.DB_PREFIX.'lbs SET ';
		foreach ($data as $key=>$val)
		{
			$sql .=  $key . '="' . addslashes($val) . '",';
		}
		$sql = rtrim($sql, ',');
		$sql.= ' WHERE id = ' . $id;
		$this->db->query($sql);
		
		//内容处理
		$content = trim($this->input['content']);
		$sql = 'UPDATE '.DB_PREFIX.'lbs_content SET content = "'.addslashes($content).'" WHERE id = '.$id;
		$this->db->query($sql);
		//图片删除
		if ($this->input['delMaterialIds'])
		{
			if (is_array($this->input['delMaterialIds']))
			{
				$delMaterialIds = implode(',', $this->input['delMaterialIds']);
			}
			if (is_string($this->input['delMaterialIds']))
			{
				$delMaterialIds = $this->input['delMaterialIds'];
			}
			$ret = $this->lbs->deleteMaterials($delMaterialIds);
			if (!$ret)
			{
				$this->errorOutput('删除图片失败');
			}
		}	
		//图片上传
		if ($_FILES['photos'])
		{
			$photos = array();
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			$count = count($_FILES['photos']['name']);
			for($i = 0; $i < $count; $i++)
			{
				if ($_FILES['photos']['name'][$i])
				{
					if ($_FILES['photos']['error'][$i]>0)
					{
						$this->errorOutput('图片上传异常');
					}
					if ($_FILES['photos']['size'][$i]>100000000)
					{
						$this->errorOutput('只允许上传100M以下的图片!');
					}
					foreach($_FILES['photos'] AS $k =>$v)
					{
						$photo['Filedata'][$k] = $_FILES['photos'][$k][$i];
					}
					$photos[] = $photo;
				}			
			}
			if (!empty($photos))
			{
				//循环插入图片服务器
				foreach ($photos as $val)
				{
					$PhotoInfor = $this->lbs->uploadToPicServer($val, $id);
					if (empty($PhotoInfor))
					{
						$this->errorOutput('图片服务器错误!');
					}
					$temp = array(
									'cid'			=> $id,
									'type'			=> $PhotoInfor['type'],						
									'original_id'	=> $PhotoInfor['id'],
									'host'			=> $PhotoInfor['host'],
									'dir'			=> $PhotoInfor['dir'],
									'filepath' 		=> $PhotoInfor['filepath'],
									'filename'		=> $PhotoInfor['filename'],
									'imgwidth'		=> $PhotoInfor['imgwidth'],
									'imgheight'		=> $PhotoInfor['imgheight'],
									'mark'			=> 'img',
					);
					//插入数据库
					$ret_pic = $this->lbs->upload_pic($temp);
					if ($ret_pic)
					{
						$data['images'][] = $ret_pic;
					}
					else 
					{
						$this->errorOutput('图片入库失败');	
					}
				}
			}
		}
		$data['id'] = $id;
		$this->lbs_field->field_contentupdate($id,$this->input['sort_id'],$this->input['catalog']);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		
	}
	
	public function unknow()
	{
		$this->errorOutput('此方法不存在');
	}
}
$ouput= new LBSUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
