<?php
require_once('./global.php');
require_once(ROOT_PATH . 'lib/class/opinion.class.php');
require_once(CUR_CONF_PATH . 'lib/contribute.class.php');
include_once(ROOT_PATH . 'lib/class/recycle.class.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
include_once(ROOT_PATH . 'lib/class/archive.class.php');
define('MOD_UNIQUEID','contribute');//模块标识
class contribute_update extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->recycle = new recycle();
		$this->opinion = new opinion();
		$this->contribute = new contribute();
		$this->publish_column = new publishconfig();
		$this->archive = new archive();
	}


	public function __destruct()
	{
		parent::__destruct();
	}


	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		//爆料信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$conInfor  = array();
		$colunmIds = array();
		$sorts 	   = array();
		$claimIds = array();
		while($row = $this->db->fetch_array($query))
		{
			$result[]		      = $row['id'];
			$sorts[] 		   	  = $row['sort_id'];
			$conInfor[$row['id']] = $row;

			//回收站数据
			$data2[$row['id']] = array(
									'delete_people' => trim($this->user['user_name']),
									'title' 		=> $row['title'],
									'cid' 			=> $row['id'],
			);
			$data2[$row['id']]['content']['content'] = $row;

			//准备删除发布系统上的数据
			$colunm_id = unserialize($row['column_id']);
			if (!empty($colunm_id) && is_array($colunm_id) )
			{
				$colunmIds[$row['id']] = array_keys($colunm_id);
			}
			//获取所有认领部门
			$claimIds[] = $row['claim_org_id'];
		}


		/**************权限控制开始**************/
		//节点验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sorts = array_filter($sorts);
			if (!empty($sorts))
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
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
		//验证认领部门
		$claimIds = array_filter($claimIds);
		if (!empty($claimIds) && CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$userOrgId = $this->user['org_id'];
			$checkClaimIds = array_diff($claimIds, array($userOrgId));
			if (!empty($checkClaimIds))
			{
				$this->errorOutput('报料已被其他部门认领，无法删除');
			}
		}
		//内容表
		$sql = "SELECT * FROM " . DB_PREFIX . "contentbody WHERE id IN (" . $ids .")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$data2[$row['id']]['content']['contentbody'] = $row;
		}

		//素材表
		$sql = 'SELECT * FROM '.DB_PREFIX.'materials WHERE content_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$data2[$row['content_id']]['content']['materials'][$row['materialid']] = $row;
		}

		//用户信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content_user WHERE con_id IN ('.$ids.')';
		$query = $this->db->query($sql);
		while ($row = $this->db->fetch_array($query))
		{
			$data2[$row['con_id']]['content']['content_user'] = $row;
		}
		if(is_array($data2) && count($data2) && $this->settings['App_recycle'])
		{
			//放入回收站
			foreach($data2 as $key => $value)
			{
				$res = $this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);

			}
		}
		//删除发布中心数据
		if (!empty($colunmIds) && is_array($colunmIds))
		{
			foreach ($colunmIds as $key=>$value)
			{
				$op = 'delete';
				$this->contribute->publish_insert_query($key, $op, $value);
			}
		}
		//删除转发数据
		$this->contribute->del_send_contribute($ids);
		//删除信息表
		$this->contribute->del_content($ids);
		//删除内容表
		$this->contribute->del_contentbody($ids);
		//删除素材表
		$this->contribute->del_materials($ids);
		//删除用户信息表
		$this->contribute->del_con_user($ids);
		//添加日志
		if (!empty($conInfor))
		{
			$this->addLogs('删除报料', $conInfor,'','删除报料'.$ids);
		}
		$this->addItem('sucess');
		$this->output();
	}


	//彻底删除
	public function delete_comp()
	{
		return true;
	}

    public function opinion()
    {
        $id = intval($this->input['id']);
        if(!$id){
            $this->errorOutput(NOID);
        }
        $sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id ='.$id;
        $ret = $this->db->query_first($sql);
        if(!$ret){
            $this->errorOutput(OPINION_NO);
        }
        /***权限开始***/

        //能否修改他人数据
        $userInfor = array(
            'id'	  => $id,
            'user_id' => $ret['user_id'],
            'org_id'  => $ret['org_id'],
        );
      //  $this->verify_content_prms($userInfor);

        //节点验证
        if ($ret['sort_id'])
        {
            $sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
            $sortInfor = $this->db->query_first($sql);
        }
        if ($sortInfor)
        {
            $node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
        }
        $node['_action'] = 'manage';
        $this->verify_content_prms($node);
        /***权限结束***/
        $opinion = addslashes(trim($this->input['opinion']));
        if($opinion != $ret['opinion']){
            $sql = 'UPDATE '.DB_PREFIX.'content SET opinion = "'. $opinion .'" WHERE id = '.$id;
            $query = $this->db->query($sql);
            if(!$this->db->affected_rows($query)){
                $this->errorOutput(OPINION_ERR);
            }
            //添加日志
            $this->addLogs('添加爆料审核意见', $ret, array('opinion' => $opinion) + $ret, $ret['title'],$ret['id'],$ret['sort_id']);
        }
        $this->addItem('success');
        $this->output();
    }

	public function update()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$content = trim($this->input['content']);
		if (!$content)
		{
			$this->errorOutput('请输入报料内容');
		}

		//查询修改文章之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."content WHERE id =" . $id;
		$ret = $this->db->query_first($sql);
		if (!$ret)
		{
			$this->errorOutput(NO_DATA);
		}
		//添加部门认领功能
		if (CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE && $this->user['org_id']!= $ret['claim_org_id'] && $ret['claim_org_id'])
		{
			$this->errorOutput('该报料已被其他部门认领');
		}


		$ret['column_id'] = unserialize($ret['column_id']);
		$old_column_id = array();
		if(is_array($ret['column_id']))
		{
			$old_column_id = array_keys($ret['column_id']);
		}

		$data = array(
					'title'			=> addslashes(trim($this->input['title'])),
					'sort_id'   	=> intval($this->input['sort_id']),
					'brief'	    	=> addslashes(trim($this->input['brief'])),
					'column_id' 	=> $this->input['column_id'],
					'user_id'   	=> $this->input['user_id'],
					'user_name' 	=> addslashes(trim($this->input['user_name'])),
					'opinion'		=> addslashes(trim($this->input['opinion'])),
					'publish_time' 	=> $this->input['pub_time'],
					'baidu_longitude' => trim($this->input['baidu_longitude']),
	 				'baidu_latitude'  => trim($this->input['baidu_latitude']),
					'event_time'  => strtotime($this->input['event_time']),
					'event_address'=> trim($this->input['event_address']),
					'event_suggest'=> trim($this->input['event_suggest']),
					'event_user_name'=> trim($this->input['event_user_name']),
					'event_user_tel'=> trim($this->input['event_user_tel']),
					'is_follow' 	=> $this->input['is_follow'],
					'audit'   	=> intval($this->input['audit']),
		);
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->contribute->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
			$data['longitude'] = $gps['GPS_x'];
			$data['latitude'] = $gps['GPS_y'];
		}
		$data['title'] = $data['title'] ? $data['title'] : addslashes(hg_cutchars($this->input['content'],20));
		$data['brief'] = $data['brief'] ? $data['brief'] : addslashes(hg_cutchars($this->input['content'],100));
		$data['publish_time'] = $data['publish_time'] ? strtotime($data['publish_time']) : TIMENOW;
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$data['column_id'] = !empty($column_id) ? addslashes(serialize($column_id)) : "";
		
		/**************权限控制开始**************/
		//节点权限
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN (' . $ret['sort_id']. ',' . $data['sort_id'] . ')';
		$query = $this->db->query($sql);
		$sortInfo = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sortInfo[$row['id']] = $row['parents'];
		}
		//修改前
		if($ret['sort_id'])
		{
			$node['nodes'][$ret['sort_id']] = $sortInfo[$ret['sort_id']];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);

		//修改后
		if($data['sort_id'])
		{
			$node['nodes'][$data['sort_id']] = $sortInfo[$data['sort_id']];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);

        //管理员是否回复
        if($this->input['opinion'] != "" )
        {    
            if($ret['is_opinion'] != 1)
            { 
                $sql = 'UPDATE '.DB_PREFIX.'content SET is_opinion=1 where id  = '.$id; 
                $this->db->query_first($sql);
	            $sql = 'SELECT * FROM '.DB_PREFIX.'sort  where id = '.$data['sort_id'] ;
	            $res = $this->db->query_first($sql);
	            $space = '';
		        if($res)
		        {   
			         $sql = 'UPDATE '.DB_PREFIX.'sort SET opinion_score = opinion_score +1  where id in ( '.$res['parents'].')';
		             $this->db->query($sql);
		        }
		    }            
		    if ($ret['opinion'] != $this->input['opinion'])
            {
               $this->send_notify($ret['user_id'], $id);
            }
        }

		//发布权限
		$published_column_id = !empty($old_column_id) ? implode(',', $old_column_id) : '';
		$arr = array(
			'column_id'=> $this->input['column_id'],
			'_action'=>'manage',
			'published_column_id'=>$published_column_id,
		);
		$this->verify_content_prms($arr);

		//能否修改他人数据
		$arr = array(
				'_action' => 'manage',
				'id'	  => $id,
				'user_id' => $ret['user_id'],
				'org_id'  => $ret['org_id'],
		);
		//$this->verify_content_prms($arr);
		/**************权限控制结束**************/

		//验证是否有数据更新
		//主表
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'content SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id;
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//内容表
		$sql = 'UPDATE '.DB_PREFIX.'contentbody SET text = "'.addslashes($content).'" WHERE id = '.$id;
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//用户信息
		$userinfo = array(
      		'con_id'	=> $id,
      		'tel'		=> addslashes($this->input['tel']),
      		'email'		=> addslashes($this->input['email']),
      		'addr'		=> addslashes($this->input['addr']),
			'money'		=> addslashes($this->input['money']),
      		'is_bounty' => $this->input['is_bounty'] ? 1 : 0,
		);
		$sql = 'REPLACE INTO '.DB_PREFIX.'content_user SET ';
		foreach ($userinfo as $key=>$val)
		{
			$sql.= $key.'="'.$val.'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
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
			//修改发布后数据的状态
			if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_publish_content']==1)
				{
					$additionalData['audit'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
				{
					$additionalData['audit'] = 2;
				}
				elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
				{
					$additionalData['audit'] = 3;
				}
			}
			//修改审核数据后的状态
			elseif ($ret['audit']==2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_audit_content']==1)
				{
					$additionalData['audit'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
				{
					$additionalData['audit'] = 2;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
				{
					$additionalData['audit'] = 3;
				}
			}

			$this->contribute->update_content($additionalData, $id);
			/**************权限控制结束**************/
			$res = array_merge($ret, $data, $additionalData);
			//添加日志
			$this->addLogs('更新报料', $ret, $res, $ret['title'], $ret['id'], $ret['sort_id']);

		}
		//检测视频库状态
		/*if (!$this->settings['App_livmedia'])
		{
			$this->errorOutput('视频服务器未安装!');
		}*/
		//更新视频库的视频状态
		$audit = $additionalData['audit'] ? $additionalData['audit'] : $ret['audit'];
		switch ($audit)
		{
			case 2:$this->contribute->video_audit($id, 1);break;
			case 3:$this->contribute->video_audit($id, 0);break;
			default:$this->contribute->video_audit($id, 0);
		}
		/*
		 //更新爆料主表
		 $this->contribute->update_content($additionalData, $id);
		 //爆料内容表
		 $content = addslashes($content);
		 $this->contribute->update_contentbody($content, $id);
		 //更新用户信息
		 $this->contribute->user_info($userinfo);
		 */
		$new_column_id = array();
		if ($this->input['column_id'])
		{
			$new_column_id = explode(',', $this->input['column_id']);
		}
		//准备更改发布内容
		if($audit == 2)
		{
			//更新转发数据
			$this->contribute->update_send_contribute($id);

			if(!empty($ret['expand_id']))
			{
				//已经发布过，对比修改先后栏目
				$del_column = array_diff($old_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->contribute->publish_insert_query($id, 'delete', $del_column, $data['publish_time'], $this->user);
				}
				$add_column = array_diff($new_column_id,$old_column_id);
				if(!empty($add_column))
				{
					$this->contribute->publish_insert_query($id, 'insert',$add_column, $data['publish_time'], $this->user);
				}
				$same_column = array_intersect($old_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->contribute->publish_insert_query($id, 'update',$same_column, $data['publish_time'], $this->user);
				}
			}
			elseif (!empty($new_column_id))
			{
				//未发布，直接插入最新栏目
				$op = "insert";
				$this->contribute->publish_insert_query($id,$op ,$new_column_id, $data['publish_time'], $this->user);
			}
		}
		else
		{
			//删除报料的已经发布的数据
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				$this->contribute->publish_insert_query($id, $op, $old_column_id, $data['publish_time'], $this->user);
			}
		}
		$this->addItem('sucess');
		$this->output();
	}


	public function create()
	{
		$content = addslashes(trim($this->input['content']));
		if (!$content)
		{
			$this->errorOutput('请输入报料内容');
		}
		$data = array(
					'title'  	  => addslashes(trim($this->input['title'])),
					'brief' 	  => addslashes(trim($this->input['brief'])),
					'appid'  	  => $this->user['appid'],
					'client' 	  => $this->user['display_name'],
					'audit'		  => 1,
		 			'sort_id'     => intval($this->input['sort_id']), 
					'org_id'	  => $this->user['org_id'],
					'is_m2o'      => 1,
					'user_id'	  => $this->input['user_name'] ? 0 : $this->user['user_id'],
					'user_name'	  => $this->input['user_name'] ? addslashes($this->input['user_name']) : addslashes($this->user['user_name']),			 	
					'create_time' => TIMENOW,
					'ip'          => $this->user['ip'],
					'baidu_longitude' => trim($this->input['baidu_longitude']),
	 				'baidu_latitude'  => trim($this->input['baidu_latitude']),
					'event_time'  => strtotime($this->input['event_time']),
					'event_address'=> trim($this->input['event_address']),
					'event_suggest'=> trim($this->input['event_suggest']),
					'event_user_name'=> trim($this->input['event_user_name']),
					'event_user_tel'=> trim($this->input['event_user_tel']),
					'is_follow'=>  intval($this->input['is_follow']),
		);
		if (!$data['sort_id'])
		{
			$data['sort_id'] = 0;
		}
		if (!$data['title'])
		{
			$data['title'] = hg_cutchars($content,20);
		}
		if (!$data['brief'])
		{
			$data['brief'] = hg_cutchars($content,100);
		}
		//如果百度坐标存在的话，就转换为GPS坐标也存起来
		if($data['baidu_longitude'] && $data['baidu_latitude'])
		{
			$gps = $this->contribute->FromBaiduToGpsXY($data['baidu_longitude'],$data['baidu_latitude']);
			$data['GPS_longitude'] = $gps['GPS_x'];
			$data['GPS_latitude'] = $gps['GPS_y'];
		}
		/**************权限控制开始**************/
		//节点权限
		if($data['sort_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id = '.$data['sort_id'];
			$sort = $this->db->query_first($sql);
			$nodes['nodes'][$sort['id']] = $sort['parents'];
		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		//创建数据后的审核状态
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['create_content_status']==1)
			{
				$data['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['create_content_status']==2)
			{
				$data['audit'] = 2;
			}
		}
		/**************权限控制结束**************/
		//添加爆料主表
		$contributeId = $this->contribute->add_content($data);
		if (!intval($contributeId))
		{
			$this->errorOutput('数据库插入失败');
		}
		//添加内容表
		$body = array(
			'id'   => $contributeId,
			'text' => $content,
		);
		$this->contribute->add_contentbody($body);
		$userinfo = array();
		//用户信息
		$userinfo['con_id'] = intval($contributeId);
		$userinfo['tel'] = addslashes($this->input['tel']) ;
		$userinfo['email'] = addslashes($this->input['email']);
		$userinfo['addr'] = addslashes($this->input['addr']);
		if (!empty($userinfo))
		{
			$this->contribute->user_info($userinfo);
		}
		
		//视频上传
		if ($_FILES['videofile'])
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
			
			$count = count($_FILES['videofile']['name']);
			for($i = 0; $i <= $count; $i++)
			{
				if ($_FILES['videofile']['name'][$i])
				{
					if ($_FILES['videofile']['error'][$i]>0)
					{
						$this->errorOutput('视频异常');
					}
					$filetype = '';
					$filetype = strtolower(strrchr($_FILES['videofile']['name'][$i], '.'));
					if (!in_array($filetype, $videoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$videoConfig['hit'].'格式的视频');
					}
					foreach($_FILES['videofile'] AS $k =>$v)
					{
						$video['videofile'][$k] = $_FILES['videofile'][$k][$i];
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
					$videodata = $this->contribute->uploadToVideoServer($val, $data['title'], $data['brief']);
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
				$this->errorOutput('图片服务器未安装!');
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
						$this->errorOutput('图片异常');
					}
					if (!in_array($_FILES['photos']['type'][$i], $PhotoConfig['type']))
					{
						$this->errorOutput('只允许上传'.$PhotoConfig['hit'].'格式的图片');
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
					$PhotoInfor = $this->contribute->uploadToPicServer($val, $contributeId);
					if (empty($PhotoInfor))
					{
						$this->errorOutput('图片服务器错误!');
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
		
		/**************权限控制开始**************/
		if ($data['audit'] == 2){
			$this->contribute->send_contribute($contributeId);
			//审核视频
			$this->contribute->video_audit($contributeId, 1);
		}
		/**************权限控制结束**************/
		//添加日志
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$contributeId;
		$ret = $this->db->query_first($sql);
		$this->addLogs('添加报料', '', $ret, $ret['title'], $contributeId, $ret['sort_id']);
		$this->addItem($contributeId);
		$this->output();
	}

	/**
	 *
	 * @Description 改变单条审核状态,1-未审核,2-审核
	 * @author Kin
	 * @date 2013-4-15 下午05:46:04
	 */
	public function stateAudit()
	{
		$status = intval($this->input['audit']);	//审核前的状态
		$id = intval($this->input['id']);			//报料id
		$state = 0;									//审核后的状态
		$node = array();							//节点
		$claimId = '';
		if (!$status || !$id)
		{
			$this->errorOutput(NOID);
		}
		/*if (!$this->settings['App_livmedia'])
		{
			$this->errorOutput('视频服务器未安装！无法审核视频');
		}*/
		//查询原始数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$claimId = $ret['claim_org_id'];
		if ($claimId && CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE && $claimId != $this->user['org_id'])
		{
			$this->errorOutput('该报料已被其他部门认领');
		}
		//存在发布栏目时验证发布权限
		$column_id = unserialize($ret['column_id']);
		if ($column_id)
		{
			$column_id = implode(',', array_keys($column_id));
			//发布权限
			$pub = array(
				'_action'=>'publish',
				'column_id'=>$column_id,
				'published_column_id'=>$column_id,
			);
			$this->verify_content_prms($pub);
		}
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}
		if ($status == 1 || $status == 3)
		{
			//验证审核节点权限
			if ($sortInfor)
			{
				$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
			}
			$node['_action'] = 'manage';
			$this->verify_content_prms($node);
			//验证修改他人数据权限
			/*
			 $authUser = array(
				'_action'	=> 'manage',
				'id'		=> $ret['id'],
				'user_id'	=> $ret['user_id'],
				'org_id'	=> $ret['org_id'],
				);
				$this->verify_content_prms($authUser);
				*/
			//审核后数据转发
			$this->contribute->send_contribute($id);
			//审核视频
			$this->contribute->video_audit($id, 1);
			$state = 2;
		}
		if ($status == 2)
		{
			//验证打回节点权限
			if ($sortInfor)
			{
				$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
			}
			$node['_action'] = 'manage';
			$this->verify_content_prms($node);
			//验证修改他人数据权限
			/*
			 $authUser = array(
				'_action'	=> 'back',
				'id'		=> $ret['id'],
				'user_id'	=> $ret['user_id'],
				'org_id'	=> $ret['org_id'],
				);
				*/
			//打回后删除转发数据
			$this->contribute->del_send_contribute($id);
			//打回视频
			$this->contribute->video_audit($id, 0);
			$state = 3;
		}
		if (!$state)
		{
			$this->errorOutput('改变审核状态失败！');
		}
		$this->contribute->changeAudit($state, $id);

		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$res = $this->db->query_first($sql);
		/*******************调用积分规则,给已审核评论增加积分START*****************/

		if($res['new_member']&&$res['user_id']&&empty($res['is_credits'])&&empty($res['is_m2o']))//是启用新会员系统并且审核通过是给积分的.
		{
			if($this->settings['App_members'])
			{
				include (ROOT_PATH.'lib/class/members.class.php');
				$Members = new members();
				/***审核增加积分**/
					$Members->Setoperation(APP_UNIQUEID,'','','extra');
					$Members->get_credit_rules($res['user_id'],APP_UNIQUEID);
					$this->db->query("UPDATE " . DB_PREFIX . "content SET is_credits=1 WHERE id=".$id);//更新获得积分字段
			}
		}
		/********************调用积分规则,给已审核评论增加积分END*****************/
		$res['column_id'] = unserialize($res['column_id']);
		$column_id = array();
		if(is_array($res['column_id']))
		{
			$column_id = array_keys($res['column_id']);
		}
		if ($state == 2)
		{
			//审核同时进行发布
			if (!empty($column_id))
			{
				if (!empty($ret['expand_id']))
				{
					$op = "update";
				}
				else
				{
					$op = "insert";
				}
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user);
			}
			//添加日志
			$this->addLogs('审核报料', $ret, $res, $ret['title'], $id, $ret['sort_id']);
		}
		if ($state == 3)
		{
			if (!empty($ret['expand_id']))
			{
				$op = 'delete';
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user);
			}
			//添加日志
			$this->addLogs('打回报料', $ret, $res, $ret['title'], $id, $ret['sort_id']);
		}
		$this->addItem($state);
		$this->output();
	}

	/**
	 *
	 * @Description 批量审核
	 * @author Kin
	 * @date 2013-5-4 上午10:54:45
	 * @see adminUpdateBase::audit()
	 */
	public function audit()
	{
		$ids = $this->input['id'];		//报料id
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		/*if (!$this->settings['App_livmedia'])
		{
			$this->errorOutput('视频服务器未安装！');
		}*/
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$userInfor = array();
		$sorts = array();
		$column_id = '';
		$claimIds = array();
		$credit_rules_uid=array();//可以增加积分的会员id
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$userInfor[] = $row;
			$temp = unserialize($row['column_id']);
			if ($temp)
			{
				$column_id .= implode(',', array_keys($temp)).',';
			}
			//认领
			$claimIds[] = $row['claim_org_id'];

			if($row['user_id']&&empty($row['is_credits'])&&empty($row['is_m2o'])&&$row['new_member'])//是否审核增加积分判断
			{
				$credit_rules_uid[$row['id']]=$row['user_id'];
			}
		}
		//发布权限
		if ($column_id)
		{
			$column_id = rtrim($column_id, ',');
			$pub = array(
				'_action'				=> 'publish',
				'column_id'				=> $column_id,
				'published_column_id'  	=> $column_id,
			);
			$this->verify_content_prms($pub);
		}
		$nodes = array();
		//节点权限
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}

		}
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		//能否修改他人数据
		/*
		 if (!empty($userInfor))
		 {
			foreach ($userInfor as $val)
			{
			$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
			}
			*/
		/**************权限控制结束**************/
		//验证认领部门
		$claimIds = array_filter($claimIds);
		if (!empty($claimIds) && CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$userOrgId = $this->user['org_id'];
			$checkClaimIds = array_diff($claimIds, array($userOrgId));
			if (!empty($checkClaimIds))
			{
				$this->errorOutput('报料已被其他部门认领，无法审核');
			}
		}


		$ret = $this->contribute->audit($ids);
		if (!$ret)
		{
			$this->errorOutput('审核失败!');
		}
		/*******************调用积分规则,给已审核评论增加积分START*****************/

			if($this->settings['App_members'])
			{
				include (ROOT_PATH.'lib/class/members.class.php');
				$Members = new members();
					
				/***审核增加积分**/
				if($credit_rules_uid)//审核增加积分为真&&已审核状态&&有user_id
				{
					$Members->Setoperation(APP_UNIQUEID,'','','extra');
					if(is_array($credit_rules_uid))
					{
						foreach ($credit_rules_uid as $key => $member_id)
						{
							$Members->get_credit_rules($member_id,APP_UNIQUEID);
							$this->db->query("UPDATE " . DB_PREFIX . "content SET is_credits=1 WHERE id=".$key);//更新获得积分字段
						}
					}
				}
			}
		/********************调用积分规则,给已审核评论增加积分END*****************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$result  = array();
		while ($row = $this->db->fetch_array($query))
		{
			//改变审核状态控制发布状态
			$column_id = '';
			$column_id = unserialize($row['column_id']);

			if ($column_id && !empty($column_id))
			{
				if ($row['expand_id'])
				{
					$op = "update";
				}
				else
				{
					$op = "insert";
				}
				$column_id = array_keys($column_id);
				$this->contribute->publish_insert_query($row['id'], $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
			$result[] = $row;
		}
		//添加日志
		$this->addLogs('审核报料',$userInfor, $result, '审核报料'.$ids);
		//审核后进行数据转发
		$this->contribute->send_contribute($ids);
		//审核视频
		$this->contribute->video_audit($ids, 1);
		$arr = explode(',', $ret);
		$this->addItem($arr);
		$this->output();
	}

	/**
	 *
	 * @Description 批量打回
	 * @author Kin
	 * @date 2013-5-4 上午10:11:26
	 */
	public function back()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		/*if (!$this->settings['App_livmedia'])
		{
			$this->errorOutput('视频服务器未安装！');
		}*/
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$userInfor = array();
		$column_id = '';
		$claimIds = array();

		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$userInfor[] = $row;
			$temp = unserialize($row['column_id']);
			if ($temp)
			{
				$column_id .= implode(',', array_keys($temp)).',';
			}
			//认领
			$claimIds[] = $row['claim_org_id'];
		}
		//验证发布权限
		if ($column_id)
		{
			$column_id = rtrim($column_id, ',');
			$pub = array(
				'_action'=>'publish',
				'column_id'=>$column_id,
				'published_column_id'=>$column_id,
			);
			$this->verify_content_prms($pub);
		}
		//节点权限验证
		if($sorts)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id IN ('.implode(',',$sorts).')';
			$query = $this->db->query($sql);
			$nodes = array();
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
			$nodes['_action'] = 'manage';
			$this->verify_content_prms($nodes);

		}
		//能否修改他人数据
		/*
		 if (!empty($userInfor))
		 {
			foreach ($userInfor as $val)
			{
			$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage'));
			}
			}
			*/
		/**************权限控制结束**************/
		//验证认领部门
		$claimIds = array_filter($claimIds);
		if (!empty($claimIds) && CLAIM && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$userOrgId = $this->user['org_id'];
			$checkClaimIds = array_diff($claimIds, array($userOrgId));
			if (!empty($checkClaimIds))
			{
				$this->errorOutput('报料已被其他部门认领，无法打回');
			}
		}


		$ret = $this->contribute->back($ids);
		if (!$ret)
		{
			$this->errorOutput('打回失败!');
		}
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$result  = array();
		while ($row = $this->db->fetch_array($query))
		{
			$result[] = $row;
			$column_id = unserialize($row['column_id']);

			//改变审核状态控制发布状态
			if (!empty($row['expand_id']) && $column_id && !empty($column_id))
			{
				$column_id = array_keys($column_id);
				$op = "delete";
				$this->contribute->publish_insert_query($row['id'], $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}
		//添加日志
		$this->addLogs('打回报料', $userInfor, $result, '打回报料'.$ids);
		//删除转发数据
		$this->contribute->del_send_contribute($ids);
		//打回视频
		$this->contribute->video_audit($ids, 0);
		$arr = explode(',', $ret);
		$this->addItem($arr);
		$this->output();
	}

	/**
	 *
	 * @Description swf上传
	 * @author Kin
	 * @date 2013-4-16 上午10:33:44
	 */
	public function upload()
	{

		if (!$this->settings['App_material'])
		{
			$this->errorOutput('图片服务器未安装！');
		}
		$id = intval($this->input['content_id']);
		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);

		//能否修改他人数据
		$userInfor = array(
			'_action'	=> 'manage',
			'id'		=> $id,
			'user_id'	=> $ret['user_id'],
			'org_id'	=> $ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点验证
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}
		if ($sortInfor)
		{
			$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);

		//操作的人信息
		$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
		);
			
		//修改发布后数据的状态
		if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		//修改审核数据后的状态
		elseif ($ret['audit'] == 2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}

		$this->mater = new material();
		$material = $this->mater->addMaterial($_FILES,$id); //插入各类服务器
		if (!$material || empty($material))
		{
			$this->errorOutput('图片上传失败!');
		}
		$data = array(
					'content_id'	=> $id,
					'mtype'			=> $material['type'],
					'host'			=> $material['host'],
					'dir'			=> $material['dir'],
					'material_path' => $material['filepath'],
					'pic_name'		=> $material['filename'],
					'original_id'	=> $material['id']
		);
		$id = $this->contribute->upload($data);
		//记录更新人的信息
		$this->contribute->update_content($additionalData, $id);

		$material['pic'] = array(
								'host'		=> $material['host'],
								'dir'		=> $material['dir'],
								'file_path' => $material['filepath'],
								'file_name' => $material['filename'],	
		);
		$material['id'] = $id;
		//更新发布库和转发数据
		$res = array_merge($ret, $additionalData);
		$column_id = unserialize($ret['column_id']);
		if ($ret['audit'] == 2 )
		{
			if ($column_id && !empty($column_id) && $ret['expand_id'])
			{
				$column_id = array_keys($column_id);
				if ($res['audit'] == 2)
				{
					$op = 'update';
				}else {
					$op = 'delete';
				}
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}else {
			//此时进行发布
			if ($res['audit'] == 2 && $column_id && !empty($column_id))
			{
				$column_id = array_keys($column_id);
				$this->contribute->publish_insert_query($id, 'insert', $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}
		//添加日志
		$this->addLogs('上传报料图片', $ret, $res, $ret['title'], $id, $ret['sort_id']);
		//更新转发数据
		$this->contribute->update_send_contribute($id);
		$this->addItem($material);
		$this->output();

	}

	/**
	 *
	 * @Description 删除图片(swf的快速操作)
	 * @author Kin
	 * @date 2013-4-16 上午10:17:04
	 */
	public function del_material()
	{
		$mid =  $this->input['pic']; // 素材id
		if (!$mid)
		{
			$this->errorOutput(NOID);
		}

		/**************权限控制开始**************/
		//获取报料id
		$sql = 'SELECT content_id FROM '.DB_PREFIX.'materials WHERE materialid = '.$mid;
		$material = $this->db->query_first($sql);
		$id = $material['content_id'];
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);

		//能否修改他人数据
		$userInfor = array(
			'_action'	=> 'manage',
			'id'		=> $id,
			'user_id'	=> $ret['user_id'],
			'org_id'	=> $ret['org_id'],

		);
		$this->verify_content_prms($userInfor);
		//节点验证
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}
		if ($sortInfor)
		{
			$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);


		//操作的人信息
		$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
		);
			
		//修改发布后数据的状态
		if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		//修改审核数据后的状态
		elseif ($ret['audit'] == 2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
			
		/**************权限控制结束**************/
		//删除图片
		$state = $this->contribute->del_pic($mid);
		if (!$state)
		{
			$this->errorOutput('删除图片失败！');
		}
		//记录更新人的信息
		$this->contribute->update_content($additionalData, $id);
		$res = array_merge($ret,$additionalData);
		$column_id = unserialize($ret['column_id']);
		if ($ret['audit'] == 2 )
		{
			if ($column_id && !empty($column_id) && $ret['expand_id'])
			{
				$column_id = array_keys($column_id);
				if ($res['audit'] == 2)
				{
					$op = 'update';
				}else {
					$op = 'delete';
				}
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}else {
			//此时进行发布
			if ($res['audit'] == 2 && $column_id && !empty($column_id))
			{
				$column_id = array_keys($column_id);
				$this->contribute->publish_insert_query($id, 'insert', $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}
		//添加日志
		$this->addLogs('删除报料图片', $ret, $res, $ret['title'], $ret['id'], $ret['sort_id']);
		$this->contribute->update_send_contribute($id);
		//更新发布库和转发数据
		$this->addItem('sucess');
		$this->output();
	}

	/**
	 *
	 * @Description 更新索引图
	 * @author Kin
	 * @date 2013-4-16 上午10:16:32
	 */
	public function update_indexpic()
	{
		$mid = intval($this->input['id']);			//素材id
		$id = intval($this->input['content_id']);	//内容id
		if (!$id || !$mid)
		{
			$this->errorOutput(NOID);
		}

		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'_action'=>'manage',
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点验证
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}
		if ($sortInfor)
		{
			$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);

		//操作的人信息
		$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
		);
			
		//修改发布后数据的状态
		if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		//修改审核数据后的状态
		elseif ($ret['audit'] == 2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		/**************权限控制结束**************/

		//更新索引图
		$data = $this->contribute->update_indexpic($mid, $id);
		if (!$data)
		{
			$this->errorOutput('更新索引图失败！');
		}
		//记录更新人的信息
		$this->contribute->update_content($additionalData, $id);

		$res = array_merge($ret,$additionalData);
		$column_id = unserialize($ret['column_id']);
		if ($ret['audit'] == 2 )
		{
			if ($column_id && !empty($column_id) && $ret['expand_id'])
			{
				$column_id = array_keys($column_id);
				if ($res['audit'] == 2)
				{
					$op = 'update';
				}else {
					$op = 'delete';
				}
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}else {
			//此时进行发布
			if ($res['audit'] == 2 && $column_id && !empty($column_id))
			{
				$column_id = array_keys($column_id);
				$this->contribute->publish_insert_query($id, 'insert', $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}
		//添加日志
		$this->addLogs('更新报料索引图', $ret, $res,$ret['title'],$ret['id'],$ret['sort_id']);
		//更新转发数据
		$this->contribute->update_send_contribute($id);
		$this->addItem($data);
		$this->output();

	}

	/**
	 *
	 * @Description 更新索引图
	 * @author Kin
	 * @date 2013-4-16 上午10:16:32
	 */
	public function update_indexpic_by_vod_pic()
	{
		$vodid = intval($this->input['vodid']);			//素材id
		$id = intval($this->input['content_id']);	//内容id
		$src = $this->input['img_src'];
		if (!$id || !$vodid || !$src)
		{
			$this->errorOutput(NOID);
		}

		/**************权限控制开始**************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		//能否修改他人数据
		$userInfor = array(
			'_action'=>'manage',
			'id'=>$id,
			'user_id'=>$ret['user_id'],
			'org_id'=>$ret['org_id'],
		);
		$this->verify_content_prms($userInfor);
		//节点验证
		if ($ret['sort_id'])
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'sort WHERE id ='.$ret['sort_id'];
			$sortInfor = $this->db->query_first($sql);
		}
		if ($sortInfor)
		{
			$node['nodes'][$sortInfor['id']] = $sortInfor['parents'];
		}
		$node['_action'] = 'manage';
		$this->verify_content_prms($node);

		//操作的人信息
		$additionalData = array(
				'update_time'		=> TIMENOW,
				'update_org_id'		=> $this->user['org_id'],
				'update_user_id'	=> $this->user['user_id'],
				'update_user_name'	=> addslashes($this->user['user_name']),
				'update_ip'			=> $this->user['ip'],
		);
			
		//修改发布后数据的状态
		if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		//修改审核数据后的状态
		elseif ($ret['audit'] == 2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$additionalData['audit'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$additionalData['audit'] = 2;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$additionalData['audit'] = 3;
			}
		}
		/**************权限控制结束**************/

		//更新索引图
		$data = $this->contribute->update_indexpic_by_vod_pic($id, $src, $vodid);
		if (!$data)
		{
			$this->errorOutput('更新索引图失败！');
		}
		//记录更新人的信息
		$this->contribute->update_content($additionalData, $id);

		$res = array_merge($ret,$additionalData);
		$column_id = unserialize($ret['column_id']);
		if ($ret['audit'] == 2 )
		{
			if ($column_id && !empty($column_id) && $ret['expand_id'])
			{
				$column_id = array_keys($column_id);
				if ($res['audit'] == 2)
				{
					$op = 'update';
				}else {
					$op = 'delete';
				}
				$this->contribute->publish_insert_query($id, $op, $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}else {
			//此时进行发布
			if ($res['audit'] == 2 && $column_id && !empty($column_id))
			{
				$column_id = array_keys($column_id);
				$this->contribute->publish_insert_query($id, 'insert', $column_id, $res['publish_time'], $this->user['user_name']);
			}
		}
		//添加日志
		$this->addLogs('更新报料索引图', $ret, $res,$ret['title'],$ret['id'],$ret['sort_id']);
		//更新转发数据
		$this->contribute->update_send_contribute($id);
		$this->addItem($data);
		$this->output();

	}

	/**
	 *
	 * @Description  快速发布
	 * @author Kin
	 * @date 2013-4-22 上午09:35:06
	 * @see adminUpdateBase::publish()
	 */
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		$id  = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id ='.$id;
		$ret = $this->db->query_first($sql);
		/**************权限控制开始**************/

		//能否修改他人数据
		$userInfor = array(
			'id'	  => $id,
			'user_id' => $ret['user_id'],
			'org_id'  => $ret['org_id'],
		);
		$this->verify_content_prms($userInfor);

		//发布权限验证
		$ret['column_id'] = unserialize($ret['column_id']);
		$published_column_id = array();
		if(is_array($ret['column_id']))
		{
			$published_column_id = array_keys($ret['column_id']);
		}
		$this->verify_content_prms(array('column_id'=> $this->input['column_id'],'_action'=>'publish','published_column_id'=>$published_column_id));

		//节点验证
		if($ret['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN ('.$ret['sort_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$data['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($data);


		/**************权限控制结束**************/

		//参数接收
		$data = array(
			'publish_time'	=> $this->input['pub_time'],
			'column_id'		=> $this->input['column_id'],
		);
		$data['publish_time'] = $data['publish_time'] ? strtotime($data['publish_time']) : TIMENOW ;
		$new_column_id = $data['column_id'] ? explode(',', $data['column_id']) : array();
		$old_column_id = $published_column_id;

		//更新发布信息
		//通过id获取发布栏目名称
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$data['column_id']);
		$column_id = !empty($column_id) ? serialize($column_id) : '';
		$affected_rows = false;
		$sql = 'UPDATE ' . DB_PREFIX .'content SET column_id = "'. addslashes($column_id) .'", publish_time = ' . $data['publish_time'] . '  WHERE id = ' . $id;
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
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
			//修改发布后数据的状态
			if ($ret['expand_id'] && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_publish_content']==1)
				{
					$additionalData['audit'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
				{
					$additionalData['audit'] = 2;
				}
				elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
				{
					$additionalData['audit'] = 3;
				}
			}
			//修改审核数据后的状态
			elseif ($ret['audit']==2 && $this->user['group_type'] > MAX_ADMIN_TYPE)
			{
				if ($this->user['prms']['default_setting']['update_audit_content']==1)
				{
					$additionalData['audit'] = 1;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
				{
					$additionalData['audit'] = 2;
				}
				elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
				{
					$additionalData['audit'] = 3;
				}
			}

			$this->contribute->update_content($additionalData, $id);
			/**************权限控制结束**************/
			//添加日志
			$res = array_merge($ret, $additionalData);
			$this->addLogs('发布报料', $ret, $res, $ret['title'], $id, $ret['sort_id']);
			//审核状态
			if ($res['audit'] == 2)
			{
				if (!empty($ret['expand_id']))
				{
					$del_column = array_diff($old_column_id,$new_column_id);
					if (!empty($del_column))
					{
						$this->contribute->publish_insert_query($id, 'delete', $del_column, $data['publish_time'], $this->user);
					}

					$add_column = array_diff($new_column_id,$old_column_id);
					if (!empty($add_column))
					{
						$this->contribute->publish_insert_query($id, 'insert', $add_column, $data['publish_time'], $this->user);
					}

					$same_column = array_intersect($old_column_id,$new_column_id);
					if(!empty($same_column))
					{
						$this->contribute->publish_insert_query($id, 'update', $same_column, $data['publish_time'], $this->user);
					}
				}
				elseif (!empty($new_column_id))
				{
					$op = "insert";
					$this->contribute->publish_insert_query($id,$op, $new_column_id, $data['publish_time'], $this->user);
				}
			}else {
				if (!empty($ret['expand_id']))
				{
					$op = "delete";
					$this->contribute->publish_insert_query($id, $op, $old_column_id, $data['publish_time'], $this->user);
				}
			}
		}
		$this->addItem($ret);
		$this->output();
	}
	/**
	 * 同步访问统计
	 */
	function access_sync()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput('NOID');
		}
		$data = array();
		if($this->input['click_num'])
		$data['click_num'] = intval($this->input['click_num']);
		if($this->input['comm_num'])
		$data['comm_num'] = intval($this->input['comm_num']);
		if($this->input['share_num'])
		$data['share_num'] = intval($this->input['share_num']);
		if($this->input['down_num'])
		$data['down_num'] = intval($this->input['down_num']);
		$return = $this->contribute->access_sync($data,intval($this->input['id']));
		$this->addItem($return);
		$this->output();
	}

	public function sort()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->addLogs('更改报料排序', '', '', '更改报料排序');
		$ret = $this->drag_order('content', 'order_id');
		$this->addItem($ret);
		$this->output();
	}

	/**
	 *
	 * @Description 部门认领
	 * @author Kin
	 * @date 2013-5-23 上午10:33:33
	 */
	public function claim()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		if (!$this->user)
		{
			$this->errorOutput('获取用户信息失败');
		}
		$data = $this->contribute->claim($ids,$this->user);
		$this->addItem($data);
		$this->output();
	}

	public function off_claim()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->contribute->off_claim($ids);
		$this->addItem($data);
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
				case 1:$state = 1;break;
				case 2:$state = 2;break;
				case 3:$state = 3;break;
			}

			if($state == 2)
			{
				/*******************调用积分规则,给已审核评论增加积分START*****************/

					if($this->settings['App_members'])
					{
						include (ROOT_PATH.'lib/class/members.class.php');
						$Members = new members();
						$sql = 'SELECT id,user_id FROM '.DB_PREFIX .'content WHERE 1 AND create_time>'.$start_time.' AND create_time<'.$end_time.' AND audit = 1 AND is_m2o = 0 AND new_member =1 AND is_credits=0';
						$q = $this->db->query($sql);
						$credit_rules_uid=array();//需增加积分的会员id
						while ($r = $this->db->fetch_array($q))
						{
							if($r['user_id'])
							{
								$credit_rules_uid[$r['id']]=$r['user_id'];
							}
						}
						/***审核增加积分**/
						if($credit_rules_uid)//审核增加积分为真&&已审核状态&&有user_id
						{
							$Members->Setoperation(APP_UNIQUEID,'','','extra');
							if(is_array($credit_rules_uid))
							{
								foreach ($credit_rules_uid as $key => $member_id)
								{
									$Members->get_credit_rules($member_id,APP_UNIQUEID);
									$this->db->query("UPDATE " . DB_PREFIX . "content SET is_credits=1 WHERE id=".$key);//更新获得积分字段
								}
							}
						}
					}
				/********************调用积分规则,给已审核评论增加积分END*****************/
			}

			$sql = 'UPDATE '.DB_PREFIX.'content SET audit = '.$state.'
					WHERE audit = 1 
					AND create_time>'.$start_time.' AND create_time<'.$end_time;
			$this->db->query($sql);
		}
		$this->addItem(true);
		$this->output();

	}
	/**
	 * 更新评论计数
	 * @name 		update_comment_count
	 */
	function update_comment_count()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput(NO_ID);
		}
		$id = intval($this->input['id']);
		//评论数
		if($this->input['comment_count'])
		{
			$comment_count = $this->input['comment_count'];
		}
		else
		{
			$comment_count = 1;
		}
		//审核增加评论数、打回减少评论数
		if($this->input['type'] == 'audit')
		{
			$type = '+';
		}
		else if($this->input['type'] == 'back')
		{
			$type = '-';
		}
		$info = array();
		if($type)
		{
			$sql = "UPDATE " . DB_PREFIX . "content SET comm_num=comm_num" . $type . $comment_count . " WHERE id =" . $id ;
			$this->db->query($sql);
			$sql = "SELECT id, audit, expand_id, title, column_id, publish_time,user_name FROM " . DB_PREFIX ."content WHERE id =" . $id ;
			$info = $this->db->query_first($sql);
		}
		
		if(empty($info))
		{
			return FALSE;
		}
		if(intval($info['audit']) == 2)
		{
			if(!empty($info['expand_id']))
			{
				$op = "update";
			}
			else
			{
				$op = "insert";
			}
		}
		else
		{
			if(!empty($info['expand_id']))
			{
				$op = 'delete';
			}
			else
			{
				$op = '';
			}
		}
		publish_insert_query($info, $op);
		$return = array('status' => 1,'id'=> $id,'pubstatus'=> 1);
		$this->addItem($return);
		$this->output();
	}
	
	public function update_is_follow()
	{
	    $id = $this->input['id'];

		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//爆料信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);

		if($ret)
        {   
            $sql = 'UPDATE '.DB_PREFIX.'content SET is_follow= '.$this->input['is_follow'].' where id ='.$id ;
            $query = $this->db->query($sql);
	       
        }
        $return = array('status' => 1,'id'=> $id,'is_follow'=> $this->input['is_follow']);
		$this->addItem($return);
		$this->output();
	}
	   
    public function update_satisfy_score()
	{
	    $id = $this->input['id'];

		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//爆料信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);

		if($this->input['satisfy_score'] == 1 )
        {   
            $sql = 'UPDATE '.DB_PREFIX.'content SET satisfy_score= satisfy_score +1  where id ='.$id ;
            $query = $this->db->query($sql);
	        $sql = 'SELECT * FROM '.DB_PREFIX.'sort  where id = '.$ret['sort_id'] ;
	        $res = $this->db->query_first($sql);
            $space = '';
	        if($res)
	        {   
		         $sql = 'UPDATE '.DB_PREFIX.'sort SET satisfy_score= satisfy_score +1  where id in ( '.$res['parents'].')';
	             $this->db->query($sql);
	        }
        }
        elseif($this->input['satisfy_score'] == 0 )
        {   
            $sql = 'UPDATE '.DB_PREFIX.'content SET unsatisfy_score= unsatisfy_score +1  where id ='.$id ;
            $query = $this->db->query($sql);
	        $sql = 'SELECT * FROM '.DB_PREFIX.'sort  where id = '.$ret['sort_id'] ;
	        $res = $this->db->query_first($sql);
            $space = '';
	        if($res)
	        {   
		         $sql = 'UPDATE '.DB_PREFIX.'sort SET unsatisfy_score= unsatisfy_score +1  where id in ( '.$res['parents'].')';
	             $re = $this->db->query($sql);
	        }
        }
        $return = array('status' => 1,'id'=> $id,'satisfy'=> $this->input['satisfy_score']);
        $this->addItem($return);
		$this->output();
	}
	
	public function show_satisfy_score()
	{
	    $id = $this->input['id'];

		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//爆料信息表
		$sql = 'SELECT * FROM '.DB_PREFIX.'content WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
        if($ret['satisfy_score'] > $ret['unsatisfy_score'])
        {
	        $ret['satisfy'] = '满意';
        }
        elseif($ret['satisfy_score'] < $ret['unsatisfy_score'])
        {
	         $ret['satisfy'] = '不满意';
        }
        else
        {
	         $ret['satisfy'] = '';
        }    	
	    $this->addItem($ret);
		$this->output();
	}
	
    public function update_audit()
    {
        $id = intval($this->input['id']);
        $audit =  intval($this->input['audit']);
        $opinion =  addslashes(trim($this->input['opinion']));
        if (!$id)
        {
                $this->errorOutput(NOID);
        }
        $sql = 'SELECT user_id, audit FROM '.DB_PREFIX.'content WHERE id = '.$id;
        $res = $this->db->query_first($sql);
        $sql = 'UPDATE '.DB_PREFIX.'content SET audit = '.$audit;
        if ($opinion)
        {
                $sql .= ' , opinion = "'.$opinion.'"';
        }
        $sql .= ' where id ='.$id ;
        $re = $this->db->query($sql);
        if($this->db->affected_rows()>0)
        {
                $status = 1;
                $this->send_notify($res['user_id'], $id);
        }
        else
        {
                $status = 2;
        }
        $return = array('status' => $status ,'id'=> $id );
        $this->addItem($return);
        $this->output();
    }	
	
	private function send_notify($member_id, $content_id)
	{
		if (!$member_id)
		{
			return;
		}
		if (!$this->settings['notify_to'] || !$this->settings['notify_msg'])
		{
			return;
		}
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$curl = new curl($this->settings['App_members']['host'], $this->settings['App_members']['dir']);
		$curl->initPostData();
		$curl->addRequestData('member_id', $member_id);
		$curl->addRequestData('message', $this->settings['notify_msg']);
		$curl->addRequestData('module', $this->settings['notify_to'] );
		$curl->addRequestData('content_id', $content_id);
		$ret = $curl->request('send_notify.php');
	}

	
	
	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new contribute_update();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();