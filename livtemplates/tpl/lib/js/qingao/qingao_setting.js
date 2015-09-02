$(function() {
	$("#upload_background").click(function() {
		// 上传方法
		$.upload({
			// 上传地址
			url: 'manage.php', 
			// 文件域名字
			fileName: 'Filedata', 
			// 其他表单数据
			params: {a: 'upload_file',ajax:true,group_id:parseInt(gId),type:'background'},
			// 上传完成后, 返回json, text
			dataType: 'json',
			// 上传之前回调,return true表示可继续上传
			onSend: function() {
				return true;
			},
			// 上传之后回调
			onComplate: function(data) {
				var url = data.host + data.dir + '50x/' + data.filepath + data.filename + '?dsklg';
				$("#background_img").attr('src',url);
			}
		});
	});
	
	$("#upload_logo").click(function() {
		// 上传方法
		$.upload({
			// 上传地址
			url: 'manage.php', 
			// 文件域名字
			fileName: 'Filedata', 
			// 其他表单数据
			params: {a: 'upload_file',ajax:true,group_id:parseInt(gId),type:'logo'},
			// 上传完成后, 返回json, text
			dataType: 'json',
			// 上传之前回调,return true表示可继续上传
			onSend: function() {
				return true;
			},
			// 上传之后回调
			onComplate: function(data) {
				var url = data.host + data.dir + '50x/' + data.filepath + data.filename + '?dsklg';
				$("#logo_img").attr('src',url);
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
		if ($(this).val() == _v || $(this).val() == '')
		{
			$(this).val(_v);
		}
	});
});