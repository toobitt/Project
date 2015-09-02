<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* @public function show|detail|count
* @private function get_condition
* 
* $Id: vote_question.php 6445 2012-04-18 06:47:35Z lijiaying $
***************************************************************************/
require('global.php');
define('MOD_UNIQUEID','vote');//模块标识
class voteApi extends adminReadBase
{
	private $mVote;
	public function __construct()
	{
		parent::__construct();
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'增加',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'状态',
	//	'manage'	=>'管理',
		'_node'=>array(
			'name'=>'投票分类',
			'filename'=>'vote_node.php',
			'node_uniqueid'=>'vote_node',
			),
		);
		require_once CUR_CONF_PATH . 'lib/vote.class.php';
		$this->mVote = new vote();
		
		require_once(ROOT_PATH.'lib/class/material.class.php');
		$this->material = new material();
		
		require_once(ROOT_PATH . 'lib/class/verifycode.class.php');
		$this->verifycode = new verifycode();
		require_once(ROOT_PATH . 'lib/class/members.class.php');
		$this->members = new members();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index()
	{
		
	}
	public function show()
	{
		#######权限#######
		$this->verify_content_prms();
		#######权限#######
		$condition = $this->get_condition();
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
		
		$vote_info = $this->mVote->show($condition, $offset, $count);
		
		if (!empty($vote_info))
		{
			foreach ($vote_info AS $v)
			{
				if ($v['end_time'])
				{
					$v['end_time_flag'] = (strtotime($v['end_time']) < TIMENOW) ? 0 : 1;
				}
				$this->addItem($v);
			}
		}
		$this->output();
	}

