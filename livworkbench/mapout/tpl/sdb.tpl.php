<?php 
if($dbserver)
{
?>
<tr>
	<td height="35" width="100" align="right">选择数据库:&nbsp;</td>
	<td>
	<select name="db" onchange="showdb('dbsel', 'dbname', this.value)">
<?php 
	$db = $server['db'];

	$autoload = "showdb('dbsel', 'dbname','{$db}');";
	include('tpl/db_select.tpl.php');
}
?>
	</select>
	&nbsp;数据库名:<input type="text"id="dbname" name="dbname" value="<?php echo $server['dbname'];?>" size="20" />
	&nbsp;<span id="dbsel">
	</span>
	</td>
</tr>
<script type="text/javascript">
<!--
	setTimeout("<?php
	echo $autoload;
	?>", 500);
//-->
</script>