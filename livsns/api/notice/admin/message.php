<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :message.php
 * package  :package_name
 * Created  :2013-5-23,Writen by scala
 * 
 ******************************************************************/
 
define('MOD_UNIQUEID', 'message'); //模块标识
require ('global.php');
class messageApi extends adminReadBase {
	public function __construct() 
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/message.class.php');
		$this->obj = new message();
		
		include ROOT_PATH."lib/class/auth.class.php";
		$this->auth = new auth();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->obj->count('session',$condition);
		echo json_encode($info);
	}
	public function show() 
	{
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;					
		$data_limit = 'LIMIT ' . $offset . ' , ' . $count;		
		$session = $this->obj->get_session_all($condition . $data_limit);
		
		if($session && is_array($session))
		{
			foreach($session as $k => $v)
			{
				$this->addItem($v);
			}			
		}
		$this->output();	
	}
	
	//获取收到的信息
	public function get_rmessages_by_userid()
	{
		$user_id = $this->input['id'];
		$messages= $this->obj->get_rmessages_by_userid($user_id);
		if($messages && is_array($messages))
		{
			foreach($messages as $k => $v)
			{
				$this->addItem($v);
			}			
		}
		$this->output();
		
		
	}
	
	public function get_rmessages_num()
	{
		$userid 	= $this->input['id'];
		
		$numsinfo	= $this->obj->get_rmessages_num($userid);
		
		
		$this->addItem($numsinfo);
		$this->output();
		
	}
	

	
	
	
	/*
	 * @function:显示某次会话的详细信息
	 * 
	 */
	
	public function detail() 
	{
		$id = intval($this->input['id']);
		if($id)
		{
			$data_limit = ' AND a.id=' . $id;
		}
		else
		{
			$data_limit = ' LIMIT 1';
		}
		$session_info 			= $this->obj->get_session_detail($data_limit);
		
		
		$message_id		= $session_info['message_id'];
		
		$cond			= " and id = $message_id";
		$info 			= $this->obj->get_message_detail($cond);
		//export_var('google.txt',$info);
		
		if(!$info)
		{
			$this->errorOutput('消息不存在');
		}
			
			
		$this->addItem($info);
		$this->output();
				
	}
	
		
	/*
	 * @function   	:获取用户组织架构，如果未传参数，则获取全部的架构信息，否则未单个组织下的架构的信息
	 * @param		:$org_id
	 * @return		:output
	 */
	public function get_org()
	{
		$org_id = $this->input['org_id'];
		if(!$org_id)
		{
			$orgs = $this->auth->get_org(0);
		}
		else
		{
			$orgs = $this->auth->get_org(trim($org_id));
		}
		
		$this->addItem($orgs);
		$this->output();
	}

	
	
	/*
	 *  @function:获取某个组织架构的用户
	 */
	public function get_org_users()
	{
		$org_id = $this->input['org_id'];
		if(!$org_id)
		{
			$this->errorOutput("没有选择部门");
		}
		$users = $this->auth->getMemberByOrg($org_id);
		$this->addItem($users); 
		$this->output();
		
	}
	
	
	public function index() 
	{
		
	}
	
	private function get_condition()
	{
		if($this->input['user_id'])
		{
			$condition = " AND to_user_id = ".$this->input['user_id'];
		}
		 //查询发布的时间
        if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('Y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
				case 1://所有时间段
					break;
				case 2://昨天的数据
					$yesterday = strtotime(date('y-m-d',TIMENOW-24*3600));
					$condition .= " AND  a.create_time > '".$yesterday."' AND a.create_time < '".$today."'";
					break;
				case 3://今天的数据
					$condition .= " AND  a.create_time > '".$today."' AND a.create_time < '".$tomorrow."'";
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  a.create_time > '".$last_threeday."' AND a.create_time < '".$tomorrow."'";
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND  a.create_time > '".$last_sevenday."' AND a.create_time < '".$tomorrow."'";
					break;
				default://所有时间段
					break;
			}
		}
		
		
		
		
		//根据时间
		$condition .=" ORDER BY a.create_time  ";
		//查询排序方式(升序或降序,默认为降序)
		$condition .= $this->input['descasc'] ? $this->input['descasc'] : ' DESC ';

		
		return $condition;	
	}
	
	
	function unknow() 
	{
		$this->errorOutput("此方法不存在！");
		//echo "此方法不存在！";
	}

	public function __destruct() 
	{
		parent :: __destruct();
	}
}

$out = new messageApi();
$action = $_INPUT['a'];
if (!method_exists($out, $action))
{
	$action = 'show';
}
$out-> $action ();
?>
 

