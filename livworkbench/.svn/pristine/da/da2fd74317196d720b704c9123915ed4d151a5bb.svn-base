var LotteryDisc = function( options ){
	this.op = options || {};
	var op = this.op;
	this.canvas = document.getElementById( op.canvasId || 'mycanvas' );
	this.ctx = this.canvas.getContext('2d');
	this.arc = 2*Math.PI / this.op.awardsConfig.length;
	this.startAngle = 0;

	this.spinTimeout = null;
	this.spinArcStart = 10; 

	this.centerX = op.centerX || this.canvas.width/2;
	this.centerY = op.centerY || this.canvas.height/2;
	this.outsideRadius = op.outsideRadius || 200;
    this.insideRadius = op.insideRadius || 145;
    this.textRadius = op.textRadius || 160;
    
    
    this.drawRouletteWheel();
};

LotteryDisc.prototype.getRandomNum = function( Min, Max ){
	var Range = Max - Min;   
	var Rand = Math.random();   
	return(Min + Math.round(Rand * Range));   
},

LotteryDisc.prototype.drawRouletteWheel = function() {
    var ctx = this.ctx,
    	canvas = this.canvas;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    this.drawEachAward(0);
    this.drawArrow( ctx );
};   
LotteryDisc.prototype.drawEachAward = function(i){
	if( i>=this.op.awardsConfig.length ){
		return;
	}
	var ctx = this.ctx,
		award = this.op.awardsConfig[i],
   		angle = this.startAngle + i * this.arc,
		outsideRadius = this.outsideRadius,
		textRadius = this.textRadius,
		insideRadius = this.insideRadius;
    /** 绘制背景 */
    ctx.fillStyle = award.bgColor;
    ctx.beginPath();
    ctx.arc(this.centerX, this.centerY, outsideRadius, angle, angle + this.arc, false);
    ctx.arc(this.centerX, this.centerY, insideRadius, angle + this.arc, angle, true);
    ctx.fill();
    /** 绘制奖项 */
    ctx.font = "bold 18px sans-serif";
    ctx.fillStyle = award.awardsStyle || 'red';
    ctx.save();
	var _x = this.centerX + Math.cos(angle + this.arc / 2) * this.textRadius,
		_y = this.centerY + Math.sin(angle + this.arc / 2) * this.textRadius;
	ctx.translate(_x, _y);
	ctx.rotate(angle + this.arc / 2 + Math.PI / 2);
    if( award.awardsType ){
    	if( award.awardsType == 'text' ){
    		var text = award.awardsVal;
    		for(var flag=0,textLen=text.length; flag<textLen; flag++){
    			ctx.fillText(text[flag], -ctx.measureText(text[flag]).width / 2, 16*flag);
    		}
    	}else{
   		var img = new Image();
    	img.onload = function () {
    		var w = $(this).width(),
    			h = $(this).height();
    		ctx.drawImage(img,_x-w/2,_y-h/2);
    	}
    	img.src = './images/award' + i + '.png' ;
	    	// console.log( random, '123' );
    		// var img=document.getElementById("award" + random);
    		// ctx.drawImage(img,-15,0);
    	}
    }
    ctx.restore();
    /** 绘制end*/
    
    
    this.drawEachAward( ++i );
};
LotteryDisc.prototype.drawArrow = function( ctx ){
	// var image = new Image(),
	    // _this = this;
	// image.onload = function () {
	    // _this.ctx.drawImage(this, 110, 0);
	    // _this.ctx.globalCompositeOperation = 'destination-out';
	// }
	// image.src = './images/arrow.png';
	// var img=document.getElementById("canvas-arraw");
	// this.ctx.drawImage(img,110,0);
};
LotteryDisc.prototype.spin = function() {    
//	this.spinAngleStart = Math.random() * 10 + 10;    
	this.spinAngleStart = 20;
	this.spinTime = 0;    
	this.spinTimeTotal = Math.random() * 3 + 4 * 1000;   
	this.rotateWheel();  
}
LotteryDisc.prototype.rotateWheel = function() { 
	this.spinTime += 30;   
//	if(this.spinTime >= this.spinTimeTotal) {      
//		this.stopRotateWheel();      
//		return;    
//	}
	if( this.spinAngleStart <= 0.1 ){
		this.stopRotateWheel();      
		return;
	}
//	var spinAngle = this.spinAngleStart - this.easeOut(this.spinTime, 0, this.spinAngleStart, this.spinTimeTotal);  
	this.spinAngleStart = this.spinAngleStart*0.98;
	this.startAngle += (this.spinAngleStart * Math.PI / 180);
	this.drawRouletteWheel();
	var _this = this;
	this.spinTimeout = setTimeout(function(){
		_this.rotateWheel();
	}, 30); 
}    
LotteryDisc.prototype.stopRotateWheel = function() {
	var ctx = this.ctx;
	clearTimeout(this.spinTimeout);    
	var degrees = this.startAngle * 180 / Math.PI + 90;    
	var arcd = this.arc * 180 / Math.PI;    
	
	var index = Math.floor((360 - degrees % 360) / arcd);    
	console.log(  );
	if( this.op.callback ){
		this.op.callback( index );
	}
}
LotteryDisc.prototype.easeOut = function(t, b, c, d) {    
	var ts = (t/=d)*t;    
	var tc = ts*t;    
	return b+c*(tc + -3*ts + 3*t);  
}