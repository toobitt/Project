<?php
/***************************************************************************
 * HOGE M2O
 *
 * @package     DingDone M2O API
 * @author      RDC3 - Zhoujiafei
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-8-15
 * @encoding    UTF-8
 * @description 后台推送申请认证查询接口
 **************************************************************************/
define('MOD_UNIQUEID','operate_statistics');
require_once('global.php');
require_once(CUR_CONF_PATH . 'lib/operate_statistics.class.php');
require_once(CUR_CONF_PATH . 'lib/company.class.php');
require_once(CUR_CONF_PATH . 'lib/developer_auth_mode.php');
require_once(CUR_CONF_PATH . 'lib/business_auth_mode.php');
require_once(CUR_CONF_PATH . 'lib/push_msg_mode.php');
require_once(CUR_CONF_PATH . 'lib/app.class.php');
require_once(CUR_CONF_PATH . 'lib/dingdonestatistics.class.php');
require_once(CUR_CONF_PATH."lib/member.class.php");
class operate_statistics extends adminReadBase
{
    private $mode;
    private $company;
    private $api;
    private $statistics;
    private $member;
    private $developer_mode;
    private $bus_mode;
    private $push_msg_mode;
    public function __construct()
    {
        parent::__construct();
        $this->mode = new operate_statistics_class();
        $this->company = new CompanyApi();
        $this->api = new app();
        $this->statistics = new dingdonestatistics();
        $this->member = new member();
        $this->developer_mode = new developer_auth_mode();
        $this->bus_mode = new business_auth_mode();
        $this->push_msg_mode = new push_msg_mode();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function index(){}

    /**
     * 显示数据
     *
     * @access public
     * @param  offset | count
     * @return array
     */
    public function show()
    {
//     	0、叮当生产出的APP覆盖设备用户数的存量（所有APP的唯一设备数）
    	$seven_cover_info = $this->statistics->getCoverNums('-7 days');
    	$thirty_cover_info = $this->statistics->getCoverNums('-30 days');
    	$seven_cover_count = count($seven_cover_info);
    	$thirty_cover_count = count($thirty_cover_info);
    	
//     	叮当生产出的APP覆盖会员用户数的存量（所有APP的唯一会员数）
		$seven_member_info = $this->member->getActivateMemberCount('-7 days');
		$thirty_member_info = $this->member->getActivateMemberCount('-30 days');
		$seven_member_count = count($seven_member_info);
		$thirty_member_count = count($thirty_member_info);
		
// 		叮当生产出的APP存量（至少打过一次包的APP个数）
		$seven_package_app = $this->api->getPackageApp('-7 days');
		$thirty_package_app = $this->api->getPackageApp('-30 days');
		$seven_pack_count = count($seven_pack_count);
		$thirty_pack_count = count($thirty_pack_count);
		
		
// 		叮当生产出的APP，启动次数的存量（打开app的次数）改为活跃数
		$seven_start_info = $this->statistics->getStart('-7 days');
		$thirty_start_info = $this->statistics->getStart('-30 days');
		$seven_start_count = $seven_start_info['total_liveness'];
		$thirty_start_count = $thirty_start_info['total_liveness'];
		
		
		
//     	1、总用户数
		$all_user = $this->company->getAllUserCount();
		$all_user_count = $all_user['total'];
		
//     	2、总app数
		$all_app = $this->api->count();
		$all_app_count = $all_app['total'];
		
//     	3、三个月内活跃过的app
		$info = $this->statistics->getLivenessByTime("- 3 months");
		$three_month_activate = count($info);
		
//     	4、1个月内活跃过的app
		$one_info = $this->statistics->getLivenessByTime("- 1 months");
		$one_month_activate = count($one_info);

//     	5、每日新增用户
		$day_add_user = $this->company->getDayNewUsers();
		$day_add_user_count = count($day_add_user);
			
//     	6、每日新增APP
		//时间条件
		$zeroTime = strtotime('today');
		$data = array(
				'count' => -1,
				'condition' => array(
						'start_time' => date('Y-m-d'),
				),
		);
		$day_add_app = $this->api->show($data);
		$day_add_app_count = count($day_add_app);
		
//     	7、每日活跃app
		$day_info = $this->statistics->getLivenessByTime("today");
		$day_activate_app = count($day_info);
		
//     	8、每日活跃前10
		$top_ten = $this->statistics->getTopTen();
		foreach ($top_ten as $key => &$val)
		{
			$da_con = array('id' => $val['app_id']);
			$app_info = $this->api->detail('app_info', $da_con);
			$val['app_info'] = $app_info;
		}
		$ret = array(
			'all_user_count' => $all_user_count,
			'all_app_count' => $all_app_count,
			'three_month_activate' => $three_month_activate,
			'one_month_activate' => $one_month_activate,
			'day_add_user_count' => $day_add_user_count,
			'day_add_app_count' => $day_add_app_count,
			'day_activate_app' => $day_activate_app,
// 			'top_ten' => $top_ten,
		);

		$this->addItem($ret);
		$this->output();
    }

    /**
     * 根据条件获取申请的个数
     *
     * @access public
     * @param  无
     * @return array 例如：array('total' => 20)
     */
    public function count()
    {
        $condition = $this->get_condition();
        $info = $this->mode->count($condition);
        echo json_encode($info);
    }

    /**
     * 获取查询条件
     *
     * @access public
     * @param  $this->input
     * @return String
     */
    public function get_condition()
    {
        $condition = " ";
        if($this->input['id'])
        {
            $condition .= " AND id IN (".($this->input['id']).")";
        }

        if($this->input['status'])
        {
            $condition .= " AND status = '" .$this->input['status']. "' ";
        }

        if($this->input['type'])
        {
            $condition .= " AND type = '" .$this->input['type']. "' ";
        }

        if($this->input['k'] || trim(($this->input['k']))== '0')
        {
            $condition .= ' AND  user_name  LIKE "%'.trim(($this->input['k'])).'%"';
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

        return $condition;
    }

    /**
     * 查询某一条消息的详情
     *
     * @access public
     * @param  id:消息id
     * @return array
     */
    public function detail()
    {
        if($this->input['id'])
        {
            $ret = $this->mode->getLeancloudInfoByUserid($this->input['id']);
            if($ret)
            {
                $this->addItem($ret);
                $this->output();
            }
        }
    }
    
    /**
     * 用于新运营统计
     * 总概况（全部）
     */
   	public function all_info()
   	{
   		$time_type = intval($this->input['time_type']);
   		$start_date = trim($this->input['start_date']);
   		$end_date = trim($this->input['end_date']);
   		switch($time_type)
   		{
   			case 1://首页 
	   				$start_time = 0;
	   				$end_time = 0;
   				break;
   		}
   		//获取运营统计首页信息
   		//1、用户总数
   		$all_user_count = $this->company->getAllUserCount($start_time,$end_time);
   		$user_count = $all_user_count['total'];
   		//2、开发者申请总数(待审核+未通过+已通过)   		
   		
   		$is_developer_count = $this->company->getIsDeveloperNums($start_time,$end_time);
		
   		//3、推送申请总数（已通过）
   		$push_count = $this->company->getIsPushNums();
   		 		
   		//4、app创建总数
   		$app_count = $this->api->app_count();
   		
   		//5、商业授权总数 
   		$bus_count = $this->bus_mode->count();
   		
   		//6、消息推送总数
   		$push_info_count = $this->push_msg_mode->count();

   		//7、覆盖设备总数
   		$device_info_count = $this->statistics->getCoverNums($start_time,$end_time);
   		//8、会员总数
   		$member_info_count = $this->member->getActivateMemberCount($start_time,$end_time);
   		
   		$ret = array(
   			'user_count' 		=> intval($user_count),
//    			'developer_count'	=> intval($developer_count['total']+$is_developer_count['total']),
   			'developer_count'	=> intval($is_developer_count['total']),
   			'push_count'		=> intval($push_count['total']),
   			'app_count'			=> intval($app_count['total']),
   			'business_count'	=> intval($bus_count['total']),
   			'push_msg_count'	=> intval($push_info_count['total']),
   			'device_count'		=> intval($device_info_count['total']),
   			'member_count' 		=> intval($member_info_count['total']),
   		);
   		
   		$this->addItem($ret);
   		$this->output();
   	}
    
   	/**
   	 * 根据day来得到
   	 * 昨日统计全部的数据
   	 */
   	public function getInfoByDay()
   	{   		
   		$start_time = strtotime(date('Y-m-d',strtotime("-1 day")));
   		$end_time = strtotime(date('Y-m-d'),TIMENOW);
   		

   		//1、新增用户
		$add_user_count = $this->company->getAllUserCount($start_time,$end_time);
   		
   		//2、活跃用户
   		$activate_user = $this->company->getAllActivateUsers($start_time,$end_time);
			
   		//3、开发者申请数
   		$add_developer_count = $this->company->getIsDeveloperNums($start_time,$end_time);
   		
   		//4、推送申请数
   		$add_push_count = $this->api->getAddPush($start_time,$end_time);
   		
   		//5、APP创建数
   		$add_app_count = $this->api->getAddAppinfo($start_time,$end_time);
   		
   		
   		//6、商业授权
   		$add_business_count = $this->bus_mode->count(" and open_time > ".$start_time." and open_time < ".$end_time." and status = 2");
   		
   		//7、消息推送
   		$add_push_msg_count = $this->push_msg_mode->count(" and create_time > ".$start_time." and create_time < ".$end_time);
   		
   		//8、覆盖设备数
   		$device_info_count = $this->statistics->getCoverNums($start_time,$end_time);
   		
   		//9、设备活跃数
   		$activate_device_count = $this->statistics->getAllActivateInfo($start_time,$end_time);
   		
   		//10、会员数
   		$member_info_count = $this->member->getActivateMemberCount($start_time,$end_time);

   		$ret = array(
   			'add_user_count' => intval($add_user_count['total']),
   			'activate_user_count'	 => intval($activate_user['total']),
   			'add_developer_count' => intval($add_developer_count['total']),
   			'add_push_count'	=> intval($add_push_count['total']),
   			'add_app_count'		=> intval($add_app_count['total']),
//    			'liushi_user_count' => intval($liushi_user_count['total']),
   			'add_business_count' => intval($add_business_count['total']),
   			'add_push_msg_count'	=> intval($add_push_msg_count['total']),	
   			'device_info_count'		=> intval($device_info_count['total']),
   			'activate_device_count'	=> intval($activate_device_count['total']),
   			'member_info_count'		=> intval($member_info_count['total']),
   		);
   		$this->addItem($ret);
   		$this->output();
   	}
   	
   	
   	/**
   	 * 获取今日数据
   	 */
   	public function getTodayInfo()
   	{
//    		date_default_timezone_set('Asia/Shanghai'); 

   		$index_type = intval($this->input['index_type']);
   		
   		
   		$zero_time = strtotime(date('Y-m-d',TIMENOW));
   		//1、新增用户
   		if($index_type == 1)
   		{
   			$today_user_info = $this->company->getTodayAddInfo();
   			$ret['totay_user_count'] = $today_user_info;
   		}  
   				
   		//2、开发者数
   		if($index_type == 2)
   		{
   			$today_developer_count = $this->company->getTodayAddDevelopInfo($zero_time,$zero_time+24*3600);
   			$ret['today_developer_count'] = $today_developer_count;
   		}
   		
   		
   		//3、推送数
   		if($index_type == 3)
   		{
   			$today_push_count = $this->api->getTodayPushInfo($zero_time);
   			$ret['today_push_count'] = $today_push_count;
   		}
   		
   		
   		//4、APP创建数
   		if($index_type == 4)
   		{
   			$today_app_info = $this->api->getTodayAddAppInfo($zero_time);
   			$ret['today_app_count'] = $today_app_info;
   		}
   		
   		
   		//5、商业授权
   		if($index_type == 5)
   		{
   			$today_bus_info = $this->api->getTodayBus_info($zero_time);
   			$ret['today_bus_count'] = $today_bus_info;
   		}
   		
   		//6、消息推送
   		if($index_type == 6)
   		{
   			$today_pushmsg_info = $this->api->getTodayPushMsgInfo($zero_time);
   			$ret['today_push_msg_count'] = $today_pushmsg_info;
   		}
   		
   		//7、活跃用户
   		if($index_type == 7)
   		{
   			$today_activate_info = $this->company->getTodayActivateInfo();
   			$ret['today_activate_user_count'] = $today_activate_info;
   		}
   		
   		//8、覆盖设备数
   		if($index_type == 8)
   		{
   			$today_device_info = $this->statistics->getTodayDeviceInfo($zero_time);
   			$ret['today_device_info_count'] = $today_device_info;
   		}
   		
   		
   		//9、今日设备活跃
   		if($index_type == 9)
   		{
   			$today_activate_device_info = $this->statistics->getTodayActivateInfo($zero_time);
   			$ret['today_device_activate_count'] = $today_activate_device_info;
   		}
   	
   		
   		//10、覆盖会员
   		if($index_type == 10)
   		{
   			$today_member_info = $this->member->getTodayMemberInfo($zero_time);
   			$ret['today_member_count'] = $today_member_info;
   		}
   	
   		
//    		$ret = array(
//    			'totay_user_count' 		=> $today_user_info,
//    			'today_activate_user_count' 	=> $today_activate_info,
//    			'today_developer_count'	=> $today_developer_count,
//    			'today_push_count'		=> $today_push_count,
//    			'today_app_count'		=> $today_app_info,
//    			'today_bus_count'		=> $today_bus_info,
//    			'today_push_msg_count'	=> $today_pushmsg_info,
//    			'today_device_info_count'		=> $today_device_info,
//    			'today_device_activate_count'     => $today_activate_device_info,
//    			'today_member_count'		=> $today_member_info,
//    		);
   		
   		$this->addItem($ret);
   		$this->output();
   	}
   	
   	/**
   	 * 获取APP激活量排名
   	 */
   	public function getAppDownRank()
   	{
   		$time_type = $this->input['time_type'] ? intval($this->input['time_type']) : 1;
   		$start_date = $this->input['start_date'] ? trim($this->input['start_date']) : '';
   		$end_date = $this->input['end_date'] ? trim($this->input['end_date']) : '';
   		//安卓和ios分别的激活量排名
   		$info = $this->statistics->getTopTenDown($time_type,$start_date,$end_date);
   		if($info['android'] && is_array($info['android']))
   		{
   			foreach ($info['android'] as $k => &$v)
   			{
   				//获取app名字
   				$temp = $this->api->detail('app_info', array('id' => $v['app_id']));
   				$v['app_name'] = $temp['name'];
   				$v['user_name'] = $temp['user_name']; 				
   			}
   		}
   		if($info['ios'] && is_array($info['ios']))
   		{
   			foreach ($info['ios'] as $k => &$v)
   			{
   				$temp = $this->api->detail('app_info', array('id' => $v['app_id']));
   				$v['app_name'] = $temp['name'];
   				$v['user_name'] = $temp['user_name'];
   			}
   		}
   		$this->addItem($info);
   		$this->output();
   	}
   	
   	/**
   	 * 获取app活跃数排名
   	 */
   	public function getAppActivateRank()
   	{
   		$time_type = $this->input['time_type'] ? intval($this->input['time_type']) : 1;
   		$start_date = $this->input['start_date'] ? trim($this->input['start_date']) : '';
   		$end_date = $this->input['end_date'] ? trim($this->input['end_date']) : '';
   		//安卓和ios分别的激活量排名
   		$info = $this->statistics->getAppActivateRank($time_type,$start_date,$end_date);
   		if($info['android'] && is_array($info['android']))
   		{
   			foreach ($info['android'] as $k => &$v)
   			{
   				//获取app名字
   				$temp = $this->api->detail('app_info', array('id' => $v['app_id']));
   				$v['app_name'] = $temp['name'];
   				$v['user_name'] = $temp['user_name'];
   					
   			}
   		}
   		if($info['ios'] && is_array($info['ios']))
   		{
   			foreach ($info['ios'] as $k => &$v)
   			{
   				$temp = $this->api->detail('app_info', array('id' => $v['app_id']));
   				$v['app_name'] = $temp['name'];
   				$v['user_name'] = $temp['user_name'];
   			}
   		}
   		$this->addItem($info);
   		$this->output();
   	}
   	
   	public function getAllActivateAndDown()
   	{
   		$time_type = $this->input['time_type'] ? intval($this->input['time_type']) : 1;
   		$start_date = trim($this->input['start_date']);
   		$end_date = trim($this->input['end_date']);
   		$info = $this->statistics->getAllActivateAndDown($time_type,$start_date,$end_date);
   		$this->addItem($info);
   		$this->output();
   	}
   	
   	public function searchall()
   	{
   		$time_type = intval($this->input['time_type']);
   		$start_date = trim($this->input['start_date']);
   		$end_date = trim($this->input['end_date']);
   		switch($time_type)
   		{
   			case 1://首页
   				$start_time = 0;
   				$end_time = 0;
   				break;
   			case 2://今日
   				$start_time = strtotime(date('Y-m-d',TIMENOW));
   				$end_time = TIMENOW;
   				break;
   			case 3://最近7天
   				$start_time = strtotime(date('Y-m-d',strtotime('- 7 days')));
   				$end_time = strtotime(date('Y-m-d',TIMENOW));
   				break;
   			case 4://最近30天
   				$start_time = strtotime(date('Y-m-d',strtotime('- 30 days')));
   				$end_time = strtotime(date('Y-m-d',TIMENOW));
   				break;
   			case 5://最近60天
   				$start_time = strtotime(date('Y-m-d',strtotime('- 60 days')));
   				$end_time = strtotime(date('Y-m-d',TIMENOW));
   				break;
   			case 6://自定义日期
   				$start_time = strtotime(date('Y-m-d',strtotime($start_date)));
   				$end_time = strtotime(date('Y-m-d',strtotime($end_date)))+24*3600;
   				break;
   		}
   		//获取运营统计首页信息
   		//1、用户总数
   		$all_user_count = $this->company->getAllUserCount($start_time,$end_time);
   		$user_count = $all_user_count['total'];
   		
   		//2、活跃用户
   		$activate_user_count = $this->company->getAllActivateUsers($start_time,$end_time);
   		
   		//3、开发者数	 
   		$developer_count = $this->company->getTodayAddDevelopInfo($start_time,$end_time);  		
   		
   		//4、推送申请总数（申请记录）
   		$push_count = $this->api->getPushInfoInDate($start_time,$end_time);
   		
   		//5、app创建总数
   		$app_count = $this->api->count(array(
   			'start_date' => $start_time,
   			'end_date'	 => $end_time,	
   		));
   		 
   		//6、商业授权总数
   		$bus_count = $this->bus_mode->count(" and create_time > " . $start_time . " and create_time < ".$end_time);
   		 
   		//7、消息推送总数
   		$push_info_count = $this->push_msg_mode->count(' and create_time >'.$start_time." and create_time <".$end_time);
   		
   		//8、覆盖设备总数
   		$device_info_count = $this->statistics->getCoverNums($start_time,$end_time);
   		
   		//9、设备活跃
   		$device_activate_count = $this->statistics->getAllActivateInfo($start_time,$end_time);
   		
   		//10、会员总数
   		$member_info_count = $this->member->getActivateMemberCount($start_time,$end_time);

   		$ret = array(
   				'user_count' 		=> intval($user_count),
   				'activate_user_count' => intval($activate_user_count['total']),
   				'developer_count'	=> intval($developer_count['total']),
   				'push_count'		=> intval($push_count['total']),
   				'app_count'			=> intval($app_count['total']),
   				'business_count'	=> intval($bus_count['total']),
   				'push_msg_count'	=> intval($push_info_count['total']),
   				'device_info_count'	=> intval($device_info_count['total']),
   				'device_activate_count'	=> intval($device_activate_count['total']),
   				'member_info_count'		=> intval($member_info_count['total']),
   								
   		);
   		$this->addItem($ret);
   		$this->output();
   	}
   	
   	/**
   	 * 根据时间范围，，类型获取对应数据
   	 */
   	public function getEveryInfoByTypeAndDate()
   	{
   		$time_type = intval($this->input['time_type']);
   		$start_date = trim($this->input['start_date']);
   		$end_date = trim($this->input['end_date']);
   		$page = $this->input['page'] ? intval($this->input['page']) : 1;
   		$index_type = intval($this->input['index_type']);
   		//data_type按照什么类型来搜数据
   		//1按天 2按周 3按月
   		$data_type = $this->input['data_type'] ? intval($this->input['data_type']) : 1;
   		switch($time_type)
   		{
   			case 1://首页
   				$start_time = 0;
   				$end_time = 0;
   				break;
   			case 2://今日
   				$start_time = strtotime(date('Y-m-d',TIMENOW));
   				$end_time = TIMENOW;
   				break;
   			case 3://最近7天
   				$start_time = strtotime(date('Y-m-d',strtotime('- 7 days')));
   				$end_time = strtotime(date('Y-m-d',TIMENOW));
   				break;
   			case 4://最近30天
   				$start_time = strtotime(date('Y-m-d',strtotime('- 30 days')));
   				$end_time = strtotime(date('Y-m-d',TIMENOW));
   				break;
   			case 5://最近12周
//    				$start_time = strtotime(date('Y-m-d',strtotime('- 60 days')));
//    				$end_time = strtotime(date('Y-m-d',TIMENOW));
					//先获取现在是星期几
					$now_week = date('w',TIMENOW);
					if($now_week == 0)
					{
						$end_time = strtotime(date('Y-m-d'),TIMENOW)-$this->settings['statistics']['one_day_time']*(7-1);
						$start_time = $end_time - 12*7*$this->settings['statistics']['one_day_time'];
					}
					else
					{
						$end_time = strtotime(date('Y-m-d',TIMENOW))-$this->settings['statistics']['one_day_time']*($now_week-1);
						$start_time = $end_time - 12*7*$this->settings['statistics']['one_day_time'];
					}		
   				break;
   			case 6://自定义日期
   				$start_time = strtotime(date('Y-m-d',strtotime($start_date)));
   				$end_time = strtotime(date('Y-m-d',strtotime($end_date)))+24*3600;
   				break;
   		}
   		$big_start = $start_time;
   		$big_end = $end_time;
   		$temp_start_time = 0;
   		$temp_end_time = 0;
   		$ret = array();
   		$flag = 1;//是否break出循环
   		$month_break = 1;
   		$for_count = $this->settings['statistics']['max_num'];
   		for ($i = 0 ; $i < $for_count ; $i++)
   		{
   			if(!$month_break)
   			{
   				break;
   			}
   			//获取对应时间内的第i天的数据
   			$temp = array();
   			switch ($data_type)
   			{
   				case 1:
   					$temp_start_time = $big_start+$i*3600*24;
   					$temp_end_time = $temp_start_time+3600*24;
   					break;
   				case 2:
   					//按周 如果是第一个页的第一个数据 则要判断周几 以得到第个数据

   					if($i == 0)
   					{
   						$week = date('w',$big_start);
   						if($week != 1)
   						{
   							if($week == 0)
   							{
   								$temp_start_time = $big_start;
   								$temp_end_time = $temp_start_time+3600*24;
   							}
   							else
   							{
   								$temp_start_time = $big_start;
   								$temp_end_time = $temp_start_time+3600*24*(7-$week);
   							}
   						}
   						else
   						{
   							$temp_start_time = $big_start;
   							$temp_end_time = $temp_start_time+3600*24*7;
   						}
   					}
   					else
   					{
   						$temp_start_time = $temp_end_time;
   						$temp_end_time = $temp_start_time+3600*24*7;
   					}
   					 				
   				break;
   			case 3:
   				//按月
   				if($i == 0)
   				{
   					$temp_start_time = $big_start;
   					//获取这个月的最后一天
   					$arr = $this->getthemonth(date('Y-m-d',$temp_start_time));
   					$temp_end_time = strtotime($arr[1])+$this->settings['statistics']['one_day_time'];
   				}
   				else
   				{
   					$temp_start_time = $temp_end_time;
   					$arr = $this->getthemonth(date('Y-m-d',$temp_start_time));
   					$temp_end_time = strtotime($arr[1])+$this->settings['statistics']['one_day_time'];
   				}	
   				break;
   			}
   			
   			switch ($data_type)
   			{	
   				case 1:
   					if($temp_end_time > $big_end)
   					{
   						$flag = 0;
   					}
   					break;
   				case 2:
   					if($temp_end_time > $big_end)
   					{
   						if(($temp_end_time - $big_end) >= 7*3600*24)
   						{
   							$flag = 0;
   						}
   						else 
   						{
   							$temp_end_time = $big_end;
   						}
   					}
   					break;
   				case 3:
   					if($temp_end_time > $big_end)
   					{
   						$temp_end_time = $big_end;
   						$month_break = 0;
   					}
   					break;
   			}
   			if(!$flag)
   			{
   				break;
   			}

   			//1、用户总数
   			if($index_type == 1 || $index_type == 11)
   			{
   				$all_user_count = $this->company->getAllUserCount($temp_start_time,$temp_end_time);
   				$user_count = $all_user_count['total'];
   				$temp['user_count'] = $user_count;
   			}
   			
   			
   			//2、开发者申请总数(待审核)
   			if($index_type == 2 || $index_type == 11)
   			{
   				$develop_conditon = " and is_developer = 1 and develop_create_time > ".$temp_start_time . " and develop_create_time < ".$temp_end_time;
   				$developer_count = $this->developer_mode->count($develop_conditon);
   				$temp['developer_count'] = $developer_count['total'];
   			}
   			
   			
   			//3、推送申请总数（申请记录）
   			if($index_type == 3 || $index_type == 11)
   			{
   				$push_count = $this->api->getPushInfoInDate($temp_start_time,$temp_end_time);
   				$temp['push_count'] = $push_count['total'];
   			}
   				
   					 
   			//4、app创建总数
   			if($index_type == 4 || $index_type == 11)
   			{
   				$app_count = $this->api->appCountInDate($temp_start_time,$temp_end_time);
   				$temp['app_count'] = $app_count['total'];
   			}
   			
   			
   			//5、商业授权总数
   			if($index_type == 5 || $index_type == 11)
   			{
   				$bus_count = $this->bus_mode->count(" and create_time > " . $temp_start_time . " and create_time < ".$temp_end_time);
   				$temp['bus_count'] = $bus_count['total'];
   			}
   			
   			//6、消息推送总数
   			if($index_type == 6 || $index_type == 11)
   			{
   				$push_info_count = $this->push_msg_mode->count(' and create_time >'.$temp_start_time." and create_time <".$temp_end_time); 				
   				$temp['push_msg_count'] = $push_info_count['total'];
   			}
   			
   			//7、活跃用户
   			if($index_type == 7 || $index_type == 11)
   			{
   				$activate_info_count = $this->company->getActivateInfoIndate($temp_start_time,$temp_end_time);
   				$temp['activate_user_count'] = $activate_info_count['total'];
   			}
   			
   			
   			//8、覆盖设备总数
   			if($index_type == 8 || $index_type == 11)
   			{
   				$device_info_count = $this->statistics->getCoverNums($temp_start_time,$temp_end_time);
   				$temp['device_info_count'] = $device_info_count['total'];
   			}
   			
   			
   			//9、设备活跃
   			if($index_type == 9 || $index_type == 11)
   			{
   				$device_activate_count = $this->statistics->getAllActivateInfo($temp_start_time,$temp_end_time);
   				$temp['device_activate_count'] = $device_activate_count['total'];
   			}
   			
   			
   			//10、会员数
   			if($index_type == 10 || $index_type == 11)
   			{
   				$member_info_count = $this->member->getActivateMemberCount($temp_start_time,$temp_end_time);
   				$temp['member_count'] = $member_info_count['total'];
   			}
   			
   			switch ($data_type)
   			{
   				case 1:
   					$ret[date('Y-m-d',$temp_start_time)] = $temp;
   					break;
   				case 2:
   					$ret[date('Y/m/d',$temp_start_time)."-".date('Y/m/d',$temp_end_time-3600*24)] = $temp;
   					break;
   				case 3:
   					$ret[date('Y/m/d',$temp_start_time)."-".date('Y/m/d',$temp_end_time-3600*24)] = $temp;
   					break;
   			}
   		}
   		$this->addItem($ret);
   		$this->output();	
   	}
   	
   	/**
   	 * 获取这个月的最后一天和最后一天
   	 * @param unknown $date
   	 * @return multitype:string
   	 */
   	public function getthemonth($date)
	{
		$firstday = date('Y-m-01', strtotime($date));
		$lastday = date('Y-m-d', strtotime("$firstday +1 month -1 day"));
		return array($firstday, $lastday);
	}
   	
	
	/**************************用户统计用************************************/
	/**
	 * 获取app的创建数
	 */
	public function getAppCreateNums()
	{
		//获取昨日， 最近3天，最近7天，总数
		$yes_start = strtotime(date('Y-m-d'),TIMENOW)-$this->settings['statistics']['one_day_time'];
		$yse_end = strtotime(date('Y-m-d'),TIMENOW);
		$and_yes_info = $this->api->getAppNumsInDateBySource($yes_start, $yse_end , 1);
		$ios_yes_info = $this->api->getAppNumsInDateBySource($yes_start, $yse_end , 2);
		
		$three_start =  strtotime(date('Y-m-d'),TIMENOW)-$this->settings['statistics']['one_day_time']*3;
		$three_end = strtotime(date('Y-m-d'),TIMENOW);
		$and_three_info = $this->api->getAppNumsInDateBySource($three_start, $three_end , 1);
		$ios_three_info = $this->api->getAppNumsInDateBySource($three_start, $three_end , 2);
		
		$seven_start = strtotime(date('Y-m-d'),TIMENOW)-$this->settings['statistics']['one_day_time']*7;
		$seven_end = strtotime(date('Y-m-d'),TIMENOW);
		$and_seven_info = $this->api->getAppNumsInDateBySource($seven_start, $seven_end , 1);
		$ios_seven_info = $this->api->getAppNumsInDateBySource($seven_start, $seven_end , 2);
		
		$thirty_start = strtotime(date('Y-m-d'),TIMENOW)-$this->settings['statistics']['one_day_time']*30;
		$thirty_end = strtotime(date('Y-m-d'),TIMENOW);
		$and_thirty_info = $this->api->getAppNumsInDateBySource($thirty_start, $thirty_end , 1);
		$ios_thirty_info = $this->api->getAppNumsInDateBySource($thirty_start, $thirty_end , 2);
		
		$all_start = 0;
		$all_end = 0;
		$and_all_info = $this->api->getAppNumsInDateBySource($all_start, $all_end , 1);
		$ios_all_info = $this->api->getAppNumsInDateBySource($all_start, $all_end , 2);
	
		$create_ino = array(
			'yesterday' => array('and' => $and_yes_info['total'],'ios' => $ios_yes_info['total']),
			'threeday' => array('and' => $and_three_info['total'],'ios' => $ios_three_info['total']),
			'sevenday' => array('and' => $and_seven_info['total'],'ios' => $ios_seven_info['total']),
			'thirtyday' => array('and' => $and_thirty_info['total'],'ios' => $ios_thirty_info['total']),
			'allday' => array('and' => $and_all_info['total'],'ios' => $ios_all_info['total']),
		);
		
		//过去15天中每天ios和app的创建按数()
		$every_day_info = array();
		$start_time = 0;
		$end_time = 0;
		for($i = 0 ; $i < 15 ; $i++)
		{
			if($i == 0)
			{
				$start_time = strtotime(date('Y-m-d',TIMENOW))-$this->settings['statistics']['one_day_time'];
			}
			else
			{
				$start_time = $start_time - $this->settings['statistics']['one_day_time'];
			}
			$end_time = $start_time+$this->settings['statistics']['one_day_time'];
			$every_day_info[date('Y-m-d',$start_time)]['and'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 1);
			$every_day_info[date('Y-m-d',$start_time)]['ios'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 2);
		}
		//过去12周 每周的数据
		$every_week_info = array();
		$week = date('w',TIMENOW);
		for($i = 0 ; $i < 12 ; $i++)
		{
			if($i == 0)
			{
				if($week == 0)
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-6*3600*24;
				}
				else
				{
					$end_time = strtotime(date('Y-m-d',TIMENOW))-($week-1)*3600*24;
				}
				$start_time = $end_time - 7*3600*24;
			}		
			else
			{
				$end_time = $start_time;
				$start_time = $end_time - 7*3600*24;
			}
			
			$every_week_info[date('Y/m/d',$start_time)."-".date('Y/m/d',$end_time-3600*24)]['and'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 1);
			$every_week_info[date('Y/m/d',$start_time)."-".date('Y/m/d',$end_time-3600*24)]['ios'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 2);

		}
		//过去12个月 每月数据
		$every_month_info = array();
		for ($i = 0 ; $i < 12 ; $i++)
		{
			if($i == 0)
			{
				$back_month_date = date('Y-m-d',strtotime("-1 month"));
				$arr = $this->getthemonth($back_month_date);
				$start_time = strtotime($arr[0]);
				$end_time = strtotime($arr[1])+3600*24;
			}
			else
			{
				$back_month_date = date('Y-m-d',$start_time-3600*24);
				$arr = $this->getthemonth($back_month_date);
				$start_time = strtotime($arr[0]);
			}
			
			$every_month_info[date('Y/m/d',$start_time)."-".date('Y/m/d',$end_time-3600*24)]['and'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 1);
			$every_month_info[date('Y/m/d',$start_time)."-".date('Y/m/d',$end_time-3600*24)]['ios'] = $this->api->getAppNumsInDateBySource($start_time, $end_time , 2);
		}
		
		$every_info = array(
			'by_day' => $every_day_info,
			'by_week' => $every_week_info,
			'by_month' => $every_month_info,
		);
		
		//消亡数perish 数据 :APP沉没数和APP夭折数的总和
		//沉没数sink：自创建起6个月内，前3个月用户量达到100后，日活跃用户均小于5的APP数
		//APP夭折数dead：自创建起3个月内用户量均未达到100的APP数。
		//过去15天
		$perish_info = array();
		$sink_info = array();
		$dead_info = array();
		$days = $this->settings['statistics']['perish_day'];
		for ($i = 0 ; $i < $days ; $i ++)
		{
			if($i == 0)
			{
				$end_time = strtotime(date('Y-m-d',TIMENOW));
				$start_time = $end_time - $this->settings['statistics']['one_day_time'];	
			}
			else
			{
				$end_time = $start_time;
				$start_time = $end_time - $this->settings['statistics']['one_day_time'];
			}
			//先要获取在这一天创建恰好满6个月APP
			$six_start_time = date('Y-m-d',strtotime("-6 months"))-($i+1)*$this->settings['statistics']['one_day_time'];
			$six_end_time = date('Y-m-d',strtotime("-6 months"))-$i*$this->settings['statistics']['one_day_time'];
			$and_apps = $this->api->getFirstCreateAndroidAppIndate($six_start_time,$six_end_time,1);
			$ios_apps = $this->api->getFirstCreateAndroidAppIndate($six_start_time,$six_end_time,2);
			$and_sink_info = array();
			$ios_sink_info = array();
			if($and_apps)
			{
				//判断对应APP是否满足沉没的条件	
				$and_ids_arr = array();
				foreach ($and_apps as $k => $v)
				{
					$and_ids_arr[] = $v['app_id'];
				}	
				$and_ids = implode(',', $and_ids_arr);
				$and_sink_info = $this->statistics->getSinkInfo($start_time,$end_time,1,$and_ids,$six_start_time,$six_end_time);
			}
			if($ios_apps)
			{
				$ios_ids_arr = array();
				foreach ($ios_apps as $k => $v)
				{
					$ios_ids_arr[] = $v['app_id'];
				}
				$ios_ids = implode(',', $ios_ids_arr);
				$ios_sink_info = $this->statistics->getSinkInfo($start_time,$end_time,2,$ios_ids,$six_start_time,$six_end_time);
			}
			$and_sink_num = count($and_sink_info);
			$ios_sink_num = count($ios_sink_info);
			$and_sink_proportion = $and_apps ? $and_sink_num/count($and_apps) : '/';
			$ios_sink_proportion = $ios_apps ? $ios_sink_num/count($ios_apps) : '/';
			$sink_info[date('Y-m-d',$start_time)] = array(
				'and' => array('num' => count($and_sink_info),'proportion' => $and_sink_proportion),
				'ios' => array('num' => count($ios_sink_info),'proportion' => $ios_sink_proportion),
			);
			
			//获取今天正好3个月的应用
			$three_month_start_time = date('Y-m-d',strtotime("-3 months"))-($i+1)*$this->settings['statistics']['one_day_time'];
			$three_month_end_time = date('Y-m-d',strtotime("-3 months"))-$i*$this->settings['statistics']['one_day_time'];
			$three_month_and_apps = $this->api->getFirstCreateAndroidAppIndate($three_month_start_time,$three_month_end_time,1);
			$three_month_ios_apps = $this->api->getFirstCreateAndroidAppIndate($three_month_start_time,$three_month_end_time,2);
			
			if($three_month_and_apps)
			{
				//判断对应的APP是否夭折
				$and_ids_arr = array();
				foreach ($three_month_and_apps as $k => $v)
				{
					$and_ids_arr[] = $v['app_id'];
				}
				$and_ids = implode(',', $and_ids_arr);
				$and_perish_info = $this->statistics->getPerishInfo($three_month_start_time,$three_month_end_time,1,$and_ids);
			}
			
			if($three_month_ios_apps)
			{
				$ios_ids_arr = array();
				foreach ($three_month_ios_apps as $k => $v)
				{
					$ios_ids_arr[] = $v['app_id'];
				}
				$ios_ids = implode(',', $ios_ids_arr);
				$ios_perish_info = $this->statistics->getPerishInfo($three_month_start_time,$three_month_end_time,2,$ios_ids);
			}
			$and_perish_proportion = $three_month_and_apps ? count($and_perish_info)/count($three_month_and_apps) : '/';
			$ios_perish_proportion = $three_month_ios_apps ? count($three_month_and_apps)/count($three_month_ios_apps) : '/';
			$perish_info[date('Y-m-d',$start_time)] = array(
					'and' => array('num' => count($and_perish_info) , 'proportion' => $and_perish_proportion),
					'ios' => array('num' => count($ios_perish_info) , 'proportion' => $ios_perish_proportion),
			);
		}	
		
		
		
		
		$ret = array(
			'create_info' => $create_ino,
			'every_info'  => $every_info,
			'sink_info'	  => $sink_info,
			'perish_info' => $perish_info,
		);
		$this->addItem($ret);
		$this->output();
	}
	/**************************用户统计用end************************************/
}

$out = new operate_statistics();
if(!method_exists($out, $_INPUT['a']))
{
    $action = 'show';
}
else
{
    $action = $_INPUT['a'];
}
$out->$action();