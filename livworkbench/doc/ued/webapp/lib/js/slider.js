/*图片轮播*/
;(function(window,undefined) {
        /*
         * - 'imgInit' {Number}: (可选, 默认:2)初始加载几张图片
         * - 'autoPlay' {Boolean}: (可选, 默认:true)是否自动播放
         * - 'showDot' {Boolean}: (可选, 默认:true)是否展示页码
         * - 'slideEnd' {Function}: (可选)页面切换完成(滑动完成)时执行的函数,参数为滑动后的page页码
         */
        var slider = function(id, opt) {
            var me = this,
            	opt = opt || {};
            me.data = {
                dom: document.getElementById(id.replace(/^#/,'')),
                index: opt.index || 0,
                imgInit: opt.imgInit || 2,
                autoPlay: opt.autoPlay || true,
                showDot: opt.showDot !== undefined ? opt.showDot : true,
                slideEnd: opt.slideEnd || null,
                _needPlay: false,
                _direction: 1
            };
            me.init();
            me.initEvent();
            me.start();
        };
        slider.prototype = {
            constructor: slider,
            init: function() {
                var me = this,
                        o = me.data;
                o.dom.className += ' slider';
                var width = o.dom.offsetWidth,
                    height = o.dom.offsetHeight,
                    items = o.dom.children,
                    wheel = document.createElement('div'),
                    dotContainer = wheel.cloneNode(),
                    dot = document.createElement('b'),
                    lazyImgs = [],
                    i = 0, j, img, len = items.length;
                for(; j = items[0]; i++){
                    j.className += ' slider-item';
                    j.style.cssText += 'width:'+ width + 'px;position:absolute;-webkit-transform:translate3d(' + i * width + 'px,0,0);-ms-transform:translate3d(' + i * width + 'px,0,0);z-index:' + (900 - i);
                    wheel.appendChild(j);
                    dotContainer.appendChild(dot.cloneNode());
                    img = j.getElementsByTagName('img')[0];
                    if(i < o.imgInit) {
                        img && !img.src && img.getAttribute('lazyload') && (img.src = img.getAttribute('lazyload'));
                    } else {
                        lazyImgs.push(img);
                    }
                }
                wheel.style.width = width * len + 'px';
                wheel.style.height = height + 'px';
                dotContainer.className = 'slider-dots';
                o.showDot || (dotContainer.style.display = 'none');
                o.dom.appendChild(wheel);
                o.dom.appendChild(dotContainer);
                o.wheel = wheel;
                o.items = wheel.children;
                o.length = o.items.length;
                o.dots = dotContainer.children;
                o.dots[o.index].id = 'slider-dot-select';
                o.width = width;
                o.height = height;
                o.lazyImgs = lazyImgs;
            },
            initEvent: function() {
                var me =this,
                        o = me.data,
                        wheel = o.wheel;
                wheel.addEventListener('touchstart', function(e) {
                    o.pageX = e.touches[0].pageX;
                    o.pageY = e.touches[0].pageY;
                    o.S = false;      //isScrolling
                    o.T = false;      //isTested
                    o.X = 0;          //horizontal moved
                    o.wheel.style.webkitTransitionDuration = '0ms';
                    o.wheel.style.msTransitionDuration = '0ms';
                });
                wheel.addEventListener('touchmove', function(e) {
                    var X = o.X = e.touches[0].pageX - o.pageX;
                    if(!o.T) {
                        var S = Math.abs(X) < Math.abs(e.touches[0].pageY - o.pageY);
                        S || clearTimeout(o.play);
                        o.T = true;
                        o.S = S;
                    }
                    if(!o.S) {
                        e.stopPropagation();
                        e.preventDefault();
                        o.wheel.style.webkitTransform = 'translate3d(' + (X - o.index * o.width) + 'px,0,0)';
                        o.wheel.style.msTransform = 'translate3d(' + (X - o.index * o.width) + 'px,0,0)';
                    }
                });
                var touchEnd = function() {
                    o.S || me._slide(o.index + (o.X <= -20 ? Math.ceil(-o.X / o.width) : (o.X > 20) ? -Math.ceil(o.X / o.width) : 0));
                };
                var transitionEnd = ['webkitTransitionEnd','MSTransitionEnd'];
                wheel.addEventListener('touchend', touchEnd);
                wheel.addEventListener('touchcancel', touchEnd);
                for( var i = 0; i < transitionEnd.length; i++ ){
                	wheel.addEventListener(transitionEnd[i], function() {
                        if(o.showDot) {
                            document.getElementById('slider-dot-select').id = '';
                            o.dots[o.index].id = 'slider-dot-select';
                        }
                        if(o.lazyImgs.length){
                            var j = o.lazyImgs.shift();
                            j && (j.src = j.getAttribute('lazyload'));
                        }
                        me._setTimeout();
                        me.slideEnd && me.slideEnd.apply(me);
                    });
                }
            },
            // 轮播位置判断
            _slide:function(index, auto) {
                var me = this,
                        o = me.data,
                        length = o.length;
                if(-1 < index && index < length) {
                    me._move(index);
                } else if(index >= length) {
                    me._move(length - (auto ? 2 : 1));
                    o._direction = -1;
                } else {
                    me._move(auto ? 1 : 0);
                    o._direction =  1;
                }
                return me;
            },

            // 轮播方法
            _move:function(index) {
                var o = this.data;
                if(o.lazyImgs.length) {
                    var j = o.items[index].getElementsByTagName('img')[0];
                    j && !j.src && (j.src = j.getAttribute('lazyload'));
                }
                o.index = index;
                o.wheel.style.cssText += '-webkit-transition:400ms;-ms-transition:400ms;-webkit-transform:translate3d(-' + index * o.width + 'px,0,0);-ms-transform:translate3d(-' + index * o.width + 'px,0,0);';
            },
            /**
             * 设置自动播放
             */
            _setTimeout:function() {
                var me = this,
                        o = me.data;
                if(!o._needPlay || !o.autoPlay) return me;
                clearTimeout(o.play);
                o.play = setTimeout(function() {
                    me._slide.call(me, o.index + o._direction, true);
                }, 3000);
                return me;
            },

            // 重设容器及子元素宽度
            _resize:function() {
                var me = this,
                        o = me.data,
                        width = o.dom.offsetWidth,
                        length = o.length,
                        items = o.items;
                if(!width) return me;
                o.width = width;
                clearTimeout(o.play);
                for(var i = 0; i < length; i++) items[i].style.cssText += 'width:' + width + 'px;-webkit-transform:translate3d(' + i * width + 'px,0,0);-ms-transform:translate3d(' + i * width + 'px,0,0);';
                o.wheel.style.removeProperty('-webkit-transition');
                o.wheel.style.removeProperty('-ms-transition');
                o.wheel.style.cssText += 'width:' + width * length + 'px;-webkit-transform:translate3d(-' + o.index * width + 'px,0,0);-ms-transform:translate3d(-' + o.index * width + 'px,0,0);';
                o._direction = 1;
                me._setTimeout();
                return me;
            },
            start: function() {
                var me = this;
                me.data._direction = 1;
                me.data._needPlay = true;
                me._setTimeout();
                return me;
            },
            stop: function() {
                var me = this;
                clearTimeout(me.data.play);
                me.data._needPlay = false;
                return me;
            },
            prev: function() {
                return this._slide(this.data.index - 1);
            },
            next: function() {
                return this._slide(this.data.index + 1);
            }
        };
        window.Slider = slider;
    }(window));