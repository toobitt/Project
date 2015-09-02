{template:head}
{css:mms_control_list}
{css:tab_btn}
<h2 class="title_bg">直播控制-电视墙<span class="e">自动更新时间：2秒</span>
{template:menu/btn_menu}
</h2>
<ul class="pic_list">
{if $mms_control_list}
	{foreach $mms_control_list as $k => $v}
		<li>
			<div class="img"><a href="{if $v['is_live']}run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$v['id']}{else}###" style="cursor:default;{/if}" title="{$v['mms_prview_img']}"><span id="img_{$v['id']}"><img src="{if $v['_snap']}{$v['_snap']}{else}{$RESOURCE_URL}nopic2.png{/if}"></span></a></div>
			<p><span class="overflow">{$v['name']}</span><img src="{$v['logo_url']}" width="52" height="20" /></p>
			<div class="clr"></div>
			<div class="zb overflow">{if $v['is_live']}<a class="qb" href="run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$v['id']}">切播</a>{else}{/if}<span id="curr_{$v['id']}">{$v['current']}</span></div>
		</li>
	{/foreach}
{/if}
</ul>
<script type="text/javascript">
	function get_mms_list()
	{
		var channel_ids = '{$mms_control_list[0]["all_channel_ids"]}';
		var url = 'run.php?mid={$_INPUT["mid"]}&a=update_mms_list&channel_ids='+channel_ids;
		hg_request_to(url, '', 'get', 'hg_build_mms_list', 1);
	}
	function hg_build_mms_list (data)
	{
		setTimeout("get_mms_list();", 2000);
		if(!data)
		{
			return;
		}
		for(var n in data[0])
		{
			$('#curr_'+n).text(data[0][n]['pro']);
			var img_src = data[0][n]['img'];
		
			if(!img_src)
			{
				img_src = RESOURCE_URL + 'nopic2.png';
			}
			$('#img_'+n).html('<img src="'+img_src+'">');
		}
	}
	setTimeout("get_mms_list();", 2000);
</script>
{template:foot}
