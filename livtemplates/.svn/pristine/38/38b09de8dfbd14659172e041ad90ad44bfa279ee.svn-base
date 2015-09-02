(function(){
	$.widget('hospital.doctorpop', {
		options : {
			popdoctortpl : '',
			popdoctortname : 'popdoctor-tpl'
		},
		
		_create : function(){
			var op = this.options;
			$.template(op.popdoctortname, op.popdoctortpl);
			this.content = this.element.find('.pop-add-content');
		},
		
		_init : function(){
			this._on({
				'click .save-button' : '_save',
				'click .pop-close' : '_close',
			});
			this._root();	
		},
		
		_root : function(){
			return;
		},
		
		_tmpl : function( option ){
			$.extend( option, this.options )
			$.tmpl( this.options.popdoctortname, option) .appendTo( this.content.empty() );
			this._initForm();
		},
		
		_change : function( param ){
			var _this = this;
			this._delay( function(){
				_this._close();
				_this.options.callback( param );
			}, 2000 );
		},
		
		_close : function(){
			this.element.addClass('pop-hide');
		},
		
		_show : function( level, method ){
			this.element.removeClass('pop-hide');
		},
		
		_before : function(){
			return 111;
		},
		
		_save : function(){
			var form = this.element.find('.pop-form');
			this._change( form.serializeArray() );
			//form.submit();
		},
		
		_initForm : function(){
			var _this = this,
				btn = this.content.find('.save-button');
			this.element.find('.pop-form').submit(function(){
				var $this = $(this);
				$this.ajaxSubmit({
					beforeSubmit : function(){
						var tip = _this._before( $this );
						if( tip ){
							_this._myTip(btn, tip);
							return false;
						}
					},
					dataType : 'json',
					success : function( json ){
						if( json && json['callback'] ){
							eval( json['callback'] );
							return;
						}else if( $.isArray( json ) && json[0] == 'success' ){
							console.log( json );
							_this._change( $this.serializeArray() );
							_this._myTip(btn, '保存成功');
						}
					},
					error : function(){
						_this._myTip(btn, '保存失败');
					}
				});
				return false;
			});
		},
		
		_myTip : function( dom, str, left ){
			dom.myTip({
				string : str,
				delay: 2000,
				dtop : 5,
				dleft : left || 130,
				width : 'auto',
				padding: 10
			});
		},
		
		refresh : function( param ){
			this._tmpl({
				date : param.date,
				allDay : param.allDay
			});
			this._show();
		},
	});
})();
