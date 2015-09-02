<?php
define('MOD_UNIQUEID','workload');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/workload_mode.php');
include_once ROOT_PATH.'lib/class/auth.class.php';
include_once ROOT_PATH.'lib/class/curl.class.php';
class workload extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		//权限设置数据
		$this->mPrmsMethods = array(
		'show'	=>'查看',
		'_node'=>array(
			'name'=>'部门分类',
			'filename'=>'workload_node.php',
			'node_uniqueid'=>'workload_node',
			),
		);
		parent::__construct();
		$this->mode = new workload_mode();
		$this->auth = new auth();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}
	
	//获取所有部门的总数据一段时间内的数据
	public function getTotal() 
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		$date = $this->mode->setDate();
		if(!$date)
		{
			$this->errorOutput('时间错误');
		}
		$group = $date['date_type'];
		$orderby = '  ORDER BY date DESC,count desc';
		$ret = $this->mode->get_total($condition,$orderby,$group);
		if($date['date_time'])
		{
			foreach ($date['date_time'] as $v)
			{
				$data['count'] = $ret[$v]['count'] ? intval($ret[$v]['count']): 0;
				$data['statued'] = $ret[$v]['statued'] ? intval($ret[$v]['statued']): 0;
				$data['date'] =  $group == 'date' ? date('m.d',$v) : $v.$this->settings['date_zh'][$group];
				$this->addItem($data);
			}
		}
		$this->output();
	}
	
	//获取所有部门的总数据一段时间内的各应用发稿量的百分比
	public function getTotalPre() 
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		$group = 'app_uniquedid';
		$orderby = ' ORDER BY count DESC';
		$apps = $this->mode->get_appname();
		$ret = $this->mode->get_total($condition,$orderby,$group);
		if($apps)
		{
			foreach ($apps as $k=>$v)
			{
				$ret[$k]['app_uniquedid'] = $k;
				$ret[$k]['name'] = $v['name'];
				$ret[$k]['color'] = $v['color'];
				$ret[$k]['count'] = $ret[$k]['count'] ? $ret[$k]['count'] : 0;
				$ret[$k]['statued'] = $ret[$k]['statued'] ? $ret[$k]['statued'] : 0;
				$re[] = $ret[$k];
				$total += $ret[$k]['count'];
			}
		}
		$data['total'] = $total ? $total : 0;
		$data['app_count'] = $re;
		$this->addItem($data);
		$this->output();
	}
	
	//部门最近一段时间的发稿量
	public function getOrgTotal() 
	{
		$type = $this->input['type'];
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;
		$count = $this->input['page_count'] ? intval($this->input['page_count']) : 9;
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		
		if(empty($type) || intval($type) == 1)
		{
			$data['person'] = array();
			$limit = ' LIMIT '.$offset.' , '.$count;
			$person = $this->mode->get_person($condition,$limit);
			if($person)
			{
				$order = 0;
				foreach ($person as $o=>$v)
				{
					$order++;
					$v['order'] = $order + $offset;
					$data['person'][] = $v;
				}
			}
		}
		if(empty($type) || intval($type) == 2)
		{
			$data['org'] = array();
			$node = $this->getPrmNodeId($this->input['org_id'],$count);
			if($node['child'])
			{
				$condition .= ' AND org_id in('.$node['child'].')';
			}
			$orderby = '  ORDER BY count DESC';
			//获取需要显示的部门
			$ret = $this->mode->get_org_count($condition,$orderby,$node);
			if($ret)
			{
				foreach ($ret as $v)
				{
					$data['org'][] = $v;
				}
			}
		}
		/*		
		$page_info = array(
			'total_num'	=> $node['total'], 
			'page_num'	=> $count, 
			'offset'	=> $offset + $count, 
			'is_next_page'	=> $offset + $count >= $data['total'] ? 0 : 1,
		);
		$data['page_info'] = $page_info;
		*/
		$this->addItem($data);
		$this->output();
	}
	
	public function getPerson()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0 ;
		$count = $this->input['count'] ? intval($this->input['count']) : 10 ;
		$limit = ' LIMIT '. $offset .' , '.$count;
		$orderby = '  ORDER BY count DESC ,user_id ASC';
		$ret = $this->mode->get_person($condition,$orderby,$limit);
		if(!$ret)
		{
			$this->errorOutput(NODATA);
		}
		foreach ($ret as $v)
		{
			$this->addItem($v);
		}
		$this->output();
	}
	
	public function getOrgAppPre()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		$node = $this->getPrmNodeId(trim($this->input['org_id']),5);
		#####
		$condition = $this->get_condition();
		if($node['child'])
		{
			$condition .= ' AND org_id in('.$node['child'].')';
		}
		$orderby = '  ORDER BY org_id DESC';
		$ret = $this->mode->get_orgs_precount($condition,$orderby,$node);
		$this->addItem($ret);
		$this->output();
	}
	
	public function getOneOrg()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$id = intval($this->input['org_id']);
		if(!$id)
		{
			$this->errorOutput(NO_ID);
		}
		$node = $this->getOneNodePrm($id);
		$condition = $this->get_detail_condition();
		$ret = $this->mode->get_one_org($id,$condition,$node);
		$date_search = intval($this->input['date_search']);
		if($date_search != 5)
		{
			if(!$date_search)
			{
				$date_title = '最近7天';
			}
			else 
			{
				$date_title = $this->settings['date_search'][$date_search];
			}
		}
		$ret['info']['date_title'] = $date_title ? $date_title : '';
		$this->addItem($ret);
		$this->output();
	}
	
	//部门最近一段时间的发稿量
	public function getOneOrgTotal() 
	{
		$id = intval($this->input['org_id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		$node = $this->getOneNodePrm($id);
		#####
		$condition = $this->get_condition();
		if($node['child'])
		{
			$condition .= ' AND org_id in('.$node['child'].')';
		}
		//获取需要显示的部门
		$ret = $this->mode->get_one_org_count($id,$condition,$orderby,$node);
		$this->addItem($ret);
		$this->output();
	}
	
	//获取个人详细数据
	public function detail()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		if(!$this->input['user_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->detail($this->input['user_id'],$condition);
		if(!$ret)
		{
			$this->errorOutput(NODATA);
		}
		$date_search = intval($this->input['date_search']);
		if($date_search != 5)
		{
			if(!$date_search)
			{
				$date_title = '最近7天';
			}
			else 
			{
				$date_title = $this->settings['date_search'][$date_search];
			}
		}
		$ret['info']['date_title'] = $date_title ? $date_title : '';
		$this->addItem($ret);
		$this->output();
	}
	
	//获取个人总数
	public function getPersonTotal()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		if(!$this->input['user_id'])
		{
			$this->errorOutput(NOID);
		}
		$condition = $this->get_condition();
		$ret = $this->mode->get_person_total($this->input['user_id'],$condition);
		if(!$ret)
		{
			$this->errorOutput('该用户不存在');
		}
		$this->addItem($ret);
		$this->output();
	}
		
	public function get_detail_condition()
	{
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND date >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			//$end_time = strtotime(trim(($this->input['end_time'])));
			$end_time = strtotime(trim(($this->input['end_time']))) + 86400 -1;
			$end_time = $end_time ? $end_time : TIMENOW;
			$condition .= " AND date <= '".$end_time."'";
		}
		
		if($this->input['date_search'])
		{
			$week = date('W',TIMENOW);
			$month = date('m',TIMENOW);
			$year = date('Y',TIMENOW);
			$quarter = ceil($month/3);
			$quarter_month = array(1 => '1,2,3' ,2 => '4,5,6' ,3 => '7,8,9' ,4 => '10,11,12');
			switch(intval($this->input['date_search']))
			{
				case 1://本周
					$condition .= " AND  week = ".$week. " AND  year = ".$year;
					break;
				case 2://本月
					$condition .= " AND  month = ".$month . " AND  year = ".$year;
					break;
				case 3://本季度
					$condition .= " AND  month in( ".$quarter_month[$quarter] . ") AND  year = ".$year;
					break;
				case 4://本年
					$condition .= " AND  year = ".$year;
					break;
				default:		
					break;
			}
		}
		else 
		{
			$today = strtotime(date('Y-m-d',TIMENOW));
			$sevendays = strtotime(date('Y-m-d',TIMENOW)) - 7*86400;
			$condition .= " AND date >= ". $sevendays ." AND  date < ".$today;
		}
				
		if($this->input['app'])
		{
			$app = str_replace(',','","',trim($this->input['app']));
			$condition .= ' AND  app_uniquedid in( "'.$app.'")';
		}
		
		return $condition;
		
	}

	public function show()
	{
		$org_id = intval($this->input['org_id']);
		if(!$org_id)
		{
			$this->errorOutput(NOID);
		}
		#####
		$this->verify_content_prms();
		$node = $this->getOneNodePrm($org_id);
		#####
		$condition = $this->get_condition();
		if($node['child'])
		{
			$condition .= ' AND org_id in('.$node['child'].')';
		}
		$order = $this->input['order'] ? trim($this->input['order']) : 'count';
		$order_adsc = $this->input['adsc'] ? trim($this->input['adsc']) : 'DESC';
		$orderby = '  ORDER BY '.$order.' '.$order_adsc;
		$count = $this->input['count'] ? intval($this->input['count']) : 13;
		$pp = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$offset = intval(($pp - 1)*$count);			
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		if($ret['data'])
		{
			foreach ($ret['data'] as $k=>$v)
			{
				$v['top'] = $offset+$k+1;
				$retkey[] = $v;
			}
		}
		$page_info = array(
			'total_page'	=> ceil($ret['total']/$count),
			'total_num' 	=> $ret['total'],
			'page_num'		=> $count,
			'current_page'	=> $pp,
		);
		$data['data'] = $retkey;
		$data['page_info'] = $page_info;
		$this->addItem($data);
		$this->output();
	}

	public function count()
	{
	}
	
	public function get_condition()
	{
		$condition = '';
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND date >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time']))) + 86400 -1;
			$end_time = $end_time ? $end_time : TIMENOW;
			$condition .= " AND date <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$week = date('W',TIMENOW);
			$month = date('m',TIMENOW);
			$year = date('Y',TIMENOW);
			if(TIMENOW >= strtotime(date('Y-12-26')) || TIMENOW < strtotime(date('Y-01-07')))
			{
				if($month == '01' && $week > '05' )
				{
					$week = 1;
				}
				if($week == '01' && $month === '12')
				{
					$week = date('W',strtotime('-1 week'))+1;
				}
			}
			$quarter = ceil($month/3);
			$quarter_month = array(1 => '1,2,3' ,2 => '4,5,6' ,3 => '7,8,9' ,4 => '10,11,12');
			switch(intval($this->input['date_search']))
			{
				case 1://本周
					$condition .= " AND  week = ".$week. " AND  year = ".$year;
					break;
				case 2://本月
					$condition .= " AND  month = ".$month . " AND  year = ".$year;
					break;
				case 3://本季度
					$condition .= " AND  month in( ".$quarter_month[$quarter] . ") AND  year = ".$year;
					break;
				case 4://本年
					$condition .= " AND  year = ".$year;
					break;
				default:		
					break;
			}
		}
		else 
		{
			$today = strtotime(date('Y-m-d',TIMENOW));
			$sevendays = strtotime(date('Y-m-d',TIMENOW)) - 7*86400;
			$condition .= " AND date >= ". $sevendays ." AND  date < ".$today;
		}
		
		if($this->input['app'])
		{
			$app = str_replace(',','","',trim($this->input['app']));
			$condition .= ' AND  app_uniquedid in( "'.$app.'")';
		}
		
		return $condition;
	}

	//获取查询条件
	public function get_org_condition()
	{
		if($this->input['_id'])
		{
			$condition['id'] = $this->input['_id'];
		}
		
		if($this->input['k'])
		{
			$condition['k'] = $this->input['k'];
		}
		return $condition;
	}

	public function get_log_operation()
	{
		#####
		$this->verify_content_prms(array('_action'=>'show'));
		#####
		$condition = $this->get_condition();
		if($this->input['user_id'])
		{
			$ret = $this->mode->get_operation($this->input['user_id'],$condition);
			if($ret)
			{
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	public function get_access()
	{
		if(!$this->settings['App_publishcontent'])
		{
			$this->errorOutput('发布库尚未安装');
		}
		$data = array(
       		'create_user'	=> $this->input['user_name'],
        	'start_createtime'	=> $this->input['start_createtime'],
        	'end_createtime'	=> $this->input['end_createtime'],
	    );
		$curl = new curl($this->settings['App_publishcontent']['host'],$this->settings['App_publishcontent']['dir']);
		$curl->setSubmitType('post');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('a','get_click_num');
		if (is_array($data))
		{
			foreach($data as $k=>$v)
			{
				$curl->addRequestData($k,$v);
			}
		}
		$ret = $curl->request('content.php');
		$ret = $ret[0];
		if(!$ret) //如果没有新接口，就取原来的 get_content 接口
		{
			include_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
	        $this->publishcontent = new publishcontent();
	        $content = $this->publishcontent->get_content($data);
	        if($content)
	        {
	        	$start_time = $data['start_createtime'] ? strtotime($data['start_createtime']) : 0;
	        	$end_time = $data['end_createtime'] ? strtotime($data['end_createtime']) : 0;
	        	foreach ($content as $v)
		        {
		        	if(!$start_time || $start_time <= strtotime($v['create_time']))
		        	{
		        		if(!$end_time || $end_time >= strtotime($v['create_time']))
		        		{
		        			$click_num += $v['click_num'];
			        		$comment_num += $v['comment_num'];
			        		$share_num += $v['share_num'];
		        		}
			        	if($v['click_num'] || $v['share_num'] || $v['comment_num'])
			        	{
				        	$num[$v['column_id']]['click_num'] += intval($v['click_num']);
				        	$num[$v['column_id']]['share_num'] += intval($v['share_num']);
				        	$num[$v['column_id']]['comment_num'] += intval($v['comment_num']);
				        	$num[$v['column_id']]['column_name'] = $v['column_name'];
			        	}
		        	}
		        }
	        }
	        $ret['total'] = array(
	        	'click_num' => $click_num ? $click_num : 0,
	        	'share_num' => $share_num ? $share_num : 0,
	        	'comment_num' => $comment_num ? $comment_num : 0,
	        );
	        $ret['column'] =$num;
		}
        $this->addItem($ret);
        $this->output();
	}
	
	public function publishcontent()
	{
		require_once(ROOT_PATH . 'lib/class/publishcontent.class.php');
		$this->puscont = new publishcontent();
		$pp     = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count  = $this->input['count'] ? intval($this->input['count']) : 13;
		$offset = intval(($pp - 1)*$count);			
		$data = array(
			'offset'	  		=> $offset,
			'count'		  		=> $count,
			'need_count'		=> '1',
		);
		
		$data['create_user'] = $this->input['user_name'];
		$date_search = intval($this->input['date_search']);
		//$data['date_search'] = $this->input['date_search'] ? $this->input['date_search'] : 0;
		switch($date_search )
		{
			case 1:
				$start = date('Y-m-d', TIMENOW-86400*date('w')+(date('w')>0?86400:-6*86400));
				$data['starttime'] = $start;
				$data['endtime'] = date('Y-m-d');
				break;
			case 2:
				$data['starttime'] = date('Y-m-01');
				$data['endtime'] = date('Y-m-d');
				break;
			case 3:
				$season = ceil((date('m'))/3);//当月是第几季度
				$data['starttime'] = date('Y-m-d', mktime(0, 0, 0,$season*3-3+1,1,date('Y')));
				$data['endtime'] = date('Y-m-d');
				break;
			case 4:
				$data['starttime'] = date('Y-01-01');
				$data['endtime'] = date('Y-m-d');
				break;
			default:
				$data['starttime'] = $this->input['start_time'] ? $this->input['start_time'] : date('Y-01-01');
				$data['endtime'] = $this->input['end_time'] ? $this->input['end_time'] : date('Y-m-d');
				break;
		}
		$data['bundle_id'] = $this->input['app'];
		$re = $this->puscont->get_content_list($data);
		$re = $re[0];
		$this->publishcontent = new publishcontent();
		$pubtype = $this->publishcontent->get_pub_content_type();
		if(is_array($pubtype))
		{
			foreach($pubtype as $k => $v)
			{
				$bundles[$v['bundle']] = $v['name'];
			}
		}
		$total_num =$re['total'];//总的记录数
		if($total_num)
		{
			foreach ($re['data'] as $k=>$v)
			{
				$re['data'][$k]['apptype'] = $bundles[$v['bundle_id']] ? $bundles[$v['bundle_id']] : '其他';
				$click_name += $v['click_num'];
				$comment_num += $v['comment_num'];
			}
		}
		//总页数
		if(intval($total_num%$count) == 0)
		{
			$return['total_page']    = intval($total_num/$count);
		}
		else 
		{
			$return['total_page']    = intval($total_num/$count) + 1;
		}
		$return['total_num'] = $total_num;//总的记录数
		$return['page_num'] = $count;//每页显示的个数
		$return['current_page']  = $pp;//当前页码
		
		$retu['data'] = $re['data'] ? $re['data'] : array();
		$retu['page_info'] = $return;
		$retu['apptype'] = $bundles;
		$retu['click_num'] = $click_name ? $click_name : 0;
		$retu['comment_num'] = $comment_num ? $comment_num : 0;
		$this->addItem($retu);
		$this->output();
	}
	
	//节点权限控制
	public function getPrmNodeId($org_id = 0,$count = 10)
	{
		$fid = $this->input['fid'];
		$node_id = $org_id ? $org_id : $this->input['org_id'];//请求查询的部门id
		$node_id_arr = $node_id ? explode(',',$node_id) : array();
		
		$user_node_id = $this->user['org_id'];//用户的部门id
		$prm_parent_node_id_arr =  $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];//具有查看权限的部门父id
		$prm_parent_node_id = $prm_parent_node_id_arr ? implode(',',$prm_parent_node_id_arr) : 0;
		if($prm_parent_node_id)
		{
			$back_node_id_arr = $this->mode->get_org_by_ids($prm_parent_node_id);
			if($back_node_id_arr)
			{
				foreach ($back_node_id_arr as $v)
				{
					$prm_child_node_id[] = $v['childs'];
					$prm_node_id = implode(',',$prm_child_node_id);
				}
			}
		}
		if($prm_node_id)
		{
			$prm_node_id_arr = explode(',',$prm_node_id);
		}
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			if($node_id && $prm_node_id_arr)
			{
				foreach ($node_id_arr as $v)
				{
					if(!in_array($v,$prm_node_id_arr))
					{
						$this->errorOutput(NO_PRIVILEGE);
					}
				}
			}
		}
		
		$ids =  $node_id ? $node_id : $prm_parent_node_id;
		$ids_arr = $node_id ? $node_id_arr : $prm_parent_node_id_arr;
		$ret_back_arr = $this->mode->get_orgs($ids,$fid,$count);
		$back_arr = $ret_back_arr['data'];
		$children_node = array();
		if(!$back_arr)
		{
			return false;
		}
		foreach ($back_arr as $k => $v)
		{
			$children_org[$k] = $v['childs'];
			if($v['childs'] != $k)
			{
				$children_node = explode(',',$v['childs']);
				foreach ($children_node as $vs)
				{
					$parent_node[$vs] = $k;
				}
			}
			else 
			{
				$parent_node[$k] = $k;
			}
			$orgname[$k] = $v['name'];
		}
		$data = array(
			'child' 	=> $children_org ? implode(',',$children_org) : 0,
			'parent'	=> $parent_node ? $parent_node : 0,
			'id'		=> $orgname,
			'total'		=> $ret_back_arr['total'],
			'nchild'	=> $children_org,
		);
		return $data;
	}
	
	//单独部门权限判断
	public function getOneNodePrm($org_id)
	{
		if(!$org_id)
		{
			$this->errorOutput(NOID);
		}
		####增加权限控制 用于显示####
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$user_node_id = $this->user['user_node_id'];//获取自己所在部门的id
			$prm_parent_node_id_arr =  $this->user['prms']['app_prms'][APP_UNIQUEID]['nodes'];
			$prm_parent_node_id = $prm_parent_node_id_arr ? implode(',',$prm_parent_node_id_arr) : 0;//权限id整合
			if($prm_parent_node_id)
			{
				$back_node_id_arr = $this->mode->get_org_by_ids($prm_parent_node_id);// 获取所有权限id
				if($back_node_id_arr)
				{
					foreach ($back_node_id_arr as $v)
					{
						$prm_child_node_id[] = $v['childs'];	//组合子部门id
						$prm_node_id = implode(',',$prm_child_node_id);
					}
				}
			}
			if($prm_node_id)
			{
				$prm_node_id_arr = explode(',',$prm_node_id);
			}
			if($prm_node_id_arr && !$org_id && !in_array($user_node_id,$prm_node_id_arr)) 
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
			if($prm_node_id_arr && !in_array($org_id,$prm_node_id_arr))
			{
				$this->errorOutput(NO_PRIVILEGE);
			}
		}
		$back_arr = $this->auth->get_one_org($org_id);
		$back_arr = $back_arr[0];
		if(!$back_arr['id'])
		{
			$this->errorOutput('对不起，部门错误!');
		}
		$data = array(
			'child' 	=> $back_arr['childs'] ? $back_arr['childs'] : 0 ,
			'id'		=> array($org_id=>$back_arr['name']),
		);
		return $data;
	}
	
	public function appType()
	{
		$ret = $this->mode->get_appname();
		if($ret)
		{
			foreach ($ret as $k=>$v)
			{
				$v['app_uniq'] = $k;
				$this->addItem($v);
			}
		}
		$this->output();
	}
}

$out = new workload();
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