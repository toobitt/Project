$(function(){
				$('.confirm-pay').click(function(){
					var target = $(this),
						url = 'run.php?mid='+ gMid +'&a=confirmPay',
						id = target.attr('_id');
					jConfirm('确认付款？', '操作确认', function( result ){
						if( !result ) return;
						$.globalAjax(target, function(){
							return $.getJSON(url, {id : id}, function( json ){
								var json = json[0];
								target.myTip({
									string : json.status_text
								});
								var time = setTimeout(function(){
									target.hide();
								},1000);
							});
						});
					}).position( target );
				});
				$('.fapiao-btn').click(function(){
					var target = $(this),
						url =  './run.php?mid='+gMid+'&a=auditInvoice';
					var params = {
							id : $('.invoice-apply-id').val(),
							status : target.attr('_status')
						};
					var doAjax = function(){
						$.globalAjax(target, function(){
							return $.getJSON(url, params, function( json ){
								var json = json[0];
		    					target.myTip({
									string : json.status_text
			    				});
			    				$('.current-invoice-status').val(json.status_text);
			    				$('.invoice-btns[_status="'+ json.status +'"]').removeClass('hide').siblings().addClass('hide');
							});
						});
					};
					if( params.status == 3 ){	//打回 
						jPrompt('请填写打回原因','','打回发票申请',function(info){
							if( info ){
								params.reason = info;
								doAjax();
							}
						}).position(target);
					}else{
						jConfirm('确定要'+target.val()+'?', '操作确认', function( result ){
							result && doAjax();
						}).position(target);
					}
					
				});

				$('.business-auth-btn').click(function(){
					var target = $(this),
						url = 'run.php?mid='+ gMid +'&a=audit';
					var params = {
							id : $('#_business_auth_id').val(),
							status : target.attr('_status')
						};
					var doAjax = function(){
						$.globalAjax(target, function(){
	    					return $.getJSON(url, params, function( json ){
		    					var json = json[0];
		    					target.myTip({
									string : json.status_text
			    				});
			    				$('.business-current-status').val(json.status_text);
			    				$('.business-btns[_status="'+ json.status +'"]').removeClass('hide').siblings().addClass('hide');
	    					});
	    				});
					};
					if( params.status == 3 ){	//打回 
						jPrompt('请填写打回原因','','打回授权',function(info){
							if( info ){
								params.reason = info;
								doAjax();
							}
						}).position(target);
					}else{
						jConfirm('确定要'+target.val()+'?', '操作确认', function( result ){
							result && doAjax();
						}).position(target);
					}
				})
			});