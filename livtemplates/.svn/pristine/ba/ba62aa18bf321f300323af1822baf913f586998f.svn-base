{css:spectrum/spectrum}
{js:jqueryfn/spectrum}
{js:jqueryfn/jqueryfn_custom/hg_spectrum}
{js:app_plant/app_attrs}
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
<div class="only-spectrum">
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
</div>
{{/if}}
{{if typeFlag=='advanced_color_picker'}}	<!-- 高级拾色器 -->
<div class="only-spectrum">
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_color_picker_name" value="{{= style_value['name']}}"/></div>
	<div class="tile-item">
		<span class="tname">color默认值</span>
		<div class="overflow">
			<div class="spectrum_cp-wrap">
				<input type="text" class="spectrum_cp alpha" data-color="{{if currentData.default_value}}{{= currentData.default_value.color}}{{/if}}"/>
				<input type="hidden" name="default_value" value="{{if currentData.default_value}}{{= currentData.default_value.color}}|{{= currentData.default_value.alpha}}{{/if}}"/>
			</div>
			<input type="checkbox" name="is_alpha" value="1" {{if style_value['is_alpha']}}checked{{/if}}/>开启透明度设置
		</div>
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
{{if isNew}}
<div class="advance-setting">
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_background_set_name" value=""/></div>
	<div class="common-tab-wrap">
		<div class="tab-btns sys-flex">
			<div class="tab-btn selected" _type="">使用色值</div>
			<div class="tab-btn" _type="">使用图片</div>
		</div>
		<div class="tab-content">
			<div class="tab-item">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">选择默认色</p>
					<div class="item-content spectrum_cp-wrap">
						<input type="text" class="spectrum_cp alpha" data-color=""/>
						<input type="hidden" name="default_value" value=""/>
					</div>
				</div>
			</div>
			<div class="tab-item hide">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">上传默认图</p>
					<div class="item-content">
						<div class="pic-prev">
							<img src="">
						</div>
						<input type="hidden" name="default_value" value="" disabled/>
						<input type="file" accept="image/*" name="default_value" disabled/>
					</div>
				</div>
				<div class="tab-inner-item sys-flex">
					<p class="item-name">图片是否平铺</p>
					<div class="item-content">
						<input type="checkbox" class="is-tile"/>平铺
						<input type="hidden" name="is_tile"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{else}}
<div class="advance-setting">
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_background_set_name" value="{{= style_value['name']}}"/></div>
	<div class="common-tab-wrap">
		<div class="tab-btns sys-flex">
			<div class="tab-btn {{if !currentData.default_value.img}}selected{{/if}}" _type="">使用色值</div>
			<div class="tab-btn {{if currentData.default_value.img}}selected{{/if}}" _type="">使用图片</div>
		</div>
		<div class="tab-content">
			<div class="tab-item {{if currentData.default_value.img}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">选择默认色</p>
					<div class="item-content spectrum_cp-wrap">
						<input type="text" class="spectrum_cp alpha" data-color="{{= currentData.default_value.color}}"/>
						<input type="hidden" name="default_value" value="{{if currentData.default_value.color}}color|{{= currentData.default_value.color}}|{{= currentData.default_value.alpha}}{{/if}}" {{if currentData.default_value.img}}disabled{{/if}}/>
					</div>
				</div>
			</div>
			<div class="tab-item {{if !currentData.default_value.img}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">上传默认图</p>
					<div class="item-content">
						<div class="pic-prev">
							<img src="{{if currentData.default_value.img}}{{= $.imgSrc( currentData.default_value.img )}}{{/if}}">
						</div>
						<input type="hidden" name="default_value" value="{{if currentData.default_value.img}}img_id|{{= currentData.default_value.img.id}}{{/if}}"{{if !currentData.default_value.img}}disabled{{/if}}/>
						<input type="file" accept="image/*" name="default_value" {{if !currentData.default_value.img}}{{else currentData.default_value.img.id}}disabled{{/if}}/>
					</div>
				</div>
				<div class="tab-inner-item sys-flex">
					<p class="item-name">图片是否平铺</p>
					<div class="item-content">
						<input type="checkbox" class="is-tile" {{if currentData.default_value}}{{if currentData.default_value.is_tile==1}}checked{{/if}}{{/if}}/>平铺
						<input type="hidden" name="is_tile" {{if currentData.default_value}}{{= currentData.default_value.is_tile}}{{/if}}/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{/if}}
{{/if}}
{{if typeFlag=='advanced_character_set'}}	<!-- 高级文字设置 -->
{{if isNew}}
<div class="advance-setting">
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_character_set_name" value=""/></div>
	<div class="common-tab-wrap">
		<div class="tab-btns sys-flex">
			<div class="tab-btn selected" _type="">使用文字</div>
			<div class="tab-btn" _type="">使用图片</div>
		</div>
		<div class="tab-content">
			<div class="tab-item">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">填写文字</p>
					<div class="item-content">
						<input class="text-input" value="" />
						<input type="hidden" name="default_value" value="" >
					</div>
				</div>
			</div>
			<div class="tab-item hide">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">上传默认图</p>
					<div class="item-content">
						<div class="pic-prev">
							<img src="">
						</div>
						<input type="hidden" name="default_value" value="" disabled/>
						<input type="file" accept="image/*" name="default_value" disabled/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{else}}
<div class="advance-setting">
	<div class="tile-item"><span class="tname">name</span><input type="text" name="advanced_character_set_name" value="{{= style_value['name']}}"/></div>
	<div class="common-tab-wrap">
		<div class="tab-btns sys-flex">
			<div class="tab-btn {{if !currentData.default_value.img}}selected{{/if}}" _type="">使用文字</div>
			<div class="tab-btn {{if currentData.default_value.img}}selected{{/if}}" _type="">使用图片</div>
		</div>
		<div class="tab-content">
			<div class="tab-item {{if currentData.default_value.img}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">填写文字</p>
					<div class="item-content">
						<input class="text-input" value="{{= currentData.default_value.text}}" />
						<input type="hidden" name="default_value" value="{{= currentData.default_value.text}}" {{if currentData.default_value.img}}disabled{{/if}}>
					</div>
				</div>
			</div>
			<div class="tab-item {{if !currentData.default_value.img}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">上传默认图</p>
					<div class="item-content">
						<div class="pic-prev">
							<img src="{{if currentData.default_value.img}}{{= $.imgSrc( currentData.default_value.img )}}{{/if}}">
						</div>
						<input type="hidden" name="default_value" value="{{if currentData.default_value.img}}img_id|{{= currentData.default_value.img.id}}{{/if}}"{{if !currentData.default_value.img}}disabled{{/if}}/>
						<input type="file" accept="image/*" name="default_value" {{if !currentData.default_value.img}}{{else currentData.default_value.img.id}}disabled{{/if}}/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{/if}}
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
		<input type="text" name="value[]" value="{{= $value['value']}}">
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
		<input type="text" name="value[]" value="{{= $value['value']}}">
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