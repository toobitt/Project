<?php

function xml_filter($str)
{
    $str = preg_replace('/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/', '', $str);
    return $str;
}
 function xml_parser($str)
 { 
    $xml_parser = xml_parser_create(); 
    if(!xml_parse($xml_parser,$str,true)){ 
        xml_parser_free($xml_parser); 
        return false; 
    }else { 
        return (json_decode(json_encode(simplexml_load_string($str)),true)); 
    } 
}



function startTag($parser, $name, $attrs) 
{
   global $stack;
   $tag=array("name"=>$name,"attrs"=>$attrs);   
   array_push($stack,$tag);
  
}

function cdata($parser, $cdata)
{
    global $stack;//,$i;
    if(trim($cdata))
    {     
        $stack[count($stack)-1]['data']=$cdata;    
    }
}

function endTag($parser, $name)
{
   global $stack;   
   $stack[count($stack)-2]['children'][] = $stack[count($stack)-1];
   array_pop($stack);
}


?>