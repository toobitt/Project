(function(){
	$.plugin = function(name, object) {
		$.fn[name] = function(options) {
			var args = Array.prototype.slice.call(arguments, 1);
			return this.each(function() {
				var instance = $( this ).data('name')
				if (instance) {
					instance[options].apply(instance, args);
				} else {
					instance =  $( this ).data('name', new object(options, this));
				}
			});
		};
	};
	
	var pluginName = 'new_lottery';
	
	function Plugin( options, element, e ) {
		if ( e ) {
            e.stopPropagation();
            e.preventDefault();
        }
        
       this.el = $(element);
       this.op = $.extend({}, $.fn[pluginName].defaults, options);
       this.init(); 
	}
	
	Plugin.prototype =  {
		constructor : Plugin,
		
		init : function(){
			this.loading();
			this.bindEvent();
		},
		
		loading : function(){
			var op = this.op;
			this.storage = $.Hg_localstorage( {key : op.key} );
			this.action = 'start';
			this.result = {};
			this.index = 0;
			this._draw();
		},
		
		_draw : function(){
			var _this = this;
			_this._resultPart();
			_this._filter();
			_this._timeout();
		},
		
		_timeout : function(){
			var _this = this;
			this.setInterval = setInterval(function(){
				_this._filter();
			}, _this.op.ajaxInterval);
		},
		
		_filter : function(){
			var _this = this;
			this.ajax( _this.op.getdataUrl, null, function( data ){
				if( $.isArray( data ) && data[0] ){
					_this.$( _this.op.inner )[0].innerHTML = _this._each( data );
				}
			} );
		},
		
		bindEvent : function(){
			var _this = this,
				op = _this.op;
			_this.$( op.btn ).on('click', function(){
				_this.process();
			});
			_this.$( op.back ).on('click', function(){
				_this.back();
			});
			_this.$( op.clear ).on('click', function(){
				_this.clear();
			});
		},
		
		process : function(){
			var _this = this;
			switch( _this.action ){
				case 'start' : {
					_this.action = 'stop';
					_this.$( _this.op.mask ).addClass('hide');
					_this.rotate_start();
					break
				}
				case 'stop' : {
					_this.action = 'end';
					_this.rotate_end();
					break;
				}
				default : {
					console.log('抽奖失败~');
				}
			}
		},
		
		rotate_start : function(){
			var _this = this;
			if( !_this.$( _this.op.inner ).find('li').length ){
				return;
			}
			if( this.setInterval ){
				clearInterval( this.setInterval );
				this.setInterval = null;
			}
			_this.rotate_run();
			_this.$( _this.op.btn ).html( '暂停' );
		},
		
		rotate_end : function(){
			var _this = this;
			_this._timeout();
			_this.$( _this.op.btn ).html( '抽奖' );
		},
		
		rotate_run : function(){
			var _this = this,
				op = _this.op;
			var wrap = _this.$( op.inner ).find('ul'),
				first = wrap.find( 'li:first' );
			var time = Math.floor( op.interval / (op.line) );
			first.animate({
				marginTop : -op.lineheight + 'px'
			}, time, function() {	
				first.css('marginTop', 0).appendTo( wrap );
			});
			
			if( _this.action == 'end' ){
				_this.action = 'start';
				if( op.url ){
					_this.ajaxData();
				}
			}else{
				//_this.show_result( arr );
				setTimeout( function(){
					_this.rotate_run();
				}, op.interval );
			}
		},
		
		ajaxData : function(){
			var _this = this,
				op = this.op,
				param = {
					count : op.num
				};
			this.ajax(op.url, param, function( data ){
				if( $.isArray( data ) && data[0]  ){
					if( data[0]['error'] ){
						_this.$( op.inner ).html( '<p class="title">' + data[0]['error'] + '</p>' );
						_this.$( op.btn ).addClass('disabled');
						return;
					}
					_this.$( op.mask ).removeClass('hide');
					_this.get_result( data );
				}
			})
		},
		
		ajax : function( url, param, callback ){
			param = $.extend( {
				id : this.op.id
			}, param )
			$.ajax({
				type: 'get',
				url : url,
				data : param,
				dataType : 'json',
				success : function( data ){
					callback( data );
				},
				error : function(){
	        		console.log('接口访问错误，请稍候再试');
	        	}
			});
		},
		
		get_result : function( data ){
			this.index += 1;
			this._cache( data );
			this.draw_result( data );
			var resultbox = this.$( this.op.result );
			if( resultbox.find('.nodata').length ){
				resultbox.find('.nodata').remove();
			}
			this.$().addClass('translate');
		},
		
		_resultPart : function(){
			var _this = this,
				hash = location.hash,
				hash = hash.substr(1);
			var value = this.storage.getItem(),
				strHtml = '';
			var resultbox = _this.$( _this.op.result );
			if( $.isArray( value ) && !value[0] ){
				strHtml = '<p class="title nodata">暂无中奖名单</p>';
				resultbox[0].innerHTML = strHtml;
			}else{
				$.each(value, function(kk, vv){
					if( $.isArray( vv ) && vv[0] ){
						_this.draw_result( vv );
					}
				});	
			}
			if( hash == 'result' ){
				this.$().addClass('translate');
			}
		},
		
		draw_result : function( data ){
			var _this = this;
			var strHtml = _this._each( data, 'list-' + _this.op.resultnum + ' clear' ),
				liwidth = Math.floor( 100 / this.op.resultnum ) + '%';
			var resultbox = this.$( this.op.result );
			resultbox.html( strHtml ).find('li').css({
				width : liwidth
			});
		},
		
		back : function(){
			this.$().removeClass('translate');
		},
		
		clear : function(){
			var _this = this,
				op = _this.op;
			this.ajax( op.url, {clear : 1}, function( data ){
				_this.$( op.result )[0].innerHTML = '<p class="title nodata">暂无中奖名单</p>';
				_this._cache();
			} );
		},
		
		_cache : function( data ){
			if( data ){
				this.result[ this.index ] = data;
				this.storage.resetItem( this.result );
				return;
			}
			this.result = {};
			this.storage.removeItem();
		},
		
		_each : function( data, className ){
			var _this = this,
				strhtml = '<ul class="list ' + (className || '') + '">';
			for( var i=0, len=data.length; i<len; i++ ){
				var tel_sub =  _this.substr_replace( data[i]['tel'] );
				strhtml += '<li class="item"><label>' + data[i]['name'] + '：</label><span>' + tel_sub + '</span></li>';
			}
			strhtml += '</ul>';
			return strhtml;
		},
		
		substr_replace : function( str ){
			return str.substr(0, 4) + '****' + str.substr(8, 3);
		},
		
		$ : function( s ){
			return s ? this.el.find(s) : this.el;
		}
		
	};
	
	$.plugin(pluginName, Plugin);
	
	$.fn[pluginName].defaults = {
		line : 3,		//滚动行数
		num : 5,		//抽奖个数
		interval : 120,	//滚动间隔时间
		ajaxInterval : 30000,	//请求数据间隔
		lineheight : 34,	//行高
		btn : '.lottery',
		url : '',
		key : 'lottery_result',
		mask : '.mask',
		inner : '.inner',
		result : '.result-wrap .area',
		resultnum : 2,
		back : '.back',
		clear : '.clearbtn'
	}
})();