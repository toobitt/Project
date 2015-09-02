<?php
require('./global.php');
define('SCRIPT_NAME', 'datasetupdateapi');
class datasetupdateapi extends adminBase
{
	function __construct()
	{
		parent::__construct();		
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function update()
	{
		$id = intval($this->input['id']);
		if(!$id)
		{
			$this->errorOutput("无效数据");
		}
		//前置数据
		$sql = 'SELECT relyonids FROM '.DB_PREFIX.'data_set WHERE id = '.$id;
		$ret = $this->db->query_first($sql);
		$relyonids = $ret['relyonids'];
		$data = array(
			'dbtype'	=> $this->input['dbtype'],
			'count'		=> intval($this->input['freq']),
			'status'	=> intval($this->input['status']),
			'sql'		=> trim($this->input['sql']),
			'datadeal'	=> trim($this->input['datadeal']),
			'title'		=> trim($this->input['title']),
			'primarykey'=> 	$this->input['primary_key'],
			'sqlprimarykey'=> 	$this->input['sql_primary_key'],
		);
		$dbinfo = array(
			'host'	=> $this->input['dbhost'],
			'user'	=> $this->input['dbuser'],
			'database'=> $this->input['db'],
			'pass'	=> $this->input['dbpass'],
			'charset'	=> $this->input['charset'],
			'port'	=> $this->input['port'],
		);
		$dbinfo = serialize($dbinfo);
		$apiurl = array(
			'host'	=> $this->input['apiurlhost'],
			'dir'	=> $this->input['apiurldir'],
			'filename'=> $this->input['apiurlfilename'],
			'a'	=> $this->input['apiurla'],
		);
		$apiurl = serialize($apiurl);
		//$this->errorOutput(var_export($data,1));
		$source_data_sql = $this->input['source_data_sql'];
		$filed_maps = array();
		$source_fields = $this->input['source_fields'];
		$detin_fields = $this->input['detin_fields'];
		if(is_array($source_fields) && $source_fields)
		{
			foreach ($source_fields as $index=>$field)
			{
				$filed_maps[$field] = $detin_fields[$index];
			}
		}
		$filed_maps = serialize($filed_maps);
		//前置导入
		$import = array();
		if ($this->input['config_fields'] && is_array($this->input['config_fields']))
		{
			foreach ($this->input['config_fields'] as $key=>$value) 
			{
				$import[$value] = array(
					'id'=>$val,
					'field'=>$this->input['import_fields'][$key],
					'default'=>$this->input['default_fields'][$key],
				);
			}
		}
		if (!empty($import))
		{
			$data['beforeimport'] = serialize($import);
		}
		$sql = 'UPDATE ' . DB_PREFIX . 'data_set SET ';
		$data['dbinfo'] = $dbinfo;
		$data['apiurl'] = $apiurl;
		$data['paras'] = $filed_maps;
		//$this->errorOutput(var_export($data,1));
		foreach ($data as $key=>$val)
		{
			$sql .= "`{$key}` = '".addslashes($val)."',";
		}
		$sql = trim($sql, ',') . ' WHERE id = '.$id;
		$this->db->query($sql);

		$this->addItem($data);
		$this->output();
	}
	function create()
	{
		$data = array(
		'dbtype'	=> $this->input['dbtype'],
		'apiurl'	=> $this->input['apiurl'],
		'count'		=> intval($this->input['freq']),
		'status'	=> intval($this->input['status']),
		'sql'		=> trim($this->input['sql']),
		'datadeal'	=> trim($this->input['datadeal']),
		'title'		=> trim($this->input['title']),
		'primarykey'=> 	$this->input['primary_key'],
		'sqlprimarykey'=> 	$this->input['sql_primary_key'],
		);
		$dbinfo = array(
		'host'	=> $this->input['dbhost'],
		'user'	=> $this->input['dbuser'],
		'database'=> $this->input['db'],
		'pass'	=> $this->input['dbpass'],
		'charset'	=> $this->input['charset'],
		'port'	=> $this->input['port'],
		);
		$dbinfo = serialize($dbinfo);
		$apiurl = array(
			'host'	=> $this->input['apiurlhost'],
			'dir'	=> $this->input['apiurldir'],
			'filename'=> $this->input['apiurlfilename'],
			'a'	=> $this->input['apiurla'],
		);
		$apiurl = serialize($apiurl);
		//$this->errorOutput(var_export($data,1));
		$source_data_sql = $this->input['source_data_sql'];
		$filed_maps = array();
		$source_fields = $this->input['source_fields'];
		$detin_fields = $this->input['detin_fields'];
		if(is_array($source_fields) && $source_fields)
		{
			foreach ($source_fields as $index=>$field)
			{
				$filed_maps[$field] = $detin_fields[$index];
			}
		}
		$filed_maps = serialize($filed_maps);
		//前置导入
		$import = array();
		if ($this->input['config_fields'] && is_array($this->input['config_fields']))
		{
			foreach ($this->input['config_fields'] as $key=>$value) 
			{
				$import[$value] = array(
					'id'=>$val,
					'field'=>$this->input['import_fields'][$key],
					'default'=>$this->input['default_fields'][$key],
				);
			}
		}
		if (!empty($import))
		{
			$data['beforeimport'] = serialize($import);
		}
		$sql = 'INSERT INTO ' . DB_PREFIX . 'data_set SET ';
		$data['dbinfo'] = $dbinfo;
		$data['apiurl'] = $apiurl;
		$data['paras'] = $filed_maps;
		//$this->errorOutput(var_export($data,1));
		foreach ($data as $key=>$val)
		{
			$sql .= "`{$key}` = '".addslashes($val)."',";
		}
		$sql = trim($sql, ',');
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$id = $this->db->insert_id();
		$this->addItem($data);
		$this->output();
	}

	function delete()
	{
		$ids = $this->input['id'];
		if (!$ids)
		{
			$this->errorOutput(NOID);
		}
		$sql = 'SELECT relyonids FROM '.DB_PREFIX.'data_set WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$rids = array();
		while ($row = $this->db->fetch_array($query))
		{
			$rids[] = $row['relyonids'];
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'data_set WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		if (!empty($rids))
		{
			$relyids = implode(',', $rids);
			$sql = 'DELETE FROM '.DB_PREFIX.'data_rely WHERE id IN ('.$relyids.')';
			$this->db->query($sql);
		}
		$this->addItem($ids);
		$this->output();
	}
	function unknown()
	{
		
	}
}
$o = new datasetupdateapi();
$a = $_INPUT['a'];
if(!$a || !method_exists($o, $a))
{
	$a = 'unknown';
}
$o->$a();