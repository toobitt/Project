(function($){
    var officeInfo = {
		template : '' + 
				'<input type="file" class="office-file" name="file" accept="${msType}" style="display:none;"/>'  +
				'',
		flag_tpl : '' +
				'<div>' +
					'<p class="word-uploading">文档名：<strong>${name}</strong>。正在上传中，请稍等...</p>' +
				'</div>' +
				'',
		tip_tpl : '' +
				'<div class="office-message">' +
					'<span>${message}</span>' +
				'</div>' +
				'',
		css : '' +
				'.office-message{border:1px solid rgb(255, 154, 0);color:rgb(255, 154, 0);background:#fff;border-radius:2px;text-align:center;}' +
				'',
		tip_css : '' + 
				'.word-uploading{font-size:12px;font-weight:normal;color:#dedede;background:url(' + $.ueditor.pluginDir +'/office.png) no-repeat;border:1px solid #dedede;height:53px;line-height:53px;padding-left:45px;margin:0 15px;}' +
				'.word-uploading strong{color:#000;}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.office', $.ueditor.base, {
        options : {
	        msType : [
				        'application/x-zip-compressed',
				        'application/msword',
				        'application/vnd.ms-powerpoint',
				        'application/vnd.ms-excel',
				        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
				        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
				    ]
        },

        _create : function(){
            this._super();
            this._template( 'input_file_tpl', officeInfo, this.element, {msType : this.options.msType.join() } );
            this.editor_head = $(this.editor.document).find('head');
            this.input_file = this.element.find('.office-file');
        },

        _init : function(){
        	this._super();
        	this._initUpload();
        	this._initFlagCss();
        },
        
        
        _initUpload : function( option ){
        	var _this = this;
        	this.input_file.ajaxUpload({
        		url : _this.options.config['officeUrl'],
        		type : 'doc',
        		phpkey : 'file',
        		beforeSend : function( data ){
        			_this._uploadBefore( data );
        		},
        		after : function( json ){
        			_this._uploadAfter( json['data'] );
        		},
        		error : function( json ){
        			_this._serviceError( json );
        		}
        	});
        },
        
        _serviceError : function( data ){
        	if( data['error'] ){
	        	this._tip( data['error'] );
	        	$(this.editorBody).find('.word-uploading').remove();
	        	var _this = this;
	        	setTimeout(function(){
	        		_this.tip_dom.fadeOut();
	        	},2000);
        	}
        },
        
        _uploadBefore : function( data ){
        	var fileinfo = {},
        		flag_html = null;
        	fileinfo.name = data.file.name;
        	this._tip('文档上传中...');
        	$.template( 'flag_tpl', officeInfo.flag_tpl );
        	flag_html = $.tmpl( 'flag_tpl', fileinfo );
        	this.insertHtml( flag_html.html() );
        },
        
        _uploadAfter : function( data ){
        	this.tip_dom.remove();
        	if( data['error'] == true ){
        		this._uploadError();
        	}else{
        		this._tip( '文档上传成功！正在解析中...' );
        		this._parseWord( data );
        	}
        },
        _uploadError : function(){
        	this._tip( '文档解析出错！' );
        	$(this.editorBody).find('.word-uploading').remove();
        	var _this = this;
        	setTimeout(function(){
        		_this.tip_dom.fadeOut();
        	},2000);
        },
        _parseWord : function( data ){
        	var _this = this,
        		href = location.href;
            var url = href.substr(0, href.indexOf(location.pathname));
            url += (location.pathname.indexOf("livworkbench") != -1 ? "/livworkbench" : "") + "/";
            var doc_iframe = $("<iframe/>").attr("src", url + data["url"]).on({
                load : function(){
                    var body = $(this.contentWindow.document).find("body");
                    bodyImgNum = body.find("img").each(function(){
                        $(this).attr("src", url + data.path + $(this).attr("src"));
                    }).length;
                    _this.tip_dom.remove();
                    doc_iframe.off('load').remove();
                    _this._insertWord( body );
                    _this.tip_dom.remove();
                }
            }).css("display", "none").appendTo("body");
        },
        
        _insertWord : function( body ){
        	body.find('table[border]').css({
        		'border' : '1px solid #000'
        	});
        	var content = body.html(),
        		flag_dom = $(this.editor.document).find('.word-uploading');
        	flag_dom.replaceWith( content );
        	$.editorPlugin.get(this.editor, 'imglocal').imglocal('initLocal');
        },
        
        _tip : function( message ){
        	var offset = $('.edui-for-office').offset();
        	offset.top +=26;
        	$.template( 'tip_tpl', officeInfo.tip_tpl );
        	this.tip_dom = $.tmpl('tip_tpl',{ message : message }).css({
        		'z-index' : 10000,
        		position : 'absolute',
        		top : offset.top + 'px',
        		left : offset.left + 'px'
        	}).appendTo(this.element);
        },
        
        _initFlagCss : function(){
        	$('<style/>').attr('style', 'text/css').appendTo(this.editor_head).html(officeInfo.tip_css);
        },
        
        click : function(){
        	this.input_file.trigger('click');
        },

        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'office',
        title : '上传word文档',
        click : function(editor){
            $.editorPlugin.get(editor, 'office').office('click');
        }
    });
    


})(jQuery);