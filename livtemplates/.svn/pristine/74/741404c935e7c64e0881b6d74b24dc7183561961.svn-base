(function($){
	$.widget('split.live' , {
		options : {
			ohms : null,
			callback : $.noop,
			status : $.noop,
			checkSpilt : $.noop
		},
		
		_create : function(){
			
		},
		
		_init : function(){
			this._on({
				'click .modal-live-close' : 'hide',
				'click .quick-time-list li' : '_quickSelect',
//				'click .current-time' : '_getCurrent',
				'click .modal-live-save' : '_save',
				'click .modal-slide-up' : '_slideUp',
				'click .modal-slide-out' : '_slideDown',
				'click .check-spilt' : '_checkSpilt'
			});
			var _this = this;
			this.element.find( '.time-picker').on({
                mousedown : function( event ){
                    var self = $(event.currentTarget);
                    var disOffset = {left : -70, top : -66};
                    _this.options.ohms.ohms('option', {
                        time : self.val(),
                        target : self
                    }).ohms('show', disOffset);
                    return false;
                },
                set : function(event, hms){
                 	 var self = $(event.currentTarget);
                 	 var time = [hms.h, hms.m ,hms.s].join(':');
             		 self.val(time);
                	 self.attr('_start',time);
                	 var sTime = _this.element.find('input[name="start_time"]').val().split(':'),
             	 	 	 eTime = _this.element.find('input[name="end_time"]').val().split(':');
             	 	 	 sTime = (sTime[0])*3600 + (sTime[1])*60 + sTime[2],
             	 	 	 eTime = (eTime[0])*3600 + (eTime[1])*60 + eTime[2];
             	 	 _this.element.find('.quick-time-item').text('一键选取');
	             	 if( sTime && eTime ){
	             		if( sTime > eTime ){
	                		 _this._myTip(self , '初始时间不能大于结束时间');
	                		 self.val('');
	                		 self.attr('_start','');
	                	 }
	             	 }
                }
            });
		},
		
//		_getCurrent : function( event ){
//			var self = $(event.currentTarget ),
//				item = self.prev('.time-picker'),
//				now = new Date();
//			this.ntime = now.getTime();
//			var new_time = this._formatTime( this.ntime );
//			item.val( new_time );
//			item.attr('_start' , new_time );
//			
//		},
		
		_quickSelect : function( event ){
			var self = $( event.currentTarget ),
				sTime = this.element.find('input[name="start_time"]'),
				eTime = this.element.find('input[name="end_time"]'),
				val = self.attr('_value');
			var now = new Date();
				ntime = now.getTime();
			var now_time = this._formatTime( ntime );
			
			
			var start_time = ntime - val*60*1000;
			var new_time = this._formatTime( start_time );
			sTime.val( new_time );
			sTime.attr('_start' , new_time );
			eTime.val( now_time );
			sTime.attr('_start' , now_time );
			this.element.find('.quick-time-item').text( val + '分钟' );
		},
		
		_formatTime : function( ntime ){
			var new_time,
				time = new Date( ntime ),
				h = time.getHours(), 
				m = time.getMinutes(), 
				s = time.getSeconds();
			if(JSON.stringify(m).length == 1){
				m = '0' + m;
			}
			if(JSON.stringify(s).length == 1){
				s = '0' + s;
			}
			return new_time = h + ':' + m + ':' + s;
		},
		
		_save : function( event ){
			var self = $( event.currentTarget ),
				param = {};
				param.live_id = this.element.find('input[name="channel_id"]').val();
				param.start_time = this.element.find('input[name="start_time"]').val();
				param.end_time = this.element.find('input[name="end_time"]').val();
			var url = 'run.php?mid=' + gMid + '&a=create_live_to_vod',
				status_url = 'run.php?mid=' + gMid + '&a=get_make_livevideo_status';
			var _this = this;
			if( !param.live_id || !param.start_time || !param.end_time){
				this._myTip( self , '请选择时间段！');
				return false;
			}
			var wait = $.globalLoad( self );
			$.globalAjax( null , function(){
				return $.getJSON( url  + '&ajax=1' , param , function( data ){
					if( data['callback'] ){
						wait();
						eval( data['callback'] );
						return;
					}
					var setTime = setInterval(function(){
						$.globalAjax( null , function(){
							return $.getJSON( status_url + '&ajax=1', { live_data_id : data.id } , function( json ){	
								var channel = {
										id : param.live_id,
										live_data_id : data.id
								};
//								if( json.errorCode || json.errorText ){
//									_this._myTip( self , json.errorCode || json.errorText );
//									return;
//								}
								if( json['callback'] ){
									eval( json['callback'] );
									clearTimeout( setTime );
									wait();
									return;
								}
								if( json.status == 1 ){
									self.val( '直播视频生成中...');
								}else if( json.status == 3 ){
									wait();
									self.val('转码完成');
									setTimeout( function(){
										self.val('确定');
									} , 3000 );
									_this._trigger( 'callback' , this.element , [json.videoinfo , channel ] );
									$('.modal-live-box').find('.modal-slide-up').show();
									$('.modal-live-box').addClass('slide-down');
						            clearTimeout( setTime );
								}else if( json.status == 4){
									self.val( '直播视频转码中' );
								}
							});
						});
					} , 1000 );
				});
			});
		},
		
		_checkSpilt : function(){
			this._trigger( 'checkSpilt' , null , [null] );
			$('.modal-live-box').addClass('slide-down');
		},
		
		_slideUp : function(){
			$('.modal-live-box').addClass('slide-down');
			
		},
		
		_slideDown : function(){
			$('.modal-live-box').removeClass('slide-down');
		},
		
		_myTip : function( target , tip ){
			target.myTip({
				string : tip,
				width : 160,
				delay: 1000,
				dtop : 0,
				dleft : 20,
			});
		},
		
		hide : function(){
			var _this = this,
				target = this.element.find('.modal-live-close');
			jConfirm( '您确定关闭弹窗吗？', '关闭提醒', function( result ){
				if( result ){
					_this.element.remove();
					_this.options.ohms.ohms('hide');
					_this._trigger( 'status' , this.element , null );
				}
			}).position( target );
		},
		
		destroy: function() {
			$.Widget.prototype.destroy.call( this );
	    },
			
	});
})($);

