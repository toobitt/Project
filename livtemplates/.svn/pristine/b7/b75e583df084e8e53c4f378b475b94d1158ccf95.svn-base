{css:colorpicker}
{js:jqueryfn/colorpicker.min}
{js:2013/hg_colorpicker}
<style type="text/css">
input[type="text"] {line-height:20px; height:20px;}
.config_form p {margin:10px 0;}
.config_form label {margin-right:15px;}
.config_form label input {margin-right:5px;}
.config_form input {vertical-align:middle;}
.config_form .dropBtn {width:20px; height:20px; line-height:20px; display:inline-block; text-align:center; cursor:pointer; font-style:normal;}
.terminal_size {padding-left:20px;}
.terminal_size input {width:300px;}
.config_form ul li {margin:10px 0 10px 10px;}
.btn {border:1px solid #CCC; display:inline-block; width:20px; height:20px; line-height:20px; text-align:center; cursor:pointer; margin-left:10px;}
.defStyle {margin-right:10px;}
.colorpicker-wrap {display:inline-block; margin-left:10px;}
.color-custom-txt {width:50px;}
#shapeSignList input[type="text"] {font-size:2em; width:80px;}
.keyValueLayout label {margin:0 5px 0 10px;}
.valueOption {width:100px;}
</style>
<script type="text/javascript">
var effectCount = {code}echo count($settings['base']['app_effect']);{/code};
var textCount = {code}echo count($settings['base']['cpTextSize']);{/code};
var guideEffectCount = {code}echo count($settings['base']['guideEffect']);{/code};
var guideCount = {code}echo count($settings['base']['guideAnimation']);{/code};
var signCount = {code}echo count($settings['base']['shapeSign']);{/code};

$(function() {
	$('input:checkbox').live('click', function() {
		if ($(this).attr('checked'))
		{
			$(this).parent().parent().find('input:checkbox').removeAttr('checked');
			$(this).attr('checked', true);
		}
		else
		{
			$(this).removeAttr('checked');
		}
	});
	$('#addBtn').click(function() {
		var con = '<li><input type="text" name="base[names][]" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" placeholder="数据地址" size="50" /><span class="btn dropBtn">-</span></li>';
		$(this).parent().parent().append(con);
	});
	$('.dropBtn').live('click', function() {
		$(this).parent().remove();
	});

	//启动图启动方式
	$('#addEffect').click(function() {
		effectCount++;
		var con = '<li>\
			<label>标识</label><input type="text" name="base[appEffect_identifiers]['+effectCount+']" />\
			<label>选项</label><input type="text" name="base[appEffect_options]['+effectCount+']" />\
			<label>值</label><input type="text" class="valueOption" name="base[appEffect_values]['+effectCount+']" />\
			<input type="checkbox" name="base[appEffect_default]['+effectCount+']" value="1" />\
			<span class="btn dropEffect">-</span>\
		</li>';
		$('#effectList').append(con);
	});
	$('.dropEffect').live('click', function() {
		$(this).parent().remove();
	});

	//版权文字
	$('#addTextSizeOption').click(function() {
		textCount++;
		var con = '<li>\
			<label>标识</label><input type="text" name="base[cpTextSize_identifiers]['+textCount+']" />\
			<label>选项</label><input type="text" name="base[cpTextSize_options]['+textCount+']" />\
			<label>值</label><input type="text" class="valueOption" name="base[cpTextSize_values]['+textCount+']" />\
			<input type="checkbox" name="base[cpTextSize_default]['+textCount+']" value="1" />\
			<span class="btn dropTextSizeOption">-</span>\
		</li>';
		$('#textSizeList').append(con);
	});
	$('.dropTextSizeOption').live('click', function() {
		$(this).parent().remove();
	});

	//引导效果
	$('#addGuideEffect').click(function() {
		guideEffectCount++;
		var con = '<li>\
			<label>标识</label><input type="text" name="base[guideEffect_identifiers]['+guideEffectCount+']" />\
			<label>选项</label><input type="text" name="base[guideEffect_options]['+guideEffectCount+']" />\
			<label>值</label><input type="text" class="valueOption" name="base[guideEffect_values]['+guideEffectCount+']" />\
			<input type="checkbox" name="base[guideEffect_default]['+guideEffectCount+']" value="1" />\
			<span class="btn dropGuideEffect">-</span>\
		</li>';
		$('#guideEffectList').append(con);
	});
	$('.dropGuideEffect').live('click', function() {
		$(this).parent().remove();
	});

	//引导动画
	$('#addAnimationEffect').click(function() {
		guideCount++;
		var con = '<li>\
			<label>标识</label><input type="text" name="base[animation_identifiers]['+guideCount+']" />\
			<label>选项</label><input type="text" name="base[animation_options]['+guideCount+']" />\
			<label>值</label><input type="text" class="valueOption" name="base[animation_values]['+guideCount+']" />\
			<input type="checkbox" name="base[animation_default]['+guideCount+']" value="1" />\
			<span class="btn dropAnimationEffect">-</span>\
		</li>';
		$('#guideAnimationList').append(con);
	});
	$('.dropAnimationEffect').live('click', function() {
		$(this).parent().remove();
	});

	//引导页脚标记
	$('#addShapeSign').click(function() {
		signCount++;
		var con = '<li>\
			<input type="text" name="base[shape_signs]['+signCount+']" placeholder="标记符" />\
			<input type="checkbox" name="base[sign_default]['+signCount+']" value="1" />\
			<span class="btn dropShapeSign">-</span>\
		</li>';
		$('#shapeSignList').append(con);
	});
	$('.dropShapeSign').live('click', function() {
		$(this).parent().remove();
	});
	
	/*颜色选择器*/
	var custom_color_txt = $('.color-custom-txt');
	custom_color_txt.on('blur', function() {
		var value = $(this).val(),	
		color_picker = $(this).parent().find('.color-picker');
		color_picker.val(value);
		color_picker.css({background:value});
	});
	$('.color-picker').hg_colorpicker({
		callback : function(color) {
			var custom_color_txt = $(this).closest('p').find('.color-custom-txt');
			custom_color_txt.val(color);
		}
	});
});
</script>
<div class="config_form">
{if $settings['base']['icon_size']}
<p><span>APP图标建议尺寸：</span><input type="text" name="base[icon_max_size][width]" value="{$settings['base']['icon_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[icon_max_size][height]" value="{$settings['base']['icon_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['icon_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . ',' . $vv['key'] . ',' . $vv['thumb'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size">
<span style="color:red;">android:每一项用'|'分隔，从左至右依次是：宽、高、客户端取值key、upyun上定义的对应的大小的缩略图</span>
<input type="text" name="base[icon_size][android]" value="{$size['android']}" style="width:950px;" /></label><br/><br/>
<span style="color:red;">ios:每一项用'|'分隔，从左至右依次是：宽、高、客户端取值key、upyun上定义的对应的大小的缩略图</span><br/>
<input type="text" name="base[icon_size][ios]" value="{$size['ios']}"  style="width:950px;" /></label>
</p>
{/if}
{if $settings['base']['startup_size']}
<p><span>APP启动画面建议尺寸：</span><input type="text" name="base[startup_max_size][width]" value="{$settings['base']['startup_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[startup_max_size][height]" value="{$settings['base']['startup_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['startup_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . ',' . $vv['key'] . ',' . $vv['thumb'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size">
<label>Android：<input type="text" name="base[startup_size][android]" value="{$size['android']}" /></label>
<label>iOS：<input type="text" name="base[startup_size][ios]" value="{$size['ios']}" style="width:500px;"/></label></p>
{/if}
{if $settings['base']['guide_size']}
<p><span>APP引导图建议尺寸：</span><input type="text" name="base[guide_max_size][width]" value="{$settings['base']['guide_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[guide_max_size][height]" value="{$settings['base']['guide_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['guide_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . ',' . $vv['key'] . ',' . $vv['thumb'] . ',' . $vv['effect2'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[guide_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[guide_size][ios]" value="{$size['ios']}" /></label></p>
{/if}
{if $settings['base']['module_size']}
<p><span>APP模块图标建议尺寸：</span><input type="text" name="base[module_max_size][width]" value="{$settings['base']['module_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[module_max_size][height]" value="{$settings['base']['module_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['module_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . ',' . $vv['key'] . ',' . $vv['thumb'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[module_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[module_size][ios]" value="{$size['ios']}" /></label></p>
{/if}
{if $settings['base']['navBarTitle_size']}
<p><span>导航栏标题建议尺寸：</span><input type="text" name="base[nav_max_size][width]" value="{$settings['base']['navBarTitle_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[nav_max_size][height]" value="{$settings['base']['navBarTitle_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['navBarTitle_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[navBarTitle_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[navBarTitle_size][ios]" value="{$size['ios']}" /></label></p>
{/if}
{if $settings['base']['magazine_size']}
<p><span>杂志首页背景建议尺寸：</span><input type="text" name="base[magazine_max_size][width]" value="{$settings['base']['magazine_size']['max_size']['width']}" style="width: 50px;" /> x <input type="text" name="base[magazine_max_size][height]" value="{$settings['base']['magazine_size']['max_size']['height']}" style="width: 50px;" /> (单位：像素)</p>
{code}
$size = array();
foreach ($settings['base']['magazine_size'] as $k => $v)
{
	$size_val = '';
	foreach ($v as $vv)
	{
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[magazine_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[magazine_size][ios]" value="{$size['ios']}" /></label></p>
{/if}
<!--
{if $settings['base']['image_type']}
<p><span>APP上传图片格式：</span>
{foreach $settings['base']['image_type'] as $k => $v}
<label><input type="checkbox" name="base[pic_type][]" value="{$k}"{if in_array($k, $settings['base']['pic_type'])} checked="checked"{/if} />{$v}</label>
{/foreach}
</p>
{/if}
-->
<!--
{if $settings['template']}
<p><span>默认风格：</span>
{foreach $settings['template'] as $template}
<label class="defStyle"><input type="radio" name="define[DEFAULT_STYLE]" value="{$template['id']}"{if $settings['define']['DEFAULT_STYLE'] == $template['id']} checked="checked"{/if} />{$template['name']}</label>
{/foreach}
</p>
{/if}
-->

<p><span>默认界面：</span>
{if $settings['interface']}
{foreach $settings['interface'] as $interface}
<label class="defStyle"><input type="radio" name="define[DEFAULT_UI]" value="{$interface['id']}"{if $settings['define']['DEFAULT_UI'] == $interface['id']} checked="checked"{/if} />{$interface['name']}</label>
{/foreach}
{/if}
</p>

<p><span>默认正文模板：</span>
{if $settings['body_tpl']}
{foreach $settings['body_tpl'] as $body_tpl}
<label class="defStyle"><input type="radio" name="define[DEFAULT_BODY_TPL]" value="{$body_tpl['id']}"{if $settings['define']['DEFAULT_BODY_TPL'] == $body_tpl['id']} checked="checked"{/if} />{$body_tpl['name']}</label>
{/foreach}
{/if}
</p>

<p><span>APP引导图数量上限：</span><input type="text" name="define[GUIDE_LIMIT]" value="{$settings['define']['GUIDE_LIMIT']}" style="width:50px;" /></p>
<p><span>APP模块中文名称字符限制：</span><input type="text" name="define[MODULE_NAME_LIMIT]" value="{$settings['define']['MODULE_NAME_LIMIT']}" style="width:50px;" /></p>
<p><span>APP模块英文名称字符限制：</span><input type="text" name="define[MODULE_ENGLISH_LIMIT]" value="{$settings['define']['MODULE_ENGLISH_LIMIT']}" style="width:50px;" /></p>
<p><span>APP创建限制：</span><input type="text" name="define[APP_LIMIT_NUM]" value="{$settings['define']['APP_LIMIT_NUM']}" style="width:50px;" /></p>
<p><span>APP模块限制：</span><input type="text" name="define[MODULE_LIMIT_NUM]" value="{$settings['define']['MODULE_LIMIT_NUM']}" style="width:50px;" /></p>
<p><span>图片域名：</span><input type="text" name="define[REPLACE_IMG_DOMAIN]" value="{$settings['define']['REPLACE_IMG_DOMAIN']}" style="width:150px;" /></p>
<p><span>是否替换图片域名：</span>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="0"{if $settings['define']['IS_REPLACE'] == 0} checked="checked"{/if} />否</label>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="1"{if $settings['define']['IS_REPLACE'] == 1} checked="checked"{/if} />是</label>
</p>
<p><span>天气接口：</span><input type="text" name="define[WEATHER_API]" value="{$settings['define']['WEATHER_API']}" style="width:300px;" /></p>
<p><span>统计接口：</span><input type="text" name="define[STATISTICS_API]" value="{$settings['define']['STATISTICS_API']}" style="width:300px;" /></p>
<p><span>会员接口：</span><input type="text" name="define[MEMBER_API]" value="{$settings['define']['MEMBER_API']}" style="width:300px;" /></p>
<p><span>互助接口：</span><input type="text" name="define[SEEKHELP_API]" value="{$settings['define']['SEEKHELP_API']}" style="width:300px;" /></p>
<p><span>二维码地址：</span><input type="text" name="base[qrcode_url]" value="{$settings['base']['qrcode_url']}" style="width:300px;" /></p>

<p><span>模块数据配置：</span>
	<ul>
		<li><label>目录地址：<input type="text" name="base[data_url][path]" value="{$settings['base']['data_url']['path']}" placeholder="目录" size="50" /></label><span id="addBtn" class="btn">+</span></li>
		{if $settings['base']['data_url']['file']}
			{foreach $settings['base']['data_url']['file'] as $mark => $file}
		<li>
			<input type="text" name="base[names][]" value="{$file['name']}" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" value="{$mark}" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" value="{$file['url']}" placeholder="数据地址" size="50" /><span class="btn dropBtn">-</span>
		</li>
			{/foreach}
		{/if}
	</ul>
</p>

<p>
<span>启动图启动方式：</span><span id="addEffect" class="btn">+</span>
<ul id="effectList" class="keyValueLayout">
{if $settings['base']['app_effect']}
{code}$index = 0;{/code}
	{foreach $settings['base']['app_effect'] as $v}
	{code}$index++;{/code}
	<li>
		<label>标识</label><input type="text" name="base[appEffect_identifiers][{$index}]" value="{$v['identifier']}" />
		<label>选项</label><input type="text" name="base[appEffect_options][{$index}]" value="{$v['option']}" />
		<label>值</label><input type="text" class="valueOption" name="base[appEffect_values][{$index}]" value="{$v['value']}" />
		<input type="checkbox" name="base[appEffect_default][{$index}]" value="1"{if $v['default']} checked="checked"{/if} />
		<span class="btn dropEffect">-</span>
	</li>
	{/foreach}
{/if}
</ul>
</p>

<p>
<span>版权文字默认色：</span>
<input type="text" class="color-custom-txt" value="{$settings['base']['cpTextColor']}" /><input class="select-input color-picker" type="text" name="base[cpTextColor]" value="{$settings['base']['cpTextColor']}" data-color="{$settings['base']['cpTextColor']}" />
</p>

<p>
<span>版权文字大小：</span><span id="addTextSizeOption" class="btn">+</span>
<ul id="textSizeList" class="keyValueLayout">
{if $settings['base']['cpTextSize']}
{code}$index = 0;{/code}
	{foreach $settings['base']['cpTextSize'] as $v}
	{code}$index++;{/code}
	<li>
		<label>标识</label><input type="text" name="base[cpTextSize_identifiers][{$index}]" value="{$v['identifier']}" />
		<label>选项</label><input type="text" name="base[cpTextSize_options][{$index}]" value="{$v['option']}" />
		<label>值</label><input type="text" class="valueOption" name="base[cpTextSize_values][{$index}]" value="{$v['value']}" />
		<input type="checkbox" name="base[cpTextSize_default][{$index}]" value="1"{if $v['default']} checked="checked"{/if} />
		<span class="btn dropTextSizeOption">-</span>
	</li>
	{/foreach}
{/if}
</ul>
</p>

<p>
<span>引导图标记默认色：</span>
<input type="text" class="color-custom-txt" value="{$settings['base']['signDefaultColor']}" /><input class="select-input color-picker" type="text" name="base[signDefaultColor]" value="{$settings['base']['signDefaultColor']}" data-color="{$settings['base']['signDefaultColor']}" />
</p>

<p>
<span>引导图标记选中色：</span>
<input type="text" class="color-custom-txt" value="{$settings['base']['signSelectedColor']}" /><input class="select-input color-picker" type="text" name="base[signSelectedColor]" value="{$settings['base']['signSelectedColor']}" data-color="{$settings['base']['signSelectedColor']}" />
</p>

<p>
<span>引导图效果：</span><span id="addGuideEffect" class="btn">+</span>
<ul id="guideEffectList" class="keyValueLayout">
{if $settings['base']['guideEffect']}
{code}$index = 0;{/code}
	{foreach $settings['base']['guideEffect'] as $v}
	{code}$index++;{/code}
	<li>
		<label>标识</label><input type="text" name="base[guideEffect_identifiers][{$index}]" value="{$v['identifier']}" />
		<label>选项</label><input type="text" name="base[guideEffect_options][{$index}]" value="{$v['option']}" />
		<label>值</label><input type="text" class="valueOption" name="base[guideEffect_values][{$index}]" value="{$v['value']}" />
		<input type="checkbox" name="base[guideEffect_default][{$index}]" value="1"{if $v['default']} checked="checked"{/if} />
		<span class="btn dropGuideEffect">-</span>
	</li>
	{/foreach}
{/if}
</ul>
</p>

<p>
<span>引导图动画：</span><span id="addAnimationEffect" class="btn">+</span>
<ul id="guideAnimationList" class="keyValueLayout">
{if $settings['base']['guideAnimation']}
{code}$index = 0;{/code}
	{foreach $settings['base']['guideAnimation'] as $v}
	{code}$index++;{/code}
	<li>
		<label>标识</label><input type="text" name="base[animation_identifiers][{$index}]" value="{$v['identifier']}" />
		<label>选项</label><input type="text" name="base[animation_options][{$index}]" value="{$v['option']}" />
		<label>值</label><input type="text" class="valueOption" name="base[animation_values][{$index}]" value="{$v['value']}" />
		<input type="checkbox" name="base[animation_default][{$index}]" value="1"{if $v['default']} checked="checked"{/if} />
		<span class="btn dropAnimationEffect">-</span>
	</li>
	{/foreach}
{/if}
</ul>
</p>

<p>
<span>引导图页脚标记：</span><span id="addShapeSign" class="btn">+</span>
<ul id="shapeSignList">
{if $settings['base']['shapeSign']}
{code}$index = 0;{/code}
	{foreach $settings['base']['shapeSign'] as $k => $v}
	{code}$index++;{/code}
	<li>
		<input type="text" name="base[shape_signs][{$index}]" value="{$v['sign']}" placeholder="标记符" />
		<input type="checkbox" name="base[sign_default][{$index}]" value="1"{if $v['default']} checked="checked"{/if} />
		<span class="btn dropShapeSign">-</span>
	</li>
	{/foreach}
{/if}
</ul>
</p>
{code}if ($settings['base']['vip_user']) { $vip_user = implode('|', $settings['base']['vip_user']); }{/code}
<p><span>VIP用户：</span><input type="text" name="base[vip_user]" value="{$vip_user}" /></p>
<p><span>打包服务器是否可用：</span><input type="text" name="define[IS_BAG_SERVER_OK]" value="{$settings['define']['IS_BAG_SERVER_OK']}" /></p>
</div>