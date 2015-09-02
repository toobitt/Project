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
	
	var pluginName = 'tel_lottery';
	
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
			this.samebtn = (!op.endbtn || op.startbtn == op.endbtn);
			this.action = 'start';
			this.rotate_index = 0;
			this.storage = $.Hg_localstorage( {key : op.key} );
			(this.results = {})[ op.step ] = [];
			//this.storage.clearItem();
			this.candidates = this.filter();
			
		},
		
		filter : function(){
			var op = this.op,
				newData = op.defaultData,
				localstorage = this.results = this.localstorage();
			if( !$.isArray( localstorage ) ){
				if( localstorage[ op.step ] ){
					this.show_result( localstorage[ op.step ] );
					return newData;
				}else{
					var items = this.array_make( localstorage );
					//this.array_del_all( newData,  );
					return newData;
				}
			}
			return newData;
		},
		
		bindEvent : function(){
			var _this = this,
				op = _this.op;
			_this.$( op.startbtn ).on('click', function(){
				_this.process();
			});
		},

		process : function(){
			var _this = this;
			switch( _this.action ){
				case 'start' : {
					_this.action = 'stop';
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
			var _this = this,
				op = _this.op;
			_this.arry_shuffle( _this.candidates );
			_this.rotate_run();
			
			if( _this.samebtn ){
				_this.$( op.startbtn ).html( '暂停' );
			}
		},
		
		rotate_end : function(){
			var _this = this,
				op = this.op;
			if( _this.samebtn ){
				_this.$( op.startbtn ).html( '抽奖' );
			}
		},
		
		rotate_run : function(){
			var _this = this,
				op = _this.op;
			var arr = this.array_slice( this.candidates, this.rotate_index, op.num );
			this.rotate_index += parseInt( op.num );
			
			if( this.rotate_index >= this.candidates.length ){
				this.rotate_index -= this.candidates.length;
			}
			if( _this.action == 'end' ){
				_this.action = 'start';
				
				//_this.candidates = _this.array_del_all(_this.candidates, arr);
				if( op.url ){
					_this.ajaxData();
				}
				if( arr.length ){
					_this.get_result( arr );
				}
				
			}else{
				_this.show_result( arr );
				setTimeout( function(){
					_this.rotate_run();
				}, op.interval );
			}
		},
		
		ajaxData : function(){
			var _this = this,
				op = this.op,
				param = $.extend(op.data, {
					count : op.num
				});
			$.ajax({
				url : op.url,
				data : op.data,
				dataType : 'json',
				beforeSend  : function(){
					
				},
				success : function( data ){
					if( data && data['ErrorCode'] ){
						_this.$( op.showArea ).html( '<p class="title">' + data['ErrorCode'] + '</p>' );
						return;
					}
					if( $.isArray( data ) && data[0] ){
						var tels = _this.array_map( data, 'tel' );
						_this.get_result( tels );
					}
				}
			})
		},
		
		localstorage : function( method ){
			if( method == 'addItem' ){
				this.storage.resetItem( this.results );
				return;
			}
			var values = this.storage.getItem();
			return values;
			
		},
		
		get_result : function( arr ){
			var values = this.localstorage();
			this.results = $.isArray( values ) && values[0] ? {} : values;
			this.results[ this.op.step ] = arr;
			this.show_result();
			this.localstorage( 'addItem' );
		},
		
		show_result : function( arr ){
			arr = arr || this.results[ this.op.step ];
			var html = '<h2>中奖号码</h2>';
			for( var i=0, len=arr.length; i<len; i++ ){
				html += ('<p>' + arr[i] + '</p>');
			}
			this.$( this.op.showArea ).html( html );
		},
		
		array_map : function( data, type ){
			return $.map(data, function( vv ){
				return vv[ type ]
			});
		},
		
		// 从数组中删除元素. 返回新数组
		array_del_all : function( list, items ){
			var _this = this,
				arr = [];
			for( var i=0, len=list.length; i<len; i++ ){
				if( _this.array_find( items, list[i] ) == -1 ){
					arr.push( list[i] );
				}
			}
			return arr;
		},
		
		//返回指定元素在数组中的索引
		array_find : function( list, item ){
			for( var i=0, len = list.length; i<len; i++ ){
				if( item == list[i] ){
					return i
				}
			}
			return -1;
		},
		
		// 扰乱数组元素的顺序
		arry_shuffle : function( list ){		
			var len = list.length;
			for(var i=0; i<len*5; i++){
				var p1 = parseInt(len * Math.random());
				var p2 = parseInt(len * Math.random());
				var tmp = list[p1];
				list[p1] = list[p2];
				list[p2] = tmp;
			}
		},
		
		//转化为数组
		array_make : function( data ){
			return $.map(data, function( vv ){
				return vv;
			});
		},
		
		//把数组当做环形，返回start开始，一共count个元素
		//如果count大于数组长度，返回整个数组
		array_slice : function( list, start, count){	
			var ret = list.slice( start, start + count );
			if( ret.length < count ){
				ret = ret.concat( list.slice(0, count - ret.length) );
			};
			return ret;
		},
		
		$ : function( s ){
			return this.el.find(s);
		}
	};
	$.plugin(pluginName, Plugin);

	$.fn[pluginName].defaults = {
		startbtn : '.lottery',
		showArea : '.lottery-area',
		endbtn : '',
		num : 1,
		key : 'lottery_result',
		step : 1,
		url : '',
		interval : 100,
		defaultData : ['13280863475', '15257329034', '15290438924', '13648290544', '15850594304', '15034394805', 
		'13280863475', '15257329034', '15290438924', '13648290544', '15850594304', '15034394805', 
		'13280863475', '15257329034', '15290438924', '13648290544', '15850594304', '15034394805'],
		//defaultData : [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
	}
})( window.jQuery || window.Zepto );

$(function(){
	var lottery = $( '.lottery-wrap' );
	lottery.each(function( i ){
		var $this = $(this);
		$this.tel_lottery({
			startbtn : '.lottery',
			endbtn : '.lottery',
			showArea : '.lottery-area',
			num : parseInt( $this.attr('_num') ),
			step : parseInt( $this.attr('_step') ),
			url : 'http://localhost/livsns/api/feedback/feedback.php?a=get_winners&clear=1',
			data : {
				id : 39
			}
		});
	});
})
