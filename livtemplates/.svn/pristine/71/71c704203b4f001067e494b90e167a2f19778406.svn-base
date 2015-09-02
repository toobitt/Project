	{code}
		$argument = $formdata['datasource_info']['argument'];
		$content_data = $formdata['content_data'];
		$block_content_info = $formdata['block_content_info'];
		$block_line_data = $formdata['block_line_data'];
	{/code}
	<script>
	function browse_submit()
	{
		$("#datasource_content_form").attr("action", "./run.php?mid={$_INPUT['mid']}&a=content_create");
		hg_get_browse('{$formdata['id']}','{$formdata['line']}','1');
	}
	</script>
	<div style="margin-left:730px">
		<a href="javascript:void(0)" onclick="hg_close_brower()"><img src='{$RESOURCE_URL}/close.gif'></a><br>
	</div>
	
	<div style="align:right">
		<a href="javascript:void(0)" onclick="menu_change('basic_info')">创建表单</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" onclick="menu_change('datasource')">数据源</a>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" onclick="menu_change('block_line_set')">行设置</a>
		
		<!--
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="javascript:void(0)" onclick="menu_change('content_style_set')">内容样式设置</a>
		-->
	</div>
	<hr>
	<br>
	<form id="datasource_content_form" name="datasource_content_form" action="./run.php?mid={$_INPUT['mid']}&a=content_create" method="POST">
	<div id="datasource" style="height:370px;display:none;">
		
		参数：
		{if $argument}
			{foreach $argument['argument_name'] as $k=>$v}
				{$v}:<input type=text id='argument_{$argument['ident'][$k]}' name='argument_{$argument['ident'][$k]}' value="{$formdata['default_datasource_argument'][$argument['ident'][$k]]}" style="width:40px;height:10px">
			{/foreach}
		{else}
			没有选择数据源
		{/if}
		
		
		
		<input type="hidden" id="ident" name="ident" value='{code}echo implode(',',$argument['ident']){/code}' />
		<input type="button" value="查询" onclick="hg_get_browse('{$formdata['id']}','{$formdata['line']}','1')" />
	<br>
		<div style="height:330px;OVERFLOW-y:auto;">
		<table width=100%>
			<tr><td>标题</td><td>外链</td><td>描述</td><td>操作</td></tr>
			{foreach $content_data as $k=>$v}
			<tr>
				<td>{$v['title']}</td>
				<td>{$v['outlink']}</td>
				<td>{$v['brief']}</td>
				<td><input type=button onclick="block_choose_content('{$v['id']}','{$v['title']}','{$v['outlink']}','{$v['brief']}','{$v['indexpic']}','{$v['appid']}','{$v['appname']}','{$v['publish_time']}')" value="选择"></td>
			</tr>
			{/foreach}
		</table>
		</div>
		<br>
		<div>
		选中的内容：<label id="choose_content" color=red></label>
		</div>
		<div >
		<input type="hidden" id="a" name="a" value="content_create" />
		<input type="hidden" id="content_id" name="content_id" value="" />
		<input type="hidden" id="title" name="title" value="" />
		<input type="hidden" id="outlink" name="outlink" value="" />
		<input type="hidden" id="brief" name="brief" value="" />
		<input type="hidden" id="indexpic" name="indexpic" value="" />
		<input type="hidden" id="appid" name="appid" value="" />
		<input type="hidden" id="appname" name="appname" value="" />
		<input type="hidden" id="publish_time" name="publish_time" value="" />
		<input type="hidden" id="id" name="id" value="{$formdata['id']}" />
		<input type="hidden" id="line" name="line" value="{$formdata['line']}" />
		<input type="button" name="sub"  onclick="hg_ajax_submit('datasource_content_form','','','hg_close_brower')" value="确定" />
		</div>
	</div>	
	</form>
	<form id="new_content_form" name="new_content_form" action="./run.php?mid={$_INPUT['mid']}&a=content_create" method="POST"  enctype="multipart/form-data">
	<div id="basic_info"  style="">
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">内容标题：</span>
								<input type="text" value="{$block_content_info['title']}" name='title' style="width:300px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li><br>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">内容描述：</span>
								<textarea name="brief" wrap="virtual" style="width:300px;height:30px" >
								{$block_content_info['brief']}
								</textarea>
								<span class="site_fill_tip">
								</span>
							</div>
						</li><br>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">外链：</span>
								<input type="text" value="{$block_content_info['outlink']}" name='outlink' style="width:300px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<br>
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">索引图：</span>
								<input type=file name="indexpic" style="width:100px;" />
								<div>
								{if $block_content_info['indexpic']}
									<img src="{$block_content_info['indexpic']}" width=100px height=100px />
								{/if}
								
								</div>
							</div>
						</li><br>
					</ul>
					字体颜色：<input type=text value="{$block_content_info['font_color']}" name="font_color" style="width:50px;height:12px">
					加粗：<input type=text value="{$block_content_info['font_b']}" name="font_b" style="width:50px;height:12px">
					字号：<input type=text value="{$block_content_info['font_size']}" name="font_size" style="width:50px;height:12px">
					边框：<input type=text value="{$block_content_info['font_border']}" name="font_border" style="width:50px;height:12px">
					底色：<input type=text value="{$block_content_info['font_backcolor']}" name="font_backcolor" style="width:50px;height:12px">
	
					<input type="hidden" name="id" value="{$formdata['id']}" />
					<input type="hidden" name="line" value="{$formdata['line']}" />
					<input type="hidden" name="block_content_id" value="{$block_content_info['id']}" />
		<div >
		<br>
		<input type="button" onclick="hg_ajax_submit('new_content_form','','','hg_close_brower')" name="sub" value="{if $block_content_info['id']}修改{else}确定{/if}" class="button_6_14"/>
		</div>
	</div>
	</form>
	
	<form id="block_line_set_form" name="block_line_set_form" action="./run.php?mid={$_INPUT['mid']}&a=block_line_set" method="POST">
	<div id="block_line_set" style="display:none">
					循环体：<input type=text value="{$block_line_data['loop_body']}" name="width" style="width:200px;height:12px"><br>
					行宽：<input type=text value="{$block_line_data['width']}" name="width" style="width:50px;height:12px">
					行高：<input type=text value="{$block_line_data['height']}" name="height" style="width:50px;height:12px">
					字体颜色：<input type=text value="{$block_line_data['font_color']}" name="font_color" style="width:50px;height:12px">
					加粗：<input type=text value="{$block_line_data['font_b']}" name="font_b" style="width:50px;height:12px">
					字号：<input type=text value="{$block_line_data['font_size']}" name="font_size" style="width:50px;height:12px">
					边框：<input type=text value="{$block_line_data['font_border']}" name="font_border" style="width:50px;height:12px">
					底色：<input type=text value="{$block_line_data['font_backcolor']}" name="font_backcolor" style="width:50px;height:12px">
	
	<div >
	<input type="hidden" name="id" value="{$formdata['id']}" />
		<input type="hidden" name="line" value="{$formdata['line']}" />
		<input type="button" onclick="hg_ajax_submit('block_line_set_form','','','hg_close_brower')"  name="sub" value="修改" class="button_6_14"/>
	</div>
	</div>	
	</form>
	