<?php
/**
 * HOGE DingDone Client-API
 *
 * 会员黑名单管理
 *
 * @package Member
 * @author RDC3 - dxtan
 * @copyright Copyright (c) 2014, HOGE CO., LTD (http://hoge.cn/)
 * @since Version 0.0.1
 */
class memberblacklist extends classCore
{
	private $membersql;
	public function __construct()
	{
		parent::__construct();
		$this->Members=new members();
		$this->membersql = new membersql();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function showDeviceBlacklist($condition = '',$orderby = '',$limit = '')
	{
        $sql = "SELECT * FROM " . DB_PREFIX . "device_blacklist  WHERE 1 " . $condition . $orderby . $limit;
        $q = $this->db->query($sql);
        $info = array();
        while($r = $this->db->fetch_array($q))
        {
            //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $info[] = $r;
        }

        return $info;
	}
	
	/**
	 * 获取用户详情
	 * @param unknown $member_id
	 * @return boolean|multitype:unknown
	 */
	public function detailDeviceBlacklist($params)
	{
		if(!$params)
		{
			return false;
		}
		$device_token = $params['device_token'];
		$identifier = $params['identifier'];
		$sql = "SELECT * FROM ". DB_PREFIX ."device_blacklist WHERE 1 AND device_token='".$device_token."' AND identifier=".$identifier;
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}

		return $info;
	}
	
	/**
	 * 创建设备号黑名单
	 * @param $data  array
	 */
	public function createDeviceBlacklist($data)
	{
		$this->membersql = new membersql();
		return $this->membersql->create('device_blacklist', $data);
	}
	
	/**
	 * 删除用户黑名单
	 * @param unknown $member_id
	 * @return boolean
	 */
	public function updateDeviceBlacklist($data,$condition)
	{
		$this->membersql = new membersql();
		$res = $this->membersql->update('device_blacklist', $data,$condition);
		return $res;
	}
	
	public function showIpBlacklist($condition = '',$orderby = '',$limit = '')
	{
        $sql = "SELECT * FROM " . DB_PREFIX . "ip_blacklist  WHERE 1 " . $condition . $orderby . $limit;
        $q = $this->db->query($sql);
        $info = array();
        while($r = $this->db->fetch_array($q))
        {
            //此处根据情况做一些格式化的处理,如：date('Y-m-d',TIMENOW);
            $r['ip'] = long2ip($r['ip']);
            $info[] = $r;
        }
        return $info;
	}
	
	/**
	 * 获取ip黑名单详情
	 * @return boolean|multitype:unknown
	 */
	public function detailIpBlacklist($params)
	{
		if(!$params)
		{
			return false;
		}
		$ip = $params['ip'];
		$identifier = $params['identifier'];
		$sql = "SELECT * FROM ". DB_PREFIX ."ip_blacklist WHERE 1 AND ip=".$ip." AND identifier=".$identifier."";
		$q = $this->db->query($sql);
		$info = array();
		while($r = $this->db->fetch_array($q))
		{
			$info[] = $r;
		}
		return $info;
	}
	
	/**
	 * 创建ip黑名单
	 */
	public function createIpBlacklist($data)
	{
		$this->membersql = new membersql();
		return $this->membersql->create('ip_blacklist', $data);
	}
	
	/**
	 * 删除ip黑名单
	 */
	public function updateIpBlacklist($data,$wheres)
	{
		$this->membersql = new membersql();
		return $this->membersql->update('ip_blacklist', $data,$wheres);
	}


    public function device_count($condition = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "device_blacklist WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        return $total;
    }

    public function ip_count($condition = '')
    {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ip_blacklist WHERE 1 " . $condition;
        $total = $this->db->query_first($sql);
        return $total;
    }

    public function detail($id = '')
    {
        return '';
    }
}