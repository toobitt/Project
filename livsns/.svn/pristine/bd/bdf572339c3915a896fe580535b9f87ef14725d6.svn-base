<?php
require('global.php');
define('MOD_UNIQUEID','notice');
class noticeApi extends adminReadBase
{
    public function __construct()
    {
        parent::__construct();
        include_once CUR_CONF_PATH.'lib/notice.class.php';
        include_once ROOT_PATH.'lib/class/members.class.php';
        include_once ROOT_PATH.'lib/class/auth.class.php';
        $this->mode = new notice();
        $this->auth = new Auth();
        $this->mPrmsMethods = array(
			'show'	=>'查看',
			'manage'=>'管理',
        		'audit' =>'审核',
		);
    }
    
    public function index(){}
    
    public function __destruct()
    {
        parent::__destruct();
    }

    public function show()
    {
    		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$conditon = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,send_time DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($conditon,$orderby,$limit);
		if($ret)
		{
			foreach ($ret as $k=>$v)
			{
				$this->addItem($v);
			}
		}
        $this->output();
    }
    
    public function detail()
    {
    		/***************权限*****************/
    		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***********************************/
	    	$id = intval($this->input['id']);
	    	if(!$id)
	    	{
	    		return false;
	    	}
	    	$ret = $this->mode->detail($id,$condition);
	    	$this->addItem($ret);
	    	$this->output();
    }
    
    public function count()
    {
	    	$condition = $this->get_condition();
	    	$return = $this->mode->count($condition);
	    	echo json_encode($return);;
    }
   
    private function get_condition()
    {
    		$condition = '';
    		/***** 权限 *****/
		$this->verify_content_prms(array('_action'=>'show'));
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] .')';
			}
		}
		/***** 权限 *****/
        
		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND title like \'%'.trim($this->input['k']).'%\'';
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
		
		if(isset($this->input['type']) && $this->input['type'] && urldecode($this->input['type'])!= -1)
		{
			$condition .= " AND type = '".urldecode($this->input['type'])."'";
		}
		else if(urldecode($this->input['type']) == '0')
		{
			$condition .= " AND type = 0 ";
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
     //所有会员
    function show_members()
    {
	    	$this->member = new members();
	    	$member = $this->member->get_members();
	    	if($member)
	    	{
	    		$member[count($member)] = array('id'=>0,'name'=>'所有会员');
	    		sort($member);
	    	}
	    	$this->addItem($member);
	    	$this->output();
    }

    //所有管理员
    function show_auths()
    {
	    	$member = $this->auth->getAllUser();
	    	if($member)
	    	{
	    		$member[count($member)] = array('id'=>0,'name'=>'所有管理员');
	    		sort($member);
	    	}
	    	$this->addItem($member);
	    	$this->output();
    }
    
    //所有会员组
    function show_member_group()
    {
	    	$this->member = new members();
	    	$members = $this->member->get_group();
	    	if($members)
	    	{
	    		$members[count($members)] = array('id'=>0,'name'=>'所有会员');
	    		sort($members);
	    	}
	    	$this->addItem($members);
	    	$this->output();
    }
    
    //所有角色
    function show_roles()
    {
	    	$roles = $this->auth->get_role_list();
	    	if($roles)
	    	{
	    		$roles[count($roles)] = array('id'=>0,'name'=>'所有角色');
	    		sort($roles);
	    	}
	    	$this->addItem($roles);
	    	$this->output();
    }
    
    //所有部门
    function show_orgs()
    {
	    	$org = $this->auth->getUserOrg();
	    	if($org)
	    	{
	    		$org[count($org)] = array('id'=>0,'name'=>'所有部门');
	    		sort($org);
	    	}
	    	$this->addItem($org);
	    	$this->output();
    }
    
    function unknow()
    {
        $this->errorOutput("此方法不存在");
    }
}

$out = new noticeApi();
$action = $_INPUT['a'];
if(!method_exists($out,$action))
{
    $action = 'show';
}
$out->$action();

?>