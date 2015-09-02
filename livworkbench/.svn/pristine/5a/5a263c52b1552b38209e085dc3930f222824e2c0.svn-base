(function($){
    var attachInfo = {
		template : '' + 
				'<div class="editor-upload-area">' + 
					'<span class="upload-btn">添加附件</span>' + 
					'<p>pdf,txt,zip,docx,doc</p>'+
					'<input type="file" multiple class="upload-file" />' + 
				'</div>' + 
				'<div class="attach-area">' + 
					'<ul class="editor-content-area editor-attach-content-area">' +
					'</ul>' + 
				'</div>' + 
				'',
		item_tpl : '' + 
				'<li class="item-box m2o-flex m2o-flex-center" _id="${id}">' + 
					'<div class="attach-logo attach-${type}"></div>' +
					'<div class="attach-info m2o-flex-one"><span class="name">${name}</span><span class="size">(${filesize})</span></div>' +
					'<div class="attach-option">' +
						'<div class="attach-option-del"></div>' + 
					'</div>' + 
					'<input type="hidden" name="material_id[]" value="${id}" />' + 
				'</li>' + 
				'',
		css : '' + 
			'.attach-area{overflow-y:auto;overflow-x:hidden;}' +
			'.ump-box .editor-upload-area{position:absolute;bottom:0;left:10px;cursor:pointer;text-decoration: underline;}'+
			'.editor-upload-area p{text-align:center;margin-top:5px;color:#333;font-size:12px;}'+
			'.editor-upload-area{margin:10px 2px; font-size:14px; color:#5b98d1;}' + 
			'.editor-attach-content-area{margin: 0 10px;}' + 
			'.editor-inner-box{position:relative; padding:30px 10px; border-bottom:1px solid #e7e7e7;}' +
			'.attach-area .item-box{position:relative; padding:15px 0;border-bottom:1px solid #e7e7e7;overflow:hidden;cursor: pointer;}' + 
			'.attach-area .item-box:hover{background:#f0eff5;}' + 
			'.upload-file{display:none;}' + 
			'.editor-slide-box .upload-btn{cursor:pointer;display:block;border:1px dashed #d2d3d5;color:#9c9c9c;font-size:14px;width:95px;height:31px;line-height:31px;margin:0 auto;padding-left:64px;background:url('+$.ueditor.pluginDir+'/slide-button.png) no-repeat 40px 8px #f5f6f8;}'+
			'.attach-area .slidebtn{display: block;border: 1px dashed #d2d3d5;color: #9c9c9c;font-size: 14px;width: 95px;height: 31px;line-height: 31px;margin: 0 auto;padding-left: 64px;background: url('+$.ueditor.pluginDir+'/slide-button.png) no-repeat 40px 8px #f5f6f8;}'+
			'.attach-area .slidebtn:hover{color: #9fb7d8;border-color: #bbd2e3;background-color: #f2f9ff;background-image: url('+$.ueditor.pluginDir+'/slide-button-hover.png);}'+
			'.attach-area .attach-logo{width:24px;height:24px;margin:0 8px;background:url() no-repeat center;background-size:24px 24px;}' +
			'.attach-area .attach-doc,.attach-area .attach-docx{background-image:url('+$.ueditor.pluginDir+'/attach/doc-2x.png);}' +
			'.attach-area .attach-zip{background-image:url(./res/ueditor/third-party/m2o/images/attach/zip-2x.png)}' + 
			'.attach-area .attach-txt{background-image:url(./res/ueditor/third-party/m2o/images/attach/txt-2x.png);}' +
			'.attach-area .attach-pdf{background-image:url(./res/ueditor/third-party/m2o/images/attach/pdf-2x.png);}' +
			'.attach-area .attach-info{overflow:hidden;}' + 
			'.attach-area .size{color:#ababab;}' + 
			'.attach-area .attach-option{display:none;width:24px;height:24px;position:absolute;right:0;bottom:0;}' +
			'.attach-area .item-box:hover .attach-option{display:block;}' + 
			'.attach-area .attach-option-del{cursor:pointer;height:100%;background:url('+$.ueditor.pluginDir+'/del.png) #ccc no-repeat center;}' + 
			'.attach-area .attach-option-del:hover{background-image:url('+$.ueditor.pluginDir+'/del_hover.png);}' + 
			'.attach-area .del-transition{height:0;margin:0 auto;padding:0;border:0;}' + 
			'.ump-inner .editor-attach-content-area{min-height:176px}' +
			'',
		cssInited : false
    };

    $.widget('ueditor.attach', $.ueditor.baseWidget, {
        options : {
        	index : true,
        	title : '附件管理',
        },

        _create : function(){
            this._super();
            this._template('attach-template', attachInfo,  this.body);
            $.template('item_tpl',attachInfo.item_tpl);
            this.slide && this.element.find('.upload-btn').addClass('slidebtn');
        },

        _init : function(){
        	var op = this.options,
        		handlers = {};
        	this.content = this.element.find( '.editor-content-area' );
            this._super();
            this._on({
            	'click .upload-btn' : '_upload',
            	'click .attach-option-del' : '_delete',
            	'click .item-box' : '_insertAttach'
            });
            this._initInputFile();
            var hei = this.element.height()- this.title.height()- this.element.find('.editor-upload-area').outerHeight( true );
            this.element.find('.attach-area').height( hei );
            var _this = this;
            if( typeof attachList == 'undefined' ){
            	return;
            }
            if( attachList.length ){
            	$.each( attachList, function(k, v){
            		var obj = {
            				data : v,
            				index : k+1
            		};
            		_this._uploadAfter(obj);
            	} );
        	}
        },
        
        _initInputFile : function(){
        	var _this = this,
        		op = this.options,
        		url = op.config['uploadUrl'],
        		input_file = this.element.find( '.upload-file' );
	        var acceptType = [	
	                          'text/plain',
        	                  'application/pdf',
        	                  'application/zip',
        	                  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        	                  'application/msword' ];
	        input_file.attr('accept', acceptType.join() );
        	input_file.ajaxUpload({
        		url : url,
        		phpkey : 'Filedata',
        		type : 'attach',
        		after : function( json ){
        			_this._uploadAfter( json );
        		}
        	});
        },
        
        _upload : function(){
        	var op = this.options,
	    		root = this.element;
	    	var input_file = root.find( '.upload-file' );
	    	input_file.click();
        },
        
        _uploadAfter : function( json ){
        	var data = json['data'],
        		op = this.options;
        	if(data['error']){
        		this.element.find('.upload-btn').myTip({
					string : data['error'],
					delay : 1000,
					color : '#6ba4eb'
				});
        	}else{
            	this.content.find( '.editor-inner-box' ).remove();
            	var dom = $.tmpl('item_tpl', data).appendTo(this.content);
            	if( this.editorOp.needCount ){
            		$('.editor-statistics-item[_type="attach"]').find('span').text( this.element.find('.item-box').length );
            	}
            	this._recordattachInfo(data);
        	}
        },
        _insertAttach : function( event ){
        	var self = $(event.currentTarget),
        		id = self.attr('_id'),
        		code = this.attachCollection[id]['code'];
        	this.insertHtml( code );
        },
        _recordattachInfo : function( data ){
        	this.attachCollection = this.attachCollection || {};
        	var id = data['id'];
        	this.attachCollection[id] = data;
        },
        
        
        _delete : function( event ){
        	event.stopPropagation();
        	var self = $(event.currentTarget),
        		item = self.closest( '.item-box' ),
        		id = item.attr('_id'),
        		_this = this;
        	item.slideUp(function(){
        		item.remove();
        		if( _this.editorOp.needCount ){
        			$('.editor-statistics-item[_type="attach"]').find('span').text( _this.element.find('.item-box').length );
        		}
        	});
        	this._asyncDelmaterialId( id );
        },
        
        /*同步删除页面隐藏域中的素材id*/
       _asyncDelmaterialId : function( id ){
       		$('input[_id="' +  id +'"]').remove();
       },
        
        _destroy : function(){

        }
    });

    $.ueditor.m2oPlugins.add({
        cmd : 'attach',
        title : '附件管理',
        click : function(editor){
            $.editorPlugin.get(editor, 'attach').attach('show');
        }
    });
    


})(jQuery);