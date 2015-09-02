<?php 
/* $Id: list.php 20403 2013-05-04 09:44:45Z wangleyuan $ */
?>
{code}
if(!$_INPUT['article_status'])
{
	$_INPUT['article_status']=1;
}
if(!$_INPUT['date_search'])
{
	$_INPUT['date_search'] = 1;
}
$list=$user_list;
{/code}

{template:head}
{template:list/common_list}
<script type="text/javascript">
	function hg_check_auth()
	{
		if($("#auth-info").css("display") == 'none')
		{
			$("#auth-info").show();
			$.get("./run.php?mid=" + gMid + "&a=get_plat&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,{key:''},
					function (data)	{
					$("#auth-info").html(data);
			 });	
		}
		else
		{
			var str='<div id="auth-loading"></div>';
			$("#auth-info").html(str);
			$("#auth-info").hide();	
		}
	}
</script>
<style sytle="text/html">
#auth-info{position:absolute;right:0px;top:0px;border:1px solid #DDDDDD;border-top:none;background:#EFEFEF;width:400px;min-height:200px;float:left;z-index:4;display:none;padding:10px 10px;}
#auth-info li{margin-bottom:10px;}
#auth-loading{background:url("{$RESOURCE_URL}loading.gif") left no-repeat;width:50px;height:50px;}
</style>
<div id="auth-info">
	<div id="auth-loading"></div>
</div>
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="add-button news mr10"  target="nodeFrame" id="auth-check" onclick="hg_check_auth();">添加用户</a>
	</div>
</div>
<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$user_list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
{else}
	<form method="post" action="" name="listform" class="common-list-form">
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list news-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
	                <div class="common-list-item paixu open-close">
                    </div>
                </div>
				<div class="common-list-right">
					<div class="common-list-item news-ren open-close wd100">平台名称</div>
					<div class="common-list-item news-ren open-close" style="width:200px;">授权过期时间</div>
                    <div class="common-list-item news-ren open-close wd150">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">昵称</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="news-list common-list public-list hg_sortable_list" id="newslist" data-table_name="article" data-order_name="order_id">
		{foreach $user_list as $k => $v}
			{template:unit/userlist}
		{/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>   	
	</form>	
{/if}
</div>    
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
<script type="text/javascript">
function hg_request_auth(platid,uid,token)
{
	$.get("./run.php?mid="+gMid+"&a=request_auth&platid="+platid+"&uid="+uid+"&token="+token+"&admin_id=" + gAdmin.admin_id +"&admin_pass="+gAdmin.admin_pass,{key:''},
			function(data) {
			var obj = eval('('+data+')');
			var url = obj[0].url;
			window.open(url);
	});
}	
</script>
{template:unit/record_edit}
{template:foot}     				