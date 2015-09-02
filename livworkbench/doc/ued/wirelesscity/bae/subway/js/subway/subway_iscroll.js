!function($){
	var dummyStyle = document.createElement('div').style,
		vendor = (function () {
        var vendors = 'webkitT,MozT,msT,OT,t'.split(','),
            t,
            i = 0,
            l = vendors.length;

        for ( ; i < l; i++ ) {
            t = vendors[i] + 'ransform';
            if ( t in dummyStyle ) {
                return vendors[i].substr(0, vendors[i].length - 1);
            }
        }

        return false;
    })(),
 	transform = prefixStyle('transform'),
 	isTouchPad = (/hp-tablet/gi).test(navigator.appVersion),
 	hasTouch = 'ontouchstart' in window && !isTouchPad,
 	math = Math,
	
	customSlider = function(element, options){
		this.init(element, options);
	};
	
	customSlider.prototype =  {

        constructor: customSlider,
        
        init : function (element, options) {
        	var _this = this;
        	var $el = this.$element = $(element);
		    var op = this.options = $.extend({}, $.fn.customSlider.defaults, options); 
		    this.scroller = $el[0].children[0];
	    	this.root();
	    	this.attachEvent();
	    	this.bindEvent();
	    },
	    
	    attachEvent : function(){
	    	var _this = this;
	    	// 线网规划
	    	this.$element.find('.pic-left').on('touchstart', '.pic-btns a', function( event ){
				var self = $(event.currentTarget),
					className = self[0].className;
				switch( className ){
					case 'fullpic-add' : {
						_this.controlArea('add');
						break;
					}
					case 'fullpic-reduction' : {
						_this.controlArea('reduce');
						break;
					}
					default: 
						break;
				}
			});
	    	
	    	
	    	this.$element.find('#subway-mapArea').on( 'touchend', 'area', function( event ){
					var id = $(this).data('id'),
						self = $(event.currentTarget);
					_this.scaleArea('stationClick', function(){
						_this.stationback( self );
					});
					event.stopPropagation();
				} );
			this.$element
				.on('touchstart', '.network-nearby', function( event ){
					$(this).fadeOut();
					event.stopPropagation();
				});
	    },
	    
	    bindEvent : function(){
	    	var _this = this;
	    	//var triggerData = ['swipe', 'swipeLeft', 'swipeRight', 'swipeUp', 'swipeDown'];
	    	var triggerData = ['touchstart', 'touchend', 'touchmove'];
	    	triggerData.forEach(function( eventName ){
	    		_this.$element.find('.subway-map-inner').on(eventName, function( e ){
	    			_this._touch( e, eventName );
	    		})
	    	})
	    },
	    
	    _touch : function( e, eventName ){
	    	switch( eventName ){
	    		case 'touchstart' : {
	    			this._start( e );
	    			break;
	    		}
	    		case 'touchend' : {
	    			this._end( e );
	    			break;
	    		}
	    		case 'touchmove' : {
	    			this._move( e );
	    			break;
	    		}
	    	}
	    },
	    
	    _start : function( e ){
	    	var that = this;
	    	var point = hasTouch ? e.touches[0] : e;
	    	that.pointX = point.pageX;
            that.pointY = point.pageY;
            
    		var station_pop = this.$element.find('.station-pop');
			if( station_pop.length ){
				station_pop.remove();
			}
            e.preventDefault();
	    },
	    
	    _end : function( e ){
	    	e.preventDefault();
	    },
	    
	    _move : function( e ){
	    	var that = this;
	    	var point = hasTouch ? e.touches[0] : e;
	    	var deltaX = point.pageX - that.pointX,
            	deltaY = point.pageY - that.pointY;

        	var ratioX = this.formatRatio( math.floor( math.abs(deltaX)/100 ) );
        	var ratioY = this.formatRatio( math.floor( math.abs(deltaY)/100 ) );

        	deltaX = this.formatFloat( deltaX * ratioX, 2 );
        	deltaY = this.formatFloat( deltaY * ratioY, 2 );
			this._pos(deltaX, deltaY);
			e.preventDefault();
	    },
	    
	    formatRatio : function( num ){
	    	var ratio;
	    	switch( num ){
        		case 0 : {
        			ratio = 1;
        			break;
        		}
        		case 1 : {
        			ratio = 0.8;
        			break;
        		}
        		case 2 : {
        			ratio = 0.6;
        			break;
        		}
        		default : {
        			ratio = 0.4; 
        			break;
        		}
        	}
        	return ratio;
	    },
	    
	    formatFloat : function(num, digital){
	    	var m = math.pow(10, digital);
	    	return parseInt( num * m, 10 ) / m; 
	    },
	    
	    _pos : function(x, y){
	    	var real_size = this.getTranslate(), jisuan_size = {},
	    		real_scale = this.$element.data('scale');
	    		jisuan_size.x = parseInt(real_size.x) + x,
	    		jisuan_size.y = parseInt(real_size.y) + y;
    		var adjust_size = this.adjustXY( jisuan_size, real_scale);
    		this.drawTranslate( adjust_size );
			this.adjustArea(adjust_size, real_scale, false);
	    	
	    },
	    
	    adjustXY : function(jisuan_size, real_scale){
	    	var adjust_size = jisuan_size,
	    		target = this.maxRefresh( real_scale );
	    	if( math.abs( jisuan_size.y ) > target.y ){
	    		adjust_size.y = jisuan_size.y > 0 ? target.y : -target.y; 
	    	}
	    	if( math.abs( jisuan_size.x ) > target.x  ){
	    		adjust_size.x = jisuan_size.x > 0 ? target.x : -target.x; 
	    	}
	    	return adjust_size;
	    },
	    
	    maxRefresh : function( scale ){
	    	var op = this.options;
	    	return {
	    		x : this.formatFloat( math.abs(op.adjust.x) * scale, 2 ) - op.width /2,
	    		y : this.formatFloat( math.abs(op.adjust.y) * scale, 2 ) - op.height /2,
	    	}
	    },
	    
	    root : function(){
	    	this.$element.height( this.options.height );
	    },
	    
	    scaleMapArea : function( id ){
	    	var self = this.$element.find('area[data-id="' + id + '"]');
	    	this.stationback( self );
	    },
	    
		stationback : function( station_target ){
			var self = station_target,
				coords = self.attr('coords').split(','),
				x = parseInt( coords[0] ),
				y = parseInt( coords[1] ),
				stationXY = { x: x, y : y };
			var station_info = {
				title : self.data('title'),
				istoilet : self.data('istoilet'),
				station_color : self.data('color').split(','),
				stationXY : stationXY,
				id : self.data('id'),
				line : self.data('line').toString().split(',')
			};
			this.moveCenter( station_info );
		},
		
		moveCenter : function( station_info ){						//点击站点移动线路图让该站点处于视口中心
			var stationXY = station_info.stationXY || {x : 0, y : 0};
			var real_x = stationXY.x + this.options.adjust.x,
				real_y = stationXY.y + this.options.adjust.y;
			var real_size = {
				x : -real_x,
				y : -real_y
			}
			this.drawTranslate( real_size );
			this.adjustArea( real_size , this.$element.data('scale'), true);
			this.renderPop( station_info );
		},
	    
	    controlArea : function( type ){
	    	var _this = this,
	    		mapArea = this.$element;
    		var real_size = this.getTranslate();
    		this.scaleArea(type, function( real_scale ){
    			_this.adjustArea(real_size, real_scale, true);
    		});
	    },
	    
	    scaleArea : function( type, callback ){
	    	var ratio = this.options.ratio,
	    		ration_len = ratio.length;
	    	var current_scale = this.$element.data('scale'),
	    		real_scale;
    		var index = $.inArray(current_scale, ratio);
	    	if( type == 'stationClick' ){
	    		real_scale = ratio[ index + 1 ] ? ratio[ index + 1 ] : ratio[ ration_len - 1 ]
	    	}else{
	    		real_scale = (type == 'add') ? ratio[ ration_len - 1 ] : ratio[0];
	    	}
    		(real_scale != current_scale) && this.$element.data('scale', real_scale);
	    	$.isFunction( callback ) && callback( real_scale );
	    },
	    
	    getTranslate : function(){
	    	var father = this.$element;
	    	return {
	    		x : father.attr('_x'),
	    		y : father.attr('_y')
	    	}
	    },
	    
	    drawTranslate : function( real_size ){
	    	var father = this.$element;
	    	father.attr({
	    		'_x' : real_size.x,
	    		'_y' : real_size.y
	    	});
	    },
	    
	    adjustArea : function( real_size, scale, type ){
	    	var _this = this;
	    	real_size.x = type ? (real_size.x * scale).toFixed(2) : real_size.x;
	    	real_size.y = type ? (real_size.y * scale).toFixed(2) : real_size.y;
	    	this.throttle(function(){
	    		_this.scroller.style[transform] = 'translate(' + real_size.x + 'px,' + real_size.y + 'px) scale(' + scale + ')';
	    	});
	    },
	    
	    throttle : function( method, context ){
	    	clearTimeout( method.tId );
	    	method.tId = setTimeout(function(){
	    		method.call( context );
	    	}, 100);
	    },
	    
	    renderPop : function( station_info ){
	    	var stationPop = $.parseTpl( $.templete.station_pop, station_info);
			this.$element.find('.station-pop').remove();
			$( stationPop ).appendTo( this.$element );
	    },
	    
	    render : function( station_list, linecolor, callback ){
			var _this = this;
			var parseTpl_func = $.parseTpl( $.templete.map_area_list ),
				html_str = '';
			linecolor && (this.linecolor = linecolor) || (linecolor = this.linecolor); 
			if( station_list.length ){
				$.each( station_list, function( key, value ){
					value['line'] = _this.handleLinecolor( value.sub_color, value.linename );
					html_str += parseTpl_func( value );
				} );
				this.$element.find('#subway-mapArea').html( html_str );
				$.isFunction( callback ) && callback();
			}
	    },
	    
	    handleLinecolor : function( color, title ){
			var colorline = [];
			if( title && color.length == 1){
				colorline.push(title.substring(0, 1));
			}else{
				$.each(this.linecolor, function(k, v){
					if($.inArray(v, color) > -1){
						colorline.push(k);
					}
				});
			}
			return colorline;
		},
	}
	
	function prefixStyle (style) {
        if ( vendor === '' ) return style;

        style = style.charAt(0).toUpperCase() + style.substr(1);
        return vendor + style;
    }

    dummyStyle = null;	// for the sake of it
	
	$.fn.customSlider = function(option, event) {
       return this.each(function () {
	      var $this = $(this)
	        , data = $this.data('customSlider')
	        , options = $.extend({}, typeof option == 'object' && option);
	      if (!data) $this.data('customSlider', (data = new customSlider(this, options)));
	     
	      if (typeof option == 'string') {
                property = option;
                if(data[property] instanceof Function) {
                    [].shift.apply(args);
                    value = data[property].apply(data, args);
                } else {
                    value = data[property];
                }
            }
	    });
    };
	
	$.fn.customSlider.defaults = {
		ratio : [0.4, 0.6, 0.8],
		adjust : {
			x : -1703.5,
			y : -1488.5
		},
	}
	
	
}( Zepto || null);
