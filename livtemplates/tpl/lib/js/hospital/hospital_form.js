(function(){
	$.widget('hospital.form', {
		options : {
			teltpl : '',
			hasImage : 'has-images'
		},
		
		_create : function(){
			var op = this.options,
				widget = this.element;
			this.imgLoading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="loading2" style="width:50px; height:50px;"/>');
		},
		
		_init : function(){
			this._on({
				'click .img-add' : '_addImg',
				'change .images-file' : '_uploadImg',
				'click .set' : '_setLi',
				'click .save-button' : '_submit',
				'click .m2o-close' : '_close'
			});
			//this._initForm();
			this._switch();	
			// this.element.find('.btn-item').find('input[name="referto"]').val( document.referrer );
		},
		
		//滑动选择
		_switch : function(){
			var _this = this;
			$('.common-switch').each(function(){
				var $this = $(this),
					obj = $this.parent();
				var id = _this.widget().data('id'),
					val;
				$this.hasClass('common-switch-on') ? val = 100 : val = 0;
				$this.hg_switch({
					'value' : val,
					'callback' : function( event, value ){
						var is_on = 0;
						( value > 50 ) ? is_on = 1 : is_on = 0;
						_this._onOff(id, obj, is_on);
					}
				});
			});
		},
		
		_onOff : function( id, obj, is_on ){
			obj.find('input[type="hidden"]').val( is_on );
		},
		
		_initForm : function(){
			var btn = this.element.find('.save-button'),
				_this = this, tip, loadSubmit;
			this.element.submit(function(){
				$(this).ajaxSubmit({
					beforeSubmit : function(){
						tip = _this._before();
						if( tip ){
							_this._myTip(btn, tip);
							return false;
						}
						loadSubmit = $.globalLoad( btn );
					},
					dataType : 'json',
					success : function( data ){
						loadSubmit();
						if( data && data.error ){
							_this._myTip(btn, data.msg);
							return;
						}
						_this._myTip( btn, btn.val() + '成功');
						setTimeout(function(  ){
							_this._close();
						}, 2000)
					}
				});
				return false;
			});
		},
		
		_submit : function( event ){
			var _this = this,
				btn = $(event.currentTarget),
				loadSubmit = $.globalLoad( btn );
			var tip = _this._before();
			if( tip ){
				loadSubmit();
				_this._myTip(btn, tip);
				return false;
			}
			this.element.trigger('submit');
			loadSubmit();
		},
		
		_close : function(){
			var id = this.element.data('id');
			if( id ){
				location.href = document.referrer;
			}else{
				top.$.closeFormWin();
			}
		},
		
		_before : function(){
			var widget = this.element,
				param = {
					empty_name : '请填写医院名称',
					empty_indexpic : '请添加医院索引图',
					empty_logo : '请添加医院logo',
					empty_level : '请选择医院等级',
					empty_brief : '请填写医院简介',
					error_hospitalid : '医院id为必须为有效数字'
				},
				tip = '';
			var title = $.trim(widget.find('.m2o-m-title').val()),
				indexpic = widget.find('.indexpic img').attr('src'),
				logo = widget.find('.logo-item img').attr('src'),
				level = widget.find('#level').val(),
				brief = $.trim( widget.find('.brief-item textarea').val() ),
				hospitalid = widget.find('input[name="hospital_id"]').val();
			$.each({
				empty_name : title,
				empty_indexpic : indexpic,
				empty_logo : logo,
				empty_level : level,
				empty_brief : brief,
				error_hospitalid : hospitalid
			}, function(ii, nn){
				if( !nn ){
					tip = param[ ii ];
					return false;
				}
				if( ii == 'error_hospitalid' ){
					var reg_id = /^[1-9]\d*$/;
					var match = reg_id.test( hospitalid );
					if( !match ){
						tip = param[ ii ];
					}
				}
			});
			return tip;
		},
		
		//增加图片
		_addImg : function( event ){
			$(event.currentTarget).next('.images-file').click();
		},
		
		_uploadImg : function( event ){
			var _this = this,
				self = event.currentTarget,
				imgbox = $( self ).prev();
			var file=self.files[0];
		        reader = new FileReader();
		   reader.onload=function( e ){
				var imgData = e.target.result,
					img = imgbox.find('img');
				!img[0] && (img = $('<img />').appendTo( imgbox.prev() ));
	            img.attr('src', imgData);
	            imgbox.addClass( _this.options.hasImage );
			}  
			reader.readAsDataURL( file );    
		},
		
		//增加文本
		_setLi : function( event ){
			var self = $(event.currentTarget),
				parent = self.closest('li'),
				item = parent.closest('.item');
			var attr = item.attr('attr'),
				message = {
					tel : '联系方式',
					envir : '环境信息'
				};
			if( self.hasClass('add') ){
				item.append( this.options[attr + 'tpl'] );
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
				this._remind('您确定删除该' + message[attr] +  '?', '删除提醒', callback, self);
			}else{
				callback();
			}
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
	});
})();
