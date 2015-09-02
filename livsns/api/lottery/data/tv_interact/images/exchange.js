(function(){
	var opts = {
		getInfoUrl : '../lottery.php',
		count : 20,
		offset : 0,
		pop : $('body'),
		recordBox : $('.record-box'),
		idList : {},
		param : {},
	};

	var storage = window.localStorage,
		storageSeller = storage.getItem('seller_id');
	
	utils.spinner.show();	

	init();
		
	var sendNo = getSendNo();
	
	function getSendNo(){
		var url =location.search;
		var strs = url.split('?');
		var pairs = strs[1].split('=');
		var send_no = pairs[1];
		return send_no;
	}
	function init(){
		if( storageSeller ){
			opts.pop.find('.content-box').show();
		}else{
			opts.pop.find('.seller-box').show();
			utils.spinner.close();	
			return;
		}
		var timer = setInterval(function(){
			console.log(sendNo);
			if( sendNo && storageSeller ){
				var seller_id = JSON.parse( storageSeller );
				getAwardInfo({
					type : 2,
					seller_id : seller_id,
					send_no : sendNo
				});
				clearInterval( timer );
			}
		},'10');
	}
	
	function getAwardInfo( param ){
		param.a = 'get_order_info';
		$.getJSON( opts.getInfoUrl, param, function( data ){
			utils.spinner.close();
			if( data.ErrorCode || data.ErrorText ){
				showTip( data.ErrorCode );
				return;
			}
			var data = data[0];
			var img = data.prize_info.host && (data.prize_info.host + data.prize_info.dir + data.prize_info.filepath + data.prize_info.filename);
			var tip = (data.provide_status==1) ? '已兑换' : '确认兑换',
				className = (data.provide_status==1) ? 'has-confirm' : 'confirm-btn';
			opts.pop.find('input[name="send"]').val( param.seller_id );
			opts.pop.find('.current-seller-num a').text( param.seller_id );
			opts.pop.find('.award-pic').attr('src' , img ).css('opacity' , 1);
			opts.pop.find('.prize').text( data.prize_info.prize );
			opts.pop.find('.prize-name').text( data.prize_info.name );
			opts.pop.find('.prize-address').text( data.address );
			opts.pop.find('.phone-num').text( data.phone_num );
			opts.pop.find('.btn').text( tip ).addClass( className );
			if( param.type == 1 ){
				opts.pop.find('.seller-box').css('left' , '-100%');
				opts.pop.find('.content-box').show();
				storage.removeItem('seller_id');
				storage.setItem('seller_id' , JSON.stringify( param.seller_id ));
			}
		});
	}
	
	function getRecordInfo( param ){
		param.a = 'seller_exchange_info';
		$.getJSON( opts.getInfoUrl, param, function( data ){
			utils.spinner.close();
			var info = {};
			info.data = data;
			var recordHtml = template('record-info' , info);
			opts.recordBox.find('.record-list').append( recordHtml );
			opts.recordBox.css('top' , '0px');
		});
	}
	
	function confirm_prize(param){
		param.a = 'confirm_prize';
		$.getJSON( opts.getInfoUrl, param, function( data ){
			if( data.ErrorCode ){
				showTip( data.ErrorCode  );
				utils.spinner.close();
				return;
			}
			if( data == 'success' ){
				utils.spinner.close();
				showTip( '兑换成功' );
				$('.btn').text('已兑换').removeClass('confirm-btn').addClass('has-confirm');
			}
		});
	}
	
	function showTip( tip ){
		$.hg_toast({
			appendTo : 'body',
			delay : 1500
		}).show( tip );
	}
	
	opts.pop
	.on('touchstart' , '.login' , function(){
		var seller_id = opts.pop.find('input[name="seller_id"]').val();
		if( seller_id ){
			utils.spinner.show();	
			var param = {
					type  : 1 ,
					seller_id : $.trim( seller_id ),
					send_no : sendNo
			};
			getAwardInfo( param );
		}
	})
	.on('touchstart' , '.logout' , function(){
		storage.removeItem('seller_id');
	//	opts.pop.find('.content-box').hide();
		opts.pop.find('.seller-box').show().css('left' , 0 );
	})
	.on('touchstart' , '.confirm-btn' , function(){
		utils.spinner.show();
		confirm_prize({
			seller_id : opts.pop.find('input[name="send"]').val(),
			send_no : sendNo
		});
	})
	.on('touchstart' , '.check-history' , function(){
		utils.spinner.show();
		getRecordInfo( {
			seller_id : opts.pop.find('input[name="send"]').val(),
			send_no : sendNo
		});
	})
	.on('touchstart' , '.close-record' , function(){
		opts.recordBox.css('top' , '100%');
		opts.recordBox.find('.record-list').html('');
	})
})();
