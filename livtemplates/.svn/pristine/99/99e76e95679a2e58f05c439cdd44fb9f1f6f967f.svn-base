$(function(){
	(function($){
	var linkvideoInfo = {
		template : ''+
			'<div class="input-group">' + 
				'<input type="text" name="video_url" class="form-control" placeholder="贴入视频地址，支持土豆，优酷，乐视等网站">' + 
				'<span class="input-group-btn">' + 
					'<button class="btn btn-default extract-btn" type="button">提取视频</button>' + 
				'</span>' + 
			'</div>'+
			'',
		css: '#link-vodupload .pop .modal-title{padding:0 10px; }' +
			'#link-vodupload .input-group{margin:50px 30px; }' +
			'#link-vodupload .form-control{width:400px; height:40px; border:1px solid #cfcfcf; border-radius:3px; box-sizing:border-box; padding-left:5px; box-shadow:none; }' +
			'#link-vodupload .btn-default{background-color:#6ea5e8; border-width:0; outline:none; color:#fff; }' +
			'#link-vodupload .btn-default:hover{background-color:#76abeb; }' +
			'#link-vodupload .btn-default:active{background-color:#4b8edc; }' +
			'#link-vodupload .extract-btn{width:120px; height:40px; margin-left:10px; font-size:14px; border-radius:3px; }' +
			'.form-control::-webkit-input-placeholder {color:#ccc; }' +
			'',
		cssInited : false
	};
	$.widget('flatpop.link_vodupload', $.flatpop.base, {
		options : {
			
		},
		_create : function(){
			this._super();
		},
		_init : function(){
			this._super();
			this._template('uploadvideo', linkvideoInfo, this.body);
			this.show();
			this._on({
				'click .extract-btn' : '_extract'
			});
		},
		
		_extract : function( event ){
			var _this = this;
			var self = $(event.currentTarget);
			var videoArea = this.body.find('input[name="video_url"]'),
        		videoLinks = $.trim( videoArea.val() );
        	if( !videoLinks ){
        		this._tips({
        			dom : self,
        			str : '请先填入该视频地址...',
        		});
        		return false;
        	}
        	var url = './run.php?a=add_video&mid=' + gMid + '&is_link=1&ajax=1';
        	$.globalAjax(self, function(){
		        return $.getJSON(url,{url : videoLinks}, function(data){
		        	if( data && data['callback'] ){
		        		eval( data['callback'] );
		        	}else{
		        		_this.ajaxBack( self, data );
		        	}
        	   });
		    });
		},
		
		ajaxBack : function( dom, data ){
			var str, _this = this;
			if( data && data.error ){
				str = data.msg,
				delay = 2000;
			}else if( data && data[0] && data[0].ori_url ){
				str = '提取视频成功！',
				delay = 1000;
				setTimeout(function(){
					_this._empty();
				}, 2000);
			}
			this._tips({
				dom : dom,
    			str : str,
    			delay : delay
			});
		},
		
		_empty : function(){
			this.body.find('.input-group .form-control').val('');
			this.hide();
		}
	});
})(jQuery);
});
