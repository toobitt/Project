<?php
/***************************************************************************
 * LivSNS 0.1
 * (C)2004-2010 HOGE Software.
 *
 * $Id:$
 ***************************************************************************/
require_once('global.php');
require_once('../core/tuji.dat.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
define('MOD_UNIQUEID','tuji');//模块标识
class tuji_update extends adminUpdateBase
{
	function __construct()
	{
		#####默认方法的属性改变######
		/*
		 $this->mPrmsMethods['delete']['publish'] = True;
		 $this->mPrmsMethods['audit']['publish'] = True;
		 $this->mModPrmsMethods = array(
			'publish'		=>	array('name'=>'快速发布','publish' => true,'node' => true),
			'update_tuji'	=>	array('name'=>'更新','publish' => true,'node'=>true),
			'create_tuji'	=>	array('name'=>'创建','publish' => true,'node'=>true),
			);
			*/
		#####默认方法的属性改变######
		parent::__construct();
		$this->tuji = new tuji_data();
		include_once(ROOT_PATH . 'lib/class/recycle.class.php');
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->recycle = new recycle();
		$this->material = new material();
		$this->publish_column = new publishconfig();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	function create()
	{
		$this->create_tuji();
	}

	function sort()
	{
		$this->drag_order('tuji','order_id');
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id IN(" . $this->input['content_id'] . ")";
		$ret = $this->db->query($sql);
		while($row = $this->db->fetch_array($ret))
		{
			if(!empty($row['column_id']) && !empty($row['expand_id']))
			{
				publish_insert_query($row, 'update');
			}
		}
		$ids = explode(',',$this->input['content_id']);
		$this->addItem(array('id' =>$ids));
		$this->output();
	}

	function update()
	{
		/*//临时屏蔽,有问题立即改回.无问题.此方法内容将会被删除
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$flag = false;
		$fileds = " SET ";
		if($this->input['title'])
		{
			if(trim(urldecode($this->input['title'])) == '在这里添加标题')
			{
				$this->errorOutput('请填写标题');
			}

			$flag = true;
			$fileds .= " title = \"".urldecode($this->input['title']).'",';
		}

		if($this->input['tuji_sort_id'])
		{
			$flag = true;
			$fileds .= " tuji_sort_id = \"".intval($this->input['tuji_sort_id']).'",';
		}
		else
		{
			$flag = true;
			$fileds .= " tuji_sort_id = 0 ,";
		}

		if($this->input['comment'])
		{
			$comment = trim(urldecode($this->input['comment']));
			if(trim(urldecode($this->input['comment'])) == '这里输入描述')
			{
				$comment = '';
			}
			$flag = true;
			$fileds .= " comment = \"".$comment.'",';
		}

		if($this->input['default_comment'])
		{
			$default_comment = trim(urldecode($this->input['default_comment']));
			if(trim(urldecode($this->input['default_comment'])) == '这里输入默认描述')
			{
				$default_comment = '';
			}
			$flag = true;
			$fileds .= " default_comment = \"".$default_comment.'",';
		}

		if($this->input['cover'])
		{
			$flag = true;
			$fileds .= " cover_url = \"".urldecode($this->input['cover']).'",';
		}

		if($this->input['keywords'])
		{
			$keywords = trim(urldecode($this->input['keywords']));
			if(trim(urldecode($this->input['keywords'])) == '在这里输入关键字')
			{
				$keywords = '';
			}
			$flag = true;
			$fileds .= " keywords = \"".$keywords.'",';
		}

		if($this->input['auto_cover'])
		{
			$flag = true;
			$fileds .= " auto_cover = 1,";
		}
		else
		{
			$flag = true;
			$fileds .= " auto_cover = 0,";
		}

		if($this->input['is_namecomment'])
		{
			$flag = true;
			$fileds .= " is_namecomment = 1,";
		}
		else
		{
			$flag = true;
			$fileds .= " is_namecomment = 0,";
		}

		if($this->input['is_orderby_name'])
		{
			$flag = true;
			$fileds .= " is_orderby_name = 1,";
		}
		else
		{
			$flag = true;
			$fileds .= " is_orderby_name = 0,";
		}

		if($this->input['is_add_water'])
		{
			$flag = true;
			$fileds .= " is_add_water = 1,";
		}
		else
		{
			$flag = true;
			$fileds .= " is_add_water = 0,";
		}

		if($flag)
		{
			$fileds .= ' update_time = '.TIMENOW;
			if($this->db->query("UPDATE ".DB_PREFIX.'tuji '.$fileds.' WHERE id = '.intval($this->input['id'])))
			{
				$sql = "SELECT * FROM ".DB_PREFIX."tuji_node WHERE id = '".intval($this->input['tuji_sort_id'])."'";
				$arr = $this->db->query_first($sql);
				$tuji_sort_name = $arr['id']?$arr['name']:'无';
				$ret = array('id'=>intval($this->input['id']),'title'=>urldecode($this->input['title']),'tuji_sort_name'=>$tuji_sort_name);
				$this->addItem($ret);
			}
			else
			{
				$this->addItem('error');
			}
		}
		else
		{
			$this->errorOutput(NODATA);
		}
		$this->output();
		*/
	}
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$tuji_sorts_arr = array();//存储节点
		$pre_log = array();//记录日志
		$prms_arr = array();//存储权限信息
		$sql = "SELECT * FROM ".DB_PREFIX."tuji  WHERE id IN (".($this->input["id"]).")";
		$q  = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$pre_log[] = $r;
			$column_id = @unserialize($r['column_id']);
			if($column_id && is_array($column_id))
			{
				$published_column_id = implode(',',array_keys($column_id));
			}
			else
			{
				$published_column_id = '';
			}
			//存储权限信息
			$tuji_sorts_arr[] = $r['tuji_sort_id'];
			//$prms_arr[$r['id']]['id'] = $r['id'];
			//$prms_arr[$r['id']]['user_id'] = $r['user_id'];
			//$prms_arr[$r['id']]['published_column_id'] = $published_column_id;
			$prms_arr[$r['id']]['tuji_sort_id'] = $r['tuji_sort_id'];
			//$prms_arr[$r['id']]['org_id'] = $r['org_id'];
		}
		 
		/***********************************权限控制*****************************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($tuji_sorts_arr)
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'tuji_node WHERE id IN('.implode(',',$tuji_sorts_arr).')';
				$query = $this->db->query($sql);
				$sort_ids_array = array();
				while($row = $this->db->fetch_array($query))
				{
					$sort_ids_array[$row['id']] = $row['parents'];
				}
			}

			if(!empty($prms_arr))
			{
				foreach ($prms_arr as $key => $value)
				{
					if(intval($value['tuji_sort_id']))
					{
						$value['nodes'][$value['tuji_sort_id']] = $sort_ids_array[$value['tuji_sort_id']];
					}
					$this->verify_content_prms($value);
					/*
					 $value['nodes'][$value['tuji_sort_id']] = $sort_ids_array[$value['tuji_sort_id']];
					 $this->verify_content_prms($value);
					 */
				}
			}
		}
		/***********************************权限控制*****************************************************/
		$ids = explode(',',urldecode($this->input['id']));
		//判断是审核还是打回
		if(intval($this->input['audit']) == 1)//审核
		{
			$sql = "UPDATE ".DB_PREFIX."tuji  SET  status=1  WHERE id IN (".$this->input['id'].")";
			$this->db->query($sql);
			$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id IN (" . $this->input['id'] .")";
			$ret = $this->db->query($sql);
			//加日志
			$this->addLogs('审核图集', $pre_log, $ret,'审核图集' . $this->input['id']);
			while($info = $this->db->fetch_array($ret))
			{
				if(!empty($info['expand_id']))
				{
					$op = "update";
				}
				else
				{
					if(@unserialize($info['column_id']))
					{
						$op = "insert";
					}
				}
				publish_insert_query($info, $op);

				$stat_id[] = $info['id'];
				$stat_user_id[] = $info['user_id'];
				$stat_user_name[] = $info['user_name'];
			}
			$return = array('id' => $ids,'status' => 1);
			//审核通过
			$stat_opration = 'verify_suc';
		}
		else if(intval($this->input['audit']) == 0)  //打回
		{
			$sql = "UPDATE ".DB_PREFIX."tuji  SET  status=2  WHERE id IN (".urldecode($this->input['id']).")";
			$this->db->query($sql);
			$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id IN (" . urldecode($this->input['id']) .")";
			$ret = $this->db->query($sql);
			//加日志
			$this->addLogs('审核图集', $pre_log, $ret,'审核图集' . $this->input['id']);
			while($info = $this->db->fetch_array($ret))
			{
				$info['column_id'] = @unserialize($info['column_id']);
				if(!empty($info['expand_id']) || $info['column_id'])
				{
					$op= "delete";  		//expand_id不为空说明已经发布过，打回操作时应重新发布，发布时执行delete操作
				}
				else
				{
					$op = "";
				}
				publish_insert_query($info, $op);

				$stat_id[] = $info['id'];
				$stat_user_id[] = $info['user_id'];
				$stat_user_name[] = $info['user_name'];
			}
			$return = array('id' => $ids,'status' => 2);
			$stat_opration = 'verify_fail';
		}

		if($stat_id)
		{
			$stat_data = array(
				'content_id' => implode(',',$stat_id),
				'contentfather_id' => '',
				'type' => $stat_opration,
				'user_id' => implode(',',$stat_user_id),
				'user_name' => implode(',',$stat_user_name),
				'before_data' => '',
				'last_data' => '',
				'num' => 1,
			);
			$this->addStatistics($stat_data);
		}

		$this->addItem($return);
		$this->output();
	}

	//获取原始封面
	function get_cover()
	{
		if($this->input['id'] && !$this->input['tuji_id'])
		{
			$sql = 'SELECT t.*,p.* FROM '.DB_PREFIX.'pics p LEFT JOIN '.DB_PREFIX.'tuji t ON p.tuji_id = t.id WHERE p.id='.intval($this->input['id']);
			$r = $this->db->query_first($sql);
			//$r['cover_url'] = UPLOAD_ABSOLUTE_URL.$r['cover_url'];
			//$r['new_name'] = UPLOAD_ABSOLUTE_URL.get_upload_dir($r['tuji_id']).$this->settings['thumb']['prefix'].$r['new_name'];
			$this->addItem($r);
			$this->output();
		}
	}
	//设置新封面
	function set_new_cover()
	{
		if($this->input['id'] && $this->input['tuji_id'])
		{
			$sql = 'SELECT new_name FROM '.DB_PREFIX.'pics WHERE id='.intval($this->input['id']);
			$r = $this->db->query_first($sql);
			$sql = 'UPDATE '.DB_PREFIX.'tuji SET cover_url="'.get_upload_dir(intval($this->input['tuji_id'])).$this->settings['thumb']['prefix'].$r['new_name'].'" WHERE id='.intval($this->input['tuji_id']);
			if($this->db->query($sql))
			{
				$this->addItem('success');
			}
			$this->output();
			//$this->Redirect('内容已推荐成功', '', 0, 0, "hg_recommend_call('$id')");
		}
	}
	function delete()
	{
		if(!$this->input['id'])
		{
			return;
		}
		$tuji_sorts_arr = array();//存储节点
		$prms_arr = array();//存储权限控制的信息
		$this->input['id'] = trim($this->input['id']);
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id IN(" .$this->input['id'] .")";
		$r = $this->db->query($sql);
		while($row = $this->db->fetch_array($r))
		{
			$column_id = @unserialize($row['column_id']);
			if($column_id && is_array($column_id))
			{
				$published_column_id = implode(',',array_keys($column_id));
			}
			else
			{
				$published_column_id = '';
			}
			if(intval($row['status'])==1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				publish_insert_query($row,$op);
			}
			$data[$row['id']] = array(
				'title' => $row['title'],
				'delete_people' => trim(urldecode($this->user['user_name'])),
				'cid' => $row['id'],
			);
			$data[$row['id']]['content']['tuji'] = $row;

			$stat_id[] = $row['id'];
			$stat_user_id[] = $row['user_id'];
			$stat_user_name[] = $row['user_name'];
			/***********************************权限数据*****************************************************/
			$prms_arr[$row['id']]['id'] = $row['id'];
			$prms_arr[$row['id']]['user_id'] = $row['user_id'];
			$prms_arr[$row['id']]['published_column_id'] = $published_column_id;
			$prms_arr[$row['id']]['tuji_sort_id'] = $row['tuji_sort_id'];
			$prms_arr[$row['id']]['org_id'] = $row['org_id'];
			$tuji_sorts_arr[] = $row['tuji_sort_id'];
			/***********************************权限数据*****************************************************/
		}

		/***********************************权限控制*****************************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($tuji_sorts_arr)
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'tuji_node WHERE id IN('.implode(',',$tuji_sorts_arr).')';
				$query = $this->db->query($sql);
				$sort_ids_array = array();
				while($row = $this->db->fetch_array($query))
				{
					$sort_ids_array[$row['id']] = $row['parents'];
				}
			}
			if(!empty($prms_arr))
			{
				foreach ($prms_arr as $key => $value)
				{
					if(intval($value['tuji_sort_id']))
					{
						$value['nodes'][$value['tuji_sort_id']] = $sort_ids_array[$value['tuji_sort_id']];
					}
					$this->verify_content_prms($value);
				}
			}
		}
		/***********************************权限控制*****************************************************/
		if(!empty($data))
		{
			foreach($data as $key => $value)
			{
				$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
			}
		}
		$sql = "DELETE FROM ".DB_PREFIX.'tuji WHERE id in('.$this->input['id'].')';
		$this->db->query($sql);

		if($stat_id)
		{
			$stat_data = array(
				'content_id' => implode(',',$stat_id),
				'contentfather_id' => '',
				'type' => 'delete',
				'user_id' => implode(',',$stat_user_id),
				'user_name' => implode(',',$stat_user_name),
				'before_data' => '',
				'last_data' => '',
				'num' => 1,
			);
			$this->addStatistics($stat_data);
			$this->catalog('delete',$stat_data['content_id']);
		}

		$this->addItem($this->input['id']);
		$this->output();
	}
	public function delete_comp()
	{
		if(empty($this->input['cid']))
		{
			return false;
		}
		$id = urldecode($this->input['cid']);
		$sql = 'DELETE FROM '.DB_PREFIX.'pics WHERE tuji_id in('.$id.')';
		$this->db->query($sql);
		$this->delete_pics($id);
		return true;
	}

	private function delete_pics($tuji_id)
	{
		if(!$tuji_id)
		{
			return;
		}
		//需提交图片服务器
	}

	public function move_tuji()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		$sql = "SELECT * FROM ".DB_PREFIX."tuji WHERE id = '".intval($this->input['id'])."'";
		$ret = $this->db->query_first($sql);
		$this->addItem($ret);
		$this->output();
	}

	public function update_move()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$sql = "UPDATE ".DB_PREFIX."tuji SET tuji_sort_id = '".intval($this->input['tuji_sort'])."' WHERE id = '".intval($this->input['id'])."'";
		$this->db->query($sql);
		$sql = "SELECT * FROM ".DB_PREFIX."tuji_node WHERE id = '".intval($this->input['tuji_sort'])."'";
		$ret = $this->db->query_first($sql);
		$this->addItem(array('id'=>intval($this->input['id']),'tuji_sort_name'=>$ret['name']));
		$this->output();
	}
	/**
	 * 
	 * 创建新图集(图片$_FILES信息和图集属性同时提交接口) ...
	 */
	public function createTuji()
	{
		$uploadInfo = array();
		$uploadInfo = $this->uploadFilesPicsProcess();//图片上传处理
		$this->imgsInputProcess($uploadInfo);//imgs 传参处理
		$this->picLinksInputProcess();//pic_links参数处理
		$this->create_tuji();//调用创建图集
	}
	
	/**
	 * 
	 * 图片上传处理（$_FILES） ...
	 */
	private function uploadFilesPicsProcess()
	{
		$uploadInfo = array();
		if($_FILES['uploadimg'])
		{
			foreach ($_FILES['uploadimg'] AS $k => $v)
			{
				foreach ($v as $kk => $vv)
				{
					$_files[$kk][$k] = $vv;
				}
			}
			foreach ($_files as $v)
			{
				$uploadInfo[] = $this->upload_tuji_imgs($v);
			}
		}
		if($_FILES['zipfile'])
		{
			$zipfiles = $_FILES['zipfile'];
			$uploadZipFilesInfo = $this->upload_zip_img($zipfiles);
			$uploadZipFilesInfo && is_array($uploadZipFilesInfo) && $uploadInfo = array_merge($uploadInfo,$uploadZipFilesInfo);
		}
		return $uploadInfo;
	}
	
	/**
	 * 
	 * imgs 模拟传参处理
	 * @param array $uploadInfo 上传图片返回数据
	 */
	private function imgsInputProcess(array $uploadInfo)
	{
		$imgInfo = array();
		$diyImg = $this->input['diyImg'];
		$isisfm = 1;//封面哨兵
		foreach ($uploadInfo as $k => $v)
		{
			$diyConfig = array();
			if($diyConfig = $diyImg[$v['old_name']])
			{
				if(isset($diyConfig['isfm']))
				{
					if($isisfm && $diyConfig['isfm'])
					{
						$isfm = 1;
						$isisfm = 0;	
					} 
					else 
					{
						$isfm = 0;
					}
				}
				if (isset($diyConfig['description']))
				{
					$description = $diyConfig['description'];
				}
				else 
				{
					$description = $v['description'];
				}
			}
			else 
			{
				$isfm = 0;
				$description = $v['description'];
			}
			$imgInfo[] = array(
								'pic_id' => $v['pic_id'],
								'des'    => $description,//图片描述
								'isfm'   => $isfm ,//是否为封面
								'sort'   => $k + 1,//按图集上传顺序
							);
		}
		$this->input['imgs'] = json_encode($imgInfo);		
	}
	
	/**
	 * 
	 * 将pic_links参数的别名参数picLinks格式化为pic_links,优先级比 pic_links高，会覆盖pic_links数据 ...
	 */
	public function picLinksInputProcess()
	{
		$picLinks = array_filter((array)$this->input['picLinks'],'clean_array_null');
		$picLinks && $this->input['pic_links'] = implode("\n", $picLinks);
	}

	public function create_tuji()
	{
		/**********************************权限控制********************************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['tuji_sort_id'])
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'tuji_node WHERE id IN('.$this->input['tuji_sort_id'].')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
			$nodes['_action'] = 'create_tuji';
			$nodes['column_id'] = $this->input['column_id'];
			$this->verify_content_prms($nodes);
		}
		/**********************************权限控制********************************************************/
		if(!$this->input['title'] || urldecode($this->input['title']) == '添加标题')//如果没传图集的名称过来就报错
		{
			$this->errorOutput(TITLE);
		}

		if(urldecode($this->input['comment']) == '图集描述')
		{
			$this->input['comment'] = '';
		}

		$title = ($this->input['title']);
		$sql = "SELECT * FROM ".DB_PREFIX."tuji WHERE title = '{$title}'";
		$arr = $this->db->query_first($sql);
		if($arr['id'])
		{
			//如果已经存在了该标题的图集就需要改标题
			//$this->errorOutput(REPEAT_TITLE);
		}

		$column_id = urldecode($this->input['column_id']);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		if($column_id && is_array($column_id))
		{
			$column_id = serialize($column_id);
		}
		else
		{
			$column_id = '';
		}
		//创建图集
		/*
		 $sql  = " INSERT INTO ".DB_PREFIX."tuji SET ";
		 $sql .= " title = '".$title."',".
		 " tuji_sort_id = '".intval($this->input['tuji_sort_id'])."',".
		 " comment = '".$this->input['comment']."',".
		 " keywords = '".$this->input['keywords']."',".
		 " water_id = '".$this->input['water_id']."',".
		 " url = '".$this->input['url']."',".
		 " user_name = '".$this->user['user_name']."',".
		 " user_id   = '".$this->user['user_id']."',".
		 " org_id   = '".$this->user['org_id']."',".
		 " appid   = '".$this->user['appid']."',".
		 " appname   = '".$this->user['display_name']."',".
		 " create_time = '".TIMENOW."',".
		 " update_time = '".TIMENOW."',".
		 " ip = '".hg_getip()."',".
		 " column_id ='".$column_id."',".
		 " pub_time = '".strtotime($this->input['publish_time'])."'," .
		 " status = -1".",".
		 " weight = '".intval($this->input['weight'])."'";
		 */
		/**************审核权限控制开始**************/
		$status = 0;
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if ($this->user['prms']['default_setting']['create_content_status']==1)
			{
				$status = -1;
			}
			elseif ($this->user['prms']['default_setting']['create_content_status']==2)
			{
				$status = 1;
			}
			elseif ($this->user['prms']['default_setting']['create_content_status']==3)
			{
				$status = 2;
			}
			if ($this->user['prms']['default_setting']['create_content_status'] == 0)
			{
				$status = $this->settings['default_state'] ? $this->settings['default_state']: -1;
			}
		}
		/**************审核权限控制结束**************/
		$insert_data = array(
			'title' 			=> $title,
			'tuji_sort_id' 		=> intval($this->input['tuji_sort_id']),
			'comment' 			=> $this->input['comment'],
			'keywords' 			=> $this->input['keywords'],
			'water_id' 			=> $this->input['water_id'],
			'url' 				=> $this->input['url'],
			'org_id'   			=> $this->input['org_id'] ? intval($this->input['org_id']): intval($this->user['org_id']),
			'user_id'  			=> $this->input['user_id'] ? intval($this->input['user_id']): intval($this->user['user_id']),
			'user_name' 		=> $this->input['user_name'] ? $this->input['user_name'] : $this->user['user_name'],
			'appid' 			=> $this->user['appid'],
			'appname' 			=> $this->user['display_name'],
			'create_time' 		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'column_id' 		=> $column_id,
			'pub_time' 			=> strtotime($this->input['publish_time']),
			'status' 			=> empty($status)?(isset($this->input['status']) ? intval($this->input['status']) : -1):$status,
			'weight' 			=> intval($this->input['weight']),
			'isbold'			=> intval($this->input['isbold']),
			'isitalic'			=> intval($this->input['isitalic']),
			'tcolor'			=> $this->input['tcolor']?trim($this->input['tcolor']):'#000',
			'template_sign'		=> intval($this->input['template_sign']),	//叮当的最佳样式
			'iscomment'			=> intval($this->input['iscomment']) ? 1 : 0,
			'is_praise'			=> intval($this->input['is_praise']) ? 1 : 0,
			/*为了给https://redmine.hoge.cn/issues/3315提供支持，添加作者author和来源source字段*/
			'author'			=> trim($this->input['author']),
			'source'			=> trim($this->input['source']),
		);
		$sql = " INSERT INTO ".DB_PREFIX."tuji SET ";
		foreach ($insert_data AS $k => $v)
		{
			$sql .= " {$k} = '{$v}',";
		}
		$sql = trim($sql,',');
		$this->db->query($sql);
		$tuji_id = $this->db->insert_id();
		$sql = " UPDATE ".DB_PREFIX."tuji SET order_id = '{$tuji_id}'  WHERE id = '{$tuji_id}'";
		$this->db->query($sql);

		//记录发布库栏目分发表
		$this->tuji->update_pub_column($tuji_id, $this->input['column_id']);
		//记录发布库栏目分发表

		//获取图片信息
		$img_data = json_decode(str_replace('&quot;','"',urldecode($this->input['imgs'])),1);
		if($img_data && is_array($img_data) && !empty($img_data))
		{
			$fm_picid = 0;
			foreach($img_data AS $k => $v)
			{
				if($v['isfm'])
				{
					$fm_picid = $v['pic_id'];
				}
				$data = array(
					'description' => $v['des'],
					'is_cover'    => $v['isfm'],
					'order_id' 	  => $v['sort'],
					'tuji_id'	  => $tuji_id,
				);

				$sql = " UPDATE ".DB_PREFIX."pics SET ";
				foreach($data AS $kk => $vv)
				{
					$sql .= "{$kk}"." = "."'{$vv}',";
				}
				$sql  = trim($sql,',');
				$sql .= " WHERE id = '".$v['pic_id']."'";
				$this->db->query($sql);
			}
		}
		if($this->input['img_datas'])//如果直接传素材字段过来
		{
			$this->insert_imgs($tuji_id);
		}
		$link_info=array();
		/********************************此处处理直接填写图片链接的*******************************/
		if($this->input['pic_links'])
		{
			$link_info = $this->uploadImgByLink($tuji_id,$this->input['pic_links'],intval($this->input['water_id']));
		}
		/********************************此处处理直接填写图片链接的*******************************/
		$insert_data['index_id']= 0;
		$insert_data['img_info']=array();
		if($fm_picid)
		{
			$sql = "SELECT img_info FROM ".DB_PREFIX."pics WHERE id = '".$fm_picid."'";
			$img_info = $this->db->query_first($sql);
			$sql = " UPDATE ".DB_PREFIX."tuji SET index_id = '".$fm_picid."',cover_url = '".$img_info['img_info']."' WHERE id = '".$tuji_id."'";
			$this->db->query($sql);
            $insert_data['index_id'] = $fm_picid;
            $insert_data['img_info'] = unserialize($img_info['img_info']);
		}
		//合并返回缩略图
		if(empty($insert_data['index_id'])&&empty($insert_data['img_info'])&&$link_info)
		{
			$insert_data = array_merge($insert_data,$link_info);
		}
		$tuji_pic_count = $this->pic_count($tuji_id);
		if($tuji_pic_count)
		{
			$insert_data =array_merge($insert_data,$tuji_pic_count);
		}
		//放入发布队列
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . $tuji_id;
		$r = $this->db->query_first($sql);
		//加入日志
		$this->addLogs('创建图集', '', $r,$r['title']);
		if(intval($r['status'])==1 && !empty($r['column_id']))
		{
			$op = 'insert';
			publish_insert_query($r,$op);
		}
		$stat_data = array(
				'content_id' => $tuji_id,
				'contentfather_id' => '',
				'type' => 'insert',
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'before_data' => '',
				'last_data' => $this->input['title'],
				'num' => 1,
		);
		$this->addStatistics($stat_data);
		//编目添加
		$this->catalog('create',$tuji_id,'tuji');
		//返回数据
		$insert_data['id'] = $tuji_id;
		$insert_data['app'] = APP_UNIQUEID;
		$insert_data['module'] = MOD_UNIQUEID;
		$this->addItem($insert_data);
		$this->output();
	}

	public function update_tuji()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}

		if(!$this->input['title'] || ($this->input['title']) == '添加标题')//如果没传图集的名称过来就报错
		{
			$this->errorOutput(TITLE);
		}

		if(($this->input['comment']) == '图集描述')
		{
			$this->input['comment'] = '';
		}

		$title = ($this->input['title']);
		$sql = "SELECT * FROM ".DB_PREFIX."tuji WHERE title = '{$title}' AND id != '".intval($this->input['id'])."'";
		$arr = $this->db->query_first($sql);
		if($arr['id'])
		{
			//如果已经存在了该标题的图集就需要改标题
			//$this->errorOutput(REPEAT_TITLE);//为配合叮当不同用户也许会创建相同名称图集特注释.
		}

		//查询修改图集之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."tuji where id = " . intval($this->input['id']);
		$q = $this->db->query_first($sql);
		if(!empty($q))
		{
			$ori_column_id = array();
			$q['column_id'] = unserialize($q['column_id']);
			if(is_array($q['column_id']))
			{
				$ori_column_id = array_keys($q['column_id']);
			}
		}

		$new_column_ids = $this->input['column_id'];
		$column_id = urldecode($this->input['column_id']);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		if($column_id && is_array($column_id))
		{
			$column_id = serialize($column_id);
		}
		else
		{
			$column_id = '';
		}

		/*********************************权限控制****************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['tuji_sort_id'])
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'tuji_node WHERE id IN('.$this->input['tuji_sort_id'].')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$data['nodes'][$row['id']] = $row['parents'];
				}
			}
			$data['id'] = $this->input['id'];
			$data['user_id'] = $q['user_id'];
			$data['org_id']  = $q['org_id'];
			$data['column_id'] = $new_column_ids;
			$data['published_column_id'] = implode(',',$ori_column_id);
			$this->verify_content_prms($data);
			$this->check_weight_prms(intval($this->input['weight']), $q['weight']);
		}
		/*********************************权限控制****************************************/	
		$status = 0;
		//修改审核数据后的状态
		if(!empty($q['column_id']))
		{
			if ($this->user['prms']['default_setting']['update_publish_content']==1)
			{
				$status = -1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==2)
			{
				$status = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_publish_content']==3)
			{
				$status = 2;
			}
		}
		elseif ($q['status']==1)/**************审核权限控制开始**************/
		{
			if ($this->user['prms']['default_setting']['update_audit_content']==1)
			{
				$status = -1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==2)
			{
				$status = 1;
			}
			elseif ($this->user['prms']['default_setting']['update_audit_content']==3)
			{
				$status = 2;
			}
		}
		/**************审核权限控制结束**************/	
		//更新图集
		$sql  = " UPDATE ".DB_PREFIX."tuji SET ";
		$sql .= " title = '".$title."',".
		 		" tuji_sort_id = '".intval($this->input['tuji_sort_id'])."',".
		 		" comment = '".($this->input['comment'])."',".
		 		" keywords = '".($this->input['keywords'])."',".
		 		" water_id = '".($this->input['water_id'])."',".
				" status = ".(empty($status)?'status':$status).",".
				" url = '".($this->input['url'])."',".
				" column_id ='".$column_id."',".
				" weight ='".($this->input['weight'])."',".
				" isbold ='".intval($this->input['isbold'])."',".
				" isitalic ='".intval($this->input['isitalic'])."',".
				" tcolor ='".trim($this->input['tcolor'])."',".
				" pub_time = '".strtotime($this->input['publish_time'])."'," .
				" template_sign = '".intval($this->input['template_sign'])."'," .
				" iscomment = '".intval($this->input['iscomment'])."'," .
				" is_praise = '".intval($this->input['is_praise'])."'," .
				" click_num = '".intval($this->input['click_num'])."'," .
				/*为了给https://redmine.hoge.cn/issues/3315提供支持，添加作者author和来源source字段*/
				" author = '".trim($this->input['author'])."'," .
				" source = '".trim($this->input['source'])."'," .
		 		" ip = '".hg_getip()."' WHERE id = '".intval($this->input['id'])."'";
		$this->db->query($sql);
		if($this->db->affected_rows())
		{
			$append_update_data = array(
			/*
				'user_id' 		=> $this->user['user_id'],
				'user_name' 	=> $this->user['user_name'],
				'org_id'    	=> $this->user['org_id'],
				*/
				'update_time'   => TIMENOW,
			);
			$sql = " UPDATE "  . DB_PREFIX . "tuji SET ";
			foreach ($append_update_data AS $k => $v)
			{
				$sql .= " {$k} = '{$v}',";
			}
			$sql  = trim($sql,',');
			$sql .= " WHERE id = '" .intval($this->input['id']). "'";
			$this->db->query($sql);
		}

		//记录发布库栏目分发表
		$this->tuji->update_pub_column(intval($this->input['id']), $this->input['column_id']);
		//记录发布库栏目分发表

		//发布系统
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . intval($this->input['id']);
		$ret = $this->db->query_first($sql);

		$this->addLogs('更新图集',$arr, $ret,$ret['title']);

		//更改文章后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}

		//获取图片信息
		$img_data = json_decode(str_replace('&quot;','"',urldecode($this->input['imgs'])),1);
		if ($img_data && is_array($img_data) && !empty($img_data))
		{
			foreach($img_data AS $k => $v)
			{
				if($v['isfm'])
				{
					$fm_picid = $v['pic_id'];
				}
				$data = array(
					'description' => $v['des'],
					'is_cover'    => $v['isfm'],
					'order_id' 	  => $v['sort'],
					'tuji_id'	  => intval($this->input['id']),
				);
	
				$sql = " UPDATE ".DB_PREFIX."pics SET ";
				foreach($data AS $kk => $vv)
				{
					$sql .= "{$kk}"." = "."'{$vv}',";
				}
				$sql  = trim($sql,',');
				$sql .= " WHERE id = '".$v['pic_id']."'";
				$this->db->query($sql);
				if(intval($ret['status']) == 1)
				{
					if(!empty($ret['expand_id']))
					{
						if($this->db->affected_rows())
						{
							$v['id'] = $v['pic_id'];
							publish_insert_query($v, 'update',$new_column_id,1,'des');
						}
					}
				}
			}
		}
		
		
		
		/********************************此处处理直接填写图片链接的*******************************/
		if($this->input['pic_links'])
		{
			$this->uploadImgByLink($this->input['id'],$this->input['pic_links'],intval($this->input['water_id']));
		}
		/********************************此处处理直接填写图片链接的*******************************/

		if($fm_picid)
		{
			$sql = "SELECT img_info FROM ".DB_PREFIX."pics WHERE id = '".$fm_picid."'";
			$img_info = $this->db->query_first($sql);
			$sql = " UPDATE ".DB_PREFIX."tuji SET index_id = '".$fm_picid."',cover_url = '".$img_info['img_info']."' WHERE id = '".intval($this->input['id'])."'";
			$this->db->query($sql);
		}
		elseif(empty($this->input['pic_links']))
		{
			$sql = " UPDATE ".DB_PREFIX."tuji SET index_id = 0,cover_url = '' WHERE id = '".intval($this->input['id'])."'";
			$this->db->query($sql);
		}
		$this->pic_count($this->input['id']);
		if(intval($ret['status']) == 1)
		{
			if(!empty($ret['expand_id'])) //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($ret, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($ret, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($ret, 'update',$same_column);
					//有新图片时需插入子队列
					publish_insert_query($ret, 'insert',$same_column,1);
				}
			}
			else     //未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($ret,$op);
			}
		}
		else
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				publish_insert_query($ret,$op);
			}
		}

		if($ret['id'])
		{
			$stat_data = array(
				'content_id' => $ret['id'],
				'contentfather_id' => '',
				'type' => 'update',
				'user_id' => $ret['user_id'],
				'user_name' => $ret['user_name'],
				'before_data' => '',
				'last_data' => $ret['title'],
				'num' => 1,
			);
			$this->addStatistics($stat_data);
		}

		//返回之后的数据
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . intval($this->input['id']);
		$_newData = $this->db->query_first($sql);

		//编目更新
		$this->catalog('update',$this->input['id'],'tuji',$ret['catalog']);
		$this->addItem($_newData);
		$this->output();
	}
	
	/**
	 * 
	 * 统计图集图片数量 ...
	 */
	private function pic_count($tuji_id)
	{
		$tuji_id = intval($tuji_id);
		$sql = "SELECT count(*) as total_pic FROM " . DB_PREFIX ."pics WHERE tuji_id = " .$tuji_id;
		$total_pic = $this->db->query_first($sql);
		$this->db->update_data($total_pic, 'tuji',' id ='.$tuji_id);
		return $total_pic?$total_pic:array();
	}

	//根据链接上传图片到制定图集
	private function uploadImgByLink($tuji_id = 0,$pic_link  = '',$water_id = 0)
	{
		if(!$tuji_id || !$pic_link)
		{
			return false;
		}
		$sql = 'SELECT count(*) AS total FROM '.DB_PREFIX.'pics WHERE tuji_id ='.$tuji_id.' AND is_cover=1';
		$is_cover_num=$this->db->query_first($sql);
		$is_cover = 1;
		$is_cover_num['total']>0&&$is_cover=0;
		//将图片链接提交到图片服务器
		$pic_link_arr = explode("\n",$pic_link);
		$pic_link_arr = array_unique($pic_link_arr);
		$briefs_array = explode("|||", $this->input['briefs']);
		$data=array();
		if($pic_link_arr)
		{
			foreach ($pic_link_arr AS $k => $link_url)
			{
				if(!$link_url)
				{
					continue;
				}
				$img_info = $this->material->localMaterial($link_url,'','',$water_id);
				if($img_info && $img_info[0])
				{
					$img_info = $img_info[0];
					if($img_info['error'] || !$img_info['imgwidth'] || !$img_info['imgheight'])
					{
						continue;
					}
					//查询当前的最大的order_id
					$sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "pics WHERE tuji_id = '" .$tuji_id. "'";
					$total_arr = $this->db->query_first($sql);
					if($total_arr)
					{
						$pic_order_id = intval($total_arr['total'] + 1);
					}
					else
					{
						$pic_order_id = 1;
					}
					$file_name = basename($link_url);
					$file_name_info = no_extension_file_name($file_name);
					$description = DESCRIPTION_TYPE==1?$file_name_info['filename']:'';//如果为1，则采用文件名为描述。否则留空
					//入图片库
					$pic_link_data = array(
						'tuji_id' 		=> $tuji_id,
						'old_name'		=> $file_name,
						'new_name'		=> $img_info['filename'],
						'description'	=> $briefs_array[$k] ? $briefs_array[$k] : $description,
						'material_id' 	=> $img_info['id'],
						'water_id'		=> $water_id,
						'is_cover'      => $k == 0&&$is_cover?1:0,
						'path'			=> $img_info['filepath'],
						'img_info' 		=> serialize(array('host' => $img_info['host'],'dir' => $img_info['dir'],'filepath' =>$img_info['filepath'],'filename' =>$img_info['filename'],'imgwidth' => $img_info['imgwidth'],'imgheight' => $img_info['imgheight'])),
						'ip'			=> hg_getip(),
						'user_id' 		=> $this->user['user_id'],
						'user_name' 	=> $this->user['user_name'],
						'appname' 		=> $this->user['display_name'],
						'appid' 		=> $this->user['appid'],
						'create_time'	=> TIMENOW,
						'order_id'		=> $pic_order_id,
					);

					//图片数据入库
					$sql = "INSERT INTO ".DB_PREFIX.'pics SET ';
					foreach($pic_link_data as $key=>$v)
					{
						$sql .= " `{$key}` = '{$v}',";
					}
					$this->db->query(rtrim($sql,','));
					$inser_id = $this->db->insert_id();
					//首个图片作为封面
					if($k == 0&&$is_cover)
					{	$data['index_id']=$inser_id;
						$data['img_info']=@unserialize($pic_link_data['img_info']);
						$this->db->query('UPDATE ' . DB_PREFIX . 'tuji SET index_id='.$inser_id.',cover_url = \''.addslashes($pic_link_data['img_info']).'\' WHERE id = '.$tuji_id);
					}
				}
			}
		}
		return $data;
	}
	/**
	 *
	 * 图片信息入pics表 ...
	 */
	public function insert_imgs($tuji_id)
	{
		if(!$tuji_id || empty($this->input['img_datas']))
		{
			return false;
		}
		$sql = 'SELECT count(*) AS total FROM '.DB_PREFIX.'pics WHERE tuji_id ='.$tuji_id.' AND is_cover=1';
		$is_cover_num=$this->db->query_first($sql);
		$is_cover = 1;
		$is_cover_num['total']>0&&$is_cover =  0;
		$img_datas=$this->input['img_datas'];
		if($img_datas)
		{
			foreach ($img_datas AS $k => $img_info)
			{
				if($img_info)
				{
					//查询当前的最大的order_id
					$sql = "SELECT count(*) AS total FROM " .DB_PREFIX. "pics WHERE tuji_id = '" .$tuji_id. "'";
					$total_arr = $this->db->query_first($sql);
					if($total_arr)
					{
						$pic_order_id = intval($total_arr['total'] + 1);
					}
					else
					{
						$pic_order_id = 1;
					}
					$file_name_info = no_extension_file_name($img_info['filename']);
					$description = DESCRIPTION_TYPE==1?$file_name_info['filename']:'';//如果为1，则采用文件名为描述。否则留空
					//入图片库
					$img_data = array(
						'tuji_id' 		=> $tuji_id,
						'old_name'		=> $img_info['filename'],
						'new_name'		=> $img_info['filename'],
						'description'	=> $img_info['description'] ? $img_info['description'] : $description,
						'material_id' 	=> $img_info['id'],
						'path'			=> $img_info['filepath'],
						'is_cover'		=> $k == 0 && $is_cover ? 1: 0,
						'img_info' 		=> serialize(array('host' => $img_info['host'],'dir' => $img_info['dir'],'filepath' =>$img_info['filepath'],'filename' =>$img_info['filename'],'imgwidth' => $img_info['imgwidth'],'imgheight' => $img_info['imgheight'])),
						'ip'			=> hg_getip(),
						'user_id'  		=> $this->input['user_id'] ? intval($this->input['user_id']): intval($this->user['user_id']),
						'user_name' 	=> $this->input['user_name'] ? $this->input['user_name'] : $this->user['user_name'],
						'appname' 		=> $this->user['display_name'],
						'appid' 		=> $this->user['appid'],
						'create_time'	=> TIMENOW,
						'order_id'		=> $pic_order_id,
					);

					//图片数据入库
					$sql = "INSERT INTO ".DB_PREFIX.'pics SET ';
					foreach($img_data as $key=>$v)
					{
						$sql .= " `{$key}` = '{$v}',";
					}
					$this->db->query(rtrim($sql,','));
					$inser_id = $this->db->insert_id();
					//首个图片作为封面
					if($k == 0&&$is_cover)
					{
						$this->db->query('UPDATE ' . DB_PREFIX . 'tuji SET index_id='.$inser_id.',cover_url = \''.addslashes($img_data['img_info']).'\' WHERE id = '.$tuji_id);
					}
				}
			}
		}
	}

	//上传图片
	public function upload_tuji_imgs(array $_files = array())
	{
		/*将图片提交到图片服务器*/
		if(!$_FILES['videofile'] && ! $_files)
		{
			$this->errorOutput('请选择上传文件');
		}

		//水印的id
		$water_id = intval($this->input['water_id']);

		//$_FILES['videofile'] && $files['Filedata'] = $_FILES['videofile'];
		$_files ? $files['Filedata'] = $_files : $_FILES['videofile'] && $files['Filedata'] = $_FILES['videofile'];
		if($files['Filedata'])
		{
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($files,0,0,$water_id);
			$img_thumb_info = hg_fetchimgurl($img_info, 160);
			$file_name_info = no_extension_file_name($files['Filedata']['name']);
			$description = DESCRIPTION_TYPE==1?$file_name_info['filename']:'';//如果为1，则采用文件名为描述。否则留空
			$data = array(
				'tuji_id' => 0,
				'old_name'=> $files['Filedata']['name'],
				'new_name'=> $img_info['filename'],
				'description'=>$description,
				'material_id' => $img_info['id'],
				'create_time'=>TIMENOW,
				'water_id'=>$water_id,
				'path'=>$img_info['filepath'],
				'img_info' => serialize(array('host' => $img_info['host'],'dir' => $img_info['dir'],'filepath' =>$img_info['filepath'],'filename' =>$img_info['filename'],'imgwidth' => $img_info['imgwidth'],'imgheight' => $img_info['imgheight'])),
				'ip'=>hg_getip(),
				'url' => urldecode($this->input['url']),
				'user_id' => $this->user['user_id'],
				'user_name' => $this->user['user_name'],
				'appname' => $this->user['display_name'],
				'appid' =>  $this->user['appid'],
			);

			//图片数据入库
			$sql = "INSERT INTO ".DB_PREFIX.'pics SET ';
			foreach($data as $key=>$v)
			{
				$sql .= " `{$key}` = '{$v}',";
			}
			$this->db->query(rtrim($sql,','));
			$vid = $this->db->insert_id();
			$ret_data = array('old_name' => $files['Filedata']['name'] ,'pic_src'=>$img_thumb_info,'img_flag' => 1,'pic_id' =>$vid,'host' => $img_info['host'],'dir' =>$img_info['dir'],'filepath' => $img_info['filepath'],'filename' => $img_info['filename'],'description'=>$description,'material_id' => $img_info['id']);
			if($_files)
			{
				return $ret_data;
			}
			$this->addItem($ret_data);
			$this->output();
		}
	}

	public function upload_zip_img(array $zipfiles = array())
	{
		/*将图片提交到图片服务器*/
		if(!$_FILES['zipfile'] && ! $zipfiles)
		{
			$this->errorOutput('请选择上传文件');
		}

		$water_id = intval($this->input['water_id']);

		//$_FILES['zipfile'] && $files['Filedata'] = $_FILES['zipfile'];
		$zipfiles ? $files['Filedata'] = $zipfiles : $_FILES['zipfile'] && $files['Filedata'] = $_FILES['zipfile'];
		if($files['Filedata'])
		{
			//判断是不是zip类型，如果是zip类型可以提交到图片服务器unzip_img方法
			$typetmp = explode('.',$files['Filedata']['name']);
			$filetype = strtolower($typetmp[count($typetmp)-1]);
			if($filetype != 'zip')
			{
				$this->errorOutput('不是zip类型');
			}
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($files,'','',$water_id,1);
			$ret_data = array();
			if($img_info && !empty($img_info))
			{
				foreach($img_info AS $k => $v)
				{
					$file_name_info = no_extension_file_name($v['name']);
					$description = DESCRIPTION_TYPE==1?$file_name_info['filename']:'';//如果为1，则采用文件名为描述。否则留空
					$data = array(
						'tuji_id' => 0,
						'old_name'=> $v['name'],
						'new_name'=> $v['filename'],
						'description'=>$description,
						'material_id' => $v['id'],
						'create_time'=>TIMENOW,
						'water_id'=>$water_id,
						'path'=>$v['filepath'],
						'img_info' => serialize(array('host' => $v['host'],'dir' => $v['dir'],'filepath' =>$v['filepath'],'filename' =>$v['filename'],'imgwidth' => $img_info['imgwidth'],'imgheight' => $img_info['imgheight'])),
						'ip'=>hg_getip(),
						'url' => urldecode($this->input['url']),
						'user_id' => $this->user['user_id'],
						'user_name' => $this->user['user_name'],
						'appname' => $this->user['display_name'],
						'appid' =>  $this->user['appid'],
					);

					//图片数据入库
					$sql = "INSERT INTO ".DB_PREFIX.'pics SET ';
					foreach($data as $key => $value)
					{
						$sql .= " `{$key}` = '{$value}',";
					}
					$this->db->query(rtrim($sql,','));
					$vid = $this->db->insert_id();
					$ret_data[] = array(
						'old_name'=> $v['name'],
						'pic_src'=> hg_fetchimgurl($v, 160),
						'img_flag' => 1,
						'pic_id' =>$vid,
						'host' => $v['host'],
						'dir' =>$v['dir'],
						'filepath' => $v['filepath'],
						'filename' => $v['filename'],
						'description' =>$description,
						'material_id' => $v['id']
					);
				}
			}
			if($zipfiles)
			{
				return $ret_data;
			}
			$this->addItem($ret_data);
			$this->output();
		}
	}

	function upload_indexpic()
	{
		//外链索引图片
		if(!$_FILES['indexpic']['error'] && is_array($_FILES['indexpic']))
		{
			$material = parent::upload_indexpic();
			if(!empty($material))
			{
				/*$sql = "REPLACE INTO " . DB_PREFIX ."material SET ";
				 $material['material_id'] = $material['id'];
				 $material['pic'] = array(
					'host' => $material['host'],
					'dir' => $material['dir'],
					'filepath' => $material['filepath'],
					'filename' => $material['filename'],
					);
					$material['pic'] = serialize($material['pic']);
					unset($material['bundle_id'], $material['mid'], $material['id'], $material['url']);
					$sql_extra = $space ='';
					foreach($material as $k => $v)
					{
					$sql_extra .= $space . $k . "='" . $v . "'";
					$space = ',';
					}
					$this->db->query($sql . $sql_extra);
					*/
				$material['filesize'] = hg_bytes_to_size($material['filesize']);
				$material['success'] = true;
				$material['id'] = $material['material_id'];
				$this->addItem($material);
			}
			else
			{
				$return = array(
					'success' => false,
					'error' => '文件上传失败',
				);
				$this->addItem($return);
			}
		}
		else
		{
			$return = array(
				'success' => false,
				'error' => '文件上传失败',
			);
			$this->addItem($return);
		}
		$this->output();
	}


	/**
	 * 修改权重
	 *
	 */
	private function check_weight_prms($input_weight =  0, $org_weight = 0)
	{
		if($this->user['group_type'] < MAX_ADMIN_TYPE)
		{
			return;
		}
		$set_weight_limit = $this->user['prms']['default_setting']['set_weight_limit'];
		if(!$set_weight_limit)
		{
			return;
		}
		if($org_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
		if($input_weight > $set_weight_limit)
		{
			$this->errorOutput(MAX_WEIGHT_LIMITED);
		}
	}

	function update_weight()
	{
		if(empty($this->input['data']))
		{
			$this->errorOutput(NODATA);
		}
		$data = htmlspecialchars_decode($this->input['data']);
		$data = json_decode($data,1);
		$id = @array_keys($data);
		if(!$id)
		{
			$this->errorOutput(INVALID_TUJI);
		}
		$sql = 'SELECT id,weight FROM '.DB_PREFIX.'tuji WHERE id IN('.implode(',', $id).')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$org_weight[$row['id']] = $row['weight'];
		}
		$sql = "CREATE TEMPORARY TABLE tmp (id int primary key,weight int)";
		$this->db->query($sql);
		$sql = "INSERT INTO tmp VALUES ";
		$space = '';
		foreach ($data as $k => $v)
		{
			$sql .= $space . '(' . $k . ', ' . $v . ')';
			$this->check_weight_prms($v, $org_weight[$k]);
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "UPDATE " . DB_PREFIX ."tuji t, tmp SET t.weight = tmp.weight WHERE t.id = tmp.id";
		$this->db->query($sql);

		//发布系统
		//		$ids = array_keys($data);
		//		$ids = $ids ? implode(',',$ids) : $ids;
		//		$sql = "SELECT  * FROM  " . DB_PREFIX . "tuji WHERE id IN(" . $ids . ")";
		//		$ret = $this->db->query($sql);
		//		while($row = $this->db->fetch_array($ret))
		//		{
		//			if($row['expand_id'])  //如果为真则已经发布过
		//			{
		//				$op = "update";
		//				publish_insert_query($row,$op);
		//			}
		//		}
		$this->addItem('success');
		$this->output();
	}

	public function push_special()
	{
		$id_arr = explode(',',$this->input['id']);
		$spe_idarr = explode(',',$this->input['special_id']);
		$col_namearr = explode(',',$this->input['column_name']);
		$col_idarr = explode(',',$this->input['col_id']);
		$sname_idarr = explode(',',$this->input['show_name']);
		if(!$spe_idarr)
		{
			$this->errorOutput('NO_ID');
		}
		$spe_arr = array();
		if($col_idarr)
		{
			foreach($col_idarr as $k=>$v)
			{
				if($v)
				{
					$spe_arr[$v]['id'] = $v;
					$spe_arr[$v]['name'] = $col_namearr[$k];
					$spe_arr[$v]['special_id'] = $spe_idarr[$k];
					$spe_arr[$v]['show_name'] = $sname_idarr[$k];
				}
			}
		}
		if($id_arr)
		{
			foreach($id_arr as $k=>$v)
			{
				$sql = "UPDATE " . DB_PREFIX ."tuji SET special = '". serialize($spe_arr) ."' WHERE id = " . $v;
				$this->db->query($sql);

				$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . $v;
				$q = $this->db->query_first($sql);
				if($q['expand_id'])
				{
					//插发布队列
					$q['column_id'] = unserialize($q['column_id']);
					$ori_column_id = array();
					if(is_array($q['column_id']))
					{
						$ori_column_id = array_keys($q['column_id']);
					}
					publish_insert_query($q, 'update',$ori_column_id);
				}
			}
		}

		$this->addItem('true');
		$this->output();
	}


	public function publish()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('No Id');
		}
		$pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
		$column_id = urldecode($this->input['column_id']);
		$isbatch = strpos($id, ',');
		if($isbatch !== false && !$column_id)
		{
			$this->addItem(true);
			$this->output();
		}
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();
		$column_id = $this->publish_column->get_columnname_by_ids('id,name,parents',$column_id);
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id IN( " . $id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$row['column_id'] = unserialize($row['column_id']);
			$ori_column_id = array();
			if(is_array($row['column_id']))
			{
				$ori_column_id = array_keys($row['column_id']);
			}
			$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
			if($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
			{
				$row['column_id'] = is_array($row['column_id']) ? ($row['column_id']+$column_id) : $column_id;
			}
			else
			{
				$row['column_id'] = $column_id;
			}
			$new_column_id = array_keys($row['column_id']);
			/***************************权限控制***************************************/
			//批量签发时只能新增 所以对比已经发布的栏目，会导致没有权限发布
			$published_column_id = ($isbatch !== false) ? $this->input['column_id'] : $ori_column_id_str;
			$this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$published_column_id));
			/***************************权限控制***************************************/
			$sql = "UPDATE " . DB_PREFIX ."tuji SET column_id = '". addslashes(serialize($row['column_id'] )) ."',pub_time ='".$pub_time."' WHERE id = " . $row['id'];
			$this->db->query($sql);

			//记录发布库栏目分发表
			$this->tuji->update_pub_column($row['id'], implode(',', $new_column_id));
			//记录发布库栏目分发表

			if(intval($row['status']) ==1)
			{
				if(!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						publish_insert_query($row, 'delete',$del_column);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						publish_insert_query($row, 'insert',$add_column);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						publish_insert_query($row, 'update',$same_column);
					}
				}
				else 							//未发布，直接插入
				{
					if ($new_column_id) {
						$op = "insert";
						publish_insert_query($row,$op);
					}
				}
			}
			else    //打回
			{
				if(!empty($row['expand_id']))
				{
					$op = "delete";
					publish_insert_query($row,$op);
				}
			}
		}
		$this->addItem('true');
		$this->output();
	}


	//插入发布队列
	private function publish_tuji($id,$op,$column_id = array(),$child_queue = 0,$is_childId = 0)
	{
		$id = intval($id);
		if(empty($id))
		{
			return false;
		}
		if(empty($op))
		{
			return false;
		}
		if($is_childId)
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."pics WHERE id = " . $id;
		}
		else
		{
			$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . $id;
		}
		$info = $this->db->query_first($sql);

		if(empty($column_id))
		{
			$info['column_id'] = unserialize($info['column_id']);
			if(is_array($info['column_id']))
			{
				$column_id = array_keys($info['column_id']);
				$column_id = implode(',',$column_id);
			}
		}
		else
		{
			$column_id = implode(',',$column_id);
		}

		include_once (ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 		=> PUBLISH_SET_ID,
			'from_id' 		=> $id,
			'class_id' 		=> 0,
			'column_id' 	=> $column_id,
			'title' 		=> $info['title'],
			'action_type'	=> $op,
			'publish_time'	=> $info['pub_time'],
			'publish_people'=> $this->user['user_name'],
			'ip'=> hg_getip(),
		);
		if($child_queue)
		{
			$data['set_id'] = PUBLISH_SET_SECOND_ID;
		}
		if($is_childId)
		{
			$data['title'] = $info['old_name'];
		}
		$ret = $plan->insert_queue($data);
		return $ret;
	}
	//图集评论计数更新
	function update_comment_count()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		//评论数目
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
		if($type)
		{
			$sql = "UPDATE ".DB_PREFIX."tuji  SET  total_comment=total_comment". $type . $comment_count . "  WHERE id = ".$id;
			$this->db->query($sql);
			$sql = "SELECT id, expand_id, title, column_id, pub_time,status,user_name FROM " . DB_PREFIX ."tuji WHERE id = " . $id ;
			$info = $this->db->query_first($sql);
		}
		if($info['status'] == 1)
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
		else if($info['status'] == 2)
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
		$data = array('id' => $id,'status' => $info['status']);
		$this->addItem($data);
		$this->output();
	}
	/**
	 * 移动
	 */
	public function move()
	{
		$id = urldecode($this->input['content_id']);
		$node_id = urldecode($this->input['node_id']);
		if(!$id)
		{
			$this->errorOutput('文章ID不能为空');
		}
		if($node_id)
		{
			$this->db->update_data(array('tuji_sort_id'=>$node_id), 'tuji', ' id IN('.$id.')');
		}
		$ret = array('success' => true, 'id' => $id);
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
		$id = intval($this->input['id']);
		$data = array();
		if($this->input['click_num'])
		$data['click_num'] = intval($this->input['click_num']);
		if(!empty($data) && is_array($data))
		{
			$sql = "UPDATE ".DB_PREFIX."tuji SET ";
			$space = '';
			foreach($data as $k => $v)
			{
				$sql.= $space . $k ."='".$v."'";
				$space = ',';
			}
			$sql .= " WHERE id = " . $id;
			$this->db->query($sql);
		}
		$this->addItem($data);
		$this->output();
	}
	 
	function change_material_water() {
		if (!$this->input['material_id']) {
			$this->errorOutput(NO_MATERIAL_ID);
		}
		
		/*********************************权限控制****************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
					//查询修改图集之前已经发布到的栏目
			$sql = "select * from " . DB_PREFIX ."tuji where id = " . intval($this->input['id']);
			$q = $this->db->query_first($sql);
			if($this->input['tuji_sort_id'])
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'tuji_node WHERE id IN('.$this->input['tuji_sort_id'].')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$data['nodes'][$row['id']] = $row['parents'];
				}
			}

			$data['_action']=trim($this->input['type']);
			$data['id'] = $this->input['id'];
			$data['user_id'] = $q['user_id'];
			$data['org_id']  = $q['org_id'];
			$this->verify_content_prms($data);
		}
		/*********************************权限控制****************************************/
		$material_id = $this->input['material_id'];
		$water_id = intval($this->input['water_id']);
		$ret = $this->material->change_material_water($material_id, $water_id);
		if ($ret) {
			if(is_string($material_id))
			{ 
				$material_id=@explode(',', $material_id);
				if(is_array($material_id))
				{
					foreach ($material_id as $v)
					{
						$this->update_pics(array('water_id'=>$water_id), array('material_id'=>$v));
					}
				}
			}
			$this->addItem(true);
		}
		else {
			$this->errorOutput(CHANGE_FALSE);
		}
		$this->output();
	}
	/**
	 * 更新图片信息 ...
	 * @param Array $data 数据
	 * @param Array $idsArr 条件
	 * @param Boolean $flag
	 */
	function update_pics($data, $idsArr, $flag = false)
	{
			
		if (!is_array($data) || !is_array($idsArr)) return false;
		$fields = '';

		foreach ($data as $k => $v)
		{
			if ($flag)
			{
				$v = $v > 0 ? '+' . $v : $v;
				$fields .= $k . '=' . $k . $v . ',';
			}
			else
			{
				if (is_string($v))
				{
					$fields .= $k . "='" . $v . "',";
				}
				elseif (is_int($v) || is_float($v))
				{
					$fields .= $k . '=' . $v . ',';
				}
			}
		}
		$fields = rtrim($fields, ',');
		$sql = 'UPDATE ' . DB_PREFIX . 'pics SET ' . $fields . ' WHERE 1';
		if ($idsArr)
		{
			foreach ($idsArr as $key => $val)
			{
				if (is_int($val) || is_float($val)||is_numeric($val))
				{
					$sql .= ' AND ' . $key . ' = ' . $val;
				}
				elseif (is_string($val)&&(stripos($member_id, ',')!==false))
				{
					$sql .= ' AND ' . $key . ' in (\'' . $val . '\')';
				}
				elseif (is_array($var))
				{
					$sql .= ' AND ' . $key . ' in (\'' .implode("','", $val ) . '\')';
				}
				elseif(is_string($val))
				{
					$sql .= ' AND ' . $key . ' = \'' . $val . '\'';
				}
			}
		}		
		$res=$this->db->query($sql);
		if ($idsArr&&$res)
		{
			return $idsArr;
		}
		return false;

	}

	/**
	 * 内容管理下更换内容的栏目
	 */
	public function editColumnsById()
	{
		$id = intval($this->input['id']);
		$column_id = intval($this->input['column_id']);
		$updateArray = array();
		//查询修改图集之前已经发布到的栏目
		$sql = "select * from " . DB_PREFIX ."tuji where id = " . $id;
		$q = $this->db->query_first($sql);
		if(!empty($q))
		{
			$ori_column_id = array();
			$q['column_id'] = unserialize($q['column_id']);
			if(is_array($q['column_id']))
			{
				$ori_column_id = array_keys($q['column_id']);
			}
		}
		
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$publish_column = new publishconfig();
		$result = $publish_column->get_columnname_by_ids('id,name',$column_id);
		$updateArray['column_id'] = serialize($result);
		//修改vote_question中column_id
		$sql = "UPDATE ".DB_PREFIX."tuji  SET  status=1 , column_id = '". serialize($result) ."'  WHERE id IN (".$id.")";
		$updateC = $this->db->query($sql);
		
		//记录发布库栏目分发表
		$this->tuji->update_pub_column($id,$column_id);
		//记录发布库栏目分发表
		
		//发布系统
		$sql = "SELECT * FROM " . DB_PREFIX ."tuji WHERE id = " . intval($this->input['id']);
		$ret = $this->db->query_first($sql);
		
		//更改文章后发布的栏目
		$ret['column_id'] = unserialize($ret['column_id']);
		$new_column_id = array();
		if(is_array($ret['column_id']))
		{
			$new_column_id = array_keys($ret['column_id']);
		}
		if(intval($ret['status']) == 1)
		{
			if(!empty($ret['expand_id'])) //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($ret, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($ret, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($ret, 'update',$same_column);
					//有新图片时需插入子队列
					publish_insert_query($ret, 'insert',$same_column,1);
				}
			}
			else     //未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($ret,$op);
			}
		}
		else
		{
			if(!empty($ret['expand_id']))
			{
				$op = "delete";
				publish_insert_query($ret,$op);
			}
		}
		if($updateC)
		{
			$this->addItem($updateArray);
		}
		$this->output();
	}
	
	function unknow()
	{
		$this->errorOutput(NOMETHOD);
	}
	
	/**
	 * 图集赞或者取消赞的时候，更新赞的次数
	 */
	public function update_praise_count()
	{
		$id = intval($this->input['content_id']);
		$operate = trim($this->input['operate']);
		$num = intval($this->input['num']);
		if(!$num)
		{
			$num = 1;
		}
		$info = array();
		if($operate == 'add')
		{
			$type = "+";
		}
		elseif($operate == 'cancel')
		{
			$type = '-';
		}
		$sql = "UPDATE ".DB_PREFIX."tuji  SET  praise_count=praise_count". $type . $num . "  WHERE id = ".$id;
		$this->db->query($sql);
		$sql = "SELECT id, expand_id, title, column_id, pub_time,status,user_name FROM " . DB_PREFIX ."tuji WHERE id = " . $id ;
		$info = $this->db->query_first($sql);
		
		if($info['status'] == 1)
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
		else if($info['status'] == 2)
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
		$data = array('id' => $id,'status' => $info['status']);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 文稿移到垃圾箱
	 */
	public function moveToTrash()
	{
		$id = intval($this->input['id']);
		$photo_id = intval($this->input['photo_id']);
// 		$info = $this->tuji->get_article(' id ='.$photo_id);
		$sql = "select * from ".DB_PREFIX."tuji where id = ".$photo_id;
		$info = $this->db->query_first($sql);
		
		$info['column_id'] = @unserialize($info['column_id']);
		//取消文稿库中column的关系
		//取消article
		$update_sql = "update ".DB_PREFIX."tuji set column_id = '',column_url = '' where id = ".$photo_id;
		$this->db->query($update_sql);
		//delete  pub_column
		$delete_sql = "delete from ".DB_PREFIX."pub_column where aid = ".$photo_id;
		$this->db->query($delete_sql);
		//删除发布库
		$op = "delete";
		publish_insert_query($info, $op);
		$this->addItem(array('return'=>true));
		$this->output();
	}

}
$out = new tuji_update();
$action = $_INPUT['a'];
if(!$_INPUT['a'])
{
	$action = 'unknow';
}
$out->$action();
?>