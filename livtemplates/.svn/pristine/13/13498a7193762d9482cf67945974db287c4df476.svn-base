<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:2013/list}
{css:common/common_list}
{js:vod_opration}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:common/common_list}
{code}
	if(!isset($_INPUT['state']))
	{
		$_INPUT['state'] = -1;
	}
	
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
	//print_r($list);
{/code}

<style>
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.w120{width:120px;}
.color{color: #8fa8c6;}
.m2o-state , .m2o-audit{text-align:center}
</style>
<div style="display:none">
   {template:unit/tv_interact_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&state=1" target="formwin">
			<span class="left"></span>
			<span class="middle"><em class="add">新增电视互动</em></span>
			<span class="right"></span>
		</a>
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
            <div class="m2o-item m2o-flex-one m2o-bt" title="标题">标题</div>
            <div class="m2o-item w120 m2o-state" title="审核状态">审核状态</div>
            <div class="m2o-item m2o-num w120" title="活动时间">活动时间</div>
            <div class="m2o-item w120 m2o-sort" title="活动状态">活动状态</div>
            <div class="m2o-item w120" title="活动状态">总数/已送</div>
            <div class="m2o-item m2o-style w120" title="分类">分类</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/interactlist}
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
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id , status){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
                       status : n['status']
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
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin" need-back>编辑</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=show_win_info&id={{= id}}&infrm=1" need-back>获奖信息</a>
				<a class="option-audit">{{if status == 1}}打回{{else}}审核{{/if}}</a>
				<a class="option-delete">删除</a>
				<a></a>
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
{template:foot}
