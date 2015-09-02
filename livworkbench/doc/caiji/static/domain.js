$(function($){
    $.widget('caiji.domain', {
        options : {
            tpl : '',
            tname : 'config-each',
            url : ''
        },

        _create : function(){
            $.template(this.options.tname, this.options.tpl);
        },

        _init : function(){
            this._on({
                'click .add' : '_add',
                'click .save' : '_save'
            })
        },

        _add : function(event){
            this._insert();
        },

        _insert : function(data){
            $.tmpl(this.options.tname, data || {}).insertBefore(this.element.find('li:last'));
            this.element.find('>li').length > 1 && this.element.find('.save').css('display', 'inline-block');
        },

        _save : function(event){
            var allConfigs = [];
            this.element.find('li:not(.option)').each(function(){
                var config = {};
                var pass = false;
                $(this).find('input, select').each(function(){
                    var name = $(this).attr('name');
                    var val = $.trim($(this).val());
                    val && (pass = true);
                    config[name] = val;
                });
                if(pass){
                    config['name'] = globalName;
                    allConfigs.push(config);
                }
            });
            if(!allConfigs.length) return;
            var target = $(event.currentTarget);
            $.post(
                this.options.url,
                {domain : domain, data : JSON.stringify(allConfigs)},
                function(){
                    target.myTip({
                        string : '成功',
                        cname : 'on',
                        delay : 1000,
                        dtop : 0,
                        dleft : 0
                    });
                }
            );
        },

        init : function(configs){
            this._insert(configs);
        },

        _destroy : function(){

        }
    });

    $('.box').domain({
        tpl : $('#tpl').html(),
        url : '?a=saveDomain'
    }).domain('init', configs);
});