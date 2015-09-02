(function(){
	var opts = {
		getInfoUrl : '../lottery.php',
		listBox : $('.list-block'),
		count : 20,
		offset : 0,
		more : $('.list-more'),
		pop : $('.pop-box'),
		config : {
			exchange_code : '兑换码'
		},
		pattern : {
			exchange_code : /^\w+$/
		},
		idList : {}
	};
	utils.spinner.show();
	
	hgClient.getUserInfo(function( response ){		//获取用户登录信息
		var userInfo = response && (response.userInfo || response) || '';	
		if( userInfo && userInfo.userid ){
			getMemberInfo( {
				access_token : userInfo.userTokenKey,
				offset : opts.offset,
				count : opts.count
			} );
		}else{
			hgClient.goLogin();
		}
		
	});
	
	function getMemberInfo( param ){
		param.a = 'get_member_win_info';
		var href = window.location.href,
			position = href.indexOf('?');
		if(position>0){
			var hashstr = href.slice(position+1);
				hasharr = hashstr.split('&');
			$.each(hasharr, function(key,value){
				var hashvalue = value.split('=');
				param[hashvalue[0]] = hashvalue[1];
			});
		}
		$.getJSON( opts.getInfoUrl, param, function( data ){
			if( !data ){
				return false;
			}
			var isTip = '';
			
			if( data.ErrorCode ){
				isTip = data.ErrorText || data.ErrorCode;
			}else{
				opts.more[(data.length < opts.count) ? 'hide' : 'show']();
				if( $.isArray( data ) && data[0] ){
					$.each( data, function( _, vv ){
						opts.idList[vv.id] = {
							exchange_switch : vv.exchange_switch,
							exchange_code : vv.exchange_code,
							sendno : vv.sendno,
							member_info_flag : vv.member_info_flag,
							provide_status : vv.provide_status,
							exchange_qrcode : vv.exchange_qrcode,
						};
						vv.access_token = param.access_token;
						vv.provide_title = vv.provide_status == 1 ? '已兑换' : '兑换';
						vv.img_info = utils.createImgsrc( vv, {
							width : 228,
							height : 228
						} );
						vv.address = encodeURI( vv.address );
					} );
					localStorage.setItem('lottery.idlist', JSON.stringify( opts.idList ));
				}else{
					if( param.offset == 0 ){
						isTip = '暂没有中奖信息，赶紧去抽奖把！';
					}
				}
			}
			var html = template('list', {list : data, isTip : isTip});
			opts.listBox.data( 'param', JSON.stringify( param ) ).append( html );
			utils.spinner.close();
		});
	}
	
	opts.more.on('click', function(){
		var $this = $( this );
		utils.spinner.show( $this );
		var param = opts.listBox.data( 'param' );
		param.offset += opts.count;
		getMemberInfo({
			access_token : param.access_token,
			offset : param.offset,
			count : opts.count
		});
	});
	
	opts.listBox.on('click', '.img-box', function( event ){
		var self = $(event.currentTarget);
			dswitch = self.data('switch');
		var id = self.closest('.item').data('id');
		
		var idlist = localStorage.getItem('lottery.idlist');
		if( idlist ){
			idlist = JSON.parse( idlist );
			if( idlist[id] ){
				var iditem = idlist[id];
				if( iditem.provide_status == 1 ){
					return false;
				}else if( !iditem.member_info_flag ){
					location.href = opts.pop.find('.btn-operate').attr('_href') + '&id=' + id;
					return false;
				}
				opts.pop[( iditem.exchange_switch == 1 ? 'add' : 'remove')  + 'Class' ]('pop-code').show();
				opts.pop.find('input[name="sendno"]').val( opts.idList[id]['sendno'] );
				opts.pop.find('.qrcode').attr('src' , opts.idList[id]['exchange_qrcode']);
				opts.pop.find('.exchange_p').html( opts.idList[id]['exchange_code'] || '暂无兑换码' );
				opts.pop.find('.btn-operate')[0].href = opts.pop.find('.btn-operate').attr('_href') + '&id=' + id;
			}
			
		}
	});
	
	opts.pop
		.on('click', '.btn-cancel', function(){
			opts.pop.hide();
		})
		.on('click', '.btn-submit', function( event ){
			event.preventDefault();
			opts.pop.hide();
			return false;
			var param = {
					a : 'exchange_prize',
					send_no : opts.pop.find('input[name="sendno"]').val(),
					exchange_code : opts.pop.find('.exchange_p').html()
				};
			var listParam = opts.listBox.data( 'param' );
			param.access_token = listParam.access_token;
			
			utils.doAjax(opts.getInfoUrl, param, function( data ){
				if( data && (data.ErrorCode || data.ErrorText) ){
					showTips( data && (data.ErrorCode || data.ErrorText) );
				}
				
				if( $.isArray( data ) && data[0] == 'success' ){
					showTips( '兑换成功', function(){
						opts.pop['removeClass']('pop-code').hide();
						location.reload();
					} );
				}
			}, 'post');
		})
		.on('click', '.btn-operate', function(){
			opts.pop['removeClass']('pop-code').hide();
		});
		
	function showTips( tip, callback ){
		opts.pop.find('.tips').html( tip );
		setTimeout(function(){
			$.isFunction( callback ) && callback();
			opts.pop.find('.tips').html('');
		}, 1500);
	}
})();
