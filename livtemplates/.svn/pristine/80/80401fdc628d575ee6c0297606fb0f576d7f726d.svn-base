<?php 
/* $Id: group_list.php 9410 2012-05-22 07:43:34Z lijiaying $ */
?>
{template:head}
{css:common/common}
{css:2013/list}
{css:common/common_list}
{js:vod_opration}
{js:2013/list}
{js:2013/ajaxload_new}
{js:box_model/list_sort}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:common/common_list}
{js:hg_switchable}
{js:feedback/feedback_list}
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
.w40{width:30px;}
.w80{width:80px;}
.common-switch{bottom:0px;}
.color{color: #8fa8c6;}
.m2o-quality{text-align:center}
.m2o-link{position:relative;cursor:pointer;}
.link-box{background: #fff;border: 1px solid #ddd;position: absolute;top:25px;left:10px;padding: 10px;border-radius: 5px;z-index: 999;display:none;}
.link-box:before{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #d9dfe7;top:-15px;left:14px;}
.link-box:after{position: absolute;content: '';border: 7px solid transparent;border-bottom-color: #fff;top:-14px;left:14px;}
</style>
<div style="display:none">
    {template:unit/feedback_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		{if $_INPUT['_id']}
		<a class="blue mr10 show-pop">
			<span class="left"></span>
			<span class="middle"><em class="add">分类数据</em></span>
			<span class="right"></span>
		</a>
		{/if}
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&state=1" target="formwin">
			<span class="left"></span>
			<span class="middle"><em class="add">新增表单</em></span>
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
            <div class="m2o-item m2o-flex-one m2o-bt" title="表单名称">表单名称</div>
            <div class="m2o-item m2o-num w80" title="发布至">发布至</div>
            <div class="m2o-item m2o-style w80" title="分类">分类</div>
            <div class="m2o-item m2o-num w80" title="链接">链接</div>
            <div class="m2o-item m2o-state w80" title="审核状态">审核状态</div>
           	{if $_configs['App_im']}
            <div class="m2o-item m2o-state w80" title="回复状态">开启/关闭<br/>回复</div>
            <div class="m2o-item m2o-num w80" title="新回复">新回复</div>
            {/if}
            <div class="m2o-item w40" title="回收数量">回收数量</div>
            <div class="m2o-item w40" title="审核通过">审核通过</div>
            <div class="m2o-item w40" title="审核未通过">未通过</div>
            <div class="m2o-item m2o-time w80" title="截止时间">截止时间</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/feedbacklist}
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
    
    $('.m2o-each').geach({																								
	   	custom_audit : true,
	   	auditCallback : function(event){
	   		var status_text = ['待审核','已审核','已打回'],
	   			option_text = ['', '打回' ,'审核'],
	   			status_color = ['#8ea8c8','#17b202','#f8a6a6'];
	   		var self = $(event.currentTarget),
	   			id = self.data('id'),
	   			_this = this,
	   			item = self.closest('.m2o-each').find('.m2o-audit'),
	   			status = item.attr('_status') == 1 ? 0 : 1,
	   			url = './run.php?mid=' + gMid + '&a=audit&ajax=1&id=' + id + '&audit=' + status;
    		$.globalAjax( item , function(){
        		return $.getJSON( url,function( json ){
						if(json['callback']){
							eval( json['callback'] );
							return;
						}else{
							item.text( status_text[json[0].status] ).attr('_status' , json[0].status ).css('color' , status_color[ json[0].status ] );
							self.find('.option-audit').text( option_text[json[0].status] );
							if(json[0].status == 1)
							{
								createForm(id);
							}
						}
					});
			});
	    },
    });
	$('.m2o-list').glist();
	function createForm(id){
		var url = './run.php?mid=' + gMid + '&a=create_form&id='+ id;
			$.globalAjax( self, function(){
			return $.getJSON( url,function( data ){
				if( data['callback'] ){
					eval( data['callback'] );
					return;
				}
			});
		});
	}
</script>
<script>
$(function(){
	var onOff = function(id, obj, is_on){
		var url = './run.php?mid=' + gMid + '&a=is_reply&ajax=1';
		$.getJSON( url, {id : id, is_on : is_on} ,function( data ){
			if( data['callback'] ){
				eval( data['callback'] );
				var state = (obj.attr('_status') == 1);
				obj.find('.common-switch')[ (state ? 'add' : 'remove') + 'Class']('common-switch-on');
				obj.find('.ui-slider-handle').css({
					'left' : (state ? '100%' : '0%')
				});
			}else{
				data = data[0];
				var status = data['switch'];
				obj.attr('_status', status);		
			}
		});
	}
	
	$('.common-switch').each(function(){
		var $this = $(this),
			obj = $this.parent();
		var id = $this.closest('.m2o-each').data('id');
		$this.hasClass('common-switch-on') ? val = 100 : val = 0;
		$this.hg_switch({
			'value' : val,
			'callback' : function( event, value ){
				var is_on = 0;
				( value > 50 ) ? is_on = 1 : is_on = 0;
				onOff(id, obj, is_on);
			}
		});
	});
});
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner">
        <div class="m2o-btns">
			<div class="m2o-btn-area m2o-flex">
				<a class="option-audit">{{if status == 1}}打回{{else}}审核{{/if}}</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="formwin" need-back>编辑</a>
				<a class="option-delete">删除</a>
				<a class="generate-form">生成表单</a>
			</div>
			<div class="m2o-btn-area m2o-flex">
				<a class="generate-greet">生成贺卡</a>
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
{template:unit/pop}
{template:foot}
