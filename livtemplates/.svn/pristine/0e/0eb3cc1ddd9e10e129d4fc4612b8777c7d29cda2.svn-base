<style type="text/css">
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
</style>
<script type="text/javascript">
$(function() {
	/*
	$('input[type="checkbox"]').click(function() {
		if ($(this).attr('checked')) {
			$(this).val('1');
		} else {
			$(this).val('0');
		}
	});
	*/
	$('#addBtn').click(function() {
		var con = '<li><input type="text" name="base[names][]" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" placeholder="数据地址" size="50" /><span id="dropBtn" class="btn">-</span></li>';
		$(this).parent().parent().append(con);
	});
	$('#dropBtn').live('click', function() {
		$(this).parent().remove();
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
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[icon_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[icon_size][ios]" value="{$size['ios']}" /></label></p>
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
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
	}
	$size[$k] = trim($size_val, '|');
}
{/code}
<p class="terminal_size"><label>Android：<input type="text" name="base[startup_size][android]" value="{$size['android']}" /></label><label>iOS：<input type="text" name="base[startup_size][ios]" value="{$size['ios']}" /></label></p>
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
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
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
		$size_val .= $vv['width'] . ',' . $vv['height'] . '|';
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
{if isset($settings['define']['USE_EFFECT'])}
<p><span>是否启用过渡效果：</span><input type="checkbox" name="define[USE_EFFECT]" value="1"{if $settings['define']['USE_EFFECT'] == 1} checked="checked"{/if} /></p>
{/if}
{if $settings['base']['image_type']}
<p><span>APP上传图片格式：</span>
{foreach $settings['base']['image_type'] as $k => $v}
<label><input type="checkbox" name="base[pic_type][]" value="{$k}"{if in_array($k, $settings['base']['pic_type'])} checked="checked"{/if} />{$v}</label>
{/foreach}
</p>
{/if}
-->
{code}
//hg_pre($settings['template']);
{/code}
{if $settings['template']}
<p><span>默认风格：</span>
{foreach $settings['template'] as $template}
<label class="defStyle"><input type="radio" name="define[DEFAULT_STYLE]" value="{$template['id']}"{if $settings['define']['DEFAULT_STYLE'] == $template['id']} checked="checked"{/if} />{$template['name']}</label>
{/foreach}
</p>
{/if}
<p><span>APP引导图数量上限：</span><input type="text" name="define[GUIDE_LIMIT]" value="{$settings['define']['GUIDE_LIMIT']}" style="width:50px;" /></p>
<p><span>APP模块中文名称字符限制：</span><input type="text" name="define[MODULE_NAME_LIMIT]" value="{$settings['define']['MODULE_NAME_LIMIT']}" style="width:50px;" /></p>
<p><span>APP模块英文名称字符限制：</span><input type="text" name="define[MODULE_ENGLISH_LIMIT]" value="{$settings['define']['MODULE_ENGLISH_LIMIT']}" style="width:50px;" /></p>
<p><span>APP创建限制：</span><input type="text" name="define[APP_LIMIT_NUM]" value="{$settings['define']['APP_LIMIT_NUM']}" style="width:50px;" /></p>
<p><span>图片域名：</span><input type="text" name="define[REPLACE_IMG_DOMAIN]" value="{$settings['define']['REPLACE_IMG_DOMAIN']}" style="width:150px;" /></p>
<p><span>是否替换图片域名：</span>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="0"{if $settings['define']['IS_REPLACE'] == 0} checked="checked"{/if} />否</label>
<label class="defStyle"><input type="radio" name="define[IS_REPLACE]" value="1"{if $settings['define']['IS_REPLACE'] == 1} checked="checked"{/if} />是</label>
</p>
<p><span>天气接口：</span><input type="text" name="define[WEATHER_API]" value="{$settings['define']['WEATHER_API']}" style="width:300px;" /></p>
{if $settings['base']['data_url']}
<p><span>模块数据配置：</span>
	<ul>
		{if $settings['base']['data_url']['path']}
		<li><label>目录地址：<input type="text" name="base[data_url][path]" value="{$settings['base']['data_url']['path']}" placeholder="目录" size="50" /></label><span id="addBtn" class="btn">+</span></li>
		{/if}
		{if $settings['base']['data_url']['file']}
			{foreach $settings['base']['data_url']['file'] as $mark => $file}
		<li>
			<input type="text" name="base[names][]" value="{$file['name']}" placeholder="数据名" style="width:80px;" /> : <input type="text" name="base[marks][]" value="{$mark}" placeholder="数据标识" style="width:80px;" /> : <input type="text" name="base[urls][]" value="{$file['url']}" placeholder="数据地址" size="50" /><span id="dropBtn" class="btn">-</span>
		</li>
			{/foreach}
		{/if}
	</ul>
</p>
{/if}
</div>