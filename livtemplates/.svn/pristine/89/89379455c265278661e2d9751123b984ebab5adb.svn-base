(function($){
    $.widget('plan.program', {
        options : {
            'dates' : '',
            'channel-id' : 0,
            'ok-url' : '',
            'program-url' : ''
        },

        _create : function(){
            this.make = $('.make-program');
            this.ok = $('.ok-program');
            this.no = $('.no-program');
            this.makeNew = $('.make-new-program');
            this.watch = $('.watch-program');
            this.dropBox = $('#drop-box');
            this.copy = $('.copy-program');
            this.copyOk = $('.copy-btn');
            this.copyInfo = $('.copy-info');
            this.programData = null;
        },

        _init : function(){
            this._on(this.make, {
                'click' : '_make'
            });
            this._on(this.makeNew, {
                'click' : '_makeNew'
            });
            this._on(this.ok, {
                'click' : '_ok'
            });
            this._on(this.no, {
                'click' : '_no'
            });
            this._on(this.copy, {
                'click' : '_copy'
            });
            this._on(this.copyOk, {
                'click' : '_copyOk'
            });

            $('input', this.copyInfo).datepicker();
        },

        _pData : function(data){
            var _this = this;
            var pData = _this.programData = {};
            $.each(data, function(i, n){
                pData[n['schedule_id']] = n;
            });
        },

        _makeNew : function(event){
            var _this = this;
            if(_this.programData){
                _this._make();
                return;
            }
            var guid = $.globalAjaxLoad.bind(event.currentTarget);
            var xhr = $.getJSON(
                _this.options['program-url'],
                {
                    'channel_id' : _this.options['channel-id'],
                    'dates' : _this.options['dates']
                },
                function(json){
                    _this._pData(json[0]);
                }
            ).guid = guid;

            $.when(xhr).done(function(){
                _this._make();
            });
        },

        _make : function(){
            var _this = this;
            _this._toggle(1);
            var root = _this.element.empty();
            var dropBox = this.dropBox;
            var pData = _this.programData;
            dropBox.find('.drop-item').each(function(){
                var clone = $(this).clone();
                var time = clone.find('.drop-time-box');
                time.replaceWith(time.children());
                var info = clone.find('.drop-info');
                info.replaceWith(info.find('.drop-content'));
                clone.find('.drop-close, .drop-suo, .drop-tip').remove();
                var title = clone.find('.drop-title').prop('contentEditable', true);
                var id = clone.attr('_id');
                pData && $.type(pData[id]) != 'undefined' && title.text(pData[id]['theme']);
                root.append(clone);
            });
            var dPos = dropBox.offset();
            root.css({
                opacity : 0,
                left : dPos.left + 'px',
                top : dPos.top + 'px',
                width : dropBox.width() + 'px',
                height : dropBox.height() + 'px'
            }).show();

            dropBox.animate({
                opacity : 0
            }, 500, function(){
                root.animate({
                    opacity : 1
                }, 500);
            });
        },

        _ok : function(){
            var _this = this;
            var ids = [];
            var titles = [];
            _this.element.find('.drop-item').each(function(){
                ids.push($(this).attr('_id'));
                titles.push($.trim($(this).find('.drop-title').text()));
            });
            $.post(
                _this.options['ok-url'],
                {
                    'channel_id' : _this.options['channel-id'],
                    'dates' : _this.options['dates'],
                    'ids[]' : ids,
                    'theme[]' : titles
                },
                function(json){
                    alert('保存成功！');
                    _this.make.remove();
                    _this.make = null;
                    _this.makeNew.add(_this.watch).show();
                    _this.programData = null;
                    _this._no();
                },
                'json'
            );
        },

        _no : function(){
            var _this = this;
            var dropBox = this.dropBox;
            _this._toggle(0);
            _this.element.animate({
                opacity : 0
            }, 500, function(){
                $(this).hide().empty();
                dropBox.animate({
                    'opacity' : 1
                }, 500);
            });
        },

        _copy : function(){
            this.copyInfo[this.copyInfo.is(':visible') ? 'hide' : 'show']();
        },

        _copyOk : function(event){
            var _this = this;
            var date = this.copyInfo.find('input').val();
            if(!date) return;
            var disTime = dateDiff(date, today);
            if(disTime <= 0){
                _this.copyInfo.myTip({
                    string : '时间必须是今天以后',
                    dtop : 100,
                    delay : 2000,
                    color : 'red'
                });
                return;
            }
            var loadCB = $.globalLoad(event.currentTarget);
            $('#drop-box').plans('copy', date, disTime, function(){
                _this.copyInfo.myTip({
                    string : '复制成功！',
                    dtop : 50,
                    delay : 2000
                });
                loadCB();
                _this.copyInfo.hide();
            });

        },

        _toggle : function(state){
            if(this.make && this.make[0]){
                this.make[state ? 'hide' : 'show']();
            }else{
                this.makeNew.add(this.watch)[state ? 'hide' : 'show']();
            }
            this.ok.add(this.no)[state ? 'show' : 'hide']();
        },

        show : function(){
            this.dropBox.find('.drop-item').length > 0 && this._toggle(0);
        },

        hide : function(){
            this.make.add(this.makeNew).hide();
        },

        _destroy : function(){

        }
    });

})(jQuery);