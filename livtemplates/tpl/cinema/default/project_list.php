<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:2013/list}
{css:common/common_list}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:common/common_list}
{code}
//print_r( $total );
{/code}
<style>
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.w80{width:80px;}
.color{color: #8fa8c6;}
.common-list-pub{text-decoration: underline;color: #49a34b;}
</style>
<div style="display:none">
	{template:unit/play_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1&cinema_id={$_INPUT['cinema_id']}&cinema_name={$_INPUT['cinema_name']}&state=1" target="mainwin" class="button_6">新增排片</a>
	</div>
</div>

<div class="common-list-content" style="min-height:auto;min-width:auto;">
<div id="add_question"  class="single_upload">
					<div id="question_option_con">
					</div>
				</div>
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="排片日期">排片日期</div>
            <div class="m2o-item m2o-state" title="状态">状态</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list[0]['dates']) && count($list[0]['dates'])>0}
				{foreach $list[0]['dates'] as $k => $v}	
		            {template:unit/projectlist}
		        {/foreach}
			{else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one">
    		   <a class="batch-handle">审核</a>
    		   <a class="batch-handle">打回</a>
    		   <a class="batch-handle">删除</a>
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
 </div>
 <script>
	var data = $.globalListData = {code}echo $list[0] ? json_encode($list[0]) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id , status , dates){
            var info;
            $.each(data.dates, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
                       status : n['status'],
                       dates : n['dates']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={if $_INPUT['_id']}{$_INPUT['_id']}{else}{$list[0]['movie_id']}{/if}&infrm=1&create_time={{= dates}}&cinema_id={$_INPUT['cinema_id']}&cinema_name={$_INPUT['cinema_name']}&movie_id={if $_INPUT['_id']}{$_INPUT['_id']}{else}{$list[0]['movie_id']}{/if}" target="mainwin" need-back>编辑</a>
				<a class="option-delete">删除</a>
			</div>
			<div class="m2o-option-line"></div>
        </div>
    </div>
	<div class="m2o-option-confirm">
			<p>确定要删除该内容吗？</p>
			<div class="m2o-option-line"></div>
			<div class="m2o-option-confim-btns">
				<a class="confim-sure">确定</a>
				<a class="confim-cancel cancel">取消</a>
			</div>
	</div>
	<div class="m2o-option-close"></div>
</div>
</script>
