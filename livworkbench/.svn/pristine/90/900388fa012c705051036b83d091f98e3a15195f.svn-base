var log = function() { if (console) console.log.apply(console, arguments); };

(function () {
	
	window.App = window.App || {};
	
	var data = JSON.parse(localStorage['magic_data']);
	
	App.gMid = data.gMid;
	$.get(data.url, function(serverData) {
		try { 
			serverData = JSON.parse(serverData);
			serverData = serverData[0];
			if (typeof serverData != 'object') {
				alert('请求异常！');
				return;
			}
			App.serverData = {
				template: serverData.template_content || serverData.template || '',
				cells: serverData[0] || serverData.cell,
				data_source: serverData.data_source,
				cell_mode: serverData.mode || serverData.cell_mode,
				preview_href:serverData.preview_href,
				site_id:serverData.site_id,
				page_id:serverData.page_id,
				page_data_id:serverData.page_data_id,
				content_type:serverData.content_type,
			};
			$(dataCome);
		} catch(e) {
			alert('请求异常！');
		}
	});
	
	function dataCome() {
		var data, iframe, html, newDoc, loaded;
		
		data = App.serverData;
		var url = './magic_preview.php?a=show&site_id='+data.site_id+'&page_id='+data.page_id
			+'&page_data_id='+data.page_data_id+'&content_type='+data.content_type;
		$("#preview").attr('href',url);
		
		iframe = $('#html_iframe')[0];
		html = data.template || '';
		html = html.trim();
		if (html) {
			newDoc = iframe.contentDocument.open();
			newDoc.write(html);
			newDoc.close();
			
			$(iframe.contentDocument).ready(start);
			
		} else {
			alert( App.config.message.template_empty )
		}
		
		loaded = false;
		function start() {
			// ff会触发两次这个不知道为什么
			if (!iframe.contentDocument.getElementsByTagName('body').length || loaded) return;
			
			//var cells, cells_view;
			setTimeout(function() {
				App.trigger('CellView:position');
			}, 1000);			
			loaded = true;
			cells = new App.Cells(null, { url: 'run.php?mid=' + App.gMid });
			cells_view = new App.CellsView({ collection: cells });
			new App.SelectMenu;
			new App.SelectView({ cells_view: cells_view, data: data });
			try { 
				cells.add(data.cells);
			} catch(e) {
				var msg = e.msg;
				msg += '\n无法匹配' + e.detail;
				alert(msg);
			}
			App.trigger('CellView:position');
			setTimeout(function() {
				App.trigger('CellView:position');
			}, 500);
		}
	}
	
})();



