<?php
define('MOD_UNIQUEID','cdn_log_analysis');//模块标识
require('global.php');
include(CUR_CONF_PATH . 'lib/Core.class.php');
include(CUR_CONF_PATH . 'lib/OAuth2Client.class.php');
class CdnLogAnalysisApi extends adminReadBase
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
		$data = $return = $buckets_name = $domain = $name = array();
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
    	else
    	{
    		$bkeys = array_keys($buckets_name);
    		$data_['bucket_name'] = $data['bucket_name'] = $bkeys[0];
    	}
    	if($data_['bucket_name'])
    	{
    		$doms = $oauth->request('/buckets/info/', 'GET',$data_);
    	}
    	
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
		else
		{
			$dokeys = array_keys($domain);
			$data['domain'] = $dokeys[0];
		}
		
		if($this->input['start_time'])
		{
			$data['date'] = $this->input['start_time'];
		}
		else
		{
			$data['date'] = date('Y-m-d', TIMENOW - 24 * 3600);
		}
		$data['type'] = $this->input['type'] ? $this->input['type'] : 'url';
		$info = $oauth->request('/analysis/', 'GET',$data);
		
		if($info['data'] && is_array($info['data']))
		{
			foreach($info['data'] as $k=>$v)
			{
				$re = array(
					'content'	=>	$v['content'],
				);
				$flow = $this->get_bytes($v['flow']);
				if($this->input['type'] == 'size')
				{
					$re['reqs'] = $this->get_bytes($v['reqs']);
				}
				else
				{
					$re['reqs'] = $v['reqs'];
				}
				$re['flow'] = $flow;
				$return[] = $re;
			}
		}
		if($this->input['type'])
		{
			switch($this->input['type'])
			{
				case 'url':
					$name = 'URL';
				break;
				case 'ip':
					$name = 'IP';
				break;
				case 'referer':
					$name = '引用页面';
				break;
				case 'user_agent':
					$name = '客户端';
				break;
				case 'http_status':
					$name = 'HTTP 状态';
				break;
				case 'size':
					$name = 'URL';
				break;
				default:
					break;
			}
		}
		
		$cdn_flow[] = $return;
		$cdn_flow['buckets'] = $buckets_name;
		$cdn_flow['domain'] = $domain;
		$cdn_flow['name'] = $name;
		$cdn_flow['type'] = $this->input['type'];
		
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

$out = new CdnLogAnalysisApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>

