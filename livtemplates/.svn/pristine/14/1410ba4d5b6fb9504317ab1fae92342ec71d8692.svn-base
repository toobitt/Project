<?php 
/* 
$Id:payments_list.php 17960 2013-03-21 14:28:00 jeffrey $ 
*/
?>
{template:head}
{css:vod_style}
<style type="text/css">
.list_top {
width:100%;
height:40px;
line-height:40px;
border-bottom:1px solid #CCC;
}
.list_top li {
float:left;
}
.list_cen {
width:100%;
}
.list_cen ul {
height:60px;
padding:10px 0px;
border-bottom:1px solid #CCC;
}
.list_cen li {
height:60px;
line-height:60px;
float:left;
overflow:hidden;
}
</style>
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']}style="display:none"{/if}>
	<!--这里是导航右侧 新增操作的地方-->
	<a class="blue mr10"  href="?mid={$_INPUT['mid']}&a=form{$_ext_link}">
		<span class="left"></span>
		<span class="middle"><em class="add">新增支付方式</em></span>
		<span class="right"></span>
	</a>
</div>
<div class="content clear">
	<div class="f">
          <div class="right v_list_show">
			<div class="list_first clear">
				<div class="list_top">
					<ul>
						<li style="width: 30px;">&nbsp;</li>
						<li style="width: 150px;">LOGO</li>
						<li style="width: 150px;">支付名称</li>
						<li style="width: 350px;">支付描述</li>
						<li style="width: 20px;">&nbsp;</li>
						<li style="width: 80px;">标识</li>
						<li style="width: 100px;">是否启用</li>
						<li style="width: 150px;">操作</li>
					</ul>   
				</div>
				<div class="list_cen">
					{if is_array($list) &&!empty($list) && count($list)>0}
					{foreach $list as $k => $v}
					<ul>
						<li style="width: 30px;">&nbsp;</li>
						<li style="width: 150px;"><img src="{$v['logo']}" width="100" height="40" ></li>
						<li style="width: 150px;"><span class="m2o-common-title">{$v['pname']}</span></li>
						<li style="width: 350px;line-height:20px;">{$v['miaoshu']}...</li>
						<li style="width: 20px;">&nbsp;</li>
						<li style="width: 80px;">{$v['code']}</li>
						<li style="width: 100px;"><input type="checkbox" {if $v['is_on']==1} checked {/if} /></li>
						<li style="width: 150px;"><a href="./run.php?mid={$_INPUT['mid']}&a=form{$_ext_link}&id={$v['id']}" style="curse:pointer;" title="编辑" class="fb">编辑</a><a>&nbsp;&nbsp;</a><a href="./run.php?mid={$_INPUT['mid']}&a=delete{$_ext_link}&id={$v['id']}" onclick="return hg_ajax_post(this, '卸载', 1);" style="curse:pointer;"  class="fb">卸载</a></li>
					</ul>
					<div style="clear: both;"></div>
					{/foreach}
					{else}
					<p style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;border-top:1px solid #c8d4e0;margin:0 10px">没有您要找的内容！</p>
					<script>hg_error_html(vodlist,1);</script>
	  				{/if}
				</div>
            </div>
		</div>
	</div>
</div>
<script type="text/javascript">
function hg_remove_row()
{
	 location.reload();
}
</script>
</body>
{template:foot}