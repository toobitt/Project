<?php
/***************************************************************************
* LivCMS5.0
* (C)2004-2010 HOGE Software.
*
* $Id: functions.php 31310 2013-11-05 03:56:25Z wangleyuan $
***************************************************************************/
function write_ini_file($array,$filename){ 
    $ok = ""; 
    $s = ""; 
    foreach($array as $k => $v){ 
        if(is_array($v))   
		{ 
            if($k!=$ok)   
			{ 
                $s .= "\r\n"."[$k] "."\r\n"; 
                $ok = $k; 
            } 
            $s.= write_ini_file($v, " "); 
        }
		else   
		{ 
            //if(trim($v)!= $v || strstr($v, "[ ")) 
			$v = "\"$v \""; 
            $s .= "$k = $v" . "\r\n"; 
        } 
    } 
    if($filename == "")
	{
        return $s;
	}
    else   
	{ 
        $fp = fopen($filename, "w"); 
        fwrite($fp,$s); 
        fclose($fp); 
    } 
} 


function convert_table_name($tableName)
{
    if(!$tableName)
    {
        return false;
    }
    if(is_array($tableName))
    {
        foreach($tableName as $k => $v)
        {
            $tableName[$k] = DB_PREFIX . $v;
        }
    }
    else
    {
        $tableName = DB_PREFIX . $tableName;    
    }
    return $tableName;
}   

?>