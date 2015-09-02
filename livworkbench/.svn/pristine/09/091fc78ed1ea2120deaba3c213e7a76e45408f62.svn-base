<?php include('tpl/head.tpl.php');?>
 <h2><?php echo $stext;?>服务器</h2>
  <ul>
	<li style="float:&nbsp;left;margin-right:&nbsp;10px;"><a href="?">服务器管理</a></li>
  </ul>
  <div style="clear:&nbsp;both;"></div>
  <form action="?action=<?php echo $doaction;?>" method="post" name="form">
  <input type="hidden" name="id" value="<?php echo $id;?>" />
  <table width="98%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="35" width="100" align="right">名称:&nbsp;</td>
		<td>
			<input type="text" name="name" value="<?php echo $server['name'];?>" size="30" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;标识:&nbsp;<input type="text" name="mark" value="<?php echo $server['mark'];?>" size="20" />
		</td>
	</tr>
	<tr>
		<td height="35" width="100" align="right">ip:&nbsp;</td>
		<td>
			<input type="text" id="ip" name="ip" value="<?php echo $server['ip'];?>" size="30" onblur="check_server_connect();" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;port:&nbsp;<input type="text" id="port" name="port" value="<?php echo $server['port'];?>" size="10" onblur="check_server_connect();" />&nbsp;&nbsp;<span id="check_server_connect_result" style="color:red;display:none">请确认服务器已启动 LivMonitor.py 服务</span>
		</td>
	</tr>	
	<tr>
		<td height="35" width="100" align="right">外网IP:&nbsp;</td>
		<td>
			<input type="text" name="outip" value="<?php echo $server['outip'];?>" size="30" /> &nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td height="35" width="100" align="right">user:&nbsp;</td>
		<td>
			<input type="text" id="user" name="user" value="<?php echo $server['user'];?>" size="30" onblur="check_server_pass();" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;pass:&nbsp;<input type="text" name="pass" id="pass" onblur="check_server_pass();" value="<?php echo $server['pass'];?>" size="20" />&nbsp;&nbsp;<span id="check_server_pass_result" style="color:red;display:none">账号或密码错误</span>

		</td>
	</tr>	
	
	<tr>
		<td height="35" width="100" align="right">类型:&nbsp;</td>
		<td>
			<select name="type" id="type" onchange="change_servertype(this.value);">
			<option value="0">-请选择类型-</option>
			<?php
			foreach ($Cfg['servertype'] AS $k => $v)
			{
				if ($server['type'] == $k)
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
			</select>
			<select name="servtyp" id="servtyp"">
			<option value="0">-请选择服务器-</option>
			<?php
			foreach ($Cfg['server'] AS $k => $v)
			{
				if ($server['servtyp'] == $k)
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
			</select>
		</td>
	</tr>
	<tbody id="servertype">
		<tr>
		<td colspan="2"></td>
		</tr>
	</tbody>
	<tr>
		<td>&nbsp;</td>
		<td>
			<input type="submit" name="s" value="<?php echo $stext;?>" />
		</td>
	</tr>
  </table>
  </form>
<script type="text/javascript">
<!--
	setTimeout("<?php
	echo $autoload;
	?>", 500);
//-->
</script>
<?php include('tpl/foot.tpl.php');?>