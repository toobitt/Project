<?php
require_once './global.php';
require_once CUR_CONF_PATH.'lib/contribute.class.php';
define('MOD_UNIQUEID','contribute_update');//模块标识
class contributeUpdateApi extends outerUpdateBase
{
	private $mVerifyCode;
	public function __construct()
	{
		parent::__construct();
		$this->contribute = new contribute();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 *
	 * @Description   发报料
	 * @author Kin
	 * @date 2013-5-8 上午09:41:55
	 * @see outerUpdateBase::create()
	 */
	public function create()
	{
		$content = addslashes(trim($this->input['content']));

		/*********** 验证码 ***********/
		if(defined('IS_VERIFYCODE') && IS_VERIFYCODE)
		{
			require_once ROOT_PATH . 'lib/class/verifycode.class.php';
			$this->mVerifyCode = new verifyCode();
			$code = trim($this->input['verify_code']); //验证码
			$session_id = $this->input['session_id']; //标识
			if(!$code)
			{
				$this->errorOutput(NO_VERIFYCODE);
			}
			if(!$session_id)
			{
				$this->errorOutput(NO_SESSIONID);
			}
			$check_result = $this->mVerifyCode->check_verify_code($code, $session_id);  //验证验证码
			if($check_result != 'SUCCESS')
			{
				$data['error'] = $check_result;
				$this->addItem($data);
				$this->output();
			}
		}
		/***************************/

		if (!$content)
		{
			$this->errorOutput(NO_INPUT_CONTENT);
		}
		
		$count = count($_FILES['photos']['name']);
			
		//判断图片上传数目
		$img_num = '';
		$img_num = defined('UPLOAD_IMG_NUM') ? UPLOAD_IMG_NUM : 0;
		
		if($img_num && $count && ($count > $img_num))
		{
			$this->errorOutput('最多上传' .$img_num.'张图片');
		}
			
		//报料设备标识
		$device_token = trim($this->input['device_token']);
		
		//判断分类是否允许重复爆料
		$sort_id = intval($this->input['sort_id']);
		if($sort_id)
		{
			$sql = "SELECT repeat_switch FROM " . DB_PREFIX . "sort WHERE id = " . $sort_id;
			$res = $this->db->query_first($sql);
			$repeat_switch = $res['repeat_switch'];
			
			if($repeat_switch && $device_token)
			{
				$sql = "SELECT id FROM " . DB_PREFIX . "content WHERE sort_id = " . $sort_id . " AND device_token = '" . $device_token . "'";
				$res = $this->db->query_first($sql);
				if($res['id'])
				{
					$this->errorOutput(CONTRIBUTE_LIMIT);
				}
			}
		}
		$status = defined('CONTRIBUTE_AUDIT') ? CONTRIBUTE_AUDIT : 1;
		if($status != 1 && $status != 2)
		{
			$status = 1;
		}
		
		if($this->input['event_time'])
		{
			$event_time = strtotime($this->input['event_time']);
		}
		
		$event_time = $event_time ? $event_time : TIMENOW;
		
		$data = array(
					'title'  	  => addslashes(trim($this->input['title'])),
					'brief' 	  => addslashes(trim($this->input['brief'])),
					'appid'  	  => $this->user['appid'],
					'client' 	  => $this->user['display_name'],
					'audit'		  => $status,
		 			'sort_id'     => $sort_id, 
					'org_id'	  => $this->user['org_id'],
					'is_m2o'      => 0,
					'user_id'	  => $this->input['user_id'] ? $this->input['user_id'] : $this->user['user_id'],
					'user_name'	  => $this->input['user_name'] ? addslashes($this->input['user_name']) : addslashes($this->user['user_name']),			 	
					'create_time' => intval($this->input['create_time']) ? intval($this->input['create_time']) : TIMENOW,
					'update_time' => intval($this->input['create_time']) ? intval($this->input['create_time']) : TIMENOW,
					'ip'          => $this->user['ip'],
					'event_time'  => $event_time,
					'event_address'=> trim($this->input['event_address']),
					'event_suggest'=> trim($this->input['event_suggest']),
					'event_user_name'=> trim($this->input['event_user_name']),
					'event_user_tel'=> trim($this->input['event_user_tel']),
					'is_credits' =>IS_EXTRA_CREDITS&&$this->input['iscreditsrule']?0:-1,////积分增加配置
					'device_token' => $device_token,
		);
		
        
		//过滤敏感词
		/*if(defined('IS_BANWORD') && IS_BANWORD && $this->settings['App_banword'])
		{
			$banword_content = '';
			$banword_content = $data['title'] . '|' . $data['brief'] . '|' .  $content;
			
			include_once(ROOT_PATH . 'lib/class/banword.class.php');
			$this->banword = new banword();
			$banword = array();
			$replace_content = $this->banword->replace($banword_content,'*');
			$banword = $this->banword->exists($banword_content);
			if(!empty($banword))
			{
				$colation = '';
				if(defined('COLATION_TYPE'))
				{
					$colation = COLATION_TYPE;
				}
				else
				{
					$colation = 3;
				}
				
				if($colation == 1)//禁止入库
				{
					$this->errorOutput(BANWORD);
				}
				else if($colation == 2)
				{
					$data['audit'] = 4;//标识敏感词
				}
				else//默认替换敏感词
				{
					$replace_content = $this->banword->replace($banword_content,'*');
					
					$new_arr = explode('|', $replace_content);
					
					$data['title'] = $new_arr[0];
					$data['brief'] = $new_arr[1];
					$content = $new_arr[3];
					
					$data['audit'] = 1;//待审核
				}

				//记录敏感词
				$banwords = array();
				if(is_array($banword) && !empty($banword))
				{
					foreach ($banword as $v)
					{
						$banwords[] = $v['banname'];
					}
				}
				$data['banwords'] = implode(',', $banwords);
			}
		}*/
		
		$longitude =  trim($this->input['longitude']);
		$latitude =  trim($this->input['latitude']);
		if ($this->input['appid']=='21')
		{
			$data['baidu_longitude'] = $longitude;
			$data['baidu_latitude'] = $latitude;
		}
		else
		{
			$data['GPS_longitude'] = $longitude;
			$data['GPS_latitude'] = $latitude;
			$data['longitude'] = $longitude;
			$data['latitude'] = $latitude;
		}
		
		//支持单独接收百度坐标
		if($this->input['baidu_latitude'] && $this->input['baidu_longitude'])
		{
			$data['baidu_latitude'] = $this->input['baidu_latitude'];
			$data['baidu_longitude'] = $this->input['baidu_longitude'];
		}
		
		//支持单独接受gps坐标
		if($this->input['GPS_longitude'] && $this->input['GPS_latitude'])
		{
			$data['GPS_latitude'] = $this->input['GPS_latitude'];
			$data['GPS_longitude'] = $this->input['GPS_longitude'];
		}
		
		
		//分类异常处理
		$data['sort_id'] = $this->contribute->sortException($data['sort_id']);
		if (!$data['title'])
		{
			$data['title'] = hg_cutchars($content,20);
		}
		if (!$data['brief'])
		{
			$data['brief'] = hg_cutchars($content,100);
		}
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'] && !$data['GPS_longitude'] && !$data['GPS_latitude'])
		{
			$gps = $this->contribute->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
			$data['longitude'] = $gps['GPS_x'];
			$data['latitude'] = $gps['GPS_y'];
		}
		//如果GPS坐标存在的话，就转换为百度坐标也存起来
		if(!$data['baidu_longitude'] && !$data['baidu_latitude'] && $data['GPS_longitude'] && $data['GPS_latitude'])
		{
			$baidu = $this->contribute->FromGpsToBaiduXY($data['GPS_longitude'],$data['GPS_latitude']);
			$data['baidu_longitude'] = $baidu['x'];
			$data['baidu_latitude'] = $baidu['y'];
		}
		if (!$data['title'])
		{
			$this->errorOutput(NO_INPUT_TITLE);
		}
		$userinfo = array();
		if ($this->user['user_id'] && $this->settings['App_members'] && $this->input['new_member']==1)
		{
			$return = $this->contribute->get_newUserInfo_by_id($this->user['user_id']);
			if (empty($return))
			{
				$this->errorOutput(NEW_MEMBER_ERROR);
			}
			$data['user_name'] = $return['member_name'];
			$data['new_member'] = 1;
		}
		elseif ($this->user['user_id'] && $this->settings['App_member'])
		{
			$return = $this->contribute->get_userinfo_by_id($this->user['user_id']);
			if (empty($return))
			{
				$this->errorOutput(MEMBER_ERROR);
			}
			$data['user_name'] = $return['member_name'];
		}

		//添加爆料主表
		$contributeId = $this->contribute->add_content($data);
		if (!intval($contributeId))
		{
			$this->errorOutput(CONTRIBUTE_ERROR);
		}
		/***********************调用积分规则,给已审核评论增加积分START*****************/
		if($data['new_member']&&$this->input['iscreditsrule'])//是启用新会员系统
		{
			if($this->settings['App_members'])
			{
				include (ROOT_PATH.'lib/class/members.class.php');
				$Members = new members();
				$Members->Setoperation(APP_UNIQUEID);
				$field='';
				/***未审核增加积分**/
				if((IS_CREDITS)&&$data['user_id'])
				{
					$credit_rules=$Members->get_credit_rules($data['user_id'],APP_UNIQUEID);
				}
				/***审核增加积分**/
				if((IS_EXTRA_CREDITS&&$data['audit'] == 2)&&$data['user_id'])//审核增加积分为真&&已审核状态&&有user_id
				{
					$Members->Initoperation();//初始化
					$Members->Setoperation(APP_UNIQUEID,'','','extra');
					$credit_rules_extra=$Members->get_credit_rules($data['user_id'],APP_UNIQUEID);
					$field='is_credits=1';
				}
				/**积分文案处理**/
				$credit_copy=array();
				if($credit_rules['updatecredit'])
				{
					$credit_copy[]=$credit_rules;
				}
				if($credit_rules_extra['updatecredit'])
				{
					$credit_copy[]=$credit_rules_extra;
				}
				$data['copywriting_credit'] = $Members->copywriting_credit($credit_copy);
				/**积分文案处理结束**/
				/**更新获得积分字段**/
				if($field)
				{
					$this->db->query("UPDATE " . DB_PREFIX . "content SET ".$field." WHERE id=".$contributeId);
				}
			}
		}
		/***********************调用积分规则,给已审核评论增加积分END*****************/
		//添加内容表
		$body = array(
			'id'   => $contributeId,
			'text' => $content,
		);
		$this->contribute->add_contentbody($body);
		if ($this->input['user_name'])
		{
			$userinfo['con_id'] = intval($contributeId);
			$userinfo['tel'] = addslashes($this->input['tel']) ;
			$userinfo['email'] = addslashes($this->input['email']);
			$userinfo['addr'] = addslashes($this->input['addr']);
		}
		else
		{
			$userinfo['con_id'] = intval($contributeId);
			$userinfo['tel'] = $this->input['tel'] ? addslashes($this->input['tel']) : addslashes($return['mobile']);
			$userinfo['email'] = $this->input['email'] ? addslashes($this->input['email']) : addslashes($return['email']);
			$userinfo['addr'] = $this->input['addr'] ? addslashes($this->input['addr']) : addslashes($return['address']);
		}
		if (!empty($userinfo))
		{
			$this->contribute->user_info($userinfo);
		}
		
		//单视频上传
		if ($_FILES['videofile'])
		{
			$video = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput(NO_VIDEO_APP);
			}
			if ($_FILES['videofile']['error']>0)
			{
				$this->errorOutput(VIDEO_FILE_ERROR);
			}
			//获取视频服务器上传配置
			
			$videoConfig = $this->contribute->getVideoConfig();
			if (!$videoConfig)
			{
				$this->errorOutput('获取允许上传的视频类型失败！');
			}

			$filetype = strtolower(strrchr($_FILES['videofile']['name'], '.'));
			if (!in_array($filetype, $videoConfig['type']))
			{
				$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
			}
			
			//上传视频服务器
			$videodata = $this->contribute->uploadToVideoServer($_FILES, $data['title'], $data['brief']);
			if (!$videodata)
			{
				$this->errorOutput(VIDEO_SERVER_ERROR);
			}
			//有视频没有图片时，将视频截图上传作为索引图
			if (!$indexpic)
			{
				$url = $videodata['img']['host'].$videodata['img']['dir'].$videodata['img']['filepath'].$videodata['img']['filename'];
				$material = $this->contribute->localMaterial($url, $contributeId);
				//此处可能是音频,视频取截图作为索引图
				if ($material)
				{
					$arr = array(
						'content_id'	=> $contributeId,
						'mtype'			=> $material['type'],
						'original_id'	=> $material['id'],
						'host'			=> $material['host'],
						'dir'			=> $material['dir'],
						'material_path' => $material['filepath'],
						'pic_name'		=> $material['filename'],
						'is_vod_pic'	=> 1,
					);
					$indexpic = $this->contribute->upload($arr);
					$this->contribute->update_indexpic($indexpic, $contributeId);
				}
			}
			//视频入库
			$arr = array(
				'content_id' => $contributeId,
				'mtype'		 => $videodata['type'],
				'host'		 => $videodata['protocol'].$videodata['host'],
				'dir'		 => $videodata['dir'],
				'vodid'		 => $videodata['id'],
				'filename'	 => $videodata['file_name'],
			);

			$this->contribute->upload($arr);
		}
		
		//多视频上传
		if ($_FILES['videofiles'])
		{
			$video = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput('视频服务器未安装!');
			}
			
			//获取视频服务器上传配置
			$videoConfig = $this->contribute->getVideoConfig();
			if (!$videoConfig)
			{
				$this->errorOutput('获取允许上传的视频类型失败！');
			}
			
			$count = count($_FILES['videofiles']['name']);
			for($i = 0; $i <= $count; $i++)
			{
				if ($_FILES['videofiles']['name'][$i])
				{
					if ($_FILES['videofiles']['error'][$i]>0)
					{
						$this->errorOutput('视频异常');
					}
					$filetype = '';
					$filetype = strtolower(strrchr($_FILES['videofiles']['name'][$i], '.'));
					if (!in_array($filetype, $videoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
					}
					foreach($_FILES['videofiles'] AS $k =>$v)
					{
						$video['videofiles'][$k] = $_FILES['videofiles'][$k][$i];
					}
					$videos[] = $video;
				}
			}
			
			
			if (!empty($videos))
			{
				//循环上传视频
				foreach ($videos as $val)
				{
					$videodata = '';
					//上传视频服务器
					$video_file = array();
					$video_file['videofile'] = $val['videofiles'];
					$videodata = $this->contribute->uploadToVideoServer($video_file, $data['title'], $data['brief']);
					if (!$videodata)
					{
						$this->errorOutput('视频服务器错误!');
					}
					
					$info[] = $videodata;
					//有视频没有图片时，将视频截图上传作为索引图
					if (!$indexpic)
					{
						$url = $videodata['img']['host'].$videodata['img']['dir'].$videodata['img']['filepath'].$videodata['img']['filename'];
						$material = $this->contribute->localMaterial($url, $contributeId);
						if ($material)
						{
							$arr = array(
								'content_id'	=> $contributeId,
								'mtype'			=> $material['type'],
								'original_id'	=> $material['id'],
								'host'			=> $material['host'],
								'dir'			=> $material['dir'],
								'material_path' => $material['filepath'],
								'pic_name'		=> $material['filename'],
								'is_vod_pic'	=> 1,
							);
							$indexpic = $this->contribute->upload($arr);
							$this->contribute->update_indexpic($indexpic, $contributeId);
						}
					}
					
					//视频入库
					$arr = array(
						'content_id' => $contributeId,
						'mtype'		 => $videodata['type'],
						'host'		 => $videodata['protocol'].$videodata['host'],
						'dir'		 => $videodata['dir'],
						'vodid'		 => $videodata['id'],
						'filename'	 => $videodata['file_name'],
					);
		
					$this->contribute->upload($arr);
				}
			}
		}
		
		//图片上传
		if ($_FILES['photos'])
		{
			$photos = array();
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput(NO_MATERIAL_APP);
			}
			//获取图片服务器上传配置
			
			$PhotoConfig = $this->contribute->getPhotoConfig();
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
			}
			
