<?php
define('MOD_UNIQUEID','tv_play');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/tv_play_mode.php');
require_once(ROOT_PATH.'lib/class/material.class.php');
require_once(ROOT_PATH.'lib/class/curl.class.php');
require_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/GetPinyinByChinese.php');
require_once(ROOT_PATH .'lib/class/recycle.class.php');
class tv_play_update extends adminUpdateBase
{
	private $mode;
	private $initial;
	private $recycle;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new TVPlayMode();
		$this->publish_column = new publishconfig();
		$this->initial = new GetPinyinByChinese();
		$this->recycle = new recycle();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/******************************************基本操作***********************************************/
	
	public function sort(){}

	public function create()
	{
		/**********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['play_sort_id'])
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'play_sort WHERE id IN('.$this->input['play_sort_id'].')';
				$query = $this->db->query($sql);
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
			$nodes['column_id'] = $this->input['column_id'];
			$this->verify_content_prms($nodes);
		}
		/**********************************权限控制*******************************************/
		
		if(!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		/***********************************发布栏目*****************************************/
		$column_id = $this->input['column_id'];
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		if($column_id && is_array($column_id))
		{
			$column_id = serialize($column_id);		
		}
		else 
		{
			$column_id = '';
		}
		/***********************************发布栏目*****************************************/
		
		/***********************************构建数据*****************************************/
		//获取标题的首字母
		$initial_arr = $this->initial->Pinyin($this->input['title']);
		$initial = $initial_arr[2][0];
		if(!in_array($initial,$this->mode->get_initial()))
		{
			$initial = '';
		}
		
		$data = array(
			'title' 			=> trim($this->input['title']),
			'initial' 			=> $initial,
			'brief' 			=> ($this->input['brief'] && $this->input['brief'] != '这里输入描述')?$this->input['brief']:'',
			'director' 			=> $this->input['director'],
			'main_performer' 	=> $this->input['main_performer'],
			'type' 				=> $this->input['type'],
			'play_sort_id' 		=> $this->input['play_sort_id'],
			'district' 			=> $this->input['district'],
			'lang' 				=> $this->input['lang'],
			'year' 				=> $this->input['year'],
			'playcount' 		=> $this->input['playcount'],
			'duration' 			=> $this->input['duration'],
			'awards' 			=> $this->input['awards'],
			'copyright_limit' 	=> strtotime($this->input['copyright_limit'])?strtotime($this->input['copyright_limit']):0,
			'publisher' 		=> $this->input['publisher'],
			'play_grade' 		=> $this->input['play_grade'],
			'user_name'			=> $this->user['user_name'],
			'user_id'			=> $this->user['user_id'],
			'org_id'			=> $this->user['org_id'],
			'create_time'		=> TIMENOW,
			'update_time'		=> TIMENOW,
			'ip'				=> hg_getip(),
			'column_id'			=> $column_id,
			'pub_time'			=> strtotime($this->input['publish_time']),
			'weight' 			=> intval($this->input['weight']),
			'publish_auto'		=> intval($this->input['publish_auto']),
			'publish_num_day'	=> intval($this->input['publish_num_day']),
		);
		/***********************************构建数据*****************************************/
		
		/***********************************电视剧的索引图************************************/
		if($_FILES['img'])
		{
			$_FILES['Filedata'] = $_FILES['img'];
			unset($_FILES['img']);
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$img = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'imgwidth'  => $img_info['imgwidth'],
					'imgheight' => $img_info['imgheight'],
				);
				$data['img'] = @serialize($img);
			}
		}
		/***********************************电视剧的索引图************************************/

