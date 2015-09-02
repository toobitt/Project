$(function(){
var lottery = new LotteryDisc({
	centerX : 130,
	centerY : 157,
	canvasId : 'zhuanpan-canvas',
	outsideRadius : 130,
	insideRadius : 50,
	textRadius : 110,
	callback : function( index ){
		var lottery_pop = $('.zhuanpan-awards-pop');
		lottery_pop.show();
		var timer = setTimeout(function(){
			lottery_pop.hide();
		},2000);
	},
	awardsConfig : [
			{
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : '一等奖',
				bgColor : '#ecfeff',
			},
			{
				awardsType : null,
				bgColor : '#f7dc27',
			},
			{
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : ' 二等奖',
				bgColor : '#ecfeff'
			},
			{
				awardsType : null,
				bgColor : '#f7dc27',
			},
			{
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : '再玩一次',
				bgColor : '#ecfeff'
			},
			{
				awardsType : null,
				bgColor : '#f7dc27',
			},
			{
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : '三等奖',
				bgColor : '#ecfeff'
			},
			{
				awardsType : null,
				bgColor : '#f7dc27',
			},
			{
				awardsType : 'text',
				awardsStyle : '#d56e0c',
				awardsVal : '再玩一次',
				bgColor : '#ecfeff'
			},
			{
				awardsType : null,
				bgColor : '#f7dc27',
			}
	                
	]
});


	$('.zhuanpan-start-btn').click(function(){
		lottery.spin();
	});
});