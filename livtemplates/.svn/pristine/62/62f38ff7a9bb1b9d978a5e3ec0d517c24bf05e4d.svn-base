(function($){
    $.widget('split.tiao', {
        options : {
            'ajax-url' : '',
            'check-status-url' : '',
            'tpl' : '',
            'type' : '',
            'number' : '',
            'check-time' : 3 * 1000
        },

        _create : function(){
            this.tpl = $(this.options['tpl']);
            this.number = $(this.options['number']);
            this.numberVal = 0;

            this.currentVideoId = 0;
            this.status = {};
            this.intervalTimer = null;
        },

        _init : function(){
            this._on({
                'click .edit' : '_edit'
            });
            this._liclick();
        },

		_liclick : function(){
			var _this = this;
			$('.spit-nav').on('click', 'li', function(){
				_this.showItem($(this));
			});
		},

        _ajaxBefore : function(){
            this.element.html('<li><img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/></li>');
        },

        _ajax : function(id){
            var _this = this;
            var _cache = _this._getCache(id);
            if($.type(_cache) == 'undefined'){
                var url = this._replace(this.options['ajax-url'], {
                    id : id
                });
                _this._ajaxBefore();
                $.getJSON(
                    url,
                    function(json){
                        _this._addCache(id, json[0]);
                        _this._ajaxCB(json[0]);
                    }
                );
            }else{
                _this._ajaxCB(_cache);
            }
        },

		showItem : function( obj ){
			index = obj.index();
			obj.addClass('select').siblings().removeClass('select');
			$('.spit-list:eq('+ index +')').show().siblings('.spit-list').hide();
			if(obj.html() == '新增' && $('#tiao-box').find('li:first-child').attr('_id')){
				var id = $('#tiao-box').find('li:first-child').attr('_id');
				if( this.firstCache && this.firstCache['id'] == id){
					var data = this.firstCache;
					this._trigger('editAfter', event, [data, true]);
				}
			}
		},

        _onlyAjax : function(id){
            var _this = this;
            var url = _this._replace(_this.options['ajax-url'], {
                id : id
            });
            return $.getJSON(
                url,
                function(json){
                    _this._addCache(id, json[0]);
                }
            );
        },

        _ajaxCB : function(data){
            this.element.empty();
            var list = [],
            	_this = this;
            if(data){
                $.each(data, function(i, n){
                    list.push({
                        id : n['id'],
                        img : n['img_info'],
                        name : n['title'],
                        duration : n['duration_format'],
                        type : _this.options['type']
                    });
                });
                list.length && this.showItem( $('.vod-spit') );
                this.tpl.tmpl({
                    list : list
                }).appendTo(this.element);
                this._collectStatus(data);
                this.startCheck();
            }
            this.numberVal = list.length;
            this._setNumber();
        },

        _addCache : function(id, data){
            (this.cache || (this.cache = {}))[id] = data;
            if( data && data.length ){
            	this.firstCache = data[0];
            }
        },

        _getCache : function(id){
            return this.cache ? this.cache[id] : undefined;
        },

        _deleteCache : function(id){
            this.cache && this.cache[id] && (delete this.cache[id]);
        },

        _extendCache : function(id, item){
            $.extend(this.cache[id], item);
        },

        _updateCache : function(id, itemId, item){
            var cache = this.cache[id];
            $.each(cache, function(i, n){
                if(itemId == n['id']){
                    cache[i] = item;
                    return false;
                }
            });
        },

        _getCacheById : function(id){
            var cache = this._getCache(this.currentVideoId);
            var data = null;
            if(cache){
                $.each(cache, function(i, n){
                    if(id == n['id']){
                        data = n;
                        return false;
                    }
                });
            }
            return data;
        },

        _setNumber : function(){
            this.number.text(this.numberVal);
        },

        _collectStatus : function(data){
            var _this = this;
            $.each(data, function(i, n){
                _this.status[n['id']] = parseInt(n['status']);
            });
        },

        _checkStatus : function(){
            var _this = this;
            var ids = [];
            $.each(_this.status, function(i, n){
                if(n == 0){
                    ids.push(i);
                }
            });
            if(ids.length){
                $.getJSON(
                    _this.options['check-status-url'],
                    {id : ids.join(',')},
                    function(json){
                        json[0] && _this._checkCB(json[0]);
                    }
                );
            }
        },

        _checkCB : function(info){
            var _this = this;
            if(info['status_data']){
                $.each(info['status_data'], function(i, n){
                    _this.status[n['id']] = parseInt(n['status']);
                    var item = _this.element.find('li[_id="' + n['id'] + '"]');
                    if(item[0]){
                        _this._zhuan(item.find('.zhuan'), n['status'], n['transcode_percent'], n['waiting_task_weight']);
                    }
                });
            }
        },

        _zhuan : function(zhuan, code, percent, weight){
            var tips = {
                '-1' : '转码失败',
                '0' : '转码中 ' + percent + '%',
                '1' : '转码完成',
                '-2' : '等待转码'
            };
            if( !percent && weight || percent == -1){
                code = -2;
            }
            zhuan.html(tips[code]).addClass('on');
            if(code == 1){
                this._delay(function(){
                    zhuan.empty().removeClass('on');
                }, 1500);
            }
        },

        _edit : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var id = target.closest('li').attr('_id');
            var data = _this._getCacheById(id);
            this.showItem( $('.new-add').html('重拆') );
            if(!data){
                _this._onlyAjax(_this.currentVideoId).done(function(json){
                    _this._edit(event);
                });
            }else{
                _this._trigger('editAfter', event, [data]);
            }
        },

        startCheck : function(){
            var _this = this;
            _this.intervalTimer = setInterval(function(){
                _this._checkStatus();
            }, _this.options['check-time']);
        },

        stopCheck : function(){
            var _this = this;
            if(_this.intervalTimer){
                clearInterval(_this.intervalTimer);
                _this.intervalTimer = null;
            }
        },

        refresh : function(id){
            this.currentVideoId = id;
            this._ajax(id);
        },

        newPian : function(info, position){
            var _this = this;
            this.element.parent().scrollTop(0);
            _this._deleteCache(_this.currentVideoId);
            _this.status[info['id']] = 0;
            if(_this.numberVal == 0){
                _this.element.empty();
            }
            var newPian = _this.tpl.tmpl({
                list : [{
                    id : info['id'],
                    img : info['img_info'],
                    name : info['title'],
                    duration : info['duration_format'],
                    type : _this.options['type']
                }]
            }).css('opacity', 0).prependTo(_this.element);
            this.firstCache = info;
            var newPianPosition = newPian.offset();
            var src = info['img_info'];
            var img = new Image();
            img.onload = function(){
                var cloneImg = $('<img src="' + src + '" style="position:absolute;width:80px;"/>').appendTo('body').css({
                    left : position.left + 'px',
                    top : position.top + 'px'
                });
                _this._delay(function(){
                    cloneImg.animate({
                        left : newPianPosition.left + 'px',
                        top : newPianPosition.top + 'px'
                    }, 500, function(){
                        cloneImg.remove();
                        newPian.animate({
                            opacity : 1
                        }, 1000);
                    });
                }, 1);
            };
            img.onerror = function(){
                newPian.animate({
                    opacity : 1
                }, 1000);
            };
            img.src = src;
            this.numberVal++;
            this._setNumber();
        },

        editPian : function(info){
            var _this = this;
            _this.element.parent().scrollTop(0);
           	_this._deleteCache(_this.currentVideoId);
            _this.status[info['id']] = 0;
            var id = info['id'];
            var item = _this.element.find('li[_id="' + id + '"]');
            item.find('img').attr('src', info['img_info']);
            item.find('.name').html(info['title']);
            item.find('.time').html(info['duration_format']);
            item.find('.zhuan').empty();
            item.css('opacity', 0);
            _this._delay(function(){
                item.animate({
                    opacity : 1
                }, 1000);
            }, 100);
        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z]+)}}/ig, function(all, match){
                return data[match];
            });
        }
    });
})(jQuery);