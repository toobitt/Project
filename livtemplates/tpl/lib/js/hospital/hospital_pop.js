(function(){
	$.widget('hospital.popbox', {
		options : {
			popdeparttpl : '',
			popdeparttname : 'popdepart-tpl',
			departenvirtpl : '',
			firstdepart : '',		//一级科室数据
			hasImage : 'has-images'
		},
		
		_create : function(){
			var op = this.options;
			$.template(op.popdeparttname, op.popdeparttpl);
			this.content = this.element.find('.pop-add-content');
			this.imgLoading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="loading2" style="width:50px; height:50px;"/>');
		},
		
		_init : function(){
			this._on({
				'click .tab' : '_tab',
				'click .img-add' : '_addImg',
				'click .save-button' : '_save',
				'click .pop-close' : '_close',
				'change .images-file' : '_uploadImg',
				'click .cancel-button' : '_del',
				'click .set' : '_setLi',
			});
			this._root();	
		},
		
		_root : function(){
			return;
		},
		
		_initUpload : function(){
			this.upload.ajaxUpload({
				url : url,
				data : {
					material_id : _this.box.closest('.pic').find('input[type="hidden"]').val()
				},
				phpkey : 'Filedata',
				before : function(){
					_this.imgLoading.appendTo( _this.box );
				},
				after : function( json ){
					if( json && json['callback'] ){
						eval( json['callback'] );
						return;
					}
					var data = json['data'];
					data && _this._UploadAfterData( data );
				}
			}); 
		},
		
		_UploadAfterData : function( data ){
			this.box.addClass( this.options.hasImage ).find('.loading2').remove();
			var src = $.globalImgUrl(data, '112x112');
			this.box.find('img').attr('src', src);
			this.box.closest('.pic').find('input[type="hidden"]').val( data.id );
		},
		
		_tmpl : function( option ){
			$.tmpl( this.options.popdeparttname, option) .appendTo( this.content.empty() );
			this.upload = this.content.find('.images-file');
			this._initForm();
			//this._initUpload();
			this._initSelect();
		},
		
		_tab : function( event ){
			var self = $(event.currentTarget);
			if( self.closest('.item-tab').hasClass('disabled') ){
				return;
			}
			if( self.hasClass('current') ){
				return;
			}
			$( '.item-tab .tab' ).toggleClass('current');
			$('.depart-first').add('.depart-secondary').toggle();
		},
		
		_save : function(){
			var form = this.element.find('.pop-form:visible');
			form.submit();
		},
		
		_del : function( event ){
			var _this = this,
				self = $(event.currentTarget),
				form = this.content.find('.pop-form:visible'),
				id = form.find('input[name="id"]').val(),
				index = form.attr('attr');
			$.globalAjax(this.content, function(){
				return $.getJSON( _this.options.del_url, {id : id}, function( json ){
					if( json && json['callback'] ){
						eval( json['callback'] );
						return;
					}
					if( json && json['error_msg'] ){
						_this._myTip(self, json['error_msg']);
					}else if( $.isArray( json ) && json[0] == 'success' ){
						var level = (index == 1) ? 'first' : 'second';
						_this._change( 'del', level, id );
						_this._myTip(self, '删除成功');
					}
				} );
			});
		},
		
		//增加图片
		_addImg : function( event ){
			this.box = $( event.currentTarget );
			this.upload.trigger('click');
		},
		
		_uploadImg : function( event ){
			var _this = this,
				formdata = new FormData();
			var self = event.currentTarget,
				file=self.files[0];
			formdata.append( 'Filedata', file );
			formdata.append( 'material_id', _this.box.closest('.pic').find('input[type="hidden"]').val() );
			$.ajax({
                url : _this.options.upload_url,
                type : 'POST',
                data : formdata,
                processData : false,
                contentType : false,
                dataType : 'json',
                
                beforeSend : function(jqXHR, settings ){
                    _this.imgLoading.appendTo( _this.box );
                },
                success: function(json){
                	if( json && json['callback'] ){
						eval( json['callback'] );
						return;
					}
					$.isArray( json ) && json[0] && _this._UploadAfterData( json[0] );
                },
                error : function( xhr, status, errorThrown ){
                	if( status != 200 ){
                		_this._myTip(_this.box, '上传失败');
                	}
                }
            });
		},
		
		//科室环境
		_setLi : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('li'),
				item = parent.closest('.item');
			if( self.hasClass('add') ){
				item.append( this.options['departenvirtpl'] );
				self.removeClass('add').addClass('del');
				return;
			}
			var callback = function(){
				var del_ids = item.find('input[name="del_img"]').val(),
					id = parent.data('id');
				if( id ){
					del_ids = del_ids ? [del_ids, id].join(',') : id;
					item.find('input[name="del_img"]').val( del_ids );
				}
				parent.remove();
			};
			if( parent.data('id') ){
				this._remind('您确定删除该科室环境?', '删除提醒', callback, parent);
			}else{
				callback();
			}
		},
		
		_change : function( method, level, param ){
			var _this = this;
			this._delay( function(){
				_this._close();
				_this.options.callback( method, level, param );
			}, 2000 );
		},
		
		_close : function(){
			this.element.addClass('pop-hide');
		},
		
		_show : function( level, method ){
			var title = (method == this.options.update_method) ? '更新科室' : '新增科室';
			this.element.find('.pop-title h3').html( title );
			this.element.removeClass('pop-hide');
			if( level == 'second' ){
				this.content.find('.tab').eq(1).trigger('click');
			}
			if( method == this.options.update_method ){
				this.content.find('.item-tab').addClass('disabled');
			}
		},
		
		_before : function( target ){
			var tip = '',
				param = {
					empty_fid : '请选择所属的一级科室',
					empty_name : '请填写科室名称',
					empty_id : '请填写科室id',
					error_departid : '科室id为必须为有效数字',
					error_departname : '科室名称不能含特殊字符'
				};
			var title = target.find('.name-item input').val(),
				fid = parseInt(target.find('select[name="fid"]').val()) || 0,
				id = target.find('input[name="department_id"]').val();
			fid += 1;
			$.each({
				empty_name : title,
				error_departname : title,
				empty_fid : fid,
				empty_id : id,
				error_departid : id
			}, function(ii, nn){
				if( !nn ){
					tip = param[ ii ];
					return false;
				}
				if( ii == 'error_departname' ){
					var reg = /((?=[\x21-\x7e]+)[^A-Za-z0-9])/;
					var match =  reg.test( nn );
					if( match ){
						tip = param[ ii ];
					}
				}
				if( ii == 'error_departid' ){
					var reg_id = /^[1-9]\d*$/;
					var match = reg_id.test( nn );
					if( !match ){
						tip = param[ ii ];
					}
				}
			});
			return tip;
		},
		
		_initForm : function(){
			var _this = this,loadSubmit,
				btn = this.content.find('.save-button');
			this.element.find('.pop-form').each(function( i ){
				var $this = $(this);
				$this.submit(function(){
					loadSubmit = $.globalLoad( btn );
					$this.ajaxSubmit({
						beforeSubmit : function(){
							var tip = _this._before( $this );
							if( tip ){
								_this._myTip(btn, tip);
								loadSubmit();
								return false;
							}
						},
						dataType : 'json',
						success : function( json ){
							if( json && json['callback'] ){
								eval( json['callback'] );
							}else if( $.isArray( json ) && json[0] == 'success' ){
								var level = i ? 'second' : 'first',
									method = $this.find('input[name="a"]').val();
								_this._change(method, level, $this.serializeArray() );
								_this._myTip(btn, '保存成功');
							}
							loadSubmit();
						},
						error : function(){
							_this._myTip(btn, '保存失败');
						}
					});
					return false;
				});
			});
		},
		
		_initSelect : function(){
			$('.select-item select').selectbox();
		},
		
		_remind : function( title, message, callback, dom ){
			jConfirm( title, message , function(result){
				result && callback();
			}).position( dom );
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
		
		refresh : function( currentdepart, level ){
			var op = this.options,
				method = currentdepart && currentdepart.id ? op.update_method : op.save_method;
			this._tmpl( {
				method : method,
				update_method : op.update_method,
				save_method : op.save_method,
				depart : op.firstdepart,
				current : currentdepart || ''
			} );
			this._show( level, method );
		},
	});
})();
