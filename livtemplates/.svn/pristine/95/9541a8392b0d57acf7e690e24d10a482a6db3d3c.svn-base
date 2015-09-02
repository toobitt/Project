$(function($){
	(function($){
		$.widget('market.market_message',{
			options : {
				'market-edit' : '.market-edit',
				'market-init' : 'market-init',
				'add-message-tpl' : '#add-message-tpl',
				'mem-pink' : '.mem-pink',
				'm2o-check' : '.m2o-check',
				'm2o-each' : '.m2o-each',
				'scope_value' : '#scope_show li a',
				'scope_toggle' : '.scope_toggle',
				'market-addmember' : '.market-addmember',
				'add-member-tpl' : '#add-member-tpl',
				'add' : '.add',
				'member-box' : '.member-box',
				'member-mode' : '.member-mode input',
				'member-each' : '.member-each',
				'selected' : 'selected',
				'display_scope_show' : '#display_scope_show',
				'scope' : '#scope',
				'device' : '.device',
				'scope-data' : '.scope-data',
				'expire-time' : '.expire-time',
				'market-scope' : '.market-scope',
				'constell' : '.constell',
				'm2o-delete' : '.m2o-delete',
				'm2o-edit' : '.m2o-edit',
				'checkAll' : '.checkAll',
				'batch-delete' : '.batch-delete',
				'date-setting' : '.date-setting',
				'state_show' : '#state_show li',
				'market-list' : '.market-list',
				'member-write' : '.member-write',
				'm2o-state' : '.m2o-state',
				'batch-back' : '.batch-back',
			},
			_create : function(){
				this.status_color = ['','#8ea8c8','#17b202','#f8a6a6'];
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['mem-pink'] ] = '_addMessage';
				handlers['click ' + op['m2o-edit'] ] = '_editMessage';
				handlers['click ' + op['scope_value'] ] = '_scopeToggle';
				handlers['click ' + op['add'] ] = '_addMember';
				handlers['blur ' + op['member-mode'] ] = '_cancelFocus';
				handlers['click ' + op['m2o-delete'] ] = '_delMessage';
				handlers['click ' + op['checkAll'] ] = '_toggleSelectAll';
				handlers['click ' + op['m2o-check'] ] = 'toggleSelect';
				handlers['click ' + op['batch-delete'] ] = '_delAll';
				handlers['click ' + op['state_show'] ] = '_pasteStatus';
				handlers['click ' + op['member-write'] ] = '_writeMessage';
				handlers['click ' + op['batch-back'] ] = '_backMessage';
				this._on(handlers);
				this._initForm();
			},
			_initForm : function(){
				var op = this.options,
					info = {};
				info.market_id = $( op['market-edit'] ).data('id');
				info.value = '新增';
				info.method = 'create';
				$( op['add-message-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$($( op['scope-data'] ).html()).clone(true).appendTo( op['market-scope'] );
				$( op['date-setting'] ).find('input').each(function(){
					$(this).hg_datepicker({
						dateFormat : 'mm-dd'
					});
				});
				$( op['expire-time'] ).find('input').hg_datepicker();
			},
			_addMessage : function(){
				var op = this.options,
					info = {};
				info.market_id = $( op['market-edit'] ).data('id');
				info.value = '新增';
				info.method = 'create';
				$( op['market-edit'] ).empty();
				$( op['add-message-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$( op['date-setting'] ).find('input').each(function(){
					$(this).hg_datepicker({
						dateFormat : 'mm-dd'
					});
				});
				$( op['expire-time'] ).find('input').hg_datepicker();
				$( op['market-edit'] ).removeClass( op['market-init'] );
				$($( op['scope-data'] ).html()).clone(true).appendTo( op['market-scope'] );
				$( op['m2o-check'] ).each(function(){
					$(this).attr('checked',false);
				});
			},
			_editMessage : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					_this = this;
				var	item = self.closest( op['m2o-each'] ),
					id = item.attr('_id'),
					market_id = $( op['market-edit'] ).data('id');
					url = './run.php?mid=' + gMid + '&a=get_message_info&id=' + id; 
				$.getJSON( url, function( data ){
					if(data){
						var data=data[0];
					    $.each(data,function(key,value){
					    	 var obj=data;
					    	 scopet = data.scope;
					    	 send_device = data.send_device;
					    	 member_id = data.member_id;
					    	 member = data.member;
					    	 constellation_id = data.constellation_id;
					    	  info={};
					    	  info.market_id = market_id;
					    	  info.title = obj.title;
					    	  info.cont = obj.content;
					    	  info.age_start = obj.age_start;
					    	  info.age_end = obj.age_end;
					    	  info.birthday_start = obj.birthday_start;
					    	  info.birthday_end = obj.birthday_end;
					    	  info.send_device = obj.send_device;
					    	  info.id = id;
					    	  info.expire_time = data.expire_time;
					    	  info.value = '保存';
					    	  info.method = 'update';
					    	 $( op['market-edit'] ).empty();
					    	 $( op['add-message-tpl'] ).tmpl(info).appendTo( op['market-edit'] );
					    	 $( op['date-setting'] ).find('input').each(function(){
								$(this).hg_datepicker({
									dateFormat : 'mm-dd'
								});
							});
							$( op['expire-time'] ).find('input').hg_datepicker();
					         $( op['market-edit'] ).addClass( op['market-init'] );
				        	});
				        	var device = send_device.split(','),
				        	constellation = constellation_id.split(',');
				        	for(var i=0; i<device.length; i++){
				        		var it = device[i];
					        	$( op['device'] ).find('span').each(function(){
						    	 	var my = $(this);
						    	 	if(my.find('input').val() == it){
						    	 		my.find('input').attr('checked',true);
						    	 	}
						    	 });
						    }
						    for(var j=0; j<constellation.length; j++){
						    	var ide = constellation[j];
						    	$( op['constell'] ).find('span').each(function(){
						    		var me = $(this);
						    	 	if(me.find('input').val() == ide){
						    	 		me.find('input').attr('checked',true);
						    	 	}
						    	});
						    }
						    var val = scopet,
						   		cont = $.globalscope[scopet];
					    	$( op['display_scope_show'] ).html(cont);
					    	$( op['scope'] ).val(val);
					    	_this._contentToggle(val);
					    	$($( op['scope-data'] ).html()).clone(true).appendTo( op['market-scope'] );
					    	if(member_id){
						    	if(member != ''){
							    	var info = {};
							    	$( op['member-box'] ).empty();
						        	 $.each(member,function(key,value){
								    	info.mem = member[key];
								    	$( op['add-member-tpl'] ).tmpl( info ).appendTo( op['member-box'] );
								    });
							    }else{
							    	return;
							    }
						    }
					}
				});
			},
			_scopeToggle : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				var val = self.attr('attrid');
				this._contentToggle(val);
			},
			
			_contentToggle : function(val){
				var op = this.options,
					member = '';
				switch(val){
					case '1' : {
						$( op['scope_toggle'] ).hide();
						$( op['market-addmember'] ).hide();
						break;
					}
					case '2' : {
						$( op['scope_toggle'] ).show();
						$( op['market-addmember'] ).hide();
						break;
					}
					case '3' : {
						$( op['scope_toggle'] ).hide();
						$( op['market-addmember'] ).show();
						break;
					}
				}
			},
			
			_addMember : function(){
				var op = this.options,
					mem = '';
				$( op['add-member-tpl'] ).tmpl( mem ).appendTo( op['member-box'] );
			},
			_cancelFocus : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					box = self.closest( op['member-each'] );
				if(self.val()){
					box.removeClass( 'member-mode' );
				}else{
					box.remove();
				}
				
			},
			_delAll : function(){
				var op = this.options,
					item = this.element.find( op['m2o-each'] + '.selected' );
				var ids = item.map(function(){
					return $(this).attr('_id');
					}).get().join(',');
				this._del( ids, item);
			},
			_delMessage : function( event ){
				var op = this.options,
					widget = this.element;
				var self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] ),
					id = item.attr('_id');
				this._del( id, item);
				event.stopPropagation();
			},
			_del : function( id , item ){
				if(item[0]){
					var method = function(){
						var url = './run.php?mid=' + gMid + '&a=delete';
						$.get( url, {id : id } ,function(){
							item.remove();
						});
					};
					this._remind( '是否要删除选中内容?', '删除提醒' , method );
				}else{
					jAlert('请选择要删除的内容','删除提醒');
				}
			},	
			_remind : function( title , message , method ){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{
						
					}
				});
			},
			
			_toggleSelectAll : function( event ){
				var widget = this.element,
					op = this.options,
					self = $(event.currentTarget),
        		isSelected = self.prop('checked');
				widget.find( op['m2o-check'] ).prop('checked',isSelected).closest( op['m2o-each'] )
	        	.each(function(){
	        		$(this)[(isSelected ? 'add': 'remove') + 'Class' ]( op['selected'] );
	        	});
			},
			
			toggleSelect : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
				var isSelected = self.prop('checked');
				item[(isSelected ? 'add': 'remove') + 'Class' ]( op['selected'] );	
			},
			
			_pasteStatus : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				self.closest( op['market-list'] ).submit();
			},
			
			_writeMessage : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
				var id = item.attr('_id');
				this._back(id, item);
				event.stopPropagation();
			},
			
			_backMessage : function(){
				var op = this.options,
					item = this.element.find( op['m2o-each'] + '.selected' );
				var ids = item.map(function(){
						return $(this).attr('_id');
					}).get().join(',');
				this._back(ids, item);	
			},
			
			_back : function(id, item){
				var op = this.options,
					_this = this,
					obj = item.find( op['m2o-state'] ),
					market_id = $( op['market-edit'] ).data('id');
				var isTrue = false;
					obj.each(function(){
						old_status = $(this).attr('_status');
						if(old_status == 1){
							isTrue = true;
						}
					});
				url = "./run.php?mid=" + gMid + "&a=sendMessage&market_id=" + market_id;
				if(isTrue == true){
					$.getJSON(url, {id : id}, function( data ){
						var data = data[0];
						status = data['status'];
						status_text = data['status_format'];
						status_color = _this.status_color[status];
						item.find( op['m2o-state'] ).text(status_text).attr('_status',status).css({'color' : status_color});
						_this.element.find( op['m2o-each'] ).each(function(){
							$(this).removeClass( op['selected'] ).find( op['m2o-check'] ).prop('checked',false);
						})
					});
				}else{
					return false;
				}

			},
		});
	})($);
	$('.m2o-main').market_message();
});