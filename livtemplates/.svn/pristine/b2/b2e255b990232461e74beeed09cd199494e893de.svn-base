$(function(){
	(function($){
		$.widget('program.program_library', {
			options : {
				each : '.library-each:not(.library-add)',
				img : '.library-img',
				set : '.set em',
				checkAll : '.checkAll',
				batdelete : '.batch-delete',
				input : '.library-each:not(.library-add) input',
				Saveurl : '',
				UploadUrl : '',
				Delurl : '',
				ohms : null,
				itemTname : 'list-each-tpl'
			},
			
			_create : function(){
				$.template(this.options.itemTname, this.options.itemTpl);
				this.loading = $('<img src="' + RESOURCE_URL + 'loading2.gif" class="load-img" style="width:30px; position:absolute; top:50px; left:75px;"/>');
			},
			
			_init : function(){
				var op = this.options,
					handlers = {};
				handlers['click ' + op['each'] ] = '_check';
				handlers['click ' + op['img'] ] = '_imgIndex';
				handlers['click ' + op['set'] ] = '_setItem';
				handlers['click ' + op['input'] ] ='_focus';
				handlers['click ' + op['batdelete'] ] = '_delAll';
				handlers['click ' + op['checkAll'] ] = '_toggleSelectAll';
				this._on(handlers);
				this._on({
					'click .library-add .start_time' : '_defaultOhms',
					'click .event_day' : '_setEveryDay',
					'click .period label' : '_cancelFocus',
				})
				this.initUpload();
			},
			
			_defaultOhms : function(){
				this.setOhms();
			},
			
			setOhms : function(){
				var _this = this;
				this.element.on({
	                mousedown : function(){
	                    var disOffset = {left : 0, top : 0};
	                    _this.options.ohms.ohms('option', {
	                        time : $(this).html(),
	                        target : $(this)
	                    }).ohms('show', disOffset);
	                    return false;
	                },
	                 set : function(event, hms){
	                 	var time = [hms.h, hms.m].join(':');
	                 	$(this).val(time);
	                }
	            }, '.ohms');
			},
			
			initUpload : function(){
				var _this = this;
				this.element.find('.image-file').ajaxUpload({
					url : _this.options['UploadUrl'],
					phpkey : 'img',
					before : function(){
						var item = _this.box.closest('li');
						_this.loading.appendTo(item).show();
					},
					after : function( json ){
						var item = _this.box.closest('li');
						_this._uploadIndexAfter( json );
						item.find('.load-img').detach();
					}
				});
			},
			
			_cancelFocus : function( event ){
				this._focus( event );
			},
			
			_focus : function( event ){
				event.stopPropagation();
			},
			
			_uploadIndexAfter : function( json ){
				var data = json['data'];
				var img = this.box.find('img'),
					url = $.globalImgUrl(data, '160x145');
				!img[0] && (img = $('<img />').appendTo( this.box ));
				img.attr('src', url);
			},
			
			_check : function( event ){
				$(event.currentTarget).toggleClass('selected');
			},
			
			_toggleSelectAll : function( event ){
	        	var op = this.options,
	        		self = $(event.currentTarget),
	        		isSelected = self.prop('checked');
				this.element.find( op['each'] )[(isSelected ? 'add' : 'remove') + 'Class']('selected');
			},
			
			_imgIndex : function( event ){
				this.box = $(event.currentTarget);
				var item = this.box.closest('li');
				if(!item.find('.li_edit').length){
					$('.image-file').click();
				}
			},
			
			_setEveryDay : function( event ){
				var self = $(event.currentTarget),
					box = self.closest('.period'),
					isSelected = self.prop('checked');
				box.find('input').prop('checked', isSelected);
				event.stopPropagation();
			},
			
			_setItem : function( event ){
				var my = event.currentTarget,
					self = $(my),
					type = self.data('type'),
					item = self.closest('li');
				switch(type){
					case 'edit' : {
						item.find('input').attr('disabled', false);
						item.find('.library-name input').focus();
						item.find('.start_time').addClass('ohms');
						this.setOhms();
						self.addClass('li_save').removeClass('li_edit').data('type', 'save').attr('title', "保存节目");
						event.stopPropagation();
						break;
					}
					case 'add' : {
						this.saveItem( item, 1 );
						break;
					}
					case 'save' : {
						this.saveItem( item, 0 );
						event.stopPropagation();
						break;
					}
					case 'del' : {
						var id = item.data('id');
						this.delItem( my, item, id );
						event.stopPropagation();
						break;
					}
				}
			},
			
			_delAll : function( event ){
				var op = this.options,
					self = event.currentTarget;
				var item = this.element.find( op['each'] + '.selected'),
					ids = item.map(function(){
						return $(this).data('id');
					}).get().join(',');
				if(!item.length){
					jAlert('请选择要删除的数据', '删除提醒').position(self);
					return false;
				}
				this.delItem( self, item, ids );
			},
			
			delItem : function( my, item, id ){
				var _this = this, tip;
				if(item.length > 1){
					tip = '您确定批量删除选中数据吗？';
				}else{
					tip = '您确定删除该条内容吗？';
				}
				jConfirm(tip, '删除提醒', function( result ){
					if(result){
						$.globalAjax( item, function(){
							return $.get(_this.options.Delurl, {id: id}, function(){
								item.remove();
							});
						});
					}
				}).position(my);
			},
			
			saveItem : function( item, isAdd ){
				var _this = this;
				var id = item.data('id'),
					start_time = item.find('.start_time').val(),
					img = item.find('.library-img img').attr('src'),
					brief = item.find('.brief').val(),
					title = item.find('.library-name input').val(),
					week_day = item.find('input[id^="week_day"]:checked').map(function(){
						return $(this).val();
					}).get();
				if(!start_time || !title || !img){
					item.find('.li_save').myTip({
						string : '请填写完整信息',
						delay: 500,
						dtop : -10,
						dleft : 80,
					});
					return false;
				}
				var info = {
					id : id,
					start_time : start_time,
					title : title,
					brief : brief,
					week_day : week_day,
					indexpic : JSON.stringify(img)
				};
				$.globalAjax( item, function(){
					return $.getJSON(_this.options.Saveurl, info, function( json ){
						var data = json[0];
						data.index_img = (data.indexpic || '').split('\\"')[1];
						var weekday = data.week_day;
						if(isAdd){
							$.tmpl(_this.options.itemTname, data).insertAfter( item );
							_this.addWeekday( item, weekday);
							_this.clearAdd( item );
						}else{
							item.find('input').attr('disabled', 'disabled');
							item.find('.start_time').removeClass('ohms');
							item.find('.li_save').addClass('li_edit').removeClass('li_save').data('type', 'edit').attr('title', "编辑节目");
						}
					});
				});
			},
			
			addWeekday : function( item , weekday){
				var area = item.next();
				for(var i=0; i < weekday.length; i++){
					var day = weekday[i];
					area.find('li:not(:first-child)').each(function(){
						var my = $(this).find('input');
						if(my.val() == day){
							my.prop('checked', true);
						}
					});
				}
				if(weekday.length == 7){
					area.find('.event_day').prop('checked', true);
				}
			},
			
			clearAdd : function( item ){
				item.find('.library-img img').attr('src','');
				item.find('.library-name input').val('');
				item.find('.start_time').val('');
				item.find('.brief').val('');
				item.find('.period input').attr('checked', false);
			},
		});
	})($);
	var ohmsInstance = $('#ohms-instance').ohms();
	$('.common-list-content').program_library({
		ohms : ohmsInstance,
		itemTpl : $('#list-each-tpl').html(),
		Saveurl :  'run.php?mid=' + gMid + '&a=update&html=1',
		Delurl :  'run.php?mid=' + gMid + '&a=delete&html=1',
		UploadUrl : 'run.php?mid=' + gMid + '&a=uploadIndexpic'
	});
});
