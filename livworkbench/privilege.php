<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'Privilege');
require('./global.php');

require('./lib/class/curl.class.php');
class Privilege extends uiBaseFrm
{
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] >= MAX_ADMIN_TYPE)
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
		//导航设置
		$this->append_nav(array('name'=>'权限控制','link'=>'###'));
		//查找应用下面模块
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','get_mod_info');
		//$this->input['app_un_id'] = 'liv_mms';
		if($this->input['app_un_id'])
		{
			$curl->addRequestData('app_en',$this->input['app_un_id']);
		}
		$return = $curl->request('get_app_info.php');
		//file_put_contents('1.txt',var_export($return,1));
		$return = $return[0];
		$applications = array();
		//默认模块id
		$DefaultMid = urldecode($return[0]['module_en']);
		$mod = urldecode($this->input['_type']);
		if(is_array($return) && count($return)>0)
		{
			foreach($return as $key=>$val)
			{
				foreach($val as $k=>$v)
				{
					if($k == 'module_en')
					{
						$module[$v] = $val['module_name'];
						if($mod == $v)
						{
							$this->append_nav(array('name'=>$val['module_name'],'link'=>'?_type='.$v));
						}
					}
					if($k == 'app_en')
					{
						$applications[] = $val['app_name'];
					}
				}
			}
		}
		$server_cluster = $module;
		$show_server_node = array();
		if($server_cluster)
		{
			foreach($server_cluster as $k=>$v)
			{
				$show_server_node[] = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type' ,'attr' => 'attr','is_last'=>1);
			}
		}
		$modules = array();
		$kind = $this->input['kind'];

		if($mod)
		{
			if(!$kind || $kind == 'group')
			{
				$template = 'privilege_admin_group';
				$this->append_nav(array('name'=>'用户组','link'=>'#'));
			}
			else if($kind == 'user')
			{
				$template = 'privilege_admin_group';
				$this->append_nav(array('name'=>'用户','link'=>'#'));
			}
		}
		else if(!$mod && $this->input['infrm'])
		{
			$template = 'privilege_admin_group';
		}
		else
		{
			$template = 'privilege';
		}
		$this->tpl->addVar('_nav', $this->nav);
		//用户组列表
		if($kind == 'group' || !$kind)
		{
			//查询用户组信息
			if($this->user['group_type'] != 1)
			{
				$condition = ' WHERE group_type != 1';
			}

			$sql = 'SELECT * FROM ' . DB_PREFIX . 'admin_group ' . $condition . ' ORDER BY id ASC';
			$q = $this->db->query($sql);
			$admin_group = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['create_time'] = hg_get_date($row['create_time']);
				$row['group_type'] = $this->settings['group_types'][$row['group_type']];
				$admin_group[$row['id']] = $row;
			}
			$list_fields = array(
				'id' => array('title' => 'ID', 'exper' => '$v[id]'),
				'name' => array('title' => '用户组名称', 'exper' => '$v[name]'),
				'brief'=>array('title'=>'描述','exper'=>'$v[brief]'),
				'group_type' => array('title' => '组类型', 'exper' => '$v[group_type]'),
				'create_time' => array('title' => '创建时间', 'exper' => '$v[create_time]')
				);
			$op = array(
				'authorize' => array(
					'name' =>'功能授权',
					'brief' =>'权限管理',
					'link' => '?a=sys_authorize&mod='.$mod),
				'authorize_node' => array(
					'name' =>'节点授权',
					'brief' =>'权限管理',
					'link' => '?a=accredit_node&mod='.$mod),
				);
			//print_r($this->nav);exit;
			$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
			hg_add_head_element('js-c',$str);
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->addVar('list_fields', $list_fields);
			$this->tpl->addVar('op', $op);
			$this->tpl->addVar('batch_op', $batch_op);
			$this->tpl->addVar('close_search', true);
			$this->tpl->addVar('primary_key', 'id');
			$this->tpl->addVar('defaultmid', $DefaultMid);
			$this->tpl->addVar('list', $admin_group);
			$this->tpl->addVar('_selfurl', 'privilege.php?infrm=1');
			$this->tpl->addVar('show_server_node', $show_server_node);


			$this->tpl->outTemplate($template);
		}
		else//用户列表
		{
			$count = intval($this->input['count']);
			$count = $count ? $count : 20;
			$extralink = '';
			if ($this->input['count'])
			{
				$extralink .= '&amp;count=' . $this->input['count'];
			}
			if ($this->input['hgorder'])
			{
				$extralink .= '&amp;hgorder=' . $this->input['hgorder'];
			}
			if ($this->input['hgupdn'])
			{
				$extralink .= '&amp;hgupdn=' . $this->input['hgupdn'];
			}
			$condition = array();
			if($this->user['group_type'] != 1)
			{
				$condition[] = ' t2.group_type != 1';
			}
			if($this->input['admin_group_id'])
			{
				$condition[] = ' t1.admin_group_id = ' . $this->input['admin_group_id'];
				$extralink .= '&amp;admin_group_id=' . $this->input['admin_group_id'];
			}
			if ($condition)
			{
				$conditions = ' WHERE ' . implode(',', $condition);
			}
			$page = intval($this->input['pp']);
			$sql = 'SELECT count(*) AS total FROM ' . DB_PREFIX . 'admin t1
						left join '.DB_PREFIX.'admin_group t2
							on t1.admin_group_id=t2.id'
							. $conditions;
			$total = $this->db->query_first($sql);
			$total = intval($total['total']);
			$data = array();
			$data['totalpages'] = $total;
			$data['perpage'] = $count;
			$data['curpage'] = $page;
			$data['pagelink'] = '?' . $extralink;
			$pagelink = hg_build_pagelinks($data);
			$sql = 'SELECT t1.*,t2.name FROM ' . DB_PREFIX . 'admin t1
					left join '.DB_PREFIX.'admin_group t2
						on t1.admin_group_id=t2.id'
							. $conditions . " LIMIT $page,$count";
			$q = $this->db->query($sql);
			$admin = array();
			while ($row = $this->db->fetch_array($q))
			{
				$row['create_time'] = hg_get_date($row['create_time']);
				$row['cardid'] = $row['cardid']?'已绑定':'未绑定';
				$admin[$row['id']] = $row;
			}
			$list_fields = array(
				'id' => array('title' => 'ID', 'exper' => '$v[id]'),
				'user_name' => array('title' => '用户名', 'exper' => '$v[user_name]'),
				'group_name'=>array('title'=>'用户组','exper'=>'$v[name]'),
				'cardid'=>array('title'=>'是否绑定密保','exper'=>'$v[cardid]'),
				'create_time' => array('title' => '创建时间', 'exper' => '$v[create_time]')
				);
				/*

				'authorize' => array(
					'name' =>'授权',
					'brief' =>'权限管理',
					'link' => '?a=sys_authorize'),
				*/
			$op = array(
				'authorize' => array(
					'name' =>'功能授权',
					'brief' =>'权限管理',
					'link' => '?a=sys_authorize&type=user&mod='.$mod),
				'authorize_node' => array(
					'name' =>'节点授权',
					'brief' =>'权限管理',
					'link' => '?a=accredit_node&type=user&mod='.$mod),
				);
			$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
			hg_add_head_element('js-c',$str);
			$this->tpl->addHeaderCode(hg_add_head_element('echo'));
			$this->tpl->addVar('list_fields', $list_fields);
			$this->tpl->addVar('pagelink', $pagelink);
			$this->tpl->addVar('op', $op);
			$this->tpl->addVar('batch_op', $batch_op);
			$this->tpl->addVar('close_search', true);
			$this->tpl->addVar('primary_key', 'id');

			$this->tpl->addVar('_selfurl', 'privilege.php');
			$this->tpl->addVar('show_server_node', $show_server_node);

			$this->tpl->addVar('list', $admin);
			$this->tpl->outTemplate($template);
		}
	}
	function get_app_en($mod_en)
	{
		//获取系统标识,模块标识
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','get_app_en');
		$curl->addRequestData('mod_en',$mod_en);
		$en = $curl->request('get_app_info.php');
		return $en[0];
	}
	//功能授权
	function sys_authorize()
	{
		if(urldecode($this->input['type']) == 'user')
		{
			$type = 'user';
			$gid = intval($this->input['gid']);
		}
		else
		{
			$type = 'group';
		}
		$mod_en = urldecode($this->input['mod']);

		$en = $this->get_app_en($mod_en);
		$app_en = $en['app_en'];
		$id = $this->input['id'];
		$template = 'privilege_form1';
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','get_op');
		$curl->addRequestData('app_en',$app_en);
		$curl->addRequestData('mod_en',$mod_en);
		$curl->addRequestData('id',$id);
		$curl->addRequestData('gid',$gid);
		$curl->addRequestData('type',$type);

		$return = $curl->request('get_app_info.php');
		$return['id'] = $id;
		$return['type'] = $type;
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $return);
		$this->tpl->addVar('_selfurl', 'privilege.php?&infrm=1');
		$this->tpl->outTemplate($template);
	}
	/*******************节点授权******************************************/
	function accredit_node()
	{
		if(urldecode($this->input['type']) == 'user')
		{
			$type = 'user';
			$gid = intval($this->input['gid']);
		}
		else
		{
			$type = 'group';
		}
		$mod_en = urldecode($this->input['mod']);//模块标识
		$id = $this->input['id'];				//用户或用户组id
		$en = $this->get_app_en($mod_en);		//根据模块标识获取应用标识
		$app_en = $en['app_en'];				//应用标识

		$template = 'accredit_node';			//模板名称

		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','getNodeId');
		$curl->addRequestData('id',$id);
		$curl->addRequestData('gid',$gid);
		$curl->addRequestData('type',$type);
		$curl->addRequestData('app_en',$app_en);
		$curl->addRequestData('mod_en',$mod_en);

		$return = $curl->request('get_app_info.php');
		$return = $return[0];
		//file_put_contents('3.txt',var_export($return,1));
		$multi_node = $return['multi_node'];//多节点标识与名称
		$node_id = $return['node_id'];		//节点id
		$info = $return['info'];			//节点的权限信息
		$op = $return['op'];				//模块下节点的所有操作
		//file_put_contents('3.txt',var_export($multi_node,1));
		/**$info************************
		array (
		  42 => 
		  array (
			0 => 'delete',
			1 => 'update',
			2 => 'add_to_collect',
		  ),
		  43 => 
		  array (
			0 => 'update',
			1 => 'add_to_collect',
		  ),
		  39 => 
		  array (
			0 => 'add_to_collect',
		  ),
		)
		*********************************/
		unset($return);
		if($node_id)//节点id与节点标识关联
		{
			foreach($node_id as $k => $v)//节点标识=>节点id
			{
				$new_node_id[$v][] = $k;	
			}
		}
		//file_put_contents('1.txt',var_export($new_node_id,1));
		
		/*$new_node_id
		array (
		  'vod_node' => 
		  array (
			0 => 39,
			1 => 42,
		  ),
		  'vod_mark_node' => 
		  array (
			0 => 43,
		  ),
		)*/
		
		if($new_node_id)//取得所有节点id的标识
		{
			foreach($new_node_id as $k=>$v)
			{
				$node_en[] = '"'.$k.'"';
			}
		
			//file_put_contents('2.txt',var_export($node_en,1));
			//查询多个节点的节点文件路径
			$node_en = implode(',',$node_en);
			$conditions = ' WHERE t1.node_uniqueid in ('.$node_en.')';
			$sql = 'SELECT t1.file_name,t1.file_type,t1.func_name,t2.host,t2.dir,t2.admin_dir FROM ' . DB_PREFIX . 'node t1
					LEFT JOIN '.DB_PREFIX.'applications t2
					ON t1.application_id=t2.id'
					. $conditions;
			$q = $this->db->query($sql);
			while($row = $this->db->fetch_array($q))
			{
				$file_info[$row['file_name']] = $row;
				if($new_node_id)
				{
					foreach($new_node_id as $k=>$v)
					{
						if($k == $row['file_name'])
						{
							$file_info[$row['file_name']]['node_id'] = $v;
						}
					}
				}
			}
		}
		//file_put_contents('2.txt',var_export($file_info,1));
		
		/*array (
		  'vod_node' => 
		  array (
			'file_name' => 'vod_node',
			'file_type' => '.php',
			'func_name' => 'show',
			'host' => 'localhost',
			'dir' => 'livsns/api/liv_mms/admin/',
			'admin_dir' => 'admin/',
			'node_id' => 
			array (
			  0 => 42,
			  1 => 39,
			),
		  ),
		  'vod_mark_node' => 
		  array (
			'file_name' => 'vod_mark_node',
			'file_type' => '.php',
			'func_name' => 'show',
			'host' => 'localhost',
			'dir' => 'livsns/api/liv_mms/admin/',
			'admin_dir' => 'admin/',
			'node_id' => 
			array (
			  0 => 43,
			),
		  ),
		)*/
		
		//循环查找有权限的子节点的树形节点结构
		if($file_info)
		{
			foreach($file_info as $k => $v)
			{
				$node_host = $v['host'];
				$node_dir = $v['dir'];
				$node_file = $v['file_name'].$v['file_type'];
				$node_func = $v['func_name'];
				//查询默认分类节点
				$curl = new curl($node_host,$node_dir);
				$curl->setSubmitType('get');
				$curl->initPostData();
				//file_put_contents('4.txt',var_export($v['node_id'],1));
				$ids = $v['node_id'];
				if ($ids)//有节点id,传递节点id
				{
					foreach ($ids AS $kk => $vv)
					{
						$curl->addRequestData("ids[{$kk}]", $vv);
					}
				}
				$curl->addRequestData('a','getMergeParentsTree');

				$node = $curl->request($node_file);
				$node_info[] = $node;//多节点数据
				
				//break;
			}
			//file_put_contents('5.txt',var_export($node_info,1));//exit;//所有节点的数据
			if($node_info)//将节点树形结构与节点权限整合
			{
				foreach($node_info as $key=>$val)
				{
					$val = $val[0];
					
					if(is_array($val) && count($val)>0)
					{
						foreach($val as $k=>$v)
						{
							if($info[$k])
							{
								$node_info[$key][0][$k]['act'] = $info[$k];
								if($new_node_id)
								{
									foreach($new_node_id as $key_noden=>$ids)
									{
										if(in_array($k,$ids))
										{//节点标识
											$node_info[$key][0][$k]['node_en'] = $key_noden;
										}
									}
								}
								
							}
						}
					}
					/*else
					{
						$node_key = array_keys($val);
						$node_key = $node_key[0];
						if($info[$node_key])
						{
							$node_info[$key][0][$node_key]['act'] = $info[$node_key];
						}
					}*/
				}
				//file_put_contents('7.txt',var_export($node_info,1));
			}
		}
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('id', $id);
		$this->tpl->addVar('node', $node);
		$this->tpl->addVar('type', $type);
		$this->tpl->addVar('app_en', $app_en);
		
		$this->tpl->addVar('multi_node', $multi_node);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('list', $node_info);
		$this->tpl->addVar('_selfurl', 'privilege.php?&infrm=1');
		$this->tpl->outTemplate($template);
	}
	/*******************节点授权结束******************************************/


	//更新功能权限
	public function update()
	{
		$type = urldecode($this->input['type']);
		$mod_en = urldecode($this->input['mod_en']);
		$gid = $this->input['id'];
		$is_all = $this->input['is_all'];
		$app_global = $this->input['app_global'];
		if (!$mod_en)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		//获取系统标识
		$en = $this->get_app_en($mod_en);
		$app_en = $en['app_en'];


		//如果没选全选,就查询模块下面操作
		if(!$is_all)
		{

			//获取模块下全部操作
			$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
			$curl->setSubmitType('get');
			$curl->initPostData();
			$curl->addRequestData('a','get_op');
			$curl->addRequestData('app_en',$app_en);
			$curl->addRequestData('mod_en',$mod_en);
			$return = $curl->request('get_app_info.php');

			if(is_array($return))
			{
				foreach($return[0] as $key=>$val)
				{
					foreach($val as $k=>$v)
					{
						if($k == 'op_en')
						{
							if($this->input[$v])//判断哪些操作被授权
							{
								$op[$v] = 1;
							}
						}
					}
				}
			}
		}
		//更新权限表
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		//group用户组权限设置,user用户权限设置
		if($type == 'group')
		{
			$request_file = 'admin/set_group_privilege.php';
			$curl->addRequestData('group_id',$gid);
		}
		else
		{
			$request_file = 'admin/set_user_privilege.php';
			$curl->addRequestData('user_id',$gid);
		}

		$curl->addRequestData('app_en[flag]',$app_en);
		$curl->addRequestData('app_en[value]',intval($app_global));
		$curl->addRequestData('mod_en_set[flag]',$mod_en);
		$curl->addRequestData('mod_en_set[value]',intval($is_all));
		if ($op)//有操作,传递应用,操作
		{
			foreach ($op AS $k => $v)
			{
				$curl->addRequestData("act[{$k}]", $v);
			}
		}
		$ret = $curl->request($request_file);
		$this->redirect('更新成功');
	}
	public function set_node_auth()
	{	
		/********************新测试************************************/
		$app_en = urldecode($this->input['app_en']);//应用标识
		$mod_en = urldecode($this->input['mod_en']);//模块标识
		if (!$mod_en)
		{
			$this->ReportError('模块标识不存在!');
		}
		$type = urldecode($this->input['type']);//标识用户组
		$id = intval($this->input['id']);//用户组id
		if($this->input['node_en'])
		{
			$default_node_en = array_flip($this->input['node_en']);//默认节点操作全取消时传递节点标识
		}
		$add_en = intval($this->input['add_node']);//添加节点标识
		if($add_en)
		{
			$add_node_en = $this->input['hgCounter_0_siteid'];
			if(!$add_node_en)
			{
				$this->ReportError('节点标识不存在!');
			}
			$add_node_id = $this->input['node_id'];
		}
		
		//获取模块下全部操作
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','accredit_node');
		$curl->addRequestData('app_en',$app_en);
		$curl->addRequestData('mod_en',$mod_en);
		$return = $curl->request('get_app_info.php');
		$return = $return[0];
		
		if(is_array($return) && count($return)>0)
		{
			foreach($return as $key=>$val)
			{
				foreach($val as $k=>$v)
				{
					if($k == 'op_en')
					{
						if($this->input[$v])//判断哪些操作被授权
						{
							$op[$v] = $this->input[$v];
						}
					}
				}
			}
		}
		if(is_array($op) && count($op)>0)//修改某个节点具体操作
		{
			foreach($op as $key=>$val)
			{
				foreach($val as $k=>$v)
				{
					$set[$k][$key] = $v;
				}
			}
		}
		else if($add_node_id)//增加权限节点
		{
			foreach($add_node_id as $k => $v)
			{
				$set[$v]['all_op'] = $add_node_en;
			}
			
		}
		/****$set数据结构
		Array
		(
			[39] => Array
				(
					[add_to_collect] => vod_node
					[audit] => vod_node
				)
			[42] => Array
				(
					[add_to_collect] => vod_node
					[audit] => vod_node
					[delete] => vod_node
					[update] => vod_node
				)
			[43] => Array
				(
					[add_to_collect] => vod_mark_node
					[audit] => vod_mark_node
					[update] => vod_mark_node
				)
		)
		****/
		//更新权限表
		$curl = new curl($this->settings['App_auth']['host'],$this->settings['App_auth']['dir']);
		$curl->setSubmitType('get');
		$curl->initPostData();
		$curl->addRequestData('a','show');
		//group用户组权限设置,user用户权限设置
		if($type == 'group')
		{
			$request_file = 'admin/set_node_group_privilege.php';
			$curl->addRequestData('group_id',$id);
		}
		else
		{
			$request_file = 'admin/set_node_user_privilege.php';
			$curl->addRequestData('user_id',$id);
		}

		$curl->addRequestData('app_en',$app_en);
		$curl->addRequestData('mod_en',$mod_en);
		
		if ($set)//有操作,传递应用,操作
		{
			foreach ($set AS $k => $v)
			{
				foreach($v as $kk=>$vv)
				{
					$curl->addRequestData("act[{$k}][$kk]", $vv);
				}
				
			}
		}
		else//没操作,传递节点标识
		{
			foreach ($default_node_en AS $k => $v)
			{
				$curl->addRequestData("default_node[{$k}]", 1);
			}
		}
		$ret = $curl->request($request_file);
		$this->redirect('更新成功');
		//file_put_contents('test.txt',var_export($return,1));exit;
		/********************新测试结束************************************/
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>