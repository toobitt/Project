<?php
require_once('./global.php');
require_once(CUR_CONF_PATH . 'core/message.dat.php');
define('MOD_UNIQUEID','message');//模块标识
class comment extends adminReadBase
{
	function __construct()
	{
		$this->mPrmsMethods = array(
		'manage'=>'管理',
		/*'_node'=>array(
			'name'=>'栏目',
			'node_uniqueid'=>'cloumn_node',
			),*/
		);
		
		parent::__construct();
		
		include_once(ROOT_PATH.'lib/class/publishconfig.class.php');
		$this->pub_config= new publishconfig();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index(){}
	
	public function show()
	{
		$this->verify_content_prms(array('_action'=>'manage'));
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		else
		{
			$orderby = ' ORDER BY m.order_id ' . $descasc;
		}
		
		$condition = $this->get_condition();
		$field = ' m.id,m.title,m.content_url,m.app_uniqueid,m.mod_uniqueid,m.userid,m.username,m.member_id,m.author,m.pub_time,m.ip,m.ip_info,m.state,m.content,m.useful,m.yawp,m.contentid,m.cmid,m.appname,m.order_id,m.content_title,m.last_reply,n.name as groupname ';
		$join = ' LEFT JOIN '.DB_PREFIX.'message_node n ON m.groupid = n.id ';
		$mes = new Message();
		$res = $mes->show($field,$condition,$orderby,$limit,$join);
		
		//需要会员信息
		$need_member_info = intval($this->input['need_member_info']);
		
		if (is_array($res) && count($res))
		{
			$cmid = array();
			$member_id = array();
			//获取有多少内容发布后id
			foreach ($res as $key => $val)
			{
				//抓取会员id
				if($need_member_info && $val['member_id'])
				{
					$member_id[$val['member_id']] = 1;
				}
				//获取发布id
				if($val['cmid'])
				{
					$cmid['app'][$val['cmid']] = 0;
				}
				else 
				{
					//栏目
					if($val['app_uniqueid'] == 'column' && $val['mod_uniqueid'] == 'column')
					{
						$cmid['column'][$val['contentid']] = 1;
					}
				}
			}
			//获取会员信息
			if($need_member_info && $member_id)
			{
				include_once(ROOT_PATH . 'lib/class/member.class.php');
				$member = new member();
				$mem_ids = array_keys($member_id);
				$member_id = implode(',', $mem_ids);
				$member_info = $member->getMemberByIds($member_id);
				$member_info = $member_info[0];
			}
			//查询每个发布内容的标题
			if(count($cmid))
			{
				$arr = array();
				foreach ($cmid as $key => $val)
				{
					foreach ($val as $k => $v)
					{
						if($key == 'app')
						{
							$r = $mes->get_publish_content($k);
							$v = trim($this->input['content_title']);
							$arr['app'][$k] = $v;
						}
						else if($key == 'column')
						{
							$r = $mes->get_publish_content($k,$v);
							$v = trim($this->input['content_title']);
							$arr['column'][$k] = $v;
						}
					}
				}
			}
			//整合输出内容
			foreach ($res as $k=>$v)
			{
				//添加内容标题
				if(count($arr))
				{
					if($v['cmid'])//应用
					{
						foreach ($arr['app'] as $kk => $vv)
						{
							if ($kk == $v['cmid'])
							{
								$v['content_title'] = $vv;
							}
						}
					}
					else if($v['contentid'] && $v['app_uniqueid'] == 'column')//栏目
					{
						if($arr['column'])
						{
							foreach ($arr['column'] as $kk => $vv)
							{
								if ($kk == $v['contentid'])
								{
									$v['content_title'] = $vv;
								}
							}
						}
					}
				}
				$v['status'] = $v['state'];
				//状态判断
				if($v['state'] == '1')
				{
					$v['state'] = '已审核';
				}
				else if($v['state'] == '2')
				{
					$v['state'] = '已打回';
				} 
				else if($v['state'] == '3')
				{
					$v['state'] = '屏蔽字';
				}
				else 
				{
					$v['state'] = '待审核';
				}
				
				//没有用户名用ip替代
				if($v['author'])
				{
					$v['username'] = $v['author'];
				}
				else if(!$v['username'])
				{
					$v['username'] = $v['ip'];
				}
				
				//整合会员信息
				if($need_member_info && $member_info[$v['member_id']])
				{
					$v['member_info'] = $member_info[$v['member_id']];
				}
				$this->addItem($v);
			}
		}
		$this->output();
	}
	private function get_condition()
	{
		$condition = '';
		//权限判断
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他们数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND m.userid = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND m.org_id IN ('.$this->user['slave_org'].')';
			}
			
			//节点权限判断  暂时不用（取发布库权限）
			/*$nodes = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
			if($nodes)
			{
				$con = '';
				$con = ' AND fid IN ('. implode(',', $nodes) . ')';
				$con .= ' ORDER BY id limit 0,100';
				$column_id = $this->pub_config->get_column('id',$con);
				if($column_id)
				{
					foreach ($column_id as $k => $v)
					{
						$col_id[] = $v['id'];
					}
					$col_id = implode(',', $col_id);
				}
				else 
				{
					$col_id = implode(',', $nodes);
				}
				$col_id = rtrim($col_id,',');
				
				$condition .= ' AND m.column_id IN ('.$col_id.')';
			}*/
		}
		//权限判断结束
		
