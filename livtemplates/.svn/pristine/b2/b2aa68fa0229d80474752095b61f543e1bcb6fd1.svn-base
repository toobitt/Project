(function($){
    $.widget('plan.file', $.plan.catlist, {
        options : {
            'default-title' : '全部内容',
            'default-type' : 'file'
        },

        _listFilter : function(json){
            var _this = this;
            var list = [];
            $.each(json, function(i, n){
                list.push({
                    id : n.id,
                    src : n.img,
                    title : n.title,
                    duration : n.duration
                });
                _this._addCacheJSON(n);
            });
            return list;
        },

        _jsonChange : function(json){
            json['duration_format'] = json['duration'];
            json['duration'] = parseInt(json['toff'] / 1000);
        }
    });
})(jQuery);