<?php
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:setting}
<script type="text/javascript">
function hg_switch_tab(id)
{
	var tab = [];
	tab.push('base');
	tab.push('db');
	tab.push('cron');
    tab.push('watermark');
	if (id == 'cron')
	{
		$('#doset').hide();
	}
	else
	{
		$('#doset').show();
	}
	for (var i=0; i < 4; i++)
	{
		if (tab[i] == id)
		{
			$('#sg_' + id).show();
			$('.setting_title_label').css({'background-color':'#f9f9f9f'});
			$('#setting_title'+id).css({'background-color':'#eee'});
		}
		else
		{
			$('#sg_' + tab[i]).hide();
		}
	}
}

$( function(){
	$('#settingform').on('submit', function(){
		var form = $(this),
			url = form.attr('action');
		url = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'ajax=1';
		$(this).ajaxSubmit( {
			url : url,
			dataType : 'json',
			success : function( data ){
				if( data['msg'] ){
					form.find('input[type="submit"]').myTip( {
						string : data['msg']
					} );
				}
				data['callback'] && ( eval( data['callback'] ) );
			}
		} );
		return false;
	})
} )

</script>
{if $setting_groups}
<ul class="setting_ul clearfix">
{foreach $setting_groups AS $k => $v}
<li onclick="hg_switch_tab('{$k}');" class="setting_title_label" id="setting_title{$k}"><a><h2 class="setting_h2">{$v}</h2></a></li>
{/foreach}
</ul>
{/if}
<form action="settings.php" method="post" enctype="multipart/form-data" class="setting_form ad_form h_l"  name="settingform" id="settingform">
{if $setting_groups}
{foreach $setting_groups AS $k => $v}
	{if $k == 'base'}
	<div id="sg_{$k}">
		{template:setting/base}
	</div>
	{/if}
	{if $k == 'db'}
	<div id="sg_{$k}" style="display:none;">
		{template:setting/db}
	</div>
	{/if}
	{if $k == 'cron'}
	<div id="sg_{$k}" style="display:none;">
		{template:setting/cron}
	</div>
	{/if}
    {if $k == 'watermark'}
    <div id="sg_{$k}" style="display:none;">
        {template:setting/watermark}
    </div>
    {/if}
{/foreach}
{/if}
    <input type="hidden" name="a" value="set" />
    <input type="hidden" name="app_uniqueid" value="{$app_uniqueid}" />
    <input class="setting_button" id="doset" type="submit" name="s" value="{if !$settings['define']['INITED_APP'] || $settings['define']['INITED_APP'] == 'false'}开始使用{else}修改配置{/if}" />
</form>
{template:foot}