<?php

function  xml_filter($str)
{
	$str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/','',$str);
	return $str;
}

function mk_column_url($row)
{
	$result = '';
	if($row['father_domain'])
	{
		$result .= $row['father_domain'];
	}
	else
	{
		$result .= $row['sub_weburl'];
	}
	$result .= '.'.$row['weburl'];
	if($row['relate_dir'])
	{
		$result .= '/'.trim($row['relate_dir'],'/');
	}
	$row['colindex'] = trim($row['colindex'],'.');
	if($row['colindex'] != 'index' && $row['colindex'])
	{
		$result .= '/'.$row['colindex'].'.php';
	}
	return 'http://'.$result;
			
}

?>