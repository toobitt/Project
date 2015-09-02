(function($){
    $.widget('video.list', {
        options: {
            mainVideoId : 0,
            video : '',
            pians : '',
            listInfo : null,
            template : '',
            ajaxUrl : 'run.php?mid=' + gMid + '&a=select_videos&start={{start}}&num=15&_type={{leixing}}&title={{title}}&date_search={{date}}',
            ajaxTemplate : '',
            ajaxLeixingUrl : 'run.php?mid=' + gMid + '&a=get_video_node&fid={{fid}}',
            ajaxSelectSaveUrl : 'run.php?mid=' + gMid + '&a=save_added_videos',
            ajaxSelectDelUrl : 'run.php?mid=' + gMid + '&a=delete_added_videos'
        },

        cacheJSON : {},

        _create : function(){
            this.video = $(this.options.video);
            this.pians = $(this.options.pians);
            var root = this.element;
            this.selectBox = root.find('#video-select');
            this.ajaxBox = root.find('#video-ajax');
            this.leixingBox = root.find('#video-leixing');
            this.addBtn = root.find('#video-add');
            this.searchBox = root.find('#video-search');

            this.date = 1;
            this.leixing = -1;
            this.title = '';

            var me = this;
            if(me.options.listInfo){
                $.extend(me.cacheJSON, me.options.listInfo);
                $.each(me.options.listInfo, function(i, n){
                    me._createEach(n);
                });
            }

        },

        _createEach : function(eachInfo){
            $(this._replace(this.options.template, {
                id : eachInfo['id'],
                img : eachInfo['img'],
                title : eachInfo['title']
            })).insertBefore(this.addBtn).data('info', eachInfo);
        },

        _checkEach : function(id){
            return !!this.selectBox.find('.vb-each[_id="'+ id +'"]').length;
        },

        triggerEach : function(id){
            this.selectBox.find('.vb-each[_id="'+ id +'"]').trigger('click');
        },

        getEachInfo : function(id){
            return this.selectBox.find('.vb-each[_id="'+ id +'"]').data('info') || {};
        },

        _init : function(){
            var me = this;

            this.ajaxBox.on({
                mousewheel : function(event){
                    event.preventDefault();
                }
            });

            this.ajaxBox.jScrollPane({
                //autoReinitialise: true
            });

            this._on({
                'click #ajax-more' : '_more',
                'click #video-add' : 'openSearchBox',
                'click .vb-close' : 'closeSearchBox'
            });

            this.selectBox.on({
                mouseenter : function(){
                    if(!me.pians.pians('checkByVideoId', $(this).attr('_id'))){
                        $(this).find('.vb-each-close').show();
                    }
                },
                mouseleave : function(){
                    $(this).find('.vb-each-close').hide();
                },
                click : function(){
                    if(!$(this).hasClass('on')){
                        me.video.video('changeVideo', $(this).data('info'));
                        $(this).addClass('on').siblings('.on').removeClass('on');
                    }
                }
            }, '.vb-each');

            this.selectBox.on('click', '.vb-each-close', function(event){
                var each = $(this).closest('.vb-each');
                var id = each.attr('_id');
                me._delSelect(id);
                each.hasClass('on') && each.siblings('.vb-each').eq(0).trigger('click');
                each.off().removeData();
                each.addClass('del');
                setTimeout(function(){
                    each.addClass('delnext');
                    setTimeout(function(){
                        each.remove();
                    }, 400);
                }, 400);
                me._removeSelect(id);
                return false;
            });

            this.ajaxBox.on({
                click : function(){
                    if($(this).hasClass('selected')){
                        return false;
                    }
                    var id = $(this).attr('_id');
                    if(!me._checkEach(id)){
                        me._saveSelect(id);
                        var info = me._getCacheJSON(id);
                        var dataInfo = me._infoChange(info);
                        me._createEach(dataInfo);
                    }
                    me.triggerEach(id);
                    me.closeSearchBox();
                    me._addSelectSpan($(this));
                }
            }, '.vb-item');

            this._leixing().success(function(){
                me.leixingBox.find('.leixing-inner').html(me.leixingHtml).find('ul').prepend('<li _id="0">全部内容</li>').find('li').eq(0).trigger('click');
            });

            this.leixingBox.on({
                click : function(event){
                    var _this = $(this);
                    var li = $(this).closest('li');
                    var fid = li.attr('_id');
                    var fname = li.attr('_name');
                    var ul = _this.closest('ul');
                    ul.nextAll('ul').remove();
                    ul.after('<ul><li><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li></ul>');
                    me._leixing(fid).success(function(){
                        ul.next().replaceWith(me.leixingHtml);
                        ul.next().prepend('<li _id="'+ fid +'" class="back">返回&nbsp;'+ fname +'</li>').find('li').eq(1).trigger('click');
                    });
                    me._moveLeixing(true);
                    return false;
                }
            }, 'span');

            this.leixingBox.on({
                click : function(event){
                    var selfUl = $(this).closest('ul');
                    selfUl.prev().find('li[_id="'+ $(this).attr('_id') +'"]').trigger('click');
                    me._moveLeixing(false, function(){
                        selfUl.remove();
                    });
                    return false;
                }
            }, '.back');

            this.leixingBox.on({
                click : function(event){
                    if($(this).hasClass('on')){
                        return false;
                    }
                    $('.on', me.leixingBox).removeClass('on');
                    $(this).addClass('on');
                    me._clickLeixing($(this).attr('_id'));
                }
            }, 'li:not(.back)');

            $('.vb-title', this.element).on({
                blur : function(){
                    me._clickTitle($(this).val());
                    return false;
                },

                'keydown keyup' : function(event){
                    if(event.keyCode == 32){
                        event.stopPropagation();
                    }
                }
            });

            $('.vb-date', this.element).on({
                change : function(){
                    me._clickDate($(this).val());
                }
            });
        },

        openSearchBox : function(){
            this.element.addClass('hover');
        },

        closeSearchBox : function(){
            this.element.removeClass('hover');
        },

        _saveSelect : function(id){
            $.post(
                this.options.ajaxSelectSaveUrl,
                {main_video_id : this.options.mainVideoId, vodinfo_id : id}
            );
        },

        _delSelect : function(id){
            $.post(
                this.options.ajaxSelectDelUrl,
                {main_video_id : this.options.mainVideoId, vodinfo_id : id}
            );
        },

        changeVideo : function(id){
            this.triggerEach(id);
        },

        _leixing : function(fid){
            fid = fid || 0;
            var url = this._replace(this.option('ajaxLeixingUrl'), {fid : fid});
            var me = this;
            return $.getJSON(url, function(json){
                me._createLeixing(json);
            });
        },

        _createLeixing : function(info){
            var html = '<ul>';
            $.each(info, function(i, n){
                var child = '';
                if(!parseInt(n['is_last'])){
                    child += '<span>》</span>';
                }
                html += '<li _id="'+ n['id'] +'" _name="'+ n['name'] +'">'+ n['name'] + child +'</li>';
            });
            html += '</ul>';
            this.leixingHtml = html;
        },

        _moveLeixing : function(state, cb){
            var inner = this.leixingBox.find('.leixing-inner');
            var uls = inner.find('ul');
            var ulNum = uls.length;
            var ulWidth = uls.eq(0).outerWidth(true);
            inner.animate({
                left : - (state ? ulNum - 1 : ulNum - 2) * ulWidth + 'px'
            }, 250, function(){
                cb && cb();
            });
        },

        _clickLeixing : function(leixing){
            if(this.leixing != leixing){
                this.leixing = leixing;
                this._ajaxBefore();
            }
        },

        _clickTitle : function(title){
            if(!this.title && !title){
                return;
            }
            if(this.title != title){
                this.title = title;
                this._ajaxBefore();
            }
        },

        _clickDate : function(date){
            if(this.date != date){
                this.date = date;
                this._ajaxBefore();
            }
        },

        _ajaxBefore : function(){
            this.ajaxBox.data('jsp').getContentPane().html('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>');
            this._more();
        },

        _ajax : function(){
            var me = this;
            var ajaxBox = this.ajaxBox;
            var ajaxTemplate = this.options.ajaxTemplate;
            var url = this.options.ajaxUrl;
            var length = this.ajaxBox.find('.vb-item').length;
            var cacheJSON = this.cacheJSON;
            return $.getJSON(
                this._replace(url, {start : length, leixing : this.leixing, title : this.title, date : this.date}),
                function(json){
                    json = json[0];
                    var pane = ajaxBox.data('jsp').getContentPane();
                    if(!json['video_info']){
                        me.searchResult = null;
                        pane.html('<div style="margin:10px;">搜索结果为空</div>');
                        return;
                    }

                    var html = '';
                    var tmpJSON = {};
                    var number = 0;
                    $.each(json['video_info'], function(i, n){
                        tmpJSON[n['id']] = n;
                        html += me._replace(ajaxTemplate, {
                            'id' : n['id'],
                            'img' : n['img'],
                            'title' : n['title'],
                            'duration' : n['duration_format']
                        });
                        number++;
                    });
                    me.searchResult = number;
                    pane[length ? 'append' : 'html'](html);
                    me._addSelect();
                    $.extend(cacheJSON, tmpJSON);
                }
            );
        },

        _addSelect : function(){
            var me = this;
            var ajaxBox = me.ajaxBox;
            this.selectBox.find('.vb-each').each(function(){
                var id = $(this).attr('_id');
                var item = ajaxBox.find('.vb-item[_id="'+ id +'"]:not(.selected)');
                item[0] && me._addSelectSpan(item);
            });
        },

        _addSelectSpan : function(item){
            item.addClass('selected').append('<span class="vb-item-st"></span>');
        },

        _removeSelect : function(id){
            var item = this.ajaxBox.find('.vb-item.selected[_id="'+ id +'"]');
            if(item[0]){
                item.removeClass('selected').find('.vb-item-st').remove();
            }
        },

        _more : function(){
            var me = this;
            this._ajax().success(function(){
                if(!me.searchResult){
                    return;
                }
                var api = me.ajaxBox.data('jsp');
                var more = $(me.ajaxBox.find('#ajax-more')[0] || '<div id="ajax-more">更多<span>&gt;&gt;</span></sapn></div>').appendTo(api.getContentPane());
                api.reinitialise();
                more[me.searchResult == 15 ? 'show' : 'hide']();
                me.searchResult = null;
            });
        },

        _getCacheJSON : function(id){
            return this.cacheJSON[id];
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/g, function(all, match){
                return data[match] || '';
            });
        },

        _infoChange : function(info){
            return {
                'id' : info['id'],
                'src' : info['hostwork'].replace('vfile1', 'mcp') + '/vod/' + info['video_path'] + info['video_filename'],
                'fen' : [info['width'], info['height']],
                'zhen' : parseInt(info['frame_rate']),
                'time' : info['duration'],
                'img' : info['img'],
                'title' : info['title']
            };
        },

        _destroy : function(){
            this.selectBox.off();
            this.ajaxBox.off();
            this.element.find('.vb-each').removeData().off();

        }
    });
})(jQuery);
