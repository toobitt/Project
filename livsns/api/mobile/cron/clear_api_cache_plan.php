<?php
/***************************************************************************
* LivSNS 0.1
* (C)2004-2010 HOGE Software.
***************************************************************************/
define('MOD_UNIQUEID','clear_api_cache');
define('ROOT_PATH', '../../../');
define('CUR_CONF_PATH', '../');
require(ROOT_PATH."global.php");
require(CUR_CONF_PATH."lib/functions.php");
define('SCRIPT_NAME', 'ClearCache');
class ClearCache extends cronBase
{
	function __construct()
	{
		parent::__construct();
	}

	function __destruct()
	{
		parent::__destruct();
	}
	public function initcron()
	{
		$array = array(
			'mod_uniqueid' => MOD_UNIQUEID,	 
			'name' => '清理接口缓存',	 
			'brief' => '清理接口缓存',
			'space' => '86400',			//运行时间间隔，单位秒
			'is_use' => 0,				//默认是否启用
		);
		$this->addItem($array);
		$this->output();
	}
	
	public function show()
	{
		//清除缓存
		if(file_exists(CACHE_DIR))
		{
			$this->del_cache(CACHE_DIR);
		}
		$this->addItem('success');
		$this->output();
	}
	
	function del_cache($dir,$i='') 
	{
		//先删除目录下的文件：
	  	$dh=opendir($dir);
	  	
	  	
		if(!defined('CLEAR_API_CACHE_TIME') || CLEAR_API_CACHE_TIME <= 1)
		{
			$day_num = 2;
		}
		else 
		{
			$day_num = CLEAR_API_CACHE_TIME;
		}
		
		//清除缓存文件更新时间小于当前时间减去指定天数文件
		$before_time = $day_num * 86400;
	  	$i += 1;
	  	while ($file=readdir($dh)) 
	  	{
	  		$clear_time = TIMENOW - $before_time;
	  		
	    	if($file!="." && $file!="..") 
	    	{
	      		$fullpath=$dir."/".$file;
	      		
	      		//file_put_contents('2.txt',$fullpath.'+++'.$i.'*', FILE_APPEND);
	      		
	      		if(!is_dir($fullpath)) 
	      		{
	      			
	      			$update_time = '';
	      			$update_time = filemtime($fullpath);
	      			
	      			if(!$update_time)
	      			{
	      				$update_time = filectime($fullpath);
	      			}
	      			
	      			//文件更新时间小于设定时间才删除
	      			if($update_time < $clear_time && $file != 'index.html')
	      			{
	          			unlink($fullpath);
	      			}
	      		} 
	      		else 
	      		{
	      			//file_put_contents('3.txt', $fullpath.$i."\n",FILE_APPEND);
	        		$this->del_cache($fullpath,$i);
	      		}
			}
	  	}
	  	closedir($dh);
	  	
	  	//file_put_contents('4.txt', $dir.$i."\n",FILE_APPEND);
	  	//删除当前文件夹：
	  	if(isEmptyDir($dir) && $dir !== CACHE_DIR)
	  	{
	  		rmdir($dir);
	  	}
	}
}

include(ROOT_PATH . 'excute.php');
?>