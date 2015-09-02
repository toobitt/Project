<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
{code}
$css_attr['style'] = 'style="width:100px"';
$formdata = $mobile_api_settings[0];
{/code}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
</style>

<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>移动终端API服务器配置</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">接口协议: </span>{template:form/select,protocol,$formdata['protocol'],$_configs['api_protocol'], $css_attr}<font class="important"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">URL路径：</span><input type="text" name="host" value="{$formdata['host']}" />/<input type="text" name="directory" value="{$formdata['directory']}" /><font class="important">例如：localhost/public/api 127.0.0.1/public/api</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">服务端口: </span><input type="text" name="port" size="50" value="{$formdata['port']}" /><font class="important">默认80端口</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">数据格式: </span>{template:form/select,data_format,$formdata['data_format'],$_configs['data_format'], $css_attr}<font class="important">接口返回的数据格式</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">备注说明: </span><textarea name="ps" cols="60" rows="5">{$formdata['ps']}</textarea>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">用户名: </span><input type="text" name="uname" value="{$formdata['uname']}" style="width:200px;"/><span style="margin-left:10px;">密码: </span><input type="text" name="pwd" value="{$formdata['pwd']}" /><font class="important">可选，如果不需要留空即可</font>
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">Token令牌: </span><input type="text" name="token" size="50" value="{$formdata['token']}" /><font class="important">可选，如果不需要留空即可</font>
</div>
</li>

<li class="i">
<div class="form_ul_div clear">
<span  class="title">启用: </span><input type="checkbox" name="status" size="4" value="1" {if $formdata['status']}checked="checked"{/if}/>
</div>
</li>
</ul>
<input type="hidden" name="a" value="update" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="提交设置" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="javascript:void(0);" onclick="javascript:history.go(-1);">返回前一页</a></h2></div>
</div>
{template:foot}