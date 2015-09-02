<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :defaultdata_cate_get_cate.php
 * package  :package_name
 * Created  :2013-7-31,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 
$fields = $formdata['data'];
if(is_array($fields))
{
	echo "<ul class=\"form_ul\">";
	foreach($fields as $k=>$v)
	{
		echo "<li class='i'><div class='form_ul_div clear'>";
		if($k=='index_pic')
			echo "<span class=\"title\">$v:</span><input type=\"file\" value='' name='Filedata'/>";
		else if($k=='content')
			echo "<span class=\"title\">$v:</span><textarea id=\"data[$k]\" cols=\"60\" rows=\"5\" name='data[$k]' value=''/></textarea>";
		else if($k=='brief')
			echo "<span class=\"title\">$v:</span><textarea id=\"data[$k]\" cols=\"60\" rows=\"5\" name='data[$k]' value=''/></textarea>";
		else
			echo "<span class=\"title\">$v:</span><input id=\"data[$k]\" type='text' name='data[$k]' value=''/>";
		echo "</div></li>";
	}
	echo "</ul>";
	
}	

?>
