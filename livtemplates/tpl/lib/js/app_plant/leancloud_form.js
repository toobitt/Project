$(function(){
	var wrapForm = $('.wrap-form');
	$('.wrap-form-submit').click(function(){
		wrapForm.submit();
	});
	$('.submit-btn').click(function(){
		var btn = $(this),
			form = $(this).closest('form');
		form.ajaxSubmit({
			success : function( json ){
				if( json.error ){
					btn.myTip({
						string : json.error,
						color : red
					});
					return;
				}
				var key = '';
				for( var i in json.cert_file_path ){
					key = i;
				}
				wrapForm.find('.master_key').val( json.master_key );
				wrapForm.find('.prod').val( key );
				wrapForm.find('.certfile_name').val( json.cert_file_path[ key ] );
				btn.myTip({
					string : '提交成功'
				});
			},
			error : function( data ){
				var json = JSON.parse( data.responseText ),
					len = json.error.length;
				btn.myTip({
					string : json.error,
					delay : 2000,
					color : 'red',
					width : len*14
				});
			}
		});
	});
});