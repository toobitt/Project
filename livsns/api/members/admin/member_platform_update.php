<?php
/***************************************************************************
* $Id: member_platform_update.php 41743 2014-11-19 01:56:28Z youzhenghuan $
***************************************************************************/
define('MOD_UNIQUEID','member_platform');//模块标识
require('./global.php');
class memberPlatformUpdateApi extends adminUpdateBase
{
	private $mMemberPlatform;
	public function __construct()
	{
		parent::__construct();
		$this->verify_setting_prms();
		require_once CUR_CONF_PATH . 'lib/member_platform.class.php';
		$this->mMemberPlatform = new memberPlatform();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	/**
	 * 
	 *  id 主键id
		order_id 排序id
		name 名称
		code 标识 唯一性
		brief 描述
		logo_display 显示图片
		logo_login 登录图片
		status 状态
		official_account 官方账号
		apikey apikey
		secretkey 密钥
		callback 回调函数
		org_id 组织id
		user_id 用户id
		user_name 用户名
		appid 应用id
		appname 应用名
		create_time 创建时间
		update_time 更新时间
		ip 创建者ip
		limit_version 限制版本号
	 */
	public function create()
	{
		$name				= trim($this->input['name']);
		$mark				= trim($this->input['mark']);
		$brief				= trim($this->input['brief']);
		$official_account	= trim($this->input['official_account']);
		$apikey				= trim($this->input['apikey']);
		$secretkey			= trim($this->input['secretkey']);
		$callback			= trim($this->input['callback']);
		$limit_version		= maybe_serialize($this->input['limit_version']);
		$limit_appids       = $this->input['limit_appid'];
		if($limit_appids && is_array($limit_appids))
		{
			$limit_appid = in_array(0,$limit_appids) ? $limit_appid = '' : implode(',',$limit_appids);
		}		
		
		if (!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if (!$mark)
		{
			$this->errorOutput(NO_MARK);
		}
		/*
		if (!$official_account)
		{
			$this->errorOutput('官方账号不能为空');
		}
		*/
		if (!$apikey)
		{
			$this->errorOutput(NO_APIKEY);
		}
		
		if (!$secretkey)
		{
			$this->errorOutput(NO_SECRETKEY);
		}
		
		if (!$callback)
		{
			$this->errorOutput(NO_CALLBACK);
		}
		
		//检测 mark 唯一性
		if ($this->mMemberPlatform->mark_exists($mark))
		{
			$this->errorOutput('[' . $mark . ']'.USES_MARK);
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
		
		$data = array(
			'name'					=> $name,
			'mark'					=> $mark,
			'brief'					=> $brief,
			'official_account'		=> $official_account,
			'apikey'				=> $apikey,
			'secretkey'				=> $secretkey,
			'callback'				=> $callback,
		    'limit_version'			=> $limit_version,
		    'limit_appid'			=> $limit_appid ? $limit_appid : '',
			'org_id'				=> $this->user['org_id'],
			'user_id'				=> $this->user['user_id'],
			'user_name'				=> $this->user['user_name'],
			'appid'					=> $this->user['appid'],
			'appname'				=> $this->user['display_name'],
			'create_time'			=> TIMENOW,
			'update_time'			=> TIMENOW,
			'ip'					=> hg_getip(),
		);
		
		$ret = $this->mMemberPlatform->create($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput(ADD_FAILED);
		}
		
		$id = $ret['id'];
		
		//logo_display
		if ($_FILES['logo_display']['tmp_name'])
		{
			$logo_display = $this->mMemberPlatform->add_material($_FILES['logo_display'], $id);
		}
		
		//logo_login
		if ($_FILES['logo_login']['tmp_name'])
		{
			$logo_login = $this->mMemberPlatform->add_material($_FILES['logo_login'], $id);
		}
		
		//更新 排序id、logo_display
		$update_data = array(
			'id'		 	=> $id,
			'order_id'	 	=> $id,
			'logo_display'	=> $logo_display ? serialize($logo_display) : '',
			'logo_login'	=> $logo_login ? serialize($logo_login) : '',
		);
		//更新数据
		$ret = $this->mMemberPlatform->update($update_data);
		
		$this->addItem($id);
		$this->output();
	}
	
	public function update()
	{
		$id 				= intval($this->input['id']);
		$name				= trim($this->input['name']);
		$mark				= trim($this->input['mark']);
		$brief				= trim($this->input['brief']);
		$official_account	= trim($this->input['official_account']);
		$apikey				= trim($this->input['apikey']);
		$secretkey			= trim($this->input['secretkey']);
		$callback			= trim($this->input['callback']);
		$limit_version		= maybe_serialize($this->input['limit_version']);
		$limit_appids       = $this->input['limit_appid'];
		if($limit_appids && is_array($limit_appids))
		{
			$limit_appid = in_array(0,$limit_appids) ? $limit_appid = '' : implode(',',$limit_appids);
		}
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		if (!$name)
		{
			$this->errorOutput(NO_NAME);
		}
		
		if (!$mark)
		{
			$this->errorOutput(NO_MARK);
		}
		/*
		if (!$official_account)
		{
			$this->errorOutput('官方账号不能为空');
		}
		*/
		if (!$apikey)
		{
			$this->errorOutput(NO_APIKEY);
		}
		
		if (!$secretkey)
		{
			$this->errorOutput(NO_SECRETKEY);
		}
		
		if (!$callback)
		{
			$this->errorOutput(NO_CALLBACK);
		}
		
		//检测 mark 唯一性
		$ret_mark = $this->mMemberPlatform->mark_exists($mark, $id);
		
		if (!empty($ret_mark))
		{
			$this->errorOutput(USES_MARK);
		}
		
		$condition  = " AND id = " . $id;
		$member_platform = $this->mMemberPlatform->get_member_platform_info($condition);
		$member_platform = $member_platform[0];
		
		if (empty($member_platform))
		{
			$this->errorOutput(NO_RECORD);
		}
		
		$brief = $brief == '这里输入描述' ? '' : $brief;
		
		$data = array(
			'id'					=> $id,
			'name'					=> $name,
			'mark'					=> $mark,
			'brief'					=> $brief,
			'official_account'		=> $official_account,
			'apikey'				=> $apikey,
			'secretkey'				=> $secretkey,
			'callback'				=> $callback,
		    'limit_version'			=> $limit_version,
		    'limit_appid'			=> $limit_appid ? $limit_appid : '',
		    'update_org_id'			=> $this->user['org_id'],
			'update_user_id'		=> $this->user['user_id'],
			'update_user_name'		=> $this->user['user_name'],
			'update_time'			=> TIMENOW,
		);
		
		$ret = $this->mMemberPlatform->update($data);
		
		if (!$ret['id'])
		{
			$this->errorOutput(UPDATE_FAILED);
		}
		
		//logo_display
		if ($_FILES['logo_display']['tmp_name'])
		{
			$logo_display = $this->mMemberPlatform->add_material($_FILES['logo_display'], $id);
		}
		
		//logo_login
		if ($_FILES['logo_login']['tmp_name'])
		{
			$logo_login = $this->mMemberPlatform->add_material($_FILES['logo_login'], $id);
		}
		
		if ($logo_display || $logo_login)
		{
			//更新 logo_display、logo_login
			$update_data = array(
				'id'			=> $id,
				'logo_display'	=> $logo_display ? serialize($logo_display) : '',
				'logo_login'	=> $logo_login ? serialize($logo_login) : '',
			);
			//更新数据
			$ret = $this->mMemberPlatform->update($update_data);
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function delete()
	{
		$id = trim($this->input['id']);
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$ret = $this->mMemberPlatform->delete($id);
		
		if (!$ret)
		{
			$this->errorOutput(DELETE_FAILED);
		}
		
		$this->addItem($id);
		$this->output();
	}
	
	public function audit()
	{
		$id = trim($this->input['id']);
		
		if (!$id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$field = 'id, status';
		$condition  = " AND id = " . $id;
		$member_platform = $this->mMemberPlatform->get_member_platform_info($condition, $field);
		$member_platform = $member_platform[0];
		
		if (empty($member_platform))
		{
			$this->errorOutput(NO_RECORD);
		}
		
		$status = $member_platform['status'];
		
		$ret = 0;
		
		$update_data = array(
			'id'	=> $id,
		);
		
		if (!$status) //启动
		{
			$update_data['status'] = 1;
			$ret = 1;
		}
		else	//停止
		{
			$update_data['status'] = 0;
			$ret = 2;
		}
		
		$this->mMemberPlatform->update($update_data);
		
		$this->addItem($ret);
		$this->output();
	}
	
	public function sort()
	{
		$content_id = $this->input['content_id'];
		$order_id 	= $this->input['order_id'];
		if(!$content_id)
		{
			$this->errorOutput(NO_DATA_ID);
		}
		
		$ret = $this->drag_order('member_platform', 'order_id');
	
		$this->addItem($ret);
		$this->output();
	}
	
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(NO_ACTION);
	}

}

$out = new memberPlatformUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>