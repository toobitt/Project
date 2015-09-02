(function($){
    $.widget('plan.graph', {
        options : {
            infos : [],
            plans : '#plan-box',
            'graph-item' : '.graph-item',
            'graph-end-time' : '.graph-end-time',
            'graph-item-tpl' : '#graph-item-tpl'
        },

        _create : function(){
            this.plans = $(this.options['plans']);
            this.tpl = $(this.options['graph-item-tpl']).html();
            this.oneDayTime = 24 * 60 * 60 * 1000;
        },

        _init : function(){

        },

        /*_refresh : function(){
            this._empty();
            var _this = this;
            var infos = this.options.infos;
            if(infos){
                $.each(infos, function(i, n){
                    _this._make(n, i);
                });
            }
        }, */

        _setOptions : function(){
            this._superApply(arguments);
            this._refresh();
        },

        _make : function(info, index){
            var parentWidth = this.element.width();
            if(!this.planHeight){
                var planItem = $('.plan-item:first');
                this.planHeight = planItem.height();
                this.planOuterHeight = planItem.outerHeight(true);
            }

            var _this = this;
            $($.tmpl(this.tpl, {
                title : info['title'],
                end : info['end'],
                hash : info['hash']
            }))
                .appendTo(this.element)
                .css({
                    width : parentWidth * (info['duration'] / this.oneDayTime) + 'px',
                    height : this.planHeight + 'px',
                    top : this.planOuterHeight * index + 'px'
                })
                .resizable({
                    handles : 'e, w',
                    containment : 'parent',
                    start : function(){

                    },

                    resize : function(event, ui){
                        _this._resize(ui, 'resize');
                    },

                    stop : function(event, ui){
                        _this._resize(ui, 'stop');
                    }
                });
        },

        _resize : function(ui, type){
            var nowWidth = ui.size.width;
            var hash = ui.originalElement.attr('_hash');
            var returnTime = type == 'resize' ? true : false;
            var time = this.plans.plans('change', hash, nowWidth / this.element.width() * this.oneDayTime, returnTime);
            if(returnTime){
                ui.originalElement.find(this.options['graph-end-time']).html(time);
            }
        },

        _empty : function(condition){
            this._getItems(condition).resizable('destroy').remove();
        },

        _getItems : function(condition){
            var all = this.element.find(this.options['graph-item']);
            if(condition){
                all = all.filter(condition);
            }
            return all;
        },

        _refresh : function(infos){
            var _this = this;
            this._getItems().each(function(i, n){
                _this._refreshItem($(this), i, infos);
            });
        },

        _refreshItem : function(item, index, infos){
            var hash = item.attr('_hash');
            var info = infos[hash];
            var endName = this.options['graph-end-time'];
            item.css({
                top : index * this.planOuterHeight + 'px'
            });
            item.find(endName).html(info['end']);
        },


        refresh : function(infos){
            this._refresh(infos);
        },

        sort : function(hashs, infos){
            var _this = this;
            $.each(hashs, function(i, n){
                _this._refreshItem(_this._getItems('[_hash="'+ n +'"]'), i, infos);
            });
            hashs = hashs.reverse();
            $.each(hashs, function(i, n){
                _this._getItems('[_hash="'+ n +'"]').prependTo(_this.element);
            });
        },

        add : function(info){
            this._make(info, this._getItems().length);
        },

        remove : function(hash, infos){
            this._empty('[_hash="'+ hash +'"]');
            this._refresh(infos);
        },

        empty : function(){
            this._empty();
        },

        _destroy : function(){

        }
    });
})(jQuery);