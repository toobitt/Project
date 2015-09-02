<?php 
/* $Id: server_config_form.php 16877 2013-01-30 07:45:34Z lijiaying $ */
?>
{template:head}
{css:ad_style}
<script type="text/javascript">
	function hg_is_output(obj, id)
	{
		if ($(obj).attr('checked') == 'checked')
		{
			$('#' + id).show().find('input').removeAttr('disabled');
		}
		else
		{
			$('#' + id).hide().find('input').attr('disabled', 'disabled');
		}
		hg_resize_nodeFrame();
	}
	function hg_append_host(obj, type, id)
	{
		if (id == 'core_append_box')
		{
			var html = $('#core_append_host_dom').html();
		}
		else
		{
			var html = $('#live_append_host_dom').html();
		}
		
		if (type)
		{
			$('#' + id).append(html);
		}
		else
		{
			$(obj).parent().remove();
		}
		
		if ($('#' + id + ' div').length > 1)
		{
			$('#' + id + ' div').first().find('span[name^="delete[]"]').show();
		}
		else if ($('#' + id + ' div').length < 2)
		{
			$('#' + id + ' div').first().find('span[name^="delete[]"]').hide();
		}
		hg_resize_nodeFrame();
	}
</script>
{if $a}
	{code}
/*	hg_pre($formdata);*/
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div class="ad_middle">
	<form name="editform" id="editform" action="./run.php?mid={$_INPUT['mid']}" method="post" enctype='multipart/form-data' class="ad_form h_l">
		<h2>{if $_INPUT['copy']}复制{else}{$optext}{/if}配置</h2>
		<ul class="form_ul">
			<li class="i">
				<div class="form_ul_div">
					<span class="title">名称：</span>
					<input type="text" name="name" value="{if $_INPUT['copy']}复制{/if}{$name}" />
				</div>
				<div class="form_ul_div">	
					<span class="title">信号数目：</span>
					<input type="text" name="counts" value="{if $counts}{$counts}{else}{$_configs['wowza']['counts']}{/if}" />
				</div>
				
				<div class="form_ul_div">	
					<span class="title">描述：</span>
					{template:form/textarea,brief,$brief}
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">主控配置：</span>
					<span>host：</span>
					<input type="text" style="width:250px;" name="core_in_host" value="{$core_in_host}" />
					<span>输入端口：</span>
					<input type="text" style="width:80px;"  name="core_in_port" value="{if !$core_in_port}{$_configs['wowza']['in_port']}{else}{$core_in_port}{/if}" />
					<span>输出端口：</span>
					<input type="text" style="width:80px;"  name="core_out_port" value="{if !$core_out_port}{$_configs['wowza']['out_port']}{else}{$core_out_port}{/if}" />
				</div>
				<div class="form_ul_div">	
					<span class="title">多个host：</span>
					<div style="margin-left: 84px;" id="core_append_box">
					{if !empty($dvr_append_host)}
						{foreach $dvr_append_host AS $k => $v}
						<div style="margin-bottom: 10px;">
							<span style="cursor: pointer;padding: 4px;" title="点击添加" onclick="hg_append_host(this,true,'core_append_box');">++</span>
							<input type="text" style="width:250px;" name="dvr_append_host[]" value="{$v}" />
							<span style="cursor: pointer;padding: 4px;{if $k == 0 && count($dvr_append_host) < 2}display: none;{/if}" title="点击删除" onclick="hg_append_host(this,false,'core_append_box');" name="delete[]">--</span>
						</div>
						{/foreach}
					{else}
						<div style="margin-bottom: 10px;">
							<span style="cursor: pointer;padding: 4px;" title="点击添加" onclick="hg_append_host(this,true,'core_append_box');">++</span>
							<input type="text" style="width:250px;" name="dvr_append_host[]" value="" />
							<span style="cursor: pointer;padding: 4px;display: none;" title="点击删除" onclick="hg_append_host(this,false,'core_append_box');" name="delete[]">--</span>
						</div>
					{/if}
					</div>
				</div>
				<div class="form_ul_div clear">	
					<span class="title"></span>
					<label>
					<input type="checkbox" onclick="hg_is_output(this, 'dvr_output');" name="is_dvr_output" value="1" class="n-h" {if $is_dvr_output}checked="checked"{/if} /><span>允许主控分离</span>
					</label>
				</div>
				<div class="form_ul_div" id="dvr_output" {if !$is_dvr_output}style="display:none;"{/if}>	
					<span class="title">时移配置：</span>
					<span>host：</span>
					<input type="text" style="width:250px;" name="dvr_in_host" value="{$dvr_in_host}" />
					<span>输入端口：</span>
					<input type="text" style="width:80px;"  name="dvr_in_port" value="{if !$dvr_in_port}{$_configs['wowza']['in_port']}{else}{$dvr_in_port}{/if}" />
					<span>输出端口：</span>
					<input type="text" style="width:80px;"  name="dvr_out_port" value="{if !$dvr_out_port}{$_configs['wowza']['out_port']}{else}{$dvr_out_port}{/if}" />
				</div>
				
			</li>
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title"></span>
					<label>
					<input type="checkbox" onclick="hg_is_output(this, 'live_output');" name="is_live_output" value="1" class="n-h" {if $is_live_output}checked="checked"{/if} /><span>允许开启直播</span>
					</label>
				</div>
				<div class="form_ul_div" id="live_output" {if !$is_live_output}style="display:none;"{/if}>	
					<span class="title">直播配置：</span>
					<span>host：</span>
					<input type="text" style="width:250px;" name="live_in_host" value="{$live_in_host}" {if !$is_live_output}disabled="disabled"{/if} />
					<span>输入端口：</span>
					<input type="text" style="width:80px;"  name="live_in_port" value="{if !$live_in_port}{$_configs['wowza']['in_port']}{else}{$live_in_port}{/if}" {if !$is_live_output}disabled="disabled"{/if} />
					<span>输出端口：</span>
					<input type="text" style="width:80px;"  name="live_out_port" value="{if !$live_out_port}{$_configs['wowza']['out_port']}{else}{$live_out_port}{/if}" {if !$is_live_output}disabled="disabled"{/if} />
					<div class="form_ul_div">	
						<span class="title">多个host：</span>
					{if !empty($live_append_host)}
						{foreach $live_append_host AS $k => $v}
						<div style="margin-left: 84px;" id="live_append_box">
							<div style="margin-bottom: 10px;">
								<span style="cursor: pointer;padding: 4px;" title="点击添加" onclick="hg_append_host(this,true,'live_append_box');" name="add[]">++</span>
								<input type="text" style="width:250px;" name="live_append_host[]" value="{$v}" {if !$is_live_output}disabled="disabled"{/if} />
								<span style="cursor: pointer;padding: 4px;{if $k == 0 && count($live_append_host) < 2}display: none;{/if}" title="点击删除" onclick="hg_append_host(this,false,'live_append_box');" name="delete[]">--</span>
							</div>
						</div>
						{/foreach}
					{else}	
						<div style="margin-left: 84px;" id="live_append_box">
							<div style="margin-bottom: 10px;">
								<span style="cursor: pointer;padding: 4px;" title="点击添加" onclick="hg_append_host(this,true,'live_append_box');" name="add[]">++</span>
								<input type="text" style="width:250px;" name="live_append_host[]" value="" {if !$is_live_output}disabled="disabled"{/if} />
								<span style="cursor: pointer;padding: 4px;display: none;" title="点击删除" onclick="hg_append_host(this,false,'live_append_box');" name="delete[]">--</span>
							</div>
						</div>
					{/if}
					</div>
				</div>
				
			</li>
			<li class="i">
				<div class="form_ul_div">	
					<span class="title">录制配置：</span>
					<span>host：</span>
					<input type="text" style="width:250px;" name="record_host" value="{$record_host}" />
					<span>端口：</span>
					<input type="text" style="width:80px;"  name="record_port" value="{if !$record_port}{$_configs['wowza']['record_port']}{else}{$record_port}{/if}" />
				</div>
				<div class="form_ul_div" style="margin-left: -24px;">	
					<span class="title"></span>
					<span>输出host：</span>
					<input type="text" style="width:250px;" name="record_out_host" value="{$record_out_host}" />
				</div>
			</li>
		</ul>
	</br>
	<input type="submit" name="sub" value="{if $_INPUT['copy']}复制{else}{$optext}{/if}" id="sub" class="button_6_14"/>
	{if $_INPUT['copy']}
	<input type="hidden" name="a" value="create" id="action" />
	{else}	
	<input type="hidden" name="a" value="{$action}" id="action" />
	{/if}
	<input type="hidden" name="{$primary_key}" value="{$id}"/>
	<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
	<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
	</form>
</div>
<div id="core_append_host_dom" style="display:none;">
	<div style="margin-bottom: 10px;">
		<span style="cursor: pointer;padding: 4px;" title="点击添加" name="add[]" onclick="hg_append_host(this,true,'core_append_box');">++</span>
		<input type="text" style="width:250px;" name="dvr_append_host[]" value="" />
		<span style="cursor: pointer;padding: 4px;" title="点击删除" name="delete[]" onclick="hg_append_host(this,false,'core_append_box');">--</span>
	</div>
</div>
<div id="live_append_host_dom" style="display:none;">
	<div style="margin-bottom: 10px;">
		<span style="cursor: pointer;padding: 4px;" title="点击添加" name="add[]" onclick="hg_append_host(this,true,'live_append_box');">++</span>
		<input type="text" style="width:250px;" name="live_append_host[]" value="" />
		<span style="cursor: pointer;padding: 4px;" title="点击删除" name="delete[]" onclick="hg_append_host(this,false,'live_append_box');">--</span>
	</div>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}