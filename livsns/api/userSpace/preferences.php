<?php
require_once('./global.php');
define('SCRIPT_NAME', 'preferences');
define('MOD_UNIQUEID','preferences');
include_once ROOT_PATH . 'lib/class/curl.class.php';
class preferences extends adminBase
{
	public function __construct()
	{
		$this->getUserExtendInfo = true;
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show()
	{
		
		$module = $this->input['m'];
		if(!in_array($module, array('player', 'application', 'developer')))
		{
			return;
		}
		$action =  $this->input['action'];
		
		if($action && !in_array($action, array('create', 'update', 'delete', 'update_client_id', 'get_app_info')))
		{
			return;
		}
		
		$action && ($method = $action . '_' . $module);

		if(method_exists($this, $method))
		{
			$data = $this->$method();			
			$this->addItem($data[0] ? $data[0] : $data);
			$this->output();
		}
		$param = array(
			'flag'=>$module,
			'admin_id'=>$this->user['user_id'],
		);
		foreach ($this->input as $k=>$v)
		{
			$param[$k] = $v;
		}
		$data = $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences.php', $param);
		if(is_array($data) && !empty($data))
		{
			foreach($data as $val)
			{
				$this->addItem($val);
			}
		}
		$this->output();
	}
	protected function create_developer($id = 0)
	{
		$developer = array(
				'title'=>$this->input['developer_name'],
				'flag'=>'developer',
				'ip'=>$this->input['ip'],
				'admin_settings' =>$this->input['admin_settings'],
				'status'=>1,//提交待审核
				'id'=>$id,
				'html'=>1,//处理json字符问题
			);
		$id ? $this->addLogs('更新开发者信息', null, $developer, $developer['title']) : $this->addLogs('申请开发者', null, $developer, $developer['title']);
			
		return $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences_update.php', $developer);
	}
	protected function update_developer()
	{
		return $this->update('developer');
	}
	protected function get_app_info_application()
	{
		$formdata = array(
			'search_field'=>'client_id',
			'flag'=>'application',
			'client_id'=>$this->input['client_id'],
			'a'=>'get_specify_settings',
			);
		
		return $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']  . 'admin/', 'preferences.php', $formdata);
	}
	protected function create_player($id = 0)
	{
		$data = array(
			'version'=>'',
			'c'=> $this->settings['player']['config_xml_prefix'] . $this->user['user_id'],
			'customer_name'=>$this->input['site_name'],
			//'domain'=>$this->input['site_domain'],
			'domain'=>CLOUD_VIDEO_DOMIAN,
			'format'=>'string',
			'ui'=>intval($this->input['ui']),//皮肤
			//'otherMode'=>intval($this->input['otherMode']),
			'recommendTitle'=>urldecode($this->input['recommendTitle']),
			'siteurl'=>'http://www.' . $this->input['site_domain'],
			'api'=>$this->input['api'] ? $this->input['api'] :  $this->settings['player']['default_player_api'],
		);
		if(!$data['customer_name'])
		{
			$error = 1;
			$message = '缺少站点名称';
		}
		if(!$data['domain'])
		{
			$error = 1;
			$message = '缺少站点域名';
		}
		if(is_array($this->input['colors']) && !empty($this->input['colors']))
		{
			$colors = '';
			foreach($this->input['colors'] as $k=>$c)
			{
				$colors .= hexdec(trim($c, '#')) . ',';
			}
			$data['colors'] = trim($colors,',');
		}
		if(is_array($this->input['otherMode']) && !empty($this->input['otherMode']))
		{
			$data['otherMode'] = array_sum($this->input['otherMode']);
		}
		//file_put_contents(CACHE_DIR . 'debug.txt', var_export($data,1));
		if($error)
		{
			return array('error'=>$error, 'message'=>$message);	
		}
		$admin_settings = array(
			'site_name'=>$data['customer_name'],
			'site_domain'=>$data['domain'],
			'source_id'=>intval($this->input['source_id']),
			'ui'=>$this->input['ui'],
			'player_width'=>intval($this->input['player_width']),
			'player_height'=>intval($this->input['player_height']),
			'auto_play'=>intval($this->input['auto_play']),
			'recommendTitle'=>$data['recommendTitle'],
			'otherMode'=>$this->input['otherMode'],
			'colors'=>$this->input['colors'],
		);
		$player = array(
			'title'=>$this->input['player_name'],
			'flag'=>'player',
			'ip'=>$this->input['ip'],
			'admin_settings' =>json_encode($admin_settings),
			'html'=>1,//处理json字符问题
			'status'=>$admin_settings['source_id'] ? 0 : intval($this->input['status']),
			'status_relation'=>$admin_settings['source_id'] ? '' : 'mutex',
			'id'=>$id ? $id : 0,
		);
		$id ? $this->addLogs('更新播放器', null, $player, $player['title']) : $this->addLogs('创建播放器', null, $player, $player['title']);
		$reponses = $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences_update.php', $player);
		
		$reponses = $reponses[0];
		
		$data['c'] = $data['c'] . '_' . $reponses['id'];
		$message = $reponses;
		
		$reponses = $this->httpcall($this->settings['playerapi']['host'], $this->settings['playerapi']['dir'], 'create.php', $data);
		if($reponses['error'])
		{
			$error = 1;
			$message = '播放器操作失败，请重试';
		}
		else
		{
			$error = 0;
			$message = '播放器操作成功';
		}
		return array('error'=>$error,'info'=>$message);
		
	}
	protected function create_application($id = 0)
	{
		$id = intval($this->input['id']);
		//如果是创建检测支持多少个应用
		if(!$id)
		{
			$condit = array(
			'flag'=>'application',
			'admin_id'=>$this->user['user_id'],
			'a'=>'count',
			);
			$total = $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences.php', $condit);
			if($total['total'] >= $this->user['extend']['dev_app']['value'])
			{
				return array('error'=>1, 'message'=>'应用创建个数上限', 'field'=>'privilege');
			}
		}
		$data = array(
			'app_name'=>$this->input['app_name'],
			'app_domain'=>$this->input['app_domain'],
			'app_logo'=>$this->input['app_logo'],
			'callback_url'=>$this->input['callback_url'],		
			'status'=>1,
		);
		foreach($data as $key=>$val)
		{
			if(!$val)
			{
				return array('error'=>1, 'message'=>'必须', 'field'=>$key);
				break;
			}
		}
		if(!$id)
		{
			$data['client_id'] = $this->mk_appkey();
		}
		
		$appinfo = array(
				'title'=>$data['app_name'],
				'flag'=>'application',
				'ip'=>$this->input['ip'],
				'admin_settings' =>json_encode($data),
				'id'=>$id,
				'html'=>1,//处理json字符问题
		);
		if(!$id)
		{
			$appinfo['search_field'] = 'client_id';
			$appinfo['client_id'] = $data['client_id'];
		}
		$id ? $this->addLogs('更新应用', null, $appinfo, $appinfo['title']) : $this->addLogs('创建应用', null, $appinfo, $appinfo['title']);
		return $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences_update.php', $appinfo);		
	}
	protected function delete($module)
	{
		if(!in_array($module, array('player', 'application')))
		{
			return array('error'=>1, 'message'=>'未知模块');
		}
		$data = array(
			'id'=>$this->input['id'],
			'flag'=>$module,
			'a'=>'delete',
		);
		$info = array(
			'id'=>$this->input['id'],
			'flag'=>$module,
			'a'=>'show',
		);
		$info = $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences.php', $info);
		$info = $info[0];
		$this->addLogs('删除信息' , $info, null, $info['title']);
		return $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences_update.php', $data);
	}
	
	protected function delete_application()
	{
		return $this->delete('application');
	}
	protected function delete_player()
	{
		return $this->delete('player');
	}
	
	/*更新*/
	protected function update($module)
	{
		$id  = intval($this->input['id']);
		
		if(!$id)
		{
			return array('error'=>1, 'message'=>'无效id');
			
		}
		return $this->{'create_' . $module}($id);
		
	}
	protected function update_player()
	{
		return $this->update('player');
	}
	protected function update_application()
	{
		return $this->update('application');
	}
	/*更新*/
	protected function update_client_id_application()
	{
		$data = array(
			'id'=>intval($this->input['id']),
			'update_field'=>'client_id',
			'is_search_field'=>1,
			'flag'=>'application',
			'update_value'=>$this->mk_appkey(),
			'a'=>'update_specify_field',
		);
		$this->addLogs('刷新密钥', null, $data, '应用id['.$data['id'].']');
		return $this->update_specify_field($data);
	}
	protected function update_specify_field($formdata)
	{
		return $this->httpcall($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/', 'preferences_update.php', $formdata);
	}
	protected function httpcall($host, $dir, $file, $postdata)
	{
		$curl = new curl($host, $dir);
		$curl->initPostData();
		$curl->setSubmitType('post');
		foreach($postdata as $k=>$v)
		{
			$curl->addRequestData($k, $v);
		}
		return $curl->request($file);
	}
	private function mk_appkey()
	{
		return md5($this->user['user_id'] . time() . rand(1, time()));
	}
}
include(ROOT_PATH . 'excute.php');