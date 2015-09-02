<?php
require('./global.php');
define('MOD_UNIQUEID', 'logindb');
class logindb_update extends adminBase
{
	var $filed;
	function __construct()
	{
		parent::__construct();
		/**
		 * 需要同步的表 key表名 value表示含义如下
		 *
		 * 
		 * 1=>完全重新复制结构和内容
		 * 
		 * 2=>同步结构，保留内容 未实现 只做了保留内容 结构未同步
		 * 
		 * 3=>重新复制结构，但不同步内容 原内容清空
		 * 
		 */
		$this->table =  array(
		DB_PREFIX . 'user_login'=>2, 
		DB_PREFIX . 'authinfo'=>1,
		);
		//模块表字段
		$this->filed = array(
		'host',
		'port',
		'user',
		'database',
		'charset',
		'pconnect',
		'status',
		'create_time',
		'user_id',
		'user_name',
		'update_time',
		'update_user_name',
		'update_user_id',
		'pass',
		);
		$this->verify_setting_prms();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function check_data_valid()
	{
		
	}
	function check_db($config)
	{
		global $gDBconfig;
		if(!$config)
		{
			$this->errorOutput("此服务器不允许添加，请选择另外的服务器");
		}
		$check_param = array('host', 'port','database');
		$gDBconfig['host'] = gethostbyname($gDBconfig['host']);
		$gDBconfig['port'] = $gDBconfig['port'] ? $gDBconfig['port'] : '3306';
		$config['host'] = gethostbyname($config['host']);
		$config['port'] = $config['port'] ? $config['port'] : '3306';
		$i = count($check_param);
		foreach ($gDBconfig as $k=>$v)
		{
			if(!in_array($k,$check_param))
			{
				continue;
			}
			if($v==$config[$k])
			{
				$i--;
			}
		}
		//$this->errorOutput(var_export($config,1).'a');
		if(!$i)
		{
			$this->errorOutput("此服务器不允许添加，请选择另外的服务器");
		}
	}
	function delete()
	{
		$data = array();
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'login_server WHERE id = '.$id;
		$_data = $this->db->query_first($sql);
		if(!$_data['id'])
		{
			$this->errorOutput('无效的数据纪录');
		}
		//删除数据库相应的表
		if($link = @mysql_connect($_data['host'] . ':' . $_data['port'], $_data['user'], hg_encript_str($_data['pass'],false)))
		{
			@mysql_select_db($_data['database']);
			$sql = 'DROP TABLE IF EXISTS ';
			foreach(array_keys($this->table) as $table)
			{
				$sql .= $table . ',';
			}
			@mysql_query(trim($sql, ','), $link);
			@mysql_close($link);
		}
		$sql = 'DELETE FROM ' . DB_PREFIX . 'login_server WHERE id  = ' . $id;
		$this->db->query($sql);
		$this->addLogs("删除服务器", $_data, array(), $_data['host'] . ':' . $_data['port']);
		$total = $this->db->query_first('SELECT COUNT(*) As total FROM '.DB_PREFIX.'login_server');
		if(!intval($total['total']))
		{
			@unlink(CACHE_DIR . 'loginserv.php');
		}
		$this->addItem($_data);
		$this->output();
	}
	function create()
	{
		$data = array();
		foreach($this->filed as $index)
		{
			$data[$index] = trim($this->input[$index]);
		}
		$this->check_db($data);
		$data['create_time'] = TIMENOW;
		$data['user_id'] = $this->user['user_id'];
		$data['user_name'] = $this->user['user_name'];
		$this->check_mysql_status($data);
		$data['pass'] = hg_encript_str($data['pass']);
		$this->check_data_valid();
		$sql = 'INSERT INTO ' . DB_PREFIX . 'login_server SET ';
		foreach ($data as $field=>$val)
		{
			$sql .= "`{$field}` = '{$val}',";
		}
		$sql = trim($sql, ',');
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$data['id'] = $this->db->insert_id();
		//记录日志
		$this->addLogs("创建登陆服务器", array(), $data, $data['host'] . ':' . $data['port']);
		//记录日志结束
		$this->addItem($data);
		$this->output();
	}
	function check_mysql_status($server = array())
	{
		$link = @mysql_connect($server['host'] . ':' . $server['port'], $server['user'], $server['pass']);		
		if(!$link)
		{
			$this->errorOutput('数据库连接失败，请检查mysql服务器是否允许远程连接！,用户名密码是否正确！');
		}
		if($server['database'])
		{
			@mysql_query('CREATE DATABASE IF NOT EXISTS ' . $server['database'], $link);
		}
		@mysql_close($link);
	}
	function update()
	{
		$data = array();
		$id = intval($this->input['id']);
		$sql = 'SELECT * FROM '.DB_PREFIX.'login_server WHERE id = '.$id;
		$_data = $this->db->query_first($sql);
		if(!$_data['id'])
		{
			$this->errorOutput('无效的数据纪录');
		}
		foreach($this->filed as $index)
		{
			$data[$index] = trim($this->input[$index]);
		}
		$this->check_db($data);
		$data['update_time'] = TIMENOW;
		$data['update_user_id'] = $this->user['user_id'];
		$data['update_user_name'] = $this->user['user_name'];
		//$this->errorOutput(var_export($data,1));
		$this->check_mysql_status($data);
		$data['pass'] = hg_encript_str($data['pass']);
		//$data['pass'] = '';
		$this->check_data_valid();
		$sql = 'UPDATE ' . DB_PREFIX . 'login_server SET ';
		foreach ($data as $field=>$val)
		{
			$sql .= "`{$field}` = '{$val}',";
		}
		$sql = trim($sql, ',')  . ' WHERE id = ' . $id;
		//$this->errorOutput($sql);
		$this->db->query($sql);
		$data['id'] = $id;
		//记录日志
		$this->addLogs("更新登陆服务器", $_data, $data, $data['host'] . ':' . $data['port']);
		//记录日志结束
		$this->addItem($data);
		$this->output();
	}
	public function build_logindb_cache()
	{
		$id = $this->input['id'];
		$sql = 'SELECT id,host,port,user,pass,`database`,`charset`,`pconnect` FROM ' . DB_PREFIX . 'login_server WHERE id IN('.$id.') ORDER BY id DESC';
		//$this->errorOutput($sql);
		$query = $this->db->query($sql);
		$cache = array();
		$ids = array();
		while($row = $this->db->fetch_array($query))
		{
			$ids[] = $row['id'];
			unset($row['id']);
			$cache[] = $row;
		}
		$cache && hg_file_write(CACHE_DIR . 'loginserv.php', "<?php\n \$servers = " . var_export($cache,1) . "\n?>");
		if($ids)
		{
			$this->db->query('UPDATE '.DB_PREFIX.'login_server SET status = 1 WHERE id IN('.implode(',', $ids).')');
			$this->db->query('UPDATE '.DB_PREFIX.'login_server SET status = 0 WHERE id NOT IN('.implode(',', $ids).')');
		}
		else
		{
			$this->db->query('UPDATE '.DB_PREFIX.'login_server SET status = 0');
		}
		$this->addItem($ids);
		$this->output();
	}
	public function unset_logindb_cache()
	{
		$id = $this->input['id'];
		if(!$id)
		{
			$this->errorOutput("无效的数据库服务器");
		}
		if($id)
		{
			//禁用的数据库
			$sql = 'UPDATE ' . DB_PREFIX . 'login_server SET status = 0 WHERE id IN(' . $id  . ')';
			$this->db->query($sql);
		}
		$sql = 'SELECT host,port,user,pass,`database`,`charset`,`pconnect` FROM ' . DB_PREFIX . 'login_server WHERE status=1 ORDER BY id DESC';
		//$this->errorOutput($sql);
		$query = $this->db->query($sql);
		$cache = array();
		while($row = $this->db->fetch_array($query))
		{
			$cache[] = $row;
		}
		hg_file_write(CACHE_DIR . 'loginserv.php', "<?php\n \$servers = " . var_export($cache,1) . "\n?>");
		$this->addItem(explode(',', $id));
		$this->output();
	}
	function syntable()
	{
		$id = $this->input['id'];
		$table = array_keys($this->table);
		$sql = 'SELECT id,host,port,user,pass,`database`,`charset`,`pconnect` FROM '.DB_PREFIX.'login_server WHERE id IN('.$id.') AND status=1';
		$query = $this->db->query($sql);
		$db = array();
		while($row = $this->db->fetch_array($query))
		{
			$row['pass'] = hg_encript_str($row['pass'], false);
			$dbserver[]  = $row;
		}
		//$this->errorOutput(var_export($dbserver,1));
		$table_struct = array();
		$data = array();
		if(!$dbserver)
		{	
			$this->errorOutput("没有可同步服务器");
		}
		foreach($table as $name)
		{
			$t = $this->db->query_first('SHOW CREATE TABLE ' . $name);
			$table_struct[$name] =  $t['Create Table'];
			if($this->table[$name] == 2)
			{
				$table_struct[$name] = str_replace('CREATE TABLE', 'CREATE TABLE IF NOT EXISTS', $table_struct[$name]);
			}
			if($this->table[$name] == 1)
			{
				$sql = 'SELECT * FROM ' . $name;
				$query = $this->db->query($sql);
				$data[$name] = 'INSERT INTO '.$name.' VALUES ';
				while($row = $this->db->fetch_array($query))
				{
					$data[$name] .= "('" . implode("','", $row) . "'),";
				}
				$data[$name] = trim($data[$name], ',');
			}			
		}
		include_once ROOT_PATH . 'lib/db/db_mysql.class.php';
		$ServDB = new db();
		foreach($dbserver as $server)
		{
			$ServDB->connect($server['host'], $server['user'], $server['pass'], $server['database'], $server['charset'], $server['pconnect']);
			
			foreach($table_struct as $tn=>$ts)
			{
				////删除原表
				//$ServDB->query('DROP TABLE  IF EXISTS '.$tn.';');
			
				if($this->table[$tn] == 3 || $this->table[$tn] == 1)
				{
					$ServDB->query('DROP TABLE  IF EXISTS '.$tn.';');
				}
				//创建表
				$ServDB->query($ts);
				
				//插入数据
				if($this->table[$tn] == 1)
				{
					$ServDB->query($data[$tn]);
				}
			}
			$ServDB->close();
		}
		$this->addItem($dbserver);
		$this->output();
	}
	function unknown()
	{
		//
	}
	
}
$o = new logindb_update();
$action = $_INPUT['a'];
$action = $action && method_exists($o, $action) ? $action : 'unknown';
$o->$action();
?>