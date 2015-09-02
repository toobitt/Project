<?php
require('global.php');
define('MOD_UNIQUEID','textsearch');//模块标识
class textsearch_updateApi extends adminUpdateBase
{
	public function __construct()
	{
		parent::__construct();
		include(CUR_CONF_PATH . 'lib/textsearch.class.php');
		$this->obj = new textsearch();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function create()
	{
		$name = trim($this->input['name']);
		$index = $this->input['index'];
		$index_port = $this->input['index_port'];
		$search = $this->input['search'];
		$search_port = $this->input['search_port'];
		$is_open = $this->input['is_open'];
		
		$data = array(
			'name' => $name,
			'`index`' => $index,
			'`index_port`' => $index_port,
			'search' => $search,
			'search_port' => $search_port,
		);
		$id = $this->obj->insert('db',$data);
		
		if($mo = $this->input['module_id'])
		{
			$this->db_relation($mo,$id,$index,$index_port,$search,$search_port);	
		}
	}
	
	public function update()
	{
		$id = $this->input['id'];
		$name = trim($this->input['name']);
		$index = $this->input['index'];
		$index_port = $this->input['index_port'];
		$search = $this->input['search'];
		$search_port = $this->input['search_port'];
		$is_open = $this->input['is_open'];
		$data = array(
			'name' => $name,
			'`index`' => $index,
			'`index_port`' => $index_port,
			'search' => $search,
			'search_port' => $search_port,
		);
		$this->obj->update('db',$data,' AND id='.$id);
		//删除这个服务器上所有关联
		$this->obj->delete_relation($id);
		if($mo = $this->input['module_id'])
		{
			$this->db_relation($mo,$id,$index,$index_port,$search,$search_port);	
		}
	}
	
	public function db_relation($mo,$id,$index,$index_port,$search,$search_port)
	{
		foreach($mo as $k=>$v)
		{
			$am = explode('/',$v);
			$relation_data = array(
				'bundle_id' => $am[0],
				'module_id' => $am[1],
				'db_id' => $id,
			);
			$bundle_id_arr[] = $am[0];
			$module_id_arr[] = $am[1];
			$this->obj->replace_relation('db_relation',$relation_data);
		}
		//获取支持全文检索的应用模块
		include_once(ROOT_PATH.'lib/class/auth.class.php');
		$auth = new auth();
		$apps = $auth->get_app('',implode(',',$bundle_id_arr),'',0,1000,array('use_textsearch'=>1));
		$modules = $auth->get_module('','','','',0,1000,array('mod_uniqueid'=>implode(',',$module_id_arr)));
		foreach($apps as $k=>$v)
		{
			$a[$v['bundle']] = $v;
		}
		foreach($modules as $k=>$v)
		{
			$m[$v['mod_uniqueid']] = $v;
		}
		
		//获取各个系统配置文件
		foreach($bundle_id_arr as $k=>$v)
		{
			if($m[$module_id_arr[$k]]['host'])
			{
				//$m[$module_id_arr[$k]]['host'] = ltrim($m[$module_id_arr[$k]]['host'],'http://');
				$m[$module_id_arr[$k]]['host'] = rtrim($m[$module_id_arr[$k]]['host'],'/');
				$content = file_get_contents('http://'.$m[$module_id_arr[$k]]['host'].'/'.$m[$module_id_arr[$k]]['dir'].'conf/'.$v.'_'.$module_id_arr[$k].'.ini');
			}
			else
			{
				//$a[$module_id_arr[$k]]['host'] = ltrim($a[$module_id_arr[$k]]['host'],'http://');
				$a[$module_id_arr[$k]]['host'] = rtrim($a[$module_id_arr[$k]]['host'],'/');
				$content = file_get_contents('http://'.$a[$module_id_arr[$k]]['host'].'/'.$a[$module_id_arr[$k]]['dir'].'conf/'.$v.'_'.$module_id_arr[$k].'.ini');
			}
			//生成对应文件
			if($content)
			{
				$this->mk_ini($v,$module_id_arr[$k],'utf-8',$content,$index,$index_port,$search,$search_port);
			}
		}	
	}
	
	public function mk_ini($bundle_id,$module_id,$charset,$content,$index='127.0.0.1',$index_port='8383',$search='127.0.0.1',$search_port='8384')
	{
		$head  = '';
		$head .= 'project.name = '.$bundle_id.'_'.$module_id.'
';
		$head .= 'project.default_charset = '.$charset.'
';
		$head .= 'server.index = '.$index.':'.$index_port.'
';
		$head .= 'server.search = '.$search.':'.$search_port.'

';
		file_in(CUR_CONF_PATH.'data',$bundle_id.'_'.$module_id.'.ini',$head.$content,true,true);
	}
	
	public function delete()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput('NO_ID');
		}
		$this->obj->delete_db($id);
		$this->obj->delete_relation($id);
	}
	
	public function audit(){}
	
	public function sort(){}
	
	public function publish(){}

}

$out = new textsearch_updateApi();
$action = $_INPUT['a'];
if (!method_exists($out,$action))
{
	$action = 'show';
}
$out->$action();

?>


			