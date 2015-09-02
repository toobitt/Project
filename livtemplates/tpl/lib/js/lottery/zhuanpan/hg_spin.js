var hg_SpinClass = function( options ){
	this.op = options || {};
	var op = this.op;
	this.canvas = document.getElementById( op.canvasId || 'mycanvas' );
	this.ctx = this.canvas.getContext('2d');
	this.arc = 2*Math.PI / this.op.awardsConfig.length;
	this.startAngle = 0;
	this.spinTimeout = null;
	this.centerX = op.centerX || this.canvas.width/2;
	this.centerY = op.centerY || this.canvas.height/2;
	this.outsideRadius = op.outsideRadius || 200;
    this.insideRadius = op.insideRadius || 145;
    this.textRadius = op.textRadius || 160;
    this.timeInterval = 30;		//时间间隔
    
    this.drawRouletteWheel();
    
    this.eachRadian = 2*Math.PI / this.op.awardsConfig.length;
    this.eachDegree = 360 / this.op.awardsConfig.length;
};

hg_SpinClass.prototype.setUesrAward = function( i ){
	this.userAwardsIndex = i;
	this.angelOffset = ( this.eachDegree - 20 );	//设一个随机的偏移量
};
hg_SpinClass.prototype.drawRouletteWheel = function() {
    var ctx = this.ctx,
    	canvas = this.canvas;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    this.drawEachAward(0);
};   
hg_SpinClass.prototype.drawEachAward = function(i){
	if( i>=this.op.awardsConfig.length ){
		return;
	}
	var ctx = this.ctx,
		award = this.op.awardsConfig[i],
   		angle = this.startAngle + ( i * this.arc - 0.5*Math.PI ),
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
hg_SpinClass.prototype.spin = function() {
	var _this = this; 
	this.spinAngleStart = 20;  
	this.startAngle = Math.PI/180 * ( 100 - _this.userAwardsIndex * _this.eachDegree - _this.angelOffset);
	// setTimeout( function(){
		// _this.startAngle = Math.PI/180 * ( 100 - _this.userAwardsIndex * _this.eachDegree - _this.angelOffset);
		// _this.close = true;
	// },
	// 2000 );
	
	this.rotateWheel();
}
hg_SpinClass.prototype.rotateWheel = function() { 
	var _this = this;
	if( this.spinAngleStart <= 0.02 ){
		this.finish();
		return;
	}
	this.spinAngleStart = this.spinAngleStart*0.98;
	// if( this.close ){
		// this.spinAngleStart = this.spinAngleStart*0.98;
	// }
	this.startAngle += (this.spinAngleStart * Math.PI / 180);
	this.drawRouletteWheel();
	this.spinTimeout = setTimeout(function(){
		_this.rotateWheel();
	}, this.timeInterval); 
}
hg_SpinClass.prototype.finish = function(){
	clearTimeout(this.spinTimeout);
	if( this.op.callback ){
		this.op.callback();
	}
};