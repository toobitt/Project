(function($){
    $.widget('video.pians', {
        options: {
            mainVideoId : 0,
            video : null,
            videoSlider : null,
            progress : null,
            pianTou : '',
            pianHua : '',
            pianWei : '',
            pianPlace : '',
            pianTemplate : '',
            yulan : '',
            select : null,
            list : null,
            ajaxUrl : 'run.php?mid=' + gMid + '&a=select_videos&type={{type}}&num=15',
            saveUrl : 'run.php?mid=' + gMid + '&a=fast_auto_save',
            deleteUrl : 'run.php?mid=' + gMid + '&a=fast_auto_save_delete',
            saveOrderUrl : 'run.php?mid=' + gMid + '&a=fast_auto_save_order'
        },

        _create : function(){

            var root = this.element;
            this.yulanBox = $(this.options.yulan);
            this.select = $(this.options.select);
            this.list = $(this.options.list);

            this.sliceOut = this.element.parent();
            this.pianOption = $('#pian-option');
            this.pianOtherOption = $('#pian-other-option');

            this.totalInfo = $('.video-slice-info span');

            this.sliderHelp = $('#slice-slider-help');

            this.yulanBtn = $('.yulan').hide();
            this.baocunBtn = $('.baocun').hide();

        },

        _createInit : function(){
            var me = this;
            var pianTou = this.options.pianTou;
            var pianHua = this.options.pianHua;
            var pianWei = this.options.pianWei;
            var pianPlace = this.options.pianPlace;
            if($.isEmptyObject(vcrData)){
                $(pianTou + pianPlace + pianWei).appendTo(this.element);
                $('.pian-tou, .pian-wei', this.element).each(function(){
                    $(this).attr('_hash', me._hash());
                });
            }else{
                vcrData = vcrData.sort(function(v1, v2){
                    return v1.order_id > v2.order_id;
                });
                var pianTouData, pianWeiData;
                var initPianTHW = function(which, info){
                    var type = info['vcr_type'];
                    if(type == 1){
                        type = 'tou';
                    }else if(type == 2){
                        type = 'hua';
                    }else if(type == 3){
                        type = 'wei';
                    }
                    me.select.select('setCache', [info], type);
                    return $(which).attr({
                        '_hash' : info['hash'],
                        '_id': info['id']
                    }).addClass('pian-other-set').append('<img src="'+ info['img'] + '"/>');
                };
                $.each(vcrData, function(i, n){
                    var type = parseInt(n['vcr_type']);
                    if(type == 1){
                        pianTouData = n;
                    }else if(type == 3){
                        pianWeiData = n;
                    }else if(type == 2){
                        initPianTHW(pianHua, n).appendTo(me.element);
                    }else if(type == 4){
                        me._pian(null, n);
                    }
                });
                (pianTouData ? initPianTHW(pianTou, pianTouData) : $(pianTou).attr('_hash', me._hash())).prependTo(this.element);
                $(pianPlace).appendTo(this.element);
                (pianWeiData ? initPianTHW(pianWei, pianWeiData) : $(pianWei).attr('_hash', me._hash())).appendTo(this.element);

                this.element.find('.pian-duan').each(function(){
                    var next = $(this).next();
                    if(next.hasClass('pian-place')){
                        return false;
                    }
                    if(!next.hasClass('pian-hua')){
                        $(pianHua).insertAfter(this);
                    }
                });

                this._reflow(true);
                this._totalInfo();
            }
        },

        _init : function(){

            var me = this;
            this.sliceOut.jScrollPane({
                //autoReinitialise: true
            });

            this._on({
                'click .pian-place' : '_pian'
            });

            this.element.on({
                click : function(){
                    var pian = $(this).closest('.pian-other').removeClass('pian-other-set');
                    me._deleteTHW(pian.attr('hash'));
                    me.remove();
                    me._reflow();
                    return false;
                }
            }, '.pian-other .pian-del');

            this.element.on({
                click : function(){
                    if($(this).hasClass('pian-other-set')){
                        return false;
                    }
                    me.pianOtherOption.trigger('mouseenter');
                    me.openSelect();
                },

                mouseenter : function(){
                    if($(this).hasClass('on')) return;
                    me.pianOtherOption.trigger('_show', [$(this)]);
                },

                mouseleave : function(){
                    me.pianOtherOption.trigger('_hide', [true]);
                },

                _select : function(){
                    $(this).addClass('on');
                },

                _unselect : function(){
                    $(this).removeClass('on');
                },

                _set : function(event, info){
                    $(this).addClass('pian-other-set').attr('_id', info['id']);
                    var img = $('img', this);
                    if(img[0]){
                        img.attr('src', info['img']);
                    }else{
                        $('<img/>', {src : info['img']}).appendTo(this);
                    }
                    var type = me.select.select('types2number', $(this).attr('type'));
                    me._saveTHW($(this).attr('_hash'), info['id'], type);
                    me._reflow();
                },

                _delete : function(){
                    $(this).removeClass('pian-other-set').removeAttr('_id').find('img').remove();
                    me._deleteTHW($(this).attr('_hash'));
                    me._reflow();
                }
            }, '.pian-tou, .pian-hua, .pian-wei');

            this.pianOption.on({
                _show : function(event, position){
                    var pLeft = position.left - 15;
                    var pTop = position.top - 15;
                    $(this).show().css({
                        left : pLeft + 'px',
                        top : pTop + 'px'
                    });
                },

                _hide : function(){
                    $(this).hide().removeData();
                },

                _setPian : function(event, pian){
                    $(this).data('pian', pian);
                },

                _getPian : function(){
                    return $(this).data('pian');
                },

                mouseleave : function(){
                    $(this).trigger('_hide');
                }
            })
            .on({
                click : function(){
                    me.doPianOption('editOption');
                }
            }, '.pian-option-edit')
            .on({
                click : function(){
                    me.doPianOption('deleteOption');
                    me._reflow();
                }
            }, '.pian-option-del');


            this.pianOtherOption.on({
                _show : function(event, target){
                    $(this).data('target', target);
                    target.trigger('_select');
                    var position = target.offset();
                    var pTop = position.top - 15;
                    var pLeft = position.left - 15;
                    var width = target.outerWidth();
                    var left = pLeft - (($(this).outerWidth() - width) / 2);
                    var selfWidth = $(this).outerWidth(true);
                    var windowWidth = $(window).width();
                    $(this).show();
                    if(left < 0){
                        left = 0;
                    }else if(left + selfWidth > windowWidth){
                        left = windowWidth - selfWidth;
                    }
                    var jianLeft = width / 2 + pLeft - left - 10;
                    $('.poo-jian', this).css('left', jianLeft + 'px');
                    $(this).css({
                        left : left + 'px',
                        top : pTop + target.outerHeight() + 5 + 'px'
                    });

                    var type;
                    if(target.hasClass('pian-other-set')){
                        type = 'option';
                    }else{
                        type = target.attr('type');
                    }
                    $(this).trigger('_showOption', [type]);
                },

                _hide : function(event, delay){
                    var _this = $(this);
                    var hide = function(){
                        var target = _this.data('target');
                        target && target.trigger('_unselect');
                        _this.removeData('target');
                        _this.hide();
                    };
                    if(delay){
                        setTimeout(function(){
                            if(_this.data('mouseenter')){
                                return false;
                            }
                            hide();
                        }, 100);
                    }else{
                        hide();
                    }
                },

                _showOption : function(event, type){
                    $(this).find('.poo-type, .poo-option').hide();
                    $(this).find('.poo-' + type).show();
                },

                mouseenter : function(){
                    $(this).data('mouseenter', true);
                },

                mouseleave : function(){
                    $(this).removeData('mouseenter').trigger('_hide');
                }
            })
            .on({
                click : $.proxy(this, 'openSelect')
            }, '.poo-type')
            .on({
                click : $.proxy(this, 'openSelect')
            }, '.poo-change')
            .on({
                click : $.proxy(this, 'deleteSelect')
            }, '.poo-del');

            (function(){
                var help = me.sliderHelp.on({
                    _show : function(){
                        $('#video-box').find('.ui-icon-bujin, .ui-video-seek-prev, .ui-video-seek-next').hide();
                        $(this).show();
                    },

                    _hide : function(){
                        $('#video-box').find('.ui-icon-bujin, .ui-video-seek-prev, .ui-video-seek-next').show();
                        $(this).hide();
                    },

                    _time : function(){
                        $(this).find('.ssh-time').html($(me.options.progress).text());
                    }
                });
                help.on({
                    click : function(){
                        if(!$('.pian-slider-current .ui-state-current').length){
                            return false;
                        }
                        var isLeft = $(this).hasClass('ssh-left');
                        $(me.options.video).video(isLeft ? 'prev' : 'next');
                        $(this).parent().trigger('_time');
                        return false;
                    }
                }, '.ssh-left, .ssh-right');
                var slider = $(me.options.videoSlider);
                var offset = slider.offset();
                var offLeft = offset.left - 15;
                var offTop = offset.top - 15;
                var width = slider.width();
                help.trigger('_show');
                var selfWidth = help.outerWidth(true);
                help.css({
                    left : offLeft + (width - selfWidth) / 2 + 'px',
                    top : offTop + 10  + 'px'
                });
                help.trigger('_hide');
            })();

            this._createInit();

            //this._totalInfo();

            this.yulanBtn.click(function(){
                me.yulanBox.yulan('open');
                me.yulanBox.yulan('option', 'info', me.yulanData);
            });

            this.baocunBtn.click(function(){
                var me = $(this);
                if(me.data('submit')){
                    return false;
                }
                me.data('submit', true).html('提交中...');
                $.get(
                    'run.php?mid=' + gMid + '&a=submit_fast_edit&main_video_id=' + mainVideoId,
                    function(){
                        me.data('submit', false).html('保存');
                        $('.option-iframe-back').trigger('click');
                    }
                );
            });
        },

        openSelect : function(){
            var target = this.pianOtherOption.data('target');
            this.pianOtherOption.trigger('_hide');
            this.select.select('option', 'target', target).select('show');
        },

        deleteSelect : function(){
            var target = this.pianOtherOption.data('target');
            target && target.trigger('_delete');
            this.pianOtherOption.trigger('_hide');
        },

        openOption : function(pian, position){
            this.pianOption.trigger('_setPian', [pian]).trigger('_show', [position]);
        },

        closeOption : function(){
            this.pianOption.trigger('_hide');
        },

        doPianOption : function(type){
            var pian = this.pianOption.triggerHandler('_getPian');
            pian && pian.pian(type);
            this.pianOption.trigger('_hide');
        },

        openHelp : function(){
            this.sliderHelp.trigger('_show');
        },

        closeHelp : function(){
            this.sliderHelp.trigger('_hide');
        },

        _reflow : function(noNeedCheckTotalInfo){
            this.sliceOut.data('jsp').reinitialise();
            var width = 0;
            this.element.find('.pian').each(function(){
                width += $(this).outerWidth(true);
            });
            var windowWidth = $(window).width();
            if(width > windowWidth){
                this.element.width(width);
            }
            this.sliceOut.data('jsp').reinitialise();
            if(!noNeedCheckTotalInfo){
                this._totalInfo();
            }
        },

        totalInfoCheck : function(){
            this._totalInfo();
        },

        _totalInfo : function(){
            var me = this;
            var yulanData = this.yulanData = [];
            var pians = 0;
            var wanzheng = true;
            this.element.find('.pian').each(function(){
                var info;
                if($(this).hasClass('pian-other-set')){
                    var id = $(this).attr('_id');
                    var type = $(this).attr('type');
                    var infoTmp = me.select.select('getCache', id, type);
                    info = {
                        start : 0,
                        end : infoTmp['time'],
                        id : infoTmp['id'],
                        hash : $(this).attr('_hash'),
                        info : infoTmp
                    };
                    pians++;
                    yulanData.push(info);
                }else{
                    info = $(this).data('info');
                    if(info){
                        if(info['end'] > 0){
                            info['info'] = me.list.list('getEachInfo', info['id']);
                            pians++;
                            yulanData.push(info);
                        }else{
                            wanzheng = false;
                        }
                    }
                }
            });
            var totalTime = 0;
            $.each(yulanData, function(i, n){
                totalTime += n['end'] - n['start'];
            });
            var totalTimeString = this._duration(totalTime);
            this.totalInfo.html(totalTimeString);
            this.yulanBtn[pians ? 'show' : 'hide']();
            this.baocunBtn[pians && wanzheng ? 'show' : 'hide']();
        },

        _duration : function(seconds){
            seconds /= 1000;
            var h = parseInt(seconds / 3600);
            var m = parseInt((seconds - h * 3600) / 60);
            var s = parseInt(seconds % 60);
            return (h ? h + '\'' : '') + (m ? m + '\'' : '') + (s ? s + '"' : '');
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/g, function(all, match){
                return data[match] || '';
            });
        },

        _pian : function(event, initInfo){
            var options = {
                video : this.options.video,
                videoSlider : this.options.videoSlider,
                progress : this.options.progress,
                pians : this.element,
                mainVideoId : this.options.mainVideoId
            };
            if(initInfo){
                options['initInfo'] = initInfo;
            }
            var place = this.element.find('.pian-place');
            var pian = $(this.options.pianTemplate).pian(options);
            if(place[0]){
                pian.insertBefore(place).pian('editOption');
                if(pian.prev().is('.pian-duan')){
                    $(this.options.pianHua).attr('_hash', this._hash()).insertBefore(pian);
                }
                this._reflow();
            }else{
                pian.appendTo(this.element);
            }
        },


        _saveTHW : function(hash, id, type){
            var me = this;
            var postData = {
                main_video_id : this.options.mainVideoId,
                vodinfo_id : id,
                hash_id : hash,
                vcr_type : type
            };
            $.post(
                this.options.saveUrl,
                postData
            ).success(function(){
                me.saveOrder();
            });
        },

        _deleteTHW : function(hash){
            var me = this;
            $.getJSON(
                this.options.deleteUrl,
                {hash_id : hash}
            ).success(function(){
                me.saveOrder();
            });
        },

        saveOrder : function(){
            var index = 0;
            var hash = [];
            var order = [];
            $('.pian', this.element).each(function(){
                if($(this).hasClass('pian-other-set') || $(this).hasClass('pian-duan')){
                    hash.push($(this).attr('_hash'));
                    order.push(++index);
                }
            });
            $.post(
                this.options.saveOrderUrl,
                {order_id : order, hash_id : hash}
            );
        },

        remove : function(){
            $('.pian-hua', this.element).each(function(){
                if($(this).hasClass('pian-other-set') || ($(this).prev().is('.pian-duan') && $(this).next().is('.pian-duan'))){
                    return;
                }
                $(this).remove();
            });
        },

        change : function(info){
            $('.pian-duan', this.element).filter(function(){
                return parseInt($(this).attr('_id')) != info['id'];
            });
        },

        slide : function(){
            $('.pian-current', this.element).pian('slide');
        },

        play : function(){
            this.currentPian = $('.pian-current', this.element).trigger('click');
        },

        pause : function(){
            if(this.currentPian){
                this.currentPian.trigger('click');
                this.currentPian = null;
            }
        },


        clone : function(hash){
            return this.element.find('.pian[_hash="'+ hash +'"]').clone();
        },

        checkByVideoId : function(id){
            return !!this.element.find('.pian[_id="'+ id +'"]').length;
        },

        changeVideo : function(id){
            this.list.list('changeVideo', id);
        },

        _hash : function(){
            return (+new Date()) + '' + Math.ceil(Math.random() * 1000);
        },

        _destroy : function(){
            this.addBtn.off();
            this.element.off();
        }
    });
})(jQuery);
