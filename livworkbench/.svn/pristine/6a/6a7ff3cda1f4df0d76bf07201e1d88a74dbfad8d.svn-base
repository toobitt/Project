//刮奖构造器
function Lottery(id, cover, coverType, width, height, drawPercentCallback) {
    this.conId = id;
    this.conNode = document.getElementById(this.conId);
    this.cover = cover;
    this.coverType = coverType;
    this.background = null;
    this.backCtx = null;
    this.mask = null;
    this.maskCtx = null;
    this.lottery = null;
    this.lotteryType = 'image';
    this.width = width || 300;
    this.height = height || 100;
    this.clientRect = null;
    this.drawPercentCallback = drawPercentCallback;
}
Lottery.prototype = {
    createElement: function (tagName, attributes) {
        var ele = document.createElement(tagName);
        for (var key in attributes) {
            ele.setAttribute(key, attributes[key]);
        }
        return ele;
    },
    getTransparentPercent: function(ctx, width, height) {
        
        var imgData = ctx.getImageData(0, 0, width, height),
            pixles = imgData.data,
            transPixs = [];
        for (var i = 0, j = pixles.length; i < j; i += 4) {
            var a = pixles[i + 3];
            if (a < 128) {
                transPixs.push(i);
            }
        }
        return (transPixs.length / (pixles.length / 4) * 100).toFixed(2);
    },
    resizeCanvas: function (canvas, width, height) {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').clearRect(0, 0, width, height);
    },
    drawPoint: function (x, y) {
        this.maskCtx.beginPath();
        var radgrad = this.maskCtx.createRadialGradient(x, y, 0, x, y, 30);
        radgrad.addColorStop(0, 'rgba(0,0,0,0.6)');
        radgrad.addColorStop(1, 'rgba(255, 255, 255, 0)');
        this.maskCtx.fillStyle = radgrad;
        this.maskCtx.arc(x, y, 10, 0, Math.PI * 2, true);
        this.maskCtx.fill();
        if (this.drawPercentCallback) {
            this.drawPercentCallback.call(null, this.getTransparentPercent(this.maskCtx, this.width, this.height));
        }
        this.background.style.visibility = 'visible';//显示背景图
    },
    bindEvent: function () {
        var _this = this;
        var device = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var clickEvtName = device ? 'touchstart' : 'mousedown';
        var moveEvtName = device? 'touchmove': 'mousemove';
        if (!device) {
            var isMouseDown = false;
            document.addEventListener('mouseup', function(e) {
                isMouseDown = false;
            }, false);
        } else {
            document.addEventListener("touchmove", function(e) {
                if (isMouseDown) {
                    e.preventDefault();
                }
            }, false);
            document.addEventListener('touchend', function(e) {
                isMouseDown = false;
            }, false);
        }
        this.mask.addEventListener(clickEvtName, function (e) {
            isMouseDown = true;
            var docEle = document.documentElement;
            if (!_this.clientRect) {
                _this.clientRect = {
                    left: 0,
                    top:0
                };
            }
            var x = (device ? e.touches[0].clientX : e.clientX) - _this.clientRect.left + docEle.scrollLeft - docEle.clientLeft;
            var y = (device ? e.touches[0].clientY : e.clientY) - _this.clientRect.top + docEle.scrollTop - docEle.clientTop;
            _this.drawPoint(x, y);
        }, false);

        this.mask.addEventListener(moveEvtName, function (e) {
            if (!device && !isMouseDown) {
                return false;
            }
            var docEle = document.documentElement;
            if (!_this.clientRect) {
                _this.clientRect = {
                    left: 0,
                    top:0
                };
            }
            var x = (device ? e.touches[0].clientX : e.clientX) - _this.clientRect.left + docEle.scrollLeft - docEle.clientLeft;
            var y = (device ? e.touches[0].clientY : e.clientY) - _this.clientRect.top + docEle.scrollTop - docEle.clientTop;
            _this.drawPoint(x, y);
        }, false);
    },
    drawLottery: function () {
        this.background = this.background || this.createElement('canvas',{
            //style: 'position: absolute;top:0;left:0'
        });
        this.mask = this.mask || this.createElement('canvas', {
           // style: 'position: absolute;top:0;left:0'
        });

        if (!this.conNode.innerHTML.replace(/[\w\W]| /g, '')) {
            this.conNode.appendChild(this.background);
            this.conNode.appendChild(this.mask);
            this.clientRect = this.conNode ? this.conNode.getBoundingClientRect() : null;
            this.bindEvent();
        }

        this.backCtx = this.backCtx || this.background.getContext('2d');
        this.maskCtx = this.maskCtx || this.mask.getContext('2d');

        if (this.lotteryType == 'image') {
            var image = new Image(),
                _this = this;
            image.onload = function () {
                _this.width = this.width;
                _this.height = this.height;
                _this.resizeCanvas(_this.background, this.width, this.height);
                _this.backCtx.drawImage(this, 0, 0);
                _this.drawMask();
            }
            image.src = this.lottery;
        } else if (this.lotteryType == 'text') {
            this.width = this.width;
            this.height = this.height;
            this.resizeCanvas(this.background, this.width, this.height);
            this.backCtx.save();
            this.backCtx.fillStyle = '#FFF';
            this.backCtx.fillRect(0, 0, this.width, this.height);
            this.backCtx.restore();
            this.backCtx.save();
            var fontSize = 30;
            this.backCtx.font = 'Bold ' + fontSize + 'px Arial';
            this.backCtx.textAlign = 'center';
            this.backCtx.fillStyle = '#F60';
            this.backCtx.fillText(this.lottery, this.width / 2, this.height / 2 + fontSize / 2);
            this.backCtx.restore();
            this.drawMask();
        }
    },
    drawMask: function() {
        this.resizeCanvas(this.mask, this.width, this.height);
        if (this.coverType == 'color') {
            this.maskCtx.fillStyle = this.cover;
            this.maskCtx.fillRect(0, 0, this.width, this.height);
            this.maskCtx.globalCompositeOperation = 'destination-out';
        } else if (this.coverType == 'image'){
            var image = new Image(),
                _this = this;
            image.onload = function () {
                _this.maskCtx.drawImage(this, 0, 0);
                _this.maskCtx.globalCompositeOperation = 'destination-out';
            }
            image.src = this.cover;
        }
    },
    init: function (lottery, lotteryType) {
        this.lottery = lottery;
        this.lotteryType = lotteryType || 'image';
        this.drawLottery();
    }
};//prototype

