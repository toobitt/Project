{if $formdata['backupInfo']}
	{code}
		$i = 0;
	{/code}	
	{foreach $formdata['backupInfo'] AS $k => $v}
	{if $i < 4}
<!--	
	<li onclick="hg_getBackupFlash({$v['id']},'{$v['beibo_file_url']}');">
		<div class="streamList">
			<img class="backupImg" src="{$v['img']}" />
			<span class="backupTitle">{$v['title']}</span>
		</div>
		<input id="backupFlashBox_{$v['id']}_1" type="hidden" value="{$formdata['channel_id']},{$v['id']},file" />
	</li>	
	-->
	<script type="text/javascript">
		$(function(){
			setSwfPlay("backupInfo_"+"{$v['id']}", "{$v['beibo_file_url']}", '190', '150', '1', 'backupInfo_'+'{$v["id"]}');
		});
	</script>
	<li>
		<div class="streamList">
			<div id="backupInfo_{$v['id']}"></div>
			<span class="streamName">{$v['title']}</span>
		</div>
		<input id="backupInfo_{$v['id']}" type="hidden" value="{$formdata['channel_id']},{$v['id']},file" />
	</li>
	{/if}
	{code}
		$i++;
	{/code}
	{/foreach}
{/if}