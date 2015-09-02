console.log( navigator.userAgent )
$(function(){
(function(){
	var allAwards = [],
		currentAward = '',
		totleTimes = 1,
		currentTime = 1;
	function getTotleTimes(){
		var url = './php/awards.php?a=getTotleTimes';
		$.getJSON(url, function(json){
			totleTimes = json;
		});
	}
	function getAllAwards(){
		var url = './php/awards.php?a=getAllAwards';
		$.getJSON(url, function(json){
			allAwards = json;
		});
	}
	
	function newGame(){
		var url = './php/awards.php?a=currentAwards';
		$.getJSON(url, function(json){
			currentAward = allAwards[json];
			$('#ggk-canvas').empty();
			var lottery = new Lottery({
				id : 'ggk-canvas',
				cover : './images/mask.png',
				coverType : 'image',
				width : 235,
				height : 100,
				pointRadius : 20,
				drawPercentCallback : function( percent ){
					var _this = this;
					//兼容android
		            var percentNum = 60,outTime = 0,is_tap = 0;
		            if(navigator.userAgent.indexOf('Android') > -1){
		                percentNum = 10;
		                outTime = 1000;
		                setTimeout(function(){$(_this.conid).find('canvas').eq(1).remove()},500);
		                is_tap = 1;
		            }
		            setTimeout(function(){
		                if(percentNum <= Math.floor(percent) || is_tap){
		                	if( currentAward.hasAwards ){	//中奖 
								$('.ggk-awards-pop').find('.awards-name').text( currentAward.awards );
								$('.ggk-awards-pop').show();
							}else{
								
							}
		                }
		            },outTime);
				},
			});
			lottery.init( currentAward.awards ,'text' );
		});
	}
	function start(){
		getTotleTimes();
		getAllAwards();
		var time = setInterval(function(){
			if( allAwards.length ){
				clearInterval( time );
				newGame();
			}
		},10);
	}
	start();
	
	$('.lottery-awards-pop').click(function(){
		$(this).hide();
	});
	$('.ggk-game-btn').click(function(){
		newGame();
		currentTime++;
		if( currentTime >= totleTimes ){
			$(this).hide();
		}
	});
})();
});

