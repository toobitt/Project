(function($){
    $.widget('fast.dui', {
        options : {
            'tpl' : '#dui-tpl',
            'delete-box' : '.recycle-area',
            'reset-btn' : '.reset',
            'save-btn' : '.save',
            'sure-btn' : '.sure',
            'cancel-btn' : '.cancel',
            'save-fugai-btn' : '.save-fugai',
            'view-btn' : '.view',
            'total-duration' : '.total-duration',
            'yulan-box' : '.yulan-box',
            'drop-item' : '.drop-item',

            'save-item-ajax-url' : '',
            'delete-item-ajax-url' : '',
            'sort-ajax-url' : '',
            'save-ajax-url' : '',
            'save-fugai-ajax-url' : '',
            'reset-ajax-url' : '',


            info : '',
            'default-title' : ''
        },

        _create : function(){
            this.dropBox = this.element.find('#drop-box');
            this.deleteBox = this.element.find(this.options['delete-box']);
            this.totalDuration = this.element.find(this.options['total-duration']);
            this.yulanBox = $(this.options['yulan-box']);
        },

        _init : function(){
            var _this = this;

            _this._initInfo();

            this.dropBox.sortable({
                items : '.drop-item',
                //axis : 'x',
                scroll : true,
                placeholder : 'place-tag',
                revert : false,
                tolerance : 'pointer',
                zIndex : 10000,

                start : function(event, ui){
                    if(ui.helper.hasClass('common-vod-area')){
                        return;
                    }
                    _this._positionDrop();
                },

                stop : function(){
                    _this._positionDrop(true);
                },

                activate : function(){
                    $(this).addClass('light-state');
                },

                deactivate : function(){
                    $(this).removeClass('light-state');
                },

                receive : function(event, ui){
                    var hash = $(ui.helper).attr('_hash');
                     _this._saveItem(hash);
                     _this._checkTotalDuration();
                },

                update : function(event, ui){
                    /*var hash = $(ui.item).attr('_hash');
                    var item = _this.element.find('.drop-item[_hash="' + hash + '"]');
                    if(item[0] && !item.data('save')){
                        _this._saveItem(hash);
                        _this._checkTotalDuration();
                    }else{
                        _this._sort();
                    }*/
                    _this._sort();

                }
            });

            this.dropBox.disableSelection();

            this._initDelete();


            var handlers = {};
            handlers['click ' + this.options['drop-item']] = '_clickItem';
            handlers['click ' + this.options['reset-btn']] = '_reset';
            handlers['click ' + this.options['save-btn']] = '_popdata';
            handlers['click ' + this.options['cancel-btn']] = '_poutdata';
            handlers['click ' + this.options['save-fugai-btn']] = '_saveFugai';
            handlers['click ' + this.options['sure-btn']] = '_save';
            handlers['click ' + this.options['view-btn']] = '_view';
            this._on(handlers);


        },

        _positionDrop : function(reset){
            var _this = this;
            if(reset){
                _this.deleteBox.css('left', '1135px');
                _this._disableDelete();
            }else{
                var lastItem = _this.dropBox.find('li:last');
                var left = lastItem.position().left + 2 * lastItem.outerWidth(true);
                _this.deleteBox.css('left', left + 'px');
                _this._enableDelete();
            }
        },

        _clickItem : function(event){
            var _this = this;
            var target = $(event.currentTarget);
            var data = _this._getCache(target.attr('_hash'));
            _this.clickItemBefore(target);
            _this._trigger('clickItem', null, [target, data]);
        },

        clickItemBefore : function(target){
            target.addClass('on');
        },

        clickItemAfter : function(){
            this.dropBox.find('.drop-item.on').removeClass('on');
            this._checkTotalDuration();
        },

        _checkNotSave : function(){
            var _this = this;
            var notSave = _this.element.find('.drop-item').filter(function(){
                return !$(this).data('save');
            });

            if(notSave.length == 1){
                _this._saveItem(notSave.attr('_hash'));
            }
        },

        _saveItem : function(hash, isedit){
            var _this = this;
            var data = _this._getCache(hash);
            var post;
            if(isedit){
                post = {
                    main_id : $.type(mainId) != 'undefined' ? mainId : 0,
                    vodinfo_id : data['id'],
                    hash_id : hash,
                    start_time : data['startInfo']['time'],
                    end_time : data['endInfo']['time'],
                    start_imgdata : data['startInfo']['imgData'],
                    end_imgdata : data['endInfo']['imgData']
                };
            }else{
                var extInfo = JSON.stringify(data);
                extInfo = extInfo.replace(/&quot;/g, '');
                post = {
                    main_id : $.type(mainId) != 'undefined' ? mainId : 0,
                    hash_id : hash,
                    vodinfo_id : data['id'],
                    start_time : 0,
                    end_time : data['duration_num'],
                    img : data['img'],
                    ext_info : extInfo
                };
            }
            _this.element.find('.drop-item[_hash="'+ hash +'"]').data('save', true);

            _this._checkNotSave();

            $.post(
                _this.options['save-item-ajax-url'],
                post,
                function(json){
                    _this._sort();
                },
                'json'
            );
        },

        _deleteItem : function(hash){
            var _this = this;
            $.post(
                _this.options['delete-item-ajax-url'],
                {hash_id : hash},
                function(json){
                    _this._sort();
                },
                'json'
            );
        },

        _checkTotalDuration : function(){
            var _this = this;
            var total = 0;
            var items = _this.dropBox.find('.drop-item').each(function(){
                var hash = $(this).attr('_hash');
                var data = _this._getCache(hash);
                if(data){
                    total += parseInt($.type(data['new_duration']) != 'undefined' ? data['new_duration'] : data['duration_num']);
                }
            });
            _this.totalDuration.html(_this._duration(total / 1000));


            //检查是否有，从而决定显示或者关闭.drop-tip
            $('.drop-tip')[!items.length ? 'show' : 'hide']();
        },

        _sort : function(){
            var _this = this;
            var hashs = [];
            _this.dropBox.find('.drop-item').each(function(){
                hashs.push($(this).attr('_hash'));
            });
            $.post(
                _this.options['sort-ajax-url'],
                {'hashs[]' : hashs},
                function(){

                }
            );
        },

        _reset : function(){
            var _this = this;
            _this.dropBox.empty();
            $.getJSON(
                _this.options['reset-ajax-url'],
                function(){

                }
            );
            _this._checkTotalDuration();
        },

		_popdata : function(){
			var drop = this.element.find('#drop-box');
            if(drop.find('li').length){
				this.element.find('.save-pop').slideToggle();
			}else{
				jAlert('还没有进行快编操作，无法进行另存为！', '提醒');
			}
		},

		_poutdata : function( type ){
			this.element.find('.save-pop').slideUp();
		},

		_clearValue : function(){
			 var inputValue = this._getValue();
			 inputValue.titleDom.val('');
			 inputValue.commentDom.val('');
			 inputValue.vodDom.val('');
			 inputValue.publishDom.html('');
			 inputValue.sortDom[0].firstChild.nodeValue = '选择分类';
			 this._clearPop();
			 this._clearSort();
		},

		_clearPop : function(){
			var pop = this.element.find('.common-form-pop');
			pop.find('.publish-result').addClass('empty').find('ul').empty();
			pop.find('.date-picker').val('');
			pop.find('.column_show').detach();
			pop.find('.publish-each:first-child li').each(function(){
				if($(this).hasClass('open')){
					$(this).removeClass('open');
				}
				$(this).find('input').prop('checked', false);
			});
			pop.find('.publish-hidden').val('');
			pop.find('.publish-name-hidden').val('');
		},

		_clearSort : function(){
			var box = this.element.find('.save-pop');
			this.element.find('.sort-box li').each(function(){
				$(this).find('input[name="hg-sort-radio"]').prop('checked', false);
			});
		},

		_getValue : function(){
			var box = this.element.find('.save-pop');
			return inputValue = {
				titleDom : box.find('input[name="title"]'),
				commentDom : box.find('textarea[name="comment"]'),
				columnDom : this.element.find('.publish-hidden'),
				vodDom : box.find('input[name="vod_sort_id"]'),
				publishDom : box.find('.publish-button').find('span'),
				sortDom : box.find('.sort-label'),
			}
		},

        _save : function(){
            var _this = this;
            var inputValue = this._getValue();
            var info = {};
            info.title = inputValue.titleDom.val();
            info.comment = inputValue.commentDom.val();
            info.column_id = inputValue.columnDom.val();
            info.vod_sort_id = inputValue.vodDom.val();
            if(!info.title){
            	jAlert('视频标题还没有添加！', '提醒');
            	return false;
            }
            $.getJSON(
                _this.options['save-ajax-url'], info, function(json){
                    if(json[0] && json[0].error){
                        jAlert('还没有进行快编操作，无法进行另存为！', '提醒');
                    }else{
                        _this.dropBox.empty();
                        _this._checkTotalDuration();
                        _this._poutdata();
                        _this._clearValue();
                    }
                }
            );
        },

        _saveFugai : function(){
            var _this = this;
            //确认弹窗
            jConfirm('此操作会覆盖原视频，是否继续','视频快编提醒',function(result){
            	if(result){
            		_this.dropBox.empty();
                    $.getJSON(
                        _this.options['save-fugai-ajax-url'],
                        {id : mainId},
                        function(){

                        }
                    );
                    _this._checkTotalDuration();
            	}else{
            		return;
            	}
            }); 
        },

        _view : function(){
            var _this = this;
            if(!_this.yulanBox.is(':video-yulan')){
                _this.yulanBox.yulan({
                    duis : '#' + _this.element.attr('id')
                });
            }
            var info = [];
            $('.drop-item', this.element).each(function(){
                var hash = $(this).attr('_hash');
                var data = _this._getCache(hash);
                var dataClone = $.extend({}, data, {hash : hash});
                if((dataClone['startInfo'] && !dataClone['endInfo']) || (!dataClone['startInfo'] && dataClone['endInfo'])){
                    return;
                }
                info.push(dataClone);
            });
            if(!info.length){
                return;
            }
            this.yulanBox.yulan('option', 'info', info).yulan('open');
            return false;
        },

        _initInfo : function(){
            var _this = this;
            var info = _this.options['info'];
            if(info){
                $.each(info, function(i, n){
                    if(!n['hash_id']){
                        var hash = _this._makeHash();
                        var data = {
                            hash : hash,
                            duration : n['duration'],
                            img : n['img']
                        };
                        $(_this.options['tpl']).tmpl(data).appendTo(_this.dropBox);
                        _this._addCache(hash, n);
                        //_this._saveItem(hash);
                    }else{
                        var isTwo = !!n['start_imgdata'];
                        var data = {
                            hash : n['hash_id'],
                            duration : n['duration']
                        };
                        if(isTwo){
                            data['img'] = n['start_imgdata'];
                            data['img_end'] = n['end_imgdata'];
                        }else{
                            data['img'] = n['img'];
                            data['img_end'] = '';
                        }
                        var item = $(_this.options['tpl']).tmpl(data).appendTo(_this.dropBox).data('save', true);
                        if(isTwo){
                            item.removeClass('one-point').addClass('two-point');
                        }
                        if(n['ext_info']){
                            _this._addCache(n['hash_id'], $.extend({}, $.parseJSON(n['ext_info']), {
                                startInfo : {time : n['start_time']},
                                endInfo : {time : n['end_time']}
                            }));
                        }
                    }
                });
                _this._checkTotalDuration();
            }
        },

        clone : function(hash){
            var item = $('.drop-item[_hash="'+ hash +'"]', this.element).clone();
            item.find('.time').hide();
            return item;
        },

        getDrag : function(data){
            var _data = $.extend({}, data, {
                hash : this._makeHash()
            });
            var one = $(this.options['tpl']).tmpl(_data);
            return one[0].outerHTML;
        },

        setDrag : function(hash, data){
            this._addCache(hash, data);
        },

        setStart : function(time, imgData){
            this._setSE('start', time, imgData);
        },

        setEnd : function(time, imgData){
            this._setSE('end', time, imgData);
        },

        _setSE : function(type, time, imgData){
            var _this = this;
            var target = this.dropBox.find('.drop-item.on');
            if(target.hasClass('one-point')){
                target.removeClass('one-point').addClass('two-point');
                target.find('img, .time').hide();
            }
            var hash = target.attr('_hash');
            target.find('.' + type).find('img').attr('src', imgData).show();
            var data = _this._getCache(hash);
            data[type + 'Info'] = {
                time : time,
                imgData : imgData
            };
            var startInfo = data['startInfo'];
            var endInfo = data['endInfo'];
            if(startInfo && endInfo){
                if(startInfo['time'] == 0 && parseInt(endInfo['time']) == parseInt(data['duration_num'])){
                    delete data['startInfo'];
                    delete data['endInfo'];
                    delete data['new_duration'];
                    target.removeClass('two-point').addClass('one-point');
                    target.find('.start img').attr(data['img']).show();
                    target.find('.end').hide();
                    target.find('.time').html(data['duration']).show();
                    _this._saveItem(hash);
                    return;
                }
                data['new_duration'] = endInfo['time'] - startInfo['time'];
                target.find('.time').html(_this._duration((data['new_duration']) / 1000)).show();
                _this._saveItem(hash, true);
            }
        },

        _initDelete : function(){
            var _this = this;
            _this.deleteBox.droppable({
                accept : '.drop-item',
                hoverClass : 'on',
                drop : function(event, ui){
                    ui.helper.remove();
                    _this._deleteItem(ui.helper.attr('_hash'));
                    _this._checkTotalDuration();
                    $(this).removeClass('on');
                }
            }).droppable('disable', true);
        },

        _enableDelete : function(){
            this.deleteBox.droppable('enable');
        },

        _disableDelete : function(){
            this.deleteBox.droppable('disable');
        },

        _addCache : function(hash, data){
            this.cache = this.cache || {};
            this.cache[hash] = $.extend({}, data);

        },

        _getCache : function(hash){
            return this.cache && this.cache[hash];
        },

        _makeHash : function(){
            return new Date().getTime() + '' + Math.ceil((Math.random() * 1000));
        },

        _duration : function(duration){
            duration = parseInt(duration);
            var h = parseInt(duration / 3600);
            var m = parseInt(duration / 60);
            var s = parseInt(duration % 60);
            return (h > 0 ? h + '\'' : '') + ( (h > 0 || m > 0) ? m + '\'' : '') + s + '"';
        }


    });
})(jQuery);