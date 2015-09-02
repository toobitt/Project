<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
*
* $Id: auto_vod_record.php 481 2012-01-14 01:13:19Z repheal $
***************************************************************************/

define('WITH_DB', true);
define('WITHOUT_LOGIN', true);
define('ROOT_DIR', '../');
define('SCRIPT_NAME', 'UpgradeDb');
require('../global.php');
require('./upgrade.frm.php');
header('HTTP/1.1 200 OK',true,200);
$_INPUT['pre_release'] = 1;
class UpgradeDb extends upgradeFrm
{	
	var $dbname;
	private $mDbDir;
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}

	public function show()
	{
		$app = $this->mApp;
		$this->mDbDir = $this->mProductDir . $app . '/' . $this->mVersion . '/db/';
		if ($app && $app != 'livworkbench')
		{
			$m2odatasql = $this->mDbDir . 'm2odata.m2o';
			$data['applications'] = $this->getApplication($app);
			$data['modules'] = $this->getModules($data['applications']['id']);
			$data['node'] = $this->getNodes($data['applications']['id']);
			$moduleids = array_keys($data['modules']);
			if ($moduleids)
			{
				$moduleids = implode(',', $moduleids);
				$data['module_node'] = $this->getModuleNodes($moduleids);
				$data['module_op'] = $this->getModuleOps($moduleids);
				$data['module_append'] = $this->getModuleAppend($moduleids);
			}
			file_put_contents($m2odatasql, json_encode($data));
			$this->dbname = $this->getDbName($app);
			$this->db->select_db($this->dbname);
		}
		else
		{
			$this->dbname = $this->db->dbname;
			$app = 'livworkbench';
			$this->mDbDir = $this->mProductDir . $app . '/' . $this->mVersion . '/db/';
		}
		$structs = $this->getDbStruct();
		$updatesql = $this->mDbDir . 'update.m2o';
		$createsql = $this->mDbDir . 'create.m2o';
		$initdatasql = $this->mDbDir . 'initdata.m2o';
		file_put_contents($updatesql, json_encode($structs['update']));
		file_put_contents($createsql, json_encode($structs['create']));
		echo $app . $this->mVersion. '数据库发布成功';
	}

	private function getApplication($app)
	{
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'applications WHERE softvar=\'' . $app . "'";
		return $this->db->query_first($sql);
	}
	private function getModules($appid)
	{
		if(!$appid)
		{
			return array();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'modules WHERE application_id=' . $appid . ' ORDER BY menu_pos ASC';
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		return $data;
	}
	private function getNodes($appid)
	{
		if(!$appid)
		{
			return array();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'node WHERE application_id=' . $appid;
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		return $data;
	}
	private function getModuleNodes($module_ids)
	{
		if (!$module_ids)
		{
			return array();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_node WHERE module_id IN (' . $module_ids . ')';
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		return $data;
	}
	private function getModuleOps($module_ids)
	{
		if (!$module_ids)
		{
			return array();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_op WHERE module_id IN (' . $module_ids . ')';
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		return $data;
	}
	private function getModuleAppend($module_ids)
	{
		if (!$module_ids)
		{
			return array();
		}
		$sql = 'SELECT * FROM ' . DB_PREFIX . 'module_append WHERE module_id IN (' . $module_ids . ')';
		$q = $this->db->query($sql);
		$data = array();
		while($r = $this->db->fetch_array($q))
		{
			$data[$r['id']] = $r;
		}
		return $data;
	}


	private function getDbStruct()
	{
		if (!$this->dbname)
		{
			return array();
		}
		$tables = $this->getTables();
		$struct = array();
		$indexs = array();
		foreach ($tables AS $table)
		{
			if ($this->mApp == 'publishcontent')
			{
				$tmp = substr($table, strlen(DB_PREFIX));
				$tmp = explode('_', $tmp);
				if ($tmp[0] != 'content' && count($tmp) >= 3)
				{
					continue;
				}
			}
			if ($this->mApp == 'live')
			{
				$tmp = substr($table, strlen(DB_PREFIX));
				$tmp = explode('dvr', $tmp);
				if ($tmp[1])
				{
					continue;
				}
			}
			if ($this->mApp == 'access')
			{
				$tmp = substr($table, strlen(DB_PREFIX));
				$tmp = explode('_', $tmp);
				if ($tmp[0] == 'record' && $tmp[1])
				{
					continue;
				}
			}
			$sql = "SHOW FULL COLUMNS FROM {$table}";
			$queryid = $this->db->query($sql);
			while($row = $this->db->fetch_array($queryid))
			{
				$struct['update'][$table]['struct'][$row['Field']] = $row;
				if ($row['Key'])
				{
					$indexs[] = $row['Field'];
				}
			}
			$struct['update'][$table]['index'] = $this->getIndexs($table);
			$tablestruct = $this->getTableCreateSql($table);
			echo $table  . '<br />';
			if ($table == 'liv_merge')
			{
				$tablestruct = preg_replace('/UNION=\(.*?\)/is', 'UNION=()', $tablestruct);
			}
			$struct['create'][$table] = $tablestruct;
		}

		return $struct;
	}
	private function getInitData($app)
	{
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$curl = new curl('localhost', 'livsns/api/' . $app . '/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('str');
		$curl->initPostData();
		$curl->addRequestData('a', 'getInitData');
		return $curl->request('configuare.php');
	}

	private function getDbName($app)
	{
		include_once(ROOT_PATH . 'lib/class/curl.class.php');
		$curl = new curl('localhost', 'livsns/api/' . $app . '/');
		$curl->setSubmitType('post');
		$curl->setReturnFormat('str');
		$curl->initPostData();
		$curl->addRequestData('a', 'getDbName');
		return $curl->request('configuare.php');
	}
	
	private function getIndexs($table = 'test')
	{
		$sql = "SELECT DISTINCT * 
			FROM information_schema.statistics
			WHERE table_schema =  '{$this->dbname}'
			AND table_name =  '{$table}'";		
		$queryid = $this->db->query($sql);
		$indexs = array();
		while($row = $this->db->fetch_array($queryid))
		{
			$indexs[$row['NON_UNIQUE']][$row['INDEX_NAME']][$row['SEQ_IN_INDEX'] - 1] = $row['COLUMN_NAME'];
		}
		return $indexs;
	}

	private function getTables()
	{
		if (!$this->dbname)
		{
			return array();
		}
		$sql = "SHOW TABLES FROM " . $this->dbname;
		$queryid = $this->db->query($sql);
		$tables = array();
		while($row = $this->db->fetch_array($queryid))
		{
			$row['TABLE_NAME'] = $row['Tables_in_' . $this->dbname];
			$tables[] = $row['TABLE_NAME'];
		}
		return $tables;
	}

	private function getTableCreateSql($table)
	{
		$sql = 'SHOW CREATE TABLE ' . $table;
		$create = $this->db->query_first($sql);
		$sql = $create['Create Table'];
		$sql = str_replace('ENGINE=InnoDB', 'ENGINE=MyISAM', $sql);
		$sql = preg_replace('/ AUTO_INCREMENT=(\d+)/is', '', $sql);
		return $sql;
	}
}

include (ROOT_PATH . 'lib/exec.php');
?>
