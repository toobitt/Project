{template:head}
{css:ad_style}
{css:share_list}
{js:share}
{js:jquery-ui-1.8.16.custom.min}
<script type="text/javascript">
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px;margin-bottom:20px;">
{if $message}
<div class="error">{$message}</div>
{/if}
{code}
//hg_pre($formdata['id']);
{/code}
<form name="editform" id='editform' action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data"  class="ad_form h_l" onsubmit="return hg_ajax_submit('editform')">
<h2>{if $formdata['id']}编辑{else}新增{/if}分享</h2>
<ul class="form_ul">
<li class="i" style="display:none;">
<div class="form_ul_div clear">
</div>
</li>
		{code}
				foreach($_configs['share_plat'][127]['para'] as $v)
				{
					$str_param .= ','.$v['param'];
					$str_name .= ','.$v['name'];
				}
				$str_param = trim($str_param,',');
				$str_name = trim($str_name,',');
				$attr_share_sortname = array(
					'class' => 'down_list',
					'show' => 'tuji_sort_show',
					'width' => 100,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'is_sub' => 1
				);
				
				$tuji_sort_default = 0;
				$share_arr[$tuji_sort_default] = '平台类别';
				if(!$formdata['share_sort_id'])
				{
					$formdata['share_sort_id'] = $tuji_sort_default;
				}
				
				foreach($_configs['share_plat'] AS $k => $v)
				{
					$share_arr[$k] = $v['name_ch'];
				}

				{/code}
<input type="text" name="jsontext" id="jsontext" value="{$json}"  style="display:none"/>
<li class="i">
<div class="form_ul_div clear">
<span class="title">分类: </span>
	<select name="plat_type" id='plat_type' onchange="show_typename()" style="height:20px" {if $formdata['id']}disabled{/if}>
		{foreach $share_arr AS $k => $v}
		<option value="{$k}" {if $formdata['type']==$k}selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span class="title">状态: </span>
	<select  name="status" id='status' style="height:20px" >
		{foreach $_configs['status'] AS $k => $v}
		<option value="{$k}" {if $formdata['status']==$k}selected{/if}>{$v}</option>
		{/foreach}
	</select>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
	<span class="title">图片：</span>
	{if $formdata['picurl']}<img src="{$formdata['picurl']}"   width="40" height="30" />{/if}
	<input name="pic_files" type="file"   id="pic_files"  >
	
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
	<span class="title">登陆图片：</span>
	{if $formdata['pic_login']}<img src="{$formdata['pic_login']}"   width="40" height="30" />{/if}
	<input name="pic_login" type="file"   id="pic_login"  >
	
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
	<span class="title">分享图片：</span>
	{if $formdata['pic_share']}<img src="{$formdata['pic_share']}"   width="40" height="30" />{/if}
	<input name="pic_share" type="file"   id="pic_share"  >
	
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">账号名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">官方账号: </span><input type="text" name="offiaccount"  value="{$formdata['offiaccount']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">apikey: </span><input type="text" name="apikey" id="apikey" size="50" value="{$formdata['akey']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">密钥: </span><input type="text" name="secretkey" size="50" value="{$formdata['skey']}" />
</div>
</li>
<li class="i" id="add_sign">
<div class="form_ul_div clear" >
<span  class="title">返回地址: </span><input type="text" name="callback" id="callback" size="50" value="{$formdata['callback']}" />
</div>
</li>
{if $formdata['type']==127}
{foreach $_configs['share_plat'][127]['para'] as $v}
<li class="i" name="platdata">
<div class="form_ul_div clear">
<span  class="title">{$v['name']}: </span><input type="text" name="{$v['param']}" id="{$v['param']}" size="50" value="{if $formdata['platdata'][$v['param']]}{$formdata['platdata'][$v['param']]}{/if}" />
</div>
</li>
{/foreach}
{/if}
</ul>
<input type="hidden" id="platpara_param" value="{$str_param}"/>
<input type="hidden" id="platpara_name" value="{$str_name}"/>

<input type="hidden" name="a" value="{$a}" />
<input type="hidden" id="{$primary_key}"  name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
<br>
<input type="button" value="{if $formdata['name']}更新{else}添加{/if}"  style="margin-left:75px;"  class="button_6" id="direct_create" onclick="hg_direct_create_tuji('editform');" />
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}