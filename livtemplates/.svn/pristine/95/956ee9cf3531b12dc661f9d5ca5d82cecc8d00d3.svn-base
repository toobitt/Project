 {code}
 	$cell = array_values($formdata['cell']);
 	$cell_mode = array_values($formdata['cell_mode']);
 	$data_source = array_values($formdata['data_source']);
 	$site_id = $formdata['site_id'];
 	$page_id = $formdata['page_id'];
 	$page_data_id = $formdata['page_data_id'];
 	$content_type = $formdata['content_type'];
 	//hg_pre($data_source);
 {/code}
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>发布</title>
	{csshere}
	{css:jquery-ui-min}
	{css:create_page}
</head>
<body>
	{if !$cell}
		<h1>没有您要找的内容！</h1>
	{else}
	<div id="selectBlock">
		<div>
			<h2>样式</h2>
			<ul class="mode-list">
			{foreach $cell_mode as $k => $v}
				<li data-id="{$v['id']}" data-type="cell_mode">{$v['title']}</li> 
			{/foreach}
			</ul>
		</div>
		<div class="data_source_box">
			<h2>数据源</h2>
			<ul class="data_source-list">
			{foreach $data_source as $k => $v}
				<li data-id="{$v['id']}" data-type="data_source">{$v['name']}</li>
			{/foreach}
			</ul>
		</div>
	</div>
	<div id="displayArea">
	{foreach $cell as $k => $v} 
		<div class="cont mt15 one-cell" data-id="{$v['id']}"  id="cell{$v['id']}"></div>
	{/foreach}
	</div>
	{/if}
</body>
<script type="tpl" id="cell_tpl">
<p>单元：<%= cell_name %></p>
<p>
	<% if (!cell_mode) { %>
	未关联样式
	<% } else { %>
	已关联样式：<%= cell_mode %>
	<% } %>
</p>
<p>
	<% if (!data_source) { %>
	未关联数据源
	<% } else { %>
	已关联数据源：<%= data_source %>
	<% } %>
</p>
</script>
<script>
gData = {};
gData['cell'] = {code}echo json_encode($cell);{/code};
gData['cell_mode'] = {code}echo json_encode($cell_mode);{/code};
gData['data_source'] = {code}echo json_encode($data_source);{/code};
</script>
{jshere}
{js:jquery.min}
{js:jquery-ui-min}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:underscore}
{js:Backbone}
{js:publishsys/create_page}

</html>

