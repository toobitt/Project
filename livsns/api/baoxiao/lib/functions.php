<?php
function hg_cny($ns) { 
	static $cnums = array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"), 
	$cnyunits = array("圆","角","分"), 
	$grees = array("拾","佰","仟","万","拾","佰","仟","亿"); 
	list($ns1,$ns2) = explode(".",$ns,2);
	$ns2 = array_filter(array($ns2[1],$ns2[0]));
	$ret = array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
	$ret = implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
	return $ns2 ? str_replace(array_keys($cnums),$cnums,$ret) : str_replace(array_keys($cnums),$cnums,$ret).'整';
}
function _cny_map_unit($list,$units)
{
    $ul = count($units);
    $xs = array();
    foreach(array_reverse($list) as $x)
    { 
        $l = count($xs); 
        if($x!="0" || !($l%4)) $n=($x=='0'?'':$x).($units[($l-1)%$ul]); 
        else $n = is_numeric($xs[0][0])?$x:''; 
        array_unshift($xs,$n); 
    } 
    return $xs; 
}