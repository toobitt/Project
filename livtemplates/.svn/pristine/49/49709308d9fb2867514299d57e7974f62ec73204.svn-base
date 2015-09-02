{template:head}
{css:2013/list}
{css:tv-play}
{js:underscore}
{js:Backbone}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_cache}
{js:common/record}
{js:common/record_view}
{js:common/publish_box}
{js:common/ajax_upload}
{js:tv_play/tv_play}
<script type="text/javascript">
function hg_audit_play(id)
{
	var url = "run.php?mid="+gMid+"&a=audit&id="+id;
	hg_ajax_post(url);
}

/*审核回调*/
function hg_audit_play_callback(obj)
{
	var obj = eval('('+obj+')');
	$('#status_'+obj.id).text(obj.status);
}
jQuery(function(){
	$(".serach-btn").click(function(){$(".key-search").toggleClass("key-search-open");});
});
</script>

<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div style="display:none">
	{template:unit/play_search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a href="run.php?mid={$_INPUT['mid']}&a=form&infrm=1" target="formwin" class="button_6">新增电视剧</a>
	</div>
</div>

<div class="tv-wrap">
 <form method="post" action="" name="vod_sort_listform">
  <ul class="tv-list play-list clear">
  	{if $list}
	   {foreach $list as $k => $v}
	     	{code}
  		  	   $full = $v['update_status'] == $v['playcount'] ? true : false;
  		  	{/code}
		<li _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  			<img _src="{$v['img']}" alt="电视剧简介" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$v['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  			<h4 title="{$v['title']}"><span class="m2o-common-title">{$v['title']}</span><span class="num"><em class="updata-num">{$v['update_status']}</em>/<em class="total-num">{$v['playcount']}</em>集</span></h4>
  			<div class="tv-status"><label>状态: </label><span _id="{$v['id']}"  _status="{$v['status']}" class="reaudit" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</span></div>
  			<div class="tv-endtime"><label>到期时间: </label>
  				<span>{if $v['copyright_limit']}{$v['copyright_limit']}{else}永久有效{/if}</span>
			</div>
			<div class="tv-sort"><label>分类: </label><span>{if $v['play_sort_name']}{$v['play_sort_name']}{else}暂无分类{/if}</span></div>
  			<div class="tv-adduser"><label>添加人: </label><span>{$v['user_name']}</span></div>
  			<div class="tv-addtime"><label>添加时间: </label><span>{$v['create_time']}</span></div>
  		  </div>
  		  <a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" class="linking">&nbsp;</a>
  	     </div>
		 <span class="tv-publish"  _id="{$v['id']}">签发</span>
  	     <a class="del"></a>
     	</li>
	   {/foreach}
	{else}
	  <p style="color:#da2d2d;text-align:center;font-size:20px;line-height:20px;font-family:Microsoft YaHei;">没有您要找的内容！</p>
	{/if}
  </ul>
  <div class="tv-bottom m2o-flex m2o-flex-center">
  	 <div class="tv-operate">
  	 	<input type="checkbox" name="checkall" id="checkAll" />
  	    <a name="state" data-method="audit" class="bataudit">审核</a>
  	    <a name="back" data-method="back" class="batback">打回</a>
  	    <a name="batdelete" data-method="delete" class="batdelete">删除</a>
  	 </div>
  	 <div class="m2o-flex-one">
  	 {$pagelink}
  	 </div>
  </div>
  </form>
</div>
 <div id="infotip"  class="ordertip"></div>
 <div class="prevent-go"></div>
 
 
 <!-- 签发框 -->
<div id="vodpub" class="common-list-ajax-pub">
	<div class="common-list-pub-title">
		<p>正在发布</p>
		<div>
			<p class="overflow">标题</p><span>共1条</span>
			<div>
				<p>标题</p>
			</div>
		</div>
	</div>
	<div id="vodpub_body" class="common-list-pub-body">
		<form name="recommendform" id="recommendform" action="run.php" method="post" class="form" onsubmit="return hg_ajax_submit('recommendform');">
			{template:unit/publish}
			<input type="hidden" name="a" value="publish">
			<input type="hidden" name="ajax" value="1">
			<input type="hidden" name="mid" value="{$_INPUT['mid']}">
			<input type="hidden" name="id" value="${id}">
			<div><span class="publish-box-save">保存</span></div>
		</form>
	</div>
	<span onclick="hg_vodpub_hide();"></span>
</div>
{template:foot}
 <script>
 var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
$(function() {
	window.App = Backbone;
	var Records = window.Records;
	var RecordsView = window.RecordsView;
	var Publish_box = window.Publish_box;
	
	recordCollection = new Records;
	recordsView = new RecordsView({ el: $('.tv-each').parent(), collection: recordCollection });
    recordCollection.add($.globalListData);
 	
 	if (Publish_box) { 
 	
 		new Publish_box({
            el: $('#vodpub'),
            plugin: 'hg_publish',
            pluginOptions: {
            	maxColumn: 3
            },
            initialized: function(view) {
            	App.on('openColumn_publish', view.open, view);
            	App.on('batch:column_publish', view.openForBatch, view);
            }
        });
	}
});
</script>