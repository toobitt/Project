{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{css:colorpicker}
{css:spectrum/spectrum}
{css:attribute_form_list}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
{js:spectrum/spectrum}
{js:spectrum/hg_spectrum}
{js:app_plant/app_attrs}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>复制属性</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">属性名称：</span>
								<input type="text"  required="true" value="{$name}_copy" name='name' style="width:200px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">属性标识：</span>
								<input type="text"  required="true" value="{$uniqueid}" name='uniqueid'  style="width:200px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">属性简介：</span>
								<textarea name="brief">{$brief}</textarea>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属分组</span>
								{code}
									$attr_group = array(
                                		'class' => 'attr_group down_list',
                                		'show'  => 'attr_group_show',
                                		'width' => 200,
                                		'state' => 0,
                                		'is_sub'=> 1,
                                    );
                                    
                                    if(!$group_id)
                                    {
                                    	$group_id = 0;
                                    }
                                    
                                    $group_arr[0] = '选择分组';
                                    if($group_data)
                                    {
                                    	foreach($group_data AS $_k => $_v)
                                    	{
											 $group_arr[$_v['id']] = $_v['name'];                                  		
                                    	}
                                    }
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,group_id,$group_id,$group_arr,$attr_group}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属角色</span>
								{code}
									$attr_role = array(
                                		'class' => 'attr_role down_list',
                                		'show'  => 'attr_role_show',
                                		'width' => 200,
                                		'state' => 0,
                                		'is_sub'=> 1,
                                    );
                                    
                                    if(!$role_type_id)
                                    {
                                    	$role_type_id = 0;
                                    }
                                    
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,role_type_id,$role_type_id,$_configs['role_type'],$attr_role}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属UI</span>
								{code}
									$attr_ui = array(
                                		'class' => 'attr_ui down_list',
                                		'show'  => 'attr_ui_show',
                                		'width' => 200,
                                		'state' => 0,
                                		'is_sub'=> 1,
                                    );
                                    
                                    if(!$ui_id)
                                    {
                                    	$ui_id = 0;
                                    }
                                    
                                    $ui_arr[0] = '选择UI';
                                    if($ui_data)
                                    {
                                    	foreach($ui_data AS $_k => $_v)
                                    	{
											 $ui_arr[$_v['id']] = $_v['name'];                                  		
                                    	}
                                    }
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,ui_id,$ui_id,$ui_arr,$attr_ui}
								</div>
							</div>
						</li>
						
						<li class="i select-attr-type-wrap">
							<div class="form_ul_div">
								<span  class="title">属性类型</span>
								{code}
									$attr_type_source = array(
                                		'class'  => 'attr_type down_list',
                                		'show'   => 'attr_type_show',
                                		'width'  => 200,
                                		'state'  => 0,
                                		'is_sub' => 1,
                                    );
                                    
                                    if(!$attr_type_id)
                                    {
                                    	$attr_type_id = 0;
                                    }
                                    
                                    $attr_type_arr[0] = '选择属性类型';
                                    if($attr_type)
                                    {
                                    	foreach($attr_type AS $_k => $_v)
                                    	{
											 $attr_type_arr[$_v['id']] = $_v['name'];                                  		
                                    	}
                                    }
                                    
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,attr_type_id,$attr_type_id,$attr_type_arr,$attr_type_source}
								</div>
							</div>
						</li>
						<li class="i" style="min-height:300px;">
							<div class="form_ul_div">
								<span class="title">表现样式设置：</span>
								<div class="set-attr-default">--</div>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="create" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="添加" class="button_6_14"/>
				<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