		$vid = $this->mode->create($data);
		if($vid)
		{
			$data['id'] = $vid;
			$this->addLogs('创建电视剧','',$data,$data['title']);
			$this->addItem($data);
			$this->output();
		}
	}
	
	public function update()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$this->input['title'])
		{
			$this->errorOutput(NO_TITLE);
		}
		
		$new_column_ids = $this->input['column_id'];
		$sql = "SELECT * FROM " . DB_PREFIX ."tv_play WHERE id = '" . $this->input['id'] . "'";
		$q = $this->db->query_first($sql);
		if($q)
		{
			$ori_column_id = array();
			$q['column_id'] = unserialize($q['column_id']);
			if(is_array($q['column_id']))
			{
				$ori_column_id = array_keys($q['column_id']);
			}
		}
		else 
		{
			$this->errorOutput(NO_DATA);
		}

		/*********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($this->input['play_sort_id'])
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'play_sort WHERE id IN('.$this->input['play_sort_id'].')';
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
			//$this->check_weight_prms(intval($this->input['weight']), $q['weight']); 
		}
		/*********************************权限控制********************************************/
		
		/***********************************获取发布的栏目*************************************/
		$column_id = $this->input['column_id'];
		$new_column_id = explode(',',$column_id);
		$column_id = $this->publish_column->get_columnname_by_ids('id,name',$column_id);
		if($column_id && is_array($column_id))
		{
			$column_id = serialize($column_id);		
		}
		else 
		{
			$column_id = '';
		}
		/***********************************获取发布的栏目*************************************/
		
		/***********************************构建数据*****************************************/
		//获取标题的首字母
		$initial_arr = $this->initial->Pinyin($this->input['title']);
		$initial = $initial_arr[2][0];
		if(!in_array($initial,$this->mode->get_initial()))
		{
			$initial = '';
		}
		$update_data = array(
			'title' 			=> trim($this->input['title']),
			'initial' 			=> $initial,
			'brief' 			=> ($this->input['brief'] && $this->input['brief'] != '这里输入描述')?$this->input['brief']:'',
			'director' 			=> $this->input['director'],
			'main_performer' 	=> $this->input['main_performer'],
			'type' 				=> $this->input['type'],
			'play_sort_id' 		=> $this->input['play_sort_id'],
			'district' 			=> $this->input['district'],
			'lang' 				=> $this->input['lang'],
			'year' 				=> $this->input['year'],
			'playcount' 		=> $this->input['playcount'],
			'duration' 			=> $this->input['duration'],
			'awards' 			=> $this->input['awards'],
			'copyright_limit' 	=> strtotime($this->input['copyright_limit'])?strtotime($this->input['copyright_limit']):0,
			'play_grade' 		=> $this->input['play_grade'],
			'publisher' 		=> $this->input['publisher'],
			'update_time'		=> TIMENOW,
			'column_id'			=> $column_id,
			'pub_time'			=> strtotime($this->input['publish_time']),
			'weight' 			=> intval($this->input['weight']),
			'publish_auto'		=> intval($this->input['publish_auto']),
			'publish_num_day'	=> intval($this->input['publish_num_day']),
		);
		/***********************************构建数据*****************************************/
		
		/***********************************电视剧的索引图************************************/
		if($_FILES['img'])
		{
			$_FILES['Filedata'] = $_FILES['img'];
			unset($_FILES['img']);
			$material_pic = new material();
			$img_info = $material_pic->addMaterial($_FILES);
			if($img_info)
			{
				$img = array(
					'host' 		=> $img_info['host'],
					'dir' 		=> $img_info['dir'],
					'filepath' 	=> $img_info['filepath'],
					'filename' 	=> $img_info['filename'],
					'imgwidth'  => $img_info['imgwidth'],
					'imgheight' => $img_info['imgheight'],
				);
				$update_data['img'] = @serialize($img);
			}
		}
		/***********************************电视剧的索引图************************************/
		
		/***********************************执行更新****************************************/
		$ret = $this->mode->update($this->input['id'],$update_data);//$ret是更新之前的数据
		/***********************************执行更新****************************************/
		if($ret)
		{
			$ori_column_id = array_keys(unserialize($ret['column_id']));
			$tv_play_info = $this->mode->get_tv_play_info($this->input['id']);//更新之后的电视剧信息
			$tv_play_info = $tv_play_info[0];
			/***********************************根据状态执行发布操作****************************************/
			if(intval($tv_play_info['status']) == 2)
			{
				if(!empty($tv_play_info['expand_id'])) //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						publish_insert_query($tv_play_info, 'delete',$del_column);
						$this->insertQueueToLivmediaByPlayID($tv_play_info['id'], 'delete',$del_column,$column_id);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						publish_insert_query($tv_play_info, 'insert',$add_column);
						$this->insertQueueToLivmediaByPlayID($tv_play_info['id'], 'insert',$add_column,$column_id,$tv_play_info['pub_time']);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						publish_insert_query($tv_play_info, 'update',$same_column);
						//publish_insert_query($tv_play_info, 'insert',$same_column,1);
						$this->insertQueueToLivmediaByVideoID($tv_play_info, 'insert',$same_column,$column_id,$tv_play_info['pub_time']);
					}
				}
				else     //未发布，直接插入
				{
					$op = "insert";
					publish_insert_query($tv_play_info,$op);
					$this->insertQueueToLivmediaByPlayID($tv_play_info['id'],$op,$new_column_id,$column_id,$tv_play_info['pub_time']);
				}
			}
			else 
			{
				if(!empty($ret['expand_id']))
				{
					$op = "delete";
					publish_insert_query($tv_play_info,$op);
					$this->insertQueueToLivmediaByPlayID($tv_play_info['id'],$op,$ori_column_id,$column_id);
				}
			}
			/***********************************根据状态执行发布操作****************************************/
			$this->addLogs('更新电视剧',$ret,'','更新电视剧' . $this->input['id']);
			$update_data['id'] = $this->input['id'];
			$this->addItem($update_data);
			$this->output();
		}
	}
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		/*********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$tv_play_info = $this->mode->get_tv_play_info($this->input['id']);
			if($tv_play_info)
			{
				foreach ($tv_play_info AS $k => $v)
				{
					$column_id = @unserialize($v['column_id']);
					if($column_id && is_array($column_id))
					{
						$published_column_id = implode(',',array_keys($column_id));
					}
					else 
					{
						$published_column_id = '';
					}
					
					$prms_arr[$v['id']] = array(
							'id' 					=>  $v['id'],
							'user_id' 				=>  $v['user_id'],
							'published_column_id' 	=>  $published_column_id,
							'play_sort_id' 			=>  $v['play_sort_id'],
							'org_id' 				=>  $v['org_id'],
					);
					$play_sorts_arr[] = $v['play_sort_id'];
				}
			}
		
			if($play_sorts_arr)
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'play_sort WHERE id IN('.implode(',',$play_sorts_arr).')';
				$query = $this->db->query($sql);
				$sort_ids_array = array();
				while($row = $this->db->fetch_array($query))
				{
					$sort_ids_array[$row['id']] = $row['parents'];
				}
			}
			
			if($prms_arr)
			{
				foreach ($prms_arr as $key => $value)
				{
					if(intval($value['play_sort_id']))
					{
						$value['nodes'][$value['play_sort_id']] = $sort_ids_array[$value['play_sort_id']];
					}
					$this->verify_content_prms($value);
				}
			}
		}
		/*********************************权限控制********************************************/
		
		$ret = $this->mode->delete($this->input['id']);//$ret是删除之前的数据
		if($ret)
		{
			if($ret['video_ids'])
			{
				$this->deleteVideosOfLivmedia(implode(',',$ret['video_ids']));
			}
			/***********************************根据状态执行发布操作****************************************/
			$recycle = array();//记录回收站的数据
			foreach ($ret['pre_data'] AS $k => $v)
			{
				$column_id = @unserialize($v['column_id']);
				if($column_id && is_array($column_id))
				{
					$published_column_id = implode(',',array_keys($column_id));
				}
				else 
				{
					$published_column_id = '';
				}
				
				if(intval($v['status']) == 2 && ($v['expand_id'] || $column_id))
				{
					$op = "delete";
					publish_insert_query($v,$op);
				}
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['title'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('vodinfo' => $v),
				);
			}
			
		 	if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			
			/***********************************根据状态执行发布操作****************************************/
			$this->addLogs('删除电视剧',$ret['pre_data'],'','删除电视剧' . $this->input['id']);
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

		/*********************************权限控制********************************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查询出审核之前电视剧的信息
			$tv_play_info = $this->mode->get_tv_play_info($this->input['id']);
			if($tv_play_info)
			{
				foreach ($tv_play_info AS $k => $v)
				{
					$column_id = @unserialize($v['column_id']);
					if($column_id && is_array($column_id))
					{
						$published_column_id = implode(',',array_keys($column_id));
					}
					else 
					{
						$published_column_id = '';
					}
					
			    	//存储权限信息
			    	$play_sorts_arr[] = $v['play_sort_id'];
					$prms_arr[$v['id']] = array(
							'play_sort_id'		  => $v['play_sort_id'],
					);
				}
			}

			if($play_sorts_arr)
			{
				$sql = 'SELECT id,parents FROM '.DB_PREFIX.'play_sort WHERE id IN('.implode(',',$play_sorts_arr).')';
				$query = $this->db->query($sql);
				$sort_ids_array = array();
				while($row = $this->db->fetch_array($query))
				{
					$sort_ids_array[$row['id']] = $row['parents'];
				}
			}
			
			if($prms_arr)
			{
				foreach ($prms_arr as $key => $value)
				{
					if(intval($value['play_sort_id']))
					{
						$value['nodes'][$value['play_sort_id']] = $sort_ids_array[$value['play_sort_id']];
					}
					$this->verify_content_prms($value);
				}
			}
		}
		/*********************************权限控制********************************************/
		$ret = $this->mode->audit($this->input['id'],$this->input['op']);
		if($ret)
		{
			/***********************************根据状态执行发布操作****************************************/
			//查询出更新之后电视剧的信息
			$tv_play_update_info = $this->mode->get_tv_play_info($this->input['id']);
			if($tv_play_update_info)
			{
				foreach ($tv_play_update_info AS $k => $v)
				{
					//审核
					if(intval($v['status']) == 2)
					{
						if(!empty($v['expand_id']))
						{
							$op = "update";	
                            publish_insert_query($v, $op);
						}
						else
						{
							if(@unserialize($v['column_id']))
							{
								$op = "insert";
                                publish_insert_query($v, $op);
								$this->publishVideoInLivmedia($v['id'],2,$v['column_id'],false,$v['pub_time']);
							}
						}
					}
					else if(intval($v['status']) == 3)//打回
					{
						$v['column_id'] = @unserialize($v['column_id']);
						if(!empty($v['expand_id']) || $v['column_id'])
						{
							$op= "delete";
                            publish_insert_query($v, $op);
							$this->publishVideoInLivmedia($v['id'],3,serialize($v['column_id']));
						}	
						else 
						{
							$op = "";
						}
					}
					if($op == 'insert')
					{
						publish_insert_query($v,'update');
					}
				}
			}
			/***********************************根据状态执行发布操作****************************************/
			$this->addLogs('审核电视剧','',$ret,'审核电视剧' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	//快速发布
	public function publish()
	{
		$id = urldecode($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$pub_time = $this->input['pub_time'] ? strtotime($this->input['pub_time']) : TIMENOW;
		$column_id = $this->input['column_id'];
		$isbatch = strpos($id, ',');
		if($isbatch !== false && !$column_id)
		{
			$this->addItem(true);
			$this->output();
		}
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->publish_column = new publishconfig();		
		$column_id = $this->publish_column->get_columnname_by_ids('id,name,parents',$column_id);
		$sql = "SELECT * FROM " . DB_PREFIX ."tv_play WHERE id IN( " . $id . ")";
		$q = $this->db->query($sql);
		while($row = $this->db->fetch_array($q))
		{
			$_column_id = $row['column_id'];
			$row['column_id'] = unserialize($row['column_id']);		
			$ori_column_id = array();
			if(is_array($row['column_id']))
			{
				$ori_column_id = array_keys($row['column_id']);
			}
			$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
			$row['column_id'] = $column_id;
			if($isbatch !== false)     //批量发布只能新增，so需要合并已经发布的栏目
			{
				$row['column_id'] = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
			}
			$new_column_id = array_keys($row['column_id']);	
			/***************************权限控制***************************************/
            $this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$ori_column_id_str));
            /***************************权限控制***************************************/ 
			$sql = "UPDATE " . DB_PREFIX ."tv_play SET column_id = '". addslashes(serialize($row['column_id'] )) ."',pub_time = ".$pub_time." WHERE id = " . $row['id'];
			$this->db->query($sql);
			if(intval($row['status']) == 2)
			{
				if(!empty($row['expand_id']))   //已经发布过，对比修改先后栏目
				{	
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						publish_insert_query($row, 'delete',$del_column);
						$this->insertQueueToLivmediaByPlayID($row['id'], 'delete',$del_column,serialize($column_id));
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						publish_insert_query($row, 'insert',$add_column);
						$this->insertQueueToLivmediaByPlayID($row['id'],'insert',$add_column,serialize($column_id),$pub_time);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						publish_insert_query($row, 'update',$same_column);
						//publish_insert_query($row, 'insert',$same_column,1);
						$this->insertQueueToLivmediaByVideoID($row,'insert',$same_column,serialize($column_id),$pub_time);
					}
				}
				else
				{
					if ($new_column_id) 
					{
						$op = "insert";
						publish_insert_query($row,$op);
						$this->insertQueueToLivmediaByPlayID($row['id'],$op,$new_column_id,serialize($column_id),$pub_time);
					}
				}
			}
			else
			{
				if(!empty($row['expand_id']))
				{
					$op = "delete";
					publish_insert_query($row,$op);
					$this->insertQueueToLivmediaByPlayID($row['id'],$op,$ori_column_id,serialize($column_id));
				}
			}
		}
		$this->addItem('true');
		$this->output();
	}
	
	//发布视频库里面的视频
	private function publishVideoInLivmedia($tv_play_id,$status,$column_id,$is_column_str = false,$publish_time=TIMENOW)
	{
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$sql = "SELECT video_id FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$tv_play_id. "'";
		$q = $this->db->query($sql);
		$video_id_arr = array();
		while ($r = $this->db->fetch_array($q))
		{
			$video_id_arr[] = $r['video_id'];
		}

		if(!$is_column_str && $column_id)
		{
			$column_id = implode(',',array_keys(unserialize($column_id)));
		}
		
		if($video_id_arr)
		{
			$livmedia = new livmedia();
			$livmedia->publish(implode(',',$video_id_arr),$status,$column_id,APP_UNIQUEID,$publish_time);
		}
	}
	
	//根据电视剧id操作视频库里面的视频id的队列($column_id是数组,$now_column是现在的栏目)
	public function insertQueueToLivmediaByPlayID($tv_play_id,$op,$column_id,$now_column,$pub_time)
	{
		$sql = "SELECT video_id FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$tv_play_id. "'";
		$q = $this->db->query($sql);
		$video_id_arr = array();
		while ($r = $this->db->fetch_array($q))
		{
			$video_id_arr[] = $r['video_id'];
		}
		
		if($video_id_arr)
		{
			include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
			$livmedia = new livmedia();
			$livmedia->insertQueueToLivmedia(implode(',',$video_id_arr),$op,implode(',',$column_id),$now_column,$pub_time);
		}
	}
	
	public function insertQueueToLivmediaByVideoID($tv_play,$op,$column_id,$now_column,$pub_time,$video_id='')
	{
		$sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE tv_play_id = '" .$tv_play['id']. "'";
		if(!$video_id)
		{
			$sql .= " AND expand_id = 0";
		}
		else 
		{
			$sql .= " AND video_id = {$video_id}";
		}
		$q = $this->db->query($sql);
		
		$video_id_arr = array();
		$ep_info = array();
		while ($r = $this->db->fetch_array($q))
		{
			$ep_info[] = $r;
			$video_id_arr[] = $r['video_id'];
		}
		
		if(!empty($video_id_arr))
		{
			$video_ids = implode(',',$video_id_arr);
		}
		
		if($video_ids)
		{
			//发布剧集
			if(!empty($ep_info))
			{
				foreach ($ep_info as $k => $v)
				{
					publish_insert_query($v, 'insert',$column_id,1);
				}
			}
			
			//触发视频发布
			include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
			$livmedia = new livmedia();
			$livmedia->insertQueueToLivmedia($video_ids,$op,implode(',',$column_id),$now_column,$pub_time);
		}
		
	}

	//发布剧集
	public function tv_episode_publish()
	{
		$tv_play_id = $tv_episode['tv_play_id'];
		
		$tv_play_id = intval($this->input['tv_play_id']);
		if(!$tv_play_id)
		{
			$data = array('error'=>1,'msg'=> '电视剧id不存在');
			$this->addItem($data);
			$this->output();
		}
		
		
		$video_id = intval($this->input['video_id']);
		if (!$video_id)
		{
			$data = array('error'=>1,'msg'=> '视频id不存在');
			$this->addItem($data);
			$this->output();
		}
		
		//查询电视剧的信息
		$tv_play_info = $this->mode->get_tv_play_info($tv_play_id);
		$tv_play_info = $tv_play_info[0];
		
		
		if(empty($tv_play_info))
		{
			$data = array('error'=>1,'msg'=> '电视剧信息不存在');
			$this->addItem($data);
			$this->output();
		}
		
		$column_id_arr = array();
		if($tv_play_info['column_id'])
		{
			$column_id_arr = array_keys(unserialize($tv_play_info['column_id']));
		}
		if (empty($column_id_arr))
		{
			$data = array('error'=>1,'msg'=> '电视剧未选择栏目');
			$this->addItem($data);
			$this->output();
		}
		//审核
		if(intval($tv_play_info['status']) == 2)
		{
			if(!empty($tv_play_info['expand_id']))
			{
				$op = "update";	
                        
				$res = publish_insert_query($tv_play_info, $op, $column_id_arr);
				
				$res = $this->insertQueueToLivmediaByVideoID($tv_play_info, 'insert',$column_id_arr,$tv_play_info['column_id'],$tv_play_info['pub_time'],$video_id);
			}
		}
		else 
		{
			$data = array('error'=>1,'msg'=> '电视剧未审核');
			$this->addItem($data);
			$this->output();
		}
		
		$data = array('error'=>0,'msg'=> '签发成功');
		$this->addItem($data);
		
		$this->output();
	}
	/******************************************基本操作***********************************************/
	
	
	/******************************************扩展操作***********************************************/
	//绑定上传的剧集
	public function uploadTvEpisode()
	{
		if(!$this->input['tv_play_id'] || !$this->input['video_id'])
		{
			$this->errorOutput(NOID);
		}
		
		//提取视频相关信息，并且保存
		$data = array(
			'tv_play_id' 	=> $this->input['tv_play_id'],
			'video_id' 		=> $this->input['video_id'],
			'img'			=> serialize($this->input['img_info']),
			'user_name'		=> $this->user['user_name'],
			'user_id'		=> $this->user['user_id'],
			'org_id'		=> $this->user['org_id'],
			'create_time'	=> TIMENOW,
			'update_time'	=> TIMENOW,
			'ip'			=> hg_getip(),
		);
		
		$ret = $this->mode->createEpisode($data);
		if($ret)
		{
			$data['id'] = $ret['id'];
			$this->addLogs('新增剧集', '', $data,'新增剧集:' . $ret['id']);
			//返回的数据
			$data['img_index'] = hg_fetchimgurl($this->input['img_info']);
			$data['title'] = $ret['title'];
			$data['index_num'] = $ret['index_num'];
			$this->addItem($data);
			$this->output();
		}
	}
	
	//删除剧集
	public function deleteEpisode()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->deleteEpisode($this->input['id']);
		if($ret)
		{
			//删除视频库里面的视频
			$recycle = array();//记录回收站的数据
			foreach($ret AS $k => $v)
			{
				$video_ids[] = $v['video_id'];
				//记录回收站的数据
				$recycle[$v['id']] = array(
					'title' 		=> $v['title'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $v['id'],
					'content'		=> array('vodinfo' => $v),
				);
				
				if($v['expand_id'])
				{
					publish_insert_query($v, 'delete','',1,1);
					//查询出该剧集所属电视剧的信息
					$sql = "SELECT tv.* FROM " .DB_PREFIX. "tv_play tv LEFT JOIN " .DB_PREFIX. "tv_episode te ON te.tv_play_id = tv.id WHERE te.id = '" .$v['id']. "'"; 
					$tv_play = $this->db->query_first($sql);
					if($tv_play)
					{
						$column_id = unserialize($tv_play['column_id']);
						$column_id = array_keys($column_id);
						publish_insert_query($tv_play, 'update',$column_id);
					}
				}
			}
			
			if($recycle)
			{
				foreach($recycle as $key => $value)
				{
					$this->recycle->add_recycle($value['title'],$value['delete_people'],$value['cid'],$value['content']);
				}
			}
			
			$this->addLogs('删除电视剧剧集',$ret,'','删除电视剧剧集' . $this->input['id']);
			//删除视频库里面对应的视频
			$this->deleteVideosOfLivmedia(implode(',',$video_ids));
			$this->addItem('success');
			$this->output();
		}
	}
	
	//删除视频库里面对应的视频
	private function deleteVideosOfLivmedia($video_ids = '')
	{
		include_once(ROOT_PATH . 'lib/class/livmedia.class.php');
		$livmedia = new livmedia();
		$livmedia->delete($video_ids);
	}
	
	//接收视频库里面发布的时候传过来的发布的链接
	public function receiveUrlFromLivmedia()
	{
		$video_id = $this->input['id'];
		$url = $this->input['url'];
		
		if(!$video_id)
		{
			$this->errorOutput(NOID);
		}
		
		if(!$url)
		{
			$this->errorOutput(NO_URL);
		}
		
		if($this->mode->updateEpisodeUrl($video_id,$url))
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	//更新电视剧海报
	public function updateTvImage()
	{
		if(!$this->input['tv_play_id'])
		{
			$this->errorOutput(NOID);
		}
		
		if(!$_FILES['img'])
		{
			$this->errorOutput('没有文件');
		}
		
		$_FILES['Filedata'] = $_FILES['img'];
		unset($_FILES['img']);
		$material_pic = new material();
		$img_info = $material_pic->addMaterial($_FILES);
		if($img_info)
		{
			$img = array(
				'host' 		=> $img_info['host'],
				'dir' 		=> $img_info['dir'],
				'filepath' 	=> $img_info['filepath'],
				'filename' 	=> $img_info['filename'],
				'imgwidth'  => $img_info['imgwidth'],
				'imgheight' => $img_info['imgheight'],
			);
			$ret = $this->mode->updateTvImage($this->input['tv_play_id'],$img);
			if ($ret)
			{
				$this->addItem('success');
				$this->output();
			}
		}
	}
	
	//更新剧集里面的url
	public function updateEpisodeUrl()
	{
		if(!$this->input['video_id'])
		{
			$this->errorOutput(NOID);	
		}
		
		$sql = "UPDATE " .DB_PREFIX. "tv_episode SET url = '" .$this->input['url']. "' WHERE video_id = '" .$this->input['video_id']. "' "; 
		$this->db->query($sql);
		
        $sql = "SELECT * FROM " .DB_PREFIX. "tv_episode WHERE video_id = '" .$this->input['video_id']. "'";
		$episode = $this->db->query_first($sql);
		if($episode)
		{
			publish_insert_query($episode, 'update','',1,1);
		}
                
		//查询出该剧集所属电视剧的信息
		$sql = "SELECT tv.* FROM " .DB_PREFIX. "tv_play tv LEFT JOIN " .DB_PREFIX. "tv_episode te ON te.tv_play_id = tv.id WHERE te.video_id = '" .$this->input['video_id']. "'"; 
		$tv_play = $this->db->query_first($sql);
		if($tv_play)
		{
			$column_id = unserialize($tv_play['column_id']);
			$column_id = array_keys($column_id);
			publish_insert_query($tv_play, 'update',$column_id);
		}
		$this->addItem('success');
		$this->output();
	}

	
	//更新剧集expand_id
	public function update_episode_expand_id()
	{
		$vids = $this->input['vids'];
		
		if(!$vids)
		{
			return false;
		}
		$sql = "UPDATE " . DB_PREFIX . "tv_episode SET expand_id = '' WHERE video_id IN (" . $vids . ")";
		$this->db->query($sql);
		
		$this->addItem('success');
		$this->output();
	}
	/******************************************扩展操作***********************************************/
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new tv_play_update();
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