		//搜索标签
        if ($this->input['searchtag_id']) {
            $searchtag = $this->searchtag_detail(intval($this->input['searchtag_id']));
            foreach ((array)$searchtag['tag_val'] as $k => $v) {
                if ( in_array( $k, array('_id') ) )
                {
                    //防止左边栏分类搜索无效
                    continue;
                }
                $this->input[$k] = $v;
            }
        }
        
		if($this->input['k'])
		{
			$condition .= ' AND m.content LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		
		if($this->input['key'])
		{
			$condition .= ' AND m.content LIKE "%'.trim(urldecode($this->input['key'])).'%"';
		}
		if($this->input['id'])
		{
			$condition .= ' AND m.id = '.intval($this->input['id']);
		}
		//其他应用读取自己应用的评论
		if($this->input['app_uniqueid'])
		{
			$condition .= ' AND m.app_uniqueid = "'.$this->input['app_uniqueid'].'"';
		}
		if($this->input['mod_uniqueid'])
		{
			$condition .= ' AND m.mod_uniqueid = "'.$this->input['mod_uniqueid'].'"';
		}
		if($this->input['content_id'])
		{
			$condition .= ' AND m.contentid = '.$this->input['content_id'];
		}
		//其他应用读取自己应用的评论结束
		if($this->input['cmid'])
		{
			$condition .= ' AND m.cmid = '.intval($this->input['cmid']);
		}
		if($this->input['message_status'] == 1)
		{
			$condition .= ' AND m.state = 0';	
		}
		else if($this->input['message_status'] == 2)
		{
			$condition .= ' AND m.state = 1';	
		}
		else if($this->input['message_status'] == 3)
		{
			$condition .= ' AND m.state = 2';	
		}
		
		//节点子集有标识可以把nid换成节点标识node_en
		if($this->input['node_en'] == 'message_sort' && $this->input['_id'])
		{
			$sql = "select childs from " . DB_PREFIX . "message_node where id = " . intval($this->input['_id']);
			$ret =  $this->db->query_first($sql);
			$condition .=" AND  m.groupid in (" . $ret['childs'] . ")";
		}
		
		if($this->input['node_en'] == 'comment_column_node' && $this->input['_id'])
		{
			$fid = $this->input['_id'];
			if(strstr($fid,"site")!==false)
			{
				$site_id = str_replace('site','',$fid);
				$condition .= ' AND site_id='.$site_id;
				/*if($this->user['group_type'] > MAX_ADMIN_TYPE)
				{
					$nodes = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'];
					if($nodes)
					{
						$con = '';
						$con = ' AND fid IN ('. implode(',', $nodes) . ')';
						$con .= ' ORDER BY id limit 0,100';
						$column_id = $this->pub_config->get_column('id',$con);
						if($column_id)
						{
							foreach ($column_id as $k => $v)
							{
								$col_id[] = $v['id'];
							}
							$col_id = implode(',', $col_id);
						}
						else 
						{
							$col_id = implode(',', $nodes);
						}
						$col_id = rtrim($col_id,',');
						
						$condition .= ' AND m.column_id IN ('.$col_id.')';
					}
				}*/
			}
			else 
			{
				$con = '';
				$con = ' AND fid='.$fid;
				$con .= ' ORDER BY id limit 0,100';
				$column_id = $this->pub_config->get_column('id',$con);
				if($column_id)
				{
					foreach ($column_id as $k => $v)
					{
						$col_id[] = $v['id'];
					}
					$col_id[] = $fid;
					
					$col_id = implode(',', $col_id);
				}
				else 
				{
					$col_id = $fid;
				}
				$condition .= ' AND m.column_id IN ('.$col_id.')'; 
			}
		}
		
		//站点id
		if($this->input['site_id'])
		{
			$condition .= ' AND m.site_id = '.intval($this->input['site_id']);
		}
		
		if($this->input['group_type'] && $this->input['group_type']!= -1)
		{
			$condition .= " AND  m.groupid = '".intval($this->input['group_type'])."'";
		}
	   
		//开始结束时间相同，默认检索当天的
		if($this->input['start_time'] && $this->input['end_time'] && $this->input['start_time'] == $this->input['end_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$end_time = $start_time + 24*3600;
			$condition .= " AND  m.pub_time >= '".$start_time."' AND m.pub_time < '".$end_time."'";
		}
		else 
		{
			if($this->input['start_time'])
			{
				$start_time = strtotime(trim(urldecode($this->input['start_time'])));
				$condition .= " AND m.pub_time >= '".$start_time."'";
			}
			
			if($this->input['end_time'])
			{
				$end_time = strtotime(trim(urldecode($this->input['end_time'])));
				$condition .= " AND m.pub_time <= '".$end_time."'";
			}
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  m.pub_time > '".$yesterday."' AND m.pub_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  m.pub_time > '".$today."' AND m.pub_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  m.pub_time > '".$last_threeday."' AND m.pub_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  m.pub_time > '".$last_sevenday."' AND m.pub_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['fid'])
		{
			$condition .= " AND m.fid = ".intval($this->input['fid']);
		}
		else 
		{
			//$condition .= " AND m.fid = 0";
		}
		if($this->input['comm_id'])
		{
			$condition .= ' AND m.id = '.intval($this->input['comm_id']);
		}
		//添加ip检索
		if($this->input['ip'])
		{
			$condition .= " AND m.ip LIKE '%" . trim($this->input['ip']) . "%'";
		}
		//添加人搜索
		if($this->input['user_name'])
		{
			$condition .= " AND m.username LIKE '%" . trim($this->input['user_name']) . "%'";
		}
		//内容链接搜索
		if($this->input['content_url'])
		{
			$condition .= " AND m.content_url LIKE '%" . trim($this->input['content_url']) . "%'";
		}
		return $condition;
	}
	public function count()
	{
		$mes = new Message();
		$condition = $this->get_condition();
		$mes->count($condition);
	}
	
	function detail()
	{	
		$this->verify_content_prms(array('_action'=>'manage'));
		
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		if(!$this->input['tablename'])
		{
			$this->errorOutput(NOTABLENAME);
		}
		
		$tableNmae = $this->input['tablename'];
		$mes = new Message();
		$res = $mes->detail($id,$tableNmae);
		if($res)
		{
			//返回内容没内容标题，查询被评论内容标题
			if(!$res['content_title'])
			{
				//发布库
				$cmid = $res['cmid'];
				if($cmid)
				{
					$mes->get_publish_content($cmid);
				}
				else 
				{
					//栏目
					if($res['app_uniqueid'] == 'column' && $res['mod_uniqueid'] == 'column')
					{
						$mes->get_publish_content($res['contentid'],1);
					}
					else 
					{
						//显示应用
						$this->input['content_title'] = $res['groupname'];
					}
				}
				$res['content_title'] = $this->input['content_title'];
			}
			
			$res['tablename'] = $tableNmae;
			$this->addItem($res);
			
		}
		$this->output();
	}
	//查询留言所有回复
	function message_reply()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput("没有发现回复留言id");
		}
		else
		{
			$condition = $this->get_condition();
		}

		$mes = new Message();
		$res = $mes->message_reply($condition);
		$this->addItem($res);
		$this->output();
	}

	public function append_group()
	{
		$sql = "SELECT id,name as groupname FROM " . DB_PREFIX . "message_node";
		
		$query = $this->db->query($sql);
		$return = array();
		while($j = $this->db->fetch_array($query))
		{
			$return[$j['id']] = $j['groupname'];
		}
		$this->addItem($return);
		$this->output();
	}
	
	public function append_comment_point()
	{
		$sql = "SELECT id,brief FROM ".DB_PREFIX."comment_point ORDER BY id DESC";
		$query = $this->db->query($sql);
		$return = array();
		while($j = $this->db->fetch_array($query))
		{
			$return[$j['id']] = $j['brief'];
		}
		$this->addItem($return);
		$this->output();
	}
	public function show_opration()
	{
		$tableName = $this->input['tablename'];
		$this->show();
	}
}
$output = new comment();
if(!method_exists($output,$_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>
