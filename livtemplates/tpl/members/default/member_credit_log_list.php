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
//print_r($get_credit_name);
{/code}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/membercreditlog_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
<!--<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6">新增等级</a>-->
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
		        	<!--  <a title="排序模式切换/ALT+R" class="common-list-paixu"></a>-->
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="会员名">所属会员</div>
            <!-- 
            <div class="m2o-item m2o-sorts" title="应用标识">应用标识</div> 
              <div class="m2o-item m2o-switch" title="模块标识">模块标识</div>
         <div class="m2o-item m2o-sorts" title="方法">方法</div>
          -->
          {if $get_credit_type&&is_array($get_credit_type)}
         {foreach $get_credit_type as $k => $v }
            <div class="m2o-item m2o-credits" title="{$v['title']}">{$v['title']}</div>
           {/foreach}
           {/if}
           
            <div class="m2o-item m2o-mark" title="索引图">索引图</div>
            <div class="m2o-item m2o-title1" title="操作原因">操作原因</div>
            <div class="m2o-item m2o-remark" title="变更描述">变更描述</div>
            <div class="m2o-item m2o-mark" title="记录时间">记录时间</div>
        <!--    <div class="m2o-item m2o-sorts" title="所属分类">升级下限</div>-->
           <!--  <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>-->
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/membercreditloglist}
	        {/foreach}
	        {else}
				<p class="common-list-empty">没有你要找的内容！</p>
			{/if}
        </div>
        <div class="m2o-bottom m2o-flex m2o-flex-center">
		  	 <div class="m2o-item m2o-paixu">
        	<!-- <input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/> -->
    		</div>
    		<div class="m2o-item m2o-flex-one list-config">
    		 <!--   <a class="batch-handle">删除</a> -->
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
	var onOff = function(id, obj, is_on){
		var url = './run.php?mid=' + gMid + '&a=display';
		$.getJSON( url, {id : id, is_on : is_on} ,function( data ){
			var data = data[0],
				status = data['switch'];
				obj.attr('_status', status);		
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
{template:foot}