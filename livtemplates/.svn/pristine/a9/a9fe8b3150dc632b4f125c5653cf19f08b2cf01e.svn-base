(function($){
    $.widget('role.apps', {
        options : {
            url : ''
        },

        _create : function(){

        },

        _init : function(){
            this._on({
                'click .qx-item' : '_click'
            });
        },

        _click : function(event){
            var target = $(event.currentTarget);
            if(target.hasClass('on')){
                target.app('hide');
                return false;
            }
            target.siblings('.on').app('hide');
            target.app('show');
        },

        addApp : function(info, noOpen){
            $('<div class="qx-item"><span class="part"></span><span class="qx-item-close"></span></span></div>').appendTo(this.element).app($.extend({
                url : this.options['url'],
                noOpen : noOpen
            }, info));
            this.check();
        },

        removeApp : function(id){
            this.element.find('.qx-item[data-id="' + id + '"]').app('destroy').remove();
            this.check();
        },

        check : function(){
            $('.default')[!this.element.find('.qx-item').length ? 'show' : 'hide']();
        }
    });


    $.widget('role.app', {
        options : {
            id : 0,
            mod : '',
            app : '',
            name : '',
            url : '',
            'column-url' : 'route2node.php?a=column_node',
            tpl : '#tc-tpl',
            noOpen : false
        },

        _create : function(){
            var _this = this;
            var prms = prmsCache && prmsCache[_this.options['app'] + '-' + _this.options['mod']];
            _this.element.attr('data-id', _this.options['id']).append(_this.options['name']).css('opacity', 0);
            _this.TCName = _this.options['mod'] + _this.options['app'] + _this.options['id'];
            _this.tpl = $(_this.options['tpl']).html();
            if(prms){
                if(prms['is_complete'] > 0){
                    _this._setComplete(true);
                }
                _this.initdata = {
                    value : prms['func_prms'].split(',').length - 1,
                    checkall : prms['is_complete'] > 0,
                    nodes : prms['node_prms'].split(','),
                    settings : prms['setting_prms'].split(','),
                };
            }
        },

        _setComplete : function(isComplete){
            this.element.find('span:not(.qx-item-close)').removeClass().addClass(isComplete ? 'all' : 'part');
        },

        _init : function(){
            var _this = this;
            _this.TC = $('#' + _this.TCName);
            if(!_this.TC[0]){
                _this.element.animate({
                    opacity : 1
                }, 200);
                _this._ajax();
            }else{
                _this.element.css('opacity', 1);
                _this.TC.TC('refresh', {
                    target : this.element,
                    initdata : _this.initdata
                });
                _this.show();
            }

            _this._on({
                'click .qx-item-close' : '_close'
            });
        },

        _close : function(){
            $('.qx-list').triggerHandler('_whichClick', [this.options['app'], this.options['mod']]);
            return false;
        },

        _ajax : function(){
            var _this = this;
            var root = _this.element;
            var guid = $.globalAjaxLoad.bind(root);
            var xhr = $.getJSON(
                _this.options['url'],
                {id : _this.options['id'], mod_uniqueid : _this.options['mod'], app_uniqueid : _this.options['app']}
            );
            xhr.guid = guid;
            xhr.done(function(json){
                json = json[0];
                if(json['error']){
                    root.myTip({
                        string : json['error'],
                        dtop : 50,
                        dleft : 0,
                        delay : 1500,
                        color : 'red',
                        callback : function(){
                            $.MC.items.apps('removeApp', _this.options['id']);
                            $.MC.list.triggerHandler('_removeState', [_this.options['id']]);
                        }
                    });
                    return;
                }
                try{
                    if(json['extra']['node_uniqueid'] == 'cloumn_node'){
                        _this._ajaxColumn(json, _this.options['app']);
                    }else{
                        json['column'] = null;
                        _this._createTC(json, _this.options['app']);
                    }
                }catch(e){

                }
            });
        },

        _ajaxColumn : function(json, app_uniqueid){
            var _this = this;
            var root = _this.element;
            var guid = $.globalAjaxLoad.bind(root);
            var xhr = $.getJSON(
                _this.options['column-url'],
                function(column){
                    json['node'] = null;
                    json['column'] = column[0];
                    _this._createTC(json, app_uniqueid);
                }
            );
            xhr.guid = guid;
        },

        _createTC : function(json, app_uniqueid){
            var _this = this;
            var node, settings, nodeString = [], settingsString = [];
            if(json['node'] || json['column']){
                node = json['node'];
                if(node){
                    $.each(node, function(i, n){
                        nodeString.push(n.id);
                    });

                }else{
                    node = json['column'];
                    $.each(node, function(i, n){
                        if(n){
                            $.each(n, function(ii, nn){
                                nodeString.push(nn.id);
                            });
                        }
                    });
                }
                nodeString = nodeString.join(',');
            }else{
                nodeString = '';
            }
            if( json['settings'] ){
            	settings = json['settings'];
            	$.each(settings, function(i, n){
            		settingsString.push(n.app_uniqueid + '#' + n.mod_uniqueid);
            	});
            	settingsString = settingsString.join(',');
            }else{
            	settingsString = '';
            }
            var op = json['op'];
            var opString = [];
            $.each(op, function(i, n){
                opString.push(i);
            });
            opString = opString.join(',');
            json['nodeString'] = nodeString;
            json['settingsString'] = settingsString;
            json['opString'] = opString;
            json['nodeSepecial'] = (app_uniqueid == 'publishsys' || app_uniqueid == 'mkpublish');
            json['app_uniqueid']= app_uniqueid;
            var tc = _this.TC = $.tmpl(_this.tpl, json).attr({
                id : _this.TCName
            }).appendTo('body');
            tc.TC({
                target : this.element,
                initdata : _this.initdata
            });
            _this.show();
        },

        _TCPostion : function(){
            var _this = this;
            var info = _this.element.offset();
            $.extend(info, {
                width : _this.element.outerWidth(),
                height : _this.element.outerHeight()
            });
            var parentInfo = _this.element.parent().offset();
            _this.TC.css({
                left : parentInfo.left + 'px',
                top : info.top + info.height + 5 + 'px'
            });
        },

        show : function(){
            if(this.options['noOpen']){
                this.options['noOpen'] = false;
                return;
            }
            this.element.addClass('on').siblings('.on').app('hide');
            this.TC.TC('show');
            this._TCPostion();
        },

        hide : function(){
            this.element.removeClass('on');
            this.TC.TC('hide');
        },

        getPrms : function(){
            var $return = {};
            $return[this.options['app'] + '-' + this.options['mod']] = this.TC.TC('getPrms');
            return $return;
        },

        save : function(isComplete){
            this._setComplete(isComplete);
        },

        _destroy : function(){
            this.TC.TC('hide');
            this.TC = null;
        }
    });

    $.widget('role.TC', {
        options : {
            target : null,
            initdata : null,
            special_node : 'publishsys_node',
            publish_tpl : '#publishsys-tpl',
        },

        _create : function(){
            var _this = this;
            _this.dis = 90;
            var opItems = _this.element.find('.tc-op-item');
            var len = _this.opLen = opItems.length;
            opItems.each(function(i, n){
                $(this).css('left',  i * _this.dis + 'px');
            });
            $('.tc-sbox-inner', _this.element).width(_this.dis * (len - 1));
            if(!_this.options['initdata']){
                _this.options['initdata'] = {
                    value : 0,
                    checkall : false,
                    nodeall : true,
                    pz : false
                };
            }
            this.publish_tpl = $(_this.options['publish_tpl']).html();

        },

        _init : function(){
            var _this = this;
            _this.slider = $('.tc-slider', _this.element).slider({
                min : 0,
                max : _this.opLen - 1,
                step : 1,
                slide : function(event, ui){
                    _this._slide(ui.value);
                },
                stop : function(event, ui){
                    _this._slide(ui.value);
                }
            });
            _this._on({
                'click .check-all' : '_clickNode',
                'click .unique-set' : '_toggleNode',
                'click .check-pz' : '_clickPz',
                'click .tc-save' : '_save',
                'click .tc-cancel' : '_cancel'
            });
            _this.initData();
            this._clickNode();
            this._clickPz();
        },

        initData : function(){
            var _this = this;
            var data = _this.options['initdata'];
            _this.slider.slider('value', data['value']);
            _this._slide(data['value'], true);
            
        	// if(typeof data['nodeall'] == 'undefined' && data['nodes'][0]&& $('.tc-node input', _this.element).length == data['nodes'].length){
                // data['nodeall'] = true;
            // }
            if( typeof data['nodeall'] == 'undefined' && data['nodes'][0] && (data['nodes'][0] == -1 || $('.tc-node input', _this.element).length == data['nodes'].length) ){
            	data['nodeall'] = true;
            }
            
            if(typeof data['pz'] == 'undefined' && data['settings'][0] && $('.tc-settings input', _this.element).length == data['settings'].length){
            	data['pz'] = true;
            }
            
            if(!data['nodeall']){
                _this._node(data['nodes']);
            }
            if( !data['pz'] && data['settings'] ){
            	_this._pz(data['settings']);
            }
            var tcAll = this.element.find('.tc-all');
        	var special = tcAll.attr('_uniqueid');
        	if( special ){
				this._column(tcAll, data['nodes'], special);
				if( data['nodes'] && data['nodes'][0] == 1 && !data['nodeall'] ){
					$('.check-all', this.element).trigger('click');
				}
        	}
        },

        _slide : function(value, noCallback){
            var _this = this;
            _this.element.find('.tc-op-item').removeClass('on').each(function(i, n){
                if(i > value){
                    return false;
                }
                $(this).addClass('on');
            });
            _this.element.find('.tc-bg-inner').css('width', value * _this.dis + 'px');
            if(!noCallback){
                if(_this.timerId){
                    clearTimeout(_this.timerId);
                }
                _this.timerId = setTimeout(function(){
                    _this._callback();
                }, 100);
            }
        },

		_toggleNode : function(){
			var unique = this.widget().find('.tc-all').attr('_uniqueid');
			$('#' + unique + '-pop').publishsys('show');
		},

        _clickNode : function( event ){
        	if( event && $(event.target).is('.unique-set') ){
        		return;
        	}
        	var ischecked = this.widget().find('.check-all').prop('checked');
        	$('.tc-node', this.element)[ ischecked ? 'hide' : 'show']();
            this._callback();
        },

		_column : function(tcAll, nodes, special){
			tcAll.column({
				nodes : nodes,
				unique : special,
				id : special + '-pop',
				target : this.element,
				app : this.options.target,
				tpl : this.publish_tpl
			});
		},

        _node : function(nodes){
    		$('input[name="node[]"]', this.element).val(nodes);
        	$('.check-all', this.element).trigger('click');
        },

        _clickPz : function(){
        	$('.tc-settings', this.element)[this.widget().find('.check-pz').prop('checked') ? 'hide' : 'show']();
            this._callback();
        },

        _pz : function( settings ){
        	$('input[name="settings[]"]', this.element).val(settings);
            $('.check-pz', this.element).trigger('click');
        },

        _save : function(){
            this._cancel();
        },

        _cancel : function(){
            this.hide();
        },

        _TCMask : function(state){
            return;
            var mask = $('#tc-mask');
            if(!mask[0]){
                mask = $('<div/>').attr({
                    id : 'tc-mask'
                }).appendTo('body');
            }
            mask[state ? 'show' : 'hide']();
        },

        _callback : function(){
            this.options['target'].app('save', this.getPrms(true));
        },

        show : function(){
            var _this = this;
            _this.element.stop().css('opacity', 0).show();
            _this._delay(function(){
                _this.element.animate({
                    opacity : 1
                }, 200);
            }, 0);
            _this._TCMask(1);
            this._clickNode();
            this._clickPz();
        },

        hide : function(){
            var _this = this;
            _this.element.stop();
            _this._delay(function(){
                _this.element.animate({
                    opacity : 0
                }, 200, function(){
                    $(this).hide();
                });
            }, 0);
            _this._callback();
            _this._TCMask(0);
        },

        refresh : function(info){
            $.extend(this.options, info);
        },

        getPrms : function(returnIsComplete){
            var root = this.element;
            var op = [];
            root.find('.tc-op-item.on').each(function(i, n){
                op.push($(this).data('key'));
            });
            op = op.join(',');
            var opString = root.find('.tc-op').data('string');
            var node = [], settings = [];
            var special =  root.find('.tc-all').attr('_uniqueid');
            if(!root.find('.check-all').prop('checked')){
            	if( !special ){
            		root.find('.tc-node input:checked').each(function(){
	                    node.push($(this).val());
	                });
            	}else{
            		root.find('.tc-node label').each(function(){
            			if( $(this).attr('_id') ){
            				node.push($(this).attr('_id'));
            			}
            		});
            	}
                node = node.join(',');
            }else{
            	if( !special ){
            		node = -1;		//root.find('.tc-node').data('string')
            	}else{
            		node = 1;
            	}
            }
            
            if( !root.find('.check-pz').prop('checked') ){
            	root.find('.tc-settings input:checked').each(function(){
                    settings.push($(this).val());
                });
                settings = settings.join(',');
            }else{
            	settings = root.find('.tc-settings').data('string');
            }
            var nodeString = special ? 1 : root.find('.tc-node').data('string'),
            	settingsString = root.find('.tc-settings').data('string');
            var isall = (op == opString && node == nodeString && settings == settingsString ) ? 1 : 0;
            if(returnIsComplete){
                return isall;
            }
            return {
                op : op,
                node : node,
                setting : settings,
                is_all : isall
            };
        }
    });

    $.fn.myPlaceholder = function(options){
        options = $.extend({

        }, options);

        this.each(function(){
            $(this).on({
                focus : function(){
                    var val = $.trim($(this).text());
                    var placeholder = $(this).attr('placeholder');
                    if(val == placeholder){
                        $(this).html('');
                    }
                },

                blur : function(){
                    var val = $.trim($(this).text());
                    var placeholder = $(this).attr('placeholder');
                    if(!val || !val.length){
                        $(this).html(placeholder);
                    }
                },

                html : function(){
                    var val = $.trim($(this).text());
                    var placeholder = $(this).attr('placeholder');
                    return val == placeholder ? '' : val;
                }
            });
        }).prop('contentEditable', true);

        return this;
    }
})(jQuery);

