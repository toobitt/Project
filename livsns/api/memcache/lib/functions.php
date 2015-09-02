<?php

function file_in($dir,$filename,$strings,$type=false,$cover=false)
{  
		$path = trim($dir,'/');
	    if(!is_dir($path))
	    {
		    mkdir($path, 0777, true);
	    }
	    if(file_exists($path.'/'.$filename)&&!$cover)
	    {
	    	return false;
	    }
        if (!$type)
            file_put_contents($path.'/'.$filename, $strings, FILE_APPEND);
        else
            file_put_contents($path.'/'.$filename, $strings);
        return true;
}

?>