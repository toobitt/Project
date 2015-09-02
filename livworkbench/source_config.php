<?php
define('WITH_DB', true);
define('ROOT_DIR', './');
define('SCRIPT_NAME', 'sourceConfig');
require('./global.php');
class sourceConfig extends uiBaseFrm
{	
	function __construct()
	{
		parent::__construct();
		if ($this->user['group_type'] != 1)
		{
			$this->ReportError('对不起，您没有权限管理来源配置!');
		}
		global $_INPUT, $gDB, $gGlobalConfig;
		$this->input = &$_INPUT;
		$this->db = &$gDB;
		$this->settings = &$gGlobalConfig;
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$modules = array();
		$sql = 'SELECT *  FROM ' . DB_PREFIX . 'source_config where 1 ORDER BY id ASC';
		$q = $this->db->query($sql);
		while ($row = $this->db->fetch_array($q))
		{
			$row['create_time'] = hg_get_date($row['create_time']);
			$row['apifile'] = 'http://' . $row['host'] . '/' . $row['dir'];
			$modules[$row['id']] = $row;
		}
		
		$list_fields = array(
			'id' => array('title' => 'ID', 'exper' => '$v[id]'), 
			'name' => array('title' => '配置名称', 'exper' => '$v[name]'),
			'apifile' => array('title' => '接口文件', 'exper' => '$v[apifile]'),
			'create_time' => array('title' => '创建时间', 'exper' => '$v[create_time]')
			);
			/*'func_name' => array('title' => '方法名', 'exper' => '$v[func_name]'),*/
		$op = array(
			'form' => array(
				'name' =>'配置', 
				'brief' =>'',
				'link' => '?a=form'),
			'edit' => array(
				'name' =>'内容设置', 
				'brief' =>'',
				'link' => '?a=edit'),
			'import' => array(
				'name' =>'导入', 
				'brief' =>'',
				'link' => '?a=import'),
			'delete' => array(
				'name' =>'删除', 
				'brief' =>'',
				'attr' =>' onclick="return hg_ajax_post(this, \'删除\', 1);"',
				'link' => '?a=delete'),
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
		$this->tpl->addVar('list', $modules);
		$this->tpl->outTemplate('source_config');
	}

	public function form($message = '')
	{
		$id = intval($this->input['id']);
		if ($id)
		{
			$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . $id);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			$a = 'update';
			$optext = '更新';
		}
		else
		{
			$formdata = $this->input;
			$a = 'create';
			$optext = '添加';
		}
		
		$this->tpl->addVar('optext', $optext);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->addVar('a', $a);
		$this->tpl->outTemplate('source_config_form');
		exit;
	}
	
	public function edit($message = '')
	{
		$id = intval($this->input['id']);
		/*$sql = 'SELECT m.*, a.host AS ahost, a.dir AS adir, a.token AS atoken FROM ' . DB_PREFIX . 'modules m LEFT JOIN ' . DB_PREFIX . 'applications a ON m.application_id = a.id WHERE m.id=' . $id;
		file_put_contents('13.txt',$sql);exit;*/
		$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		
		$config = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . $id);
		$conn = mysql_connect($config['db_server'],$config['account'],$config['password']); 
		if(!$conn) 
		{ 
			echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
			exit(0); 
		} 
		$flag = mysql_select_db($config['db'],$conn); 
		if(!$flag) 
		{ 
			echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
			exit(0); 
		} 
		
		$sql = "SHOW TABLES";
		$q = @mysql_query($sql);
		while($row = @mysql_fetch_array($q,MYSQL_NUM))
		{
			$dbinfo[] = $row[0];
		}	
		$dbinfo['-1'] = '- 请选择  -';
		
