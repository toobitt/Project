(function(bodyStyle) {
    bodyStyle.mozUserSelect = 'none';
    bodyStyle.webkitUserSelect = 'none';

    var img = new Image();
    var canvas = document.getElementById("ggk-canvas");

    img.addEventListener('load', function(e) {
        var ctx;
        var w = 235,
            h = 100;
        var offsetX = canvas.offsetLeft,
            offsetY = canvas.offsetTop;
        var mousedown = false;

        function layer(ctx) {
            ctx.fillStyle = '#ddd';
            ctx.fillRect(0, 0, w, h);
            ctx.font = "Bold 30px arial";
            ctx.textAlign = "center";
            ctx.fillStyle = '#9f9f9d';
            ctx.fillText( '刮奖区',120,64 );
        }

        function eventDown(e) {
            e.preventDefault();
            mousedown = true;
        }

        function eventUp(e) {
            e.preventDefault();
            mousedown = false;
        }

        function eventMove(e) {
            e.preventDefault();
            if (mousedown) {
                if (e.changedTouches) {
                    e = e.changedTouches[e.changedTouches.length - 1];
                }
                var x = (e.clientX + document.body.scrollLeft || e.pageX) - offsetX || 0,
                    y = (e.clientY + document.body.scrollTop || e.pageY) - offsetY || 0;
                with(ctx) {
                    beginPath();
                    arc(x, y, 20, 0, Math.PI * 2);
                    fill();
                }
            }
        }

        canvas.width = w;
        canvas.height = h;
        ctx = canvas.getContext('2d');
        ctx.fillStyle = 'transparent';
        ctx.fillRect(0, 0, w, h);
		layer( ctx );
        ctx.globalCompositeOperation = 'destination-out';

        canvas.addEventListener('touchstart', eventDown);
        canvas.addEventListener('touchend', eventUp);
        canvas.addEventListener('touchmove', eventMove);
        canvas.addEventListener('mousedown', eventDown);
        canvas.addEventListener('mouseup', eventUp);
        canvas.addEventListener('mousemove', eventMove);
    });
    img.src = 'bg.png';
})(document.body.style);
