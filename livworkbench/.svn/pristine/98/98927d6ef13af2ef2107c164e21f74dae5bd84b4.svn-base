var defaultOptions = {
		awards : ["一等奖", "二等奖", "三等奖"],
		outsideRadius : 200,
		textRadius : 140,
		insideRadius : 50,
};
var options = $.extend(defaultOptions,$.lottery);

var startAngle = 0;
var arc = Math.PI / (options.awards.length/2);
var spinTimeout = null;

var spinArcStart = 10;
var spinTime = 0;
var spinTimeTotal = 0;


function draw() {
	drawRouletteWheel();
}

var canvas = document.getElementById("canvas");
var ctx = canvas.getContext("2d");
function drawRouletteWheel() {
	if (canvas.getContext) {
		ctx.clearRect(0, 0, 500, 500);
		
		ctx.font = 'bold 20px sans-serif';
		var len = options.awards.length;
		for (var i = 0; i < len; i++) {
			//绘制奖项
			var angle = startAngle + i * arc;
			ctx.fillStyle = ['#f2f2f2','#e6e6e6'][i%2];
			ctx.beginPath();
			ctx.arc(250, 250, options.outsideRadius, angle, angle + arc, false);	//大圆
			ctx.arc(250, 250, options.insideRadius, angle + arc, angle, true);		//小圆儿
			ctx.strokeStyle = "#e24f2b";
			ctx.lineWidth = 2;
			ctx.stroke();	//边框
			ctx.fill();

			ctx.save();
			ctx.shadowOffsetX = -1;
			ctx.shadowOffsetY = -1;
			ctx.shadowBlur = 0;
			ctx.fillStyle = "#e24f2b";
			ctx.translate(250 + Math.cos(angle + arc / 2) * options.textRadius, 250 + Math.sin(angle + arc / 2) * options.textRadius);
			ctx.rotate(angle + arc / 2 + Math.PI / 2);
			var text = options.awards[i];
			ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
			ctx.restore();
			
			ctx.save();
			ctx.arc(250, 250, options.insideRadius, 0, 1.5*Math.PI);
		}

		//Arrow
		ctx.fillStyle = "black";
		ctx.beginPath();
		ctx.moveTo(250 - 4, 250 - (options.outsideRadius + 5));
		ctx.lineTo(250 + 4, 250 - (options.outsideRadius + 5));
		ctx.lineTo(250 + 4, 250 - (options.outsideRadius - 5));
		ctx.lineTo(250 + 9, 250 - (options.outsideRadius - 5));
		ctx.lineTo(250 + 0, 250 - (options.outsideRadius - 13));
		ctx.lineTo(250 - 9, 250 - (options.outsideRadius - 5));
		ctx.lineTo(250 - 4, 250 - (options.outsideRadius - 5));
		ctx.lineTo(250 - 4, 250 - (options.outsideRadius + 5));
		ctx.fill();
	}
	canvas.addEventListener('mousedown', eventDown);
}

function eventDown(e) {
    e.preventDefault();
    spin();
}

function spin() {
	spinAngleStart = Math.random() * 10 + 10;//10~20的随机数
	spinTime = 0;
	spinTimeTotal = Math.random() * 3 + 2 * 1000;//2000~2003
	rotateWheel();
}

function rotateWheel() {
	spinTime += 30;
	if (spinTime >= spinTimeTotal) {
		stopRotateWheel();
		return;
	}
	var spinAngle = spinAngleStart - easeOut(spinTime, 0, spinAngleStart, spinTimeTotal);
	startAngle += (spinAngle * Math.PI / 180);
	drawRouletteWheel();
	spinTimeout = setTimeout('rotateWheel()', 30);
}

function stopRotateWheel() {
	clearTimeout(spinTimeout);
    var degrees = startAngle * 180 / Math.PI + 90;
    var arcd = arc * 180 / Math.PI;
    var index = Math.floor((360 - degrees % 360) / arcd);
    ctx.save();

    ctx.clearRect(0, 0, 20, 20);
    ctx.font="20px Verdana";
    var gradient=ctx.createLinearGradient(0,0,canvas.width,0);
    gradient.addColorStop("0","red");
	gradient.addColorStop("1.0","red");
	ctx.fillStyle=gradient;
	ctx.fillText("恭喜!",250-ctx.measureText("恭喜!").width / 2, 250 + 10);
    
	ctx.restore();
}

function easeOut(spinTime, b, spinAngleStart, spinTimeTotal) {
	console.log(spinAngleStart);
	var ts = (spinTime /= spinTimeTotal) * spinTime;
	var tc = ts * spinTime;
	return b + spinAngleStart * (tc + -3 * ts + 3 * spinTime);
}

draw();