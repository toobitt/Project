{template:head}
{css:common/common_list}
{css:vod_style}
{css:2013/list}
{css:public}
{css:common/common}
{css:export_config_list}
{js:2013/ajaxload_new}
{js:2013/list}
{js:box_model/list_sort}
{js:common/common_list}
{js:xml/export_config_list}
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
<body class="biaoz"  style="position:relative;z-index:1"  id="body_content">
<div id="hg_page_menu" class="head_op"{if $_INPUT['infrm']} style="display:none"{/if}>
	<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}&state=1" target="formwin">
		<span class="left"></span>
		<a type="button" class="button_6"  href="./run.php?mid={$_INPUT['mid']}&a=form&site_id={$_INPUT['site_id']}&infrm=1">新增配置</a>
		<span class="right"></span>
	</a>
</div>

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
            <div class="m2o-item m2o-flex-one m2o-bt" title="模版名称">标题</div>
            {if $list[0]['end_time']}
            <div class="m2o-item m2o-aduit w200" title="导出进度">导出进度</div>
            {/if}
            <div class="m2o-item m2o-aduit w80" title="设为默认">设为默认</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	 {if $list}
       	     {foreach $list as $k => $v} 
                 {template:unit/export_config_list_list}
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
	var MC=$('.m2o-list');
	MC.on('mouseenter' , '.m2o-bt' , function( event ){
		var self = $(event.currentTarget),
			selfposition = self.offset(),
			//bottomposition = MC.find('.m2o-bottom').offset(),
			height = $('.common-list-content').height(),
			item = self.closest('.m2o-each').find('.detail-box');
		if( height - selfposition.top > 270){
			item.addClass('detail').show();
		}else{
			item.addClass('detail-list').css({'top' : -265 + 'px'}).show();
		}
	});

	MC.on('mouseleave' , '.m2o-bt' , function( event ){
		var self = $(event.currentTarget);
		self.closest('.m2o-each').find('.detail-box').hide();
	});
	
	MC.on('click' , '.prev' , function( event ){
		var self = $(event.currentTarget);
		self.text('返回').removeClass('prev').addClass('back');
		MC.find('.prev-box').show().end().find('ul').hide();
	});
	
	MC.on('click' , '.back' , function( event ){
		var self = $(event.currentTarget);
		self.text('预览模版').removeClass('back').addClass('prev');
		MC.find('.prev-box').hide().end().find('ul').show();
	})
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
			<p><input type="checkbox" style="vertical-align: bottom;font-size: 16px;"/> 同时删除文件？</p>
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
