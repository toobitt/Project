<?php
	$file_path = './print.json';
	$method = $_REQUEST['a'];
	if( isset( $method ) && $method == 'log' ){
		$value = $_REQUEST['val'];
		if( !$value ){
			file_put_contents($file_path, "");	
		}else{
			if( !empty($value) && is_array($value) ){
				foreach ($value as $k=>$v)
				{
					$v1 = json_decode($v,1);
					$val_arr[] = $v1;
				}
				$allvalue = json_encode($val_arr);
				unset($value);
				$value = $allvalue;
			}
			else if(!json_decode($value,1))
			{
				$value1[] = $value;
				$value = json_encode($value1);
			}
			merge_data( $value );
		}
	}else if( isset( $method ) && $method == 'get' ){
		$value = file_get_contents( $file_path );
		echo $value;
	}
	
	function merge_data( $value ){
		$file_path = './print.json';
		$old_value = json_decode(file_get_contents( $file_path ),1);
		$val = json_decode( $value, 1 );
		if( $val ){
			$data[] = $val;
			$old_value ? $data = array_merge($data,$old_value ) : false;
		}
		file_put_contents($file_path, json_encode($data));	
	}
?>