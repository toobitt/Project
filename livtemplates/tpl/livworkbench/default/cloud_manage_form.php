<?php 
/* $Id: head.tpl.php 1 2011-04-28 06:57:29Z develop_tong $ */
?>
{template:head}
{css:ad_style}
<style>
.form_ul_div.l input{float:left}
.form_ul .n-h{float:none}
.ad_middle table textarea{min-width:0;min-height: 0;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_module first"><em></em><a>操作</a></li>
			<li class=" dq"><em></em><a>{$optext}</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
	<div id="hg_parent_page_menu" class="new_menu">
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle" style="width:850px">
<h2>{$optext}平台</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">名称: </span><input type="text" name="cloud_name" value="{$formdata['cloud_name']}" />
</div>
</li>
{if isset($site_info)}
<li class="i">
<div class="form_ul_div clear">
<span  class="title">所属客户: </span>{template:form/select,site_id,$formdata['site_id'],$site_info}
</div>
</li>
{/if}
<li class="i">
<div class="form_ul_div clear">
<span  class="title">关联模块: </span>{template:form/select,module_id,$formdata['module_id'],$modules}
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">远程主机：</span><input type="text" name="remote_host" value="{$formdata['remote_host']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">远程路径：</span><input type="text" name="remote_dir" value="{$formdata['remote_dir']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">输出文件：</span><input type="text" name="remote_file" value="{$formdata['remote_file']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">操作文件：</span><input type="text" name="remote_update_file" value="{$formdata['remote_update_file']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">节点文件：</span><input type="text" name="remote_node_file" value="{$formdata['remote_node_file']}" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">APPID：</span><input type="text" name="appid" value="{$formdata['appid']}" /> /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权key：</span><input type="text" name="appkey" value="{$formdata['appkey']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">用户名：</span><input type="text" name="username" value="{$formdata['username']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">密码：</span><input type="text" name="pwd" value="{$formdata['pwd']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">本地用户名：</span><input type="text" name="localusername" value="{$formdata['localusername']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">本地密码：</span><input type="text" name="localuserpwd" value="{$formdata['localuserpwd']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权API：</span><input type="text" name="authapi" value="{$formdata['authapi']}" />  /**默认继承客户信息的值**/
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span  class="title">是否关闭: </span>{template:form/radio,is_close,$formdata['is_close'],$option}
</div>
</li>

</ul>
<input type="hidden" name="a" value="{$a}" />
{if $site_id}
<input type="hidden" name="site_id" value="{$site_id}" />
{/if}
<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="pp" value="{$_INPUT['pp']}" />
<input type="hidden" name="goon" value="1" />
<br>
<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
{template:foot}