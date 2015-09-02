<?php
require_once './global.php';
define('MOD_UNIQUEID','seekhelp');//模块标识
require_once CUR_CONF_PATH.'lib/seekhelp.class.php';
class seekhelp extends adminReadBase
{
	public function __construct()
	{
		$this->mPrmsMethods = array(
		'show'		=>'查看',
		'create'	=>'创建',
		'update'	=>'修改',
		'delete'	=>'删除',
		'audit'		=>'审核',
		'_node'         => array(
			'name'=>'互助分类',
			'filename'=>'seekhelp_node.php',
			'node_uniqueid'=>'seekhelp_node',
		),
		);
		parent::__construct();
		$this->sh = new ClassSeekhelp();
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
		/*********权限验证开始*********/
		if(!IS_DINGDONE_ROLE)
		{
			$this->verify_content_prms();
		}
// 		if(!$this->input['sort_id']) 
// 		{
// 			$this->addItem(array());
// 			$this->output();
// 		}
		/*********权限验证结束*********/
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count']	 ? intval($this->input['count'])  : 10;
		
		$orderby = ' ORDER BY is_top DESC,order_id DESC';
		$condition = $this->get_condition();
		
		$res = $this->sh->show($condition,$orderby,$offset,$count);
		
		if (!empty($res))
		{
			foreach ($res as $key=>$val)
			{
				if(!$val['member_name'])
				{
					$val['member_name'] = $val['user_name'];
				}
				$this->addItem($val);
			}
		}
		$this->output();
	}

    public function getSeekhelplist()
    {
        /*********权限验证开始*********/
        if(!IS_DINGDONE_ROLE)
        {
            $this->verify_content_prms();
        }
// 		if(!$this->input['sort_id'])
// 		{
// 			$this->addItem(array());
// 			$this->output();
// 		}
        /*********权限验证结束*********/
        $offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
        $count  = $this->input['count']	 ? intval($this->input['count'])  : 10;

        $orderby = ' ORDER BY is_top DESC,order_id DESC';
        $condition = $this->get_condition();

        $res = $this->sh->getSeekhelplist($condition,$orderby,$offset,$count);

        if (!empty($res))
        {
            foreach ($res as $key=>$val)
            {
                if(!$val['member_name'])
                {
                    $val['member_name'] = $val['user_name'];
                }
                $this->addItem($val);
            }
        }
        $this->output();
    }
	
	public function get_condition()
	{
		$condition = '';
		/**************权限控制开始**************/
		if(!IS_DINGDONE_ROLE)
		{
			if($this->user['group_type'] > MAX_ADMIN_TYPE && !$this->user['prms']['app_prms'][APP_UNIQUEID]['is_complete'])
			{
				$condition .= '';
				if(!$this->user['prms']['default_setting']['show_other_data'])
				{
					$condition .= ' AND sh.user_id = '.$this->user['user_id'];//不允许查看他人数据
				}
				elseif ($this->user['prms']['default_setting']['show_other_data'] == 1 && $this->user['slave_org'])
				{
					$condition .= ' AND sh.org_id IN (' . $this->user['slave_org'] .')';//查看组织内的数据
				}
				if($authnode = $this->user['prms']['app_prms'][MOD_UNIQUEID]['nodes'])
				{
					$authnode_str = '';
					$authnode_str = $authnode ? implode(',', $authnode) : '';
					if ($authnode_str === '0')
					{
						$condition .= ' AND sh.sort_id IN(' . $authnode_str . ')';
					}
					if ($authnode_str && $authnode_str!=-1)
					{
						$authnode_str = intval($this->input['_id']) ? $authnode_str .',' . $this->input['_id'] : $authnode_str;
						$sql = 'SELECT id,childs FROM '.DB_PREFIX.'sort WHERE id IN('.$authnode_str.')';
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
							$authnode_str .= implode(',', $n) .',';
						}
						//$authnode_str = in_array('0', $authnode) ? $authnode_str .'0' : trim($authnode_str,',');
						$authnode_str = $authnode_str .'0';
						if(!$this->input['_id'])
						{
							$condition .= ' AND sh.sort_id IN(' . $authnode_str . ')';
						}
						else
						{
							$authnode_array = explode(',', $authnode_str);
							if(!in_array($this->input['_id'], $authnode_array))
							{
								if(!$auth_child_node_array = array_intersect($node_father_array, $authnode_array))
								{
			
									$this->errorOutput(NO_PRIVILEGE);
								}
								$condition .= ' AND sh.sort_id IN(' . implode(',', $auth_child_node_array) . ')';
							}
						}
					}
			
				}
			}
		}

