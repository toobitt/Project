{template:head}
{code}
$all_animations = $all_animations[0];
$count = count($formdata['para']);
$i=1;
{/code}
{css:ad_style}
{js:ad}
<style type="text/css">
.ad_form .form_ul .form_ul_div span.title{width:75px;margin-right: 0px;}
#adpos_para li{line-height: 36px;}
</style>

<script type="text/javascript">
function hg_getAnimationJs(jsname)
{
	if($('#edit_js').html())
	{
		hg_show_js();
		return;
	}
	hg_request_to('?mid={$_INPUT["mid"]}&a=get_animation_js&jsname='+jsname);
}
function hg_edit_js(html)
{
	$('#edit_js').html(html);
	hg_show_js();
}
function hg_hidden_js()
{
	$('#edit_js').fadeOut('slow');
}
function hg_show_js()
{
	$('#edit_js').fadeIn('slow');
}
function hg_close_editjs()
{
	$('#edit_js').fadeOut('slow');
}
function hg_tab_js(n)
{
	switch(parseInt(n))
	{
		case 1:
		{
			$('#tab_select_js').children('li:first').show();
			$('#tab_select_js').children('li:last').hide();
			break;
		}
		case 2:
		{
			
			$('#tab_select_js').children('li:last').show();
			$('#tab_select_js').children('li:first').hide();
			break;
		}
		default:
		{
			$('#tab_select_js').children('li:first').show();
			$('#tab_select_js').children('li:last').hide();
		}
	}
}
function hg_reset_textarea()
{
    $('textarea').each(function(){

        var textareavalue = $(this).val();

        if(textareavalue == '这里输入描述')
        {
            $(this).val("");
        }
    })
    return true;
}
</script>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form action="" method="post" enctype="multipart/form-data"  class="ad_form h_l" onsubmit="return hg_reset_textarea()">
<h2>新增效果</h2>
<ul id="form_ul" class="form_ul">
<li class="i">
<div class="form_ul_div">
<span class="title">名称：</span><input type="text" value='{$formdata["name"]}' name='name'>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">描述：</span>{template:form/textarea,brief,$formdata['brief']}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">浮动：</span><input type="radio" {if $formdata['float_fixed']==1}checked="checked"{/if} value='1' name='show_format' style="float:left">
<span class="title">固定：</span><input type="radio" {if $formdata['float_fixed']==2}checked="checked"{/if} value='2' name='show_format'>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">附加JS：</span>
	<span onclick="hg_tab_js(1)">本地上传</span><span style="margin:0px 5px;" onclick="hg_tab_js(2)">JS库选择</span>
	<ul id="tab_select_js">
	<li>
	<input type="file" name="loadjs" style="float:left"/>
	</li>
	<li style="display:none">
	{code}
			$loadjs_style = array(
			'class' => 'down_list i',
			'show' => 'loadjs',
			'width' => 160,	
			'state' => 0, 
			'is_sub'=>1,
			);
			$default = $formdata['include_js'] ? $formdata['include_js'] : 0;
			$all_animations[0]='无附加JS';
	{/code}
	{template:form/search_source,lib_loadjs,$default,$all_animations,$loadjs_style}
	</li>
	<a href="###" style="color:blue;margin-left:20px;" title="快速编辑" onclick="hg_getAnimationJs('{$formdata['include_js']}')">{if $formdata['include_js']}{$formdata['include_js']}{/if}</a>
	<font class="important">本地上传或者JS库选择</font>
</div>
</li>
<div id="all_ani_para">
<li class="i">
<div class="form_ul_div">
<span class="title">自定义参数：</span>
<div style="margin-left:75px;">
		<ul id="adpos_para">
			{template:unit/dynamic_para, p,p,$formdata['para']}
		</ul>
	</div>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">Html模板：</span>{template:form/textarea, tpl, $formdata['tpl']}
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">Js参数：</span>{template:form/textarea, js_para, $formdata['js_para']}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">启用：</span><label><input type="checkbox" class="n-h" name="is_use" {if $formdata['is_use']}checked="checked"{/if} value="1"></label>
</div>
</li>
</ul>
<input type="hidden" name="a" value="{$a}"/>
<input type="hidden" name="html" value="yes"/>
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version">
	<h2><a href="{$_INPUT['referto']}">返回前一页</a></h2>
</div>
</div>
<div style="display:none;position:absolute;top:70px;left:400px;border:1px" id="edit_js"></div>
<div id='for_form_style_clone' style="display:none">
{template:form/select, form_style[], $selected_style,$_configs['form_style']}
</div>
{template:foot}