$(function($){
	(function($){
		$.widget('market.special_activity',{
			options : {
				'market-edit' : '.market-edit',
				'add-member-tpl' : '#add-member-tpl',
				'mem-pink' : '.mem-pink',
				'm2o-check' : '.m2o-check',
				'market-init' : 'market-init',
				'm2o-each' : '.m2o-each',
				'selected' : 'selected',
				'm2o-delete' : '.m2o-delete',
				'm2o-edit' : '.m2o-edit',
				'checkAll' : '.checkAll',
				'batch-delete' : '.batch-delete',
				'shop' : '.shop',
				'market-activity' : '.market-activity',
				'state_show' : '.state_show',
				'market-list' : '.market-list',
			},
			_create : function(){
				
			},
			_init : function(){
				var _this = this,
					op = this.options,
					handlers = {};
				handlers['click ' + op['mem-pink'] ] = '_addActivity';
				handlers['click ' + op['m2o-delete'] ] = '_delActivity';
				handlers['click ' + op['batch-delete'] ] = '_delAll';
				handlers['click ' + op['m2o-check'] ] = 'toggleSelect';
				handlers['click ' + op['checkAll'] ] = '_toggleSelectAll';
				handlers['click ' + op['m2o-edit'] ] = '_editActivity';
				handlers['click ' + op['state_show'] ] = '_pasteStatus';
				this._on(handlers);
				this._initForm();
			},
			_initForm : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.value = '新增';
				info.method = 'create';
				$( op['add-member-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$( op['market-activity'] ).find('input').each(function(){
					$(this).hg_datepicker();
				});
			},
			_addActivity : function(){
				var op = this.options,
					info = {};
				info.opera = '新增';
				info.value = '新增';
				info.method = 'create';
				$( op['market-edit'] ).empty();
				$( op['add-member-tpl'] ).tmpl( info ).prependTo( op['market-edit'] );
				$( op['market-edit'] ).removeClass( op['market-init'] );
				$( op['m2o-check'] ).each(function(){
					$(this).attr('checked',false);
				});
				$( op['market-activity'] ).find('input').each(function(){
					$(this).hg_datepicker();
				});
			},			
			_editActivity : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
			 	var id = item.attr('_id'),
					url = './run.php?mid=' + gMid + '&a=getActivityInfo&id=' + id;
				$.getJSON( url, function( data ){
					if(data){
						var data=data[0];
					    	  info={};
					    	  store_id = data.store_id;
					    	 info.title = data.title;
					    	 info.start_time = data.start_time;
					    	 info.end_time = data.end_time;
					    	 info.method = 'update';
					    	 info.id = id;
					    	 info.opera = '编辑';
					    	 info.value = '保存';
					    	 $( op['market-edit'] ).empty();
					    	 $( op['add-member-tpl'] ).tmpl(info).appendTo( op['market-edit'] );
					         $( op['market-edit'] ).addClass( op['market-init'] );
					         $( op['market-activity'] ).find('input').each(function(){
								$(this).hg_datepicker();
							});
					         var store = store_id.split(',');
					         for(var i=0; i<store.length; i++){
				        		var it = store[i];
					        	$( op['shop'] ).find('span').each(function(){
						    	 	var my = $(this);
						    	 	if(my.find('input').val() == it){
						    	 		my.find('input').attr('checked',true);
						    	 	}
						    	 });
						    }
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
				var isSelected = item.find('input').prop('checked');
				item[(isSelected ? 'add': 'remove') + 'Class' ]( op['selected'] );	
			},
			_delActivity : function( event ){
				var op = this.options,
					self = $(event.currentTarget),
					item = self.closest( op['m2o-each'] );
					id = item.attr('_id');
				this._del(id, item);
				event.stopPropagation();
			},
			_del : function( id, item ){
				if(item[0]){
					var method = function(){
						var url = './run.php?mid=' + gMid + '&a=delete';
						$.get(url, {id : id}, function(){
							item.remove();
						});
					}
					this._remind( '是否要删除选中内容?', '删除提醒' , method );
				}else{
					jAlert('请选择要删除的内容','删除提醒');
				}
			},
			_remind : function(title, message, method){
				jConfirm( title, message , function(result){
					if( result ){
						method();
					}else{
						
					}
				});
			},
			_delAll : function(){
				var op = this.options,
					item = this.element.find( op['m2o-each'] + '.selected');
				var	ids = item.map(function(){
					return $(this).attr('_id');
				}).get().join(',');
				this._del(ids, item);
			},
			
			_pasteStatus : function( event ){
				var op = this.options,
					self = $(event.currentTarget);
				self.closest( op['market-list'] ).submit();
			},
			
		});
	})($);
	$('.m2o-main').special_activity();
});
