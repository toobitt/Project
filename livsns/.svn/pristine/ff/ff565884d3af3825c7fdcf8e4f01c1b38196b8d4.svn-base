<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp_update');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/recycle.class.php');
require_once(ROOT_PATH.'lib/class/members.class.php');
class seekhelpUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->sh = new ClassSeekhelp();
		$this->recycle = new recycle();
		$this->members = new members();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function publish(){}
	public function create()
	{
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.$this->input['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$status = 0;
		switch ($this->user['prms']['default_setting']['create_content_status'])
        {
        	case 0:
            {
            	$status = $this->settings['default_state'];
                break;
            }
            case 1:
            {
            	$status = 0;
                break;
            }
            case 2:
            {
            	$status = 1;
                break;
            }
        }
		$data = array(
				'title'  	  		=> trim($this->input['title']),
				'status'  	  		=> $status,
				'appid'  	  		=> $this->user['appid'],
				'appname' 	  		=> $this->user['display_name'],
				'section_id'        => intval($this->input['section_id']),
	 			'baidu_longitude' 	=> trim($this->input['baidu_longitude']),
	 			'baidu_latitude'  	=> trim($this->input['baidu_latitude']),
				'GPS_longitude'   	=> trim($this->input['GPS_longitude']),
	 			'GPS_latitude'    	=> trim($this->input['GPS_latitude']),
				'location'          => trim($this->input['location']),
	 			'sort_id'     		=> intval($this->input['sort_id']),
				'account_id'  		=> intval($this->input['account_id']),
				'org_id'	  		=> $this->user['org_id'],
				'user_id'	  		=> $this->user['user_id'],
				'tel'         		=> trim($this->input['tel']),
				'create_time' 		=> TIMENOW,
				'ip'          		=> $this->user['ip'],
		);
// 		if (!$data['title'])
// 		{
// 			$this->errorOutput('请输入求助内容');
// 		}
		
		//分类异常处理
		$data['sort_id'] = $this->sh->sortException($data['sort_id']);
		
		
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
		$content = trim($this->input['content']);
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
			$PhotoConfig = $this->sh->getPhotoConfig();
			
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
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
									'imgwidth'		=> $PhotoInfor['imgwidth'],
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
		//求助表数据
		$seek_data = array(
			'title' 		=> trim($this->input['title']),
			'sort_id' 		=> intval($this->input['sort_id']),
			'account_id'	=> intval($this->input['account_id']),
			'comment_id' 	=> $this->input['comment_id'],
			'section_id'    => intval($this->input['section_id']),	
			'is_recommend'	=> $this->input['comment_id'] ? 1 : 0,			
			);
		$seek_data['sort_id'] = $this->sh->sortException($seek_data['sort_id']);
		/**************权限控制开始**************/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		//节点权限
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN (' . $preData['sort_id']. ',' . $seek_data['sort_id'] . ')';
		$query = $this->db->query($sql);
		$sortInfo = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sortInfo[$row['id']] = $row['parents'];
		}
		//修改前
		if($preData['sort_id'])
		{
			$node['nodes'][$preData['sort_id']] = $sortInfo[$preData['sort_id']];
		}
		$this->verify_content_prms($node);
		
		//修改后
		if($seek_data['sort_id'])
		{
			$node['nodes'][$seek_data['sort_id']] = $sortInfo[$seek_data['sort_id']];
		}
		$this->verify_content_prms($node);	
		
		//能否修改他人数据
		$arr = array(
				'id'	  => $id,
				'user_id' => $preData['user_id'],
				'org_id'  => $preData['org_id'],
		);
		//$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		//验证是否有数据更新
		//主表
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'seekhelp SET ';
		foreach ($seek_data as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id; 
		$query = $this->db->query($sql);		
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//描述
		$sql = 'SELECT id FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$con_info = $this->db->query_first($sql);
		if (!$con_info['id'] && $this->input['content'])
		{
			$affected_rows = true;
		}
		if ($con_info['id'])
		{
			$sql = 'UPDATE '.DB_PREFIX.'content SET content = "'.addslashes($this->input['content']).'" WHERE id = '.$con_info['id'];
			$query = $this->db->query($sql);
			if ($this->db->affected_rows($query))
			{
				$affected_rows = true;
			}
		}
		//金牌回复
		if ($preData['reply_id'])
		{
			$sql = 'UPDATE '.DB_PREFIX.'reply SET content = "'.addslashes($this->input['gold_reply']).'" WHERE id = '.$preData['reply_id'];		
			$query = $this->db->query($sql);
			if ($this->db->affected_rows($query))
			{
				$affected_rows = true;
			}	
		}
		elseif ($seek_data['is_reply'])
		{
			$affected_rows = true;
		}
		
		//素材表
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE cid = '.$id.' AND rid > 0';
		$query = $this->db->query($sql);
		$preMaterials = array();
		$temp = array();
		while ($row = $this->db->fetch_array($query))
		{
			$preMaterials[] = $row['id'];
			$temp[$row['id']] = $row['flag'];
		}
		$reply_pic = $this->input['reply_pic'] ? $this->input['reply_pic'] : array();
		$reply_vod = $this->input['reply_vod'] ? $this->input['reply_vod'] : array();
		$material = array_merge($reply_pic,$reply_vod);
		if (!empty($preMaterials))
		{
			//取交集判断是否有标识的数据
			$temp1 = array_intersect($preMaterials, $material);

			if (!empty($temp1))
			{
				foreach ($temp1 as $val)
				{
					if ($temp[$val])
					{
						$affected_rows = true;
					}
				}
			}
			else 
			{
				$affected_rows = true;
			}
			//取差集是否存标识为0的被删除,无差集判断是否素材是否有标识的数据
			$temp2 = array_diff($preMaterials, $material);
			if (!empty($temp2))
			{
				foreach ($temp2 as $val)
				{
					if (!$temp[$val])
					{
						$affected_rows = true;
					}
				}
			}else 
			{
				foreach ($material as $val)
				{
					if ($temp[$val])
					{
						$affected_rows = true;
					}
				}
			}
			//将flag标识置0
			$sql = 'UPDATE '.DB_PREFIX.'materials SET flag = 0 WHERE cid = '.$id;
			$this->db->query($sql);
		}
		
		if ($affected_rows)
		{
			$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
			);
			/**************权限控制开始**************/
			//修改审核数据后的状态
			if ($preData['status']==1 && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_audit_content']==1)
				{
					$additionalData['status'] = 0;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
				{
					$additionalData['status'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
				{
					$additionalData['status'] = 2;
				}
			}
			$seek_data = array_merge($seek_data,$additionalData);
			/**************权限控制结束**************/
			$res = array_merge($preData, $seek_data);
			//添加日志
			$this->addLogs('更新互助', $preData, $res, $preData['title'], $preData['id'], $preData['sort_id']);
		}
		$ret = $this->sh->update($id,$seek_data,$reply_pic,$reply_vod,$this->input['content'],$this->input['gold_reply'],$this->user);
		if($ret)
		{
			//更新评论所属分类id
			if($preData['sort_id'] != $seek_data['sort_id'])
			{
				$sql = "UPDATE " . DB_PREFIX . "comment SET sort_id = " . $seek_data['sort_id'] . " WHERE cid = " . $id;
				$this->db->query($sql);
			}
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
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
		
		//节点权限验证
		/*if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
				if (!empty($nodes))
				{
					$this->verify_content_prms($nodes);
				}
			}
		}*/
		//能否修改他人数据
		if (!empty($seekhelps) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($seekhelps as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		
		
		
		//回收站数据整理，此处只删除了评论
		$sql = 'SELECT * FROM '.DB_PREFIX.'comment WHERE cid IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$recycle[$row['cid']]['content']['comment'][$row['id']] = $row;
		}
		
		//放入回收站
		if ($this->settings['App_recycle'] && !empty($recycle))
		{			
			foreach ($recycle as $infor)
			{
				$ret = $this->recycle->add_recycle($infor['title'], $infor['delete_people'], $infor['cid'], $infor['content']);
				$result = $ret['sucess'];
				$is_open = $ret['is_open'];
			}
			if (!$result)
			{
				$this->errorOutput('删除失败，数据不完整');
			}
			if ($is_open)
			{
				//删除主表和评论表
				$sql = 'DELETE FROM ' . DB_PREFIX . 'seekhelp WHERE id IN (' . $ids . ')';
				$this->db->query($sql);
// 				$sql = 'DELETE FROM ' . DB_PREFIX . 'content WHERE id IN (' . $ids . ')';
// 				$this->db->query($sql);
// 				$sql = 'DELETE FROM ' . DB_PREFIX . 'materials WHERE cid IN (' . $ids . ')';
// 				$this->db->query($sql);
				$sql = 'DELETE FROM ' . DB_PREFIX . 'timeline WHERE relation_id IN (' . $ids . ') AND type="seekhelp"';
				$this->db->query($sql);
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
		
		$this->updateMemberCount($this->user['user_id'], 'delete');
		$this->addLogs('删除互助信息',$seekhelps,'', '删除互助信息' . $ids);	
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description  彻底删除回调
	 * @author Kin
	 * @date 2013-7-17 下午03:43:06
	 */
	public function delete_comp()
	{
		$ids = $this->input['cid'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->sh->delete($ids);
		$this->addItem($data);
		$this->output();
	}
	
	public function audit()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		//节点权限验证
		$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$pre_data = array();
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$pre_data[] = $row;	
		}
		if (!empty($sorts))
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		//$this->verify_content_prms($nodes);
		
		$data = array();
		$status = intval($this->input['status']);
		if(isset($this->settings['seekhelp_status']))
		{
			$data = $this->sh->audit($ids,$status);
		
			//添加日志
			$new_data = array();
			if ($status == 1)
			{
				if (!empty($pre_data))
				{
					foreach ($pre_data as $key=>$val)
					{
						$val['status'] = 1;
						$new_data[$key] = $val;
					}
				}
				$this->addLogs('审核互助', $pre_data, $new_data,'审核互助'.$ids);
			}
			if ($status == 2)
			{
				if (!empty($pre_data))
				{
					foreach ($pre_data as $key=>$val)
					{
						$val['status'] = 2;
						$new_data[$key] = $val;
					}
				}
				$this->addLogs('打回互助', $pre_data, $new_data,'打回互助'.$ids);
			}
		}
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description 推送至正在帮办
	 * @author Kin
	 * @date 2013-6-20 下午05:03:18
	 */
	public function push()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		//节点权限验证
		$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$pre_data = array();
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$pre_data[] = $row;	
		}
		if (!empty($sorts))
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'audit';
		$this->verify_content_prms($nodes);
		
		$status = intval($this->input['status']);
		$status = ($status==1) ? $status : 0;
		$data = $this->sh->push($ids,$status);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description  图片上传
	 * @author Kin
	 * @date 2013-7-4 下午03:55:06
	 */
	public function upload_img()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		//节点权限验证
		$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$pre_data = array();
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$pre_data[] = $row;	
		}
		if (!empty($sorts))
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'update';
		$this->verify_content_prms($nodes);
		
		//上传图片
		if($_FILES['Filedata'])
		{
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装！');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			$img_data = array(
				'host' 			=> $img_info['host'],
				'dir' 			=> $img_info['dir'],
				'filepath' 		=> $img_info['filepath'],
				'filename' 		=> $img_info['filename'],
			);
			
			$data = $img_data;
			$data['cid'] 			= $this->input['id'];//求助的id
			$data['original_id'] 	= $img_info['id'];
			$data['type'] 			= $img_info['type'];
			$data['mark'] 			= 'img';
			$data['imgwidth'] 		= $img_info['imgwidth'];
			$data['imgheight'] 		= $img_info['imgheight'];
			$data['flag']			= 1;
			
			$vid = $this->sh->insert_img($data,$this->user);
			if($vid)
			{
				$this->addItem(array('id' => $vid,'img' => hg_fetchimgurl($img_data,100)));
				$this->output();
			}
		}
	}
	
	/**
	 * 
	 * @Description  视频上传
	 * @author Kin
	 * @date 2013-7-5 下午03:05:41
	 */
	public function upload_video()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//节点权限验证
		$sql = 'SELECT * FROM '.DB_PREFIX.'seekhelp WHERE id IN ('.$id.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$pre_data = array();
		$nodes = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$pre_data[] = $row;	
		}
		if (!empty($sorts))
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$nodes['_action'] = 'update';
		$this->verify_content_prms($nodes);
		
		//上传视频
		if($_FILES['videofile'])
		{
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput('视频服务器未安装！');
			}
			
			$vodInfor = $this->sh->uploadToVideoServer($_FILES);
			if (!$vodInfor)
			{
				$this->errorOutput('视频上传失败');
			}
			
			$img_data = array(
				'host' 			=> $vodInfor['img']['host'],
				'dir' 			=> $vodInfor['img']['dir'],
				'filepath' 		=> $vodInfor['img']['filepath'],
				'filename' 		=> $vodInfor['img']['filename'],
				'imgwidth' 		=> $vodInfor['img']['imgwidth'],
				'imgheight' 	=> $vodInfor['img']['imgheight'],
			);
			$data['cid'] 			= $this->input['id'];//求助的id
			$data['original_id'] 	= $vodInfor['id'];
			$data['host']			= $vodInfor['protocol'].$vodInfor['host'];
			$data['dir'] 			= $vodInfor['dir'];
			$data['filename'] 		= $vodInfor['file_name'];
			$data['type'] 			= $vodInfor['type'];
			$data['mark'] 			= 'video';
			$data['flag']			= 1;
			$arr = explode('.', $data['filename']);
			$type = $arr[1];
			$vod_url = $data['host'].'/'.$data['dir'].str_replace($type, 'm3u8', $data['filename']);
			$vid = $this->sh->insert_video($data,$this->user);
			if($vid)
			{
				$this->addItem(array('id' => $vid,'img' => hg_fetchimgurl($img_data,100), 'vod_url' => $vod_url));
				$this->output();
			}
		}
	}
	
	public function sort()
	{
		$this->addLogs('更改报料排序', '', '', '更改报料排序');
		$ret = $this->drag_order('seekhelp', 'order_id');
		$this->addItem($ret);
		$this->output();
	}
	
	//计划任务审核
	public function planAudit()
	{
		
		$start_time = intval($this->input['start_time']);
		$end_time = intval($this->input['end_time']);
		$status = intval($this->input['status']);
		$state = '';
		if ($start_time && $end_time && $status)
		{
			switch ($status)
			{
				case 1:$state = 0;break;
				case 2:$state = 1;break;
				case 3:$state = 2;break;
			}
			$sql = 'UPDATE '.DB_PREFIX.'seekhelp SET status = '.$state.' 
					WHERE status = 0 AND banword = "" 
					AND is_img = 0 
					AND is_video = 0 
					AND create_time>'.$start_time.' AND create_time<'.$end_time;
			$this->db->query($sql);
		}
		$this->addItem(true);
		$this->output();
		
	}
	
	/**
	 * 会员帖子数量统计
	 */
	private function updateMemberCount($member_id, $operation)
	{
	    $mycountInfo = $this->members->getMycount($member_id);
	    $action = 'posts';
	    if(empty($mycountInfo))
	    {
            $res = array();
	    }
	    else
	    {
	        $old_num = $mycountInfo[$action];
	        if($operation == 'create')
	        {
	            $new_num = $old_num + 1;
	        }
	        elseif ($operation == 'delete')
	        {
	            $new_num = $old_num - 1;
	        }
	        $res = $this->members->updateMycount($member_id, $action, $new_num);
	    }
	
	    return $res;
	}
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new seekhelpUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();