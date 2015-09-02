(function($){
    var defaultOptions = {
        url : 'route2node.php?mid={{siteid}}&fid={{fid}}&nodevar={{nodevar}}',
        maxcolumn : 2,
        column : 2,
        guding : true,
        height : 0,
        offset : null,
        hidden : '',
        mask : false,
        absolute : true,
        button : '.common-publish-button'
    };

    function replaceTpl(tpl, data){
        return tpl.replace(/{{([a-zA-Z0-9]+)}}/g, function(all, match){
            return data[match] || '';
        });
    }

    var cache = {
        cache : {},
        add : function(type, id, html){
            if(!this.cache[type]){
                this.cache[type] = {};
            }
            this.cache[type][id] = html;
        },
        get : function(type, id){
            return this.cache[type] ? this.cache[type][id] : null;
        }
    };

    $.fn.commonNode = function(options){
        options = $.extend({}, defaultOptions, options);
        return this.each(function(){
            if($(this).data('publish')){
                $(this).triggerHandler('open.publish');
                $(this).triggerHandler('offset.publish', [options['offset']]);
                return;
            }
            $(this).data('publish', true);
            var url = options['url'];
            var maxcolumn = parseInt(options['maxcolumn'], 10);
            var column = parseInt(options['column'], 10);
            var hidden = options['hidden'];
            var offset = options['offset'];
            var height = options['height'];
            var guding = options['guding'];
            var mask = options['mask'];
            var absolute = options['absolute'];
            var button = options['button'];

            var me = $(this);
            var site = me.find('.publish-site');
            var result = me.find('.publish-result');
            var list = me.find('.publish-list');
            var innerList = me.find('.publish-inner-list');
            var hiddenInput = me.find('.publish-hidden')/*.attr('name', hidden)*/;
            var hiddenNameInput = me.find('.publish-name-hidden');

            var tplTextarea = me.find('.publish-tpl');
            var tpl = tplTextarea.val();
            tplTextarea.remove();

            var tplTextareaResutl = me.find('.publish-tpl-result');
            var tplResult = tplTextareaResutl.val();
            tplTextareaResutl.remove();

            var first = me.find('.publish-each:first');
            var outerWidth = parseInt(first.outerWidth(), 10);
            var clone = first.clone().empty();
            clone.css('text-align', 'center').html('<img src="'+ RESOURCE_URL + 'loading2.gif" style="width:40px;margin-top:50px;"/>');

            me.show();

            me.add(list[0]).css('width', function(){
                var initwidth = parseInt($(this).width(), 10);
                $(this).attr('_initwidth', initwidth);
                var width = initwidth + (column - 1) * outerWidth;
                if($(this).is('.publish-list')){
                    width -= 1;
                }
                return width;
            });

            if(!absolute){
                me.css('position', 'relative');
            }

            if(height){
                var initheight = parseInt(me.height(), 10);
                me.attr('_initheight', initheight);
                me.add(list[0]).add(result[0]).each(function(){
                    $(this).css('height', function(){
                        return parseInt($(this).height(), 10) - (initheight - height);
                    });
                });
            }



            me.on({
                'open.publish' : function(){
                    $(this).show();
                    if(mask){
                        $(this).triggerHandler('mask.publish');
                    }
                },
                'close.publish' : function(){
                    $(this).hide();
                },
                'mask.publish' : function(){
                    var doc = $(document);
                    var mask = $('<div/>').css({
                        position : 'absolute',
                        'z-index' : parseInt(me.css('z-index')) - 1,
                        left : 0,
                        top : 0,
                        opacity : 0,
                        background : '#fff',
                        width : doc.width() + 'px',
                        height : doc.height() + 'px'
                    }).appendTo('body');
                    mask.one('click', function(){
                        me.triggerHandler('close.publish');
                        $(this).remove();
                        var names = [];
                        result.find('li').each(function(){
                            names.push($(this).attr('_name'));
                        });
                        $(button).html(function(){
                            return names.length ? ($(this).attr('_prev') + names.join(',')) : $(this).attr('_default');
                        });
                    });
                },
                'guding.publish' : function(event, column){
                    if(guding){
                        return;
                    }
                    if(!column){
                        column = $(this).find('.publish-each').length;
                    }
                    if(column > maxcolumn){
                        return;
                    }
                    $(this).add(list[0]).each(function(){
                        $(this).css('width', function(){
                            return parseInt($(this).attr('_initwidth'), 10) - outerWidth * (maxcolumn - column);
                        });
                    });
                },
                'offset.publish' : function(event, offset){
                    if(offset){
                        offset.left -= 16;
                        offset.top -= 16;
                        $(this).offset(offset);
                    }
                }
            });
            if(!guding){
                me.triggerHandler('guding.publish', [1]);
            }
            me.triggerHandler('open.publish');
            me.triggerHandler('offset.publish', [offset]);


            innerList.on({
                'move.publish' : function(){
                    var len = $(this).find('.publish-each').length;
                    var dis = len - column;
                    $(this).animate({
                        left : (dis > 0 ? - dis * outerWidth : 0) + 'px'
                    }, 300);
                },
                'ajax.publish' : function(event, type, id, myurl){
                    var oldCache = 0;//cache.get(type, id);
                    if(oldCache){
                        $(this).find('.publish-each:last').html(oldCache).removeAttr('style');
                        result.triggerHandler('check.publish');
                    }else{
                        var self = $(this);
                        $.getJSON(myurl).success(function(data){
                            self.triggerHandler('add.publish', [data]);
                            cache.add(type, id, self.find('.publish-each:last').html());
                            result.triggerHandler('check.publish');
                        });
                    }
                },
                'add.publish' : function(event, data){
   
                    var ul = $('<ul></ul>'),
                    	biaoshi;
                    $.each(data, function(i, n){
                    	biaoshi = n.biaoshi;
                        var mydata = {
                            id : n.id,
                            name : n.name,
                            siteid : n.biaoshi || n.siteid,
                            nodevar: n.nodevar,
                            nameother : n.name,
                            haschild : +n.is_last ? 'publish-nochild' : 'publish-child'
                        };
                        ul.append(replaceTpl(tpl, mydata));
                    });
                    if (innerList.find('.publish-each').size() == 1) {
                    	ul.append('<li _id="0" _name="管理无分类" _siteid="' + biaoshi + '"><input type="checkbox" class="publish-checkbox"/>管理无分类</li>');
                    }
                    ul.find('.publish-nochild').remove();
                    $(this).find('.publish-each:last').html(ul).removeAttr('style');
                }
            });
            innerList
                .on('click', 'li', function(event){
                    if($(event.target).is('input')){
                        return;
                    }
                    var li = $(this);
                    if(!li.find('.publish-child').length){
                        return;
                    }
                    li.closest('.publish-each').nextAll('.publish-each').remove();
                    if(li.hasClass('publish-current')){
                        li.removeClass('publish-current');
                    }else{
                        li.closest('.publish-each').find('.publish-current').removeClass('publish-current');
                        li.addClass('publish-current');
                        var id = li.attr('_id');
                        innerList.append(clone.clone());
                        var myurl = replaceTpl(url, {
                            siteid : site.find('.publish-site-current').attr('_siteid'),
                            nodevar: site.find('.publish-site-current').attr('_nodevar'),
                            fid : id
                        });
                        innerList.triggerHandler('ajax.publish', ['column', id, myurl]);

                    }
                    me.triggerHandler('guding.publish');
                    innerList.triggerHandler('move.publish');
                })
                .on('mouseup', 'input', function(event){
                    var state = !$(this).prop('checked');
                    if(state){
                        var li = $(this).closest('li');
                        var lujinTmp = [];
                        var currents = $(this).closest('.publish-each').prevAll('.publish-each').find('.publish-current');
                        var len = currents.length - 1;
                        currents.each(function(i, n){
                            lujinTmp.push($(this).attr('_name'));
                        });
                        var lujin = [];
                        for(var len = lujinTmp.length - 1; len >= 0; len--){
                            lujin.push(lujinTmp[len]);
                        }
                        var selected = {
                            name : li.attr('_name'),
                            id : li.attr('_id'),
                            siteid : li.attr('_siteid')
                        };
                        var step = '<span class="publish-step">&gt;</span>';
                        var mydata = {
                            name : (lujin.length ? replaceTpl(tplResult, {name : lujin.join(step)}) + step : '') + selected['name'],
                            id : selected['id'],
                            siteid : selected['siteid'],
                            haschild : 'publish-nochild'
                        };
                        result.triggerHandler('add.publish', [mydata, selected['name']]);
                    }else{
                        var id = $(this).closest('li').attr('_id'),
                        	siteid = $(this).closest('li').attr('_siteid');
                        result.triggerHandler('remove.publish', [id, siteid]);
                    }
                });

            result
                .on('mouseup', 'input', function(){
                    var id = $(this).closest('li').attr('_id'),
                    	siteid = $(this).closest('li').attr('_siteid');
                    result.triggerHandler('remove.publish', [id, siteid]);
                    
                    innerList.find('li[_id="'+ id +'"]').filter('li[_siteid="' + siteid + '"]').find('input').prop('checked', false);

                    result.width(180);
                })
                .on({
                    mouseenter : function(){
                        var width = $(this).data('_width');
                        if(!width){
                            var div = $('<div style="float:left;">'+ $(this).html() +'</div>').appendTo('body');
                            div.find('.publish-result-item').removeClass('publish-result-item');
                            width = div.width();
                            if(width < 160){
                                width = 180;
                            }else{
                                width += 50;
                            }
                            $(this).data('_width', width);
                            div.remove();
                        }
                        result.width(width);
                        var me = $(this);
                        setTimeout(function(){
                            me.find('.publish-result-item').css('max-width', '1000px');
                        }, 300);
                    },
                    mouseleave : function(){
                        $(this).find('.publish-result-item').css('max-width', '60px');
                        result.width(180);
                    }
                }, 'li')
                .on({
                    'save.publish' : function(){
                        /*var ids = [], names = [];
                        $(this).find('li').each(function(){
                            ids.push($(this).attr('_id'));
                            names.push($(this).attr('_name'));
                        });
                        if(ids.length){
                            hiddenInput.val(ids.join(','));
                            hiddenNameInput.val(names.join(','));
                        }*/
                    	var res = [];
                    	$(this).find('li').each(function(){
                    		
                    		res.push({
                    			id : $(this).attr('_id'),
                        		name : $(this).attr('_name'),
                        		biaoshi : $(this).attr('_siteid')
                    		});
                    		
                    		
                    	});
                    	
                    	hiddenInput.val(JSON.stringify(res));
                    },
                    'add.publish' : function(event, mydata, myname){
                        var ul = $(this).find('ul').show();
                        $(replaceTpl(tpl, mydata)).appendTo(ul).attr('_name', myname).find('input').prop('checked', true);
                        $(this).find('.publish-nochild').remove();
                        $(this).find('.publish-result-tip').hide();
                        $(this).triggerHandler('save.publish');
                    },
                    'remove.publish' : function(event, id, siteid){
                        $(this).find('li[_id="'+ id +'"]').filter('li[_siteid="' + siteid + '"]').remove();
                        if(!$(this).find('li').length){
                            $(this).find('ul').hide();
                            $(this).find('.publish-result-tip').show();
                        }
                        $(this).triggerHandler('save.publish');
                    },
                    'check.publish' : function(){
         
                        $(this).find('li').each(function(){
                        	innerList.find('li[_id=' + $(this).attr('_id') + ']').filter('li[_siteid="' + $(this).attr('_siteid') + '"]').find('input').prop('checked', true);
                        });
                    }
                });

            result.triggerHandler('save.publish');
            result.triggerHandler('check.publish');

            site
                .on('click', '.publish-site-qiehuan', function(){
                    var ul = site.find('ul');
                    var visible = ul.is(':visible');
                    site.triggerHandler((visible ? 'ulhide' : 'ulshow') + '.publish');
                    result.triggerHandler('check.publish');
                })
                .on('click', '.publish-site-item', function(){
                    if($(this).hasClass('publish-site-current')){
                        return;
                    }
                  
                    site.find('.publish-site-select').removeClass('publish-site-select');
                    $(this).addClass('publish-site-select');
                    $(this).find('input').prop('checked', true);
                    var siteid = $(this).attr('_siteid');
                    var nodevar = $(this).attr('_nodevar');
                    site.find('.publish-site-current').html($(this).attr('_name')).attr('_siteid', siteid);
                    innerList.find('.publish-each').remove();
                    innerList.append(clone.clone());
                    var myurl = replaceTpl(url, {
                        siteid : siteid,
                        nodevar: nodevar,
                        fid : 0
                    });
                   
                    innerList.triggerHandler('ajax.publish', ['site', siteid, myurl]);
                    site.triggerHandler('ulhide.publish');
                })
                .on({
                    'ulshow.publish' : function(){
                        $(this).find('ul').show();
                        list.css('opacity', 0.25);
                    },
                    'ulhide.publish' : function(){
                        $(this).find('ul').hide();
                        list.css('opacity', 1);
                    }
                });
        });
    }

})(jQuery);