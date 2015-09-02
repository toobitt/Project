<ul class="form_ul">
<li class="i">
<span class="title overflow"><font color="red" size="5">前置数据配置：</font></span>
</li>
<li class="i">
	<div class="form_ul_div">
	<span  class="title">名称：</span><input type="text" value='{$conf["title"]}' name='old_title[{$ckey}]'>
	</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">主表字段：</span><input type="text" name="old_set_field[{$ckey}]" value="{$conf['set_field']}" style="width:175px;">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">前置字段：</span><input type="text" name="old_rely_field[{$ckey}]" value="{$conf['rely_field']}" style="width:175px;">
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">sql：</span><textarea name="old_sql[{$ckey}]" style="width:300px;height:50px;">{$conf['sql']}</textarea>
<font class="important" style="color:red">SQL结尾请勿添加分号;</font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">sql主键：</span><input type="text" name="old_primarykey[{$ckey}]" value="{$conf['primarykey']}" style="width:175px;">
<font class="important" style="color:red">格式由sql语句决定</font>
</div>
</li>
<li class="i">
<div class="form_ul_div"><span class="title">数据处理：</span><textarea name="old_datadeal[{$ckey}]" style="width:300px;height:50px;">{$conf['datadeal']}</textarea>
</div>
</li>
<li class="i">
		<div class="form_ul_div clear">
		{foreach $conf['paras'] as $key => $val}
			<div class="form_ul_div clear" id="">	
				<span class="title">映射：</span>
					<input type="text" name="old_source_fields[{$ckey}][]" style="width:90px;" class="title" value="{$key}">
				<span>＝></span>
					<input type="text" name="old_detin_fields[{$ckey}][]" style="width:90px;" class="title" value="{$val}">
				 <span class='option_del_box'>
				 	<span class='option_del' title='删除' data-save="1" onclick='hg_delDom(this);' style='display: inline; '></span>
				 </span>
			</div>
		{/foreach}
			<div class="param-list-area"></div>
			<div class="form_ul_div clear">
				<span id="pa_1" type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;
					background-color: #5B5B5B;color: white;border-radius: 2px;" class="add-param-btn" _type="old" _id="{$ckey}">添加参数</span>
			</div>
		</div>
	</li>
<li class="i">
<div class="form_ul_div"><span class="title overflow">接收数据：</span><input type="text" name="old_apiurl[{$ckey}]" value="{$conf['apiurl']}"  {if $conf['flag']}readonly="readonly" disabled="disabled"{/if} style="width:275px;"><span style="color:red">*</span><font class="important">客户端标识，英文和数字，创建后无法修改</font>
</div>
<span style="float:right;color:red;font-size:20px;cursor:pointer;" onclick="hg_deleteConf(this);">删除配置</span>
</li>
<input type="hidden" value = "{$conf['id']}" name = "old_config_id[{$ckey}]"/>
</ul>