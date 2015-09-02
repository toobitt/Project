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
	{template:unit/membersign_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
<!--<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6">新增等级</a>-->
	</div>
</div>


<!-- 记录列表 -->
<div class="common-list-content" style="min-height:auto;min-width:auto;">
	<form action="" method="post">
	 <div class="m2o-list">
	 	<div class="m2o-title m2o-flex m2o-flex-center">
            <div class="m2o-item m2o-flex-one m2o-bt" title="签到总次数">签到总次数:{$get_sign_count[0]['total']}</div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="今日次数">今日次数:{$get_sign_count[0]['todayq']}</div> 
              <div class="m2o-item m2o-flex-one m2o-bt" title="昨日次数">昨日次数:{$get_sign_count[0]['yesterdayq']}</div>
              <div class="m2o-item m2o-flex-one m2o-bt" title="历史最高次数">历史最高次数:{$get_sign_count[0]['highestq']}</div>
        </div>
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	   <div id="infotip" class="ordertip">排序模式已关闭</div>
		       <div class="m2o-item m2o-paixu" title="排序">
		        <!--	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a> -->
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="会员名">会员</div>
            <div class="m2o-item m2o-mark" title="今日名次">今日名次</div> 
              <div class="m2o-item m2o-mark" title="月天数">月天数</div>
         <div class="m2o-item m2o-mark" title="连续天数">连续天数</div>
         {if $get_credit_type&&is_array($get_credit_type)}
         {foreach $get_credit_type as $k => $v }
            <div class="m2o-item m2o-mark" title="奖励">奖励({$v['title']})</div>
           {/foreach}
           {/if}
            <div class="m2o-item m2o-mark" title="心情">心 情</div>
            <div class="m2o-item m2o-mark" title="签到时间">签到时间</div>
        <!--    <div class="m2o-item m2o-sorts" title="所属分类">升级下限</div>-->
           <!--  <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>-->
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/membersignlist}
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
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=get_sign_info&id=${id}&infrm=1" target="formwin">查看</a>
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