<?php
define('MOD_UNIQUEID','market_member');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/market_member_mode.php');
require_once(ROOT_PATH . 'lib/class/curl.class.php');
class market_member extends adminReadBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new market_member_mode();
		/******************************权限*************************/
		if($this->user['group_type'] > MAX_ADMIN_TYPE)
		{
			$actions = (array)$this->user['prms']['app_prms']['supermarket']['action'];
			if(!in_array('manger',$actions))
			{
				$this->errorOutput('您没有权限访问此接口');
			}
		}
		/******************************权限*************************/
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
		$condition = $this->get_condition();
		$orderby = '  ORDER BY order_id DESC,id DESC ';
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
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mode->count($condition);
		echo json_encode($info);
	}
	
	public function get_condition()
	{
		$condition = '';
		if(!$this->input['market_id'])
		{
			$this->errorOutput(NOID);
		}
		$condition .= " AND market_id = '".$this->input['market_id']."'";
		
		if($this->input['k'] || trim(($this->input['k']))== '0')
		{
			$condition .= ' AND ( name  LIKE "%'.trim(($this->input['k'])).'%" OR card_number LIKE "%' .trim(($this->input['k'])). '%" OR phone_number LIKE "%' .trim(($this->input['k'])). '%" )';
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
		
		if($this->input['member_is_bind'] == 1)
		{
			$condition .= " AND member_id = 0 ";
		}
		else if($this->input['member_is_bind'] == 2)
		{
			$condition .= " AND member_id != 0 ";
		}
		
		//权重
		if($this->input['start_weight'] && $this->input['start_weight'] != -1)
		{
			$condition .= " AND weight >=" . $this->input['start_weight'];
		}
		
		if($this->input['end_weight'] && $this->input['end_weight'] != -2)
		{
			$condition .= " AND weight <= " . $this->input['end_weight'];
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
		
		return $condition;
	}
	
	public function detail()
	{
		if($this->input['id'])
		{
			$ret = $this->mode->detail($this->input['id']);
			if($ret)
			{
				//从会员中心获取会员信息
				if($ret['member_id'])
				{
					$bindIds = $this->mode->get_bind_member_id($ret['market_id'],$ret['id']);
					if($bindIds)
					{
						$centerMemberIds = implode(',',$bindIds);
						if($member_info  = $this->getMemberInfoFromMemberCenter($centerMemberIds))
						{	
							foreach ($member_info AS $k => $v)
							{
								$ret['member_info'][] = array(
									'member_id' 	=> $v['member_id'],
									'member_name' 	=> $v['member_name'],
									'mobile' 		=> $v['mobile'],
									'email'			=> $v['email'],
									'avatar'		=> hg_fetchimgurl($v['avatar'],160),
								);
							}
						}
					}
				}
				$this->addItem($ret);
				$this->output();
			}
		}
	}
	
	/************************************************扩展操作**************************************************/
	//从会员中心获取会员信息
	public function getMemberInfoFromMemberCenter($member_id = '')
	{
		if(!$this->settings['App_members'] || !$member_id)
		{
			return false;
		}
		
		$curl = new curl($this->settings['App_members']['host'],$this->settings['App_members']['dir']);
		$curl->setSubmitType('get');
		$curl->setReturnFormat('json');
		$curl->initPostData();
		$curl->addRequestData('member_id',$member_id);
		$memberInfo = $curl->request('member.php');
		return $memberInfo;
	}
	
	//获取绑定的日志
	public function get_bind_log()
	{
		$market_id = $this->input['market_id'];
		if(!$market_id)
		{
			$this->errorOutput(NOID);
		}
		$return = array();
		$pageArr = array();
		$pp    = $this->input['page'] ? intval($this->input['page']) : 1;//如果没有传第几页，默认是第一页	
		$count = $this->input['counts'] ? intval($this->input['counts']) : 20;
		$offset = intval(($pp - 1)*$count);
		$orderby = ' ORDER BY create_time DESC ';
		$limit = ' LIMIT ' . $offset . ' , ' . $count;
		$return['log'] = $this->mode->get_bind_log($market_id,$orderby,$limit);
		//算出分页的参数
        $total_num = $this->mode->get_total_log($market_id);
        //总页数
        if (intval($total_num % $count) == 0)
        {
            $pageArr['total_page'] = intval($total_num / $count);
        }
        else
        {
            $pageArr['total_page'] = intval($total_num / $count) + 1;
        }
        $pageArr['total_num']    = $total_num; //总的记录数
        $pageArr['page_num']     = $count; //每页显示的个数
        $pageArr['current_page'] = $pp; //当前页码
        $return['page'] = $pageArr;
		
        $this->addItem($return);
        $this->output();
	}
}

$out = new market_member();
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