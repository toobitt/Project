<?php
define('MOD_UNIQUEID','dingdone_user');
require_once('global.php');
require(ROOT_PATH.'lib/class/curl.class.php');
require_once(CUR_CONF_PATH . 'lib/dingdone_user_mode.php');
class dingdone_user_update extends adminUpdateBase
{
	private $mode;
    public function __construct()
	{
		parent::__construct();
		$this->mode = new dingdone_user_mode();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create(){}
	
	//更新账号只更新，推送的相关配置
	public function update()
	{
		$id = $this->input['id'];
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//获取配置参数
		$app_id 			= $this->input['app_id'];
		$app_key 			= $this->input['app_key'];
		$master_key 		= $this->input['master_key'];
		$push_accounts_id 	= $this->input['push_accounts_id'];
		$prov_id			= $this->input['prov_id'];
		$app_name			= $this->input['app_name'];
		
		if(!$app_id || !$app_key)
		{
			$this->errorOutput(NO_APP_CONFIG);
		}
		
		if(!$push_accounts_id)
		{
			$this->errorOutput(NO_ACCOUNT_ID);
		}

		$data = array(
			'app_id' 			=> $app_id,
			'app_key' 			=> $app_key,
			'master_key' 		=> $master_key,
			'prov_id' 			=> $prov_id,
			'app_name' 			=> $app_name,
			'push_accounts_id' 	=> $push_accounts_id,
			'user_id'			=> $id,
		);
		$ret = $this->mode->pushApiConfig($data);
		if($ret)
		{
			//更新状态
			if($this->input['push_status'])
			{
				$this->mode->update($id,array('push_status' => $this->input['push_status']));
			}
			
			$this->addItem('success');
			$this->output();
		}
	}
	
	/**
	 * 只跟新masterkey
	 */
	public function updateMasterkey()
	{
		$master_key = trim($this->input['master_key']);
        $prov_id = trim($this->input['prov_id']);
		$user_id = intval($this->input['user_id']);
		$update_array = array(
			'master_key' => $master_key,
		);
        if($prov_id)
        {
            $update_array['prov_id'] = $prov_id;
        }
		$ret = $this->mode->updateMasterkey($user_id,$update_array);
		if($ret)
		{
			$this->addItem('success');
		}
		$this->output();
	}
	
	/**
	 * 更新基本信息
	 */
	public function update_base_info()
	{
		$id = $this->input['id'];
		
		if(!$id)
		{
			$this->errorOutput(NOID);
		}
		
		//获取配置参数
		$is_business			= $this->input['is_business'];
		$permission			    = $this->input['permission'];
		$is_developer           = $this->input['is_developer'];
		$module_num             = $this->input['module_num'];
		$is_intest              = $this->input['is_intest'];
        $rename_android_package = $this->input['rename_android_package'];
		if(!$permission)
		{
			$permission = array();
		}
		//更新用户权限
		$this->mode->UpdatePermission($id,$permission);
		
		//更新会员最多新建模块数
		$userInfo = $this->mode->detail($this->input['id']);
		$this->editModuleNum($module_num,$userInfo['id']);
		
		//更新扩展字段相关设置数目
		$catalog_data = array(
			'list_ui_num'	=> $this->input['list_ui_num'] ? intval($this->input['list_ui_num']) : $this->settings['catalog_num_limit']['list_ui_num'],
			'radio_num'		=> $this->input['radio_num'] ? intval($this->input['radio_num']) : $this->settings['catalog_num_limit']['radio_num'],
			'price_num'		=> $this->input['price_num'] ? intval($this->input['price_num']) : $this->settings['catalog_num_limit']['price_num'],
			'time_num'		=> $this->input['time_num'] ? intval($this->input['time_num']) : $this->settings['catalog_num_limit']['time_num'],
			'content_ui_num'=> $this->input['content_ui_num'] ? intval($this->input['content_ui_num']) : $this->settings['catalog_num_limit']['content_ui_num'],
			'main_num'		=> $this->input['main_num'] ? intval($this->input['main_num']) : $this->settings['catalog_num_limit']['main_num'],
			'minor_num'		=> $this->input['minor_num'] ? intval($this->input['minor_num']) : $this->settings['catalog_num_limit']['minor_num'],	
			'user_id'		=> intval($this->input['id']),
		);
		
		$this->editCatalogNum($catalog_data);
		
        $userInfo_updateData = array(
            'is_business'               => $is_business,
            'dingdone_role_id'          => $is_developer,
            'is_intest'                 => $is_intest,
            'rename_android_package'    => $rename_android_package,
        );
		$res = $this->mode->update($id,$userInfo_updateData);
		if($res)
		{
			$this->addItem('success');
			$this->output();
		}		
			
	}
	
	/**
	 * 更新用户创建模块数
	 * 
	 *2014年12月5日
	 *return_type
	 */
	private function editModuleNum($module_num,$user_id)
	{
		if(!$this->input['module_num'])
		{
			return false;
		}
		$ret = '';
		if ($this->settings['App_app_plant'])
		{
			$this->curl = new curl($this->settings['App_app_plant']['host'], $this->settings['App_app_plant']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','editModuleNum');
			$this->curl->addRequestData('module_num', $module_num);
			$this->curl->addRequestData('user_id', $user_id);
			$ret = $this->curl->request('admin/app.php');
		}
		if(ret)
		{
			return json_encode(array('error' => '0', 'data' => $ret));
		}
		else
		{
			return json_encode(array('error' => '1','data' =>array()));
		}
	}
	
	/**
	 * 修改对应的扩展字段相关数量
	 * @param unknown $data
	 * @return boolean|string
	 */
	private function editCatalogNum($catalog_data = array())
	{
		if ($this->settings['App_app_plant'])
		{
			$this->curl = new curl($this->settings['App_app_plant']['host'], $this->settings['App_app_plant']['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a','editCatalogNum');
			$this->curl->addRequestData('list_ui_num', $catalog_data['list_ui_num']);
			$this->curl->addRequestData('radio_num', $catalog_data['radio_num']);
			$this->curl->addRequestData('time_num', $catalog_data['time_num']);
			$this->curl->addRequestData('price_num', $catalog_data['price_num']);
			$this->curl->addRequestData('content_ui_num', $catalog_data['content_ui_num']);
			$this->curl->addRequestData('main_num', $catalog_data['main_num']);
			$this->curl->addRequestData('minor_num', $catalog_data['minor_num']);
			$this->curl->addRequestData('user_id', $catalog_data['user_id']);
			$ret = $this->curl->request('admin/app.php');
		}
		if(ret)
		{
			return json_encode(array('error' => '0', 'data' => $ret));
		}
		else
		{
			return json_encode(array('error' => '1','data' =>array()));
		}
	}
	
	
	public function delete()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		
		$ret = $this->mode->delete($this->input['id']);
		if($ret)
		{
			$this->addItem('success');
			$this->output();
		}
	}
	
	public function audit()
	{
		if(!$this->input['id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->audit($this->input['id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}
	
	/**
	 * 根据用户id获取用户权限
	 */
	public function getPermissionByid()
	{
		if(!$this->input['user_id'])
		{
			$this->errorOutput(NOID);
		}
		$ret = $this->mode->getPermission($this->input['user_id']);
		if($ret)
		{
			$this->addItem($ret);
			$this->output();
		}
	}

	public function sort(){}
	public function publish(){}
	
	public function unknow()
	{
		$this->errorOutput(UNKNOW);
	}
}

$out = new dingdone_user_update();
if(!method_exists($out, $_INPUT['a']))
{
	$action = 'unknow';
}
else 
{
	$action = $_INPUT['a'];
}
$out->$action(); 
?>