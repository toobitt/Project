{template:head}
{css:2013/list}
{css:common/common}
{css:members_list}
{js:2013/list}
{js:2013/ajaxload_new}
{js:members/member_medal_list}
{code}
//print_r($appnames);
{/code}
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/member_medal_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
<!--		<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6">新增等级</a>-->
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
            <div class="m2o-item m2o-mark" title="申请人">申请人</div>
               <div class="m2o-item m2o-flex-one" title="勋章名称">勋章名称</div>
            <div class="m2o-item m2o-time" title="申请日期">申请日期</div> 
           <div class="m2o-item m2o-time" title="勋章有效期">勋章有效期</div> 
         <!--     <div class="m2o-item m2o-mark" title="用户级别">配置类型</div> -->
         <!--      <div class="m2o-item m2o-sort" title="升级上限">升级上限</div> -->
          <!--   <div class="m2o-item m2o-switch" title="启用">启用</div>  -->
        <!--    <div class="m2o-item m2o-sorts" title="所属分类">升级下限</div>-->
           <!--  <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>-->
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/member_medallist}
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
    		    <a class="allow" _type="1">通过</a>
    		    <a class="refuse" _type="0">否决</a>  
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
{template:foot}