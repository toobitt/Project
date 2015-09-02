$(function(){
	var MyLottery = function( dom ){
		this.el = $(dom);
		this.awardsUrl = './php/awards.php?a=getAllAwards';			//所有奖项
		this.userAwardUrl = './php/awards.php?a=currentAwards';		//中奖信息
		this.awards = null;
		this.canvasAwards = [];
		this.userAward = null;
		this.init();
		this.bindEvent();
	};
	
	MyLottery.prototype.init = function(){
		var _this = this;
		$.getJSON(this.awardsUrl, function( json ){
			_this.awards = json;
			_this.initCanvasAwards();
			_this.initCanvas();
		});
		$.getJSON(this.userAwardUrl, function( json ){
			_this.userAward = json;
		});
	};
	MyLottery.prototype.bindEvent = function(){
		var _this = this;
		this.el.find('.zhuanpan-start-btn').click(function(){
			var canvasIndex;
			if( !_this.canvasAwards.flag ){
				canvasIndex = parseInt( Math.random() * ( _this.awards.length-1 ) ) * 2 + 1;
			}else{
				for( var i=0,len=_this.canvasAwards.length; i<len; i++ ){
					if( _this.canvasAwards[i].id == _this.userAward.id ){
						canvasIndex = i;
						break;
					}
				}
			}
			console.log( _this.userAward, canvasIndex )
			_this.lottery.setUesrAward( canvasIndex );
			_this.lottery.spin();
		});
	};
	MyLottery.prototype.initCanvasAwards = function(){
		this.canvasAwards = [];
		for( var i=0,len=this.awards.length; i<len; i++ ){
			this.canvasAwards.push({
				id : this.awards[i].id,
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : this.awards[i].cname,
				bgColor : '#ecfeff',
			});
			this.canvasAwards.push({
				awardsType : null,
				bgColor : '#f7dc27',
			});
		}
	};
	MyLottery.prototype.initCanvas = function(){
		this.lottery = new LotteryDisc({
			centerX : 130,
			centerY : 157,
			canvasId : 'zhuanpan-canvas',
			outsideRadius : 130,
			insideRadius : 50,
			textRadius : 110,
			callback : function( index ){
				console.log(index);
//				var lottery_pop = $('.zhuanpan-awards-pop');
//				lottery_pop.show();
//				var timer = setTimeout(function(){
//					lottery_pop.hide();
//				},2000);
			},
			awardsConfig : this.canvasAwards
		});
	};
	var myLottery = new MyLottery('.zhuanpan-wrap');
});