jQuery(function($){
	(function($){
		$.widget('editor.editor_form',{
			options : {
				upload : '.upload-img',
        		file : '.upload-file',
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				var op = this.options,
					handlers = {};
				 handlers['click ' + op['upload'] ] = '_upload';
				 this._on( handlers );
				 this._initUpload();
			},
			
	        _upload : function(){
	        	var op = this.options,
		    		root = this.element;
		    	var input_file = root.find( op['file'] );
		    	input_file.click();
	        },
        
	        _initUpload : function(){
	        	var _this = this,
	        		op = this.options,
	        		url = './run.php?mid='+ gMid + '&a=upload&admin_id=' + gAdmin.admin_id + '&admin_pass=' + gAdmin.admin_pass;
	        		input_file = this.element.find( op['file'] );
	        	input_file.ajaxUpload({
	        		url : url,
	        		phpkey : 'Filedata',
	        		before : function( info ){
	        			_this._uploadBefore();
	        		},
	        		after : function( data ){
	        			_this._uploadAfter( data );
	        		}
	        	});
	        },
	        
	        _uploadBefore : function(){
	        	
	        },
	        
	        _uploadAfter : function( json ){
	        	data = json['data'];
	        	state = json['index'];
	        	id = data['id'];
	        	src = $.globalImgUrl(data, '160x120');
	        	$('#indexpic_url').attr('src', src).attr('_state',state);
	        	$('#indexpic').val(id);
	        },
        
		});
	})($);
	$('.common-form-main').editor_form();
});
