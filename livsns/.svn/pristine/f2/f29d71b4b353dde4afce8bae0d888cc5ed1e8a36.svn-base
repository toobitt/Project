<?php
class ClassMigration extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}

	
	public function show($config,$ids='')
	{
		if (empty($config) || $config['status'])
		{
			return ;
		}
		$this->db->close();		
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
			//数据库异常将错误信息纪录
			$error = array(
				'error'=>'数据库错误',
				'error_info'=>$e->getMessage(),
			);
			return $error;
		}
		//设置编码
		try
		{
			$this->pdolink->query('set names '.$config['dbinfo']['charset']);
		}
		catch (PDOException $e)
		{
			$error = array(
				'error'=>'设置编码错误',
				'error_info'=>$e->getMessage(),
			);
			return $error;
		}
		//先前置插入
		$pre_import = array();
		$mapData = array();
		if ($config['relyonids'] && is_array($config['relyonids']))
		{
			foreach ($config['relyonids'] as $key=>$rely)
			{
				$pre_import[] = $rely['set_field'];
				$mapData[$rely['set_field']] = $rely['relation'];
			}
		}
		//主程序
		if ($ids)
		{
			$config['sql'] .= ' WHERE 1 AND '.$config['sqlprimarykey'].' IN ('.$ids.')';
		}
		else 
		{
			$num = $config['offset'].'+'.$config['count'];
			$config['sql'] .= ' WHERE 1 AND '.$config['sqlprimarykey'].'>'.$config['offset'].' AND '.$config['sqlprimarykey'].'<='.$num;
		}
		$query = $this->pdolink->prepare($config['sql']);
		$query->execute();
		$arr = array();
		$pre_data = array();
		while ($row = $query->fetch(pdo::FETCH_ASSOC))
		{
			if ($config['datadeal'])
			{
				eval($config['datadeal']);
			}
			if (!empty($pre_import))
			{
				foreach ($pre_import as $field)
				{					
					if ($row[$field] && !$mapData[$field][$row[$field]])
					{
						$pre_data[$field][] = $row[$field];
					}
				}
			}
			$arr[$row[$config['primarykey']]] = $row;
		}
		if (empty($arr))
		{
			return false;
		}
		//处理前置数据
		if (!empty($pre_data))
		{
			foreach ($pre_data as $key=>$val)
			{
				$pre_temp_data[$key] = implode(',', $val); 
			}			
			foreach ($config['relyonids'] as $relyid=>$rely)
			{
				if ($rely && $pre_temp_data[$rely['set_field']] && $rely['rely_field'])
				{
					$rely['sql'] .=  ' WHERE 1 AND '.$rely['rely_field'].' IN ('.$pre_temp_data[$rely['set_field']].')';					
					$query = $this->pdolink->prepare($rely['sql']);
					$query->execute();
					$relys = array();
					while ($row = $query->fetch(PDO::FETCH_ASSOC))
					{
						if ($rely['datadeal'])
						{
							eval($rely['datadeal']);
						}
						$relys[$row[$rely['primarykey']]] = $row;
					}
					if (!empty($relys))
					{
						$newRelyData = array();
						foreach ($relys as $row=>$relydata)
						{
							if ($rely['paras'] && is_array($rely['paras']))
							{
								foreach ($rely['paras'] as $key=>$val)
								{
									$newRelyData[$row][$key] = $relydata[$val];
								}
							}
						}
						if (!empty($newRelyData))
						{
							if ($rely['apiurl'])
							{
								$ret = $this->forward($rely['apiurl'], $newRelyData);
							}
						}
					}
					if ($ret)
					{
						foreach ($ret as $key=>$val)
						{
							if ($val)
							{
								$mapData[$rely['set_field']][$key] = $val[$rely['rely_field']];
							}
						}
					}
					if (!empty($mapData[$rely['set_field']]))
					{
						$this->update_rely_relation($relyid, $mapData[$rely['set_field']]);
					}
				}
			}
		}
		if ($mapData && !empty($mapData))
		{
			$mapDataKeys = array_keys($mapData);
		}
		$newData = array();
		foreach ($arr as $row=>$rowdata)
		{
			if ($config['paras'] && is_array($config['paras']))
			{
				foreach ($config['paras'] as $key=>$val)
				{
					if ($mapDataKeys && !empty($mapDataKeys))
					{
						if (in_array($val, $mapDataKeys))
						{
							$rowdata[$val] = $mapData[$val][$rowdata[$val]];  //如果是被前置的数据，继续数据值替换
						}
					}
					$newData[$row][$key] = $rowdata[$val];
				}
			}
		}
		if (empty($newData))
		{
			return false;
		}	
		$offet = 0;
		if ($config['apiurl'])
		{			
			$res = $this->forward($config['apiurl'], $newData);
			if ($res && is_array($res) && !empty($res))
			{
				foreach ($res as $key=>$val)
				{
					if ($offet < $key)
					{
						$offet = $key;
					}
					//失败数据
					if (!$val['id'])
					{
						$this->relation($config['id'], $key);
					}		
				}
			}
		}
		//更新记录
		$this->update_offset($config['id'], $offet);
		return true;
	}
	
	private function forward($url, $datas)
	{
		$arr = array();
		if (!$url)
		{
			return false;
		}
		$url .= strpos($url, '?') ? '&format=json' : '?format=json';
		if (defined('APPID') && defined('APPKEY'))
		{
			$url .='&appid='. APPID;
			$url .='&appkey='. APPKEY;
		}		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		foreach ($datas as $key=>$val)
		{
			curl_setopt($ch, CURLOPT_POSTFIELDS, $val);
			$rt = curl_exec($ch);
			$rt = json_decode($rt,1);
			$rt = $rt[0];
			$arr[$key] = $rt;
		}
		curl_close($ch);
		return $arr;
	}
	/**
	 * 
	 * @Description  更新记录
	 * @author Kin
	 * @date 2013-9-10 上午10:43:41
	 */
	private function update_offset($config_id, $offset = 0)
	{
		if (!$config_id)
		{
			return false;
		}
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$sql = 'UPDATE '.DB_PREFIX.'data_set SET offset = '.$offset.',runtime =' .TIMENOW. ' WHERE id = '.$config_id;
		$source->query($sql);
		$source->close();
		return true;
	}
	/**
	 * 
	 * @Description  记录错误数据
	 * @author Kin
	 * @date 2013-9-10 上午10:43:25
	 */
	private function relation($config_id, $id)
	{
		if (!$config_id)
		{
			return false;
		}
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$sql = 'INSERT INTO '.DB_PREFIX.'data_relation (id, set_id, data_id) VALUES ("",'.$config_id.',"'.addslashes($id).'")';
		$source->query($sql);
		$source->close();
		return true;
	}
	/**
	 * 
	 * @Description  记录转发前置数据转发关系
	 * @author Kin
	 * @date 2013-9-12 下午02:43:53
	 */
	private function update_rely_relation($id, $data)
	{
		if (!$id || !$data)
		{
			return false;
		}
		$data = serialize($data);
		$source = new db();
		$source->connect($this->db->dbhost, $this->db->dbuser, $this->db->dbpw, $this->db->dbname);
		$sql = 'UPDATE '.DB_PREFIX.'data_rely SET relation = "'.addslashes($data).'" WHERE id = '.$id;
		$source->query($sql);
		$source->close();
		return true;
	}
	
	
	
}