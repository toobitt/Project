{template:head}
{css:2013/list}
{css:common/common}
{js:box_model/list_sort}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:2013/list}
{js:2013/ajaxload_new}
{js:hg_switchable}
{js:live/server_config_list}
{code}
//print_r($list);
{/code}
<style>
.w80{width:80px;}
.w100{width:100px;}
.color{color:#8fa8c6;}
.resend{color:red;}
.overflow{white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}
.m2o-bt:hover .common-title{padding-left:15px;}
.common-title{-webkit-transition: all 0.15s ease-in 0s;transition: all 0.15s ease-in 0s;}
.m2o-switch .common-switch{bottom: 0;right:0;}
</style>
<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	 <!-- 搜索 -->
				<div class="v_list_show" style="float:none;">
					<div class="search_a" id="info_list_search">
						<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
							<div class="select-search">
								<input type="hidden" name="a" value="show" />
								<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
								<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
							</div>
							<div class="text-search">
								<div class="button_search">
									<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
								</div>
								{template:form/search_input,k,$_INPUT['k']}                        
							</div>
						</form>
					</div>
				</div>
				<!-- 搜索 -->
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="blue mr10" href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" target="nodeFrame">
			<span class="left"></span>
			<span class="middle"><em class="add">新增直播配置</em></span>
			<span class="right"></span>
		</a>
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
            <div class="m2o-item m2o-flex-one m2o-bt" title="配置名称">配置名称</div>
            <div class="m2o-item m2o-state w80" title="服务状态">主控状态</div>
            <div class="m2o-item m2o-host w100" title="主机">主机</div>
            <div class="m2o-item m2o-host w80" title="启用备播">启用备播</div>
            <div class="m2o-item m2o-num w80" title="信号数目">信号数目</div>
            <div class="m2o-item m2o-audit w100" title="状态">状态</div>
            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
        </div>
        <div class="m2o-each-list">
        	{if $list}
	        {foreach $list as $k => $v}
	        	{template:unit/server_config_list_list}
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
</script>
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&infrm=1">编辑</a>
				<a class="option-delete">删除</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id=${id}&copy=1&infrm=1">复制</a>
				<a href="./run.php?mid={$_INPUT['mid']}&a=show_stream_status&id=${id}&copy=1&infrm=1">流状态</a>
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