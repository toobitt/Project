<?php 
/* $Id: server_config_form.php 32797 2014-07-15 02:33:47Z kangxiaoqiang $ */
//var_dump($formdata);
//echo '<script>alert("'.$formdata['output_dir'].'")</script>';
?>
{template:head}
{css:ad_style}
{css:server_config_form}
{js:live/server_config_form}
<script type="text/javascript">
	//2013.08.01 scala
	$(function(){
		hg_server_type();
	});
	//2013.08.01 scala end
	function hg_is_live_record(obj, id)
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
	
	function hg_server_type()
	{
	
		var type = $('#type').val() ? $('#type').val() : 'nginx';
		if (type == 'tvie')
		{
			var input_port  = "{$_configs['tvie']['tvie_server']['api_port']}";
			var output_port = "{$_configs['tvie']['tvie_server']['server_port']}";
			var input_port_text  = 'API端口：';
			var output_port_text = '服务器端口：';
			$('#super_token').css('display','inline-block').find('input[name="super_token"]').removeAttr('disabled');
		}
		else if (type == 'wowza')
		{
			var input_port_text  = 'RTMP端口：';
			var output_port_text = '输出端口：';
			var input_port  = "{$_configs['wowza']['live_server']['input_port']}";
			var output_port = "{$_configs['wowza']['live_server']['output_port']}";
			$('#super_token').hide().find('input[name="super_token"]').attr('disabled', 'disabled');
		}
		//scala 2013.08.01 
		else if (type == 'nginx')
		{
			var input_port_text  = 'RTMP端口：';
			var output_port_text = '输出端口：';
			var input_port  = "<?php echo $formdata['input_port'];?>";
			var output_port = "<?php echo $formdata['output_port'];?>";
			
			$('#super_token').hide().find('input[name="super_token"]').attr('disabled', 'disabled');
			$('#nginx_dir').show();
			$('input[name="nginx_dir"]').val('<?php echo $formdata['output_dir'];?>');
			
		}
		
		//scala 2013.08.01 end 
		$('input[name="input_port"]').val(input_port);
		$('input[name="output_port"]').val(output_port);
		$('input[name="live_input_port"]').val(input_port);
		$('input[name="live_output_port"]').val(output_port);
		$('input[name="record_input_port"]').val(input_port);
		$('input[name="record_output_port"]').val(output_port);
		$('.input_port').html(input_port_text);
		$('.output_port').html(output_port_text);
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
					<input type="text" name="name" required value="{if $_INPUT['copy']}复制{/if}{$name}" />
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
			{if !empty($_configs['server_type'])}
			<li class="i">
				<div class="form_ul_div clear">
						<span class="title">直播应用名：</span>
						<input type="text" style="width:73px;"  name="nginx_dir" value="{$output_dir}" />
						<font>注：通常应用名称为live</font>
				<!-- 	<span class="title" style="width: 72px;margin-left: -2px;">服务器类型：</span>
					{code}
						$server_source = array(
							'class' => 'down_list i',
							'show' => 'item_shows_',
							'width' => 100,/*列表宽度*/
							'state' => 0, /*0--正常数据选择列表，1--日期选择*/
							'is_sub'=>1,
							'onclick' => 'hg_server_type()',
						);
						
						$default_type = 'nginx';
						$server_type = $_configs['server_type'];
					
						$type = $type ? $type : $default_type;
						
					{/code}
				{if !$id}
					{template:form/search_source,type,$type,$server_type,$server_source}
				{else}
					<div class="down_list i" style="width: 100px;">
						<span class="input_left"></span>
						<span class="input_right"></span>
						<span class="input_middle">
							<a><em></em><label class="overflow">{$server_type[$type]}</label></a>
						</span>
					</div>
					<input type="hidden" name="type" value="{$type}" id="type" />
				{/if}-->
				<!--<div id="super_token" style="display: {if $type == 'wowza'}none;{else}inline-block;{/if}margin-left: 10px;">	
						<span>SUPER_TOKEN：</span>
						<input type="text" style="width:73px;"  name="super_token" value="{$super_token}" />
						<font>说明：访问TVIE的秘钥</font>
					</div>
					<!---scala 20130731
					<div id="nginx_dir" style="display: {if $type != 'nginx'}none;{else}inline-block;{/if}margin-left: 10px;">	
						<span class="title">直播应用名（application）：</span>
						<input type="text" style="width:73px;"  name="nginx_dir" value="{$output_dir}" />
						<font>注：通常应用名称为live</font>
					</div>
					<!---scala 20130731 end-->
				</div>
			</li>
			{/if}
			<!--
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">主控配置：</span>
					<span>主机：</span>
					<input type="text" style="width:250px;" name="host" value="{$host}" placeholder="{$_configs['wowza']['core_input_server']['host']}" />
					<span class="input_port">{if $type == 'tvie'}API端口{else}RTMP端口{/if}：</span>
					<input type="text" style="width:80px;"  name="input_port" value="{if !$input_port}{$_configs['wowza']['live_server']['input_port']}{else}{$input_port}{/if}" />
					<span class="output_port">{if $type == 'tvie'}服务器端口{else}输出端口{/if}：</span>
					<input type="text" style="width:80px;"  name="output_port" value="{if !$output_port}{$_configs['wowza']['live_server']['output_port']}{else}{$output_port}{/if}" />
				{if $type == 'nginx'}
					<div class="form_ul_div clear">
					<span class="title">TS域名：</span>
					<input type="text" style="width:250px;" name="ts_host" value="{$ts_host}" />
					</div>
					<div class="form_ul_div clear">
					<span class="title">m3u8域名：</span>
					<input type="text" style="width:250px;" name="out_host" value="{$out_host}" />
					</div>
					<div class="form_ul_div clear">
					<span class="title">nginx接口：</span>
					<input type="text" style="width:250px;" name="input_dir" value="{$input_dir}" /><font>注：如果非80端口需要配置成:port/control/</font>
					</div>
				{/if}
				</div>
				<!--
				<div class="form_ul_div">	
					<span class="title">域名池：</span>
					<div style="margin-left: 84px;" id="core_append_box">
					{if !empty($output_append_host)}
						{foreach $output_append_host AS $k => $v}
						<div style="margin-bottom: 10px;">
							<span style="cursor: pointer;padding: 4px;margin-left: 20px;" title="点击添加" onclick="hg_append_host(this,true,'core_append_box');">+</span>
							<input type="text" style="width:250px;" name="output_append_host[]" value="{$v}" />
							<span style="cursor: pointer;padding: 4px;{if $k == 0 && count($output_append_host) < 2}display: none;{/if}" title="点击删除" onclick="hg_append_host(this,false,'core_append_box');" name="delete[]">-</span>
						</div>
						{/foreach}
					{else}
						<div style="margin-bottom: 10px;">
							<span style="cursor: pointer;padding: 4px;margin-left: 20px;" title="点击添加" onclick="hg_append_host(this,true,'core_append_box');">+</span>
							<input type="text" style="width:250px;" name="output_append_host[]" value="" placeholder="{if $_configs['wowza']['core_input_server']['host']}1{$_configs['wowza']['core_input_server']['host']}{/if}" />
							<span style="cursor: pointer;padding: 4px;display: none;" title="点击删除" onclick="hg_append_host(this,false,'core_append_box');" name="delete[]">-</span>
						</div>
					{/if}
					</div>
					<span style="margin-left: 92px;width: 626px;display: inline-block;">说明：所有域名请都指向主控配置主机，这些域名供监看电视墙使用，浏览器支持flash播放器同一页面、同一域名下有个数限制，因此需此配置</span>
				</div>
			-->
			<!--
			</li>
			-->
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">主控配置：</span>
					<div class="host-box">
						<span>主机：</span>
						<input type="text" class="getpath get_host" style="width:102px;" name="host" required value="{$host}" placeholder="{$_configs['wowza']['core_input_server']['host']}" />
						<span class="input_port">RTMP端口：</span>
						<input type="text" style="width:37px;"  name="input_port" value="{if !$input_port}{$_configs['wowza']['live_server']['input_port']}{else}{$input_port}{/if}" />
						<span>nginx接口：</span>
						<!--  <input type="text" style="width:70px;"  name="input_dir" value="{if !$output_port}{$_configs['wowza']['live_server']['output_port']}{else}{$output_port}{/if}" /> -->
						<input type="text" class="getpath get_nginx" style="width:100px;"  name="input_dir" value="{$input_dir}" />
						<span>TS目录：</span>
						<div class="path-box">
							<!-- <input type="text" style="width:65px;"  name="base_hls_path" value="{$hls_path['base_hls_path']}" />-->
							<span class="select-path">{$hls_path['base_hls_path']}</span>
							<input type="hidden" name="base_hls_path" value="{$hls_path['base_hls_path']}" />
							<ul class="path-list">
							</ul>
						</div>
						<input type="text" style="width:85px;"  name="hls_path" value="{$hls_path['hls_path']}" />
						<input type="radio" style="margin-left:5px;" name="record_default" value="{$host}" {if !$id || $is_record}checked{/if} /><span> 收录主机</span>
					</div>
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">备播配置：</span>
					<div class="host-list">
					{if $formdata['backup']}
					{foreach $formdata['backup'] as $k => $v}
					
						<div class="host-box b_host-box">
							<span>主机：</span>
							<input type="text" class="getpath get_host" style="width:102px;" name="b_host[]" value="{$v['host']}" placeholder="{$_configs['wowza']['core_input_server']['host']}" />
							<span class="input_port">RTMP端口：</span>
							<input type="text" style="width:37px;"  name="b_input_port[]" value="{$v['input_port']}" />
							<span>nginx接口：</span>
							<input type="text" class="getpath get_nginx" style="width:100px;"  name="b_input_dir[]" value="{$v['input_dir']}" />
							<span>TS目录：</span>
							<div class="path-box">
								<!--  <input type="text" style="width:65px;" name="b_base_hls_path[]" value="{$v['hls_path']['base_hls_path']}" /> -->
								<span class="select-path">{$v['hls_path']['base_hls_path']}</span>
								<input type="hidden" name="b_base_hls_path[]" value="{$v['hls_path']['base_hls_path']}" />
								<ul class="path-list">
									
								</ul>
							</div>
							<input type="text" style="width:85px;"  name="b_hls_path[]" value="{$v['hls_path']['hls_path']}" />
							<input type="radio" style="margin-left:5px;" name="record_default" value="{$v['host']}" {if $v['is_record']}checked{/if} /><span> 收录主机</span>
							<!-- 
							<div class="btn-group">
								<span class="btn add_btn">+</span>
								<span class="btn del_btn">_</span>
							</div>
							 -->
						</div>
					{/foreach}
					</div>
					{else}
					<div class="host-list">
						<div class="host-box b_host-box">
							<span>主机：</span>
							<input type="text" class="getpath get_host" style="width:102px;" name="b_host[]" value="" placeholder="{$_configs['wowza']['core_input_server']['host']}" />
							<span class="input_port">RTMP端口：</span>
							<input type="text" style="width:37px;"  name="b_input_port[]" value="{if !$input_port}{$_configs['wowza']['live_server']['input_port']}{else}{$input_port}{/if}" />
							<span>nginx接口：</span>
							<input type="text" class="getpath get_nginx" style="width:100px;"  name="b_input_dir[]" value="{if !$output_port}{$_configs['wowza']['live_server']['output_port']}{else}{$output_port}{/if}" />
							<span>TS目录：</span>
							<div class="path-box">
								<span class="select-path">根目录</span>
								<input type="hidden" name="b_base_hls_path[]" value="" />
								<ul class="path-list">
									
								</ul>
							</div>
							<input type="text" style="width:85px;"  name="b_hls_path[]" value="" />
							<input type="radio" style="margin-left:5px;" name="record_default" value="" />
							<div class="btn-group">
								<span class="btn add_btn">+</span>
								<span class="btn del_btn">_</span>
							</div>
						</div>
					</div>
					{/if}
			</li>
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">m3u8域名：</span>
					<input type="text" style="width:140px;" name="out_host" value="{$out_host}" />
					<span>输出端口：</span>
					<input type="text" style="width:37px;"  name="output_port" value="{if !$output_port}{$_configs['wowza']['live_server']['output_port']}{else}{$output_port}{/if}" />
			</li>
			{if $type != 'nginx'}
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">直播配置：</span>
					<label>
						<input type="checkbox" name="is_live" value=1 class="n-h" onclick="hg_is_live_record(this, 'live');"
							{if $live_host}
								checked="checked"
							{/if}
						/>
						<span class="s">开启</span>
					</label>
				</div>
				<div class="form_ul_div clear" id="live" {if !$live_host} style="display: none;" {/if}>
					<span class="title"></span>
					<span>主机：</span>
					<input type="text" style="width:250px;" name="live_host" value="{$live_host}" />
					<span class="input_port">{if $type == 'tvie'}API端口{else}RTMP端口{/if}：</span>
					<input type="text" style="width:80px;"  name="live_input_port" value="{if !$live_input_port}{if $type == 'tvie'}{$_configs['tvie']['tvie_server']['api_port']}{else}{$_configs['wowza']['live_input_port']}{/if}{else}{$live_input_port}{/if}" />
					<span class="output_port">{if $type == 'tvie'}服务器端口{else}输出端口{/if}：</span>
					<input type="text" style="width:80px;"  name="live_output_port" value="{if !$live_output_port}{if $type == 'tvie'}{$_configs['tvie']['tvie_server']['server_port']}{else}{$_configs['wowza']['live_output_port']}{/if}{else}{$live_output_port}{/if}" />
				</div>
			</li>
			<li class="i">
				<div class="form_ul_div clear">	
					<span class="title">录制配置：</span>
					<label>
						<input type="checkbox" name="is_record" value=1 class="n-h" onclick="hg_is_live_record(this, 'record');"
							{if $record_host}
								checked="checked"
							{/if}
						/>
						<span class="s">开启</span>
					</label>
				</div>
				<div class="form_ul_div clear" id="record" {if !$record_host} style="display: none;" {/if}>
					<span class="title"></span>
					<span>主机：</span>
					<input type="text" style="width:250px;" name="record_host" value="{$record_host}" />
					<span class="input_port">{if $type == 'tvie'}API端口{else}RTMP端口{/if}：</span>
					<input type="text" style="width:80px;"  name="record_input_port" value="{if !$record_input_port}{if $type == 'tvie'}{$_configs['tvie']['tvie_server']['api_port']}{else}{$_configs['wowza']['record_input_port']}{/if}{else}{$record_input_port}{/if}" />
					<span class="output_port">{if $type == 'tvie'}服务器端口{else}输出端口{/if}：</span>
					<input type="text" style="width:80px;"  name="record_output_port" value="{if !$record_output_port}{if $type == 'tvie'}{$_configs['tvie']['tvie_server']['server_port']}{else}{$_configs['wowza']['record_output_port']}{/if}{else}{$record_output_port}{/if}" />
				</div>
			</li>
			{/if}
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
		<span style="cursor: pointer;padding: 4px;margin-left: 21px;" title="点击添加" name="add[]" onclick="hg_append_host(this,true,'core_append_box');">+</span>
		<input type="text" style="width:250px;" name="output_append_host[]" value="" placeholder="{if $_configs['wowza']['core_input_server']['host']}1{$_configs['wowza']['core_input_server']['host']}{/if}" />
		<span style="cursor: pointer;padding: 4px;" title="点击删除" name="delete[]" onclick="hg_append_host(this,false,'core_append_box');">-</span>
	</div>
</div>
<div id="live_append_host_dom" style="display:none;">
	<div style="margin-bottom: 10px;">
		<span style="cursor: pointer;padding: 4px;margin-left: 21px;" title="点击添加" name="add[]" onclick="hg_append_host(this,true,'live_append_box');">+</span>
		<input type="text" style="width:250px;" name="live_append_host[]" value="" />
		<span style="cursor: pointer;padding: 4px;" title="点击删除" name="delete[]" onclick="hg_append_host(this,false,'live_append_box');">-</span>
	</div>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
{template:foot}
<script type="text/x-jquery-tmpl" id="host-tpl">
	<div class="host-box b_host-box">
		<span>主机：</span>
		<input type="text" class="getpath get_host" style="width:102px;" name="b_host[]" value=""  />
		<span class="input_port">RTMP端口：</span>
		<input type="text" style="width:37px;"  name="b_input_port[]" value="" />
		<span class="output_port">nginx接口：</span>
		<input type="text" class="getpath get_nginx" style="width:100px;"  name="b_input_dir[]" value="" />
		<span>TS目录：</span>
		<div class="path-box">
			<span class="select-path">根目录</span>
			<input type="hidden" name="b_base_hls_path[]" value="" />
			<ul class="path-list">
			  
			</ul>
		</div>
		<input type="text" style="width:85px;"  name="b_hls_path[]" value="" />
		<input type="radio" style="margin-left:5px;" name="record_default" value="" />
		<div class="btn-group">
			<span class="btn add_btn">+</span>
			<span class="btn del_btn">_</span>
		</div>
	</div>
</script>
<script type="text/x-jquery-tmpl" id="path-tpl">
	{{each option}}
		<li>{{= $value }}</li>
	{{/each}}
</script>