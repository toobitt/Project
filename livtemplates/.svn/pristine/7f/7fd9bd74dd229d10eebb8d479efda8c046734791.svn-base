;$(function(){
	$.widget('seek.seeklist', {
		options : {
			statusUrl : "run.php?mid=" + gMid + "&a=audit",
		},
		_init : function(){
			this._on({
				'click .change-status' : '_showStatusMenu',
				'click .status-menu li' : '_changeStatus',
			});
		},
		_closeOtherMenu : function( li ){
			this.element.find('.common-list-data').not( li ).find('.status-menu').remove();
		},
		_showStatusMenu : function( e ){
			e.stopPropagation();
			var self = $(e.currentTarget),
				li = self.closest('.common-list-data');
			this._closeOtherMenu( li );
			if( self.find('.status-menu').length ){
				self.find('.status-menu').remove();
			}else{
				var param = {
						currentText : self.find('span').text(),
						up : li.siblings('.common-list-data').length - li.index() > 1 ? false : true
				};
				$('#seekhelp-list-drop').tmpl( param ).appendTo( self );
			}
		},
		_changeStatus : function( e ){
			e.stopPropagation();
			var self = $(e.currentTarget),
				li = self.closest('.common-list-data'),
				id = li.attr('_id'),
				status = self.attr('_status'),
				_this = this;
			var param = {
					id : id,
					status : status
				};
			$.globalAjax( self, function(){
				return $.getJSON( _this.options.statusUrl, param, function( json ){
					eval( json.callback );
//					var status = parseInt( json[0].status ),
//						currentStatus = self.closest('.common-list-item').find('.current-status');
//					currentStatus.text( seekhelpStatus[ status ] ).css('color', statusColor[ status ]);
					self.closest('.status-menu').remove();
				});
			});
				
		},
	});
	$('body').seeklist();
});