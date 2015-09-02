{template:head}
{css:ad_style}
<script type="text/javascript">
	function hg_add_append()
	{
		$('.form_ul').append($('#clone_tpl').html());
	}
	function hg_del_append(obj)
	{
		$(obj).parents('li').remove();
	}
</script>
<style type="text/css">
	.form_ul_div span{margin-right:5px;margin-top:7px;}
	.form_ul_div input{margin-right:20px;margin-top:7px;}
</style>
<div class="heard_menu">
	<div class="clear top_omenu" id="_nav_menu">
		<ul class="menu_part">
			<li class="menu_part_first"></li>
			<li class="nav_set first"><em></em><a>操作管理数据</a></li>
			<li class=" dq"><em></em><a>{$op}操作</a></li>
			<li class="last"><span></span></li>
		</ul>
	</div>
</div>
<div class="wrap clear">
<div class="ad_middle">
<h2>{$op}操作-关联数据</h2>
{if $message}
<div class="error">{$message}</div>
{/if}
<form name="editform" action="" method="post" class="ad_form h_l">
<ul class="form_ul">
{if $formdata}
{foreach $formdata as $k=>$v}
<li class="i">
<div class="form_ul_div">
<span>主机:</span><input type="text" value="{$v['host']}" name="host[{$v['id']}]">
<span>目录:</span><input type="text" value="{$v['dir']}" name="dir[{$v['id']}]">
<span>文件:</span><input type="text" value="{$v['file_name']}" name="file_name[{$v['id']}]">
<br />
<span>方法:</span><input type="text" value="{$v['func_name']}" name="func_name[{$v['id']}]">
<span>参数:</span><input type="text" value="{$v['paras']}" name="paras[{$v['id']}]">
<span>类型:</span><input type="text" value="{$v['return_type']}" name="return_type[{$v['id']}]">
<br />
<span>变量:</span><input type="text" value="{$v['return_var']}" name="return_var[{$v['id']}]">
<span>记录:</span><input type="text" value="{$v['count']}" name="count[{$v['id']}]"><a href="###" onclick="hg_del_append(this)" style="color:red">删除</a>
</div>
</li>
{/foreach}
{/if}
</ul>
<a href="###" onclick="hg_add_append()" style="float:right">继续添加</a>
<input type="hidden" name="a" value="doappend" />
<input type="hidden" name="op" value="{$op}" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<br>
<input type="submit" name="sub" value="编辑完成" class="button_6_14" style="margin-left:30px;"/>
</form>
</div>
<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
</div>
<ul style="display:none" id="clone_tpl">
	<li class="i">
	<div class="form_ul_div">
	<span>主机:</span><input type="text"  name="add_host[]">
	<span>目录:</span><input type="text"  name="add_dir[]">
	<span>文件:</span><input type="text"  name="add_file_name[]">
	<br />
	<span>方法:</span><input type="text"  name="add_func_name[]">
	<span>参数:</span><input type="text"  name="add_paras[]">
	<span>类型:</span><input type="text"  name="add_return_type[]">
	<br />
	<span>变量:</span><input type="text"  name="add_return_var[]">
	<span>记录:</span><input type="text"  name="add_count[]"><a href="###" onclick="hg_del_append(this)" style="color:red">删除</a>
	</div>
	</li>
</ul>
{template:foot}