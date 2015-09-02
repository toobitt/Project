<?php 
/* $Id: list.php 21480 2013-05-28 07:01:10Z yizhongyue $ */
?>
{template:head}
{css:2013/iframe}
{css:2013/list}
{css:gather_list}
{js:2013/ajaxload_new}
{js:page/page}
{js:2013/list}
{js:box_model/list_sort}
{js:gather/gather_list}
{code}
$menuSort = $menu_sort[0];
$personalType = $personal_auth[0];
//print_r($menu_list);
//print_r($menu_count);
//print_r($_user);
{/code}

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/news_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		 <!--<a class="add-button news mr10" href="run.php?mid={$_INPUT['mid']}&a=create&infrm=1" target="nodeFrame">添加接入地址</a>-->
	</div>
</div>

<!-- 记录列表 -->
<div class="wrap m2o-flex gather">
	<aside class="aside-box" data-accountId ="{$personalType}" data-userId = "{$_user['id']}">
		<div class="m2o-list">
			 <div class="m2o-each-list">
		   	 </div>
		    <div class="m2o-bottom m2o-flex m2o-flex-center">
		    	<div class="m2o-all">全部</div>
				<div class="page_size m2o-flex-one"></div>
			</div>
		</div>
	</aside>
	<section class="list-box m2o-flex-one">
		{if $menuSort}
		<div class="gather-tip">
			<span class="gather-title">以下分类会在m2o采集时显示在"发送至分类"，并选择一项：</span>
			<ul class="clear">
			{foreach $menuSort as $k=>$v}
				<li data-sortid="{$v['id']}">{$v['name']}</li>
			{/foreach}
			</ul>
		</div>
		{/if}
		<div class="m2o-list">
			<div id="infotip" class="ordertip">排序模式已关闭</div>
	        <div class="m2o-title m2o-flex m2o-flex-center">
	        	<div class="m2o-item m2o-paixu" title="排序">
	        		<a title="排序模式切换/ALT+R" class="common-list-paixu"></a>
	        	</div>
	            <div class="m2o-item m2o-flex-one m2o-bt" title="标题">标题</div>
	            <div class="m2o-item m2o-gather" title="转发至">转发至</div>
	            <div class="m2o-item m2o-sort" title="分类">分类</div>
	            <div class="m2o-item m2o-state" title="状态">状态</div>
	            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
	        </div>
			 <div class="m2o-each-list">
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
				<div class="page_size"></div>
			</div>
	  	</div>
	</section>
</div>	
{template:foot} 

<!-- 用户数据列表 -->
<script type="text/x-jquery-tmpl" id="userdata-tmpl">
	<div class="m2o-each m2o-flex m2o-flex-center" data-id="${user_id}">
  		<div class="m2o-item m2o-avatar">
  			<img src="${avatarImg}" />
		</div>
        <div class="m2o-item m2o-flex-one m2o-user">${user_name}</div>
        <div class="m2o-item m2o-time">${time}</div>
        <div class="m2o-item m2o-num">${total}</div>
    </div>
</script>

<!-- 用户数据头部 -->
<script type="text/x-jquery-tmpl" id="userhead-tmpl">
	<div class="m2o-title m2o-flex m2o-flex-center">
		<div class="m2o-item m2o-avatar" title="头像">&nbsp;</div>
        <div class="m2o-item m2o-flex-one m2o-user" title="用户">用户</div>
        <div class="m2o-item m2o-time" title="日期">日期</div>
        <div class="m2o-item m2o-num" title="采集条数">采集条数</div>
    </div>
</script>

<!-- 日期数据列表 -->
<script type="text/x-jquery-tmpl" id="datedata-tmpl">
	<div class="m2o-each m2o-flex m2o-flex-center" data-date="${time}">
		<div class="m2o-item m2o-flex-one">${time}<span>（${total}）</span></div>
	</div>
</script>

<!-- 日期数据头部 -->
<script type="text/x-jquery-tmpl" id="datehead-tmpl">
	<div class="m2o-title m2o-flex m2o-flex-center">
		<div class="m2o-item m2o-flex-one" title="时间">时间</div>
    </div>
</script>

<!-- 没有数据时 -->
<script  type="text/x-jquery-tmpl" id="nodata-tpl">
	<p class="common-list-empty">没有您要找的内容！</p>
</script>

<!-- 文章列表 -->
<script type="text/x-jquery-tmpl" id="articledata-tmpl">
	<div class="m2o-each m2o-flex m2o-flex-center" data-id="${id}" orderid="${order_id}">
        <div class="m2o-item m2o-paixu">
		   <input type="checkbox"  value="${id}" title="${id}"  name="infolist[]" class="m2o-check" />
    	</div>
        <div class="m2o-item m2o-flex-one m2o-bt">
           <div class="m2o-title-transition max-wd">
        	 <a class="m2o-title-overflow" title="${title}"  href="./run.php?mid={$_INPUT['mid']}&a=form&infrm=1&id=${id}" target="mainwin">
        	 	{{if indexpic}}
        	 	<img style="margin-right: 10px;" src="${indexpic}" />
        	 	{{/if}}
        	 	<span>${title}</span>
	 		</a>
           </div>
        </div>
        <div class="m2o-item m2o-gather ${source_class}">{{tmpl($data['sourcelist']) "#transpond-tmpl"}}</div>
        <div class="m2o-item m2o-sort">${sort_name}</div>
        <div class="m2o-item m2o-audit" _status="${status}" style="color:${status_color};" >${status_name}</div>
        <div class="m2o-item m2o-time">
            <span class="name">${user_name}</span>
            <span class="time">${create_time}</span>
        </div>
        <div class="m2o-item m2o-ibtn"></div>
    </div>
</script>
<!--转发至-->
<script type="text/x-jquery-tmpl" id="transpond-tmpl">
	<span>${source}</span>
</script>

<!-- 右侧悬浮框配置 -->
<script type="text/x-jquery-tmpl" id="m2o-option-tpl">
<div class="m2o-option" data-id="{{= id}}">
    <div class="m2o-option-inner m2o-flex">
        <div class="m2o-btns m2o-flex">
			<div class="m2o-btn-area m2o-flex">
				<a href="./run.php?mid={$_INPUT['mid']}&a=form&id={{= id}}&infrm=1" target="mainwin">编辑</a>
				<a class="option-delete">删除</a>
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