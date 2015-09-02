$(function(){
if( !globalprize ) return;	
( function($){
	/*刮刮卡 class start*/
	function hg_Scratch( options ){
		hg_Lottery.call(this,options);
		this.canvas_id = 'ggk-canvas';
		this.canvas_cover = '../images/ggk/mask.png';
		this.scratch_canvasarea = this.element.find('#ggk-canvas');
		this.bindScratchEvent();
	}
	
	/*刮刮卡类 继承游戏父类*/
	extendClass( hg_Scratch, hg_Lottery );
	
	hg_Scratch.prototype.bindScratchEvent = function(){
		var _this = this,
			Etype = device && device.desktop() ? 'click' : 'touchstart';
		/*再刮一次事件*/
		this.element.on( Etype, '.ggk-game-btn', function( event ){
			_this.initLottery();
		});	
	};
	
	hg_Scratch.prototype.initLotteryCallback = function( options ){
			var _this = this,
				is_limit = options.is_limit,
				scratchnmae = options.name;
			this.toggleLotterymask( true );
			this.scratch_canvasarea.empty();
			var scratchLottery = new ScratchClass({
				id : _this.canvas_id,
				cover : _this.canvas_cover,
				coverType : 'image',
				width : 235,
				height : 100,
				pointRadius : 20,
				drawPercentCallback : function( percent ){
					_this.drawPercentCallback( percent );;
				},
				initcanvasCallback : function(){
					_this.toggleLotterymask( is_limit );
				}
			});
			scratchLottery.init( scratchnmae, 'text' );
	};
	
	hg_Scratch.prototype.drawPercentCallback = function( percent ){
		var _this = this;
			//兼容android
        var percentNum = 20,outTime = 0,is_tap = 0;
        if(navigator.userAgent.indexOf('Android') > -1){
            percentNum = 10;
            outTime = 1000;
            setTimeout(function(){_this.scratch_canvasarea.find('canvas').eq(1).remove();},500);
            is_tap = 1;
        }
        setTimeout(function(){
            if(percentNum <= Math.floor(percent) || is_tap){
            	if( !_this.lotterystarting ) return;
            	if( _this.currentAward ){
            		var info = _this.currentAward;
            		_this.lotteryResultCallback( info );
            			
            	}
            }
        },outTime);
	};
	
	window.hg_Scratch = hg_Scratch;
	
} )($);

( function(){
	
	var ggk_el = $('.ggk-game-box');
	if( ggk_el.length ){
		var my_srcatch = new hg_Scratch({
			element : ggk_el
		});
		
		/*调用全局公用调用客户端取得用户信息后初始化游戏对象*/
		hg_ClientApi( my_srcatch );
	
	}
	
} )($);
	
	
});
