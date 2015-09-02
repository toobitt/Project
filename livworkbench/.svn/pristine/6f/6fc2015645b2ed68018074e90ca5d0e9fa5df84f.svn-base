(function($){
    $.widget('main.mask', $.magic.mask, {
        options : {
            staticSave : ''
        },

        cancel : function(callback){
            var _this = this;
            var ids = {};
            function completeRefresh(){
                var ok = true;
                $.each(ids, function(i, n){
                    if(!n){
                        ok = false;
                        return false;
                    }
                });
                if(ok){
                    _this.closeProperty();
                    callback && callback();
                }
            }
            this.element.find('.' + this.options.selectClass).each(function(){
                var $this = $(this);
                var hash = $(this).attr('hash');
                var id = $(this).data('id');
                ids[id] = false;
                $.globalAjax(this, function(){
                    return $.post(
                        _this.options.cancel,
                        {id : id},
                        function(json){
                            ids[id] = true;
                            var info = json[0];
                            if(!info){
                                cb();
                                return;
                            }
                            var info = json[0][0];
                            _this.updateCellInfo(hash, info);
                            $.MC.dylist.dylist('refreshState');
                            $.iframe.refreshCell(hash, info, 'cancel');
                            cb();
                            function cb(){
                                $this.removeClass(_this.options.selectClass);
                                completeRefresh();
                                $this.trigger('click');
                            }

                        },
                        'json'
                    );
                });
            });
        },

        save : function(ids, data, callback){
            var _this = this;
            var sourceId = ids['source'];
            var cssId = ids['cssid'];
            var cellType = ids['cellType'];
            var headerText = ids['headerText'];
            var moreHref = ids['moreHref'];
            var mask = this.element.find('.' + this.options.selectClass);
            mask.each(function(){
                var hash = $(this).attr('hash');
                var info = _this.getCellInfo(hash);
                info.data_source = sourceId;
                info.css_id = $.type(cssId) == 'undefined' ? info.css_id : cssId;
                info.cell_type = cellType;
                info.header_text = headerText;
                info.is_header = $.trim(headerText) ? 1 : 0;
                info.more_href = moreHref;
                info.is_more = $.trim(moreHref) ? 1 : 0;
                $.each(data, function(i, n){
                    if(i == 'mode_param' || i == 'js_param'){
                        var currentParam = info[i];
                        currentParam && $.each(currentParam, function(ii, nn){
                            if($.type(n[nn['sign']]) != 'undefined'){
                                currentParam[ii]['value'] = n[nn['sign']];
                            }
                        });
                    }else if(i == 'input_param' && sourceId > 0){
                        var inputParam = $.extend({}, $.MC.pb.property('getProperty', sourceId));
                        inputParam = inputParam.input_param;
                        var tmp = [];
                        $.each(inputParam, function(ii, nn){
                            if($.type(n[nn['sign']]) != 'undefined'){
                                nn['value'] = n[nn['sign']];
                            }
                            tmp.push(nn);
                        });
                        info.input_param = tmp;
                    }else if(i == 'css_param' && cssId > 0){
                        var cssParam = {};
                        info['css_list'] && $.each(info['css_list'], function(i, n){
                            if(n['id'] == cssId){
                                cssParam = n['para'];
                                return false;
                            }
                        });
                        var tmp = {};
                        cssParam && $.each(cssParam, function(ii, nn){
                            if($.type(n[nn['sign']]) != 'undefined'){
                                nn['value'] = n[nn['sign']];
                            }
                            tmp[ii] = nn;
                        });
                        info.css_param = tmp;
                    }
                });
                _this._save(this, hash, info, true, callback);
            });
        },

        staticSave : function(content){
            var _this = this;
            var doXHR = function(mask, hash, info){
                $.globalAjax(mask, function(){
                    return $.post(
                        _this.options.staticSave,
                        {static_html : content, id : info['id']},
                        function(json){
                            _this._update(mask, hash, info);
                            $.MC.pb.property('refreshCurrentData', info);
                        }
                    );
                });
            };
            this.element.find('.' + this.options.selectClass).each(function(){
                var hash = $(this).attr('hash');
                var info = _this.getCellInfo(hash);
                info['static_html'] = content;
                info['cell_type'] = 3;
                doXHR(this, hash, info);
            });
        },

        _save : function(mask, hash, info, noOpen, callback){
            mask = $(mask).addClass(this.options.selectClass);
            var _this = this;
            delete info['rended_html'];
            delete info['css'];
            var xhr = $.globalAjax(mask, function(){
                return $.post(
                    _this.options.save,
                    {data : JSON.stringify([info])},
                    function(json){
                        _this._update(mask, hash, json[0], noOpen);
                        $.MC.pb.property('refreshCurrentData', json[0]);
                        _this._shenmiCell();
                        $.MC.dylist.dylist('refreshState');
                    },
                    'json'
                );
            }, function(){
                callback && callback();
            });
        },

        gssSave : function(hash, info){
            var _this = this;
            _this.moniClick(hash, true);
            var mask = this.getMask(hash);
            var currentInfo = this.getCellInfo(hash);
            info['data_source'] = currentInfo['data_source'];
            $.globalAjax(mask, function(){
                return $.post(
                    _this.options.save,
                    {data : JSON.stringify([info])},
                    function(json){
                        _this._update(mask, hash, json[0], true);
                        $.MC.dylist.dylist('refreshState');
                    },
                    'json'
                );
            });
        },

        _tip : function(mask){
            if(mask.data('tip')){
                return;
            }
            mask.data('tip', true);
            var mt = mask.offset();
            var tip = $('<div class="tip"/>').appendTo('body').css({
                left : mt.left + 'px',
                top : mt.top + 'px'
            }).html('<div class="tip-text m2o-transition">请先为该单元设置样式</div>');
            this._delay(function(){
                tip.children().addClass('on');
            }, 0);
            this._delay(function(){
                tip.remove();
                mask.data('tip', false);
            }, 1500);
        },

        dropEventDo : function(hash, modeType, modeId){
            var _this = this;
            var info = _this.getCellInfo(hash);
            if(modeType == 'format'){
                var formatInfo = $.MC.db.mode('getFormat');
                formatInfo['data_source'] = info['data_source'];
                formatInfo['input_param'] = info['input_param'];
                info = formatInfo;
            }
            info['cell_mode'] = modeId;
            var target = _this.getMask(hash);
            _this._save(target, hash, info);
        },

        dropEventOver : function(){
            var cc = this.options.hoverClass;
            this.element.find('.' + cc).removeClass(cc);
            this.refreshDrop('all');
        },

        dropEventEnd : function(){
            this.refreshDrop();
        },

        refreshDrop : function(selected){
            if(selected == 'all'){
                return this.element.find('.' + this.options.normalClass).droppable('disable').removeClass('ui-state-disabled');
            }
            selected = selected || this.element.find('.' + this.options.selectClass);
            if(!selected.length){
                this.element.find('.' + this.options.normalClass).droppable('enable');
            }else{
                selected.droppable('enable').siblings().droppable('disable').removeClass('ui-state-disabled');
            }
        },

        _checkSelected : function(){
            var callback = function(needKong){
                $.MC.pb.property('hide');
                //mainConfig.which != 'k' && $.MC.db.mode('show');
                needKong && $.MC.db.mode('kongShenmiCell');
            };
            var _this = this;
            var selected = _this.getSelectMask();
            _this.refreshDrop(selected);
            var length = selected.length;
            if(!length){
                callback(true);
                return;
            }
            var cellModeIds = this._shenmiCell(selected);
            if(length == 1 && parseInt(cellModeIds[0]) == 0){
                callback();
                return;
            }
            _this.openProperty();

        },

        _shenmiCell : function(selected){
            selected = selected || this.element.find('.' + this.options.selectClass);
            var _this = this;
            var cellNames = [];
            var cellHashs = [];
            var cellModeIds = [];
            selected.each(function(){
                var hash = $(this).attr('hash');
                var info = _this.getCellInfo(hash);
                cellNames.push(info['cell_name']);
                cellModeIds.push(info['cell_mode']);
                cellHashs.push(hash);
            });
            $.MC.db.mode('shenmiCell', cellNames, cellHashs);
            return cellModeIds;
        },



        _click : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var cc = this.options.selectClass;

            if(target.hasClass(cc)){
                target.removeClass(cc);
            }else{
                target.addClass(cc);
                if(mainConfig.which != 'k' && (event.ctrlKey || event.metaKey)){

                }else{
                    target.siblings('.' + cc).removeClass(cc);
                }
            }
            this._checkSelected();
            this.refreshPlugin();
        },

        removeClick : function(){
            this.getSelectMask().trigger('click');
        },

        openProperty : function(){
            var _this = this;
            var mask = this.getSelectMask();
            var pb = $.MC.pb;
            var state = !pb.property('getState');
            pb.property('show');
            if(mask.length == 1){
                pb.property('refresh', this.getCellInfo(mask.attr('hash')), state);
            }else{
                var modeId = 0;
                var modeTong = true;
                var modeInfo;
                mask.each(function(){
                    var _info = _this.getCellInfo($(this).attr('hash'));
                    !modeInfo && (modeInfo = _info);
                    if(!modeId){
                        modeId = _info['cell_mode'];
                    }else if(modeId != _info['cell_mode']){
                        modeTong = false;
                        return false;
                    }
                });
                if(!modeTong){
                    pb.property('refresh', modeTong, state);
                    return;
                }
                pb.property('refresh', modeInfo, state);
            }

        },

        closeProperty : function(){
            var mask = this.getSelectMask();
            if(mask.length == 0){
                $.MC.pb.property('hide');
            }else{
                this.openProperty();
            }
        },


        _destroy : function(){

        }
    });
})(jQuery);