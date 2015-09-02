{template:head}
{css:2013/list}
{css:common/common}
{css:members_list}
{js:box_model/list_sort}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:2013/list}
{js:2013/ajaxload_new}
{js:hg_switchable}
{code}
//print_r($appnames);
{/code}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/membercredittype_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
	<!--	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6">新增积分类型</a>-->
	</div>
</div>


<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="积分名称">积分名称</div>
            <div class="m2o-item m2o-mark" title="积分字段">积分字段</div>
            <div class="m2o-item m2o-switch" title="是否开启">是否开启</div>
            <div class="m2o-item m2o-switch" title="是否交易">货币单位</div>
          <div class="m2o-item m2o-switch" title="等级启用">等级体系</div> 
           <!--  <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>-->
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/membercredittypelist}
	        {/foreach}
	        {else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        	<!-- 	<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>-->
    		</div>
    		<div class="m2o-item m2o-flex-one list-config">
    		  <!--  <a class="batch-handle">删除</a> -->
    		</div>
    		<div id="page_size">{$pagelink}</div>
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
                       id : n['id'],
                       issystem : n['issystem'],
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
<script>
$(function(){
	var judgeAfter = function( data ,obj, status ){
		var	box = obj.closest('.m2o-each'),
			other = box.siblings().find('.'+data.field),
			tname = 'common-switch-on';
		if(data.field !='is_on' && status==0){
			other.removeClass( tname );
			other.parent().attr('_status', 0);
			other.find('.ui-slider-handle').css({'left': '0%'});
		}
	};
	
	var judgeBefore = function(data , obj ){
		var	box = obj.closest('.m2o-each'),
			other = box.siblings().find('.'+data.field),
			field = data.field,
			tname = 'common-switch-on';
		var noon = true;
		$.each(other, function(){
			if($(this).hasClass( tname ) && field == 'is_on'){
				var noon = false;
			} 
		});
		return noon;
	};
	
	var onOff = function(data, obj){
		var url = './run.php?mid=' + gMid + '&a=display';
		$.getJSON( url, data ,function( json ){
			var json = json[0],
				status = json['opened'];
				obj.attr('_status', status);	
				judgeAfter(data, obj, status );	
		});
	};
	
	$('.common-switch').each(function(){
		var $this = $(this),
			obj = $this.parent();
		var data={
				id : $this.closest('.m2o-each').data('id'),
				field : $this.closest('.m2o-switch').attr('_type'),
			},
			tname = 'common-switch-on';
		$this.hasClass( tname ) ? val = 100 : val = 0;
		$this.hg_switch({
			'value' : val,
			'callback' : function( event, value ){
				if(!$this.hasClass( tname ) && (data.field != 'is_on'&&data.field != 'is_trans')){
					var bool = judgeBefore( data ,$this );
					if(bool){
						obj.myTip({
							string : '开启其它样式则自动关闭',
							delay: 2000,
							dtop : 5,
							dleft : -180,
							width : 150
						});
						$this.addClass( tname );
						$this.parent().attr('_status', 1);
						$this.find('.ui-slider-handle').css({'left': '100%'});
						return false;
					}
				}
				var is_on = $this.closest('.m2o-switch').attr('_status');
				data.is_on = is_on;
				onOff(data, obj);
				
			}
		});
	});
});
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1">编辑</a>
				{{if issystem==0}}<a class="option-delete">删除</a>{{/if}}
				<a></a>
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