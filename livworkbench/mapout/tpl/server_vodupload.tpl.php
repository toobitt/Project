<tr>
	<td height="35" width="100" align="right">域名:&nbsp;</td>
	<td>
		<input type="text" name="vodupload[domain]" value="<?php echo $vodupload['domain'];?>" size="30" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;目录:&nbsp;<input type="text" name="vodupload[dir]" id="vodupload_dir" value="<?php echo $vodupload['dir'];?>" size="60" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">接口目录:&nbsp;</td>
	<td>
		<input type="text" id="vodupload_apidir"  name="vodupload[apidir]" value="<?php echo $vodupload['apidir'] ? $vodupload['apidir'] : 'mediaserver/';?>" size="20" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">上传目录:&nbsp;</td>
	<td>
		<input type="text" name="vodupload[uploaddir]" id="vodupload_uploaddir" value="<?php echo $vodupload['uploaddir'];?>" size="80" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
		<input type="text" name="vodupload[uploaddomain]" value="<?php echo $vodupload['uploaddomain'];?>" size="80" />
	</td>
</tr>
<tr>
	<td height="35" width="100" align="right">转码后目录:&nbsp;</td>
	<td>
		<input type="text" name="vodupload[targetdir]" id="vodupload_targetdir" value="<?php echo $vodupload['targetdir'];?>" size="80" onkeyup="ls(this.id, this.value)" onfocus="ls(this.id, this.value)" />
	</td>
</tr>