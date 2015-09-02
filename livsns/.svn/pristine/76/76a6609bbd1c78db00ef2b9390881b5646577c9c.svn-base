<?php
class archiveCommon extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function __destruct()
	{
		parent::__destruct();
	}
	
	/**
	 * 
	 * @Description 检测数据库是否存在某张表，默认是数据库是当前连接的数据库
	 * @author Kin
	 * @date 2013-5-20 下午03:53:29
	 */
  	public function check_table_is_exist($find_table,$dbname='')
    {
        $dbname = $dbname ? $dbname : $this->db->dbname;
    	$sql = 'SHOW TABLES FROM '.$dbname;
    	$query = $this->db->query($sql);
    	$tables = array();
       	while ($row = $this->db->fetch_array($query))
       	{
       		$tables[] = $row['Tables_in_'.$dbname];
       	}
    	if (in_array(DB_PREFIX.$find_table, $tables))
    	{
    		return true;
    	}
    	else 
    	{
    		return false;
    	}
    }
    
    /**
     * 
     * @Description 检测表是否为空，为空则删该表
     * @author Kin
     * @date 2013-5-21 上午09:53:51
     */
    public function check_table_is_empty($table_name)
    {
    	$sql = 'SELECT * FROM '.DB_PREFIX.$table_name.' LIMIT 1	';
    	$query = $this->db->query($sql);
    	//当表为空时，删除表，只处理归档内容表
    	if ($this->db->num_rows($query) == 0)
    	{
    		$sql = 'DROP TABLE '.DB_PREFIX.$table_name;    		
    		$this->db->query($sql);
    	}
    	return true;
    }
    
    /**
     * 
     * @Description 主表名拼接规则
     * @author Kin
     * @date 2013-5-27 下午03:34:21
     */
    public function main_table_name($appMark, $moduleMark)
    {
    	if (!$appMark || !$moduleMark)
    	{
    		return false;
    	}
    	$year = date('Y',TIMENOW);
    	$name = $appMark.'_'.$moduleMark.'_'.$year;
    	return $name;
    }
    
    /**
     * 
     * @Description 内容表名拼接规则
     * @author Kin
     * @date 2013-5-27 下午03:38:26
     */
 	public function content_table_name($appMark, $moduleMark)
    {
    	if (!$appMark || !$moduleMark)
    	{
    		return false;
    	}
    	$year = date('Y',TIMENOW);
    	$name = $appMark.'_'.$moduleMark.'_'.$year.'_content';
    	return $name;
    }
    
	/**
	 * 
	 * @Description 获取应用信息
	 * @author Kin
	 * @date 2013-5-22 下午04:57:21
	 */
	public function get_app_infor($app_mark, $module_mark)
	{
		if (!$this->settings['App_auth'])
		{
			return false;
		}
		$this->curl = new curl($this->settings['App_auth']['host'], $this->settings['App_auth']['dir']);
		$this->curl->setSubmitType('get');
		$this->curl->setReturnFormat('json');
		$this->curl->initPostData();
		$this->curl->addRequestData('a','show');
		$this->curl->addRequestData('app_uniqueid',$app_mark);
		$this->curl->addRequestData('mod_uniqueid',$module_mark);
		$ret = $this->curl->request('modules.php');
		return  $ret[0];
	}
	
	public function recover_content($ids, $appInfor, $tableName)
	{
		$sql = 'SELECT * FROM '.DB_PREFIX.$tableName.'_content WHERE id IN ('.$ids.')';
		$query = $this->db->query($sql);
		$contents = array();
		while ($row = $this->db->fetch_array($query))
		{
			$contents[$row['id']] = unserialize($row['content']);
		}
		if (!empty($contents))
		{
			if($this->settings['App_' . $appInfor['app_mark']])
			{								
				$this->curl = new curl($this->settings['App_' . $appInfor['app_mark']]['host'], $this->settings['App_' . $appInfor['app_mark']]['dir'] . 'admin/');
				$this->curl->setSubmitType('post');
				$this->curl->setReturnFormat('json');
				$this->curl->initPostData();
				$this->curl->addRequestData('a', 'recover');
				$this->curl->addRequestData('content' , json_encode($contents));
				$this->curl->addRequestData('html',true);				
				$ret = $this->curl->request($appInfor['filename']. '_update.php');
				return true;
			}else {
				return false;
			}
		}
		
	}
}