jQuery(function(){
	(function($){
		$.widget('mode.parseParam',{
			options : {
				'mode-form' : '#mode-form',
				'mode-area' : '.mode-area',
				'parse-textarea' : '.parse-textarea',
				'new-parse-textarea' : '.add-parse-textarea',
				'css-parse-textarea' : '.css-parse-textarea',
				'param-box' : '.form-box',
				'param-each' : '.form-each',
				'param-mark' : '.mark-title', 
				'param-tpl' : '#param-tpl',
				'add-css-tpl' : '#css-tpl',
				'mode-css-add' : '.mode-css-add',
				'mode-css-del' : '.mode-css-del',
				'mode-css-copy' : '.mode-css-copy',
				'mode-css-list' : '.mode-css-list',
				'mode-css-item' : '.mode-css',
				'new-mode-css' : '.hased-mode-css',
				'add-mode-css' : '.add-mode-css',
				'css-name' : '.css-name',
				'css-num' : '#css_num',
				'css_arrs' : '#css_arrs',
				'css_ids' : '#css_ids',
				'mode-select' : '.mode-select',
				'drop-list' : '.drop-select',
				'mode-select-value' : '.mode-select-value,.mode-select-key',
				'mode-select-add' : '.mode-select-add',
				'drop-list-item' : '.drop-list-item',
				'drop-slide-btn' : '.drop-slide-btn',
				'drop-select-slide' : 'drop-select-slide',
				'm2o-savae-as' : '.m2o-savae-as',
				'mode_html' : '#mode_html',
				'out_arment' : '.out_arment',
				'form-each' : '.form-each',
				'out_arment-title' : '.out_arment-title',
				'del-param' : '.del-param',
				'para-html-tpl' : '#para-html-tpl',
				'disable-flag' : 'disable-flag',
				'out_arment_flag' : '.out_arment_flag',
				'editor-box' : '.editor-box',
				'style-item' : '.form-text',
				'editor-module' : '.m2o-o',
				'param-item' : '.form-item',
				'para-list' : '.para-list',
				'para-item' : '.paralist',
				'arrow' : '.arrow',
				'css-preview-file' : '.css-preview-file',
				'css-preview' : '.css-preview'
			},
			_create : function(){
				$.info = {};
			},
			_init : function(){
				var op = this.options,
				    handlers = {};
				handlers['blur '+ op['parse-textarea'] ] = '_parse';
				handlers['click '+ op['css-preview'] ] = '_uploadCssPic';
				handlers['change '+ op['css-preview-file'] ] = '_changeCssPic';
				handlers['click '+ op['del-param'] ] = '_delParam';
				handlers['click '+ op['arrow'] ] = '_toggleParamBox';
				handlers['click '+ op['para-item'] ] = '_setParam';
				handlers['click '+ op['param-mark'] ] = '_highlight';
				handlers['click '+ op['mode-css-add'] ] = '_addCss';
				handlers['click '+ op['mode-css-copy'] ] = '_copyCss';
				handlers['click '+ op['mode-css-del'] ] = '_delCss';
				handlers['change '+ op['mode-select'] ] = '_setSelect';
				handlers['click '+ op['drop-slide-btn'] ] = '_slideList';
				handlers['blur '+ op['mode-select-value'] ] = '_removeItem';
				handlers['click '+ op['mode-select-add'] ] = '_addItem';
				handlers['click '+ op['m2o-savae-as'] ] = '_saveAs';
				handlers['submit '+ op['mode-form'] ] = '_modeForm';
				this._on(handlers);
				this._on( {
					'click .set-default-btn>input' : '_setDefaultCss'
				} );
				this._showCssDel();
				this._initEditor( op['editor-box'] );
				this._handlerParaminfo('{}', '' );
			},
			_setDefaultCss : function( event ){
				var self = $( event.currentTarget ),
					item = self.closest('.mode-css'),
					css_id = item.find('textarea').attr('name'),
					hidden = this.element.find('input[name="default_css"]');
				item.siblings().find('.set-default-btn>input').attr('checked',false);
				if( self.prop('checked') ){
					hidden.val( css_id );
				}else{
					hidden.val( '' );
				}
			},
			_uploadCssPic : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					input_file = self.closest( op['mode-css-item'] ).find( op['css-preview-file'] );
				input_file.trigger('click');
			},
			_changeCssPic : function(event){
				var self = event.currentTarget,
					op = this.options,
					box = $(self).closest( op['mode-css-item'] ).find( op['css-preview'] );
				var file = self.files;
				this._handlerFile( file ,box );
			},
			_handlerFile : function( files , box ){
				var op = this.options;
				for(var i=0;i<files.length;i++){
					var file=files[i];
					var imageType=/image.*/;
					if(!file.type.match(imageType)){
						alert("请上传图片文件");
						continue;
					}
					var reader=new FileReader();
					reader.onload=function(e){
						imgData=e.target.result;
						var img = box.find('img');
						img.length || ( img = $('<img />').appendTo( box ) );
			            img.attr('src', imgData);
					}
					reader.readAsDataURL(file);
				}
			},
			_toggleParamBox : function( event ){
				var self = $( event.currentTarget ),
					op = this.options,
					box = self.closest( op['param-item'] ).find( op['para-list'] );
				box.toggleClass( op['drop-select-slide'] );
			},
			_setParam : function( event ){
				var self = $(event.currentTarget),
					op = this.options,
					box = self.closest( op['param-item'] ),
					input = box.find('input'),
					arrow = box.find( op['arrow'] );
				input.val( self.text() );
				arrow.trigger( 'click' );
				this._changeParam( self );
			},
			_highlight : function(event){
				var op = this.options,
					self = $(event.currentTarget),
					editor_box = self.closest( op['editor-module'] ).find( op['editor-box'] ),
					key = editor_box.data('type');
				$.info[key].findAll('#' + self.val());
			},
			_initEditor : function( selector ){
				var op = this.options,
					_this = this;
				$( selector ).each(function(){
					var key = $(this).data('type');
					var mode = 'html';
					if( key.indexOf('css') != -1 ){
						mode = 'css';
					}
					if( key.indexOf('js') != -1 ){
						mode = 'javascript';
					}
					$.info[key] = ace.edit(this);
					$.info[key].setTheme('ace/theme/github');
					$.info[key].getSession().setMode('ace/mode/' + mode);
				});
				$.each($.info,function(key,value){
					value.on('blur',function(event,edit){
						var input_area = $('.editor-box').filter(function(){
							return $(this).data('type') == key;
						}).closest( op['style-item'] ).find( op['parse-textarea'] );
						var reg = /#([a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|\)|}])/gi;
						var outReg = /\$([a-zA-Z_]+[0-9_]*)([\s|,|\+|'|\=|\)|}])/gi;
						var str = edit.getValue();
						var	matchs_param = str.match(reg) || [];
						var	matchs_outparam = str.match(outReg) || [];
						if( matchs_param.length ){
							matchs_param = _this._clearRepeat(matchs_param,'#');
							input_area.data('param',matchs_param);
						}else{
							input_area.data('param','');
						}
						if( matchs_outparam.length ){
							matchs_outparam = _this._clearRepeat(matchs_outparam,'$');
							input_area.data('outparam',matchs_outparam);
						}else{
							input_area.data('outparam','');
						}
						input_area.val( str );
						input_area.trigger('blur');
					});
				});
			},
			_clearRepeat : function( arr , mark ){
				var newArr = $.map( arr, function( value ){
					var reg = /[\s|,|\+|'|\=|\)|}]/g
					var para = value.replace( mark,'' );
					return para.replace( reg , '' );
				} );
				newArr = this._clearMethod( newArr );
				return newArr.join(',');
			},
			_clearMethod : function( arr ){
				var temp=arr.slice(0);//数组复制一份到temp
				for(var i=0;i<temp.length;i++){
					for(j=i+1;j<temp.length;j++){
						if(temp[j]==temp[i]){
							temp.splice(j,1);//删除该元素
							j--;
						}
					}
				}
				return temp;
			},
			_parse : function(event){
				var self = $(event.currentTarget);
				this._parse_ajax( self );
			},
			_parse_ajax : function( self ){
				var op = this.options,
					_this = this,
					url = './run.php?mid=' + gMid + '&a=get_code_para_name',
					mode_area = self.closest( op['mode-area'] ),
					param_box = mode_area.find( op['param-box'] );
				var flag = self.data('flag');
				var _params = self.data('param'),
					_outparams = self.data('outparam');
				if( flag == 'html' ){
					this._instanllOutParam(_outparams)
				}
				$.post( url,{ type: flag , params : _params } ,function( data ){
					var data = data[0];
					_this._instanllParam( self, data , _params );
				},'json');
			},
			_instanllParam : function( self, data , _params ){
				var op = this.options,
					type = self.data('type');
				var marks = true;
				var mode_area = self.closest( op['mode-area'] ),
					param_box = mode_area.find( op['param-box'] ),
					param_mark = op['param-mark'],
					param_each = op['param-each'],
					param_tpl = op['param-tpl'];
				this._initParamData( data, _params, param_box ,param_mark , param_each, param_tpl ,type );
			},
			_instanllOutParam : function( data ){
				var op = this.options,
					widget = this.element,
					param_box = widget.find( op['out_arment'] ),
					param_mark = op['out_arment-title'],
					param_each = op['param-each'],
					param_tpl = op['para-html-tpl'];
				this._initOutParamData( data, param_box ,param_mark , param_each, param_tpl );
			},
			_initParamData : function( data, _params, param_box ,param_mark , param_each, param_tpl ,type ){
				var _this = this,
					info = [];
				var param_arr = _params.split(','),
					real_arr = [];
				if( !param_arr[0]  ){
					param_box.html( '' );
					return;
				}
				param_box.find( param_each ).removeClass('on');
				var marks = param_box.find( param_mark );
				if( marks.length ){
					$.each( param_arr, function( key,value ){
						var isvalue = true;
						marks.each(function(){
							if( value == $(this).val() ){
								$(this).closest( param_each ).addClass('on');
								isvalue = false;
								return;
							}
						});
						if( isvalue ){
							real_arr.push( value );
						}
					});
				}else{
					real_arr = param_arr;
				}
				if( real_arr.length && real_arr[0] ){
					this._handlerParaminfo( data , type );
					$.each( real_arr , function( key , value ){
						var params = {};
						var params_data = data[value],
							drop_list = [];
						params.biaoshi = value;
						params.marks_info = [];
						params.configType = $.configsType;
						params.myconfigType = [];
						params.type = type;
						params.select = true;
						$.each( params_data, function( key , value ){
							var markinfo = {},
								dropinfo = [];
							markinfo.biaoshi = params.biaoshi;
							markinfo.type = params.type;
							markinfo.mark_name = value['name'];
							markinfo.id = key;
							params.marks_info.push( markinfo );
							drop_list.push( value['other_value'] );
							params.myconfigType.push( value['para_type'] );
						} )
						if( params.marks_info[0] ){
							params.hasparam = true;
							params.default_mark = params.marks_info[0]['mark_name'];
							params.defaultconfigType = params.myconfigType[0];
							params.default_drop = drop_list[0];
						}else{
							params.hasparam = false;
							params.defaultconfigType = 'text';
						}
						info.push( params );
						
					});
				}
				param_box.find( param_each ).each(function(){
					$(this).hasClass('on') || $(this).remove();
				});
				$( param_tpl ).tmpl( info ).appendTo( param_box[0] );
				var each = param_box.find( '.add-form-each' ),
					param_input = each.find( '.param-input' );
				this._initDropType( param_input );
				
			},
			
			_handlerParaminfo : function( data , type ){
				this.globalParaminfo = this.globalParaminfo || {};
				this.globalParaminfo['html'] = $.extend( this.globalParaminfo['html'],$.htmlParaminfo) || {};
				this.globalParaminfo['css'] = $.extend(this.globalParaminfo['css'], $.cssParaminfo ) || {};
				this.globalParaminfo['js'] = $.extend(this.globalParaminfo['js'] ,$.jsParaminfo  ) || {};
				if( !type ){
					this.globalParaminfo['html'] = $.extend( this.globalParaminfo['html'], data );
				}else{
					if( type.indexOf('css') != -1 ){
						this.globalParaminfo['css'] = $.extend( this.globalParaminfo['css'], data );
					}
					if( type.indexOf('js') != -1 ){
						this.globalParaminfo['js'] = $.extend( this.globalParaminfo['js'], data );
					}
				}
			},
			
			_getParaminfo : function( type ){
				if( !type ){
					return this.globalParaminfo['html'];
				}else{
					if( type.indexOf('css') != -1 ){
						return this.globalParaminfo['css'];
					}
					if( type.indexOf('js') != -1 ){
						return this.globalParaminfo['js'];
					}
				}
			},
			
			_initDropType : function( param_input ){
				var op = this.options;
				param_input.each( function(){
					var type = $(this).attr( '_marktype' ),
						param_select = $(this).closest( op['param-each'] ).find( 'select' );
					if( type == 'select' ){
						var option =  param_select.find( 'option' );
						$.each( option, function(){
							if( $(this).val() == 'select' ){
								$(this).attr( 'selected', 'selected' );
								return;
							}
						} );
					}
					param_select.trigger( 'change' );
					
				} );
					
			},
			
			_changeParam : function( self ){
				var op = this.options;
				var biaoshi = self.attr( '_biaoshi' ),
					_id = self.attr( '_id' ),
					_type = self.attr('_type');
				var globalData = this._getParaminfo( _type );
				var data = globalData[biaoshi];
					mydata = data[_id];
				var info = {};
				var param_each = self.closest( op['param-each'] ),
					param_box = self.closest( op['param-box'] );
				info.marks_info = [];
				info.biaoshi = biaoshi;
				info.type = _type;
				info.default_mark = mydata['name']
				info.default_drop = mydata['other_value'];
				info.hasparam = true;
				info.configType = $.configsType;
				info.defaultconfigType = mydata['para_type'];
				$.each( data , function( key , value ){
					var markinfo = {};
					markinfo.biaoshi = biaoshi;
					markinfo.type = _type;
					markinfo.mark_name = value['name'];
					markinfo.id = key; 
					info.marks_info.push( markinfo );
				} );
				var param_input = $( op['param-tpl'] ).tmpl( info ).insertAfter( param_each[0] ).find( '.param-input' );
				param_each.remove();
				this._initDropType(param_input);
				
			},
			
			_initOutParamData : function( data, param_box ,param_mark , param_each, param_tpl ){
				var data = data.split(',');
				var info = {},
					params = [];
				if( !data[0] ){
					param_box.html('');
				}else{
					param_box.find( param_each ).removeClass('on');
						$.each( data, function( key,value ){
							var is = false;
							var marks = param_box.find( param_mark );
							if( marks.length ){
								marks.each(function(){
									if( value == $(this).val() ){
										$(this).closest( param_each ).addClass('on');
										is = true;
										return;
									}
								});
								if( !is ){
									params.push(value);
								}
							}else{
								
								params.push( value );
							}
						});
						info.list = params;
					param_box.find( param_each ).each(function(){
						$(this).hasClass('on') || $(this).remove();
					});
					$( param_tpl ).tmpl(info).appendTo( param_box[0] );
				}
			},
			
			_addCss : function(){
				var op = this.options,
					widget = this.element,
					box = widget.find('.mode-all');
				var first_item = box.find( op['mode-css-item'] ).first(),
					first_item_code = $.trim(first_item.find( op['parse-textarea'] ).val()),
					info = {};
				if( !(first_item_code) ){
					alert('请先填写当前css代码');
				}else{
					var length = this._setIndex();
					info.index = ++length;
					$( op['add-css-tpl'] ).tmpl( info ).prependTo( op['mode-css-list'] );
					box.find( op['mode-css-item'] ).removeClass('del-hide');
				}
				var selector = box.find( op['mode-css-item'] ).first().find( op['editor-box'] );
				this._initEditor(selector[0])
			},
			_setIndex : function(){
				var op = this.options,
					widget = this.element;
				var item = widget.find( op['add-mode-css'] ).first();
				if( item.length ){
					var length = item.data('id');
				}else{
					var length = widget.find( op['new-mode-css'] ).last().data('id');
				}
				return length;
			},
			_copyCss : function( event ){
				var op = this.options,
					widget = this.element,
					box = widget.find('.mode-all');
				var self = $(event.currentTarget),
					currentBox = self.closest( op['mode-css-item'] ),
					code = currentBox.find( op['parse-textarea'] ).val(),
					copyBox = currentBox.clone(true);
				copyBox.find( op['parse-textarea'] ).addClass( 'add-parse-textarea' ).val( code );
				copyBox.find( op['editor-box'] ).html('');
				copyBox.find( op['css-preview'] ).find( 'img' ).attr('src','');
				var	copyDom = JSON.stringify( copyBox.html() );
				var length = this._setIndex(),
					index = ++length,
					reg = /css([0-9]+[_]+)/g;
				var matchBox = copyDom.replace( reg ,'css'+ index + '_' ),
					parseBox = JSON.parse(matchBox);
				var box_area = $('<div class="m2o-o m2o-flex mode-area mode-css add-mode-css clear"></div>');
				box_area.data('id' ,index ).html( parseBox ).prependTo( op['mode-css-list'] );
				box.find( op['mode-css-item'] ).removeClass('del-hide');
				var selector = box.find( op['mode-css-item'] ).first().find( op['editor-box'] ).text(code);
				this._initEditor(selector[0]);
			},
			_delCss : function(event){
				var op = this.options,
					widget = this.element,
					box = widget.find('.mode-all'),
					self = $(event.currentTarget),
					css_item = self.closest( op['mode-css-item'] );
				css_item.remove();
				var css_items= box.find( op['mode-css-item'] );
				if( css_items.length > 1 ){
					css_items.removeClass( 'del-hide' );
				}else{
					css_items.addClass('del-hide' );
				}
				this._recordCss();
			},
			_showCssDel : function(){
				var op = this.options,
					widget = this.element,
					box = widget.find('.mode-all');
					css_item = box.find( op['mode-css-item'] );
				if( css_item.length > 1 ){
					css_item.removeClass('del-hide');
				}
			},
			_recordCss : function(){
				var widget = this.element,
					op = this.options;
				if( this.saveAs ){
					var class_css = op['css-parse-textarea'];
				}else{
					var class_css = op['new-parse-textarea'];
				}
				var css_items = widget.find( class_css ).filter(function(){
					return $.trim( $(this).val() ) ? true : false;
				});
				var css_Arrs = css_items.map(function(){
					return $(this).data('type');
				}).get().join(',');
				$( op['css_arrs'] ).val( css_Arrs );
			},
			_recordIds : function(){
				var widget = this.element,
					op = this.options;
				var new_css_item = widget.find( op['new-mode-css'] );
				var css_ids = new_css_item.map( function(){
					return $(this).data('id');
				}).get().join(',');
				$( op['css_ids'] ).val( css_ids );
			},
			_modeForm : function( event ){
				var op = this.options,
				 	widget = this.element,
				 	items = widget.find( op['mode-css-list'] ),
				 	isOk = true;
				this._recordCss();
				this._recordIds();
				if ( $( 'input[name="sort_id"]' ).val() == -1 ){
					alert( '请选择分类!');
					return false;
				}
				//$(event.currentTarget).ajaxSubmit();
				//return false;
				/*items.find( op['parse-textarea'] ).each(function(){
					if( !$(this).val() ){
						isOk = false;
						return;
					}
				});*/
				items.each( function(){
					var css_name = $.trim( $(this).find( '.css-name' ).val() ),
						css_con = $.trim( $(this).find('.css-parse-textarea').val() );
					if( css_con && !css_name ){
						isOk = false;
						return;
					}
				} );
				if( !isOk ){
					alert('请填写css名称!');
					return false;
				}
			},
			_setSelect : function( event ){
				var self = $(event.currentTarget),
					op = this.options,
					drop_select = self.closest( op['param-each'] ).find( op['drop-list'] );
				if( self.val() == 'select' ){
					drop_select.removeClass( 'drop-hide' );
				}else{
					drop_select.addClass( 'drop-hide' );
				}
			},
			_removeItem : function( event ){
				var self = $(event.currentTarget),
					item = self.closest( 'li' );
				if( item.siblings().length ){
					self.val() || item.remove();
				}
			},
			_addItem : function( event ){
				var op = this.options,
					box = $(event.currentTarget).closest( op['drop-list'] ).find('ul');
				box.find( op['drop-list-item'] ).last().clone().find( 'input' ).val('').end().appendTo( box[0] );
				
			},
			_saveAs : function(){
				var op = this.options,
			 		widget = this.element;
				this.saveAs = true;
				$('#aid').val('create');
				widget.find( op['mode-form'] ).submit();
			},
			_slideList : function(event){
				var op = this.options,
					self = $(event.currentTarget),
					list_box = self.closest( op['drop-list'] );
				list_box.toggleClass( op['drop-select-slide'] );
			},
		_delParam : function( event ){
			var op =this.options,
				box  = $(event.currentTarget).closest( op['form-each'] ),
				flag_item = box.find( op['out_arment_flag'] );
			if( box.hasClass( op['disable-flag'] ) ){
				box.removeClass( op['disable-flag'] );
				flag_item.val('0');
			}else{
				box.addClass( op['disable-flag'] );
				flag_item.val('1');
			}
		}
	});
	})($);
	
	$('#mode-main').parseParam();
})