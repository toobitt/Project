<?php 
foreach($apps AS $k => $v)
{
?>
<tr>
<td height="35" width="100" align="right">&nbsp;</td><td><h3><?php echo $v['name'];?>&nbsp;&nbsp;<input type="checkbox" name="checked[]" <?php echo $app[$k]['checked'];?> value="<?php echo $k;?>" /></h3></td>
</tr>
<tr>
	<td height="35" width="100" align="right">域名:&nbsp;</td>
	<td>
		<input type="text" name="app[<?php echo $k;?>][domain]" value="<?php echo $app[$k]['domain'];?>" size="30" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目录:&nbsp;<input type="text" id="app_<?php echo $k;?>_dir" name="app[<?php echo $k;?>][dir]" value="<?php echo $app[$k]['dir'];?>" size="60" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>
<?php
}
?>