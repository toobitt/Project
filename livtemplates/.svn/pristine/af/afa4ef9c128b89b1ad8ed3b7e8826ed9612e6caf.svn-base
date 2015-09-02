{template:head}
{css:2013/iframe}
{css:2013/list}
{css:2013/button}
{css:access_list}
{js:2013/ajaxload_new}
{js:page/page}
{js:storage/hoge_storage}
{js:access/access}
{js:access/access_set}
{code}
/*所有选择控件基础样式*/
$all_select_style = array(
	'class' 	=> 'access down_list',
	'state' 	=> 	0,
	'is_sub'	=>	1
);
$_INPUT['access_time'] = isset($_INPUT['access_time']) ? $_INPUT['access_time'] : 1;
$app_info = $access_list[0]['app_info'];
//print_r($app_info);
{/code}

<div class="access-box">
	<div class="module attention m2o-flex">
		<aside class="aside">
			<div class="title">已关注</div>
			<ul class="tab">
				<li class="visit">访问</li>
<!--				<li class="comment">评论</li>-->
			</ul>
		</aside>
		<section class="section m2o-flex-one">
			<ul class="module-box clear">
			</ul>
		</section>
	</div>
	<!--
	<div class="module push m2o-flex">
		<aside class="aside">
			<div class="title">系统推送</div>
			<ul class="tab">
				<li class="visit">访问</li>
				<li class="comment">评论</li>
			</ul>
		</aside>
		<section class="section m2o-flex-one">
			<ul class="module-box clear">
			</ul>
		</section>
	</div>
	-->
	<div class="module type m2o-flex">
		<aside class="aside">
			<form name="searchform" id="searchform" action="" method="get">
				<div class="title type-item clear">
					{code}
						$type_style = $all_select_style;
						$type_style['show'] = 'type_item';
						$type_style['width'] = 109;
						$type_default = null;
						$type_data[$type_default] = '全部类型';
						if($app_info){
							foreach($app_info AS $k => $v)
							{
								$type_data[$v['_appid']] = $v['name'];
							}
						}
					{/code}       
					{template:form/search_source,app_uniqued,$type_default,$type_data,$type_style}
				</div>
				<div class="type-time type-item clear">
					{code}
						$time_css = $all_select_style;
						$time_css['show'] = 'time_item';
						$time_css['width'] = 157;
						$_INPUT['access_time'] = isset($_INPUT['access_time']) ? $_INPUT['access_time'] : 1;
                        if(isset($_configs['date_search']['other']))
                        {
                            unset($_configs['date_search']['other']);
                        }
					{/code}
					{template:form/search_source,access_time,$_INPUT['access_time'],$_configs['date_search'],$time_css}
				</div>
				<div class="type-item"><input type="text" name="k" placeholder="关键字搜索" value=""/></div>
				<div class="type-item">
					<input type="submit" class="view-button search" name="hg_search" value="搜索" />
					<span class="view-button save-set">保存为排行类型</span>
				</div>
				<div class="type-item set-item"></div>
				<input type="hidden" class="access_nums" name="access_nums" value="">
				<input type="hidden" name="a" value="get_content" />
			</form>
		</aside>
		<section class="section m2o-flex-one">
			<div class="m2o-list">
				<div id="infotip" class="ordertip">排序模式已关闭</div>
		        <div class="m2o-title m2o-flex m2o-flex-center">
		            <div class="m2o-item m2o-flex-one m2o-bt" title="标题">标题</div>
		            <div class="m2o-item m2o-sort" title="类型">类型</div>
		            <div class="m2o-item m2o-visit" title="访问">访问 <!--[<a class="sort_visit" attr="2">升序</a>/ <a class="sort_visit" attr="1">降序</a>/ <a class="sort_visit selected" attr="0">清除</a>]--></div>
		            <!-- <div class="m2o-item m2o-comment" title="评论">评论</div> -->
                    <div class="m2o-item m2o-time" title="发布时间">发布时间</div>
<!--		            <div class="m2o-item m2o-time" title="创建日期">访问时间</div>-->
		            <div class="m2o-item m2o-attention" title="关注">关注</div>
		        </div>
				 <div class="m2o-each-list">
			   	 </div>
			    <div class="m2o-bottom m2o-flex m2o-flex-center">
			    	<div class="m2o-item m2o-flex-one"></div>
					<div class="page_size"></div>
				</div>
		  	</div>
		</section>
	</div>
</div>

<!-- 已关注、系统推送模板 -->
<script type="text/x-jquery-tmpl" id="module-tpl">
<li class="module-item" data-id="${id}" data-cid="${cid}">
	<div class="title"><span>${nbundle}</span>${title}</div>
	<p class="vs-effet" style="width:${waccess} ">
		<label>${naccess}</label>
	</p>
<!--	<p class="cm-effet" style="width:${wcomment} ">-->
<!--		<label>${ncomment}</label>-->
<!--	</p>-->
	{{if ispush}}<em class="cancel">取消关注</em>{{/if}}
</li>
</script>

<script type="text/x-jquery-tmpl" id="nomodule-tpl">
<li class="nomodule-data">
	<p>暂无${sort}数据</p>
</li>
</script>

<!-- 信息列表模板 -->
<script type="text/x-jquery-tmpl" id="item-tpl">
<div class="m2o-each m2o-flex m2o-flex-center" data-id="${id}" orderid="${order_id}" data-cid="${cid}">
    <div class="m2o-item m2o-flex-one m2o-bt">
       <div class="m2o-title-transition max-wd">
    	 <a class="m2o-title-overflow" title="${title}" href="${content_url}">
    	 	{{if pic}}<img src="${pic}" style="margin-right:10px;" />{{/if}}
    	 	<span>${title}</span>
 		</a>
       </div>
    </div>
    <div class="m2o-item m2o-sort">${bundle_name}</div>
    <div class="m2o-item m2o-visit">${access_nums}</div>
    <!-- <div class="m2o-item m2o-comment">${comment_num}</div> -->
    <div class="m2o-item m2o-time"><span class="time">${publish_time}</span></div>
    <!-- <div class="m2o-item m2o-time"><span class="time">${update_time}</span></div> -->
    <div class="m2o-item m2o-attention"><a class="add" title="关注">关注</a></div>
</div>
</script>

<!-- 没有数据时 -->
<script  type="text/x-jquery-tmpl" id="noitem-tpl">
	<p class="common-list-empty">没有您要找的内容！</p>
</script>
{template:foot}