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
				'<div class="pop-body"></div>' + 
			'</div>' +
			'',
			css : '' +
			'.pop-area{position:absolute;top:50%;left:50%;margin-left:-430px;margin-top:-300px;width:860px;height:600px;padding:10px;background:#212121;z-index:100000;}' +
			'.pop-head{height:38px;padding:5px 0;font-size:24px;color:#fff;font-size:24px;}' +
			'.pop-head .pop-title{float:left;}' + 
			'.pop-head .pop-close{display:block;float:right;width:26px;height:26px;border-radius:2px;background:url(' + RESOURCE_URL+'datasource/close4.png) no-repeat center,-webkit-linear-gradient(#f3f3f3,#dedede);background:url(' + RESOURCE_URL+ 'datasource/close4.png) no-repeat center,-moz-linear-gradient(#f3f3f3,#dedede);cursor:pointer;}' + 
			'.pop-search{height:43px;border:1px solid #ccc;border-left:none;border-right:none;background:url('+  RESOURCE_URL + 'datasource/nav-bg.png) repeat-x;position:relative;}' +
			'.pop-body{height:500px;background:#fff;}' + 
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
        	this.title = root.find( '.pop-title' );
        	this.options.pop_drag && this._setPopDrag();
        },
        _init : function(){
        	this._on( {
        		'click .pop-close' : '_close'
        	} );
        	this.show();
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
        show : function( option  ){
        	this.element.show();
        	option && this.element.find('.pop-area').css( option );
        },
        hide : function(){
        	this.element.hide();
        },
        refresh : function(){
        	this.show()
        }
    });
    
    $.fn.hg_autocomplete = function( option ){
    	return this.each( function(){
    		var $this = $(this),
    			defaultOption = { url : '../getUser.php', param : 'name' },
    			options = $.extend( defaultOption, option );

            var cache = {
                _cache : {},
                get : function(key){
                    return this._cache[key];
                },
                set : function(key, val){
                    key && (this._cache[key] = val);
                }
            };

            var autoClass = $.fn.hg_autocomplete.autoClass = $.fn.hg_autocomplete.autoClass || (function(){
                function _autoClass($dom){
                    this.$dom = $dom;
                    this.init();
                }

                $.extend(_autoClass.prototype, {
                    init : function(){
                        this.$dom.autocomplete({source : []});
                    },
                    callback : function(value, members){
                        this.$dom.autocomplete('option', 'source', members);
                        this.$dom.autocomplete('search' , value);
                    }
                });

                return _autoClass;
            })();
            var autoComplete = new autoClass($this);

            $this.on('keyup', function( event ){
                if(event.keyCode >= 37 && event.keyCode <= 40){
                    return;
                }
                var $this = $(this);
                var timer = $this.data('timer');
                timer && clearTimeout(timer);
                $this.data('timer', setTimeout(function(){
                    var	value = $.trim($this.val());
                    if(value){
                        var members = cache.get(value);
                        if(members){
                            autoComplete.callback(value, members);
                        }else{
                            var url = options['url'] + '?' + options['param'] + '=' + value;
                            var hash = +new Date() + '' + Math.ceil(Math.random() * 1000);
                            $this.data('ajaxhash', hash);
                            $.getJSON(url ,function(data){
                                if(hash != $this.data('ajaxhash')) return;
                                var members = [];
                                $.each(data, function(key, value){
                                    members.push(value['user_name']);
                                });
                                cache.set(value, members);
                                autoComplete.callback(value, members);
                            });
                        }
                    }

                }, 300));
    		});
    	});
    };

    $.fn.autocompleteResult = function( option ){
    	var defaultOption = { event: 'autocompleteselect', issubmit : true },
    		options = $.extend( defaultOption, option  );
    	return this.each( function(){
    		$(this).hg_autocomplete();
    		$(this).on( options['event'], function( event,ui ){
    			$(this).val( ui.item.label );
    			options['issubmit'] && $(this).closest( 'form' ).submit();
    		});
    	} );
    };
    
})($);