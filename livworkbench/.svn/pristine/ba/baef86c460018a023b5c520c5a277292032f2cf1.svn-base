<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: index.php 2 2011-05-03 10:51:54Z develop_tong $
***************************************************************************/
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'server');
require('./global.php');
class server extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] >= MAX_ADMIN_TYPE)
		{
			$this->ReportError('对不起，您没有权限管理模块!');
		}
		$this->mServer_cluster = array(
		'1'=>'应用服务器',
		'2'=>'图片服务器',
		'3'=>'视频服务器',
		'4'=>'授权服务器',
		'5'=>'数据库服务器',
		);
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$show_server_node = array();
		$server_cluster = $this->mServer_cluster;
		if($server_cluster)
		{
			foreach($server_cluster as $k=>$v)
			{
				$show_server_node[] = array('id'=>$k,"name"=>$v,"fid"=>0,"depth"=>0, 'input_k' => '_type' ,'attr' => 'attr','is_last'=>1);
			}
		}
		$modules = array();;
		
		$type = intval($this->input['_type']);
		if ($type)
		{
			$template = 'servers_list';
			$cond = ' AND type=' . $type;
		}
		else if(!$this->input['_type'] && $this->input['infrm'])
		{
			$template = 'servers_list';
		}
		else
		{
			$template = 'servers';
		}
		
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'servers WHERE 1' . $cond . ' ORDER BY id ASC';
		$q = $this->db->query($sql);
		
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			
			$servers[] = $row;
		}
		
		//$str = 'var gBatchAction = new Array();gBatchAction[\'delete\'] = \'?a=delete\';';
		//hg_add_head_element('js-c',$str);
		$this->tpl->addHeaderCode(hg_add_head_element('echo'));
		$this->tpl->addVar('list_fields', $list_fields);
		$this->tpl->addVar('op', $op);
		$this->tpl->addVar('batch_op', $batch_op);
		$this->tpl->addVar('all_m', $all_m);
		$this->tpl->addVar('applications', $applications);
		$this->tpl->addVar('close_search', true);
		$this->tpl->addVar('primary_key', 'id');
		$this->tpl->addVar('list', $servers);
		$this->tpl->addVar('_selfurl', 'server.php?&infrm=1');
		$this->tpl->addVar('show_server_node', $show_server_node);
		$this->tpl->outTemplate($template);
	}
	
	
	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'servers WHERE id=' . $id);
			
			$e = $this->db->query('SELECT * FROM '. DB_PREFIX .'servers_extend WHERE sid='.$id);
			while ($row = $this->db->fetch_array($e))
			{
				$extend[] = $row;
			}
		
			$formdata['extend'] = $extend;
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			unset($formdata['password']);
			$a = 'update';
			$optext = '更新';
			
		}
		else
		{
			$formdata = $this->input;
			if (!$formdata['primary_key'])
			{
				$formdata['primary_key'] = 'id';
			}
			$a = 'create';
			$optext = '添加';
		}
		
		
		$type = $this->mServer_cluster;
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('type', $type);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('servers_form');
		exit;
	}

	public function create()
	{
		
		$name = trim($this->input['name']);
		if (!$name)
		{
			$this->form('请填写名称');
		}
		$type = $this->input['type'];
		if(!$type)
		{
			$this->form('请选择分类');
		}
		if($type == 5 && !$this->input['port'])
		{
			//$this->input['port'] = '';
		}
		/*if($type == 5)
		{
			if(!class_exists('db'))
			{
				include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
			}
			$gDB = new db();
			$res = $gDB->connect($this->input['site_name'],$this->input['user_name'],$this->input['password']);

			if($res)
			{
				$this->input['link_state'] = 1;
			}
		}
		else
		{
			
		}*/
		$data = array(
			'name' => $name,
			'type' => $this->input['type'],
			'brief' => $this->input['brief'], 	
			'ident' => $this->input['ident'],	
			'n_ip' => $this->input['n_ip'], 	
			'o_ip' => $this->input['o_ip'],
			'site_name' => urldecode($this->input['site_name']),
			'access_deal' => urldecode($this->input['access_deal']),

			'user_name' => urldecode($this->input['user_name']), 
			'password' => hg_encript_str(urldecode($this->input['password'])), 
			'token' => urldecode($this->input['token']),
				
			'port' => $this->input['port'], 
			'state' => $this->input['state'], 
			'link_state' => $this->input['link_state'], 
			
			'create_time' => TIMENOW,
			'update_time' => TIMENOW,
		);
		$data['link_state'] = $this->ping_server($data);
		hg_fetch_query_sql($data, 'servers');
		$id = $this->db->insert_id();
		
		if($id)
		{
			//添加配置
			$info = $this->input['zh_name'];
			foreach($info as $k=>$v)
			{
				$extend = array(
					'sid' => $id,
					'zh_name' => $this->input['zh_name'][$k],
					'en_name' => $this->input['en_name'][$k],
					'value' => $this->input['value'][$k],
				);
				if(is_array($extend) && count($extend)>0)
				{	
					hg_fetch_query_sql($extend, 'servers_extend');
				}
			}
		}
		
		$this->redirect('添加成功');
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

		
		$data = array(
			'name' => $name,
			'type' => $this->input['type'],
			'brief' => $this->input['brief'], 	
			'ident' => $this->input['ident'],	
			'n_ip' => $this->input['n_ip'], 	
			'o_ip' => $this->input['o_ip'],
			'site_name' => urldecode($this->input['site_name']),
			'access_deal' => urldecode($this->input['access_deal']),

			'user_name' => urldecode($this->input['user_name']), 
			'password' => hg_encript_str(urldecode($this->input['password'])), 
			'token' => urldecode($this->input['token']),
				
			'port' => $this->input['port'], 
			'state' => $this->input['state'], 
			//'link_state' => $this->input['link_state'], 
			
			'update_time' => TIMENOW,
		);
		//如果密码不填,默认不更新
		if(!$this->input['password'])
		{
			unset($data['password']);
		}
		$data['link_state'] = $this->ping_server($data);
		hg_fetch_query_sql($data, 'servers', 'id=' . $id);
		
		//更新添加配置
		$info = $this->input['eid'];
		if(is_array($info) && count($info)>0)
		{
			foreach($info as $k=>$v)
			{
				//编辑时添加新字段
				if(!$v['eid'])
				{
					$extend = array(
						'sid' => $id,
						'zh_name' => $this->input['zh_name'][$k],
						'en_name' => $this->input['en_name'][$k],
						'value' => $this->input['value'][$k],
					);
					hg_fetch_query_sql($extend, 'servers_extend');
				}
				else
				{
					$extend = array(
						'zh_name' => $this->input['zh_name'][$k],
						'en_name' => $this->input['en_name'][$k],
						'value' => $this->input['value'][$k],
					);
					hg_fetch_query_sql($extend, 'servers_extend', 'eid=' . $v['eid']);
				}
			}
		}
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'servers WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			$affect_rows = $this->db->affected_rows();
			$this->redirect('成功删除' . $affect_rows . '条记录', 0, 0, '', 'hg_remove_row("' . $ids . '")');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}
	function del_extend()
	{
		$eid = $this->input['eid'];
		if (!$eid)
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
		if($eid)
		{
			$sql = 'DELETE FROM ' . DB_PREFIX . 'servers_extend WHERE eid =' . $eid;
			$this->db->query($sql);
			$this->redirect('删除成功');
		}
	}
	function ping_server($server_info = array())
	{
		if(!$server_info['type'])
		{
			return 0;
		}
		if($server_info['site_name'])
		{
			if($server_info['type'] == 5)
			{
				return $this->ping_db_server($server_info);
			}
			return $this->ping_other_server($server_info);
		}

		return 0;
	}
	//数据库连接测试
	function ping_db_server($server_info)
	{
		if(@mysql_connect($server_info['site_name'] .':'.$server_info['port'], $server_info['user_name'], hg_encript_str($server_info['password'], false)))
		{
			mysql_close();
			return 1;
		}
		return 0;
	}
	//其他服务器CURL测试 需要ping.php位于服务器的根目录
	function ping_other_server($server_info)
	{
		if(!class_exists('curl'))
		{
			include_once ROOT_PATH . 'lib/class/curl.class.php';
		}
		$test_uri = new curl($server_info['site_name'] .':'.$server_info['port']);
		$test_uri->initPostData();
		if(!$server_info['access_deal'])
		{
			$server_info['access_deal'] = 'http';
		}
		$test_uri->setRequestType($server_info['access_deal']);
		$ret = @$test_uri->request('ping.php');
		//exit($ret['message']);
		if( $ret['message']== "success")
		{
			return 1;
		}
		return 0;
	}
	//列表测试服务器联通
	function bash_ping_server()
	{
		$_sid = intval($this->input['sid']);

		$server_info = $this->db->query_first("SELECT * FROM ".DB_PREFIX.'servers WHERE id = '.$_sid);

		$text = '<span class="ping_failed">通信失败</span>';
		if($server_info['site_name'])
		{
			if($server_info['type'] == 5)
			{
				if($this->ping_db_server($server_info))
				{
					$text = '<span class="ping_success">通信成功</span>';
				}
			}
			else
			{
				if($this->ping_other_server($server_info))
				{
					$text = '<span class="ping_success">通信成功</span>';
				}
			}
		}
		echo 'document.getElementById(\'status_'.$_sid.'\').innerHTML = \''.$text.'\';testlink();';
		exit;
	}
	protected function check_api()
	{
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>