<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auth.php 37088 2014-05-21 01:06:24Z zhuld $
***************************************************************************/
define('MOD_UNIQUEID','auth');
define('CUR_CONF_PATH', '../');
require('./global.php');
class auth extends Auth_frm
{
	public function __construct()
	{
		parent::__construct();
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
		$this->verify_setting_prms();
		$offset = $this->input['offset']?intval(urldecode($this->input['offset'])):0;
		$count = $this->input['count']?intval(urldecode($this->input['count'])):10;
		$limit = " limit {$offset}, {$count}";
		$condition = $this->get_condition();
		$sql = "SELECT * FROM ".DB_PREFIX."authinfo  WHERE 1 ".$condition."  ORDER BY order_id DESC  ".$limit;
		$q = $this->db->query($sql);
		$this->setXmlNode('appinfo','item');
		while($r = $this->db->fetch_array($q))
		{
			//$r['status_name'] = $this->settings['auth_status'][$r['status']];
			$r['create_time'] = date('Y-m-d',$r['create_time']);
			$r['expire_time'] = $r['expire_time'] ? date('Y-m-d',$r['expire_time']) : $r['expire_time'];
			$this->addItem($r);
		}
		$this->output();
	}

	public function count()
	{
		$this->verify_setting_prms();
		$sql = 'SELECT count(*) as total from '.DB_PREFIX.'authinfo WHERE 1 '.$this->get_condition();
		$appinfo_total = $this->db->query_first($sql);
		echo json_encode($appinfo_total);
	}

	public function get_condition()
	{
		$condition = '';
		if($this->input['id'])
		{
			$condition .= ' AND id = '.intval($this->input['id']);
		}

		if($this->input['k'] || urldecode($this->input['k'])== '0')
		{
			$condition .= ' AND  custom_name LIKE "%'.trim(urldecode($this->input['k'])).'%"  OR custom_desc LIKE "%'.trim(urldecode($this->input['k'])).'%"   OR display_name LIKE "%'.trim(urldecode($this->input['k'])).'%" ';
		}

		if($this->input['start_time'])
		{
			$start_time = strtotime(trim(urldecode($this->input['start_time'])));
			$condition .= " AND create_time >= '".$start_time."'";
		}

		if($this->input['end_time'])
		{
			$end_time = strtotime(trim(urldecode($this->input['end_time'])));
			$condition .= " AND create_time <= '".$end_time."'";
		}

		if($this->input['date_search'])
		{
			$today = strtotime(date('Y-m-d'));
			$tomorrow = strtotime(date('y-m-d',TIMENOW+24*3600));
			switch(intval($this->input['date_search']))
			{
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
					$condition .= " AND  create_time > ".$last_sevenday." AND create_time < ".$tomorrow;
					break;
				default://所有时间段
					break;
			}
		}

		return $condition;
	}

	public function detail()
	{
		$this->verify_setting_prms();
		//编辑
		if($this->input['appid'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."authinfo WHERE appid = '".intval($this->input['appid'])."'";
			$ret = $this->db->query_first($sql);
			if ($ret['expire_time']<TIMENOW && $ret['expire_time'] != 0)
			{
				$ret['is_update'] = 1;
			}
			$ret['expire_time'] = $ret['expire_time'] ? date('Y-m-d',$ret['expire_time']) :$ret['expire_time'];
			$ret['create_time'] = date('Y-m-d',$ret['create_time']);
			$ret['update_time'] = date('Y-m-d',$ret['update_time']);
			//$ret['status_name'] = $this->settings['auth_status'][$ret['status']];
			$this->addItem($ret);
		}
		$this->output();
	}
	
	public function other_detail()
	{
		//编辑
		if($this->input['app_id'])
		{
			$sql = "SELECT * FROM ".DB_PREFIX."authinfo WHERE appid = '".intval($this->input['app_id'])."'";
			$ret = $this->db->query_first($sql);
			$ret['expire_time'] = $ret['expire_time'] ? date('Y-m-d',$ret['expire_time']) :$ret['expire_time'];
			$ret['create_time'] = date('Y-m-d',$ret['create_time']);
			$ret['update_time'] = date('Y-m-d',$ret['update_time']);
			//$ret['status_name'] = $this->settings['auth_status'][$ret['status']];
			$this->addItem($ret);
		}
		$this->output();
	}

	//验证客户有无权限创建应用
	public function verify_custom()
	{
		if(!$this->input['appid'])
		{
			$this->errorOutput('没有appid');
		}

		if(!$this->input['appkey'])
		{
			$this->errorOutput('没有appkey');
		}

		$sql = " SELECT * FROM " .DB_PREFIX. "authinfo WHERE appid = '".intval($this->input['appid'])."' AND appkey = '".urldecode($this->input['appkey'])."'";
		$ret = $this->db->query_first($sql);
		if($ret['appid'])
		{
			$this->addItem(array('is_have' => true));
		}
		else
		{
			$this->addItem(array('is_have' => false));
		}
		$this->output();
	}
}
$out = new auth();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>