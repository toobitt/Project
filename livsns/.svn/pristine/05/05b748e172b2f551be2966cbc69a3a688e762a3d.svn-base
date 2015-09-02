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
	
	//多维数组合并 $array2覆盖$array1
	function multi_array_merge($array1,$array2)
	{  
		if (is_array($array2) && count($array2))
		{
			//不是空数组的话  
			foreach ($array2 as $k=>$v)
			{  
				if (is_array($v) && count($v))
				{  
					$array1[$k] = multi_array_merge($array1[$k], $v);  
				}
				else 
				{  
					if (!empty($v))
					{  
						$array1[$k] = $v;  
					}  
				}  
			}  
		}
		else 
		{  
			$array1 = $array2;  
		}  
		return $array1;  
	}  
	
	//改变数组键值
	function array_change_key($arr,$str='a',&$new_arr)
	{
		foreach($arr as $k=>$v)
		{
			if(is_array($v))
			{
				array_change_key($v,$str,&$new_arr[$str.$k]);
			}
			else
			{
				$new_arr[$str.$k] = $v;
			}
		}
	}
	
	

?>