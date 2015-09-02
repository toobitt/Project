$(function(){
	
	(function($){
		$.widget( 'program.calendar', {
			options : {
				channel_id : '',
				events : [],
				del_tmpl : '#event-del-tmpl',
				setUrl : '',
				redict_url : ''
			},
			_create : function(){
				
			},
			_init : function(){
				this._on( {
					'click .fc-day' : '_click',
					'click .fc-day-del' : '_delEvent',
					'mouseover .fc-day' : '_over',
					'mouseout .fc-day' : '_out'
				} );
				this._initCalendar();
			},
			_initCalendar : function(){
				var _this = this,
					op = this.options,
					events = op.events;
				this.element.hg_fullCalendar( {
					events : events,
					drop: function(date, allDay, event, ui) { 
						var eventObject = $(this).data('eventObject'),
							formatdate = _this.formatDate( date );
						var copiedEventObject = $.extend( {}, eventObject);
						copiedEventObject.start = formatdate;
						copiedEventObject.allDay = allDay;
						_this.removeEvent( formatdate );
						_this.renderEvent( copiedEventObject );
						_this.controllTemplate( { templateId : eventObject.eid, date:formatdate } );

					}
				} );
			},
			_removeEvent : function( id ){
				this.element.fullCalendar( 'removeEvents', id );
			},
			formatDate : function( date ){
				var date = $.fullCalendar.formatDate( date,'yyyy-MM-dd' );
				return date;
			},
			removeEvent : function( date ){
				var _this = this,
					isEvent = false;
				this.element.find('.fc-event').each( function(){
					if( $(this).data('date') == date ){
						var id = $(this).data('flag');
						isEvent = true;
						_this._removeEvent( id );
						return false;
					}
				} );
				return isEvent;
			},
			renderEvent : function( eventObject ){
				this.element.fullCalendar('renderEvent', eventObject, true);
			},
			controllTemplate : function( data ){
				var op = this.options,
					url = op.setUrl,
					data = data || {};
				data.channelId = op.channel_id;
				$.post( url, data, function(){
					
				} );
			},
			_click : function( event ){
				var op = this.options;
					date = $( event.currentTarget ).data('date'),
					url = '&channel_id=' + op.channel_id +'&dates=' + date;
				window.location = op.redict_url += url ;
			},
			_delEvent : function( event ){
				var self = $( event.currentTarget ),
					date = self.closest('td').data('date');
				self.hide();
				this.removeEvent( date );
				this.controllTemplate( { date: [date ] } );
				event.stopPropagation();
			},
			_over : function( event ){
				this._toggleDel( event );
			},
			_out : function( event ){
				this._toggleDel( event, true );
			},
			_toggleDel : function( event, hide ){
				var item = $( event.currentTarget ),
					date = item.data('date'),
					del = item.find('.fc-day-del');
				var isShow = false;
				if( hide ){
					del.hide();
					return;
				}
				this.element.find('.fc-event').each( function(){
					var event_date = $(this).data( 'date' );
					if( event_date == date ){
						isShow = true;
						return false;
					}
				} );
				isShow ? del.show() : del.hide();
			}
			
		} );
		
		$.widget( 'program.template', {
			options : {
				ajaxUrl : '',
				containment : '.wrap',
				helper_css : 'dragg-class',
				preview : null,
				week : null
			},
			_create : function(){
				this.ajaxCache = {};
			},
			_init : function(){
				this._on( {
					'click .preview' : '_preview'
				} );
				this._initDraggable();
			},
			_initDraggable : function(){
				var op = this.options,
					containment = op.containment,
					help_css = op.helper_css;
				var week = op.week;
				this.element.find('li').each( function(){
					var self = $(this),
						title = $.trim( self.text() ),
						id = self.data( 'id' );
					$(this).data( 'eventObject', { title : title, eid : id });
					$(this).draggable({
						helper : function(){
							var helper = $(this).clone().find('.title');
							return $( helper ).addClass( help_css );
						},
						zIndex: 999,
						containment : containment,
						start : function( event, ui ){
							week.week( 'heightLight', 'add' );
						},
						stop : function(){
							week.week( 'heightLight', 'remove' );
						}
					});
				} );
			},
			_preview : function( event ){
				var _this = this,
					self = $( event.currentTarget ),
					item = self.closest( 'li' ),
					id = item.data( 'id' ),
					title = item.data( 'title' ),
					preview = this.options.preview;
				var hash = + new Date() + Math.ceil( Math.random() * 1000 );
				self.data( 'ajaxhash', hash );
				preview.preview( 'show' );
				if( this.ajaxCache[id] ){
					preview.preview( 'instanceTmpl', this.ajaxCache[id], title );
					return;
				}
				$.globalAjax( preview, function(){
					return $.getJSON( _this.options.ajaxUrl, { id : id }, function( data ){
						if( hash != self.data('ajaxhash') ) return;
						var data = data[0],
							list = [];
						if( data ){
							list = $.map( data['data'], function( value, key ){
								var info = {};
								info.data = value;
								info.title = ( key == 'am' ) ? ' 上午' : '下午' ; 
								return info;
							} );
							_this.ajaxCache[id] = list;
							preview.preview( 'instanceTmpl', list, title );
						}
						
					} );
				} );
			}
			
		} );
		
		$.widget( 'program.week', {
			options : {
				overdayClass : 'day-over',
				overweekClass : 'week-over',
				show : 'show',
				content : '点击删除一列节目模板',
				calendar : null
			},
			_create : function(){
				
			},
			_init : function(){
				this._on( {
					'click li' : '_click'
				} );
				this._initTip();
				this._initDroppable();
			},
			_initTip : function(){
				var content = this.options.content;
				this.element.tooltip( {
					items : 'li' ,
					content : content,
					track : true
				} );
			},
			_initDroppable : function(){
				var _this = this,
					Calendar = this.options.calendar,
					overClass = this.options.overClass;
				this.element.find( 'li' ).each( function(){
					$(this).droppable( {
						drop : function( event,ui ){
							var helper = $( ui.helper ),
								index = $( this ).index(),
								id = helper.data('id'),
								title = helper.data('title');
							var dates = [];
							var columns = _this._changeStatus( $(this),index, 'remove'  );
							$.each( columns,  function( key, value ){
								var date = value.data('date');
								dates.push( date );
								Calendar.calendar( 'removeEvent', date );
								Calendar.calendar('renderEvent', { eid : id,title : title, start : date } );
							} );
							Calendar.calendar('controllTemplate', { templateId : id, date : dates } );
						},
						over : function( event, ui ){
							var index = $( this ).index();
							_this._changeStatus( $(this), index, 'add');
						},
						out : function( event, ui ){
							var index = $( this ).index();
							_this._changeStatus( $(this), index, 'remove');
						}
					} );
				} );
			},
			_filterColumns : function( index ){
				var columns = [],
					Calendar = this.options.calendar;
				columns = Calendar.find('.fc-week').map( function(){
					var items = $(this).find('td').eq( index );
					return items;
				}).get();
				return columns;
			},
			_changeStatus : function( self, index, type ){
				var overdayClass = this.options.overdayClass,
					overweekClass = this.options.overweekClass,
					columns = this._filterColumns(index);
				self[ type + 'Class' ]( overweekClass );
				$.each( columns, function( key, value ){
					value[ type + 'Class' ]( overdayClass );
				} );
				return columns;
			},
			_click : function( event ){
				var Calendar = this.options.calendar,
					self = $( event.currentTarget ),
					index = self.index(),
					dates =[];
				Calendar.find('.fc-week').each( function(){
					var date = $(this).find('td').eq( index ).data('date');
					var isEvent = Calendar.calendar( 'removeEvent', date );
					isEvent && dates.push( date );
				} );
				dates.length && Calendar.calendar( 'controllTemplate', { date:dates } );
			},
			heightLight : function( method ){
				this.element.find( 'li' )[ method + 'Class' ]( this.options.overweekClass );
				MC.tip.toggleClass( this.options.show );
			}
		} );
		
		$.widget( 'program.preview', {
			options : {
				tmpl : '#preview-tmpl',
				title : '.preview-box-title',
				content : '.preview-box-content',
				show : 'show'
			},
			_create : function(){
				var op = this.options;
				this.box = this.element.find( op['content'] );
				this.title = this.element.find( op['title'] );
			},
			_init : function(){
				this._on( {
					'click .preview-box-close' : '_close'
				} );
			},
			instanceTmpl : function( data, title ){
				var op = this.options;
				this.box.html( $( this.options.tmpl ).tmpl( data ) );
				this.title.text( title );
			},
			_close : function(){
				this.element.removeClass( this.options.show );
			},
			show : function(){
				this.element.addClass( this.options.show );
			}
		} );
		
	} )($);
	
	var MC = {
			calendar : $('#calendar'),
			week : $('.week-list'),
			template : $('.program-template'),
			preview : $('.preview-box'),
			tip : $('.week-tip') 
	};
	
	var channel_id = MC.calendar.data('channel');
	
	MC.calendar.calendar( {
		channel_id : channel_id,
		events : $.events,
		setUrl : './run.php?mid=' + gMid + '&a=programTemplateSet',
		redict_url : './run.php?a=relate_module_show&app_uniq=program&mod_uniq=program&mod_a=show&mod_main_uniq=channel&infrm=1'
	} );
	MC.week.week( {
		calendar : MC.calendar
	} );
	MC.template.template({
		week : MC.week,
		preview : MC.preview,
		ajaxUrl : './run.php?mid=' + gMid + '&a=detail'
	});
	MC.preview.preview({
	});

	
});