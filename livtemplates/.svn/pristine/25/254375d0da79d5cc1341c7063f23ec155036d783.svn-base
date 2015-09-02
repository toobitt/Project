{template:head}
{css:common/common_list}
{js:common/common_list}
{css:vod_style}
{css:2013/list}
{css:public}
{css:common/common}
{js:2013/ajaxload_new}
{js:2013/list}
{js:box_model/list_sort}
{js:common/common_list}
{js:xml/xml_list}
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
	//print_r($formdata);
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
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div class="content clear">
 <div class="f">
          <div class="right v_list_show">
				
				<div class="common-list-content" style="min-height:auto;min-width:auto;">
	<form action="" method="post">
	 <div class="m2o-list">
			<!--排序模式打开后显示排序状态-->
			<div class="m2o-title m2o-flex m2o-flex-center">
		 	  <!--  <div id="infotip" class="ordertip">排序模式已关闭</div> -->
		       <div class="m2o-item m2o-paixu" title="排序">
		        	<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
		       </div>
            <div class="m2o-item m2o-flex-one m2o-bt" title="模版名称">模版名称</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	 {if $formdata}
       	     {foreach $formdata as $k => $v} 
                 {template:unit/xml_list_list}
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
    		   <a class="batch-handle">删除</a>
    		</div>
    		<div id="page_size">{$pagelink}</div>
		</div>
    </div>
   </form>
 </div>
			<div class="edit_show">
			<span class="edit_m" id="arrow_show"></span>
			<div id="edit_show"></div>
			</div>
            </div>
        </div>
</div>

</body>
<script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1&state=1" target="mianwin">编辑</a>
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
