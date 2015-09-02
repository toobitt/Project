<?php
define('MOD_UNIQUEID','period');
require_once('global.php');

require_once(CUR_CONF_PATH . 'lib/period_mode.php');
require_once(ROOT_PATH . 'lib/class/GetPinyinByChinese.php');

class period_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new period_mode();
		$this->initial = new GetPinyinByChinese();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$epaper_id = intval($this->input['epaper_id']);
		$period_num = intval($this->input['period_num']);
		/*新增一期 权限判断*/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'manage_period')); //期刊操作权限
			/**************节点权限*************/
			$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			if($prms_epaper_ids && implode(',', $prms_epaper_ids)!=-1 && !in_array($epaper_id,$prms_epaper_ids))
			{
				$this->errorOutput('没有权限');
			}
			/*********************************/
		}
		
		if(!$epaper_id)
		{
			$this->errorOutput('报刊id不存在');
		}
		
		if(!$period_num)
		{
			$this->errorOutput('期数不存在');
		}
		
		if(!$this->input['period_date'])
		{
			$this->errorOutput('未选择当期时间');
		}
		
		
		//确定索引图Id
		if($this->input['jpg_id'][0])
		{
			$indexpic_id = $this->input['jpg_id'][0];
		}
			
		//统计版数
		$page_num = count($this->input['page_id']);
		
		//统计叠数,查询是为了防止叠下未上传图片
		/*$page_id = implode(',', $this->input['page_id']);
		$sql = "SELECT stack_id FROM ".DB_PREFIX."page WHERE id IN (".$page_id.")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$stack_arr[$r['stack_id']] = 1; 
		}
		if($stack_arr)
		{
			$stack_num = count($stack_arr);
		}*/
		
		if($this->input['stack_id'])
		{
			$stack_num = count($this->input['stack_id']);
		}
		
		$period_date = strtotime($this->input['period_date']);
		/*
		$sql = " SELECT id FROM " . DB_PREFIX . "period WHERE epaper_id = " . $epaper_id . " AND period_date = " . $period_date;
		$re = $this->db->query_first($sql);
		if($re)
		{
			$this->errorOutput('今日期刊已存在');
		}
		*/
		$data = array(
			'epaper_id' 		=> $epaper_id,
			'period_num'		=> $period_num,
			'period_date'		=> $period_date,
			'user_id'			=> $this->user['user_id'],
			'org_id'			=> $this->user['org_id'],
			'user_name'			=> $this->user['user_name'],
			'ip'				=> hg_getip(),
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'indexpic_id'		=> $indexpic_id,
			'stack_num'			=> $stack_num,
			'page_num'			=> $page_num,
		);
		
		$ret = $this->mode->create($data);
		
		$period_id = $ret['id'];
		
		if($ret && $period_id)
		{
			//更新报刊最新一期信息
			$sql = "UPDATE ".DB_PREFIX."epaper SET period_id = ".$period_id.",cur_stage = ".$period_num.",cur_time = ".$period_date." WHERE id = ".$epaper_id;
			$this->db->query($sql);
		
			//更新叠的信息
			if($this->input['stack_id'])
			{
				$stack_ids = implode(',', $this->input['stack_id']);
				$sql = "UPDATE ".DB_PREFIX."stack SET epaper_id = ".$epaper_id.",period_id = ".$period_id." WHERE id IN (".$stack_ids.")";
				$this->db->query($sql);
			}
			
			//更新页信息
			if($this->input['page_id'])
			{
				foreach ($this->input['page_id'] as $k => $v)
				{
					if(!$v)
					{
						continue;
					}
					$page_ids[] = $v;
				}
				
				$page_ids = implode(',', $page_ids);
				//更新页属于哪期
				$sql = "UPDATE ".DB_PREFIX."page SET period_id = ".$period_id.",epaper_id = ".$epaper_id." WHERE id IN (".$page_ids.")";
				$this->db->query($sql);
				
				
				//更新素材所属期刊id
				if($this->input['pdf_id'] && $this->input['jpg_id'])
				{
					$imgId_arr = array_merge($this->input['jpg_id'],$this->input['pdf_id']);
				}
				else if($this->input['jpg_id'])
				{
					$imgId_arr = $this->input['jpg_id'];
				}
				else if($this->input['pdf_id'])
				{
					$imgId_arr = $this->input['pdf_id'];
				}
				if($imgId_arr)
				{
					foreach ($imgId_arr as $k => $v)
					{
						if(!$v)
						{
							continue;
						}
						$imgId[] = $v;
					}
					$img_ids = implode(',', $imgId);
					$sql = "UPDATE ".DB_PREFIX."material SET epaper_id = ".$epaper_id.",period_id = ".$period_id." WHERE id IN (".$img_ids.")";
					$this->db->query($sql);
				}
			}
			
			//记录日志
			$this->addLogs('电子报',$ret,'','新增一期' . $period_id);
			
			$this->addItem('success');
		}
		$this->output();
	}
	
	public function update()
	{
		$period_id = intval($this->input['id']);
		if(!$period_id)
		{
			$this->errorOutput(NOID);
		}
		$epaper_id = intval($this->input['epaper_id']);
		if(!$epaper_id)
		{
			$this->errorOutput('报刊id不存在');
		}
		
		/**************期刊所在报刊节点权限判断*************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids)!=-1 && !in_array($epaper_id,$prms_epaper_ids))
		{
			$this->errorOutput('您没有更新此期刊的权限');
		}
	
		/**************更新他人数据权限判断***************/
		$sql = "select * from " . DB_PREFIX ."period where id = " . $period_id;
		$q = $this->db->query_first($sql);
		$info['id'] = $period_id;
		$info['org_id'] = $q['org_id'];
		$info['user_id'] = $q['user_id'];
		$info['_action'] = 'manage_period';
		$this->verify_content_prms($info);
		/*********************************************/
		
		$status = $q['status']; //为后面的"更新已审核内容权限判断"准备数据
		
		$period_num = intval($this->input['period_num']);
		
		if(!$period_num)
		{
			$this->errorOutput('期数不存在');
		}
		
		if(!$this->input['period_date'])
		{
			$this->errorOutput('未选择当期时间');
		}
		
		
		//确定索引图Id
		if($this->input['jpg_id'][0])
		{
			$indexpic_id = $this->input['jpg_id'][0];
		}
		
		if($this->input['page_id'])
		{
			foreach ($this->input['page_id'] as $k => $v)
			{
				if(!$v)
				{
					continue;
				}
				$page_ids[] = $v;
			}
			
			$page_ids = implode(',', $page_ids);
			//更新页属于哪期
			$sql = "UPDATE ".DB_PREFIX."page SET period_id = ".$period_id.",epaper_id = ".$epaper_id." WHERE id IN (".$page_ids.")";
			$this->db->query($sql);
			
			//更新素材所属期刊id
			if($this->input['pdf_id'] && $this->input['jpg_id'])
			{
				$imgId_arr = array_merge($this->input['jpg_id'],$this->input['pdf_id']);
			}
			else if($this->input['jpg_id'])
			{
				$imgId_arr = $this->input['jpg_id'];
			}
			else if($this->input['pdf_id'])
			{
				$imgId_arr = $this->input['pdf_id'];
			}
			if($imgId_arr)
			{
				foreach ($imgId_arr as $k => $v)
				{
					if(!$v)
					{
						continue;
					}
					$imgId[] = $v;
				}
				$img_ids = implode(',', $imgId);
				$sql = "UPDATE ".DB_PREFIX."material SET epaper_id = ".$epaper_id.",period_id = ".$period_id." WHERE id IN (".$img_ids.")";
				$this->db->query($sql);
			}
		}
		
		//查询有多少叠和版
		$sql = "SELECT id,stack_id FROM ".DB_PREFIX."page WHERE period_id = ".$period_id;
		$q = $this->db->query($sql);
		
		$page_ids = array();
		$stack_arr = array();
		while ($r = $this->db->fetch_array($q))
		{
			$arr[] = $r;
			$page_ids[] = $r['id'];
			$stack_arr[$r['stack_id']] = 1;
		}
		//统计叠数
		$stack_num = count($stack_arr);
		
		//统计版数
		$page_num = count($page_ids);
		
		$period_date = strtotime($this->input['period_date']);
		/*
		$sql = " SELECT id FROM " . DB_PREFIX . "period WHERE epaper_id = " . $epaper_id . " AND period_date = " . $period_date;
		$re = $this->db->query_first($sql);
		if($re)
		{
			$this->errorOutput('今日期刊已存在');
		}
		*/
		
		$data = array(
			'period_num'		=> $period_num,
			'period_date'		=> $period_date,
			//'user_id'			=> $this->user['user_id'],
			//'org_id'			=> $this->user['org_id'],
			//'user_name'			=> $this->user['user_name'],
			//'ip'				=> hg_getip(),
			//'create_time'		=> TIMENOW,
			//'update_time'		=> TIMENOW,
			//'indexpic_id'		=> $indexpic_id,
			'stack_num'			=> $stack_num,
			'page_num'			=> $page_num,
		);
		if($indexpic_id)
		{
			$data['indexpic_id'] = $indexpic_id;
		}
		$row = $this->mode->update($period_id,$data);
		if($row) //如果数据有更新
		{
			$state = $this->user['prms']['default_setting']['update_audit_content'];
			if($status == 1 && $state)
			{
				if($state == 1)
				{
					$this->input['state'] = 0;
				}
				else if($state == 2)
				{
					$this->input['state'] = 1;
				}
				else if($state == 3)
				{
					$this->input['state'] = 2;
				}
			}
			else
			{
				$this->input['state'] = $status;
			}
			$data = array(
				'update_user_id'		=> $this->user['user_id'],
				'update_org_id'			=> $this->user['org_id'],
				'update_user_name'		=> $this->user['user_name'],
				'update_user_ip'		=> $this->user['ip'],
				'update_time'			=> TIMENOW,
				'status'				=> $this->input['state']
			);
			$this->mode->update($period_id,$data);
		}
		$this->addLogs('电子报',$ret,'','更新第' . $this->input['id'] . '期');
		$this->addItem($data);
		$this->output();
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$period_id = $this->input['id'];
		$sql = "SELECT epaper_id FROM " . DB_PREFIX ."period where id IN (" . $period_id . ")";
		$q = $this->db->query_first($sql);
		$epaper_id = $q['epaper_id'];
		/****************节点权限判断********************/
		$prms_epaper_ids = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $prms_epaper_ids && implode(',', $prms_epaper_ids)!=-1 && !in_array($epaper_id,$prms_epaper_ids))
		{
			echo -1;exit();
		}
		/**************删除他人数据权限判断***************/
		$sql = 'SELECT * FROM '.DB_PREFIX.'period WHERE id IN ('.$period_id.')';
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$conInfor[] = $row;
		}
		if (!empty($conInfor))
		{
			foreach ($conInfor as $val)
			{
				$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'manage_period'));
			}
		}
		/*********************************************/
		$sql = "SELECT id FROM ".DB_PREFIX."article WHERE period_id IN (".$this->input['id'].")";
		$res = $this->db->query_first($sql);
		if($res)
		{
			echo 0;exit();
		}
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$epaper_id = $ret[0]['epaper_id'];
			if($epaper_id)
			{
				$sql = "SELECT period_id FROM ".DB_PREFIX."epaper WHERE id = ".$epaper_id;
				$res = $this->db->query_first($sql);
				$cur_period_id = $res['period_id'];
				
				$period_ids = explode(',', $this->input['id']);
				if(in_array($cur_period_id, $period_ids))
				{
					$sql = "SELECT id FROM ".DB_PREFIX."period WHERE epaper_id = ".$epaper_id." ORDER BY id DESC LIMIT 0,1";
					$res = $this->db->query_first($sql);
					$period_id = $res['id'];
					if(!$period_id)
					{
						$period_id = 0;
					}
					$sql = "UPDATE ".DB_PREFIX."epaper SET period_id = ".$period_id." WHERE id = ".$epaper_id;
					$this->db->query($sql);
				}
			}
			$this->addLogs('电子报',$ret,'','删除第' . $this->input['id'] . '期');
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$this->verify_content_prms(array('_action'=>'audit')); //权限判断
			
			/**************审核他人数据权限判断***************
			$sql = 'SELECT * FROM '.DB_PREFIX.'period WHERE id IN ('.$this->input['id'].')';
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$conInfor[] = $row;
			}
			if (!empty($conInfor))
			{
				foreach ($conInfor as $val)
				{
					$this->verify_content_prms(array('id'=>$val['id'],'user_id'=>$val['user_id'],'org_id'=>$val['org_id'],'_action'=>'audit'));
				}
			}
			*********************************************/
		}
		
		$ret = $this->mode->audit($this->input['id'],$this->input['audit']);
		
		if($ret)
		{
			$this->addLogs('审核','',$ret,'审核期刊' . $this->input['id']);//此处是日志，自己根据情况加一下
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort()
	{
		$table_name = 'page';
		$order_name = 'order_id';
	
		$ids       = explode(',',urldecode($this->input['id']));
		$order_ids = explode(',',urldecode($this->input['order_id']));
		$page_num  = explode(',', urldecode($this->input['page_num']));
		foreach($ids as $k => $v)
		{
			$sql = "UPDATE " . DB_PREFIX . $table_name . " SET " . $order_name . " = '" . $order_ids[$k] . "',page = '".$page_num[$k]."' WHERE id = '" . $v . "'";
			$this->db->query($sql);
			if($this->db->affected_rows())
			{
				$this->addLogs('版页排序','','','');
			}
		}
		
		$this->addItem($ids);
		$this->output();
	}
	public function publish(){}
	
	public function update_page_title()
	{
		$page_id = intval($this->input['page_id']);
		if(!$page_id)
		{
			$this->errorOutput('未发现页id');	
		}
		
		$title = $this->input['title'];
		
		$sql = "UPDATE ".DB_PREFIX."page SET title = '".$title."' WHERE id = ".$page_id;
		$this->db->query($sql);
		
		$this->addItem('sucess');
		$this->output();
	}
	
	//ajax上传图片
	public function upload()
	{
		//叠Id
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			$this->errorOutput('请选择叠');
		}
		$_FILES['Filedata'] = $_FILES['videofile'];
		//图片上传
		
		if($_FILES['Filedata'])
		{
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			
			//获取图片服务器上传配置
			$PhotoConfig = $this->mode->getPhotoConfig();
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
			}
			
			if ($_FILES['Filedata']['name'])
			{
				if ($_FILES['Filedata']['error']>0)
				{
					$this->errorOutput('图片上传异常');
				}
				if (!in_array($_FILES['Filedata']['type'], $PhotoConfig['type']))
				{
					$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式的图片');
				}
				if ($_FILES['Filedata']['size']>100000000)
				{
					$this->errorOutput('只允许上传100M以下的图片!');
				}
			}			
			
			//页id,编辑时传递
			$page_id = intval($this->input['page_id']);
			
			if(!$page_id)
			{
				//图片格式正确，创建版页
				$page_num = $this->input['page_num'];//版页数
				$data = array(
					'stack_id'			=> $stack_id,
					'page'				=> $page_num,
				);
				
				$period_id = intval($this->input['period_id']);
				if($period_id)
				{
					$data['period_id'] = $period_id;
				}
				
				$epaper_id = intval($this->input['epaper_id']);
				if($epaper_id)
				{
					$data['epaper_id'] = $epaper_id;
				}
				//创建版页
				$sql = " INSERT INTO " . DB_PREFIX . "page SET ";
				foreach ($data AS $k => $v)
				{
					$sql .= " {$k} = '{$v}',";
				}
				$sql = trim($sql,',');
				$this->db->query($sql);
				$page_id = $this->db->insert_id();
			}
			
			
			if(!$page_id)
			{
				$this->errorOutput('版页id不存在');
			}
			
			
			//上传图片
			$PhotoInfor = $this->mode->uploadToPicServer($_FILES,$page_id);
			
			if (empty($PhotoInfor))
			{
				$this->errorOutput('图片服务器错误!');
			}
			$temp = array(
				'page_id'		=> $page_id,
				'stack_id'		=> $stack_id,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'mark'			=> $PhotoInfor['mark'],
				'imgwidth'		=> $PhotoInfor['imgwidth'],
				'imgheight'		=> $PhotoInfor['imgheight'],
				'filesize'		=> $PhotoInfor['filesize'],
				'ip'			=> hg_getip(),
			);
			
			//期id
			$period_id = intval($this->input['period_id']);
			if($period_id)
			{
				$temp['period_id'] = $period_id;
			}
			
			//报刊Id
			$epaper_id = intval($this->input['epaper_id']);
			if($epaper_id)
			{
				$temp['epaper_id'] = $epaper_id;
			}
			
			$img_id = intval($this->input['img_id']);
			if($img_id)
			{
				//更新素材表
				$res = $this->mode->update_material($img_id,$temp);
			}
			else 
			{
				$temp['create_time'] = TIMENOW;
				//插入素材表
				$PhotoId = $this->mode->insert_material($temp);
			}
			
			
			//照片类型
			$PhotoType = '';
			if($temp['type'] == 'jpg' || $temp['type'] == 'jpeg')
			{
				$PhotoType = 'jpg_id';
			}
			else 
			{
				$PhotoType = 'pdf_id';
			}
				
			//素材入库成功,更新页内
			if($PhotoId)
			{
				$sql = " UPDATE ".DB_PREFIX."page SET {$PhotoType} = {$PhotoId}, order_id = {$page_id}  WHERE id = {$page_id}";
				$this->db->query($sql);
			}
			else 
			{
				$PhotoId = $img_id;
			}	
			
			//图片信息
			$img_info = array(
				'host'		=> $temp['host'],
				'dir'		=> $temp['dir'],
				'filepath'	=> $temp['filepath'],
				'filename'	=> $temp['filename'],
			);
				
			$PageInfo[$stack_id][$page_id]['img_id'] 		= $PhotoId;
			$PageInfo[$stack_id][$page_id]['page_id'] 		= $page_id;
			$PageInfo[$stack_id][$page_id]['img_type'] 		= $PhotoType;
			//$PageInfo[$stack_id][$page_id]['page_num'] 	= $this->settings['stack_set'][$stack_id].$page_id;
			$PageInfo[$stack_id][$page_id]['img_info']		= $img_info;
			
			$this->addItem($PageInfo);
			
		}
		$this->output();
	}
	
	//ajax上传图片,根据文件名排序
	public function upload_new()
	{
		//叠Id
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			//$this->errorOutput('请选择叠');
			echo -1;exit();
		}
		
		$_FILES['Filedata'] = $_FILES['videofile'];
		if($_FILES['Filedata'])
		{
			//检测图片服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('图片服务器未安装!');
			}
			
			//获取图片服务器上传配置
			$PhotoConfig = $this->mode->getPhotoConfig();
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的图片类型失败！');
			}
			
			if ($_FILES['Filedata']['name'])
			{
				if ($_FILES['Filedata']['error']>0)
				{
					$this->errorOutput('图片上传异常');
				}
				if (!in_array($_FILES['Filedata']['type'], $PhotoConfig['type']))
				{
					$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式的图片');
				}
				if ($_FILES['Filedata']['size']>100000000)
				{
					$this->errorOutput('只允许上传100M以下的图片!');
				}
			}			
			
			
			//根据文件名获取页码（config配置型）
			$file_name = $_FILES['Filedata']['name'];
			/*$arr = explode('.', $file_name);
			$stack = $this->settings['stack_set'][$stack_id];
			$arr = explode($stack, $arr[0]);
			$order_id = intval($arr[1]);*/
			
			//文件名判断(数据库记录叠类型)
			$order_id = $this->check_filename($file_name);
			
			$check_file = true;
			if(!$order_id || $this->input['page_id'])
			{
				$check_file = false;
				//$this->errorOutput('文件名解析错误,请确认是否是当前叠下图片');
				
				if($this->input['page_num'])
				{
					$order_id = intval($this->input['page_num']);
				}
			}
			
			//页码
			//$page_num = $stack . $order_id;
			$page_num = $order_id;
			
			//批量上传jpg时传递所有页id(在检测文件名成功的情况下判断)
			if($this->input['page_ids'] && $check_file)
			{
				//查询当前页中，页码与文件名相同的页
				$sql = "SELECT id,jpg_id FROM ".DB_PREFIX."page WHERE page = ".$order_id." AND id IN (" . $this->input['page_ids'] . ")";
				$res = $this->db->query_first($sql);
				
				$page_id = $res['id'];
				
				if($res['jpg_id'] && !$this->input['confirm_replace'])
				{
					//echo $page_id;exit();
					//页码重复并且页中已经存在图片，更新图片信息
					$this->input['img_id'] = $res['jpg_id'];
				}
			}
			else if($this->input['page_id'])
			{
				//页id,编辑时传递
				$page_id = intval($this->input['page_id']);
			}
			
			//更新页还是创建页标识
			$add_flag = false;
			if(!$page_id)
			{
				//图片格式正确，创建版页
				$data = array(
					'stack_id'			=> $stack_id,
					'page'				=> $order_id,
					'order_id'			=> $order_id,
				);
				
				$period_id = intval($this->input['period_id']);
				if($period_id)
				{
					$data['period_id'] = $period_id;
				}
				
				$epaper_id = intval($this->input['epaper_id']);
				if($epaper_id)
				{
					$data['epaper_id'] = $epaper_id;
				}
				
				//创建版页
				if($data)
				{
					$page_id = $this->mode->create_page($data);
				}
				
				$add_flag = true;
			}
			
			
			if(!$page_id)
			{
				$this->errorOutput('版页id不存在');
			}
			
			
			//上传图片
			$PhotoInfor = $this->mode->uploadToPicServer($_FILES,$page_id);
			
			if (empty($PhotoInfor))
			{
				$this->errorOutput('图片服务器错误!');
			}
			$temp = array(
				'page_id'		=> $page_id,
				'stack_id'		=> $stack_id,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'mark'			=> $PhotoInfor['mark'],
				'imgwidth'		=> $PhotoInfor['imgwidth'],
				'imgheight'		=> $PhotoInfor['imgheight'],
				'filesize'		=> $PhotoInfor['filesize'],
				'ip'			=> hg_getip(),
			);
			
			//期id
			$period_id = intval($this->input['period_id']);
			if($period_id)
			{
				$temp['period_id'] = $period_id;
			}
			
			//报刊Id
			$epaper_id = intval($this->input['epaper_id']);
			if($epaper_id)
			{
				$temp['epaper_id'] = $epaper_id;
			}
			
			$img_id = intval($this->input['img_id']);
			if($img_id)
			{
				//更新素材表
				$res = $this->mode->update_material($img_id,$temp);
			}
			else 
			{
				$temp['create_time'] = TIMENOW;
				//插入素材表
				$PhotoId = $this->mode->insert_material($temp);
			}
			
			
			$PhotoType = 'jpg_id';
			//素材入库成功,更新页内
			if($PhotoId)
			{
				$sql = " UPDATE ".DB_PREFIX."page SET {$PhotoType} = {$PhotoId}  WHERE id = {$page_id}";
				$this->db->query($sql);
			}
			else
			{
				$PhotoId = $img_id;
			}	
			
			//图片信息
			$img_info = array(
				'host'		=> $temp['host'],
				'dir'		=> $temp['dir'],
				'filepath'	=> $temp['filepath'],
				'filename'	=> $temp['filename'],
			);
				
			$PageInfo[$stack_id][$page_id]['img_id'] 		= $PhotoId;
			$PageInfo[$stack_id][$page_id]['page_id'] 		= $page_id;
			$PageInfo[$stack_id][$page_id]['img_type'] 		= $PhotoType;
			$PageInfo[$stack_id][$page_id]['page_num'] 		= $page_num;
			$PageInfo[$stack_id][$page_id]['img_info']		= $img_info;
			$PageInfo[$stack_id][$page_id]['add_flag']		= $add_flag;
			$PageInfo[$stack_id][$page_id]['order_id']		= $order_id;
			
			$this->addItem($PageInfo);
			
		}
		$this->output();
	}
	
	
	
	//根据文件名上传pdf文件
	public function upload_pdf_new()
	{
		//叠Id
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			//$this->errorOutput('请选择叠');
			echo -1;exit();
		}
		
		
		$_FILES['Filedata'] = $_FILES['videofile'];
		
		if($_FILES['Filedata'])
		{
			//检测附件服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('附件服务器未安装!');
				//echo -2;exit();
			}
			
			//获取图片服务器上传配置
			$PhotoConfig = $this->mode->getPhotoConfig($type='doc');
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的文件类型失败！');
			}
			if ($_FILES['Filedata']['name'])
			{
				if ($_FILES['Filedata']['error']>0)
				{
					$this->errorOutput('文件上传异常');
				}
				if (!in_array($_FILES['Filedata']['type'], $PhotoConfig['type']))
				{
					$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式文件');
				}
				if ($_FILES['Filedata']['size']>100000000)
				{
					$this->errorOutput('只允许上传100M以下的文件!');
				}
			}			
			
			//根据文件判定第几页
			
			$file_name = $_FILES['Filedata']['name'];
			/*$arr = explode('.', $file_name);
			$arr = explode($stack, $arr[0]);
			$stack = $this->settings['stack_set'][$stack_id];
			$order_id = intval($arr[1]);*/

			$order_id = $this->check_filename($file_name);
			
			$check_file = true;
			if(!$order_id || $this->input['page_id'])
			{
				$check_file = false;
				//$this->errorOutput('文件名解析错误,请确认是否是当前叠下图片');
				
				if($this->input['page_num'])
				{
					$order_id = intval($this->input['page_num']);
				}
			}
			//页码
			//$page_num = $stack . $order_id;
			$page_num = $order_id;
			
			
			//批量上传pdf时传递所有页id
			if($this->input['page_ids'] && $check_file)
			{
				//查询当前页中，页码与文件名相同的页
				$sql = "SELECT id,pdf_id FROM ".DB_PREFIX."page WHERE page = ".$order_id." AND id IN (" . $this->input['page_ids'] . ")";
				$res = $this->db->query_first($sql);
				
				$page_id = $res['id'];
				
				
				//已经存在页的，判断页下是否有pdf文件，已经存在返回提示是否要替换
				if($res['pdf_id'] && !$this->input['confirm_replace'])
				{
					//echo $page_id;exit();
					$this->input['img_id'] = $res['pdf_id'];
				}
			}
			else if($this->input['page_id'])//编辑时候传递页id
			{
				$page_id = intval($this->input['page_id']);
			}
			
			
			$add_flag = false;
			if(!$page_id)
			{
				$data = array(
					'stack_id'			=> $stack_id,
					'page'				=> $order_id,
					'order_id'			=> $order_id,
				);
				
				$period_id = intval($this->input['period_id']);
				if($period_id)
				{
					$data['period_id'] = $period_id;
				}
				
				$epaper_id = intval($this->input['epaper_id']);
				if($epaper_id)
				{
					$data['epaper_id'] = $epaper_id;
				}
				
				//创建版页
				if($data)
				{
					$page_id = $this->mode->create_page($data);
				}
				$add_flag = true;
			}
			
			if(!$page_id)
			{
				$this->errorOutput('页id不存在');
			}
			
			
			//上传文件
			$PhotoInfor = $this->mode->uploadToPicServer($_FILES,$page_id);
			if (empty($PhotoInfor))
			{
				$this->errorOutput('附件服务器错误!');
			}
			$temp = array(
				'page_id'		=> $page_id,
				'stack_id'		=> $stack_id,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'mark'			=> $PhotoInfor['mark'],
				'filesize'		=> $PhotoInfor['filesize'],
				'ip'			=> hg_getip(),
			);
			
			$PhotoType = 'pdf_id';
			$img_id = intval($this->input['img_id']);
			if($img_id)
			{
				//更新素材表
				$res = $this->mode->update_material($img_id,$temp);
				
				//$sql = " UPDATE ".DB_PREFIX."page SET order_id = {$order_id}  WHERE id = {$page_id}";
				//$this->db->query($sql);
				$PhotoId = $img_id;
			}
			else 
			{
				$temp['create_time'] = TIMENOW;
				//插入素材表
				$PhotoId = $this->mode->insert_material($temp);
				
				$sql = " UPDATE ".DB_PREFIX."page SET {$PhotoType} = {$PhotoId}  WHERE id = {$page_id}";
				$this->db->query($sql);
			}
			
			//图片信息
			$img_info = array(
				'host'		=> $temp['host'],
				'dir'		=> $temp['dir'],
				'filepath'	=> $temp['filepath'],
				'filename'	=> $temp['filename'],
			);
				
			$PageInfo['page_id']		= $page_id;
			$PageInfo['img_id'] 		= $PhotoId;
			$PageInfo['img_type'] 		= $PhotoType;
			$PageInfo['img_info']		= $img_info;
			$PageInfo['add_flag']		= $add_flag;
			$PageInfo['page_num']		= $page_num;
			$PageInfo['order_id']		= $order_id;
			
			$this->addItem($PageInfo);
		}
		$this->output();
	}
	
	public function upload_pdf()
	{
		//叠Id
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id)
		{
			$this->errorOutput('请选择叠');
		}
		$_FILES['Filedata'] = $_FILES['videofile'];
		
		
		if($_FILES['Filedata'])
		{
			//检测附件服务器
			if (!$this->settings['App_material'])
			{
				$this->errorOutput('附件服务器未安装!');
			}
			
			//获取图片服务器上传配置
			$PhotoConfig = $this->mode->getPhotoConfig($type='doc');
			if (!$PhotoConfig)
			{
				$this->errorOutput('获取允许上传的文件类型失败！');
			}
			if ($_FILES['Filedata']['name'])
			{
				if ($_FILES['Filedata']['error']>0)
				{
					$this->errorOutput('文件上传异常');
				}
				if (!in_array($_FILES['Filedata']['type'], $PhotoConfig['type']))
				{
					$this->errorOutput('只允许上传'.$PhotoConfig['hint'].'格式文件');
				}
				if ($_FILES['Filedata']['size']>100000000)
				{
					$this->errorOutput('只允许上传100M以下的文件!');
				}
			}			
			
			//页id,编辑时传递
			$page_id = intval($this->input['page_id']);
			
			//上传图片
			$PhotoInfor = $this->mode->uploadToPicServer($_FILES,$page_id);
			if (empty($PhotoInfor))
			{
				$this->errorOutput('附件服务器错误!');
			}
			$temp = array(
				'page_id'		=> $page_id,
				'stack_id'		=> $stack_id,
				'type'			=> $PhotoInfor['type'],						
				'material_id'	=> $PhotoInfor['id'],
				'host'			=> $PhotoInfor['host'],
				'dir'			=> $PhotoInfor['dir'],
				'filepath' 		=> $PhotoInfor['filepath'],
				'filename'		=> $PhotoInfor['filename'],
				'mark'			=> $PhotoInfor['mark'],
				'filesize'		=> $PhotoInfor['filesize'],
				'ip'			=> hg_getip(),
			);
			
			
			$img_id = intval($this->input['img_id']);
			if($img_id)
			{
				//更新素材表
				$res = $this->mode->update_material($img_id,$temp);
				$PhotoId = $img_id;
			}
			else 
			{
				$temp['create_time'] = TIMENOW;
				//插入素材表
				$PhotoId = $this->mode->insert_material($temp);
			}
			
			//照片类型
			$PhotoType = '';
			if($temp['type'] == 'jpg' || $temp['type'] == 'jpeg')
			{
				$PhotoType = 'jpg_id';
			}
			else 
			{
				$PhotoType = 'pdf_id';
			}
				
			
			//图片信息
			$img_info = array(
				'host'		=> $temp['host'],
				'dir'		=> $temp['dir'],
				'filepath'	=> $temp['filepath'],
				'filename'	=> $temp['filename'],
			);
				
			$PageInfo['img_id'] 		= $PhotoId;
			$PageInfo['img_type'] 		= $PhotoType;
			$PageInfo['img_info']		= $img_info;
			
			$this->addItem($PageInfo);
		}
		$this->output();
	}
	
	public function update_pdf_page()
	{
		$page_id = explode(',', $this->input['page_id']);
		$pdf_id = explode(',', $this->input['pdf_id']);
		
		if($page_id && $pdf_id)
		{
			foreach ($page_id as $k => $v)
			{
				if(!$pdf_id[$k])
				{
					continue;
				}
				$sql = "UPDATE ".DB_PREFIX."page SET pdf_id = ".$pdf_id[$k]." WHERE id = ".$v;
				$this->db->query($sql);
				
				$sql = "UPDATE ".DB_PREFIX."material SET page_id = ".$v." WHERE id = ".$pdf_id[$k];
				$this->db->query($sql);
			}
		}

		$this->addItem('success');
		$this->output();
		
	}
	//删除某一页
	public function del_page()
	{
		$page_id = urldecode($this->input['page_id']);
		if(!$page_id)
		{
			$this->errorOutput('没有page_id');
		}
		
		//查询页下是否有文章
		$sql = "SELECT id FROM ".DB_PREFIX."article WHERE page_id IN (".$page_id.")";
		$res = $this->db->query_first($sql);
		if($res)
		{
			echo -1;exit();
		}
		
		//查询页下所有图片
		$sql = "SELECT jpg_id,pdf_id,period_id FROM ".DB_PREFIX."page WHERE id IN (".$page_id.")";
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$res[] = $r['jpg_id'];
			$res[] = $r['pdf_id'];
			$period_id = $r['period_id'];
		}
		if($res)
		{
			$mater_ids = implode(',', $res);
		}
		
		$sql = "DELETE FROM ".DB_PREFIX."page WHERE id IN (".$page_id.")";
		$this->db->query($sql);
		
		if($mater_ids)
		{
			$sql = "DELETE FROM ".DB_PREFIX."material WHERE id IN (".$mater_ids.")";
			$this->db->query($sql);
		}
		
		//更新期下叠和版，索引图id
		$this->mode->update_period($period_id,$res);
		
		$this->addItem('sucess');
		$this->output();
	}
	
	/**
	 * 
	 * Enter description here ...
	 * 
	 * 更新页码
	 * @param page_id 页id
	 * @param page_num 页码
	 */
	public function update_page_num()
	{
		$page_id = intval($this->input['page_id']);
		$page_num = intval($this->input['page_num']);
		
		if(!$page_id)
		{
			$this->errorOutput('页id不存在');
		}
		
		if(!$page_num)
		{
			$this->errorOutput('页码不存在');
		}
		
		$sql = "UPDATE ".DB_PREFIX."page SET page = '".$page_num."',order_id = '".$page_num."' WHERE id = ".$page_id;
		$this->db->query($sql);
		
		
		$this->addItem('success');
		$this->output();
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * 更新页的叠
	 */
	public function update_page_stack()
	{
		$stack_set = $this->settings['stack_set'];
		$stack = strtoupper(trim(urldecode($this->input['stack'])));
		$stack = trim(str_replace('叠', '', $stack));
		if($stack && $stack_set)
		{
			if(!in_array($stack, $stack_set))
			{
				$this->errorOutput('不在规定范围内');
			}
			else 
			{
				$stack_id = array_search($stack, $stack_set);
			}
		}
		
		$page_ids = urldecode($this->input['page_ids']);
		if(!$page_ids)
		{
			$this->errorOutput('页id不存在');
		}
		
		if(!$stack_id)
		{
			$this->errorOutput('叠id不存在');
		}
		
		$sql = "UPDATE ".DB_PREFIX."page SET stack_id = ".$stack_id." WHERE id IN (".$page_ids.")";
		$this->db->query($sql);
		
		if($stack == '特刊')
		{
			$stack = 'T';
		}
		
		$this->addItem($stack);
		$this->output();
	}
	
	
	/**
	 * 创建叠
	 * Enter description here ...
	 */
	public function create_stack()
	{
		
		$stack = $this->input['stack'];
		if(!$stack)
		{
			$this->errorOutput('请填写叠名称');
		}
		//获取叠的首字母
		$initial_arr = $this->initial->Pinyin($stack);
		$initial = strtoupper($initial_arr[2][0]);
		
		$epaper_id = intval($this->input['epaper_id']);
		$period_id = intval($this->input['period_id']);
		
		$data = array(
			'name'		=> $stack,
			'zm'		=> $initial,
			'epaper_id'	=> $epaper_id,
			'period_id'	=> $period_id, 
		);
		
		$sql = " INSERT INTO " . DB_PREFIX . "stack SET ";
		foreach ($data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		
		$this->db->query($sql);
		
		$id = $this->db->insert_id();
		
		
		$sql = "UPDATE ".DB_PREFIX."stack SET order_id = ".$id." WHERE id = ".$id;
		$this->db->query($sql);
		
		//统计叠数量
		$sql = 'SELECT count(*) as total FROM ' . DB_PREFIX . "stack WHERE epaper_id = " . $epaper_id . " AND period_id = " . $period_id;
		$count = $this->db->query_first($sql);
		
		$sql = "UPDATE " . DB_PREFIX . "period SET stack_num = " . $count['total'];
		$this->db->query($sql);
		
		$arr['id'] = $id;
		$arr['zm'] = $initial;
		$arr['name'] = $stack;
		
		$this->addItem($arr);
		$this->output();
	}
	/**
	 * 
	 * Enter description here ...
	 * 更新数据库记录的叠
	 */
	public function update_stack_db()
	{
		$stack = $this->input['stack'];
		$stack_id = intval($this->input['stack_id']);
		
		
		if(!$stack || !$stack_id)
		{
			$this->errorOutput('叠信息不全');
		}
		
		//获取叠的首字母
		$initial_arr = $this->initial->Pinyin($stack);
		$initial = strtoupper($initial_arr[2][0]);
		
		$sql = "UPDATE ".DB_PREFIX."stack SET name = '".$stack."',zm = '".$initial."' WHERE id = ".$stack_id;
		$this->db->query($sql);
		
		$this->addItem($initial);
		$this->output();
	}
	
	/**
	 * 检查上传文件名(叠入数据库)
	 * Enter description here ...
	 */
	public function check_filename($file_name)
	{
		$stack_id = intval($this->input['stack_id']);
		if(!$stack_id || !$file_name)
		{
			return false;
		}
		
		$sql = "SELECT name,zm FROM ".DB_PREFIX."stack WHERE id = ".$stack_id;
		$res = $this->db->query_first($sql);
		
		//$stack = $res['name'];
		//$stack = trim(str_replace('叠', '', $stack));
		$stack = $res['zm'];
		
		//根据文件名获取页码
		$arr = explode('.', $file_name);
		$arr = explode($stack, $arr[0]);
		$order_id = intval($arr[1]);
		
		//file_put_contents('1.txt', $file_name);
		//file_put_contents('2.txt', var_export($arr,1));
		if(!$order_id)
		{
			$stack = strtolower($stack);
			
			$arr = explode('.', $file_name);
			$arr = explode($stack, $arr[0]);
			$order_id = intval($arr[1]);
			//file_put_contents('3.txt', var_export($arr,1));
		}
		return $order_id;
		
	}
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}



$out = new period_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>