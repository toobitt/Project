<div id="backupList" class="postBackupInfo">
{if $formdata['info']}
	<ul class="postBackupInfo_ul" id="postBackupInfo_ul">
		{code}
			$ii = 1;
		{/code}
		{foreach $formdata['info'] AS $k => $v}
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
	</ul>
	<span class="count">共{$formdata['count']}页/计{$formdata['total']}条</span>
	<div id="pageBox" style="width:400px;margin-left:116px;"></div>
	<input id="backupCount" type="hidden" value="{$formdata['count']}" />
{else}
	<div style="text-align: center;color:red;margin-top: 20px;">暂无记录</div>
{/if}
</div>
