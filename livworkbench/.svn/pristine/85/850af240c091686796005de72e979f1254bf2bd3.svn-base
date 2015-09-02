window.onload = function () {
	var gData = JSON.parse(localStorage['magic_data']);
	$.getJSON(gData.url, function(data) {
		data = data[0];	
		if (typeof data != 'object') {
			alert('请求异常！');
			return;
		}
		//通过本地存储传递数据
		localStorage['magic_data'] = JSON.stringify({
			template: data.template_content || data.template || '',
			cells: data[0] || data.cell,
			data_source: data.data_source,
			cell_mode: data.mode || data.cell_mode,
			gMid: gData.gMid,
			SCRIPT_URL: gData.SCRIPT_URL
		});
		//iframe.attr('src', 'magic_view.php');
		if(data.template_content || data.template || '') {
			start();			
		}
		else
		{
			alert('尚未部署模板!');
			return;
		}
	});	
	function start() {
		var gData = JSON.parse(localStorage['magic_data']);
		gData.js_tpls = {
			selectSettings: document.querySelector('#selectSettings').innerHTML,
			"selectSettings-attr": document.querySelector('#selectSettings-attr').innerHTML
		};
		localStorage['magic_data'] = JSON.stringify(gData);
		var SCRIPT_URL = gData.SCRIPT_URL;
		var res = 
				'<link rel="stylesheet" type="text/css" href="res/magic_view/css/create_page.css" />' +
				'<script src="res/magic_view/js/jquery.min.js"></script>' +
				'<script src="' + SCRIPT_URL + 'jquery-ui-min.js"></script>' +
				'<script src="' + SCRIPT_URL + 'underscore.js"></script>' +
				'<script src="' + SCRIPT_URL + 'Backbone.js"></script>' + 
				'<script src="res/magic_view/js/magic_view_edit.js"></script>';
				
		var html = gData.template || '';
		html = html.trim();
		html = html.slice(0, -7) + res + '</html>';
		var newDoc = document.open();
		newDoc.write(html);
		newDoc.close();
	}
};