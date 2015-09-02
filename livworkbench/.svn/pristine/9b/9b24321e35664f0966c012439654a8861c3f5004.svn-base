var LotteryDisc = function( options ){
	this.op = options || {};
	var op = this.op;
	this.canvas = document.getElementById( op.canvasId || 'mycanvas' );
	this.ctx = this.canvas.getContext('2d');
	this.awards = op.awards || ['一等奖','二等奖','三等奖'];
	this.colors = op.colors &&  op.colors != 'auto' ? op.colors : this.createColor();
	this.arc = 2*Math.PI / this.awards.length;
	this.startAngle = 0;

	this.spinTimeout = null;
	this.spinArcStart = 10; 
	this.spinTime = 0; 
	this.spinTimeTotal = 0;  

	this.centerX = op.centerX || this.canvas.width/2;
	this.centerY = op.centerY || this.canvas.height/2;
	this.outsideRadius = op.outsideRadius || 200;
    this.insideRadius = op.insideRadius || 125;
    this.textRadius = op.textRadius || 160;
    
    this.drawRouletteWheel();
};
LotteryDisc.prototype.createColor = function(){
	var colorArr = [];
	for(var i=0,len=this.awards.length; i<len; i++){
		var color = '#';
		for( var j=0;j<3;j++ ){
			color += parseInt( parseInt( Math.random()*255 ) ,10).toString(16);
		}
		colorArr.push( color );
	}
	return colorArr;
}; 
LotteryDisc.prototype.drawRouletteWheel = function() {
    var ctx = this.ctx,
    	canvas = this.canvas,
    	outsideRadius = this.outsideRadius,
    	textRadius = this.textRadius,
    	insideRadius = this.insideRadius;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.strokeStyle = "#eee";
    ctx.lineWidth = 1;
    ctx.font = "bold "+ (this.op.fontSize || 30) +"px sans-serif";
    for (var i = 0; i < this.awards.length; i++) {
        var angle = this.startAngle + i * this.arc;
        ctx.fillStyle = this.colors[i];
        ctx.beginPath();
        ctx.arc(this.centerX, this.centerY, outsideRadius, angle, angle + this.arc, false);
        ctx.arc(this.centerX, this.centerY, insideRadius, angle + this.arc, angle, true);
        ctx.fill();
        ctx.stroke();
        ctx.save();
//        ctx.shadowOffsetX = -1;
//        ctx.shadowOffsetY = -1;
//        ctx.shadowBlur = 0;
//        ctx.shadowColor = "rgb(220,220,220)";
        ctx.fillStyle = this.op.fontColor || '#000';
        ctx.translate(this.centerX + Math.cos(angle + this.arc / 2) * this.textRadius, this.centerY + Math.sin(angle + this.arc / 2) * this.textRadius);
        ctx.rotate(angle + this.arc / 2 + Math.PI / 2);
        var text = this.awards[i];
        ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
        ctx.restore();
    }
    this.drawArrow( ctx );
};   
LotteryDisc.prototype.drawArrow = function( ctx ){
	ctx.beginPath();
	ctx.fillStyle = '#df3e3f';
	ctx.moveTo(150, 55);
	ctx.lineTo(140, 150);
	ctx.lineTo(150, 170);
	ctx.lineTo(160, 150);
	ctx.closePath();
	ctx.fill();
	
	ctx.beginPath();
	ctx.fillStyle ='#eee';
	ctx.arc(150,150,5,0,2*Math.PI);
	ctx.closePath();
	ctx.fill();
};
LotteryDisc.prototype.spin = function() {    
	this.spinAngleStart = Math.random() * 10 + 10;    
	this.spinTime = 0;    
	this.spinTimeTotal = Math.random() * 3 + 4 * 1000;   
	this.rotateWheel();  
}
LotteryDisc.prototype.rotateWheel = function() {    
	this.spinTime += 30;    
	if(this.spinTime >= this.spinTimeTotal) {      
		this.stopRotateWheel();      
		return;    
	}    
	var spinAngle = this.spinAngleStart - this.easeOut(this.spinTime, 0, this.spinAngleStart, this.spinTimeTotal);    
	this.startAngle += (spinAngle * Math.PI / 180);
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
	ctx.save();
//	ctx.font = 'bold 30px sans-serif';    
//	var text = this.awards[index]    
//	ctx.fillText(text, 250 - ctx.measureText(text).width / 2, 250 + 10);
	ctx.restore();  
	if( this.op.callback ){
		this.op.callback( index );
	}
}
LotteryDisc.prototype.easeOut = function(t, b, c, d) {    
	var ts = (t/=d)*t;    
	var tc = ts*t;    
	return b+c*(tc + -3*ts + 3*t);  
}