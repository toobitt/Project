<?php
define('MOD_UNIQUEID','lbs_update');//模块标识
require_once('./global.php');
require_once(CUR_CONF_PATH.'lib/lbs.class.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/recycle.class.php');
require_once(CUR_CONF_PATH.'core/lbs.core.php');
class LBSUpdate extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		$this->lbs = new ClassLBS();
		$this->recycle = new recycle();
		$this->lbs_field = new lbs_field();

	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function publish()
	{

	}
	public function create()
	{
		/************************节点权限验证开始***************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['sort_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN('.intval($this->input['sort_id']).')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}
		$this->verify_content_prms($nodes);
		/************************节点权限验证结束***************************/
		/**************审核权限控制开始**************/
		//修改审核数据后的状态
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['create_content_status']==1)
			{
				$status = 0;
			}
			elseif ($this->user['prms']['default_setting']['create_content_status']==2)
			{
				$status = 1;
			}
			elseif ($this->user['prms']['default_setting']['create_content_status']==3)
			{
				$status = 2;
			}
		}
		/**************审核权限控制结束**************/
		$province_id = intval($this->input['province_id']);
		$city_id 	 = intval($this->input['city_id']);
		$area_id 	 = intval($this->input['area_id']);
		if(empty($province_id))
		{
			$city_id=0;
			$area_id=0;
		}
		elseif(empty($city_id))
		{
			$area_id=0;
		}
		$data = array(
			'title' 			=> trim($this->input['title']),
			'sort_id' 			=> intval($this->input['sort_id']),
			'province_id'		=> $province_id,
			'city_id' 			=> $city_id,
			'status'			=> intval($status),
			'area_id'			=> $area_id,
			'stime'				=> strtotime($this->input['stime']),
			'etime'				=> strtotime($this->input['etime']),
			'indexpic'			=> intval($this->input['indexpic']),
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'address'			=> trim($this->input['address']),
			'create_time'		=> TIMENOW,
			'org_id'			=> $this->user['org_id'],	
			'user_id'			=> $this->user['user_id'],	
			'user_name'			=> $this->user['user_name'],	
			'ip'				=> $this->user['ip'],
			'company_id'		=> intval($this->input['company_id']),
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
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$data['tel']=serialize($tel_arr);
		}
		$lbs = $this->lbs->add_lbs($data);
		$id = $lbs['id'];
		//附加信息处理
		$this->lbs_field->field_contentcreate($id,$this->input['sort_id'],$this->input['catalog']);
		if (!$id)
		{
			$this->errorOutput('创建失败');
		}
		//内容处理
		$content = trim($this->input['content']);
		$lbsContent = $this->lbs->add_content($content, $id);
		$data['content'] = $lbsContent;
		//素材处理
		$materialIds = $this->input['materials'];
		$materialIds[] = $data['indexpic'];
		if (is_array($materialIds) && !empty($materialIds))
		{
			$mids = implode(',',$materialIds);
			$sql = 'UPDATE '.DB_PREFIX.'materials SET cid = '.$id .',flag = 0 WHERE id IN ('.$mids.')';
			$this->db->query($sql);
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
		$index_pic=intval($this->input['indexpic']);
		$org_materials = $this->input['all_materials'] ? explode(',', $this->input['all_materials']) : array();//原始素材
		$materialIds = $this->input['materials'] ? $this->input['materials'] : array();
		if(((!in_array($index_pic, $materialIds)&&!in_array($index_pic, $org_materials))||(in_array($index_pic, $materialIds)&&!in_array($this->input['indexpic'], $org_materials)))&&!empty($this->input['indexpic']))
		{
			$materialIds[]=$index_pic;
		}
		elseif(!in_array($index_pic, $materialIds)&&in_array($index_pic, $org_materials)&&!empty($index_pic))
		{
			$index_pic = 0;
		}
		$province_id = intval($this->input['province_id']);
		$city_id 	 = intval($this->input['city_id']);
		$area_id 	 = intval($this->input['area_id']);
		if(empty($province_id))
		{
			$city_id=0;
			$area_id=0;
		}
		elseif(empty($city_id))
		{
			$area_id=0;
		}
		$data = array(
			'title' 			=> trim($this->input['title']),
			'sort_id' 			=> intval($this->input['sort_id']),
			'province_id'		=> $province_id,
			'city_id' 			=> $city_id,
			'area_id'			=> $area_id,
			'stime'				=> strtotime($this->input['stime']),
			'etime'				=> strtotime($this->input['etime']),
			'indexpic'			=> $index_pic,
			'baidu_longitude'	=> $this->input['baidu_longitude'],
			'baidu_latitude'	=> $this->input['baidu_latitude'],
			'address'			=> trim($this->input['address']),
			'update_time'		=> TIMENOW,
			'update_org_id'		=> $this->user['org_id'],	
			'update_user_id'	=> $this->user['user_id'],	
			'update_user_name'	=> $this->user['user_name'],	
			'update_ip'			=> $this->user['ip'],
			'company_id'		=> intval($this->input['company_id']),
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
		$tel_name = $this->input['tel_name'];
		$tel = $this->input['tel'];
		if (is_array($tel))
		{
			$tel = array_filter($tel);
			if (!empty($tel)&&is_array($tel))
			{
				foreach ($tel as $k=>$v)
				{
					$telname=$tel_name[$k]?$tel_name[$k]:'联系电话'.($k+1);
					$tel_arr[] = array('telname'=>$telname,'tel'=>$v);
				}
			}
			$data['tel']=serialize($tel_arr);
		}

		/**************权限控制开始**************/
		//源数据
		$sql = 'SELECT * FROM '.DB_PREFIX.'lbs WHERE id = '.$id;
		$preData = $this->db->query_first($sql);
		//节点权限
		$sql = 'SELECT id, parents FROM '.DB_PREFIX.'sort WHERE id IN (' . $preData['sort_id']. ',' . $data['sort_id'] . ')';
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
		if($data['sort_id'])
		{
			$node['nodes'][$data['sort_id']] = $sortInfo[$data['sort_id']];
		}
		$this->verify_content_prms($node);

		//能否修改他人数据
		$arr = array(
				'id'	  => $id,
				'user_id' => $preData['user_id'],
				'org_id'  => $preData['org_id'],
		);
		$this->verify_content_prms($arr);
		/**************权限控制结束**************/
		/**************审核权限控制开始**************/
		//修改审核数据后的状态
		if ($preData['status']==1 && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$data['status'] = 0;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$data['status'] = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$data['status'] = 2;
			}
		}
		/**************审核权限控制结束**************/
		//验证是否有数据更新
		//主表
		$affected_rows = false;
		$sql = 'UPDATE '.DB_PREFIX.'lbs SET ';
		foreach ($data as $key=>$val)
		{
			$sql .= $key.'="'.daddslashes(html_entity_decode($val,ENT_QUOTES)).'",';
		}
		$sql = rtrim($sql,',');
		$sql .= ' WHERE id = '.$id;
		$query = $this->db->query($sql);
		if ($this->db->affected_rows($query))
		{
			$affected_rows = true;
		}
		//描述
		$sql = 'SELECT id FROM '.DB_PREFIX.'lbs_content WHERE id = '.$id;
		$con_info = $this->db->query_first($sql);
		if (!$con_info['id'] && $this->input['content'])
		{
			$affected_rows = true;
		}
		if ($con_info['id'])
		{
			$sql = 'UPDATE '.DB_PREFIX.'lbs_content SET content = "'.daddslashes(html_entity_decode($this->input['content'],ENT_QUOTES)).'" WHERE id = '.$con_info['id'];
			$query = $this->db->query($sql);
			if ($this->db->affected_rows($query))
			{
				$affected_rows = true;
			}
		}
		//素材表
		if ($materialIds)
		//搜索图片所有记录
		$sql = 'SELECT id FROM '.DB_PREFIX.'materials WHERE cid = '.$id;
		$mids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$mids[] = $row['id'];
		}
		//排除异常数据
		$exceptionData = array_diff($mids, $materialIds);
		if (!empty($exceptionData))
		{
			$sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $exceptionData).')';
			$this->db->query($sql);
			$affected_rows = true;
		}
		$del_materials = array_diff($org_materials, $materialIds);
		if (!empty($del_materials))
		{
			$affected_rows = true;
			$sql = 'DELETE FROM '.DB_PREFIX.'materials WHERE id IN ('.implode(',', $del_materials).')';
			$this->db->query($sql);
		}
		$add_materials = array_diff($materialIds, $org_materials);
		if (!empty($add_materials))
		{
			$affected_rows = true;
			$sql = 'UPDATE '.DB_PREFIX.'materials SET cid = '.$id.',flag = 0 WHERE id IN ('.implode(',', $add_materials).')';
			$this->db->query($sql);
		}

		if ($affected_rows)
		{
			$res = array_merge($preData, $data);
			//添加日志
			$this->addLogs('更新lbs信息', $preData, $res, $preData['title'], $preData['id'], $preData['sort_id']);
		}
		//附加信息处理
		if($this->input['is_expand'])//是否更新附加信息.
		{			
			$this->lbs_field->field_contentupdate($id,$this->input['sort_id'],$this->input['catalog']);
		}
		$this->addItem(true);
		$this->output();
		/*
		 $sql = 'UPDATE '.DB_PREFIX.'lbs SET ';
		 foreach ($data as $key=>$val)
		 {
			$sql .=  $key . '="' . daddslashes($val) . '",';
			}
			$sql = rtrim($sql, ',');
			$sql.= ' WHERE id = ' . $id;
			$this->db->query($sql);

			//内容处理
			$content = trim($this->input['content']);
			$sql = 'UPDATE '.DB_PREFIX.'lbs_content SET content = "'.daddslashes($content).'" WHERE id = '.$id;
			$this->db->query($sql);
			//素材处理

			if (is_array($materialIds) && !empty($materialIds))
			{
			$mids = implode(',',$materialIds);
			$sql = 'UPDATE '.DB_PREFIX.'materials SET cid = '.$id .',flag = 0 WHERE id IN ('.$mids.')';
			$this->db->query($sql);
			}
			$this->addItem(true);
			$this->output();
			*/
	}
	
	public function excel_update()
	{
		$data_prms['_action'] = 'create';
		$this->verify_content_prms($data_prms);
	 	if ($this->user['group_type'] > MAX_ADMIN_TYPE)
        {
            //$this->errorOutput('只有管理员可以操作');
        }
        
		include (CUR_CONF_PATH . 'lib/excel.class.php');
		$excel = new excel();
		//获取文件扩展名
		 $extend = pathinfo($_FILES["excel"]["name"]);
		 $extend = strtolower($extend["extension"]);
		 //获取文件扩展名结束
		 $time=date("Y-m-d-H-i-s");//取当前上传的时间
		 $name=$time.'.'.$extend; //重新组装上传后的文件名
		 $uploadfile=CACHE_DIR.$name;//上传后的文件名地址
		if ((($extend == "xls") && ($_FILES["file"]["size"] < 2000000)))
		{
			$tmp_name=$_FILES["excel"]["tmp_name"];
			$strtotimes=strtotime(date('Ymd'));
			$key=md5_file($tmp_name);
			$sql=" SELECT filekey FROM " .DB_PREFIX. "con_fileinfo WHERE filekey = '" .$key. "' AND create_time =".$strtotimes;
			$re=$this->db->query_first($sql);
			if ($_FILES["excel"]["error"] > 0)
			{
				$this->errorOutput("Return Code: " . $_FILES["excel"]["error"] . "<br />");
			}
			elseif($re['filekey']==$key)
			{
				$this->errorOutput('已经导入成功,无需重复导入');
			}
			else
			{
				$sort_id = intval($this->input['sort_id']);
				$isupload=$excel->show($uploadfile,$tmp_name,$this->user,$sort_id);
				if($isupload)
				{
					$sql = 'INSERT INTO ' . DB_PREFIX . 'con_fileinfo SET filekey = \''.$key.'\',create_time ='.$strtotimes;
					$this->db->query($sql);
					// 删除除今天以外的文件MD5值.
					$sql = " DELETE FROM " .DB_PREFIX. "con_fileinfo WHERE 1 AND create_time NOT IN (".$strtotimes.")";
					$this->db->query($sql);
					$this->addItem($isupload);
					$this->output();
				}
				else $this->errorOutput('导入失败');

			}
		}
		else
		{
			$this->errorOutput('文件错误,仅支持xls,文件不能大于2M');
		}

	}

	public function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}

		$sql = 'SELECT * FROM '.DB_PREFIX.'lbs WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$sorts = array();
		$lbs = array();
		$recycle = array();
		while ($row = $this->db->fetch_array($query))
		{
			$sorts[] = $row['sort_id'];
			$lbs[$row['id']]  = $row;
			$recycle[$row['id']] = array(
				'cid'=>$row['id'],
				'title'=>$row['title'],
				'delete_people'=>$this->user['user_name'],
			);
			$recycle[$row['id']]['content']['lbs'] = $row;
		}
		//节点权限验证
		if($sorts && $this->user['group_type'] > MAX_ADMIN_TYPE)
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
		}
		//能否修改他人数据
		if (!empty($lbs) && $this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			foreach ($lbs as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id']));
			}
		}
		//放入回收站
		if ($this->settings['App_recycle'] && !empty($recycle))
		{
			require_once(ROOT_PATH.'lib/class/recycle.class.php');
			$this->recycle = new recycle();
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
				//删除主表
				$sql = 'DELETE FROM '.DB_PREFIX.'lbs WHERE id IN ('.$ids.')';
				$this->db->query($sql);
				$data = $ids;
			}
			else
			{
				$data = $this->lbs->delete($ids);
			}
		}
		else
		{
			$data = $this->lbs->delete($ids);
		}
		$this->addLogs('删除lbs信息', $lbs, '', '删除lbs信息' . $ids);

		//$data = $this->lbs->delete($ids);
		$this->lbs_field->field_contentdelete($ids);//删除附加信息内容
		$this->addItem($data);
		$this->output();
	}

	public function delete_comp()
	{
		$ids = $this->input['cid'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->lbs->delete($ids);
		$this->lbs_field->field_contentdelete($ids);
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'lbs WHERE id IN ('.$ids.')';
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
		$this->verify_content_prms($nodes);

		$status = intval($this->input['audit']);
		$status = ($status ==1) ? $status : 2;
		$data = $this->lbs->audit($ids,$status);

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
			$this->addLogs('审核lbs', $pre_data, $new_data,'审核lbs'.$ids);
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
			$this->addLogs('打回lbs', $pre_data, $new_data,'打回lbs'.$ids);
		}
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
			$data['cid'] 			= 0;//lbs的id,直接置零
			$data['original_id'] 	= $img_info['id'];
			$data['type'] 			= $img_info['type'];
			$data['mark'] 			= 'img';
			$data['imgwidth'] 		= $img_info['imgwidth'];
			$data['imgheight'] 		= $img_info['imgheight'];
			$data['flag']			= 1;
			$vid = $this->lbs->insert_img($data);
			if($vid)
			{
				$data['id'] = $vid;
				$this->addItem($data);
				$this->output();
			}
		}
	}

	public function delete_img()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->lbs->deleteMaterials($ids);
		$this->addItem($ret);
		$this->output();
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
		$sql = 'SELECT * FROM '.DB_PREFIX.'lbs WHERE id IN ('.$id.')';
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
			$vodInfor = $this->lbs->uploadToVideoServer($_FILES);
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
			$vid = $this->lbs->insert_video($data,$this->user);
			if($vid)
			{
				$this->addItem(array('id' => $vid,'img' => hg_fetchimgurl($img_data,100), 'vod_url' => $vod_url));
				$this->output();
			}
		}
	}

	public function sort()
	{
		$this->addLogs('更改lbs排序', '', '', '更改lbs排序');
		$ret = $this->drag_order('lbs', 'order_id');
		$this->addItem($ret);
		$this->output();
	}

	public function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
}
$ouput= new LBSUpdate();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'unknow';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();