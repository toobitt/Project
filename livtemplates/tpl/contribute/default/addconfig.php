	<ul class="form_ul settingField">
		<li class="i">
			<div class="form_ul_div">
				<span class="title">配置名称</span>
				<input type="text" name="con_title[{$formdata['num']}]" style="width:440px;"/>
				&nbsp;&nbsp;&nbsp;
				是否开启<input type="checkbox" class="needIndex" name="con_open[{$formdata['num']}]" value="1" />
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div">
				<span class="title">url路径</span>
				<div>
					<input type="text" name="con_host[{$formdata['num']}]" class="needIndex" style="width:220px;"/>				
					<input type="text" name="con_dir[{$formdata['num']}]" class="needIndex" style="width:220px;"/>
					<input type="text" name="con_file[{$formdata['num']}]" class="needIndex" style="width:220px;"/>
				</div>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">接口协议: </span>
				{if $formdata['protocol']}
				<select name = "con_protocol[{$formdata['num']}]">
				{foreach $formdata['protocol'] as $key=>$val}
				<option value="{$key}">{$val}</option>	
				{/foreach}
				</select>
				{/if}
				<font class="important"></font>
			</div>
		</li>
		<li class="i">
			<div class="form_ul_div clear">
				<span class="title">请求方式: </span>
				{if $formdata['request_type']}
				<select name = "con_request_type[{$formdata['num']}]">
				{foreach $formdata['request_type'] as $key=>$val}
				<option value="{$key}">{$val}</option>	
				{/foreach}
				</select>
				{/if}
				<font class="important"></font>
			</div>
		</li>
		<li class="i">
			<div class="paramsBox">
			</div>
			<div class="form_ul_div clear">
				<span id="pa_{$formdata['num']}" type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addParam({$formdata['num']});">添加参数</span>
				<span style="float:right;color:red;font-size:20px;cursor:pointer;" onclick="hg_deleteConf(this);">删除配置</span>
			</div>
		</li>
			<input type="hidden" value = "{$formdata['config_id']}" name = "con_config_id[{$formdata['num']}]"/>	
	</ul>
