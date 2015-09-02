{template:head}
{css:common/common_form}
{css:2013/iframe_form}
{css:2013/m2o}
{css:vod_add}
{js:2013/keywords}
{js:hg_sort_box}
{js:common/common_form}
{js:underscore}
{js:Backbone}
{js:vod/batch_edit}
<style>



</style>

<div class="common-form-head news-outlink-head">
     <div class="common-form-title">
          <h2>编辑新增视频</h2>
          <div class="form-dioption-submit">
		      <input type="submit" name="sub" value="保存视频" class="common-form-save" />
		      <span class="option-iframe-back">关闭</span>
		  </div> 
	</div>
</div>
<div class="new_vod_edit_wrapper">
	<div id="new_vod_list" class="new_vod_list">
		<ul></ul>
		<div class="new_vod_list-overlay">
			<div class="play-box">
			</div>
			<a class="play-box-next">></a>
			<a class="play-box-prev"><</a>
			<a class="play-box-close"></a>
		</div>
	</div>
	
	<div id="edit_info_area">
		<div class="edit_info_area-inner">
		<form action="run.php?mid={$_INPUT['mid']}&a=update" method="post">	
			<div class="ul">
				
			</div>
			<input type="hidden" name="ajax" value="1" />
		</form>
		</div>
		<div class="edit-info-overlay">
		</div>
	</div>
</div>

<script type="tpl" id="new_vod_list_tpl">
	<div class="list-img">
		<img src="<%= $.createImgSrc(img, {width: 120, height: 90}) %>" />
	</div>
	<div class="m2o-flex-one">
		<h4><%= title %></h4>
	<% if (obj.comment) { %>
		<p title=<%= obj.comment %>>描述：<%= obj.comment %></p>
	<% } %>
	</div>
</script>

<script type="tpl" id="edit_info_area_tpl">
<ul>
<li><input class="input" name="title" value="<%= info.title %>" placeholder="添加视频标题" /></li>
<li><textarea name="comment" placeholder="添加视频描述"><%= info.comment %></textarea></li>
<li class="form-dioption-keyword clearfix" style="position:relative;">
    <span class="keywords-del"></span>
    <span class="form-item" _value="添加关键字" id="keywords-box" data-title="提取文章内容与标题为关键字">
        <span class="keywords-start">添加关键字</span>
        <span class="keywords-add">+</span>
    </span>
    <input name="keywords" type="hidden" value="{$keywords}" id="keywords"/>
</li>
<li><input name="subtitle" class="input" value="<%= info.subtitle %>" placeholder="设置副题" /></li>
<li><input name="author" class="input" value="<%= info.author %>" placeholder="作者" /></li>
</ul>
<input type="hidden" name="id" value="<%= info.id %>" />
</script>
</body>
</html>