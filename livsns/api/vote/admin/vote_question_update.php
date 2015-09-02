<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function create|update|delete|delQuestionOption|unknow
* 
* $Id: vote_question_update.php 6430 2012-04-17 03:36:32Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','vote');//模块标识
class voteUpdateApi extends adminUpdateBase
{
	private $mVote;
	private $mPublishColumn;
	private $verifycode;
	public function __construct()
	{
		parent::__construct();
		require_once CUR_CONF_PATH . 'lib/vote.class.php';
		$this->mVote = new vote();
		
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$this->mPublishColumn = new publishconfig();
		
		include_once(ROOT_PATH . 'lib/class/verifycode.class.php');
		$this->verifycode = new verifyCode();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	public function create()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		$title 				= trim($this->input['title']);    //投票标题
		$describes 			= trim($this->input['describes']);
		$more_info 			= trim($this->input['more_info']);
		$start_time			= strtotime($this->input['start_time']);
		$end_time			= strtotime($this->input['end_time']);
		$column_id			= $this->input['column_id'];
		$option_title 		= $this->input['option_title'];		//投票选项的标题
		$option_describes 	= $this->input['option_describes'];     //选项描述
		$option_ini_num 	= $this->input['ini_num'];				//初始化投票数 数组
		$option_num 		= count(@array_filter($option_title)); 		
		$vod_id				= trim($this->input['vod_id']);
		$option_vod_id		= $this->input['option_vod_ids'];
		$publishcontent_ids = $this->input['quote_ids'];	//发布库数据id
	    $picture_ids        = trim($this->input['picture_ids']) ? trim($this->input['picture_ids']) : '';
	    $option_picture_ids = $this->input['option_picture_ids'] ? $this->input['option_picture_ids'] : '';
	    $order 				= $this->input['order'];
		
		if (!$title)
		{
			$this->errorOutput('标题不能为空');
		}

		if (empty($option_title))
		{
			$this->errorOutput('投票选项不能为空');
		}
		
		if($option_num < 2)
		{
			$this->errorOutput('投票不能少于两个选项');
		}
		
		if ($this->input['option_type'] == 2) //多选题
		{
			if($this->input['max_option'] < $this->input['min_option'])
			{
				$this->errorOutput('最多选项不能少于最少选项');
			}
			if(!$this->input['max_option'] || $this->input['max_option'] > $option_num || $this->input['max_option'] < 2)
			{
				$max_option = $option_num;
			}
			else 
			{
				$max_option = intval($this->input['max_option']);
			}
			if(intval($this->input['min_option'])<= 0 || $this->input['min_option'] > $option_num)
			{
				$min_option = 1;
			}
			else 
			{
				$min_option = intval($this->input['min_option']);
			}
		}
		else //单选题
		{
			$max_option = 1;
			$min_option = 1;
		}
		
		if ($start_time && $end_time && ($end_time <= $start_time))
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verify_code']))
		{
			$this->errorOutput('验证码应用尚未安装');
		}
		
		//计算初始化总数
		$ini_total = 0;
		$min_ini = $max_ini = 0;
		$ini_person = 0;
		if(is_array($option_ini_num) && count($option_ini_num) > 0)
		{
			foreach($option_ini_num AS $v)
			{
				$ini_total += $v;
				$max_ini = ($v > $max_ini) ? $v : $max_ini;
			}
			if(intval($this->input['option_type']) == 2 )
			{
				//如果是多选，根据最多项和最少项取投票人数范围
				$max_person = floor($ini_total/$min_option) ; //最多投票人数
				$min_person = ceil($ini_total/$max_option) < $max_ini ? $max_ini : ceil($ini_total/$max_option); //最少投票人数
				$ini_person = mt_rand($min_person,$max_person);//根据投票人数范围，取一个随机数作为初始投票人数
			}
			else {//如果是单选，初始化人数就是初始化票数总数
				$ini_person = $ini_total;
			}		
		}
		
		$data = array(
			'title' 			=> $title,
			'describes'		 	=> $describes,
		    'keywords'          => trim($this->input['keywords']),
		    'pictures'		 	=> $picture_ids,
			'start_time' 		=> $start_time,
			'end_time' 			=> $end_time,
			'ip_limit_time' 	=> $this->input['is_ip'] ? (float)$this->input['ip_limit_time'] : 0,
			'ip_limit_num' 	    => $this->input['is_ip'] ? intval($this->input['ip_limit_num']) : 1,
			'is_ip' 			=> intval($this->input['is_ip']),
			'is_userid' 		=> intval($this->input['is_userid']),
		    'userid_limit_time' => $this->input['is_userid'] ? intval($this->input['userid_limit_time']) : 0,
			'userid_limit_num'  => $this->input['is_userid'] ? intval($this->input['userid_limit_num']) : 0,
			'is_verify_code' 	=> intval($this->input['is_verify_code']),
			'is_feedback'       => intval($this->input['is_feedback']),
			'feedback_id'     	=> $this->input['is_feedback'] ? $this->input['feedback_id'] : 0,	
			'is_open'           => 1, //创建时默认开启状态
			'verify_type' 	    => intval($this->input['is_verify_code']) && intval($this->input['verify_type']) != -1  ? intval($this->input['verify_type']) : 0,
			'is_msg_verify' 	=> intval($this->input['is_verify_code']) && intval($this->input['verify_type']) == -1 ? 1 : 0,
			'option_num' 		=> $option_num,
			'option_type' 		=> intval($this->input['option_type']) ? intval($this->input['option_type']) : 1,//默认单选
			'min_option' 		=> $min_option,
			'max_option' 		=> $max_option,
			'is_other' 			=> intval($this->input['is_other']),
			'status'            => $status ? $status : 0,
			'org_id' 			=> $this->user['org_id'],
			'user_id' 			=> $this->user['user_id'],
			'user_name' 		=> $this->user['user_name'],
			'appid' 			=> $this->user['appid'],
			'appname' 			=> $this->user['display_name'],
			'create_time' 		=> TIMENOW,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'more_info' 		=> $more_info ? @serialize($more_info) : '',
			'is_user_login' 	=> intval($this->input['is_userid']),
			'source_type' 		=> intval($this->input['source_type']),
			'node_id' 			=> intval($this->input['sort_id']),
			'weight' 			=> intval($this->input['weight']),
			'vod_id' 			=> $vod_id,
			'ini_total' 		=> $ini_total,
		    'ini_person'        => $ini_person ? $ini_person : 0,
		    'publishcontent_id' => trim($this->input['publishcontent_id']),
			'template_sign'		=> intval($this->input['template_sign']),
			'device_limit_time' => $this->input['is_device'] ? (float)$this->input['device_limit_time'] : 0,
			'device_limit_num' 	=> $this->input['is_device'] ? intval($this->input['device_limit_num']) : 1,
			'is_device' 		=> intval($this->input['is_device']),
			'iscomment' 		=> intval($this->input['iscomment']) ? 1 : 0,
			'is_praise' 		=> intval($this->input['is_praise']) ? 1 : 0,
			/*为了给https://redmine.hoge.cn/issues/3315提供支持，添加作者author和来源source字段*/
			'author'			=> trim($this->input['author']),
			'source'			=> trim($this->input['source']),
		);
		
		//发布开始
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		$ret = $this->mVote->create($data);
		$vote_id = $ret['id'];
		if (!$vote_id)
		{
			$this->errorOutput('投票添加失败');
		}
		$data['id'] = $ret['id'];
		//更新排序id
		$update_data = array(
			'id'			=> $vote_id,
			'order_id'		=> $vote_id,
		);
		//创建投票图片
		if ($_FILES['question_files']['tmp_name'])
		{
			$question_file = $this->mVote->add_material($_FILES['question_files'], $vote_id);
			$index_pic = array(
			    'host' =>$question_file['host'],
			    'dir'  =>$question_file['dir'],
			    'filepath' => $question_file['filepath'],
			    'filename' => $question_file['filename'],
			    'imgwidth' => $question_file['imgwidth'],
			    'imgheight'=> $question_file['imgheight'],
			);
			$update_data['pictures_info'] = $question_file ? @serialize($index_pic) : '';
		}
		$ret_vote = $this->mVote->update($update_data);
		$data['order_id'] 		= $vote_id;
		$data['pictures_info'] 	= $ret_vote['pictures_info'] ? $ret_vote['pictures_info'] : '';
		//添加投票选项
		$option_index_ids = $this->input['option_index'];
		if($option_index_ids)
		{
			$option_index_id = array_filter($option_index_ids);
			$op_index_ids = implode(',',$option_index_id);
		}
		$option_indexs = $this->mVote->get_image($op_index_ids,1);
		$option_info = array();
		foreach ($option_title AS $k => $v)
		{
			if ($v)
			{
				if(is_array($option_describes[$k]))   //选项描述是否为数组，如果是数组，则进行去空操作
				{
					$option_description[$k] = array();
				    foreach ($option_describes[$k] as $des)
				    {
				    	if(trim($des))
				    	{
				    		$option_description[$k][] = trim($des);  //数组去空操作
				    	}
				    }	
				}
				elseif(!is_array($option_describes[$k]) && trim($option_describes[$k]))
				{
					$option_description[$k][] = trim($option_describes[$k]);
				}
				//创建选项图片
				if ($_FILES['option_files_'.$k]['tmp_name'] || trim($option_index_ids[$k]) || $this->input['option_files'][$k])
				{
					if($_FILES['option_files_'.$k]['tmp_name'])
					{
						$option_file = $this->mVote->add_material($_FILES['option_files_'.$k], $option['id']);
					}
					elseif(trim($option_index_ids[$k]))
					{
						$option_file = $option_indexs[$option_index_ids[$k]]['pic_arr'];
					}
					elseif($this->input['option_files'][$k])
					{
						$option_file = stripslashes(htmlspecialchars_decode($this->input['option_files'][$k]));
						$option_file = json_decode($option_file,true);
					}
					$option_index_pic[$k] = array(
					    'host' =>$option_file['host'],
					    'dir'  =>$option_file['dir'],
					    'filepath' => $option_file['filepath'],
			            'filename' => $option_file['filename'],
			            'imgwidth' => $option_file['imgwidth'],
			            'imgheight'=> $option_file['imgheight'],
			       );
				}				
								
				$option_data = array(
					'vote_question_id'	=> $vote_id,
					'title'				=> trim($v),
					'describes'			=> count($option_description[$k]) ? serialize($option_description[$k]) : '',
				    'pictures'		 	=> trim($option_picture_ids[$k]),
					'state'				=> 1,
					'ini_num'			=> intval($option_ini_num[$k]),
					'user_id'			=> $this->user['user_id'],
					'user_name'			=> $this->user['user_name'],
					'publishcontent_id'	=> trim($publishcontent_ids[$k]),
					'create_time' 		=> TIMENOW,
					'update_time' 		=> TIMENOW,
			        'vod_ids'           => trim($option_vod_id[$k]),
				    'pictures_info'	    => $option_file ? @serialize($option_index_pic[$k]) : '',
					'order_id'	    => $order[$k] ? $order[$k] : 0,
				);
				
				$option = $this->mVote->option_create($option_data);
				$option_id = $option['id'];
				if (!$option['id'])
				{
					continue;
				}
				
				$option_info[] = $option;
			}
		}
		
		$data['option_info'] = $option_info;
		
		if ($data['id'] && $status == 1)
		{
			//放入发布队列
			if(!empty($column_id))
			{
				$op = 'insert';
				$this->publish_insert_query($data['id'], $op);
			}
		}
				
		$mat_id = '';
		if($picture_ids)
		{
			$mat_id .= $picture_ids .',';
		}
		if(is_array($option_picture_ids) && count($option_picture_ids)>0)
		{
			$option_picture = implode(',',$option_picture_ids);
		}
		if($option_picture)
		{
			$mat_id .= $option_picture.',';
		}
		$mat_id = trim($mat_id,',');
		if($mat_id)
		{
			$sql = 'UPDATE '.DB_PREFIX.'material SET vid = '.$vote_id.' WHERE id in('.$mat_id.')';
			$this->db->query($sql);
		}
		
		$vote = $this->mVote->detail($vote_id);
		$vote['column_id'] = $data['column_id'];
		//记录日志
		$this->addLogs('新增投票' , '' , $data , $data['title'], $data['id']);
		
		$this->addItem($vote);
		$this->output();
	}
	
