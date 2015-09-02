<?php
/*
 * HOGE WEB
 *
 * @package     DingDone WEB
 * @author      RDC3 - dxtan
 * @copyright   Copyright (c) 2013 - 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since       Version 1.1.0
 * @date        2014-10-16
 * @encoding    UTF-8
 * @description 会员黑名单
 */
define('MOD_UNIQUEID','member_blacklist_update');//模块标识
require('./global.php');
require CUR_CONF_PATH . 'lib/member.class.php';
class memberBlacklistApi extends adminUpdateBase
{
	private $member;
	private $Blacklist;
    private $Members;
	public function __construct()
	{
		parent::__construct();
		$this->verify_content_prms(array('_action'=>'manage'));
		$this->member = new member();
		$this->members = new members();
        $this->Members = new members();
		$this->Blacklist = new memberblacklist();
        $this->mMember = new member();
	
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 创建设备号黑名单
	 */
	public function SetBlacklist()
	{
        if($guid = $this->input['guid'])
        {
            $condition = ' AND guid="'.$guid.'"';
            $memberInfo = $this->mMember->get_member_info($condition);
            if($memberInfo)
            {
                $member_id = $memberInfo[0]['member_id'];
            }
        }
        else
        {
            $member_id = explode(',', $this->input['member_id']);
        }

        $member_id_arr =  explode(',', $member_id);
		$black_device = $this->input['black_device'];
		$black_ip = $this->input['black_ip'];

		foreach($member_id_arr as $k=>$v)
		{
			$params = array(
					'member_id'  =>  $v,
			);
			$res = $this->initblacklist($params,$black_device,$black_ip);
		}
		if($res)
		{
			echo  json_encode(array('msg' => $res, 'error' => 0));
		}
		else
		{
			echo json_encode(array('msg' => $res, 'error' => 1));
		}	
	}
	
	private function initblacklist($params,$black_device,$black_ip)
	{
        //type 1:app应用管理员拉黑 2:官方拉黑
        $type = intval($this->input['type']) ? $this->input['type'] : 1;
        $limit = "limit 0,5";
		//获取用户登录注册日志
		$field = '*';
		$key='';
		$orderby = 'ORDER BY create_time DESC';
		$isBatch = 1;
		$member_log = $this->member->getMemberTrace($params,$field,$key,$orderby,$isBatch,$limit);
		$device_arr = $this->unique_arr($member_log,'udid');
		$ip_arr = $this->unique_arr($member_log,'ip');
		$uid = $params['member_id'];
		$member_info = $this->member->detail($uid);
		//设置device黑名单
		if($black_device)
		{
			foreach ($device_arr as $k=>$v)
			{
				if($v && !in_array($v, array('unknown','admin','www')))
				{
					//查询是否存在
					$device_log = $this->Blacklist->detailDeviceBlacklist(array('device_token'=>$v,'identifier'=>$member_info['identifier']));
					if(!$device_log)
					{
						$Devicedata = array(
								'device_token'  => $v,
                                'member_id' => $uid,
                                'member_name' => $member_info['member_name'],
                                'type' => $type,
                                'identifier' => $member_info['identifier'],
								'deadline'   => '-1',
						);
						$res_Device = $this->Blacklist->createDeviceBlacklist($Devicedata);
					}
					else
					{
						//增加黑名单统计次数
						$total = $device_log[0]['total'] + 1;
						$res_Device = $this->Blacklist->updateDeviceBlacklist(array('total' => $total,'deadline' => '-1','type' => $type),array('device_token'=>$v,'identifier'=>$member_info['identifier']));
					}
				}
			}
			//强制用户退出
			$this->members->force_logout_user($uid);
		}
		else
		{
			//取消黑名单  将deadine置为0
			foreach ($device_arr as $k=>$v)
			{
				$res_Device = $this->Blacklist->updateDeviceBlacklist(array('deadline' => '0','type' => 0),array('device_token'=>$v,'identifier' => $member_info['identifier']));
			}
		}
		
		//设置ip黑名单
		if($black_ip)
		{
			foreach ($ip_arr  as $k => $v)
			{
				if($v && !in_array($v, array('unknown')))
				{
				//查询是否存在
					$ip_log = $this->Blacklist->detailIpBlacklist(array('ip'=>ip2long($v),'identifier'=>$member_info['identifier']));
					if(!$ip_log)
					{
						$Ipdata = array(
								'ip'  => ip2long($v),
                                'member_id' => $uid,
                                'member_name' => $member_info['member_name'],
                                'type' => $type,
								'identifier' => $member_info['identifier'],
								'deadline'   => '-1',
						);
						$res_Ip = $this->Blacklist->createIpBlacklist($Ipdata);
					}
					else
					{
						//增加黑名单统计次数
						$total = $ip_log[0]['total'] + 1;
						$res_Ip = $this->Blacklist->updateIpBlacklist(array('total' => $total,'deadline' => '-1','type' => $type),array('identifier'=>$member_info['identifier'],'ip'=>ip2long($v)));
					}
				}
			}
			//强制用户退出
			$this->members->force_logout_user($uid);
		}
		else
		{
			//取消黑名单  将deadine置为0
			foreach ($ip_arr  as $k=>$v)
			{
				$res_Ip = $this->Blacklist->updateIpBlacklist(array('deadline' => '0','type' => 0),array('ip'  => ip2long($v),'identifier' => $member_info['identifier']));
			}
		}
		if($res_Device || $res_Ip)
		{
			$info = array();
			$info['device_token'] = $res_Device;
			$info['ip'] = $res_Ip;
			return $info;
		}
		return false;
	}

    /**
     * 官网永久拉黑设备号
     */
    public function black_device_forever()
    {
        $member_id = $this->input['member_id'];
        $device_token = $this->input['device_token'];
        $identifier = $this->input['identifier'];
        $black_device = $this->input['black_device'];
        $deadline = $this->input['deadline'] ? $this->input['deadline'] : 0;
        $type = intval($this->input['type']) ? $this->input['type'] : 1;

        //查询是否存在
        $device_log = $this->Blacklist->detailDeviceBlacklist(array('device_token'=>$device_token,'identifier'=>$identifier));
        if(empty($device_log))
        {
            $this->errorOutput(NO_INFO);
        }
        if($black_device)
        {
            //增加黑名单统计次数
            $total = $device_log[0]['total'] + 1;

            if($type == 2)
            {
                $res_Device = $this->Blacklist->updateDeviceBlacklist(array('total' => $total,'type' => $type),array('device_token'=>$device_token,'identifier'=>$identifier));
            }
            else
            {
                $res_Device = $this->Blacklist->updateDeviceBlacklist(array('total' => $total,'deadline' => $deadline,'type' => 1),array('device_token'=>$device_token,'identifier'=>$identifier));
            }

            //强制用户退出
            $this->members->force_logout_user($member_id);
        }
        else
        {
            $res_Device = $this->Blacklist->updateDeviceBlacklist(array('deadline' => 0,'type' => 0),array('device_token'=>$device_token,'identifier' => $identifier));
        }
        $this->Members->blacklist_set($member_id,$deadline,$type);

        if($res_Device)
        {
            $res_Device['id'] = $device_log[0]['id'];
            $res_Device['type'] = $type;
            $res_Device['deadline'] = $deadline;
        }

        $this->addItem($res_Device);
        $this->output();
    }

    /**
     * 官网永久拉黑ip
     */
    public function black_ip_forever()
    {
        $member_id = $this->input['member_id'];
        $ip = $this->input['ip'];
        $identifier = $this->input['identifier'];
        $black_ip = $this->input['black_ip'];
        $deadline = $this->input['deadline'] ? $this->input['deadline'] : 0;
        $type = intval($this->input['type']) ? $this->input['type'] : 1;

        //查询是否存在
        $ip_log = $this->Blacklist->detailIpBlacklist(array('ip'=>ip2long($ip),'identifier'=>$identifier));
        if(empty($ip_log))
        {
            $this->errorOutput(NO_INFO);
        }
        if($black_ip)
        {
            //增加黑名单统计次数
            $total = $ip_log[0]['total'] + 1;

            if($type == 2)
            {
                $res_Ip = $this->Blacklist->updateIpBlacklist(array('total' => $total,'type' => 2),array('identifier'=>$identifier,'ip'=>ip2long($ip)));
            }
            else
            {
                $res_Ip = $this->Blacklist->updateIpBlacklist(array('total' => $total,'deadline' => $deadline,'type' => 1),array('identifier'=>$identifier,'ip'=>ip2long($ip)));
            }

            //强制用户退出
            $this->members->force_logout_user($member_id);
        }
        else
        {
            $res_Ip = $this->Blacklist->updateIpBlacklist(array('deadline' => '0','type' => 0),array('ip'  => ip2long($ip),'identifier' => $identifier));
        }
        $this->Members->blacklist_set($member_id,$deadline,$type);

        if($res_Ip)
        {
            $res_Ip['id'] = $ip_log[0]['id'];
            $res_Ip['ip'] = long2ip($res_Ip['ip']);
            $res_Ip['type'] = $type;
            $res_Ip['deadline'] = $deadline;
        }

        $this->addItem($res_Ip);
        $this->output();
    }
	
	private function unique_arr($arr2D,$key)
	{
		$temp = array();
		foreach($arr2D as $k=>$v)
		{
			array_push($temp,$v[$key]);
		}
		$res = array_unique($temp);
		return $res;
	}
	
	public function create(){}
	public function update(){}
	public function delete(){}
	public function audit(){}
	public function sort(){}
	public function publish(){}
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}
$out = new memberBlacklistApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>