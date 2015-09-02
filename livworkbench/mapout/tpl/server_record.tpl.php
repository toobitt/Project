<tr>
	<td height="35" width="100" align="right">录制服务端口:&nbsp;</td>
	<td>
		<input type="text" name="record[port]" id="record_recorddir" value="<?php echo $record['port'] ? $record['port'] : 8089;?>" size="20" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">录制服务配置:&nbsp;</td>
	<td>
		<input type="text" name="record[confile]" id="record_conf" value="<?php echo $record['confile'] ? $record['confile'] : '/usr/local/RecordServer/etc/config.ini';?>" size="60"  onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>

