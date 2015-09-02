<?php
require_once ROOT_PATH . 'lib/class/curl.class.php';
class DataMigration extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($config, $ids = '')
	{
		if (empty($config) || $config['status'])
		{
			return false;	
		}
		
		//检测前置导入
		$beforeImport = $this->beforeImport($config);
		if (!$beforeImport)
		{
			return false;		
		}
		//hg_pre($beforeImport);exit();
		//前置完成进行数据导入
		$migration = $this->migration($beforeImport);
		//hg_pre($migration);exit();
		if (!$migration)
		{
			return false;
		}
		
		$ret = $this->behindImport($migration, $beforeImport['id']);
		return $ret;
	}
	//前置导入
	public function beforeImport($config)
	{
		
		if (!$config['relyonids'])
		{
			return $config;
		}
		
		$sql = 'SELECT * FROM '.DB_PREFIX.'data_set WHERE id IN ( '.$config['relyonids'].')';
		$query = $this->db->query($sql);
		$beforeImport = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['dbinfo'] = @unserialize($row['dbinfo']) ? unserialize($row['dbinfo']) : '';
			$row['paras'] = @unserialize($row['paras']) ? unserialize($row['paras']) : '';
			$row['apiurl'] = @unserialize($row['apiurl']) ? unserialize($row['apiurl']) : '';
			$row['format_runtime'] = $row['runtime'] ? date('Y-m-d H:i:s', $row['runtime']) : '';
			if ($row['relyonids'])
			{
				$relyids[$row['id']] = $row['relyonids'];
			}
			$row['beforeimport'] = @unserialize($row['beforeimport']) ? unserialize($row['beforeimport']) : '';
			$beforeImport[$row['id']] = $row;
		}
		
		//存在前置导入，首先进行前置导入
		if (!empty($beforeImport))
		{
			//如果存在前置，前置未开启停止导入
			foreach ($beforeImport as $key=>$val)
			{
				if ($val['status'])
				{
					return false;
				}
			}
			foreach ($beforeImport as $key=>$val)
			{
				if ($val['importstatus'] !=2)
				{
					$this->show($val);
				}
			}
		}
		return $config;
	}
	//数据迁移
	public function migration($config)
	{
		//hg_pre($config);exit();
		//关闭原数据库连接
		if (!class_exists('pdo'))
		{
			return false;
		}
		$dsn = $config['dbtype'].':'.'host='.$config['dbinfo']['host'].':'.$config['dbinfo']['port'].';dbname='.$config['dbinfo']['database'];
		//检测数据库连接状态
		try 
		{
			$this->pdolink = new PDO($dsn, $config['dbinfo']['user'], $config['dbinfo']['pass']);
		}
		catch (PDOException $e)
		{
			return false;
		}
		//设置编码
		$this->pdolink->query('set names '.$config['dbinfo']['charset']);
		//检测是否有数据更新
		if (!$config['sql'])
		{
			return false;
		}
		$sql = $config['sql'].' WHERE 1 AND '.$config['sqlprimarykey'].'>'.$config['offset'];
		$query = $this->pdolink->prepare($sql);
		$query->execute();
		$fetchrow = $query->fetch(PDO::FETCH_ASSOC);
		if (!$fetchrow)
		{
			//数据全部导入完毕
			$this->_importstatus($config['id'], 2);
			return false;
		}
		$num = $config['offset'].'+'.$config['count'];
		$sql = $config['sql'].' WHERE 1 AND '.$config['sqlprimarykey'].'>'.$config['offset'].' AND '.$config['sqlprimarykey'].'<='.$num;
		$query = $this->pdolink->prepare($sql);
		$query->execute();
		$arr = array();
		//前置关系数据处理
		$beforeimportfield = array();
		$beforeimportrelation = array();
		if ($config['relyonids'])
		{
			$beforeimport = $config['beforeimport'];
			if ($beforeimport && is_array($beforeimport))
			{
				foreach ($beforeimport as $key=>$val)
				{
					$beforeimportfield[] = $val['field'];
					$beforeimportrelation[$val['field']] = $val;//将关联字段做键值
				}
			}
		}
		//hg_pre($beforeimportfield);exit();
		//hg_pre($beforeimportrelation);exit();
		
		$beforeimportfield = array_filter($beforeimportfield);//过滤空值
		$search = array();
		
		while ($row = $query->fetch(PDO::FETCH_ASSOC))
		{
			if ($config['datadeal'])
			{
				eval($config['datadeal']);
			}
			if (!empty($beforeimportfield))
			{
				foreach ($beforeimportfield as $field)
				{
					if ($row[$field])
					{
						$search[$beforeimportrelation[$field]['id']][] = $row[$field];
					}
				}
			}
			$arr[$row[$config['primarykey']]] = $row;
		}
		//hg_pre($search);exit();
		//hg_pre($arr);exit();
		
		//验证是否全部导入完成
		$sql = $config['sql'].' WHERE 1 AND '.$config['sqlprimarykey'].'>'.$num;
		$query = $this->pdolink->prepare($sql);
		$query->execute();
		$fetchrow = $query->fetch(PDO::FETCH_ASSOC);
		//hg_pre($config);exit();
		if (!$fetchrow)
		{
			//数据全部导入完毕
			$this->_importstatus($config['id'], 2);
		}
		else 
		{
			$this->_importstatus($config['id'], 1);	
		}
		
		//查询转发数据关系
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$sqlcondition = '';
		//hg_pre($search);exit();
		foreach ($search as $key=>$val)
		{
			if (is_array($val) && !empty($val))
			{
				$temp = implode(',', $val);
				$sqlcondition .= ' AND ( sid = '.$key.' AND  cid IN ('.$temp.'))';
			}
		}
		//hg_pre($sqlcondition);exit();
		$sqlcondition = $sqlcondition ? $sqlcondition : ' AND id = -1';  //当不满足条件时，将条件置空
		$sql = 'SELECT * FROM '.DB_PREFIX.'data_relation WHERE 1 '.$sqlcondition;
		$query = $source->query($sql);
		$r = array();
		while ($row = $source->fetch_array($query))
		{
			if (!$row['rid'])
			{
				if ($beforeimport[$row['sid']]['default'])
				{
					$row['rid'] = $beforeimport[$row['sid']]['default'];
				}
			}
			$r[$row['sid']][$row['cid']] = $row['rid'];
		}
		//hg_pre($config['paras']);exit();
		//未配置转发关系的，直接推出
		if (empty($config['paras']) || !$config['paras'])
		{
			return false;
		}
		$newData = array();
		$mapDataKeys = array_keys($beforeimportrelation);
		foreach ($arr as $row=>$rowdata)
		{
			if ($config['paras'] && is_array($config['paras']))
			{
				foreach ($config['paras'] as $key=>$val)
				{
					//将配置字段进行匹配转换
					/*
					if (is_string($rowdata[$val]))
					{
						$tmp = explode(',', $rowdata[$val]);
						foreach ($tmp as $kk=>$vv)
						{
							$tmp[$kk] = $r[$beforeimportrelation[$vv]['id']][$row];
						}
						$rowdata[$val] = implode(',', $tmp);
					}
					else 
					{
						$rowdata[$val] = $r[$beforeimportrelation[$val]['id']][$row];
					}
					*/
					//hg_pre($rowdata);exit();
					//$rowdata[$val] = $r[$beforeimportrelation[$val]['id']][$row];
					$relationValue = $r[$beforeimportrelation[$val]['id']][$row];
					//没有转发关系，则认为没有前置导入
					$rowdata[$val] = $relationValue ? $relationValue : $rowdata[$val];
					$newData[$row][$key] = $rowdata[$val];
				}
				//匹配出错直接清空
				$tmp = $newData[$row];
				$tmp = array_filter($tmp);
				if (empty($tmp))
				{
					unset($newData[$row]);
				}
				else 
				{
					$newData[$row]['dataplat_id'] = $row;
				}
				
			}
		}
		//hg_pre($newData);exit();
		//数据导入
		if (!$config['apiurl'] || empty($config['apiurl']))
		{
			return false;
		}
		$res = $this->_forward($config['apiurl'], $newData);
		return $res;
	}
	
	//更新导入状态
	private function _importstatus($id, $status = 0)
	{		
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$sql = 'UPDATE '.DB_PREFIX.'data_set SET importstatus = '.$status.' WHERE id = '.$id;		
		$source->query($sql);
		$source->close();
		return true;
	}
	//转发curl
	private function _forward($apiurl, $datas)
	{
		if (!$apiurl || !$datas || empty($datas))
		{
			return false;
		}
		//hg_pre($datas);exit();
		$arr = array();
		$this->curl = new curl($apiurl['host'], $apiurl['dir']);
		$this->curl->initPostData();
		$this->curl->setSubmitType('post');
		foreach ($datas as $key=>$val)
		{
			$dataplat_id = $val['dataplat_id'];
			unset($val['dataplat_id']);
			$this->curl->addRequestData('a', $apiurl['a']);
			//hg_pre($val);exit();
			foreach ($val as $k=>$v)
			{
				$this->array_to_add($k, $v);
			}
			$ret = $this->curl->request($apiurl['filename']);
			$arr[$key] = $ret[0];
			$arr[$key]['dataplat_id'] = $dataplat_id;
		}
		//hg_pre($arr);exit();
		return $arr;
	}
	
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if (is_array($data))
		{
			foreach ($data AS $kk => $vv)
			{
				if(is_array($vv))
				{
					$this->array_to_add($str . "[$kk]" , $vv);
				}
				else
				{
					$this->curl->addRequestData($str . "[$kk]", $vv);
				}
			}
		}
		else 
		{
			$this->curl->addRequestData($str, $data);
		}
	}
	//数据迁移后处理
	public function behindImport($data, $id)
	{
		//hg_pre($data);exit();
		//数据导入
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$offset = 0;
		if ($data && is_array($data) && !empty($data))
		{
			$sql = 'REPLACE INTO '.DB_PREFIX.'data_relation (id, sid, cid, rid) VALUES';
			$joint = '';
			foreach ($data as $key=>$val)
			{
				if ($offset < $key)
				{
					$offset = $key;
				}
				if ($val['id'])
				{
					$joint.= "('',".$id.",".$val['dataplat_id'].",".$val['id']." ) ,";
				}else
				{
					//失败数据处理
					
					//$this->_relation($config['id'], $key);
				}		
			}
			if ($joint)
			{
				$joint = rtrim($joint, ',');
				$sql .= $joint;
				$source->query($sql); 
			}
		}
		if ($offset)
		{
			$sql = 'UPDATE '.DB_PREFIX.'data_set SET offset = '.$offset.',runtime =' .TIMENOW. ' WHERE id = '.$id;		
			$source->query($sql);
		}
		$source->close();
		return true;
	}
}