	<ul class="form_ul settingField">
		<li class="i">
			<div class="form_ul_div">
				<span class="title">配置名称</span>
				<input type="text" name="con_title[{$ckey}]" style="width:440px;" value="{$conf['title']}"/>
				&nbsp;&nbsp;&nbsp;
				是否开启
				<input type="checkbox" class="needIndex" name="con_open[{$ckey}]" value="1" {if $conf['is_open']} checked="checked" {/if}/>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">url路径</span>
				<div>
					<input type="text" name="con_host[{$ckey}]" class="needIndex" style="width:220px;" value="{$conf['host']}"/>				
					<input type="text" name="con_dir[{$ckey}]" class="needIndex" style="width:220px;" value="{$conf['dir']}"/>
					<input type="text" name="con_file[{$ckey}]" class="needIndex" style="width:220px;" value="{$conf['filename']}"/>
				</div>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">接口协议: </span>
				{if $_configs['con_api_protocol']}
				<select name = "con_protocol[{$ckey}]">
				{foreach $_configs['con_api_protocol'] as $kk=>$vv}
				<option value="{$kk}" {if $kk==$conf['protocal']} selected="selected" {/if}>{$vv}</option>	
				{/foreach}
				</select>
				{/if}
				<font class="important"></font>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">请求方式: </span>
				{if $_configs['con_request_type']}
				<select name = "con_request_type[{$ckey}]">
				{foreach $_configs['con_request_type'] as $kkk=>$vvv}
				<option value="{$kkk}" {if $kkk==$conf['request_type'] } selected="selected" {/if} >{$vvv}</option>	
				{/foreach}
				</select>
				{/if}
				<font class="important"></font>
			</div>
		</li>
		<li class="i">
			<div class="paramsBox">
			{if is_array($conf['match_rule']) && !empty($conf['match_rule'])}
				{foreach $conf['match_rule']['name'] as $pkey=>$param}
				{template:unit/addparam}
				{/foreach}
			{/if}
			</div>
			<div class="form_ul_div clear">
				<span id="pa_{$ckey}" type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addParam({$ckey});">添加参数</span>
				<span style="float:right;color:red;font-size:20px;cursor:pointer;" onclick="hg_deleteConf(this);">删除配置</span>
			</div>
		</li>
			<input type="hidden" value = "{$conf['id']}" name = "con_config_id[{$ckey}]"/>	
	</ul>