<script>
window.attrConfig = {code}echo $attr_type ? json_encode($attr_type) : '[]'{/code};
currentData = {code}echo $formdata ? json_encode($formdata) : '{}'{/code};
</script>
<!-- 模板 -->
<script type="text/x-jquery-tmpl" id="attrs-tpl">
{{if typeFlag == 'textbox'}}		<!-- 文本框 -->
	<div class="tile-item"><span class="tname">name</span><input type="text"  name="textbox_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item"><span class="tname">size</span><input type="text"  name="size" value="{{= style_value['size']}}"/></div>
	<div class="tile-item"><span class="tname">maxlength</span><input type="text"  name="maxlength" value="{{= style_value['maxlength']}}"/></div>
	<div class="tile-item"><span class="tname">placeholder</span><input type="text"  name="placeholder" value="{{= style_value['placeholder']}}"/></div>
	<div class="tile-item"><span class="tname">validate</span><input type="text" name="validate" value="{{= style_value['validate']}}"/></div>
	<div class="tile-item"><span class="tname">默认值</span><input type="text" name="default_value" value="{{= default_value}}"/></div>
{{/if}}
{{if typeFlag=='textfield'}}		<!-- 文本域 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="textfield_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item"><span class="tname">rows</span><input type="text"  name="rows" value="{{= style_value['rows']}}"/></div>
	<div class="tile-item"><span class="tname">cols</span><input type="text"  name="cols" value="{{= style_value['cols']}}"/></div>
	<div class="tile-item"><span class="tname">placeholder</span><input type="text"  name="placeholder" value="{{= style_value['placeholder']}}"/></div>
	<div class="tile-item"><span class="tname">validate</span><input type="text"  name="validate" value="{{= style_value['validate']}}"/></div>
	<div class="tile-item"><span class="tname">默认值</span><input type="text" name="default_value" value="{{= default_value}}"/></div>
{{/if}}
{{if typeFlag=='single_choice'}}	<!-- 单选 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="single_choice_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item">
		<span class="tname">style</span>
		<input type="radio" name="style" value="SEGMENT" {{if style_value['style']=='SEGMENT'}}checked{{/if}}/>分段式 
		<input type="radio" name="style" value="DROP_DOWN"  {{if style_value['style']=='DROP_DOWN'}}checked{{/if}}/>下拉式
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='check'}}			<!-- 勾选 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="check_name" value="{{= style_value['name']}}"/></div>
    <div class="tile-item"><span class="tname">text</span><input type="text" name="check_text" value="{{= style_value['text']}}"/></div>
    <div class="tile-item"><span class="tname">是否默认选中</span><input type="checkbox" name="is_selected" value="1" {{if style_value['is_selected']}}checked{{/if}}/>默认选中</div>
{{/if}}
{{if typeFlag=='span'}}				<!-- 取值范围 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="span_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item"><span class="tname">start</span><input type="text" name="start" value="{{= style_value['start']}}"/></div>
	<div class="tile-item"><span class="tname">end</span><input type="text" name="end" value="{{= style_value['end']}}"/></div>
	<div class="tile-item"><span class="tname">精度</span><input type="text" name="degree" value="{{= style_value['degree']}}"/></div>
	<div class="tile-item"><span class="tname">是否包含开始值</span><input type="checkbox" name="is_contain_start" value="1" {{if style_value['is_contain_start']}}checked{{/if}}/>包含</div>
	<div class="tile-item"><span class="tname">是否包含结束值</span><input type="checkbox" name="is_contain_end" value="1" {{if style_value['is_contain_end']}}checked{{/if}}/>包含</div>
    <div class="tile-item"><span class="tname">默认值</span><input type="text" name="default_value" value="{{= default_value}}"/></div>
{{/if}}
{{if typeFlag=='pic_radio'}}		<!-- 图片单选 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="pic_radio_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item"><span class="tname">width</span><input type="text" name="width" value="{{= style_value['width']}}"/></div>
	<div class="tile-item"><span class="tname">height</span><input type="text" name="height" value="{{= style_value['height']}}"/></div>
	<div class="tile-item">
		<span class="tname">方向</span>
		<input type="radio" name="direction" value="HORIZONTAL" {{if style_value['direction']=='HORIZONTAL'}}checked{{/if}}/>水平
		<input type="radio" name="direction" value="VERTICAL"  {{if style_value['direction']=='VERTICAL'}}checked{{/if}}/>竖直
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='pic_upload_radio'}}	<!-- 图片上传+单选 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="pic_upload_radio_name" value="{{= style_value['name']}}"/></div>
    <div class="tile-item"><span class="tname">width</span><input type="text" name="width" value="{{= style_value['width']}}"/></div>
	<div class="tile-item"><span class="tname">height</span><input type="text" name="height" value="{{= style_value['height']}}"/></div>
	<div class="tile-item">
		<span class="tname">方向</span>
		<input type="radio" name="direction" value="HORIZONTAL" {{if style_value['direction']=='HORIZONTAL'}}checked{{/if}}/>水平
		<input type="radio" name="direction" value="VERTICAL"  {{if style_value['direction']=='VERTICAL'}}checked{{/if}}/>竖直
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='multiple_choice'}}	<!-- 多选 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="multiple_choice_name" value="{{= style_value['name']}}"/></div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='color_picker'}}		<!-- 拾色器 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="color_picker_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item">
		<span class="tname">color默认值</span>
		<div class="overflow">
			<div class="spectrum_cp-wrap">
				<input type="text" class="spectrum_cp" data-color="{{= default_value}}"/>
				<input type="hidden" name="default_value" value="{{= default_value}}"/>
			</div>
		</div>
	</div>
{{/if}}
{{if typeFlag=='advanced_color_picker'}}	<!-- 高级拾色器 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_color_picker_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item">
		<span class="tname">color默认值</span>
		<div class="overflow">
			<div class="spectrum_cp-wrap">
				<input type="text" class="spectrum_cp alpha" data-color="{{= default_value}}"/>
				<input type="hidden" name="default_value" value="{{= default_value}}"/>
			</div>
			<input type="checkbox" name="is_alpha" value="1" {{if style_value['is_alpha']}}checked{{/if}}/>开启透明度设置
		</div>
	</div>
{{/if}}
{{if typeFlag=='color_schemes'}}			<!-- 配色方案 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="color_schemes_name" value="{{= style_value['name']}}"/></div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='advanced_color_schemes'}}	<!-- 高级配色方案 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_color_schemes_name" value="{{= style_value['name']}}"/>
	<input class="colorpicker" placeholder="默认值"/></div>
{{/if}}
{{if typeFlag=='advanced_background_set'}}	<!-- 高级背景设置 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_background_set_name" value="{{= style_value['name']}}"/></div>
	<div class="common-tab-wrap">
		<div class="tab-btns sys-flex">
			<div class="tab-btn {{if typeof default_value != 'object'}}selected{{/if}}" _type="">使用色值</div>
			<div class="tab-btn {{if typeof default_value == 'object'}}selected{{/if}}" _type="">使用图片</div>
		</div>
		<div class="tab-content">
			<div class="tab-item {{if typeof default_value == 'object'}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">选择默认色</p>
					<div class="item-content spectrum_cp-wrap">
						<input type="text" class="spectrum_cp alpha" data-color="{{= default_value}}"/>
						<input type="hidden" name="default_value" value="{{if typeof default_value != 'object'}}{{= default_value}}{{/if}}" {{if typeof default_value == 'object'}}disabled{{/if}}/>
					</div>
				</div>
			</div>
			<div class="tab-item {{if typeof default_value != 'object'}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">图片是否平铺</p>
					<div class="item-content">
						<input type="checkbox" name="is_tile" value="1" {{if style_value['is_tile']}}checked{{/if}}/>平铺
					</div>
				</div>
				<div class="tab-inner-item sys-flex">
					<p class="item-name">上传默认图</p>
					<div class="item-content">
						<div class="pic-prev">
							<img src="{{if typeof default_value == 'object'}}{{= default_value && $.imgSrc( default_value )}}{{/if}}">
						</div>
						<input type="file" accept="image/*" name="default_value" {{if typeof default_value != 'object'}}disabled{{/if}}/>
					</div>
				</div>
			</div>
		</div>
	</div>
{{/if}}
{{if typeFlag=='advanced_character_set'}}	<!-- 高级文字设置 -->
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_character_set_name" value="{{= style_value['name']}}"/></div>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="more-setting-wrap-tpl">
<div class="tile-item">
	<span class="tname">更多设置</span>
	<div class="more-setting-box overflow">
		<a class="add-btn">添加选项</a>
		<div class="items-box">
		{{tmpl({}, {typeFlag:$item.typeFlag, data: $item.data}) '#more-setting-item-tpl'}}
		</div>
	</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="more-setting-item-tpl">
{{if $item.typeFlag == 'single_choice'}}	<!-- 单选 -->
{{each $item.data}}
	<div class="setting-item">
		<input type="text" placeholder="显示文字" name="display_text[]" value="{{= $value['text']}}"/>
		<input type="text" placeholder="值"  name="value[]" value="{{= $value['value']}}"/>
		<input type="radio" value="{{= $item.index || $index}}" name="is_selected" {{if $value['is_selected']}}checked{{/if}}/>设为默认选中
		<span class="del-btn">-</span>
	</div>
{{/each}}
{{/if}}
{{if $item.typeFlag == 'multiple_choice'}}	<!-- 多选 -->
{{each $item.data}}
	<div class="setting-item">
		<input type="text" placeholder="显示文字" name="display_text[]" value="{{= $value['text']}}"/>
		<input type="text" placeholder="值"  name="value[]" value="{{= $value['value']}}"/>
		<input type="checkbox" value="{{= $item.index || $index}}" name="is_selected[]" {{if $value['is_selected']}}checked{{/if}}/>设为默认选中
		<span class="del-btn">-</span>
	</div>
{{/each}}
{{/if}}
{{if $item.typeFlag == 'pic_radio'}}		<!-- 图片单选 -->
{{each $item.data}}
	<div class="setting-item">
		<div class="pic-prev">
			<img src="{{= $value['img_info'] && $.imgSrc( $value['img_info'] )}}">
		</div>
		<input type="file" accept="image/*" name="pics[]"/>
		<div>
		    <input type="radio" name="is_selected[]" value="{{= $item.index || $index}}" {{if $value['is_selected']}}checked{{/if}}/>设为默认选中
		    <span class="del-btn">-</span>
		</div>
	</div>
{{/each}}
{{/if}}
{{if $item.typeFlag == 'pic_upload_radio'}}		<!-- 图片上传+单选 -->
{{each $item.data}}
	<div class="setting-item">
		<div class="pic-prev">
			<img src="{{= $value['img_info'] && $.imgSrc( $value['img_info'] )}}">
		</div>
		<input type="file" accept="image/*" name="pics[]"/>
		<div>
		    <input type="radio" name="is_selected[]" value="{{= $item.index || $index}}" {{if $value['is_selected']}}checked{{/if}}/>设为默认选中
		    <span class="del-btn">-</span>
		</div>
	</div>
{{/each}}
{{/if}}
{{if $item.typeFlag == 'color_schemes'}}	<!-- 配色方案 -->
{{each $item.data}}
	<div class="setting-item">
		<input placeholder="颜色名称" name="color_name[]" value="{{= $value['color_name']}}"/>
		<input placeholder="值" name="color_value[]" value="{{= $value['color_value']}}"/>
		<input type="radio" name="is_selected" value="{{= $item.index || $index}}" {{if $value['is_selected']}}checked{{/if}}/>设为默认选中
		<span class="del-btn">-</span>
	</div>
{{/each}}
{{/if}}
</script>
<!-- 模板end -->
{template:foot}