$(function(){
	(function($){
		$.widget('workload.search' , {
			options : {
				callback_1 : '',
				callback_2 : '',
				callback_3 : '',
				callback_4 : ''
			},
			
			_create : function(){
				
			},
			
			_init : function(){
				this._on({
					'click .search-item-title' : '_selectItem',
					'click .time-list li' : '_tiemSearch',
					'change .date-picker' : '_customDate',
					'click .type-list input' : '_typeList'
				});
				$('.search-info').hide();
				$('body').on('click' , function( e ){
					var self = target = $( e.target );
					if(self.closest(".search-item-title").length == 0 && self.closest('.search-info').length ==0){ 
						if( self.closest('.ui-corner-all').length == 0){
							$('.search-info').hide();
						}
					}
				});
			},
			
			_selectItem : function( event ){
				var target = $( event.currentTarget ),
					item = target.closest('.search-item').find('.search-info');
				item.toggle();
			},
			
			_tiemSearch : function( event ){
				var target = $( event.currentTarget ),
					value = target.attr('_value'),
					txt = target.text(),
					item = target.closest('.search-item'),
					url = target.closest('.search-box').attr('_url');
				target.addClass('current').siblings().removeClass('current');
				item.find('.search-item-title').text( txt );
				item.find('input[type="hidden"]').val( value );
				item.find('.date-picker').val('');
				var param = {};
				param.date_search = value;
				param.app = target.closest('.search-box').find('input[name="app"]').val();
				this._getInfo( url , param );
			},
			
			_customDate : function( event ){
				var target = $( event.currentTarget ),
					g_parent = target.closest('.search-box')
					parent = target.closest('.search-info'),
					url = target.closest('.search-box').attr('_url');
		    		startTime = parent.find('.date-picker[name="start_time"]').val(),
		    		endTime = parent.find('.date-picker[name="end_time"]').val(),
		    		startTime = startTime.replace(/-/g,'/'),
		    		endTime = endTime.replace(/-/g,'/'),
					startTime = new Date(startTime),
					endTime = new Date(endTime),
					endTime = endTime.getTime(),
					startTime = startTime.getTime();
				parent.find('input[name="date_search"]').val(5);
				target.closest('.search-item').find('.search-item-title').text('自定义时间');
				parent.find('li').removeClass('current');
		    	if( startTime && endTime ){
		    		if(startTime > endTime ){
		    			target.myTip({
							string : '初始时间不能大于结束时间',
							width : 170,
							delay: 1000,
							dtop : 0,
							dleft : 120,
						});
		    			target.val('');
		    		}else{
		    			var param = {};
						param.date_search = parent.find('input[name="date_search"]').val();
						param.app = g_parent.find('input[name="app"]').val();
						param.start_time = parent.find('.date-picker[name="start_time"]').val();
						param.end_time = parent.find('.date-picker[name="end_time"]').val()
						this._getInfo( url , param );
		    		}
		    	}
			},
			
			_typeList : function( event ){
				var target = $( event.currentTarget ),
					parent = target.closest('.search-box'),
					checked = target.prop('checked'),
					value = target.attr('_value'),
					item = target.closest('.search-item'),
					typelist = target.closest('.type-list'),
					url = target.closest('.search-box').attr('_url');
				var app = typelist.find('li').map(function(){
					var checked = $(this).find('input').prop('checked');
					if( checked ){
						return $(this).attr('_value');
					}
				}).get().join(',');
				item.find('input[type="hidden"]').val( app );
				var param = {};
				param.date_search = parent.find('input[name="date_search"]').val();
				param.app = app;
				if( param.date_search == 5){
					param.start_time = parent.find('.date-picker[name="start_time"]').val();
					param.end_time = parent.find('.date-picker[name="end_time"]').val()
				}
				this._getInfo( url , param );
			},
			
			_getInfo : function( url , param ){
				if( url ==1 ){
					this._trigger('callback_1' , null , [param]);
				}else if( url == 2){
					this._trigger('callback_2' , null , [param]);
				}else if( url == 3){
					this._trigger('callback_3' , null , [param]);
				}else if( url == 4){
					this._trigger('callback_4' , null , [param]);
				}
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
			
		})
	})($);
})