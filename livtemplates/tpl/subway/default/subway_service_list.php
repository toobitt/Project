<?php 
?>
{template:head}
{css:2013/list}
{css:2013/button}
{css:subway}
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

{template:unit/subwayservicesearch}
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<form action="" method="POST" name="add_sub" id="add_sub">
		<a type="button" class="button_6"  href="modify.php?app_uniqueid=news&mod_uniqueid=news&app=subway" target="formwin" >新增</a>
	</form>
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
            <div class="m2o-item m2o-gather" title="发布至">发布至</div>
            <div class="m2o-item m2o-audit" title="状态">状态</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/subwayservicelist}
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
		 <!--发布box-->
	    <div class="pop-box publish-box pop-hide">
			<div class="pop-title bus-title">发布至
			 <input type="button" class="pop-save-button publish-save" value="保存"/>	
			 <a class="pop-close-button2 publish-close"></a>
			</div>
			<div class="publish-area">
				<ul></ul>
				<input type="hidden" class="selectId" value=""/>
				<input type="hidden" class="selectName" value=""/>
			</div>
		</div>
    </div>
   </form>
 </div>
 <script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
	$('.m2o-each').on('click', '.options-publish', function(){
		var self = $(this), PublishId = [], PublishTitle = [],
			id = self.closest('.m2o-option').data('id'),
			url = './run.php?mid=' + gMid + '&a=get_service_sort';
		var dom = $('.m2o-each[data-id="' + id + '"]').find('.m2o-gather');
		if( dom.find('span').length ){
			PublishId = dom.find('span').map(function(){
				return $(this).attr('_key');
			}).get();
		}
		$.getJSON(url, function( data ){
			var arrInfo = [];
			if( data && data[0] ){
				$.each(data[0], function(k, v){
					arrInfo.push({
						key : k,
						value : v
					});
				});
				$('#add-publish-tpl').tmpl( arrInfo ).appendTo( $('.publish-area').find('ul').empty() );
				if( $.isArray(PublishId) && PublishId.length ){
					$.each(PublishId, function(k, v){
						var obj = $('.publish-area').find('li[_id=' + v + ']');
						obj.find('input').attr('checked', true);
						PublishTitle.push( obj.find('label').html() );
					})
				}
				$('.publish-box').find('.selectId').val( PublishId.join(',') );
				$('.publish-box').find('.selectName').val( PublishTitle.join(',') );
				$('.publish-box').data('id', id).removeClass('pop-hide');
			}
		});
	});
	
	$('.m2o-list').on('click', '.publish-area input', function(){
		var publishId = [], publishTitle = [];
		var box = $(this).closest('.publish-box');
		box.find('li').each(function(){
			var $this = $(this);
			if( $this.find('input').prop('checked')){
				publishId.push($this.attr('_id'));
				publishTitle.push( $this.find('label').html() );
			}
		});
		$('input.selectId').val( publishId.join(',') );
		$('input.selectName').val( publishTitle.join(',') );
	});
	
	$('.m2o-list').on('click', '.publish-save', function(){
		var self = $(this), 
			box = $('.publish-box');
		var id = box.data('id'),
			column_id = $('input.selectId').val(),
			column_title = $('input.selectName').val();
		var url = './run.php?mid=' + gMid + '&a=publish';
		$.getJSON(url, {column_id : column_id, column_name : column_title, id : id },function( data ){
			if( data && data[0] ){
				var obj = $('.m2o-list').find('.m2o-each[data-id="' + id + '"]');
				var Acolumn = column_title.split(','), str_html = '';
				var className = (obj.find('.m2o-audit').attr('_status') == 1) ? 'common-list-pub' : 'common-list-pre-pub';
				$.each(Acolumn, function(k, v){
					str_html +=	'<span class="' + className + '">' + v + '</span> ';
				});
				obj.find('.m2o-gather').html( str_html );
				$('.publish-close').click();
				$('.m2o-option-close').click();
			}
		});
	});
	
	$('.m2o-list').on('click', '.publish-close', function(){
		$('.publish-box').addClass('pop-hide');
	});
</script>

<script type="text/x-jquery-tmpl" id="add-publish-tpl">
	<li _id='${key}'><input type="checkbox" id="publish_${key}"/><label for="publish_${key}">${value}</label></li>
</script>

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="modify.php?app_uniqueid=news&mod_uniqueid=news&id={{= id}}" target="formwin">编辑</a>
				<a class="option-delete">删除</a>
				<a class="options-publish">发布</a>
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
