$(function(){
    /*console.log('start -- ' + (+new Date()));
    setTimeout(function(){
        console.log('end -- ' + (+new Date()));
    }, 1000);*/
    var video = null, canvas = null, canplay = false, isPlayIng = false;
    var videos = function(){
        var it = {};
        it.videos = [];
        it.end = false;
        it.put = function(info){
            if(!this.check(info)){
                this.videos.push(info);
                return true;
            }
            return false;
        };
        it.remove = function(index){
            this.videos.splice(index, 1);
        };
        it.info = function(index){
            return this.videos[index];
        };
        it.infoById = function(id){
            var index = -1;
            $.each(this.videos, function(i, n){
                if(n['id'] == id){
                    index = i;
                    return false;
                }
            });
            if(index != -1){
                return this.fetch(index);
            }
            return false;
        };
        it.fetch = function(index){
            var _index = this.index();
            if(index === undefined){
                if(this.videos.length - 1 == _index){
                    this.end = true;
                    return;
                 }
                this.index(++_index);
            }else{
                if(index < this.videos.length){
                    this.index(index);
                }else{
                    return;
                }
            }
            return this.videos[this.index()];
        };
        it.current = function(){
            return this.info(this.index());
        };
        it.index = function(){
            var _index = 0;
            return function(index){
                return index === undefined ? _index : (_index = index);
            }
        }();
        it.len = function(){
            return this.videos.length;
        };
        it.check = function(info){
            var src = info['src'];
            var isin = false;
            $.each(this.videos, function(i, n){
                if(n['src'] == src){
                    isin = true;
                    return false;
                }
            });
            return isin;
        };
        return it;
    }();

    var Timer = function(){
        window.requestAFrame = (function(){
            return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                window.oRequestAnimationFrame ||
                function(callback){
                    window.setTimeout(callback, 1000 / 60);
                };
        })();
        window.cancelAFrame = (function(){
            return window.cancelAnimationFrame ||
                window.webkitCancelAnimationFrame ||
                window.mozCancelAnimationFrame ||
                window.oCancelAnimationFrame ||
                function(id){
                    window.clearTimeout(id);
                };
        })();

        var it = {};
        var timer = null;
        var isStart = false;
        it.loop = function(){
            /*timer = setTimeout(function(){
                video.video('setZhen', currentVideo['zhen'], true);
                it.loop();
            }, 15); */
            timer = window.requestAFrame(function(){
                video.video('setZhen', true);
                it.loop();
            });
        };
        it.start = function(){
            isStart = true;
            this.loop();
        };
        it.stop = function(){
            //timer && clearTimeout(timer) && (timer = null);
            isStart = false;
            timer && window.cancelAFrame(timer) && (timer = null);
        };
        it.isStart = function(){
            return isStart;
        };
        return it;
    }();


    $('#video-mask').on({
        click : function(){
            $('.ui-video-play').trigger('click');
        },
        'load-start' : function(){
            var img = $(this).find('img');
            if(!img[0]){
                img = $('<img src="'+ RESOURCE_URL + 'loading2.gif"/>').css({
                    position : 'absolute',
                    left : '50%',
                    top : '50%',
                    width : '30px',
                    margin : '-15px 0 0 -15px'
                }).appendTo(this);
            }
            img.show();
        },
        'load-end' : function(){
            $(this).find('img').hide();
        }
    });


    var box = $('#video-box').on({
        'set' : function(event, info, callback){
            $(this).trigger('destroy');
            $('#video-mask').trigger('load-start');
            $(this).html('<video id="video" style="background:#000;" width="500" height="375" src="'+ info['src'] +'" poster="'+ info['img'] +'"></video>');
            $('.ui-video-progress').remove();
            $(this).triggerHandler('video', [info, function(){
                callback && callback();
                $('#video-mask').trigger('load-end');
            }]);
            $(this).triggerHandler('canvas', [info['fen']]);
        },
        'video' : function(event, info, callback){
            video = $(this).find('video');
            video.video({
                autoPlay : false,
                autobuffer : true,
                zhen : info['zhen'],
                customEvents : {
                    'canplay.video' : function(){
                        canplay = true;
                        callback && setTimeout(callback, 0);
                        $('.ui-video-progress').appendTo('#video-dian');
                    },
                    '_play.video' : function(){
                        $('#video-bujin').trigger('_hide');
                        isPlayIng = true;
                        $('#video-btn').trigger('change');
                        !Timer.isStart() && Timer.start();
                    },
                    '_pause.video' : function(){
                        $('#video-bujin').trigger('_show');
                        isPlayIng = false;
                        $('#video-btn').trigger('change');
                        $('#video-box').trigger('zhen', [true]);
                        Timer.stop();
                    },
                    'timeupdate.video' : function(){
                        $('#video-box').trigger('zhen');
                        $('#zhou').trigger('ok');
                    },
                    'ended.video' : function(){
                        return;
                        var next = videos.fetch();
                        if(!next) return;
                        setTimeout(function(){
                            $('#video-box').trigger('set', [next]);
                        }, 0);
                    },
                    'error.video' : function(){
                        console.log(this.currentTime);
                    }
                }
            });
            var me = $(this);
            $(this).find('.ui-video-seek-prev, .ui-video-seek-next').on({
                mousedown : function(event){
                    if(event.which != 1) return;
                    var which = $(this).hasClass('ui-video-seek-prev') ? 'prev' : 'next';
                    me.trigger(which);
                    $('body').data('timer', setTimeout(function(){
                        me.trigger(which);
                        $('body').data('timer', setTimeout(arguments.callee, 300));
                    }, 300)).one('mouseup', function(){
                        clearTimeout($(this).data('timer'));
                        $(this).data('timer', null);
                    });
                },
                mouseup : function(){
                    if(event.which != 1) return;
                    //if($('body').data('timer')){
                        clearTimeout($('body').data('timer'));
                        $('body').data('timer', null);
                        me.trigger($(this).hasClass('ui-video-seek-prev') ? 'prev' : 'next');
                    //}
                }
            });
        },
        'canvas' : function(event, fen){
            canvas = $.createCanvas({
                width : fen[0] || video.attr('width'),
                height : fen[1] || video.attr('height')
            });
        },
        'prev' : function(){
            var bujin = $(this).data('bujin');
            video.video('setT', bujin, false);
         },
        'next' : function(){
            var bujin = $(this).data('bujin');
            video.video('setT', bujin, true);
         },
        'bujin' : function(event, which){
            var currentInfo = videos.current();
            var bujin;
            switch(which){
                case 'zhen' :
                    bujin = 1 / currentInfo['zhen'];
                    break;
                case 'zhen5' :
                    bujin = 5 / currentInfo['zhen'];
                    break;
                case 'miao' :
                    bujin = 1;
                    break;
                case 'miao5' :
                    bujin = 5;
                    break;
            }
            $(this).data('bujin', bujin);
        },
        destroy : function(){
            if(canvas !== null){
                canvas.destroy();
                canvas = null;
            }
            if(video !== null){
                video.video('destroy');
                video = null;
            }
            canplay = false;
            isPlaying = false;

            $(this).find('.ui-video-seek-prev, .ui-video-seek-next').off();
        }
    });
    $.each(videoInfos, function(i, n){
        videos.put(n);
    });
    box.triggerHandler('set', [videos.fetch(0)]);


    (function(){
        var types = {
            //type : [['zhen', '逐帧步进'], ['zhen5', '&nbsp;5帧步进'], ['miao', '逐秒步进']],
            type : [['zhen', '逐帧步进'], ['miao', '逐秒步进']],
            next : function(){
                var _index = -1;
                return function(current){
                    if(typeof current != 'undefined'){
                        return this.type[_index];
                    }
                    var len = this.type.length;
                    _index++;
                    _index >= len && (_index = 0);
                    return this.type[_index];
                }
            }()
        };

        $('#video-bujin').on({
            click : function(){
                if($(this).data('ban')) return;
                var type = types.next();
                $(this).html(type[1]);
                box.trigger('bujin', [type[0]]);
            },
            _show : function(){
                $(this).data('ban', false).css({'opacity' : 1, 'cursor' : 'pointer'});
                box.trigger('bujin', [types.next(true)[0]]);
            },
            _hide : function(){
                $(this).data('ban', true).css({'opacity' :.3, 'cursor' : 'default'});
                box.trigger('bujin', ['miao5']);
            }
        }).trigger('click');
    })();

    (function(){

    $('#video-btn').on({
        mousedown : function(event){
            $(this).addClass('on');
        },
        mouseup : function(event){
            $(this).removeClass('on');
            $('#video-slice').trigger('set');
        },
        change : function(){
            var info = $(this).data('info');
            if(!info){
                info = {
                    'ls' : '设为入点',
                    'le' : '编辑入点',
                    'rs' : '设为出点',
                    're' : '编辑出点',
                    'wu' : '......'
                }
                $(this).data('info', info);
            }
            if(isPlayIng){
                current = 'wu';
                $(this).removeClass('ru chu');
            }else{
                var on = $('#video-slice').find('.s-img.on');
                var current = 'wu';
                if(on[0]){
                    current = on.hasClass('s-left') ? 'ls' : 'rs';
                    if(on.find('.s-bottom').find('img')[0]){
                        current == 'ls' ? 'le' : 're';
                    }
                }
                $(this).removeClass('ru chu');
                if(current == 'ls' || current == 'le'){
                    $(this).addClass('ru');
                }else if(current == 'rs' || current == 're'){
                    $(this).addClass('chu');
                }
            }
            //$(this).find('span').html(info[current]);
            if(current == 'wu'){
                $(this).hide();
            }else{
                $(this).show().attr('title', info[current]);
            }
        }
    });

    })();


    (function(){


    var keydown37 = 0, keydown39 = 0;
    $(document).on({
        keydown : function(event){
            if($('body').data('title-ing')) return;
            var keycode = event.keyCode;
            if($.inArray(keycode, [32, 37, 38, 39, 40]) != -1){
                event.preventDefault();
            }
            switch(keycode){
                case 32:
                    video.video('space');
                    break;
                case 37:
                    if($.browser.chrome){
                        box.triggerHandler('prev');
                    }else{
                        if(keydown37) return;
                        keydown37 = 1;
                        (function(){
                            var me = arguments.callee;
                            $(document).data('key-timer', setTimeout(function(){
                                box.triggerHandler('prev');
                                keydown37++;
                                me();
                            }, 100));
                        })();
                    }
                    break;
                case 39:
                    if($.browser.chrome){
                        box.triggerHandler('next');
                    }else{
                        if(keydown39) return;
                        keydown39 = 1;
                        (function(){
                            var me = arguments.callee;
                            $(document).data('key-timer', setTimeout(function(){
                                box.triggerHandler('next');
                                keydown39++;
                                me();
                            }, 100));
                        })();
                    }
                    break;
            }
            if(event.ctrlKey){
                 if(keycode == 78){
                     $('#vs-add').trigger('click');
                     event.preventDefault();
                     return false;
                 }
            }
        },
        keyup : function(event){
            if($('body').data('title-ing')) return;
            var keycode = event.keyCode;
            if($.inArray(keycode, [32, 37, 38, 39, 40]) != -1){
                event.preventDefault();
            }
            switch(keycode){
                case 37:
                case 39:
                    if($.browser.chrome){

                    }else{
                        if(keycode == 37){
                            keydown37 == 1 && box.triggerHandler('prev');
                            keydown37 = 0;
                        }else{
                            keydown39 == 1 && box.triggerHandler('next');
                            keydown39 = 0;
                        }
                        clearTimeout($(this).data('key-timer'));
                        $(this).removeData('key-timer');
                    }
                    break;
                case 40:
                    $('#video-slice').trigger('set');
                    break;
            }
        }
    });

    })();

    var srcTpl = 'http://vapi1.dev.hogesoft.com:233/snap/{{id}}/{{time}}/60-.jpg';
    var partTpl = $('#line-tpl').val();

    function replaceTpl(tpl, data){
        return tpl.replace(/{{([a-z]+)}}/g, function(all, match){
            return data[match];
        });
    }

    function changeTimeShow(secondsTmp, zhenTmp){
        var seconds = secondsTmp / 1000;
        var h = parseInt(seconds / 3600);
        var m = parseInt((seconds - h * 3600) / 60);
        var s = parseInt(seconds % 60);
        var sp = s >= 10 ? '' : '0';
        var mp = m >= 10 ? '' : '0';
        var hp = h >= 10 ? '' : '0';

        secondsTmp /= 1000;
        var zhen = secondsTmp - Math.floor(secondsTmp);
        zhen *= Math.ceil(zhenTmp);
        zhen = Math.ceil(zhen);
        var zhenp = zhen >= 10 ? '' : '0';

        return hp + h + ":" + mp + m + ":" + sp + s + ":" + zhenp + zhen;
    }

    (function(){

        var tpl = $('#slice-tpl').val();

        var ajaxCutUrl = 'run.php?mid='+ gMid +'&a=video_cutting';
        var ajaxUrl = 'run.php?mid='+ gMid +'&a=auto_save';
        var ajaxDeleteUrl = 'run.php?mid='+ gMid +'&a=auto_save_delete';
        var ajaxOrderUrl = 'run.php?mid='+ gMid +'&a=auto_save_order';

        function getImgData(info){
            return canvas.toImgFormVideo(video);
        }

        function getTime(){
            return $('.ui-video-progress .ui-video-current-progress').text() + $('.ui-video-progress .ui-video-current-zhen').text();
        }

        function li(sort, hash){
            this.isnew = true;
            this.isinit = false;
            this.timeChange = false;
            this.kuaiChange = false;
            this.hash = hash;
            this.id = 0;
            this.start = -1;
            this.startShow = '';
            this.startImg = '';
            this.end = -1;
            this.endShow = '';
            this.endImg = '';
            this.duration = 0;
            this.sort = sort;
            this.kuai = '';
            this.callback = $.noop;

            this.title = '';
            this.titleChange = false;
        }
        $.extend(li.prototype, {
            setHash : function(hash){
                this.hash = hash;
            },
            setId : function(id){
                if(this.id == id){
                    return true;
                }
                if(this.id && this.id != id){
                    jAlert('不是同一个视频，不能设置出入点！', '提示');
                    return false;
                }
                this.id = id;
                return true;
            },
            setStart : function(id, start, startShow, img){
                if(!this.setId(id)){
                    return false;
                }
                var me = this;
                var set = function(){
                    me.start = start;
                    me.startShow = startShow;
                    me.startImg = img;
                    me.setDuration();
                    me.timeChange = true;
                    me.ajax();
                    me.callback();
                }
                if(this.isinit){
                    set();
                    return;
                }
                if(this.end != -1 && this.end <= start){
                    jConfirm('设置的入点大于等于已设置的出点，确定要设置？', '提示', function(result){
                        if(result){
                            me.clearEnd();
                            set();
                        }
                    });
                }else{
                    if(this.start == -1){
                        this.kuai = this.img;
                    }
                    set();
                }
            },
            clearStart : function(){
                this.start = -1;
                this.startShow = '';
                this.startImg = '';
            },
            setEnd : function(id, end, endShow, img){
                if(!this.setId(id)){
                    return false;
                }
                var me = this;
                var set = function(){
                    me.end = end;
                    me.endShow = endShow;
                    me.endImg = img;
                    me.setDuration();
                    me.timeChange = true;
                    me.ajax();
                    me.callback();
                }
                if(this.isinit){
                    set();
                    return;
                }
                if(this.start != -1 && this.start >= end){
                    var me = this;
                    jConfirm('设置的出点小于等于已设置的入点，确定要设置？', '提示', function(result){
                        if(result){
                            me.clearStart();
                            set();
                        }
                    });
                }else{
                    set();
                }
            },
            clearEnd : function(){
                this.end = -1;
                this.endShow = '';
                this.endImg = '';
            },
            setDuration : function(){
                var time = '';
                if(this.end != -1 && this.start != -1){
                    var seconds = (this.end - this.start) / 1000;
                    var h = parseInt(seconds / 3600);
                    var m = parseInt(seconds / 60);
                    var s = parseInt(seconds % 60);
                    time = (h ? h + '\'' : '') + (m ? m + '\'' : '') + (s ? s + '"' : '');
                }
                this.duration = time;
            },
            setSort : function(sort){
                this.sort = sort;
            },
            setKuai : function(kuai){
                this.kuai = kuai;
                this.kuaiChange = true;

                this.ajax();
            },
            setCallback : function(callback){
                var me = this;
                this.callback = function(){
                    callback();
                    setTimeout(function(){
                        me.callback = $.noop;
                    }, 0);
                }
            },
            setTitle : function(title){
                this.title = title;
                this.titleChange = true;

                this.ajax();
            },
            ajax : function(){
                if(this.isinit){
                    return;
                }
                var ajaxCallback = function(){
                    $('.v-save').trigger('init');
                }
                if(this.isnew){
                    if(!this.isok()){
                        return;
                    }
                    var me = this;
                    var post = {
                        main_video_id : videos.info(0)['id'],
                        vodinfo_id : this.id,
                        input_point : this.start,
                        output_point : this.end,
                        order_id : this.sort,
                        hash_id : this.hash,
                        imgdata : this.kuai,
                        title : this.title
                    };
                    $.post(
                        ajaxUrl,
                        post,
                        function(json){
                            me.isnew = false;
                            ajaxCallback();
                        },
                        'json'
                    );
                }else{
                    var me = this;
                    var post = {
                        'hash_id' : me['hash']
                    };
                    if(me.timeChange){
                        post['input_point'] = me['start'];
                        post['output_point'] = me['end'];
                        $.post(
                            ajaxUrl,
                            post,
                            function(json){
                                me.timeChange = false;
                                ajaxCallback();
                            },
                            'json'
                        );
                    }
                    if(me.titleChange){
                        post['title'] = me['title'];
                        $.post(
                            ajaxUrl,
                            post,
                            function(json){
                                me.titleChange = false;
                                ajaxCallback();
                            },
                            'json'
                        );
                    }
                    if(me.kuaiChange){
                        post['imgdata'] = me['kuai'];
                        $.post(
                            ajaxUrl,
                            post,
                            function(json){
                                me.kuaiChange = false;
                                ajaxCallback();
                            },
                            'json'
                        );
                    }
                }

            },
            isok : function(){
                return this.id && (this.start != -1) && (this.end != -1);
            }
        });

        var lis = {
            lis : {},
            add : function(hash, sort){
                hash = hash || (+new Date() + parseInt(Math.random() * 10000));
                sort = sort || ($('#video-slice li[hash]').length || 0) + 1;
                this.lis[hash] = new li(sort, hash);
                return hash;
            },
            remove : function(hash, callback){
                $.post(
                    ajaxDeleteUrl,
                    {
                        hash_id : hash
                    },
                    function(json){
                        callback && callback();
                    },
                    'json'
                );
                delete this.lis[hash];
            },
            get : function(hash){
                return this.lis[hash];
            },
            empty : function(callback){
                var hash_ids = [];
                $.each(this.lis, function(i, n){
                    hash_ids.push(i);
                });
                $.post(
                    ajaxDeleteUrl,
                    {
                        hash_id : hash_ids.join(',')
                    },
                    function(json){
                        callback && callback();
                    },
                    'json'
                );
                for(var i in this.lis){
                    delete this.lis[i];
                }
                this.lis = {};
            }
        };

        /*function autoSaveNew(){
            setInterval(function(){
                $.each(lis.lis, function(i, n){
                    if(!n.isnew && n.isok()){
                        var post = {
                            'hash_id' : n['hash']
                        };
                        if(n.timeChange){
                            post['input_point'] = n['start'];
                            post['output_point'] = n['end'];
                            $.post(
                                ajaxUrl,
                                post,
                                function(json){
                                    lis.lis[i].timeChange = false;
                                },
                                'json'
                            );
                        }
                        if(n.titleChange){
                            post['title'] = n['title'];
                            $.post(
                                ajaxUrl,
                                post,
                                function(json){
                                    lis.lis[i].titleChange = false;
                                },
                                'json'
                            );
                        }
                        if(n.kuaiChange){
                            post['imgdata'] = n['kuai'];
                            $.post(
                                ajaxUrl,
                                post,
                                function(json){
                                    lis.lis[i].kuaiChange = false;
                                },
                                'json'
                            );
                        }
                    }
                });
            }, 2 * 1000);
        }

        //autoSaveNew();*/

        $('.v-save').on({
            'init' : function(){
                var which = 'show';
                var num = 0;
                $.each(lis.lis, function(i, n){
                    if(n.isok()){
                        num++;
                    }
                    if(n.isnew && (n.start + n.end) != -2){
                        which = 'hide';
                        return false;
                    }
                    if(!n.isnew && (n.start == -1 || n.end == -1)){
                        which = 'hide';
                        return false;
                    }
                });
                if(num < 1){
                    which = 'hide';
                }
                $(this)[which]();
            },
            click : function(){
                var me = $(this);
                $.post(
                    ajaxCutUrl,
                    {main_video_id : videos.info(0)['id']},
                    function(json){

                        $('.option-iframe-back').trigger('click');
                    },
                    'json'
                );
            }
        });

        var slice = $('#video-slice');

        slice.on('click', '.s-img', function(event, videoNoShow){
            if($(this).hasClass('on')){
                $(this).removeClass('on');
                $('#video-btn').trigger('change');
                return false;
            }
            $('.s-img.on').removeClass('on');
            $(this).addClass('on');
            $('#video-btn').trigger('change');

            if(canplay && !videoNoShow){
                var liObj = lis.get($(this).closest('li').attr('hash'));
                var currentVideo = videos.current();
                var type = $(this).hasClass('s-left') ? 'start' : 'end';
                var time = liObj[type];
                if(time != -1){
                    video.video('pause');
                    video.video('setT', time / 1000);
                }
            }
        });

        slice.on('set', function(event){
            if($('.ui-video-play').hasClass('ui-icon-pause')){
                return;
            }
            var on = $(this).find('.s-img.on');
            if(!on[0]){
                return;
            }
            var domLi = on.closest('li');
            var hash = domLi.attr('hash');
            var li = lis.get(hash);
            var which = on.hasClass('s-left') ? 'left' : 'right';
            var currentTime = video.video('getT');
            var currentId = videos.current()['id'];

            var me = $(this);
            li.setCallback(function(){
                me.trigger('createDomLi', [li, domLi]);
                domLi.find('.on').removeClass('on');
                if(which == 'left'){
                    domLi.find('.s-right').show();
                    if(li.end == -1){
                        domLi.find('.s-right').trigger('click', [true]);
                    }
                }
                $('#video-btn').trigger('change');
                $('.v-save').trigger('init');
                $('.v-ext').trigger('checkVext');
            });
            li[which == 'left' ? 'setStart' : 'setEnd'](currentId, currentTime * 1000, getTime(), getImgData());
        });

        slice.on('setImg', '.s-img', function(event, imgSrc){
            var img = '';
            imgSrc && (img = '<img style="width:60px;" src="'+ imgSrc +'"/>');
            $(this).find('.s-bottom').html(img);
        });

        slice.on('setTime', '.s-t-l, .s-t-r', function(event, time){
            time == -1 && (time = '');
            $(this).html(time);
        });

        slice.on('click', '.s-kuai-outer', function(event, imgData){
            if($(this).closest('.s-kuai').hasClass('s-kuai-select')) return;
            if(!imgData){
                imgData = getImgData();
                lis.get($(this).closest('li').attr('hash')).setKuai(imgData);
            }
            var img = '<img src="'+ imgData +'" style="width:80px;"/>';
            $(this).closest('.s-kuai').addClass('s-kuai-select').find('.s-kuai-inner').html(img);
            return false;
        });

        slice.on('click', '.s-kuai-btn', function(event){
            var imgData = getImgData();
            lis.get($(this).closest('li').attr('hash')).setKuai(imgData);
            var img = '<img src="'+ imgData +'" style="width:80px;"/>';
            $(this).closest('.s-kuai').find('.s-kuai-inner').html(img);
            return false;
        });



        slice.on('click', '.s-del', function(){
            var me = $(this);
            jConfirm('确认要删除吗?', '删除提示', function(result){
                if(result){
                    var domLi = me.closest('li');
                    var hash = domLi.attr('hash');
                    lis.remove(hash);
                    domLi.remove();
                    $('.v-ext').trigger('changeVext');
                    $('.v-save').trigger('init');
                }
            });
            return false;
        });

        $('.v-ext').on({
            'changeVext' : function(){
                $(this).trigger('checkVext');
                $(this).height(function(){
                    var height = 0;
                    $(this).siblings().each(function(){
                        height += $(this).outerHeight();
                    });
                    height = $('#video-slice').height() - height;
                    height < 80 && (height = 80);
                    return height;
                });
            },
            'checkVext' : function(){
                var type = 'removeClass';
                $.each(lis.lis, function(i, n){
                    if(n.isnew && (n.start == -1 || n.end == -1)){
                        type = 'addClass';
                        return false;
                    }
                });
                $(this)[type]('v-ext-hide');
            }
        });

        slice.on('init', function(event, data){
            var hash, li, zhen, start, end, startShow, endShow;
            var me = $(this);
            $.each(data, function(i, n){
                hash = lis.add(n['hash_id'], n['order_id']);
                li = lis.get(hash);
                li.isnew = false;
                li.isinit = true;
                zhen = videoInfos[n['vodinfo_id']]['zhen'];
                start = parseInt(n['input_point']);
                if(start == -1){
                    li.setStart(n['vodinfo_id'], -1, '', '');
                }else{
                    startShow = changeTimeShow(start, zhen);
                    li.setStart(n['vodinfo_id'], start, startShow, replaceTpl(srcTpl, {
                        id : n['vodinfo_id'],
                        time : start
                    }));
                }
                end = parseInt(n['output_point']);
                if(end == -1){
                    li.setEnd(n['vodinfo_id'], -1, '', '');
                }else{
                    endShow = changeTimeShow(end, zhen);
                    li.setEnd(n['vodinfo_id'], end, endShow, replaceTpl(srcTpl, {
                        id : n['vodinfo_id'],
                        time : end
                    }));
                }

                li.setSort(n['order_id']);
                li.setKuai(n['src']);
                li.setDuration();
                li.setTitle(n['vcr_title']);
                li.timeChange = false;
                li.kuaiChange = false;
                li.titleChange = false;

                li.isinit = false;
                me.trigger('createDomLi', [li]);
            });
            $('.v-ext').trigger('changeVext');
            $('.v-save').trigger('init');
            return false;
        }).on('createDomLi', function(event, li, domLi){
            domLi || (domLi = $(tpl).insertBefore('#vs-add'));
            domLi.attr('hash', li.hash);
            domLi.find('.s-t-l').trigger('setTime', [li.startShow]);
            domLi.find('.s-left').trigger('setImg', [li.startImg]);
            domLi.find('.s-t-r').trigger('setTime', [li.endShow]);
            domLi.find('.s-right').show().trigger('setImg', [li.endImg]);
            domLi.find('.s-duration').html(li.duration);
            domLi.find('.s-kuai-outer').trigger('click', [li.kuai]);
            domLi.find('.s-title').html(function(){
                return li.title || $(this).attr('_default');
            });
        }).trigger('init', [vcrData]);

        $('#vs-add').click(function(){
            var prev = $(this).prev(), hash;
            if(prev[0] && (hash = prev.attr('hash'))){
                var li = lis.get(hash);
                if(!li.isok()){
                    return;
                }
            }
            hash = lis.add();
            $(tpl).attr('hash', hash).insertBefore(this).find('.s-left').trigger('click', [true]);
            $('.v-ext').trigger('changeVext');
        }).trigger('click');

        slice.on({
            mouseenter : function(){
                $(this).addClass('title-on');
            },
            mouseleave : function(){
                !$(this).data('s-focus') && $(this).removeClass('title-on');
            },
            focus : function(){
                $('body').data('title-ing', true);
                $(this).addClass('title-on').data('s-focus', true);
                if($.trim($(this).text()) == $(this).attr('_default')){
                    $(this).html('');
                }
            },
            blur : function(){
                $('body').data('title-ing', false);
                $(this).removeClass('title-on').data('s-focus', false);
                var title = $.trim($(this).text());
                var _default = $(this).attr('_default');
                if(title != '' || title != _default){
                    var hash = $(this).closest('li').attr('hash');
                    var li = lis.get(hash);
                    li.setTitle(title);
                }
                if(title == ''){
                    $(this).html(_default);
                }
            }
        }, '.s-title');

        slice.sortable({
            items : 'li:not(#vs-add)',
            placeholder : 'placer',
            axis : 'y',
            start : function(event, ui){
                ui.helper.css('background', '#494949');
            },
            beforeStop : function(event, ui){
                ui.helper.css('background', 'transparent');
            },
            stop : function(){
                slice.sortable('disable');
                setTimeout(function(){
                    var post = {
                        'hash_id[]' : [],
                        'order_id[]' : []
                    };
                    var order = 1;
                    $('#video-slice li[hash]').each(function(){
                        post['hash_id[]'].push($(this).attr('hash'));
                        post['order_id[]'].push(order);
                        order++;
                    });
                    $.post(
                        ajaxOrderUrl,
                        post,
                        function(json){

                        },
                        'json'
                    );
                }, 0);
            }
        });
        slice.sortable('disable');

        slice.on({
            mousedown : function(){
                slice.sortable('enable')
            }
        }, '.s-sort');
    })();

    (function(){
        function line(_line){
            this.line = _line;
            this.imgs = {};
            this.imgsType = 0;
            this.ok = false;
            this.len = 1;
        }
        line.setMax = function(){
            line.prototype.max = Math.floor(($('body').width() - 40) / 60);
        };
        $.extend(line.prototype, {
            max : 15,
            put : function(ref, img){
                if(this.ok){
                    return false;
                }
                if(!this.imgs[ref]){
                    if(this.imgsType > 0){
                        this.len++;
                        if(this.len > this.max){
                            this.ok = true;
                            return false;
                        }
                    }
                    this.imgs[ref] = [];
                    this.imgsType++;
                }
                this.imgs[ref].push(img);
                this.len++;
                if(this.len > this.max){
                    this.ok = true;
                }
                return true;
            },
            empty : function(){
                return !!$.isEmptyObject(this.imgs);
            }
        });

        var lines = (function(){
            var _each, _lines, _currentLineIndex;
            var _cache = {}, _cacheCheck = {};
            var it = {};
            it.init = function(){
                _each = 5000;
                _lines = [];
                _currentLineIndex = 1;
            };
            it.each = function(each){
                if(typeof each != 'undefined'){
                    _each = each;
                }else{
                    return _each;
                }
            };
            it.lines = function(){
                return _lines;
            };
            it.add = function(video, callback){
                var _cacheKey = 'lines-' + _each;
                if(_cacheCheck[_cacheKey]){
                    _lines = _cache[_cacheKey].concat();
                    callback();
                    return;
                }else{
                    _cache[_cacheKey] = [];
                }
                var imgs = [];

                function getTime(){
                    return +new Date();
                }

                (function(){
                    $.each(video, function(i, n){
                        var id = n.id;
                        var time = n.time;
                        var number = new Array(Math.ceil(time / _each));
                        imgs[i] = [];
                        $.each(number, function(ii, nn){
                            var st = _each * ii;
                            var et = st + _each;
                            if(et > time){
                                et = time;
                            }
                            imgs[i].push([st, et, replaceTpl(srcTpl, {
                                id : id,
                                time : st
                            })]);
                        });
                    });
                })();

                (function(){
                    var currentLine = _lines[_currentLineIndex] || new line(_currentLineIndex);
                    var imgsI = 0;
                    var imgsIN = imgs[imgsI];
                    var lastTime = getTime();
                    var nowTime;
                    imgsIN && loop();
                    function loop(){
                        nowTime = getTime();
                        if(nowTime - lastTime > 100){
                            setTimeout(function(){
                                lastTime = getTime();
                                loop();
                            }, 30);
                        }else{
                            var tmp = imgsIN.shift();
                            if(tmp){
                                if(!currentLine.put(imgsI, tmp)){
                                    _lines.push(currentLine);
                                    _cache[_cacheKey] = _cache[_cacheKey] || [];
                                    _cache[_cacheKey].push(currentLine);
                                    _currentLineIndex++;
                                    currentLine = new line(_currentLineIndex);
                                    currentLine.put(imgsI, tmp);
                                }
                            }else{
                                imgsI++;
                                imgsIN = imgs[imgsI];
                                if(!imgsIN){
                                    if(!currentLine.empty()){
                                        _lines.push(currentLine);
                                        _cache[_cacheKey].push(currentLine);
                                    }
                                    _cacheCheck[_cacheKey] = true;
                                    callback();
                                    return;
                                }
                            }
                            loop();
                        }
                    }
                })();

                /*
                $.each(video, function(i, n){
                    var id = n.id;
                    var time = n.time;
                    var _cacheKey = id + 'x' + _each;
                    if(_cache[_cacheKey]){
                        imgs[i] = _cache[_cacheKey];
                        return;
                    }
                    var number = new Array(Math.ceil(time / _each));
                    imgs[i] = [];
                    $.each(number, function(ii, nn){
                        var st = _each * ii;
                        var et = st + _each;
                        if(et > time){
                            et = time;
                        }
                        imgs[i].push([st, et, replaceTpl(srcTpl, {
                            id : id,
                            time : st
                        })]);
                    });
                    _cache[_cacheKey] = imgs[i];
                });
                $.each(imgs, function(i, n){
                    if(n){
                        $.each(n, function(ii, nn){
                            if(!currentLine.put(i, nn)){
                                _lines.push(currentLine);
                                _currentLineIndex++;
                                currentLine = new line(_currentLineIndex);
                                currentLine.put(i, nn);
                            }
                        });
                    }
                });
                if(!currentLine.empty()){
                    _lines.push(currentLine);
                }
                */
            };
            return it;
        })();

        $('.zhou-box').on('change-height', function(){
            $(this).height($(window).height());
        });

        var zhouTimer = null;
        $('#zhou').on({
            init : function(event, each){
                var me = $(this);
                var complete = false;
                clearInterval(zhouTimer);
                me.trigger('destroy');
                lines.init();
                lines.each(each);
                lines.add(videos.videos, function(){
                    complete = true;
                });
                var initNum = 1, initPerNum = 8;
                zhouTimer = setInterval(function(){
                    var line = lines.lines().shift();
                    if(line){
                        me.trigger('cline', [line, each]);
                        if(initNum % initPerNum == 0){
                            bindImgLazyLoad();
                        }
                        initNum++;
                    }else{
                        if(complete){
                            clearInterval(zhouTimer);
                            bindImgLazyLoad();
                            $('.zhou-box').trigger('change-height');
                        }
                    }
                }, 150);

                function bindImgLazyLoad(){
                    me.find('img').filter(function(){
                        return !$(this).data('haslazy');
                    }).data('haslazy', true).lazyload({
                        container : '.zhou-box'
                    });
                }

                /*
                var totalLines = lines.lines();
                $.each(totalLines, function(i, n){
                    var last = false;
                    if(totalLines.length == i + 1){
                        last = true;
                    }
                    me.trigger('cline', [n, each, last]);
                });
                me.trigger('creatAdd');
                me.find('img').lazyload({
                    container : '.zhou-box'
                });
                $('.zhou-box').trigger('change-height');
                */
            },
            destroy : function(){
                $(':ui-slider', this).slider('destroy').off();
                $('img', this).off();
                $(this).removeData().empty();
            },
            cline : function(event, newLine, each){
                var line = $('<div class="z-line"></div>').appendTo(this);
                var imgs = newLine.imgs;
                var left = 0;
                $.each(imgs, function(i, n){
                    var part = $(partTpl).appendTo(line);
                    if(n){
                        var imgsHtml = '';
                        $.each(n, function(ii, nn){
                            imgsHtml += '<span><img original="'+ nn[2] +'"/></span>';
                        });
                        var width = n.length * 60;
                        var currentVideo = videos.info(i);
                        var info = {
                            vindex : i,
                            start : n[0][0],
                            end : n[n.length - 1][1]
                        };
                        if(info['end'] == currentVideo['time']){
                            width *= (info['end']- info['start']) / (n.length * each);
                        }
                        part.css({
                            'width' : width + 'px',
                            'left' : (left ? left : 0) + 'px'
                        });
                        left += width + 60;

                        part.attr(info);
                        part.find('.z-img-box').width(width);
                        part.find('.z-img').html(imgsHtml);
                        var border = part.find('.z-border').css({
                            width : width + 'px'
                        });

                        if(info['start'] > 0){
                            border.addClass('z-border-noleft');
                        }
                        if(info['end'] != currentVideo['time']){
                            border.addClass('z-border-noright');
                        }


                        part.find('.z-slider').data('info', info).width(width).slider({
                            range : 'min',
                            step : 0.2,
                            stop : function(event, ui){
                                ui.handle.blur();
                            },
                            slide : function(event, ui){
                                if(!$(this).hasClass('z-sliderCurrent')){
                                    $('.z-sliderCurrent').removeClass('z-sliderCurrent');
                                    $(this).addClass('z-sliderCurrent');
                                }
                                var me = $(this);
                                var info = $(this).data('info');
                                var doThis = function(){
                                    if(!canplay) return;
                                    var tt = info['start'] + (info['end'] - info['start']) * (ui.value / 100);
                                    video.video('setT', tt / 1000);
                                }
                                if(videos.index() != info['vindex']){
                                    $('#video-box').trigger('set', [videos.fetch(info['vindex']), doThis]);
                                }else{
                                    doThis();
                                }
                            }
                        });
                    }
                });
            },
            ok : function(){
                var me = $(this);
                if(canplay){
                    var tt = video.video('getT') * 1000;
                    var vindex = videos.index();
                    var info = me.data('info');
                    if(info && info['vindex'] == vindex && tt > info['start'] && tt < info['end']){
                        var val = (tt - info['start']) / (info['end'] - info['start']) * 100
                        me.data('currentSlider').slider('value', val);
                    }else{
                        $('.z-slider').each(function(){
                            var info = $(this).data('info');
                            if(info['vindex'] == vindex && tt > info['start'] && tt < info['end']){
                                me.data('info', info).data('currentSlider', $(this));
                                if(!$(this).hasClass('z-sliderCurrent')){
                                    $('.z-sliderCurrent').removeClass('z-sliderCurrent');
                                    $(this).addClass('z-sliderCurrent');
                                }
                                var val = (tt - info['start']) / (info['end'] - info['start']) * 100
                                $(this).slider('value', val);
                                return false;
                            }
                        });
                    }
                }
            }
        }).on('click', '#video-add', function(){
            $('#add-box').trigger('show');
        });

        $('#zhou-type').on('click', 'li', function(event){
            $(this).addClass('on').siblings().removeClass('on');
            var me = $(this);
            setTimeout(function(){
                $('#zhou').trigger('init', [me.attr('_type') * 1000]);
            }, 10);
        }).find('li[_type="10"]').addClass('on');

        (function(){
            var resizeTimer;
            $(window).resize(function(){
                resizeTimer && clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function(){
                    line.setMax();
                    $('#zhou-type').find('li.on').trigger('click');
                }, 300);
            });
        })();
    })();
});

