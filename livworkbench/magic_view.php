<?php

$res_path = 'res/magic_view/images/';

Class Resource {
	private $scripts = array();
	private $links = array();
	private $options = array(
		merge => true,
		cache_path => 'res/magic_view/cache/',
		res_path => 'res/magic_view/'
	);
	function __construct($options = array()) {
		if ($options) {
			$this->option($options);
		}
	}
	public function option($attr, $value = '') {
		if (!is_array($attr)) {
			$attrs = array();
			$attrs[$attr] = $value; 
		} else {
			$attrs = $attr;
		}
		foreach ($attrs as $key => $value) {
			$this->options[$key] = $value;
		}	
		return $this;
	}
	public function addJS($filename) {
		$this->add('js', $filename);
		return $this;
	}
	public function addCSS($filename) {
		$this->add('css', $filename);
		return $this;
	}
	public function add($type, $filename) {
		if (!$filename || !($type != 'css' || $type != 'js')) return $this;
		if (!is_array($filename)) {
			$filename = array($filename);
		}
		foreach ($filename as $v) {
			if ($type == 'css') {
				$this->links[] = $v;
			} else {
				$this->scripts[] = $v;
			}
		}
		return $this;
	}
	public function flush() {
		if ($this->options['merge']) {
			$this->merge_flush();
		} else {
			$this->normal_flush();
		}
		$this->links = array();
		$this->scripts = array();
		return $this;
	}
	private function merge_flush() {
		echo $this->merge();
		echo $this->merge('js');
		return $this;
	}
	private function merge($type = 'css') {
		if ($type == 'css') {
			$data = $this->links;
		} else if ($type == 'js') {
			$data = $this->scripts;
		}
		if (empty($data)) return '';
		$filename = '';
		$content = '';
		foreach ($data as $file) {
			$file = str_replace('/', '-_', $file);
			$filename .= "{$file}.{$type}.";
		}
		$filename = $this->options['cache_path'] . $filename . ".$type";
		if (!file_exists($filename) || $this->options['development']) {
			foreach ($data as $file) {
				$file = $this->options['res_path'] . "$type/$file" . ".$type";
				if (file_exists($file)) {
					$content .= file_get_contents($file);	
				}
			}
			
			file_put_contents($filename, $content);
		}  
		if ($type == 'css') {
			return '<link type="text/css" rel="stylesheet" href="' . $filename  . '">';
		} else {
			return '<script type="text/javascript" src="' . $filename . '"></script>';
		}
	}
	private function normal_flush() {
		$links = '';
		$scripts = '';
		foreach ($this->links as $file) {
			$file = $this->options['res_path'] . "css/$file" . '.css';
			$links .= '<link type="text/css" rel="stylesheet" href="' . $file . '">';
		}
		foreach ($this->scripts as $file) {
			$file = $this->options['res_path'] . "js/$file" . '.js';
			$scripts .= '<script type="text/javascript" src="' . $file . '"></script>';
		}
		echo $links;
		echo $scripts;
		return $this;
	}
}
?>
<!doctype html>
<html>
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
		<?php
		$obj = new Resource(array('development' => true, 'merge' => false));
		$obj->addCSS('reset');
		$obj->addCSS('app');
		$obj->addJS(array(
			'lib/jquery.min',
			'lib/jquery-ui-min',
			'lib/underscore',
			'lib/Backbone',
			'global',
			'config',
			'cell',
			'select_menu',
			'select_view',
			'cell_view',
			'bootstrap'
		));
		$obj->flush(); 
		?>
	</head>
	<body>
		<a href="" id="preview" style="position:fixed;top:5px;right:5px;z-index:1;" target="_blank">预览</a>
		<div id="shower_box">
			<iframe id="html_iframe"></iframe>
		</div>
		
		<div id="operate_box">
			<!-- <div class="option-iframe-back"></div> -->
			
			<div id="selectMenu">
				<div class="selectMenu-bg"></div>
				<svg class="" height="100" width="100" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg">
					<g class="">
						<g class="">
							<g index="0" class="">
								<image index="0" xlink:href="<?php echo $res_path; ?>attr.png" hover_href="<?php echo $res_path; ?>attr_hover.png" width="22" height="22" x="56" y="13" title="属性设置" />
								<path index="0" class="selectMenu-item-bg" d="M100,50 A50,50,0,0,0,25,6.698729810778071 L50,50 L100,50 Z"  title="属性设置" />
							</g>
							<g index="1" class="">
								<image index="1" xlink:href="<?php echo $res_path; ?>mode.png" hover_href="<?php echo $res_path; ?>mode_hover.png" width="22" height="22" x="10" y="39" title="样式选择" />
								<path index="1" class="selectMenu-item-bg" d="M25,6.698729810778071 A50,50,0,0,0,25,93.30127018922192 L50,50 L25,6.698729810778071 Z" title="样式选择" />
							</g>
							<g index="2" class="">
								<image index="2" xlink:href="<?php echo $res_path; ?>layout.png" hover_href="<?php echo $res_path; ?>layout_hover.png" width="22" height="22" x="56" y="64" title="布局选择" />
								<path index="2" class="selectMenu-item-bg" d="M25,93.30127018922192 A50,50,0,0,0,100,50 L50,50 L25,93.30127018922192 Z" title="布局选择" />
							</g>
						</g>
					</g>
					<circle class="selectMenu-center" r="13" cx="50" cy="50" stroke-width="0" stroke="#979797" fill="transparent"/>
				</svg>
			</div>
			
			<div id="selectSettings">
				<div class="select-cell-tabs">
					<div class="select-cell_mode-box select-cell-tab-item">
						<h3><span class="select-cell-back"></span> | <span class="title">样式选择</span></h3>
						
					</div>
					
					<div class="select-cell-attr-box select-cell-tab-item">
						
					</div>
				</div>
			</div>
		</div>

		<!-- start js模板区域 -->
		<script type="tpl" id="mode-list-tpl">
		<% if (obj.cell_mode) { %>
		<ul class="mode-list">
			<% _.each(cell_mode, function(v) { %>
			<li data-id="<%= v.id %>" data-type="cell_mode">
				<span><img /></span>
				<span><%= v.title %></span>
			</li>
			<% }); %>
		</ul>
		<% } else { %>
		<p>无样式</p>
		<% } %>
		</script>
		
		<script type="tpl" id="selectSettings-attr">
			<h3>
				<span class="select-cell-back">back</span> |
				<span><%= title %><% if (num) { %>(<%= num %>)<% } %></span>
			</h3>
		<% if ( obj.none ) { %>
			<p style="text-align:center;color:pink;font-size:20px;padding:10px;">无可编辑的属性</p>
		<% } else { %>
			<ul class="select-attr-list">
			<% if (obj.mode_param) { %>
				<% _.each(obj.mode_param, function(v) { %>
				<li data-id="<%= v.id %>" data-info='<%= JSON.stringify(v) %>' 
					class="select-attr-list-data-item select-attr-list-mode_param" data-type="mode_param">
					<label><%= v.name %>：
					<% if (v.type == 'text') { %>
						<input type="text" value="<%= v.default_value %>" />
					<% } else { %>
						<select>
						<% for (var i in v.other_value) { %>
							<option value="<%= i %>" <% if (i == v.default_value) { %>selected="selected"<% } %>>
							<%= v.other_value[i] %>
							</option>
						<% } %>
						</select>
					<% } %>
					</label>
				</li>
				<% }); %>
			<% } %>
			<% if (obj.all_data_source) { %>
				<li>
					<label>数据源：
						<select class="select-attr-data_source">
							<option value="0" <%if (data_source == 0) {%> selected <% } %>>无</option>
							<% for (var i in all_data_source) { %>
							<option value="<%= all_data_source[i].id %>" <%if (data_source == all_data_source[i].id) {%> { selected <%}%>>
								<%= all_data_source[i].name %>
							</option>
							<% } %>
						</select>
					</label>
				</li>
			<% } %>
			
			<li class="common-button-group">
				<a class="select-cell-yes-btn blue">保存</a>
				<a class="select-cell-no-btn gray">取消</a>
				<% if (typeof original_id != 'undefined' && original_id != '0') { %>
				<a class="select-cell-delete-btn gray">删除</a>
				<% } %>
				</li>
			</ul>
		<% } %>
		</script>
		<script type="tpl" id="template_input_param">
			<% if (obj) { %>
				<% _.each(obj, function(v, id) { %>
				<li data-id="<%= id %>" data-info='<%= JSON.stringify(v) %>' class="select-attr-list-data-item" data-type="input_param">
					<label><%= v.name %>：
					<% if (v.type == 'text') { %>
						<input type="text" value="<%= v.default_value %>" />
					<% } else { %>
						<select>
						<% for (var i in v.other_value) { %>
							<option value="<%= i %>" <% if (i == v.default_value) { %>selected="selected"<% } %>>
								<%= v.other_value[i] %>
							</option>
						<% } %>
						</select>
					<% } %>
					</label>
				</li>
				<% }); %>
			<% } %>
		</script>
		<!-- end js模板区域 -->
	</body>
</html>