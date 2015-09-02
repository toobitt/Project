(function($){
    $.widget('magic.mask', {
        options : {
            infos : null,
            accept : '.mode-item',
            normalClass : 'mask',
            hoverClass : 'mask-on',
            selectClass : 'mask-select',
            save : '',
            cancel : '',
            staticSave : ''
        },

        _create : function(){
            this._createMasks();
        },

        _init : function(){
            this._on({
                'click .mask' : '_click'
            });
        },

        _createMasks : function(){
            var infos = this.options.infos;
            var _this = this;
            $.each(infos, function(i, n){
                _this._createMask(i, n);
            });
        },

        _createMask : function(hash, info){
            var _this = this;
            var op = this.options;
            var mask = $('<div/>').attr({
                'class' : op.normalClass + ' m2o-transition',
                'hash' : hash,
                title : info['name'],
                'data-id' : info['id']
            }).html('<span>' + info['name'] + '_' + info['id'] + '</span>').appendTo(this.element);
            mask.droppable({
                accept : function(ui){
                    return ui.hasClass('mode-item') || ui.hasClass('mode-format');
                },
                hoverClass : op.hoverClass,
                tolerance : 'pointer',
                drop : function(event, ui){
                    var selected = _this.element.find('.' + op.selectClass);
                    if(selected.length && selected[0] != this) return;
                    var modeType = ui.helper.data('type');
                    var modeId = ui.helper.data('id');
                    ui.helper.remove();
                    var hash = $(this).attr('hash');
                    var info = _this.getCellInfo(hash);
                    if(modeType == 'format'){
                        var formatInfo = $.MC.db.mode('getFormat');
                        formatInfo['data_source'] = info['data_source'];
                        formatInfo['input_param'] = info['input_param'];
                        info = formatInfo;
                    }
                    info['cell_mode'] = modeId;
                    _this._save(this, hash, info);
                }
            });
            return mask;
        },

        _update : function(mask, hash, info, noOpen, isPreview){
            var _this = this;
            var loadCallback = $.globalLoad(mask);
            var html = info['static_html'] || info['rended_html'];
            var imgs = {};
            var imgsNumber = 0;
            var tmpDIV = $('<div/>').html(html);
            tmpDIV.find('img').each(function(){
                var src = $(this).attr('src');
                if(src){
                    imgs[src] = false;
                    imgsNumber++;
                }
            });
            tmpDIV.remove();
            var update = function(){
                loadCallback();
                !isPreview && _this.updateCellInfo(hash, info);
                $.iframe.refreshCell(hash, info, 'update');
                !noOpen && _this.openProperty &&_this.openProperty();
            }
            if(!imgsNumber){
                update();
                return;
            }
            var checkImgLoad = function(){
                var all = true;
                $.each(imgs, function(i, n){
                    if(!n){
                        all = false;
                        return false;
                    }
                });
                if(all){
                    update();
                }
            };
            $.each(imgs, function(i, n){
                var img = new Image();
                img.onload = img.onerrer = function(){
                    imgs[i] = true;
                    checkImgLoad();
                }
                img.src = i;
            });
        },

        getCellInfo : function(hash){
            return $.MC.info.cellNew[hash];
        },

        getMask : function(hash){
            return this.element.find('.' + this.options['normalClass'] + '[hash="' + hash + '"]');
        },

        updateCellInfo : function(hash, key, value){
            if(arguments.length == 2){
                $.MC.info.cellNew[hash] = key;
                this.getMask(hash).attr('data-id', key['id']);

            }else{
                $.MC.info.cellNew[hash][key] = value;
            }
        },

        refresh : function(infos){
            var _this = this;
            var root = this.element;
            root.find('.' + this.options['normalClass']).addClass('check');
            $.each(infos, function(hash,  info){
                if(info['hidden'] === true){
                    return;
                }
                var item = root.find('[hash="' + hash + '"]');
                if(!item[0]){
                    item = _this._createMask(hash, info);
                }
                item.css({
                    left : info['left'] + 'px',
                    top : info['top'] + 'px',
                    width : info['width'] - (info['width'] > 2 ? 2 : 0) + 'px',
                    height : info['height'] - (info['height'] > 2 ? 2 : 0) + 'px'
                }).removeClass('check').show();
            });
            //root.find('.check').remove();
            root.find('.check').hide();
        },

        moniMouseenter : function(hash){
            var cell = this.getMask(hash);
            if(!cell.length) return;
            cell.addClass(this.options.hoverClass);
        },

        moniMouseleave : function(){
            this.element.find('.' + this.options.hoverClass).removeClass(this.options.hoverClass);
        },


        moniClick : function(hash, noclick){
            var cell = this.getMask(hash);
            if(cell.length){
                var offset = cell.offset();
                $('body').animate({
                    scrollTop : (offset.top > 100 ? offset.top - 50 : 0) + 'px'
                }, 300);
                !noclick && cell.trigger('click');
            }
        },

        getSelectMask : function(){
            return this.element.find('.' + this.options.selectClass);
        },

        getSelectMaskHash : function(){
            var hashs = [];
            this.getSelectMask().each(function(){
                hashs.push($(this).attr('hash'));
            });
            return hashs;
        },

        refreshPlugin : function(){
            $.MC.dylist && $.MC.dylist.dylist('refreshClick', this.getSelectMaskHash());
        },

        show : function(){
            var doc = $(document);
            this.element.css({
                width : doc.width(),
                height : doc.height()
            });
        },

        hide : function(){
            this.element.css({
                width : 0,
                height : 0
            });
        },

        _destroy : function(){

        }
    });
})(jQuery);