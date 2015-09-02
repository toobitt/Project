<?php
/*******************************************************************
 * LivSNS 0.1
 * (C) 2004-2013 HOGE Software
 * 
 * filename :test.php
 * package  :package_name
 * Created  :2013-7-29,Writen by scala yuanzhigang@scalachina.com
 * export_var(get_filename()."_b.txt",var,__LINE__,__FILE__);
 * 
 ******************************************************************/
 $tbname = $_INPUT['tbname'];
 echo "<input type='hidden' name='tbname' value='".$tbname."' />";
 echo "<table>";
 if(is_array($formdata))
 {
 	foreach($formdata as $key=>$data)
 	{
 		if($data['Field']=='id')
 			continue;
 		echo "<tr><td>".$data['Comment']."</td><td><input type'text' name='formdata[".$data['Field']."][]' value=''/></td></tr>";
  	}
 }
 echo "</table>";
?>
