{css:bootstrap/3.3.0/bootstrap.min}
{css:spectrum/spectrum}
{js:jqueryfn/spectrum}
{js:jqueryfn/jqueryfn_custom/hg_spectrum}
{js:app_plant/app_attrs}
<style>
.sp-replacer{width:0px;height:0px;padding:0;border:0;visibility:hidden;}
</style>
<script type="text/x-jquery-tmpl" id="attrs-tpl">
{{if typeFlag == 'textbox'}}		<!-- 文本框 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text"  name="textbox_name" value="{{= style_value['name']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">size</span><input type="text"  name="size" value="{{= style_value['size']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">maxlength</span><input type="text"  name="maxlength" value="{{= style_value['maxlength']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">placeholder</span><input type="text"  name="placeholder" value="{{= style_value['placeholder']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">validate</span><input type="text" name="validate" value="{{= style_value['validate']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">默认值</span><input type="text" name="default_value" value="{{= default_value}}"/></div>
{{/if}}
{{if typeFlag=='textfield'}}		<!-- 文本域 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="textfield_name" value="{{= style_value['name']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">rows</span><input type="text"  name="rows" value="{{= style_value['rows']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">cols</span><input type="text"  name="cols" value="{{= style_value['cols']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">placeholder</span><input type="text"  name="placeholder" value="{{= style_value['placeholder']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">validate</span><input type="text"  name="validate" value="{{= style_value['validate']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">默认值</span><input type="text" name="default_value" value="{{= default_value}}"/></div>
{{/if}}
{{if typeFlag=='single_choice'}}	<!-- 单选 -->
	<div class="form-group">
		<span class="col-sm-2 control-label">name</span>
		<div class="col-sm-3">
			<input class="form-control" type="text" name="single_choice_name" value="{{= style_value['name']}}"/>
		</div>
	</div>
	<div class="form-group">
		<span class="col-sm-2 control-label">style</span>
		<div class="col-sm-7">
			<input type="radio" name="style" value="SEGMENT" {{if style_value['style']=='SEGMENT'}}checked{{/if}}/>分段式 
			<input type="radio" name="style" value="DROP_DOWN"  {{if style_value['style']=='DROP_DOWN'}}checked{{/if}}/>下拉式
		</div>
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='check'}}			<!-- 勾选 -->
	<div class="form-group">
		<span class="col-sm-2 control-label">name</span>
		<div class="col-sm-3">
			<input class="form-control" name="check_name" value="{{= style_value['name']}}"/></div>
		</div>
    <div class="form-group">
		<span class="col-sm-2 control-label">text</span>
		<div class="col-sm-3">
			<input class="form-control" name="check_text" value="{{= style_value['text']}}"/>
		</div>
	</div>
    <div class="form-group">
		<span class="col-sm-2 control-label">是否默认选中</span>
		<div class="col-sm-3">
			<input type="checkbox" name="is_selected" value="1" {{if style_value['is_selected']}}checked{{/if}}/>默认选中
		</div>
	</div>
{{/if}}
{{if typeFlag=='span'}}				<!-- 取值范围 -->
    <div class="form-group">
		<div class="col-sm-2 control-label">name</div>
		<div class="col-sm-3">
			<input class="form-control" name="span_name" value="{{= style_value['name']}}" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">start</div>
		<div class="col-sm-3">
			<input class="form-control" name="start" value="{{= style_value['start']}}" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">end</div>
		<div class="col-sm-3">
			<input class="form-control" name="end" value="{{= style_value['end']}}" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">精度</div>
		<div class="col-sm-3">
			<input class="form-control" name="degree" value="{{= style_value['degree']}}" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">包含开始值</div>
		<div class="col-sm-3">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="is_contain_start" value="1" {{if style_value['is_contain_start']}}checked{{/if}} />包含
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">包含结束值</div>
		<div class="col-sm-3">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="is_contain_end" value="1" {{if style_value['is_contain_end']}}checked{{/if}} />包含
				</label>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">默认值</div>
		<div class="col-sm-3">
			<input class="form-control" name="default_value" value="{{= default_value}}" />
		</div>
	</div>
{{/if}}
{{if typeFlag=='pic_radio'}}		<!-- 图片单选 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="pic_radio_name" value="{{= style_value['name']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">width</span><input type="text" name="width" value="{{= style_value['width']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">height</span><input type="text" name="height" value="{{= style_value['height']}}"/></div>
	<div class="form-group">
		<span class="col-sm-2 control-label">方向</span>
		<input type="radio" name="direction" value="HORIZONTAL" {{if style_value['direction']=='HORIZONTAL'}}checked{{/if}}/>水平
		<input type="radio" name="direction" value="VERTICAL"  {{if style_value['direction']=='VERTICAL'}}checked{{/if}}/>竖直
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='pic_upload_radio'}}	<!-- 图片上传+单选 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="pic_upload_radio_name" value="{{= style_value['name']}}"/></div>
    <div class="form-group"><span class="col-sm-2 control-label">width</span><input type="text" name="width" value="{{= style_value['width']}}"/></div>
	<div class="form-group"><span class="col-sm-2 control-label">height</span><input type="text" name="height" value="{{= style_value['height']}}"/></div>
	<div class="form-group">
		<span class="col-sm-2 control-label">方向</span>
		<input type="radio" name="direction" value="HORIZONTAL" {{if style_value['direction']=='HORIZONTAL'}}checked{{/if}}/>水平
		<input type="radio" name="direction" value="VERTICAL"  {{if style_value['direction']=='VERTICAL'}}checked{{/if}}/>竖直
	</div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='multiple_choice'}}	<!-- 多选 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="multiple_choice_name" value="{{= style_value['name']}}"/></div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='color_picker'}}		<!-- 拾色器 -->
<div class="only-spectrum">
	<div class="form-group">
		<span class="col-sm-2 control-label">name</span>
		<div class="col-sm-3">
			<input type="text" name="color_picker_name" value="{{= style_value['name']}}"/>
		</div>
	</div>
	<div class="form-group">
		<span class="col-sm-2 control-label">color默认值</span>
		<div class="overflow">
			<input class="spectrum-colorpicker" _color="{{= default_value}}">
			<input type="hidden" name="default_value" value="{{= default_value}}"/>
		</div>
	</div>
</div>
{{/if}}
{{if typeFlag=='advanced_color_picker'}}	<!-- 高级拾色器 -->
<div class="only-spectrum">
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="advanced_color_picker_name" value="{{= style_value['name']}}"/></div>
	<div class="form-group">
		<span class="col-sm-2 control-label">color默认值</span>
		<div class="overflow">
			<input class="spectrum-colorpicker alpha" _color="{{if currentData.default_value}}{{= currentData.default_value.color}}{{/if}}" _alpha="{{if currentData.default_value}}{{= currentData.default_value.alpha}}{{/if}}">
			<input type="hidden" name="default_value" value="{{if currentData.default_value}}{{= currentData.default_value.color}}|{{= currentData.default_value.alpha}}{{/if}}"/>
			<input type="checkbox" name="is_alpha" value="1" {{if style_value['is_alpha']}}checked{{/if}}/>开启透明度设置
		</div>
	</div>
</div>
{{/if}}
{{if typeFlag=='color_schemes'}}			<!-- 配色方案 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="color_schemes_name" value="{{= style_value['name']}}"/></div>
	{{tmpl({}, {typeFlag:typeFlag, data: style_value['datasource']}) '#more-setting-wrap-tpl'}}
{{/if}}
{{if typeFlag=='advanced_color_schemes'}}	<!-- 高级配色方案 -->
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="advanced_color_schemes_name" value="{{= style_value['name']}}"/>
	<input class="colorpicker" placeholder="默认值"/></div>
{{/if}}
{{if typeFlag=='advanced_background_set'}}	<!-- 高级背景设置 -->
<div class="advance-setting">
	<div class="form-group">
		<div class="col-sm-2 control-label">name</div>
		<div class="col-sm-3">
			<input class="form-control" name="advanced_background_set_name" value="{{if isNew}}{{else}}{{= style_value['name']}}{{/if}}"/>
		</div>
	</div>
	{{if isNew}}
	<div class="form-group">
		<div class="col-sm-2 control-label">背景类型</div>
		<div class="col-sm-8">
			<div class="common-tab-wrap">
				<div class="tab-btns sys-flex">
					<div class="tab-btn selected" _type="">使用色值</div>
					<div class="tab-btn" _type="">使用图片</div>
				</div>
				<div class="tab-content">
					<div class="tab-item">
						<div class="tab-inner-item sys-flex">
							<p class="item-name">选择默认色</p>
							<div class="item-content">
								<input type="text" class="spectrum-colorpicker alpha"/>
								<input type="hidden" name="default_value" value="" />
							</div>
						</div>
					</div>
					<div class="tab-item hide">
						<div class="tab-inner-item sys-flex">
							<p class="item-name">宽</p>
							<div class="item-content">
								<input type="text" class="form-control" name="width"/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">高</p>
							<div class="item-content">
								<input type="text" class="form-control" name="width"/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">建议文本</p>
							<div class="item-content">
								<input type="text" class="form-control" name="info"/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">上传默认图</p>
							<div class="item-content">
								<div class="pic-prev">
									<img src="">
								</div>
								<input type="hidden" name="default_value" value="disabled/>
								<input type="file" accept="image/*" name="default_value" disabled/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">图片是否平铺</p>
							<div class="item-content">
								<input type="checkbox" class="is-tile" />平铺
								<input type="hidden" name="is_tile"/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{else}}
	<div class="form-group">
		<div class="col-sm-2 control-label">背景类型</div>
		<div class="col-sm-8">
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
								<input type="text" class="spectrum-colorpicker alpha" _color="{{= currentData.default_value.color}}" _alpha="{{= currentData.default_value.alpha}}"/>
								<input type="hidden" name="default_value" value="{{if currentData.default_value.color}}color|{{= currentData.default_value.color}}|{{= currentData.default_value.alpha}}{{/if}}" {{if currentData.default_value.img}}disabled{{/if}}/>
							</div>
						</div>
					</div>
					<div class="tab-item {{if !currentData.default_value.img}}hide{{/if}}">
						<div class="tab-inner-item sys-flex">
							<p class="item-name">宽</p>
							<div class="item-content">
								<input type="text" class="form-control" name="width" value="{{= currentData.default_value.width}}"/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">高</p>
							<div class="item-content">
								<input type="text" class="form-control" name="height" value="{{= currentData.default_value.height}}"/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">建议文本</p>
							<div class="item-content">
								<input type="text" class="form-control" name="info" value="{{= currentData.default_value.info}}"/>
							</div>
						</div>
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
	</div>
	{{/if}}
</div>
{{/if}}
{{if typeFlag=='advanced_character_set'}}	<!-- 高级文字设置 -->
<div class="advance-setting">
	{{if isNew}}
	<div class="form-group">
		<div class="col-sm-2 control-label">name</div>
		<div class="col-sm-4">
			<input class="form-control" name="advanced_character_set_name" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2 control-label">文字类型</div>
		<div class="col-sm-8">
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
								<input class="form-control" value="" />
								<input type="hidden" name="default_value" value="">
							</div>
						</div>
					</div>
					<div class="tab-item hide">
						<div class="tab-inner-item sys-flex">
							<p class="item-name">宽</p>
							<div class="item-content">
								<input type="text" class="form-control" name="width" value=""/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">高</p>
							<div class="item-content">
								<input type="text" class="form-control" name="height" value=""/>
							</div>
						</div>
						<div class="tab-inner-item sys-flex">
							<p class="item-name">建议文本</p>
							<div class="item-content">
								<input type="text" class="form-control" name="info" value=""/>
							</div>
						</div>
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
	</div>
	{{else}}
	<div class="form-group"><span class="col-sm-2 control-label">name</span><input type="text" name="advanced_character_set_name" value="{{= style_value['name']}}"/></div>
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
						<input class="form-control" value="{{= currentData.default_value.text}}" />
						<input type="hidden" name="default_value" value="{{= currentData.default_value.text}}" {{if currentData.default_value.img}}disabled{{/if}}>
					</div>
				</div>
			</div>
			<div class="tab-item {{if !currentData.default_value.img}}hide{{/if}}">
				<div class="tab-inner-item sys-flex">
					<p class="item-name">宽</p>
					<div class="item-content">
						<input type="text" class="form-control" name="width" value="{{= currentData.default_value.width}}"/>
					</div>
				</div>
				<div class="tab-inner-item sys-flex">
					<p class="item-name">高</p>
					<div class="item-content">
						<input type="text" class="form-control" name="height" value="{{= currentData.default_value.height}}"/>
					</div>
				</div>
				<div class="tab-inner-item sys-flex">
					<p class="item-name">建议文本</p>
					<div class="item-content">
						<input type="text" class="form-control" name="info" value="{{= currentData.default_value.info}}"/>
					</div>
				</div>
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
	{{/if}}
</div>
{{/if}}
</script>
<script type="text/x-jquery-tmpl" id="more-setting-wrap-tpl">
<div class="form-group">
	<span class="col-sm-2 control-label">更多设置</span>
	<div class="col-sm-9">
		<div class="more-setting-box overflow">
			<a class="add-btn">添加选项</a>
			<div class="items-box">
			{{tmpl({}, {typeFlag:$item.typeFlag, data: $item.data}) '#more-setting-item-tpl'}}
			</div>
		</div>
	</div>
</div>
</script>
<script type="text/x-jquery-tmpl" id="more-setting-item-tpl">
{{if $item.typeFlag == 'single_choice'}}	<!-- 单选 -->
{{each $item.data}}
<div class="form-group row">
	<div class="col-sm-4">	
		<input class="form-control" type="text" placeholder="显示文字" name="display_text[]" value="{{= $value['text']}}"/>
	</div>
	<div class="col-sm-4">	
		<input class="form-control" type="text" placeholder="值"  name="value[]" value="{{= $value['value']}}"/>
	</div>	
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