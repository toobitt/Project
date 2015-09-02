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
    public function __construct()
    {
        parent::__construct();
        $this->mode = new operate_statistics_class();
        $this->company = new CompanyApi();
        $this->api = new app();
        $this->statistics = new dingdonestatistics();
        $this->member = new member();
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
		    '' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
			'' => '',
				
				
				
				
			'all_user_count' => $all_user_count,
			'all_app_count' => $all_app_count,
			'three_month_activate' => $three_month_activate,
			'one_month_activate' => $one_month_activate,
			'day_add_user_count' => $day_add_user_count,
			'day_add_app_count' => $day_add_app_count,
			'day_activate_app' => $day_activate_app,
			'top_ten' => $top_ten,
				
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