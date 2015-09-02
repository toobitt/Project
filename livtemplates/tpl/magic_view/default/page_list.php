{template:head}
{js:underscore}
{js:Backbone}
{js:publishsys/init_create_page}
{code}
$res_path = './res/magic_view/images/';

if(!class_exists('column'))
{
    include_once(ROOT_DIR . 'lib/class/column.class.php');
    $publish = new column();
}
//获取所有站点
$hg_sites = $publish->getallsites();
//hg_pre($cell_list);
{/code}
<style>
html,body,.list {height:100%;}
.page-menu {width:200px;float:left;border-right:1px solid green;cursor:default;overflow:auto;}
#cell_form_wrap {overflow:hidden;height:100%;}
#cell_form {display:block;width:100%;height:100%;}
.page-menu li{height: 40px;line-height: 40px;border-bottom: 1px solid #d8d8d8;text-indent: 20pt;}
.page-menu{width:200px;overflow:hidden;}
.page-menu-inner{width:2000px;transition:all .5s;}
.page-menu-each{float:left;width:200px;}
</style>
<div class="page-menu">
	<div class="page-menu-inner">
		<div class="page-menu-each">
			<ul>
				{foreach $hg_sites as $id => $name}
				<li data-id="{$id}">
					<a>{$name}</a>
					<a class="next">></a>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
</div>
<div class="list">
	<ul class="page-list">
		<li><a>首页</a></li>
	</ul>	
</div>


<script type="tpl" id="selectSettings">
<div id="selectMenu">
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
<script type="tpl" id="pageMenuTpl">
<div class="page-menu-each">
	<% if (name) { %>
	<div class="back">
		<a><%= name %></a>
	</div>
	<% } %>
	<% if (data) { %>
	<ul>
		<% _.each(data, function(v) { %>
		<li data-id="<%= v.id %>">
			<a><%= $name %></a>
			<% if (!is_last) { %>
			<a class="next">></a>
			<% } %>
		</li>
		<% }); %>
	</ul>
	<% } %>
</div>
</script>
<script>
var NodeTree = (function() {
	var Klass = function(options) {
		this.el = options.el;
		this.listEl = this.el.find('.page-menu-inner');
		this.listContent = this.el.find('.publish-content');
		this.depth = options.depth || 0;
		this.options = $.extend({}, this.options, options);
		this.htmlCache = {
			cache: {},
			set: function(id, data) { this.cache[id] = data; },
			get: function(id) { return null;//this.cache[id]; 
			}
			
		};
		this.ajaxid = 0;
		this.bindDomEvents();
	};
	$.extend(Klass.prototype, {
		//绑定dom事件
		bindDomEvents: function() {
			var _this = this;
			this.el
			.on('click', '.next', $.proxy(this.upDepth, this) )
			.on('click', '.back', $.proxy(this.downDepth, this))
			.on('click', '.publish-each li', function(e) {
				if ( $(e.target).is('.publish-child') ) return;
				_this.beingCurrent($(this).children());
				var id = $(this).data('id');
				_this.get_block(id);
			});
		},
		options: {
			depth: 0,
			eachWidth: 200,
			maxShow: 1,
			nodeapi: 'get_block.php'
		},
		//调整下，让当前深度的可视
		adjustView: function() {
			this.listEl.css({
				'margin-left': -(this.depth + 1 - this.options.maxShow) * this.options.eachWidth
			});
		},
		beingCurrent: function(anchor) {
			anchor.closest('li').addClass('open').siblings().removeClass('open');
		},
		//增加深度
		upDepth: function(e) {
			var btn = $(e.currentTarget),
			    id = btn.data('id'),
			    name = btn.data('name'),
			    html = this.htmlCache.get(id),
			    needRequest = !html;
			this.beingCurrent(btn);
			this.removeNeedless();
			this.depth += 1;
			if (!html) {
				html = this.pageMenuTpl({
					name: name,
					data: null
				});
			}
			this.listEl.append(html);
			this.adjustView();
			this.displayCurNode(true);
			
			if (!needRequest) return;
			var depth = this.depth;
			var _ajaxid = ++this.ajaxid;
			var _this = this;
			$.get(this.options.nodeapi, {
				fid: id
			}, function(html) {
				_this.htmlCache.set(id, html);
				html = $.tmpl(publis_li_tpl, html);
				//深度和请求都没改变，将请求到的html放进dom
				if (_ajaxid == _this.ajaxid && depth == _this.depth) {
					_this.listEl.find('.publish-each:last').find('ul').html(html).end().removeClass('publish-wait');
					_this.displayCurNode(true);
				}
			}, 'json');
		},
		//减小深度
		downDepth: function() {
			this.depth -= 1;
			this.adjustView();
			this.displayCurNode();
		},
		//改变右侧的nodeFrame的链接，以显示当前的node
		displayCurNode: function(first) {
			
		},
		removeNeedless: function() {
			this.el.find('.each-node:gt(' + this.depth + ')').remove();
		},
		pageMenuTpl: _.template( $('#pageMenuTpl').html() )
	});
	return Klass;
})();
new NodeTree({
	el: $('.page-menu'),
	nodeapi: 'run.php?mid='+gMid
});
</script>
</html>