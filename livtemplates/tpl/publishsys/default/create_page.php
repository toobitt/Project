<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>发布</title>
	{csshere}
	{css:jquery-ui-min}
	{css:1}
</head>
<body>
	<div id="selectBlock">
		<ul class="block-list">
			<li data-block="1">区块1</li>
			<li data-block="2">区块2</li>
			<li data-block="2">区块3</li>
			<li data-block="2">区块4</li>
			<li data-block="2">区块5</li>
			<li data-block="2">区块6</li>
		</ul>
		
		<ul class="datasource-list">
			<li>数据源1</li>
			<li>数据源2</li>
			<li>数据源3</li>
		</ul>
	</div>
	<div id="displayArea">
		<div class="cont top"></div>
		<div class="clearfix mt15">
			<div class="cont cont-2"></div>
			<div class="cont cont-3"></div>
		</div>
		<div class="clearfix mt15">
			<div class="cont cont-4"></div>
			<div class="cont cont-5"></div>
			<div class="cont cont-6"></div>
		</div>
	</div>
</body>
<script type="tpl" id="selectTpl">
	<div>
		<p>请选择模板：</p>
		<ul>
			<li><label><input type="radio" name="tpl" value="1" />模板1</label></li>
			<li><label><input type="radio" name="tpl" value="2" />模板2</label></li>
			<li><label><input type="radio" name="tpl" value="3" />模板3</label></li>
			<li><label><input type="radio" name="tpl" value="4" />模板4</label></li>
		</ul>
	</div>
</script>
<script type="tpl" id="tpl1">
	<ul class="list1">
		<% _.each(data, function (item) { %>
		<li><a href="<%= item.href %>"><%= item.content %></a></li>
		<% }); %>
	</ul>
</script>
<script type="tpl" id="tpl2">
	<ul class="list2">
		<% _.each(data, function (item) { %>
		<li><a href="<%= item.href %>"><img src="<%= item.src %>" /><span><%= item.content %></span></a></li>
		<% }); %>
	</ul>
</script>
<script type="tpl" id="tpl3">
	<div class="slide_display_wrapper">
		<div class="slide_display">
			<ul>
				<li><img src="http://r3.sinaimg.cn/10260/2013/0206/dd/c/57377383/250x10000x100x0x0x1.jpg"><em>希拉里就钓鱼岛问题警告中国</em><p>希拉里就钓鱼岛问题警告中国...<strong>[详细]</strong></p></li>
				<li><img src="http://r3.sinaimg.cn/10260/2013/0206/48/0/11378603/250x10000x100x0x0x1.jpg"><em>中国不甩希拉里的废话</em><p>希拉里就钓鱼岛问题警告中国...<strong>[详细]</strong></p></li>
				<li><img src="http://r3.sinaimg.cn/10260/2013/0206/46/a/87377666/250x10000x100x0x0x1.jpg"><em>希拉里就钓鱼岛问题警告中国</em><p>希拉里就钓鱼岛问题警告中国...<strong>[详细]</strong></p></li>
			</ul>
		</div>
	</div>
</script>
<script type="tpl" id="tpl4">
<div class="long_slide_display_wrapper">
	<div class="long_slide_display">
		<ul class="conts">
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic2.png"><em>希拉里就钓鱼岛问题警告中国</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>中国不甩希拉里的废话</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic4.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic5.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>希拉里很是尴尬</em></li>
			<li><img src="../../livworkbench/m2o/tpl/tpl/lib/images/m2o/pic3.png"><em>希拉里很是尴尬</em></li>
		</ul>
	</div>
	<div class="ctrlBtn">
		<a class="ctrlPrev"><em></em></a>
		<a class="ctrlNext"><em></em></a>
	</div>
</div>
</script>
{jshere}
{js:jquery.min}
{js:jquery-ui-min}
{js:jqueryfn/jquery.switchable-2.0.min}
{js:underscore}
{js:publishsys/create_page}
<script>

</script>
</html>