$(function() {
	$("#upload_img").click(function() {
		// 上传方法
		$.upload({
			// 上传地址
			url: 'activitys.php', 
			// 文件域名字
			fileName: 'Filedata', 
			// 其他表单数据
			params: {a: 'material'},
			// 上传完成后, 返回json, text
			dataType: 'json',
			// 上传之前回调,return true表示可继续上传
			onSend: function() {
				return true;
			},
			// 上传之后回调
			onComplate: function(data) {
				var html = '<img src='+data.url+' width="50" height="50" />';
				$('#show_img').html(html);
				$('<input>', {
					'type' : 'hidden',
					'name' : 'id',
					'value' : data.id
				}).appendTo($('#actionForm'));
				$('<input>', {
					'type' : 'hidden',
					'name' : 'host',
					'value' : data.host+data.dir
				}).appendTo($('#actionForm'));
				$('<input>', {
					'type' : 'hidden',
					'name' : 'filepath',
					'value' : data.filepath+data.filename
				}).appendTo($('#actionForm'));
			}
		});
	});
	
	var _v = $('input[name="action_title"]').val();
	$('input[name="action_title"]').focus(function() {
		if ($(this).val() == _v)
		{
			$(this).val('');
		}
	}).blur(function() {
		if ($(this).val() == '')
		{
			$(this).val(_v);
		}
	});
});