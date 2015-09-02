<?php 
/* $Id: special_list.php 19748 2013-04-22 11:11:24Z zhangzhen $ */
?>
{template:head}
{css:common/common_list}
{css:transcode_list}
{css:common/common}
{js:transcode_center/transcode}
{js:common/common_list}
{js:2013/ajaxload_new}
{code}
$list=$list[0]['status'];
{/code}
<script>
$(window).load(function(){
	parent.$('#top-loading').hide();
});
</script>

<!-- 记录列表 -->
<div>
	<form method="post" action="" name="listform" class="common-list-form">
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list special-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
	                <div class="common-list-item paixu open-close">
 	                   <a title="排序模式切换/ALT+R"  class="common-list-paixu"></a>
                    </div>
                </div>
				<div class="common-list-right">
                    <div class="common-list-item wd50">大小</div>
                    <div class="common-list-item wd120">进度</div>
                    <div class="common-list-item wd70">暂停/恢复</div>
                    <div class="common-list-item wd50">删除</div>
                   <!--  <div class="common-list-item wd50">恢复</div>--> 
                    <div class="common-list-item wd50">优先级</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">转码任务</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="special-list common-list public-list hg_sortable_list" id="tasklist" data-table_name="article" data-order_name="order_id">
		    {foreach $list as $k => $v}
		    	{template:unit/tasklist}
		    {/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_bacth_trans(this, 'pause', '暂停');" name="pause">暂停</a>
					<a style="cursor:pointer;" onclick="return hg_bacth_trans(this, 'stop', '删除');" name="stop">删除</a>
				</div>
				{$pagelink}
			</li>
		</ul>    	
	</form>
</div>   

<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>
