<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'application');
require('./global.php');
require('./lib/class/curl.class.php');
class application extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理系统!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'applications ORDER BY id desc';
		$q = $this->db->query($sql);
		$applications = array();
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			$row['api'] = 'http://' . $row['host'] . '/' . $row['dir'];
			$applications[$row['id']] = $row;
			$applications_relate[$row['father_id']][] = $row['id'];
		}
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[name]'),
			'softvar' => array('title' => '标识', 'exper' => '$v[softvar]'),
			'api' => array('title' => '接口', 'exper' => '$v[api]'),
			'create_time' => array('title' => '创建时间', 'exper' => '$v[create_time]')
			);
		$op = array(
			'form' => array(
				'name' =>'编辑', 
				'brief' =>'',
				'link' => '?a=form'),
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'删除\', 1);"',
				'link' => '?a=delete'),
			'see_module' => array(
				'name' =>'查看模块', 
				'brief' =>'',
				'link' => 'modules.php?a=show'),
			'export_xml' => array(
				'name' =>'导出应用xml',
				'brief' =>'',
				'link' => '?a=export_xml'),
			'app_publish' => array(
				'name' =>'发布至应用商店',
				'brief' =>'',
				'link' => '?a=app_publish'),
			);
		$batch_op = array(
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'delete\', \'删除\', 1,\'\',\'\',\'ajax\');"',
				),
			); 
		$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('applications_relate', $applications_relate);
		$this->tpl->outTemplate('application');
	}

	public function form($message = '')
	{
		$id = intval($this->input['id']);
		$this->cache->check_cache('applications');
		$applications = array(0 => '无');
		foreach ($this->cache->cache['applications'] AS $k => $v)
		{
			if ($v['father_id'])
			{
				continue;
			}
			$applications[$k] = $v['name'];
		}
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'applications WHERE id=' . $id);
			unset($applications[$id]);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$a = 'update';
			$optext = '更新';
		}
		else
		{
			/*验证客户有无权限创建应用*/
			if(!$this->verify_custom())
			{
				$this->ReportError('您无权限创建应用');
			}
			
			$formdata = $this->input;
			$a = 'create';
			$optext = '添加';
		}
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('a', $a);
		//$this->tpl->outTemplate('app_form');
		$this->tpl->outTemplate('app_form_list');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}
		if (!$this->check_unique())
		{
			$this->form('标识必须唯一');
		}
		
		/**********************申请appkey与appid开始*********************/
		/*
		$app_data = array(
			'custom_name' => trim(urldecode($this->input['name'])),
			'custom_desc' => urldecode($this->input['brief']),
			'bundle_id' => urldecode($this->input['softvar']),
		);
		
		$app = $this->request_appkey($app_data);
		if(!$app['appid'])
		{
			$this->ReportError('申请appkey失败');
		}
		*/
		/**********************申请appkey与appid结束*********************/
		
		//检测是否添加过
		$data = array(
			'name' => $name, 	
			'softvar' => $this->input['softvar'], 	
			'brief' => $this->input['brief'], 	
			'token' => $this->input['token'], 	
			'logo' => $this->input['logo'], 	
			'father_id' => $this->input['father_id'], 	
			'dir' => $this->input['dir'], 	
			'host' => $this->input['host'], 	
			'order_id' => $this->input['order_id'],
			'create_time' => TIMENOW,
		//	'need_auth'	=> $this->input['need_auth'],
		/*
			'appid' => $app['appid'],
			'appkey' =>  $app['appkey'],
		*/
		);
		hg_fetch_query_sql($data, 'applications');
		$this->syn_auth_app($this->db->insert_id());
		$this->cache->recache('applications');
		$this->redirect('添加成功');
	}
	protected function syn_auth_app($id = '', $op='update')
	{
		if($op == 'update')
		{
			$sql = 'SELECT id,name,softvar as bundle, dir,admin_dir,host,port FROM '.DB_PREFIX.'applications WHERE id = '.intval($id);
			$app = $this->db->query_first($sql);
			if($app)
			{
				$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
				$curl->initPostData();
				foreach ($app as $field=>$value)
				{
					if ($field == 'dir')
					{
						$value = str_replace('admin/', '', $value);
					}
					$curl->addRequestData($field, $value);
				}
				$syn_auth = $curl->request('apps.php');
				if($syn_auth['ErrorCode'] || !is_array($syn_auth[0]))
				{	
					$this->db->query('DELETE FROM '.DB_PREFIX.'applications WHERE id = '.intval($id));
					$this->ReportError(SYN_AUTH_ERROR);
				}
			}
		}
		if($op == 'delete')
		{
			$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
			$curl->initPostData();
			$curl->addRequestData('a', 'delete');
			$curl->addRequestData('id', $id);
			$curl->request('apps.php');
		}
	}
	public function update()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}
		if (!$this->check_unique(1))
		{
			$this->form('标识必须唯一');
		}
		//检测是否添加过
		$data = array(
			'name' => $name, 	
			'softvar' => $this->input['softvar'],
			'logo' => $this->input['logo'],
			'brief' => $this->input['brief'], 	
			'token' => $this->input['token'], 	
			'dir' => $this->input['dir'], 	
			'father_id' => $this->input['father_id'], 
			'host' => $this->input['host'], 	
			'order_id' => $this->input['order_id'],
		//	'need_auth'	=> $this->input['need_auth'],	
		);
		hg_fetch_query_sql($data, 'applications', 'id=' . $id);
		$this->syn_auth_app($id);
		$this->cache->recache('applications');
		$this->rebuild_program($id);
		$this->redirect('更新成功');
	}
	
	public function delete()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$id = explode(',', $id);
		$ids = array();
		foreach ($id AS $v)
		{
			if ($v)
			{
				$ids[] = $v;
			}
		}
		if ($ids)
		{
			$ids = implode(',', $ids);
			$sql = 'DELETE FROM ' . DB_PREFIX . 'applications WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->syn_auth_app($ids, 'delete');
			$this->cache->recache('applications');
			$this->redirect('成功删除' . $affect_rows . '条记录', 0, 0, '', 'hg_remove_row("' . $ids . '")');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}

	private function check_unique($num = 0, $field = 'softvar')
	{
		$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'applications WHERE ' . $field . '=\'' . $this->input[$field] . "'";
		$row = $this->db->query_first($sql);
		if ($row['total'] > $num)
		{
			return false;
		}
		return true;
	}
	
	private function rebuild_program($id)
	{
			include(ROOT_PATH . 'lib/class/program.class.php');
			$program = new program();
			$program->rebuild_program($id);
	}
	
	/*验证客户有无权限创建应用*/
	private function verify_custom()
	{
		$curl = new curl($this->settings['verify_custom_api']['host'], $this->settings['verify_custom_api']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','verify_custom');
		$curl->addRequestData('appid',CUSTOM_APPID);
		$curl->addRequestData('appkey',CUSTOM_APPKEY);
		$return = $curl->request('auth.php');
		return $return[0]['is_have']?true:false;
	}
	
	private function request_appkey($data)
	{
		$curl = new curl($this->settings['request_appkey_api']['host'], $this->settings['request_appkey_api']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		foreach($data AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$return = $curl->request('auth_update.php');
		if($return[0]['appid'])
		{
			return array('appid' => $return[0]['appid'],'appkey' => $return[0]['appkey']);
		}
		else 
		{
			return array('appid' => 0,'appkey' => 0);
		}
	}
	
	public function export_xml()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	
		$sql = "SELECT * FROM " . DB_PREFIX . "applications WHERE id = {$id}";
		$app = $this->db->query_first($sql);
		
		//应用的基本信息
		$item_arr = array(
			'name' 			=> $app['name'],
			'uniqueid' 		=> $app['softvar'],
			'version' 		=> $app['version'],
			'type' 			=> $app['type'],
			'description' 	=> $app['brief'],
			'adminDir' 		=> $app['admin_dir'],
			'host' 			=> $app['host'],
			'dir' 			=> $app['dir'],
		);
		
		//目录信息
		$dir_arr = array(
			'dirAttr' => array('dir' => 'conf/','attr' => '0777'),
		);
		
		//基本信息xml
		$dom = new DOMDocument('1.0', 'utf-8');
		$dom->formatOutput = true;//格式化输出
		
		$config = $dom->createElement('configs');
		foreach($item_arr AS $k => $v)
		{
			$item = $dom->createElement($k);
			$text = $dom->createTextNode($v);
			$item->appendchild($text);
			$config->appendchild($item);
		}
		
		//目录信息xml
		foreach($dir_arr AS $k => $v)
		{
			$item = $dom->createElement($k);
			foreach($v AS $key => $value)
			{
				$c_item = $dom->createElement($key);
				$c_text = $dom->createTextNode($value);
				$c_item->appendchild($c_text);
				$item->appendchild($c_item);
			}
			$config->appendchild($item);
		}
		
		/*************************************************查询出配置开始******************************************/
		$appConfigInfo = $this->get_api_config($app);
		if($appConfigInfo)
		{
			//数据库的配置部分
			if($appConfigInfo['gdb']['value'])
			{
				$gdb = $dom->createElement('gDBconfig');
				foreach ($appConfigInfo['gdb']['value'] AS $k => $v)
				{
					$c_item = $dom->createElement($k);
					$c_text = $dom->createTextNode($v);
					$c_item->appendchild($c_text);
					$gdb->appendchild($c_item);
				}
				$config->appendchild($gdb);
			}
			
			//全局配置与常量部分
			$g_global = $dom->createElement('gGlobalConfig');
			
			//常量部分
			if($appConfigInfo['gdefine'])
			{
				foreach ($appConfigInfo['gdefine'] AS $k => $v)
				{
					$c_item_key = $dom->createElement('key');
					$is_edit = $v['is_edit']?'true':'false';
					$c_item_key->setAttribute('candefine',$is_edit);
					$c_item_key->setAttribute('const','true');
					$c_text_key = $dom->createTextNode($v['var_name']);
					$c_item_key->appendchild($c_text_key);
					
					$c_item_desc = $dom->createElement('desc');
					$c_text_desc = $dom->createTextNode('');
					$c_item_desc->appendchild($c_text_desc);
					
					$c_item_str = $dom->createElement('string');
					$c_text_str = $dom->createTextNode($v['value']);
					$c_item_str->appendchild($c_text_str);
					
					$g_global->appendchild($c_item_key);
					$g_global->appendchild($c_item_desc);
					$g_global->appendchild($c_item_str);
				}
			}
			
			//全局部分
			if($appConfigInfo['gglobal'])
			{
				foreach ($appConfigInfo['gglobal'] AS $v)
				{
					$c_item_key = $dom->createElement('key');
					$is_edit = $v['is_edit']?'true':'false';
					$c_item_key->setAttribute('candefine',$is_edit);
					$c_item_key->setAttribute('const','false');
					$c_text_key = $dom->createTextNode($v['var_name']);
					$c_item_key->appendchild($c_text_key);
					$g_global->appendchild($c_item_key);
					
					$c_item_desc = $dom->createElement('desc');
					$c_text_desc = $dom->createTextNode('');
					$c_item_desc->appendchild($c_text_desc);
					$g_global->appendchild($c_item_desc);
					
					if(is_array($v['value']))
					{
						$c_item_str = $dom->createElement('array');
						$this->recursion_xml($v['value'],$c_item_str,$dom);
						$g_global->appendchild($c_item_str);
					}
					else 
					{
						$c_item_str = $dom->createElement('string');
						$c_text_str = $dom->createTextNode($v['value']);
						$c_item_str->appendchild($c_text_str);
						$g_global->appendchild($c_item_str);
					}
				}
			}

			$config->appendchild($g_global);
		}

		/*************************************************查询出配置结束******************************************/
		
		//模块部分
		$sql = "SELECT * FROM " .DB_PREFIX."modules WHERE application_id = {$id}";
		$q = $this->db->query($sql);
		$modules_arr = array();
		$module_unique = array();//存放模块标识
		while ($r = $this->db->fetch_array($q))
		{
			$modules_arr[] = $r;
			$module_unique[] = '\''.$r['mod_uniqueid'].'\'';
		}
		
		$mod = $dom->createElement('modules');
		
		foreach ($modules_arr AS $k => $v)
		{
			$sql_op = "SELECT * FROM ".DB_PREFIX."module_op WHERE module_id = {$v['id']} ";
			$q_op = $this->db->query($sql_op);
			$op_info = array();
			while ($row = $this->db->fetch_array($q_op))
			{
				$op_info[] = $row;
			}
			
			$module = $dom->createElement('module');
			foreach ($v AS $key => $value)
			{
				$item_key  = $dom->createElement($key);
				$item_text = $dom->createTextNode($value);
				$item_key->appendchild($item_text);
				$module->appendchild($item_key);
			}
			//加入操作的信息
			$mod_op = $dom->createElement('module_ops');
			if($op_info)
			{
				foreach ($op_info AS $m => $n)
				{
					$item_key = $dom->createElement('module_op');
					foreach($n AS $key => $value)
					{
						$item_key_child = $dom->createElement($key);
						$item_text_child = $dom->createTextNode($value);
						$item_key_child->appendchild($item_text_child);
						$item_key->appendchild($item_key_child);
					}
					$mod_op->appendchild($item_key);
				}
			}
			$module->appendchild($mod_op);
			$mod->appendchild($module);
		}

		$config->appendchild($mod);

		//节点部分
		$node = $dom->createElement('node');
		$sql = " SELECT n.*,mn.mod_uniqueid FROM ".DB_PREFIX."node n  LEFT JOIN ".DB_PREFIX."module_node mn ON mn.node_id = n.id WHERE n.application_id = {$id}";
		$q = $this->db->query($sql);
		$node_arr = array();
		while($r = $this->db->fetch_array($q))
		{
			$node_arr[] = $r;
		}

		foreach($node_arr AS $k => $v)
		{
			$item_key = $dom->createElement('item');
			foreach($v AS $key => $value)
			{
				$item_key_child = $dom->createElement($key);
				$item_text_child = $dom->createTextNode($value);
				$item_key_child->appendchild($item_text_child);
				$item_key->appendchild($item_key_child);
			}
			$node->appendchild($item_key);
		}

		$config->appendchild($node);

		//module_append数据
		$module_append = $dom->createElement('append');
		$module_ids = array();
		foreach($modules_arr AS $k => $v)
		{
			$module_ids[] = $v['id'];
		}

		$mids = implode(',',$module_ids);
		$sql = " SELECT * FROM ".DB_PREFIX."module_append WHERE module_id IN (".$mids.")";
		$q = $this->db->query($sql);
		$module_append_arr = array();
		while($r = $this->db->fetch_array($q))
		{
			$item_key = $dom->createElement('item');
			foreach($r AS $key => $value)
			{
				$item_key_child = $dom->createElement($key);
				$item_text_child = $dom->createTextNode($value);
				$item_key_child->appendchild($item_text_child);
				$item_key->appendchild($item_key_child);
			}
			$module_append->appendchild($item_key);
		}

		$config->appendchild($module_append);

		//菜单部分
		$menu = $dom->createElement('menu');
		$uniqueids = implode(',',$module_unique);
		$sql = "SELECT * FROM ".DB_PREFIX."menu WHERE mod_uniqueid IN (".$uniqueids.")";
		$q = $this->db->query($sql);
		$menu_arr = array();
		$father_menu = array();
		while($r = $this->db->fetch_array($q))
		{
			$menu_arr[$r['father_id']][] = $r;
			if(!in_array($r['father_id'],$father_menu))
			{
				$father_menu[] = $r['father_id'];
			}
		}

		$sql = "SELECT * FROM ".DB_PREFIX."menu WHERE id IN (".implode(',',$father_menu).")";
		$q = $this->db->query($sql);
		$parent_menu = array();
		while($r = $this->db->fetch_array($q))
		{
			$parent_menu[] = $r;
		}

		foreach($parent_menu AS $k => $v)
		{
			$item = $dom->createElement('item');
			$item_key_f = $dom->createElement('father');
			unset($v['module_id'],$v['url'],$v['father_id'],$v['close'],$v['order_id'],$v['mod_uniqueid'],$v['app_uniqueid']);
			foreach($v AS $key => $value)
			{
				if($key == 'id')continue;
				$item_key_child = $dom->createElement($key);
				$item_text_child = $dom->createTextNode($value);
				$item_key_child->appendchild($item_text_child);
				$item_key_f->appendchild($item_key_child);
			}
			$item->appendchild($item_key_f);
			
			$item_key_c = $dom->createElement('children');
			foreach($menu_arr[$v['id']] AS $kk => $vv)
			{
				$c_item = $dom->createElement('item');
				unset($vv['id'],$vv['module_id'],$vv['url'],$vv['father_id'],$vv['close'],$vv['order_id']);
				foreach($vv AS $_k => $_v)
				{
					$item_key_child = $dom->createElement($_k);
					$item_text_child = $dom->createTextNode($_v);
					$item_key_child->appendchild($item_text_child);
					$c_item->appendchild($item_key_child);
				}
				$item_key_c->appendchild($c_item);
			}
			$item->appendchild($item_key_c);
			$menu->appendchild($item);
		}
		$config->appendchild($menu);

		$dom->appendChild($config);
		
		file_put_contents('./cache/config.xml', $dom->saveXml());
		$file = fopen('./cache/config.xml','r');
		// 输入文件标签 
		Header("Content-type: application/octet-stream"); 
		Header("Accept-Ranges: bytes"); 
		Header("Accept-Length: ".filesize('./cache/config.xml')); 
		Header("Content-Disposition: attachment; filename=config.xml"); 
		// 输出文件内容 
		echo fread($file,filesize('./cache/config.xml')); 
		fclose($file); 
			
	}
	
	//递归输出xml
	public function recursion_xml($arr,$node,$dom)
	{
		if(is_array($arr))
		{
			foreach ($arr AS $k => $v)
			{
				$item_key  = $dom->createElement('key');
				$item_text = $dom->createTextNode($k);
				$item_key->appendchild($item_text);
				$node->appendchild($item_key);
				
				$item_key  = $dom->createElement('desc');
				$item_text = $dom->createTextNode('');
				$item_key->appendchild($item_text);
				$node->appendchild($item_key);
				
				if(is_array($v))
				{
					$item_array = $dom->createElement('array');
					$this->recursion_xml($v,$item_array,$dom);
					$node->appendchild($item_array);
				}
				else 
				{
					$item_key  = $dom->createElement('string');
					$item_text = $dom->createTextNode($v);
					$item_key->appendchild($item_text);
					$node->appendchild($item_key);
				}
			}
		}
	}
	
	/*去请求制定应用的配置*/
	public function get_api_config($app)
	{
		$app['dir'] = str_replace('admin/','',$app['dir']);
		$curl = new curl($app['host'],$app['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','getAppConfigInfo');
		$return = $curl->request('confApi.php');
		return $return[0];
	}
	
	//发布至应用商店
	public function app_publish()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$sql = "SELECT * FROM " .DB_PREFIX. "applications WHERE id = '" .$id. "'";
		$app = $this->db->query_first($sql);
		$curl = new curl($this->settings['App_appstore']['host'], $this->settings['App_appstore']['dir'] . 'admin/');
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','create');
		foreach($app AS $k => $v)
		{
			$curl->addRequestData($k,$v);
		}
		$curl->request('appstore_update.php');
		$this->input['goon'] = 1;
		$this->Redirect('发布成功','application.php?a=show');
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>