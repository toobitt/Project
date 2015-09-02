$(function(){
	
( function($){
	var globalPrizeInfo = {};
	
	var platform = navigator.userAgent.toLowerCase(),
	isIOS= (/iphone|ipod|ipad/gi).test(platform),
	isIPad = (/ipad/gi).test(platform),
	isAndroid = (/android/gi).test(platform),
	isAndroidOld = (/android 2.3/gi).test(platform) || (/android 2.2/gi).test(platform),
	isSafari = (/safari/gi).test(platform) && !(/chrome/gi).test(platform),
	isWifiwx = true,
	isWechat = /micromessenger/gi.test(platform);
	
	if( globalprize ){
		
	$.each( globalprize, function( key, value ){
		var obj = {};
		obj.id = value['id'];
		obj.name = value['name'];
		obj.prize = value['prize'];
		value['host'] && ( obj.pic = value['host'] + value['dir'] + '140x120/' + value['filepath'] + value['filename'] );
		globalPrizeInfo[ obj.id ] = obj;
	} );

	
	window.ConfigData = {
		lottery_url : RESOURCE_URL + '?a=lottery',
		send_lottery_url : RESOURCE_URL + '?a=update_win_info',
		send_address_url : RESOURCE_URL + '?a=update_address',
		userInfo : null,
		systemInfo : null
	};
	
	/*类式继承函数*/
	function extendClass( subclass, superclass ){
		var Func = function(){};
		Func.prototype = superclass.prototype;
		subclass.prototype = new Func();
		subclass.prototype.constructor = subclass;
	}
	
	window.extendClass = extendClass;
	
	/*抽奖游戏 父类 start*/
	function hg_Lottery( options ){
		this.element = options.element;
		this.needreinit = true;
		this.allAwards = globalPrizeInfo;
		this.currentAward = {};
		this.lottery_id = this.element.attr('_id');
		this.body_mask = $('.body-mask');
		this.lotteryawardsPop = this.element.find('.lottery-awards-pop');
		this.lotterylimitPop = this.element.find('.lottery-limit-tip');
		this.lottery_nologin_box =  this.element.find('.lottery-nologin-area');
		this.lottery_canvaswrap = this.element.find('.lottery-canvas-wrap');
		this.lottery_mask = this.element.find('.lottery-mask');
		this.lottery_personinfo_box = this.element.find('.lottery-personinfo-box');
		this.lottery_awardsresult_box = this.lotteryawardsPop.find('.awards-tip-box');
		this.lottery_awardsresult_box_con = this.lottery_awardsresult_box.find('.awards-tip');
		this.lottery_startbtn = this.element.find('.lottery-start-btn');
		this.bindEvent();
	}
	
	hg_Lottery.prototype={
		bindEvent : function(){
			var _this = this,
				Etype = device && device.desktop() ? 'click' : 'touchstart';
			
			/*启动抽奖事件*/
			this.element.on( Etype, '.lottery-start-btn', function( event ){
				var target = $(event.currentTarget);
				_this.statrLottery( target );
			});	
			/*未登录状态刷新事件*/
			this.element.on(Etype,'.lottery-refresh', function(){
				var href = window.location.href;
				window.location.href = href;
			});
			/*未刷新状态去登录事件*/
			this.element.on(Etype,'.lottery-gologin', function(){
				_this.api.goUcenter();
			});
			
			/*中奖弹窗-关闭抽奖弹窗事件*/
			this.lotteryawardsPop.on( Etype, '.close', function( event ){
				_this.closeLotteryPop();
			});
			
			/*中奖弹窗-抽奖弹窗去兑奖操作事件*/
			this.lotteryawardsPop.on( Etype, '.lottery-reward-btn', function( event ){
				//_this.rewardlottery();
			});
			
			/*中奖弹窗-提交兑奖个人信息*/
			this.lotteryawardsPop.on( Etype, '.lottery-submit', function( event ){
				_this.submitLotteryPersoninfo();
			});
			
			/*提示信息弹窗-抽奖提示信息取消操作事件*/
			this.lotterylimitPop.on( Etype, '.cancle-mask', function( event ){
				_this.cancleLotteryLimitPop();
			});
			
			/*提示信息弹窗-抽奖提示信息确定操作事件*/
			this.lotterylimitPop.on( Etype, '.sure-mask', function( event ){
				_this.sureLotteryLimitPop();
			});
			
			/*提示信息弹窗-打开抽奖提示信息弹窗*/
			this.element.on( Etype, '.lottery-mask', function( event ){
				_this.openLotteryLimitPop();
			});
			
		},
		
		toggleStartLotteryBtnabled : function( bool, target ){
			var target = target || this.lottery_startbtn;
			target[bool ? 'removeClass' : 'addClass']('disabled');
		},
		
		/*启动抽奖*/
		statrLottery : function( target ){
			if( target.hasClass('disabled') ) return;
			if( target.hasClass('enterretry') ){
				this.ajaxLottery();
			}else{
				this.statrLotteryCallback();
			}
			this.toggleStartLotteryBtnabled(false);
		},
		/*初始化抽奖*/
		initLottery : function(){
			this.ajaxLottery();
		},
		/*请求中奖信息*/
		ajaxLottery : function(){
			var _this = this,
				url = ConfigData.lottery_url,
				data = this.getUserInfo();
			if( this.lotterystarting ) return;
			this.showLoading();
			this.lotterystarting = true;
			data.id = this.lottery_id;
			if( isIOS ){
				data.GPS_longitude = ConfigData.location.longitude;
				data.GPS_latitude = ConfigData.location.latitude;
			}else{
				data.baidu_longitude = ConfigData.location.longitude;
				data.baidu_latitude = ConfigData.location.latitude;
			}
//			print.log( data );
			var xhr = $.post( url, data,function( data ){
				_this.closeLoading();
					var name = '',
						is_limit_mask = false;
					if( $.isArray( data ) ){
						var data = data[0];
						_this.ErrorCode = '';
						_this.currentAward = data;
						name = data['name'];
						if( +data['score_limit'] && !_this.jifenTip ){
							_this.jifen = data['need_score'];
							is_limit_mask = true;
						}else{
							is_limit_mask = false;
						}
					}
					if( data['ErrorCode'] ){
						is_limit_mask = true;
						_this.ErrorCode = data['ErrorCode'];
						name = '';
					}
					_this.toggleLotterymask( is_limit_mask );
					if( _this.needreinit ){
						_this.initLotteryCallback( {
							is_limit : is_limit_mask,
							name : name
						} );
					}else{
						_this.toggleEnterRetryStatus && _this.toggleEnterRetryStatus(false);
					}
			}, 'json' );
		},
		/*确认中奖信息回传接口*/
		sureajaxLottery : function(){
			var url = ConfigData.send_lottery_url,
				data = this.getUserInfo(),
				id = this.currentAward.id,
				sendno = this.currentAward.sendno;
			data.sendno = sendno;
			data.id = id;
			$.post( url, data, function(){
				
			},'json' );
		},
		/*打开中奖弹窗显示中奖结果*/
		lotteryResultCallback : function( info ){
			var id = info['id'];
    		id && ( info.pic = this.allAwards[id]['pic'] || '' );
			var dom = template( 'lottery-tip-info', info );
			this.lottery_awardsresult_box_con.html( dom );
			this.lotteryawardsPop.show();
			this.body_mask.show();
			id && this.renderLotteryOtherPersoninfo( info['win_info'] );
    		this.lotterystarting = false;
    		info['sendno'] && this.sureajaxLottery();
		},
		
		/*获得用户token信息*/
		getUserInfo : function(){
			var config = ConfigData,
				data = {};
			//print.log( config );
			data.access_token = config.userInfo.userTokenKey;
			//data.access_token = '579b4deb1040e66378977c961c9b8a0d';
			data.device_token = config.systemInfo ? config.systemInfo.device_token : '';
			data.version = config.systemInfo ? config.systemInfo.program_version : '';
			return data;
		},
		/*关闭中奖信息弹窗*/
		closeLotteryPop : function(){
			this.body_mask.hide();
			this.lotteryawardsPop.hide();
			this.toggleLotteryPersoninfo( false );
		},
		/*渲染其他人中奖信息*/
		renderLotteryOtherPersoninfo : function( data ){
			//console.log( data );
			if( $.isArray( data ) &&  data.length ){
				var obj = {};
				obj.list = data;
				var dom = template( 'people-lottery-info', obj );
				this.element.find('.other-lottery-info').html( dom );
			}
		},
		/*是否登录状态切换*/
		toggleLoginStatus : function( islogin, api ){
			this.lottery_nologin_box[ islogin ? 'hide' : 'show' ]();
			this.lottery_canvaswrap[ islogin ? 'removeClass' : 'addClass' ]('mask');
			this.api = api;
		},
		/*打开提示信息弹窗*/
		openLotteryLimitPop : function(){
			var msg = '';
			if( this.jifen ){
				msg = '抽奖需要消耗' + this.jifen + '个积分，是否继续?';
				this.lotterylimitPop.addClass('need-confirm');
			}
			if( this.ErrorCode ){
				this.lotterylimitPop.removeClass('need-confirm');
				msg = this.ErrorCode;
			}
			!msg && ( msg = '抽奖信息初始化失败，请一会重试！' );
			this.lotterylimitPop.show();
			this.lotterylimitPop.find('.msg').text( msg );
		},
		/*确定提示信息*/
		sureLotteryLimitPop : function(){
			this.cancleLotteryLimitPop();
			if( this.lotterylimitPop.hasClass('need-confirm') ){
				this.toggleLotterymask( false );
				this.jifenTip = true;
				if( !this.needreinit ){
					this.statrLottery( this.lottery_startbtn );
				}
			}
		},
		/*取消提示信息*/
		cancleLotteryLimitPop : function(){
			this.lotterylimitPop.hide();
		},
		/*切换抽奖区域是否可用*/
		toggleLotterymask : function( bool ){
			this.lottery_mask[ bool ? 'show' : 'hide' ]();
		},
		/*切换是否显示个人地址信息form表单*/
		toggleLotteryPersoninfo : function( bool ){
			this.lotteryawardsPop.find('.lottery-result-box')[bool ? 'hide' : 'show']();
			this.lottery_personinfo_box[!bool ? 'hide' : 'show']();
			this.lottery_personinfo_box.find('input,textarea').val('');
		},
		
		/*去兑奖*/
		rewardlottery : function(){
			this.toggleLotteryPersoninfo( true );
		},
		/*提交用于兑奖的个人信息*/
		submitLotteryPersoninfo : function(){
			if( this.addressAjax ) return;
			var personinfo_box = this.lottery_personinfo_box;
			var data = {},
				tel_input = personinfo_box.find('.tel-txt'),
				address_area = personinfo_box.find('.address-txtarea');
			data.phone_num = $.trim( tel_input.val() );
			data.address = $.trim( address_area.val() );
			if( data.phone_num && data.address ){
				this.ajaxsendAddress( data );
				this.addressAjax = true;
			}
		},
		/*ajax提交个人地址信息*/
		ajaxsendAddress : function( info ){
			var _this = this,
				url = ConfigData.send_address_url,
				data = this.getUserInfo(),
				id = this.currentAward.id,
				sendno = this.currentAward.sendno;
			data.sendno = sendno;
			data.id = id;
			this.showLoading();
			$.extend( data, info );
			$.post( url, data, function(){
				_this.closeLotteryPop();
				_this.closeLoading();
				_this.addressAjax = false;
			},'json' );
		},
		
		createSpinner : function(){
			var option = {
				lines : 12,
        		length : 8,
        		width : 3,
        		speed : 1.4,
        		radius : 8,
        		color : '#999'
        	};
			return new Spinner( option );
		},
		
		showLoading : function( target ){
			var target = target || this.element;
			this.spinner = this.createSpinner();
			this.spinner.spin(target[0]);
		},
		
		closeLoading : function(){
			this.spinner && this.spinner.stop();
			this.spinner && delete this.spinner;
		}
		
		
	};
	
	/*抽奖游戏 父类 end*/
	
	window.hg_Lottery = hg_Lottery;
	
	/*公用调用客户端取得信息后初始化游戏函数*/
	window.hg_ClientApi = function( lotteryObject ){
		if( lotteryObject ){
			var api = new Api({
				onlyTokenKey : function(json){
					//print.log( json );
					ConfigData.userInfo = json;
					var interval = setInterval( function(){
						if( ConfigData.systemInfo && ConfigData.location ){
							lotteryObject.initLottery();
							clearInterval( interval );
						}
					}, 10 );
				},
				authDeviceSuccess : function( json ){
					//print.log( json );
					if( json.errorCode ){
						alert( json.errorCode );
						return;
					}
					ConfigData.systemInfo = json;
				},
				nologinCallback : function(){
					lotteryObject.toggleLoginStatus( false );
				},
				getLocationSuccess : function( json ){
					//print.log( json );
					if( json.errorCode ){
						alert( json.errorCode );
						return;
					}
					ConfigData.location = json;
				}
			});
		}
	};
	
		
	}else{
		setTimeout( function(){
			alert( '奖项信息未配置,请配置奖项信息!' );
		}, 1000 );
	}
	
} )($);
	
	
});