$(function(){
    var userName = $('#userName'),
        userTel = $('#userTel'),
        info = $('#info'),
        iMsg = $('#msg'),
        loading = $('#loading'),
        reg_tel = /^\d{8}$/,
        reg_name = /^[\u4e00-\u9fa5a-zA-Z]*$/,
        msg = {},
        dialog = $('#dialog'),
        swipe = $('#swipe'),
        timeouts = null;

    //提示框
    var msgBox = function(msg){
        iMsg.show().find('p').text(msg.msg);
        iMsg.css({'top':msg.id.offset().top + 50,'left':msg.id.offset().left});
        document.body.scrollTop = msg.id.offset().top;
        clearTimeout(timeouts)
        timeouts = setTimeout(function(){
            iMsg.hide();
        },2000)
        return false;
    };
    msg.keyD = (function(){
        $('input,textarea').keydown(function(event) {
           iMsg.hide();
           clearTimeout(timeouts);
        });
    })();

    var win = {
        open : function(id){
            id.show().addClass('in');
        },
        close : function(id){
            id.addClass('out');
            setTimeout(function(){
                id.removeClass('in');
                id.removeClass('out');
            },600);
        },
        dialog : function(text){
            dialog.show().find('span').text(text);
        }
    };
    //加载框
    var load = {
        show : function(s){
            setTimeout(function(){loading.show().animate({opacity:1},500,'ease-out');},100);
            if(s){
             setTimeout(function(){load.hide()},1000);
            }
        },
        hide : function(){
            loading.animate({opacity:0},800,'ease-out',function(){
                $(this).hide();
            });
        }
    };

    var slide_page = function(index){//页面切换
        var windowWidth = document.body.clientWidth;
            windowWidth -= windowWidth * (index+1);
            clearTimeout(timeouts);
            $('#swipe > div').eq(index).show().addClass('active');
            $('#swipe > div').eq(index).siblings().removeClass('active')  
            timeouts = setTimeout(function(){
                $('#swipe > div').eq(index).siblings().hide();
            },500);

            swipe.css({
                'transform':'translate3d('+windowWidth+'px, 0px, 0px)',
                '-webkit-transform':'translate3d('+windowWidth+'px, 0px, 0px)',
                '-ms-transform':'translate3d('+windowWidth+'px, 0px, 0px)'
            });
            setTimeout(scrollTo,0,0,0);
        };

    //表单验证
    $('div.card').on('click','a',function(){
        if('' === userName.val()){
            msg.msg = '姓名不能为空';
            msg.id = userName;
        }else if(!reg_name.test(userName.val())){
            msg.msg = '姓名只能是中文或英文';
            msg.id = userName;
        }else if('' === userTel.val()){
            msg.msg = '電話不能为空';
            msg.id = userTel;
        }else if(!reg_tel.test(userTel.val())){
            msg.msg = '请输入8位有效電話号码';
            msg.id = userTel;
        }else if('' === info.val()){
            msg.msg = '郵寄地址不能为空';
            msg.id = info;
        }else{
            $('#sName').text(userName.val());
            $('#sTel').text(userTel.val());
            $('#sAddress').text(info.val());
            slide_page(2);
            return true;
        }
        msgBox(msg);
    });

    //确认
    $('div.confirm').on('click','a',function(){
        var i = $(this).index();
        if(0 === i){
            //  $('#subForm').submit()
            //  or
            //  $.ajax({});
            win.open($('#infoWindow'));//打开分享框
   
        }else{
            slide_page(1);//表单窗口
        }
    });

    //分享
    $('#infoWindow a').click(function(){
        var _timeout;
        if('share' === $(this).data('action')){
            $('div.share').removeClass('hide').click(function(event) {
                $(this).addClass('hide');
                clearTimeout(_timeout);
            });
            _timeout = setTimeout(function(){
               $('div.share').addClass('hide');
                clearTimeout(_timeout);
            },2000);
        }else{
            win.close($('#infoWindow'))
        }
    });

    var text = function(text){return '恭喜！您赢得惠'+text+'一張!'};
    var canvasBox = $('div.list div');
    //刮奖
    $.each(canvasBox,function(index,elem){

         var lotterys = ['100','50','hannah','hannah','hannah'],//随机奖品对应图片名称
             loy = parseInt(5*Math.random());

            $(this).attr('id','lot_'+index+'_'+lotterys[loy]);
            var _this = $(this);

            //var lottery = new Lottery('lot_'+index+'_'+lotterys[loy], 'images/cover.png', 'image', 75, 75,function(percent){ //服务器访问
            var lottery = new Lottery('lot_'+index+'_'+lotterys[loy], 'gray', 'color', 75, 75,function(percent){//本地访问
            //只能刮奖一次
            canvasBox.eq(index).siblings().find('canvas').remove();
            canvasBox.eq(index).parent().siblings().find('canvas').remove();

            //兼容android
            var percentNum = 60,outTime = 0,is_tap = 0;
            if(navigator.userAgent.indexOf('Android') > -1){
                percentNum = 10;
                outTime = 1000;
                setTimeout(function(){_this.find('canvas').eq(1).remove()},500);
                is_tap = 1;
            }
            setTimeout(function(){
                if(percentNum <= Math.floor(percent) || is_tap){
                    var gift = canvasBox.eq(index).attr('id').split('_')[2];//获取当前选中的奖品图片名称
                    if(gift == '100'){    
                        win.dialog(text('惠康HK$100現金券'));
                    }if(gift == '50'){    
                        win.dialog(text('惠康HK50現金券'));
                    }if(gift == 'hannah'){    
                        win.dialog(text('hannah'));
                    }if(gift == 'nbs'){    
                        win.dialog(text('nbs'));
                    }if(gift == 'zrm'){    
                        win.dialog(text('zrm'));
                    } 
                }
            },outTime);
         });
        lottery.init('images/'+(lotterys[loy])+'.jpg', 'image');
        load.hide();
    });
    dialog.on('click','a',function(){
        dialog.hide();
        slide_page(1);//填写表单
    });
});
