<?php
/***************************************************************************
 * $Id: member_platform.php 42735 2014-12-12 10:00:50Z youzhenghuan $
 ***************************************************************************/
define('MOD_UNIQUEID','member_platform');//模块标识
require('./global.php');
class memberPlatformApi extends outerReadBase
{
	private $mMemberPlatform;
	public function __construct()
	{
		parent::__construct();

		require_once CUR_CONF_PATH . 'lib/member_platform.class.php';
		$this->mMemberPlatform = new memberPlatform();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 取出平台列表 已审核
	 * Enter description here ..
	 */
	public function show()
	{
	  $PlatForm = $this->getPlatform();
	  $this->setAddItemValueType();
	  $this->addItem($PlatForm);
	  $this->output();
	}
	
	/**
	 * 
	 * 获取平台信息和其它配置信息 ...
	 */
	public function getPlatAndConfig()
	{
		$re = array();
		$re['plat'] = $this->getPlatform();
		$re['config'] = $this->getConfig();
		$this->setAddItemValueType();
		$this->addItem($re);
		$this->output();
	}
	private function getPlatform()
	{
		if($this->input['version'] == CLIENT_VERSION)
		{
			return array();
		}
		$retInfo = array();
		$condition = $this->get_condition();
		$offset = $this->input['offset'] ? intval($this->input['offset']) : 0;
		$count  = $this->input['count'] ? intval($this->input['count']) : 20;
	  //屏蔽字段
	  //$filter = array('official_account', 'apikey','secretkey','callback');
		$field = 'id,name,mark,brief,logo_display,logo_login,limit_version,limit_appid';
		$this->mMemberPlatform->setSelectField($field);
		$info = $this->mMemberPlatform->show($condition, $offset, $count);
		if (!empty($info))
		{
			foreach ($info AS $v)
			{
				if(!$v['limit_appid'] || in_array($this->user['appid'],explode(',',$v['limit_appid'])))
				{
					if( !$v['limit_version']['min'] || trim($this->input['app_version']) >= $v['limit_version']['min'] )
					{
						if(!$v['limit_version']['max'] || trim($this->input['app_version']) <= $v['limit_version']['max'] )
						{

							$retInfo[] = $v;
								
						}
					}
				}
			}
		}
		return $retInfo;
	}
	
	/**
	 * 
	 * 获取客户端关闭配置 ...
	 */
	private function getConfig()
	{
		return array(
		'regConfig' => $this->regConfig(),
		'loginConfig' => $this->loginConfig(),
		);
	}
	
	private function regConfig()
	{
		$regConfig = $this->settings['regConfig'];
		$ArrAppid = dexplode($this->settings['closeRegTypeSwitchAppid'],3);
		if($regConfig['close'] && $ArrAppid && !in_array($this->user['appid'], $ArrAppid))
		{
			$regConfig['close'] = 0;
			$regConfig['url'] = '';
		}
		return $regConfig;
	}
	
	private function loginConfig()
	{
		$loginConfig = $this->settings['loginConfig'];
		$ArrAppid = dexplode($this->settings['closeLoginTypeSwitchAppid'],3);
		if($loginConfig['close'] && $ArrAppid && !in_array($this->user['appid'], $ArrAppid))
		{
			$loginConfig['close'] = 0;
			$loginConfig['url'] = '';
		}
		return $loginConfig;
	}

	public function detail()
	{
		$id = intval($this->input['id']);

		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}

		$condition 	 = $this->get_condition();

		//会员信息
		$member_platform = $this->mMemberPlatform->get_member_platform_info($condition);
		$member_platform = $member_platform[0];

		if (empty($member_platform))
		{
			$this->errorOutput(NO_RECORD);
		}

		$return = $member_platform;

		$this->addItem($return);
		$this->output();
	}

	public function count()
	{
		$condition = $this->get_condition();
		$info = $this->mMemberPlatform->count($condition);
		$this->addItem($info);
		$this->output();
	}

	private function get_condition()
	{
		$condition = " AND status = 1 ";

		if(isset($this->input['k']) && !empty($this->input['k']))
		{
			$condition .= ' AND ' . $binary . ' name like \'%'.trim($this->input['k']).'%\'';
		}

		if ($this->input['id'])
		{
			$condition .= " AND id IN (" . trim($this->input['id']) . ")";
		}

		//排除的平台标识,多个逗号隔开
		$exclude_sign = $this->input['exclude_sign'];
		if($exclude_sign)
		{
			$exclude_sign_arr = explode(',',$exclude_sign);
			foreach($exclude_sign_arr as $k=>$v)
			{
				$exclude_sign_arr[$k] = addslashes($v);
			}
			$condition .= " AND mark NOT IN ('".implode('\',\'',$exclude_sign_arr)."')";
		}

		return $condition;
	}

	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}
}

$out = new memberPlatformApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();
?>