<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :road_area.php
 * package  :package_name
 * Created  :2013-7-12,Writen by scala
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
?>
<span class="title">地区分类:</span>
{code}
foreach($road_areas as $key=>$val)
{
	if(array_key_exists($key,$road_area))
		echo $val['name']."<input type=\"checkbox\" name=\"area[]\" checked=\"checked\" value=\"$key\"> ";
	else
		echo $val['name']."<input type=\"checkbox\" name=\"area[]\" value=\"$key\"> ";
}
{/code}