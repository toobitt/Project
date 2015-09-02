$(function($) {
	var iframe = top.$('#formwin');
	
	var open = function() {
		localStorage['magic_data'] = JSON.stringify({
			url: $(this).attr('_href'),
			gMid: gMid,
			SCRIPT_URL: SCRIPT_URL,
		});
		/*$.get($(this).attr('href'), function(data) {
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
				gMid: gMid,
				SCRIPT_URL: SCRIPT_URL,
			});
			//iframe.attr('src', 'magic_view.php');
			if(data.template_content || data.template || '') {
				window.open('magic_view.php', '_blank');
			}
			else
			{
				alert('尚未部署模板!');
				return;
			}
			
		}, 'json');*/
	};
	$('.page-list').on('click', 'a', open);
	$('body').on('click', '.open_magic_view', open);
});