(function($){
    function canvas(options){
        this.width = options.width || 500;
        this.height = options.height || 375;
        this.oldWidth = options.oldWidth || options.width;
        this.oldHeight = options.oldHeight || options.height;
        this.init();
    }
    $.extend(canvas.prototype, {
        init : function(){
            this.element = $('<canvas width='+ this.width +' height="'+ this.height +'"></canvas>')[0];
        },
        toImgFormVideo : function(video){
            video = $(video);
            this.element.getContext('2d').drawImage(video[0], (this.width - this.oldWidth) / 2, (this.height - this.oldHeight) / 2, this.width, this.height);
            return this.element.toDataURL('image/png');
        },
        destroy : function(){
            this.element = null;
        }
    });
    $.createCanvas = function(options){
        return new canvas(options);
    }
})(jQuery);

$(function(){
    $(window).on('resize', function(){
        var width = $('body').width() - 40;
        $('.video').width(width);
        $('#video-slice').width(function(){
            return width - 20 - $('.v-left').outerWidth(true);
        });
        $('.zhou-box').trigger('change-height');
    }).trigger('resize');

    var timer = setInterval(function(){
        if($('video')[0]){
            clearInterval(timer);
            $('<input />').appendTo('body').focus().remove();
            $('body').scrollTop(0);
        }
    }, 10);

    $('.option-iframe-back').click(function(){
        if(top != self){
            top.$('#livwinarea').trigger('iclose');
        }
    });
});
