$(function(){
	(function(){
	var tpl = {
			modal : '' +
			'<div class="modal fade modal-pop in" id="upload-video" style="width:675px;  height: 440px;display:none;position:fixed;top:110px;left: 61%;z-index: 100001;">'+
				'<div class="modal-header">'+
					'<a class="videoclose"></a>'+
					'<h3 class="overhidden">上传视频</h3>'+
				'</div>'+
				'<div class="inner">'+
					'<div class="upload-title m2o-flex m2o-flex-center">' +
						'<p class="upload-file m2o-flex-one">' +
							'待上传<em class="num">0</em>个文件，总大小<em class="size">0KB</em>'+
						'</p>' +
						'<div class="video-btn btn btn-mul btn-blue loading">' +
							'添加视频文件' +
							'<div id="YunPlace"></div>' + 
						'</div>' +
					'</div>' +
					'<div class="panel panel-list">' +
						'<div class="panel-heading sys-flex sys-flex-center">' +
							'<div class="sys-item sys-flex-one list-title" title="文件名称">文件名称</div>' +
							'<div class="sys-item list-file" title="文件大小">文件大小</div>' +
							'<div class="sys-item list-progress"></div>' +
							'<div class="sys-item list-rate" title="操作"></div>' +
						'</div>' +
						'<div id="upload-queue" class="panel-body">' +
						'</div>' +
						'<div class="panel-footer sys-flex sys-flex-center">' +
							'<div class="sys-item sys-flex-one">' +
								'<div class="tip"></div>'+
							'</div>' +
							'<div class="sys-item list-config">' +
								'<button type="button" class="btn btn-mul btn-tolerant btn-cancel">取消</button>' +
								'<button type="button" class="btn btn-mul btn-operate sure-upload">上传</button>' +
							'</div>' +
						'</div>' +
					'</div>'+
				'</div>'+
			'</div>'+
			'<div class="modal-pop-mask"></div>',
			
	 };
	
	 $.widget('upyun.upyun', {
		 options : {
			 
		 },
        	

        _create : function(){
        	this.totalSize = 0;																	/*计数器 统计所有上传视频大小*/
        },

        _init : function(){
        	this.configdata = {
        		upload_url : gUploadApi.upload_url,
				file_size_limit : gUploadApi.size_limit,
				file_types : gUploadApi.file_types
        		
        	};
        	this.mask = $('.modal-pop-mask');
        	this._initFlash();
            this._on({
            	'click .videoclose' : '_hideVideoBox',
            	'click .btn-cancel' : '_quitUpload',
            	'click .sure-upload': '_startUploadVideo',
            	'click .list-del'   : '_delList'
            });
        },
        
        
        _initFlash : function(){
        	var _this = this,
        		modalBox = this.element;
        	if(!modalBox.find('object')[0]){
	   			$.getScript( SCRIPT_URL + 'jqueryfn/jqueryfn_custom/uploadify/jquery.uploadify.js', function(){
	   				var target = modalBox.find('#YunPlace');
		   		 	_this._flashReady( _this.configdata ); 
	   			}); 
   		 	}
        },
        
        _hideVideoBox : function(){
        	this.element.hide();
        	this._hideMask();
        },
        
        show : function(){
        	this.element.show();
        	this._showMask();
        },
        
        _showMask : function(){
        	var h = $('body').height();
        	this.mask.height(h).show();
        },
        _hideMask : function(){
        	this.mask.hide();
        },
        
        _quitUpload : function(){
        	var modalBox = this.element;
        	modalBox.hide();
	   		modalBox.find('.panel-body .sys-each').remove();
	   		modalBox.find("#YunPlace").uploadify('cancel','*'); 										/*清空队列*/
	   		this.totalSize = 0;						  													/*取消时计数器清零*/
	   		modalBox.find('.num').attr('_num', 0 ).html( 0 );
	   		modalBox.find('.size').attr('_size', 0 ).html( 0 );
	   		this._hideMask();
        },
        
        _startUploadVideo : function(){
        	this.element.find("#YunPlace").uploadify('upload');
        },
        
        _delList : function( event ){
        	var self = $( event.currentTarget ),
				item = self.closest('.sys-each');
			this._delback( item );
        },
        
        _flashReady : function( data ){
        	var modalBox = this.element,
				_this = this;
        	modalBox.find('#YunPlace').uploadify({
				auto 		  : false,
				debug    	  : false,
				width         : 125,
				height        : 34,
				swf           : SCRIPT_URL + 'jqueryfn/jqueryfn_custom/uploadify/uploadify.swf',   		/*flash*/
				uploader      : data.upload_url,															/*后台接受地址*/
				fileObjName : 'videofile',
				fileSizeLimit : data.size_limit,													/*视频尺寸限制*/
				fileTypeExts : data.file_types,												    /*视频类型限制*/
				formData : {access_token:gToken},
				removeTimeout : 30000,
				queueID : 'upload-queue',
				queueSizeLimit : 5,																		/*视频个数限制*/
				itemTemplate : '<div id="${fileID}" class="sys-each">' +
									'<div class="sys-line sys-flex sys-flex-center">' +
										'<div class="sys-item sys-flex-one list-title">' +
											'<div class="sys-title-transition max-wd">' +
												'<a class="sys-title-overflow" title="${fileName}">' +
												'<span>${fileName}</span>' +
												'</a>' +
											'</div>' +
										'</div>' +
										'<div class="sys-item list-file">' +
											'<div class="overhidden"><progress value="0" max="1"></progress></div>' +
										'</div>' +
										'<div class="sys-item list-progress">' +
											'<span class="instantly">0</span><em class="each-size" _size="${ori_size}">/${fileSize}</em>' +
										'</div>' +
										'<div class="sys-item list-set list-rate">' +
											'<span class="list-set list-del">删除</span>' +
										'</div>' +
									'</div>' +
								 '</div>',
				onSWFReady : function(){
					modalBox.find('.video-btn').removeClass('loading');
				},
				
				onSelect : function( file ){
					 var target = modalBox.find('#YunPlace');
					 _this.totalSize += file.size;
					var len = modalBox.find('.sys-each').length;
					modalBox.find('.num').attr( '_num', len ).html( len  );
					modalBox.find('.size').attr('_size', _this.totalSize ).html( _this._hg_format_num( _this.totalSize) );
				},
		
				onUploadComplete : function(){
					
				},
				
				onClearQueue : function(){
					modalBox.find('.uploadify').data('uploadify').queueData.files ={};  					/*清空队列*/
				},
				
				onQueueComplete : function( queueData ){
				},
				
				onUploadStart : function( file ){
					// modalBox.find("#YunPlace").uploadify('settings','formData',{
						// "policy": policy[file.name],
						// "signature" : signature[file.name]
					// });
				},
				
				onUploadSuccess : function(file, data, response){
					var data = JSON.parse( data ),
						fileName = file.name;
					var item = modalBox.find('#'+ file.id);
					if (fileName.length > 25) {
						fileName = fileName.substr(0, 25) + '...';
					}
					if( response ){
						if( $.isArray( data ) && data.length ){
							var index = file.index,
								current = modalBox.find( '#' + file.id );
							if( current.length ){
								current.addClass('over');
							}
							modalBox.find('.tip').addClass('success').text( fileName + '  上传成功!' );
							setTimeout(function(){
								current.remove();
								modalBox.find('.tip').removeClass('success').text('').slideDown();
								_this._countSzie( item );
								modalBox.find("#YunPlace").uploadify('upload');
								_this._hideVideoBox();
								_this._trigger('successCallback',null,[data]);
							},'2000');
						}else{
							if( data.ErrorCode || data.ErrorText ){
								var msg = data.ErrorCode ? data.ErrorCode : data.ErrorText;
								modalBox.find('.tip').addClass('error').text( fileName + '  上传失败' );
								setTimeout(function(){
									modalBox.find('.tip').removeClass('error').text('').slideDown();
									_this._countSzie( item );
									modalBox.find("#YunPlace").uploadify('upload');
								},'2000');
							}
						}
					}
				},
				
				onUploadError : function(file, errorCode, errorMsg, errorString) {
					var fileName = file.name;
					if (fileName.length > 25) {
						fileName = fileName.substr(0, 25) + '...';
					}
					modalBox.find('.tip').addClass('error').text( fileName + '上传失败！  ' + errorString );
					var current = modalBox.find('#' + file.id);
					setTimeout(function(){
						modalBox.find('.tip').removeClass('error').text('').slideDown();
						current.remove();
						_this._countSzie( current );
					},'2000');
		        },
				
				onUploadProgress : function(file, fileBytesLoaded, fileTotalBytes, queueBytesLoaded, uploadSize){
					var ratio = (fileBytesLoaded/fileTotalBytes).toFixed(2);
						current = modalBox.find('#' + file.id);
					current.find('progress').val( ratio );													/*进度条*/
					current.find('.instantly').html( _this._hg_format_num( fileBytesLoaded ) );
				}
			});	
        },
        
    	_delback : function( item ){																			/*删除操作*/
    		var modalBox = this.element,
    			id = item.attr('id');
    		modalBox.find("#YunPlace").uploadify('cancel',id);  											/*将删除掉的某一条数据清除出队列*/
    		/*会走flash的onUploadError方法，所以处理逻辑在onUploadError方法里面*/
    	 },
    	 
    	 _countSzie : function( item ){
    		 var modalBox = this.element,
 				 len = modalBox.find('.sys-each').length,
 				 allSize = modalBox.find('.size').attr('_size'),
 				 isize = item.find('.list-progress').find('em').attr('_size');
    		 this.totalSize -= isize;																				/*计数器减去改视频大小*/
    		 var size = (allSize - isize) > 0 ? allSize - isize : 0;
    		 modalBox.find('.num').attr('_num', len ).html( len );
    		 modalBox.find('.size').attr('_size', this.totalSize ).html( this._hg_format_num(this.totalSize) );
    	 },
    	 
    	 _hg_format_num : function(fileSize){																	/*视频大小转换*/
    		var size, unit;
    		if( (fileSize/1024) < 1024 ){
    			size = fileSize/1024;
    			unit = 'KB';
    		}else if( ((fileSize/1024) > 1024) && ((fileSize/(1024*1024)) < 1024) ){
    			size = fileSize/(1024*1024);
    			unit = 'MB';
    		}else{
    			size = fileSize/(1024*1024*1024);
    			unit = 'GB';
    		}
    		size = size.toFixed(2);
    		return size + unit;
    	 },
    	 
    	 _ajax : function( item , url , param , callback){
     		$.globalAjax( item, function(){
     			return $.getJSON( url , param , function( data ){
     				if( $.isFunction( callback ) ){
     					callback( data );
     				}	
     		    });
     		});
     	 },
        
	        
	 });
	 $('body').append( tpl.modal );
	 var yunBtn = $('.open-video-btn'),
 	 	modalBox = $('#upload-video');
	 $('#upload-video').upyun({
	 	successCallback : function(event,data){
	 		var data = data[0],
	 			video_id = data['id'],
	 			tran_server = data['tran_server'],
	 			host = tran_server['host'],
	 			port = tran_server['port'],
	 			indexpic = $.createImgSrc( data['img'],{width:160,height:160} );
	 		var indexpic_box = $('.indexpic-box');
	 		indexpic_box.find('img').attr('src',indexpic).show();
			indexpic_box.find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
			indexpic_box.addClass('hasimg');
			$('input[name="video"]').val( video_id );
			$('input[name="host"]').val( host );
			$('input[name="port"]').val( port );
	 		
	 	}
	 });
	 yunBtn.on('click' , function(){
		 $('#upload-video').upyun('show');
	 });
	})($);
});
