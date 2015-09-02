<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'modules');
require('./global.php');
require('./lib/class/curl.class.php');
class modules extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理模块!');
		}
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules = array();
		$yesno = array(0 => '否', 1 => '是');
		$appid = intval($this->input['application_id']);
		$appid = $appid ? $appid : intval($this->input['id']);
		if ($appid)
		{
			$cond = ' AND m.application_id=' . $appid;
		}
		
		$fatherid = intval($this->input['fatherid']);
		if ($fatherid)
		{
			$cond .= ' AND m.fatherid=' . $fatherid;
		}
		$sql = 'SELECT m.*, a.host AS ahost, a.dir AS adir, a.name AS aname FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE 1' . $cond . ' ORDER BY m.order_id ASC';
		$q = $this->db->query($sql);
		$this->cache->check_cache('modules');
		$all_modules = $this->cache->cache['modules'];
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			if (!$row['host'])
			{
				$row['host'] = $row['ahost'];
				if (!$row['dir'])
				{
					$row['dir'] = $row['adir'];
				}
			}
			if ($row['file_name'])
			{
				$row['apifile'] = 'http://' . $row['host'] . '/' . $row['dir'] . $row['file_name'] . $row['file_type'];
			}
			$row['is_pages'] = $yesno[$row['is_pages']];
			$row['application_id'] = $row['aname'];
			$row['fatherid'] = $row['fatherid'] ? $all_modules[$row['fatherid']]['name'] : '无';
			$modules[$row['id']] = $row;
		}
		
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '名称', 'exper' => '$v[name]'),
			'application_id' => array('title' => '所属系统', 'exper' => '$v[application_id]', 'width' => ' width="120"'),
			'fatherid' => array('title' => '上级模块', 'exper' => '$v[fatherid]', 'width' => ' width="120"'),
			'apifile' => array('title' => '接口文件', 'exper' => '$v[apifile]'),
			'func_name' => array('title' => '方法名', 'exper' => '$v[func_name]'),
			'template' => array('title' => '模板', 'exper' => '$v[template]'),
			'is_pages' => array('title' => '分页', 'exper' => '$v[is_pages]'),
			);
		$op = array(
			'view' => array(
				'name' =>'查看', 
				'brief' =>'',
				'link' => 'modules_op.php?'),
			'form_set' => array(
				'name' =>'表单', 
				'brief' =>'',
				'link' => '?a=form_set'),
			'form' => array(
				'name' =>'编辑', 
				'brief' =>'',
				'link' => '?a=form'),
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'删除\', 1);"',
				'link' => '?a=delete'),
			'pub_setting' => array(
				'name'=>'发布设置',
				'brief'=>'',
				'link'=>'?a=pub_setting'
				), 
			'app_design' => array(
				'name'=>'应用设计',
				'brief'=>'',
				'link'=>'?a=app_design'
				), 
			); 
		$batch_op = array(
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_batchpost(this, \'delete\', \'删除\', 1,\'\',\'\',\'ajax\');"',
				),
			); 
		$this->cache->check_cache('applications');
		$applications = array(0 => ' 全部 ');
		foreach ($this->cache->cache['applications'] AS $k => $v)
		{
			$applications[$k] = $v['name'];
		}
		$all_m = array(0 => ' 全部 ');
		foreach ($this->cache->cache['modules'] AS $k => $v)
		{
			if ($v['fatherid'])
			{
				continue;
			}
			$all_m[$k] = $v['name'];
		}
		$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
		hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('all_m', $all_m);
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $modules);
		$this->tpl->addVar('appid', $appid);
		$this->tpl->outTemplate('modules');
	}
	function pub_setting()
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT m.*, a.host AS ahost, a.dir AS adir, a.token AS atoken FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE m.id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			if ($formdata['settings'])
			{
				$formdata['settings'] = unserialize($formdata['settings']);
			}
			else
			{
				$formdata['settings'] = array();
			}
			$formdata['relate_module'] = unserialize($formdata['relate_module']);

			$a = 'dopub_setting';
			$optext = '确定';
			//执行接口程序
			
			$host = $formdata['host'];
			$dir = $formdata['dir'];
			$token = $formdata['token'];
			if (!$host)
			{
				$host = $formdata['ahost'];
				if (!$dir)
				{
					$dir = $formdata['adir'];
				}
				if (!$token)
				{
					$token = $formdata['atoken'];
				}
			}
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($host, $dir, $token);	
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getModelDict');
			$this->curl->addRequestData('model_name', $formdata['file_name']);
			$return = $this->curl->request($formdata['file_name'] . $formdata['file_type']);
			$this->tpl->addVar('module_field', $return[0]);
			$module_field = $return[0];

			
			$this->curl = new curl($this->settings['App_livcms']['host'], $this->settings['App_livcms']['dir']);
			$this->curl->initPostData();
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->addRequestData('a', 'show');
			$return = array();
			$return = $this->curl->request('read_model.php');
			$this->tpl->addVar('cms_model', $return[0]);
			$cms_model = $return[0];
			//读取已经设置的字段映射关系
			$sql = 'SELECT * FROM '.DB_PREFIX.'publish_fieldmap WHERE moduleid = '.intval($id);
			$setting = $this->db->query_first($sql);

			//请求cms模型字段含义
			$this->curl->initPostData();
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->addRequestData('applyid', $setting['model_id']);
			$this->curl->addRequestData('a', 'getModelField');
			$model_fields = $this->curl->request('read_model.php');
			$model_fields = $model_fields[0];

			$setting['map_field'] = $setting['map_field'] ? unserialize($setting['map_field']) : array();
			$select = array();
			if($model_fields)
			{
				foreach($model_fields as $k=>$v)
				{
					$title = $v ? $v : '未知';
					$select[$k] = $title . '('.$k.')';
				}
			}
			$this->tpl->addVar('formdata', $setting);
			$this->tpl->addVar('select', $select);
			//$this->tpl->addVar('url', 'http://'.$this->settings['App_livcms']['host'].'/');
			$this->tpl->addVar('a', $a);
			$this->tpl->addVar('optext', $optext);
			$this->tpl->outTemplate('pub_setting');
			exit;
		}
	}
	public function dopub_setting()
	{
		//print_r($this->input);
		if(($id = intval($this->input['id'])) < 1)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		if(intval($this->input['cms_model'] < 1))
		{
			$this->ReportError('未选择数据模型!');
		}
		$map = array();
		foreach($this->input['module_field'] as $k=>$v)
		{
			$map[$v] = $this->input['model_field'][$k] == '未选择' ? '' : $this->input['model_field'][$k]; 
		}
		$map = serialize($map);
		$data = array(
		'moduleid'=>$id,
		'medium_type' => intval($this->input['medium_type']),
		'pub_type' => intval($this->input['pub_type']),
		'modelid' => intval($this->input['cms_model']),
		);
		$sql = 'REPLACE INTO '.DB_PREFIX."publish_fieldmap SET moduleid={$data['moduleid']},medium_type={$data['medium_type']},pub_type={$data['pub_type']}, model_id={$data['modelid']}, map_field='{$map}'";
		//$this->ReportError($sql);
		$this->db->query($sql);
		$this->redirect('设置成功!');
	}
	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT m.*, a.host AS ahost, a.dir AS adir, a.token AS atoken FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE m.id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			if ($formdata['settings'])
			{
				$formdata['settings'] = unserialize($formdata['settings']);
			}
			else
			{
				$formdata['settings'] = array();
			}
			$formdata['relate_module'] = unserialize($formdata['relate_module']);
			if($create_update = unserialize($formdata['create_update']))
			{
				$formdata['create_update'] = implode(',', $create_update);
			}
			$a = 'update';
			$optext = '更新';
			
			/***********************查询app_design里面的数据开始************************/
			$sql = "SELECT * FROM " .DB_PREFIX."app_design WHERE mid = '".$id."' ORDER BY id ASC ";
			$q = $this->db->query($sql);
			$app_design = array();
			while ($r = $this->db->fetch_array($q))
			{
				$app_design[] = $r;
			}
			
			$formdata['app_design'] = $app_design;
			/***********************查询app_design里面的数据结束************************/
			//执行接口程序
			$host = $formdata['host'];
			$dir = $formdata['dir'];
			$token = $formdata['token'];
			if (!$host)
			{
				$host = $formdata['ahost'];
				if (!$dir)
				{
					$dir = $formdata['adir'];
				}
				if (!$token)
				{
					$token = $formdata['atoken'];
				}
			}
			/*
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($host, $dir, $token);
	
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', 'show');
			$this->curl->addRequestData('count', 1);
			$return = $this->curl->request($formdata['file_name'] . $formdata['file_type']);
			*/
			if(is_array($return))
			{
				$data = array();
				$data = $return[0];
				/*
				if ($formdata['settings']['order'])
				{
					$orders = $formdata['settings']['order'];
					asort($orders);
					foreach ($orders AS $kk => $vv)
					{
						$k = explode('.', $kk);
						if (count($k) == 1)
						{
							$data[$k[0]] = $return[0][$k[0]];
							unset($return[0][$k[0]]);
							unset($orders[$kk]);
						}
						else
						{
							$data[$k[0]] = array();
						}
					}
					if ($return[0])
					{
							//$data = $return[0] + $data;
					}
					foreach ($orders AS $kk => $vv)
					{
						$k = explode('.', $kk);
						$data[$k[0]][$k[1]] = $return[0][$k[0]][$k[1]];
						unset($return[0][$k[0]][$k[1]]);
					}
					unset($orders);
				}*/
				$formdata['apidata'] = $data;
			}
		}
		else
		{
			$formdata = $this->input;
			if (!$formdata['file_type'])
			{
				$formdata['file_type'] = '.php';
			}
			if (!$formdata['primary_key'])
			{
				$formdata['primary_key'] = 'id';
			}
			$a = 'create';
			$optext = '添加';
		}
		$this->cache->check_cache('applications');
		$applications = array();
		foreach ($this->cache->cache['applications'] AS $k => $v)
		{
			$applications[$k] = $v['name'];
		}
		$this->cache->check_cache('modules');
		$modules = array(0 => ' 无 ');
		foreach ($this->cache->cache['modules'] AS $k => $v)
		{
			if ($id && $v['id'] == $id)
			{
				continue;
			}
			$modules[$k] = $v['id'] . '_' . $v['name'];
		}
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('modules', $modules);
		$this->tpl->addVar('a', $a);
		//$this->tpl->outTemplate('module_form');
		$this->tpl->outTemplate('module_form_list');
		exit;
	}

	public function create()
	{
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->ReportError('请填写名称');
		}
		
		$application_id = intval($this->input['application_id']);
		$mod_uniqueid = trim($this->input['mod_uniqueid']);
		if($mod_uniqueid)
		{
			$sql = "SELECT count(*) AS num FROM ".DB_PREFIX."modules WHERE application_id=" . $application_id . " AND mod_uniqueid = '".$mod_uniqueid."' AND id != '".$id."'";
			$m_arr = $this->db->query_first($sql);
			if($m_arr['id'])
			{
				$this->ReportError('请重新填写模块标识,该模块标识已在系统中存在');
			}
		}
		else
		{
			$this->ReportError('请填写模块标识');
		}

		if(!$application_id)
		{
			$this->ReportError('请选择应用');
		}

		if ($this->input['relate_module'])
		{
			foreach ($this->input['relate_module'] AS $k => $v)
			{
				$this->input['relate_module'][$k] = $this->input['relate_module_name'][$k] ? $this->input['relate_module_name'][$k] : $v;
			}
			$relate_module = serialize($this->input['relate_module']);
		}

		//根据application_id查询出应用标识
		$sql = "SELECT softvar FROM ".DB_PREFIX."applications WHERE id = '".$application_id."'";
		$a_arr = $this->db->query_first($sql);
		$app_uniqueid = $a_arr['softvar'];
		$create_update='';
		if($this->input['create_update'])
		{
			$create_update = serialize(explode(',', trim(urldecode($this->input['create_update']))));
		}
		//检测是否添加过
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'application_id' => $application_id, 	
			'file_name' => $this->input['file_name'], 	
			'fatherid' => $this->input['fatherid'], 
			'icon' => $this->input['icon'],
			'mod_uniqueid' => $mod_uniqueid,
			'app_uniqueid' => $app_uniqueid,
			'func_name' => $this->input['func_name'], 
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'], 
			'primary_key' => $this->input['primary_key'], 
			'template' => $this->input['template'], 
			'is_pages' => $this->input['is_pages'], 
			'order_id' => $this->input['order_id'], 	
			'page_count' => $this->input['page_count'],
			'return_var' => $this->input['return_var'],
			'relate_module' => $relate_module,
			'menu_pos' => $this->input['menu_pos'],
			'is_pub' => $this->input['is_pub'],
			'relate_molude_id' => $this->input['relate_molude_id'],
			'pub_module_id' => $this->input['pub_module_id'],
			'create_time' => TIMENOW,
			'accept_outerlink'=> $this->input['accept_outerlink'],
			'create_update'=> $create_update,
			'need_auth'	=> $this->input['need_auth'],	
		);
		if($data['accept_outerlink'])
		{
			$this->db->query('INSERT INTO '.DB_PREFIX.'module_op SET module_id = '.$id . ', op="form_outerlink", func_name="detail",name="编辑外链数据",file_name="'.$data['file_name'].'",template="form_outerlink"');
			$this->db->query('INSERT INTO '.DB_PREFIX.'module_op SET module_id = '.$id . ', op="upload_indexpic", func_name="upload_indexpic",name="上传索引图片",file_name="'.$data['file_name'].'_update",direct_return=1,request_type="ajax"');
		}
		hg_fetch_query_sql($data, 'modules');
		$id = $this->db->insert_id();
		if ($this->input['fatherid'])
		{
			$this->cache->check_cache('modules');
			$modules = $this->cache->cache['modules'];
			$parents = $modules[$id]['parents'] . ',';
		}
		$parents .= $id;
		$data = array(
			'parents' => $parents, 
		);
		hg_fetch_query_sql($data, 'modules', 'id=' . $id);
		$this->syn_auth_module($id);
		/***********************设计表入库开始************************/
		$design_name = $this->input['design_name'];
		$bundle_id	 = $this->input['bundle_id'];
		$design_desc = $this->input['design_desc'];
		$type_length = $this->input['type_length'];
		$data_source = $this->input['data_source'];
		$is_index	 = $this->input['is_index'];
		$is_edit	 = $this->input['is_edit'];
		$data_type	 = $this->input['data_type'];
		for($i = 0;$i<count($design_name);$i++)
		{
			$data_design = array();
			$data_design = array(
				'mid' => $id,
				'name' => urldecode($design_name[$i]),
				'bundle_id' => urldecode($bundle_id[$i]),
				'desciption' => urldecode($design_desc[$i]),
				'type_length' => urldecode($type_length[$i]),
				'data_source' => urldecode($data_source[$i]),
				'is_edit' => urldecode($is_edit[$i]),
				'data_type' => urldecode($data_type[$i]),
			);
			
			if(in_array($i,$is_index))
			{
				$data_design['is_index'] = 1;
			}
			else 
			{
				$data_design['is_index'] = 0;
			}
			
			if(intval($this->input['is_primary']) ==  $i)
			{
				$data_design['is_primary'] = 1;
			}
			else 
			{
				$data_design['is_primary'] = 0;
			}
			
			hg_fetch_query_sql($data_design, 'app_design');
		}
		/***********************设计表入库结束************************/
		$this->cache->recache('modules');
		$this->input['goon'] = 1;
		$this->redirect('添加成功', '?application_id=' . $application_id);
		//$this->redirect('添加成功');
	}
	protected function syn_auth_module($id = '', $op = 'update')
	{
		if($op == 'update')
		{
			$sql = 'SELECT id,name,mod_uniqueid,app_uniqueid,application_id, dir,host,file_name,file_type,func_name,need_auth FROM '.DB_PREFIX.'modules WHERE id = '.intval($id);
			$mod = $this->db->query_first($sql);
			if($mod)
			{
				$curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir'] . 'admin/');
				$curl->initPostData();
				foreach ($mod as $field=>$value)
				{
					$curl->addRequestData($field, $value);
				}
				$syn_auth = $curl->request('modules.php');
				if($syn_auth['ErrorCode'] || !is_array($syn_auth[0]))
				{
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
			$curl->request('modules.php');
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
			$this->ReportError('请填写名称');
		}

		$application_id = intval($this->input['application_id']);
		$mod_uniqueid = trim($this->input['mod_uniqueid']);
		if($mod_uniqueid)
		{
			$sql = "SELECT count(*) AS num FROM ".DB_PREFIX."modules WHERE application_id=" . $application_id . " AND mod_uniqueid = '".$mod_uniqueid."' AND id != '".$id."'";
			$m_arr = $this->db->query_first($sql);
			if(intval($m_arr['num']) >= 1)
			{
				$this->ReportError('请重新填写模块标识,该模块标识已在系统中存在');
			}
		}
		else
		{
			$this->ReportError('请填写模块标识');
		}

		if(!$application_id)
		{
			$this->ReportError('请选择应用');
		}
		//检测是否添加过
		if ($this->input['fatherid'])
		{
			$this->cache->check_cache('modules');
			$modules = $this->cache->cache['modules'];
			$parents = $modules[$this->input['fatherid']]['parents'] . ',';
		}
		$parents .= $id;
		if ($this->input['relate_module'])
		{
			foreach ($this->input['relate_module'] AS $k => $v)
			{
				$this->input['relate_module'][$k] = $this->input['relate_module_name'][$k] ? $this->input['relate_module_name'][$k] : $v;
			}
			$relate_module = serialize($this->input['relate_module']);
		}

		//根据application_id查询出应用标识
		$sql = "SELECT softvar FROM ".DB_PREFIX."applications WHERE id = '".$application_id."'";
		$a_arr = $this->db->query_first($sql);
		$app_uniqueid = $a_arr['softvar'];
		$create_update='';
		if($this->input['create_update'])
		{
			$create_update = serialize(explode(',', trim(urldecode($this->input['create_update']))));
		}
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'application_id' => $application_id, 
			'file_name' => $this->input['file_name'], 	
			'fatherid' => $this->input['fatherid'], 	
			'func_name' => $this->input['func_name'], 
			'icon' => $this->input['icon'],
			'mod_uniqueid' => $mod_uniqueid,
			'app_uniqueid' => $app_uniqueid,
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'], 
			'template' => $this->input['template'], 
			'parents' => $parents, 
			'is_pages' => $this->input['is_pages'], 
			'page_count' => $this->input['page_count'], 
			'order_id' => $this->input['order_id'],
			'return_var' => $this->input['return_var'],
			'relate_module' => $relate_module,
			'is_pub' => $this->input['is_pub'],
			'menu_pos' => $this->input['menu_pos'],
			'pub_module_id' => $this->input['pub_module_id'],
			'relate_molude_id' => $this->input['relate_molude_id'],
			'accept_outerlink'=> $this->input['accept_outerlink'],
			'create_update'=> $create_update,
			'need_auth'	=> $this->input['need_auth'],
		);
		$this->db->query('DELETE FROM '.DB_PREFIX.'module_op WHERE module_id = '.$id . ' AND op="form_outerlink"');
		$this->db->query('DELETE FROM '.DB_PREFIX.'module_op WHERE module_id = '.$id . ' AND op="upload_indexpic"');
		if($data['accept_outerlink'])
		{
			$this->db->query('INSERT INTO '.DB_PREFIX.'module_op SET module_id = '.$id . ', op="form_outerlink", func_name="detail",name="编辑外链数据",file_name="'.$data['file_name'].'",template="form_outerlink"');
			$this->db->query('INSERT INTO '.DB_PREFIX.'module_op SET module_id = '.$id . ', op="upload_indexpic", func_name="upload_indexpic",name="上传索引图片",file_name="'.$data['file_name'].'_update",direct_return=1,request_type="ajax"');
		}
		if ($this->input['primary'])
		{
			$data['primary_key'] = $this->input['primary'];
		}
		if ($this->input['show'])
		{
			$settings = array(
				'primary' => $this->input['primary'], 	
				'canorder' => $this->input['canorder'], 	
				'show' => $this->input['show'], 	
				'show_title' => $this->input['show_title'],
				'show_append' => $_REQUEST['show_append'],  //需支持html
				'width' => $this->input['show_width'], 	
				'title' => $this->input['title'], 	
				'brief' => $this->input['brief'], 	
				'pic' => $this->input['pic'], 	
				'cancommend' => $this->input['cancommend'], 	
				'time' => $this->input['time'], 	
				'link' => $this->input['link'], 	
				'order' => $this->input['order'], 	
			);
			$data['settings'] = serialize($settings);
		}
		hg_fetch_query_sql($data, 'modules', 'id=' . $id);
		$this->syn_auth_module($id);
		/*************************更新app_design开始*******************************************/
			//先把原来的全部删除掉
			 $sql = "DELETE FROM ".DB_PREFIX."app_design WHERE mid = '".$id."'";
			 $this->db->query($sql);
			//插入新的app_design数据
			$design_name = $this->input['design_name'];
			$bundle_id	 = $this->input['bundle_id'];
			$design_desc = $this->input['design_desc'];
			$type_length = $this->input['type_length'];
			$data_source = $this->input['data_source'];
			$is_index	 = $this->input['is_index'];
			$is_edit	 = $this->input['is_edit'];
			$ids_arr	 = $this->input['ids_arr'];
			$data_type	 = $this->input['data_type'];
			for($i = 0;$i<count($design_name);$i++)
			{
				$data_design = array();
				$data_design = array(
					'mid' => $id,
					'name' => urldecode($design_name[$i]),
					'bundle_id' => urldecode($bundle_id[$i]),
					'desciption' => urldecode($design_desc[$i]),
					'type_length' => urldecode($type_length[$i]),
					'data_source' => urldecode($data_source[$i]),
					'is_edit' => urldecode($is_edit[$i]),
					'data_type' => urldecode($data_type[$i]),
				);
				
				if(in_array(urldecode($ids_arr[$i]),$is_index))
				{
					$data_design['is_index'] = 1;
				}
				else 
				{
					$data_design['is_index'] = 0;
				}
				
				if(intval($this->input['is_primary']) ==  urldecode($ids_arr[$i]))
				{
					$data_design['is_primary'] = 1;
				}
				else 
				{
					$data_design['is_primary'] = 0;
				}
				
				hg_fetch_query_sql($data_design, 'app_design');
			}
		/*************************更新app_design结束*******************************************/
		$this->cache->recache('modules');
		$this->rebuild_program($id);
		$this->input['goon'] = 1;
		$this->redirect('更新成功', '?application_id=' . $application_id);
	}
	
	public function form_set($message = '')
	{
		$id = intval($this->input['id']);
		$formdata = $this->db->query_first('SELECT m.*, a.host AS ahost, a.dir AS adir, a.token AS atoken FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE m.id=' . $id);
		if (!$formdata)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$formdata['form_set'] = unserialize($formdata['form_set']);
		
		//执行接口程序
		$this->cache->check_cache('applications');
		$applications = $this->cache->cache['applications'];
		$host = $formdata['host'];
		$dir = $formdata['dir'];
		$token = $formdata['token'];
		if (!$host)
		{
			$host = $formdata['ahost'];
			if (!$dir)
			{
				$dir = $formdata['adir'];
			}
			if (!$token)
			{
				$token = $formdata['atoken'];
			}
		}
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$this->curl = new curl($host, $dir, $token);
	
		$this->curl->setSubmitType('post');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a', 'detail');
		$return = $this->curl->request($formdata['file_name'] . $formdata['file_type']);
		if(is_array($return))
		{
			$data = array();
			if ($formdata['form_set']['order'])
			{
				asort($formdata['form_set']['order']);
				foreach ($formdata['form_set']['order'] AS $k => $v)
				{
					$data[$k] = $return[0][$k];
					unset($return[0][$k]);
				}
			}
			if ($return[0])
			{
				$data = $return[0] + $data;
			}
			$formdata['apidata'] = $data;
		}
		$show_types = array(
			'text' => '单行文本',
			'textarea' => '多行文本',
			'radio' => '单选按钮',
			'select' => '下拉选择',
		);
		$groups = array(
			1=> '基础',
		);
		$rowscols = array(
			'row' => '行',
			'col' => '列',
		);
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('groups', $groups);
		$this->tpl->addVar('rowscols', $rowscols);
		$this->tpl->addVar('show_types', $show_types);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->outTemplate('module_form_set');
		exit;
	}
	
	public function doform_set()
	{
		$id = $this->input['id'];
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		//检测是否添加过
		$data = array();
		$settings = array(
				'title' => $this->input['title'], 	
				'order' => $this->input['order'],
				'height' => $this->input['height'], 
				'width' => $this->input['width'], 	
				'group' => $this->input['group'], 	
				'show_type' => $this->input['show_type'], 	
				'rowscols' => $this->input['rowscols'], 	
				'canedit' => $this->input['canedit'], 	
		);
		$data['form_set'] = serialize($settings);
		hg_fetch_query_sql($data, 'modules', 'id=' . $id);
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'modules WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->syn_auth_module($ids, 'delete');
			$this->cache->recache('modules');
			$this->redirect('成功删除' . $affect_rows . '条记录', 0, 0, '', 'hg_remove_row("' . $ids . '")');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}
	
	/*进入应用设计界面*/
	public function app_design()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		
		/*查询出模块的信息*/
		$sql = " SELECT m.*,a.name as app_name FROM ".DB_PREFIX."modules m LEFT JOIN " . DB_PREFIX . "applications a ON m.application_id = a.id   WHERE m.id = {$id}";
		$data = $this->db->query_first($sql);
		$data['api_file'] = $data['file_name'].$data['file_type'];
		$data['settings_arr'] = unserialize($data['settings']);
		
		/*查询出方法的信息*/
		$op_arr = array();
		$sql = "SELECT * FROM ".DB_PREFIX."module_op WHERE module_id = 0 OR module_id = {$id}";
		$q = $this->db->query($sql);
		while($r = $this->db->fetch_array($q))
		{
			$op_arr[] = array('op' => $r['op'],'name' => $r['name']);
		}
		
		/*查询出存储设计的信息*/
		$sql = "SELECT * FROM " .DB_PREFIX."app_design WHERE mid = '".$id."' ORDER BY id ASC ";
		$q = $this->db->query($sql);
		$app_design = array();
		while ($r = $this->db->fetch_array($q))
		{
			$r['data_source'] = $this->settings['data_source'][$r['data_source']];
			$r['data_type'] = $this->settings['data_type'][$r['data_type']];
			$app_design[] = $r;
		}

		$this->tpl->addVar('app_design', $app_design);
		$this->tpl->addVar('op_info', $op_arr);
		$this->tpl->addVar('module_info', $data);
		$this->tpl->addVar('id', $id);
		$this->tpl->outTemplate('app_design_list');
	}

	/*保存存储设计信息*/
	public function saveStorage()
	{
		$id = intval($this->input['id']);
		if (!$id)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		$bundle_id   = explode(',',urldecode($this->input['bundle_id']));
		$design_name = explode(',',urldecode($this->input['name']));
		$design_desc = explode(',',urldecode($this->input['desciption']));
		$type_length = explode(',',urldecode($this->input['type_length']));
		$data_source = explode(',',urldecode($this->input['data_source']));
		$data_type   = explode(',',urldecode($this->input['data_type']));
		$is_primary  = explode(',',urldecode($this->input['is_primary']));
		$is_index    = explode(',',urldecode($this->input['is_index']));
		
				
		/*************************更新app_design开始*******************************************/
			//先把原来的全部删除掉
			 $sql = "DELETE FROM ".DB_PREFIX."app_design WHERE mid = '".$id."'";
			 $this->db->query($sql);
			//插入新的app_design数据
			for($i = 0;$i<count($design_name);$i++)
			{
				foreach($this->settings['data_type'] AS $k => $v)
				{
					if($v == $data_type[$i])
					{
						$d_type = intval($k);
					}
				}
				
				foreach($this->settings['data_source'] AS $k => $v)
				{
					if($v == $data_source[$i])
					{
						$d_src = intval($k);
					}
				}
				
				$data_design = array();
				$data_design = array(
					'mid' => $id,
					'name' => $design_name[$i],
					'bundle_id' => $bundle_id[$i],
					'desciption' => $design_desc[$i],
					'type_length' => $type_length[$i],
					'data_source' => $d_src,
					'data_type' => $d_type,
					'is_index' => intval($is_index[$i]),
					'is_primary' => intval($is_primary[$i]),
					'is_edit' => 1,
				);
				
				hg_fetch_query_sql($data_design, 'app_design');
			}
		/*************************更新app_design结束*******************************************/
		$this->redirect('保存成功');
	}

	private function rebuild_program($id)
	{
		return;
		include(ROOT_PATH . 'lib/class/program.class.php');
		$program = new program();
		$program->rebuild_program(0, $id);
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>