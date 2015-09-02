$(function(){
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
        var it = {};
        var timer = null;
        var currentVideo = null;
        var isStart = false;
        it.video = function(){
            currentVideo = videos.current();
        };
        it.loop = function(){
            timer = setTimeout(function(){
                video.video('setZhen', currentVideo['zhen'], true);
                it.loop();
            }, 15);
        };
        it.start = function(){
            isStart = true;
            this.video();
            it.loop();
        };
        it.stop = function(){
            timer && clearTimeout(timer) && (timer = null);
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
            $(this).triggerHandler('video', [function(){
                callback && callback();
                $('#video-mask').trigger('load-end');
            }]);
            $(this).triggerHandler('canvas', [info['fen']]);
        },
        'video' : function(event, callback){
            video = $(this).find('video');
            video.video({
                autoPlay : false,
                autobuffer : true,
                customEvents : {
                    'canplay.video' : function(){
                        canplay = true;
                        callback && setTimeout(callback, 0);
                        $('.ui-video-progress').appendTo('#video-dian');
                        $.browser.chrome && !Timer.isStart() && Timer.start();
                    },
                    '_play.video' : function(){
                        $('#video-bujin').trigger('_hide');
                        isPlayIng = true;
                        $('#video-btn').trigger('change');
                    },
                    '_pause.video' : function(){
                        $('#video-bujin').trigger('_show');
                        isPlayIng = false;
                        $('#video-btn').trigger('change');
                        $('#video-box').trigger('zhen');
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
            $(this).triggerHandler('zhen', true);
         },
        'next' : function(){
            var bujin = $(this).data('bujin');
            video.video('setT', bujin, true);
            $(this).triggerHandler('zhen', true);
         },
        'zhen' : function(event, force){
            var currentInfo = videos.current();
            video.video('setZhen', currentInfo['zhen'], force);
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
            type : [['zhen', '逐帧步进'], ['zhen5', '&nbsp;5帧步进'], ['miao', '逐秒步进']],
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

    $('#video-pn').on({
        'click' : function(){
            if($(this).data('disabled')) return;
            var which = $(this).attr('id') == 'video-prev' ? -1 : 1;
            var current = videos.index();
            var len = videos.len();
            var now = current + which;
            if(now >= 0 && now < len){
                $('#video-box').trigger('set', [videos.fetch(now)]);
                $('#video-prev, #video-next').trigger('abled');
                if(now == 0 || now == len - 1){
                    $('#video-' + (now == 0 ? 'prev' : 'next')).trigger('disabled');
                }
            }
        },
        abled : function(){
            $(this).css('opacity', 1).removeData();
        },
        disabled : function(){
            $(this).css('opacity', .3).data('disabled', true);
        }
    }, 'div').on('init', function(){
        var len = videos.len();
        if(len < 2){
            $(this).hide();
        }else{
            $(this).show();
            $('#video-prev, #video-next').trigger('abled');
            var index = videos.index();
            if(index == 0 || index == len - 1){
                $('#video-' + (index == 0 ? 'prev' : 'next')).trigger('disabled');
            }
        }
    }).trigger('init');

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
            $(this).find('span').html(info[current]);
        }
    });

    var keydown37 = 0, keydown39 = 0;
    $(document).on({
        keydown : function(event){
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

    var srcTpl = 'http://vapi1.dev.hogesoft.com:233/snap/{{id}}/{{time}}/60-.jpg';
    var partTpl = $('#line-tpl').val();

    function replaceTpl(tpl, data){
        return tpl.replace(/{{([a-z]+)}}/g, function(all, match){
            return data[match];
        });
    }

    (function(){

        var tpl = $('#slice-tpl').val();

        var ajaxUrl = 'run.php?mid='+ gMid +'&a=auto_save';
        var ajaxDeleteUrl = 'run.php?mid='+ gMid +'&a=auto_save_delete';
        var ajaxOrderUrl = 'run.php?mid='+ gMid +'&a=auto_save_order';

        function getImgData(info){
            return canvas.toImgFormVideo(video);
        }

        function getTime(){
            return $('.ui-video-progress .ui-video-current-progress').text() + $('.ui-video-progress .ui-video-current-zhen').text();
        }

        function changeTimeShow(seconds, zhen){
            seconds /= 1000;
            var h = parseInt(seconds / 3600);
            var m = parseInt(seconds / 60);
            var s = parseInt(seconds % 60);
            var sp = s >= 10 ? '' : '0';
            var mp = m >= 10 ? '' : '0';
            var hp = h >= 10 ? '' : '0';

            var zhen = seconds - Math.floor(seconds);
            zhen *= Math.ceil(zhen);
            zhen = Math.ceil(zhen);
            var zhenp = zhen >= 10 ? '' : '0';

            return hp + h + ":" + mp + m + ":" + sp + s + ":" + zhenp + zhen;
        }

        function li(sort, hash){
            this.isnew = true;
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
                    /*var totalH = (this.end - this.start) / 1000;
                    var h = parseInt(totalH / 3600);
                    var totalM = totalH - h * 3600;
                    var m = parseInt(totalM / 60);
                    var s = Math.ceil(totalM - m * 60);
                    time = (h ? h + '\'' : '') + (m ? m + '\'' : '') + (s ? s + '"' : '');*/
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
            ajax : function(){
                if(!this.isok()){
                    return;
                }
                if(this.isnew){
                    var me = this;
                    var post = {
                        main_video_id : videos.info(0)['id'],
                        vodinfo_id : this.id,
                        input_point : this.start,
                        output_point : this.end,
                        order_id : this.sort,
                        hash_id : this.hash,
                        imgdata : this.kuai
                    };
                    $.post(
                        ajaxUrl,
                        post,
                        function(json){
                            me.isnew = false;
                        },
                        'json'
                    );
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
            },
            info : function(){
                var len = 0, vids = [], hash = [];
                $.each(this.lis, function(i, n){
                    len++;
                    if($.inArray(n['id'], vids) != -1){
                        vids.push(n['id']);
                    }
                    hash.push(i);
                });
                return {
                    len : len,
                    vids : vids,
                    hash : hash
                };
            }
        };

        function autoSave(){
            return;
            var post = {
                'main_video_id' : videos.info(0)['id'],
                'vodinfo_id[]' : [],
                'input_point[]' : [],
                'output_point[]' : [],
                'order_id[]' : [],
                'imgdata[]' : []
            };
            var kong = true;
            $.each(lis.lis, function(i, n){
                if(n.isok()){
                    kong = false;
                    post['vodinfo_id[]'].push(n['id']);
                    post['input_point[]'].push(n['start']);
                    post['output_point[]'].push(n['end']);
                    post['order_id[]'].push(n['sort']);
                    post['imgdata[]'].push(n['kuai']);
                }
            });
            if(kong){
                return;
            }
            var url = 'run.php?mid='+ gMid +'&a=auto_save_vcr';
            $.post(
                url,
                post,
                function(json){

                },
                'json'
            );
        }

        function autoSaveNew(){
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

        autoSaveNew();

        $('.v-options').on({
            init : function(event, save){
                $(this).find('.v-o-fan, .v-o-merge, .v-o-more').hide();
                var okNum = 0;
                var vids = [];
                var wanzheng = true;
                $.each(lis.lis, function(i, n){
                    if(!n.isok()){
                        wanzheng = false;
                    }else{
                        okNum++;
                    }
                    if($.inArray(n['id'], vids) == -1){
                        vids.push(n['id']);
                    }
                });
                if(wanzheng){
                    if(okNum > 0){
                        $(this).find('.v-o-more').show();
                        if(okNum > 1){
                            $(this).find('.v-o-merge').show();
                        }
                    }
                    if(vids.length == 1){
                        $(this).find('.v-o-fan').show();
                    }
                }
                return false;
            }
        }).on('click', '.v-o-fan', function(){
            var ising = $(this).data('ising');
            if(ising) return;
            $(this).data('ising', true);
            $(this).html('<img src="'+ RESOURCE_URL + 'loading2.gif" style="width:15px;"/>');
            var me = $(this);

            var lisClone = [];
            $.each(lis.lis, function(i, n){
                if(n.isok()){
                    lisClone.push(n);
                }
            });
            Array.prototype.sort.call(lisClone, function(a, b){
                return a.start > b.start;
            });
            lis.empty(function(){

                var pointer = null;
                var hash;
                var videoInfo;
                var len = lisClone.length - 1;
                var liObj;
                var sort = 1;
                $.each(lisClone, function(i, n){
                    if(i == 0){
                        if(0 < n['start']){
                            hash = lis.add();
                            liObj = lis.get(hash);
                            var img = replaceTpl(srcTpl, {
                                id : n['id'],
                                time : 0
                            });
                            liObj.setStart(n['id'], 0, '00:00:00:00', img);
                            liObj.setEnd(n['id'], n['start'], n['startShow'], n['startImg']);
                            liObj.setKuai(img);
                            liObj.setDuration();
                            liObj.setSort(sort);
                            sort++;
                        }
                    }
                    if(pointer && pointer['end'] < n['start']){
                        hash = lis.add();
                        liObj = lis.get(hash);
                        liObj.setStart(pointer['id'], pointer['end'], pointer['endShow'], pointer['endImg']);
                        liObj.setEnd(n['id'], n['start'], n['startShow'], n['startImg']);
                        liObj.setKuai(pointer['endImg']);
                        liObj.setDuration();
                        liObj.setSort(sort);
                        sort++;
                    }
                    if(i == len){
                        if(!videoInfo){
                            videoInfo = videos.infoById(n['id']);
                        }
                        if(n['end'] < videoInfo['time']){
                            hash = lis.add();
                            liObj = lis.get(hash);
                            liObj.setStart(n['id'], n['end'], n['endShow'], n['endImg']);
                            var img = replaceTpl(srcTpl, {
                                id : n['id'],
                                time : videoInfo['time']
                            });
                            liObj.setEnd(n['id'], videoInfo['time'], changeTimeShow(videoInfo['time'], videoInfo['zhen']), img);
                            liObj.setKuai(pointer['endImg']);
                            liObj.setDuration();
                            liObj.setSort(sort);
                            sort++;
                        }
                    }

                    pointer = lisClone[i];
                });
                var slice = $('#video-slice');
                slice.find('li:not(#vs-add)').remove();
                $.each(lis.lis, function(i, n){
                    slice.trigger('createDomLi', [n]);
                });

                me.data('ising', false).html('反选');
            });
        });

        var slice = $('#video-slice');

        slice.on('click', '.s-img, .s-ttt', function(event, videoNoShow){
            if($(this).hasClass('on')){
                $(this).closest('li').find('.s-img.on, .s-ttt.on').removeClass('on');
                return false;
            }
            $('.s-img.on, .s-ttt.on').removeClass('on');
            var li = $(this).closest('li');
            var c = $(this).hasClass('s-img') ? '.s-img' : '.s-ttt';
            var index = li.find(c).index(this);
            li.find('.s-img').eq(index).add(li.find('.s-ttt').eq(index)).addClass('on');
            $('#video-btn').trigger('change');

            if(canplay && !videoNoShow){
                var liObj = lis.get(li.attr('hash'));
                var currentVideo = videos.current();
                var type = ($(this).hasClass('s-left') || $(this).hasClass('s-t-l')) ? 'start' : 'end';
                var time = liObj[type] / 1000;
                if(currentVideo['id'] == liObj['id']){
                    time != -1 && video.video('setT', time);
                }else{
                    var videoInfo = videos.infoById(liObj['id']);
                    $('#video-box').trigger('set', [videoInfo, function(){
                        time != -1 && video.video('setT', time);
                        $('#zhou').trigger('ok');
                    }]);
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
                $('.v-options').trigger('init', [true]);
            });
            li[which == 'left' ? 'setStart' : 'setEnd'](currentId, currentTime * 1000, getTime(), getImgData());
        });

        slice.on('setImg', '.s-img', function(event, imgSrc){
            var img = '';
            imgSrc && (img = '<img style="width:60px;" src="'+ imgSrc +'"/>');
            $(this).find('.s-bottom').html(img);
        });

        slice.on('setTime', '.s-ttt', function(event, time){
            time == -1 && (time = '');
            $(this).html(time);
        });

        slice.on('click', '.s-kuai', function(event, imgData){
            if(!imgData){
                imgData = getImgData();
                lis.get($(this).closest('li').attr('hash')).setKuai(imgData);
                autoSave();
            }
            var img = '<img src="'+ imgData +'" style="width:80px;"/>';
            $(this).addClass('s-kuai-select').find('.s-kuai-inner').html(img);
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
                    $('.v-options').trigger('init', [true]);
                }
            });
            return false;
        });

        slice.on('init', function(event, data){
            var hash, li, zhen, start, end, startShow, endShow;
            var me = $(this);
            $.each(data, function(i, n){
                hash = lis.add(n['hash_id'], n['order_id']);
                li = lis.get(hash);
                li.isnew = false;
                zhen = videoInfos[n['vodinfo_id']]['zhen'];
                start = parseInt(n['input_point']);
                startShow = changeTimeShow(start, zhen);
                li.setStart(n['vodinfo_id'], start, startShow, replaceTpl(srcTpl, {
                    id : n['vodinfo_id'],
                    time : start
                }));
                end = parseInt(n['output_point']);
                endShow = changeTimeShow(end, zhen);
                li.setEnd(n['vodinfo_id'], end, endShow, replaceTpl(srcTpl, {
                    id : n['vodinfo_id'],
                    time : end
                }));

                li.setSort(n['order_id']);
                li.setKuai(n['src']);
                li.setDuration();

                me.trigger('createDomLi', [li]);
            });
            $('.v-options').trigger('init', [true]);
            return false;
        }).on('createDomLi', function(event, li, domLi){
            domLi || (domLi = $(tpl).insertBefore('#vs-add'));
            domLi.attr('hash', li.hash);
            domLi.find('.s-t-l').trigger('setTime', [li.startShow]);
            domLi.find('.s-t-r').trigger('setTime', [li.endShow]);
            domLi.find('.s-left').trigger('setImg', [li.startImg]);
            domLi.find('.s-right').show().trigger('setImg', [li.endImg]);
            domLi.find('.s-duration').html(li.duration);
            domLi.find('.s-kuai').trigger('click', [li.kuai]);
            if(li.start != -1 || li.end != -1){
                domLi.find('.s-t-m').show();
            }
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
            $('#video-btn').trigger('change');
        }).trigger('click');

        var slice = $('#video-slice').sortable({
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
            line.prototype.max = Math.floor(($('.v-bottom').width() - 50) / 60);
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
            var _each, _lines , _currentLineIndex;
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
            it.add = function(video){
                var imgs = [];
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
                var currentLine = _lines[_currentLineIndex] || new line(_currentLineIndex);
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
            };
            return it;
        })();

        $('#zhou').on({
            init : function(event, each){
                $(this).removeData().html('');
                lines.init();
                lines.each(each);
                lines.add(videos.videos);
                var me = $(this);
                var totalLines = lines.lines();
                $.each(totalLines, function(i, n){
                    var last = false;
                    if(totalLines.length == i + 1){
                        last = true;
                    }
                    me.trigger('cline', [n, last]);
                });
                $(this).trigger('creatAdd');
                $(this).find('img').lazyload();
            },
            cline : function(event, newLine, last){
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
                        part.css({
                            'width' : width + 'px',
                            'left' : (left ? left : 0) + 'px'
                        });
                        left += width + 60;
                        var info = {
                            vindex : i,
                            start : n[0][0],
                            end : n[n.length - 1][1]
                        };
                        part.attr(info);
                        part.find('.z-img').html(imgsHtml);
                        var border = part.find('.z-border').css({
                            width : width + 'px'
                        });
                        var currentVideo = videos.info(i);
                        if(info['start'] > 0){
                            border.addClass('z-border-noleft');
                        }
                        if(info['end'] != currentVideo['time']){
                            border.addClass('z-border-noright');
                        }

                        part.find('.z-slider').data('info', info).width(width).slider({
                            range : 'min',
                            step : 0.1,
                            stop : function(event, ui){
                                ui.handle.blur();
                            },
                            slide : function(event, ui){
                                if(!$(this).hasClass('z-sliderCurrent')){
                                    $('.z-sliderCurrent').removeClass('z-sliderCurrent');
                                    $(this).addClass('z-sliderCurrent');
                                }
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
                if(last && left + 80 < line.width()){
                    $(this).trigger('creatAdd', [line, left - 3]);
                }
            },
            creatAdd : function(event, parent, left){
                if($(this).find('#video-add')[0]) return;
                if(!parent){
                    parent = $('<div class="z-line"></div>').appendTo(this);
                }
                $('<div id="video-add"></div>').appendTo(parent).css({
                    left : (left || 0) + 'px'
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
            $('#zhou').trigger('init', [$(this).attr('_type') * 1000]);

            var position = $(this).position();
            $('.z-type-mask').css({
                left : position.left - 1 + 'px',
                top : position.top + 'px'
            }).show();
        }).find('li[_type="5"]').addClass('on');

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

    (function(){
        var atpl = $('#ajax-tpl').val();
        var stpl = $('#selected-tpl').val();

        function page(options){
            this.total = options['total'];
            this.pageNum = options['pageNum'] || 15;
            this.currentPage = options['currentPage'] || 1;
            this.container = options['container'];
            this.totalNum = Math.ceil( this.total / this.pageNum );
            this.html = '';
            this.init();
        }
        $.extend(page.prototype, {
            init : function(){
                this.html = '<div class="p-box">';
                if(this.currentPage > 1){
                    this.html += '<span class="p-f" _p="1">｜&lt;</span>';
                    this.html += '<span class="p-p" _p="'+ (this.currentPage - 1) +'">&lt;&lt;</span>';
                }
                var cc;
                for(var i = 2; i > 0 ; i--){
                    cc = this.currentPage - i;
                    if(cc > 0){
                        this.html += '<span class="p-e" _p="'+ cc +'">'+ cc +'</span>';
                    }
                }
                this.html += '<span class="p-e p-c" _p="'+ this.currentPage +'">'+ this.currentPage +'</span>';
                for(var i = 1; i <= 2; i++){
                    cc = this.currentPage + i;
                    if(cc <= this.totalNum){
                        this.html += '<span class="p-e" _p="'+ cc +'">'+ cc +'</span>';
                    }
                }
                if(this.currentPage < this.totalNum){
                    this.html += '<span class="p-n" _p="'+ (this.currentPage + 1) +'">&gt;&gt;</span>';
                    this.html += '<span class="p-l" _p="'+ this.totalNum +'">&gt;|</span>';
                }
                this.html += '</div>';
                this.bind();
            },
            bind : function(){
                $(this.html).on('click', 'span', function(){
                    if($(this).hasClass('p-c')) return;
                    var box = $(this).closest('.p-box');
                    if(box.data('ajax')) return;
                    box.data('ajax', true);
                    $('#add-box').trigger('ajax', [parseInt($(this).attr('_p'))]);
                }).appendTo($(this.container).empty());
            }
        });

        var currentPage = {};
        var currentList = {};
        var addBox = $('#add-box').on({
            show : function(){
                $(this).trigger('log', ['start']);
                $(this).show().css({
                    top : $(document).scrollTop() + ($(window).height() - $(this).height()) / 2 + 'px'
                });
                $(this).trigger('selected');
                $(this).trigger('ajax');
            },
            hide : function(){
                $(this).hide();
                var me = $(this);
                setTimeout(function(){
                    me.trigger('log', ['end', true]);
                }, 10);
            },
            log : function(event, type, change){
                var ids = [];
                $.each(videos.videos, function(i, n){
                    ids.push(n['id']);
                });
                ids = ids.join('.');
                if(type == 'start'){
                    $(this).data('video-info', ids);
                    $('body').data('currentTop', $('body').scrollTop());
                }else if(type == 'end'){
                    var html = $('#videos-html');
                    !html[0] && (html = $('<div id="videos-html"></div>').appendTo('body').css({position : 'absolute', right : 0, top : 0, width : '300px', border : '1px solid red'}));
                    html.html($.param(videos.videos));
                    var oldIds = $(this).data('video-info');
                    if(ids != oldIds){
                        if(change){
                            $('#video-pn').trigger('init');
                            $('#zhou-type li.on').trigger('click');
                            $('body').scrollTop($('body').data('currentTop'));
                            $(this).removeData('video-info');
                        }else{
                            $('.close-s').show().siblings().hide();
                        }
                    }else{
                        $('.close-x').show().siblings().hide();
                    }

                }
            },
            ajax : function(event, pp, num){
                pp = pp || 1;
                num = num || 15;
                var me = $(this);
                $('.vlist').html('<img src="'+ RESOURCE_URL + 'loading2.gif" style="width:30px;"/>');
                var url = 'run.php?mid='+gMid+'&a=select_videos&start='+ (pp - 1) * 15 +'&num=' + num;
                $.getJSON(url, function(json){
                    json = json[0];
                    if(json['video_info'].length){
                        currentPage[json['current_page']] = json;

                        var html = '';
                        $.each(json['video_info'], function(i, n){
                            currentList[n['id']] = n;
                            html += replaceTpl(atpl, {
                                'id' : n['id'],
                                'img' : n['img'],
                                'title' : n['title'],
                                'duration' : n['duration_format']
                            });
                        });
                        $('.vlist').html(html);
                        me.trigger('check');
                        new page({
                            total : json['total_num'],
                            pageNum : num,
                            currentPage : pp,
                            container : '.pages'
                        });
                    }
                });
            },
            selected : function(event){
                var html = '';
                $.each(videos.videos, function(i, n){
                    html += replaceTpl(stpl, n);
                });
                $('.slist').html(html).find('li:first-child .sd').remove();
                $(this).trigger('num');
            },
            add : function(event, id){
                var info = currentList[id];
                videos.put({
                    'id' : info['id'],
                    'src' : info['hostwork'].replace('vfile1', 'mcp') + '/vod/' + info['video_path'] + info['video_filename'],
                    'fen' : [info['width'], info['height']],
                    'zhen' : parseInt(info['frame_rate']),
                    'time' : info['duration'],
                    'img' : info['img'],
                    'title' : info['title']
                });
                $(this).trigger('selected');
                $(this).trigger('check');
                $(this).trigger('log', ['end']);
            },
            remove : function(event, index){
                videos.remove(index);
                $(this).trigger('num');
                $(this).trigger('check');
                $(this).trigger('log', ['end']);
            },
            num : function(){
                $('#selected-box .title span').html(videos.videos.length);
            },
            check : function(){
                $('.vlist .on').removeClass('on');
                $.each(videos.videos, function(i, n){
                    $('.vlist li[_id="'+ n['id'] +'"]').addClass('on');
                });
            }
        });
        addBox.on('click', '.close', function(){
            $('#add-box').trigger('hide');
        });
        addBox.on('click', '.slist .sd', function(){
            var li = $(this).closest('li');
            jConfirm('确定要删除？', '删除提示', function(result){
                if(result){
                    $('#add-box').trigger('remove', [$('.slist li').index(li[0])]);
                    li.remove();
                }
            });
        });
        addBox.on('click', '.vlist li', function(){
            if($(this).hasClass('on')) return;
            $(this).addClass('on');
            $('#add-box').trigger('add', [$(this).attr('_id')]);
        });
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

(function($){
    var tip = {
        init : function(){
            if(!this.tip){
                var tip = $('#my-tip');
                if(!tip[0]){
                    tip = $('<div/>', {id : 'my-tip'}).css({
                        position : 'absolute',
                        left : 0,
                        top : 0,
                        padding : '3px 10px',
                        background : 'green',
                        color : '#fff',
                        'z-index' : 1000
                    }).appendTo('body').on({
                        _show : function(){
                            $(this).css('opacity', 1).show();
                        },
                        _hide : function(){
                            $(this).animate({
                                opacity : 0
                            }, 1000, function(){
                            $(this).hide();
                            });
                        },
                        _html : function(event, string){
                            $(this).html(string);
                            },
                        _offset : function(event, offset){
                            $(this).css({
                                left : offset.left - $(this).outerWidth() / 2 + 'px',
                                top : offset.top - $(this).outerHeight() / 2 + 'px'
                            });
                        }
                    });
                }
                this.tip = tip;
            }
        },
        show : function(options){
            this.init();
            this.tip.trigger('_html', [options['string']])
            .stop().trigger('_show')
            .trigger('_offset', [options['offset']]);
            clearTimeout(this.timer);
            var me = this;
            this.timer = setTimeout(function(){
                me.tip.trigger('_hide');
            }, 1000);
        }
    }
    $.fn.tip = function(options){
        options = $.extend({
            center : true,
            offset : null
        }, options);
        return this.each(function(){
            var offset = options['offset'];
            var center = options['center'];
            var string = options['string'];
            var position = $(this).offset();
            if(center){
                position.left += $(this).outerWidth() / 2;
                position.top += $(this).outerHeight() / 2;
            }
            if(offset){
                position.left += offset.left;
                position.top += offset.top;
             }
            tip.show({
                string : string,
                offset : position
             });
       });
    }
})(jQuery);

(function($){
    var options = {

    };
    $.fn.fangSelect = function(){
        return this.each(function(){

        });
    };
})(jQuery);

$(function(){
    $(window).on('resize', function(){

        $('.v-top').width(function(){
            return $('body').width() - 40;
        });
        $('#video-slice').width(function(){
            return $('body').width() - 4 - 20 - $('.v-left').outerWidth(true) - 2 * parseInt($('.video').css('padding-left'));
        });
    }).trigger('resize').focus();

    var timer = setInterval(function(){
        if($('video')[0]){
            clearInterval(timer);
            $('<input />').appendTo('body').focus().remove();
            $('body').scrollTop(0);
        }
    }, 10);

    /*$('.option-iframe-back').click(function(){
        if(top != self){
            top.$('#livwinarea').trigger('iclose');
        }
    });*/
});
