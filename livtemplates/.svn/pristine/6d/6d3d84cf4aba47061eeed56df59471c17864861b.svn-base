$(function(){
	(function($){
		$.widget('font.font_list',{
			options : {
				addFont : '',
				Url : ''
			},
			
			_create : function(){
				this.flag = true;
			},
			
			_init : function(){
				var op = this.options,
					_this = this;
				op.addFont.click(function(){
					$('.upload-file').click();
				});
				this.uploadFile = $( '.upload-file' );
				this.uploadFile.ajaxUpload({
					url : op.Url,
					phpkey : 'filedata',
					before : function( info ){
						_this._uploadBefore(info);
					},
					after : function( json ){
						if(_this.flag){
							_this._uploadAfter(json);
						}
					}
				});
			},
			
			_uploadBefore : function(info){
				var max = parseFloat(this.element.attr('_max')),
					cur = (info.file.size)/1024/1024;
				if(cur > max){
					alert('文件大小不得超过'+ max +'M');
					this.flag = false;
					return false;
				}else{
					this.flag = true;
				}
			},
			
			_uploadAfter : function(json){
				if(json.data['callback']){
             		eval(json.data['callback']);
             	}else{
					var data = json.data;
					var info = {};
					info.name = data.name;
					info.id = data.id;
					info.user_name = data.user_name;
					info.time = data.create_time;
					info.status = data.status;
					info.type = data.type;
					info.url = data.dir;
					$('#list-tpl').tmpl(info).insertBefore($('.m2o-each-list .m2o-each:eq(0)'));
             	}
			},
		});
	})($);
	$('.common-list-content').font_list({
		addFont : $(parent.$('body').find('.add-font-pack')).length ? $(parent.$('body').find('.add-font-pack')) : $('.add-font-pack'),
		Url : "run.php?mid=" + gMid + '&a=create&ajax=1',
	});
});




