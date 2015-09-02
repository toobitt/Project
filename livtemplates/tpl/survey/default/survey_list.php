<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:2013/list}
{css:survey_form}
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
	
	if(!isset($_INPUT['source_type']))
	{
		$_INPUT['source_type'] = -1;
	}
	
	if(!isset($_INPUT['date_search']))
	{
		$_INPUT['date_search'] = 1;
	}
	//print_r($list);
{/code}
<script>
$(function($){
	var addFont = $(parent.$('body').find('.add-font-pack'));
	addFont.click(function(){
		$('.survey-box').css('top' , 100);
	});
	$('.delete').on('click' , 'span' ,function(){
		del();
	});
	
	function del(){
		$('.survey-box').css('top' , -1000);
	};
	
	$('.new').on('click' , 'a' ,function(){
		setTimeout(function(){
			del();
		},2000)
	})
});
</script>
<style>
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.w80{width:80px;}
.color{color: #8fa8c6;}
.common-list-pub{text-decoration: underline;color: #49a34b;}
.m2o-num a{color:#17b202;text-decoration: underline;}
.m2o-num,.m2o-style{text-align:center;}
.m2o-link{position:relative;cursor:pointer;}
.link-url{display:none; }
.link-url.hasurl{display:block; }
.link-box{background: #fff;border: 1px solid #ddd;position: absolute;top:25px;left:10px;padding: 10px;border-radius: 5px;z-index: 999;display:none;}
.link-box.show{display:block; }
.link-box:before{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #d9dfe7;top:-15px;left:14px;}
.link-box:after{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #fff;top:-14px;left:14px;}
</style>
<div style="display:none">
	{template:unit/survey_top}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<span class="add-font-pack button_6">新增问卷</span>
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
            <div class="m2o-item m2o-flex-one m2o-bt" title="问卷标题">问卷标题</div>
            <div class="m2o-item m2o-state" title="状态">状态</div>
            <div class="m2o-item m2o-sort" title="发布至">发布至</div>
            <div class="m2o-item m2o-link w80" title="链接">链接</div>
            <div class="m2o-item m2o-num w80" title="有效问卷">有效问卷</div>
            <div class="m2o-item m2o-style w80" title="题目数">题目数</div>
            <div class="m2o-item m2o-style w80" title="分类">分类</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list[0] as $k => $v}	
		            {template:unit/surveylist}
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
	$.m2o.geach.prototype._click = function( event ){
		$('.m2o-list').find('.link-box.show').removeClass('show');
		var target = $( event.target ),
			item = this.element;
		if( target.is('.option-generate') ){
			generate( item, target );		//生成功能
		}else if( target.is('.m2o-link') || target.closest('.m2o-link').length ){
			viewlink( item );
		}
	};
	
	function generate( item, target ){
		var id = item.data('id'),
			generate_url ='./run.php?mid=' + gMid + '&a=generate&ajax=1';
		$.globalAjax( target, function(){
    		return $.getJSON( generate_url, { id : id }, function( data ){
					if(data['callback']){
						eval( data['callback'] );
						return;
					}
					
					if( $.isArray( data ) && data[0] && data[0].state == 1 ){
						target.myTip({
							string : '生成表单成功',
							color : '#1bbc9b',
							dleft : 68
						});
						var ahref = item.find('.link-url').addClass('hasurl').find('a');
						ahref.html( data[0].url )[0].href = data[0].url;
					}
			});
		});
	}
	
	function viewlink( item ){
		item.find('.link-box').addClass('show');
	}
	
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a class="option-audit">{{if status == 1}}打回{{else}}审核{{/if}}</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin" need-back>编辑</a>
				<a class="option-generate">生成</a>
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
{template:unit/survey_add_box}
{template:foot}
