<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :functions.php
 * package  :package_name
 * Created  :2013-5-28,Writen by scala
 * 
 ******************************************************************/
 /*
  * @function debug export var
  * @param:$fname
  * @param:$var 
  * @param:$line which line calls the function
  * @param:$file which file calls the function
  * 
  */
  
 function export_var($fname,$var,$line,$file,$flag=false)
 {
 	if(DEBUG_OPEN)
 	{
 		$path = realpath($fname);
 		$path_parts = pathinfo($path);
 		$content = $line."\n".$file."\n".var_export($var,1)."\n";
 		
 		if(@!file_put_contents($fname,$content)||!$flag)
 		{
 			echo "<div class='debug_export'>";
			echo $content;			
			echo "</div>";	
 		}//end if
 		
 	}//end if
 	
 }
 
 /*
  * 获取文件名称
  */
 function get_filename()
 {
 	 	$phpself = explode("/",$_SERVER['PHP_SELF']);
 		return substr($phpself[count($phpself)-1],0,-4);
 }
 
?>
