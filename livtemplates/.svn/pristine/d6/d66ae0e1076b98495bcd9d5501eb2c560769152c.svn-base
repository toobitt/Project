(function($){
    $.widget('video.select', {
        options: {
            target : null,
            eachTemplate : '',
            ajaxUrl : 'run.php?mid=' + gMid + '&a=select_videos&type={{type}}&num=15'
        },

        caches : {},
        cachesType : {},

        _create : function(){
            var root = this.element;
            this.loading = root.find('.ts-loading').attr('src', RESOURCE_URL + 'loading2.gif');
            this.inner = root.find('.ts-inner');
            this.content = root.find('.ts-content');
        },

        _init : function(){

            var me = this;
            this._on({
                mouseleave : 'hide'
            });

            this.element.on({
                click : function(){
                    me._targetSet({
                        img : $('img', this).attr('src'),
                        id : $(this).attr('_id'),
                        time : $('.ts-time', this).text()
                    });
                }
            }, '.ts-each');

            this._on({
                'click .ts-close' : 'hide'
            });
        },

        _refresh : function(){
            this.loading.show();
            this.inner.hide();
            this.content.empty();
        },

        show : function(){
            this._refresh();
            var target = $(this.options.target);
            target.trigger('_select');
            this._offset(target);
            this._ajax(target.attr('type'));
            this.element.show();
        },

        hide : function(){
            $(this.options.target).trigger('_unselect');
            this.options.target = null;
            this.element.hide();
        },

        _targetSet : function(info){
            $(this.options.target).trigger('_set', [info]);
            this.hide();
        },

        _offset : function(target){
            var offset = target.offset();
            var offLeft = offset.left - 15;
            var offTop = offset.top - 15;
            var height = target.outerHeight() + 5;
            var width = target.outerWidth();
            this.element.css({
                top : offTop + height + 'px'
            });
            var jianLeft = width / 2 + offLeft - 10;
            $('.ts-jian', this.element).css({
                left : jianLeft + 'px'
            });
        },

        _ajax : function(type){
            var me = this;
            var url = this._replace(this.options.ajaxUrl, {type : this.types2number[type]});
            var json;
            if(this.cachesType[type]){
                json = this.caches[type];
                this._ajaxAfter(json);
            }else{
                $.getJSON(url, function(json){
                    if(json && json[0] && json[0]['video_info']){
                        json = json[0]['video_info'];
                        var cache = me.setCache(json, type);
                        me.cachesType[type] = true;
                        setTimeout(function(){
                            me._ajaxAfter(cache);
                        }, 1000);
                    }else{
                        me._ajaxNone();
                    }
                });
            }
        },

        _ajaxAfter : function(info){
            this._createInit(info);
            this.loading.hide();
            this.inner.show();
        },

        _ajaxNone : function(){
            this.content.html('暂无！');
        },

        types2number : function(type){
            var types = {
                'tou' : 1,
                'hua' : 2,
                'wei' : 3
            };
            return types[type];
        },

        _createInit : function(info){
            var me = this;
            var html = '';
            $.each(info, function(i, n){
                html += me._createEach(n);
            });
            this.content.html(html);
        },

        _createEach : function(eachInfo){
            return this._replace(this.options.eachTemplate, eachInfo);
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/g, function(all, match){
                return data[match] || '';
            });
        },

        getCache : function(id, type){
            var info;
            if(type && this.caches[type]){
                $.each(this.caches[type], function(i, n){
                    if(n['id'] == id){
                        info = n;
                        return false;
                    }
                });
            }else{
                var find = false;
                $.each(this.caches, function(i, n){
                    $.each(n, function(ii, nn){
                        if(nn['id'] == id){
                            find = true;
                            info = nn;
                            return false;
                        }
                    });
                    if(find){
                        return false;
                    }
                });
            }
            return this._infoChange(info);
        },

        setCache : function(json, type){
            var me = this;
            var cache = this.caches[type] = [];
            $.each(json, function(i, n){
                if(n['duration'] > 0){
                    n['time'] = me._duration(n['duration'] / 1000);
                    cache.push(n);
                }
            });
            return cache;
        },


        _infoChange : function(info){
            return {
                'id' : info['id'] || info['vodinfo_id'],
                'src' : info['hostwork'].replace('vfile1', 'mcp') + '/vod/' + info['video_path'] + info['video_filename'],
                'fen' : [info['width'], info['height']],
                'zhen' : parseInt(info['frame_rate']),
                'time' : info['duration'] / 1000,
                'img' : info['source_img'],
                'title' : info['title']
            };
        },

        _duration : function(seconds){
            var h = parseInt(seconds / 3600);
            var m = parseInt((seconds - h * 3600) / 60);
            var s = parseInt(seconds % 60);
            return (h ? h + '\'' : '') + (m ? m + '\'' : '') + (s ? s + '"' : '');
        },

        _destroy : function(){
            this.element.off();
        }
    });
})(jQuery);
