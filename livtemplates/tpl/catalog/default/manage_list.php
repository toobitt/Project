{template:head}
{css:2013/list}
{css:common/common}
{css:catalog_list}
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
	{template:unit/catalog_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6">新增编目</a>
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
            <div class="m2o-item m2o-flex-one m2o-bt" title="编目名称">编目名称</div>
            <div class="m2o-item m2o-mark" title="标识">标识</div>
            <div class="m2o-item m2o-sort" title="数据类型">类型名称</div>
            <div class="m2o-item m2o-switch" title="启用">启用</div> 
            <div class="m2o-item m2o-sorts" title="所属分类">所属分类</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/managelist}
	        {/foreach}
	        {else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
    		</div>
    		<div class="m2o-item m2o-flex-one list-config">
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
</script>
<script>
$(function(){
	var onOff = function(id, obj, is_on){
		var url = './run.php?mid=' + gMid + '&a=display&ajax=1';
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
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1">编辑</a>
				<a class="option-delete">删除</a>
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