jQuery(function($){
    void function(){
        var groups = {};
        if(top != self){
            top.$('.app-tag-fenzu li').each(function(){
                var id = $(this).attr('_index');
                var name = $(this).attr('_name');
                groups[id] = name;
            });
        }
        $('.title').each(function(){
            $(this).html(groups[$(this).data('id')]);
        });
    }();
    var MC;
    MC = $.MC = {
        box : $('.qx-app'),
        list : $('.qx-list'),
        items : $('.qx-items')
    };

    MC.box.on({
        _auto : function(){
            var _this = $(this).css('height', 'auto');
            setTimeout(function(){
                _this.css('height', function(){
                    return $(this).height();
                });
            }, 0);
        }
    }).triggerHandler('_auto');

    MC.list.on({
        _init : function(){
            var _on = function(child, type, method){
                $(this).on(type, child, $(this).triggerHandler('_handlers', [method]));
            };
            _on.call(this, '.app-group', 'click', '_groupClick');
            _on.call(this, '.app-item', 'click', '_itemClick');
        },

        _handlers : function(event, name){
            var events = arguments.callee.events || (arguments.callee.events = $._data(this).events);
            return $.proxy(events[name][0].handler, this);
        },

        _groupClick : function(event, only){
            var target = $(event.currentTarget);
            var parent = target.parent();
            parent[parent.hasClass('on') ? 'removeClass' : 'addClass']('on');
            target.next().slideToggle(250, function(){
                MC.box.triggerHandler('_auto');
            });
            if(!only){
                $(this).find('li.on .app-group').not(target).trigger('click', [true]);
            }
        },

        _itemClick : function(event, noOpen){
            var target = $(event.currentTarget);
            var has = target.hasClass('on');
            target[has ? 'removeClass' : 'addClass']('on');
            var id = target.data('id');
            if(!has){
                MC.items.apps('addApp', {
                    id : target.data('id'),
                    mod : target.data('mod_uniqueid'),
                    app : target.data('app_uniqueid'),
                    name : target.text(),
                    noOpen : noOpen
                });
            }else{
                MC.items.apps('removeApp', target.data('id'));
            }
        },

        _appItem : function(event, id){
            return $('.app-item[data-id="' + id + '"]');
        },

        _removeState : function(event, id){
            $(this).triggerHandler('_appItem', [id]).removeClass('on');
        },

        _whichClick : function(event, app, mod, noOpen){
            $('.app-item[data-app_uniqueid="' + app + '"][data-mod_uniqueid="' + mod + '"]').trigger('click', [noOpen]);
        }

    }).triggerHandler('_init');

    var extendPrmsData = ['show_other_data', 'manage_other_data', 'set_weight_limit',
    	'update_audit_content', 'create_content_status', 'update_publish_content'];
    $('form').submit(function(event){
        var id = parseInt($('#role-id').val());
        id == -1 && (id = 0);
        var post = {
            id : id || 0,
            name : $.trim($('#role-name').val()),
            brief : $('#role-brief div').triggerHandler('html'),
            domain : $('#role-domain div').triggerHandler('html'),
            column_id: $('.publish-box .publish-hidden').val(),
            siteid: $('#hiddenSiteid').val()
        };
        $.each(extendPrmsData, function(index, name) {
        	post['extend[' + name + ']'] = $('#role-' + name).find('input, select').val();
        });

        if(!post['name']){
            alert('请输入角色名称!');
            return false;
        }
        var prms = {};
        $('.qx-item').each(function(){
            $.extend(prms, $(this).app('getPrms'));
        });
        post['prms'] = JSON.stringify(prms);
        var guid = $.globalAjaxLoad.bind($(':submit', this));
        var xhr = $.post(
            'run.php?mid='+ gMid +'&a=update2',
            post,
            function(json){
                top.$.closeFormWin();
            },
            'json'
        );
        xhr.guid = guid;
        return false;
    });

    MC.items.apps({
        url : 'run.php?mid='+ gMid +'&a=prms_setting'
    });

    $('.qx-base-item select').each(function(){
        var value = $(this).closest('.qx-base-item').attr('value');
        $(this).val(value);
    });

    $('.qx-base-item div[placeholder]').myPlaceholder().trigger('focus').trigger('blur');

    prmsCache && $.each(prmsCache, function(key, value){
        key = key.split('-');
        MC.list.triggerHandler('_whichClick', [key[0], key[1], true]);
    });
});