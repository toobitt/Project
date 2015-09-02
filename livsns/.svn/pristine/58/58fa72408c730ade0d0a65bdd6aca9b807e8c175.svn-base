(function() {
    debugger;
    console.log("fingerTracker");
    var css2 = '.ball{ background-color: #0da0cc;border-radius: 50px;height: 50px;width: 50px;position: absolute;z-index: 999999; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

    style.type = 'text/css';
    if (style.styleSheet){
        style.styleSheet.cssText = css2;
    } else {
        style.appendChild(document.createTextNode(css2));
    }

    head.appendChild(style);


    var ball = document.createElement("div");
    ball.classList.add("ball");

    var CSS = {
        translate3d : function(x, y, z, t) {
            t = (typeof t === "undefined") ? 0 : t; //defaults to 0
            var tr = '-webkit-transform: translate3d(' + x + 'px, ' + y + 'px, ' + z + 'px); -webkit-transition: ' + t + 'ms;' +
                '-moz-transform: translate3d(' + x + 'px, ' + y + 'px, ' + z + 'px); -moz-transition: ' + t + 'ms;' +
                '-ms-transform: translate3d(' + x + 'px, ' + y + 'px, ' + z + 'px); -ms-transition: ' + t + 'ms;' +
                '-o-transform: translate(' + x + 'px, ' + y + 'px); -o-transition: ' + t + 'ms;' +
                'transform: translate3d(' + x + 'px, ' + y + 'px, ' + z + 'px); transition: ' + t + 'ms;';

            return tr;
        }
    };

    document.body.addEventListener('touchstart',function(e, r, t,y)
    {
        //ball.position(e.touches[0].clientX - 25, e.touches[0].clientY - 25);
        ball.setAttribute('style', CSS.translate3d(e.touches[0].clientX - 25, e.touches[0].clientY - 25, 0));
        ball.classList.toggle("visible");
        //target is a reference to an $altNav element here, e is the event object, go mad
    },false);

    document.body.addEventListener('touchend',function(e, r, t,y)
    {
        ball.classList.toggle("visible");
        //target is a reference to an $altNav element here, e is the event object, go mad
    },false);

    document.body.addEventListener('touchmove',function(e)
    {
        var xt = e.touches[0];
        ball.setAttribute('style', CSS.translate3d(xt.clientX - 25, xt.clientY - 25, 0));
    });

});