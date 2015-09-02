{if $formdata['streamInfo']}
	{code}
		$i = 0;
	{/code}	
	{foreach $formdata['streamInfo'] AS $k => $v}
	{if $i < 4}
	<script type="text/javascript">
		$(function(){
			setSwfPlay("streamInfo_"+"{$v['id']}", "{$v['out_url'][0]}", '190', '150', '1', 'streamInfo_'+'{$v["id"]}');
		});
	</script>
	<li>
		<div class="streamList">
			<div id="streamInfo_{$v['id']}"></div>
			<span class="streamName">{$v['s_name']}</span>
		</div>
		<input id="streamInfo_{$v['id']}" type="hidden" value="{$formdata['channel_id']},{$v['id']},stream" />
	</li>
	{/if}
	{code}
		$i++;
	{/code}
	{/foreach}
{/if}