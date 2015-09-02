var lottery = new LotteryDisc({
	colors : ['#f5b963', '#f6c37a', '#f5b963', '#f6c37a', '#f5b963', '#f6c37a'],
	awards : ['一等奖', '二等奖', '一等奖', '二等奖', '一等奖', '二等奖'],
	outsideRadius : 145,
	insideRadius : 1,
	textRadius : 110,
	fontSize : 24,
	fontColor : '#fff',
	callback : function( index ){
		console.log( index+'-获得'+lottery.awards[ index ]+'!' );
	},
});

$(function(){
	$('.new-game-btn').click(function(){
		lottery.spin();
	});
});