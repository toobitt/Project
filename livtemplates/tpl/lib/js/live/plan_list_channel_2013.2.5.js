(function($){
    $.widget('plan.channel', {
        options : {

            'cat-url' : 'run.php?mid='+ gMid +'&a=get_channel_info',
            'list-url' : 'run.php?mid='+ gMid +'&a=get_dvr_info&channel_id={{channel_id}}&dates={{dates}}&stime={{stime}}',

            'channel-cat-tpl' : '#channel-cat-tpl',
            'channel-list-tpl' : '#channel-list-tpl',

            date : ''
        },

        _create : function(){
            var root = this.element;
            this.types = {
                'channelCat' : {
                    url : this.options['cat-url'],
                    name : '#channel-box',
                    tpl : $(this.options['channel-cat-tpl']).html()
                },
                'channelList' : {
                    url : this.options['list-url'],
                    name : '.channel-list',
                    tpl : $(this.options['channel-list-tpl']).html()
                }
            };
            var _this = this;
            $.each(this.types, function(i, n){
                _this[i] = root.find(n['name']);
            });

            if(this.options.date){
                var date = new Date();
                this.options.date = [date.getFullYear(), date.getMonth(), date.getDate()].join('-');
            }

            this._ajax('channelCat');
        },

        _init : function(){
            /*this._on({
             'click .channel-cat-item' : '_catClick'
             });*/
        },

        _beforeAjax :function(type){
            this[type].html('<img src="' + RESOURCE_URL + 'loading2.gif" style="width:30px;"/>');
        },

        _ajax : function(type, urlPrama){
            this._beforeAjax(type);
            var _this = this;
            $.getJSON(
                this['_' + type + 'Url'](urlPrama),
                function(json){
                    _this._afterAjax(type, json);
                }
            );
        },

        _afterAjax : function(type, info){
            this[type].html('1111');
            console.log(this[type]);
        },

        _catClick : function(event){
            var target = $(event.currentTarget);
            var channelId = target.attr('_id');
            this._ajax('channelList', {
                channel_id : channelId
            });
        },

        _channelCatUrl : function(){
            return this.types['channelCat']['url'];
        },

        _channelCatFilterInfo : function(info){
            info = info[0];
            return {
                list : info
            };
        },

        _channelListUrl : function(urlPrama){
            return this._replace(this.types['channelList']['url'], urlPrama);
        },

        _channelListFilterInfo : function(info){

        },

        _replace : function(tpl, data){
            return tpl.replace(/{{([a-z_]+)}}/g, function(all, match){
                var val = data[match];
                return typeof val != 'undefined' ? val : '';
            });
        },

        _destroy : function(){

        }
    });
})(jQuery);