			$count = count($_FILES['photos']['name']);
			
			for($i = 0; $i <= $count; $i++)
			{
				if ($_FILES['photos']['name'][$i])
				{
					if ($_FILES['photos']['error'][$i]>0)
					{
						$this->errorOutput(PICTURE_FILE_ERROR);
					}
					
					 if (!in_array($_FILES['photos']['type'][$i], $PhotoConfig['type']))
					 {
					//	$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式的图片');
					}
						
					if ($_FILES['photos']['size'][$i]>100000000)
					{
						$this->errorOutput(PICTURE_OVER_SIZE);
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
					$PhotoInfor = $this->contribute->uploadToPicServer($val, $contributeId);
					if (empty($PhotoInfor))
					{
						$this->errorOutput(MATERIAL_SERVER_ERROR);
					}
					$temp = array(
									'content_id'	=> $contributeId,
									'mtype'			=> $PhotoInfor['type'],						
									'original_id'	=> $PhotoInfor['id'],
									'host'			=> $PhotoInfor['host'],
									'dir'			=> $PhotoInfor['dir'],
									'material_path' => $PhotoInfor['filepath'],
									'pic_name'		=> $PhotoInfor['filename'],
									'imgwidth'		=> $PhotoInfor['imgwidth'],
									'imgheight'		=> $PhotoInfor['imgheight'],
					);
					//插入数据库
					$PhotoId = $this->contribute->upload($temp);
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->contribute->update_indexpic($PhotoId, $contributeId);
					}
				}
			}
		}
		else
		{
			if($this->input['local_material'])
		{
			$file_array = explode(',',trim($this->input['local_material']));
			
			if (!empty($file_array))
			{
				//循环插入图片服务器
				foreach ($file_array as $val)
				{
					$PhotoInfor = $this->contribute->localMaterial($val, $contributeId);
					if (empty($PhotoInfor))
					{
						$this->errorOutput(MATERIAL_SERVER_ERROR);
					}
					$temp = array(
									'content_id'	=> $contributeId,
									'mtype'			=> $PhotoInfor['type'],						
									'original_id'	=> $PhotoInfor['id'],
									'host'			=> $PhotoInfor['host'],
									'dir'			=> $PhotoInfor['dir'],
									'material_path' => $PhotoInfor['filepath'],
									'pic_name'		=> $PhotoInfor['filename'],
									'imgwidth'		=> $PhotoInfor['imgwidth'],
									'imgheight'		=> $PhotoInfor['imgheight'],
					);
					//插入数据库
					$PhotoId = $this->contribute->upload($temp);
					//默认第一张图片为索引图
					if (!$indexpic)
					{
						$indexpic = $this->contribute->update_indexpic($PhotoId, $contributeId);
					}
				}
			}
		}
		}
		
		$data['id'] = $contributeId;
		$data['copywriting']='爆料成功';
		$this->addItem($data);
		$this->output();
	}

	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		if($this->input['event_time'])
		{
			$event_time = strtotime($this->input['event_time']);
		}
		
		$event_time = $event_time ? $event_time : TIMENOW;
		
		$data = array(
					'title'			=> addslashes(trim($this->input['title'])),
					'sort_id'   	=> intval($this->input['sort_id']),
					'brief'	    	=> addslashes(trim($this->input['brief'])),
					'event_time'  	=> $event_time,
					'event_address'=> trim($this->input['event_address']),
					'event_suggest'=> trim($this->input['event_suggest']),
					'event_user_name'=> trim($this->input['event_user_name']),
					'event_user_tel'=> trim($this->input['event_user_tel']),
		);
		$updateContent = $this->contribute->update_content($data, $id);
		if (!$updateContent)
		{
			$this->errorOutput(UPDATE_FAILED);
		}
		$content = $this->input['content'];
		if ($content)
		{
			$updateContentbody = $this->contribute->update_contentbody($content, $id);
			if (!$updateContentbody)
			{
				$this->errorOutput(UPDATE_FAILED);
			}
			$data['content'] = $content;
		}
		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}

	public function delete()
	{

	}
}
$ouput= new contributeUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>
