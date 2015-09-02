{template:head}
{js:underscore}
{js:Backbone}
{js:publishsys/init_create_page}
{code}
$res_path = './res/magic_view/images/';
{/code}
<ul class="page-list">
 {foreach $formdata['set_type'] as $k => $v}
   <li>
   	<a href="./run.php?mid={$_INPUT['mid']}&a=search_cell&site_id={$formdata['site_id']}&page_id={$formdata['page_id']}&page_data_id={$formdata['page_data_id']}&content_type={$k}">
   	{$v}
   	</a>
   </li>
 {/foreach}
</ul>
 
<script type="tpl" id="selectSettings">
<div id="selectMenu">
	<div class="close-current-page" style="
    position: fixed;
    color: black;
    right: -100px;
	cursor: pointer;
	top: 0;
	right: 0;
	">关闭</div>
	<div class="selectMenu-bg"></div>
	<svg class="" height="100" width="100" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"> 
		<g class=""> 
			<g class=""> 
				<g index="0" class="">
					<image index="0" xlink:href="{$res_path}attr.png" hover_href="{$res_path}attr_hover.png" width="22" height="22" x="56" y="13" title="属性设置" /> 
					<path index="0" class="selectMenu-item-bg" d="M100,50 A50,50,0,0,0,25,6.698729810778071 L50,50 L100,50 Z"  title="属性设置" />
				</g>
				<g index="1" class=""> 
					<image index="1" xlink:href="{$res_path}mode.png" hover_href="{$res_path}mode_hover.png" width="22" height="22" x="10" y="39" title="样式选择" />
					<path index="1" class="selectMenu-item-bg" d="M25,6.698729810778071 A50,50,0,0,0,25,93.30127018922192 L50,50 L25,6.698729810778071 Z" title="样式选择" />
				</g>
				<g index="2" class=""> 
					<image index="2" xlink:href="{$res_path}layout.png" hover_href="{$res_path}layout_hover.png" width="22" height="22" x="56" y="64" title="布局选择" />
					<path index="2" class="selectMenu-item-bg" d="M25,93.30127018922192 A50,50,0,0,0,100,50 L50,50 L25,93.30127018922192 Z" title="布局选择" />
				</g>
			</g>
		</g>
		<circle class="selectMenu-center" r="13" cx="50" cy="50" stroke-width="0" stroke="#979797" fill="transparent"/>
	</svg>
</div>
<div class="select-cell-tabs">
	<div class="select-cell_mode-box select-cell-tab-item">
		<h3>
			<span class="select-cell-back">back</span>
			|
			<%= title %>
		</h3>
		<ul class="mode-list">
			<% _.each(cell_mode, function(v) { %>
			<li data-id="<%= v.id %>" data-type="cell_mode">
				<span><img /></span>
				<span><%= v.title %></span>
			</li>
			<% }); %>
		</ul>
	</div>
	<div class="select-cell-attr-box select-cell-tab-item">
	
	</div>
</div>
</script>
<script type="tpl" id="selectSettings-attr">
<h3>
	<span class="select-cell-back">back</span>
	|
	<%= title %><% if (num) { %>(<%= num %>)<% } %>
</h3>
<% if (obj.mode_param) { %>
<ul class="select-attr-list">
	<% _.each(mode_param, function(v) { %>
	<li data-id="<%= v.id %>" class="select-attr-list-data-item">
		<label><%= v.name %>：
			<% if (v.type == 'text') { %>
				<input type="text" value="<%= v.value || v.default_value %>" />
			<% } else { %>
				<select>
					<% for (var i in v.other_value) { %>
						<option <% if (v.other_value[i] == v.value) { %>selected="selected"<% } %>>
							<%= v.other_value[i] %>
						</option>
					<% } %>
				</select>
			<% } %>
		</label>
	</li>
	<% }); %>
	<li>
		<label>数据源：
			<select>
				<% for (var i in data_source) { %>
						<option value="<%= data_source[i].id %>">
							<%= data_source[i].name %>
						</option>
					<% } %>
			</select>
		</label>
	</li>
	<li class="common-button-group">
		<a class="select-cell-yes-btn blue">保存</a>
		<a class="select-cell-no-btn gray">取消</a>
		<% if (typeof original_id != 'undefined') { %>
		<a class="select-cell-delete-btn gray">删除</a>
		<% } %>
	</li>
</ul>
<% } else { %>
<p style="text-align:center;color:pink;font-size:20px;">无可编辑的属性</p>
<% } %>
</script>
</body>
</html>