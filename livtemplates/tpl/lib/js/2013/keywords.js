(function($){
	function Keywords( el, options ){
		this.$el = el;
		this.options = options;
		this.defalut = this.$('#keywords-box').attr('_value');
		this.addbox = this.$('.keywords-add');
		this.startbox = this.$('.keywords-start');
		this.init( options );
	}
	$.extend(Keywords.prototype, {
		init : function( options ){
			this.bindEvent();
			this.initEvent( options );
		},
		
		initEvent : function( options ){
			var val = options&&options.val || this.$('#keywords').val();
			if( val ){
				var Aval = val.split(',');
				this.append( Aval );
			}
		},
		
		delEvent : function(){
			var item = this.$el.find('.each-keyword-item');
			if( item.length ){
				item.each(function(){
					$(this).find('.keywords-del').click();
				});
			}
		},
		
		bindEvent : function(){
			var _this = this;
			this.$el
			.on('mouseenter', '.each-keyword-item', $.proxy(this.enter, this))
			.on('mouseleave', '.each-keyword-item', $.proxy(this.leave, this))
			.on('click', '.keywords-start', $.proxy(this.start, this))
			.on('click', '.keywords-add', $.proxy(this.add, this))
			.on('click', '.each-keyword-item-span', $.proxy(this.each, this))
			.on('click', '.keywords-del', $.proxy(this.del, this));
		},
		
		del : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('.each-keyword-item');
			parent.remove();
			if( !this.$('.each-keyword-item').length ){
				this.startbox.show();
				this.addbox.hide();
				this.$el.css('padding-bottom', '15px');
			}
			this.values();
		},
		
		each : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			var val = self.text();
			self.closest('.each-keyword-item').addClass('editing');
			var edit = $('<input type="text" style="width:50px;" value="' + val + '" _defaultValue="' + val + '" /><span class="keywords-del"></span>').appendTo( self.parent().empty() );
			edit.on('blur', function(){
				var  sval= $(this).val(),
					_dval = $(this).attr('_defaultValue');
				if( !sval ){
					sval = _dval;
				}
				$(this).closest('.each-keyword-item').removeClass('editing hover').html('<span class="each-keyword-item-span">' + sval +'</span><span class="keywords-del"></span>');
				_this.values();
			}).on('click', function(){
				event.stopPropagation();
			}).focus();
		},
		
		start : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			this.appendInput( self, function( result ){
				if( result ){
					self.hide();
					_this.move();
				}else{
					self.show();
					_this.$el.css('padding-bottom', '15px');
				}
			} );
			self.hide();
			event.stopPropagation();
		},
		
		add : function( event ){
			var self = $(event.currentTarget),
				_this = this;
			this.appendInput(self, function( result ){
				if( result ){
					_this.move();
				}else{
					self.show();
				}
			});
			self.hide();
		},
		
		appendInput : function(obj, callback){
			var _this = this;
			var input = obj.after('<span class="each-keyword-input"><input type="text" /></span>').next().find('input').focus();
			input.on('blur', function(){
				var val = $.trim($(this).val());
				if( !val || val == _this.defalut ){
					callback( false );
				}else{
					var item = _this.appendItem( $(this).val() );
					obj.after( item );
					_this.values();
					callback( true );
				}
				$(this).parent().remove();
			});
		},
		
		appendItem : function( val ){
			return '<span class="each-keyword-item"><span class="each-keyword-item-span">' + val +'</span><span class="keywords-del"></span></span>';
		},
		
		$: function(s) {
			return this.$el.find(s);
		},
		
		append : function( val ){
			var _this = this;
			if( !this.$('.each-keyword-item').length ){
				this.startbox.hide();
			}
			if( !$.isArray( val ) ){
				val = [val];
			}
			var obj = this.$('#keywords-box');
			$.each(val, function(k, v){
				var item = _this.appendItem( v );
				obj.append( item );
			});
			this.move();
			this.values();
		},
		
		move : function(){
			this.addbox.appendTo( this.addbox.parent() ).show();
			this.$el.css('padding-bottom', '10px');
		},
		
		values : function(){
			var words = [];
			this.$('.each-keyword-item').each(function(){
				words.push($(this).text());
			});
			this.$('#keywords').val( words.join(',') );
		},
		
		enter : function( event ){
			var self = $(event.currentTarget);
			if( self.hasClass('editing') ){
				return;
			}
			if( !self.hasClass('hover') ){
				self.addClass('hover')
			}
		},
		
		leave : function( event ){
			var self = $(event.currentTarget);
			if( self.hasClass('editing') ){
				return; 
			}
			if( self.hasClass('hover') ){
				self.removeClass('hover')
			}
		}
	});

	$.fn.hg_keywords = function( options ){
		return this.each(function(){
			$(this).data('keywords', new Keywords($(this), options));
		});
	}
})(jQuery);
