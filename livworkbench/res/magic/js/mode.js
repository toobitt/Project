(function($){
    $.widget('magic.mode', {
        options : {
            modes : null,
            currentModes : null,
            eachNum : 10,
            'cat-tpl' : null,
            'cat-tname' : 'cat-template',
            'list-tpl' : null,
            'list-tname' : 'list-template',

            on : 'open'
        },

        _create : function(){
            $.template(this.options['cat-tname'], this.options['cat-tpl']);
            $.template(this.options['list-tname'], this.options['list-tpl']);

            this.cat = this.element.find('.mode-cat');
            this.list = this.element.find('.mode-list');
            this.pn = this.element.find('.mode-pn');
            this.pnP = this.pn.find('.mode-p');
            this.pnN = this.pn.find('.mode-n');

            this.smCell = this.element.find('.shenmi-cell');

            this.btn = this.element.find('.mode-btn');
            mainConfig['which'] != 'k' && this.btn.show();

            this._createCat();
            this._createList();
        },

        _createCat : function(){
            var cats = this.options.cats;
            var defaultCat;
            $.extend(cats, (defaultCat = {
                0 : {
                    id : 0,
                    name : '全部样式'
                }
            }));
            var data = {
                list : cats,
                defaultId : defaultCat[0]['id'],
                defaultCat : defaultCat[0]['name']
            };
            $.tmpl(this.options['cat-tname'], data).appendTo(this.cat);

            this.current = this.element.find('.mode-cat-current');
            this.currentId = 0;
        },

        _createList : function(modes){
            var _this = this;
            this.list.empty();
            this.currentModes = modes = modes || this.options.modes;
            var num = 0;
            modes && $.each(modes, function(i, n){
                num++;
            });
            this.page = 1;
            this.totalPage = Math.ceil(num / this.options.eachNum);
            this._createEachPage();
            this._createPN();
        },

        _destroyDrag : function(){
            this.list.find('.mode-item').draggable('destroy');
        },

        _createEachPage : function(){
            this._destroyDrag();
            this.list.empty();
            var _this = this;
            var num = this.options.eachNum;
            var page = this.page;
            var start = (page - 1) * num;
            var end = page * num;
            var modes = this.currentModes;
            if(modes.length){
                for(var i = start; i < end; i++){
                    modes[i] && _this._createItem(modes[i]);
                }
            }
        },

        _createPN : function(){
            this.pn.hide();
            if(this.totalPage > 1){
                this.pn.show();
                var on = 'on';
                this.pnP.add(this.pnN).removeClass(on);
                this.page > 1 && this.pnP.addClass(on);
                this.page < this.totalPage && this.pnN.addClass(on);
            }
        },

        _createItem : function(info){
            var item = $.tmpl(this.options['list-tname'], {
                id : info['id'],
                img : info['indexpic'] ? $.globalImgUrl(info['indexpic'], 'x120') : '',
                name : info['title']
            }).appendTo(this.list);
            this._bindDrag(item);
        },

        _bindDrag : function(item){
            item.draggable({
                helper : 'clone',
                appendTo : 'body',
                zIndex : 1000,
                revert : true,
                snapTolerance : false,
                start : function(){
                    $.MC.mb.mask('show');
                },
                stop : function(){
                    $.MC.mb.mask('hide');
                }
            });
        },

        _init : function(){
            //this.show();

            this._on({
                'click .mode-search-btn' : '_searchBtn',
                'click .mode-cat li' : '_selectCat',
                'keyup .mode-search-text' : '_search',
                'click .mode-pn span' : '_clickPN',
                'click .mode-btn' : '_btn'
            });

            this.cat.on({
                mouseenter : function(){
                    var $this = $(this);
                    var timer = $this.data('timer');
                    if(timer) clearTimeout(timer);
                    $this.data('timer', setTimeout(function(){
                        $this.addClass('on');
                    }, 300));

                },
                mouseleave : function(){
                    var $this = $(this);
                    var timer = $this.data('timer');
                    if(timer) clearTimeout(timer);
                    $this.data('timer', 0);
                    $this.removeClass('on');
                }
            });

            this._initFormat();

            this._bindShenmiDrop();
        },

        show : function(){
            this.element.addClass(this.options.on);
            this.open = true;
            this.btn.html(this.btn.data('close'));
        },

        hide : function(){
            this.element.removeClass(this.options.on);
            //this.kongShenmiCell();
            this.open = false;
            this.btn.html(this.btn.data('open'));
        },

        _btn : function(){
            if(this.open){
                this.hide();
            }else{
                this.show();
            }
        },

        clickPN : function(index){
            this.element.find('.mode-pn span').eq(index).trigger('click');
        },

        _clickPN : function(event){
            var target = $(event.currentTarget);
            if(!target.hasClass('on')) return;
            this.page += parseInt(target.attr('which'));
            this._createEachPage();
            this._createPN();
        },

        _searchBtn : function(event){
            var targetParent = $(event.currentTarget).parent();
            var on = 'on';
            var state = targetParent.hasClass(on);
            targetParent[state ? 'removeClass' : 'addClass'](on);
            state && targetParent.find('input').val('');
        },

        _search : function(event){
            var target = $(event.currentTarget);
            var name = $.trim(target.val());
            if(!name && !name.length){
                this.cat.find('li[data-id="0"]').trigger('click');
                return;
            }
            var modes = [];
            $.each(this.options.modes, function(i, n){
                if((n.title || '').indexOf(name) != -1){
                    modes.push(n);
                }
            });
            this._createList(modes);
            this._current('-1', '查询结果');
        },

        _selectCat : function(event){
            var target = $(event.currentTarget);
            var id = target.data('id');
            var name = target.html();
            if(id == this.currentId){
                return;
            }
            this._current(id, name);
            this._refreshList(id);
            this.cat.removeClass('on');
        },

        _current : function(id, name){
            this.current.attr('data-id', id).html(name);
            this.currentId = id;
        },

        _refreshList : function(id){
            id = id || this.currentId;
            if(!id){
                return this._createList();
            }
            var modes = [];
            $.each(this.options.modes, function(i, n){
                if(n.sort_id == id){
                    modes.push(n);
                }
            });
            this._createList(modes);
        },

        getModeInfo : function(id){
            var info;
            $.each(this.options.modes, function(i, n){
                if(n['id'] == id){
                    info = n;
                    return false;
                }
            });
            return info;
        },

        _initFormat : function(){
            var formatItem = this.formatItem = this.element.find('.mode-format');
            this._bindDrag(formatItem);
        },

        setFormat : function(name, info){
            this.formatInfo = $.extend({}, info);
            this.formatItem.attr('data-id', this.formatInfo['cell_mode']).attr('data-type', 'format');
            this.formatItem.find('span').html(name).attr('data-id', this.formatInfo['cell_mode']).attr('title', name);
            this.formatItem.show();
            $.MC.mb.mask('removeClick');
        },

        getFormat : function(){
            return this.formatInfo;
        },

        shenmiCell : function(cellTitle, cellHash){
            this.smCell.html(this.smCell.attr('pre') + cellTitle.join(',')).css('color', 'red');
            var _this = this;
            setTimeout(function(){
                _this.smCell.stop().animate({
                    color : '#333'
                }, 1000);
            }, 0);

            this.currentCellHash = cellHash;
        },

        kongShenmiCell : function(){
            this.smCell.html(this.smCell.attr('default-title'));
            this.currentCellHash = null;
        },

        _bindShenmiDrop : function(){
            var _this = this;
            this.element.find('.shenmi-box').droppable({
                accept : function(ui){
                    return ui.hasClass('mode-item') || ui.hasClass('mode-format');
                },
                hoverClass : 'on',
                tolerance : 'pointer',
                drop : function(event, ui){
                    if(!_this.currentCellHash) return;
                    var modeType = ui.helper.data('type');
                    var modeId = ui.helper.data('id');
                    ui.helper.remove();
                    $.each(_this.currentCellHash, function(i, n){
                        $.MC.mb.mask('dropEventDo', n, modeType, modeId);
                    });
                },
                over : function(event, ui){
                    if(_this.currentCellHash) return;
                    $.MC.mb.mask('dropEventOver');
                },
                out : function(){
                    if(_this.currentCellHash) return;
                    $.MC.mb.mask('dropEventEnd');
                }
            });
        },

        _destroy : function(){

        }
    });
})(jQuery);