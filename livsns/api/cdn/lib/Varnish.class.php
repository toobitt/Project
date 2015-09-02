<?php
/*******************************************************************
 * filename :Varnish.class.php
 * Created  :2013年8月8日,Writen by scala 
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
class Varnish implements ICdnConf,ICdnFile
{
 	public function  __construct()
 	{
 		$this->obj = new Core();
 	}
 	
 	public function __destruct()
 	{
 		
 	}

	//cdn configure
	function getCdnConf()
	{
			
	}
	function addCdnConf()
	{

	}
	function delCdnConf()
	{

	}
	//cdn configure

	//cdn file op
	function addCdnFile()
	{

		
	}
	function delCdnFile()
	{
		
	}
	function getCdnFile()
	{
		
	}
    
	function updateCdnFile()
	{
		
	}
	//cdn file op
	public function pushfordb()
	{
		
	}
	public function push()
	{
		
	}
}
