<?php 
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id:$
***************************************************************************/
require_once('./global.php');
define('MOD_UNIQUEID','adv_group');//模块标识
class adv_group extends adminReadBase
{
	function __construct()
	{
		parent::__construct();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function index()
	{
		
	}
	function show()
	{
		
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$orders = array('id');
		$descasc = strtoupper($this->input['hgupdn']);
		if ($descasc != 'ASC')
		{
			$descasc = 'DESC';
		}
		if (in_array($this->input['hgorder'], $orders))
		{
			$orderby = ' ORDER BY ' . $this->input['hgorder']  . ' ' . $descasc ;
		}
		$condition = $this->get_condition();
		$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup WHERE 1'.$condition.$orderby.$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('groups','group');
		while($r = $this->db->fetch_array($q))
		{
			$r['create_time'] = date('Y-m-d h:i:s');
			$this->addItem($r);
		}
		$this->output();
	}
	function get_condition()
	{
		$condition = '';
		if($this->input['k'])
		{
			$condition .= ' AND name LIKE "%'.trim(urldecode($this->input['k'])).'%"';
		}
		return $condition;
	}
	function count()
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'advgroup'.$this->get_condition();
		echo json_encode($this->db->query_first($sql));
	}
	function detail()
	{
		
		if($this->input['latest'])
		{
			$sql = 'SELECT * FROM '.DB_PREFIX.'advgroup ORDER BY id desc limit 1';
		}
		else
		{
			if($this->input['id'])
			{
				$sql =  'SELECT * FROM '.DB_PREFIX.'advgroup WHERE id = '.intval($this->input['id']);
			}
			else
			{
				$this->errorOutput(NOID);
			}
		}
		$r = $this->db->query_first($sql);

		if($r && is_array($r))
		{
			$this->addItem($r);
		}
		$this->output();
	}
	
	//取出可以用于策略的数据字段
	function policy()
	{
		
		if(!$this->input['id'])
		{
			return;
		}
		$selected  = $this->db->query_first('SELECT policy,flag FROM '.DB_PREFIX.'advgroup WHERE id = '.intval($this->input['id']));
		$group_flag = $selected['flag'];
		$selected = unserialize($selected['policy']);
		include_once(ROOT_PATH .'lib/class/curl.class.php');
		$curl_request_settings = '';
		$dic = array();
		switch ($group_flag)
		{
			case $this->settings['hg_ad_flag']['liv_player_flag']:
				{
					$curl_request_settings = 'App_live';
					break;
				}
			case $this->settings['hg_ad_flag']['vod_player_flag']:
				{
					$curl_request_settings = 'App_livmedia';
					break;
				}
			case $this->settings['hg_ad_flag']['mobile_ad_flag']:
				{
					$curl_request_settings = 'App_publishcontent';
					break;
				}
			case  $this->settings['hg_ad_flag']['web_ad_flag']:
				{
					//break;
				}
			default:
				{
					$this->errorOutput('等待开发！');
				}
		}
		if(!$dic)
		{
			$curl = new curl($this->settings[$curl_request_settings]['host'], $this->settings[$curl_request_settings]['dir']);
			$curl->initPostData();
			$ad = array();
			$curl->addRequestData('a', '__getModelDict');
			$curl->addRequestData('model_name', rawurldecode($this->input['model_name']));
			$dic = $curl->request('index.php');
		}
		if($dic['ErrorCode'])
		{
			$this->errorOutput($dic['ErrorCode']);
		}
		if(!$dic[0])
		{
			$this->errorOutput(NO_DICT_DATA);	
		}
		$dic[0]['selected'] = $selected;
		$this->addItem($dic[0]);
		$this->output();
	}
}
$ouput= new adv_group();
if(!method_exists($ouput, $_INPUT['a']))
{
	$action = 'show';
}
else
{
	$action = $_INPUT['a'];
}
$ouput->$action();