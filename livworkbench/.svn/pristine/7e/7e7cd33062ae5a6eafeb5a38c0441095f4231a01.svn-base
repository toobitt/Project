(function($){
	var GLOBAL = {
			dateInit : false,
			programInit : false,
			refreshFlash : function(){
				console.log(Math.random());
			}
	};
	$.widget('hoge.channel',{
		options : {
			item : '.channel-item',
			programwrap : '.program'
		},
		_init : function(){
			var handlers = {},
				op = this.options;
			handlers['click' + op['item']] = '_changeChannel';
			this._on(handlers);
			this._initChannel();		//初始化频道列表
		},
		_initChannel : function(){
			$('.channel-item:first-child').click();
		},
		_changeChannel : function( event ){
			var self = $(event.currentTarget);
			$('.current-channel').text( self.text() );
			self.addClass('current').siblings().removeClass('current');
			var op = this.options;
			if( op.date ){
				if( GLOBAL.dateInit ){
					this.element.date('refresh');
				}else{
					this.element.date();
				}
			}else if( op.program ){
				if( GLOBAL.programInit ){
					this.element.program('refresh');
				}else{
					this.element.program();
				}
			}else{
				GLOBAL.refreshFlash();
			}
		},
	});
	$.widget('hoge.date',{
		options : {
			item : '.date-item',
		},
		_init : function(){
			this._on({
				'click .date-item' : '_changeDate',
			});
			GLOBAL.dateInit = true;
			this.getDateData();
		},
		getDateData : function(){
			var url = 'newfile.php?a=dateList&type=2',
				param = {};
			param.id = 'channelId';
			$.getJSON(url, param, function( data ){
				var infos = [];
				$.each(data,function(k, v){
					var info = {};
					info.date = v.date;
					info.day = v.day;
					info.month = v.month;
					info.weekday = v.weekday;
					infos.push(info);
				});
				$('.current-channel').text($('.channel-item.current').text());
				$('.date-list').empty();
				$('#date-item-tpl').tmpl(infos).appendTo('.date-list');
				$('.date-list').find('li:last-child').click();
			});
		},
		_changeDate : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			self.addClass('current').siblings().removeClass('current');
			var op = this.options;
			if( GLOBAL.programInit ){
				this.element.program('refresh');
			}else{
				this.element.program();
			}
		},
		refresh : function(){
			this.getDateData();
		}
	});
	$.widget('hoge.program',{
		_init : function(){
			this._on({
				'click .program-item.option ' : '_changeProgram',
				'click .back-live' : '_backLive'
			});
			GLOBAL.programInit = true;
			this.getProgramList();
		},
		getProgramList : function(){
			var url = 'newfile.php?a=program&type=3',
				info = {
					'channel_id' : $('.channel-item.current').attr('_id'),
					'dates' : '2013-10-30',
					'play_time' : 0,
					'shownums' : 6,
					'_' : 134123
				};
			var _this = this;
			$.getJSON(url,info,function( data ){
				var infos = [];
				$.each(data,function(k,v){
					var info = {
							time : v.time,
							name : v.name,
							detail : v.detail
					};
					infos.push(info);
				});
				$('.program-list').empty();
				$('#program-item-tpl').tmpl(infos).appendTo('.program-list');
				_this._markLive( $('.program-list') );
			});
		},
		_changeProgram : function( event ){
			var self = $(event.currentTarget);
			self.addClass('current').siblings().removeClass('current');
			var url = '',
				info = {
					'channel_id' : $('.channel-item.current').attr('_id'),
					'play_time' : 12345,
					'dates' : $('.date-item.current').attr('_date'),
					'shownums' : 6,
					'_' : 54321
			};
			$.get(url,info,function( data ){
//					console.log(data);
			});
			GLOBAL.refreshFlash();
		},
		refresh : function(){
			this.getProgramList();
		},
		_markLive : function( ul ){
			var items = ul.find('li');
			var live = items.filter(function(){
				return $(this).hasClass('live');
			});
			$('<span />').text('当前直播').appendTo(live).addClass('live-flag');
			live.click();
		},
		_backLive : function(){
			$('.program-item.live').click();
		}
	});
})(jQuery)