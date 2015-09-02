<tr>
	<td height="35" width="100" align="right">域名:&nbsp;</td>
	<td>
		<input type="text" name="mediaserver[domain]" value="<?php echo $mediaserver['domain'];?>" size="30" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目录:&nbsp;<input type="text" name="mediaserver[dir]" id="mediaserver_dir" value="<?php echo $mediaserver['dir'];?>" size="60" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">上传目录:&nbsp;</td>
	<td>
		<input type="text" name="mediaserver[uploaddir]" id="mediaserver_uploaddir" value="<?php echo $mediaserver['uploaddir'];?>" size="80" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
		
		域名：<input type="text" name="mediaserver[uploaddomain]" value="<?php echo $mediaserver['uploaddomain'];?>" size="80" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">转码后目录:&nbsp;</td>
	<td>
		<input type="text" name="mediaserver[targetdir]" id="mediaserver_targetdir" value="<?php echo $mediaserver['targetdir'];?>" size="80" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>