		/**************权限控制结束**************/
		if($this->input['k'])
		{
			$condition .= ' AND sh.title LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if ($this->input['id'])
		{
			$condition .= ' AND sh.id = '.intval($this->input['id']);
		}
		if ($this->input['_id'])
		{
			$sql = " SELECT childs,fid FROM ".DB_PREFIX."sort WHERE  id = '".$this->input['_id']."'";
			$arr = $this->db->query_first($sql);
			if($arr)
			{
				$condition .= ' AND sh.sort_id IN ('.$arr['childs'].')';
			}
		}
		if (isset($this->input['sort_id']))
		{
			$condition .= ' AND sh.sort_id = '.intval($this->input['sort_id']);
		}
		if ($this->input['member_id'])
		{
			$condition .= ' AND sh.member_id = '.intval($this->input['member_id']);
		}
		if (isset($this->input['status']) && $this->input['status'] != -1)
		{
			$condition .= ' AND sh.status = '.intval($this->input['status']);
		}
		if (isset($this->input['is_push']) && $this->input['is_push'] != -1)
		{
			$condition .= ' AND sh.is_push = '.intval($this->input['is_push']);
		}
		if (isset($this->input['is_reply']) && $this->input['is_reply'] != -1)
		{
			$condition .= ' AND sh.is_reply = '.intval($this->input['is_reply']);
		}
		if (isset($this->input['account_id']) && $this->input['account_id'] != -1)
		{
			$condition .= ' AND sh.account_id = '.intval($this->input['account_id']);
		}
		if ($this->input['joint'])
		{
			$sql = 'SELECT cid FROM '.DB_PREFIX.'joint WHERE member_id = '.$this->user['user_id'];
			$query = $this->db->query($sql);
			$cids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$cids[] = $row['cid'];
			}
			if (!empty($cids))
			{
				$condition .= ' AND sh.id IN ('.implode(',', $cids).')';
			}
		}
		if ($this->input['attention'])
		{
			$sql = 'SELECT cid FROM '.DB_PREFIX.'attention WHERE member_id = '.$this->user['user_id'];
			$query = $this->db->query($sql);
			$cids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$cids[] = $row['cid'];
			}
			if (!empty($cids))
			{
				$condition .= ' AND sh.id IN ('.implode(',', $cids).')';
			}
		}
		if (defined('SHOW_OTHER_DATA') && $this->user['group_type'] > MAX_ADMIN_TYPE && !SHOW_OTHER_DATA)
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'account';
			$query = $this->db->query($sql);
			$account_ids = array();
			while ($row = $this->db->fetch_array($query))
			{
				$account_ids[$row['account_id']] = $row['id'];
			}
			
			if (!empty($account_ids))
			{
				if (in_array($this->user['user_id'], array_keys($account_ids)))
				{
					$condition .= ' AND sh.account_id = '. $account_ids[$this->user['user_id']];
				}
			}
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND sh.create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND sh.create_time <= ".$end_time;
		}
		if($this->input['seekhelp_time'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['seekhelp_time']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  sh.create_time > ".$yesterday." AND sh.create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  sh.create_time > ".$today." AND sh.create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  sh.create_time > ".$last_threeday." AND sh.create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND sh.create_time > ".$last_sevenday." AND sh.create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		if($this->input['section_id'])
		{
			$section_id = intval($this->input['section_id']);
			$condition .= " AND sh.section_id=".$section_id;
		}
		return $condition;
	}
	
	public function count()
	{
		$ret = $this->sh->count($this->get_condition());
		echo json_encode($ret);
	}
	
	public function detail()
	{
		/*********权限验证开始*********/
		if(!IS_DINGDONE_ROLE)
		{
			$this->verify_content_prms(array('_action'=>'show'));
		}
		/*********权限验证结束*********/
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->sh->detail($id);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 
	 * @Description 所有机构
	 * @author Kin
	 * @date 2013-7-16 上午09:52:57
	 */
	public function organization()
	{
		$data = $this->sh->organization();
		$this->addItem($data);
		$this->output();
	}
	//输出权限，判断是否为编辑
	public function personal_auth()
	{
		$auth = array();
		if ($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$prms = $this->user['prms']['app_prms'][APP_UNIQUEID];
			$auth = array(
				'is_complete'	=> $prms['is_complete'],
				'action'		=> $prms['action'],
			);
		}
		else
		{
			
			$auth = array(
				'is_complete'	=> 1,
				);
		} 
		$this->addItem($auth);
		$this->output();
	}
}
$ouput = new seekhelp();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();
?>