$(function(){
	(function($){
		$.widget('market.market_member',{
			options : {
				'market-edit' : '.market-edit',
				'add-member-tpl' : '#add-member-tpl',
				'm2o-each' : '.m2o-each',
				'm2o-edit' : '.m2o-edit',
				'm2o-state' : '.m2o-state',
				'm2o-unlock' : '.m2o-unlock',
				'market-member' : '.market-member',
				'm2o-check' : '.m2o-check',
				'selected' : 'selected',
				'm2o-lock' : 'm2o-lock',
				'market-birth' : '.market-birth',
				'kinput' : '.search-k'
			},
			_create : function(){
				this.fileLead = this.element.find('.file-lead');
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['m2o-edit'] ] = '_editMember';
				//handlers['click ' + op['m2o-state'] ] = '_bindMember';
				handlers['click ' + op['m2o-check'] ] = '_checkMessage';
				handlers['click ' + op['m2o-unlock'] ] = '_unlockMember';
				this._on(handlers);
				this._on({
					'click .add-member' : '_addMember',
					'click .m2o-delete' : '_delMember',
					'click .lead-member' : '_leadMember',
					'click #state_show li' : '_pasteStatus',
					'click .send-btn' : '_sendMessage',
					'click .checkAll' : '_checkAll',
					'click .batch-delete' : '_delAll',
					'click .view-member' : '_viewMember',
					'click .view-close' : '_closeMember',
					'click .member-profile .more' : '_moreMember'
				})
				this._initForm();
				this._initUpload();
				this._initList();
				this._searchKey();
			},
			_initUpload : function(){
				var op = this.options,
					_this = this,
					widget = this.element;
				var id = widget.attr('_id');
				var url = "./run.php?mid=" + gMid + "&a=importMemberData&market_id=" + id;
				this.fileLead.ajaxUpload({
					url : url,
					phpkey : 'excelfile',
					type : 'xls',
					after : function( json ){
						_this._uploadIndexAfter(json);
					}
				});   
			},
			
			_searchKey : function(){
				var key = this.element.find( this.options.kinput ).val();
			 	var reg = new RegExp(key, 'ig');
	            $('.search-item').length && key && $('.search-item').each(function(){
	            	var _title = $(this).find('span').html(); 
	                _title = _title.replace(reg, '<em style="color:red;font-style:normal;font-weight:normal;">' + key + '</em>');
	                $(this).find('span').html(_title);
	            });
			},
			
			_initList : function(){
				var op = this.options,
					widget = this.element;
				widget.find( op['m2o-state'] ).each(function(){
					var self = $(this),
						item = self.closest( op['m2o-each'] ),
						obj = item.find( op['m2o-unlock'] );
					if(self.attr('_status') == 2){
						obj.addClass( op['m2o-lock'] );
					}else{
						obj.removeClass( op['m2o-lock'] );
					}
				});
				$( op['market-birth'] ).find('input').hg_datepicker({
					changeMonth : true,
					changeYear : true,
				});
			},
			
			_uploadIndexAfter : function( json ){
				var op = this.options;
				var data = json['data'];
				if(data == 'success'){
					window.location.reload();
				}
			},
			
			_initForm : function(){
				var op = this.options,
					info = {};
				info.method = 'create';
				info.oper = '新增';
				info.value = '新增';
				info.market_id = $( op['market-edit'] ).data('id');
				$( op['add-member-tpl'] ).tmpl(info).prependTo( op['market-edit'] );
			},
			
			_editMember : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
				var id = item.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=getMemberInfo&id=' + id;
				$.globalAjax( item, function(){
					return $.getJSON(url, function(data){
							var data = data[0],
								info = {};
							info.card_number = data.card_number;
							info.nname = data.name;
							info.phone_number = data.phone_number;
							info.email = data.email;
							info.birthday = data.birthday;
							info.barcode_img_url = data.barcode_img_url;
							info.method = 'update';
							info.oper = '编辑';
							info.value = '保存';
							info.id = id;
							info.market_id = $( op['market-edit'] ).data('id');
							info.dmemberInfo = data.member_info;
							$( op['market-edit'] ).empty().addClass('market-init');
							$( op['add-member-tpl'] ).tmpl(info).appendTo( op['market-edit'] );
							$( op['market-birth'] ).find('input').hg_datepicker();
							$('.market-barcode').show();
						});
				});
			},
			
			_moreMember : function( event ){
				var box = $(event.currentTarget).closest('.member-info'),
					more = box.find('.member-more');
				if(box.hasClass('on')){
					box.removeClass('on');
					more.slideUp();
				}else{
					box.addClass('on');
					box.siblings().removeClass('on').find('.member-more').slideUp();
					more.slideDown();
				}
			},
			
			_viewMember : function( event ){
				var self = $(event.currentTarget);
				if(self.hasClass('on')){
					$('.view-box').removeClass('view-pop');
					self.removeClass('on');
				}else{
					$('.view-box').addClass('view-pop');
					self.addClass('on');
				}
				if(!self.data('init')){
					this._ajaxMember( self );
				}
			},
			
			_closeMember : function(){
				var obj = this.element.find('.view-member');
				$('.view-box').removeClass('view-pop');
				obj.removeClass('on');
			},
			
			_ajaxMember : function( obj, page ){
				var _this = this;
				var info = {};
				info.market_id = this.element.attr('_id');
				info.page = page ? page : 1;
				info.counts = 10;
				var url = "./run.php?mid=" + gMid + "&a=get_bind_log";
				$.globalAjax( self, function(){
					return $.getJSON(url, info, function(data){
						var data = data[0];
						_this._viewData( data.log );
						data.page && _this._getPage( data.page );
						!page && obj.data('init', true);
					});
				});
			},
			
			_viewData : function( data ){
				if(data.length){
					$('.view-area').find('.area-content').detach();
					$( '#view-member-tpl' ).tmpl(data).appendTo('.view-area');
				}else{
					$('.view-area').empty();
					$('#no-member-tpl').tmpl().appendTo('.view-area');
				}
			},
			
			_getPage : function( option ){
				var page_box = this.element.find('.page_size'),
					_this = this;
				option.show_all = false;
				if(page_box.data('init')){
					page_box.page('refresh',option);
				}else{
					option['page'] = function( event, page, page_num){
						_this._refresh( page );
					}
					page_box.page( option );
					page_box.data('init', true);
				}
			},
			
			_refresh : function( page ){
				var obj = this.element.find('.view-member');
				this._ajaxMember( obj, page );
			},
			
			_addMember : function(){
				var op = this.options,
					info = {};
				info.method = 'create';
				info.oper = '新增';
				info.value = '新增';
				info.market_id = $( op['market-edit'] ).data('id');
				$( op['market-edit'] ).empty().removeClass('market-init');
				$( op['add-member-tpl'] ).tmpl(info).prependTo( op['market-edit'] );
				$( op['market-birth'] ).find('input').hg_datepicker();
			},
			
			_delMember : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
					id = item.attr('_id');
					this._del(id, item, self);
					event.stopPropagation();
			},
			
			_del : function(id, item, self){
				if(item[0]){
					var method = function(){
						var url = './run.php?mid='+ gMid + '&a=delete';
						$.get(url, {id : id}, function(){
							item.remove();
						});
					}
					this._remind( '是否要删除选中数据?','删除提醒', method, self );
				}else{
					jAlert('请选择要删除的记录','删除提醒').position( self );
				}
			},
			
			_delAll : function( event ){
				var op = this.options,
					widget = this.element; 
				var self = $(event.currentTarget);
				var	item = widget.find( op['m2o-each'] + '.selected'),
					ids = item.map(function(){
						return $(this).attr('_id');
					}).get().join(',');
				this._del(ids, item, self);
			},
			
			_remind : function( title, message, method, self ){
				jConfirm( title, message, function(result){
					if(result){
						method();
					}else{
						return;
					}
				}).position( self );
			},
			
			_unlockMember : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var item = self.closest( op['m2o-each'] ),
					obj = item.find( op['m2o-state'] ),
					old_status = obj.attr('_status');
					id = item.attr('_id');
				var url = "./run.php?mid=" + gMid + "&a=unbind";
				if(old_status == 2){
					var method = function(){
						$.globalAjax( self, function(){
							return $.getJSON(url, {id : id}, function( data ){
									var data = data[0];
									if(data == "success"){
										obj.text('未绑定').attr('_status','1').css({'color':'#8ea8c8'});
										self.removeClass( op['m2o-lock'] );
									}
								});
						});
					}
					this._remind( '是否要解除绑定选中数据?','解除绑定提醒', method, self );
				}else{
					return false;
				}
			},
			
			_bindMember : function( event ){
				var op = this.options,
					widget = this.element,
					self = $(event.currentTarget);
				var item = self.closest( op['m2o-each'] ),
					obj = item.find( op['m2o-state'] ),
					old_status = obj.attr('_status');
				var info = {};
				info.member_id = item.attr('_id');
				info.market_id = widget.attr('_id');
				info.card_number = item.find('.m2o-num span').html();
				info.phone_number = item.find('.m2o-tel span').html();
				if(old_status == 1){
					var url = "./run.php?mid=" + gMid + "&a=bind";
					$.get(url, info, function(){
						obj.text('已绑定').attr('_status','2').css({'color':'#17b202'});
					})
				}else{
					return false;
				}
			},
			
			_checkMessage : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
				var	isChecked = self.prop('checked');
					item[( isChecked ? 'add' : 'remove') + 'Class']( op['selected'] );
			},
			
			_checkAll : function( event ){
				var widget = this.element,
					op = this.options,
					self = $(event.currentTarget);
				var isChecked = self.prop('checked');
				widget.find( op['m2o-check'] ).prop('checked',isChecked).closest( op['m2o-each'] ).each(function(){
					$(this)[( isChecked ? 'add' : 'remove') + 'Class']( op['selected'] );
				});
			},
			
			_leadMember : function(){
				this.fileLead.click();
			},
			
			_sendMessage : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var	member_id = self.closest( op['market-member'] ).attr('_id');
					market_id = $( op['market-edit'] ).data('id'),
					url = "./run.php?mid=" + gMid + '&a=pushMessageToMember',
					content = $('.member-content').find('textarea').val();
				var param = {};
				param.member_id = member_id;
				param.market_id = market_id;
				param.memberinfo =  content;
				$.globalAjax( self, function(){
					return $.getJSON(url, param , function( data ){
						var data = data[0];
						if(data == 'sucess'){
							obj = $('.result-tip');
							obj.css( {'opacity':1,'z-index':1000 });
							setTimeout(function(){
								obj.css( {'opacity':0, 'z-index':-1});
								$('.member-content').find('textarea').val('');
							},2000);
						}
					});
				});
			},
			
			_pasteStatus : function( event ){
				this.element.find('.market-list').submit();
			},
		});
	})($);
	$('.m2o-main').market_member();
});
