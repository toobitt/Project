<?php 
/* $Id: special_list.php 19748 2013-04-22 11:11:24Z zhangzhen $ */
?>
{code}
$list = $special_list[0];
$special_list=$special_list[0];
if($list  && is_array( $list ) ){
foreach($list as $kk => $vv){
	if( $vv['template_sign'] ){
		$vv['ext'] = urlencode("page_data_id=".$vv['id']."&template_id=". $vv['template_sign']);
	}else{
		$vv['ext'] = '';
	}
	$list[$kk] = $vv;
}
}
$attrs_for_edit = array('name','content_count','template_sign','column_url','ext');
{/code}
{template:head}
{template:list/common_list}
{css:special}
<script>
$( function(){
	$('#record-edit').on('click', '.delcache',function( event ){
		var url = $(this).attr( 'href' );
		$.get( url, function(){
		     $('#record-edit').find( '.record-edit-close' ).trigger( 'click' );
		} );
		return false;
	});
	
	$('#record-edit').on('click', '.mkpublish',function( event ){
		var url = $(this).attr( 'href' );
		$.get( url, function(){
		     $('#record-edit').find( '.record-edit-close' ).trigger( 'click' );
		} );
		return false;
	});
} )

</script>
<style>
.biaoti-img{width: 55px;height: 40px;float: left;}
.public-list .common-list-item{height:46px;}
</style>
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display:none"{/if}>
	{template:unit/special_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="./run.php?mid={$_INPUT['mid']}&a=form&infrm={$_INPUT['infrm']}" class="add-button" target="formwin">新增专题</a>
	</div>
</div>
<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
{if !$special_list}
	<p id="emptyTip" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
{else}
	<form method="post" action="" name="listform" class="common-list-form">
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list special-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
	                <div class="common-list-item paixu open-close">
 	                   <a title="排序模式切换/ALT+R" onclick="hg_switch_order('speciallist');"  class="common-list-paixu"></a>
                    </div>
                </div>
				<div class="common-list-right">
					<div class="common-list-item common-list-pub-overflow">发布至</div>
					<!-- <div class="common-list-item wd100" style="width:160px;max-width:none;"></div> -->
                    <div class="common-list-item wd80">分类</div>
                    <div class="common-list-item wd60">权重</div>
                    <div class="common-list-item wd60">状态</div>
                    <div class="common-list-item wd100">添加人/时间</div>
                </div>
                <div class="common-list-biaoti">
					<div class="common-list-item">专题名称</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="special-list common-list public-list hg_sortable_list" id="speciallist" data-table_name="article" data-order_name="order_id">
		{foreach $special_list as $k => $v}
			{template:unit/speciallist}
		{/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" /> 
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
					<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
					<a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
					<a style="cursor:pointer;" onclick="return hg_bacthmove_show(this,'special_sort');">移动</a>
				</div>
				{$pagelink}
			</li>
		</ul>    	
	</form>
{/if}
</div> 
<!-- 移动框 -->  
{template:unit/list_move_box}
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip"  class="ordertip"></div>

<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}

{template:foot}