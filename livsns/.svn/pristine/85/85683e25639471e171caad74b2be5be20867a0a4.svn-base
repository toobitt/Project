<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp_update');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once CUR_CONF_PATH.'lib/section_mode.php';
require_once CUR_CONF_PATH.'lib/timeline_mode.php';
require_once CUR_CONF_PATH.'lib/app_config_mode.php';
require_once(ROOT_PATH.'lib/class/recycle.class.php');
require_once(ROOT_PATH.'lib/class/members.class.php');
require_once CUR_CONF_PATH.'lib/seekhelp_blacklist_mode.php';
class seekhelpUpdateApi extends outerUpdateBase
{
	private $timeline;
	private $section;
	private $members;
    private $appconfig;
    private $blacklist;
	public function __construct()
	{
		parent::__construct();
		//$this->verify_member_purview(array('_action'=>'manage','token'=>$this->user['token']));
		$this->sh = new ClassSeekhelp();
		$this->recycle = new recycle();
		$this->timeline = new timeline_mode();
		$this->section = new section_mode();
		$this->members = new members();
        $this->appconfig = new app_config_mode();
        $this->blacklist = new seekhelp_blacklist_mode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * @Description
	 * @author Kin
	 * @date 2013-6-6 下午03:50:51 
	 * @see outerUpdateBase::create()
	 */
	public function create()
	{
        //检测社区黑名单
        $this->check_black();
        if(!intval($this->input['sort_id']))
        {
            $this->errorOutput(NO_SORT_ID);
        }
        //限制发帖时间
        if($this->user['user_id'])
        {
            $condition = ' AND member_id='.$this->user['user_id'].'';
            $latest_info = $this->sh->getSeekhelplist($condition,' ORDER BY order_id  DESC',0,1,$this->input['sort_id']);
            if($latest_info[0])
            {
                if($latest_info[0]['create_time'] +  LIMIT_POSTING_TIME >= TIMENOW)
                {
                    $this->errorOutput(POSTING_FAST);
                }
                if($latest_info[0]['content'] == trim($this->input['content']))
                {
                    $this->errorOutput(CONTENT_EXIST);
                }
            }
        }

        $data = array(
				'title'  	  => trim($this->input['title']),
				'status'  	  => 0,
				'appid'  	  => $this->user['appid'],
				'appname' 	  => $this->user['display_name'],
	 			'baidu_longitude' => trim($this->input['baidu_longitude']),
	 			'baidu_latitude'  => trim($this->input['baidu_latitude']),
				'GPS_longitude'   => trim($this->input['GPS_longitude']),
	 			'GPS_latitude'    => trim($this->input['GPS_latitude']),
				'location'    => trim($this->input['location']),
	 			'sort_id'     => intval($this->input['sort_id']),
	 			'section_id'  => intval($this->input['section_id']),
				'account_id'  => intval($this->input['account_id']),
				'org_id'	  => $this->user['org_id'],
				'member_id'	  => $this->user['user_id'],
				'tel'         => trim($this->input['tel']),
				'create_time' => TIMENOW,
				'comment_latest_time' => TIMENOW,
				'ip'          => $this->user['ip'],
		);

		$content = trim($this->input['content']);
		if (empty($content))
		{
			$this->errorOutput(NO_CONTENT);
		}
		
		if($data['section_id'])
		{
			$sectionInfo = $this->section->detail($data['section_id']);
			if(!$sectionInfo)
			{
				$this->errorOutput(NO_SECTION);
			}
		}
		//会员黑名单验证
		if($data['member_id'])
		{
			include_once(ROOT_PATH.'lib/class/members.class.php');
			
			$obj = new members();
			
			$res = $obj->check_blacklist($data['member_id']);
			
			if($res[$data['member_id']]['isblack'])
			{
                //$this->addItem_withkey('error', "您的评论被屏蔽，请联系管理员！");
                //$this->addItem_withkey('msg', "您的评论被屏蔽，请联系管理员！");
                //$this->output(); 
				$this->errorOutput(IS_BLACK_MEMBER);
			}
		}
		//分类异常处理
		$data['sort_id'] = $this->sh->sortException($data['sort_id']);
		if (defined('SEEKHELP_STATUS') && SEEKHELP_STATUS && !$_FILES['photos'] && !$_FILES['video'])
		{
			$data['status'] = 1;
		}
		if (defined('SEEKHELP_MATERIAL_STATUS') && SEEKHELP_MATERIAL_STATUS && ($_FILES['photos'] || $_FILES['video']))
		{
			$data['status'] = 1;
		}
        if($this->input['app_id'])
        {
            $app_id = $this->input['app_id'];
            $appconfig = $this->appconfig->detail($app_id);
            if($appconfig['seekhelp_audit'] == 0)
            {
                $data['status'] = 1;
            }
            else
            {
                $data['status'] = 0;
            }
        }

		//屏蔽字验证
		if ($this->settings['App_banword'] && defined('IS_BANWORD') && IS_BANWORD)
		{
			require_once(ROOT_PATH.'lib/class/banword.class.php');
			$this->banword = new banword();
			$str = $data['title'].$content;
			
			$banword = $this->banword->exists($str);
			
			if ($banword && is_array($banword))
			{
				$banword_title = '';
				$banword_content = '';
				foreach ($banword as $key=>$val)
				{
					if (strstr($data['title'], $val['banname']))
					{
						$banword_title .= $val['banname'].',';
					}
					if (strstr($content, $val['banname']))
					{
						$banword_content .= $val['banname'].',';
					}
				}
				$banword_title = $banword_title ? rtrim($banword_title,',') : '';
				$banword_content = $banword_content ? rtrim($banword_content,',') : '';
				if ($banword_title || $banword_content)
				{
					$banwords = array(
						'title'		=> $banword_title,
						'content'	=> $banword_content,
					);
					$data['status'] = 0;					//含有屏蔽字直接未审
					$data['banword'] = serialize($banwords);
				}
			}
		}
		
		
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'] && !$data['GPS_longitude'] && !$data['GPS_latitude'])
		{
			$gps = $this->sh->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		//如果GPS坐标存在的话，就转换为百度坐标也存起来
		if(!$data['baidu_longitude'] && !$data['baidu_latitude'] && $data['GPS_longitude'] && $data['GPS_latitude'])
		{
			$baidu = $this->sh->FromGpsToBaiduXY($data['GPS_longitude'],$data['GPS_latitude']);
			$data['baidu_longitude'] = $baidu['x'];
			$data['baidu_latitude'] = $baidu['y'];
		}
		
		if (!$data['title'])
		{
			$data['title'] = hg_cutchars($content,100);
		}
		
		if (!$data['title'])
		{
			$this->errorOutput('请输入内容');
		}
		//初始化的数据
		$is_img 	= 0;
		$is_video 	= 0;
		$is_reply 	= 0;
		//添加求助信息
		$seekhelpInfor = $this->sh->add_seekhelp($data);
		if (!$seekhelpInfor['id'])
		{			
			$this->errorOutput('数据库插入失败');
		}
		$id = $seekhelpInfor['id'];
		//添加描述	
		
		if ($content)
		{
			$contentInfor = $this->sh->add_content($content, $id);
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
			//获取图片服务器上传配置
// 			$PhotoConfig = $this->sh->getPhotoConfig();
// 			if (!$PhotoConfig)
// 			{
// 				$this->errorOutput('获取允许上传的图片类型失败！');
// 			}
			$count = count($_FILES['photos']['name']);
			for($i = 0; $i < $count; $i++)
			{
				if ($_FILES['photos']['name'][$i])
				{
					if ($_FILES['photos']['error'][$i]>0)
					{
						$this->errorOutput('图片上传异常');
					}
					/*
					if (!in_array($_FILES['photos']['type'][$i], $PhotoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$PhotoConfig['hit'].'格式的图片');
					}
					*/
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
					$PhotoInfor = $this->sh->uploadToPicServer($val, $id);
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
									'imgwidth'		    => $PhotoInfor['imgwidth'],
									'imgheight'		=> $PhotoInfor['imgheight'],
									'mark'			=> 'img',
					);
					//插入数据库
					$ret_pic = $this->sh->upload_pic($temp);
					if ($ret_pic)
					{
						$data['pic'][] = $ret_pic;
					}
					else 
					{
						$this->errorOutput('图片入库失败');	
					}
					
				}
				$is_img = 1;
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
			//获取视频服务器上传配置
			$videoConfig = $this->sh->getVideoConfig();
			if (!$videoConfig)
			{
				$this->errorOutput('获取允许上传的视频类型失败！');
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
					/*
					$filetype = strtolower(strrchr($_FILES['video']['name'][$i], '.'));	
					if (!in_array($filetype, $videoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
					}
					*/
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
					$videodata = $this->sh->uploadToVideoServer($videoInfor, $data['title'], '',2);
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
							
					$ret_vod = $this->sh->upload_vod($arr);
					if ($ret_vod)
					{
						$data['video'][] = $ret_vod;
					}
					else
					{
						$this->errorOutput('视频入库失败');
					} 
				}
				$is_video = 1;
			}
		}
		//更新主表回复，图片，视频纪录
		$status = array(
			'is_reply'	=> 0,
			'is_img'	=> $is_img,
			'is_video'	=> $is_video,
		);
		$ret_status = $this->sh->update_status($status, $id);
		if ($ret_status)
		{
			$data['is_reply'] = $ret_status['is_reply'];
			$data['is_img'] = $ret_status['is_img'];
			$data['is_video'] = $ret_status['is_video'];	
		}
        if($data['status'])
        {
            $this->SetTimeline($id);
            //更新会员统计
            $this->updateMemberCount($data['member_id'],'create');
        }

		$data['id'] = $id;
		$this->addItem($data);
		$this->output();
	}
	
	public function update()
	{
	
	}
	
	/**
	 * 更新老社区帖子到默认版块
	 */
	public function update_seekhelp_section()
	{
		$sortId = $this->input['sortId'];
		if(empty($sortId))
		{
			$this->errorOutput(NO_SORT_ID);
		}
		$sectionId = $this->input['sectionId'];
		$cids = is_array($this->input['contentid']) ? implode(",", $this->input['contentid']) : $this->input['contentid'];
		if(empty($sectionId))
		{
			$this->errorOutput(NO_SECTION_ID);
		}
		$old_sectionId = intval($this->input['old_sectionId']);
		
		$result = $this->sh->update_sekkhelp_section($sortId, $sectionId,$old_sectionId,$cids);
		
		$this->addItem($result);
		$this->output();
	}
	
	/**
	 * 帖子置顶功能
	 * isTop 置顶传1 取消0
	 */
	public function setTop()
	{
		$id = $this->input['id'];
		$isTop = intval($this->input['isTop']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		if($id)
		{
			$result = $this->sh->update_status(
					array(
						'is_top' => $isTop,
					), $id);
		}
		
		$this->addItem(array('code' => 0,'msg' => 'success'),$result);
		$this->output();
	}
	
	/**
	 * 帖子加精华功能
	 * isTop 加精传1 取消0
	 */
	public function setEssence()
	{
		$id = $this->input['id'];
		$isEssence = intval($this->input['isEssence']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
	
		if($id)
		{
			$result = $this->sh->update_status(
					array(
							'is_essence' => $isEssence,
					), $id);
		}
	
		$this->addItem(array('code' => 0,'msg' => 'success'),$result);
		$this->output();
	}
	
	public function delete()
	{
	    $ids = $this->input['id'];
	    if (!$ids)
	    {
	        $this->errorOutput(NOID);
	    }
	    $member_id = $this->user['user_id'];
	    if(!$member_id)
	    {
	    	$this->errorOutput(NO_MEMBER_ID);
	    }
	    
	    //判断是否是自己的帖子
	    $posts_info = $this->sh->detail($ids);
	    if(!$posts_info['member_id'])
	    {
	        $this->errorOutput(NO_POSTS);
	    }
	    if($posts_info['member_id'] != $this->user['user_id'])
	    {
	        //判断是否有权限删除贴子
	        if($this->input['access_token'])
	        {
	            $access_token = $this->input['access_token'];
	        }
	        $ret = $this->members->check_purview_Bytoken($access_token,'members_posts_del');
	        if(!$ret['allow'])
	        {
	            $this->errorOutput("您没有权限删除帖子，请联系管理员");
	        }
	    }
	    
	    
	    $sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$ids.')';
	    $query = $this->db->query($sql);
	    $sorts = array();
	    $seekhelps = array();
	    $recycle = array();
	    while ($row = $this->db->fetch_array($query))
	    {
	        $sorts[] = $row['sort_id'];
	        $seekhelps[$row['id']]  = $row;
	        $recycle[$row['id']] = array(
	                'cid'=>$row['id'],
	                'title'=>$row['title'],
	                'delete_people'=>$this->user['user_name'],
	        );
	        $recycle[$row['id']]['content']['seekhelp'] = $row;
	    }
            
        // 回收站数据整理，此处只删除了评论
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'comment WHERE cid IN (' . $ids . ')';
        $query = $this->db->query($sql);
        while($row = $this->db->fetch_array($query))
        {
            $recycle[$row['cid']]['content']['comment'][$row['id']] = $row;
        }
        
        // 放入回收站
        if($this->settings['App_recycle'] && ! empty($recycle))
        {
            foreach($recycle as $infor)
            {
                $ret = $this->recycle->add_recycle($infor['title'], $infor['delete_people'], $infor['cid'], $infor['content']);
                $result = $ret['sucess'];
                $is_open = $ret['is_open'];
            }
            if(!$result)
            {
                $this->errorOutput('删除失败，数据不完整');
            }
            if($is_open)
            {
                // 删除主表和评论表
                $sql = 'DELETE FROM ' . DB_PREFIX . 'seekhelp WHERE id IN (' . $ids . ')';
                $this->db->query($sql);
//                 $sql = 'DELETE FROM ' . DB_PREFIX . 'content WHERE id IN (' . $ids . ')';
//                 $this->db->query($sql);
//                 $sql = 'DELETE FROM ' . DB_PREFIX . 'materials WHERE cid IN (' . $ids . ')';
//                 $this->db->query($sql);
                $sql = 'DELETE FROM ' . DB_PREFIX . 'timeline WHERE relation_id IN (' . $ids . ') AND type="seekhelp"';
                $this->db->query($sql);
//                 $sql = 'DELETE c.*,j.* FROM ' . DB_PREFIX . 'comment c,' . DB_PREFIX . 'joint j WHERE c.id=j.cid AND j.joint_type="vice" AND c.cid IN (' . $ids . ')';
//                 $this->db->query($sql);
                $data = $ids;
            }
            else
            {
                $data = $this->sh->delete($ids);
            }
        }
        else
        {
            $data = $this->sh->delete($ids);
        }
       
        if($data)
        {
            $info = array('code'=>'0','msg'=>'success');
            $info['data'] = $data;
        }
        else
        {
           $info = array('code'=>'0','msg'=>'success');
           $info['data'] = $data;
        }

        $ids_arr = explode(",",$ids);
        $count = sizeof($ids_arr);
        //更新会员统计
        $this->updateMemberCount($member_id,'delete',$count);
        
    	$this->addLogs('删除互助信息',$seekhelps,'', '删除互助信息' . $ids);
    	foreach ($info as $key => $v)
    	{
    	    $this->addItem_withkey($key,$v);
    	}
    	$this->output();
	}
	
	/**
	 * 创建帖子的时间线
	 * @param unknown $relation_id
	 */
	private function SetTimeline($relation_id, $operation = 'create', $user_id = 0 ,$user_name = '')
	{
		if($operation == 'create')
        {
            $this->timeline->create(array(
                'type' => 'seekhelp',
                'relation_id' => $relation_id,
                'user_id' => $user_id ? $user_id : $this->user['user_id'],
                'user_name' => $user_name ? $user_name : $this->user['user_name'],
                'create_time' => TIMENOW,
            ));
        }
        elseif($operation == 'delete')
        {
            $this->timeline->delete($relation_id,'seekhelp', $user_id);
        }
	}
	
	/**
	* 会员帖子数量统计
	*/
	private function updateMemberCount($member_id, $operation = 'create', $count = 1)
	{
	    $mycountInfo = $this->members->getMycount($member_id);
	    $action = 'posts';
	    $res = array();
	    if(empty($mycountInfo))
	    {

	    }
	    else
	    {
	        $old_num = $mycountInfo[$action];
	        if($operation == 'create')
	        {
	            $new_num = $old_num + $count;
	        }
	        elseif ($operation == 'delete')
	        {
	            $new_num = $old_num - $count;
	        }
	        $res = $this->members->updateMycount($member_id, $action, $new_num);
	    }
	     
	    return $res;
	}
	
	/**
	 * 
	 * @Description 关注
	 * @author Kin
	 * @date 2013-6-15 上午09:22:36
	 */
	public function attention()
	{
		$id = intval($this->input['cid']); //求助信息的id
		$userInfor = $this->user;
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		if (!$userInfor['user_id'])
		{
			$this->errorOutput('获取用户信息失败');
		}
		$data = $this->sh->attention($id,$userInfor);
		if (!$data)
		{
			$this->errorOutput('关注失败');
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description 取消关注
	 * @author Kin
	 * @date 2013-6-15 上午09:22:50
	 */
	public function cancel_attention()
	{
		$cid = intval($this->input['cid']);  //求助id
		$member_id = $this->user['user_id'];
		if (!$cid || !$member_id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->sh->cancel_attention($cid, $member_id);
		$this->addItem($data);
		$this->output();
	}

    /**
     * 审核帖子状态
     */
    public function audit()
    {
        if(!$this->input['id'])
        {
            $this->errorOutput(NOID);
        }

        $status = intval($this->input['status']);
        if(!$status)
        {
            $this->errorOutput(NO_STATUS);
        }

        $data = array(
            'status' 		=> $status,
        );

        $ret = $this->sh->audit($this->input['id'],$status);

        $info = $this->sh->seekhelp_detail($this->input['id']);
        if($ret)
        {
            //$this->addLogs('审核','',$ret,'审核' . $this->input['id']);此处是日志，自己根据情况加一下
            if($data['status'] == 1)
            {
                $this->SetTimeline($info['id'],'create',$info['member_id'],$info['member_name']);
                $this->updateMemberCount($info['member_id'], 'create');
            }
            elseif($data['status'] == 2)
            {
                $this->SetTimeline($info['id'],'delete',$info['member_id'],$info['member_name']);
                $this->updateMemberCount($info['member_id'], 'delete');
            }

            $this->addItem($ret);
            $this->output();
        }
    }

    /**
     * 检测黑名单
     */
    private function check_black()
    {
        if($this->input['app_id'])
        {
            //检查社区黑名单
            $blackInfo = $this->blacklist->check_blackByappId($this->input['app_id']);
            if($blackInfo && $blackInfo['deadline'] == -1)
            {
                $this->errorOutput(SEEKHELP_IS_BLACK);
            }
        }
    }
	
}
$ouput= new seekhelpUpdateApi();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'create';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
