<?php

	function get_tablename($bundle_id,$module_id,$struct_id,$struct_ast_id = '')
	{
		return strtolower($bundle_id.'_'.$module_id.'_'.$struct_id.(empty($struct_ast_id)?'':('_'.$struct_ast_id)));
	}
	
	function file_in($dir,$filename,$strings,$type=false)
	{  
		$path = trim($dir,'/');
	    if(!is_dir($path))
	    {
		    mkdir($path, 0777, true);
	    }
	    if(file_exists($path.'/'.$filename))
	    {
	    	return false;
	    }
        if ($type == false)
            file_put_contents($path.'/'.$filename, $strings, FILE_APPEND);
        else
            file_put_contents($path.'/'.$filename, $strings);
        return true;
	}
	

?>