<tr>
	<td height="35" width="100" align="right">域名:&nbsp;</td>
	<td>
		<input type="text" onkeyup="sync_text(this.value);" name="api[domain]" value="<?php echo $api['domain'];?>" size="30" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目录:&nbsp;<input type="text" id="api_dir" name="api[dir]" value="<?php echo $api['dir'];?>" size="60" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>
<?php 

$autoload = "";
$i = 0;
foreach($apps AS $k => $v)
{
	$i++;
?>
<tr>
<td height="35" width="100" align="right">&nbsp;</td><td style="font-weight:bold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $v['name'];?>&nbsp;&nbsp;<input type="checkbox" name="checked[]" <?php echo $api[$k]['checked'];?> value="<?php echo $k;?>" /></td>
</tr>
<tr>
	<td height="35" width="100" align="right">数据库:&nbsp;</td>
	<td>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		
		<select name="api[<?php echo $k;?>][db]" onchange="showdb('dbsel_api_<?php echo $k;?>', 'dbname_api_<?php echo $k;?>', this.value)">
		<?php
			$db = $api[$k]['db'];
			$var = $k;
			$t = $i * 200;
			if (is_array($db))
			{
				$autoload .= "setTimeout(function(){showdb('dbsel_api_{$var}', 'dbname_api_{$var}','{$db}');}, $t);";
			}
			include('tpl/db_select.tpl.php');
		?>
		</select>
		&nbsp;数据库名:<input type="text" id="dbname_api_<?php echo $var;?>" name="api[<?php echo $var;?>][dbname]" value="<?php echo $api[$var]['dbname'] ? $api[$var]['dbname'] : 'liv_' . $var;?>" size="20" />
		&nbsp;<span id="dbsel_api_<?php echo $var;?>">
	</span>
	</td>
</tr>
<?php
}
?>
<script type="text/javascript">
<!--
	<?php
	echo $autoload;
	?>
//-->
</script>