	public function detail()
	{
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
		if(!$this->input['id'])
		{
			$this->errorOutput('NOID');
		}
		$id = intval($this->input['id']);
		$info = $this->mVote->detail($id);
		if(!$info)
		{
			$this->errorOutput(NO_DATA);
		}
		/**
		if($this->settings['App_catalog'])
		{
			$info['catalog'] = $this->catalog('show');
		}
		**/
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 获取验证码种类
	 */
	public function get_verify_type()
	{
		$ret = $this->verifycode->get_verify_type();
		$this->addItem($ret);
		$this->output();	
	}
	
	/**
	 * 获取反馈表单列表
	 */
	public function get_feedback_list()
	{
		if($this->settings['App_feedback'])
		{
			$count = $this->input['count'] ? intval($this->input['count']) : 5;
			$pp = $this->input['page'] ? intval($this->input['page']) : 1;
			
			require_once(ROOT_PATH . 'lib/class/feedback.class.php');
			$this->feedback = new feedback();
			$page = $this->feedback->count();
			$total_page = ceil($page['total']/$count);
			$page_info = array(
				'page_num' 		=> $count,
				'current_page' 	=> $pp,
				'total_num'		=> $page['total'],
				'total_page'	=> $total_page,
			);
			$offset = ($pp-1)*$count;
			$ret = $this->feedback->show($offset,$count);
			$data['info'] = $ret;
			$data['page_info'] = $page_info;
			$this->addItem($data);
		}
		$this->output();	
	}
	
	public function count()
	{
		$condition = $this->get_condition();				
		$return = $this->mVote->count($condition);
		echo json_encode($return);
	}
	
    //已审核的
	public function news_refer_material()
	{
		$condition = '';
		if(!empty($this->input['user']))
		{
			$user = trim($this->input['user']);
			$condition .=" and user_name='" . $user . "'";
		}
		if(!empty($this->input['sort_id']))
		{
			$sort_id = intval($this->input['sort_id']);
			$condition .=" and node_id = " . $sort_id;
		}
		//判断是否传入搜索关键次，如果传入则将搜索得数距全部范围
		if(!empty($this->input['key']))
		{
			$key = trim($this->input['key']);
			$condition .=" and title like '%".$key."%'";
		}
		$condition .= ' and status = 1 and (end_time >' . TIMENOW .' or end_time = 0 )';
		
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 10;
		$data_limit = " LIMIT " . $offset . ", " . $count;
		if(!empty($key))
		{
			$data_limit = '';
		}
		
		$vote_question = $this->mVote->news_refer_show($condition,$data_limit,'');
		if (is_array($vote_question) && count($vote_question) > 0)
		{
			foreach ($vote_question AS $v)
			{	
				$v['img'] = array( 'host' => $v['pictures_info']['host'],'dir' => $v['pictures_info']['dir'],'filepath' => $v['pictures_info']['filepath'],'filename' => $v['pictures_info']['filename']);
				unset($v['pictures_info']);
				$v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
				$v['start_time'] = date('Y-m-d H:i:s', $v['start_time']);				
				$v['time'] = hg_tran_time($v['update_time']);
				$v['update_time'] = date('Y-m-d H:i:s',$v['update_time']);

				$v['filepath'] = $v['pictures_info']['filepath'] . $v['pictures_info']['filename'];
				
				$v['app_bundle'] = APP_UNIQUEID;
				$v['module_bundle'] = MOD_UNIQUEID;
				$this->addItem($v);
			}
		}
		$this->output();
	}
	
	
	public function news_refer_sort()
	{
		$info = array();
		$info[] = array('name' => '全部分类','brief' => '全部分类','fid' => 0,'is_last' => 1,'sort_id'=>0);
		
		if(!empty($this->input['fid']))
		{
			$fid = intval($this->input['fid']);
			$sql = "select * from " . DB_PREFIX."vote_node where fid = " . $fid;
			$q = $this->db->query($sql);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			if(!empty($ret))
			{
				foreach($ret as $k => $v)
				{
					$v['fid'] = $v['sort_id'] = $v['id'];
					$info[] =  $v;
				}
			}
		}
		else 
		{
			$sql = "select * from " . DB_PREFIX ."vote_node where fid = 0";
			$q = $this->db->query($sql);
			$ret = array();
			while($row = $this->db->fetch_array($q))
			{
				$ret[] = $row;
			}
			if(!empty($ret))
			{
				foreach($ret as $k => $v)
				{
					$v['fid'] = $v['sort_id'] = $v['id'];
					$info[] = $v;	
				}
			}
		}
		
		if(!empty($info))
		{
			foreach ($info as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
		
	}
	//生产参考图
	public function get_sketch_map()
	{
		if(!$this->input['id'])
		{
			return false;
		}
		$sql = "SELECT * FROM ".DB_PREFIX."vote_question  WHERE id = " . intval($this->input['id']); 
		$ret = $this->db->query_first($sql);
		$pictures_info = unserialize($ret['pictures_info']);
		$srcPath = $pictures_info['filepath'] . $pictures_info['filename'];
		//获取当前脚本名称
		$url = $_SERVER['PHP_SELF'];
		$scriptname = end(explode('/',$url));
		$scriptname = explode('.', $scriptname);
		$scriptname = $scriptname[0];
		$newName = $scriptname .'_'. $ret['id'].".png";
		$title = array();
		$title[] = hg_cutchars($ret['title'],20);
		$sql = "SELECT * FROM " . DB_PREFIX . "question_option WHERE vote_question_id = " . intval($this->input['id']) ." ORDER BY id ASC";
		$q = $this->db->query($sql);		
		while($r = $this->db->fetch_array($q))
		{
			$arr = $r['title'] .  '_' .  $r['single_total'];
			$title[] = $arr;
		}
		$title = implode(',', $title);
		$url = $this->material->create_sketch_map($srcPath,$newName,$title,'vote_question');
		$this->addItem($url);
		$this->output();				
	}
	
	public function refer_detail()
	{
		$ret = array();
		if($this->input['id'])
		{			
			$sql = "select * from " . DB_PREFIX ."vote_question where id=" . intval($this->input['id']);
			$info = $this->db->query_first($sql);
			$ret['type'] = "vote";
			$ret['title'] = $info['title'];
			$ret['brief'] = $info['describes'];
			$ret['create_time'] = date('Y-m-d H:i',$info['create_time']);
			$ret['start_time'] = $info['start_time'] ? date('Y-m-d H:i',$info['start_time']) : '';
			$ret['end_time'] = $info['end_time'] ? date('Y-m-d H:i',$info['end_time']) : '永久有效';
			$ret['pictures_info'] = unserialize($info['pictures_info']);	
			
			$ret['img'] = array('host'=>$ret['pictures_info']['host'],'dir'=>$ret['pictures_info']['dir'],'filepath' => $ret['pictures_info']['filepath'],'filename'=>$ret['pictures_info']['filename']);
			
			$sql = "select * from " . DB_PREFIX ."vote_node where id=" . $info['node_id'];
			$sort_info = $this->db->query_first($sql);
			$ret['sort_name'] = $sort_info['name'];
			unset($ret['pictures_info']);
		}
		$this->addItem($ret);
		$this->output();	
	}
	
	//已审核的总数
	public function news_refer_count()
	{
		$sql = "select count(*) as total from " . DB_PREFIX."vote_question where 1 and status = 1 and end_time >". TIMENOW;
		$total = $this->db->query_first($sql);
		$this->addItem($total);
		$this->output();
	}
		
	/**
	 * 检索条件
	 * @name get_condition
	 * @access private
	 * @author lijiaying
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	private function get_condition()
	{
		$condition = '';
		
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else
			{
				//组织以内
				if($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_group'])
				{
					$condition .= ' AND org_id IN('.$this->user['slave_org'].')';
				}
			}
			if($authnode = $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'])
			{
				$authnode_str = $authnode ? implode(',', $authnode) : '';
				if($authnode_str === '0')
				{
					$condition .= ' AND node_id IN(' . $authnode_str . ')';
				}
				if($authnode_str != -1)
				{
					$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
					$sql = 'SELECT id,childs FROM ' . DB_PREFIX . 'vote_node WHERE id IN('.$authnode_str.')';
					$query = $this->db->query($sql);
					$authnode_array = array();
					while($row = $this->db->fetch_array($query))
					{
						$authnode_array[$row['id']]= explode(',', $row['childs']);
					}
					$authnode_str = '';
					foreach ($authnode_array as $node_id=>$n)
					{
						if($node_id == intval($this->input['_id']))
						{
							$node_father_array = $n;
							if(!in_array(intval($this->input['_id']), $authnode))
							{
								continue;
							}
						}
						$authnode_str .= implode(',', $n) . ',';
					}
					$authnode_str = true ? $authnode_str . '0' : trim($authnode_str,',');
					if(!$this->input['_id'])
					{
						$condition .= ' AND node_id IN(' . $authnode_str . ')';
					}
					else
					{
						$authnode_array = explode(',', $authnode_str);
						if(!in_array($this->input['_id'], $authnode_array))
						{
							//
							if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
							{
								$this->errorOutput(NO_PRIVILEGE);
							}
							//$this->errorOutput(var_export($auth_child_node_array,1));
							$condition .= ' AND node_id IN(' . implode(',', $auth_child_node_array) . ')';
						}
					}
				}
			}
		}
		if($this->input['_id'])
		{
			$sql = "SELECT childs FROM " . DB_PREFIX . "vote_node WHERE id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  node_id in (" . $ret['childs'] . ")";
		}
		####增加权限控制 用于显示####
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%'.trim($this->input['k']).'%\'';
		}
		if (isset($this->input['uid']) && $this->input['uid'])
		{
			$condition .= ' AND user_id IN (' . trim($this->input['uid']) . ')';
		}
		
		if(isset($this->input['id']) && $this->input['id'])
		{
			$condition .= ' AND id IN('.trim($this->input['id']).')';
		}
		
		if( isset($this->input['start_time']) && $this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if(isset($this->input['end_time']) && $this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}
		
		if(isset($this->input['state']) && $this->input['state'] && urldecode($this->input['state'])!= -1)
		{
			$condition .= " AND status = '".urldecode($this->input['state'])."'";
		}
		else if(urldecode($this->input['state']) == '0')
		{
			$condition .= " AND status = 0 ";
		}
		
		if (isset($this->input['source_type']) && intval($this->input['source_type']) != -1)
		{
			$condition .= " AND source_type = " . intval($this->input['source_type']);
		}
		
		if (isset($this->input['_id']) && intval($this->input['_id']))
		{
			$condition .= " AND node_id = " . intval($this->input['_id']);
		}
		//查询权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .=" AND weight >= " . $this->input['start_weight'];
		}
		if($this->input['end_weight'] && $this->input['end_weight'] != -1)
		{
			$condition .=" AND weight <= " . $this->input['end_weight'];
		}
		if(isset($this->input['date_search']) && $this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  create_time > '".$yesterday."' AND create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > '".$today."' AND create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > '".$last_threeday."' AND create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  create_time > '".$last_sevenday."' AND create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}
	/**
	 * 取出投票数据分析
	 * $id 投票id
	 * Enter description here ...question
	 */
	public function getQestionOption()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$return['options'] = $return['other_options'] = array();
		
		$return = $this->mVote->getResult($id);
		
		$return['vote_total'] = $return['ini_num'] = 0;
		if (!empty($return['options']))
		{
			foreach ($return['options'] AS $v)
			{
				$return['vote_total'] += $v['single_total'];
				$return['ini_num'] += $v['ini_num'];
			}
		}
		if($return['is_other'])
		{
			$sql = "SELECT count(*) as other_option_num FROM " . DB_PREFIX . "question_record WHERE vote_question_id = " . $id . " AND question_option_id = ".OTHER_OPTION_ID;
			$oc = $this->db->query_first($sql);
			$return['other_vote_total'] = $oc['other_option_num'] ? $oc['other_option_num'] : count($return['other_options']);
			$return['options'][]=array(
			'id'          => OTHER_OPTION_ID,
			'title'       => OTHER_OPTION_TITLE,
			'single_total'=> intval($return['other_vote_total']),
			'ini_num'     => 0,
			'ini_single'  => intval($return['other_vote_total']),
			);
			$return['other_option_num'] = count($return['other_options']);
		}

		$return['question_total'] = $return['vote_total'] + $return['other_vote_total'];
		$return['question_total_ini'] = $return['ini_num'] + $return['question_total'];
		$return['question_total_ini'] = $return['question_total_ini'] > 0 ? $return['question_total_ini'] : 0;
		//参与人数
		$sql = "SELECT counts, app_name, app_id FROM " . DB_PREFIX . "question_count WHERE vote_question_id = " . $id;
		$q = $this->db->query($sql);
		
		$return['app_id'] = array();
		$return['preson_count'] = 0;
		while ($row = $this->db->fetch_array($q))
		{
			$return['app_id'][]=array(
			        'app_id'    => $row['app_id'],
			        'counts'    => $row['counts'],
				    'app_name'    => $row['app_name'],
			);
			$return['preson_count'] += $row['counts'];
		}
		$return['person_total'] = $return['preson_count'] + $return['ini_person'];				
		$return['person_total'] = $return['person_total'] > 0 ? $return['person_total'] : 0;
		$this->addItem($return);
		$this->output();
	}
	
	public function show_detail_result()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "question_person_info pi LEFT JOIN " . DB_PREFIX . "question_person p ON pi.id = p.pid WHERE p.vote_question_id = " . $id ;
		$re = $this->db->query_first($sql);
	    $total_num = intval($re['total']);//总的记录数
	    $count = $this->input['page_num'] ? intval($this->input['page_num']) : 20;
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$page_info['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$page_info['total_page']    = intval($total_num/$count) + 1;
		}
		$page_info['total_num'] = $total_num;//总的记录数
		$page_info['page_num'] = $count;//每页显示的个数
		$pp = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页
		if($pp > $page_info['total_page'])
		{
			$pp = $page_info['total_page'];
		}
		$page_info['current_page']  = $pp;//当前页码
		$offset = intval(($pp - 1)*$count) > 0 ? intval(($pp - 1)*$count) : 0;
		$limit = ' LIMIT '.$offset.','.$count;
		$sql = "SELECT title,id FROM " . DB_PREFIX . "question_option WHERE vote_question_id = " . $id;
		$query = $this->db->query($sql);
	    while ($r = $this->db->fetch_array($query))
		{
			$op[$r['id']] = $r['title'];
		}
		$op[OTHER_OPTION_ID] = OTHER_OPTION_TITLE;
		$sql = "SELECT p.*,pi.option_ids as option_ids FROM " . DB_PREFIX . "question_person_info pi LEFT JOIN " . DB_PREFIX . "question_person p ON pi.id = p.pid WHERE p.vote_question_id = " . $id . " ORDER BY p.create_time DESC ".$limit;
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = date('Y-m-d H:i:s',$row['create_time']);
		    $option_id = explode(',',$row['option_ids']);
		    $option_title = array();
		    if(count($option_id)>0 && is_array($option_id))
		    {
			    foreach ($option_id as $v)
			    {
				    $option_title[] = $op[$v];
			    }
		    }
		    $row['title']=implode(',',$option_title);
		    $detail[] = $row;
		}
		$return['page_info'] = $page_info;
		$return['info'] = $detail;
		$this->addItem($return);
		$this->output();
	}
	
