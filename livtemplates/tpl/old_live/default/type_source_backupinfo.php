
{if $formdata}
	{code}
		$ii = 1;
	{/code}
	{foreach $formdata AS $k => $v}
	<li onclick="hg_channel_live(this,2,'',{$v['id']});" value="{$v['id']}">
		<input type="hidden" id="_title_{$v['id']}" value="{$v['title']}" />
		<span style="display:none;" id="_toff_{$v['id']}">{$v['toff']}</span>
		<img class="backup_img" src="{$v['img']}" onmouseover="hg_backupTitleShow(this,{$v['id']},'show');" onmouseout="hg_backupTitleShow(this,{$v['id']},'hide');" sid="{$ii}" />
		<input type="hidden" name="toff_s[]" value="{$v['toff']}" />
		<div id="backupIdInfo_{$v['id']}" class="backupinfo">
			<span>名称：{$v['title']}</span>
			<span>时长：{$v['toff']}</span>
		</div>
	</li>
	{code}
		$ii ++;
	{/code}
	{/foreach}	
{/if}