{code}
	$type = $formdata['type'];
	$info = $formdata['info'];
{/code}
{if $type == 1}
	<li class="f_l overflow"  id="live_{$v['id']}" value="{$v['id']}" onclick="hg_channel_live(this,1,'','');">
		<a id="num_{$v['id']}" class="overflow" >{$v['name']}</span></a>
		<span>{if $v['stream_state']}已启动{else}未启动{/if}</span>
	</li>
{else if $type == 4}
	{foreach $info AS $k => $v}
	<li class="f_l overflow"  id="live_{$v['id']}" value="{$v['id']}" onclick="hg_channel_live(this,4,'','');">
		<a id="num_{$v['id']}" class="overflow" >{$v['ch_name']}</span></a>
		<span>{if $v['s_status']}已启动{else}未启动{/if}</span>
	</li>
	{/foreach}
{else if $type == 2}
	{code}
		$ii = 1;
	{/code}
	{foreach $info AS $k => $v}
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