	public function show_catalog()
	{}
	
	/***
	 * 获取积分种类
	 */
	public function get_credit_type()
	{
		$ret = $this->members->get_credit_type();
		$this->addItem($ret);
		$this->output();	
	}	
	
    public function statistics()
    {
    	$return['static'] = 1;
    	$static_date = $this->input['static_date'];
    	if($static_date)
    	{
    		$date = strtotime($static_date);
    	}
    	else 
    	{
    		$date = strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));
    	}
    	$sql = 'select status,user_id,user_name,org_id,expand_id,column_id from '.DB_PREFIX.'vote_question where create_time >= '.$date .' and create_time < '. ($date+86400);
    	$query = $this->db->query($sql);
    	while($r = $this->db->fetch_array($query))
    	{
    		$ret[$r['user_id']]['org_id'] = $r['org_id'];
    		$ret[$r['user_id']]['user_name'] = $r['user_name'];
    		$ret[$r['user_id']]['count']++;
    		$r['status'] == 1 ? $ret[$r['user_id']]['statued']++ : false;
    		$r['status'] == 2 ? $ret[$r['user_id']]['unstatued']++ : false;
    		$r['column_id'] ? $ret[$r['user_id']]['publish']++ : false;
    		$r['expand_id'] ? $ret[$r['user_id']]['published']++ : false;
    		if($r['column_id'])
    		{
    			$columns = unserialize($r['column_id']);
    			if($columns)
    			foreach ($columns as $column_id => $column_name)
    			{
	    			$ret[$r['user_id']]['column'][$column_id]['column_name'] = $column_name;
	    			$ret[$r['user_id']]['column'][$column_id]['total']++;
    				if($r['expand_id'])
	    			{
	    				$ret[$r['user_id']]['column'][$column_id]['success']++;
	    			}
    			}
    		}
     	}
     	$return['data'] = $ret;
    	$this->addItem($return);
    	$this->output();
    }
    
    /**
     * 获取全局属性（云投票使用）
     */
    public function get_vote_info()
    {
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$info = $this->mVote->get_vote($id);
		if(!$info)
		{
			$this->errorOutput(NO_DATA);
		}
		if(!$info['template_id'])
		{
			$this->errorOutput('该投票未选择模板');
		}
		$this->addItem($info);
		$this->output();
    }
    
    /**
     * 获取所有选项（云投票使用）
     * Enter description here ...
     */
    public function get_vote_options()
    {
		#######权限#######
		$this->verify_content_prms(array('_action'=>'show'));
		#######权限#######
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NOID');
		}
		$info = $this->mVote->get_vote_options($id);
		if(!$info)
		{
			$this->errorOutput(NO_DATA);
		}
		foreach ($info as $v)
		{
			$this->addItem($v);
		}
		$this->output();
    }
    
    public function get_vote_person_info()
    {
    	require_once(ROOT_PATH . 'lib/class/curl.class.php');
    	$curl = new curl($this->settings['App_feedback']['host'],$this->settings['App_feedback']['dir']);
    	$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_app_fbinfo');
		$curl->addRequestData('source_id',intval($this->input['source_id']));
		$curl->addRequestData('feedback_id',intval($this->input['feedback_id']));
		$curl->addRequestData('source_app',trim($this->input['source_app']));
		$ret = $curl->request('feedback.php');
		$ret = $ret[0];
		$this->addItem($ret);
		$this->output();
    }
    
	public function unknow()
	{
		$this->errorOutput('NO_ACTION');
	}
	
}

$out = new voteApi();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'show';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action();
?>