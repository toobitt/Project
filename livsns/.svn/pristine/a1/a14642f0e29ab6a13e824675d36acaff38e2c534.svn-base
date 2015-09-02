<?php
require('./global.php');
define('SCRIPT_NAME', 'data_util');
class data_util extends adminBase
{
	function __construct()
	{
		parent::__construct();
		$this->init_env();
	}
	function __destruct()
	{
		parent::__destruct();
	}
	function init_env()
	{
		if(!class_exists('pdo'))
		{
			$this->errorOutput('无法找到PDO类，确定已正确安装！');
		}
	}
	function show()
	{
		$this->step1();
		
	}
	function step1()
	{
		 //$this->errorOutput(var_export(pdo::getAvailableDrivers(),1));
	}
	function step2()
	{
		if(!$this->input['type'])
		{
			$this->errorOutput("未知的操作");
		}
	}
	function step3()
	{
		$support = pdo::getAvailableDrivers();
		if(!$support)
		{
			$this->errorOutput("PDO不支持任何数据库驱动");
		}
		$this->addItem($support);
		$this->output();
	}
	function step4()
	{
		$source = array(
		'host' => $this->input['shost'],
		'username' => $this->input['susername'],
		'password' => $this->input['spassword'],
		'dbname'   => $this->input['sdbname'],
		'dbtype'   => $this->input['sdbtype'],
		);
		$destin = array(
		'host' => $this->input['dhost'],
		'username' => $this->input['dusername'],
		'password' => $this->input['dpassword'],
		'dbname'   => $this->input['ddbname'],
		'dbtype'   => $this->input['ddbtype'],
		);
		//$this->errorOutput(var_export($destin,1));
		//$dbtype = $dbtype ? $dbtype : 'mysql';
		$sdsn = $source['dbtype'] . ':dbname='.$source['dbname'].';host='.$source['host'];
		$ddsn = $destin['dbtype'] . ':dbname='.$destin['dbname'].';host='.$destin['host'];
		try {
		    $sdblink = new PDO($sdsn, $source['username'], $source['password']);
		} catch (PDOException $e) {
		   // echo 'Connection failed: ' . $e->getMessage();
		   $this->errorOutput('数据源连接失败');
		}
		//$this->errorOutput($sdsn);
		
		try {
			//$this->errorOutput(var_export($destin,1));
		    $ddblink = new PDO($ddsn, $destin['username'], $destin['password']);
		} catch (PDOException $e) {
		   // echo 'Connection failed: ' . $e->getMessage();
		   $this->errorOutput('目标数据库连接失败');
		}
		
		$sth = $sdblink->prepare('SHOW TABLES');
		$sth->execute();
		$source_table = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
		$sth = $ddblink->prepare('SHOW TABLES');
		$sth->execute();
		$destin_table = $sth->fetchAll(PDO::FETCH_COLUMN, 0);
		//$this->errorOutput(var_export($source_table,1));
		$output['source'] = $source_table;
		$output['destin'] = $destin_table;
		$this->addItem($output);
		$this->output();
		//
	}
	
	function step5()
	{
		$dtable = (array)$this->input['destin_table'];
		$stable = (array)$this->input['source_table'];
		
		//$this->errorOutput(var_export($dtable,1));
		try 
		{
		  $dbh = new PDO('mysql:host=127.0.0.1;dbname=test', 'root', 'hogesoft');
		  foreach ($dtable as $tab)
		  {
			  $stmt = $dbh->query("select * from ".$tab);
				
			  for($i=0; $i<$stmt->columnCount(); $i++) 
			  {
				$tmp = $stmt->getColumnMeta($i);
				$field['source'][$tab][] = $tmp['name'];
			  }
		  }
		  
		} 
		catch (PDOException $e)
		{
			//
		}
		$this->errorOutput(var_export($field,1));
	}
	function step6()
	{
			
	}
	function history()
	{
		
	}
}
include ROOT_PATH . 'excute.php';