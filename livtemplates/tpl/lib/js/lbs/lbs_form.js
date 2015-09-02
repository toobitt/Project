$(function(){
	(function($){
		$.widget('lbs.lbs_form', {
			options : {
				getFieldUrl : '',
				getImageUrl : '',
				delImageUrl : '',
				ohms : '',
				telItemTpl : '',
				telItemTname : 'add-tel-tpl',
			},
			
			_create : function(){
				$.template(this.options.telItemTname, this.options.telItemTpl);
				this.uploadLogo = this.element.find('.photo-file');
				this.isTrue = true;
				this.chechbox = $('<input type="hidden" name="catalog[like][]" value=""/>')
			},
			
			_init : function(){
				var _this = this;
				this._on({
					'click .lbs-title' : '_switchInfo',
					'click .explain-button' : '_triggerUpload',
					'click .indexpic' : '_clickUpload',
					'click .tel-item em' : '_setTel',
					'click .pic-del' : '_delPic',
					'click .del-extend' : '_delInfo',
					'click .upload-pic' : '_uploadavatar',
					'click .set-index' : '_setIndex',
					'change .catalog_avatar' : '_filePic',
					'click .sort-name-inner' : '_sortInfo',
					'click .del' : '_del'
				});
				this._initUpload();
				this._initData();
				this._initForm();
				this.element.on({
			        mousedown : function(){
			            var disOffset = {left : 0, top : 0};
			             _this.options.ohms.ohms('option', {
			                time : $(this).val(),
			                target : $(this)
			            }).ohms('show', disOffset);
			            return false;
			        },
			         set : function(event, hms){
			         	var $this = $(this);
			         	var time = [hms.h, hms.m].join(':');
			         	if($this.hasClass('etime')){
			         		var stime = $('.stime').val();
			         		if(stime && time < stime){
								_this._myTip( {obj : $this, left: 120, tip : '结束时间不能小于开始时间'} );
			         			return false;
			         		}
			         	}
			         	if($this.hasClass('stime')){
			         		var etime = $('.etime').val();
			         		if(etime && time > etime){
								_this._myTip( {obj : $this, left: 120, tip : '开始时间不能大于结束时间'} );
			         			return false;
			         		}
			         	}
			         	$this.val(time);
			        }
			    }, '.btime');
			},
			
			_initForm : function(){
				var _this = this;
				$('.m2o-form').submit(function(){
					if($('.addition-checkbox').length){
						var input = $('.addition-checkbox').find('input[type="checkbox"]');
						var i = 0;
						input.each(function(key, value){
							if($(this).prop('checked')){
								i += 1;
							}
						});
						if(i == 0){
							_this.chechbox.appendTo('.addition-checkbox');
						}
					}
					var sort_id = $(this).find('#sort_id').val();
					var mtitle = $(this).find('.m2o-m-title').val();
					var obj = $(this).find('.m2o-save');
					if( !mtitle ){
						_this._myTip( {obj : obj, left: -160, tip : '请先填写信息点名称'} );
						return false;
					}
					if( !sort_id || sort_id == '0' ){
						_this._myTip( {obj : obj, left: -160, tip : '请先选择左侧分类数据'} );
						return false;
					}
				});
			},
			
			_myTip : function( info ){
				info.obj.myTip({
					string : info.tip,
					delay: 2000,
					dtop : 5,
					dleft : info.left,
					width : 'auto',
					padding: 10
				});
			},
			
			_initData : function(){
				var imgid = this.element.find('.indexid').val();
				var box = this.element.find('.pic-info');
				var obj = box.find('.pic-item').filter(function(){
					return ($(this).attr('_id') == imgid);
				});
				obj.find('.set-index').addClass('current').html('索引图');
				var all_picid = box.find('.pic-item').map(function(){
						return $(this).attr('_id');
					}).get().join(',');
				box.find('.pic_allid').val(all_picid);
			},
			
			_sortInfo : function( event ){
				var self = $(event.currentTarget);
				this._JudgetField(self);
			},
			
			_switchInfo : function( event ){
				var self = $(event.currentTarget),
					type = self.data('type');
				if( type == 'extend' ){
					this._JudgetField( self );
					self.closest('.lbs-info').find('input[name="is_expand"]').val(1);
				}
				if(this.isTrue || type=='basic'){
					if(self.hasClass('active')){
						return;
					}else{
						 $('.lbs-title').toggleClass('active');
					   	 $('.basic-info').add('.extend-info').toggle();
					}
				}
			},
			
			_JudgetField : function( self ){
				this.oldid = sortid = this.element.find('#sort_id').val();
				if(!sortid || sortid == 0){
					this.isTrue = false;
					self.myTip({
						string : '请先选择分类信息',
						delay: 2000,
						dtop : -10,
						dleft : 120,
					});
					return false;
				}else{
					this.isTrue = true;
					if(this.oldid != this.id){
						this.id = this.element.find('#sort_id').val();
						this._getField( self, sortid );
					}
				}
			},
			
			_getField : function( self, sortid ){
				var url = this.options.getFieldUrl,
					_this = this,
					unit = this.element.find('.operating-unit'),
					id = this.element.data('id');
				$.globalAjax( self, function(){
					return $.getJSON(url, {sortid : sortid, id : id}, function( data ){
						var data = data[0];
						data ? _this.handleData( data ) : _this.noData();
						if( sortid == bicycle_sort_id ){
							unit.show();
						}else{
							unit.hide();
						}
 					});
				});
				
			},
			
			handleData : function( data ){
				var _this = this;
				var arrInfo = [];
				this._handleCallback( data, arrInfo);
				this._clearField();
				$('#additionInfo-tpl').tmpl(arrInfo).appendTo('.extend-info');
				$('<input type="hidden" name="catalog[materialdel]" value="" />').appendTo('.extend-info');
				this._handleCallback( data );
			},
			
			noData : function(){
				this._clearField();
				$('#noInfo-tpl').tmpl().appendTo('.extend-info');
			},
			
			_clearField : function(){
				$('.extend-info').empty();
			},
			
			_handleCallback : function( data, arrInfo){
				var _this = this;
				$.each(data, function( key, value ){
					value.title = "删除" + value.zh_name;
					if(!arrInfo && value.type == 'radio'){
						_this._getInput(value, 'radio');
					}
					if(!arrInfo && value.type == 'checkbox'){
						_this._getInput(value, 'checkbox');
					}
					if(!arrInfo && value.type == 'option'){
						_this._getInput(value, 'option');
					}
					if(!arrInfo && value.type == 'img'){
						_this._getImg(value);
					}
					arrInfo && arrInfo.push(value);
				});
			},
			
			_getImg : function( data ){
				if(data.selected){
					$.each(data.selected , function(key , value){
						var src = value.host + value.dir + value.filepath + value.filename;
							id = value.id;
						var obj = $('.extend-info').find('.m2o-item').filter(function(){
							return $(this).find('li').data('id') == id;
						});
						obj.find('img').attr('src', src);
					})
				}
			},
			
			_getInput : function( data, type ){
				var data_default = data.field_default,
					id = data.id;
				var nhtml = '';
				var thtml = '<select name="' + data.field + '">';
				var selected = (type == 'checkbox') ? data.selected.split(',') : data.selected;
				$.each(data_default, function(key, value){
					(value == data.selected) ? checked = 'checked' : checked = '';
					var selected = checked ? 'selected' : '';
					if(type == 'option'){
						thtml += '<option value="' + value + '" ' + selected + '>' + value + '</option>';
					}else{
						 nhtml += '<input type=' + type + ' name="' + data.field + '" value="' + value + '" ' + checked + '/>' + value;
					}
				});
				thtml += '</select>';
				var obj = $('.extend-info').find('.m2o-item').filter(function(){
					return $(this).data('id') == id;
				});
				var html = (type=='option' ? thtml : nhtml);
				obj.find('.addition-content').html( html );
				(type == 'checkbox') && this._getCheckbox(obj, selected);
			},
			
			_getCheckbox : function(obj, selected){
				for(var i=0; i < selected.length; i++){
					obj.find('input').each(function(){
						if($(this).val() == selected[i]){
							$(this).attr('checked', true);
						}
					});
				}
			},
			
			_setTel : function( event ){
				var self = $(event.currentTarget),
					type = self.data('type'),
					obj = self.closest('li'),
					parent = self.closest('ul');
				if(type == 'add'){
					$.tmpl(this.options.telItemTname).appendTo( parent );
					self.data('type', 'del').addClass('del').removeClass('add')
						.attr('title', '删除电话');
				}else{
					var val = obj.find('input').val();
					if(val){
						jConfirm('你确定要删除该条记录', '删除提醒', function( result ){
							if(result){
								obj.remove();
							}
						}).position(self);
					}else{
						obj.remove();
					}
				}
			},

			_setIndex : function( event ){
				var self = $(event.currentTarget),
					parent = self.closest('.pic-item'),
					src = parent.find('img').attr('src'),
					id = parent.attr('_id');
				parent.find('.set-index').addClass('current').html('索引图')
				parent.siblings().find('.set-index').removeClass('current').html('设为索引');
				var box = this.element.find('.indexpic'),
					img = box.find('img');
				!img[0] && (img = $('<img />').appendTo( box ));
				img.attr('src', src);
				this.element.find('.indexid').val(id);
			},

			_uploadavatar : function( event ){
				var self = $(event.currentTarget),
					box = self.closest('.content');
				if(box.find(' .upload-pic ').length > 1){
					box.find('.catalog_avatar').last().click();
				}else{
					self.next().click();
				}
			},
			
			_filePic : function( event ){
				var op = this.options,
					self = event.currentTarget;
					clone = $(self).clone();
					obj = $(self).prev();
				    info = {};
			   var  file=self.files[0];
			        reader=new FileReader();
			   reader.onload=function(e){
					imgData=e.target.result;
					var img = obj.find('img');
		            var box = $(self).closest('.content');
		            if(box.data('batch') == 1 || box.find('.img-batch').length == 1){    /*当batch==1时或img-batch的长度为1的时候*/
		            	!img[0] && (img = $('<img />').appendTo( obj ));				 /*插入图片并在此img-batch后再增加一条img-batch*/
			            img.attr('src', imgData);
		            	$('<div class="img-batch"><p class="upload-pic"></p><p class="del">-</p> </div>').appendTo( box );
		            	$(clone[0]).insertAfter( box.find('.img-batch:last-child .upload-pic') );
					}else{
						$(self).closest('.img-batch').prev().find('img').attr('src' , imgData);  /*当batch!=1时，覆盖前面一条img-batch的src*/
					}
				}  
				reader.readAsDataURL(file); 
				event.stopPropagation();
			},
			
			_del : function(event){
				var self = $(event.currentTarget),
					obj = self.closest('.img-batch'),
					item = obj.closest('.addition-content'),
					val = this.element.find('input[name="catalog[materialdel]"]').val(),
					ids = val + obj.data('id') + ',';
				obj.data('id') && this.element.find('input[name="catalog[materialdel]"]').val(ids); /*已删除id*/
				obj.remove();
			},
			
			_triggerUpload : function( event ){
				var self = $(event.currentTarget);
				this.box = self.next();
				this.type = true;
				this.uploadLogo.click();
			},
			
			_clickUpload : function( event ){
				var self = $(event.currentTarget);
				this.box = self;
				this.type = false;
				this.uploadLogo.click();
			},
			
			_initUpload : function(){
				var op = this.options,
					_this = this;
				var url = this.options.getImageUrl + '&id=' + this.element.data('id');
				this.uploadLogo.ajaxUpload({
					url : url,
					phpkey : 'Filedata',
					after : function( json ){
						var data = json['data'];
						data && _this._UploadAfterData( data );
					}
				}); 
			},
			
			_UploadAfterData : function( data ){
				var type = this.type;
				if(type){
					var src = $.globalImgUrl(data, '115x115'),
						id = data.id;
					$('#add-pic-tpl').tmpl({pic : src, id : id}).appendTo( this.box );
				}else{
					var img = this.box.find('img'),
						src =  $.globalImgUrl(data, '176x176'),
						id = data.id;
					!img[0] && (img = $('<img />').appendTo( this.box ));
					img.attr('src', src);
					this.element.find('.indexid').val(id);
				}
				this.element.find('.indexpic-suoyin').addClass('indexpic-suoyin-current');
			},
			
			_delPic : function( event ){
				var self = $(event.currentTarget),
					_this = this,
					box = self.closest('.pic-item'),
					id = box.attr('_id');
				var lbs_id = this.element.data('id');
				if(lbs_id){
					box.remove();
				}else{
					$.globalAjax( self, function(){
						return $.getJSON(_this.options.delImageUrl, {id : id}, function( data ){
								if(data){
									box.remove();
								}
							});
					});
				}
			},
			
			_delInfo : function( event ){
				var self = $(event.currentTarget),
					box = self.closest('.m2o-item');
				jConfirm('你确定要删除该条记录', '删除提醒', function( result ){
					if(result){
						box.remove();
					}
				}).position(self);
			}
			
		});
	})($);
	$('#seek_form').lbs_form({
		 telItemTpl : $('#add-tel-tpl').html(),
		 ohms : $('#ohms-instance').ohms(),
		 getFieldUrl : "./run.php?mid=" + gMid + "&a=get_field",
		 getImageUrl : "./run.php?mid=" + gMid + "&a=upload_img",
		 delImageUrl : "./run.php?mid=" + gMid + "&a=delete_img"
	});
});
