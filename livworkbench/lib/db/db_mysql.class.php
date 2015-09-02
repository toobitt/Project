<?php
class db
{
	private $querynum = 0;
	private $link;
	private $histories;

	public $dbhost;
	public $dbname;
	public $dbuser;
	private $dbpw;
	private $dbcharset;
	private $pconnect;
	private $tablepre;
	private $time;
	public $mErrorExit = true;

	public $goneaway = 5;

	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $dbcharset = '', $pconnect = 0, $tablepre='', $time = 0) 
	{
		$this->dbhost = $dbhost;
		$this->dbuser = $dbuser;
		$this->dbpw = $dbpw;
		$this->dbname = $dbname;
		$this->dbcharset = $dbcharset;
		$this->pconnect = $pconnect;
		$this->tablepre = $tablepre;
		$this->time = $time;

		if($pconnect) 
		{
			if(!$this->link = mysql_pconnect($dbhost, $dbuser, $dbpw)) 
			{
				$this->halt('Can not connect to MySQL server');
			}
		} 
		else 
		{
			if(!$this->link = mysql_connect($dbhost, $dbuser, $dbpw)) 
			{
				$this->halt('Can not connect to MySQL server');
			}
		}

		if($this->version() > '4.1') 
		{
			if($dbcharset) 
			{
				mysql_query("SET character_set_connection=".$dbcharset.", character_set_results=".$dbcharset.", character_set_client=binary", $this->link);
			}

			if($this->version() > '5.0.1') 
			{
				//mysql_query("SET sql_mode=''", $this->link); //关闭严格模式
			}
		}

		if($dbname) 
		{
			mysql_select_db($dbname, $this->link);
		}

	}

	function fetch_array($query, $result_type = MYSQL_ASSOC) 
	{
		return mysql_fetch_array($query, $result_type);
	}

	function select_db($dbname)
	{
		if($dbname) 
		{
			mysql_select_db($dbname, $this->link);
		}
	}
	function result_first($sql) 
	{
		$query = $this->query($sql);
		return $this->result($query, 0);
	}

	function query_first($sql) 
	{
		$query = $this->query($sql);
		return $this->fetch_array($query);
	}

	function fetch_all($sql, $id = '') 
	{
		$arr = array();
		$query = $this->query($sql);
		while($data = $this->fetch_array($query)) 
		{
			$id ? $arr[$data[$id]] = $data : $arr[] = $data;
		}
		return $arr;
	}

	function query($sql, $type = '', $cachetime = FALSE) 
	{
		$func = $type == 'UNBUFFERED' && @function_exists('mysql_unbuffered_query') ? 'mysql_unbuffered_query' : 'mysql_query';
		if(!($query = $func($sql, $this->link)) && $type != 'SILENT') 
		{
			$this->halt('MySQL Query Error', $sql);
		}
		$this->querynum++;
		$this->histories[] = $sql;
		return $query;
	}

	function affected_rows() 
	{
		return mysql_affected_rows($this->link);
	}

	function error() 
	{
		return (($this->link) ? mysql_error($this->link) : mysql_error());
	}

	function errno() 
	{
		return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
	}

	function result($query, $row) 
	{
		$query = @mysql_result($query, $row);
		return $query;
	}

	function num_rows($query) 
	{
		$query = mysql_num_rows($query);
		return $query;
	}

	function num_fields($query) 
	{
		return mysql_num_fields($query);
	}

	function free_result($query) 
	{
		return mysql_free_result($query);
	}

	function insert_id() 
	{
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	function fetch_row($query) 
	{
		$query = mysql_fetch_row($query);
		return $query;
	}

	function fetch_fields($query) 
	{
		return mysql_fetch_field($query);
	}

	function version() 
	{
		return mysql_get_server_info($this->link);
	}

	function close() 
	{
		return mysql_close($this->link);
	}

	function halt($message = '', $sql = '') 
	{
		if (!$this->mErrorExit)
		{
			return;
		}
		$error = mysql_error();
		$errorno = mysql_errno();
		if($errorno == 2006 && $this->goneaway-- > 0) 
		{
			$this->connect($this->dbhost, $this->dbuser, $this->dbpw, $this->dbname, $this->dbcharset, $this->pconnect, $this->tablepre, $this->time);
			$this->query($sql);
		}
		else 
		{
			include_once(ROOT_PATH . 'lib/func/debug.php');
			$s = '<strong>version:</strong>' . $this->version() . '<br />';
			$s = '<strong>Error:</strong>' . $error . '<br />';
			$s .= '<strong>Errno:</strong>' . $errorno . '<br />';
			$s .= '<strong>SQL:</strong>:' . $sql;
			exit($s . $trace);
		}
	}
}

?>