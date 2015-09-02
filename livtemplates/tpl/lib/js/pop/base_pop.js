;(function($){
    $.widget('pop.base', {
        _template : function( tname, tpl, dataInfo, container, data ){
        	$.template( tname, tpl );
        	$.tmpl( tname, data ).appendTo( container );
        	if( !dataInfo.cssInited && dataInfo.css ){
        		dataInfo.cssInited = true;
        		this._addCss( dataInfo.css );
        	}
        },
        
        _addCss : function(css){
            $('<style/>').attr('style', 'text/css').appendTo('head').html(css);
        }
    });
	
	var pluginInfo = {
			template : '' + 
			'<div id="pop-box" class="pop-area">' + 
				'<div class="pop-head">' + 
					'<div class="pop-title"></div>' + 
					'<div class="pop-close"></div>' + 
				'</div>' +
				'<div class="pop-search"><span class="custom-btn"></span></div>' + 
				'<div class="pop-body">' + 
					'<div class="publish-lib-wrap"></div>' +
					'<div class="publish-custom-wrap"></div>' +
				'</div>' + 
			'</div>' +
			'',
			css : '' +
			'.pop-area{position:absolute;top:50%;left:44%;margin-left:-430px;margin-top:-300px;width:1080px;height:600px;padding:10px;background:#6ba4eb;z-index:100000000;}' +
			'.pop-head{height:38px;padding:5px 0;font-size:24px;color:#fff;font-size:24px;}' +
			'.pop-head .pop-title{float:left;}' + 
			'.pop-head .pop-close{display:block;float:right;width:26px;height:26px;border-radius:2px;background:url(' + RESOURCE_URL+'datasource/close4.png) no-repeat center,-webkit-linear-gradient(#f3f3f3,#dedede);background:url(' + RESOURCE_URL+ 'datasource/close4.png) no-repeat center,-moz-linear-gradient(#f3f3f3,#dedede);cursor:pointer;}' + 
			'.pop-search{height:43px;border:1px solid #ccc;border-left:none;border-right:none;background:url('+  RESOURCE_URL + 'datasource/nav-bg.png) repeat-x;position:relative;}' +
			'.pop-body{height:500px;background:#fff;}' + 
			'.publish-custom-wrap{display:none;}' +
			'.m2o-flex{display:-webkit-box;display:-moz-box;display:box;width:100%;}' + 
			'.m2o-flex-center{-webkit-box-align:center;-moz-box-align:center;box-align:center;}' +
			'.m2o-flex-one{-webkit-box-flex:1;-moz-box-flex:1;box-flex:1;}' +
			'',
			cssInited : false
	};
    $.widget('pop.pop', $.pop.base, {
        options : {
        	pop_drag : true,
        	css : '' //弹窗位置样式
        },
        _create : function(){
        	var root = this.element;
        	root.addClass( this.options.className );
        	this._template( 'template', pluginInfo.template, pluginInfo, this.element );
        	this.search_box = root.find( '.pop-search' );
        	this.body = root.find( '.pop-body' );
        	this.publish_lib_wrap = this.body.find('.publish-lib-wrap');
        	this.publish_custom_wrap = this.body.find('.publish-custom-wrap');
        	this.title = root.find( '.pop-title' );
        	this.options.pop_drag && this._setPopDrag();
        },
        _init : function(){
        	this._on( {
        		'click .pop-close' : '_close'
        	} );
        	this.show( this.options.css );
        	this._initTitle( this.options.title || '添加内容' );
        },
        _setPopDrag : function(){
        	var box = this.element.find('.pop-area');
        	box.draggable().css( 'cursor', 'move' );
        },
        _initTitle : function( title ){
        	this.title.html( title );
        },
        _close : function(){
        	this.hide();
        },
        show : function(option ){
        	this.element.show();
        	option && this.element.find('.pop-area').css( option );
        	this._createMask();
        },
        hide : function(){
        	this.element.hide();
        	this._clearMask();
        },
        refresh : function(){
        	this.show()
        },
        _createMask : function(){
        	if( this.mask ) return;
        	var height = $('body').outerHeight(true);
        	this.mask = $('<div/>').css( {
        		position:'absolute',
        		width : '100%',
        		height : height + 'px',
        		background : 'black',
        		opacity : 0.1,
        		'z-index' : 100000000
        	} ).prependTo( 'body' );
        },
        _clearMask : function(){
        	if( this.mask ){
        		this.mask.remove();
        		this.mask = null;
        	}
        }
    });
    
    

})($);