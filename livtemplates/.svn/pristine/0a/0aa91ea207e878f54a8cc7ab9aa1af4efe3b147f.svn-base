$(function(){
	(function($){
		var controll = {
			delajax : function( ids, obj, method, self ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1',
					tip, 
					message;
				if(obj.length < 1){
					jAlert("请选择要删除的数据", "删除提醒").position( self );
					return false;
				}
				if( obj.length > 1 ){
					tip = "您确认批量删除选中记录吗？";
				}else{
					tip = "您确定删除该条内容吗？";
				}
				if(!obj.data("magid")){
					message = "请先删除该杂志下的期刊";
				}else{
					message = "请先删除该期刊下的文章";
				}
				jConfirm( tip , '删除提醒' , function( result ){
					if( result ){
						$.globalAjax( self, function(){
							return $.getJSON(url,{id : ids},function( data ){
									if( data == "-1" ){
										jAlert( message , "删除提醒");
										return false;
									}else if(data['callback']){
										eval( data['callback'] );
										return;
									}else{
										obj.remove();
									}
								});
						});
					}
				}).position( self );
			},
			auditajax : function( ids, obj , method, self ){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				if(!obj.data("magid")){
					url += '&audit=1';
				}
				if(obj.length < 1){
					jAlert("请选择要审核的数据", "审核提醒").position( self );
					return false;
				}
				if( obj.length > 1 ){
					var tip = "您确认批量审核选中记录吗？";
				}else{
					var tip = "您确定审核该条内容吗？";
				}
				jConfirm( tip , '审核提醒' , function( result ){
					if( result ){
						$.globalAjax( self, function(){
								return $.getJSON(url,{id : ids},function( data ){
									if(data['callback']){
										eval( data['callback'] );
										$('.load-img').remove();
										return;
									}else{
									obj.find('.m2o-state').text('已审核')
														  .attr('_status',1)
														  .css({'color':'#17b202'});
									}
								});
						});
					}
				} ).position( self );
			},
			
			auditchajax : function(id, obj, method, status){
				var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				var audit,
					isMag = false;
				if(status == 1){
					audit = 2;
				}else{
					audit = 1;
				}
				var info = {},
					each = obj.closest('.magazine-each');
				if(!each.data("magid")){
					isMag = true;
				}
				if(isMag){
					info = {id : id, audit : audit}
				}else{
					info = {id : id}
				}
				$.globalAjax( obj, function(){
					return $.getJSON(url, info, function(data){
						if(data['callback']){
							eval( data['callback'] );
							$('.load-img').remove();
							return;
						}else{
							var data = data[0];
							status = data.status;
							audit = data.audit;
							op = data.op;
							color = status_color[audit];
							var meth = isMag ? 'audit' : op ;
							obj.data('method',meth);
							obj.text(audit).css('color',color)
											.attr('_status',status);
							$('.load-img').remove();
						}
					});
				});
			},
			backajax : function( ids, obj , method, self ){
					var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
				if(!obj.data("magid")){
					url += '&audit=2';
				}
				if(obj.length < 1){
					jAlert("请选择要打回的数据", "打回提醒").position( self );
					return false;
				}
				if( obj.length > 1 ){
					var tip = "您确认批量打回选中记录吗？";
				}else{
					var tip = "您确定打回该条内容吗？";
				}
				jConfirm( tip , '打回提醒' , function( result ){
					if( result ){
						$.globalAjax( self, function(){
							return $.getJSON(url,{id : ids},function( data ){
									if(data['callback']){
										eval( data['callback'] );
										$('.load-img').remove();
										return;
									}else{
									obj.find('.m2o-state').text('已打回')
														  .attr('_status',2)
														  .css({'color':'#f8a6a6'});	
								  	}									  
								});
						});
					}
				} ).position( self );
			},
		};
		
		var param = {
			del : $('.magazine-list').children('.magazine-each').find('.del'),
			checkbox : $('.magazine-list').children('.magazine-each').find('.mag-img')
		}
		
		var status_color = {
			'待审核' : '#8ea8c8',
			'已审核' : '#17b202',
			'已打回' : '#f8a6a6'
		}
		
		$('.common-list-content').glist({
			each : '.magazine-each',
			'batchDelete' : function(event,_this){
				var op = _this.options,
					obj = _this.element.find( op['each'] + '.selected' ),
					self = $(event.currentTarget),
					method = self.data('method');
				var ids = obj.map(function(){
					return $(this).data('id');
					}).get().join(',');
				controll.delajax( ids, obj ,method, self );
			},
			'batchAudit' : function(event, _this){
				var op = _this.options,
					obj = _this.element.find( op['each'] + '.selected' ),
					self = $(event.currentTarget),
					method = self.data('method');
				var ids = obj.map(function(){
					return $(this).data('id');
					}).get().join(',');
				controll.auditajax( ids, obj , method, self );
			},
			'batchBack' : function(event, _this){
				var op = _this.options,
					obj = _this.element.find( op['each'] + '.selected' ),
					self = $(event.currentTarget),
					method = self.data('method');
				var ids = obj.map(function(){
					return $(this).data('id');
					}).get().join(',');
				controll.backajax( ids, obj , method, self );
			},
		});
		$('.magazine-each').geach({
			checkbox : '.mag-img',
			needInfoBtn : false,
			'audit' : function( event, _this ){
				var self = $(event.currentTarget),
					status = self.attr('_status'),
					id = self.attr('_id'),
					method = self.data('method');
				controll.auditchajax( id, self , method, status );
			}
		});
		
		param.del.bind('click', function( event ){
			var self = $(event.currentTarget),
				obj = self.closest('.magazine-each'),
				id = obj.data('id'),
				method = self.data('method');
			controll.delajax( id, obj ,method, self );
		});
		param.checkbox.bind('click', function( event ){
			var op = this.options,
        		self = $(event.currentTarget),
        		obj = self.closest('.magazine-each');
        	obj.each(function(){
        		var isSelected = $(this).hasClass('selected');
        		var noSelected = !isSelected;
        		$(this).find('input').prop('checked', noSelected);
        		$(this)[(isSelected ? 'remove': 'add') + 'Class' ]('selected');
        	});	
		});
	})($);
});
