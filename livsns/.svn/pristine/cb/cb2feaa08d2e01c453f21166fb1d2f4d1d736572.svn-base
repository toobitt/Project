<?php
require_once(ROOT_PATH. 'lib/class/curl.class.php');
require_once(CUR_CONF_PATH.'lib/archive_common.class.php');
class archive extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
		$this->common = new archiveCommon();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	
	public function show($condition,$orderby,$offset,$count)
	{
		$limit = " limit {$offset}, {$count}";
		$sql = 'SELECT a.*,s.name FROM '.DB_PREFIX.'archive a 
				LEFT JOIN '.DB_PREFIX.'archive_sort s ON a.sort_id = s.id
				WHERE 1 '.$condition.$orderby.$limit;
		$query = $this->db->query($sql);
		$k = array();
		while ($row = $this->db->fetch_array($query))
		{
			$row['create_time'] = date('Y-m-d H:i', $row['create_time']);
			$k[] = $row;
		}
		return $k;	
	}
	
	
	public function count($condition)
	{
		$sql = 'SELECT COUNT(*) AS total FROM '.DB_PREFIX.'archive WHERE 1 '.$condition;
		$ret = $this->db->query_first($sql);
		return $ret;
	}
	
	
	public function detail($id)
	{
		
	}
	
	/**
	 * 
	 * @Description 删除
	 * @author Kin
	 * @date 2013-5-20 下午05:34:45
	 */
	public function delete($ids)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.'archive WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$archive = array();
		$tables = array();
		$table_archive_id = array();
		while ($row = $this->db->fetch_array($query))
		{
			$archive[] = $row;
			$tables[] = $row['table_name'];
			$table_archive_id[$row['table_name']][] = $row['id'];      //表和归档id对应关系
		}
		$tables = array_unique($tables);
		if (!empty($tables))
		{
			foreach ($tables as $table)
			{
				$check_table = $this->common->check_table_is_exist($table);
				if ($check_table)
				{
					$archive_ids = implode(',', $table_archive_id[$table]);
					$sql = 'DELETE FROM '.DB_PREFIX.$table.' WHERE archive_id IN ('.$archive_ids.')';
					$this->db->query($sql);
					//表为空就删除表
					$this->common->check_table_is_empty($table);
				}
				$check_table_content = $this->common->check_table_is_exist($table.'_content');
				if ($check_table_content)
				{
					$archive_ids = implode(',', $table_archive_id[$table]);
					$sql = 'DELETE FROM '.DB_PREFIX.$table.'_content WHERE archive_id IN ('.$archive_ids.')';
					$this->db->query($sql);
					//表为空就删除表
					$this->common->check_table_is_empty($table.'_content');
				}
			}
		}
		$sql = 'DELETE FROM '.DB_PREFIX.'archive WHERE id IN ('.$ids.')';
		$this->db->query($sql);
		return $ids;
	}
	
	public function create($data)
	{
		//检测是否存在应用
		$sql = 'SELECT * FROM '.DB_PREFIX.'archive_sort WHERE app_mark="'.$data['app_mark'].'" AND module_mark="'.$data['module_mark'].'"';
		$sortInfor = $this->db->query_first($sql);
		if (empty($sortInfor))
		{
			//获取应用信息
			$appInfor = $this->common->get_app_infor($data['app_mark'], $data['module_mark']);
			if ($appInfor)
			{
				//创建分类
				$sortInfor = $this->create_archive_sort($appInfor);
			}else {
				return false;
			}
			
		}
		$sort_id = $sortInfor['id'];
		//检测是否存在主表
		$mainTableName = $this->common->main_table_name($data['app_mark'], $data['module_mark']);
		if (!$mainTableName)
		{
			return false;
		}
		$result = $this->common->check_table_is_exist($mainTableName);
		if (!$result)
		{
			$main_table = $this->create_main_table($mainTableName);
			if (!$main_table)
			{
				return false;
			}
		}
		//检测是否存在内容表
		$contentTableName = $this->common->content_table_name($data['app_mark'], $data['app_mark']);
		if (!$contentTableName)
		{
			return false;
		}
		$result = $this->common->check_table_is_exist($contentTableName);
		if (!$result)
		{
			$content_table = $this->create_content_table($contentTableName);
			if (!$content_table)
			{
				return false;
			}
		}
		
		//插入archive表
		$archiveData = array(
			'title'			=> $data['name'],
			'table_name'	=> $mainTableName,
			'sort_id'		=> $sort_id,
			'create_time'	=> TIMENOW,
			'user_id'		=> $data['archive_user']['user_id'],
			'user_name'		=> $data['archive_user']['user_name'],
			'ip'			=> $data['archive_user']['ip'],
		);
		$sql = 'INSERT INTO '.DB_PREFIX.'archive SET ';
		foreach ($archiveData as $key=>$val)
		{
			$sql .= $key.'="'.addslashes($val).'",';
		}
		$sql = rtrim($sql,',');
		$this->db->query($sql);
		$archivId = $this->db->insert_id();
		//更新order_id，默认与ID值相同
		$u_sql = 'UPDATE '.DB_PREFIX.'archive SET order_id = '.$archivId.' WHERE id ='.$archivId;
		$this->db->query($u_sql);
		
		//插入内容主表
		if (!empty($data['content']))
		{
			foreach ($data['content'] as $key=>$val)
			{
				$mainData = array(
					'archive_id'=>$archivId,
					'title'=>$val['title'],
					'sort_id'=>$sort_id,
					'create_time'=>$val['create_time'],
					'user_id'=>$val['user_id'],
					'user_name'=>$val['user_name'],
					'ip'=>$val['ip'],
				);
				$sql = 'INSERT INTO '.DB_PREFIX.$mainTableName.' SET ';
				foreach ($mainData as $kk=>$vv)
				{
					$sql .= $kk.'="'.addslashes($vv).'",';
				}
				$sql = rtrim($sql,',');
				$this->db->query($sql);
				$mainId = $this->db->insert_id();
				//更新order_id，默认与ID值相同!!!!!!
				$u_sql = 'UPDATE '.DB_PREFIX.$mainTableName.' SET order_id = '.$mainId.' WHERE id ='.$mainId;				
				$this->db->query($u_sql); 
				//插入内容表
				$c_sql = 'INSERT INTO ' . DB_PREFIX .$contentTableName.'(id,archive_id,content) 
						VALUES('. $mainId .','. $archivId .',"'.addslashes(serialize($val['content'])).'")';
				$this->db->query($c_sql);
			}
			return $archivId;
		}
		else 
		{
			return false;	
		}
	}
	
	/**
	 * 
	 * @Description  创建应用分类
	 * @author Kin
	 * @date 2013-5-22 下午05:06:21
	 */
	public function create_archive_sort($infor)
	{
		if (!$this->settings['App_archive'])
		{
			return false;
		}
		$this->curl = new curl($this->settings['App_archive']['host'], $this->settings['App_archive']['dir'].'admin/');
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','create');
		$this->array_to_add('appInfor',$infor);
		$ret = $this->curl->request('archive_node_update.php');
		return  $ret[0];
	}
	
	public function array_to_add($str , $data)
	{
		$str = $str ? $str : 'data';
		if(is_array($data))
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
	}

  	/**
     * 
     * @Description  创建归档内容主表
     * @author Kin
     * @date 2013-5-22 下午05:35:20
     */
    public function create_main_table($tableName)
    {
    	if (!$tableName)
    	{
    		return false;
    	}
    	$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$tableName."` (
				  `id` int(10) NOT NULL AUTO_INCREMENT,
				  `archive_id` int(10) NOT NULL COMMENT '归档id',
				  `title` varchar(100) NOT NULL COMMENT '标题',
				  `sort_id` int(10) NOT NULL COMMENT '分类id',
				  `create_time` int(10) NOT NULL,
				  `user_id` int(10) NOT NULL,
				  `user_name` varchar(50) NOT NULL,
				  `ip` varchar(50) NOT NULL,
				  `order_id` int(10) NOT NULL,
				  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    	$this->db->query($sql);
    	return true;
    }
    
    /**
     * 
     * @Description 创建归档内容子表
     * @author Kin
     * @date 2013-5-22 下午05:58:54
     */
    public function create_content_table($tableName)
    {
    	if (!$tableName)
    	{
    		return false;
    	}
    	$sql = "CREATE TABLE IF NOT EXISTS `".DB_PREFIX.$tableName."` (
			  `id` int(10) NOT NULL,
			  `archive_id` int(10) NOT NULL COMMENT '归档id',
			  `content` text NOT NULL,
			  UNIQUE KEY `id` (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
    	$this->db->query($sql);
    	return true;
    }
    
    /**
     * 
     * @Description  还原归档
     * @author Kin
     * @date 2013-5-29 上午10:16:34
     */
    public function recover_archive($ids)
    {
    	$sql = 'SELECT * FROM '.DB_PREFIX.'archive WHERE id IN ('.$ids.')';
    	$query = $this->db->query($sql);
    	$sortIds = array();
    	$tables = array();
    	while ($row = $this->db->fetch_array($query))
    	{
    		$sortIds[$row['id']] = $row['sort_id'];
    		$tables[$row['id']] = $row['table_name'];
    		
    	}
    	if (!empty($sortIds))
    	{
    		$sortIds = array_unique($sortIds);
    		$sql = 'SELECT * FROM '.DB_PREFIX.'archive_sort WHERE id IN ('.implode(",", $sortIds).')';    		
    		$query = $this->db->query($sql);
    		$appInfor = array();
    		while ($row = $this->db->fetch_array($query))
    		{
    			$appInfor[$row['id']] = array(
    									'app_mark'=>$row['app_mark'],
    									'module_mark'=>$row['module_mark'],
    								); 
    		}
    		if (!empty($appInfor))
    		{
    			foreach ($appInfor as $key=>$val)
    			{
    				$app = $this->common->get_app_infor($val['app_mark'], $val['module_mark']);
    				if (empty($app))
    				{
    					return false;
    				}
    				$appInfor[$key]['filename'] = $app['file_name']; 
    			}
    		}    		
    		$id_arr =  explode(',', $ids);
    		if (!empty($id_arr))
    		{
    			foreach ($id_arr as $key=>$val)
    			{
    				$sql = 'SELECT id FROM '.DB_PREFIX.$tables[$val].' WHERE archive_id = '.$val;    				
    				$query = $this->db->query($sql);
    				$archive_ids = array();
    				while ($row = $this->db->fetch_array($query))
    				{
    					$archive_ids[] = $row['id'];
    				}
    				if (!empty($archive_ids))
    				{
						$archive_id = implode(',', $archive_ids);
    					$ret = $this->common->recover_content($archive_id, $appInfor[$sortIds[$val]], $tables[$val]);
    					if (!$ret)
    					{
    						return false;
    					}
    				}
    			}
    			//删除归档
    			$del = $this->delete($ids);
    			if (!$del)
    			{
    				return false;
    			}
    			return $ids;
    		}
    	}
    }
}