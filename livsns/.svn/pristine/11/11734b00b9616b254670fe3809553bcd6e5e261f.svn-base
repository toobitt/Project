<?php
/***************************************************************************
* $Id: mobile_module_update.php 11744 2012-09-22 09:24:58Z lijiaying $
***************************************************************************/
define('MOD_UNIQUEID', 'mobile_module');
require('global.php');
class mobileModuleUpdateApi extends adminUpdateBase
{
	private $mMobileModule;
	public function __construct()
	{
		parent::__construct();
		include_once CUR_CONF_PATH . 'lib/mobile_module.class.php';
		$this->mMobileModule = new mobileModule();
	}

	public function __destruct()
	{
		parent::__destruct();
	}

	function sort()
	{
		
	}
	function publish()
	{
		
	}
	public function create()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		$type = trim(urldecode($this->input['type']));
		if (!$type)
		{
			$this->errorOutput('请选择类型');
		}
		
		$main_url = trim(urldecode($this->input['main_url']));
		$module_id = trim(urldecode($this->input['module_id']));
		
		$version_url = array();
		if($this->input['version_url'])
		{
			foreach ($this->input['version_url'] as $k => $v)
			{
				$version_url[$v] = trim($this->input['url_ver'][$k]);
			}
		}
		if($version_url)
		{
			$version_url = serialize($version_url);
		}
		
		$version_url = $version_url ? $version_url : '';
		
		$sort_id = intval($this->input['sort_id']);
		
		$res = $this->sole_module_id($module_id,$sort_id);
		if($res)
		{
			$this->errorOutput('模块id已经存在');
		}
		
		$event = array();
		
		if($this->input['event_bs'])
		{
			foreach ($this->input['event_bs'] as $k => $v)
			{
				if(!$v)
				{
					continue;
				}
				$event[$v] = array(
					'outlink' => $this->input['outlink'][$k],
					'tip'		=> $this->input['tip'][$k],
				);
			}
		}
		if(!empty($event))
		{
			$event = serialize($event);
		}
		
		$event = $event ? $event : '';
		
		
		$input_info = array(
			'name' 			=> $name,
			'type' 			=> $type,
			'url'		 	=> $main_url,
			'version_url'	=> $version_url,
			'module_id' 	=> $module_id,
			'appid' 		=> $this->user['appid'],
			'appname'		=> $this->user['display_name'],
			'user_id' 		=> $this->user['user_id'],
			'user_name' 	=> $this->user['user_name'],
			'ip'			=> hg_getip(),
			'sort_id'		=> $sort_id,
			'brief'			=> $this->input['brief'],
			'event'			=> $event,
		);
		
		$input_file = $_FILES;
		
		$info = $this->mMobileModule->create($input_info, $input_file);
		
		if (!$info)
		{
			$this->errorOutput('添加失败');
		}
		$this->addItem($info);
		$this->output();
	}
	
	public function update()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$name = trim(urldecode($this->input['name']));
		if (!$name)
		{
			$this->errorOutput('名称不能为空');
		}
		
		$type = trim(urldecode($this->input['type']));
		if (!$type)
		{
			$this->errorOutput('请选择类型');
		}
		
		$main_url = trim(urldecode($this->input['main_url']));
		$module_id = trim(urldecode($this->input['module_id']));
		
		
		
		if($module_id)
		{
			$sql = "SELECT module_id,sort_id FROM ".DB_PREFIX."mobile_module WHERE id = ".$id;
			$res = $this->db->query_first($sql);
			//同一分类下，module_id改变了再判断module_id是否存在
			if($res['sort_id'] == $this->input['sort_id'])
			{
				if($this->input['module_id'] != $res['module_id'])
				{
					$ret = $this->sole_module_id($this->input['module_id'],$res['sort_id']);
					if($ret)
					{
						$this->errorOutput('module_id已经存在');
					}
				}
			}
			else //分类改变，判断新分类下是否存在传递进来的module_id
			{
				$ret = $this->sole_module_id($this->input['module_id'],$this->input['sort_id']);
				if($ret)
				{
					$this->errorOutput('module_id已经存在');
				}
			}
			
		}
		$version_url = array();
		if($this->input['version_url'])
		{
			foreach ($this->input['version_url'] as $k => $v)
			{
				$version_url[$v] = trim($this->input['url_ver'][$k]);
			}
		}
		if($version_url)
		{
			$version_url = serialize($version_url);
		}
		
		$version_url = $version_url ? $version_url : '';
		
		
		
		$event = array();
		
		if($this->input['event_bs'])
		{
			foreach ($this->input['event_bs'] as $k => $v)
			{
				if(!$v)
				{
					continue;
				}
				$event[$v] = array(
					'outlink' => $this->input['outlink'][$k],
					'tip'		=> $this->input['tip'][$k],
				);
			}
		}
		if(!empty($event))
		{
			$event = serialize($event);
		}
		
		$event = $event ? $event : '';
		
		$input_info = array(
			'name' 			=> $name,
			'type' 			=> $type,
			'url' 			=> $main_url,
			'version_url'	=> $version_url,
			'module_id' 	=> $module_id,
			'sort_id'		=> intval($this->input['sort_id']),
			'brief'			=> $this->input['brief'],
			'event'			=> $event,
		);
		
		$input_file = $_FILES;
		$info = $this->mMobileModule->update($input_info, $id, $input_file);
		
		if (!$info)
		{
			$this->errorOutput('更新失败');
		}
		$this->addItem($info);
		$this->output();
	}
	
	function delete()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = urldecode($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入id');
		}
		
		$info = $this->mMobileModule->delete($id);
		
		if (!$info)
		{
			$this->errorOutput('删除失败');
		}
		$this->addItem($id);
		$this->output();
	}

	public function audit()
	{
		//检测是否具有配置权限
	    if ($this->user['group_type'] > MAX_ADMIN_TYPE)
	    {
		    if(!$this->user['prms']['app_prms'][APP_UNIQUEID]['setting'])
	        {
	        	$this->errorOutput(NO_PRIVILEGE);
	        }
	    }
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->errorOutput('未传入ID');
		}
		
		$type = trim(urldecode($this->input['type']));
		if (!$type)
		{
			$this->errorOutput('请传入要审核的字段');
		}
		
		$table = 'mobile_module';
		
		$info = $this->mMobileModule->audit($table, $id, $type);
		$this->addItem($info);
		$this->output();
	}
	
	/**
	 * 
	 * 判断分类下模块标识是否重复
	 * @param string $module_id 模块标识
	 * @param int $sort_id 分类id
	 */
	function sole_module_id($module_id,$sort_id = '')
	{
		if(!$module_id)
		{
			return false;
		}
		
		$sql = "SELECT module_id FROM ".DB_PREFIX."mobile_module WHERE module_id = '".$module_id."'";
		
		if($sort_id)
		{
			$sql .= " AND sort_id = ".$sort_id;
		}
		$res = $this->db->query_first($sql);
		if($res['module_id'])
		{
			return true;
		}
	}
	function unknow()
	{
		$this->errorOutput('未被实现的空方法');
	}
}

$out = new mobileModuleUpdateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'unknow';
}
$out->$action();
?>