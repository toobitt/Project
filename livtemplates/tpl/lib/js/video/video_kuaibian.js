$(function(){

    var canplay = false, isPlayIng = false;
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
            if(isPlayIng){
                timer = window.requestAFrame(function(){
                    video.video('timeupdate');
                    it.loop();
                });
            }
        };
        it.start = function(){
            isStart = true;
            this.loop();
        };
        it.stop = function(){
            isStart = false;
            timer && window.cancelAFrame(timer) && (timer = null);
        };
        it.isStart = function(){
            return isStart;
        };
        return it;
    }();


    $.each(videoInfos, function(i, n){
        videos.put(n);
    });

    var video = $('#video').video({
        autoPlay : false,
        autobuffer : true,
        customEvents : {
            '_change.video' : function(event, info){
                $(this).video('option', 'zhen', info['zhen']);
                $(this).data('info', info);
                var fen = info['fen'];
                var canvas = $.createCanvas({
                    width : fen[0],
                    height : fen[1]
                });
                $(this).data('canvas', canvas);
                $(this).attr({
                    src : info['src'],
                    poster : info['img']
                });
                this.load();
                if(isPlayIng){
                    this.play();
                }
                $('#video-slice').pians('change', info);
            },
            '_pian.video' : function(){
                $('#video-slice').pians('slide');
            },
            'canplay.video' : function(){
                canplay = true;
                //$.browser.chrome && !Timer.isStart() && Timer.start();
            },
            'play.video' : function(){
                isPlayIng = true;
            },
            'pause.video' : function(){
                isPlayIng = false;
            },
            'timeupdate.video' : function(){
                if($(this).data('prev-or-next')){
                    $(this).data('prev-or-next', false);
                    $(this).trigger('_pian');
                }
            },
            'emptied.video' : function(){
            },
            'seeked.video' : function(){
            },
            'error.video' : function(){
                //console.log('error');
                //this.load();
            }
        }
    });

    $('#select-box').select({
        'eachTemplate' : $('#select-tpl').val()
    });

    $('#video-list').list({
        mainVideoId : mainVideoId,
        video : '#video',
        pians : '#video-slice',
        listInfo : videoInfos,
        template : $('#each-tpl').val(),
        ajaxTemplate : $('#ajax-tpl').val()
    });

    $('#video-slice').pians({
        mainVideoId : mainVideoId,
        video : '#video',
        videoSlider : '#video-box .ui-video-scrubber-slider',
        progress : '#video-box .ui-video-progress',
        pianTou : $('#pian-tou-tpl').val(),
        pianHua : $('#pian-hua-tpl').val(),
        pianWei : $('#pian-wei-tpl').val(),
        pianPlace : $('#pian-place-tpl').val(),
        pianTemplate : $('#pian-tpl').val(),
        yulan : '.yulan-box',
        select : '#select-box',
        list : '#video-list'
    });

    $('.yulan-box').yulan({
        video : '#video',
        pians : '#video-slice'
    });

    $('#video-list').find('.vb-each:first').trigger('click').find('.vb-each-close').remove();



    /*var helpBtn = $('.help-btn').click(function(){
        helpTimeout && (clearTimeout(helpTimeout)) && (helpTimeout = null);
        $('#help-box').toggleClass('zk');
    });
    var helpTimeout = setTimeout(function(){
        helpBtn.trigger('click');
    }, 1500);

    $(window).on('resize', function(){
        var width = $('body').width() - 40;
        $('.video').width(width);
        $('#video-slice').width(function(){
            return width - 20 - $('.v-left').outerWidth(true);
        });
    }).trigger('resize');*/

    /*var timer = setInterval(function(){
        if($('video')[0]){
            clearInterval(timer);
            $('<input />').appendTo('body').focus().remove();
            $('body').scrollTop(0);
        }
    }, 10);*/

    $('.option-iframe-back').click(function(){
        if(top != self){
            top.$('#livwinarea').trigger('iclose');
        }
    });


    $('.vb-date').mySelect();
});


(function($){
    var defaultOptions = {
        classname : 'my-select',
        hoverClass : 'hover',
        tpl : '<div class="{{classname}}"><span value="{{value}}">{{name}}</span><ul></ul></div>',
        liTpl : '<li value="{{value}}">{{name}}</li>'
    };
    function replace(tpl, data){
        return tpl.replace(/{{([a-zA-Z0-9]+)}}/g, function(all, match){
            return data[match] || '';
        });
    }
    $.fn.mySelect = function(options){
        options = $.extend({}, defaultOptions, options);
        this.each(function(){
            var me = $(this);
            $(this).hide();
            var data = [];
            $(this).find('option').each(function(){
                data.push([$(this).attr('value'), $(this).text()]);
            });
            var mySelect = $(replace(options['tpl'], {
                classname : options['classname'],
                value : data[0][0],
                name : data[0][1]
            })).insertAfter(this);
            var lis = '';
            $.each(data, function(i, n){
                lis += replace(options['liTpl'], {
                    value : n[0],
                    name : n[1]
                });
            });
            mySelect.find('ul').html(lis);

            mySelect.on({
                hover : function(){
                    var ul = $(this).find('ul');
                    var state = $(this).data('state');
                    $(this).data('state', !state);
                    ul.stop()[state ? 'slideUp' : 'slideDown'](150);
                }
            });

            mySelect.on({
                click : function(){
                    var box = $(this).parent().parent();
                    var name = $(this).html();
                    var value = $(this).attr('value');
                    box.find('>span').trigger('_set', {
                        name : name,
                        value : value
                    });
                    box.find('ul').hide();
                    me.val(value).trigger('change');
                },
                hover : function(){
                    $(this).toggleClass(options['hoverClass']);
                }
            }, 'li');

            mySelect.on({
                _set : function(event, data){
                    if($(this).attr('value') == data['value']){
                        return false;
                    }
                    $(this).html(data['name']).attr('value', data['value']);
                }
            }, '>span')
        });
    }
})(jQuery);

