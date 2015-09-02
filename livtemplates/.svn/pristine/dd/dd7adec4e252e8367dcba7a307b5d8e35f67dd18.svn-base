<?php 
/* $Id: channel_list.php 7694 2012-01-18 02:52:44Z gengll $ */
?>
{template:head}
{css:ad_style}
{js:message_set_form}
{code}
//hg_pre($verifyType);
//验证码配置
$verifyType = $verifyType[0];
$set = $formdata;
//hg_pre($set);
if(!$set['value'])
{
	$set = $_configs['message_form_set'];
	$display = $set['display'];
	$rule = $set['rule'];
}
else
{
	$form = $set['value'];
	$display = $form['display'];
	unset($form['display']);
	$rule = $form['rule'];
	unset($form['rule']);
}
if($set['id'])
{
	$a = 'update';
	$optext="更新";
}
else
{
	$a = 'create';
	$optext="添加";
}
{/code}
<script type="text/javascript">

	function verify_check(type)
	{
		if(type == 1)
		{
			$(".verify_type").show();
		}
		else if(type == 2)
		{
			$(".verify_type").hide();
		}
	}
</script>
<style>
.verify_type{display:none}
</style>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="" method="post" enctype="multipart/form-data"  id="content_form">
{if $a == 'update'}
	<h2>更新{$set['name']}评论配置</h2>
{/if}
{if $a == 'create'}
<h2>添加应用评论配置</h2>
<fieldset><legend>应用模块信息设置</legend>
<ul class="form_ul">
	<li class="i">
		<div class="form_ul_div clear">
			<span class="title">应用名称：</span>
			{code}
				$attr_app = array(
					'class' => 'transcoding down_list',
					'show'  => 'select_ap',
					'width' => 180,/*列表宽度*/
					'state' => 0,/*0--正常数据选择列表，1--日期选择*/
					'onclick' => 'change_module();'
				);
				$apps = $apps[0];
				$apps['-1'] = "-请选择-";
				
				$bundle = $set['bundle_id'];
				$set['bundle_id'] = $set['bundle_id'] ? $set['bundle_id'] : -1;
			{/code}
			
			{template:form/search_source,app_id,$set['bundle_id'],$apps,$attr_app}
			<span class="second-title" style="padding-left:5px;">模块名称：</span>
			<div id='app' style="display:inline-block;width:120px;margin-top:3px">
				{if $set['mod_name']}
				<select name='mod_id'><option value="{$set['module_id']}">{$set['mod_name']}</option></select>
				{else}
				<select name='mod_id'><option value='0'>-请选择-</option></select>
				{/if}
			</div>
		</div>
	</li>
</ul>
</fieldset>
<br />
{/if}
{if $set_type != 'db'}
<fieldset><legend>评论显示设置</legend>
<ul class="form_ul">
{foreach $display as $k => $v}
{if $v['names'] == "display_order"}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]'  onclick="check('{$v[names]}',this);" {if $v["def_val"]=='1'}checked{/if}>按时间逆序
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]'  onclick="check('{$v[names]}',this);"  {if $v["def_val"]==='0'}checked{/if}>按时间顺序
	</div>
</li>
{else}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]=='1'}checked{else}{/if}>开启
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]==='0'}checked{else}{/if}>关闭
	</div>
</li>
{/if}
{/foreach}
</ul>
</fieldset>
<br />
<fieldset color="grey"><legend>评论规则设置</legend>
<ul class="form_ul">
{foreach $rule as $k => $v}
{if $v['names'] == 'is_login'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]=='1'}checked{else}{/if}>登录
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]==='0'}checked{else}{/if}>匿名
	</div>
</li>
{else if $v['names'] == 'reply_way'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span><input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]==='0'}checked{/if}>单次
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]=='1'}checked{/if}>多次
	</div>
</li>
{else if $v['names'] == 'max_word'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" style="width: 10%" value='{$v["def_val"]}' name='st[{$v["names"]}]'>字
		<font class="important">不填默认限制300字</font>
	</div>
</li>
{else if $v['names'] == 'rate'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" style="width: 10%" value='{$v["def_val"]}' name='st[{$v["names"]}]'>秒钟<font class="important">不填默认不限制</font>
	</div>
</li>
{else if $v['names'] == 'same_user_same_record'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" style="width: 10%" value='{$v["def_val"]}' name='st[{$v["names"]}]'>次数<font class="important">相同用户相同记录有效次数,不填或者0默认不限制</font>
	</div>
</li>
{else if $v['names'] == 'colation'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="check_colation(1);" {if $v["def_val"]}checked{else}{/if}>开启
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="check_colation(2);" {if !$v["def_val"]}checked{else}{/if}>关闭
		&nbsp;&nbsp;&nbsp;
		<span {if !$v['def_val']}style="display:none"{/if} id="message_colation">
		<span>处理方式: </span>
		<select name="colation_type">
		{foreach $_configs['message_colation'] as $key=>$val}
			<option value="{$key}" {if $v['def_val'] == $key}selected="selected"{/if}>{$val}</option>
		{/foreach}
		</select>
		</span>
		<font class="important">关闭默认不处理</font>
	</div>
</li>
{else if $v['names'] == 'verify_mode'}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="verify_check(1);" {if $v["def_val"]=='1'}checked{else}{/if}>开启
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="verify_check(2);" {if $v["def_val"] === '0'}checked{else}{/if}>关闭
	</div>
	<div class="verify_type" style="display:{if $verifyType && $v['def_val']}block{/if}">
               {foreach $verifyType as $kk=>$vv}
                	<div class="form-dioption-fabu form-dioption-item">
                  		<input type="radio"  {if $v['verify_type'] == $vv['id'] }checked{/if} name="verify_type" value="{$vv['id']}">
                  		<input type="hidden" name="is_dipartite" value="{$vv['is_dipartite']}">
                  		<a class="" >{$vv['name']}</a>
                	</div>
                	<img src="run.php?a=get_verify_code&type={$vv['id']}"/>
               {/foreach}
   </div>
</li>
{else}
<li class="i">
	<div class="form_ul_div clear">
		<span class="title">{$v['title']}：</span>
		<input type="{$v['type']}" value='{$v["value1"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"]=='1'}checked{else}{/if}>开启
		<input type="{$v['type']}" value='{$v["value2"]}' name='st[{$v["names"]}]' onclick="check('{$v[names]}',this);" {if $v["def_val"] === '0'}checked{else}{/if}>关闭
	</div>
</li>
{/if}
{/foreach}
</ul>
</fieldset>
{else}
<h2>数据库配置</h2>
	<ul class="form_ul">
		{if  $form}
			{foreach $form['value'] AS $k => $v}
				<li class="i">
					<div class="form_ul_div clear">
						<span class="title">{$k}：</span>
						<input type="text" value="{$v}" name='st[{$k}]' class="title" />
					</div>
				</li>
			{/foreach}
		{/if}
	</ul>
{/if}
<input type="hidden" name="a" value="{$a}" />
{if $a == 'update'}
<input type="hidden" name="var_name" value="{$set['var_name']}" />
{/if}
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}配置" class="button_6_14"/>
<input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<div class="right_version">
	<h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}