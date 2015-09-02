<option value="-1">-请选择数据库-</option>
<?php
foreach ($dbserver AS $k => $v)
{
	if ($db == $k)
	{
		$selected = ' selected="selected"';
	}
	else
	{
		$selected = '';
	}
?>
	<option value="<?php echo $k;?>"<?php echo $selected;?>><?php echo $v['name']?></option>
<?php
}
?>