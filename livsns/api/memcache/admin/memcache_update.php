<?php
require('global.php');
define('MOD_UNIQUEID','memcache');//模块标识
class memcache_updateApi extends adminUpdateBase
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
		$name = trim($this->input['name']);
		$index = $this->input['host'];
		$index_port = $this->input['port'];
		$is_open = $this->input['is_open'];
		
		$data = array(
			'name' => $name,
			'`host`' => $index,
			'`port`' => $index_port,
		);
		$id = $this->obj->insert('memcache',$data);
	}
	
	public function update()
	{
		$id = $this->input['id'];
		$name = trim($this->input['name']);
		$index = $this->input['host'];
		$index_port = $this->input['port'];
		$is_open = $this->input['is_open'];
		$data = array(
			'name' => $name,
			'`host`' => $index,
			'`port`' => $index_port,
		);
		$this->obj->update('memcache',$data,' AND id='.$id);
		//删除这个服务器上所有关联
//		$this->obj->delete_relation($id);
//		if($mo = $this->input['module_id'])
//		{
//			$this->memcache_relation($mo,$id);	
//		}
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

$out = new memcache_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			