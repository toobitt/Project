$(function(){
	(function($){
		var tpl = '<div class="express-info" style="top:{{= top+3}}px;" _id="{{= id}}" _top="{{= top+3}}">'+
						'<span class="slideout express-sign" title="物流信息"><</span>'+
						'<span class="expressarrow" style="top:{{= arrowTop}}px"></span>'+
						'<div class="express-main-info">'+
					     	'<span class="nav-title overhidden">{{= title}}</span>'+
					     	'<span class="express-close express-sign">X</span>'+
					    	'<div class="express-item">'+
					    		'<span class="express-title">快递公司:</span>'+
					    		'<input type="text" name="express_name" autofocus placeholder="输入快递公司" value="{{= express_name}}"/>'+
					    	'</div>'+
					    	'<div class="express-item">'+
					    		'<span class="express-title">快递单号:</span>'+
					    		'<input type="text" name="express_no" placeholder="输入快递单号" value="{{= express_no}}"/>'+
					    	'</div>'+
					    	'<input type="hidden" name="tracestep" value="{{= tracestep}}" />'+
					    	'<input type="hidden" name="express_id" value="{{= id}}" />'+
					    	'<span class="save-express-info">确定</span>'+
				    	'</div>'+
				  '</div>'+
				  '',
		    css = '.express-info{width:0px;height:0px;border:1px solid #ddd;border-radius:3px;z-index:9;padding: 10px 20px 20px 20px;box-shadow: 10px 8px 8px #ddd;position: absolute;background: #f9f9f9;right: 155px;}'+
				  '.express-info .express-main-info{display:none;}'+
		    	  '.express-info .express-sign{position: absolute;cursor: pointer;width: 17px;height: 17px;color: #333;text-align: center;line-height: 17px;border-radius: 3px;font-size: 12px;}'+
				  '.express-info .express-sign:hover{background: #ddd;}'+
				  '.express-close{top: 13px;right: 15px;}'+
				  '.express-info .slideout{top: 6px;right: 13px;}'+
				  '.expressarrow{display:block;width:20px;height:20px;position: absolute;top: 0px;right:0px;}'+
				  '.expressarrow:before{content: "";border-left: 10px solid #ddd;border-bottom: 8px solid transparent;border-top: 8px solid transparent;position: absolute;right: -10px;top: 8px;}'+
				  '.expressarrow:after{content: "";border-left: 10px solid #f9f9f9;border-bottom: 8px solid transparent;border-top: 8px solid transparent;position: absolute;right: -9px;top: 8px;}'+
				  '.express-info .nav-title{font-size: 16px;padding-bottom: 3px;display: block;border-bottom: 1px dotted #ddd;margin-bottom: 15px;}'+
				  '.express-info .express-item{display: -webkit-box;margin:0px 0px 15px 0px;}'+
				  '.express-info .express-title{display: block;width: 25%;height:30px;line-height:30px;font-size: 14px;}'+
				  '.express-info input[type="text"]{display:block;width: 75%;height:30px;padding: 0px;border-radius:3px;text-indent:10px;background: #f9f9f9;}'+
				  '.express-info input[type="text"]:focus{background: #fff;}'+
				  '.express-info .save-express-info{display: block;width: 30%;padding: 5px 0px;background: #6ea5e8;font-size: 14px;color: #fff;margin-left:25%;cursor:pointer;text-align: center;border-radius: 3px;}'+
				  '.express-info .save-express-info:hover{background:#5192E2;}'+
				  '.overhidden{white-space: nowrap;overflow: hidden;text-overflow: ellipsis;}'+
		          '',
		    cssInited = false;
		
		$.widget('payments.express_add' , {
			options : {
				tracestep : '',
				title : '',
				id : '',
				target : '',
				top : 0,
				flag : ''
			},
			
			_init : function(){
				this.initTmpl();
			},
			
			initTmpl : function(){
				var options = this.options,
					_this = this;
				$.template('express_add', tpl );
				var data = $.extend( options , {
					//top : _this.options.target.offset().top,
				});
		        var dom = $.tmpl('express_add', options ).appendTo( this.options.target );
		        var items = dom.siblings('.express-info');
		        if( this.options.flag ){
		        	setTimeout(function(){
		        		_this.slideshow( dom );
			        	$('.express-info').do_express();
		        	})
		        }
		        this.slidehide( items );
				this.initCss();
			},
			
			getTop : function(){
				var eHeight = this.element.height(),
					mTop = this.options.target.offset().top;
				var Top={};
				if( mTop + 190 > eHeight ){
					Top.aTop = mTop - 150;
					Top.arrowTop = 150;
				}else{
					Top.aTop = mTop;
				}
				return Top;
			},
			
			initCss : function(){
		        if( !cssInited && css ){
		        	cssInited = true;
		        	this.addCss( css );
		        }
		    },
		    
		    addCss : function(css){
	            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
	        },
    	
	    	slideshow : function( item ){
	    		var _this = this;
	        	item.find('.express-main-info').show();
	        	var eHeight = this.element.height(),
	        		_top = JSON.parse( item.attr('_top') ),
	        		top = item.offset().top,
	        		arrowtop ;
	        	if( top + 190 > eHeight ){
					top = _top - 150;
					arrowtop = 150;
					
				}else{
					top = _top ;
					arrowtop = 0;
				}
	        	item.animate({width:280,height:160 , top : top},200).attr('_top' ,  _top).css('z-index' , '99' );
	        	item.find('.expressarrow').css('top' , arrowtop );
	        	item.find('.slideout').hide();
	    	},
	    	
	    	slidehide : function( item ){
	    		var _this = this;
	    		var eHeight = this.element.height();
	    		item.map(function( key , value ){
	    			var _top = JSON.parse( $(this).attr('_top') ),
	    				top = $(this).offset().top;
					if( top + 340 > eHeight ){
						top = _top;
					}else{
						top = _top ;
					}
	    			$(this).animate({width:0,height:0 , top : top},100).css('z-index' , '9' );
	    			$(this).find('.express-main-info').hide();
	    			item.find('.expressarrow').css('top' , 0 );
	    			$(this).find('.slideout').show();
	    		})
	    	},
		});
		
		$.widget('payments.do_express' , {
			options : {
				
			},
			
			_init : function(){
				this._on({
					'click .save-express-info' : '_save',
					'click .express-close' : '_close',
					'click .slideout' : '_show'
				})
			},
			
	        _save : function( event ){
	        	var self = $( event.currentTarget ),
	        		content = self.closest('.express-info'),
	        		express_name = content.find('input[name="express_name"]'),
	        		express_no = content.find('input[name="express_no"]'),
	        		name = $.trim(express_name.val()),
	        		num = $.trim(express_no.val()),
	        		id = content.find('input[name="express_id"]').val();
	        		tracestep = content.find('input[name="tracestep"]').val();
	        	var param = {};
	        		param.express_name = name;
	        		param.express_no = num;
	        		param.id = id;
	        		param.tracestep = tracestep;
	        	if( !name ){
	        		this.tip( content , '快递公司名没有填写！' );
	        		express_name.focus();
	        		return;
	        	}
	        	if( !num ){
	        		this.tip( content , '快递单号没有填写！' );
	        		express_no.focus();
	        		return;
	        	}
	        	var target = content.find('.save-express-info'),
	        		url = 'run.php?mid='+ gMid + '&a=update_trace_step',
	        		_this = this;
	        	this.ajax( target , url , param , function( data ){
	        		if( data ){
	        			_this.tip( content , '快递信息保存成功！' );
	        		}
	        	});
	        },
	        
	        _close : function( event ){
	        	var self = $( event.currentTarget ),
	        		item = self.closest('.express-info');
	        	$('body').express_add('slidehide' , item );
	        },
	        
	        _show : function( event ){
	        	var self = $( event.currentTarget ),
	        		item = self.closest('.express-info');	
	        	$('body').express_add('slideshow' , item );
	        	var siblings = item.siblings('.express-info');
	        	if( siblings ){
	        		$('body').express_add('slidehide' , siblings );
	        	}
	        },
	        
	        ajax : function( item , url , param , callback){
	    		$.globalAjax( item, function(){
	    			return $.getJSON( url , param , function( data ){
	    				if( $.isFunction( callback ) ){
	    					callback( data );
	    				}	
	    		    });
	    		});
	    	},
	    	
	    	tip : function( item , tip ){
	    		item.find('.save-express-info').myTip({
					string : tip,
					width : 130,
					delay: 1000,
					dtop : 0,
					dleft : 65,
				});
	    	},
		});
		
	})($);
	$.express = function( tracestep ,  express_name , express_no , id , title , target , flag){
		$('body').express_add({
			tracestep : tracestep,
			express_name : express_name,
			express_no : express_no,
			id : id,
			title : title,
			target : target,
			flag : flag
		});
	}
	setTimeout(function(){
		$('.express-info').do_express();
	})
})