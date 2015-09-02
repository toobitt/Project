<?php 
require_once './global.php';
require_once(ROOT_PATH . 'lib/class/share.class.php');
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(CUR_CONF_PATH. 'lib/account.class.php');
define('MOD_UNIQUEID','contribute_user');//模块标识
class contributeUser extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->share = new share();
		$this->conAccount = new contributeAccount();
		/*
		$this->mPrmsMethods= array(
								'show'=>array(
										'name' => '查看',
										'node' => false,
										),
								'create'=>array(
										'name'=>'创建',
										'node'=>false,
										),
								'update'=>array(
										'name'=>'更新',
										'node'=>false,
										),
								'delete'=>array(
										'name'=>'删除',
										'node'=>false,
										),
								'audit'=>array(
										'name'=>'审核',
										'node'=>false,
										),
								'back'=>array(
										'name'=>'打回',
										'node'=>false,
										),
								'sort'=>array(
										'name'=>'排序',
										'node'=>false,
										),
								);
		*/
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
		/**************权限控制开始**************/
		//$this->verify_content_prms();
		/**************权限控制结束**************/
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$orderby = ' ORDER BY order_id  DESC';
		$condition = $this->get_condition();
		$data = $this->conAccount->show($condition,$orderby,$offset,$count);
		$this->addItem($data);
		$this->output();
	}
	
	
	public function count()
	{
		$ret = $this->conAccount->count($this->get_condition());
		echo json_encode($ret);
	}
	
	
	function get_condition()
	{
		$condition = '';
		/*
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			//不允许查看他人数据
			if(!$this->user['prms']['default_setting']['show_other_data'])
			{
				$condition .= ' AND user_id = '.$this->user['user_id'];
			}
			else if($this->user['prms']['default_setting']['show_other_data'] == 1)//查看组织内
			{
				$condition .= ' AND org_id IN (' . $this->user['slave_org'] . ')';
			}
		}
		*/
		if($this->input['k'])
		{
			$condition .= ' AND nickname LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= ".$start_time;
		}
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= ".$end_time;
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
					$condition .= " AND  create_time > ".$yesterday." AND create_time < ".$today;
					break;
				case 3://今天的数据
					$condition .= " AND  create_time > ".$today." AND create_time < ".$tomorrow;
					break;
				case 4://最近3天
					$last_threeday = strtotime(date('y-m-d',TIMENOW-2*24*3600));
					$condition .= " AND  create_time > ".$last_threeday." AND create_time < ".$tomorrow;
					break;
				case 5://最近7天
					$last_sevenday = strtotime(date('y-m-d',TIMENOW-6*24*3600));
					$condition .= " AND create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}
		return $condition;
	}
	
	public function detail()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		$data = $this->conAccount->detail($id);
		$sort = $this->conAccount->sort();
		$arr = array(
			'data'=>$data,
			'sort'=>$sort,
		);
		$this->addItem($arr);
		$this->output();	
	}
	
	
	public function get_plat_name()
	{
		$plat = $this->share->get_plat();
		$sort = $this->conAccount->sort();
		$data = array(
			'plat'=>$plat,
			'sort'=>$sort
		);
		$this->addItem($data);
		$this->output();
	}
	
	/**
	 * 请求接入平台token
	 * Enter description here ...
	 */
	public function get_auth()
	{
		/**************权限控制开始**************/
		//$this->verify_content_prms(array('_action'=>'create'));
		/**************权限控制结束**************/
		$type = intval($this->input['type']);
		$platid = intval($this->input['platid']);
		$con_sort = intval($this->input['con_sort']);
		$plat = $this->share->oauthlogin($platid);
		$plat = $plat[0];
		$plat['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' .$plat['access_plat_token']; 
		$refer =  'http://'.$_SERVER["HTTP_HOST"].'/livworkbench/run.php?mid='.intval($this->input['kid']).'&a=get_user&access_plat_token='.$plat['access_plat_token'].'&platid='.$platid.'&con_sort='.$con_sort.'&infrm=1';
		$plat['url'] = $plat['url'].'&refer_url='.urlencode($refer);
		$this->addItem($plat);
		$this->output();
	}
	
	
	public function get_user()
	{
		$uid = $this->input['id'];
		$platid = $this->input['platid'];
		$plat_token = $this->input['access_plat_token'];
		$appid = $this->input['appid'];
		$con_sort = intval($this->input['con_sort']);
		//获取用户信息
		$ret = $this->share->get_user('','',$plat_token);
		$user = $ret[0];
		if (is_array($user) && !empty($user) && !$user['error'])
		{	
			
				//获取平台
			$res = $this->share->get_plat($plat_token);
			$plat = array();
			if (!empty($res))
			{
				foreach ($res as $key=>$val)
				{
					if ($val['id']==$platid)
					{
						$plat =$val;
					}
				}	
				}else {
					$this->errorOutput("获取平台信息失败");
				}
				if (empty($plat))
				{
					$this->errorOutput('获取平台信息失败');
				}
				if ($uid)
				{					
					//此时是重新授权
					//查询上次数据
					$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$uid;
					$last = $this->db->query_first($sql);

					$sql = ' UPDATE '.DB_PREFIX.'user_token SET can_access = '.$plat['can_access'].',expired_time ='.$plat['expired_time'].' WHERE id ='.$uid;
					$this->db->query($sql);
					$affected_rows = false;
					if ($this->db->affected_rows($query))
					{
						$affected_rows = true;
					}
					if ($affected_rows)
					{
						$additionalData = array(
							'update_time'=>TIMENOW,
							'update_org_id'=>$this->user['org_id'],
							'update_user_id'=>$this->user['user_id'],
							'update_user_name'=>$this->user['user_name'],
							'update_ip'=>$this->user['ip'],
						);
						$sql = 'UPDATE '.DB_PREFIX.'user_token SET ';
						foreach ($additionalData as $key=>$val)
						{
							$sql .= $key.'="'.$val.'",';
						}
						$sql = rtrim($sql,',');
						$sql .= ' WHERE id = '.$id; 
						$query = $this->db->query($sql);
						$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$uid;
						$new = $this->db->query_first($sql);
						//添加日志
						$this->addLogs('报料帐户重新授权', $last, $new, $last['name'], $uid , '');		
					}
					$this->show();
				}else {
				//信息准备入库
				$data = array(
					'appid'			=> $appid,
					'name'			=> $user['name'],
					'nickname'		=> $user['screen_name'],
					'avatar'		=> $user['avatar'],
					'plat_id'		=> $plat['id'],
					'plat_name'		=> $plat['name'],
					'plat_token'	=> $plat_token,
					'since_id'		=> 0,
					'since_time'	=> TIMENOW,
					'type'			=> $plat['type'],
					'type_name'		=> $plat['type_name'],
					'can_access'	=> $plat['can_access'],
					'expired_time'  => $plat['expired_time'],
					'user_infor'	=> addslashes(serialize($user)),
					'audit'			=> 0,
					'con_sort'		=> $con_sort,
					'create_time'	=> TIMENOW,
					'update_time'	=> TIMENOW,
					'org_id'		=> $this->user['org_id'],
					'user_id'		=> $this->user['user_id'],
					'user_name'		=> $this->user['user_name'],
					'ip'			=> $this->user['ip'],
				);
				$id = $this->conAccount->storedIntoDB($data, 'user_token',1);
				$sql = ' UPDATE '.DB_PREFIX.'user_token SET order_id = '.$id.' WHERE id ='.$id;
				$this->db->query($sql);
				//添加日志
				$sql = 'SELECT * FROM '.DB_PREFIX.'user_token WHERE id = '.$id;
				$res = $this->db->query_first($sql);
				$this->addLogs('添加报料帐户', '', $res, $res['name'], $id, '');
			}
		}else {
			$this->errorOutput('获取用户信息失败');
		}
		$this->show();
		//$this->addItem($id);
		//$this->output();		
	}
	
	
	function reset_auth()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->errorOutput(NOID);
		}
		//查询过期用户信息
		$ret = $this->conAccount->get_userinfo_by_id($id);
		if ($ret)
		{
			$platinfo = $this->share->oauthlogin($ret['plat_id'],$ret['plat_token']);
			$plat = $platinfo[0];
			$plat['url'] = $plat['sync_third_auth'] . '?oauth_url=' . $plat['oauth_url'] . '&access_plat_token=' .$plat['access_plat_token']; 
			$refer =  'http://'.$_SERVER["HTTP_HOST"].'/livworkbench/run.php?mid='.intval($this->input['kid']).'&a=get_user&access_plat_token='.$ret['plat_token'].'&platid='.$ret['plat_id'].'&con_sort='.$ret['con_sort'].'&id='.$ret['id'].'&infrm=1';
			$plat['url'] = $plat['url'].'&refer_url='.urlencode($refer);
			$this->addItem($plat);
		}
		$this->output();
	}
	
}
$output = new contributeUser();
if(!method_exists($output, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$output->$action();
?>