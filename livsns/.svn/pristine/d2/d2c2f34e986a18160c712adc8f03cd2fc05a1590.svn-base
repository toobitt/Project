<?php
require('global.php');
define('MOD_UNIQUEID','memmodule');//模块标识
class memmodule_updateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/mcache.class.php');
		$this->obj = new mcache();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		
	}
	
	public function update()
	{
		$app = trim($this->input['app']);
		$module = trim($this->input['module']);
		if(!$app || !$module)
		{
			$this->errorOutput('NO_APP_MODULE');
		}
		$server = $this->input['server'];
		if(empty($server))
		{
			$this->obj->delete_relation_by_am($app,$module);
			@unlink(CUR_CONF_PATH.'data/'.$app.'_'.$module.'.ini.php');
		}
		else
		{
			//删除除这些服务器外的
			$this->obj->delete_relation_by_am($app,$module,implode(',',$server));
			
			$memcaches = $this->obj->get_memcaches(implode(',',$server),'id');
			$param_arr = array();
			foreach($server as $k=>$v)
			{
				if(intval($v)<=0)
				{
					continue;
				}
				$param_arr[$v]['persistent'] = trim($this->input[$v.'_persistent']);
				$param_arr[$v]['weight'] = intval($this->input[$v.'_weight']);
				$param_arr[$v]['timeout'] = intval($this->input[$v.'_timeout']);
				$param_arr[$v]['retry_interval'] = trim($this->input[$v.'_retry_interval']);
				$param_arr[$v]['status'] = trim($this->input[$v.'_status']);
				$param_arr[$v]['failure_callback'] = trim($this->input[$v.'_failure_callback']);
				$relation_data['memcache_id'] = $v;
				$relation_data['bundle_id'] = $app;
				$relation_data['module_id'] = $module;
				$relation_data['param'] = serialize($param_arr[$v]);
				$this->obj->replace_relation('memcache_relation',$relation_data);
				$param_arr[$v]['host'] = $memcaches[$v]['host'];
				$param_arr[$v]['port'] = $memcaches[$v]['port'];
			}
			$this->mk_ini($app,$module,$param_arr);
		}
	}
	
	public function memcache_relation($mo,$id)
	{
		foreach($mo as $k=>$v)
		{
			$am = explode('/',$v);
			$relation_data = array(
				'bundle_id' => $am[0],
				'module_id' => $am[1],
				'memcache_id' => $id,
			);
			$bundle_id_arr[] = $am[0];
			$module_id_arr[] = $am[1];
			$this->obj->replace_relation('memcache_relation',$relation_data);
		}
		$this->mk_memcache($bundle_id_arr,$module_id_arr);
	}
	
	public function mk_memcache($bundle_id_arr,$module_id_arr)
	{
		foreach($bundle_id_arr as $k=>$v)
		{
			$result = $this->obj->get_relation_by_am($v,$module_id_arr[$k]);
			$memcache_ids = empty($result['memcache_id'])?array():$result['memcache_id'];
		}
		if(!$memcache_ids)
		{
			return false;
		}
		$memcaches = $this->obj->get_memcaches(implode(',',$memcache_ids),'id');
		if(!$memcaches)
		{
			return false;
		}
		foreach($bundle_id_arr as $k=>$v)
		{
			if(empty($result['relation'][$v][$module_id_arr[$k]]))
			{
				continue;
			}
			$m_s = array();
			foreach($result['relation'][$v][$module_id_arr[$k]] as $kk=>$vv)
			{
				if($memcaches[$vv])
				{
					$m_s[] = $memcaches[$vv];
				}
			}
			$this->mk_ini($v,$module_id_arr[$k],$m_s);
		}
	}
	
	public function mk_ini($bundle_id,$module_id,$m_s)
	{
		if(!$m_s)
		{
			return false;
		}
		$content = '<?php 
				return ';
		$content .= var_export($m_s,1);
		$content .= ' ?>';
		file_in(CUR_CONF_PATH.'data',$bundle_id.'_'.$module_id.'.ini.php',$content,true,true);
	}
	
	public function delete()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$this->obj->delete_memcache($id);
		$this->obj->delete_relation($id);
	}
	
	public function audit(){}
	
	public function sort(){}
	
	public function publish(){}

}

$out = new memmodule_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			