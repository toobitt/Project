{if $formdata}
	{code}
		$ii = 1;
	{/code}
	{foreach $formdata AS $k => $v}
	<li onclick="hg_postBackupVideoFileName(this, {$v['id']});">
		<img src="{$v['img']}" onmouseover="hg_backupTitleShow(this, 'show');" onmouseout="hg_backupTitleShow(this, 'hide');" sid="{$ii}" />
		<div id="backupIdInfo_{$v['id']}" class="backupTitle">
			<span>名称：{$v['title']}</span>
			<span>时长：{$v['toff']}</span>
		</div>
		<input id="videFileName_{$v['id']}" type="hidden" value="{$v['fileid']}" />
		<input id="backupTitle_{$v['id']}" type="hidden" value="{$v['title']}" />
		<input id="backupId_{$v['id']}" type="hidden" value="{$v['id']}" />
	</li>
	{code}
		$ii ++;
	{/code}
	{/foreach}	
{/if}