	public function update()
	{
		file_put_contents(CACHE_DIR.'222.txt', var_export($this->input,1));
		$id					= intval($this->input['id']);
		$title 				= trim($this->input['title']);
		$describes 			= trim($this->input['describes']);
		$option_id 			= $this->input['option_id'];
		$option_title 		= $this->input['option_title'];
		$option_describes 	= $this->input['option_describes'];
		$option_ini_num 	= $this->input['ini_num'];
		$more_info 			= $this->input['more_info'];
		$option_num 		= count(@array_filter($option_title));
		$start_time			= strtotime($this->input['start_time']);
		$end_time			= strtotime($this->input['end_time']);
		$column_id			= $this->input['column_id'];
		$vod_id				= trim($this->input['vod_id']);
		$option_vod_id		= $this->input['option_vod_ids'];
		$expand_ids			= $this->input['expand_id'];
		$publishcontent_ids = $this->input['quote_ids'];
		
	    /****************新增数据***************/
	    $picture_ids        = trim($this->input['picture_ids']) ? trim($this->input['picture_ids']) : '';
	    $option_picture_ids = $this->input['option_picture_ids'] ? $this->input['option_picture_ids'] : '';
	    $order 				= $this->input['order'];
	    /****************新增数据***************/
	    	    
	    if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		if (!$title)
		{
			$this->errorOutput('标题不能为空');
		}

		if (empty($option_title))
		{
			$this->errorOutput('投票选项不能为空');
		}
		
		if(empty($option_id))
		{
			$this->errorOutput('投票选项ID不能为空');
		}
		
		if($option_num < 2)
		{
			$this->errorOutput('投票不能少于两个选项');
		}
				
		if ($this->input['option_type'] == 1)
		{
			$max_option = 1;
			$min_option = 1;
		}
		else 
		{
			if($this->input['max_option'] < $this->input['min_option'])
			{
				$this->errorOutput('最多选项不能少于最少选项');
			}
			if(!$this->input['max_option'] || $this->input['max_option'] > $option_num || $this->input['max_option'] < 2)
			{
				$max_option = $option_num;
			}
			else 
			{
				$max_option = intval($this->input['max_option']);
			}
			if(intval($this->input['min_option'])<= 0 || $this->input['min_option'] > $option_num)
			{
				$min_option = 1;
			}
			else 
			{
				$min_option = intval($this->input['min_option']);
			}
		}
		
		if ($start_time && $end_time && ($end_time <= $start_time))
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		if(!$this->settings['App_verifycode'] && intval($this->input['is_verify_code']))
		{
			$this->errorOutput('验证码应用尚未安装');
		}
		//取投票数据
		$vote = $this->mVote->get_vote_by_id($id);

		$vote = $vote[0];
		if (empty($vote))
		{
			$this->errorOutput('该投票不存在或已被删除');
		}
		$status = $vote['status'];
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($vote['node_id'])
			{
				$_node_ids = $vote['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= $id;
		$nodes['user_id'] 	= $vote['user_id'];
		$nodes['org_id'] 	= $vote['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
		//$nodes['weight'] = $vote['weight'];
		
		###获取默认数据状态
		if(!empty($vote['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $vote['status']);
		}
		else 
		{			
			if(intval($vote['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $vote['status']);
			}
		}
		
		$ori_column_id = array();
		if(is_array($vote['column_id']))
		{
			$ori_column_id = array_keys($vote['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		
		######获取默认数据状态
		$this->verify_content_prms($nodes);
		########权限#########
		
		//$this->check_weight_prms(intval($this->input['weight']), $nodes['weight']);
		
		//取投票选项数据
		$condition   = ' AND is_other = 0 ';
		$option_info = $this->mVote->get_vote_option($id, $condition);
		$option_info = $option_info[$id];			//得到投票选项数据
		
		$vote['option_info'] = $option_info;
		
		$_option_id = $edit_option_id = $delete_option_id = array();
		if (!empty($option_info))
		{
			$_min_ini = $_max_ini = 0;
			foreach ($option_info AS $v)
			{
				$_option_id[] = $v['id'];
				foreach ($option_id AS $vv)
				{
					if ($vv == $v['id'])
					{
						$edit_option_id[] = $v['id'];
					}
				}
			}
			
			//分析出要删除的投票选项id
			$delete_option_id = @array_diff($_option_id, $edit_option_id);
			$delete_option_info = array();
			if (!empty($delete_option_id))
			{
				foreach ($option_info AS $k => $v)
				{
					foreach ($delete_option_id AS $vv)
					{
						if ($vv == $v['id'])
						{
							$delete_option_info[$k] = $v;
						}
					}
				}
			}
		}
		
		//计算初始化总数
		$ini_total = 0;
		$min_ini = $max_ini = 0;
		$ini_person = $vote['ini_person'];
		if(is_array($option_ini_num) && count($option_ini_num) > 0)
		{
			foreach($option_ini_num AS $v)
			{
				$ini_total += $v;
				$max_ini = ($v > $max_ini) ? $v : $max_ini;
			}
			if($vote['ini_total'] != $ini_total || $min_option != $vote['min_option'] || $max_option != $vote['max_option'] )
			{
				if(intval($this->input['option_type']) == 2 )
				{
					//如果是多选，根据最多项和最少项取投票人数范围
					$max_person = floor($ini_total/$min_option); //最多投票人数
					$min_person = ceil($ini_total/$max_option) < $max_ini ? $max_ini : ceil($ini_total/$max_option); //最少投票人数
					$ini_person = mt_rand($min_person,$max_person);//根据投票人数范围，取一个随机数作为初始投票人数
				}
				else {//如果是单选，初始化人数就是初始化票数总数
					$ini_person = $ini_total;
				}
			}		
		}
		
		$data = array(
			'id'				=> $id,
			'title' 			=> $title,
		    'pictures'          => $picture_ids,
			'describes'		 	=> $describes == '这里输入描述' ? '' : $describes,
		    'keywords'          => trim($this->input['keywords']),
			'start_time' 		=> $start_time,
			'end_time' 			=> $end_time,
			'ip_limit_time' 	=> $this->input['is_ip'] ? (float)$this->input['ip_limit_time'] : 0,
			'ip_limit_num' 	    => $this->input['is_ip'] ? intval($this->input['ip_limit_num']) : 1,
			'is_ip' 			=> intval($this->input['is_ip']),
			'is_userid' 		=> intval($this->input['is_userid']),
		    'userid_limit_time' => $this->input['is_userid'] ? intval($this->input['userid_limit_time']) : 0,
			'userid_limit_num'  => $this->input['is_userid'] ? intval($this->input['userid_limit_num']) : 0,
			'is_verify_code' 	=> intval($this->input['is_verify_code']),
		    'is_feedback'       => intval($this->input['is_feedback']),
			'feedback_id'     	=> $this->input['is_feedback'] ? $this->input['feedback_id'] : 0,			
			'verify_type' 	    => intval($this->input['is_verify_code']) && intval($this->input['verify_type']) != -1 ? intval($this->input['verify_type']) : '',
			'is_msg_verify' 	=> intval($this->input['is_verify_code']) && intval($this->input['verify_type']) == -1 ? 1 : 0,
			'option_num' 		=> $option_num,
			'option_type' 		=> intval($this->input['option_type'])?intval($this->input['option_type']) : 1,//默认单选
			'min_option' 		=> $min_option,
			'max_option' 		=> $max_option,
			'is_other' 			=> intval($this->input['is_other']),
			'more_info' 		=> $more_info ? @serialize($more_info) : '',
			'is_user_login' 	=> intval($this->input['is_userid']),
			'source_type' 		=> intval($this->input['source_type']),
			'node_id' 			=> intval($this->input['sort_id']),
			'weight' 			=> intval($this->input['weight']),
			'vod_id' 			=> $vod_id,
			'ini_total' 		=> $ini_total,
		    'ini_person'        => $ini_person ? $ini_person : 0,
		    'publishcontent_id' => trim($this->input['publishcontent_id']),
			'template_sign'		=> intval($this->input['template_sign']),			
			'device_limit_time' => $this->input['is_device'] ? (float)$this->input['device_limit_time'] : 0,
			'device_limit_num' 	=> $this->input['is_device'] ? intval($this->input['device_limit_num']) : 1,
			'is_device' 		=> intval($this->input['is_device']),
			'iscomment' 		=> intval($this->input['iscomment']) ? 1 : 0,
			'is_praise' 		=> intval($this->input['is_praise']) ? 1 : 0,
			/*为了给https://redmine.hoge.cn/issues/3315提供支持，添加作者author和来源source字段*/
			'author'			=> trim($this->input['author']),
			'source'			=> trim($this->input['source']),
		);
		//发布开始
		//$vote['column_id'] = unserialize($vote['column_id']);
		$ori_column_id = array();
		if(is_array($vote['column_id']))
		{
			$ori_column_id = array_keys($vote['column_id']);
		}
				
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		//发布结束
		$ret_vote = $this->mVote->update($data);
		$vote_id = $ret_vote['id'];
		
		if (!$vote_id)
		{
			$this->errorOutput('投票更新失败');
		}
		
		//更新标记
		$affected_rows = $ret_vote['affected_rows'];
		//创建投票图片
		if ($_FILES['question_files'])
		{
			$question_file = $this->mVote->add_material($_FILES['question_files'], $vote_id);
			$index_pic = array(
			    'host' =>$question_file['host'],
			    'dir'  =>$question_file['dir'],
			    'filepath' => $question_file['filepath'],
			    'filename' => $question_file['filename'],
			    'imgwidth' => $question_file['imgwidth'],
			    'imgheight'=> $question_file['imgheight'],
			);
			$update_data = array(
				'id'			=> $vote_id,
				'pictures_info'	=> $question_file ? @serialize($index_pic) : '',
			);
			
			$ret_vote_pictures = $this->mVote->update($update_data);
			
			$data['pictures_info'] = $ret_vote_pictures['pictures_info'];
			
			$affected_rows = $ret_vote_pictures['affected_rows'];
		}
				//添加投票选项
		$option_index_ids = $this->input['option_index'];
		if($option_index_ids)
		{
			$option_index_id = array_filter($option_index_ids);
 			$op_index_ids = implode(',',$option_index_id);
		}
		$option_indexs = $this->mVote->get_image($op_index_ids,1);
		
		$_option_info = array();
		foreach ($option_title AS $k => $v)
		{
			if ($v)
			{
				if(is_array($option_describes[$k]))
				{
				    foreach ($option_describes[$k] as $des)
				    {
				        if(trim($des))
				    	{
				    		$option_description[$k][] = trim($des);  //数组去空操作
				    	}
				    }	
				}
				elseif(!is_array($option_describes[$k]) && trim($option_describes[$k]))
				{
					$option_description[$k][] = trim($option_describes[$k]);
				}
				$option_data = array(
					'vote_question_id'	=> $vote_id,
					'title'				=> trim($v),
					'describes'			=> count($option_description[$k]) ? serialize($option_description[$k]) : '',
				    'pictures'          => trim($option_picture_ids[$k]),
				    'state'				=> 1,
					'ini_num'			=> intval($option_ini_num[$k]),
					'publishcontent_id'	=> $publishcontent_ids[$k],
					'vod_ids'           => trim($option_vod_id[$k]),
					'order_id'	    	=> $order[$k] ? $order[$k] : 0,
				);
				
				if ($option_id[$k])	//update
				{
					$option_data['id'] = $option_id[$k];
					$option = $this->mVote->option_update($option_data);
					if ($option['affected_rows'])
					{
						$_option_data = array(
							'id'			=> $option_id[$k],
							'update_time'	=> TIMENOW,
						);
						$_option = $this->mVote->option_update($_option_data);
						$option['update_time'] = $_option['update_time'];
						
						$affected_rows = $option['affected_rows'];
					}
				}
				else 			//create
				{
					$option_data['user_id'] 	= $this->user['user_id'];
					$option_data['user_name'] 	= $this->user['user_name'];
					$option_data['create_time'] = TIMENOW;
					$option_data['update_time'] = TIMENOW;
					
					$option = $this->mVote->option_create($option_data);
					
					$affected_rows = $option['affected_rows'];
				}
				
				if (!$option['id'])
				{
					continue;
				}
				
				//创建选项图片
				if ($_FILES['option_files_'.$k]['tmp_name'] || $option_index_ids[$k] || $this->input['option_files'][$k])
				{
					if($_FILES['option_files_'.$k]['tmp_name'])
					{
						$option_file = $this->mVote->add_material($_FILES['option_files_'.$k], $option['id']);
					}
					elseif(trim($option_index_ids[$k]))
					{
						$option_file = $option_indexs[$option_index_ids[$k]]['pic_arr'];
					}
					elseif($this->input['option_files'][$k])
					{
						$option_file = stripslashes(htmlspecialchars_decode($this->input['option_files'][$k]));
						$option_file = json_decode($option_file,true);
					}
					$option_index_pic = array(
					'host' =>$option_file['host'],
					'dir'  =>$option_file['dir'],
					'filepath' => $option_file['filepath'],
					'filename' => $option_file['filename'],
					'imgwidth' => $option_file['imgwidth'],
					'imgheight'=> $option_file['imgheight'],
			       );
					$update_data = array(
						'id'			=> $option['id'],
						'pictures_info'	=> $option_file ? @serialize($option_index_pic) : '',
					);
					
					$ret_option = $this->mVote->option_update($update_data);
					
					$option['pictures_info'] = $ret_option['pictures_info'];
					
					$affected_rows = $ret_option['affected_rows'];
				}
				unset($option['affected_rows']);
				$_option_info[] = $option;
			}
		}
		
		$data['option_info'] = $_option_info;
		
		//发布开始
		if ($ret_vote['id'])
		{
			//更改文章后发布的栏目
			$ret_vote['column_id'] = unserialize($ret_vote['column_id']);
			$new_column_id = array();
			if(is_array($ret_vote['column_id']))
			{
				$new_column_id = array_keys($ret_vote['column_id']);
			}
			
			if ($status ==1)
			{
				if(!empty($vote['expand_id']))   //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						$this->publish_insert_query($data['id'], 'delete',$del_column);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						$this->publish_insert_query($data['id'], 'insert',$add_column);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						$this->publish_insert_query($data['id'], 'update',$same_column);
					}
				}
				else 							//未发布，直接插入
				{
					$op = "insert";
					$this->publish_insert_query($data['id'],$op,$new_column_id);
				}
			}
			else 
			{
				if(!empty($vote['expand_id']))
				{
					$op = "delete";
					$this->publish_insert_query($data['id'],$op,$ori_column_id);
				}
			}
			
		}
		//发布结束
		
		if ($affected_rows)
		{
			$user_data = array(
				'id'				=> $vote_id,
				'update_time'		=> TIMENOW,
				'update_org_id' 	=> $this->user['org_id'],
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name' 	=> $this->user['user_name'],
				'update_appid' 		=> $this->user['appid'],
				'update_appname' 	=> $this->user['display_name'],
				'update_ip' 		=> hg_getip(),
				'status' 			=> $status,
			);
			
			$ret_user = $this->mVote->update($user_data);
			
			if (!empty($ret_user))
			{
				unset($ret_user['id']);
				foreach ($ret_user AS $k => $v)
				{
					$data[$k] = $v;
				}
			}
			
			//记录日志
			if ($data['more_info'])
			{
				$data['more_info'] = unserialize($data['more_info']);
			}
			$pre_data = $vote;
			$up_data = $data;
			$this->addLogs('更新投票', $pre_data, $up_data, $data['title'], $data['id']);
		}
		
		//删除剔除的投票选项
		if (!empty($delete_option_id))
		{
			$delete_option_id = implode(',', $delete_option_id);
			$this->mVote->option_delete($delete_option_id);
			//记录日志
			$this->addLogs('删除投票选项', '', $delete_option_info, $data['title'], $data['id']);
		}
		
		$mat_id = '';
		if($picture_ids)
		{
			$mat_id .= $picture_ids .',';
		}
		$option_picture = @implode(',',$option_picture_ids);
		if($option_picture)
		{
			$mat_id .= $option_picture.',';
		}
		$mat_id = trim($mat_id,',');
		if($mat_id)
		{
			$sql = 'UPDATE '.DB_PREFIX.'material SET vid = '.$id.' WHERE id in('.$mat_id.')';
			$this->db->query($sql);
		}
		
		$update_vote = $this->mVote->detail($vote_id);
		$update_vote['column_id'] = $data['column_id'];
		$this->addItem($update_vote);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		//投票数据
		$vote = $this->mVote->get_vote_by_id($id);
		foreach ($vote as $v)
		{
			$pid .= trim($v['pictures']) ? trim($v['pictures']) . ',' : '';
		}
		//投票选项
		$option_info = $this->mVote->get_vote_option($id);
		foreach ($option_info as $v)
		{
			foreach ($v as $vv)
			{
				$opid .= trim($vv['pictures']) ? trim($vv['pictures']) . ',' : '';
			}
		}
		$mat = trim($pid.$opid,',');
		
		if (empty($vote))
		{
			$this->errorOutput('该投票不存在或已被删除');
		}
		
		#####整合数据进行权限
		$nodes = $node_id = array();
		foreach ($vote AS $k => $v)
		{
			//发布
			if($v['expand_id'] || $v['column_id'])
			{
				$op = "delete";
				$this->publish_insert_query($v['id'],$op);
			}
			
			$vote[$k]['option_info'] = $option_info[$v['id']];
			
			$node_id[] = $v['node_id'];
			$nodes[] = array(
				'title' 		=> $v['name'],
				'delete_people' => $this->user['user_name'],
				'cid' 			=> $v['id'],
				'catid' 		=> $v['node_id'],
				'user_id'		=> $v['user_id'],
				'org_id'		=> $v['org_id'],
				'id'			=> $v['id'],
			);
		}
		
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.implode(',',$node_id).')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		
		if(!empty($nodes))
		{
			foreach ($nodes AS $node)
			{
				if($node['catid'])
				{
					$node['nodes'][$node['catid']] = $node_ids[$node['catid']];
				}
				$this->verify_content_prms($node);
			}
		}
		#####整合数据进行权限结束
		
		//删除
		$return = $this->mVote->delete($id);
		$this->delete_image($mat);   //删除投票的图片素材
		
		if (!$return)
		{
			$this->errorOutput('删除失败');
		}
		
		//记录日志
		$this->addLogs('删除投票', '', $vote);
		
		$this->addItem($id);
		$this->output();
	}

	/**
	 * 编辑其他选项
	 * $other_title 选项标题
	 * $hiddenFlag 选项标记
	 * Enter description here ...
	 */
	public function updateOtherTitle()
	{
		$other_title 	= $this->input['other_title'];
		$hiddenFlag 	= $this->input['hiddenFlag'];
		
		if ($other_title)
		{
			foreach ($other_title AS $id => $title)
			{
				if ($hiddenFlag[$id] == 1)
				{
					$data = array(
						'id'		  => $id,
						'title' 	  => trim($title),
						'flag' 		  => 1,
						'update_time' => TIMENOW,
					);
					$this->mVote->option_update($data);
				}
			}
		}
		$this->addItem($other_title);
		$this->output();
	}
			
	/**
	 * 审核
	 * @name status
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 投票ID
	 * @return $tip int 是否允许开启其他选项信息 (1-允许 0-不允许)
	 */
	public function status()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$sql = "SELECT id, title, node_id, user_id, org_id, status FROM " . DB_PREFIX . "vote_question WHERE id=" . $id;
		$vote_info = $this->db->query_first($sql);
		
		#####整合数据进行权限
		$nodes = array(
			'title' 		=> $vote_info['title'],
			'delete_people' => $this->user['user_name'],
			'cid' 			=> $vote_info['id'],
			'catid' 		=> $vote_info['node_id'],
			'user_id'		=> $vote_info['user_id'],
			'org_id'		=> $vote_info['org_id'],
			'id'			=> $vote_info['id'],
		);
		$node_id = $vote_info['node_id'];
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$node_id.')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		if($nodes['catid'])
		{
			$nodes['nodes'][$nodes['catid']] = $node_ids[$nodes['catid']];
		}
		$nodes['_action'] = 'audit';
		$this->verify_content_prms($nodes);
		#####整合数据进行权限结束
		
		$status = $vote_info['status'];
		
		$tip = '';
		
		if ($status)
		{
			$sql = "UPDATE " . DB_PREFIX . "vote_question SET status=0 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 0;
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "vote_question SET status=1 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 1;
		}
		$this->addItem($tip);
		$this->output();
	}

	/**
	 * 获取其他选项
	 * @name getOtherOption
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vote_question_id int 投票ID
	 * @param $offset int 查询起始数
	 * @param $count int 查询长度
	 * @return $info array 用户填写选项信息
	 */
	public function getOtherOption()
	{
		$id = $this->input['vote_question_id'];
	
		$sql = "SELECT * FROM " . DB_PREFIX . "vote_question WHERE id=" . $id;		
		$row = $this->db->query_first($sql);
		$this->setXmlNode('vote_question' , 'info');
		
		if(is_array($row) && $row)
		{
			$row['create_time'] = date('Y-m-d H:i:s' , $row['create_time']);
			$row['update_time'] = date('Y-m-d H:i:s' , $row['update_time']);
			$row['question_img'] = hg_get_images($row['other_option_pictures'], UPLOAD_URL . QUESTION_IMG_DIR, $this->settings['question_img_size']);
			
			$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id IN(" . $id . ") ORDER BY id ASC";
			$q = $this->db->query($sql);
			$row['options'] = $row['other_options'] =  array();
			while ($r = $this->db->fetch_array($q))
			{
				if (!$r['is_other'])
				{
					$r['option_img'] = hg_get_images($r['other_option_pictures'], UPLOAD_URL . OPTION_IMG_DIR, $this->settings['option_img_size']);
					$row['options'][] = $r;
				}
				else 
				{
					$row['other_options'][] = $r;
				}
			}
			if ($row['options'])
			{
				$row['vote_total'] = "";
				foreach ($row['options'] AS $vv)
				{
					$row['vote_total'] = $vv['single_total'] + $row['vote_total'];
				}
			}
			if ($row['other_options'])
			{
				$row['other_vote_total'] = "";
				foreach ($row['other_options'] AS $vv)
				{
					$row['other_vote_total'] = $vv['single_total'] + $row['other_vote_total'];
				}
			}
			$row['other_option_num'] = count($row['other_options']);
			$row['question_total'] = $row['vote_total'] + $row['other_vote_total'];
			
			$this->addItem($row);
			$this->output();
		}
	}

	/**
	 * 其他选项审核
	 * @name optionOtherState
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $id int 选项ID
	 * @return $tip int (1-已审核 0-待审核)
	 */
	public function optionOtherState()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$sql = "SELECT state FROM " . DB_PREFIX . "question_option WHERE id=" . $id;
		$option_info = $this->db->query_first($sql);
		$state = $option_info['state'];
		
		$tip = '';
		
		if ($state)
		{
			$sql = "UPDATE " . DB_PREFIX . "question_option SET state=0 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 0;
		}
		else 
		{
			$sql = "UPDATE " . DB_PREFIX . "question_option SET state=1 WHERE id=" . $id;
			$this->db->query($sql);
			$tip = 1;
		}
		$this->addItem($tip);
		$this->output();
	}
	
	/**
	 * 获取投票其他更多选项
	 * @name getOtherMore
	 * @access public
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 * @param $vote_question_id int 投票ID
	 * @param $offset int 查询起始数
	 * @param $count int 查询长度
	 * @return $info array 用户填写选项信息
	 */
	public function getOtherMore()
	{
		$vote_question_id = $this->input['vote_question_id'];
		$offset = $this->input['offset'];
		$count = $this->settings['other_option_count'];
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE is_other=1 AND vote_question_id=" . $vote_question_id;
		$sql .= " ORDER BY id ASC ";
		$sql .= " LIMIT " . $offset . ", " . $count;
		$q = $this->db->query($sql);
		$info = array();
		while ($row = $this->db->fetch_array($q))
		{
			$info[] = $row;
		}
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 删除投票选项
	 * $id 投票选项id
	 * Enter description here ...
	 */
	public function delQuestionOption()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		$return = $this->mVote->option_delete($id);
		if (!$return)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		$id = trim($this->input['id']);
		//$status = intval($this->input['audit']);
		switch (intval($this->input['audit']))
		{
			case 0:$status =1;break;
			case 1:$status =2;break;
			case 2:$status =1;break;
		}
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		$this->verify_content_prms($nodes);
		
		$sql = "UPDATE " . DB_PREFIX . "vote_question SET status=".$status." WHERE id in(" . $id .")";
		$this->db->query($sql);
		
	    if($status == 1)
	    {
	    	$ret_sur = $this->mVote->get_vote_list(" id IN({$id})");
			if(is_array($ret_sur) && count($ret_sur) > 0 )
			{
				foreach($ret_sur as $info)
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
				}
			}
	    }
	    elseif($status == 2)
	    {
	    	$ret_sur = $this->mVote->get_vote_list(" id IN({$id})");
			if(is_array($ret_sur) && count($ret_sur) > 0 )
			{
				foreach($ret_sur as $info)
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
				}
			}
	    	
	    }
			//file_put_contents('333.txt',var_export($op,1));
	    
	    /**
	    $update_data = array(
		    'id'                 => $vote_info['id'],
		    'audit_user_id'      => $this->user['user_id'],
		    'audit_user_name'    => $this->user['user_name'],
		    'audit_time'         => TIMENOW,
		);
		$ret_audit = $this->mVote->update($update_data);
		**/
	    $sql = 'UPDATE '.DB_PREFIX.'vote_question SET audit_user_id ='.$this->user['user_id'].', audit_user_name ="'.$this->user['user_name'].'", audit_time = '.TIMENOW.' WHERE id in('.$id.')';
	    $this->db->query($sql);
	    
		$return = array(
		    'id'                =>$id ? explode(',',$id):$id,
		    'status'            =>$status,
		);
		$this->addItem($return);
		$this->output();
	}
	
	/**
	 * 投票开启/关闭
	 * Enter description here ...
	 */
	public function open()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$sql = "SELECT id, title, node_id, user_id, org_id,is_open, status, column_id, expand_id FROM " . DB_PREFIX . "vote_question WHERE id=" . $id;
		$vote_info = $this->db->query_first($sql);
		#####整合数据进行权限
		$nodes = array(
			'title' 		=> $vote_info['title'],
			'delete_people' => $this->user['user_name'],
			'cid' 			=> $vote_info['id'],
			'catid' 		=> $vote_info['node_id'],
			'user_id'		=> $vote_info['user_id'],
			'org_id'		=> $vote_info['org_id'],
			'id'			=> $vote_info['id'],
		);
		$node_id = $vote_info['node_id'];
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$node_id.')';
			$query = $this->db->query($sql);
			$node_ids = array();
			while($row = $this->db->fetch_array($query))
			{
				$node_ids[$row['id']] = $row['parents'];
			}
		}
		
		if($nodes['catid'])
		{
			$nodes['nodes'][$nodes['catid']] = $node_ids[$nodes['catid']];
		}
		$nodes['_action'] = 'audit';
		$this->verify_content_prms($nodes);
		#####整合数据进行权限结束

		$is_open = $vote_info['is_open'];  //提取当前开启状态
		
		switch ($is_open)
		{
			case 0:
                $is_open = 1;
                $sql = "UPDATE " . DB_PREFIX . "vote_question SET is_open= ". $is_open ." WHERE id=" . $id;
			    $this->db->query($sql);
                break;
		    case 1:
		    	$is_open = 0;
			    $sql = "UPDATE " . DB_PREFIX . "vote_question SET is_open= ". $is_open ." WHERE id=" . $id;
			    $this->db->query($sql);
			    break;
		}
		
		$this->addItem($is_open);
		$this->output();
	}
	
	public function sort()
	{
	//	$this->verify_content_prms();
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('vote_question', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish()
	{
		if(empty($this->input['id']))
		{
			$this->errorOutput('ID不能为空');
		}
		$id = intval($this->input['id']);
		
		$pub_time = $this->input['pub_time'] ? strtotime(trim($this->input['pub_time'])) : TIMENOW;
		$column_id = urldecode($this->input['column_id']);
		$new_column_id = explode(',',$column_id);
		
		$column_id = $this->mPublishColumn->get_columnname_by_ids('id,name,parents',$column_id);
		
		$column_id = $column_id ? serialize($column_id) : '';
		
		//查询修改之前已经发布到的栏目
		$sql = "SELECT * FROM " . DB_PREFIX ."vote_question WHERE id = " . $id;
		$q = $this->db->query_first($sql);
		$q['column_id'] = unserialize($q['column_id']);
		$ori_column_id = array();
		if(is_array($q['column_id']))
		{
			$ori_column_id = array_keys($q['column_id']);
		}
		$ori_column_id_str = $ori_column_id ? implode(',', $ori_column_id) : '';
		
		/***************************权限控制***************************************/
		$this->verify_content_prms(array('column_id' =>$this->input['column_id'],'published_column_id'=>$ori_column_id_str));
		/***************************权限控制***************************************/	
		
		$sql = "UPDATE " . DB_PREFIX ."vote_question SET column_id = '". $column_id ."',pub_time = ".$pub_time." WHERE id = " . $id;
		$this->db->query($sql);
		
		if(intval($q['status']) ==1)
		{
			if(!empty($q['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					$this->publish_insert_query($id, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					$this->publish_insert_query($id, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					$this->publish_insert_query($id, 'update',$same_column);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				$this->publish_insert_query($id,$op);
			}
		}
		else    //打回
		{
			if(!empty($q['expand_id']))
			{
				$op = "delete";
				$this->publish_insert_query($id,$op);
			}
		}

		$this->addItem('true');
		$this->output();
	}
	
	/**
	 * 放入发布队列
	 */
	public function publish_insert_query($id,$op,$column_id = array(),$child_queue = 0)
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
		$sql = "SELECT * FROM " . DB_PREFIX ."vote_question WHERE id = " . $id;
		$info = $this->db->query_first($sql);
		if(empty($column_id) || count($column_id) < 1)
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
		
		
 		require_once(ROOT_PATH . 'lib/class/publishplan.class.php');
		$plan = new publishplan();
		$data = array(
			'set_id' 	=> PUBLISH_SET_ID,
			'from_id'   => $info['id'],
			'class_id'	=> 0,
			'column_id' => $column_id,
			'title'     => $info['title'],
			'action_type' 	 => $op,
			'publish_time'   => $info['pub_time'],
			'publish_people' => trim($this->user['user_name']),
			'ip'  			 => hg_getip(),
		);
		$ret = $plan->insert_queue($data);
		return $ret;
	}

	/**
	 * 上传图片
	 */
	public function upload_image()
	{
		$picture['Filedata'] = $_FILES['Filedata'];
		file_put_contents(CACHE_DIR.'debug.txt', var_export($_FILES,1));
		
		if($picture['Filedata'])
		{
			$picture_pic = $this->mVote->add_material($picture['Filedata'], '');
			$img_info = addslashes(serialize($picture_pic));	
		}
		if(!$picture_pic) 
		{
			$this->errorOutput('没有上传的图片信息');
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET mid = '" . $picture_pic['mid'] ."',
		                                                   name = '" . trim($picture_pic['name']) ."', 
		                                                   pic = '" . $img_info ."', 
		                                                   host = '" . $picture_pic['host'] ."', 
		                                                   dir = '" . $picture_pic['dir'] ."', 
		                                                   filepath = '" . $picture_pic['filepath'] ."', 
		                                                   filename = '" . trim($picture_pic['filename']) ."', 
		                                                   type = '" . $picture_pic['type'] ."', 
		                                                   imgwidth = '" . $picture_pic['imgwidth'] ."', 
		                                                   imgheight = '" . $picture_pic['imgheight'] ."',
		                                                   filesize = '" . $picture_pic['filesize'] ."',
		                                                   create_time = '" . TIMENOW ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
        $data['id'] = $vid;
		$data['img_info'] = hg_fetchimgurl($picture_pic);
		$data['upload_type'] = '图片';
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 视频上传
	 */
	public function upload_video()
	{
		if(!$_FILES['videofile'])
		{
			$this->errorOutput('没有上传视频信息！');
		}
		
		if ($_FILES['videofile'])
		{
			$video = array();
			//检测视频服务器
			if (!$this->settings['App_mediaserver'])
			{
				$this->errorOutput('视频服务器未安装!');
			}
			if ($_FILES['videofile']['error']>0)
			{
				$this->errorOutput('视频上传错误！');
			}
			
			//获取视频服务器上传配置
			$videoConfig = $this->mVote->getVideoConfig();
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
			$videodata = $this->mVote->uploadToVideoServer($_FILES, $data['title'], $data['brief']);
			if (!$videodata)
			{
				$this->errorOutput('视频服务器错误!');
			}
			$data = $this->mVote->get_vod_info_by_id($videodata['id']);
		    $video_type = $_FILES['videofile']['type'];
			foreach ($data as $v)
            {
            	if(strstr($video_type, 'audio') && $v['is_audio']==0)
            	{
            		$v['is_audio'] = 1;
            		$v['upload_type'] = '音频';
		    	}
            	$this->addItem($v);
            }
			$this->output();
		}
		
	}
	/**
	 * 删除上传图片
	 * @param $id int 图片ID
	 */
	private function delete_image($id)
	{
		if(!$id)
		{
			return false;
		}
		$sql = "SELECT mid FROM " . DB_PREFIX . "material WHERE id in (" . $id  .")";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$mid[] = $r['mid'];
		}
		$mid = implode(',',$mid);
		
		$sql = "DELETE FROM " . DB_PREFIX . "material WHERE id in (" . $id .")";
		$data = $this->db->query_first($sql);
		if($mid)
		{
		    include_once ROOT_PATH . 'lib/class/material.class.php';
			$material_pic = new material();
			$material_pic->delMaterialById($mid);			
		}
		return $id;
	}
	

	/**
	 * 设置权重
	 * @name 		update_weight
	 */
	function check_weight_prms($input_weight =  0, $org_weight = 0)
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
		//检测
		if(empty($this->input['data']))
		{
			$this->errorOutput(NO_DATA);
		}
		$data = $this->input['data'];
		$data = htmlspecialchars_decode($data);
		$data = json_decode($data,1);
		$id = @array_keys($data);
		if(!$id)
		{
			$this->errorOutput(INVALID_ARTICLE);
		}
		$sql = 'SELECT id,weight FROM '.DB_PREFIX.'vote_question WHERE id IN('.implode(',', $id).')';
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$org_weight[$row['id']] = $row['weight'];
		}
		$sql = "CREATE TEMPORARY TABLE tmp (id int primary key, weight int)";
		$this->db->query($sql);
		$sql = "INSERT INTO tmp VALUES ";
		$space = '';

		foreach ($data as $k => $v)
		{
			$sql .= $space . "(" . $k . ", ". $v .")";
			$this->check_weight_prms($v, $org_weight[$k]);
			$space = ',';
		}
		$this->db->query($sql);
		$sql = "UPDATE " . DB_PREFIX . "vote_question a,tmp SET a.weight = tmp.weight WHERE a.id = tmp.id";
		$this->db->query($sql);
		$id = implode(',',$id);
		$this->addLogs('修改权重','','', '修改权重+' . $id);
		$this->addItem('true');
		$this->output();
	}
	
	public function generate()
	{
		require_once CUR_CONF_PATH . 'lib/template.class.php';
		$this->template = new template_mode();
    	$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$vote = $this->mVote->get_vote($id);
		if($vote['status'] == 0 || $vote['status'] == 2)
		{
			$this->errorOutput('对不起，该投票未通过审核');
		}
		if(!$vote['template_id'])
		{
			$this->errorOutput('对不起，尚未选择模板');
		}
		$vote['url'] = !defined('VOTE_DOMAIN') || !VOTE_DOMAIN ? '../submit.php' : VOTE_DOMAIN.'submit.php';
		$temp = $this->template->get_template($vote['template_id']);
		$template_file = $temp['template_file'];
		$style_dir = $temp['style_dir'];
		$entrip = create_filename($vote['create_time'].$vote['id'],APP_UNIQUEID);
   		$html_dir = DATA_DIR.$entrip.'/';
		$options = $this->mVote->get_vote_options($id);
		if(!$options)
		{
			$this->errorOutput('该问卷为空问卷');
		}
		$vote['mode_type'] = 'choose';
		$vote['options'] = $options;
		$vote['unique_name'] = 'option_id';
		$vote['cor'] = $vote['option_type'];
		$forms[] = $vote;
 		$content = $this->template->generation($vote,$template_file,$forms);
 		if(!$content)
		{
			$this->errorOutput('生成模板失败');
		}
		if(!is_dir($html_dir))
		{
			hg_mkdir($html_dir);
		}
		if(!$this->template->create_file($html_dir,$content))
		{
			$this->errorOutput('生成失败');
		}
		if(!$this->template->generate_assist($style_dir,$html_dir))
		{
			$this->errorOutput('生成辅助文件失败');
		}
   		if(file_exists($html_dir.'index.html'))
		{
			if($vote['status'])
			{
				$this->mVote->update(array('reupdate'=>0,'id'=>$id));
			}
			$ret['state'] = 1;
			$ret['url'] = VOTE_DOMAIN.$entrip.'/index.html';
		}
		else 
		{
			$ret['state'] = 0;
		}
		$this->addItem($ret);
		$this->output();
	}
	
	public function yuncreate()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$this->input['node_id'].')';
			$query = $this->db->query($sql);
			while($row = $this->db->fetch_array($query))
			{
				$nodes['nodes'][$row['id']] = $row['parents'];
			}
		}		
		$nodes['column_id'] = $this->input['column_id'];
		$nodes['published_column_id'] = '';
		###获取默认数据状态
		$status = $this->get_status_setting('create');
		$this->verify_content_prms($nodes);
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$options = $this->input['options'];
		if(!$options)
		{
			$this->errorOutput('投票选项不能为空');
		}
		$data = $this->request_param();
		//计算初始化总数
		$ini_total = 0;
		$min_ini = $max_ini = 0;
		$ini_person = 0;
		if(is_array($options) && count($options) > 0)
		{
			foreach($options AS $v)
			{
				if($v['picture_id'])
				{
					$option_picture[] = $v['picture_id'];
				}
				$ini_total += $v['ini_num'];
				$max_ini = ($v['ini_num'] > $max_ini) ? $v['ini_num'] : $max_ini;
			}
			if($data['option_type'] == 2 )
			{
				//如果是多选，根据最多项和最少项取投票人数范围
				$max_person = floor($ini_total/$data['min_option']) ; //最多投票人数
				$min_person = ceil($ini_total/$data['max_option']) < $max_ini ? $max_ini : ceil($ini_total/$data['max_option']); //最少投票人数
				$ini_person = mt_rand($min_person,$max_person);//根据投票人数范围，取一个随机数作为初始投票人数
			}
			else {//如果是单选，初始化人数就是初始化票数总数
				$ini_person = $ini_total;
			}		
		}
		$data['is_open'] = 1;	//新增时默认开启
		$data['status'] = $status;
		$data['ini_total'] =  $ini_total;
		$data['ini_person'] = $ini_person;
		//发布开始
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		$ret = $this->mVote->create($data);
		$vote_id = $ret['id'];
		if (!$vote_id)
		{
			$this->errorOutput('投票添加失败');
		}
		$data['id'] = $ret['id'];
		//更新排序id
		$update_data = array(
			'id'			=> $vote_id,
			'order_id'		=> $vote_id,
		);
		//创建投票图片
		if ($_FILES['question_files']['tmp_name'])
		{
			$question_file = $this->mVote->add_material($_FILES['question_files'], $vote_id);
			$index_pic = array(
			    'host' =>$question_file['host'],
			    'dir'  =>$question_file['dir'],
			    'filepath' => $question_file['filepath'],
			    'filename' => $question_file['filename'],
			    'imgwidth' => $question_file['imgwidth'],
			    'imgheight'=> $question_file['imgheight'],
			);
			$data['pictures_info'] = $update_data['pictures_info'] = $index_pic ? serialize($index_pic) : '';
		}
		$ret_vote = $this->mVote->update($update_data);
		$data['order_id'] 		= $vote_id;
		//添加投票选项
		$option_info = $this->mVote->process_options($options, $vote_id);
		
		$data['option_info'] = $option_info;
		
		if ($data['id'] && $status == 1)
		{
			//放入发布队列
			if(!empty($column_id))
			{
				$op = 'insert';
				$this->publish_insert_query($data['id'], $op);
			}
		}
		
		$mat_id = '';
		if($data['picture_ids'])
		{
			$mat_id[] = $data['picture_ids'];
		}
		if($option_picture)
		{
			$mat_id[] = implode(',',$option_picture);
		}
		if($mat_id)
		{
			$mat_ids = implode(',',$mat_id);
			$sql = 'UPDATE '.DB_PREFIX.'material SET vid = '.$vote_id.' WHERE id in('.$mat_ids.')';
			$this->db->query($sql);
		}
		//记录日志
		$this->addLogs('新增投票' , '' , $data , $data['title'], $data['id']);
		$this->addItem($data);
		$this->output();
	}
	
	public function yunupdate()
	{
		$id = intval($this->input['id']);
		$options = $this->input['options'];
	    if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		if(!$options)
		{
			$this->errorOutput('投票选项不能为空');
		}
		//取投票数据
		$vote = $this->mVote->get_simple_vote($id);
		if (!$vote)
		{
			$this->errorOutput('该投票不存在或已被删除');
		}
		$status = $vote['status'];
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($vote['node_id'])
			{
				$_node_ids = $vote['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'vote_node WHERE id IN('.$_node_ids.')';
				$query = $this->db->query($sql);
				$nodes = array();
				while($row = $this->db->fetch_array($query))
				{
					$nodes['nodes'][$row['id']] = $row['parents'];
				}
			}
		}
		#####节点权限
		$nodes['id'] 		= $id;
		$nodes['user_id'] 	= $vote['user_id'];
		$nodes['org_id'] 	= $vote['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
		//$nodes['weight'] = $vote['weight'];
		
		###获取默认数据状态
		if(!empty($vote['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $vote['status']);
		}
		else 
		{			
			if(intval($vote['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $vote['status']);
			}
		}
		
		$ori_column_id = array();
		if(is_array($vote['column_id']))
		{
			$ori_column_id = array_keys($vote['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		
		######获取默认数据状态
		$this->verify_content_prms($nodes);
		########权限#########
		
		$_options = $vote['options'];
		if($_options)
		{
			foreach ($_options as $v)
			{
				$_option_id[] = $v['id'];
			}
		}
		//计算初始化总数
		$ini_total = 0;
		$min_ini = $max_ini = 0;
		$ini_person = $vote['ini_person'];
		foreach ($options as $v)
		{
			if($v['id'])
			{
				$option_id[] = $v['id'];
			}
			if($v['picture_id'])
			{
				$option_picture[] = $v['picture_id'];
			}
			$ini_total += $v['ini_num'];
			$max_ini = ($v['ini_num'] > $max_ini) ? $v['ini_num'] : $max_ini;
		}
		$data = $this->request_param();
		$data['id'] = $id;
		if($vote['ini_total'] != $ini_total || $data['min_option'] != $vote['min_option'] || $data['max_option'] != $vote['max_option'] )
		{
			if(intval($this->input['option_type']) == 2 )
			{
				//如果是多选，根据最多项和最少项取投票人数范围
				$max_person = floor($ini_total/$data['min_option']); //最多投票人数
				$min_person = ceil($ini_total/$data['max_option']) < $max_ini ? $max_ini : ceil($ini_total/$data['max_option']); //最少投票人数
				$ini_person = mt_rand($min_person,$max_person);//根据投票人数范围，取一个随机数作为初始投票人数
			}
			else {//如果是单选，初始化人数就是初始化票数总数
				$ini_person = $ini_total;
			}
		}		
		$del_id = array_diff($_option_id,$option_id);//比较选项，得到需要删除的选项 id
		
		$data['status'] = $status;
		$data['ini_total'] =  $ini_total;
		$data['ini_person'] = $ini_person;
		//发布开始
		//$vote['column_id'] = unserialize($vote['column_id']);
		$ori_column_id = array();
		if(is_array($vote['column_id']))
		{
			$ori_column_id = array_keys($vote['column_id']);
		}
				
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		//创建投票图片
		if ($_FILES['question_files'])
		{
			$question_file = $this->mVote->add_material($_FILES['question_files'], $vote_id);
			$index_pic = array(
			    'host' =>$question_file['host'],
			    'dir'  =>$question_file['dir'],
			    'filepath' => $question_file['filepath'],
			    'filename' => $question_file['filename'],
			    'imgwidth' => $question_file['imgwidth'],
			    'imgheight'=> $question_file['imgheight'],
			);
			$data['pictures_info'] = $index_pic ? serialize($index_pic) : '';
		}
		//发布结束
		$ret_vote = $this->mVote->update($data);
		$affected_rows = $ret_vote['affected_rows'];
		
		$ret_option = $this->mVote->process_options($options, $id);
		$affected_rows = $affected_rows ? 1 : $ret_option['affected_rows'];
		$data['options'] = $options;
		
		//删除剔除的投票选项
		if ($del_id)
		{
			$delete_option_id = implode(',', $del_id);
			$this->mVote->option_delete($delete_option_id);
			$affected_rows = 1;
			//记录日志
			$this->addLogs('删除投票选项', $_options, $options, $data['title'], $data['id']);
		}
		
		//发布开始
		if ($ret_vote['id'])
		{
			//更改文章后发布的栏目
			$ret_vote['column_id'] = unserialize($ret_vote['column_id']);
			$new_column_id = array();
			if(is_array($ret_vote['column_id']))
			{
				$new_column_id = array_keys($ret_vote['column_id']);
			}
			
			if ($status ==1)
			{
				if(!empty($vote['expand_id']))   //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						$this->publish_insert_query($data['id'], 'delete',$del_column);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						$this->publish_insert_query($data['id'], 'insert',$add_column);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						$this->publish_insert_query($data['id'], 'update',$same_column);
					}
				}
				else 							//未发布，直接插入
				{
					$op = "insert";
					$this->publish_insert_query($data['id'],$op,$new_column_id);
				}
			}
			else 
			{
				if(!empty($vote['expand_id']))
				{
					$op = "delete";
					$this->publish_insert_query($data['id'],$op,$ori_column_id);
				}
			}
			
		}
		//发布结束
		
		if ($affected_rows)
		{
			$user_data = array(
				'id'				=> $id,
				'update_time'		=> TIMENOW,
				'update_org_id' 	=> $this->user['org_id'],
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name' 	=> $this->user['user_name'],
				'update_appid' 		=> $this->user['appid'],
				'update_appname' 	=> $this->user['display_name'],
				'update_ip' 		=> hg_getip(),
				'status' 			=> $status,
			);
			
			$ret_user = $this->mVote->update($user_data);
			
			if (!empty($ret_user))
			{
				unset($ret_user['id']);
				foreach ($ret_user AS $k => $v)
				{
					$data[$k] = $v;
				}
			}
			$this->addLogs('更新投票', $vote, $data, $data['title'], $data['id']);
		}
		if($data['picture_ids'])
		{
			$mat_id[] = $data['picture_ids'];
		}
		if($option_picture)
		{
			$mat_id[] = implode(',',$option_picture);
		}
		if($mat_id)
		{
			$mat_ids = implode(',',$mat_id);
			$sql = 'UPDATE '.DB_PREFIX.'material SET vid = '.$id.' WHERE id in('.$mat_ids.')';
			$this->db->query($sql);
		}
		$this->addItem($data);
		$this->output();
	}
	
	protected function request_param()
	{
		if($this->input['header_info'] && is_array($this->input['header_info']))
    	{
    		foreach ($this->input['header_info'] as $k=>$v)
    		{
    			if($v['key'] && !is_numeric($v['key']))
    			{
    				${$v['key']} = $v['value'];
    			}
    			if($v['label'] || $v['value'])
    			{
    				$header_info[] = $v;
    			}
    		}
    		$start_time = $start_time ? $start_time : $this->input['start_time'];
    		$end_time = $end_time ? $end_time : $this->input['end_time'];
    	}
    	if($this->input['footer_info'] && is_array($this->input['footer_info']))
    	{
    		foreach ($this->input['footer_info'] as $k=>$v)
    		{
    			if($v['key'] && !is_numeric($v['key']))
    			{
    				${$v['key']} = $v['value'];
    			}
    			if($v['label'] || $v['value'])
    			{
    				$footer_info[] = $v;
    			}
    		}
    	}
    	if(!$title && !trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$option_num = count(array_filter($this->input['options']));
		if($option_num < 2)
		{
			$this->errorOutput('投票不能少于两个选项');
		}
		$option_type = intval($this->input['option_type']) ? intval($this->input['option_type']) : 1;
		if($option_type == 2)
		{
			if($this->input['max_option'] < $this->input['min_option'])
			{
				$this->errorOutput('最多选项不能少于最少选项');
			}
			if(!$this->input['max_option'] || $this->input['max_option'] > $option_num || $this->input['max_option'] < 2)
			{
				$max_option = $option_num;
			}
			else 
			{
				$max_option = intval($this->input['max_option']);
			}
			if(intval($this->input['min_option'])<= 0 || $this->input['min_option'] > $option_num)
			{
				$min_option = 1;
			}
			else 
			{
				$min_option = intval($this->input['min_option']);
			}
		}else {
			$min_option = $max_option = 1;
		}
		
    	$data = array(
    		'title' 			=> $title ? $title : trim($this->input['title']),
			'describes'		 	=> $brief ? $brief : trim($this->input['brief']),
		    'keywords'          => trim($this->input['keywords']),
		    'pictures'		 	=> trim($this->input['picture_ids']),
			'start_time' 		=> strtotime($start_time),
			'end_time' 			=> strtotime($end_time),
			'ip_limit_time' 	=> $this->input['is_ip'] ? $this->input['ip_limit_time'] : 0,
			'ip_limit_num' 	    => $this->input['is_ip'] ? intval($this->input['ip_limit_num']) : 1,
			'is_ip' 			=> $this->input['is_ip'] ? 1 : 0,
			'is_userid' 		=> $this->input['is_userid'] ? 1 : 0,
		    'userid_limit_time' => $this->input['is_userid'] ? intval($this->input['userid_limit_time']) : 0,
			'userid_limit_num'  => $this->input['is_userid'] ? intval($this->input['userid_limit_num']) : 0,
			'is_feedback'       => $this->input['is_feedback'] ? 1 : 0,
			'feedback_id'     	=> $this->input['is_feedback'] ? $this->input['feedback_id'] : 0,	
			'is_verify_code' 	=> $this->input['is_verify_code'] ? 1 : 0,
			'verify_type' 	    => $this->input['is_verify_code'] && intval($this->input['verify_type']) != -1  ? intval($this->input['verify_type']) : 0,
			'is_msg_verify' 	=> $this->input['is_verify_code'] && intval($this->input['verify_type']) == -1 ? 1 : 0,
			'option_type' 		=> $option_type,//默认单选
			'option_num' 		=> $option_num,
    		'min_option' 		=> $min_option,
			'max_option' 		=> $max_option,
			'is_other' 			=> $this->input['is_other'] ? 1 : 0,
			'update_time' 		=> TIMENOW,
			'ip' 				=> hg_getip(),
			'more_info' 		=> $this->input['more_info'] ? serialize($this->input['more_info']) : '',
			'is_user_login' 	=> $this->input['is_userid'] ? 1 : 0,
			'source_type' 		=> intval($this->input['source_type']),
			'node_id' 			=> intval($this->input['sort_id']),
			'weight' 			=> intval($this->input['weight']),
			'vod_id' 			=> $this->input['vod_id'],
		    'publishcontent_id' => $this->input['publishcontent_id'] ,
			'template_sign'		=> intval($this->input['template_sign']),
			'is_device' 		=> $this->input['is_device'] ? 1 : 0,
    		'device_limit_time' => $this->input['is_device'] ? $this->input['device_limit_time'] : 0,
			'device_limit_num' 	=> $this->input['is_device'] ? intval($this->input['device_limit_num']) : 1,
			'iscomment' 		=> $this->input['iscomment'] ? 1 : 0,
			/*为了给https://redmine.hoge.cn/issues/3315提供支持，添加作者author和来源source字段*/
			'author'			=> trim($this->input['author']),
			'source'			=> trim($this->input['source']),
    		'template_id'        => intval($this->input['template_id']),
    		'header_info'	=> $header_info ? serialize($header_info) : '',
    		'footer_info'	=> $footer_info ? serialize($footer_info) : '',
		);
		return $data;
	}
	

	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
	/**
	 * 内容管理下更换内容的栏目
	 */
	public function editColumnsById()
	{
		$id = intval($this->input['id']);
		$column_id = intval($this->input['column_id']);
		$updateArray = array();
		//取投票数据
		$vote = $this->mVote->get_vote_by_id($id);
		$vote = $vote[0];
		
		
		$status = $vote['status'];
		include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
		$publish_column = new publishconfig();
		$result = $publish_column->get_columnname_by_ids('id,name',$column_id);
		$updateArray['column_id'] = serialize($result);
		$updateArray['id'] = $id;
		//修改vote_question中column_id	
		$ret_vote = $this->mVote->update($updateArray);
		//发布开始
		//$vote['column_id'] = unserialize($vote['column_id']);
		$ori_column_id = array();
		if(is_array($vote['column_id']))
		{
			$ori_column_id = array_keys($vote['column_id']);
		}
		
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? serialize($data['column_id']) : '';
		$data['id'] = $id;
		
		
		//发布开始
		if ($ret_vote['id'])
		{
			//更改文章后发布的栏目
			$ret_vote['column_id'] = unserialize($ret_vote['column_id']);
			$new_column_id = array();
			if(is_array($ret_vote['column_id']))
			{
				$new_column_id = array_keys($ret_vote['column_id']);
			}
			if ($status ==1)
			{
				if(!empty($vote['expand_id']))   //已经发布过，对比修改先后栏目
				{
					$del_column = array_diff($ori_column_id,$new_column_id);
					if(!empty($del_column))
					{
						$this->publish_insert_query($data['id'], 'delete',$del_column);
					}
					$add_column = array_diff($new_column_id,$ori_column_id);
					if(!empty($add_column))
					{
						$this->publish_insert_query($data['id'], 'insert',$add_column);
					}
					$same_column = array_intersect($ori_column_id,$new_column_id);
					if(!empty($same_column))
					{
						$this->publish_insert_query($data['id'], 'update',$same_column);
					}
				}
				else 							//未发布，直接插入
				{
					$op = "insert";
					$this->publish_insert_query($data['id'],$op,$new_column_id);
				}
			}
			else
			{
				if(!empty($vote['expand_id']))
				{
					$op = "delete";
					$this->publish_insert_query($data['id'],$op,$ori_column_id);
				}
			}
				
		}
		
		if($ret_vote)
		{
			$this->addItem($updateArray);
		}
		$this->output();
	}
	
	/**
	 * 投票赞的时候，更新赞的次数
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
		
		$sql = "UPDATE " . DB_PREFIX . "vote_question SET praise_count = praise_count" . $type . $num . " WHERE id =" . $id ;
		$this->db->query($sql);
		$sql = "SELECT id, state, expand_id, title, column_id, pub_time,user_name FROM " . DB_PREFIX ."vote_question WHERE id =" . $id ;
		$info = $this->db->query_first($sql);
		if(empty($info))
		{
			return FALSE;
		}
		if(intval($info['state']) == 1)
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
		$return = array('status' => 1,'id'=> $article_id,'pubstatus'=> 1);
		$this->addItem($return);
		$this->output();
	}
	
	
	/**
	 * 文稿移到垃圾箱
	 */
	public function moveToTrash()
	{
		$id = intval($this->input['id']);
		$vote_id = intval($this->input['vote_id']);
		$ret_sur = $this->mVote->get_vote_list(" id IN({$vote_id})");	
		$info = $ret_sur[0];
		$info['column_id'] = @unserialize($info['column_id']);
		//取消文稿库中column的关系
		//取消article
		$this->mVote->update(array(
			'id'	=> $vote_id,
			'column_id'	=> '',
			'column_url'	=> '',
		));
		//delete  pub_column
// 		$this->obj->delete('pub_column', ' aid = '.$vote_id);
		//删除发布库
		$op = "delete";
		publish_insert_query($info, $op);
		$this->addItem(array('return'=>true));
		$this->output();
	}
	
}

$out = new voteUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>