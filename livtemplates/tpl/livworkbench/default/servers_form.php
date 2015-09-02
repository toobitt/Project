<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{code}
	$formdata['type'] = $formdata['type'] ? $formdata['type'] : $_INPUT['type'];
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
.option_del_box{display:inline-block;width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 180px;top: 4px;}
.option_del{display:none;width:16px;height:16px;cursor:pointer;float:right;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
.option_del_b{width:16px;height:16px;cursor:pointer;float:right;position: relative;right: 160px;top: 4px;background: url('{$RESOURCE_URL}close_plan.png') 0 0 no-repeat;}
</style>
<script type="text/javascript">
	function hg_disabled_pri()
	{
		var type = $('#type').val();
		if(type == 5)
		{
			$('#user_name').show();
			$('#password').show();
			$('#token').hide();
			$("input[name='port']").val('3306');
		}
		else
		{
			$('#user_name').hide();
			$('#password').hide();
			$('#token').show();
			$("input[name='port']").val('80');
		}
	}
	function hg_addCollocateDom()
	{
		var div = "<div class='form_ul_div clear'><span class='title'>中文名称: </span><input type='text' name='zh_name[]' style='width:90px;' class='title'>&nbsp;&nbsp;英文名称: <input type='text' name='en_name[]' style='width:90px;' class='title'>&nbsp;值: <input type='text' name='value[]' size='40'/><input type='hidden' name='eid[]' value='0' /><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this,0);' style='display: inline; '></span></span></div>";
		$('#extend').append(div);
	}
	function hg_optionTitleDel(obj,id)
	{
		
		if(id)
		{
			if(confirm('确定删除该配置吗？'))
			{
				var url = '?a=del_extend&eid=' + id;
				hg_ajax_post(url);
				$(obj).parent().parent().remove();
			}
		}
		else
		{
			$(obj).parent().parent().remove();
		}
	}
	function hg_appendsitename(obj)
	{	
		var ss = $(obj).val();
		var site_name = $("input[name='site_name']").val();
		if(!site_name)
		{
			$("input[name='site_name']").val(ss);
		}
	}
</script>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}服务器</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">服务器类型: </span>
{code}

							$item_source = array(
								'class' => 'down_list i',
								'show' => 'item_shows_',
								'width' => 100,/*列表宽度*/		
								'state' => 0, /*0--正常数据选择列表，1--日期选择*/
								'is_sub'=>1,
								'onclick'=>'hg_disabled_pri();hg_stream_select();',
							);
							$default = $formdata['type'] ? $formdata['type'] : -1;
							$type[-1] = '--请选择--';
							
						{/code}
						{template:form/search_source,type,$default,$type,$item_source}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">服务器名称: </span><input type="text" name="name" value="{$formdata['name']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">服务器标识: </span><input type="text" name="ident" onchange="hg_appendsitename(this);" value="{$formdata['ident']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">描述: </span><textarea name="brief" cols="60" rows="5">{$formdata['brief']}</textarea>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">内网ip: </span><input type="text" name="n_ip" size="50" value="{$formdata['n_ip']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">外网ip: </span><input type="text" name="o_ip" size="50" value="{$formdata['o_ip']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">域名: </span><input type="text" name="site_name" size="50" value="{$formdata['site_name']}" />
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">端口: </span><input type="text" name="port" size="4" value="{$formdata['port']}" />
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">访问协议: </span><input type="text" name="access_deal" size="50" value="{$formdata['access_deal']}" />
</div>
</li>
<li class="i" id="user_name" style="display:{if $formdata['type']==5}block{else}none{/if};">
<div class="form_ul_div clear">
<span  class="title">用户名: </span><input type="text"  name="user_name" size="50" value="{$formdata['user_name']}" /><font class="important">数据库服务器配置</font>
</div>
</li>
<li class="i" id="password" style="display:{if $formdata['type']==5}block{else}none{/if};">
<div class="form_ul_div clear">
<span  class="title">密码: </span><input type="text"  name="password" size="50" value="{$formdata['password']}" /><font class="important">数据库服务器配置</font>
</div>
</li>
<li class="i" id="token" style="display:{if $formdata['type']==5}none{else}block{/if};">
<div class="form_ul_div clear">
<span  class="title">token: </span><input type="text" name="token" size="50" value="{$formdata['token']}" />
</div>
</li>


<!-- <li class="i">
<div class="form_ul_div clear">
<span  class="title">链接状态: </span>{template:form/radio,link_state,$formdata['link_state'],$option}
</div>
</li>-->
<li class="i">
	
	{if($formdata['extend'])}
	
	{foreach $formdata['extend'] as $k=>$v}
	<div class='form_ul_div clear'><span class='title'>中文名称: </span><input type='text' name='zh_name[]' value='{$v["zh_name"]}' style='width:90px;' class='title'>&nbsp;&nbsp;英文名称: <input type='text' name='en_name[]' value='{$v["en_name"]}' style='width:90px;' class='title'>&nbsp;值: <input type='text' name='value[]' value='{$v["value"]}' size='40'/><input type="hidden" name="eid[]" value="{$v['eid']}" /><span class='option_del_box'><span name='option_del[]' class='option_del' title='删除' onclick='hg_optionTitleDel(this,"{$v["eid"]}");' style='display: inline; '></span></span></div>
	{/foreach}
	{/if}
	<div id="extend">
	</div>
	<div class="form_ul_div clear">
		<span type="text" style="cursor:pointer;padding: 5px 20px;margin-left: 75px;background-color: #5B5B5B;color: white;border-radius: 2px;" onclick="hg_addCollocateDom();">添加配置</span>
	</div>
	
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否启用: </span><input type="checkbox" name="state" size="4" value="1" {if $formdata['state']}checked="checked"{/if}/><font class="important">服务器启用</font>
</div>
</li>

</ul>
<input type="hidden" name="a" value="{$a}" />
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}