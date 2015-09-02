$(function(){
	(function(){
		var pluginInfo = {
	        template : ''+
		        // '<div class="modal pop {{= bgStyle}}" id="{{= id}}">'+
		        	'<div class="modal-dialog {{= bgStyle}} {{if modalHead}}hasHead{{/if}}">' + 
			        	'<div class="modal-header">' + 
			        		'<div class="modal-title">'+
	        					'{{= popTitle}}'+
	        				'</div>'+
	        				'<div class="modal-btns">' +
	        				 	'{{if savebtn}}<span class="pop-save-button save-btn">保存</span>{{/if}}' + 
	        				 	'<span class="{{if savebtn}}pop-close-button2{{else}}pop-close-btn{{/if}} close-btn"></span>' + 
	        				 '</div>' + 
	        			'</div>' +
        				'<div class="modal-body">'+
        					'<!-- 内容 -->'+
        				'</div>'+
		        	'</div>' + 
		        // '</div>'+
	        	'',
	        css : ''+
	        	'.pop{z-index:1200;}' + 
	        	// '.modal{position:fixed; left:0; right:0; top:0; bottom:0; background-color:rgba(0, 0, 0, 0.5); }' +
	        	// '.pop.fade{display:none; }' + 
	        	'.pop.fade .modal-dialog{top:-800px!important; }' + 
	        	'.pop .modal-dialog{position:absolute; top:290px; left:50%; border:10px solid transparent; transition:top 0.5s; background-color:#fff; z-index:10001; }'+
	        	'.pop .modal-header{height:50px;line-height:50px;font-size:24px; border-bottom:1px solid #cfcfcf; margin:0 10px; }'+
	        	'.pop .modal-content{background:#fbfcfe;width:100%;height:100%;max-width: 100%;}'+
	        	'.pop .modal-title{color:#333; }' +
	        	
	        	'.pop .pop-blue{border-color: #6ea5e8; }' +
	        	'.pop .hasHead .modal-title{color:#fff; }' +
	        	'.pop .pop-blue.hasHead .modal-header{background-color: #6ea5e8; border-color: #6ea5e8; margin:0; }' +
	        	'.pop .hasHead{border-top-width:0;}' + 
	        	
	        	'.pop .modal-btns span{position:absolute; top:10px; }'+ 
	        	'.pop .close-btn{right:10px;}'+
	        	'.pop .save-btn{right:50px; }' + 
	        	'',
	        cssInited : false
	    };
	    
	    var defaultOptions = {
			id : 'model-pop',	//弹窗id
			width : 600,		//弹窗宽、高
			height : 430,
			modalHead : false,		//是否需要标题栏
			flexBoxCenter : true,	//内容垂直居中
			popTitle : '弹窗标题',
			bgStyle : 'pop-blue',
			modalHead : true,
			savebtn : true
		};
	    
		$.widget('flatpop.base', {
			options : {
				custom_close : false
			},
			_create : function(){
				this.options = $.extend( {}, defaultOptions, this.options);
			},
			
			_init : function(){
				this.el = this._template('pop', pluginInfo, $('#'+ this.options.id), this.options);
				this.body = this.el.find('.modal-body');
				this.el.css({
					width : this.options.width + 'px',
					height: this.options.height + 'px',
					'margin-top' : - ( this.options.height / 2 ) + 'px',
					'margin-left' : - ( this.options.width / 2 ) + 'px',
					top : this.options.ptop + 'px'
				});
				this._on({
					'click .pop-save-button' : 'save',
					'click .close-btn' : 'close',
				})
			},
			
			_template : function(tname, info, container, datas, param){
	            tname = tname || this.options.templateName;
	            info = info || this.options.pluginInfo;
	            container = container || this.element;
	            var temp = info.template ? info.template : info;
	            param = param || {};
	            datas = datas || {};
	            $.template( tname, temp );
	            var dom = $.tmpl(tname, datas, param).appendTo(container);
	            if(!info.cssInited && info.css){
	                info.cssInited = true;
	                this.addCss(info.css);
	            }
	            return dom;
	        },
	        
	        addCss : function(css){
	            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
	        },
	        
	        show : function(){
	        	this.element.removeClass('fade');
	        },
	        
	        hide : function(){
	        	this.element.addClass('fade');
	        },
	        
	        save : function(){
	        	this._trigger('savePop', event, this);
	        	this.hide();
	        },
	        
	        close : function( event ){
	        	this.hide();
	        },
	        
	        _tips : function( option ){
	        	option.dom.myTip({
					string : option.str,
					delay : option.delay || 1000,
					dleft : option.dleft || 50,
					dfontsize : option.dleft || 14,
				});
	        },
	        
	        _destroy : function(){
	
	        }
		});
		
		$.modalPop = function( id ){
			var popDom = $('<div />').appendTo('body').attr({
				'class' : 'modal fade pop',
				id : id
			});
			return popDom;
		}
	})($);
});