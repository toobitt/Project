<?php
define('MOD_UNIQUEID','dingdone_user');
require_once('global.php');
require_once(ROOT_PATH . 'lib/class/applant.class.php');
require_once(CUR_CONF_PATH . 'lib/dingdone_user_mode.php');
class dingdone_user extends adminReadBase
{
	private $mode;
	private $applant;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new dingdone_user_mode();
		$this->applant = new applant();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function index(){}

	public function show()
	{
		$offset = $this->input['offset'] ? $this->input['offset'] : 0;			
		$count = $this->input['count'] ? intval($this->input['count']) : 20;
		$condition = $this->get_condition('user_name');
		$orderby = '  ORDER BY create_time DESC,id DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$ret = $this->mode->show($condition,$orderby,$limit);
		
		if(!empty($ret))
		{
			foreach($ret as $k => $v)
			{
				$this->addItem($v);
			}
			$this->output();
		}
		
		//ret不存在 需要将K当做手机号码 或者邮箱
		if(!$ret)
		{
			//邮箱
			$email_condition = $this->get_condition('email');
			$email_ret = $this->mode->show($email_condition,$orderby,$limit);
			if(!empty($email_ret))
			{
				foreach($email_ret as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}
		}
			
		if(!$email_ret && !$ret)
		{
			$phone_condition = $this->get_condition('telephone');
			$phone_ret = $this->mode->show($phone_condition,$orderby,$limit);
			if(!empty($phone_ret))
			{
				foreach($phone_ret as $k => $v)
				{
					$this->addItem($v);
				}
				$this->output();
			}
		}
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition($key_type = '')
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= " AND id IN (".($this->input['id']).")";
		}
		
		if($this->input['push_status'])
		{
			$condition .= " AND push_status = '" .$this->input['push_status']. "' ";
		}	
		
		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}
		
		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
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

		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND  '.$key_type.'  LIKE "%'.trim(($this->input['k'])).'%"';
		}
		
		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = array();
			$userInfo = $this->mode->detail($this->input['id']);
			$permission = $this->mode->getPermissionById($this->input['id']);
			if($userInfo)
			{
				$ret['user_info'] = $userInfo;
				$ret['app_info'] = $this->applant->getAppinfoByuid($userInfo['id']);
				$ret['push_accounts'] = $this->applant->getPushAccounts();				
				$ret['permissionInfo'] = $permission;
				//获取扩展字段相关设置
				$catalog_num = $this->applant->getCatalogNumLimit($userInfo['id']);
				if($catalog_num)
				{
					$ret['app_info']['catalog_num'] = array(
						'max_list_ui_num'	=> $catalog_num['list_ui_num'],
						'max_content_ui_num'	=> $catalog_num['content_ui_num'],
						'max_price_num'	=> $catalog_num['price_num'],
						'max_time_num'	=> $catalog_num['time_num'],
						'max_radio_num'	=> $catalog_num['radio_num'],
						'max_main_num'	=> $catalog_num['main_num'],
						'max_minor_num'	=> $catalog_num['minor_num'],
					);
				}
				else
				{
					$ret['app_info']['catalog_num'] = array(
							'max_list_ui_num'	=> $this->settings['catalog_num_limit']['list_ui_num'],
							'max_content_ui_num'	=> $this->settings['catalog_num_limit']['content_ui_num'],
							'max_price_num'	=> $this->settings['catalog_num_limit']['price_num'],
							'max_time_num'	=> $this->settings['catalog_num_limit']['time_num'],
							'max_radio_num'	=> $this->settings['catalog_num_limit']['radio_num'],
							'max_main_num'	=> $this->settings['catalog_num_limit']['main_num'],
							'max_minor_num'	=> $this->settings['catalog_num_limit']['minor_num'],
					);
				}
			}
			$this->addItem($ret);
			$this->output();
		}
	}
	
}

$out = new dingdone_user();
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