jQuery(function(){
	(function($){
		var controll = {
				delajax : function( ids, obj, method, self ){
					var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
					if( obj.length < 1 ){
						jAlert("请选择要删除的内容","删除提醒").position( self );
						return false;
					}
					if( obj.length > 1 ){
						var tip = "您确认批量删除选中记录吗？";
					}else{
						var tip = "您确定删除该条内容吗？";
					}
					if(obj.is('.selected')){
						jConfirm( tip , '删除提醒' , function( result ){
							if( result ){
								$.get(url,{id : ids},function(){
									obj.remove();
									if($('.m2o-each').length < 1){
										$('#nodata-tpl').tmpl().appendTo('.m2o-each-list');
									}
								});
							}
						}).position( self );
					}else{
						$.get(url,{id : ids},function(){
							obj.remove();
							if($('.m2o-each').length < 1){
								$('#nodata-tpl').tmpl().appendTo('.m2o-each-list');
							}
						});
					}
				},
				
				auditajax : function( ids, obj, method, self ){
					var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
					if( obj.length < 1 ){
						jAlert("请选择要审核的内容","审核提醒").position( self );
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
										obj.text('已审核').css({'color':'#17b202'});
									}
								});
							});
						}
					} ).position( self );
				},
				
				auditchajax : function( ids, obj , method ){
					var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
					var tip = "您确定审核该条内容吗？";
					$.globalAjax( obj, function(){
						return $.getJSON(url,{id : ids},function( data ){
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
									obj.data('method', op);
									obj.text(audit).css('color',color)
													.attr('_status',status);
								}
						});
					});
				},
				backajax : function( ids, obj, method, self ){
					var url = './run.php?mid=' + gMid + '&a=' + method + '&ajax=1';
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
				}
			};
			var status_color = {
				'待审核' : '#8ea8c8',
				'已审核' : '#17b202',
				'已打回' : '#f8a6a6'
			};
			$('.m2o-each').geach({
				'audit' : function( event, _this ){
					var self = $(event.currentTarget),
						id = self.closest( _this.element ).data('id'),
						method = self.data('method');
					controll.auditchajax( id, self , method );
				}
			});
			$('.m2o-list').glist({
				'batchDelete' : function(event,_this){
					var op = _this.options,
						obj = _this.element.find( op['each'] + '.selected' ),
						self = $(event.currentTarget),
						method = self.data('method');
					var ids = obj.map(function(){
						return $(this).data('id');
						}).get().join(',');
					controll.delajax( ids, obj, method, self );
				},
				'batchAudit' : function(event,_this){
					var op = _this.options,
						obj = _this.element.find( op['each'] + '.selected' ),
						status = obj.find('.m2o-state'),
						self = $(event.currentTarget),
						method = self.data('method');
					var ids = obj.map(function(){
						return $(this).data('id');
						}).get().join(',');
					controll.auditajax( ids, status, method, self );
				},
				'batchBack' : function(event, _this){
					var op = _this.options,
						obj = _this.element.find( op['each'] + '.selected' ),
						self = $(event.currentTarget),
						method = self.data('method');
					var ids = obj.map(function(){
						return $(this).data('id');
						}).get().join(',');
					controll.backajax( ids, obj, method, self );
				},
			});
	})($);
});