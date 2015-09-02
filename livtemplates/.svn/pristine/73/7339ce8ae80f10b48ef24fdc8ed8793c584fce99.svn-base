(function($){
	
	var defaultOptions = {
			getUrl: function( speid ){
				return './run.php?mid=' + gMid + '&a=get_scolumn&speid=' + speid;
			},
			change: $.noop
	}
	function Column( el,options ){
		var _this = this;
		this.options = options = $.extend( {}, defaultOptions, options )
		this.el = el;
		this.box = el.find('ul');
		this.firstTitle = el.find('.title-show');
		this.titleList = el.find('.title-list');
		this.titleNum = el.find('.title-number');
		this.pop_height = el.height();
		this.win_height = $(window).height();
		this.top = options.top;
		this.contentIds = el.find('.hidden-id');
		this.old_columnid = el.find('.old-columnid');
		this.column_name = el.find('.hidden-name');
		this.bundle_id = el.find('.bundle-id');
		this.module_id = el.find('.module-id');
		this.content_fromid = el.find('.content-fromid');
		this.cid = el.find('.c-id');
		this.init();
		el.on('click', '.publish-box-save', function(){
			el.find('form').submit();
		});
		
		el.on('click','.common-list-pub-close',function(){
			el.removeAttr('style');
		});
		
		el.on( 'click', '.column-item-each', function( event ){
			var self = $(event.currentTarget),
				radio = self.find( 'input' ),
				column_name = self.find('.name').text();
			if( radio.prop('checked') ){
				_this.options['column_name'] = column_name;
			}
		} );
		
		el.on('submit','form',function(){
			
			_this.saveResult();
			if( !_this.column_name.val() ){
				jAlert( '请选择栏目','移动提醒' );
				return false;
			}
			$(this).ajaxSubmit({
				success : function(){
					_this.el.find('.common-list-pub-close').trigger('click');
					_this.options.change.call(_this);
				}
			});
			return false;
		});
	}
	
	$.extend( Column.prototype,{
		init : function(){
			this.initData();
		},
		initData : function(){
			var _this = this,
				url = this.options.getUrl.call( this, this.options.speid );
			$.getJSON( url, function( data ){
				var data = data[0];
				$('#column-tmpl-list').tmpl( data ).appendTo( _this.box );
				_this.syncSelect( _this.options.ids );
			} );
			this.showTitle( this.options.ids );
			this.show( this.options.top );
		},
		refersh : function( options ){
			this.options = $.extend( this.options, options );
			this.show();
			this.showTitle( options.ids );
			this.syncSelect( options.ids );
		},
		show : function( ){
			var top = this.options.top,
				win_height = this.win_height,
				pop_height = this.pop_height,
				adjust_top = '';
			if( top + pop_height > win_height ){
				adjust_top = top-pop_height;
			}else{
				adjust_top = top;
			}
			this.el.css( {top: adjust_top+ 'px',opacity: 1} );
		},
		saveResult : function(){
			this.contentIds.val( this.options.ids.join(',') );
			this.old_columnid.val( this.options.column_ids.join(',') );
			this.bundle_id.val( this.options.bundle_ids.join(',') );
			this.content_fromid.val( this.options.content_fromids.join(',') );
			this.module_id.val( this.options.module_ids.join(',') );
			this.column_name.val( this.options.column_name );
			this.cid.val( this.options.cids.join(',') );
		},
		
		showTitle : function( ids ){
			var html = '';
			$.each( ids, function( key,value ){
				var title = $.globalData[value]['title'],
					item = '<p>' + title + '</p>';
				html+= item;
			} );
			this.firstTitle.text( $.globalData[ids[0]]['title'] );
			this.titleList.html( html );
			this.titleNum.html( ids.length );
		},
		syncSelect : function( ids ){
			if( ids.length ==1 ){
				var column_id = $.globalData[ids]['column_id'];
				this.options.column_name = $.globalData[ids]['column_name'];
				this.el.find('input[type="radio"]').each( function(){
					if( $(this).val() == column_id ){
						$(this).attr( 'checked','checked' );
						return false;
					}
				} );
			}else{
				this.options.column_name = '';
				this.el.find('input[type="radio"]').removeAttr('checked');
			}
		}
	} );
	
	$.fn.hg_moveColumn = function( options ){
		var el = $(this);
		if( el.data('column') ){
			var _this = el.data('column');
			_this.refersh( options );
		}else{
			el.data('column',new Column( el,options ));
		}
	}
	
})($);