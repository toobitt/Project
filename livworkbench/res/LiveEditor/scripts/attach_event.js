function AttachEvent(number, slide){
    this.number = number;
    this.slide = slide;
    this.editor = window['oEdit' + this.number];
    this.editorWindow = $('#idContentoEdit' + this.number)[0].contentWindow;
    this.box = null;
    this.option = null;
    this.init();
}
var attachIconClass = {
	doc: 'doc-icon',
	xls: 'xls-icon',
	txt: 'txt-icon',
	zip: 'zip-icon',
	rar: 'rar-icon'
};
jQuery.extend(AttachEvent.prototype, {
    init : function(){
		var self = this;
        self.slide.html(this.content());
        self.box = $('#edit-slide-attach' + this.number);
        self.box.on('set', function(event, data){
            var html = '',
            	code = [];
            $.each(data, function(i, n){
                html += '<div class="attach-item" materialId="' + n.material_id + '">' +
                '<div class="attach-item-logo ' + n.type + 'icon"></div>'+
                '<div class="attach-item-info">'+ n.filename + '<span class="attach-item-size">(' + n.size + ')</span>' +'</div>' +
                '</div>';
                code.push( n.code );
            });
            var content = $(this).find('.edit-slide-attach-content').html(html);
            content.append('<div class="attach-option"><span class="attach-option-del">删</span></div>');
            self.option = content.find('.attach-option');
            content.find('.attach-item').each( function( key, value ){
            	$(this).data('code', code[key]);
            } );
        }).on('mouseenter', '.attach-item', function(){
			if($(this).attr('_nooption')) {
                return;
            }
            !$(this).hasClass('current') && $(this).addClass('current');
            $(this).append(self.option.show());
        }).on('mouseleave', '.attach-item', function(){
            $(this).removeClass('current');
            self.option.hide();
        }).on('click.option', '.attach-option-del', function(event) {
			$(this).closest('.attach-item').trigger('delete');
			event.stopPropagation();
		}).on('click.info','.attach-item', function(){
			var info = $(this).data('code');
			window['globalSlideInsertHtml' + self.number]('attach', info);
		}).on('delete.item', '.attach-item', function() {
			self.box.find('.edit-slide-attach-content').append( self.option );
			$(this).animate({
				opacity : 0,
				height : 0
			}, 500, function(){
				$(this).remove();
			});
			var hidden = $( '#material_'+ $(this).attr('materialId') );
			if(hidden[0]){
				hidden.remove();
			}
		}).on('before.item', function(event, data){
            var html = '<div class="attach-item" _nooption="'+ data['index'] +'">' +
			'<img class="image-loading" src="' + RESOURCE_URL + 'loading2.gif"/>' +
            '<div class="attach-item-logo"></div>'+
            '<div class="attach-item-info">'+ data['filename'] + '<span class="attach-item-size">(' + data['size'] + ')</span>' +'</div>'+
            '</div>';
			var first = $(this).find('.edit-slide-attach-content').find('.attach-item:first');
			if( first.length != 0 ) {
				first.before(html);
			} else {
				$(this).find('.edit-slide-attach-content').append( html );
			}
        }).on('after.item', function(event, data){
        	var attachBox = $(this).find('.attach-item[_nooption="'+ data['index'] +'"]');
        	attachBox.find('.image-loading').remove();
			if( data.status == 0 ) {
				attachBox.html('上传失败：' + data.msg);
				attachBox.animate( {
					opacity : 0,
					height : 0
				}, 5000, function(){
					attachBox.remove();
				});
				return;
			}
			attachBox.removeAttr('_nooption').attr('materialId', data.id);
			attachBox.data('code', data.code);
			attachBox.find('.attach-item-logo').addClass( data.type + 'icon' ).next().html(data.filename + '<span class="attach-item-size">('+ data['size'] + ')</span>');
        });

		var index = 0;
        self.box.find(".attach-upload-button").on('change', function(event){
            var i, len = this.files.length, file, reader, formdata;
            for(i = 0; i < len; i++){
                file = this.files[i];
                if(file.type.match(/image.*/)){
                    continue;
                }
                (function(ii){
                	var name = file.name, size = file.size;
                    if(window.FileReader){
                        reader = new FileReader();
                        reader.onloadend = function(event){
                            var target = event.target;
                            self.box.trigger('before', [{
                                filename: name,
								size: size,
                                index : ii
                            }]);
                        };
                        reader.readAsDataURL(file);
                    }
                    if(window.FormData){
                        formdata = new FormData();
                        formdata.append('Filedata', file);
                        $.ajax({
                            url : gUrl.upload + "&admin_id=" + gAdmin.admin_id + "&admin_pass=" + gAdmin.admin_pass,
                            type : 'POST',
                            data : formdata,
                            processData : false,
                            contentType : false,
                            dataType : 'json',
							error: function() {
								self.box.trigger('after', [{
									status: 0 //表示失败
								}]);
							},
                            success: function(data){
                                data = data[0] || {};
								if( !data.success ) {
									self.box.trigger('after', [{
										index: ii,
										status: 0, 
										msg: data.error
									}]);
									return;
								}
                                self.box.trigger('after', [{
									status: 1,
                                    src : data['url'],
                                    filename: data['name'] || data['filename'],
									id: data['id'],
									size: data.filesize,
									type: data.type,
									code : data.code,
									index: ii
                                }]);
								$('.material-box').eq(0).append('<div id="material_'+ data['id'] +'">'+
                                    '<input type="hidden" name="material_id[]" value="'+ data['id'] +'" />'+
                                    '<input type="hidden" name="material_name[]" value="'+ data['filename'] +'"/>'+
                                '</div>');
                                $("#material_history").val(function(){
                                    var space = '', val;
                                    if(val = $(this).val()){
                                        space = ',';
                                    }
                                    return val + space + data['id'];
                                });
                            }
                        });
                    }
                })(index++);
            }

        });
    },
    content : function(){
        return '<div id="edit-slide-attach' + this.number +'" class="edit-slide-html-each">'+
        '<div class="edit-slide-title"><span class="edit-slide-close">关闭</span>附件管理</div>'+
        '<div class="edit-slide-button"><span class="edit-slide-button-item attach-upload">添加附件<input type="file" multiple class="attach-upload-button"/></span></div>'+
        '<div class="edit-slide-attach-brief">' + (window.attach_support || '') + '</div>' +
        '<div class="edit-slide-attach-content edit-slide-content"></div>'+
        '</div>';
    },
    set : function(json){
        this.box.trigger('set', [json]);
    },
    insertHTML : function(html){
        var self = this;
        var body = $(this.editorWindow.document.body);
        if(!body.data('init-attach-info')){
            body.on('dblclick', '.attach-info', function(){
                var src = $(this).attr('src');
                self.watchInfo(src);
            });
            body.data('init-attach-info', true);
        }
        this.editor.insertHTML(html);
        window['contentWindow' + this.number]('refresh');
    },
    statisctis : function(){
        var num = this.box.find('.attach-item').length;
        window['statistics' + this.number].attachNumber(num);
    }
});
