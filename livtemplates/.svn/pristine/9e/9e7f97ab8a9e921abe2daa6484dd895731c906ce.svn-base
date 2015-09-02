$(function(){
	(function($){
		
		$.widget('workload.workdetail',{
			options : {
			},
			_init : function(){
				this._on({
					'click .operations' : '_getOperation',
					'click .clicks' : '_getClicksDate',
					'click .member-title' : '_toggle',
				})
			},
			
			_getOperation : function(){
				var user_id = MC.find('input[name="user_id"]').val();
				var url = 'run.php?a=get_log_operation&mid=' + gMid + '&user_id=' + user_id;
				var item = this.element.find('.operations-info');
				var tpl = MC.find('.operations-info .m2o-item-col');
				if(tpl.length == 0)
				{
					$.globalAjax(item ,function(){
						return $.getJSON(url, function( json ) {
							if(json['callback']){
								eval( json['callback'] );
								return;
							}else{
								data = json[0];
								if(data && data.total){
							   		$.each(data.total, function(op,total){
								   		var tpl_op = '<div class="m2o-item-col w100 left"><label class="title">' + op + '</label></div>';
								   		var tpl_count = '<div class="m2o-item-col w100 left"><label class="title">' + total + '</label></div><div style="clear: both"></div>';
								   		tpl += (tpl_op + tpl_count);
								   	})
								   	if(data.date){
									   	$.each(data.date, function(date,opera){
									   		var tpl_date = '<div class="m2o-item total_num"><label class="title">' + date + '</label></div>';
									   		$.each(opera, function(opname,opcount){
									   			var tpl_opname = '<div class="m2o-item-col w100 left"><label class="title">' + opname + '</label></div>';
										   		var tpl_opcount = '<div class="m2o-item-col w100 left"><label class="title">' + opcount + '</label></div><div style="clear: both"></div>';
										   		tpl_date += (tpl_opname + tpl_opcount);
									   		})
									   		tpl += (tpl_date);
								   		})
								   	}
							   	}
								if(data == null){
								   	tpl = '<p class="m2o-item-col" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">该用户没有操作记录！</p>';
							   	}
								$(tpl).appendTo('.operations-info');
							}
						});
					} );
				}
			},
			
			_getClicksDate : function(){
				var user_name = MC.find('input[name="member_name"]').val();
				var url = 'run.php?a=get_access&mid=' + gMid + '&user_name=' + user_name;
				var item = this.element.find('.clicks-info');
				var tpl = MC.find('.clicks-info .m2o-item-col');
				if(tpl.length == 0)
				{
					$.globalAjax(item ,function(){
						return $.getJSON(url, function( json ) {
							if(json['callback']){
								eval( json['callback'] );
								return;
							}else{
								data = json[0];
								var click_total = data.total;
								if(click_total){
									tpl += '<div class="m2o-item-col w100 left"><label class="title">点击数</label></div>';
									tpl += '<div class="m2o-item-col w100 left"><label class="title">' + click_total.click_num + '</label></div><div style="clear: both"></div>';
									tpl += '<div class="m2o-item-col w100 left"><label class="title">评论数</label></div>';
									tpl += '<div class="m2o-item-col w100 left"><label class="title">' + click_total.comment_num + '</label></div><div style="clear: both"></div>';
									tpl += '<div class="m2o-item-col w100 left"><label class="title">分享数</label></div>';
									tpl += '<div class="m2o-item-col w100 left"><label class="title">' + click_total.share_num + '</label></div><div style="clear: both"></div>';
							   	}
							   	if(data.column){
							   		$.each(data.column, function(op,column){ 
							   			tpl+= '<div class="m2o-item-col w100 left"><label class="title">' + column.column_name + '</label></div>';
							   			tpl+= '<div class="m2o-item-col w100 left"><label class="title">' + column.click_num + '</label></div>';
							   			tpl+= '<div class="m2o-item-col w100 left"><label class="title">' + column.comment_num + '</label></div>';
							   			tpl+= '<div class="m2o-item-col w100 left"><label class="title">' + column.share_num + '</label></div><div style="clear: both"></div>';
								   	})
							   	}
							   	if(data == null){
								   	tpl = '<p  class="m2o-item-col" style="color:#da2d2d;text-align:center;font-size:20px;line-height:50px;font-family:Microsoft YaHei;">该用户没有操作记录！</p>';
							   	}
							   	$(tpl).appendTo('.clicks-info');
							}
						});
					} );
				}
			},
			

			_toggle : function( event ){
				var self = $( event.currentTarget ),
					index = self.index();
				self.addClass('active').siblings().removeClass('active');
				MC.find('.member-info:eq('+ index +')').show().siblings('.member-info').hide();
			},
			
			
			_myTip : function( tip ){
				this.element.find('.save-button').myTip({
					string : tip,
					width : 200,
					delay: 1000,
					dtop : 0,
					dleft : -120,
				});
			},
		});	
	})($);
	
	var MC = $('.m2o-workolad');
	MC.workdetail({});
})