<?php
class mssql_db
{
	var $con = null;
	var $resource = null;
	
	function __construct()
	{
	}
	
	function connect($host = '127.0.0.1' , $user = 'sa' , $passwd = 'sa')
	{
		$this->con = mssql_pconnect($host , $user , $passwd);
		if(!$this->con)
		{
			$this->debug('Connect To Host : ' . $host .' Faild!');
		}
		$this->query('SET TEXTSIZE 2147483647'); 
		return $this->con;
	}
	
	function query($sql = '')
	{
		$this->resource = mssql_query($sql , $this->con);
		if(!$this->resource)
		{
			$this->debug('Query: ' . $sql . ' Faild!');
		}
		return $this->resource;
	}
	
	function fetch_array($resource = '')
	{
		!$resource && $resource = $this->resource;
		return @mssql_fetch_array($resource , MSSQL_ASSOC);
	}
	
	function query_first($sql = '')
	{
		$resource = $this->query($sql);
		return @mssql_fetch_array($resource , MSSQL_ASSOC);
	}
	
	function fetch_all($resource = '' , $result_type = MSSQL_ASSOC)
	{
		!$resource && $resource = $this->resource;
		$results = array();
		while(false !== ($row = @mssql_fetch_array($resource , $result_type)))
		{
			$results[] = $row;
		}
		return $results;
	}
	
	function select_db($dbname)
	{
		return @mssql_select_db($dbname , $this->con);
	}
	
	function num_rows()
	{
		return $this->affected_rows();
	}
	
	function affected_rows()
	{
		return @mssql_rows_affected($this->con);
	}
	
	function debug($message = '')
	{
		$message .= '<br />以下是 mssql 提供的错误信息：' . mssql_get_last_message();   
		exit($message);
	}
	
	function __destruct()
	{
		if($this->resource) @mssql_free_result($this->resource);
	}
}

?>