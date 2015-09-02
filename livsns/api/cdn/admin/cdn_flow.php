<?php
define('MOD_UNIQUEID','cdn_flow');//模块标识
require('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnFlowApi extends adminReadBase
{
	public function __construct()
	{
		parent::__construct();
		$this->obj = new Core();
		include(CUR_CONF_PATH . 'lib/UpYun.class.php');
		$this->upyun = new UpYun();
		
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function  show()
	{	
		//file_put_contents('0',var_export($this->input,1));
		$data = array(
		);
		$upyun = $this->upyun->get_upyun_access_token();
	
		$oauth = new OAuth2Client(OAUTH_CLIENT_ID, OAUTH_CLIENT_SECRET,
    						OAUTH_BASE_URI, OAUTH_AUTHORIZE_URI, OAUTH_ACCESS_TOKEN_URI);
    	$oauth->setAccessToken($upyun['access_token']);
    	
    	$buckets = $oauth->request('/buckets/', 'GET');
    	if($buckets && is_array($buckets))
    	{
    		foreach($buckets['buckets'] as $k=>$v)
    		{
    			$buckets_name[$v['bucket_name']] = $v['bucket_name'];
    		}
    	}
    	if($this->input['bucket'] && $this->input['bucket'] !='-1')
    	{
    		$data_['bucket_name'] = $data['bucket_name'] = $this->input['bucket'];
    	}
		
    	$doms = $oauth->request('/buckets/info/', 'GET',$data_);
    	if($doms['approval_domains'] && is_array($doms['approval_domains']))
		{
			foreach($doms['approval_domains'] as $k=>$v)
			{
				$domain[$v] = $v;
			}
		}
		if($doms['approvaling_domains'] && is_array($doms['approvaling_domains']))
		{
			foreach($doms['approvaling_domains'] as $k=>$v)
			{
				$domain[$v] = $v;
			}
		}
		if($this->input['domain'] && $domain[$this->input['domain']])
		{
			$data['domain'] = $this->input['domain'];
		}
		
		if($this->input['start_time'] && !$this->input['end_time'])
		{
			$start_date = date("Y-m-d");
			$end_date = $this->input['start_time'];
			$ds = strtotime($start_date);
			$de = strtotime($end_date);
			$data['period'] = round(($ds-$de)/3600/24) + 1;
		}
		if($this->input['start_time'] && $this->input['end_time'])
		{
			$start_date = $this->input['start_time'];
			$end_date = $this->input['end_time'];
			$ds = strtotime($start_date);
			$de = strtotime($end_date);
			$data['period'] = round(($de-$ds)/3600/24) + 1;
			$data['start_day'] = $this->input['end_time'];
		}

		if($this->input['date_search'])
		{
			switch(intval($this->input['date_search']))
			{
				case 1://今天
					break;
				case 2://最近3天
					$data['period'] = 3;
					break;
				case 3://最近7天
					$data['period'] = 7;
					break;
				case 4://最近15天
					$data['period'] = 15;
					break;
				case 5://最近30天
					$data['period'] = 30;
					break;
				default://所有时间段
					break;
			}
		}
		
		$info = $oauth->request('/stats/', 'GET',$data);
		if($info['maxs'] && is_array($info['maxs']))
		{
			if($data['period'])
			{
				//$this->count($data['period']);
			}
			foreach($info['maxs'] as $k=>$v)
			{
				$re = array(
					'data'		=>	$k,
					'pubtime'	=>	$v['pubtime'],
					'reqs'		=>	$v['reqs'],
				);
				$bytes = $this->get_bytes($v['bytes']);
				$sbytes = $this->get_bytes($v['sbytes']);
				$re['bytes'] = $bytes;
				$re['sbytes'] = $sbytes;
				$return[] = $re;
			}
		}
		$cdn_flow[] = $return;
		$cdn_flow['buckets'] 		= $buckets_name;
		$cdn_flow['domain'] 		= $domain;
		$cdn_flow['bandwidth']  	= $info['bandwidth'];
		$cdn_flow['reqs'] 			= $info['reqs'];
		$cdn_flow['discharge'] 		= $info['discharge'];
		$cdn_flow['day_count'] 		= $info['day_count'];
		
		if($info['bandwidth'] && is_array($info['bandwidth']))
		{
			foreach($info['bandwidth'] as $k=>$v)
			{
				$cdn_flow['start_time'] = strtotime($k);
				if(!$cdn_flow['start_time'])
				{
					$time = explode(' ',$k);
					$cdn_flow['start_time'] = strtotime($time[0]);
				}
				break;
			}
		}
		$this->addItem($cdn_flow);	
		$this->output();		
	}

	public function get_bytes($bys)
	{
		$num = '1024';
		$unit = ' Kb';
		$bytes =  round($bys/$num, 2);
		if($bytes/($num) > 1)
		{
			$bytes = round($bytes/$num,2);
			$unit = ' Mb';
		}
		if($bytes/$num > 1)
		{
			$bytes = round($bytes/$num,2);
			$unit = ' Gb';
		}
		$by = $bytes.$unit;
		return $by;
	}
	
	public function get_space()
	{	
		
	}
	
	public function detail()
	{	
	}
	
	/**
	 * 根据条件返回总数
	 * @name count
	 * @access public
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 * @return $info string 总数，json串
	 */
	public function count($count ='')
	{	
		echo json_encode(array('total' => 0));	
		exit;
		$re = array();
		$re['total'] = $count;
		//echo json_encode($re);	
	}
	
	/**
	 * 检索条件应用，模块,操作，来源，用户编号，用户名
	 * @name get_condition
	 * @access private
	 * @author gaoyuan
	 * @category hogesoft
	 * @copyright hogesoft
	 */
	public function get_condition()
	{		
		$condition = '';
		//查询应用分组
		return $condition;
	}
	
	
	public function index()
	{	
	}
}

$out = new CdnFlowApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>