		$formdata['dbinfo'] = $dbinfo;
		$formdata['db_condition'] = unserialize($formdata['db_condition']);
		$formdata['mdbcolumn'] = $this->get_column($formdata['db_condition']['mdbinfo']['mdb'],$id);
		$formdata['ldbcolumn'] = $this->get_column($formdata['db_condition']['ldbinfo']['ldb'],$id);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->addVar('message', $message);
		$this->tpl->outTemplate('source_config_edit');
		exit;
	}
	
	function pub_setting($id)
	{
		if ($id)
		{	
			$sql = 'SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . $id;
			$formdata = $this->db->query_first($sql);
			if (!$formdata)
			{
				$this->ReportError('指定记录不存在或已删除!');
			}
			//执行接口程序
			$host = $formdata['host'];
			$dir = $formdata['dir'];
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($host,$dir);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', '__getModelDict');
			$this->curl->addRequestData('model_name',$formdata['model_name']);
			$return = $this->curl->request($formdata['file_name']);
			return $return[0];
		}
	}
		
	public function edited()
	{	
		if(intval($this->input['id']))
		{
			file_put_contents('1.txt',intval($this->input['id']));
			$config = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . intval($this->input['id']));
			$conn = mysql_connect($config['db_server'],$config['account'],$config['password']); 
			//print_r($conn);
			if(!$conn) 
			{ 
				echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
				exit(0); 
			} 
			$flag = mysql_select_db($config['db'],$conn); 
			if(!$flag) 
			{ 
				echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
				exit(0); 
			} 
		}
		$sql = "SHOW TABLES";
		$q = @mysql_query($sql);
		while($row = @mysql_fetch_array($q,MYSQL_NUM))
		{
			$dbinfo[] = $row[0];
		}	
		$mdb = intval($this->input['mdb']);
		
		$tablename = $dbinfo[$mdb];
		$sql_ = "SHOW FULL COLUMNS FROM {$tablename}";
		$q_ = @mysql_query($sql_);
		while($row = @mysql_fetch_array($q_,MYSQL_NUM))
		{
			$column[] = $row[0];
		}	
		echo json_encode($column);
	}
	
	public function get_column($db,$id,$flag)
	{	
		$config = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . $id);
		$conn = mysql_connect($config['db_server'],$config['account'],$config['password']); 
		if(!$conn) 
		{ 
			echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
			exit(0); 
		} 
		$re = mysql_select_db($config['db'],$conn); 
		if(!$re)
		{ 
			echo"<p align=center>在链接数据库系统数据库里发生了意外,请速与系统管理员取得联系,谢谢!</p>"; 
			exit(0); 
		} 
		$sql = "SHOW TABLES";
		$q = @mysql_query($sql);
		while($row = @mysql_fetch_array($q,MYSQL_NUM))
		{
			$dbinfo[] = $row[0];
		}	
		$dbname= $dbinfo[$db];
		$sql_ = "SHOW FULL COLUMNS FROM {$dbname}";
		$q_ = @mysql_query($sql_);
		while($row = @mysql_fetch_array($q_,MYSQL_NUM))
		{	
			if($flag)
			{
				$column[] = $dbname.'.'.$row[0];
			}
			else
			{
				$column[] = $row[0];
			}
		}
		return $column;
	}	
	
	public function edit_update($message = '')
	{
		$id = intval($this->input['id']);
		$serdb = unserialize(urldecode($this->input['serdb']));
		$content_id = $this->db->query_first('SELECT id FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		if($content_id)
		{
			$db_info = array(
					'mdbinfo'  => array('mdb'=>$this->input['mdb'],'mdbkey'=>$this->input['mdbkey'],'mdblink'=>$this->input['mdblink'],'mdbname'=>$serdb[$this->input['mdb']]),
					'ldbinfo'  => array('ldb'=>$this->input['ldb'],'ldbkey'=>$this->input['ldbkey'],'ldblink'=>$this->input['ldblink'],'ldbname'=>$serdb[$this->input['ldb']]),
			);
			$db_condition = serialize($db_info);
			$data = array(
					'config_id'				=> $id,
					'db_condition'			=> $db_condition,	
					'create_time' 			=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content', 'config_id=' . $id);
		}
		else
		{
			$db_info = array(
					'mdbinfo'  => array('mdb'=>$this->input['mdb'],'mdbkey'=>$this->input['mdbkey'],'mdblink'=>$this->input['mdblink'],'mdbname'=>$serdb[$this->input['mdb']]),
					'ldbinfo'  => array('ldb'=>$this->input['ldb'],'ldbkey'=>$this->input['ldbkey'],'ldblink'=>$this->input['ldblink'],'ldbname'=>$serdb[$this->input['ldb']]),
			);
			$db_condition = serialize($db_info);
			$data = array(
					'config_id'				=> $id,
					'db_condition'			=> $db_condition,	
					'create_time' 			=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content');
		}
		$mcolumn = $this->get_column($this->input['mdb'],$id,1);
		$lcolumn = $this->get_column($this->input['ldb'],$id,1);
		$formdata['dbcolumn'] = array_merge($mcolumn,$lcolumn);
		
		$formdata['field'] = $this->pub_setting($id);

		$sql = 'SELECT field_condition FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id;
		$r = $this->db->query_first($sql);
		$formdata['field_condition'] = unserialize($r['field_condition']);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->outTemplate('source_config_flink');
	}
	
	public function import($message = '')
	{
		$id = intval($this->input['id']);
		$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->outTemplate('source_config_import');
		exit;
	}

	public function upimport()
	{	
		//print_r($this->input['addition']);exit;
		$id = intval($this->input['id']);
		$content_id = $this->db->query_first('SELECT id FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		if($content_id)
		{
			$data = array(		
				'num' 					=> intval($this->input['num']),	
				'addition' 	  			=> intval($this->input['addition']),
				'create_time' 			=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content', 'config_id=' . $id);
			//$this->redirect('更新成功');
		}
		else
		{
			$data = array(	
				'config_id'				=> intval($this->input['id']),
				'addition'				=> intval($this->input['addition']),
				'num' 					=> intval($this->input['num']),
				'create_time' 			=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content');
			//$this->redirect('添加成功');
		}
		$this->importdata($id);
	}
	
	public function importdata($id)
	{
		$content = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		
		$db_condition = unserialize($content['db_condition']);
		$f = unserialize($content['field_condition']);
		$con = unserialize($content['content_condition']);
//		/print_r($con);exit;
		$data = array('db'=>$db_condition,'where'=>$content['content_condition']);
		$mdb = $data['db']['mdbinfo'];
		$ldb = $data['db']['ldbinfo'];
		if (!$data['db'])
		{
			return false;
		}
		$sql = 'SELECT * FROM ' . $mdb['mdbname'];
		if ($ldb)
		{
			$sql .= ' LEFT JOIN ' . $ldb['ldbname'] . ' ON ' . $mdb['mdbname'].'.'.$mdb['mdblink'] . ' = ' . $ldb['ldbname'].'.'.$ldb['ldblink'];
		}
		
		$sql .= '  WHERE 1';
		if($con)
		{
			foreach($con as $k=>$v)
			{
				$sql .= '  AND ' . $k.' '.$v;
			}
		}
		if ($content['num'])
		{
			$sql .=' LIMIT ' . $content['num'];
		}			
		//print_r($sql);exit();
		$config = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config  WHERE id=' . $id);
		$ndbhost = $this->db->dbhost;
		$ndb = $this->db->dbname;
		if($ndbhost == $config['db_server']&&$ndb == $config['db'])
		{
			while($row = $this->db->fetch_array($sql))
			{ 
	      	  $info[] = $row;
			}
		}
		elseif($ndbhost == $config['db_server']&&$ndb != $config['db'])
		{
			mysql_select_db($config['db']);
			while($row = $this->db->fetch_array($sql))
			{ 
	      	  $info[] = $row;
			}
			mysql_select_db($ndb);
		}
		elseif($ndbhost != $config['db_server']&&$ndb != $config['db'])
		{
			$dbcharset = 'utf8';
			$link = @mysql_connect($config['db_server'], $config['account'],$config['password']);
			if (!$link)
			{
				$this->ReportError('无法连接数据库或数据库帐号密码不正确');
			}
			$version = @mysql_get_server_info($link);
			
			if($version < '4.3')
			{
				$this->ReportError('数据库版本要求4.3以上，请升级数据库');
			}
			@mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $link);
			$s = @mysql_select_db($config['db'], $link);
			if($s)
			{	
				$re = mysql_query($sql,$link);
				while($row = mysql_fetch_assoc($re))
				{ 
		      	  $info[] = $row;
				}
			}
			//执行接口程序
			include_once(ROOT_PATH . 'lib/class/curl.class.php');
			$this->curl = new curl($config['host'],$config['dir']);
			$this->curl->setSubmitType('post');
			$this->curl->setReturnFormat('json');
			$this->curl->initPostData();
			$this->curl->addRequestData('a', $config['function']);
			
			$asort = explode(',',$config['sort']);
			foreach($asort as $k=>$v)
			{
				$bsort = explode('=>',$v);
				$sortinfo[$bsort[0]] = $bsort[1];
			}

			$datainfo = array();
			foreach($info as $key=>$value)
			{	
				foreach($sortinfo as $ke=>$va)
				{	
					if( $ke == $value['sort_id'])
					{
						$value['sort_id'] = $va;
					}					
				}
				foreach($f as $k=>$v)
				{
					$datainfo[$v['f']] = $value[$k];
				}	
				$d[] = $datainfo;
				foreach($datainfo as $ke => $va)
				{
					$this->curl->addRequestData($ke,$va);
				}
				$this->curl->addRequestData('html',true);
				$ret= $this->curl->request($config['file_name']);
				$reid = $ret[0]['id'];
			}
			$sql_ = "UPDATE ". DB_PREFIX . "source_config_content SET lastid = ".$reid ." WHERE config_id = ".$id;
			$this->db->query($sql_);
			if($reid)
			{
				$this->redirect('数据导入成功');
			}
		}
	}
	
	function build_sql($data)
	{	
		$mdb = $data['db']['mdbinfo'];
		$ldb = $data['db']['ldbinfo'];
		if (!$data['db'])
		{
			return false;
		}
		$sql = 'SELECT * FROM ' . $mdb['mdbname'];
		if ($ldb)
		{
			$sql .= ' LEFT JOIN ' . $ldb['ldbname'] . ' ON ' . $mdb['mdbname'].'.'.$mdb['mdblink'] . ' = ' . $ldb['ldbname'].'.'.$ldb['ldblink'];
		}
		
		$sql .= '  WHERE 1';
		if ($data['where'])
		{
			$sql .= '  AND ' . $data['where'];
		}
		return $sql;
	}
	
	
	//字段关联
	public function flink_edit()
	{	
		//file_put_contents('1.txt',var_export($this->input,true));exit;
		$id = intval($this->input['id']);
		$column = unserialize(urldecode($this->input['column']));
		$field_info = unserialize(urldecode($this->input['field_info']));
		//print_r($field_info);
		if($field_info)
		{
			foreach($field_info as $k=>$v)
			{	
				$re = explode('.',$column[$this->input[$k]]);
				$f = 'field_'.$k;
				$fields[$k] = array('v'=>$this->input[$k],'f'=>$re[1]);
				$fields[$k][$f] = $this->input[$f];
			}
		}
		/*print_r($this->input['field_indexpic']);
		print_r($fields);
		exit;*/
		//file_put_contents('1as.txt',var_export($fields,true));exit;
		$content_id = $this->db->query_first('SELECT id FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		if($content_id)
		{
			$data = array(
				'field_condition' 			=> serialize($fields),	
				'create_time' 				=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content', 'config_id=' . $id);
			//$this->redirect('更新成功');
		}
		else
		{
			$data = array(	
				'config_id'					=> $id,
				'field_condition' 			=> serialize($fields),	
				'create_time' 				=> TIMENOW, 		
			);
			hg_fetch_query_sql($data, 'source_config_content');
			
		}
		$con = $this->db->query_first('SELECT content_condition FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		$formdata['column'] = $column;
		$formdata['addcondition'] = unserialize($con['content_condition']);
		//print_r(unserialize($content_condition));exit;
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->outTemplate('source_config_addcondition');
			
	}	
	
	public function add_condition()
	{
		$id = intval($this->input['id']);
		if($this->input['where'])
		{
			$content_condition = urldecode($this->input['where']);
		}
		else
		{
			$columninfo = unserialize($this->input['columninfo']);
			
			foreach ($columninfo as $k=>$v)
			{
				if($this->input[$k])
				{
					$content_condition[$v] = $this->input[$k];
				}
			}
		}		
		$content_id = $this->db->query_first('SELECT id FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		if($content_id)
		{
			$data = array(
				'content_condition' 		=> serialize($content_condition),	
				'create_time' 				=> TIMENOW, 	
			);
			hg_fetch_query_sql($data, 'source_config_content', 'config_id=' . $id);
			//$this->redirect('更新成功');
		}
		else
		{
			$data = array(	
				'config_id'					=> $id,
				'content_condition' 		=> serialize($content_condition),	
				'create_time' 				=> TIMENOW, 		
			);
			hg_fetch_query_sql($data, 'source_config_content');
			
		}
		
		$formdata = $this->db->query_first('SELECT * FROM ' . DB_PREFIX . 'source_config_content  WHERE config_id=' . $id);
		$this->tpl->addVar('formdata', $formdata);
		$this->tpl->outTemplate('source_config_import');
	}
	public function create()
	{
		$name = urldecode($this->input['name']);
		if (!$name)
		{
			$this->form('<font color="red">请填写配置名称</font>');
		}
		//配置数据
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'db_server' => $this->input['db_server'],
			'account' => $this->input['account'], 	
			'password' => $this->input['password'], 	
			'db' => $this->input['db'],
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'],
			'model_name' => $this->input['model_name'],
			'file_name' => $this->input['file_name'], 
			'function' => $this->input['function'], 
			'codefmt' => $this->input['codefmt'],  	
			'sort' => $this->input['sort'], 	
			'create_time' => TIMENOW, 	
		);
		hg_fetch_query_sql($data, 'source_config');
		$node_id = $this->db->insert_id();
		if($node_id)
		{
			$this->redirect('添加成功');
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
			$this->form('<font color="red">请填写名称</font>');
		}
		//print_r($this->input['sort']);exit;
		$data = array(
			'name' => $name, 	
			'brief' => $this->input['brief'], 	
			'db_server' => $this->input['db_server'],
			'account' => $this->input['account'], 	
			'password' => $this->input['password'], 	
			'db' => $this->input['db'],
			'host' => $this->input['host'], 
			'dir' => $this->input['dir'],
			'model_name' => $this->input['model_name'],
			'file_name' => $this->input['file_name'], 
			'function' => $this->input['function'], 
			'codefmt' => $this->input['codefmt'],  	
			'sort' => $this->input['sort'], 	
			'create_time' => TIMENOW, 	
		);
		hg_fetch_query_sql($data, 'source_config', 'id=' . $id);
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
			$sql = 'DELETE FROM ' . DB_PREFIX . 'source_config WHERE id IN (' . $ids . ')';
			$this->db->query($sql);
			//再删除source_config的对应数据
			$sql = "DELETE FROM " . DB_PREFIX . "source_config_content 	WHERE config_id IN (" . $ids . ")";
			$this->db->query($sql);
			$this->redirect('删除成功');
		}
		else
		{
			$this->ReportError('指定记录不存在或已删除!');
		}
	}
}
include (ROOT_PATH . 'lib/exec.php');
?>