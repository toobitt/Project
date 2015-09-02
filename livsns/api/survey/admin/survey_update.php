<?php
define('MOD_UNIQUEID','survey');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/survey_mode.php');
require_once(CUR_CONF_PATH . 'lib/template_mode.php');
include_once(ROOT_PATH . 'lib/class/publishconfig.class.php');
require_once(ROOT_PATH . 'lib/class/material.class.php');
class survey_update extends adminUpdateBase
{
	private $mode;
	private $mPublishColumn;
	private $material;
	private $is_redis;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new survey_mode();
		$this->mPublishColumn = new publishconfig();
		$this->material = new material();
		$this->template = new template_mode();
		$this->is_redis = $this->settings['redis'] ? 1 : 0;
		if($this->is_redis)
		{
			$this->redis = new Redis();
			$this->redis->connect($this->settings['redis']['redis2']['host'], $this->settings['redis']['redis2']['port']);
			$this->redis->auth(REDIS_KEY);
		}
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
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$this->input['node_id'].')';
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
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		
		if(!$this->input['title'] && $this->input['title'] !== '0')
		{
			$this->errorOutput(NO_TITLE);
		}
		$statr_time = strtotime($this->input['start_time']);
		$end_time = strtotime($this->input['end_time']);
		$is_time = intval($this->input['is_time']);
		$question_time = intval($this->input['use_hour'])*3600+intval($this->input['use_minute'])*60+intval($this->input['use_second']);
		if ($start_time && $end_time && ($end_time <= $start_time))
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		if($_FILES)
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$picture_pic = $this->material->addMaterial($files, $id);
			if($picture_pic['filename'])
			{
				$pic = array(
			    'host' => $picture_pic['host'],
			    'dir'  => $picture_pic['dir'],
			    'filepath' => $picture_pic['filepath'],
			    'filename' => $picture_pic['filename'],
				'id'   => $picture_pic['id'],
				);
			    $indexpic = serialize($pic);
			}
		}  //上传问卷的索引图
		$data = array(
			'title'         => trim($this->input['title']),
		    'brief'         => trim($this->input['brief']),
		    //'problem_num'   => $problem_num,
		    'node_id'       => intval($this->input['sort_id']),
		    'start_time'    => $statr_time,
		    'end_time'      => $end_time,
		    'is_time'       => $is_time,
		    'question_time' => $is_time ? $question_time : 0,
	        //'column_id'   => $this->input['column_id'], 
	        'indexpic'      => $indexpic ? $indexpic : '',   
		    'status'        => $status,
			'more'        	=> $this->input['more'],
            'is_result_public' => $this->input['is_result_public'] ? intval($this->input['is_result_public']) : 0 ,
            'is_login'      => $this->input['is_login'] ? intval($this->input['is_login']) : 0 ,
            'is_auto_submit'=> $this->input['is_auto_submit'] ? intval($this->input['is_auto_submit']) : 0 ,
		    'is_ip'         => $this->input['is_ip'] ? intval($this->input['is_ip']) : 0 ,
            'ip_limit_time' => intval($this->input['is_ip']) ? intval($this->input['ip_limit_time']) : 0 ,
			'ip_limit_num'  => intval($this->input['ip_limit_num']) ? intval($this->input['ip_limit_num']) : 1 ,
			'is_device'     => $this->input['is_device'] ? intval($this->input['is_device']) : 0 ,
			'device_limit_time'     => $this->input['is_device'] ? intval($this->input['device_limit_time']) : 0 ,
			'device_limit_num'  => intval($this->input['device_limit_num']) ? intval($this->input['device_limit_num']) : 1 ,
			'device_num_error'	=> trim($this->input['device_num_error']),
			'device_time_error'	=> trim($this->input['device_time_error']),
		    'is_verifycode' => $this->input['is_verifycode'] ? intval($this->input['is_verifycode']) : 0 ,
			'verify_type' => intval($this->input['is_verifycode']) ? intval($this->input['verifycode_type']) : 0,
		    'ip'            => hg_getip(),
		    'picture_ids'   => trim($this->input['attach_pic']),
		    'video_ids'     => trim($this->input['attach_video']),
		    'audio_ids'     => trim($this->input['attach_audio']),
		    'publicontent_ids'   => trim($this->input['attach_cite']),
		    'org_id'        => $this->user['org_id'],
		    'user_id'       => $this->user['user_id'],
		    'user_name'     => $this->user['user_name'],
		    'create_time'   => TIMENOW,
		    'appid'         => $this->user['app_id'],
		    'appname'       => $this->user['display_name'] ? $this->user['display_name'] : 'MCP网页版',
		    'update_user_id'       => $this->user['update_user_id'],
		    'update_user_name'     => $this->user['update_user_name'],
		    'update_time'   => TIMENOW,
		    'pub_time'      => strtotime($this->input['pub_time']),
		);
	    //发布开始
	    $column_id		   = $this->input['column_id'];
		$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$data['column_id'] = $data['column_id'] ? @serialize($data['column_id']) : '';
		
		$survey = $this->mode->create('survey',$data);  //创建问卷主表
		/**
		if($data['indexpic'])
		{
			$survey['indexpic'] = hg_material_link($pic['host'],$pic['dir'],$pic['filepath'],$pic['filename']);
		}
		**/
		$vid = $survey['id'];
		$survey['column_id'] = $data['column_id'] ? @unserialize($data['column_id']) : '';
	
		$type = explode(',',$this->input['type']);

		foreach ($type as $k=>$v)
		{
			$tnum[$v][] = $v;
		}
		foreach ($type as $k=>$v)
		{
			$tid =  $v;
			$pdata = array(
				'survey_id'     => $vid,
				'type'			=> $tid,
				'title'			=> $this->get_pData($tid,$num[$tid],'title'),
				'description'	=> trim($this->get_pData($tid,$num[$tid],'brief')),
				'more'			=> trim($this->get_pData($tid,$num[$tid],'more')),
				'is_required'	=> $this->get_pData($tid,$num[$tid],'required',','),
				'max_option'	=> $this->get_pData($tid,$num[$tid],'max',','),
				'min_option'	=> $this->get_pData($tid,$num[$tid],'min',','),
				'is_other'		=> $this->get_pData($tid,$num[$tid],'other',','),
				'tips'			=> $this->get_pData($tid,$num[$tid],'tip'),
				'order_id'	 	=> $k,
			);
			$cret = $this->mode->create('problem',$pdata,0);
			$option = array();
			$ini_total_problem = $ini_max_total = 0;
			if($tid != 4)
			{
				if($tid == 3)
				{
					$t_option = explode('|',$this->get_pData($tid,$num[$tid],'title'));
				}else 
				{
					$t_option = explode('|',$this->get_pData($tid,$num[$tid],'option'));
				}
				$t_char = explode('|',$this->get_pData($tid,$num[$tid],'num'));
				$t_ininum = explode('|',$this->get_pData($tid,$num[$tid],'initnum'));
				foreach ($t_option as $kk=>$vv)
				{
					if($vv)
					{
						$option[] = array(
							'survey_id'     => $vid,
							'problem_id'    => $cret['id'],
							'name'          => $vv,
							'type'          => $tid,
							'char_num'      => intval($t_char[$kk]),
							'ini_num'       => intval($t_ininum[$kk]),
							'is_other'      => 0,
							'order_id'		=> $kk,
						);
						$ini_max_total += $t_ininum[$kk];
					}
				}
				if($tid == 2)
				{
					$ini_total_problem = rand(max($t_ininum),$ini_max_total);
				}else 
				{
					$ini_total_problem = $ini_max_total;
				}
				$ini_total = $ini_total_problem > $ini_total ? $ini_total_problem : $ini_total;
			}
			if($option)
			{
				$oData = $this->mode->insert_datas('options', $option);
				if($oData && $this->is_redis)
				{
					foreach ($oData as $vo)
					{
						$fileds['p_'.$vo['problem_id'].'_'.$vo['id']] = $vo['ini_num'];
					}
				}
			}
			if($v['is_other'])
			{
				$fileds['p_'.$cret['id'].'_-1'] = 0;
			}
			if($ini_total_problem)
			{
				$pudata = array(
					'ini_num'	=> $ini_total_problem,
				);
				$this->mode->update($cret['id'],'problem',$pudata);
			}
			$num[$tid]++;
		}
		$update_data['ini_num'] = $ini_total;
		$update_data['problem_num'] = count(array_filter($type));
		$this->mode->update($vid,'survey',$update_data);
		$survey['problem_num'] = $update_data['problem_num'];
		
		if($this->is_redis)
		{
			$this->redis->set('inig_'.$vid,$ini_total);
			$this->redis->hmset('inis_'.$vid,$fileds);
			$this->redis->set('survey_'.$vid , json_encode($data));
		}

		if ($vid)
		{
			$data['id'] = $vid;
			//放入发布队列
			if(intval($data['status']) == 1  && !empty($column_id))
			{
				$op = 'insert';
				publish_insert_query($data, $op, $data['user_name']);
			}
			if($data['picture_ids'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'material SET cid = '.$vid.' WHERE id in('.$data['picture_ids'].')';
				$this->db->query($sql);
			}
			$survey['problem'] = $problems;
			$this->addLogs('创建问卷',$survey,'','创建调查问卷' .$data['title'] .$vid);
			$this->addItem($survey);
			$this->output();
		}
	}
	
	public function update()
	{	
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		if(!trim($this->input['title']))
		{
			$this->errorOutput(NO_TITLE);
		}
		$id = intval($this->input['id']);
		$statr_time = strtotime($this->input['start_time']);
		$end_time = strtotime($this->input['end_time']);
		$is_time = intval($this->input['is_time']);
		$question_time = intval($this->input['use_hour'])*3600+intval($this->input['use_minute'])*60+intval($this->input['use_second']);
		if ($start_time && $end_time && ($end_time <= $start_time))
		{
			$this->errorOutput('结束时间不能小于开始时间');
		}
		
		$_survey = $this->mode->get_survey('id = '.$id, '*'); //获取问卷信息的初始数据
		if(!$_survey)
		{
			$this->errorOutput(NO_CONTENT);
		}
		$status = $_survey['status'];
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($_survey['node_id'])
			{
				$_node_ids = $_survey['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$_node_ids.')';
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
		$nodes['user_id'] 	= $_survey['user_id'];
		$nodes['org_id'] 	= $_survey['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
	//	$nodes['weight'] = $vote['weight'];
		
		###获取默认数据状态
		if(!empty($_survey['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $_survey['status']);
		}
		else 
		{			
			if(intval($_survey['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $_survey['status']);
			}
		}
		$_survey['column_id'] = unserialize($_survey['column_id']);
		$ori_column_id = array();
		if(is_array($_survey['column_id']))
		{
			$ori_column_id = array_keys($_survey['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		$nodes['_action'] = 'manage';
		######获取默认数据状态
		$this->verify_content_prms($nodes);
		########权限#########
		
		$update_data = array(
			'title'         => trim($this->input['title']),
		    'brief'         => trim($this->input['brief']),
		    //'problem_num'   => $problem_num,
		    'node_id'       => intval($this->input['sort_id']),
		    'start_time'    => $statr_time,
		    'end_time'      => $end_time,
			'more'        	=> $this->input['more'],
		    'is_time'       => $is_time,
		    'question_time' => $is_time ? $question_time : 0,
	        //'column_id'     => $this->input['column_id'], 
            'is_result_public' => $this->input['is_result_public'] ? intval($this->input['is_result_public']) : 0 ,
            'is_login'      => $this->input['is_login'] ? intval($this->input['is_login']) : 0 ,
            'is_auto_submit'=> $this->input['is_auto_submit'] ? intval($this->input['is_auto_submit']) : 0 ,
		    'is_ip'         => $this->input['is_ip'] ? intval($this->input['is_ip']) : 0 ,
            'ip_limit_time' => intval($this->input['is_ip']) ? intval($this->input['ip_limit_time']) : 0 ,
			'ip_limit_num'  => intval($this->input['ip_limit_num']) ? intval($this->input['ip_limit_num']) : 1 ,
			'is_device'     => $this->input['is_device'] ? intval($this->input['is_device']) : 0 ,
			'device_limit_time'     => $this->input['is_device'] ? intval($this->input['device_limit_time']) : 0 ,
			'device_limit_num'  => intval($this->input['device_limit_num']) ? intval($this->input['device_limit_num']) : 1 ,
		    'device_num_error'	=> trim($this->input['device_num_error']),
			'device_time_error'	=> trim($this->input['device_time_error']),
			'is_verifycode' => $this->input['is_verifycode'] ? intval($this->input['is_verifycode']) : 0 ,
		    'verify_type' => intval($this->input['is_verifycode']) ? intval($this->input['verifycode_type']) : 0,
		    'picture_ids'   => trim($this->input['attach_pic']),
		    'video_ids'     => trim($this->input['attach_video']),
		    'audio_ids'     => trim($this->input['attach_audio']),
		    'publicontent_ids'   => trim($this->input['attach_cite']),
		    'pub_time' => strtotime($this->input['pub_time']),
		    'status'   => $status,
		);
		$column_id		   = $this->input['column_id'];
		$update_data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
		$update_data['column_id'] = $update_data['column_id'] ? serialize($update_data['column_id']) : '';
		if($_FILES['indexpic']['name'])
		{
			$files['Filedata'] = $_FILES['indexpic'];
			$picture_pic = $this->material->addMaterial($files, $id);
			if($picture_pic['filename'])
			{
				$pic = array(
				    'host' => $picture_pic['host'],
				    'dir'  => $picture_pic['dir'],
				    'filepath' => $picture_pic['filepath'],
				    'filename' => $picture_pic['filename'],
					'id'       => $picture_pic['id'],
			    );
			    $indexpic = serialize($pic);
			}
			$update_data['indexpic'] = $indexpic ? $indexpic : '';
		}
		$affected_rows = $this->mode->update($id,'survey',$update_data);//更新主表数据

		$survey = $update_data;
		$survey['id'] = $id;
		$survey['column_id'] = $update_data['column_id'] ? @unserialize($update_data['column_id']) : '';
		$_problem = $this->mode->get_problems($id);
		
		if($_problem && is_array($_problem))
		{
			foreach ($_problem as $v)
			{
				$_pro_ids[] = $v['id'];
				$_pro_title[] = $v['title'];
				$_pro_initnum[$v['id']] = $v['ini_num'];
				if(is_array($v['options']) && count($v['options']>0))
				{
					$_option_ids[$v['id']] = array();
					foreach($v['options'] as $_options)
					{
						$_option_ids[$v['id']][] = $_options['id'];
						$_option_title[$v['id']][] = $_options['name'];
						$_pro_ini[$v['id']] += $_options['ini_num'];
					}
				}
			}
		}
				
		$detele_problem = trim($this->input['delete_proid'],','); //删除问题
		if($detele_problem)
		{
			//删除问题表
			$sql = " DELETE FROM " .DB_PREFIX. "problem WHERE id IN (" . $detele_problem . ")";
			$this->db->query($sql);
			//删除选项表
			$sql = " DELETE FROM " .DB_PREFIX. "options WHERE problem_id IN (" . $detele_problem . ")";
			$this->db->query($sql);
			$this->addLogs('更新',$ret,'','删除问题' . $detele_problem);
			$affected_rows = 1;
		}
		
		$type = explode(',',$this->input['type']);
		$pro_id = explode(',',$this->input['type_id']);
		foreach ($type as $k=>$v)
		{
			$tnum[$v][] = $v;
		}
		$ini_total = 0;
		foreach ($pro_id as $k=>$v)
		{
			$tid =  $type[$k];
			$data = array(
				'survey_id'     => $id,
				'type'			=> $tid,
				'title'			=> $this->get_pData($tid,$num[$tid],'title'),
				'description'	=> trim($this->get_pData($tid,$num[$tid],'brief')),
				'more'			=> trim($this->get_pData($tid,$num[$tid],'more')),
				'is_required'	=> $this->get_pData($tid,$num[$tid],'required',','),
				'max_option'	=> $this->get_pData($tid,$num[$tid],'max',','),
				'min_option'	=> $this->get_pData($tid,$num[$tid],'min',','),
				'is_other'		=> $this->get_pData($tid,$num[$tid],'other',','),
				'tips'			=> $this->get_pData($tid,$num[$tid],'tip'),
				'order_id' 		=> $k,
			);
			$option = array();
			$ini_total_problem = $ini_max_total = 0;
			if($tid != 4)
			{
				if($tid == 3)
				{
					$t_option = explode('|',$this->get_pData($tid,$num[$tid],'title'));
				}else 
				{
					$t_option = explode('|',$this->get_pData($tid,$num[$tid],'option'));
				}
				$t_char = explode('|',$this->get_pData($tid,$num[$tid],'num'));
				$t_ininum = explode('|',$this->get_pData($tid,$num[$tid],'initnum'));
				foreach ($t_option as $kk=>$vv)
				{
					if($vv)
					{
						$option[] = array(
								'survey_id'     => $id,
								'problem_id'    => $v,
								'name'          => $vv,
								'type'          => $tid,
								'char_num'      => intval($t_char[$kk]),
								'ini_num'       => intval($t_ininum[$kk]),
								'is_other'      => 0,
								'order_id'		=> $kk,
							);
						$ini_max_total += $t_ininum[$kk];
					}
				}
				if($tid == 2)
				{
					if($_pro_ini[$v] != $ini_max_total)
					{
						$ini_total_problem = rand(max($t_ininum),$ini_max_total);
					}else
					{
						$ini_total_problem = $_pro_initnum[$v];
					}
				}else 
				{
					$ini_total_problem = $ini_max_total;
				}
				$ini_total = $ini_total_problem > $ini_total ? $ini_total_problem : $ini_total;
			}
			$data['ini_num'] = $ini_total_problem;
			if(!intval($v))
			{
				
				$cret = $this->mode->create('problem',$data,0);
				if($option)
				{
					foreach ($option as $kk=>$vv)
					{
						$option[$kk]['problem_id'] = $cret['id'];
					}
					$this->mode->insert_datas('options', $option);
				}
				$affected_rows = 1;
			}else 
			{
				$affected_rows = $this->mode->update($v,'problem',$data) ? 1 : $affected_rows;
				if($option)
				{
					if(count($option) >= count($_option_title[$v]))//新增选项或者修改
				    {
				    	foreach ($option as $ok=>$ov)
				    	{
				    		if($_option_ids[$v][$ok])
				    		{
				    			$affected_rows = $this->mode->update($_option_ids[$v][$ok], 'options',$ov) ? 1 : $affected_rows;
				    		}
				    		else
				    		{
				    			$insert_op[] = $ov; 
				    		}
				    	}
				    }else
				    {
				    	foreach ($_option_ids[$v] as $ok=>$ov)
				    	{
				    		if($option[$ok])
				    		{
				    			$affected_rows = $this->mode->update($ov, 'options',$option[$ok]) ? 1 : $affected_rows;
				    		}
				    		else
				    		{
				    			$delete_op[] = $ov;
				    		}
				    	}	
					}
				}
			}
			$num[$tid]++;
		}
		if(count($delete_op)>0)
		{
			$delete_option = implode(',',$delete_op);
			$sql = "DELETE FROM " .DB_PREFIX. "options WHERE id IN (" . $delete_option . ")";
			$q = $this->db->query($sql);
			$affected_rows = 1;
		}
		if(count($insert_op)>0)
		{
			$options = $this->mode->insert_datas('options',$insert_op,TRUE);
			$affected_rows = 1;
		}
		
		//发布系统
		$ret_sur = $this->mode->get_survey(" id = {$id}", 'column_id,status,expand_id');
		//更改问卷之后的栏目
		$ret_sur['column_id'] = unserialize($ret_sur['column_id']);
		$new_column_id = array();
		if($ret_sur['column_id'])
		{
			$new_column_id = array_keys($ret_sur['column_id']);
		}
		$update_data['id'] = $id;
		if(intval($status) == 1)
		{
			if(!empty($ret_sur['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($update_data, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($update_data, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($update_data, 'update',$same_column);
					//有新插入素材时需插入子队列
					//publish_insert_query($update_data, 'insert',$same_column,1);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($update_data, $op);
			}
		}
		else    //打回
		{
			if(!empty($ret_sur['expand_id']))
			{
				$op = "delete";
				publish_insert_query($update_data,$op);
			}
		}
		$update_data['problem_num'] = count(array_filter($type));
		$update_data['ini_num'] = $ini_total;
		$affected_rows = $this->mode->update($id,'survey',$update_data); 
		if ($affected_rows)
		{
			$user_data = array(
				'update_time'		=> TIMENOW,
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name' 	=> $this->user['user_name'],
			);
			$ret = $this->mode->update($id,'survey',$user_data); 
		}
		$problems = $this->mode->get_problems($id);
		$survey['problems'] =  $problems;
		if($this->is_redis)
		{
			$this->redis->set('inig_'.$id,$ini_total);
			if($problems)
			{
				foreach ($problems as $vp)
				{
					foreach ($vp['options'] as $v)
					{
						$fileds['p_'.$vp['id'].'_'.$v['id']] = $v['ini_num'];
					}
					if($vp['is_other'])
					{
						$fileds['p_'.$vp['id'].'_-1'] = 0;
					}
				}
			}
			$this->redis->hmset('inis_'.$id,$fileds);
			$this->redis->set('survey_'.$id,json_encode($update_data));
		}
		if($id)
		{
			if($update_data['picture_ids'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'material SET cid = '.$id.' WHERE id in('.$update_data['picture_ids'].')';
				$this->db->query($sql);
			}
			$this->addLogs('更新问卷',$_survey,$survey,'更新' .$survey['title']. $this->input['id']);
			$this->addItem($survey);
			$this->output();
		}
	}
	
	public function delete()
	{
		$nodes = $node_id = array();
		$sql = 'SELECT * FROM '.DB_PREFIX.'survey WHERE id IN ('.$this->input['id'].')';
		$q = $this->db->query($sql);
		while($r=$this->db->fetch_array($q))
		{
			if($r['node_id'])
			{
				$node_id[] = $r['node_id'];
				$nodes[] = array(
					'title' 		=> $r['name'],
					'delete_people' => $this->user['user_name'],
					'cid' 			=> $r['id'],
					'catid' 		=> $r['node_id'],
					'user_id'		=> $r['user_id'],
					'org_id'		=> $r['org_id'],
					'id'			=> $r['id'],
				);
			}
		}
		if($node_id)
		{
			$sql = 'SELECT id,parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.implode(',',$node_id).')';
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
				$node['_action'] = 'manage';
				$this->verify_content_prms($node);
			}
		}

		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$id = $this->input['id'];
		$sql = "SELECT * FROM " . DB_PREFIX . "survey WHERE id IN(" . $id .")";
		$query = $this->db->query($sql);
		while($row = $this->db->fetch_array($query))
		{
			$column_id = @unserialize($row['column_id']);
			if(intval($row['status']) == 1 && ($row['expand_id'] || $column_id))
			{
				$op = "delete";
				publish_insert_query($row,$op);
			}
		}
		
		$ret = $this->mode->delete($id);
		if($ret)
		{
			$this->addLogs('删除',$ret,'','删除' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$this->input['node_id'].')';
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
		
		$id = trim($this->input['id']);
		$audit = intval($this->input['audit']);
		$ret = $this->mode->audit($id,$audit);
	    if($audit == 1)
	    {
	    	$ret_sur = $this->mode->get_survey_list(" id IN({$id})");
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
	    elseif($audit == 0)
	    {
	    	$ret_sur = $this->mode->get_survey_list(" id IN({$id})");
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
	    
		if($ret)
		{
			$sql = 'UPDATE '.DB_PREFIX.'survey SET audit_user_id ='.$this->user['user_id'].', audit_user_name ="'.$this->user['user_name'].'", audit_time = '.TIMENOW.' WHERE id in('.$id.')';
			$this->db->query($sql);
			
			$this->addLogs('审核','',$ret,'审核' . $this->input['id']);
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->drag_order('survey', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}

	/**
	 * 上传图片
	 * 
	 */
	public function upload_image()
	{
		$files['Filedata'] = $_FILES['pic'];
		
		if($files['Filedata'])
		{
			$picture_pic = $this->material->addMaterial($files, $id);
			$img_info = addslashes(serialize($picture_pic));	
		}
		if(!$picture_pic) 
		{
			$this->errorOutput('没有上传的图片信息');
		}
		$sql = " INSERT INTO " . DB_PREFIX . "material SET material_id = '" . $picture_pic['id'] ."',
		                                                   name = '" . trim($picture_pic['name']) ."', 
		                                                   pic = '" . $img_info ."', 
		                                                   host = '" . $picture_pic['host'] ."', 
		                                                   dir = '" . $picture_pic['dir'] ."', 
		                                                   filepath = '" . $picture_pic['filepath'] ."', 
		                                                   filename = '" . trim($picture_pic['filename']) ."', 
		                                                   imgwidth = '" . $picture_pic['imgwidth'] ."', 
		                                                   imgheight = '" . $picture_pic['imgheight'] ."',
		                                                   filesize = '" . $picture_pic['filesize'] ."',
		                                                   create_time = '" . TIMENOW ."'";
		$query = $this->db->query($sql);
		
		$vid = $this->db->insert_id();
        $data['id'] = $vid;
		$data['img_info'] = hg_fetchimgurl($picture_pic);
		$data['upload_type'] = '图片';
		$data['img_arr'] = array('host'=>$picture_pic['host'],'dir'=>$picture_pic['dir'],'filepath'=>$picture_pic['filepath'],'filename'=>$picture_pic['filename']);
		$this->addItem($data);
		$this->output();
	}
	
	public function delete_image()
	{
		$img_id = $this->input['img_id'];
		if(empty($img_id))
		{
			$this->errorOutput(NO_IMAGE_ID);
		}
		$sql = " DELETE FROM " .DB_PREFIX. "material WHERE id IN (" . $img_id . ")";
		$this->db->query($sql);
		$this->addItem($img_id);
		$this->output();
	}
	
	/**
	 * 视频上传接口
	 * 
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
			$videoConfig = $this->settings['video_type'] ? explode(',',$this->settings['video_type']['type']) : $this->mode->getVideoConfig();
			if (!$videoConfig)
			{
				$this->errorOutput('获取允许上传的视频类型失败！');
			}
			$filetype = strtolower(strrchr($_FILES['videofile']['name'], '.'));	
			if (!in_array($filetype, $videoConfig))
			{
				$this->errorOutput('只允许上传'.$this->settings['video_type']['type'].'格式的视频');
			}
			//上传视频服务器
			$videodata = $this->mode->uploadToVideoServer($_FILES, $data['title'], $data['brief']);
			if (!$videodata)
			{
				$this->errorOutput('视频服务器错误!');
			}
			$data = $this->mode->get_vod_info_by_id($videodata['id']);
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
	 	$sql = "SELECT * FROM " . DB_PREFIX ."survey WHERE id IN( " . $id . ")";
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
	 			$row['column_id'] = is_array($row['column_id']) ? ($row['column_id'] + $column_id) : $column_id;
	 		}
	 		else
	 		{
	 			$row['column_id'] = $column_id;
	 		}
	 		$new_column_id = array_keys($row['column_id']);
	 		
	 		/***************************权限控制***************************************/
	 		$this->verify_content_prms(array('column_id' =>$this->input['column_id'], 'published_column_id'=>$ori_column_id_str));
	 		/***************************权限控制***************************************/
	 		$sql = "UPDATE " . DB_PREFIX ."survey SET column_id = '". addslashes(serialize($row['column_id'] )) ."',pub_time = ".$pub_time." WHERE id = " . $row['id'];
	 		$this->db->query($sql);
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
	 					publish_insert_query($row,$op,$new_column_id);
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
	
	public function add_tags()
	{
		$tag_name = $this->input['tag_name'];
		if(!$tag_name)
		{
			$this->errorOutput(NO_NAME);
		}
		$data['tag_name'] = $tag_name;
		$data['user_id'] = $this->user['user_id'];
		$cre = $this->mode->create('tags',$data);
		$this->addItem($cre);
		$this->output();
	}
	
	public function delete_tags()
	{
		$tag_id = intval($this->input['tag_id']);
		if(!$tag_id)
		{
			$this->errorOutput(NOID);
		}
		$sql = " DELETE FROM " .DB_PREFIX. "tags WHERE id IN (" . $tag_id . ")";
		$this->db->query($sql);
		$this->addItem($tag_id);
		$this->output();
	}
	
	 /**
	  * 移动
	  */
	 public function move()
	 {
	 	$id = urldecode($this->input['content_id']);
	 	$node_id = intval($this->input['node_id']);
	 	if(!$id)
	 	{
	 		$this->errorOutput('问卷ID不能为空');
	 	}
	 	if($node_id)
	 	{
	 		$this->db->update_data(array('node_id'=>$node_id), 'survey', ' id IN('.$id.')');
	 	}
	 	$ret = array('success' => true, 'id' => $id);
	 	$this->addItem($ret);
	 	$this->output();
	 }
	 
	public function yuncreate()
	{
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
		{
			$sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$this->input['node_id'].')';
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
		$nodes['_action'] = 'manage';
		$this->verify_content_prms($nodes);
		
		#####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
		$problems = $this->input['forms'];
		$problems = $problems ? $problems : array();
		$data = $this->request_param();
		$data['problem_num'] = count(array_filter($problems));
		$data['org_id']			= $this->user['org_id'];
		$data['user_id'] 		= $this->user['user_id'];
		$data['user_name']		= $this->user['user_name'];
		$data['create_time']	= TIMENOW;
		$data['appid']			= $this->user['app_id'];
		$data['appname']		= $this->user['display_name'];
		$data['status'] = $status;
	    //发布开始
	    if($column_id = $this->input['column_id'])
	    {
			$data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
			$data['column_id'] = $data['column_id'] ? @serialize($data['column_id']) : '';
	    }
		if($_FILES['Filedata']['name'])
		{
			$files['Filedata'] = $_FILES['Filedata'];
			$picture_pic = $this->material->addMaterial($files);
			if($picture_pic['filename'])
			{
				$data['indexpic'] = serialize(array(
			    	'host' 		=> $picture_pic['host'],
			    	'dir'  		=> $picture_pic['dir'],
			    	'filepath' 	=> $picture_pic['filepath'],
			    	'filename' 	=> $picture_pic['filename'],
					'id'   		=> $picture_pic['id'],
				));
			}
		}  //上传问卷的索引图
		$survey = $this->mode->create('survey',$data);  //创建问卷主表
		$vid = $survey['id'];
		$this->mode->process_data($problems,$vid,1);
		if ($vid)
		{
			$data['id'] = $vid;
			$pro = $this->mode->get_child_problem($vid);
			$data['problem'] = $survey['problem'] = $pro ? $pro : array();
			//放入发布队列
			if(intval($data['status']) == 1  && !empty($column_id))
			{
				$op = 'insert';
				publish_insert_query($data, $op, $data['user_name']);
			}
			if($data['picture_ids'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'material SET cid = '.$vid.' WHERE id in('.$data['picture_ids'].')';
				$this->db->query($sql);
			}
			$this->addLogs('创建问卷',$survey,'','创建调查问卷' .$data['title'] .$vid);
			$this->addItem($survey);
			$this->output();
		}
	}	 
	
	public function yunupdate()
	{	
		$id = intval($this->input['id']);
		$_survey = $this->mode->get_survey('id = '.$id, '*'); //获取问卷信息的初始数据
		if(!$_survey)
		{
			$this->errorOutput(NO_CONTENT);
		}
		$status = $_survey['status'];
		#####节点权限检测数据收集
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$_node_ids = '';
			if($_survey['node_id'])
			{
				$_node_ids = $_survey['node_id'];
			}
			if($this->input['node_id'])
			{
				$_node_ids  = $_node_ids ? $_node_ids . ',' . $this->input['node_id'] : $this->input['node_id'];
			}
			if($_node_ids)
			{
				$sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$_node_ids.')';
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
		$nodes['user_id'] 	= $_survey['user_id'];
		$nodes['org_id'] 	= $_survey['org_id'];
		$nodes['column_id'] = $this->input['column_id'];
		
		$nodes['published_column_id'] = '';
	//	$nodes['weight'] = $vote['weight'];
		
		###获取默认数据状态
		if(!empty($_survey['column_id']))
		{
			$status = $this->get_status_setting('update_publish', $_survey['status']);
		}
		else 
		{			
			if(intval($_survey['status']) == 1)
			{
				$status = $this->get_status_setting('update_audit', $_survey['status']);
			}
		}
		$_survey['column_id'] = $_survey['column_id'] ? unserialize($_survey['column_id']) : array();
		$ori_column_id = array();
		if(is_array($_survey['column_id']))
		{
			$ori_column_id = array_keys($_survey['column_id']);
			$nodes['published_column_id'] = implode(',', $ori_column_id);
		}
		$nodes['_action'] = 'manage';
		######获取默认数据状态
		$this->verify_content_prms($nodes);
		########权限#########
		
		$problems = $this->input['forms'];
		if(!$problems)
		{
			$this->errorOutput('未提交问卷的问题');
		}
		$update_data = $this->request_param();
		$update_data['problem_num'] = count(array_filter($problems));
		$update_data['status'] = $status;
		if($column_id = $this->input['column_id'])
		{
			$update_data['column_id'] = $this->mPublishColumn->get_columnname_by_ids('id,name', $column_id);
			$update_data['column_id'] = $update_data['column_id'] ? serialize($update_data['column_id']) : '';
		};
		if($_FILES['Filedata']['name'])
		{
			$files['Filedata'] = $_FILES['Filedata'];
			$picture_pic = $this->material->addMaterial($files, $id);
			if($picture_pic['filename'])
			{
				$pic = array(
			    'host' => $picture_pic['host'],
			    'dir'  => $picture_pic['dir'],
			    'filepath' => $picture_pic['filepath'],
			    'filename' => $picture_pic['filename'],
				'id'       => $picture_pic['id'],
			    );
			    $indexpic = @serialize($pic);
			}
			$update_data['indexpic'] = $indexpic ? $indexpic : '';
		}
		$affected_rows = $this->mode->update($id,'survey',$update_data);//更新主表数据

		$survey = $update_data;
		$survey['id'] = $id;
		$survey['column_id'] = $update_data['column_id'] ? unserialize($update_data['column_id']) : '';
		$del_id = $_problem_id = array();
		$sql = 'SELECT id FROM '.DB_PREFIX.'problem WHERE survey_id = '.$id;
		$q = $this->db->query($sql);
		while ($r = $this->db->fetch_array($q))
		{
			$_problem_id[] = $r['id'];
		}
		foreach ($problems as $v)
		{
			$problems_id[] = $v['id'];
		}
		$del_id = array_diff($_problem_id,$problems_id);//比较标准组件，要删除的组件id 
		if($del_id)
		{
			$detele_problem = implode(',',$del_id);
			//删除问题表
			$sql = " DELETE FROM " .DB_PREFIX. "problem WHERE id IN (" . $detele_problem . ")";
			$this->db->query($sql);
			//删除选项表
			$sql = " DELETE FROM " .DB_PREFIX. "options WHERE problem_id IN (" . $detele_problem . ")";
			$this->db->query($sql);
			$this->addLogs('更新',$ret,'','删除问题' . $detele_problem);
			$affected_rows = 1;
		}
		$affected_rows = $this->mode->process_data($problems,$id) || $affected_rows ? 1 : 0;
		//发布系统
		$ret_sur = $this->mode->get_survey(" id = {$id}", 'column_id,status,expand_id');
		//更改问卷之后的栏目
		$ret_sur['column_id'] = $ret_sur['column_id'] ? unserialize($ret_sur['column_id']) : array();
		$new_column_id = array();
		if($ret_sur['column_id'] && is_array($ret_sur['column_id']))
		{
			$new_column_id = array_keys($ret_sur['column_id']);
		}
		$update_data['id'] = $id;
		if(intval($status) == 1)
		{
			if(!empty($ret_sur['expand_id']))   //已经发布过，对比修改先后栏目
			{
				$del_column = array_diff($ori_column_id,$new_column_id);
				if(!empty($del_column))
				{
					publish_insert_query($update_data, 'delete',$del_column);
				}
				$add_column = array_diff($new_column_id,$ori_column_id);
				if(!empty($add_column))
				{
					publish_insert_query($update_data, 'insert',$add_column);
				}
				$same_column = array_intersect($ori_column_id,$new_column_id);
				if(!empty($same_column))
				{
					publish_insert_query($update_data, 'update',$same_column);
					//有新插入素材时需插入子队列
					//publish_insert_query($update_data, 'insert',$same_column,1);
				}
			}
			else 							//未发布，直接插入
			{
				$op = "insert";
				publish_insert_query($update_data, $op);
			}
		}
		else    //打回
		{
			if(!empty($ret_sur['expand_id']))
			{
				$op = "delete";
				publish_insert_query($update_data,$op);
			}
		}
		$affected_rows = $this->mode->update($id,'survey',$update_data) || $affected_rows ? 1 : 0; 
		if ($affected_rows)
		{
			$user_data = array(
				'update_time'		=> TIMENOW,
				'update_user_id' 	=> $this->user['user_id'],
				'update_user_name' 	=> $this->user['user_name'],
			);
			$ret = $this->mode->update($id,'survey',$user_data); 
		}
		$survey['problems'] =  $problems;

		if($id)
		{
			if($update_data['picture_ids'])
			{
				$sql = 'UPDATE '.DB_PREFIX.'material SET cid = '.$id.' WHERE id in('.$update_data['picture_ids'].')';
				$this->db->query($sql);
			}
			$this->addLogs('更新问卷',$_survey,$survey,'更新' .$survey['title']. $this->input['id']);
			$this->addItem($survey);
			$this->output();
		}
	}
	
	protected function request_param()
	{
		$question_time = intval($this->input['use_hour'])*3600+intval($this->input['use_minute'])*60+intval($this->input['use_second']);
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
		if($start_time && $end_time && strtotime($start_time) >= strtotime($end_time))
        {
        	$this->errorOutput('结束时间必须大于开始时间');
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
    	if(!$this->input['title'] && $this->input['title'] !== '0')
		{
			//$this->errorOutput(NO_TITLE);
			$title = '问卷标题';
		}
    	$data = array(
			'title'         => $title ? $title : trim($this->input['title']),
		    'brief'         => $brief ? $brief : trim($this->input['brief']),
		    'node_id'       => intval($this->input['sort_id']),
		    'start_time'    => strtotime($start_time),
		    'end_time'      => strtotime($end_time),
		    'is_time'       => $this->input['is_time'] ? 1 : 0,
		    'question_time' => $this->input['is_time'] ? $question_time : 0,
            'is_result_public' => $this->input['is_result_public'] ? 1 : 0 ,
            'is_login'      => $this->input['is_login'] ? 1 : 0 ,
            'is_auto_submit'=> $this->input['is_auto_submit'] ? 1 : 0 ,
		    'is_ip'         => $this->input['is_ip'] ? 1 : 0 ,
            'ip_limit_time' => $this->input['is_ip'] ? $this->input['ip_limit_time'] : 0 ,
    		'ip_limit_num'	=> $this->input['is_ip'] ? intval($this->input['ip_limit_num']) : 1 ,
    		'is_device'         => $this->input['is_device'] ? 1 : 0 ,
            'device_limit_time' => $this->input['is_ip'] ? $this->input['device_limit_time'] : 0 ,
    		'device_limit_num'	=> $this->input['is_ip'] ? intval($this->input['device_limit_num']) : 1 ,
		    'is_verifycode' => $this->input['is_verifycode'] ? 1 : 0 ,
			'verify_type' 	=> $this->input['is_verifycode'] ? intval($this->input['verifycode_type']) : 0,
		    'ip'            => hg_getip(),
		    'picture_ids'   => trim($this->input['picture_ids']),
		    'video_ids'     => trim($this->input['video_ids']),
		    'audio_ids'     => trim($this->input['audio_ids']),
		    'publicontent_ids'   => trim($this->input['publicontent_ids']),
			'template_id'        => intval($this->input['template_id']),
    		'header_info'	=> $header_info ? serialize($header_info) : '',
    		'footer_info'	=> $footer_info ? serialize($footer_info) : '',
		);
		return $data;
	}
	
	/**
	 * 生成
	 */
	public function generate()
	{
    	$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$survey = $this->mode->get_survey_info($id);
		if($survey['status'] == 0 || $survey['status'] == 2)
		{
			$this->errorOutput('对不起，该表单未通过审核');
		}
		/*
		if(!$survey['template_id'])
		{
			$this->errorOutput('对不起，尚未选择模板');
		}
		*/
		$survey['url'] = !defined('SV_DOMAIN') || !SV_DOMAIN ? '../submit.php' : SV_DOMAIN.'submit.php';
		/*
		$temp = $this->template->get_template($survey['template_id']);
		$template_file = $temp['template_file']; //模板文件
		$style_dir = $temp['style_dir'];//源文件路径
		*/
		$entrip = $survey['create_time'].$survey['id'];
   		$html_dir = DATA_DIR.$entrip.'/';//生成文件的路径
		$problem = $this->mode->get_child_problem($id);
		if(!$problem)
		{
			$this->errorOutput('该问卷为空问卷');
		}
		$style_dir =  CORE_DIR.TFILE.'/';
		$template_file = CORE_DIR.TFILE.'/index.html';
		$survey['assist_url'] = SV_CSS_DOMAIN.TFILE ? SV_CSS_DOMAIN.TFILE : '.';
		$survey['brief'] = nl2br($survey['brief']);
		$survey['limit'] = json_encode(array(
			'is_login'=> $survey['is_login'],
			'is_device'=> $survey['is_device'],
			'vote_num'=> $survey['device_limit_num'],
			'interval' => $survey['device_limit_time'],
			'start_time'	=> $survey['start_time'] ? strtotime($survey['start_time']) : '',
			'end_time'		=> $survey['end_time'] ? strtotime($survey['end_time']) : '',
			'device_num_error'	=> $survey['device_num_error'] ? $survey['device_num_error'] : ( '同一设备只能投'.$survey['device_limit_num'].'票，谢谢参与！' ),
			'device_time_error'	=> $survey['device_time_error'] ? $survey['device_time_error'] : ( $survey['device_limit_time'] .'小时内只能投1票' ),
			'no_device_error'	=> NO_DEVICE_TIPS,
			)
		);
 		$content = $this->template->generation($survey,$template_file,$problem);
 		if(!$content)
		{
			$this->errorOutput('生成模板失败');
		}
		if(!is_dir($html_dir))
		{
			hg_mkdir($html_dir);
		}
		$assist_dir = DATA_DIR.TFILE.'/';
		if(!is_dir($assist_dir))
		{
			hg_mkdir($assist_dir);
		}
		if(!$this->template->generate_assist($style_dir,$assist_dir))
		{
			$this->errorOutput('生成辅助文件失败');
		}
		if(!$this->template->create_file($html_dir,$content))
		{
			$this->errorOutput('生成失败');
		}
   		if(file_exists($html_dir.'index.html'))
		{
			if($survey['status'])
			{
				$updatedata['reupdate'] = 1;
			}
			$updatedata['gen_status'] = $ret['state'] = 1;
			$updatedata['gen_url'] = $ret['url'] = SV_DOMAIN.$entrip.'/index.html';
		}
		else 
		{
			$updatedata['gen_url'] = $ret['url'] = '';
			$updatedata['gen_status'] = $ret['state'] = 0;
		}
		if(file_exists($style_dir.'index.manifest'))
		{
			$manifest = file_get_contents($style_dir.'index.manifest');
			$manifest = str_replace('{liv_assist_url}',$survey['assist_url'],$manifest);
			file_put_contents($html_dir.'index.manifest', $manifest);
		}
		if(file_exists($style_dir.'js/views.js'))
		{
			$view = file_get_contents($style_dir.'js/views.js');
			$view = str_replace('{liv_assist_url}',SV_HOST,$view);
			file_put_contents($assist_dir.'js/views.js', $view);
		}
		if(file_exists($style_dir.'js/views.min.js'))
		{
			$view = file_get_contents($style_dir.'js/views.js');
			$view = str_replace('{liv_assist_url}',SV_HOST,$view);
			file_put_contents($assist_dir.'js/views.min.js', $view);
		}
		$this->mode->update($id, 'survey',$updatedata);
		$this->addItem($ret);
		$this->output();
    
		
	}

    /**
     * 新增云表单
     */
    public function process_header()
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
        if($start_time && $end_time && strtotime($start_time) >= strtotime($end_time))
        {
        	$this->errorOutput('结束时间必须大于开始时间');
        }
        //2015-6-24
        if(!$title && $title !== '0')
        {
            $this->errorOutput('标题不能为空');
        }
        
        if($title)
        {
        	$this->input['title'] = $title;
        }
       $this->input['title'] = $this->input['title'] ? $this->input['title'] : '问卷标题';
       
        $data = array(
            'title'         => $this->input['title'],
            'brief'         => $brief ? $brief : trim($this->input['brief']),
            'start_time'    => strtotime($start_time),
            'end_time'      => strtotime($end_time),
            'header_info'	=> $header_info ? serialize($header_info) : '',
        	'picture_ids'   => trim($this->input['picture_ids']),
            'video_ids'     => trim($this->input['video_ids']),
            'audio_ids'     => trim($this->input['audio_ids']),
            'publicontent_ids'   => trim($this->input['publicontent_ids']),
        );
        return $data;
    }

    /**
     * 保存头部信息
     * Enter description here ...
     */
    public function save_header()
    {
        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点
        if($this->user['group_type'] > MAX_ADMIN_TYPE && $this->input['node_id'])
        {
            $sql = 'SELECT id, parents FROM '.DB_PREFIX.'survey_node WHERE id IN('.$this->input['node_id'].')';
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
        $nodes['_action'] = 'manage';
        $this->verify_content_prms($nodes);
        #####节点权限认证需要将节点数据放在nodes=>标志＝>节点id=>节点所有父级节点

        $id = $this->input['id'];
        $data = $this->process_header();
        if($id)
        {
            $ret = $this->mode->update($id, 'survey',$data);
            if($ret)
            {
            	$data['id'] = 1;
            	$data['affect_rows'] = $id;
                $data['update_time'] = $update_data['update_time']	= TIMENOW;
                $data['update_user_id'] = $update_data['update_user_id']	= $this->user['user_id'];
                $data['update_user_name'] = $update_data['update_user_name'] = $this->user['user_name'];
                $data['reupdate'] = $update_data['reupdate']		= 1;
                $this->mode->update($id, 'survey',$update_data);
            }
        }
        else
        {
            $data['org_id']			= $this->user['org_id'];
            $data['user_id'] 		= $this->user['user_id'];
            $data['user_name']		= $this->user['user_name'];
            $data['create_time']	= TIMENOW;
            $data['appid']			= $this->user['app_id'];
            $data['appname']		= $this->user['display_name'];
            $data['ip'] = hg_getip();
            $data['update_time']	= TIMENOW;
            $data['update_user_id']	= $this->user['user_id'];
            $data['update_user_name'] = $this->user['user_name'];
            $data['reupdate']		= 1;
            $ret = $this->mode->create('survey',$data,1);
            if($ret['id'])
            {
            	$data['affect_rows'] = 1;
                $this->addLogs('新增问卷', '', $ret,$ret['title'],$ret['id']);
            }
            $data['id'] = $ret['id'];
        }
        $this->addItem($data);
        $this->output();
    }

    /**
     * 保存底部信息
     * Enter description here ...
     */
    public function save_footer()
    {
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput('请先添加一个标题');
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
        $data = array(
            'footer_info'	=> $footer_info ? serialize($footer_info) : '',
        );
        $ret = $this->mode->update($id, 'survey',$data);
        if($ret)
        {
        	$data['id'] = $id;
        	$data['affect_rows'] = 1;
            $update_data['update_time']	= TIMENOW;
            $update_data['update_user_id']	= $this->user['user_id'];
            $update_data['update_user_name'] = $this->user['user_name'];
            $update_data['reupdate']		= 1;
            $this->mode->update($id, 'survey',$update_data);
        }
        $this->addItem($data);
        $this->output();
    }

    /**
     * 保存其他属性
     * Enter description here ...
     */
    public function save_other()
    {
        $id = $this->input['id'];
        if(!$id)
        {
            $this->errorOutput('请先添加一个标题');
        }
        $question_time = intval($this->input['use_hour'])*3600+intval($this->input['use_minute'])*60+intval($this->input['use_second']);
        $data = array(
            'node_id'       => intval($this->input['sort_id']),
            'is_time'       => $this->input['is_time'] ? 1 : 0,
            'question_time' => $this->input['is_time'] ? $question_time : 0,
            'is_result_public' => $this->input['is_result_public'] ? 1 : 0 ,
            'is_login'      => $this->input['is_login'] ? 1 : 0 ,
            'is_auto_submit'=> $this->input['is_auto_submit'] ? 1 : 0 ,
            'is_ip'         => $this->input['is_ip'] ? 1 : 0 ,
            'ip_limit_time' => $this->input['is_ip'] ? $this->input['ip_limit_time'] : 0 ,
            'ip_limit_num'	=> $this->input['is_ip'] ? intval($this->input['ip_limit_num']) : 1 ,
            'is_device'         => $this->input['is_device'] ? 1 : 0 ,
            'device_limit_time' => $this->input['is_ip'] ? $this->input['device_limit_time'] : 0 ,
            'device_limit_num'	=> $this->input['is_ip'] ? intval($this->input['device_limit_num']) : 1 ,
            'is_verifycode' => $this->input['is_verifycode'] ? 1 : 0 ,
            'verify_type' 	=> $this->input['is_verifycode'] ? intval($this->input['verifycode_type']) : 0,
            //'picture_ids'   => trim($this->input['picture_ids']),
            //'video_ids'     => trim($this->input['video_ids']),
            //'audio_ids'     => trim($this->input['audio_ids']),
            //'publicontent_ids'   => trim($this->input['publicontent_ids']),
             'template_id'   => intval($this->input['template_id']),
        );
        $ret = $this->mode->update($id, 'survey',$data);
        if($ret)
        {
        	$data['id'] = $id;
        	$data['affect_rows'] = 1;
            $update_data['update_time']	= TIMENOW;
            $update_data['update_user_id']	= $this->user['user_id'];
            $update_data['update_user_name'] = $this->user['user_name'];
            $update_data['reupdate']	= 1;
            $this->mode->update($id, 'survey',$update_data);
        }
        $this->addItem($data);
        $this->output();
    }

    /**
     * 新增组件
     * Enter description here ...
     */
    public function create_component()
    {
        $survey_id = $this->input['survey_id'];
        if(!$survey_id)
        {
            $this->errorOutput('未指定调查表');
        }
        $problem = $this->input['problem_info'];
       
        $data = array(
            'survey_id'    => $survey_id,
            'title'        => $problem['title'],
            'description'  => $problem['description'],
            'type'         => $problem['type'] ? $problem['type'] : $problem['form_type'],
            'is_required'  => $problem['is_required'] ? 1 : 0,
            'is_other'     => $problem['is_other'] ? 1 : 0,
            'tips'         => $problem['tips'],
            'min_option'   => $problem['min'] ? $problem['min'] : $problem['min_option'],
            'max_option'   => $problem['max'] ? $problem['max'] : $problem['max_option'],
            'counts'        => 0,
            'picture'      => '',
            //'options'      => $problem['options']
        );
        $options = array();
        if($problem['options'])
        {
	        foreach($problem['options'] as $k=>$v)
	        {
	            $options[] = array(
	                "name"		=>trim($v['name']),
	                "is_other"	=>$v['is_other'] ? 1 : 0,
	                "type"		=>intval($v['type']),
	                "char_num"	=>intval($v['char_num']),
	                "total"		=>intval($v['total']),
	             	"order_id"	=>$k,
	            );
	        }
        }
        
        $ret = $this->mode->create_component($survey_id,$data,$options);
        if($ret)
        {
        	$this->mode->update($survey_id, 'survey',array('reupdate'	=> 1));
            $this->addItem($ret);
            $this->output();
        }
        else
        {
            $this->errorOutput('创建问题失败');
        }
        
    }

    /**
     * 编辑组件
     * Enter description here ...
     */
    public function update_component()
    {
        if(!$this->input['pid'])
        {
            $this->errorOutput('未指定更新题目');
        }
        $problem = $this->input['problem_info'];
  	  	
        $data = array(
            'title'        => $problem['title'],
            'description'  => $problem['description'],
            'type'         => $problem['type'] ? $problem['type'] : $problem['form_type'],
            'is_required'  => $problem['is_required'] ? 1 : 0,
            'is_other'     => $problem['is_other'] ? 1 : 0,
            'tips'         => $problem['tips'],
            'min_option'   => $problem['min'] ? $problem['min'] : $problem['min_option'],
            'max_option'   => $problem['max'] ? $problem['max'] : $problem['max_option'],
            'counts'        => 0,
            'picture'      => '',
        );
        $options = array();
        if($problem['options'])
        {
	        foreach($problem['options'] as $k=>$v)
	        {
	        	if(trim($v['name']))
	        	{
		            $options[$k] = array(
		            	"name"		=>trim($v['name']),
		                "is_other"	=>$v['is_other'] ? 1 : 0,
		                "type"		=>intval($v['type']),
		                "char_num"	=>intval($v['char_num']),
		                "total"		=>intval($v['total']),
		            	"order_id"	=>$k,
		            );
	        	}
	            if(isset($v['id']))
	            {
	                $options[$k]['id'] = $v['id'];
	            }
	        }
        }
        $ret = $this->mode->update_component($this->input['pid'],$data,$options);
        if($ret)
        {
	       	$updatedata = array(
        		'update_user_id'	=> $this->user['user_id'],
        		'update_user_name'	=> $this->user['user_name'],
        		'update_time'	=> TIMENOW,
        		'reupdate'		=> 1,
        	);
        	$this->mode->update($problem['survey_id'], 'survey',$updatedata);
        }
        $data['options'] = $options;
        $data['affect_rows'] = $ret;
        $this->addItem($data);
        $this->output();
    }

    /**
     * 删除组件
     * @param pid string 问题id
     * Enter description here ...
     */
    public function delete_component()
    {
        $pid = $this->input['pid'];
        if(!$pid)
        {
            $this->errorOutput('请先选择一个问题');
        }
        $sql = 'SELECT survey_id FROM '.DB_PREFIX.'problem WHERE id = ' . intval($pid) ;
        $q = $this->db->query_first($sql);
        //删除问题表
        $sql = " DELETE FROM " .DB_PREFIX. "problem WHERE id IN (" . $pid . ")";
        $this->db->query($sql);
        //删除选项表
        $sql = " DELETE FROM " .DB_PREFIX. "options WHERE problem_id IN (" . $pid . ")";
        $this->db->query($sql);
    	if($pid && $q['survey_id'])
        {
	       	$updatedata = array(
        		'update_user_id'	=> $this->user['user_id'],
        		'update_user_name'	=> $this->user['user_name'],
        		'update_time'	=> TIMENOW,
        		'reupdate'		=> 1,
        	);
        	$this->mode->update($q['survey_id'], 'survey',$updatedata);
        }
        $this->addItem($pid);
        $this->output();
    }

    /**
     * @param content_id
     * @param order_id
     * Enter description here ...
     */
    public function sort_component()
    {
        if(!$this->input['content_id'])
        {
            $this->errorOutput(NOID);
        }

        $ret = $this->drag_order('problem', 'order_id');
        $this->addItem($ret);
        $this->output();
    }
    
    private function get_pData($tid,$num,$name,$exp = '@')
    {
    	if($this->input[$tid.'_'.$name])
    	{
    		$d = explode($exp,$this->input[$tid.'_'.$name]);
    		return $d[intval($num)];
    	}else 
    	{
    		return '';
    	}
    }
    
    //生成状态，生成链接 回调
    public function generate_callback()
    {
    	$id = $this->input['id'];
    	if(!$id)
    	{
    		$this->errorOutput(NOID);
    	}
    	$data = array(
    		'gen_status' => intval($this->input['gen_status']),
    		'gen_url'	 => $this->input['gen_status'] ? $this->input['gen_url'] : '',
    		'reupdate'	 => intval($this->input['reupdate']),
    		'status'	 => intval($this->input['status']),
    	);
    	$this->mode->update($id, 'survey',$data);
    	$this->addItem($data);
    	$this->output();
    }

	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}

}
$out = new survey_update();
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



