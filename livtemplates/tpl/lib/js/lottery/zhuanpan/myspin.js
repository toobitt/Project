$(function(){

if( !globalprize ) return;
	
( function($){
	/*大转盘 class start*/
	function hg_Spin( options ){
		hg_Lottery.call(this,options);
	}
	
	/*大转盘类 继承游戏父类*/
	extendClass( hg_Spin, hg_Lottery );
	
	hg_Spin.prototype.initLotteryCallback = function(){
		this.initSpinCanvas();
		this.needreinit = false;
	};
	
	hg_Spin.prototype.statrLotteryCallback = function(){
		var index = this.getcurrentAwardIndex();
		this.myspin.setUesrAward( index );
		this.myspin.spin();
	};
	
	hg_Spin.prototype.initSpinCanvas = function(){
		var _this = this;
		this.initSpinAwards();
		this.myspin = new hg_SpinClass({
			centerX : 130,
			centerY : 157,
			canvasId : 'zhuanpan-canvas',
			outsideRadius : 130,
			insideRadius : 50,
			textRadius : 110,
			callback : function(){
				_this.lotteryResultCallback( _this.currentAward );
				_this.toggleEnterRetryStatus(true);
			},
			awardsConfig : this.canvasAwards
		});
	};
	
	hg_Spin.prototype.initSpinAwards = function(){
		var _this = this;
		this.canvasAwards = [];
		$.each( this.allAwards, function( key, value ){
			_this.canvasAwards.push({
				id : key,
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : value.name,
				bgColor : '#ecfeff',
			});
			_this.canvasAwards.push({
				awardsType : null,
				bgColor : '#f7dc27',
			});
		} );
	};
	
	hg_Spin.prototype.getcurrentAwardIndex = function(){
		var _this = this,
			index = 0,
			current_id = this.currentAward.id;
		if( !+current_id ){
			index = parseInt( Math.random() * ( this.canvasAwards.length /2  ) )*2 + 1;
		}else{
			for( var i=0,len=this.canvasAwards.length; i<len; i++ ){
				if( _this.canvasAwards[i].id == current_id ){
					index = i;
					break;
				}
			}
		}
		return index;
	};
	
	hg_Spin.prototype.toggleEnterRetryStatus = function( bool ){
		this.lottery_startbtn[bool ? 'addClass' : 'removeClass']('enterretry');
		this.lottery_startbtn.removeClass('disabled');
	};
	
	window.hg_Spin = hg_Spin;
	
} )($);

( function(){
	
	var zhuanpan_el = $('.zhuanpan-game-box');
	if( zhuanpan_el.length ){
		var my_spin = new hg_Spin({
			element : zhuanpan_el
		});
		
		/*调用全局公用调用客户端取得用户信息后初始化游戏对象*/
		hg_ClientApi( my_spin );
	}
	
	
} )($);
	
	
});
