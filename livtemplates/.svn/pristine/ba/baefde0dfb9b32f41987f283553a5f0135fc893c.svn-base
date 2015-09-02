{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:2013/list}
{css:public}
{css:common/common}
{js:ajax_upload}
{js:2013/ajaxload_new}
{js:2013/list}
{js:box_model/list_sort}
{js:common/common_list}
{js:verify_code/verify_font_list}
{template:list/common_list}
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
</script>
<style>
.m2o-item .m2o-item-bt img{width:120px;}
.m2o-list .w80{width:80px;}
.m2o-list .w120{width:120px;}
.m2o-bt .m2o-item-bt{width:auto}
.m2o-bt .m2o-item-bt{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.m2o-bt:hover .m2o-item-bt{padding-left:8px;}
.m2o-state{color:#939393;}
</style>

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/verify_font_top}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<span class="add-font-pack button_6" _id="{$_INPUT['mid']}">导入字体包</span>
		<input type="file" name="filedata" class="upload-file" style="display:none;">
	</div>
</div>
			
<div class="common-list-content" style="min-height:auto;min-width:auto;">
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	  <!--   <div id="infotip" class="ordertip">排序模式已关闭</div>-->
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="对象">字体名称</div>
            <div class="m2o-item m2o-state" title="类型">类型</div>
            <div class="m2o-item m2o-aduit w80" title="所属栏目">状态</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if is_array($list) && count($list)>0}
				{foreach $list as $k => $v}	
		            {template:unit/verify_font_list_list}
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

<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
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

<script type="text/x-jquery-tmpl" id="list-tpl">
<style>
@font-face{
font-family: ${name};
src: url('http://localhost/livsns/api/verifycode/data/fonts/${name}.${type}');
}
.font${id}{font-family:'${name}';font-size:20px;color: #939393;max-height:40px;}
</style>
<div class="m2o-each m2o-flex m2o-flex-center" data-id="${id}" orderid="${id}">
<div class="m2o-item m2o-paixu">
	<input type="checkbox" value="${id}" title="${id}" name="infolist[]" class="m2o-check" style="visibility: visible;">
</div>
<div class="m2o-item m2o-flex-one m2o-bt">
		<span class="m2o-item-bt font${id}">
			<span>${name}</span>
		</span>
</div>
 <div class="m2o-item m2o-state">${type}</div>
<div class="m2o-item m2o-audit" _status="${status}" style="color:#8ea8c8;" >待审核</div>
<div class="m2o-item m2o-time">
    <span class="name">${user_name}</span>
    <span class="time">${time}</span>
</div>
<div class="m2o-item m2o-ibtn"></div>
</div>
</script>

{template:foot}